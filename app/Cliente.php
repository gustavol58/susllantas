<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public function Cod_postal(){
      return $this->belongsTo('App\Cod_postal' , 'cod_postal_id');
   }

    public function Cod_direccion(){
      return $this->belongsTo('App\Cod_direccion' , 'dir_id');
   }

   public function vehiculos()
   {
       return $this->hasMany('App\Vehiculo', 'cliente_id');
   }
}
