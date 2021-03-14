<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use Redirect,Response,DB,Config;
use Datatables;
use App\Rules\ExisteNumParte;

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

    public function store(Request $request){
      // llamada POST desde la vista create.blade.php
      $validatedData = $request->validate([
        'codigo' => 'required|max:12',
        'num_parte' => ['required', 'max:15' , new ExisteNumParte('es_agregar')],
        'nombre' => 'required|max:40',
        'ubicacion' => 'required|max:6',
        'precio_a' => 'required|numeric',
        'precio_b' => 'required|numeric',
        'iva' => 'required|max:1',
        'clase' => 'required|max:1',
        'iva_dif' => 'required|max:1',
        'unidad' => 'max:3',
        'aux_1' => 'max:20',
        'consu_c' => 'max:1',
        'iva_difs' => 'max:1',
        'marca' => 'max:5',
      ]);
      // Si esta validación no fue correcta, regresará al 'error' del ajax

      // si la validación fue correcta grabará en la b.d. y regresará
      // al 'success' del ajax:
      $data = $request->all();
      // $result = Producto::insert($data);
      $producto = Producto::create($data);
      $arr = array('msg' => 'Error !!!', 'status' => false);
      if($producto){
        $arr = array('msg' => 'El producto fue agregado correctamente', 'status' => true);
      }
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

    public function update(Request $request, $id)
    {
      // llamada POST (PUT-PATCH) desde la vista edit.blade.php
      $validatedData = $request->validate([
        'codigo' => 'required|max:12',
        'num_parte' => ['required', 'max:15' , new ExisteNumParte($id)],
        'nombre' => 'required|max:40',
        'ubicacion' => 'required|max:6',
        'precio_a' => 'required|numeric',
        'precio_b' => 'required|numeric',
        'iva' => 'required|max:1',
        'clase' => 'required|max:1',
        'iva_dif' => 'required|max:1',
        'unidad' => 'max:3',
        'aux_1' => 'max:20',
        'consu_c' => 'max:1',
        'iva_difs' => 'max:1',
        'marca' => 'max:5',
      ]);
      // Si esta validación no fue correcta, regresará al 'error' del ajax

      // si la validación fue correcta actualizará en la b.d. y regresará
      // al 'success' del ajax:
      $data = request()->except(['_token','_method']);
      $producto = Producto::whereId($id)->update($data);
      $arr = array('msg' => 'Error !!!', 'status' => false);
      if($producto){
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
