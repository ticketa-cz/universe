<?php
// Remove woo action
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

// Add woo action
add_action( 'woocommerce_before_shop_loop_item_title', 'universe_woocommerce_img_effect', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'before_shop_item_buttons', 9 );
add_action( 'woocommerce_after_shop_loop_item', 'after_shop_item_buttons', 11 );
add_action( 'woocommerce_before_shop_loop', 'universe_woo_products_order', 30 );
add_action( 'woocommerce_get_catalog_ordering_args', 'universe_woo_get_order', 20 );
add_action( 'woocommerce_before_shop_loop', 'universe_woocommerce_list_or_grid', 20 );
add_action( 'woocommerce_share', 'universe_woocommerce_share_social' );


if( !function_exists('universe_magnifier_active') ){
	function universe_magnifier_active(){
		return true;
	}
}

add_action( 'after_setup_theme', 'universe_woocommerce_support' );
function universe_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// Display woocommerce products per page.
if ( !function_exists( 'universe_loop_shop_per_page' ) ) {

	function universe_loop_shop_per_page() {
		parse_str( $_SERVER['QUERY_STRING'], $params );
		if ( isset( $params['product_count'] ) && $params['product_count'] ) {
			$number_per_page = $params['product_count'];
		} else {
			$universe = universe::globe();
			if( !empty( $universe->cfg['product_number'] ) ) {
				$number_per_page = $universe->cfg['product_number'];
			} else {
				$number_per_page = 12;
			}
		}

		return $number_per_page;
	}
	add_filter( 'loop_shop_per_page', 'universe_loop_shop_per_page', 20 );

}


// Display woocommerce products per row
if (!function_exists('universe_loop_columns')) {

	function universe_loop_columns() {
		global $woocommerce_loop, $universe;
		if ( empty( $woocommerce_loop['columns'] ) ) {
			if( !empty( $universe->cfg['woo_grids'] ) ) {
				$woo_columns = $universe->cfg['woo_grids'];
			} else {
				$woo_columns = 3;
			}
		} else {
			$woo_columns = $woocommerce_loop['columns'];
		}

		return $woo_columns;
	}
	add_filter('loop_shop_columns', 'universe_loop_columns');

}


function universe_woocommerce_img_effect() {

	global $product, $woocommerce;

	$items_in_cart = array();

	if( class_exists('WC')) {
		$get_cart = WC()->cart->get_cart();
		if (!empty($get_cart)) {
			foreach( $get_cart as $cart ) {
				$items_in_cart[] = $cart['product_id'];
			}
		}
	}

	$id      = get_the_ID();
	$in_cart = in_array( $id, $items_in_cart );
	$size    = 'shop_catalog';

	$gallery = get_post_meta( $id, '_product_image_gallery', true );
	$attachment_image = '';
	if( !empty( $gallery ) ) {
		$gallery          = explode( ',', $gallery );
		$first_image_id   = $gallery[0];
		$attachment_image = wp_get_attachment_image( $first_image_id , $size, false, array( 'class' => 'hover-image' ) );
	}

	if ( has_post_thumbnail() ) {
		$thumb_image = get_the_post_thumbnail( $id, $size );
	} elseif ( wc_placeholder_img_src() ) {
		$thumb_image = wc_placeholder_img( $size );
	}

	if( $attachment_image ) {
		$classes = 'product-detail-image crossfade-images';
	} else {
		$classes = 'product-detail-image';
	}

	echo '<span class="'.$classes.'">';
		print( $attachment_image );
		print( $thumb_image );
		if( $in_cart ) {
			echo '<span class="cart-loading checked globalBgColor"><i class="icon-check"></i></span>';
		} else {
			echo '<span class="cart-loading"><i class="icon-spinner"></i></span>';
		}
	echo '</span>';

}

