<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Reportes') | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/carza.css') }}">
</head>
<body>
    <header class="site-header">
        <nav class="navbar container py-3" aria-label="Navegación principal">
            <a class="navbar-brand brand" href="{{ route('reportes.index') }}">
                {{ config('app.name') }}
                <small>Módulo Laravel</small>
            </a>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-dark btn-sm" href="{{ route('reportes.api') }}">Ver JSON</a>
            </div>
        </nav>
    </header>

    <main>
        @yield('contenido')
    </main>

    <footer class="site-footer">
        <div class="container py-4 small">
            © {{ date('Y') }} {{ config('app.name') }} · Módulo de reportes con Laravel (Routing, Controller, Eloquent y Blade)
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
