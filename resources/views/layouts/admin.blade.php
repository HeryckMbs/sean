<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') | Perfil Digital Ads</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    @php
        $navItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'dashboard'],
            ['label' => 'Configurações', 'url' => route('admin.settings.edit'), 'icon' => 'tune'],
            ['label' => 'Seções', 'url' => route('admin.content.index', 'secoes'), 'icon' => 'view_day'],
            ['label' => 'Serviços', 'url' => route('admin.content.index', 'servicos'), 'icon' => 'ads_click'],
            ['label' => 'Benefícios', 'url' => route('admin.content.index', 'beneficios'), 'icon' => 'verified'],
            ['label' => 'Nichos', 'url' => route('admin.content.index', 'nichos'), 'icon' => 'groups'],
            ['label' => 'Processo', 'url' => route('admin.content.index', 'processo'), 'icon' => 'timeline'],
            ['label' => 'Cases', 'url' => route('admin.content.index', 'cases'), 'icon' => 'work'],
            ['label' => 'Depoimentos', 'url' => route('admin.content.index', 'depoimentos'), 'icon' => 'format_quote'],
            ['label' => 'FAQ', 'url' => route('admin.content.index', 'faq'), 'icon' => 'quiz'],
            ['label' => 'Redes sociais', 'url' => route('admin.content.index', 'redes-sociais'), 'icon' => 'share'],
            ['label' => 'Leads', 'url' => route('admin.leads.index'), 'icon' => 'mail'],
        ];
    @endphp

    <ul id="admin-menu" class="sidenav sidenav-fixed admin-sidenav">
        <li class="admin-brand">
            <span>Perfil Digital Ads</span>
            <small>Painel de conteúdo</small>
        </li>
        @foreach ($navItems as $item)
            <li>
                <a href="{{ $item['url'] }}">
                    <i class="material-icons">{{ $item['icon'] }}</i>{{ $item['label'] }}
                </a>
            </li>
        @endforeach
        <li class="divider"></li>
        <li>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout-button" type="submit">
                    <i class="material-icons">logout</i>Sair
                </button>
            </form>
        </li>
    </ul>

    <nav class="admin-topbar hide-on-large-only">
        <div class="nav-wrapper">
            <a href="#" data-target="admin-menu" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <span class="brand-logo">@yield('title', 'Admin')</span>
        </div>
    </nav>

    <main class="admin-main">
        <div class="admin-container">
            @if (session('status'))
                <div class="card-panel green darken-3 white-text">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="card-panel red darken-3 white-text">
                    <strong>Revise os campos:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            M.Sidenav.init(document.querySelectorAll('.sidenav'));
            M.updateTextFields();
        });
    </script>
    @stack('scripts')
</body>
</html>
