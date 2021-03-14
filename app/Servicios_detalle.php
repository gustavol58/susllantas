<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicios_detalle extends Model
{
   public function producto(){
      return $this->belongsTo('App\Producto' , 'producto_id');
   }

   public function operario(){
      return $this->belongsTo('App\User' , 'operario_id');
   }
}
