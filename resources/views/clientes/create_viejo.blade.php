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
    Creación de un nuevo cliente
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

        <div class="form-group row required">
          <label for="clave" class="col-sm-2 col-form-label">Clave</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="clave" name="clave" value="{{old('clave')}}" autofocus>
          </div>
          <span class="text-danger">{{ $errors->first('clave') }}</span>
        </div>

        <div class="form-group row">
          <label for="nombre" class="col-sm-2 col-form-label">Nombre completo</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{old('nombre')}}">
          </div>
          <span class="text-danger">{{ $errors->first('nombre') }}</span>
        </div>

        <div class="form-group row">
          <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
          <div class="col-sm-10">
            <input type="date" class="form-control" id="fecha" name="fecha" value="{{old('fecha')}}">
          </div>
          <span class="text-danger">{{ $errors->first('fecha') }}</span>
        </div>

        <div class="form-group row">
          <label for="otros" class="col-sm-2 col-form-label">Otros</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="otros" name="otros" value="{{old('otros')}}">
          </div>
          <span class="text-danger">{{ $errors->first('otros') }}</span>
        </div>

        <div class="form-group row">
          <label for="direc1" class="col-sm-2 col-form-label">Dirección 1</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="direc1" name="direc1" value="{{old('direc1')}}">
          </div>
          <span class="text-danger">{{ $errors->first('direc1') }}</span>
        </div>

        <div class="form-group row">
          <label for="direc2" class="col-sm-2 col-form-label">Dirección 2</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="direc2" name="direc2" value="{{old('direc2')}}">
          </div>
          <span class="text-danger">{{ $errors->first('direc2') }}</span>
        </div>

        <div class="form-group row">
          <label for="direc3" class="col-sm-2 col-form-label">Dirección 3</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="direc3" name="direc3" value="{{old('direc3')}}">
          </div>
          <span class="text-danger">{{ $errors->first('direc3') }}</span>
        </div>

        <div class="form-group row">
          <label for="documento" class="col-sm-2 col-form-label">Documento</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="documento" name="documento" value="{{old('documento')}}">
          </div>
          <span class="text-danger">{{ $errors->first('documento') }}</span>
        </div>

        <div class="form-group row">
          <label for="tlf1" class="col-sm-2 col-form-label">Teléfono 1</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="tlf1" name="tlf1" value="{{old('tlf1')}}">
          </div>
          <span class="text-danger">{{ $errors->first('tlf1') }}</span>
        </div>

        <div class="form-group row">
          <label for="tlf2" class="col-sm-2 col-form-label">Teléfono 2</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="tlf2" name="tlf2" value="{{old('tlf2')}}">
          </div>
          <span class="text-danger">{{ $errors->first('tlf2') }}</span>
        </div>

        <div class="form-group row">
          <label for="observa" class="col-sm-2 col-form-label">Observaciones</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="observa" name="observa" value="{{old('observa')}}">
          </div>
          <span class="text-danger">{{ $errors->first('observa') }}</span>
        </div>

        <div class="form-group row">
          <label for="nro_dias" class="col-sm-2 col-form-label">Nro dias</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="nro_dias" name="nro_dias" value="{{old('nro_dias')}}"   >
          </div>
          <span class="text-danger">{{ $errors->first('nro_dias') }}</span>
        </div>

        <div class="form-group row">
          <label for="tipo_dir" class="col-sm-2 col-form-label">Tipo dir</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="tipo_dir" name="tipo_dir" value="{{old('tipo_dir')}}">
          </div>
          <span class="text-danger">{{ $errors->first('tipo_dir') }}</span>
        </div>

        <div class="form-group row">
          <label for="autorete" class="col-sm-2 col-form-label">Autoretenedor</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="autorete" name="autorete" value="{{old('autorete')}}">
          </div>
          <span class="text-danger">{{ $errors->first('autorete') }}</span>
        </div>

        <div class="form-group row">
          <label for="descuento" class="col-sm-2 col-form-label">Descuento</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="descuento" name="descuento" value="{{old('descuento')}}">
          </div>
          <span class="text-danger">{{ $errors->first('descuento') }}</span>
        </div>

        <div class="form-group row">
          <label for="estatus" class="col-sm-2 col-form-label">Status</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="estatus" name="estatus" value="{{old('estatus')}}">
          </div>
          <span class="text-danger">{{ $errors->first('estatus') }}</span>
        </div>

        <div class="form-group row">
          <label for="recargo" class="col-sm-2 col-form-label">Recargo</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="recargo" name="recargo" value="{{old('recargo')}}">
          </div>
          <span class="text-danger">{{ $errors->first('recargo') }}</span>
        </div>

        <div class="form-group row">
          <label for="modcli" class="col-sm-2 col-form-label">Mod cli</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="modcli" name="modcli" value="{{old('modcli')}}">
          </div>
          <span class="text-danger">{{ $errors->first('modcli') }}</span>
        </div>

        <div class="form-group row">
          <label for="regimen" class="col-sm-2 col-form-label">Régimen</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="regimen" name="regimen" value="{{old('regimen')}}">
          </div>
          <span class="text-danger">{{ $errors->first('regimen') }}</span>
        </div>

        <div class="form-group row">
          <label for="agenrete" class="col-sm-2 col-form-label">Agente retenedor</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="agenrete" name="agenrete" value="{{old('agenrete')}}">
          </div>
          <span class="text-danger">{{ $errors->first('agenrete') }}</span>
        </div>

        <div class="form-group row">
          <label for="tipo_id" class="col-sm-2 col-form-label">Tipo id</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="tipo_id" name="tipo_id" value="{{old('tipo_id')}}">
          </div>
          <span class="text-danger">{{ $errors->first('tipo_id') }}</span>
        </div>

        <div class="form-group row">
          <label for="tip_act" class="col-sm-2 col-form-label">Tip act</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="tip_act" name="tip_act" value="{{old('tip_act')}}">
          </div>
          <span class="text-danger">{{ $errors->first('tip_act') }}</span>
        </div>

        <div class="form-group row">
          <label for="direc5" class="col-sm-2 col-form-label">Direc 5</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="direc5" name="direc5" value="{{old('direc5')}}">
          </div>
          <span class="text-danger">{{ $errors->first('direc5') }}</span>
        </div>

        <div class="form-group row">
          <label for="s_area" class="col-sm-2 col-form-label">S area</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="s_area" name="s_area" value="{{old('s_area')}}">
          </div>
          <span class="text-danger">{{ $errors->first('s_area') }}</span>
        </div>

        <div class="form-group row">
          <label for="reciproca" class="col-sm-2 col-form-label">Reciproca</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="reciproca" name="reciproca" value="{{old('reciproca')}}">
          </div>
          <span class="text-danger">{{ $errors->first('reciproca') }}</span>
        </div>

        <div class="form-group row">
          <label for="act_des" class="col-sm-2 col-form-label">Act des</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="act_des" name="act_des" value="{{old('act_des')}}">
          </div>
          <span class="text-danger">{{ $errors->first('act_des') }}</span>
        </div>

        <div class="form-group row">
          <label for="exporta" class="col-sm-2 col-form-label">Exporta</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="exporta" name="exporta" value="{{old('exporta')}}">
          </div>
          <span class="text-danger">{{ $errors->first('exporta') }}</span>
        </div>

        <div class="form-group row">
          <label for="retener" class="col-sm-2 col-form-label">Retener</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="retener" name="retener" value="{{old('retener')}}">
          </div>
          <span class="text-danger">{{ $errors->first('retener') }}</span>
        </div>

        <div class="form-group row">
          <label for="rete_ica" class="col-sm-2 col-form-label">Rete ica</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="rete_ica" name="rete_ica" value="{{old('rete_ica')}}">
          </div>
          <span class="text-danger">{{ $errors->first('rete_ica') }}</span>
        </div>

        <div class="form-group row">
          <label for="correo" class="col-sm-2 col-form-label">Correo</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="correo" name="correo" value="{{old('correo')}}">
          </div>
          <span class="text-danger">{{ $errors->first('correo') }}</span>
        </div>

        <div class="form-group row">
          <label for="celular" class="col-sm-2 col-form-label">Celular</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="celular" name="celular" value="{{old('celular')}}">
          </div>
          <span class="text-danger">{{ $errors->first('celular') }}</span>
        </div>

        <div class="form-group row">
          <label for="tipo_guber" class="col-sm-2 col-form-label">Tipo guber</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="tipo_guber" name="tipo_guber" value="{{old('tipo_guber')}}">
          </div>
          <span class="text-danger">{{ $errors->first('tipo_guber') }}</span>
        </div>

        <div class="form-group row">
          <label for="foto" class="col-sm-2 col-form-label">Foto</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="foto" name="foto" value="{{old('foto')}}">
          </div>
          <span class="text-danger">{{ $errors->first('foto') }}</span>
        </div>

        <div class="form-group row">
          <label for="website" class="col-sm-2 col-form-label">Web site</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="website" name="website" value="{{old('website')}}">
          </div>
          <span class="text-danger">{{ $errors->first('website') }}</span>
        </div>

        <div class="form-group row">
          <label for="dpto" class="col-sm-2 col-form-label">Departamento</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="dpto" name="dpto" value="{{old('dpto')}}">
          </div>
          <span class="text-danger">{{ $errors->first('dpto') }}</span>
        </div>

        <div class="form-group row">
          <label for="digitoveri" class="col-sm-2 col-form-label">Dígito verificación</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="digitoveri" name="digitoveri" value="{{old('digitoveri')}}">
          </div>
          <span class="text-danger">{{ $errors->first('digitoveri') }}</span>
        </div>

        <div class="form-group row">
          <label for="apellido1" class="col-sm-2 col-form-label">Apellido 1</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="apellido1" name="apellido1" value="{{old('apellido1')}}">
          </div>
          <span class="text-danger">{{ $errors->first('apellido1') }}</span>
        </div>

        <div class="form-group row">
          <label for="apellido2" class="col-sm-2 col-form-label">Apellido 2</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="apellido2" name="apellido2" value="{{old('apellido2')}}">
          </div>
          <span class="text-danger">{{ $errors->first('apellido2') }}</span>
        </div>

        <div class="form-group row">
          <label for="nombre1" class="col-sm-2 col-form-label">Nombre 1</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="nombre1" name="nombre1" value="{{old('nombre1')}}">
          </div>
          <span class="text-danger">{{ $errors->first('nombre1') }}</span>
        </div>

        <div class="form-group row">
          <label for="nombre2" class="col-sm-2 col-form-label">Nombre 2</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="nombre2" name="nombre2" value="{{old('nombre2')}}">
          </div>
          <span class="text-danger">{{ $errors->first('nombre2') }}</span>
        </div>

        <div class="form-group row">
          <label for="persona_ju" class="col-sm-2 col-form-label">Persona jurídica</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="persona_ju" name="persona_ju" value="{{old('persona_ju')}}">
          </div>
          <span class="text-danger">{{ $errors->first('persona_ju') }}</span>
        </div>

        <div class="form-group row">
          <label for="cuenta_aho" class="col-sm-2 col-form-label">Cuentas ahorros</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="cuenta_aho" name="cuenta_aho" value="{{old('cuenta_aho')}}">
          </div>
          <span class="text-danger">{{ $errors->first('cuenta_aho') }}</span>
        </div>

        <div class="form-group row">
          <label for="banco_aho" class="col-sm-2 col-form-label">Banco ahorros</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="banco_aho" name="banco_aho" value="{{old('banco_aho')}}">
          </div>
          <span class="text-danger">{{ $errors->first('banco_aho') }}</span>
        </div>

        <div class="form-group row">
          <label for="estado" class="col-sm-2 col-form-label">Estado</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="estado" name="estado" value="{{old('estado')}}">
          </div>
          <span class="text-danger">{{ $errors->first('estado') }}</span>
        </div>

        <div class="form-group row">
          <label for="agen_cree" class="col-sm-2 col-form-label">Agente cree</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="agen_cree" name="agen_cree" value="{{old('agen_cree')}}">
          </div>
          <span class="text-danger">{{ $errors->first('agen_cree') }}</span>
        </div>

        <div class="form-group row">
          <label for="exen_cree" class="col-sm-2 col-form-label">Agente exen</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="exen_cree" name="exen_cree" value="{{old('exen_cree')}}">
          </div>
          <span class="text-danger">{{ $errors->first('exen_cree') }}</span>
        </div>

        <div class="form-group row">
          <label for="retene_cre" class="col-sm-2 col-form-label">Retenedor cree</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="retene_cre" name="retene_cre" value="{{old('retene_cre')}}">
          </div>
          <span class="text-danger">{{ $errors->first('retene_cre') }}</span>
        </div>

        <div class="form-group row">
          <label for="declarante" class="col-sm-2 col-form-label">Declarante</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="declarante" name="declarante" value="{{old('declarante')}}">
          </div>
          <span class="text-danger">{{ $errors->first('declarante') }}</span>
        </div>

        <div class="form-group row">
          <label for="importa" class="col-sm-2 col-form-label">Importador</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="importa" name="importa" value="{{old('importa')}}">
          </div>
          <span class="text-danger">{{ $errors->first('importa') }}</span>
        </div>

        <div class="form-group row">
          <label for="agerteica" class="col-sm-2 col-form-label">Agente ica</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="agerteica" name="agerteica" value="{{old('agerteica')}}">
          </div>
          <span class="text-danger">{{ $errors->first('agerteica') }}</span>
        </div>

        <div class="form-group row">
          <label for="agrteicav" class="col-sm-2 col-form-label">Agente rete ica</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="agrteicav" name="agrteicav" value="{{old('agrteicav')}}">
          </div>
          <span class="text-danger">{{ $errors->first('agrteicav') }}</span>
        </div>

        <div class="form-group row">
          <label for="bolagro" class="col-sm-2 col-form-label">Bolagro</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="bolagro" name="bolagro" value="{{old('bolagro')}}">
          </div>
          <span class="text-danger">{{ $errors->first('bolagro') }}</span>
        </div>

        <div class="form-group row">
          <label for="autoretica" class="col-sm-2 col-form-label">Auto rete ica</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="autoretica" name="autoretica" value="{{old('autoretica')}}">
          </div>
          <span class="text-danger">{{ $errors->first('autoretica') }}</span>
        </div>

        <button id="send_form_clientes" type="submit" class="btn btn-primary">Crear cliente</button>
        <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('clientes') }}'" >Cancelar</button>

      </form>
  </div>
