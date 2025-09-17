<?php
/**
 * Single Product Rating
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' )
	return;

$count   = $product->get_rating_count();
$average = $product->get_average_rating();

if ( $count > 0 ) : ?>

	<div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<div class="star-rating" title="<?php echo esc_html__( 'Rated', 'universe' ) . $average . esc_html__( 'out of 5', 'universe' ); ?>">
			<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
				<strong itemprop="ratingValue" class="rating"><?php print( $average ); ?></strong> <?php esc_html_e( 'out of 5', 'universe' ); ?>
			</span>
		</div>
		<a href="#reviews" class="woocommerce-review-link" rel="nofollow"><?php printf( _n( '%s Review', '%s Reviews', $count, 'universe' ), '<span itemprop="ratingCount" class="count">' . $count . '</span>' ); ?></a>|
		<a href="#reviews" class="go-to-review-form woocommerce-review-link" rel="nofollow"><span><?php esc_html_e( 'Add review', 'universe' ); ?></span></a>
	</div>

<?php endif; ?>