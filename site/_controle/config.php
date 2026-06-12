<?php
/*-----------------------------------------------------**
**  Copyright © 2023 ~ isDesign ~ www.isdesign.com.br  **
**-----------------------------------------------------*/

// Sessão moda foca
@session_start();

// Charset e timeZone
@header ('Content-type: text/html; charset=ISO-8859-1', true);
date_default_timezone_set("Brazil/East");

// ======================================================================== //

// Permitir upload de arquivos maiores
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('max_input_time', 9000);
ini_set('max_execution_time', 9000);
ini_set('memory_limit', '256M');

// ======================================================================== //

$dominio = $_SERVER["HTTP_HOST"];

// Constantes
define("COR", "#fdff04");
define("VERSAO", "?v=2024-04-19");

define("NOME_SITE", "SEE Cranes");
define("NOME_COMPLETO", NOME_SITE. " | Soluções em elevação");

define("PASTA_DINAMICOS", "uploads/");
define("URL_LIMPA", "www.isdesign.com");
define("URL_BASE", "https://www.seecranes.ind.br/");
define("URL_ARQUIVOS", URL_BASE.PASTA_DINAMICOS);
define("URL_SITE", URL_BASE."site/");

define("URL_PAINEL_BASE", "https://www.isdesign.com.br/");
define("URL_PAINEL", URL_PAINEL_BASE."painel/");
define("PAGINACAO", "");

