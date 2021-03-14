<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//    return view('welcome');
// });

// para que la url raiz vaya directamente al login
// tuve que ser comentariada porque daña el ruteo en un hosting compartido:
// Route::redirect('/', '/home');
Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/cambiar_clave' , 'HomeController@formu_cambiar_clave');
Route::post('/cambiar_clave' , 'HomeController@grabar_nueva_clave');


// Se decidió hacer el control auth desde el propio controlador Producto, por
// eso está comentariada esta linea de control por middleware:
// Route::get('/productos', 'ProductoController@index')->middleware( 'auth' );

// rutas para el crud de productos:
Route::resource('productos' , 'ProductoController');

// rutas para el crud de productos:
Route::resource('clientes' , 'ClienteController');

// rutas para el crud de vehículos:
Route::resource('vehiculos' , 'VehiculoController');

// Existen 2 url para usar datatables y mostrar el listado
// de productos:  la que lleva al método index() y es llamada
// desde Route::resource(), y la url jquery, es esta:
Route::get('/productos-listar-jquery', 'ProductoController@listarProductosJquery');

// Existen 2 url para usar datatables y mostrar el listado
// de clientes:  la que lleva al método index() y es llamada
// desde Route::resource(), y la url jquery, es esta:
Route::get('/clientes-listar-jquery', 'ClienteController@listarClientesJquery');

// Existen 2 url para usar datatables y mostrar el listado
// de vehiculos:  la que lleva al método index() y es llamada
// desde Route::resource(), y la url jquery, es esta:
Route::get('/vehiculos-listar-jquery', 'VehiculoController@listarVehiculosJquery');


// ******************************************************
//    Rutas para el manejo de vehículos de un solo cliente
// ******************************************************
// Cuando en el crud de clientes se escoge el botón para ver los
// vehículos de un cliente, se hace este llamado:
Route::get('/cliente/vehi/{cliente_id}', 'VehiculoController@listarVehiUnCliente');
// Existen 2 url para usar datatables y mostrar el listado
// de vehiculos de un solo cliente:  la que se acaba de hacer
// y la url jquery, es esta:
Route::get('/vehi-listar-uncliente-jquery/{cliente_id}', 'VehiculoController@listarVehiUnClienteJquery');

// creación de un vehículo para un cliente escogido (get y post):
Route::get('/cliente/vehi/crear/{cliente_id}/{cliente_nombre}', 'VehiculoController@createVehiUnCliente');
Route::post('/cliente/vehi/crear/{cliente_id}', 'VehiculoController@storeVehiUnCliente');

// modificación de un vehículo para un cliente escogido (get y post):
Route::get('/cliente/vehi/editar/{cliente_nombre}/{vehi_id}', 'VehiculoController@editVehiUnCliente');
Route::put('/cliente/vehi/editar/{vehi_id}', 'VehiculoController@updateVehiUnCliente');

// 03dic2019: no se usara a partir que se borre en la tabla menus
// Route::get('/servicios','ServicioController@index');

// input text box autocomplete clientes
// Route::get('search', 'ServicioController@autocompletarClientes');
Route::get('/autocomplete', 'ServicioController@buscarClientes');

// llenar el select de tipos documento:
Route::get('/llenar_select_doc_tipo', 'ClienteController@llenar_select_doc_tipo');

// llenar el select de dir ppal:
Route::get('/llenar_select_dir_ppal', 'ClienteController@llenar_select_dir_ppal');

// llenar el select de departamentos:
Route::get('/llenar_select_dpto', 'ClienteController@llenar_select_dpto');

// llenar el select de ciudades:
Route::get('/llenar_select_ciudades/{dpto_cod}', 'ClienteController@llenar_select_ciudades');

// llenar el select de ciudades:
Route::get('/obtener_cod_postal/{divipol}', 'ClienteController@obtener_cod_postal');

// llama el método que graba la firma en un archivo png con el nro
// de documento y la extensión png:
Route::post('/grabar_firma', 'ClienteController@grabar_firma');

// llama el método que modifica(sobreescribe) la firma en un archivo png con
// el id del cliente y la extensión png:
Route::post('/modificar_firma', 'ClienteController@modificar_firma');

// opción órdenes de servicio por placa, del menú principal:
Route::get('/orden_placa', 'OrdenController@index_placa');

// opción órdenes de servicio por cédula, del menú principal:
Route::get('/orden_cedula', 'OrdenController@index_cedula');

// opción órdenes de servicio por cédula, del menú principal:
// Route::get('/buscar_cliente/{cliente_id}', 'OrdenController@buscar_cliente');
// 21ene2020
Route::get('/buscar_doc_num/{doc_num}', 'OrdenController@buscar_doc_num');

