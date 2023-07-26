<?php
/**
 * Accredible LearnDash Add-on
 *
 * @package Accredible_Learndash_Add_On
 *
 * Plugin Name: Accredible LearnDash Add-on
 * Plugin URI:  https://github.com/accredible/accredible-learndash-add-on
 * Description: Issue credentials, certificates, or badges for your LearnDash courses through Accredible digital credentialing.
 * Version:     1.0.11
 * Author:      Accredible
 * Author URI:  https://www.accredible.com/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || die;

define( 'ACCREDIBLE_LEARNDASH_VERSION', '1.0.11' );
define( 'ACCREDILBE_LEARNDASH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'ACCREDILBE_LEARNDASH_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN', ACCREDIBLE_LEARNDASH_VERSION );

if ( ! defined( 'ACCREDIBLE_LEARNDASH_PLUGIN_URL' ) ) {
	$accredible_learndash_plugin_url = trailingslashit( WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) );
	$accredible_learndash_plugin_url = str_replace( array( 'https://', 'http://' ), array( '//', '//' ), $accredible_learndash_plugin_url );

	/**
	 * Define Accredible - Set the plugin relative URL.
	 *
	 * Will be set based on the WordPress define `WP_PLUGIN_URL`.
	 *
	 * @uses WP_PLUGIN_URL
	 *
	 * @var string URL to plugin install directory.
	 */
	define( 'ACCREDIBLE_LEARNDASH_PLUGIN_URL', $accredible_learndash_plugin_url );
}

// XXX `register_activation_hook` needs to be executed in the plugin main file.
register_activation_hook(
	ACCREDILBE_LEARNDASH_PLUGIN_BASENAME,
	array( 'Accredible_Learndash_Admin_Setting', 'set_default' )
);
register_activation_hook(
	ACCREDILBE_LEARNDASH_PLUGIN_BASENAME,
	array( 'Accredible_Learndash_Admin_Database', 'setup' )
);

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash-admin.php';
	add_action( 'plugins_loaded', array( 'Accredible_Learndash_Admin', 'init' ), 11 );
}

require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash.php';
add_action( 'plugins_loaded', array( 'Accredible_Learndash', 'init' ), 11 );
