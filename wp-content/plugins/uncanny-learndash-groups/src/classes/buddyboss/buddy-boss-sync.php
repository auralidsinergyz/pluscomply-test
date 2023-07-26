<?php


namespace uncanny_learndash_groups;

/**
 * Class BuddyBossSync
 * @package uncanny_learndash_groups
 */
class BuddyBossSync {
	/**
	 * BuddyBossSync constructor.
	 */
	public function __construct() {
		// when group is created
		add_action( 'init', array( $this, 'forced_logout' ), 1 );
		add_action( 'uo_new_group_created', array( $this, 'group_created' ), 20, 2 );
		add_action( 'uo_new_group_purchased', array( $this, 'group_created' ), 20, 2 );

		// for group leaders
		add_action( 'ld_added_leader_group_access', array( $this, 'group_leader_added' ), 20, 2 );
		add_action( 'ld_removed_leader_group_access', array( $this, 'group_leader_removed' ), 20, 2 );

		// for students/users
		add_action( 'ld_added_group_access', array( $this, 'user_added_to_group' ), 20, 2 );
		add_action( 'ld_removed_group_access', array( $this, 'user_removed_from_group' ), 20, 2 );

		// for courses
		add_action( 'ld_added_course_group_access', array( $this, 'course_added_to_group' ), 20, 2 );
		add_action( 'ld_removed_course_group_access', array( $this, 'course_removed_from_group' ), 20, 2 );

	}

	/**
	 * @param $group_id
	 */
	public function group_created( $group_id, $user_id ) {
		if ( true === apply_filters( 'ulgm_group_created_buddyboss_force_login', true, $group_id, $user_id, $this ) ) {
			$this->force_login( $user_id );
		}
		add_filter( 'bp_loggedin_user_id', array( $this, 'bp_loggedin_user_id' ), 99, 1 );
		do_action( 'bp_ld_sync/learndash_group_updated', $group_id ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
	}

	/**
	 * @param $user_id
	 *
	 * @return void
	 */
	private function force_login( $user_id ) {
		if ( ! is_user_logged_in() && $user_id ) {
			$user = get_user_by( 'id', $user_id );

			if ( $user ) {
				wp_set_current_user( $user_id, $user->user_login );
				wp_set_auth_cookie( $user_id );
				update_user_meta( $user_id, 'uog_forced_login', 1 );
			}
		}
	}

	/**
	 * @return void
	 */
	public function forced_logout() {
		if ( true !== apply_filters( 'ulgm_group_created_buddyboss_force_logout', true, $this ) ) {
			return;
		}
		if ( is_user_logged_in() ) {

			$user_id          = get_current_user_id();
			$uog_forced_login = get_user_meta( $user_id, 'uog_forced_login', true );
			if ( $uog_forced_login ) {
				wp_logout();
				update_user_meta( $user_id, 'uog_forced_login', 0 );
			}
		}
	}

	/**
	 * @param $user_id
	 */
	public function bp_loggedin_user_id( $user_id ) {
		if ( ! $user_id ) {
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();
			}
		}

		return $user_id;
	}

	/**
	 * @param $user_id
	 * @param $group_id
	 */
	public function group_leader_added( $user_id, $group_id ) {
		do_action( 'bp_ld_sync/learndash_group_admin_added', $group_id, $user_id );
	}

	/**
	 * @param $user_id
	 * @param $group_id
	 */
	public function group_leader_removed( $user_id, $group_id ) {
		do_action( 'bp_ld_sync/learndash_group_admin_removed', $group_id, $user_id );
	}

	/**
	 * @param $user_id
	 * @param $group_id
	 */
	public function user_added_to_group( $user_id, $group_id ) {
		do_action( 'bp_ld_sync/learndash_group_user_added', $group_id, $user_id );
	}

	/**
	 * @param $user_id
	 * @param $group_id
	 */
	public function user_removed_from_group( $user_id, $group_id ) {
		do_action( 'bp_ld_sync/learndash_group_user_removed', $group_id, $user_id );
	}

	/**
	 * @param $course_id
	 * @param $group_id
	 */
	public function course_added_to_group( $course_id, $group_id ) {
		do_action( 'bp_ld_sync/learndash_group_course_added', $group_id, $course_id );
	}

	/**
	 * @param $course_id
	 * @param $group_id
	 */
	public function course_removed_from_group( $course_id, $group_id ) {
		do_action( 'bp_ld_sync/learndash_group_course_deleted', $group_id, $course_id );
	}
}
