<?php
/**
 * This file contains a class that is used to setup the LearnDash endpoints.
 *
 * @package learndash-reports-by-wisdmlabs
 */

 require_once 'class-wrld-common-functions.php';
/**
 * Class that sets up all the LearnDash endpoints
 *
 * @author     WisdmLabs
 * @since      1.0.0
 * @subpackage LearnDash API
 */
class WRLD_Course_Time_Tracking extends WRLD_Common_Functions {

	/**
	 * This static contains the number of points being assigned on course completion
	 *
	 * @var    Instance of WRLD_Course_Time_Tracking class
	 * @since  1.0.0
	 * @access private
	 */
	private static $instance = null;

	/**
	 * This static method is used to return a single instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return Object
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * This is a constructor which will be used to initialize required hooks.
	 *
	 * @since  1.0.0
	 * @access private
	 * @see    initHook static method
	 */
	private function __construct() {
	}

	/**
	 * This method is used to add all the WordPress hooks/filters.
	 */
	public function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_tracking_script' ) );// Enqueue Tracking JS.
		add_action( 'wp_ajax_add_time_entry', array( $this, 'add_time_tracking_entry' ) );// Add time entry.
		add_action( 'learndash_course_completed', array( $this, 'save_completion_time' ), 10, 2 );
		add_action( 'learndash_lesson_completed', array( $this, 'save_lesson_completion_time' ), 10, 3 );
		add_action( 'learndash_topic_completed', array( $this, 'save_topic_completion_time' ), 10, 4 );
		add_action( 'learndash_quiz_completed', array( $this, 'save_quiz_completion_time' ), 10, 2 );
	}

	public function save_completion_time( $course_data ) {
		$user   = $course_data['user'];
		$course = $course_data['course'];
		$time   = $this->fetch_user_course_time_spent( $course->ID, $user->ID );
		update_user_meta( $user->ID, 'course_time_' . $course->ID, $time );
	}

	public function save_lesson_completion_time( $lesson_data ) {
		$user   = $lesson_data['user'];
		$course = $lesson_data['course'];
		$lesson = $lesson_data['lesson'];

		$time          = $this->fetch_user_module_time_spent( $lesson->ID, $course->ID, $user->ID );
		$all_module_id = learndash_course_get_children_of_step( $course->ID, $lesson->ID, '', 'ids', true );
		update_user_meta( $user->ID, 'lesson_time_bk_' . $lesson->ID, $time );
		if ( ! empty( $all_module_id ) ) {
			foreach ( $all_module_id as $module_id ) {
				$time += $this->fetch_user_module_time_spent( $module_id, $course->ID, $user->ID );
			}
		}
		update_user_meta( $user->ID, 'lesson_time_' . $lesson->ID, $time );
	}

	public function save_topic_completion_time( $topic_data ) {
		$user   = $topic_data['user'];
		$course = $topic_data['course'];
		$topic  = $topic_data['topic'];

		$time          = (int) $this->fetch_user_module_time_spent( $topic->ID, $course->ID, $user->ID );
		$all_module_id = learndash_course_get_children_of_step( $course->ID, $topic->ID, '', 'ids', true );
		update_user_meta( $user->ID, 'topic_time_bk_' . $topic->ID, $time );
		if ( ! empty( $all_module_id ) ) {
			foreach ( $all_module_id as $module_id ) {
				$time += (int) $this->fetch_user_module_time_spent( $module_id, $course->ID, $user->ID );
			}
		}
		update_user_meta( $user->ID, 'topic_time_' . $topic->ID, $time );
	}

	public function save_quiz_completion_time( $quizdata, $user ) {
		$time = $this->fetch_user_module_time_spent( $quizdata['quiz'], $quizdata['course']->ID, $user->ID );
		update_user_meta( $user->ID, 'quiz_time_' . $quizdata['quiz'], $time );
	}

	/**
	 * Enqueue the script on course/lesson/topic which tracks the amount of time spent on a particular module by a student.
	 *
	 * @return void
	 */
	public function enqueue_tracking_script() {
		global $post;

		if ( ! is_singular( array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz' ) ) ) {
			return;
		}

		$min = '.min';
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$min = '';
		}

		if ( ! is_user_logged_in() || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			return;
		}

		$course_id = learndash_get_course_id( $post->ID );
		$user_id   = get_current_user_id();
		$meta      = learndash_get_setting( $course_id );

		if ( ( isset( $meta['course_price_type'] ) ) && ( 'open' === $meta['course_price_type'] ) ) {
			return;
		}

		if ( ! sfwd_lms_has_access( $course_id, $user_id ) ) {
			return;
		}

		wp_enqueue_script( 'wrld_time_tracking_script', plugins_url( 'assets/js/time-tracking/index.js', WRLD_REPORTS_FILE ), array( 'jquery' ), WRLD_PLUGIN_VERSION, true );

		wp_localize_script(
			'wrld_time_tracking_script',
			'page_info',
			array(
				'post_id'     => $post->ID,
				'course_id'   => $course_id,
				'user_id'     => $user_id,
				'is_enrolled' => sfwd_lms_has_access( $course_id, $user_id ),
				'security'    => wp_create_nonce( 'add-course-time' ),
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
			)
		);

		wp_localize_script(
			'wrld_time_tracking_script',
			'settings',
			array(
				'status'   => get_option( 'wrld_time_tracking_status', 'on' ),
				'timer'    => get_option( 'wrld_time_tracking_timer', 600 ),
				'message'  => get_option( 'wrld_time_tracking_message', __( 'Are you still on this page?', 'learndash-reports-by-wisdmlabs' ) ),
				'btnlabel' => get_option( 'wrld_time_tracking_btnlabel', 'Yes' ),
			)
		);
	}

	/**
	 * This method adds an timespent entry for a user on a particular course.
	 */
	public function add_time_tracking_entry() {
		global $wpdb;
		$user_id          = filter_input( INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT );
		$post_id          = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
		$course_id        = filter_input( INPUT_POST, 'course_id', FILTER_SANITIZE_NUMBER_INT );
		$activity_updated = filter_input( INPUT_POST, 'time', FILTER_VALIDATE_INT );
		$time_spent       = filter_input( INPUT_POST, 'total_time', FILTER_VALIDATE_INT );
		// $ip_address       = $this->get_user_ip_address();
		$nonce = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_STRING );

		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'add-course-time' ) ) {
			wp_send_json_error();
			die();
		}
		if ( empty( $post_id ) || empty( $course_id ) || empty( $time_spent ) ) {
			wp_send_json_error();
			die();
		}
		$table_name   = $wpdb->prefix . 'ld_time_entries';
		$last_updated = $this->fetch_last_updated_activity( $post_id, $course_id, $user_id );
		if ( empty( $last_updated ) ) {
			// Create new entry.
			$insert_id = $wpdb->insert(
				$table_name,
				array(
					'course_id'        => $course_id,
					'post_id'          => $post_id,
					'user_id'          => $user_id,
					'activity_updated' => $activity_updated,
					'time_spent'       => $time_spent,
					'ip_address'       => '',
				),
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
				)
			);
			if ( false === $insert_id ) {
				wp_send_json_error();
				die();
			}
			wp_send_json_success();
			die();
		}
		// Update existing entry.
		$activity = $this->fetch_last_updated_entry( $post_id, $course_id, $user_id );
		if ( empty( $activity ) ) {
			wp_send_json_error();
			die();
		}
		$activity_id         = current( array_column( $activity, 'id' ) );
		$previous_time_spent = current( array_column( $activity, 'time_spent' ) );
		$total_time_spent    = $time_spent + $previous_time_spent;

		$updated = $wpdb->update(
			$table_name,
			array(
				'activity_updated' => $activity_updated,
				'time_spent'       => $total_time_spent,
			),
			array(
				'id' => $activity_id,
			),
			array(
				'%d',
				'%d',
			),
			array(
				'%d',
			)
		);
		if ( false === $updated ) {
			wp_send_json_error();
			die();
		}
		wp_send_json_success();
		die();
	}

	/**
	 * This method is used to fetch user's IP address.
	 *
	 * @return IP Address of the user.
	 */
	public function get_user_ip_address() {
		$ip_addr = '';
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {// phpcs:ignore
			// check ip from shared internet.
			$ip_addr = $_SERVER['HTTP_CLIENT_IP'];// phpcs:ignore
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {// phpcs:ignore
			// to check ip is passed from proxy.
			$ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];// phpcs:ignore
		} else {
			$ip_addr = $_SERVER['REMOTE_ADDR'];// phpcs:ignore
		}
		return apply_filters( 'ldrp_get_ip', $ip_addr );
	}

	/**
	 * This method is used to fetch the last updated entry for a user.
	 *
	 * @param  integer $post_id      Post ID.
	 * @param  integer $course_id    Course ID.
	 * @param  integer $user_id      User ID.
	 * @return array    Timespent value for the supplied params.
	 */
	public function fetch_last_updated_entry( $post_id, $course_id, $user_id = 0 ) {
		global $wpdb;

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$table_name = $wpdb->prefix . '';
		$output     = $wpdb->get_results( $wpdb->prepare( 'SELECT id, time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE post_id = %d AND course_id = %d AND user_id = %d', $post_id, $course_id, $user_id ), ARRAY_A );
		return $output;
	}

	/**
	 * This method is used to fetch the last updated activity for a user.
	 *
	 * @param  integer $post_id      Post ID.
	 * @param  integer $course_id    Course ID.
	 * @param  integer $user_id      User ID.
	 * @return array    Timespent value for the supplied params.
	 */
	public function fetch_last_updated_activity( $post_id, $course_id, $user_id = 0 ) {
		global $wpdb;

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE post_id = %d AND course_id = %d AND user_id = %d', $post_id, $course_id, $user_id ), ARRAY_A );
		if ( empty( $output ) ) {
			return false;
		}
		return $output;
		// $latest_update = max( array_column( $output, 'activity_updated' ) );// Max timestamp.
		// return $latest_update;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_course_time_spent( $course_id, $user_id ) {
		global $wpdb;

		$total_time_spent = 0;

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE course_id = %d AND user_id = %d', $course_id, $user_id ), ARRAY_A );

		if ( empty( $output ) ) {
			return $total_time_spent;
		}

		$total_time_spent = array_sum( array_column( $output, 'time_spent' ) );
		return $total_time_spent;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_average_course_completion_time( $course_id, $user_id ) {
		$time = get_user_meta( $user_id, 'course_time_' . $course_id, true );
		return empty( $time ) ? 0 : $time;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_average_lesson_completion_time( $lesson_id, $user_id ) {
		$time = get_user_meta( $user_id, 'lesson_time_' . $lesson_id, true );
		return empty( $time ) ? 0 : $time;
	}
	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_average_topic_completion_time( $topic_id, $user_id ) {
		$time = get_user_meta( $user_id, 'topic_time_' . $topic_id, true );
		return empty( $time ) ? 0 : $time;
	}
	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_average_quiz_completion_time( $quiz_id, $user_id ) {
		$time = get_user_meta( $user_id, 'quiz_time_' . $quiz_id, true );
		return empty( $time ) ? 0 : $time;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_course_completion_time_spent( $course_id, $user_id ) {
		global $wpdb;

		$total_time_spent     = 0;
		$completed_time_stamp = $this->learndash_get_completed_timestamp( $user_id, $course_id, 'course' );

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE course_id = %d AND user_id = %d AND activity_updated <= %d', $course_id, $user_id, $completed_time_stamp ), ARRAY_A );

		if ( empty( $output ) ) {
			return $total_time_spent;
		}

		$total_time_spent = array_sum( array_column( $output, 'time_spent' ) );
		return $total_time_spent;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_module_time_spent( $post_id, $course_id, $user_id ) {
		global $wpdb;

		$total_time_spent = 0;

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE post_id = %d AND course_id = %d AND user_id = %d', $post_id, $course_id, $user_id ), ARRAY_A );

		if ( empty( $output ) ) {
			return $total_time_spent;
		}

		$total_time_spent = array_sum( array_column( $output, 'time_spent' ) );
		return $total_time_spent;
	}

	public function learndash_get_completed_timestamp( $user_id = 0, $course_id = 0, $type = 'course' ) {
		$completed_on_timestamp = 0;
		if ( ( ! empty( $user_id ) ) && ( ! empty( $course_id ) ) ) {
			if ( 'course' === $type ) {
				$completed_on_timestamp = get_user_meta( $user_id, 'course_completed_' . $course_id, true );
			}

			if ( empty( $completed_on_timestamp ) ) {
				$activity_query_args = array(
					'post_ids'      => $course_id,
					'user_ids'      => $user_id,
					'activity_type' => $type,
					'per_page'      => 1,
				);

				$activity = learndash_reports_get_activity( $activity_query_args );
				if ( ! empty( $activity['results'] ) ) {
					foreach ( $activity['results'] as $activity_item ) {
						if ( property_exists( $activity_item, 'activity_completed' ) ) {
							$completed_on_timestamp = $activity_item->activity_completed;
							if ( 'course' === $type ) {
								// To make the next check easier we update the user meta.
								update_user_meta( $user_id, 'course_completed_' . $course_id, $completed_on_timestamp );
							}
							break;
						}
					}
				}
			}
		}

		return $completed_on_timestamp;
	}

	/**
	 * This method is used to get user's time spent on a quiz.
	 *
	 * @param  int $user_id User ID.
	 * @param  int $quiz_id Quiz ID.
	 * @return int Timespent on a quiz by a user in seconds.
	 */
	public function learndash_get_user_quiz_attempts_time_spent( $user_id, $quiz_id ) {
		$total_time_spent = 0;

		$attempts = learndash_get_user_quiz_attempts( $user_id, $quiz_id );
		if ( ( ! empty( $attempts ) ) && ( is_array( $attempts ) ) ) {
			foreach ( $attempts as $attempt ) {
				if ( empty( $attempt->activity_completed ) || empty( $attempt->activity_started ) ) {
					continue;
				}
				if ( $attempt->activity_completed - $attempt->activity_started < 0 ) {
					continue;
				}
				$total_time_spent += ( $attempt->activity_completed - $attempt->activity_started );
			}
		}

		return $total_time_spent;
	}

	/**
	 * Gets the time spent by user in the course.
	 *
	 * Total of each started/complete time set.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id   Optional. The ID of the user to get course time spent. Default 0.
	 * @param int $course_id Optional. The ID of the course to get time spent. Default 0.
	 *
	 * @return int Total number of seconds spent.
	 */
	public function learndash_get_user_course_attempts_time_spent( $user_id = 0, $course_id = 0 ) {
		$total_time_spent = 0;
		$attempts         = learndash_get_user_course_attempts( $user_id, $course_id );

		// We should only ever have one entry for a user+course_id. But still we are returned an array of objects.
		if ( ( ! empty( $attempts ) ) && ( is_array( $attempts ) ) ) {
			foreach ( $attempts as $attempt ) {

				if ( ! empty( $attempt->activity_completed ) ) {
					// If the Course is complete then we take the time as the completed - started times.
					if ( empty( $attempt->activity_completed ) || empty( $attempt->activity_started ) ) {
						continue;
					}
					if ( $attempt->activity_completed - $attempt->activity_started < 0 ) {
						continue;
					}
					$total_time_spent += ( $attempt->activity_completed - $attempt->activity_started );
				} else {
					if ( empty( $attempt->activity_updated ) || empty( $attempt->activity_started ) ) {
						continue;
					}
					if ( $attempt->activity_updated - $attempt->activity_started < 0 ) {
						continue;
					}
					// But if the Course is not complete we calculate the time based on the updated timestamp.
					// This is updated on the course for each lesson, topic, quiz.
					$total_time_spent += ( $attempt->activity_updated - $attempt->activity_started );
				}
			}
		}

		return $total_time_spent;
	}

	/**
	 * This method returns time spent on each of the courses.
	 *
	 * @return WP_Rest_Response/WP_Error object.
	 */
	public function get_course_time_spent() {
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		unset( $request_data['start_date'] );
		unset( $request_data['end_date'] );
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_time_spent' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_time_spent' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
				if ( empty( $group_users ) ) {
					$group_users = array();
				}
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					$group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		$category_query        = array();
		$is_pro_version_active = apply_filters( 'wisdm_ld_reports_pro_version', false );
		if ( ( ! is_null( $accessible_users ) && -1 != $accessible_users ) && empty( $accessible_users ) ) {
			return new WP_Error(
				'no-users-accessible',
				__( 'No data found for accessible learners', 'learndash-reports-by-wisdmlabs' ),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : array( $request_data['learner'] );
			if ( empty( $accessible_courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
		} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
					return new WP_Error(
						'unauthorized',
						sprintf(/* translators: %s: custom label for course */
							__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::label_to_lower( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
				}
			}
			$courses_selected   = array( $request_data['course'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $courses_selected, $accessible_courses ) : $courses_selected;
			$course_price_type = learndash_get_course_meta_setting( $request_data['course'], 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$course_users = get_transient( 'wrld_course_students_data_' . $request_data['course'] );
			if ( false === $course_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$course_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $request_data['course'] );
				} else {
					$course_users = learndash_get_users_for_course( $request_data['course'], array(), false );
				}
				$course_users       = is_array( $course_users ) ? $course_users : $course_users->get_results();
				set_transient( 'wrld_course_students_data_' . $request_data['course'], $course_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $course_users, $accessible_users ) : $course_users;
			$accessible_users = array_diff( $accessible_users , $excluded_users );

			$class_size         = count( $accessible_users );
			$time_spent         = array();
			$total_time_spent   = 0;
			$average_time_spent = 0;
			foreach ( $accessible_users as $student_id ) {
				if ( $is_pro_version_active ) {
					$user_time_spent = $this->fetch_user_course_time_spent( $request_data['course'], $student_id );
				} else {
					$user_time_spent = $this->learndash_get_user_course_attempts_time_spent( $student_id, $request_data['course'] );
				}

				if ( $user_time_spent < 0 ) {
					$user_time_spent = 0;
				}
				$total_time_spent          = $total_time_spent + $user_time_spent;
				$time_spent[ $student_id ] = array(
					'time'     => $user_time_spent,
					'username' => get_userdata( $student_id )->display_name,
				);
			}

			if ( 0 != $total_time_spent && $class_size > 0 ) {
				$average_time_spent = floatval( number_format( $total_time_spent / $class_size, 2, '.', '' ) );// Cast to integer if no decimals.
			}

			// Calculate average across students.
			return new WP_REST_Response(
				array(
					'requestData'        => self::get_values_for_request_params( $request_data ),
					'time_spent'         => $time_spent,
					'average_time_spent' => $average_time_spent,
					'total_time'         => $total_time_spent,
					'total_learners'     => $class_size,
				),
				200
			);
		} elseif ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
			// Check if course category enabled.
			if ( ! taxonomy_exists( 'ld_course_category' ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			// Check if valid category passed.
			if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}

			$category_query = array(
				array(
					'taxonomy'         => 'ld_course_category',
					'field'            => 'term_id',
					'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
					'include_children' => false,
				),
			);
		} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
				if ( empty( $group_users ) ) {
					$group_users = array();
				}
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					$group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}

		$query_args = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => '-1',
			'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
		);

		if ( ! empty( $category_query ) ) {
			$query_args['tax_query'] = $category_query;
		}

		$courses = get_posts( $query_args );

		// Check if any courses present in the category.
		if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		$course_count     = count( $courses );
		$total_time_spent = 0;
		$course_wise_time = array();

		foreach ( $courses as $course ) {
			$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				continue;
			}
			$students = get_transient( 'wrld_course_students_data_' . $course->ID );
			if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
			// Get all students for a course.
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
				} else {
					$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
				}
				$students = is_array( $students ) ? $students : $students->get_results();
				set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
			}
			$students   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
			$students = array_diff( $students , $excluded_users );

			$class_size = is_array( $students ) ? count( $students ) : $students->get_total();

			$course_time     = 0;
			$course_avg_time = 0;
			// If no students in the course then the course has 0 time spent.
			if ( empty( $students ) ) {
				$course_wise_time[ $course->ID ] = array(
					'time'   => $course_time,
					'course' => $course->post_title,
				);
				continue;
			}
			foreach ( $students as $student_id ) {

				if ( $is_pro_version_active ) {
					$user_t = $this->fetch_user_course_time_spent( $course->ID, $student_id );
				} else {
					$user_t = $this->learndash_get_user_course_attempts_time_spent( $student_id, $course->ID );
				}

				if ( $user_t < 0 ) {
					$user_t = 0;
				}

				$course_time = $course_time + $user_t;
			}

			$course_avg_time                 = 0 == $class_size ? 0 : floatval( number_format( $course_time / $class_size, 2, '.', '' ) );
			$total_time_spent                = $total_time_spent + $course_avg_time;
			$course_wise_time[ $course->ID ] = array(
				'time'   => $course_avg_time,
				'course' => $course->post_title,
			);
		}

		if ( $course_count <= 0 ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		$overall_average_time = intval( $total_time_spent / $course_count );
		$total_time_spent     = $total_time_spent;
		$overall_average_time = $overall_average_time;

		return new WP_REST_Response(
			array(
				'requestData'       => self::get_values_for_request_params( $request_data ),
				'averageCourseTime' => $overall_average_time,
				'courseWiseTime'    => $course_wise_time,
				'courseCount'       => $course_count,
				'courseTotalTime'   => $total_time_spent,
			),
			200
		);
	}

	/**
	 * This method returns time spent on each lessons of a course.
	 *
	 * @author Seraj Alam
	 * @return WP_Rest_Response/WP_Error object.
	 */
	public function get_lesson_topic_time_spent() {
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		unset( $request_data['start_date'] );
		unset( $request_data['end_date'] );
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_time_spent' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_time_spent' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					$group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		$category_query = array();

		if ( ( ! is_null( $accessible_users ) && -1 != $accessible_users ) && empty( $accessible_users ) ) {
			return new WP_Error(
				'no-users-accessible',
				__( 'No data found for accessible learners', 'learndash-reports-by-wisdmlabs' ),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : array( $request_data['learner'] );
			if ( empty( $accessible_courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
		} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
					return new WP_Error(
						'unauthorized',
						sprintf(/* translators: %s: custom label for course */
							__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::label_to_lower( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
				}
			}
			$course_price_type = learndash_get_course_meta_setting( $request_data['course'], 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$courses_selected   = array( $request_data['course'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $courses_selected, $accessible_courses ) : $courses_selected;
			$course_users = get_transient( 'wrld_course_students_data_' . $request_data['course'] );
			if ( false === $course_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$course_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $request_data['course'] );
				} else {
					$course_users = learndash_get_users_for_course( $request_data['course'], array(), false );
				}
				$course_users       = is_array( $course_users ) ? $course_users : $course_users->get_results();
				set_transient( 'wrld_course_students_data_' . $request_data['course'], $course_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $course_users, $accessible_users ) : $course_users;
			$accessible_users = array_diff( $accessible_users , $excluded_users );

			$class_size         = count( $accessible_users );
			$time_spent         = array();
			$total_time_spent   = 0;
			$average_time_spent = 0;

			$current_post_id = isset( $request_data['topic'] ) && ! empty( $request_data['topic'] ) ? $request_data['topic'] : $request_data['lesson'];

			foreach ( $accessible_users as $student_id ) {
				$user_time_spent = $this->fetch_user_module_time_spent( $current_post_id, $request_data['course'], $student_id );
				if ( $user_time_spent < 0 || empty( $user_time_spent ) || null == $user_time_spent ) {
					$user_time_spent = 0;
				}
				$total_time_spent          = $total_time_spent + $user_time_spent;
				$time_spent[ $student_id ] = array(
					'time'     => $user_time_spent,
					'username' => get_userdata( $student_id )->display_name,
				);
			}

			if ( 0 != $total_time_spent && $class_size > 0 ) {
				$average_time_spent = floatval( number_format( $total_time_spent / $class_size, 2, '.', '' ) );// Cast to integer if no decimals.
			}

			// Calculate average across students.
			return new WP_REST_Response(
				array(
					'requestData'        => self::get_values_for_request_params( $request_data ),
					'time_spent'         => $time_spent,
					'average_time_spent' => $average_time_spent,
					'total_time'         => 0 == $total_time_spent ? '-' : $total_time_spent,
					'total_learners'     => $class_size,
				),
				200
			);
		}
	}

	/**
	 * This method returns Quiz completion time based on input parameters.
	 *
	 * @return WP_Rest_Response/WP_Error returned.
	 */
	public function get_quiz_completion_time() {
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		unset( $request_data['start_date'] );
		unset( $request_data['end_date'] );
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'quiz_completion_rate' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'quiz_completion_rate' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
				if ( empty( $group_users ) ) {
					$group_users = array();
				}
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					$group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}

		$category_query = array();

		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : array( $request_data['learner'] );
			if ( ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) && empty( $accessible_courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(
						/* translators: %s: custom label for courses */
						__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
		} elseif ( isset( $request_data['topic'] ) && ! empty( $request_data['topic'] ) ) {
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
					return new WP_Error(
						'unauthorized',
						sprintf(/* translators: %s: custom label for course */
							__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::label_to_lower( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
				}
			}
			return $this->get_modulewise_quiztime( $request_data['course'], $request_data['topic'], 'array', $accessible_users, $request_data );
		} elseif ( isset( $request_data['lesson'] ) && ! empty( $request_data['lesson'] ) ) {
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
					return new WP_Error(
						'unauthorized',
						sprintf(/* translators: %s: custom label for course */
							__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::label_to_lower( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
				}
			}
			return $this->get_modulewise_quiztime( $request_data['course'], $request_data['lesson'], 'array', $accessible_users, $request_data );
		} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
					return new WP_Error(
						'unauthorized',
						sprintf(/* translators: %s: custom label for course */
							__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::label_to_lower( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
				}
			}
			return $this->get_modulewise_quiztime( $request_data['course'], $request_data['course'], 'array', $accessible_users, $request_data );
		} elseif ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
			// Check if course category enabled.
			if ( ! taxonomy_exists( 'ld_course_category' ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			// Check if valid category passed.
			if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$category_query = array(
				array(
					'taxonomy'         => 'ld_course_category',
					'field'            => 'term_id',
					'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
					'include_children' => false,
				),
			);
		} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					$group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			if ( empty( $group_users ) ) {
				$group_users = array();
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}

		$query_args = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => '-1',
			'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
		);

		if ( ! empty( $category_query ) ) {
			$query_args['tax_query'] = $category_query;
		}

		$courses = get_posts( $query_args );
		// Check if any courses present in the category.
		if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s data found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'quiz' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		$total_time_spent = 0;
		$course_wise_time = array();
		$course_count     = count( $courses );

		foreach ( $courses as $course ) {
			$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				continue;
			}
			$students = get_transient( 'wrld_course_students_data_' . $course->ID );
			if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
			// Get all students for a course.
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
				} else {
					$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
				}
				$students = is_array( $students ) ? $students : $students->get_results();
				set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
			}
			$students   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
			$students = array_diff( $students , $excluded_users );

			$class_size = is_array( $students ) ? count( $students ) : $students->get_total();

			$course_time = 0;

			// If no students in the course then the course has 0 time spent.
			if ( empty( $students ) ) {
				// $course_wise_time[ $course->ID ] = array(
				// 'time'   => $course_time,
				// 'course' => $course->post_title,
				// );
				continue;
			}

			$course_quiz_time = $this->get_modulewise_quiztime( $course->ID, $course->ID, 'array', $accessible_users, $request_data );

			if ( ! is_wp_error( $course_quiz_time ) ) {
				$course_wise_time[ $course->ID ] = array(
					'time'   => $course_quiz_time['quizTotalTime'],
					'course' => $course->post_title,
				);
				$total_time_spent                = $total_time_spent + $course_quiz_time['quizTotalTime'];
			} else {
				--$course_count;
			}
		}

		if ( $course_count <= 0 ) {
			if ( $course_count <= 0 ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s data found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'quiz' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
		} else {
			$overall_average_time = floatval( number_format( $total_time_spent / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.
		}

		return new WP_REST_Response(
			array(
				'requestData'       => self::get_values_for_request_params( $request_data ),
				'courseTotalTime'   => $total_time_spent,
				'averageCourseTime' => $overall_average_time,
				'courseWiseTime'    => $course_wise_time,
			),
			200
		);
	}

	/**
	 * This method returns Quiz time spent for any modules i.e., Course/Lesson/Topic.
	 *
	 * @param  int        $course Course ID.
	 * @param  int        $module Lesson/Topic ID.
	 * @param  string     $return return type.
	 * @param  null|array $student_filter Array of student IDs for which the quiz data is required.
	 *
	 * @return array/WP_Rest_Response object.
	 */
	public function get_modulewise_quiztime( $course, $module, $return = 'object', $student_filter = null, $request_data = array() ) {
		$course_price_type = learndash_get_course_meta_setting( $course, 'course_price_type' );
		if ( 'open' === $course_price_type ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		$students = get_transient( 'wrld_course_students_data_' . $course );
		if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
		// Get all students for a course.
			if ( get_option( 'migrated_course_access_data', false ) ) {
				$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course );
			} else {
				$students = learndash_get_users_for_course( $course, array(), false ); // Third argument is $exclude_admin.
			}
			$students = is_array( $students ) ? $students : $students->get_results();
			set_transient( 'wrld_course_students_data_' . $course, $students, 1 * HOUR_IN_SECONDS );
		}
		
		if ( empty( $students ) ) {
			return new WP_Error(
				'no-data',
				__( 'No Students Enrolled', 'learndash-reports-by-wisdmlabs' ),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		$user = wp_get_current_user();

		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		$students      = ( ! is_null( $student_filter ) && -1 != $student_filter ) ? array_intersect( $student_filter, $students ) : $students;
		$students = array_diff( $students , $excluded_users );

		$quizzes       = learndash_course_get_children_of_step( $course, $module, 'sfwd-quiz', 'ids', true );
		$student_count = is_array( $students ) ? count( $students ) : $students->get_total();

		if ( empty( $quizzes ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for quizzes */
					__( 'No %s found for the selected filters', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'quizzes' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		$total_quizzes_time = 0;
		$learner_wise_time  = array();

		foreach ( $students as $student_id ) {
			$student_time = 0;
			foreach ( $quizzes as $quiz ) {
				$quiz_t = $this->learndash_get_user_quiz_attempts_time_spent( $student_id, $quiz );
				if ( $quiz_t < 0 ) {
					$quiz_t = 0;
				}
				$student_time = $student_time + $quiz_t;
			}
			if ( 0 >= $student_time ) {
				$student_count--;
				continue;
			}
			$total_quizzes_time               = $total_quizzes_time + $student_time;
			$learner_wise_time[ $student_id ] = array(
				'name' => get_userdata( $student_id )->display_name,
				'time' => $student_time,
			);
		}
		if ( $student_count <= 0 ) {
			return new WP_Error(
				'no-data',
				/* translators: %s : Quiz Attempt  */
				sprintf( __( 'No Students have made %s attempts', 'learndash-reports-by-wisdmlabs' ), \LearnDash_Custom_Label::label_to_lower( 'quiz' ) ),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		$overall_average_time = floatval( number_format( $total_quizzes_time / $student_count, 2, '.', '' ) );// Cast to integer if no decimals.
		if ( 'array' === $return ) {
			return array(
				'requestData'     => self::get_values_for_request_params( $request_data ),
				'averageQuizTime' => $overall_average_time,
				'learnerWiseTime' => $learner_wise_time,
				'studentCount'    => $student_count,
				'quizTotalTime'   => $total_quizzes_time,
			);
		}
		return new WP_REST_Response(
			array(
				'requestData'     => self::get_values_for_request_params( $request_data ),
				'averageQuizTime' => $overall_average_time,
				'learnerWiseTime' => $learner_wise_time,
				'studentCount'    => $student_count,
				'quizTotalTime'   => $total_quizzes_time,
			),
			200
		);
	}

	/**
	 * Converts the seconds to time output.
	 *
	 * @since 2.1.0
	 *
	 * @param int $input_seconds The seconds value.
	 *
	 * @return string The time output string.
	 */
	public function ldrp_seconds_to_time( $input_seconds = 0 ) {

		$seconds_minute = 60;
		$seconds_hour   = 60 * $seconds_minute;
		$seconds_day    = 24 * $seconds_hour;

		$return = '';

		// extract days.
		$days = floor( $input_seconds / $seconds_day );
		if ( ! empty( $days ) ) {
			if ( ! empty( $return ) ) {
				$return .= ' ';
			}
			// translators: placeholder: Number of Days count.
			$return .= sprintf( _n( '%s day', '%s days', $days, 'learndash-reports-by-wisdmlabs' ), number_format_i18n( $days ) );
		}

		// extract hours.
		$hour_seconds = $input_seconds % $seconds_day;
		$hours        = floor( $hour_seconds / $seconds_hour );
		if ( ! empty( $hours ) ) {
			if ( ! empty( $return ) ) {
				$return .= ' ';
			}
			// translators: placeholder: Number of Hours count.
			$return .= sprintf( _n( '%s hr', '%s hrs', $hours, 'learndash-reports-by-wisdmlabs' ), number_format_i18n( $hours ) );
		}

		// extract minutes.
		$minute_seconds = $input_seconds % $seconds_hour;
		$minutes        = floor( $minute_seconds / $seconds_minute );
		if ( ! empty( $minutes ) ) {
			if ( ! empty( $return ) ) {
				$return .= ' ';
			}
			// translators: placeholder: Number of Minutes count.
			$return .= sprintf( _n( '%s min', '%s min', $minutes, 'learndash' ), number_format_i18n( $minutes ) );

		}

		// extract the remaining seconds.
		$remaining_seconds = $input_seconds % $seconds_minute;
		$seconds           = ceil( $remaining_seconds );
		if ( ! empty( $seconds ) ) {
			if ( ! empty( $return ) ) {
				$return .= ' ';
			}
			// translators: placeholder: Number of Seconds count.
			$return .= sprintf( _n( '%s sec', '%s sec', $seconds, 'learndash' ), number_format_i18n( $seconds ) );
		}

		return trim( $return );
	}
}
