/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************!*\
  !*** ./resources/views/assets/js/produtos.js ***!
  \***********************************************/
function moeda(valor) {
  return valor.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  });
}

data('.data');

function data(data) {
  $(data).datepicker({
    language: 'pt-BR'
  });
  $(data).mask('99/99/9999');
}

$('body').on('click', '.addProduto', function () {
  if ($('.boxSemRegistro').length) {
    $('.boxSemRegistro').remove();
  }

  conteudo();
});

function conteudo() {
  var produto = 0;
  $.each($('.produto'), function () {
    produto++;
  });
  var conteudo = "\n        <div id=\"produto_".concat(produto, "\" class=\"col-md-12 produto\">\n            <div class=\"card\">\n                <div class=\"card-header\"></div>\n\n                <div class=\"card-body\">\n                    <div class=\"row\">\n\n                        <div class=\"col-md-12 d-flex\">\n                            <div class=\"custom-control custom-switch\">\n                                <input type=\"checkbox\" name=\"produto[").concat(produto, "][indisponivel]\" value=\"0\" class=\"custom-control-input produtoIndisponivel\" id=\"prodIndisponivel_").concat(produto, "\">\n                                <label class=\"custom-control-label\" for=\"prodIndisponivel_").concat(produto, "\">Indispon\xEDvel</label>\n                            </div>\n\n                            <div class=\"custom-control custom-switch ml-3\">\n                                <input type=\"checkbox\" name=\"produto[").concat(produto, "][mostrar]\" value=\"1\" checked class=\"custom-control-input produtoMostrar\" id=\"prodMostrar_").concat(produto, "\">\n                                <label class=\"custom-control-label\" for=\"prodMostrar_").concat(produto, "\">Mostrar Produto</label>\n                            </div>\n                        </div>\n\n                        <div class=\"col-md-2 text-center\">\n                            <div class=\"form-group\">\n                                <label for=\"logotipo_produto_").concat(produto, "\"><img src=\"").concat(BASE, "/assets/imgs/sem-foto.jpg\" class=\"preview_").concat(produto, "\" width=\"100\"></label>\n                                <input type=\"file\" class=\"hidden file_preview\"  style=\"cursor:pointer;\" data-preview=\".preview_").concat(produto, "\" name=\"produto[").concat(produto, "][logotipo]\" id=\"logotipo_produto_").concat(produto, "\">\n                            </div>\n                        </div>\n\n                        <div class=\"col-md-10\">\n                            <div class=\"row\">\n                                <div class=\"col-md-5\">\n                                    <div class=\"form-group\">\n                                        <label>Nome</label>\n                                        <input type=\"text\" class=\"form-control nomeProduto\" name=\"produto[").concat(produto, "][nome]\" value=\"\" placeholder=\"Nome\">\n                                    </div>\n                                </div>\n\n                                <div class=\"col-md-5\">\n                                    <div class=\"form-group\">\n                                        <label>Descri\xE7\xE3o</label>\n                                        <input type=\"text\" class=\"form-control \" name=\"produto[").concat(produto, "][descricao]\" value=\"\" placeholder=\"Descri\xE7\xE3o\">\n                                    </div>\n                                </div>\n\n                                <div class=\"col-md-2\">\n                                    <div class=\"form-group\">\n                                        <label>Valor</label>\n                                        <input type=\"text\" class=\"form-control\" name=\"produto[").concat(produto, "][valor]\" value=\"\" placeholder=\"Valor\">\n                                    </div>\n                                </div>\n                            </div>\n                        </div>\n\n\n                        <div class=\"col-md-12\">\n                            <div class=\"accordion\" id=\"accordionProduto").concat(produto, "\">\n                                <button class=\"btn btn-primary btn-block\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapseItem").concat(produto, "\" aria-expanded=\"false\" aria-controls=\"collapseOne\">\n                                    <h4 class=\"p-0 m-0\">Itens</h4>\n                                </button>\n\n                                <div id=\"collapseItem").concat(produto, "\" class=\"collapse shadow-sm\" aria-labelledby=\"headingOne\" data-parent=\"#accordionProduto").concat(produto, "\">\n                                    <button type=\"button\" id=\"").concat(produto, "\" class=\"btn btn-info addItemProduto\"><i class=\"fa fa-plus\"></i> Itens</button>\n                                    <div class=\"row listaItens\"  style=\"max-height: 400px; overflow-x: auto\"></div>\n                                </div>\n\n                                <button class=\"btn btn-primary btn-block\" type=\"button\" data-toggle=\"collapse\" data-target=\"#accordionOpcao").concat(produto, "\" aria-expanded=\"false\" aria-controls=\"collapseOne\">\n                                    <h4 class=\"p-0 m-0\">Op\xE7\xF5es</h4>\n                                </button>\n\n                                <div id=\"accordionOpcao").concat(produto, "\" class=\"collapse shadow-sm\" aria-labelledby=\"headingOne\" data-parent=\"#accordionProduto").concat(produto, "\">\n                                    <button type=\"button\" id=\"").concat(produto, "\" class=\"btn btn-info addOpcao\"><i class=\"fa fa-plus\"></i> Itens</button>\n                                    <div class=\"row listaOpcoes\"  style=\"max-height: 400px; overflow-x: auto\"></div>\n                                </div>\n                            </div>\n                        </div>\n\n                        <div class=\"col-md-12 text-right\"><button type=\"button\" id=\"").concat(produto, "\" class=\"btn btn-sm btn-danger removerProduto\"><i class=\"fa fa-trash\"></i></button></div>\n\n                    </div>\n                </div>\n            </div>\n        </div>\n    ");
  $('.boxLista').prepend(conteudo);
}

