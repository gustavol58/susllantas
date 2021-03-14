<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Producto;

class formularioProducto extends FormRequest
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
           'cai' => 'required | max:15',
           'nombre' => 'required | max:40',
           'costo' => 'required | numeric',
           'precio' => 'required | numeric',
           'marca' => 'required | max:5',
           'linea' => 'required | max:20',
           'clave' => 'required | max:12',
        ];
    }

    public function messages()
    {
        return [
            'clave.required' => 'Debe ingresar una cuenta contable',
            'clave.max'  => 'La cuenta contable no puede tener más de 12 caracteres',
        ];
    }

    public function withValidator($validator){
      //    Que el CAI digitado no exista en la
      //    tabla productos:
      $validator->after(function ($validator) {
           $resul_cai = $this->validar_cai();
           if ($resul_cai !== null) {
               $validator->errors()->add('cai', $resul_cai);
           }
      });
   }

   private function validar_cai(){
     $resul = null;    // null indicará que no hubo problemas
     if ($this->getMethod() == 'POST') {
        // el formrequest fué llamado desde store() - creación
        if(Producto::where('cai' , $this->cai)->exists()){
           $resul = "El CAI digitado ya existe en la base de datos.";
        }
     }else{
        $producto_id = $this->route()->parameter('producto');;
// dd($miid . "___" . $this->doc_num);
        // fue llamado desde update() - modificación
        $arr_existe = Producto::where([['cai' , '=' , $this->cai] , ['id' , '!=' , $producto_id]])->get()->toArray();
        if(count($arr_existe) == 0){
           // el cai no existe, o si existe pertenece al producto que
           // se está modificando, o sea que todo está correto.
        }else{
           // el cai digitado pertenece a otro producto ya matriculado;
           $resul = "El CAI digitado pertenece al producto:  ". $arr_existe[0]['nombre'] ;
        }
     }
     return $resul;
   }

}
