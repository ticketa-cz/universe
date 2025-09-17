<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( is_user_logged_in() ){
	echo '<p>'.esc_html__( 'You are logged in', 'universe' ).'</p>';
	return;
}

global $post;

?>

<div class="logregform">
	<div class="title">
		<h3><?php esc_html_e('Account Login', 'universe' ); ?></h3>
		<p>
			<?php esc_html_e('Not member yet?', 'universe' ); ?> &nbsp;
			<a href="<?php echo esc_url( home_url('/?action=register') ); ?>"><?php esc_html_e('Sign Up.', 'universe' ); ?></a>
		</p>
	</div>

	<div class="feildcont">
		<form id="king-form" method="post" name="loginform" action="" class="king-form" novalidate="novalidate">
			<label><i class="fa fa-user"></i> <?php esc_html_e('Username / Email', 'universe' ); ?></label>
			<input type="text" name="log" value="" />

			<label><i class="fa fa-lock"></i> <?php esc_html_e('Password', 'universe' ); ?></label>
			<input type="password" name="pwd" value="" />

			<div class="checkbox">
				<label>
					<input type="checkbox" name="rememberme" />
				</label>
				<label><?php esc_html_e('Remember Me', 'universe' ); ?></label>
				<label>
						<a href="<?php echo esc_url( home_url('/?action=forgot') ); ?>">
							<strong><?php esc_html_e('Forgot Password?', 'universe' ); ?></strong>
						</a>
				</label>
			</div>

			<p class="status"></p>

			<button type="button" class="fbut btn-login"><?php esc_html_e('Login Now!', 'universe' ); ?></button>

			<input type="hidden" name="action" value="king_user_login" />
			<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
		</form>
	</div>
</div>

