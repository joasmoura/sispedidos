$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

BASE = $('meta[name="BASE"]').attr('content');

if($('.selectPaises').length){
    $('.selectPaises').select2()
}

if($('.selectEstados').length){
    $('.selectEstados').select2()
}

if($('.selectCidades').length){
    $('.selectCidades').select2()
}

$('body').on('keyup mouseenter','.hora',function(){
    $(this).mask('99:99')
})

$('body').on('click','.verificaCadastros',function(e){
    e.preventDefault();

    $.get($(this).data('verifica'),function(r){
        if(r.status){
            window.location.href = $(this).attr('href')
        }else{
            Swal.fire({
                title:'Oooops...',
                text:'Você atingiu o limite de negócios da sua assinatura. Verifique os planos disponíveis e aumente este limite!',
                icon:'info'
            })
        }
    })

})

function preload(status = true){
    const preload = $('<div class="preload"/>')

    if(status){
        preload.append(`
            <div class="d-flex align-items-center position-relative " style="width:100%; height:100%">
                <div class="bg-white d-flex align-items-center position-relative justify-content-center m-auto rounded-lg body">
                    <div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div>
                    <div class="pl-2 tituloPreload"> Carregando...</div>
                </div>
            </div>
        `)
        $('body').append(preload)
        $(preload).fadeIn()
    }else{
        $('.preload').fadeOut()
    }
}

//PREVIEW DAS IMAGENS
$('body').on('change','.file_preview',function(){
    preview_imagem(this,$(this).data('preview'));
});

function preview_imagem(file,img,input = null) {
    if (file.files && file.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(img).attr('src', e.target.result);
            if(input != ''){
                $(input).val(e.target.result);
            }
        };
        reader.readAsDataURL(file.files[0]);
    }
}

$('body').on('change','.selectPaises',function(){
    const id = $(this).val()
    $('.selectEstados').empty()
    $('.selectEstados').append($('<option>',{
        value:'',
        text:'Selecione...'
    }))

    $('.selectCidades').empty()
    $('.selectCidades').append($('<option>',{
        value:'',
        text:'Selecione...'
    }))

    $.get(`${BASE}/painel/estados/${id}`,function(r){
        if(r.estados){
            r.estados.map(estado => {
                $('.selectEstados').append($('<option>',{
                    value:estado.id,
                    text:estado.uf_nome
                }))
            })
        }
    })
});

$('body').on('change','.selectEstados',function(){
    const id = $(this).val()
    $('.selectCidades').empty()
    $('.selectCidades').append($('<option>',{
        value:'',
        text:'Selecione...'
    }))

    $.get(`${BASE}/painel/cidades/${id}`,function(r){
        if(r.cidades){
            r.cidades.map(cidade => {
                $('.selectCidades').append($('<option>',{
                    value:cidade.id,
                    text:cidade.cd_nome
                }))
            })
        }
    })
});

$('body').on('click','.buscarEndereco',function(){
    var zip_code = $('input[name="cep"]').val().replace(/\D/g, '');
    var validate_zip_code = /^[0-9]{8}$/;

    var campo = $(this);
    preload()

    if (zip_code != "" && validate_zip_code.test(zip_code)) {
        $.getJSON("https://viacep.com.br/ws/" + zip_code + "/json/?callback=?", function (data) {
            preload(false)
            if (!("erro" in data)) {
                $(".logradouro").val(data.logradouro);
                $('.bairro').val(data.bairro);
                // $(`option[uf="${data.uf}"]`).prop('selected',true).trigger('change');
                //campo.closest('.loja').find('.uf').select2();

                // var loja = campo.closest('.loja').attr('id');

                // uf_cidade($('select[name="loja['+loja+'][estados_id]"]'),data.localidade);

            } else {
                preload(false)
                alert("CEP não encontrado.");
            }
        });
    } else {
        alert("Formato de CEP inválido.");
    }
})

