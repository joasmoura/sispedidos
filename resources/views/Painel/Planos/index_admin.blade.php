@extends('layouts.PainelTemplate')

@section('header')
<div class="panel-header ">
    <div class="header text-center">
        <h2 class="title">Planos</h2>
        <p><a href="{{route('painel.planos.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Plano</a> </p>
    </div>
</div>
@stop

@section('conteudo')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th>Assinaturas</th>
                            <th>Quantidade Cadastro</th>
                            <th>Periodo Teste</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($planos as $plano)
                            <tr id="plano_{{$plano->id}}">
                                <td>{{$plano->nome}}</td>
                                <td>{{$plano->valor}}</td>
                                <td></td>
                                <td>{{$plano->total_registros}}</td>
                                <td>{{$plano->periodo_testes}}</td>
                                <td>
                                    <a href="{{route('painel.planos.edit',$plano->id)}}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="{{route('painel.planos.destroy',$plano->id)}}" id="{{$plano->id}}" class="btn btn-danger btn-sm excluirPlano"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Não há planos cadastrados no momento</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
