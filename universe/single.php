<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$universe = universe::globe();

get_header();

?>

	<?php
		if ( isset( $kctheme->cfg['blog_breadcrumb'] ) && $kctheme->cfg['blog_breadcrumb']['_file_'] != '') {
			universe_incl_core( $universe->cfg['blog_breadcrumb'] );
		} else {
			universe::path( 'breadcrumb' );
		}
	?>

	<div id="main-pages">
		<div class="container">
			<div class="row">
				<div class="col-md-9 content_left blog-single-post">
					<div class="main-content single-post">

						<?php while ( have_posts() ) : the_post(); ?>

							<div <?php post_class(); ?>>

								<div class="entry-header">
									<?php

										global $more,$post;

										if( !isset($universe->cfg['excerptImage']) ){
											$universe->cfg['excerptImage'] = 1;
										}

										if( $universe->cfg['excerptImage'] == 1 && !is_page() && !is_single() )
										{

											$img = $universe->get_featured_image( $post, true );
											if( strpos( $img , 'default.') === false && $img != null  && !is_single() )
											{
												if( strpos( $img , 'youtube') !== false )
												{
													echo '<div class="video_frame">';
													echo '<ifr'.'ame src="'.$img.'"></ifra'.'me>';
													echo '</div>';

												} else {

													echo '<div class="imgframe animated fadeInUp">';
														if( $more == false )
															echo '<a title="Continue read: '.get_the_title().'" href="'.get_permalink(get_the_ID()).'">';
																echo '<img alt="'.get_the_title().'" class="featured-image" src="'.$img.'" />';
														if( $more == false )
															echo '</a>';
													echo '</div>';

												}
											}

										}

										if( $universe->cfg['excerptImage'] == 1 && is_single() ){

											$img = $universe->get_featured_image( $post, false );

											if( strpos( $img , 'default.') === false && $img != null )
											{
												if( $more == false ){
													echo '<a title="Continue read: '.get_the_title().'" href="'.get_permalink(get_the_ID()).'">';
												}
														echo '<img alt="'.get_the_title().'" class="featured-image animated eff-fadeInUp" src="'.$img.'" />';
												if( $more == false ){
													echo '</a>';
												}
											}
										}

									?>

									<?php if ( is_sticky() ) : ?>
										<h3 class="entry-format">
												<?php esc_html_e( 'Featured', 'universe' ); ?>
										</h3>
									<?php endif; ?>

								</div>

								<div class="entry-content">

										<?php if ( !empty( $universe->cfg['showMeta'] ) && $universe->cfg['showMeta'] ==1 ): ?>
											<?php if ( !empty( $universe->cfg['showDateMeta'] ) && $universe->cfg['showDateMeta'] == 1 ): ?>

												<div class="box-date">
													<span>.<?php echo get_the_date('d'); ?></span>
													<em><?php echo get_the_date('M'); ?></em>
												</div>

											<?php endif ?>
										<?php endif ?>

										<div class="title animated ext-fadeInUp">
											<h2><?php the_title(); ?></h2>

											<?php if ( !empty( $universe->cfg['showMeta'] ) && $universe->cfg['showMeta'] ==1 ): ?>
												<ul class="post-meta">

													<?php if ( !empty( $universe->cfg['showAuthorMeta'] ) && $universe->cfg['showAuthorMeta'] == 1 ): ?>
														<li><?php echo esc_html__( 'Written by:', 'universe' ); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a></li>
													<?php endif ?>

													<?php if ( !empty( $universe->cfg['showCommentsMeta'] ) && $universe->cfg['showCommentsMeta'] == 1 ): ?>
														<li><a href="<?php comments_link(); ?>"><?php echo get_comments_number( $post->ID ); ?></a> <?php echo esc_html__( 'Comments', 'universe' ); ?></li>
													<?php endif ?>

													<?php if ( !empty( $universe->cfg['showShareBox'] ) && $universe->cfg['showShareBox'] == 1 ): ?>
														<li class="single-post-share">
															<a href="#"><?php echo esc_html__( 'Share', 'universe' ); ?></a>
															<?php if( !empty($universe->cfg['showShareBox']) && $universe->cfg['showShareBox'] == 1 ){ ?>
																<?php $escaped_link = get_the_permalink(); ?>

																<ul>
																	<?php if( $universe->cfg['showShareFacebook'] == 1 ){ ?>
																		<li>
																			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $escaped_link ); ?>">
																				&nbsp;<i class="fa fa-facebook fa-lg"></i>&nbsp;
																			</a>
																		</li>
																	<?php } ?>
																	<?php if( $universe->cfg['showShareTwitter'] == 1 ){ ?>
																		<li>
																			<a href="https://twitter.com/home?status=<?php echo esc_url( $escaped_link ); ?>">
																				<i class="fa fa-twitter fa-lg"></i>
																			</a>
																		</li>
																	<?php } ?>
																	<?php if( $universe->cfg['showShareGoogle'] == 1 ){ ?>
																		<li>
																			<a href="https://plus.google.com/share?url=<?php echo esc_url( $escaped_link ); ?>">
																				<i class="fa fa-google-plus fa-lg"></i>
																			</a>
																		</li>
																	<?php } ?>
																	<?php if( $universe->cfg['showSharePinterest'] == 1 ){ ?>
																		<li>
																			<a href="https://pinterest.com/pin/create/button/?url=&amp;media=&amp;description=<?php echo esc_url( $escaped_link ); ?>">
																				<i class="fa fa-pinterest fa-lg"></i>
																			</a>
																		</li>
																	<?php } ?>
																	<?php if( $universe->cfg['showShareLinkedin'] == 1 ){ ?>
																		<li>
																			<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=&amp;title=&amp;summary=&amp;source=<?php echo esc_url( $escaped_link ); ?>">
																				<i class="fa fa-linkedin fa-lg"></i>
																			</a>
																		</li>
																	<?php } ?>
																</ul>

															<?php } ?>
														</li>
													<?php endif ?>

													<?php if( !empty( $universe->cfg['showCateMeta'] ) && $universe->cfg['showCateMeta'] == 1 && has_category() ) :  ?>
														<li><?php the_category(', '); ?></li>
													<?php endif; ?>
												</ul>
											<?php endif ?>

										</div>


									<div class="desc">

										<?php
											if( ( get_option('rss_use_excerpt') == 1 || is_search() ) && !is_single() && !is_page() ){

												the_excerpt();
												echo '<a href="'.get_the_permalink().'">'.esc_html__('Read More &#187;','universe').'</a>';

											} else {
												the_content( esc_html__( 'Read More &#187;', 'universe' ) );
											}

											wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'universe' ) . '</span>', 'after' => '</div>' ) );
										?>

									</div>

									<?php if ( !empty( $universe->cfg['showMeta'] ) && $universe->cfg['showMeta'] ==1 ): ?>
										<?php if ( !empty( $universe->cfg['showTagsMeta'] ) && $universe->cfg['showTagsMeta'] ): ?>
											<?php
											$posttags = get_the_tags();
											if ($posttags) {
											?>
											<div class="post-tag">
												<h4><?php echo esc_html__( 'Tags', 'universe' ); ?></h4>
												<?php
													
													$tag_val = array();
													foreach($posttags as $tag) {
														$tag_link = get_tag_link( $tag->term_id );
														$tag_val[] = '<a href="'.$tag_link.'">'.$tag->name.'</a>';
													}

													echo implode(' ', $tag_val);
												?>
											</div>
											<?php } ?>
										<?php endif ?>
									<?php endif ?>

								</div>

							</div>

							<?php if( !empty($universe->cfg['navArticle']) && $universe->cfg['navArticle'] == 1 ): ?>

								<nav id="nav-single">
									<span class="nav-previous"><?php previous_post_link( '%link', '<span class="fa fa-angle-double-left"></span> ' . esc_html__( 'Previous Article', 'universe' ) ); ?></span>
									<span class="nav-next"><?php next_post_link( '%link', esc_html__( 'Next Article', 'universe' ) . ' <span class="fa fa-angle-double-right"></span>' ); ?></span>
								</nav><!-- #nav-single -->

							<?php endif; ?>
							<?php
								$author_desc = get_the_author_meta( 'description' );
							?>
							<?php if (!empty($universe->cfg['archiveAboutAuthor']) && $universe->cfg['archiveAboutAuthor'] == 1 && !empty($author_desc)){ ?>

								<!--About author-->
								<div class="about-author">
									<h3 class="author-name"><?php echo get_the_author(); ?></h3>
									<figure>
										<?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?>
									</figure>
									<div class="author-content">
										<p><?php the_author_meta( 'description' ); ?></p>
									</div>
								</div>

							<?php } ?>

							<?php
							if( !empty($universe->cfg['archiveRelatedPosts']) && $universe->cfg['archiveRelatedPosts'] == 1 ){
								get_template_part( 'post-related' );
							}

							if( is_single() ){
								comments_template( '', true );
							}

						endwhile; // end of the loop. ?>

					</div>
				</div>

				<div class="col-md-3 right_sidebar">
					<?php get_sidebar( ); ?>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>