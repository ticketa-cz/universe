<?php
/*
 *
 * Register Postype
 *
 */
if( class_exists( 'KingComposer' ) ){

	$kc = KingComposer::globe();

	$list_postype = array(
		'our_works' => array(
			esc_html__('Our Works', 'universe' ),
			'kc-works',
			'Work',
			'dashicons-book',
			array('title','editor','author','thumbnail','excerpt','page-attributes')
		),
		'kc_mega_menu' => array(
			esc_html__('Mega Menu', 'universe' ),
			'kc_mega_menu',
			'Menu',
			'dashicons-list-view',
			array( 'title', 'editor' )
		)
	);

	kc_tools::register_post_types( $list_postype );

}


/**
 * (C) King-Theme.Com
 * Calls the class on the post edit screen.
 * Meta box version 1.0
 */

function universe_call_metabox() {
    new universe_metabox();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'universe_call_metabox' );
    add_action( 'load-post-new.php', 'universe_call_metabox' );
}

/**
 * The universe_metabox Class.
 */
class universe_metabox {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		//$this->_instance = $instance;

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_action('admin_enqueue_scripts', array($this, 'enqueue_style'));
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_boxes( $post_type ) {

		$meta_box_list = array(
			array(
				'id'			=> 'page_metabox_options',
				'title'			=> UNIVERSE_THEME_NAME.esc_html__( ' Theme - Page Settings', 'universe' ),
				'callback'		=> array( $this, 'page_meta_box' ),
				'screen'		=> array('page'),
				'context'		=> 'advanced',
				'priority'		=> 'high',
				'callback_args'	=> array()
			),
			array(
				'id'			=> 'post_metabox_options',
				'title'			=> esc_html__( 'Post - Options', 'universe' ),
				'callback'		=> array( $this, 'post_meta_box' ),
				'screen'		=> array('post'),
				'context'		=> 'normal',
				'priority'		=> 'high',
				'callback_args'	=> array()
			),
			array(
				'id'			=> 'our_work_metabox_options',
				'title'			=> esc_html__( 'Project\'s - Options', 'universe' ),
				'callback'		=> array( $this, 'our_work_meta_box' ),
				'screen'		=> array('kc-works'),
				'context'		=> 'normal',
				'priority'		=> 'high',
				'callback_args'	=> array()
			)
		);

		$meta_box_list = apply_filters( 'universe_reg_meta_box_list', $meta_box_list );

		foreach($meta_box_list as $meta_box){
			if ( in_array( $post_type, $meta_box['screen'] )) {
				add_meta_box(
					$meta_box['id']
					,$meta_box['title']
					,$meta_box['callback']
					,$post_type
					,$meta_box['context']
					,$meta_box['priority']
					,$meta_box['callback_args']
				);
			}
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
		global $post;
		if( !empty( $_POST['universe'] ) ){
			if( !add_post_meta( $post->ID , '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options' , $_POST['universe'], true ) ){
				update_post_meta( $post->ID , '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options' , $_POST['universe'] );
			}
		}

	}



	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function post_meta_box( $post ) {
		
		global $universe, $universe_options;

		universe_incl_core( 'options'.DS.'options.php' );

		$fields = array(
			array(
				'id'	=> 'feature_video',
				'type'	=> 'text',
				'title'	=> esc_html__('Enter feature video url', 'universe'),
				'std'	=> '',
				'desc'	=> esc_html__('Enter feature video url, for example: https://www.youtube.com/watch?v=YRb-xF0RW-k', 'universe')
			)
		);

		$this->display($fields);
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function page_meta_box( $post ) {
		
		global $universe;

		require_once get_template_directory() .DS.'options'.DS.'options.php';

		$listHeaders = array();
		$listHeaders[ 'default' ] = array('title' => 'Use Global Setting', 'img' => UNIVERSE_THEME_URI.'/templates/header/thumbnails/global.jpg' );

		if ( $handle = $universe->ext['od']( UNIVERSE_THEME_PATH.DS.'templates'.DS.'header' ) ){
			while ( false !== ( $entry = readdir($handle) ) ) {
				if( $entry != '.' && $entry != '..' && strpos($entry, '.php') !== false  ){
					$title  = ucwords( str_replace( '-', ' ', basename( $entry, '.php' ) ) );
					$listHeaders[ 'templates/header/'.$entry ] = array('title' => $title, 'img' => UNIVERSE_THEME_URI.'/templates/header/thumbnails/'.basename( $entry, '.php' ).'.jpg');
				}
			}
		}

		$listBreadcrumbs = array();
		if ( $handle = $universe->ext['od']( UNIVERSE_THEME_PATH.DS.'templates'.DS.'breadcrumb' ) ){
			while ( false !== ( $entry = readdir($handle) ) ) {
				if( $entry != '.' && $entry != '..' && strpos($entry, '.php') !== false  ){
					$title  = ucwords( str_replace( '-', ' ', basename( $entry, '.php' ) ) );
					$listBreadcrumbs[ 'templates/breadcrumb/'.$entry ] = array('title' => $title, 'img' => UNIVERSE_THEME_URI.'/templates/breadcrumb/thumbnails/'.basename( $entry, '.php' ).'.jpg');
				}
			}
		}

		$list_footers_style = array();
		$list_footers_style['global'] = 'From global setting';
		$list_footers_style['empty'] = 'Empty';
		$posts = get_posts( array('post_type' => 'universe_footer', 'posts_per_page' => -1, 'order' => 'ASC') );
		foreach ($posts as $post) {
			$list_footers_style[$post->post_name] = $post->post_title;
		}

		$sidebars = array( '' => '--Select Sidebar--' );

		if( !empty( $universe->cfg['sidebars'] ) ){
			foreach( $universe->cfg['sidebars'] as $sb ){
				$sidebars[ sanitize_title_with_dashes( $sb ) ] = esc_html( $sb );
			}
		}

		$fields = array(
			array(
				'id'       => 'header',
				'type'     => 'profile_template',
				'title'    => esc_html__('Select Header', 'universe'),
				'sub_desc' => esc_html__('Overlap: The header will cover up anything beneath it.', 'universe'),
				'options'  => $listHeaders,
				'std'      => ''
			),
			array(
				'id'       => 'breadcrumb',
				'type'     => 'profile_template',
				'title'    => esc_html__('Display Breadcrumb', 'universe'),
				'options'  => $listBreadcrumbs,
				'std'      => '',
				'sub_desc' => esc_html__( 'Set for show or dont show breadcrumb for this page.', 'universe' )
			),
			array(
				'id'       => 'breadcrumb_bg',
				'type'     => 'upload',
				'title'    => esc_html__('Upload Breadcrumb Background Image', 'universe'),
				'std'      => '',
				'sub_desc' => esc_html__( 'Upload your Breadcrumb background image for this page.', 'universe' )
			),
			array(
				'id'       => 'sidebar',
				'type'     => 'select',
				'title'    => esc_html__('Select Sidebar', 'universe'),
				'options'  => $sidebars,
				'std'      => '',
				'sub_desc' => esc_html__( 'Select template from Page Attributes at right side', 'universe' ),
				'desc'     => '<br /><br />'.esc_html__( 'Select a dynamic sidebar what you created in theme-panel to display under page layout.', 'universe' )
			),
			array(
				'id'       => 'footer_style',
				'type'     => 'footer_styles',
				'title'    => esc_html__('Select Footer Styles', 'universe'),
				'sub_desc' => wp_kses( __('<br />Select footer for all pages, You can also go to each page to select specific.', 'universe'), array( 'br'=>array() )),
				'options'  => $list_footers_style,
				'std'      => 'default'
			)
		);

		echo '<textarea name="universe[vc_cache]" id="universe_vc_cache" style="display:none">'.esc_html( get_post_meta( $post->ID, '_universe_page_vc_cache', true) ).'</textarea>';

		$this->display($fields);
	}


	public function our_work_meta_box(){
		
		global $universe, $universe_options;

		universe_incl_core( 'options'.DS.'options.php' );

		$fields = array(
			array(
				'id'       => 'link',
				'type'     => 'text',
				'title'    => esc_html__('Link', 'universe'),
				'std'      => '',
				'sub_desc' => '',
				'desc'     => ''
			),
			array(
				'id'       => 'outhor',
				'type'     => 'text',
				'title'    => esc_html__('Create by', 'universe'),
				'std'      => '',
				'sub_desc' => '',
				'desc'     => ''
			),
			array(
				'id'       => 'our_date',
				'type'     => 'text',
				'title'    => esc_html__('Date Create', 'universe'),
				'std'      => '',
				'sub_desc' => '',
				'desc'     => ''
			),
			array(
				'id'       => 'images_list',
				'type'     => 'images',
				'title'    => esc_html__('Images', 'universe'),
				'std'      => '',
				'sub_desc' => '',
				'desc'     => ''
			),
			array(
				'id'       => 'video_link',
				'type'     => 'text',
				'title'    => esc_html__('Video Link', 'universe'),
				'std'      => '',
				'sub_desc' => '',
				'desc'     => esc_html__( 'Please insert video link from youtube.com or vimeo.com', 'universe' )
			)
		);

		$this->display($fields);
	}


	public function display($fields){
		$html = $this->_before();
		$html .= $this->ren_fields($fields);
		$html .= $this->_after();

		print( $html );
	}

	public function _before(){
		return '<div class="nhp-opts-group-tab single-page-settings" style="display:block;padding:0px;">
			<table class="form-table" style="border:none;">
				<tbody>';
	}

	public function _after(){
		return '</tbody>
			</table>
		</div>';
	}

	public function ren_fields($fields){
		global $post;
		$post_type = $post->post_type;

		$html = '';

		foreach( $fields as $key => $field ){

			$field_data = get_post_meta( $post->ID,'_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options' , true );
			if($field_data){
				if(!empty($field_data[$field['id']])){
					$field['std'] = $field_data[$field['id']];
				}else{
					$field['std'] = '';
				}

			}else{
				$field['std'] = '';
			}

			if( empty( $field['std'] ) ){
				if( $field['id'] == 'header' ){
					$field['std'] = 'default';
				}
				if( $field['id'] == 'footer' ){
					$field['std'] = 'default';
				}
				if(  $field['id'] == 'breadcrumb' ){
					$field['std'] = 'global';
				}
			}
			
			universe_incl_core( 'options'.DS.'fields'.DS.$field['type'].'/field_'.$field['type'].'.php' );

			$field_class = UNIVERSE_THEME_OPTNAME.'_options_'.$field['type'];

			if( class_exists( $field_class ) ){

				$render = '';
				$obj = new stdClass();
				$obj->extra_tabs = '';
				$obj->sections = '';
				$obj->args = '';
				$render = new $field_class($field, $field['std'], $obj );

				$html .= '<tr><th scope="row">'.(isset($field['title'])?esc_html($field['title']):'').'<span class="description">';
				$html .= (isset($field['sub_desc'])?esc_html($field['sub_desc']):'').'</span></th>';
				$html .= '<td>';

				ob_start();
				$render->render();
				$html .= ob_get_clean();

				if( method_exists( $render, 'enqueue' ) ){
					$render->enqueue();
				}

				$html .= '</td></tr>';
			}
		}

		return $html;
	}

	public function enqueue_style(){
		wp_enqueue_style('universe-metabox-admin', UNIVERSE_THEME_URI.'/core/assets/css/metabox-admin.css', false, time() );
	}
}