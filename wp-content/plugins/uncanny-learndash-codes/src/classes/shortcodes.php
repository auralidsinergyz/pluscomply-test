<?php

namespace uncanny_learndash_codes;

/**
 * Class Shortcodes
 * @package uncanny_learndash_codes
 */
class Shortcodes extends Config {
	private static $coupon_id;

	/**
	 * Shortcodes constructor.
	 */
	public function __construct() {

		//Code Redemption for Logged in Users
		add_shortcode( 'uo_user_redeem_code', array( __CLASS__, 'user_redeem_code_callback' ) );
		add_shortcode( 'uo_self_remove_access', array( __CLASS__, 'remove_from_group_callback' ) );
		add_shortcode( 'uo_code_registration', array( __CLASS__, 'user_code_registration' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts_styles' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_registration_css' ), 10, 2 );
		/*if ( ! function_exists( 'wp_verify_nonce' ) ) {
			require_once( ABSPATH . 'wp-includes/pluggable.php' );
		}*/
		//Only fire if default registration is used
		add_action( 'wp_loaded', array( __CLASS__, 'uncanny_learndash_codes_add_new_member' ), 999 );
		//}
	}

	/**
	 *
	 */
	public static function enqueue_scripts_styles() {
		global $post;

		$block_is_on_page = false;
		if ( is_a( $post, 'WP_Post' ) && function_exists( 'parse_blocks' ) ) {
			$blocks = parse_blocks( $post->post_content );
			foreach ( $blocks as $block ) {
				if ( 'uncanny-learndash-codes/uo-code-registration' === $block['blockName'] || 'uncanny-learndash-codes/uo-user-redeem-code' === $block['blockName'] ) {
					$block_is_on_page = true;
				}
			}
		}

		if ( ! empty( $post->ID ) && (
				has_shortcode( $post->post_content, 'uo_user_redeem_code' )
				|| has_shortcode( $post->post_content, 'uo_self_remove_access' )
				|| has_shortcode( $post->post_content, 'uo_code_registration' )
				|| $block_is_on_page
			)
		) {
			wp_enqueue_script( 'uncanny-learndash-codes-mootools-core', Config::get_vendor( 'mootools/mootools-core-1.3.1.js' ), false, '1.3.1' );
			wp_enqueue_script( 'uncanny-learndash-codes-mootools-more', Config::get_vendor( 'mootools/mootools-more-1.3.1.1.js' ), array( 'uncanny-learndash-codes-mootools-core' ), '1.3.1.1' );
			wp_enqueue_script( 'uncanny-learndash-codes-simple-modal', Config::get_vendor( 'simple-modal/simple-modal.min.js' ), array( 'uncanny-learndash-codes-mootools-core', 'uncanny-learndash-codes-mootools-more' ), '1.3.1.1' );

			wp_enqueue_style( 'uncanny-learndash-codes-backend', Config::get_asset( 'backend', 'bundle.min.css' ), false, '2.0.5' );
			wp_enqueue_script( 'uncanny-learndash-codes-backend', Config::get_asset( 'backend', 'bundle.min.js' ), array( 'jquery', 'uncanny-learndash-codes-mootools-core', 'uncanny-learndash-codes-mootools-more', 'uncanny-learndash-codes-simple-modal' ), '2.0.5' );
		}
	}

	public static function enqueue_registration_css() {
		global $post;

		$block_is_on_page = false;
		if ( is_a( $post, 'WP_Post' ) && function_exists( 'parse_blocks' ) ) {
			$blocks = parse_blocks( $post->post_content );
			foreach ( $blocks as $block ) {
				if ( 'uncanny-learndash-codes/uo-code-registration' === $block['blockName'] || 'uncanny-learndash-codes/uo-user-redeem-code' === $block['blockName'] ) {
					$block_is_on_page = true;
				}
			}
		}

		if ( ! empty( $post->ID ) && (
				has_shortcode( $post->post_content, 'uo_user_redeem_code' )
				|| has_shortcode( $post->post_content, 'uo_self_remove_access' )
				|| has_shortcode( $post->post_content, 'uo_code_registration' )
				|| $block_is_on_page
			)
		) {
			wp_enqueue_style( 'uncanny-learndash-codes-frontend', Config::get_asset( 'frontend', 'bundle.min.css' ) );
		}
	}


