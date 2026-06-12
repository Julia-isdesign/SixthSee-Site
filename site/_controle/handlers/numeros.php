<?php
/* Copyright © isDesign Estъdio de Criaзгo Digital [2021] ** www.isdesign.com.br */

/**
 * Funзгo para converter moeda
 *
 * @param string $valor Ex: 9,99
 * @return float Ex: 9.99
 */
function moedaToFloat($valor)
{
    $trocarIsso = array('.', ',');
    $porIsso    = array('', '.');
    $valor      = (float) str_replace($trocarIsso, $porIsso, $valor);
    return $valor;
}

/**
 * Funзгo para converter float
 *
 * @param string|float $valor Ex: 9.99
 * @return string Ex: 9,99
 */
function floatToMoeda($valor)
{
    $valor = number_format($valor, 2, ',', '.');
    return $valor;
}

/** Funзгo para validar o CPF */
function validaCPF($cpf = null)
{

    if (!$cpf) {
        return false;
    }

    // Deixa somente os numeros
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequкncia de digitos repetidos. Ex: 111.111.111-11
    else if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

/** Funзгo para validar o CNPJ */
function validaCnpj($cnpj)
{
    // Deixa o CNPJ com apenas nъmeros
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    // Garante que o CNPJ й uma string
    $cnpj = (string) $cnpj;

    // o valor original
    $cnpj_original = $cnpj;

    // Captura os primeiros 12 nъmeros do CNPJ
    $primeiros_numeros_cnpj = substr($cnpj, 0, 12);

    /**
     * Multiplicaзгo do CNPJ
     *
     * @param string $cnpj Os digitos do CNPJ
     * @param int $posicoes A posiзгo que vai iniciar a regressгo
     * @return int O
     *
     */
    if (!function_exists('multiplica_cnpj')) {
        function multiplica_cnpj($cnpj, $posicao = 5)
        {
            // Variбvel para o cбlculo
            $calculo = 0;

            // Laзo para percorrer os item do cnpj
            for ($i = 0; $i < strlen($cnpj); $i++) {
                // Cбlculo mais posiзгo do CNPJ * a posiзгo
                $calculo = $calculo + ($cnpj[$i] * $posicao);

                // Decrementa a posiзгo a cada volta do laзo
                $posicao--;

                // Se a posiзгo for menor que 2, ela se torna 9
                if ($posicao < 2) {
                    $posicao = 9;
                }
            }
            // Retorna o cбlculo
            return $calculo;
        }
    }
    // Faz o primeiro cбlculo
    $primeiro_calculo = multiplica_cnpj($primeiros_numeros_cnpj);

    // Se o resto da divisгo entre o primeiro cбlculo e 11 for menor que 2, o primeiro
    // Dнgito й zero (0), caso contrбrio й 11 - o resto da divisгo entre o cбlculo e 11
    $primeiro_digito = ($primeiro_calculo % 11) < 2 ? 0 : 11 - ($primeiro_calculo % 11);

    // Concatena o primeiro dнgito nos 12 primeiros nъmeros do CNPJ
    // Agora temos 13 nъmeros aqui
    $primeiros_numeros_cnpj .= $primeiro_digito;

    // o segundo cбlculo й a mesma coisa do primeiro, porйm, comeзa na posiзгo 6
    $segundo_calculo = multiplica_cnpj($primeiros_numeros_cnpj, 6);
    $segundo_digito  = ($segundo_calculo % 11) < 2 ? 0 : 11 - ($segundo_calculo % 11);

    // Concatena o segundo dнgito ao CNPJ
    $cnpj = $primeiros_numeros_cnpj . $segundo_digito;

    // Verifica se o CNPJ gerado й idкntico ao enviado
    if ($cnpj === $cnpj_original) {
        return true;
    } else {
        return false;
    }
}
