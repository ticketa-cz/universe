<?php
/**
 *
 * (c) king-theme.com
 *
 * The Header of theme.
 *
 */


$universe = universe::globe();
$post = universe::globe('post');

$post_data = universe_post_meta_options();

$m_class = array( 'site_wrapper' );
if ( isset($universe->cfg['layout']) ) {
	$m_class[] = 'layout-' . $universe->cfg['layout'];
}

?>
<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head><?php wp_head(); ?>
	
<!-- Global site tag (gtag.js) - Google Ads: 671858753 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-671858753"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-671858753');
</script>
	
<!-- Event snippet for Website traffic conversion page -->
<script>
  gtag('event', 'conversion', {'send_to': 'AW-671858753/OBWtCKzM9fYBEMGAr8AC'});
</script>

	
</head>
<body <?php body_class(); ?> <?php body_style(); ?>>
	<div id="main" class="<?php echo esc_attr( implode( " ", $m_class ) ); ?>">

		<?php

			/**
			* Load header path, change header via theme panel, files location themes/universe/templates/header/
			*
			include 'core/style-selector.php';*/
			universe::path( 'header' );

		?>