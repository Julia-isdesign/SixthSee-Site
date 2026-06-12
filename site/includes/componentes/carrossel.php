<?php
# Carrossel / Banners

# Consultar os banners
$banners = consultar(
	"SELECT id, titulo, link,
			(SELECT file
			   FROM arquivos
			  WHERE arquivos.idPai = banners.id
			    AND arquivos.pai = 'banners-desktop'
			  ORDER BY arquivos.id DESC LIMIT 1) AS imgDesktop,
			(SELECT file
			   FROM arquivos
			  WHERE arquivos.idPai = banners.id
			    AND arquivos.pai = 'banners-mobile'
			  ORDER BY arquivos.id DESC LIMIT 1) AS imgMobile
	   FROM banners
	  WHERE ativo = 1 AND dataInicial <= NOW() AND dataFinal >= NOW()
	  ORDER BY pos
	  LIMIT 10"
);

# Facilitar acesso aos dados
$banners = $banners["dados"];

# Garantir que há resultados
if ($banners) {
	# Iniciar array vazia para o carrossel
	$carrossel = [];

	# Iterar por todos os banners e popular o carrossel
	foreach ($banners as $banner) {
		$carrossel[] = [
			"link" => $banner["link"] ? true : false,
			"url" => $banner["link"],
			"target" => strpos($banner["link"], URL_ARQUIVOS) === false ? "_blank" : "_self",
			"imagem-desktop" => $banner["imgDesktop"],
			"imagem-mobile" => $banner["imgMobile"] ?: $banner["imgDesktop"],
			"titulo" => $banner["titulo"],
		];
	}

	# Descobrir o número total de resultados
	$carrossel_total = count($carrossel);
?>
<section class="c-carrossel" id="carrossel">
	<?php if ($carrossel_total > 1) { ?>
	<button type="button" class="c-carrossel__seta c-carrossel__seta--d | a-mi a-hover-opacity | js-carrossel-seta" id="carrossel-botao-proximo" aria-label="Próximo banner">arrow_forward</button>
	<button type="button" class="c-carrossel__seta c-carrossel__seta--e | a-mi a-hover-opacity | js-carrossel-seta" id="carrossel-botao-anterior" aria-label="Banner anterior">arrow_back</button>
	<?php } ?>
	<div class="c-carrossel__scroller" id="carrossel-scroller">
		<ul class="c-carrossel__lista" id="carrossel-lista">
			<?php foreach ($carrossel as $item) { ?>
			<li class="c-carrossel__lista__item">
				<?php if ($item["link"]) { ?>
				<a href="<?=$item["url"]?>" target="<?=$item["target"]?>" class="c-carrossel__conteudo c-carrossel__conteudo--link">
				<?php } else { ?>
				<div class="c-carrossel__conteudo">
				<?php } ?>
					<picture>
                        <source srcset="<?=URL_ARQUIVOS.$item["imagem-mobile"]."-pp.webp"?> 400w,
                                        <?=URL_ARQUIVOS.$item["imagem-mobile"]."-p.webp"?> 800w" media="(max-width:719px)">
                        <source srcset="<?=URL_ARQUIVOS.$item["imagem-desktop"]."-m.webp"?> 800w,
                                        <?=URL_ARQUIVOS.$item["imagem-desktop"]."-g.webp"?> 1200w,
                                        <?=URL_ARQUIVOS.$item["imagem-desktop"]."-g.webp"?> 1600w" media="(min-width:720px)">
                        <img srcset="<?=URL_ARQUIVOS.$item["imagem-desktop"]."-pp.webp"?> 400w,
                                     <?=URL_ARQUIVOS.$item["imagem-desktop"]."-p.webp"?> 800w,
                                     <?=URL_ARQUIVOS.$item["imagem-desktop"]."-p.webp"?> 1200w,
                                     <?=URL_ARQUIVOS.$item["imagem-desktop"]."-g.webp"?> 1600w"
                             src="<?=URL_ARQUIVOS.$item["imagem-desktop"]."-g.webp"?>"
                             alt="Veja mais sobre - <?=$item["titulo"]?>"
							 <?=$carrossel_loading?>
							 class="c-carrossel__imagem">
                    </picture>
					<div class="c-carrossel__envelope | l-envelope l-envelope--m">
						<?php if ($item["titulo"]) { ?>
						<h3 class="c-carrossel__titulo | f-titulo f-titulo--h3"><?=$item["titulo"]?></h3>
						<?php } ?>
					</div>
				<?php if ($item["url"]) { ?>
				</a>
				<?php } else { ?>
				</div>
				<?php } ?>
			</li>
			<?php
				if ($carrossel_loading == "") {
					$carrossel_loading = 'loading="lazy"';
				}
			}
			?>
		</ul>
	</div>
	<div class="c-carrossel__menu">
		<?php for ($i = 0; $i < $carrossel_total; $i++) { ?>
		<div type="button" class="c-carrossel__menu__item | js-carrossel-menu <?=($i == 0) ? "| ativo" : ""?>" data-numero="<?=$i?>"></div>
		<?php } ?>
	</div>
</section>
<script async src="js/efeitos/carrossel.min.js"></script>
<?php } ?>