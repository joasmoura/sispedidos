@extends('layouts.SiteTemplate')

@section('header')

 <nav class="navbar navbar-expand-lg navbar-light bg-transparent pt-2"> {{-- fixed-top  --}}
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="{{route('site.negocio',$negocio->uri)}}"><img src="{{$negocio->imagem}}" class="img-fluid" width="200"></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" style="flex-grow: initial;" id="navbarNav">
            <ul class="navbar-nav justify-center">

                @if($negocio->whatsapp)
                    <li class="nav-item">
                        <a class="nav-link text-center" target="_blank" href="https://api.whatsapp.com/send?phone=+55{{$negocio->whatsapp}}"><i class="fab fa-whatsapp"></i></a>
                    </li>
                @endif

                @if($negocio->facebook)
                    <li class="nav-item">
                        <a class="nav-link text-center"  target="_blank" href="{{$negocio->facebook}}"><i class="fab fa-facebook"></i></a>
                    </li>
                @endif

                @if($negocio->telegram)
                    <li class="nav-item">
                        <a class="nav-link text-center" target="_blank" href="{{$negocio->telegram}}"><i class="fab fa-telegram"></i></a>
                    </li>
                @endif

                @if($negocio->twitter)
                <li class="nav-item">
                    <a class="nav-link text-center" target="_blank" href="{{$negocio->twitter}}"><i class="fab fa-twitter"></i></a>
                </li>
                @endif

                @if($negocio->instagram)
                    <li class="nav-item">
                        <a class="nav-link text-center" target="_blank" href="{{$negocio->instagram}}"><i class="fab fa-instagram"></i></a>
                    </li>
                @endif

                @if($negocio->linkedin)
                    <li class="nav-item">
                        <a class="nav-link text-center" target="_blank" href="{{$negocio->linkedin}}"><i class="fab fa-linkedin"></i></a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>
@stop

@section('conteudo')
@if($configuracao->modelo_lista == 1)
    <div class="container-fluid z-10">
        @if($produtos->first())
            <section class="container">
                <div class="pt-3 pb-2 "><h3 class="z-10">Produtos</h3></div>
                <div class="d-flex justify-between overflow-auto">
                    @forelse($produtos as $key => $produto)
                        @if($produto->itens()->first() || $produto->valor)
                            @if($produto->mostrar)
                                <button type="button" id="{{$key}}" class="btn col-md-1 text-center searchProd">
                                    <img src="{{$produto->imagem}}" height="50" width="50" style="border-radius: 50%; object-fit: cover;">
                                    <p style="font-size: 12px;">{{$produto->nome}}</p>
                                </button>
                            @endif
                        @endif
                    @empty
                    @endforelse
                </div>
            </section>

            @forelse($produtos as $key => $produto)
                @if($produto->mostrar)
                    <x-produto-lista :produto="$produto" :key="$key" :negocio="$negocio"/>
            @endif
            @empty
            @endforelse
        @endif
    </div>

    @php
        $dia_atual = $funcionamento[dias(date('w'))];
        $vazio = (!empty($dia_atual['Abre']) && !empty($dia_atual['Fecha']));
    @endphp

    <input type="hidden" name="aberto" value="{{($mostrar_funcionamento ? ($vazio && $dia_atual['Abre'] <= date('H:i') && $dia_atual['Fecha'] >= date('H:i') ? 1 : 0 ) : 1)}}">
    @if($mostrar_funcionamento)
        <div class="container py-5">
            <div class="pt-2 pb-2"><h3 class="z-10">Funcionamento</h3></div>
            <div class="d-flex  overflow-auto">
                @forelse($funcionamento as $key => $dia)
                    @if((!empty($dia['Abre']) && !empty($dia['Fecha'])))
                        <div class=" mx-1 rounded-lg p-1 text-center shadow-sm bg-light {{($key == dias(date('w',strtotime($dataAtual))) ? 'border  border-primary' : '')}}" style="min-width: 100px;">
                            <p class="">{{$key}}</p>

                            @if($key == dias(date('w',strtotime($dataAtual))) && $dia['Abre'] <= date('H:i') && $dia['Fecha'] >= date('H:i') )
                                <p class="p-0 m-0 text-success">Aberto</p>
                            @elseif($key != dias(date('w',strtotime($dataAtual))))
                                <p class="p-0 m-0 text-success">Abre {{$dia['Abre']}}</p>
                                <p class="p-0 m-0 text-success">Fecha {{$dia['Fecha']}}</p>
                            @else
                                <p class="p-0 m-0 text-danger">Fechado</p>
                            @endif
                        </div>
                    @endif
                @empty
                @endforelse
            </div>
        </div>
    @endif

    <div class="container-fluid">
        <footer class=" container-sm bg-light py-5">
            <div class="row">
                <div class="col-md-3 text-center">
                    <a class="navbar-brand" href="{{route('site.negocio',$negocio->uri)}}">
                        <img src="{{$negocio->imagem}}" width="100" class="img-fluid">
                    </a>
                </div>

                <div class="col-md-7">
                    <div class="d-flex">
                        <span>{{$negocio->cep}}, </span>
                        <span class="ml-1">{{$negocio->cidade->cd_nome}}-</span>
                        <span class="ml-1">{{$negocio->estado->uf_uf}}, </span>
                        <span class="ml-1">{{$negocio->logradouro}},</span>
                        <span class="ml-1">{{$negocio->bairro}}</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endif
