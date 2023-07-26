<?php


namespace uncanny_learndash_groups;

/**
 * Class Group_Management_Helpers
 *
 * @package uncanny_learndash_groups
 */
class Group_Management_Helpers {

	/**
	 * @param      $user_data
	 * @param bool $do_not_send_emails
	 * @param bool $counter On error, return(false) or count errors(true)
	 * @param bool $is_api
	 *
	 * @return array|int|string[]|\WP_Error
	 */
	public static function add_invite_user( $user_data, $do_not_send_emails = false, $counter = false, $is_api = true ) {
		if ( key_exists( 'group_id', $user_data ) ) {
			$group_id = (int) $user_data['group_id'];
		} else {
			$group_id = (int) ulgm_filter_input( 'group-id', INPUT_POST );
		}

		//returns true or false if group leader allowed to add user
		$ulgm_gdpr_compliance = apply_filters( 'ulgm_gdpr_is_group_leader_allowed', true, wp_get_current_user(), $group_id, (object) $user_data, 'add-invite' );
		if ( false === $ulgm_gdpr_compliance ) {
			if ( ! $counter ) {
				$data['message'] = __( 'You are not allowed to add users.', 'uncanny-learndash-groups' );
				wp_send_json_error( $data );
			} else {
				return array(
					'error-code' => __( 'You are not allowed to add users.', 'uncanny-learndash-groups' ),
					'status'     => array(
						'user'   => 'new_failed',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => 'use_error_code',
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		}

		// Validate that the user does not exist
		if ( email_exists( $user_data['user_email'] ) || username_exists( $user_data['user_email'] ) ) {
			$user_id              = email_exists( $user_data['user_email'] );
			$user_data['user_id'] = $user_id;

			return self::add_existing_user( $user_data, $do_not_send_emails, 0, 0, 'not-redeemed', $is_api, false, $counter );
		}

		if ( key_exists( 'user_pass', $user_data ) && ! empty( $user_data['user_pass'] ) ) {
			$plain_password = $user_data['user_pass'];
		} else {
			// Create a password
			$plain_password         = SharedFunctions::wp_generate_password();
			$user_data['user_pass'] = $plain_password;
		}
		// hash the password before insertion!
		$user_data['role'] = apply_filters( 'uo-groups-user-role', get_option( 'default_role', 'subscriber' ) );

		// Remove new user notifications
		if ( ! function_exists( 'wp_new_user_notification' ) ) {
			function wp_new_user_notification() {
			}
		}

		// add user to group. NOTE group id has already been validate and the script would return error so its safe :)
		if ( key_exists( 'group_id', $user_data ) ) {
			$group_id = (int) $user_data['group_id'];
		} else {
			$group_id = ulgm_filter_input( 'group-id', INPUT_POST );
		}

		// check if this user email already have an invite or not.
		$code = ulgm()->group_management->check_sign_up_code_from_group_id( $group_id, $user_data['user_email'] );
		if ( empty( $code ) && ! is_array( $code ) ) {
			// Get a new code
			$code = ulgm()->group_management->get_sign_up_code_from_group_id( $group_id );
		}

		$group_name = get_the_title( (int) $group_id );

		// No Codes Left
		if ( empty( $code ) || ( is_array( $code ) && isset( $code['message'] ) ) ) {

			if ( ! $counter ) {
				$data['message'] = isset( $code['message'] ) ? $code['message'] : sprintf( __( 'There are no available %s left.', 'uncanny-learndash-groups' ), strtolower( ulgm()->group_management->seat->get_per_seat_text( 2 ) ) );
				wp_send_json_error( $data );
			} else {

				return array(
					'error-code' => ! empty( $code['message'] ) ? $code['message'] : sprintf(
						__( 'There were no available %s left.', 'uncanny-learndash-groups' ),
						strtolower( ulgm()->group_management->seat->get_per_seat_text( 2 ) )
					),
					'status'     => array(
						'user'   => 'new_failed',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => 'use_error_code',
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		}
		// Assign a nickname.
		$firstname             = $user_data['first_name'] ?? '';
		$lastname              = $user_data['last_name'] ?? '';
		$user_data['nickname'] = implode( ' ', array( $firstname, $lastname ) );
		$user_data['nickname'] = str_replace( ' ', '', $user_data['nickname'] );

		// Create the user.
		$user_id              = wp_insert_user( apply_filters( 'ulgm_add_new_user_data', $user_data ) );
		$user_data['user_id'] = $user_id;

		// User creation failed
		if ( is_wp_error( $user_id ) ) {

			if ( ! $counter ) {
				$data['message'] = __( 'The user could not be added. Please contact the website administrator.', 'uncanny-learndash-groups' );
				if ( $is_api ) {
					wp_send_json_error( $data );
				} else {
					return $user_id;
				}
			} else {
				return array(
					'error-code' => __( 'The user could not be added. Check user_email.', 'uncanny-learndash-groups' ),
					'status'     => array(
						'user'   => 'new_failed',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => 'use_error_code',
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		}

		if ( isset( $user_data['first_name'] ) ) {
			update_user_meta( $user_id, 'first_name', $user_data['first_name'] );
		}

		if ( isset( $user_data['last_name'] ) ) {
			update_user_meta( $user_id, 'last_name', $user_data['last_name'] );
		}

		// Add new user to a group
		ld_update_group_access( $user_id, $group_id );

		$order_id       = ulgm()->group_management->get_order_id_from_group_id( $group_id );
		$codes_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );
		$data           = array(
			'code'           => $code,
			'user_id'        => $user_id,
			'order_id'       => $order_id,
			'code_status'    => SharedFunctions::$not_redeemed_status,
			'codes_group_id' => $codes_group_id,
		);
		$set_code       = ulgm()->group_management->set_sign_up_code_status( $data );

		if ( ! $set_code ) {

			if ( ! $counter ) {
				$data['message'] = __( 'The key failed to be assigned. Please contact the website administrator.', 'uncanny-learndash-groups' );
				if ( $is_api ) {
					wp_send_json_error( $data );
				}
			} else {
				return array(
					'error-code' => __( 'The key failed to be assigned.', 'uncanny-learndash-groups' ),
					'status'     => array(
						'user'   => 'new_failed',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => 'use_error_code',
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		}

		do_action( 'ulgm_group_user_invited', $user_data, $group_id, $order_id );
		$do_not_send_emails = apply_filters( 'ulgm_do_not_send_new_user_email', $do_not_send_emails, $user_data, $group_id, $order_id );

		if ( $do_not_send_emails ) {

			if ( ! $counter ) {
				$data['message'] = __( 'User has been added.', 'uncanny-learndash-groups' );
				SharedFunctions::delete_transient( null, $group_id );
				if ( $is_api ) {
					wp_send_json_success( $data );
				}
			} else {
				return array(
					'success-code' => 'user-added-no-email',
					'status'       => array(
						'user'   => 'new_created_added_to_group',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => __( 'User has been added.', 'uncanny-learndash-groups' ),
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		}

		// Add and invite email subject
		$ulgm_user_welcome_email_subject = SharedVariables::user_welcome_email_subject( $group_id );

		// Add and invite email subject
		$ulgm_user_welcome_email_body = SharedVariables::user_welcome_email_body( $group_id );

		// Send Welcome email, for extra validation we are sending in the user id and getting user data from WP because there may be filters
		if ( 'yes' === get_option( 'ulgm_send_user_welcome_email', 'yes' ) ) {
			$welcome_email = self::send_welcome_email( $user_id, $plain_password, $ulgm_user_welcome_email_subject, $ulgm_user_welcome_email_body, $group_name, $group_id );
			// Welcome Email Failed
			if ( is_wp_error( $welcome_email ) ) {

				if ( ! $counter ) {
					$data['message'] = __( 'User has been added. Welcome email FAILED to send.', 'uncanny-learndash-groups' );
					SharedFunctions::delete_transient( null, $group_id );
					if ( $is_api ) {
						wp_send_json_success( $data );
					}
				} else {
					return array(
						'error-code' => __( 'The invitation email could not be sent.', 'uncanny-learndash-groups' ),
						'status'     => array(
							'user'   => 'new_created_added_to_group',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'send_failed',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}
			}
		}

		if ( ! $counter ) {
			$data['message'] = __( 'User has been added and welcome email is sent.', 'uncanny-learndash-groups' );
			SharedFunctions::delete_transient( null, $group_id );

			//Hook for LD Notification -- send notification for each course in group
			$do_ld_group_postdata_updated = apply_filters( 'do_ld_group_postdata_filter', false, $group_id );
			if ( $do_ld_group_postdata_updated ) {
				$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrator_ids( $group_id );
				$group_users   = LearndashFunctionOverrides::learndash_get_groups_user_ids( $group_id );
				$group_courses = LearndashFunctionOverrides::learndash_get_groups_courses_ids( $group_id );
				do_action( 'ld_group_postdata_updated', $group_id, $group_leaders, $group_users, $group_courses );
			}

			if ( $is_api ) {
				wp_send_json_success( $data );
			}
		}

		return array(
			'success-code' => 'user-added-email-sent',
			'status'       => array(
				'user'   => 'new_created_added_to_group',
				// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
				'email'  => 'sent',
				//  sent, not_sent, send_failed
				'reason' => __( 'User has been added and welcome email is sent.', 'uncanny-learndash-groups' ),
				// message if success or use_error_code or if needed a custom
			),
		);
	}

	/**
	 * @param        $user_data
	 * @param bool $do_not_send_emails
	 * @param int $group_id
	 * @param int $order_id
	 * @param string $code_status
	 * @param bool $is_api
	 * @param bool $is_cron
	 * @param bool $counter
	 * @param bool $added_as_gl
	 *
	 * @return array
	 */
	public static function add_existing_user( $user_data, $do_not_send_emails = false, $group_id = 0, $order_id = 0, $code_status = 'not redeemed', $is_api = true, $is_cron = false, $counter = false, $added_as_gl = false ) {
		if ( ! array_key_exists( 'user_id', $user_data ) ) {
			if ( ! isset( $user_data['user_email'] ) && empty( $user_data['user_email'] ) ) {
				if ( $counter ) {
					return array(
						'error-code' => __( 'The existing user could not be found.', 'uncanny-learndash-groups' ),
						'status'     => array(
							'user'   => 'existing_failed',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'not_sent',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}

				// User already exists
				$data['message'] = __( 'A user with the email does not exist.', 'uncanny-learndash-groups' );

				if ( $is_api ) {
					wp_send_json_error( $data );
				}
			}

			$user = get_user_by( 'email', $user_data['user_email'] );

			if ( is_wp_error( $user ) ) {
				if ( $counter ) {
					return array(
						'error-code' => __( 'The existing user could not be found.', 'uncanny-learndash-groups' ),
						'status'     => array(
							'user'   => 'existing_failed',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'not_sent',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}

				// User already exists
				$data['message'] = __( 'A user with the email does not exist.', 'uncanny-learndash-groups' );

				if ( $is_api ) {
					wp_send_json_error( $data );
				}
			}
			$user_id = $user->ID;
		} else {
			if ( ! isset( $user_data['user_id'] ) || empty( $user_data['user_id'] ) ) {
				if ( $counter ) {
					return array(
						'error-code' => __( 'The existing user could not be found.', 'uncanny-learndash-groups' ),
						'status'     => array(
							'user'   => 'existing_failed',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'not_sent',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}

				// User already exists
				$data['message'] = __( 'A user with the email does not exist.', 'uncanny-learndash-groups' );

				if ( $is_api ) {
					wp_send_json_error( $data );
				}
			}
			$user_id = $user_data['user_id'];
			$user    = get_user_by( 'ID', $user_id );
			if ( is_wp_error( $user ) ) {
				if ( $counter ) {
					return array(
						'error-code' => __( 'The existing user could not be found.', 'uncanny-learndash-groups' ),
						'status'     => array(
							'user'   => 'existing_failed',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'not_sent',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}

				// User already exists
				$data['message'] = __( 'A user with the email does not exist.', 'uncanny-learndash-groups' );

				if ( $is_api ) {
					wp_send_json_error( $data );
				}
			}
		}

		// add user to group. NOTE group id has already been validate and the script would return error so its safe :)
		if ( 0 === absint( $group_id ) ) {
			$group_id = absint( ulgm_filter_input( 'group-id', INPUT_POST ) );
		}

		if ( learndash_is_user_in_group( $user_id, $group_id ) ) {
			// Join new users
			//foreach ( $current_user_groups as $current_user_group ) {
			//	if ( is_object( $current_user_group ) && $current_user_group->ID === absint( $group_id ) ) {
			$data['message'] = __( 'This user is already a member of this group.', 'uncanny-learndash-groups' );

			if ( $counter ) {
				return array(
					'error-code' => __( 'The user is already a member of this group.', 'uncanny-learndash-groups' ),
					'status'     => array(
						'user'   => 'existing_already_member',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => 'use_error_code',
						// '' if success or use_error_code or if needed a custom
					),
				);
			}

			if ( $is_api ) {
				wp_send_json_error( $data );
			}
		}

		// Get a code
		if ( 'yes' === (string) get_option( 'group_leaders_dont_use_seats', 'no' ) && true === $added_as_gl && user_can( $user, 'group_leader' ) ) {
			//Prevent group leader from taking seat if setting is turned on
			//Do nothing
		} elseif ( 'yes' !== (string) get_option( 'group_leaders_dont_use_seats', 'no' ) && true === $added_as_gl && user_can( $user, 'group_leader' ) && learndash_is_user_in_group( $user->ID, $group_id ) ) {
			//Prevent group leader from taking another seat if they are already a member of the group
			//Do nothing
		} else {
			$code = ulgm()->group_management->get_sign_up_code_from_group_id( $group_id, 1, $user_id, $order_id, $is_cron );

			// No Codes Left
			if ( ! $code || ( is_array( $code ) && isset( $code['message'] ) ) ) {
				$data['message'] = ! empty( $code['message'] ) ? $code['message'] : __( 'There are no codes left.', 'uncanny-learndash-groups' );

				if ( $counter ) {
					return array(
						'error-code' => ! empty( $code['message'] ) ? $code['message'] : sprintf(
							__( 'The user was not imported because there were no available %s left.', 'uncanny-learndash-groups' ),
							strtolower( get_option( 'ulgm_per_seat_text_plural', 'Seats' ) )
						),
						'status'     => array(
							'user'   => 'existing_failed',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'not_sent',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}

				if ( $is_api ) {
					wp_send_json_error( $data );
				}
			}
		}
		//Prevent group leader being added to group either from front and/on on edit group by admin in wp-admin
		// set all group users.
		//learndash_set_users_group_ids( $user_id, $user_groups );
		ld_update_group_access( $user_id, $group_id );

		// Check if user has already redeemed code to avoid duplicate seat issues
		$existing_code = SharedFunctions::check_if_user_has_code_redeemed( $user_id, $group_id );
		if ( null !== $existing_code ) {
			$data['message'] = sprintf( __( 'User added to the group successfully but %s was previously redeemed.', 'uncanny-learndash-groups' ), strtolower( get_option( 'ulgm_per_seat_text', 'Seat' ) ) );

			if ( $counter ) {
				return array(
					'success-code' => 'user-added-no-seat-redeem',
					'status'       => array(
						'user'   => 'existing_added_to_group',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => __( 'User has been added but code was already redeemed.', 'uncanny-learndash-groups' ),
						// '' if success or use_error_code or if needed a custom
					),
				);
			}

			if ( $is_api ) {
				SharedFunctions::delete_transient( null, $group_id );
				wp_send_json_success( $data );
			}
		}

		// Make sure the user has the group membership role (default: 'subscriber')
		// Ignore subscriber role if user is group_leader or administrator
		if ( ! user_can( $user, 'group_leader' ) && ! user_can( $user, 'administrator' ) ) {
			if ( ! $user instanceof \WP_User ) {
				$user = get_user_by( 'ID', $user_id );
				if ( is_wp_error( $user ) ) {
					if ( $counter ) {
						return array(
							'error-code' => __( 'The existing user could not be found.', 'uncanny-learndash-groups' ),
							'status'     => array(
								'user'   => 'existing_failed',
								// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
								'email'  => 'not_sent',
								//  sent, not_sent, send_failed
								'reason' => 'use_error_code',
								// '' if success or use_error_code or if needed a custom
							),
						);
					}

					// User already exists
					$data['message'] = __( 'A user with the email does not exist.', 'uncanny-learndash-groups' );

					if ( $is_api ) {
						wp_send_json_error( $data );
					}
				}
			}
			$user->add_role( apply_filters( 'uo-groups-user-role', get_option( 'default_role', 'subscriber' ) ) );
		}

		if ( 0 === absint( $order_id ) ) {
			$order_id = ulgm()->group_management->get_order_id_from_group_id( $group_id );
		}

		if ( 'yes' === (string) get_option( 'group_leaders_dont_use_seats', 'no' ) && $added_as_gl && user_can( $user, 'group_leader' ) ) {
			//Prevent group leader from taking seat if setting is turned on
			//Do nothing
		} else {
			$code = ulgm()->group_management->get_sign_up_code_from_group_id( $group_id, 1, $user_id, $order_id, $is_cron );
			if ( ! $code || ( is_array( $code ) && isset( $code['message'] ) ) ) {
				$data['message'] = ! empty( $code['message'] ) ? $code['message'] : __( 'Code failed.', 'uncanny-learndash-groups' );

				if ( $counter ) {
					return array(
						'error-code' => ! empty( $code['message'] ) ? $code['message'] : __( 'The key failed to be assigned.', 'uncanny-learndash-groups' ),
						'status'     => array(
							'user'   => 'existing_failed',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'not_sent',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}

				if ( $is_api ) {
					wp_send_json_error( $data );
				}
			}
			$codes_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );
			$data           = array(
				'code'           => $code,
				'user_id'        => $user_id,
				'order_id'       => $order_id,
				'code_status'    => $code_status,
				'codes_group_id' => $codes_group_id,
			);
			$set_code       = ulgm()->group_management->set_sign_up_code_status( $data );

			$data = array();
			if ( ! $set_code ) {
				$data['message'] = __( 'Code failed.', 'uncanny-learndash-groups' );

				if ( $counter ) {
					return array(
						'error-code' => __( 'The key failed to be assigned.', 'uncanny-learndash-groups' ),
						'status'     => array(
							'user'   => 'existing_failed',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'not_sent',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}

				if ( $is_api ) {
					wp_send_json_error( $data );
				}
			}
		}

		do_action( 'ulgm_existing_group_user_added', $user_data, $group_id, $order_id );
		$do_not_send_emails = apply_filters( 'ulgm_do_not_send_existing_user_email', $do_not_send_emails, $user_data, $group_id, $order_id );

		if ( $do_not_send_emails ) {

			$data['message'] = __( 'The specified user was already registered on the site and has been automatically added to this group.', 'uncanny-learndash-groups' );

			if ( $counter ) {
				return array(
					'success-code' => 'user-added-no-email',
					'status'       => array(
						'user'   => 'existing_added_to_group',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => __( 'User has been added.', 'uncanny-learndash-groups' ),
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
			if ( $is_api ) {
				SharedFunctions::delete_transient( null, $group_id );
				wp_send_json_success( $data );
			}
		} else {

			// Add and invite email subject
			$ulgm_user_welcome_email_subject = SharedVariables::exiting_user_welcome_email_subject( $group_id );

			// Add and invite email subject
			$ulgm_user_welcome_email_body = SharedVariables::exiting_user_welcome_email_body( $group_id );

			// Get group name
			$group_name = get_the_title( $group_id );
			// Send Welcome email, for extra validation we are sending in the user id and getting user data from WP because there may be filters
			if ( 'yes' === get_option( 'ulgm_send_existing_user_welcome_email', 'yes' ) ) {
				$welcome_email = self::send_welcome_email( $user_id, '', $ulgm_user_welcome_email_subject, $ulgm_user_welcome_email_body, $group_name, $group_id );
			}

			// Welcome Email Failed
			if ( is_wp_error( $welcome_email ) ) {
				$data['message'] = __( 'User has been added. Welcome email FAILED to send.', 'uncanny-learndash-groups' );

				if ( $counter ) {
					return array(
						'error-code' => __( 'User has been added. The invitation email could not be sent.', 'uncanny-learndash-groups' ),
						'status'     => array(
							'user'   => 'existing_added_to_group',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'send_failed',
							//  sent, not_sent, send_failed
							'reason' => 'use_error_code',
							// '' if success or use_error_code or if needed a custom
						),
					);
				}

				if ( $is_api ) {
					SharedFunctions::delete_transient( null, $group_id );
					wp_send_json_success( $data );
				}
			}

			$data['message'] = __( 'The specified user was already registered on the site and has been automatically added to this group.', 'uncanny-learndash-groups' );

			if ( $counter ) {
				return array(
					'success-code' => 'user-added-email-sent',
					'status'       => array(
						'user'   => 'existing_added_to_group',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'sent',
						//  sent, not_sent, send_failed
						'reason' => __( 'User has been added and welcome email is sent.', 'uncanny-learndash-groups' ),
						// '' if success or use_error_code or if needed a custom,
					),
				);
			}

			if ( $is_api ) {
				SharedFunctions::delete_transient( null, $group_id );

				//Hook for LD Notification -- send notification for each course in group
				$do_ld_group_postdata_updated = apply_filters( 'do_ld_group_postdata_filter', false, $group_id );
				if ( $do_ld_group_postdata_updated ) {
					$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrator_ids( $group_id );
					$group_users   = LearndashFunctionOverrides::learndash_get_groups_user_ids( $group_id );
					$group_courses = LearndashFunctionOverrides::learndash_get_groups_courses_ids( $group_id );
					do_action( 'ld_group_postdata_updated', $group_id, $group_leaders, $group_users, $group_courses );
				}

				wp_send_json_success( $data );
			}
		}
	}

	/**
	 * @param        $user_id
	 * @param        $new_group_purchase_subject
	 * @param        $new_group_purchase_body
	 * @param string $group_name
	 *
	 * @return bool
	 */
	public static function send_new_group_purchase_email( $user_id, $new_group_purchase_subject, $new_group_purchase_body, $group_name = '', $group_id = null ) {
		// Set up user data
		$user_info    = get_userdata( $user_id );
		$user_login   = $user_info->user_login;
		$user_email   = $user_info->user_email;
		$first_name   = $user_info->first_name;
		$last_name    = $user_info->last_name;
		$display_name = $user_info->display_name;

		// Filter #Username variable
		$new_group_purchase_subject = str_ireplace( '#Username', $user_login, $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#Username', $user_login, $new_group_purchase_body );

		// Filter #EmailEncoded variable
		$new_group_purchase_subject = str_ireplace( '#EmailEncoded', urlencode( $user_email ), $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#EmailEncoded', urlencode( $user_email ), $new_group_purchase_body );

		// Filter #Email variable
		$new_group_purchase_subject = str_ireplace( '#Email', $user_email, $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#Email', $user_email, $new_group_purchase_body );

		// Filter #FirstName variable
		$new_group_purchase_subject = str_ireplace( '#FirstName', $first_name, $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#FirstName', $first_name, $new_group_purchase_body );

		// Filter #LastName variable
		$new_group_purchase_subject = str_ireplace( '#LastName', $last_name, $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#LastName', $last_name, $new_group_purchase_body );

		// Filter #DisplayName variable
		$new_group_purchase_subject = str_ireplace( '#DisplayName', $display_name, $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#DisplayName', $display_name, $new_group_purchase_body );

		// Filter #SiteUrl variable
		$new_group_purchase_subject = str_ireplace( '#SiteUrl', site_url(), $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#SiteUrl', site_url(), $new_group_purchase_body );

		// Filter #LoginUrl variable
		$new_group_purchase_subject = str_ireplace( '#LoginUrl', wp_login_url(), $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#LoginUrl', wp_login_url(), $new_group_purchase_body );

		// Filter #LoginUrl variable
		$new_group_purchase_subject = str_ireplace( '#SiteName', get_bloginfo( 'name' ), $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#SiteName', get_bloginfo( 'name' ), $new_group_purchase_body );

		// Filter #GroupName variable
		$new_group_purchase_subject = str_ireplace( '#GroupName', $group_name, $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#GroupName', $group_name, $new_group_purchase_body );

		// Filter #GroupLeaderInfo variable
		$new_group_purchase_subject = str_ireplace( '#GroupLeaderInfo', self::get_group_leader_info( $group_id ), $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#GroupLeaderInfo', self::get_group_leader_info( $group_id ), $new_group_purchase_body );

		// Filter #Courses variable
		$new_group_purchase_subject = str_ireplace( '#Courses', self::get_group_courses( $group_id ), $new_group_purchase_subject );
		$new_group_purchase_body    = str_ireplace( '#Courses', self::get_group_courses( $group_id ), $new_group_purchase_body );

		// Filter #ResetPassword variable
		$reset_password_pattern = '/\#ResetPassword/';
		if ( preg_match( $reset_password_pattern, $new_group_purchase_subject ) ) {
			$new_group_purchase_subject = str_ireplace( '#ResetPassword', self::generate_reset_token( $user_id ), $new_group_purchase_subject );
		}
		if ( preg_match( $reset_password_pattern, $new_group_purchase_body ) ) {
			$new_group_purchase_body = str_ireplace( '#ResetPassword', self::generate_reset_token( $user_id ), $new_group_purchase_body );
		}

		// Remove escaped apostrophes
		$new_group_purchase_subject = str_replace( "\'", "'", $new_group_purchase_subject );
		$new_group_purchase_body    = str_replace( "\'", "'", $new_group_purchase_body );

		$user_data = array(
			'user_id'    => $user_id,
			'user_email' => $user_email,
			'user_login' => $user_login,
			'first_name' => $first_name,
			'last_name'  => $last_name,
		);

		$to      = apply_filters( 'ulgm_new_group_purchase_email_to', $user_email, $user_data, $group_name );
		$subject = apply_filters( 'ulgm_new_group_purchase_email_subject', $new_group_purchase_subject, $user_data, $group_name );
		$body    = apply_filters( 'ulgm_new_group_purchase_email_body', $new_group_purchase_body, $user_data, $group_name );

		if ( ! class_exists( 'WP_Better_Emails' ) || ( false === preg_match( '/\<DOCTYPE/', $body ) && false === preg_match( '/\<head\>/', $body ) ) ) {
			$body = wpautop( $body );
		}

		$send_mail = apply_filters( 'ulgm_maybe_send_new_group_purchase_email', true, $to, $subject, $group_id );
		if ( $send_mail ) {
			$mail_sent = SharedFunctions::wp_mail( $to, $subject, $body, self::get_headers() );

			return $mail_sent;
		} else {
			return true;
		}
	}

	/**
	 * @param      $user_data
	 * @param      $group_id
	 * @param bool $is_api
	 *
	 * @return mixed
	 */
	public static function create_group_leader( $user_data, $group_id, $is_api = true ) {

		$group_leader = get_user_by( 'email', $user_data['user_email'] );

		// get grops leaders that are already in the group
		$current_group_leaders = learndash_get_groups_administrators( $group_id, true );

		if ( empty( $group_leader ) ) {
			// user with the email does not exist, create one
			// Create a password
			$plain_password = SharedFunctions::wp_generate_password();

			// hash the password before insertion!
			$user_data['user_pass'] = $plain_password;

			// Remove new user notifications
			if ( ! function_exists( 'wp_new_user_notification' ) ) {
				function wp_new_user_notification() {
				}
			}

			// Set user as a group leader
			$user_data['role'] = 'group_leader';

			// Create the user
			$user_id = wp_insert_user( $user_data );

			// User creation falied
			if ( is_wp_error( $user_id ) ) {
				$data['message'] = __( 'Adding Group Leader failed.', 'uncanny-learndash-groups' );
				$data['error']   = json_encode( $user_id );
				if ( $is_api ) {
					wp_send_json_error( $data );
				} else {
					return $data;
				}
			}
			$is_new_user = true;
		} else {

			// User already exists, make sure its a group leader, if not the make them a group leader
			$user_id = $group_leader->ID;

			// Check if the added group leader is already part of the group
			foreach ( $current_group_leaders as $current_group_leader ) {
				if ( $current_group_leader->ID === $user_id ) {
					$data['message'] = __( 'This user is already a group leader.', 'uncanny-learndash-groups' );
					$data['error']   = json_encode( $user_id );

					if ( $is_api ) {
						wp_send_json_error( $data );
					} else {
						return $data;
					}
				}
			}

			if ( ! user_can( $group_leader, 'group_leader' ) && ! user_can( $group_leader, 'administrator' ) ) {
				$group_leader->add_role( 'group_leader' );
			}
			$is_new_user = false;
		}

		// Add new group leader to a group
		ld_update_leader_group_access( $user_id, $group_id, false );

		// Name of the group
		$group_name = get_the_title( $group_id );

		if ( $is_new_user ) {
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
		$send_email = true;
		if ( $is_new_user && 'yes' !== get_option( 'ulgm_send_group_leader_welcome_email', 'yes' ) ) {
			$send_email = false;
		} elseif ( ! $is_new_user && 'yes' !== get_option( 'ulgm_send_existing_group_leader_welcome_email', 'yes' ) ) {
			$send_email = false;
		}

		if ( $send_email ) {
			$welcome_email = self::send_welcome_email( $user_id, $plain_password, $ulgm_group_leader_welcome_email_subject, $ulgm_group_leader_welcome_email_body, $group_name, $group_id );

			// Welcome Email Failed
			if ( is_wp_error( $welcome_email ) ) {
				$data['message'] = __( 'Group leader has been added. Welcome email FAILED to send.', 'uncanny-learndash-groups' );
				if ( $is_api ) {
					wp_send_json_success( $data );
				} else {
					return $data;
				}
			}
		}

		if ( $is_new_user ) {
			if ( $send_email ) {
				$data['message'] = __( 'Group leader has been added and welcome email is sent.', 'uncanny-learndash-groups' );
			} else {
				$data['message'] = __( 'Group leader has been added.', 'uncanny-learndash-groups' );
			}

			if ( $is_api ) {
				wp_send_json_success( $data );
			} else {
				return $data;
			}
		} else {
			if ( $send_email ) {
				$data['message'] = __( 'Group leader has been added and welcome email is sent. We detected that this is an existing user so first and last name have not been updated.', 'uncanny-learndash-groups' );
			} else {
				$data['message'] = __( 'Group leader has been added. We detected that this is an existing user so first and last name have not been updated.', 'uncanny-learndash-groups' );
			}
			if ( $is_api ) {
				wp_send_json_success( $data );
			} else {
				return $data;
			}
		}

	}

	/**
	 * Get and globalize user data
	 *
	 * @param $group_id
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_unused__key_users_data( $group_id ) {

		$unused__key_users_data = array();
		$get_codes              = SharedFunctions::get_codes_for_download_csv( $group_id );
		if ( $get_codes ) {
			foreach ( $get_codes as $code ) {
				$unused__key_users_data[] = array(
					$code['group_name'],
					$code['code'],
				);
			}
		}

		return $unused__key_users_data;
	}

	/**
	 * @param bool $group_users_email
	 * @param array $group_leader_details
	 *
	 * @return array
	 */
	public static function get_headers( $group_users_email = false, $group_leader_details = array(), $group_id = null ) {

		$headers = array();
		if ( ! class_exists( 'WP_Better_Emails' ) ) {
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
		}

		if ( $group_users_email ) {
			$from_name  = $group_leader_details['from_name'];
			$from_email = $group_leader_details['from_email'];
			$reply_to   = $group_leader_details['reply_to'];

			$headers[]     = "From: $from_name <$from_email>";
			$reply_to_name = '';
			if ( ! empty( $reply_to ) && is_email( $reply_to ) ) {
				$__user = get_user_by( 'email', $reply_to );
				if ( $__user instanceof \WP_User ) {
					$reply_to_name = sprintf( '%s %s', $__user->first_name, $__user->last_name );
				}
				$headers[] = "Reply-To: $reply_to_name <$reply_to>";
			}

			return $headers;
		}

		$from_email = SharedFunctions::ulgm_group_management_email_users_from_email( $group_id );
		$from_name  = SharedFunctions::ulgm_group_management_email_users_from_name( $group_id );
		$headers[]  = "From: $from_name <$from_email>";

		return $headers;
	}

	/**
	 * Remove users from group
	 *
	 * @param $user_id
	 * @param $group_id
	 *
	 * @since 1.0.0
	 * @return bool|void
	 */
	public static function remove_user_from_group( $user_id, $group_id ) {

		// Was group id received
		if ( ! isset( $group_id ) || ! isset( $user_id ) ) {
			return;
		}
		if ( $user_id ) {
			$existing_code = ulgm()->group_management->get_user_code( $user_id, $group_id );
			// Remove Temp users
			if ( ! empty( $existing_code ) ) {
				if ( 1 === count( $existing_code ) ) {
					$existing_code = array_shift( $existing_code );
				}
				ulgm()->group_management->remove_sign_up_code( $existing_code, $group_id, true );
			}
			ld_update_group_access( $user_id, $group_id, true );
			do_action( 'uo-groups-role-cleanup', $user_id ); // remove the group membership role if no longer a member of any groups
		}

		return true;
	}

	/**
	 * @param $user_data
	 * @param $redemption_template_subject
	 * @param $redemption_template_body
	 * @param $group_id
	 *
	 * @return bool
	 */
	public static function resend_redemption_email( $user_data, $redemption_template_subject, $redemption_template_body, $group_id ) {

		// Set up user data
		$user_email = $user_data['user_email'];
		$first_name = $user_data['first_name'];
		$last_name  = $user_data['last_name'];

		// Get the redemption key
		$redemption_key = $user_data['key'];

		// Filter #EmailEncoded variable
		$redemption_template_subject = str_ireplace( '#EmailEncoded', urlencode( $user_email ), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#EmailEncoded', urlencode( $user_email ), $redemption_template_body );

		// Filter #email variable
		$redemption_template_subject = str_ireplace( '#Email', $user_email, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#Email', $user_email, $redemption_template_body );

		// Filter #first_name variable
		$redemption_template_subject = str_ireplace( '#FirstName', $first_name, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#FirstName', $first_name, $redemption_template_body );

		// Filter #last_name variable
		$redemption_template_subject = str_ireplace( '#LastName', $last_name, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#LastName', $last_name, $redemption_template_body );

		// Filter #redemption_key variable
		$redemption_template_subject = str_ireplace( '#RedemptionKey', $redemption_key, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#RedemptionKey', $redemption_key, $redemption_template_body );

		// Filter #SiteUrl variable
		$redemption_template_subject = str_ireplace( '#SiteUrl', site_url(), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#SiteUrl', site_url(), $redemption_template_body );

		// Filter #LoginUrl variable
		$redemption_template_subject = str_ireplace( '#LoginUrl', wp_login_url(), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#LoginUrl', wp_login_url(), $redemption_template_body );

		// Filter #GroupLeaderInfo variable
		$redemption_template_subject = str_ireplace( '#GroupLeaderInfo', self::get_group_leader_info( $group_id ), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#GroupLeaderInfo', self::get_group_leader_info( $group_id ), $redemption_template_body );

		// Filter #Courses variable
		$redemption_template_subject = str_ireplace( '#Courses', self::get_group_courses( $group_id ), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#Courses', self::get_group_courses( $group_id ), $redemption_template_body );

		// Remove escaped apostrophes
		$redemption_template_subject = str_replace( "\'", "'", $redemption_template_subject );
		$redemption_template_body    = str_replace( "\'", "'", $redemption_template_body );

		$to      = apply_filters( 'ulgm_redemption_email_to', $user_email, $user_data );
		$subject = apply_filters( 'ulgm_redemption_email_subject', $redemption_template_subject, $user_data );
		$body    = apply_filters( 'ulgm_redemption_email_body', $redemption_template_body, $user_data );

		if ( ! class_exists( 'WP_Better_Emails' ) || ( false === preg_match( '/\<DOCTYPE/', $body ) && false === preg_match( '/\<head\>/', $body ) ) ) {
			$body = wpautop( $body );
		}
		$send_mail = apply_filters( 'ulgm_maybe_resend_redemption_email', true, $to, $subject, $group_id );
		if ( $send_mail ) {
			$redemption_email = SharedFunctions::wp_mail( $to, $subject, $body, self::get_headers() );

			//If the mail is successful let a a fake user and group meta
			if ( is_wp_error( $redemption_email ) ) {
				return false;
			}
		}

		return true;

	}

	/**
	 * @param            $user_id
	 * @param            $plain_password
	 * @param            $welcome_template_subject
	 * @param            $welcome_template_body
	 * @param string $group_name
	 * @param            $group_id
	 *
	 * @return bool
	 */
	public static function send_welcome_email( $user_id, $plain_password, $welcome_template_subject, $welcome_template_body, $group_name = '', $group_id = 0 ) {

		// Set up user data
		$user_info    = get_userdata( $user_id );
		$user_login   = $user_info->user_login;
		$user_email   = $user_info->user_email;
		$first_name   = $user_info->first_name;
		$last_name    = $user_info->last_name;
		$display_name = $user_info->display_name;

		// Filter #Username variable
		$welcome_template_subject = str_ireplace( '#Username', $user_login, $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#Username', $user_login, $welcome_template_body );

		// Filter #EmailEncoded variable
		$welcome_template_subject = str_ireplace( '#EmailEncoded', urlencode( $user_email ), $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#EmailEncoded', urlencode( $user_email ), $welcome_template_body );

		// Filter #Email variable
		$welcome_template_subject = str_ireplace( '#Email', $user_email, $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#Email', $user_email, $welcome_template_body );

		// Filter #Password variable
		$welcome_template_subject = str_ireplace( '#Password', $plain_password, $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#Password', $plain_password, $welcome_template_body );

		// Filter #FirstName variable
		$welcome_template_subject = str_ireplace( '#FirstName', $first_name, $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#FirstName', $first_name, $welcome_template_body );

		// Filter #LastName variable
		$welcome_template_subject = str_ireplace( '#LastName', $last_name, $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#LastName', $last_name, $welcome_template_body );

		// Filter #DisplayName variable
		$welcome_template_subject = str_ireplace( '#DisplayName', $display_name, $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#DisplayName', $display_name, $welcome_template_body );

		// Filter #SiteUrl variable
		$welcome_template_subject = str_ireplace( '#SiteUrl', site_url(), $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#SiteUrl', site_url(), $welcome_template_body );

		// Filter #LoginUrl variable
		$welcome_template_subject = str_ireplace( '#LoginUrl', wp_login_url(), $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#LoginUrl', wp_login_url(), $welcome_template_body );

		// Filter #LoginUrl variable
		$welcome_template_subject = str_ireplace( '#SiteName', get_bloginfo( 'name' ), $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#SiteName', get_bloginfo( 'name' ), $welcome_template_body );

		// Filter #LoginUrl variable
		$welcome_template_subject = str_ireplace( '#GroupName', $group_name, $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#GroupName', $group_name, $welcome_template_body );

		// Filter #GroupLeaderInfo variable
		$welcome_template_subject = str_ireplace( '#GroupLeaderInfo', self::get_group_leader_info( $group_id ), $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#GroupLeaderInfo', self::get_group_leader_info( $group_id ), $welcome_template_body );

		// Filter #Courses variable
		$welcome_template_subject = str_ireplace( '#Courses', self::get_group_courses( $group_id ), $welcome_template_subject );
		$welcome_template_body    = str_ireplace( '#Courses', self::get_group_courses( $group_id ), $welcome_template_body );

		// Filter #ResetPassword variable
		$reset_password_pattern = '/\#ResetPassword/';
		if ( preg_match( $reset_password_pattern, $welcome_template_subject ) ) {
			$welcome_template_subject = str_ireplace( '#ResetPassword', self::generate_reset_token( $user_id ), $welcome_template_subject );
		}
		if ( preg_match( $reset_password_pattern, $welcome_template_body ) ) {
			$welcome_template_body = str_ireplace( '#ResetPassword', self::generate_reset_token( $user_id ), $welcome_template_body );
		}

		// Remove escaped apostrophes
		$welcome_template_subject = str_replace( "\'", "'", $welcome_template_subject );
		$welcome_template_body    = str_replace( "\'", "'", $welcome_template_body );

		$user_data = array(
			'user_id'    => $user_id,
			'user_email' => $user_email,
			'user_login' => $user_login,
			'first_name' => $first_name,
			'last_name'  => $last_name,
		);

		$to      = apply_filters( 'ulgm_welcome_email_to', $user_email, $user_data, $group_name );
		$subject = apply_filters( 'ulgm_welcome_email_subject', $welcome_template_subject, $user_data, $group_name );
		$body    = apply_filters( 'ulgm_welcome_email_body', $welcome_template_body, $user_data, $group_name );

		if ( ! class_exists( 'WP_Better_Emails' ) || ( false === preg_match( '/\<DOCTYPE/', $body ) && false === preg_match( '/\<head\>/', $body ) ) ) {
			$body = wpautop( $body );
		}

		$send_mail = apply_filters( 'ulgm_maybe_send_welcome_email', true, $to, $subject );
		if ( $send_mail ) {
			$mail_sent = SharedFunctions::wp_mail( $to, $subject, $body, self::get_headers() );

			return $mail_sent;
		} else {
			return true;
		}
	}

	/**
	 * Generates an html anchor link that user can click to generate their
	 * password.
	 *
	 * @param int $user_id The user id.
	 *
	 * @return string The link.
	 */
	public static function generate_reset_token( $user_id ) {

		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return '';
		}
		$adt_rp_key = get_password_reset_key( $user );
		$user_login = $user->user_login;
		$url        = apply_filters(
			'ulgm_reset_password_url',
			network_site_url( "wp-login.php?action=rp&key=$adt_rp_key&login=" . rawurlencode( $user_login ), 'login' ),
			$user_id,
			$user_login,
			$adt_rp_key
		);
		$text       = apply_filters( 'ulgm_reset_password_text', esc_attr__( 'Click here to reset your password.', 'uncanny-learndash-groups' ), $url );

		return sprintf( '<a title="%2$s" href="%1$s">%2$s</a>', $url, $text );
	}

	/**
	 * @param      $user_data
	 * @param      $redemption_template_subject
	 * @param      $redemption_template_body
	 * @param bool $do_not_send_emails
	 * @param bool $counter On error, return(false) or count errors(true)
	 *
	 * @return array success code || error code
	 */
	public static function send_redemption_email( $user_data, $redemption_template_subject, $redemption_template_body, $do_not_send_emails = false, $counter = false ) {

		// Set up user data
		$user_email = $user_data['user_email'];
		$group_name = $user_data['group_name'];
		$first_name = $user_data['first_name'];
		$last_name  = $user_data['last_name'];

		$user = get_user_by( 'email', $user_data['user_email'] );

		$group_id = ulgm_filter_input( 'group-id', INPUT_POST ); // already validate before this function was called

		// If the user exists and is already part of the group there is no need to send an invitation
		if ( $user && learndash_is_user_in_group( $user->ID, (int) $group_id ) ) {
			// User already exists
			if ( ! $counter ) {
				$data['message'] = __( 'This user is already a member of this group.', 'uncanny-learndash-groups' );
				wp_send_json_error( $data );
			} else {
				return array(
					'error-code' => __( 'The user is already a member of this group.', 'uncanny-learndash-groups' ),
					'status'     => array(
						'user'   => 'existing_already_member',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => 'use_error_code',
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		}

		// Check if this invite is already existing.
		$is_already_invited = false;
		$code               = ulgm()->group_management->check_sign_up_code_from_group_id( (int) $group_id, $user_email );

		// if not exist then Get the redemption key
		if ( ! $code && ! is_array( $code ) ) {
			$code = ulgm()->group_management->get_sign_up_code_from_group_id( (int) $group_id );
		} else {
			$is_already_invited = true;
		}

		if ( ! $code || ( is_array( $code ) && isset( $code['message'] ) ) ) {
			if ( ! $counter ) {
				$data['message'] = ! empty( $code['message'] ) ? $code['message'] : sprintf(
					__( 'User has not been added. No %s left.', 'uncanny-learndash-groups' ),
					strtolower( get_option( 'ulgm_per_seat_text_plural', 'Seats' ) )
				);
				wp_send_json_error( $data );
			} else {

				return array(
					'error-code' => ! empty( $code['message'] ) ? $code['message'] : sprintf(
						__( 'The user was not imported because there were no available %s left.', 'uncanny-learndash-groups' ),
						strtolower( get_option( 'ulgm_per_seat_text_plural', 'Seats' ) )
					),
					'status'     => array(
						'user'   => 'new_failed',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => 'use_error_code',
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		}

		// Update codes to table as with set email but user-id 0
		//$added_user = SharedFunctions::set_sign_up_code_status( $code, null, null, SharedFunctions::$not_redeemed_status, (int) $group_id, $user_email, $first_name, $last_name );
		//$code           = ulgm()->group_management->get_sign_up_code_from_group_id( $group_id, 1, $user_id, $order_id, $is_cron );
		$codes_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );
		$data           = array(
			'code'           => $code,
			'user_id'        => null,
			'order_id'       => null,
			'code_status'    => SharedFunctions::$not_redeemed_status,
			'codes_group_id' => $codes_group_id,
			'user_email'     => $user_email,
			'first_name'     => $first_name,
			'last_name'      => $last_name,
		);
		$added_user     = ulgm()->group_management->set_sign_up_code_status( $data );

		// Updates will send false if same values are sent for update.
		if ( $is_already_invited && ! $added_user ) {
			$added_user = true;
		}

		global $wpdb;

		if ( ! $added_user ) {

			if ( ! $counter ) {
				$data['message'] = __( 'The user could not be added. Please contact the website administrator.', 'uncanny-learndash-groups' );
				wp_send_json_error( $data );
			} else {

				return array(
					'error-code' => __( 'The user could not be added. Please contact the website administrator.', 'uncanny-learndash-groups' ),
					'status'     => array(
						'user'   => 'new_failed',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => 'use_error_code',
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		}

		// Filter #email variable
		$redemption_template_subject = str_ireplace( '#FirstName', $first_name, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#FirstName', $first_name, $redemption_template_body );

		// Filter #email variable
		$redemption_template_subject = str_ireplace( '#LastName', $last_name, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#LastName', $last_name, $redemption_template_body );

		// Filter #EmailEncoded variable
		$redemption_template_subject = str_ireplace( '#EmailEncoded', urlencode( $user_email ), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#EmailEncoded', urlencode( $user_email ), $redemption_template_body );

		// Filter #email variable
		$redemption_template_subject = str_ireplace( '#Email', $user_email, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#Email', $user_email, $redemption_template_body );

		// Filter #redemption_key variable
		$redemption_template_subject = str_ireplace( '#RedemptionKey', $code, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#RedemptionKey', $code, $redemption_template_body );

		// Filter #SiteUrl variable
		$redemption_template_subject = str_ireplace( '#SiteUrl', site_url(), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#SiteUrl', site_url(), $redemption_template_body );

		// Filter #LoginUrl variable
		$redemption_template_subject = str_ireplace( '#LoginUrl', wp_login_url(), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#LoginUrl', wp_login_url(), $redemption_template_body );

		// Filter #LoginUrl variable
		$redemption_template_subject = str_ireplace( '#SiteName', get_bloginfo( 'name' ), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#SiteName', get_bloginfo( 'name' ), $redemption_template_body );

		// Filter #LoginUrl variable
		$redemption_template_subject = str_ireplace( '#GroupName', $group_name, $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#GroupName', $group_name, $redemption_template_body );

		// Filter #GroupLeaderInfo variable
		$redemption_template_subject = str_ireplace( '#GroupLeaderInfo', self::get_group_leader_info( $group_id ), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#GroupLeaderInfo', self::get_group_leader_info( $group_id ), $redemption_template_body );

		// Filter #Courses variable
		$redemption_template_subject = str_ireplace( '#Courses', self::get_group_courses( $group_id ), $redemption_template_subject );
		$redemption_template_body    = str_ireplace( '#Courses', self::get_group_courses( $group_id ), $redemption_template_body );

		// Remove escaped apostrophes
		$redemption_template_subject = str_replace( "\'", "'", $redemption_template_subject );
		$redemption_template_body    = str_replace( "\'", "'", $redemption_template_body );

		$do_not_send_emails = apply_filters( 'ulgm_do_not_send_redemption_email', $do_not_send_emails, $user_data, $group_id );

		if ( $do_not_send_emails ) {

			if ( ! $counter ) {
				$data['message'] = __( 'User has been added.', 'uncanny-learndash-groups' );
				SharedFunctions::delete_transient( null, $group_id );
				wp_send_json_success( $data );
			} else {

				return array(
					'success-code' => 'user-added-no-email',
					'status'       => array(
						'user'   => 'new_created_added_to_group',
						// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
						'email'  => 'not_sent',
						//  sent, not_sent, send_failed
						'reason' => __( 'User has been added.', 'uncanny-learndash-groups' ),
						// '' if success or use_error_code or if needed a custom
					),
				);
			}
		} else {
			$to      = apply_filters( 'ulgm_redemption_email_to', $user_email, $user_data, $group_id );
			$subject = apply_filters( 'ulgm_redemption_email_subject', $redemption_template_subject, $user_data, $group_id );
			$body    = apply_filters( 'ulgm_redemption_email_body', $redemption_template_body, $user_data, $group_id );

			if ( ! class_exists( 'WP_Better_Emails' ) || ( false === preg_match( '/\<DOCTYPE/', $body ) && false === preg_match( '/\<head\>/', $body ) ) ) {
				$body = wpautop( $body );
			}

			$send_email = apply_filters( 'ulgm_maybe_send_redemption_email', true, $to, $subject );
			do_action( 'ulgm_redemption_email_sent', $user_data, $group_id );

			if ( $send_email && 'yes' === get_option( 'ulgm_send_code_redemption_email', 'yes' ) ) {
				$redemption_email = SharedFunctions::wp_mail( $to, $subject, $body, self::get_headers() );

				//If the mail is successful let a a fake user and group meta
				if ( is_wp_error( $redemption_email ) ) {

					if ( ! $counter ) {
						$data['message'] = __( 'The invitation email could not be sent. Please contact the website administrator.', 'uncanny-learndash-groups' );
						wp_send_json_success( $data );
					} else {

						return array(
							'error-code' => __( 'The invitation email could not be sent. Please contact the website administrator.', 'uncanny-learndash-groups' ),
							'status'     => array(
								'user'   => 'new_created_added_to_group',
								// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
								'email'  => 'not_sent',
								//  sent, not_sent, send_failed
								'reason' => 'use_error_code',
								// '' if success or use_error_code or if needed a custom
							),
						);
					}
				}

				if ( ! $counter ) {
					$data['message'] = sprintf( __( 'Enrollment key sent to %s.', 'uncanny-learndash-groups' ), $user_email );
					SharedFunctions::delete_transient( null, $group_id );
					wp_send_json_success( $data );
				} else {
					return array(
						'success-code' => 'user-added-email-sent',
						'status'       => array(
							'user'   => 'existing_added_to_group',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'sent',
							//  sent, not_sent, send_failed
							'reason' => __( 'Enrollment key sent.', 'uncanny-learndash-groups' ),
							// '' if success or use_error_code or if needed a custom,
						),
					);
				}
			} else {
				if ( ! $counter ) {
					$data['message'] = __( 'User has been added.', 'uncanny-learndash-groups' );
					SharedFunctions::delete_transient( null, $group_id );
					wp_send_json_success( $data );
				} else {
					return array(
						'success-code' => 'user-added-no-email',
						'status'       => array(
							'user'   => 'new_created_added_to_group',
							// new_created_added_to_group, new_failed, existing_added_to_group, existing_already_member, existing_failed
							'email'  => 'not_sent',
							//  sent, not_sent, send_failed
							'reason' => __( 'User has been added.', 'uncanny-learndash-groups' ),
							// '' if success or use_error_code or if needed a custom
						),
					);
				}
			}
		}
	}

	/**
	 * @param $group_id
	 *
	 * @return string
	 */
	public static function get_group_leader_info( $group_id ) {

		// Default values
		$group_id          = absint( $group_id );
		$group_leaders     = (object) array();
		$group_leader_info = '';

		if ( 0 !== $group_id ) {

			$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrators( $group_id );

			if ( ! empty( $group_leaders ) ) {
				foreach ( $group_leaders as $group_leader ) {

					if ( isset( $group_leader->data ) && isset( $group_leader->data->ID ) && absint( $group_leader->data->ID ) ) {

						$first_name = get_user_meta( $group_leader->data->ID, 'first_name', true );
						if ( ! empty( $first_name ) ) {
							$first_name = $first_name . ' ';
						} else {
							$first_name = '';
						}

						$last_name = get_user_meta( $group_leader->data->ID, 'last_name', true );
						if ( ! empty( $last_name ) ) {
							$last_name = $last_name . ' ';
						} else {
							$last_name = '';
						}

						$add_dash = '';
						if ( ! empty( $first_name ) || ! empty( $last_name ) ) {
							$add_dash = '- ';
						}

						$email             = $group_leader->data->user_email . "\r\n";
						$group_leader_info .= $first_name . $last_name . $add_dash . $email;
					}
				}
			}
		}

		$group_leader_info = apply_filters( 'groups_email_get_group_leader_info_token', $group_leader_info, $group_id, $group_leaders );

		return $group_leader_info;
	}

	/**
	 * @param $group_id
	 *
	 * @return mixed|void
	 */
	public static function get_group_courses( $group_id ) {

		// Default values
		$group_id           = absint( $group_id );
		$group_courses      = (object) array();
		$group_courses_info = '';

		if ( 0 !== $group_id ) {
			$group_courses = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id );

			$group_courses = array_map( 'intval', $group_courses );
			$courses       = new \WP_Query(
				array(
					'post_type'      => 'sfwd-courses',
					'post__in'       => $group_courses,
					'orderby'        => 'post_title',
					'posts_per_page' => 99,
				)
			);

			// The Loop
			if ( $courses->have_posts() ) {
				while ( $courses->have_posts() ) {
					$courses->the_post();
					$group_courses_info .= get_the_title() . "\r\n";
				}
			}

			/* Restore original Post Data */
			wp_reset_postdata();
		}

		$group_courses_info = apply_filters( 'groups_email_get_group_course_info_token', $group_courses_info, $group_id, $group_courses );

		return $group_courses_info;
	}

}
