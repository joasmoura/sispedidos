$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const aberto = $('input[name="aberto"]').val()

BASE = $('meta[name="BASE"]').attr('content');

window.onload = () => {
    'use strict';

    if ('serviceWorker' in navigator) {
      navigator.serviceWorker
               .register(BASE+'/sw.js');
    }
}
const carrinho = []
const itensOpcao = []
const limiteItens = []
var itemAtual = ''
var valorItem = 0
var ListaOpcoes = []
var nomeOpcaoAtual = ''
var nomeProdutoAtual = ''

const subTotal = $('.subTotal')


$('body').on('keyup change','.pesquisar',function(){
    const produto = $(this).closest('.produto');
    const itens = produto.find('.item')

    const nome = $(this).val().toLowerCase()

    itens.map((key,s) => {
        const item = $(s)
        const search = item.attr('data-search').toLowerCase()

        if(search.includes(nome)){
            item.fadeIn()
        }else{
            item.fadeOut()
        }
    })
})

var searchProd = ''
$('body').on('click','.searchProd',function(e){
    e.preventDefault()
    const produtos = $('.produto')
    const id = $(this).attr('id')

    if(searchProd == id){
        produtos.fadeIn()
        $(this).removeClass('bg-light')
    }else{
        $('.searchProd').removeClass('bg-light')
        $(this).addClass('bg-light')
        produtos.map((key,s) => {
            const item = $(s)
            const search = item.attr('data-search')
            searchProd = id
            if(search.includes(id)){
                item.fadeIn()
            }else{
                item.fadeOut()
            }
        })
    }
})

$('body').on('click','.addItem',function(){
    if(aberto == 0){
        Swal.fire({
            title:'Ooops!',
            text:'Estamos fechados no momento!',
            icon:'warning'
        })
        return
    }else{
        const pai = $(this).closest('.item');
        const search = pai.data('search')
        const valor = pai.data('valor')
        const id = $(this).attr('id')
        const nomeProduto = pai.closest('.produto').find('.tituloItem').data('titulo')

        if(!$(`#itemCarrinho_${id}`).length){
            carrinho.push({
                nomeProduto:nomeProduto,
                id:id,
                nome:search,
                qtd:1,
                valor:valor,
            })
            lista_carrinho()
        }
    }
})

$('body').on('click','.JanelaOpcao',function(){
    const item_id = $(this).attr('id')
    const produto_id = $(this).data('produto')
    const link = $(this).data('link')
    nomeOpcaoAtual = $(this).closest('.item').data('search')

    nomeProdutoAtual = $(this).closest('.produto').find('.tituloItem').data('titulo')

    if(!$('.modal').length){
        $('body').append(`
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>

                    <div class="modal-body"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" data-item="" class="btn btn-success incluirOpcoes">Confirmar</button>
                    </div>
                </div>
            </div>
    </div>`)
    }
    itemAtual = item_id

    $('.modal-title').text('Opções: '+nomeOpcaoAtual)

    listaOpcoes(link)
    $('.modal').modal('show')
})

function listaOpcoes(link){
    var conteudo = '';
    $('.modal-body').addClass('p-0')
    $.get(link,function(r){
        if(r.item){
            var encontrou = false
            valorItem = r.item.valor

            limiteItens.filter((item) => {
                encontrou = item.id == r.item.id
            })

            if(!encontrou){
                limiteItens.push({
                    id:r.item.id,
                    max_opcao:r.item.max_opcoes,
                    max_opcao_paga:r.item.max_opcoes_pagas,
                })
            }
        }

        if(r.opcoes){
            ListaOpcoes = r.opcoes

            conteudo += `
                <div class="card">
                    <div class="card-header pt-1 pb-1 d-flex justify-content-between">
                        <span>Opções</span>
                        ${(r.item.max_opcoes ? `<span>Limite de <strong> ${r.item.max_opcoes}</strong> opç${(r.item.max_opcoes > 1 ? 'ões' : 'ão')}</span>` : '')}
                    </div>

                    <div class="card-body">${opcoes(r.opcoes)}</div>
                </div>

                <div class="card">
                    <div class="card-header pt-1 pb-1 d-flex justify-content-between">
                        <span>Opções adicionais pagos</span>
                        ${(r.item.max_opcoes_pagas ? `<span>Limite de <strong>${r.item.max_opcoes_pagas}</strong> opç${(r.item.max_opcoes_pagas > 1 ? 'ões' : 'ão')}</span>` : '' )}
                    </div>

                    <div class="card-body">${opcoes_pagas(r.opcoes)}</div>
                </div>


                <div class="row p-3">
                    <div class="col-md-6 mb-1">
                        <input type="number" value="1" min="1" class="form-control calculaSubtotalOpcao" placeholder="Quantidade">
                    </div>

                    <div class="col-md-6 mb-1 ">
                        <span class="badge badge-info text-white py-2 px-2 subTotalOpcao"></span>
                    </div>
                </div>
            `
        }
        $('.modal-body').html(`
            <div class="list-group">
                ${conteudo}
            </div>
        `)
    })
}

