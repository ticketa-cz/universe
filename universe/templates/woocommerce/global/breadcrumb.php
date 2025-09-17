<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

	<div id="breadcrumb" class="page_title2">
		<div class="container">
			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

				<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

			<?php endif; ?>

			<?php
				/**
				 * woocommerce_archive_description hook.
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */
				do_action( 'woocommerce_archive_description' );
			?>

			<?php
				if ( ! empty( $breadcrumb ) ) {
					echo universe::esc_js($wrap_before);

					foreach ( $breadcrumb as $key => $crumb ) {

						echo universe::esc_js($before);

						if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
							echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
						} else {
							echo esc_html( $crumb[0] );
						}

						echo universe::esc_js($after);

						if ( sizeof( $breadcrumb ) !== $key + 1 ) {
							echo universe::esc_js($delimiter);
						}

					}

					echo universe::esc_js($wrap_after);
				}
			?>

		</div>
	</div>