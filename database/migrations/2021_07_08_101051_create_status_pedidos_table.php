<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->enum('status',['pedido','preparacao','acaminho','entregue','cancelado']);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('obs')->nullable();
            $table->timestamps();

            $table->foreign('pedido_id')
            ->on('pedidos')
            ->references('id')
            ->onDelete('cascade');

            $table->foreign('user_id')
            ->on('users')
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
        Schema::dropIfExists('status_pedidos');
    }
}
