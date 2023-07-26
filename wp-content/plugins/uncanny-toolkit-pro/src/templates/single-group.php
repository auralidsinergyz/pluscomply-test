<?php
get_header();
?>
    <style>
        .post-pagination-wrap,
        .post-pagination,
        .post-prev,
        .post-next {
            display: none !important;
        }
    </style>
    <div id="main-content">
        <div id="group-main" class="page type-page status-publish hentry">
            <div class="entry-content">
                <div id="left-area" class="content-area clr">
                    <main id="content" class="site-content clr" role="main">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php
							// lets check for key
							$group_key = crypt( $post->ID, 'uncanny-group' );
							//Fixing $_GET string from . (dot) & space to _ ( underscore )
							$group_key = str_replace( [ ' ', '.', '[', '-' ], '_', $group_key );
							// Display Errors if any...
							$errors = \uncanny_pro_toolkit\LearnDashGroupSignUp::uncanny_group_signup_errors()->get_error_messages();
							
							if ( ! empty( $errors ) ) {
								foreach( $errors as $error ){
									echo '<div class="uncanny_group_signup_form-container">';
									echo $error;
									echo '</div>';
								}
							} else {
								
								if ( ! isset( $_GET[ $group_key ] ) ) {
									?>
                                    <p><?php echo esc_html__( 'This page can only be used by organizations with a valid group ID. The URL used to reach this page is not valid. Please contact your organization to obtain the correct registration URL.', 'uncanny-pro-toolkit' ) ?></p>
									<?php
									if ( current_user_can( 'manage_options' ) ) {
										printf( '<h2>' . __( 'Shown to admins only.', 'uncanny-pro-toolkit' ) . '</h2>' . '<p>' . __( 'The sign up link for this group is:', 'uncanny-pro-toolkit' ) . ' <br /><a href="%1$s" >%1$s</a></p>',
											get_permalink( get_the_ID() ) . '?gid=' . get_the_ID() . '&' . $group_key
										);
									}
								} else {
									?>
                                    <article <?php post_class() ?>>
										<?php
										if ( ! is_user_logged_in() ) {
											if ( ! isset( $_REQUEST['registered'] ) ) {
												
												$show_content = TRUE;
												if ( class_exists( '\uncanny_learndash_groups\SharedFunctions' ) ) {
													$code_group_id = get_post_meta( get_the_ID(), '_ulgm_code_group_id', TRUE );
													
													if ( $code_group_id ) {
														$remaining_seats = \uncanny_learndash_groups\SharedFunctions::remaining_seats( get_the_ID() );
														
														if ( 0 === $remaining_seats ) {
															echo '<div class="uncanny_group_signup_form-container">';
															echo __( 'Sorry, no more seats are available for this group.', 'uncanny-pro-toolkit' );
															echo '</div>';
															
															$show_content = FALSE;
														}
													}
												}
												
												if ( $show_content ) {
													the_content();
												}
												
												$is_gravityform_block = FALSE;
												if ( function_exists( 'has_blocks' ) ) {
													if ( has_blocks( $post->post_content ) ) {
														$blocks = parse_blocks( $post->post_content );
														foreach ( $blocks as $block ) {
															if ( $block['blockName'] === 'gravityforms/block' ) {
																$is_gravityform_block = TRUE;
																break;
															}
														}
													}
												}
												if ( ! has_shortcode( $post->post_content, 'gravityform' ) && ! has_shortcode( $post->post_content, 'theme-my-login' ) && $is_gravityform_block === FALSE ) {
													if ( $show_content ) {
														\uncanny_pro_toolkit\LearnDashGroupSignUp::groups_register_form();
													}
													
												}
												
											} else { ?>
												<?php
												$frontEndLogin = \uncanny_learndash_toolkit\Config::get_settings_value( 'uo_frontendloginplus_needs_verifcation', 'FrontendLoginPlus' );
												if ( ! empty( $frontEndLogin ) && 'on' === $frontEndLogin ) { ?>
                                                    <p><?php echo esc_html__( 'Thank you for registering. Your account needs to be approved by site administrator.', 'uncanny-pro-toolkit' ) ?></p>
												<?php } else { ?>
                                                    <p><?php echo esc_html__( 'Congratulations! You are now registered on this site. You will receive an email shortly with login details.', 'uncanny-pro-toolkit' ) ?></p>
												<?php } ?>
											<?php }
											
										} elseif ( is_user_logged_in() && isset( $_REQUEST['registered'] ) ) { ?>
                                            <p><?php echo esc_html__( 'Congratulations!  You are now registered on this site.', 'uncanny-pro-toolkit' ) ?></p>
											<?php
										} elseif ( is_user_logged_in() && isset( $_REQUEST['joined'] ) ) { ?>
                                            <p><?php
												if( isset( $_REQUEST['msg']) && $_REQUEST['msg'] === '2' ){
													echo esc_html__( 'Congratulations! You have successfully joined the new group and have been removed from the previous group.', 'uncanny-pro-toolkit' );
												}else {
													echo esc_html__( 'Congratulations! You are now a member of this group.', 'uncanny-pro-toolkit' );
												}
												?></p>
										<?php } else {
											$show_content = TRUE;
											$user_id  = get_current_user_id();
											
											$meta     = get_user_meta( $user_id, 'learndash_group_users_' . get_the_ID(), true );
											if ( ! empty( $meta ) ) {
												echo '<div class="uncanny_group_signup_form-container">';
												echo __( 'You are already in this group.', 'uncanny-pro-toolkit' );
												echo '</div>';
												
												$show_content = FALSE;
											}
											
											if ( class_exists( '\uncanny_learndash_groups\SharedFunctions' ) && $show_content ) {
												$code_group_id = get_post_meta( get_the_ID(), '_ulgm_code_group_id', TRUE );
												
												if ( $code_group_id ) {
													$remaining_seats = \uncanny_learndash_groups\SharedFunctions::remaining_seats( get_the_ID() );
													
													if ( 0 === $remaining_seats ) {
														echo '<div class="uncanny_group_signup_form-container">';
														echo __( 'Sorry, no more seats are available for this group.', 'uncanny-pro-toolkit' );
														echo '</div>';
														
														$show_content = FALSE;
													}
												}
											}
											
											if ( $show_content ) {
												echo \uncanny_pro_toolkit\LearnDashGroupSignUp::groups_login_form();
												echo \uncanny_pro_toolkit\LearnDashGroupSignUp::check_group_membership();
											}
										}
										?>
                                    </article><!-- .entry -->
									<?php
								}
							}
						endwhile; ?>
                    </main>
                </div>
                <div id="right-area" class="sidebar">
					<?php
					if ( isset( $_GET[ $group_key ] ) ) {
						echo do_shortcode( '[uo_group_organization]' );
					}
					if ( ! is_user_logged_in() ) {
						echo do_shortcode( '[uo_group_login]' );
					}
					?>
                </div>
            </div>
        </div><!-- .container -->
    </div>
<?php get_footer();