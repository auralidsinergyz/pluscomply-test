<?php
namespace uncanny_learndash_groups;

$license_exists = SharedFunctions::is_license_exists( GroupManagementInterface::$ulgm_current_managed_group_id );
?>

	<!-- Courses -->
	<?php if ( $group_courses_section ) { ?>
	<div class="uo-row uo-groups-section uo-groups-group-courses">
		<?php
		$learn_dash_labels     = new \LearnDash_Custom_Label();
		$course_label_singular = $learn_dash_labels::get_label( 'course' );
		$course_label_plural   = $learn_dash_labels::get_label( 'courses' );

		$group_id = GroupManagementInterface::$ulgm_current_managed_group_id;

		// For group hierarchy support
		$is_hierarchy_setting_enabled = false;
		if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
			if ( ulgm_filter_has_var( 'show-children' ) ) {
				$is_hierarchy_setting_enabled     = true;
				$learndash_group_enrolled_courses = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id, true );
			}
		}

		if ( $is_hierarchy_setting_enabled ) {
			$post_vars = array(
				'post_type'      => 'sfwd-courses',
				'post__in'       => $learndash_group_enrolled_courses,
				'orderby'        => 'post_title',
				'order'          => 'ASC',
				'posts_per_page' => 99999,
				'nopaging'       => true,
			);
		} else {
			$post_vars = array(
				'post_type'      => 'sfwd-courses',
				'meta_key'       => 'learndash_group_enrolled_' . $group_id,
				'orderby'        => 'post_title',
				'order'          => 'ASC',
				'posts_per_page' => 99999,
				'nopaging'       => true,
			);
		}

		$post_vars = apply_filters( 'ulgm_group_courses_list_get_posts_vars', $post_vars, $group_id );

		$courses = array();

		$the_query = new \WP_Query( $post_vars );

		// The Loop
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$courses[] = array(
					'link'  => get_the_permalink(),
					'title' => get_the_title(),
				);
			}
		}

		// Reset Query
		wp_reset_postdata();

		$courses           = apply_filters( 'ulgm_group_courses_list_courses', $courses, $post_vars, $group_id );
		$number_of_courses = count( $courses );
		?>
		<div class="uo-row uo-header">
			<h2 class="group-courses-heading uo-looks-like-h3">
				<?php printf( _x( 'Group %s', '%s is the "courses" LearnDash label', 'uncanny-learndash-groups' ), $course_label_plural ); ?>
			</h2>
			<p class="uo-header-subtitle">
				<span>
					<?php
					if ( $number_of_courses == 1 ) {
						printf( _x( '1 %s', '%1$s is the "course" LearnDash label', 'uncanny-learndash-groups' ), $course_label_singular );
					} else {
						printf( _x( '%1$s %2$s', '%1$s is a number, %2$s is the "courses" LearnDash label', 'uncanny-learndash-groups' ), $number_of_courses, $course_label_plural );
					}
					?>
				</span>
				<?php
				if ( $add_courses_button && $license_exists ) {
					if ( 'yes' === get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, '_uo_custom_group', true ) && Utilities::if_woocommerce_active() && empty( get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, SharedFunctions::$code_group_downgraded, true ) ) ) {
						?>
						<!-- Add Courses button -->
						<button class="ulgm-link uo-btn uo-inline uo-btn--small"
								onclick="location.href='<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['buy_courses_link']; ?>'"
								type="button">
							<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['buy_courses']; ?>
						</button>
						<?php
					}
				}
				?>
			</p>
		</div>

		<div class="uo-row uo-groups-group-courses-list">
			<div class="group-courses-list">
				<ul class="list-of-courses uo-list">
					<?php
					if ( ! empty( $courses ) && is_array( $courses ) ) {
						foreach ( $courses as $course ) {
							?>
							<li><a href="<?php echo $course['link']; ?>"><?php echo $course['title']; ?></a>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
<?php } ?>


	<!-- Enrolled Users -->
	<?php
	$populate_management_features = GroupManagementInterface::$populate_management_features;
	if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
		if ( ulgm_filter_has_var( 'show-children' ) ) {
			$populate_management_features = false;
		}
	}
	$populate_management_features = apply_filters( 'ulgm_populate_management_features', $populate_management_features, GroupManagementInterface::$ulgm_current_managed_group_id );
	if ( $populate_management_features ) {
		?>
<div class="uo-row uo-groups-section uo-groups-enrolled-users">
	<div class="uo-row uo-header">
		<h2 class="group-table-heading uo-looks-like-h3"><?php _e( 'Enrolled users', 'uncanny-learndash-groups' ); ?></h2>
		<div class="uo-row uo-header-subtitle">
			<?php
			if ( $seats_quantity ) {
				?>
				<div class="uo-header-subtitle">
					<div class="group-management-total uo-inline">
						<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['x_users_enrolled']; ?>
					</div>
				</div>
				<?php if ( true === apply_filters( 'ulgm_show_remaining_total_seats', true, GroupManagementInterface::$ulgm_current_managed_group_id ) ) { ?>
				<span class="uo-subtitle-of-h3">
						<span class="group-management-total uo-inline">
							<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['x_seats_remaining']; ?>
							/ <?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['x_total_seats']; ?>
						</span>
					</span>
					<?php
				}
			}

			if ( $add_seats_button && $license_exists ) {
				if ( Utilities::if_woocommerce_active() && empty( get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, SharedFunctions::$code_group_downgraded, true ) ) && empty( get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, '_ulgm_is_upgraded', true ) ) && empty( get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, '_ulgm_is_custom_group_created', true ) ) ) {
					$add_seats_link = GroupManagementInterface::$ulgm_management_shortcode['text']['add_seats_link'];
					$lm             = '';
					$show_button    = true;
					if ( Utilities::if_woocommerce_subscription_active() && 'yes' === get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, '_uo_subscription_group', true ) ) {
						if ( class_exists( '\uncanny_learndash_groups\WoocommerceLicenseSubscription' ) && WoocommerceLicenseSubscription::is_woo_subscriptions_add_seats_enabled() ) {
							$learn_more_link = apply_filters( 'ulgm_subscription_additional_seats_learn_more_link', get_option( 'woo_subscription_allow_additional_seats_learn_more_link', '' ), array() );
							$learn_more      = apply_filters( 'ulgm_subscription_additional_seats_learn_more', '<span class="dashicons dashicons-external"></span>' );
							if ( ! empty( $learn_more_link ) ) {
								$lm = sprintf( '<a href="%s" target="_blank">%s</a>', $learn_more_link, $learn_more );
							}
							$add_seats_link = "$add_seats_link&is_subscription_group=yes";
						} else {
							$show_button = false;
						}
					}
					if ( $show_button ) {
						?>
						<!-- Add Seats -->
						<button class="ulgm-link uo-btn uo-inline uo-btn--small"
								onclick="location.href='<?php echo esc_url_raw( $add_seats_link ); ?>'"
								type="button">
							<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['add_seats']; ?>
						</button>
						<?php
					}
					if ( true === apply_filters( 'ulgm_subscription_show_additional_seats_learn_more', true, GroupManagementInterface::$ulgm_current_managed_group_id ) ) {
						echo $lm;
					}
				}
			}
			?>
		</div>
	</div>
		<?php } ?>
	<!-- Actions & Search -->
	<div class="user-table-actions">
		<div class="uo-row uo-groups-actions">
			<div class="group-management-buttons">
				<div class="group-management-buttons__left">
					<?php if ( $populate_management_features ) { ?>

						<?php
						if ( $remove_user_button ) {
							?>

							<!-- Remove Users -->
							<div id="group-management-enrolled-users-table-remove-users"
								 class="group-user-management-buttons uo-hidden mrg-right">
								<button class="ulgm-modal-link uo-btn uo-left"
										data-modal-id="#group-management-remove-user"
										rel="modal:open"><?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['remove_users']; ?></button>
							</div>

							<div id="group-management-remove-user" class="group-management-modal" style="display:none;">
								<div class="uo-groups">
									<div class="group-management-form">
										<div class="group-management-rest-message"></div>

										<input type="hidden" id="removing-users" name="removing-users" value="">

										<div class="uo-row">
											<?php
											if ( 'yes' === get_option( 'do_not_restore_seat_if_user_is_removed', 'no' ) && 'yes' === get_option( 'allow_to_remove_users_anytime', 'no' ) ) {
												echo sprintf(
													'%s<p class="small-text" style="font-size:0.8em"><br /><strong>Warning:</strong> %s</p>',
													sprintf( __( 'Are you sure you want to remove %s user(s)?', 'uncanny-learndash-groups' ), '<span class="amount-users"></span>' ),
													sprintf( __( 'Removing a student with "%s" status will not free up a seat in this group.', 'uncanny-learndash-groups' ), __( 'Completed', 'uncanny-learndash-groups' ) )
												);
											} else {
												echo sprintf(
													__( 'Are you sure you want to remove %s user(s)?', 'uncanny-learndash-groups' ),
													'<span class="amount-users"></span>'
												);
											}
											?>
										</div>

										<input type="hidden" name="action" id="remove-users" value="remove-users">
										<input type="hidden" name="group-id" id="group-id"
											   value="<?php echo GroupManagementInterface::$ulgm_current_managed_group_id; ?>">

										<div class="uo-row-footer">
											<div style="margin-bottom: 15px" class="uo-modal-spinner"></div>
											<button class="uo-btn submit-group-management-form"
													data-end-point="remove_users">
												<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['remove_users']; ?>
											</button>
										</div>
									</div>
								</div>
							</div>

							<?php
						}

						?>

					<?php } ?>

					<?php if ( $populate_management_features ) { ?>

						<div id="group-management-enrolled-users-table-send-password" class="uo-hidden mrg-right">
							<button class="uo-btn uo-left ulgm-modal-link"
									data-modal-id="#group-management-enrolled-users-table-send-password-modal">
								<?php _e( 'Send password reset', 'uncanny-learndash-groups' ); ?>
							</button>
						</div>

						<div id="group-management-enrolled-users-table-send-password-modal"
							 class="group-management-modal"
							 style="display:none;">
							<div class="uo-groups">
								<div class="group-management-form">
									<div class="group-management-rest-message"></div>

									<input type="hidden" id="group-management-enrolled-users-table-send-password-field"
										   name="send-password-users" value="">

									<div class="uo-row">
										<?php
										echo sprintf(
											__( 'Are you sure you want to send a password reset email to %s user(s)?', 'uncanny-learndash-groups' ),
											'<span class="amount-users"></span>'
										);
										?>
									</div>

									<input type="hidden" name="action" id="send-password-reset"
										   value="send-password-reset">
									<input type="hidden" name="group-id" id="group-id"
										   value="<?php echo GroupManagementInterface::$ulgm_current_managed_group_id; ?>">

									<div class="uo-row-footer">
										<div style="margin-bottom: 15px" class="uo-modal-spinner"></div>
										<button class="uo-btn submit-group-management-form"
												data-end-point="send-password-reset">
											<?php _e( 'Send password reset', 'uncanny-learndash-groups' ); ?>
										</button>
									</div>
								</div>
							</div>
						</div>

					<?php } ?>

					<?php

					if (
							// The CSV export button is visible
							$csv_export_button
							||
							// The Excel export button is visible
							$excel_export_button
							// or at least one of the actions (Add user, upload users,
							// email users, etc.) is enabled
							|| (
								// Not showing children groups
									$populate_management_features && (
										// One of the actions (Add user, upload users, email users, etc.) is enabled
											$add_user_button || $upload_users_button || $add_group_email_button || $download_keys_button || $csv_export_button
									)
							)
					) {
						?>
						<!-- Add Users -->
						<?php
						$users_menu_items = array();
						if ( $populate_management_features ) {

							if ( $add_user_button && absint( ulgm()->group_management->seat->remaining_seats( GroupManagementInterface::$ulgm_current_managed_group_id ) ) ) {
								$users_menu_items['add_user_button'] = '<button class="ulgm-modal-link uo-btn uo-left"
													data-modal-id="#group-management-add-user" rel="modal:open">
												' . __( 'Add one', 'uncanny-learndash-groups' ) . '
											</button>';

								$users_menu_items['add_user_multiple_button'] = '<button class="ulgm-modal-link uo-btn uo-left" id="uo-open-bulk-add">
												' . __( 'Add multiple', 'uncanny-learndash-groups' ) . '
											</button>';
							}

							if ( $upload_users_button ) {
								if ( absint( ulgm()->group_management->seat->remaining_seats( GroupManagementInterface::$ulgm_current_managed_group_id ) ) ) {
									$users_menu_items['upload_users_button'] = '<button class="ulgm-modal-link uo-btn uo-left"
													data-modal-id="#group-management-upload-users"
													rel="modal:open">' . GroupManagementInterface::$ulgm_management_shortcode['text']['upload_users'] . '</button>';
								}
							}

							if ( $add_group_email_button ) {
								$users_menu_items['add_group_email_button'] = '<button class="ulgm-modal-link uo-btn uo-left"
												data-modal-id="#group-management-email-users"
												rel="modal:open">' . GroupManagementInterface::$ulgm_management_shortcode['text']['email_users'] . '</button>';
							}

							if ( $download_keys_button ) {
								$users_menu_items['users_menu_items'] = '<div class="group-management-form">
											<input type="hidden" name="action" id="download" value="download">
											<input type="hidden" name="group-id" id="group-id"
												   value="' . GroupManagementInterface::$ulgm_current_managed_group_id . '">
											<button class="submit-group-management-form uo-btn uo-right"
													data-end-point="download_keys_csv">' . __( 'Download keys', 'uncanny-learndash-groups' ) . '</button>
										</div>';
							}
						}

						if ( $csv_export_button ) {
							$users_menu_items['csv_export_button'] = '<button class="ulgm-modal-link uo-btn uo-left" id="group-management-users-export-csv">
											' . __( 'Export CSV', 'uncanny-learndash-groups' ) . '
										</button>';
						}
						if ( $excel_export_button ) {
							$users_menu_items['excel_export_button'] = '<button class="ulgm-modal-link uo-btn uo-left" id="group-management-users-export-excel">
											' . __( 'Export Excel', 'uncanny-learndash-groups' ) . '
										</button>';
						}

						$users_menu_items = apply_filters(
							'uo_users_menu_items',
							$users_menu_items,
							array(
								'csv_export_button'      => $csv_export_button,
								'excel_export_button'    => $excel_export_button,
								'populate_management_features' => $populate_management_features,
								'add_user_button'        => $add_user_button,
								'upload_users_button'    => $upload_users_button,
								'add_group_email_button' => $add_group_email_button,
								'download_keys_button'   => $download_keys_button,
							)
						);
						?>

						<?php if ( is_array( $users_menu_items ) && ! empty( $users_menu_items ) ) { ?>
							<div class="uo-groups-list-of-btns uo-inline-block uo-left" id="uo_add_users_button"
								 data-id="add-users">
								<div class="uo-btn uo-groups-list-of-btns-main" data-direction="<?php echo is_rtl() ? 'left' : 'right'; ?>"
									 data-id="add-users">
									<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['add_user']; ?>
								</div>
								<div class="uo-groups-list">
									<?php
									foreach ( $users_menu_items as $item_index => $item_output ) {
										echo $item_output;
									}
									?>
								</div>
							</div>
						<?php } ?>
					<?php } ?>

					<?php if ( $populate_management_features ) { ?>

						<?php
						if ( $add_user_button ) {
							$checked  = apply_filters( 'ulgm_add_invite_checked', true );
							$selected = $checked ? ' checked="checked"' : '';
							?>
							<!-- Add Users Modal box -->
							<div id="group-management-add-user" class="group-management-modal" style="display:none;">

								<div class="uo-groups">
									<div class="group-management-form">
										<form id="group-management-add-user-frm" action="return false;">
											<div class="group-management-rest-message"></div>

											<div class="uo-row">
												<div>
													<label class="uo-radio">
														<input class="uo-radio-input bb-custom-check" <?php echo $selected; ?>
															   type="radio" name="action"
															   id="add_invite" value="add-invite">
														<span class="uo-radio-checkmark"></span>
													</label>

													<label for="add_invite"><?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['add_invite_user']; ?></label>
												</div>
												<div>
													<?php if ( $key_options ) { ?>

														<label class="uo-radio">
															<input class="uo-radio-input bb-custom-check" type="radio"
																   name="action"
																   id="send_enrollment" value="send-enrollment">
															<span class="uo-radio-checkmark"></span>
														</label>

														<label for="send_enrollment"><?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['send_enrollment_key']; ?></label>

													<?php } ?>
												</div>
											</div>

											<input type="hidden" name="group-id" id="group-id"
												   value="<?php echo GroupManagementInterface::$ulgm_current_managed_group_id; ?>">

											<?php do_action( 'ulgm_before_add_invite_form_fields', GroupManagementInterface::$ulgm_current_managed_group_id, $this ); ?>

											<div class="uo-row">
												<label for="first-name">
													<div class="uo-row__title">
														<?php _e( 'First name*', 'uncanny-learndash-groups' ); ?>
													</div>
												</label>
												<input class="uo-input"
														<?php if ( true === apply_filters( 'ulgm_add_invite_user_first_name_required', true, GroupManagementInterface::$ulgm_current_managed_group_id ) ) { ?>
															required="required"
														<?php } ?>
													   type="text"
													   name="first_name"
													   id="first-name"
													   value="">
											</div>

											<div class="uo-row">
												<label for="last-name">
													<div class="uo-row__title">
														<?php _e( 'Last name*', 'uncanny-learndash-groups' ); ?>
													</div>
												</label>
												<input class="uo-input"
														<?php if ( true === apply_filters( 'ulgm_add_invite_user_last_name_required', true, GroupManagementInterface::$ulgm_current_managed_group_id ) ) { ?>
															required="required"
														<?php } ?>
													   type="text" name="last_name"
													   id="last-name"
													   value="">
											</div>

											<div class="uo-row">
												<label for="email">
													<div class="uo-row__title">
														<?php _e( 'Email*', 'uncanny-learndash-groups' ); ?>
													</div>
												</label>
												<input class="uo-input" required="required" type="text" name="email"
													   id="email" value="">
											</div>

											<div class="uo-row" id="uo_add_user_password">
												<label for="password">
													<div class="uo-row__title">
														<?php _e( 'Password', 'uncanny-learndash-groups' ); ?>
													</div>
												</label>
												<input class="uo-input" type="text" name="uo_password" id="password"
													   value="">

												<div class="uo-row__description">
													<?php _e( 'Set an optional password for new users. If no password is entered, a random password will be generated. If the user already exists, the user\'s password will not be changed and this value will be ignored.', 'uncanny-learndash-groups' ); ?>
												</div>
											</div>
											<?php do_action( 'ulgm_after_add_invite_form_fields', GroupManagementInterface::$ulgm_current_managed_group_id, $this ); ?>
											<div class="uo-row-footer">
												<div style="margin-bottom: 15px" class="uo-modal-spinner"></div>

												<button class="uo-btn submit-group-management-form"
														data-end-point="add_user"><?php _e( 'Add user', 'uncanny-learndash-groups' ); ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>

							<?php
						}
						?>

						<div id="group-management-edit-user" class="group-management-modal" style="display:none;">
							<div class="uo-groups">
								<div class="group-management-form">
									<div class="group-management-rest-message"></div>
									<form id="group-management-edit-user-frm" action="return false;">
										<input type="hidden" name="group-id" id="group-id"
											   value="<?php echo GroupManagementInterface::$ulgm_current_managed_group_id; ?>">
										<input type="hidden" name="edit-user-id" id="edit-user-id" value="">
										<input type="hidden" name="action" id="edit-action" value="edit-user">

										<div class="uo-row">
											<label for="edit-first-name">
												<div class="uo-row__title">
													<?php _e( 'First name*', 'uncanny-learndash-groups' ); ?>
												</div>
											</label>
											<input class="uo-input" required="required" type="text" name="first_name"
												   id="edit-first-name" value="">
										</div>

										<div class="uo-row">
											<label for="edit-last-name">
												<div class="uo-row__title">
													<?php _e( 'Last name*', 'uncanny-learndash-groups' ); ?>
												</div>
											</label>
											<input class="uo-input" required="required" type="text" name="last_name"
												   id="edit-last-name" value="">
										</div>

										<div class="uo-row">
											<label for="edit-email">
												<div class="uo-row__title">
													<?php _e( 'Email*', 'uncanny-learndash-groups' ); ?>
												</div>
											</label>
											<input class="uo-input" required="required" type="text" name="email"
												   id="edit-email" value="">
										</div>

										<?php if ( 'yes' === get_option( 'allow_group_leader_change_username', 'no' ) ) { ?>
											<div class="uo-row">
												<label for="edit-username">
													<div class="uo-row__title">
														<?php _e( 'Username*', 'uncanny-learndash-groups' ); ?>
													</div>
												</label>
												<input class="uo-input" required="required" type="text" name="username"
													   id="edit-username" value="">
											</div>
										<?php } ?>

										<div class="uo-row-footer">
											<div style="margin-bottom: 15px" class="uo-modal-spinner"></div>

											<button class="uo-btn submit-group-management-form"
													data-end-point="edit_user"><?php _e( 'Update User', 'uncanny-learndash-groups' ); ?></button>
										</div>
									</form>
								</div>
							</div>
						</div>

					<?php } ?>
				</div>

				<div class="group-management-buttons__right">
					<!-- List of actions to show on small screens show-in-mobile-only -->

					<?php

					if (
							( $progress_report_button && ! empty( SharedFunctions::get_group_report_page_id() ) ) ||
							( $quiz_report_button && ! empty( SharedFunctions::get_group_quiz_report_page_id() ) ) ||
							( $progress_management_report_button && ! empty( SharedFunctions::get_group_manage_progress_report_page_id() ) ) ||
							( $assignment_button && ! empty( SharedFunctions::$group_assignment_report_page_id ) ) ||
							( $essay_button && ! empty( SharedFunctions::$group_essay_report_page_id ) )
					) {
						?>
						<div class="uo-groups-list-of-btns uo-inline-block uo-right"
							 data-id="general-actions">
							<div class="uo-btn uo-right uo-groups-list-of-btns-main" id="uo-groups-action-users"
								 data-direction="<?php echo is_rtl() ? 'right' : 'left'; ?>" data-id="general-actions">
								<?php _e( 'Reports', 'uncanny-learndash-groups' ); ?>
							</div>
							<div class="uo-groups-list">

								<?php

								if ( $progress_report_button && ! empty( SharedFunctions::get_group_report_page_id() ) ) {
									if ( ! empty( SharedFunctions::get_group_report_page_id() ) ) {
										?>
										<!-- Progress Report -->
										<button class="ulgm-link uo-btn"
												onclick="location.href='<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_progress_link']; ?>'"
												type="button">
											<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_progress']; ?>
										</button>
										<?php
									}
								}

								if ( $quiz_report_button && ! empty( SharedFunctions::get_group_quiz_report_page_id() ) ) {
									if ( ! empty( SharedFunctions::get_group_quiz_report_page_id() ) ) {
										?>

										<button class="ulgm-link uo-btn uo-right"
												onclick="location.href='<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_quiz_progress_link']; ?>'"
												type="button">
											<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_quiz_progress']; ?>
										</button>

										<?php
									}
								}

								if ( $progress_management_report_button && ! empty( SharedFunctions::get_group_manage_progress_report_page_id() ) ) {
									?>

									<!-- Manage Progress Report -->
									<button class="ulgm-link uo-btn uo-right"
											onclick="location.href='<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_progress_management_link']; ?>'"
											type="button">
										<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_progress_management']; ?>
									</button>

									<?php

								}

								?>

								<?php

								if ( $assignment_button && ! empty( SharedFunctions::$group_assignment_report_page_id ) ) {

									?>
									<button class="ulgm-link uo-btn uo-right"
											onclick="location.href='<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_assignment_link']; ?>'"
											type="button">
										<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_assignment_page']; ?>
									</button>

									<?php

								}

								?>

								<?php

								if ( $essay_button && ! empty( SharedFunctions::$group_essay_report_page_id ) ) {

									?>
									<button class="ulgm-link uo-btn uo-right"
											onclick="location.href='<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_essay_link']; ?>'"
											type="button">
										<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_essay_page']; ?>
									</button>

									<?php

								}

								?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php

	if ( $upload_users_button ) {

		?>
	<!-- Upload Users Modal box -->
	<div id="group-management-upload-users" class="group-management-modal"
		 style="display:none;">
		<div class="uo-groups">
			<div class="group-management-form">
				<div class="group-management-rest-message"></div>

				<div id="group-management-title">
					<?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['modal_x_seats_remaining']; ?>
				</div>

				<div class="uo-row">
					<div>
						<label class="uo-radio">
							<input class="uo-radio-input bb-custom-check" type="radio" name="action"
								   id="add-invite" value="add-invite">
							<span class="uo-radio-checkmark"></span>
						</label>

						<label for="add-invite"><?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['add_invite_users']; ?></label>
					</div>
					<div>
						<?php if ( $key_options ) { ?>

							<label class="uo-radio">
								<input class="uo-radio-input bb-custom-check" type="radio" name="action"
									   id="send-enrollment" value="send-enrollment">
								<span class="uo-radio-checkmark"></span>
							</label>

							<label for="send-enrollment"><?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['send_enrollment_keys']; ?></label>

						<?php } ?>
					</div>
					<div>
						<?php if ( $key_options ) { ?>

							<label class="uo-checkbox">
								<input class="uo-checkbox-input bb-custom-check" type="checkbox"
									   name="not-send-emails" id="not-send-emails"
									   value="not-send-emails">
								<span class="uo-checkbox-checkmark"></span>
							</label>

							<label for="not-send-emails"><?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['do_not_send_emails']; ?></label>

						<?php } ?>
					</div>
				</div>

				<input type="hidden" name="group-id" id="group-id"
					   value="<?php echo GroupManagementInterface::$ulgm_current_managed_group_id; ?>">

				<div class="uo-row">
					<label for="csv-file">
						<div class="uo-row__title">
							<?php _e( 'Upload CSV File', 'uncanny-learndash-groups' ); ?>
						</div>
						<div class="uo-row__description" id="uo-import-user-note">
							<?php printf( __( 'Note: Empty values in the %s column will generate a random password for new users. The passwords of existing users will not be changed.', 'uncanny-learndash-groups' ), '<em>user_pass</em>' ); ?>
						</div>
					</label>
					<input type="file" name="csv-file" id="csv-file" style="border: 0"/>
					<input type="hidden" name="csv-text" id="csv-text" value=""/>
					<div>
						<a href="<?php echo Utilities::get_sample( 'group_management_user_upload.csv' ); ?>"><?php _e( 'Download a sample .csv file', 'uncanny-learndash-groups' ); ?></a>
					</div>
				</div>

				<div class="uo-row-footer">
					<div style="margin-bottom: 15px" class="uo-modal-spinner"></div>

					<button class="uo-btn submit-group-management-form"
							data-end-point="upload_users"><?php _e( 'Add users', 'uncanny-learndash-groups' ); ?></button>
				</div>
			</div>
		</div>
	</div>

		<?php

	}

	?>

	<?php

	if ( $add_group_email_button ) {

		?>

		<!-- Upload Users Modal box -->
		<div id="group-management-email-users"
			class="group-management-modal group-management-email-users"
			style="display:none;"
		>
			<div class="uo-groups">
				<div class="group-management-form">
					<div class="group-management-rest-message"></div>

					<input
						id="group_email_group_id"
						type="hidden"
						name="group_email_group_id"
						value="<?php echo esc_attr( GroupManagementInterface::$ulgm_current_managed_group_id ); ?>"
					/>

					<input
						id="group_email_nonce"
						type="hidden"
						name="group_email_nonce"
						value="<?php echo wp_create_nonce( 'group_email_nonce_' . GroupManagementInterface::$ulgm_current_managed_group_id . '_' . $user->ID ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
					/>

					<!-- Email Group feature below the Group Table (on the Group Leader page) -->
					<div class="uo-row">
						<label for="group_email_fromname">
							<strong><?php esc_html_e( 'From name:', 'uncanny-learndash-groups' ); ?></strong>
							<?php echo esc_attr( SharedFunctions::ulgm_group_management_email_users_from_name( GroupManagementInterface::$ulgm_current_managed_group_id ) ); ?>
						</label>
					</div>

					<div class="uo-row">
						<label for="group_email_fromemail">
							<strong><?php esc_html_e( 'From email:', 'uncanny-learndash-groups' ); ?></strong>
							<?php echo esc_attr( SharedFunctions::ulgm_group_management_email_users_from_email( GroupManagementInterface::$ulgm_current_managed_group_id ) ); ?>
						</label>
					</div>

					<div class="uo-row">
						<label for="group_email_replytoemail">
							<div class="uo-row__title">
								<?php esc_html_e( 'Reply-to email:', 'uncanny-learndash-groups' ); ?>
							</div>
						</label>
						<select id="ulgm-management-reply-to-email" name="reply_to_email">
							<?php
							$group_leaders = array_merge( array( $user->ID ), learndash_get_groups_administrator_ids( GroupManagementInterface::$ulgm_current_managed_group_id ) );
							$group_leaders = array_unique( $group_leaders );
							foreach ( $group_leaders as $group_leader ) {
								$g_user   = get_user_by( 'ID', $group_leader );
								$selected = $group_leader === $user->ID ? ' selected="selected' : '';
								?>
								<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $g_user->user_email ); ?>">
								<?php echo sprintf( '%s %s [%s]', $g_user->first_name, $g_user->last_name, $g_user->user_email ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>

					<div class="uo-row">
						<label for="group_email_replytoemail">
							<div class="uo-row__title">
								<?php printf( esc_html__( 'Group %1$s', 'uncanny-learndash-groups' ), strtolower( \LearnDash_Custom_Label::get_label( 'courses' ) ) ); ?>
							</div>
						</label>
						<?php
						$courses = LearndashFunctionOverrides::learndash_group_enrolled_courses( GroupManagementInterface::$ulgm_current_managed_group_id )
						?>
						<select id="ulgm-management-send-email-courses" name="group_email_courses[]" multiple="multiple">
							<option value="-1" selected="selected">
								<?php printf( esc_html__( 'Any %1$s', 'uncanny-learndash-groups' ), strtolower( \LearnDash_Custom_Label::get_label( 'course' ) ) ); ?>
							</option>

							<?php
							foreach ( $courses as $course ) {
								?>
								<option value="<?php echo absint( $course ); ?>"><?php echo get_the_title( $course ); ?></option>
								<?php
							}
							?>
						</select>

						<div class="uo-row__description">
							<?php

								printf(
									/* translators: 1. LearnDash "course" label; 2. Learndash "group" label */
									esc_html__( 'Choose "Any %1$s" to send emails to students that have any %2$s %1$s that matches the selected status.', 'uncanny-learndash-groups' ),
									'course',
									'group'
								);

							?>

							<a
								href="https://www.uncannyowl.com/knowledge-base/group-management-page/#emails-group-members"
								target="_blank"
							>
								<?php esc_html_e( 'Learn more', 'uncanny-learndash-groups' ); ?>
							</a>
						</div>
					</div>

					<div class="uo-row">
						<label for="group_email_replytoemail">
							<div class="uo-row__title">
							<?php printf( esc_html__( '%1$s status', 'uncanny-learndash-groups' ), 'Course' ); ?>
							</div>
						</label>

						<select
							name="group_email_status[]"
							multiple="multiple"
						> 
							<?php

							foreach ( $send_email_course_statuses as $option_value => $option_text ) {
								?>
								 

								<option value="<?php echo esc_attr( $option_value ); ?>" selected="selected">
									<?php echo esc_html( $option_text ); ?>
								</option>

								<?php
							}

							?>
						</select>

					</div>

					<div class="uo-row">
						<label for="group_email_sub">
							<div class="uo-row__title">
								<?php esc_html_e( 'Email subject:', 'uncanny-learndash-groups' ); ?>
							</div>
						</label>

						<input
							id="group_email_sub"
							name="group_email_sub"
							class="uo-input group_email_sub"
						/>
					</div>

					<div class="uo-row">
						<label for="text">
							<div class="uo-row__title">
								<?php esc_html_e( 'Email message:', 'uncanny-learndash-groups' ); ?>
							</div>
						</label>

						<div class="group_email_text">
							<textarea name="group_email_text" id="group_email_text"></textarea>
						</div>
					</div>

					<div class="uo-row-footer">
						<div style="margin-bottom: 15px" class="uo-modal-spinner"></div>

						<button
							id="email_group"
							class="uo-btn submit-group-management-form"
							type="button"
							data-end-point="email_users"
						>
							<?php esc_html_e( 'Send', 'uncanny-learndash-groups' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>

		<?php
	}
