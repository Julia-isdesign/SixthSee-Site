<?php
/**
* Bloco de galeria
* @param array $galeria [ id, url, legenda ]
**/

if ($galeria) {
?>
<ul class="b-galeria">
	<?php foreach ($galeria as $foto) { ?>
	<li class="b-galeria__item">
		<a href="<?=URL_ARQUIVOS.$foto["url"].".webp"?>"
		   class="b-galeria__link | a-hover-opacity | js-fancybox"
		   data-caption="<?=$foto["legenda"]?>"
		   data-fancybox="<?="galeria-".$foto["id"]?>">
			<figure class="b-galeria__imagem">
				<img srcset="<?=URL_ARQUIVOS.$foto["url"]."-g.webp"?> 1600w,
							 <?=URL_ARQUIVOS.$foto["url"]."-m.webp"?> 1200w,
							 <?=URL_ARQUIVOS.$foto["url"]."-p.webp"?> 800w,
							 <?=URL_ARQUIVOS.$foto["url"]."-pp.webp"?> 400w"
					 sizes="(max-width:639px) 50vw,
							(max-width:959px) 30vw,
							(max-width:1119px) 20vw,
							(max-width:1339px) 16vw,
							185px"
					 src="<?=URL_SITE.$foto["url"].".webp"?>"
					 alt="<?=$foto["legenda"]?>"
					 loading="lazy"
					 class="a-img a-img--cover">
			</figure>
		</a>
	</li>
	<?php } ?>
</ul>
<?php
}

$galeria = null;
?>