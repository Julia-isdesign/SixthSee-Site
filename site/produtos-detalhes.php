<?php
# Includes essenciais
require_once("_controle/config.php");

# Funções para manipulação do conteúdo
require_once("_controle/handlers/arquivos.php");
require_once("_controle/handlers/datas.php");
require_once("_controle/handlers/numeros.php");
require_once("_controle/handlers/textos.php");

# Pegar ID do produto na URL
$id = (int)$_GET["id"];

# Verificar origem
# Se estiver vindo do painel, possui uma condição especial de visualização
if (@strpos($_SERVER["HTTP_REFERER"], URL_PAINEL_BASE) === false) {
	$where = " AND ativo IN (1, 2) ";
}

# Consulta de todos os blocos da página
$consulta = consultar(
	"SELECT id, url, titulo, subtitulo, referencia, preco,
			urlVideo AS video_url, legendaVideo AS video_legenda,
			(SELECT GROUP_CONCAT(nome SEPARATOR ', ')
			   FROM categorias
			  WHERE categorias.id IN
					(SELECT idCategoria
					   FROM produtos_categorias
					  WHERE produtos_categorias.idProduto = produtos.id)) AS categorias,
			(SELECT GROUP_CONCAT(idCategoria SEPARATOR ',')
			   FROM produtos_categorias
			  WHERE produtos_categorias.idProduto = produtos.id) AS ids_categorias
	   FROM produtos
	  WHERE id = $id $where"
);

# Se a consulta falhar, volta para a página inicial
if (!$consulta["dados"]) {
	header("location: ".URL_SITE);
	exit;
}

# Facilitar o acesso aos resultados
$pagina = $consulta["dados"][0];


# Consultar imagens para $meta e $galeria
$imagens = consultar(
	"SELECT file AS url, legenda, proporcao
	   FROM arquivos
	  WHERE pai = 'produtos-galeria'
		AND idPai = $id
	  ORDER BY pos ASC, id DESC"
);

# Facilitar o acesso aos resultados
$imagens = $imagens["dados"];


# Verificar se há a necessidade de Fancybox
$pagina["fancybox"] = (count($imagens) > 0) ? true : false;


