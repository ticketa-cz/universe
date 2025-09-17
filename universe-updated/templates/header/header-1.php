<?php
/**
*
*	Author: King-Theme.com
*	Package: Templates system by King-Theme
*	Version: 1.0
*
*	Positions: logo|upload|%UNIVERSE_SITE_URL%/wp-content/themes/universe/assets/images/logo.png, menu|menu, top_left|textarea|<i class="fa-envelope"></i> hi@king-theme.com \n<i class="fa-phone"></i> 1234 5678 9023, top_right|textarea|<a href="#" target="_blank">\n	<i class="fa-facebook"></i>\n</a>\n<a href="#" target="_blank">\n	<i class="fa-twitter"></i>\n</a>\n<a href="#" target="_blank">\n	<i class="fa-flickr"></i>\n</a>\n<a href="#" target="_blank">\n	<i class="fa-instagram"></i>\n</a>\n<a href="#" target="_blank">\n	<i class="fa-youtube"></i>\n</a>
*
*	Register position_id and field_type to be add content from admin panel. ( text| textarea | upload | menu..etc.. )
*	All settings from backend will be return to varible $args
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*
*/

	if ( ! defined( 'ABSPATH' ) )
		exit; // Exit if accessed directly

	global $universe, $woocommerce;

	wp_enqueue_style('universe-menu-default');

	$position = !empty($universe->cfg['sidebar_menu_pos']) ? $universe->cfg['sidebar_menu_pos'] : 'left';

	$menu = !empty($atts['menu']) ? $atts['menu'] : '';
	if(empty($menu) || $menu == 'Select Menu') $menu = 'main-menu';

	$default_logo = get_template_directory_uri().'/assets/images/logo.png';
	
	$top_left = !empty($atts['top_left']) ? $atts['top_left'] : '';
	$top_right = !empty($atts['top_right']) ? $atts['top_right'] : '';
	
?>

<header class="header header-default">
	<?php if (!empty($top_left) || !empty($top_right)){ ?>
	<div class="top-nav">
		<div class="container">
			<div class="top-nav-left"><?php echo universe::esc_js($top_left); ?></div>
			<div class="top-nav-right"><?php echo universe::esc_js($top_right); ?></div>
		</div>
	</div>
	<?php } ?>
	<div class="container">
		<a class="sb-toggle-<?php echo esc_attr($position); ?>" href="javascript:;" data-connection="navbar-collapse-1"><i class="fa fa-bars"></i></a>

		<!-- Cart button for responsive -->
		<?php if( !empty( $woocommerce ) ): ?>
		<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="minicart-reponsive minicart-reponsive-<?php echo ($position=='left')?'right':'left'; ?>">
			<i class="et-basket et"></i>
			<span class="cart-items"><?php echo WC()->cart->cart_contents_count; ?></span>
		</a>
		<?php endif; ?>

		<!-- Logo -->
		<div class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>" id="logo">
				<img src="<?php echo esc_url( !empty($atts['logo']) ? $atts['logo']: $default_logo ); ?>" alt="<?php bloginfo('description'); ?>" />
			</a>
		</div>
		<!-- Navigation Menu -->
		<div class="menu_main">
		  <div class="navbar yamm navbar-default">
			  <div id="navbar-collapse-1" class="navbar-collapse collapse pull-right">
				<nav><?php $universe->mainmenu( $menu ); ?></nav>
				<div class="nav-search">
					<i class="sl-magnifier"></i>
					<form action="<?php echo esc_url(site_url()); ?>">
						<input type="search" name="s" placeholder="<?php _e('Search..', 'universe'); ?>" />
					</form>
				</div>
				<?php
					//Start cart
					$showCart = isset( $universe->cfg['topInfoCart'] ) ? $universe->cfg['topInfoCart'] : 'show';

					if( !empty( $woocommerce ) && $showCart == 'show' ){

				?>
					<div  class="tpbut three minicart-li">
						<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="minicart-nav">
							<i class="et-basket et"></i>
							<span class="cart-items"><?php echo WC()->cart->cart_contents_count; ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="minicart-wrp">
							<?php
								if( function_exists( 'universe_cart_func' ) ){
									echo '<div class="minicart-wrp">'.universe_cart_func( array() ).'</div>';
								}
							?>
							</li>
						</ul>
					</div>
				<?php }
				// End cart
				?>

			  </div>
		  </div>
		</div>
	<!-- end Navigation Menu -->
	</div>
</header>