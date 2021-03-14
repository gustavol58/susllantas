<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id','fk_servicios_detalles_servicios')
               ->references('id')
               ->on('servicios')
               ->onDelete('restrict')
               ->onUpdate('restrict');

            $table->integer('canti');

            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id','fk_servicios_detalles_productos')
               ->references('id')
               ->on('productos')
               ->onDelete('restrict')
               ->onUpdate('restrict');

            $table->string('producto_adic' , 200)->nullable()->comment('Si el producto es \'REP-PUESTOS\' o \'SERVI08BD\' aqui va la descripción adicional que escibrió el usuario en el formulario cais, sino aquí irá NULL');

            $table->unsignedBigInteger('operario_id');
            $table->foreign('operario_id','fk_servicios_detalles_users_2')
               ->references('id')
               ->on('users')
               ->onDelete('restrict')
               ->onUpdate('restrict');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id','fk_servicios_detalles_users')
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
        Schema::dropIfExists('servicios_detalles');
    }
}
