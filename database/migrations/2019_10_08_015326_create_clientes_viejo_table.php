<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientes_viejoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('clave',5)->unique();
            $table->string('nombre',80)->nullable();
            $table->date('fecha')->nullable();
            $table->string('otros',30)->nullable();
            $table->string('direc1',25)->nullable();
            $table->string('direc2',25)->nullable();
            $table->string('direc3',25)->nullable();
            $table->string('documento',25)->nullable();
            $table->string('tlf1',15)->nullable();
            $table->string('tlf2',15)->nullable();
            $table->string('observa',60)->nullable();
            $table->integer('nro_dias')->nullable();
            $table->string('tipo_dir',1)->nullable();
            $table->string('autorete',1)->nullable();
            $table->float('descuento', 16, 2)->nullable();
            $table->string('estatus',2)->nullable();
            $table->string('recargo',1)->nullable();
            $table->string('modcli',1)->nullable();
            $table->string('regimen',1)->nullable();
            $table->string('agenrete',1)->nullable();
            $table->string('tipo_id',2)->nullable();
            $table->string('tip_act',3)->nullable();
            $table->string('direc5',200)->nullable();
            $table->string('s_area',6)->nullable();
            $table->string('reciproca',1)->nullable();
            $table->string('act_des',1)->nullable();
            $table->string('exporta',1)->nullable();
            $table->string('retener',1)->nullable();
            $table->string('rete_ica',1)->nullable();
            $table->string('correo',80)->nullable();
            $table->string('celular',12)->nullable();
            $table->string('tipo_guber',1)->nullable();
            $table->string('foto',100)->nullable();
            $table->string('website',100)->nullable();
            $table->string('dpto',50)->nullable();
            $table->integer('digitoveri')->nullable();
            $table->string('apellido1',20)->nullable();
            $table->string('apellido2',20)->nullable();
            $table->string('nombre1',20)->nullable();
            $table->string('nombre2',20)->nullable();
            $table->string('persona_ju',1)->nullable();
            $table->string('cuenta_aho',20)->nullable();
            $table->string('banco_aho',30)->nullable();
            $table->string('estado',1)->nullable();
            $table->string('agen_cree',1)->nullable();
            $table->string('exen_cree',1)->nullable();
            $table->string('retene_cre',1)->nullable();
            $table->string('declarante',1)->nullable();
            $table->string('importa',1)->nullable();
            $table->string('agerteica',1)->nullable();
            $table->string('agrteicav',1)->nullable();
            $table->string('bolagro',1)->nullable();
            $table->string('autoretica',1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
