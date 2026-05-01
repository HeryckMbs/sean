@props(['settings', 'menuLinks', 'logoUrl', 'whatsappUrl', 'contactUrl' => null])

@php
    $contactUrl = $contactUrl ?: route('home').'#contato';
@endphp

<div class="navbar-fixed">
    <nav class="site-nav">
        <div class="nav-wrapper container-wide">
            <a href="{{ route('home') }}" class="brand-logo site-brand" aria-label="{{ $settings['brand_name'] ?? 'Perfil Digital Ads' }}">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $settings['brand_name'] ?? 'Perfil Digital Ads' }}">
                @else
                    <span>Perfil <strong>Digital Ads</strong></span>
                @endif
            </a>

            <a href="#" data-target="mobile-menu" class="sidenav-trigger right"><i class="material-icons">menu</i></a>

            <ul class="right hide-on-med-and-down main-menu">
                @foreach ($menuLinks as $link)
                    <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? 'Link' }}</a></li>
                @endforeach
                <li>
                    <a class="btn btn-primary nav-cta" href="{{ $contactUrl }}">
                        {{ $settings['primary_cta_label'] ?? 'Quero agendar uma reunião' }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<ul class="sidenav mobile-site-menu" id="mobile-menu">
    <li class="mobile-brand">{{ $settings['brand_name'] ?? 'Perfil Digital Ads' }}</li>
    @foreach ($menuLinks as $link)
        <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? 'Link' }}</a></li>
    @endforeach
    <li><a class="btn btn-primary full-width" href="{{ $contactUrl }}">{{ $settings['primary_cta_label'] ?? 'Quero agendar uma reunião' }}</a></li>
    <li>
        <a class="mobile-whatsapp-link" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">
            <span class="whatsapp-mark" aria-hidden="true">
                <img src="{{ asset('images/whatsapp.png') }}" alt="">
            </span>
            <span>{{ $settings['secondary_cta_label'] ?? 'Falar no WhatsApp' }}</span>
        </a>
    </li>
</ul>
