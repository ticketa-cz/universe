<?php
/**
 * The template for displaying image attachments.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary" class="image-attachment">
			<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<nav id="nav-single">
					<h3 class="assistive-text"><?php esc_html_e( 'Image navigation', 'universe' ); ?></h3>
					<span class="nav-previous"><?php previous_image_link( false, '&larr; '.esc_html__( 'Previous' , 'universe' ) ); ?></span>
					<span class="nav-next"><?php next_image_link( false, esc_html__( 'Next' , 'universe' ).'&rarr;' ); ?></span>
				</nav><!-- #nav-single -->

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>

							<div class="entry-meta">
								<?php $metadata = wp_get_attachment_metadata(); ?>

								<span class="meta-prep meta-prep-entry-date"><?php esc_html_e( 'Published', 'universe' ); ?> </span>
								<span class="entry-date"><abbr class="published" title="<?php echo esc_attr( get_the_time() ); ?>"><?php echo get_the_date(); ?></abbr></span> <?php esc_html_e( 'at', 'universe' ); ?> 
								<a href="<?php echo esc_url( wp_get_attachment_url() ); ?>" title="<?php esc_html_e( 'Link to full-size image', 'universe' ); ?>"><?php echo esc_attr($metadata['width']); ?> &times; <?php echo esc_attr($metadata['height']); ?></a> <?php esc_html_e( 'in', 'universe' ); ?> 
								<a href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>" title="<?php esc_html_e( 'Return to', 'universe' ); ?> <?php echo esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ); ?>" rel="gallery"><?php echo get_the_title( $post->post_parent ); ?></a>
								<?php edit_post_link( esc_html__( 'Edit', 'universe' ), '<span class="edit-link">', '</span>' ); ?>
							</div><!-- .entry-meta -->

						</header><!-- .entry-header -->

						<div class="entry-content">

							<div class="entry-attachment">
								<div class="attachment">
									<?php
										/**
										 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
										 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
										 */
										$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
										foreach ( $attachments as $k => $attachment ) {
											if ( $attachment->ID == $post->ID )
												break;
										}
										$k++;
										// If there is more than 1 attachment in a gallery
										if ( count( $attachments ) > 1 ) {
											if ( isset( $attachments[ $k ] ) )
												// get the URL of the next image attachment
												$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
											else
												// or get the URL of the first image attachment
												$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
										} else {
											// or, if there's only 1 image, get the URL of the image
											$next_attachment_url = wp_get_attachment_url();
										}
									?>

									<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment">
										<?php
											$attachment_size = apply_filters( 'universe_attachment_size', 848 );
											echo wp_get_attachment_image( $post->ID, array( $attachment_size, 1024 ) ); // filterable image width with 1024px limit for image height.
										?>
									</a>

									<?php if ( ! empty( $post->post_excerpt ) ) : ?>
										<div class="entry-caption">
											<?php the_excerpt(); ?>
										</div>
									<?php endif; ?>
								</div><!-- .attachment -->

							</div><!-- .entry-attachment -->

							<div class="entry-description">
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'universe' ) . '</span>', 'after' => '</div>' ) ); ?>
							</div><!-- .entry-description -->

						</div><!-- .entry-content -->

					</article><!-- #post-<?php the_ID(); ?> -->

					<?php comments_template(); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>