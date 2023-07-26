<?php


namespace uncanny_learndash_groups;

/**
 * Class InitializePlugin
 * @package uncanny_learndash_groups
 * @deprecated 4.0
 */
class InitializePlugin {

	/**
	 * The plugin version number
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	public $plugin_version;

	/**
	 * The instance of the class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Load_Groups
	 */
	private static $instance = null;

	/**
	 * Creates singleton instance of class
	 *
	 * @return InitializePlugin $instance The InitializePlugin Class
	 * @since 1.0.0
	 *
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * class constructor
	 */
	private function __construct() {
		$this->plugin_version = UNCANNY_GROUPS_VERSION;
	}
}


/**
 * @param null $group_title
 * @param int $seats
 * @param array $course_ids
 * @param null $first_name
 * @param null $last_name
 * @param null $email
 */
function uo_add_new_group_details( $group_title = null, $seats = 0, $course_ids = array(), $first_name = null, $last_name = null, $email = null ) {
	//Validation!
	if ( is_user_logged_in() && ! is_null( $group_title ) && 0 !== $seats && is_array( $course_ids ) && ! empty( $course_ids ) && ! is_null( $first_name ) && ! is_null( $last_name ) && ! is_null( $email ) && is_email( $email ) ) {
		$args = [
			'ulgm_group_leader_first_name' => $first_name,
			'ulgm_group_leader_last_name'  => $last_name,
			'ulgm_group_leader_email'      => $email,
			'ulgm_group_name'              => $group_title,
			'ulgm_group_total_seats'       => $seats,
			'ulgm_group_courses'           => $course_ids,
		];
		ProcessManualGroup::process( $args );
	}
}
