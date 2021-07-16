<?php

namespace App\Http\Controllers\Painel;

use App\Events\AssinaturaEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\NegocioRequest;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Negocios;
use App\Models\Pais;
use App\Models\Pedido;
use App\Models\PedidoItens;
use App\Models\StatusPedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NegociosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $Negocios;
    public function __construct(Negocios $negocios){
        $this->Negocios = $negocios;
    }

    public function verifica(){
        $usuario = auth()->user();
        $assinatura_atual = $usuario->assinatura()->whereNotIn('status',['canceled','ended'])->first();
        $negocios = $usuario->negocios()->count();
        $cadastra = true;

        if($negocios >= $assinatura_atual->max_registros){
            $cadastra = false;
        }

        $result = ['status' => $cadastra,'texto' => '', 'link' => ''];
        return compact('result');
    }

    public function dados_negocio($id){
        $usuario = auth()->user();
        $negocio = $usuario->negocios()->find($id);
        if($negocio){
            $negocio->cidade = $negocio->cidade;
            $negocio->estado = $negocio->estado;
            $negocio->pais = $negocio->pais;
            return compact('negocio');
        }
    }

    public function pedidos(Request $request,$id = null){
        $pesquisa = $request->all();

        $usuario = auth()->user();
        $negocios = $usuario->negocios;
        $negocio = $usuario->negocios()->find($id);
        $pedidos = [];
        if($negocio){
            if($pesquisa){
                if(!empty($pesquisa['de']) && !empty($pesquisa['ate']) ){
                    $pedidos = $negocio->pedidos()->where(function($query)use($pesquisa){
                        if(!empty($pesquisa['status'])){
                            if($pesquisa['status'] == 'menosCancelado'){
                                $query->where('status','!=','cancelado');
                            }else{
                                $query->where('status','like','%'.$pesquisa['status'].'%');
                            }
                        }
                        if(!empty($pesquisa['pedido'])){
                            $query->where('pedido','like','%'.$pesquisa['pedido'].'%');
                        }
                        $query->whereDate('created_at','>=',dataParaBanco($pesquisa['de']))->whereDate('created_at','<=',dataParaBanco($pesquisa['ate']));
                    })->get();
                }
            }else{
                $pesquisa['status'] = 'menosCancelado';
                $pedidos = $negocio->pedidos()->where('status','!=','cancelado')->get();
            }
        }
        return view('Painel.negocios.Pedidos.index',compact('negocios','pedidos','negocio','pesquisa'));
    }

    public function itenspedido($id){
        $pedido = Pedido::with('itens')->find($id);
        if($pedido){
            $itens = $pedido->itens()->with('opcoes')->get();
            if($itens->first()){
                foreach($itens as $key => $item){
                    $produto = $item->produto;
                    $produtoPrincipal = $produto->produto;
                    $itens[$key]['produtoPrincipal'] = $produtoPrincipal;
                    $itens[$key]['produto'] = $produto;

                    if($item->opcoes){
                        foreach($item->opcoes as $keyOp => $opcao){
                            $item->opcoes[$keyOp]['nome'] = $opcao->produto->nome;
                        }
                        $itens[$key]['opcoes'] = $item->opcoes;
                    }
                }
            }
            return compact('itens');
        }
    }

    public function statuspedido($id){
        $pedido = Pedido::with('statusPedido')->find($id);
        $listaStatus = [];
        if($pedido){
            $status = $pedido->statusPedido()->get();
            if($status){
                foreach($status as $key => $s){
                    $status[$key]['usuario'] = $s->usuario;
                    $status[$key]['excluir'] = route('painel.negocios.pedidos.excluirStatus',$s->id);
                    array_push($listaStatus,$s->status);
                }
            }
            return compact('status','pedido','listaStatus');
        }
    }

    public function salvarStatus($id, Request $request){
        $pedido = Pedido::find($id);

        if($pedido){
            $pedido->status = $request->status;
            $salvo = $pedido->save();

            if($salvo){
                $pedido->statusPedido()->create([
                    'status' => $request->status,
                    'obs' => $request->obs,
                    'user_id' => auth()->user()->id,
                ]);

                $result = ['status' => true,'texto' => 'Status atualizado com sucesso', 'link' => route('painel.negocios.pedidos.statuspedido',$pedido->id)];
                return compact('result');
            }
        }else{

        }
    }

    public function cancelarPedido($id){
        $pedido = Pedido::find($id);

        if($pedido){
            $pedido->status = 'cancelado';
            $salvo = $pedido->save();

            if($salvo){
                $pedido->statusPedido()->create([
                    'status' => 'cancelado',
                    'user_id' => auth()->user()->id,
                ]);

                $result = ['status' => true,'texto' => 'Status cancelado com sucesso', 'link' => ''];
                return compact('result');
            }
        }else{

        }
    }

    public function excluirStatus($id){
        $status = StatusPedidos::find($id);

        if($status){
            $pedido = $status->pedido;
            $excluido = $status->delete();
            if($excluido){
                $result = ['status' => true,'texto' => 'Status removido com sucesso', 'link' => route('painel.negocios.pedidos.statuspedido',$pedido->id)];
                return compact('result');
            }
        }
    }

    public function index(){
        event(new AssinaturaEvent());
        $user = auth()->user();
        $negocios = $user->negocios()->paginate(10);
        return view('Painel.negocios.index',compact('negocios'));
    }

    public function excluidos(){
        $user = auth()->user();
        $negocios = $user->negocios()->onlyTrashed()->get();
        if($negocios->first()){
            foreach($negocios as $key => $negocio){
                $negocios[$key]['imagem'] = $negocio->imagem;
                $negocios[$key]['linkExcluir'] = route('painel.negocios.exclusaoPermanente',$negocio->id);
                $negocios[$key]['linkRestaurar'] = route('painel.negocios.restaurar',$negocio->id);
            }
        }
        return compact('negocios');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Pais $paises, Estado $estados){
        event(new AssinaturaEvent());
        $paises = $paises->get();
        $estados = $estados->where('uf_pais','1')->get();
        return view('Painel.negocios.form',compact('paises','estados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NegocioRequest $request){
        $usuario = auth()->user();
        $negocios = $usuario->negocios;
        $assinatura = $usuario->assinatura()->whereNotIn('status',['canceled','ended'])->first();

        if($negocios->first() && $assinatura->max_registros == $negocios->count()){
            $result = ['status' => false,'texto' => "Você já alcançou o limite de negócios que seu plano contempla! \nEscolha um novo plano para melhor atender sua necessidade.", 'link' => ''];
            return compact('result');
        }else{
            $uri = Str::slug($request->nome);

            $salvo = $usuario->negocios()->create([
                'nome' => $request->nome,
                'uri' => $uri,
                'descricao' => $request->descricao,
                'telegram' => $request->telegram,
                'whatsapp' => $request->whatsapp,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
                'linkedin' => $request->linkedin,
                'pais_id' => $request->pais_id,
                'estado_id' => $request->estado_id,
                'cidade_id' => $request->cidade_id,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'bairro' => $request->bairro,
                'complemento' => $request->complemento,
                'modelo_lista' => $request->modelo_lista,
                'funcionamento' => json_encode($request->funcionamento),
            ]);

            if($salvo){
                $usuario->negocios()->attach($salvo->id);

                if(!empty($request->file('logotipo'))):
                    $extensao = '.'.$request->file('logotipo')->getClientOriginalExtension();
                    $path = 'negocio/'.$salvo->uuid;
                    $up = $request->file('logotipo')->storeAs($path,$uri.$extensao,'public');

                    if($up){
                        $salvo->logotipo = $uri.$extensao;
                        $salvo->save();
                    }
               endif;

               $configuracao = $salvo->configuracao()->create([
                    'modelo_lista' => $request->modelo_lista,
                    'mostrar_imagem_fundo' => (isset($request->mostrar_imagem_fundo) ? 1 : 0),
               ]);

                if($configuracao){
                    if(!empty($request->file('img_fundo'))){
                        $extensao = '.'.$request->file('img_fundo')->getClientOriginalExtension();
                        $path = 'negocio/'.$salvo->uuid;
                        $up = $request->file('img_fundo')->storeAs($path,$uri.$extensao,'public');
                        if($up){
                            $configuracao->img_fundo = 'bg_'.$uri.$extensao;
                            $configuracao->save();
                        }
                    }
                }

               $result = ['status' => true,'texto' => 'Negócio cadastrado com sucesso', 'link' => route('painel.negocios.edit',$salvo->id)];
               return compact('result');
            }else{

            }
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
    public function edit($id, Pais $paises, Estado $estados, Cidade $cidades)
    {
        event(new AssinaturaEvent());
        $usuario = auth()->user();
        $dados = $usuario->negocios()->find($id);
        if($dados){
            $paises = $paises->get();
            $estados = $estados->where('uf_pais',$dados->pais_id)->get();
            $cidades = $cidades->where('cd_uf',$dados->estado_id)->get();
            $funcionamento = json_decode($dados->funcionamento,true);
            $configuracao = $dados->configuracao;

            return view('Painel.negocios.form',compact('dados','paises','estados','cidades','funcionamento','configuracao'));
        }else{

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NegocioRequest $request, $id)
    {
        $usuario = auth()->user();
        $negocio = $usuario->negocios()->find($id);
        if($negocio){
            $uri = Str::slug($request->nome);

            $negocio->nome = $request->nome;
            $negocio->uri = $uri;
            $negocio->descricao = $request->descricao;
            $negocio->telegram = $request->telegram;
            $negocio->whatsapp = $request->whatsapp;
            $negocio->facebook = $request->facebook;
            $negocio->twitter = $request->twitter;
            $negocio->instagram = $request->instagram;
            $negocio->linkedin = $request->linkedin;
            $negocio->pais_id = $request->pais_id;
            $negocio->estado_id = $request->estado_id;
            $negocio->cidade_id = $request->cidade_id;
            $negocio->cep = $request->cep;
            $negocio->logradouro = $request->logradouro;
            $negocio->numero = $request->numero;
            $negocio->bairro = $request->bairro;
            $negocio->complemento = $request->complemento;
            $negocio->funcionamento = json_encode($request->funcionamento);

            $salvo = $negocio->save();

            if($salvo){
                if(!empty($request->file('logotipo'))){
                    if(Storage::disk('public')->exists('negocio/'.$negocio->uuid.'/'.$negocio->logotipo)):
                        Storage::disk('public')->delete('negocio/'.$negocio->uuid.'/'.$negocio->logotipo);
                    endif;

                    $extensao = '.'.$request->file('logotipo')->getClientOriginalExtension();
                    $path = 'negocio/'.$negocio->uuid;
                    $up = $request->file('logotipo')->storeAs($path,$uri.$extensao,'public');
                    if($up){
                        $negocio->logotipo = $uri.$extensao;
                        $negocio->save();
                    }
                }

                $configuracao = $negocio->configuracao;

                if($configuracao){
                    $configuracao->modelo_lista = $request->modelo_lista;
                    $configuracao->mostrar_imagem_fundo = (isset($request->mostrar_imagem_fundo) ? 1 : 0);
                    $configuracao->save();

                    if(!empty($request->file('img_fundo'))){
                        if(Storage::disk('public')->exists('negocio/'.$negocio->uuid.'/'.$configuracao->img_fundo)):
                            Storage::disk('public')->delete('negocio/'.$negocio->uuid.'/'.$configuracao->img_fundo);
                        endif;

                        $extensao = '.'.$request->file('img_fundo')->getClientOriginalExtension();
                        $path = 'negocio/'.$negocio->uuid;
                        $up = $request->file('img_fundo')->storeAs($path,'bg_'.$uri.$extensao,'public');
                        if($up){
                            $configuracao->img_fundo = 'bg_'.$uri.$extensao;
                            $configuracao->save();
                        }
                    }
                }else{
                    $configuracao = $negocio->configuracao()->create([
                        'modelo_lista' => $request->modelo_lista,
                        'mostrar_imagem_fundo' => (isset($request->mostrar_imagem_fundo) ? 1 : 0),
                    ]);

                    if($configuracao){
                        if(!empty($request->file('img_fundo'))){
                            $extensao = '.'.$request->file('img_fundo')->getClientOriginalExtension();
                            $path = 'negocio/'.$negocio->uuid;
                            $up = $request->file('img_fundo')->storeAs($path,$uri.$extensao,'public');
                            if($up){
                                $configuracao->img_fundo = 'bg_'.$uri.$extensao;
                                $configuracao->save();
                            }
                        }
                    }
                }

                $result = ['status' => true,'texto' => 'Negócio atualizado com sucesso', 'link' => ''];
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
        $negocio = $this->Negocios->find($id);

        if($negocio){
            $excluido = $negocio->delete();
            if($excluido){
                $result = ['status' => true,'texto' => 'Negócio excluído com sucesso', 'link' => ''];
                return compact('result');
            }
        }
    }

    public function restaurar($id){
        $usuario = auth()->user();
        $negocios = $usuario->negocios;
        $assinatura = $usuario->assinatura()->whereNotIn('status',['canceled','ended'])->first();
        $negocio = $usuario->negocios()->withTrashed()->find($id);

        if($negocios->first() && $assinatura->max_registros == $negocios->count()){
            $result = ['status' => false,'texto' => "Você já alcançou o limite de negócios que seu plano contempla! \nEscolha um novo plano para melhor atender sua necessidade.", 'link' => ''];
            return compact('result');
        }else{
            if($negocio){
                $restaurado = $negocio->restore();
                if($restaurado){
                    $result = ['status' => true,'texto' => 'Negócio restaurado com sucesso', 'link' => ''];
                    return compact('result');
                }
            }
        }
    }

    public function exclusao_permanente($id){
        $negocio = $this->Negocios->withTrashed()->find($id);
        if($negocio){
            $excluido = $negocio->forceDelete();
            if($excluido){
                if(Storage::disk('public')->exists($negocio->logotipo)):
                    Storage::disk('public')->delete($negocio->logotipo);
                endif;

                $result = ['status' => true,'texto' => 'Negócio excluído com sucesso', 'link' => ''];
                return compact('result');
            }
        }
    }

    public function produtos($id){
        $negocio = $this->Negocios->find($id);
        if($negocio){
            return view('painel.negocios.produtos',compact('negocio'));
        }else{

        }
    }
}

