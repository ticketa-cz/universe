<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to king_comment() which is
 * located in the functions.php file.
 *
 */
?>
	<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php esc_html_e( 'This post is password protected. Enter the password to view any comments.', 'universe' ); ?></p>
	</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php if ( have_comments() ) : ?>

		<h3 id="comments-title" class="title-comment">
			<?php
				printf( _n( 'Comment', 'Comment %1$s', get_comments_number(), 'universe' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php esc_html_e( 'Comment navigation', 'universe' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'universe' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'universe' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php
				wp_list_comments( array( 'callback' => 'universe_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text"><?php esc_html_e( 'Comment navigation', 'universe' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'universe' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'universe' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'universe' ); ?></p>
	<?php endif; ?>

	<?php

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$args = array(

			'id_form'           => 'commentform',
			'class_submit'         => 'comment_submit',
			'title_reply'       => esc_html__( 'Write a post', 'universe' ),
			'title_reply_to'    => esc_html__( 'Leave a Reply to %s', 'universe' ),
			'cancel_reply_link' => esc_html__( 'Cancel Reply', 'universe' ),
			'label_submit'      => esc_html__( 'Send Comment', 'universe' ),

			'comment_field' =>  '<div class="form-group"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="Comments"></textarea></div>',

			'must_log_in' => '<p class="must-log-in">'
				. esc_html__( 'You must be', 'universe')
				. '<a href="'. wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) .'">'
				. esc_html__( 'logged in', 'universe' )
				. '</a>'
				. esc_html__( 'to post a comment.', 'universe' )
				. '</p>',

			'logged_in_as' => '<p class="logged-in-as">'
				. esc_html__( 'Logged in as', 'universe' )
				. ' <a href="'. admin_url( 'profile.php' ) .'">'. $user_identity .'</a>'
				. ' <a href="'. wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) .'" title="Log out of this account">'
				. esc_html__( 'Log out?', 'universe' )
				. '</a>'
				. '</p>',

			'comment_notes_before' => '',

			'comment_notes_after' => '',

			'fields' => apply_filters( 'comment_form_default_fields', array(

				'author' =>
					'<div class="form-group w2">' .
					'<input id="author" class="comment_input_bg" name="author" type="text" placeholder="Full Name" value="' . esc_attr( $commenter['comment_author'] ) .
					'" size="30"' . $aria_req . ' />'.
					'</div>',

				'email' =>
					'<div class="form-group w2 last">' .
					'<input id="email" class="comment_input_bg" name="email" type="text" placeholder="Email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
					'" size="30"' . $aria_req . ' /></div>'

				)
			),
		);

		comment_form( $args );

	?>

</div>