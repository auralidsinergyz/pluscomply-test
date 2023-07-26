<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class CSV_User_Upload
 * @since   4.0
 * @package uncanny_learndash_groups
 */
class CSV_User_Upload {

	/**
	 * AJAX action
	 *
	 * @since 4.0
	 * @var string
	 */
	public $action = '';

	/**
	 * Should emails no be sent out
	 *
	 * @since 4.0
	 * @var string
	 */
	public $do_not_send_emails = false;

	/**
	 * Group ID
	 *
	 * @since 4.0
	 * @var int
	 */
	public $group_id = 0;

	/**
	 * A user data object
	 *
	 * @since 4.0
	 * @var array
	 */
	public $user_data = array();

	/**
	 * CSV_User_Upload constructor.
	 */
	public function __construct() {

		// If a single part of data doesn't validate, it dies in the validate_add_user_data function and sends back the validation error

		$this->validate_action();
		$this->validate_permissions();
		$this->maybe_send_emails();
		$this->set_group_id();
		$this->validate_new_users_data();
	}

	/**
	 *
	 */
	private function validate_action() {

		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array( 'add-invite', 'send-enrollment' );

		// Was an action received, and is the actions allowed
		if ( ulgm_filter_has_var( 'action', INPUT_POST ) && in_array( ulgm_filter_input( 'action', INPUT_POST ), $permitted_actions ) ) {

			$this->action = (string) ulgm_filter_input( 'action', INPUT_POST );

		} else {
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}
	}

