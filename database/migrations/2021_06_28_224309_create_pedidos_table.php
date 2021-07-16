<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('pedido');
            $table->unsignedBigInteger('negocio_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('observacao')->nullable();
            $table->enum('status',['pedido','preparacao','acaminho','entregue','cancelado']);
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
        Schema::dropIfExists('pedidos');
    }
}
