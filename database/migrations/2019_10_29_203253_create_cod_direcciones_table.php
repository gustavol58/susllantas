<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodDireccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cod_direcciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo' , 10)->unique();
            $table->string('nombre' , 25)->unique();
            $table->boolean('activo_principal');
            $table->integer('orden_principal')->nullable();
            $table->boolean('activo_secundario')->nullable();
            $table->integer('orden_secundario')->nullable();
            $table->integer('grupo')->nullable()->comment('1: Principal pide num casa + adicionales
              2: Principal solo pide adicionales
              3: Secundario pide num casa + adicionales
              4: Secundario solo pide adicionales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cod_direcciones');
    }
}
