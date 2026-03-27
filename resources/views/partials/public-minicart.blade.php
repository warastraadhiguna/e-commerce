<li class="hm-minicart">
    <div class="hm-minicart-trigger">
        <span class="item-icon"></span>
        <span class="item-text">{{ $cartSubtotalLabel }}
            <span class="cart-item-count">{{ $cartBadgeCount }}</span>
        </span>
    </div>
    <span></span>
    <div class="minicart">
        <ul class="minicart-product-list">
            @forelse ($cartItems as $cartItem)
                <li>
                    @if ($cartItem['product']->thumbnail_url)
                        <a href="{{ route('products.show', $cartItem['product']->slug) }}" class="minicart-product-image">
                            <img src="{{ $cartItem['product']->thumbnail_url }}" alt="{{ $cartItem['product']->name }}">
                        </a>
                    @endif
                    <div class="minicart-product-details">
                        <h6><a href="{{ route('products.show', $cartItem['product']->slug) }}">{{ $cartItem['product']->name }}</a></h6>
                        <span>{{ $cartItem['unit_price_label'] }} x {{ $cartItem['quantity_label'] }}</span>
                    </div>
                    <form action="{{ route('cart.destroy', $cartItem['product']) }}" method="POST" data-preserve-scroll="true">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                        <button class="close" title="Remove" type="submit">
                            <i class="fa fa-close"></i>
                        </button>
                    </form>
                </li>
            @empty
                <li>
                    <div class="minicart-product-details">
                        <h6>Keranjang masih kosong.</h6>
                        <span>Belum ada produk yang ditambahkan.</span>
                    </div>
                </li>
            @endforelse
        </ul>
        <p class="minicart-total">SUBTOTAL: <span>{{ $cartSubtotalLabel }}</span></p>
        <div class="minicart-button">
            <a href="{{ route('cart.index') }}" class="li-button li-button-fullwidth li-button-dark">
                <span>Lihat Keranjang</span>
            </a>
            @if ($cartItems->isNotEmpty())
                <a href="{{ route('checkout.show') }}" class="li-button li-button-fullwidth">
                    <span>Checkout</span>
                </a>
            @endif
        </div>
    </div>
</li>
