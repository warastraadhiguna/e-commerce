@php($showcasePosts = $homeMedia->where('section', 'instagram_showcase')->sortBy('sort_order'))
@if ($showcasePosts->isNotEmpty())
    <section id="galeri-kegiatan" class="instagram-showcase pt-60 pb-60">
        <div class="container">
            <div class="li-section-title"><h2><span>Cerita &amp; Kegiatan</span></h2></div>
            <p class="text-muted mt-20 mb-30">Mengenal karya lokal dan kegiatan melalui publikasi Instagram. Klik gambar untuk membuka sumber aslinya.</p>
            <div class="instagram-showcase__grid">
                @foreach ($showcasePosts as $post)
                    <article class="instagram-showcase__card">
                        <a href="{{ $post->link_href }}" target="_blank" rel="noopener noreferrer"><img src="{{ $post->image_url }}" alt="{{ $post->label }}" loading="lazy"><div><h3>{{ $post->label }}</h3><span>Lihat di Instagram ↗</span></div></a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