define("DIRETORIO_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
define("DIRETORIO_SITE", DIRETORIO_BASE."site/");
define("DIRETORIO_DINAMICOS", DIRETORIO_BASE.PASTA_DINAMICOS);

define("GOOGLE_MAPS_KEY", "AIzaSyD94Tv19VX42mktlEo1Ok_XruE7JDkf3cw");
define("COMPLEMENTO_ACESSOS", "");
define("SECRET_PASS", "");

// se alterar aqui, precisa alterar no Painel Padrão também.
define("DIMENSOES_IMAGENS", [
	"pp" => 400,
	"p" => 800,
	"m" => 1200,
    "g" => 1600
]);

define("IP_DESENVOLVEDOR", [
    "isDesign" => "187.84.51.154",
]);

/*************************************************************************************************
**                        Funções de conexão e execução no Banco de dados                       **
*************************************************************************************************/

function conectaBD($return = false) : mysqli{
	
	# Variaveis para conexão com o banco de dados e servidor
	$host = "mysql.seecranes.ind.br";
	$hostAlt = "mysql34-farm15.uni5.net";
	$user = "seecranes";
	$senha = "1re29m223AWruoMK34lsZ2A6Q".COMPLEMENTO_ACESSOS;
	$banco = "seecranes";

	//------------------------------------------------

	$conex = @mysqli_connect($host, $user, $senha, $banco);

	// check connection 1
	if (mysqli_connect_errno()) {

        if($hostAlt){

            // Tentar conectar no host alternativo
            $conex = @mysqli_connect($hostAlt, $user, $senha, $banco);
            if (mysqli_connect_errno()) {
                
                if(! $return){
                    printf("Não conseguiu conectar com o banco de dados. Erro: %s\n", mysqli_connect_error());
                    die();
                }
                
                else return false;
            }
        }

        if(! $return){
            printf("Não conseguiu conectar com o banco de dados. Erro: %s\n", mysqli_connect_error());
            die();
        }
        
        else return false;
	}

	mysqli_set_charset($conex, "latin1");
	
	return $conex;
}

/**
 * Funçao para consultas no MySQL
 * @param string $query		A string de consulta
 * @param int $echo			Deve printar a string
 * @param int $salvaLog		Deve salvar a string no log
 * @param int $bigSelects	É uma consulta com muitas linhas
 * @return array            [ status => 1|0, erro = string|false, rows => int, total => int, dados => array ]
**/

function consultar(string $query, int $echo = 0, int $bigSelects = 0) : array{
	
    $conexao = conectaBD();
    $resultado = [];

    if($bigSelects){
		mysqli_query($conexao, "SET SESSION SQL_BIG_SELECTS=1;");
		mysqli_query($conexao, "SET max_join_size=18446744073709551615;");
		mysqli_query($conexao, "SET SESSION group_concat_max_len = 1000000;");
    }
    
	$consulta = mysqli_query($conexao, $query);

    if($consulta === false){
        $resultado["erro"]   = mysqli_error($conexao);
        $resultado["rows"]   = 0;
        $resultado["status"] = 0;
    }
    
    else{
        $resultado["rows"] = mysqli_num_rows($consulta);
        $resultado["erro"] = false;
        $resultado["status"] = 1;
    }

	if($echo) {
		echo "<pre>".$query."</pre>";
		echo "<hr>MySQL: ".$resultado["erro"]."<hr>";
	}

    if ( strpos( $query, "SQL_CALC_FOUND_ROWS" ) !== false ) {
        unset($_SESSION[PAGINACAO]['FOUND_ROWS']);
		$FOUND_ROWS = mysqli_query($conexao, "SELECT FOUND_ROWS() as num;");
		$FOUND_ROWS = mysqli_fetch_assoc($FOUND_ROWS);
		$_SESSION[PAGINACAO]['FOUND_ROWS'] = $FOUND_ROWS['num'];
        $resultado["total"] = $FOUND_ROWS['num'];
    }

	mysqli_close($conexao);

    $resultado["dados"] = [];
    if($resultado["status"] == 1){
        while ($dados = mysqli_fetch_assoc($consulta)) {
            $resultado["dados"][] = $dados;
        }
    }

	return $resultado;
}

/**
 * Executa uma ação no banco, INSERT, UPDATE ou DELETE
 * @param string $query 		A query a ser executada
 * @param int $returnID			Deve retornar o ultimo id inserido? Valido somente para INSERT
 * @param int $affected_rows 	Deve retornar o número de linhas afetadas? Valido somente para UPDATE e DELETE
 * @param int $multiQuery       Deve ser executada várias querys de uma vez só
 * @return array                [ status => 1|0, erro = string|false, rows => int, id => int]
**/
function quickExec(string $query, int $returnId = 0, int $echo = 0, int $affected_rows = 0, $multiQuery = 0) : array{
	
	$conexao  = conectaBD();
    $retorno  = [];
	
    if($multiQuery){
		$execucao = mysqli_multi_query($conexao, $query);
    }

    else $execucao = mysqli_query($conexao, $query);

    if($execucao){
        $retorno["erro"] = false;
        $retorno["status"] = 1;
    }
    
    else{
        $retorno["erro"] = mysqli_error($conexao);
        $retorno["status"] = 0;
    }

	if($echo) {
		echo "<pre>$query</pre>";
		echo "<hr>MySQL: ".$retorno["erro"]."<hr>";
	}

	if($returnId){
		$retorno["id"] = mysqli_insert_id($conexao);
	}

    else if($affected_rows){
        $retorno["rows"] = mysqli_affected_rows($conexao);
    }

	mysqli_close($conexao);

	return $retorno;
}


/*************************************************************************************************
**                             Funções de proteção do banco de dados                            **
*************************************************************************************************/

/**
 * Elimina strings nocivas ao banco de dados
 * @param string|array $valor	Se infomado um array irá executar recursivamente
 * @return string
**/
function antiInjection($valor){

    if(is_array($valor)){
        return array_map("antiInjection", $valor);
    }

    else{
        
        $valor      = trim($valor);//limpa espaços vazio
        $trocarIsso	= array(";", "<?", "?>", "'", "‘", "’", '"', "“", "”", "from", "insert", "update", "where", "delete", "join", "delimeter", "like", "drop", "show tables", "truncate", "alter", "create", "table", "into", ".ini", "system", "passwd", "write", "script", "alert", "onload", "location", "select", "#", "\\", "<", ">", "=", "!", "\*", "/*");
        $porIsso	= array(",", "&lt;?", "?&gt;", "&apos;", "&apos;", "&quot;", "&quot;", "&apos;", "&apos;");
        $valor		= str_ireplace($trocarIsso, $porIsso, $valor); // Versão que não diferencia maiúsculas e minúsculas
        $valor	    = strip_tags($valor);//tira tags html e php
        $valor	    = addslashes($valor);//Adiciona barras invertidas a uma string

        return $valor;
    }
}

function dbEncript(string $string){
	return "AES_ENCRYPT('$string','".SECRET_PASS."')";
}

/**
 * Cria a string de decriptografia para o SQL 
 * @param string $campo
 * @param int|string $cast   Tipo de conversão, se recebe um tamanho, converte para aquele tamanho. As strings representam tipos especiais de dados e podem ser [DATE, DATETIME, TIME, DECIMAL, INT]
**/
function dbDecript(string $campo, $cast = 150){

    $base = "CAST(AES_DECRYPT($campo,'".SECRET_PASS."') AS";

    if($cast == "DATE") return "CAST($base CHAR(150)) AS DATE)"; 
    else if($cast == "DATETIME") return "CAST($base CHAR(150)) AS DATETIME)";
    else if($cast == "TIME") return "CAST($base CHAR(150)) AS TIME)";
    else if($cast == "DECIMAL") return "CAST($base CHAR(150)) AS DECIMAL)";
    else if($cast == "INT") return "CAST($base CHAR(150)) AS SIGNED)";

    else return "$base CHAR($cast))";

}


/**
 * Elimina strings nocivas ao banco de dados. Com excessões e algumas permissões
 * @param string $valor
 * @return string
**/
function antiInjectionSimples($valor){

    if(is_array($valor)){
        return array_map("antiInjectionSimples", $valor);
    }

    else{
        $valor 		= trim($valor);//limpa espaços vazio

        $trocarIsso	= array("‘", "’", '"', "“", "”", "from", "select", "insert", "update", "where", "delete", "join", "delimeter", "like", "drop", "show tables", "truncate", "alter", "create", "table", "into", ".ini", "system", "passwd", "write", "script", "alert", "onload", "location", "#", "--", "\\", "<", ">", "=", "!", "\*", "/*");

        $porIsso	= array('"', '"', '"', '"', '"', "<?", "?>");

        $valor	= str_ireplace($trocarIsso, $porIsso, $valor); // Versão que não diferencia maiúsculas e minúsculas
        $valor	= strip_tags($valor);//tira tags html e php
        $valor	= addslashes($valor);//Adiciona barras invertidas a uma string
        return $valor;
    }
}

/**
 * Função para aumentar a contagem de visualizações da página
 *
 * @param string $tabela tabela que esta a pagina
 * @param string $id id da pagina
 * @param string $campo campo da tabela que esta contabilizando as visualizacoes
 */
function acrescentarVisualizacao($tabela, $id, $campo = "vis"){
    quickExec("UPDATE $tabela SET $campo = $campo + 1 WHERE id = $id");
}

/**
 * Verifica se o IP do usuário é um dos IPs de desenvolvedor 
 * @return bool
**/
function isDesenvolvedor(){
    $ip = $_SERVER['REMOTE_ADDR'];
    return in_array($ip, array_values(IP_DESENVOLVEDOR));
}


/*************************************************************************************************
**                      Funções para identificação de navegador, so e afins                     **
*************************************************************************************************/

/**
 * Descobrir qual é o navegador do usuário 
 * @param bool $versaoResumida	Se verdadeiro trás todas as informações sobre a versão
**/
function qualNavegador($versaoResumida = false){
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $navegador = 'Desconhecido';
    $versao    = "";

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
        $navegador = 'Internet Explorer';
        $ub        = "MSIE";
    } elseif (preg_match('/Firefox/i', $userAgent)) {
        $navegador = 'Mozilla Firefox';
        $ub        = "Firefox";
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $navegador = 'Google Chrome';
        $ub        = "Chrome";
    } elseif (preg_match('/Safari/i', $userAgent)) {
        $navegador = 'Apple Safari';
        $ub        = "Safari";
    } elseif (preg_match('/Opera/i', $userAgent)) {
        $navegador = 'Opera';
        $ub        = "Opera";
    } elseif (preg_match('/Netscape/i', $userAgent)) {
        $navegador = 'Netscape';
        $ub        = "Netscape";
    }

    // finally get the correct version number
    $known   = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $userAgent, $matches)) {
        // we have no matching number just continue
    }

    // versão
    if (!$versaoResumida) {
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($userAgent, "Version") < strripos($userAgent, $ub)) {
                $versao = $matches['version'][0];
            } else {
                $versao = $matches['version'][1];
            }
        } else {
            $versao = $matches['version'][0];
        }
        // check if we have a number
        if ($versao == null || $versao == "") {
            $versao = " v: ?";
        } else {
            $versao = " v:" . $versao;
        }
    }

    return $navegador . $versao;
}


