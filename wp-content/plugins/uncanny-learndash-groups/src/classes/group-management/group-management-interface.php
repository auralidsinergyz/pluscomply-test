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
class GroupManagementInterface {


	/**
	 * An array of localized and filtered strings that are used in templates
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	public static $ulgm_management_shortcode = array();


	/**
	 * This group ID that is loaded going to be load for management
	 *
	 * @since    1.0.0
	 * @access   v
	 * @var      int
	 */
	public static $ulgm_current_managed_group_id = 0;

	/**
	 * The group objects that are managed by the user
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	public static $ulgm_managed_group_objects = array();

	/**
	 * An array of user with an array of data values
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	public static $ulgm_enrolled_users_data_dt = array();

	/**
	 * An array of user with an array of data values
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	public static $ulgm_group_leaders_data_dt = array();

	/**
	 * Should features for group leaders to modify groups be turned on
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	public static $populate_management_features = true;

	/**
	 * @var bool
	 */
	public static $seats_pooled_in_children = false;

	/**
	 * class constructor
	 */
	public function __construct() {

		// Enqueue Scripts for uo_group_management shortcode
		add_action(
			'wp_enqueue_scripts',
			array(
				$this,
				'uo_group_management_scripts',
			)
		);

		/* ADD FILTERS ACTIONS FUNCTION */
		add_shortcode( 'uo_groups', array( $this, 'uo_group_mgr' ) );
		add_shortcode( 'uo_groups_url', array( $this, 'uo_groups_url' ) );

		add_shortcode( 'uo_groups_button', array( $this, 'uo_groups_button' ) );

		add_shortcode( 'uo_groups_link', array( $this, 'uo_groups_link' ) );

		add_action( 'init', array( $this, 'toggle_pool_seats_value' ) );
	}

	/**
	 * @return void
	 */
	public function toggle_pool_seats_value() {
		if ( ! ulgm_filter_has_var( 'toggle_pool_value' ) ) {
			return;
		}
		$group_id = ulgm_filter_input( 'group-id' );
		$url      = ulgm()->group_management->pages->get_group_management_page_id( true );
		if ( 1 === absint( ulgm_filter_input( 'toggle_pool_value' ) ) ) {
			$r = LearndashGroupsPostEditAdditions::fix_total_number_of_hierarchy_users( $group_id );
			if ( true !== $r ) {
				wp_safe_redirect( $url . '?group-id=' . $group_id . '&message=' . $r );
			}
		}
		if ( 0 === absint( ulgm_filter_input( 'toggle_pool_value' ) ) ) {
			$previous_count = (int) get_post_meta( $group_id, '_ulgm_seats_before_pooling', true );
			$code_group_id  = ulgm()->group_management->seat->get_code_group_id( $group_id );
			LearndashGroupsPostEditAdditions::update_seat_count( $group_id, $code_group_id, $previous_count );
			LearndashGroupsPostEditAdditions::update_user_redeemed_seat_func( $group_id );
			delete_post_meta( $group_id, '_ulgm_seats_before_pooling' );
		}

		update_post_meta( $group_id, 'ulgm_pool_seats_active', ulgm_filter_input( 'toggle_pool_value' ) );
		wp_safe_redirect( $url . '?group-id=' . $group_id );
		exit;
	}

