<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ThemeMyLoginSupport
 * @package uncanny_learndash_groups
 */
class ThemeMyLoginSupport {
	/**
	 * Code details
	 *
	 * @var array $code_details
	 */
	public static $code_details;

	/**
	 * Theme My Login enable option
	 *
	 * @var string $uncanny_groups_tml_template_override
	 */
	public static $uncanny_groups_tml_template_override = 'uncanny-learndash-groups-tml-override';

	/**
	 * Theme My Login Registration field required option
	 *
	 * @var string $uncanny_groups_tml_codes_required_field
	 */
	public static $uncanny_groups_tml_codes_required_field = 'uncanny-learndash-groups-tml-required-field';

	/**
	 * Theme My Login Registration add code registration field option
	 *
	 * @var string $uncanny_groups_tml_codes_add_field
	 */
	public static $uncanny_groups_tml_codes_add_field = 'uncanny-learndash-groups-tml-add-field';

	/**
	 * ThemeMyLoginSupport constructor.
	 */
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'admin_init', array( $this, 'save_form_settings' ) );
	}

	/**
	 * Loads the plugin on start
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		add_action( 'admin_menu', array( $this, 'create_submenu' ), 70 );

		if ( self::is_tml_field_active() ) {
			add_filter( 'registration_errors', [ $this, 'tml_registration_errors' ], 10, 3 );
		}
		//Registration completed
		add_action( 'user_register', array( $this, 'tml_user_register' ), 15 );

		add_action( 'init', array( $this, 'tml_add_fields' ), 99 );

	}

	/**
	 * Adds sub-menu on admin side
	 *
	 * @return void
	 */
	public function create_submenu() {
		$capability  = 'manage_options';
		$parent_slug = 'uncanny-groups-create-group';
		add_submenu_page( $parent_slug, __( 'Theme My Login', 'uncanny-learndash-groups' ), __( 'Theme My Login', 'uncanny-learndash-groups' ), $capability, 'uncanny-groups-theme-my-login', array(
			$this,
			'tml_support',
		) );
	}

	/**
	 * Saves TML settings on admin side
	 *
	 * @return void
	 */
	public function save_form_settings() {
		if ( ( ! empty( $_POST ) && ulgm_filter_has_var( '_tmlwpnonce', INPUT_POST ) ) && wp_verify_nonce( ulgm_filter_input( '_tmlwpnonce', INPUT_POST ), 'uncanny-owl' ) ) {
			//replace registration form
			if ( ulgm_filter_has_var( 'tml-replace-registration-form', INPUT_POST ) && is_numeric( intval( ulgm_filter_input( 'tml-replace-registration-form', INPUT_POST ) ) ) ) {
				update_option( self::$uncanny_groups_tml_template_override, intval( ulgm_filter_input( 'tml-replace-registration-form', INPUT_POST ) ) );
			} else {
				update_option( self::$uncanny_groups_tml_template_override, 0 );
			}

			//add field to form
			if ( ulgm_filter_has_var( 'tml-add-code-field', INPUT_POST ) && is_numeric( intval( ulgm_filter_input( 'tml-add-code-field', INPUT_POST ) ) ) ) {
				update_option( self::$uncanny_groups_tml_codes_add_field, intval( ulgm_filter_input( 'tml-add-code-field', INPUT_POST ) ) );
			} else {
				update_option( self::$uncanny_groups_tml_codes_add_field, 0 );
			}

			//mark code field required
			if ( ulgm_filter_has_var( 'tml-code-required-field', INPUT_POST ) && is_numeric( intval( ulgm_filter_input( 'tml-code-required-field', INPUT_POST ) ) ) ) {
				update_option( self::$uncanny_groups_tml_codes_required_field, intval( ulgm_filter_input( 'tml-code-required-field', INPUT_POST ) ) );
			} else {
				update_option( self::$uncanny_groups_tml_codes_required_field, 0 );
			}

			wp_safe_redirect( ulgm_filter_input( '_wp_http_referer', INPUT_POST ) . '&saved=true&redirect_nonce=' . wp_create_nonce( time() ) );
			exit;
		}
	}

	/**
	 * Form TML settings on admin side
	 *
	 * @return void
	 */
	public function tml_support() {
		$tml_registration_field = get_option( self::$uncanny_groups_tml_template_override, 0 );
		$tml_required_field     = get_option( self::$uncanny_groups_tml_codes_required_field, 0 );
		$tml_add_field          = get_option( self::$uncanny_groups_tml_codes_add_field );
		if ( empty( $tml_add_field ) && 1 === absint( $tml_registration_field ) ) {
			$tml_add_field = 1;
		}
		?>
		<div class="wrap">
			<div class="ulgm">
				<div class="uo-ulgm-admin form-table group-management-form">
					<?php

					// Add admin header and tabs
					$tab_active = 'uncanny-groups-theme-my-login';
					include Utilities::get_template( 'admin/admin-header.php' );

					?>

					<div class="ulgm-admin-content">
						<!-- Messages -->
						<?php if ( isset( $_REQUEST['saved'] ) ) { ?>
							<div class="updated notice">
								<h4><?php _e( 'Settings saved!', 'uncanny-learndash-groups' ) ?></h4>
							</div>
						<?php } ?>
						<div class="uo-admin-section">
							<div class="uo-admin-header">
								<div
									class="uo-admin-title"><?php _e( 'Theme My Login registration settings', 'uncanny-learndash-groups' ); ?></div>
							</div>
							<div class="uo-admin-block">
								<form method="post" action="" id="uncanny-learndash-codes-form">
									<input type="hidden" name="_wp_http_referer"
									       value="<?php echo admin_url( 'admin.php?page=uncanny-groups-theme-my-login&saved=true' ) ?>"/>
									<input type="hidden" name="_tmlwpnonce"
									       value="<?php echo wp_create_nonce( 'uncanny-owl' ); ?>"/>
									<div class="uo-admin-form">
										<?php if ( ! defined( 'THEME_MY_LOGIN_VERSION' ) ) { ?>
											<div class="uo-admin-field">
												<label class="uo-checkbox">
													<input type="checkbox" value="1"
													       name="tml-replace-registration-form"
													       id="tml-replace-registration-form"<?php if ( 1 === intval( $tml_registration_field ) ) {
														echo 'checked="checked"';
													} ?>>
													<div class="uo-checkmark"></div>
													<span class="uo-label">
													<?php _e( 'Custom Theme My Login registration form including registration code field', 'uncanny-learndash-groups' ); ?>
												</span>
												</label>
											</div>
										<?php } ?>
										<div class="uo-admin-field">
											<label class="uo-checkbox">
												<input type="checkbox" value="1" name="tml-add-code-field"
												       id="tml-add-code-field"<?php if ( 1 === intval( $tml_add_field ) ) {
													echo 'checked="checked"';
												} ?>>
												<div class="uo-checkmark"></div>
												<span class="uo-label">
													<?php printf( _x( 'Add %s field', '%s is a field name', 'uncanny-learndash-groups' ), sprintf( '<strong>%s</strong>', __( 'Registration code', 'uncanny-learndash-groups' ) ) ); ?>
												</span>
											</label>
										</div>


										<div class="uo-admin-field">
											<label class="uo-checkbox">
												<input type="checkbox" value="1" name="tml-code-required-field"
												       id="tml-code-required-field"<?php if ( 1 === intval( $tml_required_field ) ) {
													echo 'checked="checked"';
												} ?>>
												<div class="uo-checkmark"></div>
												<span class="uo-label">
													<?php printf( _x( 'Make the %s field required', '%s is a field name', 'uncanny-learndash-groups' ), sprintf( '<strong>%s</strong>', __( 'Registration code', 'uncanny-learndash-groups' ) ) ); ?>
												</span>
											</label>
										</div>

										<div class="uo-admin-field">
											<input type="submit" name="submit" id="submit" class="uo-admin-form-submit"
											       value="<?php _e( 'Save Theme My Login changes', 'uncanny-learndash-groups' ); ?>">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php

	}

	/**
	 * Add fields for TML 7 on front-end
	 *
	 * @return void
	 */
	public function tml_add_fields() {
		$tml_add_field = get_option( self::$uncanny_groups_tml_codes_add_field, 0 );
		if ( empty( $tml_add_field ) ) {
			return;
		}
		if ( 1 !== (int) $tml_add_field ) {
			return;
		}

		tml_add_form_field( 'register', 'first_name', array(
			'type'        => 'text',
			'value'       => tml_get_request_value( 'first_name', 'post' ),
			'label'       => __( 'First name', 'uncanny-learndash-groups' ),
			'id'          => 'first_name',
			'attributes'  => array( 'required' => 'required' ),
			'description' => '',
			'priority'    => 5,
		) );

		tml_add_form_field( 'register', 'last_name', array(
			'type'        => 'text',
			'value'       => tml_get_request_value( 'last_name', 'post' ),
			'label'       => __( 'Last name', 'uncanny-learndash-groups' ),
			'id'          => 'last_name',
			'attributes'  => array( 'required' => 'required' ),
			'description' => '',
			'priority'    => 6,
		) );

		tml_add_form_field( 'register', 'ulgm_code_registration', array(
			'type'        => 'text',
			'value'       => tml_get_request_value( 'ulgm_code_registration', 'post' ),
			'label'       => __( 'Registration code', 'uncanny-learndash-groups' ),
			'id'          => 'ulgm_code_registration',
			'attributes'  => 1 === intval( get_option( self::$uncanny_groups_tml_codes_required_field ) ) ? array( 'required' => 'required' ) : '',
			'description' => '',
			'priority'    => 15,
		) );
	}

	/**
	 * Add fields for less then TML 7 on front-end
	 *
	 * @param $paths
	 *
	 * @return string
	 */
	public static function tml_single_template( $paths ) {
		if ( 1 === intval( get_option( self::$uncanny_groups_tml_template_override ) ) && defined( 'THEME_MY_LOGIN_VERSION' ) ) {
			$paths = array_merge( array( dirname( dirname( __FILE__ ) ) . '/templates/' ), $paths );
		}

		return $paths;
	}

	/**
	 * Validates the registration code on sign up.
	 *
	 * @return object
	 */
	public static function tml_registration_errors( $errors, $sanitized_user_login, $user_email ) {
		if ( ! self::is_tml_field_active() ) {
			return $errors;
		}

		if ( empty( ulgm_filter_input( 'ulgm_code_registration', INPUT_POST ) ) ) {
			$tml_required_field = get_option( self::$uncanny_groups_tml_codes_required_field, 0 );
			if ( $tml_required_field ) {
				self::$code_details = null;
				$errors->add( 'code_invalid', '<strong>' . __( 'Error', 'uncanny-learndash-groups' ) . '</strong>: ' . Config::$invalid_code );
			}

			return $errors;
		}
		$code_redeem  = stripslashes_deep( ulgm_filter_input( 'ulgm_code_registration', INPUT_POST ) );
		$code_details = SharedFunctions::is_key_available( $code_redeem );

		if ( is_array( $code_details ) ) {
			if ( 'failed' === $code_details['result'] ) {
				if ( 'invalid' === $code_details['error'] ) {
					$errors->add( 'code_invalid', '<strong>' . __( 'Error', 'uncanny-learndash-groups' ) . '</strong>: ' . Config::$invalid_code );
				} elseif ( 'existing' === $code_details['error'] ) {
					$errors->add( 'code_maximum', '<strong>' . __( 'Error', 'uncanny-learndash-groups' ) . '</strong>: ' . Config::$already_redeemed );
				}
			} elseif ( 'success' === $code_details['result'] ) {
				self::$code_details = $code_details;
			}
		} else {
			self::$code_details = null;
			$errors->add( 'code_invalid', '<strong>' . __( 'Error', 'uncanny-learndash-groups' ) . '</strong>: ' . Config::$invalid_code );
		}

		return $errors;
	}

	/**
	 * Apply code after register.
	 *
	 * @return void
	 */
	public static function tml_user_register( $user_id ) {
		if ( ! self::is_tml_field_active() ) {
			return;
		}
		if ( ! empty( ulgm_filter_input( 'first_name', INPUT_POST ) ) ) {
			update_user_meta( $user_id, 'first_name', esc_attr( ulgm_filter_input( 'first_name', INPUT_POST ) ) );
		}
		if ( ! empty( ulgm_filter_input( 'last_name', INPUT_POST ) ) ) {
			update_user_meta( $user_id, 'last_name', esc_attr( ulgm_filter_input( 'last_name', INPUT_POST ) ) );
		}

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
	 * @return bool
	 */
	public static function is_tml_field_active() {
		$tml_add_field = get_option( self::$uncanny_groups_tml_codes_add_field, 0 );
		if ( empty( $tml_add_field ) ) {
			return false;
		}
		if ( 1 !== (int) $tml_add_field ) {
			return false;
		}

		return true;
	}
}
