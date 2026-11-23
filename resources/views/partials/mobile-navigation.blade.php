<nav class="public-mobile-navigation" aria-label="Navigasi seluler">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('home', ['browse' => 1]) }}#search-results">Katalog</a>
    <a href="{{ route('cart.index') }}">Keranjang</a>
    <a href="{{ auth()->user()?->isShopper() ? route('account.show') : route('login') }}">Akun</a>
</nav>
<style>
    .public-mobile-navigation { display: none; }
    @media (max-width: 991px) {
        .public-mobile-navigation { display: flex; justify-content: space-around; gap: 8px; padding: 14px 12px; background: var(--brand-primary, #fed700); }
        .public-mobile-navigation a { color: #242424; font-size: 13px; font-weight: 700; }
    }
</style>
