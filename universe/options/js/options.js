jQuery(document).ready(function($){

	//$('#nhp-opts-group-menu li .subsection').css('display', 'none');

	if( $('#last_tab').val() == '' )
	{
		$('.nhp-opts-group-tab:first').slideDown('fast');
		$('#nhp-opts-group-menu li:first').addClass('active on');
		$('#nhp-opts-group-menu li:first').children('ul').css('display', 'block');

	}
	else
	{
		var tabid = $('#last_tab').val();

		$('#'+tabid+'_section_group').slideDown('fast');
		$('#'+tabid+'_section_group_li').addClass('active');

		if($('#'+tabid+'_section_group_li').parent('ul').hasClass('subsection')){
			$('#'+tabid+'_section_group_li').parent('ul').parent('li').addClass('active');
		}

		if($('#'+tabid+'_section_group_li').hasClass('haschild')){
			$('#'+tabid+'_section_group_li').addClass('on');
		}
	}


	$('input[name="'+universe_opts.opt_name+'[defaults]"]').on( 'click', function(){
		if(!confirm(universe_opts.reset_confirm)){
			return false;
		}
	});

	$('.nhp-opts-group-tab-link-a').on( 'click', function(){

		var relid = $(this).attr('data-rel');

		$('#last_tab').val( relid );

		$('.nhp-opts-group-tab').each(function(){
			if($(this).attr('id') == relid+'_section_group')
			{
				$(this).delay(150).fadeIn(300);
			}
			else
			{
				$(this).fadeOut(150);
			}

		});

		$('.nhp-opts-group-tab-link-li').each(function(){

			if($(this).attr('id') != relid+'_section_group_li' && $(this).hasClass('active'))
				$(this).removeClass('active');

			if($(this).attr('id') == relid+'_section_group_li')
				$(this).addClass('active');

		});



		if( $(this).closest('li.haschild').length > 0 )
			$(this).closest('li.haschild').addClass('active');


	});


	if($('#nhp-opts-save').is(':visible')){
		$('#nhp-opts-save').delay(4000).slideUp('slow');
	}

	if($('#nhp-opts-imported').is(':visible')){
		$('#nhp-opts-imported').delay(4000).slideUp('slow');
	}

	$('input, textarea, select').change(function(){
		$('#nhp-opts-save-warn').slideDown('slow');
	});


	$('#nhp-opts-import-code-button').on( 'click', function(){
		if($('#nhp-opts-import-link-wrapper').is(':visible')){
			$('#nhp-opts-import-link-wrapper').fadeOut('fast');
			$('#import-link-value').val('');
		}
		$('#nhp-opts-import-code-wrapper').fadeIn('slow');
	});

	$('#nhp-opts-import-link-button').on( 'click', function(){
		if($('#nhp-opts-import-code-wrapper').is(':visible')){
			$('#nhp-opts-import-code-wrapper').fadeOut('fast');
			$('#import-code-value').val('');
		}
		$('#nhp-opts-import-link-wrapper').fadeIn('slow');
	});


	$('#nhp-opts-export-code-copy').on( 'click', function(){
		if($('#nhp-opts-export-link-value').is(':visible')){$('#nhp-opts-export-link-value').fadeOut('slow');}
		$('#nhp-opts-export-code').toggle('fade');
	});

	$('#nhp-opts-export-link').on( 'click', function(){
		if($('#nhp-opts-export-code').is(':visible')){$('#nhp-opts-export-code').fadeOut('slow');}
		$('#nhp-opts-export-link-value').toggle('fade');
	});

	$('#verify-purchase-key').on( 'click', function( e ){

		if( $(this).closest('td').hasClass('verifying') )
			return;

		$('#nhp-opts-form-wrapper').data({ 'go' : 'no' });

		var key = $('#input-purchase-key').val();

		if( key == '' ){
			$('#verify-purchase-status').css({color:'red'}).html('Error! Empty Key.');
			return false;
		}

		$(this).closest('td').addClass('verifying');

		$.post(

			ajaxurl,
			{
				action : 'verifyPurchase',
				code : key
			},
			function( result ){

				$('#verify-purchase-wrp').removeClass('verifying');

				if( result == null )
				{
					$('#verify-purchase-status').css({color:'red'}).html( 'Could not contact with server at this time. Please check your connection and try again.' );
				}
				else if( result.status == 0 )
				{
					$('#verify-purchase-status').css({color:'red'}).html( result.message );
					$('#verify-purchase-msg-wrp .msg-notice').addClass('active');
					$('#verify-purchase-msg-wrp .msg-success').removeClass('active');
				}
				else
				{
					$('#verify-purchase-status').css({color:'green'}).html( result.message );
					$('#verify-purchase-msg-wrp .msg-notice').removeClass('active');
					$('#verify-purchase-msg-wrp .msg-success').addClass('active');
				}
			}
		);

		e.preventDefault();
		return false;
	});

	$("#input-purchase-key").on( 'keydown', function(e){
	    if( e.keyCode == 13 ){
		    $('#verify-purchase-key').trigger('click');
	    	e.preventDefault();
	    	return false;
	    }
	});

	$('#nhp-opts-form-wrapper').on('submit', function(){
		if( $(this).data('go') != 'no' )
			return true;
		else{
			$(this).data({ 'go' : '' });
			return false;
		}
	});

	$('#theme-export-button').on( 'click', function(e){

		var form = $('<form action="'+window.location.href+'" method="POST"><input name="doAction" type="hidden" value="export" /></form>');
		$('body').append( form );
		form.trigger('submit');

		e.preventDefault();
		return false;

	});

	$('#theme-import-button').on( 'click', function(e){

		var wrp = $(this).closest('.king-file-upload');
		if( $('#file-upload-to-import').val() == '' )
		{
			$('#import-warning-msg')
				.html('Error! Please choose a file to import.')
				.animate({marginLeft:-10,marginRight:10}, 100)
				.animate({marginLeft:10,marginRight:-10}, 100)
				.animate({marginLeft:-5,marginRight:5}, 100)
				.animate({marginLeft:3,marginRight:-3}, 100)
				.animate({marginLeft:0,marginRight:0}, 100);
		}
		else
		{
			var form = $('<form enctype="multipart/form-data" action="'+window.location.href+'" method="POST" style="display:none;"><input name="doAction" type="hidden" value="import" /><input type="text" name="option" value="'+wrp.find('input[name="import_type"]:checked').val()+'" /></form>');
			$('body').append( form );
			form.append( $('#file-upload-to-import') );
			form.trigger('submit');
		}

		e.preventDefault();
		return false;

	});


	$( '.field-profile-rows input[type="text"]' ).add('.field-profile-rows textarea').on('focus', function(){
		var $_this = $( this );

		//$('.field-profile-rows .tiny_shortcodes').remove();

		if(!$_this.next('div').hasClass('tiny_shortcodes'))
		{
			$('.field-profile-rows .tiny_shortcodes').remove();

			$_this.after( '<div id="uni_tiny_shortcodes" class="tiny_shortcodes" data-status="true"><div class="content">Loading...</div></div>' );

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'load_tinyshortcodes'
				},
				success: function( data_response ){
					//console.log($_this);
					$("#uni_tiny_shortcodes > .content").html( data_response.html );

					$("#uni_tiny_shortcodes > i").on('click', function(){
						$("#uni_tiny_shortcodes").remove();
					});

					$("#uni_tiny_shortcodes a").on('mousedown', function(){
						var new_text = $( this ).data('shortcode');
						universe_insertAtCursor($_this, new_text);
						$("#uni_tiny_shortcodes").hide();
					});
				}
			});

		}else{
			$("#uni_tiny_shortcodes").show();
		}

	}).on( 'blur', function(){
		var $_this = $( this );
		setTimeout(function(){
			$_this.next('.tiny_shortcodes').hide();
		}, 100);
	} );

});


jQuery( window ).load(function(){

	var url = window.location.href;
	if( url.indexOf( '#' ) > -1 ){
		url = url.split('#')[1];
		if( url.indexOf('tab-') > -1 ){
			jQuery('#nhp-opts-group-menu li').eq( url.split('tab-')[1] ).find('a').trigger('click');
		}
	}

});




function universe_insertAtCursor(el, new_val) {
	var start = el.prop("selectionStart");
	var end = el.prop("selectionEnd");
	var text = el.val();
	var before = text.substring(0, start);
	var after  = text.substring(end, text.length);
	el.val(before + new_val + after);
	el[0].selectionStart = el[0].selectionEnd = start + new_val.length;
	el.focus();
}
