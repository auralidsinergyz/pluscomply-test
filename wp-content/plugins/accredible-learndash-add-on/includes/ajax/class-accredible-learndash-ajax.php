<?php
/**
 * Accredible LearnDash Add-on ajax
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-issuer-helper.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-auto-issuance-list-helper.php';
require_once plugin_dir_path( __DIR__ ) . 'class-accredible-learndash-admin-action-handler.php';
require_once plugin_dir_path( __DIR__ ) . '/learndash/class-accredible-learndash-learndash-utils.php';

if ( ! class_exists( 'Accredible_Learndash_Ajax' ) ) :
	/**
	 * Accredible LearnDash Add-on ajax class.
	 * All methods in this class are only called via ajax.
	 */
	class Accredible_Learndash_Ajax {
		/**
		 * Get group options.
		 */
		public static function search_groups() {
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
		}

		/**
		 * Get group by id.
		 */
		public static function get_group() {
			$response = array();

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_REQUEST['group_id'] ) && ! empty( $_REQUEST['group_id'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$group_id   = sanitize_text_field( wp_unslash( $_REQUEST['group_id'] ) );
				$api_client = new Accredible_Learndash_Api_V1_Client();
				$response   = $api_client->get_group( $group_id );
			}

			if ( ! isset( $response['errors'] ) ) {
				$group = array(
					'id'   => $response['group']['id'],
					'name' => $response['group']['name'],
				);

				wp_send_json_success( $group );
			} else {
				wp_send_json_error();
			}
		}

		/**
		 * Get lesson options.
		 */
		public static function get_lessons() {
			$course_id = self::get_request_value( 'course_id', null );

			if ( isset( $course_id ) ) {
				$utils   = new Accredible_Learndash_Learndash_Utils();
				$lessons = $utils->get_lesson_options( $course_id );

				wp_send_json_success( $lessons );
			} else {
				wp_send_json_error();
			}
		}

		/**
		 * Get issuer details html.
		 */
		public static function load_issuer_html() {
			$issuer     = null;
			$api_client = new Accredible_Learndash_Api_V1_Client();
			$response   = $api_client->organization_search();

			if ( ! isset( $response['errors'] ) ) {
				$issuer = $response['issuer'];
			}

			// Capture html from display_issuer_info.
			ob_start();
			Accredible_Learndash_Issuer_Helper::display_issuer_info( $issuer );
			$issuer_html = ob_get_clean();

			wp_send_json_success( $issuer_html );
		}

		/**
		 * Load auto issuances
		 */
		public static function load_auto_issuance_list_html() {
			$current_page = self::get_request_value( 'page_num', 1 );
			$page_size    = 20;

			$page_results = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results(
				$current_page,
				$page_size
			);

			// Capture html from display_auto_issuance_list_info.
			ob_start();
			Accredible_Learndash_Auto_Issuance_List_Helper::display_auto_issuance_list_info( $page_results, $current_page, $page_size );
			$auto_issuance_list_html = ob_get_clean();

			wp_send_json_success( $auto_issuance_list_html );
		}

		/**
		 * Triggers the appropriate action in Accredible_Learndash_Admin_Action_Handler
		 */
		public static function handle_auto_issuance_action() {
			try {
				$results = Accredible_Learndash_Admin_Action_Handler::call();
				wp_send_json_success( $results );
			} catch ( \Exception $wp_exception ) {
				wp_send_json_error( $wp_exception->getMessage() );
			}
		}

		/**
		 * Get page html.
		 */
		public static function load_issuance_form_html() {
			$accredible_learndash_issuance_id           = self::get_request_value( 'id', null );
			$accredible_learndash_issuance_current_page = self::get_request_value( 'page_num', null );

			// Capture html from page.
			ob_start();

			require plugin_dir_path( __DIR__ ) . 'templates/admin-auto-issuance-form.php';

			$page_html = ob_get_clean();

			wp_send_json_success( $page_html );
			wp_die();
		}

		/**
		 * Returns a resolved $_REQUEST value
		 *
		 * @param string $key the key to fetch the value.
		 * @param mixed  $default_value default value to return.
		 *
		 * @return mixed
		 */
		public static function get_request_value( $key, $default_value ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_REQUEST[ $key ] ) && ! empty( $_REQUEST[ $key ] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) );
			} else {
				return $default_value;
			}
		}
	}
endif;
