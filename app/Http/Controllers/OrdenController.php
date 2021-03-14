<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Cod_direccion;
use App\Cod_postal;
use App\Vehiculo;
use App\Servicio;
use App\Servicios_detalle;
use App\Producto;
use App\User;
use PDF;
use Auth;
use DB;
use App\Http\Requests\formularioCliente;
use App\Http\Requests\formularioVehiculo;


class OrdenController extends Controller
{
   // constructor para evitar el ingreso de usuarios no logueados
   public function __construct(){
      $this->middleware('auth');
   }

   public function index_placa(){
      // llamado desde la opcion orden servicio->por placa
      echo "en placa...";
   }

   public function index_cedula(){
      // llamado desde la opcion orden servicio->por placa
      return view('ordenes.orden_cedula');
   }

   public function buscar_doc_num($doc_num){
      // 21ene2020
      // llamada get cuando el usuario digita el num docu a buscar
      // en la generación de una orden de servicio:
      $cliente = Cliente::where('doc_num' , $doc_num)->first();
      // $cliente tendra null si el cliente no Existe
      // en otro caso, tendrá información.
      if($cliente == null){
         $existe = false;
      }else{
         $existe = true;
      }
      return response()->json($existe);
   }

   // public function buscar_cliente_orden_servicio($doc_num){
   //    // llamada get cuando el usuario digita el num docu en
   //    // la generación de una orden de servicio y ese doc_num
   //    // existe en la tabla clientes:
   //    // en los parámetros del formulario llega el num docu
   //    // lee el parámetro:
   //    $doc_num = $request->input("php_doc_num");
   //    // se hace neceario el first() (en vez de toArray()) para que no
   //    // tenga problemas con partials.form_edit_cliente.blade.php
   //    $cliente = Cliente::where('doc_num' , $doc_num)->first();
   //
   //    $nombre_completo = $cliente['razon_social'].' '.$cliente['nombre1'].' '.$cliente['nombre2'].' '.$cliente['apellido1'].' '.$cliente['apellido2'];
   //    // 21ene2020
   //    // si el cliente existe, llama la edición de clientes
   //    // si el cliente no existe, llama el ingreso de clientes:
   //
   //    // llama la vista que mostrará todos los datos del cliente:
   //    return view('ordenes.edit_cliente_orden_servicio' ,
   //       [
   //          'cliente' => $cliente,
   //          'nombre_completo_cliente' => $nombre_completo,
   //       ]
   //    );
   // }

   public function editar_cliente_orden_servicio($doc_num){
      // 22ene2020
      // llamada get cuando el usuario digita el num docu en
      // la generación de una orden de servicio y ese doc_num
      // existe en la tabla clientes:
      // Recibe el número de documento digitado
      // se hace neceario el first() (en vez de toArray()) para que no
      // tenga problemas con partials.form_edit_cliente.blade.php
      $cliente = Cliente::where('doc_num' , $doc_num)->first();

      $nombre_completo = $cliente['razon_social'].' '.$cliente['nombre1'].' '.$cliente['nombre2'].' '.$cliente['apellido1'].' '.$cliente['apellido2'];

      // llama la vista que permitirá editar todos los datos del cliente:
      return view('ordenes.editar_cliente_orden_servicio' ,
         [
            'cliente' => $cliente,
            'nombre_completo_cliente' => $nombre_completo,
         ]
      );
   }

   public function crear_cliente_orden_servicio($doc_num){
      // 22ene2020
      // llamada get cuando el usuario desea crear un
      // nuevo cliente desde la ventana de crear orden
      // de servicio
      // Recibe el número de documento digitado

      // llama la vista que permitirá crear el cliente:
      return view('ordenes.crear_cliente_orden_servicio' ,
         [
            'doc_num' => $doc_num,
         ]
      );
   }



   public function llenar_select_placas_orden_servicio($doc_num){
      // recibe 1 parámetro:
      //    el número de documento digitado
      // lee las tablas correspondienten para llenar el combo:
      $placas = Cliente::select('vehiculos.id' , 'vehiculos.placa')->join('vehiculos' , 'vehiculos.cliente_id' , '=' ,'clientes.id')->where('doc_num' , $doc_num)->get()->toArray();
      // para devolver al ajax el json:
      return response()->json($placas);
   }

   public function buscar_vehiculo_orden_servicio($vehiculo_id){
         // 26dic2019
         // llamada via AJAX desde clientes.js (función procesar_placa_orden_servicio())
         // devuelve un json con los datos del vehículo escogido por el usuario:
         // lee las tablas correspondienten para llenar el combo:
         $vehiculo = Vehiculo::select('marca' , 'modelo' , 'gama' , 'fec_soat' , 'fec_tecno' , 'fec_extintor' , 'kilom' , 'kilom_aceite')->where('id' , $vehiculo_id)->get()->toArray();
         // para devolver al ajax el json:
         return response()->json($vehiculo);
   }

