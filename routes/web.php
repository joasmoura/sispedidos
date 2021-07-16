<?php

use App\Http\Controllers\Painel\AssinaturasController;
use App\Http\Controllers\Painel\CategoriasController;
use App\Http\Controllers\Painel\NegociosController;
use App\Http\Controllers\Painel\PainelController;
use App\Http\Controllers\Painel\ProdutosController;
use App\Http\Controllers\Painel\UsuariosController;
use App\Http\Controllers\Painel\PlanosController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['auth'])->prefix('painel')->name('painel.')->group(function () {
    Route::get('/',[PainelController::class,'index'])->name('index');
    Route::get('/perfil',[PainelController::class,'perfil'])->name('perfil');
    Route::get('/estados/{pais}',[PainelController::class,'estados'])->name('estados');
    Route::get('/cidades/{estado}',[PainelController::class,'cidades'])->name('cidades');

    Route::resource('/usuarios',UsuariosController::class);

    Route::get('/negocios/excluidos',[NegociosController::class,'excluidos'])->name('negocios.excluidos');
    Route::get('/negocios/restaurar/{id}',[NegociosController::class,'restaurar'])->name('negocios.restaurar');
    Route::get('/negocios/exclusaoPermanente/{id}',[NegociosController::class,'exclusao_permanente'])->name('negocios.exclusaoPermanente');
    Route::get('/negocios/{id}/produtos',[NegociosController::class,'produtos'])->name('negocios.produtos');
    Route::get('/negocio/dados/{id}',[NegociosController::class,'dados_negocio'])->name('negocios.dados');
    Route::get('/negocio/pedidos/{id?}',[NegociosController::class,'pedidos'])->name('negocios.pedidos');
    Route::get('/negocio/pedidos/itens/{id}',[NegociosController::class,'itenspedido'])->name('negocios.pedidos.itenspedido');
    Route::get('/negocio/pedidos/status/{id}',[NegociosController::class,'statuspedido'])->name('negocios.pedidos.statuspedido');
    Route::post('/negocio/pedidos/salvarStatus/{id}',[NegociosController::class,'salvarStatus'])->name('negocios.pedidos.salvarStatus');
    Route::get('/negocio/pedidos/cancelar/{id}',[NegociosController::class,'cancelarPedido'])->name('negocios.pedidos.cancelarPedido');
    Route::get('/negocio/pedidos/excluirStatus/{id}',[NegociosController::class,'excluirStatus'])->name('negocios.pedidos.excluirStatus');
    Route::get('/negocio/verifica',[NegociosController::class,'verifica'])->name('negocios.verifica');
    Route::resource('/negocios',NegociosController::class);

    Route::resource('/categorias',CategoriasController::class);

    Route::resource('/produtos',ProdutosController::class);

    Route::get('/planos/admin',[PlanosController::class, 'planos_admin'])->name('planos.admin');
    Route::resource('/planos',PlanosController::class);

    Route::post('/assinatura/confirmar_trial/{codigo}',[AssinaturasController::class, 'confirmar_trial'])->name('assinatura.confirmar_trial');
    Route::get('/assinatura/trial/{codigo}',[AssinaturasController::class, 'form_trial'])->name('assinatura.trial');
    Route::post('/assinatura/confirmarAssinatura/{codigo}',[AssinaturasController::class, 'confirmar_assinatura'])->name('assinatura.confirmar_assinatura');
    Route::get('/assinatura/{codigo}/assinar',[AssinaturasController::class, 'form_assinatura'])->name('assinatura.assinar');
    Route::get('/assinatura/cancelar',[AssinaturasController::class, 'cancelar_assinatura'])->name('assinatura.cancelar');
    Route::resource('/assinatura',AssinaturasController::class);
});

Route::name('site.')->group(function () {
    Route::get('/',[SiteController::class,'index'])->name('index');
    Route::get('/negocio/{uri}',[SiteController::class,'negocio'])->name('negocio');
    Route::post('/negocio/{uri}/pedido',[SiteController::class,'realizar_pedido'])->name('realizar_pedido');
    Route::get('/negocio/{uri}/retornaOpcoes/{idProduto}/{idItem}',[SiteController::class,'retornaOpcoes'])->name('retornaOpcoes');
});


require __DIR__.'/auth.php';
