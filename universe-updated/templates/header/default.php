<?php
/**
*
*	Author: King-Theme.com
*	Package: Templates system by King-Theme
*	Version: 1.0
*
*	Positions: logo|upload|%UNIVERSE_SITE_URL%/wp-content/themes/universe/assets/images/logo.png, menu|menu, social-icon|textarea|<a href="#" class="fa fa-twitter"></a>\n<a href="#" class="fa fa-behance"></a>\n<a href="#" class="fa fa-google-plus"></a>
*
*	Register position_id and field_type to be add content from admin panel. ( text| textarea | upload | menu..etc.. )
*	All settings from backend will be return to varible $args
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*
*/

	if ( ! defined( 'ABSPATH' ) )
		exit; // Exit if accessed directly

	global $universe, $woocommerce;
	wp_enqueue_style('universe-menu-1');

	$position = !empty($universe->cfg['sidebar_menu_pos']) ? $universe->cfg['sidebar_menu_pos'] : 'left';

	$menu = !empty($atts['menu']) ? $atts['menu'] : '';

	if( empty($menu) || $menu == 'Select Menu' ) $menu = 'main-menu';

	$default_logo = get_template_directory_uri().'/assets/images/logo.png';

?>
<div class="margin_top8"></div>
<header class="header header-1" id="header">
	<div class="top-header">
		<div class="container">

			<a class="sb-toggle-<?php echo esc_attr($position); ?>" href="javascript:;" data-connection="navbar-collapse-1"><i class="fa fa-bars"></i></a>

			<!-- Logo -->
			<div class="logo">
				<a href="<?php echo esc_url(home_url('/')); ?>" id="logo">
					<img src="<?php echo esc_url( isset($atts['logo']) ? $atts['logo'] : $default_logo ); ?>" alt="<?php bloginfo('description'); ?>" />
				</a>
			</div>

			<!-- Navigation Menu -->
			<div class="top-right">

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

				<ul class="social">
					<?php if ( !empty( $atts['social-icon'] ) ): ?>

						<?php
							$top_arr = explode( "\n", $atts['social-icon'] );
							if ( count( $top_arr ) ) {
								foreach ($top_arr as $top_val) {
									echo '<li>'. $top_val .'</li>';
								}
							}
						?>

					<?php endif ?>
				</ul>

				<div class="menu_main main-menu">
					<div class="navbar yamm navbar-default">
						<div id="navbar-collapse-1" class="navbar-collapse collapse pull-right menuopv1">
							<nav class="nav-collapse">
								<?php $universe->mainmenu( $menu ); ?>
							</nav>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</header>