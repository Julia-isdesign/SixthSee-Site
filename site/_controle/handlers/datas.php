<?php
/* Copyright © isDesign Estúdio de Criação Digital [2021] ** www.isdesign.com.br

 ** O PHP interpreta as datas da seguinte maneira:
 ** mm/dd/yyyy => padrão americano
 ** dd-mm-yyyy => padrão brasileiro e europeu
 ** yy-mm-dd   => padrão iso para datas

 */
date_default_timezone_set("Brazil/East");

/** Converter para strtotime */
function timestampData($data)
{
    // Trocar o / por -  a data br dd/mm/yyyy com a data inglesa mm/dd/yyyy
    $data = str_replace("/", "-", $data);
    return strtotime($data);
}

/**
 * Data para banco de dados
 *
 * @return string aaaa-mm-dd
 */
function dataPadraoBD($data)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $timeStamp = date("Y-m-d", $timeStamp);
    }
    return $timeStamp;
}

/**
 * Data e hora para banco de dados
 *
 * @return string aaaa-mm-dd hh:ii:ss
 */
function dataEhoraPadraoBD($data)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $timeStamp = date("Y-m-d H:i:s", $timeStamp);
    }
    return $timeStamp;
}

/**
 * Padrão Americano para Brasileiro
 *
 * @return string dd/mm | dd/mm/aaaa
 */
function dataPadraoBr($data, $resumido = false)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {

        if ($resumido) {
            $timeStamp = date("d/m", $timeStamp);
        } else {
            $timeStamp = date("d/m/Y", $timeStamp);
        }
    }
    return $timeStamp;

}

/** Padrão Americano para Brasileiro (2 digitos no ano) */
function dataPadraoBr2DigitosAno($data)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $timeStamp = date("d/m/y", $timeStamp);
    }
    return $timeStamp;
}

/**
 * Padrão Americano para Brasileiro (data e hora)
 *
 * @return string dd/mm/aaaa as hh:ii | dd/mm/aaaa
 */
function dataEhoraPadraoBr($data, $resumido = false)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        if (date("H:i", $timeStamp) != "00:00") {
            if ($resumido) $timeStamp = date("d/m à\s H:i", $timeStamp);
            else $timeStamp = date("d/m/Y à\s H:i", $timeStamp);
        } 
        
        else {
            if ($resumido) $timeStamp = date("d/m", $timeStamp);
            else $timeStamp = date("d/m/Y", $timeStamp);
        }
    }
    return $timeStamp;
}

/**
 * Padrão Americano para Brasileiro
 *
 * @return string hh:ii
 */
function horaPadraoBr($data)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $timeStamp = date("H:i", $timeStamp);
    }
    return $timeStamp;
}

/**
 * Padrão Americano para Brasileiro
 *
 * @return string dd
 */
function diaPadraoBr($data)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $timeStamp = date("d", $timeStamp);
    }
    return $timeStamp;
}

/**
 * Função que retorna o nome do mês, completo ou abreviado
 *
 * @return string Janeiro | resumido: JAN [...]
 */
function mesPadraoBr($data, $resumido = false)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $mesNumeral = date("m", $timeStamp);

        $mes = mesExtenso($mesNumeral);

        if ($resumido) {
            $mes = strtoupper(substr($mes, 0, 3));
        }

        return $mes;
    }
}

/**
 * Função que descobre o nome do mês a partir de um numero
 *
 * @return string Janeiro [...]
 */
function mesExtenso($mesNumeral)
{
    $mes = "";
    switch ($mesNumeral) {
        case 1:$mes = "Janeiro";
            break;
        case 2:$mes = "Fevereiro";
            break;
        case 3:$mes = "Março";
            break;
        case 4:$mes = "Abril";
            break;
        case 5:$mes = "Maio";
            break;
        case 6:$mes = "Junho";
            break;
        case 7:$mes = "Julho";
            break;
        case 8:$mes = "Agosto";
            break;
        case 9:$mes = "Setembro";
            break;
        case 10:$mes = "Outubro";
            break;
        case 11:$mes = "Novembro";
            break;
        case 12:$mes = "Dezembro";
            break;
    }
    return $mes;
}

