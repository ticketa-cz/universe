/*
 *
 * universe_options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function universe_radio_img_select(relid, labelclass){
	
	var $ = jQuery, curent = $('#'+relid).parent().get(0).getBoundingClientRect().top;
	
	$('#'+relid).prop('checked');

	$('.nhp-radio-img-'+labelclass).removeClass('nhp-radio-img-selected');	
	
	$('label[for="'+relid+'"]').addClass('nhp-radio-img-selected');
	curent = $(window).scrollTop()-(curent - $('#'+relid).parent().get(0).getBoundingClientRect().top);
	$('html,body').scrollTop(curent).animate({ scrollTop: ($('#'+relid).parent().offset().top-100) });
	
}//function

jQuery( document ).ready(function( $ ){
	$('fieldset.radio-img').each(function(){
		$(this).find('label').each(function(){
			if( $(this).hasClass('nhp-radio-img-selected') ){
				$(this).closest('fieldset.radio-img').prepend(this);
			}
		});
	});
});