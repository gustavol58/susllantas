<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id' , 'fk_servicios_clientes')
               ->references('id')->on('clientes')
               ->onDelete('restrict')
               ->onUpdate('restrict');
            $table->unsignedBigInteger('vehiculo_id');
            $table->foreign('vehiculo_id' , 'fk_servicios_vehiculos')
               ->references('id')->on('vehiculos')
               ->onDelete('restrict')
               ->onUpdate('restrict');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id' , 'fk_servicios_users')
               ->references('id')->on('users')
               ->onDelete('restrict')
               ->onUpdate('restrict');
            $table->boolean('abierta')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id','fk_servicios_users')
               ->references('id')
               ->on('users')
               ->onDelete('restrict')
               ->onUpdate('restrict');
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
        Schema::dropIfExists('servicios');
    }
}
