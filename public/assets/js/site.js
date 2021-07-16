/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************!*\
  !*** ./resources/views/assets/js/site.js ***!
  \*******************************************/
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
var aberto = $('input[name="aberto"]').val();
BASE = $('meta[name="BASE"]').attr('content');

window.onload = function () {
  'use strict';

  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(BASE + '/sw.js');
  }
};

var carrinho = [];
var itensOpcao = [];
var limiteItens = [];
var itemAtual = '';
var valorItem = 0;
var ListaOpcoes = [];
var nomeOpcaoAtual = '';
var nomeProdutoAtual = '';
var subTotal = $('.subTotal');
$('body').on('keyup change', '.pesquisar', function () {
  var produto = $(this).closest('.produto');
  var itens = produto.find('.item');
  var nome = $(this).val().toLowerCase();
  itens.map(function (key, s) {
    var item = $(s);
    var search = item.attr('data-search').toLowerCase();

    if (search.includes(nome)) {
      item.fadeIn();
    } else {
      item.fadeOut();
    }
  });
});
var searchProd = '';
$('body').on('click', '.searchProd', function (e) {
  e.preventDefault();
  var produtos = $('.produto');
  var id = $(this).attr('id');

  if (searchProd == id) {
    produtos.fadeIn();
    $(this).removeClass('bg-light');
  } else {
    $('.searchProd').removeClass('bg-light');
    $(this).addClass('bg-light');
    produtos.map(function (key, s) {
      var item = $(s);
      var search = item.attr('data-search');
      searchProd = id;

      if (search.includes(id)) {
        item.fadeIn();
      } else {
        item.fadeOut();
      }
    });
  }
});
$('body').on('click', '.addItem', function () {
  if (aberto == 0) {
    Swal.fire({
      title: 'Ooops!',
      text: 'Estamos fechados no momento!',
      icon: 'warning'
    });
    return;
  } else {
    var pai = $(this).closest('.item');
    var search = pai.data('search');
    var valor = pai.data('valor');
    var id = $(this).attr('id');
    var nomeProduto = pai.closest('.produto').find('.tituloItem').data('titulo');

    if (!$("#itemCarrinho_".concat(id)).length) {
      carrinho.push({
        nomeProduto: nomeProduto,
        id: id,
        nome: search,
        qtd: 1,
        valor: valor
      });
      lista_carrinho();
    }
  }
});
$('body').on('click', '.JanelaOpcao', function () {
  var item_id = $(this).attr('id');
  var produto_id = $(this).data('produto');
  var link = $(this).data('link');
  nomeOpcaoAtual = $(this).closest('.item').data('search');
  nomeProdutoAtual = $(this).closest('.produto').find('.tituloItem').data('titulo');

  if (!$('.modal').length) {
    $('body').append("\n        <div class=\"modal fade\" id=\"staticBackdrop\" data-backdrop=\"static\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"staticBackdropLabel\" aria-hidden=\"true\">\n            <div class=\"modal-dialog\" role=\"document\">\n                <div class=\"modal-content\">\n                    <div class=\"modal-header\">\n                    <h5 class=\"modal-title\" id=\"staticBackdropLabel\"></h5>\n                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n                        <span aria-hidden=\"true\">&times;</span>\n                    </button>\n                    </div>\n\n                    <div class=\"modal-body\"></div>\n\n                    <div class=\"modal-footer\">\n                        <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>\n                        <button type=\"button\" data-item=\"\" class=\"btn btn-success incluirOpcoes\">Confirmar</button>\n                    </div>\n                </div>\n            </div>\n    </div>");
  }

  itemAtual = item_id;
  $('.modal-title').text('Opções: ' + nomeOpcaoAtual);
  listaOpcoes(link);
  $('.modal').modal('show');
});