$('body').on('keyup mouseup','.calculaSubtotalOpcao,.opcao_paga',function(){
    calculaSubtotalItens()
})

function calculaSubtotalItens(){
    var qtd = $('.calculaSubtotalOpcao').val()
    var subTotal = 0
    var selecionado = false
    $.each($('.opcao'),function(i,op) {
        if($(op).is(':checked')){
            selecionado = true
        }
    })

    if(selecionado){
        subTotal += valorItem*qtd
    }

    $.each($('.opcao_paga'),function(i,op) {
        if($(op).is(':checked')){
            item = ListaOpcoes.find(item => item.id == $(op).attr('id'))
            subTotal += parseFloat(item.valor) * qtd
        }
    })

    $('.subTotalOpcao').text('Subtotal de '+moeda(subTotal));
}

function opcoes(opcoes){
    var conteudo = '';

    opcoes.map((opcao) =>{
        if(opcao.mostrar){
            if(!opcao.valor){
                conteudo += `
                    <div class="">
                        <label class="d-flex justify-content-between" style="cursor:pointer;">
                            <span>
                                <input type="checkbox" data-itemid="${opcao.item_id}" id="${opcao.id}" name="opcao[${opcao.id}][op]" class="opcao">
                                ${opcao.nome}
                                ${(opcao.descricao ? `<span class="badge badge-primary">${opcao.descricao}</span>` : '')}
                            </span>
                        </label>
                    </div>
                `
            }
        }
    })
    return conteudo
}

function opcoes_pagas(opcoes){
    var conteudo = '';

    opcoes.map((opcao) =>{
        if(opcao.mostrar){
            if(opcao.valor){
                conteudo += `
                    <div class="">
                        <label class="d-flex justify-content-between" style="cursor:pointer;">
                            <span>
                                <input type="checkbox" data-itemid="${opcao.item_id}" id="${opcao.id}" name="opcao[${opcao.id}][op]" class="opcao_paga">
                                <input type="hidden" value="${opcao.valor}" name="opcao[${opcao.id}][valor]" >
                                ${opcao.nome}
                                ${(opcao.descricao ? `<span class="badge badge-primary">${opcao.descricao}</span>` : '')}
                            </span>
                            <span>${opcao.valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}</span>
                        </label>
                    </div>
                `
            }
        }
    })
    return conteudo
}

$('body').on('change','.opcao',function(){
    const itemid = $(this).data('itemid')
    const limite = limiteItens.filter((item) => item.id == itemid)
    var qtdOpcoes = $('.opcao:checked').length

    if(qtdOpcoes > limite[0].max_opcao){
        if($(this).is(':checked')){
            $(this).prop('checked',false)
        }else{
            $(this).prop('checked',true)
        }
    }
    calculaSubtotalItens()
})

$('body').on('change','.opcao_paga',function(){
    const itemid = $(this).data('itemid')
    const limite = limiteItens.filter((item) => item.id == itemid)
    var qtdOpcoes = $('.opcao_paga:checked').length

    if(limite[0].max_opcao_paga && qtdOpcoes > limite[0].max_opcao_paga){
        if($(this).is(':checked')){
            $(this).prop('checked',false)
        }else{
            $(this).prop('checked',true)
        }
    }
    calculaSubtotalItens()
})

