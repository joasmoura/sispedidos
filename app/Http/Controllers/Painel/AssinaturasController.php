<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Assinatura;
use App\Models\Plano;
use Illuminate\Http\Request;
use PagarMe\Client;

class AssinaturasController extends Controller
{
    private $Plano;
    private $Assinatura;
    private $Pagarme;
    public function __construct(Plano $planos, Assinatura $assinatura)
    {
        $this->apiKey = env('PAGARME_CHAVE_API');
        $this->Plano = $planos;
        $this->Assinatura = $assinatura;
        $this->Pagarme = new Client($this->apiKey);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = auth()->user();
        $assinatura = $usuario->assinatura()->whereNotIn('status',['canceled','ended'])->first();
        if($assinatura){
            $plano = $assinatura->plano;
            $transacoes = $assinatura->cobrancas;

            // $transacoes = $this->Pagarme->subscriptions()->transactions([
            //     'subscription_id' => $assinatura->codigo_assinatura
            // ]);

            return view('Painel.Assinaturas.index',compact('assinatura','plano','transacoes'));

        }else{
            return redirect()->route('painel.planos.index')->with('Necessário escolher um plano de assinatura!');
        }
    }

    public function confirmar_trial($codigo, Request $request){
        $plano = $this->Plano->where('codigo',$codigo)->first();
        if($plano){
            $assinatura = Assinatura::create([
                'user_id' => auth()->user()->id,
                'plano_id' => $request->plano_id,
                'max_registros' => $plano->total_registros,
                'codigo_assinatura' => '',
                'nome_plano' => $plano->nome,
                'valor' => $plano->valor,
                'metodo_pagamento' => $request->metodoPagamento,
                'status' => 'trialing',
                'periodo_testes' => $plano->periodo_testes
            ]);

            if($assinatura){
                $result = ['status' => true,'texto' => 'Período de testes ativado, faça muitas vendas!', 'link' => route('painel.index')];
                return compact('result');
            }
        }
    }

    public function form_assinatura($codigo){
        $plano = $this->Plano->where('codigo',$codigo)->first();
        if($plano){
            return view('Painel.Assinaturas.formAssinar',compact('plano'));
        }else{
            return redirect()->back();
        }
    }

    public function confirmar_assinatura($plano,Request $request){
        $dados = $request->all();

        if($dados['ad_metodoPagamento'] == 'boleto'){
            return $this->confirmar_assinatura_boleto($plano,$dados);
        }else{
            return $this->confirmar_assinatura_cartao($plano,$dados);
        }
    }

