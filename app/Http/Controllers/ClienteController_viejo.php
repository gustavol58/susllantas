<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use Redirect,Response,DB,Config;
use Datatables;
use App\Rules\ExisteClaveCliente;

class Cliente_viejoController extends Controller
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

    public function store(Request $request)
    {
      // llamada POST desde la vista clientes/create.blade.php
      $validatedData = $request->validate([
        'clave' => ['required', 'max:5' , new ExisteClaveCliente('es_agregar')],
        'nombre' => 'max:80',
        'fecha' => 'nullable|date',
        'otros' => 'max:30',
        'direc1' => 'max:25',
        'direc2' => 'max:25',
        'direc3' => 'max:25',
        'documento' => 'max:25',
        'tlf1' => 'max:15',
        'tlf2' => 'max:15',
        'observa' => 'max:60',
        'nro_dias' => 'nullable|numeric',
        'tipo_dir' => 'max:1',
        'autorete' => 'max:1',
        'descuento' => 'nullable|numeric',
        'estatus' => 'max:2',
        'recargo' => 'max:1',
        'modcli' => 'max:1',
        'regimen' => 'max:1',
        'agenrete' => 'max:1',
        'tipo_id' => 'max:2',
        'tip_act' => 'max:3',
        'direc5' => 'max:200',
        's_area' => 'max:6',
        'reciproca' => 'max:1',
        'act_des' => 'max:1',
        'exporta' => 'max:1',
        'retener' => 'max:1',
        'rete_ica' => 'max:1',
        'correo' => 'max:80',
        'celular' => 'max:12',
        'tipo_guber' => 'max:1',
        'foto' => 'max:100',
        'website' => 'max:100',
        'dpto' => 'max:50',
        'digitoveri' => 'nullable|numeric',
        'apellido1' => 'max:20',
        'apellido2' => 'max:20',
        'nombre1' => 'max:20',
        'nombre2' => 'max:20',
        'persona_ju' => 'max:1',
        'cuenta_aho' => 'max:20',
        'banco_aho' => 'max:30',
        'estado' => 'max:1',
        'agen_cree' => 'max:1',
        'exen_cree' => 'max:1',
        'retene_cre' => 'max:1',
        'declarante' => 'max:1',
        'importa' => 'max:1',
        'agerteica' => 'max:1',
        'agrteicav' => 'max:1',
        'bolagro' => 'max:1',
        'autoretica' => 'max:1',
      ]);
      // Si esta validación no fue correcta, regresará al 'error' del ajax

      // si la validación fue correcta grabará en la b.d. y regresará
      // al 'success' del ajax:
      $data = $request->all();
      $cliente = Cliente::create($data);
      $arr = array('msg' => 'Error !!!', 'status' => false);
      if($cliente){
        $arr = array('msg' => 'El cliente fue agregado correctamente', 'status' => true);
      }
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
      $cliente=Cliente::find($id);
      return view('clientes.edit',['cliente' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // llamada POST (PUT-PATCH) desde la vista edit.blade.php
      $validatedData = $request->validate([
        'clave' => ['required', 'max:5' , new ExisteClaveCliente($id)],
        'nombre' => 'max:80',
        'fecha' => 'nullable|date',
        'otros' => 'max:30',
        'direc1' => 'max:25',
        'direc2' => 'max:25',
        'direc3' => 'max:25',
        'documento' => 'max:25',
        'tlf1' => 'max:15',
        'tlf2' => 'max:15',
        'observa' => 'max:60',
        'nro_dias' => 'nullable|numeric',
        'tipo_dir' => 'max:1',
        'autorete' => 'max:1',
        'descuento' => 'nullable|numeric',
        'estatus' => 'max:2',
        'recargo' => 'max:1',
        'modcli' => 'max:1',
        'regimen' => 'max:1',
        'agenrete' => 'max:1',
        'tipo_id' => 'max:2',
        'tip_act' => 'max:3',
        'direc5' => 'max:200',
        's_area' => 'max:6',
        'reciproca' => 'max:1',
        'act_des' => 'max:1',
        'exporta' => 'max:1',
        'retener' => 'max:1',
        'rete_ica' => 'max:1',
        'correo' => 'max:80',
        'celular' => 'max:12',
        'tipo_guber' => 'max:1',
        'foto' => 'max:100',
        'website' => 'max:100',
        'dpto' => 'max:50',
        'digitoveri' => 'nullable|numeric',
        'apellido1' => 'max:20',
        'apellido2' => 'max:20',
        'nombre1' => 'max:20',
        'nombre2' => 'max:20',
        'persona_ju' => 'max:1',
        'cuenta_aho' => 'max:20',
        'banco_aho' => 'max:30',
        'estado' => 'max:1',
        'agen_cree' => 'max:1',
        'exen_cree' => 'max:1',
        'retene_cre' => 'max:1',
        'declarante' => 'max:1',
        'importa' => 'max:1',
        'agerteica' => 'max:1',
        'agrteicav' => 'max:1',
        'bolagro' => 'max:1',
        'autoretica' => 'max:1',
      ]);
      // Si esta validación no fue correcta, regresará al 'error' del ajax

      // si la validación fue correcta actualizará en la b.d. y regresará
      // al 'success' del ajax:
      $data = request()->except(['_token','_method']);
      $cliente = Cliente::whereId($id)->update($data);
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
        $clientes = Cliente::all();
        return datatables()->of($clientes)
            ->make(true);
    }
}
