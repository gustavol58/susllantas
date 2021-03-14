@extends('layouts.app')

@section('css_js_datatables')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSFR token for ajax call -->
  <meta name="_token" content="{{ csrf_token() }}"/>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>

  <!-- funciones javascript propias:  -->
  {{-- <script src="{{ asset('js/productos.js') }}"></script> --}}

  <!-- styles propios para cruds:
          Para título de la ventana, mensajes de error, espacio superior,
          que los campos obligatorios tengan el asterisco rojo
          conversion de input text a mayúsculas
          y otros específicos para el formulario de clientes
          y medias querys para manejo en celus, tablets, etc...
   -->
  <link href="{{ asset('css/propios_cruds.css') }}" rel="stylesheet">
@endsection()

@section('content')
<div class="card uper" style="margin-top: 0 !important;">
  <div class="card-header tit">
    <center>Creación de un nuevo producto</center>
  </div>
  <div class="card-body">
    <!-- notificación desde la validación del lado cliente: -->
    <div class="alert alert-success d-none" id="msg_div">
        <span  id="res_message"></span>
    </div>
    <!-- notificacion desde la validación del lado servidor -->
    <div class="alert alert-danger d-none" id="errores_servidor">
        <span  id="res_message_servidor"></span>
    </div>
    <form id="form_crear_producto" method="post" action="javascript:void(0)">
        @csrf

        {{-- fila para pedir CAI y nombre del producto --}}
        <div class="row" >
           <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 required">
            <label>CAI:</label>
            <input type="text" class="form-control upperCase"
                id="cai"
                name="cai"
                value="{{old('cai')}}" >
            <span class="text-danger">{{ $errors->first('cai') }}</span>
          </div>
           <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 required">
            <label>Nombre del producto:</label>
            <input type="text" class="form-control upperCase"
                id="nombre"
                name="nombre"
                value="{{old('nombre')}}" >
            <span class="text-danger">{{ $errors->first('nombre') }}</span>
          </div>
        </div>
        {{-- fin de la fila  para pedir CAI y nombre del producto --}}

        <br>
        {{-- fila para pedir costo, precio, marca y linea --}}
        <div class="row" >
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Costo:</label>
            <input type="text" class="form-control"
                id="costo"
                name="costo"
                value="{{old('costo')}}" >
            <span class="text-danger">{{ $errors->first('costo') }}</span>
           </div>
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Precio:</label>
            <input type="text" class="form-control"
                id="precio"
                name="precio"
                value="{{old('precio')}}" >
            <span class="text-danger">{{ $errors->first('precio') }}</span>
           </div>
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Marca:</label>
            <input type="text" class="form-control upperCase"
                id="marca"
                name="marca"
                value="{{old('marca')}}" >
            <span class="text-danger">{{ $errors->first('marca') }}</span>
           </div>
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Linea:</label>
            <input type="text" class="form-control upperCase"
                id="linea"
                name="linea"
                value="{{old('linea')}}" >
            <span class="text-danger">{{ $errors->first('linea') }}</span>
           </div>
        </div>
        {{-- fin de la fila  para pedir costo, precio, marca y linea --}}

         <br>
         {{-- fila  para pedir cuenta contable y casillas de iva (iva va dentro de un fieldset) --}}
         <div class="row">
            {{-- cuenta contable: --}}
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
               <label>Cuenta contable:</label>
                <input type="text" class="form-control"
                    id="clave"
                    name="clave"
                    value="{{old('clave')}}" >
                <span class="text-danger">{{ $errors->first('clave') }}</span>
            </div>
            {{-- fildset para ivas: --}}
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
               <fieldset class="scheduler-border">
                  <label>&nbsp;</label>
                  <div class="row">
                     <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-center">
                       <input type="checkbox"
                             id="iva_ventas"
                             value="1"     {{-- si llega al $_POST llega con 1, que es  true  --}}
                             style="width:1.5em;height:1.5em;"
                             name="iva_ventas" > Iva en ventas
                       <span class="text-danger">{{ $errors->first('iva_ventas') }}</span>
                     </div>
                     <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-center">
                       <input type="checkbox"
                             id="iva_compras"
                             value="1"
                             style="width:1.5em;height:1.5em;"
                             name="iva_compras" > Iva en compras
                       <span class="text-danger">{{ $errors->first('iva_compras') }}</span>
                     </div>
                     <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-center">
                       <input type="checkbox"
                             id="iva_dif"
                             value="1"
                             style="width:1.5em;height:1.5em;"
                             name="iva_dif" > Iva diferencial
                       <span class="text-danger">{{ $errors->first('iva_dif') }}</span>
                     </div>
                  </div>
               </fieldset>
            </div>
         </div>
         {{-- fin de la fila para pedir  cuenta contable y casillas de iva (iva va dentro de un fieldset) --}}

         <br>
        <button id="send_form" type="submit" class="btn btn-primary">Crear producto</button>
        <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('productos') }}'" >Cancelar</button>
      </form>
  </div>