$('body').on('change', '.produtoIndisponivel', function () {
  var valor = $(this).val();

  if (valor == 0) {
    $(this).val(1);
  } else {
    $(this).val(0);
  }
});
$('body').on('change', '.produtoMostrar', function () {
  var valor = $(this).val();

  if (valor == 0) {
    $(this).val(1);
  } else {
    $(this).val(0);
  }
});
$('body').on('keyup', '.nomeProduto', function () {
  var produto = $(this).closest('.produto');
  produto.find('.card-header').text($(this).val());
});
$('body').on('click', '.addItemProduto', function () {
  var idProduto = $(this).attr('id');
  var pai = $(this).closest('.produto');
  addItem(pai, idProduto, '.listaItens');
});
$('body').on('click', '.addOpcao', function () {
  var idProduto = $(this).attr('id');
  var pai = $(this).closest('.produto');
  addItem(pai, idProduto, '.listaOpcoes', true);
});

function addItem(pai, idProduto, lista) {
  var sub = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
  var item = 0;
  $.each($('.item'), function () {
    item++;
  });
  var conteudo = "\n        <div id=\"item_".concat(item, "\" class=\"col-md-6 item\">\n            <div class=\"card\">\n            <div class=\"card-header\"></div>\n                <div class=\"card-body\">\n                    <div class=\"row\">\n                        <div class=\"col-md-12 d-flex\">\n                            <div class=\"custom-control custom-switch\">\n                                <input type=\"checkbox\" name=\"").concat(sub ? 'produto[' + idProduto + '][sub][' + item + '][indisponivel]' : 'produto[' + idProduto + '][item][' + item + '][indisponivel]', "\" value=\"0\" class=\"custom-control-input produtoIndisponivel\" id=\"itemIndisponivel_").concat(item, "\">\n                                <label class=\"custom-control-label\" for=\"itemIndisponivel_").concat(item, "\">Indispon\xEDvel</label>\n                            </div>\n\n                            <div class=\"custom-control custom-switch ml-3\">\n                                <input type=\"checkbox\" name=\"").concat(sub ? 'produto[' + idProduto + '][sub][' + item + '][mostrar]' : 'produto[' + idProduto + '][item][' + item + '][mostrar]', "\" value=\"1\" checked class=\"custom-control-input produtoMostrar\" id=\"itemMostrar_").concat(item, "\">\n                                <label class=\"custom-control-label\" for=\"itemMostrar_").concat(item, "\">Mostrar Produto</label>\n                            </div>\n                        </div>\n\n                        <div class=\"col-md-2 text-center\">\n                            <div class=\"form-group\">\n                                <label for=\"logotipo_item_").concat(item, "\"><img src=\"").concat(BASE, "/assets/imgs/sem-foto.jpg\" class=\"preview_item_").concat(item, "\" width=\"70\"></label>\n                                <input type=\"file\" class=\"hidden file_preview\"  style=\"cursor:pointer;\" data-preview=\".preview_item_").concat(item, "\" name=\"").concat(sub ? 'produto[' + idProduto + '][sub][' + item + '][logotipo]' : 'produto[' + idProduto + '][item][' + item + '][logotipo]', "\" id=\"logotipo_item_").concat(item, "\">\n                            </div>\n                        </div>\n\n                        <div class=\"col-md-10\">\n                            <div class=\"row\">\n                                <div class=\"col-md-12\">\n                                    <div class=\"form-group\">\n                                        <label>Nome</label>\n                                        <input type=\"text\" id=\"").concat(item, "\" class=\"form-control nomeItem\" name=\"").concat(sub ? 'produto[' + idProduto + '][sub][' + item + '][nome]' : 'produto[' + idProduto + '][item][' + item + '][nome]', "\" value=\"\" placeholder=\"Nome\">\n                                    </div>\n                                </div>\n\n                                <div class=\"col-md-6\">\n                                    <div class=\"form-group\">\n                                        <label>Descri\xE7\xE3o</label>\n                                        <input type=\"text\" class=\"form-control\" name=\"").concat(sub ? 'produto[' + idProduto + '][sub][' + item + '][descricao]' : 'produto[' + idProduto + '][item][' + item + '][descricao]', "\" value=\"\" placeholder=\"Descri\xE7\xE3o\">\n                                    </div>\n                                </div>\n\n                                <div class=\"col-md-6\">\n                                    <div class=\"form-group\">\n                                        <label>Valor</label>\n                                        <input type=\"text\" class=\"form-control\" name=\"").concat(sub ? 'produto[' + idProduto + '][sub][' + item + '][valor]' : 'produto[' + idProduto + '][item][' + item + '][valor]', "\" value=\"\" placeholder=\"Valor\">\n                                    </div>\n                                </div>\n\n                                ").concat(sub ? '' : "\n                                    <div class=\"col-md-6\">\n                                        <div class=\"form-group\">\n                                            <label>M\xE1ximo op\xE7\xE3o</label>\n                                            <input type=\"number\" class=\"form-control\" min=\"1\" name=\"produto[".concat(idProduto, "][item][").concat(item, "][max_opcoes]\" value=\"\" placeholder=\"M\xE1ximo de op\xE7\xF5es\">\n                                        </div>\n                                    </div>\n\n                                    <div class=\"col-md-6\">\n                                        <div class=\"form-group\">\n                                            <label>M\xE1ximo op\xE7\xE3o paga</label>\n                                            <input type=\"number\" class=\"form-control\" min=\"1\" name=\"produto[").concat(idProduto, "][item][").concat(item, "][max_opcoes_pagas]\" value=\"\" placeholder=\"M\xE1ximo de op\xE7\xF5es pagas\">\n                                        </div>\n                                    </div>\n                                "), "\n                            </div>\n                        </div>\n\n                        <div class=\"col-md-12\"><hr></div>\n                        <div class=\"col-md-12 text-right\"><button type=\"button\" id=\"").concat(item, "\" class=\"btn btn-sm btn-danger removerItem\"><i class=\"fa fa-trash\"></i></button></div>\n                    </div>\n                </div>\n            </div>\n        </div>\n    ");
  pai.find(lista).prepend(conteudo);
}

