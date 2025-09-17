jQuery(document).ready(function($) {
	$('.universe-post-like').click(function() {
		heart = $(this);
		post_id = heart.data("post_id");
		heart.html("<i id='icon-like' class=''></i><i id='icon-gear' class='icon-gear'></i>");
		$.ajax({
			type: "post",
			url: ajax_var.url,
			data: "action=universe-post-like&nonce="+ajax_var.nonce+"&universe_post_like=&post_id="+post_id,
			success: function(count){
				if( count.indexOf( "already" ) !== -1 )
				{
					var lecount = count.replace("already","");
					if (lecount === "0")
					{
						lecount = "0";
					}
					heart.prop('title', 'Like');
					heart.removeClass("liked");
					heart.html('<i class="fa fa-thumbs-up"></i><span> '+lecount+'</span>');
				} else {
					heart.prop('title', 'Unlike');
					heart.addClass("liked");
					heart.html('<i class="fa fa-thumbs-up"></i><span> '+count+'</span>');
				}
			}
		});
	});
});