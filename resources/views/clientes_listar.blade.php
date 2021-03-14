@extends('layouts.app')

@section('css_js_datatables')

  <!-- archivo de estilos propios, contiene:
    a) unos estilos para datatables:
          para buscar por cada columna de las datatables
          para que en la datatable no aparezca el SEARCH general
          para mostrar alineado a la izquierda el mensaje "cargando registros"
    b) otros estilos para los scroll horizontales superiror e inferior
  -->
  <link href="{{ asset('css/propios_datatables.css') }}" rel="stylesheet">

  <!-- estilos y scripts para las datatables jquery, para el menú multinivel
        y para el menu tipo hamburguesa -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
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
  <div id="app2">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-8 tit">
          Listado de clientes
        </div>
        <div class="col-sm-4">
          <a href="{{ url('clientes/create')}}" class="btn btn-primary">Crear clientes</a>
          <a href="{{ url('/')}}" class="btn btn-primary">Regresar al inicio</a>
        </div>
      </div>

      {{--
        para el manejo de los scroll horizontales superior e inferior
        (ver https://stackoverflow.com/questions/35147038/how-to-place-the-datatables-horizontal-scrollbar-on-top-of-the-table)
      --}}
      <div class="large-table-fake-top-scroll-container-3">
        {{-- 06oct2019 los siguientes 200 caracteres (que deben tener un
        font-size de 1.7em, ver css), hay que escribirlos en este div
        porque de no ser asi el scroll horizontal superior no permite desplazar
        hasta la última columna de la tabla.
        Adicionalmente, si algun dia a la tabla se le agregan columnas, habrá
        que modificar el número de 100. --}}
        {{-- <div style="">1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890</div> --}}
        <div style="">12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890</div>
        {{-- <div>&nbsp;</div>    <===  asi estaba en el original de la página web --}}
      </div>
      <div class="large-table-container-3">
        <table class="table table-bordered display"   id="laravel_datatable_clientes" >
           <thead>
              <tr>
                <th> </th>
                <th> </th>
                <th> </th>
                <th>Origen</th>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Ciudad</th>
                <th>Dirección</th>
                <th>Tel fijo</th>
                <th>Celular</th>
                <th>email</th>
                <th>Fec ingreso</th>
                <th>Tipo doc.</th>
                <th>DV</th>
                <th>Per jurídica</th>
                <th>Declarante</th>
                <th>Estado</th>
                <th>Habeas data</th>
                <th>Cumple dia</th>
                <th>Cumple mes</th>
                <th>Contacto</th>
                <th>Departamento</th>
                <th>Clave</th>
                <th>Id</th>
              </tr>
              <tr>
                <td> </td>
                <td> </td>
                <td> </td>
                <td>Origen</td>
                <td>Documento</td>
                <td>Nombre</td>
                <td>Ciudad</td>
                <td>Dirección</td>
                <td>Tel fijo</td>
                <td>Celular</td>
                <td>email</td>
                <td>Fec ingreso</td>
                <td>Tipo doc.</td>
                <td>DV</td>
                <td>Per jurídica</td>
                <td>Declarante</td>
                <td>Estado</td>
                <td>Habeas data</td>
                <td>Cumple dia</td>
                <td>Cumple mes</td>
                <td>Contacto</td>
                <td>Departamento</td>
                <td>Clave</td>
                <td>Id</td>
              </tr>
           </thead>
           <tfoot>
               <tr>
                 <th> </th>
                 <th> </th>
                 <th> </th>
                 <th>Origen</th>
                 <th>Documento</th>
                 <th>Nombre</th>
                 <th>Ciudad</th>
                 <th>Dirección</th>
                 <th>Tel fijo</th>
                 <th>Celular</th>
                 <th>email</th>
                 <th>Fec ingreso</th>
                 <th>Tipo doc.</th>
                 <th>DV</th>
                 <th>Per jurídica</th>
                 <th>Declarante</th>
                 <th>Estado</th>
                 <th>Habeas data</th>
                 <th>Cumple dia</th>
                 <th>Cumple mes</th>
                 <th>Contacto</th>
                 <th>Departamento</th>
                 <th>Clave</th>
                 <th>Id</th>
               </tr>
           </tfoot>
        </table>
       </div>  {{-- fin del div para manejo de los scroll horizontales superior e inferior --}}
    </div>  {{-- fin de la clase container --}}
  </div>  {{-- fin del div app --}}
@endsection

@section('javascript_onReady')
  <script>
    $(document).ready( function () {

      // función para el manejo de los scroll horizontales superior e inferior
      $(function() {
        var tableContainer = $(".large-table-container-3");
        var table = $(".large-table-container-3 table");
        var fakeContainer = $(".large-table-fake-top-scroll-container-3");
        var fakeDiv = $(".large-table-fake-top-scroll-container-3 div");

        var tableWidth = table.width();
        fakeDiv.width(tableWidth);
        fakeContainer.scroll(function() {
          tableContainer.scrollLeft(fakeContainer.scrollLeft());
        });
      })

      // si no se usa la siguiente instrucción, habrá problemas al subir al
      // hosting compartido.
      // para llamar la vista que permite editar un cliente:
      baseUrlEditarVista = "{{ url('/clientes') }}";
      // para llamar el crud de vehículos de un cliente escogido:
      baseUrlVehiculosUnCliente = "{{ url('/cliente/vehi') }}";

      // creación de la datatable:
      var table = $('#laravel_datatable_clientes').DataTable({
        // para personalizar los mensaje de la navegación,
        // ver documentación en https://datatables.net/examples/basic_init/dom.html
        dom: 'lipt',

        // asignar orden por defecto:
        order: [[ 4, "asc" ]],

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

 // "deferLoading": 50,
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
        // 12nov2019:
        // tener en cuenta respecto al serverside:
        // serverSide alguna vez se cambio a false para poder usar
        // los botones de exportar info. Asi quedó funcionando
        // a pesar de que se demoraba mucho para mostrar los
        // registros al ser invocado el datatable.
        // Un tiempo después serverside fué puesto
        // en true y mostró los registros mucho más rápido
        // aunque al filtrar se demora mucho y además no
        // muestra el mensaje "Cargando..."
        // Habrá que decidir como dejarlo definitivamente....
        // 11feb2020: algo nuevo sobre serverSide:
        // cuando serverSide es: true, AL EXPORTAR NO SE EXPORTAN TODOS
        // LOS REGISTROS (sino únicamente los que están en la página actual)
        // serverSide: true,
        serverSide: false,
        ajax: "{{ url('clientes-listar-jquery') }}",
        columns: [
           // columna de acción ver vehículos de un cliente:
           {
                orderable: false,
                mRender: function(data, type, row) {
                  return "<a  title='Vehículos' href='" + baseUrlVehiculosUnCliente +  "/" + row['id'] + "'><img id='ico_vehiculo' alt='ver vehículos' height='45' src = '{{ asset('img/iconos/ico_vehiculo.png') }}'/></a>";
                }
           },
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
                  return "<a title='Eliminar' href='javascript:void(0);' id='btn-eliminar-cliente' data-id=" + row['id'] + " ><img id='ico_eliminar' alt='eliminar' height='45' src = '{{ asset('img/iconos/ico_eliminar.png') }}'/></a>"
                }
           },
           { data: 'origen'},
           { data: 'doc_num'},
           { data: 'nombre'},
           { data: 'ciudad'},
           { data: 'direccion'},
           { data: 'tel_fijo'},
           { data: 'tel_celu'},
           { data: 'email'},
           { data: 'fec_ingreso'},
           { data: 'doc_tipo_id', visible: false },
           { data: 'doc_dv', visible: false },
           { data: 'juridica', visible: false },
           { data: 'declarante', visible: false },
           { data: 'estado', visible: false },
           { data: 'habeas_data', visible: false },
           { data: 'cumple_dia', visible: false },
           { data: 'cumple_mes', visible: false },
           { data: 'contacto', visible: false },
           { data: 'departamento', visible: false },
           { data: 'clave', visible: false },
           { data: 'id', visible: false },
        ],
        //  para que el indicador SORT se coloque en
        // la primera fila (no en la segunda)
        orderCellsTop: true,
      });      // fin del var = laravel_datatable_clientes
      // $('#laravel_datatable_clientes').DataTable();    // ¿para qué es esta instrucción?
                                               // no se quita para evitar comportamientos
                                               // extraños en la datatable

      // Agrega un inputtext a cada columna (en el thead (segunda fila) - td )
      $('#laravel_datatable_clientes thead tr:eq(1) td').each( function () {
          var title = $(this).text();
          if(title.trim().length  == 0){
            // es una column action, no debe usar filtro
          }else{
            $(this).html( '<input type="text" placeholder="Buscar '+title+'" class="column_search" />' );
          }
      } );
      // Cuando el usuario digita algo en un input textbox de búsqueda,
      // se ejecuta la siguiente función:
      $( '#laravel_datatable_clientes thead' ).on( 'keyup change clear', ".column_search",function () {
            table
              .column( $(this).parent().index()+':visIdx' )
              .search( this.value )
              .draw();
       } );
    });   // fin de la función onReady()
   </script>
