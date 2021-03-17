{{-- 
    17mar2021
    el "app.blade" llamado por los componentes livewire es app.blade.php
    el "app.blade" llamado por los componentes NO livewire. appnolive.blade.php
    NOTA: el app-sin-menu.blade.php, por ser llamado por un 
    componente NO liveware, es similar al appnolive.blade.php 
--}}

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- estilos propios para que el fondo sea una imagen responsive  -->
    <link href="{{ asset('css/propios_fondoweb.css') }}" rel="stylesheet">

    @yield('css_transparencia')

</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