$('body').on('click','.incluirOpcoes',function(){
    if(aberto == 0){
        Swal.fire({
            title:'Ooops!',
            text:'Estamos fechados no momento!',
            icon:'warning'
        })
        return
    }else{
        incluirOpcoes()
    }
})

function incluirOpcoes(){
    var item = carrinho.find(item => item.id == itemAtual)
    var opcoes = []
    var existeOpcao = false
    $.each($('.opcao'),function(i,op){
        if($(this).is(':checked')){
            opcao_normal = ListaOpcoes.find(it => it.id == $(op).attr('id'))
            opcoes.push(opcao_normal)
            if(!existeOpcao){
                existeOpcao = true
            }
        }
    })

    $.each($('.opcao_paga'),function(i,op){
        if($(this).is(':checked')){
            opcao_paga = ListaOpcoes.find(it => it.id == $(op).attr('id'))

            opcoes.push({
                id:opcao_paga.id,
                qtd:1,
                valor:opcao_paga.valor,
                nome:opcao_paga.nome
            })
            if(!existeOpcao){
                existeOpcao = true
            }
        }
    })

    if(!existeOpcao){
        Swal.fire({
            title:'Selecione ao menos uma das opções',
            icon:'warning',
            timer:1500
        })
    }else{
        if(item == undefined){
            carrinho.push({
                id:itemAtual,
                valor:valorItem,
                nome: nomeOpcaoAtual,
                qtd:$('.calculaSubtotalOpcao').val(),
                opcoes,
                nomeProduto:nomeProdutoAtual
            })
        }else{
            item.qtd = $('.calculaSubtotalOpcao').val()
            item.opcoes = opcoes
        }
        lista_carrinho()
        $('.modal').modal('hide')
    }
}

function lista_carrinho(){
    const box_carrinho = $('.carrinho')
    box_carrinho.find('.tabelaCarrinho tbody').empty()
    const badgeCarrinho = $('.badgeCarrinho')
    var totalItens = 0;
    var conteudoItem = ''

    carrinho.map((item,i) => {
        var key = i+1;
        var valor = (item.valor ? parseFloat(item.valor)*(item.qtd ? item.qtd : 0)  : 0)

        var td_opcoes = ''
        if(item.opcoes){
            item.opcoes.map((op,opi) => {
                td_opcoes += `
                    <tr>
                        <td colspan="4">${op.nome}</td>
                        <td>`
                            if(!item.valor){
                                td_opcoes += `<input type="number" data-index="${i}" data-indexOpcao="${opi}" min="1" min-value="1" value="${(op.qtd ? op.qtd : 1)}" class="form-control form-control-sm quantidade" style="width:100px">`
                            }
                        td_opcoes += `</td>

                        <td class="valorOpcao_${op.id}">${(item.valor ? moeda(parseFloat(op.valor)*parseInt(item.qtd)) : (op.valor > 0 ? moeda(parseFloat(op.valor)*parseInt(op.qtd)) : ''))}</td>
                    </tr>
                `
            })
        }

        conteudoItem = `
            <tr id="itemCarrinho_${item.id}" class="itemCarrinho">
                <td class="py-1 px-2">${key}</td>
                <td class="py-1 px-2">${item.nomeProduto} - ${item.nome}</td>
                    <td class="py-1 px-2">`
                    if(item.valor){
                        conteudoItem += `<input type="number" data-index="${i}" min="1" min-value="1" value="${item.qtd}" class="form-control form-control-sm quantidade" style="width:100px">`
                    }

                conteudoItem += `</td>
                    <td class="py-1 px-2 valorItem">${(valor ? moeda(valor) : '')}</td>
                <td class="py-1 px-2"><button type="button" data-index="${i}" class="btn text-danger btn-sm removerItem"><i class="fa fa-times"></i></button></td>
            </tr>
            ${td_opcoes}
        `

        box_carrinho.find('.tabelaCarrinho tbody').append(conteudoItem)
        totalItens += 1
        badgeCarrinho.text(totalItens)
        calculaValorTotal()
    })
}

