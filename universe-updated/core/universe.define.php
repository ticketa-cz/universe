<?php
/**
*
* (C) King-Theme.com
*
*/

/********************************************************/
/*                        Actions                       */
/********************************************************/

	// Constants
	
	if (!defined('DS'))
		define('DS', DIRECTORY_SEPARATOR);
	
	$theme = wp_get_theme();
	if( !empty( $theme->get('Template') ) ){
		$theme = wp_get_theme($theme->get('Template'));
	}
	define('UNIVERSE_THEME_NAME', $theme->get('Name') );
	define('UNIVERSE_THEME_SLUG', $theme->get('Template') );
	define('UNIVERSE_THEME_VERSION', $theme->get('Version') );

	define('UNIVERSE_HOME_URL', home_url() );
	define('UNIVERSE_SITE_URI', site_url() );
	define('UNIVERSE_SITE_URL', site_url() );
	define('UNIVERSE_THEME_URI', get_template_directory_uri() );
	define('UNIVERSE_THEME_PATH', get_template_directory() );
	define('UNIVERSE_THEME_CPATH', get_template_directory().DS.'core'.DS );
	define('UNIVERSE_THEME_SPATH', get_stylesheet_directory() );
	define('UNIVERSE_THEME_OPTNAME', 'universe');

	if( !class_exists( 'universe' ) ){
		universe_incl_core( 'core'.DS.'universe.class.php' );
	}

	### Start Run FrameWork ###
	global $universe;
	$universe = new universe();
	$universe->init();
	### End FrameWork ###