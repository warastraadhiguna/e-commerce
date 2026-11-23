<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $product->name }} | {{ $companyProfile->company_name }}</title>
    <meta name="description" content="{{ $product->excerpt ?: $product->name }}">
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
    <link rel="stylesheet" href="{{ asset('desain/style.css') }}?v={{ filemtime(public_path('desain/style.css')) }}">
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

        .site-brand--footer {
            min-height: 72px;
            margin-bottom: 10px;
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

        .site-brand--footer .site-brand__image {
            max-height: 72px;
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

        .site-brand--footer .site-brand__text {
            font-size: 30px;
        }

        .site-social__text {
            display: inline-block;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            line-height: 40px;
            letter-spacing: .02em;
        }

        .x-social {
            background: #111;
        }

        .hb-menu nav > ul > li.site-nav-link > a::after {
            display: none !important;
        }

        .hb-menu nav > ul > li.site-nav-link > a {
            padding-right: 0;
        }

        @include('partials.site-navigation-styles')

        .single-product-main-image {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 520px;
            border: 1px solid #ededed;
            background: #fff;
            padding: 24px;
        }

        .single-product-main-image img {
            width: 100%;
            max-width: 100%;
            max-height: 460px;
            object-fit: contain;
        }

        .single-product-main-image__empty {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 420px;
            border: 1px dashed #d5d5d5;
            color: #999;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .product-meta-list {
            margin: 22px 0 0;
            padding: 0;
            list-style: none;
        }

        .product-meta-list li {
            padding: 8px 0;
            color: #666;
            border-bottom: 1px solid #f1f1f1;
        }

        .product-meta-list li strong {
            display: inline-block;
            min-width: 140px;
            color: #222;
        }

        .product-long-description p {
            margin-bottom: 14px;
            color: #666;
            line-height: 1.9;
            text-align: justify;
        }

        .single-product-wrap .product-image a {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 270px;
        }

        .single-product-wrap .product-image img {
            width: 100%;
            max-width: 100%;
            max-height: 240px;
            object-fit: contain;
        }

        .single-product-wrap .product-image__empty {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 240px;
            border: 1px dashed #d5d5d5;
            color: #999;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .public-setting-button {
            background: transparent;
            border: 0;
            color: #444;
            cursor: pointer;
            padding: 0;
        }

        .partner-price-note {
            margin-top: 14px;
            color: #666;
            line-height: 1.8;
        }

        .product-info .single-add-to-cart {
            margin-top: 26px;
        }

        .product-info .cart-quantity {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 12px;
        }

        .product-info .cart-quantity .quantity {
            margin-bottom: 0;
        }

        .product-info .cart-quantity .quantity label {
            display: block;
            margin-bottom: 8px;
            color: #222;
            font-size: 14px;
            font-weight: 600;
        }

        .product-info .cart-quantity .cart-plus-minus {
            margin-right: 0;
            height: 46px;
            border: 1px solid #d9d9d9;
        }

        .product-info .cart-quantity .cart-plus-minus-box {
            height: 44px;
            padding: 0;
            font-size: 16px;
        }

        .product-info .cart-quantity .qtybutton {
            width: 28px;
            line-height: 22px;
        }

        .product-info .cart-quantity .add-to-cart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 240px;
            height: 46px;
            padding: 0 22px;
            border: 0;
            background: var(--brand-primary, #fed700);
            color: #242424;
            cursor: pointer;
            text-align: center;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .product-info .wishlist-inline-form {
            margin-top: 14px;
        }

        .product-info .wishlist-inline-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: 0;
            color: #666;
            cursor: pointer;
            padding: 0;
        }

        .product-info .wishlist-inline-button.is-active {
            color: var(--brand-primary, #fed700);
        }

        .add-actions-link .add-cart form {
            display: block;
            width: 100%;
            height: 100%;
            margin: 0;
        }

        .add-actions-link .add-cart form button {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0;
            background: transparent;
            border: 0;
            color: inherit;
            cursor: pointer;
            font: inherit;
            text-transform: inherit;
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

            .single-product-main-image {
                min-height: 340px;
            }

            .product-info .cart-quantity {
                gap: 14px;
            }

            .product-info .cart-quantity .add-to-cart {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    @include('partials.brand-theme')
</head>
<body>
    <div class="body-wrapper">
        @php
            $searchQuery = $homeCatalog['search_query'] ?? '';
            $searchFilter = $homeCatalog['search_filter'] ?? 'all';
            $searchOptions = $homeCatalog['search_options'] ?? collect();
            $currentShopper = auth()->user();
            $canPurchase = ! $product->is_catalog_only && ($currentShopper?->isShopper() ?? false);
            $displayPrice = $product->displayPriceFor($currentShopper);
            $oldPrice = $product->oldPriceFor($currentShopper);
            $displayPriceLabel = $product->displayPriceLabelFor($currentShopper);
            $oldPriceLabel = $product->oldPriceLabelFor($currentShopper);
            $discountPercentage = $product->discountPercentageFor($currentShopper);
            $purchaseLabel = $canPurchase ? 'Lanjut pembelian' : 'Login untuk beli';
            $isWishlisted = $wishlistProductIds->contains($product->getKey());
        @endphp
        <header>
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="header-top-left">
                                <ul class="phone-wrap">
                                    @if (filled($companyProfile->phone))<li><span>Hubungi Kami:</span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyProfile->phone) }}">{{ $companyProfile->phone }}</a></li>@endif
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
        @include('partials.mobile-navigation')
        </header>

        <div class="breadcrumb-area">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('home') }}">Produk</a></li>
                        <li class="active">{{ $product->name }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-wraper pt-60 pb-60">
            <div class="container">
                <div class="row single-product-area">
                    <div class="col-lg-5 col-md-6">
                        @if ($product->thumbnail_url)
                            <div class="single-product-main-image">
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
                            </div>
                        @else
                            <div class="single-product-main-image">
                                <div class="single-product-main-image__empty">No Image</div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-7 col-md-6">
                        <div class="product-info">
                            <h2>{{ $product->name }}</h2>
                            <span class="product-details-ref">Reference: {{ $product->sku }}</span>
                            <div class="price-box pt-20">
                                <span class="new-price new-price-2">{{ $displayPriceLabel }}</span>
                                @if ($oldPrice)
                                    <span class="old-price">{{ $oldPriceLabel }}</span>
                                    @if ($discountPercentage)
                                        <span class="discount-percentage">-{{ $discountPercentage }}%</span>
                                    @endif
                                @endif
                            </div>
                            @if (auth()->check() && auth()->user()->isEligibleForPartnerPricing() && $product->has_partner_discount)
                                <p class="partner-price-note">Harga mitra aktif untuk akun kamu.</p>
                            @elseif (auth()->check() && auth()->user()->isMitra() && $product->has_partner_discount)
                                <p class="partner-price-note">Harga mitra belum aktif. Omzet pembelian harus memenuhi syarat dan menunggu approval admin/superadmin.</p>
                            @elseif ($product->has_partner_discount)
                                <p class="partner-price-note">Login buyer atau mitra untuk melanjutkan pembelian. Harga khusus mitra aktif setelah approval admin/superadmin.</p>
                            @endif
                            <div class="product-desc">
                                <p>
                                    <span>{{ $product->excerpt ?: 'Ringkasan produk belum tersedia.' }}</span>
                                </p>
                            </div>
                            <ul class="product-meta-list">
                                <li><strong>Brand</strong>{{ $product->brand?->name ?: '-' }}</li>
                                <li><strong>Tipe</strong>{{ $product->productType?->name ?: '-' }}</li>
                                <li><strong>Kategori</strong>{{ $product->category?->full_name ?: '-' }}</li>
                                <li><strong>Satuan</strong>{{ $product->unit_summary }}</li>
                                <li><strong>Stok</strong>{{ $product->is_catalog_only ? 'Belum dikonfirmasi' : rtrim(rtrim(number_format((float) $product->stock_quantity, 3, '.', ''), '0'), '.').' '.$product->measurementUnit?->symbol }}</li>
                                <li><strong>Status</strong>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</li>
                                @if ($product->weight)
                                    <li><strong>Berat</strong>{{ number_format((float) $product->weight, 3) }} kg</li>
                                @endif
                                @if ($product->length || $product->width || $product->height)
                                    <li>
                                        <strong>Dimensi</strong>
                                        {{ number_format((float) $product->length, 2) }} x {{ number_format((float) $product->width, 2) }} x {{ number_format((float) $product->height, 2) }} cm
                                    </li>
                                @endif
                            </ul>
                            <div class="single-add-to-cart pt-25">
                                @if ($product->is_catalog_only)
                                    <p>Katalog informasi dari materi publik. Harga, varian, dan ketersediaan perlu dikonfirmasi sebelum pembelian.</p>
                                    @if ($product->public_source_url)
                                        <a class="li-button" href="{{ $product->public_source_url }}" target="_blank" rel="noopener noreferrer">Lihat sumber Instagram</a>
                                    @endif
                                @else
                                <form id="purchase-section" action="{{ $canPurchase ? route('cart.store', $product) : route('login', ['redirect' => request()->getRequestUri()]) }}" method="{{ $canPurchase ? 'POST' : 'GET' }}" class="cart-quantity" data-preserve-scroll="true">
                                    @if ($canPurchase)
                                        @csrf
                                        <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                                    @endif
                                    <div class="quantity">
                                        <label>Quantity</label>
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" name="quantity" value="{{ rtrim(rtrim(number_format((float) $product->min_order_quantity, 3, '.', ''), '0'), '.') }}" type="text">
                                            <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                            <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                        </div>
                                    </div>
                                    <button class="add-to-cart" type="submit">{{ $purchaseLabel }}</button>
                                </form>
                                @endif
                                <form class="wishlist-inline-form" action="{{ route('wishlist.store', $product) }}" method="POST" data-preserve-scroll="true">
                                    @csrf
                                    <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                                    <button type="submit" class="wishlist-inline-button{{ $isWishlisted ? ' is-active' : '' }}">
                                        <i class="fa fa-heart-o"></i>
                                        <span>{{ $isWishlisted ? 'Sudah ada di wishlist' : 'Tambah ke wishlist' }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-area pt-60">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="li-product-tab">
                                <ul class="nav li-product-menu">
                                    <li><a class="active" data-toggle="tab" href="#product-description"><span>Description</span></a></li>
                                    <li><a data-toggle="tab" href="#product-information"><span>Additional Information</span></a></li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div id="product-description" class="tab-pane active show">
                                    <div class="product-long-description pt-25">
                                        @foreach (preg_split('/\r\n|\r|\n/', trim($product->description ?: $product->excerpt ?: 'Deskripsi produk belum tersedia.')) as $paragraph)
                                            @if (filled(trim($paragraph)))
                                                <p>{{ trim($paragraph) }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div id="product-information" class="tab-pane">
                                    <div class="table-content table-responsive pt-25">
                                        <table class="table">
                                            <tbody>
                                                <tr><th>SKU</th><td>{{ $product->sku }}</td></tr>
                                                <tr><th>Brand</th><td>{{ $product->brand?->name ?: '-' }}</td></tr>
                                                <tr><th>Tipe Produk</th><td>{{ $product->productType?->name ?: '-' }}</td></tr>
                                                <tr><th>Kategori</th><td>{{ $product->category?->full_name ?: '-' }}</td></tr>
                                                <tr><th>Satuan</th><td>{{ $product->unit_summary }}</td></tr>
                                                <tr><th>Minimum Order</th><td>{{ rtrim(rtrim(number_format((float) $product->min_order_quantity, 3, '.', ''), '0'), '.') }}</td></tr>
                                                @if ($product->weight)
                                                    <tr><th>Berat</th><td>{{ number_format((float) $product->weight, 3) }} kg</td></tr>
                                                @endif
                                                @if ($product->length || $product->width || $product->height)
                                                    <tr>
                                                        <th>Dimensi</th>
                                                        <td>{{ number_format((float) $product->length, 2) }} x {{ number_format((float) $product->width, 2) }} x {{ number_format((float) $product->height, 2) }} cm</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-area pt-55">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="li-section-title">
                                <h2><span>Produk Terkait</span></h2>
                            </div>
                            @include('partials.home.product-carousel', ['products' => $relatedProducts])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-static-top">
                <div class="container">
                    <div class="footer-shipping pt-60 pb-25">
                        <div class="row">
                            @foreach ($homeHighlights as $highlight)
                                <div class="col-lg-4 col-md-4 col-sm-6 pb-sm-25">
                                    <div class="li-shipping-inner-box">
                                        @if ($highlight->image_url)
                                            <div class="shipping-icon">
                                                <img src="{{ $highlight->image_url }}" alt="{{ $highlight->title }}">
                                            </div>
                                        @endif
                                        <div class="shipping-text">
                                            <h2>{{ $highlight->title }}</h2>
                                            <p>{{ $highlight->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-static-middle">
                <div class="container">
                    <div class="footer-logo-wrap pt-50 pb-35">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="footer-logo">
                                    <a href="{{ route('home') }}">
                                        <span class="site-brand site-brand--footer" aria-label="{{ $companyProfile->company_name }}">
                                            @if ($companyProfile->has_logo)
                                                <img class="site-brand__image" src="{{ $companyProfile->logo_url }}" alt="{{ $companyProfile->company_name }}">
                                            @else
                                                <span class="site-brand__text">{{ $companyProfile->company_name }}</span>
                                            @endif
                                        </span>
                                    </a>
                                    <p class="info">
                                        {{ $companyProfile->short_description ?: $companyProfile->description }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="footer-block">
                                    <h3 class="footer-block-title">Contact</h3>
                                    <ul>
                                        <li><strong>Address:</strong> {{ $companyProfile->address }}</li>
                                        <li><strong>Phone:</strong> {{ $companyProfile->phone }}</li>
                                        <li><strong>Email:</strong> {{ $companyProfile->email }}</li>
                                    </ul>
                                </div>
                            </div>
                            @if (count($companyProfile->socialLinks()))
                                <div class="col-lg-4 col-md-12">
                                    <div class="footer-block">
                                        <h3 class="footer-block-title">Follow Us</h3>
                                        <ul class="social-link">
                                            @foreach ($companyProfile->socialLinks() as $socialLink)
                                                <li class="{{ $socialLink['class'] }}">
                                                    <a href="{{ $socialLink['url'] }}" target="_blank" rel="noopener noreferrer" title="{{ $socialLink['title'] }}">
                                                        @if (($socialLink['label'] ?? null) === 'X')
                                                            <span class="site-social__text">X</span>
                                                        @else
                                                            <i class="fa fa-{{ $socialLink['icon'] }}"></i>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-static-bottom pt-30 pb-30">
                <div class="container">
                    <div class="copyright text-center">
                        <span>&copy; {{ now()->year }} {{ $companyProfile->company_name }}. All rights reserved.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="js/vendor/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/ajax-mail.js"></script>
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
