<?php

/**
*
*	Theme functions
*	(c) king-theme.com
*
*/
global $universe;
/*----------------------------------------------------------*/
#	Theme Setup
/*----------------------------------------------------------*/
function universe_themeSetup() {

	load_theme_textdomain( 'universe', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ,'title','editor','author','thumbnail','excerpt','custom-fields','page-attributes') );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'topmenu' => esc_html__( 'Top menu', 'universe' ),
		'primary' => esc_html__( 'Primary Menu', 'universe' ),
		'onepage' => esc_html__( 'One Page Menu', 'universe' ),
		'footer'  => esc_html__( 'Footer Menu', 'universe' )
	));

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	add_theme_support( "custom-header", array() );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );

	add_theme_support( "title-tag" );

}
add_action( 'after_setup_theme', 'universe_themeSetup' );


/**
 * Child Theme
 **/
function universe_child_theme_enqueue( $url ){

	global $universe;

	if( $universe->template != $universe->stylesheet ){
		$path = str_replace( UNIVERSE_THEME_URI, ABSPATH.'wp-content'.DS.'themes'.DS.$universe->stylesheet, $url );
		$path = str_replace( array( '\\', '\/' ), array(DS, DS), $path );

		if( file_exists( $path ) ){
			return str_replace( DS, '/', str_replace( ABSPATH , UNIVERSE_SITE_URI.'/', $path ) );
		}else{
			return $url;
		}

	}else{

		return $url;

	}
}

/**
 * Color mode
 **/
if( !empty( $_GET['mode'] ) && !empty( $_GET['color'] ) ){
	if( $_GET['mode'] == 'css-color-style' ){
		$color = urldecode( $_GET['color'] );
		$rgb = $universe->hex2rgb( $color );
		$darkercolor = $universe->hex2rgb( $color, 30 );
		$brightercolor = $universe->hex2rgb( $color, -30 );
		$color_rgb = $universe->hex2rgb( $color );
		$file = universe_child_theme_enqueue( UNIVERSE_THEME_PATH.DS.'assets'.DS.'css'.DS.'colors'.DS.'color-primary.css' );
		$file = str_replace( UNIVERSE_SITE_URI.'/', ABSPATH, str_replace( '/', DS, $file ) );
		if (file_exists($file)) {
			$handle = $universe->ext['fo']( $file, 'r' );
			$css_data = $universe->ext['fr']( $handle, filesize( $file ) );
			header("Content-type: text/css", true);
			echo str_replace( array( '{color}', '{darkercolor}', '{brightercolor}', '{color_rgb}' ), array( $color, 'rgb('.$darkercolor.')', 'rgb('.$brightercolor.')', $color_rgb ), $css_data );
		}	
		exit;
	}
}


/*-----------------------------------------------------------------------------------*/
# Menu sidebar on mobile
/*-----------------------------------------------------------------------------------*/
function universe_responsive_sidebar_menu_set_post(){
	global $universe;
	
	$position = !empty($universe->cfg['sidebar_menu_pos']) ? $universe->cfg['sidebar_menu_pos'] : 'left';
		
	echo '<script type="text/javascript">var universe_set_pos_sidebar_menu = "'. $position .'";</script>';
}
add_action('wp_head', 'universe_responsive_sidebar_menu_set_post');



function universe_responsive_sidebar_menu(){
	global $universe;
	
	$position = !empty($universe->cfg['sidebar_menu_pos']) ? $universe->cfg['sidebar_menu_pos'] : 'left';
	
	echo '<div class="sb-slidebar sb-'. $position .'"></div>';
}
add_action('wp_footer', 'universe_responsive_sidebar_menu');


/*-----------------------------------------------------------------------------------*/
# Comment template
/*-----------------------------------------------------------------------------------*/

function universe_comment( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;
	
	switch ( $comment->comment_type ) :
		case 'pingback' : break;
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'universe' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'universe' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class('comment_wrap'); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-block">
				<?php
					$avatar_size = 68;
					if ( '0' != $comment->comment_parent )
						$avatar_size = 68;
					echo '<div class="avtar-author">'.get_avatar( $comment, $avatar_size ).'</div>';
				?>
				<div class="comment-content">
					<div class="comment-header">
						<h4><?php echo get_comment_author_link(); ?></h4>
						<em><?php echo get_comment_date();?> <?php echo esc_html__( 'at', 'universe' ); ?> <?php echo get_comment_time(); ?></em>
						<div class="comment-link">
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'universe' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
							<?php edit_comment_link( esc_html__( 'Edit', 'universe' ), '', '' ); ?>
						</div>
					</div>
					<?php comment_text(); ?>
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'universe' ); ?></em>
						<br />
					<?php endif; ?>
				</div>
			</div>
		</article><!-- #comment-## -->

	<?php
	break;
	endswitch;
}
	
