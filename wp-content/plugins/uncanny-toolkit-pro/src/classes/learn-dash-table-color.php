<?php
/**
 * Class LearnDashTableColor
 *
 * This class extends WP Core Color Picker functionality
 * to set table colors for LearnDash Tables.
 *
 *
 * @package     uncanny_learndash_toolkit
 * @subpackage  uncanny_pro_toolkit\LearnDashTableColor
 * @since       1.0.1
 * @since       1.1.0 Separated Background & Text Color so either one of them can be changed at a time
 *
 */

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class LearnDashTableColor extends toolkit\Config implements toolkit\RequiredFunctions {
	/**
	 * Class constructor
	 *
	 * @since       1.0.1
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 *
	 * @since       1.0.1
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {

			/* ADD FILTERS ACTIONS FUNCTION */
			add_action( 'wp_head', array( __CLASS__, 'change_learndash_colors' ) );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @since       1.0.1
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Lesson/Topic/Quiz Table Colors (Legacy)', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/learndash-table-colors/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Change the background and header text color of LearnDash tables. This module modifies the course, lesson, topic, quiz, and Pro Dashboard tables. (Legacy LearnDash theme only).', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-eyedropper"></i><span class="uo_pro_text">PRO</span>';
		$category   = 'learndash';
		$type       = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => self::get_class_settings( $class_title ),
			'icon'             => $class_icon,
		);

	}

	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * @since           1.0.1
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
	 *
	 * @since       1.0.1
	 *
	 * @param $class_title
	 *
	 * @return array
	 */
	public static function get_class_settings( $class_title ) {

		//Create options
		$options = array(

			array(
				'type'        => 'text',
				'label'       => 'Background color of LearnDash table headings',
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_learndash_heading_color',
			),
			array(
				'type'        => 'text',
				'label'       => "Text color of LearnDash table headings",
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_learndash_text_color',
			),
		);

		//Build html
		$html = self::settings_output( array(
			'class'   => __CLASS__,
			'title'   => $class_title,
			'options' => $options,
		) );

		return $html;
	}

	/**
	 *
	 * @since       1.0.1
	 * @since       1.1.0 Separated Background & Text Color condition
	 */
	public static function change_learndash_colors() {
		$background = self::get_settings_value( 'uo_learndash_heading_color', __CLASS__ );
		$color      = self::get_settings_value( 'uo_learndash_text_color', __CLASS__ );
		if ( ! empty( $background ) ) {
			echo "<style type='text/css'>
					body #learndash_lessons #lesson_heading, 
					body #learndash_profile .learndash_profile_heading, 
					body #learndash_quizzes #quiz_heading, 
					body #learndash_lesson_topics_list div > strong {
					background-color: {$background}!important;
					}
					</style>";
		}
		if ( ! empty( $color ) ) {
			echo "<style type='text/css'>
					body #learndash_lessons #lesson_heading, 
					body #learndash_profile .learndash_profile_heading, 
					body #learndash_quizzes #quiz_heading, 
					body #learndash_lesson_topics_list div > strong {
					color: {$color}!important;
					}
					</style>";
		}
	}
}