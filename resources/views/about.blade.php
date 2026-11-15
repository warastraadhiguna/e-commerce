<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $companyProfile->company_name }} | About Us</title>
    <meta name="description" content="About {{ $companyProfile->company_name }}">
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

        .about-text-wrap h2 {
            line-height: 1.3;
        }

        .about-text-wrap p {
            line-height: 1.9;
            text-align: justify;
        }

        .about-image-wrap {
            border-radius: 8px;
            overflow: hidden;
        }

        .about-image-wrap img {
            width: 100%;
            object-fit: cover;
        }

        .about-image-wrap--brand {
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            background: #f7f7f7;
            border: 1px solid #ececec;
        }

        .about-image-wrap--floating {
            float: right;
            width: 38%;
            margin: 8px 0 20px 40px;
        }

        .about-image-wrap--brand .site-brand {
            width: 100%;
            min-height: auto;
            justify-content: center;
        }

        .about-image-wrap--brand .site-brand__image {
            max-width: 100%;
            max-height: 220px;
            object-position: center;
        }

        .about-image-wrap--brand .site-brand__text {
            max-width: 100%;
            text-align: center;
        }

        .limupa-counter {
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px 20px;
        }

        .about-stats .col-lg-4:nth-child(2) .limupa-counter {
            background: #ededed;
        }

        .about-stats .col-lg-4:nth-child(1) .limupa-counter,
        .about-stats .col-lg-4:nth-child(3) .limupa-counter {
            background: #f7f7f7;
        }

        .team-content p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 0;
        }

        .about-value-card {
            background: #fff;
            border: 1px solid #ececec;
            height: 100%;
            padding: 28px 24px 30px;
        }

        .about-value-card__icon {
            margin-bottom: 18px;
        }

        .team-content .about-value-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #fed700;
            color: #242424;
            font-size: 18px;
            margin-bottom: 18px;
        }

        @media (max-width: 767px) {
            .about-image-wrap--floating {
                float: none;
                width: 100%;
                margin: 0 0 24px;
            }

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
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><span>About Us</span></li>
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
                            <div class="header-middle-right">
                                <ul class="hm-menu">
                                    <li class="hm-wishlist">
                                        <a href="{{ route('contact') }}">
                                            <i class="fa fa-envelope-o"></i>
                                        </a>
                                    </li>
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
                            <li class="active">About Us</li>
                        </ul>
                    </div>
                </div>
            </div>
        @include('partials.mobile-navigation')
        </header>

        <div class="about-us-wrapper pt-60 pb-40">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="about-text-wrap">
                            <div class="about-image-wrap about-image-wrap--brand about-image-wrap--floating">
                                <span class="site-brand" aria-label="{{ $companyProfile->company_name }}">
                                    @if ($companyProfile->has_logo)
                                        <img class="site-brand__image" src="{{ $companyProfile->logo_url }}" alt="{{ $companyProfile->company_name }}">
                                    @else
                                        <span class="site-brand__text">{{ $companyProfile->company_name }}</span>
                                    @endif
                                </span>
                            </div>
                            <h2><span>About</span> {{ $companyProfile->company_name }}</h2>
                            <p>{{ $companyProfile->short_description_text }}</p>
                            <p>{!! nl2br(e($companyProfile->long_description_text)) !!}</p>
                            <p>Jika kamu ingin bekerja sama atau menjadi mitra, tim kami bisa dihubungi melalui halaman contact, email <a href="mailto:{{ $companyProfile->email }}">{{ $companyProfile->email }}</a>, atau telepon <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyProfile->phone) }}">{{ $companyProfile->phone }}</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="counterup-area">
            <div class="container-fluid p-0">
                <div class="row no-gutters about-stats">
                    <div class="col-lg-4 col-md-6">
                        <div class="limupa-counter">
                            <div class="counter-img">
                                <img src="images/about-us/icon/2.png" alt="Counter Icon">
                            </div>
                            <div class="counter-info">
                                <div class="counter-number">
                                    <h3 class="counter">869</h3>
                                </div>
                                <div class="counter-text">
                                    <span>ORDERS HANDLED</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="limupa-counter">
                            <div class="counter-img">
                                <img src="images/about-us/icon/3.png" alt="Counter Icon">
                            </div>
                            <div class="counter-info">
                                <div class="counter-number">
                                    <h3 class="counter">689</h3>
                                </div>
                                <div class="counter-text">
                                    <span>SUPPORT HOURS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="limupa-counter">
                            <div class="counter-img">
                                <img src="images/about-us/icon/4.png" alt="Counter Icon">
                            </div>
                            <div class="counter-info">
                                <div class="counter-number">
                                    <h3 class="counter">216</h3>
                                </div>
                                <div class="counter-text">
                                    <span>PARTNERS GROWING</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($aboutValues->isNotEmpty())
            @php
                $aboutValueColumnClass = match ($aboutValues->count()) {
                    1 => 'col-lg-12 col-md-12 col-sm-12',
                    2 => 'col-lg-6 col-md-6 col-sm-6',
                    3 => 'col-lg-4 col-md-6 col-sm-6',
                    default => 'col-lg-3 col-md-6 col-sm-6',
                };
            @endphp
            <div class="team-area pt-60 pt-sm-44">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="li-section-title capitalize mb-25">
                                <h2><span>our values</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($aboutValues as $aboutValue)
                            <div class="{{ $aboutValueColumnClass }}">
                                <div class="team-member mb-30 mb-sm-30 mb-xs-30">
                                    <div class="team-content text-center about-value-card">
                                        <div class="about-value-card__icon">
                                            <span class="about-value-icon"><i class="fa fa-{{ $aboutValue->icon }}"></i></span>
                                        </div>
                                        <h3>{{ $aboutValue->title }}</h3>
                                        <p>{{ $aboutValue->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <footer>
            <div class="footer">
                <div class="footer-static-top">
                    <div class="container">
                        <div class="footer-shipping pt-60 pb-55">
                            <div class="row justify-content-center">
                                <div class="col-12 text-center">
                                    <p class="mb-0">&copy; {{ now()->year }} {{ $companyProfile->company_name }}. All rights reserved.</p>
                                </div>
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
    <script src="js/main.js"></script>
</body>
</html>
