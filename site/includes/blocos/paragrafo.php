<?php
/**
* Bloco de parágrafo
* @param array $paragrafo [ id, classe, conteudo, imagem, imagem-proporcao, imagem-url, imagem-legenda, imagem-texto ]
**/

if ($paragrafo) {
?>
<div class="b-paragrafo <?=$paragrafo["classe"]?> | a-clear">
	<?php if ($paragrafo["imagem"]) { ?>
	<a href="<?=URL_ARQUIVOS.$paragrafo["imagem-url"].".webp"?>"
	   class="b-paragrafo__link | a-hover-transform | js-fancybox"
	   data-caption="<?=$paragrafo["imagem-texto"]?>"
	   data-fancybox="<?="imagem-".$paragrafo["id"]?>">
		<figure>
			<div class="b-paragrafo__imagem">
				<img srcset="<?=URL_ARQUIVOS.$paragrafo["imagem-url"]."-g.webp"?> 1600w,
							 <?=URL_ARQUIVOS.$paragrafo["imagem-url"]."-m.webp"?> 1200w,
							 <?=URL_ARQUIVOS.$paragrafo["imagem-url"]."-p.webp"?> 800w,
							 <?=URL_ARQUIVOS.$paragrafo["imagem-url"]."-pp.webp"?> 400w"
						<?php if (strpos($paragrafo["classe"], "b-paragrafo--x") !== false) { ?>
						sizes="(max-width:719px) 100vw,
							   (max-width:959px) 40vw,
							   (max-width:1339px) 20vw,
							   300px"
						<?php } else { ?>
						sizes="(max-width:719px) 100vw,
							   (max-width:959px) 50vw,
							   (max-width:1339px) 30vw,
							   380px"
						<?php } ?>
						src="<?=URL_ARQUIVOS.$paragrafo["imagem-url"].".webp"?>"
						alt="<?=$paragrafo["imagem-texto"]?>"
						loading="lazy"
						class="a-img a-img--cover">
			</div>
			<?php if ($paragrafo["imagem-legenda"]) { ?>
			<figcaption class="b-paragrafo__legenda | f-legenda"><?=$paragrafo["imagem-texto"]?></figcaption>
			<?php } ?>
		</figure>
	</a> 
	<?php } ?>
	<div class="f-wysiwyg"><?=$paragrafo["conteudo"]?></div>
</div>
<?php
}

$paragrafo = null;
?>