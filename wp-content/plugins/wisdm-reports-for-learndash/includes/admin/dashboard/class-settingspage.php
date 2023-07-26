<?php

namespace WRLDAdmin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SettingsPage' ) ) {

	/**
	 * Class for showing tabs of WRLD.
	 */
	class SettingsPage {

		public function __construct() {
			wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.css', array(), WRLD_PLUGIN_VERSION );
			wp_enqueue_style( 'wrld_admin_dashboard_settings_select_css', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/multi-select.css', array(), WRLD_PLUGIN_VERSION );
		}

		public static function style_display( $display, $value = 'inline-block', $hide = true ) {
			if ( $display ) {
				return 'display:' . $value . ';';
			} elseif ( $hide ) {
				return 'display:none';
			}
		}

		public static function render() {
			?>
			<div class='wrld-dashboard-page-container'>
				<?php
					self::content_main();
					self::content_sidebar();
				?>
			</div>
			<?php
		}

		public static function content_main() {
			$settings_data = get_option( 'wrld_settings', array() );

			wp_enqueue_style( 'wrld_welcome_modal_style', plugins_url( 'assets/css/wrld-welcome-modal.css', WRLD_REPORTS_FILE ), array(), WRLD_PLUGIN_VERSION );
			wp_enqueue_script( 'wrld_welcome_modal_script', plugins_url( 'assets/js/wrld-welcome-modal.js', WRLD_REPORTS_FILE ), array( 'jquery' ), WRLD_PLUGIN_VERSION, true );
			$local_script_data = array(
				'wp_ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'wrld-welcome-modal' ),
			);
			wp_localize_script( 'wrld_welcome_modal_script', 'wrld_welcome_modal_script_data', $local_script_data );
			include_once WRLD_REPORTS_PATH . '/includes/templates/welcome-modal.php';
			echo wrld_dont_turn_off_modal_content();// phpcs:ignore
			?>
				<div class='wrld-dashboard-page-content settings-content'>
					<div>
						<span class='wrld-dashboard-text license'> <?php esc_html_e( 'Header Menu Settings', 'learndash-reports-by-wisdmlabs' ); ?> </span>
						<br/>
						<div class='wrld-dashboard-config-setting'>
							<?php
							self::get_add_menu_link_setting( $settings_data );
							?>
							<div>
								<button id='wrld-action-update-menu-settings' disabled><?php esc_html_e( 'Apply', 'learndash-reports-by-wisdmlabs' ); ?></button>
							</div>
							<?php
							self::get_accessibility_setting( $settings_data );
							?>
						</div>
					</div>
					<div>
						<button id='wrld-action-update-settings' disabled><?php esc_html_e( 'Apply', 'learndash-reports-by-wisdmlabs' ); ?></button>
					</div>
					<div class='wrld-dashboard-config-setting'>
						<?php
						self::get_users_exclude_setting( $settings_data );
						?>
					</div>
					<div class='wrld-dashboard-config-setting' id='wlrd-dashboard-time-settings'>
						<?php
						self::get_time_tracking_setting( $settings_data );
						?>
					</div>
				</div>
			<?php
		}

		public static function content_sidebar() {
			?>
				<div class='wrld-dashboard-page-sidebar'>
					<?php self::sidebar_block_upgrade(); ?>
					<?php self::sidebar_block_help(); ?>
				</div>
			<?php
		}

		public static function sidebar_block_upgrade() {
			if ( defined( 'LDRP_PLUGIN_VERSION' ) ) {
				return '';
			} else {
				?>
				<div class='wrld-sidebar-block'>
					<div class='wrld-sidebar-block-head'>
						<div class='wrld-sidebar-head-icon'>
							<span class='upgrade-icon'></span>
						</div>
						<div class='wrld-sidebar-head-text'>
							<span><?php esc_html_e( 'Upgrade your FREE Wisdm Reports Plugin to PRO!', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</div>
					</div>
					<div class='wrld-sidebar-body'>
						<div class='wrld-sidebar-body-text'>
							<span><?php esc_html_e( 'Click the button below to upgrade your FREE Wisdm Reports Plugin to PRO!', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</div>
						<a href="https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=wrld_in_plugin_settings_tab&utm_campaign=wrld_in_plugin_settings_tab&utm_id=20062022&utm_term=wrld_in_plugin_settings_tab#pricing" target='__blank'>
							<button class='wrld-sidebar-body-button'><?php esc_html_e( 'Upgrade to PRO', 'learndash-reports-by-wisdmlabs' ); ?></button>
						</a>
					</div>
				</div>
				<?php
			}
		}

		public static function sidebar_block_help() {
			?>
			<div class='wrld-sidebar-block'>
					<div class='wrld-sidebar-block-head'>
						<div class='wrld-sidebar-head-icon'>
							<span class='help-icon'></span>
						</div>
						<div class='wrld-sidebar-head-text'>
							<span><?php esc_html_e( 'Looking for help?', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</div>
					</div>
					<div class='wrld-sidebar-body'>
						<div class='wrld-sidebar-body-section'>
							<div class='wrld-sidebar-body-text'>
								<span><?php esc_html_e( 'Need help with the plugin configuration?', 'learndash-reports-by-wisdmlabs' ); ?></span>
							</div>
							<ul>
							  <li><a href="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/configuration-accessibility-settings/" target='__blank'><?php esc_html_e( 'Settings & access configuration', 'learndash-reports-by-wisdmlabs' ); ?></a></li>
							  <!-- <li><a href="#" target='__blank'><?php esc_html_e( 'Document Link 2', 'learndash-reports-by-wisdmlabs' ); ?></a></li> -->
							</ul>
						</div>
						<div class='wrld-sidebar-body-section'>
							<div class='wrld-sidebar-body-text'>
								<span><?php esc_html_e( 'Need help with creating separate reports dashboards for different user roles?', 'learndash-reports-by-wisdmlabs' ); ?></span>
							</div>
							<ul>
							  <li><a href="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/how-to-create-multiple-user-specific-dashboards/" target='__blank'><?php esc_html_e( 'Create Multiple Dashboards', 'learndash-reports-by-wisdmlabs' ); ?></a></li>
							  <!-- <li><a href="#" target='__blank'><?php esc_html_e( 'Document Link 2', 'learndash-reports-by-wisdmlabs' ); ?></a></li> -->
							</ul>
						</div>
					</div>
				</div>
			<?php
		}

		public static function get_add_menu_link_setting( $settings_data ) {
			$add_menu_link         = isset( $settings_data['wrld-menu-config-setting'] ) && ( 'true' == $settings_data['wrld-menu-config-setting'] ) ? true : false;
			$add_menu_student_link = isset( $settings_data['wrld-menu-student-setting'] ) && ( 'true' == $settings_data['wrld-menu-student-setting'] ) ? true : false;
			$disabled              = defined( 'LDRP_PLUGIN_VERSION' ) ? false : true;
			?>
				<div class='wrld-menu-config-setting-section'>
					<label for='wrld-menu-config-setting' class="checkbox-label <?php echo esc_attr( $add_menu_link ? '' : 'no-access' ); ?>">
						<input type="checkbox" name="wrld-menu-config-setting" id="wrld-menu-config-setting" class="dashicons" <?php checked( $add_menu_link ); ?> >
						<span><?php esc_html_e( 'Add the link of the Reports Dashboard to the Header Menu', 'learndash-reports-by-wisdmlabs' ); ?></span>
					</label>
				</div>
				<div class='wrld-menu-config-setting-section'>
					<label for='wrld-menu-student-setting' class="checkbox-label <?php echo esc_attr( $add_menu_student_link ? '' : 'no-access' ); ?>">
						<input type="checkbox" name="wrld-menu-student-setting" id="wrld-menu-student-setting" class="dashicons" <?php checked( $add_menu_student_link ); ?> <?php disabled( $disabled ); ?>">
						<span class="<?php echo $disabled ? 'disabled' : ''; ?>"><?php esc_html_e( 'Add the link of the Student Quiz Reports page to the Header Menu', 'learndash-reports-by-wisdmlabs' ); ?></span>
					</label>
				</div>
			<?php
		}

		public static function get_accessibility_setting( $settings_data ) {
			$instructor_role_status = defined( 'INSTRUCTOR_ROLE_PLUGIN_VERSION' ) ? '' : 'disabled';
			$gl_access              = isset( $settings_data['dashboard-access-roles'] ) && ( 'true' == $settings_data['dashboard-access-roles']['group_leader'] ) ? true : false;
			$wdm_instructor_access  = isset( $settings_data['dashboard-access-roles'] ) && ( 'true' == $settings_data['dashboard-access-roles']['wdm_instructor'] ) ? true : false;
			if ( ! isset( $settings_data['dashboard-access-roles'] ) ) {
				$gl_access             = true;
				$wdm_instructor_access = true;
			}
			?>
				<div class='wrld-accessibility-setting-section'>
					<div>
						<span class='wrld-dashboard-text license'> <?php esc_html_e( 'User Role Access for Reports Dashboard', 'learndash-reports-by-wisdmlabs' ); ?> </span>
					</div>
					<div class='wrld-dashboard-note-container'>
						<span class='wrld-dashboard-text'> <?php esc_html_e( 'The user roles selected here and the admin will have access to the Reports Dashboard.', 'learndash-reports-by-wisdmlabs' ); ?> </span>
					</div>
					<div>
						<label for='wrld-menu-access-setting-group-leader' class="checkbox-label <?php echo esc_attr( $gl_access ? '' : 'no-access' ); ?>">
							<input type="checkbox" name="wrld-menu-access-setting-group-leader" id="wrld-menu-access-setting-group-leader" class="dashicons" <?php checked( $gl_access ); ?>>
							<span><?php esc_html_e( 'Group Leader', 'learndash-reports-by-wisdmlabs' ); ?></span>
							<p class='wrld-access-status wrld-access-status-enabled wrld-group-leader-enabled' style='<?php echo esc_attr( self::style_display( $gl_access ) ); ?>'><span class="dashicons dashicons-yes"></span><span><?php esc_html_e( 'Access Active', 'learndash-reports-by-wisdmlabs' ); ?></span></p>
							<p class='wrld-access-status wrld-access-status-disabled wrld-group-leader-disabled' style='<?php echo esc_attr( self::style_display( ! $gl_access ) ); ?>'><span class="dashicons dashicons-no"></span><span><?php esc_html_e( 'Access Inactive', 'learndash-reports-by-wisdmlabs' ); ?></span></p>
						</label>
					</div>
					<div>
						<label for='wrld-menu-access-setting-wdm-instructor' class="checkbox-label <?php echo esc_attr( $wdm_instructor_access ? '' : 'no-access' ); ?>">
							<input type="checkbox" <?php echo esc_attr( $instructor_role_status ); ?> name="wrld-menu-access-setting-wdm-instructor" id="wrld-menu-access-setting-wdm-instructor" class="dashicons" <?php checked( $wdm_instructor_access ); ?>>
							<span class="<?php echo esc_attr( $instructor_role_status ); ?>"><?php esc_html_e( 'Instructor role by WisdmLabs', 'learndash-reports-by-wisdmlabs' ); ?></span>
							<p class='wrld-access-status wrld-access-status-enabled wrld-wisdm-instructor-enabled' style='<?php echo esc_attr( self::style_display( $wdm_instructor_access && 'disabled' !== $instructor_role_status, 'inline-block' ) ); ?>'><span class="dashicons dashicons-yes"></span><span><?php esc_html_e( 'Access Active', 'learndash-reports-by-wisdmlabs' ); ?></span></p>
							<p class='wrld-access-status wrld-access-status-disabled wrld-wisdm-instructor-disabled' style='<?php echo esc_attr( self::style_display( ! $wdm_instructor_access && 'disabled' !== $instructor_role_status, 'inline-block' ) ); ?>'><span class="dashicons dashicons-no"></span><span><?php esc_html_e( 'Access Inactive', 'learndash-reports-by-wisdmlabs' ); ?></span></p>
							<?php
							if ( 'disabled' === $instructor_role_status ) {
								?>
										<span><a href="https://wisdmlabs.com/instructor-role-for-learndash/?utm_source=wrld&utm_medium=wrld_in_plugin_settings_tab&utm_campaign=wrld_in_plugin_settings_tab&utm_id=20062022&utm_term=wrld_in_plugin_settings_tab" target='_blank'><?php esc_html_e( 'Learn more', 'learndash-reports-by-wisdmlabs' ); ?></a></span>
									<?php
							}
							?>
						</label>
					</div>
				</div>
			<?php
		}

		public static function get_users_exclude_setting( $settings_data ) {
			?>
			<div class='wrld-accessibility-setting-section'>
				<div>
					<span class="wrld-dashboard-text license"><?php esc_html_e( 'Exclude from all Reports and Graphs', 'learndash-reports-by-wisdmlabs' ); ?></span>
				</div>
				<div class="exclude-field courses-field">
					<div class="left-fields">
						<div class="label_wrapper">
							<span><?php esc_html_e( 'Exclude the courses', 'learndash-reports-by-wisdmlabs' ); ?></span>
							<!-- <span class="dashicons dashicons-edit"></span> -->
						</div>
					</div>
					<div class="right-fields">
						<div class="select">
							<?php
							$selected_courses = get_option( 'exclude_courses', false );
							if ( empty( $selected_courses ) ) {
								$selected_courses = array();
							}
							$courses = get_posts(
								array(
									'post_type'      => 'sfwd-courses',
									'posts_per_page' => -1,
								)
							);
							echo '<select name="courses" class="exclude_courses" multiple>';
							foreach ( $courses as $course ) {
								$excluded_courses = '';
								if ( in_array( $course->ID, $selected_courses ) ) {
									$excluded_courses = 'selected="selected"';
								}
								echo '<option value="' . esc_attr( $course->ID ) . '" ' . esc_attr( $excluded_courses ) . '>' . esc_html( $course->post_title ) . '</option>';
							}
							echo '</select>';
							?>
						</div>
						<div class="apply_button">
							<button class="apply_courses_exclude" style="display: none;"><?php esc_html_e( 'Save', 'learndash-reports-by-wisdmlabs' ); ?></button>
							<button class="discard_courses_exclude" style="display: none;"><?php esc_html_e( 'Discard', 'learndash-reports-by-wisdmlabs' ); ?></button>
							
						</div>
					</div>
				</div>
				<div class="exclude-field user-roles-field <?php echo defined( 'LDRP_PLUGIN_VERSION' ) ? 'pro-active' : 'pro-inactive'; ?>">
					<div class="left-fields">
						<div class="label_wrapper">
							<span><?php esc_html_e( 'Exclude the user roles', 'learndash-reports-by-wisdmlabs' ); ?></span>
							<!-- <span class="dashicons dashicons-edit"></span> -->
						</div>
					</div>
					<div class="right-fields">
						<div class="select">
							<?php
							global $wp_roles;
							$selected_ur = get_option( 'exclude_ur', false );
							if ( empty( $selected_ur ) ) {
								$selected_ur = array();
							}
							$disabled = '';
							if ( ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
								$disabled = 'disabled';
							}
							echo '<select name="userrole" class="exclude_ur" multiple ' . esc_attr( $disabled ) . '>';
							foreach ( $wp_roles->roles as $slug => $role ) {
								$excluded_ur = '';
								if ( in_array( $slug, $selected_ur ) ) {
									$excluded_ur = 'selected="selected"';
								}
								echo '<option value="' . esc_attr( $slug ) . '" ' . esc_attr( $excluded_ur ) . '>' . esc_html( $role['name'] ) . '</option>';
							}
							echo '</select>';
							?>
						</div>
						<div class="apply_button">
							<button class="apply_ur_exclude" style="display: none;"><?php esc_html_e( 'Save', 'learndash-reports-by-wisdmlabs' ); ?></button>
							<button class="discard_ur_exclude" style="display: none;"><?php esc_html_e( 'Discard', 'learndash-reports-by-wisdmlabs' ); ?></button>
						</div>
					</div>
				</div>
				<div class="exclude-field users-field <?php echo defined( 'LDRP_PLUGIN_VERSION' ) ? 'pro-active' : 'pro-inactive'; ?>">
					<div class="left-fields">
						<div class="label_wrapper">
							<span><?php esc_html_e( 'Exclude the users', 'learndash-reports-by-wisdmlabs' ); ?></span>
							<!-- <span class="dashicons dashicons-edit"></span> -->
						</div>
					</div>
					<div class="right-fields">
						<div class="select">
							<?php
							$selected_user = get_option( 'exclude_users', false );
							if ( empty( $selected_user ) ) {
								$selected_user = array();
							}
							$disabled = 0;
							if ( ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
								$disabled = 1;
							}
							\WRLD_Common_Functions::wrld_dropdown_users(
								array(
									'name'             => 'users',
									'multi'            => 1,
									'disabled'         => $disabled,
									'class'            => 'exclude_users',
									'multiselect'      => $selected_user,
									'number'           => 10,
									'include_selected' => true,
								)
							);
							?>
						</div>
						<div class="apply_button">
							<button class="apply_users_exclude" style="display: none;"><?php esc_html_e( 'Save', 'learndash-reports-by-wisdmlabs' ); ?></button>
							<button class="discard_users_exclude" style="display: none;"><?php esc_html_e( 'Discard', 'learndash-reports-by-wisdmlabs' ); ?></button>
						</div>
						<?php if ( ! defined( 'LDRP_PLUGIN_VERSION' ) ) : ?>
							<a class="wrld-upgrade-button" href="https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=exclude-users-settings&utm_campaign=exclude-users&utm_term=exclude-users-settings#pricing" target='__blank'>
								<button class='wrld-sidebar-body-button'><?php esc_html_e( 'Upgrade to PRO', 'learndash-reports-by-wisdmlabs' ); ?></button>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}

		public static function get_time_tracking_setting( $settings_data ) {
			?>
			<div class='wrld-accessibility-setting-section'>
				<div>
					<div class="wrld-dashboard-text license" style="margin-bottom: 7px;"><?php esc_html_e( 'Time Tracking', 'learndash-reports-by-wisdmlabs' ); ?></div>
					<span class="wrld-dashboard-text">
						<ul id="wlrp_more" ><?php esc_html_e( 'This setting enables you to track the “actual” time spent by learners by discarding the time', 'learndash-reports-by-wisdmlabs' ); ?> <span>[ + <?php esc_html_e( 'more', 'learndash-reports-by-wisdmlabs' ); ?> ]</span>
							<li><?php esc_html_e( 'when the learner - “opens another tab and leaves the current tab” or', 'learndash-reports-by-wisdmlabs' ); ?></li>
							<li><?php esc_html_e( 'the learner is “Idle” on the current tab such as “the learner does not move the cursor” or', 'learndash-reports-by-wisdmlabs' ); ?></li>
							<li><?php esc_html_e( 'the learner is “Idle” on the current tab such as “the learner does not perform any keyboard strokes”.', 'learndash-reports-by-wisdmlabs' ); ?></li>
						</ul>
					</span>
				</div>
				<div style="margin-top: 24px;">
					<?php if ( defined( 'LDRP_PLUGIN_VERSION' ) ) : ?>
						<?php
						$status   = get_option( 'wrld_time_tracking_status', false );
						$timer    = get_option( 'wrld_time_tracking_timer', false );
						$message  = get_option( 'wrld_time_tracking_message', false );
						$btnlabel = get_option( 'wrld_time_tracking_btnlabel', false );
						$latest   = get_option( 'wrld_time_tracking_last_update', false );
						$log      = get_option( 'wrld_time_tracking_log', false );
						if ( empty( $status ) ) {
							$all_updates  = get_option( 'wrld_time_tracking_log', false );
							$current_time = current_time( 'timestamp' );
							if ( ! empty( $all_updates ) ) {
								$all_updates[] = $current_time;
							} else {
								$all_updates = array( $current_time );
							}
							update_option( 'wrld_time_tracking_status', 'on' );
							update_option( 'wrld_time_tracking_last_update', $current_time );
							update_option( 'wrld_time_tracking_log', $all_updates );
							$log    = $all_updates;
							$latest = $current_time;
							$status = 'on';
						}
						?>
						<div class="wrld-dashboard-text sub-heading" style="margin-bottom: 5px;"><?php esc_html_e( 'Configure Idle Time', 'learndash-reports-by-wisdmlabs' ); ?><span class="info-icon"><span class="dashicons dashicons-info-outline"></span><div class="wdm-tooltip"><?php esc_html_e( 'Enable/Disable the idle time tracking module', 'learndash-reports-by-wisdmlabs' ); ?></div></span></div>
						<span class="wrld-dashboard-text"><?php esc_html_e( 'This Setting enables you to track the “Idle Time” of the learners on the site.', 'learndash-reports-by-wisdmlabs' ); ?></span>
						<div class="toggle">
							<label class="switch"><input type="checkbox" name="wrld_time_tracking_status" class="wdm-input-checkbox wrld_time_tracking_status" <?php esc_attr_e( 'off' !== $status ? 'checked="checked"' : '' );// phpcs:ignore ?>>
							<div class="slider round <?php esc_attr_e( 'off' !== $status ? 'on' : '' );// phpcs:ignore ?>"></div>
							</label>
							<span class="option <?php esc_attr_e( 'off' !== $status ? 'on' : '' );// phpcs:ignore ?>"><?php esc_html_e( 'off' !== $status ? 'ON' : 'OFF' );// phpcs:ignore ?></span>	
						</div>
						<?php
						$css = '';
						if ( 'off' === $status ) {
							$css = 'style="display: none;"';
						}
						?>
						<span class="wrld-dashboard-text note-text" <?php echo $css;// phpcs:ignore ?>><strong><?php esc_html_e( 'Note:', 'learndash-reports-by-wisdmlabs' ); ?></strong> <?php esc_html_e( 'This setting was activated on ', 'learndash-reports-by-wisdmlabs' ); ?><span class="latest"><?php echo esc_html( date_i18n( 'Y-m-d H:i:s', $latest ) ); ?></span>.<?php esc_html_e( ' If there are any learners that were already “in-progress” of courses, the “Idle Time” for these learners will only start getting tracked after this setting was activated.', 'learndash-reports-by-wisdmlabs' ); ?></span>
						<script type="text/javascript">
							jQuery( '.wrld_time_tracking_status' ).on( 'change', function() {
								if( jQuery( '.wrld_time_tracking_status' ).is(':checked') ) {
									jQuery( '.wrld_time_tracking_status' ).parent().find('.slider').addClass('on');
									jQuery( '.wrld_time_tracking_status' ).parent().next().addClass('on');
									jQuery( '.wrld_time_tracking_status' ).parent().next().text('ON');
									jQuery('.toggle-settings').addClass('activated');
								} else {
									jQuery( '.wrld_time_tracking_status' ).parent().next().text('OFF');
									jQuery( '.wrld_time_tracking_status' ).parent().next().removeClass('on');
									jQuery( '.wrld_time_tracking_status' ).parent().find('.slider').removeClass('on');
									jQuery('.toggle-settings').removeClass('activated');
								}
							} );
						</script>
						<div class="time_frontend_popup_image"><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/images/time_tracking_popup.png' ); ?>" alt=""></div>
						<div class="toggle-settings <?php esc_attr_e( 'off' !== $status ? 'activated' : '' );// phpcs:ignore ?>">
							<div><span><?php esc_html_e( 'Idle Time (in seconds)', 'learndash-reports-by-wisdmlabs' ); ?></span><span class="info-icon"><span class="dashicons dashicons-info-outline"></span><div class="wdm-tooltip"><?php esc_html_e( 'Configuring the Idle Time will open a popup every time a learner is idle for the same amount of time as configured here.', 'learndash-reports-by-wisdmlabs' ); ?></div></span></div>
							<input type="number" name="wrld_idle_time" value="<?php esc_attr_e( ! empty( $timer ) ? $timer : 600 );// phpcs:ignore ?>" />
						</div>
						<div class="toggle-settings <?php esc_attr_e( 'off' !== $status ? 'activated' : '' );// phpcs:ignore ?>">
							<div><span><?php esc_html_e( 'Idle Message', 'learndash-reports-by-wisdmlabs' ); ?></span><span class="info-icon"><span class="dashicons dashicons-info-outline"></span><div class="wdm-tooltip"><?php esc_html_e( 'This is the message that learners will see in the popup if they are idle.', 'learndash-reports-by-wisdmlabs' ); ?></div></span></div>
							<input type="text" name="wrld_idle_msg" value="<?php echo esc_attr( ! empty( $message ) ? $message : __( 'Are you still on this page?', 'learndash-reports-by-wisdmlabs' ) ); ?>" />
						</div>
						<div class="toggle-settings <?php esc_attr_e( 'off' !== $status ? 'activated' : '' );// phpcs:ignore ?>">
							<div><span><?php esc_html_e( 'Active Button Label', 'learndash-reports-by-wisdmlabs' ); ?></span><span class="info-icon"><span class="dashicons dashicons-info-outline"></span><div class="wdm-tooltip"><?php esc_html_e( 'Clicking on this button will resume the time being tracked on a course for the learner.', 'learndash-reports-by-wisdmlabs' ); ?></div></span></div>
							<input type="text" name="wrld_idle_btn_label" value="<?php esc_attr_e( ! empty( $btnlabel ) ? $btnlabel : 'Yes' );// phpcs:ignore ?>" />
						</div>
						<div class="apply_button">
							<button class="apply_time_tracking_settings" disabled="disabled" <?php echo $css;// phpcs:ignore ?> ><?php esc_html_e( 'Save Changes', 'learndash-reports-by-wisdmlabs' ); ?></button>
						</div>
						<div class="idle-configuration-log">
							<h2><?php esc_html_e( 'Idle Time Configuration Log', 'learndash-reports-by-wisdmlabs' ); ?></h2>
							<?php
							$log = get_option( 'wrld_time_tracking_log', false );
							if ( empty( $log ) ) {
								?>
								<table>
									<tr>
										<?php esc_html_e( 'No Log Entries found', 'learndash-reports-by-wisdmlabs' ); ?>
									</tr>
								</table>
								<?php
							} else {
								?>
								<table>
									<tr>
										<th><?php esc_html_e( 'Idle Time Status', 'learndash-reports-by-wisdmlabs' ); ?></th>
										<th><?php esc_html_e( 'Activity Log', 'learndash-reports-by-wisdmlabs' ); ?></th>
									</tr>
									<?php
									$counter = 0;
									if ( 'off' !== $status ) {
										$counter = 1;
									}
									$log = array_slice( $log, -10 );
									for ( $index = count( $log ) - 1; $index >= 0; $index-- ) {
										?>
										<tr>
											<td><?php echo esc_html( ( $counter % 2 ) ? __( 'Activated', 'learndash-reports-by-wisdmlabs' ) : __( 'Deactivated', 'learndash-reports-by-wisdmlabs' ) ); ?></td>
											<td><?php echo esc_html( date_i18n( 'Y-m-d H:i:s', $log[ $index ] ) ); ?></td>
										</tr>
										<?php
										$counter++;
									}
									?>
								</table>
								<span><?php echo sprintf( '<strong>%s</strong> %s', esc_html__( 'Note:', 'learndash-reports-by-wisdmlabs' ), esc_html__( 'Only the latest 10 entries will be shown here.', 'learndash-reports-by-wisdmlabs' ) ); ?></span>
								<?php
							}
							?>
						</div>
					<?php else : ?>
						
						<div class="wrld-dashboard-text sub-heading free-plugin" style="margin-bottom: 5px;"><span><?php esc_html_e( 'Configure Idle Time', 'learndash-reports-by-wisdmlabs' ); ?></span><span class="info-icon"><span class="dashicons dashicons-info-outline"></span><div class="wdm-tooltip"><?php esc_html_e( 'Enable/Disable the idle time tracking module', 'learndash-reports-by-wisdmlabs' ); ?></div></span></div>
						
						<div class="toggle">
							<label class="switch"><input type="checkbox" name="wrld_time_tracking_status" class="wdm-input-checkbox wrld_time_tracking_status" disabled="disabled">
							<div class="slider round" style="opacity: 0.5;"></div>
							</label>
							<span class="option" style="opacity: 0.5;">OFF</span>	
						</div>
						<span class="wrld-dashboard-text">
							<ul>
								<li><?php esc_html_e( 'The Free version does not track the “Idle Time” of the learners on the site.', 'learndash-reports-by-wisdmlabs' ); ?></li>
								<li><?php esc_html_e( 'Currently, the total time between a learner’s enrollment and completion in a course is displayed as the Time Spent.', 'learndash-reports-by-wisdmlabs' ); ?></li>
								<li><?php esc_html_e( 'The Pro version will track “Idle Time” for you and produce more accurate “Time Spent” Reports.', 'learndash-reports-by-wisdmlabs' ); ?></li>
							</ul>
						</span>
						<a class="wrld-upgrade-button" href="https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=wrld_in_plugin_settings_tab&utm_campaign=wrld_in_plugin_settings_tab&utm_id=20062022&utm_term=wrld_in_plugin_settings_tab#pricing" target='__blank' style="margin-top: 20px; margin-bottom: 35px;">
							<button class='wrld-sidebar-body-button'><?php esc_html_e( 'Upgrade to PRO', 'learndash-reports-by-wisdmlabs' ); ?></button>
						</a>
						<div class="time_frontend_popup_image"><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/images/time_tracking_popup.png' ); ?>" alt=""></div>
						<div class="toggle-settings activated free-plugin">
							<div><span><?php esc_html_e( 'Idle Time (in seconds)', 'learndash-reports-by-wisdmlabs' ); ?></span><span class="info-icon"><span class="dashicons dashicons-info-outline"></span><div class="wdm-tooltip"><?php esc_html_e( 'Configuring the Idle Time will fire a popup every time a learner is idle for the same amount of time as configured here.', 'learndash-reports-by-wisdmlabs' ); ?></div></span></div>
							<input type="number" name="wrld_idle_time" value="600" disabled="disabled"/>
						</div>
						<div class="toggle-settings activated free-plugin">
							<div><span><?php esc_html_e( 'Idle Message', 'learndash-reports-by-wisdmlabs' ); ?></span><span class="info-icon"><span class="dashicons dashicons-info-outline"></span><div class="wdm-tooltip"><?php esc_html_e( 'This is the message that learners will see in the popup if they are idle.', 'learndash-reports-by-wisdmlabs' ); ?></div></span></div>
							<input type="text" name="wrld_idle_msg" value="<?php esc_attr_e( 'Are you still on this page?', 'learndash-reports-by-wisdmlabs' ); ?>" disabled="disabled"/>
						</div>
						<div class="toggle-settings activated free-plugin">
							<div><span><?php esc_html_e( 'Active Button Label', 'learndash-reports-by-wisdmlabs' ); ?></span><span class="info-icon"><span class="dashicons dashicons-info-outline"></span><div class="wdm-tooltip"><?php esc_html_e( 'Clicking on this button will resume the time being tracked on a course for the learner.', 'learndash-reports-by-wisdmlabs' ); ?></div></span></div>
							<input type="text" name="wrld_idle_btn_label" value="Yes" disabled="disabled"/>
						</div>
						<div class="apply_button">
							<button class="apply_time_tracking_settings" disabled="disabled"><?php esc_html_e( 'Save Changes', 'learndash-reports-by-wisdmlabs' ); ?></button>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
	}
}
