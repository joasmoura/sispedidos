<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $Produtos;
    public function __construct(Produtos $produtos)
    {
         $this->Produtos = $produtos;
    }

    public function index()
    {
        //
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
        $user = auth()->user();
        $negocio = $user->negocios()->find($request->negocio_id);
        if($negocio){
            if($request->produto){
                foreach($request->produto as $produto){
                    if(isset($produto['id']) && !empty($produto['id'])){
                        $this->atualizarProduto($produto,$negocio);
                    }else{
                        $this->criarProduto($produto,$negocio);
                    }
                }
                $result = ['status' => true,'texto' => 'Dados salvos com sucesso', 'link' => ''];
                return compact('result');
            }
        }else{
            $result = ['status' => false,'texto' => 'Nenhum produto adicionado!', 'link' => ''];
            return compact('result');
        }
    }

    public function criarProduto($produto, $negocio){
        $produtoCriado = $this->Produtos->create([
            'nome' => $produto['nome'],
            'uri' => Str::slug($produto['nome']),
            'descricao' => $produto['descricao'],
            'valor' => $produto['valor'],
            'indisponivel' => (isset($produto['indisponivel']) ? $produto['indisponivel'] : 0),
            'mostrar' => (isset($produto['mostrar']) ? $produto['mostrar'] : 0),
            'negocios_id' => $negocio->id
        ]);

        if($produtoCriado){
            if(!empty($produto['logotipo'])){
                $extensao = '.'.$produto['logotipo']->getClientOriginalExtension();
                $path = 'negocio/'.$negocio->uuid.'/produtos';
                $up = $produto['logotipo']->storeAs($path,$produto['nome'].$extensao,'public');
                if($up){
                    $produtoCriado->logotipo = 'produtos/'.$produto['nome'].$extensao;
                    $produtoCriado->save();
                }
            }

            if(isset($produto['item'])){
                foreach($produto['item'] as $item){
                    $this->criarItem($item,$produtoCriado->id,$negocio);
                }
            }

            if(isset($produto['sub'])){
                foreach($produto['sub'] as $opcao){
                    $this->criarOpcao($opcao,$produtoCriado->id,$negocio);
                }
            }
        }
    }

    public function atualizarProduto($produto, $negocio){
        $editaProduto = $this->Produtos->find($produto['id']);
        if($editaProduto){
            $editaProduto->nome = $produto['nome'];
            $editaProduto->descricao = $produto['descricao'];
            $editaProduto->valor = $produto['valor'];
            $editaProduto->indisponivel = (isset($produto['indisponivel']) ? $produto['indisponivel'] : 0 );
            $editaProduto->mostrar = (isset($produto['mostrar']) ? $produto['mostrar'] : 0 );
            $prodAtualizado = $editaProduto->save();

            if($prodAtualizado){
                if(!empty($produto['logotipo'])){
                    if(Storage::disk('public')->exists('negocio/'.$negocio->uuid.'/'.$editaProduto->logotipo)):
                        Storage::disk('public')->delete('negocio/'.$negocio->uuid.'/'.$editaProduto->logotipo);
                    endif;

                    $extensao = '.'.$produto['logotipo']->getClientOriginalExtension();
                    $path = 'negocio/'.$negocio->uuid.'/produtos';
                    $up = $produto['logotipo']->storeAs($path,$produto['nome'].$extensao,'public');
                    if($up){
                        $editaProduto->logotipo = 'produtos/'.$produto['nome'].$extensao;
                        $editaProduto->save();
                    }
                }

                if(isset($produto['item'])){
                    foreach($produto['item'] as $item){
                        if(isset($item['id']) && !empty($item['id'])){
                            $editaItem = $this->Produtos->find($item['id']);
                            if($editaItem){
                                $editaItem->nome = $item['nome'];
                                $editaItem->descricao = $item['descricao'];
                                $editaItem->valor = $item['valor'];
                                $editaItem->max_opcoes = $item['max_opcoes'];
                                $editaItem->max_opcoes_pagas = $item['max_opcoes_pagas'];
                                $editaItem->indisponivel = (isset($item['indisponivel']) ? $item['indisponivel'] : 0);
                                $editaItem->mostrar = (isset($item['mostrar']) ? $item['mostrar'] : 0);
                                $itemAtualizado = $editaItem->save();

                                if($itemAtualizado){
                                    if(!empty($item['logotipo'])){
                                        if(Storage::disk('public')->exists('negocio/'.$negocio->uuid.'/'.$editaItem->logotipo)):
                                            Storage::disk('public')->delete('negocio/'.$negocio->uuid.'/'.$editaItem->logotipo);
                                        endif;

                                        $extensao = '.'.$item['logotipo']->getClientOriginalExtension();
                                        $path = 'negocio/'.$negocio->uuid.'/produtos';
                                        $up = $item['logotipo']->storeAs($path,$item['nome'].$extensao,'public');
                                        if($up){
                                            $editaItem->logotipo = 'produtos/'.$item['nome'].$extensao;
                                            $editaItem->save();
                                        }
                                    }
                                }
                            }
                        }else{
                            $this->criarItem($item,$editaProduto->id,$negocio);
                        }
                    }
                }

                if(isset($produto['sub'])){
                    foreach($produto['sub'] as $opcao){
                        if(isset($opcao['id']) && !empty($opcao['id'])){
                            $editaItem = $this->Produtos->find($opcao['id']);
                            if($editaItem){
                                $editaItem->nome = $opcao['nome'];
                                $editaItem->descricao = $opcao['descricao'];
                                $editaItem->valor = $opcao['valor'];
                                $editaItem->indisponivel = (isset($opcao['indisponivel']) ? $opcao['indisponivel'] : 0);
                                $editaItem->mostrar = (isset($opcao['mostrar']) ? $opcao['mostrar'] : 0);
                                $itemAtualizado = $editaItem->save();

                                if($itemAtualizado){
                                    if(!empty($opcao['logotipo'])){
                                        if(Storage::disk('public')->exists('negocio/'.$negocio->uuid.'/'.$editaItem->logotipo)):
                                            Storage::disk('public')->delete('negocio/'.$negocio->uuid.'/'.$editaItem->logotipo);
                                        endif;

                                        $extensao = '.'.$opcao['logotipo']->getClientOriginalExtension();
                                        $path = 'negocio/'.$negocio->uuid.'/produtos';
                                        $up = $opcao['logotipo']->storeAs($path,$opcao['nome'].$extensao,'public');
                                        if($up){
                                            $editaItem->logotipo = 'produtos/'.$opcao['nome'].$extensao;
                                            $editaItem->save();
                                        }
                                    }
                                }
                            }
                        }else{
                            $this->criarOpcao($opcao,$produto['id'],$negocio);
                        }
                    }
                }
            }
        }
    }

    public function criarItem($item,$produto_id,$negocio){
        $itemCriado = $this->Produtos->create([
            'nome' => $item['nome'],
            'uri' => Str::slug($item['nome']),
            'descricao' => $item['descricao'],
            'valor' => $item['valor'],
            'max_opcoes' => $item['max_opcoes'],
            'max_opcoes_pagas' => $item['max_opcoes_pagas'],
            'indisponivel' => (isset($item['indisponivel']) ? $item['indisponivel'] : 0),
            'mostrar' => (isset($item['mostrar']) ? $item['mostrar'] : 0),
            'produtos_id' => $produto_id
        ]);

        if($itemCriado){
            if(!empty($item['logotipo'])){
                $extensao = '.'.$item['logotipo']->getClientOriginalExtension();
                $path = 'negocio/'.$negocio->uuid.'/produtos';
                $up = $item['logotipo']->storeAs($path,$item['nome'].$extensao,'public');
                if($up){
                    $itemCriado->logotipo = 'produtos/'.$item['nome'].$extensao;
                    $itemCriado->save();
                }
            }
        }
    }

    public function criarOpcao($opcao, $produto_id, $negocio){
        $itemCriado = $this->Produtos->create([
            'nome' => $opcao['nome'],
            'uri' => Str::slug($opcao['nome']),
            'descricao' => $opcao['descricao'],
            'valor' => $opcao['valor'],
            'indisponivel' => (isset($opcao['indisponivel']) ? $opcao['indisponivel'] : 0),
            'mostrar' => (isset($opcao['mostrar']) ? $opcao['mostrar'] : 0),
            'opcao_id' => $produto_id
        ]);

        if($itemCriado){
            if(!empty($opcao['logotipo'])){
                $extensao = '.'.$opcao['logotipo']->getClientOriginalExtension();
                $path = 'negocio/'.$negocio->uuid.'/produtos';
                $up = $opcao['logotipo']->storeAs($path,$opcao['nome'].$extensao,'public');
                if($up){
                    $itemCriado->logotipo = 'produtos/'.$opcao['nome'].$extensao;
                    $itemCriado->save();
                }
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
        $produto = $this->Produtos->with('negocio','itens')->find($id);
        if($produto){
            if(empty($produto->produtos_id)){
                $negocio = $produto->negocio;
                $itens = $produto->itens;
                $rProduto = $produto->delete();

                if($rProduto){
                    if(Storage::disk('public')->exists('negocio/'.$negocio->uuid.'/'.$produto->logotipo)):
                        Storage::disk('public')->delete('negocio/'.$negocio->uuid.'/'.$produto->logotipo);
                    endif;

                    if($itens){
                        foreach($itens as $item){
                            $rItem = $item->delete();
                            if($rItem){
                                if(Storage::disk('public')->exists('negocio/'.$negocio->uuid.'/'.$item->logotipo)):
                                    Storage::disk('public')->delete('negocio/'.$negocio->uuid.'/'.$item->logotipo);
                                endif;
                            }
                        }

                    }
                }
            }else{
                $negocio = $produto->produto->negocio;
                $rProduto = $produto->delete();
                if($rProduto){
                    if(Storage::disk('public')->exists('negocio/'.$negocio->uuid.'/'.$produto->logotipo)):
                        Storage::disk('public')->delete('negocio/'.$negocio->uuid.'/'.$produto->logotipo);
                    endif;
                }
            }
        }
    }
}
