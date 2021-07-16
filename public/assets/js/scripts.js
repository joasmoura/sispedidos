/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./resources/views/assets/js/scripts.js ***!
  \**********************************************/
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
BASE = $('meta[name="BASE"]').attr('content');

if ($('.selectPaises').length) {
  $('.selectPaises').select2();
}

if ($('.selectEstados').length) {
  $('.selectEstados').select2();
}

if ($('.selectCidades').length) {
  $('.selectCidades').select2();
}

$('body').on('keyup mouseenter', '.hora', function () {
  $(this).mask('99:99');
});
$('body').on('click', '.verificaCadastros', function (e) {
  e.preventDefault();
  $.get($(this).data('verifica'), function (r) {
    if (r.status) {
      window.location.href = $(this).attr('href');
    } else {
      Swal.fire({
        title: 'Oooops...',
        text: 'Você atingiu o limite de negócios da sua assinatura. Verifique os planos disponíveis e aumente este limite!',
        icon: 'info'
      });
    }
  });
});

function preload() {
  var status = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
  var preload = $('<div class="preload"/>');

  if (status) {
    preload.append("\n            <div class=\"d-flex align-items-center position-relative \" style=\"width:100%; height:100%\">\n                <div class=\"bg-white d-flex align-items-center position-relative justify-content-center m-auto rounded-lg body\">\n                    <div class=\"spinner-border text-primary\" role=\"status\"><span class=\"sr-only\"></span></div>\n                    <div class=\"pl-2 tituloPreload\"> Carregando...</div>\n                </div>\n            </div>\n        ");
    $('body').append(preload);
    $(preload).fadeIn();
  } else {
    $('.preload').fadeOut();
  }
} //PREVIEW DAS IMAGENS


$('body').on('change', '.file_preview', function () {
  preview_imagem(this, $(this).data('preview'));
});

function preview_imagem(file, img) {
  var input = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

  if (file.files && file.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $(img).attr('src', e.target.result);

      if (input != '') {
        $(input).val(e.target.result);
      }
    };

    reader.readAsDataURL(file.files[0]);
  }
}

