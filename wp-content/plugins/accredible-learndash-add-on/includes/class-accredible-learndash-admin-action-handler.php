<?php
/**
 * Server-side actions on the admin panel.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . 'models/class-accredible-learndash-model-auto-issuance.php';

if ( ! class_exists( 'Accredible_Learndash_Admin_Action_Handler' ) ) :
	/**
	 * Accredible LearnDash Add-on admin action handler class
	 */
	class Accredible_Learndash_Admin_Action_Handler {
		/**
		 * Call the requested action.
		 *
		 * @throws Exception Exception containing the error message.
		 *
		 * @return mixed results from called action.
		 */
		public static function call() {
			$action        = self::sanitize_parameter( 'call_action' );
			$class_methods = get_class_methods( 'Accredible_Learndash_Admin_Action_Handler' );
			if ( in_array( $action, $class_methods, true ) ) {
				$data = array(
					'id'                          => self::sanitize_parameter( 'id' ),
					'nonce'                       => self::sanitize_parameter( '_mynonce' ),
					'page_num'                    => self::sanitize_parameter( 'page_num' ),
					'accredible_learndash_object' => self::sanitize_object( $action ),
					'redirect_url'                => isset( $_REQUEST['redirect_url'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_url'] ) ) : wp_get_referer(), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				);
				return self::$action( $data );
			} else {
				throw new Exception( 'An action type mismatch has been detected.' );
			}
		}

		/**
		 * Create an auto issuance.
		 *
		 * @throws Exception Exception containing the error message.
		 *
		 * @param string $data Data for the action.
		 * @return mixed result.
		 */
		public static function add_auto_issuance( $data ) {
			self::verify_nonce( $data['nonce'], 'add_auto_issuance' );

			$auto_issuance_id = Accredible_Learndash_Model_Auto_Issuance::insert( $data['accredible_learndash_object'] );

			if ( $auto_issuance_id < 1 ) {
				throw new Exception( 'Failed to save auto issuance. Please try again later.' );
			}

			return array(
				'message' => 'Saved auto issuance successfully.',
				'id'      => $auto_issuance_id,
				'nonce'   => wp_create_nonce( 'edit_auto_issuance' . $auto_issuance_id ),
			);
		}

		/**
		 * Edit an auto issuance.
		 *
		 * @throws Exception Exception containing the error message.
		 *
		 * @param string $data Data for the action.
		 * @return string result.
		 */
		public static function edit_auto_issuance( $data ) {
			self::verify_nonce( $data['nonce'], 'edit_auto_issuance' . $data['id'] );

			$auto_issuance_params = $data['accredible_learndash_object'];
			$result               = Accredible_Learndash_Model_Auto_Issuance::update( $data['id'], $auto_issuance_params );

			if ( false === $result ) {
				throw new Exception( 'Failed to save auto issuance. Please try again later.' );
			}

			return 'Saved auto issuance successfully.';
		}

		/**
		 * Delete an auto issuance.
		 *
		 * @throws Exception Exception containing the error message.
		 *
		 * @param string $data Data for the action.
		 * @return string result.
		 */
		public static function delete_auto_issuance( $data ) {
			self::verify_nonce( $data['nonce'], 'delete_auto_issuance' . $data['id'] );
			$result = Accredible_Learndash_Model_Auto_Issuance::delete( $data['id'] );

			if ( false === $result ) {
				throw new Exception( 'Failed to delete auto issuance. Please try again later.' );
			}

			return 'Deleted auto issuance successfully.';
		}

		/**
		 * Sanitize a string parameter.
		 *
		 * @param string $key The key of the parameter.
		 */
		private static function sanitize_parameter( $key ) {
			return isset( $_REQUEST[ $key ] ) ? sanitize_key( wp_unslash( $_REQUEST[ $key ] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Sanitize accredible learndash object.
		 *
		 * @param string $action The name of the action.
		 */
		private static function sanitize_object( $action ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! isset( $_REQUEST['accredible_learndash_object'] ) ) {
				return array();
			}

			switch ( $action ) {
				case 'add_auto_issuance':
				case 'edit_auto_issuance':
					$object = array(
						'kind'                => self::sanitize_object_field( 'kind' ),
						'post_id'             => self::sanitize_object_field( 'post_id' ),
						'accredible_group_id' => self::sanitize_object_field( 'accredible_group_id' ),
					);
					break;
				default:
					$object = array();
			}
			return $object;
		}

		/**
		 * Sanitize accredible learndash object field.
		 *
		 * @param string $field The name of the field.
		 */
		private static function sanitize_object_field( $field ) {
			return isset( $_REQUEST['accredible_learndash_object'][ $field ] ) ? sanitize_text_field( wp_unslash( $_REQUEST['accredible_learndash_object'][ $field ] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Verify WP nonce for the action.
		 *
		 * @param string $nonce Nonce value that was used for verification.
		 * @param string $action Should give context to what is taking place and be the same when nonce was created.
		 */
		private static function verify_nonce( $nonce, $action ) {
			if ( ! ( isset( $nonce ) && wp_verify_nonce( $nonce, $action ) ) ) {
				wp_die( 'Invalid nonce.' );
			};
		}

		/**
		 * Redirect to the page.
		 *
		 * @param string $redirect_url Redirect URL.
		 */
		private static function redirect_to( $redirect_url ) {
			// You cannot use `wp_redirect` at the `admin_menu` Action Hook callback
			// since http headers were already sent to the browser.
			echo '<p>Processing...</p>';
			print( "<script>window.location.href='" . esc_url_raw( $redirect_url ) . "'</script>" );
		}
	}
endif;
