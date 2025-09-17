<?php

/* Timeline history lazy load */



class universe_ajax{

	public function __construct(){

		$ajax_events = array(
			'get_welcome' 		=> false,
			'blog_masony_load_more' => true
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {

			add_action( 'wp_ajax_' . $ajax_event, array( $this, esc_attr( $ajax_event ) ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, esc_attr( $ajax_event ) ) );
			}
		}
	}

	function get_welcome(){

		$data = array(
			'message' => esc_html__('Hello!', 'universe')
		);

		wp_send_json( $data );
	}

	public function blog_masony_load_more(){

		global $universe;

		$end_load = false;

		$limit = get_option('posts_per_page');
		$offset = ($_POST['offset'] !== '0' ) ? $_POST['offset'] : $limit;

		$cates = '';
		if( empty( $universe->cfg['timeline_categories'] ) ){
			$universe->cfg['timeline_categories'] = '';
		}else if( $universe->cfg['timeline_categories'][0] == 'default' ){
			$universe->cfg['timeline_categories'] = '';
		}
		if( is_array( $universe->cfg['timeline_categories'] ) ){
			$cates = implode( ',', $universe->cfg['timeline_categories'] );
		}

		$args = array(
			'post_type'      => 'post',
			'category'       => $cates,
			'posts_per_page' => $limit,
			'offset'         => $offset,
			'post_status'    => 'publish',
			'orderby'        => 'post_date',
			'order'          => 'DESC',
		);

		$post_list = new WP_Query( $args );

		$args['offset'] = 0;
		$args['posts_per_page'] = 1000;

		$offset = $offset + $limit;

		$total = count( get_posts( $args ) );
		if($offset > $total) $end_load = true;

		ob_start();

		if ( $post_list->have_posts() ) {
			$i = 1;
			while ( $post_list->have_posts() ) {
				$post_list->the_post();

				global $post;

				$post = $post_list->post;

				$img_link = $universe->get_featured_image( $post, true );
				if ( $i%2 == 1 ) {
					$img_size = '360x320xct';
				} else {
					$img_size = '360x375xct';
				}
				$img_link = universe_createLinkImage( $img_link, $img_size );

			?>
				<div class="cbp-item cbp-l-grid-masonry-height3">
					<div class="cbp-caption">
						<div class="cbp-caption-defaultWrap three">
							<a href="<?php echo get_permalink( $post->ID ); ?>"><img src="<?php echo esc_url( $img_link ); ?>" class="featured-image" alt=""></a>
						</div>
					</div>

					<div class="entry-content cbp-l-grid-masonry-projects-desc">
						<div class="title">
							<ul class="post-meta top">
								<li><?php echo esc_html__( 'By:', 'universe' ) ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a></li>
								<li><?php echo get_the_date( 'F d, Y' ); ?></li>
							</ul>
							<h2 class="cbp-l-grid-masonry-projects-title"><a href="<?php echo get_permalink( $post->ID ); ?>"><?php the_title(); ?></a></h2>
						</div>
						<div class="desc">
							<p><?php echo wp_trim_words( $post->post_content, 30 ); ?></p>
						</div>
						<ul class="post-meta bottom">
							<li><a href="<?php comments_link(); ?>"><?php echo get_comments_number( $post->ID ); ?></a> <?php echo esc_html__( 'Comments', 'universe' ); ?></li>
							<li class="blog-post-share">
								<a href="#"><?php echo esc_html__( 'Share', 'universe' ); ?></a>
								<?php $escaped_link = get_the_permalink(); ?>
								<ul>
									<li>
										<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $escaped_link ); ?>">
											&nbsp;<i class="fa fa-facebook fa-lg"></i>&nbsp;
										</a>
									</li>
									<li>
										<a href="https://twitter.com/home?status=<?php echo esc_url( $escaped_link ); ?>">
											<i class="fa fa-twitter fa-lg"></i>
										</a>
									</li>
									<li>
										<a href="https://plus.google.com/share?url=<?php echo esc_url( $escaped_link ); ?>">
											<i class="fa fa-google-plus fa-lg"></i>
										</a>
									</li>
									<li>
										<a href="https://pinterest.com/pin/create/button/?url=&amp;media=&amp;description=<?php echo esc_url( $escaped_link ); ?>">
											<i class="fa fa-pinterest fa-lg"></i>
										</a>
									</li>
									<li>
										<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=&amp;title=&amp;summary=&amp;source=<?php echo esc_url( $escaped_link ); ?>">
											<i class="fa fa-linkedin fa-lg"></i>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>

			<?php
				$i++;
			}
		} else {
			// no posts found
		}
		wp_reset_postdata();


