@extends('layouts.PainelTemplate')

@section('titulo','Negócios')

@section('header')
<div class="panel-header">
    <div class="header text-center">
        <h2 class="title">NEGÓCIOS</h2>
        <a href="{{route('painel.negocios.excluidos')}}" class="btn btn-danger  modalNegociosExcluidos"><i class="fa fa-trash"></i> Excluídos</a>
        <a href="{{route('painel.negocios.create')}}" class="btn btn-btn-outline-default verificaCadastros" data-verifica="{{route('painel.negocios.verifica')}}"><i class="fa fa-plus"></i> Novo Cadastro</a>
    </div>
</div>
@stop

@section('conteudo')
    <div class="row">
        @forelse($negocios as $negocio)
            <div id="negocio_{{$negocio->id}}" class="col-md-4 ">
                <div class="card">
                    <div class="card-header card-chart">
                        <h6 class="title text-center">{{$negocio->nome}}</h6>
                        <div class="dropdown">
                        </div>
                    </div>

                    <div class="card-body text-center">
                        <img src="{{$negocio->imagem}}" width="100">
                    </div>

                    <div class="card-footer">
                        <a href="{{route('painel.negocios.destroy',$negocio->id)}}" id="{{$negocio->id}}" class="btn btn-danger btn-round btn-simple btn-icon deletarNegocio" title="Deletar Negócio"><i class="fa fa-trash"></i></a>
                        <a href="{{route('painel.negocios.edit',$negocio->id)}}" class="btn btn-round btn-info  btn-simple btn-icon" title="Editar Negócio"><i class="fa fa-edit"></i></a>
                        <a href="{{route('painel.negocios.produtos',$negocio->id)}}" class="btn btn-primary btn-round btn-simple btn-icon" title="Configurar Produtos"><i class="fa fa-box-open"></i></a>
                        <a class="btn btn-default btn-simple btn-icon btn-round" target="_blank" href="{{route('site.negocio',$negocio->uri)}}" title="Página deste Negócio">
                            <i class="fa fa-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header card-chart">
                    <div class="dropdown">
                    </div>
                </div>

                <div class="card-body text-center">
                    <h2><i class="fa fa-info"></i> Sem registro de negócios até o momento</h2>
                    <p><a href="{{route('painel.negocios.create')}}" class="btn btn-success  "><i class="fa fa-plus"></i> Cadastrar novo negócio</a> </p>
                </div>
            </div>
        @endforelse

        <div class="col-md-12 ">
            {{$negocios->links()}}
        </div>

    </div>
@stop
