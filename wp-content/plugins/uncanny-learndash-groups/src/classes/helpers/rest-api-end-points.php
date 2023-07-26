<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
use WP_Error;
use WP_REST_Request;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class AdminMenu
 *
 * @package uncanny_learndash_groups
 */
class RestApiEndPoints {

	/**
	 * class constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
		add_action(
			'wp_ajax_get_group_management_users',
			array(
				__CLASS__,
				'datatable_qrys',
			),
			99
		);
	}

	/**
	 * @param false $all
	 * @param false $group_id_
	 *
	 * @return array|mixed|void
	 */
	public static function datatable_qrys( $all = false, $group_id_ = false ) {

		$group_id  = 0;
		$draw      = 1;
		$start     = 0;
		$length    = 99999;
		$direction = 'asc';
		$order_by  = 'first_name';
		$search    = '';

		if ( ulgm_filter_has_var( 'group' ) ) {
			$group_id = absint( ulgm_filter_input( 'group' ) );
		}
		if ( absint( $group_id_ ) ) {
			$group_id = absint( $group_id_ );
		}
		if ( ulgm_filter_has_var( 'draw' ) ) {
			$draw = absint( ulgm_filter_input( 'draw' ) );
		}
		if ( ulgm_filter_has_var( 'start' ) ) {
			$start = absint( ulgm_filter_input( 'start' ) );
		}
		if ( ulgm_filter_has_var( 'length' ) ) {
			$length = absint( ulgm_filter_input( 'length' ) );
		}

		if ( ulgm_filter_has_var( 'order' ) ) {

			// We are not supporting multiple column ordering.
			$order       = ulgm_filter_input( 'order' );
			$first_order = array_shift( $order );
			if ( $first_order ) {

				$column    = $first_order['column'];
				$direction = $first_order['dir'];

				if ( ulgm_filter_has_var( 'columns' ) && isset( ulgm_filter_input( 'columns' )[ $column ] ) ) {
					$order_by = esc_attr( ulgm_filter_input( 'columns' )[ $column ]['data'] );
				}

				switch ( $order_by ) {
					case 'first_name':
						$order_by = 'first_name';
						break;
					case 'last_name':
						$order_by = 'last_name';
						break;
					case 'email':
						$order_by = 'email';
						break;
					case 'status':
						$order_by = 'status';
						break;
					case 'key':
						$order_by = 'key';
						break;
					default:
						$order_by = 'first_name';
						break;
				}
			}
		}

		if ( ulgm_filter_has_var( 'search' ) && ! empty( ulgm_filter_input( 'search' ) ) && isset( ulgm_filter_input( 'search' )['value'] ) && ! empty( ulgm_filter_input( 'search' )['value'] ) ) {
			$search = esc_attr( ulgm_filter_input( 'search' )['value'] );
		}
		if ( ! empty( $search ) ) {
			$start  = 0;
			$length = 999999;
		}

		$args = array(
			'start'   => $start,
			'length'  => $length,
			'order'   => $direction,
			'orderby' => $order_by,
			'search'  => $search,
		);

		$data = GroupManagementInterface::set_enrolled_users_data( $group_id, $args );

		$total_users = LearndashFunctionOverrides::get_group_id_user_count( $group_id, $args );
		$count       = ! empty( $total_users ) ? $total_users : 0;
		$return      = array(
			'draw'            => $draw,
			'recordsTotal'    => $count,
			'recordsFiltered' => $count,
			'data'            => $data,
		);
		if ( $all ) {
			return $data;
		}
		echo wp_json_encode( $return );
		die();
	}

	/**
	 * Rest API Custom Endpoints
	 *
	 * @since   1.0
	 * @version 3.7 - Added permission_callback for WP 5.5
	 */
	public static function register_routes() {

		register_rest_route(
			ULGM_REST_API_PATH,
			'/add_user/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'add_user' ),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/add_group_leader/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'add_group_leader' ),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/remove_group_leaders/',
			array(
				'methods'             => 'POST',
				'callback'            => array(
					__CLASS__,
					'remove_group_leaders',
				),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/upload_users/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'upload_users' ),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/email_users/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'email_users' ),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/remove_users/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'remove_users' ),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/download_keys_csv/',
			array(
				'methods'             => 'POST',
				'callback'            => array(
					__CLASS__,
					'download_keys_csv',
				),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/send-password-reset/',
			array(
				'methods'             => 'POST',
				'callback'            => array(
					__CLASS__,
					'send_password_reset',
				),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/get_user_details/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'get_user_details' ),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/edit_user/',
			array(
				'methods'             => 'POST',
				'callback'            => array(
					__CLASS__,
					'update_user_details',
				),
				'permission_callback' => function () {
					return self::permission_callback_check();
				},
			)
		);

