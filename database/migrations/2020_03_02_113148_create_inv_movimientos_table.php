<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_movimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id' , 'fk_inv_movimientos_productos')
               ->references('id')->on('productos')
               ->onDelete('restrict')
               ->onUpdate('restrict');
            $table->string('tipo' , 1)->comment('(E)ntrada (S)alida');
            $table->integer('canti');
            $table->decimal('valor' , 10 , 2 )->comment('Si el tipo es entrada, es costo. Si el tipo es salida, es precio');
            $table->unsignedBigInteger('servicio_id')->default(0)->comment('Si el tipo es salida, acá se graba el número de orden de servicio. Si el tipo es entrada, queda con CERO');
            $table->foreign('servicio_id' , 'fk_inv_movimientos_servicios')
               ->references('id')->on('servicios')
               ->onDelete('restrict')
               ->onUpdate('restrict');
            $table->string('num_entrada' , 10)->nullable()->default(null)->comment('Si el tipo es entrada, acá se graba el número digitado por el usuario en el formulario. Si el tipo es salida, tendrá NULL');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id','fk_inv_movimientos_users')
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
        Schema::dropIfExists('inv_movimientos');
    }
}
