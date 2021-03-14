<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('clave',5)->unique();
          $table->unsignedBigInteger('doc_tipo_id')->nullable();
          $table->foreign('doc_tipo_id','fk_clientes_doc_tipos')
            ->references('id')
            ->on('doc_tipos')
            ->onDelete('restrict')
            ->onUpdate('restrict');
          $table->string('doc_num',15)->unique();
          $table->integer('doc_dv')->nullable();
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
          $table->unsignedBigInteger('dir_id');
          $table->foreign('dir_id','fk_clientes_cod_direcciones')
            ->references('id')
            ->on('cod_direcciones')
            ->onDelete('restrict')
            ->onUpdate('restrict');
          $table->string('dir_num_ppal',15)->nullable();
          $table->string('dir_num_casa',15)->nullable();
          $table->string('dir_adic',50)->nullable();
          $table->string('dir_old',80)->nullable();
          $table->unsignedBigInteger('cod_postal_id');
          $table->foreign('cod_postal_id','fk_clientes_cod_postales')
            ->references('id')
            ->on('cod_postales')
            ->onDelete('restrict')
            ->onUpdate('restrict');
          $table->string('cod_postal_escogido' , 6)->comment('Contiene el cod_postal escogido por el usuario en el select de códigos postales');
          $table->string('email',80)->nullable();
          // los 2 teléfonos serán nullable pero solo provisional mientras se depura
          // la información de Wimax:
          $table->string('tel_fijo',15)->nullable();
          $table->string('tel_celu',12)->nullable();
          // habeas_data será nullable pero solo provisional mientras se depura
          // la información de Wimax:
          $table->boolean('habeas_data')->nullable();
          // cumple_dia y cumple_mes serán nullable pero solo provisional mientras se depura
          // la información de Wimax:
          $table->integer('cumple_dia')->nullable();
          $table->string('cumple_mes' , 3)->nullable();

          $table->string('contacto',80)->nullable();
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id','fk_clientes_users')
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
        Schema::dropIfExists('clientes');
    }
}
