<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class AdminMenu
 *
 * @package uncanny_learndash_groups
 */
class AdminPage {


	/**
	 * An array of localized and filtered strings that are used in templates
	 *
	 * @var      array
	 * @since    1.0.0
	 * @access   private
	 */
	static $ulgm_management_admin_page = array();

	/**
	 * class constructor
	 */
	public function __construct() {

		// Create Plugin admin menus, pages, and sub-pages
		add_action( 'admin_menu', array( $this, 'create_admin_area' ), 150 );

	}

	/**
	 * Create Plugin admin menus, pages, and sub-pages
	 *
	 * @since 1.0.0
	 */
	public function create_admin_area() {

		$menu_slug   = 'uncanny-groups';
		$capability  = 'manage_options';
		$function    = array( $this, 'options_output' );
		$parent_slug = 'uncanny-groups-create-group';
		// Register main page settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Create a link the main page when the menu expands
		add_submenu_page( $parent_slug, __( 'Settings', 'uncanny-learndash-groups' ), __( 'Settings', 'uncanny-learndash-groups' ), $capability, $menu_slug, $function );
		add_submenu_page(
			$parent_slug,
			__( 'Email settings', 'uncanny-learndash-groups' ),
			__( 'Email settings', 'uncanny-learndash-groups' ),
			$capability,
			$menu_slug . '-email-settings',
			array(
				$this,
				'email_settings_output',
			)
		);

		// Enqueue page specific styles
		add_action(
			'admin_enqueue_scripts',
			array(
				$this,
				'admin_scripts',
			),
			30
		);

	}

	/**
	 * Register a settings and its data
	 *
	 * @since 1.0.0
	 */
	function register_settings() {

		register_setting( 'ulgm-settings-group', 'ulgm_welcome_email_subject' );
		register_setting( 'ulgm-settings-group', 'ulgm_welcome_email_body' );

		register_setting( 'ulgm-settings-group', 'ulgm_redemption_email_subject' );
		register_setting( 'ulgm-settings-group', 'ulgm_redemption_email_body' );

	}

	/**
	 * Create Theme Options page
	 *
	 * @since 1.0.0
	 */
	public function options_output() {

		$this->localize_filter_globalize_text();

		include Utilities::get_template( 'admin/admin-groups.php' );

	}

	/**
	 * Create Theme Options page
	 *
	 * @since 1.0.0
	 */
	public function email_settings_output() {

		$this->localize_filter_globalize_text();

		include Utilities::get_template( 'admin/admin-email-settings.php' );

	}

