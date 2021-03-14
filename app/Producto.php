<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
   public function servicios(){
      return $this->hasMany('App\Servicio_detalle' , 'producto_id');
   }
}
