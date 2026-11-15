<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $companyProfile->company_name }} | Contact</title>
    <meta name="description" content="Contact page for {{ $companyProfile->company_name }}">
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

        .contact-map-frame,
        .contact-map-placeholder {
            width: 100%;
            min-height: 420px;
            border: 0;
        }

        .contact-map-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f7f7;
            color: #666;
            font-size: 16px;
            line-height: 1.8;
            padding: 32px;
            text-align: center;
        }

        .contact-page-message {
            line-height: 1.9;
        }

        .single-contact-block a {
            color: #666;
        }

        .contact-socials {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 28px;
        }

        .contact-socials a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: #fff;
        }

        .hb-menu nav > ul > li.site-nav-link > a::after {
            display: none !important;
        }

        .hb-menu nav > ul > li.site-nav-link > a {
            padding-right: 0;
        }

        @include('partials.site-navigation-styles')

        .contact-form-feedback {
            margin-bottom: 24px;
        }

        .contact-form-feedback__success,
        .contact-form-feedback__error {
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.8;
            padding: 14px 18px;
        }

        .contact-form-feedback__success {
            background: #edf9f0;
            border: 1px solid #b8e2c2;
            color: #25683a;
        }

        .contact-form-feedback__error {
            background: #fff0f0;
            border: 1px solid #f1c2c2;
            color: #a33a3a;
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

            .contact-map-frame,
            .contact-map-placeholder {
                min-height: 320px;
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
                                    <li><span>Contact</span></li>
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
                            <li class="active">Contact</li>
                        </ul>
                    </div>
                </div>
            </div>
        @include('partials.mobile-navigation')
        </header>

        <div class="contact-main-page mt-60 mb-40 mb-md-40 mb-sm-40 mb-xs-40">
            <div class="container mb-60">
                @if (filled($companyProfile->map_embed_url))
                    <iframe
                        class="contact-map-frame"
                        src="{{ $companyProfile->map_embed_url }}"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                @else
                    <div class="contact-map-placeholder">
                        <div>
                            <strong>{{ $companyProfile->company_name }}</strong><br>
                            {{ $companyProfile->address }}<br><br>
                            Tambahkan `Location iframe URL` di menu Management agar peta tampil di halaman ini.
                        </div>
                    </div>
                @endif
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-5 offset-lg-1 col-md-12 order-1 order-lg-2">
                        <div class="contact-page-side-content">
                            <h3 class="contact-page-title">Contact Us</h3>
                            <p class="contact-page-message mb-25">{{ $companyProfile->short_description_text }}</p>

                            <div class="single-contact-block">
                                <h4><i class="fa fa-fax"></i> Address</h4>
                                <p>{{ $companyProfile->address }}</p>
                            </div>

                            <div class="single-contact-block">
                                <h4><i class="fa fa-phone"></i> Phone</h4>
                                <p><a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyProfile->phone) }}">{{ $companyProfile->phone }}</a></p>
                            </div>

                            <div class="single-contact-block last-child">
                                <h4><i class="fa fa-envelope-o"></i> Email</h4>
                                <p><a href="mailto:{{ $companyProfile->email }}">{{ $companyProfile->email }}</a></p>
                            </div>

                            @if (count($companyProfile->socialLinks()))
                                <div class="contact-socials">
                                    @foreach ($companyProfile->socialLinks() as $socialLink)
                                        <a
                                            class="{{ $socialLink['class'] }}"
                                            href="{{ $socialLink['url'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            aria-label="{{ $socialLink['title'] }}"
                                            title="{{ $socialLink['title'] }}"
                                        >
                                            @if (filled($socialLink['icon']))
                                                <i class="fa fa-{{ $socialLink['icon'] }}"></i>
                                            @else
                                                <span class="site-social__text">{{ $socialLink['label'] ?? $socialLink['title'] }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 order-2 order-lg-1" id="partner-registration">
                        <div class="contact-form-content pt-sm-55 pt-xs-55">
                            <h3 class="contact-page-title">Pendaftaran Mitra</h3>

                            @if (session('partner_registration_success'))
                                <div class="contact-form-feedback">
                                    <div class="contact-form-feedback__success">
                                        {{ session('partner_registration_success') }}
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="contact-form-feedback">
                                    <div class="contact-form-feedback__error">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="contact-form">
                                <form action="{{ route('partner-registrations.store') }}" method="post">
                                    @csrf
                                    <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                                        <label for="website">Website</label>
                                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Lengkap <span class="required">*</span></label>
                                        <input type="text" name="full_name" value="{{ old('full_name') }}" required maxlength="120">
                                    </div>
                                    <div class="form-group">
                                        <label>No. WhatsApp <span class="required">*</span></label>
                                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" required maxlength="25">
                                    </div>
                                    <div class="form-group mb-30">
                                        <label>Keterangan</label>
                                        <textarea name="message" maxlength="2000">{{ old('message') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="li-btn-3">Daftar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
    @if ($errors->any() || session('partner_registration_success'))
        <script>
            if (window.location.hash !== '#partner-registration') {
                window.location.hash = 'partner-registration';
            }
        </script>
    @endif
</body>
</html>
