<?php
# Includes essenciais
require_once("_controle/config.php");
require_once("_controle/handlers/datas.php");
require_once("_controle/handlers/textos.php");


# Push essencial
$push = [
	"pagina" => "index"
];
require_once("includes/manipuladores/push.php");
?>
<!doctype html>
<html lang="pt-br">
	<head>

		<?php
		# Configurações e metatags
		$meta = [
			"titulo" => "Página não encontrada | ".NOME_COMPLETO,
			"descricao" => "Oops! A página que você procura não existe ou foi excluída.",
			"categorias" => "404, página não encontrada",
			"url" => URL_SITE."404",
			"imagem" => URL_SITE."img/core-social.png",
			"imagem-w" => "512",
			"imagem-h" => "384",
		];
		require_once("includes/componentes/head.php");


		# CSS
		$css = [
			"pagina" => "index"
		];
		require_once("includes/manipuladores/css.php")
		?>

	</head>


	<body>

		<?php 
		# Topo
		require_once("includes/componentes/topo.php");
		?>

		<?php # Conteúdo principal ?>
		<main>
	
			<?php # SEO ?>
			<header class="a-hidden">
				<h1><?=$meta["titulo"]?></h1>
				<h2><?=$meta["descricao"]?></h2>
			</header>

			<div class="l-conteudo l-envelope l-envelope--m">

				<header class="l-bloco l-bloco--titulo">
					<h3 class="f-titulo f-titulo--h1">Página não encontrada</h3>
				</header>

				<div class="l-bloco">
					<p class="f-corpo">Oops! A página que você procura não existe ou foi excluída.</p>
				</div>

			</div>


		</main>


		<?php
		# Rodapé e aviso de privacidade
		require_once("includes/componentes/rodape.php");
		require_once("includes/componentes/privacidade.php");
		?>

	</body>
</html>