<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $companyProfile->company_name }} | {{ $order->order_number }}</title>
    <meta name="description" content="Detail pesanan {{ $order->order_number }}">
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
        .site-brand { display: inline-flex; align-items: center; justify-content: flex-start; width: 220px; max-width: 100%; min-height: 64px; }
        .site-brand__image { display: block; max-width: 220px; max-height: 64px; width: auto; height: auto; object-fit: contain; object-position: left center; }
        .site-brand__text { display: inline-block; max-width: 220px; color: #242424; font-size: 34px; font-weight: 700; line-height: 1.1; letter-spacing: .04em; text-transform: uppercase; }
        .public-setting-button { background: transparent; border: 0; color: #444; cursor: pointer; padding: 0; }
        .hb-menu nav > ul > li.site-nav-link > a::after { display: none !important; }
        .hb-menu nav > ul > li.site-nav-link > a { padding-right: 0; }
        @include('partials.site-navigation-styles')
        .order-card { padding: 24px 26px; border: 1px solid #ececec; background: #fff; margin-bottom: 24px; }
        .order-card h3 { margin-bottom: 18px; }
        .order-status-badge { display: inline-flex; align-items: center; min-height: 28px; padding: 0 12px; border-radius: 999px; background: #fff3cd; color: #7c5a00; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .order-status-badge--processing { background: #d1ecf1; color: #0c5460; }
        .order-status-badge--completed { background: #d4edda; color: #155724; }
        .order-status-badge--cancelled { background: #f8d7da; color: #721c24; }
        .order-meta-list, .order-address-list { margin: 0; padding: 0; list-style: none; }
        .order-meta-list li, .order-address-list li { padding: 8px 0; border-bottom: 1px solid #f2f2f2; color: #666; }
        .order-meta-list li strong, .order-address-list li strong { display: inline-block; min-width: 150px; color: #222; }
        .order-notes { margin-top: 16px; padding: 16px 18px; border-left: 3px solid var(--brand-primary, #fed700); background: #fffdf5; color: #666; line-height: 1.8; }
        .order-actions { margin-top: 24px; }
        .order-actions a { display: inline-flex; align-items: center; justify-content: center; min-height: 46px; padding: 0 24px; background: var(--brand-primary, #fed700); color: #242424; font-weight: 700; text-transform: uppercase; }
        .order-grid--compact { display: grid; grid-template-columns: 1fr 1.15fr; gap: 24px; align-items: start; }
        .order-items-table th,
        .order-items-table td { padding: 12px 14px; }
        .order-items-table small { color: #777; }
        @media (max-width: 767px) {
            .site-brand { justify-content: center; width: 200px; min-height: 56px; }
            .site-brand__image { max-width: 200px; max-height: 56px; }
            .site-brand__text { font-size: 28px; text-align: center; }
            .order-card { padding: 22px 18px; }
            .order-grid--compact { display: block; }
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
        $statusClass = match ($order->status?->value) {
            'processing' => 'order-status-badge--processing',
            'completed' => 'order-status-badge--completed',
            'cancelled' => 'order-status-badge--cancelled',
            default => '',
        };
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
                            <div class="hb-menu"><nav>@include('partials.site-navigation')</nav></div>
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
                            <li class="active">{{ $order->order_number }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        @include('partials.mobile-navigation')
        </header>

        <div class="checkout-area pt-60 pb-60">
            <div class="container">
                <div class="order-grid--compact">
                    <div>
                        <div class="order-card">
                            <h3>Informasi Pesanan</h3>
                            <ul class="order-meta-list">
                                <li><strong>No. Order</strong>{{ $order->order_number }}</li>
                                <li><strong>Status</strong><span class="order-status-badge {{ $statusClass }}">{{ $order->status->label() }}</span></li>
                                <li><strong>Tanggal Checkout</strong>{{ $order->checked_out_at?->format('d M Y H:i') ?: '-' }}</li>
                                <li><strong>Total Item</strong>{{ $order->item_count }} produk</li>
                                <li><strong>Total Qty</strong>{{ $order->total_quantity_label }}</li>
                                <li><strong>Total Belanja</strong>{{ $order->total_label }}</li>
                                <li><strong>Pembayaran</strong>{{ $order->payment_status_label }}</li>
                                <li><strong>Tipe Harga</strong>{{ $order->uses_partner_pricing ? 'Harga mitra' : 'Harga buyer' }}</li>
                            </ul>

                            @if ($order->user_notes)
                                <div class="order-notes">
                                    <strong>Catatan pesanan:</strong><br>
                                    {{ $order->user_notes }}
                                </div>
                            @endif

                            <div class="order-actions">
                                <a href="{{ route('orders.invoice', $order) }}" target="_blank" rel="noopener">Cetak Invoice</a>
                                <a href="{{ route('home') }}">Lanjut Belanja</a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="order-card mb-30">
                            <h3>Alamat Pengiriman</h3>
                            <ul class="order-address-list">
                                <li><strong>Label</strong>{{ $order->shipping_label ?: 'Alamat Kirim' }}</li>
                                <li><strong>Penerima</strong>{{ $order->shipping_recipient_name }}</li>
                                <li><strong>Nomor HP</strong>{{ $order->shipping_phone ?: '-' }}</li>
                                <li><strong>Alamat</strong>{{ $order->shipping_full_address }}</li>
                                @if ($order->shipping_notes)
                                    <li><strong>Catatan alamat</strong>{{ $order->shipping_notes }}</li>
                                @endif
                            </ul>
                        </div>

                        <div class="order-card">
                            <h3>Item Pesanan</h3>
                            <div class="table-content table-responsive">
                                <table class="table order-items-table">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td>{{ $item->product_name }}<br><small>{{ $item->measurement_summary ?: '-' }}</small></td>
                                                <td>{{ $item->unit_price_label }}</td>
                                                <td>{{ $item->quantity_label }}</td>
                                                <td>{{ $item->line_total_label }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total</th>
                                            <th>{{ $order->total_label }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
    @include('partials.public-scroll-restore')
    <script src="js/main.js"></script>
</body>
</html>