$('body').on('submit','form[name="formNegocio"]',function(e){
    e.preventDefault()
    preload()

    $(this).ajaxSubmit({
        url:$(this).attr('action'),
        method:'POST',
        dataType:'json',
        beforeSend:function(){

        },
        success:function(r){
            preload(false)
            if(r.result && r.result.status){
                Swal.fire({
                    position: 'top-end',
                    title:'',
                    text:r.result.texto,
                    icon:'success',
                    timer:1500
                }).then((result) => {
                    if(r.result.link != ''){
                        window.location.replace(r.result.link);
                    }
                });
            }else{
                Swal.fire({
                    title:'',
                    text:r.result.texto,
                    icon:'warning',
                })
            }
        },
        error:function(e){
            preload(false)
            const response = e.responseJSON
            if(response.errors){
                var i = 0;
                $.each(response.errors,(key,value) => {
                    if(i == 0){
                        const mensagem = value.toString()
                        Swal.fire({
                            position: 'top-end',
                            title:'',
                            text:mensagem,
                            icon:'warning'
                        }).then((result) => {
                        });
                        i+=1;
                        return
                    }
                })
            }
        }
    })
})

$('body').on('click','.deletarNegocio',function(e){
    e.preventDefault()

    const href = $(this).attr('href')
    const id = $(this).attr('id')

    Swal.fire({
        title:'Deletar Negócio',
        icon:'question',
        text:'Esta ação inicialmente não o excluirá, você poderá recuperá-lo na aba excluídos!',
        showCancelButton: true,
    }).then((result) => {
       if(result.isConfirmed){
        preload()
        $.post({
            url: href,
            method:'DELETE',
            success:function(r){
                preload(false)
                if(r.result.status){
                    Swal.fire({
                        title:'',
                        text:r.result.texto,
                        icon:'success',
                        timer:1500,
                        position:'top-end'
                    }).then(response => {
                        if(response.isConfirmed || response.isDismissed){
                            $('#negocio_'+id).fadeOut('fast',function(){
                                $(this).remove()
                            })
                        }
                    })
                }
            }
        });
       }
    });
})

$('body').on('click','.modalNegociosExcluidos',function(e){
    e.preventDefault()
    $('.modal-title').text('Consultar negócios excluídos')
    $('.modal').attr('data-backdrop','static')
    $('.modal-dialog').addClass('modal-lg')

    const tableResponsive = $('<div>').attr({
        class:'table-responsive'
    })
    const table = $('<table>').attr({
        class:'table table-stripped table-hover tabelaNegociosExcluidos'
    })
    table.append(`
        <thead>
            <tr>
                <th></th>
                <th>Nome</th>
                <th>Data Exclusão</th>
                <th></th>
            </tr>
        </thead>

        <tbody></tbody>
    `)
    tableResponsive.html(table)

    $('.modal-body').html(tableResponsive)
    $('#ModalDialog').modal('show')

    $.get($(this).attr('href'),function(r){
        if(r.negocios){
            const body = $('.tabelaNegociosExcluidos tbody')
            body.empty()
            r.negocios.map(negocio => {
                body.append(`
                    <tr id="excluido_${negocio.id}">
                        <td><img src="${negocio.imagem}" width="50"></td>
                        <td>${negocio.nome}</td>
                        <td>${negocio.deleted_at}</td>
                        <td>
                            <a href="${negocio.linkExcluir}" id="${negocio.id}" class="btn btn-danger excluirNegocioPermanentemente" title="Excluir permanentemente"><i class="fa fa-trash"></i></a>
                            <a href="${negocio.linkRestaurar}" class="btn btn-success restaurarNegocio"><i class="fa fa-sync" title="Restaurar"></i></a>
                        </td>
                    </tr>
                `)
            })
        }
    })
});

