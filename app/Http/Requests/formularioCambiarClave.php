<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class formularioCambiarClave extends FormRequest
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
          'php_clave_actual' => 'required | max:30',
          'php_clave_nueva1' => 'required | max:30',
          'php_clave_nueva2' => 'required | max:30 | in_array: "php_clave_nueva1"',
      ];
    }

    public function messages(){
      return [
              'php_clave_actual.required' => 'No se digitó la clave actual',
              'php_clave_actual.max' => 'La clave actual no puede tener más de 30 caracteres.',
              'php_clave_nueva1.required' => 'No se digitó la clave nueva',
              'php_clave_nueva1.max' => 'La clave nueva no puede tener más de 30 caracteres.',
              'php_clave_nueva2.required' => 'No se volvió a digitar la clave nueva',
              'php_clave_nueva2.max' => 'La clave repetida no puede tener más de 30 caracteres.',
              'php_clave_nueva2.in_array' => 'La nueva clave se debe digitar dos veces.',
          ];
   }
}