	/**
	 * Filter and localize all text, then set as global for use in template file
	 *
	 * @since 1.0.0
	 */
	public function localize_filter_globalize_text() {

		// Page message set by rest call (localized during rest call)
		if ( ulgm_filter_has_var( 'message' ) ) {
			self::$ulgm_management_admin_page['text']['message'] = esc_html( wp_kses( ulgm_filter_input( 'message' ), array() ) );
		} else {
			self::$ulgm_management_admin_page['text']['message'] = '';
		}

		// User invitation email subject
		self::$ulgm_management_admin_page['ulgm_term_condition'] = SharedVariables::ulgm_term_condition();

		// User invitation email subject
		self::$ulgm_management_admin_page['ulgm_invitation_user_email_subject'] = SharedVariables::user_invitation_email_subject();

		// User invitation email body
		self::$ulgm_management_admin_page['ulgm_invitation_user_email_body'] = SharedVariables::user_invitation_email_body();

		// Add and invite email subject
		self::$ulgm_management_admin_page['ulgm_user_welcome_email_subject'] = SharedVariables::user_welcome_email_subject();

		// Add and invite email body
		self::$ulgm_management_admin_page['ulgm_user_welcome_email_body'] = SharedVariables::user_welcome_email_body();

		// Exiting Add and invite email subject
		self::$ulgm_management_admin_page['ulgm_existing_user_welcome_email_subject'] = SharedVariables::exiting_user_welcome_email_subject();

		// ExistingAdd and invite email body
		self::$ulgm_management_admin_page['ulgm_existing_user_welcome_email_body'] = SharedVariables::exiting_user_welcome_email_body();

		// Group leader welcome email subject
		self::$ulgm_management_admin_page['ulgm_group_leader_welcome_email_subject'] = SharedVariables::group_leader_welcome_email_subject();

		// Group leader welcome email body
		self::$ulgm_management_admin_page['ulgm_group_leader_welcome_email_body'] = SharedVariables::group_leader_welcome_email_body();

		// Existing group leader welcome email subject
		self::$ulgm_management_admin_page['ulgm_existing_group_leader_welcome_email_subject'] = SharedVariables::existing_group_leader_welcome_email_subject();

		// Existing group leader welcome email body
		self::$ulgm_management_admin_page['ulgm_existing_group_leader_welcome_email_body'] = SharedVariables::existing_group_leader_welcome_email_body();

		// Existing group leader welcome email subject
		self::$ulgm_management_admin_page['ulgm_new_group_purchase_email_subject'] = SharedVariables::ulgm_new_group_purchase_email_subject();

		// Existing group leader welcome email body
		self::$ulgm_management_admin_page['ulgm_new_group_purchase_email_body'] = SharedVariables::ulgm_new_group_purchase_email_body();

		// Group management page
		self::$ulgm_management_admin_page['ulgm_group_management_page'] = SharedFunctions::get_group_management_page_id();

		// Group Buy Courses page
		self::$ulgm_management_admin_page['ulgm_group_buy_courses_page'] = SharedFunctions::get_buy_courses_page_id();

		// Group report page
		self::$ulgm_management_admin_page['ulgm_group_report_page'] = SharedFunctions::get_group_report_page_id();

		// Group quiz report page
		self::$ulgm_management_admin_page['ulgm_group_quiz_report_page'] = SharedFunctions::get_group_quiz_report_page_id();

		// Group manage progress page
		self::$ulgm_management_admin_page['ulgm_group_manage_progress_page'] = SharedFunctions::get_group_manage_progress_report_page_id();

		// Group assignment report page
		self::$ulgm_management_admin_page['ulgm_group_assignment_report_page'] = SharedFunctions::get_group_assignment_report_page_id();

		// Group essay report page
		self::$ulgm_management_admin_page['ulgm_group_essay_report_page'] = SharedFunctions::get_group_essay_report_page_id();

		// Color Settings
		self::$ulgm_management_admin_page['ulgm_main_color']                  = get_option( 'ulgm_main_color', '#ff9655' );
		self::$ulgm_management_admin_page['text']['ulgm_main_color']          = __( 'Accent Color', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['ulgm_font_color']                  = get_option( 'ulgm_font_color', '#fff' );
		self::$ulgm_management_admin_page['text']['ulgm_font_color']          = __( 'Font Color', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_add_to_cart_message'] = __( 'Adding Seats Message', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['ulgm_add_to_cart_message']         = get_option( 'ulgm_add_to_cart_message', '' );

		// Per seat text
		self::$ulgm_management_admin_page['ulgm_per_seat_text']                              = get_option( 'ulgm_per_seat_text', __( 'Seat', 'uncanny-learndash-groups' ) );
		self::$ulgm_management_admin_page['ulgm_per_seat_text_plural']                       = get_option( 'ulgm_per_seat_text_plural', __( 'Seats', 'uncanny-learndash-groups' ) );
		self::$ulgm_management_admin_page['text']['page_settings']                           = __( 'Page Setup', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['page_settings_general']                   = __( 'General', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['page_settings_ecommerce']                 = __( 'WooCommerce', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['page_settings_woocommerce_subscriptions'] = __( 'WooCommerce Subscriptions', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['page_settings_learndash']                 = __( 'LearnDash', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_per_seat_text']                      = __( 'Per Seat Text - Singular', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_per_seat_text_plural']               = __( 'Per Seat Text - Plural', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['show_license_product_on_front']           = __( 'Show License Products in Store', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['do_not_add_group_leader_as_member']       = __( 'Do not automatically add Group Leaders as Group Members', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['allow_group_leader_change_username']      = __( 'Allow Group Leaders to change username', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['allow_group_leader_edit_users']           = __( 'Allow Group Leaders to edit users', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['allow_to_remove_users_anytime']           = __( 'Allow Group Leaders to remove students at any time', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['do_not_restore_seat_if_user_is_removed']  = __( 'Do not free up a seat when a student with "Completed" status is removed', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['allow_group_leaders_to_manage_progress']  = __( 'Allow Group Leaders to Manage Progress', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['group_leaders_dont_use_seats']            = __( "Group Leaders don't use seats", 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['add_courses_as_part_of_license']          = sprintf( __( 'Automatically include Group %s products in Group License purchases', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );
		self::$ulgm_management_admin_page['text']['ld_hide_courses_users_column']            = sprintf( __( 'Hide the %s / Users column in the list of LearnDash groups', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );
		self::$ulgm_management_admin_page['text']['add_groups_as_woo_products']              = __( 'Enable association of products and groups', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_complete_group_license_orders']      = __( 'Autocomplete Uncanny Groups orders', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_group_management_page']              = __( 'Group Management Page', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_group_buy_courses_page']             = sprintf( __( 'Buy %s Page', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );
		self::$ulgm_management_admin_page['text']['ulgm_group_report_page']                  = sprintf( __( '%s Report Page', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );
		self::$ulgm_management_admin_page['text']['ulgm_group_quiz_report_page']             = sprintf( _x( '%s Report Page', 'Quiz Report Page', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quiz' ) );
		self::$ulgm_management_admin_page['text']['ulgm_group_manage_progress_page']         = __( 'Progress Report Page', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_group_assignment_report_page']       = __( 'Assignment Management Page', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_group_essay_report_page']            = __( 'Essay Management Page', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_group_license_product_cat']          = __( 'Default License Product category', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ulgm_migrate_old_groups_to_new']          = __( 'Start Process', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['use_progress_report_instead_course']      = sprintf( __( 'Use Progress Report instead of %s Report for individual users', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );
		self::$ulgm_management_admin_page['text']['show_basic_groups_in_frontend']           = __( 'Show "basic" (non-upgraded) groups in front end with access to reports only', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['use_legacy_course_progress']              = __( "Use LearnDash's 'legacy' course progress data", 'uncanny-learndash-groups' );

		self::$ulgm_management_admin_page['text']['redemption_email_template']                    = __( 'Send enrollment key', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['user_welcome_email_template']                  = __( 'Add and invite (new user)', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['existing_user_welcome_email_template']         = __( 'Add and invite (existing user)', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['group_leader_welcome_email_template']          = __( 'Add group leader/Create group (new user)', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['existing_group_leader_welcome_email_template'] = __( 'Add group leader/Create group (existing user)', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['new_group_purchase_email_template']            = __( 'New group purchase', 'uncanny-learndash-groups' );

		// Global Email Settings
		self::$ulgm_management_admin_page['text']['email_settings'] = __( 'Email Settings', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['email_from']             = get_option( 'ulgm_email_from', '' );
		self::$ulgm_management_admin_page['text']['email_from']     = __( 'From Email', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['name_from']              = get_option( 'ulgm_name_from', '' );
		self::$ulgm_management_admin_page['text']['name_from']      = __( 'From Name', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['reply_to']               = get_option( 'ulgm_reply_to', '' );
		self::$ulgm_management_admin_page['text']['reply_to']       = __( 'Reply To', 'uncanny-learndash-groups' );

		self::$ulgm_management_admin_page['text']['subject']   = __( 'Subject', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['body']      = __( 'Body', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['variables'] = __( 'Variables', 'uncanny-learndash-groups' );
		//self::$ulgm_management_admin_page['text']['test_email_address'] = __( 'Test Email Address: ', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['redemption_email_template '] = __( 'Send enrollment key', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['save_changes']               = __( 'Save Changes', 'uncanny-learndash-groups' );

		self::$ulgm_management_admin_page['text']['ld_hierarchy_settings_child_groups_in_reports'] = __( 'Include data from child groups in reports', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ld_pool_seats_in_hierarchy']                    = __( 'Allow Group Leaders to enable seat pooling from the Group Management page', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['ld_pool_seats_all_groups']                      = __( 'Enable pooled seats for all groups in hierarchies', 'uncanny-learndash-groups' );

		// WooCommerce subscriptions
		self::$ulgm_management_admin_page['text']['woo_subscription_allow_additional_seats']                 = __( 'Enable "Add seats" option for subscription-based groups', 'uncanny-learndash-groups' );
		self::$ulgm_management_admin_page['text']['woo_subscription_allow_additional_seats_learn_more_link'] = __( 'Learn more URL', 'uncanny-learndash-groups' );
	}

	/**
	 * Load Scripts that are specific to the admin page
	 *
	 * @param string $hook Admin page being loaded
	 *
	 * @since 1.0
	 */
	public function admin_scripts( $hook ) {
		/*
		 * Only load styles if the page hook contains the pages slug
		 *
		 * Hook can be either the toplevel_page_{page_slug} if its a parent  page OR
		 * it can be {parent_slug}_pag_{page_slug} if it is a sub page.
		 * Lets just check if our page slug is in the hook.
		 */
		// Target Licensing page
		//      if ( 'uncanny-groups_page_uncanny-learndash-groups-licensing' === $hook ) {
		//          // Load Styles for Licensing page located in general plugin styles
		//      }

		// Target group management page
		if (
			(
				ulgm_filter_has_var( 'page' ) &&
				(
					preg_match( '/(uncanny-groups)/', ulgm_filter_input( 'page' ) ) ||
					preg_match( '/(groups-install-automator)/', ulgm_filter_input( 'page' ) )
				)
			) ||
			(
				'post.php' === $hook &&
				ulgm_filter_has_var( 'post' ) &&
				! empty( ulgm_filter_input( 'post' ) ) &&
				'groups' === get_post_type( ulgm_filter_input( 'post' ) )
			)
		) {
			wp_enqueue_style( 'ulgm-backend', Utilities::get_asset( 'backend', 'bundle.min.css' ), array(), Utilities::get_version() );
			// Add the color picker css file
			wp_enqueue_style( 'wp-color-picker' );
			// Setup group management JS with localized WP Rest API variables @see rest-api-end-points.php
			if ( SharedFunctions::load_backend_bundles() ) {
				wp_register_script(
					'ulgm-backend',
					Utilities::get_asset( 'backend', 'bundle.min.js' ),
					array(
						'jquery',
						'wp-color-picker',
					),
					Utilities::get_version(),
					true
				);

				// API data
				$api_setup = array(
					'root'  => esc_url_raw( rest_url() . 'ulgm_management/v1/' ),
					'nonce' => \wp_create_nonce( 'wp_rest' ),
					'i18n'  => array(
						'youCantDoThat' => __( 'Sorry, you can\'t do that.', 'uncanny-learndash-groups' ),
						'CSV'           => __( 'CSV', 'uncanny-learndash-groups' ),
						'exportCSV'     => __( 'CSV export', 'uncanny-learndash-groups' ),
						'excel'         => __( 'Excel', 'uncanny-learndash-groups' ),
						'exportExcel'   => __( 'Excel export', 'uncanny-learndash-groups' ),
					),
				);

				wp_localize_script( 'ulgm-backend', 'ulgmRestApiSetup', $api_setup );
				wp_enqueue_script( 'ulgm-backend' );
			}
		}
	}

}
