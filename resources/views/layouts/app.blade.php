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
        <link rel="stylesheet" href="http://localhost/markka/public/css/app.css">
        {{-- libreria pikaday: Para pedir fechas con datetimepicker: --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- styles propios para menus:
            para las opciones multinivel de los menús
            para que el menú tenga un fondo azul y las opciones otros colores    -->
        <link href="{{ asset('css/propios_menus.css') }}" rel="stylesheet">

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
        <script src="http://localhost/susllantas/public/js/app.js" defer></script>
    </head>
    <body class="font-sans antialiased">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm" style="height: 3em !important;">
            <div class="container-fluid">
                <a class="navbar-brand"  href="{{ url('/') }}">
                  <img id="logo" alt="Logo de Colibrí"
                        height="45"
                        src = "{{ asset('img/logo.png') }}"
                   />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    {{-- opciones del menú grabadas en la tabla menus --}}
                    <ul class="navbar-nav">
                       @foreach ($menus as $key => $item)
                           @if ($item['parent'] != 0)
                               @break
                           @endif
                           @include('partials.menu-item', [
                              'item' => $item,
                              'menu_roles' => $menus_roles,
                           ])
                       @endforeach
                    </ul>

                    {{-- opciones usuario del menú --}}
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                          <li class="dropdown">
                              {{-- con icono de usuario --}}
                              <a  href="#" class="dropdown-toggle opciones" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img id='ico_menuusuario' alt='menu usuario' height='30' src = '{{ asset('img/iconos/menu/usuario.png') }}'/>&nbsp;{{ Auth::user()->name }} <span class="caret"></span></a>
                              {{-- sin icono de usuario --}}
                              {{-- <a  href="#" class="dropdown-toggle opciones" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a> --}}
                              <ul class="dropdown-menu sub-menu">
                                    <li><a href="{{ url('cambiar_clave')}}" class="subopcion">Cambiar clave </a></li>
                                    <li>
                                      <a  href="{{ route('logout') }}" class="subopcion"
                                         onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                                          {{ __('Logout') }}
                                      </a>

                                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                          @csrf
                                      </form>
                                    </li>
                              </ul>
                          </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>        
        {{-- <x-jet-banner /> --}}

        <div class="min-h-screen bg-gray-100">
            {{-- @livewire('navigation-menu') --}}

                {{-- linea que solo estará sin comentario en el AMBIENTE DE PRUEBAS --}}
                {{-- <p class="text-font-bold text-3xl text-center text-red-500">AMBIENTE DE PRUEBAS</p> --}}

            <!-- Page Heading -->
            {{-- <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header> --}}

            <!-- Page Content -->
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
