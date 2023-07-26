<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class CourseAccessExpiry extends toolkit\Config implements toolkit\RequiredFunctions {

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

			//Expiration date shortcode
			add_shortcode( 'uo_expiration_in', array( __CLASS__, 'expiration_in' ) );

		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = __( 'Days Until Course Expiry', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/days-until-course-expiry/';

		/* Sample Simple Description with shortcode */
		$class_description = __( 'Use this shortcode to display the number of days until the learner\'s access expires for the current course. This is a useful shortcode to include on course pages.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-hourglass-end"></i><span class="uo_pro_text">PRO</span>';

		$category          = 'learndash';
		$type = 'pro';

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
	 * @return boolean || string Return either true or name of function or plugin
	 *
	 */
	public static function dependants_exist() {

		/* Checks for LearnDash */
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		return true;

	}

	/*
	 * Shortcode [expiration_in]
	 *
	 */
	public static function expiration_in( $attributes ) {

		$a = shortcode_atts( array(
			'pre-text'  => '',
			'course-id' => ''
		), $attributes );

		// Set default text
		$text = (object) [
			'singular' => __( 'Course Access Expires in 1 Day', 'uncanny-pro-toolkit' ),
			'plural'   => __( 'Course Access Expires in %s Days', 'uncanny-pro-toolkit' ),
		];

		// Get fields
		$text_singular_field = self::get_settings_value( 'days_expiry_text_singular', __CLASS__ );
		$text_plural_field   = self::get_settings_value( 'days_expiry_text_plural', __CLASS__ );

		// Overwrite the default values with the one in the fields, but only if those are defined
		$text->singular = ! empty( $text_singular_field ) ? $text_singular_field : $text->singular;
		$text->plural   = ! empty( $text_plural_field )   ? $text_plural_field   : $text->plural;

		// Replace the %days% argument with an %s
		// We need to do this so we can use sprintf to insert the value
		$text->plural   = str_replace( '%days%', '%s', $text->plural );

		// Backward compability for the pre-text attribute
		// Check if the user defined the pre-text attribute, but didn't set a value in the fields
		if ( ! empty( $a[ 'pre-text' ] ) && ( empty( $text_singular_field ) && empty( $text_plural_field ) ) ){
			// In that case add the %days% argument
			// We won't worry about the order since this solution was already working for the user
			$text->singular = $a[ 'pre-text' ] . ' ' . __( '1 Day', 'uncanny-pro-toolkit' );
			$text->plural   = $a[ 'pre-text' ] . ' %s ' . __( 'Days', 'uncanny-pro-toolkit' );
		}

		$current_user_id = get_current_user_id();

		$course_id = $a['course-id'];

		if ( '' === $course_id ) {
			global $post;
			$post_object = $post;
			// Get course id
			if ( $post->post_type == 'sfwd-courses' ) {
				$course_id = $post->ID;
			}
			// Get course id from related lesson, topic, or quiz
			if ( $post_object->post_type == 'sfwd-lessons' || $post_object->post_type == 'sfwd-topic' || $post_object->post_type == 'sfwd-quiz' ) {
				$course_id = learndash_get_course_id($post_object->ID);
			}
		} else {
			$post_object = get_post( (int) $course_id );
			if ( $post_object ) {
				$course_id = $post_object->ID;
			}
		}

		// if course id not found
		if ( '' === $course_id ) {
			return '';
		}

		// Get expiration date
		$course_access_up_to = ld_course_access_expires_on( $course_id, $current_user_id );

		if ( 0 !== $course_access_up_to && sfwd_lms_has_access( $course_id, $current_user_id ) ) {

			$current_time = current_time('timestamp');

			$amount_seconds_between = $course_access_up_to - $current_time;

			$amount_days_between = floor( $amount_seconds_between / 60 / 60 / 24 );

			if ( 0 > $amount_days_between ) {
				return '';
			}

			if ( 1 == $amount_days_between ) {
				$text = $text->singular;
				// $text = apply_filters( '', $text,$a['pre-text'], $amount_days_between);
				return $text;
			} else {
				$text = sprintf( $text->plural, $amount_days_between );
				// $text = apply_filters( '', $text,$a['pre-text'], $amount_days_between);
				return $text;
			}


		} else {
			return '';
		}

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
		// Create options
		$options = array(
			array(
				'type'        => 'text',
				'label'       => __( 'Text displayed when one day remaining', 'uncanny-pro-toolkit' ),
				'placeholder' => __( 'Course Access Expires in 1 Day', 'uncanny-pro-toolkit' ),
				'option_name' => 'days_expiry_text_singular',
			),
			array(
				'type'        => 'text',
				'label'       => __( 'Text displayed when multiple days remaining', 'uncanny-pro-toolkit' ),
				'placeholder' => sprintf( __( 'Course Access Expires in %s Days', 'uncanny-pro-toolkit' ), '%days%' ),
				'description' => sprintf( __( 'You can use the %s argument in your string to get the number of days', 'uncanny-pro-toolkit' ), '<strong>%days%</strong>' ),
				'option_name' => 'days_expiry_text_plural',
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
}