<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
  protected $fillable = [
    'clave',
    'nombre',
    'fecha',
    'otros',
    'direc1',
    'direc2',
    'direc3',
    'documento',
    'tlf1',
    'tlf2',
    'observa',
    'nro_dias',
    'tipo_dir',
    'autorete',
    'descuento',
    'estatus',
    'recargo',
    'modcli',
    'regimen',
    'agenrete',
    'tipo_id',
    'tip_act',
    'direc5',
    's_area',
    'reciproca',
    'act_des',
    'exporta',
    'retener',
    'rete_ica',
    'correo',
    'celular',
    'tipo_guber',
    'foto',
    'website',
    'dpto',
    'digitoveri',
    'apellido1',
    'apellido2',
    'nombre1',
    'nombre2',
    'persona_ju',
    'cuenta_aho',
    'banco_aho',
    'estado',
    'agen_cree',
    'exen_cree',
    'retene_cre',
    'declarante',
    'importa',
    'agerteica',
    'agrteicav',
    'bolagro',
    'autoretica'
  ];

  public function vehiculos()
  {
      return $this->hasMany('App\Vehiculo', 'cliente_id');
  }
}
