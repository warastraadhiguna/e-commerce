<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $companyProfile->company_name }} | Checkout</title>
    <meta name="description" content="Checkout {{ $companyProfile->company_name }}">
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
        .site-brand { display: inline-flex; align-items: center; justify-content: flex-start; width: 220px; max-width: 100%; min-height: 64px; }
        .site-brand__image { display: block; max-width: 220px; max-height: 64px; width: auto; height: auto; object-fit: contain; object-position: left center; }
        .site-brand__text { display: inline-block; max-width: 220px; color: #242424; font-size: 34px; font-weight: 700; line-height: 1.1; letter-spacing: .04em; text-transform: uppercase; }
        .public-setting-button { background: transparent; border: 0; color: #444; cursor: pointer; padding: 0; }
        .hb-menu nav > ul > li.site-nav-link > a::after { display: none !important; }
        .hb-menu nav > ul > li.site-nav-link > a { padding-right: 0; }
        @include('partials.site-navigation-styles')
        .checkout-card { padding: 28px 30px; border: 1px solid #ececec; background: #fff; }
        .checkout-card h3 { margin-bottom: 22px; }
        .checkout-helper { margin-bottom: 20px; padding: 14px 18px; border-left: 3px solid #fed700; background: #fffdf5; color: #666; line-height: 1.8; }
        .checkout-address-list { display: grid; gap: 16px; }
        .checkout-address-option { display: block; margin: 0; cursor: pointer; }
        .checkout-address-option input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }
        .checkout-address-option__card { display: block; padding: 18px 18px 16px; border: 1px solid #e2e2e2; transition: border-color .2s ease, box-shadow .2s ease; }
        .checkout-address-option input[type="radio"]:checked + .checkout-address-option__card { border-color: #fed700; box-shadow: 0 0 0 1px #fed700 inset; }
        .checkout-address-option__header { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-bottom: 8px; }
        .checkout-address-option__label { font-weight: 700; color: #242424; }
        .checkout-address-option__badge { display: inline-flex; align-items: center; min-height: 24px; padding: 0 10px; background: #fed700; color: #242424; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .checkout-address-option__meta { margin-bottom: 6px; color: #333; font-weight: 600; }
        .checkout-address-option__address, .checkout-address-option__notes { color: #666; line-height: 1.8; }
        .checkout-address-empty { padding: 22px; border: 1px dashed #d9d9d9; color: #666; line-height: 1.8; }
        .checkout-address-empty a { color: #242424; font-weight: 700; text-decoration: underline; }
        .checkout-notes textarea { min-height: 140px; resize: vertical; }
        .checkout-summary-table td, .checkout-summary-table th { vertical-align: top; }
        .checkout-summary-product small { display: block; margin-top: 4px; color: #777; }
        .checkout-submit { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; padding: 0 26px; border: 0; background: #fed700; color: #242424; font-size: 14px; font-weight: 700; text-transform: uppercase; cursor: pointer; }
        .checkout-back-link { display: inline-flex; align-items: center; margin-left: 14px; color: #666; text-decoration: underline; }
        .checkout-error { margin-bottom: 20px; padding: 14px 18px; border-left: 3px solid #dc3545; background: #fff5f5; color: #8a1f11; line-height: 1.8; }
        @media (max-width: 767px) {
            .site-brand { justify-content: center; width: 200px; min-height: 56px; }
            .site-brand__image { max-width: 200px; max-height: 56px; }
            .site-brand__text { font-size: 28px; text-align: center; }
            .checkout-card { padding: 22px 18px; }
            .checkout-submit { width: 100%; }
            .checkout-back-link { margin-top: 12px; margin-left: 0; }
        }
    </style>
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body>
    @php
        $searchQuery = $homeCatalog['search_query'] ?? '';
        $searchFilter = $homeCatalog['search_filter'] ?? 'all';
        $searchOptions = $homeCatalog['search_options'] ?? collect();
    @endphp
    <div class="body-wrapper">
        <header>
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="header-top-left">
                                <ul class="phone-wrap">
                                    <li><span>Telephone Enquiry:</span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyProfile->phone) }}">{{ $companyProfile->phone }}</a></li>
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
                            <form action="{{ route('home') }}" method="GET" class="hm-searchbox">
                                <select name="catalog" class="nice-select select-search-category">
                                    @foreach ($searchOptions as $option)
                                        <option value="{{ $option['value'] }}" @selected($searchFilter === $option['value'])>{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Enter your search key ...">
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
                                <nav>@include('partials.site-navigation')</nav>
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
                            <li><a href="{{ route('cart.index') }}">Keranjang</a></li>
                            <li class="active">Checkout</li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="checkout-area pt-60 pb-60">
            <div class="container">
                @if ($errors->has('checkout'))
                    <div class="checkout-error">{{ $errors->first('checkout') }}</div>
                @endif

                <div class="checkout-helper">
                    Stok akan dipotong saat checkout berhasil dibuat. Sistem akan mengecek ulang stok di database tepat sebelum pesanan disimpan.
                </div>

                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="checkout-card mb-30">
                                <h3>Pilih Alamat Kirim</h3>

                                @if ($shippingAddresses->isEmpty())
                                    <div class="checkout-address-empty">
                                        Kamu belum punya alamat kirim. Tambahkan dulu dari <a href="{{ route('account.show', ['tab' => 'shipping']) }}">Akun Saya</a> sebelum checkout.
                                    </div>
                                @else
                                    <div class="checkout-address-list">
                                        @foreach ($shippingAddresses as $shippingAddress)
                                            <label class="checkout-address-option">
                                                <input type="radio" name="shipping_address_id" value="{{ $shippingAddress->id }}" @checked((string) $selectedShippingAddressId === (string) $shippingAddress->id)>
                                                <span class="checkout-address-option__card">
                                                    <span class="checkout-address-option__header">
                                                        <span class="checkout-address-option__label">{{ $shippingAddress->label ?: 'Alamat Kirim' }}</span>
                                                        @if ($shippingAddress->is_default)
                                                            <span class="checkout-address-option__badge">Default</span>
                                                        @endif
                                                    </span>
                                                    <div class="checkout-address-option__meta">
                                                        {{ $shippingAddress->recipient_name ?: auth()->user()->name }}
                                                        @if ($shippingAddress->phone)
                                                            · {{ $shippingAddress->phone }}
                                                        @endif
                                                    </div>
                                                    <div class="checkout-address-option__address">{{ $shippingAddress->full_address }}</div>
                                                    @if ($shippingAddress->notes)
                                                        <div class="checkout-address-option__notes">Catatan alamat: {{ $shippingAddress->notes }}</div>
                                                    @endif
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                    @error('shipping_address_id')
                                        <p class="text-danger mt-10 mb-0">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>

                            <div class="checkout-card checkout-notes">
                                <h3>Catatan Pesanan</h3>
                                <textarea name="user_notes" placeholder="Tambahkan catatan khusus untuk pesanan ini, jika ada.">{{ $checkoutNotes }}</textarea>
                                @error('user_notes')
                                    <p class="text-danger mt-10 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="your-order checkout-card">
                                <h3>Ringkasan Pesanan</h3>
                                <div class="your-order-table table-responsive">
                                    <table class="table checkout-summary-table">
                                        <thead>
                                            <tr>
                                                <th class="cart-product-name">Produk</th>
                                                <th class="cart-product-total">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cartItems as $cartItem)
                                                <tr class="cart_item">
                                                    <td class="checkout-summary-product">
                                                        {{ $cartItem['product']->name }}
                                                        <small>{{ $cartItem['unit_price_label'] }} x {{ $cartItem['quantity_label'] }} · {{ $cartItem['product']->unit_summary }}</small>
                                                    </td>
                                                    <td class="cart-product-total"><span class="amount">{{ $cartItem['line_total_label'] }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="cart-subtotal">
                                                <th>Total sementara</th>
                                                <td><span class="amount">{{ $cartSubtotalLabel }}</span></td>
                                            </tr>
                                            <tr class="order-total">
                                                <th>Total checkout</th>
                                                <td><strong><span class="amount">{{ $cartSubtotalLabel }}</span></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                @if ($shippingAddresses->isNotEmpty())
                                    <button type="submit" class="checkout-submit">Buat Pesanan</button>
                                @endif
                                <a href="{{ route('cart.index') }}" class="checkout-back-link">Kembali ke keranjang</a>
                            </div>
                        </div>
                    </div>
                </form>
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
    @include('partials.public-scroll-restore')
    <script src="js/main.js"></script>
</body>
</html>
