<?php
if ($x_categorias) {
	foreach($x_categorias as $categoria) {
		$topo_categorias[] = [
			"url" => $categoria["url"],
			"titulo" => $categoria["nome"],
		];
	}
}

$topo_links = [[
	"url" => "quem-somos",
	"texto" => "Quem somos"
],[
	"texto" => "Produtos",
	"submenu" => $topo_categorias,
],[
	"url" => "contato",
	"texto" => "Contato"
]];
?>
<header class="c-topo">
	<div class="c-topo__envelope | l-envelope">
		<?php # Logo ?>
		<a href="<?=URL_SITE?>" class="c-topo__logo | a-hover-opacity">
			<img srcset="img/logo-seecranes-150.webp 150w,
						 img/logo-seecranes-112.webp 112w"
				 sizes="(max-width:719px) 112px,
						150px"
				 src="img/logo-seecranes-150.webp"
				 alt="Logo da <?=NOME_SITE?>"
				 class="a-img">
		</a>

		<?php # Mobile ?>
		<button class="c-topo__mobile | a-hover-opacity" id="menu-mobile">
			<i class="c-topo__mobile__icone | a-mi" id="menu-mobile-icone" aria-hidden="true">menu</i>
			<span class="c-topo__mobile__texto" id="menu-mobile-texto">Menu</span>
		</button>

		<div class="c-topo__conteudo" id="menu-conteudo">
			<?php # Pesquisa ?>
			<a href="produtos?pesquisa=1" class="c-topo__link c-topo__link--pesquisa | a-hover-opacity">
				<i class="c-topo__link__icone | a-mi" aria-hidden="true">search</i>
				<span class="c-topo__link__texto">Pesquise produtos</span>
			</a>
			<?php # Whatsapp ?>
			<a href="https://wa.me/+5554999512602" target="_blank" class="c-topo__link c-topo__link--whatsapp | a-hover-opacity">
				<svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="c-topo__link__icone" aria-hidden="true">
					<use xlink:href="img/logo-plataformas.svg#whatsapp-black"></use>
				</svg>
				<span class="c-topo__link__texto">(54) 999.512.602</span>
			</a>
			<ul class="c-menu">
				<?php foreach($topo_links as $item) { ?>
				<li class="c-menu__item <?=$item["submenu"] ? "c-menu__item--submenu" : ""?>">
					<?php if ($item["submenu"]) { ?>
						<button type="button" class="c-menu__botao | js-menu-botao">
							<span class="c-menu__botao__texto"><?=$item["texto"]?></span>
							<i class="c-menu__botao__icone | a-mi" aria-hidden="true">arrow_drop_down</i>
						</button>
						<ul class="c-menu__submenu | js-menu-submenu">
							<?php foreach($item["submenu"] as $item) { ?>
								<li class="c-menu__submenu__item | a-hover-opacity">
									<a href="<?=$item["url"]?>" class="c-menu__submenu__link"><?=$item["titulo"]?></a>
								</li>
							<?php } ?>
						</ul>
					<?php } else { ?>
					<a href="<?=$item["url"]?>" class="c-menu__link | a-hover-opacity"><?=$item["texto"]?></a>
					<?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>	
</header>
<script async src="js/efeitos/topo.min.js"></script>