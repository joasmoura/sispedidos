@extends('layouts.PainelTemplate')

@section('header')
<div class="panel-header ">
    <div class="header text-center">
        @if(session('error'))
            <h2 class="title">{{session('error')}}</h2>
        @else
            <h2 class="title">Escolha o plano que se encaixa ao seu perfil</h2>
        @endif
        <p class="text-white">Abaixo estão os planos disponíveis em nossa plataforma, teste por 10 dias, cancele a hora que quiser
            @if(auth()->user()->conta_master)
                <a href="{{route('painel.planos.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i></a>
            @endif
        </p>
    </div>
</div>
@stop

@section('conteudo')
    <div class="card-group">
        @forelse($planos as $plano)
            <div class="card">
                <div class="card-header bg-info text-center text-white py-2" style="font-size: 18px; font-weight:bold;">{{$plano->nome}}</div>
                <div class="card-body">
                    <h5 class="card-title text-center bg-light text-secondary py-3" style="font-size:25px; font-weight: 800;">R$ {{numeroInteiroReal($plano->valor)}}</h5>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <tbody>
                                @if($plano->descricao)
                                    @php
                                        $itens = json_decode($plano->descricao,true);
                                    @endphp

                                    @if($itens)
                                        @forelse($itens as $item)
                                            <tr>
                                                <td class="text-success"><i class="fa fa-check"></i> {{$item['nome']}}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    @if($usuario->assinatura()->first())
                        @if($assinatura && $assinatura->plano->codigo == $plano->codigo)
                            <span class="btn btn-success btn-block">SEU PLANO ATUAL</span>
                        @else
                            <a href="{{route('painel.assinatura.assinar',$plano->codigo)}}" class="btn btn-primary btn-block">Assinar</a>
                        @endif
                    @else
                        <a href="{{route('painel.assinatura.trial',$plano->codigo)}}" class="btn btn-primary btn-block">{{$plano->periodo_testes}} dias Grátis</a>
                    @endif
                </div>
            </div>
        @empty
        @endforelse

    </div>
@stop
