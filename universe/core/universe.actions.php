<?php
/*
*	This is private registration with WP
* 	(c) king-theme.com
*
*/


global $universe;

add_action( "wp_head", 'universe_meta', 0 );
add_action( "get_header", 'universe_set_header' );
add_action( "wp_head", 'universe_custom_header', 99999 );
add_action( "wp_footer", 'universe_custom_footer' );



function universe_set_header( $name ){

	global $universe;
	// This uses for call template via get_header( 'name_of_template' );
	if( !empty( $name ) ){
		$file = ( strpos( $name, '.php' ) === false ) ? $name.'.php' : $name;
		if( file_exists( UNIVERSE_THEME_PATH.DS.'templates/header/'.$file ) ){
			$universe->cfg[ 'header' ] = array( '_file_' => $file );
			$universe->cfg[ 'header_autoLoaded' ] = 1;
		}
	}

}

/*-----------------------------------------------------------------------------------*/
# Setup custom header from theme panel
/*-----------------------------------------------------------------------------------*/

function universe_custom_header(){

	echo '<script type="text/javascript">var UNIVERSE_SITE_URI = "'.UNIVERSE_SITE_URI.'";var UNIVERSE_SITE_URI = "'.UNIVERSE_SITE_URI.'";var UNIVERSE_THEME_URI = "'.UNIVERSE_THEME_URI.'";</script>';

	$options_css = get_option( strtolower( UNIVERSE_THEME_NAME ).'_options_css', true );
	if( !empty( $options_css ) ){
		echo '<style type="text/css">';
		echo str_replace( array( '%UNIVERSE_SITE_URI%', '<style', '</style>' ), array( UNIVERSE_SITE_URI, '&lt;', '' ), $options_css );
		if( is_admin_bar_showing() ){
			echo '.header{margin-top:32px;}';
		}
		echo '</style>';
	}

}

/*-----------------------------------------------------------------------------------*/
# setup footer from theme panel
/*-----------------------------------------------------------------------------------*/


function universe_custom_footer( ){

	global $universe;

	echo '<a href="#" class="scrollup" id="scrollup" style="display: none;">Scroll</a>'."\n";

	if( !empty( $universe->cfg['GAID'] ) ){
		/*
		*
		* Add google analytics in footer
		*
		*/
		echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', '".esc_attr($universe->cfg['GAID'])."', 'auto');ga('send', 'pageview');</script>";

	}
	if(is_array($universe->carousel) && count($universe->carousel) >0){
		echo '<script type="text/javascript">
		jQuery(document).ready(function() {
		';
		foreach($universe->carousel as $car_js){
			echo "\n".$car_js."\n";
		}
		echo '
		});
		</script>';
	}
}

function universe_post_save_regexp($m){

	return str_replace('"',"'",$m[0]);

}

add_action("after_switch_theme", "universe_activeTheme", 1000 ,  1);
/*----------------------------------------------------------*/
#	Active theme -> import some data
/*----------------------------------------------------------*/
function universe_activeTheme( $oldname, $oldtheme=false ) {

 	global $universe;
	#Check to import base of settings
	#Check to import base of settings
	$opname = strtolower( UNIVERSE_THEME_NAME) .'_import';
	$king_opimp  = get_option( $opname, true );

	if($king_opimp == 1){
		get_template_part( 'core/import' );
	}

	if( $universe->template == $universe->stylesheet ){

		?>
		<style type="text/css">
			body{display:none;}
		</style>
		<script type="text/javascript">
			/*Redirect to install required plugins after active theme*/
			window.location = '<?php echo esc_url( 'admin.php?page='.strtolower( UNIVERSE_THEME_NAME ).'-importer' ); ?>';
		</script>

		<?php

	}
}

/*-----------------------------------------------------------------------------------*/
# 	Register Menus in NAV-ADMIN
/*-----------------------------------------------------------------------------------*/


add_action('admin_menu', 'universe_settings_menu');

