<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ReportingCapabilities
 * @package uncanny_learndash_groups
 */
class Capabilities {

	/**
	 * The array of roles and there capabilites
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Load_Groups
	 */
	private $set_role_capabilities = null;

	/**
	 * Class constructor
	 *
	 * @param array $set_role_capabilities Array of user roles(as keys) and this array of capabilities (as value)
	 *
	 * @since 1.0
	 *
	 */
	public function __construct( $set_role_capabilities ) {

		/**
		 * Example capabilities array
		 *
		 * $set_role_capabilities = array(
		 *    'subscriber'  => array('custom_cap_b'),
		 *    'administrator' => array('custom_cap_b','custom_cap_a')
		 * );
		 *
		 */
		$this->set_role_capabilities = $set_role_capabilities;

		// Add custom capabilities to roles
		add_action( 'admin_init', array( $this, 'add_capabilities' ) );

	}

	/*
	 * Add capabilities to roles
	 *
	 * @since 1.0
	 */
	public function add_capabilities() {

		if ( $this->set_role_capabilities ) {

			// Loop through all roles that need the reporting capability added
			foreach ( $this->set_role_capabilities as $role => $capabilities ) {

				// Get the role class instance
				$group_leader_role = get_role( $role );

				if ( $group_leader_role instanceof WP_Role ) {
					// Add the reporting capability to the role
					foreach ( $capabilities as $capability ) {
						$group_leader_role->add_cap( $capability );
					}
				}
			}
		}

	}

	/*
	 * Remove capabilities from roles
	 *
	 * @since 1.0
	 */
	public function remove_capabilities() {
		if ( $this->set_role_capabilities ) {
			// Loop through all roles that need the reporting capability added
			foreach ( $this->set_role_capabilities as $role => $capabilities ) {
				// Get the role class instance
				$group_leader_role = get_role( $role );
				// Add the reporting capability to the role
				foreach ( $capabilities as $capability ) {
					$group_leader_role->remove_cap( $capability );
				}
			}
		}
	}
}
