<?php


namespace uncanny_learndash_groups;

/**
 * Class Uncanny_Groups_Helpers
 * @package uncanny_learndash_groups
 */
class Uncanny_Groups_Helpers {
	/**
	 * @var
	 */
	public static $instance;

	/**
	 * @var DB_Handler
	 */
	public $db;
	/**
	 * @var Group_Management_DB_Handler
	 */
	public $group_management;

	/**
	 * @return Uncanny_Groups_Helpers
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Uncanny_Groups_Helpers constructor.
	 */
	public function __construct() {
	}

	/**
	 *
	 */
	public function load() {
	}
}
