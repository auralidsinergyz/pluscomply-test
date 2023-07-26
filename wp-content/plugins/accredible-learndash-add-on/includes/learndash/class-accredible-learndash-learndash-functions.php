<?php
/**
 * Accredible LearnDash Add-on LearnDash functions class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Learndash_Functions' ) ) :
	/**
	 * Accredible LearnDash Add-on LearnDash functions class.
	 */
	class Accredible_Learndash_Learndash_Functions {
		/**
		 * Gets the lesson list output for a course.
		 *
		 * @param WP_Post|int|null $course_id The course ID or WP_Post object.
		 * @param int|null         $user_id User ID.
		 * @param Array            $query_args An array of query arguments to get lesson list.
		 */
		public function learndash_get_course_lessons_list( $course_id = null, $user_id = null, $query_args = array() ) {
			if ( function_exists( 'learndash_get_course_lessons_list' ) ) {
				return learndash_get_course_lessons_list( $course_id, $user_id, $query_args );
			}
		}

		/**
		 * Gets the course ID for a resource.
		 *
		 * @param WP_Post|int|null $id ID of the resource.
		 * @param boolean          $bypass_cb If true will bypass course_builder logic.
		 */
		public function learndash_get_course_id( $id = null, $bypass_cb = false ) {
			if ( function_exists( 'learndash_get_course_id' ) ) {
				return learndash_get_course_id( $id, $bypass_cb );
			}
		}
	}
endif;
