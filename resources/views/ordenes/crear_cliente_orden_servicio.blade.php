{{-- recibe 1 parámetro:
   doc_num    trae el número de documento  a ser creado
--}}

@extends('layouts.app')

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

  {{-- para incluir el datapicker: --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
  <script src="{{ asset('js/bootstrap-datepicker.es.min.js') }}"></script>
  <link href="{{ asset('css/bootstrap-datepicker.standalone.css') }}" rel="stylesheet">

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
    <center>Creación de un nuevo cliente para orden de servicio</center>
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
    <form id="form_crear_cliente_orden_servicio" method="post" action="javascript:void(0)">
        @csrf

        @include('partials.form_crear_cliente')

        <br><br>
        {{-- fila  para titulo datos del vehículo y pedir placa --}}
        {{-- <div class="row espacio_filas" style="border: 1px solid red;"> --}}

         <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
               <center>AGREGAR VEHÍCULO&nbsp;&nbsp;&nbsp; ===> </center>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <label class="tam_peq_label upperCase">Placa:</label>
                <input type="text"
                   class="form-control upperCase tam_peq"
                    id="id_placa"
                    name="placa"
                >
            </div>
         </div>
        {{-- fin de la fila  para titulo datos del vehículo y pedir placa  --}}

        {{-- los demás datos del vehículo para orden de servicio: --}}
        @include('partials.form_edit_vehiculo' , ['origen' => 'orden_servicio'])

        <br><br>
        <button type="submit" class="btn btn-primary">Siguiente</button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('/orden_cedula') }}'" >Cancelar</button>

     </form>
  </div>
</div>

@endsection

@section('javascript_onReady')
   <script>
      $('#id_fec_soat').datepicker({
         format: "yyyy-mm-dd",
         language: 'es',
         autoClose: true,
      }).on('changeDate', function (ev) {    // para que cierre al escoger una fecha
           $(this).datepicker('hide');
      });

      $('#id_fec_tecno').datepicker({
         format: "yyyy-mm-dd",
         language: 'es',
      }).on('changeDate', function (ev) {    // para que cierre al escoger una fecha
           $(this).datepicker('hide');
      });

      $('#id_fec_extintor').datepicker({
         format: "yyyy-mm-dd",
         language: 'es',
      }).on('changeDate', function (ev) {    // para que cierre al escoger una fecha
           $(this).datepicker('hide');
      });

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
   </script>
@endsection

@section('javascript_jquery_validation')
   <script>
      // fragmente de código javascript a ejecutar cuando el usuario
      // presione el botón GUARDAR el cliente:
      // define la validación y el submit para todos los campos del formulario:
      if ($("#form_crear_cliente_orden_servicio").length > 0) {
       $("#form_crear_cliente_orden_servicio").validate({
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
            placa: {
               required: true,
            },
            marca: {
              required: true,
              maxlength: 50
            },
            modelo: {
              required: true,
              number: true
            },
            gama: {
              required: true,
              maxlength: 20
            },
            fec_soat: {
              required: true,
              date: true
            },
            fec_tecno: {
              required: true,
              date: true
            },
            fec_extintor: {
              required: true,
              date: true
            },
            kilom: {
               required: true,
               number: true
            },
            kilom_aceite: {
               required: true,
               number: true
            }
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
            placa: {
              required: "Debe digitar la placa",
            },
            marca: {
              required: "Debe digitar una marca",
              maxlength: "La marca no puede tener más de 50 caracteres"
            },
            modelo: {
              required: "Debe digitar el modelo",
              number: "Debe digitar un número (año de cuatro cifras)"
            },
            gama: {
               required: "Debe digitar la gama",
               maxlength: "La gama no puede tener más de 20 caracteres"
            },
            fec_soat: {
               required: "Debe suministrar la fecha de vencimiento del SOAT",
               date: "Debe suministrar una fecha"
            },
            fec_tecno: {
               required: "Debe suministrar la fecha de tecno-mecánica",
               date: "Debe suministrar una fecha"
            },
            fec_extintor: {
               required: "Debe suministrar la fecha de vencimiento del extintor",
               date: "Debe suministrar una fecha"
            },
            kilom: {
              required: "Debe digitar el kilometraje",
              number: "Debe digitar un número"
            },
            kilom_aceite: {
              required: "Debe digitar los kms para cambio de aceite",
              number: "Debe digitar un número"
            },
         },
         // fin de la validación lado cliente del formulario

         submitHandler: function(form) {
           var token = $('meta[name="csrf-token"]').attr('content');
           baseUrl = '{{ url('/crear_tablas_pedir_cais') }}';   // OrdenController@crear_tablas_pedir_cais
           $.ajax({
             url:baseUrl,
             type: "POST",
             header:{
               'X-CSRF-TOKEN': token
             },
             data: $('#form_crear_cliente_orden_servicio').serialize(),
             success: function( response ) {
                 // llamará el controlador que a su vez renderizará
                 // la vista encargada de pedir los productos:
                 var baseUrl_vista = "{{ url('/llamar_vista_pedir_cais') }}" + '/' + response.cliente_id + '/' + response.vehiculo_id ;
                 location.href = baseUrl_vista;
             },
             error:function (error) {
               console.log('entro a error...');
               console.log(error);
               // ('#send_form').html('Crear cliente');

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
