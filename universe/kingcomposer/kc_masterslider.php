<?php
$slug = $class = '';
extract( $atts );

echo '<div class="kc-masterslider '. (empty( $class ) ? "" : " ".$class) .'">'.do_shortcode('[masterslider alias="'.$slug.'"]').'</div>';

?>