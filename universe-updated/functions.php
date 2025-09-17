<?php

/*
*
*	(c) king-theme.com
*
*/

define('KC_LICENSE', '43c10gkr-cyyw-de3x-83zk-xwe0-ny4oypljoek5');

if (!isset($content_width))
	$content_width = 1170;

###Load core of theme###
function universe_is_plugin_active( $plugin ){
	if ( in_array( $plugin , apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	}
	else {
		return false;
	}
}


function universe_incl_core( $file = '', $type = 'ro', $atts = array(), $content = '' ){

	if (strpos(trim($file), DIRECTORY_SEPARATOR) !== 0)
		$file = DIRECTORY_SEPARATOR.trim($file);
		
	$path = get_template_directory().$file;

	if( file_exists( $path ) && is_file( $path ) ){

		switch( $type ){
			case 'ro':
				require_once( $path );
				break;
			case 'r':
				require( $path );
				break;
			case 'i':
				include $path;
		}

	} else {
		echo 'Could not load theme file: '.$file;
	}

}

universe_incl_core( trailingslashit('core').'universe.define.php' );


//Register new path shortcode with mini
add_action('init', 'universe_set_shortcode_template', 99 );
function universe_set_shortcode_template(){

    global $kc;

	if( method_exists( $kc, 'set_template_path' ) ){
		$kc->set_template_path( get_template_directory().'/templates/kingcomposer/' );
	}

}


if( !function_exists('randomId') ){

	function randomId( $length ) {
		$key = null;
		$keys = array_merge( range( 0, 9 ), range( 'a', 'z' ) );
		for( $i=0; $i < $length; $i++ ) {
			$key .= $keys[ array_rand( $keys ) ];
		}
		return $key;
	}
}
#
#	End Load
#
