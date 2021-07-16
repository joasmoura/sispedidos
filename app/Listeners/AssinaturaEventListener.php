<?php

namespace App\Listeners;

use App\Events\AssinaturaEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssinaturaEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AssinaturaEvent  $event
     * @return void
     */
    public function handle($event)
    {
        $usuario = auth()->user();
        $data_atual = Carbon::createFromFormat('Y-m-d',date('Y-m-d'));

        if($usuario->perfil == '2'){
            $assinatura  = $usuario->assinatura()->whereNotIn('status',['canceled','ended'])->first();
            if($assinatura ){
                if($assinatura->status == 'trialing'){
                    if($data_atual->diffInDays($assinatura->created_at) > $assinatura->periodo_testes){
                        echo redirect()->route('painel.planos.index')->with('error','Período de testes encerrou, escolha um plano e continue trabalhando');
                    }
                }elseif($assinatura->status == 'unpaid'){

                }
            }else{
                echo redirect()->route('painel.planos.index');
            }
        }
    }
}
