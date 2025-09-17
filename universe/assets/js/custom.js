// -----------------------------------
// Slidebars
// Version 0.10.3
// http://plugins.adchsm.me/slidebars/
//
// Written by Adam Smith
// http://www.adchsm.me/
//
// Released under MIT License
// http://plugins.adchsm.me/slidebars/license.txt
//
// ---------------------
// Index of Slidebars.js
//
// 001 - Default Settings
// 002 - Feature Detection
// 003 - User Agents
// 004 - Setup
// 005 - Animation
// 006 - Operations
// 007 - API
// 008 - User Input

if ($===undefined)
	$ = jQuery;
	
( function ( $ ) {

	$.slidebars = function ( options ) {

		// ----------------------
		// 001 - Default Settings

		var settings = $.extend( {
			siteClose: true, // true or false - Enable closing of Slidebars by clicking on #sb-site.
			scrollLock: false, // true or false - Prevent scrolling of site when a Slidebar is open.
			disableOver: false, // integer or false - Hide Slidebars over a specific width.
			hideControlClasses: false // true or false - Hide controls at same width as disableOver.
		}, options );

		// -----------------------
		// 002 - Feature Detection

		var test = document.createElement( 'div' ).style, // Create element to test on.
		supportTransition = false, // Variable for testing transitions.
		supportTransform = false; // variable for testing transforms.

		// Test for CSS Transitions
		if ( test.MozTransition === '' || test.WebkitTransition === '' || test.OTransition === '' || test.transition === '' ) supportTransition = true;

		// Test for CSS Transforms
		if ( test.MozTransform === '' || test.WebkitTransform === '' || test.OTransform === '' || test.transform === '' ) supportTransform = true;

		// -----------------
		// 003 - User Agents

		var ua = navigator.userAgent, // Get user agent string.
		android = false, // Variable for storing android version.
		iOS = false; // Variable for storing iOS version.
		
		if ( /Android/.test( ua ) ) { // Detect Android in user agent string.
			android = ua.substr( ua.indexOf( 'Android' )+8, 3 ); // Set version of Android.
		} else if ( /(iPhone|iPod|iPad)/.test( ua ) ) { // Detect iOS in user agent string.
			iOS = ua.substr( ua.indexOf( 'OS ' )+3, 3 ).replace( '_', '.' ); // Set version of iOS.
		}
		
		if ( android && android < 3 || iOS && iOS < 5 ) $( 'html' ).addClass( 'sb-static' ); // Add helper class for older versions of Android & iOS.

		// -----------
		// 004 - Setup

		// Site container
		var $site = $( '#main, .site_wrapper' ); // Cache the selector.

		// Left Slidebar	
		if ( $( '.sb-left' ).length ) { // Check if the left Slidebar exists.
			var $left = $( '.sb-left' ), // Cache the selector.
			leftActive = false; // Used to check whether the left Slidebar is open or closed.
		}

		// Right Slidebar
		if ( $( '.sb-right' ).length ) { // Check if the right Slidebar exists.
			var $right = $( '.sb-right' ), // Cache the selector.
			rightActive = false; // Used to check whether the right Slidebar is open or closed.
		}
				
		var init = false, // Initialisation variable.
		windowWidth = $( window ).width(), // Get width of window.
		$controls = $( '.sb-toggle-left, .sb-toggle-right, .sb-open-left, .sb-open-right, .sb-close' ), // Cache the control classes.
		$slide = $( '.sb-slide' ); // Cache users elements to animate.
		
		// Initailise Slidebars
		function initialise () {
			if ( ! settings.disableOver || ( typeof settings.disableOver === 'number' && settings.disableOver >= windowWidth ) ) { // False or larger than window size. 
				init = true; // true enabled Slidebars to open.
				$( 'html' ).addClass( 'sb-init' ); // Add helper class.
				if ( settings.hideControlClasses ) $controls.removeClass( 'sb-hide' ); // Remove class just incase Slidebars was originally disabled.
				css(); // Set required inline styles.
			} else if ( typeof settings.disableOver === 'number' && settings.disableOver < windowWidth ) { // Less than window size.
				init = false; // false stop Slidebars from opening.
				$( 'html' ).removeClass( 'sb-init' ); // Remove helper class.
				if ( settings.hideControlClasses ) $controls.addClass( 'sb-hide' ); // Hide controls
				$site.css( 'minHeight', '' ); // Remove minimum height.
				if ( leftActive || rightActive ) close(); // Close Slidebars if open.
			}
		}
		initialise();
		
		// Inline CSS
		function css() {
			// Site container height.
			$site.css( 'minHeight', '' );
			var siteHeight = parseInt( $site.css( 'height' ), 10 ),
			htmlHeight = parseInt( $( 'html' ).css( 'height' ), 10 );
			if ( siteHeight < htmlHeight ) $site.css( 'minHeight', $( 'html' ).css( 'height' ) ); // Test height for vh support..
			
			// Custom Slidebar widths.
			if ( $left && $left.hasClass( 'sb-width-custom' ) ) $left.css( 'width', $left.attr( 'data-sb-width' ) ); // Set user custom width.
			if ( $right && $right.hasClass( 'sb-width-custom' ) ) $right.css( 'width', $right.attr( 'data-sb-width' ) ); // Set user custom width.
			
			// Set off-canvas margins for Slidebars with push and overlay animations.
			if ( $left && ( $left.hasClass( 'sb-style-push' ) || $left.hasClass( 'sb-style-overlay' ) ) ) $left.css( 'marginLeft', '-' + $left.css( 'width' ) );
			if ( $right && ( $right.hasClass( 'sb-style-push' ) || $right.hasClass( 'sb-style-overlay' ) ) ) $right.css( 'marginRight', '-' + $right.css( 'width' ) );
			
			// Site scroll locking.
			if ( settings.scrollLock ) $( 'html' ).addClass( 'sb-scroll-lock' );
		}
		
		// Resize Functions
		$( window ).resize( function () {
			var resizedWindowWidth = $( window ).width(); // Get resized window width.
			if ( windowWidth !== resizedWindowWidth ) { // Slidebars is running and window was actually resized.
				windowWidth = resizedWindowWidth; // Set the new window width.
				initialise(); // Call initalise to see if Slidebars should still be running.
				if ( leftActive ) open( 'left' ); // If left Slidebar is open, calling open will ensure it is the correct size.
				if ( rightActive ) open( 'right' ); // If right Slidebar is open, calling open will ensure it is the correct size.
			}
		} );
		// I may include a height check along side a width check here in future.

		// ---------------
		// 005 - Animation

		var animation; // Animation type.

		// Set animation type.
		if ( supportTransition && supportTransform ) { // Browser supports css transitions and transforms.
			animation = 'translate'; // Translate for browsers that support it.
			if ( android && android < 4.4 ) animation = 'side'; // Android supports both, but can't translate any fixed positions, so use left instead.
		} else {
			animation = 'jQuery'; // Browsers that don't support css transitions and transitions.
		}

		// Animate mixin.
		function animate( object, amount, side ) {
			
			// Choose selectors depending on animation style.
			var selector;
			
			if ( object.hasClass( 'sb-style-push' ) ) {
				selector = $site.add( object ).add( $slide ); // Push - Animate site, Slidebar and user elements.
			} else if ( object.hasClass( 'sb-style-overlay' ) ) {
				selector = object; // Overlay - Animate Slidebar only.
			} else {
				selector = $site.add( $slide ); // Reveal - Animate site and user elements.
			}
			
			// Apply animation
			if ( animation === 'translate' ) {
				if ( amount === '0px' ) {
					removeAnimation();
				} else {
					selector.css( 'transform', 'translate( ' + amount + ' )' ); // Apply the animation.
					selector.css( 'overflow', 'hidden' ); // Apply the animation.
				}

			} else if ( animation === 'side' ) {
				if ( amount === '0px' ) {
					//removeAnimation();
					selector.css( side, amount );
					$('.header').css( side, amount ); 
				} else {
					if ( amount[0] === '-' ) amount = amount.substr( 1 ); // Remove the '-' from the passed amount for side animations.
					selector.css( side, '0px' ); // Add a 0 value so css transition works.
					setTimeout( function () { // Set a timeout to allow the 0 value to be applied above.
						selector.css( side, amount ); // Apply the animation.
						$('.header').css( side, amount ); 
					}, 1 );
				}

			} else if ( animation === 'jQuery' ) {
				if ( amount[0] === '-' ) amount = amount.substr( 1 ); // Remove the '-' from the passed amount for jQuery animations.
				var properties = {};
				properties[side] = amount;
				selector.stop().animate( properties, 400 ); // Stop any current jQuery animation before starting another.
			}
			
			// Remove animation
			function removeAnimation () {
				selector.removeAttr( 'style' );
				css();
			}
		}

		// ----------------
		// 006 - Operations

		// Open a Slidebar
		function open( side ) {
			// Check to see if opposite Slidebar is open.
			if ( side === 'left' && $left && rightActive || side === 'right' && $right && leftActive ) { // It's open, close it, then continue.
				close();
				setTimeout( proceed, 400 );
			} else { // Its not open, continue.
				proceed();
			}

			// Open
			function proceed() {
				if ( init && side === 'left' && $left ) { // Slidebars is initiated, left is in use and called to open.
					$( 'html' ).addClass( 'sb-active sb-active-left' ); // Add active classes.
					$left.addClass( 'sb-active' );
					animate( $left, $left.css( 'width' ), 'left' ); // Animation
					setTimeout( function () { leftActive = true; }, 400 ); // Set active variables.
				} else if ( init && side === 'right' && $right ) { // Slidebars is initiated, right is in use and called to open.
					$( 'html' ).addClass( 'sb-active sb-active-right' ); // Add active classes.
					$right.addClass( 'sb-active' );
					animate( $right, '-' + $right.css( 'width' ), 'right' ); // Animation
					setTimeout( function () { rightActive = true; }, 400 ); // Set active variables.
				}
			}
		}
			
		// Close either Slidebar
		function close( url, target ) {
			if ( leftActive || rightActive ) { // If a Slidebar is open.
				if ( leftActive ) {
					animate( $left, '0px', 'left' ); // Animation
					leftActive = false;
				}
				if ( rightActive ) {
					animate( $right, '0px', 'right' ); // Animation
					rightActive = false;
				}
			
				setTimeout( function () { // Wait for closing animation to finish.
					$( 'html' ).removeClass( 'sb-active sb-active-left sb-active-right' ); // Remove active classes.
					if ( $left ) $left.removeClass( 'sb-active' );
					if ( $right ) $right.removeClass( 'sb-active' );
					if ( typeof url !== 'undefined' ) { // If a link has been passed to the function, go to it.
						if ( typeof target === undefined ) target = '_self'; // Set to _self if undefined.
						window.open( url, target ); // Open the url.
					}
				}, 400 );
			}
		}
		
		// Toggle either Slidebar
		function toggle( side ) {
			if ( side === 'left' && $left ) { // If left Slidebar is called and in use.
				if ( ! leftActive ) {
					open( 'left' ); // Slidebar is closed, open it.
				} else {
					close(); // Slidebar is open, close it.
				}
			}
			if ( side === 'right' && $right ) { // If right Slidebar is called and in use.
				if ( ! rightActive ) {
					open( 'right' ); // Slidebar is closed, open it.
				} else {
					close(); // Slidebar is open, close it.
				}
			}
		}

		// ---------
		// 007 - API
		
		this.slidebars = {
			open: open, // Maps user variable name to the open method.
			close: close, // Maps user variable name to the close method.
			toggle: toggle, // Maps user variable name to the toggle method.
			init: function () { // Returns true or false whether Slidebars are running or not.
				return init; // Returns true or false whether Slidebars are running.
			},
			active: function ( side ) { // Returns true or false whether Slidebar is open or closed.
				if ( side === 'left' && $left ) return leftActive;
				if ( side === 'right' && $right ) return rightActive;
			},
			destroy: function ( side ) { // Removes the Slidebar from the DOM.
				if ( side === 'left' && $left ) {
					if ( leftActive ) close(); // Close if its open.
					setTimeout( function () {
						$left.remove(); // Remove it.
						$left = false; // Set variable to false so it cannot be opened again.
					}, 400 );
				}
				if ( side === 'right' && $right) {
					if ( rightActive ) close(); // Close if its open.
					setTimeout( function () {
						$right.remove(); // Remove it.
						$right = false; // Set variable to false so it cannot be opened again.
					}, 400 );
				}
			}
		};

		// ----------------
		// 008 - User Input
		
		function eventHandler( event, selector ) {
			event.stopPropagation(); // Stop event bubbling.
			event.preventDefault(); // Prevent default behaviour.
			if ( event.type === 'touchend' ) selector.off( 'click' ); // If event type was touch, turn off clicks to prevent phantom clicks.
		}
		
		// Toggle left Slidebar
		$( '.sb-toggle-left' ).on( 'touchend click', function ( event ) {
			eventHandler( event, $( this ) ); // Handle the event.
			toggle( 'left' ); // Toggle the left Slidbar.
		} );
		
		// Toggle right Slidebar
		$( '.sb-toggle-right' ).on( 'touchend click', function ( event ) {
			eventHandler( event, $( this ) ); // Handle the event.
			toggle( 'right' ); // Toggle the right Slidbar.
		} );
		
		// Open left Slidebar
		$( '.sb-open-left' ).on( 'touchend click', function ( event ) {
			eventHandler( event, $( this ) ); // Handle the event.
			open( 'left' ); // Open the left Slidebar.
		} );
		
		// Open right Slidebar
		$( '.sb-open-right' ).on( 'touchend click', function ( event ) {
			eventHandler( event, $( this ) ); // Handle the event.
			open( 'right' ); // Open the right Slidebar.
		} );
		
		// Close Slidebar
		$( '.sb-close' ).on( 'touchend click', function ( event ) {
			if ( $( this ).is( 'a' ) || $( this ).children().is( 'a' ) ) { // Is a link or contains a link.
				if ( event.type === 'click' ) { // Make sure the user wanted to follow the link.
					event.stopPropagation(); // Stop events propagating
					event.preventDefault(); // Stop default behaviour
					
					var link = ( $( this ).is( 'a' ) ? $( this ) : $( this ).find( 'a' ) ), // Get the link selector.
					url = link.attr( 'href' ), // Get the link url.
					target = ( link.attr( 'target' ) ? link.attr( 'target' ) : '_self' ); // Set target, default to _self if not provided
					
					close( url, target ); // Close Slidebar and pass link target.
				}
			} else { // Just a normal control class.
				eventHandler( event, $( this ) ); // Handle the event.
				close(); // Close Slidebar.
			}
		} );
		
		// Close Slidebar via site
		$site.on( 'touchend click', function ( event ) {
			if ( settings.siteClose && ( leftActive || rightActive ) ) { // If settings permit closing by site and left or right Slidebar is open.
				eventHandler( event, $( this ) ); // Handle the event.
				close(); // Close it.
			}
		} );
		
	}; // End Slidebars function.

} ) ( jQuery );


