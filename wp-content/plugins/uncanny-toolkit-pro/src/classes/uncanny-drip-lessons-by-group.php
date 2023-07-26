<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class UncannyDripLessonsByGroup
 * @package uncanny_pro_toolkit
 */
class UncannyDripLessonsByGroup extends toolkit\Config implements toolkit\RequiredFunctions {

	public static $learndash_post_types = array( 'sfwd-lessons' );

	public static $access_metabox_key = 'learndash-lesson-access-settings';

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {

			// Legacy - group access settings
			add_filter( 'learndash_post_args', array( __CLASS__, 'add_group_access_to_post_args_legacy' ) );

			// 3.0+  - Add auto complete setting to LearnDash Lessons (auto creates field and loads value)
			add_filter( 'learndash_settings_fields', array(
				__CLASS__,
				'add_auto_complete_to_post_args',
			), 10, 2 ); // 3.0+

			// 3.0+ - Save custom lesson settings field
			add_filter( 'learndash_metabox_save_fields', array(
				__CLASS__,
				'save_lesson_custom_meta',
			), 60, 3 );

			# Change again when the option is called on "Edit Lesson" page
			add_filter( 'sfwd-lessons_display_settings', array( __CLASS__, 'change_lesson_setting' ) );

			# When post is saved
			add_action( 'save_post', array( __CLASS__, 'save_post' ), 50, 3 );

			# Change shortcodes and hooks to show the lesson because there is no hooking point to control it, so I change entire screen
			add_action( 'after_setup_theme', array( __CLASS__, 'change_hooks_and_shortcodes' ), 1 );

			# Call a javascript
			//add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );

			#Convert String DateTime to UnixTimeStamp
			add_action( 'admin_init', array( __CLASS__, 'reformat_date_to_unix' ), 999 );

			///Add filter for LD Notifications
			add_filter( 'ld_lesson_access_from', array(
				__CLASS__,
				'ld_lesson_access_from_func'
			), 99999, 3 );
		}

	}

	/**
	 * @param $access_from
	 * @param $lesson_id
	 * @param $user_id
	 *
	 * @return bool|int|mixed|string
	 * @throws \Exception
	 */
	public static function ld_lesson_access_from_func( $access_from, $lesson_id, $user_id ) {
		if ( ! is_admin() ) {

			if ( is_object( $lesson_id ) ) {
				$lesson_id = $lesson_id->ID;
			}

			$group_access = self::ld_lesson_access_group( $lesson_id, $user_id );

			if ( 'Available' === $group_access || empty( $access_from ) ) {
				$access_from = '';
			}

			if ( is_numeric( $group_access ) && $group_access >= time() ) {
				$access_from = $group_access;
			}
		}

		return $access_from;
	}

	/**
	 * Add settings to Lessons and Topics settings tab
	 *
	 * @param $setting_option_fields
	 * @param $settings_metabox_key
	 *
	 * @return mixed
	 */
	public static function add_auto_complete_to_post_args( $setting_option_fields, $settings_metabox_key ) {


		if ( $settings_metabox_key === self::$access_metabox_key ) {

			?>
			<style>
				#learndash-lesson-access-settings_set_groups_for_dates_field {
					display: none;
				}
			</style>
			<script>
              jQuery(function () {

                // Show group drop down is drip date is active
                if (0 !== jQuery('.ld-settings-inner-visible_after_specific_date.ld-settings-inner-state-open').length) {
                  jQuery('#learndash-lesson-access-settings_set_groups_for_dates_field').show()
                }

                // Toggle groups drop down visibility when access type is changed
                jQuery('#learndash-lesson-access-settings_lesson_schedule_field input[type=radio]').on('change', function () {
                  if ('visible_after_specific_date' === this.value) {
                    jQuery('#learndash-lesson-access-settings_set_groups_for_dates_field').show()
                  } else {
                    jQuery('#learndash-lesson-access-settings_set_groups_for_dates_field').hide()
                  }
                })
              })
			</script>
			<?php


			global $post;
			$learndash_post_settings = (array) learndash_get_setting( $post, null );

			$value = '';
			if ( isset( $learndash_post_settings['set_groups_for_dates'] ) ) {
				if ( ! empty( $learndash_post_settings['set_groups_for_dates'] ) ) {
					$value = $learndash_post_settings['set_groups_for_dates'];
				}
			}

			$groups = get_posts( [
				'post_type'      => 'groups',
				'posts_per_page' => 999,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			] );

			// If any group is not exists, this option will be disabled
			if ( ! $groups ) {
				return $setting_option_fields;
			}

			$all_other_users_date = get_post_meta( $post->ID, stripslashes( __CLASS__ ) . '-all', true );

			if ( $all_other_users_date ) {
				if ( is_array( $all_other_users_date ) ) {
					$all_other_users_date = self::reformat_date( $all_other_users_date );
					$all_other_users_date = learndash_adjust_date_time_display( $all_other_users_date );
				}
				if ( self::is_timestamp( $all_other_users_date ) ) {
					$date_format          = get_option( 'date_format' );
					$time_format          = get_option( 'time_format' );
					$all_other_users_date = self::adjust_for_timezone_difference( $all_other_users_date );
					$all_other_users_date = date_i18n( "$date_format $time_format", $all_other_users_date );
				}
				$all_other_users_name = 'All Other Users' . ' &mdash; (' . $all_other_users_date . ')';
			} else {
				$all_other_users_name = 'All Other Users';
			}

			// group_selection
			$group_selection = array(
				0     => 'Select a LearnDash Group',
				'all' => $all_other_users_name,
			);

			foreach ( $groups as $group ) {
				if ( $group && is_object( $group ) ) {

					$date = get_post_meta( $post->ID, stripslashes( __CLASS__ ) . '-' . $group->ID, true );
					// Add tha ( date ) after group name on selection if exists

					if ( $date ) {
						if ( is_array( $date ) ) {
							$date = self::reformat_date( $date );
							$date = learndash_adjust_date_time_display( $date );
						}
						if ( self::is_timestamp( $date ) ) {
							$date_format = get_option( 'date_format' );
							$time_format = get_option( 'time_format' );
							$date        = self::adjust_for_timezone_difference( $date );
							$date        = date_i18n( "$date_format $time_format", $date );
						}
						$group_name = $group->post_title . ' &mdash; (' . $date . ')';
					} else {
						$group_name = $group->post_title;
					}

					$group_selection[ $group->ID ] = $group_name;
				}
			}

			$setting_option_fields['set_groups_for_dates'] = array(
				'name'      => 'set_groups_for_dates',
				'label'     => __( 'LearnDash Group', 'uncanny-pro-toolkit' ),
				'type'      => 'select',
				'help_text' => __( 'Choose a group for a custom drip date', 'uncanny-pro-toolkit' ),
				'options'   => $group_selection,
				'default'   => 'all',
				'value'     => $value
			);
		}

		return $setting_option_fields;

	}

	/**
	 * Save post metadata when a post is saved.
	 *
	 * @param $settings_field_updates
	 * @param $settings_metabox_key
	 * @param $settings_screen_id
	 *
	 * @return mixed
	 */
	public static function save_lesson_custom_meta( $settings_field_updates, $settings_metabox_key, $settings_screen_id ) {


		global $post;

		if ( self::$access_metabox_key === $settings_metabox_key ) {

			// - Update the post's metadata. Nonce already verified by LearnDash
			if (
				isset( $_POST['learndash-lesson-access-settings'] ) &&
				isset( $_POST['learndash-lesson-access-settings']['set_groups_for_dates'] )
			) {


				// if group was set, save it
				if ( isset( $_POST['learndash-lesson-access-settings']['set_groups_for_dates'] ) ) {

					$group_id = $_POST['learndash-lesson-access-settings']['set_groups_for_dates'];
					if ( ! empty( $group_id ) ) {

						//learndash-lesson-access-settings[visible_after_specific_date][mm]: 01
						//learndash-lesson-access-settings[visible_after_specific_date][jj]: 01
						//learndash-lesson-access-settings[visible_after_specific_date][aa]: 2020
						//learndash-lesson-access-settings[visible_after_specific_date][hh]: 01
						//learndash-lesson-access-settings[visible_after_specific_date][mn]: 01

						$date = self::reformat_date( $_POST['learndash-lesson-access-settings']['visible_after_specific_date'] );

						if ( 0 === $date ) {
							delete_post_meta( $post->ID, stripslashes( __CLASS__ ) . '-' . $group_id );
							self::unset_notifications( $post->ID );
						} else {
							update_post_meta( $post->ID, stripslashes( __CLASS__ ) . '-' . $group_id, $date );
							self::set_notifications( $post->ID );
						}
					}
				}
			}

			// get original options and reset it
			$original_option                                             = get_post_meta( $post->ID, '_sfwd-lessons', true );
			$original_date                                               = get_post_meta( $post->ID, stripslashes( __CLASS__ ) . '-all', true );
			$original_option['sfwd-lessons_set_groups_for_dates']        = '';
			$original_option['sfwd-lessons_visible_after_specific_date'] = $original_date;

			update_post_meta( $post->ID, '_sfwd-lessons', $original_option );
		}

		return $settings_field_updates;


	}


	# Change the shortcode

	/**
	 *
	 */
	public static function change_hooks_and_shortcodes() {
		# Replace the function
		remove_filter( 'learndash_content', 'lesson_visible_after', 1 );
		add_filter( 'learndash_content', array( __CLASS__, 'lesson_visible_after' ), 1, 2 );
		add_filter( 'learndash_template', array( __CLASS__, 'learndash_template' ), 1, 5 );
	}

	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * @return boolean || string Return either true or name of function or plugin
	 *
	 */
	public static function dependants_exist() {

		/* Checks for LearnDash */
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		// Return true if no dependency or dependency is available
		return true;

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Drip Lessons by Group', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/drip-lessons-by-ldgroup/';

		$class_description = esc_html__( 'Unlock access to LearnDash lessons by setting dates for LearnDash Groups rather than for all enrolled users.', 'uncanny-pro-toolkit' );

		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-user-times"></i><span class="uo_pro_text">PRO</span>';

		$category = 'learndash';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link,
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => false, // OR
			'icon'             => $class_icon,
		);

	}

	/**
	 *
	 */
	/*public static function admin_enqueue_scripts() {
		global $post;

		if ( 'sfwd-lessons' === $post->post_type ) {
			wp_enqueue_script( stripslashes( __CLASS__ ), plugins_url( 'assets/js/time_limit_lesson_for_group.js', dirname( __FILE__ ) ), array( 'jquery' ) );
		}
	}*/

	/**
	 * @param $post_args
	 *
	 * @return array
	 */
	public static function add_group_access_to_post_args_legacy( $post_args ) {

		if ( class_exists( 'LearnDash_Theme_Register' ) ) {
			return $post_args;
		}

		// Get all groups
		if ( ! is_user_logged_in() ) {
			return $post_args;
		}

		$groups = get_posts( [
			'post_type'      => 'groups',
			'posts_per_page' => 999,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		] );


		// If any group is not exists, this option will be disabled
		if ( ! $groups ) {
			return $post_args;
		}

		// group_selection
		$group_selection = array(
			0     => 'Select a LearnDash Group',
			'all' => 'All Other Users',
		);

		# TODO Show only groups that have access to this lesson
		# Current code is inefficient and will have issues when a lot of groups are set up
		# try recursive to get courses of the lessons and then groups of the courses
//		global $post;
//		$current_lessons = $post->ID;

		foreach ( $groups as $group ) {
			if ( $group && is_object( $group ) ) {

//				$courses = learndash_group_enrolled_courses( $group->ID );
//				foreach ( $courses as $course_id ) {
//					$lessons = learndash_get_course_lessons_list( $course_id );
//					foreach ( $lessons as $lesson ) {
//
//						$group_lesson_id = $lesson['post']->ID;
//
//						if ( $current_lessons === $group_lesson_id ) {
				$group_selection[ $group->ID ] = $group->post_title;
//						}
//					}
//				}
			}
		}

		$new_post_args = array();


		foreach ( $post_args as $key => $val ) {
			// add option on lessons setting
			if ( in_array( $val['post_type'], self::$learndash_post_types, true ) ) {
				$new_post_args[ $key ]           = $val;
				$new_post_args[ $key ]['fields'] = array();

				foreach ( $post_args[ $key ]['fields'] as $key_lessons => $val_lessons ) {
					$new_post_args[ $key ]['fields'][ $key_lessons ] = $val_lessons;

					if ( 'visible_after' === $key_lessons ) {
						$new_post_args[ $key ]['fields']['set_groups_for_dates'] = array(
							'name'            => 'LearnDash Group',
							'type'            => 'select',
							'help_text'       => 'Choose a group for a custom drip date',
							'initial_options' => $group_selection,
						);
					}
				}
			} else {
				$new_post_args[ $key ] = $val;
			}
		}

		return $new_post_args;
	}

	/**
	 * @param $setting
	 *
	 * @return mixed
	 */
	public static function change_lesson_setting( $setting ) {
		// Get the post which are modifying
		global $post;

		foreach ( $setting['sfwd-lessons_set_groups_for_dates']['initial_options'] as $group_id => &$group_name ) {

			if ( ! $group_id ) {
				continue;
			}
			$date = get_post_meta( $post->ID, stripslashes( __CLASS__ ) . '-' . $group_id, true );
			// Add tha ( date ) after group name on selection if exists

			if ( $date ) {
				if ( is_array( $date ) ) {
					$date = self::reformat_date( $date );
					$date = learndash_adjust_date_time_display( $date );
				}
				if ( self::is_timestamp( $date ) ) {
					$date_format = get_option( 'date_format' );
					$time_format = get_option( 'time_format' );
					$date        = self::adjust_for_timezone_difference( $date );
					$date        = date_i18n( "$date_format $time_format", $date );
				}
				$group_name = $group_name . ' &mdash; (' . $date . ')';
			}
		}

		return $setting;
	}

	/**
	 * @param $post_id
	 * @param $post
	 *
	 * @return bool|void
	 * @throws \Exception
	 */
	public static function save_post( $post_id, $post ) {

		// Only use this for legacy learndash
		if ( class_exists( 'LearnDash_Theme_Register' ) ) {
			return;
		}

		// prevent auto saving
		// check user capacity
		// check post type
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return true;
		}
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return true;
		}
		if ( 'sfwd-lessons' !== $post->post_type ) {
			return true;
		}

		// if group was set, save it
		if ( isset( $_POST['sfwd-lessons_set_groups_for_dates'] ) ) {

			$group_id = $_POST['sfwd-lessons_set_groups_for_dates'];
			if ( ! empty( $group_id ) ) {

				$date = self::reformat_date( $_POST['sfwd-lessons_visible_after_specific_date'] );
				if ( 0 === $date ) {
					delete_post_meta( $post_id, stripslashes( __CLASS__ ) . '-' . $group_id );
					self::unset_notifications( $post_id );
				} else {
					update_post_meta( $post_id, stripslashes( __CLASS__ ) . '-' . $group_id, $date );
					self::set_notifications( $post_id );
				}
			}
		}

		// get original options and reset it
		$original_option                                             = get_post_meta( $post_id, '_sfwd-lessons', true );
		$original_date                                               = get_post_meta( $post_id, stripslashes( __CLASS__ ) . '-all', true );
		$original_option['sfwd-lessons_set_groups_for_dates']        = '';
		$original_option['sfwd-lessons_visible_after_specific_date'] = $original_date;

		update_post_meta( $post_id, '_sfwd-lessons', $original_option );

		return true;
	}

	/**
	 * @param $lesson_id
	 *
	 * @throws \Exception
	 */
	public static function set_notifications( $lesson_id ) {
		if ( function_exists( 'learndash_notifications_send_notifications' ) ) {
			$course_id = learndash_get_course_id( $lesson_id );
			//Logic for course builder
			if ( 0 === (int) $course_id && ( isset( $_REQUEST['course_id'] ) ) && ( ! empty( $_REQUEST['course_id'] ) ) ) {
				$course_id = intval( $_GET['course_id'] );
			}

			if ( 0 === (int) $course_id && isset( $_REQUEST['ld-course-switcher'] ) ) {
				preg_match( "/course_id=[^&]*/", $_REQUEST['ld-course-switcher'], $parse_query );
				//Boot::trace_logs( $parse_query, '$parse_query', 'debug-drip' );
				if ( $parse_query ) {
					$course_id = (int) str_replace( 'course_id=', '', $parse_query[0] );
				}
			}

			$group_ids = learndash_get_course_groups( $course_id );
			$user_ids  = [];
			if ( $group_ids ) {
				foreach ( $group_ids as $group_id ) {
					$users = learndash_get_groups_user_ids( $group_id );
					if ( $users ) {
						foreach ( $users as $user_id ) {
							$user_ids[ $user_id ] = $user_id;
						}
					}
				}
			}
			/*Boot::trace_logs( [
				$lesson_id,
				$course_id,
				$group_ids,
				$user_ids,
				//$_REQUEST,
				//$parse_query,
			], '$lesson_id, $course_id, $group_ids, $user_ids, $_REQUEST', 'debug-drip' );*/

			if ( $user_ids ) {
				foreach ( $user_ids as $user_id => $u_id ) {
					learndash_notifications_delete_delayed_emails_by_user_id_lesson_id( $user_id, $lesson_id );

					delete_user_meta( $user_id, 'ld_sent_notification_lesson_available_' . $lesson_id );
					delete_user_meta( $user_id, 'uo_ld_sent_notification_lesson_available_' . $lesson_id );

					$lesson_access_from = self::ld_lesson_access_group( $lesson_id, $user_id, $course_id );
					if ( ! is_null( $lesson_access_from ) ) {
						//learndash_notifications_send_notifications( 'lesson_available', $user_id, $course_id, $lesson_id, null, null, null, $lesson_access_from );
						self::manually_set_notification( $user_id, $course_id, $lesson_id, $lesson_access_from );
						update_user_meta( $user_id, 'uo_ld_sent_notification_lesson_available_' . $lesson_id, $lesson_access_from );
					}
				}
			}
		}
	}

	/**
	 * @param $lesson_id
	 *
	 * @throws \Exception
	 */
	public static function unset_notifications( $lesson_id ) {
		if ( function_exists( 'learndash_notifications_send_notifications' ) ) {
			$course_id = learndash_get_course_id( $lesson_id );
			if ( 0 === (int) $course_id && isset( $_REQUEST['ld-course-switcher'] ) ) {
				preg_match( "/course_id=[^&]*/", $_REQUEST['ld-course-switcher'], $parse_query );
				//Boot::trace_logs( $parse_query, '$parse_query', 'debug-drip' );
				if ( $parse_query ) {
					$course_id = (int) str_replace( 'course_id=', '', $parse_query[0] );
				}
			}
			$group_ids = learndash_get_course_groups( $course_id );
			$user_ids  = [];
			if ( $group_ids ) {
				foreach ( $group_ids as $group_id ) {
					$users = learndash_get_groups_user_ids( $group_id );
					if ( $users ) {
						foreach ( $users as $user_id ) {
							$user_ids[ $user_id ] = $user_id;
						}
					}
				}
			}

			if ( $user_ids ) {
				foreach ( $user_ids as $user_id => $u_id ) {
					learndash_notifications_delete_delayed_emails_by_user_id_lesson_id( $user_id, $lesson_id );
					delete_user_meta( $user_id, 'ld_sent_notification_lesson_available_' . $lesson_id );
					delete_user_meta( $user_id, 'uo_ld_sent_notification_lesson_available_' . $lesson_id );
				}
			}
		}
	}

	/**
	 * @param $user_id
	 * @param $course_id
	 * @param $lesson_id
	 * @param $lesson_access_from
	 */
	public static function manually_set_notification( $user_id, $course_id, $lesson_id, $lesson_access_from ) {
		$notifications = learndash_notifications_get_notifications( 'lesson_available' );
		foreach ( $notifications as $n ) {
			self::insert_delayed_notification( $n, $user_id, $course_id, $lesson_id, $lesson_access_from );
		}
	}

	/**
	 * @param $notification
	 * @param $user_id
	 * @param $course_id
	 * @param $lesson_id
	 * @param $lesson_access_from
	 */
	public static function insert_delayed_notification( $notification, $user_id, $course_id, $lesson_id, $lesson_access_from ) {

		// Get recipient
		$recipients = learndash_notifications_get_recipients( $notification->ID );

		// If notification doesn't have recipient, exit
		if ( empty( $recipients ) ) {
			return;
		}

		// Get recipients emails
		$emails = learndash_notifications_get_recipients_emails( $recipients, $user_id, $course_id );

		global $ld_notifications_shortcode_data;
		$ld_notifications_shortcode_data = array(
			'user_id'         => $user_id,
			'course_id'       => $course_id,
			'lesson_id'       => $lesson_id,
			'topic_id'        => null,
			'assignment_id'   => null,
			'quiz_id'         => null,
			'question_id'     => null,
			'notification_id' => $notification->ID,
			'group_id'        => null,
		);

		$shortcode_data = $ld_notifications_shortcode_data;
		$bcc            = learndash_notifications_get_bcc( $notification->ID );
		//$update_where   = [];

		/**
		 * Action hook before sending out notification or save it to database
		 *
		 * @param array $shortcode_data Notification trigger data that trigger this notification sending
		 */
		do_action( 'learndash_notification_before_send', $shortcode_data );

		if ( isset( $lesson_access_from ) && $lesson_access_from > time() ) {

			$sent_on = $lesson_access_from;

			$data = array(
				'title'          => do_shortcode( $notification->post_title ),
				'message'        => $notification->post_content,
				'recipient'      => maybe_serialize( $emails ),
				'shortcode_data' => maybe_serialize( $shortcode_data ),
				'sent_on'        => $sent_on,
				'bcc'            => maybe_serialize( $bcc ),
			);

			global $wpdb;
			$wpdb->query( "INSERT INTO {$wpdb->prefix}ld_notifications_delayed_emails (title, message, recipient, shortcode_data, sent_on, bcc)	VALUES ( '{$notification->post_title}', '{$notification->post_content}', '" . maybe_serialize( $emails ) . "', '" . maybe_serialize( $shortcode_data ) . "', '{$sent_on}', '" . maybe_serialize( $bcc ) . "')" );
		}

	}

	/**
	 * @param $date
	 *
	 * @return array|false|int
	 */
	public static function reformat_date( $date ) {
		if ( is_array( $date ) ) {
			if ( isset( $date['aa'] ) ) {
				$date['aa'] = intval( $date['aa'] );
			} else {
				$date['aa'] = 0;
			}

			if ( isset( $date['mm'] ) ) {
				$date['mm'] = intval( $date['mm'] );
			} else {
				$date['mm'] = 0;
			}

			if ( isset( $date['jj'] ) ) {
				$date['jj'] = intval( $date['jj'] );
			} else {
				$date['jj'] = 0;
			}

			if ( isset( $date['hh'] ) ) {
				$date['hh'] = intval( $date['hh'] );
			} else {
				$date['hh'] = 0;
			}

			if ( isset( $date['mn'] ) ) {
				$date['mn'] = intval( $date['mn'] );
			} else {
				$date['mn'] = 0;
			}

			if ( ( ! empty( $date['aa'] ) ) && ( ! empty( $date['mm'] ) ) && ( ! empty( $date['jj'] ) ) ) {

				$date_string = sprintf( '%04d-%02d-%02d %02d:%02d:00', intval( $date['aa'] ), intval( $date['mm'] ), intval( $date['jj'] ), intval( $date['hh'] ), intval( $date['mn'] ) );
				$gmt_offset  = get_option( 'gmt_offset' );
				if ( empty( $gmt_offset ) ) {
					$gmt_offset = 0;
				}

				//get ms difference for time offset from GMT
				//could be +ve or -ve depending on timezone
				//If GMT offset is +ve, subtract from time to get time in GMT since user is ahead of GMT
				//If GMT offset is -ve, add time to get GMT time since user is behind GMT
				//-1 is the logic to add/subtract offset time to implement above two line logic
				$offset      = ( $gmt_offset * ( 60 * 60 ) ) * - 1; //MS difference for time offset
				$return_time = (int) strtotime( $date_string ) + $offset;

				return $return_time;
			} else {
				return 0;
			}
		} else {
			return $date;
		}
	}

	/**
	 * @param $time
	 *
	 * @return int
	 */
	public static function adjust_for_timezone_difference( $time ) {
		$gmt_offset = get_option( 'gmt_offset' );
		if ( empty( $gmt_offset ) ) {
			$gmt_offset = 0;
		}
		//get ms difference for time offset from GMT
		//could be +ve of -ve depending on timezone
		$offset = $gmt_offset * ( 60 * 60 );

		return (int) $time + $offset;
	}

	# Change the template as one in template dir of this plugin

	/**
	 * @param $filepath
	 * @param $name
	 * @param $args
	 * @param $echo
	 * @param $return_file_path
	 *
	 * @return string
	 */
	public static function learndash_template( $filepath, $name, $args, $echo, $return_file_path ) {

		if ( 'course' === $name ) {
			if ( ! class_exists( 'LearnDash_Theme_Register' ) ||
			     (
				     class_exists( 'LearnDash_Theme_Register' ) &&
				     'legacy' === \LearnDash_Theme_Register::get_active_theme_key()
			     )
			) {
				$filepath = self::get_template( 'drip-template_legacy.php', dirname( dirname( __FILE__ ) ) . '/src' );
				$filepath = apply_filters( 'uo_drip_template', $filepath );
			}


		}

		if ( 'learndash_course_lesson_not_available' === $name ) {

			if ( ! class_exists( 'LearnDash_Theme_Register' ) ||
			     (
				     class_exists( 'LearnDash_Theme_Register' ) &&
				     'legacy' === \LearnDash_Theme_Register::get_active_theme_key()
			     )
			) {
				$filepath = self::get_template( 'learndash_course_lesson_not_available_legacy.php', dirname( dirname( __FILE__ ) ) . '/src' );
				$filepath = apply_filters( 'uo_learndash_course_lesson_not_available', $filepath );

			}

		}

		return $filepath;
	}

	# Access Permission Change for Single Page

	/**
	 * @param $content
	 * @param $post
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function lesson_visible_after( $content, $post ) {
		if ( empty( $post->post_type ) ) {
			return $content;
		}

		$user = wp_get_current_user();
		if ( in_array( 'administrator', $user->roles ) ) {
			return $content;
		}
		$uncanny_active_classes = get_option( 'uncanny_toolkit_active_classes', '' );
		if ( ! empty( $uncanny_active_classes ) ) {
			if ( key_exists( 'uncanny_pro_toolkit\GroupLeaderAccess', $uncanny_active_classes ) ) {
				$course_id         = learndash_get_course_id( $post->ID );
				$get_course_groups = learndash_get_course_groups( $course_id );
				$groups_of_leader  = learndash_get_administrators_group_ids( $user->ID );
				$matching          = array_intersect( $groups_of_leader, $get_course_groups );
				if ( in_array( 'group_leader', $user->roles ) && ! empty( $matching ) ) {
					return $content;
				}
			}
		}


		if ( 'sfwd-lessons' === $post->post_type ) {
			$lesson_id = $post->ID;
		} elseif ( 'sfwd-topic' === $post->post_type || 'sfwd-quiz' === $post->post_type ) {
			$lesson_id = learndash_get_setting( $post, 'lesson' );
			if ( empty( $lesson_id ) ) {
				return $content;
			}
		} else {
			return $content;
		}

		// Compare Two of Dates and return minimum value
		$lesson_access_from = self::get_lesson_access_from( $lesson_id, $user->ID );

		if ( 'Available' === $lesson_access_from || empty ( $lesson_access_from ) ) {
			return $content;
		} elseif ( $lesson_access_from > time() ) {
			$content     = sprintf( __( 'Available on: %s', 'uncanny-pro-toolkit' ), learndash_adjust_date_time_display( $lesson_access_from ) . ' <br><br>' );
			$course_id   = learndash_get_course_id( $lesson_id );
			$course_link = get_permalink( $course_id );
			$content     .= '<a href="' . esc_url( $course_link ) . '">' . esc_html__( 'Return to Course Overview', 'uncanny-pro-toolkit' ) . '</a>';

			return '<div class=\'notavailable_message\'>' . apply_filters( 'leardash_lesson_available_from_text', $content, $post, $lesson_access_from ) . '</div>';
		}

		return $content;
	}

	# Access Permission for user's group

	/**
	 * @param $lesson_id
	 * @param $user_id
	 *
	 * @param bool $course_id
	 *
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	private static function ld_lesson_access_group( $lesson_id, $user_id, $course_id = false ) {
		if ( false === $course_id ) {
			$course_id = learndash_get_course_id( $lesson_id );
		}
		$user_groups = learndash_get_users_group_ids( $user_id );
		//Boot::trace_logs( [ $lesson_id, $user_id, $user_groups ], '$lesson_id, $user_id, $user_groups', 'debug-drip' );
		//No group found, assumption: Available
		if ( empty( $user_groups ) ) {
			$default = get_post_meta( $lesson_id, stripslashes( __CLASS__ ) . '-all', true );
			if ( ! empty( $default ) ) {
				if ( ! self::is_timestamp( $default ) ) {
					return strtotime( $default );
				}

				return $default;
			}
		}

		$group_dates = array();
		foreach ( $user_groups as $group_id ) {
			$date = get_post_meta( $lesson_id, stripslashes( __CLASS__ ) . '-' . $group_id, true );
			if ( ! empty( $date ) ) {
				//echo self::attempt_to_unix( $date );
				if ( self::is_timestamp( $date ) ) {
					$group_dates[ $group_id ] = $date;
				} else {
					$group_dates[ $group_id ] = strtotime( $date );
				}
			}
		}
		/*Boot::trace_logs( [
			$group_dates,
			stripslashes( __CLASS__ ) . '-' . $group_id
		], '$group_dates, stripslashes( __CLASS__ ) . \'-\' . $group_id', 'debug-drip' );*/

		//Array contains Group Dates!
		asort( $group_dates );
		$gmt_date_time = new \DateTime();
		$gmt_date_time->setTimezone( new \DateTimeZone( 'GMT' ) );
		$time_now = strtotime( $gmt_date_time->format( 'Y-m-d H:i:s' ) );
		//$time_now =current_time('timestamp');
		$return = false;
		if ( ! empty( $group_dates ) ) {
			foreach ( $user_groups as $group_id ) {

				if ( ! empty( $group_dates[ $group_id ] ) && learndash_group_has_course( $group_id, $course_id ) ) {

					if ( absint( $time_now ) < absint( $group_dates[ $group_id ] ) ) {
						$return = false;
					} elseif ( absint( $time_now ) >= absint( $group_dates[ $group_id ] ) ) {
						return 'Available';
					}
				}
			}
		} else {
			//No Group Dates found
			$default = get_post_meta( $lesson_id, stripslashes( __CLASS__ ) . '-all', true );
			if ( ! empty( $default ) ) {
				if ( ! self::is_timestamp( $default ) ) {
					return strtotime( $default );
				}

				return $default;
			}

			return 'Available';
		}

		if ( false === $return ) {
			foreach ( $group_dates as $group_id => $date ) {
				if ( learndash_group_has_course( $group_id, $course_id ) ) {
					return $date;
				}
			}
		}

		return false;
	}

	# It will use in the course template so I put this on here as public method

	/**
	 * @param $lesson_id
	 * @param $user_id
	 *
	 * @return bool|int|mixed|string
	 * @throws \Exception
	 */
	public static function get_lesson_access_from( $lesson_id, $user_id ) {
		$lesson_access_from = ld_lesson_access_from( $lesson_id, $user_id );
		// Check Group Access As Well
		$lesson_access_group = self::ld_lesson_access_group( $lesson_id, $user_id );
		$return              = 'Available';
		if ( ! empty( $lesson_access_group ) && 'Available' !== $lesson_access_group ) {
			if ( $lesson_access_group > time() ) {
				$return = $lesson_access_group;
			}
		}

		// Compare Two of Them without null, and return maximum value
		if ( ! empty( $lesson_access_from ) ) {
			$return = $lesson_access_from;
		}

		return $return;
	}

	/**
	 * @param $timestamp
	 *
	 * @return bool
	 */
	public static function is_timestamp( $timestamp ) {
		if ( is_numeric( $timestamp ) && strtotime( date( 'd-m-Y H:i:s', $timestamp ) ) === (int) $timestamp ) {
			return $timestamp;
		} else {
			return false;
		}
	}


	/**
	 *
	 */
	public static function reformat_date_to_unix() {
		if ( 'no' === get_option( 'group_drip_date_modified_to_unix', 'no' ) ) {
			global $wpdb;
			$groups = $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE meta_key LIKE '" . stripslashes( __CLASS__ ) . "%'" );
			// If any group is not exists, this option will be disabled
			if ( ! empty( $groups ) ) {
				// group_selection
				foreach ( $groups as $group ) {
					$post_id      = $group->post_id;
					$key          = $group->meta_key;
					$current_date = $group->meta_value;
					if ( ! empty( $current_date ) && 0 !== $current_date ) {
						if ( false === self::is_timestamp( $current_date ) ) {
							//attempt to convert to unix timestamp
							if ( is_array( maybe_unserialize( $current_date ) ) ) {
								$date_format  = get_option( 'date_format' );
								$time_format  = get_option( 'time_format' );
								$current_date = date( "$date_format $time_format", self::reformat_date( $current_date ) );
							}
							$unix_time = self::attempt_to_unix( $current_date );
							if ( false !== $unix_time ) {
								//DateTime was able to convert it to unix time, all good
								update_post_meta( $post_id, $key, $unix_time );
								$bak = str_replace( stripslashes( __CLASS__ ), 'bak-UncannyDripLessonsByGroup', $key );
								update_post_meta( $post_id, $bak, $current_date ); //keep a backup, Just-in-case
							}
						}
					}
				}
			}
			update_option( 'group_drip_date_modified_to_unix', 'yes' );
		}
	}

	/**
	 * @param $date
	 *
	 * @return bool
	 */
	public static function attempt_to_unix(
		$date
	) {
		try {
			$date = new \DateTime( $date );

			return $date->getTimestamp();
		} catch ( \Exception $e ) {
			return false;
		}
	}
}