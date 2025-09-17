<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$universe = universe::globe();

get_header();

?>

	<?php universe::path( 'breadcrumb' ); ?>

	<div id="main-pages">
		<div class="container">
			<div class="row">
				<div class="col-md-9 content_left">

					<?php
						while ( have_posts() ) : the_post();
							get_template_part( 'content' );
							if( $universe->cfg['showShareBox'] == 1 ){
								$escaped_link = get_the_permalink();
					?>

								<div class="sharepost">
									<h4><?php esc_html_e('Share this Post','universe'); ?></h4>
									<ul>
										<?php if( $universe->cfg['showShareFacebook'] == 1 ){ ?>
											<li>
												<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $escaped_link ); ?>">
													&nbsp;<i class="fa fa-facebook fa-lg"></i>&nbsp;
												</a>
											</li>
										<?php } ?>
										<?php if( $universe->cfg['showShareTwitter'] == 1 ){ ?>
											<li>
												<a href="https://twitter.com/home?status=<?php echo esc_url( $escaped_link ); ?>">
													<i class="fa fa-twitter fa-lg"></i>
												</a>
											</li>
										<?php } ?>
										<?php if( $universe->cfg['showShareGoogle'] == 1 ){ ?>
											<li>
												<a href="https://plus.google.com/share?url=<?php echo esc_url( $escaped_link ); ?>">
													<i class="fa fa-google-plus fa-lg"></i>
												</a>
											</li>
										<?php } ?>
										<?php if( $universe->cfg['showShareLinkedin'] == 1 ){ ?>
											<li>
												<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=&amp;title=&amp;summary=&amp;source=<?php echo esc_url( $escaped_link ); ?>">
													<i class="fa fa-linkedin fa-lg"></i>
												</a>
											</li>
										<?php } ?>
										<?php if( $universe->cfg['showSharePinterest'] == 1 ){ ?>
											<li>
												<a href="https://pinterest.com/pin/create/button/?url=&amp;media=&amp;description=<?php echo esc_url( $escaped_link ); ?>">
													<i class="fa fa-pinterest fa-lg"></i>
												</a>
											</li>
										<?php } ?>
									</ul>
								</div>


					<?php
							}
						endwhile;

					?>
				</div>

				<div class="col-md-3 right_sidebar">

					<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>

						<div id="sidebar" class="widget-area king-sidebar">
							<?php dynamic_sidebar( 'sidebar' ); ?>
						</div><!-- #secondary -->

					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>