/*-----------------------------------------------------------------------------------*/
# Set meta tags on header for SEO onpage
/*-----------------------------------------------------------------------------------*/
function universe_meta(){

	global $universe, $post;
	
	?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="generator" content="king-theme" />
<?php if( isset($universe->cfg['responsive']) && $universe->cfg['responsive'] == 1 ){ ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<?php }
	
	echo '<link rel="pingback" href="'.get_bloginfo( 'pingback_url' ).'" />'."\n";

	if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {	
		if( !empty( $universe->cfg['favicon'] ) ){
			echo '<link rel="shortcut icon" href="'. esc_url( $universe->cfg['favicon'] ).'" type="image/x-icon" />'."\n";
		}
	}else{
		wp_site_icon();
	}
	
}

/*-----------------------------------------------------------------------------------*/
# Filter content at blog posts
/*-----------------------------------------------------------------------------------*/
function universe_the_content_filter( $content ) {
  
  if( is_home() ){
	  
	  $content = preg_replace('/<ifr'.'ame.+src=[\'"]([^\'"]+)[\'"].*iframe>/i', '', $content );
	  
  }
  
  return $content;
}

add_filter( 'the_content', 'universe_the_content_filter' );


/**
 * Blog link
 **/
function universe_blog_link() {
  
  if( get_option( 'show_on_front', true ) ){
	  
	  $_id = get_option( 'page_for_posts', true );
	  if( !empty( $_id ) ){
		  echo get_permalink( $_id );
		  return;
	  }
  }
  
  echo UNIVERSE_SITE_URI;
  
}

/**
 * Create image link
 **/
function universe_createLinkImage( $source, $attr ){

	global $universe;
	
	$attr = explode( 'x', $attr );
	$arg = array();
	if( !empty( $attr[2] ) ){
		$arg['w'] = $attr[0];
		$arg['h'] = $attr[1];
		$arg['a'] = $attr[2];
		if( $attr[2] != 'c' ){
			$attr = '-'.implode('x',$attr);
		}else{
			$attr = '-'.$attr[0].'x'.$attr[1];
		}
	}else if( !empty( $attr[0] ) && !empty( $attr[1] ) ){
		$arg['w'] = $attr[0];
		$arg['h'] = $attr[1];
		$attr = '-'.$attr[0].'x'.$attr[1];
	}else{
		return $source;
	}
	
	$source = strrev( $source );
	$st = strpos( $source, '.');
	
	if( $st === false ){
		return strrev( $source ).$attr;
	}else{
		
		$file = str_replace( array( UNIVERSE_SITE_URI.'/', '\\', '/' ), array( ABSPATH, DS, DS ), strrev( $source ) );
		
		$_return = strrev( substr( $source, 0, $st+1 ).strrev($attr).substr( $source, $st+1 ) );
		$__return = str_replace( array( UNIVERSE_SITE_URI.'/', '\\', '/' ), array( ABSPATH, DS, DS ), $_return );

		if( file_exists( $file ) && !file_exists( $__return ) ){
			ob_start();
			$universe->processImage( $file, $arg, $__return );
			ob_end_clean();
		}
		
		return $_return;
		
	}
}


/**
 * Is Shop Function
 **/
if( !function_exists( 'is_shop' ) ){
	function is_shop(){
		return false;
	}
}

function universe_random_string( $length = 10 ){
	$str = "";
	$allow_characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$_max_length = count($allow_characters) - 1;

	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $_max_length);
		$str .= $allow_characters[$rand];
	}

	return $str;
}

/*
* Function return styles array from string font param of VC
*/
function universe_get_styles($font_container_data) {
	$styles = array();
	if ( ! empty( $font_container_data ) && isset( $font_container_data['values'] ) ) {
		foreach ( $font_container_data['values'] as $key => $value ) {
			if ( $key !== 'tag' && strlen( $value ) > 0 ) {
				if ( preg_match( '/description/', $key ) ) {
					continue;
				}
				if ( $key === 'font_size' || $key === 'line_height' ) {
					$value = preg_replace( '/\s+/', '', $value );
				}
				if ( $key === 'font_size' ) {
					$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
					// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
					$regexr = preg_match( $pattern, $value, $matches );
					$value = isset( $matches[1] ) ? (float) $matches[1] : (float) $value;
					$unit = isset( $matches[2] ) ? $matches[2] : 'px';
					$value = $value . $unit;
				}
				if ( strlen( $value ) > 0 ) {
					$styles[] = str_replace( '_', '-', $key ) . ': ' . $value;
				}
			}
		}
	}
	return $styles;
}