$('body').on('change', '.selectPaises', function () {
  var id = $(this).val();
  $('.selectEstados').empty();
  $('.selectEstados').append($('<option>', {
    value: '',
    text: 'Selecione...'
  }));
  $('.selectCidades').empty();
  $('.selectCidades').append($('<option>', {
    value: '',
    text: 'Selecione...'
  }));
  $.get("".concat(BASE, "/painel/estados/").concat(id), function (r) {
    if (r.estados) {
      r.estados.map(function (estado) {
        $('.selectEstados').append($('<option>', {
          value: estado.id,
          text: estado.uf_nome
        }));
      });
    }
  });
});
$('body').on('change', '.selectEstados', function () {
  var id = $(this).val();
  $('.selectCidades').empty();
  $('.selectCidades').append($('<option>', {
    value: '',
    text: 'Selecione...'
  }));
  $.get("".concat(BASE, "/painel/cidades/").concat(id), function (r) {
    if (r.cidades) {
      r.cidades.map(function (cidade) {
        $('.selectCidades').append($('<option>', {
          value: cidade.id,
          text: cidade.cd_nome
        }));
      });
    }
  });
});
$('body').on('click', '.buscarEndereco', function () {
  var zip_code = $('input[name="cep"]').val().replace(/\D/g, '');
  var validate_zip_code = /^[0-9]{8}$/;
  var campo = $(this);
  preload();

  if (zip_code != "" && validate_zip_code.test(zip_code)) {
    $.getJSON("https://viacep.com.br/ws/" + zip_code + "/json/?callback=?", function (data) {
      preload(false);

      if (!("erro" in data)) {
        $(".logradouro").val(data.logradouro);
        $('.bairro').val(data.bairro); // $(`option[uf="${data.uf}"]`).prop('selected',true).trigger('change');
        //campo.closest('.loja').find('.uf').select2();
        // var loja = campo.closest('.loja').attr('id');
        // uf_cidade($('select[name="loja['+loja+'][estados_id]"]'),data.localidade);
      } else {
        preload(false);
        alert("CEP não encontrado.");
      }
    });
  } else {
    alert("Formato de CEP inválido.");
  }
});
$('body').on('submit', 'form[name="formNegocio"]', function (e) {
  e.preventDefault();
  preload();
  $(this).ajaxSubmit({
    url: $(this).attr('action'),
    method: 'POST',
    dataType: 'json',
    beforeSend: function beforeSend() {},
    success: function success(r) {
      preload(false);

      if (r.result && r.result.status) {
        Swal.fire({
          position: 'top-end',
          title: '',
          text: r.result.texto,
          icon: 'success',
          timer: 1500
        }).then(function (result) {
          if (r.result.link != '') {
            window.location.replace(r.result.link);
          }
        });
      } else {
        Swal.fire({
          title: '',
          text: r.result.texto,
          icon: 'warning'
        });
      }
    },
    error: function error(e) {
      preload(false);
      var response = e.responseJSON;

      if (response.errors) {
        var i = 0;
        $.each(response.errors, function (key, value) {
          if (i == 0) {
            var mensagem = value.toString();
            Swal.fire({
              position: 'top-end',
              title: '',
              text: mensagem,
              icon: 'warning'
            }).then(function (result) {});
            i += 1;
            return;
          }
        });
      }
    }
  });
});
$('body').on('click', '.deletarNegocio', function (e) {
  e.preventDefault();
  var href = $(this).attr('href');
  var id = $(this).attr('id');
  Swal.fire({
    title: 'Deletar Negócio',
    icon: 'question',
    text: 'Esta ação inicialmente não o excluirá, você poderá recuperá-lo na aba excluídos!',
    showCancelButton: true
  }).then(function (result) {
    if (result.isConfirmed) {
      preload();
      $.post({
        url: href,
        method: 'DELETE',
        success: function success(r) {
          preload(false);

          if (r.result.status) {
            Swal.fire({
              title: '',
              text: r.result.texto,
              icon: 'success',
              timer: 1500,
              position: 'top-end'
            }).then(function (response) {
              if (response.isConfirmed || response.isDismissed) {
                $('#negocio_' + id).fadeOut('fast', function () {
                  $(this).remove();
                });
              }
            });
          }
        }
      });
    }
  });
});
$('body').on('click', '.modalNegociosExcluidos', function (e) {
  e.preventDefault();
  $('.modal-title').text('Consultar negócios excluídos');
  $('.modal').attr('data-backdrop', 'static');
  $('.modal-dialog').addClass('modal-lg');
  var tableResponsive = $('<div>').attr({
    "class": 'table-responsive'
  });
  var table = $('<table>').attr({
    "class": 'table table-stripped table-hover tabelaNegociosExcluidos'
  });
  table.append("\n        <thead>\n            <tr>\n                <th></th>\n                <th>Nome</th>\n                <th>Data Exclus\xE3o</th>\n                <th></th>\n            </tr>\n        </thead>\n\n        <tbody></tbody>\n    ");
  tableResponsive.html(table);
  $('.modal-body').html(tableResponsive);
  $('#ModalDialog').modal('show');
  $.get($(this).attr('href'), function (r) {
    if (r.negocios) {
      var body = $('.tabelaNegociosExcluidos tbody');
      body.empty();
      r.negocios.map(function (negocio) {
        body.append("\n                    <tr id=\"excluido_".concat(negocio.id, "\">\n                        <td><img src=\"").concat(negocio.imagem, "\" width=\"50\"></td>\n                        <td>").concat(negocio.nome, "</td>\n                        <td>").concat(negocio.deleted_at, "</td>\n                        <td>\n                            <a href=\"").concat(negocio.linkExcluir, "\" id=\"").concat(negocio.id, "\" class=\"btn btn-danger excluirNegocioPermanentemente\" title=\"Excluir permanentemente\"><i class=\"fa fa-trash\"></i></a>\n                            <a href=\"").concat(negocio.linkRestaurar, "\" class=\"btn btn-success restaurarNegocio\"><i class=\"fa fa-sync\" title=\"Restaurar\"></i></a>\n                        </td>\n                    </tr>\n                "));
      });
    }
  });
});
$('body').on('click', '.excluirNegocioPermanentemente', function (e) {
  e.preventDefault();
  var link = $(this).attr('href');
  var id = $(this).attr('id');
  Swal.fire({
    title: 'Deseja realmente excluir?',
    text: 'Esta ação não poderá ser desfeita.',
    icon: 'question',
    showCancelButton: true
  }).then(function (result) {
    if (result.isConfirmed) {
      preload();
      $.get(link, function (r) {
        preload(false);

        if (r.result) {
          Swal.fire({
            title: '',
            text: r.result.texto,
            icon: 'success',
            timer: 1500,
            position: 'top-end'
          }).then(function (r) {
            if (r.isConfirmed || r.isDismissed) {
              $('#excluido_' + id).fadeOut('slow', function () {
                $(this).remove();
              });
            }
          });
        }
      });
    }
  });
});
$('body').on('click', '.restaurarNegocio', function (e) {
  e.preventDefault();
  var link = $(this).attr('href');
  var id = $(this).attr('id');
  Swal.fire({
    title: 'Restaurar este negócio?',
    text: '',
    icon: 'question',
    showCancelButton: true
  }).then(function (result) {
    if (result.isConfirmed) {
      preload();
      $.get(link, function (r) {
        preload(false);

        if (r.result.status) {
          Swal.fire({
            title: '',
            text: r.result.texto,
            icon: 'success',
            timer: 1500,
            position: 'top-end'
          }).then(function (r) {
            window.location.reload();
          });
        } else {
          Swal.fire({
            title: '',
            text: r.result.texto,
            icon: 'warning'
          });
        }
      });
    }
  });
});
$('body').on('click', '.excluirPlano', function (e) {
  e.preventDefault();
  var href = $(this).attr('href');
  var id = $(this).attr('id');
  Swal.fire({
    title: 'Deletar Plano',
    icon: 'question',
    text: '',
    showCancelButton: true
  }).then(function (result) {
    if (result.isConfirmed) {
      preload();
      $.post({
        url: href,
        method: 'DELETE',
        success: function success(r) {
          preload(false);

          if (r.result.status) {
            Swal.fire({
              title: '',
              text: r.result.texto,
              icon: 'success',
              timer: 1500,
              position: 'top-end'
            }).then(function (response) {
              if (response.isConfirmed || response.isDismissed) {
                $('#plano_' + id).fadeOut('fast', function () {
                  $(this).remove();
                });
              }
            });
          }
        }
      });
    }
  });
});
$('body').on('submit', 'form[name="formPlanos"]', function (e) {
  e.preventDefault();
  preload();
  $.post($(this).attr('action'), $(this).serialize(), function (r) {
    preload(false);
    Swal.fire({
      title: '',
      text: r.result.texto,
      icon: r.result.status ? 'success' : 'error',
      timer: 2000,
      position: 'top-end'
    }).then(function (cl) {
      if (r.result.status) {
        window.location.replace(r.result.link);
      }
    });
  });
});
$('body').on('click', '.adicionarDescricaoPlano', function () {
  var itens = $('.item');
  var keyItem = 1;
  itens.map(function (i) {
    keyItem += 1;
  });
  $('.listaDescricao').append("\n        <div id=\"item_".concat(keyItem, "\" class=\"row item\">\n            <div class=\"col-md-1\">").concat(keyItem, "</div>\n\n            <div class=\"col-md-10\">\n                <input type=\"text\" class=\"form-control\" name=\"item[").concat(keyItem, "][nome]\" placeholder=\"Descri\xE7\xE3o\">\n            </div>\n\n            <div class=\"col-md-1\">\n                <button type=\"button\" id=\"").concat(keyItem, "\" class=\"btn btn-sm btn-danger removerItemPlano\"><i class=\"fa fa-trash\" title=\"Remover Item\"></i></button>\n            </div>\n        </div>\n    "));
});
$("body").on('click', '.removerItemPlano', function () {
  var id = $(this).attr('id');
  $('#item_' + id).remove();
});
$('body').on('submit', 'form[name="formTrial"]', function (e) {
  e.preventDefault();
  $.post($(this).attr('action'), $(this).serialize(), function (r) {
    preload();
    Swal.fire({
      title: '',
      text: r.result.texto,
      icon: r.result.status ? 'success' : 'error',
      timer: 2500,
      position: 'top-end'
    }).then(function (cl) {
      preload(false);

      if (r.result.status) {
        window.location.replace(r.result.link);
      }
    });
  });
});
/******/ })()
;