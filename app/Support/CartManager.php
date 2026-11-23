<?php

namespace App\Support;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class CartManager
{
    public const SESSION_KEY = 'cart_items';

    public const COOKIE_KEY = 'cart_items';

    protected const COOKIE_LIFETIME_MINUTES = 43200;

    public function __construct(
        protected Session $session,
    ) {}

    public function lines(): array
    {
        $sessionLines = $this->sanitizeLines($this->session->get(self::SESSION_KEY, []));

        if ($sessionLines !== []) {
            return $sessionLines;
        }

        $cookieLines = $this->sanitizeLines($this->readCookieLines());

        if ($cookieLines !== []) {
            $this->session->put(self::SESSION_KEY, $cookieLines);
        }

        return $cookieLines;
    }

    public function ids(): array
    {
        return array_map('intval', array_keys($this->lines()));
    }

    public function quantityFor(Product|int $product): float
    {
        $productId = $product instanceof Product ? $product->getKey() : (int) $product;

        return (float) ($this->lines()[$productId] ?? 0);
    }

    public function totalQuantity(): float
    {
        return (float) collect($this->lines())->sum();
    }

    public function badgeCount(): string
    {
        return $this->formatQuantity($this->totalQuantity());
    }

    public function contains(Product|int $product): bool
    {
        return $this->quantityFor($product) > 0;
    }

    public function add(Product|int $product, float $quantity): void
    {
        $productId = $product instanceof Product ? $product->getKey() : (int) $product;
        $quantity = max(0, $quantity);

        if ($quantity <= 0) {
            return;
        }

        $lines = $this->lines();
        $lines[$productId] = round(((float) ($lines[$productId] ?? 0)) + $quantity, 3);

        $this->persistLines($lines);
    }

    public function setQuantity(Product|int $product, float $quantity): void
    {
        $productId = $product instanceof Product ? $product->getKey() : (int) $product;
        $quantity = round(max(0, $quantity), 3);
        $lines = $this->lines();

        if ($quantity <= 0) {
            unset($lines[$productId]);
        } else {
            $lines[$productId] = $quantity;
        }

        $this->persistLines($lines);
    }

    public function remove(Product|int $product): void
    {
        $productId = $product instanceof Product ? $product->getKey() : (int) $product;
        $lines = $this->lines();

        unset($lines[$productId]);

        $this->persistLines($lines);
    }

    public function clear(): void
    {
        $this->persistLines([]);
    }

    public function items(?User $shopper = null): Collection
    {
        $lines = $this->lines();

        if ($lines === []) {
            return collect();
        }

        $products = Product::query()
            ->with([
                'brand:id,name,slug',
                'productType:id,name,slug',
                'category:id,name,slug,parent_id',
                'measurementUnit:id,name,symbol',
            ])
            ->activePublished()
            ->where('is_catalog_only', false)
            ->whereIn('id', array_keys($lines))
            ->get()
            ->keyBy('id');

        return collect($lines)
            ->map(function (float $quantity, int|string $productId) use ($products, $shopper): ?array {
                $product = $products->get((int) $productId);

                if (! $product) {
                    return null;
                }

                $unitPrice = $product->displayPriceFor($shopper);
                $lineTotal = round($unitPrice * $quantity, 2);

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'quantity_label' => $this->formatQuantity($quantity),
                    'unit_price' => $unitPrice,
                    'unit_price_label' => Product::formatCurrency($unitPrice),
                    'line_total' => $lineTotal,
                    'line_total_label' => Product::formatCurrency($lineTotal),
                ];
            })
            ->filter()
            ->values();
    }

    public function subtotal(?User $shopper = null): float
    {
        return (float) $this->items($shopper)->sum('line_total');
    }

    public function subtotalLabel(?User $shopper = null): string
    {
        return Product::formatCurrency($this->subtotal($shopper));
    }

    protected function persistLines(array $lines): void
    {
        $lines = $this->sanitizeLines($lines);

        $this->session->put(self::SESSION_KEY, $lines);

        if ($lines === []) {
            Cookie::queue(Cookie::forget(self::COOKIE_KEY));

            return;
        }

        Cookie::queue(self::COOKIE_KEY, json_encode($lines, JSON_THROW_ON_ERROR), self::COOKIE_LIFETIME_MINUTES);
    }

    protected function readCookieLines(): array
    {
        $cookieValue = request()->cookie(self::COOKIE_KEY);

        if (! is_string($cookieValue) || trim($cookieValue) === '') {
            return [];
        }

        try {
            $decoded = json_decode($cookieValue, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return [];
        }

        return is_array($decoded) ? $decoded : [];
    }

    protected function sanitizeLines(mixed $lines): array
    {
        return collect(is_array($lines) ? $lines : [])
            ->filter(fn ($quantity, $productId): bool => is_numeric($productId) && is_numeric($quantity))
            ->mapWithKeys(function ($quantity, $productId): array {
                $normalizedQuantity = round((float) $quantity, 3);

                if ($normalizedQuantity <= 0) {
                    return [];
                }

                return [(int) $productId => $normalizedQuantity];
            })
            ->all();
    }

    protected function formatQuantity(float $quantity): string
    {
        return rtrim(rtrim(number_format($quantity, 3, '.', ''), '0'), '.');
    }
}
