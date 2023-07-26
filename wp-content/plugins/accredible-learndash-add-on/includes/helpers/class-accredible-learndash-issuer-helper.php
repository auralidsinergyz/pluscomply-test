<?php
/**
 * Accredible LearnDash Add-on issuer helper
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-form-helper.php';

if ( ! class_exists( 'Accredible_Learndash_Issuer_Helper' ) ) :
	/**
	 * Accredible LearnDash Add-on issuer helper class
	 */
	class Accredible_Learndash_Issuer_Helper {

		/**
		 * Displays issuer information html.
		 *
		 * @param mixed $issuer issuer.
		 */
		public static function display_issuer_info( $issuer ) {
			if ( ! empty( $issuer ) && ! is_null( $issuer ) ) {
				?>
				<div class="status">
					<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/check.png' ); ?>">
					<span><?php esc_html_e( 'Integration is up and running' ); ?></span>
				</div>
				<div class="status-info">
					<div class="left">
						<div class="status-info-item">
							<span class="label"><?php esc_html_e( 'Issuer' ); ?></span>
							<span class="label-value">
								<?php Accredible_Learndash_Admin_Form_Helper::html( $issuer['name'] ); ?>
							</span>
						</div>
						<div class="status-info-item">
							<span class="label"><?php esc_html_e( 'Email' ); ?></span>
							<span class="label-value">
								<?php Accredible_Learndash_Admin_Form_Helper::html( $issuer['email'] ); ?>
							</span>
						</div>
						<div class="status-info-item">
							<span class="label"><?php esc_html_e( 'URL' ); ?></span>
							<span class="label-value">
								<?php Accredible_Learndash_Admin_Form_Helper::html( $issuer['url'] ); ?>
							</span>
						</div>
					</div>
					<div class="right">
						<div class="accredible-credits-tile">
							<img class="credits-icon" src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/credits.png' ); ?>">
							<div class="status-info-item">
								<span class="label"><?php esc_html_e( 'Credits Left' ); ?></span>
								<span class="label-value">
									<?php Accredible_Learndash_Admin_Form_Helper::html( $issuer['certificate_left'] ); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			<?php } else { ?>
				<div class="status">
					<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/error.png' ); ?>">
					<span><?php esc_html_e( 'Integration is not working' ); ?></span>
				</div>
				<?php
			}
		}
	}
endif;
