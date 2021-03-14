<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Menu_rol extends Model
{
   protected $table = 'menus_roles';

   public static function menus_roles()
   {
      // devuelve los menus_roles (permisos) al AppServiceProvider, el cual
      // los enviará a la vista app.blade.php, en este formato:
      //    un array que tendrá tantas filas como registros tenga la tabla
      //    menus_roles, con un columna llamada "permiso" y en la cual está
      //    el rol y el id menu separados por un _, ejemplo: adm_18 , pat_13, ....
      $menus_rol = new Menu_rol();
      $arr_permisos = $menus_rol->optionsMenu_rol();
      $arr_resul = [];
      foreach($arr_permisos as $fila){
         $arr_resul[] = $fila["permiso"];
      }
      return $arr_resul;
   }

   public function optionsMenu_rol(){
      return $this->select(DB::raw('concat(rol,"_",menu_id) permiso'))->get()->toArray();
   }
}
