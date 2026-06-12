fn_paginas = (function(opcoes){

	// Configuraçõs

	let padrao = {
		url: "",
		quantidade: 10,
		filtros: false,
		mensagem: {
			carregando: {
				titulo: "Carregando conteúdo...",
				legenda: "Por favor, aguarde enquanto buscamos o material solicitado",
			},
			erro: {
				titulo: "Ops, conteúdo indisponível",
				legenda: "Não foi possível buscar o material solicitado, tente novamente",
			},
		},
		formatar: () => {}
	};

	let config = $.extend(true, padrao, opcoes);



	// Elementos

	const $body = $("html, body");
	const $resultados = $("#resultados");

	const $aviso = $("#aviso");
	const $aviso_titulo = $("#aviso-titulo");
	const $aviso_legenda = $("#aviso-legenda");

	const $paginacao = $("#paginacao");
	const $paginacao_atual = $("#paginacao-atual");
	const $paginacao_final = $("#paginacao-final");
	const $paginacao_botoes = $(".js-paginacao-botao");
	const $paginacao_botao_voltar = $("#paginacao-botao-voltar");
	const $paginacao_botao_avancar = $("#paginacao-botao-avancar");

	const $filtro_form = $("#filtro-form");
	const $filtro_campos = $("#form-input-termos");
	const $filtro_botao = $(".js-filtro-pesquisa-botao");



	// Variáveis

	let pagina_atual = 1;
	let pagina_final = 1;

	let resposta = "";
	let parametros;

	let primeiro_carregamento = true;
	let carregando = false;



	// Carregar lista

	config.carregar = (function(){

		if (carregando) return;
		carregando = true;

		$resultados.addClass("a-loader");
		$filtro_form.addClass("a-loader");
		$paginacao_botoes.removeClass("ativo");

		$aviso.addClass("ativo");
		$aviso_titulo.html(config.mensagem.carregando.titulo);
		$aviso_legenda.html(config.mensagem.carregando.legenda);

		if (primeiro_carregamento) {
			primeiro_carregamento = false;
		}
		else {
			$body.stop().animate({ scrollTop: $aviso.offset().top - 20 }, 400);
		}

		if (config.filtros) {
			let data = new FormData($filtro_form[0]);

			parametros = data;

		}
		else {
			parametros = new FormData();
		}

		parametros.append("pagina", pagina_atual);
		parametros.append("quantidade", config.quantidade);

		$.ajax({
			url: config.url, 
			data: parametros,
			type: "POST",
			contentType: false,
			cache: false,
			processData: false,
		})
		.done(function(e) {
			const json = jQuery.parseJSON(e);

			if (typeof json.resultados != "object" || json.resultados == 0) {
				$resultados.removeClass("ativo").html("");

				$aviso.addClass("ativo").show();
				$aviso_titulo.html(config.mensagem.erro.titulo);
				$aviso_legenda.html(config.mensagem.erro.legenda);

				pagina_final = 0;
			}
			else {
				resposta = "";

				json.resultados.forEach(function(item) {
					resposta += config.formatar(item);
				});

				$resultados.addClass("ativo").html(resposta);

				$aviso.removeClass("ativo");
				$aviso_titulo.html("");
				$aviso_legenda.html("");

				pagina_final = json.paginas;
			}
		})
		.fail(function(){
			$resultados.removeClass("ativo").html("");

			$aviso.addClass("ativo");
			$aviso_titulo.html(config.mensagem.erro.titulo);
			$aviso_legenda.html(config.mensagem.erro.legenda);

			pagina_final = 0;
		})
		.always(function(){
			$resultados.removeClass("a-loader");
			$filtro_form.removeClass("a-loader");

			$paginacao_final.html(pagina_final);

			if (pagina_atual > 1) {
				$paginacao_botao_voltar.addClass("ativo");
			}
			if (pagina_atual < pagina_final) {
				$paginacao_botao_avancar.addClass("ativo");
			}

			if (pagina_final != 0) {
				$paginacao.addClass("ativo");
			}
			else {
				$paginacao.removeClass("ativo");
			}

			carregando = false;
		});

	});



	// Filtros

	$filtro_form.on("change", function() {
		$paginacao_atual.val(1);
		pagina_atual = $paginacao_atual.val();

		config.carregar();
	});

	$filtro_campos.on("keydown", function(e){
		let $this = $(this);

		if (e.keyCode == 13) {
			e.preventDefault();

			$this.trigger("blur");
			$filtro_botao.trigger("click");
		}
	});



	// Input

	$paginacao_atual.on("keydown", function(e){
		if (e.keyCode == 13) {
			e.preventDefault();
			$paginacao_atual.trigger("blur");
		}
	}).on("blur", function(){
		let valor = parseInt($paginacao_atual.val());

		if (isNaN(valor) || valor < 1) {
			$paginacao_atual.val(1);
		}
		else if (valor > pagina_final) {
			$paginacao_atual.val(pagina_final);
		}

		pagina_atual = $paginacao_atual.val();
		config.carregar();
	});



	// Botões

	$paginacao_botoes.on("click", function(){
		let $this = $(this);

		if (!$this.hasClass("ativo")) return;

		if ($this.attr("id") == $paginacao_botao_voltar.attr("id")) {
			pagina_atual--;
		}
		else {
			pagina_atual++;
		}

		$paginacao_atual.val(pagina_atual);
		config.carregar();
	});



	// The magic

	return config;

});