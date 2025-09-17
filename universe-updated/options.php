<?php


/*
 *
 * Thanks for Leemason-NHP
 * Copyright (c) Options by Leemason-NHP 
 *
 * 
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 * Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 *
 */
//define('universe_options_URL', home_url('path the options folder'));
if(!class_exists('universe_options')){
	require_once get_template_directory() .DS.'options'.DS.'options.php';
}

/*
 * 
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){
	
	//$sections = array();
	$sections[] = array(
				'title' => esc_html__('A Section added by hook', 'universe'),
				'desc' => wp_kses( __('<p class="description">This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.</p>', 'universe'), array('p'=>array())),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);
	
	return $sections;
	
}//function

/*
 * 
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){
	
	//$args['dev_mode'] = false;
	
	return $args;
	
}//function

/*
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_framework_options(){
	
	global $universe;
	
	$args = array();
	
	$args['dev_mode'] = false;

	$args['google_api_key'] = 'AIzaSyDAnjptHMLaO8exTHk7i8jYPLzygAE09Hg';

	$args['intro_text'] = wp_kses( __('<p>This is the HTML which can be displayed before the form, it isnt required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'universe'), array('p'=>array()));

	$args['share_icons']['twitter'] = array(
											'link' => 'http://twitter.com/devnCo',
											'title' => 'Folow me on Twitter'
											);

	$args['show_import_export'] = false;

	$args['opt_name'] = UNIVERSE_THEME_OPTNAME;

	$args['page_position'] = 1001;
	$args['allow_sub_menu'] = false;


	$import_file = ABSPATH.'wp-content'.DS.'themes'.DS.UNIVERSE_THEME_SLUG.DS.'core'.DS.'sample'.DS.'data.xml';
	$import_html = '';
	if ( file_exists($import_file) ){

		$import_html = '<h2></h2><br /><div class="nhp-opts-section-desc"><p class="description"><a style="font-style: normal;" href="admin.php?page=king-sample-data" class="btn btn_green">One-Click Importer Sample Data</a>  &nbsp; Just click and your website will look exactly our demo (posts, pages, menus, categories, tags, layouts, images, sliders, post-type) </p> <br /></div><hr style="background: #ccc;border: none;height: 1px;"/><br />';

	}

	$sections = array();

	$patterns = array();
	for( $i=1; $i<13; $i++ ){
		$patterns['pattern'.$i.'.png'] = array('title' => 'Background '.$i, 'img' => UNIVERSE_THEME_URI.'/assets/images/elements/pattern'.$i.'.png');
	}
	for( $i=13; $i<17; $i++ ){
		$patterns['pattern'.$i.'.jpg'] = array('title' => 'Background '.$i, 'img' => UNIVERSE_THEME_URI.'/assets/images/elements/pattern'.$i.'-small.jpg');
	}

	$listHeaders = array();
	if ( $handle = @$universe->ext['od']( UNIVERSE_THEME_PATH.DS.'templates'.DS.'header' ) ){
		while ( false !== ( $entry = readdir($handle) ) ) {
			if( $entry != '.' && $entry != '..' && strpos($entry, '.php') !== false  ){
				$title  = ucwords( str_replace( '-', ' ', basename( $entry, '.php' ) ) );
				$listHeaders[ 'templates'.DS.'header'.DS.$entry ] = array('title' => $title, 'img' => UNIVERSE_THEME_URI.'/templates/header/thumbnails/'.basename( $entry, '.php' ).'.jpg');
			}
		}
	}

	$listBreadcrumbs = array();
	if ( $handle = @$universe->ext['od']( UNIVERSE_THEME_PATH.DS.'templates'.DS.'breadcrumb' ) ){
		while ( false !== ( $entry = readdir($handle) ) ) {
			if( $entry != '.' && $entry != '..' && strpos($entry, '.php') !== false  ){
				$title  = ucwords( str_replace( '-', ' ', basename( $entry, '.php' ) ) );
				$listBreadcrumbs[ 'templates'.DS.'breadcrumb'.DS.$entry ] = array('title' => $title, 'img' => UNIVERSE_THEME_URI.'/templates/breadcrumb/thumbnails/'.basename( $entry, '.php' ).'.jpg');
			}
		}
	}

	$sidebars = array( '' => '--Select Sidebar--' );

	if( !empty( $universe->cfg['sidebars'] ) ){
		foreach( $universe->cfg['sidebars'] as $sb ){
			$sidebars[ sanitize_title_with_dashes( $sb ) ] = esc_html( $sb );
		}
	}

$sections[] = array(
	'id' => 'general-settings',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_023_cogwheels.png',
	'title' => esc_html__('General Settings', 'universe'),
	'desc' => wp_kses( __('<p class="description">general configuration options for theme</p>', 'universe'), array('p'=>array())),
	'fields' => array(

		array(
			'id' => 'logo',
			'type' => 'upload',
			'title' => esc_html__('Upload Logo', 'universe'), 
			'sub_desc' => esc_html__('This will be display as logo at header of every page', 'universe'),
			'desc' => esc_html__('Upload new or from media library to use as your logo. We recommend that you use images without borders and throughout.', 'universe'),
			'std' => UNIVERSE_THEME_URI.'/assets/images/logo.png'
		),
		array(
			'id' => 'logo_height',
			'type' => 'text',
			'title' => esc_html__('Logo Max Height', 'universe'), 
			'sub_desc' => esc_html__('Limit logo\'s size. Eg: 60', 'universe'),
			'std' => '45',
			'desc' => 'px',
			'css' => '<?php if($value!="")echo "html body .logo img{max-height: ".$value."px;}"; ?>',
		),		
		array(
			'id' => 'logo_top',
			'type' => 'text',
			'title' => esc_html__('Logo Top Spacing', 'universe'), 
			'sub_desc' => esc_html__('The spacing from the logo to the edge of the page. Eg: 5', 'universe'),
			'std' => '5',
			'desc' => 'px',
			'css' => '<?php if($value!="")echo "html body .logo{margin-top: ".$value."px;}"; ?>',
		),			
		array(
			'id' => 'favicon',
			'type' => 'upload',
			'title' => esc_html__('Upload Favicon', 'universe'),
			'std' => UNIVERSE_THEME_URI.'/favico.png',
			'sub_desc' => esc_html__('This will be display at title of browser', 'universe'),
			'desc' => esc_html__('Upload new or from media library to use as your favicon.', 'universe')
		),
		array(
			'id' => 'layout',
			'type' => 'button_set',
			'title' => esc_html__('Select Layout', 'universe'),
			'desc' => '',
			'options' => array('wide' => 'WIDE','boxed' => 'BOXED'),
			'std' => 'wide'
		),
		array(
			'id' => 'responsive',
			'type' => 'button_set',
			'title' => esc_html__('Responsive Support', 'universe'),
			'desc' => esc_html__('Help display well on all screen size (smartphone, tablet, laptop, desktop...)', 'universe'),
			'options' => array('1' => 'Enable','0' => 'Disable'),
			'std' => '1'
		),
		array(
			'id' => 'admin_bar',
			'type' => 'button_set',
			'title' => esc_html__('Admin Bar', 'universe'),
			'desc' => esc_html__('The admin bar on top at Front-End when you logged in.', 'universe'),
			'options' => array('hide' => 'Hide','show' => 'Show'),
			'std' => 'hide'
		),
		array(
			'id' => 'breadcrumb',
			'type' => 'profile_template',
			'title' => esc_html__('Show Breadcrumb', 'universe'),
			'desc' => esc_html__('The Breadcrumb on every page', 'universe'),
			'options' => $listBreadcrumbs,
			'std' => 'default.php'
		),
		array(
			'id' => 'breadeli',
			'type' => 'text',
			'title' => esc_html__('Breadcrumb Delimiter', 'universe'),
			'desc' => esc_html__('The symbol in beetwen your Breadcrumbs.', 'universe'),
			'std' => '/'
		),
		array(
			'id' => 'breadcrumb_bg',
			'type' => 'upload',
			'title' => esc_html__('Breadcrumb Background Image', 'universe'),
			'desc' => esc_html__('Upload your background image for Breadcrumb', 'universe'),
			'std' => '',
			'css' => '<?php if($value!="")echo "#breadcrumb.page_title1{background-image:url(".$value.");}"; ?>}'
		),
		array(
			'id' => 'api_server',
			'type' => 'button_set',
			'title' => esc_html__('Select API Server', 'universe'), 
			'desc' => esc_html__('Select API in case you have problems importing sample data or install sections', 'universe'),
			'options' => array('api.devn.co' => 'API Server 1','api2.devn.co' => 'API Server 2'),
			'std' => 'api.devn.co'
		),
	)	
);

$sections[] = array(
	'id' => 'header-settings',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_263_bank.png',
	'title' => esc_html__('Header Settings', 'universe'),
	'desc' => wp_kses( __('<p class="description">Select header & footer layouts, Add custom meta tags, hrefs and scripts to header.</p>', 'universe'), array('p'=>array())),
	'parent' => 'general-settings',
	'fields' => array(
		array(
			'id' => 'sidebar_menu_pos',
			'type' => 'button_set',
			'title' => esc_html__('Display sidebar menu mobile on', 'universe'),
			'options' => array( 'left' => 'Left side', 'right' => 'Right side' ),
			'std' => 'left'
		),
		array(
			'id' => 'header',
			'type' => 'profile_template',
			'title' => esc_html__('Select Header', 'universe'),
			'sub_desc' => '<br /><br />'.wp_kses( __('Overlap: The header will cover up anything beneath it. <br /> <br />Select header for all pages, You can also go to each page to select specific. This path has located /templates/header/{-file-}', 'universe'), array( 'br'=>array() )),
			'options' => $listHeaders,
			'std' => 'default.php'
		),
		array(
			'type'	=> 'color',
			'id'	=> 'header_bg_color',
			'title'	=>  esc_html__('Header Background Color', 'universe'),
			'desc'	=>  esc_html__('Header Background Corlor', 'universe'),
			'css'	=> '<?php if($value!="")echo "body header.header{background-color: ".$value.";}"; ?>',
			'std'	=> ''
		),
		array(
			'id' => 'topInfoCart',
			'type' => 'button_set',
			'title' => esc_html__('Minicart', 'universe'),
			'desc' => esc_html__('Display minicart in right side of top navigation (Only when Woocommerce plugin has been activated)', 'universe'),
			'options' => array( 'show' => 'Show', 'hide' => 'Hide' ),
			'std' => 'show'
		),
		array(
			'id' => 'searchNav',
			'type' => 'button_set',
			'title' => esc_html__('Search box in Menu', 'universe'),
			'desc' => esc_html__('Display search in right side of main menu', 'universe'),
			'options' => array( 'show' => 'Show', 'hide' => 'Hide' ),
			'std' => 'show'
		),

	)
);


$list_footers_style = array();
$list_footers_style['empty'] = 'Empty';
$posts = get_posts( array('post_type' => 'universe_footer', 'posts_per_page' => -1, 'order' => 'ASC') );
foreach ($posts as $post) {
	$list_footers_style[$post->post_name] = $post->post_title;
}

$sections[] = array(
	'id' => 'footer-settings',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_303_temple_islam.png',
	'title' => esc_html__('Footer Settings', 'universe'),
	'desc' => wp_kses( __('<p class="description">Select footer layouts, Add analytics embed..etc.. to footer</p>', 'universe'), array( 'p' =>array() )),
	'parent' => 'general-settings',
	'fields' => array(
		array(
			'id' => 'footer_style',
			'type' => 'footer_styles',
			'title' => esc_html__('Select Footer Styles', 'universe'),
			'sub_desc' => wp_kses( __('<br />Select footer for all pages, You can also go to each page to select specific.', 'universe'), array( 'br'=>array() )),
			'options' => $list_footers_style,
			'std' => 'default'
		),
	)
);


$sections[] = array(
	'id' => 'blog',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_087_log_book.png',
	'title' => esc_html__('Blog', 'universe'),
	'desc' => esc_html__('Blog Settings', 'universe'),
	'fields' => array(		
		array(
			'id' => 'blog',
			'type' => 'blog'
		)
	)
);


$sections[] = array(
	'id' => 'article-settings',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_061_keynote.png',
	'title' => esc_html__('Article Settings', 'universe'),
	'desc' => wp_kses( __('<p class="description">Settings for Single post or Page</p>', 'universe'),array('p'=>array())),
	'fields' => array(
		array(
			'id' => 'article_sidebar',
			'type' => 'select',
			'title' => esc_html__('Select Sidebar', 'universe'),
			'options' => $sidebars,
			'std' => '',
			'sub_desc' => esc_html__( 'Select template from single article at right side', 'universe' ),
			'desc' => '<br /><br />'.esc_html__( 'Select a dynamic sidebar what you created in theme-panel to display under page layout.', 'universe' )
		),
		array(
			'id' => 'excerptImage',
			'type' => 'button_set',
			'title' => esc_html__('Featured Image', 'universe'), 
			'sub_desc' => esc_html__('Display Featured image before of content', 'universe'),
			'options' => array('1' => 'Display','2' => 'Hide'),
			'std' => '1'
		),		
		array(
			'id' => 'navArticle',
			'type' => 'button_set',
			'title' => esc_html__('Next/Prev Article Direction', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showMeta',
			'type' => 'button_set',
			'title' => esc_html__('Meta Box', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showAuthorMeta',
			'type' => 'button_set',
			'title' => esc_html__('Author Meta', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showDateMeta',
			'type' => 'button_set',
			'title' => esc_html__('Date Meta', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showCateMeta',
			'type' => 'button_set',
			'title' => esc_html__('Categories Meta', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showCommentsMeta',
			'type' => 'button_set',
			'title' => esc_html__('Comments Meta', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showTagsMeta',
			'type' => 'button_set',
			'title' => esc_html__('Tags Meta', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showShareBox',
			'type' => 'button_set',
			'title' => esc_html__('Share Box', 'universe'), 
			'sub_desc' => esc_html__('Display box socials button below', 'universe'),
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showShareFacebook',
			'type' => 'button_set',
			'title' => esc_html__('Facebook Button', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showShareTwitter',
			'type' => 'button_set',
			'title' => esc_html__('Tweet Button', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showShareGoogle',
			'type' => 'button_set',
			'title' => esc_html__('Google Button', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showSharePinterest',
			'type' => 'button_set',
			'title' => esc_html__('Pinterest Button', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'showShareLinkedin',
			'type' => 'button_set',
			'title' => esc_html__('LinkedIn Button', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'std' => '1'
		),
		array(
			'id' => 'archiveAboutAuthor',
			'type' => 'button_set',
			'title' => esc_html__('About Author', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'sub_desc' => esc_html__('About author box with avatar and description', 'universe'),
			'std' => '1'
		),
		array(
			'id' => 'archiveRelatedPosts',
			'type' => 'button_set',
			'title' => esc_html__('Related Posts', 'universe'), 
			'options' => array('1' => 'Show','0' => 'Hide'),
			'sub_desc' => esc_html__('List related posts after the content.', 'universe'),
			'std' => '1'
		),
		array(
			'id' => 'archiveNumberofPosts',
			'type' => 'text',
			'title' => esc_html__('Number of posts related to show', 'universe'), 
			'validate' => 'numeric',
			'std' => '3'
		),
		array(
			'id' => 'archiveRelatedQuery',
			'type' => 'button_set',
			'title' => esc_html__('Related Query Type', 'universe'), 
			'options' => array('category' => 'Category','tag' => 'Tag','author'=>'Author'),
			'std' => 'category'
		)
	)

);


//  coming soon

$sections[] = array(
	'id' => 'coming-soon',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_022_fire.png',
	'title' => esc_html__('Coming soon', 'universe'),
	'desc' => esc_html__('Set your socials and will be displayed icons at header and footer, Leave blank to hide icons from front-end', 'universe'),
	'fields' => array(
		array(
			'id'       => 'cs_logo',
			'type'     => 'upload',
			'title'    => esc_html__('Upload Logo', 'universe'),
			'sub_desc' => esc_html__('This will be display as logo at header of every page', 'universe'),
			'desc'     => esc_html__('Upload new or from media library to use as your logo. We recommend that you use images without borders and throughout.', 'universe'),
			'std'      => UNIVERSE_THEME_URI.'/assets/images/logo.png'
		),
		array(
			'id'       => 'cs_timedown',
			'type'     => 'text',
			'title'    => esc_html__('Date time for countdown', 'universe'),
			'sub_desc' => esc_html__('Format  "Y-F-d H:i:s" for example "2016-06-06 12:00:00"', 'universe'),
			'std'      => '2016-06-06 12:00:00'
		),
		array(
			'id'    => 'cs_title',
			'type'  => 'textarea',
			'title' => esc_html__('Title', 'universe'),
			'std'   => '<h2>Launching.</h2><h3>Soon.</h3>'
		),
		array(
			'id'    => 'cs_description',
			'type'  => 'textarea',
			'title' => esc_html__('Description', 'universe'),
			'std'   => '<span>. Stay tuned here .</span><span>. We are up & working soon .</span>'
		)
	)

);


//  Post Types
$sections[] = array(
	'id' => 'custom-post-types',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_145_folder_plus.png',
	'title' => esc_html__('Our work settings', 'universe'),
	'desc' => esc_html__('Setting title, slugs for post types', 'universe'),
	'fields' => array(
		array(
			'id'       => 'our_works_style',
			'type'     => 'select',
			'title'    => esc_html__('Layout Single Our Work', 'universe'),
			'sub_desc' => esc_html__('Select layout style single post type Our Work', 'universe'),
			'std'      => '1',
			'options'  => array(
				'1' => 'Style 1',
				'2' => 'Style 2'
			)
		),
		array(
			'id'    => 'our_works_breadcrumb_bg',
			'type'  => 'upload',
			'title' => esc_html__('Our Works Breadcrumb Background Image', 'universe'),
			'desc'  => esc_html__('Upload your background image for Breadcrumb', 'universe'),
			'std'   => ''
		)
	)

);


$sections[] = array('divide'=>true);


$sections[] = array(
	'id' => 'dynamic-sidebars',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_037_credit.png',
	'title' => esc_html__('Dynamic Sidebars', 'universe'),
	'desc' => esc_html__('You can create unlimited sidebars and use it in any page you want.','universe'),
	'parent' => 'general-settings',
	'fields' => array(
		array(
			'id' => 'sidebars',
			'type' => 'multi_text',
			'title' => esc_html__('List of Sidebars Created', 'universe'),
			'sub_desc' => esc_html__('Add name of sidebar', 'universe'),
			'std' => array('Nav Sidebar')
		),
	)

);
 
$sections[] = array(
	'id' => 'styling',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_273_drink.png',
	'title' => esc_html__('Styling', 'universe'),
	'desc' => wp_kses( __('<p class="description">Setting up global style and background</p>', 'universe'), array('p'=>array())),
	'fields' => array(
		array(
			'id' => 'colorStyle',
			'type' => 'colorStyle',
			'title' => esc_html__('Color Style', 'universe'), 
			'sub_desc' => esc_html__('Predefined Color Skins', 'universe'),
			'desc' => esc_html__( 'Primary css file has been located at: /wp_content/themes/__name__/assets/css/colors/color-primary.css', 'universe' ),
			'std'	=> ''
		),
		array(
			'type' => 'color',
			'id' => 'backgroundColor',
			'title' =>  esc_html__('Background Color', 'universe'),
			'desc' =>  esc_html__(' Background body for layout wide and background box for layout boxed', 'universe'), 
			'css' => '<?php if($value!="")echo "body{background-color: ".$value.";}"; ?>',
			'std' => '#ffffff'
		),	
		array(
			'type' => 'upload',
			'id' => 'backgroundCustom',
			'title' =>  esc_html__('Custom Background Image', 'universe'),
			'sub_desc' => esc_html__('Only be used for Boxed Type.', 'universe'),
			'desc' =>  esc_html__(' Upload your custom background image, or you can also use the Pattern available below.', 'universe'),
			'std' => '',
			'css' => '<?php if($value!="")echo "body{background-image: url(".$value.") !important;}"; ?>'
		
		),
		array(
			'id' => 'useBackgroundPattern',
			'type' => 'checkbox_hide_below',
			'title' => esc_html__('Use Pattern for background', 'universe'), 
			'sub_desc' => esc_html__('Tick on checkbox to show list of Patterns', 'universe'),
			'desc' => esc_html__('If you do not have background image, you can also use our Pattern.', 'universe'),
			'std' => 0,
		),
		array(
			'id' => 'backgroundImage',
			'type' => 'radio_img',
			'title' => esc_html__('Select background', 'universe'), 
			'sub_desc' => esc_html__('Only be used for Boxed Type.', 'universe'),
			'options' => $patterns,
			'std' => '',
			'css' => '<?php if($value!="")echo "body{background-image: url('.UNIVERSE_THEME_URI.'/assets/images/elements/".$value.");}"; ?>'
		),		
		array(
			'id' => 'linksDecoration',
			'type' => 'select',
			'title' => esc_html__('Links Decoration', 'universe'), 
			'sub_desc' => esc_html__('Set decoration for all links.', 'universe'),
			'options' => array('default'=>'Default','none'=>'None','underline'=>'Underline','overline'=>'Overline','line-through'=>'Line through'),
			'std' => 'default',
			'css' => '<?php if($value!="")echo "a{text-decoration: ".$value.";}"; ?>'
		),		
		array(
			'id' => 'linksHoverDecoration',
			'type' => 'select',
			'title' => esc_html__('Links Hover Decoration', 'universe'), 
			'sub_desc' => esc_html__('Set decoration for all links when hover.', 'universe'),
			'options' => array('default'=>'Default','none'=>'None','underline'=>'Underline','overline'=>'Overline','line-through'=>'Line through'),
			'std' => 'default',
			'css' => '<?php if($value!="")echo "a:hover{text-decoration: ".$value.";}"; ?>'
		),		
		array(
			'id' => 'cssGlobal',
			'type' => 'textarea',
			'title' => esc_html__('Global CSS', 'universe'), 
			'sub_desc' => esc_html__('CSS for all screen size, only CSS without &lt;style&gt; tag', 'universe'),
			'css' => '<?php if($value!="")print( $value ); ?>'
		),
		array(
			'id' => 'cssTablets',
			'type' => 'textarea',
			'title' => esc_html__('Tablets CSS', 'universe'), 
			'sub_desc' => esc_html__('Width from 768px to 985px, only CSS without &lt;style&gt; tag', 'universe'),
			'css' => '<?php if($value!="")echo "@media (min-width: 768px) and (max-width: 985px){".$value."}"; ?>'
		),
		array(
			'id' => 'cssPhones',
			'type' => 'textarea',
			'title' => esc_html__('Wide Phones CSS', 'universe'), 
			'sub_desc' => esc_html__('Width from 480px to 767px, only CSS without &lt;style&gt; tag', 'universe'),
			'css' => '<?php if($value!="")echo "@media (min-width: 480px) and (max-width: 767px){".$value."}"; ?>'
		),
		
	)

);

$sections[] = array(
	'id' => 'typography',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_107_text_resize.png',
	'title' => esc_html__('Typography', 'universe'),
	'desc' => wp_kses( __('<p class="description">Set the color, font family, font size, font weight and font style.</p>', 'universe'),array('p'=>array())),
	'fields' => array(
		array(
			'id' => 'generalTypography',
			'type' => 'typography',
			'title' => esc_html__('General Typography', 'universe'), 
			'std' => array(),
			'css' => 'body,.dropdown-menu,body p{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),				
		array(
			'id' => 'generalHoverTypography',
			'type' => 'typography',
			'title' => esc_html__('General Link Hover', 'universe'), 
			'css' => 'body * a:hover, body * a:active, body * a:focus{<?php if($value[color]!="")echo "color:".$value[color]." !important;"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),		
		array(
			'id' => 'mainMenuTypography',
			'type' => 'typography',
			'title' => esc_html__('Main Menu', 'universe'),
			'css' => 'body .navbar-default .navbar-nav>li>a{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\' !important;"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight]." !important;"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),		
		array(
			'id' => 'mainMenuHoverTypography',
			'type' => 'typography',
			'title' => esc_html__('Main Menu Hover', 'universe'), 
			'css' => 'body .navbar-default .navbar-nav>li>a:hover,.navbar-default .navbar-nav>li.current-menu-item>a{<?php if($value[color]!="")echo "color:".$value[color]." !important;"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\' !important;"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),			
		array(
			'id' => 'mainMenuSubTypography',
			'type' => 'typography',
			'title' => esc_html__('Sub Main Menu', 'universe'), 
			'css' => '.dropdown-menu>li>a{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),			
		array(
			'id' => 'mainMenuSubHoverTypography',
			'type' => 'typography',
			'title' => esc_html__('Sub Main Menu Hover', 'universe'), 
			'css' => '.dropdown-menu>li>a:hover{<?php if($value[color]!="")echo "color:".$value[color]." !important;"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),	
		array(
			'id' => 'postMetaTypography',
			'type' => 'typography',
			'title' => esc_html__('Post Meta', 'universe'), 
			'std' => array(),
			'css' => '.post_meta_links{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'postMatalinkTypography',
			'type' => 'typography',
			'title' => esc_html__('Post Meta Link', 'universe'), 
			'css' => '.post_meta_links li a{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'postTitleTypography',
			'type' => 'typography',
			'title' => esc_html__('Post Title', 'universe'), 
			'css' => '.blog_post h3.entry-title a{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'postEntryTypography',
			'type' => 'typography',
			'title' => esc_html__('Post Entry', 'universe'), 
			'css' => 'article .blog_postcontent,article .blog_postcontent p{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'widgetTitlesTypography',
			'type' => 'typography',
			'title' => esc_html__('Widget Titles', 'universe'),
			'css' => 'h3.widget-title,#reply-title,#comments-title{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'footerWidgetTitlesTypography',
			'type' => 'typography',
			'title' => esc_html__('Footer Widgets Titles', 'universe'), 
			'std'	=> array(),
			'css' => '.footer h3.widget-title{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'h1Typography',
			'type' => 'typography',
			'title' => esc_html__('H1 Typography', 'universe'), 
			'std' => array(),
			'css' => '.entry-content h1{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'h2Typography',
			'type' => 'typography',
			'title' => esc_html__('H2 Typography', 'universe'), 
			'std' => array(),
			'css' => '.entry-content h2{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'h3Typography',
			'type' => 'typography',
			'title' => esc_html__('H3 Typography', 'universe'), 
			'std' => array(),
			'css' => '.entry-content h3{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'h4Typography',
			'type' => 'typography',
			'title' => esc_html__('H4 Typography', 'universe'), 
			'std' => array(),
			'css' => '.entry-content h4{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'h5Typography',
			'type' => 'typography',
			'title' => esc_html__('H5 Typography', 'universe'), 
			'std' => array(),
			'css' => '.entry-content h5{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		),
		array(
			'id' => 'h6Typography',
			'type' => 'typography',
			'title' => esc_html__('H6 Typography', 'universe'), 
			'std' => array(),
			'css' => '.entry-content h6{<?php if($value[color]!="")echo "color:".$value[color].";"; ?><?php if($value[font]!="")echo "font-family:\'".$value[font]."\';"; ?><?php if($value[size]!="")echo "font-size:".$value[size]."px;"; ?><?php if($value[weight]!="")echo "font-weight:".$value[weight].";"; ?><?php if($value[style]!="")echo "font-style:".$value[style].";"; ?>}'
		)
		
	)

);

$sections[] = array(
	'id' => 'twitter-api-key',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_322_twitter.png',
	'title' => esc_html__('Twitter API Key', 'universe'),
	'desc' => esc_html__('Enter your twitter API key for twitter widget feed updates', 'universe'),
	'parent' => 'social-accounts',
	'fields' => array(
		array(
			'id' => 'twitter_consumer_key',
			'type' => 'text',
			'title' => esc_html__('Consumer Key (API Key)', 'universe'),
			'sub_desc' => esc_html__('Get consumer key in https://apps.twitter.com', 'universe'),
			'std' => 'tHWsp0yQQioooQZJfXJdGP3d4'
		),
		array(
			'id' => 'twitter_consumer_secret',
			'type' => 'text',
			'title' => esc_html__('Consumer Secret (API Secret)', 'universe'),
			'sub_desc' => esc_html__('Get consumer Secret in https://apps.twitter.com', 'universe'),
			'std' => 'bl1kN9xH6nf167d0SJXnv9V5ZXuGXSShr5CeimLXaIGcUEQnsp'
		),
		array(
			'id' => 'twitter_oauth_access_token',
			'type' => 'text',
			'title' => esc_html__('Access Token', 'universe'),
			'sub_desc' => esc_html__('Get Access Token in https://apps.twitter.com', 'universe'),
			'std' => ' 120290116-vmLx4sPp5O3hjhRxjpl28i0APJkCpg04YVsoZyb7'
		),
		array(
			'id' => 'twitter_oauth_access_token_secret',
			'type' => 'text',
			'title' => esc_html__('Access Token Secret', 'universe'),
			'sub_desc' => esc_html__('Get Access Token Secret in https://apps.twitter.com', 'universe'),
			'std' => 'B9mAhgZhQG0cspt1doF2cxDky40OEatjftRI5NCmQh1pE'
		),
	)
);


$sections[] = array(
	'id'     => 'newsletter-settings',
	'icon'   => universe_options_URL.'img/glyphicons/glyphicons_037_credit.png',
	'title'  => esc_html__('Newsletter', 'universe'),
	'desc'   => esc_html__('Select your newsletter method on website.','universe'),
	'fields' => array(
		array(
			'id'      => 'newsletter_method',
			'type'    => 'button_set',
			'title'   => esc_html__('Method', 'universe'),
			'options' => array('mc' => 'Mailchimp', 'self' => 'Theme Functions'),
			'std'     => 'mc'
		),

		array(
			'id'       => 'mc_api',
			'type'     => 'text',
			'title'    => esc_html__('Mailchimp API Key', 'universe'),
			'sub_desc' => esc_html__('Your API key which you can grab from http://admin.mailchimp.com/account/api/', 'universe'),
			'std'      => ''
		),

		array(
			'id'       => 'mc_list_id',
			'type'     => 'text',
			'title'    => esc_html__('Mailchimp List ID', 'universe'),
			'sub_desc' => esc_html__('The ID of list which you want to customers signup. You can grab your List Id by going to http://admin.mailchimp.com/lists/ click the "settings" link for the list - the Unique Id is at the bottom of that page. ', 'universe'),
			'std'      => ''
		),

	)

);


$sections[] = array('divide'=>true);

//  Woo Admin
$sections[] = array(
	'id' => 'wooecommerce',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_202_shopping_cart.png',
	'title' => esc_html__('WooEcommerce', 'universe'),
	'desc' => esc_html__('Setting for your Shop!', 'universe'),
	'fields' => array(
		array(
			'id' => 'product_number',
			'type' => 'text',
			'title' => esc_html__('Number of Products per Page', 'universe'),
			'desc' => esc_html__('Insert the number of products to display per page.', 'universe'),
			'std' => '12'
		),
		array(
			'id' => 'woo_grids',
			'type' => 'select',
			'title' => esc_html__('Items per row', 'universe'),
			'desc' => esc_html__('Set number products per row (for Grids layout)', 'universe'),
			'options' => array('4'=>'4 (Shop layout without sidebar)','3'=>'3 (Shop layout with sidebar)'),
			'std' => '3'
		),
		array(
			'id' => 'woo_layout',
			'type' => 'select',
			'title' => esc_html__('Shop Layout', 'universe'),
			'desc' => esc_html__('Set layout for your shop page.', 'universe'),
			'options' => array('full'=>'No sidebar - Full width', 'left'=>'With Sidebar on Left', 'right'=>'With Sidebar on Right'),
			'std' => 'left'
		),
		array(
			'id' => 'woo_product_layout',
			'type' => 'select',
			'title' => esc_html__('Product Layout', 'universe'),
			'desc' => esc_html__('Set layout for your product detail page.', 'universe'),'options' => array('full'=>'No sidebar - Full width', 'left'=>'With Sidebar on Left', 'right'=>'With Sidebar on Right'),
			'std' => 'right'
		),
		array(
			'id' => 'woo_product_display',
			'type' => 'select',
			'title' => esc_html__('Product Display', 'universe'),
			'desc' => esc_html__('Display products by grid or list.', 'universe'),
			'options' => array('grid'=>'Grid','list'=>'List'),
			'std' => 'grid'
		),
		array(
			'id' => 'woo_filter',
			'type' => 'button_set',
			'title' => esc_html__('Filter Products', 'universe'),
			'options' => array('1' => 'Enable','0' => 'Disable'),
			'desc' => esc_html__('Enable filter products by price, categories, attributes..', 'universe'),
			'std' => '1'
		),
		array(
			'id' => 'woo_social',
			'type' => 'button_set',
			'title' => esc_html__('Show Woocommerce Social Icons', 'universe'),
			'options' => array('1' => 'Enable','0' => 'Disable'),
			'desc' => esc_html__('Show Woocommerce Social Icons in Single Product Page', 'universe'),
			'std' => '1'
		),
		array(
			'id' => 'woo_message_1',
			'type' => 'textarea',
			'title' => esc_html__('Account Message 1', 'universe'),
			'desc' => esc_html__('Insert your message to appear in the first message box on the acount page.', 'universe'),
			'std' => 'Call us in 000-000-000 If you need our support. Happy to help you !'
		),
		array(
			'id' => 'woo_message_2',
			'type' => 'textarea',
			'title' => esc_html__('Account Message 2', 'universe'),
			'desc' => esc_html__('Insert your message to appear in the second message box on the acount page.', 'universe'),
			'std' => 'Send us a email in devn@support.com'
		),

	)

);

// Woo Magnifier
$sections[] = array(
	'id' => 'woo-magnifier',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_027_search.png',
	'title' => esc_html__('Woo Magnifier', 'universe'),
	'desc' => esc_html__('Setting Magnifier effect for images product in single product page!', 'universe'),
	'parent' => 'wooecommerce',
	'fields' => array(
		array(
			'id' => 'mg_active',
			'type' => 'button_set',
			'title' => esc_html__('Magnifier Active', 'universe'), 
			'options' => array('1' => 'Enable','0' => 'Disable'),
			'desc' => esc_html__('Enable magnifier for product images/ Disable magnifier to use default lightbox for product images', 'universe'),
			'std' => '1'
		),
		array(
			'id' => 'mg_zoom_width',
			'type' => 'text',
			'title' => esc_html__('Zoom Width', 'universe'),
			'desc' => esc_html__('Set width of magnifier box ( default: auto )', 'universe'),
			'std' => 'auto'
		),
		array(
			'id' => 'mg_zoom_height',
			'type' => 'text',
			'title' => esc_html__('Zoom Height', 'universe'),
			'desc' => esc_html__('Set height of magnifier box ( default: auto )', 'universe'),
			'std' => 'auto'
		),
		array(
			'id' => 'mg_zoom_position',
			'type' => 'select',
			'title' => esc_html__('Zoom Position', 'universe'), 
			'desc' => esc_html__('Set magnifier position ( default: Right )', 'universe'),
			'options' => array('right'=>'Right','inside'=>'Inside'),
			'std' => 'right'
		),	
		array(
			'id' => 'mg_zoom_position_mobile',
			'type' => 'select',
			'title' => esc_html__('Zoom Position on Mobile', 'universe'), 
			'desc' => esc_html__('Set magnifier position on mobile devices (iPhone, Android, etc.)', 'universe'),
			'options' => array('default'=>'Default','inside'=>'Inside','disable'=>'Disable'),
			'std' => 'default'
		),	
		array(
			'id' => 'mg_loading_label',
			'type' => 'text',
			'title' => esc_html__('Loading Label', 'universe'),
			'desc' => esc_html__('Set text for magnifier loading...', 'universe'),
			'std' => 'Loading...'
		),
		array(
			'id' => 'mg_lens_opacity',
			'type' => 'text',
			'title' => esc_html__('Lens Opacity', 'universe'),
			'desc' => esc_html__('Set opacity for Lens (0 - 1)', 'universe'),
			'std' => '0.5'
		),
		array(
			'id' => 'mg_blur',
			'type' => 'button_set',
			'title' => esc_html__('Blur Effect', 'universe'), 
			'options' => array('1' => 'Enable','0' => 'Disable'),
			'desc' => esc_html__('Blur effect when Lens hover on product images', 'universe'),
			'std' => '1'
		),
		array(
			'id' => 'mg_thumbnail_slider',
			'type' => 'button_set',
			'title' => esc_html__('Active Slider', 'universe'), 
			'options' => array('1' => 'Enable','0' => 'Disable'),
			'desc' => esc_html__('Enable slider for product thumbnail images', 'universe'),
			'std' => '1'
		),
		array(
			'id' => 'mg_slider_item',
			'type' => 'text',
			'title' => esc_html__('Items', 'universe'),
			'desc' => esc_html__('Number items of Slide', 'universe'),
			'default' => 3
		),
		array(
			'id' => 'mg_thumbnail_circular',
			'type' => 'button_set',
			'title' => esc_html__('Circular Thumbnail', 'universe'), 
			'options' => array('1' => 'Enable','0' => 'Disable'),
			'desc' => esc_html__('Continue slide as a circle', 'universe'),
			'std' => '1'
		),
		array(
			'id' => 'mg_thumbnail_infinite',
			'type' => 'button_set',
			'title' => esc_html__('Infinite Thumbnail', 'universe'), 
			'options' => array('1' => 'Enable','0' => 'Disable'),
			'desc' => esc_html__('Back to first image when end of list', 'universe'),
			'std' => '1'
		),
		
		
		
		
	)

);


$sections[] = array('divide'=>true);
/*
$sections[] = array(
	'id' => 'license',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_044_keys.png',
	'title' => esc_html__('Product License Key', 'universe'),
	'desc' => esc_html__('Submit Theme License Key to get auto-update Universe Theme and Plugins', 'universe'),
	'fields' => array(
		array(
			'id' => 'license',
			'type' => 'license'
		),
	)
);
*/
$sections[] = array(
	'id' => 'import-export',
	'icon' => universe_options_URL.'img/glyphicons/glyphicons_082_roundabout.png',
	'title' => esc_html__('Import / Export', 'universe'),
	'desc' => esc_html__('Import or Export theme options and widgets data', 'universe'),
	'fields' => array(
		array(
			'id' => 'import_data',
			'type' => 'import_data',
			'title' => esc_html__('Import From File', 'universe'), 
			'warning_text' => esc_html__( 'WARNING! This will overwrite all existing option values, please proceed with caution!', 'universe' ),
			'desc' => esc_html__('', 'universe')
		),
		array(
			'id' => 'export_data',
			'type' => 'export_data',
			'title' => esc_html__('Export To File', 'universe'), 
			'desc' => esc_html__('Here you can copy/download your current option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).', 'universe')
		),
	)
);

			
	$tabs = array();
			
	if (function_exists('wp_get_theme')){
		$theme_data = wp_get_theme();
		$UNIVERSE_THEME_URI = $theme_data->get('ThemeURI');
		$description = $theme_data->get('Description');
		$author = $theme_data->get('Author');
		$version = $theme_data->get('Version');
		$tags = $theme_data->get('Tags');
	}else{
		$theme_data = wp_get_theme(trailingslashit(get_stylesheet_directory()).'style.css');
		$UNIVERSE_THEME_URI = $theme_data['URI'];
		$description = $theme_data['Description'];
		$author = $theme_data['Author'];
		$version = $theme_data['Version'];
		$tags = $theme_data['Tags'];
	}	
	
	
	
	if(file_exists(trailingslashit(get_stylesheet_directory()).'README.html')){
		$tabs['theme_docs'] = array(
						'icon' => universe_options_URL.'img/glyphicons/glyphicons_071_book.png',
						'title' => esc_html__('Documentation', 'universe'),
						'content' => nl2br(devnExt::file( 'get', trailingslashit(get_stylesheet_directory()).'README.html'))
						);
	}//if

	global $universe, $universe_options;
	
	$universe_options = new universe_options($sections, $args, $tabs);
	$universe->cfg = get_option( $args['opt_name'] );

}//function
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function video_get_start($field, $value){
	
	switch( $field['id'] ){
		case 'inspector':
		  echo '<ifr'.'ame width="560" height="315" src="http://www.youtube.com/embed/rO8HYqUUbL8?vq=hd720&rel=0&start=76" frameborder="0" allowfullscreen></ifr'.'ame>';
		break;
		case 'grid':
			echo '<ifr'.'ame width="560" height="315" src="http://www.youtube.com/embed/rO8HYqUUbL8?vq=hd720&rel=0" frameborder="0" allowfullscreen></ifr'.'ame>';
		break;
	}

}//function

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';	
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function

function universe_get_template_content( $path ){
	
}
?>