(function($){
	//"use strict";
	$(document).ready(function($){

		$('.menuopv1 a.nav-toggle').on('click', function(){
			$(this).next().slideToggle();
		});

		$('.yamm-content').each(function(){
			$(this).parents('ul.dropdown-menu').width($(this).data('width'));
		});

		$.slidebars({
			disableOver: 999,
			hideControlClasses: true
		});

		var sidebar_pos = universe_set_pos_sidebar_menu;

		if($('.sb-toggle-'+sidebar_pos).length > 0){
			var sb_connection = $('.sb-toggle-'+sidebar_pos).data('connection');

			if(sb_connection != '' && $('#'+sb_connection).length > 0){
				$('.sb-slidebar.sb-'+sidebar_pos).html($('#'+sb_connection).html());
			}
		}

		//Search menu

		var search_form = '<div class="overlay" id="one">'
						+'<div class="modal">'
							+'<form role="search" method="get" action="'+ UNIVERSE_SITE_URI +'" onsubmit="return universe_check_search_form(this);">'
								+'<input class="sitesearch_input" name="s" id="s" value="Please Search Here..." onfocus="if(this.value == \'Please Search Here...\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Please Search Here...\';}" type="text">'
								+'<input name="search_action" value="Search" class="sitesearch_but" type="submit" />'
							+'</form>'
						+'</div>'
					+'</div>';
		$('li.nav-search>a').html('<i class="fa fa-search secbt"></i>').attr('data-overlay-trigger', 'one');
		$('.sb-slidebar li.nav-search>a').html('<i class="fa fa-search secbt"></i> Search').attr('data-overlay-trigger', 'one');

		$('footer').after(search_form);

		$('.overlay').overlay();

		
		$('ul.nav>li.current-menu-item>a').addClass('active');

		$('#scrollup').on( 'click', function(e){
			$('html,body').animate({ 'scroll-top' : 0 });
			e.preventDefault();
		});

		$('.navbar-toggle').on( 'click', function(){
			var targ = $(this).attr('data-target');
			if( $( targ ).get(0) ){
				$( targ ).slideToggle();
			}
		});

		$('a').on( 'click', function(e){
			if( $(this).attr('href') == '#' ){
				e.preventDefault();
			}
		});


		document.mainMenu = $('body');

		$(window).scroll(function () {

			var compact_height = 30;

			if(parseInt($('header.header').data('compact')) > 0){
				compact_height = parseInt($('header.header').data('compact'));
			}

			if ($(window).scrollTop() >= compact_height ) {
				$('#scrollup').show();
				document.mainMenu.addClass('compact');
			} else {
				$('#scrollup').hide();
				document.mainMenu.removeClass('compact');
			}
		});

		$('.close-but').on( 'click', function(){
			$(this).parent().parent().hide('slow',function(){$(this).remove();});
		});

		$('.video-player .video-close').on( 'click', function(){
			$(this).parent().find('iframe').remove();
			$(this).parent().animate({opacity:0},function(){$(this).hide();});
		});

		$('.king-video-play-wrapper .play-button').on( 'click', function(){
			
			var url = $(this).data('video');
			var height = $(this).data('height');
			
			if (url.indexOf('youtube.com') > -1 )
			{
				var id = url.split('v=')[1].replace('/','');
				id = 'https://www.youtube.com/embed/'+id+'?autoplay=1&controls=0&showinfo=0';
			}
			else if (url.indexOf('vimeo.com') > -1)
			{
				var id = url.split('vimeo.com/')[1].replace('/','');
				id = 'https://player.vimeo.com/video/'+id+'?autoplay=1&title=0&byline=0&portrait=0';
			}
			
			var w = $(window).width();
			var h = parseInt(w*0.5609);
			var mt = -parseInt((h-height)/2);
			
			$(this).closest('.king-video-play-wrapper')
					.find('.video-player')
					.append('<iframe style="height:'+h+'px;width:'+w+'px;margin-top:'+mt+'px" src="'+id+'"></iframe')
					.css({display:'block', opacity:0})
					.animate({opacity:1});
		});

		$('.king-preload').each(function(){

			var rel = $(this).attr('data-option').split('|');

			(function( elm ){
				$.post( UNIVERSE_SITE_URI+'/index.php', {
						'control'	: 'ajax',
						'task'		: rel[0],
						'id'		: rel[1],
						'amount'	: rel[2]
					}, function (result) {

					elm.innerHTML = result;
					$(elm).addClass('animated fadeIn');

				})
			})(this);

		});

		$('.navbar-nav li.yamm-fw a.active').each(function(){
			$(this).closest('li.yamm-fw').find('>a').addClass('active');
		});

		$('#king-mainmenu li a').on( 'click', function(e){
			if( !$(this.parentNode).find('ul').get(0) || $('body').width() > 1000 ){
				return true;
			}
			if( $(this.parentNode).hasClass('open') ){
				$(this.parentNode).removeClass('open');
				return true;
			}else $(this.parentNode).addClass('open');

			e.preventDefault();

			return false;
		});

		$('#menu-onepage a[href^=#]').on('click', function(e){
			
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
				&& location.hostname == this.hostname
				&& this.hash.indexOf('#') === 0
			){
				var target = $(this.hash);
					
				if (target.length) 
				{
					$('html,body').stop().animate({
						scrollTop: target.offset().top-60
					}, 500);
					$('#menu-onepage li.active').removeClass('active');
					$(this).parent().addClass('active');
					e.preventDefault();
					return false;
				}
			}
			
		});
		$('#menu-onepage a').each(function(){
	
			if( $(this).attr('href').indexOf('#') > -1 ){
				
				var id =  $(this).attr('href').split('#')[1];
				
				$( '#'+id ).viewportChecker({
					callbackFunction: function( elm ){
						
						$('#menu-onepage li.active').removeClass('active');
						$('#menu-onepage a').each(function(){
							if( $(this).attr('href') == '#'+elm.attr('id') ){
								$(this).closest('li').addClass('active');
							}
						});
											
					},
					classToAdd: ''
				});
			
			}
			
			
		});

		//enable scroll for map
		$('.fgmapfull').on( 'click', function () {
			$('.fgmapfull iframe').css("pointer-events", "auto");
		});

		videos_gallery( jQuery );


		$(function() {
			$('#sidebar ul.children').hide();
			$('#sidebar .arrows_list1 > li > a').on( 'click', function(event) {
				if($(this).parent().hasClass('page_item_has_children')){
					event.preventDefault();
					$(this).next('.children').slideToggle("slow");
				}
			});
		});

		$('.retina-support').each(function(){
			$(this).find('img').each(function(){
				if( $(this).attr('width') ){
					$(this).removeAttr('height').attr({ width : ( $(this).attr('width')/2) });
				}
			});
		});

		//Tooltip
		$('.uni_button').add('.uni_tooltip').hsTooltip();

		// Google maps and button close
		$('.contact_form_box>.close').on('click', function(){
			$(this).parent('.contact_form_box').toggle( "slide" );
			$('.show_contact_form').fadeIn('slow');
		});
		$('.show_contact_form').on('click', function(){
			$(this).prev('.contact_form_box').toggle( "slide" );
			$('.show_contact_form').fadeOut('slow');
		});

		//flips4 fix height
		$('.flips4').each(function( index ){
			var img = $(this).find('img'),
				ratio_height,
				flip_back = $(this).find('.flips4_back');

			ratio_height = img.width() * img.prop('naturalHeight') / img.prop('naturalWidth');

			$(this).height(ratio_height);
			flip_back.height(ratio_height - parseInt(flip_back.css('padding-top')) - parseInt(flip_back.css('padding-bottom')));
			//console.log();
		});

		if( typeof $().owlCarousel == 'function' ){

			universe_ourworks_init();

			universe_lastpost_init();

			universe_share_post_init();

			// Services Owl Carousel
			$('.services-owl-carousel').each(function() {
				$(this).owlCarousel({
					items : 3,
					itemsTablet: [768,1],
					itemsTabletSmall: false,
					itemsMobile : [479,1],
					autoPlay : 18000,
					stopOnHover : true,
					paginationSpeed : 1000,
					goToFirstSpeed : 2000,
					singleItem : false,
				});
			});

			// Tabs Slider Style 1
			$('.tabs-slider-style').each(function() {
				$(this).owlCarousel({
					autoPlay : 5000,
					stopOnHover : true,
					navigation: false,
					paginationSpeed : 1000,
					goToFirstSpeed : 2000,
					singleItem : true
				});
			});


			$(".list-property-owl-carousel").owlCarousel({
				// Most important owl features
				items : 4,
				itemsCustom : false,
				itemsDesktop : [1199,4],
				itemsDesktopSmall : [980,3],
				itemsTablet: [768,2],
				itemsTabletSmall: false,
				itemsMobile : [480,1],
				singleItem : false,
				itemsScaleUp : false,
			});

		}
	});

})(jQuery);


