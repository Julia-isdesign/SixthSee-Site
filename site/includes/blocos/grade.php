<?php
/**
* Bloco de grade
* @param array $grade [ marcador, marcador-tipo, marcador-conteudo, texto-titulo, texto-legenda, link, link-url, link-target ]
**/

if ($grade) {
?>
<ul class="b-grade">
	<?php foreach ($grade as $item) { ?>
	<li class="b-grade__item">
		<div class="b-grade__conteudo">
			<?php if ($item["marcador"]) { ?>
			<i class="b-grade__marcador <?=$item["marcador-tipo"] == "mi" ? "| a-mi" : "b-grade__marcador--texto"?>" aria-hidden="true"><?=$item["marcador-conteudo"]?></i>
			<?php } ?>
			<h4 class="b-grade__titulo | f-titulo f-titulo--h4"><?=$item["texto-titulo"]?></h4>
			<?php if ($item["texto-legenda"]) { ?>
			<p class="b-grade__legenda | f-corpo"><?=nl2br($item["texto-legenda"])?></p>
			<?php } ?>
			<?php if ($item["link"]) { ?>
			<a href="<?=$item["link-url"]?>" target="<?=$item["link-target"] ?: "_self"?>" class="b-grade__link | f-link">
				<i class="f-link__icone | a-mi" aria-hidden="true">launch</i>
				<span class="f-link__texto">Acessar</span>
			</a>
			<?php } ?>
		</div>
	</li>
	<?php } ?>
</ul>
<?php
}

$grade = null;
?>