/**
 * Descobrir qual é o sistema operacional do usuário 
**/
function qualSistemaOperacional(){
    $userAgent          = $_SERVER['HTTP_USER_AGENT'];
    $sistemaOperacional = "Desconhecido";

    if (preg_match("/linux/i", $userAgent)) {
        $sistemaOperacional = "Linux";
    } elseif (preg_match("/macintosh|mac os x/i", $userAgent)) {
        $sistemaOperacional = "MacOS";
    } elseif (preg_match("/windows|win32/i", $userAgent)) {
        $sistemaOperacional = "Windows";
    }

    return $sistemaOperacional;
}

/** 
 * Função para descobrir navegador do usuario 
**/
function informacoesSistema(){
    $navegador          = qualNavegador();
    $sistemaOperacional = qualSistemaOperacional();

    return "Navegador: " . $navegador . ". SO: " . $sistemaOperacional;
}

/*************************************************************************************************
**                               Funções de manipulação de arrays                               **
*************************************************************************************************/

/** 
 * Faz um utf8_decode para todos os elementos de um array, mesmo tendo array dentro de array
 * @param array $array
 * @return array
**/
function utf8_decode_array(&$array){
	
	if(!function_exists("decode_items")){
        function decode_items(&$item, $key){
            $item = utf8_decode($item);
        }
    }
	
	array_walk_recursive($array, 'decode_items');

	return $array;
}

