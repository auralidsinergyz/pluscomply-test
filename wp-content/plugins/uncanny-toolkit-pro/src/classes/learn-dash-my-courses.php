<?php

namespace uncanny_pro_toolkit;

use LD_QuizPro;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class learnDashMyCourses
 * @package uncanny_pro_toolkit
 */
class learnDashMyCourses extends toolkit\Config implements toolkit\RequiredFunctions {
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

			/* ADD FILTERS ACTIONS FUNCTION */
			add_shortcode( 'uo_dashboard', array( __CLASS__, 'uo_course_dashboard' ) );
			add_filter( 'uo-dashboard-template', array( __CLASS__, 'uo_dashboard_get_template' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_dashboard_style' ) );
		}

	}

	public static function add_dashboard_style() {

		global $post;

		$block_is_on_page = false;
		if ( is_a( $post, 'WP_Post' ) && function_exists( 'parse_blocks' ) ) {
			$blocks = parse_blocks( $post->post_content );
			foreach ( $blocks as $block ) {
				if ( 'uncanny-toolkit-pro/learn-dash-my-courses' === $block['blockName'] ) {
					$block_is_on_page = true;
				}
			}
		}

		if ( ! empty( $post->ID ) &&
			 (
				 has_shortcode( $post->post_content, 'uo_dashboard' ) ||
				 $block_is_on_page
			 )
		) {
			// Get current dashboard template
			$dashboard_template = self::get_settings_value( 'uo_dashboard_template', __CLASS__ );

			$course_theme = get_option( 'learndash_settings_courses_themes' );

			if ( ! empty( $course_theme ) && isset( $course_theme['active_theme'] ) && 'ld30' === $course_theme['active_theme'] ) {
				return;
			}

			// Check if it's using the legacy template
			if ( ( '' === $dashboard_template || 'legacy' === $dashboard_template ) ) {
				// Enqueue dashboard assets
				wp_enqueue_style( 'uo_dashboard', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/legacy/frontend/css/uo_dashboard.css', array(), UNCANNY_TOOLKIT_PRO_VERSION, 'all' );
			}
		}
	}


	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Course Dashboard', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/learndash-course-dashboard/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Use the [uo_dashboard] shortcode to display a list of courses for the current user.  Users can filter courses by category, or expand courses to view lessons and topics.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-book "></i><span class="uo_pro_text">PRO</span>';

		$category = 'learndash';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
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

		/* Checks for LearnDash */
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		// Return true if no dependency or dependency is available
		return true;

	}

