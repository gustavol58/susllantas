<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Cliente;
use App\Cod_postal;
use App\Param;
use Auth;
use DB;

// class CompartidoController extends Controller
class CompartidoController extends Controller
{
    public function actualizar_cliente($data , $cliente_id){
      $msg = "";
      $fec_hora_hoy = date("Y-m-d H:i:s");
      // para obtener el campo dir_id:
      $direccion_id =  substr($data['dir_ppal'],0,strpos($data['dir_ppal'],"@"));
      // 2 instrucciones comentariadas porque se decidió grabar la dirección repartida en
      // cuatro campos, y no solo en uno solo como se pensaba al principio
      // $direccion_nombre =  Cod_direccion::select('nombre')->find($direccion_id);
      // $direccion = $direccion_nombre->nombre . " " . strtoupper($data['dir_num_ppal']) . " " . strtoupper($data['dir_num_casa']) . " " . strtoupper($data['dir_adic']);
      // obtener el id del código postal a partir del dpto-ciudad escogidos
      $ciudad_id = $data['ciudad'];
      $col_cod_postal_id = Cod_postal::where('divipol',$ciudad_id)->get();
      $cod_postal_id = $col_cod_postal_id[0]['id'];
      // otra manera de hacer lo anterior seria mas sencillo:
      // $cod_postal_id = Cod_postal::select('id')->where('divipol','05091')->first();

      // si el checkbox de habeas_data fue activado llegará aqui con el
      // value="1", pero si no fué seleccionado, no llegará en el POST,
      // por lo tanto:
      if(isset($data['habeas_data'])) {
         $habeas_data = $data['habeas_data'];
      }else{
         $habeas_data = false;
      }
      // para convertir el número de mes en sus tres primeras letras:
      // 13nov2019: se decidió que en el select se pondrían directamente
      // las tres primeras letras del mes, por eso los siguientes comentarios:
      // $arr_meses_3 = ['' , 'ENE' , 'FEB' , 'MAR' , 'ABR' , 'MAY' , 'JUN' , 'JUL' , 'AGO' , 'SEP' , 'OCT' , 'NOV' , 'DIC' ];
      // $nom_mes = $arr_meses_3[$data['cumple_mes']];
      // usuario logueado:
      $user_id = Auth::user()->id;

      DB::table('clientes')
        ->where('id', $cliente_id)
        ->update(
           [
             'doc_tipo_id' => $data['doc_tipo'],
             'doc_num' => $data['doc_num'],
             'doc_dv' => $data['doc_dv'],
             'juridica' => $data['juridica'],
             'declarante' => $data['declarante'],
             'nombre1' => strtoupper($data['nombre1']),
             'nombre2' => strtoupper($data['nombre2']),
             'apellido1' => strtoupper($data['apellido1']),
             'apellido2' => strtoupper($data['apellido2']),
             'razon_social' => strtoupper($data['razon_social']),
             'dir_id' => $direccion_id,
             'dir_num_ppal' => strtoupper($data['dir_num_ppal']),
             'dir_num_casa' => strtoupper($data['dir_num_casa']),
             'dir_adic' => strtoupper($data['dir_adic']),
             'cod_postal_id' => $cod_postal_id,
             'cod_postal_escogido' => $data['cod_postal_escogido'],             
             'email' => $data['email'],
             'tel_fijo' => $data['tel_fijo'],
             'tel_celu' => $data['tel_celu'],
             'habeas_data' => $habeas_data,
             'cumple_dia' => $data['cumple_dia'],
             'cumple_mes' => $data['cumple_mes'],
             'contacto' => $data['contacto'],
             'user_id' => $user_id,
             'updated_at' => $fec_hora_hoy
           ]
        );

        // si el cliente autorizo el habeas data, renombra el archivo de firma
        // para que no quede como docu_<num_docu>.png sino como <idcliente>.png:
        // if($habeas_data){
        //    $baseUrl = public_path('img\habeas_data\\');
        //    $firma_ant = $baseUrl."docu_".$data['doc_num'].".png";
        //    $firma_nueva = $baseUrl.$id.".png";
        //    rename($firma_ant , $firma_nueva);
        // }
      $msg = 'Edición correcta. Fueron modificados los datos del cliente.';
      return $msg;
   }

