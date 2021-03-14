@extends('layouts.app')

@section('css_js_datatables')
  <meta name="csrf-token2" content="{{ csrf_token() }}">
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
    Modificación de un vehículo
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
    <form id="form_modificar_vehiculo" method="POST" action="javascript:void(0)">
        @csrf
        @method('PUT')
        <div class="form-group row required">
          <label for="placa" class="col-sm-2 col-form-label">Placa</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="placa" name="placa" value="{{$vehiculo->placa}}" autofocus>
          </div>
          <span class="text-danger">{{ $errors->first('placa') }}</span>
        </div>

        <div class="form-group row required">
          <label for="marca" class="col-sm-2 col-form-label">Marca</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="marca" name="marca" value="{{$vehiculo->marca}}">
          </div>
          <span class="text-danger">{{ $errors->first('marca') }}</span>
        </div>

        <div class="form-group row">
          <label for="modelo" class="col-sm-2 col-form-label">Modelo</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="modelo" name="modelo" value="{{$vehiculo->modelo}}">
          </div>
          <span class="text-danger">{{ $errors->first('modelo') }}</span>
        </div>

        <button id="send_form_modify" type="submit" class="btn btn-primary">Grabar cambios</button>
        <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('vehiculos') }}'" >Cancelar</button>

      </form>
  </div>
</div>
<script>
   if ($("#form_modificar_vehiculo").length > 0) {
    $("#form_modificar_vehiculo").validate({
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
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token2"]').attr('content')
          }
      });
      $('#send_form_modify').html('Modificando ...');
      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      baseUrl = "{{ url('/vehiculos') }}";
      $.ajax({
        url: baseUrl + '/' + {{$vehiculo->id}} ,
        type: "POST",
        data: $('#form_modificar_vehiculo').serialize(),
        success: function( response ) {
            location.href='{{ url('/vehiculos') }}';
        },
        error:function (error) {
          $('#send_form_modify').html('Grabar cambios');
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