var valorTotal = 0;
function calculaValorTotal(){
    valorTotal = 0
    carrinho.map((item,i) => {
        valorTotal += (item.valor ? parseFloat(item.valor)*(item.qtd ? parseInt(item.qtd) : 0) : 0)
        if(item.opcoes){
            item.opcoes.map((op) => {
                if(op.valor){
                    valorTotal += parseFloat(op.valor)*(item.valor ? parseInt(item.qtd) : parseInt(op.qtd))
                }
            })
        }
    })

    if(carrinho.length){
        subTotal.fadeIn()
    }else{
        subTotal.fadeOut()
    }

    $('.total').text(moeda(valorTotal))
}

$('body').on('keyup mouseup ','.quantidade',function(){
    const este = $(this)
    const index = este.data('index')
    const item = carrinho[index]
    item.qtd = parseInt($(this).val())

    if(item.valor){
        var valorItem = parseFloat(item.valor) * (item.qtd ? parseInt(item.qtd) : 0)

        if(item.opcoes){
            item.opcoes.map((op) => {
                if(op.valor){
                    $(`.valorOpcao_${op.id}`).text(moeda(parseFloat(op.valor)*(item.qtd ? parseInt(item.qtd) : 0) ))
                }
            })
        }

        $(this).closest('.itemCarrinho').find('.valorItem').text(moeda(valorItem))
    }else{
        const itemOpcao = item['opcoes'][este.data('indexopcao')]
        itemOpcao.qtd = parseInt($(this).val())
        var valorOpcao = parseFloat(itemOpcao.valor) * (itemOpcao.qtd ? parseInt(itemOpcao.qtd) : 0)
        $(`.valorOpcao_${itemOpcao.id}`).text(moeda(valorOpcao))
        $(this).closest('.itemCarrinho').find('.valorItem').text(moeda(valorOpcao))
    }
    calculaValorTotal()
})

$('body').on('click','.removerItem',function(){
    const index = $(this).data('index')
    $(this).closest('.itemCarrinho').remove()
    carrinho.splice(carrinho.indexOf(index),1)
    lista_carrinho()

    if(!carrinho.length){
        mostraCarrinho()
        subTotal.fadeOut()
        $('.total').text('')
        $('.badgeCarrinho').text('')
    }
})

$('body').on('click','.boxCarrinho',function(){
    if(!carrinho.length){
        Swal.fire({
            title:'Ooops...',
            text:'Sua cesta está vazia',
            icon:'warning',
            timer:1500
        })
        return
    }
    mostraCarrinho()
})

$('body').on('click','.bgCarrinho',function(){
    mostraCarrinho()
})

function mostraCarrinho(){
    $('.bgCarrinho').fadeToggle()
    $('.carrinho').animate({
        width: [ "toggle", "swing" ],
    }, 'fast', "linear")

    $('body').toggleClass('noOverflow')
}

$('body').on('click','.viaWhatsapp',function(){
    if(aberto == 0){
        Swal.fire({
            title:'Ooops!',
            text:'Estamos fechados no momento!',
            icon:'warning'
        })
        return
    }else{
        const link = $(this).data('url')
        var observacoes = $('#observacao').val()
        $.post(link,{carrinho,observacoes},function(r){
            var texto = `Olá, estou querendo: \n`

            carrinho.map((item,i) => {
                if(parseInt(item.qtd) >= 1){
                    texto += '\n'
                    texto += (item.valor ? item.qtd : '') + ' ' + item.nome + (item.valor ? ' => '+ moeda(parseFloat(item.valor)*parseInt(item.qtd)) : '')+ '\n'
                    if(item.opcoes){
                        item.opcoes.map((opcao) => {
                            texto += (!item.valor ? '```' + opcao.qtd + '``` '  : ' - ') + '```' + opcao.nome + (opcao.valor ? ' => '+ moeda(parseFloat(opcao.valor)*parseInt(item.qtd)) : '')+ '```\n'
                        })
                    }
                }
            })
            texto += `\n*Total de ${moeda(valorTotal)}*`

            if(observacoes){
                texto += '\nObservações: '
                texto += '```'+observacoes+'```'
            }
            texto = window.encodeURIComponent(texto)

            var urlWhatsapp = `https://api.whatsapp.com/send?phone=+55${r.result.tel}&text=${texto}`;

            window.open(urlWhatsapp,'_blank')
        })
    }
})

function moeda(valor){
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
}