<button type="button" class="btn boxCarrinho text-success " style="position:fixed; bottom:50%; right:30px; z-index:100; border-radius:50%; width:50px; height:50px;">
    <i class="fa fa-shopping-bag" style="font-size: 3em; opacity:0.7"></i>
    <span class="badge badge-danger shadow-sm position-absolute badgeCarrinho"></span>
</button>

<div class=" carrinho bg-white fixed-top border-left p-0 shadow ">
    <div class="d-flex justify-content-between align-items-center h-12 py-2 px-2">
        <h3 class="text-xl">Conferir pedido</h3>
        <button type="button" class="close mr-2 boxCarrinho" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        {{-- <button type="button" class="btn btn-light btn-sm btn-round btn-simple btn-icon mr-1 boxCarrinho" style="border-radius: 50%"><i class="fa fa-times"></i></button> --}}
    </div>

    <div class="body px-2 py-2" style="max-height:550px; min-width:50%; overflow-y: auto">
        <div class="p-2">
            <div class="block bg-info px-2 py-1 mb-1 text-white text-center">Total de <span class="total">0,00</span></div>
            <button type="button" class="btn btn-success btn-sm btn-block viaWhatsapp" data-url="{{route('site.realizar_pedido',$negocio->uri)}}"><i class="fab fa-whatsapp"></i> Pedir via Whatsapp</button>
            {{-- <button type="button" class="btn btn-primary btn-block btn-sm "><i class="fab fa-telegram"></i> Pedir via Telegram</button> --}}
        </div>

        <div class="table-responsive" style="overflow: hidden;">
            <table class="table table-hover table-borderless tabelaCarrinho">
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="">
        <textarea id="observacao" class="form-control" placeholder="Obervações" rows="5"></textarea>
    </div>

</div>

<div class="fixed-top bgCarrinho" style="height: 100%; width:100%; z-index:30; background:rgba(0,0,0,0.3); display:none;"></div>
<div class="fixed-bottom bottom-0 right-0 left-0 py-1 bg-success text-center text-white  subTotal" style="widt:100%;  z-index:100; display: none; font-weight: 600;">
    <span>Total de </span>
    <span class="ml-1 total font-bold"></span>
</div>


@stop

@section('styles')
    <style>
        @if($configuracao->mostrar_imagem_fundo)
            *{z-index:10}
            footer{position: relative; border-radius: 20px 20px 0 0;}
            .z-10{position: relative; z-index: 10;}
            .bg-negocio::after{
                content: '';
                height: 100%;
                width: 100%;
                position: fixed;
                background-image: url({{$configuracao->imagem}});
                background-repeat: no-repeat;
                opacity: .7;
                background-size: cover;
                /* display: flex; */
                top:0;
            }
        @endif
    </style>
@stop
