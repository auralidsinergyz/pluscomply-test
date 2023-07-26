<?php


namespace uncanny_learndash_groups;


/**
 * Class LearnDash_Modifications
 * @package uncanny_learndash_groups
 */
class LearnDash_Modifications {
	/**
	 * LearnDash_Modifications constructor.
	 */
	public function __construct() {
		// Added "Seats" column on groups
		add_filter( 'learndash_listing_columns', array( $this, 'add_custom_columns' ), 10, 2 );

		// Add Download keys button
		add_filter( 'post_row_actions', array( $this, 'add_download_keys_on_groups' ), 2000, 2 );
		add_filter( 'page_row_actions', array( $this, 'add_download_keys_on_groups' ), 2000, 2 );

		add_action( 'admin_init', array( $this, 'download_csv_file' ) );
	}

	/**
	 * @param $columns
	 * @param $post_type
	 *
	 * @return mixed
	 */
	public function add_custom_columns( $columns, $post_type ) {
		if ( 'groups' !== $post_type ) {
			return $columns;
		}

		$columns['seat_count'] = array(
			'label'   => sprintf( __( '%s', 'uncanny-learndash-groups' ), ulgm()->group_management->seat->get_per_seat_text( 10 ) ),
			'display' => array( $this, 'show_seat_count' ),
			'after'   => 'title',
		);

		return $columns;
	}

	/**
	 * @param $post_id
	 * @param $column_name
	 */
	public function show_seat_count( $post_id, $column_name ) {
		if ( 'seat_count' !== $column_name ) {
			return;
		}
		$code_group = get_post_meta( $post_id, '_ulgm_code_group_id', true );
		if ( empty( $code_group ) ) {
			echo 'N/A';

			return;
		}
		$total_seats     = ulgm()->group_management->seat->total_seats( $post_id );
		$available_seats = ulgm()->group_management->seat->remaining_seats( $post_id );

		echo "$available_seats / $total_seats";
	}

	/**
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public function add_download_keys_on_groups( $actions, $post ) {
		if ( 'groups' !== $post->post_type ) {
			return $actions;
		}

		$post_type_object = get_post_type_object( $post->post_type );
		$can_edit_post    = current_user_can( $post_type_object->cap->edit_post, $post->ID );
		if ( ! $can_edit_post ) {
			return $actions;
		}

		// Only show if Group Management of the group is enabled
		$code_group = ulgm()->group_management->seat->get_code_group_id( $post->ID );
		if ( empty( $code_group ) ) {
			return $actions;
		}

		$action                   = sprintf( '%s?action=%s&post=%d&_wpnonce=%s', admin_url( 'edit.php' ), 'download_seat_count', $post->ID, wp_create_nonce( 'ulgm' ) );
		$actions['download_keys'] = sprintf( '<a href="%s" title="%s">%s</a>', $action, __( 'Download keys', 'uncanny-learndash-groups' ), __( 'Download keys', 'uncanny-learndash-groups' ) );

		return $actions;
	}

	public function download_csv_file() {
		if ( ! ulgm_filter_has_var( 'action' ) ) {
			return;
		}

		if ( ulgm_filter_has_var( 'action' ) && 'download_seat_count' !== ulgm_filter_input( 'action' ) ) {
			return;
		}

		if ( ! ulgm_filter_has_var( 'post' ) ) {
			return;
		}

		if ( ! ulgm_filter_has_var( '_wpnonce' ) ) {
			return;
		}
		if ( ! wp_verify_nonce( ulgm_filter_input( '_wpnonce' ), 'ulgm' ) ) {
			return;
		}

		$group_id = absint( ulgm_filter_input( 'post' ) );

		$users = apply_filters( 'ulgm_download_users_keys', Group_Management_Helpers::get_unused__key_users_data( $group_id ), $group_id );

		$header = array(
			'header' => array(
				'Group',
				'Key',
			),
		);
		// open raw memory as file so no temp files needed, you might run out of memory though
		$f          = fopen( 'php://memory', 'w' );
		$group_slug = get_post_field( 'post_name', $group_id );
		$file_name  = 'keys-' . $group_slug . '-' . date( 'Y-m-d' );
		$file_name  = apply_filters( 'csv_file_name', $file_name, $group_slug, $group_id, wp_get_current_user()->ID );
		$filename   = "$file_name.csv";
		$delimiter  = ",";
		// loop over the input array
		foreach ( $header as $line ) {
			// generate csv lines from the inner arrays
			fputcsv( $f, $line, $delimiter );
		}
		foreach ( $users as $line ) {
			// generate csv lines from the inner arrays
			fputcsv( $f, $line, $delimiter );
		}
		// reset the file pointer to the start of the file
		rewind( $f );
		// tell the browser it's going to be a csv file
		header( 'Content-Type: application/csv; charset=UTF-8' );
		// tell the browser we want to save it instead of displaying it
		header( 'Content-Disposition: attachment;filename="' . $filename . '";' );
		// make php send the generated csv lines to the browser
		fpassthru( $f );

		die();
	}
}
