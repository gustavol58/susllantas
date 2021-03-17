<?php


// 14mar2021: antes , en laravel 6, los métodos se llamaban asi:
//      Route::get('/home', 'HomeController@index')->name('home');
// Pero ahora (laravel 7), para llamar una ruta de un controlador NOLIVEWIRE:
//      Route::get('/home', [HomeController::class, 'index'])->name('home');
// Y cuando el controlador sea livewire: 
//      Route::get('/clientes', ClienteLive::class);

    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\ClienteController;
    use App\Http\Controllers\ProductoController;
    use App\Http\Controllers\VehiculoController;
    use App\Http\Controllers\OrdenController;
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\Auth\ForgotPasswordController;
    use App\Http\Controllers\Auth\ResetPasswordController;
    use App\Http\Livewire\ClienteLive;


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
// Route::get('/', 'HomeController@index');

// llamar una ruta de un controlador NOLIVEWIRE, en laravel 7:
Route::get('/', [HomeController::class, 'index']);

// 14mar2021:
// Auth::routes();
// La lista completa de Auth::routes() se encuentra
// en /var/www/html/susllantas/vendor/laravel/ui/src/AuthRouteMethods.php
// enseguida solo se modificaron (por lo de livewire) los que aplican para sus llantas:
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [Controller::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// 14mar2021: antes , en laravel 6, los métodos se llamaban asi:
// Route::get('/home', 'HomeController@index')->name('home');
// Pero ahora, para llamar una ruta de un controlador NOLIVEWIRE, en laravel 7:
Route::get('/home', [HomeController::class, 'index'])->name('home');

// 14mar2021: antes , en laravel 6, los métodos se llamaban asi:
// Route::get('/home', 'HomeController@index')->name('home');
// Pero ahora, para llamar una ruta de un controlador NOLIVEWIRE, en laravel 7:
Route::get('/cambiar_clave', [HomeController::class, 'formu_cambiar_clave']);
Route::post('/cambiar_clave', [HomeController::class, 'grabar_nueva_clave']);

// Se decidió hacer el control auth desde el propio controlador Producto, por
// eso está comentariada esta linea de control por middleware:
// Route::get('/productos', 'ProductoController@index')->middleware( 'auth' );

// rutas para el crud de productos:
// 14mar2021:
// Route::resource('productos' , 'ProductoController');
Route::get('/productos', [ProductoController::class, 'index']);
Route::post('/productos', [ProductoController::class, 'store']);
Route::get('/productos/create', [ProductoController::class, 'create']);
Route::put('/productos/{producto}', [ProductoController::class, 'update']);
Route::get('/productos/{producto}', [ProductoController::class, 'show']);
Route::delete('/productos/{producto}', [ProductoController::class, 'destroy']);
Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit']);

// Existen 2 url para usar datatables y mostrar el listado
// de productos:  la que lleva al método index() y es llamada
// desde Route::get('/productos'), y la url jquery, es esta:
Route::get('/productos-listar-jquery', [ProductoController::class, 'listarProductosJquery']);

// rutas para el crud de clientes:
// 14mar2021: Es un componente livewire, se llama asi:
Route::get('/clientes', ClienteLive::class);
// los demás no son livewire: 
Route::post('/clientes', [ClienteController::class, 'store']);
Route::get('/clientes/create', [ClienteController::class, 'create']);
Route::put('/clientes/{producto}', [ClienteController::class, 'update']);
Route::get('/clientes/{producto}', [ClienteController::class, 'show']);
Route::delete('/clientes/{producto}', [ClienteController::class, 'destroy']);
Route::get('/clientes/{producto}/edit', [ClienteController::class, 'edit']);

Route::get('/clientes-listar-jquery', [ClienteController::class, 'listarClientesJquery']);

// rutas para el crud de vehículos:
Route::get('/vehiculos', [VehiculoController::class, 'index']);
Route::post('/vehiculos', [VehiculoController::class, 'store']);
Route::get('/vehiculos/create', [VehiculoController::class, 'create']);
Route::put('/vehiculos/{producto}', [VehiculoController::class, 'update']);
Route::get('/vehiculos/{producto}', [VehiculoController::class, 'show']);
Route::delete('/vehiculos/{producto}', [VehiculoController::class, 'destroy']);
Route::get('/vehiculos/{producto}/edit', [VehiculoController::class, 'edit']);

// Existen 2 url para usar datatables y mostrar el listado
// de clientes:  la que lleva al método index() y es llamada
// desde Route::resource(), y la url jquery, es esta:
Route::get('/clientes-listar-jquery', [ClienteController::class, 'listarClientesJquery']);

// Existen 2 url para usar datatables y mostrar el listado
// de vehiculos:  la que lleva al método index() y es llamada
// desde Route::resource(), y la url jquery, es esta:
Route::get('/vehiculos-listar-jquery', [VehiculoController::class, 'listarVehiculosJquery']);

// ******************************************************
//    Rutas para el manejo de vehículos de un solo cliente
// ******************************************************
// Cuando en el crud de clientes se escoge el botón para ver los
// vehículos de un cliente, se hace este llamado:
Route::get('/cliente/vehi/{cliente_id}', [VehiculoController::class , 'listarVehiUnCliente']);
// Existen 2 url para usar datatables y mostrar el listado
// de vehiculos de un solo cliente:  la que se acaba de hacer
// y la url jquery, es esta:
Route::get('/vehi-listar-uncliente-jquery/{cliente_id}', [VehiculoController::class , 'listarVehiUnClienteJquery']);

// creación de un vehículo para un cliente escogido (get y post):
Route::get('/cliente/vehi/crear/{cliente_id}/{cliente_nombre}', [VehiculoController::class , 'createVehiUnCliente']);
Route::post('/cliente/vehi/crear/{cliente_id}', [VehiculoController::class , 'storeVehiUnCliente']);

// modificación de un vehículo para un cliente escogido (get y post):
Route::get('/cliente/vehi/editar/{cliente_nombre}/{vehi_id}', [VehiculoController::class , 'editVehiUnCliente']);
Route::put('/cliente/vehi/editar/{vehi_id}', [VehiculoController::class , 'updateVehiUnCliente']);

// 03dic2019: no se usara a partir que se borre en la tabla menus
// Route::get('/servicios','ServicioController@index');

// input text box autocomplete clientes
// Route::get('search', 'ServicioController@autocompletarClientes');
Route::get('/autocomplete', [ServicioController::class , 'buscarClientes']);

// llenar el select de tipos documento:
Route::get('/llenar_select_doc_tipo', [ClienteController::class , 'llenar_select_doc_tipo']);

// llenar el select de dir ppal:
Route::get('/llenar_select_dir_ppal', [ClienteController::class , 'llenar_select_dir_ppal']);

// llenar el select de departamentos:
Route::get('/llenar_select_dpto', [ClienteController::class , 'llenar_select_dpto']);

// llenar el select de ciudades:
Route::get('/llenar_select_ciudades/{dpto_cod}', [ClienteController::class , 'llenar_select_ciudades']);

// llenar el select de ciudades:
Route::get('/obtener_cod_postal/{divipol}', [ClienteController::class , 'obtener_cod_postal']);

// llama el método que graba la firma en un archivo png con el nro
// de documento y la extensión png:
Route::post('/grabar_firma', [ClienteController::class , 'grabar_firma']);

// llama el método que modifica(sobreescribe) la firma en un archivo png con
// el id del cliente y la extensión png:
Route::post('/modificar_firma', [ClienteController::class , 'modificar_firma']);

// opción órdenes de servicio por placa, del menú principal:
Route::get('/orden_placa', [OrdenController::class , 'index_placa']);

// opción órdenes de servicio por cédula, del menú principal:
Route::get('/orden_cedula', [OrdenController::class , 'index_cedula']);

// opción órdenes de servicio por cédula, del menú principal:
// Route::get('/buscar_cliente/{cliente_id}', 'OrdenController@buscar_cliente');
// 21ene2020
Route::get('/buscar_doc_num/{doc_num}', [OrdenController::class , 'buscar_doc_num']);

// 20dic2019:
// Route::post('/buscar_cliente_orden_servicio', 'OrdenController@buscar_cliente_orden_servicio');

// llenar el select de placas para la orden de servicio:
Route::get('/llenar_select_placas_orden_servicio/{doc_num}', [OrdenController::class , 'llenar_select_placas_orden_servicio']);

// verificar que el cliente+vehiculo escogidos no tengan una
// orden de servicio abierta:
Route::get('/verificar_orden_servicio_abierta/{cliente_id}/{vehiculo_id}', [OrdenController::class , 'verificar_orden_servicio_abierta']);

// mostrar datos del vehiculo escogido en orden de servicio:
Route::get('/buscar_vehiculo_orden_servicio/{vehiculo_id}', [OrdenController::class , 'buscar_vehiculo_orden_servicio']);

// actualizar en las tablas clientes, vehículos y llamar la vista
// que pedirá el detalle de CAIS antes de generar la orden de servicio:
// NOTAS: 1) El primer parámetro no se llamó "cliente_id" para que no
//       hubiera inconsistencia con la ruta put clientes (en ella, que es
//       manejada automáticamente por laravel, el parámetro se llama cliente)
//       2) El segundo parámetro no se llamó "vehiculo_id" para que no
//       hubiera inconsistencia con la ruta put cliente/vehi/editar/{vehi_id}
//       (en ella, como se puede ver,  el parámetro se llama vehi_id)
Route::put('/modificar_tablas_pedir_cais/{cliente}/{vehi_id}', [OrdenController::class , 'modificar_tablas_pedir_cais']);

// actualizar en la tabla clientes y agregar en la tabla vehiculos, y hacer lo
// mismo que el anterior put:
Route::put('/modificar_crear_tablas_pedir_cais/{cliente}/{placa}', [OrdenController::class , 'modificar_crear_tablas_pedir_cais']);

// llamará la vista en donde se pedirán los productos:
Route::get('/llamar_vista_pedir_cais/{cliente_id}/{vehiculo_id}', [OrdenController::class , 'llamar_vista_pedir_cais']);

// Para obtener los datos del producto escogido en formu_pedir_cais.blade.php:
Route::get('/leer_datos_producto_escogido/{producto_id}/{operario_id}', [OrdenController::class , 'leer_datos_producto_escogido']);

Route::post('/guardar_servicios', [OrdenController::class , 'guardar_servicios']);

// opcion para generar el pdf después de abrir una orden de servicio:
Route::post('/generar_pdf_orden_servicio', [OrdenController::class , 'generar_pdf_orden_servicio']);

// 22ene2019:
Route::get('/editar_cliente_orden_servicio/{doc_num}', [OrdenController::class , 'editar_cliente_orden_servicio']);

Route::get('/crear_cliente_orden_servicio/{doc_num}', [OrdenController::class , 'crear_cliente_orden_servicio']);

Route::post('/crear_tablas_pedir_cais', [OrdenController::class , 'crear_tablas_pedir_cais']);

// 30ene2020
Route::get('/autocompletar_productos/{digitado}', [OrdenController::class , 'autocompletar_productos']);

// 11feb2020:
// para llamar la vista que mostrará el grid de órdenes abiertas y para actualizar
// en la b.d. la modificación de órdenes abiertas:
Route::get('/ordenes_abiertas_listar', [OrdenController::class , 'listar_ordenes_abiertas']);
Route::get('/ordenes_abiertas_listar_jquery', [OrdenController::class , 'listar_ordenes_abiertas_jquery']);
Route::get('/modificar_orden_abierta/{num_orden}/{nom_cliente}/{placa}', [OrdenController::class , 'modificar_orden_abierta']);
Route::get('/modificar_orden_leer_cais/{num_orden}', [OrdenController::class , 'modificar_orden_leer_cais']);
Route::post('/modificar_servicios', [OrdenController::class , 'modificar_servicios']);

// 24feb2020:
// para llamar la vista que mostrará el grid de órdenes abiertas y permitirá
// cerrar cualquiera de ellas:
Route::get('/ordenes_cerrar_listar', [OrdenController::class , 'listar_ordenes_cerrar']);

// mostrará los cais de una orden que se escogio para ser cerrada:
Route::get('/cerrar_orden/{num_orden}/{nom_cliente}/{placa}', [OrdenController::class , 'cerrar_orden']);
// modificará en la base de datos para cerrar la orden escogida:
Route::post('/cerrar_orden_bd', [OrdenController::class , 'cerrar_orden_bd']);

Route::get('cache-clear' , function(){
    $exitCode = Artisan::call('cache:clear');
    echo "limpiada...";
});
