@props(['url' => null, 'alt' => null, 'wide' => false])

@if ($url)
    <figure class="section-media {{ $wide ? 'section-media--wide' : '' }}">
        <img src="{{ $url }}" alt="{{ $alt ?? '' }}">
    </figure>
@endif
