@if($produto->itens()->first())
<div data-search="{{$key}}" class="container produto">
    <div class="mt-2 d-flex h-4 justify-content-between align-items-center">
        <h2 class="tituloItem" data-titulo="{{$produto->nome}}">{{$produto->nome}}</h2>
        <input type="text" class="form-control form-control-sm pesquisar" style="width:200px" placeholder="Filtrar">
    </div>

    <div class="row position-relative my-2">
        @if($produto->indisponivel)
            <div class="position-absolute d-flex justify-content-center align-items-center boxIndisponivel shadow "><h2><strong>INDISPONÍVEL</strong></h2></div>
        @endif

        @forelse($produto->itens as $item)
            @if($item->mostrar)
                @if($item->valor && $item->valor > 0 || $produto->opcoes()->first())
                    <div class="col col-md-2  mt-2 item" data-search="{{$item->nome}}" data-valor="{{$item->valor}}">

                        <div class="card cardItem" style="border:0">
                            @if($item->indisponivel)
                                <div class="position-absolute d-flex justify-content-center align-items-center boxIndisponivel shadow"><h4><strong>INDISPONÍVEL</strong></h4></div>
                            @endif
                            <img src="{{$item->imagem_item}}" class="bd-placeholder-img card-img-top">

                            <div class="card-body">
                                <h2 class=" text-center tituloItem">{{$item->nome}}</h2>

                                @if($item->descricao)
                                    <div class="text-center descricaoItem">{{$item->descricao}}</div>
                                @endif

                                <div class="text-center">
                                    @if($item->valor)
                                        <h2 class="px-3 py-2 text-lg  valorItemProduto">R$ {{valorReal($item->valor)}}</h2>
                                    @endif

                                    @if(!$produto->indisponivel && !$item->indisponivel)
                                        @if($produto->opcoes()->first())
                                            <button type="button" id="{{$item->id}}" data-produto="{{$produto->id}}" data-link="{{route('site.retornaOpcoes',[$negocio->uri,$produto->id,$item->id])}}" class="btn btn-lg btn-success botaoItem JanelaOpcao"><i class="fa fa-cart-plus"></i> opções</button>
                                        @else
                                            <button type="button" id="{{$item->id}}" class="btn btn-lg btn-success botaoItem addItem"><i class="fa fa-cart-plus"></i> incluir</button>
                                        @endif
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
            @endif
        @empty
        @endforelse
    </div>
</div>

@else
    @if($produto->mostrar)
        @if($produto->valor && $produto->valor > 0 )
            <div class="container">
                <div class="mt-4 d-flex h-4 justify-content-between align-items-center">
                    <h2 class="tituloItem">{{$produto->nome}}</h2>
                </div>

                <div id="{{$produto->id}}" class="itemso produto item" data-search="{{$produto->nome}}"  data-valor="{{$produto->valor}}">
                    <div class="">
                        <div class="card " style="border:0">
                            @if($produto->indisponivel)
                                <div class="position-absolute d-flex justify-content-center align-items-center boxIndisponivel shadow "><h2 ><strong>INDISPONÍVEL</strong></h2></div>
                            @endif

                            <div class="text-center">
                                <img src="{{$produto->imagem}}" class="imagemProduto" width="50">
                            </div>

                            <div class="card-body">
                                <div class="text-center">
                                    @if($produto->descricao)
                                        <div class="text-center descricaoItem">{{$produto->descricao}}</div>
                                    @endif

                                    @if($produto->valor)
                                        <h2 class="px-3 py-2 valorItem">R$ {{valorReal($produto->valor)}}</h2>
                                    @endif

                                    <div class=" text-center ">
                                        @if(!$produto->indisponivel)
                                            <button type="button" id="{{$produto->id}}" class="btn btn-lg btn-success botaoItem addItem"><i class="fa fa-cart-plus"></i> incluir</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif
