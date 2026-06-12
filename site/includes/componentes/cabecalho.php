<?php
/**
 * Componente cabeçalho
 * @param array $cabecalho [ icone, titulo, subtitulo, imagem ]
**/

if ($cabecalho) {
?>
<header class="c-cabecalho <?=isset($cabecalho["imagem"]) ? "c-cabecalho--imagem" : ""?>">
	<div class="c-cabecalho__envelope | l-conteudo l-envelope l-envelope--m">
		<?php if (isset($cabecalho["icone"])) { ?>
		<i class="c-cabecalho__icone | a-mi a-mi--g" aria-hidden="true"><?=$cabecalho["icone"]?></i>
		<?php } ?>
		<?php if (isset($cabecalho["titulo"])) { ?>
		<h1 class="c-cabecalho__titulo | f-titulo f-titulo--h1"><?=$cabecalho["titulo"]?></h1>
		<?php } ?>
		<?php if (isset($cabecalho["subtitulo"])) { ?>
		<h2 class="c-cabecalho__subtitulo | f-texto"><?=$cabecalho["subtitulo"]?></h2>
		<?php } ?>
	</div>
	<figure class="c-cabecalho__midia">
		<?php if (isset($cabecalho["imagem"])) { ?>
		<img srcset="<?=URL_ARQUIVOS.$cabecalho["imagem"]."-g.webp"?> 1600w,
					 <?=URL_ARQUIVOS.$cabecalho["imagem"]."-m.webp"?> 1200w,
					 <?=URL_ARQUIVOS.$cabecalho["imagem"]."-p.webp"?> 800w,
					 <?=URL_ARQUIVOS.$cabecalho["imagem"]."-pp.webp"?> 400w"
			 src="<?=URL_ARQUIVOS.$cabecalho["imagem"].".webp";?>"
             alt="<?=$cabecalho["titulo"]?>"
             class="a-img a-img--cover">
		<?php } ?>
	</figure>
</header>
<?php
}

$cabecalho = null;
?>