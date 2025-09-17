<?php

global $universe, $post;
$breadcrumb_bg = '';

if (isset($post) && isset($post->ID)){
	$page_options = get_post_meta( $post->ID , '_kc_addon_options_post_meta_options');
	
	if ( !empty( $page_options[0]['breadcrumb_bg'] ) ) {
		$breadcrumb_url =  str_replace( '%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, $page_options[0]['breadcrumb_bg'] );
		$breadcrumb_bg = 'background-image: url('. esc_url( $breadcrumb_url ) .');';
	}
}

if ( isset( $post->ID ) ) {
	if ( get_post_type( $post->ID ) == 'kc-works' ) {

		if ( isset( $universe->cfg['our_works_breadcrumb_bg'] ) && !empty( $universe->cfg['our_works_breadcrumb_bg'] ) ) {
			$breadcrumb_bg = 'background-image: url('. esc_url( $universe->cfg['our_works_breadcrumb_bg'] ) .');';
		}

	}

	if( is_home() || is_single() || is_category() ){
		$blog_breadcrumb_bg = '';

		if(  !empty( $universe->cfg['blog_breadcrumb_bg'] ) ){
			$blog_breadcrumb_bg = $universe->cfg['blog_breadcrumb_bg'];

			$breadcrumb_url =  str_replace( '%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, $blog_breadcrumb_bg );
			$breadcrumb_bg = 'background-image: url('. esc_url( $breadcrumb_url ) .');';
		}
	}
}

?>

<div id="breadcrumb" class="page_title2" style="<?php echo universe::esc_js($breadcrumb_bg); ?>">
	<div class="container">
		<h1><?php universe_title(); ?></h1>
		<div class="pagenation">&nbsp;<?php $universe->breadcrumb(); ?></div>
	</div>
</div>