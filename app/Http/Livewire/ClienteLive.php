<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class ClienteLive extends Component
{
    use WithPagination;
    public $filas_por_pagina;
    
    public $origen; 
    public $doc_num; 
    public $nombre; 
    public $ciudad; 
    public $direccion; 
    public $tel_fijo; 
    public $tel_celu; 
    public $email; 
    public $fec_ingreso; 

    public $ordenar_campo;
    public $ordenar_tipo;    

    public function mount(){
        $this->filas_por_pagina = 50;
        $this->ordenar_campo = 'subcon.nombre';
        $this->ordenar_tipo = ' desc';        
    }

    public function render()
    {
        $arr_params =[];
        $sql1 = "select subcon.* 
                    from (select cli.id,
                            cli.origen,
                            cli.doc_num,
                            case
                                when cli.razon_social IS NULL then trim(CONCAT(TRIM(cli.nombre1),' ',trim(IFNULL(cli.nombre2,'')),' ',trim(IFNULL(cli.apellido1,'')),' ',trim(IFNULL(cli.apellido2,''))))
                                when length(cli.razon_social)=0 then trim(CONCAT(trim(cli.nombre1),' ',trim(cli.nombre2),' ',trim(cli.apellido1),' ',trim(cli.apellido2)))
                                ELSE trim(cli.razon_social)
                            END nombre,
                            case
                                when cli.origen='Tiremaxx' then
                                    pos.ciudad
                                else
                                    cli.ciudad_susllantas
                            end ciudad,
                            case
                                when cli.origen='Tiremaxx' then
                                    case
                                        when dir_id=99
                                            then cli.dir_old
                                        else
                                            CONCAT(dir.nombre, ' ', trim(IFNULL(cli.dir_num_ppal,'')), ' ', trim(IFNULL(cli.dir_num_casa,'')), ' ', trim(IFNULL(cli.dir_adic,'')))
                                    end
                                else
                                    cli.dir_susllantas
                            end direccion,
                            cli.tel_fijo,
                            cli.tel_celu,
                            cli.email,
                            cli.fec_ingreso
                        FROM clientes cli
                            LEFT JOIN doc_tipos doctip on doctip.id=cli.doc_tipo_id
                            LEFT JOIN cod_postales pos ON pos.id=cli.cod_postal_id
                            LEFT JOIN cod_direcciones dir ON dir.id=cli.dir_id) subcon
                where subcon.origen like :origen
                    and subcon.doc_num like :doc_num
                    and subcon.nombre like :nombre
                    and subcon.ciudad like :ciudad
                    and subcon.direccion like :direccion
                    and subcon.tel_fijo like :tel_fijo
                    and subcon.tel_celu like :tel_celu
                    and subcon.email like :email
                    and subcon.fec_ingreso like :fec_ingreso
            ";
        $sql1 = $sql1 . " order by " . $this->ordenar_campo . $this->ordenar_tipo;             

        $arr_params = [
            'origen' => '%' . $this->origen . '%',
            'doc_num' => '%' . $this->doc_num . '%',
            'nombre' => '%' . $this->nombre . '%',
            'ciudad' => '%' . $this->ciudad . '%',
            'direccion' => '%' . $this->direccion . '%',
            'tel_fijo' => '%' . $this->tel_fijo . '%',
            'tel_celu' => '%' . $this->tel_celu . '%',
            'email' => '%' . $this->email . '%',
            'fec_ingreso' => '%' . $this->fec_ingreso . '%',
        ];

        $collection = collect(DB::select($sql1 , $arr_params));
        $perPage = $this->filas_por_pagina;        
        $items = $collection->forPage($this->page, $perPage);
        $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);

        return view('livewire.cliente-live', ['clientes' => $paginator]);
    }

    public function ordenar($campo){
        if($this->ordenar_campo == $campo){
            if($this->ordenar_tipo == ' asc'){
                $this->ordenar_tipo = ' desc';
            }else{
                $this->ordenar_tipo = ' asc';
            }
        }else{
            $this->ordenar_campo = $campo;
            $this->ordenar_tipo = ' asc';
        }
    }    

    // ganchos para cada variable de búsqueda, de tal manera que se evite el problema
    // que consistia en que después de grabar, si la página actual era una en la que 
    // la nueva búsqueda no dejaba registros, entonces no aparecia nada en la pantalla (a 
    // pesar de que habian registros que concordaban con la búsqueda). Para evitar este 
    // problema, cada que se hace una búsqueda se muestra la primera página de los
    // resultados hallados.
    // public function updatingOrigen(){
    //     $this->gotoPage(1);
    //     // $this->resetPage();
    // }

    // public function updatingDocNum(){
    //     $this->gotoPage(1);
    // }    

    // public function updatingNombre(){
    //     $this->gotoPage(1);
    // }

    // public function updatingCiudad(){
    //     $this->gotoPage(1);
    // }

    // public function updatingDireccion(){
    //     $this->gotoPage(1);
    // }

    // public function updatingTelFijo(){
    //     $this->gotoPage(1);
    // }

    // public function updatingTelCelu(){
    //     $this->gotoPage(1);
    // }

    // public function updatingEmail(){
    //     $this->gotoPage(1);
    // }

    // public function updatingFecIngreso(){
    //     $this->gotoPage(1);
    // }


}
