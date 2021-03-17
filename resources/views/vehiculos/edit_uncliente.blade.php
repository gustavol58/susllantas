@php
  // la url que usará el boton CANCELAR:
  $baseUrlRegresarVehi = url('/cliente/vehi') .  "/" . $vehiculo->cliente_id  ;
@endphp

@extends('layouts.appnolive')

@section('css_js_datatables')
  {{-- <meta name="csrf-token2" content="{{ csrf_token() }}"> --}}
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

  <!-- styles propios para cruds:
          Para título de la ventana, mensajes de error, espacio superior,
          y que los campos obligatorios tengan el asterisco rojo    -->
  <link href="{{ asset('css/propios_cruds.css') }}" rel="stylesheet">
@endsection()

@section('content')
<div class="card uper">
  <div class="card-header tit">
    Modificación de un vehículo del cliente {{$cliente_nombre}}
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

        {{-- fila para pedir placa  --}}
        <div class="row" >
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Placa:</label>
            <input type="text" class="form-control upperCase"
                id="placa"
                name="placa"
                value="{{$vehiculo->placa}}"
                autofocus >
            <span class="text-danger">{{ $errors->first('placa') }}</span>
           </div>
        </div>
        {{-- fin de la fila  para pedir placa --}}

        @include('partials.form_edit_vehiculo' , ['origen' => 'crud'])

          <br>

         <br>
        <button id="send_form_modify" type="submit" class="btn btn-primary">Grabar cambios</button>
        <button type="reset" class="btn btn-primary" onclick="window.location='{{ url($baseUrlRegresarVehi) }}'" >Cancelar</button>

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
        required: "Debe digitar una placa",
        maxlength: "La placa no puede tener más de 6 caracteres"
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
    submitHandler: function(form) {
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token2"]').attr('content')
          }
      });
      $('#send_form_modify').html('Modificando ...');
      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      baseUrl = "{{ url('/cliente/vehi/editar') }}" + '/' + {{$vehiculo->id}} ,
      $.ajax({
        url: baseUrl ,
        type: "PUT",
        data: $('#form_modificar_vehiculo').serialize(),
        success: function( response ) {
            location.href='{{ url($baseUrlRegresarVehi) }}';
        },
        error:function (error) {
          console.log(error);
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
