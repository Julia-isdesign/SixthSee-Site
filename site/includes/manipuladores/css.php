<?php
/**
 * Manipulador CSS
 * @param array $css [ fancybox, pagina ]
**/
?>

<link href="css/geral/estilos.min.css<?=VERSAO?>" rel="stylesheet">

<?php if ($css["fancybox"]) { ?>
<link href="css/bibliotecas/fancybox.min.css<?=VERSAO?>" rel="stylesheet">
<?php } ?>

<?php if ($css["pagina"]) { ?>
<link href="<?="css/paginas/".$css["pagina"].".min.css".VERSAO?>" rel="stylesheet">
<?php } ?>