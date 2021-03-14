<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use Redirect,Response,DB,Config;
use Datatables;
use App\Rules\ExisteNumParte;
use App\Http\Requests\formularioProducto;
use Auth;

class ProductoController extends Controller
{
    // constructor para evitar el ingreso de usuarios no logueados
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
      // muestra los productos en datatable y permite las opciones crud
      return view('productos_listar');
    }


    public function create()
    {
        // llamada por el botón CREAR PRODUCTOS de la vista productos_listar.blade.php
        return view('productos.create');
    }

    public function store(formularioProducto $request){
      // llamada POST desde la vista create.blade.php

      // si llega aquí es porque las validaciones del formRequest
      // formularioCliente fueron correctas. Si hubiera sucedido
      // lo contrario, regresaria al 'error' del ajax
      $data = $request->all();
   // dd($data);
      // campos que no son pedidos al usuario o que debe ser
      // obtenidos por programación:
      $unidad = "UND";
      $fec_hoy = date("Y-m-d");
      $fec_hora_hoy = date("Y-m-d H:i:s");

      // checkboxes IVA:
      // Recordar que si algún checkbox  fue activado llegará aqui con el
      // value="1", pero si no fué seleccionado, no llegará en el POST,
      // por lo tanto:
      if(isset($data['iva_ventas'])) {
         $iva_ventas = $data['iva_ventas'];
      }else{
         $iva_ventas = false;
      }
      if(isset($data['iva_compras'])) {
         $iva_compras = $data['iva_compras'];
      }else{
         $iva_compras = false;
      }
      if(isset($data['iva_dif'])) {
         $iva_dif = $data['iva_dif'];
      }else{
         $iva_dif = false;
      }
      $user_id = Auth::user()->id;

      // inserta el registro en la tabla productos
      $id_agregado = DB::table('productos')->insertGetId(
         [
            'cai' => strtoupper($data['cai']),
            'nombre' => strtoupper($data['nombre']),
            'costo' => $data['costo'],
            'precio' => $data['precio'],
            'marca' => strtoupper($data['marca']),
            'linea' => strtoupper($data['linea']),
            'iva_ventas' => $iva_ventas,
            'iva_compras' => $iva_compras,
            'iva_dif' => $iva_dif,
            'unidad' => $unidad,
            'clave' => $data['clave'],
            'user_id' => $user_id,
            'created_at' => $fec_hora_hoy
         ]
      );   // fin del DB::table insert

      $nom_producto = $data['nombre'];
      $msg = 'Grabación correcta. Producto agregado: '.strtoupper($nom_producto);
      $arr = array('msg' => $msg  , 'status' => true, 'error' => '');

      // retorna al sucees del ajax de la función validar_grabar_nuevo_producto():
      return Response()->json($arr);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
      // llamado por el botón EDIT de algún registro en la vista productos-listar.blade.
      $producto=Producto::find($id);
      return view('productos.edit',['producto' => $producto]);
    }

    public function update(formularioProducto $request, $id)
    {
      // llamada POST (PUT-PATCH) desde la vista edit.blade.php

      // si llega aquí es porque las validaciones del formRequest
      // formularioCliente fueron correctas. Si hubiera sucedido
      // lo contrario, regresaria al 'error' del ajax

      // Como la validación fue correcta actualizará en la b.d. y regresará
      // al 'success' del ajax:
      $data = request()->all();

      $unidad = "UND";
      $fec_hora_hoy = date("Y-m-d H:i:s");

      // checkboxes IVA:
      // Recordar que si algún checkbox  fue activado llegará aqui con el
      // value="1", pero si no fué seleccionado, no llegará en el POST,
      // por lo tanto:
      if(isset($data['iva_ventas'])) {
         $iva_ventas = $data['iva_ventas'];
      }else{
         $iva_ventas = false;
      }
      if(isset($data['iva_compras'])) {
         $iva_compras = $data['iva_compras'];
      }else{
         $iva_compras = false;
      }
      if(isset($data['iva_dif'])) {
         $iva_dif = $data['iva_dif'];
      }else{
         $iva_dif = false;
      }
      $user_id = Auth::user()->id;

      DB::table('productos')
        ->where('id', $id)
        ->update(
           [
             'cai' => strtoupper($data['cai']),
             'nombre' => strtoupper($data['nombre']),
             'costo' => $data['costo'],
             'precio' => $data['precio'],
             'marca' => strtoupper($data['marca']),
             'linea' => strtoupper($data['linea']),
             'iva_ventas' => $iva_ventas,
             'iva_compras' => $iva_compras,
             'iva_dif' => $iva_dif,
             'unidad' => $unidad,
             'clave' => $data['clave'],
             'user_id' => $user_id,
             'updated_at' => $fec_hora_hoy
           ]
        );   // fin del table update_at

        $nom_producto = $data['nombre'];
        $msg = 'Modificación correcta del producto: '.strtoupper($nom_producto);
        $arr = array('msg' => $msg  , 'status' => true, 'error' => '');

        // retorna al sucees del ajax de la función validar_grabar_nuevo_producto():
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
      $producto = Producto::where('id',$id)->delete();

      $arr = array('msg' => 'Error !!!', 'status' => false);
      if($producto){
        $arr = array('msg' => 'El registro fue eliminado correctamente', 'status' => true);
      }
      return Response()->json($arr);
    }

    public function listarProductosJquery(){
      // método jquery para mostrar los productos usando datatables
        $productos = DB::table('productos')->select('*');
        return datatables()->of($productos)
            ->make(true);
    }

}