function listaOpcoes(link) {
  var conteudo = '';
  $('.modal-body').addClass('p-0');
  $.get(link, function (r) {
    if (r.item) {
      var encontrou = false;
      valorItem = r.item.valor;
      limiteItens.filter(function (item) {
        encontrou = item.id == r.item.id;
      });

      if (!encontrou) {
        limiteItens.push({
          id: r.item.id,
          max_opcao: r.item.max_opcoes,
          max_opcao_paga: r.item.max_opcoes_pagas
        });
      }
    }

    if (r.opcoes) {
      ListaOpcoes = r.opcoes;
      conteudo += "\n                <div class=\"card\">\n                    <div class=\"card-header pt-1 pb-1 d-flex justify-content-between\">\n                        <span>Op\xE7\xF5es</span>\n                        ".concat(r.item.max_opcoes ? "<span>Limite de <strong> ".concat(r.item.max_opcoes, "</strong> op\xE7").concat(r.item.max_opcoes > 1 ? 'ões' : 'ão', "</span>") : '', "\n                    </div>\n\n                    <div class=\"card-body\">").concat(opcoes(r.opcoes), "</div>\n                </div>\n\n                <div class=\"card\">\n                    <div class=\"card-header pt-1 pb-1 d-flex justify-content-between\">\n                        <span>Op\xE7\xF5es adicionais pagos</span>\n                        ").concat(r.item.max_opcoes_pagas ? "<span>Limite de <strong>".concat(r.item.max_opcoes_pagas, "</strong> op\xE7").concat(r.item.max_opcoes_pagas > 1 ? 'ões' : 'ão', "</span>") : '', "\n                    </div>\n\n                    <div class=\"card-body\">").concat(opcoes_pagas(r.opcoes), "</div>\n                </div>\n\n\n                <div class=\"row p-3\">\n                    <div class=\"col-md-6 mb-1\">\n                        <input type=\"number\" value=\"1\" min=\"1\" class=\"form-control calculaSubtotalOpcao\" placeholder=\"Quantidade\">\n                    </div>\n\n                    <div class=\"col-md-6 mb-1 \">\n                        <span class=\"badge badge-info text-white py-2 px-2 subTotalOpcao\"></span>\n                    </div>\n                </div>\n            ");
    }

    $('.modal-body').html("\n            <div class=\"list-group\">\n                ".concat(conteudo, "\n            </div>\n        "));
  });
}

$('body').on('keyup mouseup', '.calculaSubtotalOpcao,.opcao_paga', function () {
  calculaSubtotalItens();
});

function calculaSubtotalItens() {
  var qtd = $('.calculaSubtotalOpcao').val();
  var subTotal = 0;
  var selecionado = false;
  $.each($('.opcao'), function (i, op) {
    if ($(op).is(':checked')) {
      selecionado = true;
    }
  });

  if (selecionado) {
    subTotal += valorItem * qtd;
  }

  $.each($('.opcao_paga'), function (i, op) {
    if ($(op).is(':checked')) {
      item = ListaOpcoes.find(function (item) {
        return item.id == $(op).attr('id');
      });
      subTotal += parseFloat(item.valor) * qtd;
    }
  });
  $('.subTotalOpcao').text('Subtotal de ' + moeda(subTotal));
}

function opcoes(opcoes) {
  var conteudo = '';
  opcoes.map(function (opcao) {
    if (opcao.mostrar) {
      if (!opcao.valor) {
        conteudo += "\n                    <div class=\"\">\n                        <label class=\"d-flex justify-content-between\" style=\"cursor:pointer;\">\n                            <span>\n                                <input type=\"checkbox\" data-itemid=\"".concat(opcao.item_id, "\" id=\"").concat(opcao.id, "\" name=\"opcao[").concat(opcao.id, "][op]\" class=\"opcao\">\n                                ").concat(opcao.nome, "\n                                ").concat(opcao.descricao ? "<span class=\"badge badge-primary\">".concat(opcao.descricao, "</span>") : '', "\n                            </span>\n                        </label>\n                    </div>\n                ");
      }
    }
  });
  return conteudo;
}

function opcoes_pagas(opcoes) {
  var conteudo = '';
  opcoes.map(function (opcao) {
    if (opcao.mostrar) {
      if (opcao.valor) {
        conteudo += "\n                    <div class=\"\">\n                        <label class=\"d-flex justify-content-between\" style=\"cursor:pointer;\">\n                            <span>\n                                <input type=\"checkbox\" data-itemid=\"".concat(opcao.item_id, "\" id=\"").concat(opcao.id, "\" name=\"opcao[").concat(opcao.id, "][op]\" class=\"opcao_paga\">\n                                <input type=\"hidden\" value=\"").concat(opcao.valor, "\" name=\"opcao[").concat(opcao.id, "][valor]\" >\n                                ").concat(opcao.nome, "\n                                ").concat(opcao.descricao ? "<span class=\"badge badge-primary\">".concat(opcao.descricao, "</span>") : '', "\n                            </span>\n                            <span>").concat(opcao.valor.toLocaleString('pt-BR', {
          style: 'currency',
          currency: 'BRL'
        }), "</span>\n                        </label>\n                    </div>\n                ");
      }
    }
  });
  return conteudo;
}