   public function verificar_orden_servicio_abierta($cliente_id , $vehiculo_id){
      $orden_abierta = Servicio::where([['cliente_id' , '=' , $cliente_id] , ['vehiculo_id' , '=' , $vehiculo_id] , ['abierta' , '=' , true]])->get()->toArray();
      return response()->json($orden_abierta);
   }

   public function modificar_tablas_pedir_cais($cliente_id ,
         $vehiculo_id ,
         formularioCliente $requestCliente ,
         formularioVehiculo $requestVehiculo)
   {
      // Función llamada con tipo  PUT (via AJAX) desde la
      // vista clientes/edit_orden_servicio.blade.php
      // Hace lo siguiente:
      // 1) Actualiza los datos de la tabla clientes, de acuerdo a los posibles
      // cambios que haya realizado el usuario en el formulario de crear orden
      // de servicio.
      // 2) Actualiza los datos de la tabla vehiculos, de acuerdo a los posibles
      // cambios que haya realizado el usuario en el mismo formulario.
      // 3) Llama la vista que pedirá el detalle de los CAIS para el
      // cliente + vehículo

     // si llega aquí es porque las validaciones de los formRequest
     // formularioCliente y formularioVehiculo fueron correctas.
     // Si hubiera sucedido lo contrario, regresaria al 'error' del ajax

     // Como la validación fue correcta actualizará en la b.d. y regresará
     // al 'success' del ajax:
     $data = request()->all();
// echo "<pre>";
// print_r($data);
// exit;
     // el proceso de actualización a la tabla clientes se hace con la función
     // llamada a continuación (esto se debe a que el proceso también es llamado
     // desde la función ClienteController@update):
     $objCompartido = new CompartidoController();
     $msgCliente = $objCompartido->actualizar_cliente($data , $cliente_id);

     // actualización de la tabla vehículos:
     $msgVehiculo = $objCompartido->actualizar_vehiculo($data , $vehiculo_id);

     $msgAux = "";
     $statusAux = true;
     $errorAux = "";
     if(strlen($msgCliente) !== 0
            && strlen($msgVehiculo) !== 0){
        $msgAux = $msgCliente . ". " . $msgVehiculo;
        $statusAux = true;
        $errorAux = "";
     }else{
        $msgAux = "No se pudieron actualizar los datos en clientes o vehiculos.";
        $statusAux = false;
        $errorAux = "No se pudieron actualizar los datos en clientes o vehiculos.";
     }

     $arr = array('msg' => $msgAux  , 'status' => $statusAux, 'error' => $errorAux);
     return Response()->json($arr);
   }

   public function modificar_crear_tablas_pedir_cais($cliente_id ,
         $placa ,
         formularioCliente $requestCliente ,
         formularioVehiculo $requestVehiculo)
   {
      // Función llamada con tipo  PUT (via AJAX) desde la
      // vista clientes/edit_orden_servicio.blade.php
      // Hace lo siguiente:
      // 1) Actualiza los datos de la tabla clientes, de acuerdo a los posibles
      // cambios que haya realizado el usuario en el formulario de crear orden
      // de servicio.
      // 2) Agrega un nuevo vehiculo a la tabla vehiculos
      // 3) Llama la vista que pedirá el detalle de los CAIS para el
      // cliente + vehículo

     // si llega aquí es porque las validaciones de los formRequest
     // formularioCliente y formularioVehiculo fueron correctas.
     // Si hubiera sucedido lo contrario, regresaria al 'error' del ajax

     // Como la validación fue correcta actualizará en la b.d. y regresará
     // al 'success' del ajax:
     $data = request()->all();
// echo "<pre>";
// print_r($data);
// exit;
     // el proceso de actualización a la tabla clientes se hace con la función
     // llamada a continuación (esto se debe a que el proceso también es llamado
     // desde la función ClienteController@update):
     $objCompartido = new CompartidoController();
     $msgCliente = $objCompartido->actualizar_cliente($data , $cliente_id);

     // creación del nuevo vehículo:
     $arr_vehiculo = $objCompartido->crear_vehiculo($data ,  $cliente_id);
// echo "<pre>";
// print_r($msgCliente);
// print_r($msgVehiculo);
// exit;
     $msgAux = "";
     $statusAux = true;
     $errorAux = "";
     if(strlen($msgCliente) !== 0
            && $arr_vehiculo["status"]){
        $msgAux = $msgCliente . ". Fue agregado el nuevo vehículo";
        $statusAux = true;
        $errorAux = "";
        $vehiculo_id = $arr_vehiculo["vehiculo_id"];
     }else{
        $msgAux = "No se pudieron actualizar los datos en clientes o crear el vehiculo.";
        $statusAux = false;
        $errorAux = "No se pudieron actualizar los datos en clientes o crear el vehículo.";
        $vehiculo_id = 0;
     }

     $arr = array('msg' => $msgAux  , 'status' => $statusAux, 'error' => $errorAux , 'vehiculo_id' => $vehiculo_id );
     return Response()->json($arr);
   }

