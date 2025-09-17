<?php
/*--------------------------------------
 #		(c) king-theme.com
 -----------------------------------*/

get_header();
global $kctheme;
?>

	<?php universe::path( 'blog_breadcrumb' ); ?>

	<div id="main-pages" class="main-posts-pages default-posts-pages">
		<div class="container">
			<div class="row">
				<div class="col-md-9 content_left">
					<div class="main-content">

						<?php while ( have_posts() ) : the_post(); ?>

							<?php get_template_part( 'templates/blog/content', 'default' ); ?>

						<?php endwhile; // end of the loop. ?>

					</div>
					<?php $universe->pagination(); ?>
				</div>

				<div class="col-md-3 right_sidebar">
					<?php get_sidebar( ); ?>
				</div>
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>