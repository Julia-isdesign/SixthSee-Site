<?php
# Includes essenciais
require_once("_controle/config.php");

# Funções para manipulação do conteúdo
require_once("_controle/handlers/arquivos.php");
require_once("_controle/handlers/datas.php");
require_once("_controle/handlers/numeros.php");
require_once("_controle/handlers/textos.php");


# Pegar endereço da página da URL
$url = antiInjection($_GET["url"]);

# Verificar origem
# Se estiver vindo do painel, possui uma condição especial de visualização
if (@strpos($_SERVER["HTTP_REFERER"], URL_PAINEL_BASE) === false) {
	$where = " AND p.ativo IN (1, 2) ";
}


# Consulta de todos os blocos da página
$blocos = consultar(
	"SELECT
		b.*,
		p.titulo AS pai_titulo, p.subtitulo AS pai_subtitulo, p.icone AS pai_icone,
		p.metaTitle AS meta_title, p.metaKeywords AS meta_keywords, p.metaDescription AS meta_description,
		p.id AS pai_id, p.url AS pai_url, p.formulario AS pai_form, p.destino AS pai_form_destino
	FROM paginas p
		LEFT JOIN blocos b ON b.idPai = p.id AND b.pai = 'paginas'
	WHERE p.url = '$url' $where ORDER BY b.pos ASC"
);

# Se a consulta falhar, volta para a página inicial
if (count($blocos["dados"]) == 0) {
	header("location: ".URL_SITE);
	exit;
}

# Facilitar o acesso aos resultados
$blocos = $blocos["dados"];


# Reunir as principais informações da página
# Essas informações também aparecem nos blocos, mas assim mantemos organizado
$pagina = [
	"id" => $blocos[0]["pai_id"],
	"url" => $blocos[0]["pai_url"],
	"icone" => $blocos[0]["pai_icone"],
	"titulo" => $blocos[0]["pai_titulo"],
	"subtitulo" => $blocos[0]["pai_subtitulo"],
	"galeria" => true,
	"fancybox" => false,
	"form" => $blocos[0]["pai_form"],
	"form_destino" => $blocos[0]["pai_form_destino"],
	"meta_title" => $blocos[0]["meta_title"],
	"meta_description" => $blocos[0]["meta_description"],
	"meta_keywords" => $blocos[0]["meta_keywords"],
];

# Adicionar uma nova visualização à página no painel
acrescentarVisualizacao("paginas", $pagina["id"], "vis");


# Consultar os arquivos relacionados à página
$arquivos = consultar(
	"SELECT
		ar.file AS cabecalho, ar.proporcao AS cabecalho_proporcao,
		ar2.file AS compartilhamento, ar2.proporcao AS compartilhamento_proporcao
	FROM paginas p
		LEFT JOIN arquivos ar ON ar.idPai = p.id AND ar.pai = 'pagina-cabecalho'
		LEFT JOIN arquivos ar2 ON ar2.idPai = p.id AND ar2.pai = 'pagina-compartilhamento'
	WHERE p.url = '$url' $where"
);

# Facilitar o acesso aos resultados
$arquivos = $arquivos["dados"][0];

# Se não houver imagem de compartilhamento, mas houver imagem de cabeçalho
# Definir a imagem de cabeçalho como imagem de compartilhamento, para utilizar nas meta tags
if (!$arquivos["compartilhamento"] && $arquivos["cabecalho"]) {
	$arquivos["compartilhamento"] = $arquivos["cabecalho"];
	$arquivos["compartilhamento_proporcao"] = $arquivos["cabecalho_proporcao"];
}


# Configurar as meta tags da página
# Se não houver configurações no BD, utilizar os dados disponíveis no cadastro
$meta = [
	"titulo" => ($pagina["meta_title"] ?: $pagina["titulo"])." | ".NOME_SITE,
	"descricao" => $pagina["meta_description"] ?: $pagina["subtitulo"],
	"categorias" => $pagina["meta_keywords"] ?: geraKeywords($pagina["titulo"]),
	"url" => URL_SITE.$pagina["url"],
];

# Garantir que há descrição
# Se ainda não houver, criar descrição genérica com título
if (!$meta["descricao"]) {
	$meta["descricao"] = "Confira todos os detalhes de ".$meta["titulo"];
}

# Verificar se há imagem de compartilhamento
# Se não houver nada cadastrado, utilizar a imagem padrão
if ($arquivos["compartilhamento"]) {
	$meta["imagem"] = URL_ARQUIVOS.$arquivos["compartilhamento"]."-pp.webp";
	$meta["imagem-w"] = 400;
	$meta["imagem-h"] = round(400 / $arquivos["compartilhamento_proporcao"]);
}
else {
	$meta["imagem"] = URL_SITE."img/core-social.png";
	$meta["imagem-w"] = "512";
	$meta["imagem-h"] = "384";
}


# Verificar se o Fancybox é necessário
# O plugin deve ser chamado se houver alguma galeria de fotos ($bloco tipo galeria)
# Ou se houver um bloco de texto que contenha imagem ($bloco tipo texto com ícone)
foreach ($blocos as $bloco) {
	if ($bloco["tipo"] == "galeria" || ($bloco["tipo"] == "texto" && $bloco["icone"])) {
		$pagina["fancybox"] = true;
		break;
	}
}


# Definir quais recursos serão carregados antecipadamente através do server push
$push = [
	"fancybox" => $pagina["fancybox"],
];

require_once("includes/manipuladores/push.php");
?>
<!doctype html>
<html lang="pt-br">
	<head>

		<?php
		# Head (SEO) e CSSs
		require_once("includes/componentes/head.php");

		$css = [
			"fancybox" => $pagina["fancybox"],
		];
		require_once("includes/manipuladores/css.php");
		?>
	</head>


	<body>

		<?php # Topo ?>
		<?php require_once("includes/componentes/topo.php"); ?>


		<?php # Principal ?>
		<main role="main">
			<?php # Cabeçalho ?>
			<?php
			$cabecalho = [
				"classe" => "",
				"icone" => $pagina["icone"],
				"titulo" => $pagina["titulo"],
				"subtitulo" => $pagina["subtitulo"],
				"imagem" => $arquivos["cabecalho"],
			];

			require_once("includes/componentes/cabecalho.php");
			?>

			<?php # Blocos ?>
			<?php if ($blocos[0]["tipo"]) { ?>
			<section class="l-conteudo l-envelope l-envelope--m">
				<?php require_once("includes/componentes/blocos.php"); ?>
				<script async src="js/efeitos/youtube.min.js<?=VERSAO?>"></script>
			</section>
			<?php } ?>


			<?php # Formulários ?>
			<?php
			if ($pagina["form"]) { ?>
			<section class="l-form">
				<div class="l-conteudo l-envelope l-envelope--m">
					<?php require_once("includes/componentes/formulario.php");?>
				</div>
			</section>
			<?php } ?>

		</main>


		<?php
		# Rodapé e aviso de privacidade
		require_once("includes/componentes/rodape.php");
		require_once("includes/componentes/privacidade.php");
		?>


		<?php
		# Scripts
		$scripts = [
			"jquery" => $pagina["fancybox"] || $pagina["form"],
			"masker" => $pagina["form"],
			"fancybox" => $pagina["fancybox"],
			"form" => $pagina["form"],
			"pagina" => $pagina["form"] ? "textos" : "",
		];
		require_once("includes/manipuladores/scripts.php");
		?>
		<script src="js/paginas/textos.min.js<?=VERSAO?>"></script>

	</body>
</html>