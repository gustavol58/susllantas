<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('clave' , 12);
          $table->string('cai' , 15)->unique();
          $table->string('nombre' , 40);
          $table->string('marca' , 5)->nullable();
          $table->string('linea' , 20)->nullable();
          $table->string('unidad' , 3);
          $table->decimal('costo' , 10 , 2 );
          $table->decimal('precio' , 10 , 2 );
          $table->string('iva_ventas' , 1)->nullable();
          $table->string('iva_compras' , 1)->nullable();
          $table->string('iva_dif' , 1)->nullable();
          $table->string('codbarras' , 20)->nullable();
          $table->integer('inv_inicial');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id','fk_productos_users')
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
        Schema::dropIfExists('productos');
    }
}
