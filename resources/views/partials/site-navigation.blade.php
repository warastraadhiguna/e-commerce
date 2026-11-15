<ul>
    <li class="site-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ route('home') }}">Home</a>
    </li>
    <li class="megamenu-holder catalog-nav-link {{ request()->routeIs('products.show') ? 'active' : '' }}">
        <a href="{{ route('home', ['browse' => 1]) }}#search-results">Product</a>
        @if ($productNavigation->isNotEmpty())
            <ul class="megamenu hb-megamenu hb-megamenu--catalog">
                @foreach ($productNavigation as $type)
                    <li>
                        <a href="{{ $type['url'] }}">{{ $type['name'] }}</a>
                        <ul>
                            @foreach ($type['categories'] as $category)
                                <li class="catalog-nav__category">
                                    <a href="{{ $category['url'] }}">{{ $category['name'] }}</a>
                                </li>
                                @foreach ($category['products'] as $product)
                                    <li class="catalog-nav__product">
                                        <a href="{{ route('products.show', $product->slug) }}">{{ \Illuminate\Support\Str::limit($product->name, 32) }}</a>
                                    </li>
                                @endforeach
                                <li class="catalog-nav__all">
                                    <a href="{{ $category['all_url'] }}">All</a>
                                </li>
                            @endforeach
                            <li class="catalog-nav__type-all">
                                <a href="{{ $type['url'] }}">{{ $type['has_more_categories'] ? 'All '.$type['name'] : 'All '.$type['name'] }}</a>
                            </li>
                        </ul>
                    </li>
                @endforeach
                <li>
                    <a href="{{ route('home', ['browse' => 1]) }}#search-results">Catalog</a>
                    <ul>
                        <li class="catalog-nav__type-all">
                            <a href="{{ route('home', ['browse' => 1]) }}#search-results">All Products</a>
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    </li>
    <li class="site-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
        <a href="{{ route('about') }}">About Us</a>
    </li>
    <li class="site-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
        <a href="{{ route('contact') }}">Contact</a>
    </li>
</ul>
