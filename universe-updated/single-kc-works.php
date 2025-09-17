<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $universe, $post;

$image  = kc_tools::get_featured_image( $post );

$escaped_link = get_permalink( $post );

$our_work_options = get_post_meta( $post->ID , '_kc_pro_options_post_meta_options' );

if ( isset( $our_work_options[0] ) ) {
	$our_work_options = $our_work_options[0];
}

get_header();

if ( get_post_type( $post->ID ) == 'kc-works' ) {

	if ( isset( $universe->cfg['our_works_breadcrumb_bg'] ) && !empty( $universe->cfg['our_works_breadcrumb_bg'] ) ) {
		$breadcrumb_bg = 'background-image: url('. esc_url( $universe->cfg['our_works_breadcrumb_bg'] ) .');';
	} else {
		$breadcrumb_bg = '';
	}

}

?>

<div id="breadcrumb" class="page_title_blog" style="<?php echo universe::esc_js($breadcrumb_bg); ?>">
	<div class="container">
		<div class="title">
			<h2>
				<?php the_title(); ?>
			</h2>
		</div>
	</div>
</div>


<div id="our-work-single">
	<div class="container">
		<h2 class="title-style1"><?php echo esc_html__( 'outside view', 'universe' ); ?></h2>

		<?php if ( $universe->cfg['our_works_style'] == 1 ): ?>
			<?php $image  = kc_tools::createImageSize( $image, '1140x500xct' ); ?>

			<div class="work-single">
				<div id="portfolio-large-preview">
					<img src="<?php echo esc_url( $image ); ?>" alt="" />
				</div>
				<?php if ( isset( $our_work_options['images_list'] ) && !empty( $our_work_options['images_list'] ) ): ?>
					<div class="portfolio_thumbnails">
						<?php foreach ($our_work_options['images_list'] as $key => $value): ?>
							<?php $url_thumb = wp_get_attachment_image_url( $key, 'thumbnail' ); ?>
							<a target="_blank" href="<?php echo esc_url( $value ); ?>">
								<img src="<?php echo esc_url( $url_thumb ); ?>">
							</a>
						<?php endforeach ?>
					</div>
				<?php endif ?>
				<div class="work-content">
					<div class="row">
						<div class="col-md-8">
							<div class="work-content-left">
								<h4 class="title"><?php echo esc_html__( 'Project description', 'universe' ); ?></h4>
								<div class="work-desc"><p><?php echo do_shortcode( $post->post_content ); ?></p></div>
								<?php if ( !empty( $our_work_options['link'] ) ): ?>
									<a href="<?php echo esc_url( $our_work_options['link'] ); ?>" class="btn-style3">
										<?php esc_html_e('View project', 'universe' ); ?>
									</a>
								<?php endif ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="work-content-right">
								<h4 class="title"><?php esc_html_e( 'Summary', 'universe' ); ?></h4>
								<ul>
									<?php if ( isset( $our_work_options['outhor'] ) && !empty( $our_work_options['outhor'] ) ): ?>
										<li><h4><?php esc_html_e( 'Created by', 'universe' ); ?></h4><?php echo esc_html($our_work_options['outhor']); ?></li>
									<?php endif ?>

									<?php if ( isset( $our_work_options['our_date'] ) && !empty( $our_work_options['our_date'] ) ): ?>
										<li><h4><?php esc_html_e( 'Delivered', 'universe' ); ?></h4><?php echo esc_html($our_work_options['our_date']); ?></li>
									<?php endif ?>

									<?php if ( isset( $custom_fields ) ): ?>
										<?php foreach ( $custom_fields as $key => $value ): ?>
											<?php if ( !empty( $our_work_options[$value['id']] ) ): ?>
												<li><h4><?php echo esc_html( $value['title'] ); ?></h4><?php echo esc_html($our_work_options[$value['id']]) ?></li>
											<?php endif ?>
										<?php endforeach ?>
									<?php endif ?>
									<li>
										<h4><?php esc_html_e( 'Share', 'universe' ); ?></h4>
										<ul class="social">
											<li>
												<a href="<?php echo esc_url( 'https://twitter.com/home?status='.$escaped_link ); ?>" class="fa fa-twitter"></a>
											</li>
											<li>
												<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u='.$escaped_link ); ?>" class="fa fa-facebook"></a>
											</li>
											<li>
												<a href="<?php echo esc_url( 'https://plus.google.com/share?url='.$escaped_link ); ?>" class="fa fa-google-plus"></a>
											</li>
											<li>
												<a href="<?php echo esc_url( 'https://www.linkedin.com/shareArticle?mini=true&url=&title=&summary=&source='.$escaped_link ); ?>" class="fa fa-linkedin"></a>
											</li>
											<li>
												<a href="<?php echo esc_url( 'https://pinterest.com/pin/create/button/?url=&media=&description='.$escaped_link ); ?>" class="fa fa-pinterest"></a>
											</li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php else: ?>
			<?php $image  = kc_tools::createImageSize( $image, '555x670xct' ); ?>

			<div class="work-single style-2">
				<div class="row">
					<div class="col-md-6">
						<div id="portfolio-large-preview">
							<img src="<?php echo esc_url( $image ); ?>" alt="" />
						</div>
						<?php if ( isset( $our_work_options['images_list'] ) && !empty( $our_work_options['images_list'] ) ): ?>
							<div class="portfolio_thumbnails">
								<?php foreach ($our_work_options['images_list'] as $key => $value): ?>
									<?php $url_thumb = wp_get_attachment_image_url( $key, 'thumbnail' ); ?>
									<a target="_blank" href="<?php echo esc_url( $value ); ?>">
										<img src="<?php echo esc_url( $url_thumb ); ?>">
									</a>
								<?php endforeach ?>
							</div>
						<?php endif ?>
					</div>
					<div class="col-md-6">
						<div class="work-content">
							<div class="work-content-left">
								<h4 class="title"><?php echo esc_html__( 'Project description', 'universe' ); ?></h4>
								<div class="work-desc"><p><?php echo do_shortcode( $post->post_content ); ?></p></div>
								<?php if ( !empty( $our_work_options['link'] ) ): ?>
									<a href="<?php echo esc_url( $our_work_options['link'] ); ?>" class="btn-style3">
										<?php esc_html_e('View project', 'universe' ); ?>
									</a>
								<?php endif ?>
							</div>
							<div class="work-content-right">
								<h4 class="title"><?php esc_html_e( 'Summary', 'universe' ); ?></h4>
								<ul>
									<?php if ( isset( $our_work_options['outhor'] ) && !empty( $our_work_options['outhor'] ) ): ?>
										<li><h4><?php esc_html_e( 'Created by', 'universe' ); ?></h4><?php echo esc_html($our_work_options['outhor']); ?></li>
									<?php endif ?>

									<?php if ( isset( $our_work_options['our_date'] ) && !empty( $our_work_options['our_date'] ) ): ?>
										<li><h4><?php esc_html_e( 'Delivered', 'universe' ); ?></h4><?php echo esc_html($our_work_options['our_date']); ?></li>
									<?php endif ?>

									<?php if ( isset( $custom_fields ) ): ?>
										<?php foreach ( $custom_fields as $key => $value ): ?>
											<?php if ( !empty( $our_work_options[$value['id']] ) ): ?>
												<li><h4><?php echo esc_html( $value['title'] ); ?></h4><?php echo esc_html( $our_work_options[$value['id']] ) ?></li>
											<?php endif ?>
										<?php endforeach ?>
									<?php endif ?>
									<li>
										<h4><?php esc_html_e( 'Share', 'universe' ); ?></h4>
										<ul class="social">
											<li>
												<a href="<?php echo esc_url( 'https://twitter.com/home?status='.$escaped_link ); ?>" class="fa fa-twitter"></a>
											</li>
											<li>
												<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u='.$escaped_link ); ?>" class="fa fa-facebook"></a>
											</li>
											<li>
												<a href="<?php echo esc_url( 'https://plus.google.com/share?url='.$escaped_link ); ?>" class="fa fa-google-plus"></a>
											</li>
											<li>
												<a href="<?php echo esc_url( 'https://www.linkedin.com/shareArticle?mini=true&url=&title=&summary=&source='.$escaped_link ); ?>" class="fa fa-linkedin"></a>
											</li>
											<li>
												<a href="<?php echo esc_url( 'https://pinterest.com/pin/create/button/?url=&media=&description='.$escaped_link ); ?>" class="fa fa-pinterest"></a>
											</li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endif ?>
	</div>

	<?php
		$categories = get_the_category($post->ID);
		$category_ids = array();
		foreach( $categories as $individual_category ){
			$category_ids[] = $individual_category->term_id;
		}

		$args = array(
			'post_type'      => 'kc-works',
			'posts_per_page' => 4,
			'post__not_in'   => array($post->ID),
			'category__in'   => $category_ids
		);

		$work_query = new WP_Query( $args );
	?>
	<!-- Related project -->
	<div class="related-project">

		<h3><?php esc_html_e( 'Related project', 'universe' ); ?></h3>

		<?php
			if ( $work_query->have_posts() ) : while ( $work_query->have_posts() ) : $work_query->the_post();

				global $post;
				$thumbnail_url = kc_tools::get_featured_image( $post );
				$thumbnail_url = kc_tools::createImageSize( $thumbnail_url, '475x300xct' );

				$taxonomy = 'kc-works-category';
				$post_id  = $post->ID;

				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );

				$cat_item = array();
				if ( !empty( $post_terms ) && !is_wp_error( $post_terms ) ) {
					$term_ids = implode( ',' , $post_terms );

					$args = array(
						'orderby'  => 'name',
						'order'    => 'ASC',
						'include'  => $term_ids,
						'taxonomy' => $taxonomy
					);

					$categories = get_categories( $args );

					foreach ( $categories as $category ) {
						$cat_item[] = $category->name;
					}
				}
		?>

				<div class="related-project-item">
					<figure>
						<img alt="" src="<?php echo esc_url( $thumbnail_url ); ?>">
					</figure>
					<div class="overlay">
						<div class="overlay-content">
							<a class="sk-search" href="<?php the_permalink(); ?>"></a>
							<h3><?php the_title(); ?></h3>
							<span><?php echo implode( ", ", $cat_item ); ?></span>
						</div>
					</div>
				</div>

		<?php
			endwhile;
			endif;
		?>
		<?php wp_reset_query(); wp_reset_postdata(); ?>

	</div>
	<!-- End Related Project -->
</div>




<script type="text/javascript">
(function($){
	$(window).load(function() {
		$('.portfolio_thumbnails a').each(function(){
			var obj = this;
			var img = new Image();
			img.onload = function(){
				$(obj).html('').append( this ).on( 'click', function(e){
					var new_src = $(this).attr('href');
					$('#portfolio-large-preview img').animate({'opacity':0.1},150,function(){
						$('#portfolio-large-preview img').attr({ 'src' : new_src }).css({ 'opacity' : 0 }).animate({ 'opacity' : 1 });
					});
					e.preventDefault();
				});
			}
			img.src = $(this).attr('href');
		});
	});
})(jQuery);
</script>

<?php get_footer(); ?>