<?php

namespace uncanny_learndash_codes;


/**
 * Class Boot
 * @package uncanny_learndash_codes
 */
class Boot extends Config {
	/**
	 * class constructor
	 */
	public function __construct() {
		global $uncanny_learndash_codes;

		if ( ! isset( $uncanny_learndash_codes ) ) {
			$uncanny_learndash_codes = new \stdClass();
		}

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'uo_codes_admin_scripts' ) );


		//Adding Classes!
		include_once 'admin-menu.php';
		$uncanny_learndash_codes->admin_menu = new AdminMenu;

		//Add Database Functions!
		include_once 'classes/database.php';
		$uncanny_learndash_codes->database = new Database;

		//Add LearnDash Functions!
		include_once 'classes/learndash.php';
		$uncanny_learndash_codes->learndash = new LearnDash;

		//Add Generate Code Functions!
		include_once 'classes/generate-codes.php';
		$uncanny_learndash_codes->generate_codes = new GenerateCodes;

		//Add Woocommerce Functions!
		include_once 'classes/woocommerce.php';
		$uncanny_learndash_codes->woocommerce = new Woocommerce;

		//Add Gravity Forms Functions!
		include_once 'classes/gravity-forms.php';
		$uncanny_learndash_codes->gravity_forms = new GravityForms;

		//Add Shortcodes Functions!
		include_once 'classes/shortcodes.php';
		$uncanny_learndash_codes->shortcodes = new Shortcodes;

		//Add CSV Functions!
		include_once 'classes/csv.php';
		$uncanny_learndash_codes->csv = new CSV;

		//Add CSV Functions!
		include_once 'classes/theme-my-login.php';
		$uncanny_learndash_codes->theme_my_login = new ThemeMyLogin;
		
		//Add Gravity Forms Code Field Functions!
		include_once 'classes/gravity-forms-code-field.php';
		$uncanny_learndash_codes->gravity_forms_code_field = new GravityFormsCodeField;

		// Import Gutenberg Blocks
		require_once( dirname( __FILE__ ) . '/blocks/blocks.php' );
		new Blocks( 'uncanny_learndash_codes', UNCANNY_LEARNDASH_CODES_VERSION );

		add_action( 'admin_init', array( __CLASS__, 'actions_before_header' ) );

		add_filter( 'plugin_action_links', array( __CLASS__, 'uncanny_learndash_codes_plugin_settings_link' ), 10, 5 );

		add_action( 'plugins_loaded', array( __CLASS__, 'uncanny_learndash_codes_text_domain' ) );
		add_action( 'admin_init', array( __CLASS__, 'uncanny_learndash_codes_plugin_redirect' ) );

		/* Licensing */

		// URL of store powering the plugin
		define( 'UO_CODES_STORE_URL', 'https://www.uncannyowl.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

		// Store download name/title
		define( 'UO_CODES_ITEM_NAME', 'Uncanny LearnDash Codes' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

		// include updater
		include_once 'classes/EDD_SL_Plugin_Updater.php';

		add_action( 'admin_init', array( __CLASS__, 'uo_plugin_updater' ), 0 );
		add_action( 'admin_menu', array( __CLASS__, 'uo_license_menu' ), 50 );
		add_action( 'admin_init', array( __CLASS__, 'uo_activate_license' ) );
		add_action( 'admin_init', array( __CLASS__, 'uo_deactivate_license' ) );

		add_action( 'admin_menu', array( __CLASS__, 'add_help_submenu' ), 30 );
		add_action( 'admin_menu', array( __CLASS__, 'add_uncanny_plugins_page' ), 31 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_external_scripts' ) );
		add_action( 'admin_init', array( __CLASS__, 'uo_admin_help_process' ) );
	}

	/**
	 * Add "Help" submenu
	 */
	public static function add_help_submenu() {
		add_submenu_page(
			'uncanny-learndash-codes',
			__( 'Uncanny LearnDash Codes Support', 'uncanny-learndash-codes' ),
			__( 'Help', 'uncanny-learndash-codes' ),
			'manage_options',
			'uncanny-codes-kb',
			array( __CLASS__, 'include_help_page' )
		);
	}

	/**
	 * Create "Uncanny Plugins" submenu
	 */
	public static function add_uncanny_plugins_page() {
		add_submenu_page(
			'uncanny-learndash-codes',
			__( 'Uncanny LearnDash Plugins', 'uncanny-learndash-codes' ),
			__( 'LearnDash Plugins', 'uncanny-learndash-codes' ),
			'manage_options',
			'uncanny-codes-plugins',
			array( __CLASS__, 'include_learndash_plugins_page' )
		);
	}

	/**
	 * Include "Help" template
	 */
	public static function include_help_page() {
		include( 'templates/admin-help.php' );
	}

	/**
	 * Include "LearnDash Plugins" template
	 */
	public static function include_learndash_plugins_page() {
		include( 'templates/admin-learndash-plugins.php' );
	}

	/**
	 * Enqueue external scripts from uncannyowl.com
	 */
	public static function enqueue_external_scripts() {
		$pages_to_include = [ 'uncanny-codes-plugins', 'uncanny-codes-kb' ];

		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $pages_to_include ) ) {
			wp_enqueue_style( 'uncannyowl-core', 'https://uncannyowl.com/wp-content/mu-plugins/uncanny-plugins-core/dist/bundle.min.css', array(), Config::get_version() );
			wp_enqueue_script( 'uncannyowl-core', 'https://uncannyowl.com/wp-content/mu-plugins/uncanny-plugins-core/dist/bundle.min.js', array( 'jquery' ), Config::get_version() );
		}
	}

	/**
	 * Submit ticket
	 */
	public static function uo_admin_help_process() {
		if ( isset( $_POST['ulc-send-ticket'] ) && check_admin_referer( 'uncanny0w1', 'ulc-send-ticket' ) ) {
			$name     = esc_html( $_POST['fullname'] );
			$email    = esc_html( $_POST['email'] );
			$website  = esc_html( $_POST['website'] );
			$message  = esc_html( $_POST['message'] );
			$siteinfo = stripslashes( $_POST['siteinfo'] );
			if ( isset( $_POST['site-data'] ) && 'yes' === $_POST['site-data'] ) {
				$message = "<h3>Message:</h3><p>{$message}</p><br /><hr /><h3>User Site Information:</h3>{$siteinfo}";
			}

			$to        = 'support.41077.bb1dda3d33afb598@helpscout.net';
			$subject   = esc_html( $_POST['subject'] );
			$headers   = array( 'Content-Type: text/html; charset=UTF-8' );
			$headers[] = 'From: ' . $name . ' <' . $email . '>';
			$headers[] = 'Reply-To:' . $name . ' <' . $email . '>';
			wp_mail( $to, $subject, $message, $headers );
			if ( isset( $_POST['page'] ) ) {
				$url = admin_url( 'admin.php' ) . '?page=' . esc_html( $_POST['page'] ) . '&sent=true&wpnonce=' . wp_create_nonce();
				wp_safe_redirect( $url );
				exit;
			}
		}
	}

	/**
	 * @param $actions
	 * @param $plugin_file
	 *
	 * @return array
	 */
	public static function uncanny_learndash_codes_plugin_settings_link( $actions, $plugin_file ) {
		static $plugin;

		if ( ! isset( $plugin ) ) {
			$plugin = 'uncanny-learndash-codes/uncanny-learndash-codes.php';
		}

		if ( $plugin === $plugin_file ) {
			$settings_link[] = '<a href="' . admin_url( 'admin.php?page=uncanny-learndash-codes-settings' ) . '">Settings</a>';
			$settings_link[] = '<a href="' . admin_url( 'admin.php?page=uncanny-codes-license-activation' ) . '">Licensing</a>';
			$actions         = array_merge( $settings_link, $actions );
		}

		return $actions;
	}

	/**
	 *
	 */
	public static function uncanny_learndash_codes_text_domain() {
		load_plugin_textdomain( 'uncanny-learndash-codes', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 *
	 */
	public static function uncanny_learndash_codes_plugin_redirect() {
		if ( is_multisite() ) {
			if ( 'yes' === get_blog_option( get_current_blog_id(), 'uncanny_learndash_codes_plugin_do_activation_redirect', 'no' ) ) {

				update_site_option( 'uncanny_learndash_codes_plugin_do_activation_redirect', 'no' );

				if ( ! isset( $_GET['activate-multi'] ) ) {
					wp_redirect( admin_url( 'admin.php?page=uncanny-learndash-codes' ) );
				}
			}
		} else {
			if ( 'yes' === get_option( 'uncanny_learndash_codes_plugin_do_activation_redirect', 'no' ) ) {

				update_option( 'uncanny_learndash_codes_plugin_do_activation_redirect', 'no' );

				if ( ! isset( $_GET['activate-multi'] ) ) {
					wp_redirect( admin_url( 'admin.php?page=uncanny-learndash-codes' ) );
				}
			}
		}
	}


	/**
	 *
	 */
	public static function actions_before_header() {
		if ( isset( $_GET['page'] ) && isset( $_GET['mode'] ) ) {
			$page = $_GET['page'];
			$mode = $_GET['mode'];

			switch ( $page ) {
				case 'uncanny-learndash-codes-view' :
					if ( ! isset( $_GET['group_id'] ) ) {
						break;
					}

					switch ( $mode ) {
						case 'download' :
							self::generate_csv( 'login_coupon' );
							break;

						case 'delete' :
							Database::delete_coupon( $_GET['group_id'] );
							header( 'Location: ' . remove_query_arg( array( 'group_id', 'mode' ) ) );
							die;
							break;
					}
					break;
				case 'uncanny-learndash-codes-settings':
					switch ( $mode ) {
						case 'reset' :
							Database::reset_data();
							header( 'Location: ' . add_query_arg( array(
									'saved' => 'true',
								), remove_query_arg( array(
									'mode',
								) ) ) );
							die;
							break;
						case 'tml' :
							//verify if there's TML Directory / File in user's theme
							$dir    = get_stylesheet_directory() . '/theme-my-login/';
							$dest   = get_stylesheet_directory() . ' /theme-my-login/register-form.php';
							$source = Config::get_template( 'frontend-register-form.php' );

							if ( ! file_exists( $dir ) ) {
								mkdir( $dir, 0755, true );
							}

							if ( file_exists( $dest ) ) {
								rename( $dest, $dest . '.bak' );
							}

							if ( ! copy( $source, $dest ) ) {
								header( 'Location: ' . add_query_arg( array(
										'mode' => 'force_download',
									) ) );
								die;
							}

							header( 'Location: ' . add_query_arg( array(
									'saved' => 'true',
								), remove_query_arg( array(
									'mode',
								) ) ) );
							die;
							break;
						case 'force_download' :
							header( 'Location: ' . add_query_arg( array(
									'force_downloaded' => 'true',
								), remove_query_arg( array(
									'mode',
								) ) ) );
							die;
							break;
						case 'download_file' :
							header( 'Content-Type: application/octet-stream' );
							header( 'Content-Transfer-Encoding: Binary' );
							header( 'Content-disposition: attachment; filename="register-form.php"' );
							$source = Config::get_template( 'frontend-register-form.php' );
							echo readfile( $source );
							die;
							break;
						case 'destroy' :
							Database::reset();
							deactivate_plugins( plugin_basename( dirname( dirname( __FILE__ ) ) . '/uncanny-learndash-codes.php' ) );
							header( 'Location: /wp-admin/ ' );
							die;
							break;
					}
			}
		}
	}

	/**
	 * @param $mode
	 */
	public static function generate_csv( $mode ) {
		if ( 'login_coupon' === $mode ) {
			$csv = new CSV( array(
				'filename' => 'login-codes-' . date( 'Y-m-d-HisA', current_time( 'timestamp' ) ),
				'data'     => Database::get_coupons_csv( $_GET['group_id'] ),
			) );

		}
	}

	/**
	 *
	 */
	public static function uo_codes_admin_scripts() {
		wp_enqueue_style( 'uncanny-learndash-codes-backend', Config::get_asset( 'backend', 'bundle.min.css' ), false, '2.0.5' );
		wp_register_script( 'uncanny-learndash-codes-backend', Config::get_asset( 'backend', 'bundle.min.js' ), false, '2.0.5' );

		// Localized translations
		$translation_array = array(
			'PleaseInputMaximumUsageAmount'                      => __( 'Please Input Maximum Usage Amount', 'uncanny-learndash-codes' ),
			'PleaseSelectLearnDashGroups'                        => __( 'Please Select LearnDash Groups', 'uncanny-learndash-codes' ),
			'PleaseSelectLearnDashCourses'                       => __( 'Please Select LearnDash Courses', 'uncanny-learndash-codes' ),
			'PleaseInputLetterLength'                            => __( 'Please Input Letter Length', 'uncanny-learndash-codes' ),
			'TheLengthofPrefixandSuffixisLongerthanLetterLength' => __( 'The Length of Prefix and Suffix is Longer than Letter Length', 'uncanny-learndash-codes' ),
			'TheLengthofPrefixandSuffixissameasLetterLength'     => __( 'The Length of Prefix and Suffix is same as Letter Length', 'uncanny-learndash-codes' ),
			'Doyoureallywanttodeletethis'                        => __( 'Do you really want to delete this?', 'uncanny-learndash-codes' ),
			'Doyoureallywanttodeletethesecodes'                  => __( 'Are you sure you want to delete these codes?  This action is irreversible.', 'uncanny-learndash-codes' ),
		);

		wp_localize_script( 'uncanny-learndash-codes-backend', 'uoCodesStrings', $translation_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'uncanny-learndash-codes-backend' );
	}

	public static function uo_plugin_updater() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( 'uo_codes_license_key' ) );

		// setup the updater
		$uo_updater = new EDD_SL_Plugin_Updater( UO_CODES_STORE_URL, UO_CODES_FILE, array(

			'version'   => UNCANNY_LEARNDASH_CODES_VERSION,     // current version number
			'license'   => $license_key,                    // license key (used get_option above to retrieve from DB)
			'item_name' => UO_CODES_ITEM_NAME,                    // name of this plugin
			'author'    => 'Uncanny Owl',                    // author of this plugin

		) );

	}

	// Licence options page
	public static function uo_license_menu() {

		add_submenu_page( 'uncanny-learndash-codes', __( 'Uncanny Codes License Activation', 'uncanny-learndash-codes' ), __( 'License Activation', 'uncanny-learndash-codes' ), 'manage_options', 'uncanny-codes-license-activation', array(
			__CLASS__,
			'uo_license_page',
		) );

	}

	public static function uo_license_page() {

		self::uo_check_license();

		$license = get_option( 'uo_codes_license_key' );
		$status  = get_option( 'uo_codes_license_status' ); // $license_data->license will be either "valid", "invalid", "expired", "disabled"
		//$license_check = get_option( 'uo_codes_license_check' ); // $license_data->license_check will be either

		// Check license status
		$license_is_active = ( 'valid' === $status ) ? true : false;

		// CSS Classes
		$license_css_classes = array();

		if ( $license_is_active ) {
			$license_css_classes[] = 'ulc-license--active';
		}

		// Set links. Add UTM parameters at the end of each URL
		$where_to_get_my_license = 'https://www.uncannyowl.com/plugin-frequently-asked-questions/#licensekey';
		$buy_new_license         = 'https://www.uncannyowl.com/downloads/uncanny-learndash-codes/';
		$knowledge_base          = menu_page_url( 'uncanny-codes-kb', false );

		include Config::get_template( 'admin-license.php' );
	}

	public static function uo_sanitize_license( $new ) {

		$old = get_option( 'uo_codes_license_key' );
		if ( $old && $old != $new ) {
			delete_option( 'uo_codes_license_status' ); // new license has been entered, so must reactivate
		}

		return $new;
	}


	/************************************
	 * this illustrates how to activate
	 * a license key
	 *************************************/

	public static function uo_activate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['uo_codes_license_activate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'uo_codes_nonce', 'uo_codes_nonce' ) ) {
				return null;
			} // get out if we didn't click the Activate button

			update_option( 'uo_codes_license_key', $_POST['uo_codes_license_key'] );

			// retrieve the license from the database
			$license = trim( get_option( 'uo_codes_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode( UO_CODES_ITEM_NAME ), // the name of our product in uo
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post( UO_CODES_STORE_URL, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "valid" or "invalid"
			update_option( 'uo_codes_license_status', $license_data->license );

		}
	}


	/***********************************************
	 * Illustrates how to deactivate a license key.
	 * This will descrease the site count
	 ***********************************************/

	public static function uo_deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['uo_codes_license_deactivate'] ) ) {


			// run a quick security check
			if ( ! check_admin_referer( 'uo_codes_nonce', 'uo_codes_nonce' ) ) {
				return;
			} // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'uo_codes_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( UO_CODES_ITEM_NAME ), // the name of our product in uo
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post( UO_CODES_STORE_URL, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license === 'deactivated' ) {
				delete_option( 'uo_codes_license_status' );
			}

		}
	}


	/************************************
	 * this illustrates how to check if
	 * a license key is still valid
	 * the updater does this for you,
	 * so this is only needed if you
	 * want to do something custom
	 *************************************/

	public static function uo_check_license() {

		$license = trim( get_option( 'uo_codes_license_key' ) );

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => urlencode( UO_CODES_ITEM_NAME ),
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( UO_CODES_STORE_URL, array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data->license == 'valid' ) {
			update_option( 'uo_codes_license_status', $license_data->license );
			// this license is still valid
		} else {
			update_option( 'uo_codes_license_status', $license_data->license );
			// this license is no longer valid
		}
	}

}