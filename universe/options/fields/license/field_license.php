<?php

class universe_options_license extends universe_options{	

	function __construct( $field = array(), $value ='', $parent ){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}

	function render(){
		
		global $universe;
		
		$current_valid = get_option( 'universe_valid', true );
		if( !empty( $current_valid ) )
			$current_valid = $universe->bsp( $current_valid );
		else $current_valid = '';
		
		$license = get_option( 'universe_purchase_code' );
		if( empty( $license ) )
			$license = '';
		
?>
<table class="form-table blog-table-opt" style="border: none;margin: 0px;">
	<tr>
		<th scope="row">
			<label for="blog-layout"><?php _e( 'Purchase Code' , 'universe'); ?></label>
		</th>
		<td class="king-upload-wrp" id="verify-purchase-wrp">
			<div id="verify-purchase-status"></div>
			<p>
				<input type="text" id="input-purchase-key" name="" autocomplete="off" value="<?php echo esc_attr( $license ); ?>" class="regular-text" style="height: 35px;" />
				<button class="button button-large button-primary" id="verify-purchase-key">
					<i class="fa fa-key"></i> <?php _e( 'Verify This Key' , 'universe'); ?>
				</button>
			</p>
			<p>
				<a href="<?php echo esc_url( 'http://help.king-theme.com/faq/12-how-to-find-my-purchase-code-3f.html' ); ?>" target=_blank><?php _e( 'How to find the purchase code?' , 'universe'); ?></a>
			</p>
			<div class="loading">
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>
				<br />
				<?php _e( 'Please wait a moment...' , 'universe'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="blog-layout"><?php _e( 'Verification Status' , 'universe'); ?></label>
		</th>
		<td id="verify-purchase-msg-wrp">
			<div class="msg-notice verify-stt <?php if( $current_valid !== $universe->bsp( site_url() ) )echo ' active'; ?>">
				<i class="fa fa-warning"></i> <?php printf( esc_html__( 'The domain %s has yet to be verified.' , 'universe'), '<u style="color:#555">'.site_url().'</u>' ); ?>
			</div>
			<div class="msg-success verify-stt <?php if( $current_valid === $universe->bsp( site_url() ) )echo ' active'; ?>">
				<i class="fa fa-check"></i> <?php printf( esc_html__( 'Wooot! The domain %s has been verified.' , 'universe'), '<u style="color:#555">'.site_url().'</u>' ); ?>
			</div>
			<br />
			<p>
				<?php _e( 'Have You verified the purchase key with another domain? Don\'t worry! You can reissues your purchase key for another domain', 'universe'); ?>.<br />
				<i>(<?php _e( 'Maximum 3 domains per key, and can\'t reissues the domain which has been verified before', 'universe'); ?>)</i>
			</p>
		</td>
	</tr>
</table>		
<?php	
	
			
	}
	
	function enqueue(){

		
	}//function
	
}//class