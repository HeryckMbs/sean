@props(['service', 'href' => null])

<article class="service-card">
    <div class="card-icon"><i class="material-icons">{{ $service->icon ?: 'ads_click' }}</i></div>
    <h3>{{ $service->name }}</h3>
    <p>{{ $service->summary }}</p>
    @if ($href)
        <a class="text-link" href="{{ $href }}">Ver detalhes</a>
    @endif
</article>
