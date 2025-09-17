<?php
/**
 * (c) king-theme.com
 */

	$universe = universe::globe();

?>

<article <?php post_class(); ?>>

	<div class="entry-content blog_postcontent">

		<?php

			the_content( esc_html__( 'Read More &#187;', 'universe' ) );
			wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'universe' ) . '</span>', 'after' => '</div>' ) );

		?>

	</div>

</article>