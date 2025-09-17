<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $universe, $post;

get_header();

	if ( $universe->cfg['our_works_breadcrumb'] ) {
		universe_incl_core( $universe->cfg['our_works_breadcrumb'] );
	}

	wp_enqueue_style( 'universe-cubeportfolio-min' );
	wp_enqueue_script( 'universe-cubeportfolio' );
	wp_enqueue_script( 'universe-cubeportfolio-main' );

?>

	<div class="content_fullwidth less4">
		<div id="js-grid-juicy-projects" class="cbp js-grid-juicy-projects-layout-4" data-cols="4" data-gap="0">

			<?php
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$image = $universe->get_featured_image( $post );
					$image_thumb = universe_createLinkImage($image, '476x420xct');

					$work_cf = get_post_meta( $post->ID , '_kc_addon_options_post_meta_options', TRUE );
					$link = !empty($work_cf['link']) ? $work_cf['link'] : get_permalink( $post->ID );
			?>

					<div class="cbp-item">
						<div class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								<img src="<?php echo esc_url($image_thumb); ?>" alt="" />
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">

										<a href="<?php echo esc_url( $link ); ?>" class="cbp-l-caption-buttonLeft" rel="nofollow"><?php echo esc_html__( 'more info', 'universe' ); ?></a>

										<?php if ( isset( $work_cf['video_link'] ) && !empty( $work_cf['video_link'] ) ): ?>
											<a href="<?php echo esc_url( $work_cf['video_link'] ) ?>" class="cbp-lightbox cbp-l-caption-buttonRight" data-title="<?php the_title(); ?><br>by <?php echo esc_html($work_cf['outhor']); ?>"><?php echo esc_html__( 'view video', 'universe' ); ?></a>
										<?php else: ?>
											<a href="<?php echo esc_url($image); ?>" class="cbp-lightbox cbp-l-caption-buttonRight" data-title="<?php the_title(); ?><br>by <?php echo esc_html($work_cf['outhor']); ?>"><?php esc_html_e( 'view larger', 'universe' ); ?></a>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div>
					</div><!-- end item -->

			<?php
					endwhile;
				endif;
			?>
		</div>
	</div>

	<?php $universe->pagination(); ?>

<?php get_footer(); ?>