   public function llamar_vista_pedir_cais($cliente_id , $vehiculo_id){
      // llamado desde la vista editar_cliente_orden_servicio.blade.php, luego de que
      // se actualizarron las tablas de clientes y vehículos.
      // Debe obtener la información para llenar los combos productos y
      // operarios que serán usados en el formulario modal que tiene la
      // vista que será llamada.
      // Recibe 2 parámetros: los id del cliente y vehículos escogidos
      // para hacerles una orden de servicio.
      // debe obtener el nombre del cliente y la placa para mostrarlos
      // en la vista que será llamada.

      $productos =  Producto::select(DB::raw('concat(nombre , " ( " , cai , " ) ") producto') , 'id')->orderby('nombre')->get()->toArray();
      $operarios = User::select('name' , 'id')->where('rol' , 'ope')->orderby('name')->get()->toArray();

      $coll_cliente_nombre = Cliente::select(DB::raw('concat(razon_social," ",nombre1," ",nombre2," ",apellido1," ",apellido2) nombre'))->where('id' , $cliente_id)->get();
      $cliente_nombre = $coll_cliente_nombre->toArray()[0]['nombre'];
      $arr_vehiculo = Vehiculo::select('placa')->where('id' , $vehiculo_id)->get()->toArray();
      $placa = $arr_vehiculo[0]['placa'];

      return view('ordenes.formu_pedir_cais',
         [
            'productos' => $productos,
            'operarios' => $operarios,
            'cliente_id' => $cliente_id,
            'vehiculo_id' => $vehiculo_id,
            'cliente_nombre' => $cliente_nombre,
            'placa' => $placa,
            'origen' => 'crear',
            'num_orden' => 0,
         ]
      );


   }

   public function leer_datos_producto_escogido($producto_id , $operario_id){
      // llamado desde la vista formu_pedir_cais.blade.php cuando el usuario
      // presiona el botón para agregar un producto a la tabla html5.
      // Recibe 2 parámetros:
      //    producto_id que contiene el idproducto escogido
      //    operario_id id del operario escogido (0 si no se escogió ninguno)
      // Se encarga de leer en la tabla de productos y devuelve al
      // ajax un json con estas columnas:
      //    id
      //    cai
      //    descripción
      //    valor unitario (precio de venta)
      //    operario_nombre
      $datos_producto = Producto::select('id','cai','nombre','precio')->where('id' , '=' , $producto_id)->get()->toArray();
      if($operario_id == 0){
         $datos_producto[0]["operario"] = "";
      }else{
         $operario_nombre = User::select('name as operario')->where('id' , '=' , $operario_id)->get()->toArray();
         $datos_producto[0]["operario"] = $operario_nombre[0]["operario"];
      }


      return response()->json($datos_producto);
   }


   public function guardar_servicios(Request $request){
// echo "<pre>";
// print_r($request);
// exit;
// return response()->json($request);
      // 08ene2020
      // llamada post, via ajax desde formu_pedi_cais.blade.
      // En el parámetro $request llega esta información desde javascript:
      //    _token
      //    cliente_id
      //    vehiculo_id
      //    arr_productos: que contiene 9 columnas, pero de las cuales
      //    solamente se usarán 4:
      //       0  producto_id
      //       1  operario_id
      //       2  cantidad
      //       5  adicionales al nombre del producto
      // Está función se encarga de grabar en las tablas servicios y
      // servicios_detalles y luego regresa al ajax del que fue llamado

      // recepción de las variables de los parámetros:
      // .... aqui vamos 1 pm .....  08 enero .....................
      $cliente_id = $request->input("cliente_id");
      $vehiculo_id = $request->input("vehiculo_id");
      $arr_productos = json_decode($request->input("arr_productos"));

      // Grabación en las tablas servicios y servicios_detalles:

      // campos que deben ser
      // obtenidos por programación:
      // hay que obtener al mismo tiempo (aunque en formatos distintos),
      // la fecha hora actual para la b.d. y para generar el pdf:
      $fec_hora_hoy = date("Y-m-d H:i:s");
      // $fec_entrada_pdf = date("Y-m-d");
      // $hor_entrada_pdf = date('h:i:s a');
      $user_id = Auth::user()->id;
      $id_servicio_agregado = DB::table('servicios')->insertGetId(
         [
            'cliente_id' => $cliente_id,
            'vehiculo_id' => $vehiculo_id,
            'user_id' => $user_id,
            'created_at' => $fec_hora_hoy,
            'abierta' => true,
         ]
      );   // fin del DB::table insert para servicios
      foreach($arr_productos as $fila){
         $operario_id = $fila[1];
         // quitarle el formato al valor unitario (precio);
         $precio = str_replace("," , "" , $fila[7]);

         DB::table('servicios_detalles')->insert(
            [
               'servicio_id' => $id_servicio_agregado,
               'canti' => $fila[2],
               'precio' => $precio,
               'producto_id' => $fila[0],
               'producto_adic' => $fila[5],
               'operario_id' => $operario_id,
               'user_id' => $user_id,
               'created_at' => $fec_hora_hoy,
            ]
         );   // fin del DB::table insert para servicios_detalle
      };
      $arr_resul = [
         'servicio_id' => $id_servicio_agregado,
         'msg' => 'Grabación correcta en servicios',
      ];
      return response()->json($arr_resul);

   }

