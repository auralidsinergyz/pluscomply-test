<?php
/**
 * Accredible LearnDash Add-on admin menu class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-action-handler.php';

if ( ! class_exists( 'Accredible_Learndash_Admin_Menu' ) ) :
	/**
	 * Accredible LearnDash Add-on admin menu class
	 */
	class Accredible_Learndash_Admin_Menu {
		/**
		 * Add plugin pages to wp menu
		 */
		public static function add() {
			$menu_position = 3; // Show our plugin just below LearnDash.

			add_menu_page(
				'Accredible LearnDash Add-on',
				'Accredible LearnDash Add-on',
				'administrator',
				'accredible_learndash',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_settings_page' ),
				'dashicons-awards',
				$menu_position
			);

			add_submenu_page(
				'accredible_learndash',
				'Auto Issuances',
				'Auto Issuances',
				'administrator',
				'accredible_learndash_issuance_list',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_issuance_list_page' )
			);

			add_submenu_page(
				'accredible_learndash',
				'Issuance Logs',
				'Issuance Logs',
				'administrator',
				'accredible_learndash_issuance_log',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_issuance_logs_page' )
			);

			add_submenu_page(
				'accredible_learndash',
				'Settings',
				'Settings',
				'administrator',
				'accredible_learndash_settings',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_settings_page' )
			);

			// Admin action without a view template.
			add_submenu_page(
				null,
				'Admin Action',
				'Admin Action',
				'administrator',
				'accredible_learndash_admin_action',
				array( 'Accredible_Learndash_Admin_Action_Handler', 'call' )
			);
		}

		/**
		 * Render admin settings page
		 */
		public static function admin_settings_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-settings.php';
		}

		/**
		 * Render admin issuance list page
		 */
		public static function admin_issuance_list_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-issuance-list.php';
		}

		/**
		 * Render admin auto issuance logs page
		 */
		public static function admin_issuance_logs_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-auto-issuance-logs.php';
		}

		/**
		 * Add plugin action links.
		 *
		 * @param Array $links An array of plugin links.
		 */
		public static function add_action_links( $links ) {
			$mylinks = array(
				'<a href="' . admin_url( 'admin.php?page=accredible_learndash_settings' ) . '">Settings</a>',
			);
			return array_merge( $mylinks, $links );
		}
	}
endif;
