<?php
/**
* Bloco de mídia
* @param array $midia [ tipo, proporcao, url, extensao, legenda, texto ]
**/

if ($midia) {
?>
<figure class="b-midia">
	<?php if ($midia["tipo"] == "imagem") { ?>
	<div class="b-midia__imagem">
		<img srcset="<?=URL_ARQUIVOS.$midia["url"]."-g.webp"?> 1600w,
					 <?=URL_ARQUIVOS.$midia["url"]."-m.webp"?> 1200w,
					 <?=URL_ARQUIVOS.$midia["url"]."-p.webp"?> 800w,
					 <?=URL_ARQUIVOS.$midia["url"]."-pp.webp"?> 400w"
			 sizes="(max-width:959px) 100vw,
					(max-width:1279px) 75vw,
					(max-width:1339px) 60vw,
					800px"
			 src="<?=URL_ARQUIVOS.$midia["url"].".webp"?>"
			 alt="<?=$midia["texto"]?>"
			 loading="lazy"
			 class="a-img a-img--cover">
	</div>
	<?php } else if ($midia["tipo"] == "video") { ?>
	<div class="b-midia__proporcao">
		<video class="a-img a-img--cover" controls>
			<source src="<?=URL_ARQUIVOS.$midia["url"].".".$midia["extensao"]?>" type="<?="video/".$midia["extensao"]?>">	
			Este navegador não suporta o formato do vídeo
		</video>
	</div>
	<?php
	}
	else if ($midia["tipo"] == "youtube") {
		require_once(DIRETORIO_SITE."_controle/handlers/arquivos.php");
	?>
	<div class="b-midia__proporcao">
		<button type="button"
				class="b-midia__youtube | js-youtube | a-img a-img--cover"
				data-youtube="<?=urlDoVideo($midia["url"], "embed")?>"
				aria-label="Reproduzir vídeo do YouTube">
			<svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet"
					class="b-midia__youtube__logo" aria-hidden="true">
				<use xlink:href="img/logo-plataformas.svg#youtube-white"></use>
			</svg>
			<img src="<?=urlDoVideo($midia["url"], "imagem")?>"
					alt=""
					loading="lazy"
					class="b-midia__youtube__cover">
		</button>
	</div>
	<?php } else if ($midia["tipo"] == "mapa") { ?>
	<div class="b-midia__proporcao">
		<div class="a-img a-img--cover">
			<iframe src="<?=$midia["url"]?>" allowfullscreen loading="lazy" class="a-img a-img--cover" style="border:0"></iframe>
		</div>
	</div>
	<?php } else if ($midia["tipo"] == "audio") { ?>
	<div class="b-midia__audio">
		<audio class="b-midia__audio__player" controls>
			<source src="<?=URL_ARQUIVOS.$midia["url"].".".$midia["extensao"]?>" type="<?="audio/".$midia["extensao"]?>">
			Este navegador não suporta o formato do áudio
		</audio>
	</div>
	<?php } else if ($midia["tipo"] == "soundcloud") { ?>
	<div class="b-midia__soundcloud">
		<?=$midia["url"]?>
	</div>
	<?php } ?>
	<?php if ($midia["legenda"]) { ?>
	<figcaption class="b-midia__legenda f-legenda"><?=$midia["texto"]?></figcaption>
	<?php } ?>
</figure>
<?php
}

$midia = null;
?>