<?php

/*
*	(c) king-theme.com
*/

class Margin_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
						'classname'		=> 'widget_margin',
						'description'	=> esc_html__('Creating distance between widget.','universe')
					);
		$control_ops = array('width' => 300, 'height' => 300);
		parent::__construct('margin', esc_html__('Margin distance','universe'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {

		$universe = universe::globe();
		extract($args);

		$distance		= empty($instance['distance']) ? '' : $instance['distance'];

		echo '<div class="clearfix margin_top'. $distance .'"></div>';

	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['distance']		= strip_tags(!empty($new_instance['distance'])?$new_instance['distance']:'');

		return $instance;

	}

	function form( $instance ) {

		$instance	= wp_parse_args( (array) $instance, array('distance' =>''));
		$distance		= strip_tags($instance['distance']);

	?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('distance') ); ?>">
				<?php _e( 'Select distance:', 'universe' ); ?>
			</label>

			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('distance') ); ?>" name="<?php echo esc_attr( $this->get_field_name('distance') ); ?>">
				<?php for($i=1; $i<13; $i++){ ?>
					<option value="<?php echo esc_attr($i); ?>" <?php if($i == $distance ) echo 'selected="selected"'; ?>><?php echo esc_attr($i).'0 px'; ?></option>
				<?php } ?>
			</select>

		</p>


<?php
	}
}

add_action('widgets_init', fn() => register_widget('Margin_Widget'));
