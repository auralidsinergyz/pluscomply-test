<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://edwiser.org
 * @since      1.3.4
 * @package    Edwiser Bridge
 */

namespace app\wisdmlabs\edwiserBridge;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Eb admin handler.
 */
class Eb_Admin_Notice_Handler {

	/**
	 * Check if installed.
	 */
	public function check_if_moodle_plugin_installed() {
		$plugin_installed   = 1;
		$connection_options = get_option( 'eb_connection' );
		$eb_moodle_url      = '';
		if ( isset( $connection_options['eb_url'] ) ) {
			$eb_moodle_url = $connection_options['eb_url'];
		}
		$eb_moodle_token = '';
		if ( isset( $connection_options['eb_access_token'] ) ) {
			$eb_moodle_token = $connection_options['eb_access_token'];
		}
		$request_url = $eb_moodle_url . '/webservice/rest/server.php?wstoken=';

		$moodle_function = 'eb_get_course_progress';
		$request_url    .= $eb_moodle_token . '&wsfunction=' . $moodle_function . '&moodlewsrestformat=json';
		$response        = wp_remote_post( $request_url );

		if ( is_wp_error( $response ) ) {
			$plugin_installed = 0;
		} elseif ( 200 === wp_remote_retrieve_response_code( $response ) ||
				300 === wp_remote_retrieve_response_code( $response ) ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( 'accessexception' === $body->errorcode ) {
				$plugin_installed = 0;
			}
		} else {
			$plugin_installed = 0;
		}

		return $plugin_installed;
	}

	/**
	 * Get Moodle plugin Info.
	 * Currently only version is provided.
	 */
	public function eb_get_mdl_plugin_info() {
		$connection_options = get_option( 'eb_connection' );
		$eb_moodle_url      = '';
		if ( isset( $connection_options['eb_url'] ) ) {
			$eb_moodle_url = $connection_options['eb_url'];
		}
		$eb_moodle_token = '';
		if ( isset( $connection_options['eb_access_token'] ) ) {
			$eb_moodle_token = $connection_options['eb_access_token'];
		}
		$request_url = $eb_moodle_url . '/webservice/rest/server.php?wstoken=';

		$moodle_function = 'eb_get_edwiser_plugins_info';
		$request_url    .= $eb_moodle_token . '&wsfunction=' . $moodle_function . '&moodlewsrestformat=json';
		$request_args    = array(
			'sslverify' => false,
			'body'      => array(),
			'timeout'   => 100,
		);
		$response        = wp_remote_post( $request_url, $request_args );

		$status = 0;

		if ( is_wp_error( $response ) ) {
			return $status;
		} elseif ( 200 === wp_remote_retrieve_response_code( $response ) ||
				300 === wp_remote_retrieve_response_code( $response ) ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( isset( $body->plugin_name ) && isset( $body->version ) && 0 === version_compare( '2.0.7', $body->version ) ) {
				$status = 1;
			}
		} else {
			$status = 0;
		}

		return $status;

	}