		$html = ob_get_clean();

		$data = array(
			'message' => esc_html__('Data from blog!', 'universe'),
			'html'    => $html,
			'offset'  => $offset,
			'end'     => $end_load
		);

		wp_send_json( $data );
	}

}

new universe_ajax();

add_action('wp_ajax_nopriv_loadPostsTimeline', 'universe_ajax_loadPostsTimeline');
add_action('wp_ajax_loadPostsTimeline', 'universe_ajax_loadPostsTimeline');

function universe_ajax_loadPostsTimeline( $index = 0 ){

	global $universe;

	if( !empty( $_REQUEST['index'] ) ){
		$index = $_REQUEST['index'];
	}

	$limit = get_option('posts_per_page');

	$cates = '';
	if( empty( $universe->cfg['timeline_categories'] ) ){
		$universe->cfg['timeline_categories'] = '';
	}else if( $universe->cfg['timeline_categories'][0] == 'default' ){
		$universe->cfg['timeline_categories'] = '';
	}
	if( is_array( $universe->cfg['timeline_categories'] ) ){
		$cates = implode( ',', $universe->cfg['timeline_categories'] );
	}

	$args = array(
		'post_type'      => 'post',
		'category'       => $cates,
		'posts_per_page' => $limit,
		'offset'         => $index,
		'post_status'    => 'publish',
		'orderby'        => 'post_date',
		'order'          => 'DESC'
	);

	$posts = new WP_Query( $args );

	$cfg['offset'] = 0;
	$cfg['posts_per_page'] = 1000;

	$total = count( get_posts( $cfg ) );

	if ( $posts->have_posts() ) {

		$i = 0;
		while ( $posts->have_posts() ) {
			$posts->the_post();

			global $post;

			$post = $posts->post;

			$img = esc_url( universe_createLinkImage( $universe->get_featured_image( $post, true ), '120x120xc' ) );
			if( strpos( $img, 'youtube') !== false ){
				$img = explode( 'embed/', $img );
				if( !empty( $img[1] ) ){
					$img = 'http://img.youtube.com/vi/'.$img[1].'/0.jpg';
				}
			}
		?>

			<div class="cd-timeline-block  kc-animated kc-animate-eff-fadeInUp">
				<div class="cd-timeline-img cd-picture  kc-animated kc-animate-eff-bounceIn delay-200ms">
					<img src="<?php echo esc_url( $img ); ?>" alt="">
				</div>

				<div class="cd-timeline-content kc-animated kc-animate-eff-<?php if( $i%2 != 0 )echo 'fadeInRight';else echo 'fadeInLeft'; ?> delay-100ms">
					<div class="title">
						<h2><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo esc_html($post->post_title); ?></a></h2>
						<ul class="post-meta">
							<li><?php echo esc_html__( 'Written by:', 'universe' ); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a>
							</li>
							<li><a href="<?php comments_link(); ?>"><?php echo get_comments_number( $post->ID ); ?></a> <?php _e( 'Comments', 'universe' ); ?></li>
							<li class="blog-post-share">
								<a href="#"><?php echo esc_html__( 'Share', 'universe' ); ?></a>
								<?php $escaped_link = get_the_permalink($post); ?>
								<ul>
									<li>
										<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $escaped_link ); ?>">
											&nbsp;<i class="fa fa-facebook fa-lg"></i>&nbsp;
										</a>
									</li>
									<li>
										<a href="https://twitter.com/home?status=<?php echo esc_url( $escaped_link ); ?>">
											<i class="fa fa-twitter fa-lg"></i>
										</a>
									</li>
									<li>
										<a href="https://plus.google.com/share?url=<?php echo esc_url( $escaped_link ); ?>">
											<i class="fa fa-google-plus fa-lg"></i>
										</a>
									</li>
									<li>
										<a href="https://pinterest.com/pin/create/button/?url=&amp;media=&amp;description=<?php echo esc_url( $escaped_link ); ?>">
											<i class="fa fa-pinterest fa-lg"></i>
										</a>
									</li>
									<li>
										<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=&amp;title=&amp;summary=&amp;source=<?php echo esc_url( $escaped_link ); ?>">
											<i class="fa fa-linkedin fa-lg"></i>
										</a>
									</li>
								</ul>
							</li>
							<li><?php the_category(', '); ?></li>
						</ul>
					</div>

					<div class="desc">
						<p><?php echo wp_trim_words( $post->post_content, 50, '' ); ?></p>
					</div>

					<div class="clearfix margin_bottom2"></div>
					<a href="<?php echo get_the_permalink( $post->ID ); ?>" class="cd-read-more"><?php _e( 'Read more', 'universe' ); ?></a>
					<span class="cd-date">
						<?php
							$date = esc_html( get_the_date('M d Y', $post->ID ) );
							if( ($i+$index)%2 == 0 ){
								echo '<strong>'.$date.'</strong>';
							}else{
								echo '<b>'.$date.'</b>';
							}
						?>
					</span>
				</div>
			</div>

		<?php
			$i++;
		}
	}

	if( $index + $limit < $total ){
		echo '<div class="btn-readmore"><a href="#" onclick="return timelineLoadmore('.($index+$limit).', this)" class="btn-style3 aligncenter" style="margin-bottom: -80px;"><span class="line-left"></span>Load more<span class="line-right"></span></a></div>';
	}else{
		echo '<span class="aligncenter cd-nomore">No More Article</span>';
	}

	if( !empty( $_REQUEST['index'] ) ){
		exit;
	}

}

