@extends('layouts.PainelTemplate')

@section('header')
<div class="panel-header ">
    <div class="header text-center">
        <h2 class="title">Plano: {{$plano->nome}}</h2>
        <p class="text-white">Dados do seu plano atual</p>
    </div>
</div>
@stop

@section('conteudo')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('painel.planos.index')}}" class=""><i class="fa fa-list"></i> Planos</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            Nome do plano
                        </div>

                        <div class="col-md-6">
                            {{$plano->nome}}
                        </div>

                        <div class="col-md-12"><hr></div>

                        <div class="col-md-6">
                            Número de negócios
                        </div>

                        <div class="col-md-6">
                            {{$assinatura->max_registros}}
                        </div>

                        <div class="col-md-12"><hr></div>

                        <div class="col-md-6">
                            Data Assinatura
                        </div>

                        <div class="col-md-6">
                            {{date('d/m/Y H:i', strtotime($assinatura->created_at))}}
                        </div>

                        <div class="col-md-12"><hr></div>

                        <div class="col-md-6">
                            Forma de pagamento
                        </div>

                        <div class="col-md-6">
                            {{($assinatura->metodo_pagamento == 'boleto' ? 'Boleto Bancário' : 'Cartão de Crédito')}}
                        </div>

                        <div class="col-md-12"><hr></div>

                        @if($assinatura->metodo_pagamento == 'credit_card')
                            <div class="col-md-6">
                                Últimos dígitos do cartão
                            </div>

                            <div class="col-md-6">

                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Suas Transações</div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Transação</th>
                                    <th>Valor</th>
                                    <th>Criado em</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($transacoes as $transacao)
                                    <tr>
                                        <td>{{$transacao->transacao_id}}</td>
                                        <td>R$ {{numeroInteiroReal($transacao->valor)}}</td>
                                        <td>{{date('d/m/Y H:i',strtotime($transacao->created_at))}}</td>
                                        <td>{{($assinatura->metodo_pagamento == 'boleto' ? date('d/m/Y',strtotime($transacao->vencimento)) : '')}}</td>
                                        <td>{{$transacao->status}}</td>
                                        <td>{{($transacao->codigo_boleto ? 'Código ' .$transacao->codigo_boleto : '')}}</td>
                                        <td>
                                            @if($assinatura->metodo_pagamento == 'boleto')
                                                <a href="{{$transacao->boleto_url}}" target="_blank"><i class="fa fa-link" ></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class=""><strong>R$ {{numeroInteiroReal($assinatura->valor)}}/mês</strong></h4>
                    <button type="button" class="btn btn-danger cancelarAssinatura"><i class="fa fa-ban"></i> Cancelar Assinatura</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script src="{{asset('assets/js/scriptAssinatura.js')}}"></script>
@stop
