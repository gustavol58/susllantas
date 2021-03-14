{{-- parcial para el nucleo del formulario editar vehiculo
Es llamada desde edit_uncliente.blade.php y desde edit_orden_servicio.blade.php
recibe un parámetro:
   origen:  puede ser "crud" o "orden_servicio"
--}}

{{-- fila para pedir marca, modelo, gama y fecha soat --}}
<div class="row espacio_filas" >
   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
   <label class="tam_peq_label">Marca:</label>
    <input type="text"
        class="form-control upperCase tam_peq"
        id="id_marca"
        name="marca"
        @if($origen == "crud")
          value="{{$vehiculo->marca}}"
        @endif
    >
    {{-- <span class="text-danger">{{ $errors->first('marca') }}</span> --}}
   </div>
   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
    <label class="tam_peq_label">Modelo:</label>
    <input type="text"
       class="form-control tam_peq"
        id="id_modelo"
        name="modelo"
        @if($origen == "crud")
          value="{{$vehiculo->modelo}}"
        @endif
    >
    <span class="text-danger">{{ $errors->first('modelo') }}</span>
   </div>
   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
    <label class="tam_peq_label">Gama</label>
    <input type="text"
       class="form-control upperCase tam_peq"
        id="id_gama"
        name="gama"
        @if($origen == "crud")
          value="{{$vehiculo->gama}}"
        @endif
    >
    {{-- <span class="text-danger">{{ $errors->first('gama') }}</span> --}}
   </div>
   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
   <label class="tam_peq_label">Fecha vencimiento SOAT:</label>
   <input
      id="id_fec_soat"
      name="fec_soat"
      @if($origen == "crud")
        value="{{$vehiculo->fec_soat}}"
      @endif
      type="text"
      class="form-control tam_peq"
      autocomplete = "off"
      placeholder = "aaaa-mm-dd"
   />
   {{-- <span class="text-danger">{{ $errors->first('fec_soat') }}</span> --}}
  </div>
</div>
{{-- fin de la fila  para pedir marca, modelo, gama y fecha soat --}}

 <br>
 {{-- fila para fechas de tecnomecánica y extintor, kilometraje y kms aceite --}}
 <div class="row espacio_filas" >

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
  <label class="tam_peq_label">Fecha tecno-mecánica:</label>
   <input
      type="text"
      class="form-control tam_peq"
       id="id_fec_tecno"
       name="fec_tecno"
       @if($origen == "crud")
         value="{{$vehiculo->fec_tecno}}"
       @endif
       autocomplete = "off"
       placeholder = "aaaa-mm-dd" >
   {{-- <span class="text-danger">{{ $errors->first('fec_tecno') }}</span> --}}
  </div>
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
   <label class="tam_peq_label">Fecha  vencimiento extintor:</label>
   <input
      type="text"
      class="form-control tam_peq"
       id="id_fec_extintor"
       name="fec_extintor"
       @if($origen == "crud")
         value="{{$vehiculo->fec_extintor}}"
       @endif
       autocomplete = "off"
       placeholder = "aaaa-mm-dd" >
   {{-- <span class="text-danger">{{ $errors->first('fec_extintor') }}</span> --}}
  </div>
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
   <label class="tam_peq_label">Kilometraje:</label>
   <input
      id="id_kilom"
      name="kilom"
      @if($origen == "crud")
        value="{{$vehiculo->kilom}}"
      @endif
      type="text"
      class="form-control tam_peq"
   />
   {{-- <span class="text-danger">{{ $errors->first('kilom') }}</span> --}}
  </div>
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
  <label class="tam_peq_label">Kms próximo cambio de aceite:</label>
   <input
      type="text"
      class="form-control tam_peq"
       id="id_kilom_aceite"
       name="kilom_aceite"
       @if($origen == "crud")
         value="{{$vehiculo->kilom_aceite}}"
       @endif
    />
   {{-- <span class="text-danger">{{ $errors->first('kilom_aceite') }}</span> --}}
  </div>
 </div>
 {{-- fin de la fila  fechas de tecnomecánica y extintor, kilometraje y kms aceite --}}
