<?php

/**
* Footer manager
*/
class universe_footers
{

	function __construct()
	{
		# code...
		$this->init();
	}

	public function init(){

		global $universe;

		if(is_admin()){
			add_action( 'init', array( &$this, 'universe_register_footer_post_type') );
			add_action( 'init', array( &$this, 'universe_set_kingcomposer') );

			add_filter( 'manage_edit-universe_footer_columns', array( &$this, 'universe_edit_footer_columns') ) ;
			add_action( 'manage_universe_footer_posts_custom_column', array( &$this, 'universe_manage_footer_columns'), 10, 2 );

			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post',      array( $this, 'save'         ) );
		}

		$universe->ext['asc']( 'universe_footer', array( &$this, 'universe_show_footer') );
	}

	public function universe_set_kingcomposer(){
		global $kc;
		if( isset( $kc ) ){
			$kc->add_content_type( 'universe_footer' );
			$kc->add_content_type( 'kc_mega_menu' );
		}
			
	}

	public static function _get(){
		$args = array(
			'post_type' => 'universe_footer'
		);

		$posts = get_posts( $args );

		return $posts;
	}

	public function universe_show_footer( $atts ) {

		global $kc;
		$atts = shortcode_atts( array(
			'alias' => '',
		), $atts, 'universe_footer' );

		ob_start();

		if( isset($atts['alias']) ){
			$post = get_page_by_path( $atts['alias'], OBJECT , 'universe_footer' );

			if ( $post ) {
				if (isset($kc))
				{
					if(isset($post->post_content_filtered) && !empty($post->post_content_filtered)){
						echo kc_do_shortcode($post->post_content_filtered);
					}else{
						echo kc_do_shortcode($post->post_content);
					}
				}else{
					echo do_shortcode($post->post_content);
				}
			} else {
				return null;
			}
		}

		$content = ob_get_clean();

		return $content;
	}

	public function universe_edit_footer_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => esc_html__( 'Footer style', 'universe' ),
			'fw_preview' => esc_html__( 'Preview', 'universe' ),
			'date' => esc_html__( 'Date', 'universe' )
		);

		return $columns;
	}

	public function universe_manage_footer_columns( $column, $post_id ) {
		global $post;

		switch( $column ) {

			case 'fw_preview' :
				$preview = get_post_meta( $post->ID, '_universe_footer_preview', true );

				if( !empty($preview) ){
					$preview_url = get_template_directory_uri() .'/core/footers/previews/'. $preview;
					echo '<img src="'. esc_url( $preview_url ) .'" style="max-width:100%;margin-top:10px;" />';
				}else{
					echo esc_html__('No preview set', 'universe');
				}

				break;

			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
		// Limit meta box to certain post types.
		$post_types = array( 'universe_footer' );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'meta_box_preview',
				esc_html__( 'Preview', 'universe' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'side',
				'high'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['universe_inner_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['universe_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'universe_inner_custom_box' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, it's safe for us to save the data now. */

		// Sanitize the user input.
		$mydata = sanitize_text_field( $_POST['universe_preview_field'] );

		// Update the meta field.
		update_post_meta( $post_id, '_universe_footer_preview', $mydata );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'universe_inner_custom_box', 'universe_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$_value = get_post_meta( $post->ID, '_universe_footer_preview', true );

		// Display the form, using the current value.

		$preview_file = array_diff(scandir(get_template_directory().DS.'core'.DS.'footers'.DS.'previews'), array('..', '.'));
		//print_r($preview_file);

		_e( 'Footer widget preview', 'universe' );
		echo '<select name="universe_preview_field">';
		foreach ($preview_file as $value) {
			if($value == $_value)
				echo '<option value="'. $value .'" selected="selected">'.$value.'</option>';
			else
				echo '<option value="'. $value .'">'.$value.'</option>';
		}
		echo '</select>';

		if(!empty($_value)){
			echo '<img src="'. get_template_directory_uri() .'/core/footers/previews/'. $_value .'" style="max-width:100%;margin-top:10px;" />';
		}

		echo '<br />';

		$shortcode = '[universe_footer alias="'. $post->post_name .'"]';

		echo '<input type="text" name="shortcode" value="'. esc_attr( $shortcode ) .'" readonly="readonly" />';

	}

	public function universe_register_footer_post_type(){

		$universe = universe::globe();

		$labels = array(
			'name'               => _x( 'Footer widgets', 'post type general name', 'universe' ),
			'singular_name'      => _x( 'Footer widget', 'post type singular name', 'universe' ),
			'menu_name'          => _x( 'Theme Footers', 'admin menu', 'universe' ),
			'name_admin_bar'     => _x( 'Theme Footers', 'add new on admin bar', 'universe' ),
			'add_new'            => _x( 'Add New', 'book', 'universe' ),
			'add_new_item'       => esc_html__( 'Add New Footer widget', 'universe' ),
			'new_item'           => esc_html__( 'New Footer Widget', 'universe' ),
			'edit_item'          => esc_html__( 'Edit Footer Widget', 'universe' ),
			'view_item'          => esc_html__( 'View Footer Widget', 'universe' ),
			'all_items'          => esc_html__( 'Manage Footers', 'universe' ),
			'search_items'       => esc_html__( 'Search footer widget', 'universe' ),
			'parent_item_colon'  => esc_html__( 'Parent footer widget:', 'universe' ),
			'not_found'          => esc_html__( 'No footer widget found.', 'universe' ),
			'not_found_in_trash' => esc_html__( 'No footer widget found in Trash.', 'universe' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			//'show_in_menu'       => UNIVERSE_THEME_SLUG.'-footers-manage',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'universe_footer' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 200,
			'supports'           => array( 'title', 'editor' ),
		);

		$universe->ext['rpt']( 'universe_footer', $args );
	}
}

new universe_footers();