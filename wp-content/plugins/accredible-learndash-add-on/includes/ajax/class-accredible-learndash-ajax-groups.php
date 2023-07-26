<?php
/**
 * Accredible LearnDash Add-on ajax groups
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Ajax_Groups' ) ) :
	/**
	 * Accredible LearnDash Add-on ajax groups class
	 */
	class Accredible_Learndash_Ajax_Groups {
		/**
		 * Get group options. This method is only called via ajax.
		 */
		public static function search() {
			$groups   = array();
			$response = array();

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_REQUEST['search_term'] ) && ! empty( $_REQUEST['search_term'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$search_term = sanitize_text_field( wp_unslash( $_REQUEST['search_term'] ) );
				$api_client  = new Accredible_Learndash_Api_V1_Client();
				$response    = $api_client->search_groups( $search_term );
			}

			if ( ! isset( $response['errors'] ) ) {
				foreach ( $response['groups'] as $value ) {
					array_push(
						$groups,
						array(
							'value' => $value['id'],
							'label' => $value['name'],
						)
					);
				}

				wp_send_json_success( $groups );
			} else {
				wp_send_json_error();
			}

			wp_die();
		}
	}
endif;