function universe_check_search_form(obj){
	var search_input = jQuery(obj).find('.sitesearch_input').val();

	if(search_input == '' || search_input == 'Please Search Here...')
		return false;

	return true;
}


function videos_gallery($){

	$('.videos-gallery-list').each(function(){
		$(this).find('iframe').each(function(){
			$(this).parent().find('br').remove();
			var yid = this.src;
			yid = yid.split('embed')[1].replace(/\//g,'');
			$(this).closest('.wpb_text_column').attr({'data-yid':yid}).on( 'click', function(){
				var yid = $(this).attr('data-yid');
				$(this).closest('.wpb_row').find('.videos-gallery-player .wpb_wrapper').html('<iframe src="https://www.youtube.com/embed/'+yid+'?autoplay=1"></iframe>');
			});
			$(this).after('<img src="https://i.ytimg.com/vi/'+yid+'/default.jpg" />').remove();
		});
	});

}


function syncPosition(el){
	var current = this.currentItem;
	$(".member-thumb")
		.find(".owl-item")
		.removeClass("active")
		.eq(current)
		.addClass("active")
	if($(".member-thumb").data("owlCarousel") !== undefined){
		center(current)
	}
}

function center(number){
	var sync2 = $('.our-team-layout-8').find(".member-thumb");
	var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
	var num = number;
	var found = false;
	for(var i in sync2visible){
		if(num === sync2visible[i]){
			var found = true;
		}
	}

	if(found===false){
		if(num>sync2visible[sync2visible.length-1]){
			sync2.trigger("owl.goTo", num - sync2visible.length+2)
		}else{
			if(num - 1 === -1){
				num = 0;
			}
			sync2.trigger("owl.goTo", num);
		}
	} else if(num === sync2visible[sync2visible.length-1]){
		sync2.trigger("owl.goTo", sync2visible[1])
	} else if(num === sync2visible[0]){
		sync2.trigger("owl.goTo", num-1)
	}
}

function syncPosition2(el){
	var current = this.currentItem;
	$(".member-thumb")
		.find(".owl-item")
		.removeClass("active")
		.eq(current)
		.addClass("active")
	if($(".member-thumb").data("owlCarousel") !== undefined){
		center2(current)
	}
}

function center2(number){
	var sync4 = $('.our-team-layout-9').find(".member-thumb");
	var sync2visible = sync4.data("owlCarousel").owl.visibleItems;
	var num = number;
	var found = false;
	for(var i in sync2visible){
		if(num === sync2visible[i]){
			var found = true;
		}
	}

	if(found===false){
		if(num>sync2visible[sync2visible.length-1]){
			sync4.trigger("owl.goTo", num - sync2visible.length+2)
		}else{
			if(num - 1 === -1){
				num = 0;
			}
			sync4.trigger("owl.goTo", num);
		}
	} else if(num === sync2visible[sync2visible.length-1]){
		sync4.trigger("owl.goTo", sync2visible[1])
	} else if(num === sync2visible[0]){
		sync4.trigger("owl.goTo", num-1)
	}
}

function universe_ourworks_init(){

	$('.kc-works-4').each(function(){

		if( $(this).data('kc-loaded') !== true )
			$(this).data({ 'kc-loaded' : true });
		else return;


		$(this).find('.cbp-filter-item').on('click', function(){
			var cat = $(this).data('filter');
			if(cat=='*'){
				$(this).closest('.kc-works-4').find('.owl-item').removeClass('deactive');
			} else {
				$(this).closest('.kc-works-4').find('.owl-item').addClass('deactive');
				$(this).closest('.kc-works-4').find('.cbp-item'+cat).parent().removeClass('deactive');
			}
			$(this).parent().find('.cbp-filter-item').removeClass('cbp-filter-item-active');
			$(this).addClass('cbp-filter-item-active');
			$(this).closest('.kc-works-4').find(".portfolio-content").trigger('owl.jumpTo', 1)
		});

		$(this).find(".portfolio-content").owlCarousel({
			autoPlay: false,
			navigation: false,
			pagination: true,
			slideSpeed : 1000,
			itemsCustom : [
				[0, 1],
				[450, 2],
				[600, 3],
				[768, 3],
				[960, 3],
				[1200, 3],
				[1400, 4],
				[1900, 4]
			]
		});

	});

}

function universe_lastpost_init(){

	$('.last-post-layout-4').each(function() {

		if( $(this).data('kc-loaded') !== true )
			$(this).data({ 'kc-loaded' : true });
		else return;

		$(this).owlCarousel({
			items : 3,
			itemsCustom : false,
			itemsDesktop : [1199,3],
			itemsDesktopSmall : [980,3],
			itemsTablet: [768,2],
			itemsTabletSmall: false,
			itemsMobile : [480,1],
			singleItem : false,
			itemsScaleUp : false
		});
	});

	// Last post layout 9
	$('.last-post-layout-9').each(function() {
		
		if( $(this).data('kc-loaded') !== true )
			$(this).data({ 'kc-loaded' : true });
		else return;
		
		$(this).owlCarousel({
			items : 2,
			itemsCustom : false,
			itemsDesktop : [1199,2],
			itemsDesktopSmall : [980,2],
			itemsTablet: [768,2],
			itemsTablet: [640,1],
			itemsTabletSmall: false,
			itemsMobile : [480,1],
			singleItem : false,
			itemsScaleUp : false
		});
	});

	// Last post layout 10
	$('.last-post-layout-10').each(function() {

		if( $(this).data('kc-loaded') !== true )
			$(this).data({ 'kc-loaded' : true });
		else return;

		$(this).owlCarousel({
			autoPlay : 5000,
			stopOnHover : true,
			navigation: false,
			paginationSpeed : 1000,
			goToFirstSpeed : 2000,
			singleItem : true
		});
	});

}


function universe_share_post_init(){

	$('.last-post-layout-11').each(function() {

		if( $(this).data('kc-loaded') !== true )
			$(this).data({ 'kc-loaded' : true });
		else return;

		$(this).find('.share-post-layout-11').on('click', function() {
			$(this).parent().find('ul').fadeToggle("slow");
		});

	});

	$('.kc-post-layout-6').each(function() {

		if( $(this).data('kc-loaded') !== true )
			$(this).data({ 'kc-loaded' : true });
		else return;

		$(this).find('.share-post-layout-6').on('click', function() {
			$(this).parent().find('ul').fadeToggle("slow");
		});

	});

	$('.single-post-share').each(function() {

		if( $(this).data('kc-loaded') !== true )
			$(this).data({ 'kc-loaded' : true });
		else return;

		$(this).find('.single-post-share').on('click', function() {
			$(this).parent().find('ul').fadeToggle("slow");
		});

	});

}


(function ( $ ) {
	$.fn.hsTooltip = function() {

		return this.each(function() {
			var rect = this.getBoundingClientRect();
			var tooltip = $(this).data('tooltip');
			var span_w = $(this).find('span').outerWidth();
			var span_h = $(this).find('span').outerHeight();
			var this_w = $(this).outerWidth();
			var this_h = $(this).outerHeight();


			if(typeof(tooltip) == 'undefined'){
				$(this).find('span').css('margin-left', -span_w/2);
				$(this).hover().find('span').css('bottom', this_h+10);
			}else{
				var position = $(this).data('position');
				var ext_bottom = -10;
				if(typeof position == 'undefined')
					position = 'top';

				$(this).addClass(position);

				if($(this).hasClass('style1')) ext_bottom = 5;

				switch(position) {
					case 'right': {
						var bottom;
						bottom = this_h/2 - span_h/2;

						$(this).find('span').css('left', this_w+10 );
						$(this).find('span').css('bottom', bottom );

						$(this).hover().find('span').css('left', this_w-ext_bottom);
						break;
					}

					case 'bottom': {
						$(this).find('span').css('margin-left', -span_w/2);
						$(this).hover().find('span').css('bottom', -span_h+ext_bottom);
						break;
					}

					case 'left': {
						var bottom, ext_left = 0;
						bottom = this_h/2 - span_h/2;
						if(!$(this).hasClass('style1')) ext_left = 10;

						$(this).find('span').css('left', -span_w-ext_left );
						$(this).find('span').css('bottom', bottom );

						break;
					}

					default: {
						$(this).find('span').css('margin-left', -span_w/2);
						$(this).hover().find('span').css('bottom', this_h-ext_bottom);
					}
				}
			}

		});

	};
}( jQuery ));

/*
 * Images fadeIn
 * Copyright 2016 King-Theme
 */
(function($) {
	"use strict";

	$('.image_fadein').each(function(){
		var _this = $(this),
			rotate = $(this).data('rotate');

		$(this).find('img:gt(0)').hide();

		setInterval(function () {
			_this.find('>:first-child').fadeOut()
									 .next('img')
									 .fadeIn()
									 .end()
									 .appendTo('.image_fadein');
		}, rotate); // 4 seconds
	});

	$(document).ready(function() {

		$(".tabs-realestate").each(function() {

			$(this).find(">:first").addClass('active');

			$(this).find('li').on('click', function(t){
				var i = $("a", this).attr("href");
				var cat = $("a", this).data("cat");

				$('.tabs-content-realestate').find('.property_cat').val(cat);

				$(this).siblings().removeClass("active");
				$(this).addClass("active");
				$(i).siblings().hide();
				$(i).fadeIn(400);
				t.preventDefault();
			});

		});


		$('.search_property').on( 'click', function(){
			$(this).parents('form').submit();
		});


		$(".travel_bf_tabs").each(function() {

			$(this).find(">:first").addClass('active');

			$(this).find('li').on('click', function(t){
				var i = $("a", this).attr("href");
				var type = $("a", this).data("type");
console.log(type);
				$('#travel_type').val(type);

				$(this).siblings().removeClass("active");
				$(this).addClass("active");
				$(i).siblings().hide();
				$(i).fadeIn(400);
				t.preventDefault();
			});

		});

	});
	

})(jQuery);


/* 
 * overlay.js v1.1.0
 * Copyright 2014 Joah Gerstenberg (www.joahg.com)
 */
(function($) { 
  $.fn.overlay = function() {
	overlay = $('.overlay');

	overlay.ready(function() {
		overlay.on('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(e) {
			if (!$(this).hasClass('shown')) return $(this).css('visibility', 'hidden');
		});

		overlay.on('show', function() {
			$(this).css('visibility', 'visible');
			$(this).addClass('shown');
			return true;
		});

		overlay.on('hide', function() {
			$(this).removeClass('shown');
			return true;
		});

		overlay.on('click', function(e) {
			if (e.target.className === $(this).attr('class')) return $(this).trigger('hide');
		})

		$('a[data-overlay-trigger=""]').on('click', function() {
			overlay.trigger('show');
		});

		$('a[data-overlay-trigger]:not([data-overlay-trigger=""])').on('click', function() {
			//console.log($('.overlay#' + $(this).attr('data-overlay-trigger')))
			$('.overlay#' + $(this).attr('data-overlay-trigger')).trigger('show');
		});
	});


	var sidebar_pos = universe_set_pos_sidebar_menu;

	$( ".nav-search > a" ).on('click', function(){
		$( '.sb-toggle-'+sidebar_pos ).trigger( "click" );
	});


  };
})(jQuery);



