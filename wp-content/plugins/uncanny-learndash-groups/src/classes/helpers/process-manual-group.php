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
class ProcessManualGroup {
	/**
	 * ProcessManualGroup constructor.
	 */
	function __construct() {
		add_action( 'wp_loaded', array( $this, 'process_manual_group' ), 99 );
	}

	/**
	 *
	 */
	public function process_manual_group() {

		if ( ( ulgm_filter_has_var( 'is_custom_group_nonce', INPUT_POST ) && wp_verify_nonce( ulgm_filter_input( 'is_custom_group_nonce', INPUT_POST ), 'ulgm_nonce' ) ) && ( ulgm_filter_has_var( 'is_custom_group', INPUT_POST ) && 'yes' === ulgm_filter_input( 'is_custom_group', INPUT_POST ) ) ) :

			$group_leader_first_name = ulgm_filter_input( 'ulgm_group_leader_first_name', INPUT_POST );
			$group_leader_last_name  = ulgm_filter_input( 'ulgm_group_leader_last_name', INPUT_POST );
			$group_leader_email      = ulgm_filter_input( 'ulgm_group_leader_email', INPUT_POST );
			$group_name              = ulgm_filter_input( 'ulgm_group_name', INPUT_POST );
			$number_of_seats         = ulgm_filter_input( 'ulgm_group_total_seats', INPUT_POST );
			$group_courses           = ulgm_filter_input_array( 'ulgm_group_courses', INPUT_POST );
			$group_image             = ulgm_filter_input( 'ulgm_group_image_attachment_id', INPUT_POST );

			$customer = false;

			if ( is_user_logged_in() ) {
				$customer    = wp_get_current_user();
				$customer_id = $customer->ID;
			}

			if ( empty( $group_leader_email ) && $customer ) {
				$group_leader_email      = $customer->user_email;
				$group_leader_first_name = $customer->first_name;
				$group_leader_last_name  = $customer->last_name;
			}

			$args = array(
				'ulgm_group_leader_first_name' => $group_leader_first_name,
				'ulgm_group_leader_last_name'  => $group_leader_last_name,
				'ulgm_group_leader_email'      => $group_leader_email,
				'ulgm_group_name'              => $group_name,
				'ulgm_group_total_seats'       => $number_of_seats,
				'ulgm_group_courses'           => $group_courses,
				'ulgm_group_image'             => $group_image,
				'ulgm_group_customer_id'       => $customer_id,
			);

			self::process( $args, $_POST );

		endif;
	}


