(function($, window, document, undefined) {

	'use strict';


	$('#grid-masonry-container').each(function(){



		if( $(this).data('loaded') !== true )

			$(this).data({'loaded':true});

		else return;



		var _cols = $(this).data('cols'),

			_caption = $(this).data('caption'),

			_animation = $(this).data('animation'),

			_gap = $(this).data('gap').toString(),

			_gap_sp, _gapHorizontal, _gapVertical;



		_gap_sp = _gap.split("|");



		if(_gap_sp.length > 1){

			_gapHorizontal = parseInt(_gap_sp[0]);

			_gapVertical = parseInt(_gap_sp[1]);

		}else{

			_gapHorizontal = parseInt(_gap_sp[0]);

			_gapVertical = parseInt(_gap_sp[0]);

		}



		$(this).cubeportfolio({

			filters: '#js-filters-masonry',

			loadMore: '#loadMore-container',

			loadMoreAction: 'click',

			layoutMode: 'masonry',

			defaultFilter: '*',

			animationType: _animation,

			gapHorizontal: _gapHorizontal,

			gapVertical: _gapVertical,

			gridAdjustment: 'responsive',

			mediaQueries: [{

				width: 1500,

				cols: _cols

			}, {

				width: 960,

				cols: _cols

			}, {

				width: 800,

				cols: 3

			}, {

				width: 480,

				cols: 2

			}, {

				width: 320,

				cols: 1

			}],

			caption: _caption,

			displayType: 'sequentially',

			displayTypeSpeed: 80,



			// lightbox

			lightboxDelegate: '.cbp-lightbox',

			lightboxGallery: true,

			lightboxTitleSrc: 'data-title',

			lightboxCounter: '<div class="cbp-popup-lightbox-counter">{{current}} of {{total}}</div>',



			// singlePage popup

			singlePageDelegate: '.cbp-singlePage',

			singlePageDeeplinking: true,

			singlePageStickyNavigation: true,

			singlePageCounter: '<div class="cbp-popup-singlePage-counter">{{current}} of {{total}}</div>',

			singlePageCallback: function(url, element) {

				// to update singlePage content use the following method: this.updateSinglePage(yourContent)

				var t = this;



				$.ajax({

						url: url,

						type: 'GET',

						dataType: 'html',

						timeout: 10000

					})

					.done(function(result) {

						t.updateSinglePage(result);

					})

					.fail(function() {

						t.updateSinglePage('AJAX Error! Please refresh the page!');

					});

			},

		});

	});



})(jQuery, window, document);

