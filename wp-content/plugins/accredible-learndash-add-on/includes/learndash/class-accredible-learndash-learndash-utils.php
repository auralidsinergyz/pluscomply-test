<?php
/**
 * Accredible LearnDash Add-on utils class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-learndash-functions.php';

if ( ! class_exists( 'Accredible_Learndash_Learndash_Utils' ) ) :
	/**
	 * Accredible LearnDash Add-on utils class.
	 */
	final class Accredible_Learndash_Learndash_Utils {
		/**
		 * Accredible_Learndash_Learndash_Utils constructor.
		 *
		 * @param Accredible_Learndash_Learndash_Functions $functions A mock instance to be passed when unit testing.
		 */
		public function __construct( $functions = null ) {
			if ( null !== $functions ) {
				$this->functions = $functions;
			} else {
				$this->functions = new Accredible_Learndash_Learndash_Functions();
			}
		}

		/**
		 * Get available courses.
		 *
		 * @return array
		 */
		public static function get_course_options() {
			$args    = array(
				'post_type'   => 'sfwd-courses',
				'numberposts' => -1, // returns all posts, removing the default limit of 5.
			);
			$courses = array();
			$posts   = get_posts( $args );

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $value ) {
					$course_id             = get_post_field( 'ID', $value );
					$course_name           = get_the_title( $value );
					$courses[ $course_id ] = $course_name;
				}
			}

			return $courses;
		}

		/**
		 * Get a list of lessons that belong to a course.
		 *
		 * @param int $course_id LearnDash course ID.
		 *
		 * @return array
		 */
		public function get_lesson_options( $course_id ) {
			$options = array();
			$lessons = $this->functions->learndash_get_course_lessons_list( $course_id );
			if ( ! empty( $lessons ) ) {
				foreach ( $lessons as $lesson ) {
					$options[ $lesson['id'] ] = $lesson['post']->post_title;
				}
			}
			return $options;
		}

		/**
		 * Find a parent course with a LearnDash child post ID (Lesson, Topic, Quiz, etc).
		 *
		 * @param int $child_post_id Should be the ID of anything that belongs to a course.
		 *
		 * @return WP_POST
		 */
		public function get_parent_course( $child_post_id ) {
			$course_id = $this->functions->learndash_get_course_id( $child_post_id );
			$course    = get_post( $course_id );
			if ( $course && 'sfwd-courses' === $course->post_type ) {
				return $course;
			}
		}
	}
endif;
