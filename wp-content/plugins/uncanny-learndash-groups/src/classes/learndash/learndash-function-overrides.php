<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class SharedFunctions
 * @package uncanny_learndash_groups
 */
class LearndashFunctionOverrides {

	/**
	 * @var null
	 */
	private static $instance = null;


	/**
	 * @return LearndashFunctionOverrides|null
	 */
	public static function get_instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * LearndashFunctionOverrides constructor.
	 */
	public function __construct() {
		if ( 'yes' === get_option( 'ld_hide_courses_users_column', 'no' ) ) {
			add_filter( 'learndash_listing_columns', array( $this, 'learndash_listing_columns_func' ), 20, 2 );
		}
	}

	/**
	 * @param $columns
	 * @param $post_type
	 *
	 * @return mixed
	 */
	public function learndash_listing_columns_func( $columns, $post_type ) {
		if ( 'groups' !== (string) $post_type ) {
			return $columns;
		}
		if ( key_exists( 'groups_courses_users', $columns ) ) {
			unset( $columns['groups_courses_users'] );
		}

		return $columns;
	}

	/**
	 * @param $transient
	 *
	 * @return mixed
	 */
	public static function get_ld_transient( $transient ) {

		return get_transient( $transient );
	}

	/**
	 * @param $transient
	 * @param $data
	 */
	public static function set_ld_transient( $transient, $data ) {

		set_transient( $transient, $data, MINUTE_IN_SECONDS );
	}

	/**
	 * @param int $group_id
	 * @param false $bypass_transient
	 * @param array $args
	 *
	 * @return array|mixed
	 */
	public static function learndash_get_groups_users( $group_id = 0, $bypass_transient = false, $args = array() ) {
		$group_id = absint( $group_id );
		// Bail early if group id is not set
		if ( 0 === $group_id || empty( $group_id ) || is_null( $group_id ) ) {
			return array();
		}

		// check if there's transient data available
		if ( false === $bypass_transient ) {
			$transient_key       = 'learndash_group_users_' . $group_id;
			$group_users_objects = self::get_ld_transient( $transient_key );
			if ( ! empty( $group_users_objects ) ) {
				return $group_users_objects;
			}
		}
		$user_query_args = array(
			'orderby'    => 'display_name',
			'order'      => 'ASC',
			'meta_query' => array(
				array(
					'key'     => 'learndash_group_users_' . intval( $group_id ),
					'compare' => 'EXISTS',
				),
			),
		);

		if ( ! empty( $args ) && isset( $args['offset'] ) && 0 !== absint( $args['offset'] ) ) {
			$user_query_args['offset'] = absint( $args['offset'] );
		}

		if ( ! empty( $args ) && isset( $args['length'] ) && 0 !== absint( $args['length'] ) ) {
			$user_query_args['number'] = absint( $args['length'] );
		}

		if ( ! empty( $args ) && isset( $args['search'] ) && ! empty( $args['search'] ) ) {
			$user_query_args['search']         = '*' . $args['search'] . '*';
			$user_query_args['search_columns'] = array( 'ID', 'user_email', 'user_login', 'user_nicename' );
		}

		if ( ! empty( $args ) && isset( $args['orderby'] ) && ! empty( $args['orderby'] ) ) {
			$user_query_args['orderby'] = $args['orderby'];
		}

		if ( ! empty( $args ) && isset( $args['order'] ) && ! empty( $args['order'] ) ) {
			$user_query_args['order'] = $args['order'];
		}

		$user_query = new \WP_User_Query( $user_query_args );

		if ( ! empty( $user_query->get_results() ) ) {
			$results = $user_query->get_results();
		} else {
			$results = array();
		}

		if ( false === $bypass_transient ) {
			self::set_ld_transient( $transient_key, $results );
		}

		return $results;
	}

