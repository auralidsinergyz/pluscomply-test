<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class GravityFormsSupport
 * @package uncanny_learndash_groups
 */
class GravityFormsSupport {
	public static $uncanny_codes_settings_gravity_forms = 'ulgm-setting-form-id';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms_mandatory = 'ulgm-setting-form-field-mandatory';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms_label = 'ulgm-setting-form-field-label';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms_error = 'ulgm-setting-form-field-error';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms_placeholder = 'ulgm-setting-form-field-placeholder';

	public static $form_id;
	public static $code_details;

	/**
	 * GravityFormsSupport constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'admin_init', array( $this, 'save_form_settings' ) );
	}

	/**
	 *
	 */
	public function plugins_loaded() {
		//add_action( 'admin_menu', array( $this, 'create_submenu' ), 40 );
		if ( ! class_exists( 'GF_User_Registration' ) ) {
			return;
		}
		self::$form_id = get_option( self::$uncanny_codes_settings_gravity_forms, 0 );

		//Add Code Redemption Field to Gravity Forms
		add_filter( 'gform_pre_render', array( $this, 'add_code_field' ) );
		add_filter( 'gform_pre_validation', array( $this, 'add_code_field' ) );
		add_filter( 'gform_pre_submission_filter', array( $this, 'add_code_field' ) );

		//Add Custom Validation for Code Redemption Field
		add_filter( 'gform_validation', array( $this, 'custom_validation' ) );
		//Registration completed
		add_action( 'user_register', array( $this, 'gf_user_register' ), 15 );
		add_action( 'gform_activate_user', array( __CLASS__, 'gf_user_register_activation' ), 15, 3 );
	}

	/**
	 *
	 */
	public function create_submenu() {
		$capability  = 'manage_options';
		$parent_slug = 'uncanny-groups-create-group';
		add_submenu_page( $parent_slug, __( 'Gravity Forms', 'uncanny-learndash-groups' ), __( 'Gravity Forms', 'uncanny-learndash-groups' ), $capability, 'uncanny-groups-gravity-forms', array(
				$this,
				'gravity_forms_support',
		) );

	}

	/**
	 *
	 */
	public function save_form_settings() {
		if ( ( ! empty( $_POST ) && ulgm_filter_has_var( '_gfwpnonce', INPUT_POST ) ) && wp_verify_nonce( ulgm_filter_input( '_gfwpnonce', INPUT_POST ), 'uncanny-owl' ) ) {
			if ( ulgm_filter_has_var( 'registration_form', INPUT_POST ) && is_numeric( intval( ulgm_filter_input( 'registration_form', INPUT_POST ) ) ) ) {
				update_option( self::$uncanny_codes_settings_gravity_forms, intval( ulgm_filter_input( 'registration_form', INPUT_POST ) ) );
			}

			if ( ulgm_filter_has_var( 'registration-field-mandatory', INPUT_POST ) && is_numeric( intval( ulgm_filter_input( 'registration-field-mandatory', INPUT_POST ) ) ) ) {
				update_option( self::$uncanny_codes_settings_gravity_forms_mandatory, intval( ulgm_filter_input( 'registration-field-mandatory', INPUT_POST ) ) );
			} else {
				delete_option( self::$uncanny_codes_settings_gravity_forms_mandatory );
			}

			if ( ulgm_filter_has_var( 'gravity_form_field_label', INPUT_POST ) && ! empty( ulgm_filter_input( 'gravity_form_field_label', INPUT_POST ) ) ) {
				update_option( self::$uncanny_codes_settings_gravity_forms_label, esc_html( ulgm_filter_input( 'gravity_form_field_label', INPUT_POST ) ) );
			}

			if ( ulgm_filter_has_var( 'gravity_form_field_error_message', INPUT_POST ) && ! empty( ulgm_filter_input( 'gravity_form_field_error_message', INPUT_POST ) ) ) {
				update_option( self::$uncanny_codes_settings_gravity_forms_error, esc_html( ulgm_filter_input( 'gravity_form_field_error_message', INPUT_POST ) ) );
			}

			if ( ulgm_filter_has_var( 'gravity_form_field_placeholder', INPUT_POST ) && ! empty( ulgm_filter_input( 'gravity_form_field_placeholder', INPUT_POST ) ) ) {
				update_option( self::$uncanny_codes_settings_gravity_forms_placeholder, esc_html( ulgm_filter_input( 'gravity_form_field_placeholder', INPUT_POST ) ) );
			}

			wp_safe_redirect( admin_url( 'admin.php' ) . '?page=uncanny-groups-gravity-forms&saved=true&redirect_nonce=' . wp_create_nonce( time() ) );
			exit;
		}
	}

