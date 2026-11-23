@php
    $useCarousel = ($useCarousel ?? true) && $products->count() > 5;
@endphp

@if ($products->isNotEmpty())
    @if ($useCarousel)
        <div class="row">
            <div class="product-active owl-carousel">
                @foreach ($products as $product)
                    <div class="col-lg-12">
                        @include('partials.home.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    @include('partials.home.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    @endif
@else
    <div class="row">
        <div class="col-lg-12">
            <p class="text-muted mb-0">Produk belum tersedia.</p>
        </div>
    </div>
@endif
