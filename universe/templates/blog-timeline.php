<?php
	/**
	*
	* @author king-theme.com
	*
	*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

universe::path( 'blog_breadcrumb' );

?>

<div class="clearfix"></div>
<div class="content_fullwidth blog-timeline">
	<div class="features_sec65">
		<div class="container no-touch">
			<div id="cd-timeline" class="cd-container timeline-posts-pages">
				<?php universe_ajax_loadPostsTimeline(); ?>
			</div>
		</div>
	</div>
</div>

<div class="clearfix margin_top10"></div>
<div class="clearfix"></div>
<?php get_footer(); ?>