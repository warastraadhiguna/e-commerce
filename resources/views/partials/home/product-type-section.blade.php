<section class="product-area {{ $sectionClasses }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="li-section-title">
                    <h2>
                        <span>{{ $title }}</span>
                    </h2>
                    @if ($filters->isNotEmpty())
                        <ul class="li-sub-category-list">
                            @foreach ($filters as $filter)
                                <li @class(['active' => $loop->first])>
                                    <a href="#">{{ $filter->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                @include('partials.home.product-carousel', ['products' => $products])
            </div>
        </div>
    </div>
</section>
