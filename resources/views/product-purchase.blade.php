<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $product->name }} | Pembelian</title>
    <meta name="description" content="Akses pembelian {{ $product->name }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="{{ asset('desain') }}/">
    <link rel="icon" href="{{ $companyProfile->favicon_href }}">
    <link rel="shortcut icon" href="{{ $companyProfile->favicon_href }}">
    <link rel="apple-touch-icon" href="{{ $companyProfile->favicon_href }}">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/helper.css">
    <link rel="stylesheet" href="{{ asset('desain/style.css') }}?v={{ filemtime(public_path('desain/style.css')) }}">
    <style>
        .purchase-card {
            padding: 36px 40px;
            border: 1px solid #ececec;
            background: #fff;
        }

        .purchase-meta {
            margin: 18px 0 0;
            padding: 0;
            list-style: none;
        }

        .purchase-meta li {
            padding: 8px 0;
            color: #666;
            border-bottom: 1px solid #f3f3f3;
        }

        .purchase-meta li strong {
            display: inline-block;
            min-width: 180px;
            color: #222;
        }

        .purchase-alert {
            margin-top: 20px;
            padding: 16px 18px;
            background: #fff9e8;
            border: 1px solid #f2df9a;
            color: #6d5610;
            line-height: 1.8;
        }
    </style>
    @include('partials.brand-theme')
</head>
<body>
    @php
        $displayPrice = $product->displayPriceFor($buyer);
        $oldPrice = $product->oldPriceFor($buyer);
        $displayPriceLabel = $product->displayPriceLabelFor($buyer);
        $oldPriceLabel = $product->oldPriceLabelFor($buyer);
        $discountPercentage = $product->discountPercentageFor($buyer);
    @endphp
    <div class="body-wrapper">
        <div class="breadcrumb-area">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></li>
                        <li class="active">Pembelian</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-wraper pt-60 pb-60">
            <div class="container">
                <div class="purchase-card">
                    <h2>{{ $product->name }}</h2>
                    <div class="price-box pt-20">
                        <span class="new-price new-price-2">{{ $displayPriceLabel }}</span>
                        @if ($oldPrice)
                            <span class="old-price">{{ $oldPriceLabel }}</span>
                            @if ($discountPercentage)
                                <span class="discount-percentage">-{{ $discountPercentage }}%</span>
                            @endif
                        @endif
                    </div>

                    <ul class="purchase-meta">
                        <li><strong>Akun login</strong>{{ $buyer->name }} ({{ $buyer->role->label() }})</li>
                        <li><strong>Brand</strong>{{ $product->brand?->name ?: '-' }}</li>
                        <li><strong>Tipe</strong>{{ $product->productType?->name ?: '-' }}</li>
                        <li><strong>Kategori</strong>{{ $product->category?->full_name ?: '-' }}</li>
                        <li><strong>Stok</strong>{{ rtrim(rtrim(number_format((float) $product->stock_quantity, 3, '.', ''), '0'), '.') }} {{ $product->measurementUnit?->symbol }}</li>
                    </ul>

                    <div class="purchase-alert">
                        @if ($buyer->isEligibleForPartnerPricing() && $product->has_partner_discount)
                            Harga khusus mitra sudah aktif untuk akun kamu.
                        @elseif ($buyer->isMitra() && $product->has_partner_discount)
                            Akun kamu adalah mitra, tetapi harga khusus belum aktif karena omzet belum memenuhi syarat atau belum disetujui admin/superadmin.
                        @else
                            Harga yang tampil saat ini adalah harga yang berlaku untuk akun kamu.
                        @endif
                        Keranjang dan checkout penuh belum dibuat. Fondasi login, validasi role, dan hak harga akun sudah aktif.
                    </div>

                    <div class="pt-30">
                        <a class="li-button li-button-dark" href="{{ route('products.show', $product->slug) }}">Kembali ke produk</a>
                        <a class="li-button" href="{{ route('contact') }}">Hubungi kami</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.public-flash-alerts')
</body>
</html>
