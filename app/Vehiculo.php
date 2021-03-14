<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
   public function cliente(){
      return $this->belongsTo('App\Cliente', 'cliente_id');
   }
}
