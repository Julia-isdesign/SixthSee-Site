(function(){

	// Elementos gerais
	const $d = document;
	const $w = window;

	// Menu no mobile
	const $menu = $d.querySelector("#menu-conteudo");

	// Botão mobile
	const $mobile = $d.querySelector("#menu-mobile");
	const $mobile_texto = $d.querySelector("#menu-mobile-texto");
	const $mobile_icone = $d.querySelector("#menu-mobile-icone");

	// Botões
	const $botoes = $d.querySelectorAll(".js-menu-botao");
	const $submenus = $d.querySelectorAll(".js-menu-submenu");


	// Função de "desativar" botões e submenus  
	const fn_menu = (function() {
		$botoes.forEach(function(e) {
			e.classList.remove("ativo");
		});
		
		$submenus.forEach(function(e) {
			e.classList.remove("ativo");
		});
	});


	// Função fecha submenus com clique externo
	$w.addEventListener("click", function(e) {
		// Se for menor que 720px de largura, para por aqui
		if ($w.screen.width < 720) return;

		// Verificar se o target possui não possui "menu" entre as classes
		if (e.target && typeof e.target.className === "string") {
			if (e.target.className.includes("menu") === false) {
				fn_menu();
			}
		}
	});


	// Clique no botão mobile
	$mobile.addEventListener("click", function(){
		if (!$menu.classList.contains("ativo")) {
			// Se não houver classe "ativo", adicionar
			$menu.classList.add("ativo");

			// Mostrar ao usuário que o botão está clicado
			$mobile_texto.innerHTML = "Fechar";
			$mobile_icone.innerHTML = "menu_open";
		}
		else {
			// Se houver classe "ativo", remover
			$menu.classList.remove("ativo");

			// Resetar o botão do mobile
			$mobile_texto.innerHTML = "Menu";
			$mobile_icone.innerHTML = "menu";

			// Fechar todos os submenus
			fn_menu();
		}
	}, false);


	// Efeito de ativo no botão clicado e seu respectivo submenu
	// Iterar por todos os botões do menu
	$botoes.forEach(function(botao) {
		// Adicionar listener de clique
		botao.addEventListener("click", function() {
			// Verificar se possui a classe ativo
			const ativo = botao.classList.contains("ativo");

			// Fechar os submenus
			fn_menu();

			// Se possuía a classe "ativo", para por aqui
			if (ativo) return;

			// Se não possuía, adiciona a classe ao botão e ao submenu
			botao.classList.add("ativo");
			botao.nextElementSibling.classList.add("ativo");
		})
	});

})();