   public function generar_pdf_orden_servicio(Request $request){
      // 13ene2020
      // llamada via Ajax Post, desde 3 funciones javascript distintas (ordenes.js):
      //    a)  generar_orden_js()
      //    b)  modificar_orden_js()
      //    c)  cerrar_orden_js()
      // Lo que hará esta función es:
      //    a) Generar el pdf con la orden de servicio
      //       que acaba de ser abierta, modificada o cerrada.
      //    b) Regresar un JSON con el nombre
      //       del archivo pdf que fué grabado en public\tmp
      // Recibe 5 parámetros POST (además del _token):
      //    origen: tendrá uno de estos valores:
      //       i) 'crear': si fue llamado desde generar_orden_js()
      //       ii) 'modificar': si fue llamado desde modificar_orden_js()
      //       iii) 'cerrar': si fue llamado desde cerrar_orden_js()
      //    servicio_id: consecutivo de la orden de servicio abierta
      //    cliente_id: id del cliente que fue escogido
      //    vehiculo_id: id del vehículo escogido
      //    arr_productos: que contiene 9 columnas, pero de las cuales
      //    solamente se usarán 7:
      //       2  canti
      //       3  cai
      //       4  nombre del producto
      //       5  adicionales al producto
      //       6  nombre del operario
      //       7  valor unitario
      //       8  valor total

      $origen = $request->input("origen");
      $servicio_id = $request->input("servicio_id");
      $cliente_id = $request->input("cliente_id");
      $vehiculo_id = $request->input("vehiculo_id");
      $arr_productos = json_decode($request->input("arr_productos"));

      // armar variables para la fecha de entrada, que será enviada a la vista que
      // generará el pdf. la fecha de entrada será la que esté grabada en la tabla servicios:
      $arr_fecha_entrada = Servicio::select('created_at')->where('id', $servicio_id)->get()->toArray();
      $fec_entrada_pdf = date("Y-m-d" , strtotime($arr_fecha_entrada[0]["created_at"]));
      $hor_entrada_pdf = date('h:i a' , strtotime($arr_fecha_entrada[0]["created_at"]));

      $fec_entrada_dia = substr($fec_entrada_pdf , 8 , 2);
      $fec_entrada_mes = substr($fec_entrada_pdf , 5 , 2 );
      $fec_entrada_an = substr($fec_entrada_pdf , 0 , 4);

      // armar variables para la fecha de salida, que será enviada a la vista que
      // generará el pdf, esta variable depende del origen:
      if($origen == "cerrar"){
         // llegó desde cerrar_orden_js(), la fecha de cierre existe y está
         // en la tabla servicios:
         $arr_fecha_cierre = Servicio::select('cerrada_el')->where('id', $servicio_id)->get()->toArray();
         $fec_cierre_pdf = date("Y-m-d" , strtotime($arr_fecha_cierre[0]["cerrada_el"]));
         $hor_cierre_pdf = date('h:i a' , strtotime($arr_fecha_cierre[0]["cerrada_el"]));
         $fec_cierre_dia = substr($fec_cierre_pdf , 8 , 2);
         $fec_cierre_mes = substr($fec_cierre_pdf , 5 , 2 );
         $fec_cierre_an = substr($fec_cierre_pdf , 0 , 4);
      }else{
         // llegó desde generar_orden_js() o desde modificar_orden_js(), la fecha
         // de cierre todavia no existe:
         $espacio_blanco = '&nbsp;';
         $hor_cierre_pdf = html_entity_decode($espacio_blanco);
         $fec_cierre_dia = html_entity_decode($espacio_blanco);
         $fec_cierre_mes = html_entity_decode($espacio_blanco);
         $fec_cierre_an = html_entity_decode($espacio_blanco);
      }

      $user_id = Auth::user()->id;
      // obtener los datos del cliente escogido:
      $arr_clientes = Cliente::where('id' , $cliente_id)->get()->toArray();
      $fila_value = $arr_clientes[0];
      $cliente = $fila_value['razon_social'] . $fila_value['nombre1'] . " " . $fila_value['nombre2'] . " " . $fila_value['apellido1'] . " " . $fila_value['apellido2'];
      $telefonos = $fila_value['tel_fijo'] . " , " . $fila_value['tel_celu'];
      $cumple = $fila_value['cumple_dia'] . " - " . $fila_value['cumple_mes'];
      $arr_dir_ppal = Cod_direccion::select('nombre')->where('id' , $fila_value['dir_id'])->get()->toarray();
      $direccion = $arr_dir_ppal[0]['nombre'] . " " . $fila_value['dir_num_ppal'] . " " . $fila_value['dir_num_casa'] . " " . $fila_value['dir_adic'] ;
      $arr_ciudad = Cod_postal::select('ciudad')->where('id' , $fila_value['cod_postal_id'])->get()->toArray();
      $ciudad = $arr_ciudad[0]['ciudad'];
      $user_name = Auth::user()->name;

      // obtener los datos del vehículo escogido:
      $arr_vehiculo = Vehiculo::select('marca' , 'gama' , 'modelo' , 'placa' , 'kilom' , 'kilom_aceite' , 'fec_soat' , 'fec_tecno' , 'fec_extintor')->where('id' , $vehiculo_id)->get()->toArray();
      $fila_vehiculo = $arr_vehiculo[0];

      // obtener la info de los productos escogidos por el usuario:


      // array para enviar a la vista generadora del pdf:
      $arr_para_vista = [
         'fec_entrada_dia' => $fec_entrada_dia,
         'fec_entrada_mes' => $fec_entrada_mes,
         'fec_entrada_an' => $fec_entrada_an,
         'hor_entrada' => $hor_entrada_pdf,
         'fec_cierre_dia' => $fec_cierre_dia,
         'fec_cierre_mes' => $fec_cierre_mes,
         'fec_cierre_an' => $fec_cierre_an,
         'hor_cierre' => $hor_cierre_pdf,
         'consecutivo' => $servicio_id,
         'cliente' => $cliente,
         'doc_num' => $fila_value['doc_num'],
         'telefonos' => $telefonos ,
         'cumple' => $cumple ,
         'direccion' => $direccion ,
         'ciudad' => $ciudad ,
         'email' => $fila_value['email'],
         'marca' => $fila_vehiculo['marca'],
         'gama' => $fila_vehiculo['gama'],
         'modelo' => $fila_vehiculo['modelo'],
         'placa' => $fila_vehiculo['placa'],
         'kilom' => $fila_vehiculo['kilom'],
         'kilom_aceite' => $fila_vehiculo['kilom_aceite'],
         'fec_soat' => $fila_vehiculo['fec_soat'],
         'fec_tecno' => $fila_vehiculo['fec_tecno'],
         'fec_extintor' => $fila_vehiculo['fec_extintor'],
         'asesor' => $user_name,
         'arr_productos' => $arr_productos,
      ];

      $pdf = PDF::loadView('pdf_orden_servicio' , $arr_para_vista);
      $output = $pdf->output();

      // graba el pdf en el servidor (para luego abrirlo en una
      // nueva pestaña):
      // para que al abrir el archivo javascript window.open no
      // abra el del cache del navegador, sino el archivo original,
      // se debe manejar un identificador aleatorio a cada nombre
      // de archivo:
      $id_aleatorio = rand();
      $nom='tmp/mipdf_'.$id_aleatorio.'.pdf';
      // $nom_js = '../tmp/mipdf_'.$id_aleatorio.'.pdf';
      file_put_contents($nom , $output); //save pdf on server

      // regresa al ajax y envia el nombre del pdf:
      $arr_resul = [
         'pdf_creado' => $nom,
         'msg' => 'Pdf generado correctamente',
      ];
      return response()->json($arr_resul);


      // abre el pdf en una nueva ventana y regresa a pedir otro
      // cliente para generar otro pdf:
      // echo "<script type='text/javascript' language='javascript'>
      //    var nomarchi = '".$nom_js."';
      //    window.open( nomarchi , '_blank');
      //    var getUrl = window.location;
      //    var arr_path = getUrl.pathname.split('/');
      //    // el segundo elemento del arr_path puede ser:
      //    //    generar_orden: en un localhost
      //    //    colibri(u otro nombre): en un hosting compartido
      //    // esa es la razón de ser del siguiente if:
      //    if(arr_path[1]=='generar_orden'){
      //       var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + 'orden_cedula';
      //    }else{
      //       var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + arr_path[1] + '/' + 'orden_cedula';
      //    }
      //    location.href= baseUrl;
      // </script>";
   }

