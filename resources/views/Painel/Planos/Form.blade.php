@extends('layouts.PainelTemplate')

@section('header')
<div class="panel-header ">
    <div class="header text-center">
        <h2 class="title">Cadastrar Plano</h2>
        <p>
            <a href="{{route('painel.planos.admin')}}" class="btn btn-default"><i class="fa fa-list"></i> Cadastros</a>
            <a href="{{route('painel.planos.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Plano</a>
        </p>
    </div>
</div>
@stop

@section('conteudo')
    <form name="formPlanos" action="{{(isset($plano) ? route('painel.planos.update',$plano->id) : route('painel.planos.store'))}}" method="POST">
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><input type="checkbox" name="criar_pagarme" > Criar no Pagarme</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome no Pagarme</label>
                            <input type="text" name="nome_pagarme" value="{{(isset($plano) && !empty($plano->nome_pagarme) ? $plano->nome_pagarme : '')}}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome na plataforma</label>
                            <input type="text" name="nome" value="{{(isset($plano) && !empty($plano->nome) ? $plano->nome : '')}}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Código</label>
                            <input type="text" name="codigo" value="{{(isset($plano) && !empty($plano->codigo) ? $plano->codigo : '')}}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dias Cobrança</label>
                            <input type="text" name="dias_cobranca" value="30" value="{{(isset($plano) && !empty($plano->dias_cobranca) ? $plano->dias_cobranca : '')}}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dias Avisos</label>
                            <input type="text" name="avisos_antes" value="{{(isset($plano) && !empty($plano->avisos_antes) ? $plano->avisos_antes : '5')}}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Valor</label>
                            <input type="text" name="valor" value="{{(isset($plano) && !empty($plano->valor) ? $plano->valor : '')}}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Periodo Testes</label>
                            <input type="text" name="periodo_testes" value="{{(isset($plano) && !empty($plano->periodo_testes) ? $plano->periodo_testes : '10')}}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Total Registros</label>
                            <input type="text" name="total_registros" value="{{(isset($plano) && !empty($plano->total_registros) ? $plano->total_registros : '')}}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" >
                                <option value="A" {{(isset($plano) && $plano->status == 'A' ? 'selected' : '')}}>Ativo</option>
                                <option value="D" {{(isset($plano) && $plano->status == 'D' ? 'selected' : '')}}>Desativado</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Descrição</label>
                            <button type="button" class="btn btn-default adicionarDescricaoPlano"><i class="fa fa-plus"></i> Adicionar</button>
                            <div class="row ">
                                <div class="col-md-12 listaDescricao">
                                    @if(isset($itens))
                                        @forelse($itens as $key => $item)
                                            <div id="item_{{$key}}" class="row item">
                                                <div class="col-md-1">{{$key}}</div>

                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="item[{{$key}}][nome]" value="{{$item['nome']}}" placeholder="Descrição">
                                                </div>

                                                <div class="col-md-1">
                                                    <button type="button" id="{{$key}}" class="btn btn-sm btn-danger removerItemPlano"><i class="fa fa-trash" title="Remover Item"></i></button>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                </div>
            </div>
        </div>
    </form>
@stop