	/**
	 * HTML for modal to create settings
	 *
	 * @param String
	 *
	 * @return array || string Return either false or settings html modal
	 *
	 */
	public static function get_class_settings( $class_title ) {

		// Create options
		$options = array(
			array(
				'type'       => 'radio',
				'label'      => __( 'Choose a Dashboard Theme', 'uncanny-pro-toolkit' ),
				'radio_name' => 'uo_dashboard_template',
				'radios'     => array(
					array(
						'value' => 'legacy',
						'text'  => __( 'Legacy', 'uncanny-pro-toolkit' ),
					),
					array(
						'value' => '3_0',
						'text'  => __( '3.0', 'uncanny-pro-toolkit' ),
					),
				),
			),
			array(
				'type'       => 'html',
				'inner_html' => '<h2>' . __( 'Colors (3.0 theme only)', 'uncanny-pro-toolkit' ) . '</h2>',
			),
			array(
				'type'        => 'text',
				'label'       => esc_html__( "Toggle button background (expanded)", 'uncanny-pro-toolkit' ),
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_dashboard_color_toggle_expanded_background',
				'description' => esc_html__( "Color of the background of the toggle button when expanded.", 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'text',
				'label'       => esc_html__( "Toggle button icon color (expanded)", 'uncanny-pro-toolkit' ),
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_dashboard_color_toggle_expanded_icon',
				'description' => esc_html__( "Color of the icon of the toggle button when expanded.", 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'text',
				'label'       => esc_html__( "Toggle button background (disabled)", 'uncanny-pro-toolkit' ),
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_dashboard_color_toggle_disabled_background',
				'description' => esc_html__( "Color of the background of the toggle button when not expandable.", 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'text',
				'label'       => esc_html__( "Progress color", 'uncanny-pro-toolkit' ),
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_dashboard_color_progress',
				'description' => esc_html__( "Color used to represent progress. Used in the progress bar and the completed icon.", 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Third level background', 'uncanny-pro-toolkit' ),
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_dashboard_color_third_level',
				'description' => esc_html__( "Color of the background of quiz results and topics.", 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Quiz score background (passed)', 'uncanny-pro-toolkit' ),
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_dashboard_color_quiz_passed_bg',
				'description' => esc_html__( "Color of the quiz score when the quiz was passed", 'uncanny-pro-toolkit' ),
			),
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Quiz score background (failed)', 'uncanny-pro-toolkit' ),
				'class'       => 'uo-color-picker',
				'option_name' => 'uo_dashboard_color_quiz_failed_bg',
				'description' => esc_html__( "Color of the quiz score when the quiz was failed.", 'uncanny-pro-toolkit' ),
			),
		);

		// Build html
		$html = self::settings_output(
			array(
				'class'   => __CLASS__,
				'title'   => $class_title,
				'options' => $options,
			) );

		return $html;
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function uo_course_dashboard( $atts ) {


		// Set theme as 3.0 for LD course theme 3.0+
		$course_theme = get_option( 'learndash_settings_courses_themes' );

		if ( ! empty( $course_theme ) && isset( $course_theme['active_theme'] ) && 'ld30' === $course_theme['active_theme'] ) {
			return self::template_3_0( $atts );
		}

		$template = self::get_settings_value( 'uo_dashboard_template', __CLASS__ );

		// Set Default template theme
		if ( empty( $template ) ) {
			return self::legacy_template( $atts );
		}

		if ( 'legacy' === $template ) {
			return self::legacy_template( $atts );
		}

		if ( '3_0' === $template ) {
			return self::template_3_0( $atts );
		}

		return __( 'Course dashboard template in settings not defined.', 'uncanny-pro-toolkit' );

	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function template_3_0( $atts ) {

		if ( is_string( $atts ) ) {
			$atts = [];
		}

		// Get user colors
		$user_colors = (object) [
			'toggle'         => (object) [
				'expanded_bg'   => self::get_settings_value( 'uo_dashboard_color_toggle_expanded_background', __CLASS__ ),
				'expanded_icon' => self::get_settings_value( 'uo_dashboard_color_toggle_expanded_icon', __CLASS__ ),
				'disabled_bg'   => self::get_settings_value( 'uo_dashboard_color_toggle_disabled_background', __CLASS__ ),
			],
			'quiz'           => (object) [
				'passed_bg' => self::get_settings_value( 'uo_dashboard_color_quiz_passed_bg', __CLASS__ ),
				'failed_bg' => self::get_settings_value( 'uo_dashboard_color_quiz_failed_bg', __CLASS__ )
			],
			'progress'       => self::get_settings_value( 'uo_dashboard_color_progress', __CLASS__ ),
			'third_level_bg' => self::get_settings_value( 'uo_dashboard_color_third_level', __CLASS__ )
		];

		// Check if it has custom colors
		$has_custom_colors = ! empty( $user_colors->toggle->expanded_bg ) || ! empty( $user_colors->toggle->expanded_icon ) || ! empty( $user_colors->toggle->disabled_bg ) || ! empty( $user_colors->quiz->passed_bg ) || ! empty( $user_colors->quiz->failed_bg ) || ! empty( $user_colors->progress ) || ! empty( $user_colors->third_level_bg );

		$user_id = self::set_user_id( $atts );

		$tax_query     = array();
		$ld_categories = array();
		$categories    = array();

		if ( ! isset( $atts['ld_category'] ) || '' === $atts['ld_category'] ) {
			$atts['ld_category'] = 'all';
		}
		if ( 'all' !== $atts['ld_category'] ) {
			$tax_query = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'slug',
					'terms'    => array( sanitize_text_field( $atts['ld_category'] ) ),
				),
			);
		}

		if ( ! isset( $atts['category'] ) || '' === $atts['category'] ) {
			$atts['category'] = 'all';
		}
		if ( 'all' !== $atts['category'] ) {
			$tax_query[] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( sanitize_text_field( $atts['category'] ) ),
			);
		}

		if ( isset( $atts['categoryselector'] ) && 'hide' !== $atts['categoryselector'] ) {
			$get_categories_args = array(
				'taxonomy'   => 'category',
				'type'       => 'sfwd-courses',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);

			$categories = get_categories( $get_categories_args );

			if ( ( isset( $_GET['catid'] ) ) && ( ! empty( $_GET['catid'] ) ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => intval( $_GET['catid'] ),
				);
			}
		}
		if ( isset( $atts['course_categoryselector'] ) && 'hide' !== $atts['course_categoryselector'] ) {
			$get_categories_args = array(
				'taxonomy'   => 'ld_course_category',
				'type'       => 'sfwd-courses',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);
			$ld_categories       = get_categories( $get_categories_args );

			if ( ( isset( $_GET['course_catid'] ) ) && ( ! empty( $_GET['course_catid'] ) ) ) {
				$tax_query[] = array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'term_id',
					'terms'    => intval( $_GET['course_catid'] ),
				);
			}
		}

		// Format $categories
		$wp_categories = array_filter( $categories, function ( $category ) {
			// Get number of posts
			$posts = get_posts( 'post_type=sfwd-courses&category=' . $category->term_id );
			$count = count( $posts );

			// Check if it has posts
			return $count > 0;
		} );

		$wp_categories = array_map( function ( $category ) {
			// Check if it's selected
			$selected = false;

			if ( isset( $_GET['catid'] ) || isset( $_GET['course_catid'] ) ) {
				if ( isset( $_GET['catid'] ) && absint( $_GET['catid'] ) ) {
					if ( $category->term_id === absint( $_GET['catid'] ) ) {
						$selected = true;
					}
				}
			} else {
				if ( isset( $atts['category'] ) ) {
					if ( $atts['category'] === $category->slug ) {
						$selected = true;
					}
				}
			}

			return (object) [
				'id'                => $category->term_id,
				'title'             => $category->name,
				'number_of_courses' => $category->category_count,
				'has_courses'       => $category->category_count > 0,
				'is_selected'       => $selected
			];
		}, $wp_categories );

		// Format $ld_categories
		$ld_categories = array_filter( $ld_categories, function ( $category ) {
			// Get number of posts
			$args  = [
				'post_type'      => 'sfwd-courses',
				'post_status'    => 'publish',
				'posts_per_page' => 999,
				'tax_query'      => [
					[
						'taxonomy' => 'ld_course_category',
						'field'    => 'term_id',
						'terms'    => $category->term_id,
					]
				]
			];
			$posts = get_posts( $args );
			$count = count( $posts );

			// Check if it has posts
			return $count > 0;
		} );

		$ld_categories = array_map( function ( $category ) {
			// Check if it's selected
			$selected = false;

			if ( isset( $_GET['course_catid'] ) || isset( $_GET['catid'] ) ) {
				if ( isset( $_GET['course_catid'] ) && absint( $_GET['course_catid'] ) ) {
					if ( $category->term_id === absint( $_GET['course_catid'] ) ) {
						$selected = true;
					}
				}
			} else {
				if ( isset( $atts['ld_category'] ) ) {
					if ( $atts['ld_category'] === $category->slug ) {
						$selected = true;
					}
				}
			}

			return (object) [
				'id'                => $category->term_id,
				'title'             => $category->name,
				'number_of_courses' => $category->category_count,
				'has_courses'       => $category->category_count > 0,
				'is_selected'       => $selected
			];
		}, $ld_categories );

		$has_wp_category_dropdown = ! empty( $wp_categories );
		$has_ld_category_dropdown = ! empty( $ld_categories );

		if ( isset( $atts['orderby'] ) ) {

			// Make a correct order by value isset
			$allowed_order_by = array( 'ID', 'title', 'date', 'menu_order' );
			if ( in_array( $atts['orderby'], $allowed_order_by ) ) {
				$order_by = $atts['orderby'];
			} else {
				return 'The order by value is not of the type title, date, or menu_order.';
			}
		} else {
			$order_by = 'ID';
		}

		if ( isset( $atts['order'] ) ) {

			// Make a correct order value isset
			$allowed_order = array( "asc", "desc" );
			if ( in_array( $atts['order'], $allowed_order ) ) {
				$order = $atts['order'];
			} else {
				return 'The order value is not of the type asc, or desc';
			}
		} else {
			$order = 'desc';
		}

		// Set sorting
		$sort_atts = array(
			'order'   => $order,
			'orderby' => $order_by,
		);

		if ( ! empty( $tax_query ) ) {
			$sort_atts['tax_query'] = $tax_query;
		}

		if ( function_exists( 'ld_get_mycourses' ) ) {

			if ( 0 === $user_id ) {

				if ( isset( $atts['show'] ) ) {

					if ( 'open' === $atts['show'] ) {
						// Get open courses for logged out users
						$user_courses = learndash_get_open_courses();
						// Not filter available for open courses.
						$ld_categories = array();
						$categories    = array();
					} elseif ( 'all' === $atts['show'] ) {
						// Show all courses
						$course_query_args = [
							'post_type'      => 'sfwd-courses',
							'post_status'    => 'publish',
							'posts_per_page' => 999,
							'tax_query'      => []
						];
						if ( ! empty( $tax_query ) ) {
							$course_query_args['tax_query'] = $tax_query;
						}

						if ( ! empty( $sort_atts ) ) {
							$course_query_args['order']   = $sort_atts['order'];
							$course_query_args['orderby'] = $sort_atts['orderby'];
						}

						$courses      = get_posts( $course_query_args );
						$user_courses = wp_list_pluck( $courses, 'ID' );
					} else {
						return '';
					}

				} else {

					return '';

				}


			} else {
				$user_courses = ld_get_mycourses( $user_id, $sort_atts );
			}

		} else {
			return;
		}

		// Get all users attempted and completed quizzes
		$quiz_attempts = self::get_all_quiz_attemps( $user_id );

		$courses = self::set_up_course_object( $user_courses, $user_id, $quiz_attempts );

		if ( isset( $atts['expand_by_default'] ) && 'yes' === $atts['expand_by_default'] ) {
			$expanded_on_load = true;
		} else {
			$expanded_on_load = false;
		}

		//Check to see if the file is in template to override default template.
		$file_path = get_stylesheet_directory() . '/uncanny-toolkit-pro/templates/frontend-dashboard/dashboard-template-3_0.php';

		if ( ! file_exists( $file_path ) ) {
			$file_path = apply_filters( 'uo-dashboard-template-3-0', self::get_template( 'frontend-dashboard/dashboard-template-3_0.php', dirname( dirname( __FILE__ ) ) . '/src' ) );
		}

		$level = ob_get_level();
		ob_start();

		global $learndash_assets_loaded;

		$asset_url = plugins_url( 'src/assets/legacy/frontend/js/learndash_template_script.js', UO_FILE );

		if ( isset( $learndash_assets_loaded['scripts']['learndash_template_script_js'] ) ) {
			wp_dequeue_script( 'learndash_template_script_js' );

			wp_enqueue_script( 'learndash_template_script_js_2', $asset_url, array( 'jquery' ), LEARNDASH_VERSION, true );

			$user_info = get_userdata( $user_id );
			$nicename  = $user_info->user_nicename;

			$data              = array();
			$data['ajaxurl']   = admin_url( 'admin-ajax.php' );
			$data['user_name'] = $nicename;
			$data              = array( 'json' => json_encode( $data ) );
			wp_localize_script( 'learndash_template_script_js_2', 'sfwd_data', $data );
		}

		if ( defined( 'LEARNDASH_LMS_PLUGIN_URL' ) && function_exists( 'learndash_is_active_theme' ) && learndash_is_active_theme( 'ld30' ) ) {
		}

		LD_QuizPro::showModalWindow();

		include( $file_path );

		$contents = learndash_ob_get_clean( $level );

		return $contents;
	}

	/**
	 * @param $atts
	 *
	 * @return string|void
	 */
	public static function legacy_template( $atts ) {

		if ( is_string( $atts ) ) {
			$atts = [];
		}

		$tax_query     = array();
		$ld_categories = array();
		$categories    = array();
		$user_id       = self::set_user_id( $atts );
		$current_user  = get_user_by( 'ID', $user_id );

		if ( ! isset( $atts['ld_category'] ) || '' === $atts['ld_category'] ) {
			$atts['ld_category'] = 'all';
		}
		if ( 'all' !== $atts['ld_category'] ) {
			$tax_query = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'slug',
					'terms'    => array( sanitize_text_field( $atts['ld_category'] ) ),
				),
			);
		}

		if ( ! isset( $atts['category'] ) || '' === $atts['category'] ) {
			$atts['category'] = 'all';
		}
		if ( 'all' !== $atts['category'] ) {
			$tax_query[] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( sanitize_text_field( $atts['category'] ) ),
			);
		}

