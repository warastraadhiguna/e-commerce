<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $companyProfile->company_name }} | Login</title>
    <meta name="description" content="Login {{ $companyProfile->company_name }}">
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

        .auth-page-section {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(254, 215, 0, .28), transparent 34%),
                linear-gradient(135deg, #fffdf3 0%, #ffffff 54%, #fff7d1 100%);
        }

        .auth-page-section::before,
        .auth-page-section::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .auth-page-section::before {
            width: 360px;
            height: 360px;
            right: -120px;
            top: -90px;
            background: rgba(254, 215, 0, .22);
        }

        .auth-page-section::after {
            width: 280px;
            height: 280px;
            left: -120px;
            bottom: -100px;
            background: rgba(36, 36, 36, .05);
        }

        .auth-stage {
            position: relative;
            z-index: 1;
            padding: 24px 0 10px;
        }

        .auth-login-card,
        .auth-register-card {
            height: 100%;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 55px rgba(36, 36, 36, .10);
        }

        .auth-login-card {
            background: #fff;
            border: 1px solid rgba(36, 36, 36, .07);
        }

        .auth-register-card {
            position: relative;
            background: linear-gradient(150deg, #242424 0%, #343434 58%, #4a4a4a 100%);
            color: #fff;
        }

        .auth-register-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(254, 215, 0, .55), transparent 28%),
                radial-gradient(circle at bottom left, rgba(254, 215, 0, .22), transparent 34%);
            pointer-events: none;
        }

        .auth-login-card__inner,
        .auth-register-card__inner {
            position: relative;
            z-index: 1;
            padding: 42px 42px 46px;
        }

        .auth-kicker {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 0 14px;
            border-radius: 999px;
            background: rgba(254, 215, 0, .16);
            color: #8a6a00;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .auth-login-card .auth-kicker {
            background: #fff7cc;
        }

        .auth-title {
            color: #242424;
            font-size: 34px;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 14px;
        }

        .auth-subtitle {
            color: #666;
            font-size: 16px;
            line-height: 1.9;
            margin-bottom: 0;
        }

        .auth-divider {
            width: 72px;
            height: 4px;
            border-radius: 999px;
            background: #fed700;
            margin: 24px 0 28px;
        }

        .auth-login-card label {
            display: block;
            color: #242424;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .auth-login-card input[type="tel"],
        .auth-login-card input[name="password"] {
            height: 54px;
            border: 1px solid #e4e1d5;
            border-radius: 12px;
            background: #fffef8;
            padding: 0 18px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .auth-login-card input[type="tel"]:focus,
        .auth-login-card input[name="password"]:focus {
            border-color: #fed700;
            box-shadow: 0 0 0 4px rgba(254, 215, 0, .18);
            background: #fff;
            outline: none;
        }

        .auth-password-field {
            position: relative;
        }

        .auth-password-field input[name="password"] {
            padding-right: 56px;
        }

        .auth-password-toggle {
            position: absolute;
            right: 6px;
            top: 5px;
            width: 44px;
            height: 44px;
            padding: 0;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #666;
            font-size: 19px;
            cursor: pointer;
        }

        .auth-password-toggle:hover {
            color: #242424;
            background: rgba(254, 215, 0, .15);
        }

        .auth-password-toggle:focus-visible {
            outline: 2px solid #b58900;
            outline-offset: 1px;
        }

        .auth-alert {
            margin-bottom: 22px;
            padding: 14px 16px;
            border: 1px solid #f3caca;
            border-radius: 12px;
            background: #fff4f4;
            color: #9b2f2f;
        }

        .auth-login-card .check-box label {
            display: inline-flex;
            align-items: center;
            color: #666;
            font-weight: 500;
            line-height: 1;
            margin-bottom: 0;
        }

        .auth-login-card .check-box {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            line-height: 1;
        }

        .auth-login-card .check-box input[type="checkbox"] {
            width: 16px;
            height: 16px;
            min-width: 16px;
            min-height: 16px;
            margin: 0;
            vertical-align: middle;
            position: relative;
            top: -1px;
        }

        .auth-submit {
            width: 100%;
            min-height: 54px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(90deg, #fed700 0%, #ffcb05 100%);
            color: #242424;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .auth-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(254, 215, 0, .25);
        }

        .auth-register-card .auth-kicker {
            background: rgba(254, 215, 0, .16);
            color: #ffe27a;
        }

        .auth-register-card .auth-title,
        .auth-register-card .auth-subtitle {
            color: #fff;
        }

        .auth-register-card .auth-subtitle {
            color: rgba(255, 255, 255, .82);
        }

        .auth-feature-list {
            margin: 30px 0 0;
            padding: 0;
            list-style: none;
        }

        .auth-feature-list li {
            position: relative;
            padding: 0 0 0 28px;
            color: rgba(255, 255, 255, .9);
            line-height: 1.85;
            margin-bottom: 14px;
        }

        .auth-feature-list li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 11px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #fed700;
            box-shadow: 0 0 0 6px rgba(254, 215, 0, .14);
        }

        .auth-register-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .auth-register-primary,
        .auth-register-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .auth-register-primary {
            background: #fed700;
            color: #242424;
        }

        .auth-register-secondary {
            border: 1px solid rgba(255, 255, 255, .24);
            color: #fff;
        }

        .auth-register-primary:hover,
        .auth-register-secondary:hover {
            color: inherit;
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

            .auth-login-card__inner,
            .auth-register-card__inner {
                padding: 32px 24px 34px;
            }

            .auth-title {
                font-size: 28px;
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
                    </div>
                </div>
            </div>
            <div class="breadcrumb-area">
                <div class="container">
                    <div class="breadcrumb-content">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="active">Login</li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-section mb-60 auth-page-section">
            <div class="container">
                <div class="row auth-stage">
                    <div class="col-sm-12 col-md-12 col-xs-12 col-lg-6 mb-30">
                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf
                            <div class="auth-login-card">
                                <div class="auth-login-card__inner">
                                    <span class="auth-kicker">Member Access</span>
                                    <h4 class="auth-title">Masuk untuk lanjut pembelian</h4>
                                    <p class="auth-subtitle">Buyer dan mitra memakai login yang sama. Masuk dengan nomor HP akun kamu, lalu harga akan menyesuaikan otomatis berdasarkan status akun.</p>
                                    <div class="auth-divider"></div>

                                    @if ($errors->any())
                                        <div class="auth-alert">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-12 col-12 mb-20">
                                            <label>Nomor HP</label>
                                            <input class="mb-0" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" autofocus inputmode="tel" placeholder="Contoh: 08123456789">
                                        </div>
                                        <div class="col-12 mb-20">
                                            <label for="login-password">Password</label>
                                            <div class="auth-password-field">
                                                <input id="login-password" class="mb-0" type="password" name="password" required autocomplete="current-password">
                                                <button id="toggle-login-password" class="auth-password-toggle" type="button" aria-controls="login-password" aria-label="Tampilkan password" aria-pressed="false" title="Tampilkan password">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-8 mb-20">
                                            <div class="check-box d-inline-block ml-0 ml-md-2 mt-10">
                                                <input type="checkbox" id="remember_me" name="remember" value="1" @checked(old('remember'))>
                                                <label for="remember_me">Ingat saya</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="hidden" name="redirect" value="{{ $redirectTo }}">
                                            <button class="auth-submit">Login</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-6">
                        <div class="auth-register-card">
                            <div class="auth-register-card__inner">
                                <span class="auth-kicker">Join as Partner</span>
                                <h4 class="auth-title">Pendaftaran Mitra</h4>
                                <p class="auth-subtitle">Kalau kamu ingin mendapatkan akses harga khusus, daftarkan akun mitra kamu terlebih dahulu melalui halaman contact.</p>
                                <ul class="auth-feature-list">
                                    <li>Pendaftaran mitra langsung terhubung ke admin.</li>
                                    <li>Status harga khusus aktif setelah evaluasi omzet dan approval admin atau superadmin.</li>
                                    <li>Buyer tetap bisa login dan membeli dengan harga normal.</li>
                                </ul>
                                <div class="auth-register-actions">
                                    <a class="auth-register-primary" href="{{ route('contact') }}#partner-registration">Pendaftaran Mitra</a>
                                    <a class="auth-register-secondary" href="{{ route('home') }}">Kembali ke Home</a>
                                </div>
                            </div>
                        </div>
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
    <script>
        jQuery('.ht-setting-trigger').on('click', function () {
            jQuery(this).toggleClass('is-active').siblings('.ht-setting').slideToggle();
        });

        document.getElementById('toggle-login-password').addEventListener('click', function () {
            const password = document.getElementById('login-password');
            const showPassword = password.type === 'password';
            password.type = showPassword ? 'text' : 'password';
            this.setAttribute('aria-pressed', String(showPassword));
            const label = showPassword ? 'Sembunyikan password' : 'Tampilkan password';
            this.setAttribute('aria-label', label);
            this.title = label;
            this.querySelector('i').className = showPassword ? 'fa fa-eye-slash' : 'fa fa-eye';
        });
    </script>
</body>
</html>
