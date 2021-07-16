function moeda(valor){
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
}

data('.data')
function data(data) {
    $(data).datepicker({
        language:'pt-BR',
    });
    $(data).mask('99/99/9999')
}


$('body').on('click','.addProduto',function(){
    if($('.boxSemRegistro').length){
        $('.boxSemRegistro').remove();
    }
    conteudo()
})

function conteudo(){
    var produto = 0;
    $.each($('.produto'),function(){
        produto++
    })

    var conteudo = `
        <div id="produto_${produto}" class="col-md-12 produto">
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12 d-flex">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="produto[${produto}][indisponivel]" value="0" class="custom-control-input produtoIndisponivel" id="prodIndisponivel_${produto}">
                                <label class="custom-control-label" for="prodIndisponivel_${produto}">Indisponível</label>
                            </div>

                            <div class="custom-control custom-switch ml-3">
                                <input type="checkbox" name="produto[${produto}][mostrar]" value="1" checked class="custom-control-input produtoMostrar" id="prodMostrar_${produto}">
                                <label class="custom-control-label" for="prodMostrar_${produto}">Mostrar Produto</label>
                            </div>
                        </div>

                        <div class="col-md-2 text-center">
                            <div class="form-group">
                                <label for="logotipo_produto_${produto}"><img src="${BASE}/assets/imgs/sem-foto.jpg" class="preview_${produto}" width="100"></label>
                                <input type="file" class="hidden file_preview"  style="cursor:pointer;" data-preview=".preview_${produto}" name="produto[${produto}][logotipo]" id="logotipo_produto_${produto}">
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input type="text" class="form-control nomeProduto" name="produto[${produto}][nome]" value="" placeholder="Nome">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Descrição</label>
                                        <input type="text" class="form-control " name="produto[${produto}][descricao]" value="" placeholder="Descrição">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Valor</label>
                                        <input type="text" class="form-control" name="produto[${produto}][valor]" value="" placeholder="Valor">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="accordion" id="accordionProduto${produto}">
                                <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#collapseItem${produto}" aria-expanded="false" aria-controls="collapseOne">
                                    <h4 class="p-0 m-0">Itens</h4>
                                </button>

                                <div id="collapseItem${produto}" class="collapse shadow-sm" aria-labelledby="headingOne" data-parent="#accordionProduto${produto}">
                                    <button type="button" id="${produto}" class="btn btn-info addItemProduto"><i class="fa fa-plus"></i> Itens</button>
                                    <div class="row listaItens"  style="max-height: 400px; overflow-x: auto"></div>
                                </div>

                                <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#accordionOpcao${produto}" aria-expanded="false" aria-controls="collapseOne">
                                    <h4 class="p-0 m-0">Opções</h4>
                                </button>

                                <div id="accordionOpcao${produto}" class="collapse shadow-sm" aria-labelledby="headingOne" data-parent="#accordionProduto${produto}">
                                    <button type="button" id="${produto}" class="btn btn-info addOpcao"><i class="fa fa-plus"></i> Itens</button>
                                    <div class="row listaOpcoes"  style="max-height: 400px; overflow-x: auto"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-right"><button type="button" id="${produto}" class="btn btn-sm btn-danger removerProduto"><i class="fa fa-trash"></i></button></div>

                    </div>
                </div>
            </div>
        </div>
    `
    $('.boxLista').prepend(conteudo)
}

$('body').on('change','.produtoIndisponivel',function(){
    var valor = $(this).val()

    if(valor == 0){
        $(this).val(1)
    }else{
        $(this).val(0)
    }
})

$('body').on('change','.produtoMostrar',function(){
    var valor = $(this).val()

    if(valor == 0){
        $(this).val(1)
    }else{
        $(this).val(0)
    }
})

$('body').on('keyup','.nomeProduto',function(){
    const produto = $(this).closest('.produto')

    produto.find('.card-header').text($(this).val())
})

$('body').on('click','.addItemProduto',function(){
    const idProduto = $(this).attr('id')
    const pai = $(this).closest('.produto')
    addItem(pai,idProduto,'.listaItens')
})

$('body').on('click','.addOpcao',function(){
    const idProduto = $(this).attr('id')
    const pai = $(this).closest('.produto')
    addItem(pai,idProduto,'.listaOpcoes',true)
})