    public function confirmar_assinatura_boleto($plano, $dados){
        $telefone = str_replace(['(',')','-'],['','',''],$dados['celular']);
        $telefone = explode(' ',$telefone);

        $plano = $this->Pagarme->plans()->get(['id' => $plano]);
        $addDadosAsinatura =[];

        if($plano){
            $dados_assinatura = [
                'plan_id' => $plano->id,
                'payment_method' => $dados['ad_metodoPagamento'],
                'postback_url' => '',
                'customer' => [
                    'email' => $dados['email'],
                    'name' => $dados['nome'],
                    'document_number' => str_replace(['.','/','-'],['','',''],$dados['documento']),
                    'address' => [
                    'street' => $dados['logradouro'],
                    'street_number' => $dados['numero'],
                    'complementary' => $dados['complemento'],
                    'neighborhood' => $dados['bairro'],
                    'zipcode' => $dados['cep']
                    ],
                    'phone' => [
                    'ddd' => $telefone[0],
                    'number' => $telefone[1]
                    ],
                ],
            ];

           $subscription = $this->Pagarme->subscriptions()->create($dados_assinatura);//Criando Assinatura

            if($subscription){
                $assinatura = auth()->user()->assinatura()->whereNotIn('status',['canceled','ended'])->first();

                if($assinatura){
                    $dados_assinatura['codigo_assinatura'] = $subscription->id;
                    $assinatura->status = $subscription->status;
                    $assinatura->codigo_assinatura = $subscription->id;

                    array_push($addDadosAsinatura,$subscription,json_decode($assinatura->dados_assinatura,true));
                    $assinatura->dados_assinatura = json_encode($addDadosAsinatura);

                    $salvo = $assinatura->save();
                    if($salvo){
                        $assinatura->cobrancas()->create([
                            'metodo_pagamento' => $assinatura->metodo_pagamento,
                            'transacao_id' => $subscription->current_transaction->id,
                            'codigo_boleto' => ($assinatura->metodo_pagamento == 'boleto'? $subscription->current_transaction->boleto_barcode : ''),
                            'vencimento' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->boleto_expiration_date)),
                            'valor' => $assinatura->valor,
                            'status' => $subscription->current_transaction->status,
                            'boleto_url' => $subscription->current_transaction->boleto_url,
                            'created_at' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->date_created)),
                            'updated_at' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->date_created))
                        ]);
                        $result = ['status' => false,'texto' => 'Sucesso, sua assinatura foi realizada com sucesso! ', 'link' => route('painel.assinatura.index')];
                        return compact('result');
                    }else{

                    }
                }else{
                    $plano_sistema = Plano::find($dados['plano_id']);
                    if($plano_sistema){
                        $assinatura = auth()->user()->assinatura()->create([
                            'plano_id' => $dados['plano_id'],
                            'max_registros' => $plano_sistema->total_registros,
                            'codigo_assinatura' => $subscription->id,
                            'nome_plano' => $plano_sistema->nome,
                            'valor' => $plano_sistema->valor,
                            'metodo_pagamento' => $dados['ad_metodoPagamento'],
                            'status' => $subscription->status,
                            'periodo_testes' => '',
                            'dados_assinatura' => json_encode($subscription),
                        ]);

                        if($assinatura){
                            $assinatura->cobrancas()->create([
                                'metodo_pagamento' => $assinatura->metodo_pagamento,
                                'transacao_id' => $subscription->current_transaction->id,
                                'codigo_boleto' => ($assinatura->metodo_pagamento == 'boleto'? $subscription->current_transaction->boleto_barcode : ''),
                                'vencimento' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->boleto_expiration_date)),
                                'valor' => $assinatura->valor,
                                'status' => $subscription->current_transaction->status,
                                'boleto_url' => $subscription->current_transaction->boleto_url,
                                'created_at' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->date_created)),
                                'updated_at' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->date_created))
                            ]);
                            $result = ['status' => true,'texto' => 'Sucesso, sua assinatura foi realizada com sucesso! ', 'link' => route('painel.assinatura.index')];
                            return compact('result');
                        }
                    }
                }
            }
        }else{
            $result = ['status' => false,'texto' => 'O plano escolhido não foi encontrado, pedimos que recarregue o navegador! ', 'link' => ''];
            return compact('result');
        }
    }

    public function confirmar_assinatura_cartao($plano, $dados){
        $telefone = str_replace(['(',')','-'],['','',''],$dados['celular']);
        $telefone = explode(' ',$telefone);

        $plano = $this->Pagarme->plans()->get(['id' => $plano]);
        $addDadosAsinatura = [];

        if($plano){
            $dados_assinatura = [
                'plan_id' => $plano->id,
                'payment_method' => $dados['ad_metodoPagamento'],
                'card_number' => str_replace([' '],[''],$dados['cartaoNumero']),
                'card_holder_name' => $dados['cartaoNomeTitular'],
                'card_expiration_date' => str_replace(['/',' '],['',''],$dados['cartaoVencimento']),
                'card_cvv' => $dados['cartaoCodigo'],
                'postback_url' => '',
                'customer' => [
                    'email' => $dados['email'],
                    'name' => $dados['nome'],
                    'document_number' => str_replace(['.','/','-'],['','',''],$dados['documento']),
                    'address' => [
                        'street' => $dados['logradouro'],
                        'street_number' => $dados['numero'],
                        'complementary' => $dados['complemento'],
                        'neighborhood' => $dados['bairro'],
                        'zipcode' => $dados['cep']
                    ],
                    'phone' => [
                        'ddd' => $telefone[0],
                        'number' => $telefone[1]
                    ],
                ],
            ];

            $subscription = $this->Pagarme->subscriptions()->create($dados_assinatura);//Criando Assinatura

            if($subscription){
                $assinatura = auth()->user()->assinatura()->whereNotIn('status',['canceled','ended'])->first();

                if($assinatura){
                    $dados_assinatura['codigo_assinatura'] = $subscription->id;
                    $assinatura->status = $subscription->status;
                    $assinatura->codigo_assinatura = $subscription->id;
                    array_push($addDadosAsinatura,$subscription,json_decode($assinatura->dados_assinatura,true));
                    $assinatura->dados_assinatura = json_encode($addDadosAsinatura);
                    $assinatura->periodo_testes = '';

                    $salvo = $assinatura->save();
                    if($salvo){
                        $assinatura->cobrancas()->create([//CRIANDO TRANSAÇÃO DE COBRANÇA VINDA DO PAGARME NO BANCO DE DADOS
                            'metodo_pagamento' => $assinatura->metodo_pagamento,
                            'transacao_id' => $subscription->current_transaction->id,
                            'codigo_boleto' => '',
                            'vencimento' => null,
                            'valor' => $assinatura->valor,
                            'status' => $subscription->current_transaction->status,
                            'boleto_url' => '',
                            'created_at' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->date_created)),
                            'updated_at' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->date_created))
                        ]);
                        $result = ['status' => false,'texto' => 'Sucesso, sua assinatura foi realizada com sucesso! ', 'link' => route('painel.assinatura.index')];
                        return compact('result');
                    }else{

                    }
                }else{
                    $plano_sistema = Plano::find($dados['plano_id']);
                    if($plano_sistema){
                        $assinatura = auth()->user()->assinatura()->create([
                            'plano_id' => $dados['plano_id'],
                            'max_registros' => $plano_sistema->total_registros,
                            'codigo_assinatura' => $subscription->id,
                            'nome_plano' => $plano_sistema->nome,
                            'valor' => $plano_sistema->valor,
                            'metodo_pagamento' => $dados['ad_metodoPagamento'],
                            'status' => $subscription->status,
                            'periodo_testes' => '',
                            'dados_assinatura' => json_encode($subscription),
                        ]);

                        if($assinatura){
                            $assinatura->cobrancas()->create([//CRIANDO TRANSAÇÃO DE COBRANÇA VINDA DO PAGARME NO BANCO DE DADOS
                                'metodo_pagamento' => $assinatura->metodo_pagamento,
                                'transacao_id' => $subscription->current_transaction->id,
                                'codigo_boleto' => ($assinatura->metodo_pagamento == 'boleto'? $subscription->current_transaction->boleto_barcode : ''),
                                'vencimento' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->boleto_expiration_date)),
                                'valor' => $assinatura->valor,
                                'status' => $subscription->current_transaction->status,
                                'boleto_url' => $subscription->current_transaction->boleto_url,
                                'created_at' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->date_created)),
                                'updated_at' => date('Y-m-d H:i:s',strtotime($subscription->current_transaction->date_created))
                            ]);
                            $result = ['status' => true,'texto' => 'Sucesso, sua assinatura foi realizada com sucesso! ', 'link' => route('painel.assinatura.index')];
                            return compact('result');
                        }
                    }
                }
            }else{

            }


        }else{
            $result = ['status' => false,'texto' => 'O plano escolhido não foi encontrado, pedimos que recarregue o navegador! ', 'link' => ''];
            return compact('result');
        }
    }

    public function form_trial($codigo){
        $plano = $this->Plano->where('codigo',$codigo)->first();
        return view('Painel.Assinaturas.trial',compact('plano'));
    }

    public function cancelar_assinatura(){
        $usuario = auth()->user();
        $assinatura = $usuario->assinatura->whereNotIn('status',['canceled','ended'])->first();

        if($assinatura){
            $cancelado = $this->Pagarme->subscriptions()->cancel(['id' => $assinatura->codigo_assinatura]);
            if($cancelado){
                $assinatura->status = $cancelado->status;
                $salvo = $assinatura->save();
                if($salvo){
                    $result = ['status' => true,'texto' => 'Sua assinatura foi cancelada. Não demore de voltar! ', 'link' => route('painel.planos.index')];
                    return compact('result');
                }
            }else{
                $result = ['status' => false,'texto' => 'Algo deu errado ao cancelar seu plano, favor, entre em contato com o suporte para obter informações! ', 'link' => ''];
                return compact('result');
            }
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
