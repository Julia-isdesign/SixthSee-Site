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
			"titulo" => NOME_COMPLETO,
			"descricao" => "Fornecemos soluções em elevação, com foco em segurança e produtividade.",
			"categorias" => "elevação, segurança, produtividade",
			"url" => URL_SITE,
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

		<?php
		# Carrossel
		require_once("includes/componentes/carrossel.php");
		?>

		<?php # Conteúdo principal ?>
		<main>
	
			<?php # SEO ?>
			<header class="a-hidden">
				<h1><?=$meta["titulo"]?></h1>
				<h2><?=$meta["descricao"]?></h2>
			</header>

			<div class="l-conteudo l-envelope l-envelope--m">
				<?php 
				# Categorias de produtos
				if ($x_categorias) {
					foreach($x_categorias as $categoria) {
						$home_categorias[] = [
							"url" => $categoria["url"],
							"titulo" => $categoria["nome"],
							"imagem" => $categoria["imagem"]
						];
					}
				}
				?>
				<section>
					<header class="l-bloco l-bloco--titulo">
						<h3 class="p-titulo | f-titulo f-titulo--h3">Categorias de produtos</h3>
					</header>
					<div class="l-bloco">
						<ul class="p-produtos">
							<?php foreach ($home_categorias as $item) { ?>
							<li>
								<a href="<?=$item["url"]?>" class="p-produtos__link | a-hover-opacity">
									<?php if ($item["imagem"]) { ?>
									<figure class="p-produtos__imagem">
										<img srcset="<?=URL_ARQUIVOS.$item["imagem"]."-g.webp"?> 1600w,
													 <?=URL_ARQUIVOS.$item["imagem"]."-m.webp"?> 1200w,
													 <?=URL_ARQUIVOS.$item["imagem"]."-p.webp"?> 800w,
													 <?=URL_ARQUIVOS.$item["imagem"]."-pp.webp"?> 400w"
											 sizes="(max-width:479px) 50vw,
											 	    (max-width:719px) 33vw
												    (max-width:939px) 25vw
												 	200px"
											 src="<?=URL_ARQUIVOS.$item["imagem"].".webp"?>"
											 alt="Veja todos os produtos da categoria <?=$item["titulo"]?>"
											 loading="lazy"
											 class="a-img a-img--contain"
										>
									</figure>
									<?php 
									} else { 
									?>
									<div class="p-produtos__imagem | a-capa a-capa--claro"></div>
									<?php } ?>
									<div class="p-produtos__conteudo">
										<h4 class="p-produtos__titulo | f-titulo"><?=$item["titulo"]?></h4>
									</div>
								</a>
							</li>
							<?php } ?>
						</ul>
					</div>
				</section>
			</div>

			
			<?php # Banner ?>
			<?php
			$home1 = consultar(
				"SELECT tituloConteudo1, resumoConteudo1, linkConteudo1, iconeBotaoConteudo1, textoBotaoConteudo1,
						(SELECT file
						   FROM arquivos
						  WHERE pai = 'home-imagem-conteudo-1') AS imagemConteudo1
				   FROM conteudo_home"
			);
			$home1 = $home1["dados"][0];
			
			if ($home1["tituloConteudo1"] || $home1["resumoConteudo1"]) {
				$secao = [
					"imagem" => $home1["imagemConteudo1"] ?: "placeholder",
					"titulo" => $home1["tituloConteudo1"],
					"texto" => $home1["resumoConteudo1"],
					"botao" => $home1["linkConteudo1"] ? true : false,
					"botao-url" => $home1["linkConteudo1"],
					"botao-target" => strpos($home1["linkConteudo1"], URL_SITE) === false ? "_blank" : "_self",
					"botao-icone" => $home1["iconeBotaoConteudo1"],
					"botao-texto" => $home1["textoBotaoConteudo1"],
				];
			}
			?>
			<section class="p-banner">
				<figure class="p-banner__imagem">
					<img srcset="<?=URL_ARQUIVOS.$secao["imagem"]."-g.webp"?> 1600w,
								 <?=URL_ARQUIVOS.$secao["imagem"]."-m.webp"?> 1200w,
								 <?=URL_ARQUIVOS.$secao["imagem"]."-p.webp"?> 800w,
								 <?=URL_ARQUIVOS.$secao["imagem"]."-pp.webp"?> 400w"
						 sizes="100vw"
						 src="<?=URL_ARQUIVOS.$secao["imagem"].".webp"?>"
						 alt="<?=$item["texto"]?>"
						 loading="lazy"
						 class="a-img a-img--cover"
					>
				</figure>
				<div class="p-banner__envelope | f-invertido | l-envelope l-envelope--m">
					<h4 class="f-titulo f-titulo--h3"><?=$secao["titulo"]?></h4>
					<?php if ($secao["texto"]) { ?>
					<div class="f-corpo"><?=$secao["texto"]?></div>
					<?php } ?>
					<a href="<?=$secao["botao-url"]?>" target="<?=$secao["botao-target"]?>" class="c-botao">
						<?php if ($secao["botao-icone"]) { ?>
						<i class="c-botao__icone"><?=$secao["botao-icone"]?></i>
						<?php } ?>
						<span class="c-botao__texto"><?=$secao["botao-texto"]?></span>
					</a>
				</div>

			</section>

		</main>


		<?php
		# Rodapé e aviso de privacidade
		require_once("includes/componentes/rodape.php");
		require_once("includes/componentes/privacidade.php");
		?>

	</body>
</html>