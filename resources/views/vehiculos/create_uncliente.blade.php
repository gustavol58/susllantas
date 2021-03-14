@php
  // la url que usará el boton CANCELAR:
  $baseUrlRegresarVehi = url('/cliente/vehi') .  "/" . $cliente_id  ;
@endphp

@extends('layouts.app')

@section('css_js_datatables')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>


  {{-- 01dic2019
  para incluir el datapicker: --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
  <script src="{{ asset('js/bootstrap-datepicker.es.min.js') }}"></script>
  <link href="{{ asset('css/bootstrap-datepicker.standalone.css') }}" rel="stylesheet">

  <!-- styles propios para cruds:
        Para título de la ventana, mensajes de error, espacio superior,
        y que los campos obligatorios tengan el asterisco rojo    -->
   <link href="{{ asset('css/propios_cruds.css') }}" rel="stylesheet">

@endsection()

@section('content')
<div class="card uper" style="margin-top: 0 !important;">
  <div class="card-header tit">
    <center>Creación de un nuevo vehículo para el cliente {{$cliente_nombre}}</center>
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

        {{-- fila para pedir placa , marca, modelo y gama --}}
        <div class="row" >
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Placa:</label>
            <input type="text" class="form-control upperCase"
                id="placa"
                name="placa"
                value="{{old('placa')}}" >
            <span class="text-danger">{{ $errors->first('placa') }}</span>
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
            <label>Modelo:</label>
            <input type="text" class="form-control"
                id="modelo"
                name="modelo"
                value="{{old('modelo')}}" >
            <span class="text-danger">{{ $errors->first('modelo') }}</span>
           </div>
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Gama</label>
            <input type="text" class="form-control upperCase"
                id="gama"
                name="gama"
                value="{{old('gama')}}" >
            <span class="text-danger">{{ $errors->first('gama') }}</span>
           </div>
        </div>
        {{-- fin de la fila  para pedir placa , marca, modelo y gama--}}

        <br>
        {{-- fila para fechas del soat, tecnomecánica y extintor --}}
        <div class="row" >
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Fecha vencimiento SOAT:</label>
            <input
               id="fec_soat"
               name="fec_soat"
               type="text"
               class="form-control"
               value="{{old('fec_soat')}}"
               placeholder = "aaaa-mm-dd"
            />
            <span class="text-danger">{{ $errors->first('fec_soat') }}</span>
           </div>
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
           <label>Fecha tecno-mecánica:</label>
            <input
               type="text"
               class="form-control"
                id="fec_tecno"
                name="fec_tecno"
                value="{{old('fec_tecno')}}"
                placeholder = "aaaa-mm-dd" >
            <span class="text-danger">{{ $errors->first('fec_tecno') }}</span>
           </div>
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Fecha  vencimiento extintor:</label>
            <input
               type="text"
               class="form-control"
                id="fec_extintor"
                name="fec_extintor"
                value="{{old('fec_extintor')}}"
                placeholder = "aaaa-mm-dd" >
            <span class="text-danger">{{ $errors->first('fec_extintor') }}</span>
           </div>
        </div>
        {{-- fin de la fila  fechas del soat, tecnomecánica y extintor --}}

        <br>
        {{-- fila para kilometraje y kilometros aceite --}}
        <div class="row" >
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
            <label>Kilometraje:</label>
            <input
               id="kilom"
               name="kilom"
               type="text"
               class="form-control"
               value="{{old('kilom')}}"
            />
            <span class="text-danger">{{ $errors->first('kilom') }}</span>
           </div>
           <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 required">
           <label>Kms próximo cambio de aceite:</label>
            <input
               type="text"
               class="form-control"
                id="kilom_aceite"
                name="kilom_aceite"
                value="{{old('kilom_aceite')}}"
             />
            <span class="text-danger">{{ $errors->first('kilom_aceite') }}</span>
           </div>
        </div>
        {{-- fin de la fila para kilometraje y kilometros aceite --}}

        <br>
        <button id="send_form_vehiculo" type="submit" class="btn btn-primary">Crear vehículo</button>
        <button type="reset" class="btn btn-primary" onclick="window.location='{{ url($baseUrlRegresarVehi) }}'" >Cancelar</button>
      </form>
  </div>
</div>
<script>
    $('#fec_soat').datepicker({
        format: "yyyy-mm-dd",
        language: 'es',
    });
    $('#fec_tecno').datepicker({
        format: "yyyy-mm-dd",
        language: 'es',
    });
    $('#fec_extintor').datepicker({
        format: "yyyy-mm-dd",
        language: 'es',
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
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('#send_form_vehiculo').html('Agregando ...');
      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      baseUrl = "{{ url('/cliente/vehi/crear') }}" + '/' + {{$cliente_id}} ;
      $.ajax({
        url:baseUrl,
        type: "POST",
        data: $('#form_crear_vehiculo').serialize(),
        success: function( response ) {
            $('#send_form_vehiculo').html('Crear vehículo');
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
