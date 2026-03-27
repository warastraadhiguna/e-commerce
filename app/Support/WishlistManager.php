<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class WishlistManager
{
    public const SESSION_KEY = 'wishlist_product_ids';
    public const COOKIE_KEY = 'wishlist_product_ids';
    protected const COOKIE_LIFETIME_MINUTES = 43200;

    public function __construct(
        protected Session $session,
    ) {}

    public function ids(): array
    {
        $sessionIds = $this->sanitizeIds($this->session->get(self::SESSION_KEY, []));

        if ($sessionIds !== []) {
            return $sessionIds;
        }

        $cookieIds = $this->sanitizeIds($this->readCookieIds());

        if ($cookieIds !== []) {
            $this->session->put(self::SESSION_KEY, $cookieIds);
        }

        return $cookieIds;
    }

    public function count(): int
    {
        return count($this->ids());
    }

    public function contains(Product|int $product): bool
    {
        $productId = $product instanceof Product ? $product->getKey() : (int) $product;

        return in_array($productId, $this->ids(), true);
    }

    public function add(Product|int $product): void
    {
        $productId = $product instanceof Product ? $product->getKey() : (int) $product;

        $ids = collect($this->ids())
            ->push($productId)
            ->unique()
            ->values()
            ->all();

        $this->persistIds($ids);
    }

    public function remove(Product|int $product): void
    {
        $productId = $product instanceof Product ? $product->getKey() : (int) $product;

        $ids = collect($this->ids())
            ->reject(fn (int $id): bool => $id === $productId)
            ->values()
            ->all();

        $this->persistIds($ids);
    }

    public function products(): Collection
    {
        $ids = $this->ids();

        if ($ids === []) {
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
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn (int $id) => $products->get($id))
            ->filter()
            ->values();
    }

    protected function persistIds(array $ids): void
    {
        $ids = $this->sanitizeIds($ids);

        $this->session->put(self::SESSION_KEY, $ids);

        if ($ids === []) {
            Cookie::queue(Cookie::forget(self::COOKIE_KEY));

            return;
        }

        Cookie::queue(self::COOKIE_KEY, json_encode($ids, JSON_THROW_ON_ERROR), self::COOKIE_LIFETIME_MINUTES);
    }

    protected function readCookieIds(): array
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

    protected function sanitizeIds(mixed $ids): array
    {
        return collect(is_array($ids) ? $ids : [])
            ->filter(fn ($id): bool => is_numeric($id))
            ->map(fn ($id): int => (int) $id)
            ->unique()
            ->values()
            ->all();
    }
}
