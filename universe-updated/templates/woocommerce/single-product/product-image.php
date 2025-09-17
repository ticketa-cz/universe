<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $universe, $post, $woocommerce, $product;

$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
$image       = get_the_post_thumbnail(
	$post->ID,
	apply_filters( 'single_product_large_thumbnail_size', 'shop_catalog' ),
	array( 'title' => $image_title )
);

$magnifier_option = array(
	'active'		=> ($universe->cfg['mg_active'] == 1) ? 'true' : 'false',
	'enableSlider'  => ($universe->cfg['mg_thumbnail_slider'] == 1 ) ? 'true' : 'false',
	'circular'      => ($universe->cfg['mg_thumbnail_circular'] == 1) ? 'true' : 'false',
	'infinite'      => ($universe->cfg['mg_thumbnail_infinite'] == 1) ? 'true' : 'false',
	'zoomWidth'     => $universe->cfg['mg_zoom_width'],
	'zoomHeight'    => $universe->cfg['mg_zoom_height'],
	'position'      => $universe->cfg['mg_zoom_position'],
	'lensOpacity'   => $universe->cfg['mg_lens_opacity'],
	'softFocus'     => ($universe->cfg['mg_blur'] == 1) ? 'true' : 'false',
	'phoneBehavior' => $universe->cfg['mg_zoom_position_mobile'],
	'loadingLabel'  => $universe->cfg['mg_loading_label'],
);

$magnifier_option = json_encode($magnifier_option);

?>
<div class="images" data-magnifieroptions="<?php echo esc_attr( $magnifier_option ); ?>">
	<?php
		if ( has_post_thumbnail() ) {

			$attachment_count = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			} ?>

			<?php if( !universe_magnifier_active() ): ?>

				<!-- Default Woocommerce Template -->

				<?php echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_title, $image ), $post->ID ); ?>

			<?php else: ?>

				<!-- Custom Magnifier Template -->

				<?php echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="universe_magnifier_zoom" title="%s" rel="thumbnails">%s</a>', $image_link, $image_title, $image ), $post->ID ); ?>
			<?php endif ?>

			<?php } else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $post->ID );

		}
	?>

	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>


