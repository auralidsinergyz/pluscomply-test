<?php

namespace uncanny_learndash_groups;

/**
 * Class GravtiyFormsCodeField
 *
 * @package uncanny_learndash_groups
 */
class GravityFormsCodeField {
	/**
	 * @var
	 */
	public static $form_id;
	/**
	 * @var
	 */
	public static $code_details;

	/**
	 * GravityFormsCodeField constructor.
	 */
	public function __construct() {
		add_action( 'gform_loaded', array( $this, 'load' ), 5 );
		add_action( 'gform_loaded', array( $this, 'handle_gravity_forms' ), 20 );

		add_action( 'admin_init', array( $this, 'migrate_code' ) );
	}

	/**
	 *
	 */
	public function migrate_code() {
		if ( 'no' === get_option( 'ulgm_gforms_migrated', 'no' ) && class_exists( '\GFCommon' ) ) {
			global $wpdb;
			$forms = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gf_form_meta WHERE display_meta LIKE '%uncanny_code%'" );
			if ( $forms ) {
				foreach ( $forms as $form ) {
					$content = $form->display_meta;
					if ( 0 !== preg_match( '/uncanny_code/', $content ) ) {
						$content = preg_replace( '/uncanny_code/', 'ulgm_code', $content );
						$form_id = $form->form_id;
						$wpdb->query( "UPDATE {$wpdb->prefix}gf_form_meta SET display_meta = '$content' WHERE form_id = $form_id" );
					}
				}
			}
			update_option( 'ulgm_gforms_migrated', 'yes', false );
		}
	}

	/**
	 *
	 */
	public function load() {

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		require_once Utilities::get_include( 'gravity-forms/class-gf-code-field-add-on.php' );

		\GFAddOn::register( 'GFCodeFieldAddOn' );
	}

	/**
	 *
	 */
	public function handle_gravity_forms() {
		//Add Custom Validation for Code Redemption Field
		add_filter( 'gform_validation', array( $this, 'custom_validation' ) );
		//Registration completed
		add_action( 'user_register', array( $this, 'gf_user_register' ), 15 );
		add_action( 'gform_activate_user', array( $this, 'gf_user_register_activation' ), 15, 3 );
		add_action( 'gform_after_submission', array( $this, 'gform_after_submission_func' ), 99, 2 );
	}

	/**
	 * @param $validation_result
	 *
	 * @return mixed
	 */
	public function custom_validation( $validation_result ) {
		$form              = $validation_result['form'];
		$code_redeem_field = false;
		$field_id          = 0;

		if ( $form['fields'] ) {
			foreach ( $form['fields'] as $field ) {
				if ( 'ulgm_code' === $field->type ) {
					$field_id          = $field->id;
					$code_redeem_field = true;
					$code_redeem       = rgpost( 'input_' . $field->id );
					break;
				}
			}
		}
		if ( $code_redeem_field && ! empty( $code_redeem ) ) {
			$code_details = SharedFunctions::is_key_available( $code_redeem );
			$results      = SharedFunctions::validate_key_results( $code_details );
			if ( false === $results['is_valid'] ) {
				$validation_result['is_valid'] = false;
				$message                       = $results['message'];
				foreach ( $form['fields'] as &$field ) {
					if ( $field_id === $field->id ) {
						$field->failed_validation  = true;
						$field->validation_message = $message;
						break;
					}
				}
			} elseif ( true === $results['is_valid'] ) {
				self::$code_details = $code_details;
			} else {
				self::$code_details = null;
			}
		}
		$validation_result['form'] = $form;

		return $validation_result;

	}


	/**
	 * @param $user_id
	 */
	public function gf_user_register( $user_id ) {
		if ( is_array( self::$code_details ) && 'success' === self::$code_details['result'] ) {
			$code_registration = self::$code_details['key'];
			update_user_meta( $user_id, '_ulgm_code_used', $code_registration );
			$result = SharedFunctions::set_user_to_code( $user_id, $code_registration, SharedFunctions::$not_started_status );
			if ( $result ) {
				SharedFunctions::set_user_to_group( $user_id, self::$code_details['ld_group_id'] );
			}
		}
	}

	/**
	 * @param $user_id
	 * @param $user_data
	 * @param $entry_meta
	 */
	public static function gf_user_register_activation( $user_id, $user_data, $entry_meta ) {
		$entry    = \GFAPI::get_entry( $entry_meta['entry_id'] );
		$form     = \GFAPI::get_form( $entry['form_id'] );
		$field_id = 0;
		if ( $form['fields'] ) {
			foreach ( $form['fields'] as $field ) {
				if ( 'ulgm_code' === $field->type ) {
					$field_id = $field->id;
					break;
				}
			}
		}
		$code_redemption = gform_get_meta( $entry_meta['entry_id'], $field_id );

		if ( false !== $code_redemption ) {
			$code_details = SharedFunctions::is_key_available( $code_redemption );
			if ( 'success' === $code_details['result'] ) {
				update_user_meta( $user_id, '_ulgm_code_used', $code_redemption );
				$result = SharedFunctions::set_user_to_code( $user_id, $code_redemption, SharedFunctions::$not_started_status );
				if ( $result ) {
					SharedFunctions::set_user_to_group( $user_id, $code_details['ld_group_id'] );
				}
			}
		}
	}

	/**
	 * @param $entry
	 * @param $form
	 */
	public function gform_after_submission_func( $entry, $form ) {
		// only run for logged in users
		if ( ! is_user_logged_in() ) {
			return;
		}
		$code_redeem_field = false;
		$code_redemption   = null;
		foreach ( $form['fields'] as $field ) {
			if ( 'ulgm_code' !== $field->type ) {
				continue;
			}
			$code_redeem_field = true;
			$code_redemption   = rgar( $entry, (string) $field->id );
			break;
		}
		if ( false === $code_redeem_field ) {
			return;
		}
		if ( empty( $code_redemption ) ) {
			return;
		}
		$user_id      = wp_get_current_user()->ID;
		$code_details = SharedFunctions::is_key_available( $code_redemption );

		if ( true !== apply_filters( 'ulgm_redeem_code_for_current_logged_in_user', true, $entry, $form, $user_id, $code_redemption, $code_details ) ) {
			return;
		}

		if ( 'success' === $code_details['result'] ) {
			update_user_meta( $user_id, '_ulgm_code_used', $code_redemption );
			$result = SharedFunctions::set_user_to_code( $user_id, $code_redemption, SharedFunctions::$not_started_status );
			if ( $result ) {
				SharedFunctions::set_user_to_group( $user_id, $code_details['ld_group_id'] );
			}
		}
	}
}
