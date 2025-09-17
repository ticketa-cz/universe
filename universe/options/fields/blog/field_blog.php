<?php
class universe_options_blog extends universe_options{

	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since universe_options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){

		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();

	}



	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since universe_options 1.0
	*/
	function render(){


	if( isset( $_REQUEST['settings-updated'] ) && isset( $_REQUEST['page'] ) ){

		if( $_REQUEST['settings-updated'] == 'true' && $_REQUEST['page'] == strtolower(UNIVERSE_THEME_SLUG).'-panel' ){

			$options = get_option($this->args['opt_name']);
			update_option('show_on_front',isset($options['show_on_front'])?$options['show_on_front']:get_option('show_on_front'));
			update_option('page_on_front',isset($options['page_on_front'])?$options['page_on_front']:get_option('page_on_front'));
			update_option('page_for_posts',isset($options['page_for_posts'])?$options['page_for_posts']:get_option('page_for_posts'));
			update_option('posts_per_page',isset($options['posts_per_page'])?$options['posts_per_page']:get_option('posts_per_page'));
			update_option('posts_per_rss',isset($options['posts_per_rss'])?$options['posts_per_rss']:get_option('posts_per_rss'));
			update_option('rss_use_excerpt',isset($options['rss_use_excerpt'])?$options['rss_use_excerpt']:get_option('rss_use_excerpt'));
		}
	}

?>

	<table class="form-table blog-table-opt" style="border: none;margin-top: 0px;">

		<tr style="">
			<th scope="row">
				<label for="blog-breadcrumb">
					<?php esc_html_e( 'Blog breadcrumb heading' , 'universe'); ?>
				</label>
			</th>
			<td>
				
				<?php
					global $universe;
					$h1 = ''; $h2 = '';
					if (!empty($universe->cfg['blog_breadcrumb'])) {
						if (!empty($universe->cfg['blog_breadcrumb']['heading']))
							$h1 = $universe->cfg['blog_breadcrumb']['heading'];
						if (!empty($universe->cfg['blog_breadcrumb']['heading_sub']))
							$h2 = $universe->cfg['blog_breadcrumb']['heading_sub'];
					}
				?>
				<input type="text" name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[blog_breadcrumb][heading]" value="<?php echo esc_attr($h1); ?>" class="regular-text" />
				<br />
				<br />
				<strong><?php _e('Sub heading', 'universe'); ?></strong>
				<textarea style="height: 230px;" name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[blog_breadcrumb][heading_sub]" class="large-text"><?php echo esc_attr($h2); ?></textarea>
				<input type="text" style="display: none;" name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[blog_breadcrumb][_file_]" value="templates/breadcrumb/for-blog.php" />
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="blog-sidebar"><?php esc_html_e( 'Blog breadcrumb background' , 'universe'); ?></label></th>
			<td>

				<?php
					$blog_breadcrumb_bg = '';

					if(  !empty( $universe->cfg['blog_breadcrumb_bg'] ) ){
						$blog_breadcrumb_bg = $universe->cfg['blog_breadcrumb_bg'];
					}
				?>

				<div class="king-upload-wrp">
					<input type="hidden" id="<?php echo esc_attr( $this->field['id'] .'_breadcrumb_bg' ); ?>" name="<?php echo esc_attr( $this->args['opt_name'].'[blog_breadcrumb_bg]' ); ?>" value="<?php echo esc_attr( $blog_breadcrumb_bg ); ?>" class="king-upload-input" />

					<img style="max-width: 100%; cursor: pointer;<?php if( empty($blog_breadcrumb_bg) ){echo 'display: none;';} ?>" src="<?php echo esc_url( !empty($blog_breadcrumb_bg)?(str_replace( '%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, $blog_breadcrumb_bg )):'' ); ?>" class="king-upload-image" />
					<p>
						<button class="button button-large button-primary king-upload-button">
							<i class="fa fa-cloud-upload"></i> Upload Image
						</button>
						&nbsp;
						<button <?php if( empty($blog_breadcrumb_bg) ){echo ' style="display: none;" ';} ?> class="button button-large king-upload-button-remove">
							<i class="fa fa-times"></i> Remove Image
						</button>
					</p>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="blog-sidebar"><?php esc_html_e( 'Blog Sidebar' , 'universe'); ?></label></th>
			<td>
				<?php
					global $universe;
					$std = '';
					if(  !empty( $universe->cfg['blog_sidebar'] ) ){
						$std = $universe->cfg['blog_sidebar'];
					}

				?>

				<select name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[blog_sidebar]">
					<option <?php if( $std == '' )echo 'selected'; ?> value=""><?php echo esc_html__( '--Select Sidebar--', 'universe' ); ?></option>
					<?php
						if( !empty( $universe->cfg['sidebars'] ) ){
							foreach( $universe->cfg['sidebars'] as $sb ){
					?>
								<option <?php if( $std == sanitize_title_with_dashes( $sb ) )echo 'selected'; ?> value="<?php echo sanitize_title_with_dashes( $sb ); ?>"><?php echo esc_html( $sb ); ?></option>
					<?php
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blog-layout"><?php esc_html_e( 'Blog Layout' , 'universe'); ?></label></th>
			<td>
				<?php
					global $universe;
					$std = 'default';
					if(  !empty( $universe->cfg['blog_layout'] ) ){
						$std = $universe->cfg['blog_layout'];
					}
				?>

				<select name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[blog_layout]">
					<option <?php if( $std == 'default' )echo 'selected'; ?> value="default">Default</option>
					<option <?php if( $std == 'large' )echo 'selected'; ?> value="large">Large Image</option>
					<option <?php if( $std == 'medium' )echo 'selected'; ?> value="medium">Medium Image</option>
					<option <?php if( $std == 'small' )echo 'selected'; ?> value="small">Small Image (2 columns)</option>
					<option <?php if( $std == 'masonry' )echo 'selected'; ?> value="masonry">Masonry</option>
					<option <?php if( $std == 'timeline' )echo 'selected'; ?> value="timeline">Time line </option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blog-layout"><?php esc_html_e( 'Categories for blog' , 'universe'); ?></label></th>
			<td>
				<?php
					global $universe;
					$std = array();
					if(  !empty( $universe->cfg['timeline_categories'] ) ){
						$std = $universe->cfg['timeline_categories'];
					}

					$cates = $universe->get_terms( 'category' );

				?>

				<select style="width: 450px;height: 200px;" multiple="" name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[timeline_categories][]">
					<option <?php if( $std == 'default' )echo 'selected'; ?> value="default">All Categories</option>
					<?php

						foreach( $cates as $k => $v ){

							echo '<option';
							if( in_array( $k, $std ) ){
								echo ' selected';
							}
							echo ' value="'.esc_attr($k).'">'.esc_html($v).'</option>';
						}

					?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="posts_per_page"><?php esc_html_e( 'Blog pages show at most' , 'universe'); ?></label></th>
			<td>
				<input name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[posts_per_page]" type="number" step="1" min="1" id="posts_per_page" value="<?php form_option( 'posts_per_page' ); ?>" class="small-text regular-text" /> <?php esc_html_e( 'posts' , 'universe'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="posts_per_rss"><?php esc_html_e( 'Syndication feeds show the most recent', 'universe' ); ?></label></th>
			<td><input name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[posts_per_rss]" type="number" step="1" min="1" id="posts_per_rss" value="<?php form_option( 'posts_per_rss' ); ?>" class="small-text regular-text" /> <?php esc_html_e( 'items', 'universe' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'For each article in a feed, show' , 'universe'); ?> </th>
			<td><fieldset><legend class="screen-reader-text regular-text"><span><?php esc_html_e( 'For each article in a feed, show', 'universe' ); ?> </span></legend>
			<p><label><input name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[rss_use_excerpt]" type="radio" value="0" <?php checked( 0, get_option( 'rss_use_excerpt' ) ); ?>	/> <?php esc_html_e( 'Full text', 'universe' ); ?></label><br />
			<label><input name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[rss_use_excerpt]" type="radio" value="1" <?php checked( 1, get_option( 'rss_use_excerpt' ) ); ?> /> <?php esc_html_e( 'Summary', 'universe' ); ?></label></p>
			</fieldset></td>
		</tr>
	</table>
	<input name="<?php echo esc_attr( $this->args['opt_name'] ); ?>[opt_version]" type="hidden" value="<?php echo time(); ?>" />
	<script type="text/javascript">
	//<![CDATA[
		jQuery(document).ready(function($){
			var section = $('#front-static-pages'),
				staticPage = section.find('input:radio[value="page"]'),
				selects = section.find('select'),
				check_disabled = function(){
					selects.prop( 'disabled', ! staticPage.prop('checked') );
				};
			check_disabled();
	 		section.find('input:radio').change(check_disabled);
	 		jQuery('.blog-table-opt').parent().prev().hide();
		});
	//]]>
	</script>


	<?php

	}//function



	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since universe_options 1.0
	*/
	function enqueue(){


	}//function

}