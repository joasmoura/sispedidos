@extends('layouts.PainelTemplate')

@section('header')
    <div class="panel-header">
        <div class="header text-center">
            @if($negocio)
                <h2 class="title">Pedidos: <strong>{{$negocio->nome}}</strong></h2>
            @else
            <h2 class="title">Selecione um dos negócios</h2>
            @endif

            <a href="{{route('painel.negocios.pedidos')}}" class="btn btn-info"><i class="fa fa-check"></i> Selecione um negócio</a>
        </div>
    </div>
@stop

@section('conteudo')

    @if($negocio)
        <div class="card">
            <div class="card-header">
                <form  method="GET" class="row">

                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <label class="input-group-prepend" for="npedido">
                              <span class="input-group-text px-1 py-1" id="basic-addon1">Nº</span>
                            </label>
                            <input type="text" class="form-control" name="pedido" value="{{(isset($pesquisa['pedido']) ? $pesquisa['pedido'] : '')}}" id="npedido" placeholder="123" >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <label class="input-group-prepend" for="status">
                              <span class="input-group-text px-1 py-1" id="basic-addon1">Status</span>
                            </label>
                            <select type="text" class="custom-select" name="status" id="status">
                                <option value="">Todos</option>
                                <option value="menosCancelado" {{(isset($pesquisa['status']) && $pesquisa['status'] == 'menosCancelado' ? 'selected' : '')}}>Menos os cancelados</option>
                                <option value="pedido" {{(isset($pesquisa['status']) && $pesquisa['status'] == 'pedido' ? 'selected' : '')}}>Pedidos</option>
                                <option value="preparacao" {{(isset($pesquisa['status']) && $pesquisa['status'] == 'preparacao' ? 'selected' : '')}}>Em preparação</option>
                                <option value="acaminho" {{(isset($pesquisa['status']) && $pesquisa['status'] == 'acaminho' ? 'selected' : '')}}>A caminho</option>
                                <option value="entregue" {{(isset($pesquisa['status']) && $pesquisa['status'] == 'entregue' ? 'selected' : '')}}>Entregues</option>
                                <option value="cancelado" {{(isset($pesquisa['status']) && $pesquisa['status'] == 'cancelado' ? 'selected' : '')}}>Cancelados</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                              <span class="input-group-text px-1 py-0" id="basic-addon1">De</span>
                            </div>
                            <input type="text" class="form-control data" name="de" value="{{(isset($pesquisa['de']) ? $pesquisa['de'] : date('d/m/Y'))}}" placeholder="dd/mm/yyy" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                              <span class="input-group-text px-1 py-0" id="basic-addon1">Até</span>
                            </div>
                            <input type="text" class="form-control data" name="ate" value="{{(isset($pesquisa['ate']) ? $pesquisa['ate'] : date('d/m/Y'))}}" placeholder="dd/mm/yyy" >
                        </div>
                    </div>

                    <div class="col-md-1 p-0 m-0">
                        <button type="submit" class="btn btn-success btn-sm m-0"><i class="fa fa-search"></i></button>
                    </div>

                    <div class="col-md-1 p-0 m-0 "><button type="button" title="Imprimir pedidos" class="btn btn-sm m-0 imprimirPedidos"><i class="fa fa-print"></i></button></div>
                </form>
            </div>

            <div class="card-body">
                <div id="tabelaPedidos" class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Data</th>
                                <th>Observações</th>
                                <th>Status</th>
                                <th>Valor Total</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $totalPedido = 0;
                            @endphp

                            @forelse($pedidos as $pedido)
                                @php
                                    $itens = $pedido->itens()->get();
                                @endphp
                                <tr class="{{($pedido->status == 'cancelado' ? 'bg-danger text-white' : '')}}">
                                    <td>{{$pedido->pedido}}</td>
                                    <td>
                                        {{date('d/m/Y H:i:s',strtotime($pedido->created_at))}}
                                    </td>
                                    <td>{{$pedido->observacao}}</td>
                                    <td>{{$pedido->status}}</td>
                                    <td>
                                        @php
                                            $valorItem = 0;

                                                $itens = $pedido->itens()->get();
                                                if($itens->first()){
                                                    foreach($itens as $item){
                                                        $opcoes = $item->opcoes()->get();
                                                        if(empty($item->valor)){
                                                            if($opcoes){
                                                                foreach($opcoes as $opcao){
                                                                    $valorItem += $opcao->valor*$opcao->qtd;
                                                                }
                                                            }
                                                        }else{
                                                            $valorItem += $item->valor*$item->qtd;

                                                            if($opcoes){
                                                                foreach($opcoes as $opcao){
                                                                    $valorItem += $opcao->valor*$item->qtd;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                $totalPedido += $valorItem;
                                        @endphp

                                        R$ {{valorReal($valorItem)}}
                                    </td>

                                    <td>
                                        <button type="button" data-link="{{route('painel.negocios.pedidos.itenspedido',$pedido->id)}}" data-pedido="{{$pedido->pedido}}" class="btn btn-info btn-sm itensPedido"><i class="fa fa-list"></i> Itens</button>
                                        <button type="button" data-link="{{route('painel.negocios.pedidos.statuspedido',$pedido->id)}}" data-action="{{route('painel.negocios.pedidos.salvarStatus',$pedido->id)}}" data-pedido="{{$pedido->pedido}}" class="btn btn-success btn-sm statusPedido"><i class="fa fa-check"></i> Status</button>
                                        @if($pedido->status != 'cancelado')
                                            <button type="button" data-link="{{route('painel.negocios.pedidos.cancelarPedido',$pedido->id)}}" class="btn btn-danger btn-sm cancelarPedido"><i class="fa fa-ban"></i> Cancelar</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse

                            <tr>
                                <td colspan="4" class="text-center"><strong>Total</strong></td>
                                <td>R$ {{valorReal($totalPedido)}}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer"></div>
        </div>
    @else
        <div class="row">
            @forelse($negocios as $negocio)
                <a class="col-md-3" href="{{route('painel.negocios.pedidos',$negocio->id)}}">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="card-title">Selecione um negócio</span>
                            <p><img src="{{$negocio->imagem}}" width="100"></p>
                            <h3>{{$negocio->nome}}</h3>
                        </div>
                    </div>
                </a>
            @empty
            @endforelse
        </div>
    @endif

@stop