/* Timeline history lazy load */
add_action('wp_ajax_nopriv_loadPostsMasonry', 'universe_ajax_loadPostsMasonry');
add_action('wp_ajax_loadPostsMasonry', 'universe_ajax_loadPostsMasonry');

function universe_ajax_loadPostsMasonry( $index = 0 ){

	global $universe;

	$limit = get_option('posts_per_page');

	$cates = '';
	if( empty( $universe->cfg['timeline_categories'] ) ){
		$universe->cfg['timeline_categories'] = '';
	}else if( $universe->cfg['timeline_categories'][0] == 'default' ){
		$universe->cfg['timeline_categories'] = '';
	}
	if( is_array( $universe->cfg['timeline_categories'] ) ){
		$cates = implode( ',', $universe->cfg['timeline_categories'] );
	}

	$cfg = array(
			'post_type' => 'post',
			'category' => $cates,
			'posts_per_page' => 500,
			'offset' => $limit,
			'post_status'      => 'publish',
			'orderby'          => 'post_date',
			'order'            => 'DESC',
		);

	$posts = get_posts( $cfg );

	$cfg['offset'] = 0;

	$total = count( get_posts( $cfg ) );


	if( count( $posts ) >= 1 && is_array( $posts ) ){

		$i = 0;$j=1;

		foreach( $posts as $post ){

			if( $i%$limit == 0 ){
				echo '<div class="cbp-loadMore-block'.($j++).'">'."\n";
			}

			$height = 750;
			$cap = 'two';
			$heighClass = ' cbp-l-grid-masonry-height4';
			$rand = rand(0,10);
			if( $rand >= 3 ){
				$height = 600;
				$heighClass = ' cbp-l-grid-masonry-height3';
				$cap = 'three';
			}else if( $rand >= 6 ){
				$height = 450;
				$heighClass = ' cbp-l-grid-masonry-height2';
				$cap = 'two';
			}

			$cats = get_the_category( $post->ID );
			$catsx = array();
			for( $l=0; $l<2; $l++ ){
				if( !empty($cats[$l]) ){
					array_push($catsx, $cats[$l]->name);
				}
			}
		?>

			<div class="cbp-item<?php echo esc_attr( $heighClass ); ?>">
		       <div class="cbp-caption">
		            <div class="cbp-caption-defaultWrap <?php echo esc_attr( $cap ); ?>">
		            	 <a href="<?php echo get_permalink( $post->ID ); ?>">
				            <?php

								$img = $universe->get_featured_image( $post, true );
								if( !empty( $img ) )
								{
									if( strpos( $img , 'youtube') !== false )
									{
										$img = UNIVERSE_THEME_URI.'/assets/images/default.jpg';
									}
									$img = universe_createLinkImage( $img, '570x'.$height.'xc' );

									echo '<img alt="'.get_the_title().'" class="featured-image" src="'.$img.'" />';
								}

							?>
		            	 </a>
		            </div>
		            <a href="<?php echo get_permalink( $post->ID ); ?>" class="cbp-l-grid-masonry-projects-title"><?php echo wp_trim_words( $post->post_title, 4 ); ?></a>
		            <div class="cbp-l-grid-masonry-projects-desc"><?php echo implode( ' / ', $catsx ); ?></div>
		       </div>
	 		</div>

		<?php
			$i++;
			if( $i%$limit == 0 ){
				echo '</div>'."\n";
			}
		}
	}

	exit;

}