$('body').on('click', '.removerProduto', function () {
  var _this = this;

  var data = $(this).data();
  var item = data.item;
  var link = data.link;
  Swal.fire({
    title: 'Deletar este produto',
    icon: 'question',
    text: '',
    showCancelButton: true
  }).then(function (result) {
    if (result.isConfirmed) {
      $(_this).closest('.produto').remove();

      if (item != undefined) {
        $.ajax({
          url: link,
          method: 'DELETE',
          success: function success(r) {}
        });
      }
    }
  });
});
$('body').on('click', '.removerItem', function () {
  var _this2 = this;

  var data = $(this).data();
  var item = data.item;
  var link = data.link;
  Swal.fire({
    title: 'Deletar este item',
    icon: 'question',
    text: '',
    showCancelButton: true
  }).then(function (result) {
    if (result.isConfirmed) {
      $(_this2).closest('.item').remove();

      if (item != undefined) {
        $.ajax({
          url: link,
          method: 'DELETE',
          success: function success(r) {}
        });
      }
    }
  });
});
$('body').on('keyup', '.nomeItem', function () {
  var item = $(this).closest('.item');
  item.find('.card-header').text($(this).val());
});
$('body').on('submit', 'form[name="formProdutosNegocio"]', function (e) {
  e.preventDefault();
  $(this).ajaxSubmit({
    url: $(this).attr('action'),
    method: 'POST',
    dataType: 'JSON',
    success: function success(r) {
      Swal.fire({
        title: '',
        text: r.result.texto,
        icon: 'success',
        timer: 1500,
        position: 'top-end'
      }).then(function (response) {
        window.location.reload();
      });
    }
  });
}); //Pedidos

