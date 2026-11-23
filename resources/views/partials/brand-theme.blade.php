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
</style>
