<?php
/*
Plugin Name: Uncanny LearnDash Toolkit Pro
Version: 3.4.2
Description: This plugin adds the Pro suite of modules to the Uncanny LearnDash Toolkit.
Author: Uncanny Owl
Author URI: uncannyowl.com
Plugin URI: uncannyowl.com/downloads/uncanny-learndash-toolkit-pro/
Text Domain: uncanny-pro-toolkit
Domain Path: /languages
*/

// Only load pro modules when the Public Uncanny LearnDash Toolkit is loaded
if ( class_exists( '\uncanny_learndash_toolkit\Boot' ) ) {

	define( 'UO_FILE', __FILE__ );
	define( 'UNCANNY_TOOLKIT_PRO_VERSION', '3.4.2' );
	define( 'UNCANNY_TOOLKIT_PRO_PREFIX', 'utkp' );

	//check version of the public toolkit is at least 1.3
	$compare_version = version_compare( UNCANNY_TOOLKIT_VERSION, '1.3' );

	if ( 0 > $compare_version ) {
		add_action( 'current_screen', 'uncanny_learnDash_toolkit_screen' );
	}

	/**
	 *
	 */
	function uncanny_learnDash_toolkit_screen() {

		$current_screen = get_current_screen();

		if ( $current_screen->id === "toplevel_page_uncanny-toolkit" ) {
			add_action( 'admin_notices', 'uncanny_learnDash_toolkit_notice__error' );
		}

	}

	/*
	 * Notice shown on toolkit page if an update is needed before pro can add clasees
	 */
	function uncanny_learnDash_toolkit_notice__error() {
		$class        = 'notice notice-error';
		$warning_text = "Uncanny LearnDash Toolkit PRO needs Uncanny LearnDash Toolkit 1.3 or higher to work properly.";
		$warning_text .= "<br>Please, upgrade the standard Uncanny LearnDash Toolkit.";
		$message      = __( $warning_text, 'uncanny-pro-toolkit' );
		printf( '<div class="%1$s"><h3>%2$s</h3></div>', $class, $message );
	}

	global $uncanny_pro_toolkit;

	// Allow Translations to be loaded
	add_action( 'plugins_loaded', 'uncanny_learndash_toolkit_pro_text_domain' );

	function uncanny_learndash_toolkit_pro_text_domain() {
		load_plugin_textdomain( 'uncanny-pro-toolkit', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	// On first activation, redirect to toolkit license page
	register_activation_hook( __FILE__, 'uncanny_learndash_toolkit_pro_plugin_activate' );
	add_action( 'admin_init', 'uncanny_learndash_toolkit_pro_plugin_redirect' );

	function uncanny_learndash_toolkit_pro_plugin_activate() {

		update_option( 'uncanny_learndash_toolkit_pro_plugin_do_activation_redirect', 'yes' );

	}

	function uncanny_learndash_toolkit_pro_plugin_redirect() {
		if ( 'yes' === get_option( 'uncanny_learndash_toolkit_pro_plugin_do_activation_redirect', 'no' ) ) {

			update_option( 'uncanny_learndash_toolkit_pro_plugin_do_activation_redirect', 'no' );

			if ( ! isset( $_GET['activate-multi'] ) ) {
				wp_redirect( admin_url( 'admin.php?page=uncanny-toolkit-license' ) );
			}
		}
	}

	// Add settings link on plugin page
	$uncanny_learndash_toolkit_pro_plugin_basename = plugin_basename( __FILE__ );

	add_filter( 'plugin_action_links_' . $uncanny_learndash_toolkit_pro_plugin_basename, 'uncanny_learndash_toolkit_pro_plugin_settings_link' );

	/**
	 * @param $links
	 *
	 * @return mixed
	 */
	function uncanny_learndash_toolkit_pro_plugin_settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=uncanny-toolkit-license' ) . '">Licensing</a>';
		array_unshift( $links, $settings_link );
		$settings_link = '<a href="' . admin_url( 'admin.php?page=uncanny-toolkit' ) . '">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	// Load all plugin classes(functionality)
	include_once( dirname( __FILE__ ) . '/src/boot.php' );

	$boot                            = '\uncanny_pro_toolkit\Boot';
	$uncanny_learndash_toolkit_class = new $boot;

} else {

	/*
	 * If PRO version of the toolkit is set for activation and the Free toolkit is not installed and activated,
	 * deactivate the Pro toolkit and show a error message via wp_die(). There is no way to show a message anywhere else
	 * without an active plugin.
	 */

	register_activation_hook( __FILE__, 'uncanny_learndash_toolkit_pro_activate' );

	/**
	 *
	 */
	function uncanny_learndash_toolkit_pro_activate() {

		deactivate_plugins( plugin_basename( __FILE__ ) );

		// Link to Add Plugins page with url params set to look for the Uncanny LearnDash Toolkit
		$toolkit_link = admin_url( 'plugin-install.php?s=Uncanny+LearnDash+Toolkit&tab=search&type=term' );

		$message = '<p style="text-align: center;">Please download and activate Uncanny LearnDash Toolkit Free <br />before activating Uncanny LearnDash Toolkit Pro. </p>';
		$message .= '<p style="text-align: center;" ><a  href="' . $toolkit_link . '">Uncanny LearnDash Toolkit Free</a></p>';

		wp_die( $message );
	}

	/*
	 * If Uncanny LearnDash Toolkit free isn't activated and Uncanny LearnDash Toolkit Pro is activated,
	 * deactivate Uncanny LearnDash ToolKit Pro.
	 */
	add_action( 'plugins_loaded', 'uo_pro_requires_uo_free', 1 );

	/**
	 *
	 */
	function uo_pro_requires_uo_free() {

		remove_action( 'plugins_loaded', 'uo_pro_requires_uo_free' );

		// Deactivate PLugins function is not available to this action adding the plugin file manually
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( plugin_basename( __FILE__ ) );

	}
}
