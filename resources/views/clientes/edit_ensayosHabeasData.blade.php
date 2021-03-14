@extends('layouts.app')

@section('css_js_datatables')
  <meta name="csrf-token2" content="{{ csrf_token() }}">





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
  <link rel="stylesheet"
            href=
  "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
.input-icons i {
border: 1px solid yellow;
    position: absolute;
    text-align: right;
}

.input-icons {
/* border: 1px solid blue; */

    width: 100%;
    margin-bottom: 10px;
}

.icon {
   /* border: 1px solid red; */

    padding: 10px;
    min-width: 40px;
}

.input-field {
   border: 1px solid green;

    width: 100%;
    padding: 10px;
    text-align: center;
}
</style>
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

        {{-- fila para pedir tipo docu, num docu, jurídica y declarante --}}
        <div class="row" >
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required">
            <label>Tipo de documento:</label>
            <select class="form-control" id="selectDoc_tipo" name="doc_tipo" onchange="procesar_tipo_doc(this)">
              {{-- el combo es llenado desde javascript --}}
            </select>
            <span class="text-danger">{{ $errors->first('doc_tipo') }}</span>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required pad_sup_2">
            <label for="doc_num">Número de documento:</label>
            <div class="row">
                <div class="col-xs-9 col-sm-9 col-md-9 ">
                    <input type="text" class="form-control"
                        id="doc_num"
                        name="doc_num"
                        onblur="determinar_dv(this.value)"
                        value="{{$cliente->doc_num}}">
                    <span class="text-danger">{{ $errors->first('doc_num') }}</span>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                  <input type="text" class="form-control  input_left" id="doc_dv" name="doc_dv" readonly=true >
                </div>
            </div>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required pad_sup_3_4">
            <label>Persona jurídica:</label>
            <select class="form-control" id="juridica" name="juridica">
              <option value="S">SI</option>
              <option value="N">NO</option>
            </select>
            <span class="text-danger">{{ $errors->first('juridica') }}</span>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 pad_sup_3_4 required">
            <label>Declarante</label>
            <select class="form-control" id="declarante" name="declarante">
              <option value="S">SI</option>
              <option value="N">NO</option>
            </select>
            <span class="text-danger">{{ $errors->first('declarante') }}</span>
          </div>

        </div>
        {{-- fin de la fila  para pedir tipo docu, num docu, jurídica y declarante --}}

        <br>
        {{-- fila para pedir nombres y apellidos --}}
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <label>Primer nombre:</label>
            <input type="text"
               class="form-control upperCase"
               id="nombre1"
               name="nombre1"
               value="{{$cliente->nombre1}}">
            <span class="text-danger">{{ $errors->first('nombre1') }}</span>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 pad_sup_2">
            <label>Segundo nombre:</label>
            <input type="text"
               class="form-control upperCase"
               id="nombre2"
               name="nombre2"
               value="{{$cliente->nombre2}}">
            <span class="text-danger">{{ $errors->first('nombre2') }}</span>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 pad_sup_3_4">
            <label>Primer apellido:</label>
            <input type="text"
               class="form-control upperCase"
               id="apellido1"
               name="apellido1"
               value="{{$cliente->apellido1}}">
            <span class="text-danger">{{ $errors->first('apellido1') }}</span>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 pad_sup_3_4">
            <label>Segundo apellido:</label>
            <input type="text"
               class="form-control upperCase"
               id="apellido2"
               name="apellido2"
               value="{{$cliente->apellido2}}">
            <span class="text-danger">{{ $errors->first('apellido2') }}</span>
          </div>

        </div>
        {{-- fin de la fila  para pedir nombres y apellidos --}}

        <br>
        {{-- fila para pedir razón social y contacto  --}}
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <label>Razón social:</label>
            <input readonly=true
               type="text"
               class="form-control upperCase"
               id="razon_social"
               name="razon_social"
               value="{{$cliente->razon_social}}">
            <span class="text-danger">{{ $errors->first('razon_social') }}</span>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <label>Contacto:</label>
            <input type="text"
               class="form-control upperCase"
               id="contacto"
               name="contacto"
               value="{{$cliente->contacto}}">
            <span class="text-danger">{{ $errors->first('contacto') }}</span>
          </div>

        </div>
        {{-- fin de la fila para pedir razón social y contacto  --}}

        <br>
        {{-- fila para pedir departamento y ciudad y mostrar cod postal --}}
        <div class="row">
          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 required">
              <label>Departamento:</label>
              <select class="form-control" id="selectDpto" name="dpto" onchange="procesar_dpto(this , '{{ url('llenar_select_ciudades') }}')" >
                {{-- el combo es llenado desde javascript --}}
              </select>
              <span class="text-danger">{{ $errors->first('dpto') }}</span>
          </div>

          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 required">
              <label>Ciudad:</label>
              <select class="form-control" id="selectCiudad" name="ciudad" onchange="procesar_ciudad(this , '{{ url('obtener_cod_postal') }}')" >
                {{-- el combo es llenado desde javascript --}}
              </select>
              <span class="text-danger">{{ $errors->first('ciudad') }}</span>
          </div>

          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
              <label>Códigos postales:</label>
              <input type="text" class="form-control  input_left" id="cod_postal" name="cod_postal" readonly=true >
              <span class="text-danger">{{ $errors->first('cod_postal') }}</span>
          </div>

        </div>
        {{-- fin de la fila para pedir departamento y ciudad --}}

        <br>
        {{-- fila para pedir dirección --}}
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required">
            <label>Dirección:</label>
            <select class="form-control" id="selectDir_ppal" name="dir_ppal" onchange="pedir_dir_completa(this)" >
              {{-- el combo es llenado desde javascript --}}
            </select>
            <span class="text-danger">{{ $errors->first('dir_ppal') }}</span>
          </div>

          <div id="div_dir_num_ppal" style="display: none;" class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required">
            <label id="lbl_dir_num_ppal"></label>
            <input placeholder="Ejemplo: 40 SUR" type="text" class="form-control upperCase" id="dir_num_ppal" name="dir_num_ppal" value="{{old('dir_num_ppal')}}">
            <span class="text-danger">{{ $errors->first('dir_num_ppal') }}</span>
          </div>

          <div id="div_dir_num_casa" style="display: none;" class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required">
            <label id="lbl_dir_num_casa" style="white-space:nowrap;"></label>
            <input placeholder="Ejemplo: 25B - 32" type="text" class="form-control upperCase" id="dir_num_casa" name="dir_num_casa" value="{{old('dir_num_casa')}}">
            <span class="text-danger">{{ $errors->first('dir_num_casa') }}</span>
          </div>

          <div id="div_dir_adic" style="display: none;" class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <label id="lbl_dir_adic"  style="white-space:nowrap;"></label>
            <input type="text" class="form-control upperCase" id="dir_adic" name="dir_adic" value="{{old('dir_adic')}}">
            <span class="text-danger">{{ $errors->first('dir_adic') }}</span>
          </div>
        </div>
        {{-- fin de la fila para pedir dirección  --}}

        <br>
       {{-- fila  para pedir teléfonos, autorización y cumpleaños --}}
       <div class="row">
         <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
           <label>Teléfono fijo:</label>
           <input type="text"
              class="form-control"
              id="tel_fijo"
              name="tel_fijo"
              value="{{$cliente->tel_fijo}}">
           <span class="text-danger">{{ $errors->first('tel_fijo') }}</span>
         </div>

         <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 pad_sup_2">
           <label>Celular:</label>
           <input type="text"
              class="form-control"
              id="tel_celu"
              name="tel_celu"
              value="{{$cliente->tel_celu}}">
           <span class="text-danger">{{ $errors->first('tel_celu') }}</span>
         </div>

         <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 pad_sup_3_4 required">
           <label>Email:</label>
           <input type="text"
              class="form-control"
              id="email"
              name="email"
              value="{{$cliente->email}}">
           <span class="text-danger">{{ $errors->first('email') }}</span>
         </div>

         <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 pad_sup_3_4 required">
           <label>Fecha de cumpleaños:</label>
           <div class="row">
               <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                   <input type="text" class="form-control"
                       id="cumple_dia"
                       name="cumple_dia"
                       placeholder="Día..."
                       value="{{$cliente->cumple_dia}}">
                   <span class="text-danger">{{ $errors->first('cumple_dia') }}</span>
               </div>
               <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                  <select class="form-control" id="selectMes_cumple" name="cumple_mes" >
                    <option value="" selected disabled style="display: none;">MES...</option>
                    <option value="ENE">ENERO</option>
                    <option value="FEB">FEBRERO</option>
                    <option value="MAR">MARZO</option>
                    <option value="ABR">ABRIL</option>
                    <option value="MAY">MAYO</option>
                    <option value="JUN">JUNIO</option>
                    <option value="JUL">JULIO</option>
                    <option value="AGO">AGOSTO</option>
                    <option value="SEP">SEPTIEMBRE</option>
                    <option value="OCT">OCTUBRE</option>
                    <option value="NOV">NOVIEMBRE</option>
                    <option value="DIC">DICIEMBRE</option>
                  </select>
                  <span class="text-danger">{{ $errors->first('cumple_mes') }}</span>
               </div>
           </div>
         </div>
       </div>
       {{-- fin de la fila  para pedir teléfonos, email  y cumpleaños --}}

       <br>
      {{-- fila  para habeas data --}}
      <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<center>
   <button id="btnhabeas_data" class="btn btn-secondary" onclick="pedir_firma_digital(this)">

   </button>