	/**
	 * Loads all scripts and styles required by the shortcode
	 *
	 * @since 1.0.0
	 */
	public function uo_group_management_scripts() {

		global $post;
		$add_thru_custom_code = apply_filters( 'ulgm_allow_assets_for_custom_uo_groups', false, $post );
		// Only add scripts if shortcode is present on page
		if ( Utilities::has_shortcode( $post, 'uo_groups' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups' ) || true === $add_thru_custom_code ) {
			self::enqueue_frontend_assets();
		}

		if ( Utilities::has_shortcode( $post, 'uo_groups_button' ) ) {
			self::enqueue_button_frontend_assets();
		}
	}

	/**
	 * @since    3.7.5
	 * @author   Agus B.
	 * @internal Saad S.
	 */
	public static function enqueue_frontend_assets() {
		global $post;

		if ( ! empty( $post ) ) {
			// DataTables
			wp_enqueue_script(
				'ulgm-datatables',
				Utilities::get_vendor( 'datatables/datatables.min.js' ),
				array( 'jquery' ),
				Utilities::get_version(),
				true
			);

			wp_enqueue_style(
				'ulgm-datatables',
				Utilities::get_vendor( 'datatables/datatables.min.css' ),
				array(),
				Utilities::get_version()
			);

			// Setup group management JS with localized WP Rest API variables @see rest-api-end-points.php
			wp_register_script(
				'ulgm-frontend',
				Utilities::get_asset( 'frontend', 'bundle.min.js' ),
				array(
					'jquery',
					'ulgm-datatables',
				),
				Utilities::get_version(),
				true
			);

			// API data
			$api_setup = array(
				'root'                         => esc_url_raw( rest_url() . 'ulgm_management/v1/' ),
				'ajaxURL'                      => admin_url( 'admin-ajax.php' ),
				'groupManagementUsersEndPoint' => '?action=get_group_management_users',
				'nonce'                        => \wp_create_nonce( 'wp_rest' ),
				'language'                     => self::get_frontend_localized_strings(),
				'i18n'                         => array(
					'searchPlaceholder' => __( 'Search...', 'uncanny-learndash-groups' ),
					'youCantDoThat'     => __( 'Sorry, you can\'t do that.', 'uncanny-learndash-groups' ),
					'CSV'               => __( 'CSV', 'uncanny-learndash-groups' ),
					'exportCSV'         => __( 'CSV export', 'uncanny-learndash-groups' ),
					'excel'             => __( 'Excel', 'uncanny-learndash-groups' ),
					'exportExcel'       => __( 'Excel export', 'uncanny-learndash-groups' ),
					'firstName'         => __( 'First name', 'uncanny-learndash-groups' ),
					'lastName'          => __( 'Last name', 'uncanny-learndash-groups' ),
					'emailAddress'      => __( 'Email', 'uncanny-learndash-groups' ),
				),
			);

			wp_localize_script( 'ulgm-frontend', 'ulgmRestApiSetup', $api_setup );

			// Load DT group leader data
			self::set_group_id();
			self::localize_filter_globalize_text();
			self::set_group_leaders_data();
			self::set_populate_management_features();

			// API data
			$group_data = array(
				'enrolled_user_columns'        => self::set_enrolled_users_columns(),
				'enrolled_user_data'           => RestApiEndPoints::datatable_qrys( true, self::$ulgm_current_managed_group_id ),
				'enrolled_leader_columns'      => self::set_group_leader_columns(),
				'enrolled_leader_data'         => self::$ulgm_group_leaders_data_dt,
				'check_all'                    => self::table_checkbox( 'all' ),
				'populate_management_features' => wp_json_encode( self::$populate_management_features ),
				'groupSeatsLeft'               => ulgm()->group_management->seat->remaining_seats( self::$ulgm_current_managed_group_id ),
			);

			$group_data = apply_filters_deprecated( 'ulgmGroupLeaderData', array( $group_data ), '4.2.1', 'ulgm_group_leader_data' );
			$group_data = apply_filters( 'ulgm_group_leader_data', $group_data );

			wp_localize_script( 'ulgm-frontend', 'ulgmGroupLeaderData', $group_data );

			wp_localize_script( 'ulgm-frontend', 'ulgmGroupManagementLocalized', self::$ulgm_management_shortcode );

			wp_enqueue_script( 'ulgm-frontend' );

			wp_register_style(
				'ulgm-frontend',
				Utilities::get_asset( 'frontend', 'bundle.min.css' ),
				array( 'ulgm-datatables' ),
				Utilities::get_version()
			);

			$user_colors = Utilities::user_colors();

			wp_add_inline_style( 'ulgm-frontend', $user_colors );
			wp_enqueue_style( 'ulgm-frontend', $user_colors );

			wp_enqueue_editor();

			// Load File saving utility
			wp_enqueue_script( 'ulgm-file-saver', Utilities::get_vendor( 'filesaver/js/filesaver.js' ), array(), Utilities::get_version(), true ); // @see https://github.com/eligrey/FileSaver.js

			// Load Modal
			wp_enqueue_script( 'ulgm-modal', Utilities::get_vendor( 'jquery-modal/js/jquery.modal.js' ), array( 'jquery' ), Utilities::get_version(), true ); // @see https://raw.githubusercontent.com/kylefox/jquery-modal/master/jquery.modal.js
			wp_enqueue_script( 'ulgm-select2', Utilities::get_vendor( 'select2/js/select2.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
			wp_enqueue_style( 'ulgm-modal', Utilities::get_vendor( 'jquery-modal/css/jquery.modal.css' ), array(), Utilities::get_version() ); // @see https://raw.githubusercontent.com/kylefox/jquery-modal/master/jquery.modal.css
			wp_enqueue_style( 'ulgm-select2', Utilities::get_vendor( 'select2/css/select2.min.css' ), array(), Utilities::get_version() );
		}

		if ( Utilities::has_shortcode( $post, 'uo_groups_button' ) ) {
			wp_register_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array(), Utilities::get_version() );
			$user_colors = Utilities::user_colors();
			wp_add_inline_style( 'ulgm-frontend', $user_colors );
			wp_enqueue_style( 'ulgm-frontend', $user_colors );
		}
	}

	/**
	 *
	 */
	public static function enqueue_button_frontend_assets() {
		wp_register_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array(), Utilities::get_version() );
		$user_colors = Utilities::user_colors();
		wp_add_inline_style( 'ulgm-frontend', $user_colors );
		wp_enqueue_style( 'ulgm-frontend', $user_colors );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function uo_groups_url( $atts ) {
		// Start output
		ob_start();

		if ( is_user_logged_in() ) {
			$atts = shortcode_atts(
				array(
					'text' => __( 'Group Management', 'uncanny-learndash-groups' ),
				),
				$atts,
				'uo_groups_url'
			);

			$user          = wp_get_current_user();
			$allowed_roles = apply_filters(
				'ulgm_gm_allowed_roles',
				array(
					'administrator',
					'group_leader',
					'ulgm_group_management',
				)
			);
			// Is the user a group leader
			if ( array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {
				$url = SharedFunctions::get_group_management_page_id( true );

				?>

				<div class="uo-groups uo-groups-url">
					<a href="<?php echo $url; ?>" id="uo-groups-url-button"
					   class="button anchor btn btn-large uo-btn"><?php echo $atts['text']; ?></a>
				</div>

				<?php
			}
		}

		// Get output
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function uo_groups_button( $atts ) {
		// Start output
		ob_start();

		if ( is_user_logged_in() ) {
			$atts = shortcode_atts(
				array(
					'text'        => __( 'Group Management', 'uncanny-learndash-groups' ),
					'css_classes' => 'uo-btn ulg-group-management-button',
				),
				$atts,
				'uo_groups_button'
			);

			$user          = wp_get_current_user();
			$allowed_roles = apply_filters(
				'ulgm_gm_allowed_roles',
				array(
					'administrator',
					'group_leader',
					'ulgm_group_management',
				)
			);
			// Is the user a group leader
			if ( array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {
				$url = SharedFunctions::get_group_management_page_id( true );

				?>

				<div class="uo-groups ulg-group-management-button-wrapper">
					<a
						href="<?php echo $url; ?>"
						id="ulg-group-management-button"
						class="<?php echo $atts['css_classes']; ?>">
						<?php echo $atts['text']; ?>
					</a>
				</div>

				<?php
			}
		}

		// Get output
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function uo_groups_link( $atts ) {
		// Start output
		ob_start();

		if ( is_user_logged_in() ) {
			$atts = shortcode_atts(
				array(
					'text'        => __( 'Group Management', 'uncanny-learndash-groups' ),
					'css_classes' => '',
				),
				$atts,
				'uo_groups_button'
			);

			$user          = wp_get_current_user();
			$allowed_roles = apply_filters(
				'ulgm_gm_allowed_roles',
				array(
					'administrator',
					'group_leader',
					'ulgm_group_management',
				)
			);
			// Is the user a group leader
			if ( array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {
				$url = SharedFunctions::get_group_management_page_id( true );

				?>

				<div class="ulg-group-management-link-wrapper">
					<a
						href="<?php echo $url; ?>"
						id="ulg-group-management-link"
						class="<?php echo $atts['css_classes']; ?>">
						<?php echo $atts['text']; ?>
					</a>
				</div>

				<?php
			}
		}

		// Get output
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Return the HTML template that is displayed by the shortcode
	 *
	 * @param array $attributes The attributes passed in the the shortcode
	 * @param string $content The content contained by the shortcode
	 *
	 * @return string $shortcode_template The HTML template loaded
	 * @since 1.0.0
	 */
	public function uo_group_mgr( $attributes, $content = '' ) {

		// make sure that the users has groups.
		$validated = self::set_group_id();

		if ( ! $validated['success'] ) {
			return $validated['message'];
		}

		ob_start();

		self::localize_filter_globalize_text();

		self::set_group_leaders_data();

		/*
		* Show & hide sections
		*/
		$attributes = shortcode_atts(
			array(
				'group_name_selector'               => 'show',
				'add_courses_button'                => 'show',
				'seats_quantity'                    => 'show',
				'add_seats_button'                  => 'show',
				'add_user_button'                   => 'show',
				'remove_user_button'                => 'show',
				'group_email_button'                => 'hide',
				'upload_users_button'               => 'show',
				'download_keys_button'              => 'show',
				'group_leader_section'              => 'show',
				'add_group_leader_button'           => 'show',
				'key_options'                       => 'show',
				'group_courses_section'             => 'show',
				'key_column'                        => 'show',
				// working on this
				'quiz_report_button'                => 'show',
				'assignments_button'                => 'show',
				'essays_button'                     => 'show',
				'progress_management_report_button' => 'show',
				'progress_report_button'            => 'show',
				'first_last_name_required'          => 'no',
				'csv_export_button'                 => 'show',
				'excel_export_button'               => 'hide',
				'enrolled_users_page_length'        => '50',
				'enrolled_users_length_menu'        => '25,50,100,-1 : ' . __( 'All', 'uncanny-learndash-groups' ),
				'group_leaders_page_length'         => '50',
				'group_leaders_length_menu'         => '25,50,100,-1 : ' . __( 'All', 'uncanny-learndash-groups' ),
				'enrolled_users_orderby_column'     => esc_attr__( 'First name', 'uncanny-learndash-groups' ),
				// The ID of the column used to sort
				'enrolled_users_order_column'       => 'asc',
				// Designates the ascending or descending order of the ‘orderby‘ parameter
				'group_leaders_orderby_column'      => esc_attr__( 'First name', 'uncanny-learndash-groups' ),
				// The ID of the column used to sort
				'group_leaders_order_column'        => 'asc',
				// Designates the ascending or descending order of the ‘orderby‘ parameter
			),
			$attributes,
			'uo_groups'
		);

		// General
		$group_name_selector = Utilities::show_section( $attributes['group_name_selector'] );

		// Group Courses
		$add_courses_button    = Utilities::show_section( $attributes['add_courses_button'] );
		$group_courses_section = Utilities::show_section( $attributes['group_courses_section'] );

		// Enrolled Users @ title
		$seats_quantity   = Utilities::show_section( $attributes['seats_quantity'] );
		$add_seats_button = Utilities::show_section( $attributes['add_seats_button'] );

		// Enrolled Users @ buttons
		$add_user_button    = Utilities::show_section( $attributes['add_user_button'] );
		$remove_user_button = Utilities::show_section( $attributes['remove_user_button'] );

		// Group Email
		$add_group_email_button = Utilities::show_section( $attributes['group_email_button'] );
		$user                   = wp_get_current_user();

		$upload_users_button  = Utilities::show_section( $attributes['upload_users_button'] );
		$download_keys_button = Utilities::show_section( $attributes['download_keys_button'] );

		$progress_report_button            = Utilities::show_section( $attributes['progress_report_button'] );
		$progress_management_report_button = Utilities::show_section( $attributes['progress_management_report_button'] );
		$quiz_report_button                = Utilities::show_section( $attributes['quiz_report_button'] );
		$assignment_button                 = Utilities::show_section( $attributes['assignments_button'] );
		$essay_button                      = Utilities::show_section( $attributes['essays_button'] );
		$csv_export_button                 = Utilities::show_section( $attributes['csv_export_button'] );
		$excel_export_button               = Utilities::show_section( $attributes['excel_export_button'] );

		$send_email_course_statuses = apply_filters(
			'ulgm_groups_management_send_emails_group_user_course_statuses',
			array(
				'not-enrolled' => __( 'Not enrolled', 'uncanny-learndash-groups' ),
				'not-started'  => __( 'Not Started', 'uncanny-learndash-groups' ),
				'in-progress'  => __( 'In Progress', 'uncanny-learndash-groups' ),
				'completed'    => __( 'Completed', 'uncanny-learndash-groups' ),
			)
		);

		// Check if the "First name" and "Last name" fields when adding new users must be required
		$first_last_name_required = $attributes['first_last_name_required'] == 'yes';

		$key_column  = Utilities::show_section( $attributes['key_column'] );
		$key_options = Utilities::show_section( $attributes['key_options'] );
		// key_options hide key column will be hidden.
		if ( ! $key_options ) {
			$key_column = false;
		}

		// Check if group leaders are allowed to view basic groups report from group management page
		$show_basic_groups_in_frontend = self::show_basic_groups_in_frontend();
		$populate_management_features  = self::$populate_management_features;

		// Check if it is off from enrollment email settings
		if ( 'yes' !== get_option( 'ulgm_send_code_redemption_email', 'yes' ) ) {
			$key_options = Utilities::show_section( 'hide' );
		}

		// Groups Leaders
		$add_group_leader_button = Utilities::show_section( $attributes['add_group_leader_button'] );
		$group_leader_section    = Utilities::show_section( $attributes['group_leader_section'] );

		// Sanitize and get the default value of the DataTable page length attributes
		$enrolled_users_page_length = Utilities::attr_datatables_page_length( $attributes['enrolled_users_page_length'] );
		$group_leaders_page_length  = Utilities::attr_datatables_page_length( $attributes['group_leaders_page_length'] );
		// Sanitize and get the default value of the DataTable length menu attributes
		$enrolled_users_length_menu = Utilities::attr_datatables_length_menu( $attributes['enrolled_users_length_menu'] );
		$group_leaders_length_menu  = Utilities::attr_datatables_length_menu( $attributes['group_leaders_length_menu'] );

		// Order and orderby
		$enrolled_users_orderby_column = $attributes['enrolled_users_orderby_column']; // The ID of the column used to sort
		$enrolled_users_order_column   = $attributes['enrolled_users_order_column']; // Designates the ascending or descending order of the ‘orderby‘ parameter

		$group_leaders_orderby_column = $attributes['group_leaders_orderby_column']; // The ID of the column used to sort
		$group_leaders_order_column   = $attributes['group_leaders_order_column']; // Designates the ascending or descending order of the ‘orderby‘ parameter

		/*
		* Include template
		*/

		?>

		<script>

			// Shortcode data
			var ulgmGroupManagementShortcode = {
				firstnameLastnameRequired: <?php echo $first_last_name_required ? 1 : 0; ?>,
				tables: {
					enrolledUsers: {
						orderBy: "<?php echo esc_attr( $enrolled_users_orderby_column ); ?>",
						order: "<?php echo esc_attr( $enrolled_users_order_column ); ?>",
					},
					groupLeaders: {
						orderBy: "<?php echo esc_attr( $group_leaders_orderby_column ); ?>",
						order: "<?php echo esc_attr( $group_leaders_order_column ); ?>",
					},
				}
			}

		</script>

		<?php

		include Utilities::get_template( 'frontend-uo_groups/frontend-uo_groups.php' );

		return ob_get_clean();

	}

	/**
	 * @return bool
	 */
	public static function show_basic_groups_in_frontend() {

		$show_basic_groups_in_frontend = get_option( 'show_basic_groups_in_frontend', 'no' );
		if ( 'no' === (string) $show_basic_groups_in_frontend ) {
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public static function use_legacy_course_progress() {

		$use_legacy_course_progress = get_option( 'use_legacy_course_progress', 'no' );

		if ( 'no' === (string) $use_legacy_course_progress ) {
			return false;
		}

		return true;
	}

	/**
	 * Set the group id that the management page is to load
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function set_group_id() {

		$validation = array(
			'success' => false,
			'message' => '',
		);

		$group_leader_id = get_current_user_id();

		// Allow admin to override group_leader_id
		if ( current_user_can( 'manage_options' ) && ulgm_filter_has_var( 'user_id' ) ) {
			$group_leader_id = absint( ulgm_filter_input( 'user_id' ) );
		}

		// Validate the user as a group leader
		$allowed_roles = apply_filters(
			'ulgm_gm_allowed_roles',
			array(
				'administrator',
				'group_leader',
				'ulgm_group_management',
			)
		);
		if ( ! learndash_is_group_leader_user( $group_leader_id ) && empty( array_intersect( $allowed_roles, wp_get_current_user()->roles ) ) ) {
			$validation['message'] = apply_filters( 'ulgm_permissions_callback_message', __( 'You do not have permission to manage groups.', 'uncanny-learndash-groups' ) );

			return $validation;
		}

		$user_group_ids = learndash_get_administrators_group_ids( $group_leader_id );
		$user_group_ids = apply_filters( 'ulgm_user_group_ids', array_map( 'absint', $user_group_ids ), $group_leader_id );

		if ( empty( $user_group_ids ) ) {
			$validation['message'] = __( 'You do not have any groups available to manage.', 'uncanny-learndash-groups' );

			return $validation;
		}

		$user_group_ids = array_map( 'absint', $user_group_ids );

		$args = array(
			'posts_per_page' => 9999,
			'include'        => array_map( 'intval', $user_group_ids ),
			'post_type'      => 'groups',
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		self::$ulgm_managed_group_objects = get_posts( $args );

		if ( empty( self::$ulgm_managed_group_objects ) ) {
			$validation['message'] = __( 'You do not have any groups available to manage.', 'uncanny-learndash-groups' );

			return $validation;
		}

		foreach ( self::$ulgm_managed_group_objects as $key => $group_object ) {
			$code_group_id = ulgm()->group_management->seat->get_code_group_id( $group_object->ID );
			$total_seats   = ulgm()->group_management->seat->total_seats( $group_object->ID );
			$basic_group   = false;
			// Set basic group to true if seats is 0 and code group id is empty
			if ( 0 === $total_seats && empty( $code_group_id ) ) {
				$basic_group = true;
			}
			// Unset groups with no seats only if basic groups are not allowed to show
			if ( false === self::show_basic_groups_in_frontend() && true === $basic_group ) {
				unset( self::$ulgm_managed_group_objects[ $key ] );
				continue;
			}
			// Setting default value of basic group
			self::$ulgm_managed_group_objects[ $key ]->basic_group = $basic_group;
		}

		if ( empty( self::$ulgm_managed_group_objects ) || ! is_array( self::$ulgm_managed_group_objects ) ) {
			$validation['message'] = __( 'You do not have access to manage any group.', 'uncanny-learndash-groups' );

			return $validation;
		}
		$all_groups                          = self::$ulgm_managed_group_objects;
		$first_group                         = array_shift( $all_groups )->ID;
		self::$ulgm_current_managed_group_id = absint( $first_group );
		unset( $all_groups );

		// Maybe override current group id
		if ( ulgm_filter_has_var( 'group-id' ) ) {

			$current_group_id               = absint( ulgm_filter_input( 'group-id' ) );
			$can_the_user_manage_this_group = SharedFunctions::can_user_manage_this_group( $group_leader_id, $current_group_id, $user_group_ids );
			if ( false === $can_the_user_manage_this_group ) {
				$validation['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );

				return $validation;
			}
			self::$ulgm_current_managed_group_id = $current_group_id;
		}

		// Filter selected group on page load
		self::$ulgm_current_managed_group_id = apply_filters( 'ulgm_groups_management_selected_group_id', self::$ulgm_current_managed_group_id, self::$ulgm_managed_group_objects );
		$validation['success']               = true;

		return $validation;
	}


	/**
	 * @param        $user_id
	 * @param string $input_id
	 * @param string $input_class
	 *
	 * @return string
	 */
	public static function table_checkbox( $user_id, $input_id = '', $input_class = 'select-user' ) {

		if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
			if ( ulgm_filter_has_var( 'show-children' ) ) {
				return '';
			}
		}

		if ( true === SharedFunctions::is_basic_group( self::$ulgm_current_managed_group_id ) && false === self::show_basic_groups_in_frontend() ) {
			return '';
		}

		if ( 'all' === $user_id ) {
			$input_class = 'group-management-enrolled-select-all';
		}

		return '<div class="content-select uo-table-cell uo-table-cell-0_5 groups-user-select-checkbox-"><label class="uo-checkbox"><input class="uo-checkbox-input ' . $input_class . ' bb-custom-check" name="user-id[]" value="' . $user_id . '" type="checkbox"><span class="uo-checkbox-checkmark"></span></label></div>';

	}

	/**
	 * @param $text
	 * @param $slug
	 * @param $user_id
	 *
	 * @return false|string
	 */
	public static function user_edit_link( $text, $slug, $user_id ) {
		if ( 'yes' === (string) get_option( 'allow_group_leader_edit_users', 'no' ) ) {
			ob_start();
			?>
			<span class="ulgm-modal-link <?php echo $slug; ?> user_edit_link"
				  data-modal-id="#group-management-edit-user"
				  data-modal-ajax="true" data-endpoint="get_user_details"
				  data-user-id="<?php echo $user_id; ?>"
				  data-group-id="<?php echo self::$ulgm_current_managed_group_id; ?>">
				<?php echo $text; ?>
			</span>
			<?php
			return ob_get_clean();
		} else {
			return $text;
		}
	}

	/**
	 * Get and globalize user data
	 *
	 * @param null $group_id
	 * @param array $args
	 *
	 * @return array|mixed|void
	 * @since 1.0.0
	 */
	public static function set_enrolled_users_data( $group_id = null, $args = array() ) {

		if ( ! empty( self::$ulgm_enrolled_users_data_dt ) ) {
			return;
		}

		if ( empty( self::$ulgm_current_managed_group_id ) && is_null( $group_id ) ) {
			return;
		}

		if ( is_numeric( $group_id ) ) {
			self::$ulgm_current_managed_group_id = absint( $group_id );
		}

		$use_legacy_progress = self::use_legacy_course_progress();

		// Set user that are in the system
		//$groups_user_object = LearndashFunctionOverrides::learndash_get_groups_users( self::$ulgm_current_managed_group_id, true, $args );
		$is_hierarchy_setting_enabled = false;
		if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
			if ( ! ulgm_filter_has_var( 'show-children' ) ) {
				$args['hierarchy-disable'] = true;
			} else {
				$is_hierarchy_setting_enabled = true;
			}
		}
		$groups_user_object = LearndashFunctionOverrides::ulgm_get_group_users( self::$ulgm_current_managed_group_id, $args );

		// Set leaders that are in the system
		//$groups_group_object = LearndashFunctionOverrides::learndash_get_groups_administrator_ids( self::$ulgm_current_managed_group_id, true );

		// Set group enrolled courses

		$learndash_group_enrolled_courses = LearndashFunctionOverrides::learndash_group_enrolled_courses( self::$ulgm_current_managed_group_id, $is_hierarchy_setting_enabled, ! $is_hierarchy_setting_enabled );

		// Set progress link
		$use_progress_report_instead_course = get_option( 'use_progress_report_instead_course', '' );
		if ( 'yes' === $use_progress_report_instead_course ) {
			$user_progress_link = self::$ulgm_management_shortcode['text']['group_progress_management_link'];
		} else {
			$user_progress_link = self::$ulgm_management_shortcode['text']['group_progress_link'];
		}
		// Set progress management link
		$user_progress_management_link = self::$ulgm_management_shortcode['text']['group_progress_link'];
		$completions_rearranged        = array();
		if ( ! $use_legacy_progress ) {
			global $wpdb;

			// Completion
			$q_completions = "
							SELECT post_id as course_id, user_id, activity_completed
							FROM {$wpdb->prefix}learndash_user_activity
							WHERE activity_type = 'course'
							AND activity_completed IS NOT NULL
							AND activity_completed <> 0
							";

			$completions = $wpdb->get_results( $q_completions );
			foreach ( $completions as $completion ) {
				$completions_rearranged[ (int) $completion->user_id ][ (int) $completion->course_id ] = true;
			}

			// In-progress
			$q_in_progress = "
						SELECT a.post_id as course_id, user_id
						FROM {$wpdb->prefix}learndash_user_activity a
						WHERE a.activity_type = 'course'
						AND a.activity_completed = 0
						AND ( a.activity_started != 0 || a.activity_updated != 0)
						";

			$in_progress = $wpdb->get_results( $q_in_progress );

			$in_progress_rearranged = array();

			foreach ( $in_progress as $progress ) {
				$in_progress_rearranged[ (int) $progress->user_id ][ (int) $progress->course_id ] = true;
			}
		}
		if ( $groups_user_object ) {
			foreach ( $groups_user_object as $user ) {

				$f     = empty( $user->first_name ) ? '-' : $user->first_name;
				$l     = empty( $user->last_name ) ? '-' : $user->last_name;
				$email = $user->user_email;
				if ( $use_legacy_progress ) {
					$progress = get_user_meta( $user->ID, '_sfwd-course_progress', true );

				}
				// Default is not completed
				$completed = false;

				// Default progress
				$in_progress = false;

				$not_started = false;

				$status = false;

				// Check group progress courses
				if ( $learndash_group_enrolled_courses ) {
					foreach ( $learndash_group_enrolled_courses as $course_id ) {
						if ( ! $use_legacy_progress ) {
							if ( isset( $in_progress_rearranged[ (int) $user->ID ][ (int) $course_id ] ) ) {
								$in_progress = true;
							} elseif ( isset( $completions_rearranged[ (int) $user->ID ][ (int) $course_id ] ) && false === $in_progress ) {
								$completed = true;
							} else {
								$not_started = true;
							}
						} else {
							if ( isset( $progress[ $course_id ] ) && isset( $progress[ $course_id ]['status'] ) && 'completed' === (string) strtolower( $progress[ $course_id ]['status'] ) ) {
								$completed = true;
							} elseif ( isset( $progress[ $course_id ] ) && (int) $progress[ $course_id ]['completed'] !== (int) $progress[ $course_id ]['total'] ) {
								$in_progress = true;
							} elseif ( isset( $progress[ $course_id ] ) && (int) $progress[ $course_id ]['completed'] === (int) $progress[ $course_id ]['total'] ) {
								$completed = true;
							} else {
								$not_started = true;
							}
						}
					}
				}

				// Set Status
				if ( $in_progress && ! $not_started && ! $completed ) {
					$status_id = 'in-progress';
					$status    = '<a class="status status-in-progress" data-status="in-progress" href="' . $user_progress_link . '?user-id=' . $user->ID . '&group-id=' . self::$ulgm_current_managed_group_id . '">' . __( 'In Progress', 'uncanny-learndash-groups' ) . '</a>';
				}

				if ( $not_started && $in_progress ) {
					$status_id = 'in-progress';
					$status    = '<a class="status status-in-progress" data-status="in-progress" href="' . $user_progress_link . '?user-id=' . $user->ID . '&group-id=' . self::$ulgm_current_managed_group_id . '">' . __( 'In Progress', 'uncanny-learndash-groups' ) . '</a>';
				}

				if ( $not_started && $completed ) {
					$status_id = 'in-progress';
					$status    = '<a class="status status-in-progress" data-status="in-progress" href="' . $user_progress_link . '?user-id=' . $user->ID . '&group-id=' . self::$ulgm_current_managed_group_id . '">' . __( 'In Progress', 'uncanny-learndash-groups' ) . '</a>';
				}

				if ( $in_progress && $completed ) {
					$status_id = 'in-progress';
					$status    = '<a class="status status-in-progress" data-status="in-progress" href="' . $user_progress_link . '?user-id=' . $user->ID . '&group-id=' . self::$ulgm_current_managed_group_id . '">' . __( 'In Progress', 'uncanny-learndash-groups' ) . '</a>';
				}

				if ( $completed && ! $not_started && ! $in_progress ) {
					$status_id = 'completed';
					$status    = '<a class="status status-completed" data-status="completed" href="' . $user_progress_link . '?user-id=' . $user->ID . '&group-id=' . self::$ulgm_current_managed_group_id . '">' . __( 'Completed', 'uncanny-learndash-groups' ) . '</a>';
				}

				$key = ulgm()->group_management->get_user_code( $user->ID, self::$ulgm_current_managed_group_id );

				// If it's in array, convert it to string
				if ( is_array( $key ) && count( $key ) == 1 ) {
					$key = $key[0];
				}

				if ( false === $status ) {
					if ( 'yes' === $use_progress_report_instead_course ) {
						$status_id = 'not-started';
						$status    = '<a class="status status-not-started" data-status="not-started" href="' . $user_progress_link . '?user-id=' . $user->ID . '&group-id=' . self::$ulgm_current_managed_group_id . '">' . __( 'Not Started', 'uncanny-learndash-groups' ) . '</a>';

					} else {
						$status_id = 'not-started';
						$status    = '<div class="status status-not-started" data-status="not-started">' . __( 'Not Started', 'uncanny-learndash-groups' ) . '</div>';
					}
				}

				if ( empty( $learndash_group_enrolled_courses ) ) {
					$status = '<div class="status status-not-started" data-status="not-started">' . apply_filters( 'ulgm_group_management_enrolled_user_na_status', __( 'N/A', 'uncanny-learndash-groups' ), $group_id ) . '</div>';
				}

				$status                              = apply_filters( 'ulgm_group_management_enrolled_user_status', $status, $user, $group_id, $learndash_group_enrolled_courses, $completions_rearranged );
				self::$ulgm_enrolled_users_data_dt[] = apply_filters(
					'ulgm_group_management_enrolled_user_info',
					(object) array(
						'check'      => self::table_checkbox( $user->ID ),
						'first_name' => self::user_edit_link( $f, 'first_name', $user->ID ),
						'last_name'  => self::user_edit_link( $l, 'last_name', $user->ID ),
						'email'      => '<a href="mailto:' . $email . '" class="edit_assignment groups-email-mailto-link">' . $email . '</a>',
						'status'     => $status,
						'status_id'  => apply_filters( 'ulgm_group_management_enrolled_user_status_id', $status_id, $status, $user, $group_id, $learndash_group_enrolled_courses, $completions_rearranged ),
						'key'        => $key,
						'id'         => $user->ID,
					),
					$group_id,
					$user,
					$learndash_group_enrolled_courses
				);
			}
		}
		// Set user that have been sent a code but have not redeemed it
		global $wpdb;

		$codes_group_id  = ulgm()->group_management->seat->get_code_group_id( $group_id );
		$temp_users_code = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->prefix" . SharedFunctions::$db_group_codes_tbl . ' WHERE group_id = %d AND code_status LIKE %s AND student_id IS NULL', $codes_group_id, SharedFunctions::$not_redeemed_status ) );

		foreach ( $temp_users_code as $user ) {

			$f     = $user->first_name;
			$l     = $user->last_name;
			$email = $user->user_email;
			$key   = $user->code;

			self::$ulgm_enrolled_users_data_dt[] = (object) array(
				'check'      => self::table_checkbox( $key ),
				'first_name' => $f,
				'last_name'  => $l,
				'email'      => '<a href="mailto:' . $email . '" class="edit_assignment groups-email-mailto-link">' . $email . '</a>',
				'status'     => apply_filters( 'ulgm_group_management_invited_user_status', '<div class="status status-not-enrolled" data-status="not-enrolled">' . __( 'Not Enrolled', 'uncanny-learndash-groups' ) . '</div>', $user, $group_id ),
				'status_id'  => apply_filters( 'ulgm_group_management_invited_user_status_id', 'not-enrolled', $user, $group_id ),
				'key'        => $key,
				'id'         => null,
			);
		}

		self::$ulgm_enrolled_users_data_dt = apply_filters_deprecated( 'ulgm_enrolled_user_data', array( self::$ulgm_enrolled_users_data_dt ), '4.2.1', 'ulgm_group_management_enrolled_user_data' );
		self::$ulgm_enrolled_users_data_dt = apply_filters( 'ulgm_group_management_enrolled_user_data', self::$ulgm_enrolled_users_data_dt, $group_id, $args );
		if ( is_numeric( $group_id ) ) {
			return self::$ulgm_enrolled_users_data_dt;
		}
	}


	/**
	 * Get and globalize group leader data
	 *
	 * @since 1.0.0
	 */
	public static function set_group_leaders_data() {

		if ( ! empty( self::$ulgm_group_leaders_data_dt ) ) {
			return;
		}

		$groups_user_object = LearndashFunctionOverrides::learndash_get_groups_administrators( self::$ulgm_current_managed_group_id, true );
		if ( $groups_user_object ) {
			foreach ( $groups_user_object as $user ) {
				$f     = get_user_meta( $user->ID, 'first_name', true );
				$l     = get_user_meta( $user->ID, 'last_name', true );
				$email = $user->user_email;

				self::$ulgm_group_leaders_data_dt[] = (object) array(
					'check'      => ( $user->ID !== get_current_user_id() ) ? self::table_checkbox( $user->ID ) : '',
					'first_name' => $f,
					'last_name'  => $l,
					'email'      => '<a href="mailto:' . $email . '" class="edit_assignment groups-email-mailto-link">' . $email . '</a>',
					'id'         => $user->ID,
				);
			}
		}
		self::$ulgm_group_leaders_data_dt = apply_filters( 'ulgm_enrolled_group_leader_data', self::$ulgm_group_leaders_data_dt );
	}

	/**
	 * @param $group_id
	 * @param $total_seats
	 * @param $enrolled_seats
	 * @param $remaining_seats
	 * @param bool $front_end
	 *
	 * @return bool
	 * @since 4.0.5
	 */
	public static function is_reconcile_required( $group_id, $total_seats, $enrolled_seats, $remaining_seats, $front_end = false ) {
		if ( false === apply_filters( 'ulgm_is_reconcile_required', true, $front_end, $group_id, $total_seats, $enrolled_seats, $remaining_seats ) ) {
			return false;
		}

		if ( SharedFunctions::is_pool_seats_enabled_for_current_parent_group( $group_id ) ) {
			$ld_group_id = ulgm()->group_management->seat->get_real_ld_group_id( $group_id );
			//if ( empty( get_post_meta( $ld_group_id, 'reconciled_after_pool_seats', true ) ) ) {
			// remaining seats are equal to total seats but there are users in the group, reconcile!
			$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrators( $group_id, true );
			$group_users   = LearndashFunctionOverrides::learndash_get_groups_users( $group_id, true );
			LearndashGroupsPostEditAdditions::update_group_seat_counts( $group_id, true, $group_users, $group_leaders );
			update_post_meta( $ld_group_id, 'reconciled_after_pool_seats', current_time( 'mysql' ) );

			return true;
			//}

			//return false;
		}

		if ( ! empty( get_post_meta( $group_id, '_ulgm_seats_before_pooling', true ) ) ) {
			$previous_count = get_post_meta( $group_id, '_ulgm_seats_before_pooling', true );
			$code_group_id  = ulgm()->group_management->seat->get_code_group_id( $group_id );
			LearndashGroupsPostEditAdditions::update_seat_count( $group_id, $code_group_id, $previous_count );
			LearndashGroupsPostEditAdditions::update_user_redeemed_seat_func( $group_id );
			delete_post_meta( $group_id, '_ulgm_seats_before_pooling' );
		}

		if ( ( $remaining_seats === $total_seats ) && $enrolled_seats > 1 ) {
			// remaining seats are equal to total seats but there are users in the group, reconcile!
			$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrators( $group_id, true );
			$group_users   = LearndashFunctionOverrides::learndash_get_groups_users( $group_id, true );
			LearndashGroupsPostEditAdditions::update_group_seat_counts( $group_id, true, $group_users, $group_leaders );

			return true;
		}

		if ( ( $remaining_seats !== $total_seats ) && ( $enrolled_seats + $remaining_seats ) !== $total_seats ) {
			$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrators( $group_id, true );
			$group_users   = LearndashFunctionOverrides::learndash_get_groups_users( $group_id, true );
			LearndashGroupsPostEditAdditions::update_group_seat_counts( $group_id, true, $group_users, $group_leaders );

			return true;
		}

		return false;
	}

	/**
	 *
	 */
	public static function set_populate_management_features() {

		self::$populate_management_features = true;
		foreach ( self::$ulgm_managed_group_objects as $object ) {
			if ( $object->ID === absint( self::$ulgm_current_managed_group_id ) ) {
				if ( $object->basic_group ) {
					self::$populate_management_features = false;
				}
				break;
			}
		}

		self::$populate_management_features = apply_filters( 'ulgm_populate_management_features', self::$populate_management_features, self::$ulgm_current_managed_group_id );
	}

	/**
	 * Filter and localize all text, then set as global for use in template file
	 *
	 * @since 1.0.0
	 */
	public static function localize_filter_globalize_text() {

		if ( ! empty( self::$ulgm_management_shortcode ) ) {
			return;
		}

		// Get current manged group's post object (id has been validated)
		$group_post = get_post( (int) self::$ulgm_current_managed_group_id );
		if ( ! $group_post instanceof \WP_Post ) {
			return;
		}
		// Page heading
		//$deleted_seats   = (int) ulgm()->group_management->seat->deleted_seats( $group_post->ID );
		$total_seats     = (int) ulgm()->group_management->seat->total_seats( $group_post->ID );
		$remaining_seats = (int) ulgm()->group_management->seat->remaining_seats( $group_post->ID );
		$enrolled_seats  = (int) ulgm()->group_management->count_users_enrolled_in_group( $group_post->ID ) + (int) ulgm()->group_management->users_invited_in_group( $group_post->ID );

		if ( self::is_reconcile_required( $group_post->ID, $total_seats, $enrolled_seats, $remaining_seats, true ) ) {
			$total_seats     = (int) ulgm()->group_management->seat->total_seats( $group_post->ID );
			$remaining_seats = (int) ulgm()->group_management->seat->remaining_seats( $group_post->ID );
			$enrolled_seats  = (int) ulgm()->group_management->count_users_enrolled_in_group( $group_post->ID ) + (int) ulgm()->group_management->users_invited_in_group( $group_post->ID );

		}
		$seats_label_singular = ulgm()->group_management->seat->get_per_seat_text( 1 );
		$user_label_singular  = get_option( 'ulgm_per_user_text', __( 'User', 'uncanny-learndash-groups' ) );
		$seats_label_plural   = ulgm()->group_management->seat->get_per_seat_text( 2 );
		$users_label_plural   = get_option( 'ulgm_per_user_text_plural', __( 'Users', 'uncanny-learndash-groups' ) );

		$total_seats_string     = sprintf( _nx( '%1$s Total %2$s', '%1$s Total %3$s', $total_seats, '%1$s is a number, %2$s is the singular "seat" label, %3$s is the plural "seats" label', 'uncanny-learndash-groups' ), number_format_i18n( $total_seats ), $seats_label_singular, $seats_label_plural );
		$remaining_seats_string = sprintf( _nx( '%1$s %2$s remaining', '%1$s %3$s remaining', $remaining_seats, '%1$s is a number, %2$s is the singular "seat" label, %3$s is the plural "seats" label', 'uncanny-learndash-groups' ), number_format_i18n( $remaining_seats ), $seats_label_singular, $seats_label_plural );
		$available_seats_string = sprintf( _nx( '%1$s %2$s available', '%1$s %3$s available', $remaining_seats, '%1$s is a number, %2$s is the singular "seat" label, %3$s is the plural "seats" label', 'uncanny-learndash-groups' ), number_format_i18n( $remaining_seats ), $seats_label_singular, $seats_label_plural );
		$enrolled_seats_string  = sprintf( _nx( '%1$s %2$s', '%1$s %3$s', $enrolled_seats, '%1$s is a number, %2$s is the singular "user" label, %3$s is the plural "users" label', 'uncanny-learndash-groups' ), number_format_i18n( $enrolled_seats ), $user_label_singular, $users_label_plural );

		self::$ulgm_management_shortcode['text']['group_title']           = $group_post->post_title;
		self::$ulgm_management_shortcode['text']['group_id_select_label'] = __( 'Select Group ID', 'uncanny-learndash-groups' );

		self::$ulgm_management_shortcode['text']['x_total_seats']     = $total_seats_string;
		self::$ulgm_management_shortcode['text']['x_seats_remaining'] = $remaining_seats_string;
		self::$ulgm_management_shortcode['text']['x_seats_available'] = $available_seats_string;
		self::$ulgm_management_shortcode['text']['x_users_enrolled']  = $enrolled_seats_string;

		//Enrolled Users
		self::$ulgm_management_shortcode['text']['enrolled_users'] = __( 'Enrolled users', 'uncanny-learndash-groups' );

		// Enrolled user table headers
		self::$ulgm_management_shortcode['table']['enrolled_users']['headers'][0] = array(
			'title' => __( 'First name', 'uncanny-learndash-groups' ),
			'slug'  => 'first-name',
		);
		self::$ulgm_management_shortcode['table']['enrolled_users']['headers'][1] = array(
			'title' => __( 'Last name', 'uncanny-learndash-groups' ),
			'slug'  => 'last-name',
		);
		self::$ulgm_management_shortcode['table']['enrolled_users']['headers'][2] = array(
			'title' => __( 'Email', 'uncanny-learndash-groups' ),
			'slug'  => 'email',
		);
		self::$ulgm_management_shortcode['table']['enrolled_users']['headers'][3] = array(
			'title' => __( 'Status', 'uncanny-learndash-groups' ),
			'slug'  => 'status',
		);
		self::$ulgm_management_shortcode['table']['enrolled_users']['headers'][4] = array(
			'title' => __( 'Key', 'uncanny-learndash-groups' ),
			'slug'  => 'key',
		);

		// Enrolled user buttons
		self::$ulgm_management_shortcode['text']['add_user']                          = __( 'Users', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['upload_users']                      = __( 'Upload users', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['email_users']                       = __( 'Email users', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['progress_management_report_button'] = __( 'Manage progress', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['modal_x_seats_remaining']           = sprintf( _x( 'You have %1$s %2$s remaining', '%1$s is a number, %2$s is the "seats" label', 'uncanny-learndash-groups' ), ulgm()->group_management->seat->remaining_seats( $group_post->ID ), strtolower( get_option( 'ulgm_per_seat_text_plural', 'seats' ) ) );
		if ( 'yes' === get_option( 'ulgm_send_user_welcome_email', 'yes' ) && 'yes' === get_option( 'ulgm_send_existing_user_welcome_email', 'yes' ) ) {
			self::$ulgm_management_shortcode['text']['add_invite_users'] = __( 'Add and invite users', 'uncanny-learndash-groups' );
			self::$ulgm_management_shortcode['text']['add_invite_user']  = __( 'Add and invite user', 'uncanny-learndash-groups' );
		} else {
			self::$ulgm_management_shortcode['text']['add_invite_users'] = __( 'Add users', 'uncanny-learndash-groups' );
			self::$ulgm_management_shortcode['text']['add_invite_user']  = __( 'Add user', 'uncanny-learndash-groups' );
		}
		self::$ulgm_management_shortcode['text']['send_enrollment_key']  = __( 'Send enrollment key', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['add_existing_user']    = __( 'Add existing user', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['send_enrollment_keys'] = __( 'Send enrollment keys', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['do_not_send_emails']   = __( 'Suppress email (keys to be manually distributed)', 'uncanny-learndash-groups' );

		self::$ulgm_management_shortcode['text']['group_progress_link'] = SharedFunctions::get_group_report_page_id( true );
		self::$ulgm_management_shortcode['text']['group_progress']      = sprintf( __( '%s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );

		self::$ulgm_management_shortcode['text']['group_progress_management_link'] = SharedFunctions::get_group_manage_progress_report_page_id( true );
		self::$ulgm_management_shortcode['text']['group_progress_management']      = __( 'Progress', 'uncanny-learndash-groups' );

		self::$ulgm_management_shortcode['text']['group_quiz_progress_link'] = SharedFunctions::get_group_quiz_report_page_id( true );
		self::$ulgm_management_shortcode['text']['group_quiz_progress']      = sprintf( __( '%s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quiz' ) );

		self::$ulgm_management_shortcode['text']['group_assignment_link'] = SharedFunctions::get_group_assignment_report_page_id( true );
		self::$ulgm_management_shortcode['text']['group_assignment_page'] = __( 'Assignments', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['group_essay_link']      = SharedFunctions::get_group_essay_report_page_id( true );
		self::$ulgm_management_shortcode['text']['group_essay_page']      = __( 'Essays', 'uncanny-learndash-groups' );

		if ( Utilities::if_woocommerce_active() ) {
			//self::$ulgm_management_shortcode['text']['add_seats_link']   = SharedFunctions::add_group_seats_link( $group_post->ID, ulgm()->group_management->seat->total_seats( $group_post->ID ) + 1 );
			self::$ulgm_management_shortcode['text']['add_seats_link']   = SharedFunctions::add_group_seats_link( $group_post->ID, 1 );
			self::$ulgm_management_shortcode['text']['add_seats']        = __( 'Add seats', 'uncanny-learndash-groups' );
			self::$ulgm_management_shortcode['text']['buy_courses_link'] = SharedFunctions::add_buy_courses_link( $group_post->ID );
		}
		self::$ulgm_management_shortcode['text']['buy_courses']         = sprintf( __( 'Add %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );
		self::$ulgm_management_shortcode['text']['administration_link'] = admin_url();
		self::$ulgm_management_shortcode['text']['administration']      = __( 'Administration', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['remove_users']        = __( 'Remove user(s)', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['resend_invitation']   = __( 'Resend invitation', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['copy_key']            = __( 'Copy key', 'uncanny-learndash-groups' );

		// Groups leader headers
		self::$ulgm_management_shortcode['text']['group_leaders'] = __( 'Group leaders', 'uncanny-learndash-groups' );

		// Group Leaders buttons
		self::$ulgm_management_shortcode['text']['add_leader']          = __( 'Add group leader', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['remove_group_leader'] = __( 'Remove group leader(s)', 'uncanny-learndash-groups' );

		// Group Leaders table headers
		self::$ulgm_management_shortcode['table']['group_leaders']['headers'][0] = array(
			'title' => __( 'First name', 'uncanny-learndash-groups' ),
			'slug'  => 'first-name',
		);
		self::$ulgm_management_shortcode['table']['group_leaders']['headers'][1] = array(
			'title' => __( 'Last name', 'uncanny-learndash-groups' ),
			'slug'  => 'last-name',
		);
		self::$ulgm_management_shortcode['table']['group_leaders']['headers'][2] = array(
			'title' => __( 'Email', 'uncanny-learndash-groups' ),
			'slug'  => 'email',
		);

		// Group Leaders buttons
		self::$ulgm_management_shortcode['text']['add_group_leader'] = __( 'Add group leader', 'uncanny-learndash-groups' );

		// File API Error messages
		self::$ulgm_management_shortcode['text']['file_api_is_not_supported'] = __( 'Error: File API is not supported. Update your browser.', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['select_a_csv.']             = __( 'Please select a CSV (.csv) file.', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['csv_file_error']            = __( 'CSV File Error', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['incompatible_format']       = __( 'An incompatible format was detected. Please see the KB for supported .csv formats.', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['not_contains_email']        = __( 'The first row of the CSV file must contain the column header user_email.', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['not_contains_first']        = __( 'The first row of the CSV file must contain the column header first_name.', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['not_contains_last']         = __( 'The first row of the CSV file must contain the column header last_name.', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['must_contain_email']        = __( 'Each row in the CSV must contain a user_email value.', 'uncanny-learndash-groups' );
		self::$ulgm_management_shortcode['text']['choose_file']               = __( 'Please choose a file to upload.', 'uncanny-learndash-groups' );

		// Message is set by a redirect in the JS file
		if ( ulgm_filter_has_var( 'message' ) ) {
			self::$ulgm_management_shortcode['message'] = esc_html( wp_kses( ulgm_filter_input( 'message' ), array() ) );
		} else {
			self::$ulgm_management_shortcode['message'] = '';
		}

		self::$ulgm_management_shortcode = apply_filters( 'ulgm_management_shortcode', self::$ulgm_management_shortcode );

	}

	/**
	 * @return array
	 */
	private static function get_frontend_localized_strings() {

		$localized_strings = array_merge( array(), Utilities::i18n_datatable_strings() );

		/* DataTable */
		$localized_strings['searchPlaceholder'] = __( 'Search by name, email, status or key', 'uncanny-learndash-groups' );

		return $localized_strings;
	}

	/**
	 * @return mixed|void
	 */
	public static function set_enrolled_users_columns() {
		$array = array();

		$array[] = (object) array(
			'data'    => 'first_name',
			'targets' => 'first_name',
			'title'   => __( 'First name', 'uncanny-learndash-groups' ),
			'type'    => 'uo-groups-anchor',
		);

		$array[] = (object) array(
			'data'    => 'last_name',
			'targets' => 'last_name',
			'title'   => __( 'Last name', 'uncanny-learndash-groups' ),
			'type'    => 'uo-groups-anchor',
		);

		$array[] = (object) array(
			'data'    => 'email',
			'targets' => 'email',
			'title'   => __( 'Email', 'uncanny-learndash-groups' ),
		);

		$array[] = (object) array(
			'data'    => 'status',
			'targets' => 'status',
			'title'   => __( 'Status', 'uncanny-learndash-groups' ),
		);

		$array[] = (object) array(
			'data'    => 'key',
			'targets' => 'key',
			'title'   => __( 'Key', 'uncanny-learndash-groups' ),
			'type'    => 'uo-groups-anchor',
		);
		$array   = apply_filters( 'ulgm_user_data_columns', $array );

		return $array;
	}

	/**
	 * @return mixed|void
	 */
	public static function set_group_leader_columns() {
		$array = array();

		$array[] = (object) array(
			'data'    => 'first_name',
			'targets' => 'first_name',
			'title'   => __( 'First name', 'uncanny-learndash-groups' ),
			'type'    => 'uo-groups-anchor',
		);

		$array[] = (object) array(
			'data'    => 'last_name',
			'targets' => 'last_name',
			'title'   => __( 'Last name', 'uncanny-learndash-groups' ),
			'type'    => 'uo-groups-anchor',
		);

		$array[] = (object) array(
			'data'    => 'email',
			'targets' => 'email',
			'title'   => __( 'Email', 'uncanny-learndash-groups' ),
		);

		$array = apply_filters( 'ulgm_group_leader_columns', $array );

		// Loop thru array and assign
		// targets based on the position
		if ( $array ) {
			$j = 0;
			foreach ( $array as $k => $v ) {
				$v->targets = $j;
				$j ++;
			}
		}

		return $array;
	}

}