$('body').on('change', '.opcao', function () {
  var itemid = $(this).data('itemid');
  var limite = limiteItens.filter(function (item) {
    return item.id == itemid;
  });
  var qtdOpcoes = $('.opcao:checked').length;

  if (qtdOpcoes > limite[0].max_opcao) {
    if ($(this).is(':checked')) {
      $(this).prop('checked', false);
    } else {
      $(this).prop('checked', true);
    }
  }

  calculaSubtotalItens();
});
$('body').on('change', '.opcao_paga', function () {
  var itemid = $(this).data('itemid');
  var limite = limiteItens.filter(function (item) {
    return item.id == itemid;
  });
  var qtdOpcoes = $('.opcao_paga:checked').length;

  if (limite[0].max_opcao_paga && qtdOpcoes > limite[0].max_opcao_paga) {
    if ($(this).is(':checked')) {
      $(this).prop('checked', false);
    } else {
      $(this).prop('checked', true);
    }
  }

  calculaSubtotalItens();
});
$('body').on('click', '.incluirOpcoes', function () {
  if (aberto == 0) {
    Swal.fire({
      title: 'Ooops!',
      text: 'Estamos fechados no momento!',
      icon: 'warning'
    });
    return;
  } else {
    incluirOpcoes();
  }
});

function incluirOpcoes() {
  var item = carrinho.find(function (item) {
    return item.id == itemAtual;
  });
  var opcoes = [];
  var existeOpcao = false;
  $.each($('.opcao'), function (i, op) {
    if ($(this).is(':checked')) {
      opcao_normal = ListaOpcoes.find(function (it) {
        return it.id == $(op).attr('id');
      });
      opcoes.push(opcao_normal);

      if (!existeOpcao) {
        existeOpcao = true;
      }
    }
  });
  $.each($('.opcao_paga'), function (i, op) {
    if ($(this).is(':checked')) {
      opcao_paga = ListaOpcoes.find(function (it) {
        return it.id == $(op).attr('id');
      });
      opcoes.push({
        id: opcao_paga.id,
        qtd: 1,
        valor: opcao_paga.valor,
        nome: opcao_paga.nome
      });

      if (!existeOpcao) {
        existeOpcao = true;
      }
    }
  });

  if (!existeOpcao) {
    Swal.fire({
      title: 'Selecione ao menos uma das opções',
      icon: 'warning',
      timer: 1500
    });
  } else {
    if (item == undefined) {
      carrinho.push({
        id: itemAtual,
        valor: valorItem,
        nome: nomeOpcaoAtual,
        qtd: $('.calculaSubtotalOpcao').val(),
        opcoes: opcoes,
        nomeProduto: nomeProdutoAtual
      });
    } else {
      item.qtd = $('.calculaSubtotalOpcao').val();
      item.opcoes = opcoes;
    }

    lista_carrinho();
    $('.modal').modal('hide');
  }
}

function lista_carrinho() {
  var box_carrinho = $('.carrinho');
  box_carrinho.find('.tabelaCarrinho tbody').empty();
  var badgeCarrinho = $('.badgeCarrinho');
  var totalItens = 0;
  var conteudoItem = '';
  carrinho.map(function (item, i) {
    var key = i + 1;
    var valor = item.valor ? parseFloat(item.valor) * (item.qtd ? item.qtd : 0) : 0;
    var td_opcoes = '';

    if (item.opcoes) {
      item.opcoes.map(function (op, opi) {
        td_opcoes += "\n                    <tr>\n                        <td colspan=\"4\">".concat(op.nome, "</td>\n                        <td>");

        if (!item.valor) {
          td_opcoes += "<input type=\"number\" data-index=\"".concat(i, "\" data-indexOpcao=\"").concat(opi, "\" min=\"1\" min-value=\"1\" value=\"").concat(op.qtd ? op.qtd : 1, "\" class=\"form-control form-control-sm quantidade\" style=\"width:100px\">");
        }

        td_opcoes += "</td>\n\n                        <td class=\"valorOpcao_".concat(op.id, "\">").concat(item.valor ? moeda(parseFloat(op.valor) * parseInt(item.qtd)) : op.valor > 0 ? moeda(parseFloat(op.valor) * parseInt(op.qtd)) : '', "</td>\n                    </tr>\n                ");
      });
    }

    conteudoItem = "\n            <tr id=\"itemCarrinho_".concat(item.id, "\" class=\"itemCarrinho\">\n                <td class=\"py-1 px-2\">").concat(key, "</td>\n                <td class=\"py-1 px-2\">").concat(item.nomeProduto, " - ").concat(item.nome, "</td>\n                    <td class=\"py-1 px-2\">");

    if (item.valor) {
      conteudoItem += "<input type=\"number\" data-index=\"".concat(i, "\" min=\"1\" min-value=\"1\" value=\"").concat(item.qtd, "\" class=\"form-control form-control-sm quantidade\" style=\"width:100px\">");
    }

    conteudoItem += "</td>\n                    <td class=\"py-1 px-2 valorItem\">".concat(valor ? moeda(valor) : '', "</td>\n                <td class=\"py-1 px-2\"><button type=\"button\" data-index=\"").concat(i, "\" class=\"btn text-danger btn-sm removerItem\"><i class=\"fa fa-times\"></i></button></td>\n            </tr>\n            ").concat(td_opcoes, "\n        ");
    box_carrinho.find('.tabelaCarrinho tbody').append(conteudoItem);
    totalItens += 1;
    badgeCarrinho.text(totalItens);
    calculaValorTotal();
  });
}

