@php($brandTheme = $companyProfile->theme())
<style id="store-brand-theme">
    :root {
        --brand-primary: {{ $brandTheme['primary'] }};
        --brand-primary-rgb: {{ $brandTheme['primary_rgb'] }};
        --brand-secondary: {{ $brandTheme['secondary'] }};
        --brand-background: {{ $brandTheme['background'] }};
        --brand-on-primary: {{ $brandTheme['on_primary'] }};
        --brand-on-secondary: {{ $brandTheme['on_secondary'] }};
        --brand-font: {!! $brandTheme['font'] !!};
    }
    body { font-family: var(--brand-font); }
    body:not(.invoice-page) {
        background-color: var(--brand-background);
        @if ($companyProfile->pattern_url)
            background-image: url("{{ $companyProfile->pattern_url }}");
            background-repeat: repeat;
            background-size: 320px auto;
        @endif
    }
    h1, h2, h3, h4, h5, h6, input, select, textarea, button { font-family: var(--brand-font); }
    .header-bottom, .header-sticky.sticky { background-color: var(--brand-primary); }
    .hb-menu nav > ul > li > a,
    .hb-menu nav > ul > li > a::after,
    .public-mobile-navigation a,
    .hm-searchbox .li-btn,
    .cart-item-count,
    .auth-submit, .auth-register-primary,
    .checkout-submit, .checkout-address-option__badge,
    .default-btn .links, .li-button, .li-btn-3,
    .add-actions-link .add-cart > a, .add-actions-link .add-cart button,
    .cart-quantity .add-to-cart, .cart-page-actions__primary,
    .invoice-page .button {
        color: var(--brand-on-primary);
    }
    .hb-menu nav > ul > li:hover > a,
    .hb-menu nav > ul > li:hover > a::after { color: var(--brand-on-primary); opacity: .8; }
    .product-image > span.sticker { color: var(--brand-on-secondary); }
    .hm-minicart-trigger { background: var(--brand-secondary); border-color: var(--brand-secondary); }
    .auth-page-section { background: linear-gradient(135deg, rgba(var(--brand-primary-rgb), .12), var(--brand-background)); }
    .auth-login-card input[type="tel"], .auth-login-card input[name="password"] { background: var(--brand-background); }
    .auth-submit, .auth-register-primary { background: var(--brand-primary); }
    .default-btn .links:hover, .auth-submit:hover, .auth-register-primary:hover,
    .hm-searchbox .li-btn:hover, .li-button:hover, .cart-quantity .add-to-cart:hover {
        background: var(--brand-secondary); color: var(--brand-on-secondary);
    }
    .site-brand__text, .brand { font-family: var(--brand-font); }
    .invoice-logo { max-width: 220px; max-height: 72px; object-fit: contain; }
    .brand-intro { padding: 48px 0; background: linear-gradient(120deg, rgba(var(--brand-primary-rgb), .09), var(--brand-background)); }
    .brand-intro__grid { display: grid; grid-template-columns: 1.15fr 1fr; align-items: center; gap: 60px; }
    .brand-intro__eyebrow { color: var(--brand-primary); font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .brand-intro h1 { font-size: 52px; line-height: 1.12; margin: 20px 0 14px; color: var(--brand-primary); }
    .brand-intro h2 { font-size: 27px; line-height: 1.35; margin-bottom: 20px; }
    .brand-intro p { font-size: 16px; line-height: 1.9; color: #56657a; max-width: 520px; }
    .brand-intro__actions { display: flex; flex-wrap: wrap; align-items: center; gap: 24px; margin-top: 28px; }
    .brand-intro__primary { padding: 14px 24px; background: var(--brand-primary); color: var(--brand-on-primary); border-radius: 6px; font-weight: 600; }
    .brand-intro__primary:hover { background: var(--brand-secondary); color: var(--brand-on-secondary); }
    .brand-intro__image { display: block; background: #fff; border-radius: 12px; overflow: hidden; }
    .brand-intro__image img { display: block; width: 100%; max-height: 480px; object-fit: contain; }
    .instagram-showcase__grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 24px; }
    .instagram-showcase__card { background: #fff; border: 1px solid #e6ebf2; border-radius: 10px; overflow: hidden; }
    .instagram-showcase__card img { width: 100%; aspect-ratio: 4 / 5; object-fit: contain; background: #f3f6fb; }
    .instagram-showcase__card a > div { padding: 20px; }
    .instagram-showcase__card h3 { font-size: 16px; line-height: 1.6; color: #25354d; }
    .instagram-showcase__card span { color: var(--brand-primary); font-size: 13px; }
    @media (max-width: 991px) { .brand-intro__grid { gap: 24px; } .brand-intro h1 { font-size: 38px; } .instagram-showcase__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 575px) { .brand-intro { padding: 32px 0; } .brand-intro__grid { grid-template-columns: 1fr; } .brand-intro__image img { max-height: 360px; } .instagram-showcase__grid { gap: 12px; } .instagram-showcase__card a > div { padding: 12px; } .instagram-showcase__card h3 { font-size: 13px; } }
</style>
