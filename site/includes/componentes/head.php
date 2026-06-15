<?php
/**
 * Componente head
 * @param array $meta [ titulo, descricao, categorias, url, imagem, imagem-w, imagem-h ]
**/

# Título e URL são parâmetros obrigatórios no Array $meta
# Sem eles, não é possível preencher corretamente as informações necessárias
# Embora pouco informativo, os demais elementos podem ser preenchidos com dados genéricos

# Se não houver descrição, criar descrição genérica a partir do título
if (!$meta["descricao"]) {
	$meta["descricao"] = "Confira todos os detalhes de ".$meta["titulo"];
}

# Se não houver categorias, criar categorias genéricas a partir do título
if (!$meta["categorias"]) {
	require_once("_controle/handlers/textos.php");
	$meta["categorias"] = geraKeywords($meta["titulo"]);
}

# Se não houver imagem, utilizar imagem genérica de compartilhamento
if (!$meta["imagem"]) {
	$meta["imagem"] = URL_SITE."img/core-social.png";
	$meta["imagem-w"] = "512";
	$meta["imagem-h"] = "384";
}
?>

<?php # Configurações ?>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
<base href="<?=URL_SITE?>">

<?php # SEO ?>
<title><?=$meta["titulo"]?></title>
<meta name="description" content="<?=$meta["descricao"]?>">
<meta name="category" content="<?=$meta["categorias"]?>">
<meta name="url" content="<?=$meta["url"]?>">

<link href="<?=$meta["url"]?>" rel="canonical">
<link href="img/core-favoritos.png<?=VERSAO?>" rel="icon">
<link href="img/core-smartphones.png<?=VERSAO?>" rel="apple-touch-icon">

<meta name="application-name" content="<?=NOME_SITE?>">
<meta name="author" content="isDesign Softwares">
<meta name="subject" content="Fornecemos soluções em elevação, com foco em segurança e produtividade">
<meta name="topic" content="Fornecemos soluções em elevação, com foco em segurança e produtividade"> 
<meta name="abstract" content="Site oficial da <?=NOME_SITE?>, RS">
<meta name="summary" content="Site oficial da <?=NOME_SITE?>, RS">

<meta name="coverage" content="Worldwide">
<meta name="directory" content="submission">
<meta name="rating" content="General">
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">

<?php # Geo ?>
<meta name="ICBM" content="-29.2765218,-51.5267358">
<meta name="geo.position" content="-29.2765218,-51.5267358">
<meta name="geo.placename" content="Carlos Barbosa">
<meta name="geo.region" content="BR-RS">

<?php # Facebook ?>
<meta property="og:title" content="<?=$meta["titulo"]?>">
<meta property="og:description" content="<?=$meta["descricao"]?>">
<meta property="og:url" content="<?=$meta["url"]?>">
<meta property="og:image" content="<?=$meta["imagem"].VERSAO?>">
<meta property="og:image:width" content="<?=$meta["imagem-w"]?>">
<meta property="og:image:height" content="<?=$meta["imagem-h"]?>">
<meta property="og:site_name" content="<?=NOME_COMPLETO?>">
<meta property="og:locale" content="pt_BR">
<meta property="og:type" content="website">

<?php # Twitter ?>
<meta name="twitter:title" content="<?=$meta["titulo"]?>">
<meta name="twitter:description" content="<?=$meta["descricao"]?>">
<meta name="twitter:url" content="<?=$meta["url"]?>">
<meta name="twitter:image" content="<?=$meta["imagem"].VERSAO?>">
<meta name="twitter:card" content="summary">

<?php # iOS ?>
<meta name="apple-mobile-web-app-capable" content="no">
<meta name="apple-mobile-web-app-title" content="<?=NOME_COMPLETO?>">

<?php # Android ?>
<meta name="mobile-web-app-capable" content="no">
<meta name="theme-color" content="#fdff04">

<?php # CSS ?>
<link rel="preload" href="fonts/Material-Icons-Round.woff2<?=VERSAO?>" as="font">
<!--[if lt IE 9]><script src="js/bibliotecas/html-5-shiv.min.js<?=VERSAO?>"></script><![endif]-->

<?php
# Consultar as categorias de produtos
$x_categorias = consultar(
	"SELECT c.id, c.nome,
		    (SELECT a.file 
			   FROM arquivos a 
			  WHERE a.pai = 'categorias-imagem'
			    AND a.idPai = c.id 
			  ORDER BY a.id DESC LIMIT 1) AS imagem
	   FROM categorias c
	  WHERE c.ativo = 1
		AND c.tipo = 'produtos'
	  ORDER BY c.nome ASC", 0
);
$x_categorias = $x_categorias["dados"];

foreach ($x_categorias as &$categoria) {
	$categoria["url"] = URL_SITE."produtos/".$categoria["id"]."/".geraUrlLimpa($categoria["nome"]);
}

// Remove a referencia //
unset($categoria);
?>
<script>
	const JS_URL_BASE = "<?= URL_BASE ?>",
		JS_URL_SITE = "<?= URL_SITE ?>";
</script>