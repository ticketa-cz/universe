<?php
/*
	(c) king-theme.com
*/

global $wpdb, $universe;

$prepare_query = $wpdb->prepare(
	
	"SELECT * FROM `$wpdb->options` WHERE ".
	"`$wpdb->options`.`option_name` LIKE '%s' ".
	" OR ".
	"`$wpdb->options`.`option_name` = '%s' ".
	" OR ".
	"`$wpdb->options`.`option_name` = 'sidebars_widgets'", 
	
    "widget_%", 
    "king_".strtolower( UNIVERSE_THEME_NAME )."_options_css"
    
);

$wgs = $wpdb->get_results( $prepare_query );					
							
$data = array();
if( count( $wgs ) ){
	foreach( $wgs as $wg ){
		if( get_option( $wg->option_name ) != false ){
			$data[ $wg->option_name ] =  $universe->ext['be']( json_encode( get_option( $wg->option_name ) ) );
		}
	}
}

// Theme options
$themeOptions = get_option( UNIVERSE_THEME_OPTNAME );
if( $themeOptions != false ){
	$data[ UNIVERSE_THEME_OPTNAME ] = str_replace( UNIVERSE_THEME_URI, '%UNIVERSE_THEME_URI%', json_encode( $themeOptions ) );
}

		
$export = json_encode($data);

$file = UNIVERSE_THEME_PATH.DS.'core'.DS.'sample'.DS.'data'.DS.'widgets.export.txt';
$fp = @$universe->ext['fo']( $file, 'w');

$universe->ext['fw']( $fp, $export );
$universe->ext['fc']( $fp );

echo '<strong style="color:green">Export succesful</strong><br /> Data stored in the file <i>www/wp-contents/themes/'.strtolower(UNIVERSE_THEME_NAME).'/core/sample/data/widgets.export.txt</i>';
echo '<br /><br />';
echo '<strong>Ho To import</strong><br />  -step 1: copy your backup file under name <strong>"widgets.export.txt"</strong> into folder www/wp-contents/themes/'.strtolower(UNIVERSE_THEME_NAME).'/core/sample/ ';
echo '<br />';
echo '-step 2: Run a link <strong>'.UNIVERSE_SITE_URI.'/wp-admin?devn=import</strong> ';
echo '<br /><br />';
echo '<a href="'.UNIVERSE_SITE_URI.'/wp-admin">Go Back</a>';
exit;
	