$('body').on('click', '.itensPedido', function () {
  $('.modal').modal('show');
  $('.modal-title').text('Itens do pedido ' + $(this).data('pedido'));
  $('.modal-dialog').addClass('modal-xl');
  preload();
  $.get($(this).data('link'), function (r) {
    preload(false);

    if (r.itens) {
      var conteudo = '';
      r.itens.map(function (it, i) {
        conteudo += "\n                    <tr>\n                        <td>".concat(i += 1, "</td>\n                        <td>").concat(it.produtoPrincipal.nome, " (").concat(it.produto.nome, ")</td>\n                        <td>").concat(it.valor ? it.qtd : '', "</td>\n                        <td>").concat(it.valor ? moeda(it.valor) : '', "</td>\n                    </tr>\n                ");

        if (it.opcoes) {
          it.opcoes.map(function (op, iop) {
            conteudo += "\n                            <tr class=\"bg-light\">\n                                <td colspan=\"2\"> <i class=\"fa fa-arrow-right\"></i> ".concat(op.nome, "</td>\n                                <td>").concat(op.qtd, "</td>\n                                <td>").concat(op.valor ? moeda(op.valor) : '', "</td>\n                            </tr>\n                        ");
          });
        }
      });
      $('.modal-body').html("\n                <div class=\"table-responsive\">\n                    <table class=\"table table-striped table-hover\">\n                        <thead>\n                            <tr>\n                                <th></th>\n                                <th>Nome</th>\n                                <th>Quantidade</th>\n                                <th>Valor</th>\n                            </tr>\n                        </thead>\n                        <tbody>\n                            ".concat(conteudo, "\n                        </tbody>\n                    </table>\n                </div>\n            "));
    }
  });
});
$('body').on('click', '.statusPedido', function () {
  var link = $(this).data('link');
  var action = $(this).data('action');
  $('.modal').modal('show');
  $('.modal-title').text('Status do pedido ' + $(this).data('pedido'));
  $('.modal-dialog').addClass('modal-xl');
  $('.modal-body').html("\n        <form class=\"row\" action=\"".concat(action, "\" name=\"formStatus\" method=\"post\">\n            <div class=\"col-md-3\">\n                <div class=\"input-group\">\n                    <div class=\"input-group-prepend\">\n                        <label class=\"input-group-text pb-0 pt-0 pr-1\" for=\"inputGroupSelect01\">Options</label>\n                    </div>\n\n                    <select class=\"custom-select\" name=\"status\" id=\"inputGroupSelect01\">\n                        <option value=\"\"></option>\n                        <option value=\"pedido\">Pedido</option>\n                        <option value=\"preparacao\">Em prepara\xE7\xE3o</option>\n                        <option value=\"acaminho\">A caminho</option>\n                        <option value=\"entregue\">Entregue</option>\n                    </select>\n                </div>\n            </div>\n\n            <div class=\"col-md-4\">\n                <textarea name=\"obs\" class=\"form-control\" placeholder=\"Observa\xE7\xF5es\"></textarea>\n            </div>\n\n            <div class=\"col-md-4\">\n                <button type=\"submit\" class=\"btn btn-success btn-sm\" title=\"Salvar\"><i class=\"fa fa-save\"></i></button>\n            </div>\n        </form>\n\n        <div class=\"table-responsive\">\n            <table class=\"table table-striped table-hover tabelaStatus\">\n                <thead>\n                    <tr>\n                        <th></th>\n                        <th>Status</th>\n                        <th>Observa\xE7\xE3o</th>\n                        <th>Usu\xE1rio</th>\n                        <th></th>\n                    </tr>\n                </thead>\n\n                <tbody>\n\n                </tbody>\n            </table>\n        </div>\n    "));
  listaStatus(link);
});
$('body').on('submit', 'form[name="formStatus"]', function (e) {
  e.preventDefault();
  $.post($(this).attr('action'), $(this).serialize(), function (r) {
    if (r.result.status) {
      Swal.fire({
        title: 'Sucesso',
        text: r.result.msg,
        icon: 'success'
      }).then(function (result) {
        window.location.reload();
      });
      listaStatus(r.result.link);
    }
  });
});

