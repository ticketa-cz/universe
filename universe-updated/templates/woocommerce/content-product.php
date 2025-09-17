<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $universe;

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Extra post classes
$classes = array();

if ( empty( $woocommerce_loop['columns'] ) ) {
	if( !empty( $universe->cfg['woo_grids'] ) ) {
		$classes[] = 'columns-' . $universe->cfg['woo_grids'];
		$col_columns = $universe->cfg['woo_grids'];
	} else {
		$classes[] = 'columns-3';
		$col_columns = 3;
	}
} else {
	$classes[] = 'columns-' .  $woocommerce_loop['columns'];
	$col_columns = $woocommerce_loop['columns'];
}

if ( isset( $universe->cfg['woo_product_display'] ) ) {
	$classes[] = $universe->cfg['woo_product_display'];
}

if ( ($woocommerce_loop['loop'] + 1)%$col_columns == 1 ) {
	$classes[] = 'first';
}
if ( ($woocommerce_loop['loop'] + 1)%$col_columns == 0 ) {
	$classes[] = 'last';
}

?>
<li <?php post_class( $classes ); ?>>

	<a href="<?php the_permalink(); ?>" class="product-images">
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
	</a>

	<div class="king-product-info">
		<div class="product-info-box">
			<h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php
					/**
					 * woocommerce_after_shop_loop_item_title hook
					 *
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );
				?>
			<div class="woo_des"><?php the_content(); ?></div>
		</div>
	</div>

	<?php
		/**
		 * woocommerce_after_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' );
	?>

</li>
