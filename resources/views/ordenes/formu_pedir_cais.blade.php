{{--
   llamada desde: OrdenController@llamar_vista_pedir_cais
                  OrdenController@modificar_orden_abierta
                  OrdenController@cerrar_orden
   Recibe 8 parámetros:
      productos  array para llenar el combo de productos
      operarios  array para llenar el combo de operarios
      cliente_id    para grabar en la tabla servicios
      vehiculo_id   para grabar en la tablla servicios
      cliente_nombre para mostrar en el título de la ventana
      placa          para mostrar en el título de la ventana
      origen         'crear' fue llamada desde CREAR ORDEN DE SERVICIO
                              (método llamar_vista_pedir_cais() )
                     'modificar' fue llamada desde MODIFICAR ORDEN DE SERVICIO
                              (método modificar_orden_abierta() )
                     'cerrar' fue llamada desde CERRAR ORDEN DE SERVICIO
                              (método cerrar_orden() )
      num_orden      recibe 0 si origen="crear"
                     recibe el número de la orden a modificar, si origen="modificar"
                     recibe el número de la orden a cerrar, si origen="cerrar"
   Esta vista muestra:
      1) la petición de tres datos: producto, cantidad y operario
      2) una tabla con los productos que se vayan ingresando (los cuales
         se van guardando en un array)
   Dependiendo del origen.
   Si origen="crear"
      El botón "Generar orden de servicio"  hará que el array
      sea pasado a la  base de datos (tablas servicios y servicios_detalles)
   Si origen = "modificar"
      El botón "Modificar orden de servicio"  hará que se
      actualice la tabla servicios_detalle
   Si origen = "cerrar"
      El botón "Cerrar orden de servicio"  modificará la tabla
      servicios para que abierta quede false y manejará inventarios
--}}

{{-- {{dd($productos)}}; --}}

@extends('layouts.app')

@section('css_js_datatables')
   <!-- funciones javascript propias:  -->
   <script src="{{ asset('js/ordenes.js') }}"></script>
   <!-- styles propios para cruds:
           Para título de la ventana, mensajes de error, espacio superior,
           que los campos obligatorios tengan el asterisco rojo
           conversion de input text a mayúsculas
           y otros específicos para el formulario de clientes
           y medias querys para manejo en celus, tablets, etc...
    -->
   <link href="{{ asset('css/propios_cruds.css') }}" rel="stylesheet">

   {{-- para el jquery validation: --}}
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>

<style>

   /* ===============================================================
      ESTILOS PARA EL AUTOCOMPLETED:
   ================================================================ */
   /* para que los items tengan el mismo ancho que el input box */
   /* * {
     box-sizing: border-box;
   } */

   /*the container must be positioned relative:*/
   /* .autocomplete {
     position: relative;
     display: inline-block;
   } */

   /* input {
     border: 1px solid transparent;
     background-color: #f1f1f1;
     padding: 10px;
     font-size: 16px;
   }

   input[type=text] {
     background-color: #f1f1f1;
     width: 100%;
   } */

   .autocomplete-items {
   /* border: 1px solid yellow; */
     /* position: absolute; */
     border: 1px solid #d4d4d4;
     border-bottom: none;
     border-top: none;
     z-index: 99;
     /*position the autocomplete items to be the same width as the container:*/
     top: 100%;
     left: 0;
     right: 0;
   }

   /* coloca lineas de separación entre items */
   .autocomplete-items div {
   /* border: 1px solid blue; */
     padding: 10px;
     cursor: pointer;
     /* background-color: #fff; */
     border-bottom: 1px solid #d4d4d4;
   }

   /*when hovering an item:*/
   .autocomplete-items div:hover {
   /* border: 1px solid red; */
     background-color: #e9e9e9;
   }

   /*when navigating through the items using the arrow keys:*/
   .autocomplete-active {
   /* border: 1px solid green; */
     background-color: DodgerBlue !important;
     color: #ffffff;
   }

</style>

@endsection()

