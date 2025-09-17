<?php
class universe_options_menu_select extends universe_options{	


	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}
	
	function render(){
		
		$class = (isset($this->field['class']))?'class="'.$this->field['class'].'" ':'';
		
		echo '<select id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.' >';
		
		if(!isset($this->field['args'])){$this->field['args'] = array();}
		$args = wp_parse_args($this->field['args'], array());
			
		$menus = wp_get_nav_menus($args);
		if($menus){
			foreach ( $menus as $menu ) {
				echo '<option value="'.$menu->term_id.'"'.selected($this->value, $menu->term_id, false).'>'.$menu->name.'</option>';
			}
		}

		echo '</select>';

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.$this->field['desc'].'</span>':'';
		
	}
	
}