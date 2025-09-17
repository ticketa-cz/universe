<?php
/**
 * (c) www.king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $universe, $post, $more;

get_header();

?>

<div class="page_title2 sty2">
	<div class="container">

	    <h1><?php esc_html_e( 'Login Form', 'universe' ); ?></h1>
	    <div class="pagenation">&nbsp;<a href="<?php echo site_url(); ?>"><?php esc_html_e( 'Home', 'universe' ); ?></a> <i>/</i> <a href="#"><?php esc_html_e( 'Features', 'universe' ); ?></a> <i>/</i> <?php esc_html_e( 'Login Form', 'universe' ); ?></div>

	</div>
</div>

<div class="clearfix"></div>

<div id="primary" class="site-content">
	<div id="content" class="container">
		<div class="entry-content blog_postcontent">
			<div class="margin_top1"></div>
			<?php get_template_part( 'templates/login' ); ?>
			<div class="margin_top6"></div>
		</div>
	</div>
</div>



<?php get_footer(); ?>