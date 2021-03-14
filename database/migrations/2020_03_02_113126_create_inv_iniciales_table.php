<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvInicialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_iniciales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id' , 'fk_inv_iniciales_productos')
               ->references('id')->on('productos')
               ->onDelete('restrict')
               ->onUpdate('restrict');
            $table->integer('inicial')->comment('saldo inicial al comienzo del periodo actual');
            $table->decimal('costo' , 10 , 2 )->comment('costo con el que se registró el saldo inicial del producto');
            $table->decimal('precio' , 10 , 2 )->comment('precio con el que se registró el saldo inicial del producto');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id','fk_inv_iniciales_users')
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
        Schema::dropIfExists('inv_iniciales');
    }
}
