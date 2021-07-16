<?php


function dataParaBanco($data){
    return implode('-', array_reverse(explode('/', $data)));
}

function valorReal($valor){
    return number_format($valor,2,',','.');
}

function inteiroParaReal($valor){
    return number_format($valor,2,',','.');
}

function numeroInteiroReal($valor){
    return number_format($valor/100,2,',','.');
}

function valorPorcentagem($valor){
    return number_format($valor,2);
}

function valorBanco($valor){
    $valor = str_replace([',','.'], ['.',''], $valor);
    return number_format($valor/100,2,'.','');
}

function dias($dia){
    $d = array(
        '0' => 'Domingo',
        '1' => 'Segunda',
        '2' => 'Terça',
        '3' => 'Quarta',
        '4' => 'Quinta',
        '5' => 'Sexta',
        '6' => 'Sábado',
    );
    return $d[$dia];
}

function mes_extenso($mes){
    $meses = array(
        'Jan' => 'Janeiro',
        'Feb' => 'Fevereiro',
        'Mar' => 'Março',
        'Apr' => 'Abril',
        'May' => 'Maio',
        'Jun' => 'Junho',
        'Jul' => 'Julho',
        'Aug' => 'Agosto',
        'Nov' => 'Novembro',
        'Sep' => 'Setembro',
        'Oct' => 'Outubro',
        'Dec' => 'Dezembro'
    );

    return $meses[$mes];
}

function mes_numero_nome($mes){
    $meses = array(
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Novembro',
        '10' => 'Setembro',
        '11' => 'Outubro',
        '12' => 'Dezembro'
    );

    return $meses[$mes];
}
