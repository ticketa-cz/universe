<?php
	/**
	*
	* @author king-theme.com
	*
	*/

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $universe, $post, $more;

	function universe_masonry_assets() {
		$css_dir = UNIVERSE_THEME_URI.'/assets/css/';
		$js_dir = UNIVERSE_THEME_URI.'/assets/js/';

		wp_enqueue_style('universe-cubeportfolio-min', $js_dir.'cubeportfolio/cubeportfolio.min.css', false, UNIVERSE_THEME_VERSION );
		wp_enqueue_script('universe-cubeportfolio', universe_child_theme_enqueue( $js_dir.'cubeportfolio/js/jquery.cubeportfolio.min.js' ), array( 'jquery' ), UNIVERSE_THEME_VERSION, true );
		wp_enqueue_script('universe-cubeportfolio-main-blog', universe_child_theme_enqueue( $js_dir.'cubeportfolio/main-blog.js' ), array( 'jquery' ), UNIVERSE_THEME_VERSION, true );
	}
	add_action('wp_print_styles', 'universe_masonry_assets');

	get_header();

?>

<?php universe::path( 'blog_breadcrumb' ); ?>

<div id="main-pages" class="main-posts-pages">
	<div class="container">
		<div class="row">
			<div class="col-md-12 content_full">
				<div class="main-content">

					<div id="grid-masonry-container" class="cbp-l-grid-masonry-posts" data-cols="3" data-gap="70|30">
						<?php
							$i = 1;
							while ( have_posts() ) : the_post();

								$img_link = $universe->get_featured_image( $post, true );
								if ( $i%2 == 1 ) {
									$img_size = '360x320xct';
								} else {
									$img_size = '370x280xct';
								}
								$img_link = universe_createLinkImage( $img_link, $img_size );
						?>

							<div class="cbp-item cbp-l-grid-masonry-height3">
								<a href="<?php echo get_permalink( $post->ID ); ?>"><img src="<?php echo esc_url( $img_link ); ?>" class="featured-image" alt=""></a>

								<div class="entry-content">
									<div class="title">
										<ul class="post-meta top">
											<li><?php echo esc_html__( 'By:', 'universe' ) ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a></li>
											<li><?php echo get_the_date( 'F d, Y' ); ?></li>
										</ul>
										<h2><a href="<?php echo get_permalink( $post->ID ); ?>"><?php the_title(); ?></a></h2>
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

							<?php $i++; ?>
						<?php endwhile; ?>

					</div><!-- #grid-container -->

					<!-- Load More Posts -->
					<div id="loadMore-container" class="cbp-l-loadMore-button hiddenf btn-readmore">
						<a href="#" class="cbp-l-loadMore-link btn-style3" data-offset="0"><span class="line-left"></span><?php echo esc_html__( 'Load More Posts', 'universe' ); ?><span class="line-right"></span></a>
					</div>

				</div>
			</div>
		</div>
	</div>
</div><!-- #primary -->

<?php get_footer(); ?>