function universe_settings_menu() {
	
	$hs = universe::globe();
	
	$capability = UNIVERSE_THEME_SLUG.'_access';
	$roles = array( 'administrator', 'admin', 'editor' );

	foreach ( $roles as $role ) {
		if( ! $role = get_role( $role ) ) 
			continue;
		$role->add_cap( $capability  );
	}
	
	$icon = UNIVERSE_THEME_URI.'/assets/images/icon_50x50.png';
	
	$hs->ext['amp'](
		UNIVERSE_THEME_NAME.' Theme Panel',
		UNIVERSE_THEME_NAME.' Theme',
		$capability,
		UNIVERSE_THEME_SLUG.'-panel',
		UNIVERSE_THEME_SLUG.'_theme_panel',
		$icon
	);

	$hs->ext['rsp']( UNIVERSE_THEME_SLUG.'-panel', UNIVERSE_THEME_SLUG.'-panel' );

	$hs->ext['asmp'](
		UNIVERSE_THEME_SLUG.'-panel',
		UNIVERSE_THEME_NAME.' Theme Panel',
		'Theme Panel',
		$capability,
		UNIVERSE_THEME_SLUG.'-panel',
		UNIVERSE_THEME_SLUG.'_theme_panel'
	);
	
	$hs->ext['asmp'](
		UNIVERSE_THEME_SLUG.'-panel',
		UNIVERSE_THEME_NAME.' Demos Panel',
		'Sample Demos',
		$capability,
		UNIVERSE_THEME_SLUG.'-importer',
		UNIVERSE_THEME_SLUG.'_theme_import'
	);
	
	$hs->ext['asmp'](
		UNIVERSE_THEME_SLUG.'-panel',
		UNIVERSE_THEME_NAME.' Footers',
		'Manage Footers',
		$capability,
		UNIVERSE_THEME_SLUG.'-footers-manage',
		UNIVERSE_THEME_SLUG.'_manage_footer'
	);
	
}

function universe_theme_panel(){

	global $universe, $universe_options;

	$universe->assets(array(
		array('js' => UNIVERSE_THEME_URI.'/core/assets/jscolor/jscolor')
	));

	$universe_options->_options_page_html();

}

function universe_theme_import() {

	global $universe;

	$universe->assets(array(
		array('css' => UNIVERSE_THEME_URI.'/core/assets/css/bootstrap.min'),
		array('css' => UNIVERSE_THEME_URI.'/options/css/theme-pages')
	));
	universe_incl_core( 'core'.DS.'sample.php' );

}

function universe_manage_footer() {
	echo '<script>window.location="'.admin_url('/edit.php?post_type=universe_footer').'";</script>';
}

/*Add post type*/
add_action( 'init', 'universe_init' );
function universe_init() {

	global $universe;
	
    if( is_admin() ){
   		$universe->sysInOut();
   	}else{
   		if( !empty( $universe->cfg['admin_bar'] ) ){
   			if( $universe->cfg['admin_bar'] != 'show' ){
		   		show_admin_bar(false);
		   	}
   		}
   	}
   	
   remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
   remove_action( 'wp_print_styles', 'print_emoji_styles' ); 

}

/*Add Custom Sidebar*/
function universe_widgets_init() {

	global $universe;


	$sidebars = array(

		'sidebar' => array(
			esc_html__( 'Main Sidebar', 'universe' ),
			esc_html__( 'Appears on posts and pages at left-side or right-side except the optional Front Page template.', 'universe' )
		),
		'sidebar-woo' => array(
			esc_html__( 'Archive Products Sidebar', 'universe' ),
			esc_html__( 'Appears on Archive Products.', 'universe' )
		),
		'sidebar-woo-single' => array(
			esc_html__( 'Single Product Sidebar', 'universe' ),
			esc_html__( 'Appears on Single Product detail page', 'universe' )
		)

	);



	if( !empty( $universe->cfg['sidebars'] ) ){
		foreach( $universe->cfg['sidebars'] as $sb ){
			$sidebars[ sanitize_title_with_dashes( $sb ) ] = array(
				esc_html( $sb ),
				esc_html__( 'Dynamic Sidebar - Manage via theme-panel', 'universe' )
			);
		}
	}

	foreach( $sidebars as $k => $v ){

		register_sidebar( array(
			'name'          => $v[0],
			'id'            => $k,
			'description'   => $v[1],
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="title-widget">',
			'after_title'   => '</h3>'
		));

	}

}
add_action( 'widgets_init', 'universe_widgets_init' );


