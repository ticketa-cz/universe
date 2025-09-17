<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$universe = universe::globe();

get_header();

?>

	<?php universe::path( 'blog_breadcrumb' ); ?>

	<div id="main-pages" class="main-posts-pages">
		<div class="container">
			<div class="row">
				<?php if ( have_posts() ) : ?>

					<?php
						if ( !empty( $universe->cfg['blog'] ) ) {
							$blog_template = $universe->cfg['blog'];
						} else {
							$blog_template = 'default';
						}
						get_template_part( 'templates/blog', $blog_template );
					?>

					<?php $universe->pagination(); ?>

				<?php else : ?>

					<article id="post-0" class="post no-results not-found">

					<?php if ( current_user_can( 'edit_posts' ) ) :
						// Show a different message to a logged-in user who can add posts.
					?>
						<header class="entry-header">
							<h1 class="entry-title"><?php esc_html_e( 'No posts to display', 'universe' ); ?></h1>
						</header>

						<div class="entry-content">
							<p><?php esc_html_e( 'Ready to publish your first post?', 'universe' ); ?> <a href="<?php echo admin_url( 'post-new.php' ); ?>"><?php esc_html_e( 'Get started here', 'universe' ); ?></a></p>
						</div><!-- .entry-content -->

					<?php else :
						// Show the default message to everyone else.
					?>
						<header class="entry-header">
							<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'universe' ); ?></h1>
						</header>

						<div class="entry-content">
							<p><?php esc_html_e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'universe' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					<?php endif; // end current_user_can() check ?>

					</article><!-- #post-0 -->

				<?php endif; // end have_posts() check ?>

			</div>
		</div>
	</div>

<?php get_footer(); ?>