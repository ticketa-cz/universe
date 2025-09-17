<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$universe = universe::globe();

get_header();

?>

	<?php universe::path('breadcrumb'); ?>

	<div id="main-pages" class="search-posts-pages">
		<div class="container">
			<div class="row">
				<div class="col-md-9 content_left">
					<div class="main-content">

						<?php if ( have_posts() ) : ?>
							<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>

								<?php
									get_template_part( 'content' );
								?>

							<?php endwhile; ?>

							<?php universe::pagination(); ?>

						<?php else : ?>

							<article id="post-0" class="post no-results not-found">
								<header>
									<h3 class="widget-title">
										<?php esc_html_e( 'Nothing Found', 'universe' ); ?>
									</h3>
								</header><!-- .entry-header -->

								<div class="entry-content">
									<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'universe' ); ?></p>
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