<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
* Cron class
*/
class Learndash_Groups_WooCommerce_Cron {
	
	/**
	 * Hook functions
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'add_cron_schedule' ) );
		add_action( 'admin_init', array( $this, 'register_cron' ) );
		register_deactivation_hook( LEARNDASH_GROUPS_WOOCOMMERCE_FILE, array( $this, 'deregister_hook' ) );
	}

	/**
	 * Add cron schedule
	 * 
	 * @param array $schedules Cron schedules
	 */
	public function add_cron_schedule( $schedules ) {
		$schedules['per_minute'] = array(
			'interval' => MINUTE_IN_SECONDS,
			'display'  => __( 'Once per minute', 'uncanny-learndash-groups' ),
		);

		return $schedules;
	}

	/**
	 * Register cron hook
	 */
	public function register_cron() {
		$options = get_option( 'learndash_groups_woocommerce_settings', array() );

		if ( isset( $options['version'] ) && version_compare( $options['version'], LEARNDASH_GROUPS_WOOCOMMERCE_VERSION, '>=' ) ) {
			return;
		}

		if ( ! wp_next_scheduled( 'learndash_groups_woocommerce_cron' ) ) {
			wp_schedule_event( time(), 'per_minute', 'learndash_groups_woocommerce_cron' );
		}

		$options['version'] = LEARNDASH_GROUPS_WOOCOMMERCE_VERSION;
		update_option( 'learndash_groups_woocommerce_settings', $options );
	}

	public function deregister_hook() {
		wp_clear_scheduled_hook( 'learndash_groups_woocommerce_cron' );
	}
}

new Learndash_Groups_WooCommerce_Cron();