jQuery(document).ready(function($) {
	$('.feature_section4 .kc_row.kc_row_inner').hover(function() {
		$('.feature_section4_active').removeClass('active');
	}, function() {
		$('.feature_section4_active').addClass('active');
	});



	/*Subscribe Progress*/

	$(".uni_subscribe").submit(function(){

		universe_submit_newsletter( this );

		return false;
	});
	
	function universe_submit_newsletter( sform ){
		
		var email = jQuery( sform ).children(".enter_email_input").val();

		if( email.length < 8 || email.indexOf('@') == -1 || email.indexOf('.') == -1 ){
			jQuery( sform ).children('.enter_email_input').
			animate({marginLeft:-10, marginRight:10},100).
			animate({marginLeft:0, marginRight:0},100).
			animate({marginLeft:-10, marginRight:10},100).
			animate({marginLeft:0, marginRight:0},100);
			jQuery( sform ).children('.universe_newsletter_status').html('<span style="color:#F00;">Your email is invalid.</span>');
			return false;
		}

		jQuery( sform ).children('.universe_newsletter_status').html('<i style="color:#ccc" class="fa fa-spinner fa-pulse fa-2x"></i> Sending...');

		var admin_url = jQuery( sform ).data('url');

		$.ajax({

			type:'POST',

			data:{	
				"action" : "universe_newsletter",
				"universe_newsletter" : "subcribe",
				"universe_email" : email 
			},

			url: admin_url,

			success: function( data ) {

//				jQuery( sform ).children(".king-newsletter-preload").fadeOut( 500 );

				var obj = $.parseJSON( data );

				if( obj.status === 'success' ){

					var txt = '<div class="universe_newsletter_status" style="color:green;">'+obj.messages+'</div>';

				}else{

					var txt = '<div class="universe_newsletter_status" style="color:red;">'+obj.messages+'</div>';

				}	
					
				jQuery( sform ).children('.universe_newsletter_status').after( txt ).remove();

			}

		});	
	}




	/*--------------------------------------------
	 # Hosting
	 -------------------------------------------*/

	$('.search_domain_form').find('input[type="submit"]').on('click', function( event ){
		event.preventDefault();

		var domain = $('#domain_input').val();

		if($.active < 1){
			$('#domain_input').addClass('loading');
			$.ajax({
				url: universe_hosting_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					'domain': domain,
					'action': 'universe_search_domain',
					'security': $('#security').val()
				},
				success: function(data){
					$('#domain_search_results').html(data.results_html);
					$('#domain_input').removeClass('loading');	
					
				}
			});
		}

	});

});