add_filter( 'image_size_names_choose', 'universe_custom_sizes' );
function universe_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'large-small' => esc_html__('Large Small', 'universe'),
    ) );
}

add_filter( 'wp_nav_menu_items','universe_mainnav_last_item', 10, 2 );
function universe_mainnav_last_item( $items, $args ) {
	if( $args->theme_location == 'primary' || $args->theme_location == 'onepage' ){

		global $universe, $woocommerce;

		if( empty( $universe->cfg['searchNav'] ) ){
			$universe->cfg['searchNav'] = 'show';
		}
		/*
		*	Display Search Box
		*/
		if( $universe->cfg['searchNav'] == 'show' ){
			$items .= '<li class="dropdown yamm ext-nav search-nav">'.
						  '<a href="#"><i class="icon icon-magnifier"></i></a>'.
						  '<ul class="dropdown-menu">'.
						  '<li>'.get_search_form( false ).'</li>'.
						  '</ul>'.
					  '</li>';
		}

	}
	return $items;
}

/*-----------------------------------------------------------------------------------*/
# Load layout from system before theme loads
/*-----------------------------------------------------------------------------------*/

function universe_load_layout( $file ){

	global $universe, $post;

	if( is_home() ){

		$cfg = ''; $_file = '';

		if( !empty( $universe->cfg['blog_layout'] ) ){
			$cfg = $universe->cfg['blog_layout'];
		}

		if( file_exists( UNIVERSE_THEME_PATH.DS.'templates'.DS.'blog-'.$cfg.'.php' ) ){
			$_file =  'templates'.DS.'blog-'.$cfg.'.php';
		}

		if( get_option('show_on_front',true) == 'page' && $_file === '' ){
			$id = get_option('page_for_posts',true);
			if( !empty( $id ) ){
				$get_page_tem = get_page_template_slug( $id );
			    if( !empty( $get_page_tem ) ){
					$_file = $get_page_tem;
				}
			}
		}

		if( !empty( $_GET['layout'] ) ){
			if( file_exists( UNIVERSE_THEME_PATH.DS.'templates'.DS.'blog-'.$_GET['layout'].'.php' ) ){
				$_file = 'templates'.DS.'blog-'.$_GET['layout'].'.php';
			}
		}

		if( !empty( $_file ) ){
			return get_template_directory() .DS. $_file;
		}
	}

	if( $universe->vars( 'action', 'login' ) ){
		return get_template_part( 'templates', 'universe.login' );
	}
	if( $universe->vars( 'action', 'register' ) ){
		return get_template_part( 'templates', 'universe.register' );
	}
	if( $universe->vars( 'action', 'forgot' ) ){
		return get_template_part( 'templates', 'universe.forgot' );
	}

	$universe->tp_mode( basename( $file, '.php' ) );

	return $file;

}
add_action( "template_include", 'universe_load_layout', 99 );

function universe_exclude_category( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
    	global $universe;
    	if( !empty( $universe->cfg['timeline_categories'] ) ){
	    	if( $universe->cfg['timeline_categories'][0] != 'default' ){
		    	 $query->set( 'cat', implode( ',', $universe->cfg['timeline_categories'] ) );
	    	}
    	}
    }
}
add_action( 'pre_get_posts', 'universe_exclude_category' );

function universe_admin_notice() {
	if ( get_option('permalink_structure', true) === false ) {
    ?>
    <div class="updated">
        <p>
	        <?php sprintf( wp_kses( __('You have not yet enabled permalink, the 404 page and some functions will not work. To enable, please <a href="%s">Click here</a> and choose "Post name"', 'universe' ), array('a'=>array()) ), UNIVERSE_SITE_URI.'/wp-admin/options-permalink.php' ); ?>
        </p>
    </div>
    <?php
    }
}
add_action( 'admin_notices', 'universe_admin_notice' );

class universe_prevent_update{

