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

$universe_user = new universe_User();
$countries = $universe_user->get_countries();

?>


<div class="logregform two">
	<div class="title">
		<h3><?php esc_html_e('REGISTRATION', 'universe' ); ?></h3>
		<p>
			<?php esc_html_e('Already Registered?', 'universe' ); ?>
			&nbsp;<a href="<?php echo esc_url( home_url('/?action=login') ); ?>"><?php esc_html_e('Log In', 'universe' ); ?>.</a></p>
	</div>

	<div class="feildcont">
		<form id="king_form" class="king-form" name="registerform" method="post" action="" novalidate="novalidate">

			<label><?php esc_html_e('Username', 'universe' ); ?> <em>*</em></label>
			<input type="text" name="user_login" placeholder="" />

			<label><?php esc_html_e('Email', 'universe' ); ?> <em>*</em></label>
			<input type="email" name="user_email" placeholder="" />

			<div class="one_half">
				<label><?php esc_html_e('Password', 'universe' ); ?> <em>*</em></label>
				<input type="password" name="password" placeholder="" id="password" />
			</div>

			<div class="one_half last">
				<label><?php esc_html_e('Confirm Password', 'universe' ); ?> <em>*</em></label>
				<input type="password" name="passwordConfirm" placeholder="" />
			</div>

			<label><?php esc_html_e('Name', 'universe' ); ?></label>
			<input type="text">

			<div class="one_third radiobut">
				<label><?php esc_html_e('Gender', 'universe' ); ?></label>
				<input class="one" type="radio" name="sex" value="male" checked>
				<span class="onelb"><?php esc_html_e('Male', 'universe' ); ?></span>
				<input class="two" type="radio" name="sex" value="female">
				<span class="onelb"><?php esc_html_e('Female', 'universe' ); ?></span>
			</div>

			<div class="two_third last">
				<label>Date of Birth</label>

				<div class="one_third">
					<select name="bd_day">
						<option value="0"><?php esc_html_e('Day', 'universe' ); ?></option>
						<?php
							for($i=1;$i<=31;$i++){
								echo "<option value='$i'>$i</option>";
							}
						?>
					</select>
				</div>

				<div class="one_third">
					<select name="bd_month">
						<option value="0"><?php esc_html_e('Month', 'universe' ); ?></option>
						<?php
							for($m=1;$m<=12;$m++){
								$dateObj   = DateTime::createFromFormat('!m', $m);
								$monthName = $dateObj->format('F'); // March
								echo "<option value='$m'>$monthName</option>";
							}
						?>
					</select>
				</div>

				<div class="one_third last">
					<select name="bd_year">
						<option value="0"><?php esc_html_e('Year', 'universe' ); ?></option>
						<?php
							for($i=2001;$i>=1980;$i--){
								echo "<option value='$i'>$i</option>";
							}
						?>
					</select>
				</div>

			</div>
			
			<div class="margin_bottom2"></div>

			<div class="one_half">
				<label><?php esc_html_e('City', 'universe' ); ?></label>
				<input type="text" name="city" value="" />
			</div>

			<div class="one_half last">
				<label><?php esc_html_e('Country', 'universe' ); ?></label>

				<select name="country">
					<option value="0">- Select -</option>
					<?php
						foreach($countries as $code => $country_name){
							echo "<option value='$code'>$country_name</option>";
						}
					?>
				</select>
			</div>

			<label><?php esc_html_e('Address', 'universe' ); ?></label>
			<input type="text" name="address" value="">

			<div class="checkbox">
				<input type="checkbox" name="argee" class="re_checkbox" checked="checked">
				<label><?php esc_html_e('I agree the User Agreement and<a href="#">Terms &amp; Condition.</a>', 'universe' ); ?></label>
			</div>

			<p class="status"></p>

			<button type="button" class="fbut btn-register"><?php esc_html_e('Create Account', 'universe' ); ?></button>

			<input type="hidden" name="action" value="king_user_register" />
			<?php wp_nonce_field( 'ajax-register-nonce', 'security_reg' ); ?>
		</form>
	</div>
</div>