	/**
	 *
	 */
	public function gravity_forms_support() {
		?>
		<div class="wrap">
			<div class="ulgm">
				<div class="uo-ulgm-admin form-table group-management-form">
					<?php

					// Add admin header and tabs
					$tab_active = 'uncanny-groups-gravity-forms';
					include Utilities::get_template( 'admin/admin-header.php' );

					?>

					<div class="ulgm-admin-content">
						<!-- Messages -->
						<?php if ( isset( $_REQUEST['saved'] ) ) { ?>
							<div class="updated notice">
								<h4><?php _e( 'Settings saved!', 'uncanny-learndash-groups' ) ?></h4>
							</div>
						<?php } ?>

						<!-- Settings -->
						<div class="uo-admin-section">
							<div class="uo-admin-header">
								<div class="uo-admin-title"><?php _e( 'Gravity Forms registration form', 'uncanny-learndash-groups' ); ?></div>
							</div>
							<div class="uo-admin-block">
								<div id="registration_form_error" class="notice notice-error notice--inside"
									 style="display: none;"><h4></h4></div>

								<form method="post" action="" id="uncanny-learndash-groups-form">

									<input type="hidden" name="_wp_http_referer"
										   value="<?php echo admin_url( 'admin.php?page=uncanny-groups-gravity-forms' ) ?>"/>
									<input type="hidden" name="_gfwpnonce"
										   value="<?php echo wp_create_nonce( 'uncanny-owl' ); ?>"/>

									<?php
									$existing                         = get_option( self::$uncanny_codes_settings_gravity_forms, 0 );
									$gravity_form_field_mandatory     = get_option( self::$uncanny_codes_settings_gravity_forms_mandatory, 0 );
									$gravity_form_field_label         = get_option( self::$uncanny_codes_settings_gravity_forms_label, null );
									$gravity_form_field_error_message = get_option( self::$uncanny_codes_settings_gravity_forms_error, null );
									$gravity_form_field_placeholder   = get_option( self::$uncanny_codes_settings_gravity_forms_placeholder, null );
									$forms                            = \GFFormsModel::get_forms();

									foreach ( $forms as $form ) {
										$results = \GF_User_Registration::get_config( $form->id );
										if ( $results ) {
											//foreach ( $results as $result ) {
											printf( '<div id="form-id-%d" style="display:none" data-register="1"></div>', $form->id );
											//}
										} else {
											printf( '<div id="form-id-%d" style="display:none" data-register="0"></div>', $form->id );
										}
									}

									?>

									<div class="uo-admin-form">

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'Select form', 'uncanny-learndash-groups' ) ?></div>

											<select class="uo-admin-select" name="registration_form"
													id="registration_form">
												<option value="0"><?php _e( 'Forms', 'uncanny-learndash-groups' ) ?></option>

												<?php foreach ( $forms as $form ) { ?>

													<option <?php if ( $form->id === $existing ) {
														echo 'selected="selected"';
													} ?> value="<?php echo esc_attr( $form->id ) ?>"><?php echo esc_html( $form->title ) ?></option>

												<?php } ?>
											</select>
										</div>

										<!-- Separator -->
										<div class="uo-admin-field">
											<div class="uo-admin-separator"></div>
										</div>

										<div class="uo-admin-field">
											<label class="uo-checkbox">
												<input type="checkbox" value="1" name="registration-field-mandatory"
													   id="registration-field-mandatory"<?php if ( 1 === intval( $gravity_form_field_mandatory ) ) {
													echo 'checked="checked"';
												} ?>>
												<div class="uo-checkmark"></div>
												<span class="uo-label">
													<?php _e( 'Make code field mandatory on user registration form.', 'uncanny-learndash-groups' ) ?>
												</span>
											</label>
										</div>

