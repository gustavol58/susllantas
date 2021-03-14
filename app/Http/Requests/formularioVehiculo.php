<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Vehiculo;

class formularioVehiculo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'placa' => 'required | max:6',
           'marca' => 'required | max:50',
           'modelo' => 'required | numeric',
           'gama' => 'required | max:30',
           'fec_soat' => 'required | date',
           'fec_tecno' => 'required | date',
           'fec_extintor' => 'required | date',
           'kilom' => 'required | numeric',
           'kilom_aceite' => 'required | numeric',
           // 'php_placa' => 'required | max:6',
           // 'php_marca' => 'required | max:50',
           // 'php_modelo' => 'required | numeric',
           // 'php_gama' => 'required | max:30',
           // 'php_fec_soat' => 'required | date',
           // 'php_fec_tecno' => 'required | date',
           // 'php_fec_extintor' => 'required | date',
           // 'php_kilom' => 'required | numeric',
           // 'php_kilom_aceite' => 'required | numeric',
        ];
    }

    public function withValidator($validator){
      //    Que la placa digitada no exista en la
      //    tabla vehículos (al crear) o no exista
      //    en otro registro (al modificar):
      $validator->after(function ($validator) {
           $resul_placa = $this->validar_placa();
           if ($resul_placa !== null) {
               $validator->errors()->add('placa', $resul_placa);
           }
      });
   }

   private function validar_placa(){
     $resul = null;    // null indicará que no hubo problemas
     if ($this->getMethod() == 'POST') {
        // el formrequest fué llamado desde ClienteController@store() - creación
        // o desde OrdenController@crear_tablas_pedir_cais
        $arr_existe = Vehiculo::with('cliente')->where('placa' , $this->placa)->get()->toArray();

        if(count($arr_existe) >= 1){
           $nombre_cliente = $arr_existe[0]['cliente']['razon_social']
               . " " . $arr_existe[0]['cliente']['nombre1']
               . " " . $arr_existe[0]['cliente']['nombre2']
               . " " . $arr_existe[0]['cliente']['apellido1']
               . " " . $arr_existe[0]['cliente']['apellido2'];
           $resul = "La placa digitada ya existe en la base de datos, pertenece al vehículo:  " . $arr_existe[0]['marca'] . ", MODELO: " . $arr_existe[0]['modelo'] . ", GAMA: " . $arr_existe[0]['gama'] . ", del cliente: " . $nombre_cliente ;
        }
     }else{
        // es PUT, llamado desde ClienteController@update o
        // desde OrdenController@modificar_crear_tablas_pedir_cais
        // si $vehiculo_id == 0 debe grabar en vehiculos, en caso
        //    contrario debe modificar el vehiculo vehiculo_id
        $vehiculo_id = $this->route()->parameter('vehi_id');
        if($vehiculo_id == 0){
           // la placa será agregada (es lo mismo que el POST):
           $arr_existe = Vehiculo::with('cliente')->where('placa' , $this->placa)->get()->toArray();

           if(count($arr_existe) >= 1){
              $nombre_cliente = $arr_existe[0]['cliente']['razon_social']
                  . " " . $arr_existe[0]['cliente']['nombre1']
                  . " " . $arr_existe[0]['cliente']['nombre2']
                  . " " . $arr_existe[0]['cliente']['apellido1']
                  . " " . $arr_existe[0]['cliente']['apellido2'];
              $resul = "La placa digitada ya existe en la base de datos, pertenece al vehículo:  " . $arr_existe[0]['marca'] . ", MODELO: " . $arr_existe[0]['modelo'] . ", GAMA: " . $arr_existe[0]['gama'] . ", del cliente: " . $nombre_cliente ;
           }
        }else{
           // la placa será modificada:
           $arr_existe = Vehiculo::with('cliente')->where([['placa' , '=' , $this->placa] , ['id' , '!=' , $vehiculo_id]])->get()->toArray();
           if(count($arr_existe) == 0){
              // la placa no existe, o si existe pertenece al vehículo que
              // se está modificando, o sea que todo está correto.
           }else{
              // la placa pertenece a otro vehículo ya matriculado;
              $nombre_cliente = $arr_existe[0]['cliente']['razon_social']
                  . " " . $arr_existe[0]['cliente']['nombre1']
                  . " " . $arr_existe[0]['cliente']['nombre2']
                  . " " . $arr_existe[0]['cliente']['apellido1']
                  . " " . $arr_existe[0]['cliente']['apellido2'];
              $resul = "La placa digitada pertenece al vehículo:  " . $arr_existe[0]['marca'] . ", MODELO: " . $arr_existe[0]['modelo'] . ", GAMA: " . $arr_existe[0]['gama'] . ", del cliente: " . $nombre_cliente ;
           }
        }
     }
     return $resul;
   }

}
