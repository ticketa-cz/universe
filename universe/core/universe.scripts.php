<?php

add_action( 'wp_enqueue_scripts', 'universe_enqueue_content', 9999 );
add_action( 'wp_enqueue_scripts', 'universe_enqueue_content_last', 99999 );
add_action('admin_enqueue_scripts', 'universe_enqueue_admin');
add_action( 'admin_head', 'universe_admin_head', 99999 );

function universe_enqueue_content() {

	global $universe;

	$css_dir = UNIVERSE_THEME_URI.'/assets/css/';
	$js_dir = UNIVERSE_THEME_URI.'/assets/js/';

	/* Register google fonts */
	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style( 'universe-google-fonts', "$protocol:".universe_google_fonts_url() );

	wp_enqueue_style( UNIVERSE_THEME_SLUG.'-reset', universe_child_theme_enqueue( $css_dir.'reset.css' ), false, UNIVERSE_THEME_VERSION );
	wp_enqueue_style('bootstrap', universe_child_theme_enqueue( $css_dir.'main_menu/bootstrap.min.css' ), false, UNIVERSE_THEME_VERSION );

	if( is_home() || is_single() ){
		wp_enqueue_style( UNIVERSE_THEME_SLUG.'-blog-reset', universe_child_theme_enqueue( $css_dir.'blog-reset.css' ), false, UNIVERSE_THEME_VERSION );
	}

	wp_enqueue_style( UNIVERSE_THEME_SLUG.'-stylesheet', universe_child_theme_enqueue( UNIVERSE_THEME_URI.'/style.css' ), false, UNIVERSE_THEME_VERSION );

	wp_enqueue_style( UNIVERSE_THEME_SLUG.'-universe', universe_child_theme_enqueue( $css_dir.'universe.css'  ), false, UNIVERSE_THEME_VERSION );

	wp_register_style('universe-menu-default', universe_child_theme_enqueue( $css_dir.'main_menu/menu-default.css' ), false, UNIVERSE_THEME_VERSION );
	wp_register_style('universe-menu-1', universe_child_theme_enqueue( $css_dir.'main_menu/menu-1.css' ), false, UNIVERSE_THEME_VERSION );
	wp_register_style('universe-menu-2', universe_child_theme_enqueue( $css_dir.'main_menu/menu-2.css' ), false, UNIVERSE_THEME_VERSION );
	wp_register_style('universe-menu-one-page', universe_child_theme_enqueue( $css_dir.'main_menu/menu-one-page.css' ), false, UNIVERSE_THEME_VERSION );

	wp_register_script('universe-custom', universe_child_theme_enqueue( $js_dir.'custom.js' ), array( 'jquery' ), UNIVERSE_THEME_VERSION, true );
	wp_enqueue_script('universe-custom');

	if ( is_singular() ){
		wp_enqueue_script( "comment-reply" );
	}

	ob_start();
		$header = $universe->path( 'header' );
		if( $header == true ){
			$universe->path['header'] = ob_get_contents();
		}
	ob_end_clean();

}

function universe_enqueue_content_last(){

	global $universe;
	$css_dir = UNIVERSE_THEME_URI.'/assets/css/';

	if ( isset( $universe->cfg['responsive'] ) && $universe->cfg['responsive'] == 1 ) {
		wp_enqueue_style( UNIVERSE_THEME_SLUG.'-responsive', $css_dir.'responsive.css', false, UNIVERSE_THEME_VERSION );
	}

	echo "<script type=\"text/javascript\">if(!document.getElementById('rs-plugin-settings-inline-css')){document.write(\"<style id='rs-plugin-settings-inline-css' type='text/css'></style>\")}</script>";

}

function universe_enqueue_admin() {

	global $universe;

	$screen = get_current_screen();

	$css_dir = UNIVERSE_THEME_URI.'/assets/css/';
	
	wp_enqueue_style( UNIVERSE_THEME_SLUG.'-admin', UNIVERSE_THEME_URI.'/core/assets/css/universe-admin.css', false, UNIVERSE_THEME_VERSION );
	if( $screen->base == 'post' ){
		wp_enqueue_style('aristo', UNIVERSE_THEME_URI.'/options/css/jquery-ui-aristo/aristo.css', false, UNIVERSE_THEME_VERSION );
	}
	wp_enqueue_style('simple-line-icons.', UNIVERSE_THEME_URI.'/core/assets/css/icons.css', false, UNIVERSE_THEME_VERSION );

	if( $universe->page == strtolower( UNIVERSE_THEME_NAME ).'-importer' ){
		add_thickbox();
	}

}

function universe_admin_head() {

	global $universe;

	echo '<script type="text/javascript">var UNIVERSE_SITE_URI = "'.UNIVERSE_SITE_URI.'";var UNIVERSE_SITE_URI = "'.UNIVERSE_SITE_URI.'";var UNIVERSE_HOME_URL = "'.UNIVERSE_HOME_URL.'";var UNIVERSE_THEME_URI = "'.UNIVERSE_THEME_URI.'";var UNIVERSE_THEME_NAME = "'.UNIVERSE_THEME_NAME.'";</script>';

	echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#sc_select").change(function() {send_to_editor(jQuery("#sc_select :selected").val());return false;});});</script><style type="text/css">.vc_license-activation-notice,.ls-plugins-screen-notice,.rs-update-notice-wrap{display: none;}</style>';

}

function universe_google_fonts_url() {

    $font_url = '';

    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'universe' ) ) {
        $font_url = '//fonts.googleapis.com/css?family=Poppins:400,300,500,600%26subset=latin,latin-ext|Playfair+Display:400,italic%2C700%2C700italic%2C900%2C900italic';
    }

    return $font_url;

}

// Add font icon into plugin kingcomposer
add_action('init', 'universe_font_icon_init');
function universe_font_icon_init() {

	if( function_exists( 'kc_add_icon' ) ) {

		kc_add_icon( get_template_directory_uri().'/assets/css/stroke-icon.css' );

	}

}