/**
 * Função que retorna o ano a partir de uma data
 *
 * @return string aaaa
 */
function anoPadraoBr($data)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $timeStamp = date("Y", $timeStamp);
    }
    return $timeStamp;
}

/**
 * Qualquer padrão para URL
 *
 * @return string dd-mm-aaaa
 */
function dataPadraoURL($data)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $timeStamp = date("d-m-Y", $timeStamp);
    }
    return $timeStamp;
}

/**
 * Data brasileira completa por extenso
 *
 * @return string ?Segunda-Feira,? 26 de abril de 2021
 */
function dataPorExtenso($data, $mostrarDiaDaSemana = false)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {
        $dia       = date("d", $timeStamp);
        $mes       = date("m", $timeStamp);
        $ano       = date("Y", $timeStamp);
        $diasemana = '';

        if ($mostrarDiaDaSemana) {
            $naSemana = date("N", $timeStamp);
            // Semana
            switch ($naSemana) {
                case 1:$diasemana = "Segunda-Feira, ";
                    break;
                case 2:$diasemana = "Terça-Feira, ";
                    break;
                case 3:$diasemana = "Quarta-Feira, ";
                    break;
                case 4:$diasemana = "Quinta-Feira, ";
                    break;
                case 5:$diasemana = "Sexta-Feira, ";
                    break;
                case 6:$diasemana = "Sábado, ";
                    break;
                case 7:$diasemana = "Domingo, ";
                    break;
            }
        }
        // configuração mes
        $mes = strtolower(mesExtenso($mes));

        return $diasemana . $dia . " de " . $mes . " de " . $ano;
    }

}

/**
 * Data mais "humanizada", informando como "Ontem","Amanhã",etc
 *
 * @return string (Ontem|12 de (janeiro|Jan)) ?de 2021? ?às 10:40?
 */
function dataInformal($data, $mostraHora = false, $mostrarMesAbreviado = false)
{
    $timeStamp = timestampData($data);
    if ($timeStamp) {

        $dia  = date("d", $timeStamp);
        $mes  = date("m", $timeStamp);
        $ano  = date("Y", $timeStamp);
        $hora = date("H:i", $timeStamp);

        // Configuração mes
        $mesNome          = mesPadraoBr($data);
        $mesNomeAbreviado = ucfirst(mesPadraoBr($data, true));

        // hoje, ontem e sempre
        $hoje   = time();
        $ontem  = $hoje - (24 * 3600);
        $amanha = $hoje + (24 * 3600);

        if (substr($data, 0, 10) == date('Y-m-d', $hoje)) {
            $retorno = "Hoje";
        } else if (substr($data, 0, 10) == date('Y-m-d', $ontem)) {
            $retorno = "Ontem";
        } else if (substr($data, 0, 10) == date('Y-m-d', $amanha)) {
            $retorno = "Amanhã";
        } else {
            if ($mostrarMesAbreviado) {
                $retorno = "$dia de $mesNomeAbreviado";
            } else {
                $retorno = "$dia de $mesNome de $ano";
            }
        }
        // hora
        if ($mostraHora) {
            $retorno .= " às $hora";
        }

        return $retorno;
    }
}

/**
 * Transoforma o numero do dia no seu respectivo nome
 *
 * @param int $numero de 1 a 7, 1=segunda, pode ser utilizado date("N")
 * @return string dia por extenso: Segunda-Feira|Seg
 */
function diaDaSemanaFromNumero($numero, $resumido = false)
{
    if ($resumido) {
        $arrayDiasDaSemana = array("Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom");
    } else {
        $arrayDiasDaSemana = array("Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado", "Domingo");
    }

    $retorno = $arrayDiasDaSemana[$numero - 1];

    return $retorno;
}

/**
 * Calcula a idade a partir do nascimento
 *
 * @param string $nascimento pode ser em qualquer formado
 */
function calcularIdade($nascimento)
{
    $timeNascimento = timestampData($nascimento);
    $timeHoje       = time();
    $diferencaTime  = $timeHoje - $timeNascimento;
    $idade          = floor($diferencaTime / (60 * 60 * 24 * 365));
    return $idade;
}
