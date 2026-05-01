@props(['settings', 'menuLinks', 'socialLinks', 'logoUrl', 'whatsappUrl'])

<footer class="site-footer">
    <div class="container-wide footer-grid">
        <div>
            <a href="{{ route('home') }}" class="footer-brand">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $settings['brand_name'] ?? 'Perfil Digital Ads' }}">
                @else
                    <span>{{ $settings['brand_name'] ?? 'Perfil Digital Ads' }}</span>
                @endif
            </a>
            <p>{{ $settings['footer_slogan'] ?? 'O perfil certo para o seu sucesso digital' }}</p>
        </div>
        <div>
            <h3>Navegação</h3>
            @foreach ($menuLinks as $link)
                <a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? 'Link' }}</a>
            @endforeach
        </div>
        <div>
            <h3>Contato</h3>
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener">WhatsApp</a>
            @if (! empty($settings['contact_email']))
                <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a>
            @endif
            <div class="social-links">
                @foreach ($socialLinks as $social)
                    <a href="{{ $social->url }}" target="_blank" rel="noopener" aria-label="{{ $social->label ?: $social->network }}">
                        <i class="material-icons">{{ $social->icon ?: 'link' }}</i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="footer-copy">© {{ date('Y') }} {{ $settings['brand_name'] ?? 'Perfil Digital Ads' }}. Todos os direitos reservados.</div>
</footer>
