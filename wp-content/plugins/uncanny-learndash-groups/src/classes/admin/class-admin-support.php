<?php


namespace uncanny_learndash_groups;


/**
 * Class Admin_Support
 * @package uncanny_learndash_groups
 */
class Admin_Support {
	/**
	 * Admin_Support constructor.
	 */
	public function __construct() {

		add_action( 'admin_menu', array( __CLASS__, 'add_help_submenu' ), 149 );
		add_action( 'admin_menu', array( __CLASS__, 'add_uncanny_plugins_page' ), 152 );
		add_action( 'admin_init', array( __CLASS__, 'uo_admin_help_process' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_external_scripts' ) );
	}

	/**
	 * Add "Help" submenu
	 */
	public static function add_help_submenu() {
		add_submenu_page(
			'uncanny-groups-create-group',
			__( 'Uncanny Groups for LearnDash Support', 'uncanny-learndash-groups' ),
			__( 'Help', 'uncanny-learndash-groups' ),
			'manage_options',
			'uncanny-groups-kb',
			array( __CLASS__, 'include_help_page' )
		);
	}

	/**
	 * Create "Uncanny Plugins" submenu
	 */
	public static function add_uncanny_plugins_page() {
		add_submenu_page(
			'uncanny-groups-create-group',
			__( 'Uncanny LearnDash Plugins', 'uncanny-learndash-groups' ),
			__( 'LearnDash plugins', 'uncanny-learndash-groups' ),
			'manage_options',
			'uncanny-groups-plugins',
			array( __CLASS__, 'include_learndash_plugins_page' )
		);
	}

	/**
	 * Enqueue external scripts from uncannyowl.com
	 */
	public static function enqueue_external_scripts() {
		$pages_to_include = [ 'uncanny-groups-plugins', 'uncanny-groups-kb' ];

		if ( ulgm_filter_has_var( 'page' ) && in_array( ulgm_filter_input( 'page' ), $pages_to_include ) ) {
			wp_enqueue_style( 'uncannyowl-core', 'https://uncannyowl.com/wp-content/mu-plugins/uncanny-plugins-core/dist/bundle.min.css', array(), UNCANNY_GROUPS_VERSION );

			wp_enqueue_script( 'uncannyowl-core', 'https://uncannyowl.com/wp-content/mu-plugins/uncanny-plugins-core/dist/bundle.min.js', array( 'jquery' ), UNCANNY_GROUPS_VERSION );
		}
	}

	/**
	 * Include "Help" template
	 */
	public static function include_help_page() {
		include Utilities::get_template( 'admin/admin-help.php' );
	}

	/**
	 * Include "LearnDash Plugins" template
	 */
	public static function include_learndash_plugins_page() {
		include Utilities::get_template( 'admin/admin-learndash-plugins.php' );
	}

	/**
	 * Submit ticket
	 */
	public static function uo_admin_help_process() {
		if ( ulgm_filter_has_var( 'ulgm-send-ticket', INPUT_POST ) && check_admin_referer( 'uncanny0w1', 'ulgm-send-ticket' ) ) {
			$name        = esc_html( ulgm_filter_input( 'fullname', INPUT_POST ) );
			$email       = esc_html( ulgm_filter_input( 'email', INPUT_POST ) );
			$website     = esc_url_raw( ulgm_filter_input( 'website', INPUT_POST ) );
			$license_key = esc_html( ulgm_filter_input( 'license_key', INPUT_POST ) );
			$message     = esc_html( ulgm_filter_input( 'message', INPUT_POST ) );
			$siteinfo    = stripslashes( $_POST['siteinfo'] );
			$message     = '<h3>Message:</h3><br/>' . wpautop( $message );
			if ( ! empty( $website ) ) {
				$message .= '<hr /><strong>Website:</strong> ' . $website;
			}
			if ( ! empty( $license_key ) ) {
				$message .= '<hr /><strong>License:</strong> <a href="https://www.uncannyowl.com/wp-admin/edit.php?post_type=download&page=edd-licenses&s=' . $license_key . '" target="_blank">' . $license_key . '</a>';
			}
			if ( isset( $_POST['site-data'] ) && 'yes' === sanitize_text_field( $_POST['site-data'] ) ) {
				$message = "$message<hr /><h3>User Site Information:</h3><br />{$siteinfo}";
			}

			$to        = 'support.41077.bb1dda3d33afb598@helpscout.net';
			$subject   = esc_html( ulgm_filter_input( 'subject', INPUT_POST ) );
			$headers   = array( 'Content-Type: text/html; charset=UTF-8' );
			$headers[] = 'From: ' . $name . ' <' . $email . '>';
			$headers[] = 'Reply-To:' . $name . ' <' . $email . '>';
			wp_mail( $to, $subject, $message, $headers );
			if ( ulgm_filter_has_var( 'page', INPUT_POST ) ) {
				$url = admin_url( 'admin.php' ) . '?page=' . esc_html( ulgm_filter_input( 'page', INPUT_POST ) ) . '&sent=true&wpnonce=' . wp_create_nonce();
				wp_safe_redirect( $url );
				exit;
			}
		}
	}
}
