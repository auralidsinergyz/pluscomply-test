<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class GroupLeaderAccess extends toolkit\Config implements toolkit\RequiredFunctions {
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

			//add_filter( 'current_screen', array( __CLASS__, 'dashboard_redirect' ) );
			add_action( 'admin_bar_menu', array( __CLASS__, 'modify_top_admin_bar' ), 999 );
			add_action( 'admin_menu', array( __CLASS__, 'modify_admin_sidebar_menu' ), 10001 );
			add_action( 'wp_login', array( __CLASS__, 'add_to_group_on_login' ), 99, 3 );
			
			// applied settings from module settings
			$should_redirect   = self::get_settings_value( 'uo-group-access-enable-redirect-leaders', __CLASS__ );
			$prioirty_redirect = self::get_settings_value( 'uo-group-access-enable-redirect-priority', __CLASS__ );
			if ( 'on' === $should_redirect ) {
				if ( empty( $prioirty_redirect ) ) {
					$prioirty_redirect = 999;
				}
				add_filter( 'login_redirect', [ __CLASS__, 'group_leader_login_redirect' ], absint( $prioirty_redirect ), 3 );
			}
			
			add_action( 'wp_dashboard_setup', array( __CLASS__, 'remove_dashboard_widgets' ), 999 );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = __( 'Improved Group Leader Interface', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/group-leader-access/';

		/* Sample Simple Description with shortcode */
		$class_description = __( 'Enhances the experience of LearnDash Group Leaders by providing direct access to reports and removing unnecessary distractions from the admin panel.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-hourglass-end"></i><span class="uo_pro_text">PRO</span>';

		$category          = 'learndash';
		$type = 'pro';

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

		return true;

	}

	/**
	 * HTML for modal to create settings
	 *
	 * @param String
	 *
	 * @return boolean || string Return either false or settings html modal
	 *
	 */
	public static function get_class_settings( $class_title ) {
		$pages[]   = array( 'value' => 0, 'text' => '-- Select Page --' );
		$get_pages = get_pages(
			array(
				'sort_order'  => 'asc',
				'sort_column' => 'post_title',
			) );
		foreach ( $get_pages as $page ) {
			$pages[] = array( 'value' => $page->ID, 'text' => get_the_title( $page->ID ) );
		}
		if ( class_exists( 'LearnDash_Custom_Label' ) ) {
			$learn_dash_labels = new \LearnDash_Custom_Label();
			$course_label      = $learn_dash_labels::get_label( 'courses' );
		}

		// Create options
		$options = array(
			array(
				'type'        => 'select',
				'label'       => esc_html__( 'Select "View ' . $course_label . '" Page', 'uncanny-pro-toolkit' ),
				'select_name' => 'uo-group-access-view-course-link',
				'options'     => $pages,
			),
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Redirect group leaders', 'uncanny-pro-toolkit' ),
				'option_name' => 'uo-group-access-enable-redirect-leaders',
			),
			array(
				'type'        => 'text',
				'placeholder' => '',
				'label'       => esc_html__( 'Redirect URL', 'uncanny-pro-toolkit' ),
				'option_name' => 'uo-group-access-enable-redirect-url',
			),
			array(
				'type'        => 'text',
				'placeholder' => '',
				'label'       => esc_html__( 'Redirect Priority', 'uncanny-pro-toolkit' ),
				'option_name' => 'uo-group-access-enable-redirect-priority',
			)
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
	 *
	 */
	public static function dashboard_redirect() {
		$user   = wp_get_current_user();
		$screen = get_current_screen()->base;
		global $pagenow;
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'group_leader', $user->roles, true ) ) {
				if ( 'dashboard' === $screen || 'about.php' === $pagenow || 'profile.php' === $pagenow ) {
					wp_safe_redirect( admin_url( '/admin.php?page=group_admin_page' ) );
					exit;
				} elseif ( 'learndash-lms_page_uo-my-courses' === $screen ) {
					$view_courses_link = self::get_settings_value( 'uo-group-access-view-course-link', __CLASS__ );
					wp_safe_redirect( get_permalink( $view_courses_link ) );
					exit;
				}
			}
		}
	}

	/**
	 * @param $wp_toolbar
	 */
	public static function modify_top_admin_bar( $wp_toolbar ) {
		$user = wp_get_current_user();
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'group_leader', $user->roles, true ) ) {
				$wp_toolbar->remove_node( 'wp-logo' );
				$wp_toolbar->remove_node( 'about' );
				$wp_toolbar->remove_node( 'wporg' );
				$wp_toolbar->remove_node( 'documentation' );
				$wp_toolbar->remove_node( 'support-forums' );
				$wp_toolbar->remove_node( 'feedback' );
				$wp_toolbar->remove_node( 'new-content' );
				$wp_toolbar->remove_node( 'new-sfwd-assignment' );
				$wp_toolbar->remove_node( 'edit-profile' );
				$wp_toolbar->remove_node( 'view-store' );

				$wp_toolbar->add_node( array(
					'parent' => '',
					'id'     => 'view-reports',
					'title'  => '<span class="user-url">' . __( 'View Reports', 'uncanny-pro-toolkit'  ) . '</span>',
					'href'   => esc_url( admin_url( '/admin.php?page=group_admin_page' ) ),
				) );
				if ( class_exists( 'LearnDash_Custom_Label' ) ) {
					$learn_dash_labels = new \LearnDash_Custom_Label();
					$course_label      = $learn_dash_labels::get_label( 'courses' );
				}

				if ( empty( $course_label ) ) {
					$course_label = 'Courses';
				}

				$view_courses_link = self::get_settings_value( 'uo-group-access-view-course-link', __CLASS__ );
				if ( ! empty( $view_courses_link ) ) {
					$wp_toolbar->add_node( array(
						'parent' => '',
						'id'     => 'view-courses',
						'title'  => '<span class="user-url">' . sprintf( __( 'View %s', 'uncanny-pro-toolkit' ), $course_label ) . '</span>',
						'href'   => get_permalink( $view_courses_link ),
					) );
				}
			}
		}
	}

	/**
	 *
	 */
	public static function modify_admin_sidebar_menu() {
		$user = wp_get_current_user();
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'group_leader', $user->roles, true ) ) {
				remove_menu_page( 'vc-welcome' );
				//remove_menu_page( 'index.php' );
				remove_menu_page( 'profile.php' );

				if ( class_exists( 'LearnDash_Custom_Label' ) ) {
					$learn_dash_labels = new \LearnDash_Custom_Label();
					$course_label      = $learn_dash_labels::get_label( 'courses' );
				}

				if ( empty( $course_label ) ) {
					$course_label = 'Courses';
				}
				$page_title = sprintf( esc_html__( 'View %s', 'uncanny-pro-toolkit' ), $course_label);
				$menu_title = sprintf( esc_html__( 'View %s', 'uncanny-pro-toolkit' ), $course_label);
				$capability = 'read';
				$menu_slug  = 'uo-my-courses';
				$function   = array( __CLASS__, 'options_menu_page_output' );

				$view_courses_link = self::get_settings_value( 'uo-group-access-view-course-link', __CLASS__ );

				if ( ! empty( $view_courses_link ) ) {
					add_submenu_page( 'learndash-lms', $page_title, $menu_title, $capability, $menu_slug, $function );
				}
			}
		}
	}

	/**
	 *
	 */
	public static function options_menu_page_output() {

	}

	/**
	 * @param $redirect_to
	 * @param $request
	 * @param $user
	 *
	 * @return string
	 */
	public static function group_leader_login_redirect( $redirect_to, $request, $user ) {
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'group_leader', $user->roles, true ) ) {
				$url_redirect = self::get_settings_value( 'uo-group-access-enable-redirect-url', __CLASS__ );
				
				if ( ! empty( $url_redirect ) ) {
					
					return $url_redirect;
				}
				//return admin_url( '/admin.php?page=group_admin_page' );
			}
		}

		return $redirect_to;
	}

	/**
	 * @param $user_login
	 * @param $user
	 */
	public static function add_to_group_on_login( $user_login, $user ) {
		global $wpdb;
		$user_id = $user->ID;
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'group_leader', $user->roles, true ) ) {
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key LIKE '%%%s%%'", $user_id, 'learndash_group_leaders_' ) );
				if ( $results ) {
					foreach ( $results as $result ) {
						$course_access = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key LIKE %s", 'learndash_group_enrolled_' . $result->meta_value ) );
						if ( $course_access ) {
							foreach ( $course_access as $c ) {
								ld_update_course_access( $user_id, $c->post_id );
							}
						}
					}
				}
			}
		}
	}

	/**
	 *
	 */
	function remove_dashboard_widgets() {
		global $wp_meta_boxes;
		$user = wp_get_current_user();
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'group_leader', $user->roles, true ) ) {
				foreach ( $wp_meta_boxes['dashboard']['normal']['core'] as $k => $v ) {
					if ( ! preg_match( '/learndash(.*)/', $k ) ) {
						unset( $wp_meta_boxes['dashboard']['normal']['core'][ $k ] );
					}
				}
				foreach ( $wp_meta_boxes['dashboard']['side']['core'] as $k => $v ) {
					if ( ! preg_match( '/learndash(.*)/', $k ) ) {
						unset( $wp_meta_boxes['dashboard']['side']['core'][ $k ] );
					}
				}
			}
		}
	}
}