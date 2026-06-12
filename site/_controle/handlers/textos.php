<?php
/* Copyright © isDesign Estúdio de Criação Digital [2021] ** www.isdesign.com.br */

/** Função que gera uma texto limpo pra virar URL */
function geraUrlLimpa($texto)
{
    // remove espaços em brancos no início e no fim.
    $texto = trim($texto);

    $trocarIsso      = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú', 'Ÿ', "&lt;?", "?&gt;", "&rsquo;", "&rsquo;", "&ldquo;", "&rdquo;", "&lsquo;", "&rsquo;");
    $porIsso         = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'Y', "", "", "", "", "", "", "", "");
    $textoSemAcentos = str_replace($trocarIsso, $porIsso, $texto);

    // Troca outros caracteres
    $replaces = array(
        '/( )/'             => '-',
        '/[^a-zA-Z0-9\-_]/' => '', //tirando outros caracteres invalidos
        '/_/' => '-',
        '/-+/'              => '-', // substitui múltiplos espaços (hifens) por apenas um
    );
    $textoLimpo = preg_replace(array_keys($replaces), array_values($replaces), $textoSemAcentos);
    return strtolower($textoLimpo);
}

function removeAcentos($texto){
    $trocarIsso      = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú', 'Ÿ', "&lt;?", "?&gt;", "&rsquo;", "&rsquo;", "&ldquo;", "&rdquo;", "&lsquo;", "&rsquo;");
    $porIsso         = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'Y', "", "", "", "", "", "", "", "");
    return str_replace($trocarIsso, $porIsso, $texto);
}


/** Remove caracteres como: < > ; , " & * = ? */
function textoLimpo($texto)
{
    $trocarIsso = array('<', '>', ';', ',', '"', '&', '*', '=', '?');
    $porIsso    = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');
    $textoLimpo = str_replace($trocarIsso, $porIsso, $texto);
    return trim(addslashes($textoLimpo));
}

/** Substitui as tags php e as aspas por seus equivalentes em html */
function aspasPHP($texto)
{
    $trocarIsso = array("<?", "?>", "\'", "\‘", "\’", '\"', "\“", "\”", "'", "‘", "’", '"', "“", "”", "%u2018", "%u2019", "%u201C", "%u201D", "%u2013", "\\\\");
    $porIsso    = array("&lt;?", "?&gt;", "&rsquo;", "&lsquo;", "&rsquo;", "&quot;", "&ldquo;", "&rdquo;", "&rsquo;", "&lsquo;", "&rsquo;", "&quot;", "&ldquo;", "&rdquo;", "&lsquo;", "&rsquo;", "&lsquo;", "&rsquo;", "-", "\\");
    $textoLimpo = str_replace($trocarIsso, $porIsso, $texto);


    //Adiciona barras invertidas a uma string
    $textoLimpo = addslashes($textoLimpo);

    return $textoLimpo;
}


/** Função para cortar texto sem cortar a palavra */
function quebraTexto($mensagem, $quantos)
{
    $texto = strip_tags($mensagem);
    $str   = substr($texto, $quantos, 1);

    if (strlen($texto) > $quantos) {
        if ($str != "" && $str != " " && $str != "," && $str != ".") {
            while ($str != "" && $str != " " && $str != "," && $str != ".") {
                $quantos++;
                $str = substr($texto, $quantos, 1);
            }
        }
        $str = substr($texto, 0, $quantos);
        return $str . "...";
    } else {
        return $texto;
    }
}

function maxLength($mensagem, $tamanho) {
	$texto = strip_tags($mensagem);
	
	if(strlen($texto) > $tamanho){
		return substr($texto, 0, $tamanho);
	} 
	else {
		return $texto;
	}
}

function geraDescription($opcao1, $opcao2=""){
	
	$texto = $opcao1 ? $opcao1 : quebraTexto($opcao2,140);
	
	$texto = trim(strip_tags(html_entity_decode($texto)));
	return $texto;
}


