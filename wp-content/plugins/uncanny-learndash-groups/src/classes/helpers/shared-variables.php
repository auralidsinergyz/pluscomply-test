<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class SharedVariables
 * @package uncanny_learndash_groups
 */
class SharedVariables {

	/**
	 * @param $group_id
	 *
	 * @return int|null
	 */
	public static function get_real_group_id( $group_id ) {
		if ( false === apply_filters( 'ulgm_welcome_emails_check_real_group_id', false, $group_id ) ) {
			return $group_id;
		}
		$ancestors       = get_post_ancestors( $group_id );
		$actual_group_id = array_pop( $ancestors );

		return apply_filters( 'ulgm_welcome_emails_get_real_group_id', $actual_group_id, $group_id );
	}

	/**
	 * User invitation email subject
	 *
	 * @return string
	 */
	public static function ulgm_term_condition() {

		$string = get_option( 'ulgm_term_condition', false );

		if ( ! $string ) {
			$string = '';
		}

		return wp_unslash( $string );
	}

	/**
	 * User invitation email subject
	 *
	 * @return string
	 */
	public static function user_invitation_email_subject() {

		$string = get_option( 'ulgm_invitation_user_email_subject', false );

		if ( ! $string ) {
			$string = __( 'You have been invited to join #SiteUrl', 'uncanny-learndash-groups' );
		}

		return wp_unslash( $string );
	}

	/**
	 * User invitation email body
	 *
	 * @return string
	 */
	public static function user_invitation_email_body() {

		$string = get_option( 'ulgm_invitation_user_email_body', false );

		if ( ! $string ) {
			$string = __( "You have been invited to join #SiteUrl! To accept your invitation, click the link below:\n[Insert registration page link here]\n\nEnter the following code on the registration form to activate your account:\n#RedemptionKey\n\nEnjoy, and welcome to the site!", 'uncanny-learndash-groups' );
		}

		return wp_unslash( $string );
	}

	/**
	 * Add and invite email subject
	 *
	 * @param null $group_id
	 *
	 * @return string
	 */
	public static function user_welcome_email_subject( $group_id = null ) {

		$string = get_option( 'ulgm_user_welcome_email_subject', false );

		if ( ! $string ) {
			$string = __( 'You have been added to #SiteUrl', 'uncanny-learndash-groups' );
		}
		if ( null !== $group_id ) {
			$group_id                = self::get_real_group_id( $group_id );
			$new_user_email_override = get_post_meta( $group_id, 'ulgm_override_user_welcome_email', true );
			if ( 'no' !== $new_user_email_override ) {
				$new_user_email_subject = get_post_meta( $group_id, 'ulgm_override_user_welcome_email_subject', true );
				if ( ! empty( $new_user_email_subject ) ) {
					$string = $new_user_email_subject;
				}
			}
		}

		return apply_filters( 'ulgm_user_welcome_email_subject', wp_unslash( $string ), $group_id );
	}

	/**
	 * Add and invite email body
	 *
	 * @param null $group_id
	 *
	 * @return string
	 */
	public static function user_welcome_email_body( $group_id = null ) {

		$string = get_option( 'ulgm_user_welcome_email_body', false );

		if ( ! $string ) {
			$string = __( "Hi #FirstName,\n\nYou have been added to #SiteUrl.\n\nYour login credentials are:\nLogin: #Username\nPassword: #Password\n\nTo log in, click the link below:\n#LoginUrl\n\n Thanks!", 'uncanny-learndash-groups' );
		}
		if ( null !== $group_id ) {
			$group_id                = self::get_real_group_id( $group_id );
			$new_user_email_override = get_post_meta( $group_id, 'ulgm_override_user_welcome_email', true );
			if ( 'no' !== $new_user_email_override ) {
				$new_user_email_body = get_post_meta( $group_id, 'ulgm_override_user_welcome_email_body', true );
				if ( ! empty( $new_user_email_body ) ) {
					$string = $new_user_email_body;
				}
			}
		}

		return apply_filters( 'ulgm_user_welcome_email_body', wp_unslash( $string ), $group_id );
	}