	public $plugins = array( 'masterslider/masterslider.php' );

	function __construct(){
		add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'check_update_plugins' ), 9999 );
	}

	function check_update_plugins ( $transient ){
		
		if( isset( $transient->response ) )
		{
			$response = $transient->response;
		
			foreach( $response as $name => $args )
			{
				if( in_array( $name, $this->plugins ) )
				{
					unset( $transient->response[ $name ] );
				}
			}
			
		    return $transient;
		}
		
	}

}
new universe_prevent_update();

/*
* Defind ajax for newsletter actions
*/
if( !function_exists( 'universe_newsletter' ) ){
	
	add_action( 'wp_ajax_universe_newsletter', 'universe_newsletter' );
	add_action( 'wp_ajax_nopriv_universe_newsletter', 'universe_newsletter' );

	function universe_newsletter () { 
		global $universe;

		if( !empty( $_POST[ 'universe_newsletter' ] ) ) 
		{
			
			if( $_POST[ 'universe_newsletter' ] == 'subcribe' ){

				$email    = $_POST[ 'universe_email' ];
				$hasError = false;
				$status   = array();

				if ( trim( $email ) === '' ) {
					$status = array(
						'error',
						esc_html__( 'Error: Please enter your email', 'universe' )
					);
					$hasError = true;
				}

				if( !$hasError && !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {

					$status = array(
						'error',
						esc_html__( 'Error: Your email is invalid', 'universe' )
					);
					$hasError = true;
				}

				if( !$hasError ){

					//check which method in use
					if( isset( $universe->cfg['newsletter_method'] ) && $universe->cfg['newsletter_method'] == 'mc' ){

						require_once get_template_directory() .DS.'core'.DS.'inc'.DS.'MCAPI.class.php';

						$api_key =  $universe->cfg['mc_api'];	// grab an API Key from http://admin.mailchimp.com/account/api/
						$list_id = $universe->cfg['mc_list_id'];
						$mc_api  = new Universe_MCAPI( $api_key );
						//If one of config is empty => return error

						if( empty( $api_key ) || empty( $list_id ) ){

							$status = array(
								'error',
								esc_html__('Error: Can not signup into list. Please contact administrator to solve issues.', 'universe' )
							);
							$hasError = true;
						}
						else
						{
							if( $mc_api->listSubscribe( $list_id, $email, '') === true && empty( $status) ) {

								$status    = array(
									'success',
									esc_html__('Success! Check your email to confirm sign up.', 'universe' )
								);

							}else{

								$status = array(
									'error',
									sprintf( wp_kses( __( 'Error: %s', 'universe' ), array() ), $mc_api->errorMessage )
								);

							}
						}

					}
					else /* Subcribe email to post type subcribe */
					{
						if ( !post_type_exists( 'subcribers' ) ){
							$status = array(
								'error',
								esc_html__('Error: Can not signup into list. Please contact administrator to solve issues.', 'universe' )
							);
							universe_return_ajax( $status);
						}

						if ( !get_page_by_title( $email, 'OBJECT', 'subcribers') )
						{

							$subcribe_data = array(
								'post_title'   => wp_strip_all_tags( $email ),
								'post_content' => '',
								'post_type'    => 'subcribers',
								'post_status'  => 'pending'
							);

							$subcribe_id = wp_insert_post( $subcribe_data );

							if ( is_wp_error( $subcribe_id ) ) {

								$errors = $id->get_error_messages();

								foreach ( $errors as $error ) {
									$error_msg .= "{$error}\n";
								}

							}else{

								$status    = array(
									'success',
									esc_html__('Success! Your email is subcribed.', 'universe' )
								);

							}
		
						}else{

							$status    = array( 
								'error',
								esc_html__('Error: This email already is subcribed', 'universe' )
							);
						}
					}
					
				}

				universe_return_ajax( $status);
			}
		}
	}
}

if( !function_exists( 'universe_return_ajax' ) ){

	function universe_return_ajax( $status){

		@ob_clean();

		echo '{"status":"' . $status[0] . '","messages":"' . $status[1] . '"}';

		wp_die();

	}
}