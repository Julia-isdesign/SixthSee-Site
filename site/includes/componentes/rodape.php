<?php 
$rodape_links = [[
	"url" => URL_SITE,
	"texto" => "Página inicial"
], [
	"url" => "quem-somos",
	"texto" => "Quem somos"
], [
	"url" => "produtos",
	"texto" => "Produtos"
], [
	"url" => "contato",
	"texto" => "Contato"
]];

if ($x_categorias) {
	foreach($x_categorias as $categoria) {
		$rodape_categorias[] = [
			"url" => $categoria["url"],
			"titulo" => $categoria["nome"],
		];
	}
}

$rodape_contato = [[
	"url" => "https://wa.me/+5554999512602",
	"imagem" => true,
	"icone" => "whatsapp",
	"texto" => "(54) 999.512.602"
], [
	"url" => "mailto:contato@6thsee.com.br",
	"icone" => "email",
	"texto" => "contato@6thsee.com.br"
],];
?>
<footer class="c-rodape">
	<h5 class="a-hidden">Mapa do site</h5>	
	<div class="c-rodape__envelope | l-envelope">
		<div class="c-rodape__mapa">
			<div class="c-rodape__mapa__item">
				<a href="<?=URL_SITE?>" class="c-rodape__logo | a-hover-opacity">
					<img src="img/logo-sixthsee-150.webp"
						alt="Logo da <?=NOME_SITE?>"
						class="a-img">
				</a>
				<ul class="c-rodape__lista">
					<li>
						<span class="c-rodape__texto | f-legenda">
							Rua Jacob Ely, 274 Sl 409<br>Garibaldi - RS<br>CEP 95720-000
						</span>
					</li>
				</ul>
			</div>
			<div class="c-rodape__mapa__item">
				<h6 class="c-rodape__titulo | f-titulo">Sixth See</h6>
				<ul class="c-rodape__lista">
					<?php foreach ($rodape_links as $item) { 
						$target = isset($item["target"]) ? $item["target"] : "_self"; ?>
						 <li>
							<a href="<?=$item["url"]?>" target="<?=$target?>" class="c-rodape__link">
								<span class="c-rodape__link__texto | f-legenda"><?=$item["texto"]?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
			<div class="c-rodape__mapa__item">
				<h6 class="c-rodape__titulo | f-titulo">Produtos</h6>
				<ul class="c-rodape__lista">
					<?php foreach ($rodape_categorias as $item) { ?>
						<li>
							<a href="<?=$item["url"]?>" class="c-rodape__link">
								<span class="c-rodape__link__texto | f-legenda"><?=$item["titulo"]?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
			<div class="c-rodape__mapa__item">
				<h6 class="c-rodape__titulo | f-titulo">Contato</h6>
				<ul class="c-rodape__lista c-rodape__lista--social">
					<?php foreach ($rodape_contato as $item) { ?>
						<li>
							<a href="<?=$item["url"]?>" class="c-rodape__link">
								<?php if ($item["imagem"]) { ?>
									<svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="c-rodape__link__icone" aria-hidden="true">
										<use xlink:href="img/logo-plataformas.svg#<?=$item["icone"]?>-white"></use>
									</svg>
								<?php } else { ?>
								<i class="c-rodape__link__icone | a-mi"><?=$item["icone"]?></i>
								<?php } ?>
								<span class="c-rodape__link__texto | f-legenda"><?=$item["texto"]?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="c-creditos | l-envelope">
		<div class="c-creditos__copyright | f-legenda">
			<p>Copyright <?=NOME_SITE?> - Todos os direitos reservados</p>
		</div>
		<a href="https://www.isdesign.com.br/" target="_blank" class="c-creditos__isdesign | a-hover-opacity"> 
			<img src="img/logo-isdesign-branco.svg"
				 alt="Logo isDesign Softwares"
				 loading="lazy"
				 class="a-img">
		</a>
	</div>
</footer>

<a href="https://wa.me/+5554999512602" target="_blank" class="c-whatsapp | a-hover-transform" aria-label="Envie uma mensagem no nosso WhatsApp">
	<svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="c-whatsapp__icone" aria-hidden="true">
		<use xlink:href="img/logo-plataformas.svg#whatsapp-white"></use>
	</svg>
</a> 