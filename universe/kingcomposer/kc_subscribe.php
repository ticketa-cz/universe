<?php

$title = $input_text = $input_submit = $input_submit_class = $method = $mc_api = $mc_list_id = $class = '';

extract( $atts );

$wrap_class	= apply_filters( 'kc-el-class', $atts );
$wrap_class[]	= $class;

if (!empty($mc_api) && !empty($mc_list_id)) {
	$get_mc_method = get_option('kc_mc_method');
	if (!empty($get_mc_method)) {
		if( get_option( 'kc_mc_method', true ) != $method )
			update_option( 'kc_mc_method', $method );
	} else {
		add_option( 'kc_mc_method', '', '', 'yes' );
		update_option( 'kc_mc_method', $method, '', 'yes' );
	}
	$get_kc_mc_api = get_option('kc_mc_api');
	if(!empty($get_kc_mc_api)) {
		if( get_option( 'kc_mc_api', true ) != $mc_api )
			update_option( 'kc_mc_api', $mc_api );
	} else {
		add_option( 'kc_mc_api', '', '', 'yes' );
		update_option( 'kc_mc_api', $mc_api, '', 'yes' );
	}
	$get_kc_mc_list_id = get_option('kc_mc_list_id');
	if (!empty($get_kc_mc_list_id)) {
		if( get_option( 'kc_mc_list_id', true ) != $mc_list_id )
			update_option( 'kc_mc_list_id', $mc_list_id );
	} else {
		add_option( 'kc_mc_list_id', '', '', 'yes' );
		update_option( 'kc_mc_list_id', $mc_list_id, '', 'yes' );
	}
}



if ( !empty( $input_submit ) ) {

	$text_submit = $input_submit;

} else {

	$text_submit = esc_html__( 'Subscribe', 'universe' );

}
?>
	<div class="<?php echo esc_attr( implode(' ', $wrap_class) ) ;?>">

		<form data-url="<?php echo admin_url( 'admin-ajax.php?t='. time() ); ?>" class="kc_subscribe kc_mailchimp" method="POST" action="" _lpchecked="1">
			<input class="enter_email_input required email" name="kc_email" value="" placeholder="<?php echo esc_attr( $input_text ); ?>" type="text">
			<input name="subscribe" value="<?php echo esc_attr( $text_submit ); ?>" class="input_submit <?php echo esc_attr($input_submit_class); ?>" type="submit">
			<div class="kc_newsletter_status"></div>
		</form>
	</div>