/**
 * Get site logo
 **/ 
function universe_get_logo(){
	global $universe, $post;
	
	if(is_page() || is_single()){
		$post_options = get_post_meta($post->ID, '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options', TRUE);
		$post_options = str_replace( '%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, $post_options ); 
		
		if(!empty($post_options['logo'])){
			return $post_options['logo'];
		}else if(!empty($universe->cfg['logo'])){
			return $universe->cfg['logo'];
		}else{
			return get_template_directory_uri().'/assets/images/logo.png';
		}
	}else{
		if(!empty($universe->cfg['logo'])){
			return $universe->cfg['logo'];
		}else{
			return get_template_directory_uri().'/assets/images/logo.png';
		}
	}
}

function universe_title($display = true){
	 global $wp_locale, $page, $paged;
 
	$m = get_query_var('m');
	$year = get_query_var('year');
	$monthnum = get_query_var('monthnum');
	$day = get_query_var('day');
	$search = get_query_var('s');
	$title = '';
 
	$t_sep = ' > '; // Temporary separator, for accurate flipping, if necessary
 
	// If there is a post
	if ( is_single() || ( is_home() && !is_front_page() ) || ( is_page() && !is_front_page() ) ) {
		$title = single_post_title( '', false );
	}
 
	// If there's a post type archive
	if ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) )
			$post_type = reset( $post_type );
		$post_type_object = get_post_type_object( $post_type );
		if ( ! $post_type_object->has_archive )
			$title = post_type_archive_title( '', false );
	}
 
	// If there's a category or tag
	if ( is_category() || is_tag() ) {
		$title = single_term_title( '', false );
	}
 
	// If there's a taxonomy
	if ( is_tax() ) {
		$term = get_queried_object();
		if ( $term ) {
			$tax = get_taxonomy( $term->taxonomy );
			$title = single_term_title( $tax->labels->name . $t_sep, false );
		}
	}
 
	// If there's an author
	if ( is_author() && ! is_post_type_archive() ) {
		$author = get_queried_object();
		if ( $author )
			$title = $author->display_name;
	}
 
	// Post type archives with has_archive should override terms.
	if ( is_post_type_archive() && $post_type_object->has_archive )
		$title = post_type_archive_title( '', false );
 
	// If there's a month
	if ( is_archive() && !empty($m) ) {
		$my_year = substr($m, 0, 4);
		$my_month = $wp_locale->get_month(substr($m, 4, 2));
		$my_day = intval(substr($m, 6, 2));
		$title = $my_year . ( $my_month ? $t_sep . $my_month : '' ) . ( $my_day ? $t_sep . $my_day : '' );
	}

	// If there's a year
	if ( is_archive() && !empty($year) ) {
		$title = $year;
		if ( !empty($monthnum) )
			$title .= $t_sep . $wp_locale->get_month($monthnum);
		if ( !empty($day) )
			$title .= $t_sep . zeroise($day, 2);
	}

	// If it's a search
	if ( is_search() ) {
		/* translators: 1: separator, 2: search phrase */
		$title = esc_html__( 'Search Results ', 'universe' ) . $t_sep . strip_tags( $search );
	}

	// If it's a 404 page
	if ( is_404() ) {
		$title = esc_html__('Page not found', 'universe');
	}


	// Send it out
	if ( $display )
		echo universe::esc_js($title);
	else
		return $title;
}

if(!function_exists('randomstring')):
function randomstring($length) {
	$key = null;
	$keys = array_merge(range(0,9), range('a', 'z'));
	for($i=0; $i < $length; $i++) {
		$key .= $keys[array_rand($keys)];
	}
	return $key;
}
endif;


function universe_global_shortcode_css($content){
	global $universe_sc_css;

	$universe_sc_css = array();

	return $content;
}
add_filter('the_content', 'universe_global_shortcode_css');


function universe_get_css( $value = array() ) {
	$css = '';
	$prefix = '.';

	if ( ! empty( $value ) && is_array( $value ) ) {
		foreach($value as $class => $style){
			$pos = strpos($class, '#');
			if($pos!== false && $pos == 0){
				$prefix = '';
			}
			$css .= $prefix.$class.'{';
			foreach ( $style as $key => $value ) {
				if ( ! empty( $value ) && $key != "media" ) {
					if ( $key == "background-image" ) {
						$css .= $key . ":url('" . $value . "');";
					} else {
						$css .= $key . ":" . $value . ";";
					}
				}
			}
			$css .= '}'."\n";
		}
	}

	return $css;
}
/*
 * @param array $array1
 * @param array $array2
 * @return array
 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
 */
