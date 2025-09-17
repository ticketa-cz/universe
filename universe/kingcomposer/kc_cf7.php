<?php

global $wpdb;

$title = $slug = $class = '';

extract( $atts );

$form = $wpdb->get_results($wpdb->prepare("SELECT `ID` FROM `$wpdb->posts` WHERE `post_type` = 'wpcf7_contact_form' AND `post_name` = '%s' LIMIT 1", sanitize_title($slug)));

if( !empty( $form ) ){
	echo '<div class="kc-ctf7 '. (empty( $class ) ? "" : " ".$class) .'">'.do_shortcode('[contact-form-7 id="'.$form[0]->ID.'" title="'.esc_attr($title).'"]').'</div>';
}else{
	echo '[contact-form-7 not found slug ('.esc_attr($slug).') ]';
}