	/**
	 * @param int $user_id
	 * @param false $bypass_transient
	 *
	 * @return array|mixed
	 */
	public static function learndash_user_get_enrolled_courses( $user_id = 0, $bypass_transient = false ) {
		$user_id = absint( $user_id );

		// Bail early if group id is not set
		if ( 0 === $user_id || empty( $user_id ) || is_null( $user_id ) ) {
			return array();
		}

		// check if there's transient data available
		if ( false === $bypass_transient ) {
			$transient_key        = 'learndash_user_courses_' . $user_id;
			$course_ids_transient = self::get_ld_transient( $transient_key );
			if ( ! empty( $course_ids_transient ) ) {
				return $course_ids_transient;
			}
		}

		// to complicated and extensive work required to move this function. Keeping it as is
		return learndash_user_get_enrolled_courses( $user_id, array(), $bypass_transient );
	}

	/**
	 * @param int $group_id
	 * @param false $bypass_transient
	 *
	 * @return array|mixed
	 */
	public static function learndash_get_groups_user_ids( $group_id = 0, $bypass_transient = false ) {
		$group_id = absint( $group_id );
		// Bail early if group id is not set
		if ( 0 === $group_id || empty( $group_id ) || is_null( $group_id ) ) {
			return array();
		}

		// check if there's transient data available
		if ( false === $bypass_transient ) {
			$transient_key       = 'learndash_group_user_ids_' . $group_id;
			$group_users_objects = self::get_ld_transient( $transient_key );
			if ( ! empty( $group_users_objects ) ) {
				return $group_users_objects;
			}
		}
		global $wpdb;

		$qry     = $wpdb->prepare(
			"SELECT user_id
FROM $wpdb->usermeta
WHERE meta_key LIKE %s
AND meta_value = %d",
			"learndash_group_users_{$group_id}",
			$group_id
		);
		$results = $wpdb->get_col( $qry );

		if ( empty( $results ) ) {
			$results = array();
		}

		if ( false === $bypass_transient ) {
			self::set_ld_transient( $transient_key, $results );
		}

		return $results;
	}

	/**
	 * @param array $group_ids
	 * @param int $user_id
	 * @param false $bypass_transient
	 *
	 * @return array|mixed
	 */
	public static function learndash_get_groups_courses_ids( $group_ids = array(), $user_id = 0, $bypass_transient = false ) {
		$course_ids = array();

		$user_id = absint( $user_id );
		if ( ( is_array( $group_ids ) ) && ( ! empty( $group_ids ) ) ) {
			$group_ids = array_map( 'absint', $group_ids );
		}
		if ( empty( $user_id ) ) {
			// If the current user is not able to be determined. Then abort.
			if ( ! is_user_logged_in() ) {
				return $course_ids;
			}

			$user_id = get_current_user_id();
		}

		if ( learndash_is_group_leader_user( $user_id ) ) {
			$group_leader_group_ids = learndash_get_administrators_group_ids( $user_id );

			// If user is group leader and the group ids is empty, nothing else to do. abort.
			if ( empty( $group_leader_group_ids ) ) {
				return $course_ids;
			}

			if ( empty( $group_ids ) ) {
				$group_ids = $group_leader_group_ids;
			} else {
				$group_ids = array_intersect( $group_leader_group_ids, $group_ids );
			}
		} elseif ( learndash_is_admin_user( $user_id ) ) {
		} else {
			return $course_ids;
		}
		if ( ! empty( $group_ids ) ) {

			foreach ( $group_ids as $group_id ) {
				$group_course_ids = learndash_group_enrolled_courses( $group_id, $bypass_transient );
				if ( ! empty( $group_course_ids ) ) {
					$course_ids = array_merge( $course_ids, $group_course_ids );
				}
			}
		}

		if ( ! empty( $course_ids ) ) {
			$course_ids = array_unique( $course_ids );
		}

		return $course_ids;

	}

	/**
	 * @param int $group_id
	 * @param false $bypass_transient
	 *
	 * @return array|mixed
	 */
	public static function learndash_group_enrolled_courses( $group_id = 0, $bypass_transient = false, $disable_hierarchy = false ) {

		// For group hierarchy support
		$is_hierarchy_setting_enabled = false;
		if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
			$is_hierarchy_setting_enabled = true;
		}

		if ( $disable_hierarchy ) {
			$is_hierarchy_setting_enabled = false;
		}

		$is_hierarchy_setting_enabled = apply_filters(
			'ulgm_is_hierarchy_setting_enabled',
			$is_hierarchy_setting_enabled,
			$group_id,
			$bypass_transient,
			$disable_hierarchy
		);

