<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Assinatura;
use App\Models\Plano;
use Illuminate\Http\Request;
use PagarMe\Client;
use PagarMe\PagarMe;

class PlanosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $apiKey;
    private $Plano;
    public function __construct(Plano $planos)
    {
        $this->apiKey = env('PAGARME_CHAVE_API');
        $this->Plano = $planos;
    }


    public function planos_admin()
    {
        if(!auth()->user()->conta_master){
            return redirect()->back();
        }

        $planos = $this->Plano->paginate(10);
        return view('Painel.Planos.index_admin',compact('planos'));
    }

    public function index()
    {
        $planos = $this->Plano->where('status','A')->get();
        $usuario = auth()->user();
        $assinatura = $usuario->assinatura()->whereNotIn('status',['canceled','ended'])->first();
        return view('Painel.Planos.index',compact('planos','assinatura','usuario'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->conta_master){
            return redirect()->back();
        }
        return view("Painel.Planos.Form");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pagarMe = new Client($this->apiKey);
        $codigo_pagarme = '';
        $salvo = 0;

        if(isset($request->criar_pagarme)){
            $criado = $pagarMe->plans()->create([
                'amount' =>  $request->valor,
                'days' => $request->dias_cobranca,
                'name' => $request->nome_pagarme,
                'trial_days' => $request->periodo_testes,
                'payment_methods' => ['credit_card','boleto'],
            ]);
            if($criado){
                $codigo_pagarme = $criado->id;
            }
        }

        if(!isset($request->criar_pagarme) || isset($request->criar_pagarme) && $codigo_pagarme ){
            $salvo = $this->Plano->create([
                'nome' => $request->nome,
                'nome_pagarme' => $request->nome_pagarme,
                'dias_cobranca' => $request->dias_cobranca,
                'avisos_antes' => $request->avisos_antes,
                'valor' => $request->valor,
                'periodo_testes' => $request->periodo_testes,
                'total_registros' => $request->total_registros,
                'descricao' => (isset($request->item) ? json_encode($request->item) : ''),
                'codigo' => (isset($request->criar_pagarme) ? $codigo_pagarme : $request->codigo),
            ]);
        }

        if($salvo){
            $result = ['status' => true,'texto' => 'Plano cadastrado com sucesso', 'link' => route('painel.planos.admin')];
            return compact('result');
        }else{
            $result = ['status' => false,'texto' => 'Não foi possível criar o plano. Confira se os dados estão corretos', 'link' => ''];
            return compact('result');
        }
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
        $plano = $this->Plano->find($id);

        if($plano){
            $itens = json_decode($plano->descricao,true);
            return view('Painel.Planos.Form',compact("plano",'itens'));
        }
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
        $plano = $this->Plano->find($id);

        if($plano){
            $plano->nome = $request->nome;
            $plano->nome_pagarme = $request->nome_pagarme;
            $plano->dias_cobranca = $request->dias_cobranca;
            $plano->avisos_antes = $request->avisos_antes;
            $plano->valor = $request->valor;
            $plano->periodo_testes = $request->periodo_testes;
            $plano->total_registros = $request->total_registros;
            $plano->descricao = (isset($request->item) ? json_encode($request->item) : '');
            $salvo = $plano->save();

            if($salvo){
                $result = ['status' => true,'texto' => 'Plano atualizado com sucesso', 'link' => route('painel.planos.admin')];
                return compact('result');
            }else{
                $result = ['status' => false,'texto' => 'Não foi possível atualizar o plano. Confira se os dados estão corretos', 'link' => ''];
                return compact('result');
            }
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plano = $this->Plano->find($id);

        if($plano){
            $excluido = $plano->delete();
            if($excluido){
                $result = ['status' => true,'texto' => 'Plano excluído com sucesso', 'link' => ''];
                return compact('result');
            }
        }
    }
}
