<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login admin | Perfil Digital Ads</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">
    <main class="login-shell">
        <form method="POST" action="{{ route('admin.login.store') }}" class="login-card">
            @csrf
            <span class="login-kicker">Perfil Digital Ads</span>
            <h1>Painel administrativo</h1>

            @if ($errors->any())
                <div class="card-panel red darken-3 white-text">Credenciais inválidas.</div>
            @endif

            <div class="input-field">
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                <label for="email">E-mail</label>
            </div>
            <div class="input-field">
                <input id="password" name="password" type="password" required>
                <label for="password">Senha</label>
            </div>
            <label class="remember-row">
                <input type="checkbox" name="remember" value="1">
                <span>Lembrar acesso</span>
            </label>
            <button class="btn btn-primary full-width" type="submit">Entrar</button>
        </form>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
