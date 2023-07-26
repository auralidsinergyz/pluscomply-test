<?php
/**
 * Accredible LearnDash Add-on admin database class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Database' ) ) :
	/**
	 * Accredible LearnDash Add-on admin database class
	 */
	class Accredible_Learndash_Admin_Database {
		const PLUGIN_PREFIX  = 'accredible_learndash_';
		const OPTION_VERSION = self::PLUGIN_PREFIX . 'db_version';
		const VERSION        = '1.0';

		// Custom table names.
		const AUTO_ISSUANCES_TABLE_NAME     = self::PLUGIN_PREFIX . 'auto_issuances';
		const AUTO_ISSUANCE_LOGS_TABLE_NAME = self::PLUGIN_PREFIX . 'auto_issuance_logs';
		const TABLE_NAMES                   = array(
			self::AUTO_ISSUANCES_TABLE_NAME,
			self::AUTO_ISSUANCE_LOGS_TABLE_NAME,
		);

		/**
		 * Set up plugin custom DB tables.
		 */
		public static function setup() {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			// Disable `PreparedSQL` since there are no inputs from users.
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			$auto_issuance_create_sql = 'CREATE TABLE ' . $wpdb->prefix . self::AUTO_ISSUANCES_TABLE_NAME . " (
				id int(11) unsigned NOT NULL AUTO_INCREMENT,
				kind varchar(50) NOT NULL,
				post_id bigint(20) unsigned NOT NULL,
				accredible_group_id int(11) unsigned NOT NULL,
				trigger_value varchar(255),
				created_at int(11) unsigned DEFAULT 0 NOT NULL,
				PRIMARY KEY  (id),
				KEY kind (kind),
				KEY post_id (post_id)
			) $charset_collate;";
			dbDelta( $auto_issuance_create_sql );

			$auto_issuance_id             = self::PLUGIN_PREFIX . 'auto_issuance_id';
			$auto_issuance_log_create_sql = 'CREATE TABLE ' . $wpdb->prefix . self::AUTO_ISSUANCE_LOGS_TABLE_NAME . " (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				$auto_issuance_id int(11) unsigned NOT NULL,
				user_id bigint(20) unsigned NOT NULL,
				accredible_group_id int(11) unsigned,
				accredible_group_name varchar(255),
				recipient_name varchar(255),
				recipient_email varchar(255),
				credential_url text,
				error_message text,
				created_at int(11) unsigned DEFAULT 0 NOT NULL,
				PRIMARY KEY  (id),
				KEY $auto_issuance_id ($auto_issuance_id),
				KEY user_id (user_id)
			) $charset_collate;";
			dbDelta( $auto_issuance_log_create_sql );
			// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared

			add_option( self::OPTION_VERSION, self::VERSION );
		}

		/**
		 * Drop all plugin custom DB tables.
		 */
		public static function drop_all() {
			global $wpdb;
			// Disable `PreparedSQL` since there are no inputs from users.
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			foreach ( self::TABLE_NAMES as $table_name ) {
				$drop_sql = 'DROP TABLE IF EXISTS ' . $wpdb->prefix . $table_name;
				$wpdb->query( $drop_sql );
			}
			// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared

			delete_option( self::OPTION_VERSION );
		}
	}
endif;
