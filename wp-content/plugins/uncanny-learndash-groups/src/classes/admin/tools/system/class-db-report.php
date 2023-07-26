<?php

namespace uncanny_learndash_groups\Tools\System;

use uncanny_learndash_groups\DB_Handler;

class DB_Report {

	/**
	 * Check to see if there are any missing tables.
	 *
	 * @return array The missing table names.
	 */
	public function verify_base_tables( $execute = false ) {

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$queries = dbDelta( $this->get_schema(), $execute );

		$missing_tables = array();

		foreach ( $queries as $table_name => $result ) {
			if ( "Created table $table_name" === $result ) {
				$missing_tables[] = $table_name;
			}
		}

		if ( 0 < count( $missing_tables ) ) {
			update_option( 'ulgm_schema_missing_tables', $missing_tables );
		} else {
			update_option( 'ulgm_database_version', UNCANNY_GROUPS_DB_VERSION );
			delete_option( 'ulgm_schema_missing_tables' );
		}

		return $missing_tables;

	}

	/**
	 * Returns the schema of the group tables.
	 *
	 * @return string The schema of 'ulgm_group_codes' and 'ulgm_group_details' tables.
	 */
	protected function get_schema() {

		$db_handler = DB_Handler::get_instance();

		return $db_handler->get_schema();

	}
}