	/**
	 *
	 */
	public static function user_code_registration( $atts ) {
		ob_start();
		//$registration_enabled = get_option( 'users_can_register' );
		$atts = shortcode_atts( array(
			'redirect'      => '',
			'code_optional' => 'no',
			'auto_login'    => 'yes',
			'role'          => 'subscriber',
		), $atts, 'uo_code_registration' );

		$GLOBALS['atts'] = $atts;

		// only show the registration form if allowed
		//if ( $registration_enabled ) {
		// show any error messages after form submission
		update_option( 'uncanny-codes-custom-registration-atts', $atts );

		self::uncanny_learndash_codes_show_error_messages();
		include_once( dirname( ( dirname( __FILE__ ) ) ) . '/templates/frontend-user-registration-form.php' );
		//} else {
		//	echo esc_html__( 'User registration is not enabled. Contact Site Administrator.', 'uncanny-learndash-codes' );
		//}

		return ob_get_clean();
	}

	/**
	 * @return \WP_Error
	 */
	public static function uncanny_learndash_codes_errors() {
		static $wp_error; // Will hold global variable safely

		return isset( $wp_error ) ? $wp_error : ( $wp_error = new \WP_Error( null, null, null ) );
	}

	/**
	 * function to catch all errors for default registration form
	 */
	public static function uncanny_learndash_codes_show_error_messages() {
		if ( $codes = self::uncanny_learndash_codes_errors()->get_error_codes() ) {
			echo '<div class="uncanny_learndash_codes_errors">';
			// Loop error codes and display errors
			foreach ( $codes as $code ) {
				$message = self::uncanny_learndash_codes_errors()->get_error_message( $code );
				printf( '<span class="error"><strong>Error</strong>: %s </span><br />', esc_html( $message ) );
			}
			echo '</div>';
		}
	}


