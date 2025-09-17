<?php
/**
 * (c) www.king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $universe, $post, $more;

get_header();

?>

<div id="primary" class="site-content">
	<div id="content" class="container">
		<div class="entry-content blog_postcontent">
			<div class="margin_top1"></div>

			<div class="logregform">
				<div class="title">
					<h3><?php esc_html_e('Forgot your password', 'universe' ); ?></h3>
					<p>
						<?php esc_html_e('Back to login', 'universe'); ?>
						<a href="<?php echo esc_url( home_url('/?action=login') ); ?>">
							<?php esc_html_e('Login', 'universe'); ?>
						</a>
					</p>
				</div>

				<div class="feildcont">
					<form id="king-form" method="post" name="loginform" action="" class="king-form" novalidate="novalidate">
						<label><i class="fa fa-user"></i> <?php esc_html_e('Enter your Email', 'universe' ); ?></label>
						<input type="text" name="email" value="" />

						<p class="status"></p>

						<button type="button" class="fbut btn-resetpwd"><?php esc_html_e('Reset password!', 'universe' ); ?></button>

						<input type="hidden" name="action" value="king_user_forgot" />
						<?php wp_nonce_field( 'ajax-forgotpw-nonce', 'security_fgpw' ); ?>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>



<?php get_footer(); ?>