   public function crear_tablas_pedir_cais(formularioCliente $requestCliente ,
         formularioVehiculo $requestVehiculo){
      // 22ene2020
      // Función llamada con tipo  POST (via AJAX) desde la
      // vista ordenes/crear_cliente_orden_servicio.blade.php
      // Deberá grabar un nuevo cliente y un nuevo vehículo asociado a
      // ese cliente
      // Recibe todos los datos POST escritos en el formulario (datos de los
      // nuevos cliente y vehículo)
      // Hace lo siguiente:
      // 1) Inserta un registro en la tabla clientes
      // 2) Inserta un registro en la tabla vehiculos (el nuevo vehículo
      //    quedará relacionado con el cliente que se acaba de crear)
      // 3) retorna el resultado al ajax agregando el cliente_id y
      //    el vehiculo_id que fueron agregados

     // si llega aquí es porque las validaciones de los formRequest
     // formularioCliente y formularioVehiculo fueron correctas.
     // Si hubiera sucedido lo contrario, regresaria al 'error' del ajax

     // Como la validación fue correcta actualizará en la b.d. y regresará
     // al 'success' del ajax:
     $data = request()->all();

     // el proceso de insertar en  la tabla clientes se hace con la función
     // llamada a continuación (esto se debe a que el proceso también es llamado
     // desde la función ClienteController@create):
     $objCompartido = new CompartidoController();
     $arr_creacion_cliente = $objCompartido->crear_cliente($data);
     // el array recibido tendrá 4 columnas:
     //       msg         el mensaje resultante
     //       status      true agregó el cliente, false no pudo agregar
     //       cliente_id  id asignado en la tabla clientes
     //       error   error si no pudo agregar por error en b.d. (catch)

     if($arr_creacion_cliente['status']){
        // el cliente fué creado correctamente, puede proceder a
        // crear el vehículo:

        $arr_creacion_vehiculo = $objCompartido->crear_vehiculo($data , $arr_creacion_cliente['cliente_id']);
        // el array recibido tendrá 4 columnas:
        //       msg         el mensaje resultante
        //       status      true agregó el vehículo, false no pudo agregar
        //       vehiculo_id  el id del vehículo agregado
        //       error       siempre llegará una cadena vacia
        $arr_resul = $arr_creacion_vehiculo;
        // para agregar 2 campos adicionales al arr_resul:
        $cliente_id = $arr_creacion_cliente["cliente_id"];
        $vehiculo_id = $arr_creacion_vehiculo["vehiculo_id"];
     }else{
        // el cliente no pudo ser creado, por lo tanto no se intentará
        // crear el vehículo y se devolverá el mensaje de error::
        $arr_resul = ['msg' => $arr_creacion_cliente['msg']  , 'status' => false, 'error' =>$arr_creacion_cliente['error']];
        // para agregar 2 campos adicionales al arr_resul:
        $cliente_id = "";
        $vehiculo_id = "";
     }
     $arr_resul["cliente_id"] = $cliente_id;
     $arr_resul["vehiculo_id"] = $vehiculo_id;
     return Response()->json($arr_resul);
   }

