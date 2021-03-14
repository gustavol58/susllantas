<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Doc_tipo;
use App\Cod_direccion;
use App\Cod_postal;
use App\Param;
use Redirect,Response,DB,Config;
use Datatables;
use App\Http\Requests\formularioCliente;
use Auth;
// use App\Rules\ExisteClaveCliente;

class ClienteController extends Controller
{
    // constructor para evitar el ingreso de usuarios no logueados
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
      // muestra los clientes en datatable y permite las opciones crud
      return view('clientes_listar');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      // llamada por el botón CREAR CLIENTES de la vista clientes_listar.blade.php
      return view('clientes.create');
    }

    public function store(formularioCliente $request)
    {
      // llamada POST desde la vista clientes/create.blade.php

      // si llega aquí es porque las validaciones del formRequest
      // formularioCliente fueron correctas. Si hubiera sucedido
      // lo contrario, regresaria al 'error' del ajax
      $data = $request->all();
// dd($data);
      // antes de grabar, obtiene la clave Wimax para el cliente:
      $clave = $this->generar_clave_wimax();

      // campos que no son pedidos al usuario o que debe ser
      // obtenidos por programación:
      $estado = "A";
      $fec_hoy = date("Y-m-d");
      $fec_hora_hoy = date("Y-m-d H:i:s");
      // para obtener el campo dir_id:
      $direccion_id =  substr($data['dir_ppal'],0,strpos($data['dir_ppal'],"@"));
      // 2 instrucciones comentariadas porque se decidió grabar la dirección repartida en
      // cuatro campos, y no solo en uno solo como se pensaba al principio
      // $direccion_nombre =  Cod_direccion::select('nombre')->find($direccion_id);
      // $direccion = $direccion_nombre->nombre . " " . strtoupper($data['dir_num_ppal']) . " " . strtoupper($data['dir_num_casa']) . " " . strtoupper($data['dir_adic']);
      // obtener el id del código postal a partir del dpto-ciudad escogidos
      $ciudad_id = $data['ciudad'];
      $col_cod_postal_id = Cod_postal::where('divipol',$ciudad_id)->get();
      $cod_postal_id = $col_cod_postal_id[0]['id'];
      // otra manera de hacer lo anterior seria mas sencillo:
      // $cod_postal_id = Cod_postal::select('id')->where('divipol','05091')->first();
      // si el checkbox de habeas_data fue activado llegará aqui con el
      // value="1", pero si no fué seleccionado, no llegará en el POST,
      // por lo tanto:
      if(isset($data['habeas_data'])) {
         $habeas_data = $data['habeas_data'];
      }else{
         $habeas_data = false;
      }
      // para convertir el número de mes en sus tres primeras letras:
      // 13nov2019: se decidió que en el select se pondrían directamente
      // las tres primeras letras del mes, por eso los siguientes comentarios:
      // $arr_meses_3 = ['' , 'ENE' , 'FEB' , 'MAR' , 'ABR' , 'MAY' , 'JUN' , 'JUL' , 'AGO' , 'SEP' , 'OCT' , 'NOV' , 'DIC' ];
      // $nom_mes = $arr_meses_3[$data['cumple_mes']];
      // usuario logueado:
      $user_id = Auth::user()->id;

      // inserta el registro en la tabla clientes e incrementa
      // el número para la clave wimax en la tabla params:
      DB::beginTransaction();
      try {
         $id_agregado = DB::table('clientes')->insertGetId(
            [
               'clave' => $clave,
               'doc_tipo_id' => $data['doc_tipo'],
               'doc_num' => $data['doc_num'],
               'doc_dv' => $data['doc_dv'],
               'juridica' => $data['juridica'],
               'declarante' => $data['declarante'],
               'estado' => $estado,
               'nombre1' => strtoupper($data['nombre1']),
               'nombre2' => strtoupper($data['nombre2']),
               'apellido1' => strtoupper($data['apellido1']),
               'apellido2' => strtoupper($data['apellido2']),
               'razon_social' => strtoupper($data['razon_social']),
               'fec_ingreso' => $fec_hoy,
               'dir_id' => $direccion_id,
               'dir_num_ppal' => strtoupper($data['dir_num_ppal']),
               'dir_num_casa' => strtoupper($data['dir_num_casa']),
               'dir_adic' => strtoupper($data['dir_adic']),
               'cod_postal_id' => $cod_postal_id,
               'email' => $data['email'],
               'tel_fijo' => $data['tel_fijo'],
               'tel_celu' => $data['tel_celu'],
               'habeas_data' => $habeas_data,
               'cumple_dia' => $data['cumple_dia'],
               'cumple_mes' => $data['cumple_mes'],
               'contacto' => $data['contacto'],
               'user_id' => $user_id,
               'created_at' => $fec_hora_hoy
            ]
         );   // fin del DB::table insert

         // incrementar el número para obtener posteriormente otra clave wimax:
         DB::update('update params
               set ult_clave_wimax = ult_clave_wimax +1');

         DB::commit();
         if($data['doc_tipo']==1 || $data['doc_tipo']==2){
            $nom_cliente = $data['nombre1']." ".$data['nombre2']." ".$data['apellido1']." ".$data['apellido2'];
         }else{
            $nom_cliente = $data['razon_social'];
         }
         $msg = 'Grabación correcta. Cliente agregado: '.strtoupper($nom_cliente).'   Clave asignada: '.$clave;

         $arr = array('msg' => $msg  , 'status' => true, 'error' => '');

         // si el cliente autorizo el habeas data, renombra el archivo de firma:
         if($habeas_data){
            $baseUrl = public_path('img\habeas_data\\');
            $firma_ant = $baseUrl."docu_".$data['doc_num'].".png";
            $firma_nueva = $baseUrl.$id_agregado.".png";
            rename($firma_ant , $firma_nueva);
         }
      }catch(\Throwable $e){
         $arr = array('msg' => 'Error en catch transaction!!!', 'status' => false, 'error' => $e);
      	DB::rollback();
      	throw $e;
      }

      // retorna al sucees del ajax de la función validar_grabar_nuevo_cliente():
      return Response()->json($arr);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      // llamado por el botón EDIT de algún registro en la vista clientes_listar.blade.
      $cliente=Cliente::with('Cod_postal' , 'Cod_direccion')->find($id);
// dd($cliente->toArray());
      return view('clientes.edit',['cliente' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(formularioCliente $request, $id)
    {
      // llamada PUT desde la vista clientes/edit.blade.php

      // si llega aquí es porque las validaciones del formRequest
      // formularioCliente fueron correctas. Si hubiera sucedido
      // lo contrario, regresaria al 'error' del ajax

      // Como la validación fue correcta actualizará en la b.d. y regresará
      // al 'success' del ajax:

      // lo primero es preparar los campos que no se pueden grabar
      // directamente a la tabla:
      // obtener el id del código postal a partir del dpto-ciudad escogidos
      $data_formu = request()->all();
// dd($data_formu);
      $ciudad_id = $data_formu['ciudad'];
      $col_cod_postal_id = Cod_postal::where('divipol',$ciudad_id)->get();
      $cod_postal_id = $col_cod_postal_id[0]['id'];
      // obtener el id dirección:
      $direccion_id =  substr($data_formu['dir_ppal'],0,strpos($data_formu['dir_ppal'],"@"));
      // si el checkbox de habeas_data fue activado llegará aqui con el
      // value="1", pero si no fué seleccionado, no llegará en el POST,
      // por lo tanto:
      if(isset($data['habeas_data'])) {
         $habeas_data = $data['habeas_data'];
      }else{
         $habeas_data = false;
      }
      // campos que deben ser grabados en mayúsculas:
      $nombre1_mayusc = strtoupper($data['nombre1']);
      $nombre2_mayusc = strtoupper($data['nombre2']);
      $apellido1_mayusc = strtoupper($data['apellido1']);
      $apellido2_mayusc = strtoupper($data['apellido2']);
      $razon_social_mayusc = strtoupper($data['razon_social']);
      $dir_num_ppal_mayusc = strtoupper($data['dir_num_ppal']);
      $dir_num_casa = strtoupper($data['dir_num_casa']);
      $dir_adic = strtoupper($data['dir_adic']);

      // update usando eloquent:
      $data_table = request()->except(['_token' , '_method' , 'dpto' , 'ciudad' , 'cod_postal' , 'dir_ppal']);
      $data_table['cod_postal_id'] = $cod_postal_id;
      $data_table['dir_id'] = $direccion_id;
      $data_table['nombre1'] = $nombre1_mayusc;
      $data_table['nombre2'] = $nombre2_mayusc;
      $data_table['apellido1'] = $apellido1_mayusc;
      $data_table['apellido2'] = $apellido2_mayusc;
      $data_table['razon_social'] = $razon_social_mayusc;
      $data_table['dir_num_ppal'] = $dir_num_ppal_mayusc;
      $data_table['dir_num_casa'] = $dir_num_casa_mayusc;
      $data_table['dir_adic'] = $dir_adic_mayusc;

      $cliente = Cliente::whereId($id)->update($data_table);
      $arr = array('msg' => 'Error !!!', 'status' => false);
      if($cliente){
        $arr = array('msg' => 'Los cambios fueron grabados correctamente', 'status' => true);
      }
      return Response()->json($arr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $cliente = Cliente::where('id',$id)->delete();

      $arr = array('msg' => 'Error !!!', 'status' => false);
      if($cliente){
        $arr = array('msg' => 'El registro fue eliminado correctamente', 'status' => true);
      }
      return Response()->json($arr);
    }

    public function listarClientesJquery(){
      // método jquery para mostrar los clientes usando datatables
      $clientes = DB::select('SELECT cli.doc_num,
         case
            -- is null agregado el 10nov2019 a raiz del nuevo formulario para crear clientes
            -- en donde se estableció el manejo de NULL para nombres y razones sociales:
         	when cli.razon_social IS NULL
					then trim(CONCAT(TRIM(cli.nombre1)," ",trim(IFNULL(cli.nombre2,""))," ",trim(IFNULL(cli.apellido1,""))," ",trim(IFNULL(cli.apellido2,""))))
				when length(cli.razon_social)=0
					then trim(CONCAT(trim(cli.nombre1)," ",trim(cli.nombre2)," ",trim(cli.apellido1)," ",trim(cli.apellido2)))
      		ELSE trim(cli.razon_social)
			END nombre,
        	pos.ciudad,
         case
        		when dir_id=99
        			then cli.dir_old
        		else
        			CONCAT(dir.nombre, " ", trim(IFNULL(cli.dir_num_ppal,"")), " ", trim(IFNULL(cli.dir_num_casa,"")), " ", trim(IFNULL(cli.dir_adic,"")))
        	END direccion,
        	cli.tel_fijo,
        	cli.tel_celu,
        	cli.email,
        	cli.fec_ingreso,
        	doctip.nombre doc_tipo_id,  -- a partir de aca serán columnas invisibles en el datatable
        	cli.doc_dv,
        	cli.juridica,
        	cli.declarante,
        	cli.estado,
        	cli.habeas_data,
        	cli.cumple_dia,
        	cli.cumple_mes,
        	cli.contacto,
         pos.departamento,
         cli.clave,
        	cli.id
        FROM clientes cli
        LEFT JOIN doc_tipos doctip on doctip.id=cli.doc_tipo_id
        LEFT JOIN cod_postales pos ON pos.id=cli.cod_postal_id
        LEFT JOIN cod_direcciones dir ON dir.id=cli.dir_id');

        return datatables()->of($clientes)
            ->make(true);
    }

    public function llenar_select_doc_tipo(){
      $doc_tipos = Doc_tipo::select('nombre' , 'id')->orderby('orden')->get()->toArray();
      // para devolver al ajax el json:
      return response()->json($doc_tipos);
    }

    public function llenar_select_dir_ppal(){
      $dir_ppal = DB::select('SELECT nombre, concat(id,"@",grupo) idgrupo, grupo
        FROM cod_direcciones
        WHERE activo_principal
        ORDER BY orden_principal');

      // para convertir el resultado del DB::select a un array:
      $dir_ppal = array_map(function ($value) {
            return (array)$value;
        }, $dir_ppal);

      // para devolver al ajax el json:
      return response()->json($dir_ppal);
    }

    public function llenar_select_dpto(){
      $dptos = DB::select('SELECT MIN(departamento) dpto_nom,
          MIN(cod_dpto) dpto_cod
        FROM cod_postales
          WHERE id != 1123
        GROUP BY departamento
        ORDER BY departamento');

      // para convertir el resultado del DB::select a un array:
      $dptos = array_map(function ($value) {
            return (array)$value;
        }, $dptos);

      // para devolver al ajax el json:
      return response()->json($dptos);
    }

    public function llenar_select_ciudades($dpto_cod){
      $ciudades = DB::select("SELECT  ciudad,divipol,cod_postal
          FROM cod_postales
          WHERE cod_dpto=:dpto_cod
          ORDER BY ciudad",[':dpto_cod' => $dpto_cod]);
      // para convertir el resultado del DB::select a un array:
      $ciudades = array_map(function ($value) {
            return (array)$value;
        }, $ciudades);

      // para devolver al ajax el json:
      return response()->json($ciudades);
    }

    public function obtener_cod_postal($divipol){
      $arr_cod_postal = DB::select("SELECT  cod_postal
	           FROM cod_postales
              WHERE divipol=:divipol",[':divipol' => $divipol]);
      // para convertir el resultado del DB::select a un array:
      $arr_cod_postal = array_map(function ($value) {
            return (array)$value;
        }, $arr_cod_postal);

      $cod_postal = $arr_cod_postal[0]['cod_postal'];

      // para devolver al ajax el json:
      return response()->json($cod_postal);
    }

    public function grabar_firma(Request $request){
      // echo "se grabará la firma....";
      $data_uri = request()->input(['dataUrl']);
      $doc_num = "docu_".request()->input(['doc_num']);
      $encoded_image = explode(",", $data_uri)[1];
      $decoded_image = base64_decode($encoded_image);
      $ruta_archivo = "img/habeas_data/".$doc_num.".png";
      file_put_contents($ruta_archivo, $decoded_image);
      return response()->json('ya grabó la firma en formato png');
    }

    public function modificar_firma(Request $request){
      $data_uri = request()->input(['dataUrl']);
      $cliente_id = request()->input(['cliente_id']);
      $encoded_image = explode(",", $data_uri)[1];
      $decoded_image = base64_decode($encoded_image);
      $ruta_archivo = "img/habeas_data/".$cliente_id.".png";
      file_put_contents($ruta_archivo, $decoded_image);
      return response()->json('ya modificó la firma en formato png');
    }

    private function generar_clave_wimax(){
      // Generación de la clave wimax que debe tener cada cliente
      // Es una clave de 5 letras mayúsculas.
      // Se genera a partir de una semilla que arranca en el número 0
      // y que convertido a letras mayúsculas genera la clave AAAAA
      // La última clave ZZZZZ corresponde al número 11,881,375
      // La conversión se hace con el módulo 26 y con un array que tiene
      // las letras de la A a la Z, correspondiendo a la letra A el
      // índice 0 del array y a la letra Z el índice 25 del array
      // 1) En el campo ult_clave_wimax de la tabla params, se encuentra el
      // valor correspondiente a la última clave wimax que fué asignada
      // a un cliente, para generar la nueva se lee este valor, se incrementa
      // en 1 y se hace el proceso módulo 26.
      // 2) obtenida la nueva clave, se busca en el campo "clave" de la
      // tabla clientes, si existe entonces debe increntar en 1 el
      // campo ult_clave_wimax de la tabla params y luego repetir el
      // paso 1). Si no existe entonces retorna la nueva clave al
      // sitio desde donde fué llamada esta función. (Este retorno
      // se controla con el while infinito)
      $arr_letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

      // saldrá del siguiente ciclo infinito cuando se obtenga una clave
      // wimax que no exista en la tabla clientes
      while(true){
         // obtener el siguiente número para la nueva clave:
         $ult_grabado = Param::first()->ult_clave_wimax;
         $nuevo_num_clave = $ult_grabado + 1;

         // proceso módulo 26:
         $arr_residuos = [];
         $cociente = $nuevo_num_clave;
         for($i = 0 ; $i <= 4 ; $i++){
            $residuo = $cociente % 26;
            $cociente = intdiv($cociente , 26);
            $arr_residuos[$i] = $arr_letras[$residuo];
         }
         // generar la nueva clave a partir de arr_residuos:
         $nueva_clave = "";
         for($j = 4 ; $j>=0 ; $j--){
            $nueva_clave = $nueva_clave . $arr_residuos[$j];
         }

         // buscar la nueva clave en la tabla clientes:
         // 09nov2019 PENDIENTE: CAMBIAR POR MODELO EXIST().........
         if(Cliente::where('clave' , $nueva_clave)->exists()){
            // la clave SI existe en la tabla clientes, debe
            // incrementar en 1 el campo ult_clave_wimax de
            // la tabla params y luego comenzar otra vez este ciclo
            // para buscar la siguiente clave que no exista:
            $filas_actualizadas =
               DB::update('update params
                     set ult_clave_wimax = ult_clave_wimax + 1');
         }else{
            // la clave NO existe en la tabla clientes, puede
            // salir del ciclo y retornar la clave obtenida:
            break;
         }
      }
      return $nueva_clave;
   }



}
