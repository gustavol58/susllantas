<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrecioToServiciosDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_detalles', function (Blueprint $table) {
           $table->decimal('precio' , 10 , 2 )->after('canti')->comment('El precio con que fué cerrado el producto en la orden de servicio.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicios_detalles', function (Blueprint $table) {
            $table->dropColumn('precio');
        });
    }
}
