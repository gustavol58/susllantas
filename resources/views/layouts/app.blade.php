<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="http://localhost/susllantas/public/css/app_traidoMarkka.css">
        {{-- libreria pikaday: Para pedir fechas con datetimepicker: --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
        
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
        <!-- styles propios para menus:
            para las opciones multinivel de los menús
            para que el menú tenga un fondo azul y las opciones otros colores    -->
        {{-- <link href="{{ asset('css/propios_menus.css') }}" rel="stylesheet"> --}}

        <style>
            .imagen_ini{
                background-image: url("{{asset('img/first_option.png')}}");
                width: 100%;
                height: 100%;
            }
            .imagen_login_own{
                background-image: url("{{asset('img/fondo_login.jpg')}}");
                width: 100%;
                height: 100%;
            }
        </style> 

        @livewireStyles

        <!-- Scripts -->
        <script src="http://localhost/susllantas/public/js/app_desdeMarkka.js" defer></script>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-blue-700">
            <a class="navbar-brand"  href="{{ url('/') }}">
                <img id="logo" alt="Logo de Colibrí"
                      height="45"
                      src = "{{ asset('img/logo.png') }}"
                 />
            </a>

            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- @stack('modals') --}}

        @livewireScripts
        {{-- librerias moment.js y pikaday: Para pedir fechas con datetimepicker: --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>  
     
    </body>
</html>
