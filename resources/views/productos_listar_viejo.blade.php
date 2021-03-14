@extends('layouts.app')

@section('css_js_datatables')

  <!-- archivo de estilos propios, contiene:
    a) unos estilos para datatables:
          para buscar por cada columna de las datatables
          para que en la datatable no aparezca el SEARCH general
          para mostrar alineado a la izquierda el mensaje "cargando registros
    b) otros estilos para los scroll horizontales superiror e inferior
  -->
  <link href="{{ asset('css/propios_datatables.css') }}" rel="stylesheet">

  <!-- estilos y scripts para las datatables jquery, para el menú multinivel
        y para el menu tipo hamburguesa -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

  <!-- scripts y estilos para los botones generar pdf, excel, csv etc.... -->
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
  <link  href="https://cdn.datatables.net/buttons/1.6.0/css/buttons.dataTables.min.css" rel="stylesheet">

  <!-- scripts para botones de visibilizar o no columnas de datatables -->
  <script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script>

@endsection()

@section('content')
  <div id="app">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-8 tit">
          Listado de productos
        </div>
        <div class="col-sm-4">
          <a href="{{ url('productos/create')}}" class="btn btn-primary">Crear productos</a>
          <a href="{{ url('/')}}" class="btn btn-primary">Regresar al inicio</a>
        </div>
      </div>

      {{--
        para el manejo de los scroll horizontales superior e inferior
        (ver https://stackoverflow.com/questions/35147038/how-to-place-the-datatables-horizontal-scrollbar-on-top-of-the-table)
      --}}
      <div class="large-table-fake-top-scroll-container-3">
        {{-- 06oct2019 los siguientes 100 caracteres (que deben tener un
        font-size de 1.7em, ver css), hay que escribirlos en este div
        porque de no ser asi el scroll horizontal superior no permite desplazar
        hasta la última columna de la tabla.
        Adicionalmente, si algun dia a la tabla se le agregan columnas, habrá
        que modificar el número de 100. --}}
        <div style="">1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890</div>
        {{-- <div>&nbsp;</div>    <===  asi estaba en el original de la página web --}}
      </div>
      <div class="large-table-container-3">
        <table class="table table-bordered display"   id="laravel_datatable2" >
           <thead>
             <tr>
               <th> </th>
               <th> </th>
               <th>Código</th>
               <th>Nro parte</th>
               <th>Nombre</th>
               <th>Unidad</th>
               <th>Ubicación</th>
               <th>Precio A</th>
               <th>Precio B</th>
               <th>IVA</th>
               <th>Clase</th>
               <th>Aux 1</th>
               <th>Consu c</th>
               <th>IVA dif</th>
               <th>IVA difs</th>
               <th>Marca</th>
               <th>Id</th>
             </tr>
             <tr>
               <td> </td>
               <td> </td>
               <td>Código</td>
               <td>Nro parte</td>
               <td>Nombre</td>
               <td>Unidad</td>
               <td>Ubicación</td>
               <td>Precio A</td>
               <td>Precio B</td>
               <td>IVA</td>
               <td>Clase</td>
               <td>Aux 1</td>
               <td>Consu c</td>
               <td>IVA dif</td>
               <td>IVA difs</td>
               <td>Marca</td>
               <td>Id</td>
             </tr>
           </thead>
           <tfoot>
               <tr>
                 <th> </th>
                 <th> </th>
                 <th>Código</th>
                 <th>Nro parte</th>
                 <th>Nombre</th>
                 <th>Unidad</th>
                 <th>Ubicación</th>
                 <th>Precio A</th>
                 <th>Precio B</th>
                 <th>IVA</th>
                 <th>Clase</th>
                 <th>Aux 1</th>
                 <th>Consu c</th>
                 <th>IVA dif</th>
                 <th>IVA difs</th>
                 <th>Marca</th>
                 <th>Id</th>
               </tr>
           </tfoot>
        </table>
       </div>  {{-- fin del div para manejo de los scroll horizontales superior e inferior --}}
    </div>  {{-- fin de la clase container --}}
  </div>  {{-- fin del div app --}}

  <script>
    $(document).ready( function () {

      // función para el manejo de los scroll horizontales superior e inferior
      $(function() {
        var tableContainer = $(".large-table-container-3");
        var table = $(".large-table-container-3 table");
        var fakeContainer = $(".large-table-fake-top-scroll-container-3");
        var fakeDiv = $(".large-table-fake-top-scroll-container-3 div");

        var tableWidth = table.width();
        // por ensayo y error se obtuvo este porcentaje ya que la variable
        // tableWidth estaba dañando el scroll horizontal superior. Pero
        // este 135% se daña cuando cambia el tamaño de la pantalla!!!
        // fakeDiv.width('135%');
        fakeDiv.width(tableWidth);

        fakeContainer.scroll(function() {
          tableContainer.scrollLeft(fakeContainer.scrollLeft());
        });
      })

      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      baseUrlEditarVista = "{{ url('/productos') }}";

      // creación de la datatable:
      var table = $('#laravel_datatable2').DataTable({
        // para personalizar los mensaje de la navegación,
        // ver documentación en https://datatables.net/examples/basic_init/dom.html
        dom: 'lipt',

        // asignar orden por defecto:
        order: [[ 2, "asc" ] , [ 3, "asc"]],

        // este es el manejo CORRECTO de botones de exportar y visibilidad:
        // buttons: [
        //   {
        //       extend: 'colvis',
        //       text: 'Mostrar / Ocultar columnas',
        //   },
        //   {
        //     extend: 'collection',
        //     text: 'Exportar',
        //     buttons: [
        //         {
        //             extend: 'copyHtml5',
        //             text: 'Al portapapeles',
        //             exportOptions: {
        //                 columns: ':visible'
        //             }
        //         },
        //         {
        //             extend: 'excelHtml5',
        //             text: 'Excel',
        //             exportOptions: {
        //                 columns: ':visible'
        //             }
        //         },
        //         {
        //             extend: 'csvHtml5',
        //             text: 'Csv',
        //             exportOptions: {
        //                 columns: ':visible'
        //             }
        //         },
        //         {
        //             extend: 'pdfHtml5',
        //             text: 'Pdf',
        //             exportOptions: {
        //                 columns: ':visible'
        //             }
        //         },
        //     ]
        //   },
        //   {
        //       extend: 'print',
        //       text: 'Imprimir',
        //       exportOptions: {
        //           columns: ':visible'
        //       }
        //   },
        // ],

        // traducción al español:
        language: {
           "decimal":        "",
           "emptyTable":     "No se encontró información en la tabla",
           "info":           "Mostrando del _START_ hasta el _END_ de _TOTAL_ registros",
           "infoEmpty":      "Mostrando 0 hasta 0 of 0 registros",
           "infoFiltered":   " filtrados (_MAX_ registros totales) ",
           "infoPostFix":    "",
           "thousands":      ",",
           "lengthMenu":     "Paginar cada _MENU_ registros",
           "loadingRecords": "<div class='cargando_registros'><img style='width:100px; height:100px;' src = '{{ asset('img/cargando.gif') }}' />Cargando registros, un momento por favor ...</div>",
           "processing":     "Procesando...",
           "search":         "Buscar:",
           "zeroRecords":    "No se hallaron registros",
           "paginate": {
               "first":      "Primera",
               "last":       "Última",
               "next":       "Siguiente",
               "previous":   "Anterior"
           },
           "aria": {
               "sortAscending":  ": Ordenar en forma ascendente",
               "sortDescending": ": Ordenar en forma descendente"
           }
        },

        // otras configuraciones para el datatable
        processing: true,
        // serverSide se cambio a false para poder usar los botones de exportar info:
        // serverSide: true,
        serverSide: false,
        ajax: "{{ url('productos-listar-jquery') }}",
        columns: [
           // columna de acción editar:
           {
                orderable: false,
                mRender: function(data, type, row) {
                  return "<a  title='Editar' href='" + baseUrlEditarVista +  "/" + row['id'] + "/edit'><img id='ico_editar' alt='editar' height='45' src = '{{ asset('img/iconos/ico_editar.png') }}'/></a>";
                }
           },
           // columna de acción eliminar:
           {
                orderable: false,
                mRender: function(data, type, row) {
                  return "<a title='Eliminar' href='javascript:void(0);' id='btn-eliminar-producto' data-id=" + row['id'] + " ><img id='ico_editar' alt='editar' height='45' src = '{{ asset('img/iconos/ico_eliminar.png') }}'/></a>"
                }
           },
           { data: 'codigo'},
           { data: 'num_parte' },
           { data: 'nombre' },
           { data: 'unidad' },
           { data: 'ubicacion' },
           { data: 'precio_a' },
           { data: 'precio_b' },
           { data: 'iva' },
           { data: 'clase' },
           { data: 'aux_1' },
           { data: 'consu_c' },
           { data: 'iva_dif' },
           { data: 'iva_difs' },
           { data: 'marca' },
           { data: 'id', visible: false },
        ],
        //  para que el indicador SORT se coloque en
        // la primera fila (no en la segunda)
        orderCellsTop: true,
      });
      $('#laravel_datatable2').DataTable();    // ¿para qué es esta instrucción?
                                               // no se quita para evitar comportamientos
                                               // extraños en la datatable

      // Agrega un inputtext a cada columna (en el thead (segunda fila) - td )
      $('#laravel_datatable2 thead tr:eq(1) td').each( function () {
          var title = $(this).text();
          if(title.trim().length  == 0){
            // es una column action, no debe usar filtro:
          }else{
            $(this).html( '<input type="text" placeholder="Buscar '+title+'" class="column_search" />' );
          }
      } );

      // Cuando el usuario digita algo en un input textbox de búsqueda,
      // se ejecuta la siguiente función:
      $( '#laravel_datatable2 thead' ).on( 'keyup change clear', ".column_search",function () {
          // el :visIdx es para que no tenga en cuenta las columnas ocultas:
           table
             .column( $(this).parent().index()+':visIdx' )
             .search( this.value )
             .draw();
      } );

    });   // fin de la función onReady()

    // función a la que se llega cuando el usuario dá click en el botón
    // ELIMINAR REGISTRO y que se encarga de llamar el controlador@destroy
    // (method DELETE)
    $('body').on('click', '#btn-eliminar-producto', function () {
        // el data-id fué asignado en el ahref del datatable (segunda
        // columna)
        var producto_id = $(this).data("id");
        if(confirm("¿Está seguro de borrar este registro?")){
          $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
           });
           // si no se usa la siguiente instrucción, habrá problemas al subir al
           // hosting compartido.
           baseUrlEliminar = "{{ url('/productos') }}";
           $.ajax({
             url: baseUrlEliminar + '/' + producto_id ,
             type: "DELETE",
             success: function( response ) {
                 location.href='{{ url('/productos') }}';
             },
             error:function (error) {
               console.log(error);
               alert('no se pudo borrar el registro, mirar la consola');
             },
             failure:function(msgfail){
               console.log('entro al failure');
               console.log(msgfail);
               alert('failure al borrar registro, mirar la consola');
             }
           });
        }
    });
  </script>
@endsection
