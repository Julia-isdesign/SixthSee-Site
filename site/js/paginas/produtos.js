$(function(){

	const produtos = fn_paginas({
		url: "ajax/produtos.php",
		quantidade: 12,
		filtros: true,
		mensagem: {
			carregando: {
				titulo: "Carregando produtos...",
				legenda: "Por favor, aguarde enquanto buscamos os produtos",
			},
			erro: {
				titulo: "Ops, conteúdo indisponível",
				legenda: "Não foi possível buscar o material solicitado, tente novamente",
			},
		},
		formatar: (item) => {
			if (item.imagem) {
                item.imagem = JS_URL_BASE + `uploads/${item.imagem}`;

				item.imagem = `
					<figure class="p-produto__imagem">
						<img srcset="${item.imagem}-g.webp 1600w,
									 ${item.imagem}-m.webp 1200w,
									 ${item.imagem}-p.webp 800w,
									 ${item.imagem}-pp.webp 400w"
							 sizes="(max-width:479px) 80px,
									(max-width:719px) 50vw
								   	(max-width:1339px) 25vw
									260px"
							 src="${item.imagem}.webp"
							 alt="Veja mais do produto ${item.titulo}"
							 class="a-img a-img--contain">
					</figure>
				`;
			}
			else {
				item.imagem = `
				<div class="p-produto__imagem | a-capa a-capa--claro"></div>
				`;
			}

			if (item.subtitulo) {
				item.subtitulo = `<h4 class="p-produto__marca | f-legenda">${item.subtitulo}</h4>`
			}
			else {
				item.subtitulo = `
				`;
			}

			let template = `
				<li class="p-lista__item">
					<a href="${item.url}" class="p-produto | a-hover-opacity">
						${item.imagem}
						<div class="p-produto__conteudo">
							<p class="p-produto__titulo | f-titulo">${item.titulo}</p>
							${item.subtitulo}
						</div>
					</a>
				</li>
			`;

			return template;
		}
	});

	produtos.carregar();
	

	const $filtro_subtitulo = $("#filtro-subtitulo");

	const $filtro_mobile = $("#filtro-mobile");
	const $filtro_mobile_texto = $("#filtro-mobile-texto");
	const $filtro_mobile_icone = $("#filtro-mobile-icone");

	const $filtro = $(".js-filtro");
	const $filtro_categorias = $("#filtro-categorias")


	// Abrir e fechar filtros no mobile

	$filtro_mobile.on("click", function() {
		if ($filtro_mobile.hasClass("ativo")) {
			$filtro_mobile_texto.html("Filtrar");
			$filtro_mobile_icone.html("filter_list");

			$filtro_mobile.removeClass("ativo");
			$filtro_categorias.removeClass("ativo");
		} else {
			$filtro_mobile_texto.html("Fechar");
			$filtro_mobile_icone.html("close");

			$filtro_mobile.addClass("ativo");
			$filtro_categorias.addClass("ativo");
		}
	});



	function atualizar_categoria($filtro) {
		let $valor = $filtro.val();
		let $canonical = $('link[rel="canonical"]');

		let $url = $valor ? `produtos/${$valor}/${$filtro.data("url")}` : "produtos";
		history.replaceState(null, "", $url);

		$canonical.attr('href', JS_URL_SITE + `${$url}`);
		
		$filtro_subtitulo.text($filtro.data("nome"));
	}
	
	$filtro.each(function() {
		if ($(this).prop("checked")) {
			atualizar_categoria($(this));
		}
	});
	
	$filtro.on("change", function() {
		atualizar_categoria($(this));
	});

});