var valorTotal = 0;

function calculaValorTotal() {
  valorTotal = 0;
  carrinho.map(function (item, i) {
    valorTotal += item.valor ? parseFloat(item.valor) * (item.qtd ? parseInt(item.qtd) : 0) : 0;

    if (item.opcoes) {
      item.opcoes.map(function (op) {
        if (op.valor) {
          valorTotal += parseFloat(op.valor) * (item.valor ? parseInt(item.qtd) : parseInt(op.qtd));
        }
      });
    }
  });

  if (carrinho.length) {
    subTotal.fadeIn();
  } else {
    subTotal.fadeOut();
  }

  $('.total').text(moeda(valorTotal));
}

$('body').on('keyup mouseup ', '.quantidade', function () {
  var este = $(this);
  var index = este.data('index');
  var item = carrinho[index];
  item.qtd = parseInt($(this).val());

  if (item.valor) {
    var valorItem = parseFloat(item.valor) * (item.qtd ? parseInt(item.qtd) : 0);

    if (item.opcoes) {
      item.opcoes.map(function (op) {
        if (op.valor) {
          $(".valorOpcao_".concat(op.id)).text(moeda(parseFloat(op.valor) * (item.qtd ? parseInt(item.qtd) : 0)));
        }
      });
    }

    $(this).closest('.itemCarrinho').find('.valorItem').text(moeda(valorItem));
  } else {
    var itemOpcao = item['opcoes'][este.data('indexopcao')];
    itemOpcao.qtd = parseInt($(this).val());
    var valorOpcao = parseFloat(itemOpcao.valor) * (itemOpcao.qtd ? parseInt(itemOpcao.qtd) : 0);
    $(".valorOpcao_".concat(itemOpcao.id)).text(moeda(valorOpcao));
    $(this).closest('.itemCarrinho').find('.valorItem').text(moeda(valorOpcao));
  }

  calculaValorTotal();
});
$('body').on('click', '.removerItem', function () {
  var index = $(this).data('index');
  $(this).closest('.itemCarrinho').remove();
  carrinho.splice(carrinho.indexOf(index), 1);
  lista_carrinho();

  if (!carrinho.length) {
    mostraCarrinho();
    subTotal.fadeOut();
    $('.total').text('');
    $('.badgeCarrinho').text('');
  }
});
$('body').on('click', '.boxCarrinho', function () {
  if (!carrinho.length) {
    Swal.fire({
      title: 'Ooops...',
      text: 'Sua cesta está vazia',
      icon: 'warning',
      timer: 1500
    });
    return;
  }

  mostraCarrinho();
});
$('body').on('click', '.bgCarrinho', function () {
  mostraCarrinho();
});

function mostraCarrinho() {
  $('.bgCarrinho').fadeToggle();
  $('.carrinho').animate({
    width: ["toggle", "swing"]
  }, 'fast', "linear");
  $('body').toggleClass('noOverflow');
}

$('body').on('click', '.viaWhatsapp', function () {
  if (aberto == 0) {
    Swal.fire({
      title: 'Ooops!',
      text: 'Estamos fechados no momento!',
      icon: 'warning'
    });
    return;
  } else {
    var link = $(this).data('url');
    var observacoes = $('#observacao').val();
    $.post(link, {
      carrinho: carrinho,
      observacoes: observacoes
    }, function (r) {
      var texto = "Ol\xE1, estou querendo: \n";
      carrinho.map(function (item, i) {
        if (parseInt(item.qtd) >= 1) {
          texto += '\n';
          texto += (item.valor ? item.qtd : '') + ' ' + item.nome + (item.valor ? ' => ' + moeda(parseFloat(item.valor) * parseInt(item.qtd)) : '') + '\n';

          if (item.opcoes) {
            item.opcoes.map(function (opcao) {
              texto += (!item.valor ? '```' + opcao.qtd + '``` ' : ' - ') + '```' + opcao.nome + (opcao.valor ? ' => ' + moeda(parseFloat(opcao.valor) * parseInt(item.qtd)) : '') + '```\n';
            });
          }
        }
      });
      texto += "\n*Total de ".concat(moeda(valorTotal), "*");

      if (observacoes) {
        texto += '\nObservações: ';
        texto += '```' + observacoes + '```';
      }

      texto = window.encodeURIComponent(texto);
      var urlWhatsapp = "https://api.whatsapp.com/send?phone=+55".concat(r.result.tel, "&text=").concat(texto);
      window.open(urlWhatsapp, '_blank');
    });
  }
});

function moeda(valor) {
  return valor.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  });
}
/******/ })()
;