	/**
	 *
	 */
	public static function uncanny_learndash_codes_add_new_member() {
		if ( isset( $_POST ) && ( isset( $_POST['_uo_nonce'] ) && wp_verify_nonce( $_POST['_uo_nonce'], Config::get_project_name() ) ) ) {
			//$user_login        = sanitize_text_field( $_POST['uncanny-learndash-codes-user_login'] );
			$user_login        = sanitize_user( $_POST['uncanny-learndash-codes-user_email'], false );
			$user_email        = sanitize_email( $_POST['uncanny-learndash-codes-user_email'] );
			$user_first        = sanitize_text_field( $_POST['uncanny-learndash-codes-user_first'] );
			$user_last         = sanitize_text_field( $_POST['uncanny-learndash-codes-user_last'] );
			$user_pass         = $_POST['uncanny-learndash-codes-user_pass'];
			$pass_confirm      = $_POST['uncanny-learndash-codes-user_pass_confirm'];
			$code_registration = $_POST['uncanny-learndash-codes-code_registration'];
			$redirect_to       = $_POST['redirect_to'];

			$default = array(
				'redirect'      => '',
				'code_optional' => 'no',
				'auto_login'    => 'yes',
				'role'          => 'subscriber',
			);

			if ( is_multisite() ) {
				$options = get_blog_option( get_current_blog_id(), 'uncanny-codes-custom-registration-atts', $default );
			} else {
				$options = get_option( 'uncanny-codes-custom-registration-atts', $default );
			}


			if ( username_exists( $user_login ) ) {
				// Username already registered
				self::uncanny_learndash_codes_errors()->add( 'username_unavailable', esc_html__( 'Username already taken', 'uncanny-learndash-codes' ) );
			}
			if ( ! validate_username( $user_login ) ) {
				// invalid username
				self::uncanny_learndash_codes_errors()->add( 'username_invalid', esc_html__( 'Invalid username', 'uncanny-learndash-codes' ) );
			}
			if ( '' === $user_login ) {
				// empty username
				self::uncanny_learndash_codes_errors()->add( 'username_empty', esc_html__( 'Please enter a username', 'uncanny-learndash-codes' ) );
			}
			if ( ! is_email( $user_email ) ) {
				//invalid email
				self::uncanny_learndash_codes_errors()->add( 'email_invalid', esc_html__( 'Invalid email', 'uncanny-learndash-codes' ) );
			}
			if ( email_exists( $user_email ) ) {
				//Email address already registered
				self::uncanny_learndash_codes_errors()->add( 'email_used', esc_html__( 'Email already registered', 'uncanny-learndash-codes' ) );
			}
			if ( '' === $user_pass ) {
				// passwords do not match
				self::uncanny_learndash_codes_errors()->add( 'password_empty', esc_html__( 'Please enter a password', 'uncanny-learndash-codes' ) );
			}
			if ( $pass_confirm !== $user_pass ) {
				// passwords do not match
				self::uncanny_learndash_codes_errors()->add( 'password_mismatch', esc_html__( 'Passwords do not match', 'uncanny-learndash-codes' ) );
			}
			if ( 'no' === $options['code_optional'] ) {
				if ( '' === $code_registration ) {
					self::uncanny_learndash_codes_errors()->add( 'code_empty', esc_html__( 'Registration Code is empty', 'uncanny-learndash-codes' ) );
				} elseif ( ! empty( $code_registration ) ) {
					$coupon_id = Database::is_coupon_available( $code_registration );
					if ( is_array( $coupon_id ) ) {
						if ( 'failed' === $coupon_id['result'] ) {
							if ( 'max' === $coupon_id['error'] ) {
								self::uncanny_learndash_codes_errors()->add( 'code_maximum', Config::$redeemed_maximum );
							} elseif ( 'invalid' === $coupon_id['error'] ) {
								self::uncanny_learndash_codes_errors()->add( 'code_invalid', Config::$invalid_code );
							} elseif ( 'existing' === $coupon_id['error'] ) {
								self::uncanny_learndash_codes_errors()->add( 'code_redeemed', Config::$already_redeemed );
							} elseif ( 'expired' === $coupon_id['error'] ) {
								self::uncanny_learndash_codes_errors()->add( 'code_expired', Config::$expired_code );
							}
						}
					} elseif ( is_numeric( $coupon_id ) ) {
						self::$coupon_id = intval( $coupon_id );
					} else {
						self::$coupon_id = null;
						self::uncanny_learndash_codes_errors()->add( 'code_invalid', Config::$invalid_code );
					}
				}
			}
			$errors = self::uncanny_learndash_codes_errors()->get_error_messages();
			// only create the user in if there are no errors
			if ( empty( $errors ) ) {
				$role        = key_exists( 'role', $options ) ? $options['role'] : 'subscriber';
				$new_user_id = wp_insert_user( array(
						'user_login'      => $user_login,
						'user_pass'       => $user_pass,
						'user_email'      => $user_email,
						'first_name'      => $user_first,
						'last_name'       => $user_last,
						'user_registered' => date( 'Y-m-d H:i:s' ),
						'role'            => $role,
					)
				);
				if ( $new_user_id ) {
					// send an email to the admin alerting them of the registration
					wp_new_user_notification( $new_user_id, null, 'admin' );
					// log the new user in
					if ( intval( self::$coupon_id ) ) {

						update_user_meta( $new_user_id, Config::$uncanny_codes_tracking, 'Custom Registration Form' );

						$result = Database::set_user_to_coupon( $new_user_id, self::$coupon_id );
						LearnDash::set_user_to_course_or_group( $new_user_id, $result );

					}

					if ( 'yes' === $options['auto_login'] ) {
						wp_set_auth_cookie( $new_user_id );
						wp_set_current_user( $new_user_id, $user_login );
					}

					if ( ! empty( $redirect_to ) ) {
						wp_redirect( $redirect_to . '?' . $_REQUEST['key'] . '&registered' );
					} else {
						wp_redirect( get_permalink() . '?' . $_REQUEST['key'] . '&registered' );
					}
					exit;
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public static function user_redeem_code_callback() {

		$error   = '';
		$user    = wp_get_current_user();
		$user_id = $user->ID;
		if ( ! intval( $user_id ) ) {
			return 'Sorry! You are not logged in!';
		}

		if ( ! empty( $_POST ) && ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( $_POST['_wpnonce'], Config::get_project_name() ) ) ) {
			$error = 'Sorry your request was not verified. Please try again later. Log out and try again if problem persist.';

			return $error;
		} elseif ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], Config::get_project_name() ) ) {
			if ( ! empty ( $_POST['coupon_code_only'] ) ) {
				$coupon_id = Database::is_coupon_available( $_POST['coupon_code_only'] );
				if ( is_array( $coupon_id ) ) {
					if ( 'failed' === $coupon_id['result'] ) {
						if ( 'max' === $coupon_id['error'] ) {
							$error = Config::$redeemed_maximum;
						} elseif ( 'invalid' === $coupon_id['error'] ) {
							$error = Config::$invalid_code;
						} elseif ( 'existing' === $coupon_id['error'] ) {
							$error = Config::$already_redeemed;
						} elseif ( 'expired' === $coupon_id['error'] ) {
							$error = Config::$expired_code;
						}
					}

					return self::get_form( $error );
				} elseif ( is_numeric( $coupon_id ) ) {
					self::$coupon_id = intval( $coupon_id );
				} else {
					self::$coupon_id = null;
				}

				if ( intval( self::$coupon_id ) ) {

					$is_paid    = Database::is_coupon_paid( $_POST['coupon_code_only'] );
					$is_default = Database::is_default_code( $_POST['coupon_code_only'] );

					if ( false !== $is_default || false !== $is_paid ) {
						update_user_meta( $user_id, Config::$uncanny_codes_tracking, 'User Code Redeemed' );

						$result = Database::set_user_to_coupon( $user_id, self::$coupon_id );
						LearnDash::set_user_to_course_or_group( $user_id, $result );
						$error = Config::$successfully_redeemed;

					} else {
						$error = Config::$unpaid_error;
					}

					return self::get_form( $error );

				}
			} else {
				$error = esc_html__( 'Please input the coupon code before clicking redeem', 'uncanny-learndash-codes' );
			}
		}

		return self::get_form( $error );
	}

