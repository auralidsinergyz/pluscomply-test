<?php

namespace uncanny_learndash_groups;

/**
 * Class Boot
 *
 * @package uncanny_learndash_groups
 */
class Load_Groups {

	/**
	 * The instance of the class
	 *
	 * @var      Load_Groups
	 * @since    1.0.0
	 * @access   private
	 */
	private static $instance = null;

	/**
	 * The directories that are auto loaded and initialized
	 *
	 * @var      array
	 * @since    1.0.0
	 * @access   private
	 */
	private static $auto_loaded_classes = null;
	/**
	 * @var
	 */
	public static $class_instances;

	/**
	 * @return Load_Groups|null
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			// Lets boot up!
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * class constructor
	 */
	private function __construct() {
		add_action(
			'plugins_loaded',
			array(
				$this,
				'uncanny_learndash_groups_text_domain',
			)
		);

		$this->initialize_core();

		// Import Gutenberg Blocks
		require_once ULGM_ABSPATH . 'src/blocks/blocks.php';
		new Blocks();

		// Add group management role
		$this->groups_capabilities();

		// Add settings link on plugin page
		$uncanny_learndash_groups_plugin_basename = plugin_basename( UNCANNY_GROUPS_PLUGIN_FILE );
		add_filter(
			'plugin_action_links_' . $uncanny_learndash_groups_plugin_basename,
			array(
				__CLASS__,
				'uncanny_learndash_groups_plugin_settings_link',
			),
			22
		);
		// Add feature image support to LD Groups
		add_filter(
			'register_post_type_args',
			array(
				__CLASS__,
				'uo_groups_add_support',
			),
			99,
			2
		);

	}

	/**
	 * Allow Translations to be loaded
	 */
	public function uncanny_learndash_groups_text_domain() {
		load_plugin_textdomain( 'uncanny-learndash-groups', false, basename( UNCANNY_GROUPS_PLUGIN ) . '/languages/' );
	}

	/**
	 *
	 */
	public function initialize_core() {

		// Load Utilities
		$this->initialize_utilities();

		// Load Configuration
		$this->initialize_config();

		// Statically load Groups file to speed up plugin
		$this->require_class_files();
		add_action(
			'woocommerce_loaded',
			array(
				$this,
				'load_woo_functions',
			),
			9
		);
	}

	/**
	 *
	 */
	public function load_woo_functions() {
		include_once __DIR__ . '/global-woo-functions.php';
		\Uncanny_Groups_Woo::get_instance();
	}

	/**
	 * Initialize Static singleton class that has shared function and variables
	 * that can be used anywhere in WP
	 *
	 * @since 1.0.0
	 */
	private function initialize_utilities() {
		include_once ULGM_ABSPATH . 'src/classes/class-utilities.php';
		Utilities::get_instance();

	}

	/**
	 * Initialize Static singleton class that configures all constants,
	 * utilities variables and handles activation/deactivation
	 *
	 * @since 1.0.0
	 */
	private function initialize_config() {

		include_once ULGM_ABSPATH . 'src/classes/class-config.php';
		$config_instance = Config::get_instance();

		$plugin_name = apply_filters( 'ulgm_plugin_name', 'Uncanny Groups for LearnDash' );

		$config_instance->configure_plugin_before_boot( $plugin_name, 'ulgm', UNCANNY_GROUPS_VERSION, UNCANNY_GROUPS_PLUGIN_FILE, false );

	}

