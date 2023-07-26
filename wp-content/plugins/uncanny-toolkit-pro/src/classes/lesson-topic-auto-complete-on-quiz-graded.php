<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class LessonTopicAutoCompleteOnQuizGraded
 * @package uncanny_pro_toolkit
 */
class LessonTopicAutoCompleteOnQuizGraded extends toolkit\Config implements toolkit\RequiredFunctions {

	public static $auto_completed_post_types = array( 'sfwd-lessons', 'sfwd-topic' );

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

			/* ADD FILTERS ACTIONS FUNCTION */

			add_action( 'learndash_essay_response_data_updated', array(
				__CLASS__,
				'complete_associated_topic_lesson'
			), 99, 4 );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Autocomplete Lessons & Topics When Quiz Is Graded', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/autocomplete-lessons-topics-when-quiz-is-graded/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Automatically mark LearnDash lessons and topics as completed when the associated quiz is manually graded.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-percent"></i><span class="uo_pro_text">PRO</span>';

		$category = 'learndash';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => null, //self::get_class_settings( $class_title ),
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
	 * @return string
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
				'type'       => 'radio',
				'label'      => 'Use Global Settings',
				'radio_name' => 'uo_global_auto_complete_when_graded',
				'radios'     => array(
					array(
						'value' => 'auto_complete_all',
						'text'  => 'Enable auto-completion for all lessons and topics **<br>',
					),
					array(
						'value' => 'auto_complete_only_lesson_topics_set',
						'text'  => 'Disable autocompletion for all lessons and topics **',
					),
				),
			),
			array(
				'type'       => 'html',
				'class'      => 'uo-additional-information',
				'inner_html' => __( '<div>** This global setting can be overridden for individual lessons and topics in the Edit page of the associated lesson or topic.</div>', 'uncanny-pro-toolkit' ),
			),

		);

		// Build html
		$html = toolkit\Config::settings_output( array(
			'class'   => __CLASS__,
			'title'   => $class_title,
			'options' => $options,
		) );

		return $html;
	}

	/**
	 * @param $quiz_id
	 * @param $question_id
	 * @param $essay
	 * @param $submitted_essay
	 */
	public static function complete_associated_topic_lesson( $quiz_id, $question_id, $essay, $submitted_essay ) {

		if ( empty( $submitted_essay ) ) {
			return;
		}

		$essay_id  = $submitted_essay['post_id'];
		$status    = $submitted_essay['status'];
		$user_id   = $essay->post_author;
		$lesson_id = get_post_meta( $essay_id, 'lesson_id', true );
		$topic_id  = get_post_meta( $essay_id, 'topic_id', true );
		$course_id = get_post_meta( $essay_id, 'course_id', true );

		if ( (string) 'graded' === (string) $status ) {
			if ( ! empty( $topic_id ) ) {
				$topic = get_post( $topic_id );
				//$lesson_id = learndash_get_setting( $topic, 'lesson' );
				$lesson = get_post( $lesson_id );

				// Are all quizzes complete in the topic
				$all_topic_quizzes = self::check_quiz_list( $topic->ID, $user_id );

				if ( empty( $all_topic_quizzes ) || $all_topic_quizzes ) {
					//Marking Topic Complete
					if ( ! learndash_is_lesson_complete( $user_id, $topic->ID ) ) {
						if ( ! empty( $course_id ) ) {
							learndash_process_mark_complete( $user_id, $topic->ID, false, $course_id );
						} else {
							learndash_process_mark_complete( $user_id, $topic->ID );
						}
					}
				}

				// Are all quizzes complete in the topic
				$all_lesson_quizzes = self::check_quiz_list( $lesson->ID, $user_id );
				// Check and mark lesson complete in all topics are completed in the lesson
				if ( empty( $all_lesson_quizzes ) || $all_lesson_quizzes ) {
					//Marking Topic Complete
					if ( ! learndash_is_lesson_complete( $user_id, $lesson->ID ) ) {
						learndash_lesson_topics_completed( $lesson->ID, true );
					}
				}
			}

			if ( ! empty( $lesson_id ) ) {
				$all_quizzes = self::check_quiz_list( $lesson_id, $user_id );

				if ( ( empty( $all_quizzes ) || $all_quizzes ) && learndash_lesson_topics_completed( $lesson_id, false ) ) {
					//Marking Lesson Complete
					if ( ! learndash_is_lesson_complete( $user_id, $lesson_id ) ) {
						if ( ! empty( $course_id ) ) {
							learndash_process_mark_complete( $user_id, $lesson_id, false, $course_id );
						} else {
							learndash_process_mark_complete( $user_id, $lesson_id );
						}
					}
				}
			}
		}
	}

	/**
	 * @param $id
	 *
	 * @param $user_id
	 *
	 * @return bool
	 */
	public static function check_quiz_list( $id, $user_id ) {

		//$user = get_user_by( 'ID', $user_id );

		$quiz_list = learndash_get_lesson_quiz_list( $id );
		if ( '' === $quiz_list ) {
			$quiz_list = array();
		}

		if ( is_array( $quiz_list ) && ! empty( $quiz_list ) ) {

			// Loop all quizzes in lessons
			foreach ( $quiz_list as $quiz ) {

				$quiz_id = $quiz['post']->ID;
				if ( ! learndash_is_quiz_complete( $user_id, $quiz_id ) ) {
					return false;
				};
			}
		}

		return true;
	}
}