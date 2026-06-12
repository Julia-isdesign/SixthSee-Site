$(function(){

	$(".js-fancybox").fancybox({
		animationDuration: 200,
		buttons: [
			"zoom",
			"thumbs",
			"close",
		],
		closeExisting: 1,
		preventCaptionOverlap: 0,
		toolbar: 1,
		transitionDuration: 400,
		transitionEffect: "fade",
		zoomOpacity: "auto",
	});

});