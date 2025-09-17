<?php
/**
 * (c) king-theme.com
 */
	$universe = universe::globe();
	$post = universe::globe('post');
	$more = universe::globe('more');

	$thumbnail_url = $universe->get_featured_image( $post );
	//$thumbnail_url = universe_createLinkImage( $thumbnail_url, '831x369xc' );

	$categories_list = get_the_category_list( ', ' );
	$post_custom_field = get_post_meta($post->ID , '_'.UNIVERSE_THEME_OPTNAME.'_post_meta_options', true);
	$noimg = true;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
	
	<?php if(!empty($post_custom_field['feature_video'])): ?>
	<div class="entry-header">
		<figure>
		<?php
			$noimg = false;
			echo preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<ifr"."ame width=\"831\" height=\"369\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></ifr"."ame>", $post_custom_field['feature_video'] );
		?>
		</figure>
	</div>
	<?php else: ?>
		<?php if ( has_post_thumbnail( $post->ID ) ): $noimg = false; ?>
		<div class="entry-header">
			<figure>
			<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
			</figure>
		</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="entry-content">
		<?php if(!$noimg){ ?>
		<div class="box-date">
			<span><?php echo get_the_date('d'); ?></span>
			<em><?php echo get_the_date('M'); ?></em>
		</div>
		<?php } ?>
		<div class="title">
			<h2><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
			<ul class="post-meta">
				<li><?php echo esc_html__( 'Written by:', 'universe' ); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a>
				</li>
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
						<?php if($noimg){ ?>
						<li class="meta-date">
							<?php echo get_the_date('d M Y'); ?>
						</li>
						<?php } ?>
					</ul>
				</li>
				<li><?php the_category(', '); ?></li>
			</ul>
		</div>
		<div class="desc">
			<?php
				if( ( get_option('rss_use_excerpt') == 1 || is_search() ) && !is_single() && !is_page() ){
					the_excerpt();
				} else {
					the_content( esc_html__( 'Read More &#187;', 'universe' ) );
				}

				wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'universe' ) . '</span>', 'after' => '</div>' ) );
			?>
		</div>
	</div>

</article>