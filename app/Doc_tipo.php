<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doc_tipo extends Model
{
    public function clientes(){
      return $this->hasMany('App\Cliente','doc_tipo_id');
    }
}
