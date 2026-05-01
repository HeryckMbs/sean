@extends('layouts.public')

@section('title', $agencyCase->title.' | '.($settings['brand_name'] ?? 'Perfil Digital Ads'))
@section('meta_description', $agencyCase->result ?: $agencyCase->challenge)

@section('content')
    @php
        $assetUrl = function (?string $path): ?string {
            if (! $path) {
                return null;
            }

            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
                return $path;
            }

            if (str_starts_with($path, 'media/')) {
                return asset('storage/'.$path);
            }

            return asset($path);
        };

        $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp_number'] ?? '');
        $whatsappUrl = $whatsappNumber
            ? 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($settings['whatsapp_message'] ?? '')
            : $contactUrl;
        $logoUrl = $assetUrl($settings['logo_path'] ?? null);
    @endphp

    <x-header
        :settings="$settings"
        :menu-links="$menuLinks"
        :logo-url="$logoUrl"
        :whatsapp-url="$whatsappUrl"
        :contact-url="$contactUrl"
    />

    <main>
        <section class="topic-hero page-section case-section">
            <div class="container-wide topic-hero-grid">
                <div>
                    <a class="back-link" href="{{ route('cases.index') }}">Voltar para cases</a>
                    <span class="eyebrow">{{ $agencyCase->segment ?: 'Case' }}</span>
                    <h1>{{ $agencyCase->title }}</h1>
                    <p>{{ $agencyCase->result }}</p>
                </div>
                <div class="topic-hero-actions">
                    <a class="btn btn-primary" href="{{ $contactUrl }}">{{ $agencyCase->cta_label ?: 'Quero resultados assim' }}</a>
                    <a class="btn btn-secondary whatsapp-button" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">
                        <span class="whatsapp-mark" aria-hidden="true">
                            <img src="{{ asset('images/whatsapp.png') }}" alt="">
                        </span>
                        <span>{{ $settings['secondary_cta_label'] ?? 'Falar no WhatsApp' }}</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="page-section">
            <div class="container-wide topic-detail-grid">
                <article class="topic-detail">
                    @if ($caseImageUrl = $assetUrl($agencyCase->image_path))
                        <img class="case-image" src="{{ $caseImageUrl }}" alt="{{ $agencyCase->title }}">
                    @endif
                    <dl>
                        <dt>Cenário inicial</dt>
                        <dd>{{ $agencyCase->initial_scenario }}</dd>
                        <dt>Desafio</dt>
                        <dd>{{ $agencyCase->challenge }}</dd>
                        <dt>Estratégia aplicada</dt>
                        <dd>{{ $agencyCase->strategy }}</dd>
                        <dt>Resultado</dt>
                        <dd>{{ $agencyCase->result }}</dd>
                    </dl>
                </article>

                <aside class="detail-side">
                    <div class="detail-list">
                        <h3>Indicadores</h3>
                        @forelse ($agencyCase->metrics ?? [] as $metric)
                            <span>{{ $metric }}</span>
                        @empty
                            <p>Sem indicadores cadastrados.</p>
                        @endforelse
                    </div>
                </aside>
            </div>
        </section>

        @if ($relatedCases->isNotEmpty())
            <section class="page-section section-muted">
                <div class="container-wide">
                    <div class="section-title">
                        <span class="eyebrow">Mais cases</span>
                        <h2>Outros resultados</h2>
                    </div>
                    <div class="topic-grid topic-grid--three">
                        @foreach ($relatedCases as $relatedCase)
                            <article class="topic-card">
                                <span class="case-segment">{{ $relatedCase->segment }}</span>
                                <h3>{{ $relatedCase->title }}</h3>
                                <p>{{ $relatedCase->result }}</p>
                                <a class="text-link" href="{{ route('cases.show', $relatedCase) }}">Ver case completo</a>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    <a class="whatsapp-float" href="{{ $whatsappUrl }}" target="_blank" rel="noopener" aria-label="Falar no WhatsApp">
        <img src="{{ asset('images/whatsapp.png') }}" alt="">
    </a>

    <x-footer
        :settings="$settings"
        :menu-links="$menuLinks"
        :social-links="$socialLinks"
        :logo-url="$logoUrl"
        :whatsapp-url="$whatsappUrl"
    />
@endsection
