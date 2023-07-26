<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Sample
 * @package uncanny_pro_toolkit
 */
class CourseTimer extends toolkit\Config implements toolkit\RequiredFunctions {

	public static $timed_post_types = array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz' );

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'rest_api_init' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {

			// Add timer idle setting to LearnDash Lessons, Topics, amd Quizzes
			add_filter( 'learndash_post_args', array( __CLASS__, 'add_idle_time_to_post_args' ) );

			// Add minimum Course Time to quiz settings
			add_filter( 'learndash_post_args', array( __CLASS__, 'add_min_course_time_to_post_args' ) );

			$minimum_course_time_before_quiz_access = self::get_settings_value( 'minimum_course_time_before_quiz_access', __CLASS__ );

			if ( 'on' === $minimum_course_time_before_quiz_access ) {
				// Filter quiz content
				add_filter( 'the_content', array( __CLASS__, 'filter_quiz_content' ) );
			}

			// Enqueue script for timer functionality
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_timer_scripts' ) );

			// Return time elapse
			add_shortcode( 'uo_time', array( __CLASS__, 'shortcode_uo_time' ) );

			// Return time elapse
			add_shortcode( 'uo_time_course_completed', array( __CLASS__, 'shortcode_uo_time_course_completed' ) );

			// LD overhauled the course reports, there is now two version. One s before v2.3 upgrade and one after.
			// Validate that data upgrade completed
			$data_upgrade = get_option( 'learndash_data_settings', array() );

			// Data was upgraded
			if ( isset( $data_upgrade['user-meta-courses']['version'] ) ) {

				// Filter Report Headers which sets up the data values
				add_filter( 'learndash_data_reports_headers', array(
					__CLASS__,
					'course_export_upgraded_headers_filter'
				), 10, 2 );


			} else {

				// Data wasn't upgraded

				//There is now a filter for LearnDashMenu => Report
				add_filter( 'course_export_data', array( __CLASS__, 'course_export_data_filter' ), 10, 1 );

			}


			//  Store timer when course completion get triggered
			add_action( 'learndash_course_completed', array( __CLASS__, 'uo_course_completed_store_timer' ) );

			// Delete timer data when user LearnDash data is reset
			add_action( 'personal_options_update', array( __CLASS__, 'learndash_delete_user_data' ) );
			add_action( 'edit_user_profile_update', array( __CLASS__, 'learndash_delete_user_data' ) );
			if ( isset( $_GET['page'] ) && 'uncanny-toolkit' === $_GET['page'] ) {
				add_action( 'admin_head', array( __CLASS__, 'uo_timer_interval_convert_text_to_number' ) );
			}

			// Tincanny Integration
			add_filter( 'tc_api_get_courses_overview', array( __CLASS__, 'tc_api_get_courses_overview' ), 10, 1 );
			add_filter( 'tc_api_get_courseSingleTable', array( __CLASS__, 'tc_api_get_courseSingleTable' ), 10, 3 );
			add_filter( 'tc_api_get_userSingleCoursesOverviewTable', array(
				__CLASS__,
				'tc_api_get_userSingleCoursesOverviewTable'
			), 10, 3 );
			add_filter( 'tc_api_get_userSingleCourseProgressSummaryTable', array(
				__CLASS__,
				'tc_api_get_userSingleCourseProgressSummaryTable'
			), 10, 3 );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_tc_timer_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'add_tc_timer_scripts_admin' ) );
		}
	}

	public static function tc_api_get_userSingleCourseProgressSummaryTable( $json_return, $user_id, $course_id ) {

		global $wpdb;

		//Users' Completed Timers
		$sql_string                          = "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'course_timer_completed_{$course_id}' AND user_id = {$user_id}";
		$timer                               = $wpdb->get_var( $sql_string );
		$json_return['data']['timeComplete'] = $timer;

		//Users' Time Spent
		$sql_string             = "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key LIKE 'uo_timer_{$course_id}%' AND user_id = {$user_id}";
		$times_spent            = $wpdb->get_results( $sql_string );
		$times_spent_rearranged = 0;
		foreach ( $times_spent as $time_spent ) {
			$time    = $time_spent->meta_value;
			$seconds = (int) $time;

			$times_spent_rearranged += $seconds;
		}

		$json_return['data']['timeSpent'] = self::convert_second_to_time( $times_spent_rearranged );

		return $json_return;
	}

	public static function tc_api_get_userSingleCoursesOverviewTable( $json_return, $user_id, $rows ) {

		global $wpdb;

		//Users' Completed Timers
		$sql_string       = "SELECT meta_value, meta_key FROM $wpdb->usermeta WHERE meta_key LIKE 'course_timer_completed_%' AND user_id = {$user_id}";
		$timers           = $wpdb->get_results( $sql_string );
		$timer_rearranged = [];
		foreach ( $timers as $timer ) {
			$timer_key = $timer->meta_key;
			$timer_key = explode( '_', $timer_key );
			$time      = $timer->meta_value;
			$seconds   = 0;
			if ( '' !== $time ) {
				$seconds = explode( ':', $time );
				$seconds = ( intval( $seconds[0] ) * 60 * 60 ) + ( intval( $seconds[1] ) * 60 ) + intval( $seconds[2] );
			}

			$timer_rearranged[ (int) $timer_key[3] ] = $seconds;
		}

		//Users' Time Spent
		$sql_string             = "SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE meta_key LIKE 'uo_timer_%' AND user_id = {$user_id}";
		$times_spent            = $wpdb->get_results( $sql_string );
		$times_spent_rearranged = [];
		foreach ( $times_spent as $time_spent ) {

			$time_spent_key = $time_spent->meta_key;
			$time_key       = explode( '_', $time_spent_key );
			$time           = $time_spent->meta_value;
			$seconds        = (int) $time;

			if ( isset( $times_spent_rearranged[ (int) $time_key[2] ] ) ) {
				$times_spent_rearranged[ (int) $time_key[2] ] = $times_spent_rearranged[ (int) $time_key[2] ] + $seconds;
			} else {
				$times_spent_rearranged[ (int) $time_key[2] ] = $seconds;
			}
		}

		foreach ( $json_return['data'] as $row_id => &$row ) {
			$row['timeComplete'] = ( isset( $timer_rearranged[ $row['course_id'] ] ) ) ? self::convert_second_to_time( $timer_rearranged[ $row['course_id'] ] ) : '---';
			$row['timeSpent']    = ( isset( $times_spent_rearranged[ $row['course_id'] ] ) ) ? self::convert_second_to_time( $times_spent_rearranged[ $row['course_id'] ] ) : '---';
		}

		return $json_return;
	}

	public static function tc_api_get_courseSingleTable( $json_return, $course_id, $rows ) {

		global $wpdb;

		//Users' Completed Timers
		$sql_string       = "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key = 'course_timer_completed_{$course_id}'";
		$timers           = $wpdb->get_results( $sql_string );
		$timer_rearranged = [];
		foreach ( $timers as $timer ) {

			$user_id = (int) $timer->user_id;
			$time    = $timer->meta_value;
			$seconds = 0;
			if ( '' !== $time ) {
				$seconds = explode( ':', $time );
				$seconds = ( intval( $seconds[0] ) * 60 * 60 ) + ( intval( $seconds[1] ) * 60 ) + intval( $seconds[2] );
			}

			$timer_rearranged[ $user_id ] = $seconds;
		}

		//Users' Time Spent
		$sql_string             = "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key LIKE 'uo_timer_{$course_id}%'";
		$times_spent            = $wpdb->get_results( $sql_string );
		$times_spent_rearranged = [];
		foreach ( $times_spent as $time_spent ) {

			$user_id = (int) $time_spent->user_id;
			$time    = $time_spent->meta_value;
			$seconds = (int) $time;

			if ( isset( $times_spent_rearranged[ $user_id ] ) ) {
				$times_spent_rearranged[ $user_id ] = $times_spent_rearranged[ $user_id ] + $seconds;
			} else {
				$times_spent_rearranged[ $user_id ] = $seconds;
			}
		}

		foreach ( $json_return['data'] as $row_id => &$row ) {
			$row['timeComplete'] = ( isset( $timer_rearranged[ $row['user_id'] ] ) ) ? self::convert_second_to_time( $timer_rearranged[ $row['user_id'] ] ) : '---';
			$row['timeSpent']    = ( isset( $times_spent_rearranged[ $row['user_id'] ] ) ) ? self::convert_second_to_time( $times_spent_rearranged[ $row['user_id'] ] ) : '---';
		}

		return $json_return;
	}

	public static function tc_api_get_courses_overview( $return ) {


		global $wpdb;

		//Users' Completed Timers
		$sql_string       = "SELECT user_id, meta_key, meta_value FROM $wpdb->usermeta WHERE meta_key LIKE 'course_timer_completed_%'";
		$timers           = $wpdb->get_results( $sql_string );
		$timer_rearranged = [];
		foreach ( $timers as $timer ) {

			$user_id   = (int) $timer->user_id;
			$timer_key = $timer->meta_key;
			$timer_key = explode( '_', $timer_key );
			$time      = $timer->meta_value;
			$seconds   = 0;
			if ( '' !== $time ) {
				$seconds = explode( ':', $time );
				$seconds = ( intval( $seconds[0] ) * 60 * 60 ) + ( intval( $seconds[1] ) * 60 ) + intval( $seconds[2] );
			}

			$timer_rearranged[ $user_id ][ (int) $timer_key[3] ] = $seconds;
		}

		//Users' Time Spent
		$sql_string             = "SELECT user_id, meta_key, meta_value FROM $wpdb->usermeta WHERE meta_key LIKE 'uo_timer_%'";
		$times_spent            = $wpdb->get_results( $sql_string );
		$times_spent_rearranged = [];
		foreach ( $times_spent as $time_spent ) {

			$user_id        = (int) $time_spent->user_id;
			$time_spent_key = $time_spent->meta_key;
			$time_key       = explode( '_', $time_spent_key );
			$time           = $time_spent->meta_value;
			$seconds        = (int) $time;

			if ( isset( $times_spent_rearranged[ $user_id ][ (int) $time_key[2] ] ) ) {
				$times_spent_rearranged[ $user_id ][ (int) $time_key[2] ] = $times_spent_rearranged[ $user_id ][ (int) $time_key[2] ] + $seconds;
			} else {
				$times_spent_rearranged[ $user_id ][ (int) $time_key[2] ] = $seconds;
			}
		}

		$course_avg_time_complete = [];
		$course_avg_time_spent    = [];

		foreach ( $return['data']['courseList'] as $course_id => $course ) {
			$user_access = $return['data']['userList']['course_access_list'][ $course_id ];
			foreach ( $user_access as $user_id ) {

				if ( isset( $times_spent_rearranged[ $user_id ][ $course_id ] ) ) {
					$course_avg_time_spent[ $course_id ][] = $times_spent_rearranged[ $user_id ][ $course_id ];
				}
				if ( isset( $timer_rearranged[ $user_id ][ $course_id ] ) ) {
					$course_avg_time_complete[ $course_id ][] = $timer_rearranged[ $user_id ][ $course_id ];
				}
			}

			if ( empty( $course_avg_time_complete[ $course_id ] ) ) {
				$avg_seconds = '---';
			} else {
				$avg_seconds = array_sum( $course_avg_time_complete[ $course_id ] ) / count( $course_avg_time_complete[ $course_id ] );
				$avg_seconds = sprintf( '%02d:%02d:%02d', ( $avg_seconds / 3600 ), ( $avg_seconds / 60 % 60 ), $avg_seconds % 60 );
			}

			$return['additionalData'][ $course_id ]['avgTimeComplete'] = $avg_seconds;

			if ( empty( $course_avg_time_spent[ $course_id ] ) ) {
				$avg_seconds = '---';
			} else {
				$avg_seconds = array_sum( $course_avg_time_spent[ $course_id ] ) / count( $course_avg_time_spent[ $course_id ] );
				$avg_seconds = sprintf( '%02d:%02d:%02d', ( $avg_seconds / 3600 ), ( $avg_seconds / 60 % 60 ), $avg_seconds % 60 );
			}

			$return['additionalData'][ $course_id ]['avgTimeSpent'] = $avg_seconds;
		}


		return $return;
	}

	public static function uo_timer_interval_convert_text_to_number() {
		?>
		<script>jQuery(document).ready(function ($) {
                $('input[name="uo_timer_interval"]').blur(function () {
                    var val = parseInt($(this).val());
                    if (val < 5 || isNaN(val)) {
                        $(this).val(5);
                    }
                })
            })</script><?php
	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Simple Course Timer', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/simple-course-timer/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Tracks time spent in all LearnDash courses and detects when a user is idle. Course completion time and total course time are both added to LearnDash reports. Enables blocking access to quizzes until minimum time spent in course.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-clock-o"></i><span class="uo_pro_text">PRO</span>';

		$category = 'learndash';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			/*'settings' => false, // OR */
			'settings'         => self::get_class_settings( $class_title ),
			'icon'             => $class_icon,
		);

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
	 * HTML for modal to create settings
	 *
	 * @static
	 *
	 * @param $class_title
	 *
	 * @return HTML
	 */
	public static function get_class_settings( $class_title ) {

		// Get pages to populate drop down
		$args = array(
			'sort_order'  => 'asc',
			'sort_column' => 'post_title',
			'post_type'   => 'page',
			'post_status' => 'publish',
		);

		$pages     = get_pages( $args );
		$drop_down = array();
		array_push( $drop_down, array( 'value' => '', 'text' => __( 'Select a Page', 'uncanny-pro-toolkit' ) ) );

		foreach ( $pages as $page ) {
			if ( empty( $page->post_title ) ) {
				$page->post_title = __( '(no title)', 'uncanny-pro-toolkit' );
			}

			array_push( $drop_down, array( 'value' => $page->ID, 'text' => $page->post_title ) );
		}

		// Create options
		$options = array(

			array(
				'type'        => 'text',
				'label'       => 'Idle Time (in seconds)',
				'option_name' => 'default_idle_time',
			),
			array(
				'type'        => 'text',
				'label'       => 'Idle Message',
				'option_name' => 'timed_out_message',
			),
			array(
				'type'        => 'text',
				'label'       => 'Active Button Label',
				'option_name' => 'active_button_text',
			),
			array(
				'type'        => 'text',
				'label'       => 'Inactive Button Label',
				'option_name' => 'inactive_button_text',
			),
			array(
				'type'        => 'select',
				'label'       => 'Inactive Redirect',
				'select_name' => 'timed_out_redirect',
				'options'     => $drop_down,
			),
			array(
				'type'        => 'checkbox',
				'label'       => 'Enable Quizzes after X time',
				'option_name' => 'minimum_course_time_before_quiz_access',
			),
			array(
				'type'       => 'html',
				'class'      => 'uo-additional-information',
				'inner_html' => __( '<div style="margin-top: -16px;font-style: italic;">Enable minimum course time before learners can attempt quizzes. Minimum course time value is set in quizzes.</div>', 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'text',
				'label'       => 'Timer Polling Interval(in seconds)',
				'option_name' => 'uo_timer_interval',
			),
			array(
				'type'       => 'html',
				'class'      => 'uo-additional-information',
				'inner_html' => __( '<div style="margin-top: -15px;font-style: italic;">Minimum polling interval is 5 seconds. Default polling interval is 15 seconds.</div>', 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'checkbox',
				'label'       => 'Disable Performance Mode',
				'option_name' => 'disable_performance_timer',
			),
			array(
				'type'       => 'html',
				'class'      => 'uo-additional-information',
				'inner_html' => __( '<div style="margin-top: -13px;font-style: italic;">This method uses a simplified REST API call that reduces load on the server.</div>', 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'checkbox',
				'label'       => 'Enable Debug Mode',
				'option_name' => 'enable_debug_mode',
			),

		);

		// Build html
		$html = self::settings_output( array(
			'class'   => __CLASS__,
			'title'   => $class_title,
			'options' => $options,
		) );

		return $html;
	}

	/* Add Idle time to LeearnDash Options Meta Box
	 *@param array $post_args array of options from the LearnDash custom post type option meta box
	 */
	public static function add_idle_time_to_post_args( $post_args ) {

		// Push existing and new fields
		$new_post_args = array();

		// Loop through all post arguments
		foreach ( $post_args as $key => $val ) {

			$new_post_args[ $key ] = $val;
			// add option on LD post type settings meta box
			if ( in_array( $val['post_type'], self::$timed_post_types ) ) {
				//Add new field to top
				$new_post_args[ $key ]['fields']['uo_timer_idle'] = array(
					'name'      => __( 'Idle Time(seconds)', 'uncanny-pro-toolkit' ),
					'type'      => 'text',
					'help_text' => __( 'Set the time in seconds until a user is flagged as inactive for time tracking purposes', 'uncanny-pro-toolkit' ),
					'default'   => '0'
				);
			}
		}

		return $new_post_args;
	}

	/* Add minimum Course Time to LeearnDash Options Meta Box
	 * @since 1.3
	 *
	 *@param array $post_args array of options from the LearnDash custom post type option meta box
	 */
	public static function add_min_course_time_to_post_args( $post_args ) {

		$minimum_course_time_before_quiz_access = self::get_settings_value( 'minimum_course_time_before_quiz_access', __CLASS__ );

		if ( 'on' === $minimum_course_time_before_quiz_access ) {

			// Push existing and new fields
			$new_post_args = array();

			// Loop through all post arguments
			foreach ( $post_args as $key => $val ) {

				$new_post_args[ $key ]           = $val;

				// add option on LD post type settings meta box
				if ( 'sfwd-quiz' === $val['post_type'] ) {

					//Add new field to top
					$new_post_args[ $key ]['fields']['minimum_course_time_before_quiz_access'] = array(
						'name'      => __( 'Minimum Course Time (in minutes)', 'uncanny-pro-toolkit' ),
						'type'      => 'text',
						'help_text' => __( 'Only allow access to the quiz when the user\'s time spent in the course EQUALS OR EXCEEDS this value in minutes.', 'uncanny-pro-toolkit' ),
						'default'   => '0'
					);
				}
			}

			return $new_post_args;

		} else {
			return $post_args;
		}

	}

	public static function filter_quiz_content( $content ) {

		$current_post = $GLOBALS['post'];

		if ( 'sfwd-quiz' !== $current_post->post_type ) {
			return $content;
		}

		global $wpdb;

		$user_ID = get_current_user_id();

		$timer_results = self::get_course_time_in_seconds( $current_post->ID, $user_ID );

		if ( $timer_results ) {
			$current_minutes = floor( $timer_results / 60 );
		} else {
			$current_minutes = 0;
		}

		$post_options = learndash_get_setting( $current_post );

		if ( ! isset( $post_options['minimum_course_time_before_quiz_access'] ) || 0 === (int) $post_options['minimum_course_time_before_quiz_access'] ) {
			return $content;
		}

		$current_post = $GLOBALS['post'];

		$quiz_post_html_id = 'post-' . $current_post->ID;

		$ld_quiz_post_html_class = 'learndash_post_' . $current_post->ID;

		$min_minutes = $post_options['minimum_course_time_before_quiz_access'];

		if ( $min_minutes <= $current_minutes ) {
			return $content;
		}

		$course_id = learndash_get_course_id();

		if ( ! $course_id ) {
			$lesson_id = learndash_get_lesson_id();
			$course_id = learndash_get_course_id( $lesson_id );
		}

		$course_name = get_the_title( $course_id );
		$permalink   = esc_url( get_permalink( $course_id ) );


		$course_link = '<a href="' . $permalink . '">' . $course_name . '</a>';

		$message = '<p>You must spend ' . $min_minutes . ' minutes in the course before you can attempt the quiz. You have currently spent ' . $current_minutes . ' minutes. Please go back and review course material before attempting the quiz.</p>';
		$message .= '<p>Return to ' . $course_link . '</p>';

		ob_start();
		?>
		<script>

            var i;

            var lduoQuizContainer = document.getElementById('<?php echo $quiz_post_html_id; ?>');

            if (lduoQuizContainer !== null) {

                lduoQuizContainer.innerHTML = '<?php echo $message; ?>';

            } else {

                // Sometimes theme may forget the id attribute in the article tag ex. Avada... check class
                lduoQuizContainer = document.getElementsByClassName('<?php echo $quiz_post_html_id; ?>');

                // The theme didn't add it as a class either
                if (0 === lduoQuizContainer.length) {
                    // check for a learndash ID that was added recently
                    var ldQuizPostHtmlClass = document.getElementById('<?php echo $ld_quiz_post_html_class; ?>');

                    ldQuizPostHtmlClass.style.display = 'none';

                    jQuery(document).ready(function () {
                        var ldQuizPostHtmlClass = document.getElementById('<?php echo $ld_quiz_post_html_class; ?>');
                        if (null !== ldQuizPostHtmlClass) {
                            ldQuizPostHtmlClass.innerHTML = '<?php echo $message; ?>';
                            ldQuizPostHtmlClass.style.display = 'block';
                        }
                    });

                } else {
                    for (i = 0; i < lduoQuizContainer.length; i++) {
                        lduoQuizContainer[i].innerHTML = '<?php echo $message; ?>';
                    }
                }

            }
		</script>
		<?php

		return ob_get_clean();
	}


	public static function add_timer_scripts() {


		global $post_type;
		global $post;

		if ( ! is_archive() && in_array( $post_type, self::$timed_post_types ) && is_user_logged_in() ) {

			// Set Time out
			$idle_time_out            = 900;
			$feature_time_out_default = self::get_settings_value( 'default_idle_time', __CLASS__ );

			if ( '' !== $feature_time_out_default ) {
				$idle_time_out = (int) $feature_time_out_default;
			}

			$post_options_timeout = learndash_get_setting( $post );

			if ( isset( $post_options_timeout['uo_timer_idle'] ) && 0 !== (int) $post_options_timeout['uo_timer_idle'] ) {
				$idle_time_out = (int) $post_options_timeout['uo_timer_idle'];
			}

			// Set Dialog Text
			$active_button_text         = 'Still Here';
			$feature_active_button_text = self::get_settings_value( 'active_button_text', __CLASS__ );

			if ( '' !== $feature_active_button_text ) {
				$active_button_text = $feature_active_button_text;
			}

			$inactive_button_text         = 'I\'m Done';
			$feature_inactive_button_text = self::get_settings_value( 'inactive_button_text', __CLASS__ );

			if ( '' !== $feature_inactive_button_text ) {
				$inactive_button_text = $feature_inactive_button_text;
			}

			$timed_out_message         = 'Are you still working on this section?';
			$feature_timed_out_message = self::get_settings_value( 'timed_out_message', __CLASS__ );

			if ( '' !== $feature_timed_out_message ) {
				$timed_out_message = $feature_timed_out_message;
			}

			$timed_out_redirect         = home_url();
			$feature_timed_out_redirect = self::get_settings_value( 'timed_out_redirect', __CLASS__ );

			if ( '' !== $feature_timed_out_redirect ) {
				$timed_out_redirect = get_permalink( $feature_timed_out_redirect );
			}

			$disable_performance_timer         = 'true';
			$feature_disable_performance_timer = self::get_settings_value( 'disable_performance_timer', __CLASS__ );

			if ( '' !== $feature_disable_performance_timer ) {
				$disable_performance_timer = 'false';
			}

			$enable_debug_mode         = 'false';
			$feature_enable_debug_mode = self::get_settings_value( 'enable_debug_mode', __CLASS__ );

			if ( '' !== $feature_enable_debug_mode ) {
				$enable_debug_mode = 'true';
			}

			// Dialog Box Style
			$dialog_box_style = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/legacy/frontend/css/jquery.dialogbox.css';
			wp_enqueue_style( 'jquery-dialog-box-style', $dialog_box_style, array(), UNCANNY_TOOLKIT_PRO_VERSION );

			// Dialog Box Script @ http://www.jqueryscript.net/lightbox/Simple-Flexible-jQuery-Dialog-Popup-Plugin-dialogBox.html
			$idle_timer_url = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/legacy/frontend/js/jquery.dialogBox.js';
			wp_enqueue_script( 'jquery-dialog-box', $idle_timer_url, array( 'jquery' ), '0.0.2', true );

			// Throst Idle Timer @ https://github.com/thorst/jquery-idletimer
			$idle_timer_url = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/legacy/frontend/js/idle-timer.js';
			wp_enqueue_script( 'throst-idle-timer', $idle_timer_url, array( 'jquery' ), '1.1.0', true );

			// Timer Script
			$uo_timer_url = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/legacy/frontend/js/uo-timer.js';
			wp_register_script( 'uo-timer-js', $uo_timer_url, array(
				'throst-idle-timer',
				'jquery-dialog-box'
			), UNCANNY_TOOLKIT_PRO_VERSION, true );

			$course_id = learndash_get_course_id( $post->ID );

			if ( '0' === $course_id || '' === $course_id ) {
				$lesson_id = learndash_get_lesson_id( $post->ID, $course_id );
				if ( '0' === $lesson_id || '' === $lesson_id ) {
					$course_id = 1;
				} else {
					$course_id = learndash_get_course_id( $lesson_id );
				}
			}
			if ( 'sfwd-courses' === $post->post_type ) {
				$course_id = $post->ID;
			}

			add_filter( 'nonce_life', array( __CLASS__, 'change_course_timer_nonce_life' ) );
			$nonce = \wp_create_nonce( 'wp_rest' );
			remove_filter( 'nonce_life', array( __CLASS__, 'change_course_timer_nonce_life' ) );

			// Amount of time added every call // JS interval must match
			$timer_interval = self::get_settings_value( 'uo_timer_interval', __CLASS__ );

			//  Set timer default, mininum interval is 5 seconds
			if ( '' === $timer_interval || (int) $timer_interval < 5 ) {
				$timer_interval = 15;
			}

			$localize_data_array = array(
				'courseID'               => $course_id,
				'postID'                 => $post->ID,
				'idleTimeOut'            => $idle_time_out,
				'apiUrl'                 => esc_url_raw( rest_url() . 'uo_pro/v1/add_timer/' ),
				'nonce'                  => \wp_create_nonce( 'wp_rest' ),
				'redirect'               => $timed_out_redirect,
				'inactiveButtonText'     => $inactive_button_text,
				'activeButtonText'       => $active_button_text,
				'timedOutMessage'        => $timed_out_message,
				'uoHeartBeatInterval'    => (int) $timer_interval,
				'enablePerformanceTimer' => $disable_performance_timer,
				'enableDebugMode'        => $enable_debug_mode,
				'performanceApiUrl'      => apply_filters( 'performance_timer_url', plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/includes/simple_timer_performance.php' )


			);

			wp_localize_script( 'uo-timer-js', 'uoTimer', $localize_data_array );
			wp_enqueue_script( 'uo-timer-js' );

		}

	}

	public static function add_tc_timer_scripts() {

		global $post;

		$block_is_on_page = false;
		if ( is_a( $post, 'WP_Post' ) && function_exists( 'parse_blocks' ) ) {
			$blocks = parse_blocks( $post->post_content );
			foreach ( $blocks as $block ) {
				if ( 'tincanny/course-user-report' === $block['blockName'] ) {
					$block_is_on_page = true;
				}
			}
		}

		if ( is_a( $post, 'WP_Post' ) &&
			 ( $block_is_on_page || has_shortcode( $post->post_content, 'tincanny' ) ) ) {

			$plugin_base_url = plugins_url( basename( dirname( UO_FILE ) ) );

			wp_enqueue_script( 'hooks', $plugin_base_url . '/src/assets/legacy/backend/js/hooks.min.js?ver=2.0.4' );

			$script_url = $plugin_base_url . '/src/assets/legacy/backend/js/tincany-timer.js';
			wp_register_script( 'tincany-timer', $script_url, array(), UNCANNY_TOOLKIT_PRO_VERSION, true );
			$tincanny_timer = array(
				'localizedStrings' => self::get_js_localized_strings()
			);

			wp_localize_script( 'tincany-timer', 'tincannyTimer', $tincanny_timer );

			wp_enqueue_script('tincany-timer');
		}
	}

	public static function add_tc_timer_scripts_admin( $hook ) {

		if ( 'toplevel_page_uncanny-learnDash-reporting' === $hook ) {

			global $wp_version;

			$plugin_base_url = plugins_url( basename( dirname( UO_FILE ) ) );


			if ( ! wp_script_is( 'wp-hooks', $list = 'enqueued' ) ) {
				if ( version_compare( $wp_version, '5', '<' ) ) {
					wp_enqueue_script( 'hooks', $plugin_base_url . '/src/assets/legacy/backend/js/hooks.min.js?ver=2.0.4' );
				} else {
					wp_enqueue_script( 'wp-hooks' );
				}
			}

			$script_url = $plugin_base_url . '/src/assets/legacy/backend/js/tincany-timer.js';
			wp_register_script( 'tincany-timer', $script_url, array(), UNCANNY_TOOLKIT_PRO_VERSION, true );
			$tincanny_timer = array(
				'localizedStrings' => self::get_js_localized_strings()
			);

			wp_localize_script( 'tincany-timer', 'tincannyTimer', $tincanny_timer );


			wp_enqueue_script( 'tincany-timer' );
		}
	}

	private static function get_js_localized_strings() {

		/**
		 * listed as they appear in file order under \plugins\tin-canny-learndash-reporting\src\assets\admin\js\scripts\components\*.js
		 */
		return array(
			// tables.js
			'Avg Time To Complete' => __( 'Avg Time To Complete', 'uncanny-pro-toolkit' ),
			'Avg Time Spent'       => __( 'Avg Time Spent', 'uncanny-pro-toolkit' ),
			'Avg Time To Complete' => __( 'Avg Time To Complete', 'uncanny-pro-toolkit' ),
			'Time To Complete'     => __( 'Time To Complete', 'uncanny-pro-toolkit' ),
			'Time Spent'           => __( 'Time Spent', 'uncanny-pro-toolkit' ),
		);
	}


	public static function rest_api_init() {

		// Call to store the answer in DB
		// if a field is text (?P<whatever>\w+), if a field is int (?P<whatever>\d+)
		register_rest_route( 'uo_pro/v1', '/add_timer/(?P<course_ID>\w+)/(?P<post_ID>\w+)', array(
			'methods'  => 'GET',
			'callback' => array( __CLASS__, 'add_timer' )
		) );

	}

	/*
	 * Api call to add seconds spent on a module
	 * @param array $data data parse by api call
	 * @return object success or error
	 */
	public static function add_timer( $data ) {

		error_reporting( 0 );

		$return_object = array();

		$current_user_id = get_current_user_id();

		// validate inputs
		$course_ID = absint( $data['course_ID'] );
		$post_ID   = absint( $data['post_ID'] );

		// if any of the values are 0 then they didn't validate, storage is not possible
		if ( 0 === $course_ID || 0 === $post_ID ) {
			$return_object['success']             = false;
			$return_object['message']             = 'One or more the the fields did not validate as a absolute integer';
			$return_object['fields']['course_ID'] = $data['course_ID'];
			$return_object['fields']['post_ID']   = $data['post_ID'];

			return $return_object;
		}

		$meta_key = 'uo_timer_' . $course_ID . '_' . $post_ID;
		$timer    = get_user_meta( $current_user_id, $meta_key, true );

		//  Set timer default
		if ( '' === $timer ) {
			$timer = 0;
		}

		// Amount of time added every call // JS interval must match
		$timer_interval = self::get_settings_value( 'uo_timer_interval', __CLASS__ );

		//  Set timer default, minimum is 5 seconds
		if ( '' === $timer_interval || (int) $timer_interval < 5 ) {
			$timer_interval = 15;
		}


		$timer += $timer_interval;

		update_user_meta( $current_user_id, $meta_key, $timer );

		do_action( 'uo_course_timer_add_timer', $course_ID, $post_ID, $timer_interval );

		$return_object['success'] = true;
		$return_object['time']    = $timer;

		global $uo_time_pre;
		$time_post                  = microtime( true );
		$exec_time                  = $time_post - $uo_time_pre;
		$return_object['exec_time'] = $exec_time;

		return $return_object;
	}

	/*
	 * Shortcode that displays time spent on course or learndash module
	 *
	 * @param array $attributes Optional wp user id and post id can be passed
	 * @return string Time spent on a single module or all modules in course in formatted seconds
	 */
	public static function shortcode_uo_time( $attributes ) {


		$request = shortcode_atts( array(
			'user-id'   => '',
			'course-id' => ''
		), $attributes );

		if ( '' === $request['user-id'] ) {
			$request['user-id'] = get_current_user_id();
		}

		$user_ID = absint( $request['user-id'] );
		$post_ID = absint( $request['course-id'] );

		if ( '' == $request['course-id'] ) {
			global $post;

			$course_post = get_post( learndash_get_course_id( $post->ID ) );

			return self::get_uo_time( $course_post, $user_ID );

		} elseif ( $post_ID ) {
			$requested_post = get_post( $post_ID );

			return self::get_uo_time( $requested_post, $user_ID );
		}

		return '';
	}

	/*
	 * Shortcode that displays time spent on course or learndash module
	 *
	 * @param array $attributes Optional wp user id and post id can be passed
	 * @return string Time spent on a single module or all modules in course in formatted seconds
	 */
	public static function shortcode_uo_time_course_completed( $attributes ) {

		$request = shortcode_atts( array(
			'user-id'   => '',
			'course-id' => ''
		), $attributes );

		if ( '' === $request['user-id'] ) {
			$request['user-id'] = get_current_user_id();
		}

		$user_ID   = absint( $request['user-id'] );
		$course_ID = absint( $request['course-id'] );


		if ( ! $course_ID ) {
			global $post;

			if ( null !== $post ) {

				if ( 'sfwd-courses' !== $post->post_type ) {
					return '';
				}

				$course_ID = $post->ID;
			}

		} else {

			$post_type = get_post_type( $course_ID );
			if ( 'sfwd-courses' !== $post_type ) {
				return '';
			}
		}

		$meta_key           = 'course_timer_completed_' . $course_ID;
		$time_at_completion = get_user_meta( $user_ID, $meta_key, true );

		return $time_at_completion;

	}

	/*
	 * Get the amount of time it took to complete a course or single module
	 *
	 * @param object $post_object A WP post object
	 * @param int $user_ID WP user ID
	 *
	 * return string Time spent on a single module or all modules on course in formatted seconds
	 */
	public static function get_uo_time( $post_object, $user_ID ) {

		if ( $post_object->post_type == 'sfwd-courses' ) {

			// count all timed sections of course
			$course_cumulative_time = self::get_course_time_in_seconds( $post_object->ID, $user_ID );

			return self::convert_second_to_time( $course_cumulative_time );

		} elseif ( in_array( $post_object->post_type, self::$timed_post_types ) ) {
			// return specific time
			$course_ID = learndash_get_course_id( $post_object->ID );
			$meta_key  = 'uo_timer_' . $course_ID . '_' . $post_object->ID;
			$timer     = get_user_meta( (int) $user_ID, $meta_key, true );

			return self::convert_second_to_time( (int) $timer );

		}

		return '';

	}

	/**
	 * Filter Before LearnDash CVS is loaded
	 *
	 * @param array $content
	 *
	 * @return array $content
	 */
	public static function course_export_data_filter( $content ) {

		// In this peculiar case, the organization of the key value pairs plays a huge role in organization
		// of the csv file. The CEU value can't popped at the end because there will be a variable number of
		// 'lessons completed' columns for each course/user combo. The CEU value must immediately follow
		// the "course_completed_on" column.

		foreach ( $content as &$data ) {
			$user_ID   = (int) $data['user_id'];
			$course_id = (int) $data['course_id'];

			$new_row_data = array();

			// Re-organize array key value pairs so that CEU comes after course_completed_on.
			// If other filters are present and using the same organization they may push CEU after them
			// depending on the order of the filter execution.
			foreach ( $data as $key => $value ) {
				$new_row_data[ $key ] = $value;
				if ( $key === 'course_completed_on' ) {
					$new_row_data['total_time']      = self::get_uo_time( get_post( $course_id ), $user_ID );
					$meta_key                        = 'course_timer_completed_' . $course_id;
					$new_row_data['completion_time'] = get_user_meta( $user_ID, $meta_key, true );
				}
			}

			$data = $new_row_data;
		}

		return $content;
	}


	/**
	 * Filter Headers Before LearnDash CVS is loaded
	 *
	 * The header filter does to things. It creates the CSV heading and defined the function that will return the
	 * value of the column row.
	 *
	 * @param array  $data_headers column definitions
	 * @param string $data_slug    The report type
	 *
	 * @return array $data_headers
	 */
	public static function course_export_upgraded_headers_filter( $data_headers, $data_slug ) {

		if ( 'user-courses' === $data_slug ) {

			error_log( print_r( $data_headers['user_id'], true ), 3, dirname( UO_FILE ) . '/elog2.log' );

			$data_headers['total_time'] = array(
				'label'   => 'total_time',
				'default' => '',
				'display' => [ __CLASS__, 'report_column' ]
			);

			$data_headers['completion_time'] = array(
				'label'   => 'completion_time',
				'default' => '',
				'display' => [ __CLASS__, 'report_column' ]
			);

		}


		return $data_headers;
	}

	/**
	 * This function defines the content value
	 *
	 * The header filter does to things. It creates the CSV heading and defined the function that will return the
	 * value of the column row.
	 *
	 * @param string $column_value The value of the column
	 * @param string $column_key   The key set by $data_headers['sample_key'] @ course_export_upgraded_headers_filter()
	 * @param object $report_item  The LD activity object
	 * @param object $report_user  WP_User object
	 *
	 * @return array $column_value
	 */
	public static function report_column( $column_value = '', $column_key, $report_item, $report_user ) {

		/* ex. $report_item
		stdClass Object (
            [user_id] => 1
            [user_display_name] => admin
            [user_email] => example@example.com
            [post_id] => 1
            [post_title] => Sample Course
            [post_type] => sfwd-courses
            [activity_id] =>
            [activity_type] =>
            [activity_started] =>
            [activity_completed] =>
            [activity_updated] =>
            [activity_status] =>
            [activity_meta] => Array()
        )
		 */

		switch ( $column_key ) {
			case 'total_time':
				if ( $report_user instanceof \WP_User ) {
					$column_value = self::get_uo_time( get_post( $report_item->post_id ), $report_user->ID );
				}
				break;

			case 'completion_time':
				if ( $report_user instanceof \WP_User ) {

					$meta_key     = 'course_timer_completed_' . $report_item->post_id;
					$column_value = get_user_meta( $report_user->ID, $meta_key, true );
				}
				break;

			default:
				break;
		}

		return $column_value;

	}

	public static function uo_course_completed_store_timer( $data ) {

		$user_ID    = $data['user']->ID;
		$course     = $data['course'];
		$meta_key   = 'course_timer_completed_' . $course->ID;
		$meta_value = self::get_uo_time( $course, $user_ID );

		update_user_meta( $user_ID, $meta_key, $meta_value );

	}

	/*
	 * Fetches the sum of all posts associated with the course including the course page
	 *
	 * @param int $course_id The ID of the course
	 * @param int $user_ID The users ID
	 * $return string $total_time Total time in seconds for the course
	 */
	private static function get_course_time_in_seconds( $course_id, $user_ID ) {

		global $wpdb;

		$completed_on_meta_key = 'uo_timer_' . learndash_get_course_id( $course_id ) . '_%';

		$query_results = $wpdb->prepare( "SELECT SUM( meta_value ) FROM $wpdb->usermeta WHERE meta_key LIKE %s AND user_id = %d", $completed_on_meta_key, $user_ID );

		$timer_results = $wpdb->get_var( $query_results );

		return $timer_results;

	}

	/*
	 * Convert Seconds to 00:00:00 (hours : minutes : seconds ) Format
	 *
	 * @param int $seconds time in seconds
	 * $return string $total_time seconds formatted
	 */
	private static function convert_second_to_time( $second ) {


		if ( ! $second ) {
			return '';
		}

		$hours  = floor( $second / 3600 );
		$second -= $hours * 3600;

		$minutes = floor( $second / 60 );
		$second  -= $minutes * 60;

		if ( $hours < 10 ) {
			$hours = '0' . $hours;
		}
		if ( $minutes < 10 ) {
			$minutes = '0' . $minutes;
		}
		if ( $second < 10 ) {
			$second = '0' . $second;
		}

		$total_time = ( $hours ) ? $hours . ':' : '';
		$total_time = ( $minutes ) ? $total_time . $minutes . ':' : $total_time . '00:';
		$total_time = ( $second ) ? $total_time . $second : $total_time . '00';


		return $total_time;
	}

	/**
	 * Delete user data
	 *
	 * @param int $user_id
	 *
	 * @since 2.1.0
	 *
	 */
	public static function learndash_delete_user_data( $user_id ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$user = get_user_by( 'id', $user_id );

		if ( ! empty( $user->ID ) && ! empty( $_POST['learndash_delete_user_data'] ) && $user->ID == $_POST['learndash_delete_user_data'] ) {

			global $wpdb;

			// Delete timer
			$timer_meta_key = 'uo_timer_%';

			$query_results = $wpdb->prepare( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE %s AND user_id = %d", $timer_meta_key, $user_id );

			// Delete time set at user completions
			$wpdb->query( $query_results );

			$completed_timer_meta_key = 'course_timer_completed_%';

			$query_results = $wpdb->prepare( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE %s AND user_id = %d", $completed_timer_meta_key, $user_id );

			$wpdb->query( $query_results );


		}
	}

	/*
	 * Update nonce life for timer rest api calls
	 *
	 * @since 1.2.0
	 *
	 * @return int
	 */
	public static function change_course_timer_nonce_life() {
		// 48 hours in seconds
		return 172800;
	}
}