@props(['section', 'settings', 'heroImageUrl', 'whatsappUrl', 'contactUrl' => null])

@php
    $contactUrl = $contactUrl ?: route('home').'#contato';
@endphp

<section id="inicio" class="hero-section">
    <div class="container-wide hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">{{ $section?->eyebrow ?? 'Marketing e vendas com previsibilidade' }}</span>
            <h1>{{ $section?->title ?? 'Transforme sua presença digital em oportunidades reais de venda' }}</h1>
            <p>{{ $section?->subtitle ?? 'A Perfil Digital Ads ajuda empresas a atrair mais clientes, gerar oportunidades qualificadas e crescer com estratégias digitais orientadas a resultado.' }}</p>
            <div class="hero-actions">
                <a class="btn btn-primary btn-large" href="{{ $contactUrl }}">{{ $settings['primary_cta_label'] ?? 'Quero agendar uma reunião' }}</a>
                <a class="btn btn-secondary btn-large whatsapp-button" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">
                    <span class="whatsapp-mark" aria-hidden="true">
                        <img src="{{ asset('images/whatsapp.png') }}" alt="">
                    </span>
                    <span>{{ $settings['secondary_cta_label'] ?? 'Falar no WhatsApp' }}</span>
                </a>
            </div>
            <div class="hero-proofs" aria-label="Diferenciais">
                <span>Foco em performance</span>
                <span>Processos claros</span>
                <span>Estratégias sob medida</span>
                <span>Acompanhamento próximo</span>
            </div>
        </div>

        <div class="hero-media">
            <img src="{{ $heroImageUrl }}" alt="Reunião estratégica com dashboard de performance">
            <div class="metric-strip">
                <strong>Performance</strong>
                <span>Campanhas, CRM, conversão e acompanhamento em um plano claro.</span>
            </div>
        </div>
    </div>
</section>
