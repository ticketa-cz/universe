<?php
/**
    * Template Name: Page Left Sidebar
	* (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $universe, $post;
if( is_page() ){
	// $sidebar = get_post_meta( $post->ID,'_king_page_sidebar' , true );
	$page_options = get_post_meta( $post->ID , '_universe_post_meta_options' );
	$sidebar = $page_options[0]['sidebar'];
} else if( is_home() ){
	if( get_option( 'page_for_posts', true ) ){
		$sidebar = get_post_meta( get_option( 'page_for_posts', true ),'_king_page_sidebar' , true );
	}
}
if( empty( $sidebar ) ){
	$sidebar = 'sidebar';
}
get_header();

?>

	<?php universe::path( 'breadcrumb' ); ?>

	<div class="clearfix"></div>
	<div id="main-pages">
		<div class="container">
			<div class="col-md-3 left_sidebar">

				<?php if ( is_active_sidebar( $sidebar ) ) : ?>

					<div id="sidebar" class="widget-area king-sidebar">
						<?php dynamic_sidebar( $sidebar ); ?>
					</div><!-- #secondary -->

				<?php endif; ?>

			</div>

			<div class="col-md-9 content_right">
				<div class="main-content">

					<?php if ( have_posts() ) : ?>

						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', 'page' ); ?>
						<?php endwhile; ?>

						<?php $universe->pagination(); ?>

					<?php else : ?>

						<article id="post-0" class="post no-results not-found">

							<?php if ( current_user_can( 'edit_posts' ) ) : ?>
								<header class="entry-header">
									<h1 class="entry-title"><?php esc_html_e( 'No posts to display', 'universe' ); ?></h1>
								</header>

								<div class="entry-content">
									<p><?php echo esc_html__( 'Ready to publish your first post?', 'universe') . '<a href="'. admin_url( 'post-new.php' ) .'">'. esc_html_e( 'Get started here', 'universe' ) .'</a>'; ?></p>
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
	</div>

<?php get_footer();