<?php

namespace uncanny_learndash_codes;

/**
 * Class Config
 * @package uncanny_learndash_codes
 */
class Config {
	/**
	 * @var string
	 */
	private static $version;
	/**
	 * @var string
	 */
	private static $file;
	/**
	 * @var string
	 */
	private static $basename;
	/**
	 * @var string
	 */
	private static $project_name;
	/**
	 * @var string
	 */
	private static $plugin_dir;
	/**
	 * @var string
	 */
	private static $plugin_url;

	/**
	 * @var string
	 */
	private static $css_prefix;
	/**
	 * @var array
	 */
	private static $available_plugins;
	/**
	 * @var bool
	 */
	private static $caching_on = false;

	/**
	 * @var string
	 */
	public static $tbl_groups = 'uncanny_codes_groups';
	/**
	 * @var string
	 */
	public static $tbl_codes = 'uncanny_codes_codes';
	/**
	 * @var string
	 */
	public static $invalid_code;
	/**
	 * @var string
	 */
	public static $expired_code;
	/**
	 * @var string
	 */
	public static $already_redeemed;
	/**
	 * @var string
	 */
	public static $redeemed_maximum;
	/**
	 * @var string
	 */
	public static $successfully_redeemed;
	/**
	 * @var string
	 */
	public static $allow_multiple_groups;
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_custom_messages = 'uncanny-learndash-codes-setting-custom-messages';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_multiple_groups = 'uncanny-learndash-codes-setting-group-settings';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms = 'uncanny-learndash-codes-setting-form-id';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms_mandatory = 'uncanny-learndash-codes-setting-form-field-mandatory';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms_label = 'uncanny-learndash-codes-setting-form-field-label';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms_error = 'uncanny-learndash-codes-setting-form-field-error';
	/**
	 * @var string
	 */
	public static $uncanny_codes_settings_gravity_forms_placeholder = 'uncanny-learndash-codes-setting-form-field-placeholder';
	/**
	 * @var string
	 */
	public static $uncanny_codes_user_prefix_meta = 'uncanny-learndash-codes-prefix';

	/**
	 * @var string
	 */
	public static $uncanny_codes_tml_template_override = 'uncanny-learndash-codes-tml-override';

	/**
	 * @var string
	 */
	public static $uncanny_codes_tml_codes_required_field = 'uncanny-learndash-codes-tml-required-field';

	/**
	 * @var string
	 */
	public static $uncanny_codes_tracking = 'uncanny-learndash-codes-tracking';
	/**
	 * @var string
	 */
	public static $unpaid_error;

	/**
	 * Config constructor.
	 */
	function __construct() {
		if ( is_multisite() ) {
			$messages = get_blog_option( get_current_blog_id(), self::$uncanny_codes_settings_custom_messages, '' );
		} else {
			$messages = get_option( self::$uncanny_codes_settings_custom_messages, '' );
		}

		if ( ! empty( $messages ) ) {
			if ( ! empty( $messages['invalid-code'] ) ) {
				self::$invalid_code = $messages['invalid-code'];
			} else {
				self::$invalid_code = esc_html__( 'Sorry, the code you entered is not valid.', 'uncanny-learndash-codes' );
			}
			if ( ! empty( $messages['expired-code'] ) ) {
				self::$expired_code = $messages['expired-code'];
			} else {
				self::$expired_code = esc_html__( 'Sorry, the code you entered has expired.', 'uncanny-learndash-codes' );
			}
			if ( ! empty( $messages['already-redeemed'] ) ) {
				self::$already_redeemed = $messages['already-redeemed'];
			} else {
				self::$already_redeemed = esc_html__( 'Sorry, the code you entered has already been redeemed.', 'uncanny-learndash-codes' );
			}
			if ( ! empty( $messages['redeemed-maximum'] ) ) {
				self::$redeemed_maximum = $messages['redeemed-maximum'];
			} else {
				self::$redeemed_maximum = esc_html__( 'Sorry, the code you entered has already been redeemed maximum times.', 'uncanny-learndash-codes' );
			}
			if ( ! empty( $messages['successfully-redeemed'] ) ) {
				self::$successfully_redeemed = $messages['successfully-redeemed'];
			} else {
				self::$successfully_redeemed = esc_html__( 'Congratulations, the code you entered has successfully been redeemed.', 'uncanny-learndash-codes' );
			}
			if ( ! empty( $messages['unpaid-error'] ) ) {
				self::$unpaid_error = $messages['unpaid-error'];
			} else {
				self::$unpaid_error = esc_html__( 'To use this code, please complete your purchase in the store and add your code on the checkout page.', 'uncanny-learndash-codes' );
			}

		} else {
			self::$invalid_code          = esc_html__( 'Sorry, the code you entered is not valid.', 'uncanny-learndash-codes' );
			self::$expired_code          = esc_html__( 'Sorry, the code you entered has expired.', 'uncanny-learndash-codes' );
			self::$already_redeemed      = esc_html__( 'Sorry, the code you entered has already been redeemed.', 'uncanny-learndash-codes' );
			self::$redeemed_maximum      = esc_html__( 'Sorry, the code you entered has already been redeemed maximum times.', 'uncanny-learndash-codes' );
			self::$successfully_redeemed = esc_html__( 'Congratulations, the code you entered has successfully been redeemed.', 'uncanny-learndash-codes' );
			self::$unpaid_error          = esc_html__( 'To use this code, please complete your purchase in the store and add your code on the checkout page.', 'uncanny-learndash-codes' );
		}
		if ( is_multisite() ) {
			$group_settings = get_blog_option( get_current_blog_id(), self::$uncanny_codes_settings_multiple_groups, 0 );
		} else {
			$group_settings = get_option( self::$uncanny_codes_settings_multiple_groups, 0 );
		}
		self::$allow_multiple_groups = $group_settings;
	}

