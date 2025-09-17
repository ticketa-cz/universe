<?php
class universe_options_images extends universe_options{

	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since universe_options 1.0
	*/
	function __construct($field = array(), $value ='', $parent = ''){

		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;

	}//function



	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since universe_options 1.0
	*/
	function render(){

		$class = (isset($this->field['class']))?$this->field['class']:'regular-text';

		$data_arr = array(
			'id'		=> $this->field['id'],
			'opt_name'	=> $this->args['opt_name']
		);

		$data_json = json_encode( $data_arr );
?>
		<div class="king-images-wrp" data-images-field='<?php echo universe::esc_js($data_json); ?>'>
			<input type="hidden" id="<?php echo esc_attr( $this->field['id'] ); ?>" name="<?php echo esc_attr( $this->args['opt_name'] . '[' . $this->field['id'].']' ); ?>" value="" class="<?php echo esc_attr( $class ); ?> king-images-input" />
			<button id="king-images-button-<?php echo esc_attr( $this->field['id'] ); ?>" class="button button-large button-primary king-images-button">
				<i class="fa fa-cloud-upload"></i> <?php echo esc_html__( 'Upload Image', 'universe' ); ?>
			</button>
			<?php echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<br/><span class="description">'.$this->field['desc'].'</span>':''; ?>
			<ul id="<?php echo esc_attr( $this->field['id'] ); ?>_status" class="kingtheme_media_status attach_list" style="">
				<?php if ( !empty( $this->value ) ): ?>
					<?php foreach ($this->value as $key => $value): ?>
						<li class="img_status">
							<span class="king-images-button-remove"><i class="fa fa-times"></i></span>
							<img width="70" height="70" src="<?php echo esc_url( $value ); ?>" class="attachment-70x70" alt="">
							<input type="hidden" id="filelist-<?php echo esc_attr( $this->field['id'] ); ?>" name="<?php echo esc_attr( $this->args['opt_name'] . '[' . $this->field['id'].']' ); ?>[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_url( $value ); ?>">
						</li>
					<?php endforeach ?>
				<?php endif ?>
			</ul>
		</div>
<?php

	}


	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since universe_options 1.0
	*/
	function enqueue(){

		wp_enqueue_script(
			'nhp-opts-field-images-js',
			universe_options_URL.'fields/images/field_images.js',
			array('jquery', 'thickbox', 'media-upload'),
			time(),
			true
		);

		wp_enqueue_style('thickbox');// thanks to https://github.com/rzepak
		wp_enqueue_media();
		//wp_localize_script('nhp-opts-field-images-js', 'universe_upload', array('url' => $this->url.'fields/images/blank.png'));

	}//function

}//class
?>