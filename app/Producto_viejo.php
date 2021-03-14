<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
  protected $fillable = [
    'codigo',
    'num_parte',
    'nombre',
    'ubicacion',
    'precio_a',
    'precio_b',
    'iva',
    'clase',
    'iva_dif',
    'unidad',
    'aux_1',
    'consu_c',
    'iva_difs',
    'marca',
 ];
}
