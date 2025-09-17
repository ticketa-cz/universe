<?php

// JS callback for live editor
kc_js_callback('kc_pro.works');

$filter = $filter_align = $animation = $column = $tax_term = $items = $hover_style = $caption_style = $gap = $show_link = $class = $filter_text = $filter_count = $filter_splitter = $class_main = $class_filter = $custom_class = '';
$layout = 1;

$atts['taxonomy']  = 'kc-works-category';
$atts['post_type'] = 'kc-works';

$works = kc_tools::get_posts( $atts );

extract( $atts );

$main_class = apply_filters( 'kc-el-class', $atts );
$main_class[] = 'kc-works-' . $layout;

if ( !empty( $custom_class ) ) {
	$main_class[] = $custom_class;
}

switch ( $layout ) {
	case '2':
		$class_main 	= 'js-grid-juicy-projects cbp';
		$class_filter 	= 'js-filters-juicy-projects';
	break;
	case '3':
		$class_main 	= 'js-grid-masonry cbp';
		$class_filter 	= 'js-filters-masonry';
	break;
	case '4':
		$class_main 	= 'works-layout-4';
		$class_filter 	= 'portfolio-btn';
	break;
	default:
		$class_main 	= 'js-grid-full-width cbp';
		$class_filter 	= 'js-filters-full-width';
	break;
}

$gap = !empty($gap) ? $gap : 0;