		$group_id = absint( $group_id );
		// Bail early if group id is not set
		if ( 0 === $group_id || empty( $group_id ) || is_null( $group_id ) ) {
			return array();
		}

		// check if there's transient data available
		if ( false === $bypass_transient ) {
			$transient_key = 'learndash_group_enrolled_courses_' . $group_id;
			if ( $is_hierarchy_setting_enabled ) {
				$transient_key .= '_hierarchy';
			}
			$group_users_objects = self::get_ld_transient( $transient_key );
			if ( ! empty( $group_users_objects ) ) {
				return $group_users_objects;
			}
		}

		$search_condition = " meta_key LIKE 'learndash_group_enrolled_{$group_id}' ";
		if ( $is_hierarchy_setting_enabled ) {
			$group_children = learndash_get_group_children( $group_id );
			if ( ! empty( $group_children ) ) {
				foreach ( $group_children as $child_group_id ) {
					$child_group_id   = absint( $child_group_id );
					$search_condition .= " OR meta_key LIKE 'learndash_group_enrolled_{$child_group_id}' ";
				}
			}
		}

		global $wpdb;

		$qry = "SELECT pm.post_id FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts p ON p.ID = pm.post_id WHERE 1=1 AND p.post_status = 'publish' AND ( $search_condition ) ";

		$results = $wpdb->get_col( $qry );

		if ( empty( $results ) ) {
			$results = array();
		}

		if ( ! empty( $results ) ) {
			$results = array_values( array_unique( $results ) );
		}

		/*
		 * Filter for customizing group courses
		 * ulgm_learndash_group_enrolled_courses
		 */

		$results = apply_filters( 'ulgm_learndash_group_enrolled_courses', $results, $group_id );

		if ( false === $bypass_transient ) {
			self::set_ld_transient( $transient_key, $results );
		}

