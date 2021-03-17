<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehiculo;
use App\Cliente;
use Redirect,Response,DB,Config;
use Datatables;
use App\Http\Requests\formularioVehiculo;
use Auth;

class VehiculoController extends Controller
{
    // constructor para evitar el ingreso de usuarios no logueados
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
      // muestra TODOS los vehículos en datatable y permite las opciones crud
      return view('vehiculos_listar');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //   // llamada por el botón CREAR VEHÍCULOS de la vista productos_listar.blade.php
    //   return view('vehiculos.create');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //   // llamada POST desde la vista create.blade.php
    //   $validatedData = $request->validate([
    //     'placa' => 'required|max:6',
    //     'marca' => 'required|max:50',
    //     'modelo' => 'nullable|numeric',
    //     // 'gama' => 'required|max:20',
    //   ]);
    //   // Si esta validación no fue correcta, regresará al 'error' del ajax
    //
    //   // si la validación fue correcta grabará en la b.d. y regresará
    //   // al 'success' del ajax:
    //
    //   // NOTA 09oct2019: Mientras se implementa la relación con los
    //   // clientes, habrá que poner el código de cliente 4 a todos
    //   // los registros que se agreguen por ahora:
    //   // $data = array_merge($request->all(), ['cliente_id' => 4]);
    //   // CUANDO ESTÉ LISTA LA RELACIÓN CLIENTES-VEHÍCULOS, descomentariar
    //   // la siguiente instrucción (y comentariar la anterior)
    //   $data = $request->all();
    //
    //   $vehiculo = Vehiculo::create($data);
    //   $arr = array('msg' => 'Error !!!', 'status' => false);
    //   if($vehiculo){
    //     $arr = array('msg' => 'El vehículo fue agregado correctamente', 'status' => true);
    //   }
    //   return Response()->json($arr);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //   // llamado por el botón EDIT de algún registro en la vista vehiculos_listar.blade.
    //   $vehiculo=Vehiculo::find($id);
    //   return view('vehiculos.edit',['vehiculo' => $vehiculo]);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //   // llamada POST (PUT-PATCH) desde la vista edit.blade.php
    //   $validatedData = $request->validate([
    //     'placa' => 'required|max:6',
    //     'marca' => 'required|max:50',
    //     'modelo' => 'nullable|numeric',
    //   ]);
    //   // Si esta validación no fue correcta, regresará al 'error' del ajax
    //
    //   // si la validación fue correcta actualizará en la b.d. y regresará
    //   // al 'success' del ajax:
    //   $data = request()->except(['_token','_method']);
    //   $vehiculo = Vehiculo::whereId($id)->update($data);
    //   $arr = array('msg' => 'Error !!!', 'status' => false);
    //   if($vehiculo){
    //     $arr = array('msg' => 'Los cambios fueron grabados correctamente', 'status' => true);
    //   }
    //   return Response()->json($arr);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $vehiculo = Vehiculo::where('id',$id)->delete();

      $arr = array('msg' => 'Error !!!', 'status' => false);
      if($vehiculo){
        $arr = array('msg' => 'El registro fue eliminado correctamente', 'status' => true);
      }
      return Response()->json($arr);
    }

    // lista TODOS los vehículos:
    public function listarVehiculosJquery(){
      // Para mostrar los TODOS vehículos  usando datatables
      // $vehiculos = Vehiculo::all();
      $vehiculos = Vehiculo::with('cliente')->get();
      return datatables()->of($vehiculos)
            ->make(true);
    }

    // Llama la vista inicial que llamará luego a la
    // datatable de vehículos de un cliente escogido:
    public function listarVehiUnCliente($cliente_id){
      // muestra los vehículos en datatable y permite las opciones crud,
      // obtiene el nombre del cliente para enviarlo a la vista y colocarlo
      // en el título de la misma:
      // nota: el dbraw exige manejo bindign para prevenir inyección sql,
      //       pero la variable aqui usada se maneja con query builder, el
      //       cual ya trae la prevención de inyección sql:
      $coll_cliente_nombre = Cliente::select(DB::raw('concat(razon_social," ",nombre1," ",nombre2," ",apellido1," ",apellido2) nombre'))->where('id' , $cliente_id)->get();
      $cliente_nombre = $coll_cliente_nombre->toArray()[0]['nombre'];
   
      return view('vehi_listar_uncliente' , [
        'cliente_id' => $cliente_id ,
        'cliente_nombre' => $cliente_nombre ,
      ]);
    }

    // Crea la datatable con los vehículos de un cliente escogido:
    public function listarVehiUnClienteJquery($cliente_id){
      // Para mostrar los vehículos DE UN CLIENTE usando datatables (también
      // enviará a la vista todos los datos del cliente relacionado a ese
      // vehículo)
      $vehiculos = Vehiculo::with('cliente')->where('cliente_id' , $cliente_id )->get();
      return datatables()->of($vehiculos)
            ->make(true);
    }

    public function createVehiUnCliente($cliente_id , $cliente_nombre)
    {
      // llamada por el botón CREAR VEHÍCULOS de la vista productos_listar.blade.php
      return view('vehiculos.create_uncliente',[
        'cliente_id' => $cliente_id,
        'cliente_nombre' => $cliente_nombre,
      ]);
    }

    public function storeVehiUnCliente(formularioVehiculo $request, $cliente_id){
      // llamada POST desde la vista

      // si llega aquí es porque las validaciones del formRequest
      // formularioVehiculo fueron correctas. Si hubiera sucedido
      // lo contrario, regresaria al 'error' del ajax
      $data = $request->all();

      // el proceso de creación en la tabla vehículos, se hace con la función llamada
      // a continuación (esto se debe a que el proceso también es llamado
      // desde la función OrdenController@crear_tablas_pedir_cais):
      $objCompartido = new CompartidoController();
      $arr_creacion_vehiculo = $objCompartido->crear_vehiculo($data , $cliente_id);
      // el array recibido tendrá 3 columnas:
      //       msg         el mensaje resultante
      //       status      true agregó el vehículo, false no pudo agregar
      //       vehiculo_id  el id del vehículo agregado
      //       error       siempre llegará una cadena vacia

      // retorna al sucees del ajax :
      return Response()->json($arr_creacion_vehiculo);
    }

    public function editVehiUnCliente($cliente_nombre , $vehi_id)
    {
      // llamada por el botón MODIFICAR un vehículo de un cliente escogido,
      // en la vista views/vehiculos/edit_uncliente.blade.php
      $vehiculo=Vehiculo::find($vehi_id);
      return view('vehiculos.edit_uncliente',[
        'vehiculo' => $vehiculo,
        'cliente_nombre' => $cliente_nombre,
      ]);
    }

    public function updateVehiUnCliente(formularioVehiculo $request, $id){
      // llamada POST (PUT-PATCH) desde la vista edit_uncliente.blade.php

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
      $msg = $objCompartido->actualizar_vehiculo($data , $id);

      if(strlen($msg) !== 0){
         $arr = array('msg' => $msg  , 'status' => true, 'error' => '');
      }else{
         $arr = array('msg' => $msg  , 'status' => false, 'error' => $msg);
      }
      return Response()->json($arr);

    }

}
