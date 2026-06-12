<?php
/**
 * Bloco de arquivo
 * @param array $arquivo [ url, icone, titulo, extensao ]
**/

if ($arquivo) {
?>
<a href="<?=$arquivo["url"]?>" target="_blank" class="b-arquivo | c-caixa a-hover-transform" aria-label="Fazer download do arquivo <?=$arquivo["titulo"]?>">
	<i class="b-arquivo__icone | a-mi" aria-hidden="true"><?=$arquivo["icone"] ?: "folder"?></i>
	<div class="b-arquivo__conteudo">
		<p class="b-arquivo__titulo | f-titulo f-titulo--h6"><?=$arquivo["titulo"]?></p>
		<p class="b-arquivo__legenda | f-corpo"><?="Arquivo .".strtolower($arquivo["extensao"])?></p>
	</div>
</a>
<?php
}

$arquivo = null;
?>