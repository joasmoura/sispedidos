<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nome_pagarme');
            $table->string('codigo');
            $table->string('dias_cobranca');
            $table->string('avisos_antes');
            $table->string('descricao');
            $table->string('valor');
            $table->string('total_registros');
            $table->string('periodo_testes');
            $table->enum('status',['A','D']);
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
        Schema::dropIfExists('planos');
    }
}
