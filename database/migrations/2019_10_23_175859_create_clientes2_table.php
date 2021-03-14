<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientes2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('clave',5)->unique();
            $table->string('doc_tipo',2);
            $table->string('doc_num',15)->unique();
            $table->integer('doc_div')->nullable();
            $table->string('juridica',1);
            $table->string('declarante',1)->nullable();
            $table->string('estado',1);
            $table->string('apellido1',20)->nullable();
            $table->string('apellido2',20)->nullable();
            $table->string('nombre1',20)->nullable();
            $table->string('nombre2',20)->nullable();
            $table->string('razon_social',80)->nullable();
            // fec_ingreso será nullable pero solo provisional mientras se depura
            // la información de Wimax:
            $table->date('fec_ingreso')->nullable();
            // dirección será nullable pero solo provisional mientras se depura
            // la información de Wimax:
            $table->string('direccion',80)->nullable();
            $table->integer('codigopostal_id');
            $table->string('email',80)->nullable();
            // los 2 teléfonos serán nullable pero solo provisional mientras se depura
            // la información de Wimax:
            $table->string('tel_fijo',15)->nullable();
            $table->string('tel_celu',12)->nullable();
            // habeas_data será nullable pero solo provisional mientras se depura
            // la información de Wimax:
            $table->boolean('habeas_data')->nullable();
            $table->string('firma_pdf',20);
            // fec_cumple será nullable pero solo provisional mientras se depura
            // la información de Wimax:
            $table->date('fec_cumple')->nullable();
            $table->string('contacto',80)->nullable();
            $table->integer('usuario_id');
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
        Schema::dropIfExists('clientes2');
    }
}
