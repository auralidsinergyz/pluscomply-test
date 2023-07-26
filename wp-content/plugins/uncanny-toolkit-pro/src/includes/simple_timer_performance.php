<?php
// TODO Another approach that takes long but may be more reliable
// https://pastebin.com/13N9wh39
// For the above, stop all plugins from loading and the theme.

namespace uncanny_learndash_toolkit;

class pseudo_performance_rest_call {

	private $session_token = null;

	private $execution_time = array();

	function __construct() {

		// For Debugging
		//$this->execution_time['start'] = microtime( true );

		$this->load_wp_minimally();

		$this->define_constants();

		$this->run_uo_timer();

		exit;
	}

	private function load_wp_minimally() {

		// Minimally load WP
		define( 'SHORTINIT', true );

		// Require WordPress
		$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

		if( file_exists( $parse_uri[0] . 'wp-load.php') ){
			require_once( $parse_uri[0] . 'wp-load.php' );
			return;
		}

		if( file_exists( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' ) ){
			require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
			return;
		}

	}

	private function define_constants(){

		global $wpdb;

		/**
		 * Used to guarantee unique hash cookies
		 *
		 * @since 1.5.0
		 */
		if ( ! defined( 'COOKIEHASH' ) ) {
			$q       = "SELECT option_value FROM $wpdb->options WHERE option_name = 'siteurl'";
			$siteurl = $wpdb->get_var( $q );
			if ( $siteurl ) {
				define( 'COOKIEHASH', md5( $siteurl ) );
			} else {
				define( 'COOKIEHASH', '' );
			}
		}

		/**
		 * @since 2.0.0
		 */
		if ( ! defined( 'USER_COOKIE' ) ) {
			define( 'USER_COOKIE', 'wordpressuser_' . COOKIEHASH );
		}

		/**
		 * @since 2.0.0
		 */
		if ( ! defined( 'PASS_COOKIE' ) ) {
			define( 'PASS_COOKIE', 'wordpresspass_' . COOKIEHASH );
		}

		/**
		 * @since 2.5.0
		 */
		if ( ! defined( 'AUTH_COOKIE' ) ) {
			define( 'AUTH_COOKIE', 'wordpress_' . COOKIEHASH );
		}

		/**
		 * @since 2.6.0
		 */
		if ( ! defined( 'SECURE_AUTH_COOKIE' ) ) {
			define( 'SECURE_AUTH_COOKIE', 'wordpress_sec_' . COOKIEHASH );
		}

		/**
		 * @since 2.6.0
		 */
		if ( ! defined( 'LOGGED_IN_COOKIE' ) ) {
			define( 'LOGGED_IN_COOKIE', 'wordpress_logged_in_' . COOKIEHASH );
		}


		/**
		 * @since 2.0.0
		 */
		if ( ! defined( 'COOKIE_DOMAIN' ) ) {
			define( 'COOKIE_DOMAIN', false );
		}

	}

	private function run_uo_timer(){

		global $wpdb;

		$return_object = array();

		$current_user = $this->wp_validate_auth_cookie();

		if ( ! $current_user ) {
			$return_object['success']             = false;
			$return_object['message']             = 'You must me logged in.';
			$return_object['fields']['course_id'] = $_GET['course_id'];
			$return_object['fields']['post_id']   = $_GET['post_id'];

			echo json_encode( $return_object );
			exit;
		}

		// validate inputs
		$course_ID = absint( $_GET['course_id'] );
		$post_ID   = absint( $_GET['post_id'] );

		// if any of the values are 0 then they didn't validate, storage is not possible
		if ( 0 === $post_ID ) {
			$return_object['success']             = false;
			$return_object['message']             = 'One or more the the fields did not validate as a absolute integer.';
			$return_object['fields']['course_id'] = $_GET['course_id'];
			$return_object['fields']['post_id']   = $_GET['post_id'];

			echo json_encode( $return_object );
			exit;
		}

		$meta_key = 'uo_timer_' . $course_ID . '_' . $post_ID;

		$q        = "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = '{$meta_key}' AND user_id = {$current_user->ID}";

		// get var instead of get user meta
		$timer = $wpdb->get_var( $q );

		$return_object['$timer'] = $timer;

		//  Set timer default
		if ( empty( $timer ) ) {
			$timer = 0;
			// create user meta since it doesn't exist and we need a unique to update
			$wpdb->insert(
				$wpdb->usermeta,
				array(
					'user_id'    => $current_user->ID,
					'meta_key'   => $meta_key,
					'meta_value' => 0
				),
				array(
					'%d',
					'%s',
					'%d'
				)
			);
		}

		$q = "SELECT umeta_id FROM $wpdb->usermeta WHERE meta_key = '{$meta_key}' AND user_id = {$current_user->ID}";
		$timer_umeta_id = $wpdb->get_var( $q );

		$return_object['$timer_umeta_id'] = $timer_umeta_id;

		// Amount of time added every call // JS interval must match
		$timer_interval = $this->get_settings_value( 'uo_timer_interval' );

		//  Set timer default, minimum is 10 seconds
		if ( '' === $timer_interval || (int) $timer_interval < 5 ) {
			$timer_interval = 15;
		}

		$timer += $timer_interval;

		$q = "UPDATE {$wpdb->usermeta} SET meta_value = {$timer} WHERE umeta_id = {$timer_umeta_id}";
		$wpdb->query($q);

		$return_object['success'] = true;
		$return_object['time']    = $timer;

		$q = "SELECT umeta_id FROM $wpdb->usermeta WHERE meta_key = 'session_tokens' AND user_id = {$current_user->ID}";
		$session_umeta_id = $wpdb->get_var( $q );

		$q = "UPDATE {$wpdb->usermeta} SET meta_value = '{$this->session_token}' WHERE umeta_id = {$session_umeta_id}";
		$wpdb->query(
			$q
		);

		// For debugging
		//$return_object['$meta_key'] = $meta_key;
		//$this->execution_time['end'] = microtime( true );
		//$exec_time = $this->execution_time['end'] - $this->execution_time['start'];
		//$return_object['execution_time']    = $exec_time;

		echo json_encode( $return_object );
		exit;

	}

	/**
	 * Validates authentication cookie.
	 *
	 * The checks include making sure that the authentication cookie is set and
	 * pulling in the contents (if $cookie is not used).
	 *
	 * Makes sure the cookie is not expired. Verifies the hash in cookie is what is
	 * should be and compares the two.
	 *
	 * @since 2.5.0
	 *
	 * @global int $login_grace_period
	 *
	 * @param string $cookie Optional. If used, will validate contents instead of cookie's
	 * @param string $scheme Optional. The cookie scheme to use: auth, secure_auth, or logged_in
	 *
	 * @return false|Object False if invalid cookie, User object if valid.
	 */
	private function wp_validate_auth_cookie( $cookie = '', $scheme = 'logged_in' ) {

		if ( ! $cookie_elements = $this->wp_parse_auth_cookie( $cookie, $scheme ) ) {
			return false;
		}

		$scheme   = $cookie_elements['scheme'];
		$username = $cookie_elements['username'];
		$hmac     = $cookie_elements['hmac'];
		$token    = $cookie_elements['token'];
		$expired  = $expiration = $cookie_elements['expiration'];

		// Allow a grace period for POST and Ajax requests
		if ( wp_doing_ajax() || 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$expired += HOUR_IN_SECONDS;
		}

		// Quick check to see if an honest cookie has expired
		if ( $expired < current_time( 'timestamp' ) ) {
			return false;
		}

		global $wpdb;

		$q    = "SELECT * FROM $wpdb->users WHERE user_login = '{$username}'";
		$user = $wpdb->get_row( $q );

		if ( ! $user ) {
			return false;
		}

		$pass_frag = substr( $user->user_pass, 8, 4 );

		$key = $this->wp_hash( $username . '|' . $pass_frag . '|' . $expiration . '|' . $token, $scheme );

		// If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
		$algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
		$hash = hash_hmac( $algo, $username . '|' . $expiration . '|' . $token, $key );

		if ( ! hash_equals( $hash, $hmac ) ) {
			return false;
		}

		$verifier = $this->hash_token( $token );
		if ( ! $verifier ) {
			return false; // normally returns false
		}

		$value = $wpdb->get_var(
			$wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'session_tokens' AND user_id = %d", $user->ID ) );

		$this->session_token = $value;

		$value = maybe_unserialize( $value );

		if ( is_null( $value ) ) {
			return false;
		}

		if ( ! isset( $value[ $verifier ] ) ) {
			return false;
		}

		// Ajax/POST grace period set above
		if ( $expiration < current_time( 'timestamp' ) ) {
			$GLOBALS['login_grace_period'] = 1;
		}

		return $user;
	}

	private function wp_parse_auth_cookie( $cookie = '', $scheme = '' ) {
		if ( empty( $cookie ) ) {
			switch ( $scheme ) {
				case 'auth':
					$cookie_name = AUTH_COOKIE;
					break;
				case 'secure_auth':
					$cookie_name = SECURE_AUTH_COOKIE;
					break;
				case "logged_in":
					$cookie_name = LOGGED_IN_COOKIE;
					break;
				default:
					if ( is_ssl() ) {
						$cookie_name = SECURE_AUTH_COOKIE;
						$scheme      = 'secure_auth';
					} else {
						$cookie_name = AUTH_COOKIE;
						$scheme      = 'auth';
					}
			}

			if ( empty( $_COOKIE[ $cookie_name ] ) ) {
				return false;
			}
			$cookie = $_COOKIE[ $cookie_name ];
		}

		$cookie_elements = explode( '|', $cookie );
		if ( count( $cookie_elements ) !== 4 ) {
			return false;
		}

		list( $username, $expiration, $token, $hmac ) = $cookie_elements;

		return compact( 'username', 'expiration', 'token', 'hmac', 'scheme' );
	}

	private function hash_token( $token ) {
		// If ext/hash is not present, use sha1() instead.
		if ( function_exists( 'hash' ) ) {
			return hash( 'sha256', $token );
		} else {
			return sha1( $token );
		}
	}

	private function wp_hash( $data, $scheme = 'auth' ) {
		$salt = $this->wp_salt( $scheme );

		return hash_hmac( 'md5', $data, $salt );
	}

	private function wp_salt( $scheme = 'auth' ) {
		static $cached_salts = array();
		if ( isset( $cached_salts[ $scheme ] ) ) {
			/**
			 * Filters the WordPress salt.
			 *
			 * @since 2.5.0
			 *
			 * @param string $cached_salt Cached salt for the given scheme.
			 * @param string $scheme Authentication scheme. Values include 'auth',
			 *                            'secure_auth', 'logged_in', and 'nonce'.
			 */
			return apply_filters( 'salt', $cached_salts[ $scheme ], $scheme );
		}

		static $duplicated_keys;
		if ( null === $duplicated_keys ) {
			$duplicated_keys = array( 'put your unique phrase here' => true );
			foreach ( array( 'AUTH', 'SECURE_AUTH', 'LOGGED_IN', 'NONCE', 'SECRET' ) as $first ) {
				foreach ( array( 'KEY', 'SALT' ) as $second ) {
					if ( ! defined( "{$first}_{$second}" ) ) {
						continue;
					}
					$value                     = constant( "{$first}_{$second}" );
					$duplicated_keys[ $value ] = isset( $duplicated_keys[ $value ] );
				}
			}
		}

		$values = array(
			'key'  => '',
			'salt' => ''
		);
		if ( defined( 'SECRET_KEY' ) && SECRET_KEY && empty( $duplicated_keys[ SECRET_KEY ] ) ) {
			$values['key'] = SECRET_KEY;
		}
		if ( 'auth' == $scheme && defined( 'SECRET_SALT' ) && SECRET_SALT && empty( $duplicated_keys[ SECRET_SALT ] ) ) {
			$values['salt'] = SECRET_SALT;
		}

		if ( in_array( $scheme, array( 'auth', 'secure_auth', 'logged_in', 'nonce' ) ) ) {
			foreach ( array( 'key', 'salt' ) as $type ) {
				$const = strtoupper( "{$scheme}_{$type}" );
				if ( defined( $const ) && constant( $const ) && empty( $duplicated_keys[ constant( $const ) ] ) ) {
					$values[ $type ] = constant( $const );
				} elseif ( ! $values[ $type ] ) {
					$values[ $type ] = get_site_option( "{$scheme}_{$type}" );
					if ( ! $values[ $type ] ) {
						$values[ $type ] = wp_generate_password( 64, true, true );
						update_site_option( "{$scheme}_{$type}", $values[ $type ] );
					}
				}
			}
		} else {
			if ( ! $values['key'] ) {
				$values['key'] = get_site_option( 'secret_key' );
				if ( ! $values['key'] ) {
					$values['key'] = wp_generate_password( 64, true, true );
					update_site_option( 'secret_key', $values['key'] );
				}
			}
			$values['salt'] = hash_hmac( 'md5', $scheme, $values['key'] );
		}

		$cached_salts[ $scheme ] = $values['key'] . $values['salt'];

		/** This filter is documented in wp-includes/pluggable.php */
		return apply_filters( 'salt', $cached_salts[ $scheme ], $scheme );
	}

	/**
	 * @param $key
	 *
	 * @return string
	 */
	private function get_settings_value( $key ) {

		global $wpdb;

		$q = "SELECT option_value FROM $wpdb->options WHERE option_name = 'uncanny_pro_toolkitCourseTimer'";

		$options = $wpdb->get_var($q);

		if ( ! empty( $options ) && '' !== $options ) {

			$options = maybe_unserialize($options);

			foreach ( $options as $option ) {
				if ( in_array( $key, $option, true ) ) {
					return $option['value'];
					break;
				}
			}
		}

		return '';
	}

	/**
	 * Make clone magic method private, so nobody can clone instance.
	 *
	 * @since 1.0.0
	 */
	function __clone() {
	}

	/**
	 * Make sleep magic method private, so nobody can serialize instance.
	 *
	 * @since 1.0.0
	 */
	function __sleep() {
	}

	/**
	 * Make wakeup magic method private, so nobody can unserialize instance.
	 *
	 * @since 1.0.0
	 */
	function __wakeup() {

	}


}

new pseudo_performance_rest_call();