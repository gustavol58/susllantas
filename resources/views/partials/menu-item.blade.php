{{--
   esta vista es la encargada de poner UNA A UNA las opciones y subopciones del menú
   principal que están grabadas en la tabla menus.
   es llamada mediante un include (el mismo que se usa aquí), desde layouts.app.blade.php
   recibe 2 parámetros:
      item        contiene un array fila del array $menus (o sea un registro de la
                  tabla menus). La vble $menus fue recibida por  la vista app.blade.php
                  desde el AppServiceProvider
      menu_roles  es un array bidimensional que contiene los permisos que los roles tienen
                  sobre las opciones de la tabla menus. Dicho array también llegó a
                  la vista app.blade.php desde el AppServiceProvider
--}}

   {{-- 19feb2020  para verificar permiso de usuario (por roles) --}}
   @php $verificar_permiso = Auth::user()->rol . "_" . $item['id'];@endphp
   @if(in_array($verificar_permiso , $menus_roles))
      {{-- el usuario tiene permiso para la opción/subopción escogida: --}}
      @if($item['parent'] == 0 || $item['parent'] == null)
        <!-- es una opcion principal (o sea del nivel mas superior, puede tener o no hijos) -->
        @if ($item['submenu'] == [])
            <li>
               <a  href="{{ url($item['slug']) }}" class="opciones">{{ $item['name'] }} </a>
               {{-- para cuando se le vayan a poner iconos a las opciones --}}
               {{-- <a  href="{{ url($item['slug']) }}" class="opciones"><img id="ico_menuclientes" alt="menu clientes" height="45" src = "{{ asset('img/iconos/menu/clientes.png') }}"/>&nbsp;&nbsp;{{ $item['name'] }} </a> --}}
            </li>
        @else
            <!-- las opciones que tienen hijos: -->
            <li class="dropdown">
                <a  href="#" class="dropdown-toggle opciones" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $item['name'] }} <span class="caret"></span></a>
                <ul class="dropdown-menu sub-menu">
                    @foreach ($item['submenu'] as $submenu)
                       {{-- 19feb2020  para verificar permiso de usuario (por roles)
                             se hace necesario repetirlo aqui debido a que este ciclo
                             esta recorriendo subopciones de un menú padre:
                       --}}
                       @php $verificar_permiso = Auth::user()->rol . "_" . $submenu['id'];@endphp
                       @if(in_array($verificar_permiso , $menus_roles))
                          @if ($submenu['submenu'] == [])
                             <!-- <li><span style="border: 1px solid red;"><a href="{xxx{ url('menu',['id' => $submenu['id'], 'slug' => $submenu['slug']]) }}" >{xxx{ $submenu['name'] }} </a></span></li> -->
                             <li><a href="{{ url($submenu['slug']) }}" class="subopciones">{{ $submenu['name'] }} </a></li>
                          @else
                             @include('partials.menu-item', [
                                'item' => $submenu,
                                'menu_roles' => $menus_roles,
                             ])
                          @endif
                       @endif
                    @endforeach
                </ul>
            </li>
        @endif
      @else
        <!-- es una sub-opción (puede tener o no hijos) -->
        @if ($item['submenu'] == [])
            {{-- subopción que no tiene hijos --}}
            <li>
                  <a  href="{{ url($item['slug']) }}" class="subopciones">{{ $item['name'] }} </a>
            </li>
        @else
            <!-- las opciones que tienen hijos: -->
            <li class="dropdown">
               <a  href="#" class="dropdown-toggle subopciones" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $item['name'] }} <span class="caret"></span></a>
               <ul class="dropdown-menu sub-menu">
                  @foreach ($item['submenu'] as $submenu)
                     {{-- 19feb2020  para verificar permiso de usuario (por roles)
                           se hace necesario repetirlo aqui debido a que este ciclo
                           esta recorriendo subopciones de un menú padre:
                     --}}
                     @php $verificar_permiso = Auth::user()->rol . "_" . $submenu['id'];@endphp
                     @if(in_array($verificar_permiso , $menus_roles))
                        @if ($submenu['submenu'] == [])
                           <!-- <li><span style="border: 1px solid red;"><a href="{{ url('menu',['id' => $submenu['id'], 'slug' => $submenu['slug']]) }}" >{{ $submenu['name'] }} </a></span></li> -->
                           <li><a href="{{ url($submenu['slug']) }}" class="subopciones">{{ $submenu['name'] }} </a></li>
                        @else
                           @include('partials.menu-item', [
                              'item' => $submenu,
                              'menu_roles' => $menus_roles,
                           ])
                        @endif
                     @endif
                  @endforeach
               </ul>
            </li>
        @endif
      @endif
   @endif
