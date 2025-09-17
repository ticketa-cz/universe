
/*------ Universe WooCommerce Script -----*/

jQuery(document).ready(function($){

	$('.single-product div.images').each(function(){

		var options = $(this).data('magnifieroptions');

		var magnifier_options = {
			enableSlider: options.enableSlider,

			sliderOptions: {
				responsive: false,
				circular: options.circular,
				infinite: options.infinite,
				direction: 'left',
				debug: false,
				auto: false,
				align: 'left',
				prev	: {
					button	: "#slider-prev",
					key		: "left"
				},
				next	: {
					button	: "#slider-next",
					key		: "right"
				},
				scroll : {
					items	: 1,
					pauseOnHover: true
				}
			},

			showTitle: false,
			zoomWidth: options.zoomWidth,
			zoomHeight: options.zoomHeight,
			position: options.position,
			lensOpacity: options.lensOpacity,
			softFocus: true,
			adjustY: 0,
			disableRightClick: false,
			phoneBehavior: options.phoneBehavior,
			loadingLabel: options.loadingLabel
		};

		if(options.active == 'true'){
			$(this).universe_magnifier(magnifier_options);
		}

		$('.images a.universe_magnifier_thumbnail img').on('click', function(e){

			e.preventDefault();
			var srcset = $(this).attr('srcset');

			$('a.universe_magnifier_zoom>img').attr('srcset', srcset);

		});
	});
});