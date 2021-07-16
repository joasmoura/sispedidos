@extends('layouts.PainelTemplate')

@section('header')
<div class="panel-header ">
    <div class="header text-center">
        <h2 class="title">Plano: {{$plano->nome}}</h2>
        <p class="text-white">Estamos quase acabando, preencha os dados para assinatura para concluir o processo</p>
    </div>
</div>
@stop

@section('conteudo')
    <form name="formAssinar" method="POST" action="{{route('painel.assinatura.confirmar_assinatura',$plano->codigo)}}">
        <input type="hidden" name="plano_id" value="{{$plano->id}}">
        <div class="card">
            <div class="card-header" style="font-size: 18px; font-weight:bold;"></div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-primary boxBotaoMetodo text-white active">
                                <input type="radio" name="ad_metodoPagamento" value="boleto" autocomplete="off" checked> Boleto
                            </label>
                            <label class="btn btn-primary boxBotaoMetodo text-white">
                                <input type="radio" name="ad_metodoPagamento" value="credit_card" autocomplete="off"> Cartão de Crédito
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12 ">
                        <div class="row pt-2 pb-2 boxCartaoCredito">
                            <div class="col-md-12">Dados do Cartão de Crédito</div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número do Cartão</label>
                                    <input type="text" class="form-control campoCartao" name="cartaoNumero" value="" required="" placeholder="Digite o número do cartão de Crédito">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Titular do Cartão</label>
                                    <input type="text" class="form-control campoCartao" name="cartaoNomeTitular" value="" required="" placeholder="Digite o nome conforme impresso no cartão de Crédito">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vencimento</label>
                                    <input type="text" class="form-control campoCartao" name="cartaoVencimento" value="" required="" placeholder="MM/YY">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código de Segurança (3 dígitos)</label>
                                    <input type="text" class="form-control campoCartao" name="cartaoCodigo" value="" required="" placeholder="123">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12"><hr></div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Se preferir usar os dados de um dos seus negócios, selecione-o abaixo</label>
                            <select name="selectNegocios" class="form-control selectNegocioAssinatura">
                                <option value=""></option>
                                @forelse(auth()->user()->negocios as $negocio)
                                    <option value="{{$negocio->id}}">{{$negocio->nome}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Seu nome ou nome de seu negócio</label>
                            <input type="text" class="form-control" name="nome" value="{{auth()->user()->name}} {{auth()->user()->sobrenome}}" placeholder="Seu nome ou nome de seu negócio">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>CPF/CNPJ</label>
                            <input type="text" class="form-control" name="documento" required value="" placeholder="CPF/CNPJ">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" class="form-control" required name="email" value="{{auth()->user()->email}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>CEP</label>
                            <input type="text" class="form-control" required name="cep" placeholder="CEP">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Logradouro</label>
                            <input type="text" class="form-control" required name="logradouro" placeholder="Logradouro">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bairro</label>
                            <input type="text" class="form-control" required name="bairro" placeholder="Bairro">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Número</label>
                            <input type="text" class="form-control" required name="numero">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" class="form-control" required name="complemento" placeholder="Complemento">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>País</label>
                            <input type="text" class="form-control" required name="pais" placeholder="País">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="text" class="form-control" required name="estado" placeholder="Estado">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" class="form-control" required name="cidade" placeholder="Cidade">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Celular</label>
                            <input type="text" class="form-control" required name="celular" placeholder="Celular">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block">Confirmar Assinatura</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('scripts')
<script src="{{asset('assets/js/scriptAssinatura.js')}}"></script>
@stop
