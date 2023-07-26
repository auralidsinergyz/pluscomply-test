<?php

namespace uncanny_learndash_codes;

use uncanny_learndash_codes\Database;
use uncanny_learndash_codes\LearnDash;
/**
 * Class GravtiyFormsCodeField
 * @package uncanny_learndash_codes
 */
class GravityFormsCodeField extends Config {
	public static $form_id;
	public static $coupon_id;

	/**
	 * GravityFormsCodeField constructor.
	 */
	public function __construct() {
		add_action( 'gform_loaded', array( $this, 'load' ), 5 );
		add_action( 'gform_loaded', array( $this, 'handle_gravity_forms' ), 20 );
	}

	/**
	 *
	 */
	public function load() {

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		require_once( self::get_include( 'class-gf-code-field-add-on.php' ) );

		\GFAddOn::register( 'GFCodeFieldAddOnCodes' );
	}

	public function handle_gravity_forms() {
		//Add Custom Validation for Code Redemption Field
		add_filter( 'gform_validation', array( $this, 'custom_validation' ) );
		//Registration completed
		add_action( 'user_register', array( __CLASS__, 'gf_user_register' ), 15 );
		add_action( 'gform_activate_user', array( __CLASS__, 'gf_user_register_activation' ), 15 , 3);
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
				if ( 'uncanny_enrollment_code' === $field->type ) {
					$field_id          = $field->id;
					$code_redeem_field = true;
					$code_redemption   = rgpost( 'input_' . $field->id );
					break;
				}
			}
		}
		
		// ------------------------------------------ //
		
		if ( $code_redeem_field && ! empty( $code_redemption ) ) {
				//Check if existing coupon or not!
				$coupon_id  = Database::is_coupon_available( $code_redemption );
				$is_paid    = Database::is_coupon_paid( $code_redemption );
				$is_default = Database::is_default_code( $code_redemption );
				if ( is_array( $coupon_id ) ) {
					if ( 'failed' === $coupon_id['result'] ) {
						$validation_result['is_valid'] = false;
						if ( 'max' === $coupon_id['error'] ) {
							foreach ( $form['fields'] as &$field ) {
								
								//NOTE: replace 1 with the field you would like to validate
								if ( $field_id === $field->id ) {
									$field->failed_validation  = true;
									$field->validation_message = Config::$redeemed_maximum;
									break;
								}
							}
						} elseif ( 'invalid' === $coupon_id['error'] ) {
							foreach ( $form['fields'] as &$field ) {
								
								//NOTE: replace 1 with the field you would like to validate
								if ( $field_id === $field->id ) {
									$field->failed_validation  = true;
									$field->validation_message = Config::$invalid_code;
									break;
								}
							}
						} elseif ( 'expired' === $coupon_id['error'] ) {
							foreach ( $form['fields'] as &$field ) {
								
								//NOTE: replace 1 with the field you would like to validate
								if ( $field_id === $field->id ) {
									$field->failed_validation  = true;
									$field->validation_message = Config::$expired_code;
									break;
								}
							}
						}
						
						$validation_result['form'] = $form;
						
						return $validation_result;
					}
				} elseif ( ! $is_paid && ! $is_default ) {
					foreach ( $form['fields'] as &$field ) {
						
						//NOTE: replace 1 with the field you would like to validate
						if ( $field_id === $field->id ) {
							$field->failed_validation  = true;
							$field->validation_message = Config::$unpaid_error;
							break;
						}
					}
					$validation_result['form'] = $form;
					
					return $validation_result;
				} elseif ( is_numeric( $coupon_id ) ) {
					self::$coupon_id = intval( $coupon_id );
				} else {
					self::$coupon_id = null;
				}
				
				
				if ( ! intval( self::$coupon_id ) ) {
					$validation_result['is_valid'] = false;
					foreach ( $form['fields'] as &$field ) {
						
						//NOTE: replace 1 with the field you would like to validate
						if ( $field_id === $field->id ) {
							$field->failed_validation  = true;
							$field->validation_message = Config::$invalid_code;
							break;
						}
					}
					
					$validation_result['form'] = $form;
					
					return $validation_result;
				}
			}
		
		$validation_result['form'] = $form;
		
		return $validation_result;
		// ------------------------------------------ //

	}
	
	
	/**
	 * @param $user_id
	 */
	public static function gf_user_register( $user_id ) {
		if ( intval( self::$coupon_id ) && isset( $_POST['gform_submit'] ) ) {
			update_user_meta( $user_id, Config::$uncanny_codes_tracking, __('Gravity Forms', 'uncanny-learndash-codes') );
			$result = Database::set_user_to_coupon( $user_id, self::$coupon_id );
			LearnDash::set_user_to_course_or_group( $user_id, $result );
		}
	}
	
	/**
	 * @param $user_id
	 * @param $user_data
	 * @param $entry_meta
	 */
	public static function gf_user_register_activation( $user_id, $user_data, $entry_meta ) {
		$entry = \GFAPI::get_entry($entry_meta['entry_id']);
		$form = \GFAPI::get_form($entry['form_id']);
		$field_id          = 0;
		if ( $form['fields'] ) {
			foreach ( $form['fields'] as $field ) {
				if ( 'uncanny_enrollment_code' === $field->type ) {
					$field_id          = $field->id;
					break;
				}
			}
		}
		$code_redemption = gform_get_meta( $entry_meta['entry_id'], $field_id );
		
		if( false !== $code_redemption ){
			$coupon_id  = Database::is_coupon_available( $code_redemption );
			if ( intval( $coupon_id ) ) {
				update_user_meta( $user_id, Config::$uncanny_codes_tracking, __('Gravity Forms', 'uncanny-learndash-codes') );
				$result = Database::set_user_to_coupon( $user_id, $coupon_id );
				LearnDash::set_user_to_course_or_group( $user_id, $result );
			}
		}
	}
}