</center>

         </div>
         {{-- <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <button>
               Cambiar...
            </button>
         </div> --}}
      </div>
      {{-- fin de la fila  para pedir habeas data --}}

      <div style="max-width:400px;margin:auto">
              <div class="input-icons">
                  <i class="fa fa-edit icon"></i>
                  <input class="input-field" type="text" value="siiiii">
                  {{-- <i class="fa fa-instagram icon"></i>
                  <input class="input-field" type="text">
                  <i class="fa fa-envelope icon"></i>
                  <input class="input-field" type="text">
                  <i class="fa fa-youtube icon"></i>
                  <input class="input-field" type="text">
                  <i class="fa fa-facebook icon"></i>
                  <input class="input-field" type="text"> --}}
              </div>
        </div>

      {{-- formulario modal para pedir la firma del cliente (se activará
      en clientes.js, función pedir_firma_digital()) --}}
      <div class="modal" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Habeas data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <p>Manifiesto que otorgo mi autorización expresa y clara para que ...... pueda hacer tratamiento y uso de mis datos personales, los cuales estarán reportados en la base de datos de la que es responsable dicha organización y que .................. </p>
            </div>
            <center>Firma actual:</center>
            <img src="{{asset('img/habeas_data/'.$cliente->id.'.png')}}" width=400 height=200 />

            <center>Nueva firma:</center>
            <div class="wrapper">
               <canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
            </div>
            <div class="modal-footer">
              <button id="clear" type="button" class="btn btn-primary" onclick="limpiar_firma()">Limpiar</button>
              <button id="save" type="button" class="btn btn-primary" data-dismiss="modal" onclick="grabar_firma('{{ url('grabar_firma') }}')">Grabar nueva firma</button>
              <button id="cancel" type="button" class="btn btn-primary" data-dismiss="modal" onclick="grabar_firma('{{ url('grabar_firma') }}')">Cancelar (no cambiar firma)</button>
            </div>
          </div>
        </div>
      </div>
      {{-- fin del formulario modal para pedir la firma del cliente --}}

      <br>

      <button id="send_form_modify_cliente" type="submit" class="btn btn-primary">Grabar cambios</button>
      <button type="reset" class="btn btn-primary" onclick="window.location='{{ url('clientes') }}'" >Cancelar</button>

      </form>
  </div>
