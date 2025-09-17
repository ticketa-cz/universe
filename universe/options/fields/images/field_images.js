jQuery(document).ready(function( $ ){

	/*
	 *
	 * universe_options_upload function
	 * Adds media upload functionality to the page
	 *
	*/

	$( '.king-images-wrp' ).each( function( index ){
		var options		= $( this ).data('images-field'),
			_id			= options.id,
			_opt_name	= options.opt_name;

		$('#king-images-button-' + _id).on( 'click', function(e){

			e.preventDefault();

			document.king_uploader_elm = this;

			//If the uploader object has already been created, reopen the dialog
			if ( document.king_uploader ) {
				document.king_uploader.open();
				return;
			}

			//Extend the wp.media object
			document.king_uploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Choose Image'
				},
				multiple: true,
				editing:   true,
				allowLocalEdits: true,
				displaySettings: true,
				displayUserSettings: true,
			});


			document.king_uploader.on('select', function() {

				attachments = document.king_uploader.state().get('selection');
				attachments.map( function( attachment ) {
					// Get all of our selected files
					attachment = attachment.toJSON();

					var elm = document.king_uploader_elm;
					var formfield = $( elm ).closest('.king-images-wrp').find('.king-images-input').attr('id');
					var formName = $( elm ).closest('.king-images-wrp').find('.king-images-input').attr('name');

					// Setup our fileGroup array
					var fileGroup = [];

					// Loop through each attachment
					$( attachment ).each( function() {
						if ( attachment.type == 'image' ) {
							// image preview
							uploadStatus = '<li class="img_status">'+
								'<span class="king-images-button-remove"><i class="fa fa-times"></i></span>'+
								'<img width="70" height="70" src="' + attachment.url + '" class="attachment-70x70" alt="'+ attachment.filename +'">'+
								'<input type="hidden" id="filelist-'+ attachment.id +'" name="'+ formName +'['+ attachment.id +']" value="' + attachment.url + '">'+
							'</li>';

						}

						// Add our file to our fileGroup array
						fileGroup.push( uploadStatus );
					});

					// Append each item from our fileGroup array to .kingtheme_media_status
					$( fileGroup ).each( function() {
						$('#' + formfield + '_status').slideDown().append(this);
					});

					king_image_remove();

				});

			});

			//Open the uploader dialog
			document.king_uploader.open();

		})
	});

	function king_image_remove() {
		$('.king-images-button-remove').on( 'click', function( event ){
			event.preventDefault();
			var $self = $(this);
			if ( $self.is( '.attach_list .king-images-button-remove' ) ){
				$self.parents('li').remove();
				return false;
			}
			return false;
		});
	}
	king_image_remove();

});