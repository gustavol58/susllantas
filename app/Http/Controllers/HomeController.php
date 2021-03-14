<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\formularioCambiarClave;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function formu_cambiar_clave(){
      return view('cambiar_clave');
   }

   public function grabar_nueva_clave(formularioCambiarClave $request){
     // Llamada POST por el ajax desde el app.blade.php cuando el usuario
     // presiona el botón CAMBIAR CLAVE en el formulario modal.

     // si llega aquí es porque las validaciones del formRequest
     // formularioCliente fueron correctas. Si hubiera sucedido
     // lo contrario, regresaria al 'error' del ajax

     // Como la validación fue correcta actualizará en la b.d. y regresará
     // al 'success' del ajax:
     $data = request()->all();

     // campos que no son pedidos al usuario o que debe ser
     // obtenidos por programación:
     $fec_hora_hoy = date("Y-m-d H:i:s");
     $user_id = Auth::user()->id;
     $user_pass = Auth::User()->password;

     // primero verifica si la clave_actual digitada es correcta:
     if(Hash::check($data['php_clave_actual'], $user_pass)){
        // las claves coinciden:
        // Como la clave_actual es correcta, procede a actualizar:
        DB::table('users')
          ->where('id', $user_id)
          ->update(
             [
               'password' => Hash::make($data['php_clave_nueva1']),
               'updated_at' => $fec_hora_hoy
             ]
          );
          $msg = "La clave fue cambiada.";
          $arr = array('msg' => $msg  , 'status' => true);
     }else{
        // la clave_actual digitada no es correcta:
        $msg = "No se pudo cambiar la clave. Por favor revise los datos suministrados";
        $arr = array('msg' => $msg  , 'status' => false);
     }
     // retorna al success del ajax:
     return Response()->json($arr);
  }     // fin de la función grabar_nueva_clave()
}
