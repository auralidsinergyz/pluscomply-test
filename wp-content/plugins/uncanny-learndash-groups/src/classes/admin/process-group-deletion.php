<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ProcessManualGroup
 * @package uncanny_learndash_groups
 */
class ProcessGroupDeletion {
	/**
	 * ProcessManualGroup constructor.
	 */
	public function __construct() {
		//remove group related data from custom tables
		add_action( 'after_delete_post', array( $this, 'remove_related_groups_data' ) );
	}

	/**
	 * @param $post_id
	 */
	public function remove_related_groups_data( $post_id ) {
		if ( ! $post_id ) {
			return;
		}

		global $wpdb;

		$group_detail_id = ulgm()->group_management->seat->get_code_group_id( $post_id );

		if ( $group_detail_id ) {
			$wpdb->query( "DELETE FROM {$wpdb->prefix}" . ulgm()->db->tbl_group_details . " WHERE ID={$group_detail_id}" );
			$wpdb->query( "DELETE FROM {$wpdb->prefix}" . ulgm()->db->tbl_group_codes . " WHERE group_id={$group_detail_id}" );
		}
	}

}
