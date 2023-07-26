<?php
/**
 * Accredible LearnDash Add-on main class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-event-handler.php';

if ( ! class_exists( 'Accredible_Learndash' ) ) :
	/**
	 * Accredible LearnDash Add-on main class.
	 */
	final class Accredible_Learndash {
		/**
		 * Initialize plugin hooks.
		 */
		public static function init() {
			return new self();
		}

		/**
		 * Accredible_Learndash constructor.
		 */
		public function __construct() {
			// Check if the LearnDash plugin has been installed.
			if ( class_exists( 'SFWD_LMS' ) ) {
				$this->set_learndash_hooks();
			}
		}

		/**
		 * Initialize LearnDash Action Hooks.
		 */
		private function set_learndash_hooks() {
			$priority = 20;

			add_action(
				'learndash_course_completed',
				array( 'Accredible_Learndash_Event_Handler', 'handle_course_completed' ),
				$priority
			);

			add_action(
				'learndash_lesson_completed',
				array( 'Accredible_Learndash_Event_Handler', 'handle_lesson_completed' ),
				$priority
			);
		}
	}
endif;