	/**
	 * @param $args
	 * @param null $_post
	 *
	 * @return int|\WP_Error
	 */
	public static function process( $args, $_post = null ) {

		$group_leader_first_name = $args['ulgm_group_leader_first_name'];
		$group_leader_last_name  = $args['ulgm_group_leader_last_name'];
		$group_leader_email      = sanitize_email( $args['ulgm_group_leader_email'] );
		$group_name              = $args['ulgm_group_name'];
		$number_of_seats         = absint( $args['ulgm_group_total_seats'] );
		$group_courses           = $args['ulgm_group_courses'];
		$group_image             = absint( $args['ulgm_group_image'] );
		$already_exists          = email_exists( $group_leader_email );

		$customer_id    = $args['ulgm_group_customer_id'];
		$user_details   = array(
			'first_name' => $group_leader_first_name,
			'last_name'  => $group_leader_last_name,
			'email'      => $group_leader_email,
		);
		$plain_password = SharedFunctions::wp_generate_password();

		$group_leader_id = self::get_group_leader_id( $user_details, $plain_password );

		if ( $group_leader_id ) {
			$group_title   = $group_name;
			$ld_group_args = apply_filters(
				'ulgm_insert_group',
				array(
					'post_type'    => 'groups',
					'post_status'  => apply_filters( 'uo_create_new_group_status', 'publish' ),
					'post_title'   => $group_title,
					'post_content' => '',
					'post_author'  => apply_filters( 'ulgm_custom_group_post_author', $customer_id, $group_leader_id, 'admin-created' ),
				)
			);

			$group_id = wp_insert_post( $ld_group_args );

			$price_type = apply_filters( 'uo_create_new_group_price_type', 'closed', $group_id );
			update_post_meta( $group_id, '_ulgm_is_custom_group_created', 'yes' );
			update_post_meta( $group_id, '_thumbnail_id', $group_image );
			update_post_meta( $group_id, '_ld_price_type', $price_type );
			update_post_meta(
				$group_id,
				'_groups',
				array(
					'groups_group_price_type' => $price_type,
				)
			);

			ld_update_leader_group_access( $group_leader_id, $group_id );

			if ( ! empty( $group_courses ) ) {
				foreach ( $group_courses as $course_id ) {
					ld_update_course_group_access( (int) $course_id, (int) $group_id, false );
					$transient_key = 'learndash_course_groups_' . $course_id;
					delete_transient( $transient_key );
				}
			}

			update_post_meta( $group_id, '_ulgm_total_seats', $number_of_seats );
			$order_id      = ulgm()->group_management->get_random_order_number();
			$attr          = array(
				'user_id'    => $group_leader_id,
				'order_id'   => $order_id,
				'group_id'   => $group_id,
				'group_name' => $group_title,
				'qty'        => $number_of_seats,
			);
			$codes         = ulgm()->group_management->generate_random_codes( $number_of_seats );
			$code_group_id = ulgm()->group_management->add_codes( $attr, $codes );

			update_post_meta( $group_id, SharedFunctions::$code_group_id_meta_key, $code_group_id );
			update_user_meta( $group_leader_id, '_ulgm_custom_order_id', $order_id );

			if ( 'yes' !== get_option( 'do_not_add_group_leader_as_member', 'no' ) ) {

				Group_Management_Helpers::add_existing_user( array( 'user_email' => $group_leader_email ), true, $group_id, $order_id, SharedFunctions::$redeem_status, false );
			}
			// Send Welcome email, for extra validation we are sending in the user id and getting user data from WP because there may be filters
			$send_email = true;
			if ( ! $already_exists && 'yes' !== get_option( 'ulgm_send_group_leader_welcome_email', 'yes' ) ) {
				$send_email = false;
			} elseif ( $already_exists && 'yes' !== get_option( 'ulgm_send_existing_group_leader_welcome_email', 'yes' ) ) {
				$send_email = false;
			}

			if ( $send_email ) {
				if ( ! $already_exists ) {
					// Add group leader/Create group email subject
					$ulgm_group_leader_welcome_email_subject = SharedVariables::group_leader_welcome_email_subject();

					// Add group leader/Create group email subject
					$ulgm_group_leader_welcome_email_body = SharedVariables::group_leader_welcome_email_body();

				} else {
					$plain_password = '';

					// Add group leader/Create group email subject
					$ulgm_group_leader_welcome_email_subject = SharedVariables::existing_group_leader_welcome_email_subject();

					// Add group leader/Create group email subject
					$ulgm_group_leader_welcome_email_body = SharedVariables::existing_group_leader_welcome_email_body();

				}

				// Send Welcome email, for extra validation we are sending in the user id and getting user data from WP because there may be filters
				Group_Management_Helpers::send_welcome_email( $group_leader_id, $plain_password, $ulgm_group_leader_welcome_email_subject, $ulgm_group_leader_welcome_email_body, $group_title, $group_id );
			}

			do_action( 'uo_new_group_created', $group_id, $group_leader_id );

			if ( ! ulgm_filter_has_var( 'is_front_end', INPUT_POST ) && ! is_null( $_post ) ) {
				$post_type_object = get_post_type_object( 'groups' );
				$action           = '&action=edit';
				$link             = admin_url( sprintf( $post_type_object->_edit_link . $action, $group_id ) );
				wp_safe_redirect( $link );
				exit;
			}
			if ( ulgm_filter_has_var( 'group_page_id', INPUT_POST ) ) {
				$redirect = apply_filters( 'uo_redirect_after_group_created', get_permalink( ulgm_filter_input( 'group_page_id', INPUT_POST ) ) . '?is-group-created=yes', $group_id );
				wp_safe_redirect( $redirect );
				exit;
			}

			return $group_id;
		}
	}

	/**
	 * @param $user_details
	 *
	 * @param string $plain_password
	 *
	 * @return int
	 */
	public static function get_group_leader_id( $user_details, $plain_password = '' ) {
		$email        = $user_details['email'];
		$fname        = $user_details['first_name'];
		$lname        = $user_details['last_name'];
		$exists       = email_exists( $email );
		$group_leader = get_user_by( 'email', $email );

		if ( $exists ) {
			// Get user
			// Check if user is not a group leader yet
			if ( ! user_can( $group_leader, 'group_leader' ) && ! user_can( $group_leader, 'administrator' ) ) {
				$u = new \WP_User( $group_leader->ID );
				// Give the user group_leader capabilities
				$u->add_role( 'group_leader' );
			}

			return absint( $exists );
		} else {
			if ( empty( $plain_password ) ) {
				$user_pass = SharedFunctions::wp_generate_password();
			} else {
				$user_pass = $plain_password;
			}
			$new_user = wp_insert_user(
				array(
					'user_login'      => $email,
					'user_pass'       => $user_pass,
					'user_email'      => $email,
					'first_name'      => $fname,
					'last_name'       => $lname,
					'user_registered' => date( 'Y-m-d H:i:s' ),
					'role'            => 'group_leader',
				)
			);
			if ( $new_user ) {
				// send an email to the admin alerting them of the registration
				//wp_new_user_notification( $new_user, null, 'user' );
				update_user_meta( $new_user, '_ulgm_is_custom_group_leader', 'yes' );

				return absint( $new_user );
			}
		}
	}
}
