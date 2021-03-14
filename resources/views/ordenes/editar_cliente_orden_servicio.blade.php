{{-- recibe 2 parámetro:
   cliente  array de 30 columnas que corresponden a los
            campos de la tabla clientes
   nombre_completo_cliente: nombre completo del cliente, para mostrar en
            algunos mensajes
--}}
{{-- {{dd($cliente)}} --}}
@extends('layouts.app')

@section('css_js_datatables')
   {{-- 11nov2019 comentariado por que ya está en layouts.app --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSFR token for ajax call -->
  <meta name="_token" content="{{ csrf_token() }}"/>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

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

<style>




</style>

@endsection()

@section('content')

  <div class="card-header tit">
    Datos del cliente al que se le va a generar orden de servicio:
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
    <form id="form_orden_servicio" method="POST" action="javascript:void(0)">
        @csrf
        @method('PUT')

        @include('partials.form_edit_cliente')

        <br><br>

        {{-- fila  para pedir placa --}}
        <div class="row">
           <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
              <div class="card-header tit">
                {{-- <label class="tam_peq_label">&nbsp;</label> --}}
                <select class="form-control tam_peq"
                   style = "margin-bottom: -1px !important; margin-top: 4%;"
                   id="selectPlaca"
                   name="idvehiculo"
                   onchange="procesar_placa_orden_servicio(
                     '{{ url('/verificar_orden_servicio_abierta') }}'  ,
                     '{{ url('/buscar_vehiculo_orden_servicio') }}' ,
                     '{{$cliente->id}}' ,
                     '{{$nombre_completo_cliente}}'

                   )">
                  {{-- el combo es llenado desde javascript --}}
                </select>
                {{-- <span class="text-danger">{{ $errors->first('doc_tipo') }}</span> --}}
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
               {{-- solo visible si se escoje CREAR VEHÍCULO en el combo de placas --}}
               <div id="idgrupo_placa" style="visibility: hidden">
               {{-- <div id="idnueva_placa" style="display: none"> --}}
                  <label class="tam_peq_label">Placa:</label>
                  <input type="text"
                    class="form-control upperCase tam_peq"
                    id="placa"
                    name="placa"
                  >
               </div>
            </div>
        </div>
        {{-- fin de la fila  para pedir placa --}}



        {{-- datos del vehículo para orden de servicio: --}}
        @include('partials.form_edit_vehiculo' , ['origen' => 'orden_servicio'])


      <br><br>
      <button id="idbtn_generar_orden_servicio" type="submit" class="btn btn-primary">Siguiente</button>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('/orden_cedula') }}'" >Cancelar</button>

      </form>
  </div>
@endsection

@section('javascript_onReady')
   <script>
      $('#id_fec_soat').datepicker({
         format: "yyyy-mm-dd",
         language: 'es',
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
         llenar_select_doc_tipo_js('{{ url('llenar_select_doc_tipo') }}' , '{{$cliente->doc_tipo_id}}');
         llenar_juridica_js('{{$cliente->juridica}}');
         llenar_declarante_js('{{$cliente->declarante}}');
         llenar_select_dpto_ciudad_y_codpostal_js('{{ url('llenar_select_dpto') }}' , '{{ url('llenar_select_ciudades') }}', '{{$cliente->cod_postal->cod_dpto}}' , '{{$cliente->cod_postal->cod_ciudad}}' , '{{$cliente->cod_postal->cod_postal}}' , '{{$cliente->cod_postal_escogido}}'  );
         llenar_select_placas_js( '{{$cliente->doc_num}}'   , '{{ url('/llenar_select_placas_orden_servicio') }}');
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
   </script>
@endsection

@section('javascript_jquery_validation')
   <script>
      // fragmento javascrit para grabar los cambios (submit del form):
      if ($("#form_orden_servicio").length > 0) {
      $("#form_orden_servicio").validate({
      rules: {
         placa:{
            // si el vehículo se va a crear debe estar llena para poder
            // continuar, si el vehículo se va a modificar, el campo estará
            // lleno con la placa escogida en el select, aunque su propiedad
            // visibility este ocultándolo en la ventana
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
         idvehiculo: {
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
         placa: {
            required: "Debe escribir la placa",
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
         idvehiculo: {
           required: "Debe escoger el vehículo",
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

         // obtener el vehiculo_id escogido en el select placa
         // o es 0 () la placa digitada para el nuevo vehículo:
         var objSelect = document.getElementById("selectPlaca");
         var selectedOption = objSelect.options[objSelect.selectedIndex];
         var vehiculo_id = selectedOption.value;
// alert(vehiculo_id);
         if(vehiculo_id == 0){
            // modificar cliente y CREAR  vehiculo:
            baseUrl = "{{ url('/modificar_crear_tablas_pedir_cais') }}";
            baseUrl_def = baseUrl + '/' + {{$cliente->id}} + '/' + document.getElementById("placa").value;
            // OrdenController@modificar_crear_tablas_pedir_cais
         }else{
            // modificar cliente y vehiculo:
            baseUrl = "{{ url('/modificar_tablas_pedir_cais') }}";
            baseUrl_def = baseUrl + '/' + {{$cliente->id}} + '/' + vehiculo_id;
            // OrdenController@modificar_tablas_pedir_cais
         }
// console.log(vehiculo_id);
// console.log(baseUrl_def);
// alert('revisar...');
         $.ajax({
           url: baseUrl_def ,
           type: "POST",    // El PUT fué puesto desde el <form>
           header:{
             'X-CSRF-TOKEN': token
           },
           data: $('#form_orden_servicio').serialize(),
           success: function( response ) {
               // llamará el controlador que a su vez renderizará
               // la vista encargada de pedir los productos:
               // Si vehiculo_id == 0 significa que acaba de ser
               // creado un nuevo vehículo, o sea que hay que enviar
               // el id del vehículo recientemente creado (no el cero):
               if(vehiculo_id == 0){
                  vehiculo_id = response.vehiculo_id;
               }
               var baseUrl_vista = "{{ url('/llamar_vista_pedir_cais') }}" + '/' + {{$cliente->id}} + '/' + vehiculo_id ;
               location.href = baseUrl_vista;
           },
           error:function (error) {
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
