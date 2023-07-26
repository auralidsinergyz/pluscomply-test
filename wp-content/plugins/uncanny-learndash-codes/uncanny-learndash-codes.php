<?php
/**
 * Plugin Name: Uncanny LearnDash Codes
 * Description: Generate Codes that can be used to enroll a user in to a course or group
 * Version: 3.0.3
 * Author: Uncanny Owl
 * Author URI: www.uncannyowl.com
 * Plugin URI: www.uncannyowl.com/downloads/uncanny-learndash-codes/
 * Text Domain: uncanny-learndash-codes
 * Domain Path: /languages
 */

define( 'UNCANNY_LEARNDASH_CODES_VERSION', '3.0.3' );

define( 'UO_CODES_FILE', __FILE__ );

// On first activation, redirect to toolkit settings page if min php version is met
register_activation_hook( __FILE__, 'uncanny_learndash_codes_plugin_activate' );

function uncanny_learndash_codes_plugin_activate() {
	uncanny_learndash_codes\Database::create_tables();
	if ( is_multisite() ) {
		update_site_option( 'uncanny_learndash_codes_plugin_do_activation_redirect', 'yes' );
	} else {
		update_option( 'uncanny_learndash_codes_plugin_do_activation_redirect', 'yes' );
	}
}

// Allow Translations to be loaded
add_action( 'plugins_loaded', 'uncanny_learndash_codes_text_domain' );

function uncanny_learndash_codes_text_domain() {
	load_plugin_textdomain( 'uncanny-learndash-codes', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

// Plugins Configurations File
include_once( dirname( __FILE__ ) . '/src/config.php' );

// Load all plugin classes(functionality)
include_once( dirname( __FILE__ ) . '/src/boot.php' );

$boot                          = '\uncanny_learndash_codes\Boot';
$uncanny_learndash_codes_class = new $boot;

//Upgrade Database
uncanny_learndash_codes\Database::upgrade_table();
