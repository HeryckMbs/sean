@props(['section', 'align' => 'left'])

<div class="section-title {{ $align === 'center' ? 'center-align' : '' }}">
    @if ($section?->eyebrow)
        <span class="eyebrow">{{ $section->eyebrow }}</span>
    @endif
    <h2>{{ $section?->title }}</h2>
    @if ($section?->subtitle)
        <p>{{ $section->subtitle }}</p>
    @endif
</div>
