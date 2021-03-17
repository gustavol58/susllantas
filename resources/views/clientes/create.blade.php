{{-- recibe 1 parámetro:
   doc_num    llega una cadena vacia ('')
--}}

@extends('layouts.appnolive')

@section('css_js_datatables')
   {{-- 11nov2019 comentariado por que ya está en layouts.app --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSFR token for ajax call -->
  <meta name="_token" content="{{ csrf_token() }}"/>

   {{-- para jquery validation --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>

  <!-- funciones javascript propias:  -->
  <script src="{{ asset('js/clientes.js') }}"></script>


  <!-- styles propios para cruds:
          Para título de la ventana, mensajes de error, espacio superior,
          que los campos obligatorios tengan el asterisco rojo
          conversion de input text a mayúsculas
          y otros específicos para el formulario de clientes
          y medias querys para manejo en celus, tablets, etc...
   -->
  <link href="{{ asset('css/propios_cruds.css') }}" rel="stylesheet">

   {{-- estilos y funciones javascript para tomar la firma del cliente: --}}
   <link href="{{ asset('css/propios_signature.css') }}" rel="stylesheet">
   <script src="{{ asset('js/signature.js') }}"></script>

@endsection()

@section('content')
<div class="card uper" style="margin-top: 0 !important;">
  <div class="card-header tit" >
    <center>Creación de un nuevo cliente</center>
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
    <form id="form_crear_cliente" method="post" action="javascript:void(0)">
        @csrf

        @include('partials.form_crear_cliente')

      {{-- botones del formulario (grabar y cancelar) --}}
      <br><br>
      <button id="send_form_clientes" type="submit" class="btn btn-primary"  >Crear cliente</button>
      <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('clientes') }}'" >Cancelar</button>

     </form>
  </div>
</div>
<script>
   $(document).ready(function(){
      llenar_select_doc_tipo_js('{{ url('llenar_select_doc_tipo') }}');
      llenar_select_dir_ppal_js('{{ url('llenar_select_dir_ppal') }}');
      llenar_select_dpto_js('{{ url('llenar_select_dpto') }}');
   });

   // Creación del objeto que permite escribir la firma y que será
   // utilizado por funciones de clientes.js
   var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
     backgroundColor: 'rgba(255, 255, 255, 0)',
     penColor: 'red',
   });

   // fragmente de código javascript a ejecutar cuando el usuario
   // presione el botón GUARDAR el cliente:
   // define la validación y el submit para todos los campos del formulario:
   if ($("#form_crear_cliente").length > 0) {
    $("#form_crear_cliente").validate({
      rules: {
         doc_tipo: {
           required: true,
         },
         doc_num: {
             required: true,
             // number: true,    // no es necesario porque se valida para el dv
             maxlength: 15,
         },
         nombre1:{
            maxlength: 20,
         },
         nombre2:{
            maxlength: 20,
         },
         apellido1:{
            maxlength: 20,
         },
         apellido2:{
            maxlength: 20,
         },
         razon_social:{
            maxlength: 80,
         },
         dpto: {
           required: true,
         },
         dir_ppal: {
           required: true,
         },
         tel_fijo: {
            number: true,
         },
         tel_celu: {
            number: true,
         },
         email: {
           required: true,
           email: true,
         },
         cumple_dia: {
           required: true,
           number: true,
           range: [1, 31],
         },
         cumple_mes: {
           required: true,
         },
      },
      messages: {
         doc_tipo: {
           required: "Debe escoger un tipo de documento",
         },
         doc_num: {
             required: "Debe escribir un número de documento",
             // number: "Debe digitar un número",
             maxlength: "Hasta 15 dígitos",
         },
         nombre1:{
            maxlength: "Longitud máxima: 20 caracteres",
         },
         nombre2:{
            maxlength: "Longitud máxima: 20 caracteres",
         },
         apellido1:{
            maxlength: "Longitud máxima: 20 caracteres",
         },
         apellido2:{
            maxlength: "Longitud máxima: 20 caracteres",
         },
         razon_social:{
            maxlength: "Longitud máxima: 80 caracteres",
         },
         dpto: {
           required: "Debe escoger un departamento",
         },
         dir_ppal: {
           required: "Debe suministrar una dirección",
         },
         tel_fijo: {
           number: "Debe escribir un número",
         },
         tel_celu: {
           number: "Debe escribir un número",
         },
         email: {
           required: "Debe escribir un email",
           email: "Debe escribir un formato correcto de email",
         },
         cumple_dia: {
           required: "Debe escribir el día de cumpleaños",
           number: "Debe escribir un número",
           range: "Debe escribir un número entre 1 y 31",
         },
         cumple_mes: {
           required: "Debe escoger el mes de cumpleaños",
         },
      },
      // fin de la validación lado cliente del formulario

      submitHandler: function(form) {
        var token = $('meta[name="csrf-token"]').attr('content');
        $('#send_form').html('Agregando ...');
        baseUrl = '{{ url('clientes') }}';   // ClientreController@store
        $.ajax({
          url:baseUrl,
          type: "POST",
          header:{
            'X-CSRF-TOKEN': token
          },
          data: $('#form_crear_cliente').serialize(),
          success: function( response ) {
              $('#send_form').html('Crear cliente');
              $('#res_message').show();
              $('#res_message').html(response.msg);
              $('#res_message_servidor').html('');
              $('#res_message_servidor').hide();
              $('#msg_div').removeClass('d-none');
              document.getElementById("form_crear_cliente").reset();
              $('#errores_servidor').addClass('d-none');
              // que el tipo de documento por defecto sea CÉDULA:
              var miObjeto = new Object();
              miObjeto.value = 1;
              procesar_tipo_doc(miObjeto);
              // limpiar y reiniciar los combos:
              combo = document.getElementById("selectDoc_tipo");
              while (combo.length > 0) {
                combo.remove(0);
              }
              combo = document.getElementById("selectDir_ppal");
              while (combo.length > 0) {
                combo.remove(0);
              }
              combo = document.getElementById("selectDpto");
              while (combo.length > 0) {
                combo.remove(0);
              }
              llenar_select_doc_tipo_js('{{ url('llenar_select_doc_tipo') }}');
              llenar_select_dir_ppal_js('{{ url('llenar_select_dir_ppal') }}');
              llenar_select_dpto_js('{{ url('llenar_select_dpto') }}');
              // borrar las opciones que puedan existir
              // en el select ciudades:
              combo = document.getElementById("selectCiudad");
              while (combo.length > 0) {
                combo.remove(0);
              }
              // que no muestre los campos auxiliares de dirección:
              document.getElementById('div_dir_num_ppal').style.display = "none";
              document.getElementById('div_dir_num_casa').style.display = "none";
              document.getElementById('div_dir_adic').style.display = "none";
              window.scrollTo(0, 0);
              // setTimeout(function(){
              //   $('#res_message').hide();
              //   $('#msg_div').hide();
              // },10000);
          },
          error:function (error) {
            console.log('entro a error...');
            console.log(error);
            $('#send_form').html('Crear cliente');

            var html_error='No se pudo agregar el cliente por las siguientes razones:<br /><ul>';
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