function universe_woocommerce_share_social() {
	global $post, $universe;

	if ( $universe->cfg['woo_social'] == 1 ) {
		if (has_post_thumbnail()) {
			$pin_image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
		} else {
			$pin_image = get_template_directory_uri() . '/assets/images/default.png';
		}
		echo '
			<div class="single-product-share">
				<ul>
					<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . get_permalink() . '&t=' . get_the_title() . '"><i class="fa fa-facebook"></i></a></li>
					<li><a href="https://twitter.com/home?status=' . get_permalink() . '"><i class="fa fa-twitter"></i></a></li>
					<li><a data-pin-do="skipLink" href="https://pinterest.com/pin/create/button/?url=' . get_permalink() . '&media=' . $pin_image . '&description=' . get_the_excerpt() . '"><i class="fa fa-pinterest-p"></i></a></li>
					<li><a href="https://plus.google.com/share?url=' . get_permalink() . '"><i class="fa fa-google-plus"></i></a></li>
					<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=' . get_permalink() . '&title=' . get_the_title() . '&summary=' . get_the_excerpt() . '&source="><i class="fa fa-linkedin"></i></a></li>
				</ul>
			</div>
		';
	} else {
		echo '';
	}
}


function before_shop_item_buttons() {
	echo '<div class="product-buttons"><div class="product-buttons-box">';
}


function after_shop_item_buttons() {
	echo '<a href="' . get_permalink() . '" class="show_details_button">' . esc_html__( 'Show details', 'universe' ) . '</a></div></div>';
}


