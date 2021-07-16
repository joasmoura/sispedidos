<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCobrancasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobrancas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assinatura_id');
            $table->string('transacao_id');
            $table->string('metodo_pagamento');
            $table->string('codigo_boleto')->nullable();
            $table->dateTime('vencimento')->nullable();
            $table->string('valor');
            $table->string('status');
            $table->string('boleto_url')->nullable();
            $table->timestamps();

            $table->foreign('assinatura_id')
            ->on('assinaturas')
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
        Schema::dropIfExists('cobrancas');
    }
}