		if ( isset( $atts['categoryselector'] ) && 'hide' !== $atts['categoryselector'] ) {
			$get_categories_args = array(
				'taxonomy'   => 'category',
				'type'       => 'sfwd-courses',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);
			$categories          = get_categories( $get_categories_args );

			if ( ( isset( $_GET['catid'] ) ) && ( ! empty( $_GET['catid'] ) ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => intval( $_GET['catid'] ),
				);
			}
		}
		if ( isset( $atts['course_categoryselector'] ) && 'hide' !== $atts['course_categoryselector'] ) {
			$get_categories_args = array(
				'taxonomy'   => 'ld_course_category',
				'type'       => 'sfwd-courses',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);
			$ld_categories       = get_categories( $get_categories_args );

			if ( ( isset( $_GET['course_catid'] ) ) && ( ! empty( $_GET['course_catid'] ) ) ) {
				$tax_query[] = array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'term_id',
					'terms'    => intval( $_GET['course_catid'] ),
				);
			}
		}

		if ( isset( $atts['orderby'] ) ) {

			// Make a correct order by value isset
			$allowed_order_by = array( 'ID', 'title', 'date', 'menu_order' );
			if ( in_array( $atts['orderby'], $allowed_order_by ) ) {
				$order_by = $atts['orderby'];
			} else {
				return 'The order by value is not of the type title, date, or menu_order.';
			}
		} else {
			$order_by = 'ID';
		}

		if ( isset( $atts['order'] ) ) {

			// Make a correct order value isset
			$allowed_order = array( "asc", "desc" );
			if ( in_array( $atts['order'], $allowed_order ) ) {
				$order = $atts['order'];
			} else {
				return 'The order value is not of the type asc, or desc';
			}
		} else {
			$order = 'desc';
		}


		if ( empty( $current_user ) ) {
			$current_user = get_user_by( 'id', $user_id );
		}

		// Set sorting
		$sort_atts = array(
			'order'   => $order,
			'orderby' => $order_by,
		);
		if ( ! empty( $tax_query ) ) {
			$sort_atts['tax_query'] = $tax_query;
		}

		if ( function_exists( 'ld_get_mycourses' ) ) {

			if ( 0 === $user_id ) {

				if ( isset( $atts['show'] ) ) {

					if ( 'open' === $atts['show'] ) {
						// Get open courses for logged out users
						$user_courses = learndash_get_open_courses();
						// Not filter available for open courses.
						$ld_categories = array();
						$categories    = array();
					} elseif ( 'all' === $atts['show'] ) {
						// Show all courses
						$course_query_args = array(
							'post_type'   => 'sfwd-courses',
							'post_status' => 'publish'
						);

						if ( ! empty( $tax_query ) ) {
							$course_query_args['tax_query'] = $tax_query;
						}

						if ( ! empty( $sort_atts ) ) {
							$course_query_args['order']   = $sort_atts['order'];
							$course_query_args['orderby'] = $sort_atts['orderby'];
						}


						$courses      = get_posts( $course_query_args );
						$user_courses = wp_list_pluck( $courses, 'ID' );
					} else {
						return '';
					}

				} else {

					return '';

				}


			} else {
				$user_courses = ld_get_mycourses( $user_id, $sort_atts );
			}

		} else {
			return;
		}

		$usermeta           = get_user_meta( $user_id, '_sfwd-quizzes', true );
		$quiz_attempts_meta = empty( $usermeta ) ? false : $usermeta;
		$quiz_attempts      = array();

		if ( function_exists( 'learndash_certificate_details' ) ) {
			if ( ! empty( $quiz_attempts_meta ) ) {
				foreach ( $quiz_attempts_meta as $quiz_attempt ) {
					$c                          = learndash_certificate_details( $quiz_attempt['quiz'], $user_id );
					$quiz_attempt['post']       = get_post( $quiz_attempt['quiz'] );
					$quiz_attempt["percentage"] = ! empty( $quiz_attempt["percentage"] ) ? $quiz_attempt["percentage"] : ( ! empty( $quiz_attempt["count"] ) ? $quiz_attempt["score"] * 100 / $quiz_attempt["count"] : 0 );

					if ( $user_id == get_current_user_id() && ! empty( $c["certificateLink"] ) && ( ( isset( $quiz_attempt['percentage'] ) && $quiz_attempt['percentage'] >= $c["certificate_threshold"] * 100 ) ) ) {
						$quiz_attempt['certificate'] = $c;
					}
					$quiz_attempts[ learndash_get_course_id( $quiz_attempt['quiz'] ) ][] = $quiz_attempt;
				}
			}
		}
		$args = array(
			'user_id'       => $user_id,
			'quiz_attempts' => $quiz_attempts,
			'current_user'  => $current_user,
			'user_courses'  => $user_courses,
			'categories'    => $categories,
			'ld_categories' => $ld_categories,
			'settings'      => $atts,
		);

		//Check to see if the file is in template to override default template.
		$file_path = get_stylesheet_directory() . '/uncanny-toolkit-pro/templates/frontend-dashboard/dashboard-template.php';

		if ( ! file_exists( $file_path ) ) {
			$file_path = apply_filters( 'uo-dashboard-template', 'uo_dashboard_get_template' );
		}

		extract( $args );
		$level = ob_get_level();
		ob_start();
		include( $file_path );

		$contents = learndash_ob_get_clean( $level );
		/**
		 * @since 2.4.2
		 */
		if ( isset( $atts['expand_by_default'] ) && 'yes' === $atts['expand_by_default'] ) {
			$contents = '<script>(function($){$(document).ready(function(){flip_expand_all("#course_list");});})(jQuery);</script>' . $contents;
		}

		return $contents;

	}


	/**
	 * Set the user id
	 *
	 * @param $atts
	 *
	 * @return int
	 */
	public static function set_user_id( $atts ) {

		if ( isset( $atts['user_id'] ) && '' !== $atts['user_id'] ) {
			$user_id = absint( $atts['user_id'] );
		} else {
			$current_user = wp_get_current_user();

			if ( empty( $current_user->ID ) ) {
				$user_id = 0;
			} else {
				$user_id = $current_user->ID;
			}
		}

		return $user_id;
	}

	/**
	 * Get a list on all quiz quiz attempts for each module
	 *
	 * @param $user_id
	 *
	 * @return array
	 */
	public static function get_all_quiz_attemps( $user_id ) {

		$usermeta           = get_user_meta( $user_id, '_sfwd-quizzes', true );
		$quiz_attempts_meta = empty( $usermeta ) ? false : $usermeta;
		$quiz_attempts      = array();

		if ( function_exists( 'learndash_certificate_details' ) ) {
			if ( ! empty( $quiz_attempts_meta ) ) {
				foreach ( $quiz_attempts_meta as $quiz_attempt ) {
					$c = learndash_certificate_details( $quiz_attempt['quiz'], $user_id );

					if ( true === $quiz_attempt['has_graded'] && true === LD_QuizPro::quiz_attempt_has_ungraded_question( $quiz_attempt ) ) {
						$passstatus = false;
					} else {
						$passstatus = isset( $quiz_attempt['pass'] ) ? ( ( $quiz_attempt['pass'] == 1 ) ? true : false ) : false;
					}

					$quiz_attempt['pass_status'] = $passstatus;

					$quiz_attempt["percentage"] = ! empty( $quiz_attempt["percentage"] ) ? $quiz_attempt["percentage"] : ( ! empty( $quiz_attempt["count"] ) ? $quiz_attempt["score"] * 100 / $quiz_attempt["count"] : 0 );

					if ( $user_id == get_current_user_id() && ! empty( $c["certificateLink"] ) && ( ( isset( $quiz_attempt['percentage'] ) && $quiz_attempt['percentage'] >= $c["certificate_threshold"] * 100 ) ) ) {
						$quiz_attempt['certificate'] = $c;
					}

					$parent_post = null;
					if ( ! empty( $quiz_attempt['topic'] ) ) {
						$parent_post = $quiz_attempt['topic'];
					} elseif ( ! empty( $quiz_attempt['lesson'] ) ) {
						$parent_post = $quiz_attempt['lesson'];
					} elseif ( ! empty( $quiz_attempt['course'] ) ) {
						$parent_post = $quiz_attempt['course'];
					}

					if ( ! empty( $parent_post ) ) {
//						if ( isset( $quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ] ) ) {
//							if ( $quiz_attempt['percentage'] >= $quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ]['percentage'] ) {
//								if ( $quiz_attempt['completed'] >= $quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ]['completed'] ) {
//									$quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ] = $quiz_attempt;
//								}
//							}
//						} else {
						$quiz_attempts[ $parent_post ][ $quiz_attempt['quiz'] ][] = $quiz_attempt;
//						}
					}
				}
			}
		}

		return $quiz_attempts;
	}

	/**
	 * Create an hierachal object of course lessons, topics, and quiz assocatd user data
	 *
	 * @param $user_courses
	 * @param $user_id
	 * @param $quiz_attempts
	 *
	 * @return array
	 */
	public static function set_up_course_object( $user_courses, $user_id, $quiz_attempts ) {
		$courses = [];

		foreach ( $user_courses as $course_id ) {

			$course          = get_post( $course_id );
			$course_progress = learndash_course_progress( [
				'user_id'   => $user_id,
				'course_id' => $course_id,
				'array'     => true,
			] );
			$course_status   = learndash_course_status( $course_id, $user_id, true );

			$course_certificate = learndash_get_course_certificate_link( $course_id );
			$has_certificate    = true;
			if ( empty( $course_certificate ) ) {
				$course_certificate = null;
				$has_certificate    = false;
			}


			$last_know_step = get_user_meta( get_current_user_id(), 'learndash_last_known_course_' . $course_id, true );
			$resume_url     = null;
			$has_resume_url = false;

			// User has not hit a LD module yet
			if ( ! empty( $last_know_step ) && absint( $last_know_step ) ) {
				$step_id               = $last_know_step;
				$last_know_post_object = get_post( (int) $step_id );

				if ( null !== $last_know_post_object ) {
					$has_resume_url = true;
					if ( function_exists( 'learndash_get_step_permalink' ) ) {
						$resume_url = learndash_get_step_permalink( $step_id, $course_id );
					} else {
						$resume_url = get_permalink( $step_id );
					}
				}
			}

			$courses[ $course_id ] = (object) [
				'id'       => $course_id,
				'title'    => $course->post_title, // string
				'url'      => get_permalink( $course ), // string
				'progress' => $course_progress['percentage'],

				'status'          => $course_status,
				'has_lessons'     => false, // boolean
				'has_quizzes'     => false, // boolean
				'quizzes'         => [], // array
				'has_certificate' => $has_certificate, // boolean
				'has_resume_url'  => $has_resume_url, // boolean

				'certificate_url' => $course_certificate, // string or null
				'resume_url'      => $resume_url, // string or null
			];

			$quizzes = learndash_get_course_quiz_list( $course_id );

			if ( ! empty( $quizzes ) ) {
				foreach ( $quizzes as $key => $quiz ) {

					if ( function_exists( 'learndash_get_step_permalink' ) ) {
						$quiz_url = learndash_get_step_permalink( $quiz['post']->ID, $course_id );
					} else {
						$quiz_url = get_permalink( $quiz['post']->ID );
					}

					if ( isset( $quiz_attempts[ $course_id ][ $quiz['post']->ID ] ) &&
						 ! empty( $quiz_attempts[ $course_id ][ $quiz['post']->ID ] )
					) {
						$courses[ $course_id ]->has_quizzes = true;
						$module_quiz_attempts               = $quiz_attempts[ $course_id ][ $quiz['post']->ID ];
						foreach ( $module_quiz_attempts as $attempt ) {

							$statistic_ref_id = $attempt['statistic_ref_id'];
							$pro_quizid       = $attempt['pro_quizid'];
							$is_completed     = true;
							$taken_on         = ( isset( $attempt['completed'] ) ) ? $attempt['completed'] : null;
							$score            = $attempt['percentage'];
							$statistics_nonce = wp_create_nonce( 'statistic_nonce_' . $statistic_ref_id . '_' . get_current_user_id() . '_' . $user_id );

							if ( true === $attempt['pass_status'] ) {
								$passed = true;
							} else {
								$passed = false;
							}

							$has_certificate = false;
							$certificate_url = '';
							if ( isset( $attempt['certificate'] ) ) {
								$has_certificate = true;
								$certificate_url = $attempt['certificate']['certificateLink'];
							}

							$courses[ $course_id ]->quizzes[] = // array of objects
								(object) [
									'id'    => $quiz['post']->ID, // int
									'title' => $quiz['post']->post_title, // string
									'url'   => $quiz_url, // string

									'taken_on' => $taken_on, // timestamp null

									'score'  => $score, // int null
									'passed' => $passed, // boolean

									'is_completed' => $is_completed, // boolean

									'has_certificate' => $has_certificate, // boolean
									'has_statistics'  => true, // boolean

									'certificate_url'  => $certificate_url, // string
									'statistics_url'   => '#', // string
									'pro_quizid'       => $pro_quizid,
									'statistic_ref_id' => $statistic_ref_id,
									'statistics_nonce' => $statistics_nonce
								];
						}
					}
				}
			}


			$lessons = learndash_get_course_lessons_list( $course, get_current_user_id(), [
				'num' => - 1
			] );

			if ( ! empty( $lessons ) ) {
				$courses[ $course_id ]->has_lessons = true;
				foreach ( $lessons as $lesson ) {

					$is_completed = false;
					if ( 'completed' === $lesson['status'] ) {
						$is_completed = true;
					}

					$is_available = true;
					$available_on = null;
					if ( ! empty( $lesson['lesson_access_from'] ) ) {
						$is_available = false;
						$available_on = $lesson['lesson_access_from'];
					}

					$courses[ $course_id ]->lessons[ $lesson['post']->ID ] =
						(object) [
							'id'          => $lesson['post']->ID, // int
							'title'       => $lesson['post']->post_title, // string
							'url'         => $lesson['permalink'], // string
							'has_quizzes' => false, // boolean

							'is_completed' => $is_completed, // boolean

							'is_available' => $is_available, // boolean
							'available_on' => $available_on, // timestamp || null

							'quizzes' => []
						];

					$topics = learndash_topic_dots( $lesson['post']->ID, false, 'array', $user_id, $course_id );

					$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_topics = false;
					if ( ! empty( $topics ) ) {
						$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_topics = true;
						foreach ( $topics as $key => $topic ) {

							if ( function_exists( 'learndash_get_step_permalink' ) ) {
								$topic_url = learndash_get_step_permalink( $topic->ID, $course_id );
							} else {
								$topic_url = get_permalink( $topic->ID );
							}

							$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->topics[ $topic->ID ] = // array of objects
								(object) [
									'id'    => $topic->ID, // int
									'title' => $topic->post_title, // string
									'url'   => $topic_url, // string

									'is_completed' => ! empty( $topic->completed ) ? true : false, // boolean
								];
						}
					}

					$quizzes = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id, $course_id );

					$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_quizzes = false;
					if ( ! empty( $quizzes ) ) {
						foreach ( $quizzes as $key => $quiz ) {

							if ( function_exists( 'learndash_get_step_permalink' ) ) {
								$quiz_url = learndash_get_step_permalink( $quiz['post']->ID, $course_id );
							} else {
								$quiz_url = get_permalink( $quiz['post']->ID );
							}

							if ( isset( $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ] ) &&
								 ! empty( $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ] )
							) {
								$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->has_quizzes = true;

								$module_quiz_attempts = $quiz_attempts[ $lesson['post']->ID ][ $quiz['post']->ID ];
								foreach ( $module_quiz_attempts as $attempt ) {

									$statistic_ref_id = $attempt['statistic_ref_id'];
									$pro_quizid       = $attempt['pro_quizid'];
									$is_completed     = true;
									$taken_on         = $attempt['completed'];
									$score            = $attempt['percentage'];
									$statistics_nonce = wp_create_nonce( 'statistic_nonce_' . $statistic_ref_id . '_' . get_current_user_id() . '_' . $user_id );

									if ( 1 === $attempt['pass'] ) {
										$passed = true;
									}

									$has_certificate = false;
									$certificate_url = '';
									if ( isset( $attempt['certificate'] ) ) {
										$has_certificate = true;
										$certificate_url = $attempt['certificate']['certificateLink'];
									}

									$courses[ $course_id ]->lessons[ $lesson['post']->ID ]->quizzes[] = // array of objects
										(object) [
											'id'    => $quiz['post']->ID, // int
											'title' => $quiz['post']->post_title, // string
											'url'   => $quiz_url, // string

											'taken_on' => $taken_on, // timestamp null

											'score'  => $score, // int null
											'passed' => $passed, // boolean

											'is_completed' => $is_completed, // boolean

											'has_certificate' => $has_certificate, // boolean
											'has_statistics'  => true, // boolean

											'certificate_url'  => $certificate_url, // string
											'statistics_url'   => '#', // string
											'pro_quizid'       => $pro_quizid,
											'statistic_ref_id' => $statistic_ref_id,
											'statistics_nonce' => $statistics_nonce
										];
								}
							}
						}
					}
				}
			}
		}

		return $courses;
	}

	/**
	 * @return string
	 */
	public
	static function uo_dashboard_get_template() {
		$filepath = self::get_template( 'frontend-dashboard/dashboard-template.php', dirname( dirname( __FILE__ ) ) . '/src' );
		$filepath = apply_filters( 'uo_dashboard_template', $filepath );

		return $filepath;
	}

}
