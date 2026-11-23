<section class="brand-intro">
    <div class="container"><div class="brand-intro__grid">
        <div>
            <span class="brand-intro__eyebrow">Karya lokal · Wastra · Kuliner</span>
            <h1>{{ $companyProfile->company_name }}</h1>
            <h2>{{ $brandHero->label }}</h2>
            <p>{{ $companyProfile->short_description_text }}</p>
            <div class="brand-intro__actions"><a class="brand-intro__primary" href="{{ route('home', ['browse' => 1]) }}#search-results">Jelajahi Katalog</a><a href="#galeri-kegiatan">Cerita &amp; Kegiatan <span aria-hidden="true">↓</span></a></div>
        </div>
        <a class="brand-intro__image" href="{{ $brandHero->link_href }}" target="_blank" rel="noopener noreferrer"><img src="{{ $brandHero->image_url }}" alt="{{ $brandHero->label }}"></a>
    </div></div>
</section>