	/**
	 *
	 */
	public function require_class_files() {
		if ( is_admin() ) {
			self::$auto_loaded_classes['AdminMenu']             = __DIR__ . '/classes/admin/class-admin-menu.php';
			self::$auto_loaded_classes['AdminPage']             = __DIR__ . '/classes/admin/class-admin-page.php';
			self::$auto_loaded_classes['Admin_Support']         = __DIR__ . '/classes/admin/class-admin-support.php';
			self::$auto_loaded_classes['AdminGroupEmailFields'] = __DIR__ . '/classes/admin/class-admin-group-email-fields.php';
			self::$auto_loaded_classes['Tools']                 = __DIR__ . '/classes/admin/tools/class-tools.php';

			// Licensing
			self::$auto_loaded_classes['Licensing'] = __DIR__ . '/classes/admin/licensing/licensing.php';

			// Install Automator.
			self::$auto_loaded_classes['Install_Automator'] = __DIR__ . '/classes/admin/install-automator/install-automator.php';

			// LearnDash Group is deleted
			self::$auto_loaded_classes['ProcessGroupDeletion'] = __DIR__ . '/classes/admin/process-group-deletion.php';

			// LearnDash modifications
			self::$auto_loaded_classes['LearnDash_Modifications'] = __DIR__ . '/classes/learndash/class-learndash-modifications.php';
		}

		self::$auto_loaded_classes['AdminCreateGroup'] = __DIR__ . '/classes/admin/class-admin-create-group.php';

		// Global / Helpers
		self::$auto_loaded_classes['Admin_Rest_API']           = __DIR__ . '/classes/admin/class-admin-rest-api.php';
		self::$auto_loaded_classes['Group_Management_Helpers'] = __DIR__ . '/classes/group-management/class-group-management-helpers.php';
		self::$auto_loaded_classes['RestApiEndPoints']         = __DIR__ . '/classes/helpers/rest-api-end-points.php';
		self::$auto_loaded_classes['SharedFunctions']          = __DIR__ . '/classes/helpers/shared-functions.php';
		self::$auto_loaded_classes['SharedVariables']          = __DIR__ . '/classes/helpers/shared-variables.php';
		// Migrate LearnDash groups
		self::$auto_loaded_classes['MigrateLearndashGroups'] = __DIR__ . '/classes/admin/migrate-learndash-groups.php';

		// Includes
		self::$auto_loaded_classes['Database'] = __DIR__ . '/includes/database.php';

		if ( Utilities::if_woocommerce_active() ) {
			// Bulk discount
			self::$auto_loaded_classes['WoocommerceBulkDiscount']             = __DIR__ . '/classes/woocommerce/woocommerce-bulk-discount.php';
			self::$auto_loaded_classes['WoocommerceBuyCourses']               = __DIR__ . '/classes/woocommerce/woocommerce-buy-courses.php';
			self::$auto_loaded_classes['WoocommerceCourses']                  = __DIR__ . '/classes/woocommerce/woocommerce-courses.php';
			self::$auto_loaded_classes['WooCommerceLearndashGroups']          = __DIR__ . '/classes/woocommerce/woocommerce-learndash-groups.php';
			self::$auto_loaded_classes['WoocommerceLicense']                  = __DIR__ . '/classes/woocommerce/woocommerce-license.php';
			self::$auto_loaded_classes['WoocommerceModifyGroup']              = __DIR__ . '/classes/woocommerce/woocommerce-modify-group.php';
			self::$auto_loaded_classes['Woo_Product_Visibility']              = __DIR__ . '/classes/woocommerce/woocommerce-product-visibility.php';
			self::$auto_loaded_classes['WoocommercePaymentComplete']          = __DIR__ . '/classes/woocommerce/woocommerce-payment-complete.php';
			self::$auto_loaded_classes['WoocommerceGroupLicenseSwapProducts'] = __DIR__ . '/classes/woocommerce/woocommerce-group-license-swap.php';
			self::$auto_loaded_classes['WoocommerceMinMaxQuantity']           = __DIR__ . '/classes/woocommerce/woocommerce-min-max-quantity.php';
		}

		if ( Utilities::if_woocommerce_active() && Utilities::if_woocommerce_subscription_active() ) {
			self::$auto_loaded_classes['WoocommerceLicenseSubscription'] = __DIR__ . '/classes/woocommerce/woocommerce-license-subscription.php';
		}

		self::$auto_loaded_classes['ProcessManualGroup'] = __DIR__ . '/classes/helpers/process-manual-group.php';
		self::$auto_loaded_classes['UserCodeRedemption'] = __DIR__ . '/classes/helpers/user-code-redemption.php';

		// Shortcode
		self::$auto_loaded_classes['DataShortcodes']       = __DIR__ . '/classes/shortcodes/data-shortcodes.php';
		self::$auto_loaded_classes['MemberStyleShortcode'] = __DIR__ . '/classes/shortcodes/member-style-shortcode.php';

		// LearnDash
		self::$auto_loaded_classes['LearndashFunctionOverrides']       = __DIR__ . '/classes/learndash/learndash-function-overrides.php';
		self::$auto_loaded_classes['LearndashGroupsPostEditAdditions'] = __DIR__ . '/classes/learndash/learndash-groups-post-edit-additions.php';

		// BuddyBoss
		if ( defined( 'BP_PLATFORM_VERSION' ) ) {
			self::$auto_loaded_classes['BuddyBossSync'] = __DIR__ . '/classes/buddyboss/buddy-boss-sync.php';
		}

		// Reports
		self::$auto_loaded_classes['LearnDashProgressReport'] = __DIR__ . '/classes/reports/learndash-progress-report.php';
		self::$auto_loaded_classes['GroupAssignments']        = __DIR__ . '/classes/reports/group-assignments.php';
		self::$auto_loaded_classes['GroupEssays']             = __DIR__ . '/classes/reports/group-essays.php';
		self::$auto_loaded_classes['GroupQuizReport']         = __DIR__ . '/classes/reports/group-quiz-report.php';
		self::$auto_loaded_classes['GroupReportsInterface']   = __DIR__ . '/classes/reports/group-reports-interface.php';

		// Gravity Forms
		if ( Utilities::if_gravity_forms_active() ) {
			self::$auto_loaded_classes['GravityFormsSupport']   = __DIR__ . '/classes/gravity-forms/gravity-forms-support.php';
			self::$auto_loaded_classes['GravityFormsCodeField'] = __DIR__ . '/classes/gravity-forms/gravity-forms-code-field.php';
		}

		// Formidable Forms
		if ( Utilities::if_formidable_active() ) {
			self::$auto_loaded_classes['Formidable'] = __DIR__ . '/classes/formidable/class-formidable.php';
		}

		// Formidable Forms
		if ( Utilities::if_wpforms_active() ) {
			self::$auto_loaded_classes['WPForms'] = __DIR__ . '/classes/wpforms/class-wpforms.php';
		}

		// Theme My Login
		if ( Utilities::if_tml_active() ) {
			self::$auto_loaded_classes['ThemeMyLoginSupport'] = __DIR__ . '/classes/theme-my-login/theme-my-login-support.php';
		}

		// Yoast SEO
		self::$auto_loaded_classes['YoastOverrides'] = __DIR__ . '/classes/yoast-seo/class-yoast-overrides.php';

		// Group Management
		self::$auto_loaded_classes['GroupManagementInterface']     = __DIR__ . '/classes/group-management/group-management-interface.php';
		self::$auto_loaded_classes['GroupManagementRegistration']  = __DIR__ . '/classes/group-management/group-management-registration.php';
		self::$auto_loaded_classes['ManagementGroupMultiAddUsers'] = __DIR__ . '/classes/group-management/management-group-multi-add-users.php';

		$this->auto_initialize_classes();
	}