	/**
	 * Exiting Add and invite email subject
	 *
	 * @param null $group_id
	 *
	 * @return string
	 */
	public static function exiting_user_welcome_email_subject( $group_id = null ) {

		$string = get_option( 'ulgm_existing_user_welcome_email_subject', false );

		if ( ! $string ) {
			$string = __( 'You have been added to a new group on #SiteUrl', 'uncanny-learndash-groups' );
		}
		if ( null !== $group_id ) {
			$group_id                     = self::get_real_group_id( $group_id );
			$existing_user_email_override = get_post_meta( $group_id, 'ulgm_override_existing_user_welcome_email', true );
			if ( 'no' !== $existing_user_email_override ) {
				$existing_user_email_subject = get_post_meta( $group_id, 'ulgm_override_existing_user_welcome_email_subject', true );
				if ( ! empty( $existing_user_email_subject ) ) {
					$string = $existing_user_email_subject;
				}
			}
		}

		return apply_filters( 'ulgm_exiting_user_welcome_email_subject', wp_unslash( $string ), $group_id );
	}

	/**
	 * Existing Add and invite email body
	 *
	 * @param null $group_id
	 *
	 * @return string
	 */
	public static function exiting_user_welcome_email_body( $group_id = null ) {

		$string = get_option( 'ulgm_existing_user_welcome_email_body', false );

		if ( ! $string ) {
			$string = sprintf( __( "Hi #FirstName,\n\nYou have been added to a new group on #SiteUrl!\n\nTo access your new %s, log in here:\n#LoginUrl\n\nThanks!", 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );
		}
		if ( null !== $group_id ) {
			$group_id                     = self::get_real_group_id( $group_id );
			$existing_user_email_override = get_post_meta( $group_id, 'ulgm_override_existing_user_welcome_email', true );
			if ( 'no' !== $existing_user_email_override ) {
				$existing_user_email_body = get_post_meta( $group_id, 'ulgm_override_existing_user_welcome_email_body', true );
				if ( ! empty( $existing_user_email_body ) ) {
					$string = $existing_user_email_body;
				}
			}
		}

		return apply_filters( 'ulgm_exiting_user_welcome_email_body', wp_unslash( $string ), $group_id );
	}

	/**
	 * Add group leader/Create group email subject
	 *
	 * @return string
	 */
	public static function group_leader_welcome_email_subject() {

		$string = get_option( 'ulgm_group_leader_welcome_email_subject', false );

		if ( ! $string ) {
			$string = __( 'You have been added as a group leader on #SiteUrl', 'uncanny-learndash-groups' );
		}

		return wp_unslash( $string );
	}

	/**
	 * Add group leader/Create group email body
	 *
	 * @return string
	 */
	public static function group_leader_welcome_email_body() {

		$string = get_option( 'ulgm_group_leader_welcome_email_body', false );

		if ( ! $string ) {
			$string = __( "Hi #FirstName,\n\nYou have been added as a group leader on #SiteUrl.\n\nYour login credentials are:\nLogin: #Username\nPassword: #Password\n\nTo log in, click the link below:\n#LoginUrl", 'uncanny-learndash-groups' );
		}

		return wp_unslash( $string );
	}


	/**
	 * Existing Add group leader/Create group email subject
	 *
	 * @return string
	 */
	public static function existing_group_leader_welcome_email_subject() {

		$string = get_option( 'ulgm_existing_group_leader_welcome_email_subject', false );

		if ( ! $string ) {
			$string = __( 'You have been promoted to group leader on #SiteUrl', 'uncanny-learndash-groups' );
		}

		return wp_unslash( $string );
	}

	/**
	 * Existing Add group leader/Create group email body
	 *
	 * @return string
	 */
	public static function existing_group_leader_welcome_email_body() {

		$string = get_option( 'ulgm_existing_group_leader_welcome_email_body', false );

		if ( ! $string ) {
			$string = __( "Hi #FirstName,\n\nYou have been promoted to leader of a group on #SiteUrl.\n\nYour login credentials are:\nLogin: #Username\nPassword: #Password\n\nTo log in, click the link below:\n#LoginUrl", 'uncanny-learndash-groups' );
		}

		return wp_unslash( $string );
	}

	/**
	 * New group purchase email subject
	 *
	 * @return string
	 */
	public static function ulgm_new_group_purchase_email_subject() {

		$string = get_option( 'ulgm_new_group_purchase_email_subject', false );

		if ( ! $string ) {
			$string = __( 'New group created', 'uncanny-learndash-groups' );
		}

		return wp_unslash( $string );
	}

	/**
	 * New group purchase email body
	 *
	 * @return string
	 */
	public static function ulgm_new_group_purchase_email_body() {

		$string = get_option( 'ulgm_new_group_purchase_email_body', false );

		if ( ! $string ) {
			$string = __( "Hi #FirstName,\n\nThank you for your purchase!  Your new group, #GroupName, was created successfully.\n\nVisit #LoginUrl to manage your new group.", 'uncanny-learndash-groups' );
		}

		return wp_unslash( $string );
	}
}
