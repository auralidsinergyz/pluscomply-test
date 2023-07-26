<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class LearndashCourseNavigation
 * @package uncanny_pro_toolkit
 */
class LearndashCourseNavigation extends toolkit\Config implements toolkit\RequiredFunctions {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {

			add_shortcode( 'uo-course-navigation', [ __CLASS__, 'uo_load_course_navigation' ] );
			add_action( 'rest_api_init', [ __CLASS__, 'course_navigation_api' ] );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title       = esc_html__( 'Lazy Loading Course Navigation', 'uncanny-pro-toolkit' );
		$kb_link           = 'https://www.uncannyowl.com/knowledge-base/lazy-loading-course-navigation/';
		$class_description = esc_html__( 'Shortcode that loads a course navigation tree via AJAX.  Useful on sites with many lessons and topics where the native LearnDash Course Navigation widget is extending page load time.', 'uncanny-pro-toolkit' );
		$class_icon        = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-book "></i><span class="uo_pro_text">PRO</span>';
		$category          = 'learndash';
		$type              = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link,
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => self::get_class_settings( $class_title ),
			'icon'             => $class_icon,
		);

	}

	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * @return boolean || string Return either true or name of function or plugin
	 *
	 */
	public static function dependants_exist() {
		// Return true if no depency or dependency is available
		return true;
	}

	/**
	 * HTML for modal to create settings
	 *
	 * @param String
	 * @param bool
	 *
	 * @return array Return either false or settings html modal
	 *
	 */
	public static function get_class_settings( $class_title, $only_options = false ) {

		// Create options
		$options = array(
			array(
				'type'       => 'html',
				'inner_html' => '<h2>' . __( 'Navigation Settings', 'uncanny-pro-toolkit' ) . '</h2>',
			),
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Widget Heading', 'uncanny-pro-toolkit' ),
				'placeholder' => esc_html__( 'Course Navigation.', 'uncanny-pro-toolkit' ),
				'option_name' => 'uo_course_navigation_heading',
			)
		);

		if ( $only_options ) {
			return $options;
		}

		// Build html
		$html = self::settings_output( array(
			'class'   => __CLASS__,
			'title'   => $class_title,
			'options' => $options,
		) );

		return $html;
	}

	public static function uo_load_course_navigation( $atts, $content = null ) {

		global $post;

		if ( empty( $post->ID ) || ! is_single() ) {
			return;
		}

		$course_id = learndash_get_course_id( $post->ID );
		if ( empty( $course_id ) ) {
			return;
		}

		// Output content
		ob_start();

		?>

		<div class="ultp-lazy-course-navigation ultp-lazy-course-navigation--loading">
			<div class="ultp-lazy-course-navigation-loading">
				<div class="ultp-lazy-course-navigation-loading__icon"></div>
				<div class="ultp-lazy-course-navigation-loading__text">
					<?php _e( 'Loading...', 'uncanny-pro-toolkit' ); ?>
				</div>
			</div>
		</div>

		<?php

		// Get output
		$output = ob_get_clean();

		// Return output
		return $output;
	}

	public static function load_scripts() {
		global $post;

		if ( empty( $post->ID ) || ! is_single() ) {
			return;
		}

		$course_id = learndash_get_course_id( $post->ID );
		if ( empty( $course_id ) ) {
			return;
		}

		$lesson_id = 0;
		$topic_id  = 0;
		$quiz_id   = 0;

		if ( 'sfwd-quiz' === $post->post_type ) {
			$quiz_id   = $post->ID;
			$lesson_id = learndash_get_lesson_id( $post->ID );

			if ( \LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Courses_Builder', 'shared_steps' ) == 'yes' ) {
				$topic_id = learndash_course_get_single_parent_step( $course_id, $post->ID );
			} else {
				$topic_id = learndash_get_setting( $post, 'topic' );
			}

			if ( $topic_id === $lesson_id ) {
				$topic_id = 0;
			}
		} elseif ( 'sfwd-topic' === $post->post_type ) {
			$topic_id  = $post->ID;
			$lesson_id = learndash_get_lesson_id( $post->ID );
		} elseif ( 'sfwd-lessons' === $post->post_type ) {
			$lesson_id = $post->ID;
		} elseif ( 'sfwd-courses' === $post->post_type ) {
			// Everything is set up if its a course
		}

		$uo_course_navigation_trigger = self::get_settings_value( 'uo_course_navigation_trigger', __CLASS__, '' );

		// API data
		$rest_api_setup = [
			'course_id'                    => $course_id,
			'lesson_id'                    => $lesson_id,
			'topic_id'                     => $topic_id,
			'quiz_id'                      => $quiz_id,
			'uo_course_navigation_trigger' => $uo_course_navigation_trigger,
			'nonce'                        => wp_create_nonce( 'uo_course_navigation_nonce' ),
		];

		wp_localize_script( 'ultp-frontend', 'UncannyToolkitProLazyCourseNavigation', $rest_api_setup );
	}

	public static function course_navigation_api() {
		register_rest_route( 'uo_toolkit/v1', '/course_navigation/', [
			'methods'  => 'POST',
			'callback' => [ __CLASS__, 'course_navigation_content' ],
		] );
	}

	public static function course_navigation_content() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'uo_course_navigation_nonce' ) ) {
			return [];
		}

		if ( ! isset( $_POST['course_id'] ) ) {
			return [];
		}

		$course_id = learndash_get_course_id( absint( $_POST['course_id'] ) );
		if ( empty( $course_id ) ) {
			return [];
		}

		$uo_course_navigation_heading = self::get_settings_value( 'uo_course_navigation_heading', __CLASS__, '' );

		$instance['title'] = $uo_course_navigation_heading;

		$instance['show_lesson_quizzes'] = false;
		$instance['show_topic_quizzes']  = false;
		$instance['show_course_quizzes'] = false;


		$instance['show_widget_wrapper'] = true;
		$instance['current_lesson_id']   = 0;
		$instance['current_step_id']     = 0;

		if ( $_POST['topic_id'] ) {
			$instance['current_step_id'] = absint( $_POST['topic_id'] );
		} elseif ( $_POST['lesson_id'] ) {
			$instance['current_step_id'] = absint( $_POST['lesson_id'] );
		} else {
			$instance['current_step_id'] = absint( $course_id );
		}

		global $post;

		$post = get_post( $instance['current_step_id'] );

		$lesson_query_args       = array();
		$course_lessons_per_page = learndash_get_course_lessons_per_page( $course_id );
		if ( $course_lessons_per_page > 0 ) {
			if ( ( is_a( $post, 'WP_Post' ) ) &&
				 ( is_user_logged_in() ) &&
				 in_array( $post->post_type, array( 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz', ) )
			) {

				if ( $post->post_type == 'sfwd-lessons' ) {
					$instance['current_lesson_id'] = $post->ID;
				} else if ( in_array( $post->post_type, array( 'sfwd-topic', 'sfwd-quiz' ) ) ) {
					$instance['current_lesson_id'] = learndash_course_get_single_parent_step( $course_id, $post->ID, 'sfwd-lessons' );
				}

				if ( ! empty( $instance['current_lesson_id'] ) ) {
					$course_lesson_ids = learndash_course_get_steps_by_type( $course_id, 'sfwd-lessons' );
					if ( ! empty( $course_lesson_ids ) ) {
						$course_lessons_paged = array_chunk( $course_lesson_ids, $course_lessons_per_page, true );
						$lessons_paged        = 0;
						foreach ( $course_lessons_paged as $paged => $paged_set ) {
							if ( in_array( $instance['current_lesson_id'], $paged_set ) ) {
								$lessons_paged = $paged + 1;
								break;
							}
						}

						if ( ! empty( $lessons_paged ) ) {
							$lesson_query_args['pagination'] = 'true';
							$lesson_query_args['paged']      = $lessons_paged;
						}
					}
				} else if ( in_array( $post->post_type, array( 'sfwd-quiz' ) ) ) {
					// If here we have a global Quiz. So we set the pager to the max number
					$course_lesson_ids = learndash_course_get_steps_by_type( $course_id, 'sfwd-lessons' );
					if ( ! empty( $course_lesson_ids ) ) {
						$course_lessons_paged       = array_chunk( $course_lesson_ids, $course_lessons_per_page, true );
						$lesson_query_args['paged'] = count( $course_lessons_paged );
					}
				}
			}
		} else {
			if ( ( is_a( $post, 'WP_Post' ) ) && ( is_user_logged_in() ) && ( in_array( $post->post_type, array(
					'sfwd-lessons',
					'sfwd-topic',
					'sfwd-quiz'
				) ) ) ) {

				$instance['current_step_id'] = $post->ID;
				if ( $post->post_type == 'sfwd-lessons' ) {
					$instance['current_lesson_id'] = $post->ID;
				} else if ( in_array( $post->post_type, array( 'sfwd-topic', 'sfwd-quiz' ) ) ) {
					$instance['current_lesson_id'] = learndash_course_get_single_parent_step( $course_id, $post->ID, 'sfwd-lessons' );
				}
			}
		}

		// Output content
		ob_start();

		if ( ! empty( $uo_course_navigation_heading ) ) {
			?>

			<div class="ultp-lazy-course-navigation__heading">
				<?php echo $uo_course_navigation_heading; ?>
			</div>

			<?php
		}

		// Always show quizzes at every level
		$instance['show_course_quizzes'] = true;
		$instance['show_lesson_quizzes'] = true;
		$instance['show_topic_quizzes'] = true;

		?>

		<div class="ultp-lazy-course-navigation__content">
			<?php learndash_course_navigation( $course_id, $instance, $lesson_query_args ); ?>
		</div>

		<?php

		// Get output
		$content = ob_get_clean();

		return [ 'html' => $content ];

	}
}
