<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class DataShortcodes
 * @package uncanny_learndash_groups
 */
class UserCodeRedemption {

	/**
	 * class constructor
	 */
	public function __construct() {

		add_action( 'learndash_topic_completed', array( $this, 'redeem_code' ), 10, 1 );
		add_action( 'learndash_lesson_completed', array( $this, 'redeem_code' ), 10, 1 );
		add_action( 'learndash_course_completed', array( $this, 'redeem_code' ), 10, 1 );

	}

	/**
	 * This shortcode displays the amount of total seats for a group
	 *
	 * @since 1.0
	 *
	 */
	public function redeem_code( $data ) {

		// Course ID of the course, lesson, or topic completed
		$course_id = $data['course']->ID;

		// Current Users ID
		$user_id = get_current_user_id();

		// Is the user really logged in
		if ( $user_id ) {

			// Get all users group IDS
			$users_group_ids = learndash_get_users_group_ids( $user_id );

			// Are they in any groups
			if ( ! empty( $users_group_ids ) ) {

				// Loop through all the groups that they are in
				foreach ( $users_group_ids as $group_id ) {

					// Get all the courses that are assigned to the group
					$group_course_ids = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id );

					// Are there any courses assigned to the group
					if ( ! empty( $group_course_ids ) ) {

						// Loop through all the courses in the group
						foreach ( $group_course_ids as $group_course_id ) {

							// If the completed course's course lesson or topic are in the group
							if ( $group_course_id == $course_id ) {
								// Then they have completed a module with the group and need to have there code officially redeemed
								//$code_group_id = get_post_meta( $group_id, SharedFunctions::$code_group_id_meta_key, true );
								$code_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );
								SharedFunctions::redeem_all_pending_group_codes( $user_id, $code_group_id );
							}
						}
					}
				}
			}
		}


	}
}
