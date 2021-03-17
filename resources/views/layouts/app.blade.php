{{-- 
    17mar2021
    el "app.blade" llamado por los componentes livewire es app.blade.php
    el "app.blade" llamado por los componentes NO livewire. appnolive.blade.php
    NOTA: el app-sin-menu.blade.php, por ser llamado por un 
    componente NO liveware, es similar al appnolive.blade.php 
--}}

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
        <div class="min-h-screen bg-white  ">
            {{-- Nombre consulta y botón regresar:  --}}
            <div class="flex justify-between bg-blue-500">
                <div class="text-white text-4xl">
                    Listado de clientes.
                </div>

                <a href="{{url('/clientes/create')}}" >
                    <button type="button"  class=" hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                        <svg class="inline-block w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg>
                         Crear clientes
                    </button>
                </a>

                <a href="{{url('/')}}" >
                    <button type="button"  class=" hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                        <svg class="inline-block w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                         Inicio
                    </button>
                </a>                

            </div>

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
