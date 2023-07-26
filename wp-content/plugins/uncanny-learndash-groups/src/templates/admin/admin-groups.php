<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">
	<div class="ulgm">

		<?php

		// Add admin header and tabs
		$tab_active = 'uncanny-groups';
		require Utilities::get_template( 'admin/admin-header.php' );

		?>

		<div class="ulgm-admin-content">
			<div class="uo-ulgm-admin form-table group-management-form">
				<input type="hidden" id="action" name="action"
					   value="save-general-settings"/>

				<!-- Messages -->
				<?php
				if ( '' !== AdminPage::$ulgm_management_admin_page['text']['message'] ) {
					?>
					<div class="updated ulgm-custom-message"
						 style="margin-bottom: 20px">
						<p><?php echo AdminPage::$ulgm_management_admin_page['text']['message']; ?></p>
					</div>
					<?php
				}
				?>

				<!-- Page Setup -->
				<div class="uo-admin-section uo-admin-section--first">
					<div class="uo-admin-header">
						<div
							class="uo-admin-title"><?php echo AdminPage::$ulgm_management_admin_page['text']['page_settings']; ?></div>
					</div>
					<div class="uo-admin-block">
						<div class="uo-admin-form">

							<!-- Group Management Page -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_group_management_page']; ?>
									<a href="https://www.uncannyowl.com/knowledge-base/set-group-management-pages/?utm_medium=uo_groups&utm_campaign=settings_page"
									   target="_blank"><span
											class="dashicons dashicons-info"></span></a>
								</div>
								<?php
								wp_dropdown_pages(
									array(
										'selected'         => AdminPage::$ulgm_management_admin_page['ulgm_group_management_page'],
										'name'             => 'ulgm_group_management_page',
										'id'               => 'ulgm_group_management_page',
										'class'            => 'uo-admin-select',
										'show_option_none' => __( 'None', 'uncanny-learndash-groups' ),
									)
								);
								?>
								<div
									class="uo-admin-description"><?php _e( 'Choose a page that includes the [uo_groups] shortcode.', 'uncanny-learndash-groups' ); ?></div>
							</div>
							<p><?php echo __( 'Any page configured below will enable the corresponding button on the Group Management page. To disable any feature, simply set the corresponding dropdown to <i>None</i>.', 'uncanny-learndash-groups' ); ?></p>
							<!-- Buy Courses Page -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_group_buy_courses_page']; ?>
									<a href="https://www.uncannyowl.com/knowledge-base/group-leader-purchase-experience/?utm_medium=uo_groups&utm_campaign=settings_page"
									   target="_blank"><span
											class="dashicons dashicons-info"></span></a>
								</div>
								<?php
								wp_dropdown_pages(
									array(
										'selected'         => AdminPage::$ulgm_management_admin_page['ulgm_group_buy_courses_page'],
										'name'             => 'ulgm_group_buy_courses_page',
										'id'               => 'ulgm_group_buy_courses_page',
										'class'            => 'uo-admin-select',
										'show_option_none' => __( 'None', 'uncanny-learndash-groups' ),
									)
								);
								?>
								<div
									class="uo-admin-description"><?php _e( 'Choose a page that includes the [uo_groups_buy_courses] shortcode.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Group Report Page -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_group_report_page']; ?>
									<a href="https://www.uncannyowl.com/knowledge-base/group-leader-reports/?utm_medium=uo_groups&utm_campaign=settings_page#The_Group_Course_Report"
									   target="_blank"><span
											class="dashicons dashicons-info"></span></a>
								</div>
								<?php
								wp_dropdown_pages(
									array(
										'selected'         => AdminPage::$ulgm_management_admin_page['ulgm_group_report_page'],
										'name'             => 'ulgm_group_report_page',
										'id'               => 'ulgm_group_report_page',
										'class'            => 'uo-admin-select',
										'show_option_none' => __( 'None', 'uncanny-learndash-groups' ),
									)
								);
								?>
								<div
									class="uo-admin-description"><?php _e( 'Choose a page that includes the [uo_groups_course_report] shortcode.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Group Quiz Report Page -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_group_quiz_report_page']; ?>
									<a href="https://www.uncannyowl.com/knowledge-base/group-leader-reports/?utm_medium=uo_groups&utm_campaign=settings_page#The_Group_Quiz_Report"
									   target="_blank"><span
											class="dashicons dashicons-info"></span></a>
								</div>
								<?php
								wp_dropdown_pages(
									array(
										'selected'         => AdminPage::$ulgm_management_admin_page['ulgm_group_quiz_report_page'],
										'name'             => 'ulgm_group_quiz_report_page',
										'id'               => 'ulgm_group_quiz_report_page',
										'class'            => 'uo-admin-select',
										'show_option_none' => __( 'None', 'uncanny-learndash-groups' ),
									)
								);
								?>
								<div
									class="uo-admin-description"><?php _e( 'Choose a page that includes the [uo_groups_quiz_report] shortcode.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Group Progress Management Report Page -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_group_manage_progress_page']; ?>
									<a href="https://www.uncannyowl.com/knowledge-base/manage-learner-progress/?utm_medium=uo_groups&utm_campaign=settings_page"
									   target="_blank"><span
											class="dashicons dashicons-info"></span></a>
								</div>
								<?php
								wp_dropdown_pages(
									array(
										'selected'         => AdminPage::$ulgm_management_admin_page['ulgm_group_manage_progress_page'],
										'name'             => 'ulgm_group_manage_progress_page',
										'id'               => 'ulgm_group_manage_progress_page',
										'class'            => 'uo-admin-select',
										'show_option_none' => __( 'None', 'uncanny-learndash-groups' ),
									)
								);
								?>
								<div
									class="uo-admin-description"><?php _e( 'Choose a page that includes the [uo_groups_manage_progress] shortcode.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Allow Group Leaders to Manage Progress -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="allow_group_leaders_to_manage_progress"
										   id="allow_group_leaders_to_manage_progress"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'allow_group_leaders_to_manage_progress', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['allow_group_leaders_to_manage_progress']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php _e( 'Group Leaders are allowed to view individual student progress when a page exists with the [uo_groups_manage_progress] shortcode. Check this box to also allow Group Leaders to override progress for learners.', 'uncanny-learndash-groups' ); ?>
								</div>
							</div>

							<!-- Group Assignment Report Page -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_group_assignment_report_page']; ?>
									<a href="https://www.uncannyowl.com/knowledge-base/front-end-learndash-assignment-management/?utm_medium=uo_groups&utm_campaign=settings_page"
									   target="_blank"><span
											class="dashicons dashicons-info"></span></a>
								</div>
								<?php
								wp_dropdown_pages(
									array(
										'selected'         => AdminPage::$ulgm_management_admin_page['ulgm_group_assignment_report_page'],
										'name'             => 'ulgm_group_assignment_report_page',
										'id'               => 'ulgm_group_assignment_report_page',
										'class'            => 'uo-admin-select',
										'show_option_none' => __( 'None', 'uncanny-learndash-groups' ),
									)
								);
								?>
								<div
									class="uo-admin-description"><?php _e( 'Choose a page that includes the [uo_groups_assignments] shortcode.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Group Essay Report Page -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_group_essay_report_page']; ?>
									<a href="https://www.uncannyowl.com/knowledge-base/front-end-essay-question-management/?utm_medium=uo_groups&utm_campaign=settings_page"
									   target="_blank"><span
											class="dashicons dashicons-info"></span></a>
								</div>
								<?php
								wp_dropdown_pages(
									array(
										'selected'         => AdminPage::$ulgm_management_admin_page['ulgm_group_essay_report_page'],
										'name'             => 'ulgm_group_essay_report_page',
										'id'               => 'ulgm_group_essay_report_page',
										'class'            => 'uo-admin-select',
										'show_option_none' => __( 'None', 'uncanny-learndash-groups' ),
									)
								);
								?>
								<div
									class="uo-admin-description"><?php _e( 'Choose a page that includes the [uo_groups_essays] shortcode.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Submit -->
							<div class="uo-admin-field uo-admin-extra-space">
								<button id="btn-save_template"
										class="uo-admin-form-submit submit-group-management-form"
										data-end-point="save_general_settings"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
							</div>
						</div>
					</div>
				</div>

				<!-- General Settings  -->
				<div class="uo-admin-section">
					<div class="uo-admin-header">
						<div
							class="uo-admin-title"><?php echo AdminPage::$ulgm_management_admin_page['text']['page_settings_general']; ?></div>
					</div>
					<div class="uo-admin-block">
						<div class="uo-admin-form">

							<!-- Per Seat Text - Singular -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_per_seat_text']; ?></div>

								<input class="uo-admin-input" type="text"
									   name="ulgm_per_seat_text"
									   id="ulgm_per_seat_text"
									   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_per_seat_text']; ?>"/>
							</div>

							<!-- Per Seat Text - Plural -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_per_seat_text_plural']; ?></div>

								<input class="uo-admin-input" type="text"
									   name="ulgm_per_seat_text_plural"
									   id="ulgm_per_seat_text_plural"
									   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_per_seat_text_plural']; ?>"/>
							</div>

							<!-- Separator -->
							<div class="uo-admin-field">
								<div class="uo-admin-separator"></div>
							</div>

							<!-- Main Color -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_main_color']; ?></div>

								<input type="text" name="ulgm_main_color"
									   id="ulgm_main_color"
									   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_main_color']; ?>"/>
							</div>

							<!-- Main Color -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_font_color']; ?></div>

								<input type="text" name="ulgm_font_color"
									   id="ulgm_font_color"
									   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_font_color']; ?>"/>
								<div
									class="uo-admin-description"><?php echo __( 'The color of the text that appears on top of accented areas.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Separator -->
							<div class="uo-admin-field">
								<div class="uo-admin-separator"></div>
							</div>

							<!-- Allow Group Leaders to remove students at any time -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="allow_to_remove_users_anytime"
										   id="allow_to_remove_users_anytime"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'allow_to_remove_users_anytime', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['allow_to_remove_users_anytime']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php echo sprintf( __( 'By default, Group Leaders cannot remove students from groups once the students have started %s activities. Checking this box ignores this rule and allows Group Leaders to remove students at any time to free up seats.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ); ?>
								</div>
							</div>

							<!-- Do not restore seat if a user is removed from a group -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="do_not_restore_seat_if_user_is_removed"
										   id="do_not_restore_seat_if_user_is_removed"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'do_not_restore_seat_if_user_is_removed', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['do_not_restore_seat_if_user_is_removed']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php echo __( 'By default, when a student is removed from a group by a Group Leader, the available seat count is increased. Checking this box will not free up a seat when a student with "Completed" status is removed and will decrease the total seat count of a group. This setting works in conjunction with "Allow Group Leaders to remove students at any time".', 'uncanny-learndash-groups' ); ?>
								</div>
							</div>

							<!-- Group Leaders don't use seats -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="group_leaders_dont_use_seats"
										   id="group_leaders_dont_use_seats"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'group_leaders_dont_use_seats', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['group_leaders_dont_use_seats']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php echo sprintf( __( 'Group Leaders added to the group as Group Members by a Group Leader have %1$s access but will not use seats. Be careful with this option, as Group Leaders can exploit this to give unlimited students %1$s access. Changing this setting will not affect current seat counts.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ); ?>
								</div>
							</div>

							<!-- Do not add Group Leaders as Group Members -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="do_not_add_group_leader_as_member"
										   id="do_not_add_group_leader_as_member"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'do_not_add_group_leader_as_member', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['do_not_add_group_leader_as_member']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php _e( 'Newly created Group Leaders will not be automatically added to groups as members. Group Leaders can still be manually added as Members after creation.', 'uncanny-learndash-groups' ); ?>
								</div>
							</div>

							<!-- Allow Group Leaders to edit users -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="allow_group_leader_edit_users"
										   id="allow_group_leader_edit_users"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'allow_group_leader_edit_users', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['allow_group_leader_edit_users']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php _e( 'Group Leaders will be able to edit the First name, Last name and Email of Group Members. Be careful with this option, as Group Leaders could exploit this to reassign seats to new users without purchasing an additional seat.', 'uncanny-learndash-groups' ); ?>
								</div>
							</div>

							<!-- Allow Group Leaders to change username -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="allow_group_leader_change_username"
										   id="allow_group_leader_change_username"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'allow_group_leader_change_username', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['allow_group_leader_change_username']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php _e( 'Enables a Username field in the edit user dialog.', 'uncanny-learndash-groups' ); ?>
								</div>
							</div>

							<!-- Use Progress Report instead of Course Report for user status -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="use_progress_report_instead_course"
										   id="use_progress_report_instead_course"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'use_progress_report_instead_course', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['use_progress_report_instead_course']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php _e( "Clicking a user's status on the Group Management page will go to the Progress Report for that user instead of the Course Report, providing drill-down capability with additional detail. Ensure the Progress Report Page is set before enabling this option.", 'uncanny-learndash-groups' ); ?>
								</div>
							</div>

							<!-- Show "basic" (non-upgraded) groups in front end with access to reports only -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="show_basic_groups_in_frontend"
										   id="show_basic_groups_in_frontend"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'show_basic_groups_in_frontend', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['show_basic_groups_in_frontend']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php _e( 'Groups that do not have seat management enabled will be visible in the front end Group Management interface.  These groups will have access to reporting functions only; it will not be possible for Group Leaders to add members to/remove members from these groups.', 'uncanny-learndash-groups' ); ?>
								</div>
							</div>

							<!-- Submit -->
							<div class="uo-admin-field uo-admin-extra-space">
								<button id="btn-save_template"
										class="uo-admin-form-submit submit-group-management-form"
										data-end-point="save_general_settings"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
							</div>
						</div>
					</div>
				</div>

				<!-- LearnDash Settings  -->
				<div class="uo-admin-section">
					<div class="uo-admin-header">
						<div
							class="uo-admin-title"><?php echo AdminPage::$ulgm_management_admin_page['text']['page_settings_learndash']; ?></div>
					</div>
					<div class="uo-admin-block">
						<div class="uo-admin-form">

							<!-- Use LearnDash's "legacy" course progress data -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="use_legacy_course_progress"
										   id="use_legacy_course_progress"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'use_legacy_course_progress', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['use_legacy_course_progress']; ?>
									</span>
								</label>

								<div class="uo-admin-description">
									<?php _e( "Use LearnDash's legacy data to display users' course progress on the Group Management page. Enable this if you're seeing discrepancies between course progress data in Uncanny Groups and other LearnDash reports.", 'uncanny-learndash-groups' ); ?>
								</div>
							</div>

							<!-- Hide Courses / Users column on LearnDash Groups page -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="ld_hide_courses_users_column"
										   id="ld_hide_courses_users_column"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'ld_hide_courses_users_column', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['ld_hide_courses_users_column']; ?>
								</span>
								</label>

								<div class="uo-admin-description">
									<?php printf( __( 'Hide the column that lists the number of users, %s and Group Leaders in a group on the LearnDash LMS > Groups page. Enabling this setting will improve load times on sites with a lot of groups and users.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?>
								</div>
							</div>
							<!-- Hierarchy settings with Learndash settings -->
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										<?php
										if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && ! learndash_is_groups_hierarchical_enabled() ) {
											?>
											disabled="disabled" <?php } ?>
										   name="ld_hierarchy_settings_child_groups"
										   id="ld_hierarchy_settings_child_groups"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['ld_hierarchy_settings_child_groups_in_reports']; ?>
								</span>
								</label>

								<div class="uo-admin-description">
									<?php if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && ! learndash_is_groups_hierarchical_enabled() ) { ?>
										<?php echo __( 'Group Hierarchy support is currently disabled. <a href="https://www.learndash.com/support/docs/users-groups/groups/global-group-settings/#global_course_management_display_settings" target="_blank"> Click here</a> for more information.', 'uncanny-learndash-groups' ); ?>
									<?php } else { ?>
										<?php echo __( 'For all reports in Uncanny Groups, show records for the selected group and child groups beneath it. Note that this can affect performance and applies to all reports.', 'uncanny-learndash-groups' ); ?>
									<?php } ?>
								</div>
							</div>

							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="ld_pool_seats_in_hierarchy"
										   id="ld_pool_seats_in_hierarchy"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'ld_pool_seats_in_hierarchy', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['ld_pool_seats_in_hierarchy']; ?>
									   </span>
								</label>

								<div class="uo-admin-description">
									<?php printf( esc_html__( 'When enabled, Group Leaders can enable or disable pooled seats from the top-level %1$s for each hierarchy via a checkbox on the Group Management page. Pooled seats will be shared across all %2$s in the hierarchy.', 'uncanny-learndash-groups' ), strtolower( \LearnDash_Custom_Label::get_label( 'group' ) ), strtolower( \LearnDash_Custom_Label::get_label( 'groups' ) ) ); ?>
								</div>
							</div>
							<div class="uo-admin-field">
								<label class="uo-checkbox">
									<input type="checkbox"
										   name="ld_pool_seats_all_groups"
										   id="ld_pool_seats_all_groups"
										   value="yes"
										<?php
										if ( 'yes' === get_option( 'ld_pool_seats_all_groups', 'no' ) ) {
											echo 'checked="checked"';
										}
										?>
									/>
									<div class="uo-checkmark"></div>
									<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['ld_pool_seats_all_groups']; ?>
									   </span>
								</label>
								<div class="uo-admin-description">
									<?php printf( __( "When checked, every %1\$s that is in a hierarchy has its seats pooled and managed from the hierarchy's top-level parent. Note: This setting overrides individual %1\$s settings and removes the %2\$s.", 'uncanny-learndash-groups' ), strtolower( \LearnDash_Custom_Label::get_label( 'group' ) ), '<i>' . AdminPage::$ulgm_management_admin_page['text']['ld_pool_seats_in_hierarchy'] . '</i>' ); ?>
								</div>
							</div>

							<!-- Submit -->
							<div class="uo-admin-field uo-admin-extra-space">
								<button id="btn-save_template"
										class="uo-admin-form-submit submit-group-management-form"
										data-end-point="save_general_settings">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?>
								</button>
							</div>
						</div>
					</div>
				</div>


				<?php if ( Utilities::if_woocommerce_active() ) { ?>
					<!-- eCommerce Settings  -->
					<div class="uo-admin-section">
						<div class="uo-admin-header">
							<div
								class="uo-admin-title"><?php echo AdminPage::$ulgm_management_admin_page['text']['page_settings_ecommerce']; ?></div>
						</div>
						<div class="uo-admin-block">
							<div class="uo-admin-form">

								<!-- Automatically include Group Course products in Group License purchases -->
								<div class="uo-admin-field">
									<label class="uo-checkbox">
										<input type="checkbox"
											   name="add_courses_as_part_of_license"
											   id="add_courses_as_part_of_license"
											   value="yes"
											<?php
											if ( 'yes' === get_option( 'add_courses_as_part_of_license', 'no' ) ) {
												echo 'checked="checked"';
											}
											?>
										/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['add_courses_as_part_of_license']; ?>
									</span>
									</label>

									<div class="uo-admin-description">
										<?php echo sprintf( __( 'Group %1$s products will be included as $0 line items when a user purchases a Group License.  Check this if you want to be able to trigger integrations with other plugins (e.g. Follow-Up Emails, WP Fusion, Memberium) based on the %2$s included in a license.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?>
									</div>
								</div>

								<!-- Learndash Groups as woocommerce products -->
								<div class="uo-admin-field">
									<label class="uo-checkbox">
										<input type="checkbox"
											   name="add_groups_as_woo_products"
											   id="add_groups_as_woo_products"
											   value="yes"
											<?php
											if ( 'yes' === get_option( 'add_groups_as_woo_products', 'no' ) ) {
												echo 'checked="checked"';
											}
											?>
										/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['add_groups_as_woo_products']; ?>
									</span>
									</label>

									<div class="uo-admin-description">
										<?php _e( 'LearnDash Groups will be displayed on the Edit Product screen. Selecting one or more groups and saving the product will associate that product with the selected group(s). Any user that purchases that product will then automatically be added to the associated group(s).', 'uncanny-learndash-groups' ); ?>
									</div>
								</div>

								<!-- Autocomplete group license orders -->
								<div class="uo-admin-field">
									<label class="uo-checkbox">
										<input type="checkbox"
											   name="ulgm_complete_group_license_orders"
											   id="ulgm_complete_group_license_orders"
											   value="yes"
											<?php
											if ( 'yes' === get_option( 'ulgm_complete_group_license_orders', 'no' ) ) {
												echo 'checked="checked"';
											}
											?>
										/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_complete_group_license_orders']; ?>
									</span>
									</label>

									<div class="uo-admin-description">
										<?php _e( 'Automatically change the status of orders that include Uncanny Groups products to Completed.', 'uncanny-learndash-groups' ); ?>
									</div>
								</div>

								<!-- Assign license product to a Woo category -->
								<div class="uo-admin-field">
									<div
										class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_group_license_product_cat']; ?></div>
									<?php
									$terms    = get_terms(
										array(
											'taxonomy'   => 'product_cat',
											'hide_empty' => false,
										)
									);
									$default  = get_option( 'ulgm_group_license_product_cat', '' );
									$selected = 'selected="selected"';
									$j        = 0;
									?>
									<select name="ulgm_group_license_product_cat"
											class="uo-admin-select"
											id="ulgm_group_license_product_cat">
										<option value="">None</option>
										<?php foreach ( $terms as $term ) { ?>
											<option
												class="level-<?php echo $j; ?>" <?php echo absint( $default ) === absint( $term->term_id ) ? $selected : ''; ?>
												value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
											<?php
											$j ++;
										}
										?>
									</select>
									<div
										class="uo-admin-description"><?php echo sprintf( __( 'Automatically assign dynamically-generated group license products (created when users purchase group access from the Buy %1$s page) to a category. Useful for integrating dynamic licenses with other functionality (e.g. create coupons that apply only to dynamic licenses).', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?></div>
								</div>

								<!-- Woocommerce Add To Cart Message -->
								<div class="uo-admin-field">
									<div
										class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_add_to_cart_message']; ?></div>

									<input type="text"
										   name="ulgm_add_to_cart_message"
										   id="ulgm_add_to_cart_message"
										   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_add_to_cart_message']; ?>"/>
									<div
										class="uo-admin-description"><?php _e( 'Customize the message shown to users when adding seats to their group. Use {{product}} to insert product name.', 'uncanny-learndash-groups' ); ?></div>
								</div>
								<!-- Submit -->
								<div class="uo-admin-field uo-admin-extra-space">
									<button id="btn-save_template"
											class="uo-admin-form-submit submit-group-management-form"
											data-end-point="save_general_settings"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php if ( Utilities::if_woocommerce_active() && Utilities::if_woocommerce_subscription_active() ) { ?>
					<!-- eCommerce Settings  -->
					<div class="uo-admin-section">
						<div class="uo-admin-header">
							<div
								class="uo-admin-title"><?php echo AdminPage::$ulgm_management_admin_page['text']['page_settings_woocommerce_subscriptions']; ?></div>
						</div>
						<div class="uo-admin-block">
							<div class="uo-admin-form">

								<!-- Allow group leaders to buy additional seats for subsription based groups -->
								<div class="uo-admin-field">
									<label class="uo-checkbox">
										<input type="checkbox"
											   name="woo_subscription_allow_additional_seats"
											   id="woo_subscription_allow_additional_seats"
											   value="yes"
											<?php
											if ( 'yes' === get_option( 'woo_subscription_allow_additional_seats', 'no' ) ) {
												echo 'checked="checked"';
											}
											?>
										/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
										<?php echo AdminPage::$ulgm_management_admin_page['text']['woo_subscription_allow_additional_seats']; ?>
									</span>
									</label>

									<div class="uo-admin-description">
										<?php echo sprintf( __( 'Allow group leaders of subscription-based groups to buy additional seats. Completing an order with additional seats will create a new subscription in addition to the existing subscription. We strongly recommend enabling Synchronise renewals under WooCommerce > Settings > Subscriptions > Synchronization to synchronize renewals across multiple subscriptions. %s', 'uncanny-learndash-groups' ), '<a style="text-decoration:none;" href="https://www.uncannyowl.com/knowledge-base/adding-seats-after-subscription-based-group-purchase" target="_blank"><span class="dashicons dashicons-external"></span></a>' ); ?>
									</div>
								</div>
								<!-- Woocommerce Add To Cart Message -->
								<div class="uo-admin-field">
									<div
										class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['woo_subscription_allow_additional_seats_learn_more_link']; ?></div>

									<input type="text"
										   name="woo_subscription_allow_additional_seats_learn_more_link"
										   id="woo_subscription_allow_additional_seats_learn_more_link"
										   value="<?php echo get_option( 'woo_subscription_allow_additional_seats_learn_more_link', '' ); ?>"/>
									<div
										class="uo-admin-description"><?php _e( 'To direct Group Leaders to a page that explains the process to add seats to a subscription-based group, enter the URL of that instructional page. Leave the field blank to hide the Learn More button.', 'uncanny-learndash-groups' ); ?></div>
								</div>
								<!-- Submit -->
								<div class="uo-admin-field uo-admin-extra-space">
									<button id="btn-save_template"
											class="uo-admin-form-submit submit-group-management-form"
											data-end-point="save_general_settings"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<!-- Upgrade LearnDash Groups -->
				<div class="uo-admin-section">
					<div class="uo-admin-header">
						<div
							class="uo-admin-title"><?php _e( 'Terms & Conditions', 'uncanny-learndash-groups' ); ?></div>
					</div>
					<div class="uo-admin-block">
						<div class="uo-admin-form">
							<!-- Start process description -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-description"><?php _e( 'To enable a Terms & Conditions checkbox on the Group Registration Form, enter text for the checkbox below. Does not apply if you are using a Gravity Form or Theme My Login for group registration.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Start process button -->
							<div class="uo-admin-field uo-admin-extra-space">
								<?php wp_editor( AdminPage::$ulgm_management_admin_page['ulgm_term_condition'], 'ulgm_term_condition', array( 'editor_height' => '120' ) ); ?>
							</div>

							<!-- Submit -->
							<div class="uo-admin-field uo-admin-extra-space">
								<button id="btn-save_template"
										class="uo-admin-form-submit submit-group-management-form"
										data-end-point="save_general_settings"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
							</div>
						</div>
					</div>
				</div>
				<!-- Upgrade LearnDash Groups -->
				<div class="uo-admin-section" id="Upgrade-LearnDash-Groups">
					<div class="uo-admin-header">
						<div
							class="uo-admin-title"><?php _e( 'Upgrade LearnDash Groups', 'uncanny-learndash-groups' ); ?></div>
					</div>
					<div class="uo-admin-block">
						<div class="uo-admin-form">
							<!-- Start process description -->
							<div class="uo-admin-field">
								<div
									class="uo-admin-description"><?php _e( 'All LearnDash Groups can be upgraded to include support for seat management by clicking the button below.', 'uncanny-learndash-groups' ); ?></div>
								<div
									class="uo-admin-description"><?php _e( '<span style="color:red">Warning:</span> Upgraded groups cannot be reverted to basic groups.  If you want to enable seat management on some groups only, use the Upgrade Group button on the Edit Group page.', 'uncanny-learndash-groups' ); ?></div>
							</div>

							<!-- Start process button -->
							<div class="uo-admin-field uo-admin-extra-space">
								<a href="<?php echo admin_url( 'admin.php?page=uncanny-groups&migrate=yes&wpnonce=' . wp_create_nonce( 'ulgm' ) ); ?>"
								   onclick="return confirm('<?php _e( 'This action will update all of your existing LearnDash Groups to work with the Uncanny Groups for LearnDash front end group management interface. This process will happen in the background and cannot be reversed. Do you want to continue?', 'uncanny-learndash-groups' ); ?>')"
								   class="uo-admin-form-submit">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['ulgm_migrate_old_groups_to_new']; ?>
								</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>
