<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class DataShortcodes
 * @package uncanny_learndash_groups
 */
class DataShortcodes {

	/**
	 * class constructor
	 */
	public function __construct() {

		add_shortcode( 'uo_group_seats_total', array($this, 'uo_group_seats_total' ) );
		add_shortcode( 'uo_group_seats_remaining', array($this, 'uo_group_seats_remaining' ) );

	}

	/**
	 * This shortcode displays the amount of total seats for a group
	 *
	 * @since 1.0
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	public function uo_group_seats_total( $attributes ) {
		$arguments = shortcode_atts( array(
			'id' => 0
		), $attributes );

		if(!$arguments['id']){
			return __('Please add an id attribute to the shortcode', 'uncanny-learndash-groups');
		}

		return ulgm()->group_management->seat->total_seats( (int)$arguments['id'] );

	}

	/**
	 * This shortcode displays the amount of remaining seats for a group
	 *
	 * @since 1.0
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	public function uo_group_seats_remaining( $attributes ) {
		$arguments = shortcode_atts( array(
			'id' => 0
		), $attributes );

		if(!$arguments['id']){
			return __('Please add an id attribute to the shortcode', 'uncanny-learndash-groups');
		}

		return ulgm()->group_management->seat->remaining_seats( (int)$arguments['id'] );

	}


}