	/* Code Only Redemption Form
	 *
	 * return string
	 */
	/**
	 * @param $error
	 *
	 * @return string
	 */
	public static function get_form( $error ) {

		$form = '<div class="uo uo-register uo-redeem gform_wrapper dark-form_wrapper" id="theme-my-login">
					<form name="codeRedeemForm" id="codeRedeemForm" action="" method="post" class="dark-form">
						<ul class="gform_fields top_label form_sublabel_below description_below">
							<li id="coupon_code_wrapper" class="gfield  field_sublabel_below field_description_below">
							<label for="coupon_code" class="hidden">'.__('Coupon Code', 'uncanny-learndash-codes').'</label>
							<div>
								<input name="coupon_code_only" id="coupon_code_only" type="text" value="" class="medium" tabindex="2" placeholder="Enter Coupon Code" required="required" />
							</div>
							<div class="description">' . $error . '</div>
							</li>
						</ul>
						<p class="uo-submit-wrap gform_footer top_label">
							<input type="submit" value="Redeem" />						
							<input type="hidden" name="instance" value="codeRedeemForm" />
							<input type="hidden" name="action" value="redeem-code" />
							<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( Config::get_project_name() ) . '"/>
						</p>
					</form>
                 </div>';

		return $form;

	}

	/**
	 * @return string
	 */
	public static function remove_from_group_callback() {
		$error   = '';
		$user    = wp_get_current_user();
		$user_id = $user->ID;

		if ( ! intval( $user_id ) ) {
			return esc_html__( 'Sorry! You are not logged in!', 'uncanny-learndash-codes' );
		}

		if ( ! empty( $_POST ) && ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( $_POST['_wpnonce'], Config::get_project_name() ) ) ) {
			$error = esc_html__( 'Sorry your request was not verified. Please try again later. Log out and try again if problem persist.', 'uncanny-learndash-codes' );

			return $error;
		} elseif ( ! empty( $_POST ) && ( isset( $_POST['_wp_nonce_removal'] ) && wp_verify_nonce( $_POST['_wp_nonce_removal'], Config::get_project_name() ) ) ) {
			LearnDash::remove_all_access( $user_id );
			$error = esc_html__( 'Access Removed Successfully!', 'uncanny-learndash-codes' );
		}

		return self::get_removal_form( $error );
	}

	/**
	 * @param $error
	 *
	 * @return string
	 */
	public static function get_removal_form( $error ) {
		$form = '<div class="uo uo-register uo-redeem gform_wrapper dark-form_wrapper" id="theme-my-login">
					<form name="codeRemovalForm" id="codeRemovalForm" action="" method="post" class="dark-form">
						<p class="uo-submit-wrap gform_footer top_label">
							<input type="submit" value="Remove access to all groups" id="validate-confirm-removal">						
							<input type="hidden" name="instance" value="codeRemovalInstance" />
							<input type="hidden" name="action" value="removal-code" />
							<input type="hidden" name="_wp_nonce_removal" value="' . wp_create_nonce( Config::get_project_name() ) . '"/>
						</p>
						<div class="description">' . $error . '</div>
					</form>
                </div>';
		$form .= '<script> 
					jQuery(document).ready(function(){
						$("validate-confirm-removal").addEvent("click", function(e){
						  e.stop();
						  var SM = new SimpleModal({"hideHeader":true, "btn_ok":"Yes", draggable:false});
						      SM.show({
						        "model":"confirm",
					            "callback": function(){
					              jQuery("#codeRemovalForm").submit();
					            },
								"title":"'.__('Confirm Removal', 'uncanny-learndash-codes').'",
						        "contents":"'.__('This action will remove you from all groups, which may remove your course access. Your group access cannot be restored. Are you sure you wish to continue?', 'uncanny-learndash-codes').'"
						      } );
		} );
	} )
</script>';

		return $form;
	}
}