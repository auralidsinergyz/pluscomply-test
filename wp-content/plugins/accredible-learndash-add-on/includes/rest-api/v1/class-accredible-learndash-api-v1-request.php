<?php
/**
 * Accredible LearnDash Add-on API v1 HTTP request class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Api_V1_Request' ) ) :
	/**
	 * Accredible LearnDash Add-on API v1 HTTP request class
	 */
	class Accredible_Learndash_Api_V1_Request {
		/**
		 * Accredible_Learndash_Api_V1_Request constructor.
		 *
		 * @param string $base_url The API endpoint base URL.
		 * @param string $api_key The API key.
		 */
		public function __construct( $base_url, $api_key ) {
			$this->base_url = $base_url;
			$this->headers  = array(
				'Authorization'          => 'Token ' . $api_key,
				'Content-Type'           => 'application/json',
				'Accredible-Integration' => 'Learndash',
			);
		}

		/**
		 * Make a get request.
		 *
		 * @param string $path The API endpoint path.
		 */
		public function get( $path ) {
			$res = wp_remote_get(
				$this->base_url . $path,
				$this->args()
			);
			return $this->parse_response_body( $res );
		}

		/**
		 * Make a post request.
		 *
		 * @param string $path The API endpoint path.
		 * @param array  $body The POST request body.
		 */
		public function post( $path, $body ) {
			$res = wp_remote_post(
				$this->base_url . $path,
				$this->args(
					array( 'body' => wp_json_encode( $body ) )
				)
			);
			return $this->parse_response_body( $res );
		}

		/**
		 * Return request args with default options.
		 *
		 * @param array $custom_args Custom request args.
		 */
		private function args( $custom_args = array() ) {
			return $custom_args + array( 'headers' => $this->headers );
		}

		/**
		 * Parse the response body.
		 *
		 * @param array $response The response from `wp_remote_get` or `wp_remote_post`.
		 */
		private function parse_response_body( $response ) {
			if ( is_wp_error( $response ) ) {
				// Unexpected WP_Error such as `http_request_failed`.
				return array( 'errors' => $response->get_error_code() );
			} else {
				$body        = wp_remote_retrieve_body( $response );
				$parsed_body = json_decode( $body, true );
				$status_code = wp_remote_retrieve_response_code( $response );
				if ( $status_code < 400 ) {
					return $parsed_body;
				}

				// Always include the `errors` attribute when the request fails.
				if ( empty( $parsed_body ) ) {
					return array( 'errors' => $status_code . ': ' . $body );
				}
				if ( empty( $parsed_body['errors'] ) ) {
					$parsed_body['errors'] = $status_code;
				}
				return $parsed_body;
			}
		}
	}
endif;
