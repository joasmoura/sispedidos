@extends('layouts.PainelTemplate')

@section('header')
<div class="panel-header ">
    <div class="header text-center">
        <h2 class="title">Período de testes para no plano <strong>{{$plano->nome}}</strong></h2>
        <p class="text-white">Cadastre seus negócios, compartilhe sua vitrine de produtos em suas redes sociais ou com seus amigos</p>
    </div>
</div>
@stop

@section('conteudo')
    <form name="formTrial" action="{{route('painel.assinatura.confirmar_trial',$plano->codigo)}}" method="POST">
        <input type="hidden" name="plano_id" value="{{$plano->id}}">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-primary text-white active">
                                <input type="radio"  name="metodoPagamento" value="boleto" autocomplete="off" checked="checked"> Boleto
                            </label>
                            <label class="btn btn-primary text-white">
                                <input type="radio"  name="metodoPagamento" value="credit_card" autocomplete="off"> Cartão de Crédito
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12"><br></div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <h5>O preenchimento dos dados para cobrança só será solicitado próximo de encerrar o período de teste </h5>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control" style="font-weight: 800;">Cadastre até {{$plano->total_registros}} negócios</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control " style="font-weight: 800;">Teste gratuitamente po {{$plano->total_registros}} dias</label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </form>
@stop