@section('content')
   <div class="card-header tit" >

      {{-- encabezado de la captura de cais --}}
      <div class="row">

            @if($origen == "crear")
               <div class="col-11 col-sm-11 col-md-11 col-lg-11">
                  <i>CREAR orden de servicio para el cliente:</i>
            @elseif($origen == "modificar")
               <div class="col-9 col-sm-9 col-md-9 col-lg-9">
                  <i>MODIFICAR orden de servicio del cliente:</i>
            @else
               <div class="col-9 col-sm-9 col-md-9 col-lg-9">
                  <i>CERRAR orden de servicio del cliente:</i>
            @endif
         </div>
            @if($origen == "crear")
               <div class="col-1 col-sm-1 col-md-1 col-lg-1" style="text-align: right">
                  &nbsp;
            @else
               <div class="col-3 col-sm-3 col-md-3 col-lg-3">
                  Orden número: {{$num_orden}}
            @endif
         </div>
      </div>
      <div class="row">
         <div class="col-12 col-sm-9 col-md-9 col-lg-9">
            {{$cliente_nombre}}
         </div>
         <div class="col-12 col-sm-3 col-md-3 col-lg-3">
            Placa: {{$placa}}
         </div>
      </div>

   </div>

   <div class="container">
      {{-- <div class="row justify-content-center"> --}}
      <div class="row">
         {{-- <form id="form_pedir_cais"
           action="{{ url('/generar_orden_servicio') }}"
           method="POST"> --}}
         <form id="form_pedir_cais">
               {{-- @csrf --}}
               <div class="row espacio_filas">
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5">
                     <label class="tam_peq_label">Seleccione el producto:</label>
                     <input type="text"
                        class="form-control"
                        name="php_producto"
                        id="idinput_producto"
                        autocomplete = "off"
                        placeholder="Buscar...">
                     {{-- para mostrar la descripción adicional, si es un
                     repuesto o servicio a terceros: --}}
                     <span id="idspan_adic"  style="color: blue;"></span>

                     <input type="hidden" id="idproducto_escogido_hidden" name="php_idproducto">
                     <input type="hidden" id="idproducto_adic_hidden" name="php_idproducto">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2">
                     <label class="tam_peq_label">Cantidad:</label>
                     <input
                        id="id_canti"
                        name="php_canti"
                        type="number"
                        autocomplete = "off"
                        class="form-control tam_peq"
                     />
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                     <label class="tam_peq_label">Operario:</label>
                     <select
                        class="form-control tam_peq"
                        id="selectOperarios"
                        name="php_operario"
                     >
                       {{-- el combo es llenado desde javascript --}}
                     </select>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-1">
                     <br>
                     <button
                        class="btn btn-primary"
                        type="button"
                        onclick="agregar_a_tabla_html_js('{{url('/leer_datos_producto_escogido')}}' , '{{asset("/")}}')"> +
                     </button>
                  </div>
               </div>

               <br><br>
               <div class="row espacio_filas" >
                  <table border="2" id="idtablacais" class="table">
                     <thead class="thead-light">
                        <th  style='display:none;'>
                           &nbsp;
                        </th>
                        <th  style='display:none;'>
                           &nbsp;
                        </th>
                        <th>
                           CANT
                        </th>
                        <th>
                           CAI
                        </th>
                        <th>
                           DESCRIPCIÓN
                        </th>
                        <th>
                           &nbsp;
                        </th>
                        <th>
                           OPERARIO
                        </th>
                        <th>
                           VR UNIT
                        </th>
                        <th>
                           VR TOTAL
                        </th>
                     </thead>
                  </table>
               </div>

               <br><br>
               {{-- BOTONES  --}}
               {{-- generar_orden_js() se encuentra en ordenes.js --}}
               @if($origen == "crear")
                  {{-- fue llamada desde CREAR ORDEN DE SERVICIO --}}
                  <div class="row espacio_filas" >
                     <button
                        class="btn btn-primary"
                        type="button"
                        onclick="generar_orden_js('{{ url('/guardar_servicios') }}' , '{{ url('/generar_pdf_orden_servicio') }}' , '{{ url('/orden_cedula') }}' , '{{ $cliente_id }}'   , '{{ $vehiculo_id }}'     )">Generar la orden de servicio
                     </button>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <button
                        type="button"
                        class="btn btn-primary"
                        onclick="window.location='{{ url('/orden_cedula') }}'" >Cancelar
                     </button>
                  </div>
               @elseif($origen == "modificar")
                  {{-- fue llamada desde MODIFICAR UNA ORDEN DE SERVICIO ABIERTA: --}}
                  <div class="row espacio_filas" >
                     <button
                        class="btn btn-primary"
                        type="button"
                        onclick="modificar_orden_js('{{ url('/modificar_servicios') }}' , '{{ url('/generar_pdf_orden_servicio') }}' , '{{ url('/ordenes_abiertas_listar') }}' , '{{ $num_orden }}' , '{{ $cliente_id }}'   , '{{ $vehiculo_id }}' )">Modificar la orden de servicio
                     </button>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <button
                        type="button"
                        class="btn btn-primary"
                        onclick="window.location='{{ url('/ordenes_abiertas_listar') }}'" >Cancelar
                     </button>
                  </div>
               @else
                  {{-- fue llamada desde CERRAR UNA ORDEN DE SERVICIO: --}}
                  <div class="row espacio_filas" >
                     <button
                        class="btn btn-primary"
                        type="button"
                        onclick="cerrar_orden_js('{{ url('/modificar_servicios') }}' , '{{ url('/cerrar_orden_bd') }}' , '{{ url('/generar_pdf_orden_servicio') }}' , '{{ url('/ordenes_cerrar_listar') }}' , '{{ $num_orden }}' , '{{ $cliente_id }}'   , '{{ $vehiculo_id }}' )">Cerrar la orden de servicio
                     </button>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <button
                        type="button"
                        class="btn btn-primary"
                        onclick="window.location='{{ url('/ordenes_cerrar_listar') }}'" >Cancelar
                     </button>
                  </div>
               @endif
         </form>
      </div>
   </div>

   {{-- formulario modal para pedir adicionales a: repuestos o servicios --}}
   <div class="modal" tabindex="-1" role="dialog" id="myModal">
     <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title"><b><span id="idmodal_titulo"></span></b></h5>

           {{-- botón X para cerrar: --}}
           {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button> --}}
         </div>
         <div class="modal-body">
           <p>
             <input type="text"
                class="form-control upperCase"
                id="idmodal_adic">
           </p>
         </div>
         <div class="modal-footer">
           <button id="modalbot_grabar" type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript:grabar_modal();">Grabar</button>
           <button id="modalbot_cancelar" type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript: document.getElementById('idinput_producto').value=''">Cancelar</button>
         </div>
       </div>
     </div>
   </div>
   {{-- fin del formulario modal para pedir observaciones --}}

   {{-- formulario modal para permitir modificar un cai (tabla html) --}}
   <div class="modal" tabindex="-1" role="dialog" id="myModalEditarCai">
      <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
            {{-- header: --}}
            <div class="modal-header">
               <h5 class="modal-title"><b><span id="idmodal_editar_cai_titulo">Modificación de un producto</span></b></h5>
            </div>
            {{-- body: --}}
            <div class="modal-body">
               <div class="row" >
                  <div class="col-12 col-sm-1 col-md-1 col-lg-1">
                     Cai:
                  </div>
                  <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                     <b><span id="idcai_modal"></span></b>
                  </div>
                  <div class="col-12 col-sm-2 col-md-3 col-lg-2">
                     Descripción:
                  </div>
                  <div class="col-12 col-sm-6 col-md-5 col-lg-6">
                     <b><span id="iddescripcion_modal"></span></b>
                  </div>
               </div>
               <br>
               <div class="row">
                  <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                     <label >Cantidad:</label>
                        <input
                           id="id_canti_modal"
                           name="php_canti"
                           type="number"
                           autocomplete = "off"
                           class="form-control tam_peq"
                        />
                  </div>
                  <div class="col-12 col-sm-6 col-md-9 col-lg-10">
                     <label >Operario:</label>
                     <select
                        class="form-control tam_peq"
                        id="selectOperarios_modal"
                        name="php_operario">
                        {{-- el combo es llenado desde javascript --}}
                     </select>
                  </div>
               </div>

               <input type="hidden"
                  id="idproducto_id_modal_hidden">
               <input type="hidden"
                  id="idproducto_adic_modal_hidden">
            </div>
            {{-- footer: --}}
            <div class="modal-footer">
               <button id="modal_editar_cai_grabar_modal" type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript:grabar_modal_editar();">Grabar</button>
               <button id="modal_editar_cai_cancelar_modal" type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript: document.getElementById('idinput_producto').value=''">Cancelar</button>
            </div>
         </div>
      </div>
   </div>
   {{-- fin del formulario modal para permitir modificar un cai (tabla html) --}}


