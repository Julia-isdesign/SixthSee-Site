<?php
# Inclues essenciais
require_once("../_controle/config.php");
require_once("../_controle/handlers/textos.php");


# Captura dos parтmetros passados na requisiчуo
$pagina = (int)$_POST["pagina"];
$quantidade = (int)$_POST["quantidade"];

# Garantir que parтmetros foram enviados corretamente e sуo vсlidos
if (!$pagina) {
	$pagina = 1;
}
if (!$quantidade) {
	$quantidade = 12;
}

# Calcular o inэcio da consulta
$inicio = ($pagina - 1) * $quantidade;


# Captura dos campos do formulсrio
$categoria = (int)$_POST["categoria"];
$termos = antiInjection($_POST["termos"]);

# Definir condiчѕes de filtro e ordenaчуo
$order = " 1 = 1, p.titulo ASC ";
$where = " p.ativo = 1 ";

# Verificar se hс categorias
if ($categoria) {
	# Adicionar as categorias na condiчуo
	$where .= " AND p.id IN (SELECT idProduto
				   			   FROM produtos_categorias
				  			WHERE idCategoria = {$categoria}) ";
}

# Verificar se hс termos
if ($termos) {
	# Adicionar os termos na condiчуo
	$where .= " AND p.titulo LIKE '%$termos%' ";
}


# Consultar os produtos
$consulta = consultar(
	"SELECT SQL_CALC_FOUND_ROWS
			p.id, p.destaque, p.titulo, p.subtitulo, CONCAT('produto/', p.id, '/', url) AS url,
			(SELECT file
			   FROM arquivos
			  WHERE arquivos.pai = 'produtos-galeria'
				AND arquivos.idPai = p.id
			  ORDER BY arquivos.id ASC
			  LIMIT 1) AS imagem
	   FROM produtos p
	  WHERE $where
	  ORDER BY $order
	  LIMIT $inicio, $quantidade", 0
);

# Criar array de resultados
$resultados = $consulta["dados"];


# Descobrir o nњmero total de pсginas
$paginas = ceil($_SESSION[PAGINACAO]["FOUND_ROWS"] / $quantidade);


# Montar array de resposta
$resposta = [
	"resultados" => $resultados,
	"paginas" => $paginas,
];

# Converter o array para JSON
$resposta = arrayToJSON($resposta);

# Imprimir resposta
echo $resposta;
?>