<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $companyProfile->company_name }} | Akun Saya</title>
    <meta name="description" content="Akun {{ $companyProfile->company_name }}">
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

        .account-card {
            border: 1px solid #ededed;
            background: #fff;
            padding: 30px;
            margin-bottom: 30px;
        }

        .account-page-container {
            width: 100%;
            max-width: 1170px;
        }

        .account-tabs {
            margin-bottom: 26px;
            border-bottom: 1px solid #ededed;
        }

        .account-tabs > li {
            margin-bottom: -1px;
        }

        .account-tabs > li > a {
            display: block;
            padding: 14px 22px;
            color: #666;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid transparent;
            border-bottom: 0;
        }

        .account-tabs > li.active > a,
        .account-tabs > li > a.active,
        .account-tabs > li > a:hover {
            color: #222;
            background: #fff;
            border-color: #ededed;
            border-top: 3px solid var(--brand-primary, #fed700);
        }

        .account-tab-pane {
            display: none;
        }

        .account-tab-pane.active {
            display: block;
        }

        .account-card__title {
            margin-bottom: 18px;
            font-size: 24px;
            font-weight: 700;
            color: #222;
        }

        .account-card__hint {
            margin-bottom: 22px;
            color: #666;
            line-height: 1.8;
        }

        .account-label {
            display: block;
            margin-bottom: 10px;
            color: #222;
            font-size: 14px;
            font-weight: 700;
        }

        .account-input,
        .account-textarea {
            width: 100%;
            border: 1px solid #dfdfdf;
            background: #fff;
            padding: 0 15px;
        }

        .account-input {
            height: 46px;
        }

        .account-textarea {
            min-height: 110px;
            padding-top: 12px;
            padding-bottom: 12px;
            resize: vertical;
        }

        .account-checkbox {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
            line-height: 1.2;
            margin-top: 8px;
        }

        .account-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            min-width: 16px;
            min-height: 16px;
            margin: 0;
            flex-shrink: 0;
            vertical-align: middle;
        }

        .account-checkbox span {
            display: inline-block;
            line-height: 1.3;
        }

        .account-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .account-button,
        .account-button--secondary,
        .account-button--danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 20px;
            border: 0;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .account-button {
            background: var(--brand-primary, #fed700);
            color: #222;
        }

        .account-button--secondary {
            background: #f4f4f4;
            color: #222;
        }

        .account-button--danger {
            background: #fceaea;
            color: #c62828;
        }

        .shipping-address-card {
            border: 1px solid #ededed;
            background: #fff;
            padding: 24px;
            margin-bottom: 24px;
        }

        .shipping-address-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .shipping-address-card__title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #222;
        }

        .shipping-address-card__badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 12px;
            background: #fff7cc;
            color: #8a6a00;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .account-order-status {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .account-order-status--pending {
            background: #fff3cd;
            color: #7c5a00;
        }

        .account-order-status--processing {
            background: #d1ecf1;
            color: #0c5460;
        }

        .account-order-status--completed {
            background: #d4edda;
            color: #155724;
        }

        .account-order-status--cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .account-order-link {
            color: #222;
            font-weight: 700;
            text-decoration: underline;
        }

        .account-orders-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
        }

        .account-orders-pagination__summary {
            color: #666;
            font-size: 14px;
        }

        .account-orders-pagination__links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .account-orders-pagination__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 16px;
            border: 1px solid #e2e2e2;
            background: #fff;
            color: #222;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .account-orders-pagination__link.is-disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .account-empty {
            padding: 36px 24px;
            border: 1px dashed #d5d5d5;
            color: #777;
            text-align: center;
        }

        .account-field-error {
            margin-top: 6px;
            color: #d32f2f;
            font-size: 13px;
            line-height: 1.5;
        }

        @media (max-width: 767px) {
            .account-page-container {
                width: 100%;
            }

            .account-card {
                padding: 22px 18px;
            }

            .account-orders-pagination {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    @include('partials.brand-theme')
</head>
<body>
    @php
        $searchQuery = $homeCatalog['search_query'] ?? '';
        $searchFilter = $homeCatalog['search_filter'] ?? 'all';
        $searchOptions = $homeCatalog['search_options'] ?? collect();
        $activeTab = old('account_tab', request()->query('tab', 'profile'));
        $isProfileTab = $activeTab === 'profile';
        $isShippingTab = $activeTab === 'shipping';
        $isPasswordTab = $activeTab === 'password';
        $isOrdersTab = $activeTab === 'orders';
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
                                        <option value="{{ $option['value'] }}" @selected($searchFilter === $option['value'])>{{ $option['label'] }}</option>
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
                            <li class="active">Akun Saya</li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-wraper pt-60 pb-60">
            <div class="container account-page-container">
                <div class="account-card">
                    <ul class="nav nav-tabs account-tabs" role="tablist">
                        <li role="presentation" class="{{ $isProfileTab ? 'active' : '' }}">
                            <a href="#account-profile" data-toggle="tab" data-account-tab="profile">Profil</a>
                        </li>
                        <li role="presentation" class="{{ $isShippingTab ? 'active' : '' }}">
                            <a href="#account-shipping" data-toggle="tab" data-account-tab="shipping">Alamat Kirim</a>
                        </li>
                        <li role="presentation" class="{{ $isPasswordTab ? 'active' : '' }}">
                            <a href="#account-password" data-toggle="tab" data-account-tab="password">Ganti Password</a>
                        </li>
                        <li role="presentation" class="{{ $isOrdersTab ? 'active' : '' }}">
                            <a href="#account-orders" data-toggle="tab" data-account-tab="orders">Riwayat Order</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="account-profile" class="account-tab-pane {{ $isProfileTab ? 'active' : '' }}">
                            @include('account.partials.profile-tab')
                        </div>
                        <div id="account-shipping" class="account-tab-pane {{ $isShippingTab ? 'active' : '' }}">
                            @include('account.partials.shipping-tab')
                        </div>
                        <div id="account-password" class="account-tab-pane {{ $isPasswordTab ? 'active' : '' }}">
                            @include('account.partials.password-tab')
                        </div>
                        <div id="account-orders" class="account-tab-pane {{ $isOrdersTab ? 'active' : '' }}">
                            @include('account.partials.orders-tab')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.public-simple-footer')
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
    <script>
        $(function () {
            var currentTab = new URL(window.location.href).searchParams.get('tab');

            if (currentTab) {
                var initialLink = $('.account-tabs a[data-account-tab="' + currentTab + '"]');

                if (initialLink.length) {
                    $('.account-tabs li').removeClass('active');
                    initialLink.parent('li').addClass('active');
                    initialLink.tab('show');
                }
            }

            $('.account-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (event) {
                var tab = $(event.target).data('account-tab');
                var currentUrl = new URL(window.location.href);

                $('.account-tabs li').removeClass('active');
                $(event.target).parent('li').addClass('active');

                if (tab) {
                    currentUrl.searchParams.set('tab', tab);
                    window.history.replaceState({}, document.title, currentUrl.pathname + currentUrl.search + currentUrl.hash);
                }
            });
        });
    </script>
    <script src="js/main.js"></script>
</body>
</html>
