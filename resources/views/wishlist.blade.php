<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $companyProfile->company_name }} | Wishlist</title>
    <meta name="description" content="Wishlist {{ $companyProfile->company_name }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="{{ asset('desain') }}/">
    <link rel="icon" href="{{ $companyProfile->favicon_href }}">
    <link rel="shortcut icon" href="{{ $companyProfile->favicon_href }}">
    <link rel="apple-touch-icon" href="{{ $companyProfile->favicon_href }}">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/fontawesome-stars.css">
    <link rel="stylesheet" href="css/meanmenu.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <link rel="stylesheet" href="css/venobox.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/helper.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        .site-brand {
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            width: 220px;
            max-width: 100%;
            min-height: 64px;
        }

        .site-brand__image {
            display: block;
            max-width: 220px;
            max-height: 64px;
            width: auto;
            height: auto;
            object-fit: contain;
            object-position: left center;
        }

        .site-brand__text {
            display: inline-block;
            max-width: 220px;
            color: #242424;
            font-size: 34px;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .public-setting-button {
            background: transparent;
            border: 0;
            color: #444;
            cursor: pointer;
            padding: 0;
        }

        .hb-menu nav > ul > li.site-nav-link > a::after {
            display: none !important;
        }

        .hb-menu nav > ul > li.site-nav-link > a {
            padding-right: 0;
        }

        @include('partials.site-navigation-styles')

        .wishlist-thumb {
            width: 82px;
            height: 82px;
            object-fit: contain;
            margin: 0 auto;
        }

        .wishlist-thumb--empty {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 82px;
            height: 82px;
            border: 1px dashed #d5d5d5;
            color: #999;
            font-size: 11px;
            text-transform: uppercase;
            margin: 0 auto;
        }

        .wishlist-remove-form,
        .wishlist-add-form {
            display: inline;
        }

        .wishlist-remove-button {
            background: transparent;
            border: 0;
            color: #666;
            cursor: pointer;
            padding: 0;
        }

        .wishlist-empty {
            padding: 60px 24px;
            border: 1px dashed #d9d9d9;
            text-align: center;
        }

        .wishlist-empty h3 {
            margin-bottom: 14px;
        }

        .wishlist-empty p {
            color: #666;
            margin-bottom: 22px;
        }

        .wishlist-empty a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 22px;
            background: #fed700;
            color: #242424;
            font-weight: 700;
            text-transform: uppercase;
        }

        @media (max-width: 767px) {
            .site-brand {
                justify-content: center;
                width: 200px;
                min-height: 56px;
            }

            .site-brand__image {
                max-width: 200px;
                max-height: 56px;
            }

            .site-brand__text {
                font-size: 28px;
                text-align: center;
            }
        }
    </style>
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body>
    @php
        $searchQuery = $homeCatalog['search_query'] ?? '';
        $searchFilter = $homeCatalog['search_filter'] ?? 'all';
        $searchOptions = $homeCatalog['search_options'] ?? collect();
        $currentShopper = auth()->user();
    @endphp
    <div class="body-wrapper">
        <header>
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="header-top-left">
                                <ul class="phone-wrap">
                                    <li><span>Hubungi Kami:</span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyProfile->phone) }}">{{ $companyProfile->phone }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <div class="header-top-right">
                                <ul class="ht-menu">
                                    @include('partials.public-setting-menu')
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-middle pl-sm-0 pr-sm-0 pl-xs-0 pr-xs-0">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="logo pb-sm-30 pb-xs-30">
                                <a href="{{ route('home') }}">
                                    <span class="site-brand" aria-label="{{ $companyProfile->company_name }}">
                                        @if ($companyProfile->has_logo)
                                            <img class="site-brand__image" src="{{ $companyProfile->logo_url }}" alt="{{ $companyProfile->company_name }}">
                                        @else
                                            <span class="site-brand__text">{{ $companyProfile->company_name }}</span>
                                        @endif
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 pl-0 ml-sm-15 ml-xs-15">
                            <form action="{{ route('home') }}" method="GET" class="hm-searchbox"><input type="hidden" name="browse" value="1">
                                <select name="catalog" class="nice-select select-search-category">
                                    @foreach ($searchOptions as $option)
                                        <option value="{{ $option['value'] }}" @selected($searchFilter === $option['value'])>
                                            {{ $option['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari produk, merek, atau kategori ...">
                                <button class="li-btn" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                            <div class="header-middle-right">
                                <ul class="hm-menu">
                                    <li class="hm-wishlist">
                                        <a href="{{ route('wishlist.index') }}">
                                            <span class="cart-item-count wishlist-item-count">{{ $wishlistCount }}</span>
                                            <i class="fa fa-heart-o"></i>
                                        </a>
                                    </li>
                                    @include('partials.public-minicart')
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-bottom header-sticky d-none d-lg-block d-xl-block">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hb-menu">
                                <nav>
                                    @include('partials.site-navigation')
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="breadcrumb-area">
                <div class="container">
                    <div class="breadcrumb-content">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="active">Wishlist</li>
                        </ul>
                    </div>
                </div>
            </div>
        @include('partials.mobile-navigation')
        </header>

        <div class="wishlist-area pt-60 pb-60">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        @if ($wishlistProducts->isEmpty())
                            <div class="wishlist-empty">
                                <h3>Wishlist masih kosong</h3>
                                <p>Tambahkan produk dari landing page untuk menyimpannya di sini.</p>
                                <a href="{{ route('home') }}">Kembali Belanja</a>
                            </div>
                        @else
                            <div class="table-content table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="li-product-remove">remove</th>
                                            <th class="li-product-thumbnail">images</th>
                                            <th class="cart-product-name">Product</th>
                                            <th class="li-product-price">Unit Price</th>
                                            <th class="li-product-stock-status">Stock Status</th>
                                            <th class="li-product-add-cart">add to cart</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wishlistProducts as $wishlistProduct)
                                            @php
                                                $displayPrice = $wishlistProduct->displayPriceFor($currentShopper);
                                                $displayPriceLabel = $wishlistProduct->displayPriceLabelFor($currentShopper);
                                                $canPurchase = $currentShopper?->isShopper() ?? false;
                                            @endphp
                                            <tr>
                                                <td class="li-product-remove">
                                                    <form class="wishlist-remove-form" action="{{ route('wishlist.destroy', $wishlistProduct) }}" method="POST" data-preserve-scroll="true">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="redirect_to" value="{{ route('wishlist.index') }}">
                                                        <button type="submit" class="wishlist-remove-button" aria-label="Hapus dari wishlist">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="li-product-thumbnail">
                                                    <a href="{{ route('products.show', $wishlistProduct->slug) }}">
                                                        @if ($wishlistProduct->thumbnail_url)
                                                            <img class="wishlist-thumb" src="{{ $wishlistProduct->thumbnail_url }}" alt="{{ $wishlistProduct->name }}">
                                                        @else
                                                            <div class="wishlist-thumb--empty">No Image</div>
                                                        @endif
                                                    </a>
                                                </td>
                                                <td class="li-product-name">
                                                    <a href="{{ route('products.show', $wishlistProduct->slug) }}">{{ $wishlistProduct->name }}</a>
                                                </td>
                                                <td class="li-product-price">
                                                    <span class="amount">{{ $displayPriceLabel }}</span>
                                                </td>
                                                <td class="li-product-stock-status">
                                                    <span class="{{ (float) $wishlistProduct->stock_quantity > 0 ? 'in-stock' : 'out-stock' }}">
                                                        {{ (float) $wishlistProduct->stock_quantity > 0 ? 'in stock' : 'out of stock' }}
                                                    </span>
                                                </td>
                                                <td class="li-product-add-cart">
                                                    @if ($canPurchase)
                                                        <form action="{{ route('cart.store', $wishlistProduct) }}" method="POST" data-preserve-scroll="true">
                                                            @csrf
                                                            <input type="hidden" name="quantity" value="{{ rtrim(rtrim(number_format((float) $wishlistProduct->min_order_quantity, 3, '.', ''), '0'), '.') }}">
                                                            <input type="hidden" name="redirect_to" value="{{ route('wishlist.index') }}">
                                                            <button type="submit" class="li-button li-button-dark">Masuk Keranjang</button>
                                                        </form>
                                                    @else
                                                        <a href="{{ route('login', ['redirect' => route('wishlist.index', [], false)]) }}">Login untuk beli</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="footer-static-bottom pt-30 pb-30">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="copyright text-center">
                                <span>&copy; {{ now()->year }} {{ $companyProfile->company_name }}. All rights reserved.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="js/vendor/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.meanmenu.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/jquery.mixitup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.barrating.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/venobox.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/scrollUp.min.js"></script>
    @include('partials.public-flash-alerts')
    @include('partials.public-scroll-restore')
    <script src="js/main.js"></script>
</body>
</html>