// 20dic2019:
// Route::post('/buscar_cliente_orden_servicio', 'OrdenController@buscar_cliente_orden_servicio');

// llenar el select de placas para la orden de servicio:
Route::get('/llenar_select_placas_orden_servicio/{doc_num}', 'OrdenController@llenar_select_placas_orden_servicio');

// verificar que el cliente+vehiculo escogidos no tengan una
// orden de servicio abierta:
Route::get('/verificar_orden_servicio_abierta/{cliente_id}/{vehiculo_id}', 'OrdenController@verificar_orden_servicio_abierta');

// mostrar datos del vehiculo escogido en orden de servicio:
Route::get('/buscar_vehiculo_orden_servicio/{vehiculo_id}', 'OrdenController@buscar_vehiculo_orden_servicio');

// actualizar en las tablas clientes, vehículos y llamar la vista
// que pedirá el detalle de CAIS antes de generar la orden de servicio:
// NOTAS: 1) El primer parámetro no se llamó "cliente_id" para que no
//       hubiera inconsistencia con la ruta put clientes (en ella, que es
//       manejada automáticamente por laravel, el parámetro se llama cliente)
//       2) El segundo parámetro no se llamó "vehiculo_id" para que no
//       hubiera inconsistencia con la ruta put cliente/vehi/editar/{vehi_id}
//       (en ella, como se puede ver,  el parámetro se llama vehi_id)
Route::put('/modificar_tablas_pedir_cais/{cliente}/{vehi_id}' , 'OrdenController@modificar_tablas_pedir_cais');

// actualizar en la tabla clientes y agregar en la tabla vehiculos, y hacer lo
// mismo que el anterior put:
Route::put('/modificar_crear_tablas_pedir_cais/{cliente}/{placa}' , 'OrdenController@modificar_crear_tablas_pedir_cais');

// llamará la vista en donde se pedirán los productos:
Route::get('/llamar_vista_pedir_cais/{cliente_id}/{vehiculo_id}' , 'OrdenController@llamar_vista_pedir_cais');

// Para obtener los datos del producto escogido en formu_pedir_cais.blade.php:
Route::get('/leer_datos_producto_escogido/{producto_id}/{operario_id}' , 'OrdenController@leer_datos_producto_escogido');

Route::post('/guardar_servicios' , 'OrdenController@guardar_servicios');

// opcion para generar el pdf después de abrir una orden de servicio:
Route::post('/generar_pdf_orden_servicio', 'OrdenController@generar_pdf_orden_servicio');

// 22ene2019:
Route::get('/editar_cliente_orden_servicio/{doc_num}', 'OrdenController@editar_cliente_orden_servicio');
Route::get('/crear_cliente_orden_servicio/{doc_num}', 'OrdenController@crear_cliente_orden_servicio');
Route::post('/crear_tablas_pedir_cais', 'OrdenController@crear_tablas_pedir_cais');

// 30ene2020
Route::get('/autocompletar_productos/{digitado}' , 'OrdenController@autocompletar_productos');

// 11feb2020:
// para llamar la vista que mostrará el grid de órdenes abiertas y para actualizar
// en la b.d. la modificación de órdenes abiertas:
Route::get('/ordenes_abiertas_listar', 'OrdenController@listar_ordenes_abiertas');
Route::get('/ordenes_abiertas_listar_jquery', 'OrdenController@listar_ordenes_abiertas_jquery');
Route::get('/modificar_orden_abierta/{num_orden}/{nom_cliente}/{placa}' , 'OrdenController@modificar_orden_abierta');
Route::get('/modificar_orden_leer_cais/{num_orden}' , 'OrdenController@modificar_orden_leer_cais');
Route::post('/modificar_servicios' , 'OrdenController@modificar_servicios');

// 24feb2020:
// para llamar la vista que mostrará el grid de órdenes abiertas y permitirá
// cerrar cualquiera de ellas:
Route::get('/ordenes_cerrar_listar', 'OrdenController@listar_ordenes_cerrar');
// mostrará los cais de una orden que se escogio para ser cerrada:
Route::get('/cerrar_orden/{num_orden}/{nom_cliente}/{placa}' , 'OrdenController@cerrar_orden');
// modificará en la base de datos para cerrar la orden escogida:
Route::post('/cerrar_orden_bd' , 'OrdenController@cerrar_orden_bd');

Route::get('cache-clear' , function(){
    $exitCode = Artisan::call('cache:clear');
    echo "limpiada...";
});