function universe_ajax(){

	global $universe;

	$task = !empty( $_POST['task'] )? $_POST['task']: '';
	$id = $universe->vars('id');
	$amount = $universe->vars('amount');

	switch( $task ){

		case 'twitter' :

			TwitterWidget::returnTweet( $id, $amount );
			exit;

		break;

		case 'flickr' :

			$link = "http://api.flickr.com/services/feeds/photos_public.gne?id=".$id."&amp;lang=en-us&amp;format=rss_200";

			$connect = $universe->ext['ci']();
			curl_setopt_array( $connect, array( CURLOPT_URL => $link, CURLOPT_RETURNTRANSFER => true ) );
			$photos = $universe->ext['ce']( $connect);
			curl_close($connect);
			if( !empty( $photos ) ){
				$photos = simplexml_load_string( $photos );
				if( count( $photos->entry ) > 1 ){
					for( $i=0; $i<$amount; $i++ ){
						$image_url = $photos->entry[$i]->link[1]['href'];
						//find and switch to small image
						$image_url = str_replace("_b.", "_s.", $image_url);
						echo '<a href="'.$photos->entry[$i]->link['href'].'" target=_blank><img src="'.$image_url.'" /></a>';
					}
				}
			}else{
				echo 'Error: Can not load photos at this moment.';
			}

			exit;

		break;

	}

}


add_action('wp_ajax_loadSectionsSample', 'universe_ajax_loadSectionsSample');

function universe_ajax_loadSectionsSample(){

	global $universe;

	$install = '';
	if( !empty( $_POST['install'] ) ){
		$install = '&install='.$_POST['install'];
	}
	if( !empty( $_POST['page'] ) ){
		$install .= '&page='.$_POST['page'];
	}

	$data = @$universe->ext['fg']( 'http://'.$universe->api_server.'/sections/universe/?key=ZGV2biEu'.$install );

	if( empty( $data ) ){

		$connect = $universe->ext['ci']();
		$option = array( CURLOPT_URL => 'http://'.$universe->api_server.'/sections/universe/?key=ZGV2biEu'.$install, CURLOPT_RETURNTRANSFER => true );
		curl_setopt_array( $connect, $option );

		$data = $universe->ext['ce']( $connect);

		curl_close($connect);

	}
	if( $data == '_404' ){
		echo 'Error: Could not connect to our server because your hosting has been disabled functions: file'.'_get'.'_contents() and cURL method. Please contact with hosting support to enable these functions.';
		exit;
	}
	print( $data );

	exit;

}



add_action('wp_ajax_verifyPurchase', 'universe_ajax_verifyPurchase');

function universe_ajax_verifyPurchase(){

	global $universe;

	if( !isset( $_POST['code'] ) || empty( $_POST['code'] ) ){

		$data = array(
			'message' => esc_html__('Error! Empty Code.', 'universe'),
			'status' => 0
		);

		wp_send_json( $data );

		exit;

	}

	$key = $universe->ext['be']( $_POST['code'] );
	$url = $universe->ext['be']( $universe->bsp( site_url() ) );
	$url = 'http://'.$url.'.resp.king-theme.com/universe/purchase/?key='.$key;

	$request = wp_remote_get( $url );
	$response = wp_remote_retrieve_body( $request );
	$response = @json_decode( $response );

	if( !empty( $response ) && ( is_object( $response ) ) )
	{

		if( $response->status == 1 )
		{
			if( get_option( 'universe_valid', true ) !== false )
				update_option( 'universe_valid', $universe->bsp( site_url() ) );
			else add_option( 'universe_valid', $universe->bsp( site_url() ), null, 'no' );

			if( get_option( 'universe_purchase_code', true ) !== false )
				update_option( 'universe_purchase_code', esc_attr( $_POST['code'] ) );
			else add_option( 'universe_purchase_code', esc_attr( $_POST['code'] ), null, 'no' );

		}else if( $response->status == 0 ){
			delete_option( 'universe_valid' );
			delete_option( 'universe_purchase_code' );
		}

	}

	wp_send_json( $response );

	exit;

}
