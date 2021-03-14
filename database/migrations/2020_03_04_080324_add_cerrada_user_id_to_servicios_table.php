<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCerradaUserIdToServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios', function (Blueprint $table) {
           $table->unsignedBigInteger('cerrada_user_id')->after('cerrada_el')->nullable();
           $table->foreign('cerrada_user_id' , 'fk_servicios_users2')
             ->references('id')->on('users')
             ->onDelete('restrict')
             ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('cerrada_user_id');
        });
    }
}
