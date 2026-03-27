@php
    $currentShopper = auth()->user();
    $quickViewDescription = trim(strip_tags($product->excerpt ?: $product->description ?: 'Deskripsi produk belum tersedia.'));
    $quickViewImage = $product->thumbnail_url;
    $displayPrice = $product->displayPriceFor($currentShopper);
    $oldPrice = $product->oldPriceFor($currentShopper);
    $displayPriceLabel = $product->displayPriceLabelFor($currentShopper);
    $oldPriceLabel = $product->oldPriceLabelFor($currentShopper);
    $discountPercentage = $product->discountPercentageFor($currentShopper);
    $quickViewMeta = collect([
        $product->brand?->name,
        $product->category?->name,
        $product->unit_summary,
    ])->filter()->join(' • ');
    $productUrl = route('products.show', $product->slug);
    $canPurchase = $currentShopper?->isShopper() ?? false;
    $loginUrl = route('login', ['redirect' => request()->getRequestUri()]);
    $purchaseLabel = $canPurchase ? 'Beli' : 'Login untuk beli';
    $minimumOrderQuantity = rtrim(rtrim(number_format((float) $product->min_order_quantity, 3, '.', ''), '0'), '.');
    $isWishlisted = $wishlistProductIds->contains($product->getKey());
    $wishlistRedirect = request()->getRequestUri();
@endphp

<div class="single-product-wrap">
    <div class="product-image">
        @if ($product->thumbnail_url)
            <a href="{{ $productUrl }}">
                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
            </a>
        @else
            <a href="{{ $productUrl }}">
                <div class="product-image__empty">No Image</div>
            </a>
        @endif
        @if ($product->is_new_arrival)
            <span class="sticker">New</span>
        @endif
    </div>
    <div class="product_desc">
        <div class="product_desc_info">
            <div class="product-review">
                <h5 class="manufacturer">
                    <a href="#">{{ $product->brand?->name ?: '-' }}</a>
                </h5>
                {{--
                <div class="rating-box">
                    <ul class="rating">
                        <li><i class="fa fa-star-o"></i></li>
                        <li><i class="fa fa-star-o"></i></li>
                        <li><i class="fa fa-star-o"></i></li>
                        <li class="no-star"><i class="fa fa-star-o"></i></li>
                        <li class="no-star"><i class="fa fa-star-o"></i></li>
                    </ul>
                </div>
                --}}
            </div>
            <h4>
                <a class="product_name" href="{{ $productUrl }}">
                    {{ \Illuminate\Support\Str::limit($product->name, 42) }}
                </a>
            </h4>
            <div class="price-box">
                <span class="new-price{{ $oldPrice ? ' new-price-2' : '' }}">
                    {{ $displayPriceLabel }}
                </span>
                @if ($oldPrice)
                    <span class="old-price">{{ $oldPriceLabel }}</span>
                    @if ($discountPercentage)
                        <span class="discount-percentage">-{{ $discountPercentage }}%</span>
                    @endif
                @endif
            </div>
        </div>
        <div class="add-actions">
            <ul class="add-actions-link">
                <li class="add-cart active">
                    @if ($canPurchase)
                        <form action="{{ route('cart.store', $product) }}" method="POST" data-preserve-scroll="true">
                            @csrf
                            <input type="hidden" name="quantity" value="{{ $minimumOrderQuantity ?: '1' }}">
                            <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                            <button type="submit">{{ $purchaseLabel }}</button>
                        </form>
                    @else
                        <a href="{{ $loginUrl }}">{{ $purchaseLabel }}</a>
                    @endif
                </li>
                <li>
                    <form action="{{ route('wishlist.store', $product) }}" method="POST" data-preserve-scroll="true">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ $wishlistRedirect }}">
                        <button type="submit" class="links-details{{ $isWishlisted ? ' is-active' : '' }}" aria-label="{{ $isWishlisted ? 'Produk sudah ada di wishlist' : 'Tambah ke wishlist' }}">
                            <i class="fa fa-heart-o"></i>
                        </button>
                    </form>
                </li>
                <li>
                    <a
                        href="#"
                        title="quick view"
                        class="quick-view-btn js-quick-view-trigger"
                        data-product-name="{{ e($product->name) }}"
                        data-product-sku="{{ e($product->sku) }}"
                        data-product-price="{{ $displayPriceLabel }}"
                        data-product-old-price="{{ $oldPriceLabel ?: '' }}"
                        data-product-discount="{{ $discountPercentage ? '-'.$discountPercentage.'%' : '' }}"
                        data-product-description="{{ e(\Illuminate\Support\Str::limit($quickViewDescription, 240)) }}"
                        data-product-image="{{ $quickViewImage ? e($quickViewImage) : '' }}"
                        data-product-meta="{{ e($quickViewMeta) }}"
                        data-product-url="{{ e($productUrl) }}"
                        data-product-min-qty="{{ e($minimumOrderQuantity ?: '1') }}"
                        data-product-cart-url="{{ $canPurchase ? e(route('cart.store', $product)) : '' }}"
                        data-product-login-url="{{ e($loginUrl) }}"
                        data-product-buy-label="{{ e($purchaseLabel) }}"
                        data-product-can-purchase="{{ $canPurchase ? '1' : '0' }}"
                    >
                        <i class="fa fa-eye"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