										<!-- Separator -->
										<div class="uo-admin-field">
											<div class="uo-admin-separator"></div>
										</div>

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'User registration field label:', 'uncanny-learndash-groups' ) ?></div>

											<input class="uo-admin-input" type="text"
												   value="<?php if ( null !== $gravity_form_field_label ) {
													   echo esc_html( $gravity_form_field_label );
												   } ?>" name="gravity_form_field_label" id="gravity_form_field_label"
												   placeholder="Enter Enrollment Key"/>
										</div>

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'User registration field error message:', 'uncanny-learndash-groups' ) ?></div>

											<input class="uo-admin-input" type="text"
												   value="<?php if ( null !== $gravity_form_field_error_message ) {
													   echo esc_html( $gravity_form_field_error_message );
												   } ?>" name="gravity_form_field_error_message"
												   id="gravity_form_field_error_message"
												   placeholder="This Field is Mandatory"/>
										</div>

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'User registration field placeholder:', 'uncanny-learndash-groups' ) ?></div>

											<input class="uo-admin-input" type="text"
												   value="<?php if ( null !== $gravity_form_field_placeholder ) {
													   echo esc_html( $gravity_form_field_placeholder );
												   } ?>" name="gravity_form_field_placeholder"
												   id="gravity_form_field_placeholder" placeholder="Enter Code"/>
										</div>

										<!-- Save Changes -->

										<div class="uo-admin-field uo-admin-extra-space">
											<button type="submit"
													class="uo-admin-form-submit"><?php _e( 'Save Changes', 'uncanny-learndash-groups' ) ?></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			jQuery('#uncanny-learndash-groups-form').submit(function (e) {
				if (jQuery('#registration_form').val() === '0') {
				} else {
					if (is_registration_form(jQuery('#registration_form').val())) {
						jQuery('#registration_form_error').hide();
						return true;
					} else {
						jQuery('#registration_form_error h4').html('<?php _e( 'Please Select a Valid Registration Form that has User Registration Feed enabled.', 'uncanny-learndash-groups' ) ?>');
						jQuery('#registration_form_error').show();
						return false;
					}
				}
				//return true;
			});
			jQuery('#registration_form').change(function (e) {
				if (jQuery(this).val() === '0') {
				} else {
					if (is_registration_form(jQuery(this).val())) {
						jQuery('#registration_form_error').hide();
						return true;
					} else {
						jQuery('#registration_form_error h4').html('<?php _e( 'Please Select a Valid Registration Form that has User Registration Feed enabled.', 'uncanny-learndash-groups' ) ?>');
						jQuery('#registration_form_error').show();
						return false;
					}
				}
				return true;
			});

			function is_registration_form(val) {
				if ('0' === jQuery('#form-id-' + val).attr('data-register')) {
					return false;
				} else {
					return true;
				}
			}
		</script>
		<?php

	}

	/**
	 * @param $form
	 *
	 * @return mixed
	 */
	public function add_code_field( $form ) {
		if ( intval( self::$form_id ) === intval( $form['id'] ) ) {
			$add_custom_field = true;
			if ( $form['fields'] ) {
				foreach ( $form['fields'] as $field ) {
					if ( 'uncanny_code' === $field->type ) {
						$add_custom_field = false;
						break;
					}
				}
			}
			if ( $add_custom_field ) {
				$mandatory = get_option( self::$uncanny_codes_settings_gravity_forms_mandatory, false );
				if ( 1 === intval( $mandatory ) ) {
					$mandatory = true;
				}
				$label       = get_option( self::$uncanny_codes_settings_gravity_forms_label, __( 'Enter enrollment key', 'uncanny-learndash-groups' ) );
				$error       = get_option( self::$uncanny_codes_settings_gravity_forms_error, __( 'This field is mandatory', 'uncanny-learndash-groups' ) );
				$placeholder = get_option( self::$uncanny_codes_settings_gravity_forms_placeholder, __( 'Enter code', 'uncanny-learndash-groups' ) );

				$props       = array(
						'id'           => 99,
						'label'        => $label,
						'adminLabel'   => $label,
						'type'         => 'text',
						'size'         => 'large',
						'isRequired'   => $mandatory,
						'placeholder'  => $placeholder,
						'noDuplicates' => false,
						'formId'       => $form['id'],
						'pageNumber'   => 1,
						'errorMessage' => $error,
				);
				$form_fields = array();

				foreach ( $form['fields'] as $key => $value ) {
					$form_fields[] = $value['id'];
				}
				if ( ! in_array( 99, $form_fields, true ) ) {
					$field = \GF_Fields::create( $props );
					array_push( $form['fields'], $field );
				}
			}
		}

		return $form;

	}

	/**
	 * @param $validation_result
	 *
	 * @return mixed
	 */
	public function custom_validation( $validation_result ) {
		$form = $validation_result['form'];
		if ( intval( self::$form_id ) === intval( $form['id'] ) ) {
			//$code_redemption = rgpost( 'input_99' );
			$code_redeem = rgpost( 'input_99' );

			if ( ! empty( $code_redeem ) ) {
				$code_details = SharedFunctions::is_key_available( $code_redeem );
				if ( is_array( $code_details ) ) {
					if ( 'failed' === $code_details['result'] ) {
						$validation_result['is_valid'] = false;
						if ( 'invalid' === $code_details['error'] ) {
							//$this->ulgm_registration_errors()->add( 'code_invalid', Config::$invalid_code );
							foreach ( $form['fields'] as &$field ) {
								//NOTE: replace 1 with the field you would like to validate
								if ( 99 === $field->id ) {
									$field->failed_validation  = true;
									$field->validation_message = Config::$invalid_code;
									break;
								}
							}
						} elseif ( 'existing' === $code_details['error'] ) {
							//$this->ulgm_registration_errors()->add( 'code_redeemed', Config::$already_redeemed );
							foreach ( $form['fields'] as &$field ) {
								//NOTE: replace 1 with the field you would like to validate
								if ( 99 === $field->id ) {
									$field->failed_validation  = true;
									$field->validation_message = Config::$already_redeemed;
									break;
								}
							}
						}
					} elseif ( 'success' === $code_details['result'] ) {
						self::$code_details = $code_details;
					}
				} else {
					self::$code_details = null;
					//$this->ulgm_registration_errors()->add( 'code_invalid', Config::$invalid_code );
					foreach ( $form['fields'] as &$field ) {

						//NOTE: replace 1 with the field you would like to validate
						if ( 99 === $field->id ) {
							$field->failed_validation  = true;
							$field->validation_message = Config::$invalid_code;
							break;
						}
					}
				}
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
		$code_redemption = gform_get_meta( $entry_meta['entry_id'], 99 );
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
}
