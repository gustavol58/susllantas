<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Vehiculo;
use App\Producto;

class ServicioController extends Controller
{
    public function index(){
      return view ('servicios.formu');
    }

    public function buscarClientes(Request $request){
          $search = $request->get('term');
          $result = Cliente::select('nombre','id')->where('nombre', 'LIKE', '%'. $search. '%')->orderBy('nombre')->get();

          return response()->json($result);
    }
}