		register_rest_route(
			ULGM_REST_API_PATH,
			'/try_automator_visibility/',
			array(
				'methods'             => 'POST',
				'callback'            => array(
					__CLASS__,
					'try_automator_rest_callback',
				),
				'permission_callback' => function () {
					return true;
				},
			)
		);
	}

	/**
	 * Check permission of a current logged in user for rest_api call
	 *
	 * @param bool $admin_only
	 *
	 * @since 3.7
	 * @return bool|WP_Error
	 *
	 */
	public static function permission_callback_check( $admin_only = false ) {
		if ( ! is_user_logged_in() ) {
			return new WP_Error( 'ulgm_rest_cannot_view', __( 'Sorry, you cannot view this resource.', 'uncanny-learndash-groups' ), array( 'status' => rest_authorization_required_code() ) );
		}

		if ( $admin_only ) {
			return current_user_can( 'manage_options' );
		}

		$user          = wp_get_current_user();
		$allowed_roles = apply_filters(
			'ulgm_rest_api_callback_roles',
			array(
				'administrator',
				'group_leader',
			)
		);
		if ( array_intersect( $allowed_roles, $user->roles ) ) {
			return true;
		}

		return new WP_Error( 'ulgm_rest_cannot_view', __( 'Sorry, you cannot view this resource.', 'uncanny-learndash-groups' ), array( 'status' => rest_authorization_required_code() ) );
	}

	/**
	 * Adds a user automatically or sends out a redemption code
	 *
	 * @since 1.0
	 */
	public static function add_user( WP_REST_Request $request ) {
		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array(
			'add-invite',
			'send-enrollment',
			'add-existing-user',
		);

		// Was an action received, and is the actions allowed
		if ( $request->has_param( 'action' ) && in_array( $request->get_param( 'action' ), $permitted_actions ) ) {

			$action = (string) $request->get_param( 'action' );

		} else {
			$action          = '';
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$permission = apply_filters( 'group_management_add_user_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to add users.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// If a single part of data doesn't validate, it dies in the validate_add_user_data function and sends back the validation error
		$user_data = self::validate_new_user_data( $action, $request );
		//returns true or false if group leader allowed to add user
		//wp_get_current_user() = (object) of current logged in user details
		//(int) $request->get_param( 'group-id' ) = (int) LD Group ID
		//$user_data = (object) of user being added to group as user
		$ulgm_gdpr_compliance = apply_filters( 'ulgm_gdpr_is_group_leader_allowed', true, wp_get_current_user(), (int) $request->get_param( 'group-id' ), (object) $user_data, $request->get_param( 'action' ) );

		// Add user and send out welcome email
		if ( $ulgm_gdpr_compliance && 'add-invite' === $action ) {

			$email = $user_data['user_email'];

			if ( email_exists( $email ) || username_exists( $email ) ) {
				// Add existing user and send out welcome email
				$user_id              = email_exists( $email );
				$user_data['user_id'] = $user_id;
				Group_Management_Helpers::add_existing_user( $user_data );

			} else {
				// Add the user and send out a welcome email
				Group_Management_Helpers::add_invite_user( $user_data, false );
			}
		}

		// Send enrollment key
		if ( $ulgm_gdpr_compliance && 'send-enrollment' === $action ) {

			// User invitation email subject
			$ulgm_invitation_user_email_subject = SharedVariables::user_invitation_email_subject();

			// User invitation email body
			$ulgm_invitation_user_email_body = SharedVariables::user_invitation_email_body();
			// Add user to invitation list and send out email
			Group_Management_Helpers::send_redemption_email( $user_data, $ulgm_invitation_user_email_subject, $ulgm_invitation_user_email_body );

		}

	}

	/**
	 * Adds a user automatically or sends out a redemption code
	 *
	 * @since 1.0
	 */
	public static function upload_users( WP_REST_Request $request ) {

		// Licensing is not autoloaded, load manually
		include_once Utilities::get_include( 'class-csv-user-upload.php' );

		// Does initial validation and returns user data
		$csv_user_upload = new CSV_User_Upload();

		//returns true or false if group leader allowed to add user
		//wp_get_current_user() = (object) of current logged in user details
		//(int) $request->get_param( 'group-id' ) = (int) LD Group ID
		//$user_data = (object) of user being uploaded to group as user
		$ulgm_gdpr_compliance = apply_filters( 'ulgm_gdpr_is_group_leader_allowed', true, wp_get_current_user(), (int) $request->get_param( 'group-id' ), (object) $csv_user_upload->user_data, 'upload-users' );
		// Add user and send out welcome email

		$data['endpoint'] = 'upload_users';

		$uploaded_users = absint( $request->get_param( 'uploaded_users' ) );

		// @ 1 = 0
		$upload_start_rows = 0;
		if ( 1 !== $uploaded_users ) {
			// @ 2 = 10 *2 x 10 - 10*
			// @ 3 = 20 *3 x 10 - 10*
			// @ 4 = 30 *4 x 10 - 10*
			$upload_start_rows = ( $uploaded_users * 10 ) - 10;
		}

		// @ 1 = 9
		$upload_end_rows = 9;
		if ( 1 !== $upload_end_rows ) {
			// @ 2 = 19 *2 x 10 - 1*
			// @ 3 = 29 *3 x 10 - 1*
			// @ 4 = 39 *4 x 10 - 1*
			$upload_end_rows = ( $uploaded_users * 10 ) - 1;
		}

		// @ 1 between 0 && 9
		// @ 2 between 10 && 19
		// @ 3 between 20 && 29
		// @ 4 between 30 && 39

		// @ 4 IS 34 *count($user_data)*  <=  @ 39 *$upload_end_rows*
		$is_last_loop = false;
		if ( count( $csv_user_upload->user_data ) <= $upload_end_rows + 1 ) {
			$is_last_loop = true;
		}

		if ( $ulgm_gdpr_compliance && 'add-invite' === $csv_user_upload->action && $request->has_param( 'uploaded_users' ) && 0 !== absint( $request->get_param( 'uploaded_users' ) ) ) {

			$results                = array();
			$data['total_rows']     = count( $csv_user_upload->user_data );
			$data['reload']         = false;
			$data['action']         = 'add-invite';
			$data['uploaded_users'] = $uploaded_users;

			// Add the user and send out a welcome email
			foreach ( $csv_user_upload->user_data as $row_num => $user ) {

				if ( $row_num >= $upload_start_rows && $row_num <= $upload_end_rows ) {

					$success = Group_Management_Helpers::add_invite_user( $user, $csv_user_upload->do_not_send_emails, true );

					if ( isset( $success['error-code'] ) ) {
						$results[ $row_num ]['type']    = 'error';
						$results[ $row_num ]['message'] = $success['error-code'];
						$results[ $row_num ]['email']   = $user['user_email'];
						$results[ $row_num ]['status']  = $success['status'];
					}

					if ( isset( $success['success-code'] ) ) {
						$results[ $row_num ]['type']    = 'success';
						$results[ $row_num ]['message'] = $success['success-code'];
						$results[ $row_num ]['email']   = $user['user_email'];
						$results[ $row_num ]['status']  = $success['status'];
					}
				}
			}

			if ( $is_last_loop ) {

				$data['message']   = __( 'CSV uploaded and executed successfully.', 'uncanny-learndash-groups' );
				$data['completed'] = true;

				//Hook for LD Notification -- send notification for each course in group
				$group_id = (int) $request->get_param( 'group-id' );
				SharedFunctions::delete_transient( null, $group_id );
				$do_ld_group_postdata_updated = apply_filters( 'do_ld_group_postdata_filter', false, $group_id );
				if ( $do_ld_group_postdata_updated ) {
					$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrator_ids( $group_id );
					$group_users   = LearndashFunctionOverrides::learndash_get_groups_user_ids( $group_id );
					$group_courses = LearndashFunctionOverrides::learndash_get_groups_courses_ids( $group_id );
					do_action( 'ld_group_postdata_updated', $group_id, $group_leaders, $group_users, $group_courses );
				}
			} else {
				$data['message']   = 'Imported ' . ( $upload_end_rows + 1 ) . ' of ' . count( $csv_user_upload->user_data ) . ' rows';
				$data['completed'] = false;
			}

			$data['results'] = array_values( $results );

			wp_send_json_success( $data );

		}

		// Send enrollment key
		if ( $ulgm_gdpr_compliance && 'send-enrollment' === $csv_user_upload->action && $request->has_param( 'uploaded_users' ) && 0 !== absint( $request->get_param( 'uploaded_users' ) ) ) {

			$results                = array();
			$data['reload']         = false;
			$data['total_rows']     = count( $csv_user_upload->user_data );
			$data['action']         = 'send-enrollment';
			$data['uploaded_users'] = $uploaded_users;

			// User invitation email subject
			$ulgm_invitation_user_email_subject = SharedVariables::user_invitation_email_subject();

			// User invitation email body
			$ulgm_invitation_user_email_body = SharedVariables::user_invitation_email_body();

			// Add user to invitation list and send out email
			foreach ( $csv_user_upload->user_data as $row_num => $user ) {

				if ( $row_num >= $upload_start_rows && $row_num <= $upload_end_rows ) {

					if ( email_exists( $user['user_email'] ) || username_exists( $user['user_email'] ) ) {
						// Add existing user and send out welcome email
						$user_id              = email_exists( $user['user_email'] );
						$user_data['user_id'] = $user_id;
						$success              = Group_Management_Helpers::add_existing_user( $user_data, $csv_user_upload->do_not_send_emails, 0, 0, 'not-redeemed', false, false, true );

					} else {
						$success = Group_Management_Helpers::send_redemption_email( $user, $ulgm_invitation_user_email_subject, $ulgm_invitation_user_email_body, $csv_user_upload->do_not_send_emails, true );
					}
					if ( isset( $success['error-code'] ) ) {
						$results[ $row_num ]['type']    = 'error';
						$results[ $row_num ]['message'] = $success['error-code'];
						$results[ $row_num ]['email']   = $user['user_email'];
						$results[ $row_num ]['status']  = $success['status'];
					}

					if ( isset( $success['success-code'] ) ) {
						$results[ $row_num ]['type']    = 'success';
						$results[ $row_num ]['message'] = $success['success-code'];
						$results[ $row_num ]['email']   = $user['user_email'];
						$results[ $row_num ]['status']  = $success['status'];
					}
				}
			}

			if ( $is_last_loop ) {
				$data['message']   = __( 'CSV uploaded and executed successfully.', 'uncanny-learndash-groups' );
				$data['completed'] = true;
			} else {
				$data['message']   = 'Imported ' . ( $upload_end_rows + 1 ) . ' of ' . count( $csv_user_upload->user_data ) . ' rows';
				$data['completed'] = false;
			}

			$data['results'] = array_values( $results );

			wp_send_json_success( $data );
		}
	}

	/**
	 * sends out emails to all group users
	 *
	 * @since 2.6
	 */
	public static function email_users( WP_REST_Request $request ) {
		if ( ! $request->has_param( 'group_email_nonce' ) || ! $request->has_param( 'group_email_group_id' ) || empty( $request->get_param( 'group_email_group_id' ) ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}
		$current_user  = wp_get_current_user();
		$allowed_roles = apply_filters(
			'ulgm_rest_api_callback_roles',
			array(
				'administrator',
				'group_leader',
			)
		);
		if ( ! array_intersect( $allowed_roles, $current_user->roles ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: You are not allowed to send emails.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$group_email_data = array();
		if ( ! $request->has_param( 'group_email_group_id' ) || empty( $request->get_param( 'group_email_group_id' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: Invalid group.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$group_email_data['statuses'] = $request->has_param( 'group_email_status' ) ? array_map( 'sanitize_text_field', $request->get_param( 'group_email_status' ) ) : array();
		if ( ! $request->has_param( 'group_email_status' ) || empty( $request->get_param( 'group_email_status' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: No status selected.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$group_email_data['courses'] = $request->has_param( 'group_email_courses' ) ? array_map( 'intval', $request->get_param( 'group_email_courses' ) ) : array();
		if ( ! $request->has_param( 'group_email_courses' ) || empty( $request->get_param( 'group_email_courses' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: No courses selected.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$group_id                     = absint( $request->get_param( 'group_email_group_id' ) );
		$group_email_data['group_id'] = $group_id;
		if ( ! $request->has_param( 'group_email_sub' ) || empty( $request->get_param( 'group_email_sub' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: Email subject required.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$group_email_data['email_subject'] = wp_strip_all_tags( stripcslashes( $request->get_param( 'group_email_sub' ) ) );
		if ( ! $request->has_param( 'group_email_text' ) || empty( $request->get_param( 'group_email_text' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: Email message required.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$group_email_data['email_message'] = wpautop( stripcslashes( $request->get_param( 'group_email_text' ) ) );
		if (
			! $request->has_param( 'group_email_nonce' )
			|| empty( $request->get_param( 'group_email_nonce' ) )
			|| ! wp_verify_nonce( $request->get_param( 'group_email_nonce' ), 'group_email_nonce_' . $group_email_data['group_id'] . '_' . $current_user->ID )
		) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: Please refresh page and try again.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$group_admin_ids = LearndashFunctionOverrides::learndash_get_groups_administrator_ids( $group_email_data['group_id'] );
		$group_admin_ids = array_map( 'absint', $group_admin_ids );
		if ( ! in_array( $current_user->ID, $group_admin_ids, true ) && ! current_user_can( 'administrator' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: You are not the Group Leader of this group.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$ulgm_email_from      = SharedFunctions::ulgm_group_management_email_users_from_email( $group_id );
		$ulgm_email_from_name = SharedFunctions::ulgm_group_management_email_users_from_name( $group_id );

		$mail_args = array(
			'to'          => sanitize_email( $current_user->user_email ),
			'subject'     => $group_email_data['email_subject'],
			'message'     => wpautop( $group_email_data['email_message'] ),
			'attachments' => '',
			'headers'     => array(
				'From: ' . $ulgm_email_from_name . ' <' . $ulgm_email_from . '>',
				'Reply-to: ' . $ulgm_email_from_name . ' <' . $ulgm_email_from . '>',
			),
		);

		$group_leader_details['from_name']   = $ulgm_email_from_name;
		$group_leader_details ['from_email'] = $ulgm_email_from;
		if ( $request->has_param( 'reply_to_email' ) && ! empty( $request->get_param( 'reply_to_email' ) ) ) {
			$group_leader_details ['reply_to'] = sanitize_email( $request->get_param( 'reply_to_email' ) );
		}
		// Added on a request.
		$group_email_data['group_name'] = get_the_title( $group_email_data['group_id'] );

		if ( empty( $group_email_data['statuses'] ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'No status selected.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$email_addresses    = array();
		$not_enrolled_users = array();

		// Collect non-enrolled user email address and temp user info
		if ( in_array( 'not-enrolled', $group_email_data['statuses'], true ) ) {
			global $wpdb;
			$codes_group_id = ulgm()->group_management->seat->get_code_group_id( absint( $group_email_data['group_id'] ) );

			if ( absint( $codes_group_id ) ) {
				$temp_users_code = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}ulgm_group_codes WHERE group_id = %d AND code_status LIKE %s AND student_id IS NULL",
						$codes_group_id,
						SharedFunctions::$not_redeemed_status
					)
				);
				if ( $temp_users_code ) {
					foreach ( $temp_users_code as $user ) {
						$f     = $user->first_name;
						$l     = $user->last_name;
						$email = sanitize_email( $user->user_email );

						$not_enrolled_users[ $email ] = (object) array(
							'first_name' => $f,
							'last_name'  => $l,
							'email'      => $email,
						);

						$email_addresses[] = $email;
					}
				}
			}
		}

		$group_user_ids = LearndashFunctionOverrides::learndash_get_groups_user_ids( $group_email_data['group_id'] );
		if ( in_array( intval( '-1' ), $group_email_data['courses'], true ) ) {
			$learndash_group_enrolled_courses = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_email_data['group_id'] );
		} else {
			$learndash_group_enrolled_courses = $group_email_data['courses'];
		}
		if ( empty( $group_user_ids ) && empty( $not_enrolled_users ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'No users in group.', 'uncanny-learndash-groups' ),
				)
			);
		}
		$group_user_ids = array_map( 'absint', $group_user_ids );
		foreach ( $group_user_ids as $user_id ) {
			$user = get_user_by( 'id', $user_id );
			if ( ! $user instanceof \WP_User ) {
				continue;
			}
			// Default is not completed
			$completed = false;

			// Default progress
			$in_progress = false;

			// Check group progress courses
			foreach ( $learndash_group_enrolled_courses as $course_id ) {

				$course_progress = learndash_course_progress(
					array(
						'course_id' => $course_id,
						'user_id'   => $user->ID,
						'array'     => true,
					)
				);
				// does the groups course and user progress
				if ( empty( $course_progress ) || ! is_array( $course_progress ) || ( is_array( $course_progress ) && 0 === (int) $course_progress['completed'] && 0 === (int) $course_progress['percentage'] ) ) {
					$in_progress = false;
					$completed   = false;
				} elseif ( is_array( $course_progress ) && (int) $course_progress['completed'] === (int) $course_progress['total'] ) {
					$completed = true;
				} elseif ( is_array( $course_progress ) && (int) $course_progress['total'] !== (int) $course_progress['completed'] ) {
					$in_progress = true;
					$completed   = false;
					break;
				}
			}

			// Set Status
			if ( $completed ) {
				$status = 'completed';
			} elseif ( $in_progress ) {
				$status = 'in-progress';
			} else {
				$status = 'not-started';
			}

			// removing from bcc.
			if ( in_array( $status, $group_email_data['statuses'], true ) ) {
				$email_addresses[] = sanitize_email( $user->user_email );
			}
		}

		add_action(
			'wp_mail_failed',
			function ( $mail_error ) {
				global $group_email_error;
				$group_email_error = $mail_error;
			}
		);

		$mail_errors   = array();
		$mail_success  = array();
		$backup_emails = $email_addresses;

		if ( empty( $email_addresses ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'No users in group.', 'uncanny-learndash-groups' ),
				)
			);
		}

		$mail_args = apply_filters( 'ld_group_email_users_args', $mail_args );
		if ( empty( $mail_args ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Mail args empty. Unexpected condition from filter: ld_group_email_users_args.', 'uncanny-learndash-groups' ),
				)
			);
		}

		do_action( 'ld_group_email_users_before', $mail_args );

		foreach ( $email_addresses as $k => $email_address ) {

			if ( isset( $not_enrolled_users[ $email_address ] ) ) {
				$user_data = $not_enrolled_users[ $email_address ];
			} else {
				$user_data = get_user_by( 'email', $email_address );
			}

			/**
			 * New filters added for email subject and body.
			 *
			 * @since 3.5.1
			 *
			 */
			$mail_args_subject    = apply_filters( 'ld_group_email_users_personalize_subject', $mail_args['subject'], $user_data, $group_email_data );
			$mail_args_message    = apply_filters( 'ld_group_email_users_personalize_message', $mail_args['message'], $user_data, $group_email_data );
			$ulgm_gdpr_compliance = apply_filters( 'ulgm_gdpr_is_group_leader_allowed', true, wp_get_current_user(), (int) $group_email_data['group_id'], $user_data, 'email-users' );

			if ( ! $ulgm_gdpr_compliance ) {
				$mail_errors[] = $email_address;
			} else {
				$send_mail = apply_filters( 'ulgm_maybe_send_group_email', true, $email_address, $mail_args_subject );
				if ( $send_mail ) {
					$mail_ret        = SharedFunctions::wp_mail(
						$email_address,
						$mail_args_subject,
						$mail_args_message,
						Group_Management_Helpers::get_headers( true, $group_leader_details, $group_id )
					);
					$mail_args['to'] = $email_address;
					do_action( 'ld_group_email_users_after', $mail_args, $mail_ret );

					unset( $backup_emails[ $k ] );

					if ( ! $mail_ret ) {
						$mail_errors[] = $email_address;
					} else {
						$mail_success[] = $email_address;
					}
				}
			}
		}

		if ( ! empty( $mail_errors ) ) {
			wp_send_json_error(
				array(
					// translators: Email errors
					'message' => sprintf( __( 'Error: Email(s) to %s not sent. Please try again or check with your hosting provider.', 'uncanny-learndash-groups' ), join( ', ', $mail_errors ) ),
				)
			);
		}

		wp_send_json_success(
			array(
				// translators: Number of successful emails sent
				'message' => sprintf( __( 'Success: Email sent to %d group users.', 'uncanny-learndash-groups' ), count( $mail_success ) ),
			)
		);
	}

	/**
	 * Remove users from group
	 *
	 * @since 1.0.0
	 */
	public static function remove_users( WP_REST_Request $request ) {

		$group_leader_id = get_current_user_id();

		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array( 'remove-users' );

		// Was an action received, and is the actions allowed
		if ( $request->has_param( 'action' ) && in_array( $request->get_param( 'action' ), $permitted_actions ) ) {

			$action = (string) $request->get_param( 'action' );

		} else {
			$action          = '';
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$permission = apply_filters( 'group_management_add_user_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to remove users.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Was group id received
		if ( $request->has_param( 'group-id' ) ) {

			// is group a valid integer
			if ( ! absint( $request->get_param( 'group-id' ) ) ) {
				$data['message'] = __( 'Group ID must be a whole number.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$user_group_ids                 = LearndashFunctionOverrides::learndash_get_administrators_group_ids( $group_leader_id );
			$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( $group_leader_id, absint( $request->get_param( 'group-id' ) ), $user_group_ids );
			// is the current user able to administer this group
			if ( false === $can_the_user_manage_this_group ) {
				$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$group_id = absint( $request->get_param( 'group-id' ) );

		} else {
			$data['message'] = __( 'Group ID was not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		if ( 'remove-users' === $action ) {

			if ( $request->has_param( 'removing-users' ) ) {

				$users = explode( ',', esc_html( $request->get_param( 'removing-users' ) ) );

				$temp_user_keys = array();
				$user_ids       = array();

				foreach ( $users as $user_id ) {

					if ( ! is_numeric( $user_id ) ) {
						// not a really user, we use the code as the user id if the user is not in the system... codes are always alphanumberic
						$temp_user_keys[] = ctype_alnum( $user_id ) ? $user_id : false;
					} else {
						// real user
						$user_ids[] = absint( $user_id );
					}
				}

				// Remove Temp users
				foreach ( $temp_user_keys as $code ) {
					ulgm()->group_management->remove_sign_up_code( $code, $group_id, true );
				}

				// Remove real users from the groups
				foreach ( $user_ids as $user_id ) {
					$status = SharedFunctions::get_user_current_progress_in_group( $user_id, $group_id );
					if ( SharedFunctions::$not_started_status === $status || 'yes' === get_option( 'allow_to_remove_users_anytime', 'no' ) ) {
						$code = ulgm()->group_management->get_user_code( $user_id, $group_id, true );
						if ( $code ) {
							foreach ( $code as $c ) {
								ulgm()->group_management->remove_sign_up_code( $c, $group_id, true );
							}
						}
					}

					// set all
					//learndash_set_users_group_ids( $user_id, $current_user_groups );
					ld_update_group_access( $user_id, $group_id, true );
					// remove the group membership role if no longer a member of any groups
					do_action( 'uo-groups-role-cleanup', $user_id ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
					do_action( 'ulgm_group_user_removed', $user_id, (int) $group_id );
				}

				$data['message'] = _n(
					'User is removed.',
					'Users have been removed.',
					count( $users ),
					'uncanny-learndash-groups'
				);
				SharedFunctions::delete_transient( null, $group_id );
				//Remove user from group
				wp_send_json_success( $data );

			} else {
				$data['message'] = __( 'Users where not received.', 'uncanny-learndash-groups' );
				wp_send_json_error( $data );
			}
		}

	}

	/**
	 * Adds a user automatically or sends out a redemption code
	 *
	 * @since 1.0
	 */
	public static function add_group_leader( WP_REST_Request $request ) {

		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array( 'add-leader' );

		// Was an action received, and is the actions allowed
		if ( $request->has_param( 'action' ) && in_array( $request->get_param( 'action' ), $permitted_actions ) ) {

			$action = (string) $request->get_param( 'action' );

		} else {
			$action          = '';
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$permission = apply_filters( 'group_management_add_group_leader_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to add group leaders.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// If a single part of data doesn't validate, it dies and sends back the validation error
		$user_data   = self::validate_new_user_data( $action, $request );
		$ld_group_id = (int) $request->get_param( 'group-id' );
		$order_id    = SharedFunctions::get_order_id_from_group_id( $ld_group_id );
		//returns true or false if group leader allowed to add user
		//wp_get_current_user() = (object) of current logged in user details
		//(int) $request->get_param( 'group-id' ) = (int) LD Group ID
		//$user_data = (object) of user being added to group as group leader
		$ulgm_gdpr_compliance = apply_filters( 'ulgm_gdpr_is_group_leader_allowed', true, wp_get_current_user(), $ld_group_id, (object) $user_data, $request->get_param( 'action' ) );
		// Add group leader and send out welcome email
		if ( $ulgm_gdpr_compliance && 'add-leader' === $action ) {
			// Add the user and send out a welcome email NOTE group id has already been validate and the script would return error so its safe :)
			$data = Group_Management_Helpers::create_group_leader( $user_data, $ld_group_id, false );
			if ( ! key_exists( 'error', $data ) ) {
				if ( 'yes' !== get_option( 'do_not_add_group_leader_as_member', 'no' ) ) {
					$user = get_user_by( 'email', $user_data['user_email'] );
					if ( 'no' === ulgm()->group_management->is_user_already_member_of_group( $user->ID, $ld_group_id ) ) {
						$user_data['user_id'] = $user->ID;

						//if ( 'yes' !== get_option( 'do_not_add_group_leader_as_member', 'no' ) ) {
						Group_Management_Helpers::add_existing_user( $user_data, true, $ld_group_id, $order_id, SharedFunctions::$redeem_status, false, false, false, true );
						//}

						SharedFunctions::delete_transient( null, $ld_group_id );
					}
				}

				do_action( 'ulgm_group_leader_added', $user_data, $ld_group_id, $order_id );

				wp_send_json_success( $data );
			} else {
				wp_send_json_error( $data );
			}
		}

	}

	/**
	 * Adds a user automatically or sends out a redemption code
	 *
	 * @since 1.0
	 */
	public static function remove_group_leaders( WP_REST_Request $request ) {

		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array( 'remove-group-leaders' );

		// Was an action received, and is the actions allowed
		if ( empty( $request->has_param( 'action' ) ) || ! in_array( $request->get_param( 'action' ), $permitted_actions, true ) ) {
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$permission = apply_filters( 'group_management_remove_group_leader_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to remove groups leaders.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}
		$users = explode( ',', $request->get_param( 'removing-group-leaders' ) );
		$users = array_map( 'intval', $users );

		if ( empty( $users ) ) {
			$data['message'] = __( 'You must select at least one group leader to remove.', 'uncanny-learndash-groups' );
			$data['error']   = 'invalid-user-list';
			wp_send_json_error( $data );
		}

		// Was group id received
		if ( $request->has_param( 'group-id' ) ) {

			// is group a valid integer
			if ( ! absint( $request->get_param( 'group-id' ) ) ) {
				$data['message'] = __( 'Group ID must be a whole number.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$user_group_ids                 = LearndashFunctionOverrides::learndash_get_administrators_group_ids( get_current_user_id() );
			$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( get_current_user_id(), absint( $request->get_param( 'group-id' ) ), $user_group_ids );
			// is the current user able to administer this group
			if ( false === $can_the_user_manage_this_group ) {
				$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$group_id = absint( $request->get_param( 'group-id' ) );
			foreach ( $users as $user_id ) {
				ld_update_leader_group_access( $user_id, $group_id, true );
				do_action( 'ulgm_group_leader_removed', $user_id, (int) $group_id );
				if ( has_action( 'uo_groups_role_cleanup' ) || has_action( 'uo-groups-role-cleanup' ) ) {
					do_action_deprecated(
						'uo-groups-role-cleanup',
						array( $user_id ),
						'4.4.1',
						'uo_groups_role_cleanup'
					); // remove the group leader role if no longer a member of any groups
					do_action( 'uo_groups_role_cleanup', $user_id ); // remove the group leader role if no longer a member of any groups
				}
				SharedFunctions::delete_transient( $user_id );
				if ( ! learndash_is_user_in_group( $user_id, $group_id ) ) {
					// Remove group leader code if they have used any
					$code = ulgm()->group_management->get_user_code( $user_id, $group_id, true );
					if ( $code ) {
						foreach ( $code as $c ) {
							ulgm()->group_management->remove_sign_up_code( $c, $group_id, true );
						}
					}
				}
			}

			SharedFunctions::delete_transient( null, $group_id );
			$data['message'] = _n(
				'Group leader is removed.',
				'Group leaders are removed.',
				count( $users ),
				'uncanny-learndash-groups'
			);
			//Remove user from group
			wp_send_json_success( $data );
		} else {
			$data['message'] = __( 'Group ID was not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

	}

	/**
	 * Save email templates for user Send enrollment key, Add and invite email
	 * and Add group leader/Create group email
	 *
	 * @since 1.0.0
	 *
	 */
	public static function download_keys_csv( WP_REST_Request $request ) {

		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array( 'download' );

		// Was an action received, and is the actions allowed
		if ( $request->has_param( 'action' ) && in_array( $request->get_param( 'action' ), $permitted_actions ) ) {

			$action = (string) $request->get_param( 'action' );

		} else {
			$action          = '';
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$allowed_roles = apply_filters(
			'ulgm_gm_allowed_roles',
			array(
				'administrator',
				'group_leader',
				'ulgm_group_management',
			)
		);
		$permission    = apply_filters( 'download_keys_csv_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {
			$data['message'] = __( 'You do not have permission to download csv keys.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		$group_leader_id                = get_current_user_id();
		$user_group_ids                 = LearndashFunctionOverrides::learndash_get_administrators_group_ids( $group_leader_id );
		$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( $group_leader_id, absint( $request->get_param( 'group-id' ) ), $user_group_ids );
		// is the current user able to administer this group
		if ( false === $can_the_user_manage_this_group ) {
			$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
			$data['error']   = 'invalid-group-id';
			wp_send_json_error( $data );
		}

		$group_id = absint( $request->get_param( 'group-id' ) );

		$users = apply_filters( 'ulgm_download_users_keys', Group_Management_Helpers::get_unused__key_users_data( $group_id ), $group_id );
		$csv   = '';

		if ( ! empty( $users ) ) {
			$headers = apply_filters( 'ulgm_download_keys_header', "Group,Key\n", $group_id );
			$csv     .= $headers;
			foreach ( $users as $row ) {
				$csv .= implode( ',', $row ) . "\n";
			}
		} else {
			$headers = apply_filters( 'ulgm_download_keys_header', "Group,Key\n", $group_id );
			$csv     .= $headers;
		}

		// File name
		$group_slug = get_post_field( 'post_name', $group_id );
		$file_name  = 'keys-' . $group_slug . '-' . date( 'Y-m-d' );
		$file_name  = apply_filters( 'csv_file_name', $file_name, $group_slug, $group_id, $group_leader_id );

		/// Trigger file creation to frontend
		$data['reload']        = false;
		$data['call_function'] = 'downloadCsv';
		$data['function_vars'] = array(
			'csvDataString' => $csv,
			'fileName'      => $file_name,

		);

		wp_send_json_success( $data );
	}

	/**
	 * Send email for password reset.
	 *
	 * @since 3.4.1
	 *
	 */
	public static function send_password_reset( WP_REST_Request $request ) {
		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array( 'send-password-reset' );

		// Was an action received, and is the actions allowed
		if ( $request->has_param( 'action' ) && in_array( $request->get_param( 'action' ), $permitted_actions ) ) {

			$action = (string) $request->get_param( 'action' );

		} else {
			$action          = '';
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		if ( ! $request->has_param( 'send-password-users' ) || empty( $request->get_param( 'send-password-users' ) ) ) {
			$data['message'] = __( 'Select a user.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		$users_ids_raw = explode(
			',',
			str_replace(
				array(
					'[',
					']',
				),
				'',
				wp_unslash( $request->get_param( 'send-password-users' ) )
			)
		);
		if ( empty( $users_ids_raw ) ) {
			$data['message'] = __( 'Select a user.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		$users_ids = array();
		//Removing quotes and making sure values are absint
		foreach ( $users_ids_raw as $user_id ) {
			$users_ids[] = absint( str_replace( '"', '', $user_id ) );
		}

		// Does the current user have permission
		$permission = apply_filters( 'send_password_reset_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to send password reset email.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		$group_leader_id                = get_current_user_id();
		$user_group_ids                 = LearndashFunctionOverrides::learndash_get_administrators_group_ids( $group_leader_id );
		$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( $group_leader_id, absint( $request->get_param( 'group-id' ) ), $user_group_ids );
		// is the current user able to administer this group
		if ( false === $can_the_user_manage_this_group ) {
			$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
			$data['error']   = 'invalid-group-id';
			wp_send_json_error( $data );
		}

		$group_id = absint( wp_unslash( $request->get_param( 'group-id' ) ) );

		// Get group users ids for validation.
		$group_users = LearndashFunctionOverrides::learndash_get_groups_user_ids( $group_id );
		$data        = array();
		if ( is_multisite() ) {
			$site_name = get_network()->site_name;
		} else {
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}
		foreach ( $users_ids as $user_id ) {
			$user_id = absint( $user_id );
			if ( in_array( $user_id, $group_users ) ) {
				$user_data = get_userdata( $user_id );
				if ( $request->has_param( 'user_login' ) ) {
					$user_login = $request->get_param( 'user_login' );
					unset( $user_login );
				}

				$user_login = $user_data->user_login;
				$user_email = $user_data->user_email;
				$key        = get_password_reset_key( $user_data );

				if ( is_wp_error( $key ) ) {
					$data['message'] = __( 'There is some problem please try again later.', 'uncanny-learndash-groups' );
					wp_send_json_error( $data );
				}

				$message = __( 'Someone has requested a password reset for the following account:', 'uncanny-learndash-groups' ) . "\r\n\r\n";
				/* translators: %s: site name */
				$message .= sprintf( __( 'Site Name: %s', 'uncanny-learndash-groups' ), $site_name ) . "\r\n\r\n";
				/* translators: %s: user login */
				$message .= sprintf( __( 'Username: %s', 'uncanny-learndash-groups' ), $user_login ) . "\r\n\r\n";
				$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'uncanny-learndash-groups' ) . "\r\n\r\n";
				$message .= __( 'To reset your password, visit the following address:', 'uncanny-learndash-groups' ) . "\r\n\r\n";
				$message .= network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n";

				/* translators: Password reset notification email subject. %s: Site title */
				$title = sprintf( __( '[%s] Password Reset', 'uncanny-learndash-groups' ), $site_name );
				$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

				$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );
				$mail    = SharedFunctions::wp_mail( $user_email, wp_specialchars_decode( $title ), $message );
				if ( $message && ! $mail ) {
					$data['message'] = __( 'The email could not be sent. Possible reason: your host may have disabled the mail() function.', 'uncanny-learndash-groups' );
					wp_send_json_error( $data );
				}
			}
		}
		if ( empty( $data ) ) {
			$data['message'] = sprintf( __( 'Password reset email sent to %d group users.', 'uncanny-learndash-groups' ), count( $users_ids ) );
			wp_send_json_success( $data );
		}

		wp_send_json_error( $data );
	}

	/**
	 * Get user details from group
	 *
	 * @since 3.5.0
	 */
	public static function get_user_details( WP_REST_Request $request ) {

		$group_leader_id = get_current_user_id();

		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array( 'get-user-details' );

		// Was an action received, and is the actions allowed
		if ( ! $request->has_param( 'action' ) || ! in_array( $request->get_param( 'action' ), $permitted_actions ) ) {
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$permission = apply_filters( 'group_management_edit_user_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to modify users.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}
		if ( ! $request->has_param( 'group-id' ) ) {
			$data['message'] = __( 'Group ID was not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}
		// Was group id received
		// is group a valid integer
		if ( ! absint( $request->get_param( 'group-id' ) ) ) {
			$data['message'] = __( 'Group ID must be a whole number.', 'uncanny-learndash-groups' );
			$data['error']   = 'invalid-group-id';
			wp_send_json_error( $data );
		}
		$group_id = absint( $request->get_param( 'group-id' ) );

		$user_group_ids                 = LearndashFunctionOverrides::learndash_get_administrators_group_ids( $group_leader_id );
		$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( $group_leader_id, $group_id, $user_group_ids );
		// is the current user able to administer this group
		if ( false === $can_the_user_manage_this_group ) {
			$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
			$data['error']   = 'invalid-group-id';
			wp_send_json_error( $data );
		}

		if ( ! $request->has_param( 'user' ) ) {
			$data['message'] = __( 'Users where not received.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		$response_data = array(
			'edit-user-id'    => array(
				'value'       => '',
				'is_editable' => true,
			),
			'edit-first-name' => array(
				'value'       => '',
				'is_editable' => true,
			),
			'edit-last-name'  => array(
				'value'       => '',
				'is_editable' => true,
			),
			'edit-email'      => array(
				'value'       => '',
				'is_editable' => true,
			),
			'edit-username'   => array(
				'value'       => '',
				'is_editable' => true,
			),
		);
		$user_id       = $request->get_param( 'user' );
		$group_id      = $request->get_param( 'group-id' );
		if ( ! is_numeric( $user_id ) ) {
			$user_data = SharedFunctions::get_details_by_code( $user_id, $group_id );
			if ( $user_data ) {
				$response_data['edit-user-id']['value']        = $user_id;
				$response_data['edit-first-name']['value']     = $user_data->first_name;
				$response_data['edit-last-name']['value']      = $user_data->last_name;
				$response_data['edit-email']['value']          = $user_data->user_email;
				$response_data['edit-username']['is_editable'] = false;
			}
		} else {
			// real user
			$user_id             = absint( $user_id );
			$current_user_groups = learndash_is_group_leader_of_user( $group_leader_id, $user_id );
			if ( $current_user_groups || current_user_can( 'manage_options' ) || current_user_can( 'ulgm_group_management' ) ) {
				$user_data = get_user_by( 'ID', $user_id );
				if ( $user_data ) {
					$response_data['edit-user-id']['value']    = $user_id;
					$response_data['edit-first-name']['value'] = $user_data->first_name;
					$response_data['edit-last-name']['value']  = $user_data->last_name;
					$response_data['edit-email']['value']      = $user_data->user_email;
					$response_data['edit-username']['value']   = $user_data->user_login;
				}
			}
		}
		if ( 'no' === get_option( 'allow_group_leader_change_username', 'no' ) ) {
			unset( $response_data['edit-username'] );
		}

		wp_send_json_success( $response_data );
	}

	/**
	 * Update user details from group
	 *
	 * @since 3.5.0
	 */
	public static function update_user_details( WP_REST_Request $request ) {

		$group_leader_id = get_current_user_id();

		// Actions permitted by the pi call (collected from input element with name action )
		$permitted_actions = array( 'edit-user' );

		// Was an action received, and is the actions allowed
		if ( $request->has_param( 'action' ) && in_array( $request->get_param( 'action' ), $permitted_actions ) ) {

			$action = (string) $request->get_param( 'action' );

		} else {
			$action          = '';
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$permission = apply_filters( 'group_management_edit_user_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			//$data['message'] = __( 'You do not have permission to remove users.', 'uncanny-learndash-groups' );
			//wp_send_json_error( $data );
		}

		// Was group id received
		if ( $request->has_param( 'group-id' ) ) {

			// is group a valid integer
			if ( ! absint( $request->get_param( 'group-id' ) ) ) {
				$data['message'] = __( 'Group ID must be a whole number.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$user_group_ids                 = LearndashFunctionOverrides::learndash_get_administrators_group_ids( $group_leader_id );
			$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( $group_leader_id, absint( $request->get_param( 'group-id' ) ), $user_group_ids );
			// is the current user able to administer this group
			if ( false === $can_the_user_manage_this_group ) {
				$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$group_id = absint( $request->get_param( 'group-id' ) );

		} else {
			$data['message'] = __( 'Group ID was not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		if ( 'edit-user' === $action ) {

			// Input Validation
			if ( $request->has_param( 'edit-user-id' ) ) {
				$user_id = $request->get_param( 'edit-user-id' );

				if ( $request->has_param( 'first_name' ) ) {
					$first_name = stripslashes( (string) $request->get_param( 'first_name' ) );
					// is first name a valid name, check if it contains letters only, allow hyphens
					//if ( 0 === preg_match( "/^[a-zA-Z0-9\s,.\'\-\pL]+$/u", $first_name ) ) {
					if ( false === SharedFunctions::remove_special_character( $first_name, true ) ) {
						$data['message'] = __( 'First name can only contain letters, numbers, spaces, and hyphens.', 'uncanny-learndash-groups' );
						$data['error']   = 'invalid-first-name';
						wp_send_json_error( $data );
					}
				} else {
					$first_name      = '';
					$data['message'] = __( 'First name not received. Reload page and try again.', 'uncanny-learndash-groups' );
					wp_send_json_error( $data );
				}

				// Was last name received
				if ( $request->has_param( 'last_name' ) ) {
					$last_name = stripslashes( (string) $request->get_param( 'last_name' ) );
					// is last name a valid name, check if it contains unicode letters only, allow hyphens
					if ( false === SharedFunctions::remove_special_character( $last_name, true ) ) {
						$data['message'] = __( 'Last name can only contain letters, numbers, spaces, commas, apostrophes and hyphens.', 'uncanny-learndash-groups' );
						$data['error']   = 'invalid-last-name';
						wp_send_json_error( $data );
					}
				} else {
					$last_name       = '';
					$data['message'] = __( 'Last name not received. Reload page and try again.', 'uncanny-learndash-groups' );
					wp_send_json_error( $data );
				}

				// Was email received
				if ( $request->has_param( 'email' ) ) {
					// is email a valid name
					if ( ! is_email( stripcslashes( $request->get_param( 'email' ) ) ) ) {
						$data['message'] = __( 'Please enter a valid email address.', 'uncanny-learndash-groups' );
						$data['error']   = 'invalid-email';
						wp_send_json_error( $data );
					}
					$email = sanitize_email( stripcslashes( $request->get_param( 'email' ) ) );

					$is_email_available = email_exists( $email );

					if ( $is_email_available && $is_email_available != $user_id ) {
						$data['message'] = __( 'Email already in use.', 'uncanny-learndash-groups' );
						$data['error']   = 'invalid-email';
						wp_send_json_error( $data );
					}
				} else {
					$email           = '';
					$data['message'] = __( 'Email not received. Reload page and try again.', 'uncanny-learndash-groups' );
					wp_send_json_error( $data );
				}

				if ( 'yes' === get_option( 'allow_group_leader_change_username', 'no' ) ) {
					if ( is_numeric( $user_id ) ) {
						if ( $request->has_param( 'username' ) ) {
							// is email a valid name
							if ( ! validate_username( $request->get_param( 'username' ) ) ) {
								$data['message'] = __( 'Please enter a valid username.', 'uncanny-learndash-groups' );
								$data['error']   = 'invalid-username';
								wp_send_json_error( $data );
							}

							$username = sanitize_user( $request->get_param( 'username' ) );
							// check if username available
							$is_available = username_exists( $username );
							if ( $is_available && $is_available != $user_id ) {
								$data['message'] = __( 'Username already in use.', 'uncanny-learndash-groups' );
								$data['error']   = 'invalid-username';
								wp_send_json_error( $data );
							}
						} else {
							$username        = '';
							$data['message'] = __( 'Username not received. Reload page and try again.', 'uncanny-learndash-groups' );
							wp_send_json_error( $data );
						}
					}
				}

				global $wpdb;

				if ( is_numeric( $user_id ) ) {
					$user_data = get_userdata( $user_id );
					// Update username!
					if ( 'yes' === get_option( 'allow_group_leader_change_username', 'no' ) ) {
						$is_available = username_exists( $username );
						if ( $is_available == false ) {
							$q = $wpdb->prepare( "UPDATE $wpdb->users SET user_login = %s WHERE ID = %d", $username, $user_id );

							if ( false !== $wpdb->query( $q ) ) {
								// Update user_nicename.
								$qnn = $wpdb->prepare( "UPDATE $wpdb->users SET user_nicename = %s WHERE ID = %d", $username, $user_id );
								$wpdb->query( $qnn );

								// Update display_name.
								$qdn = $wpdb->prepare( "UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $username, $user_id );
								$wpdb->query( $qdn );

								// Update nickname.
								update_user_meta( $user_id, 'nickname', $username );

							}
						}
					} else {
						$username = $user_data->user_login;
					}
					$args = array(
						'ID'         => $user_id,
						'user_email' => esc_attr( $email ),
						'user_login' => $username,
					);

					$error = wp_update_user( $args );

					update_user_meta( $user_id, 'first_name', $first_name );
					update_user_meta( $user_id, 'last_name', $last_name );

				} else {

					$update = $wpdb->update(
						$wpdb->prefix . SharedFunctions::$db_group_codes_tbl,
						array( 'user_email' => $email ),
						array( 'code' => $user_id ),
						array( '%s' ),
						array( '%s' )
					);

					$update = $wpdb->update(
						$wpdb->prefix . SharedFunctions::$db_group_codes_tbl,
						array( 'first_name' => $first_name ),
						array( 'code' => $user_id ),
						array( '%s' ),
						array( '%s' )
					);

					$update = $wpdb->update(
						$wpdb->prefix . SharedFunctions::$db_group_codes_tbl,
						array( 'last_name' => $last_name ),
						array( 'code' => $user_id ),
						array( '%s' ),
						array( '%s' )
					);

				}

				$data['message'] = __( 'User has been updated.', 'uncanny-learndash-groups' );
				wp_send_json_success( $data );

			} else {
				$data['message'] = __( 'Users where not received.', 'uncanny-learndash-groups' );
				wp_send_json_error( $data );
			}
		}

	}

	/**
	 * @param $action
	 *
	 * @return array
	 */
	public static function validate_new_user_data( $action, WP_REST_Request $request ) {

		// Defaults, we don't always need first and last
		$first_name = '';
		$last_name  = '';
		$group_name = '';

		// Was group id received
		if ( $request->has_param( 'group-id' ) ) {

			// is group a valid integer
			if ( ! absint( $request->get_param( 'group-id' ) ) ) {
				$data['message'] = __( 'Group ID must be a whole number.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$group_leader_id                = get_current_user_id();
			$user_group_ids                 = LearndashFunctionOverrides::learndash_get_administrators_group_ids( $group_leader_id );
			$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( $group_leader_id, absint( $request->get_param( 'group-id' ) ), $user_group_ids );
			// is the current user able to administer this group
			if ( false === $can_the_user_manage_this_group ) {
				$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$group_name = get_the_title( absint( $request->get_param( 'group-id' ) ) );

		} else {
			$data['message'] = __( 'Group ID was not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Was first name received
		if ( $request->has_param( 'first_name' ) && ( 'add-invite' === $action || 'add-leader' === $action || 'send-enrollment' === $action ) ) {

			$first_name = stripslashes( (string) $request->get_param( 'first_name' ) );

			// is first name a valid name, check if it contains letters only, allow hyphens
			if ( false === SharedFunctions::remove_special_character( $first_name, true ) ) {
				$data['message'] = __( 'First name can only contain letters, numbers, spaces, and hyphens.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-first-name';
				wp_send_json_error( $data );
			}
		} elseif ( 'add-invite' === $action ) {
			$first_name      = '';
			$data['message'] = __( 'First name not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Was last name received
		if ( $request->has_param( 'last_name' ) && ( 'add-invite' === $action || 'add-leader' === $action || 'send-enrollment' === $action ) ) {

			$last_name = stripslashes( (string) $request->get_param( 'last_name' ) );

			// is last name a valid name, check if it contains unicode letters only, allow hyphens
			if ( false === SharedFunctions::remove_special_character( $last_name, true ) ) {
				$data['message'] = __( 'Last name can only contain letters, numbers, spaces, commas, apostrophes and hyphens.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-last-name';
				wp_send_json_error( $data );
			}
		} elseif ( 'add-invite' === $action ) {
			$last_name       = '';
			$data['message'] = __( 'Last name not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Was email received
		if ( $request->has_param( 'email' ) ) {

			// is email a valid name
			if ( ! is_email( stripcslashes( $request->get_param( 'email' ) ) ) ) {
				$data['message'] = __( 'Please enter a valid email address.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-email';
				wp_send_json_error( $data );
			}

			$email = sanitize_email( stripcslashes( $request->get_param( 'email' ) ) );

		} else {
			$email           = '';
			$data['message'] = __( 'Email not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Was password received
		$password = '';
		if ( $request->has_param( 'uo_password' ) && ! empty( $request->get_param( 'uo_password' ) ) ) {

			// is email a valid name
			if ( strlen( stripcslashes( $request->get_param( 'uo_password' ) ) ) < 6 ) {
				$data['message'] = __( 'Passwords must include at least 6 characters.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-password';
				wp_send_json_error( $data );
			}

			$password = stripcslashes( $request->get_param( 'uo_password' ) );
		}

		if ( $request->has_param( 'first_name' ) && ! empty( $request->get_param( 'first_name' ) ) ) {
			$first_name = (string) $request->get_param( 'first_name' );
		}

		if ( $request->has_param( 'last_name' ) && ! empty( $request->get_param( 'last_name' ) ) ) {
			$last_name = (string) $request->get_param( 'last_name' );
		}

		$user_data = array(
			'user_login'  => $email,
			'user_email'  => $email,
			'first_name'  => $first_name,
			'last_name'   => $last_name,
			'group_name'  => $group_name,
			'posted_data' => $_REQUEST,
		);

		if ( ! empty( $password ) ) {
			$user_data['user_pass'] = $password;
		}

		return $user_data;
	}

	/**
	 * @param $user_data
	 * @param false $do_not_send_emails
	 * @param int $group_id
	 * @param int $order_id
	 * @param string $code_status
	 * @param bool $is_api
	 * @param false $is_cron
	 * @param false $counter
	 * @param false $added_as_gl
	 *
	 * @return array
	 * @deprecated 4.0. See Group_Management_Helpers::add_existing_user
	 */
	public static function add_existing_user( $user_data, $do_not_send_emails = false, $group_id = 0, $order_id = 0, $code_status = 'not redeemed', $is_api = true, $is_cron = false, $counter = false, $added_as_gl = false ) {
		return Group_Management_Helpers::add_existing_user( $user_data, $do_not_send_emails, $group_id, $order_id, $code_status, $is_api, $is_cron, $counter, $added_as_gl );
	}

	/**
	 * Rest API callback for saving user selection for hiding the "Try
	 * Automator" item.
	 *
	 * @param object $request
	 *
	 * @since 3.5.4
	 * @return object
	 */
	public static function try_automator_rest_callback( $request ) {
		// check if its a valid request.
		$data = $request->get_params();
		if ( isset( $data['action'] ) && ( 'hide-forever' === $data['action'] || 'hide-forever' === $data['action'] ) ) {
			update_option( '_uncanny_groups_try_automator_visibility', $data['action'] );

			return new \WP_REST_Response( array( 'success' => true ), 200 );
		}

		return new \WP_REST_Response( array( 'success' => false ), 200 );
	}

}