   public function autocompletar_productos($digitado){
      // 30ene2020
      // llamada desde formu_pedir_cais.blade cuando el usuario esta
      // llenando el input text de autocompletado de productos

      // 10feb2020
      // ver la documentación en la función que hace el llamado ajax autocomplete()
      $digitado = str_replace('_@_' , '/' , $digitado);

      $arr_productos =   Producto::select(DB::raw('concat("( " , cai , " ) " , nombre) producto') , 'id')->where('cai' , 'like' , '%' . $digitado . '%')->orWhere('nombre' , 'like' , '%' . $digitado . '%')->get()->toArray();

      // lo siguiente es para depuracion: enviar info al success ajax:
      // $rgtos = count($arr_productos);
      // $arr_resul = [
      //    'arr_productos' => $arr_productos,
      //    'rgtos' => $rgtos,
      //    'digitado' => $digitado,
      // ];
      // return Response()->json($arr_resul);

      // return Response()->json($arr_productos);
      return Response()->json($arr_productos);
   }

   // *******************************************************************
   // 11feb2020
   // para mostrar el grid de las órdenes abiertas y grabar modificaciones
   // en la b.d.
   // *******************************************************************
   public function listar_ordenes_abiertas(){
     // muestra las órdenes abiertas (en datatable) y permite modificarlas
     return view('ordenes_abiertas_listar' , [
        'origen' => 'editar',
     ]);
   }

