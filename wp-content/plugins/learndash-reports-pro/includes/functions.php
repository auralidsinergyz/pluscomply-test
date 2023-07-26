<?php
/**
 * This file is used to define pluggable functions.
 *
 * @package Quiz Reporting Extension.
 */

if ( ! function_exists( 'check_isset' ) ) {
	/**
	 * Checks variable and if isset true returns variable else ''
	 *
	 * @since     1.0.0
	 *
	 * @param array  $variable Input Array.
	 * @param string $parameter Key to check.
	 *
	 * @return variable or null
	 */
	function check_isset( $variable, $parameter ) {
		if ( isset( $variable[ $parameter ] ) ) {
			return $variable[ $parameter ];
		}
		return '';
	}
}

if ( ! function_exists( 'check_isset_var' ) ) {
	/**
	 * Verifies whether variable is set or empty
	 *
	 * @param  mixed $variable Variable to check.
	 * @return  $variable or ''
	 **/
	function check_isset_var( $variable ) {
		if ( isset( $variable ) ) {
			return $variable;
		}

		return '';
	}
}

if ( ! function_exists( 'remove_empty_array_items' ) ) {
	/**
	 * Remove_empty_array_items
	 * To get result
	 *
	 * @param  array $prevresult Input array.
	 * @return array $result Output array.
	 **/
	function remove_empty_array_items( $prevresult ) {
		if ( is_array( $prevresult ) ) {
			$result = array_filter( $prevresult );

			return $result;
		}

		return $prevresult;
	}
}

if ( ! function_exists( 'init_key_as_empty_array' ) ) {
	/**
	 * Return value of a particular key.
	 *
	 * @param array $arr_process_data Input array.
	 * @param int   $ref_id Key to check.
	 * @return mixed Value of the key or empty array.
	 */
	function init_key_as_empty_array( $arr_process_data, $ref_id ) {
		if ( ( ! isset( $arr_process_data[ $ref_id ] ) ) || ( ! is_array( $arr_process_data[ $ref_id ] ) ) ) {
			$arr_process_data[ $ref_id ] = array();
		}
		return $arr_process_data[ $ref_id ];
	}
}

if ( ! function_exists( 'get_protected_value' ) ) {

	/**
	 * [get_protected_value To access protected elements from the object as an array].
	 *
	 * @param object $obj  object to get protected fields.
	 * @param string $name field name from the object.
	 *
	 * @return [array] [associative array of an protected field]
	 */
	function get_protected_value( $obj, $name ) {
		$array  = (array) $obj;
		$prefix = chr( 0 ) . '*' . chr( 0 );

		return $array[ $prefix . $name ];
	}
}

if ( ! function_exists( 'array_column_ext' ) ) {
	/**
	 * An extended version of array_column that
	 * preserves the associated array key values if you give an $indexkey value of -1 to it.
	 *
	 * @param  array  $array     Input array.
	 * @param  string $columnkey Column to extract.
	 * @param  string $indexkey  Column to set as key for the output array.
	 * When you give an $indexkey value of -1 it preserves the associated array key values.
	 * @return array Output array.
	 */
	function array_column_ext( $array, $columnkey, $indexkey = null ) {
		$result = array();
		foreach ( $array as $subarray => $value ) {
			if ( array_key_exists( $columnkey, $value ) ) {
				$val = $array[ $subarray ][ $columnkey ];
			} elseif ( null === $columnkey ) {
				$val = $value;
			} else {
				continue;
			}

			if ( null === $indexkey ) {
				$result[] = $val;
			} elseif ( -1 === $indexkey || array_key_exists( $indexkey, $value ) ) {
				$result[ ( -1 === $indexkey ) ? $subarray : $array[ $subarray ][ $indexkey ] ] = $val;
			}
		}
		return $result;
	}
}

