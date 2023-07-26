<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class AdminMenu
 * @package uncanny_learndash_groups
 */
class GroupReportsInterface {

	/**
	 * The root path of the rest call
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $root_path = 'ulgm_management/v1';

	/**
	 * Collection of variabls that can be dumped for debug
	 *
	 * @since  1.0.0
	 * @access private
	 * @var
	 */
	private $raw_data_log = array();


	/**
	 * An array of localized and filtered strings that are used in templates
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	static $ulgm_reporting_shortcode = array();

	/**
	 * An array of localized and filtered strings that are used in templates
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	static $transcript_page_url = '';

	/**
	 * Set the order of the course drop down
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	static $course_order = '';

	/**
	 * Set up the report for a specific user
	 *
	 * @since    1.0.0
	 * @access   static
	 * @var      array
	 */
	static $user_id = 0;

	/**
	 * Table columns (the columns of the table)
	 *
	 * @since  4.3.1
	 * @access static
	 * @var    array
	 */
	static $columns = array();

	/**
	 * Columns that will be visible in the course report
	 *
	 * @since    3.8
	 * @access   static
	 * @var      array
	 */
	static $table_columns = array();

	/**
	 * user course completion data
	 *
	 * @since    3.8
	 * @access   static
	 * @var      array
	 */
	static $user_course_completion_data = null;

	/**
	 * class constructor
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'uo_plugins_loaded' ), 99 );

		// Enqueue Scripts for uo_group_management shortcode
		add_action( 'wp_enqueue_scripts', array( $this, 'uo_group_management_scripts' ) );

		/* ADD FILTERS ACTIONS FUNCTION */
		add_shortcode( 'uo_groups_course_report', array( $this, 'uo_group_course_report' ) );

