{{-- recibe 2 parámetro:
   cliente  array de 30 columnas que corresponden a los
            campos de la tabla clientes del cliente escogido
            para ser modificado
   nombre_completo_cliente: nombre completo del cliente, para mostrar en
            algunos mensajes (y debe ser enviado a la vista parcial)
--}}

{{-- {{dd($cliente)}} --}}

@extends('layouts.appnolive')

@section('css_js_datatables')
   {{-- 11nov2019 comentariado por que ya está en layouts.app --}}
  {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

  <!-- CSFR token for ajax call -->
  <meta name="_token" content="{{ csrf_token() }}"/>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

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
<div class="card uper">
  <div class="card-header tit">
    Modificación de un cliente
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
    <form id="form_modificar_cliente" method="POST" action="javascript:void(0)">
        @csrf
        @method('PUT')

        @include('partials.form_edit_cliente')

      <br>
      <button id="send_form_modify_cliente" type="submit" class="btn btn-primary">Grabar cambios</button>
      <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('clientes') }}'" >Cancelar</button>

      </form>
  </div>
</div>
<script>
   $(document).ready(function(){
      llenar_select_doc_tipo_js('{{ url('llenar_select_doc_tipo') }}' , '{{$cliente->doc_tipo_id}}' );
      llenar_juridica_js('{{$cliente->juridica}}');
      llenar_declarante_js('{{$cliente->declarante}}');
      llenar_select_dpto_ciudad_y_codpostal_js('{{ url('llenar_select_dpto') }}' , '{{ url('llenar_select_ciudades') }}', '{{$cliente->cod_postal->cod_dpto}}' , '{{$cliente->cod_postal->cod_ciudad}}' , '{{$cliente->cod_postal->cod_postal}}' , '{{$cliente->cod_postal_escogido}}'  );
      var idgrupo_actual = '{{$cliente->cod_direccion->id}}' + '@' + '{{$cliente->cod_direccion->grupo}}';
      llenar_direccion_js(
         '{{ url('llenar_select_dir_ppal') }}' ,
         '{{$cliente->dir_id}}' ,
         '{{$cliente->dir_num_ppal}}' ,
         '{{$cliente->dir_num_casa}}' ,
         '{{$cliente->dir_adic}}' ,
         idgrupo_actual,
         '{{$cliente->cod_direccion->grupo}}' ,
         '{{$cliente->cod_direccion->nombre}}' ,
      );
      seleccionar_cumple_mes_js('{{$cliente->cumple_mes}}');
      activar_habeas_data_js('{{$cliente->habeas_data}}');
   });

   // Creación del objeto que permite escribir la firma y que será
   // utilizado por funciones de clientes.js
   var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
     backgroundColor: 'rgba(255, 255, 255, 0)',
     penColor: 'red',
   });

   // fragmento javascrit para grabar los cambios (submit del form):
   if ($("#form_modificar_cliente").length > 0) {
    $("#form_modificar_cliente").validate({
    rules: {
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
      $('#send_form_modify_cliente').html('Modificando ...');
      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      baseUrl = "{{ url('/clientes') }}";   // ClienteController@update
      // es llamado el método update() porque es un PUT
      // (si fuera POST sin parámetros llamaría al método store())

      $.ajax({
        url: baseUrl + '/' + {{$cliente->id}} ,
        type: "POST",    // El PUT fué puesto desde el <form>
        header:{
          'X-CSRF-TOKEN': token
        },
        data: $('#form_modificar_cliente').serialize(),
        success: function( response ) {
            // solamente redireccionar, no hay que limpiar formulario
            // ni nada adicional
            location.href='{{ url('/clientes') }}';
        },
        error:function (error) {
          $('#send_form_modify_cliente').html('Grabar cambios');
          var html_error='No se pudieron grabar los cambios por las siguientes razones:<br /><ul>';
          var obj_response = JSON.parse(error.responseText);
          var obj_response_errors=obj_response.errors;
          Object.keys(obj_response_errors).forEach(function (key){
            html_error = html_error + '<li>' + obj_response_errors[key][0] + '</li>';
          });
          html_error = html_error + '</ul>';

          $('#res_message_servidor').show();
          $('#res_message_servidor').html(html_error);
          $('#errores_servidor').removeClass('d-none');
          // document.getElementById("clave").focus();
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
     });    // fin del Ajax PUT
   }  // fin de la función submit handler, a ejecutar si la validación es correcta
 }) // fin de la validación y envío de los campos del formulario
} // fin del if: si hay campos llenos en el formulario (el cual no tiene else)
</script>
@endsection
