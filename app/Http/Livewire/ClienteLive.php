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

    public function mount(){
        $this->filas_por_pagina = 50;
    }

    public function render()
    {
        $arr_params =[];
        $sql1 = "SELECT cli.origen,
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
                LEFT JOIN cod_direcciones dir ON dir.id=cli.dir_id";
        

        $collection = collect(DB::select($sql1 , $arr_params));
        $perPage = $this->filas_por_pagina;        
        $items = $collection->forPage($this->page, $perPage);
        $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);

        return view('livewire.cliente-live', ['clientes' => $paginator]);
    }
}
