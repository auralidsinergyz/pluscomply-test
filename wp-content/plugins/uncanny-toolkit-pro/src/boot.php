<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

class Boot extends toolkit\Config {

	/**
	 * class constructor
	 */
	public function __construct() {

		global $uncanny_pro_toolkit;

		if ( ! isset( $uncanny_pro_toolkit ) ) {
			$uncanny_pro_toolkit = new \stdClass();
		}

		// We need to check if spl auto loading is available when activating plugin
		// Plugin will not activate if SPL extension is not enabled by throwing error
		if ( ! extension_loaded( 'SPL' ) ) {
			$spl_error = esc_html__( 'Please contact your hosting company to update to php version 5.3+ and enable spl extensions.', 'uncanny-pro-toolkit' );
			trigger_error( $spl_error, E_USER_ERROR );
		}

		spl_autoload_register( array( __CLASS__, 'auto_loader' ) );


		// Class Details:  Add Class to Admin Menu page
		$classes = self::get_active_classes();

		// Import Gutenberg Blocks
		require_once( dirname( __FILE__ ) . '/blocks/blocks.php' );
		new Blocks( UNCANNY_TOOLKIT_PRO_PREFIX, UNCANNY_TOOLKIT_PRO_VERSION, $classes );

		if ( $classes ) {
			foreach ( self::get_active_classes() as $class ) {

				// Some wp installs remove slashes during db calls, being extra safe when comparing DB vs php values
				if ( strpos( $class, '\\' ) === false ) {
					$class = str_replace( 'pro_toolkit', 'pro_toolkit\\', $class );
				}

				$class_namespace = explode( '\\', $class );

				if ( class_exists( $class ) && __NAMESPACE__ === $class_namespace[0] ) {
					new $class;
				}

			}
		}

		/* Licensing */

		// URL of store powering the plugin
		define( 'UO_STORE_URL', 'https://www.uncannyowl.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

		// Store download name/title
		define( 'UO_ITEM_NAME', 'Uncanny LearnDash Toolkit Pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

		// the name of the settings page for the license input to be displayed
		define( 'UO_LICENSE_PAGE', 'uncanny-toolkit-license' );

		// include updater
		$updater = self::get_include( 'EDD_SL_Plugin_Updater.php', __FILE__ );
		include_once( $updater );

		add_action( 'admin_init', array( __CLASS__, 'uo_plugin_updater' ), 0 );
		add_action( 'admin_menu', array( __CLASS__, 'uo_license_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'uo_activate_license' ) );
		add_action( 'admin_init', array( __CLASS__, 'uo_deactivate_license' ) );
		add_action( 'admin_notices', array( __CLASS__, 'uo_admin_notices' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'uo_license_css' ) );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'uo_enqueue_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'uo_enqueue_backend_assets' ) );
	}

	public static function uo_license_css() {
		$style_url = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/legacy/backend/css/license.css';
		wp_enqueue_style( 'uo-license-css', $style_url, false, UNCANNY_TOOLKIT_PRO_VERSION );
	}


	public static function uo_plugin_updater() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( 'uo_license_key' ) );

		// setup the updater
		$uo_updater = new EDD_SL_Plugin_Updater( UO_STORE_URL, UO_FILE, array(

			'version'   => UNCANNY_TOOLKIT_PRO_VERSION,     // current version number
			'license'   => $license_key,                    // license key (used get_option above to retrieve from DB)
			'item_name' => UO_ITEM_NAME,                    // name of this plugin
			'author'    => 'Uncanny Owl',                   // author of this plugin
			'beta'      => false

		) );

	}

	// Licence options page
	public static function uo_license_menu() {
		add_submenu_page( 'uncanny-toolkit', 'Uncanny Pro License Activation', 'License Activation', 'manage_options', UO_LICENSE_PAGE, array(
			__CLASS__,
			'uo_license_page'
		) );
	}

	public static function uo_license_page() {

		// retrieve the license from the database
		$license = trim( get_option( 'uo_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => urlencode( UO_ITEM_NAME ), // the name of our product in uo
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( UO_STORE_URL, array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params
		) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( 'valid' !== $license_data->license ) {
			// $license_data->license will be either "valid", "invalid", "expired", "disabled", or "inactive"
			update_option( 'uo_license_status', $license_data->license );
		}

		// $license_data->license_check will be either "valid", "invalid", "expired", "disabled", or "inactive"
		update_option( 'uo_license_check', $license_data->license );

		// Get data
		$status        = get_option( 'uo_license_status' ); // $license_data->license will be either "valid", "invalid", "expired", "disabled"
		$license_check = get_option( 'uo_license_check' ); // $license_data->license_check will be either

		/**
		 * Possible values for $status
		 *
		 * {bool}    false     Empty, never saved
		 * {string}  valid       The License is valid
		 * {string}  expired   The License has expired
		 * {string}  invalid   The license is invalid
		 * {string}  inactive  The license has been disabled
		 */

		// Check license status
		$license_is_active = $status == 'valid' ? true : false;

		// CSS Classes
		$css_classes = array();

		if ( $license_is_active ) {
			$css_classes[] = 'uo-license--active';
		}

		// Set links. Add UTM parameters at the end of each URL
		$where_to_get_my_license = 'https://www.uncannyowl.com/plugin-frequently-asked-questions/#licensekey';
		$buy_new_license         = 'https://www.uncannyowl.com/downloads/uncanny-learndash-toolkit-pro/';
		$knowledge_base          = menu_page_url( 'uncanny-toolkit-kb', false );;

		?>

		<div class="wrap"> <!-- WP container -->
			<div class="uo-plugins-header">
				<div class="uo-plugins-header__title">
					Uncanny LearnDash Toolkit
				</div>
				<div class="uo-plugins-header__author">
					<span><?php _e( 'by', 'uncanny-pro-toolkit' ); ?></span>
					<a href="https://uncannyowl.com" target="_blank" class="uo-plugins-header__logo">
						<img src="<?php echo esc_url( \uncanny_learndash_toolkit\Config::get_admin_media( 'uncanny-owl-logo.svg' ) ); ?>"
							 alt="Uncanny Owl">
					</a>
				</div>
			</div>

			<div id="poststuff"> <!-- WP container -->

				<h1 class="nav-tab-wrapper">
					<a href="?page=uncanny-toolkit"
					   class="nav-tab"><?php _e( 'Modules', 'uncanny-pro-toolkit' ); ?></a>
					<a href="?page=uncanny-toolkit-kb"
					   class="nav-tab"><?php _e( 'Help', 'uncanny-pro-toolkit' ); ?></a>
					<a href="?page=uncanny-toolkit-plugins"
					   class="nav-tab"><?php _e( 'LearnDash Plugins', 'uncanny-pro-toolkit' ); ?></a>
					<a href="?page=uncanny-toolkit-license"
					   class="nav-tab nav-tab-active"><?php _e( 'License Activation', 'uncanny-pro-toolkit' ); ?></a>
				</h1>

				<div class="uo-license <?php echo implode( ' ', $css_classes ); ?>">
					<div class="uo-license-status">
						<div class="uo-license-status__icon">

							<?php if ( $license_is_active ) { ?>

								<svg class="uo-license-status-icon__svg" xmlns="http://www.w3.org/2000/svg"
									 xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
									<path class="uo-license-status-icon__svg-path uo-license-status-icon__svg-check"
										  d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
								</svg>

							<?php } else { ?>

								<svg class="uo-license-status-icon__svg" xmlns="http://www.w3.org/2000/svg"
									 xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 352 512">
									<path class="uo-license-status-icon__svg-path uo-license-status-icon__svg-times"
										  d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
								</svg>

							<?php } ?>

						</div>
					</div>
					<div class="uo-license-content">

						<form class="uo-license-content-form" method="POST" action="options.php">

							<?php settings_fields( 'uo_license' ); ?>

							<?php wp_nonce_field( 'uo_nonce', 'uo_nonce' ); ?>

							<div class="uo-license-content-top">
								<div class="uo-license-content-info">

									<?php ?>

									<div class="uo-license-content-title">

										<?php

										if ( $license_is_active ) {
											_e( 'Your License is Active!', 'uncanny-pro-toolkit' );
										} else {
											_e( 'Your License is Not Active!', 'uncanny-pro-toolkit' );
										}

										?>
									</div>

									<div class="uo-license-content-description">

										<?php

										switch ( $license_check ) {
											case 'valid':
												break;

											case 'empty':
												_e( 'Please enter a valid license code and click "Activate now".', 'uncanny-pro-toolkit' );
												break;

											case 'expired':
												printf(
													_x(
														'Your license has expired. Please %s to get instant access to updates and support.',
														'Your license has expired. Please renew your license to get instant access to updates and support.',
														'uncanny-pro-toolkit'
													),
													sprintf(
														'<a href="%s" target="_blank">%s</a>',
														'https://www.uncannyowl.com/checkout/?edd_license_key='.$license.'&download_id=1377',
														_x(
															'renew your license',
															'Your license has expired. Please renew your license to get instant access to updates and support.',
															'uncanny-pro-toolkit' )
													)
												);
												break;

											case 'disabled':
												printf(
													_x( 'Your license is disabled. Please %s to get instant access to updates and support.',
														'Your license has disabled. Please renew your license to get instant access to updates and support.',
														'uncanny-pro-toolkit' ),
													sprintf(
														'<a href="%s" target="_blank">%s</a>',
														'https://www.uncannyowl.com/checkout/?edd_license_key='.$license.'&download_id=1377',
														_x( 'renew your license',
															'Your license has expired. Please renew your license to get instant access to updates and support.',
															'uncanny-pro-toolkit' )
													)
												);
												break;

											case 'invalid':
											case 'inactive':
												_e( 'The license code you entered is invalid.', 'uncanny-pro-toolkit' );
												break;
										}

										?>

									</div>

									<div class="uo-license-content-form">

										<?php if ( $license_is_active ) { ?>

											<input id="uo-license-field" name="uo_license_key"
												   type="password" value="<?php echo esc_attr( $license ); ?>"
												   placeholder="<?php _e( 'Enter your Uncanny LearnDash Toolkit Pro license key', 'uncanny-pro-toolkit' ); ?>"
												   required>

										<?php } else { ?>

											<input id="uo-license-field" name="uo_license_key" type="text"
												   value="<?php echo esc_attr( $license ); ?>"
												   placeholder="<?php _e( 'Enter your Uncanny LearnDash Toolkit Pro license key', 'uncanny-pro-toolkit' ); ?>"
												   required>

										<?php } ?>

									</div>

									<div class="uo-license-content-mobile-buttons">

										<?php if ( $license_is_active ) { ?>

											<button type="submit" name="uo_license_deactivate"
													class="uo-license-btn uo-license-btn--error">
												<?php _e( 'Deactivate License', 'uncanny-pro-toolkit' ); ?>
											</button>

										<?php } else { ?>

											<button type="submit" name="uo_license_activate"
													class="uo-license-btn uo-license-btn--primary">
												<?php _e( 'Activate now', 'uncanny-pro-toolkit' ); ?>
											</button>

											<a href="<?php echo $buy_new_license; ?>" target="_blank"
											   class="uo-license-btn uo-license-btn--secondary">
												<?php _e( 'Buy license', 'uncanny-pro-toolkit' ); ?>
											</a>

										<?php } ?>

									</div>

								</div>
								<div class="uo-license-content-faq">
									<div class="uo-license-content-title">
										<?php _e( 'Need help?', 'uncanny-pro-toolkit' ); ?>
									</div>

									<div class="uo-license-content-faq-list">
										<ul class="uo-license-content-faq-list-ul">
											<li class="uo-license-content-faq-item">
												<a href="<?php echo $where_to_get_my_license; ?>" target="_blank">
													<?php _e( 'Where to get my license key', 'uncanny-pro-toolkit' ); ?>
												</a>
											</li>
											<li class="uo-license-content-faq-item">
												<a href="<?php echo $buy_new_license; ?>" target="_blank">
													<?php _e( 'Buy a new license', 'uncanny-pro-toolkit' ); ?>
												</a>
											</li>
											<li class="uo-license-content-faq-item">
												<a href="<?php echo $knowledge_base; ?>" target="_blank">
													<?php _e( 'Knowledge Base', 'uncanny-pro-toolkit' ); ?>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="uo-license-content-footer">

								<?php if ( $license_is_active ) { ?>

									<button type="submit" name="uo_license_deactivate"
											class="uo-license-btn uo-license-btn--error">
										<?php _e( 'Deactivate License', 'uncanny-pro-toolkit' ); ?>
									</button>

								<?php } else { ?>

									<button type="submit" name="uo_license_activate"
											class="uo-license-btn uo-license-btn--primary">
										<?php _e( 'Activate now', 'uncanny-pro-toolkit' ); ?>
									</button>

									<a href="<?php echo $buy_new_license; ?>" target="_blank"
									   class="uo-license-btn uo-license-btn--secondary">
										<?php _e( 'Buy license', 'uncanny-pro-toolkit' ); ?>
									</a>

								<?php } ?>

							</div>

						</form>

					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function uo_enqueue_frontend_assets(){
		$assets_url = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/dist/';

		$uncanny_toolkit_pro = [
			'restURL' => esc_url_raw( rest_url() . 'uo_toolkit/v1/' ),
			'nonce'   => \wp_create_nonce( 'wp_rest' ),
		];

		wp_register_script( 'ultp-frontend', $assets_url . 'frontend/bundle.min.js', false, UNCANNY_TOOLKIT_PRO_VERSION );
		wp_localize_script( 'ultp-frontend', 'UncannyToolkitPro', $uncanny_toolkit_pro );
		wp_enqueue_script(  'ultp-frontend' );

		wp_enqueue_style( 'ultp-frontend', $assets_url . 'frontend/bundle.min.css', false, UNCANNY_TOOLKIT_PRO_VERSION );
	}

	public static function uo_enqueue_backend_assets(){
		$assets_url = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/dist/';

		wp_enqueue_style( 'ultp-backend', $assets_url . 'backend/bundle.min.css', false, UNCANNY_TOOLKIT_PRO_VERSION );
		wp_enqueue_script( 'ultp-backend', $assets_url . 'backend/bundle.min.js', false, UNCANNY_TOOLKIT_PRO_VERSION );
	}

	/************************************
	 * this illustrates how to activate
	 * a license key
	 *************************************/

	public static function uo_activate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['uo_license_activate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'uo_nonce', 'uo_nonce' ) ) {
				return;
			} // get out if we didn't click the Activate button


			// Save license key
			$license = $_POST['uo_license_key'];
			update_option( 'uo_license_key', $license );

			// retrieve the license from the database
			$license = trim( get_option( 'uo_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode( UO_ITEM_NAME ), // the name of our product in uo
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( UO_STORE_URL, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params
			) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {

					switch ( $license_data->error ) {

						case 'expired' :

							$message = sprintf(
								__( 'Your license key expired on %s.' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

						case 'revoked' :

							$message = __( 'Your license key has been disabled.' );
							break;

						case 'missing' :

							$message = __( 'Invalid license.' );
							break;

						case 'invalid' :
						case 'site_inactive' :

							$message = __( 'Your license is not active for this URL.' );
							break;

						case 'item_name_mismatch' :

							$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), UO_ITEM_NAME );
							break;

						case 'no_activations_left':

							$message = __( 'Your license key has reached its activation limit.' );
							break;

						default :

							$message = __( 'An error occurred, please try again.' );
							break;
					}

				}

			}


			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( 'admin.php?page=' . UO_LICENSE_PAGE );
				$redirect = add_query_arg( array(
					'sl_activation' => 'false',
					'message'       => urlencode( $message )
				), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"

			update_option( 'uo_license_status', $license_data->license );

			wp_redirect( admin_url( 'admin.php?page=' . UO_LICENSE_PAGE ) );
			exit();

		}
	}


	/***********************************************
	 * Illustrates how to deactivate a license key.
	 * This will descrease the site count
	 ***********************************************/

	public static function uo_deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['uo_license_deactivate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'uo_nonce', 'uo_nonce' ) ) {
				return;
			} // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'uo_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( UO_ITEM_NAME ), // the name of our product in uo
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( UO_STORE_URL, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params
			) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

				$base_url = admin_url( 'admin.php?page=' . UO_LICENSE_PAGE );
				$redirect = add_query_arg( array(
					'sl_activation' => 'false',
					'message'       => urlencode( $message )
				), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' || $license_data->license == 'failed' ) {
				delete_option( 'uo_license_status' );
			}

			wp_redirect( admin_url( 'admin.php?page=' . UO_LICENSE_PAGE ) );
			exit();

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

		global $wp_version;

		$license = trim( get_option( 'uo_license_key' ) );

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => urlencode( UO_ITEM_NAME ),
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( UO_STORE_URL, array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data->license == 'valid' ) {
			echo 'valid';
			exit;
			// this license is still valid
		} else {
			echo 'invalid';
			exit;
			// this license is no longer valid
		}
	}

	/**
	 * This is a means of catching errors from the activation method above and displaying it to the customer
	 */
	public static function uo_admin_notices() {

		if ( isset( $_GET['page'] ) && 'uncanny-toolkit-license' == $_GET['page'] ) {

			if ( isset( $_GET['sl_activation'] ) && isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

				switch ( $_GET['sl_activation'] ) {

					case 'false':
						$message = urldecode( $_GET['message'] );
						?>
						<div class="error">
							<p><?php echo $message; ?></p>
						</div>
						<?php
						break;

					case 'true':
					default:
						// Developers can put a custom success message here for when activation is successful if they way.
						break;

				}
			}
		}
	}


	/**
	 *
	 *
	 * @static
	 *
	 * @param $class
	 */
	private static function auto_loader( $class ) {

		// Remove Class's namespace eg: my_namespace/MyClassName to MyClassName
		$class = str_replace( 'uncanny_pro_toolkit', '', $class );
		$class = str_replace( '\\', '', $class );

		// First Character of class name to lowercase eg: MyClassName to myClassName
		$class_to_filename = lcfirst( $class );

		// Split class name on upper case letter eg: myClassName to array( 'my', 'Class', 'Name')
		$split_class_to_filename = preg_split( '#([A-Z][^A-Z]*)#', $class_to_filename, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

		if ( 1 <= count( $split_class_to_filename ) ) {
			// Split class name to hyphenated name eg: array( 'my', 'Class', 'Name') to my-Class-Name
			$class_to_filename = implode( '-', $split_class_to_filename );
		}
		// Create file name that will be loaded from the classes directory eg: my-Class-Name to my-class-name.php
		$file_name = 'classes/' . strtolower( $class_to_filename ) . '.php';
		if ( file_exists( dirname( __FILE__ ) . '/' . $file_name ) ) {
			include_once $file_name;
		}

	}
}