</div>
<script>
   $(document).ready(function(){
      llenar_select_doc_tipo_js('{{ url('llenar_select_doc_tipo') }}' , '{{$cliente->doc_tipo_id}}');
      llenar_juridica('{{$cliente->juridica}}');
      llenar_declarante('{{$cliente->declarante}}');
      llenar_select_dpto_y_ciudad('{{ url('llenar_select_dpto') }}' , '{{ url('llenar_select_ciudades') }}', '{{$cliente->cod_postal->cod_dpto}}' , '{{$cliente->cod_postal->cod_ciudad}}' , '{{$cliente->cod_postal->cod_postal}}'  );
      var idgrupo_actual = '{{$cliente->cod_direccion->id}}' + '@' + '{{$cliente->cod_direccion->grupo}}';
      llenar_direccion(
         '{{ url('llenar_select_dir_ppal') }}' ,
         '{{$cliente->dir_id}}' ,
         '{{$cliente->dir_num_ppal}}' ,
         '{{$cliente->dir_num_casa}}' ,
         '{{$cliente->dir_adic}}' ,
         idgrupo_actual,
         '{{$cliente->cod_direccion->grupo}}' ,
         '{{$cliente->cod_direccion->nombre}}' ,
      );
      seleccionar_cumple_mes('{{$cliente->cumple_mes}}');
      mostrar_habeas_data('{{$cliente->habeas_data}}');
   });

   // Creación del objeto que permite escribir la firma y que será
   // utilizado por funciones de clientes.js
   var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
     backgroundColor: 'rgba(255, 255, 255, 0)',
     penColor: 'red',
   });

   if ($("#form_modificar_cliente").length > 0) {
    $("#form_modificar_cliente").validate({
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
              'X-CSRF-TOKEN': $('meta[name="csrf-token2"]').attr('content')
          }
      });
      $('#send_form_modify_cliente').html('Modificando ...');
      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      baseUrl = "{{ url('/clientes') }}";
      $.ajax({
        url: baseUrl + '/' + {{$cliente->id}} ,
        type: "POST",
        data: $('#form_modificar_cliente').serialize(),
        success: function( response ) {
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
