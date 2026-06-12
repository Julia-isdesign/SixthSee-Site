(function(){

	// Lazy loading YouTube videos
	const $d = document;
	const $youtube = $d.querySelectorAll(".js-youtube");

	if ($youtube.length) {
		$youtube.forEach(function(elem){
			elem.addEventListener("click", function(){
				const $parent = elem.parentNode;
				const url = elem.dataset.youtube;

				$parent.innerHTML = `<iframe src="${url}?autoplay=1&rel=0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen frameborder="0" class="a-img a-img--cover"></iframe>`;
			}, false);
		});
	}

}());