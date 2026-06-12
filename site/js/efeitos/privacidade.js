(function(){

	// Selecionar elementos
	const $d = document;
	const $privacidade = $d.querySelector("#privacidade");


	// Criar função que coloca o script de monitoramento
	const fn_monitoramento = (function(){
		// Criar uma tag script
		const script = $d.createElement("script");

		// Definir a tag como assíncrona e definir atributo "src"
		script.async = true;
		script.src = "https://www.googletagmanager.com/gtag/js?id=G-M0MEZSSMWB";

		// Adicionar a tag ao final do body
		$d.body.appendChild(script);

		// Chamar script do Google Analytics 4
		window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-M0MEZSSMWB');
	});


	// Verificar se o aviso foi apresentado e dispensado
	if (localStorage.getItem("privacidade")) {
		// Remover o elemento do DOM
		$privacidade.remove();

		// Chamar função de monitoramento
		fn_monitoramento();
	}
	else {
		// Mostrar o aviso de privacidade
		$privacidade.classList.add("ativo");
	
		// Adicionar listener no botão
		$d.querySelector("#privacidade-botao").addEventListener("click", function(){
			// Chamar função de monitoramento
			fn_monitoramento();

			// Armazenar o clique do usuário para próximo acesso
			localStorage.setItem("privacidade", 1);
	
			// Esconder aviso de privacidade
			$privacidade.classList.remove("ativo");

			// Definir um timer, conforme efeito do transition
			setTimeout(() => {
				// Remover o elemento do DOM
				$privacidade.remove();
			}, 200);
		}, false);
	}

})();