@endsection()

@section('javascript_onReady')
   <script>
      $(document).ready(function(){
         // llena los select operarios de los dos formularios (el principal y el modal
         // que permite modificar un cai):
         llenar_select_operarios();
         llenar_select_operarios('formu_modal');
         if('{{$origen}}' == 'modificar'
               || '{{$origen}}' == 'cerrar'){
            // la vista fue llamada desde MODIFICAR UNA ORDEN ABIERTA o desde
            // CERRAR ORDEN, debe mostrar en la tabla html los CAIS existentes
            llenar_tabla_html('{{asset("/")}}');
         }

      });    // fin del onReady()
   </script>
@endsection()

@section('javascript_funciones')
   <script>
      // evento click para el botón BORRAR UN ITEM (X)
      // NOTAS:
      // 1) No puede ir en el onReady() debido a que los botones se crean
      // dinámicamente a medida que se va llenando la tabla items_cais
      // 2) Tiene que hacerse por jquery ya que si se hace on javascript
      // addEventListener, da el error NULL cuando aun no hayan filas en la tabla cais
      // document.getElementById("borrar").addEventListener('click' , function(event){
      //    $(this).closest('tr').remove();
      // });
      $(function () {
          $(document).on('click', '.borrar', function (event) {
              // event.preventDefault();
              $(this).closest('tr').remove();
          });
      });

      // 14feb2020  eliminado
      // // evento click para el botón EDITAR UN CAI en la tabla HTML
      // // NOTAS:
      // // 1) No puede ir en el onReady() debido a que los botones se crean
      // // dinámicamente a medida que se va llenando la tabla items_cais
      // // 2) Tiene que hacerse por jquery ya que si se hace on javascript
      // // addEventListener, da el error NULL cuando aun no hayan filas en la tabla cais
      // $(function () {
      //     $(document).on('click', '#editar_cai', function (event) {
      //         // event.preventDefault();
      //         // abrir el modal para modificar el cai:
      //         jQuery.noConflict();
      //         $('#myModalEditarCai').modal();
      //     });
      // });

      function editar_cai(canti , operario_id , cai , producto_adic , descripcion, producto_id){
         // obtener la cantidad que actualmente está en la tabla html y colocarla en el input text:
         document.getElementById("id_canti_modal").value = canti;

         // obtener el operario que actualmente está en la tabla html y seleccionarlo en el select:
         var aux_select_operarios_modal = document.getElementById("selectOperarios_modal");
         for (i = 0 ; i < aux_select_operarios_modal.length ; i++) {
            if (aux_select_operarios_modal[i].value == operario_id) {
               aux_select_operarios_modal[i].selected = true;
            }
         }

         // abrir el modal para modificar el cai:
         jQuery.noConflict();
         $('#myModalEditarCai').modal();
         // para colocar el cai y descripción del producto escogido en la tabla hmtl:
         document.getElementById("idcai_modal").innerHTML = cai;
         document.getElementById("iddescripcion_modal").innerHTML = descripcion + ' ' + producto_adic;
         document.getElementById("idproducto_id_modal_hidden").value = producto_id;
         document.getElementById("idproducto_adic_modal_hidden").value = producto_adic;
      }

      // *********************************************************************
      //    comienzo de las funciones para el autocomplete input text y
      //    pasos a hacer cuando se escoja un producto
      // *********************************************************************
      {{--
         03feb2020
         Funciones para el autotext completed:
         basado en: https://www.w3schools.com/howto/howto_js_autocomplete.asp
      --}}
      var escogido_id;
      function autocomplete(inp) {
        /*the autocomplete function takes one argument,
        the input text field element */
        var currentFocus;
        /* evento para cuando se escriba algo en el campo idinput_producto:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            document.getElementById("idproducto_escogido_hidden").value = "";
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            // 10feb2020
            // debido a que val (el nombre del producto) puede tener la
            // barra que se usan en las url, hay que codificarla "a mano".
            // No se debe olvidar descodificarla en el php (ordencontroller.php)
            val = val.replace('/' , '_@_')
            $.ajax({
               url: '{{ url('autocompletar_productos') }}' + '/' + val,
               type:"GET",
               success:function (data) {
                 // recibe un objeto json con tantas filas como productos
                 // haya encontrado en la b.d.  y con dos columnas:
                 //    producto
                 //    id
                  arr = data;
                  val = val.replace('_@_' , '/')
                  for (i = 0; i < arr.length; i++) {
                    if (arr[i].producto.toUpperCase().includes(val.toUpperCase())) {
                      /*create a DIV element for each matching element:*/
                      b = document.createElement("DIV");
                      /*make the matching letters bold:*/
            		    posi = arr[i].producto.toUpperCase().indexOf(val.toUpperCase());
                      b.innerHTML = arr[i].producto.substr(0, posi);
                      b.innerHTML += "<strong>" + arr[i].producto.substr(posi , val.length) + "</strong>";
                      b.innerHTML += arr[i].producto.substr(posi + val.length , 100);

                      /*insert a input field that will hold the current array item's value:*/
                      b.innerHTML += "<input type='hidden' value='" + arr[i].producto + "'>";
                      /*execute a function when someone clicks on the item value (DIV element):*/
                      b.addEventListener("click", function(e) {
                          /*insert the value for the autocomplete text field:*/
                          escogido_nombre = this.getElementsByTagName("input")[0].value;
                          inp.value = escogido_nombre;
                           //  03feb2020:
                           //  determinar  el id escogido:
                           for(key in arr){
                              if(arr[key].producto == escogido_nombre){
                                 escogido_id = arr[key].id;
                                 break;
                              }
                           }
                           document.getElementById("idproducto_escogido_hidden").value = escogido_id;
                           document.getElementById("idproducto_adic_hidden").value = null;

                           // 06feb2020:
                           // si en el nombre están incluidas las cadenas: '( REP-REPUESTOS )'
                           // o '( SERVI08BD )' debe pedir la descripción adicional:
                           posi_repu = escogido_nombre.indexOf('( REP-REPUESTOS )');
                           posi_serv = escogido_nombre.indexOf('( SERVI08BD )');

                           if(posi_repu == -1 && posi_serv == -1){
                              // se escogió un producto que no es ni
                              // repuesto ni servicio, no hay que
                              // mostrar el modal
                           }else{
                              // hay que mostrar el modal y llenar las
                              // descripciones adicionales:
                              document.getElementById('idmodal_adic').value = "";
                              if(posi_repu >= 0){
                                 // se escogió repuestos:
                                 document.getElementById("idmodal_titulo").innerHTML = "Descripción del repuesto:";
                              }else{
                                 // se escogió servicios:
                                 document.getElementById("idmodal_titulo").innerHTML = "Descripción del servicio:";
                              }
                              // mostrar el modal, el cual hará las correspondientes
                              // acciones de acuerdo al boton que escoja el usuario en él:
                              jQuery.noConflict();
                              $('#myModal').modal({
                                 // opciones que evitan que se cierre al dar click afuera
                                 backdrop: 'static',
                                 keyboard: false
                              });
                              document.getElementById("idmodal_adic").focus();
                           }

                           /*close the list of autocompleted values,
                           (or any other open lists of autocompleted values:*/
                           closeAllLists();
                      });  // fin del evento click al escoger producto
                      a.appendChild(b);
                    }
                  }
               },   // fin del success ajax
               failure:function(msgfail){
                  console.log('entro al failure');
                  console.log(msgfail);
               }
            })      // fin del ajax que bus a en la b.d.
        });

        /* evento cuando se presiona una tecla en el input text autocomplete */
        inp.addEventListener("keydown", function(e) {
           document.getElementById('idspan_adic').innerHTML = null;
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
              /*If the arrow DOWN key is pressed,
              increase the currentFocus variable:*/
              currentFocus++;
              /*and and make the current item more visible:*/
              addActive(x);
            } else if (e.keyCode == 38) { //up
              /*If the arrow UP key is pressed,
              decrease the currentFocus variable:*/
              currentFocus--;
              /*and and make the current item more visible:*/
              addActive(x);
            } else if (e.keyCode == 13) {
              /*If the ENTER key is pressed, prevent the form from being submitted,*/
              e.preventDefault();
              if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
              }
            }
        });

        function addActive(x) {
          /*a function to classify an item as "active":*/
          if (!x) return false;
          /*start by removing the "active" class on all items:*/
          removeActive(x);
          if (currentFocus >= x.length) currentFocus = 0;
          if (currentFocus < 0) currentFocus = (x.length - 1);
          /*add class "autocomplete-active":*/
          x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
          /*a function to remove the "active" class from all autocomplete items:*/
          for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
          }
        }
        function closeAllLists(elmnt) {
          /*close all autocomplete lists in the document,
          except the one passed as an argument:*/
          var x = document.getElementsByClassName("autocomplete-items");
          for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
              x[i].parentNode.removeChild(x[i]);
            }
          }
        }
        /*execute a function when someone clicks in the document:
        (para cerrar la lista si se dá click fuera de ella)*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
      }   // fin de la función autocomplete()

      // activar la función autcomplete, asignándola al input text:
      autocomplete(document.getElementById("idinput_producto"));

      // *********************************************************************
      //    fin de las funciones para el autocomplete input text
      // *********************************************************************


      function grabar_modal(){
         // proceso para cuando se escriben las descripciones adicionales
         // en el modal para repuestos o servicios:
         // Hace lo siguiente:
         //   verifica que se haya digitado algo
         //   muestar lo digitado en el div debajo del input text autocomplete
         //   agrega lo digitado al input hidden de adicionales
         modal_adicional = document.getElementById('idmodal_adic').value;
         if(modal_adicional == ""){
            alert("Debe escribir la descripción adicional, o escoger otro producto");
            document.getElementById('idinput_producto').value = '';
         }else{
            // document.getElementById('idinput_producto').value = document.getElementById('idinput_producto').value + ' - ' + modal_adicional;
            modal_adicional = modal_adicional.toUpperCase();
            document.getElementById('idspan_adic').innerHTML = modal_adicional;
            document.getElementById('idproducto_adic_hidden').value = modal_adicional;
         }
      }

      function grabar_modal_editar(){
         // proceso para cuando se modifican los datos de un cai y se
         // presiona el botón grabar en el modal:
         // Hace lo siguiente:
         //   verifica que se haya digitado la cantidad
         //   poner la nueva cantidad y operario en la tabla html, la cantidad
         //   va en la columna 2, por el operario se deben actualizar las
         //   columnas nro 1 (idoperario) y 6 (nombre del operario)

         // llevar a variables lo modificado por el usuario:
         var modal_nueva_canti = document.getElementById('id_canti_modal').value;
         var aux_select_operarios_modal = document.getElementById("selectOperarios_modal");
         var operarioSeleccionado = aux_select_operarios_modal.options[aux_select_operarios_modal.selectedIndex];
         var modal_nuevo_operario_id = operarioSeleccionado.value;
         var modal_nuevo_operario_nombre = operarioSeleccionado.text;
         var modal_producto_id = document.getElementById('idproducto_id_modal_hidden').value;
         var modal_producto_adic = document.getElementById('idproducto_adic_modal_hidden').value;

         // colocar los nuevos datos en la fila de la tabla html correspondiente:
         // la siguiente función se encuentra en ordenes.js:
         buscar_y_modificar_en_tabla_html(modal_producto_id , modal_producto_adic , modal_nueva_canti , modal_nuevo_operario_id , modal_nuevo_operario_nombre , '{{asset("/")}}');
      }

      // función para llenar el combo de operarios:
      function llenar_select_operarios(origen = null , seleccionar = null){
         // 05feb2020
         // llamada desde dos partes distintas:
         //      desde el onReady()
         //      desde ordenes.js, function agregar_a_tabla_html_js
         // Si los parámetros llegan con algún contenido, significa que fue
         // llamada para MODIFICAR un cai, en caso contrario (cuando los
         // parámetros asumen el null), fue llamada para ADICIONAR un cai
         // Recordar que $operarios es el array de operarios (nombre e id de
         // la tabla users) que llegó como parámetro a esta vista blade.
         var obj_operarios =  @json($operarios);
         if(origen == null){
            // fue llamada desde onReady() o desde ordenes.js para llenar
            // el select del formulario que AGREGA un cai
            combo_ope = document.getElementById("selectOperarios");
         }else{
            // fue llamada desde onReady() para llenar el select
            // del formulario MODAL que permite MODIFICAR un cai
            combo_ope = document.getElementById("selectOperarios_modal");
         }
         while (combo_ope.length > 0) {
          combo_ope.remove(0);
         }

         // 18feb2020
         // las siguientes instrucciones fueron borrardas para que
         // no aparezca: Seleccione operario .... (en vez de eso
         // aparecerá seleccionado el operario cuyo nombre es "OPERARIO NO ASIGNADO")
         // opcion = document.createElement("option");
         // opcion.value = "0";
         // opcion.text = "Seleccione operario...";
         // opcion.selected = true;
         // opcion.disabled = true;
         // opcion.style.display = "none";
         // combo_ope.appendChild(opcion);

         obj_operarios.forEach(function(fila,index,arr) {
            opcion_ope = document.createElement("option");
            opcion_ope.value = fila.id;
            opcion_ope.text = fila.name;
            if(fila.name == "OPERARIO NO ASIGNADO"){
               opcion_ope.selected = true;
            }
            combo_ope.appendChild(opcion_ope);
         });
      }      // fin de la función llenar_select_operarios

      function llenar_tabla_html(helper_asset){
         // llamada desde el onReady al detectar que se llegó a esta
         // vista para MODIFICAR una orden abierta.
         // Debe llenar la tabla html con los datos que hayan en la
         // tabla servicios_detalles:
         // Hace un llamado get ajax para obtener toda la información de
         // la orden, y los resultados obtenidos los pone en la tabla html:
         var baseUrl = '{{ url('/modificar_orden_leer_cais') }}' + '/' + '{{$num_orden}}';
         $.ajax({
            url: baseUrl,
            type: "GET",
            success: function(data){
               // llega un array con tantas filas como cais tenga la
               // orden, y con estas columnas:
               //    num_orden
               //    producto_id
               //    operario_id
               //    canti
               //    cai
               //    descripcion
               //    producto_adic
               //    operario_nombre
               //    vlr_uni
               //    vlr_tot
               for(key in data){
                  var producto_id = data[key].producto_id;
                  var operario_id = data[key].operario_id;
                  var canti = data[key].canti;
                  var cai = data[key].cai;
                  var descripcion = data[key].descripcion;
                  var producto_adic = data[key].producto_adic;
                  var vlr_unitario = data[key].vlr_uni;
                  var operario = data[key].operario_nombre;
                  var vlr_total = vlr_unitario * canti;
                  var vlr_unitario_f = number_format_round(vlr_unitario , 2);
                  var vlr_total_f = number_format_round(vlr_total , 2);
                  // la función generar_fila_tabla_html() está en ordenes.js:
                  var tr = generar_fila_tabla_html(producto_id , operario_id , canti , cai , descripcion , producto_adic , operario , vlr_unitario_f , vlr_total_f , helper_asset);
                  $("#idtablacais").append(tr);
               }      // fin for ... in que recorre el resultado del mysql
            },
            failure: function(msgFail){
               console.log(msgFail);
            }
         });

      }

   </script>
@endsection()

@section('javascript_jquery_validation')
   <script>
   // if($('#form_pedir_cliente_orden_servicio').length > 0){
   //   $('#form_pedir_cliente_orden_servicio').validate({
   //      rules: {
   //         php_doc_num: {
   //           required: true,
   //         },
   //      },
   //      messages: {
   //         php_doc_num: {
   //           required: "Debe digitar el número del documento",
   //         },
   //      },
   //      submitHandler: function(form) {
   //          form.submit();
   //     }
   //   });
   // }   // fin del if length (no tiene else)

   </script>
@endsection()
