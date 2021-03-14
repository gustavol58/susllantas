<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Cod_direccion;
use App\Cod_postal;
use App\Vehiculo;
use App\Servicio;
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

   public function buscar_cliente_orden_servicio(Request $request){
      // llamada post cuando el usuario digita el num docu a buscar:
      // en los parámetros del formulario llega el num docu
      // lee el parámetro:
      $doc_num = $request->input("php_doc_num");
      // se hace neceario el first() (en vez de toArray()) para que no
      // tenga problemas con partials.form_edit_cliente.blade.php
      $cliente = Cliente::where('doc_num' , $doc_num)->first();
      // 21ene2020
      // si el cliente existe, llama la edición de clientes
      // si el cliente no existe, llama el ingreso de clientes:
      if($cliente == null){
         // el cliente no existe:
         // llama la vista que permitirá matricular el
         // cliente:
         return view('ordenes.insert_cliente_orden_servicio' ,
            [
               'doc_num' => $doc_num,
            ]
         );
      }else{
         $nombre_completo = $cliente['razon_social'].' '.$cliente['nombre1'].' '.$cliente['nombre2'].' '.$cliente['apellido1'].' '.$cliente['apellido2'];
         // llama la vista que mostrará todos los datos del cliente
         // y permitirá editarlos:
         return view('ordenes.edit_cliente_orden_servicio' ,
            [
               'cliente' => $cliente,
               'nombre_completo_cliente' => $nombre_completo,
            ]
         );
      }
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

   public function llamar_vista_pedir_cais($cliente_id , $vehiculo_id){
      // llamado desde la vista edit_orden_servicio.blade.php, luego de que
      // se actualizarron las tablas de clientes y vehículos.
      // Debe obtener la información para llenar los combos productos y
      // operarios que serán usados en el formulario modal que tiene la
      // vista que será llamada.
      // Recibe 2 parámetros: los id del cliente y vehículos escogidos
      // para hacerles una orden de servicio.

      $productos =  Producto::select(DB::raw('concat(nombre , " ( " , cai , " ) ") producto') , 'id')->orderby('nombre')->get()->toArray();
      // $productos = Producto::select('nombre' , 'cai')->get()->toArray();
      $operarios = User::select('name' , 'id')->where('rol' , 'ope')->orderby('name')->get()->toArray();

      return view('ordenes.formu_pedir_cais',
         [
            'productos' => $productos,
            'operarios' => $operarios,
            'cliente_id' => $cliente_id,
            'vehiculo_id' => $vehiculo_id,
         ]
      );


   }

   public function leer_datos_producto_escogido($producto_id , $operario_id){
      // llamado desde la vista formu_pedir_cais.blade.php cuando el usuario
      // presiona el botón para agregar un producto a la tabla html5.
      // Recibe un parámetro:
      //    producto_id que contiene el idproducto escogido
      // Se encarga de leer en la tabla de productos y devuelve al
      // ajax un json con estas columnas:
      //    id
      //    cai
      //    descripción
      //    valor unitario (precio de venta)
      //    operario_nombre
      $datos_producto = Producto::select('id','cai','nombre','precio')->where('id' , '=' , $producto_id)->get()->toArray();
      $operario_nombre = User::select('name as operario')->where('id' , '=' , $operario_id)->get()->toArray();
      $datos_producto[0]["operario"] = $operario_nombre[0]["operario"];

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
      //    arr_productos: que contiene 8 columnas, pero de las cuales
      //    solamente se usarán 3:
      //       0  producto_id
      //       1  operario_id
      //       2  cantidad
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
         DB::table('servicios_detalles')->insert(
            [
               'servicio_id' => $id_servicio_agregado,
               'canti' => $fila[2],
               'producto_id' => $fila[0],
               'operario_id' => $fila[1],
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
      // llamada via Ajax Post, despues de grabar en las tablas de servicios, lo que
      // hará esta función es generar el pdf con la orden de servicio
      // que acaba de ser abierta. Y regresará un JSON con el nombre
      // del archivo pdf que fué grabado en public\tmp
      // Recibe 4 parámetros POST:
      //    servicio_id: consecutivo de la orden de servicio abierta
      //    cliente_id: id del cliente que fue escogido
      //    vehiculo_id: id del vehículo escogido
      //    arr_productos: que contiene 8 columnas, pero de las cuales
      //    solamente se usarán 6:
      //       2  canti
      //       3  cai
      //       4  nombre del producto
      //       5  nombre del operario
      //       6  valor unitario
      //       7  valor total

      $servicio_id = $request->input("servicio_id");
      $cliente_id = $request->input("cliente_id");
      $vehiculo_id = $request->input("vehiculo_id");
      $arr_productos = json_decode($request->input("arr_productos"));

      // armar variables que serán enviadas a la vista que generará el pdf:
      $fec_entrada_pdf = date("Y-m-d");
      $hor_entrada_pdf = date('h:i:s a');
      $user_id = Auth::user()->id;
      $fec_entrada_dia = substr($fec_entrada_pdf , 8 , 2);
      $fec_entrada_mes = substr($fec_entrada_pdf , 5 , 2 );
      $fec_entrada_an = substr($fec_entrada_pdf , 0 , 4);

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



}  // fin de la clase principal del controlador OrdenController
