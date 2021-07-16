@extends('layouts.PainelTemplate')

@section('header')
<div class="panel-header">
    <div class="header text-center">
        <h2 class="title">Produtos: Negócio {{$negocio->nome}}</h2>
        <p>
            <button type="button" class="btn btn-primary  addProduto"><i class="fa fa-plus"></i> Adicionar produto</button>
            <a href="{{route('painel.negocios.edit',$negocio->id)}}" class="btn btn-success"><i class="fa fa-edit"></i> Dados do negócio</a>
            <a href="{{route('site.negocio',$negocio->uri)}}" class="btn btn-secundary" target="_blank"><i class="fa fa-eye"></i> Ver página do seu negócio</a>
        </p>
    </div>
</div>
<button type="button" class="btn btn-primary addProduto" style="position:fixed; bottom:5%; right:30px; z-index:99999; border-radius:100%;"><i class="fa fa-plus"></i> Produto</button>
@stop

@section('conteudo')

<form action="{{route('painel.produtos.store')}}" name="formProdutosNegocio" method="POST">
    <input type="hidden" name="negocio_id" value="{{$negocio->id}}" >
    <div class="row boxLista">
        @forelse($negocio->produtos()->whereNull('produtos_id')->orderBy('created_at','desc')->get() as $produto)
        <div id="produto_{{$produto->id}}" class="col-md-12 produto">
            <input type="hidden" name="produto[{{$produto->id}}][id]" value="{{$produto->id}}">
            <div class="card">
                <div class="card-header">{{$produto->nome}}</div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12 d-flex">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="produto[{{$produto->id}}][indisponivel]" value="{{($produto->indisponivel ? '1' : '0')}}" {{($produto->indisponivel ? 'checked' : '')}} class="custom-control-input produtoIndisponivel" id="prodIndisponivel_{{$produto->id}}">
                                <label class="custom-control-label" for="prodIndisponivel_{{$produto->id}}">Indisponível</label>
                            </div>

                            <div class="custom-control custom-switch ml-3">
                                <input type="checkbox" name="produto[{{$produto->id}}][mostrar]" value="{{($produto->mostrar ? '1' : '0')}}" {{($produto->mostrar ? 'checked' : '')}} class="custom-control-input produtoMostrar" id="prodMostrar_{{$produto->id}}">
                                <label class="custom-control-label" for="prodMostrar_{{$produto->id}}">Mostrar Produto</label>
                            </div>
                        </div>

                        <div class="col-md-2 text-center">
                            <label>400x400</label>
                            <div class="form-group">
                                <label for="logotipo_produto_{{$produto->id}}"><img src="{{$produto->imagem}}" class="preview_{{$produto->id}}" width="100"></label>
                                <input type="file" class="hidden file_preview"  style="cursor:pointer;" data-preview=".preview_{{$produto->id}}" name="produto[{{$produto->id}}][logotipo]" id="logotipo_produto_{{$produto->id}}">
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input type="text" class="form-control nomeProduto" name="produto[{{$produto->id}}][nome]" value="{{$produto->nome}}" placeholder="Nome">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Descrição</label>
                                        <input type="text" class="form-control" name="produto[{{$produto->id}}][descricao]" value="{{$produto->descricao}}" placeholder="Descrição">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Valor</label>
                                        <input type="text" class="form-control" name="produto[{{$produto->id}}][valor]" value="{{$produto->valor}}" placeholder="Valor">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="accordion" id="accordionProduto{{$produto->id}}">
                                <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#collapseItem{{$produto->id}}" aria-expanded="false" aria-controls="collapseOne">
                                    <h4 class="p-0 m-0">Itens</h4>
                                </button>

                                  <div id="collapseItem{{$produto->id}}" class="collapse shadow-sm" aria-labelledby="headingOne" data-parent="#accordionProduto{{$produto->id}}">
                                    <button type="button" id="{{$produto->id}}" class="btn btn-info addItemProduto"><i class="fa fa-plus"></i> Itens</button>
                                    <div class="row listaItens " style="max-height: 400px; overflow-x: auto">
                                        @forelse($produto->itens as $item)
                                            <div id="item_{{$item->id}}" class="col-md-6 item">
                                                <input type="hidden" name="produto[{{$produto->id}}][item][{{$item->id}}][id]" value="{{$item->id}}">
                                                <div class="card">
                                                <div class="card-header">{{$item->nome}}</div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12 d-flex">
                                                                <div class="custom-control custom-switch">
                                                                    <input type="checkbox" name="produto[{{$produto->id}}][item][{{$item->id}}][indisponivel]" value="{{($item->indisponivel ? 1 : 0)}}" {{($item->indisponivel ? 'checked' : '')}} class="custom-control-input produtoIndisponivel" id="itemIndisponivel_{{$item->id}}">
                                                                    <label class="custom-control-label" for="itemIndisponivel_{{$item->id}}">Indisponível</label>
                                                                </div>

                                                                <div class="custom-control custom-switch ml-3">
                                                                    <input type="checkbox" name="produto[{{$produto->id}}][item][{{$item->id}}][mostrar]" value="{{($item->mostrar ? '1' : '0')}}" {{($item->mostrar ? 'checked' : '')}} class="custom-control-input produtoMostrar" id="itemMostrar_{{$item->id}}">
                                                                    <label class="custom-control-label" for="itemMostrar_{{$item->id}}">Mostrar Produto</label>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 text-center">
                                                                <label>400x400</label>
                                                                <div class="form-group">
                                                                    <label for="logotipo_item_{{$item->id}}"><img src="{{$item->imagem_item}}" class="preview_item_{{$item->id}}" width="70"></label>
                                                                    <input type="file" class="hidden file_preview"  style="cursor:pointer;" data-preview=".preview_item_{{$item->id}}" name="produto[{{$produto->id}}][item][{{$item->id}}][logotipo]" id="logotipo_item_{{$item->id}}">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-10">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Nome</label>
                                                                            <input type="text" id="{{$item->id}}" class="form-control form-control-sm nomeItem" name="produto[{{$produto->id}}][item][{{$item->id}}][nome]" value="{{$item->nome}}" placeholder="Nome">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Descrição</label>
                                                                            <input type="text" class="form-control form-control-sm" name="produto[{{$produto->id}}][item][{{$item->id}}][descricao]" value="{{$item->descricao}}" placeholder="Descrição">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Valor</label>
                                                                            <input type="text" class="form-control form-control-sm" name="produto[{{$produto->id}}][item][{{$item->id}}][valor]" value="{{$item->valor}}" placeholder="Valor">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Máximo opção</label>
                                                                            <input type="number" class="form-control form-control-sm" min="1" name="produto[{{$produto->id}}][item][{{$item->id}}][max_opcoes]" value="{{$item->max_opcoes}}" placeholder="Máximo de opções">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Máximo opção paga</label>
                                                                            <input type="number" class="form-control form-control-sm" min="1" name="produto[{{$produto->id}}][item][{{$item->id}}][max_opcoes_pagas]" value="{{$item->max_opcoes_pagas}}" placeholder="Máximo de opções pagas">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-12"><hr></div>
                                                            <div class="col-md-12 text-right"><button type="button" id="{{$item->id}}" data-item="{{$item->id}}" data-link="{{route('painel.produtos.destroy',$item->id)}}" class="btn btn-sm btn-danger removerItem"><i class="fa fa-trash"></i></button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                  </div>

                                  <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#collapseOpcao{{$produto->id}}" aria-expanded="false" aria-controls="collapseOne">
                                    <h4 class="p-0 m-0">Opcoes</h4>
                                </button>

                                <div id="collapseOpcao{{$produto->id}}" class="collapse shadow-sm" aria-labelledby="headingOne" data-parent="#accordionProduto{{$produto->id}}">
                                    <button type="button" id="{{$produto->id}}" class="btn btn-info addOpcao"><i class="fa fa-plus"></i> Itens</button>
                                    <div class="row listaOpcoes"  style="max-height: 400px; overflow-x: auto">
                                        @forelse($produto->opcoes as $item)
                                            <div id="item_{{$item->id}}" class="col-md-6 item">
                                                <input type="hidden" name="produto[{{$produto->id}}][sub][{{$item->id}}][id]" value="{{$item->id}}">
                                                <div class="card">
                                                <div class="card-header">{{$item->nome}}</div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12 d-flex">
                                                                <div class="custom-control custom-switch">
                                                                    <input type="checkbox" name="produto[{{$produto->id}}][sub][{{$item->id}}][indisponivel]" value="{{($item->indisponivel ? 1 : 0)}}" {{($item->indisponivel ? 'checked' : '')}} class="custom-control-input produtoIndisponivel" id="itemIndisponivel_{{$item->id}}">
                                                                    <label class="custom-control-label" for="itemIndisponivel_{{$item->id}}">Indisponível</label>
                                                                </div>

                                                                <div class="custom-control custom-switch ml-3">
                                                                    <input type="checkbox" name="produto[{{$produto->id}}][sub][{{$item->id}}][mostrar]" value="{{($item->mostrar ? '1' : '0')}}" {{($item->mostrar ? 'checked' : '')}} class="custom-control-input produtoMostrar" id="itemMostrar_{{$item->id}}">
                                                                    <label class="custom-control-label" for="itemMostrar_{{$item->id}}">Mostrar Produto</label>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 text-center">
                                                                <label>400x400</label>
                                                                <div class="form-group">
                                                                    <label for="logotipo_item_{{$item->id}}"><img src="{{$item->imagem_item}}" class="preview_item_{{$item->id}}" width="70"></label>
                                                                    <input type="file" class="hidden file_preview"  style="cursor:pointer;" data-preview=".preview_item_{{$item->id}}" name="produto[{{$produto->id}}][sub][{{$item->id}}][logotipo]" id="logotipo_item_{{$item->id}}">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-10">
                                                                <div class="form-group">
                                                                    <label>Nome</label>
                                                                    <input type="text" id="{{$item->id}}" class="form-control nomeItem" name="produto[{{$produto->id}}][sub][{{$item->id}}][nome]" value="{{$item->nome}}" placeholder="Nome">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Descrição</label>
                                                                    <input type="text" class="form-control" name="produto[{{$produto->id}}][sub][{{$item->id}}][descricao]" value="{{$item->descricao}}" placeholder="Descrição">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Valor</label>
                                                                    <input type="text" class="form-control" name="produto[{{$produto->id}}][sub][{{$item->id}}][valor]" value="{{$item->valor}}" placeholder="Valor">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12"><hr></div>
                                                            <div class="col-md-12 text-right"><button type="button" id="{{$item->id}}" data-item="{{$item->id}}" data-link="{{route('painel.produtos.destroy',$item->id)}}" class="btn btn-sm btn-danger removerItem"><i class="fa fa-trash"></i></button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                  </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-right"><button type="button" id="{{$produto->id}}" data-item="{{$produto->id}}" data-link="{{route('painel.produtos.destroy',$produto->id)}}" class="btn btn-sm btn-danger removerProduto"><i class="fa fa-trash"></i></button></div>

                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="col boxSemRegistro">
                <div class="card">
                    <div class="card-body text-center">
                        <h2><i class="fa fa-info"></i> Sem registro de produtos até o momento</h2>
                        <p><button type="button" class="btn btn-primary  addProduto"><i class="fa fa-plus"></i> Adicionar produto</button> </p>
                    </div>
                </div>
            </div>
        @endforelse

        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
        </div>
    </div>
</form>
@stop