	/**
	 * @return boolean
	 */
	public static function is_caching_on() {
		return self::$caching_on;
	}

	/**
	 * @param $class_names
	 *
	 * @return array
	 */
	public static function set_available_classes( $class_names ) {
		self::$available_plugins = $class_names;
	}

	/**
	 * @return array of class names
	 */
	public static function get_active_classes() {
		if ( ! self::$available_plugins ) {
			if ( is_multisite() ) {
				self::$available_plugins = get_blog_option( get_current_blog_id(), 'uncanny_codes_active_classes', array() );
			} else {
				self::$available_plugins = get_option( 'uncanny_codes_active_classes', array() );
			}
			if ( empty( self::$available_plugins ) ) {
				self::$available_plugins = array();
			}
		}

		return self::$available_plugins;
	}

	/**
	 * @return mixed
	 */
	public static function get_basename() {
		if ( null === self::$basename ) {
			self::$basename = plugin_basename( self::$file );
		}

		return self::$basename;
	}

	/**
	 * @return string
	 */
	public static function get_file() {
		if ( null === self::$file ) {
			self::$file = __FILE__;
		}

		return self::$file;
	}

	/**
	 * @return string
	 */
	public static function get_plugin_dir() {
		if ( null === self::$plugin_dir ) {
			self::$plugin_dir = plugin_dir_path( self::$file );
		}

		return self::$plugin_dir;
	}

	/**
	 * @return string
	 */
	public static function get_plugin_url() {
		if ( null === self::$plugin_url ) {
			self::$plugin_url = plugin_dir_url( self::$file );
		}

		return self::$plugin_url;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_asset( $source = 'frontend', $file_name ) {
		$asset_url = plugins_url( 'assets/' . $source . '/dist/' . $file_name, __FILE__ );

		return $asset_url;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_vendor( $file_name ) {
		$asset_url = plugins_url( 'assets/vendor/' . $file_name, __FILE__ );

		return $asset_url;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_admin_media( $file_name ) {
		$asset_url = plugins_url( 'assets/admin/media/' . $file_name, __FILE__ );

		return $asset_url;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_admin_css( $file_name ) {
		$asset_url = plugins_url( 'assets/admin/css/' . $file_name, __FILE__ );

		return $asset_url;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_admin_js( $file_name ) {
		$asset_url = plugins_url( 'assets/admin/js/' . $file_name, __FILE__ );

		return $asset_url;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_site_media( $file_name ) {
		$asset_url = plugins_url( 'assets/front/media/' . $file_name, __FILE__ );

		return $asset_url;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_site_css( $file_name ) {
		$asset_url = plugins_url( 'assets/front/css/' . $file_name, __FILE__ );

		return $asset_url;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_site_js( $file_name ) {
		$asset_url = plugins_url( 'assets/front/js/' . $file_name, __FILE__ );

		return $asset_url;
	}

	/**
	 * @param string $file_name File name must be prefixed with a \ (foreword slash)
	 * @param mixed $file (false || __FILE__ )
	 *
	 * @return string
	 */
	public static function get_template( $file_name, $file = false ) {

		if ( false === $file ) {
			$file = __FILE__;
		}

		$asset_uri = dirname( $file ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $file_name;

		return $asset_uri;
	}

	/**
	 * @param string $file_name File name must be prefixed with a \ (foreword slash)
	 * @param mixed $file (false || __FILE__ )
	 *
	 * @return string
	 */
	public static function get_include( $file_name, $file = false ) {

		if ( false === $file ) {
			$file = __FILE__;
		}

		$asset_uri = dirname( $file ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . $file_name;

		return $asset_uri;
	}

	/**
	 * @return string
	 */
	public static function get_project_name() {
		if ( null === self::$project_name ) {
			self::$project_name = 'uncanny_learndash_codes';
		}

		return self::$project_name;
	}

	/**
	 * @param $project_name
	 */
	public static function set_project_name( $project_name ) {
		self::$project_name = $project_name;
	}

	/**
	 * @return string
	 */
	public static function get_prefix() {
		return self::get_project_name() . '_';
	}

	/**
	 * @return string
	 */
	public static function get_css_prefix() {
		if ( null === self::$css_prefix ) {
			self::$css_prefix = str_replace( '_', '-', self::get_prefix() );
		}

		return self::$css_prefix;
	}

	/**
	 * @return string
	 */
	public static function _get_prefix() {
		return '_' . self::get_prefix();
	}

	/**
	 * @return string
	 */
	public static function get_namespace() {
		return self::get_project_name();
	}

	/**
	 * @return string
	 */
	public static function get_date_formant() {
		return 'y/m/d g:i';
	}

	/**
	 * @return string
	 */
	public static function get_version() {
		if ( null === self::$version ) {
			self::$version = '0.1';
		}

		return self::$version;
	}

	/**
	 * @param array $array Array where there is slashes in the key
	 *
	 * @return array
	 */
	public static function stripslashes_deep( $array ) {
		$new_array = array();

		// strip slashes of all keys in array
		foreach ( $array as $key => $content ) {
			$key               = stripslashes( $key );
			$new_array[ $key ] = $content;
		}

		return $new_array;
	}

	public static function log_errors( $message, $label = '' ) {
		error_log( "\n-- $label --\n" . print_r( $message, true ), 3, dirname( dirname( __FILE__ ) ) . '/logs.log' );
	}
}