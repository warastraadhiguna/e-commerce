<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CartManager;
use App\Support\WishlistManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartManager $cart,
        protected WishlistManager $wishlist,
    ) {}

    public function index(): View
    {
        return view('cart', [
            'cartItems' => $this->cart->items(request()->user()),
            'cartSubtotalLabel' => $this->cart->subtotalLabel(request()->user()),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);
        abort_if($product->is_catalog_only, 422, 'Produk ini masih berupa katalog informasi. Harga dan stok belum dikonfirmasi.');

        $user = $request->user();

        abort_unless($user && $user->isShopper(), 403, 'Hanya buyer atau mitra yang bisa menambahkan produk ke keranjang.');

        $minimumQuantity = max((float) $product->min_order_quantity, 1);
        $requestedQuantity = $this->cart->quantityFor($product) + (float) $request->input('quantity', $minimumQuantity);
        $quantity = $this->resolveValidQuantity($request, $product, $minimumQuantity, $requestedQuantity);

        if ($quantity instanceof RedirectResponse) {
            return $quantity;
        }

        $this->cart->setQuantity($product, $quantity);
        $this->wishlist->remove($product);

        return redirect()
            ->to($this->safeRedirectTo($request, route('cart.index')))
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Masuk ke keranjang',
                'text' => 'Produk berhasil ditambahkan ke keranjang belanja.',
            ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);
        abort_if($product->is_catalog_only, 422, 'Produk katalog informasi tidak dapat dibeli.');

        $user = $request->user();

        abort_if($user?->isManagementUser(), 403, 'Akun admin/superadmin tidak digunakan untuk pembelian publik.');

        $minimumQuantity = max((float) $product->min_order_quantity, 1);
        $validated = $request->validate([
            'quantity' => ['nullable', 'numeric', 'gt:0'],
            'adjustment' => ['nullable', 'in:-1,1'],
        ]);
        $baseQuantity = (float) ($validated['quantity'] ?? $this->cart->quantityFor($product) ?: $minimumQuantity);
        $adjustment = (float) ($validated['adjustment'] ?? 0);
        $quantity = $this->resolveValidQuantity(
            $request,
            $product,
            $minimumQuantity,
            max($baseQuantity + $adjustment, $minimumQuantity),
        );

        if ($quantity instanceof RedirectResponse) {
            return $quantity;
        }

        $this->cart->setQuantity($product, $quantity);

        return redirect()
            ->to($this->safeRedirectTo($request, route('cart.index')))
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Jumlah diperbarui',
                'text' => 'Jumlah produk di keranjang berhasil diperbarui.',
            ]);
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return redirect()
            ->to($this->safeRedirectTo($request, route('cart.index')))
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Keranjang diperbarui',
                'text' => 'Produk berhasil dihapus dari keranjang.',
            ]);
    }

    protected function safeRedirectTo(Request $request, string $fallback): string
    {
        $redirect = trim((string) $request->input('redirect_to', ''));

        if ($redirect === '' || ! str_starts_with($redirect, '/') || str_starts_with($redirect, '//')) {
            return $fallback;
        }

        return $redirect;
    }

    protected function resolveValidQuantity(Request $request, Product $product, float $minimumQuantity, ?float $requestedQuantity = null): float|RedirectResponse
    {
        if ($requestedQuantity === null) {
            $validated = $request->validate([
                'quantity' => ['nullable', 'numeric', 'gt:0'],
            ]);
            $requestedQuantity = (float) ($validated['quantity'] ?? $minimumQuantity);
        }

        $quantity = max($requestedQuantity, $minimumQuantity);

        if ((float) $product->stock_quantity <= 0) {
            return redirect()
                ->to($this->safeRedirectTo($request, route('cart.index')))
                ->with('public_feedback', [
                    'icon' => 'error',
                    'title' => 'Stok habis',
                    'text' => 'Produk ini sedang tidak tersedia untuk ditambahkan ke keranjang.',
                ]);
        }

        if ($quantity > (float) $product->stock_quantity) {
            return redirect()
                ->to($this->safeRedirectTo($request, route('cart.index')))
                ->with('public_feedback', [
                    'icon' => 'error',
                    'title' => 'Jumlah melebihi stok',
                    'text' => 'Jumlah yang diminta melebihi stok yang tersedia.',
                ]);
        }

        return $quantity;
    }
}
