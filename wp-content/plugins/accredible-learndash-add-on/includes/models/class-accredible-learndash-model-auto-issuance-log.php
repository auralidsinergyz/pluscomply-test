<?php
/**
 * Accredible LearnDash Add-on auto issuance log model class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-model.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-database.php';

if ( ! class_exists( 'Accredible_Learndash_Model_Auto_Issuance_Log' ) ) :
	/**
	 * Accredible LearnDash Add-on auto issuance log model class
	 */
	class Accredible_Learndash_Model_Auto_Issuance_Log extends Accredible_Learndash_Model {
		/**
		 * Define the DB table name.
		 */
		protected static function table_name() {
			global $wpdb;
			return $wpdb->prefix . Accredible_Learndash_Admin_Database::AUTO_ISSUANCE_LOGS_TABLE_NAME;
		}
	}
endif;
