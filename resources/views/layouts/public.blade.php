<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trim($__env->yieldContent('title', $settings['seo_title'] ?? config('app.name'))) }}</title>
    <meta name="description" content="{{ trim($__env->yieldContent('meta_description', $settings['seo_description'] ?? '')) }}">
    <meta name="theme-color" content="#05171f">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
</head>
<body>
    @yield('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            M.Sidenav.init(document.querySelectorAll('.sidenav'));
            M.Tabs.init(document.querySelectorAll('.tabs'), { swipeable: false });
            M.Collapsible.init(document.querySelectorAll('.collapsible'));

            document.querySelectorAll('a[href^="#"]').forEach(function (link) {
                if (link.closest('.tabs')) return;

                link.addEventListener('click', function (event) {
                    var target = document.querySelector(this.getAttribute('href'));
                    if (!target) return;
                    event.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            document.querySelectorAll('[data-loading-form]').forEach(function (form) {
                form.addEventListener('submit', function () {
                    var button = form.querySelector('[type="submit"]');
                    if (!button) return;
                    button.disabled = true;
                    button.classList.add('is-loading');
                });
            });

            var revealSelectors = [
                '.hero-copy > *',
                '.hero-media',
                '.section-media',
                '.section-title',
                '.differential-item',
                '.service-card',
                '.topic-card',
                '.topic-detail',
                '.topic-hero-actions',
                '.service-summary-panel',
                '.service-main-copy',
                '.service-list-block',
                '.service-cta-grid',
                '.services-tabs-wrap',
                '.benefit-card',
                '.audience-grid article',
                '.step-item',
                '.case-metrics span',
                '.case-detail',
                '.logo-strip span',
                '.testimonial-card',
                '.faq-list',
                '.lead-form',
                '.site-footer .footer-grid > *'
            ].join(',');

            var revealTargets = Array.prototype.slice.call(document.querySelectorAll(revealSelectors));
            var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!reduceMotion && 'IntersectionObserver' in window && revealTargets.length) {
                document.body.classList.add('animations-ready');

                var parentCounters = new WeakMap();
                revealTargets.forEach(function (element) {
                    element.classList.add('reveal-target');

                    var parent = element.parentElement || document.body;
                    var count = parentCounters.get(parent) || 0;
                    parentCounters.set(parent, count + 1);
                    element.style.setProperty('--reveal-delay', Math.min(count, 6) * 70 + 'ms');
                });

                var revealObserver = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) return;

                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, {
                    rootMargin: '0px 0px -8% 0px',
                    threshold: 0.12
                });

                revealTargets.forEach(function (element) {
                    revealObserver.observe(element);
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
