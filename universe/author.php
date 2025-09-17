<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$universe = universe::globe();

get_header();

?>

	<?php universe::path( 'breadcrumb' ); ?>

	<div id="main-pages" class="author-posts-pages">
		<div class="container">
			<div class="row">
				<div class="col-md-9 content_left">
					<div class="main-content">

						<?php if ( have_posts() ) : ?>

							<?php the_post(); ?>

							<header>
								<h1 class="page-title author"><?php echo esc_html__( 'Author: ', 'universe' ) . '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>'; ?></h1>
							</header>

							<?php rewind_posts(); ?>

							<?php // If a user has filled out their description, show a bio on their entries. ?>
							<?php if (get_the_author_meta('description')) : ?>
								<div class="about-author">
									<h3 class="author-name"><?php echo esc_html__( 'About ', 'universe' ) . get_the_author(); ?></h3>
									<figure>
										<?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?>
									</figure>
									<div class="author-content">
										<p><?php the_author_meta( 'description' ); ?></p>
									</div>
								</div>
							<?php endif; ?>

							<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>

								<?php get_template_part( 'content' ); ?>

							<?php endwhile; ?>

							<?php $universe->pagination(); ?>

						<?php else : ?>

							<article id="post-0" class="post no-results not-found">
								<header class="entry-header">
									<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'universe' ); ?></h1>
								</header><!-- .entry-header -->

								<div class="entry-content">
									<p><?php esc_html_e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'universe' ); ?></p>
									<?php get_search_form(); ?>
								</div><!-- .entry-content -->
							</article><!-- #post-0 -->

						<?php endif; ?>

					</div>
				</div>

				<div class="col-md-3 right_sidebar">
					<?php get_sidebar( ); ?>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>