<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Negocios;
use App\Models\Pedido;
use App\Models\Produtos;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(){
        $seo = $this->seo->render('SisPedidos','Site de cardápio e pedidos online',url()->current(),'');
        return view('Site.index',compact('seo'));
    }

    public function negocio($uri, Negocios $negocios){
        $negocio = $negocios->where('uri',$uri)->with('produtos','configuracao')->first();
        $dataAtual = date('Y-m-d');

        if($negocio){
            env('CLIENT_SOCIAL_FACEBOOK_PAGE',$negocio->facebook);
            $produtos = $negocio->produtos()->with('itens')->whereNull('produtos_id')->get();
            $funcionamento = json_decode($negocio->funcionamento,true);
            $mostrar_funcionamento = false;

            if($funcionamento){
                foreach($funcionamento as $dia){
                    if((!empty($dia['Abre']) && !empty($dia['Fecha']))){
                        $mostrar_funcionamento = true;
                    }
                }
            }

            $configuracao = $negocio->configuracao;

            $seo = $this->seo->render($negocio->nome,$negocio->descricao,url()->current(),$negocio->imagem);
            return view('Site.negocio.index',compact('negocio','seo','produtos','funcionamento','dataAtual','mostrar_funcionamento','configuracao'));
        }else{
            return route('site.index');
        }
    }

    public function realizar_pedido($uri, Request $request, Negocios $negocios){
        $negocio = $negocios->where('uri',$uri)->first();


        if($negocio){
            $negocio->whatsapp = str_replace(['(',')'],['',''],$negocio->whatsapp);

            $novoPedido = $negocio->pedidos()->create([
                'pedido' => date('y').$negocio->id.$negocio->pedidos()->count(),
                'negocio_id' => $negocio->id,
                'observacao' => $request->observacoes,
                'status' => 'pedido'
            ]);

            if($novoPedido){
                if(isset($request->carrinho)){
                    foreach($request->carrinho as $carrinho){
                        $itemSalvo = $novoPedido->itens()->create([
                            'produto_id' => $carrinho['id'],
                            'qtd' => $carrinho['qtd'],
                            'valor' => $carrinho['valor'],
                        ]);

                        if($itemSalvo){
                            if(isset($carrinho['opcoes'])){
                                foreach($carrinho['opcoes'] as $opcao){
                                    $itemSalvo->opcoes()->create([
                                        'produto_id' => $opcao['id'],
                                        'qtd' => (!empty($carrinho['valor']) ? $carrinho['qtd'] : $opcao['qtd']),
                                        'valor' => $opcao['valor'],
                                    ]);
                                }
                            }
                        }
                    }
                }

                $novoPedido->statusPedido()->create([
                    // 'pedido_id' => $novoPedido->id,
                    'status' => 'pedido',
                    'obs' => 'Pedido pelo usuário através da vitrine'
                ]);
            }

            $result = ['status' => true,'texto' => '', 'tel' => $negocio->whatsapp];
           return compact('result');
        }
    }

    public function retornaOpcoes($uri,$idProduto,$idItem){
        $negocio = Negocios::where('uri',$uri)->with('produtos')->first();

        if($negocio){
            $produto = $negocio->produtos()->with('opcoes','itens')->find($idProduto);
            $item = $produto->itens()->select('id','valor','max_opcoes','max_opcoes_pagas')->find($idItem);
            $opcoes = $produto->opcoes()->get();

            foreach($opcoes as $key => $opcao){
                $opcoes[$key]['item_id'] = $item->id;
            }

            if($opcoes){
                return compact('opcoes','item');
            }
        }
    }
}
