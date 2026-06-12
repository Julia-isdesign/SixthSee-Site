<?php
# Permitir que outros domínios acessem o arquivo
header("Access-Control-Allow-Origin: *");


# Carregar includes/módulos essenciais
require_once("config.php");
require_once("handlers/textos.php");
require_once("handlers/arquivos.php");


# Iniciar string do sitemap
$xml_str = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
	<url>
		<loc>'.URL_SITE.'</loc>
		<priority>1</priority>
		<changefreq>monthly</changefreq>
		<image:image>
			<image:loc>'.URL_SITE.'img/core-social.png</image:loc>
			<image:caption>'.utf8_encode(NOME_COMPLETO).'</image:caption>
		</image:image>
	</url>
	<url>
		<loc>'.URL_SITE.'produtos</loc>
		<priority>0.9</priority>
		<changefreq>monthly</changefreq>
		<image:image>
			<image:loc>'.URL_SITE.'img/core-social.png</image:loc>
			<image:caption>'.utf8_encode('Produtos').'</image:caption>
		</image:image>
	</url>
</urlset>';

# Iniciar elemento XML
$xml = new SimpleXMLElement($xml_str);


# Consultar categorias de produtos

$categorias = consultar(
	"SELECT c.id, c.nome,
		    (SELECT a.file 
			   FROM arquivos a 
			  WHERE a.pai = 'categorias-imagem'
			    AND a.idPai = c.id 
			  ORDER BY a.id DESC LIMIT 1) AS imagem
	   FROM categorias c
	  WHERE c.ativo = 1
		AND c.tipo = 'produtos'
	  ORDER BY c.nome ASC"
);
$categorias = $categorias["dados"];

# Iterar por cada resultado
foreach ($categorias as $categoria) {
	# Adicionar nova URL no XML
	$url = $xml->addChild("url");

	# Adicionar propriedades à URL
	$url->addChild("loc", URL_SITE."produtos/".$categoria["id"]."/".geraUrlLimpa($categoria["nome"]));
	$url->addChild("priority", "0.8");
	$url->addChild("changefreq", "monthly");

	if ($pagina["fileCompartilhamento"]) {
		$imagem = $url->addChild("image:image", "", "http://www.google.com/schemas/sitemap-image/1.1");
		$imagem->addChild("image:loc", URL_SITE."img/core-social.png", "http://www.google.com/schemas/sitemap-image/1.1");
		$imagem->addChild("image:caption", utf8_encode(htmlspecialchars($categoria["nome"], ENT_QUOTES, "ISO-8859-1")), "http://www.google.com/schemas/sitemap-image/1.1");
	}
}


# Consultar páginas institucionais
$paginas = consultar(
	"SELECT p.titulo, p.url,
			(SELECT file
			   FROM arquivos a
			  WHERE a.pai = 'pagina-cabecalho'
			    AND a.idPai = p.id
			  LIMIT 1) AS fileCabecalho,
			(SELECT file
			   FROM arquivos a
			  WHERE a.pai = 'pagina-compartilhamento'
			    AND a.idPai = p.id LIMIT 1) AS fileCompartilhamento    
	   FROM paginas p
	  WHERE p.ativo = 1"
);
$paginas = $paginas["dados"];

# Iterar por cada resultado
foreach ($paginas as $pagina) {
	# Adicionar nova URL no XML
	$url = $xml->addChild("url");

	# Adicionar propriedades à URL
	$url->addChild("loc", URL_SITE.$pagina["url"]);
	$url->addChild("priority", "0.8");
	$url->addChild("changefreq", "monthly");

	# Verificar existência de alguma imagem
	if (!$pagina["fileCompartilhamento"] && $pagina["fileCabecalho"]) {
		$pagina["fileCompartilhamento"] = $pagina["fileCabecalho"];
	}

	# Caso existe alguma imagem, adicionar propriedades à URL
	if ($pagina["fileCompartilhamento"]) {
		$imagem = $url->addChild("image:image", "", "http://www.google.com/schemas/sitemap-image/1.1");
		$imagem->addChild("image:loc", URL_ARQUIVOS.$pagina["fileCompartilhamento"]."-p.webp", "http://www.google.com/schemas/sitemap-image/1.1");
		$imagem->addChild("image:caption", utf8_encode(htmlspecialchars($pagina["titulo"], ENT_QUOTES, "ISO-8859-1")), "http://www.google.com/schemas/sitemap-image/1.1");
	}
}


# Consultar produtos
$produtos = consultar(
	"SELECT id, titulo, CONCAT('produto/', id, '/', url) AS url,
			(SELECT file
			   FROM arquivos
			  WHERE arquivos.idPai = produtos.id
				AND arquivos.pai = 'produtos-galeria'
			  ORDER BY arquivos.pos ASC, arquivos.id DESC
			  LIMIT 1) AS imagem
	   FROM produtos
	  WHERE ativo = 1
	  ORDER BY titulo ASC"
);
$produtos = $produtos["dados"];

# Iterar por cada resultado
foreach ($produtos as $produto) {
	# Adicionar nova URL no XML
	$url = $xml->addChild("url");

	# Adicionar propriedades à URL
	$url->addChild("loc", URL_SITE.$produto["url"]);
	$url->addChild("priority", "0.7");
	$url->addChild("changefreq", "monthly");

	# Caso existe alguma imagem, adicionar propriedades à URL
	if ($produto["imagem"]) {
		$imagem = $url->addChild("image:image", "", "http://www.google.com/schemas/sitemap-image/1.1");
		$imagem->addChild("image:loc", URL_ARQUIVOS.$produto["imagem"]."-p.webp", "http://www.google.com/schemas/sitemap-image/1.1");
		$imagem->addChild("image:caption", utf8_encode(htmlspecialchars($produto["titulo"], ENT_QUOTES, "ISO-8859-1")), "http://www.google.com/schemas/sitemap-image/1.1");
	}
}


# Criar arquivo .xml
$file = fopen($_SERVER["DOCUMENT_ROOT"]."/sitemap.xml", "w");

fwrite($file, $xml->asXML());
fclose($file);
?>