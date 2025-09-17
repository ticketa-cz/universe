<?php

#	(c) king-theme.com

	global $universe, $wpdb;
		
	$file = UNIVERSE_THEME_PATH.DS.'core'.DS.'sample'.DS.'data'.DS.'widgets.export.txt';

	if (file_exists($file)) {
		
		if( !get_option( UNIVERSE_THEME_OPTNAME ) ){
			
			$universe->import_options( $file, 'all' );
			
		}
		
		
		/*Reset homepage -> index_php*/
		update_option( 'show_on_front', 'posts' );
 
		/* We need to reset primary menu */
		$thememods = 'theme_mods_'.get_option('stylesheet', true);
		$mod = get_option( $thememods );
		$menuID = $wpdb->get_results($wpdb->prepare("SELECT `term_id` FROM `$wpdb->terms` WHERE `$wpdb->terms`.`slug` = '%s'", 'main-menu'));
		$menuOnepageID = $wpdb->get_results($wpdb->prepare("SELECT `term_id` FROM `$wpdb->terms` WHERE `$wpdb->terms`.`slug` = '%s'", 'menu-onepage'));
		
		$oid = 0;
		if( isset( $menuOnepageID[0] ) ){
			$oid = $menuOnepageID[0]->term_id;
		}
		if( isset( $menuID[0] ) ){
			if( !isset( $mod ) ){
				$mod = array( 'nav_menu_locations' => array( 'primary' => $menuID[0]->term_id, 'onepage' => $oid )  );
			}else{
				$mod['nav_menu_locations']['primary'] = $menuID[0]->term_id;
				$mod['nav_menu_locations']['onepage'] = $oid;
			}
			add_option( $thememods , $mod ) || update_option( $thememods , $mod );
		}
		$checkPage = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'home-version-1' AND post_type = 'page' AND post_status = 'publish'");
		if( isset( $checkPage ) ){
			add_option( 'show_on_front', 'page' ) || update_option( 'show_on_front', 'page' );
			add_option( 'page_on_front' , $checkPage ) || update_option( 'page_on_front' , $checkPage );
		}
		$checkPage = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = 'blog' AND post_type = 'page' AND post_status = 'publish'", null));
		if( isset( $checkPage ) ){	
			add_option( 'page_for_posts' , $checkPage ) || update_option( 'page_for_posts' , $checkPage );
		}
		
		$wpdb->flush();
		
	}
	else
	{
		if(isset($_REQUEST['universe'])){
			if($_REQUEST['universe']=='import'){
				echo 'File not found: <i>'.UNIVERSE_THEME_SLUG.DS.'core'.DS.'sample'.DS.'data'.DS.'widgets.export.txt</i>';
				return;
			}
		}		
	}
	
		






