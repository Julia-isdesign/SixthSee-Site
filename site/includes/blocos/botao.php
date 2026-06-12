<?php
/**
 * Bloco Botão
 * @param array $botao [ tipo, botao-acao, link-url, link-target, classe, icone, conteudo ]
**/

if ($botao) {
	if ($botao["tipo"] == "link") {
?>
<a href="<?=$botao["link-url"]?>" target="<?=$botao["link-target"] ?: "_self"?>" class="c-botao <?=$botao["classe"]?>">
<?php } else { ?>
<button type="button" class="c-botao <?=$botao["classe"]?> <?=$botao["botao-acao"] ? "| ".$botao["botao-acao"] : ""?>">
<?php } ?>
	<?php if ($botao["icone"]) { ?>
	<i class="c-botao__icone | a-mi" aria-hidden="true"><?=$botao["icone"]?></i>
	<?php } ?>
	<?php if ($botao["conteudo"]) { ?>
	<span class="c-botao__texto"><?=$botao["conteudo"]?></span>
	<?php } ?>
<?php if ($botao["tipo"] == "link") { ?>
</a>
<?php } else { ?>
</button>
<?php
	}
}

$botao = null;
?>