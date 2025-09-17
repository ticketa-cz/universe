<?php
/**
*
*	Author: King-Theme.com
*	Package: Templates system by King-Theme
*	Version: 1.0
*
*	Positions: descriptions|textarea|<p>Storytelling fused with technology and design of Universe</p>
*
*	Register position_id and field_type to be add content from admin panel. ( text| textarea | upload | menu..etc.. )
*	All settings from backend will be return to varible $args
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*
*/

global $universe, $post;

if ( isset( $post->ID ) ) {

	$page_options = get_post_meta( $post->ID , '_kc_addon_options_post_meta_options' );
	if ( !empty( $page_options[0]['breadcrumb_bg'] ) ) {
		$breadcrumb_url =  str_replace( '%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, $page_options[0]['breadcrumb_bg'] );
		$breadcrumb_bg = 'background-image: url('. esc_url( $breadcrumb_url ) .');';
	} else {
		$breadcrumb_bg = '';
	}

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

?>

	<div id="breadcrumb" class="page_title" style="<?php echo universe::esc_js($breadcrumb_bg); ?>">
		<div class="container">
			<div class="title"><h1><?php universe_title(); ?></h1></div>
			<?php if ( !empty( $atts['descriptions'] ) ): ?>
				<?php echo wp_kses_post( $atts['descriptions'] ); ?>
			<?php endif ?>
		</div>
	</div>

<?php } ?>