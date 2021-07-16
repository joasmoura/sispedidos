<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssinaturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plano_id')->nullable();
            $table->integer('max_registros');
            $table->string('codigo_assinatura');
            $table->string('nome_plano');
            $table->double('valor',10,2);
            $table->enum('metodo_pagamento',['boleto','credit_card']);
            $table->string('status');
            $table->string('periodo_testes')->nullable();
            $table->json('dados_assinatura')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
            ->on('users')
            ->references('id')
            ->onDelete('cascade');

            $table->foreign('plano_id')
            ->on('planos')
            ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assinaturas');
    }
}