if ( ! function_exists( 'qre_search_quizzes' ) ) {
	/**
	 * Function is used to get quizzes that match the queried string.
	 *
	 * @param  string  $query Search Query.
	 * @param  boolean $skip_search Whether to skip searching and fetch all results.
	 * @return array   $results Search Results
	 */
	function qre_search_quizzes( $query = '', $skip_search = false ) {
		if ( ! is_user_logged_in() ) {
			return array();
		}
		$args = array(
			'post_type'      => 'sfwd-quiz',
			'posts_per_page' => -1,
		);
		if ( false === $skip_search ) {
			$args['s'] = $query;
		}
		$quizzes = get_posts( $args );
		if ( empty( $quizzes ) ) {
			return $quizzes;
		}
		// Filter out items for which user doesn't have access.
		$quizzes = array_filter(
			$quizzes,
			function( $quiz ) {
				$current_user         = wp_get_current_user();
				$user_managed_courses = qre_get_user_managed_group_courses();

				$is_quiz_accessible = qre_check_if_quiz_accessible( $quiz->ID, $current_user, $user_managed_courses );
				if ( ! $is_quiz_accessible || is_wp_error( $is_quiz_accessible ) ) {
					return false;
				}
				return true;
			}
		);
		$results = array_map(
			function( $quiz ) use ( $query ) {
				$item = array(
					'post'  => $quiz,
					'title' => $quiz->post_title,
					'type'  => 'post',
					'ID'    => $quiz->ID,
					'icon'  => 'quiz_icon',
				);
				return $item;
			},
			$quizzes
		);
		return $results;
	}
}

if ( ! function_exists( 'qre_search_courses' ) ) {
	/**
	 * Function is used to get quizzes that match the queried string.
	 *
	 * @param  string  $query Search Query.
	 * @param  boolean $skip_search Whether to skip searching and fetch all results.
	 * @return array   $results Search Results
	 */
	function qre_search_courses( $query = '', $skip_search = false ) {
		if ( ! is_user_logged_in() ) {
			return array();
		}
		$excluded_courses = get_option( 'exclude_courses', false );
		$args             = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => -1,
		);
		if ( ! empty( $excluded_courses ) ) {
			$args['exclude'] = $excluded_courses;
		}
		if ( false === $skip_search ) {
			$args['s'] = $query;
		}
		$courses = get_posts( $args );
		if ( empty( $courses ) ) {
			return $courses;
		}
		// Filter out items for which user doesn't have access.
		$courses = array_filter(
			$courses,
			function( $course ) {
				$current_user         = wp_get_current_user();
				$user_managed_courses = qre_get_user_managed_group_courses();

				$is_course_accessible = qre_check_if_course_accessible( $course->ID, $current_user, $user_managed_courses );
				if ( ! $is_course_accessible || is_wp_error( $is_course_accessible ) ) {
					return false;
				}
				return true;
			}
		);
		$results = array_map(
			function( $course ) use ( $query ) {
				$item = array(
					'post'  => $course,
					'title' => $course->post_title,
					'type'  => 'post',
					'ID'    => $course->ID,
					'icon'  => 'quiz_icon',
				);
				return $item;
			},
			$courses
		);
		return $results;
	}
}

if ( ! function_exists( 'qre_search_users' ) ) {
	/**
	 * Function is used to get users that match the queried string.
	 *
	 * @param  string  $search_string Search Query.
	 * @param  boolean $skip_search Whether to skip searching and fetch all results.[WARNING: Skipping search isn't recommended. For systems that have large number of users, the system may crash due to memory shortage].
	 * @return array   $results Search Results.
	 */
	function qre_search_users( $search_string = '', $skip_search = false ) {
		if ( ! is_user_logged_in() ) {
			return array();
		}
		$excluded_users = get_option( 'exclude_users', false );
		$exclude_ur     = get_option( 'exclude_ur', false );
		if ( ! empty( $excluded_users ) ) {
			$args['exclude'] = $excluded_users;
		}
		if ( ! empty( $excluded_ur ) ) {
			$args['role__not_in'] = $exclude_ur;
		}
		if ( false === $skip_search ) {
			$args        = array(
				'search'         => "*{$search_string}*",
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
				),
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'first_name',
						'value'   => $search_string,
						'compare' => 'LIKE',
					),
					array(
						'key'     => 'last_name',
						'value'   => $search_string,
						'compare' => 'LIKE',
					),
				),
			);
			$users       = new WP_User_Query( $args );
			$users_found = (array) $users->get_results();
		} else {
			$users_found = get_users( $args );
		}
		if ( empty( $users_found ) ) {
			return $users_found;
		}
		// Filter out items for which user doesn't have access.
		$users_found = array_filter(
			$users_found,
			function( $user ) {
				$current_user         = wp_get_current_user();
				$user_managed_courses = qre_get_user_managed_group_courses();

				$is_user_accessible = qre_check_if_user_accessible( $user->ID, $current_user, $user_managed_courses );
				if ( ! $is_user_accessible || is_wp_error( $is_user_accessible ) ) {
					return false;
				}
				return true;
			}
		);
		$results     = array_map(
			function( $user ) use ( $search_string ) {
				$item = array(
					'user'  => $user,
					'title' => $user->display_name,
					'type'  => 'user',
					'ID'    => $user->ID,
					'icon'  => '',
				);
				if ( empty( $search_string ) ) {
					return $item;
				}
				$item['icon'] = 'user_icon';
				return $item;
			},
			$users_found
		);
		return $results;
	}
}

