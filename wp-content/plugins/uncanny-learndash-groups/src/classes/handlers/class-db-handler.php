<?php


namespace uncanny_learndash_groups;

/**
 * Class Uncanny_Groups_DB
 * @package uncanny_learndash_groups
 */
class DB_Handler {
	/**
	 * @var
	 */
	public static $instance;
	/**
	 * @var object
	 */
	public $tables;

	/**
	 * @var string
	 */
	public $tbl_group_details = 'ulgm_group_details';
	/**
	 * @var string
	 */
	public $tbl_group_codes = 'ulgm_group_codes';
	/**
	 * @var
	 */
	public $group_management;
	/**
	 * @var
	 */
	public $reports;
	/**
	 * @var
	 */
	public $codes;

	/**
	 * Uncanny_Groups_DB constructor.
	 */
	public function __construct() {
		$this->tables = (object) apply_filters(
			'ulgm_database_tables',
			(object) array(
				'groups' => 'ulgm_group_details',
				'codes'  => 'ulgm_group_codes',
			)
		);
	}


	/**
	 * @return DB_Handler
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 *
	 */
	public function upgrade_db() {

		$db_version = get_option( 'ulgm_database_version', null );
		if ( null !== $db_version && (string) UNCANNY_GROUPS_DB_VERSION === (string) $db_version ) {
			// bail. No db upgrade needed!
			return;
		}

		ulgm()->db->create_tables();

	}

	/**
	 * @param string $table
	 *
	 * @return bool|int
	 */
	public function if_table_exists( $table = '' ) {
		if ( empty( $table ) ) {
			$table = $this->tbl_group_details;
		}
		global $wpdb;
		$qry = "SHOW TABLES LIKE '$wpdb->prefix{$table}';";

		return $wpdb->query( $qry );
	}

	/**
	 *
	 */
	public function create_tables() {
		$sql = $this->get_schema();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$this->remove_deprecated_columns();
		update_option( 'ulgm_database_version', UNCANNY_GROUPS_DB_VERSION );
	}

	/**
	 * @param $db_version
	 */
	public function remove_deprecated_columns() {

		global $wpdb;
		$table   = $this->tbl_group_details;
		$db_name = DB_NAME;
		if ( $wpdb->get_results( "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '$wpdb->prefix{$table}' AND COLUMN_NAME = 'issue_count' AND TABLE_SCHEMA = '$db_name'" ) ) {
			$wpdb->query( "ALTER TABLE $wpdb->prefix{$table} DROP COLUMN `issue_count`" );
		}
		if ( $wpdb->get_results( "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '$wpdb->prefix{$table}' AND COLUMN_NAME = 'used_code' AND TABLE_SCHEMA = '$db_name'" ) ) {
			$wpdb->query( "ALTER TABLE $wpdb->prefix{$table} DROP COLUMN `used_code`" );
		}
		if ( $wpdb->get_results( "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '$wpdb->prefix{$table}' AND COLUMN_NAME = 'issue_max_count' AND TABLE_SCHEMA = '$db_name'" ) ) {
			$wpdb->query( "ALTER TABLE $wpdb->prefix{$table} DROP COLUMN `issue_max_count`" );
		}
		if ( $wpdb->get_results( "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '$wpdb->prefix{$table}' AND COLUMN_NAME = 'group_id' AND TABLE_SCHEMA = '$db_name'" ) ) {
			$wpdb->query( "ALTER TABLE $wpdb->prefix{$table} DROP COLUMN `group_id`" );
		}

		update_option( 'ulgm_database_cols_dropped', UNCANNY_GROUPS_DB_VERSION );

	}

	/**
	 * @return string
	 */
	public function get_schema() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$groups_tbl      = $wpdb->prefix . $this->tbl_group_details;
		$codes_tbl       = $wpdb->prefix . $this->tbl_group_codes;

		return "CREATE TABLE $groups_tbl  (
`ID` bigint(20) NOT NULL AUTO_INCREMENT,
`user_id` bigint(20),
`order_id` bigint(20) NULL,
`ld_group_id` bigint(20) DEFAULT 0,
`group_name` TEXT,
`issue_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`ID`),
KEY user_id (`user_id`),
KEY order_id (`order_id`),
KEY ld_group_id (`ld_group_id`)
) $charset_collate;
CREATE TABLE $codes_tbl (
`ID` bigint(20) NOT NULL AUTO_INCREMENT,
`group_id` bigint(20),
`code` LONGTEXT NOT NULL,
`used_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`student_id` bigint(20),
`user_email` varchar(100),
`code_status` VARCHAR(50) DEFAULT 'available',
`first_name` varchar(100),
`last_name` varchar(100),
PRIMARY KEY (`ID`),
KEY group_id (`group_id`),
KEY student_id (`student_id`),
KEY user_email (`user_email`),
KEY code_status (`code_status`),
INDEX user_nicename (`first_name`, `last_name`)
) $charset_collate;";
	}
}
