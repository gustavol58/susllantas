<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodPostalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cod_postales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('departamento',30);
            $table->string('ciudad',30);
            $table->string('divipol',5)->unique();
            $table->text('cod_postal');
            $table->string('cod_dpto',2);
            $table->string('cod_ciudad',3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('cod_postales');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