	/**
	 * Show admin feedback notice.
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_update_moodle_plugin_notice() {
		$redirection = add_query_arg( 'eb-update-notice-dismissed', true );

		if ( ! get_option( 'eb_mdl_plugin_update_notice_dismissed' ) ) {
			if ( ! $this->check_if_moodle_plugin_installed() ) {
				echo '  <div class="notice  eb_admin_update_notice_message_cont">
							<div class="eb_admin_update_notice_message">

								<div class="eb_update_notice_content">
									' . esc_html__( 'Thanks for updating to the latest version of Edwiser Bridge plugin, please make sure you have also installed our associated Moodle Plugin to avoid any malfunctioning.', 'edwiser-bridge' ) . '
									<a href="https://edwiser.org/wp-content/uploads/edd/2022/12/edwiserbridge.zip">' . esc_html__( ' Click here ', 'edwiser-bridge' ) . '</a>
									' . esc_html__( ' to download Moodle plugin.', 'edwiser-bridge' ) . '

										' . esc_html__( 'For setup assistance check our ', 'edwiser-bridge' ) . '
										<a href="https://edwiser.org/bridge/documentation/#tab-b540a7a7-e59f-3">' . esc_html__( ' documentation', 'edwiser-bridge' ) . '</a>.
								</div>
								
								<div class="eb_update_notice_dismiss_wrap">
									<span style="padding-left: 5px;">
										<a href="' . esc_html( $redirection ) . '">
											' . esc_html__( ' Dismiss notice', 'edwiser-bridge' ) . '
										</a>
									</span>
								</div>

							</div>
							<div class="eb_admin_update_dismiss_notice_message">
									<span class="dashicons dashicons-dismiss eb_update_notice_hide"></span>
							</div>
						</div>';
			} elseif ( ! $this->eb_get_mdl_plugin_info() ) {
				echo '  <div class="notice  eb_admin_update_notice_message_cont">
							<div class="eb_admin_update_notice_message">

								<div class="eb_update_notice_content">
									' . esc_html__( 'Thanks for updating or installing Edwiser Bridge plugin, please update Moodle Plugin to avoid any malfunctioning.', 'edwiser-bridge' ) . '
									<a href="https://edwiser.org/wp-content/uploads/edd/2022/12/edwiserbridge.zip">' . esc_html__( ' Click here ', 'edwiser-bridge' ) . '</a>
									' . esc_html__( ' to download Moodle plugin.', 'edwiser-bridge' ) . '

										' . esc_html__( 'For setup assistance check our ', 'edwiser-bridge' ) . '
										<a href="https://edwiser.org/bridge/documentation/#tab-b540a7a7-e59f-3">' . esc_html__( ' documentation', 'edwiser-bridge' ) . '</a>.
								</div>
								
								<div class="eb_update_notice_dismiss_wrap">
									<span style="padding-left: 5px;">
										<a href="' . esc_html( $redirection ) . '">
											' . esc_html__( ' Dismiss notice', 'edwiser-bridge' ) . '
										</a>
									</span>
								</div>

							</div>
							<div class="eb_admin_update_dismiss_notice_message">
									<span class="dashicons dashicons-dismiss eb_update_notice_hide"></span>
							</div>
						</div>';
			} else {
				update_option( 'eb_mdl_plugin_update_notice_dismissed', 'true', true );
			}
		}
	}



	/**
	 * NOT USED FUNCTION
	 * handle notice dismiss
	 *
	 * @deprecated since 2.0.1 discontinued.
	 * @since 1.3.1
	 */
	public function eb_admin_discount_notice_dismiss_handler() {
		if ( true === filter_input( INPUT_GET, 'eb-discount-notice-dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			$user_id = get_current_user_id();
			add_user_meta( $user_id, 'eb_discount_notice_dismissed', 'true', true );
		}
	}


	/**
	 * NOT USED FUNCTION
	 * show admin feedback notice
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_discount_notice() {
		$redirection = add_query_arg( 'eb-discount-notice-dismissed', true );

		$user_id = get_current_user_id();
		if ( ! get_user_meta( $user_id, 'eb_discount_notice_dismissed' ) ) {
			echo '  <div class="notice  eb_admin_discount_notice_message">
						<div class="eb_admin_discount_notice_message_cont">
							<div class="eb_admin_discount_notice_content">
								' . esc_html__( 'Get all Premium Edwiser Products at Flat 20% Off!', 'edwiser-bridge' ) . '

								<div style="font-size:13px; padding-top:4px;">
									<a href="' . esc_html( $redirection ) . '">
										' . esc_html__( ' Dismiss this notice', 'edwiser-bridge' ) . '
									</a>
								</div>
							</div>
							<div>
								<a class="eb_admin_discount_offer_btn" href="https://edwiser.org/edwiser-lifetime-kit/?utm_source=WordPress&utm_medium=notif&utm_campaign=inbridge"  target="_blank">' . esc_html__( 'Avail Offer Now!', 'edwiser-bridge' ) . '</a>
							</div>
						</div>
						<div class="eb_admin_discount_dismiss_notice_message">
							<span class="dashicons dashicons-dismiss eb_admin_discount_notice_hide"></span>
						</div>
					</div>';
		}
	}





	/**
	 * Handle notice dismiss
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_update_notice_dismiss_handler() {
		if ( true === filter_input( INPUT_GET, 'eb-update-notice-dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			update_option( 'eb_mdl_plugin_update_notice_dismissed', 'true', true );
		}
	}




	/**
	 * NOT USED FUNCTION
	 * show admin feedback notice
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_feedback_notice() {
		$redirection       = add_query_arg( 'eb-feedback-notice-dismissed', true );
		$user_id           = get_current_user_id();
		$feedback_usermeta = get_user_meta( $user_id, 'eb_feedback_notice_dismissed', true );
		if ( 'eb_admin_feedback_notice' !== get_transient( 'edwiser_bridge_admin_feedback_notice' ) && ( ! $feedback_usermeta || 'remind_me_later' !== $feedback_usermeta ) && 'dismiss_permanantly' !== $feedback_usermeta ) {
				echo '  <div class="notice eb_admin_feedback_notice_message_cont">
							<div class="eb_admin_feedback_notice_message">'
								. esc_html__( 'Enjoying Edwiser bridge, Please  ', 'edwiser-bridge' ) . '
								<a href="https://WordPress.org/plugins/edwiser-bridge/">'
									. esc_html__( ' click here ', 'edwiser-bridge' ) .
								'</a>'
								. esc_html__( ' to rate us.', 'edwiser-bridge' ) . '
								<div style="padding-top:8px; font-size:13px;">
									<span class="eb_feedback_rate_links">
										<a href="' . esc_html( $redirection ) . '=remind_me_later">
										' . esc_html__( 'Remind me Later!', 'edwiser-bridge' ) . '
										</a>
									</span>
									<span class="eb_feedback_rate_links">
										<a href="' . esc_html( $redirection ) . '=dismiss_permanantly">
										' . esc_html__( 'Dismiss Notice', 'edwiser-bridge' ) . '
										</a>
									</span>
								</div>
							</div>
							<div class="eb_admin_feedback_dismiss_notice_message">
								<span class="dashicons dashicons-dismiss"></span>
							</div>
						</div>';
		}
	}


	/**
	 * Functionality to show admin dashboard template compatibility notice.
	 * We will remove it in the next version.
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_template_notice() {
		// Notice dismiss handler code here.
		if ( true === filter_input( INPUT_GET, 'eb_templ_compatibility_dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			$user_id = get_current_user_id();
			add_user_meta( $user_id, 'eb_templ_compatibility_dismissed', filter_input( INPUT_GET, 'eb_templ_compatibility_dismissed', FILTER_VALIDATE_BOOLEAN ), true );
		} else {
			$redirection    = add_query_arg( 'eb_templ_compatibility_dismissed', true );
			$user_id        = get_current_user_id();
			$templ_usermeta = get_user_meta( $user_id, 'eb_templ_compatibility_dismissed', true );
			if ( empty( $templ_usermeta ) || ! $templ_usermeta ) {
				$msg = esc_html__( 'If you have overridden the standard Edwiser Bridge templates previously then please make sure that your templates are made compatible with the NEW Edwiser Bridge template. It may cause CSS breaks if not done. ', 'edwiser-bridge' );

				echo '<div class="notice notice-warning eb_template_notice_wrap"">
						<div class="eb_template_notice">
							' . esc_html__( 'If you have overridden the standard', 'edwiser-bridge' ) . '
							<b> Edwiser Bridge </b>' . esc_html__( 'templates previously then please make sure that your templates are made compatible with the ', 'edwiser-bridge' ) . ' <b>NEW Edwiser Bridge</b>
							' . esc_html__( 'template. It may cause CSS breaks if not done.', 'edwiser-bridge' ) . '
							<div class="">
								' . esc_html__( 'Please refer to', 'edwiser-bridge' ) . '<a href="https://edwiser.org/blog/how-to-make-edwiser-bridge-compatible-with-your-theme/" target="_blank"> <b>' . esc_html__( ' this ', 'edwiser-bridge' ) . '</b> </a>' . esc_html__( ' article for theme compatibility', 'edwiser-bridge' ) . '
							</div>
						</div>
						<div class="eb_admin_templ_dismiss_notice_message">
							<a href="' . esc_html( $redirection ) . '">
								<span class="dashicons dashicons-dismiss"></span> 
							</a>
						</div>
					</div>';
			}
		}

	}


	/**
	 * Handle notice dismiss
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_notice_dismiss_handler() {
		if ( true === filter_input( INPUT_GET, 'eb-feedback-notice-dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			$user_id = get_current_user_id();
			add_user_meta( $user_id, 'eb_feedback_notice_dismissed', filter_input( INPUT_GET, 'eb-feedback-notice-dismissed', FILTER_VALIDATE_BOOLEAN ), true );
		}
	}






	/**
	 * SHow notfi.
	 *
	 * @param text $curr_plugin_meta_data curr_plugin_meta_data.
	 * @param text $new_plugin_meta_data new_plugin_meta_data.
	 */
	public function eb_show_inline_plugin_update_notification( $curr_plugin_meta_data, $new_plugin_meta_data ) {
		ob_start();
		?>
<p>
	<strong><?php echo esc_html__( 'Important Update Notice:', 'edwiser-bridge' ); ?></strong>
		<?php echo esc_html__( 'Please download and update associated edwiserbridge Moodle plugin.', 'edwiser-bridge' ); ?>
	<a href="https://edwiser.org/bridge/"><?php echo esc_html__( 'Click here ', 'edwiser-bridge' ); ?></a>
		<?php echo esc_html__( ' to download', 'edwiser-bridge' ); ?>

</p>

		<?php
		echo wp_kses( ob_get_clean(), \app\wisdmlabs\edwiserBridge\wdm_eb_get_allowed_html_tags() );

		// added this just for commit purpose.
		unset( $curr_plugin_meta_data );
		unset( $new_plugin_meta_data );
	}

	/**
	 * bfcm notice dismiss handler.
	 */
	public function eb_admin_bfcm_notice_dismiss_handler() {
		if ( true === filter_input( INPUT_GET, 'eb-admin-bfcm-pre-notice-dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			$user_id = get_current_user_id();
			add_user_meta( $user_id, 'eb_admin_bfcm_pre_notice_dismissed', filter_input( INPUT_GET, 'eb-admin-bfcm-pre-notice-dismissed', FILTER_VALIDATE_BOOLEAN ), true );
		}
		if ( true === filter_input( INPUT_GET, 'eb-admin-bfcm-notice-dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			$user_id = get_current_user_id();
			add_user_meta( $user_id, 'eb_admin_bfcm_notice_dismissed', filter_input( INPUT_GET, 'eb-admin-bfcm-notice-dismissed', FILTER_VALIDATE_BOOLEAN ), true );
		}
	}
	/**
	 * BFCM admin notice
	 *
	 * @since 2.2.4
	 */
	public function eb_admin_bfcm_notice() {


		$user_id = get_current_user_id();
		global $pagenow;
		$screen = get_current_screen();
		if ( is_admin() && ( 'index.php' === $pagenow || 'eb_course_page_eb-settings' === $screen->id ) ) {
			
			$eb_plugin_url = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_url();
			
			// chek if current date is between 4th and 23rd of november.
			$bfcm_pre_start_date = strtotime( '2022-11-04' );
			$bfcm_pre_end_date   = strtotime( '2022-11-23' );
			$bfcm_start_date     = strtotime( '2022-11-24' );
			$bfcm_end_date       = strtotime( '2022-11-30' );
			$current_date        = strtotime( wp_date( 'Y-m-d' ) );

			// pre bfcm banner.
			if( $current_date >= $bfcm_pre_start_date && $current_date <= $bfcm_pre_end_date && ! get_user_meta( $user_id, 'eb_admin_bfcm_pre_notice_dismissed' ) ) {
				$redirection    = add_query_arg( 'eb-admin-bfcm-pre-notice-dismissed', true );
				?>
				<div class="notice eb-admin-bfcm-notice-message">
					<div class="eb-admin-bfcm-notice-message-content" style="background-image: url('<?php echo $eb_plugin_url; ?>images/bfcm-pre.png');">
						<p class="title"><?php esc_html_e( 'Play a Game with Edwiser to get ahead of the Black Friday Sale!', 'edwiser-bridge' ); ?></p>
						<p class="desc"><?php esc_html_e( 'Spin the Wheel to Win Free Access or Discounts on our Premium Moodle theme & plugins', 'edwiser-bridge' ); ?></p>
						<a class="button" href="https://edwiser.org/edwiser-black-friday-giveaway/?utm_source=giveaway&utm_medium=spinthewheel&utm_campaign=bfcm22"  target="_blank"><?php esc_html_e( 'Spin and Win', 'edwiser-bridge' ); ?></a>
					</div>
					<div class="eb-admin-bfcm-notice-message-dismiss">
						<a href="<?php echo esc_html( $redirection ); ?>">
							<span class="dashicons dashicons-no-alt eb_admin_bfcm_notice_hide"></span>
						</a>
					</div>
				</div>
				<?php
			} elseif ( $current_date >= $bfcm_start_date && $current_date <= $bfcm_end_date && ! get_user_meta( $user_id, 'eb_admin_bfcm_notice_dismissed' )) {
				// bfcm banner.
				$extensions  = array(
					'woocommerce-integration/bridge-woocommerce.php',
					'selective-synchronization/selective-synchronization.php',
					'edwiser-bridge-sso/sso.php',
					'edwiser-multiple-users-course-purchase/edwiser-multiple-users-course-purchase.php',
				);
				foreach ( $extensions as $plugin_path ) {
					if ( is_plugin_active( $plugin_path ) ) {
						$free = false;
					} else {
						$free = true;
						break;
					}
				}
				$redirection    = add_query_arg( 'eb-admin-bfcm-notice-dismissed', true );
				?>
				<div class="notice eb-admin-bfcm-notice-message">
					<div class="eb-admin-bfcm-notice-message-content" style="background-image: url('<?php echo $eb_plugin_url; ?>images/bfcm.png');">
						<p class="title"><?php esc_html_e( 'Get the power of selling courses via WooCommerce!', 'edwiser-bridge' ); ?></p>
						<?php
						if ( $free ) {
							?>
							<p class="desc"><?php esc_html_e( 'Get amazing Black Friday discounts on Edwiser Bridge Pro', 'edwiser-bridge' ); ?></p>
							<a class="button" href="https://edwiser.org/bridge/?utm_source=freeplugin&utm_medium=banner&utm_campaign=bfcm22"  target="_blank"><?php esc_html_e( 'Upgrade Now', 'edwiser-bridge' ); ?></a>
							<?php
						} else {
							?>
							<p class="desc"><?php esc_html_e( 'Get amazing Black Friday discounts on Edwiser Bridge Pro Lifetime license', 'edwiser-bridge' ); ?></p>
							<a class="button" href="https://edwiser.org/bridge/?utm_source=proplugin&utm_medium=banner&utm_campaign=bfcm22"  target="_blank"><?php esc_html_e( 'Upgrade tO Lifetime', 'edwiser-bridge' ); ?></a>
							<?php
						}
						?>
					</div>
					<div class="eb-admin-bfcm-notice-message-dismiss">
						<a href="<?php echo esc_html( $redirection ); ?>">
							<span class="dashicons dashicons-no-alt eb_admin_bfcm_notice_hide"></span>
						</a>
					</div>
				</div>
				<?php
			}
		}
	}
}