/*!
 * @preserve
 *
 * Readmore.js jQuery plugin
 * Author: @jed_foster
 * Project home: http://jedfoster.github.io/Readmore.js
 * Licensed under the MIT license
 *
 * Debounce function from http://davidwalsh.name/javascript-debounce-function
 */

/* global jQuery */

(function(factory) {
  if (typeof define === 'function' && define.amd) {
	// AMD
	define(['jquery'], factory);
  } else if (typeof exports === 'object') {
	// CommonJS
	module.exports = factory(require('jquery'));
  } else {
	// Browser globals
	factory(jQuery);
  }
}(
function($) {
  'use strict';

  var readmore = 'readmore',
	  defaults = {
		speed: 100,
		collapsedHeight: 200,
		heightMargin: 16,
		moreLink: '<a href="#">Read More</a>',
		lessLink: '<a href="#">Close</a>',
		embedCSS: true,
		blockCSS: 'display: block; width: 100%;',
		startOpen: false,

		// callbacks
		beforeToggle: function(){},
		afterToggle: function(){}
	  },
	  cssEmbedded = {},
	  uniqueIdCounter = 0;

  function debounce(func, wait, immediate) {
	var timeout;

	return function() {
	  var context = this, args = arguments;
	  var later = function() {
		timeout = null;
		if (! immediate) {
		  func.apply(context, args);
		}
	  };
	  var callNow = immediate && !timeout;

	  clearTimeout(timeout);
	  timeout = setTimeout(later, wait);

	  if (callNow) {
		func.apply(context, args);
	  }
	};
  }

  function uniqueId(prefix) {
	var id = ++uniqueIdCounter;

	return String(prefix == null ? 'rmjs-' : prefix) + id;
  }

  function setBoxHeights(element) {
	var el = element.clone().css({
		  height: 'auto',
		  width: element.width(),
		  maxHeight: 'none',
		  overflow: 'hidden'
		}).insertAfter(element),
		expandedHeight = el.outerHeight(),
		cssMaxHeight = parseInt(el.css({maxHeight: ''}).css('max-height').replace(/[^-\d\.]/g, ''), 10),
		defaultHeight = element.data('defaultHeight');

	el.remove();

	var collapsedHeight = cssMaxHeight || element.data('collapsedHeight') || defaultHeight;

	// Store our measurements.
	element.data({
	  expandedHeight: expandedHeight,
	  maxHeight: cssMaxHeight,
	  collapsedHeight: collapsedHeight
	})
	// and disable any `max-height` property set in CSS
	.css({
	  maxHeight: 'none'
	});
  }

  var resizeBoxes = debounce(function() {
	$('[data-readmore]').each(function() {
	  var current = $(this),
		  isExpanded = (current.attr('aria-expanded') === 'true');

	  setBoxHeights(current);

	  current.css({
		height: current.data( (isExpanded ? 'expandedHeight' : 'collapsedHeight') )
	  });
	});
  }, 100);

  function embedCSS(options) {
	if (! cssEmbedded[options.selector]) {
	  var styles = ' ';

	  if (options.embedCSS && options.blockCSS !== '') {
		styles += options.selector + ' + [data-readmore-toggle], ' +
		  options.selector + '[data-readmore]{' +
			options.blockCSS +
		  '}';
	  }

	  // Include the transition CSS even if embedCSS is false
	  styles += options.selector + '[data-readmore]{' +
		'transition: height ' + options.speed + 'ms;' +
		'overflow: hidden;' +
	  '}';

	  (function(d, u) {
		var css = d.createElement('style');
		css.type = 'text/css';

		if (css.styleSheet) {
		  css.styleSheet.cssText = u;
		}
		else {
		  css.appendChild(d.createTextNode(u));
		}

		d.getElementsByTagName('head')[0].appendChild(css);
	  }(document, styles));

	  cssEmbedded[options.selector] = true;
	}
  }

  function Readmore(element, options) {
	this.element = element;

	this.options = $.extend({}, defaults, options);

	embedCSS(this.options);

	this._defaults = defaults;
	this._name = readmore;

	this.init();

	// IE8 chokes on `window.addEventListener`, so need to test for support.
	if (window.addEventListener) {
	  // Need to resize boxes when the page has fully loaded.
	  window.addEventListener('load', resizeBoxes);
	  window.addEventListener('resize', resizeBoxes);
	}
	else {
	  window.attachEvent('load', resizeBoxes);
	  window.attachEvent('resize', resizeBoxes);
	}
  }


  Readmore.prototype = {
	init: function() {
	  var current = $(this.element);

	  current.data({
		defaultHeight: this.options.collapsedHeight,
		heightMargin: this.options.heightMargin
	  });

	  setBoxHeights(current);

	  var collapsedHeight = current.data('collapsedHeight'),
		  heightMargin = current.data('heightMargin');

	  if (current.outerHeight(true) <= collapsedHeight + heightMargin) {
		// The block is shorter than the limit, so there's no need to truncate it.
		return true;
	  }
	  else {
		var id = current.attr('id') || uniqueId(),
			useLink = this.options.startOpen ? this.options.lessLink : this.options.moreLink;

		current.attr({
		  'data-readmore': '',
		  'aria-expanded': this.options.startOpen,
		  'id': id
		});

		current.after($(useLink)
		  .on('click', (function(_this) {
			return function(event) {
			  _this.toggle(this, current[0], event);
			};
		  })(this))
		  .attr({
			'data-readmore-toggle': '',
			'aria-controls': id
		  }));

		if (! this.options.startOpen) {
		  current.css({
			height: collapsedHeight
		  });
		}
	  }
	},

	toggle: function(trigger, element, event) {
	  if (event) {
		event.preventDefault();
	  }

	  if (! trigger) {
		trigger = $('[aria-controls="' + _this.element.id + '"]')[0];
	  }

	  if (! element) {
		element = _this.element;
	  }

	  var $element = $(element),
		  newHeight = '',
		  newLink = '',
		  expanded = false,
		  collapsedHeight = $element.data('collapsedHeight');

	  if ($element.height() <= collapsedHeight) {
		newHeight = $element.data('expandedHeight') + 'px';
		newLink = 'lessLink';
		expanded = true;
	  }
	  else {
		newHeight = collapsedHeight;
		newLink = 'moreLink';
	  }

	  // Fire beforeToggle callback
	  // Since we determined the new "expanded" state above we're now out of sync
	  // with our true current state, so we need to flip the value of `expanded`
	  this.options.beforeToggle(trigger, $element, ! expanded);

	  $element.css({'height': newHeight});

	  // Fire afterToggle callback
	  $element.on('transitionend', (function(_this) {
		return function() {
		  _this.options.afterToggle(trigger, $element, expanded);

		  $(this).attr({
			'aria-expanded': expanded
		  }).off('transitionend');
		}
	  })(this));

	  $(trigger).replaceWith($(this.options[newLink])
		.on('click', (function(_this) {
			return function(event) {
			  _this.toggle(this, element, event);
			};
		  })(this))
		.attr({
		  'data-readmore-toggle': '',
		  'aria-controls': $element.attr('id')
		}));
	},

	destroy: function() {
	  $(this.element).each(function() {
		var current = $(this);

		current.attr({
		  'data-readmore': null,
		  'aria-expanded': null
		})
		  .css({
			maxHeight: '',
			height: ''
		  })
		  .next('[data-readmore-toggle]')
		  .remove();

		current.removeData();
	  });
	}
  };


  $.fn.readmore = function(options) {
	var args = arguments,
		selector = this.selector;

	options = options || {};

	if (typeof options === 'object') {
	  return this.each(function() {
		if ($.data(this, 'plugin_' + readmore)) {
		  var instance = $.data(this, 'plugin_' + readmore);
		  instance.destroy.apply(instance);
		}

		options.selector = selector;

		$.data(this, 'plugin_' + readmore, new Readmore(this, options));
	  });
	}
	else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
	  return this.each(function () {
		var instance = $.data(this, 'plugin_' + readmore);
		if (instance instanceof Readmore && typeof instance[options] === 'function') {
		  instance[options].apply(instance, Array.prototype.slice.call(args, 1));
		}
	  });
	}
  };

}));

jQuery(document).ready(function($){
	$('.work-des').each(function() {
		if($(this).innerHeight() > 210){
			$(this).readmore({
			  moreLink: '<a href="#" class="read-more-link addto_favorites" style="margin-top: 10px;display: inline-block;"><i class="fa fa-chevron-down"></i> Show more</a>',
			  lessLink: '<a href="#" class="read-less-link addto_favorites" style="margin-top: 10px;display: inline-block;"><i class="fa fa-chevron-up"></i> Show less</a>',
			  maxHeight: 205,
			  speed: 200,
			  afterToggle: function(trigger, element, expanded) {
				if(! expanded) { // The "Close" link was clicked
				  $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
				}
			  }
			});
		}
	});
});