function addItem(pai,idProduto,lista,sub = false){
    var item = 0;
    $.each($('.item'),function(){
        item++
    })

    var conteudo = `
        <div id="item_${item}" class="col-md-6 item">
            <div class="card">
            <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="${(sub ? 'produto['+idProduto+'][sub]['+item+'][indisponivel]' : 'produto['+idProduto+'][item]['+item+'][indisponivel]')}" value="0" class="custom-control-input produtoIndisponivel" id="itemIndisponivel_${item}">
                                <label class="custom-control-label" for="itemIndisponivel_${item}">Indisponível</label>
                            </div>

                            <div class="custom-control custom-switch ml-3">
                                <input type="checkbox" name="${(sub ? 'produto['+idProduto+'][sub]['+item+'][mostrar]' : 'produto['+idProduto+'][item]['+item+'][mostrar]')}" value="1" checked class="custom-control-input produtoMostrar" id="itemMostrar_${item}">
                                <label class="custom-control-label" for="itemMostrar_${item}">Mostrar Produto</label>
                            </div>
                        </div>

                        <div class="col-md-2 text-center">
                            <div class="form-group">
                                <label for="logotipo_item_${item}"><img src="${BASE}/assets/imgs/sem-foto.jpg" class="preview_item_${item}" width="70"></label>
                                <input type="file" class="hidden file_preview"  style="cursor:pointer;" data-preview=".preview_item_${item}" name="${(sub ? 'produto['+idProduto+'][sub]['+item+'][logotipo]' : 'produto['+idProduto+'][item]['+item+'][logotipo]')}" id="logotipo_item_${item}">
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input type="text" id="${item}" class="form-control nomeItem" name="${(sub ? 'produto['+idProduto+'][sub]['+item+'][nome]' : 'produto['+idProduto+'][item]['+item+'][nome]')}" value="" placeholder="Nome">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Descrição</label>
                                        <input type="text" class="form-control" name="${(sub ? 'produto['+idProduto+'][sub]['+item+'][descricao]' : 'produto['+idProduto+'][item]['+item+'][descricao]')}" value="" placeholder="Descrição">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Valor</label>
                                        <input type="text" class="form-control" name="${(sub ? 'produto['+idProduto+'][sub]['+item+'][valor]' : 'produto['+idProduto+'][item]['+item+'][valor]')}" value="" placeholder="Valor">
                                    </div>
                                </div>

                                ${(sub ? '' : `
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Máximo opção</label>
                                            <input type="number" class="form-control" min="1" name="produto[${idProduto}][item][${item}][max_opcoes]" value="" placeholder="Máximo de opções">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Máximo opção paga</label>
                                            <input type="number" class="form-control" min="1" name="produto[${idProduto}][item][${item}][max_opcoes_pagas]" value="" placeholder="Máximo de opções pagas">
                                        </div>
                                    </div>
                                `)}
                            </div>
                        </div>

                        <div class="col-md-12"><hr></div>
                        <div class="col-md-12 text-right"><button type="button" id="${item}" class="btn btn-sm btn-danger removerItem"><i class="fa fa-trash"></i></button></div>
                    </div>
                </div>
            </div>
        </div>
    `
    pai.find(lista).prepend(conteudo)
}

$('body').on('click','.removerProduto',function(){
    const data = $(this).data();
    const item = data.item;
    const link = data.link;

    Swal.fire({
        title:'Deletar este produto',
        icon:'question',
        text:'',
        showCancelButton: true,
    }).then((result) => {
       if(result.isConfirmed){
           $(this).closest('.produto').remove()

           if(item != undefined){
            $.ajax({
                url:link,
                method:'DELETE',
                success:(r) => {

                }
             })
        }
       }
    })
})

$('body').on('click','.removerItem',function(){
    const data = $(this).data();
    const item = data.item;
    const link = data.link;

    Swal.fire({
        title:'Deletar este item',
        icon:'question',
        text:'',
        showCancelButton: true,
    }).then((result) => {
       if(result.isConfirmed){
           $(this).closest('.item').remove()
           if(item != undefined){
               $.ajax({
                   url:link,
                   method:'DELETE',
                   success:(r) => {

                   }
                })
           }
       }
    })
})

$('body').on('keyup','.nomeItem',function(){
    const item = $(this).closest('.item')

    item.find('.card-header').text($(this).val())
})

$('body').on('submit','form[name="formProdutosNegocio"]',function(e){
    e.preventDefault()

    $(this).ajaxSubmit({
        url:$(this).attr('action'),
        method:'POST',
        dataType:'JSON',
        success:(r) => {
            Swal.fire({
                title:'',
                text:r.result.texto,
                icon:'success',
                timer:1500,
                position:'top-end'
            }).then(response => {
                window.location.reload()
            })
        }
    })
})


//Pedidos
$('body').on('click','.itensPedido',function(){
    $('.modal').modal('show')
    $('.modal-title').text('Itens do pedido '+$(this).data('pedido'))
    $('.modal-dialog').addClass('modal-xl')
    preload()
    $.get($(this).data('link'),function(r){
        preload(false)
        if(r.itens){
            var conteudo = ''
            r.itens.map((it,i) => {
                conteudo += `
                    <tr>
                        <td>${i+=1}</td>
                        <td>${it.produtoPrincipal.nome} (${it.produto.nome})</td>
                        <td>${(it.valor ? it.qtd : '')}</td>
                        <td>${(it.valor ? moeda(it.valor) : '')}</td>
                    </tr>
                `

                if(it.opcoes){
                    it.opcoes.map((op,iop) => {
                        conteudo += `
                            <tr class="bg-light">
                                <td colspan="2"> <i class="fa fa-arrow-right"></i> ${op.nome}</td>
                                <td>${op.qtd}</td>
                                <td>${(op.valor ? moeda(op.valor) : '')}</td>
                            </tr>
                        `
                    })
                }
            })

            $('.modal-body').html(`
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nome</th>
                                <th>Quantidade</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${conteudo}
                        </tbody>
                    </table>
                </div>
            `)
        }
    })
})

