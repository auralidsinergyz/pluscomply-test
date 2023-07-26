<?php

namespace uncanny_learndash_groups;


/**
 * Class Database
 * @package uncanny_learndash_groups
 */
class Database {
	/**
	 * @var string
	 */
	public static $group_tbl = 'ulgm_group_details';
	/**
	 * @var string
	 */
	public static $group_codes_tbl = 'ulgm_group_codes';

	/**
	 * @return false|int
	 * @deprecated 4.0. Use ulgm()->db->if_table_exists()
	 */
	public static function check_if_table_exists() {

		return ulgm()->db->if_table_exists();
	}


	/**
	 *
	 * @deprecated 4.0
	 */
	public static function create_tables() {
		ulgm()->db->create_tables();
	}

	/**
	 * @param $attr
	 * @param $codes
	 *
	 * @return int
	 * @deprecated 4.0
	 */
	public static function add_codes( $attr, $codes ) {

		return ulgm()->group_management->add_codes( $attr, $codes );
	}


	/**
	 * @param $attr
	 * @param $codes
	 *
	 * @return bool|int
	 * @deprecated 4.0
	 */
	public static function add_additional_codes( $attr, $codes ) {

		return ulgm()->group_management->add_additional_codes( $attr, $codes );
	}

	/**
	 * @return int
	 * @deprecated 4.0
	 */
	public static function get_random_order_number() {

		return ulgm()->group_management->get_random_order_number();
	}

}