@endsection()

@section('javascript_funciones')
   <script>
    // función a la que se llega cuando el usuario dá click en el botón
    // ELIMINAR REGISTRO y que se encarga de llamar el controlador@destroy
    // (method DELETE)
    $('body').on('click', '#btn-eliminar-cliente', function () {
        // el data-id fué asignado en el ahref del datatable (segunda
        // columna)
        var cliente_id = $(this).data("id");
        if(confirm("¿Está seguro de borrar este registro?")){
          $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
           });
           // si no se usa la siguiente instrucción, habrá problemas al subir al
           // hosting compartido.
           baseUrlEliminar = "{{ url('/clientes') }}";
           $.ajax({
             url: baseUrlEliminar + '/' + cliente_id ,
             type: "DELETE",
             success: function( response ) {
                 location.href='{{ url('/clientes') }}';
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

    // 24oct2019
    // función a la que se llega cuando el usuario cambia la visibilidad
    // de alguna columna (evento column-visibility del jquery datatable)
    $('#laravel_datatable_clientes').on('column-visibility.dt' ,
      function(e, settings, column, state){
          $('#laravel_datatable_clientes thead tr:eq(1) td').each(
            function () {
              var title = $(this).text();
              if(title.trim().length  == 0){
                // es una column action, no debe usar filtro
              }else{
                $(this).html( '<input type="text" placeholder="Buscar '+title+'" class="column_search" />' );
              }
          } );
      });

  </script>
@endsection
