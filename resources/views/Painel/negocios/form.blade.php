@extends('layouts.PainelTemplate')

@section('titulo',(isset($dados) ? 'Editar Negócio' :'Cadastrar Negócio'))

@section('header')
    <div class="panel-header ">
        <div class="header text-center">
            <h2 class="title">{{(isset($dados) ? 'Negócio: '.$dados->nome : 'Cadastrar novo negócio')}}</h2>
            @if(isset($dados))
                <p>
                    <a href="{{route('painel.negocios.produtos',$dados->id)}}" class="btn btn-primary  addProduto"><i class="fa fa-box-open"></i> Produtos</a>
                    <a href="{{route('site.negocio',$dados->uri)}}" class="btn btn-secundary" target="_blank"><i class="fa fa-eye"></i> Ver página do seu negócio</a>
                </p>
            @endif
        </div>
    </div>
@stop

@section('conteudo')
    <form name="formNegocio" action="{{(isset($dados) ? route('painel.negocios.update',$dados->id) : route('painel.negocios.store'))}}" method="POST">
        <div class="card">
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success" title="Salvar"><i class="far fa-save"></i> Salvar</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Dados</div>

            <div class="card-body">
                <div class="row">
                    @if(isset($dados))
                        @method('PUT')
                    @endif

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="logotipo"><img src="{{(isset($dados) ? $dados->imagem  : url('assets/imgs/sem-foto.png'))}}" class="preview" width="100"></label>
                            <input type="file" class="hidden file_preview"  style="cursor:pointer;" data-preview=".preview" name="logotipo" id="logotipo">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" value="{{(isset($dados) && !empty($dados->nome) ? $dados->nome : '')}}" class="form-control" placeholder="Nome do negócio">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea name="descricao" rows="10" class="form-control" placeholder="Descrição sobre o negócio">{{(isset($dados) && !empty($dados->descricao) ? $dados->descricao : '')}}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telegram</label>
                            <input type="text" name="telegram" value="{{(isset($dados) && !empty($dados->telegram) ? $dados->telegram : '')}}" class="form-control" placeholder="Telegram">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Whatsapp</label>
                            <input type="text" name="whatsapp" value="{{(isset($dados) && !empty($dados->whatsapp) ? $dados->whatsapp : '')}}" class="form-control" placeholder="Whatsapp">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="text" name="facebook" value="{{(isset($dados) && !empty($dados->facebook) ? $dados->facebook : '')}}" class="form-control" placeholder="Facebook">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Twitter</label>
                            <input type="text" name="twitter" value="{{(isset($dados) && !empty($dados->twitter) ? $dados->nome : '')}}" class="form-control" placeholder="Twitter">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Instagram</label>
                            <input type="text" name="instagram" value="{{(isset($dados) && !empty($dados->instagram) ? $dados->instagram : '')}}" class="form-control" placeholder="Instagram">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Linkedin</label>
                            <input type="text" name="linkedin" value="{{(isset($dados) && !empty($dados->linkedin) ? $dados->linkedin : '')}}" class="form-control" placeholder="Linkedin">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card" >
            <div class="card-header">Endereço</div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <label>Cep</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="cep" value="{{(isset($dados) && !empty($dados->cep) ? $dados->cep : '')}}" placeholder="CEP" aria-label="CEP" >
                            <div class="input-group-append">
                            <button class="btn btn-secundary m-0 buscarEndereco" type="button" title="Buscar endereço"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12"></div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>País</label>
                            <select name="pais_id" class="form-control selectPaises">
                                <option value="">Selecione</option>
                                @forelse($paises as $pais)
                                    <option value="{{$pais->id}}" {{(isset($dados) ? ($pais->id == $dados->pais_id ? 'selected' : '') : ($pais->id == 1 ? 'selected' : ''))}}>{{$pais->ps_nome_pt}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado_id" class="form-control selectEstados">
                                <option value="">Selecione</option>
                                @forelse($estados as $estado)
                                    <option value="{{$estado->id}}" {{(isset($dados) && $dados->estado_id == $estado->id ? 'selected' : '')}}>{{$estado->uf_nome}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cidade</label>
                            <select name="cidade_id" class="form-control selectCidades">
                                <option value="">Selecione</option>
                                @isset($cidades)
                                    @forelse($cidades as $cidade)
                                        <option value="{{$cidade->id}}" {{(isset($dados) && $dados->cidade_id == $cidade->id ? 'selected' : '')}}>{{$cidade->cd_nome}}</option>
                                    @empty
                                    @endforelse
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Logradouro</label>
                            <input type="text" name="logradouro" value="{{(isset($dados) && !empty($dados->logradouro) ? $dados->logradouro : '')}}" class="form-control logradouro" placeholder="Logradouro">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Número</label>
                            <input type="text" name="numero" value="{{(isset($dados) && !empty($dados->numero) ? $dados->numero : '')}}" class="form-control numero" placeholder="Número">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Bairro</label>
                            <input type="text" name="bairro" value="{{(isset($dados) && !empty($dados->bairro) ? $dados->bairro : '')}}" class="form-control bairro" placeholder="Bairro">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento" value="{{(isset($dados) && !empty($dados->complemento) ? $dados->complemento : '')}}" class="form-control complemento" placeholder="Complemento">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Dias e horas de funcionamento</div>
            <div class="card-body">
                <div class="row">
                    @for($i = 0; $i <= 6; $i++)
                        <div class="col">
                            <p>{{dias($i)}}</p>
                            <input type="text" name="funcionamento[{{dias($i)}}][Abre]" value="{{isset($funcionamento) ? $funcionamento[dias($i)]['Abre'] : ''}}" class="form-control hora" placeholder="Abre">
                            <input type="text" name="funcionamento[{{dias($i)}}][Fecha]" value="{{isset($funcionamento) ? $funcionamento[dias($i)]['Fecha'] : ''}}" class="form-control hora" placeholder="Fecha">
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Configurações da página</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Imagem de Fundo</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="mostrar_imagem_fundo" value="{{(isset($configuracao) ? ($configuracao->mostrar_imagem_fundo == 1 ? '1' : '0' ) : '1')}}" {{(isset($configuracao) ? ($configuracao->mostrar_imagem_fundo == 1 ? 'checked' : '' ) : 'checked')}} class="custom-control-input " id="mostrarFundo">
                            <label class="custom-control-label" for="mostrarFundo">Mostrar imagem de Fundo</label>
                        </div>
                        <div class="form-group">

                            <label for="img_fundo"><img src="{{(isset($configuracao) ? $configuracao->imagem : '')}}" class="img_fundo" width="50%"></label>
                            <input type="file" class="hidden file_preview"  style="cursor:pointer;" data-preview=".img_fundo" name="img_fundo" id="img_fundo">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Modelo da lista dos produtos</label><br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="modelo1" value="1" {{(isset($configuracao) && $configuracao->modelo_lista == 1 ? 'checked' : '')}} name="modelo_lista" class="custom-control-input">
                                <label class="custom-control-label" for="modelo1">Modelo 1</label>
                            </div>

                            {{-- <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="modelo2" value="2" name="modelo_lista" class="custom-control-input">
                                <label class="custom-control-label" for="modelo2">Modelo 2</label>
                            </div> --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success" title="Salvar"><i class="far fa-save"></i> Salvar</button>
            </div>
        </div>
    </form>
@stop