/* Função para gerar Keywords a partir de uma string */
function geraKeywords($string) {

	# Transformar todas as letras em minúsculas para aprimorar comparação
	$string = strtolower($string);

	# Listar termos de pouco valor semântico que devem ser substituídos
	$termos_irrelevantes = [
		" a ", " as ", " à ", " às ", " e ", " é ", " o ", " os ",
		" da ", " das ", " de ", " do ", " dos ",
		" na ", " nas ", " no ", " nos ",
		" para ", " pra ",
		" até ", " com ", " em ", " me ", " se ", " sem ", " um ", " uma ",
		"|", "/", "-", ",", ";", ".", "!", "?",
	];

	# Contar mesma quantidade de espaços vazios
	$espacos_vazios = [
		" ", " ", " ", " ", " ", " ", " ", " ",
		" ", " ", " ", " ", " ",
		" ", " ", " ", " ",
		" ", " ",
		" ", " ", " ", " ", " ", " ", " ", " ",
		" ", " ", " ", "", "", "", "", "",
	];

	# Substituir os termos irrelevantes por espaços vazios
	$string = str_replace($termos_irrelevantes, $espacos_vazios, $string);

	# Remover tags HTML ou símbolos PHP
	$string = strip_tags($string);

	# Substituir espaços por vírgulas
	$string = str_replace(" ", ", ", $string);


	# Quebrar a string em palavras separadas por vírgula
	$palavras = explode(", ", $string);

	# Remover palavras duplicadas
	$palavras = array_unique($palavras);

	# Remover espaços vazios (gerados pela remoção das palavras duplicadas)
	$palavras = array_filter($palavras, function ($valor) {
		return $valor !== "";
	});


	# Construir a nova string com as palavras únicas separadas por vírgula
	$string = implode(", ", $palavras);

	# Retornar a nova string
	return $string;

}


/** Função para gerar nova senha randômica */
function geraSenha($tamanho, $maiusculas, $numeros, $simbolos, $letras = 1){
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';
	
	if($letras){
		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
	}
	
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;
	
	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
	$rand = mt_rand(1, $len);
	$retorno .= $caracteres[$rand-1];
	}
	return $retorno;
}


/** Traduz XML para array */
function XML2Array($array)
{
    $newArray = array();
    $array    = (array) $array;
    foreach ($array as $key => $value) {
        $value           = (array) $value;
        @$newArray[$key] = $value[0];
    }
    $newArray = array_map("trim", $newArray);
    return $newArray;
}

/**
 * Ver se esse nome já existe
 *
 * @param string $nomeUrl nome desejado
 * @param string $tabela tabela onde quer verificar se já não há o mesmo nome
 * @param string $campo coluna que irá verificar o nome
 * @param int $idIgnorar id que a query irá descartar
 *
 * @return string nome valido. Ex: arquivo_2.php
 */
function nomeUrlValido($nomeUrl, $tabela, $campo = "url", $idIgnorar = 0)
{
    if(!$campo) {
		$campo="url";
	}
	// pesquisar no BD
    $where = "$campo = '$nomeUrl'";
    if ($idIgnorar) {
        $where .= " AND id<>$idIgnorar ";
    }

    $nomesUrl = consultar("SELECT id FROM $tabela WHERE $where");

    if (count($nomesUrl["dados"]) >= 1) {
        // ver se tem underline
        if (strstr($nomeUrl, '_') !== false) {
            $vetor_string = explode("_", $nomeUrl);
            $nomeUrl      = $vetor_string[0];
            $numero       = (int) $vetor_string[1];
        } else {
            $numero = 0;
        }
        // acrescentar 1 ao número
        $numero++;

        // definir o novo nome
        $nomeUrl = $nomeUrl . "_" . $numero;

        // ver se esse nome já existe
        return nomeUrlValido($nomeUrl, $tabela, $campo, $idIgnorar);
    } else {
        return $nomeUrl;
    }
}

/** Transforma texto com as iniciais em maiuscula
 *
 * @param string $texto CADE A vaCINa
 * @return string Cade a Vacina
 */
function iniciaisEmMaiusculas($texto)
{
    /**
     * mb_strtolower contempla acentos, strtolower não
     * Demo: http://sandbox.onlinephpfunctions.com/code/27afed770d30491c3fac152f606b25ed7de85615
     */
    $texto = ucwords(mb_strtolower(trim($texto)));

    // Deixar palavras isoladas com minusculas
    $trocarIsso = array(" A ", " E ", " O ", " Da ", " De ", " Do ", " Na ", " Em ", " No ", " Para ", " Pra ", " À ", " Até ", " Com ", " Sem ", " Se ", " Me ", " É ", " Um ", " Uma ");
    $porIsso    = array(" a ", " e ", " o ", " da ", " de ", " do ", " na ", " em ", " no ", " para ", " pra ", " à ", " até ", " com ", " sem ", " se ", " me ", " é ", " um ", " uma ");
    $texto      = str_replace($trocarIsso, $porIsso, $texto);

    return $texto;
}
