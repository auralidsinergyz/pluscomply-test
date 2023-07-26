<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class AdminMenu
 *
 * This class should only be used to inherit classes
 *
 * @package uncanny_learndash_groups
 */
class Licensing {

	/**
	 * The name of the licensing page
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public $page_name = null;

	/**
	 * The slug of the licensing page
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public $page_slug = null;

	/**
	 * The slug of the parent that the licensing page is organized under
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public $parent_slug = null;

	/**
	 * The URL of store powering the plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public $store_url = null;

	/**
	 * The Author of the Plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public $item_name = null;

	/**
	 * The Author of the Plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public $author = null;

	/**
	 * Is this a beta version release
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public $beta = null;

	/**
	 * @var bool|string|null
	 */
	public $error = null;

	/**
	 * Licensing constructor.
	 */
	public function __construct() {
		include __DIR__ . '/EDD_SL_Plugin_Updater.php';

		// Create sub-page for EDD licensing
		$this->page_name   = __( 'Licensing', 'uncanny-learndash-groups' );
		$this->page_slug   = 'uncanny-learndash-groups-licensing';
		$this->parent_slug = 'uncanny-groups';
		$this->store_url   = 'https://www.uncannyowl.com/';
		$this->item_name   = 'Uncanny LearnDash Groups';
		$this->author      = 'Uncanny Owl';

		$this->error = $this->set_defaults();

		if ( true !== $this->error ) {

			// Create an admin notices with the error
			add_action( 'admin_notices', array( $this, 'licensing_setup_error' ) );

		} else {

			add_action( 'admin_init', array( $this, 'plugin_updater' ), 0 );
			add_action( 'admin_menu', array( $this, 'license_menu' ), 199 );
			add_action( 'admin_init', array( $this, 'activate_license' ) );
			add_action( 'admin_init', array( $this, 'deactivate_license' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'uo_notify_admin_of_license_expiry_groups', array( $this, 'admin_notices_for_expiry' ) );
			add_action( 'admin_notices', array( $this, 'show_expiry_notice' ) );
			add_action( 'admin_notices', array( $this, 'uo_remind_to_add_license_notice_func' ) );

			//Add license notice
			add_action( 'ulgm_activation_after', array( $this, 'add_cron_to_show_notice' ) );
			add_action( 'uo_remind_to_add_license', array( $this, 'uo_remind_to_add_license_func' ) );
			add_action( 'after_plugin_row', array(
					$this,
					'plugin_row',
			), 10, 3 );
		}
	}


	/**
	 * @param $plugin_name
	 * @param $plugin_data
	 * @param $status
	 */
	public function plugin_row( $plugin_name, $plugin_data, $status ) {
		if ( $plugin_name !== 'uncanny-learndash-groups/uncanny-learndash-groups.php' ) {
			return;
		}
		$slug           = 'uncanny-learndash-groups';
		$license_key    = trim( get_option( 'ulgm_license_key', '' ) );
		$license_status = get_option( 'ulgm_license_status', '' );

		$message = '';


		if ( 'expired' === $license_status ) {
			$message .= sprintf(
					_x(
							'Your license for %s has expired. Click %s to renew.',
							'Your license has expired. Please renew %s license to get instant access to updates and support.',
							'uncanny-learndash-groups'
					),
					'<strong>Uncanny Groups for LearnDash</strong>',
					sprintf(
							'<a href="%s" target="_blank">%s</a>',
							'https://www.uncannyowl.com/checkout/?edd_license_key=' . $license_key . '&download_id=1377&utm_medium=uo_groups&utm_campaign=plugins_page',
							__( 'here', 'uncanny-learndash-groups' )
					)
			);
		} elseif ( empty( $license_key ) || ( 'valid' !== $license_status && 'expired' !== $license_status ) ) {
			$message .= sprintf(
					__( "%s your copy of %s to get access to automatic updates and support. Don't have a license key? Click %s to buy one.", 'uncanny-learndash-groups' ),
					sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=uncanny-learndash-groups-licensing' ), __( 'Register', 'uncanny-learndash-groups' ) ),
					'<strong>Uncanny Groups</strong>',
					sprintf( '<a href="%s" target="_blank">%s</a>', 'https://www.uncannyowl.com/downloads/uncanny-learndash-groups/?utm_medium=uo_groups&utm_campaign=license_page#pricing', __( 'here', 'uncanny-learndash-groups' ) )
			);
		}

		if ( ! empty( $message ) ) {
			if ( is_network_admin() ) {
				$active_class = is_plugin_active_for_network( $plugin_name ) ? ' active' : '';
			} else {
				$active_class = is_plugin_active( $plugin_name ) ? ' active' : '';
			}

			// Get the columns for this table so we can calculate the colspan attribute.
			$screen  = get_current_screen();
			$columns = get_column_headers( $screen );

			// If something went wrong with retrieving the columns, default to 3 for colspan.
			$colspan = ! is_countable( $columns ) ? 3 : count( $columns );

			echo '<tr class="plugin-update-tr' . $active_class . '" id="' . $slug . '-update" data-slug="' . $slug . '" data-plugin="' . $plugin_name . '">';
			echo '<td colspan="' . $colspan . '" class="plugin-update colspanchange">';
			echo '<div class="update-message notice inline notice-warning notice-alt">';
			echo '<p>';
			echo $message;
			echo '</p></div></td></tr>';

			// Apply the class "update" to the plugin row to get rid of the ugly border.
			echo "
				<script type='text/javascript'>
					jQuery('#$slug-update').prev('tr').addClass('update');
				</script>
				";
		}
	}

	/**
	 *
	 */
	public function add_cron_to_show_notice() {
		if ( ! wp_get_scheduled_event( 'uo_remind_to_add_license' ) ) {
			// remind in two weeks to add license
			wp_schedule_single_event( time() + ( ( 3600 * 24 ) * 7 ), 'uo_remind_to_add_license' );
		}
	}

	/**
	 *
	 */
	public function uo_remind_to_add_license_notice_func() {
		if ( wp_get_scheduled_event( 'uo_remind_to_add_license' ) ) {
			return;
		}
		$license_key    = trim( get_option( 'ulgm_license_key', '' ) );
		$license_status = get_option( 'ulgm_license_status', '' );
		if ( ulgm_filter_has_var( 'page' ) && 'uncanny-learndash-groups-licensing' === ulgm_filter_input( 'page' ) ) {
			return;
		}
		if ( ! empty( $license_key ) && ( 'valid' !== $license_status || 'expired' !== $license_status ) ) {
			return;
		}
		?>
		<div class="notice notice-warning is-dismissible">
			<p>
				<?php
				printf(
						__( "%s your copy of %s to get access to automatic updates and support. Don't have a license key? Click %s to buy one.", 'uncanny-learndash-groups' ),
						sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=uncanny-learndash-groups-licensing' ), __( 'Register', 'uncanny-learndash-groups' ) ),
						'<strong>Uncanny Groups</strong>',
						sprintf( '<a href="%s" target="_blank">%s</a>', 'https://www.uncannyowl.com/downloads/uncanny-learndash-groups/?utm_medium=uo_groups&utm_campaign=admin_header#pricing', __( 'here', 'uncanny-learndash-groups' ) )
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Add Licensing menu and sub-page
	 *
	 * @since    1.0.0
	 */
	public function license_menu() {

		$parent_slug = 'uncanny-groups-create-group';
		//Create a sub menu page
		add_submenu_page(
				$parent_slug,
				$this->page_name,
				__( 'License activation', 'uncanny-learndash-groups' ),
				'manage_options',
				$this->page_slug,
				array(
						$this,
						'license_page',
				)
		);
	}

	/**
	 *
	 */
	public function admin_notices_for_expiry() {
		$license_data = $this->check_license();
	}

	/**
	 *
	 */
	public function show_expiry_notice() {
		$status = get_option( 'ulgm_license_status', '' );
		if ( ulgm_filter_has_var( 'page' ) && 'uncanny-learndash-groups-licensing' === ulgm_filter_input( 'page' ) ) {
			return;
		}
		if ( empty( $status ) ) {
			return;
		}
		if ( 'expired' !== $status ) {
			return;
		}
		?>
		<div class="notice notice-error <?php if ( ! $this->is_uo_plugin_page() ) { ?>is-dismissible<?php } ?>">
			<p>
				<?php
				$license = trim( get_option( 'ulgm_license_key' ) );
				printf(
						_x(
								'Your license for %s has expired. Click %s to renew.',
								'Your license has expired. Please renew %s license to get instant access to updates and support.',
								'uncanny-learndash-groups'
						),
						'<strong>Uncanny Groups for LearnDash</strong>',
						sprintf(
								'<a href="%s" target="_blank">%s</a>',
								'https://www.uncannyowl.com/checkout/?edd_license_key=' . $license . '&download_id=1377&utm_medium=uo_groups&utm_campaign=admin_header',
								__( 'here', 'uncanny-learndash-groups' )
						)
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * @return bool
	 */
	public function is_uo_plugin_page() {
		if ( ulgm_filter_has_var( 'page' ) && preg_match( '/uncanny\-groups/', ulgm_filter_input( 'page' ) ) ) {
			return true;
		}
		// License page
//		if ( ulgm_filter_has_var( 'page' ) && preg_match( '/uncanny\-learndash\-groups/', ulgm_filter_input( 'page' ) ) ) {
//			return true;
//		}

		return false;
	}

	/**
	 * Set all the defaults for the plugin licensing
	 *
	 * @return bool|string True if success and error message if not
	 * @since    1.0.0
	 * @access   private
	 *
	 */
	private function set_defaults() {

		if ( null === $this->page_name ) {
			$this->page_name = 'Uncannny Groups Licensing';
		}

		if ( null === $this->page_slug ) {
			$this->page_slug = 'ulgm-licensing';
		}

		if ( null === $this->parent_slug ) {
			$this->parent_slug = false;
		}

		if ( null === $this->store_url ) {
			return __( 'Error: Licensed plugin store URL not set.', 'uncanny-learndash-groups' );
		}

		if ( null === $this->item_name ) {
			return __( 'Error: Licensed plugin item name not set', 'uncanny-learndash-groups' );
		}

		if ( null === $this->author ) {
			$this->author = 'Uncanny Owl';
		}

		if ( null === $this->beta ) {
			$this->beta = false;
		}

		return true;

	}

	/**
	 * Admin Notice to notify that the needed licencing variables have not been set
	 *
	 * @since    1.0.0
	 */
	public function licensing_setup_error() {

		?>
		<div class="notice notice-error is-dismissible">
			<p><?php printf( __( 'There may be an issue with the configuration of %s.', 'uncanny-learndash-groups' ), Utilities::get_plugin_name() ); ?>
				<br><?php echo $this->error; ?></p>
		</div>
		<?php

	}

	/**
	 * Calls the EDD SL Class
	 *
	 * @since    1.0.0
	 */
	public function plugin_updater() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( 'ulgm_license_key' ) );

		// setup the updater
		new EDD_SL_Plugin_Updater(
				$this->store_url,
				UNCANNY_GROUPS_PLUGIN_FILE,
				array(
						'version'   => UNCANNY_GROUPS_VERSION,
						'license'   => $license_key,
						'item_name' => $this->item_name,
						'author'    => $this->author,
						'beta'      => $this->beta,
				)
		);

	}

	/**
	 * Sub-page out put
	 *
	 * @since    1.0.0
	 */
	public function license_page() {

		$license_data = $this->check_license();

		$license = get_option( 'ulgm_license_key' );
		$status  = get_option( 'ulgm_license_status' ); // $license_data->license will be either "valid", "invalid", "expired", "disabled"

		// Check license status
		$license_is_active = ( 'valid' === $status ) ? true : false;

		// CSS Classes
		$license_css_classes = array();

		if ( $license_is_active ) {
			$license_css_classes[] = 'ulgm-license--active';
		}

		// Set links.
		$where_to_get_my_license = 'https://www.uncannyowl.com/plugin-frequently-asked-questions/?utm_medium=uo_groups&utm_campaign=license_page#licensekey';
		$buy_new_license         = 'https://www.uncannyowl.com/downloads/uncanny-learndash-groups/?utm_medium=uo_groups&utm_campaign=license_page';
		$knowledge_base          = menu_page_url( 'uncanny-groups-kb', false );

		include __DIR__ . '/admin-license.php';
	}

	/**
	 * API call to activate License
	 *
	 * @since    1.0.0
	 */
	public function activate_license() {

		// listen for our activate button to be clicked
		if ( ! ulgm_filter_has_var( 'ulgm_license_activate', INPUT_POST ) ) {
			return;
		}

		// run a quick security check
		if ( ! check_admin_referer( 'ulgm_nonce', 'ulgm_nonce' ) ) {
			return;
		} // get out if we didn't click the Activate button

		// Save license key
		$license = sanitize_text_field( trim( ulgm_filter_input( 'ulgm_license_key', INPUT_POST ) ) );
		update_option( 'ulgm_license_key', $license );

		// data to send in our API request
		$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode( $this->item_name ), // the name of our product in uo
				'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post( $this->store_url, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
		) );

		// make sure the response came back okay
		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'uncanny-learndash-groups' );
			}

		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch ( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
								__( 'Your license key expired on %s.', 'uncanny-learndash-groups' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'revoked' :
						$message = __( 'Your license key has been disabled.', 'uncanny-learndash-groups' );
						break;
					case 'missing' :
						$message = __( 'Invalid license.', 'uncanny-learndash-groups' );
						break;
					case 'invalid' :
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.', 'uncanny-learndash-groups' );
						break;
					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'uncanny-learndash-groups' ), $this->item_name );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'uncanny-learndash-groups' );
						break;
					default :
						$message = __( 'An error occurred, please try again.', 'uncanny-learndash-groups' );
						break;
				}
			}
		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'admin.php?page=' . $this->page_slug );
			$redirect = add_query_arg( array(
					'sl_activation' => 'false',
					'message'       => urlencode( $message ),
			), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"
		update_option( 'ulgm_license_status', $license_data->license );
		wp_redirect( admin_url( 'admin.php?page=' . $this->page_slug ) );
		exit();
	}

	/**
	 * API call to de-activate License
	 *
	 * @since    1.0.0
	 */
	public function deactivate_license() {

		// listen for our activate button to be clicked
		if ( ! ulgm_filter_has_var( 'ulgm_license_deactivate', INPUT_POST ) ) {
			return;
		}

		// run a quick security check
		if ( ! check_admin_referer( 'ulgm_nonce', 'ulgm_nonce' ) ) {
			return;
		} // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'ulgm_license_key' ) );


		// data to send in our API request
		$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( $this->item_name ), // the name of our product in uo
				'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post( $this->store_url, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
		) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'uncanny-learndash-groups' );
			}

			$base_url = admin_url( 'admin.php?page=' . $this->page_slug );
			$redirect = add_query_arg( array(
					'sl_activation' => 'false',
					'message'       => urlencode( $message ),
			), $base_url );

			wp_redirect( $redirect );

			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' ) {
			delete_option( 'ulgm_license_status' );
		}

		wp_redirect( admin_url( 'admin.php?page=' . $this->page_slug ) );
		exit();
	}


	/**
	 * Load Scripts that are specific to the admin page
	 *
	 * @param string $hook Admin page being loaded
	 *
	 * @since 1.0
	 *
	 */
	public function admin_scripts( $hook ) {

		/*
		 * Only load styles if the page hook contains the pages slug
		 *
		 * Hook can be either the toplevel_page_{page_slug} if its a parent  page OR
		 * it can be {parent_slug}_pag_{page_slug} if it is a sub page.
		 * Lets just check if our page slug is in the hook.
		 */
		if ( strpos( $hook, $this->page_slug ) !== false ) {
			// Load Styles for Licensing page located in general plugin styles
			wp_enqueue_style( 'ulgm-backend', Utilities::get_asset( 'backend', 'bundle.min.css' ), array(), Utilities::get_version() );
		}

	}


	/**
	 * This is a means of catching errors from the activation method above and displaying it to the customer
	 *
	 * @since    1.0.0
	 */
	public function admin_notices() {

		if ( ulgm_filter_has_var( 'page' ) && $this->page_slug == ulgm_filter_input( 'page' ) ) {

			if ( ulgm_filter_has_var( 'sl_activation' ) && ! empty( ulgm_filter_input( 'message' ) ) ) {

				switch ( ulgm_filter_input( 'sl_activation' ) ) {

					case 'false':

						$message = urldecode( esc_html__( wp_kses( ulgm_filter_input( 'message' ), array() ), 'uncanny-learndash-groups' ) );

						?>
						<div class="notice notice-error">
							<p><?php echo $message; ?></p>
						</div>
						<?php

						break;

					case 'true':
					default:
						?>
						<div class="notice notice-success">
							<p><?php _e( 'License is activated.', 'uncanny-learndash-groups' ); ?></p>
						</div>
						<?php
						break;

				}
			}
		}
	}

	/**
	 * API call to check if License key is valid
	 *
	 * The updater class does this for you. This function can be used to do something custom.
	 *
	 * @since    1.0.0
	 */
	public function check_license() {

		$license = trim( get_option( 'ulgm_license_key' ) );
		if ( empty( $license ) ) {
			return new \stdClass();
		}
		$api_params = array(
				'edd_action' => 'check_license',
				'license'    => $license,
				'item_name'  => urlencode( $this->item_name ),
				'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post( $this->store_url, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// this license is still valid
		if ( $license_data->license == 'valid' ) {
			update_option( 'ulgm_license_status', $license_data->license );
			if ( 'lifetime' !== $license_data->expires ) {
				update_option( 'ulgm_license_expiry', $license_data->expires );
			} else {
				update_option( 'ulgm_license_expiry', date( 'Y-m-d H:i:s', mktime( 12, 59, 59, 12, 31, 2099 ) ) );
			}

			if ( 'lifetime' !== $license_data->expires ) {
				$expire_notification = new \DateTime( $license_data->expires, wp_timezone() );
				update_option( 'ulgm_license_expiry_notice', $expire_notification );
				if ( wp_get_scheduled_event( 'uo_notify_admin_of_license_expiry_groups' ) ) {
					wp_unschedule_hook( 'uo_notify_admin_of_license_expiry_groups' );
				}
				// 1 hour after the license is schedule to expire.
				wp_schedule_single_event( $expire_notification->getTimestamp() + 3600, 'uo_notify_admin_of_license_expiry_groups' );

			}
			wp_unschedule_hook( 'uo_remind_to_add_license' );
		} else {
			update_option( 'ulgm_license_status', $license_data->license );
			// this license is no longer valid
		}

		return $license_data;
	}

}