</div>
<script>
   if ($("#form_crear_cliente").length > 0) {
    $("#form_crear_cliente").validate({
    rules: {
      clave: {
        required: true,
        maxlength: 5
      },
      nombre: {
        maxlength: 80
      },
      fecha: {
        dateISO: true
      },
      otros: {
        maxlength: 30
      },
      direc1: {
        maxlength: 25
      },
      direc2: {
        maxlength: 25
      },
      direc3: {
        maxlength: 25
      },
      documento: {
        maxlength: 25
      },
      tlf1: {
        maxlength: 15
      },
      tlf2: {
        maxlength: 15
      },
      observa: {
        maxlength: 60
      },
      nro_dias: {
        number: true
      },
      tipo_dir: {
        maxlength: 1
      },
      autorete: {
        maxlength: 1
      },
     descuento: {
        number: true
      },
      estatus: {
        maxlength: 2
      },
      recargo: {
        maxlength: 1
      },
      modcli: {
        maxlength: 1
      },
      regimen: {
        maxlength: 1
      },
      agenrete: {
        maxlength: 1
      },
      tipo_id: {
        maxlength: 2
      },
      tip_act: {
        maxlength: 3
      },
      direc5: {
        maxlength: 200
      },
      s_area: {
        maxlength: 6
      },
      reciproca: {
        maxlength: 1
      },
      act_des: {
        maxlength: 1
      },
      exporta: {
        maxlength: 1
      },
      retener: {
        maxlength: 1
      },
      rete_ica: {
        maxlength: 1
      },
      correo: {
        maxlength: 80
      },
      celular: {
        maxlength: 12
      },
      tipo_guber: {
        maxlength: 1
      },
      foto: {
        maxlength: 100
      },
      website: {
        maxlength: 100
      },
      dpto: {
        maxlength: 50
      },
     digitoveri: {
        number: true
      },
      apellido1: {
        maxlength: 20
      },
      apellido2: {
        maxlength: 20
      },
      nombre1: {
        maxlength: 20
      },
      nombre2: {
        maxlength: 20
      },
      persona_ju: {
        maxlength: 1
      },
      cuenta_aho: {
        maxlength: 20
      },
      banco_aho: {
        maxlength: 30
      },
      estado: {
        maxlength: 1
      },
      agen_cree: {
        maxlength: 1
      },
      exen_cree: {
        maxlength: 1
      },
      retene_cre: {
        maxlength: 1
      },
      declarante: {
        maxlength: 1
      },
      importa: {
        maxlength: 1
      },
      agerteica: {
        maxlength: 1
      },
      agrteicav: {
        maxlength: 1
      },
      bolagro: {
        maxlength: 1
      },
      autoretica: {
        maxlength: 1
      },
    },
    messages: {
      clave: {
        required: "Debe digitar la clave",
        maxlength: "La clave no puede tener más de 5 caracteres"
      },
      nombre: {
        maxlength: "El nombre completo no puede tener más de 80 caracteres"
      },
      fecha: {
        dateISO: "Debe digitar la fecha"
      },
      otros: {
        maxlength: "Otros no puede tener más de 30 caracteres"
      },
      direc1: {
        maxlength: "La dirección 1 no puede tener más de 25 caracteres"
      },
      direc2: {
        maxlength: "La dirección 2 no puede tener más de 25 caracteres"
      },
      direc3: {
        maxlength: "La dirección 3 no puede tener más de 25 caracteres"
      },
      documento: {
        maxlength: "El documento no puede tener más de 25 caracteres"
      },
      tlf1: {
        maxlength: "El teléfono 1 no puede tener más de 15 caracteres"
      },
      tlf2: {
        maxlength: "El teléfono 2 no puede tener más de 15 caracteres"
      },
      observa: {
        maxlength: "Las observaciones no puede tener más de 60 caracteres"
      },
      nro_dias: {
        number: "Debe digitar un número"
      },
      tipo_dir: {
        maxlength: "El tipo dir no puede tener más de 1 caracteres"
      },
      autorete: {
        maxlength: "El autoretenedor no puede tener más de 1 caracteres"
      },
      descuento: {
        number: "Debe digitar un número"
      },
      estatus: {
        maxlength: "El status no puede tener más de 2 caracteres"
      },
      recargo: {
        maxlength: "El recargo no puede tener más de 1 caracteres"
      },
      modcli: {
        maxlength: "El mod cli no puede tener más de 1 caracteres"
      },
      regimen: {
        maxlength: "El régimen no puede tener más de 1 caracteres"
      },
      agenrete: {
        maxlength: "El agente retenedor no puede tener más de 1 caracteres"
      },
      tipo_id: {
        maxlength: "El tipo id no puede tener más de 2 caracteres"
      },
      tip_act: {
        maxlength: "El tip act no puede tener más de 3 caracteres"
      },
      direc5: {
        maxlength: "La direc 5 no puede tener más de 200 caracteres"
      },
      s_area: {
        maxlength: "La s area no puede tener más de 6 caracteres"
      },
      reciproca: {
        maxlength: "La reciproca no puede tener más de 1 caracteres"
      },
      act_des: {
        maxlength: "El act des no puede tener más de 1 caracteres"
      },
      exporta: {
        maxlength: "La exporta no puede tener más de 1 caracteres"
      },
      retener: {
        maxlength: "El retener no puede tener más de 1 caracteres"
      },
      rete_ica: {
        maxlength: "El rete ica no puede tener más de 1 caracteres"
      },
      correo: {
        maxlength: "El correo no puede tener más de 80 caracteres"
      },
      celular: {
        maxlength: "El celular no puede tener más de 12 caracteres"
      },
      tipo_guber: {
        maxlength: "El tipo guber no puede tener más de 1 caracteres"
      },
      foto: {
        maxlength: "La foto no puede tener más de 100 caracteres"
      },
      website: {
        maxlength: "El web site no puede tener más de 100 caracteres"
      },
      dpto: {
        maxlength: "El departamento no puede tener más de 50 caracteres"
      },
      digitoveri: {
        number: "Debe digitar un número"
      },
      apellido1: {
        maxlength: "El apellido 1 no puede tener más de 20 caracteres"
      },
      apellido2: {
        maxlength: "El apellido 2 no puede tener más de 20 caracteres"
      },
      nombre1: {
        maxlength: "El nombre 1 no puede tener más de 20 caracteres"
      },
      nombre2: {
        maxlength: "El nombre 2 no puede tener más de 20 caracteres"
      },
      persona_ju: {
        maxlength: "La persona jurídica no puede tener más de 1 caracteres"
      },
      cuenta_aho: {
        maxlength: "La cuentas ahorros no puede tener más de 20 caracteres"
      },
      banco_aho: {
        maxlength: "El banco ahorros no puede tener más de 30 caracteres"
      },
      estado: {
        maxlength: "El estado no puede tener más de 1 caracteres"
      },
      agen_cree: {
        maxlength: "El agente cree no puede tener más de 1 caracteres"
      },
      exen_cree: {
        maxlength: "El agente exen no puede tener más de 1 caracteres"
      },
      retene_cre: {
        maxlength: "El retenedor cree no puede tener más de 1 caracteres"
      },
      declarante: {
        maxlength: "El declarante no puede tener más de 1 caracteres"
      },
      importa: {
        maxlength: "El importador no puede tener más de 1 caracteres"
      },
      agerteica: {
        maxlength: "El agente ica no puede tener más de 1 caracteres"
      },
      agrteicav: {
        maxlength: "El agente rete ica no puede tener más de 1 caracteres"
      },
      bolagro: {
        maxlength: "La bolagro no puede tener más de 1 caracteres"
      },
      autoretica: {
        maxlength: "El auto rete ica no puede tener más de 1 caracteres"
      },
    },
    submitHandler: function(form) {
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('#send_form').html('Agregando ...');
      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      baseUrl = "{{ url('clientes') }}";
      $.ajax({
        url:baseUrl,
        type: "POST",
        data: $('#form_crear_cliente').serialize(),
        success: function( response ) {
            $('#send_form_cliente').html('Crear cliente');
            $('#res_message').show();
            $('#res_message').html(response.msg);
            $('#msg_div').removeClass('d-none');
            document.getElementById("clave").focus();
            document.getElementById("form_crear_cliente").reset();
            $('#errores_servidor').addClass('d-none');
            window.scrollTo(0, 0);
            setTimeout(function(){
              $('#res_message').hide();
              $('#msg_div').hide();
            },10000);
        },
        error:function (error) {
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
          $('#errores_servidor').removeClass('d-none');
          document.getElementById("clave").focus();
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
