<?php

if(!function_exists('is_plugin_active')){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


if ( is_plugin_active( 'kingcomposer/kingcomposer.php' ) ) {

	add_filter( 'shortcode_kc_works', 'kc_pro_works_filter' );
	function kc_pro_works_filter( $atts ){
		global $kc_front;
		$js_dir = UNIVERSE_THEME_URI.'/assets/js/';

		$atts = kc_remove_empty_code( $atts );

		wp_register_style('cubeportfolio', $js_dir.'cubeportfolio/cubeportfolio.min.css', false, UNIVERSE_THEME_VERSION );
		wp_register_script('cubeportfolio', $js_dir.'cubeportfolio/js/jquery.cubeportfolio.min.js', array( 'jquery' ), UNIVERSE_THEME_VERSION, true );
		wp_register_script('cubeportfolio-main', $js_dir.'cubeportfolio/main.js', array( 'jquery' ), UNIVERSE_THEME_VERSION, true );
		wp_enqueue_style( 'cubeportfolio' );
		wp_enqueue_script( 'cubeportfolio' );
		wp_enqueue_script( 'cubeportfolio-main' );

		return $atts;
	}


	add_filter( 'shortcode_kc_works', 'kc_pro_filter_owl' );
	function kc_pro_filter_owl( $atts ){

		if (isset($atts['layout']) && $atts['layout'] == '4' ) {
			wp_enqueue_script( 'owl-carousel' );
			wp_enqueue_style( 'owl-theme' );
			wp_enqueue_style( 'owl-carousel' );
		}

		return $atts;
	}

}
