<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class LearnDashReset
 * @package uncanny_pro_toolkit
 */
class LearnDashReset extends toolkit\Config implements toolkit\RequiredFunctions {

	static $topic_type = 'sfwd-topic';
	private static $quiz_list;
	private static $assignment_list;
	static $version = '3.0';

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
			add_shortcode( 'uo_reset_course_progress', array( __CLASS__, 'learndash_reset' ) );
			add_action( 'wp_ajax_learndashreset_courses_load', array( __CLASS__, 'ajax_courses_load' ) );
		}

	}

	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * return boolean || string Return either true or name of function or plugin
	 */
	public static function dependants_exist() {
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		return true;
	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title       = esc_html__( 'Reset Progress Button', 'uncanny-pro-toolkit' );
		$kb_link           = 'https://www.uncannyowl.com/knowledge-base/learndash-reset/';
		$class_description = esc_html__( 'Inserts a button that allows learners to reset the course.', 'uncanny-pro-toolkit' );
		$class_icon        = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-book"></i><span class="uo_pro_text">PRO</span>';
		$category          = 'learndash';
		$type              = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link,
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => self::get_class_settings( $class_title ),
			'icon'             => $class_icon,
		);

	}

	/**
	 * HTML for modal to create settings
	 *
	 * @static
	 *
	 * @param $class_title
	 *
	 * @return string
	 */
	public static function get_class_settings( $class_title ) {

		// Create options
		$options = array(

			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Reset Button Text', 'uncanny-pro-toolkit' ),
				'option_name' => 'learn-dash-reset-button-text',
			),
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Show Name of Course', 'uncanny-pro-toolkit' ),
				'option_name' => 'learn-dash-reset-show-name',
			)

		);

		// Build html
		$html = self::settings_output(
			array(
				'class'   => __CLASS__,
				'title'   => $class_title,
				'options' => $options,
			) );

		return $html;
	}


	/**
	 *Adding [uo_reset_course_progress] shortcode functionality which can be used anywhere on the website to reset specific LearnDash course.
	 *
	 * @static
	 * @return string
	 */
	public static function learndash_reset( $atts ) {
		global $post;

		$atts = shortcode_atts( array(
			'course_id'      => '',
			'reset_tincanny' => 'no',
		), $atts, 'uo_reset_course_progress' );

		if ( is_user_logged_in() ) {

			$user = wp_get_current_user();
			if ( empty( $atts['course_id'] ) && isset( $post->ID ) && ( $post->post_type === 'sfwd-courses' ) ) {
				$atts['course_id'] = $post->ID;
			} elseif ( empty( $atts['course_id'] ) && isset( $post->ID ) && ( $post->post_type !== 'sfwd-courses' ) ) {
				return '';
			}

			if ( empty( $atts['reset_tincanny'] ) ) {
				$atts['reset_tincanny'] = 'no';
			}

			$args = array(
				'course_id' => $atts['course_id'],
				'user_id'   => $user->ID,
				'array'     => true
			);

			if ( isset( $_POST['learndash_reset_course_nonce'] ) && wp_verify_nonce( $_POST['learndash_reset_course_nonce'], 'learndash_reset_course' ) ) {
				$reset_tinCanny = isset( $_POST['ld_reset_tincanny_data'] ) ? $_POST['ld_reset_tincanny_data'] : 'no';
				self::reset_course_progress( $user->ID, intval( $_POST['ld_reset_course_id'] ), $reset_tinCanny );
			}

			$course_progress = learndash_course_progress( $args );

			// User has not hit a LD course yet
			if ( is_array( $course_progress ) && 0 === $course_progress['percentage'] ) {

				return '';
			}


			$course_post_object = get_post( $atts['course_id'] );

			// Make sure the post exists and that the user hit a page that was a post
			// if $course_post_object returns '' then get post will return current pages post object
			// so we need to make sure first that the $course_post_object is returning something and
			// that the something is a valid post
			if ( null !== $course_post_object ) {

				$post_type       = $course_post_object->post_type; // getting post_type of course.
				$label           = get_post_type_object( $post_type ); // getting Labels of the course.
				$title           = $course_post_object->post_title;
				$reset_link_text = 'RESET';

				// Reset Link Text
				$link_text = self::get_settings_value( 'learn-dash-reset-button-text', __CLASS__ );
				$show_name = self::get_settings_value( 'learn-dash-reset-show-name', __CLASS__ );

				if ( strlen( trim( $link_text ) ) ) {
					$reset_link_text = $link_text;
				}

				$reset_link_text = apply_filters( 'learndash_reset_link_text', $reset_link_text );

				$form_css_classes = apply_filters( 'learndash_reset_form_css_classes', 'learndash-reset-form' );
				$css_classes      = apply_filters( 'learndash_reset_css_classes', 'learndash-reset-button' );
				$nonce_field      = wp_nonce_field( 'learndash_reset_course', 'learndash_reset_course_nonce', true, false );

				ob_start();


				printf(
					'<form id="learndash-reset-course-%s" method="POST" class="%s" name="learndash-reset-course">%s '
					. '<input type="hidden" value="%s" name="ld_reset_course_id">'
					. '<input type="hidden" value="%s" name="ld_reset_tincanny_data">'
					. '<input type="submit" value="%s" class="%s">'
					. '</form>',
					$course_post_object->ID,
					esc_attr( $form_css_classes ),
					$nonce_field,
					$course_post_object->ID,
					$atts['reset_tincanny'],
					esc_attr( $reset_link_text ),
					esc_attr( $css_classes )
				);

				if ( $show_name === 'on' ) {
					printf(
						'<div class="reset-item-name">%s</div>',
						$title
					);
				}
				$reset_link = ob_get_contents();
				ob_end_clean();

				return $reset_link;
			}

		}

		return '';
	}

	/**
	 *
	 * Function called from reset_course_progress()
	 *
	 * @param $user_id
	 * @param $course_id
	 * @param $reset_tincanny_data
	 */
	public static function reset_course_progress( $user_id, $course_id, $reset_tincanny_data = 'no' ) {

		$course_id = intval( $course_id );
		if ( '-1' !== $course_id ) {
			self::delete_user_activity( $user_id, $course_id );
			self::delete_course_progress( $user_id, $course_id );
			self::reset_quiz_progress( $user_id, $course_id );
			self::delete_assignments();
			if ( 'yes' === strtolower( $reset_tincanny_data ) && class_exists( '\UCTINCAN\Database\Admin' ) ) {
				self::reset_tincanny_data( $user_id, $course_id );
			}
		}
	}

	/**
	 *
	 * Delete course progress from Usermeta Table
	 *
	 * @param $user_id
	 * @param $course_id
	 */
	private static function delete_course_progress( $user_id, $course_id ) {
		$usermeta = get_user_meta( $user_id, '_sfwd-course_progress', true );
		unset( $usermeta[ $course_id ] );
		update_user_meta( $user_id, '_sfwd-course_progress', $usermeta );
	}

	/**
	 *
	 * Delete course related meta keys from user meta table.
	 * Delete all activity related to a course from LD tables
	 *
	 * @param $user_id
	 * @param $course_id
	 */
	private static function delete_user_activity( $user_id, $course_id ) {
		global $wpdb;
		delete_user_meta( $user_id, 'completed_' . $course_id );
		delete_user_meta( $user_id, 'course_' . $course_id . '_access_from' );
		delete_user_meta( $user_id, 'course_completed_' . $course_id );
		delete_user_meta( $user_id, 'learndash_course_expired_' . $course_id );

		$activity_ids = $wpdb->get_results( 'SELECT activity_id FROM ' . $wpdb->prefix . 'learndash_user_activity WHERE course_id = ' . $course_id . ' AND user_id = ' . $user_id );

		if ( $activity_ids ) {
			foreach ( $activity_ids as $activity_id ) {
				$wpdb->query( "DELETE FROM  {$wpdb->prefix}learndash_user_activity_meta WHERE activity_id = {$activity_id->activity_id}" );
				$wpdb->query( "DELETE FROM {$wpdb->prefix}learndash_user_activity WHERE activity_id = {$activity_id->activity_id}" );
			}
		}
	}

	/**
	 * Delete assignments of course, related to lessons / topics
	 */
	private static function delete_assignments() {
		global $wpdb;
		$assignments = self::$assignment_list;

		if ( $assignments ) {
			foreach ( $assignments as $assignment ) {
				$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE ID = {$assignment}" );
				$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE post_id = {$assignment}" );
			}
		}
	}

	/**
	 *
	 * Get lesson quiz list
	 * Get Lesson assignment list
	 * Delete quiz progress, related to course, quiz etc
	 *
	 * @param $user_id
	 * @param $course_id
	 */
	private static function reset_quiz_progress( $user_id, $course_id ) {
		$lessons = learndash_get_lesson_list( $course_id, array( 'num' => 0 ) );
		foreach ( $lessons as $lesson ) {
			self::get_topics_quiz( $user_id, $lesson->ID, $course_id );
			$lesson_quiz_list = learndash_get_lesson_quiz_list( $lesson->ID, $user_id, $course_id );

			if ( $lesson_quiz_list ) {
				foreach ( $lesson_quiz_list as $ql ) {
					self::$quiz_list[ $ql['post']->ID ] = 0;
				}
			}

			//grabbing lesson related assignments
			$assignments = get_posts( [
				'post_type'      => 'sfwd-assignment',
				'posts_per_page' => 999,
				'meta_query'     => [
					'relation' => 'AND',
					[
						'key'     => 'lesson_id',
						'value'   => $lesson->ID,
						'compare' => '=',
					],
					[
						'key'     => 'course_id',
						'value'   => $course_id,
						'compare' => '=',
					],
					[
						'key'     => 'user_id',
						'value'   => $user_id,
						'compare' => '=',
					]
				]
			] );

			if ( $assignments ) {
				foreach ( $assignments as $assignment ) {
					self::$assignment_list[] = $assignment->ID;
				}
			}
		}

		self::delete_quiz_progress( $user_id, $course_id );
	}

	/**
	 *
	 * Get topic quiz + assignment list
	 *
	 * @param $user_id
	 * @param $lesson_id
	 * @param $course_id
	 */
	private static function get_topics_quiz( $user_id, $lesson_id, $course_id ) {
		$topic_list = learndash_get_topic_list( $lesson_id, $course_id );
		if ( $topic_list ) {
			foreach ( $topic_list as $topic ) {
				$topic_quiz_list = learndash_get_lesson_quiz_list( $topic->ID, $user_id, $course_id );
				if ( $topic_quiz_list ) {
					foreach ( $topic_quiz_list as $ql ) {
						self::$quiz_list[ $ql['post']->ID ] = 0;
					}
				}

				$assignments = get_posts( [
					'post_type'      => 'sfwd-assignment',
					'posts_per_page' => 999,
					'meta_query'     => [
						'relation' => 'AND',
						[
							'key'     => 'lesson_id',
							'value'   => $topic->ID,
							'compare' => '=',
						],
						[
							'key'     => 'course_id',
							'value'   => $course_id,
							'compare' => '=',
						],
						[
							'key'     => 'user_id',
							'value'   => $user_id,
							'compare' => '=',
						]
					]
				] );

				if ( $assignments ) {
					foreach ( $assignments as $assignment ) {
						self::$assignment_list[] = $assignment->ID;
					}
				}
			}
		}
	}


	/**
	 *
	 * Actually deleting quiz data from user meta and pro quiz activity table
	 *
	 * @param      $user_id
	 * @param null $course_id
	 */
	private static function delete_quiz_progress( $user_id, $course_id = null ) {
		$quizzes = learndash_get_course_quiz_list( $course_id, $user_id );
		if ( $quizzes ) {
			foreach ( $quizzes as $quiz ) {
				self::$quiz_list[ $quiz['post']->ID ] = 0;
			}
		}
		global $wpdb;

		$quizz_progress = [];
		if ( ! empty( self::$quiz_list ) ) {
			$usermeta       = get_user_meta( $user_id, '_sfwd-quizzes', true );
			$quizz_progress = empty( $usermeta ) ? array() : $usermeta;
			foreach ( $quizz_progress as $k => $p ) {
				if ( key_exists( $p['quiz'], self::$quiz_list ) ) {
					$statistic_ref_id = $p['statistic_ref_id'];
					unset( $quizz_progress[ $k ] );
					if ( ! empty( $statistic_ref_id ) ) {
						$wpdb->query( "DELETE FROM {$wpdb->prefix}wp_pro_quiz_statistic WHERE statistic_ref_id = {$statistic_ref_id}" );
						$wpdb->query( "DELETE FROM {$wpdb->prefix}wp_pro_quiz_statistic_ref WHERE statistic_ref_id = {$statistic_ref_id}" );
					}
				}
			}
		}

		update_user_meta( $user_id, '_sfwd-quizzes', $quizz_progress );
	}

	/**
	 * Enqueue all scripts and styles
	 *
	 * @since 3.0
	 */
	public static function ajax_courses_load() {
		check_ajax_referer( 'uncanny-LearnDashReset-nonce', 'security' );
		$args      = [
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => 999,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		];
		$options   = [];
		$options[] = array( 'label' => "Default", 'value' => "" );
		$posts     = get_posts( $args );
		foreach ( $posts as $post ) {
			$options[] = array( 'label' => $post->post_title, 'value' => $post->ID );
		}
		echo json_encode( $options );
		die();
	}

	/**
	 * Delete tin canny data on reset.
	 *
	 * @param $user_id
	 * @param $course_id
	 */
	public static function reset_tincanny_data( $user_id, $course_id ) {
		global $wpdb;
		$table_reporting = \UCTINCAN\Database\Admin::TABLE_REPORTING;
		$table_resume    = \UCTINCAN\Database\Admin::TABLE_RESUME;
		$query           = sprintf( "
			DELETE FROM %s%s
				WHERE `user_id` = %s
				AND `course_id` = %s;
			",
			$wpdb->prefix,
			$table_reporting,
			$user_id,
			$course_id
		);

		$wpdb->query( $query );


		/*$query = sprintf( "
			DELETE FROM %s%s
				WHERE `user_id` = %s;
			",
			$wpdb->prefix,
			$table_resume,
			$user_id
		);

		$wpdb->query( $query );*/

	}
}
