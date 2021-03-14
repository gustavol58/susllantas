@extends('layouts.app')

@section('css_js_datatables')
   <!-- styles propios para cruds:
           Para título de la ventana, mensajes de error, espacio superior,
           que los campos obligatorios tengan el asterisco rojo
           conversion de input text a mayúsculas
           y otros específicos para el formulario de clientes
           y medias querys para manejo en celus, tablets, etc...
    -->
   <link href="{{ asset('css/propios_cruds.css') }}" rel="stylesheet">

   {{-- para el jquery validation: --}}
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>
@endsection()

@section('content')

   {{-- formulario modal para pedir cambio de clave: --}}
   <form id="form_cambiar_clave"
        {{-- action="{{ url('/cambiar_clave') }}"  --}}
        action="javascript:void(0)"
        method="post">
     @csrf
     <div class="modal fade" id="modalClave" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
           <div class="modal-header">
             <h4 class="modal-title">Cambio de la clave de acceso</h4>
             <button type="button" class="close" data-dismiss="modal">&times;</button>
           </div>
           <div class="modal-body">
             <!-- notificación CORRECTO desde la validación del lado servidor: -->
             <div class="alert alert-success d-none" id="msg_success">
                  <span  id="span_msg_success"></span>
             </div>
             <!-- notificación ERROR desde la validación del lado servidor: -->
             <div class="alert alert-danger d-none" id="msg_error">
                  <span  id="span_msg_error"></span>
             </div>
             <!-- pide la fecha inicial:  -->
             <div class="row">
                {{-- <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 required"> --}}
                <div class="col-xs-12 col-sm-1 required">
                    &nbsp;
                </div>
                <div class="col-xs-12 col-sm-5 required">
                    <label>Clave actual:</label>
                </div>
                <div class="col-xs-12 col-sm-6 required">
                    <input type="password"
                       class="form-control"
                       id="txt_clave_actual"
                       name="php_clave_actual" >
                </div>
             </div>
             <br>
             <div class="row">
                <div class="col-xs-12 col-sm-1 required">
                    &nbsp;
                </div>
                <div class="col-xs-12 col-sm-5 required">
                    <label>Nueva clave:</label>
                </div>
                <div class="col-xs-12 col-sm-6 required">
                    <input type="password"
                       class="form-control"
                       id="txt_clave_nueva1"
                       name="php_clave_nueva1" >
                </div>
             </div>
             <br>
             <div class="row">
                <div class="col-xs-12 col-sm-1 required">
                    &nbsp;
                </div>
                <div class="col-xs-12 col-sm-5 required">
                    <label>Repita la nueva clave:</label>
                </div>
                <div class="col-xs-12 col-sm-6 required">
                    <input type="password"
                       class="form-control"
                       id="txt_clave_nueva2"
                       name="php_clave_nueva2" >
                </div>
             </div>
           </div>
           <div class="modal-footer">
             <button
                class="btn btn-primary"
                type="submit"  >Cambiar clave</button>
             <button
                type="button"
                class="btn btn-primary"
                data-dismiss="modal">Cancelar</button>
           </div>
        </div>
      </div>
     </div>      <!-- fin del modal  -->
   </form>   {{-- fin del form que permite cambio de clave --}}

@endsection()

@section('javascript_onReady')

   <script>
      $(document).ready(function(){
         jQuery.noConflict();
         $('#modalClave').modal();
      });
   </script>
@endsection()

@section('javascript_funciones')

@endsection()

@section('javascript_jquery_validation')
   <script>
     if($('#form_cambiar_clave').length > 0){
         $('#form_cambiar_clave').validate({
            rules: {
              php_clave_actual: {
                required: true,
                maxlength: 30
              },
              php_clave_nueva1: {
                required: true,
                maxlength: 30
              },
              php_clave_nueva2: {
                required: true,
                maxlength: 30,
                equalTo: "#txt_clave_nueva1"
              },
            },
            messages: {
              php_clave_actual: {
                required: "Debe digitar la clave actual",
                maxlength: "La clave actual no puede tener más de 30 caracteres"
              },
              php_clave_nueva1: {
                required: "Debe digitar la nueva clave",
                maxlength: "La nueva clave no puede tener más de 30 caracteres"
              },
              php_clave_nueva2: {
                required: "Debe repetir la nueva clave",
                maxlength: "La nueva clave no puede tener más de 30 caracteres",
                equalTo: "La nueva clave se debe digitar dos veces"
              },
            },
            submitHandler: function(form){
               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });
               baseUrl = "{{ url('/cambiar_clave') }}";
               $.ajax({
                 url:baseUrl,
                 type: "POST",
                 data: $('#form_cambiar_clave').serialize(),
                 success: function( response ) {
                     if(response.status){
                        // la clave fue cambiada exitosamente:
                        // document.getElementById("form_cambiar_clave").reset();
                        // $('#span_msg_success').show();
                        // $('#span_msg_success').html(response.msg);
                        // $('#msg_success').removeClass('d-none');
                        // $('#msg_error').addClass('d-none');
                        $('#modalClave').hide();
                        alert('La clave ha sido cambiada correctamente.');
                        location.href = "{{ url('/') }}";
                     }else{
                        $('#span_msg_error').show();
                        $('#span_msg_error').html(response.msg);
                        $('#msg_error').removeClass('d-none');
                        $('#msg_success').addClass('d-none');
                     }

                     document.getElementById("txt_clave_actual").focus();
                     window.scrollTo(0, 0);
                     // setTimeout(function(){
                     //    $('#msg_success').hide();
                     //    $('#span_msg_success').hide();
                     // },10000);
                 },
                 error:function (error) {
                   // console.log('entró a error del ajax...');
                   // console.log(error);
                   var html_error='No se pudo cambiar la clave por las siguientes razones:<br /><ul>';
                   var obj_response = JSON.parse(error.responseText);
                   var obj_response_errors=obj_response.errors;
                   Object.keys(obj_response_errors).forEach(function (key){
                     html_error = html_error + '<li>' + obj_response_errors[key][0] + '</li>';
                   });
                   html_error = html_error + '</ul>';

                   $('#span_msg_error').show();
                   $('#span_msg_error').html(html_error);
                   $('#msg_error').removeClass('d-none');
                   $('#msg_success').addClass('d-none');

                   document.getElementById("txt_clave_actual").focus();
                   window.scrollTo(0, 0);
                   // setTimeout(function(){
                   //    $('#msg_error').hide();
                   //    $('#span_msg_error').hide();
                   // },10000);
                 },
                 failure:function(msgfail){
                   console.log('entro al failure del ajax');
                   console.log(msgfail);
                   $('#errores_servidor').html('failure ajax...');
                 }
               });
            },
         });
     }   // fin del if length (no tiene else)
   </script>
@endsection()
