<?php

namespace uncanny_learndash_groups;


/**
 * Class WPForms
 * @package uncanny_learndash_groups
 */
class WPForms {

	/**
	 * WPForms constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 999 );
	}

	/**
	 * Add hookable actions of WPForms
	 */
	public function plugins_loaded() {
		if ( ! class_exists( 'WPForms_Field' ) ) {
			return;
		}

		require_once Utilities::get_include( 'wpforms/class-wpforms-code-field.php' );
		//add_action( 'wpforms_process_validate_uncanny_code', array( $this, 'wpforms_check_if_code_valid' ), 100, 3 );
		add_action( 'wpforms_process_validate_ulgm_code', array( $this, 'wpforms_check_if_code_valid' ), 100, 3 );
		add_action( 'wpforms_user_registered', array( $this, 'wpforms_user_registered' ), 100, 3 );
		add_action( 'wpforms_process_complete', array( $this, 'wpforms_process_complete' ), 1000, 4 );

		add_action( 'admin_init', array( $this, 'migrate_code' ) );
	}

	/**
	 *
	 */
	public function migrate_code() {
		if ( 'no' === get_option( 'ulgm_wp_forms_migrated', 'no' ) ) {
			$forms = get_posts( array( 'post_type' => 'wpforms', 'posts_per_page' => 999 ) );
			if ( $forms ) {
				foreach ( $forms as $form ) {
					$content = $form->post_content;
					if ( 0 !== preg_match( '/uncanny_code/', $content ) ) {
						$content            = preg_replace( '/uncanny_code/', 'ulgm_code', $content );
						$form->post_content = $content;
					}
					wp_update_post( $form, false, false );
				}
			}
			update_option( 'ulgm_wp_forms_migrated', 'yes', false );
		}
	}

	/**
	 * @param $field_id
	 * @param $field_value
	 * @param $form_data
	 */
	public function wpforms_check_if_code_valid( $field_id, $field_value, $form_data ) {


		if ( empty( $field_value ) ) {
			wpforms()->process->errors[ $form_data['id'] ][ $field_id ] = esc_html__( 'Please enter valid key.', 'uncanny-learndash-groups' );

			return;
		}
		$code_details = SharedFunctions::is_key_available( $field_value );
		$results      = SharedFunctions::validate_key_results( $code_details );
		if ( false === $results['is_valid'] ) {
			$message                                                    = $results['message'];
			wpforms()->process->errors[ $form_data['id'] ][ $field_id ] = $message;
		}
	}

	/**
	 * Run when user is registered through WPForms
	 *
	 * @param $user_id
	 * @param $fields
	 * @param $form_data
	 * @param $userdata
	 */
	public function wpforms_user_registered( $user_id, $fields, $form_data ) {
		if ( $form_data && isset( $form_data['fields'] ) ) {
			foreach ( $form_data['fields'] as $k => $v ) {

				if ( 'ulgm_code' !== (string) $v['type'] ) {
					continue;
				}

				$field_id = absint( $v['id'] );

				if ( isset( $fields[ $field_id ] ) ) {
					$value = $fields[ $field_id ]['value'];
					// Check if existing coupon or not!
					$code_details = SharedFunctions::is_key_available( $value );
					if ( 'success' === $code_details['result'] ) {
						update_user_meta( $user_id, '_ulgm_code_used', $value );
						$result = SharedFunctions::set_user_to_code( $user_id, $value, SharedFunctions::$not_started_status );
						if ( $result ) {
							SharedFunctions::set_user_to_group( $user_id, $code_details['ld_group_id'] );
						}
					}
				}
			}
		}
	}

	/**
	 * Run when the entry process is completed by WPForms
	 *
	 * @param $fields
	 * @param $entry
	 * @param $form_data
	 * @param $entry_id
	 */
	public function wpforms_process_complete( $fields, $entry, $form_data, $entry_id ) {
		if ( ! is_user_logged_in() ) {
			return;
		}
		$user_id = wp_get_current_user()->ID;
		if ( $form_data && isset( $form_data['fields'] ) ) {
			foreach ( $form_data['fields'] as $k => $v ) {

				if ( 'ulgm_code' !== (string) $v['type'] ) {
					continue;
				}

				$field_id = absint( $v['id'] );

				if ( isset( $fields[ $field_id ] ) ) {
					$value = $fields[ $field_id ]['value'];
					// Check if existing coupon or not!
					$code_details = SharedFunctions::is_key_available( $value );
					if ( 'success' === $code_details['result'] ) {
						update_user_meta( $user_id, '_ulgm_code_used', $value );
						$result = SharedFunctions::set_user_to_code( $user_id, $value, SharedFunctions::$not_started_status );
						if ( $result ) {
							SharedFunctions::set_user_to_group( $user_id, $code_details['ld_group_id'] );
						}
					}
				}
			}
		}
	}
}