$('body').on('click','.excluirNegocioPermanentemente',function(e){
    e.preventDefault()
    const link = $(this).attr('href')
    const id = $(this).attr('id')

    Swal.fire({
        title:'Deseja realmente excluir?',
        text:'Esta ação não poderá ser desfeita.',
        icon:'question',
        showCancelButton: true,
    }).then(result =>{
        if(result.isConfirmed){
            preload()
            $.get(link,function(r){
                preload(false)
                if(r.result){
                    Swal.fire({
                        title:'',
                        text:r.result.texto,
                        icon:'success',
                        timer:1500,
                        position:'top-end'
                    }).then(r => {
                        if(r.isConfirmed || r.isDismissed){
                            $('#excluido_'+id).fadeOut('slow',function(){
                                $(this).remove()
                            })
                        }
                    })
                }
            });
        }
    })
})

$('body').on('click','.restaurarNegocio',function(e){
    e.preventDefault()
    const link = $(this).attr('href')
    const id = $(this).attr('id')

    Swal.fire({
        title:'Restaurar este negócio?',
        text:'',
        icon:'question',
        showCancelButton: true,
    }).then(result =>{
        if(result.isConfirmed){
            preload()
            $.get(link,function(r){
                preload(false)
                if(r.result.status){
                    Swal.fire({
                        title:'',
                        text:r.result.texto,
                        icon:'success',
                        timer:1500,
                        position:'top-end'
                    }).then(r => {
                        window.location.reload()
                    })
                }else{
                    Swal.fire({
                        title:'',
                        text:r.result.texto,
                        icon:'warning',
                    })
                }
            });
        }
    })
})


$('body').on('click','.excluirPlano',function(e){
    e.preventDefault()

    const href = $(this).attr('href')
    const id = $(this).attr('id')

    Swal.fire({
        title:'Deletar Plano',
        icon:'question',
        text:'',
        showCancelButton: true,
    }).then((result) => {
       if(result.isConfirmed){
        preload()
        $.post({
            url: href,
            method:'DELETE',
            success:function(r){
                preload(false)
                if(r.result.status){
                    Swal.fire({
                        title:'',
                        text:r.result.texto,
                        icon:'success',
                        timer:1500,
                        position:'top-end'
                    }).then(response => {
                        if(response.isConfirmed || response.isDismissed){
                            $('#plano_'+id).fadeOut('fast',function(){
                                $(this).remove()
                            })
                        }
                    })
                }
            }
        });
       }
    });
})

$('body').on('submit','form[name="formPlanos"]',function(e){
    e.preventDefault()
    preload()
    $.post($(this).attr('action'),$(this).serialize(),function(r){
        preload(false)
        Swal.fire({
            title:'',
            text:r.result.texto,
            icon:(r.result.status ? 'success' : 'error'),
            timer:2000,
            position:'top-end'
        }).then(cl => {
            if(r.result.status){
                window.location.replace(r.result.link)
            }
        })
    })
})

$('body').on('click','.adicionarDescricaoPlano',function(){
    const itens = $('.item')
    var keyItem = 1
    itens.map(i => {
        keyItem += 1
    })

    $('.listaDescricao').append(`
        <div id="item_${keyItem}" class="row item">
            <div class="col-md-1">${keyItem}</div>

            <div class="col-md-10">
                <input type="text" class="form-control" name="item[${keyItem}][nome]" placeholder="Descrição">
            </div>

            <div class="col-md-1">
                <button type="button" id="${keyItem}" class="btn btn-sm btn-danger removerItemPlano"><i class="fa fa-trash" title="Remover Item"></i></button>
            </div>
        </div>
    `)
})

$("body").on('click','.removerItemPlano',function(){
    const id = $(this).attr('id')
    $('#item_'+id).remove()
})

$('body').on('submit','form[name="formTrial"]',function(e){
    e.preventDefault()
    $.post($(this).attr('action'),$(this).serialize(),function(r){
        preload()
        Swal.fire({
            title:'',
            text:r.result.texto,
            icon:(r.result.status ? 'success' : 'error'),
            timer:2500,
            position:'top-end'
        }).then(cl => {
            preload(false)
            if(r.result.status){
                window.location.replace(r.result.link)
            }
        })
    })
})
