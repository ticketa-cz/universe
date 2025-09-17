<?php
/**
*
*	Author: King-Theme.com
*	Package: Templates system by King-Theme
*	Version: 1.0
*
*	Positions: logo|upload|%UNIVERSE_SITE_URL%/wp-content/themes/universe/assets/images/logo.png, menu|textarea|Home:#home\nAbout:#about\nPortfolio:#portfolio\nPricing:#pricing\nBlog:#blog\nContact:#contact
*
*	Register position_id and field_type to be add content from admin panel. ( text| textarea | upload | menu..etc.. )
*	All settings from backend will be return to varible $args
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*
*/

	if ( ! defined( 'ABSPATH' ) )
		exit; // Exit if accessed directly

	global $universe, $woocommerce;
	wp_enqueue_style('universe-menu-one-page');

	$position = !empty($universe->cfg['sidebar_menu_pos']) ? $universe->cfg['sidebar_menu_pos'] : 'left';

	$menu = !empty($atts['menu']) ? explode("\n", $atts['menu']) : array();

	$default_logo = get_template_directory_uri().'/assets/images/logo.png';

?>

<header class="header header-one-page">
	<div class="container_full opstycky1">
		<div class="container">

			<a class="sb-toggle-<?php echo esc_attr($position); ?>" href="javascript:;" data-connection="navbar-collapse-1"><i class="fa fa-bars"></i></a>

			<!-- Logo -->
			<div class="logo">
				<a href="<?php echo esc_url(home_url('/')); ?>">
					<img src="<?php echo esc_url( isset($atts['logo']) ? $atts['logo'] : $default_logo ); ?>" alt="<?php bloginfo('description'); ?>" />
				</a>
			</div>

			<!-- Navigation Menu -->
			<div class="menu_main">
				<div class="navbar yamm navbar-default">
					<div id="navbar-collapse-1" class="navbar-collapse collapse pull-right menuopv1">
						<nav id="menu-onepage" class="nav-collapse">
							<ul>
							<?php
								if (is_array($menu) && count($menu) > 0) 
								{
									foreach ($menu as $item) 
									{
										$item = explode(':', $item);
										$name = esc_html($item[0]);
										$hash = isset($item[1]) ? esc_html(trim($item[1])) : '#'.trim($name);
					
										$hash = str_replace(' ', '', $hash);
										if (strpos($hash, '#') === false)
											$hash = '#'.$hash;
										
										echo '<li><a href="'.esc_url($hash).'">'.$name.'</a></li>';
										
									}
								}
							?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>