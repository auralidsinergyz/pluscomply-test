<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * This class is used to run any configurations before the plugin is initialized
 *
 * @package    uncanny_learndash_groups
 * @subpackage uncanny_learndash_groups/config
 * @author     Uncanny Owl
 */
class Config {

	/**
	 * @var string
	 */
	public static $invalid_code;
	/**
	 * @var string
	 */
	public static $already_redeemed;
	/**
	 * @var string
	 */
	public static $redeemed_maximum;
	/**
	 * @var string
	 */
	public static $successfully_redeemed;
	/**
	 * @var string
	 */
	public static $seat_not_available;

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
	 * @return Config $instance The Config Class
	 * @since 1.0.0
	 *
	 */
	public static function get_instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Config constructor.
	 */
	public function __construct() {
		self::$invalid_code          = esc_html__( 'Sorry, the code you entered is not valid.', 'uncanny-learndash-groups' );
		self::$already_redeemed      = esc_html__( 'Sorry, the code you entered has already been redeemed.', 'uncanny-learndash-groups' );
		self::$redeemed_maximum      = esc_html__( 'Sorry, the code you entered has already been redeemed maximum times.', 'uncanny-learndash-groups' );
		self::$successfully_redeemed = esc_html__( 'Congratulations, the code you entered has successfully been redeemed.', 'uncanny-learndash-groups' );
		self::$seat_not_available    = esc_html__( 'Sorry, no more seats are available for this group.', 'uncanny-learndash-groups' );

	}

	/**
	 * Initialize the class and setup its properties.
	 *
	 * @param string $plugin_name The name of the plugin
	 * @param string $prefix      The variable used to prefix filters and actions
	 * @param string $version     The version of this plugin
	 * @param string $file        The main plugin file __FILE__
	 * @param bool   $debug       Whether debug log in php and js files are enabled
	 *
	 * @since    1.0.0
	 *
	 */
	public function configure_plugin_before_boot( $plugin_name, $prefix, $version, $file, $debug ) {

		$this->define_constants( $plugin_name, $prefix, $version, $file, $debug );

		do_action( 'ulgm_define_constants_after' );

		do_action( 'ulgm_config_setup_after' );

	}

	/**
	 *
	 * This action is documented in includes/class-plugin-name-deactivator.php
	 *
	 * @param string $plugin_name The name of the plugin
	 * @param string $prefix      Variable used to prefix filters and actions
	 * @param string $version     The version of this plugin.
	 * @param string $plugin_file The main plugin file __FILE__
	 * @param string $debug_mode  Whether debug log in php and js files are enabled
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 */
	private function define_constants( $plugin_name, $prefix, $version, $plugin_file, $debug_mode ) {

		// Set and define version
		if ( ! defined( strtoupper( $prefix ) . '_PLUGIN_NAME' ) ) {
			define( strtoupper( $prefix ) . '_PLUGIN_NAME', $plugin_name );
			Utilities::set_plugin_name( $plugin_name );
		}

		// Set and define version
		if ( ! defined( strtoupper( $prefix ) . '_VERSION' ) ) {
			define( strtoupper( $prefix ) . '_VERSION', $version );
			Utilities::set_version( $version );
		}

		// Set and define prefix
		if ( ! defined( strtoupper( $prefix ) . '_PREFIX' ) ) {
			define( strtoupper( $prefix ) . '_PREFIX', $prefix );
			Utilities::set_prefix( $prefix );
		}

		// Set and define the main plugin file path
		if ( ! defined( $prefix . '_FILE' ) ) {
			define( strtoupper( $prefix ) . '_FILE', $plugin_file );
			Utilities::set_plugin_file( $plugin_file );
		}

		// Set and define debug mode
		if ( ! defined( $prefix . '_DEBUG_MODE' ) ) {
			define( strtoupper( $prefix ) . '_DEBUG_MODE', $debug_mode );
			Utilities::set_debug_mode( $debug_mode );
		}

		// Set and define the server initialization time
		if ( ! defined( $prefix . '_SERVER_INITIALIZATION' ) ) {
			$time = time();
			define( strtoupper( $prefix ) . '_SERVER_INITIALIZATION', $time );
			Utilities::set_plugin_initialization( $time );
		}

		Utilities::log(
			array(
				'get_plugin_name'           => Utilities::get_plugin_name(),
				'get_version'               => Utilities::get_version(),
				'get_prefix'                => Utilities::get_prefix(),
				'get_plugin_file'           => Utilities::get_plugin_file(),
				'get_debug_mode'            => Utilities::get_debug_mode(),
				'get_plugin_initialization' => self::get_date_time_format(),

			),
			'Configuration Variables'
		);

	}

	/**
	 * Get date and time format.
	 *
	 * @return string
	 */
	public static function get_date_time_format() {
		return get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
	}
}
