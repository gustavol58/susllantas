{{--
   los parámetros $menus y $arr_permisos fueron llenados en \app\Providers\AppServiceProvider.php
   $menus   un array que tendrá tantas filas como registros ENABLED tenga la
            la tabla menus. Cada registro tiene 9 columnas:
               [id] => 17
               [name] => Órdenes abiertas
               [slug] => ordenes_abiertas_listar
               [parent] => 13
               [order] => 2
               [enabled] => 1
               [created_at] =>
               [updated_at] =>
               [submenu]  .... este a su vez es un array, que tiene las sub-opciones.
   $menus_roles   un array que tendrá tantas filas como registros tenga la tabla
                   menus_roles, con un columna llamada "permiso" y en la cual está
                   el rol y el id menu separados por un _, ejemplo: adm_18 , pat_13, ....
--}}
{{-- <pre>
{{print_r($menus)}} --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- el defer tuvo que ser eliminado porque dañaba los estilos de las datatables -->
    <!-- <script src="{xxx{ asset('js/app.js') }}" defer></script> -->
    <script src="{{ asset('js/app.js') }}" ></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles laravel ui -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- styles propios para menus:
            para las opciones multinivel de los menús
            para que el menú tenga un fondo azul y las opciones otros colores    -->
    <link href="{{ asset('css/propios_menus.css') }}" rel="stylesheet">

    @yield('css_js_datatables')

</head>
<body>
    <div id="app">
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
        <main class="py-4 bg-white" style="padding-top: 0 !important;">
            @yield('content')
        </main>
    </div>

    {{-- 2) Una sección validacion_form, por si alguna vista que
      llame a esta plantilla necesita validar uno de sus formularios: --}}
    @yield('javascript_jquery_validation')

    {{-- 3) Una section onReady() que se dejará lista por si alguna vista
      que llame esta plantilla necesita rellenarla con algún código
      específico --}}
    @yield('javascript_onReady')

    {{-- 4) una section "adicionales" por si alguna vista quiere agregar
      funciones javascript que no estén dentro del onReady() --}}
    @yield('javascript_funciones')

</body>
</html>
