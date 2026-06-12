(function(){

	const $d = document;

	const $galeria = $d.querySelectorAll(".js-galeria");
	const $imagens = $d.querySelectorAll(".js-imagem");

	$galeria.forEach((e) => {
		e.addEventListener("click", () => {

			$galeria.forEach((e) => {
				e.classList.remove("ativo");
			});
			$imagens.forEach((e) => {
				e.classList.remove("ativo");
			});

			e.classList.add("ativo");
			$imagens[e.dataset.foto].classList.add("ativo");

		}, false);
	});

}());