		//register api class
		add_action( 'rest_api_init', array( $this, 'reporting_api' ) );

	}

	/**
	 * Loads all scripts and styles required by the shortcode
	 *
	 * @since 1.0.0
	 */
	public function uo_group_management_scripts() {
		self::$columns = array(
			'user_name'           => __( 'Username', 'uncanny-learndash-groups' ),
			'first_name'          => __( 'First Name', 'uncanny-learndash-groups' ),
			'last_name'           => __( 'Last Name', 'uncanny-learndash-groups' ),
			'user_email'          => __( 'Email', 'uncanny-learndash-groups' ),
			'percent_completed'   => __( '% Complete', 'uncanny-learndash-groups' ),
			'date_completed'      => __( 'Date Completed', 'uncanny-learndash-groups' ),
			'date_enrolled'       => __( 'Date Enrolled', 'uncanny-learndash-groups' ),
			'course_name'         => sprintf( _x( '%s Name', 'LearnDash: Course Name', 'uncanny-learndash-groups' ), learndash_get_custom_label( 'course' ) ),
			'group_name'          => sprintf( _x( '%s Name', 'LearnDash: Group Name', 'uncanny-learndash-groups' ), learndash_get_custom_label( 'group' ) ),
			'transcript_page_url' => __( 'Transcript', 'uncanny-learndash-groups' ),
		);

		global $post;

		// Only add scripts if shortcode is present on page
		if ( Utilities::has_shortcode( $post, 'uo_groups_course_report' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-course-report' ) ) {
			self::enqueue_frontend_assets();
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
				Utilities::get_version()
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
				'root'         => esc_url_raw( rest_url() . 'ulgm_management/v1/' ),
				'nonce'        => \wp_create_nonce( 'wp_rest' ),
				'localized'    => self::get_frontend_localized_strings(),
				'i18n'         => array(
					'youCantDoThat' => __( "Sorry, you can't do that.", 'uncanny-learndash-groups' ),
					'CSV'           => __( 'CSV', 'uncanny-learndash-groups' ),
					'exportCSV'     => __( 'CSV export', 'uncanny-learndash-groups' ),
					'excel'         => __( 'Excel', 'uncanny-learndash-groups' ),
					'exportExcel'   => __( 'Excel export', 'uncanny-learndash-groups' ),
				),
				'tableColumns' => apply_filters(
					'ulgm_group_course_report_columns',
					self::$columns
				),
			);

			wp_localize_script( 'ulgm-frontend', 'ulgmRestApiSetup', $api_setup );
			wp_enqueue_script( 'ulgm-frontend' );

			// Load styles
			wp_register_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array( 'ulgm-datatables' ), Utilities::get_version() );
			$user_colors = Utilities::user_colors();
			wp_add_inline_style( 'ulgm-frontend', $user_colors );
			wp_enqueue_style( 'ulgm-frontend', $user_colors );

			// Load needed scripts
			wp_enqueue_script( 'ulgm-modernizr', Utilities::get_vendor( 'modernizr/js/modernizr.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
			wp_enqueue_script( 'ulgm-jquery-lazyload', Utilities::get_vendor( 'jquery-lazyload/js/jquery.lazyload.min.js' ), array( 'jquery' ), Utilities::get_version(), true );

			// Select2
			wp_enqueue_script( 'ulgm-select2', Utilities::get_vendor( 'select2/js/select2.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
			wp_enqueue_style( 'ulgm-select2', Utilities::get_vendor( 'select2/css/select2.min.css' ), array(), Utilities::get_version() );

			// Modal
			wp_enqueue_style( 'ulgm-modal', Utilities::get_vendor( 'jquery-modal/css/jquery.modal.css' ), array(), Utilities::get_version() );
		}
	}

	/**
	 * Return the HTML template that is displayed by the shortcode
	 *
	 * @param array $attributes The attributes passed in the the shortcode
	 * @param string $content The content contained by the shortcode
	 *
	 * @return string $shortcode_template The HTML template loaded
	 * @since 1.0.0
	 *
	 */
	public function uo_group_course_report( $attributes, $content = '' ) {

		$user_id = get_current_user_id();

		// Is the user logged in
		if ( ! $user_id ) {
			return __( 'Please log in to view the report.', 'uncanny-learndash-groups' );
		}

		$allowed_roles = apply_filters(
			'ulgm_gm_allowed_roles',
			array(
				'administrator',
				'group_leader',
				'ulgm_group_management',
			)
		);
		// Is the user a group leader
		if ( ! array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {
			return __( 'You must be a admin or group leader to access this page.', 'uncanny-learndash-groups' );
		}

		$request = shortcode_atts(
			array(
				'transcript-page-id' => 0,
				'course-order'       => '',
				'columns'            => 'user_name,first_name,last_name,user_email,percent_completed,date_completed,date_enrolled,course_name,group_name,transcript_page_url',

				'orderby_column'     => esc_attr__( 'Date Completed', 'uncanny-learndash-groups' ), // The ID of the column used to sort
				'order_column'       => 'desc', // Designates the ascending or descending order of the ‘orderby‘ parameter
			),
			$attributes
		);
		$request = apply_filters( 'ulgm_group_course_report_shortcode_attributes', $request );

		// Clean the transcript page ID
		$request['transcript-page-id'] = absint( $request['transcript-page-id'] );

		self::$course_order = $request['course-order'];

		if ( ulgm_filter_has_var( 'user-id' ) ) {
			self::$user_id = absint( ulgm_filter_input( 'user-id' ) );
		}

		// Columns that are available to be set in the table
		$allowed_columns = array(
			'user_name',
			'first_name',
			'last_name',
			'user_email',
			'percent_completed',
			'date_completed',
			'date_enrolled',
			'course_name',
			'group_name',
			'transcript_page_url',
		);

		// Set column visibility
		if ( isset( $request['columns'] ) && ! empty( $request['columns'] ) ) {

			// Columns that the shortcode requested to show
			$columns = explode( ',', $request['columns'] );
			$columns = array_filter( array_map( 'trim', $columns ) );

			if ( ! empty( $columns ) ) {
				foreach ( $columns as $column ) {
					if ( in_array( (string) $column, $allowed_columns, true ) ) {
						self::$table_columns[] = $column;
					}
				}
			}
		}

		if ( empty( self::$table_columns ) ) {
			self::$table_columns = $allowed_columns;
		}

		// Check if we have a valid post ID
		if ( ! empty( $request['transcript-page-id'] ) && $request['transcript-page-id'] !== 0 ) {
			// Get the permalink of the transcript
			self::$transcript_page_url = apply_filters(
				'group_reporting_transcript_url',
				get_permalink( $request['transcript-page-id'] ),
				$request['transcript-page-id']
			);
		}

		// Create object with inline data
		// We'll use this to extend ulgmRestApiSetup
		$js_extra_inline_data = array(
			'hasUserId'                 => ulgm_filter_has_var( 'user-id' ),
			'hasTranscriptId'           => ! empty( $request['transcript-page-id'] ),
			'transcriptPageURL'         => self::$transcript_page_url,

			'table'                     => array(
				'orderBy' => $request['orderby_column'], // The ID of the column used to sort
				'order'   => $request['order_column'], // Designates the ascending or descending order of the ‘orderby‘ parameter
			),

			// Check if we have to add the "Transcript" column
			'shouldAddTranscriptColumn' => ! empty( self::$transcript_page_url ),
		);

		$this->localize_filter_globalize_text();

		ob_start();

		?>

		<script>

		// Check if the main object is defined
		const ulgmCourseReportShortcode = <?php echo json_encode( $js_extra_inline_data, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS ); ?>;

		</script>

		<?php

		include Utilities::get_template( 'frontend-uo_groups_course_report.php' );

		return ob_get_clean();

	}

	/*
	 * Register rest api endpoints
	 *
	 */
	/**
	 *
	 */
	public function reporting_api() {

		register_rest_route(
			$this->root_path,
			'/get_group_courses/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'get_group_courses' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);

		register_rest_route(
			$this->root_path,
			'/get_user_course_data/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'get_user_course_data' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);

	}

	/*
	 * Get group of courses related to a LD Group
	 */
	/**
	 *
	 */
	public static function get_group_courses() {

		// Actions permitted by the pi call (colleced from input element with name action )
		$permitted_actions = array( 'get-courses' );

		// Was an action received, and is the actions allowed
		if ( ulgm_filter_has_var( 'action', INPUT_POST ) && in_array( ulgm_filter_input( 'action', INPUT_POST ), $permitted_actions ) ) {

			$action = (string) ulgm_filter_input( 'action', INPUT_POST );

		} else {
			$action          = '';
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$permission = apply_filters( 'group_management_add_user_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to add users.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		$group_id = 0;

		// Was group id received
		if ( ulgm_filter_has_var( 'group-id', INPUT_POST ) ) {

			// is group a valid integer
			if ( ! absint( ulgm_filter_input( 'group-id', INPUT_POST ) ) ) {
				$data['message'] = __( 'Group ID must be a whole number.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$group_leader_id = get_current_user_id();

			$is_hierarchy_setting_enabled = false;
			if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
				$is_hierarchy_setting_enabled = true;
			}
			$is_hierarchy_setting_enabled = apply_filters(
				'ulgm_is_hierarchy_setting_enabled',
				$is_hierarchy_setting_enabled,
				$group_id,
				false,
				false
			);

			if ( $is_hierarchy_setting_enabled ) {
				$user_group_ids = learndash_get_administrators_group_ids( $group_leader_id );
			} else {
				$user_group_ids = LearndashFunctionOverrides::learndash_get_administrators_group_ids( $group_leader_id );
			}

			// is the current user able to administer this group
			if ( ! in_array( ulgm_filter_input( 'group-id', INPUT_POST ), $user_group_ids ) && ! current_user_can( 'administrator' ) ) {
				$data['message'] = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-group-id';
				wp_send_json_error( $data );
			}

			$group_id = absint( ulgm_filter_input( 'group-id', INPUT_POST ) );

		} else {
			$data['message'] = __( 'Group ID was not received. Reload page and try again.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		// send back group courses
		if ( 'get-courses' === $action ) {

			// Add the user and send out a welcome email
			$group_course_ids = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id, true );

			if ( empty( $group_course_ids ) ) {
				$data['message'] = sprintf( __( 'This group does not have any %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );
				wp_send_json_error( $data );
			}

			$posts_in = array_map( 'intval', $group_course_ids );

			$course_order = '';
			if ( ulgm_filter_has_var( 'course-order', INPUT_POST ) ) {
				$course_order = (string) ulgm_filter_input( 'course-order', INPUT_POST );
			}

			if ( empty( $course_order ) ) {
				$course_order = 'title';
			}

			if ( ! in_array( $course_order, array( 'ID', 'title', 'date', 'menu_order' ) ) ) {
				$course_order = 'title';
			}

			$args = array(
				'post_type'        => 'sfwd-courses',
				'post__in'         => $posts_in,
				'orderby'          => $course_order,
				'order'            => 'ASC',
				'posts_per_page'   => - 1,
				'suppress_filters' => true,
			);

			$courses = new \WP_Query( $args );

			$data['reload']        = false;
			$data['call_function'] = 'populateCoursesDropDown';
			$data['function_vars'] = array(
				'group_courses' => $courses->posts,
				'group_id'      => $group_id,
			);

			$data = apply_filters( 'ulgm_rest_api_get_group_courses', $data, $_POST );

			wp_send_json_success( $data );

		}
	}

	/**
	 *
	 * Get data for courses
	 *
	 * @since 4.0.0 Added 'progress_num' parameter to shortcode atts to bypass pagination limits.
	 */
	public function get_user_course_data() {

		// Actions permitted by the pi call (collected from input element with name action ).
		$permitted_actions = array( 'get-user-data-courses', 'get-user-data-courses-single-user' );

		// Was an action received, and is the actions allowed
		if ( ulgm_filter_has_var( 'action', INPUT_POST ) && in_array( ulgm_filter_input( 'action', INPUT_POST ), $permitted_actions ) ) {

			$action = (string) ulgm_filter_input( 'action', INPUT_POST );

		} else {
			$action          = '';
			$data['message'] = __( 'Select an action.', 'uncanny-learndash-groups' );
			$data['testing'] = $_POST;
			wp_send_json_error( $data );
		}

		// Does the current user have permission
		$permission = apply_filters( 'group_management_add_user_permission', 'group_leader' );
		if ( ! current_user_can( $permission ) && ! current_user_can( 'manage_options' ) && ! current_user_can( 'ulgm_group_management' ) ) {
			$data['message'] = __( 'You do not have permission to add users.', 'uncanny-learndash-groups' );
			wp_send_json_error( $data );
		}

		$user_course_data = array();

		if ( 'get-user-data-courses' === $action ) {

			// is group a valid integer
			if ( ! absint( ulgm_filter_input( 'course-group-id', INPUT_POST ) ) && 'all' !== ulgm_filter_input( 'course-group-id', INPUT_POST ) ) {
				$data['message'] = sprintf( __( '%s ID must be a whole number.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );
				$data['error']   = 'invalid-course-id';
				wp_send_json_error( $data );
			}

			$group_id = absint( ulgm_filter_input( 'course-group-id', INPUT_POST ) );

			// get group courses
			$group_course_ids = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id );

			// is the current user able to administer this group
			if ( 'all' !== ulgm_filter_input( 'course-id', INPUT_POST ) && ! in_array( ulgm_filter_input( 'course-id', INPUT_POST ), $group_course_ids ) ) {
				$data['message'] = __( 'This course is not part of this group.', 'uncanny-learndash-groups' );
				$data['error']   = 'invalid-course-id';
				wp_send_json_error( $data );
			}

			$course_id = absint( ulgm_filter_input( 'course-id', INPUT_POST ) );

			$user_course_data = self::user_course_data( $course_id, $group_id );

		} elseif ( 'get-user-data-courses-single-user' === (string) $action ) {
			if ( ! ulgm_filter_has_var( 'user_id', INPUT_POST ) || ! absint( ulgm_filter_input( 'user_id', INPUT_POST ) ) ) {
				$data['message'] = __( 'User ID was not received. Reload page and try again.', 'uncanny-learndash-groups' );
				wp_send_json_error( $data );
			}

			$user_id          = absint( ulgm_filter_input( 'user_id', INPUT_POST ) );
			$group_leader_id  = get_current_user_id();
			$leader_group_ids = learndash_get_administrators_group_ids( $group_leader_id );
			$user_group_ids   = learndash_get_users_group_ids( $user_id, true );
			$user_group_ids   = array_intersect( $leader_group_ids, $user_group_ids );
			$group_course_ids = LearndashFunctionOverrides::learndash_get_groups_courses_ids( $user_group_ids, $group_leader_id, true );
			// is the current user able to administer this group
			if ( ! in_array( ulgm_filter_input( 'course-id', INPUT_POST ), $group_course_ids ) && 'all' !== ulgm_filter_input( 'course-id', INPUT_POST ) ) {
				$data['message'] = sprintf( __( 'This %s is not part of this group.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );
				$data['error']   = 'invalid-course-id';
				wp_send_json_error( $data );
			}

			$courses_collected = array();
			foreach ( $user_group_ids as $group_id ) {

				$courses = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id );
				foreach ( $courses as $course ) {
					if ( isset( $courses_collected[ $course ] ) ) {
						continue;
					}
					$courses_collected[ $course ] = self::user_course_data( $course, $group_id, $user_id );
					if ( isset( $courses_collected[ $course ][0] ) ) {
						$user_course_data[] = $courses_collected[ $course ][0];
					}
				}
			}
		}

		//$this->raw_data_log['$user_course_data'] = $user_course_data;
		$data['testing']       = false;
		$data['reload']        = false;
		$data['call_function'] = 'populateReportTable';
		$data['function_vars'] = array(
			'user_course_data' => $user_course_data,
		);

		$data = apply_filters( 'ulgm_rest_api_get_user_course_data', $data, $_POST );
		wp_send_json_success( $data );

	}

	/**
	 * @param $course_id
	 * @param $group_id
	 * @param false $user_id
	 *
	 * @return array
	 */
	public static function user_course_data( $course_id, $group_id, $user_id = false ) {

		$user_course_data = array();
		// Get general user data
		$start     = 0;
		$length    = 99999;
		$direction = 'asc';
		$order_by  = 'first_name';
		$search    = '';

		$args = array(
			'start'   => $start,
			'length'  => $length,
			'order'   => $direction,
			'orderby' => $order_by,
			'search'  => $search,
		);

		$groups_user_object = LearndashFunctionOverrides::ulgm_get_group_users( $group_id, $args, $user_id );

		if ( empty( $groups_user_object ) ) {
			$data['message'] = __( 'There are no users in this group', 'uncanny-learndash-groups' );
			$data['error']   = 'no-group-users';

			wp_send_json_error( $data );
		}

		global $wpdb;

		// Completion
		$q_completions = "
							SELECT user_id, activity_completed
							FROM {$wpdb->prefix}learndash_user_activity
							WHERE activity_type = 'course'
							AND activity_completed IS NOT NULL
							AND activity_completed <> 0
							AND course_id = $course_id
							";

		if ( false !== $user_id && absint( $user_id ) ) {
			$q_completions .= " AND user_id = $user_id";
		}

		$completions = $wpdb->get_results( $q_completions );

		$completions_rearranged = array();

		foreach ( $completions as $completion ) {
			$completions_rearranged[ (int) $completion->user_id ] = (int) $completion->activity_completed;
		}

		$percent_complete_link = '';

		$course_title = get_the_title( $course_id );
		$group_title  = get_the_title( $group_id );
		// Join all data
		foreach ( $groups_user_object as $user ) {
			$user_id                  = $user->ID;
			$first_name               = $user->first_name;
			$last_name                = $user->last_name;
			$user_email               = $user->user_email;
			$user_name                = $user->user_login;
			$date_completed_timestamp = isset( $completions_rearranged[ $user_id ] ) ? $completions_rearranged[ $user_id ] : '';
			$date_completed           = ! empty( $date_completed_timestamp ) ? learndash_adjust_date_time_display( $date_completed_timestamp ) : null;
			$date_completed           = apply_filters( 'ulgm_group_course_report_user_date_completed', $date_completed, $user, $course_id );
			$date_completed           = ! empty( $date_completed ) ? '<span class="ulg-hidden-data" style="display: none;">' . $date_completed_timestamp . '</span>' . $date_completed : '';
			//          $date_completed    = isset( $completions_rearranged[ (int) $user->ID ] ) ? learndash_adjust_date_time_display( $completions_rearranged[ (int) $user->ID ] ) : null;
			//          $date_completed    = apply_filters( 'ulgm_group_course_report_user_date_completed', $date_completed, $user, $course_id );
			$percent_completed = self::get_course_completion_percentage( $user->ID, $course_id ) . '%';

			if ( ! empty( $percent_complete_link ) ) {
				$percent_completed = '<a href="' . $percent_complete_link . '?user_id=' . $user_id . '&group-id=' . GroupManagementInterface::$ulgm_current_managed_group_id . '" target="_blank">' . $percent_completed . '</a>';
			}
			$courses_access_from = ld_course_access_from( $course_id, $user_id );
			// If the course_id + user_id is not set we check the group courses.
			if ( empty( $courses_access_from ) ) {
				$courses_access_from = learndash_user_group_enrolled_to_course_from( $user_id, $course_id );
			}

			if ( ! empty( $courses_access_from ) ) {
				$courses_access_from = '<span class="ulg-hidden-data" style="display: none;">' . $courses_access_from . '</span>' . learndash_adjust_date_time_display( $courses_access_from );
			}
			$user_course_data[] = apply_filters(
				'ulgm_group_course_report_user_data',
				array(
					'user_id'                  => $user_id,
					'first_name'               => SharedFunctions::remove_special_character( $first_name ),
					'last_name'                => SharedFunctions::remove_special_character( $last_name ),
					'user_email'               => $user_email,
					'user_name'                => $user_name,
					'date_completed'           => $date_completed,
					'date_completed_timestamp' => $date_completed_timestamp,
					'percent_completed'        => $percent_completed,
					'course_name'              => $course_title,
					'group_name'               => $group_title,
					'date_enrolled'            => $courses_access_from,
					'transcript_page_url'      => self::$transcript_page_url,
				),
				$user_id,
				$group_id,
				$course_id
			);
		}

		return apply_filters( 'ulgm_group_course_report_user_course_data', $user_course_data, $groups_user_object, $course_id );
	}

	/**
	 * @param $user_id
	 * @param $course_id
	 *
	 * @return int|mixed
	 */
	public static function get_course_completion_percentage( $user_id, $course_id ) {
		if ( version_compare( LEARNDASH_VERSION, '3.4.0', '>=' ) ) {
			return self::get_course_completion_percentage_v4( $user_id, $course_id );
		}
		if ( null !== self::$user_course_completion_data ) {
			if ( isset( self::$user_course_completion_data[ $user_id ][ $course_id ] ) ) {
				return self::$user_course_completion_data[ $user_id ][ $course_id ];
			}
		}

		self::$user_course_completion_data = array();

		global $wpdb;
		$user_data = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE user_id = %d AND meta_key = %s", $user_id, '_sfwd-course_progress' ) );

		foreach ( $user_data as $data ) {
			$progress = unserialize( $data->meta_value );
			if ( ! empty( $progress ) && ! empty( $progress[ $course_id ] ) && ! empty( $progress[ $course_id ]['total'] ) ) {
				$completed = absint( $progress[ $course_id ]['completed'] );
				$total     = absint( $progress[ $course_id ]['total'] );

				if ( $total > 0 ) {
					$percentage                                                  = intval( $completed * 100 / $total );
					$percentage                                                  = ( $percentage > 100 ) ? 100 : $percentage;
					self::$user_course_completion_data[ $user_id ][ $course_id ] = $percentage;
				}
			} else {
				self::$user_course_completion_data[ $user_id ][ $course_id ] = 0;
			}
		}

		if ( isset( self::$user_course_completion_data[ $user_id ][ $course_id ] ) ) {
			return self::$user_course_completion_data[ $user_id ][ $course_id ];
		}

		return 0;
	}

	/**
	 * @param $user_id
	 * @param $course_id
	 *
	 * @return int|mixed
	 */
	public static function get_course_completion_percentage_v4( $user_id, $course_id ) {
		//conditionally use is LD > 3.4.0
		if ( null !== self::$user_course_completion_data ) {
			if ( isset( self::$user_course_completion_data[ $user_id ][ $course_id ] ) ) {
				return self::$user_course_completion_data[ $user_id ][ $course_id ];
			}
		}

		self::$user_course_completion_data = array();
		$_user_id                          = $user_id;
		//$progress                          = learndash_user_get_course_progress( $_user_id, $course_id, 'summary' );
		$progress = (array) learndash_course_progress(
			array(
				'user_id'   => $_user_id,
				'course_id' => $course_id,
				'array'     => true,
			)
		);
		if ( ! empty( $progress ) ) {
			$completed = absint( $progress['completed'] );
			$total     = absint( $progress['total'] );
			$status    = $progress['status'];
			if ( $total > 0 ) {

				if ( ! isset( $progress['percentage'] ) ) {
					$progress['percentage'] = 0;
					if ( absint( $progress['completed'] ) && absint( $progress['total'] ) ) {
						$progress['percentage'] = number_format( ( ( $progress['completed'] / $progress['total'] ) * 100 ), 0 );
					}
				}

				$percentage                                                  = $progress['percentage'];
				$percentage                                                  = ( $percentage > 100 ) ? 100 : $percentage;
				self::$user_course_completion_data[ $user_id ][ $course_id ] = $percentage;
			} elseif ( 'completed' === $status ) {
				self::$user_course_completion_data[ $user_id ][ $course_id ] = 100;
			}
		} else {
			self::$user_course_completion_data[ $user_id ][ $course_id ] = 0;
		}
		//}

		if ( isset( self::$user_course_completion_data[ $user_id ][ $course_id ] ) ) {
			return self::$user_course_completion_data[ $user_id ][ $course_id ];
		}

		return 0;
	}

	/**
	 * Collect the highest quiz results and averages
	 *
	 * @param int $course_id
	 *
	 * @return array $user_quiz_data
	 */
	private function get_average_quiz_result( $course_id, $group_user_ids_string ) {

		global $wpdb;

		// Get all course quiz
		$args = array(
			'post_type'   => 'sfwd-quiz',
			'post_status' => 'publish',
			'meta_key'    => 'course_id',
			'meta_value'  => $course_id,
		);

		$course_quiz_list = new \WP_Query( $args );

		// Collect all user ids as array
		$course_quiz_list_ids = wp_list_pluck( $course_quiz_list->posts, 'ID' );
		//$this->raw_data_log['$course_quiz_list_ids'] = $course_quiz_list_ids;

		// Get all group's users quiz data
		// All variables are escaped and validated, prepare not needed
		$quiz_data_query = "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE user_id IN ($group_user_ids_string) AND meta_key = '_sfwd-quizzes'";
		$quiz_data       = $wpdb->get_results( $quiz_data_query );
		//$this->raw_data_log['$quiz_data_query'] = $quiz_data_query;
		//$this->raw_data_log['$quiz_data']       = $quiz_data;

		// Store collected quiz percentages and averages
		$user_quiz_data = array();

		// Loop through all user's quiz results
		foreach ( $quiz_data as $user_data ) {

			// Set User ID
			$user_id = $user_data->user_id;

			// Create array of all quiz attempts for the user
			$all_quiz_data = maybe_unserialize( $user_data->meta_value );

			// Loop through all user's quizzes
			foreach ( $all_quiz_data as $quiz_data ) {

				// Validate that the quiz is in the current course
				if ( in_array( (int) $quiz_data['quiz'], $course_quiz_list_ids ) ) {

					// Validate that if there is quiz percentage set for the quiz based on the user
					if ( isset( $user_quiz_data[ $user_id ]['quiz_percentage'][ (int) $quiz_data['quiz'] ] ) ) {

						// There is a percentage, we are calculating highest scores for each quiz, reset the quiz average if this quiz attempt is higher
						if ( $user_quiz_data[ $user_id ]['quiz_percentage'][ (int) $quiz_data['quiz'] ] < $quiz_data['percentage'] ) {
							$user_quiz_data[ $user_id ]['quiz_percentage'][ (int) $quiz_data['quiz'] ] = $quiz_data['percentage'];
						}
					} else {
						// store the quiz percentage
						$user_quiz_data[ $user_id ]['quiz_percentage'][ (int) $quiz_data['quiz'] ] = $quiz_data['percentage'];
					}
				}
			}
		}

		// Calculate the quiz average for each user
		foreach ( $user_quiz_data as $user_id => $data ) {
			$scores                                     = $data['quiz_percentage'];
			$user_quiz_data[ $user_id ]['quiz_average'] = ceil( array_sum( $scores ) / count( $scores ) );
		}

		return $user_quiz_data;

	}

	/**
	 * Filter and localize all text, then set as global for use in template file
	 *
	 * @since 1.0.0
	 */
	public function localize_filter_globalize_text() {

		self::$ulgm_reporting_shortcode['text']['group_management_link'] = SharedFunctions::get_group_management_page_id( true );
		if ( isset( $_GET['group-id'] ) && ! empty( $_GET['group-id'] ) && 0 !== absint( $_GET['group-id'] ) && intval( '-1' ) !== $_GET['group-id'] ) {
			self::$ulgm_reporting_shortcode['text']['group_management_link'] = self::$ulgm_reporting_shortcode['text']['group_management_link'] . '?group-id=' . absint( $_GET['group-id'] );
		}
		self::$ulgm_reporting_shortcode['text']['group_management'] = __( 'Back to Group Management', 'uncanny-learndash-groups' );

	}

	/**
	 * @return mixed|void
	 */
	private static function get_frontend_localized_strings() {

		$localized_strings = array();

		$localized_strings['selectCourse']                  = sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );
		$localized_strings['noCourse']                      = sprintf( __( 'No %s Available', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );
		$localized_strings['customizeColumns']              = __( 'Customize columns', 'uncanny-learndash-groups' );
		$localized_strings['hideCustomizeColumns']          = __( 'Hide customize columns', 'uncanny-learndash-groups' );
		$localized_strings['details']                       = __( 'Details', 'uncanny-learndash-groups' );
		$localized_strings['header']['user_name']           = __( 'Username', 'uncanny-learndash-groups' );
		$localized_strings['header']['first_name']          = __( 'First Name', 'uncanny-learndash-groups' );
		$localized_strings['header']['last_name']           = __( 'Last Name', 'uncanny-learndash-groups' );
		$localized_strings['header']['user_email']          = __( 'Email', 'uncanny-learndash-groups' );
		$localized_strings['header']['percent_completed']   = __( '% Complete', 'uncanny-learndash-groups' );
		$localized_strings['header']['date_completed']      = __( 'Date Completed', 'uncanny-learndash-groups' );
		$localized_strings['header']['date_enrolled']       = __( 'Date Enrolled', 'uncanny-learndash-groups' );
		$localized_strings['header']['course_name']         = sprintf( _x( '%s Name', 'LearnDash: Course Name', 'uncanny-learndash-groups' ), learndash_get_custom_label( 'course' ) );
		$localized_strings['header']['group_name']          = sprintf( _x( '%s Name', 'LearnDash: Group Name', 'uncanny-learndash-groups' ), learndash_get_custom_label( 'group' ) );
		$localized_strings['header']['transcript_page_url'] = __( 'Transcript', 'uncanny-learndash-groups' );

		/* DataTable */
		$localized_strings                      = array_merge( $localized_strings, Utilities::i18n_datatable_strings() );
		$localized_strings['searchPlaceholder'] = __( 'Search by username, name, email or date', 'uncanny-learndash-groups' );

		$localized_strings = apply_filters_deprecated( 'group-report-table-strings', array( $localized_strings ), '4.3.1', 'ulgm_group_course_report_table_strings' );
		$localized_strings = apply_filters( 'ulgm_group_course_report_table_strings', $localized_strings );

		return $localized_strings;
	}

	public function uo_plugins_loaded() {

		self::$columns = array(
			'user_name'           => __( 'Username', 'uncanny-learndash-groups' ),
			'first_name'          => __( 'First Name', 'uncanny-learndash-groups' ),
			'last_name'           => __( 'Last Name', 'uncanny-learndash-groups' ),
			'user_email'          => __( 'Email', 'uncanny-learndash-groups' ),
			'percent_completed'   => __( '% Complete', 'uncanny-learndash-groups' ),
			'date_completed'      => __( 'Date Completed', 'uncanny-learndash-groups' ),
			'date_enrolled'       => __( 'Date Enrolled', 'uncanny-learndash-groups' ),
			'course_name'         => sprintf( _x( '%s Name', 'LearnDash: Course Name', 'uncanny-learndash-groups' ), learndash_get_custom_label( 'course' ) ),
			'group_name'          => sprintf( _x( '%s Name', 'LearnDash: Group Name', 'uncanny-learndash-groups' ), learndash_get_custom_label( 'group' ) ),
			'transcript_page_url' => __( 'Transcript', 'uncanny-learndash-groups' ),
		);
	}
}
