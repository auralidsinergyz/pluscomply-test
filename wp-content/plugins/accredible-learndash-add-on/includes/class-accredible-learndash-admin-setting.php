<?php
/**
 * Accredible LearnDash Add-on admin setting class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Setting' ) ) :
	/**
	 * Accredible LearnDash Add-on admin setting class
	 */
	class Accredible_Learndash_Admin_Setting {
		const OPTION_GROUP       = 'accredible_learndash_settings_group';
		const OPTION_NAME_PREFIX = 'accredible_learndash_';

		// Plugin option names.
		const OPTION_API_KEY       = self::OPTION_NAME_PREFIX . 'api_key';
		const OPTION_SERVER_REGION = self::OPTION_NAME_PREFIX . 'server_region';

		const OPTION_NAMES = array(
			self::OPTION_API_KEY,
			self::OPTION_SERVER_REGION,
		);

		// server_region option values.
		const SERVER_REGION_US = 'us';
		const SERVER_REGION_EU = 'eu';

		/**
		 * Register plugin options to WP options
		 */
		public static function register() {
			foreach ( self::OPTION_NAMES as $option_name ) {
				register_setting( self::OPTION_GROUP, $option_name );
			}
		}

		/**
		 * Delete plugin options from database
		 */
		public static function delete_options() {
			foreach ( self::OPTION_NAMES as $option_name ) {
				delete_option( $option_name );
			}
		}

		/**
		 * Set default values to WP options
		 */
		public static function set_default() {
			if ( get_option( self::OPTION_SERVER_REGION ) === false ) {
				update_option( self::OPTION_SERVER_REGION, self::SERVER_REGION_US );
			}
		}
	}
endif;
