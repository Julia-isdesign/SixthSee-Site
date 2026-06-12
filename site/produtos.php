<?php
# Includes essenciais
require_once("_controle/config.php");
require_once("_controle/handlers/textos.php");


# Capturar valores dos parâmetros na URL
$valor_categoria = (int)$_GET["categoria"];

if ($valor_categoria) {
	$canonical = consultar(
		"SELECT c.id, c.nome
		   FROM categorias c
		  WHERE c.ativo = 1
			    AND c.id = $valor_categoria
			    AND c.tipo = 'produtos'
	   ORDER BY c.nome ASC"
	);
	$canonical = $canonical["dados"][0];

	if ($canonical) {
		$canonical = URL_SITE."produtos/".$canonical["id"]."/".geraUrlLimpa($canonical["nome"]);
	} else {
		header("Location: ".URL_SITE."produtos");
	}
}

# Push essencial
$push = [
	"pagina" => "produtos"
];
require_once("includes/manipuladores/push.php");



# Verificar se é uma pesquisa para apresentar no mobile
$mobile_ativo = $_GET["pesquisa"];
?>
<!doctype html>
<html lang="pt-br">
	<head>

		<?php
		# Configurações e metatags
		$meta = [
			"titulo" => "Produtos | ".NOME_SITE,
			"descricao" => "Confira produtos da ".NOME_SITE,
			"categorias" => "produtos, elevação, segurança, produtividade",
			"url" => $canonical,
			"imagem" => URL_SITE."img/core-social.png",
			"imagem-w" => "512",
			"imagem-h" => "384",
		];
		require_once("includes/componentes/head.php");


		# CSS
		$css = [
			"pagina" => "produtos"
		];
		require_once("includes/manipuladores/css.php");
		?>
	</head>


	<body>

		<?php 
		# Topo
		require_once("includes/componentes/topo.php");
		?>

		<?php # Conteúdo principal ?>
		<main class="p-conteudo | l-envelope">

			<?php # SEO ?>
			<header class="a-hidden">
				<h1><?=$meta["titulo"]?></h1>
				<h2><?=$meta["descricao"]?></h2>
			</header>

			<div class="p-estrutura">

				<div class="p-estrutura__principal">

					<?php # Cabeçalho ?>
					<div class="p-aviso" id="aviso">
						<p class="f-titulo f-titulo--h6" id="aviso-titulo"></p>
						<p class="f-legenda" id="aviso-legenda"></p>
					</div>
					
					<?php # Resultados ?>
					<ul class="p-resultados | ativo" id="resultados"></ul>

					<?php # Paginação ?>
					<footer class="p-paginacao" id="paginacao">
						<div class="p-paginacao__envelope">
							<button type="button" aria-label="Página anterior" class="p-paginacao__botao | c-botao c-botao--sombra c-botao--icone | js-paginacao-botao" id="paginacao-botao-voltar">
								<i class="c-botao__icone | a-mi" aria-hidden="true">arrow_backward</i>
							</button>
							<label class="p-paginacao__label">
								<p class="p-paginacao__texto p-paginacao__texto--pagina">Página</p>
								<input type="text" value="1" class="p-paginacao__input | c-form__input" id="paginacao-atual">
								<p class="p-paginacao__texto">de <span id="paginacao-final">1</span></p>
							</label>
							<button type="button" aria-label="Próxima página" class="p-paginacao__botao | c-botao c-botao--sombra c-botao--icone | js-paginacao-botao" id="paginacao-botao-avancar">
								<i class="c-botao__icone | a-mi" aria-hidden="true">arrow_forward</i>
							</button>
						</div>
					</footer>
				</div>

				<section class="p-estrutura__lateral">

					<header class="p-cabecalho">
						<div>
							<h3 class="p-cabecalho__titulo | f-titulo f-titulo--h3">Produtos</h3>
							<p class="p-cabecalho__subtitulo | f-corpo" id="filtro-subtitulo"></p>
						</div>
						<button type="button" class="p-mobile | c-botao | <?=$mobile_ativo ? "ativo" : ""?>" id="filtro-mobile">
							<i class="c-botao__icone | a-mi" id="filtro-mobile-icone" aria-hidden="true"><?=$mobile_ativo ? "close" : "filter_list"?></i>
							<span class="c-botao__texto" id="filtro-mobile-texto"><?=$mobile_ativo ? "Fechar" : "Filtrar"?></span>
						</button>
					</header>

					<?php # Filtros ?>
					<section class="p-filtro | <?=$mobile_ativo ? "ativo" : ""?>" id="filtro-categorias">
						<header class="p-filtro__cabecalho">
							<h4 class="p-filtro__titulo">Categorias de produtos</h4>
						</header>
						<form class="c-form" id="filtro-form">
							<div class="p-filtro__pesquisa">
								<?php
								require_once("includes/manipuladores/formulario.php");

								form([
									"type" => "text",
									"label" => "Pesquisar por termos",
									"id" => "termos",
									"attr" => $_GET["pesquisa"] == "1" ? "autofocus" : "",
									"placeholder" => "Nome do produto",
								]);
								?>
								<button type="button" class="p-filtro__pesquisa__botao | c-botao c-botao--icone c-botao--x | js-filtro-pesquisa-botao">
									<i class="c-botao__icone | a-mi">chevron_right</i>
								</button>
							</div>

							<div class="c-form__espacador">
								<fieldset class="c-form__recipiente">
									<div class="c-form__check">
										<?php
										if ($x_categorias) {
											$form_categorias = [
												"" => "Todas as categorias",
											];
			
											foreach ($x_categorias as $categoria) {
												$form_categorias[$categoria["id"]] = $categoria["nome"];
											}
			
										}
										foreach ($form_categorias as $chave => $valor) {
										?>
										<label class="c-form__check__item" for="<?="form-input-categoria-". $chave?>">
											<input
												type="radio"
												id="<?="form-input-categoria-" . $chave?>"
												name="categoria"
												value="<?=$chave?>"
												class="c-form__check__input | a-hidden | js-filtro"
												data-nome="<?=$valor?>"
												data-url="<?=geraUrlLimpa($valor)?>"
												<?=$valor_categoria == $chave ? "checked" : ""?>>
											<i class="c-form__check__icone | a-mi--b" aria-hidden="true"></i>
											<span class="c-form__check__texto"><?=$valor?></span>
										</label>
										<?php } ?> 
									</div>
								</fieldset>
							</div>

						</form>
					</section>
				</section>

			</div>

		</main>

		<?php
		# Rodapé e aviso de privacidade
		require_once("includes/componentes/rodape.php");
		require_once("includes/componentes/privacidade.php");
		?>


		<?php # Scripts ?>
		<?php
		$scripts["jquery"] = true;
		require_once("includes/manipuladores/scripts.php");
		?>
		
		<script src="js/efeitos/paginas.min.js<?=VERSAO?>"></script>
		<script src="js/paginas/produtos.min.js<?=VERSAO?>"></script>


	</body>
	
</html>