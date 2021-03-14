<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Cliente;

class ExisteClaveCliente implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
     public function __construct($id)
     {
         $this->id = $id;
     }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      // Verificar que no se digite una clave ya grabado en la tabla:
      // Si se está agregando un registro, verificar que la clave
      // no esté en otro registro de la tabla
      if($this->id == 'es_agregar'){
        $buscarClave = Cliente::where('clave',  $value)->count();
      }else{
        // Si se está modificando un registro, verificar que la clave
        // no esté en otro registro de la tabla QUE NO SEA el propio registro:
        $buscarClave = Cliente::where([['clave', '=', $value] , ['id', '<>', $this->id]])->count();
      }

      if($buscarClave == 0){
        // la clave no está en otro registro:
        return true;
      }else{
        // la clave ya está grabado en otro registro que no es
        // el que se está modificando, no se cumplió con la regla:
        return false;
      }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La clave ya está grabada para otro cliente. No se puede repetir.';
    }
}
