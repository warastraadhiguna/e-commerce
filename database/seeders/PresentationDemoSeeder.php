<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PartnerDiscountStatus;
use App\Enums\UserRole;
use App\Models\CompanyProfile;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/** Explicitly invoked demo data; never part of the production DatabaseSeeder. */
class PresentationDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            if (! Product::query()->exists()) {
                $this->call(ProductCatalogSeeder::class);
            }

            $this->prepareTemplateCatalog();

            $buyers = collect([
                ['email' => 'buyer.demo@example.test', 'name' => 'Pembeli Demo', 'role' => UserRole::Buyer, 'phone' => '081234567890'],
                ['email' => 'mitra.demo@example.test', 'name' => 'Toko Sejahtera Demo', 'role' => UserRole::Mitra, 'phone' => '081234567891'],
            ])->map(function (array $attributes): User {
                $user = User::query()->firstOrCreate(['email' => $attributes['email']], [
                    'name' => $attributes['name'], 'role' => $attributes['role'],
                    'password' => 'Demo12345!', 'is_active' => true,
                    'phone' => $attributes['phone'], 'email_verified_at' => now(),
                    'partner_discount_status' => PartnerDiscountStatus::Approved,
                    'minimum_turnover_for_discount' => 0,
                ]);
                ShippingAddress::query()->firstOrCreate(['user_id' => $user->id, 'label' => 'Alamat Demo'], [
                    'recipient_name' => $user->name, 'phone' => $user->phone,
                    'address_line' => 'Jl. Contoh No. 10 (alamat untuk demonstrasi)',
                    'district' => 'Banyumanik', 'city' => 'Semarang', 'province' => 'Jawa Tengah',
                    'postal_code' => '50269', 'is_default' => true, 'sort_order' => 1,
                ]);

                return $user;
            });

            $products = Product::query()->activePublished()->orderBy('id')->take(6)->get();
            if ($products->isEmpty()) {
                return;
            }

            foreach (range(1, 8) as $index) {
                $user = $buyers[($index - 1) % 2];
                $product = $products[($index - 1) % $products->count()];
                $quantity = $index % 3 + 1;
                $price = $product->displayPriceFor($user);
                $date = now()->startOfMonth()->addDays(min($index * 2 - 2, now()->day - 1))->setTime(9 + $index, 15);
                $status = match (true) {
                    $index <= 4 => OrderStatus::Completed,
                    $index <= 6 => OrderStatus::Processing,
                    $index === 7 => OrderStatus::Pending,
                    default => OrderStatus::Cancelled,
                };
                $order = Order::query()->firstOrCreate(['order_number' => 'DEMO-'.now()->format('Ym').'-'.str_pad((string) $index, 3, '0', STR_PAD_LEFT)], [
                    'user_id' => $user->id, 'status' => $status, 'customer_name' => $user->name,
                    'customer_email' => $user->email, 'customer_phone' => $user->phone,
                    'customer_role' => $user->role->value, 'uses_partner_pricing' => $user->isMitra(),
                    'shipping_recipient_name' => $user->name, 'shipping_phone' => $user->phone,
                    'shipping_address_line' => 'Jl. Contoh No. 10 (data demo)', 'shipping_city' => 'Semarang',
                    'shipping_province' => 'Jawa Tengah', 'item_count' => 1, 'total_quantity' => $quantity,
                    'subtotal_amount' => $price * $quantity, 'total_amount' => $price * $quantity,
                    'checked_out_at' => $date, 'payment_status' => $index <= 5 ? 'paid' : 'unpaid',
                    'paid_at' => $index <= 5 ? $date : null, 'payment_method' => $index <= 5 ? 'transfer' : null,
                    'payment_reference' => $index <= 5 ? 'DEMO-TRANSFER-'.$index : null,
                    'internal_notes' => 'Data contoh presentasi, bukan transaksi nyata. Tidak mengurangi stok.',
                ]);
                if ($order->wasRecentlyCreated) {
                    $order->items()->create([
                        'product_id' => $product->id, 'product_sku' => $product->sku,
                        'product_slug' => $product->slug, 'product_name' => $product->name,
                        'brand_name' => $product->brand?->name, 'category_name' => $product->category?->name,
                        'measurement_summary' => $product->unit_summary, 'quantity' => $quantity,
                        'unit_price' => $price, 'line_total' => $price * $quantity,
                    ]);
                }
            }

            foreach ([['Kemasan pesanan demo', 'operational', 45000], ['Promosi katalog demo', 'marketing', 75000], ['Pengiriman pesanan demo', 'shipping', 30000]] as $index => [$description, $category, $amount]) {
                Expense::query()->firstOrCreate(['reference' => 'DEMO-EXP-'.now()->format('Ym').'-'.$index], [
                    'description' => $description, 'category' => $category, 'amount' => $amount,
                    'expense_date' => now()->startOfMonth()->addDays(min($index * 3, now()->day - 1)),
                    'notes' => 'Data contoh presentasi, bukan pengeluaran nyata.',
                ]);
            }
        });
    }

    private function prepareTemplateCatalog(): void
    {
        $names = [
            'Drone Inspire Dual Controller' => 'Headphone Meito Studio',
            'Konsol Gamepad Dbox' => 'Speaker XailStation Portable',
            'Headphone Air Bass' => 'Tablet Sanai Air',
            'Tablet Meito Plus' => 'Smartphone Meito M5',
            'Speaker Portable BassGo' => 'Smartphone Sanai S9',
        ];

        foreach (Product::query()->where('sku', 'like', 'DUMMY-PRD-%')->get() as $product) {
            if ((float) $product->price < 20000) {
                $product->price = (float) $product->price * 100;
                $product->cost_price = (float) $product->cost_price * 100;
                if ($product->compare_at_price) {
                    $product->compare_at_price = (float) $product->compare_at_price * 100;
                }
                $product->partner_discount_price = round((float) $product->price * .92, 2);
            }
            if (isset($names[$product->name])) {
                $product->name = $names[$product->name];
                $product->short_name = $product->name;
                $product->excerpt = $product->name.' untuk kebutuhan sehari-hari, kerja, dan hiburan.';
                $product->description = $product->excerpt.' Periksa ketersediaan stok sebelum memesan.';
            } elseif (str_contains((string) $product->description, 'dummy')) {
                $product->description = $product->excerpt.' Produk pilihan untuk kebutuhan harian, kerja, dan hiburan.';
            }
            $product->save();
        }

        $profile = CompanyProfile::query()->first();
        if ($profile?->company_name === 'Laravel') {
            $profile->update([
                'company_name' => 'Limupa Store',
                'short_description' => 'Pilihan elektronik dan gadget untuk kebutuhan sehari-hari. Belanja praktis dengan katalog lengkap dan harga khusus mitra.',
                'long_description' => 'Limupa Store menghubungkan pelanggan dan mitra dengan pilihan produk elektronik, gadget, dan aksesori. Jelajahi katalog, simpan produk favorit, dan pantau pesanan Anda dari satu akun.',
                'description' => 'Pilihan elektronik dan gadget untuk kebutuhan sehari-hari.',
            ]);
        }
    }
}
