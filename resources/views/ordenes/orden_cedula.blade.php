@extends('layouts.app')

@section('css_js_datatables')
   <!-- funciones javascript propias:  -->
   <script src="{{ asset('js/ordenes.js') }}"></script>
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
   <div class="card-header tit" >
     Órden de servicio - Por cédula
   </div>

   {{-- <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
         <label>Número de documento:</label>
         <input type="text" class="form-control" style="height: 1.7em;"
            id="doc_num"
            name="doc_num"
            onblur="buscar_cliente('{{ url('/buscar_cliente') }}' , this.value)"
         >
      </div>
   </div> --}}
   <div class="container">
      <div class="row justify-content-center">
         <form id="form_pedir_cliente_orden_servicio" action="javascript:void(0);">
               <label>Número de documento del cliente:</label>
               <input type="text" class="form-control" style="height: 1.7em;"
                  id="doc_num"
                  {{-- name="php_doc_num" --}}
               >

               <br>
               <button
                 class="btn btn-primary"
                 type="button"
                 onclick="procesar_doc_num('{{ url('/buscar_doc_num') }}' , '{{ url('/editar_cliente_orden_servicio') }}');">
                    Buscar cliente
               </button>

         </form>



      </div>


   </div>

   {{-- formulario modal para preguntar si se debe crear
        o no un doc_num que no exista --}}
   <div class="modal" tabindex="-1" role="dialog" id="myModal">
     <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title">NÚMERO DE DOCUMENTO NO EXISTE</h5>
           {{-- botón X para cerrar: --}}
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">
           {{-- <p>Manifiesto que otorgo mi autorización expresa y clara para que ...... pueda hacer tratamiento y uso de mis datos personales, los cuales estarán reportados en la base de datos de la que es responsable dicha organización y que .................. </p> --}}
           <p><center>
             <b><span id="idpara_doc_num_noexiste"></span></b>&nbsp;&nbsp;&nbsp; no existe en la base de datos.<br><br>
             ¿Desea crearlo?
           </center></p>
         </div>
         <div class="modal-footer">
           {{-- <button id="modalbot_si" type="button" class="btn btn-primary" data-dismiss="modal" onclick="grabar_firma('{{ url('grabar_firma') }}')">Si</button> --}}
           <button id="modalbot_si" type="button" class="btn btn-primary" data-dismiss="modal" onclick="location.href='{{ url('/crear_cliente_orden_servicio') }}' + '/' + document.getElementById('doc_num').value;">Si</button>
           <button id="modalbot_no" type="button" class="btn btn-primary" data-dismiss="modal" >No</button>
         </div>
       </div>
     </div>
   </div>
   {{-- fin del formulario modal para pedir la firma del cliente --}}


@endsection()

@section('javascript_jquery_validation')

@endsection()

@section('javascript_funciones')
   <script>



      function procesar_doc_num(ruta_buscar , ruta_editar_cliente ){
         // parámetros recibidos:
         //    ruta_buscar           OrdenController@buscar_doc_num
         //    ruta_editar_cliente   OrdenController@editar_cliente_orden_servicio
         var doc_num = document.getElementById("doc_num").value ;
         if(!doc_num){
            alert("Debe digitar un número de documento.");
         }else if((isNaN(doc_num))){
            alert("Solamente puede digitar números.");
         }else if(doc_num.indexOf('.') >= 0){
            alert("No se pueden digitar puntos.");
         }else{
            // determinar si el doc num existe en la tabla clientes:
            $.ajax({
               url: ruta_buscar + '/' + doc_num,
               type: "GET",
               success: function(data){
                  if(!data){
                     // el doc_num no existe en la tabla clientes:
                     document.getElementById("idpara_doc_num_noexiste").innerHTML = doc_num;
                     jQuery.noConflict();
                     $('#myModal').modal(); // lo que escoja el usuario
                                            // en este modal, determinará
                                            // hacia donde sigue el programa....
                  }else{
                     location.href = ruta_editar_cliente + '/' + doc_num;
                  }
               },
               failure: function(msgFail){
                  console.log('failure en el ajax generar pdf...')
                  console.log(msgFail);
               }
            });


         }
      }
   </script>
@endsection()
