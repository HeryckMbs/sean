@extends('layouts.public')

@section('title', $page['title'].' | '.($settings['brand_name'] ?? 'Perfil Digital Ads'))
@section('meta_description', $page['subtitle'])

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
        $pageImageUrl = $assetUrl($page['media_path'] ?? null);
    @endphp

    <x-header
        :settings="$settings"
        :menu-links="$menuLinks"
        :logo-url="$logoUrl"
        :whatsapp-url="$whatsappUrl"
        :contact-url="$contactUrl"
    />

    <main>
        <section class="topic-hero page-section section-strong">
            <div class="container-wide topic-hero-grid">
                <div>
                    <a class="back-link" href="{{ route('home') }}">Voltar para a página principal</a>
                    <span class="eyebrow">{{ $page['eyebrow'] }}</span>
                    <h1>{{ $page['title'] }}</h1>
                    <p>{{ $page['subtitle'] }}</p>
                </div>
                <div class="topic-hero-actions">
                    <a class="btn btn-primary" href="{{ $contactUrl }}">{{ $settings['primary_cta_label'] ?? 'Quero agendar uma reunião' }}</a>
                    <a class="btn btn-secondary whatsapp-button" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">
                        <span class="whatsapp-mark" aria-hidden="true">
                            <img src="{{ asset('images/whatsapp.png') }}" alt="">
                        </span>
                        <span>{{ $settings['secondary_cta_label'] ?? 'Falar no WhatsApp' }}</span>
                    </a>
                </div>
            </div>
            @if ($pageImageUrl)
                <div class="container-wide">
                    <x-section-media :url="$pageImageUrl" :alt="$page['title']" wide />
                </div>
            @endif
        </section>

        <section class="page-section">
            <div class="container-wide">
                @if ($items->isEmpty())
                    <div class="empty-topic">
                        <h2>Nenhum registro ativo por enquanto.</h2>
                    </div>
                @elseif ($page['type'] === 'services')
                    <div class="topic-grid topic-grid--four">
                        @foreach ($items as $service)
                            <x-service-card :service="$service" :href="route('services.show', $service)" />
                        @endforeach
                    </div>
                @elseif ($page['type'] === 'benefits')
                    <div class="benefits-grid">
                        @foreach ($items as $benefit)
                            <x-benefit-card :benefit="$benefit" />
                        @endforeach
                    </div>
                @elseif ($page['type'] === 'audiences')
                    <div class="audience-grid">
                        @foreach ($items as $audience)
                            <article>
                                <i class="material-icons">{{ $audience->icon ?: 'business' }}</i>
                                <h3>{{ $audience->title }}</h3>
                                <p>{{ $audience->description }}</p>
                            </article>
                        @endforeach
                    </div>
                @elseif ($page['type'] === 'process')
                    <div class="steps-row">
                        @foreach ($items as $step)
                            <article class="step-item">
                                <span>{{ $step->step_label }}</span>
                                <h3>{{ $step->title }}</h3>
                                <p>{{ $step->description }}</p>
                            </article>
                        @endforeach
                    </div>
                @elseif ($page['type'] === 'cases')
                    <div class="topic-grid topic-grid--three">
                        @foreach ($items as $agencyCase)
                            <article class="topic-card">
                                @if ($caseImageUrl = $assetUrl($agencyCase->image_path))
                                    <img src="{{ $caseImageUrl }}" alt="{{ $agencyCase->title }}">
                                @endif
                                <span class="case-segment">{{ $agencyCase->segment }}</span>
                                <h3>{{ $agencyCase->title }}</h3>
                                <p>{{ $agencyCase->result }}</p>
                                <a class="text-link" href="{{ route('cases.show', $agencyCase) }}">Ver case completo</a>
                            </article>
                        @endforeach
                    </div>
                @elseif ($page['type'] === 'testimonials')
                    <div class="testimonials-grid">
                        @foreach ($items as $testimonial)
                            <article class="testimonial-card">
                                <p>"{{ $testimonial->content }}"</p>
                                <strong>{{ $testimonial->author_name }}</strong>
                                <span>{{ $testimonial->company }}</span>
                            </article>
                        @endforeach
                    </div>
                @elseif ($page['type'] === 'faqs')
                    <ul class="collapsible faq-list">
                        @foreach ($items as $faq)
                            <x-faq-item :faq="$faq" />
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
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
