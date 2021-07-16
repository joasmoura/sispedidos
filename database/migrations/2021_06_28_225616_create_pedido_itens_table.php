<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id')->nullable();
            $table->unsignedBigInteger('produto_id')->nullable();
            $table->unsignedBigInteger('opcao_id')->nullable();
            $table->integer('qtd');
            $table->double('valor',10,2)->nullable();
            $table->timestamps();

            $table->foreign('pedido_id')
            ->on('pedidos')
            ->references('id')
            ->onDelete('cascade');

            $table->foreign('produto_id')
            ->on('produtos')
            ->references('id');

            $table->foreign('opcao_id')
            ->on('pedido_itens')
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
        Schema::dropIfExists('pedido_itens');
    }
}
