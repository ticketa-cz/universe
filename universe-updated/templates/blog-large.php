<?php
/*--------------------------------------
 #		(c) king-theme.com
 -----------------------------------*/

get_header(); ?>

	<?php universe::path( 'blog_breadcrumb' ); ?>

	<div id="main-pages" class="main-posts-pages large-posts-pages">
		<div class="container">
			<div class="row">
				<div class="col-md-12 content_fullwidth">
					<div class="main-content">

						<?php while ( have_posts() ) : the_post(); ?>

							<?php get_template_part( 'templates/blog/content', 'large' ); ?>

						<?php endwhile; // end of the loop. ?>

					</div>
					<?php $universe->pagination(); ?>
				</div>
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>