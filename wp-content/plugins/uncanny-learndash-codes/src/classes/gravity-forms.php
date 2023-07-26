<?php

namespace uncanny_learndash_codes;

/**
 * Class GravityForms
 * @package uncanny_learndash_codes
 */
class GravityForms extends Config {
	private static $coupon_id;
	private $redirect_to;
	private static $form_id;

	/**
	 * GravityForms constructor.
	 */
	function __construct() {
		if ( class_exists( 'GFFormsModel' ) ) {
			if ( is_multisite() ) {
				self::$form_id = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms, 0 );
			} else {
				self::$form_id = get_option( Config::$uncanny_codes_settings_gravity_forms, 0 );
			}

			if ( 0 === self::$form_id && ( isset( $_REQUEST['page'] ) && 'uncanny-learndash-codes' === $_REQUEST['page'] ) ) {
				//TODO: MAY BE ADD ADMIN NOTICE?
				//header( "Location:" . admin_url( 'admin.php?page=uncanny-learndash-codes-settings' ) );
				//exit;
			}

			//Add Code Redemption Field to Gravity Forms
			add_filter( 'gform_pre_render', array( __CLASS__, 'add_code_field' ) );
			add_filter( 'gform_pre_validation', array( __CLASS__, 'add_code_field' ) );
			add_filter( 'gform_pre_submission_filter', array( __CLASS__, 'add_code_field' ) );

			//Add Custom Validation for Code Redemption Field
			add_filter( 'gform_validation', array( __CLASS__, 'custom_validation' ) );

			add_filter( 'manage_users_columns', array( __CLASS__, 'manage_users_columns' ) );
			add_action( 'manage_users_custom_column', array( __CLASS__, 'manage_users_custom_column' ), 10, 3 );

			//Registration completed
			add_action( 'user_register', array( __CLASS__, 'gf_user_register' ), 15 );
			add_action( 'gform_activate_user', array( __CLASS__, 'gf_user_register_activation' ), 15 , 3);
		}
	}

	/**
	 * @param $columns
	 *
	 * @return mixed
	 */
	public static function manage_users_columns( $columns ) {
		$columns['user_prefix'] = __('Prefix', 'uncanny-learndash-codes');

		return $columns;
	}

	/**
	 * @param $value
	 * @param $column_name
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public static function manage_users_custom_column( $value, $column_name, $user_id ) {
		$user_prefix = get_user_meta( $user_id, Config::$uncanny_codes_user_prefix_meta, true );

		if ( 'user_prefix' === $column_name ) {
			return $user_prefix;
		}

		return $value;
	}

	/**
	 * @param $validation_result
	 *
	 * @return mixed
	 */
	public static function custom_validation( $validation_result ) {
		$form = $validation_result['form'];
		if ( intval( self::$form_id ) === intval( $form['id'] ) ) {
			$code_redemption = rgpost( 'input_99' );
			if ( ! empty( $code_redemption ) ) {
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
								if ( 99 === $field->id ) {
									$field->failed_validation  = true;
									$field->validation_message = Config::$redeemed_maximum;
									break;
								}
							}
						} elseif ( 'invalid' === $coupon_id['error'] ) {
							foreach ( $form['fields'] as &$field ) {

								//NOTE: replace 1 with the field you would like to validate
								if ( 99 === $field->id ) {
									$field->failed_validation  = true;
									$field->validation_message = Config::$invalid_code;
									break;
								}
							}
						} elseif ( 'expired' === $coupon_id['error'] ) {
							foreach ( $form['fields'] as &$field ) {

								//NOTE: replace 1 with the field you would like to validate
								if ( 99 === $field->id ) {
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
						if ( 99 === $field->id ) {
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
						if ( 99 === $field->id ) {
							$field->failed_validation  = true;
							$field->validation_message = Config::$invalid_code;
							break;
						}
					}

					$validation_result['form'] = $form;

					return $validation_result;
				}
			}
		}
		$validation_result['form'] = $form;

		return $validation_result;

	}

	/**
	 * @param $form
	 *
	 * @return mixed
	 */
	public static function add_code_field( $form ) {
		if ( intval( self::$form_id ) === intval( $form['id'] ) ) {
			$add_custom_field = TRUE;
			if ( $form['fields'] ) {
				foreach ( $form['fields'] as $field ) {
					if ( 'uncanny_enrollment_code' === $field->type ) {
						$add_custom_field = FALSE;
						break;
					}
				}
			}
			if ( $add_custom_field ) {
				if ( is_multisite() ) {
					$mandatory   = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_mandatory, FALSE );
					$label       = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_label, 'Enter Registration Code' );
					$error       = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_error, 'This field is mandatory' );
					$placeholder = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_placeholder, 'Enter Code' );
				} else {
					$mandatory   = get_option( Config::$uncanny_codes_settings_gravity_forms_mandatory, FALSE );
					$label       = get_option( Config::$uncanny_codes_settings_gravity_forms_label, 'Enter Registration Code' );
					$error       = get_option( Config::$uncanny_codes_settings_gravity_forms_error, 'This field is mandatory' );
					$placeholder = get_option( Config::$uncanny_codes_settings_gravity_forms_placeholder, 'Enter Code' );
				}
				
				if ( 1 === intval( $mandatory ) ) {
					$mandatory = TRUE;
				}
				
				$props       = [
					'id'           => 99,
					'label'        => $label,
					'adminLabel'   => $label,
					'type'         => 'text',
					'size'         => 'large',
					'isRequired'   => $mandatory,
					'placeholder'  => $placeholder,
					'noDuplicates' => FALSE,
					'formId'       => $form['id'],
					'pageNumber'   => 1,
					'errorMessage' => $error,
				];
				$form_fields = [];
				
				foreach ( $form['fields'] as $key => $value ) {
					$form_fields[] = $value['id'];
				}
				if ( ! in_array( 99, $form_fields, TRUE ) ) {
					$field = \GF_Fields::create( $props );
					array_push( $form['fields'], $field );
				}
			}
		}
		
		return $form;
		
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
		$code_redemption = gform_get_meta( $entry_meta['entry_id'], 99 );
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