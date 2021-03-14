{{-- parcial para el nucleo del formulario editar cliente
Es llamada desde edit.blade.php y desde edit_orden_servicio.blade.php
--}}

{{-- fila para pedir tipo docu, num docu, jurídica y declarante --}}
<div class="row espacio_filas" >
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required">
    <label class="tam_peq_label">Tipo de documento:</label>
    <select class="form-control tam_peq" id="selectDoc_tipo" name="doc_tipo" onchange="procesar_tipo_doc(this)">
      {{-- el combo es llenado desde javascript --}}
    </select>
    <span class="text-danger">{{ $errors->first('doc_tipo') }}</span>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required ">
    <label for="doc_num" class="tam_peq_label">Número de documento:</label>
    <div class="row">
        <div class="col-xs-9 col-sm-9 col-md-9 ">
            <input type="text" class="form-control tam_peq"
                id="doc_num"
                name="doc_num"
                onblur="determinar_dv(this.value)"
                value="{{$cliente->doc_num}}">
            <span class="text-danger">{{ $errors->first('doc_num') }}</span>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3">
          <input type="text" class="form-control  input_left tam_peq" id="doc_dv" name="doc_dv" readonly=true >
        </div>
    </div>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required ">
    <label class="tam_peq_label">Persona jurídica:</label>
    <select class="form-control tam_peq" id="juridica" name="juridica">
      <option value="S">SI</option>
      <option value="N">NO</option>
    </select>
    <span class="text-danger">{{ $errors->first('juridica') }}</span>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3  required">
    <label class="tam_peq_label">Declarante</label>
    <select class="form-control tam_peq" id="declarante" name="declarante">
      <option value="S">SI</option>
      <option value="N">NO</option>
    </select>
    <span class="text-danger">{{ $errors->first('declarante') }}</span>
  </div>

</div>
{{-- fin de la fila  para pedir tipo docu, num docu, jurídica y declarante --}}

<br>
{{-- fila para pedir nombres y apellidos --}}
<div class="row espacio_filas">
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
    <label class="tam_peq_label">Primer nombre:</label>
    <input type="text"
       class="form-control upperCase tam_peq"
       id="nombre1"
       name="nombre1"
       value="{{$cliente->nombre1}}">
    <span class="text-danger">{{ $errors->first('nombre1') }}</span>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 ">
    <label class="tam_peq_label">Segundo nombre:</label>
    <input type="text"
       class="form-control upperCase tam_peq"
       id="nombre2"
       name="nombre2"
       value="{{$cliente->nombre2}}">
    <span class="text-danger">{{ $errors->first('nombre2') }}</span>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 ">
    <label class="tam_peq_label">Primer apellido:</label>
    <input type="text"
       class="form-control upperCase tam_peq"
       id="apellido1"
       name="apellido1"
       value="{{$cliente->apellido1}}">
    <span class="text-danger">{{ $errors->first('apellido1') }}</span>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 ">
    <label class="tam_peq_label">Segundo apellido:</label>
    <input type="text"
       class="form-control upperCase tam_peq"
       id="apellido2"
       name="apellido2"
       value="{{$cliente->apellido2}}">
    <span class="text-danger">{{ $errors->first('apellido2') }}</span>
  </div>

</div>
{{-- fin de la fila  para pedir nombres y apellidos --}}

<br>
{{-- fila para pedir razón social y contacto  --}}
<div class="row espacio_filas">
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <label class="tam_peq_label">Razón social:</label>
    <input readonly=true
       type="text"
       class="form-control upperCase tam_peq"
       id="razon_social"
       name="razon_social"
       value="{{$cliente->razon_social}}">
    <span class="text-danger">{{ $errors->first('razon_social') }}</span>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <label class="tam_peq_label">Contacto:</label>
    <input type="text"
       class="form-control upperCase tam_peq"
       id="contacto"
       name="contacto"
       value="{{$cliente->contacto}}">
    <span class="text-danger">{{ $errors->first('contacto') }}</span>
  </div>

</div>
{{-- fin de la fila para pedir razón social y contacto  --}}

<br>
{{-- fila para pedir departamento y ciudad y mostrar cod postal --}}
<div class="row espacio_filas">
  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 required">
      <label class="tam_peq_label">Departamento:</label>
      <select class="form-control tam_peq" id="selectDpto" name="dpto" onchange="procesar_dpto(this , '{{ url('llenar_select_ciudades') }}')" >
        {{-- el combo es llenado desde javascript --}}
      </select>
      <span class="text-danger">{{ $errors->first('dpto') }}</span>
  </div>

  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 required">
      <label class="tam_peq_label">Ciudad:</label>
      <select class="form-control tam_peq" id="selectCiudad" name="ciudad" onchange="procesar_ciudad(this , '{{ url('obtener_cod_postal') }}')" >
        {{-- el combo es llenado desde javascript --}}
      </select>
      <span class="text-danger">{{ $errors->first('ciudad') }}</span>
  </div>

  {{-- <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <label class="tam_peq_label">Códigos postales:</label>
      <input type="text" class="form-control  input_left tam_peq" id="cod_postal" name="cod_postal" readonly=true >
      <span class="text-danger">{{ $errors->first('cod_postal') }}</span>
  </div> --}}

  {{-- 24ene2020 nuevo select codpostales --}}
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
     <label class="tam_peq_label">Códigos postales:</label>
     <select class="form-control tam_peq" id="selectCod_postal" name="cod_postal_escogido"  >
      {{-- el select es llenado desde javascript --}}
     </select>
     <span class="text-danger">{{ $errors->first('cod_postal_escogido') }}</span>
   </div>

