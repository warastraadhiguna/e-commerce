<section class="product-area li-trending-product pt-60 pb-45">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="li-product-tab li-trending-product-tab">
                    <h2>
                        <span>Trending Products</span>
                    </h2>
                    @if ($brands->isNotEmpty())
                        <ul class="nav li-product-menu li-trending-product-menu">
                            @foreach ($brands as $brand)
                                <li>
                                    <a @class(['active' => $loop->first]) data-toggle="tab" href="#trending-brand-{{ $brand->id }}">
                                        <span>{{ $brand->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="tab-content li-tab-content li-trending-product-content">
                    @forelse ($brands as $brand)
                        <div id="trending-brand-{{ $brand->id }}" @class([
                            'tab-pane',
                            'show fade in active' => $loop->first,
                            'fade' => ! $loop->first,
                        ])>
                            @include('partials.home.product-carousel', ['products' => collect($productMap[$brand->id] ?? [])])
                        </div>
                    @empty
                        <div class="tab-pane show fade in active">
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="text-muted mb-0">Produk trending belum tersedia.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