	/**
	 * Looks through all defined directories and modifies file name to create
	 * new class instance.
	 *
	 * @since 1.0.0
	 */
	private function auto_initialize_classes() {
		if ( empty( self::$auto_loaded_classes ) ) {
			return;
		}
		// Check each directory
		foreach ( self::$auto_loaded_classes as $class_name => $file ) {
			require $file;
			$class = __NAMESPACE__ . '\\' . $class_name;

			$obj = new $class();
			Utilities::set_class_instance( $class_name, $obj );
			self::$class_instances[ $class_name ] = $obj;
		}
	}

	/**
	 *
	 */
	public function groups_capabilities() {

		// Set which roles will need access
		$set_role_capabilities = array(
			'group_leader'  => array( 'ulgm_group_management' ),
			'administrator' => array( 'ulgm_group_management' ),
		);

		/**
		 * Filters role based capabilities before being added
		 *
		 * @param string $set_role_capabilities Path to the plugins template folder
		 *
		 * @since 1.0.0
		 */
		$set_role_capabilities = apply_filters( 'ulgm_add_role_capabilities', $set_role_capabilities );

		include_once __DIR__ . '/includes/capabilities.php';
		$capabilities = new Capabilities( $set_role_capabilities );
		$capabilities->add_capabilities();

	}

	/**
	 * @param $links
	 *
	 * @return mixed
	 */
	public static function uncanny_learndash_groups_plugin_settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=uncanny-learndash-groups-licensing' ) . '">' . __( 'Licensing', 'uncanny-learndash-groups' ) . '</a>';
		array_unshift( $links, $settings_link );
		$settings_link = '<a href="' . admin_url( 'admin.php?page=uncanny-groups' ) . '">' . __( 'Settings', 'uncanny-learndash-groups' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Add support to LearnDash Groups for feature image.
	 *
	 * @param array $args LD group post type.
	 * @param $post_type
	 *
	 * @since 3.0.0
	 * @return array
	 */
	public static function uo_groups_add_support( $args, $post_type ) {
		if ( 'groups' !== $post_type ) {
			return $args;
		}

		if ( isset( $args['supports'] ) ) {
			$args['supports'][] = 'thumbnail';
		} else {
			$args['supports'] = array( 'thumbnail' );
		}

		return $args;
	}

}
