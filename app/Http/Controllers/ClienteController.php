<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Doc_tipo;
use App\Cod_direccion;
use App\Cod_postal;
use Redirect,Response,DB,Config;
use Datatables;
use App\Http\Requests\formularioCliente;
use Auth;
use Compartido;      // CompartidoController

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
      // 22ene2020: el parámetro doc_num se envia para que no genere conflictos
      //            con la otra llamada que se hace a esta misma vista desde
      //            el controlador OrdenController.
      return view('clientes.create' , [
         'doc_num' => '',
      ]);
    }

    public function store(formularioCliente $request)
    {
      // llamada POST desde la vista clientes/create.blade.php

      // si llega aquí es porque las validaciones del formRequest
      // formularioCliente fueron correctas. Si hubiera sucedido
      // lo contrario, regresaria al 'error' del ajax
      $data = $request->all();

      // el proceso de creación en la tabla clientes, se hace con la función llamada
      // a continuación (esto se debe a que el proceso también es llamado
      // desde la función OrdenController@crear_tablas_pedir_cais):
      $objCompartido = new CompartidoController();
      $arr_creacion_cliente = $objCompartido->crear_cliente($data);
      // el array recibido tendrá 4 columnas:
      //       msg         el mensaje resultante
      //       status      true agregó el cliente, false no pudo agregar
      //       cliente_id  id asignado en la tabla clientes
      //       error   error si no pudo agregar por error en b.d. (catch)

      // retorna al sucees del ajax de la función validar_grabar_nuevo_cliente():
      return Response()->json($arr_creacion_cliente);
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
      $nombre_completo = $cliente['razon_social'].' '.$cliente['nombre1'].' '.$cliente['nombre2'].' '.$cliente['apellido1'].' '.$cliente['apellido2'];

// dd($cliente);

      return view('clientes.edit',
         [
            'cliente' => $cliente,
            'nombre_completo_cliente' => $nombre_completo,
         ]
      );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(formularioCliente $request, $cliente_id)
    {
      // llamada PUT desde la vista clientes/edit.blade.php

      // si llega aquí es porque las validaciones del formRequest
      // formularioCliente fueron correctas. Si hubiera sucedido
      // lo contrario, regresaria al 'error' del ajax

      // Como la validación fue correcta actualizará en la b.d. y regresará
      // al 'success' del ajax:
      $data = request()->all();

      // el proceso de actualización a la tabla se hace con la función llamada
      // a continuación (esto se debe a que el proceso también es llamado
      // desde la función OrdenController@pedir_cais_orden_servicio):
      $objCompartido = new CompartidoController();
      $msg = $objCompartido->actualizar_cliente($data , $cliente_id);

      if(strlen($msg) !== 0){
         $arr = array('msg' => $msg  , 'status' => true, 'error' => '');
      }else{
         $arr = array('msg' => $msg  , 'status' => false, 'error' => $msg);
      }
      return Response()->json($arr);

               // UPDATE USANDO ELOQUENT:
               //       // lo primero es preparar los campos que no se pueden grabar
               //       // directamente a la tabla:
               //       // obtener el id del código postal a partir del dpto-ciudad escogidos
               //       $data_formu = request()->all();
               // // dd($data_formu);
               //       $ciudad_id = $data_formu['ciudad'];
               //       $col_cod_postal_id = Cod_postal::where('divipol',$ciudad_id)->get();
               //       $cod_postal_id = $col_cod_postal_id[0]['id'];
               //       // obtener el id dirección:
               //       $direccion_id =  substr($data_formu['dir_ppal'],0,strpos($data_formu['dir_ppal'],"@"));
               //       // si el checkbox de habeas_data fue activado llegará aqui con el
               //       // value="1", pero si no fué seleccionado, no llegará en el POST,
               //       // por lo tanto:
               //       if(isset($data['habeas_data'])) {
               //          $habeas_data = $data['habeas_data'];
               //       }else{
               //          $habeas_data = false;
               //       }
               //       // campos que deben ser grabados en mayúsculas:
               //       $nombre1_mayusc = strtoupper($data['nombre1']);
               //       $nombre2_mayusc = strtoupper($data['nombre2']);
               //       $apellido1_mayusc = strtoupper($data['apellido1']);
               //       $apellido2_mayusc = strtoupper($data['apellido2']);
               //       $razon_social_mayusc = strtoupper($data['razon_social']);
               //       $dir_num_ppal_mayusc = strtoupper($data['dir_num_ppal']);
               //       $dir_num_casa = strtoupper($data['dir_num_casa']);
               //       $dir_adic = strtoupper($data['dir_adic']);
               //
               //       // update usando eloquent:
               //       $data_table = request()->except(['_token' , '_method' , 'dpto' , 'ciudad' , 'cod_postal' , 'dir_ppal']);
               //       $data_table['cod_postal_id'] = $cod_postal_id;
               //       $data_table['dir_id'] = $direccion_id;
               //       $data_table['nombre1'] = $nombre1_mayusc;
               //       $data_table['nombre2'] = $nombre2_mayusc;
               //       $data_table['apellido1'] = $apellido1_mayusc;
               //       $data_table['apellido2'] = $apellido2_mayusc;
               //       $data_table['razon_social'] = $razon_social_mayusc;
               //       $data_table['dir_num_ppal'] = $dir_num_ppal_mayusc;
               //       $data_table['dir_num_casa'] = $dir_num_casa_mayusc;
               //       $data_table['dir_adic'] = $dir_adic_mayusc;
               //
               //       $cliente = Cliente::whereId($id)->update($data_table);
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
      $clientes = DB::select('SELECT cli.origen,
          cli.doc_num,
          case
            -- is null agregado el 10nov2019 a raiz del nuevo formulario para crear clientes
            -- en donde se estableció el manejo de NULL para nombres y razones sociales:
            when cli.razon_social IS NULL
            then trim(CONCAT(TRIM(cli.nombre1)," ",trim(IFNULL(cli.nombre2,""))," ",trim(IFNULL(cli.apellido1,""))," ",trim(IFNULL(cli.apellido2,""))))
            when length(cli.razon_social)=0
            then trim(CONCAT(trim(cli.nombre1)," ",trim(cli.nombre2)," ",trim(cli.apellido1)," ",trim(cli.apellido2)))
            ELSE trim(cli.razon_social)
			    END nombre,

          case
            when cli.origen="Tiremaxx" then
              pos.ciudad
            else
              cli.ciudad_susllantas
          end ciudad,


          case
            when cli.origen="Tiremaxx" then
              case
                when dir_id=99
                  then cli.dir_old
                else
                  CONCAT(dir.nombre, " ", trim(IFNULL(cli.dir_num_ppal,"")), " ", trim(IFNULL(cli.dir_num_casa,"")), " ", trim(IFNULL(cli.dir_adic,"")))
                end
            else
              cli.dir_susllantas
          end direccion,

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





}