		return $results;
	}

	/**
	 * @param int $group_id
	 * @param false $bypass_transient
	 *
	 * @return array|mixed
	 */
	public static function learndash_get_groups_administrator_ids( $group_id = 0, $bypass_transient = false ) {
		$group_id = absint( $group_id );
		// Bail early if group id is not set
		if ( 0 === $group_id || empty( $group_id ) || is_null( $group_id ) ) {
			return array();
		}

		// check if there's transient data available
		if ( false === $bypass_transient ) {
			$transient_key       = 'learndash_group_leader_ids_' . $group_id;
			$group_users_objects = self::get_ld_transient( $transient_key );
			if ( ! empty( $group_users_objects ) ) {
				return $group_users_objects;
			}
		}

		global $wpdb;

		$qry     = $wpdb->prepare(
			"SELECT user_id
FROM $wpdb->usermeta
WHERE meta_key LIKE %s
AND meta_value = %d",
			"learndash_group_leaders_{$group_id}",
			$group_id
		);
		$results = $wpdb->get_col( $qry );

		if ( empty( $results ) ) {
			$results = array();
		}

		if ( false === $bypass_transient ) {
			self::set_ld_transient( $transient_key, $results );
		}

		return $results;
	}

	/**
	 * @param int $group_id
	 * @param false $bypass_transient
	 *
	 * @return array|mixed
	 */
	public static function learndash_get_groups_administrators( $group_id = 0, $bypass_transient = false ) {
		$group_id = absint( $group_id );
		// Bail early if group id is not set
		if ( 0 === $group_id || empty( $group_id ) || is_null( $group_id ) ) {
			return array();
		}

		// check if there's transient data available
		if ( false === $bypass_transient ) {
			$transient_key       = 'learndash_group_leaders_' . $group_id;
			$group_users_objects = self::get_ld_transient( $transient_key );
			if ( ! empty( $group_users_objects ) ) {
				return $group_users_objects;
			}
		}

		$user_query_args = array(
			'orderby'    => 'display_name',
			'order'      => 'ASC',
			'meta_query' => array(
				array(
					'key'     => 'learndash_group_leaders_' . intval( $group_id ),
					'value'   => intval( $group_id ),
					'compare' => '=',
					'type'    => 'NUMERIC',
				),
			),
		);

		$user_query = new \WP_User_Query( $user_query_args );
		if ( ! empty( $user_query->get_results() ) ) {
			$group_user_objects = $user_query->get_results();
		} else {
			$group_user_objects = array();
		}

		if ( false === $bypass_transient ) {
			self::set_ld_transient( $transient_key, $group_user_objects );
		}

		return $group_user_objects;
	}

	/**
	 * @param int $user_id
	 *
	 * @return array
	 */
	public static function learndash_get_administrators_group_ids( $user_id = 0 ) {
		global $wpdb;

		$group_ids = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key LIKE %s AND user_id = %d", 'learndash_group_leaders_%%', $user_id ) );

		return array_map( 'absint', $group_ids );
	}

	/**
	 * @param int $group_id
	 * @param array $args
	 *
	 * @return int
	 */
	public static function get_group_id_user_count( $group_id = 0, $args = array() ) {

		global $wpdb;

		$search = '';
		if ( ! empty( $args ) && isset( $args['search'] ) && ! empty( $args['search'] ) ) {
			$search = '%' . $wpdb->esc_like( $args['search'] ) . '%';
		}

		if ( ! empty( $search ) ) {
			$qry = $wpdb->prepare(
				"SELECT u.ID
FROM $wpdb->users u
LEFT JOIN $wpdb->usermeta um
ON u.ID = um.user_id
WHERE um.meta_key LIKE '%s'
  AND (
      u.user_login LIKE '%s'
          OR u.user_email LIKE '%s'
          OR u.user_nicename LIKE '%s'
          OR u.ID like '%s'
    )",
				'learndash_group_users_' . intval( $group_id ),
				$search,
				$search,
				$search,
				$search
			);
		} else {
			$qry = $wpdb->prepare(
				"SELECT um.user_id
						FROM $wpdb->usermeta um
						LEFT JOIN $wpdb->users u
						ON u.ID = um.user_id
						WHERE um.meta_key LIKE %s",
				'learndash_group_users_' . intval( $group_id )
			);
		}

		return count( $wpdb->get_col( $qry ) );
	}


	/**
	 * @param int $group_id
	 * @param array $args
	 * @param false $user_id
	 *
	 * @return array|object|null
	 */
	public static function ulgm_get_group_users( $group_id = 0, $args = array(), $user_id = false ) {
		global $wpdb;
		$children_groups = array( $group_id );

		// For group hierarchy support
		$is_hierarchy_setting_enabled = false;
		if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
			$is_hierarchy_setting_enabled = true;
		}

		if ( isset( $args['hierarchy-disable'] ) && $args['hierarchy-disable'] ) {
			$is_hierarchy_setting_enabled = false;
		}
		if ( $is_hierarchy_setting_enabled ) {
			$children_groups   = learndash_get_group_children( $group_id );
			$children_groups[] = $group_id;
		}
		// Default values
		$offset  = 0;
		$total   = 500;
		$order   = 'ASC';
		$orderby = 'first_name';
		$search  = '';

		if ( ! empty( $args ) && isset( $args['start'] ) && 0 !== absint( $args['start'] ) ) {
			$offset = absint( $args['start'] );
		}

		if ( ! empty( $args ) && isset( $args['length'] ) && 0 !== absint( $args['length'] ) ) {
			$total = $offset + absint( $args['length'] );
		}

		if ( ! empty( $args ) && isset( $args['search'] ) && ! empty( $args['search'] ) ) {
			$search = '%' . $wpdb->esc_like( $args['search'] ) . '%';
		}

		if ( ! empty( $args ) && isset( $args['order'] ) && ( 'asc' === $args['order'] || 'desc' === $args['order'] ) ) {
			// Datables ass lowercase asc or desc and mysql standards in uppercase
			$order = strtoupper( $args['order'] );
		}

		if ( ! empty( $args ) && isset( $args['orderby'] ) && ! empty( $args['orderby'] ) ) {
			switch ( $args['orderby'] ) {
				case 'first_name':
					$orderby = 'first_name';
					break;
				case 'last_name':
					$orderby = 'last_name';
					break;
				case 'email':
					$orderby = 'u.user_email';
					break;
				case 'status':
					$orderby = 'u.first_name';
					break;
				case 'key':
					$orderby = 'u.first_name';
					break;
			}
		}
		$ms_query = '';
		if ( is_multisite() && get_current_network_id() !== get_current_blog_id() ) {
			$ms_query = $wpdb->prepare( "INNER JOIN $wpdb->usermeta um99 ON u.ID = um99.user_id AND um99.meta_key = %s", sprintf( '%suser_level', $wpdb->prefix ) );
		}

		$data = array();

		foreach ( $children_groups as $group_id ) {
			if ( ! empty( $search ) ) {

				$qry = $wpdb->prepare(
					"SELECT u.ID, u.user_login, u.user_email, u.user_nicename, um1.meta_value AS first_name, um2.meta_value AS last_name
						FROM $wpdb->users u $ms_query
						LEFT JOIN $wpdb->usermeta um
						ON u.ID = um.user_id
						LEFT JOIN $wpdb->usermeta um1
						ON u.ID = um1.user_id AND um1.meta_key = 'first_name'
						LEFT JOIN $wpdb->usermeta um2
						ON u.ID = um2.user_id AND um2.meta_key = 'last_name'
						WHERE um.meta_key LIKE %s AND (
							u.user_login LIKE '%s'
   							OR u.user_email LIKE '%s'
  							OR u.user_nicename LIKE '%s'
							OR u.ID like '%s'
							)
   						ORDER BY {$orderby} {$order} LIMIT %d, %d",
					'learndash_group_users_' . intval( $group_id ),
					$search,
					$search,
					$search,
					$search,
					$offset,
					$total
				);
			} elseif ( false !== $user_id && absint( $user_id ) ) {
				$qry = $wpdb->prepare(
					"SELECT u.ID, u.user_login, u.user_email, u.user_nicename, um1.meta_value AS first_name, um2.meta_value AS last_name
						FROM $wpdb->usermeta um
						LEFT JOIN $wpdb->users u
						ON u.ID = um.user_id $ms_query
						LEFT JOIN $wpdb->usermeta um1
						ON u.ID = um1.user_id AND um1.meta_key = 'first_name'
						LEFT JOIN $wpdb->usermeta um2
						ON u.ID = um2.user_id AND um2.meta_key = 'last_name'
						WHERE um.meta_key LIKE %s
						AND u.ID = '%d'
						ORDER BY {$orderby} {$order} LIMIT %d, %d",
					'learndash_group_users_' . intval( $group_id ),
					$user_id,
					$offset,
					$total
				);
			} else {
				$qry = $wpdb->prepare(
					"SELECT u.ID, u.user_login, u.user_email, u.user_nicename, um1.meta_value AS first_name, um2.meta_value AS last_name
						FROM $wpdb->usermeta um
						LEFT JOIN $wpdb->users u
						ON u.ID = um.user_id
						$ms_query
						LEFT JOIN $wpdb->usermeta um1
						ON u.ID = um1.user_id AND um1.meta_key = 'first_name'
						LEFT JOIN $wpdb->usermeta um2
						ON u.ID = um2.user_id AND um2.meta_key = 'last_name'
						WHERE um.meta_key LIKE %s
						ORDER BY {$orderby} {$order} LIMIT %d, %d",
					'learndash_group_users_' . intval( $group_id ),
					$offset,
					$total
				);
			}

			$results = $wpdb->get_results( $qry );
			if ( ! empty( $results ) ) {
				$data = array_merge( $data, $results );
			}
		}

		if ( ! empty( $data ) ) {
			$temp_results = array();
			$i            = 0;
			$key_array    = array();

			foreach ( $data as $val ) {
				if ( ! in_array( $val->ID, $key_array ) ) {
					$key_array[ $i ]    = $val->ID;
					$temp_results[ $i ] = $val;
				}
				$i ++;
			}
			$data = $temp_results;
		}

		/*
		 * Filter for customizing group users
		 * ulgm_learndash_group_enrolled_users
		 */

		$data = apply_filters( 'ulgm_learndash_group_enrolled_users', $data, $group_id, $args, $user_id );

		return $data;
	}
}