function array_merge_recursive_distinct(array &$array1, array &$array2)
{
	$merged = $array1;
	foreach ($array2 as $key => &$value)
	{
		if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
		{
			$merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
		}
		else
		{
			$merged[$key] = $value;
		}
	}
	return $merged;
}


if(!function_exists('body_style')):
function body_style( $style = null ){
	global $post;

	if(is_page() || is_single()){
		$body_style = '';
		$post_data = get_post_meta( $post->ID , '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options', TRUE );

		if( !empty($post_data['body_bg'] ) ){
			$body_style .= 'background: url('. str_replace( '%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, $post_data['body_bg'] ) .') fixed center top #000;';
		}

		$body_style .= $style;
		echo 'style="'. esc_attr($body_style) .'"';
	}
}
endif;


function universe_hex2rgb( $hexColor ){
	
  $shorthand = (strlen($hexColor) == 4);

  list($r, $g, $b) = $shorthand? sscanf($hexColor, "#%1s%1s%1s") : sscanf($hexColor, "#%2s%2s%2s");

  return hexdec($shorthand? "$r$r" : $r).', '.hexdec($shorthand? "$g$g" : $g).','.hexdec($shorthand? "$b$b" : $b);
  
}

function universe_post_meta_options($post_id = null){
	global $post;

	$post_data = '';

	if(is_page() || is_single()){
		if($post_id == null){
			$post_id = $post->ID;
		}

		$post_data = get_post_meta( $post->ID , '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options', TRUE );
	}

	return $post_data;
}


function universe_disable_search_in_menu(){
	global $universe;

	if(isset($universe->cfg['searchNav']) && $universe->cfg['searchNav'] == 'hide'){
		echo '<script type="text/javascript">jQuery("li.nav-search").remove();</script>';
	}

}
add_action( 'wp_footer', 'universe_disable_search_in_menu' );


function universe_fix_breadcrumb_same_color(){
	if( !is_admin() ){
		global $post;

		if(is_page()){
			$options = get_post_meta( $post->ID, '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options', TRUE);

			if(isset($options['breadcrumb']['_file_'])){
				$breadcrumb = $options['breadcrumb']['_file_'];
			}

			if( isset($breadcrumb) && 'templates/breadcrumb/style-4.php' == $breadcrumb ){
				add_filter( 'body_class', 'universe_breadcrumb_same_color' );
			}
		}
	}

}
add_action( 'get_header', 'universe_fix_breadcrumb_same_color' );

function universe_breadcrumb_same_color( $classes ) {
	$classes[] = 'breadcrumb_same_color';
	return $classes;
}


function universe_move_comment_field_to_bottom( $fields ) {

	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;

}
add_filter( 'comment_form_fields', 'universe_move_comment_field_to_bottom' );


function universe_add_class_in_to_body( $classes ) {

	return array_merge( $classes, array( 'bg-cover' ) );

}
add_filter( 'body_class', 'universe_add_class_in_to_body' );


function universe_mega_menu($atts, $content = null) {
	
	$_server = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	
	extract( shortcode_atts( array('menu' => '', 'title' => '', 'custom_class' =>''), $atts ) );
	
	global $wpdb;
	
	$menuID = $wpdb->get_results($wpdb->prepare("SELECT `term_id` FROM `$wpdb->terms` WHERE `'.$wpdb->terms`.`slug` = '%s'", $menu));
	
	if( empty( $menuID[0] ) ){
		return;
	}
	if( empty( $menuID[0]->term_id ) ){
		return;
	}
	
	$menu = $menuID[0]->term_id;
	$items = wp_get_nav_menu_items( $menu );

	$output = '<ul class="menu mega_menu'.$custom_class.'">';
	if ($title)$output.= '<li><p>'.$title.'</p></li>';
	if ($items) {
		foreach($items as $item) {
			
			if( $item->url == 'http://'.$_server || $item->url == 'https://'.$_server ){
				$_class = ' class="active"';
			}else{
				$_class = '';
			}
			$output .= '<li class="menu-item"><a href="'.$item->url.'" '.$_class.'>';
			if( strpos( $item->description, 'icon:') !== false ){
				$output .= ' <i class="fa fa-'.trim(str_replace( 'icon:', '', $item->description )).'"></i> ';	
			}else{
				$output .= ' <i class="fa fa-angle-right"></i> ';
			}
			$output .= $item->title.'</a></li>';
		}
	}

	$output.= '</ul>';

	return $output;
	
}
$universe->ext['asc']('mega_menu', 'universe_mega_menu');