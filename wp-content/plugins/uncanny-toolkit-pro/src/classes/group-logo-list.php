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
class GroupLogoList extends toolkit\Config implements toolkit\RequiredFunctions {

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

			add_filter( 'learndash_post_args_groups', array( __CLASS__, 'add_thumbnail_support_to_groups' ) );
			add_shortcode( 'uo_group_logo', array( __CLASS__, 'uo_group_logo' ) );

			// Add group list shortcode which takes attribute separator(default = ' ,')
			add_shortcode( 'uo_group_list', array( __CLASS__, 'uo_group_list' ) );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Group Logo/List', 'uncanny-pro-toolkit' );

		$kb_link = 'https://www.uncannyowl.com/knowledge-base/ld-group-logo-list/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Add group-specific logos to any page, including registration pages. A shortcode to list a user’s LearnDash Groups is also available.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-file-image-o "></i><span class="uo_pro_text">PRO</span>';

		$category = 'learndash';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => false,
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
	 * Adds thumbnail support to groups and updates the text
	 *
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function add_thumbnail_support_to_groups( $arguments ) {

		array_push( $arguments['supports'], 'thumbnail' );
		$arguments['labels']['featured_image']        = 'Branding Logo';
		$arguments['labels']['set_featured_image']    = 'Set Logo';
		$arguments['labels']['remove_featured_image'] = 'Remove Logo';
		$arguments['labels']['use_featured_image']    = 'Use Logo';

		$arguments['supports'] = array_unique( $arguments['supports'] );

		return $arguments;

	}

	/**
	 * Outputs a images asscoiated with a user's groups
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	public static function uo_group_logo( $attributes ) {

		$attributes = shortcode_atts( array(
			'size' => 'full'
		), $attributes );

		$user_id   = get_current_user_id();
		$group_ids = learndash_get_users_group_ids( $user_id );

		$logo = '';

		if ( ! empty( $group_ids ) ) {
			foreach ( $group_ids as $group_id ) {
				//$logo .=  '<img class="uo_white_label_logo" src="' . get_the_post_thumbnail( $group_id, $attributes['size'] ) .  '" \>';
				$logo .= get_the_post_thumbnail( $group_id, $attributes['size'] );
			}
		}

		// Add custom class
		$logo = str_replace( 'class="', 'class="uo_white_label_logo ', $logo );

		return $logo;

	}

	/**
	 * Return a list of LearnDash group names the use is a part of
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	public static function uo_group_list( $attributes ) {

		$attributes = shortcode_atts( array(
			'separator' => ', '
		), $attributes );

		$user_id   = get_current_user_id();
		$group_ids = learndash_get_users_group_ids( $user_id );

		$logo = array();

		if ( ! empty( $group_ids ) ) {
			foreach ( $group_ids as $group_id ) {
				$post_title = get_the_title( $group_id );
				if ( $post_title ) {
					$logo[] = $post_title;
				}
			}
		}

		return implode( $attributes['separator'], $logo );

	}
}