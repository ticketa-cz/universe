
jQuery(document).ready(function($){
	$('.footer_styles').on('change', function(){

		var img_url,
			preview = $('option:selected', this).data('preview');
			preview_holder = $(this).next('div').find('img');

		//console.log(option);
		if(preview != ''){
			img_url = preview_holder.data('url')+preview;
			preview_holder.attr('src', img_url);
			preview_holder.fadeIn('fast');
		}else{
			preview_holder.fadeOut('fast');
		}
		
	});
});