$('body').on('click','.statusPedido',function(){
    const link = $(this).data('link')
    const action = $(this).data('action')
    $('.modal').modal('show')
    $('.modal-title').text('Status do pedido '+$(this).data('pedido'))
    $('.modal-dialog').addClass('modal-xl')

    $('.modal-body').html(`
        <form class="row" action="${action}" name="formStatus" method="post">
            <div class="col-md-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label class="input-group-text pb-0 pt-0 pr-1" for="inputGroupSelect01">Options</label>
                    </div>

                    <select class="custom-select" name="status" id="inputGroupSelect01">
                        <option value=""></option>
                        <option value="pedido">Pedido</option>
                        <option value="preparacao">Em preparação</option>
                        <option value="acaminho">A caminho</option>
                        <option value="entregue">Entregue</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <textarea name="obs" class="form-control" placeholder="Observações"></textarea>
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-success btn-sm" title="Salvar"><i class="fa fa-save"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover tabelaStatus">
                <thead>
                    <tr>
                        <th></th>
                        <th>Status</th>
                        <th>Observação</th>
                        <th>Usuário</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    `)
    listaStatus(link)
})

$('body').on('submit','form[name="formStatus"]',function(e){
    e.preventDefault()
    $.post($(this).attr('action'),$(this).serialize(),function (r) {
        if(r.result.status){
            Swal.fire({
                title:'Sucesso',
                text:r.result.msg,
                icon:'success'
            }).then(result => {
                window.location.reload()
            })

            listaStatus(r.result.link)
        }
    })
})

function listaStatus(link)  {
    preload()
    var conteudo = ''

    $.ajax({
        url: link,
        async: false,
        success: function(r){
            preload(false)
            if(r.status){
                r.status.map((status,i) => {
                    $('select[name="status"]').find('option[value="'+status.status+'"]').attr('disabled',true)
                    const botaoExcluir = `<button type="button" data-url="${status.excluir}" class="btn btn-sm btn-danger excluirStatus" title="Excluir"><i class="fa fa-trash"></i></button>`
                    conteudo += `
                        <tr>
                            <td>${i+=1}</td>
                            <td>
                                ${status.status == 'pedido' ? 'Pedido efetuado' : ''}
                                ${status.status == 'preparacao' ? 'Pedido em preparação' : ''}
                                ${status.status == 'acaminho' ? 'Pedido a caminho' : ''}
                                ${status.status == 'entregue' ? 'Pedido entregue' : ''}
                                ${status.status == 'cancelado' ? 'Pedido cancelado' : ''}
                            </td>
                            <td>${(status.obs ? status.obs : '')}</td>
                            <td>
                                ${(status.usuario ? status.usuario.name : 'Pedido através da vitrine')}
                            </td>
                            <td>
                                ${(r.listaStatus.includes('cancelado')? '' : (status.status != 'pedido' ? botaoExcluir : ''))}
                            </td>
                        </tr>
                    `
                })
            }
        },
      });
    $('.tabelaStatus tbody').html(conteudo)
}

$('body').on('click','.cancelarPedido',function(){
    const link = $(this).data('link')

    Swal.fire({
        title:'Deseja Cancelar este pedido?',
        text: 'Esta ação não poderá ser desfeita!',
        icon:'question',
        showCancelButton:true,
    }).then(click => {
        if(click.isConfirmed){
            $.get(link,function(r){
                if(r.result.status){
                    Swal.fire({
                        text:r.result.texto,
                        icon:'success',
                        timer:1500,
                        position:'top-end'
                    }).then(response => {
                        window.location.reload()
                    })
                }
            })
        }
    })
})

$('body').on('click','.excluirStatus',function(){
    const link = $(this).data('url')
    Swal.fire({
        title:'Excluir este status?',
        text:'',
        icon:'question',
        showCancelButton:true,
    }).then(click => {
        if(click.isConfirmed){
            $.get(link,function(r){
                if(r.result.status){
                    listaStatus(r.result.link)
                    Swal.fire({
                        text:r.result.texto,
                        icon:'success',
                        timer:1500,
                        position:'top-end'
                    }).then(response => {

                    })
                }
            })
        }
    })
})

$('body').on('click','.imprimirPedidos',function(){
    var conteudo = document.getElementById('tabelaPedidos').innerHTML
    tela_impressao = window.open('about:blank');

    tela_impressao.document.write(conteudo);
    tela_impressao.window.print();
    tela_impressao.window.close();
})