/** 
 * Faz um utf8_decode para todos os elementos de um array, mesmo tendo array dentro de array
 * @param array $array
 * @return array
**/
function utf8_encode_array(&$array){
	
    if(!function_exists("encode_items")){
        function encode_items(&$item, $key){
            $item = str_replace('"', "'", $item);
            $item = utf8_encode($item);
            
        }
    }
	
	array_walk_recursive($array, 'encode_items');

	return $array;
}

/** 
 * Recebe um array, converte tudo para UTF8 e retorna um JSON
 * @param array $array		O array que deve ser convertido
 * @param bool $scape_aspas	Deve escapar as aspas
 * @param int $options		Opções de conversão @see https://www.php.net/manual/pt_BR/json.constants.php
**/
function arrayToJSON($array, $scape_aspas = true, $option = JSON_PRESERVE_ZERO_FRACTION){
	if($array == null) return "[]";

	$json = json_encode(utf8_encode_array($array), $option );
    $json = str_replace("\\r", " ", $json);
    $json = str_replace("\\n", " ", $json);
    $json = str_replace("\\t", " ", $json);

	if($scape_aspas){
        $json = str_replace("'", "\'", $json);
	}
	
    return $json;
}

/**
 * Ordena um array
 * @param array $array			Array a ser ordenado
 * @param int $ordenacao 		Direção da ordenaçaõ [SORT_ASC ou SORT_DESC ]
 * @param bool $mantem_chave	Deve manter a associação das chaves?
**/
function array_sort($array, $on, $order = SORT_ASC, $mantem_chave = true){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
						$sortable_array[$k] = $v2;
					}
                }
            } else {
				$sortable_array[$k] = $v;

            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

       	foreach ($sortable_array as $k => $v) {
			if($mantem_chave){
				$new_array[$k] = $array[$k];
			}else{
				$new_array[] = $array[$k];
			}

        }
    }

    return $new_array;
}
/**
 * Um var_dump mas estilizado
**/
function var_dump_pre($mixed = null) {
    echo '<pre>';
    var_dump($mixed);
    echo '</pre>';
    return null;
}

/************************************************************
***                                                       ***
*** Alguns NINJAS fazem mais estrago que muitos soldados! ***
***                                                       ***
************************************************************/
?>