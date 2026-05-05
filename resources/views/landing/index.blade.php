@extends('layouts.public')

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

        $menuLinks = $menuLinks ?? [
            ['label' => 'Sobre a Perfil Digital', 'url' => '#sobre'],
            ['label' => 'Soluções', 'url' => route('services.index')],
            ['label' => 'Cases', 'url' => route('cases.index')],
            ['label' => 'Nichos', 'url' => route('audiences.index')],
            ['label' => 'Contato', 'url' => '#contato'],
        ];

        $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp_number'] ?? '');
        $whatsappUrl = $whatsappNumber
            ? 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($settings['whatsapp_message'] ?? '')
            : '#contato';
        $logoUrl = $assetUrl($settings['logo_path'] ?? null);
        $sectionMediaUrl = fn (string $key): ?string => $assetUrl($sections->get($key)?->media_path);
        $contactUrl = $contactUrl ?? route('home').'#contato';
        $selectedServiceInterests = collect(old('service_interests', []))->filter()->values()->all();
    @endphp

    <x-header
        :settings="$settings"
        :menu-links="$menuLinks"
        :logo-url="$logoUrl"
        :whatsapp-url="$whatsappUrl"
        :contact-url="$contactUrl"
    />

    <main>
        <x-hero
            :section="$sections->get('hero')"
            :settings="$settings"
            :whatsapp-url="$whatsappUrl"
            :contact-url="$contactUrl"
        />

        <section id="sobre" class="page-section section-split">
            <div class="container-wide split-grid">
                <div>
                    <x-section-title :section="$sections->get('sobre')" />
                    @if ($sections->get('sobre')?->body)
                        <p class="section-body">{{ $sections->get('sobre')->body }}</p>
                    @endif
                </div>
            </div>
        </section>

        <section id="solucoes" class="page-section section-strong">
            <div class="container-wide">
                <x-section-title :section="$sections->get('solucoes')" align="center" />
                <x-section-media :url="$sectionMediaUrl('solucoes')" :alt="$sections->get('solucoes')?->title" wide />

                <div class="services-overview">
                    @foreach ($services as $service)
                        <x-service-card :service="$service" :href="route('services.show', $service)" />
                    @endforeach
                </div>

                <div class="section-actions section-actions--stacked center-align">
                    <a class="btn btn-primary" href="{{ $contactUrl }}">{{ $settings['primary_cta_label'] ?? 'Quero agendar uma reunião' }}</a>
                    @if ($services->isNotEmpty())
                        <a class="btn btn-secondary" href="{{ route('services.index') }}">Ver todas as soluções</a>
                    @endif
                </div>
            </div>
        </section>

        <section id="beneficios" class="page-section">
            <div class="container-wide">
                <x-section-title :section="$sections->get('beneficios')" align="center" />
                <x-section-media :url="$sectionMediaUrl('beneficios')" :alt="$sections->get('beneficios')?->title" wide />
                <div class="benefits-grid">
                    @foreach ($benefits as $benefit)
                        <x-benefit-card :benefit="$benefit" />
                    @endforeach
                </div>
                @if ($benefits->isNotEmpty())
                    <div class="section-actions center-align">
                        <a class="btn btn-secondary" href="{{ route('benefits.index') }}">Ver todos os benefícios</a>
                    </div>
                @endif
            </div>
        </section>

        <section id="nichos" class="page-section section-muted">
            <div class="container-wide">
                <x-section-title :section="$sections->get('nichos')" />
                <x-section-media :url="$sectionMediaUrl('nichos')" :alt="$sections->get('nichos')?->title" wide />
                <div class="audience-grid">
                    @foreach ($audiences as $audience)
                        <article>
                            <i class="material-icons">{{ $audience->icon ?: 'business' }}</i>
                            <h3>{{ $audience->title }}</h3>
                            <p>{{ $audience->description }}</p>
                        </article>
                    @endforeach
                </div>
                @if ($audiences->isNotEmpty())
                    <div class="section-actions">
                        <a class="btn btn-secondary" href="{{ route('audiences.index') }}">Ver todos os nichos</a>
                    </div>
                @endif
            </div>
        </section>

        <section id="processo" class="page-section">
            <div class="container-wide">
                <x-section-title :section="$sections->get('processo')" align="center" />
                <x-section-media :url="$sectionMediaUrl('processo')" :alt="$sections->get('processo')?->title" wide />
                <div class="steps-row steps-row--summary">
                    @foreach ($workSteps as $step)
                        <article class="step-item">
                            <span>{{ $step->step_label }}</span>
                            <h3>{{ $step->title }}</h3>
                            <p>{{ $step->description }}</p>
                        </article>
                    @endforeach
                </div>
                @if ($workSteps->isNotEmpty())
                    <div class="section-actions center-align">
                        <a class="btn btn-secondary" href="{{ route('process.index') }}">Ver processo completo</a>
                    </div>
                @endif
            </div>
        </section>

        @if ($featuredCase)
            <section id="cases" class="page-section case-section">
                <div class="container-wide case-grid">
                    <div>
                        <x-section-title :section="$sections->get('cases')" />
                        <x-section-media :url="$sectionMediaUrl('cases')" :alt="$sections->get('cases')?->title" />
                        <div class="case-metrics">
                            @foreach ($featuredCase->metrics ?? [] as $metric)
                                <span>{{ $metric }}</span>
                            @endforeach
                        </div>
                    </div>
                    <article class="case-detail">
                        @if ($caseImageUrl = $assetUrl($featuredCase->image_path))
                            <img class="case-image" src="{{ $caseImageUrl }}" alt="{{ $featuredCase->title }}">
                        @endif
                        <span class="case-segment">{{ $featuredCase->segment }}</span>
                        <h3>{{ $featuredCase->title }}</h3>
                        <dl>
                            <dt>Cenário inicial</dt>
                            <dd>{{ $featuredCase->initial_scenario }}</dd>
                            <dt>Desafio</dt>
                            <dd>{{ $featuredCase->challenge }}</dd>
                            <dt>Estratégia aplicada</dt>
                            <dd>{{ $featuredCase->strategy }}</dd>
                            <dt>Resultado</dt>
                            <dd>{{ $featuredCase->result }}</dd>
                        </dl>
                        <div class="inline-actions">
                            <a class="btn btn-primary" href="{{ $contactUrl }}">{{ $featuredCase->cta_label ?: 'Quero resultados assim' }}</a>
                            <a class="btn btn-secondary" href="{{ route('cases.show', $featuredCase) }}">Ver case completo</a>
                        </div>
                    </article>
                </div>
            </section>
        @endif

        <section id="prova-social" class="page-section section-muted">
            <div class="container-wide">
                <x-section-title :section="$sections->get('prova-social')" align="center" />
                <x-section-media :url="$sectionMediaUrl('prova-social')" :alt="$sections->get('prova-social')?->title" wide />
                <div class="logo-strip" aria-label="Clientes e parceiros">
                    @foreach (['Cliente parceiro', 'Operação local', 'E-commerce', 'Serviços B2B'] as $logo)
                        <span>{{ $logo }}</span>
                    @endforeach
                </div>
                <div class="testimonials-grid">
                    @foreach ($testimonials as $testimonial)
                        <article class="testimonial-card">
                            <p>“{{ $testimonial->content }}”</p>
                            <strong>{{ $testimonial->author_name }}</strong>
                            <span>{{ $testimonial->company }}</span>
                        </article>
                    @endforeach
                </div>
                @if ($testimonials->isNotEmpty())
                    <div class="section-actions center-align">
                        <a class="btn btn-secondary" href="{{ route('testimonials.index') }}">Ver mais depoimentos</a>
                    </div>
                @endif
            </div>
        </section>

        <section id="faq" class="page-section faq-section">
            <div class="container-wide faq-grid">
                <div>
                    <x-section-title :section="$sections->get('faq')" />
                    <x-section-media :url="$sectionMediaUrl('faq')" :alt="$sections->get('faq')?->title" />
                </div>
                <ul class="collapsible faq-list">
                    @foreach ($faqs as $faq)
                        <x-faq-item :faq="$faq" />
                    @endforeach
                </ul>
                @if ($faqs->isNotEmpty())
                    <div class="section-actions">
                        <a class="btn btn-secondary" href="{{ route('faqs.index') }}">Ver todas as perguntas</a>
                    </div>
                @endif
            </div>
        </section>

        <section id="contato" class="page-section contact-section">
            <div class="container-wide contact-grid">
                <div>
                    <x-section-title :section="$sections->get('contato')" />
                    <x-section-media :url="$sectionMediaUrl('contato')" :alt="$sections->get('contato')?->title" />
                    <a class="btn btn-secondary whatsapp-button" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">
                        <span class="whatsapp-mark" aria-hidden="true">
                            <img src="{{ asset('images/whatsapp.png') }}" alt="">
                        </span>
                        <span>{{ $settings['secondary_cta_label'] ?? 'Falar no WhatsApp' }}</span>
                    </a>
                </div>

                <form class="lead-form" method="POST" action="{{ route('leads.store') }}" data-loading-form>
                    @csrf

                    @if (session('lead_success'))
                        <div class="success-box">{{ session('lead_success') }}</div>
                    @endif

                    <div class="input-field">
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                        <label for="name">Nome</label>
                        @error('name') <span class="helper-text red-text">{{ $message }}</span> @enderror
                    </div>
                    <div class="input-field">
                        <input id="company" name="company" type="text" value="{{ old('company') }}" required>
                        <label for="company">Empresa</label>
                        @error('company') <span class="helper-text red-text">{{ $message }}</span> @enderror
                    </div>
                    <div class="input-row">
                        <div class="input-field">
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                            <label for="email">E-mail</label>
                            @error('email') <span class="helper-text red-text">{{ $message }}</span> @enderror
                        </div>
                        <div class="input-field">
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required>
                            <label for="phone">Telefone</label>
                            @error('phone') <span class="helper-text red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="input-field">
                        <input id="whatsapp" name="whatsapp" type="text" value="{{ old('whatsapp') }}" required>
                        <label for="whatsapp">WhatsApp</label>
                        @error('whatsapp') <span class="helper-text red-text">{{ $message }}</span> @enderror
                    </div>
                    <div class="input-field">
                        <textarea id="message" name="message" class="materialize-textarea">{{ old('message') }}</textarea>
                        <label for="message">Mensagem opcional</label>
                        @error('message') <span class="helper-text red-text">{{ $message }}</span> @enderror
                    </div>
                    @if (($formServices ?? collect())->isNotEmpty())
                        <fieldset class="service-interest-field">
                            <legend>Tipo de serviço que busca <span>opcional</span></legend>
                            <div class="service-interest-grid">
                                @foreach ($formServices as $formService)
                                    <label class="service-interest-option">
                                        <input
                                            class="filled-in"
                                            type="checkbox"
                                            name="service_interests[]"
                                            value="{{ $formService->name }}"
                                            @checked(in_array($formService->name, $selectedServiceInterests, true))
                                        >
                                        <span>{{ $formService->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('service_interests') <span class="helper-text red-text">{{ $message }}</span> @enderror
                            @error('service_interests.*') <span class="helper-text red-text">{{ $message }}</span> @enderror
                        </fieldset>
                    @endif
                    <button class="btn btn-primary btn-large full-width" type="submit">
                        <span class="button-label">{{ $settings['primary_cta_label'] ?? 'Quero agendar uma reunião' }}</span>
                        <span class="loading-label">Enviando...</span>
                    </button>
                    <p class="form-note">Seus dados serão usados apenas para entrar em contato sobre sua solicitação.</p>
                </form>
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
