(function(){

	// Elementos
	const $d = document;
	const $w = window;

	// Containers
	const $carrossel = $d.querySelector("#carrossel");
	const $scroller = $d.querySelector("#carrossel-scroller");

	// Lista e itens
	const $lista = $d.querySelector("#carrossel-lista");
	const $item = $d.querySelectorAll(".js-carrossel-item");

	// Menu (bullets) e setas
	const $menu = $d.querySelectorAll(".js-carrossel-menu");
	const $seta = $d.querySelectorAll(".js-carrossel-seta");

	// Variáveis para funcionamento
	let atual = 0;
	const total = $menu.length - 1;

	// Variável para cálculos
	const quantidade = total + 1;

	// Variável para timer
	let timer;


	// Definir o tamanho da lista
	$lista.style.width = `${100 * quantidade}%`;

	// Definir o tamanho de cada item
	$item.forEach((elemento) => {
		elemento.style.width = `${100 / quantidade}%`;
	});


	// Função principal
	function fn_carrossel(){
		// Limpar timer
		clearTimeout(timer);

		// Descobrir o tamanho do movimento
		const translate = atual * (100 / quantidade);

		// Mover a lista principal
		$lista.style.transform = `translateX(-${translate}%)`;

		// Remover a classe "ativo" dos itens do menu
		$menu.forEach((elemento) => {
			elemento.classList.remove("ativo");
		});

		// Adicionar a classe "ativo" ao item do menu ativado
		$menu.item(atual).classList.add("ativo");

		// Reiniciar o timer
		fn_timer();
	}

	// Função de timer
	function fn_timer(){
		// Definir um time out de 5 segundos
		timer = setTimeout(() => {
			// Identificar qual será o novo banner
			// Caso tenha chegado ao fim, retorna para o início
			const temp = (atual == total) ? 0 : (atual + 1);

			// Disparar o gatilho do clique
			$menu.item(temp).click();
		}, 5e3);
	}


	// Função de clique no menu
	// Percorrer cada item da lista de botões
	$menu.forEach(function(e){
		// Adicionar o listener de clique
		e.addEventListener("click", function(){
			// Verificar o tamanho da tela
			// Se a tela for menor do que 720px, cancelar função
			if ($w.innerWidth < 720) return;

			// Definir o valor do banner atual conforme atributo do botão clicado
			atual = parseInt(e.dataset.numero);

			// Chamar a função principal
			fn_carrossel();
		}, false);
	});


	// Função de clique nas setas
	// Percorrer cada item da lista de setas
	$seta.forEach(function(e){
		// Adicionar listener de clique
		e.addEventListener("click", function(){
			// Verificar se estamos avançando ou voltando
			if (this.id == "carrossel-botao-proximo") {
				// Identificar qual será o novo banner
				// Caso tenha chegado ao fim, retorna para o início
				atual = (atual == total) ? 0 : (atual + 1);
			}
			else {
				// Identificar qual será o novo banner
				// Caso tenha ao início, vai para o final
				atual = (atual == 0) ? total : (atual - 1);
			}

			// Chamar a função principal
			fn_carrossel();
		}, false);
	});


	// Função de scroll
	$scroller.addEventListener("scroll", function(e){
		// Calcular até qual banner o usuário rolou
		atual = e.target.scrollLeft / $carrossel.offsetWidth;
		atual = Math.round(atual);

		// Garantir que não é maior do que o total possível
		if (atual > total) atual = total;

		// Chamar a função principal
		fn_carrossel();
	});


	// Disparar o primeiro gatilho
	// Para dar início ao timer
	$menu.item(atual).click();

}());