<?php
class universe_options_profile_template extends universe_options{

	function __construct($field = array(), $value = '', $parent = ''){

		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();

	}
	function render(){

		global $universe;

		if( is_array( $this->field['options'] ) )
		{

			echo '<fieldset class="radio-img">';
			$i = 0;
			$_file = !empty($this->value['_file_']) ? $this->value['_file_']:'';

			foreach( $this->field['options'] as $name => $args ){

			?>

				<label class="nhp-radio-img nhp-radio-img-header nhp-radio-img-<?php echo esc_attr( $this->field['id'] ); ?><?php if( $_file == esc_attr( $name) )echo ' nhp-radio-img-selected'; ?>" for="<?php echo esc_attr( $this->field['id'] ).'_'.$i; ?>">
					<input type="radio" id="<?php echo esc_attr( $this->field['id'] ).'_'.$i; ?>" name="universe[<?php echo esc_attr( $this->field['id'] ); ?>][_file_]" <?php if( $_file == esc_attr( $name) )echo 'checked'; ?> value="<?php echo esc_attr( $name ); ?>" />
					<img src="<?php echo esc_url( $args['img'] ); ?>" alt="<?php echo esc_attr( $args['title'] ); ?>" onclick="jQuery: universe_radio_img_select('<?php echo esc_attr( $this->field['id'] ).'_'.$i; ?>', '<?php echo esc_attr( $this->field['id'] ); ?>');" />
					<br />
					<h3 class="nhp-label">
						<?php echo esc_html( $args['title'] ); ?>
					</h3>

			<?php

				if( file_exists( UNIVERSE_THEME_PATH.DS.$name ) )
					$positions = get_file_data( UNIVERSE_THEME_PATH.DS.$name, array( 'Positions' ) );
				else $positions = '';

				if( isset( $positions ) && !empty( $positions[0] ) ){

					$positions = explode( ',', $positions[0] );
					$args = !empty($this->value[esc_attr($name)])?$this->value[esc_attr($name)]:array();

					echo '<div class="field-profile-rows">';

					foreach( $positions as $position ){

						$position = explode( '|', trim( $position ) );
						$field_type = !empty( $position[1] ) ?  $position[1] : 'text';

						$templ = 'options'.DS.'fields'.DS.$field_type.'/field_'.$field_type.'.php';

						if( file_exists( UNIVERSE_THEME_PATH.DS.$templ ) )
							universe_incl_core( $templ );

						$field_class = 'universe_options_'.$field_type;

						if( class_exists( $field_class ) ){

							$render = '';
							$obj = new stdClass();
							$obj->extra_tabs = '';
							$obj->sections = '';
							$obj->args = '';
							
							if( isset( $args[ $position[0] ] ) )
								$std = $args[ $position[0] ];
							else if( isset( $position[2] ) )
								$std = html_entity_decode( str_replace( '\n', "\n", $position[2] ) );
							else $std = '';
							
							$field = array(

								'id' => $this->field['id'].']['.esc_attr($name).']['.$position[0],
								'type' => $field_type,
								'title' => ucfirst( str_replace( '_', ' ', $position[0] ) ),
								'sub_desc' => '',
								'std' => $std,

							);

							$render = new $field_class( $field, $field['std'], $obj );

							echo '<div class="field-profile-row">';
								echo '<div class="fpr-label"><strong>';
								echo esc_html($field['title']);
								echo '</strong></div>';
								echo '<div class="fpr-body">';
								$render->render();

								if( method_exists( $render, 'enqueue' ) ){
									$render->enqueue();
								}

								echo '</div>';
							echo '</div>';
						}else{
							echo '<p>Error: could not found param type '.$position[1].'</p>';
						}

					}

					echo '</div>';

				}

				echo '</label>';

				$i++;

			}

			echo '</fieldset>';

		}else echo 'Required options array';

	}

	function enqueue(){
		
		wp_enqueue_script(
			'nhp-opts-field-radio_img-js', 
			universe_options_URL.'fields/profile_template/field_profile_template.js', 
			array('jquery'),
			time(),
			true
		);
		
	}//function

}
