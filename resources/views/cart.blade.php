<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $companyProfile->company_name }} | Keranjang</title>
    <meta name="description" content="Keranjang belanja {{ $companyProfile->company_name }}">
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

        .cart-thumb {
            width: 82px;
            height: 82px;
            object-fit: contain;
            margin: 0 auto;
        }

        .cart-thumb--empty {
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

        .cart-remove-button {
            background: transparent;
            border: 0;
            color: #666;
            cursor: pointer;
            padding: 0;
        }

        .cart-empty {
            padding: 60px 24px;
            border: 1px dashed #d9d9d9;
            text-align: center;
        }

        .cart-empty h3 {
            margin-bottom: 14px;
        }

        .cart-empty p {
            color: #666;
            margin-bottom: 22px;
        }

        .cart-empty a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 22px;
            background: var(--brand-primary, #fed700);
            color: #242424;
            font-weight: 700;
            text-transform: uppercase;
        }

        .cart-summary {
            margin-top: 24px;
            padding: 20px 24px;
            border: 1px solid #ececec;
            background: #fffdf5;
        }

        .cart-note {
            margin-bottom: 18px;
            padding: 14px 18px;
            border-left: 3px solid var(--brand-primary, #fed700);
            background: #fffdf5;
            color: #666;
            line-height: 1.8;
        }

        .cart-quantity-form {
            display: inline-flex;
            align-items: stretch;
            justify-content: center;
            border: 1px solid #ddd;
            background: #fff;
        }

        .cart-quantity-form__button {
            width: 34px;
            border: 0;
            background: #f7f7f7;
            color: #444;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
        }

        .cart-quantity-form__input {
            width: 76px;
            border: 0;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
            padding: 10px 8px;
            color: #222;
        }

        .cart-measurement {
            color: #666;
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
    @include('partials.brand-theme')
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

            <div class="breadcrumb-area">
                <div class="container">
                    <div class="breadcrumb-content">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="active">Keranjang</li>
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
                        @if ($cartItems->isEmpty())
                            <div class="cart-empty">
                                <h3>Keranjang masih kosong</h3>
                                <p>Tambahkan produk dari landing page atau detail produk untuk mulai belanja.</p>
                                <a href="{{ route('home') }}">Lanjut Belanja</a>
                            </div>
                        @else
                            <div class="cart-note">
                                Ubah jumlah produk dengan tombol <strong>-</strong> dan <strong>+</strong>, atau ketik angka langsung lalu tekan <strong>Enter</strong>.
                            </div>

                            <div class="table-content table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="li-product-remove">remove</th>
                                            <th class="li-product-thumbnail">images</th>
                                            <th class="cart-product-name">Product</th>
                                            <th class="li-product-price">Satuan</th>
                                            <th class="li-product-price">Harga</th>
                                            <th class="li-product-quantity">Jumlah</th>
                                            <th class="li-product-subtotal">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartItems as $cartItem)
                                            <tr>
                                                <td class="li-product-remove">
                                                    <form action="{{ route('cart.destroy', $cartItem['product']) }}" method="POST" data-preserve-scroll="true">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                                                        <button type="submit" class="cart-remove-button" aria-label="Hapus dari keranjang">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="li-product-thumbnail">
                                                    <a href="{{ route('products.show', $cartItem['product']->slug) }}">
                                                        @if ($cartItem['product']->thumbnail_url)
                                                            <img class="cart-thumb" src="{{ $cartItem['product']->thumbnail_url }}" alt="{{ $cartItem['product']->name }}">
                                                        @else
                                                            <div class="cart-thumb--empty">No Image</div>
                                                        @endif
                                                    </a>
                                                </td>
                                                <td class="li-product-name">
                                                    <a href="{{ route('products.show', $cartItem['product']->slug) }}">{{ $cartItem['product']->name }}</a>
                                                </td>
                                                <td class="cart-measurement">{{ $cartItem['product']->unit_summary }}</td>
                                                <td class="li-product-price"><span class="amount">{{ $cartItem['unit_price_label'] }}</span></td>
                                                <td class="quantity">
                                                    <form action="{{ route('cart.update', $cartItem['product']) }}" method="POST" class="cart-quantity-form js-cart-quantity-form" data-preserve-scroll="true">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                                                        <input type="hidden" name="adjustment" value="">
                                                        <button type="button" class="cart-quantity-form__button js-cart-adjustment" data-adjustment="-1">-</button>
                                                        <input class="cart-quantity-form__input" type="number" name="quantity" min="{{ max((float) $cartItem['product']->min_order_quantity, 1) }}" max="{{ (float) $cartItem['product']->stock_quantity }}" step="0.001" value="{{ $cartItem['quantity_label'] }}">
                                                        <button type="button" class="cart-quantity-form__button js-cart-adjustment" data-adjustment="1">+</button>
                                                    </form>
                                                </td>
                                                <td class="product-subtotal"><span class="amount">{{ $cartItem['line_total_label'] }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="cart-summary">
                                <h4>Total sementara: <strong>{{ $cartSubtotalLabel }}</strong></h4>
                                <p>Pastikan jumlah dan alamat kirim sudah benar sebelum lanjut checkout.</p>
                                <a href="{{ route('checkout.show') }}" class="li-button li-button-fullwidth li-button-dark">
                                    <span>Lanjut ke Checkout</span>
                                </a>
                            </div>
                        @endif
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-cart-quantity-form').forEach(function (form) {
                var adjustmentInput = form.querySelector('input[name="adjustment"]');

                form.querySelectorAll('.js-cart-adjustment').forEach(function (button) {
                    button.addEventListener('click', function () {
                        if (adjustmentInput) {
                            adjustmentInput.value = button.getAttribute('data-adjustment') || '';
                        }

                        form.requestSubmit();
                    });
                });

                form.addEventListener('submit', function (event) {
                    var submitter = event.submitter;

                    if (submitter && submitter.classList.contains('js-cart-adjustment')) {
                        return;
                    }

                    if (adjustmentInput) {
                        adjustmentInput.value = '';
                    }
                });
            });
        });
    </script>
    <script src="js/main.js"></script>
</body>
</html>