if ( ! function_exists( 'qre_get_user_managed_group_courses' ) ) {
	/**
	 * Get Courses of Groups where current user is the Group Leader.
	 *
	 * @return array User's Group affialiated Courses.
	 */
	function qre_get_user_managed_group_courses() {
		global $group_leader_managed_courses;
		$courses = array();
		$user    = wp_get_current_user();
		if ( ! in_array( 'group_leader', (array) $user->roles ) ) {// phpcs:ignore
			return $courses;
		}

		if ( isset( $group_leader_managed_courses ) && ! empty( $group_leader_managed_courses ) ) {
			return $group_leader_managed_courses;
		}

		$associated_groups = learndash_get_administrators_group_ids( get_current_user_id() );

		if ( ! empty( $associated_groups ) ) {
			foreach ( $associated_groups as $associated_group ) {
				if ( empty( $courses ) ) {
					$courses = learndash_group_enrolled_courses( $associated_group );
				} else {
					$courses = array_merge( $courses, learndash_group_enrolled_courses( $associated_group ) );
				}
			}
		}
		$group_leader_managed_courses = $courses;
		return $courses;
	}
}


if ( ! function_exists( 'qre_check_if_accessible' ) ) {
	/**
	 * Function is used to check whether the requested resource(user/lesson/topic/quiz) is accessible to the user.
	 *
	 * @param  string  $query_type     Requested Resource.
	 * @param  integer $queried_obj_id Resource ID.
	 * @return boolean/wp_error
	 */
	function qre_check_if_accessible( $query_type, $queried_obj_id ) {
		if ( ! in_array( $query_type, array( 'user', 'post' ), true ) ) {
			return new \WP_Error( 400, __( 'Invalid Resource Type.', 'learndash-reports-pro' ) );
		}
		$user                 = wp_get_current_user();
		$user_managed_courses = qre_get_user_managed_group_courses();
		if ( 'post' === $query_type ) {
			if ( 'sfwd-quiz' === get_post_type( $queried_obj_id ) ) {
				return qre_check_if_quiz_accessible( $queried_obj_id, $user, $user_managed_courses );
			} else {
				return qre_check_if_course_accessible( $queried_obj_id, $user, $user_managed_courses );
			}
		}
		if ( 'user' === $query_type ) {
			return qre_check_if_user_accessible( $queried_obj_id, $user, $user_managed_courses );
		}
	}
}

