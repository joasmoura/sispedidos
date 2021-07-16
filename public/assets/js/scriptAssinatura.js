/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************************!*\
  !*** ./resources/views/assets/js/scriptAssinatura.js ***!
  \*******************************************************/
if ($('form[name="formAssinar"]').length) {
  $.getScript('https://assets.pagar.me/pagarme-js/3.0/pagarme.min.js'); //API

  $.getScript(BASE + '/assets/js/jquery.payment.min.js', function () {
    $("input[name='cartaoNumero']").payment('formatCardNumber');
    $("input[name='cartaoCodigo']").payment('formatCardCVC');
    $("input[name='cartaoVencimento']").payment('formatCardExpiry');
  });
}

$('.boxCartaoCredito').hide();
verificaMetodoPagamento($('input[name="ad_metodoPagamento"]:checked'));
$('body').on('change', 'input[name="ad_metodoPagamento"]', function () {
  verificaMetodoPagamento($(this));
});

function verificaMetodoPagamento(campo) {
  var valorAtual = campo.val();
  var boxCartao = $('.boxCartaoCredito');

  if (valorAtual == 'credit_card') {
    boxCartao.slideDown();
    boxCartao.find('.campoCartao').attr('disabled', false);
  } else {
    boxCartao.slideUp();
    boxCartao.find('.campoCartao').attr('disabled', true);
  }
}

$('body').on('change', '.selectNegocioAssinatura', function () {
  var valor = $(this).val();

  if (valor) {
    preload();
    $.get("".concat(BASE, "/painel/negocio/dados/").concat(valor), function (r) {
      if (r.negocio) {
        var dados = r.negocio;
        $('input[name="nome"]').val(dados.nome);
        $('input[name="cep"]').val(dados.cep);
        $('input[name="logradouro"]').val(dados.logradouro);
        $('input[name="bairro"]').val(dados.bairro);
        $('input[name="numero"]').val(dados.numero);
        $('input[name="complemento"]').val(dados.complemento);
        $('input[name="pais"]').val(dados.pais.ps_nome_pt);
        $('input[name="estado"]').val(dados.estado.uf_nome);
        $('input[name="cidade"]').val(dados.cidade.cd_nome);
        $('input[name="celular"]').val(dados.whatsapp);
        preload(false);
      }
    });
  }
});
$('body').on('submit', 'form[name="formAssinar"]', function (e) {
  e.preventDefault();
  e.stopPropagation();
  var dados = $(this).serialize();
  var action = $(this).attr('action');
  preload();

  if ($('input[name="ad_metodoPagamento"]:checked').val() == 'boleto') {
    confirmarAssinaturaBoleto(action, dados);
  } else if ($('input[name="ad_metodoPagamento"]:checked').val() == 'credit_card') {
    confirmarAssinaturaCartao(action, dados);
  }
});

function confirmarAssinaturaBoleto(action, dados) {
  $.post(action, dados, function (r) {
    preload(false);

    if (r.result.status) {
      Swal.fire({
        title: '',
        text: r.result.texto,
        icon: 'success',
        timer: 1500,
        position: 'top-end'
      }).then(function (response) {
        window.location.replace(r.result.link);
      });
    }
  }).fail(function (r) {
    preload(false);
    Swal.fire({
      title: 'Erro',
      text: 'Verifique se todos os dados estão corretos!',
      icon: 'error'
    }).then(function (response) {});
  });
}

function confirmarAssinaturaCartao(action, dados) {
  var cardType = $.payment.cardType($("input[name='cartaoNumero']").val());

  if (!$.payment.validateCardNumber($("input[name='cartaoNumero']").val())) {
    preload(false);
    Swal.fire({
      title: '',
      text: 'O número do cartão não é válido!',
      icon: 'warning'
    });
  } else if ($("input[name='cartaoNomeTitular']").val().length < 9) {
    preload(false);
    $("input[name='cartaoNomeTitular']").after("<p class='text-danger alertaCartao'>&#10008; Favor informe o nome impresso no cartão!</p>");
    mensagens(['danger', 'fa fa-ban', ' Favor informe o nome impresso no cartão!', 3000, '']);
    Swal.fire({
      title: '',
      text: 'Informe o nome do titular impresso no cartão!',
      icon: 'warning'
    });
  } else if (!$.payment.validateCardExpiry($("input[name='cartaoVencimento']").payment('cardExpiryVal'))) {
    preload(false);
    Swal.fire({
      title: '',
      text: 'A data informada não é válida!',
      icon: 'warning'
    });
  } else if (!$.payment.validateCardCVC($("input[name='cartaoCodigo']").val(), cardType)) {
    preload(false);
    Swal.fire({
      title: '',
      text: 'O CVV deve ter 3 ou 4 números!',
      icon: 'warning'
    });
  } else {
    var card = {};
    card.card_holder_name = $("input[name='cartaoNomeTitular']").val();
    card.card_expiration_date = $("input[name='cartaoVencimento']").val();
    card.card_number = $("input[name='cartaoNumero']").val();
    card.card_cvv = $("input[name='cartaoCodigo']").val();
    var cardValidations = pagarme.validate({
      card: card
    });

    if (!cardValidations.card.card_number) {
      preload(false);
      Swal.fire({
        title: '',
        text: 'O número do cartão não é válido!',
        icon: 'warning'
      });
    } else {
      // pagarme.client.connect({encryption_key:key}).then(client => client.security.encrypt(card)).then(card_hash => {
      $.post(action, dados, function (r) {
        preload(false);

        if (r.result.status) {
          Swal.fire({
            title: '',
            text: r.result.texto,
            icon: 'success',
            position: 'top-end',
            timer: 1500
          }).then(function (response) {
            window.location.replace(r.result.link);
          });
        }
      }, 'json').fail(function (r) {
        preload(false);

        if (r.responseJSON) {
          Swal.fire({
            title: '',
            text: r.responseJSON.message,
            icon: 'warning'
          });
        }
      }); // });
    }
  }
}

$('body').on('click', '.cancelarAssinatura', function () {
  Swal.fire({
    title: 'Cancelar assinatura',
    text: 'Que pena que está indo! Informamos que esta ação não poderá ser deifeita, você poderá aderir a outro plano a qualquer hora!',
    icon: 'question',
    showCancelButton: true
  }).then(function (response) {
    if (response.isConfirmed) {
      preload();
      $.get("".concat(BASE, "/painel/assinatura/cancelar"), function (r) {
        preload(false);

        if (r.result.status) {
          Swal.fire({
            title: '',
            text: r.result.texto,
            icon: 'success',
            timer: 1500,
            position: 'top-end'
          }).then(function (response) {
            window.location.replace(r.result.link);
          });
        } else {
          Swal.fire({
            title: 'Ooops...',
            text: r.result.texto,
            icon: 'error'
          }).then(function (response) {});
        }
      }).fail(function (e) {
        preload(false);
        Swal.fire({
          title: 'Ooops...',
          text: 'Algo deu errado ao cancelar seu plano, favor, entre em contato com o suporte para obter informações!',
          icon: 'error'
        }).then(function (response) {});
      });
    }
  });
});
/******/ })()
;