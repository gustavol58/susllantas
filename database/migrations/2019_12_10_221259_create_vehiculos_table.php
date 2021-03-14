<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('placa',6)->unique();
            $table->string('marca',50);
            $table->integer('modelo')->nullable();
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id', 'fk_vehiculos_clientes')->references('id')->on('clientes')->onDelete('restrict')->onUpdate('restrict');
            $table->string('gama',20);
            $table->date('fec_soat');
            $table->date('fec_tecno');
            $table->date('fec_extintor');
            $table->integer('kilom');
            $table->integer('kilom_aceite');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id','fk_vehiculos_users')
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
        Schema::dropIfExists('vehiculos');
    }
}