function listaStatus(link) {
  preload();
  var conteudo = '';
  $.ajax({
    url: link,
    async: false,
    success: function success(r) {
      preload(false);

      if (r.status) {
        r.status.map(function (status, i) {
          $('select[name="status"]').find('option[value="' + status.status + '"]').attr('disabled', true);
          var botaoExcluir = "<button type=\"button\" data-url=\"".concat(status.excluir, "\" class=\"btn btn-sm btn-danger excluirStatus\" title=\"Excluir\"><i class=\"fa fa-trash\"></i></button>");
          conteudo += "\n                        <tr>\n                            <td>".concat(i += 1, "</td>\n                            <td>\n                                ").concat(status.status == 'pedido' ? 'Pedido efetuado' : '', "\n                                ").concat(status.status == 'preparacao' ? 'Pedido em preparação' : '', "\n                                ").concat(status.status == 'acaminho' ? 'Pedido a caminho' : '', "\n                                ").concat(status.status == 'entregue' ? 'Pedido entregue' : '', "\n                                ").concat(status.status == 'cancelado' ? 'Pedido cancelado' : '', "\n                            </td>\n                            <td>").concat(status.obs ? status.obs : '', "</td>\n                            <td>\n                                ").concat(status.usuario ? status.usuario.name : 'Pedido através da vitrine', "\n                            </td>\n                            <td>\n                                ").concat(r.listaStatus.includes('cancelado') ? '' : status.status != 'pedido' ? botaoExcluir : '', "\n                            </td>\n                        </tr>\n                    ");
        });
      }
    }
  });
  $('.tabelaStatus tbody').html(conteudo);
}

$('body').on('click', '.cancelarPedido', function () {
  var link = $(this).data('link');
  Swal.fire({
    title: 'Deseja Cancelar este pedido?',
    text: 'Esta ação não poderá ser desfeita!',
    icon: 'question',
    showCancelButton: true
  }).then(function (click) {
    if (click.isConfirmed) {
      $.get(link, function (r) {
        if (r.result.status) {
          Swal.fire({
            text: r.result.texto,
            icon: 'success',
            timer: 1500,
            position: 'top-end'
          }).then(function (response) {
            window.location.reload();
          });
        }
      });
    }
  });
});
$('body').on('click', '.excluirStatus', function () {
  var link = $(this).data('url');
  Swal.fire({
    title: 'Excluir este status?',
    text: '',
    icon: 'question',
    showCancelButton: true
  }).then(function (click) {
    if (click.isConfirmed) {
      $.get(link, function (r) {
        if (r.result.status) {
          listaStatus(r.result.link);
          Swal.fire({
            text: r.result.texto,
            icon: 'success',
            timer: 1500,
            position: 'top-end'
          }).then(function (response) {});
        }
      });
    }
  });
});
$('body').on('click', '.imprimirPedidos', function () {
  var conteudo = document.getElementById('tabelaPedidos').innerHTML;
  tela_impressao = window.open('about:blank');
  tela_impressao.document.write(conteudo);
  tela_impressao.window.print();
  tela_impressao.window.close();
});
/******/ })()
;