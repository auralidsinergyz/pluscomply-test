<?php
/**
 * Accredible LearnDash Add-on admin main class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-database.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-menu.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-setting.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-scripts.php';
require_once plugin_dir_path( __FILE__ ) . '/ajax/class-accredible-learndash-ajax.php';

if ( ! class_exists( 'Accredible_Learndash_Admin' ) ) :
	/**
	 * Accredible LearnDash Add-on admin main class.
	 */
	final class Accredible_Learndash_Admin {
		/**
		 * Initialize plugin admin hooks.
		 */
		public static function init() {
			return new self();
		}

		/**
		 * Accredible_Learndash_Admin constructor.
		 */
		public function __construct() {
			$this->set_admin_hooks();
		}
		/**
		 * Initialize WP admin hooks.
		 */
		private function set_admin_hooks() {
			$this->add_settings();
			$this->add_menus();
			$this->add_scripts();
			$this->add_page_ajax_scripts();
			$this->add_page_scripts();
			$this->add_style_classes();
			$this->add_ajax_actions();
		}

		/**
		 * Add plugin settings to WP admin.
		 */
		private function add_settings() {
			add_action( 'admin_init', array( 'Accredible_Learndash_Admin_Setting', 'register' ) );
		}

		/**
		 * Add plugin menus to WP admin.
		 */
		private function add_menus() {
			add_action( 'admin_menu', array( 'Accredible_Learndash_Admin_Menu', 'add' ) );
			add_filter(
				'plugin_action_links_' . ACCREDILBE_LEARNDASH_PLUGIN_BASENAME,
				array( 'Accredible_Learndash_Admin_Menu', 'add_action_links' )
			);
		}

		/**
		 * Add scripts to WP admin.
		 */
		private function add_scripts() {
			add_action( 'admin_enqueue_scripts', array( 'Accredible_Learndash_Admin_Scripts', 'load_resources' ) );
		}

		/**
		 * Add page based ajax scripts to WP admin.
		 */
		private function add_page_ajax_scripts() {
			add_action( 'admin_enqueue_scripts', array( 'Accredible_Learndash_Admin_Scripts', 'load_page_ajax' ) );
		}

		/**
		 * Add page based scripts to WP admin.
		 */
		private function add_page_scripts() {
			add_action( 'admin_enqueue_scripts', array( 'Accredible_Learndash_Admin_Scripts', 'load_page_scripts' ) );
		}

		/**
		 * Add style classes to WP admin.
		 */
		private function add_style_classes() {
			add_filter( 'admin_body_class', array( 'Accredible_Learndash_Admin_Scripts', 'add_admin_body_class' ) );
		}

		/**
		 * Add ajax actions to WP admin.
		 */
		public static function add_ajax_actions() {
			add_action( 'wp_ajax_accredible_learndash_ajax_search_groups', array( 'Accredible_Learndash_Ajax', 'search_groups' ) );
			add_action( 'wp_ajax_accredible_learndash_ajax_load_issuer_html', array( 'Accredible_Learndash_Ajax', 'load_issuer_html' ) );
			add_action( 'wp_ajax_accredible_learndash_ajax_load_auto_issuance_list_html', array( 'Accredible_Learndash_Ajax', 'load_auto_issuance_list_html' ) );
			add_action( 'wp_ajax_accredible_learndash_ajax_handle_auto_issuance_action', array( 'Accredible_Learndash_Ajax', 'handle_auto_issuance_action' ) );
			add_action( 'wp_ajax_accredible_learndash_ajax_load_issuance_form_html', array( 'Accredible_Learndash_Ajax', 'load_issuance_form_html' ) );
			add_action( 'wp_ajax_accredible_learndash_ajax_get_group', array( 'Accredible_Learndash_Ajax', 'get_group' ) );
			add_action( 'wp_ajax_accredible_learndash_ajax_get_lessons', array( 'Accredible_Learndash_Ajax', 'get_lessons' ) );
		}
	}
endif;
