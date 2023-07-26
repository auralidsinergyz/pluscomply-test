<?php

namespace uncanny_learndash_groups;

use LDLMS_Factory_Post;
use LearnDash_Settings_Section;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class LearnDashMyCourses
 *
 * @package uncanny_learndash_groups
 */
class LearnDashProgressReport {
	/**
	 * @var
	 */
	private static $group_association;
	/**
	 * @var
	 */
	private static $group_user_ids;

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	/**
	 *
	 */
	public static function run_frontend_hooks() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts_func' ) );
		add_shortcode( 'uo_ajax_search', array( __CLASS__, 'handle_shortcode' ) );
		add_action( 'wp_ajax_uo_groups_search_user', array( __CLASS__, 'search_user' ), 33 );

		/* ADD FILTERS ACTIONS FUNCTION */
		add_shortcode( 'uo_groups_manage_progress', array( __CLASS__, 'uo_course_dashboard' ) );
		add_filter( 'uo-dashboard-template', array( __CLASS__, 'uo_dashboard_get_template' ) );

		//register api class
		add_action( 'rest_api_init', array( __CLASS__, 'reporting_api' ) );
	}

	/**
	 *
	 */
	public static function reporting_api() {

		// Call get all courses and general user data
		register_rest_route(
			'uncanny_group_course_management/v2',
			'/change_user_progress/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'change_user_progress' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);
	}

	/**
	 * @param $topics
	 * @param $lessons
	 *
	 * @return mixed
	 */
	protected static function maybe_lesson_topic_unchecked( $topics, $lessons ) {

		if ( empty( $topics ) || empty( $lessons ) ) {
			return $lessons;
		}

		// is topic unchecked?
		$uncheck_lessons = array();
		if ( ! empty( $topics ) && is_array( $topics ) ) {
			foreach ( $topics as $topic_lesson_id => $topic_ids ) {
				foreach ( $topic_ids as $ld_topic_id => $topic_status ) {
					if ( 0 === (int) $topic_status ) { // topic is unchecked.
						$lessons[ $topic_lesson_id ] = 0;
					}
				}
			}
		}

		return $lessons;
	}

	/**
	 * Collect general user course data and LearnDash Labels
	 *
	 * @return object
	 */
	public static function change_user_progress( \WP_REST_Request $request ) {

		$mark_progress_incomplete = false;
		$json_return              = (object) array(
			'course_id'       => null,
			'certificate_url' => null,
			'progress'        => (object) array(
				'completed'                 => null,
				'total'                     => null,
				'percentage'                => null,
				'course_completed_children' => (object) array(),
			),
			'success'         => false,
		);

		if ( ! $request->has_param( 'rest_user_id' ) || empty( $request->has_param( 'rest_user_id' ) ) || ! $request->has_param( 'rest_course_id' ) || empty( $request->has_param( 'rest_course_id' ) ) ) {
			$json_return->message = __( 'Required fields missing', 'uncanny-learndash-groups' );

			return $json_return;
		}
		// Check if the user can manage progress
		if ( false === self::can_manage_user_progress( $request->get_param( 'rest_user_id' ), $request->get_param( 'rest_course_id' ) ) ) {
			$json_return->message = __( 'You are not allowed to modify course progress of this user.', 'uncanny-learndash-groups' );

			return $json_return;
		}

		/*
		 * Running learndash_save_user_course_complete()
		 *
		 * This function is based on $_POST data set during user profile updates.
		 * We have to set up the $_POST object for the function to run on.
		 * It only takes one parameter: $user_id
		 */

		$user_id    = absint( $request->get_param( 'rest_user_id' ) );
		$course_id  = absint( $request->get_param( 'rest_course_id' ) );
		$lesson     = $request->has_param( 'rest_lessons' ) ? $request->get_param( 'rest_lessons' ) : array();
		$topics     = $request->has_param( 'rest_topics' ) ? $request->get_param( 'rest_topics' ) : array();
		$quizzes    = $request->has_param( 'rest_quizzes' ) ? $request->get_param( 'rest_quizzes' ) : array();
		$post_nonce = $request->has_param( 'postNonce' ) ? $request->get_param( 'postNonce' ) : '';
		$lesson     = self::maybe_lesson_topic_unchecked( $topics, $lesson );

		// Set up the $_POST object so that the function has data when it runs
		$_POST['user_progress'][ $user_id ]['course'][ $course_id ]['lessons'] = $lesson;
		$_POST['user_progress'][ $user_id ]['course'][ $course_id ]['topics']  = $topics;

		// We are supplied with a completion data point for topic level quizzes right now. We need to see if its complete or not.
		if ( $quizzes ) {
			foreach ( $quizzes as $_quiz_id => &$_completion ) {
				// ? is set at on the completion value if its a topic level quiz and the quizzes topic isn't being modified.
				if ( '?' === $_completion ) {
					$_completion = ( learndash_is_quiz_complete( $user_id, $_quiz_id, $course_id ) ) ? 1 : 0;
				}
			}
		}

		$_POST['user_progress'][ $user_id ]['quiz'][ $course_id ] = $quizzes;

		// Course data needs to be a JSON string. learndash_save_user_course_complete() decodes it.
		$_POST['user_progress'][ $user_id ] = json_encode( $_POST['user_progress'][ $user_id ] );

		// SET NONCE that is expected. This is set in the localized script ulgm-frontend -> postNance
		$_POST[ 'user_progress-' . $user_id . '-nonce' ] = $post_nonce;

		// We already verified that the user is allowed to manage progress. Adding a filter to override the capability check in learndash_save_user_course_complete()
		add_filter( 'user_has_cap', array( __CLASS__, 'allow_all_caps' ) );

		// mark complete the lesson
		if ( ! empty( $lesson ) && is_array( $lesson ) ) {
			foreach ( $lesson as $_lesson_id => $bool ) {
				if ( intval( $bool ) === 1 ) {
					if ( ! learndash_is_lesson_complete( $user_id, $_lesson_id ) ) {
						learndash_process_mark_complete( $user_id, $_lesson_id );
					}
				}
			}
		}

		learndash_save_user_course_complete( $user_id );

		// Remove the caps filter. It is only needed for learndash_save_user_course_complete() to run.
		remove_filter( 'user_has_cap', array( __CLASS__, 'allow_all_caps' ) );

		// Set course ID For return object
		$json_return->course_id = $course_id;

		// All done
		$json_return->success = true;

		$course_children = array();

		// Auto-complete the lesson when all topics are marked complete
		if ( ! empty( $topics ) && is_array( $topics ) ) {

			foreach ( $topics as $topic_lesson_id => $topic_ids ) {
				$topic_count = count( $topic_ids );
				$user        = get_user_by( 'id', $user_id );
				if ( $topic_count > 0 ) {
					$topic_completed = 0;
					foreach ( $topic_ids as $ld_topic_id => $topic_status ) {
						if ( 1 === (int) $topic_status ) {
							$topic_completed ++;
						}
					}

					if ( $topic_count == $topic_completed ) {

						if ( ! learndash_is_lesson_complete( $user_id, $topic_lesson_id ) ) {
							learndash_process_mark_complete( $user_id, $topic_lesson_id );
						}

						$course_children[] = (object) array(
							'ID'        => $topic_lesson_id,
							'post_type' => 'sfwd-lessons',
						);

					}
				}
			}
		}

		// return updated course data
		$user_progress = (array) learndash_course_progress(
			array(
				'course_id' => $course_id,
				'user_id'   => $user_id,
				'array'     => true,
			)
		);

		//Hack to send all completed ids back
		$course_progress = get_user_meta( $user_id, '_sfwd-course_progress', true );

		if ( ( ! empty( $course_progress ) ) && ( isset( $course_progress[ $course_id ] ) ) && ( ! empty( $course_progress[ $course_id ] ) ) ) {
			$course_to_loop = $course_progress[ $course_id ];
			if ( $course_to_loop ) {
				foreach ( $course_to_loop as $key => $loop ) {
					if ( 'lessons' === $key && is_array( $loop ) ) {
						foreach ( $loop as $lesson_id => $completed ) {
							if ( 1 === (int) $completed ) {
								$course_children[] = (object) array(
									'ID'        => $lesson_id,
									'post_type' => 'sfwd-lessons',
								);
							}
						}
					}

					if ( 'topics' === $key && is_array( $loop ) ) {
						foreach ( $loop as $lesson_id => $topics ) {
							if ( is_array( $topics ) ) {
								foreach ( $topics as $topic_id => $completed ) {
									if ( 1 === (int) $completed ) {
										$course_children[] = (object) array(
											'ID'        => $topic_id,
											'post_type' => 'sfwd-topic',
										);
									}
								}
							}
						}
					}
				}
			}
		}

		$quiz_progress = get_user_meta( $user_id, '_sfwd-quizzes', true );

		$sort_quizzes = array();
		if ( $quiz_progress ) {
			//Raw
			foreach ( $quiz_progress as $q ) {
				$sort_quizzes[] = array(
					'quiz_id'   => $q['quiz'],
					'completed' => ( isset( $q['m_edit_time'] ) ) ? $q['m_edit_time'] : 0,
					'pass'      => $q['pass'],
				);
			}

			// as of PHP 5.5.0 you can use array_column() instead of the above code
			$quiz_idss      = array_column( $sort_quizzes, 'quiz_id' );
			$quiz_completed = array_column( $sort_quizzes, 'completed' );

			// Sort the data with volume descending, edition ascending
			// Add $data as the last parameter, to sort by the common key
			array_multisort( $quiz_idss, SORT_DESC, $quiz_completed, SORT_DESC, $sort_quizzes );

			$sorted = array();
			if ( $sort_quizzes ) {
				foreach ( $sort_quizzes as $qq ) {
					$sorted[ $qq['quiz_id'] ][] = $qq['completed'];
				}
			}

			foreach ( $quiz_progress as $q ) {
				if ( (int) $course_id === (int) $q['course'] && ! empty( $q['pass'] ) && 1 === (int) $q['pass'] && 0 !== (int) $q['quiz'] ) {

					$quiz_certificate = null;
					if ( isset( $q['percentage'] ) && 0 != $q['percentage'] ) {
						$c = learndash_certificate_details( $q['quiz'], $user_id );
						if ( ! empty( $c['certificateLink'] ) ) {
							$quiz_certificate = $c['certificateLink'];
						}
					}

					$course_children[] = (object) array(
						'ID'              => $q['quiz'],
						'post_type'       => 'sfwd-quiz',
						'certificate_url' => $quiz_certificate,
						'meta'            => get_post_meta( $q['quiz'], '_sfwd-quiz', true ),
					);
				}
			}
		}

		if ( learndash_course_completed( $user_id, $course_id ) ) {
			// Get Course certificate URL
			$course_certificate = learndash_get_course_certificate_link( $course_id, $user_id );
			if ( ! empty( $course_certificate ) ) {
				// Set course certificate URL
				$json_return->certificate_url = $course_certificate;
			}
		}

		$progress_percentage = true === $mark_progress_incomplete ? ( ceil( ( $user_progress['completed'] - 1 ) / $user_progress['total'] * 100 ) ) : $user_progress['percentage'];

		$json_return->progress->completed  = true === $mark_progress_incomplete ? $user_progress['completed'] - 1 : $user_progress['completed'];
		$json_return->progress->total      = $user_progress['total'];
		$json_return->progress->percentage = $progress_percentage < 0 ? 0 : $progress_percentage;

		// If completed and total == 0. Make the progress 100%.
		if ( 0 === $json_return->progress->completed && 0 === $json_return->progress->total ) {
			$json_return->progress->percentage = 100;
		}

		$json_return->progress->course_completed_children = $course_children;

		return $json_return;
	}

	/**
	 * @param $allcaps
	 *
	 * @return mixed
	 */
	public static function allow_all_caps( $allcaps ) {
		$allcaps['edit_users'] = true;

		return $allcaps;
	}

	/**
	 * @param $quiz_id
	 * @param $course_id
	 * @param $user_id
	 */
	public static function mark_a_quiz_complete( $quiz_id, $course_id, $user_id ) {
		$usermeta       = get_user_meta( $user_id, '_sfwd-quizzes', true );
		$quizz_progress = empty( $usermeta ) ? array() : $usermeta;

		$quiz_meta = get_post_meta( $quiz_id, '_sfwd-quiz', true );

		// If the admin is marking the quiz complete AND the quiz is NOT already complete...
		// Then we add the minimal quiz data to the user profile.
		$quizdata = array(
			'quiz'             => $quiz_id,
			'score'            => 100,
			'count'            => 0,
			'pass'             => true,
			'rank'             => '-',
			'time'             => time(),
			'pro_quizid'       => $quiz_meta['sfwd-quiz_quiz_pro'],
			'course'           => $course_id,
			'points'           => 0,
			'total_points'     => 0,
			'percentage'       => 100,
			'timespent'        => 0,
			'has_graded'       => false,
			'statistic_ref_id' => 0,
			'm_edit_by'        => get_current_user_id(),  // Manual Edit By ID.
			'm_edit_time'      => time(),          // Manual Edit timestamp.
		);

		$quizz_progress[] = $quizdata;

		$quizdata_pass = true;

		// Then we add the quiz entry to the activity database.
		learndash_update_user_activity(
			array(
				'course_id'          => $course_id,
				'user_id'            => $user_id,
				'post_id'            => $quiz_id,
				'activity_type'      => 'quiz',
				'activity_action'    => 'insert',
				'activity_status'    => $quizdata_pass,
				'activity_started'   => $quizdata['time'],
				'activity_completed' => $quizdata['time'],
				'activity_meta'      => $quizdata,
			)
		);

		do_action( 'learndash_quiz_completed', $quizdata, get_user_by( 'ID', $user_id ) );
		update_user_meta( $user_id, '_sfwd-quizzes', $quizz_progress );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function uo_course_dashboard( $atts ) {
		ob_start();

		$allowed_roles = apply_filters(
			'ulgm_gm_allowed_roles',
			array(
				'administrator',
				'manage_options',
				'group_leader',
				'ulgm_group_management',
			)
		);
		// Is the user a group leader
		if ( ! array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {
			return __( 'You must be a admin or group leader to access this page.', 'uncanny-learndash-groups' );
		}

		if ( ! ulgm_filter_has_var( 'user-id' ) || 0 === absint( ulgm_filter_input( 'user-id' ) ) ) {

			echo do_shortcode( '[uo_ajax_search]' );

			return ob_get_clean();
		}

		//      if ( ( ! ulgm_filter_has_var( 'group-id' ) || 0 === absint( ulgm_filter_input( 'group-id' ) ) ) && ! current_user_can( 'manage_options' ) ) {
		//          echo '<p>' . __( 'Please select group first before managing user progress.', 'uncanny-learndash-groups' ) . '</p>';
		//          echo do_shortcode( '[uo_ajax_search]' );
		//
		//          return ob_get_clean();
		//      }

		if ( ulgm_filter_has_var( 'user-id' ) ) {
			if ( get_current_user_id() === absint( ulgm_filter_input( 'user-id' ) ) ) {
				echo '<p><i>' . sprintf( __( 'You cannot manage your own %s progress.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ) . '</i></p>';
				echo do_shortcode( '[uo_ajax_search]' );

				return ob_get_clean();
			}

			$user = get_user_by( 'ID', absint( ulgm_filter_input( 'user-id' ) ) );
			if ( ! $user ) {
				echo '<p><i>' . __( 'This user no longer exists. Please select another user.', 'uncanny-learndash-groups' ) . '</i></p>';
				echo do_shortcode( '[uo_ajax_search]' );

				return ob_get_clean();
			}
		}

		if ( ( current_user_can( 'group_leader' ) || current_user_can( 'ulgm_group_management' ) ) && ! current_user_can( 'manage_options' ) ) {
			if ( ! learndash_is_group_leader_of_user( get_current_user_id(), absint( ulgm_filter_input( 'user-id' ) ) ) ) {
				echo '<p><i>' . __( 'You are not the admin of this user.', 'uncanny-learndash-groups' ) . '</i></p>';
				echo do_shortcode( '[uo_ajax_search]' );

				return ob_get_clean();
			}
		}

		// Clean (erase) the output buffer and turn off output buffering
		ob_end_clean();

		return self::template_3_0( $atts );
	}

	/**
	 * @param null $user_id
	 * @param null $course_id
	 *
	 * @return bool
	 */
	public static function can_manage_user_progress( $user_id = null, $course_id = null ) {
		// Set default value
		$can_manage_progress = false;

		// Check if the option to manage progress is checked
		if ( get_option( 'allow_group_leaders_to_manage_progress', 'no' ) == 'yes' ) {
			// Check is $user_id is defined
			if ( isset( $user_id ) ) {
				// Check if the user is trying to edit his own progress
				if ( get_current_user_id() != absint( $user_id ) ) {
					// Check if the user exists
					// To do that, get user data
					$user = get_userdata( absint( $user_id ) );

					// Check if there is data
					if ( $user !== false ) {
						// Check if the user is an admin
						if ( current_user_can( 'manage_options' ) ) {
							// Set $can_manage_progress to true
							$can_manage_progress = true;
						} // Check if the user is a group leader
						elseif ( ( current_user_can( 'group_leader' ) || current_user_can( 'ulgm_group_management' ) ) && ! current_user_can( 'manage_options' ) ) {
							// Check if it can edit the progress of this user
							if ( learndash_is_group_leader_of_user( get_current_user_id(), absint( $user_id ) ) ) {

								// If a course ID was passed, check if the group leader can manage it
								if ( null !== $course_id ) {
									if ( absint( $course_id ) ) {
										$group_leader_courses = learndash_get_group_leader_groups_courses( get_current_user_id() );
										if ( in_array( $course_id, $group_leader_courses ) ) {
											$can_manage_progress = true;
										}
									}
								} else {
									// Set $can_manage_progress to true
									$can_manage_progress = true;
								}
							}
						}
					}
				}
			}
		} else {
			if ( isset( $user_id ) ) {
				// Check if the user is trying to edit his own progress
				if ( get_current_user_id() != absint( $user_id ) ) {
					// Check if the user exists
					// To do that, get user data
					$user = get_userdata( absint( $user_id ) );

					// Check if there is data
					if ( $user !== false ) {
						// Check if the user is an admin
						if ( current_user_can( 'manage_options' ) ) {
							// Set $can_manage_progress to true
							$can_manage_progress = true;
						}
					}
				}
			}
		}

		return $can_manage_progress;
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function template_3_0( $atts ) {

		if ( is_string( $atts ) ) {
			$atts = array();
		}

		// Get user colors
		$user_colors = (object) array(
			'toggle'         => (object) array(
				'expanded_bg'   => '',
				'expanded_icon' => '',
				'disabled_bg'   => '',
			),
			'quiz'           => (object) array(
				'passed_bg' => '', //self::get_settings_value( 'uo_dashboard_color_quiz_passed_bg', __CLASS__ ),
				'failed_bg' => '', //self::get_settings_value( 'uo_dashboard_color_quiz_failed_bg', __CLASS__ )
			),
			'progress'       => '', //self::get_settings_value( 'uo_dashboard_color_progress', __CLASS__ ),
			'third_level_bg' => '', //self::get_settings_value( 'uo_dashboard_color_third_level', __CLASS__ )
		);

		// Check if it has custom colors
		$has_custom_colors = ! empty( $user_colors->toggle->expanded_bg ) || ! empty( $user_colors->toggle->expanded_icon ) || ! empty( $user_colors->toggle->disabled_bg ) || ! empty( $user_colors->quiz->passed_bg ) || ! empty( $user_colors->quiz->failed_bg ) || ! empty( $user_colors->progress ) || ! empty( $user_colors->third_level_bg );

		$user_id  = self::set_user_id( $atts );
		$group_id = self::set_group_id( $atts );

		$tax_query     = array();
		$ld_categories = array();
		$categories    = array();

		if ( ! isset( $atts['ld_category'] ) || '' === $atts['ld_category'] ) {
			$atts['ld_category'] = 'all';
		}
		if ( 'all' !== $atts['ld_category'] ) {
			$tax_query = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'slug',
					'terms'    => array( sanitize_text_field( $atts['ld_category'] ) ),
				),
			);
		}

		if ( ! isset( $atts['category'] ) || '' === $atts['category'] ) {
			$atts['category'] = 'all';
		}
		if ( 'all' !== $atts['category'] ) {
			$tax_query[] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( sanitize_text_field( $atts['category'] ) ),
			);
		}

		if ( isset( $atts['categoryselector'] ) && 'hide' !== $atts['categoryselector'] ) {
			$get_categories_args = array(
				'taxonomy'   => 'category',
				'type'       => 'sfwd-courses',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);

			$categories = get_categories( $get_categories_args );

			if ( ( ulgm_filter_has_var( 'catid' ) ) && ( ! empty( ulgm_filter_input( 'catid' ) ) ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => intval( ulgm_filter_input( 'catid' ) ),
				);
			}
		}
		if ( isset( $atts['course_categoryselector'] ) && 'hide' !== $atts['course_categoryselector'] ) {
			$get_categories_args = array(
				'taxonomy'   => 'ld_course_category',
				'type'       => 'sfwd-courses',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);
			$ld_categories       = get_categories( $get_categories_args );

			if ( ( ulgm_filter_has_var( 'course_catid' ) ) && ( ! empty( ulgm_filter_input( 'course_catid' ) ) ) ) {
				$tax_query[] = array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'term_id',
					'terms'    => intval( ulgm_filter_input( 'course_catid' ) ),
				);
			}
		}

		// Format $categories
		$wp_categories = array_filter(
			$categories,
			function ( $category ) {
				// Get number of posts
				$posts = get_posts( 'post_type=sfwd-courses&category=' . $category->term_id );
				$count = count( $posts );

				// Check if it has posts
				return $count > 0;
			}
		);

		$wp_categories = array_map(
			function ( $category ) {
				// Check if it's selected
				$selected = false;

				if ( ulgm_filter_has_var( 'catid' ) || ulgm_filter_has_var( 'course_catid' ) ) {
					if ( ulgm_filter_has_var( 'catid' ) && absint( ulgm_filter_input( 'catid' ) ) ) {
						if ( $category->term_id === absint( ulgm_filter_input( 'catid' ) ) ) {
							$selected = true;
						}
					}
				} else {
					if ( isset( $atts['category'] ) ) {
						if ( $atts['category'] === $category->slug ) {
							$selected = true;
						}
					}
				}

				return (object) array(
					'id'                => $category->term_id,
					'title'             => $category->name,
					'number_of_courses' => $category->category_count,
					'has_courses'       => $category->category_count > 0,
					'is_selected'       => $selected,
				);
			},
			$wp_categories
		);

		// Format $ld_categories
		$ld_categories = array_filter(
			$ld_categories,
			function ( $category ) {
				// Get number of posts
				$args = array(
					'post_type'      => 'sfwd-courses',
					'post_status'    => 'publish',
					'posts_per_page' => 999,
					'tax_query'      => array(
						array(
							'taxonomy' => 'ld_course_category',
							'field'    => 'term_id',
							'terms'    => $category->term_id,
						),
					),
				);

				$posts = get_posts( $args );
				$count = count( $posts );

				// Check if it has posts
				return $count > 0;
			}
		);

		$ld_categories = array_map(
			function ( $category ) {
				// Check if it's selected
				$selected = false;

				if ( ulgm_filter_has_var( 'course_catid' ) || ulgm_filter_has_var( 'catid' ) ) {
					if ( ulgm_filter_has_var( 'course_catid' ) && absint( ulgm_filter_input( 'course_catid' ) ) ) {
						if ( $category->term_id === absint( ulgm_filter_input( 'course_catid' ) ) ) {
							$selected = true;
						}
					}
				} else {
					if ( isset( $atts['ld_category'] ) ) {
						if ( $atts['ld_category'] === $category->slug ) {
							$selected = true;
						}
					}
				}

				return (object) array(
					'id'                => $category->term_id,
					'title'             => $category->name,
					'number_of_courses' => $category->category_count,
					'has_courses'       => $category->category_count > 0,
					'is_selected'       => $selected,
				);
			},
			$ld_categories
		);

		$has_wp_category_dropdown = ! empty( $wp_categories );
		$has_ld_category_dropdown = ! empty( $ld_categories );

		if ( isset( $atts['orderby'] ) ) {

			// Make a correct order by value isset
			$allowed_order_by = array( 'ID', 'title', 'date', 'menu_order' );
			if ( in_array( $atts['orderby'], $allowed_order_by ) ) {
				$order_by = $atts['orderby'];
			} else {
				return __( 'The order by value is not of the type title, date, or menu_order.', 'uncanny-learndash-groups' );
			}
		} else {
			$order_by = 'ID';
		}

		if ( isset( $atts['order'] ) ) {

			// Make a correct order value isset
			$allowed_order = array( 'asc', 'desc' );
			if ( in_array( $atts['order'], $allowed_order ) ) {
				$order = $atts['order'];
			} else {
				return __( 'The order value is not of the type asc, or desc', 'uncanny-learndash-groups' );
			}
		} else {
			$order = 'desc';
		}

		// Set sorting
		$sort_atts = array(
			'order'   => $order,
			'orderby' => $order_by,
		);

		if ( ! empty( $tax_query ) ) {
			$sort_atts['tax_query'] = $tax_query;
		}

		if ( ! function_exists( 'ld_get_mycourses' ) ) {
			return __( 'ld_get_mycourses function is not available.', 'uncanny-learndash-groups' );
		}

		if ( 0 === $user_id ) {

			if ( isset( $atts['show'] ) ) {

				if ( 'open' === $atts['show'] ) {
					// Get open courses for logged out users
					$user_courses = learndash_get_open_courses();
					// Not filter available for open courses.
					$ld_categories = array();
					$categories    = array();
				} elseif ( 'all' === $atts['show'] ) {
					// Show all courses
					$course_query_args = array(
						'post_type'      => 'sfwd-courses',
						'post_status'    => 'publish',
						'posts_per_page' => 999,
						'tax_query'      => array(),
					);
					if ( ! empty( $tax_query ) ) {
						$course_query_args['tax_query'] = $tax_query;
					}

					if ( ! empty( $sort_atts ) ) {
						$course_query_args['order']   = $sort_atts['order'];
						$course_query_args['orderby'] = $sort_atts['orderby'];
					}

					$courses      = get_posts( $course_query_args );
					$user_courses = wp_list_pluck( $courses, 'ID' );
				} else {
					return '';
				}
			} else {

				return '';

			}
		} else {
			$user_courses = ld_get_mycourses( $user_id, $sort_atts );
			if ( ! current_user_can( 'manage_options' ) ) {
				$user_groups = learndash_get_users_group_ids( $user_id );
				$gl_groups   = learndash_get_administrators_group_ids( get_current_user_id(), true );
				$common      = array_intersect( $user_groups, $gl_groups );
				if ( empty( $common ) ) {
					$user_courses = array();
				}
				$group_courses = array();
				foreach ( $common as $c ) {
					$group_courses = array_merge( learndash_group_enrolled_courses( $c ), $group_courses );
				}
				$user_courses = array_unique( $group_courses );
			}
		}

		$user_courses = apply_filters( 'ulgm_progress_report_user_courses', $user_courses, $user_id, $group_id, $sort_atts );

		// Get all users attempted and completed quizzes
		$quiz_attempts = self::get_all_quiz_attemps( $user_id );

		$quiz_attempts = apply_filters( 'ulgm_progress_report_user_quizzes', $quiz_attempts, $user_id, $group_id );

		$courses = self::set_up_course_object( $user_courses, $user_id, $quiz_attempts, $group_id );

		$courses = apply_filters( 'ulgm_progress_report_courses', $courses, $user_courses, $user_id, $quiz_attempts, $group_id );

		if ( isset( $atts['expand_by_default'] ) && 'yes' === $atts['expand_by_default'] ) {
			$expanded_on_load = true;
		} else {
			$expanded_on_load = false;
		}

		// Check if the user can manage this user
		$can_manage_progress = self::can_manage_user_progress( ulgm_filter_input( 'user-id' ) );

		//Check to see if the file is in template to override default template.
		$file_path = get_stylesheet_directory() . '/uncanny-learndash-groups/templates/frontend-course-management/dashboard-template-3_0.php';

		if ( ! file_exists( $file_path ) ) {
			//$file_path = apply_filters( 'uo-dashboard-template-3-0', self::get_template( 'frontend-dashboard/dashboard-template-3_0.php', dirname( dirname( __FILE__ ) ) . '/src' ) );
			$file_path = apply_filters( 'uo-dashboard-template-3-0', Utilities::get_template( 'frontend-course-management/dashboard-template-3_0.php' ), UNCANNY_GROUPS_PLUGIN . '/src/templates/frontend-course-management' );
		}

		$level = ob_get_level();
		ob_start();

		\LD_QuizPro::showModalWindow();

		echo do_shortcode( '[uo_ajax_search]' );

		include $file_path;

		return ob_get_clean();
	}

	/**
	 * @param $atts
	 *
	 * @return string|void
	 */
	public static function legacy_template( $atts ) {

		if ( is_string( $atts ) ) {
			$atts = array();
		}

		$tax_query     = array();
		$ld_categories = array();
		$categories    = array();
		if ( isset( $atts['user_id'] ) && '' !== $atts['user_id'] ) {
			$user_id = absint( $atts['user_id'] );
		} else {
			$current_user = wp_get_current_user();

			if ( empty( $current_user->ID ) ) {
				$user_id = 0;
			} else {
				$user_id = $current_user->ID;
			}
		}

		if ( ! isset( $atts['ld_category'] ) || '' === $atts['ld_category'] ) {
			$atts['ld_category'] = 'all';
		}
		if ( 'all' !== $atts['ld_category'] ) {
			$tax_query = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'slug',
					'terms'    => array( sanitize_text_field( $atts['ld_category'] ) ),
				),
			);
		}

		if ( ! isset( $atts['category'] ) || '' === $atts['category'] ) {
			$atts['category'] = 'all';
		}
		if ( 'all' !== $atts['category'] ) {
			$tax_query[] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( sanitize_text_field( $atts['category'] ) ),
			);
		}

		if ( isset( $atts['categoryselector'] ) && 'hide' !== $atts['categoryselector'] ) {
			$get_categories_args = array(
				'taxonomy'   => 'category',
				'type'       => 'sfwd-courses',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);
			$categories          = get_categories( $get_categories_args );

			if ( ( ulgm_filter_has_var( 'catid' ) ) && ( ! empty( ulgm_filter_input( 'catid' ) ) ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => intval( ulgm_filter_input( 'catid' ) ),
				);
			}
		}
		if ( isset( $atts['course_categoryselector'] ) && 'hide' !== $atts['course_categoryselector'] ) {
			$get_categories_args = array(
				'taxonomy'   => 'ld_course_category',
				'type'       => 'sfwd-courses',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);
			$ld_categories       = get_categories( $get_categories_args );

			if ( ( ulgm_filter_has_var( 'course_catid' ) ) && ( ! empty( ulgm_filter_input( 'course_catid' ) ) ) ) {
				$tax_query[] = array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'term_id',
					'terms'    => intval( ulgm_filter_input( 'course_catid' ) ),
				);
			}
		}

		if ( isset( $atts['orderby'] ) ) {

			// Make a correct order by value isset
			$allowed_order_by = array( 'ID', 'title', 'date', 'menu_order' );
			if ( in_array( $atts['orderby'], $allowed_order_by ) ) {
				$order_by = $atts['orderby'];
			} else {
				return __( 'The order by value is not of the type title, date, or menu_order.', 'uncanny-learndash-groups' );
			}
		} else {
			$order_by = 'ID';
		}

		if ( isset( $atts['order'] ) ) {

			// Make a correct order value isset
			$allowed_order = array( 'asc', 'desc' );
			if ( in_array( $atts['order'], $allowed_order ) ) {
				$order = $atts['order'];
			} else {
				return __( 'The order value is not of the type asc, or desc', 'uncanny-learndash-groups' );
			}
		} else {
			$order = 'desc';
		}

		if ( empty( $current_user ) ) {
			$current_user = get_user_by( 'id', $user_id );
		}

		// Set sorting
		$sort_atts = array(
			'order'   => $order,
			'orderby' => $order_by,
		);
		if ( ! empty( $tax_query ) ) {
			$sort_atts['tax_query'] = $tax_query;
		}

		if ( function_exists( 'ld_get_mycourses' ) ) {

			if ( 0 === $user_id ) {

				if ( isset( $atts['show'] ) ) {

					if ( 'open' === $atts['show'] ) {
						// Get open courses for logged out users
						$user_courses = learndash_get_open_courses();
						// Not filter available for open courses.
						$ld_categories = array();
						$categories    = array();
					} elseif ( 'all' === $atts['show'] ) {
						// Show all courses
						$course_query_args = array(
							'post_type'   => 'sfwd-courses',
							'post_status' => 'publish',
						);

						if ( ! empty( $tax_query ) ) {
							$course_query_args['tax_query'] = $tax_query;
						}

						if ( ! empty( $sort_atts ) ) {
							$course_query_args['order']   = $sort_atts['order'];
							$course_query_args['orderby'] = $sort_atts['orderby'];
						}

						$courses      = get_posts( $course_query_args );
						$user_courses = wp_list_pluck( $courses, 'ID' );
					} else {
						return '';
					}
				} else {

					return '';

				}
			} else {
				$user_courses = ld_get_mycourses( $user_id, $sort_atts );
			}
		} else {
			return;
		}

		$usermeta           = get_user_meta( $user_id, '_sfwd-quizzes', true );
		$quiz_attempts_meta = empty( $usermeta ) ? false : $usermeta;
		$quiz_attempts      = array();

		if ( function_exists( 'learndash_certificate_details' ) ) {
			if ( ! empty( $quiz_attempts_meta ) ) {
				foreach ( $quiz_attempts_meta as $quiz_attempt ) {
					$c                          = learndash_certificate_details( $quiz_attempt['quiz'], $user_id );
					$quiz_attempt['post']       = get_post( $quiz_attempt['quiz'] );
					$quiz_attempt['percentage'] = ! empty( $quiz_attempt['percentage'] ) ? $quiz_attempt['percentage'] : ( ! empty( $quiz_attempt['count'] ) ? $quiz_attempt['score'] * 100 / $quiz_attempt['count'] : 0 );

					if ( $user_id == get_current_user_id() && ! empty( $c['certificateLink'] ) && ( ( isset( $quiz_attempt['percentage'] ) && $quiz_attempt['percentage'] >= $c['certificate_threshold'] * 100 ) ) ) {
						$quiz_attempt['certificate'] = $c;
					}
					$quiz_attempts[ learndash_get_course_id( $quiz_attempt['quiz'] ) ][] = $quiz_attempt;
				}
			}
		}
		$args = array(
			'user_id'       => $user_id,
			'quiz_attempts' => $quiz_attempts,
			'current_user'  => $current_user,
			'user_courses'  => $user_courses,
			'categories'    => $categories,
			'ld_categories' => $ld_categories,
			'settings'      => $atts,
		);

		//Check to see if the file is in template to override default template.
		$file_path = get_stylesheet_directory() . '/uncanny-learndash-groups/templates/frontend-dashboard/dashboard-template.php';

		if ( ! file_exists( $file_path ) ) {
			$file_path = apply_filters( 'uo-dashboard-template', 'uo_dashboard_get_template' );
		}

		extract( $args );
		$level = ob_get_level();
		ob_start();
		echo do_shortcode( '[uo_ajax_search]' );
		include $file_path;

		$contents = learndash_ob_get_clean( $level );
		/**
		 * @since 2.4.2
		 */
		if ( isset( $atts['expand_by_default'] ) && 'yes' === $atts['expand_by_default'] ) {
			$contents = '<script>(function($){$(document).ready(function(){flip_expand_all("#course_list");});})(jQuery);</script>' . $contents;
		}

		return $contents;

	}

	/**
	 * Set the user id
	 *
	 * @param $atts
	 *
	 * @return int
	 */
	public static function set_user_id( $atts ) {

		if ( ulgm_filter_has_var( 'user-id' ) ) {
			$user_id         = absint( ulgm_filter_input( 'user-id' ) );
			$atts['user_id'] = $user_id;
		}

		if ( isset( $atts['user_id'] ) && '' !== $atts['user_id'] ) {
			$user_id = absint( $atts['user_id'] );
		} else {
			$current_user = wp_get_current_user();

			if ( empty( $current_user->ID ) ) {
				$user_id = 0;
			} else {
				$user_id = $current_user->ID;
			}
		}

		return $user_id;
	}

	/**
	 * Set the user id
	 *
	 * @param $atts
	 *
	 * @return int
	 */
	public static function set_group_id( $atts ) {

		if ( ulgm_filter_has_var( 'group-id' ) ) {
			$group_id         = absint( ulgm_filter_input( 'group-id' ) );
			$atts['group_id'] = $group_id;
		}

		if ( isset( $atts['group_id'] ) && '' !== $atts['group_id'] ) {
			$group_id = absint( $atts['group_id'] );
		} else {
			$group_id = GroupManagementInterface::$ulgm_current_managed_group_id;
			if ( current_user_can( 'manage_options' ) ) {
				$group_id = '-1';
			}
		}

		return $group_id;
	}

	/**
	 * Get a list on all quiz quiz attempts for each module
	 *
	 * @param $user_id
	 *
	 * @return array
	 */
	public static function get_all_quiz_attemps( $user_id ) {

		$usermeta           = get_user_meta( $user_id, '_sfwd-quizzes', true );
		$quiz_attempts_meta = empty( $usermeta ) ? false : $usermeta;
		$quiz_attempts      = array();

		if ( function_exists( 'learndash_certificate_details' ) ) {
			if ( ! empty( $quiz_attempts_meta ) ) {
				foreach ( $quiz_attempts_meta as $quiz_attempt ) {
					$c                          = learndash_certificate_details( $quiz_attempt['quiz'], $user_id );
					$quiz_attempt['percentage'] = ! empty( $quiz_attempt['percentage'] ) ? $quiz_attempt['percentage'] : ( ! empty( $quiz_attempt['count'] ) ? $quiz_attempt['score'] * 100 / $quiz_attempt['count'] : 0 );

					if ( $user_id == get_current_user_id() && ! empty( $c['certificateLink'] ) && ( ( isset( $quiz_attempt['percentage'] ) && $quiz_attempt['percentage'] >= $c['certificate_threshold'] * 100 ) ) ) {
						$quiz_attempt['certificate'] = $c;
					}

					$parent_post = null;
					if ( ! empty( $quiz_attempt['topic'] ) ) {
						$parent_post = $quiz_attempt['topic'];
					} elseif ( ! empty( $quiz_attempt['lesson'] ) ) {
						$parent_post = $quiz_attempt['lesson'];
					} elseif ( ! empty( $quiz_attempt['course'] ) ) {
						$parent_post = $quiz_attempt['course'];
					}

					if ( ! empty( $parent_post ) ) {
						//                      if ( isset( $quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ] ) ) {
						//                          if ( $quiz_attempt['percentage'] >= $quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ]['percentage'] ) {
						//                              if ( $quiz_attempt['completed'] >= $quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ]['completed'] ) {
						//                                  $quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ] = $quiz_attempt;
						//                              }
						//                          }
						//                      } else {
						$quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ][] = $quiz_attempt;
						//                      }
					}
				}
			}
		}

		return $quiz_attempts;
	}

	/**
	 * Create an hierachal object of course lessons, topics, and quiz assocatd user data
	 *
	 * @param $user_courses
	 * @param $user_id
	 * @param $quiz_attempts
	 * @param $group_id
	 *
	 * @return array
	 */
	public static function set_up_course_object( $user_courses, $user_id, $quiz_attempts, $group_id ) {
		$courses = array();
		foreach ( $user_courses as $course_id ) {
			if ( ! learndash_is_group_leader_of_user( get_current_user_id(), $user_id ) && ! current_user_can( 'manage_options' ) ) {
				continue;
			}
			$status = apply_filters_deprecated(
				'ulgm_include_user_direct_enrolled_courses',
				array(
					false,
					$user_id,
					$user_courses,
					$course_id,
					wp_get_current_user(),
				),
				'4.2.2'
			);

			$course          = get_post( $course_id );
			$course_progress = (array) learndash_course_progress(
				array(
					'user_id'   => $user_id,
					'course_id' => $course_id,
					'array'     => true,
				)
			);
			$course_status   = learndash_course_status( $course_id, $user_id, true );

			$course_certificate = learndash_get_course_certificate_link( $course_id, $user_id );
			$has_certificate    = true;
			if ( empty( $course_certificate ) ) {
				$course_certificate = null;
				$has_certificate    = false;
			}

			$last_know_step = get_user_meta( $user_id, 'learndash_last_known_course_' . $course_id, true );
			$resume_url     = null;
			$has_resume_url = false;

			// User has not hit a LD module yet
			if ( ! empty( $last_know_step ) && absint( $last_know_step ) ) {
				$step_id               = $last_know_step;
				$last_know_post_object = get_post( (int) $step_id );

				if ( null !== $last_know_post_object ) {
					$has_resume_url = true;
					if ( function_exists( 'learndash_get_step_permalink' ) ) {
						$resume_url = learndash_get_step_permalink( $step_id, $course_id );
					} else {
						$resume_url = get_permalink( $step_id );
					}
				}
			}

			$course_progress['total'] = learndash_get_course_steps_count( $course_id );

			if ( ! isset( $course_progress['percentage'] ) ) {
				$course_progress['percentage'] = 0;
				if ( absint( $course_progress['completed'] ) && absint( $course_progress['total'] ) ) {
					$course_progress['percentage'] = number_format( ( ( $course_progress['completed'] / $course_progress['total'] ) * 100 ), 0 );
				}
			}

			$courses[ $course_id ] = (object) array(
				'id'              => $course_id,
				'title'           => $course->post_title, // string
				'url'             => get_permalink( $course ), // string
				'progress'        => $course_progress['percentage'] > 100 ? 100 : $course_progress['percentage'],
				'status'          => $course_status,
				'has_lessons'     => false, // boolean
				'has_quizzes'     => false, // boolean
				'quizzes'         => array(), // array
				'has_certificate' => $has_certificate, // boolean
				'has_resume_url'  => $has_resume_url, // boolean
				'certificate_url' => $course_certificate, // string or null
				'resume_url'      => $resume_url, // string or null
			);

			$quizzes = learndash_get_course_quiz_list( $course_id );

			if ( ! empty( $quizzes ) ) {
				foreach ( $quizzes as $key => $quiz ) {

					if ( function_exists( 'learndash_get_step_permalink' ) ) {
						$quiz_url = learndash_get_step_permalink( $quiz['post']->ID, $course_id );
					} else {
						$quiz_url = get_permalink( $quiz['post']->ID );
					}

					$quiz_completed                     = learndash_is_quiz_complete( $user_id, $quiz['post']->ID, $course_id );
					$courses[ $course_id ]->has_quizzes = true;

					$has_certificate = false;
					$certificate_url = '';

					if ( isset( $quiz_attempts[ $course_id ] ) &&
						 isset( $quiz_attempts[ $course_id ][ $quiz['post']->ID ] ) &&
						 ! empty( $quiz_attempts[ $course_id ][ $quiz['post']->ID ] )
					) {
						$last_attempt = array_values( array_slice( $quiz_attempts[ $course_id ][ $quiz['post']->ID ], - 1 ) )[0];
						if ( isset( $last_attempt['percentage'] ) && 0 != $last_attempt['percentage'] ) {
							$c = learndash_certificate_details( $quiz['post']->ID, $user_id );
							if ( ! empty( $c['certificateLink'] ) ) {
								$has_certificate = true;
								$certificate_url = $c['certificateLink'];
							}
						}
					}

					$courses[ $course_id ]->quizzes[] = // array of objects
							(object) array(
								'id'               => $quiz['post']->ID, // int
								'title'            => $quiz['post']->post_title, // string
								'url'              => $quiz_url, // string
								'taken_on'         => 0, // timestamp null
								'score'            => 0, // int null
								'passed'           => false, // boolean
								'is_completed'     => $quiz_completed, // boolean
								'has_certificate'  => $has_certificate, // boolean
								'has_statistics'   => false, // boolean
								'certificate_url'  => $certificate_url, // string
								'statistics_url'   => '#', // string
								'pro_quizid'       => '',
								'statistic_ref_id' => '',
								'statistics_nonce' => '',
							);

					if ( 1 === 0 && isset( $quiz_attempts[ $course_id ][ $quiz['post']->ID ] ) &&
						 ! empty( $quiz_attempts[ $course_id ][ $quiz['post']->ID ] )
					) {
						$courses[ $course_id ]->has_quizzes = true;
						$module_quiz_attempts               = $quiz_attempts[ $course_id ][ $quiz['post']->ID ];
						foreach ( $module_quiz_attempts as $attempt ) {

							$statistic_ref_id = $attempt['statistic_ref_id'];
							$pro_quizid       = $attempt['pro_quizid'];
							$is_completed     = true;
							$taken_on         = $attempt['completed'];
							$score            = $attempt['percentage'];
							$statistics_nonce = wp_create_nonce( 'statistic_nonce_' . $statistic_ref_id . '_' . get_current_user_id() . '_' . $user_id );

							if ( 1 === $attempt['pass'] ) {
								$passed = true;
							} else {
								$passed = false;
							}

							$has_certificate = false;
							$certificate_url = '';
							if ( isset( $attempt['certificate'] ) ) {
								$has_certificate = true;
								$certificate_url = $attempt['certificate']['certificateLink'];
							}

							$courses[ $course_id ]->quizzes[] = // array of objects
									(object) array(
										'id'               => $quiz['post']->ID, // int
										'title'            => $quiz['post']->post_title, // string
										'url'              => $quiz_url, // string
										'taken_on'         => $taken_on, // timestamp null
										'score'            => $score, // int null
										'passed'           => $passed, // boolean
										'is_completed'     => $is_completed, // boolean
										'has_certificate'  => $has_certificate, // boolean
										'has_statistics'   => true, // boolean
										'certificate_url'  => $certificate_url, // string
										'statistics_url'   => '#', // string
										'pro_quizid'       => $pro_quizid,
										'statistic_ref_id' => $statistic_ref_id,
										'statistics_nonce' => $statistics_nonce,
									);
						}
					}
				}
			}

			$lessons = learndash_get_course_lessons_list( $course, $user_id, array( 'per_page' => 9999 ) );

			if ( ! empty( $lessons ) ) {
				$courses[ $course_id ]->has_lessons = true;
				foreach ( $lessons as $lesson ) {

					$is_completed = false;
					if ( 'completed' === $lesson['status'] ) {
						$is_completed = true;
					}

					$is_available = true;
					$available_on = null;
					if ( ! empty( $lesson['lesson_access_from'] ) ) {
						$is_available = false;
						$available_on = $lesson['lesson_access_from'];
					}

					$courses[ $course_id ]->lessons[ $lesson['post']->ID ] =
							(object) array(
								'id'           => $lesson['post']->ID, // int
								'title'        => $lesson['post']->post_title, // string
								'url'          => $lesson['permalink'], // string
								'has_quizzes'  => false, // boolean

								'is_completed' => $is_completed, // boolean

								'is_available' => $is_available, // boolean
								'available_on' => $available_on, // timestamp || null

								'quizzes'      => array(),
							);

					$topics = learndash_topic_dots( $lesson['post']->ID, false, 'array', $user_id, $course_id );

					$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_topics = false;
					if ( ! empty( $topics ) ) {
						$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_topics = true;
						foreach ( $topics as $key => $topic ) {

							if ( function_exists( 'learndash_get_step_permalink' ) ) {
								$topic_url = learndash_get_step_permalink( $topic->ID, $course_id );
							} else {
								$topic_url = get_permalink( $topic->ID );
							}

							$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->topics[ $topic->ID ] = // array of objects
									(object) array(
										'id'           => $topic->ID, // int
										'title'        => $topic->post_title, // string
										'url'          => $topic_url, // string
										// Temporary hardcoded value
										'has_quizzes'  => false,
										'quizzes'      => array(),
										'is_completed' => ! empty( $topic->completed ) ? true : false, // boolean
									);

							$quizzes = learndash_get_lesson_quiz_list( $topic->ID, null, $course_id );

							if ( ! empty( $quizzes ) ) {
								foreach ( $quizzes as $key => $quiz ) {

									if ( function_exists( 'learndash_get_step_permalink' ) ) {
										$quiz_url = learndash_get_step_permalink( $quiz['post']->ID, $course_id );
									} else {
										$quiz_url = get_permalink( $quiz['post']->ID );
									}

									$quiz_completed                                                                           = learndash_is_quiz_complete( $user_id, $quiz['post']->ID, $course_id );
									$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->topics[ $topic->ID ]->has_quizzes = true;

									$has_certificate = false;
									$certificate_url = '';

									if ( isset( $quiz_attempts[ $course_id ] ) &&
										 isset( $quiz_attempts[ $course_id ][ $quiz['post']->ID ] ) &&
										 ! empty( $quiz_attempts[ $course_id ][ $quiz['post']->ID ] )
									) {
										$last_attempt = array_values( array_slice( $quiz_attempts[ $course_id ][ $quiz['post']->ID ], - 1 ) )[0];
										if ( isset( $last_attempt['percentage'] ) && 0 != $last_attempt['percentage'] ) {
											$c = learndash_certificate_details( $quiz['post']->ID, $user_id );
											if ( ! empty( $c['certificateLink'] ) ) {
												$has_certificate = true;
												$certificate_url = $c['certificateLink'];
											}
										}
									}

									$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->topics[ $topic->ID ]->quizzes[] = // array of objects
											(object) array(
												'id'       => $quiz['post']->ID, // int
												'title'    => $quiz['post']->post_title, // string
												'url'      => $quiz_url, // string

												'taken_on' => 0, // timestamp null

												'score'    => 0, // int null
												'passed'   => false, // boolean

												'is_completed' => $quiz_completed, // boolean

												'has_certificate' => $has_certificate, // boolean
												'has_statistics' => false, // boolean

												'certificate_url' => $certificate_url, // string
												'statistics_url' => '#', // string
												'pro_quizid' => '',
												'statistic_ref_id' => '',
												'statistics_nonce' => '',
											);
								}
							}
						}
					}

					$quizzes = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id, $course_id );

					$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_quizzes = false;
					if ( ! empty( $quizzes ) ) {
						foreach ( $quizzes as $key => $quiz ) {

							if ( function_exists( 'learndash_get_step_permalink' ) ) {
								$quiz_url = learndash_get_step_permalink( $quiz['post']->ID, $course_id );
							} else {
								$quiz_url = get_permalink( $quiz['post']->ID );
							}

							$quiz_completed                                                     = learndash_is_quiz_complete( $user_id, $quiz['post']->ID, $course_id );
							$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_quizzes = true;

							$has_certificate = false;
							$certificate_url = '';

							if ( isset( $quiz_attempts[ $lesson['post']->ID ] ) &&
								 isset( $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ] ) &&
								 ! empty( $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ] )
							) {
								$last_attempt = array_values( array_slice( $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ], - 1 ) )[0];
								if ( isset( $last_attempt['percentage'] ) && 0 != $last_attempt['percentage'] ) {
									$c = learndash_certificate_details( $quiz['post']->ID, $user_id );
									if ( ! empty( $c['certificateLink'] ) ) {
										$has_certificate = true;
										$certificate_url = $c['certificateLink'];
									}
								}
							}

							$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->quizzes[] = // array of objects
									(object) array(
										'id'               => $quiz['post']->ID, // int
										'title'            => $quiz['post']->post_title, // string
										'url'              => $quiz_url, // string

										'taken_on'         => 0, // timestamp null

										'score'            => 0, // int null
										'passed'           => false, // boolean

										'is_completed'     => $quiz_completed, // boolean

										'has_certificate'  => $has_certificate, // boolean
										'has_statistics'   => false, // boolean

										'certificate_url'  => $certificate_url, // string
										'statistics_url'   => '#', // string
										'pro_quizid'       => '',
										'statistic_ref_id' => '',
										'statistics_nonce' => '',
									);

							if ( 0 == 1 &&
								 isset( $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ] ) &&
								 ! empty( $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ] )
							) {
								$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_quizzes = true;

								$module_quiz_attempts = $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ];
								foreach ( $module_quiz_attempts as $attempt ) {

									$statistic_ref_id = $attempt['statistic_ref_id'];
									$pro_quizid       = $attempt['pro_quizid'];
									$is_completed     = true;
									$taken_on         = $attempt['completed'];
									$score            = $attempt['percentage'];
									$statistics_nonce = wp_create_nonce( 'statistic_nonce_' . $statistic_ref_id . '_' . get_current_user_id() . '_' . $user_id );

									if ( 1 === $attempt['pass'] ) {
										$passed = true;
									}

									$has_certificate = false;
									$certificate_url = '';
									if ( isset( $attempt['certificate'] ) ) {
										$has_certificate = true;
										$certificate_url = $attempt['certificate']['certificateLink'];
									}

									$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->quizzes[] = // array of objects
											(object) array(
												'id'       => $quiz['post']->ID, // int
												'title'    => $quiz['post']->post_title, // string
												'url'      => $quiz_url, // string

												'taken_on' => $taken_on, // timestamp null

												'score'    => $score, // int null
												'passed'   => $passed, // boolean

												'is_completed' => $is_completed, // boolean

												'has_certificate' => $has_certificate, // boolean
												'has_statistics' => true, // boolean

												'certificate_url' => $certificate_url, // string
												'statistics_url' => '#', // string
												'pro_quizid' => $pro_quizid,
												'statistic_ref_id' => $statistic_ref_id,
												'statistics_nonce' => $statistics_nonce,
											);
								}
							}
						}
					}
				}
			}
		}

		return $courses;
	}

	/**
	 * @return string
	 */
	public static function uo_dashboard_get_template() {
		$filepath = Utilities::get_template( 'frontend-course-management/dashboard-template-3_0.php' );

		return apply_filters( 'uo_dashboard_template', $filepath );
	}

	/**
	 *
	 */
	public static function enqueue_scripts_func() {
		global $post;

		if ( ! empty( $post ) ) {
			if (
					has_shortcode( $post->post_content, 'uo_groups_manage_progress' ) ||
					Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-progress-report' )
			) {
				self::enqueue_frontend_assets();
			}
		}
	}

	/**
	 * @since 3.7.5
	 * @author Agus B.
	 * @internal Saad S.
	 */
	public static function enqueue_frontend_assets() {
		global $post;
		if ( empty( $post ) ) {
			return;
		}
		wp_enqueue_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array(), Utilities::get_version() );

		wp_register_script( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.js' ), array(), Utilities::get_version() );

		// Get user id parameter
		$user_id = ulgm_filter_has_var( 'user-id' ) ? ulgm_filter_input( 'user-id' ) : null;

		$learndash_version_compare = version_compare( LEARNDASH_VERSION, '3.2', '>=' );

		wp_localize_script(
			'ulgm-frontend',
			'ULGM_ManageProgress',
			array(
				'restUrl'           => esc_url_raw( rest_url() . 'uncanny_group_course_management/v2/' ),
				'nonce'             => \wp_create_nonce( 'wp_rest' ),
				'postNonce'         => \wp_create_nonce( 'user_progress-' . $user_id ),
				'userId'            => ulgm_filter_has_var( 'user-id' ) ? (int) ulgm_filter_input( 'user-id' ) : 'undefined',
				'canManageProgress' => self::can_manage_user_progress( $user_id ) ? 1 : 0,
				'i18n'              => array(
					'certificate' => __( 'Certificate', 'uncanny-learndash-groups' ),
				),
				'statistic_action'  => ( $learndash_version_compare ? 'wp_pro_quiz_admin_ajax_statistic_load_user' : 'wp_pro_quiz_admin_ajax' ),
			)
		);

		wp_enqueue_script( 'ulgm-frontend' );

		/**
		 * Autocomplete
		 */
		if ( ! ulgm_filter_has_var( 'group-id' ) ) {
			GroupManagementInterface::set_group_id();
			$group_id = GroupManagementInterface::$ulgm_current_managed_group_id;
		} else {
			$group_id = absint( ulgm_filter_input( 'group-id' ) );
		}
		if ( current_user_can( 'manage_options' ) ) {
			$group_id = '-1';
		}

		$base_permalink = get_permalink( $post->ID );
		$permalink      = $base_permalink;
		if ( ! isset( $_GET['lang'] ) || ! defined( 'ICL_LANGUAGE_CODE' ) ) {
			$permalink = $base_permalink . '?g=' . time();
		}
		if ( $group_id > 0 ) {
			$permalink = $base_permalink . '?group-id=' . $group_id;
		}

		wp_enqueue_style( 'jquery-ui-autocomplete', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_register_script( 'user-autocomplete', plugins_url( 'src/assets/legacy/frontend/js/autocomplete.js', Utilities::get_plugin_file() ), '', UNCANNY_GROUPS_VERSION, true );
		wp_localize_script( 'user-autocomplete', 'current_group_id', array( 'id' => $group_id ) );
		wp_localize_script( 'user-autocomplete', 'ULGM_USER_AUTOCOMPLETE_AJAX', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'user-autocomplete', 'redirect_url', array( 'url' => $permalink ) );
		wp_enqueue_script( 'user-autocomplete' );
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
	public static function handle_shortcode( $atts ) {
		if ( ! is_user_logged_in() ) {
			return __( 'You are currently not logged in.', 'uncanny-learndash-groups' );
		}
		global $post;
		ob_start();
		GroupManagementInterface::set_group_id();

		if ( ! empty( SharedFunctions::get_group_management_page_id() ) && ! empty( SharedFunctions::get_group_manage_progress_report_page_id() ) ) {
			$return_url = SharedFunctions::get_group_management_page_id( true );
			if ( ulgm_filter_has_var( 'group-id' ) && 0 !== absint( ulgm_filter_input( 'group-id' ) ) && intval( '-1' ) !== intval( ulgm_filter_input( 'group-id' ) ) ) {
				$return_url = "$return_url?group-id=" . absint( ulgm_filter_input( 'group-id' ) );
			}
			?>
			<div class="uo-groups uo-reports">
				<div class="uo-row uo-groups-section uo-groups-report-go-back">
					<div class="uo-groups-actions">
						<div class="group-management-buttons">
							<button class="ulgm-link uo-btn uo-left uo-btn-arrow-left"
									onclick="location.href='<?php echo $return_url; ?>'"
									type="button">
								<?php echo __( 'Back to Group Management', 'uncanny-learndash-groups' ); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
			<br>
			<?php
		}

		$user_name = '';
		if ( ulgm_filter_has_var( 'user-id' ) ) {
			$user = get_userdata( absint( ulgm_filter_input( 'user-id' ) ) );
			if ( $user ) {

				$can_manage_progress = false;

				// Check if the user is an admin
				if ( current_user_can( 'manage_options' ) ) {
					// Set $can_manage_progress to true
					$can_manage_progress = true;
				} // Check if the user is a group leader
				elseif ( ( current_user_can( 'group_leader' ) || current_user_can( 'ulgm_group_management' ) ) && ! current_user_can( 'manage_options' ) ) {
					// Check if it can edit the progress of this user
					if ( learndash_is_group_leader_of_user( get_current_user_id(), absint( $user->ID ) ) ) {
						// Set $can_manage_progress to true
						$can_manage_progress = true;
					}
				}

				if ( $can_manage_progress ) {
					$user_name = $user->last_name . ', ' . $user->first_name . ' [' . $user->user_email . ']';
				}
			}
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			if ( ! empty( GroupManagementInterface::$ulgm_management_shortcode ) || ! empty( GroupManagementInterface::$ulgm_managed_group_objects ) ) {
				GroupManagementInterface::$ulgm_management_shortcode['text']['group_title']           = get_the_title( GroupManagementInterface::$ulgm_current_managed_group_id );
				GroupManagementInterface::$ulgm_management_shortcode['text']['group_id_select_label'] = __( 'Select Group ID', 'uncanny-learndash-groups' );
				$group_name_selector                                                                  = true;
				?>
				<style>
					.uo-inline {
						display: inline;
					}

					.uo-looks-like-h3 {
						font-size: 25px;
					}
				</style>
				<?php
				//include_once Utilities::get_template( 'frontend-uo_groups/page-heading.php' );
			}
			?>
		<?php } ?>
		<div class="ulg-manage-progress">
			<form method="GET" id="ulg-manage-progress-user-search">
				<label>
					<div class="ulg-manage-progress__title">
						<?php _e( 'Search users', 'uncanny-learndash-groups' ); ?>
					</div>
					<div class="ulg-manage-progress__search">
						<input type="hidden" name="page" value="<?php echo $post->ID; ?>"/>
						<input type="hidden" name="group-id"
							   value="<?php echo GroupManagementInterface::$ulgm_current_managed_group_id; ?>"/>

						<div class="ulg-manage-progress-user-search-field-wrapper">
								<input type="text" id="ulg-manage-progress-user-search-field"
									placeholder="<?php _e( 'Search by user ID, name, email address or key', 'uncanny-learndash-groups' ); ?>"
									value="<?php echo $user_name; ?>"/>
						</div>
					</div>
				</label>
			</form>
		</div>

		<?php

		return ob_get_clean();
	}

	/**
	 *
	 */
	public static function search_user() {

		if ( ! is_user_logged_in() ) {
			echo wp_json_encode( array( __( 'You are not allowed to search.', 'uncanny-learndash-groups' ) ) );
		}

		$allowed       = false;
		$allowed_roles = apply_filters(
			'ulgm_gm_allowed_roles',
			array(
				'administrator',
				'manage_options',
				'group_leader',
				'ulgm_group_management',
			)
		);
		// Is the user a group leader
		if ( array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {
			$allowed = true;
		}

		if ( ! $allowed ) {
			echo wp_json_encode( array( __( 'You are not allowed to search.', 'uncanny-learndash-groups' ) ) );
		}

		// Check if a code was used in the search and return only that user if it is
		self::validate_code();

		if ( ulgm_filter_has_var( 'term' ) && ! empty( ulgm_filter_input( 'term' ) ) ) {
			$term = strtolower( ulgm_filter_input( 'term' ) );
		} elseif ( ulgm_filter_has_var( 'name' ) && ! empty( ulgm_filter_input( 'name' ) ) ) {
			$term = strtolower( ulgm_filter_input( 'name' ) );
		} else {
			echo wp_json_encode( array() );
			die();
		}
		$suggestions = array();

		$loop = get_users( array( 'search' => "*{$term}*" ) );

		if ( $loop ) {
			foreach ( $loop as $user ) {
				$suggestions[ $user->ID ] = array(
					'user_id'    => $user->ID,
					'label'      => $user->last_name . ', ' . $user->first_name . ' [' . $user->user_email . ']',
					'first_name' => $user->first_name,
					'last_name'  => $user->last_name,
					'user_email' => $user->user_email,
				);
			}
		}

		$meta_users = get_users(
			array(
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key'     => 'last_name',
						'value'   => $term,
						'compare' => 'LIKE',
					),
					array(
						'key'     => 'first_name',
						'value'   => $term,
						'compare' => 'LIKE',
					),
				),
			)
		);

		if ( $meta_users ) {
			foreach ( $meta_users as $user ) {
				$suggestions[ $user->ID ] = array(
					'user_id'    => $user->ID,
					'label'      => sprintf( '%s, %s (%s)', $user->last_name, $user->first_name, $user->user_email ),
					'first_name' => $user->first_name,
					'last_name'  => $user->last_name,
					'user_email' => $user->user_email,
				);
			}
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			// Is the user a group leader
			//Limit results to group users only
			if ( empty( self::$group_user_ids ) || empty( self::$group_association ) ) {
				self::get_all_users_of_all_groups();
			}
			$group_user_ids = self::$group_user_ids;
			//          $group_users = learndash_get_groups_users( absint( ulgm_filter_input( 'group-id' ) ), true );
			if ( empty( $group_user_ids ) ) {
				$suggestions = array( __( 'No matching users found.', 'uncanny-learndash-groups' ) );
				echo wp_json_encode( $suggestions );
				die();
			}
			//          $group_user_ids = array_column( $group_users, 'ID' );
			foreach ( $suggestions as $user_id => $val ) {
				if ( ! in_array( absint( $user_id ), $group_user_ids, true ) ) {
					unset( $suggestions[ $user_id ] );
				}
				if ( true === apply_filters( 'ulgm_append_group_name_in_list', false ) && isset( self::$group_association[ $user_id ] ) && ! empty( self::$group_association[ $user_id ]['groups'] ) ) {
					$total = count( self::$group_association[ $user_id ]['groups'] );
					if ( $total < 3 ) {
						$group_names = join( ', ', self::$group_association[ $user_id ]['groups'] );
					} else {
						$first       = array_shift( self::$group_association[ $user_id ]['groups'] );
						$group_names = sprintf( "$first %s", __( '& others', 'uncanny-learndash-groups' ) );
					}
					$suggestions[ $user_id ]['label'] = sprintf( '[%s] %s', $group_names, $val['label'] );
				}
			}

			if ( empty( $suggestions ) ) {
				$suggestions = array( 0 => __( 'No user found matching criteria in this group.', 'uncanny-learndash-groups' ) );
			}
		}

		echo wp_json_encode( $suggestions );
		die();
	}


	/**
	 * @return array|void
	 */
	private static function get_all_users_of_all_groups() {
		$group_leader_groups = learndash_get_administrators_group_ids( get_current_user_id(), true );
		if ( empty( $group_leader_groups ) ) {
			return array();
		}
		$all_users         = array();
		$group_association = array();
		foreach ( $group_leader_groups as $group_id ) {
			$group_users = learndash_get_groups_users( absint( $group_id ), true );
			if ( empty( $group_users ) ) {
				$group_association[ $group_id ] = array();
				continue;
			}
			foreach ( $group_users as $u ) {
				$group_association[ $u->ID ]['groups'][ $group_id ] = get_the_title( $group_id );
			}
			$all_users = array_merge( $group_users, $all_users );
		}
		self::$group_association = $group_association;
		self::$group_user_ids    = array_unique( array_column( $all_users, 'ID' ), SORT_NUMERIC );
	}

	/**
	 *
	 */
	public static function validate_code() {
		global $wpdb;
		// Check if a user_id (student_id) is assocaited with the ulgm_filter_input( 'term' ) which maybe be a code
		$user_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT student_id
											  	FROM {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . '
												WHERE code = %s',
				ulgm_filter_input( 'term' )
			)
		);

		if ( absint( $user_id ) ) {
			// Valid ID returned based on code
			$user = get_user_by( 'id', absint( $user_id ) );

			if ( $user->exists() ) {
				//
				$suggestions[ $user->ID ] = array(
					'user_id'    => $user->ID,
					'label'      => $user->last_name . ', ' . $user->first_name . ' [' . $user->user_email . ']',
					'first_name' => $user->first_name,
					'last_name'  => $user->last_name,
					'user_email' => $user->user_email,
				);

				$response = wp_json_encode( $suggestions );
				echo $response;
				die();
			}
		}
	}

	/**
	 * @param $user_id
	 * @param $lesson_id
	 * @param $course_id
	 * @param $type
	 */
	public static function mark_lesson_steps( $user_id, $lesson_id, $course_id, $type ) {

		$topic_list = learndash_get_topic_list( $lesson_id, $course_id );
		$quiz_list  = array();

		if ( ! empty( $topic_list ) ) {
			foreach ( $topic_list as $topic ) {

				if ( 'uncomplete_post' === $type ) {

					learndash_process_mark_incomplete( $user_id, $course_id, $topic->ID );
				}

				if ( 'complete_post' === $type ) {
					learndash_process_mark_complete( $user_id, $topic->ID, false, $course_id );
				}

				$topic_quiz_list = learndash_get_lesson_quiz_list( $topic->ID, $user_id, $course_id );
				if ( $topic_quiz_list ) {
					foreach ( $topic_quiz_list as $ql ) {
						$quiz_list[ $ql['post']->ID ] = 0;
					}
				}
			}
		}

		$lesson_quiz_list = learndash_get_lesson_quiz_list( $lesson_id, $user_id, $course_id );

		if ( $lesson_quiz_list ) {
			foreach ( $lesson_quiz_list as $ql ) {
				$quiz_list[ $ql['post']->ID ] = 0;
			}
		}

		self::mark_quiz( $user_id, $course_id, $quiz_list, $type );
	}

	/**
	 * @param      $user_id
	 * @param null $course_id
	 * @param      $type
	 */
	public static function mark_quiz( $user_id, $course_id = null, $quiz_list = array(), $type = '' ) {

		$quizz_progress = array();

		if ( ! empty( $quiz_list ) ) {

			$usermeta       = get_user_meta( $user_id, '_sfwd-quizzes', true );
			$quizz_progress = empty( $usermeta ) ? array() : $usermeta;

			//          foreach ( $quizz_progress as $quiz ) {
			//              if ( isset( $quiz['course'] ) && isset( $quiz['course'] ) ) {
			//                  if ( $course_id == $quiz['course'] ) {
			//                      if ( in_array() ) {
			//
			//                      }
			//                  }
			//              }
			//          }
		}

		foreach ( $quiz_list as $quiz_id => $quiz ) {

			if ( 'uncomplete_post' === $type ) {

				if ( ! learndash_is_quiz_complete( $user_id, $quiz_id, $course_id ) ) {
					continue;
				}

				learndash_delete_quiz_progress( (int) $user_id, (int) $quiz_id );

			} else {

				if ( learndash_is_quiz_complete( $user_id, $quiz_id, $course_id ) ) {
					continue;
				}
				$quiz_meta = get_post_meta( $quiz_id, '_sfwd-quiz', true );

				$quizdata = array(
					'quiz'             => $quiz_id,
					'score'            => 100,
					'count'            => 0,
					'pass'             => true,
					'rank'             => '-',
					'time'             => time(),
					'pro_quizid'       => $quiz_meta['sfwd-quiz_quiz_pro'],
					'course'           => $course_id,
					'points'           => 0,
					'total_points'     => 0,
					'percentage'       => 100,
					'timespent'        => 0,
					'has_graded'       => false,
					'statistic_ref_id' => 0,
					'm_edit_by'        => 9999999,  // Manual Edit By ID.
					'm_edit_time'      => time(),          // Manual Edit timestamp.
				);

				$quizdata['pass'] = true;
				$quizdata_pass    = true;

				$quizz_progress[] = $quizdata;

				// Then we add the quiz entry to the activity database.
				learndash_update_user_activity(
					array(
						'course_id'          => $course_id,
						'user_id'            => $user_id,
						'post_id'            => $quiz_id,
						'activity_type'      => 'quiz',
						'activity_action'    => 'insert',
						'activity_status'    => $quizdata_pass,
						'activity_started'   => $quizdata['time'],
						'activity_completed' => $quizdata['time'],
						'activity_meta'      => $quizdata,
					)
				);
			}
		}
		if ( ! empty( $quizz_progress ) && 'complete_post' === $type ) {
			update_user_meta( $user_id, '_sfwd-quizzes', $quizz_progress );
		}
	}
}
