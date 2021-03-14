@extends('layouts.app')

@section('css_js_datatables')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
  <!-- styles propios para cruds:
          Para título de la ventana, mensajes de error, espacio superior,
          y que los campos obligatorios tengan el asterisco rojo    -->
  <link href="{{ asset('css/propios_cruds.css') }}" rel="stylesheet">

@endsection()

@section('content')
<div class="card uper">
  <div class="card-header tit">
    Creación de un nuevo vehículo
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
    <form id="form_crear_vehiculo" method="post" action="javascript:void(0)">
        @csrf
        <div class="form-group row required">
          <label for="placa" class="col-sm-2 col-form-label">Placa</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="placa" name="placa" value="{{old('placa')}}" autofocus>
          </div>
          <span class="text-danger">{{ $errors->first('placa') }}</span>
        </div>

        <div class="form-group row required">
          <label for="marca" class="col-sm-2 col-form-label">Marca</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="marca" name="marca" value="{{old('marca')}}">
          </div>
          <span class="text-danger">{{ $errors->first('marca') }}</span>
        </div>

        <div class="form-group row">
          <label for="modelo" class="col-sm-2 col-form-label">Modelo</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="modelo" name="modelo" value="{{old('modelo')}}">
          </div>
          <span class="text-danger">{{ $errors->first('modelo') }}</span>
        </div>
        ............(pedir cliente propietario).....<br>

        <div class="container">

            <h1>Laravel Bootstrap Datepicker</h1>

            <input class="date form-control" type="text">

        </div>

        <button id="send_form_vehiculo" type="submit" class="btn btn-primary">Crear vehículo</button>
        <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('vehiculos') }}'" >Cancelar</button>

      </form>
  </div>
</div>
<script>

$('.date').datepicker({

   format: 'mm-dd-yyyy'

 });
 
   if ($("#form_crear_vehiculo").length > 0) {
    $("#form_crear_vehiculo").validate({
    rules: {
      placa: {
        required: true,
        maxlength: 6
      },
      marca: {
        required: true,
        maxlength: 50
      },
      nombre: {
        number: true
      },
    },
    messages: {
      placa: {
        required: "Debe digitar una placa",
        maxlength: "La placa no puede tener más de 6 caracteres"
      },
      marca: {
        required: "Debe digitar una marca",
        maxlength: "La marca no puede tener más de 50 caracteres"
      },
      modelo: {
        number: "Debe digitar un número (año de cuatro cifras)"
      },
    },
    submitHandler: function(form) {
     // $.ajaxSetup({
     //      headers: {
     //          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     //      }
     //  });
      $('#send_form_vehiculo').html('Agregando ...');
      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      baseUrl = "{{ url('vehiculos') }}";
      $.ajax({
        url:baseUrl,
        type: "POST",
        data: $('#form_crear_vehiculo').serialize(),
        success: function( response ) {
            $('#send_form').html('Crear vehículo');
            $('#res_message').show();
            $('#res_message').html(response.msg);
            $('#msg_div').removeClass('d-none');
            document.getElementById("placa").focus();
            document.getElementById("form_crear_vehiculo").reset();
            $('#errores_servidor').addClass('d-none');
            window.scrollTo(0, 0);
            setTimeout(function(){
              $('#res_message').hide();
              $('#msg_div').hide();
            },10000);
        },
        error:function (error) {
console.log(error);
          $('#send_form').html('Crear vehículo');
          var html_error='No se pudo agregar el vehículo por las siguientes razones:<br /><ul>';
          var obj_response = JSON.parse(error.responseText);
          var obj_response_errors=obj_response.errors;
          Object.keys(obj_response_errors).forEach(function (key){
            html_error = html_error + '<li>' + obj_response_errors[key][0] + '</li>';
          });
          html_error = html_error + '</ul>';

          $('#res_message_servidor').show();
          $('#res_message_servidor').html(html_error);
          $('#errores_servidor').removeClass('d-none');
          document.getElementById("placa").focus();
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
      });
    }
  })
}
</script>
@endsection
