<?php
/**
 * Manipulador push
 * @param array $push [ fancybox, pagina ]
**/


# Principais recursos
header("Link: </site/css/geral/estilos.min.css".VERSAO.">; rel=preload; as=style", false);
header("Link: </site/fonts/Material-Icons-Round.woff2".VERSAO.">; rel=preload; as=font", false);

# Fancybox
if ($push["fancybox"]) {
	header("Link: </site/css/bibliotecas/fancybox.min.css".VERSAO.">; rel=preload; as=style", false);
}

# Página
if ($push["pagina"]) {
	header("Link: </site/css/paginas/".$push["pagina"].".min.css".VERSAO.">; rel=preload; as=style", false);
}

# Imagens
header("Link: </site/img/logo-seecranes-112.webp".VERSAO.">; rel=preload; as=image", false);
header("Link: </site/img/logo-seecranes-150.webp".VERSAO.">; rel=preload; as=image", false);
?>