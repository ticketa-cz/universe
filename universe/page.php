<?php
/*-----------------------------
 #		(c) king-theme.com
 ------------------------*/
get_header();

?>

	<?php
		if ( !is_front_page() )
			universe::path( 'breadcrumb' );
	?>

	<?php
		// - make sure the method exists
		// - check the page builder is be using
		if( function_exists( 'kc_is_using' ) && kc_is_using() ) :
	?>

			<div id="container_full" class="site-content">
				<div id="content" class="">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', 'page' ); ?>
					<?php endwhile; // end of the loop. ?>

				</div><!-- #content -->
			</div><!-- #primary -->

	<?php else : ?>

		<div id="main-pages" class="main-pages-default">
			<div class="container">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'page' ); ?>

					<?php
						if ( comments_open() ) {
							echo '<div class="container">';
							comments_template();
							echo '</div>';
						}
					?>
				<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #primary -->

	<?php endif; ?>

<?php get_footer(); ?>