	/**
	 *
	 */
	private function validate_permissions() {

		// Does the current user have permission
		$permission = apply_filters( 'group_management_add_user_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to add users.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}
	}

	/**
	 *
	 */
	private function validate_group_id() {

	}

	/**
	 *
	 */
	private function maybe_send_emails() {
		// Was do not send emails received and was it checked off
		if ( ulgm_filter_has_var( 'not-send-emails', INPUT_POST ) && 'not-send-emails' === ulgm_filter_input( 'not-send-emails', INPUT_POST ) ) {
			$this->do_not_send_emails = true;
		}
	}

	/**
	 *
	 */
	private function set_group_id() {

		// Was group id received
		if ( ulgm_filter_has_var( 'group-id', INPUT_POST ) ) {

			// is group a valid integer
			if ( ! absint( ulgm_filter_input( 'group-id', INPUT_POST ) ) ) {
				$data['message'] = __( 'Group ID must be a whole number.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$group_leader_id                = get_current_user_id();
			$user_group_ids                 = learndash_get_administrators_group_ids( $group_leader_id, true );
			$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( $group_leader_id, absint( ulgm_filter_input( 'group-id', INPUT_POST ) ), $user_group_ids );

			// is the current user able to administer this group
			if ( false === $can_the_user_manage_this_group ) {
				$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			// Set group ID
			$this->group_id = absint( ulgm_filter_input( 'group-id', INPUT_POST ) );

		} else {
			$data['message'] = __( 'Group ID was not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}
	}

	/**
	 *
	 */
	private function validate_new_users_data() {

		$group_name = get_the_title( $this->group_id );

		// Was first name received
		if ( ulgm_filter_has_var( 'csv-text', INPUT_POST ) && '' !== ulgm_filter_input( 'csv-text', INPUT_POST ) ) {

			// Get CSV from uploaded $_POST
			$csv_array = $this->get_csv( ulgm_filter_input( 'csv-text', INPUT_POST ) );

			$csv_header = array_shift( $csv_array );
			$csv_header = array_map( 'trim', $csv_header );

			if ( empty( $csv_header ) ) {
				$data['message'] = __( 'The CSV file was empty or not in the correct format.', 'uncanny-learndash-groups' );
				$data['error']   = 'csv-header-empty';
				wp_send_json_error( $data );
			}

			if ( empty( $csv_array ) ) {
				$data['message'] = __( 'The CSV file was empty or not in the correct format.', 'uncanny-learndash-groups' );
				$data['error']   = 'csv-rows-empty';
				wp_send_json_error( $data );
			}
		} else {
			$data['message'] = __( 'The CSV file was empty or not in the correct format.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// is user name header set
		if ( ! in_array( 'user_email', $csv_header ) && count( array_keys( $csv_header, 'user_email' ) ) ) {
			$data['message'] = __( 'The first row of the CSV file must contain the column header user_email.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );

		}

		// is first name header set
		if ( ! in_array( 'first_name', $csv_header ) && count( array_keys( $csv_header, 'first_name' ) ) ) {
			$data['message'] = __( 'The first row of the CSV file must contain the column header first_name.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );

		}

		// is last name header set
		if ( ! in_array( 'last_name', $csv_header ) && count( array_keys( $csv_header, 'last_name' ) ) ) {
			$data['message'] = __( 'The first row of the CSV file must contain the column header last_name.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );

		}

		$user_email_key      = array_search( 'user_email', $csv_header );
		$user_first_name_key = array_search( 'first_name', $csv_header );
		$user_last_name_key  = array_search( 'last_name', $csv_header );
		$user_pass_key       = array_search( 'user_pass', $csv_header );
		// check if column exist
		$user_name_key = array_search( 'username', $csv_header );

		// Validate that all emails are properly formatted
		foreach ( $csv_array as $key => $row ) {
			if ( ! is_email( stripcslashes( $row[ $user_email_key ] ) ) ) {
				$data['message'] = sprintf( __( 'An invalid email was detected: %1$s on row %2$s. No users were imported.', 'uncanny-learndash-groups' ), $row[ $user_email_key ], $key + 2 );
				wp_send_json_error( $data );
			}
		}

		$user_data = array();

		foreach ( $csv_array as $row ) {

			if ( false === $user_pass_key || 0 === absint( $user_pass_key ) ) {
				$user_pass = '';
			} elseif ( isset( $row[ $user_pass_key ] ) ) {
				$user_pass = $row[ $user_pass_key ];
			} else {
				$user_pass = '';
			}

			$user_login = isset( $row[ $user_email_key ] ) ? stripcslashes( $row[ $user_email_key ] ) : '';

			if ( false !== $user_name_key ) {
				$user_login = ( isset( $row[ $user_name_key ] ) && '' != $row[ $user_name_key ] ) ? $row[ $user_name_key ] : $user_login;
			}

			$this->user_data[] = array(
				'user_login' => $user_login,
				'user_email' => isset( $row[ $user_email_key ] ) ? stripcslashes( $row[ $user_email_key ] ) : '',
				'first_name' => isset( $row[ $user_first_name_key ] ) ? SharedFunctions::remove_special_character( $row[ $user_first_name_key ] ) : '',
				'last_name'  => isset( $row[ $user_last_name_key ] ) ? SharedFunctions::remove_special_character( $row[ $user_last_name_key ] ) : '',
				'user_pass'  => $user_pass,
				'group_name' => isset( $group_name ) ? $group_name : '',
			);
		}

	}

	/**
	 * Process CSV from file
	 *
	 * @since 1.0.0
	 */
	private function get_csv( $csv_input ) {

		@ini_set( 'auto_detect_line_endings', '1' );
		$csv_input      = str_replace( "\r\n", "\n", $csv_input );
		$csv_input      = str_replace( "\r", "\n", $csv_input );
		$csv_input      = str_getcsv( $csv_input, PHP_EOL );
		$csv_input_temp = array();

		foreach ( $csv_input as $key => $row ) {

			if ( ! empty( $row ) ) {
				$csv_input_temp[] = $row;
			}
		}

		$csv_input = $csv_input_temp;

		unset( $csv_input_temp );

		$delimiter = $this->auto_detect_delimiter( $csv_input );
		foreach ( $csv_input as &$row ) {
			$row = str_getcsv( html_entity_decode( stripcslashes( $row ) ), $delimiter );
		}

		return $csv_input;
	}

	/**
	 * @param $csv_input
	 * @param string $delimiter
	 *
	 * @return string
	 */
	private function auto_detect_delimiter( $csv_input, $delimiter = ',' ) {
		foreach ( $csv_input as $k => $v ) {
			if ( false !== strpos( $v, ';' ) && 0 === preg_match( '/(&#\d+;)/', $v ) ) {
				$delimiter = ';';
				break;
			}
		}

		return $delimiter;
	}
}
