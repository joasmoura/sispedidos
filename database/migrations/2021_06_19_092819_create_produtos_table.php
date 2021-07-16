<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('negocios_id')->nullable();
            $table->unsignedBigInteger('produtos_id')->nullable();
            $table->unsignedBigInteger('opcao_id')->nullable();
            $table->string('uri');
            $table->text('descricao')->nullable();
            $table->text('logotipo')->nullable();
            $table->double('valor',10,2)->nullable();
            $table->boolean('indisponivel')->default(false);
            $table->boolean('mostrar')->default(true);
            $table->integer('max_opcoes')->nullable();
            $table->integer('max_opcoes_pagas')->nullable();
            $table->timestamps();

            $table->foreign('negocios_id')
                  ->on('negocios')
                  ->references('id')
                  ->onDelete('cascade');

            $table->foreign('produtos_id')
                  ->on('produtos')
                  ->references('id')
                  ->onDelete('cascade');

            $table->foreign('opcao_id')
                  ->on('produtos')
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
        Schema::dropIfExists('produtos');
    }
}
