<?php

namespace uncanny_learndash_groups;

/**
 * Class MemberStyleShortcode
 * @package uncanny_learndash_groups
 */
class MemberStyleShortcode {
	/**
	 * MemberStyleShortcode constructor.
	 */
	function __construct() {
		add_shortcode( 'uo_groups_restrict_content', array( $this, 'uo_restrict_content' ) );
		add_shortcode( 'uo_groupleader_restrict_content', array( $this, 'uo_restrict_content_group_leaders' ) );
	}

	/**
	 * @param $atts
	 * @param null $contents
	 *
	 * @return int|null|string
	 */
	public function uo_restrict_content( $atts, $contents = null ) {
		//check tha the user is logged in
		if ( is_user_logged_in() ) {
			if ( ! current_user_can( 'administrator' ) ) {
				$user        = wp_get_current_user();
				$user_id     = $user->ID;
				$user_groups = learndash_get_users_group_ids( $user_id );
				$atts        = shortcode_atts(
					array(
						'user_groups' => 'all',
					),
					$atts,
					'uo_restrict_content' );

				if ( 'all' === $atts['user_groups'] ) {
					return do_shortcode( $contents );
				} else {
					$group_ids = explode( ',', $atts['user_groups'] );
					if ( is_array( $group_ids ) ) {
						foreach ( $group_ids as $g_id ) {
							if ( in_array( $g_id, $user_groups ) ) {
								return do_shortcode( $contents );
							}
						}

						return apply_filters( 'uo_restrict_content_no_access', '', $atts, $contents );
					} else {
						if ( in_array( $atts['user_groups'], $user_groups ) ) {
							return do_shortcode( $contents );
						} else {
							return apply_filters( 'uo_restrict_content_no_access', '', $atts, $contents );
						}
					}
				}
			} elseif ( current_user_can( 'administrator' ) ) {
				return do_shortcode( $contents );
			}
		} else {
			return apply_filters( 'uo_restrict_content_no_access', '', $atts, $contents );
		}
	}

	/**
	 * @param $atts
	 * @param null $contents
	 *
	 * @return int|null|string
	 */
	public function uo_restrict_content_group_leaders( $atts, $contents = null ) {
		//check tha the user is logged in
		if ( is_user_logged_in() ) {
			if ( current_user_can( 'group_leader' ) || current_user_can( 'ulgm_group_management' ) ) {
				$user        = wp_get_current_user();
				$user_id     = $user->ID;
				$user_groups = learndash_get_administrators_group_ids( $user_id );
				$atts        = shortcode_atts(
					[
						'user_groups' => 'all',
					],
					$atts,
					'uo_groupleader_restrict_content' );

				if ( 'all' === $atts['user_groups'] && ! empty( $user_groups ) ) {
					return do_shortcode( $contents );
				} else {
					$group_ids = explode( ',', $atts['user_groups'] );
					if ( is_array( $group_ids ) ) {
						foreach ( $group_ids as $g_id ) {
							if ( in_array( $g_id, $user_groups ) ) {
								return do_shortcode( $contents );
							}
						}

						return apply_filters( 'uo_restrict_content_no_access', '', $atts, $contents );
					} else {
						if ( in_array( $atts['user_groups'], $user_groups ) ) {
							return do_shortcode( $contents );
						} else {
							return apply_filters( 'uo_restrict_content_no_access', '', $atts, $contents );
						}
					}
				}
			} elseif ( current_user_can( 'administrator' ) ) {
				return do_shortcode( $contents );
			}
		} else {
			return apply_filters( 'uo_restrict_content_no_access', '', $atts, $contents );
		}
	}

}
