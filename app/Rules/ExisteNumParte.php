<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Producto;

class ExisteNumParte implements Rule
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
      // Verificar que no se digite un número de parte ya grabado en la tabla:
      // Si se está agregando un registro, verificar que el número de parte
      // no esté en otro registro de la tabla
      if($this->id == 'es_agregar'){
        $buscarNroParte = Producto::where('num_parte',  $value)->count();
      }else{
        // Si se está modificando un registro, verificar que el número de parte
        // no esté en otro registro de la tabla QUE NO SEA el propio registro:
        $buscarNroParte = Producto::where([['num_parte', '=', $value] , ['id', '<>', $this->id]])->count();
      }

      if($buscarNroParte == 0){
        // el nro de parte no está en otro registro:
        return true;
      }else{
        // el nro_parte ya está grabado en otro registro que no es
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
        return 'El número de parte ya está digitado en otro registro. No se puede repetir.';
    }
}
