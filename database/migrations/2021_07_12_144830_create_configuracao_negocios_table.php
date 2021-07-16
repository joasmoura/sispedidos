<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracaoNegociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao_negocios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('negocio_id');
            $table->longText('img_fundo')->nullable();
            $table->text('bg_fundo')->nullable();
            $table->boolean('modelo_lista')->default(1);
            $table->boolean('mostrar_imagem_fundo')->default(1);
            $table->timestamps();

            $table->foreign('negocio_id')
            ->on('negocios')
            ->references('id')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracao_negocios');
    }
}
