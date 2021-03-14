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
    Modificación de un producto
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
    <form id="form_modificar_producto" method="POST" action="javascript:void(0)">
        @csrf
        @method('PUT')

        <div class="form-group row required">
          <label for="codigo" class="col-sm-2 col-form-label">Código</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="codigo" name="codigo" value="{{$producto->codigo}}" autofocus>
          </div>
          <span class="text-danger">{{ $errors->first('codigo') }}</span>
        </div>

        <div class="form-group row required">
          <label for="num_parte" class="col-sm-2 col-form-label">Número de parte</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="num_parte" name="num_parte" value="{{$producto->num_parte}}">
          </div>
          <span class="text-danger">{{ $errors->first('num_parte') }}</span>
        </div>

        <div class="form-group row required">
          <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{$producto->nombre}}">
          </div>
          <span class="text-danger">{{ $errors->first('nombre') }}</span>
        </div>

        <div class="form-group row">
          <label for="unidad" class="col-sm-2 col-form-label">Unidad</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="unidad" name="unidad" value="{{$producto->unidad}}">
          </div>
          <span class="text-danger">{{ $errors->first('unidad') }}</span>
        </div>

        <div class="form-group row required">
          <label for="ubicacion" class="col-sm-2 col-form-label">Ubicación</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="{{$producto->ubicacion}}">
          </div>
          <span class="text-danger">{{ $errors->first('ubicacion') }}</span>
        </div>

        <div class="form-group row required">
          <label for="precio_a" class="col-sm-2 col-form-label">Precio A</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="precio_a" name="precio_a" value="{{$producto->precio_a}}">
          </div>
          <span class="text-danger">{{ $errors->first('precio_a') }}</span>
        </div>

        <div class="form-group row required">
          <label for="precio_b" class="col-sm-2 col-form-label">Precio B</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="precio_b" name="precio_b" value="{{$producto->precio_b}}">
          </div>
          <span class="text-danger">{{ $errors->first('precio_b') }}</span>
        </div>

        <div class="form-group row required">
          <label for="iva" class="col-sm-2 col-form-label">Iva</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="iva" name="iva" value="{{$producto->iva}}">
          </div>
          <span class="text-danger">{{ $errors->first('iva') }}</span>
        </div>

        <div class="form-group row required">
          <label for="clase" class="col-sm-2 col-form-label">Clase</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="clase" name="clase" value="{{$producto->clase}}">
          </div>
          <span class="text-danger">{{ $errors->first('clase') }}</span>
        </div>

        <div class="form-group row">
          <label for="aux_1" class="col-sm-2 col-form-label">Aux 1</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="aux_1" name="aux_1" value="{{$producto->aux_1}}">
          </div>
          <span class="text-danger">{{ $errors->first('aux_1') }}</span>
        </div>

        <div class="form-group row">
          <label for="consu_c" class="col-sm-2 col-form-label">Consu C</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="consu_c" name="consu_c" value="{{$producto->consu_c}}">
          </div>
          <span class="text-danger">{{ $errors->first('consu_c') }}</span>
        </div>

        <div class="form-group row required">
          <label for="iva_dif" class="col-sm-2 col-form-label">Iva dif</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="iva_dif" name="iva_dif" value="{{$producto->iva_dif}}">
          </div>
          <span class="text-danger">{{ $errors->first('iva_dif') }}</span>
        </div>

        <div class="form-group row">
          <label for="iva_difs" class="col-sm-2 col-form-label">Iva difs</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="iva_difs" name="iva_difs" value="{{$producto->iva_difs}}">
          </div>
          <span class="text-danger">{{ $errors->first('iva_difs') }}</span>
        </div>

        <div class="form-group row">
          <label for="marca" class="col-sm-2 col-form-label">Marca</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="marca" name="marca" value="{{$producto->marca}}">
          </div>
          <span class="text-danger">{{ $errors->first('marca') }}</span>
        </div>

        <button id="send_form_modify" type="submit" class="btn btn-primary">Grabar cambios</button>
        <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('productos') }}'" >Cancelar</button>

      </form>
  </div>
</div>
<script>
   if ($("#form_modificar_producto").length > 0) {
    $("#form_modificar_producto").validate({
    rules: {
      codigo: {
        required: true,
        maxlength: 12
      },
      num_parte: {
        required: true,
        maxlength: 15
      },
      nombre: {
        required: true,
        maxlength: 40
      },
      unidad: {
        maxlength: 3
      },
      ubicacion: {
        required: true,
        maxlength: 6
      },
      precio_a: {
        required: true,
        number: true
      },
      precio_b: {
        required: true,
        number: true
      },
      iva: {
        required: true,
        maxlength: 1
      },
      clase: {
        required: true,
        maxlength: 1
      },
      aux_1: {
        maxlength: 20
      },
      consu_c: {
        maxlength: 1
      },
      iva_dif: {
        required: true,
        maxlength: 1
      },
      iva_difs: {
        maxlength: 1
      },
      marca: {
        maxlength: 5
      },
    },
    messages: {
      codigo: {
        required: "Debe digitar un código",
        maxlength: "El código no puede tener más de 12 caracteres"
      },
      num_parte: {
        required: "Debe digitar un número de parte",
        maxlength: "El número de parte no puede tener más de 15 caracteres"
      },
      nombre: {
        required: "Debe digitar un nombre",
        maxlength: "El nombre no puede tener más de 40 caracteres"
      },
      unidad: {
        required: "Debe digitar una unidad",
        maxlength: "La unidad no puede tener más de 3 caracteres"
      },
      ubicacion: {
        required: "Debe digitar una ubicació",
        maxlength: "La ubicació no puede tener más de 6 caracteres"
      },
      precio_a: {
        required: "Debe digitar el precio A",
      },
      precio_a: {
        required: "Debe digitar el precio B",
      },
      iva: {
        required: "Debe digitar el iva",
        maxlength: "El iva solo puede tener 1 caracter"
      },
      clase: {
        required: "Debe digitar la clase",
        maxlength: "La clase solo puede tener 1 caracter"
      },
      aux_1: {
        maxlength: "El Aux 1 no puede tener más de 20 caracteres"
      },
      consu_c: {
        maxlength: "El consu C solo puede tener 1 caracter"
      },
      iva_dif: {
        required: "Debe digitar el iva dif",
        maxlength: "El iva dif solo puede tener 1 caracter"
      },
      iva_difs: {
        maxlength: "El iva difs solo puede tener 1 caracter"
      },
      marca: {
        maxlength: "La marca solo puede tener 1 caracter"
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
      baseUrl = "{{ url('/productos') }}";
      $.ajax({
        url: baseUrl + '/' + {{$producto->id}} ,
        type: "POST",
        data: $('#form_modificar_producto').serialize(),
        success: function( response ) {
            location.href='{{ url('/productos') }}';
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
          document.getElementById("codigo").focus();
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