</div>
{{-- fin de la fila para pedir departamento y ciudad --}}

<br>
{{-- fila para pedir dirección --}}
<div class="row espacio_filas">
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required">
    <label class="tam_peq_label">Dirección:</label>
    <select class="form-control tam_peq" id="selectDir_ppal" name="dir_ppal" onchange="pedir_dir_completa(this)" >
      {{-- el combo es llenado desde javascript --}}
    </select>
    <span class="text-danger">{{ $errors->first('dir_ppal') }}</span>
  </div>

  <div id="div_dir_num_ppal" style="display: none;" class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required">
    <label class="tam_peq_label" id="lbl_dir_num_ppal"></label>
    <input placeholder="Ejemplo: 40 SUR" type="text" class="form-control upperCase tam_peq" id="dir_num_ppal" name="dir_num_ppal" value="{{old('dir_num_ppal')}}">
    <span class="text-danger">{{ $errors->first('dir_num_ppal') }}</span>
  </div>

  <div id="div_dir_num_casa" style="display: none;" class="col-xs-12 col-sm-6 col-md-6 col-lg-3 required">
    <label class="tam_peq_label" id="lbl_dir_num_casa" style="white-space:nowrap;"></label>
    <input placeholder="Ejemplo: 25B - 32" type="text" class="form-control upperCase tam_peq" id="dir_num_casa" name="dir_num_casa" value="{{old('dir_num_casa')}}">
    <span class="text-danger">{{ $errors->first('dir_num_casa') }}</span>
  </div>

  <div id="div_dir_adic" style="display: none;" class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
    <label class="tam_peq_label" id="lbl_dir_adic"  style="white-space:nowrap;"></label>
    <input type="text" class="form-control upperCase tam_peq" id="dir_adic" name="dir_adic" value="{{old('dir_adic')}}">
    <span class="text-danger">{{ $errors->first('dir_adic') }}</span>
  </div>
</div>
{{-- fin de la fila para pedir dirección  --}}

<br>
{{-- fila  para pedir teléfonos, autorización y cumpleaños --}}
<div class="row espacio_filas">
 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
   <label class="tam_peq_label">Teléfono fijo:</label>
   <input type="text"
      class="form-control tam_peq"
      id="tel_fijo"
      name="tel_fijo"
      value="{{$cliente->tel_fijo}}">
   <span class="text-danger">{{ $errors->first('tel_fijo') }}</span>
 </div>

 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 ">
   <label class="tam_peq_label">Celular:</label>
   <input type="text"
      class="form-control tam_peq"
      id="tel_celu"
      name="tel_celu"
      value="{{$cliente->tel_celu}}">
   <span class="text-danger">{{ $errors->first('tel_celu') }}</span>
 </div>

 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3  required">
   <label class="tam_peq_label">Email:</label>
   <input type="text"
      class="form-control tam_peq"
      id="email"
      name="email"
      value="{{$cliente->email}}">
   <span class="text-danger">{{ $errors->first('email') }}</span>
 </div>

 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3  required">
   <label class="tam_peq_label">Fecha de cumpleaños:</label>
   <div class="row">
       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
           <input type="text" class="form-control tam_peq"
               id="cumple_dia"
               name="cumple_dia"
               placeholder="Día..."
               value="{{$cliente->cumple_dia}}">
           {{-- <span class="text-danger">{{ $errors->first('cumple_dia') }}</span> --}}
       </div>
       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
          <select class="form-control tam_peq" id="selectMes_cumple" name="cumple_mes" >
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
<div class="row espacio_filas">
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <center>
   <input type="checkbox"
         id="habeas_data"
         onclick="pedir_firma_digital(this ,  '{{ url('abrir_formu_firma') }}' )"
         value="1"
         name="habeas_data" > El cliente autoriza el tratamiento de sus datos personales
      </center>
   <span class="text-danger">{{ $errors->first('habeas_data') }}</span>
 </div>
</div>      {{-- fin de la fila  para pedir habeas data --}}


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
    <center>Última firma:
       <div class="wrapper">
          <img id="img_firma_actual" src="{{asset('img/habeas_data/'.$cliente->id.'.png')}}?' . filemtime({{asset('img/habeas_data/'.$cliente->id.'.png')}}) . '" width=400 height=200 />
       </div>
    </center>

    <center>Nueva firma:
       <div class="wrapper">
          <canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
       </div>
    </center>

    <div class="modal-footer">
      <button id="clear" type="button" class="btn btn-primary" onclick="limpiar_firma()">Limpiar</button>
      <button id="save" type="button" class="btn btn-primary" data-dismiss="modal" onclick="modificar_firma('{{ url('modificar_firma') }}' , '{{$cliente->id}}', '{{asset('img/habeas_data/'.$cliente->id.'.png')}}')">Grabar nueva firma</button>
      <button id="cancel" type="button" class="btn btn-primary" data-dismiss="modal" onclick="grabar_firma('{{ url('grabar_firma') }}')">Cancelar (no cambiar firma)</button>
    </div>
  </div>
</div>
</div>
{{-- fin del formulario modal para pedir la firma del cliente --}}