function universe_woo_products_order() {

	// Get WooCommerce admin setting.
	$universe = universe::globe();

	parse_str( $_SERVER['QUERY_STRING'], $params );

	$query_string = '?'.$_SERVER['QUERY_STRING'];

	if( !empty( $universe->cfg['product_number'] ) ) {
		$products_per_page = $universe->cfg['product_number'];
	} else {
		$products_per_page = 12;
	}

	// Set product_orderby, product_order, product_count.
	$universe_product_orderby = !empty( $params['product_orderby'] ) ? $params['product_orderby'] : 'default';
	$universe_product_order   = !empty( $params['product_order'] )  ? $params['product_order'] : 'asc';
	$universe_product_count   = !empty( $params['product_count'] ) ? $params['product_count'] : $products_per_page;

	$html = '';
	$html .= '<div class="king-product-order">';

		if ( $universe->cfg['woo_filter'] == 1 ) {

			$html .= '<div class="king-orderby-container">';
				$html .= '<ul class="orderby order-dropdown">';
					$html .= '<li>';
						$html .= '<span class="current-li"><span class="current-li-content"><a>'. esc_html__('Sort by', 'universe').' <strong>'. esc_html__( 'Default Order', 'universe' ).'</strong></a></span></span>';
						$html .= '<ul>';
							$html .= '<li class="'.( ( $universe_product_orderby == 'default' ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_orderby', 'default' ).'">'. esc_html__( 'Sort by', 'universe' ).' <strong>'. esc_html__( 'Default Order', 'universe' ).'</strong></a></li>';
							$html .= '<li class="'.( ( $universe_product_orderby == 'name' ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_orderby', 'name' ).'">'. esc_html__( 'Sort by', 'universe' ).' <strong>'. esc_html__( 'Name', 'universe' ).'</strong></a></li>';
							$html .= '<li class="'.( ( $universe_product_orderby == 'price' ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_orderby', 'price' ).'">'. esc_html__( 'Sort by', 'universe' ).' <strong>'. esc_html__( 'Price', 'universe' ).'</strong></a></li>';
							$html .= '<li class="'.( ( $universe_product_orderby == 'date' ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_orderby', 'date' ).'">'. esc_html__( 'Sort by', 'universe' ).' <strong>'. esc_html__( 'Date', 'universe' ).'</strong></a></li>';
							$html .= '<li class="'.( ( $universe_product_orderby == 'rating' ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_orderby', 'rating' ).'">'. esc_html__( 'Sort by', 'universe' ).' <strong>'. esc_html__( 'Rating', 'universe' ).'</strong></a></li>';
						$html .= '</ul>';
					$html .= '</li>';
				$html .= '</ul>';
				$html .= '<ul class="order">';
				if( $universe_product_order == 'desc' ):
					$html .= '<li class="desc"><a href="'.universe_get_data_url( $query_string, 'product_order', 'asc' ).'"><i class="fa fa-arrow-up"></i></a></li>';
				endif;
				if( $universe_product_order == 'asc' ):
					$html .= '<li class="asc"><a href="'.universe_get_data_url( $query_string, 'product_order', 'desc' ).'"><i class="fa fa-arrow-down"></i></a></li>';
				endif;
				$html .= '</ul>';

			$html .= '</div>';

		}


		$html .= '<ul class="sort-count order-dropdown">';
			$html .= '<li>';
				$html .= '<span class="current-li"><a>'.esc_html__('Show', 'universe').' <strong>'.$products_per_page.' '.esc_html__(' Products', 'universe').'</strong></a></span>';
				$html .= '<ul>';
					$html .= '<li class="'.( ( $universe_product_count == $products_per_page ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_count', $products_per_page ).'">'.esc_html__( 'Show', 'universe' ).' <strong>'.$products_per_page.' '.esc_html__( 'Products', 'universe' ).'</strong></a></li>';
					$html .= '<li class="'.( ( $universe_product_count == $products_per_page*2 ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_count', $products_per_page*2 ).'">'.esc_html__( 'Show', 'universe' ).' <strong>'.( $products_per_page*2 ).' '.esc_html__( 'Products', 'universe' ).'</strong></a></li>';
					$html .= '<li class="'.( ( $universe_product_count == $products_per_page*3 ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_count', $products_per_page*3 ).'">'.esc_html__( 'Show', 'universe' ).' <strong>'.( $products_per_page*3 ).' '.esc_html__( 'Products', 'universe' ).'</strong></a></li>';
					$html .= '<li class="'.( ( $universe_product_count == $products_per_page*4 ) ? 'current': '' ).'"><a href="'.universe_get_data_url( $query_string, 'product_count', $products_per_page*4 ).'">'.esc_html__( 'Show', 'universe' ).' <strong>'.( $products_per_page*4 ).' '.esc_html__( 'Products', 'universe' ).'</strong></a></li>';
				$html .= '</ul>';
			$html .= '</li>';
		$html .= '</ul>';
	$html .= '</div>
	<script>
		jQuery(".king-product-order .orderby .current-li a").html(jQuery(".king-product-order .orderby ul li.current a").html());
		jQuery(".king-product-order .sort-count .current-li a").html(jQuery(".king-product-order .sort-count ul li.current a").html());
	</script>
	';

	print( $html );
}


function universe_woo_get_order( $args ){

	global $woocommerce;

	parse_str( $_SERVER['QUERY_STRING'], $params );

	$universe_product_orderby = !empty( $params['product_orderby'] ) ? $params['product_orderby'] : 'default';
	$universe_product_order = !empty( $params['product_order'] )  ? $params['product_order'] : 'asc';

	switch( $universe_product_orderby ) {
		case 'date':
			$orderby  = 'date';
			$order    = 'desc';
			$meta_key = '';
		break;
		case 'price':
			$orderby  = 'meta_value_num';
			$order    = 'asc';
			$meta_key = '_price';
		break;
		case 'popularity':
			$orderby  = 'meta_value_num';
			$order    = 'desc';
			$meta_key = 'total_sales';
		break;
		case 'title':
			$orderby  = 'title';
			$order    = 'asc';
			$meta_key = '';
		break;
		case 'default':
		default:
			$orderby  = 'menu_order title';
			$order    = 'asc';
			$meta_key = '';
		break;
	}

	switch( $universe_product_order ) {
		case 'desc':
			$order = 'desc';
		break;
		case 'asc':
			$order = 'asc';
		break;
		default:
			$order = 'asc';
		break;
	}

	$args['orderby']  = $orderby;
	$args['order']    = $order;
	$args['meta_key'] = $meta_key;

	if( $universe_product_orderby == 'rating' ) {
		$args['orderby']  = 'menu_order title';
		$args['order']    = $universe_product_order == 'desc' ? 'desc' : 'asc';
		$args['order']	  = strtoupper( $args['order'] );
		$args['meta_key'] = '';

		add_filter( 'posts_clauses', 'universe_order_rating' );
	}

	return $args;
}


function universe_order_rating( $args ) {

	global $wpdb;

	$args['fields'] .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";

	$args['where'] .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";

	$args['join'] .= "
		LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
		LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
	";

	$order = woocommerce_clean( $_GET['product_order'] );
	$order = $order == 'asc' ? 'asc' : 'desc';
	$order = strtoupper( $order );

	$args['orderby'] = "average_rating {$order}, $wpdb->posts.post_date DESC";

	$args['groupby'] = "$wpdb->posts.ID";

	return $args;
}



function universe_woocommerce_list_or_grid() {
	if ( is_single() ) return;
	global $universe, $universe_woocommerce_loop;
?>
	<div class="king-switch-layout">
		<a id="grid-button" class="grid-view<?php if ( $universe_woocommerce_loop['view'] == 'grid' ) echo ' active'; ?>" href="#"><i class="fa fa-th"></i></a>
		<a id="list-button" class="list-view<?php if ( $universe_woocommerce_loop['view'] == 'list' ) echo ' active'; ?>" href="#"><i class="fa fa-list"></i></a>
	</div>
<?php
	$html = '';
	$html .='<script>
		jQuery( document ).ready( function( $ ) {
			$(".king-switch-layout a").on( "click", function(){
				var universe_view = $(this).attr("class").replace( "-view", "" );
				$("ul.products li").removeClass("list grid").addClass( universe_view );
				$(this).parent().find("a").removeClass("active");
				$(this).addClass("active");

				$.cookie(universe_shop_view_cookie, universe_view);
				$("ul.products li").trigger("styleswitch");
				return false;
			});
		});';
	$html .='</script>';
	print( $html );
}


function universe_get_data_url( $universe_URL, $universe_pr_name, $universe_pr_value ) {

	$universe_URL_info = parse_url( $universe_URL );
	if( !isset( $universe_URL_info["query"] ) )
		$universe_URL_info["query"]="";

	$params = array();
	parse_str( $universe_URL_info['query'], $params );
	$params[$universe_pr_name] = $universe_pr_value;
	$universe_URL_info['query'] = http_build_query( $params );


	return universe_generate_url( $universe_URL_info );
}

function universe_generate_url( $universe_URL_info ) {

	$universe_URL="";
	if( isset( $universe_URL_info['host'] ) ){
		$universe_URL .= $universe_URL_info['scheme'] . '://';
		if ( isset( $universe_URL_info['user'] ) ) {
			$universe_URL .= $universe_URL_info['user'];
			if ( isset( $universe_URL_info['pass'] ) ) {
				$universe_URL .= ':' . $universe_URL_info['pass'];
			}
			$universe_URL .= '@';
		}

		$universe_URL .= $universe_URL_info['host'];
		if ( isset( $universe_URL_info['port'] ) ) {
			$universe_URL .= ':' . $universe_URL_info['port'];
		}
	}

	if ( isset( $universe_URL_info['path'] ) ) {
		$universe_URL .= $universe_URL_info['path'];
	}

	if ( isset( $universe_URL_info['query'] ) ) {
		$universe_URL .= '?' . $universe_URL_info['query'];
	}

	if ( isset( $universe_URL_info['fragment'] ) ) {
		$universe_URL .= '#' . $universe_URL_info['fragment'];
	}


	return $universe_URL;
}



/*---------------------------------------
# Cart
*---------------------------------------*/

function universe_cart_func( $atts ) {
    $a = shortcode_atts( array(
        'author' => 'universe',
    ), $atts );

	ob_start();
	
	if ( class_exists( 'WooCommerce' ) ){
		echo '<div id="universe_cart">';
		woocommerce_mini_cart();
		echo '</div>';
	}
		
    return ob_get_clean();
}
//$universe->ext['asc']( 'universe_cart', 'universe_cart_func' );
 
/**
 * Add sample to cart for demo
 */
function universe_add_sample_product_to_cart() {	
	global $woocommerce;
	
	if(sizeof( WC()->cart->get_cart() ) == 0){	
		$products_arr = array(1589, 1603, 1620, 1624, 1781);			
		foreach($products_arr as $product_id){
			$found = false;
			if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
					$_product = $values['data'];
					if ( $_product->id == $product_id )
						$found = true;
				}

				if ( ! $found )
					WC()->cart->add_to_cart( $product_id );
			} else {				
				WC()->cart->add_to_cart( $product_id );
			}
		}
	}
}


/**
 * Get cart to item menu
 */
add_action('wp_ajax_nopriv_universe_get_cart', 'universe_woo_get_cart');
add_action('wp_ajax_universe_get_cart', 'universe_woo_get_cart');

function universe_woo_get_cart(){

	global $woocommerce;

	universe_add_sample_product_to_cart();

	ob_start();
	echo '<div id="universe_cart">';
	woocommerce_mini_cart();
	echo '</div>';
	$cart_data = ob_get_clean();

	$data = array(
		'cart_content' => $cart_data,
		'count' => WC()->cart->cart_contents_count,
		'total' => esc_html__('Total: ', 'universe') . WC()->cart->get_cart_total()
	);

	wp_send_json($data);
}


add_action('wp_footer', 'universe_woo_add_cart_script');
function universe_woo_add_cart_script(){
	if ( class_exists( 'WooCommerce' ) ) {
	global $woocommerce;
	?>
	<script type="text/javascript">
	"use strict";
	
	jQuery('.navbar-header').before('<a class="universe_res_cart" href="<?php echo WC()->cart->get_cart_url(); ?>"><i class="et-basket et"></i><span class="cart-items"><?php echo WC()->cart->cart_contents_count; ?></span></a>');
	
	var universe_cart = function(first_load){
		if( typeof first_load === 'undefined' ) first_load = true;
		
		//jQuery('.minicart-nav>a').append('<span class="cart-items"><?php echo WC()->cart->cart_contents_count; ?></span>');
				
		var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
			
		var data = {
			action: 'universe_get_cart',
		};

		// Ajax action
		jQuery.post( ajaxurl, data, function( response ) {
			jQuery('.minicart-nav>span.cart-items').text(response.count);
			jQuery('.minicart-reponsive>span.cart-items').text(response.count);
			jQuery('.sb-slidebar .minicart-nav>span.cart-items').after(response.total);
			jQuery('.minicart-li>.dropdown-menu .minicart-wrp').html(response.cart_content);
		});
	}
	
	if(jQuery('div.minicart-li>a').hasClass('minicart-nav')){
		universe_cart();				
	}
	
	</script>
	<?php
	}
}


add_action( 'wp_enqueue_scripts', 'universe_load_woo_add_to_cart_scripts', 9 );
function universe_load_woo_add_to_cart_scripts() {
    wp_enqueue_script( 'wc-add-to-cart', UNIVERSE_THEME_URI.'/assets/woocommerce/js/add-to-cart.js', array( 'jquery' ), WC_VERSION, true );
	//wp_enqueue_script( 'wc-cart-fragments', UNIVERSE_THEME_URI.'/assets/woocommerce/js/cart-fragments.js', array( 'jquery' ), WC_VERSION, true );
}


/**
 * Output the WooCommerce Breadcrumb.
 *
 * @param array $args
 */
/*function woocommerce_breadcrumb( $args = array() ) {
	$args = wp_parse_args( $args, apply_filters( 'woocommerce_breadcrumb_defaults', array(
		'delimiter'   => '&nbsp;&#47;&nbsp;',
		'wrap_before' => '<nav class="woocommerce-breadcrumb pagenation" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
		'wrap_after'  => '</nav>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'universe' )
	) ) );

	$breadcrumbs = new WC_Breadcrumb();

	if ( $args['home'] ) {
		$breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_UNIVERSE_HOME_URL', home_url() ) );
	}

	$args['breadcrumb'] = $breadcrumbs->generate();

	wc_get_template( 'global/breadcrumb.php', $args );
}*/