# Push essencial
$push = [
	"fancybox" => $pagina["fancybox"],
	"pagina" => "produtos-detalhes"
];
require_once("includes/manipuladores/push.php");
?>
<!doctype html>
<html lang="pt-br">
	<head>

	<?php
		# Configurações e metatags
		$meta = [
			"titulo" => $pagina["titulo"]." | ".NOME_SITE,
			"descricao" => $pagina["subtitulo"] ?: ("Veja detalhes do produto ".$pagina["titulo"]),
			"categorias" => geraKeywords($pagina["titulo"]).", ".$pagina["categorias"],
			"url" => URL_SITE."produto/".$pagina["id"]."/".$pagina["url"],
		];

		# Se houver imagens cadastradas, utilizar a primeira como $meta
		if ($imagens) {
			$meta["imagem"] = URL_ARQUIVOS.$imagens[0]["url"]."-p.webp";
			$meta["imagem-w"] = "800";
			$meta["imagem-h"] = (int)(800 / $imagens[0]["proporcao"]);
		}
		else {
			$meta["imagem"] = URL_SITE."img/core-social.png";
			$meta["imagem-w"] = "512";
			$meta["imagem-h"] = "384";
		}

		require_once("includes/componentes/head.php");


		# CSS
		$css = [
			"fancybox" => $pagina["fancybox"],
			"pagina" => "produtos-detalhes"
		];
		require_once("includes/manipuladores/css.php");

		
		# CSS
		if ($x_categorias) {
			foreach($x_categorias as $categoria) {
				if ($categoria["nome"] == $pagina["categorias"]) {
					$categoria_url = "https://www.seecranes.ind.br/site/produtos?categoria=".$categoria["id"];
				} 
			}
		}
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

			<div class="p-estrutura | l-envelope l-envelope--m">

				<?php # Galeria de fotos ?>
				<section class="p-estrutura__galeria">
					<h3 class="a-hidden">Galeria de fotos</h3>
					<div class="p-galeria">
						<?php # Imagens ?>
						<?php
						if ($imagens) {
							$imagens_ativo = true;
							$imagens_loading = "";

							foreach ($imagens as $imagem) {
								?>
								<a href="<?=URL_ARQUIVOS.$imagem["url"].".webp"?>" class="p-galeria__link | a-hover-opacity | js-fancybox js-imagem <?=$imagens_ativo ? "| ativo" : ""?>" data-fancybox="galeria" data-caption="<?=$imagem["legenda"] ?: $pagina["titulo"]?>" aria-label="<?=$imagem["legenda"] ?: $pagina["titulo"]?>">
									<figure class="p-galeria__imagem">
										<img srcset="<?=URL_ARQUIVOS.$imagem["url"]."-g.webp"?> 1600w,
													<?=URL_ARQUIVOS.$imagem["url"]."-m.webp"?> 1200w,
													<?=URL_ARQUIVOS.$imagem["url"]."-p.webp"?> 800w,
													<?=URL_ARQUIVOS.$imagem["url"]."-pp.webp"?> 400w"
											sizes="(max-width:959px) 100vw,
													(max-width:1019px) 50vw
													390px"
											src="<?=URL_ARQUIVOS.$imagem["url"].".webp"?>"
											alt="<?=$imagem["legenda"] ?: $pagina["titulo"]?>"
											<?=$imagens_loading?>
											class="a-img a-img--contain"
										>
									</figure>
								</a>
								<?php
								if ($imagens_ativo) {
									$imagens_ativo = false;
									$imagens_loading = 'loading="lazy"';
								}
							}
						}
						else {
						?>
						<div class="p-galeria__imagem | a-capa a-capa--claro"></div>
						<?php }?>

						<?php # Lista ?>
						<?php if (count($imagens) > 1) { ?>
						<ul class="p-galeria__lista">
							<?php foreach ($imagens as $i => $imagem) { ?>
							<li>
								<button type="button" class="p-galeria__botao | a-hover-opacity | js-galeria <?=$i == 0 ? "| ativo" : ""?>" data-foto="<?=$i?>" aria-label="<?=$imagem["legenda"] ?: $pagina["titulo"]?>">
									<img src="<?=URL_ARQUIVOS.$imagem["url"]."-p.webp"?>" alt="<?=$imagem["legenda"] ?: $pagina["titulo"]?>" class="a-img a-img--cover">
								</button>
							</li>
							<?php } ?>
						</ul>
						<?php } ?>
					</div>
				</section>

				<div class="p-estrutura__info">

					<?php # Cabeçalho ?>
					<header class="p-cabecalho | l-bloco">
						<div class="p-cabecalho__migalhas <?=$pagina["referencia"] ? "p-cabecalho__migalhas--ref" : ""?>">
							<a href="https://www.seecranes.ind.br/site/produtos"  class="p-cabecalho__migalhas__item p-cabecalho__migalhas__item--link">Produtos</a>
							<?php if ($pagina["categorias"]) { ?>
							<span class="p-cabecalho__migalhas__item"> / </span>
							<a href="<?=$categoria_url?>"  class="p-cabecalho__migalhas__item p-cabecalho__migalhas__item--link"><?=$pagina["categorias"]?></a>
							<?php
							}
							if ($pagina["referencia"]) { 
							?>
							<span class="p-cabecalho__migalhas__item"> / </span>
							<span class="p-cabecalho__migalhas__item--ref">Referência #<?=$pagina["referencia"]?></span>
							<?php } ?>
						</div>

						<?php # Título e subtítulo ?>
						<?php if ($pagina["titulo"] || $pagina["subtitulo"]) { ?>
						<div>
							<?php if ($pagina["titulo"]) { ?>
							<h1 class="p-cabecalho__titulo | f-titulo f-titulo--h3"><?=$pagina["titulo"]?></h1>
							<?php } ?>
							<?php if ($pagina["subtitulo"]) { ?>
							<h2 class="p-cabecalho__marca"><?=$pagina["subtitulo"]?></h2>
							<?php } ?>
						</div>
						<?php } ?>

						<?php
						# Preço / Consulte
						if ($pagina["preco"] != "0.00") {
							$pagina["preco"] = "R$ ".floatToMoeda($pagina["preco"]);
						}
						else {
							$pagina["preco"] = false;
						}

						if ($pagina["preco"]) {
						?>
						<div class="p-cabecalho__preco | c-botao">
							<span class="c-botao__texto"><?=$pagina["preco"]?></span>
						</div>
						<?php } else { ?>
						<a href="contato" class="c-botao">
							<span class="c-botao__texto">Consulte</span>
						</a>
						<?php } ?>
					</header>


					<?php
					# Parágrafos / textos

					# Consultar título e texto por ordem de posição ou ID
					$paragrafos = consultar(
						"SELECT titulo, texto
						FROM produtos_textos
						WHERE idProduto = $id
						ORDER BY pos ASC, id ASC"
					);

					# Facilitar acesso aos dados
					$paragrafos = $paragrafos["dados"];

					# Verificar se há resultados
					if ($paragrafos) {
						foreach ($paragrafos as $paragrafo) {
					?>
					<section class="l-bloco">
						<?php if ($paragrafo["titulo"]) { ?>
						<header class="l-bloco l-bloco--titulo">
							<h3 class="f-titulo f-titulo--h4"><?=$paragrafo["titulo"]?></h3>
						</header>
						<?php } ?>
						<?php if ($paragrafo["texto"]) { ?>
						<div class="l-bloco | f-wysiwyg"><?=$paragrafo["texto"]?></div>
						<?php } ?>
					</section>
					<?php } } ?>


					<?php
					# Informações técnicas

					# Consultar coluna 1 e 2 por ordem de posição ou ID
					$infos = consultar(
						"SELECT coluna1, coluna2
						FROM produtos_informacoes
						WHERE idProduto = $id
						ORDER BY pos ASC, id ASC"
					);

					# Facilitar acesso aos dados
					$infos = $infos["dados"];

					# Verificar se há resultados
					if ($infos) {
					?>
					<section class="l-bloco">
						<header class="l-bloco l-bloco--titulo">
							<h3 class="f-titulo f-titulo--h4">Informações técnicas</h3>
						</header>
						<div class="l-bloco">
							<div class="p-tabela">
								<table>
									<tbody>
										<?php foreach ($infos as $info) { ?>
										<tr>
											<td><?=$info["coluna1"]?></td>
											<td><?=$info["coluna2"]?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</section>
					<?php } ?>


					<?php
					# Vídeo do YouTube
					if ($pagina["video_url"]) {
					?>
					<section class="l-bloco">
						<header class="l-bloco l-bloco--titulo">
							<h4 class="f-titulo f-titulo--h4"><?=$pagina["video_legenda"]?></h4>
						</header>
						<div class="l-bloco">
							<div class="b-midia">
								<div class="b-midia__proporcao">
									<button type="button"
											class="b-midia__youtube | js-youtube | a-img a-img--cover"
											data-youtube="<?=urlDoVideo($pagina["video_url"], "embed")?>"
											aria-label="Reproduzir vídeo do YouTube">
										<svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet"
											 class="b-midia__youtube__logo" aria-hidden="true">
											<use xlink:href="img/logo-plataformas.svg#youtube-white"></use>
										</svg>
										<img src="<?=urlDoVideo($pagina["video_url"], "imagem")?>"
											 alt=""
											 loading="lazy"
											 class="b-midia__youtube__cover">
									</button>
								</div>
							</div>
						</div>
					</section>
					<script async src="js/efeitos/youtube.min.js"></script>
					<?php } ?>
					
				</div>

			</div>

		</main>


		<?php
		# Rodapé e aviso de privacidade
		require_once("includes/componentes/rodape.php");
		require_once("includes/componentes/privacidade.php");
		?>


		<?php # Scripts ?>
		<?php
		$scripts["fancybox"] = true;
		require_once("includes/manipuladores/scripts.php");
		?>

		<script async src="js/paginas/produtos-detalhes.min.js<?=VERSAO?>"></script>

	</body>

</html>