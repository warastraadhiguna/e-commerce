<?php

namespace App\Providers;

use App\Models\AboutValue;
use App\Models\Brand;
use App\Models\CompanyProfile;
use App\Models\HomeHighlight;
use App\Models\HomeMedia;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Support\CartManager;
use App\Support\WishlistManager;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('partner-registrations', function (Request $request): Limit {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function () use ($request) {
                    return redirect()
                        ->to(route('contact').'#partner-registration')
                        ->withInput($request->except('website'))
                        ->withErrors([
                            'full_name' => 'Terlalu banyak percobaan. Coba lagi dalam beberapa saat.',
                        ]);
                });
        });

        View::composer('*', function ($view): void {
            $companyProfile = once(function () {
                if (! Schema::hasTable('company_profiles')) {
                    return CompanyProfile::make(CompanyProfile::defaultAttributes());
                }

                return CompanyProfile::query()->first()
                    ?? CompanyProfile::make(CompanyProfile::defaultAttributes());
            });

            $aboutValues = once(function () {
                if (! Schema::hasTable('about_values')) {
                    return collect();
                }

                return AboutValue::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            });

            $homeHighlights = once(function () {
                if (! Schema::hasTable('home_highlights')) {
                    return collect();
                }

                return HomeHighlight::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            });

            $homeMedia = once(function () {
                if (! Schema::hasTable('home_media')) {
                    return collect();
                }

                return HomeMedia::query()
                    ->orderBy('section')
                    ->orderBy('sort_order')
                    ->get();
            });

            $productNavigation = once(function () {
                if (
                    ! Schema::hasTable('products')
                    || ! Schema::hasTable('product_types')
                    || ! Schema::hasTable('product_categories')
                ) {
                    return collect();
                }

                $productQuery = fn () => Product::query()
                    ->select([
                        'id',
                        'product_type_id',
                        'product_category_id',
                        'slug',
                        'name',
                    ])
                    ->activePublished()
                    ->orderByRaw('CASE WHEN published_at IS NULL THEN 1 ELSE 0 END')
                    ->orderByDesc('published_at')
                    ->orderBy('sort_order')
                    ->orderByDesc('id');

                return ProductType::query()
                    ->with([
                        'categories' => fn ($query) => $query
                            ->select(['id', 'product_type_id', 'name', 'slug', 'sort_order'])
                            ->withCount([
                                'products as active_products_count' => fn ($productBuilder) => $productBuilder->activePublished(),
                            ])
                            ->where('is_active', true)
                            ->whereHas('products', fn ($productBuilder) => $productBuilder->activePublished())
                            ->orderBy('sort_order')
                            ->orderBy('name'),
                    ])
                    ->where('is_active', true)
                    ->whereHas('categories.products', fn ($query) => $query->activePublished())
                    ->orderByRaw("
                        CASE
                            WHEN slug = 'laptop' THEN 0
                            WHEN slug = 'tv-audio' THEN 1
                            ELSE 2
                        END
                    ")
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get()
                    ->take(4)
                    ->map(function (ProductType $type) use ($productQuery): array {
                        $categories = $type->categories
                            ->take(3)
                            ->map(function (ProductCategory $category) use ($productQuery): array {
                                $products = (clone $productQuery())
                                    ->where('product_category_id', $category->getKey())
                                    ->limit(3)
                                    ->get();

                                return [
                                    'name' => $category->name,
                                    'url' => route('home', ['catalog' => "category:{$category->getKey()}"]).'#search-results',
                                    'all_url' => route('home', ['catalog' => "category:{$category->getKey()}"]).'#search-results',
                                    'products' => $products,
                                    'has_more_products' => ((int) $category->active_products_count) > $products->count(),
                                ];
                            })
                            ->filter(fn (array $category): bool => $category['products']->isNotEmpty())
                            ->values();

                        return [
                            'name' => $type->name,
                            'url' => route('home', ['catalog' => "type:{$type->getKey()}"]).'#search-results',
                            'categories' => $categories,
                            'has_more_categories' => $type->categories->count() > $categories->count(),
                        ];
                    })
                    ->filter(fn (array $type): bool => $type['categories']->isNotEmpty())
                    ->values();
            });

            $homeCatalog = once(function () {
                $emptyCatalog = [
                    'new_arrivals' => collect(),
                    'bestsellers' => collect(),
                    'featured_products' => collect(),
                    'search_query' => '',
                    'search_filter' => 'all',
                    'search_filter_label' => 'All',
                    'search_is_active' => false,
                    'search_options' => collect([['value' => 'all', 'label' => 'All']]),
                    'search_results' => collect(),
                    'primary_type' => null,
                    'primary_type_filters' => collect(),
                    'primary_type_products' => collect(),
                    'secondary_type' => null,
                    'secondary_type_filters' => collect(),
                    'secondary_type_products' => collect(),
                    'trending_brands' => collect(),
                    'trending_brand_products' => [],
                    'bestseller_showcase' => collect(),
                ];

                if (
                    ! Schema::hasTable('products')
                    || ! Schema::hasTable('brands')
                    || ! Schema::hasTable('product_types')
                    || ! Schema::hasTable('product_categories')
                ) {
                    return $emptyCatalog;
                }

                $productQuery = fn () => Product::query()
                    ->with([
                        'brand:id,name,slug',
                        'productType:id,name,slug',
                        'category:id,name,slug,parent_id',
                        'measurementUnit:id,name,symbol',
                    ])
                    ->activePublished()
                    ->orderByRaw('CASE WHEN published_at IS NULL THEN 1 ELSE 0 END')
                    ->orderByDesc('published_at')
                    ->orderBy('sort_order')
                    ->orderByDesc('id');

                $searchQuery = trim((string) request()->query('q', ''));
                $searchFilter = (string) request()->query('catalog', 'all');
                $searchIsActive = filled($searchQuery) || ($searchFilter !== 'all' && filled($searchFilter));

                $typeQuery = ProductType::query()
                    ->where('is_active', true)
                    ->whereHas('products', fn ($query) => $query->activePublished())
                    ->orderByRaw("
                        CASE
                            WHEN slug = 'laptop' THEN 0
                            WHEN slug = 'tv-audio' THEN 1
                            ELSE 2
                        END
                    ")
                    ->orderBy('sort_order')
                    ->orderBy('name');

                $searchOptions = collect([
                    [
                        'value' => 'all',
                        'label' => 'All',
                    ],
                ])->merge(
                    ProductType::query()
                        ->with([
                            'categories' => fn ($query) => $query
                                ->where('is_active', true)
                                ->whereHas('products', fn ($productQuery) => $productQuery->activePublished())
                                ->orderBy('sort_order')
                                ->orderBy('name'),
                        ])
                        ->where('is_active', true)
                        ->whereHas('products', fn ($query) => $query->activePublished())
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->get()
                        ->flatMap(function (ProductType $type): array {
                            $options = [[
                                'value' => "type:{$type->getKey()}",
                                'label' => $type->name,
                            ]];

                            foreach ($type->categories as $category) {
                                $options[] = [
                                    'value' => "category:{$category->getKey()}",
                                    'label' => '- - '.$category->name,
                                ];
                            }

                            return $options;
                        })
                )->merge(
                    Brand::query()
                        ->where('is_active', true)
                        ->whereHas('products', fn ($query) => $query->activePublished())
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->get()
                        ->map(fn (Brand $brand): array => [
                            'value' => "brand:{$brand->getKey()}",
                            'label' => '[Brand] '.$brand->name,
                        ])
                )->values();

                $applySearchFilter = function (Builder $query, string $filter): void {
                    if (blank($filter) || $filter === 'all' || ! str_contains($filter, ':')) {
                        return;
                    }

                    [$scope, $id] = explode(':', $filter, 2);

                    if (! ctype_digit($id)) {
                        return;
                    }

                    match ($scope) {
                        'type' => $query->where('product_type_id', (int) $id),
                        'category' => $query->where('product_category_id', (int) $id),
                        'brand' => $query->where('brand_id', (int) $id),
                        default => null,
                    };
                };

                $applySearchTerm = function (Builder $query, string $term): void {
                    if (blank($term)) {
                        return;
                    }

                    $query->where(function (Builder $builder) use ($term): void {
                        $builder
                            ->where('name', 'like', "%{$term}%")
                            ->orWhere('short_name', 'like', "%{$term}%")
                            ->orWhere('sku', 'like', "%{$term}%")
                            ->orWhere('barcode', 'like', "%{$term}%")
                            ->orWhere('excerpt', 'like', "%{$term}%")
                            ->orWhere('description', 'like', "%{$term}%")
                            ->orWhereHas('brand', fn (Builder $brandQuery) => $brandQuery->where('name', 'like', "%{$term}%"))
                            ->orWhereHas('productType', fn (Builder $typeBuilder) => $typeBuilder->where('name', 'like', "%{$term}%"))
                            ->orWhereHas('category', fn (Builder $categoryBuilder) => $categoryBuilder->where('name', 'like', "%{$term}%"));
                    });
                };

                $primaryType = (clone $typeQuery)->first();

                $secondaryType = (clone $typeQuery)
                    ->when($primaryType, fn ($query) => $query->whereKeyNot($primaryType->getKey()))
                    ->first();

                $typeFilters = function (?ProductType $type) {
                    if (! $type) {
                        return collect();
                    }

                    return ProductCategory::query()
                        ->where('product_type_id', $type->getKey())
                        ->where('is_active', true)
                        ->whereHas('products', fn ($query) => $query->activePublished())
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->limit(3)
                        ->get();
                };

                $trendingBrands = Brand::query()
                    ->where('is_active', true)
                    ->whereHas('products', fn ($query) => $query->activePublished())
                    ->orderBy("
                        CASE
                            WHEN slug = 'sanai' THEN 0
                            WHEN slug = 'chamcham' THEN 1
                            WHEN slug = 'xailstation' THEN 2
                            ELSE 4
                        END
                    ")
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->limit(3)
                    ->get();

                $searchFilterLabel = data_get(
                    $searchOptions->firstWhere('value', $searchFilter),
                    'label',
                    'All',
                );

                $searchResults = collect();

                if ($searchIsActive) {
                    $searchResultsQuery = $productQuery();
                    $applySearchFilter($searchResultsQuery, $searchFilter);
                    $applySearchTerm($searchResultsQuery, $searchQuery);
                    $searchResults = $searchResultsQuery->limit(18)->get();
                }

                return [
                    'new_arrivals' => $productQuery()->where('is_new_arrival', true)->limit(10)->get(),
                    'bestsellers' => $productQuery()->where('is_bestseller', true)->limit(10)->get(),
                    'featured_products' => $productQuery()->where('is_featured', true)->limit(10)->get(),
                    'search_query' => $searchQuery,
                    'search_filter' => $searchFilter,
                    'search_filter_label' => $searchFilterLabel,
                    'search_is_active' => $searchIsActive,
                    'search_options' => $searchOptions,
                    'search_results' => $searchResults,
                    'primary_type' => $primaryType,
                    'primary_type_filters' => $typeFilters($primaryType),
                    'primary_type_products' => $primaryType
                        ? $productQuery()->whereBelongsTo($primaryType, 'productType')->limit(10)->get()
                        : collect(),
                    'secondary_type' => $secondaryType,
                    'secondary_type_filters' => $typeFilters($secondaryType),
                    'secondary_type_products' => $secondaryType
                        ? $productQuery()->whereBelongsTo($secondaryType, 'productType')->limit(10)->get()
                        : collect(),
                    'trending_brands' => $trendingBrands,
                    'trending_brand_products' => $trendingBrands
                        ->mapWithKeys(fn (Brand $brand): array => [
                            $brand->getKey() => $productQuery()->whereBelongsTo($brand)->limit(10)->get(),
                        ])
                        ->all(),
                    'bestseller_showcase' => $productQuery()->where('is_bestseller', true)->limit(10)->get(),
                ];
            });

            $wishlist = app(WishlistManager::class);
            $wishlistProductIds = collect($wishlist->ids());
            $cart = app(CartManager::class);
            $cartItems = $cart->items(request()->user());

            $view->with('companyProfile', $companyProfile);
            $view->with('aboutValues', $aboutValues);
            $view->with('homeHighlights', $homeHighlights);
            $view->with('homeMedia', $homeMedia);
            $view->with('productNavigation', $productNavigation);
            $view->with('homeCatalog', $homeCatalog);
            $view->with('wishlistCount', $wishlistProductIds->count());
            $view->with('wishlistProductIds', $wishlistProductIds);
            $view->with('cartItems', $cartItems);
            $view->with('cartCount', $cart->totalQuantity());
            $view->with('cartBadgeCount', $cart->badgeCount());
            $view->with('cartSubtotalLabel', $cart->subtotalLabel(request()->user()));
        });
    }
}
