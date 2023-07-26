<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class GroupManagementRegistration
 *
 * @package uncanny_learndash_groups
 */
class GroupManagementRegistration {
	/**
	 * @var
	 */
	static $code_details;

	/**
	 * @var
	 */
	public static $code_redemption_atts;
	/**
	 * @var
	 */
	public static $code_registration_atts;

	/**
	 * GroupManagementRegistration constructor.
	 */
	public function __construct() {
		add_shortcode( 'uo_groups_registration_form', array( $this, 'ulgm_user_registration_func' ) );
		add_shortcode( 'uo_groups_redemption_form', array( $this, 'ulgm_code_redemption_func' ) );
		//Only fire if default registration is used
		add_action( 'init', array( $this, 'ulgm_registration_add_new_member' ), 99 );
		//Only fire if default registration is used
		add_action( 'init', array( $this, 'ulgm_registration_redeem_member' ), 99 );

		// Enqueue Scripts for uo_group_management shortcode.
		add_action( 'wp_enqueue_scripts', array( $this, 'uo_group_management_registration_scripts' ) );
	}

	/**
	 * Loads all scripts and styles required by the shortcode
	 *
	 * @since 2.2.0
	 */

	public function uo_group_management_registration_scripts() {
		global $post;

		// Only add scripts if shortcode is present on page.
		if ( Utilities::has_shortcode( $post, 'uo_groups_registration_form' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-registration-form' ) ) {
			self::enqueue_frontend_assets();
		}
	}

	/**
	 * @since 3.7.5
	 * @author Agus B.
	 * @internal Saad S.
	 */
	public static function enqueue_frontend_assets() {
		global $post;

		if ( ! empty( $post ) ) {
			// Load styles.
			wp_register_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array(), Utilities::get_version() );
			$user_colors = Utilities::user_colors();
			wp_add_inline_style( 'ulgm-frontend', $user_colors );
			wp_enqueue_style( 'ulgm-frontend', $user_colors );
		}
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function ulgm_user_registration_func( $atts ) {
		ob_start();
		self::$code_registration_atts = shortcode_atts(
			array(
				'redirect'      => '',
				'code_optional' => 'no',
				'auto_login'    => 'yes',
				'role'          => get_option( 'default_role', 'subscriber' ),
				'enable_terms'  => 'yes',
			),
			$atts,
			'ulgm_user_registration'
		);

		$this->ulgm_show_error_messages();
		include_once Utilities::get_include( 'forms/user-registration-form.php' );

		return ob_get_clean();
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function ulgm_code_redemption_func( $atts ) {
		ob_start();
		self::$code_redemption_atts = shortcode_atts(
			array(
				'redirect' => '',
				'role'     => '',
			),
			$atts,
			'ulgm_code_redemption'
		);

		$this->ulgm_show_error_messages();
		include_once Utilities::get_include( 'forms/user-code-redemption.php' );

		return ob_get_clean();
	}


	/**
	 * @return \WP_Error
	 */
	public static function ulgm_registration_errors() {
		static $wp_error; // Will hold global variable safely

		return isset( $wp_error ) ? $wp_error : ( $wp_error = new \WP_Error( null, null, null ) );
	}

	/**
	 * function to catch all errors for default registration form
	 */
	public function ulgm_show_error_messages() {
		$codes = $this->ulgm_registration_errors()->get_error_codes();
		if ( $codes ) {
			echo '<div class="uncanny_learndash_codes_errors">';
			// Loop error codes and display errors
			foreach ( $codes as $code ) {
				$message = $this->ulgm_registration_errors()->get_error_message( $code );
				echo '<span class="uo_groups_error">';
				printf( '<strong>%s</strong>: %s', __( 'Error', 'uncanny-learndash-groups' ), esc_html( $message ) );
				echo '</span>';
			}
			echo '</div>';
		}
	}

	/**
	 *
	 */
	public function ulgm_registration_add_new_member() {
		if ( ! ulgm_filter_has_var( '_ulgm_nonce', INPUT_POST ) ) {
			return;
		}

		if ( ! wp_verify_nonce( ulgm_filter_input( '_ulgm_nonce', INPUT_POST ), Utilities::get_prefix() ) ) {
			return;
		}
		$user_login        = sanitize_user( ulgm_filter_input( 'ulgm_user_email', INPUT_POST ) );
		$user_email        = sanitize_email( ulgm_filter_input( 'ulgm_user_email', INPUT_POST ) );
		$user_first        = sanitize_text_field( ulgm_filter_input( 'ulgm_user_first', INPUT_POST ) );
		$user_last         = sanitize_text_field( ulgm_filter_input( 'ulgm_user_last', INPUT_POST ) );
		$user_pass         = ulgm_filter_input( 'ulgm_user_pass', INPUT_POST );
		$pass_confirm      = ulgm_filter_input( 'ulgm_user_pass_confirm', INPUT_POST );
		$code_registration = ulgm_filter_input( 'ulgm_code_registration', INPUT_POST );
		$redirect_to       = ulgm_filter_input( 'redirect_to', INPUT_POST );
		$user_role         = ulgm_filter_input( '_ulgm_role_to_create', INPUT_POST );
		$auto_login        = ulgm_filter_input( '_ulgm_auto_login', INPUT_POST );
		$code_optional     = ulgm_filter_input( '_ulgm_code_optional', INPUT_POST );

		if ( username_exists( $user_login ) ) {
			// Username already registered
			$this->ulgm_registration_errors()->add( 'username_unavailable', esc_html__( 'Username already taken', 'uncanny-learndash-groups' ) );
		}
		//      if ( ! validate_username( $user_login ) ) {
		//          // invalid username
		//          $this->ulgm_registration_errors()->add( 'username_invalid', esc_html__( 'Invalid username', 'uncanny-learndash-groups' ) );
		//      }
		if ( '' === $user_login ) {
			// empty username
			$this->ulgm_registration_errors()->add( 'username_empty', esc_html__( 'Please enter a username', 'uncanny-learndash-groups' ) );
		}
		if ( ! is_email( $user_email ) ) {
			//invalid email
			$this->ulgm_registration_errors()->add( 'email_invalid', esc_html__( 'Invalid email', 'uncanny-learndash-groups' ) );
		}
		if ( email_exists( $user_email ) ) {
			//Email address already registered
			$this->ulgm_registration_errors()->add( 'email_used', esc_html__( 'Email already registered', 'uncanny-learndash-groups' ) );
		}
		if ( '' === $user_pass ) {
			// passwords do not match
			$this->ulgm_registration_errors()->add( 'password_empty', esc_html__( 'Please enter a password', 'uncanny-learndash-groups' ) );
		}
		if ( $pass_confirm !== $user_pass ) {
			// passwords do not match
			$this->ulgm_registration_errors()->add( 'password_mismatch', esc_html__( 'Passwords do not match', 'uncanny-learndash-groups' ) );
		}

		if ( '' === $code_registration && 'no' === $code_optional ) {
			$this->ulgm_registration_errors()->add( 'code_empty', esc_html__( 'Registration code is empty', 'uncanny-learndash-groups' ) );
		}

		$errors = $this->ulgm_registration_errors()->get_error_messages();

		if ( ! empty( $errors ) ) {
			return;
		}

		$code_details = SharedFunctions::is_key_available( $code_registration );
		if ( ! is_array( $code_details ) ) {
			$this->ulgm_registration_errors()->add( 'code_invalid', Config::$invalid_code );

			return;
		}

		if ( 'failed' === $code_details['result'] ) {
			if ( 'invalid' === $code_details['error'] ) {
				$this->ulgm_registration_errors()->add( 'code_invalid', Config::$invalid_code );
			} elseif ( 'existing' === $code_details['error'] ) {
				$this->ulgm_registration_errors()->add( 'code_redeemed', Config::$already_redeemed );
			} elseif ( 'seat_not_available' === $code_details['error'] ) {
				$this->ulgm_registration_errors()->add( 'seat_not_available', Config::$seat_not_available );
			}
		}
		if ( 'success' === $code_details['result'] ) {
			self::$code_details = $code_details;
		}

		$errors = $this->ulgm_registration_errors()->get_error_messages();

		if ( ! empty( $errors ) ) {
			return;
		}

		do_action( 'ulgm_new_user_registration_by_form_before', $_POST );
		// only create the user in if there are no errors
		$role        = ! empty( $user_role ) ? $user_role : get_option( 'default_role', 'subscriber' );
		$new_user_id = wp_insert_user(
			apply_filters(
				'ulgm_new_user_code_registration',
				array(
					'user_login'      => $user_login,
					'user_pass'       => $user_pass,
					'user_email'      => $user_email,
					'first_name'      => $user_first,
					'last_name'       => $user_last,
					'user_registered' => date( 'Y-m-d H:i:s' ),
					'role'            => $role,
				),
				$_POST
			)
		);

		do_action( 'ulgm_new_user_registration_by_form_after', $new_user_id, $_POST );

		if ( is_wp_error( $new_user_id ) ) {
			$this->ulgm_registration_errors()->add( 'user_error', __( 'Unable to create user. Please contact site admin.', 'uncanny-learndash-groups' ) );

			return;
		}
		// send an email to the admin alerting them of the registration
		wp_new_user_notification( $new_user_id, null, 'admin' );
		// log the new user in
		update_user_meta( $new_user_id, '_ulgm_code_used', $code_registration );
		$result = SharedFunctions::set_user_to_code( $new_user_id, $code_registration, SharedFunctions::$not_started_status );
		if ( $result ) {
			SharedFunctions::set_user_to_group( $new_user_id, self::$code_details['ld_group_id'] );
		}

		if ( 'yes' === $auto_login ) {
			wp_set_auth_cookie( $new_user_id );
			wp_set_current_user( $new_user_id, $user_login );
		}

		/**
		 * Fires after a user is registered in the frontend, just before redirection.
		 *
		 * @param int $new_user_id Newly registered user ID.
		 * @param array|null $coode_details Redemption code details, if available.
		 */
		do_action( 'ulgm_user_registered', $new_user_id, self::$code_details );

		if ( ! empty( $redirect_to ) ) {
			wp_safe_redirect( $redirect_to . '?&registered&nonce=' . wp_create_nonce( time() ) );
		} else {
			wp_safe_redirect( get_permalink() . '?&registered&nonce=' . wp_create_nonce( time() ) );
		}
		exit;
	}

	/**
	 *
	 */
	public function ulgm_registration_redeem_member() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! ulgm_filter_has_var( '_ulgm_code_nonce', INPUT_POST ) ) {
			return;
		}

		if ( ! wp_verify_nonce( ulgm_filter_input( '_ulgm_code_nonce', INPUT_POST ), Utilities::get_prefix() ) ) {
			return;
		}

		$code_redeem = trim( sanitize_text_field( ulgm_filter_input( 'ulgm_code_redeem', INPUT_POST ) ) );

		if ( empty( $code_redeem ) ) {
			//It's empty!
			$this->ulgm_registration_errors()->add( 'code_invalid', Config::$invalid_code );

			return;
		}
		$pattern = apply_filters( 'ulgm_group_key_pattern', '/^[A-Za-z0-9-]+$/D' );
		if ( ! preg_match( $pattern, $code_redeem ) ) {
			// Only alpha numeric and hypen allowed.
			$this->ulgm_registration_errors()->add( 'code_invalid', Config::$invalid_code );

			return;
		}

		$code_details = SharedFunctions::is_key_available( $code_redeem );
		if ( ! is_array( $code_details ) ) {
			self::$code_details = null;
			$this->ulgm_registration_errors()->add( 'code_invalid', Config::$invalid_code );

			return;
		}

		if ( 'failed' === $code_details['result'] ) {
			if ( 'invalid' === $code_details['error'] ) {
				$this->ulgm_registration_errors()->add( 'code_invalid', Config::$invalid_code );

				return;
			} elseif ( 'existing' === $code_details['error'] ) {
				$this->ulgm_registration_errors()->add( 'code_redeemed', Config::$already_redeemed );

				return;
			} elseif ( 'seat_not_available' === $code_details['error'] ) {
				$this->ulgm_registration_errors()->add( 'seat_not_available', Config::$seat_not_available );

				return;
			}
		}

		if ( 'success' === $code_details['result'] ) {
			self::$code_details = $code_details;
		}

		$errors = $this->ulgm_registration_errors()->get_error_messages();
		if ( ! empty( $errors ) ) {
			return;
		}

		$redirect_to = esc_url_raw( ulgm_filter_input( '_ulgm_code_redirect_to', INPUT_POST ) );
		$set_role    = trim( sanitize_text_field( ulgm_filter_input( '_ulgm_code_default_role', INPUT_POST ) ) );
		$user        = wp_get_current_user();
		$user_id     = $user->ID;
		do_action( 'ulgm_user_redeems_group_key_before', $user, self::$code_details, $_POST );
		update_user_meta( $user_id, '_ulgm_code_used', $code_redeem );
		$result = SharedFunctions::set_user_to_code( $user_id, $code_redeem, SharedFunctions::$not_started_status );
		if ( $result ) {
			SharedFunctions::set_user_to_group( $user_id, self::$code_details['ld_group_id'] );
			if ( ! empty( $set_role ) ) {
				// check if this role really exists.
				$user->add_role( $set_role );
			}
		}
		/**
		 * Fires after a user redeems a code, just before redirection.
		 *
		 * @param int $user_id User ID.
		 * @param array|null $coode_details Redemption code details, if available.
		 */
		do_action( 'ulgm_user_redeems_group_key', $user_id, self::$code_details );

		if ( ! empty( $redirect_to ) ) {
			wp_safe_redirect( $redirect_to );
		} else {
			wp_safe_redirect( get_permalink() . '?' . $_REQUEST['key'] . '&registered&nonce=' . wp_create_nonce( time() ) );
		}
		exit;
	}
}