if ( count($works) >0 ) {

?>
	<div class="<?php echo esc_attr( implode( " ", $main_class ) ); ?>">

		<?php if ( $filter == 'yes' ): ?>
			<?php
				$filter_class = array();
				if ( !empty( $filter_text ) ) {
					$filter_class[] = 'cbp-l-filters-' . $filter_text;
				} else {
					$filter_class[] = 'cbp-l-filters';
				}

				$filter_class[] = 'cbp-l-filters-align' . $filter_align;

			?>

			<div id="<?php echo esc_attr( $class_filter ); ?>" class="<?php echo implode( " ", $filter_class ); ?>">
				<?php
					$filter_list_cats = array();

					$filter_list_cat = '<div data-filter="*" class="cbp-filter-item-active cbp-filter-item">';
						$filter_list_cat .= esc_html__( 'All', 'universe' );
						if ( $filter_count == 'yes' ) {
							$filter_list_cat .= '<div class="cbp-filter-counter"></div>';
						}
					$filter_list_cat .= '</div>';

					$filter_list_cats[] = $filter_list_cat;
				?>

				<?php

					$filters = get_categories('taxonomy=kc-works-category');
					if ( !empty( $tax_term ) ) {
						$cat_str = trim( $tax_term );
						$cat_arr = explode( ",", $cat_str );

						foreach( $filters as $val ){
							if ( in_array( $val->slug, $cat_arr) ) {
								$filter_list_cat = '';
								$filter_list_cat .= '<div class="cbp-filter-item" data-filter=".'. $val->slug .'">'. $val->name;
									if ( $filter_count == 'yes' ) {
										$filter_list_cat .= '<div class="cbp-filter-counter"></div>';
									}
								$filter_list_cat .= '</div>';

								$filter_list_cats[] = $filter_list_cat;
							}
						}

					} else {
						foreach( $filters as $val ){
							$filter_list_cat = '';
							$filter_list_cat .= '<div class="cbp-filter-item" data-filter=".'. $val->slug .'">'. $val->name;
								if ( $filter_count == 'yes' ) {
									$filter_list_cat .= '<div class="cbp-filter-counter"></div>';
								}
							$filter_list_cat .= '</div>';

							$filter_list_cats[] = $filter_list_cat;
						}
					}

					echo implode( $filter_splitter, $filter_list_cats );

				?>

			</div>

		<?php endif ?>

		<div class="portfolio-content <?php echo esc_attr( $class_main ); ?>" data-cols="<?php echo isset($column)?$column:'3'; ?>" data-gap="<?php echo esc_attr( $gap ); ?>" data-caption="<?php echo esc_attr( $caption_style ); ?>" data-animation="<?php echo esc_attr( $animation ); ?>">

			<?php
			$dem = 1;
			foreach( $works as $item ) :

				$image = kc_tools:: get_featured_image( $item );

				$class_item = array( 'cbp-item' );
				$cats_name  = array();

				if ( $filter == 'yes' ) {
					$taxonomy = 'kc-works-category';
					$post_id  = $item->ID;

					$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );

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
							$class_item[] = $category->slug;
							$cats_name[]  = $category->name;
						}
					}
				}

				$work_cf = get_post_meta( $item->ID , '_'.KCP_OPTNAME.'_post_meta_options', TRUE );
				if ( !isset( $work_cf['outhor'] ) || empty( $work_cf['outhor'] ) ) {
					$work_cf['outhor'] = esc_html__( 'King - Theme', 'universe' );
				}
				$link	= !empty($work_cf['link']) ? $work_cf['link'] : get_permalink( $item->ID );

				$thumbnail_url = kc_tools::get_featured_image( $item );
				switch ( $layout ) {
					case '2':
						$thumbnail_url = kc_tools::createImageSize( $thumbnail_url, '680x600xct' );
					break;
					case '3':
						$thumbnail_url = $image;
					break;
					case '4':
						$thumbnail_url = kc_tools::createImageSize( $thumbnail_url, '437x300xct' );
					break;
					default:
						$kt_dem        = $dem%8;
						switch ( $kt_dem ) {
							case '1':
								$thumbnail_url = kc_tools::createImageSize( $thumbnail_url, '960x700xct' );
							break;
							case '4':
								$thumbnail_url = kc_tools::createImageSize( $thumbnail_url, '960x350xct' );
							break;
							default:
								$thumbnail_url = kc_tools::createImageSize( $thumbnail_url, '480x350xct' );
							break;
						}
					break;
				}
			?>
					<div class="<?php echo implode( " ", $class_item ); ?>">
						<?php if ( $hover_style == 2 ): ?>
							<div class="cbp-caption">
								<div class="cbp-caption-defaultWrap">
									<img src="<?php echo esc_url($thumbnail_url); ?>" alt="" />
								</div>
								<div class="cbp-caption-activeWrap">
									<div class="cbp-l-caption-alignCenter">
										<div class="cbp-l-caption-body">
											<a href="<?php echo esc_url($image); ?>" class="cbp-lightbox cbp-l-caption-buttonRight" data-title="<?php echo get_the_title( $item ); ?> <?php echo ( isset( $work_cf['outhor'] ) ) ? '<br>by' . esc_html($work_cf['outhor']) : ''; ?>"><i class="sk-search"></i></a>
											<h3><?php echo get_the_title( $item ); ?></h3>
											<span><?php echo implode( ", ", $cats_name ); ?></span>
										</div>
									</div>
								</div>
							</div>
						<?php else: ?>
							<a href="<?php echo esc_url( $image ); ?>" class="cbp-caption cbp-lightbox" data-title="<?php echo get_the_title( $item ); ?><br>by <?php echo esc_html($work_cf['outhor']); ?> <?php if( $show_link == 'yes' ) { ?><a href='<?php echo esc_url( $link ); ?>'>Read More &raquo;</a><?php } ?>">
								<div class="cbp-caption-defaultWrap">
									<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="">
								</div>
								<div class="cbp-caption-activeWrap">
									<div class="cbp-l-caption-alignCenter">
										<div class="cbp-l-caption-body">
											<div class="cbp-l-caption-title"><?php echo get_the_title( $item ); ?></div>
											<div class="cbp-l-caption-desc"><?php esc_html_e( 'by', 'universe' ); ?> <?php echo esc_html($work_cf['outhor']); ?></div>
										</div>
									</div>
								</div>
							</a>
						<?php endif ?>
					</div>

			<?php
					$dem++;
				endforeach;
			?>

		</div>

	</div>

<?php

} else {
	echo '<h4>' . esc_html__( 'Works not found', 'universe' ) . '</h4> <a href="'.admin_url('post-new.php?post_type=kc-works').'"><i class="fa fa-plus"></i> Add New Work</a>';
}