if ( ! function_exists( 'qre_check_if_quiz_accessible' ) ) {
	/**
	 * This function is used to check whether the requested quiz is accessible by the user.
	 *
	 * @param  integer $queried_obj_id       Quiz ID.
	 * @param  WP_User $user                 User requesting resource.
	 * @param  array   $user_managed_courses User's Managed Courses(for Group Leaders).
	 * @return boolean/wp_error
	 */
	function qre_check_if_quiz_accessible( $queried_obj_id, $user, $user_managed_courses ) {
		$quiz_id = \Quiz_Export_Db::instance()->check_if_quiz_ids_actually_present( $queried_obj_id );
		if ( empty( $quiz_id ) ) {
			return new \WP_Error( 400, __( 'Invalid Request. Quiz ID doesn\'t exist.', 'learndash-reports-pro' ) );
		}
		// Check for Administrator.
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		$queried_course_id = learndash_get_course_id( $queried_obj_id );
		// Check for Group Leader.
		if ( ! empty( $user_managed_courses ) ) {
			if ( in_array( $queried_course_id, $user_managed_courses ) ) {// phpcs:ignore
				return true;
			}
		}
		// Check for Instructor.
		if ( function_exists( 'ir_get_instructor_complete_course_list' ) && in_array( 'wdm_instructor', (array) $user->roles, true ) ) {
			$instructor_course_ids = ir_get_instructor_complete_course_list( $user->ID );
			if ( in_array( $queried_course_id, $instructor_course_ids ) ) {// phpcs:ignore
				return true;
			}
		}
		$user_enrolled_course_ids = learndash_user_get_enrolled_courses( $user->ID, array(), true );
		// Check for Subscriber.
		if ( in_array( $queried_course_id, $user_enrolled_course_ids ) ) {// phpcs:ignore
			return true;
		}
		// Check if stats exist regardless of course enrollment.
		$pro_quiz_id    = get_post_meta( $queried_obj_id, 'quiz_pro_id', true );
		$existing_stats = \Quiz_Export_Db::instance()->get_all_statistic_ref_ids(
			array(
				'user_ids' => array( $user->ID ),
				'quiz_ids' => array( $pro_quiz_id ),
			)
		);
		if ( ! empty( $existing_stats ) ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'qre_check_if_course_accessible' ) ) {
	/**
	 * This function is used to check whether the requested quiz is accessible by the user.
	 *
	 * @param  integer $queried_obj_id       Quiz ID.
	 * @param  WP_User $user                 User requesting resource.
	 * @param  array   $user_managed_courses User's Managed Courses(for Group Leaders).
	 * @return boolean/wp_error
	 */
	function qre_check_if_course_accessible( $queried_obj_id, $user, $user_managed_courses ) {
		// Check for Administrator.
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		$queried_course_id = $queried_obj_id;
		// Check for Group Leader.
		if ( ! empty( $user_managed_courses ) ) {
			if ( in_array( $queried_course_id, $user_managed_courses ) ) {// phpcs:ignore
				return true;
			}
		}
		// Check for Instructor.
		if ( function_exists( 'ir_get_instructor_complete_course_list' ) && in_array( 'wdm_instructor', (array) $user->roles, true ) ) {
			$instructor_course_ids = ir_get_instructor_complete_course_list( $user->ID );
			if ( in_array( $queried_course_id, $instructor_course_ids ) ) {// phpcs:ignore
				return true;
			}
		}
		$user_enrolled_course_ids = learndash_user_get_enrolled_courses( $user->ID, array(), true );
		// Check for Subscriber.
		if ( in_array( $queried_course_id, $user_enrolled_course_ids ) ) {// phpcs:ignore
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'qre_check_if_user_accessible' ) ) {
	/**
	 * This function is used to check whether the requested user info is accessible by the user.
	 *
	 * @param  integer $queried_obj_id       User ID.
	 * @param  WP_User $user                 User requesting resource.
	 * @param  array   $user_managed_courses User's Managed Courses(for Group Leaders).
	 * @return boolean/wp_error
	 */
	function qre_check_if_user_accessible( $queried_obj_id, $user, $user_managed_courses ) {
		$user_id = \Quiz_Export_Db::instance()->check_if_user_ids_actually_present( $queried_obj_id );
		if ( empty( $user_id ) ) {
			return new \WP_Error( 400, __( 'Invalid Request. User ID doesn\'t exist.', 'learndash-reports-pro' ) );
		}
		// Check for Administrator.
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		// Check for self Data.
		if ( (int) $queried_obj_id === $user->ID ) {
			return true;
		}
		// Check for Group Leader.
		if ( ! empty( $user_managed_courses ) ) {
			$user_managed_groups = learndash_get_administrators_group_ids( $user->ID );
			if ( ! empty( $user_managed_groups ) ) {
				foreach ( $user_managed_groups as $group_id ) {
					if ( in_array( $queried_obj_id, learndash_get_groups_user_ids( $group_id ) ) ) { // phpcs:ignore
						return true;
					}
				}
			}
		}
		// Check for Instructor.
		if ( function_exists( 'ir_get_instructor_complete_course_list' ) && in_array( 'wdm_instructor', (array) $user->roles, true ) ) {
			$instructor_course_ids    = ir_get_instructor_complete_course_list( $user->ID );
			$user_enrolled_course_ids = learndash_user_get_enrolled_courses( $queried_obj_id, array(), true );
			if ( ! empty( array_intersect( $instructor_course_ids, $user_enrolled_course_ids ) ) ) {
				return true;
			}
		}
		return false;
	}
}

if ( ! function_exists( 'qre_get_statistic_data' ) ) {
	function qre_get_statistic_data( $statistic ) {
		$data = array();
		if ( ! array_key_exists( 'statistic_ref_id', $statistic ) ) {
			return;
		}
		$data                  = Quiz_Export_Db::instance()->get_statistic_summarized_data( $statistic['statistic_ref_id'] );
		$quiz_post_id          = learndash_get_quiz_id_by_pro_quiz_id( $statistic['quiz_id'] );
		$data['quiz_category'] = '-';
		if ( taxonomy_exists( 'ld_quiz_category' ) ) {
			$data['quiz_category'] = wp_get_post_terms( $quiz_post_id, 'ld_quiz_category', array( 'fields' => 'names' ) );
			if ( ! is_wp_error( $data['quiz_category'] ) ) {
				$data['quiz_category'] = implode( ', ', $data['quiz_category'] );
			}
		}
		$percentage         = (int) $data['points'] / (int) $data['gpoints'] * 100;
		$quiz_post_settings = learndash_get_setting( $quiz_post_id );
		if ( ! is_array( $quiz_post_settings ) ) {
			$quiz_post_settings = array();
		}
		if ( ! isset( $quiz_post_settings['passingpercentage'] ) ) {
			$quiz_post_settings['passingpercentage'] = 0;
		}

		$data['percentage']   = $percentage;
		$passingpercentage    = floatval( number_format( $quiz_post_settings['passingpercentage'], 2, '.', '' ) );
		$percentage           = floatval( number_format( $percentage, 2, '.', '' ) );
		$data['pass_status']  = ( $percentage >= $passingpercentage ) ? __( 'Pass', 'learndash-reports-pro' ) : __( 'Fail', 'learndash-reports-pro' );
		$data['quiz_title']   = "<a href='" . add_query_arg(
			array(
				'report'    => 'quiz',
				'screen'    => 'quiz',
				'user'      => $statistic['user_id'],
				'quiz'      => $statistic['quiz_id'],
				'statistic' => $statistic['statistic_ref_id'],
			),
			''
		) . "' target='_blank'>" . get_the_title( $quiz_post_id ) . '</a>';
		$data['user_name']    = "<a href='" . add_query_arg(
			array(
				'report'         => 'quiz',
				'screen'         => 'user',
				'user'           => $statistic['user_id'],
				'ld_report_type' => 'quiz-reports',
			),
			''
		) . "' target='_blank'>" . get_userdata( $statistic['user_id'] )->display_name . '</a>';
		$data['date_attempt'] = date_i18n( get_option( 'date_format', 'd-M-Y' ), $statistic['create_time'] );
		/* translators: %1$s: Points Earned, %2$d: Total Points */
		$data['score']          = sprintf( __( '%1$s out of %2$d', 'learndash-reports-pro' ), '<strong>' . $data['points'] . '</strong>', $data['gpoints'] );
		$dt_current             = new \DateTime( '@0' );
		$dt_after_seconds       = new \DateTime( '@' . (int) $data['question_time'] );
		$data['time_taken']     = $dt_current->diff( $dt_after_seconds )->format( '%H:%I:%S' );
		$data['course_post_id'] = $statistic['course_post_id'];
		$data['create_time']    = $statistic['create_time'];
		$data['quiz_time']      = $data['question_time'];
		return $data;
	}
}

if ( ! function_exists( 'check_if_user_is_course_admin' ) ) {
	function check_if_user_is_course_admin( $course_id, $user_id = 0 ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		$user = get_userdata( $user_id );
		if ( in_array( 'group_leader', (array) $user->roles, true ) ) {
			$course_ids = qre_get_user_managed_group_courses();
			if ( ! in_array( $course_id, $course_ids ) ) {
				return false;
			}
			return true;
		}
		if ( function_exists( 'ir_get_instructor_complete_course_list' ) && wdm_is_instructor( $user_id ) ) {
			$course_ids = ir_get_instructor_complete_course_list( $user_id );
			if ( ! in_array( $course_id, $course_ids ) ) {
				return false;
			}
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'qre_custom_reports_pagination_data' ) ) {
	function qre_custom_reports_pagination_data( $courses, $selected_users_for_groups = null ) {
		$courses_row_count = array(); // Number of table rows for each course.
		$rows              = 0;       // Total number of table rows.
		$course_base_row   = array(); // Row number where the course title gets displayed.
		foreach ( $courses as $key => $course ) {
			$courses_row_count[ $course['post']->ID ] = 2; // Course Name + Label rows.
			$course_base_row[ $course['post']->ID ]   = 1 + $rows;
			$rows                                    += 2;
			if ( empty( $course['quiz'] ) ) {
				$courses_row_count[ $course['post']->ID ]++;
				$rows++;
				continue;
			}
			$quiz_pro_ids = array_map(
				function( $quiz ) {
					return get_post_meta( $quiz, 'quiz_pro_id', true );
				},
				$course['quiz']
			);
			$statArgs     = array();
			if ( check_if_user_is_course_admin( $course['post']->ID ) ) {
				$statArgs = array( 'quiz_ids' => $quiz_pro_ids );
				if ( null != $selected_users_for_groups ) {
					$statArgs['user_ids'] = $selected_users_for_groups;
				}
			} else {
				$statArgs = array(
					'quiz_ids' => $quiz_pro_ids,
					'user_ids' => array( get_current_user_id() ),
				);
			}

			$statistics_count = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_count( $statArgs );

			if ( $statistics_count == 0 ) {
				$statistics_count = 1;
			}
			$extra_rows = 0;
			if ( $statistics_count > 10 ) {
				$extra_rows = 2 * (int) ( $statistics_count / 10 );
			}

			$courses_row_count[ $course['post']->ID ] = $courses_row_count[ $course['post']->ID ] + $statistics_count + $extra_rows;
			$rows                                     = $rows + $statistics_count + $extra_rows;
		}
		$page_index                          = array();
		$page_number                         = 1;
		$page_index[ $page_number ]['start'] = 1;
		$page_index[ $page_number ]['count'] = 0;
		foreach ( $courses_row_count as $course_id => $count ) {
			if ( ! isset( $page_index[ $page_number ] ) ) {
				$page_index[ $page_number ] = array(
					'count' => 0,
					'start' => 0,
				);
			}
			$page_index[ $page_number ]['count'] += $count;
			if ( $page_number > 1 ) {
				$page_index[ $page_number ]['start'] = $page_index[ $page_number - 1 ]['count'] + $page_index[ $page_number - 1 ]['start'];
			}

			if ( $count > 10 ) {
				$remainder                            = ( $count - 2 ) % 10;
				$multiple                             = (int) ( ( $count - 2 ) / 10 );
				$extra_rows                           = ( 10 * ( $multiple - 1 ) ) + $remainder;
				$page_index[ $page_number ]['count'] -= $extra_rows;
				for ( $i = 1; $i <= $multiple; $i++ ) {
					$page_number++;
					$page_index[ $page_number ]['start']  = $page_index[ $page_number - 1 ]['start'] + $page_index[ $page_number - 1 ]['count'];
					$page_index[ $page_number ]['count']  = $extra_rows;
					$extra_rows                           = ( 10 * ( $multiple - $i - 1 ) ) + $remainder;
					$page_index[ $page_number ]['count'] -= $extra_rows;
				}
				continue;
			}
			if ( $page_index[ $page_number ]['count'] >= 10 ) {
				$page_number++;
			}
		}
		// echo "<pre>";
		// var_dump($page_index);
		// var_dump(array( 'total_rows' => $rows, 'course_rows' => $courses_row_count, 'course_row_starts' => $course_base_row ));
		// die();
		return array(
			'total_rows'        => $rows,
			'course_rows'       => $courses_row_count,
			'course_row_starts' => $course_base_row,
			'page_index'        => $page_index,
		);
	}
}

