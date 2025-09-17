<?php
/**
 * (c) www.king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $universe, $post, $more;

get_header();

?>

<div id="breadcrumb" class="page_title2">
	<div class="container">
		<h1><?php universe_title(); ?></h1>
		<div class="pagenation">&nbsp;<?php $universe->breadcrumb(); ?></div>
	</div>
</div>

<div id="primary" class="site-content">
	<div id="content" class="container">
		<div class="entry-content blog_postcontent">
			<div class="margin_top1"></div>
			<?php require_once get_template_directory() .DS.'templates'.DS.'register.php'; ?>
			<div class="margin_top6"></div>
		</div>
	</div>
</div>



<?php get_footer(); ?>