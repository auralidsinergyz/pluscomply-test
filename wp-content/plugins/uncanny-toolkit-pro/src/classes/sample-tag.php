<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class SampleTag
 * @package uncanny_pro_toolkit
 */
class SampleTag extends toolkit\Config implements toolkit\RequiredFunctions {
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
			add_action( 'wp_head', array( __CLASS__, 'add_sample_tag' ) );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Sample Lesson Label (Legacy)', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com';

		/* Sample Simple Description with shortcode */
		$kb_link = 'http://www.uncannyowl.com/knowledge-base/sample-lesson-label/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Add visual label to sample lessons (Legacy LearnDash theme only).', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-tag"></i><span class="uo_pro_text">PRO</span>';

		$category = 'learndash';
		$type     = 'pro';

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
				'label'       => 'Label text',
				'placeholder' => 'Sample',
				'option_name' => 'uo_sample_tag_text',
			),
			array(
				'type'        => 'text',
				'label'       => 'Label background color',
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_sample_tag_background',
			),
			array(
				'type'        => 'text',
				'label'       => "Label text color",
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_sample_tag_text_color',
			),
			array(
				'type'        => 'text',
				'label'       => 'Label border color',
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_sample_tag_border_color',
			),
			array(
				'type'        => 'text',
				'label'       => 'Label text size',
				'placeholder' => '14px',
				'option_name' => 'uo_sample_tag_text_size',
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
	 * @since       1.2.0
	 */
	public static function add_sample_tag() {

		$background = self::get_settings_value( 'uo_sample_tag_background', __CLASS__ );
		$color      = self::get_settings_value( 'uo_sample_tag_text_color', __CLASS__ );
		$border     = self::get_settings_value( 'uo_sample_tag_border_color', __CLASS__ );
		$text_size  = self::get_settings_value( 'uo_sample_tag_text_size', __CLASS__ );
		$text       = self::get_settings_value( 'uo_sample_tag_text', __CLASS__ );

		if ( empty( $text ) ) {
			$text = "Sample";
		}
		if ( empty( $text_size ) ) {
			$text_size = "14px";
		}
		if ( empty( $background ) ) {
			$background = "#dcdcdc";
		}
		if ( empty( $color ) ) {
			$color = "#414141";
		}
		if ( empty( $border ) ) {
			$border = "#dcdcdc";
		}
		echo "<style type='text/css'>
				@media (min-width: 769px) {
					.is_sample h4>a:after {
				    content: '$text';
				    float: right;
				    font-size: $text_size;
				    background-color: $background;
				    border-radius: 4px;
				    color: $color;
				    font-weight: 400;
				    line-height: 1.2;
				    padding: 3px 10px;
				    margin-top:-2px;
				    text-transform: initial;
				    border:1px solid $border;
					}
					.quiz_list .is_sample h4>a:after {
				    content: '';
				    line-height: 0;
				    padding: 0;
				    margin-top:0;
				    border:none;
					}
				}
				@media (max-width: 768px) {
					.is_sample h4>a:before {
				    content: '$text';
				    font-size: $text_size;
				    background-color: $background;
				    border-radius: 4px;
				    color: $color;
				    font-weight: 400;
				    line-height: 1.2;
				    padding: 3px 10px;
				    margin-right: 2px;
				    text-transform: initial;
				    border:1px solid $border;
					}
					.quiz_list .is_sample h4>a:after {
				    content: '';
				    line-height: 0;
				    padding: 0;
				    margin-top:0;
				    border:none;
					}
				}
    
				</style>";
	}
}