<?php
class universe_options_menu extends universe_options{	

	function __construct( $field = array(), $value ='', $parent ){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}

	function render(){
		
	    echo '<select id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']">';
	    echo '<option>Select Menu</option>';
	    
	    $menus = get_terms( array( 'taxonomy' => 'nav_menu' ) );
	    
	    foreach( $menus as $menu ) {
		    
	        echo '<option';
	        if( $this->value == $menu->slug )
	        	echo ' selected';
	        echo ' value="'.esc_attr( $menu->slug ).'">'.esc_html( $menu->name ).'</option>';
	    }
	    
	    echo '</select>';
		
	}
	
}
?>