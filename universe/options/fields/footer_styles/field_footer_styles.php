<?php
class universe_options_footer_styles extends universe_options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since universe_options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	


	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since universe_options 1.0
	*/
	function render(){
		global $universe;

		$class = (isset($this->field['class']))?'class="'.$this->field['class'].'" ':'';

		if(empty($this->value)) $this->value='global';
		
		echo '<select id="'.$this->field['id'].'" class="footer_styles" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.'rows="6" >';
			
			foreach($this->field['options'] as $k => $v){
				$post = get_page_by_path($v, OBJECT , 'universe_footer');
				if($post){
					$preview = get_post_meta( $post->ID, '_universe_footer_preview', true );
				}else{
					$preview = '';
				}
				echo '<option value="'.$k.'" '.selected($this->value, $k, false).' data-preview="'. $preview  .'">'.$v.'</option>';
				
			}//foreach

		echo '</select>';

		if(!empty($this->value) && $this->value != 'empty'){
			$post = get_page_by_path($this->value, OBJECT , 'universe_footer');
			if($post){
				$preview = get_post_meta( $post->ID, '_universe_footer_preview', true );

				if( !empty($preview) ){
					$preview_url = get_template_directory_uri() .'/core/footers/previews/';
					$img_preview_url = $preview_url . $preview;
		        	echo '<div><img id="preview_'. $this->field['id'] .'" src="'. esc_url( $img_preview_url ) .'" style="max-width:100%;margin-top:10px;max-height: 350px;border:10px solid #EEE;" data-url="'. $preview_url .'" /></div>';
		        }
			}			
		}

		if($this->value == 'global'){
			if(isset($universe->cfg['footer_style'])){
				$post = get_page_by_path($universe->cfg['footer_style'], OBJECT , 'universe_footer');
				if($post){
					$preview = get_post_meta( $post->ID, '_universe_footer_preview', true );
				}else{
					$preview = '';
				}
			}
			$preview_url = get_template_directory_uri() .'/core/footers/previews/';
			$img_preview_url = $preview_url . $preview;
			echo '<div><img id="preview_'. $this->field['id'] .'" src="'. esc_url( $img_preview_url ) .'" style="max-width:100%;margin-top:10px;max-height: 350px;border:10px solid #EEE;" data-url="'. $preview_url .'" /></div>';
		}
	
		if($this->value == 'empty'){
			$preview_url = get_template_directory_uri() .'/core/footers/previews/';
			$img_preview_url = $preview_url . 'empty.png';
			echo '<div><img id="preview_'. $this->field['id'] .'" src="'. esc_url( $img_preview_url ) .'" style="max-width:100%;margin-top:10px;max-height: 350px;border:10px solid #EEE;" data-url="'. $preview_url .'" /></div>';
		}

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.$this->field['desc'].'</span>':'';
		
	}//function

	function enqueue(){
		
		wp_enqueue_script(
			'nhp-opts-field-footer-styles-js', 
			universe_options_URL.'fields/footer_styles/footer_styles.js', 
			array('jquery'),
			time(),
			true
		);
		
	}//function
	
}//class
?>