<?php
/**
 * Bloco de título
 * @param array $titulo [ conteudo, tamanho ]
**/

if ($titulo) {
    if (!in_array($titulo["tamanho"], ["h1", "h2", "h3", "h4", "h5", "h6"])) {
		$titulo["tamanho"] = "h3";
	}
?>
<h3 class="f-titulo f-titulo--<?=$titulo["tamanho"] ?: "h3"?>"><?=$titulo["conteudo"]?></h3>
<?php
}

$titulo = null;
?>