   public function actualizar_vehiculo($data , $vehiculo_id){
      $msg = "";
      // campos que no son pedidos al usuario o que debe ser
      // obtenidos por programación:
      $fec_hora_hoy = date("Y-m-d H:i:s");
      $user_id = Auth::user()->id;

      DB::table('vehiculos')
        ->where('id', $vehiculo_id)
        ->update(
           [
             'placa' => strtoupper($data['placa']),
             'marca' => strtoupper($data['marca']),
             'modelo' => $data['modelo'],
             // 'cliente_id' => $data['precio'],
             'gama' => strtoupper($data['gama']),
             'fec_soat' => $data['fec_soat'],
             'fec_tecno' => $data['fec_tecno'],
             'fec_extintor' => $data['fec_extintor'],
             'kilom' => $data['kilom'],
             'kilom_aceite' => $data['kilom_aceite'],
             'user_id' => $user_id,
             'updated_at' => $fec_hora_hoy
           ]
        );   // fin del table update_at

        $placa = $data['placa'];
        $msg = 'Modificación correcta del vehículo: '.strtoupper($placa);
        return $msg;
   }

   public function crear_cliente($data){
      // 22ene2020:
      $arr_resul = [];
      // antes de grabar, obtiene la clave Wimax para el cliente:
      $clave = $this->generar_clave_wimax();

      // campos que no son pedidos al usuario o que debe ser
      // obtenidos por programación:
      $estado = "A";
      $fec_hoy = date("Y-m-d");
      $fec_hora_hoy = date("Y-m-d H:i:s");
      // para obtener el campo dir_id:
      $direccion_id =  substr($data['dir_ppal'],0,strpos($data['dir_ppal'],"@"));
      // obtener el id del código postal a partir del dpto-ciudad escogidos
      $ciudad_id = $data['ciudad'];
      $col_cod_postal_id = Cod_postal::where('divipol',$ciudad_id)->get();
      $cod_postal_id = $col_cod_postal_id[0]['id'];
      // otra manera de hacer lo anterior seria mas sencillo:
      // $cod_postal_id = Cod_postal::select('id')->where('divipol','05091')->first();
      // si el checkbox de habeas_data fue activado llegará aqui con el
      // value="1", pero si no fué seleccionado, no llegará en el POST,
      // por lo tanto:
      if(isset($data['habeas_data'])) {
         $habeas_data = $data['habeas_data'];
      }else{
         $habeas_data = false;
      }
      // para convertir el número de mes en sus tres primeras letras:
      // 13nov2019: se decidió que en el select se pondrían directamente
      // las tres primeras letras del mes, por eso los siguientes comentarios:
      // $arr_meses_3 = ['' , 'ENE' , 'FEB' , 'MAR' , 'ABR' , 'MAY' , 'JUN' , 'JUL' , 'AGO' , 'SEP' , 'OCT' , 'NOV' , 'DIC' ];
      // $nom_mes = $arr_meses_3[$data['cumple_mes']];
      // usuario logueado:
      $user_id = Auth::user()->id;

      // inserta el registro en la tabla clientes e incrementa
      // el número para la clave wimax en la tabla params:
      DB::beginTransaction();
      try {
         $id_agregado = DB::table('clientes')->insertGetId(
            [
               'clave' => $clave,
               'doc_tipo_id' => $data['doc_tipo'],
               'doc_num' => $data['doc_num'],
               'doc_dv' => $data['doc_dv'],
               'juridica' => $data['juridica'],
               'declarante' => $data['declarante'],
               'estado' => $estado,
               'nombre1' => strtoupper($data['nombre1']),
               'nombre2' => strtoupper($data['nombre2']),
               'apellido1' => strtoupper($data['apellido1']),
               'apellido2' => strtoupper($data['apellido2']),
               'razon_social' => strtoupper($data['razon_social']),
               'fec_ingreso' => $fec_hoy,
               'dir_id' => $direccion_id,
               'dir_num_ppal' => strtoupper($data['dir_num_ppal']),
               'dir_num_casa' => strtoupper($data['dir_num_casa']),
               'dir_adic' => strtoupper($data['dir_adic']),
               'cod_postal_id' => $cod_postal_id,
               'cod_postal_escogido' => $data['cod_postal_escogido'],
               'email' => $data['email'],
               'tel_fijo' => $data['tel_fijo'],
               'tel_celu' => $data['tel_celu'],
               'habeas_data' => $habeas_data,
               'cumple_dia' => $data['cumple_dia'],
               'cumple_mes' => $data['cumple_mes'],
               'contacto' => $data['contacto'],
               'user_id' => $user_id,
               'created_at' => $fec_hora_hoy
            ]
         );   // fin del DB::table insert

         // incrementar el número para obtener posteriormente otra clave wimax:
         DB::update('update params
               set ult_clave_wimax = ult_clave_wimax +1');

         DB::commit();
         $nom_cliente = $data['razon_social'] . $data['nombre1']." ".$data['nombre2']." ".$data['apellido1']." ".$data['apellido2'];
         $msg = 'Grabación correcta. Cliente agregado: '.strtoupper($nom_cliente).'   Clave asignada: '.$clave;
         $arr_resul = ['msg' => $msg, 'status' => true , 'cliente_id' => $id_agregado , 'error' => ''];

         // si el cliente autorizo el habeas data, renombra el archivo de firma
         // para que no quede como: docu_<num_docu>.png, sino como: <idcliente>.png:
         if($habeas_data){
            $baseUrl = "img/habeas_data/";
            $firma_ant = $baseUrl."docu_".$data['doc_num'].".png";
            $firma_nueva = $baseUrl.$id_agregado.".png";
            rename($firma_ant , $firma_nueva);
         }
      }catch(\Throwable $e){
         $msg = 'ERROR: (Catch transaction) ';
         $arr_resul = ['msg' => $msg, 'status' => false , 'cliente_id' => '' , 'error' => $e];
      	DB::rollback();
      	throw $e;
      }
      return $arr_resul;
   }

   public function crear_vehiculo($data , $cliente_id){
      // 23ene2020:
      $arr_resul = [];

      // campos que no son pedidos al usuario o que debe ser
      // obtenidos por programación:
      $fec_hora_hoy = date("Y-m-d H:i:s");
      $user_id = Auth::user()->id;

      // inserta el registro en la tabla vehiculos
      $id_agregado = DB::table('vehiculos')->insertGetId(
         [
            'placa' => strtoupper($data['placa']),
            'marca' => strtoupper($data['marca']),
            'modelo' => $data['modelo'],
            'cliente_id' => $cliente_id,
            'gama' => strtoupper($data['gama']),
            'fec_soat' => $data['fec_soat'],
            'fec_tecno' => $data['fec_tecno'],
            'fec_extintor' => $data['fec_extintor'],
            'kilom' => $data['kilom'],
            'kilom_aceite' => $data['kilom_aceite'],
            'user_id' => $user_id,
            'created_at' => $fec_hora_hoy
         ]
      );   // fin del DB::table insert

      $placa = $data['placa'];
      $msg = 'Grabación correcta. Vehículo agregado: '.strtoupper($placa);
      $arr_resul = ['msg' => $msg , 'status' => true ,  'vehiculo_id' => $id_agregado , 'error' => '' ];
      return $arr_resul;
   }

   private function generar_clave_wimax(){
     // Generación de la clave wimax que debe tener cada cliente
     // Es una clave de 5 letras mayúsculas.
     // Se genera a partir de una semilla que arranca en el número 0
     // y que convertido a letras mayúsculas genera la clave AAAAA
     // La última clave ZZZZZ corresponde al número 11,881,375
     // La conversión se hace con el módulo 26 y con un array que tiene
     // las letras de la A a la Z, correspondiendo a la letra A el
     // índice 0 del array y a la letra Z el índice 25 del array
     // 1) En el campo ult_clave_wimax de la tabla params, se encuentra el
     // valor correspondiente a la última clave wimax que fué asignada
     // a un cliente, para generar la nueva se lee este valor, se incrementa
     // en 1 y se hace el proceso módulo 26.
     // 2) obtenida la nueva clave, se busca en el campo "clave" de la
     // tabla clientes, si existe entonces debe increntar en 1 el
     // campo ult_clave_wimax de la tabla params y luego repetir el
     // paso 1). Si no existe entonces retorna la nueva clave al
     // sitio desde donde fué llamada esta función. (Este retorno
     // se controla con el while infinito)
     $arr_letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

     // saldrá del siguiente ciclo infinito cuando se obtenga una clave
     // wimax que no exista en la tabla clientes
     while(true){
        // obtener el siguiente número para la nueva clave:
        $ult_grabado = Param::first()->ult_clave_wimax;
        $nuevo_num_clave = $ult_grabado + 1;

        // proceso módulo 26:
        $arr_residuos = [];
        $cociente = $nuevo_num_clave;
        for($i = 0 ; $i <= 4 ; $i++){
           $residuo = $cociente % 26;
           $cociente = intdiv($cociente , 26);
           $arr_residuos[$i] = $arr_letras[$residuo];
        }
        // generar la nueva clave a partir de arr_residuos:
        $nueva_clave = "";
        for($j = 4 ; $j>=0 ; $j--){
           $nueva_clave = $nueva_clave . $arr_residuos[$j];
        }

        // buscar la nueva clave en la tabla clientes:
        // 09nov2019 PENDIENTE: CAMBIAR POR MODELO EXIST().........
        if(Cliente::where('clave' , $nueva_clave)->exists()){
           // la clave SI existe en la tabla clientes, debe
           // incrementar en 1 el campo ult_clave_wimax de
           // la tabla params y luego comenzar otra vez este ciclo
           // para buscar la siguiente clave que no exista:
           $filas_actualizadas =
              DB::update('update params
                    set ult_clave_wimax = ult_clave_wimax + 1');
        }else{
           // la clave NO existe en la tabla clientes, puede
           // salir del ciclo y retornar la clave obtenida:
           break;
        }
     }
     return $nueva_clave;
  }
}
