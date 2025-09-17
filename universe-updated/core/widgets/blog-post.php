<?php

/*
*	(c) king-theme.com
*/

class Blog_Post_Widget extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_posts', 'description' => esc_html__( 'Your site&#8217;s most blog posts.', 'universe' ) );
		parent::__construct('blog-posts', esc_html__( 'Blog Posts', 'universe' ), $widget_ops);
		$this->alt_option_name = 'widget_blog_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {

		$universe = universe::globe();

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Blog Posts', 'universe' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
		if ( ! $number )
			$number = 3;
		$show_thumb = isset( $instance['show_thumb'] ) ? $instance['show_thumb'] : false;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) ) );

		if ($r->have_posts()) :
		?>
			<?php echo universe::esc_js($args['before_widget']); ?>
				<?php if ( $title ) {
					echo universe::esc_js($args['before_title'] . $title . $args['after_title']);
				} ?>
				<ul class="sbposts">

					<?php while ( $r->have_posts() ) : $r->the_post(); ?>
						<li>
							<figure>
								<?php 
									if ( $show_thumb ){
										$thumbnail_url = $universe->get_featured_image($r->post);
										$thumbnail_url = universe_createLinkImage($thumbnail_url, '75x75xc');
										echo '<img src="'. esc_url( $thumbnail_url ) .'" />';
									}
								?>
							</figure>

							<div class="title">
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<ul class="post-meta">
									<li><a href="#"><?php echo get_the_date('d M Y'); ?></a></li>
									<li><i class="icon sk-message"></i><a href="#"><?php echo get_comments_number( $r->post->ID ); ?></a></li>
								</ul>						
							</div>
						</li>
					<?php endwhile; ?>

				</ul><!-- end footer blogs -->
			<?php echo universe::esc_js($args['after_widget']); ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_thumb'] = isset( $new_instance['show_thumb'] ) ? (bool) $new_instance['show_thumb'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;
		$show_thumb = isset( $instance['show_thumb'] ) ? (bool) $instance['show_thumb'] : false;
?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'universe' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php _e( 'Number of posts to show:', 'universe' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_thumb ); ?> id="<?php echo esc_attr($this->get_field_id( 'show_thumb' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_thumb' )); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id( 'show_thumb' )); ?>"><?php _e( 'Display post thumbnail?', 'universe' ); ?></label></p>
<?php
	}
}

add_action('widgets_init', fn() => register_widget('Blog_Post_Widget'));