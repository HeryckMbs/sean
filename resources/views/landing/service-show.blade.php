@extends('layouts.public')

@section('title', $service->name.' | '.($settings['brand_name'] ?? 'Perfil Digital Ads'))
@section('meta_description', $service->summary ?: $service->description)

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

    <main class="service-detail-page">
        <section class="service-hero page-section">
            <div class="container-wide service-hero-grid">
                <div class="service-hero-copy">
                    <a class="back-link" href="{{ route('services.index') }}">Voltar para soluções</a>
                    <span class="eyebrow">Solução</span>
                    <h1>{{ $service->name }}</h1>
                    <p>{{ $service->summary ?: $service->description }}</p>
                    <div class="inline-actions">
                        <a class="btn btn-primary" href="{{ $contactUrl }}">{{ $service->cta_label ?: ($settings['primary_cta_label'] ?? 'Quero agendar uma reunião') }}</a>
                        <a class="btn btn-secondary whatsapp-button" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">
                            <span class="whatsapp-mark" aria-hidden="true">
                                <img src="{{ asset('images/whatsapp.png') }}" alt="">
                            </span>
                            <span>{{ $settings['secondary_cta_label'] ?? 'Falar no WhatsApp' }}</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <section class="service-content page-section">
            <div class="container-wide service-content-grid">
                <article class="service-main-copy">
                    <span class="eyebrow">Aplicação prática</span>
                    <h2>Como essa frente ajuda sua empresa</h2>
                    <p>{{ $service->description }}</p>
                </article>

                <div class="service-list-column">
                    <section class="service-list-block">
                        <div class="service-list-heading">
                            <i class="material-icons">task_alt</i>
                            <h3>Entregáveis</h3>
                        </div>
                        <ul class="service-feature-list">
                            @forelse ($service->deliverables ?? [] as $deliverable)
                                <li>{{ $deliverable }}</li>
                            @empty
                                <li>Sem entregáveis cadastrados.</li>
                            @endforelse
                        </ul>
                    </section>

                    <section class="service-list-block">
                        <div class="service-list-heading">
                            <i class="material-icons">trending_up</i>
                            <h3>Benefícios práticos</h3>
                        </div>
                        <ul class="service-feature-list">
                            @forelse ($service->benefits ?? [] as $benefit)
                                <li>{{ $benefit }}</li>
                            @empty
                                <li>Sem benefícios cadastrados.</li>
                            @endforelse
                        </ul>
                    </section>
                </div>
            </div>
        </section>

        <section class="service-cta-section">
            <div class="container-wide service-cta-grid">
                <div>
                    <span class="eyebrow">Próximo passo</span>
                    <h2>Quer entender como aplicar {{ $service->name }} no seu negócio?</h2>
                </div>
                <a class="btn btn-primary" href="{{ $contactUrl }}">{{ $settings['primary_cta_label'] ?? 'Quero agendar uma reunião' }}</a>
            </div>
        </section>

        @if ($relatedServices->isNotEmpty())
            <section class="page-section section-muted">
                <div class="container-wide">
                    <div class="section-title">
                        <span class="eyebrow">Outras soluções</span>
                        <h2>Continue explorando</h2>
                    </div>
                    <div class="services-overview">
                        @foreach ($relatedServices as $relatedService)
                            <x-service-card :service="$relatedService" :href="route('services.show', $relatedService)" />
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
