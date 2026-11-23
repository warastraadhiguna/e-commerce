<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\CheckoutStoreRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Support\CartManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartManager $cart,
    ) {}

    public function show(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        abort_unless($user?->isShopper(), 403, 'Halaman checkout hanya untuk buyer atau mitra.');

        $cartItems = $this->cart->items($user);

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('public_feedback', [
                    'icon' => 'info',
                    'title' => 'Keranjang kosong',
                    'text' => 'Tambahkan produk ke keranjang sebelum melakukan checkout.',
                ]);
        }

        $shippingAddresses = $user->shippingAddresses()->get();
        $selectedShippingAddress = $shippingAddresses->firstWhere('is_default', true) ?: $shippingAddresses->first();

        return view('checkout', [
            'cartItems' => $cartItems,
            'cartSubtotalLabel' => $this->cart->subtotalLabel($user),
            'shippingAddresses' => $shippingAddresses,
            'selectedShippingAddressId' => old('shipping_address_id', $selectedShippingAddress?->getKey()),
            'checkoutNotes' => old('user_notes', ''),
        ]);
    }

    public function store(CheckoutStoreRequest $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user?->isShopper(), 403, 'Checkout hanya untuk buyer atau mitra.');

        $lines = $this->cart->lines();

        if ($lines === []) {
            return redirect()
                ->route('cart.index')
                ->with('public_feedback', [
                    'icon' => 'info',
                    'title' => 'Keranjang kosong',
                    'text' => 'Tambahkan produk ke keranjang sebelum melakukan checkout.',
                ]);
        }

        /** @var ShippingAddress $shippingAddress */
        $shippingAddress = $user->shippingAddresses()
            ->findOrFail((int) $request->validated('shipping_address_id'));

        $userNotes = trim((string) $request->validated('user_notes', ''));
        $order = null;

        DB::transaction(function () use ($user, $lines, $shippingAddress, $userNotes, &$order): void {
            $productIds = collect($lines)
                ->keys()
                ->map(fn ($id): int => (int) $id)
                ->sort()
                ->values()
                ->all();

            $products = Product::query()
                ->with([
                    'brand:id,name',
                    'category:id,name,parent_id',
                    'measurementUnit:id,name,symbol',
                ])
                ->activePublished()
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $preparedItems = [];
            $subtotalAmount = 0.0;
            $totalQuantity = 0.0;

            foreach ($lines as $productId => $quantity) {
                $product = $products->get((int) $productId);
                $quantity = round((float) $quantity, 3);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'checkout' => 'Salah satu produk di keranjang sudah tidak tersedia. Muat ulang keranjang lalu coba lagi.',
                    ]);
                }

                $minimumQuantity = max((float) $product->min_order_quantity, 1);

                if ($product->is_catalog_only) {
                    throw ValidationException::withMessages(['checkout' => "Harga dan stok {$product->name} belum dikonfirmasi. Produk ini belum dapat dibeli."]);
                }

                if ($quantity < $minimumQuantity) {
                    throw ValidationException::withMessages([
                        'checkout' => "Jumlah untuk {$product->name} di bawah minimum order.",
                    ]);
                }

                if ((float) $product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'checkout' => "Stok {$product->name} tidak mencukupi untuk checkout ini.",
                    ]);
                }

                $unitPrice = $product->displayPriceFor($user);
                $lineTotal = round($unitPrice * $quantity, 2);

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];

                $subtotalAmount += $lineTotal;
                $totalQuantity += $quantity;
            }

            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->getKey(),
                'shipping_address_id' => $shippingAddress->getKey(),
                'status' => OrderStatus::Pending,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'customer_role' => $user->role->value,
                'uses_partner_pricing' => $user->isEligibleForPartnerPricing(),
                'shipping_label' => $shippingAddress->label,
                'shipping_recipient_name' => $shippingAddress->recipient_name ?: $user->name,
                'shipping_phone' => $shippingAddress->phone ?: $user->phone,
                'shipping_address_line' => $shippingAddress->address_line,
                'shipping_district' => $shippingAddress->district,
                'shipping_city' => $shippingAddress->city,
                'shipping_province' => $shippingAddress->province,
                'shipping_postal_code' => $shippingAddress->postal_code,
                'shipping_notes' => $shippingAddress->notes,
                'item_count' => count($preparedItems),
                'total_quantity' => round($totalQuantity, 3),
                'subtotal_amount' => round($subtotalAmount, 2),
                'total_amount' => round($subtotalAmount, 2),
                'user_notes' => $userNotes !== '' ? $userNotes : null,
                'checked_out_at' => now(),
            ]);

            foreach ($preparedItems as $preparedItem) {
                /** @var Product $product */
                $product = $preparedItem['product'];
                $quantity = (float) $preparedItem['quantity'];

                $order->items()->create([
                    'product_id' => $product->getKey(),
                    'product_sku' => $product->sku,
                    'product_slug' => $product->slug,
                    'product_name' => $product->name,
                    'brand_name' => $product->brand?->name,
                    'category_name' => $product->category?->full_name ?: $product->category?->name,
                    'measurement_summary' => $product->unit_summary,
                    'quantity' => $quantity,
                    'unit_price' => $preparedItem['unit_price'],
                    'line_total' => $preparedItem['line_total'],
                ]);

                $product->forceFill([
                    'stock_quantity' => round((float) $product->stock_quantity - $quantity, 3),
                ])->save();
            }

            if ($user->isMitra()) {
                $user->increment('purchase_turnover_total', round($subtotalAmount, 2));
            }
        }, 5);

        $this->cart->clear();

        return redirect()
            ->route('orders.show', $order)
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Checkout berhasil',
                'text' => 'Pesanan berhasil dibuat dan stok sudah diperbarui.',
            ]);
    }

    protected function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
