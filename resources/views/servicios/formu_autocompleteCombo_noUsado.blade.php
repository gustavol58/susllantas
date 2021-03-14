@extends('layouts.app')

@section('css_js_datatables')
  <!-- archivo de estilos propios, contiene:
      estilo para el titulo de la ventana
    -->
  <link href="{{ asset('css/propios_global.css') }}" rel="stylesheet">

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



@endsection()

@section('content')
  <div id="app">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-8 tit">
          Registro de servicios
        </div>
      </div>
      <form action="{{ url('aaa/bbb') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row">
              <div class="col-12"><h2>Laravel 5.7 Auto Complete Search Using Jquery UI</h2></div>
              <div class="col-12">
                  <div id="custom-search-input">
                      <div class="input-group">
                          <input id="search" name="search" type="text" class="form-control" placeholder="Search" />
                      </div>
                  </div>
              </div>
          </div>
          {{-- botones  --}}
          <div class="row">
            <div class="col-sm-4">
              <a href="{{ url('productos/create')}}" class="btn btn-primary">Crear productos</a>
              <a href="{{ url('/')}}" class="btn btn-primary">Regresar al inicio</a>
            </div>
          </div>
      </form>
    </div>  {{-- fin de la clase container --}}
  </div>  {{-- fin del div app --}}

  <script>
   $(document).ready(function() {
      $( "#search" ).autocomplete({

          source: function(request, response) {
              $.ajax({
              url: "{{url('autocomplete')}}",
              data: {
                      term : request.term
               },
              dataType: "json",
              success: function(data){
                 var resp = $.map(data,function(obj){
                      //console.log(obj.city_name);
                      return obj.nombre;
                 });

                 response(resp);
              }
          });
      },
      minLength: 1
   });
 });      // fin onready function

  </script>
@endsection
