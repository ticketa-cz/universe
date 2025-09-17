<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$universe = universe::globe();

get_header();

?>

	<?php universe::path( 'breadcrumb' ); ?>

	<div id="main-pages" class="category-posts-pages">
		<div class="container">
			<div class="row">
				<div class="col-md-9 content_left">
					<div class="main-content">

						<?php if ( have_posts() ) : ?>

							<header>
								<?php

									$category_description = category_description();
									if ( ! empty( $category_description ) ){
										echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>' );
									}

								?>
							</header>

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