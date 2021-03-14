<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Cliente;

class formularioCliente extends FormRequest
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
           'doc_tipo' => 'required',
           'doc_num' => 'required',
           'juridica' => 'required',
           'declarante' => 'required',
           'dpto' => 'required',
           'dir_ppal' => 'required',
           'email' => 'required',
           'cumple_dia' => 'required|numeric|min:1|max:31',
           'cumple_mes' => 'required',
           'tel_fijo' => 'nullable|numeric',
           'tel_celu' => 'nullable|numeric',
        ];
    }

    public function withValidator($validator)
    {
        // 0) Que el numero de documento digitado no exista en la
        //    tabla clientes:
        $validator->after(function ($validator) {
            $resul_doc_num = $this->validar_doc_num();
            if ($resul_doc_num !== null) {
                $validator->errors()->add('doc_num', $resul_doc_num);
            }
        });

        //  1) Que si el tipo docu es nit o nit extranjeria, exista el DV
        $validator->after(function ($validator) {
            if (! $this->validar_dv()) {
                $validator->errors()->add('doc_dv', 'El DV no puede estar vacio.');
            }
        });

        // 2) Que si no hay razón social, haya mínimo nombre1 y apellido1, y
        //    que si hay razón social no haya ni nombre1, nombre2, apellido1 ni apellido2
        $validator->after(function ($validator) {
            $resultado = $this->validar_nombres();
            if ($resultado !== null) {
               $validator->errors()->add('nombre1', $resultado);
            }
        });

        // 3) Si el grupo de dir_ppal es 1: obligatoria num carrera y num casa
        //    sino: obliatorio dir_adic
        $validator->after(function ($validator) {
            $resultado = $this->validar_direccion();
            if ($resultado !== null) {
                $validator->errors()->add('dir_ppal', $resultado);
            }
        });

        // 4) Si fijo y celular están vacios: Muestra error.
        $validator->after(function ($validator) {
            if (! $this->validar_telefonos()) {
                $validator->errors()->add('tel_fijo', 'Debe escribir un teléfono fijo un teléfono celular, o ambos.');
            }
        });
    }

    private function validar_doc_num(){
      $resul = null;    // null indicará que no hubo problemas
      if ($this->getMethod() == 'POST') {
         // el formrequest fué llamado desde ClienteController@store() - creación
         // o desde OrdenController@crear_tablas_pedir_cais
         if(Cliente::where('doc_num' , $this->doc_num)->exists()){
            $resul = "El número de documento digitado ya existe en la base de datos.";
         }
      }else{
         // es PUT, llamado desde ClienteController@update o
         // desde OrdenController@modificar_tablas_pedir_cais
         $cliente_id = $this->route()->parameter('cliente');
// dd($miid . "___" . $this->doc_num);
         // tetMethod() es PUT: fue llamado desde update() - modificación
         $arr_existe = Cliente::where([['doc_num' , '=' , $this->doc_num] , ['id' , '!=' , $cliente_id]])->get()->toArray();
         if(count($arr_existe) == 0){
            // el docu_num no existe, o si existe pertenece al cliente que
            // se está modificando, o sea que todo está correto.
         }else{
            // el docu_num digitado pertenece a otro cliente ya matriculado;
            // $resul = "'El número de documento digitado pertenece al cliente:  " . $arr_existe['razon_social'] . $arr_existe['nombre1']. $arr_existe['nombre2']. $arr_existe['apellido1']. $arr_existe['apellido2'];
            $resul = "El número de documento digitado pertenece al cliente:  ". $arr_existe[0]['razon_social'] . $arr_existe[0]['nombre1'] . " " . $arr_existe[0]['nombre2'] . " " . $arr_existe[0]['apellido1'] . " " . $arr_existe[0]['apellido2'];
         }
      }
      return $resul;
    }

    private function validar_dv(){
      if($this->doc_tipo == 3 || $this->doc_tipo == 4){
         if($this->doc_dv == null){
            return false;
         }
      }
      return true;
    }

    private function validar_nombres(){
      $resultado = null;
      if($this->razon_social == null){
         if($this->nombre1 == null || $this->apellido1 == null){
            $resultado = "Debe escribir el primer nombre y el primer apellido, o una razón social.";
         }
      }else{
         if( !($this->nombre1 == null
               && $this->nombre2 == null
               && $this->apellido1 == null
               && $this->apellido2 == null)){
                  $resultado = "Si escribió una razón social, no puede escribir nombres ni apellidos.";
         }
      }
      return $resultado;
   }

   private function validar_direccion(){
      $resultado = null;
      $grupo = substr($this->dir_ppal , -1);
      if($grupo == 1){
         if($this->dir_num_ppal == null ||$this->dir_num_casa == null){
            $resultado = "Debe escribir el número de la dirección y el número de la casa.";
         }
      }else{
         if($this->dir_adic == null){
            $resultado = "Debe escribir los datos adicionales de la dirección.";
         }
      }
      return $resultado;
   }

   private function validar_telefonos(){
     if($this->tel_fijo == null && $this->tel_celu == null ){
        return false;
     }
     return true;
   }


}