</div>
<script>
   // fragmente de código javascript a ejecutar cuando el usuario
   // presione el botón GUARDAR el producto:
   // define la validación y el submit para todos los campos del formulario:
   if ($("#form_crear_producto").length > 0) {
      // 20nov2019
      // para que no presente conflictos por las diferentes versiones jquery utilizadas:
      // jQuery.noConflict();
      $("#form_crear_producto").validate({
         rules: {
            cai: {
              required: true,
              maxlength: 15
            },
            nombre: {
              required: true,
              maxlength: 40
            },
            costo: {
              required: true,
              number: true
            },
            precio: {
              required: true,
              number: true
            },
            marca: {
              required: true,
              maxlength: 5
            },
            linea: {
              required: true,
              maxlength: 20
            },
            clave: {
              required: true,
              maxlength: 12
            },
         },
         messages: {
            cai: {
              required: "Debe digitar CAI",
              maxlength: "CAI no puede tener más de 15 caracteres"
            },
            nombre: {
              required: "Debe digitar el nombre del producto",
              maxlength: "El nombre del producto no puede tener más de 40 caracteres"
            },
            costo: {
              required: "Debe digitar el costo",
              number: "El costo debe ser un número"
            },
            precio: {
              required: "Debe digitar el precio",
              number: "El precio debe ser un número"
            },
            marca: {
              required: "Debe digitar la marca",
              maxlength: "La marca no puede tener más de 5 caracteres"
            },
            linea: {
              required: "Debe digitar la linea",
              maxlength: "La linea no puede tener más de 20 caracteres"
            },
            clave: {
              required: "Debe digitar la cuenta contable",
              maxlength: "La cuenta contable no puede tener más de 12 caracteres"
            },
         },
         // fin de la validación lado cliente del formulario

         submitHandler: function(form) {
           var token = $('meta[name="csrf-token"]').attr('content');
           $('#send_form').html('Agregando ...');
           baseUrl = '{{ url('productos') }}';   // ProductoController@store
           $.ajax({
             url:baseUrl,
             type: "POST",
             header:{
               'X-CSRF-TOKEN': token
             },
             data: $('#form_crear_producto').serialize(),
             success: function( response ) {
                 $('#send_form').html('Crear producto');
                 $('#res_message').show();
                 $('#res_message').html(response.msg);
                 $('#res_message_servidor').html('');
                 $('#res_message_servidor').hide();
                 $('#msg_div').removeClass('d-none');
                 document.getElementById("form_crear_producto").reset();
                 $('#errores_servidor').addClass('d-none');
                 window.scrollTo(0, 0);
                 // setTimeout(function(){
                 //   $('#res_message').hide();
                 //   $('#msg_div').hide();
                 // },10000);
             },
             error:function (error) {
               console.log('entro a error...');
               console.log(error);
               $('#send_form').html('Crear producto');

               var html_error='No se pudo agregar el producto por las siguientes razones:<br /><ul>';
               var obj_response = JSON.parse(error.responseText);
               var obj_response_errors=obj_response.errors;
               Object.keys(obj_response_errors).forEach(function (key){
                 html_error = html_error + '<li>' + obj_response_errors[key][0] + '</li>';
               });
               html_error = html_error + '</ul>';

               $('#res_message_servidor').show();
               $('#res_message_servidor').html(html_error);
               $('#res_message').html('');
               $('#res_message').hide('');
               $('#errores_servidor').removeClass('d-none');
               // document.getElementById("res_message_servidor").focus();
               window.scrollTo(0, 0);
               // setTimeout(function(){
               // $('#res_message').hide();
               // $('#msg_div').hide();
               // },10000);
             },
             failure:function(msgfail){
               console.log('entro al failure');
               console.log(msgfail);
             }
           });    // fin del Ajax POST
        }  // fin de la función a ejecutar si la validación es correcta
      }) // fin de la validación y envío de los campos del formulario
   } // fin del if: si hay campos llenos en el formulario (el cual no tiene else)

</script>


@endsection
