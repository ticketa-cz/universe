<?php
/**
 * (c) www.devn.co
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$universe = universe::globe();

?>

<section class="coming-soon">
	<div class="before-content">
		<div class="coming-content">
			<div class="newlettter">
				<form data-url="<?php echo admin_url( 'admin-ajax.php?t='. time() ); ?>" method="POST" action="" _lpchecked="1">
					<input class="enter_email_input required email newslesoon" name="universe_email" value="<?php echo esc_html__( 'Join newsletter', 'universe' ); ?>" onfocus="if( this.value == '<?php echo esc_html__( 'Join newsletter', 'universe' ); ?>') { this.value = ''; }" onblur="if ( this.value == '') { this.value = '<?php echo esc_html__( 'Join newsletter', 'universe' ); ?>'; }" type="text">
					<button class="sk-mail"></button>
					<div class="universe_newsletter_status"></div>
				</form>
			</div>
			<div class="container">
				<div class="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php if ( !empty( $universe->cfg['cs_logo'] ) ): ?>
							<img src="<?php echo esc_url( $universe->cfg['cs_logo'] ); ?>" alt="" />
						<?php else: ?>
							<img src="<?php echo UNIVERSE_THEME_URI; ?>/assets/images/logo-white.png" alt="" />
						<?php endif ?>
					</a>
				</div>
				<div class="intro-text">
					<div class="intro-text-content">
						<div class="title">
							<?php
								if ( !empty( $universe->cfg['cs_title'] ) ) {
									echo wp_kses_post( $universe->cfg['cs_title'] );
								} else {
									echo wp_kses( __("<h2>Launching.</h2><h3>Soon.</h3>", 'universe' ), array( 'h2' => array(), 'h3' => array() ) );
								}
							?>
						</div>
						<?php
							if ( !empty( $universe->cfg['cs_description'] ) ) {
								echo wp_kses_post( $universe->cfg['cs_description'] );
							} else {
								echo wp_kses( __("<span>. Stay tuned here .</span><span>. We are up & working soon .</span>", 'universe' ), array( 'span' => array() ) );
							}
						?>
					</div>
				</div>
			</div>
			<div class="content-bottom">

				<div class="countdown" id="countdown1">
					<div class="item">
						<span class="days"></span>
						<div class="smalltext"><?php esc_html_e( 'Days', 'universe' ); ?></div>
					</div>
					<div class="item">
						<span class="hours"></span>
						<div class="smalltext"><?php esc_html_e( 'Hours', 'universe' ); ?></div>
					</div>
					<div class="item">
						<span class="minutes"></span>
						<div class="smalltext"><?php esc_html_e( 'Minutes', 'universe' ); ?></div>
					</div>
					<div class="item">
						<span class="seconds"></span>
						<div class="smalltext"><?php esc_html_e( 'Seconds', 'universe' ); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="after-content">
		<div class="coming-content">
			<div class="newlettter">
				<form data-url="<?php echo admin_url( 'admin-ajax.php?t='. time() ); ?>" method="POST" action="" _lpchecked="1">
					<input class="enter_email_input required email newslesoon" name="universe_email" value="<?php echo esc_html__( 'Join newsletter', 'universe' ); ?>" onfocus="if( this.value == '<?php echo esc_html__( 'Join newsletter', 'universe' ); ?>') { this.value = ''; }" onblur="if ( this.value == '') { this.value = '<?php echo esc_html__( 'Join newsletter', 'universe' ); ?>'; }" type="text">
					<button class="sk-mail"></button>
					<div class="universe_newsletter_status"></div>
				</form>
			</div>
			<div class="container">
				<div class="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php if ( !empty( $universe->cfg['cs_logo'] ) ): ?>
							<img src="<?php echo esc_url( $universe->cfg['cs_logo'] ); ?>" alt="" />
						<?php else: ?>
							<img src="<?php echo UNIVERSE_THEME_URI; ?>/assets/images/logo-white.png" alt="" />
						<?php endif ?>
					</a>
				</div>
				<div class="intro-text">
					<div class="intro-text-content">
						<div class="title">
							<?php
								if ( !empty( $universe->cfg['cs_title'] ) ) {
									echo wp_kses_post( $universe->cfg['cs_title'] );
								} else {
									echo wp_kses( __("<h2>Launching.</h2><h3>Soon.</h3>", 'universe' ), array( 'h2' => array(), 'h3' => array() ) );
								}
							?>
						</div>
						<?php
							if ( !empty( $universe->cfg['cs_description'] ) ) {
								echo wp_kses_post( $universe->cfg['cs_description'] );
							} else {
								echo wp_kses( __("<span>. Stay tuned here .</span><span>. We are up & working soon .</span>", 'universe' ), array( 'span' => array() ) );
							}
						?>
					</div>
				</div>
			</div>
			<div class="content-bottom">
				<div class="countdown" id="countdown2">
					<div class="item">
						<span class="days"></span>
						<div class="smalltext"><?php esc_html_e( 'Days', 'universe' ); ?></div>
					</div>
					<div class="item">
						<span class="hours"></span>
						<div class="smalltext"><?php esc_html_e( 'Hours', 'universe' ); ?></div>
					</div>
					<div class="item">
						<span class="minutes"></span>
						<div class="smalltext"><?php esc_html_e( 'Minutes', 'universe' ); ?></div>
					</div>
					<div class="item">
						<span class="seconds"></span>
						<div class="smalltext"><?php esc_html_e( 'Seconds', 'universe' ); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<!-- ######### JS FILES ######### -->
<script type="text/javascript" src="<?php echo UNIVERSE_THEME_URI; ?>/assets/js/comingsoon/jquery.countTo.js"></script>
<script type="text/javascript" src="<?php echo UNIVERSE_THEME_URI; ?>/assets/js/comingsoon/jquery.matchHeight.js"></script>
<script type="text/javascript" src="<?php echo UNIVERSE_THEME_URI; ?>/assets/js/comingsoon/countup.js"></script>
<script>
	// Full width************
	jQuery(document).ready(function($) {
		var windowWidth = $(window).width(),
		haft_win = windowWidth/10;
		$('.before-content').width(4.35*haft_win);
		$('.coming-content').width(windowWidth);
	});

	function getTimeRemaining(endtime) {
		var t = Date.parse(endtime) - Date.parse(new Date());
		var seconds = Math.floor((t / 1000) % 60);
		var minutes = Math.floor((t / 1000 / 60) % 60);
		var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
		var days = Math.floor(t / (1000 * 60 * 60 * 24));
		return {
			'total': t,
			'days': days,
			'hours': hours,
			'minutes': minutes,
			'seconds': seconds
		};
	}

	function initializeClock(id, endtime) {
		var clock = document.getElementById(id);
		var daysSpan = clock.querySelector('.days');
		var hoursSpan = clock.querySelector('.hours');
		var minutesSpan = clock.querySelector('.minutes');
		var secondsSpan = clock.querySelector('.seconds');

		function updateClock() {
			var t = getTimeRemaining(endtime);

			daysSpan.innerHTML = t.days;
			hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
			minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
			secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

			if (t.total <= 0) {
			  clearInterval(timeinterval);
			}
		}

		updateClock();
			var timeinterval = setInterval(updateClock, 1000);
		}
	<?php if ( isset( $universe->cfg['cs_timedown'] ) && !empty( $universe->cfg['cs_timedown'] ) ) { ?>
		var deadline = new Date( '<?php echo universe::esc_js($universe->cfg['cs_timedown']) ?>' );
	<?php } else { ?>
		var deadline = new Date(Date.parse(new Date()) + 17 * 24 * 60 * 60 * 1000);
	<?php } ?>
	initializeClock('countdown1', deadline);
	initializeClock('countdown2', deadline);
</script>