   public function listar_ordenes_abiertas_jquery(){
      $ordenes_abiertas = DB::select("SELECT ser.id
      	,trim(CONCAT(cli.razon_social , ' ' , nombre1 , ' ' , cli.nombre2 , ' ' , cli.apellido1 , ' ' , cli.apellido2)) cliente
      	,veh.placa
      	,ser.created_at creada
      	,usu.name usuario
        FROM servicios ser
        LEFT JOIN clientes cli ON cli.id=ser.cliente_id
        LEFT JOIN vehiculos veh ON veh.id=ser.vehiculo_id
        LEFT JOIN users usu ON usu.id=ser.user_id
      WHERE ser.abierta");

      return datatables()->of($ordenes_abiertas)
            ->make(true);
   }

   public function modificar_orden_abierta($num_orden , $cliente_nombre , $placa){
      // 12feb2020
      // llamada cuando el usuario presiona el botón EDITAR una orden abierta en
      // el grid de órdenes abiertas
      // Recibe 3 parámetros:
      //    $num_orden: el id de la orden que el usuario desea modificar
      //    $nom_cliente: nombre del cliente escogido, para mostrar en la vista que se llama enseguida
      //    $placa: placa escogida, para mostrar en la vista que se llama enseguida
      // Con el num_orden debe obtener el cliente_id y vehiculo_id para enviar a
      // la vista.

      $productos =  Producto::select(DB::raw('concat(nombre , " ( " , cai , " ) ") producto') , 'id')->orderby('nombre')->get()->toArray();
      $operarios = User::select('name' , 'id')->where('rol' , 'ope')->orderby('name')->get()->toArray();
      $arr_ordenes = Servicio::select('cliente_id' , 'vehiculo_id')->where('id' , $num_orden)->get()->toArray();
      $cliente_id = $arr_ordenes[0]["cliente_id"];
      $vehiculo_id = $arr_ordenes[0]["vehiculo_id"];

      return view('ordenes.formu_pedir_cais',
         [
            'productos' => $productos,
            'operarios' => $operarios,
            'cliente_id' => $cliente_id,
            'vehiculo_id' => $vehiculo_id,
            'cliente_nombre' => $cliente_nombre,
            'placa' => $placa,
            'origen' => 'modificar',
            'num_orden' => $num_orden,
         ]
      );
   }

   public function modificar_orden_leer_cais($num_orden){
      // llamada desde la vista formu_pedir_cais.blade cuando se ejecuta
      // la función js que llena la tabla html con los cais de la orden abierta
      // lo que hace es leer la tabla servicios_detalles para llenar
      // la info de la tabla html (idtablacais)
      $arr_detalle_cais = Servicios_detalle::with('producto')->with('operario')->where('servicio_id' , $num_orden)->get()->toArray();
      // para que si no hay adicionales en el nombre del producto, no lo deje como null:
      $fila_resul = 0;
      foreach($arr_detalle_cais as $fila){
         $producto_adic = $fila["producto_adic"];
         if($producto_adic == null){
            $producto_adic = "";
         }
         $vlr_total = $fila["producto"]["precio"] * $fila["canti"];
         $arr_resul[$fila_resul]["num_orden"] = $num_orden;
         $arr_resul[$fila_resul]["producto_id"] = $fila["producto_id"];
         $arr_resul[$fila_resul]["operario_id"] = $fila["operario_id"];
         $arr_resul[$fila_resul]["canti"] = $fila["canti"];
         $arr_resul[$fila_resul]["cai"] = $fila["producto"]["cai"];
         $arr_resul[$fila_resul]["descripcion"] = $fila["producto"]["nombre"];
         $arr_resul[$fila_resul]["producto_adic"] = $producto_adic;
         $arr_resul[$fila_resul]["operario_nombre"] = $fila["operario"]["name"];
         $arr_resul[$fila_resul]["vlr_uni"] = $fila["producto"]["precio"];
         $arr_resul[$fila_resul]["vlr_tot"] = $vlr_total;
         $fila_resul ++ ;
      }
      return Response()->json($arr_resul);
   }

   public function modificar_servicios(Request $request){
      // 18feb2020
      // llamada post, via ajax desde ordenes.js
      // En el parámetro $request llega esta información desde javascript:
      //    _token
      //    num_orden
      //    arr_productos: que contiene 9 columnas, pero de las cuales
      //    solamente se usarán 4:
      //       0  producto_id
      //       1  operario_id
      //       2  cantidad
      //       5  adicionales al nombre del producto
      // Está función se encarga de:
      //    a) pasar a la tabla servicios_detalles_historias los registros de la
      //       tabla servicios_detalles pertenecientes al número de orden
      //    b) borrar de la tabla servicios_detalles los registros que
      //       correspondan al número de orden
      //    c) Grabar las filas de la tabla html (que aquí llegan en el
      //        arr_productos) a la tabla servicios_detalles, asignándolos
      //       al número de orden dado.
      //    d) Regresar al ajax del que fue llamado

      // recepción de las variables de los parámetros:
      $num_orden = $request->input("num_orden");
      $arr_productos = json_decode($request->input("arr_productos"));

      DB::beginTransaction();
      try {
         // a)
         DB::statement('insert into servicios_detalles_historias select * from servicios_detalles where servicio_id = :id' , ['id' => $num_orden]);

         // b)
         DB::delete('delete from servicios_detalles where servicio_id = :id' , ['id' => $num_orden]);

         // c)

         // campos que deben ser
         // obtenidos por programación:
         // hay que obtener al mismo tiempo (aunque en formatos distintos),
         // la fecha hora actual para la b.d. y para generar el pdf:
         $fec_hora_hoy = date("Y-m-d H:i:s");
         // $fec_entrada_pdf = date("Y-m-d");
         // $hor_entrada_pdf = date('h:i:s a');
         $user_id = Auth::user()->id;
         foreach($arr_productos as $fila){
            $operario_id = $fila[1];
            // quitarle el formato al valor unitario (precio);
            $precio = str_replace("," , "" , $fila[7]);

            DB::table('servicios_detalles')->insert(
               [
                  'servicio_id' => $num_orden,
                  'canti' => $fila[2],
                  'precio' => $precio,
                  'producto_id' => $fila[0],
                  'producto_adic' => $fila[5],
                  'operario_id' => $operario_id,
                  'user_id' => $user_id,
                  'updated_at' => $fec_hora_hoy,
               ]
            );   // fin del DB::table insert para servicios_detalle
         };
         $arr_resul = [
            'msg' => 'Actualización correcta',
            'status' => true,
            'error' => '',
         ];
         DB::commit();
      }catch(\Throwable $e){
         $msg = 'ERROR: (Catch transaction) ';
         $arr_resul = ['msg' => $msg, 'status' => false , 'error' => $e];
      	DB::rollback();
      	throw $e;
      }
      return response()->json($arr_resul);
   }

   // *******************************************************************
   // 24feb2020
   // para mostrar el grid de las órdenes abiertas y permitir
   // el cierre de las mismas
   // *******************************************************************
   public function listar_ordenes_cerrar(){
     // muestra las órdenes abiertas (en datatable) y permite cerrarlas:
     return view('ordenes_abiertas_listar' , [
        'origen' => 'cerrar',
     ]);
   }

   public function cerrar_orden($num_orden , $cliente_nombre , $placa){
      // 127eb2020
      // llamada cuando el usuario presiona el botón CERRAR ORDEN en
      // el grid de órdenes abiertas, para una orden determinada
      // Recibe 3 parámetros:
      //    $num_orden: el id de la orden que el usuario desea CERRAR
      //    $nom_cliente: nombre del cliente escogido, para mostrar en la vista que se llama enseguida
      //    $placa: placa escogida, para mostrar en la vista que se llama enseguida
      // Con el num_orden debe obtener el cliente_id y vehiculo_id para enviar a
      // la vista.

      $productos =  Producto::select(DB::raw('concat(nombre , " ( " , cai , " ) ") producto') , 'id')->orderby('nombre')->get()->toArray();
      $operarios = User::select('name' , 'id')->where('rol' , 'ope')->orderby('name')->get()->toArray();
      $arr_ordenes = Servicio::select('cliente_id' , 'vehiculo_id')->where('id' , $num_orden)->get()->toArray();
      $cliente_id = $arr_ordenes[0]["cliente_id"];
      $vehiculo_id = $arr_ordenes[0]["vehiculo_id"];

      return view('ordenes.formu_pedir_cais',
         [
            'productos' => $productos,
            'operarios' => $operarios,
            'cliente_id' => $cliente_id,
            'vehiculo_id' => $vehiculo_id,
            'cliente_nombre' => $cliente_nombre,
            'placa' => $placa,
            'origen' => 'cerrar',
            'num_orden' => $num_orden,
         ]
      );
   }

   public function cerrar_orden_bd(Request $request){
      // Es llamada desde el segundo ajax interno de la función cerrar_orden_js()
      // de ordenes.js
      //    a) lleva false(0) al campo abierta de la tabla servicios
      //    b) graba la fecha-hora en que la orden fue cerrada y el cerrada_user_id
      //    c) saca del inventario la cantidad de productos contenidos en la orden
      // En el parámetro $request llega esta información desde javascript:
      //    _token
      //    num_orden
      //    arr_productos: que contiene 9 columnas:
      //       0  producto_id
      //       1  operario_id
      //       2  cantidad
      //       3  cai
      //       4  descripción del producto
      //       5  adicionales al nombre del producto
      //       6  nombre del operario
      //       7  valor unitario
      //       8  valor total

      // recepción de las variables de los parámetros:
      $num_orden = $request->input("num_orden");
      $arr_productos = json_decode($request->input("arr_productos"));

      $user_id = Auth::user()->id;
      DB::beginTransaction();
      try {
         // actualizar campos en la tabla servicios:
         DB::statement('update servicios set abierta = false , cerrada_el = now() , cerrada_user_id = :user_id where id = :num_orden' , ['num_orden' => $num_orden , 'user_id' => $user_id]);

         // agregar a la tabla a la tabla inv_movimientos un registro tipo
         // salida por cada cai de los que llegaron en productos:
         // campos que deben ser
         // obtenidos por programación:
         // hay que obtener al mismo tiempo (aunque en formatos distintos),
         // la fecha hora actual para la b.d. y para generar el pdf:
         $fec_hora_hoy = date("Y-m-d H:i:s");
         // $fec_entrada_pdf = date("Y-m-d");
         // $hor_entrada_pdf = date('h:i:s a');
         $user_id = Auth::user()->id;
         foreach($arr_productos as $fila){
            // quitarle el formato al valor unitario (precio);
            $precio = str_replace("," , "" , $fila[7]);

            DB::table('inv_movimientos')->insert(
               [
                  'producto_id' => $fila[0],
                  'tipo' => 'S',
                  'canti' => $fila[2],
                  'valor' => $precio,
                  'servicio_id' => $num_orden,
                  'user_id' => $user_id,
                  'created_at' => $fec_hora_hoy,
               ]
            );   // fin del DB::table insert para inv_movimientos
         }  // fin del foreach que recorre los cais

         $arr_resul = [
            'msg' => 'Actualización correcta',
            'status' => true,
            'error' => '',
         ];
         DB::commit();
      }catch(\Throwable $e){
         $msg = 'ERROR: (Catch transaction) ';
         $arr_resul = ['msg' => $msg, 'status' => false , 'error' => $e];
      	DB::rollback();
      	throw $e;
      }
      return response()->json($arr_resul);

   }



}  // fin de la clase principal del controlador OrdenController
