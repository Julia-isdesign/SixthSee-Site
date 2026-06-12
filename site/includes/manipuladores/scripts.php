<?php
/**
 * Manipulador JS
 * @param array $scripts [ jquery, masker, fancybox, form ]
**/
?>

<?php if ($scripts["jquery"] || $scripts["fancybox"] || $scripts["form"]) { ?>
<script src="js/bibliotecas/jquery-3.6.0.min.js<?=VERSAO?>"></script>
<?php } ?>

<?php if ($scripts["form"] || $scripts["masker"]) { ?>
<script src="js/bibliotecas/vanilla-masker.min.js<?=VERSAO?>"></script>
<?php } ?>

<?php if ($scripts["fancybox"]) { ?>
<script src="js/bibliotecas/fancybox.min.js<?=VERSAO?>"></script>
<script src="js/efeitos/fancybox.min.js<?=VERSAO?>"></script>
<?php } ?>

<?php if ($scripts["form"]) { ?>
<script src="js/efeitos/formularios.min.js<?=VERSAO?>"></script>
<?php } ?>