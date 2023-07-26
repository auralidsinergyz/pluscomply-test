<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * ImportUsers
 * @package uncanny_pro_toolkit
 */
class ImportLearndashUsersFromCsv extends toolkit\Config implements toolkit\RequiredFunctions {

	static $log_error = false;
	static $module_name = 'Import Users';
	static $module_key = 'learndash-toolkit-import-user';
	static $option_key = 'learndash-toolkit-import-user';
	static $capability = 'manage_options';
	static $version = '0.0.1';
	static $is_module_menu;
	static $template;
	static $email_title = 'User Registration Completed';
	static $email_template;

	static $csv_header;

	// WP_Users Attr
	static $registered_columns = array(
		'user_pass'     => 'Password',
		'user_nicename' => 'Nice Name',
		'user_url'      => 'URL',
		'display_name'  => 'Display Name',
		'nickname'      => 'Nick Name',
		'first_name'    => 'First Name',
		'last_name'     => 'Last Name',
		'description'   => 'Description',
	);

	// Required
	static $required_columns = array(
		'user_login' => 'User Login',
		'user_email' => 'Email',
	);

	// Extra
	static $extra_columns = array(
		'learndash_group'   => 'LearnDash Group(s)',
		'learndash_courses' => 'LearnDash Course(s)',
		'wp_role'           => 'Role',
	);

	/**
	 * Class constructor
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		self::$is_module_menu = ( ! empty( $_GET['page'] ) && $_GET['page'] == self::$module_key ) ? true : false;
		self::$template = self::get_template( 'admin-import-user/admin-import-users.php', dirname( dirname( __FILE__ ) ) . '/src' );
		self::$template = apply_filters( 'uo_admin_import_users_template', self::$template );
		self::$email_template = self::get_template( 'import-user-email.php', dirname( dirname( __FILE__ ) ) . '/src' );
		self::$email_template = apply_filters( 'uo_import_user_email_template', self::$email_template );

		add_action( 'plugins_loaded', array( __CLASS__, 'run_backend_hooks' ) );

		// Ajax Requests
		add_action( 'wp_ajax_Uncanny Toolkit Pro - Import Users : File Upload', array(
			__CLASS__,
			'ajax_file_upload'
		) );

		add_action( 'wp_ajax_Uncanny Toolkit Pro - Import Users : Options Form', array(
			__CLASS__,
			'ajax_option_checked'
		) );

		add_action( 'wp_ajax_Uncanny Toolkit Pro - Import Users : Test Email', array( __CLASS__, 'ajax_test_email' ) );

		add_action( 'wp_ajax_Uncanny Toolkit Pro - Import Users : Save Email', array( __CLASS__, 'ajax_save_email' ) );

		add_action( 'wp_ajax_Uncanny Toolkit Pro - Import Users : Perform Import', array(
			__CLASS__,
			'ajax_perform_import'
		) );
	}

	/**
	 * Initialize frontend actions and filters
	 */
	public static function run_backend_hooks() {

		self::$module_name = __( 'Import Users', 'uncanny-pro-toolkit' );

		self::$capability = apply_filters( 'toolkit_learndash_user_import_capability', self::$capability );

		// Admin Page on Users
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );


		if ( self::$is_module_menu ) {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Admin Page on Users
	 *
	 * This module needs file uploading and sorting, so the setting modal is not good
	 *
	 * @since 1.0.0
	 */
	public static function admin_menu() {
		add_users_page( self::$module_name, self::$module_name, self::$capability, self::$module_key, array(
			__CLASS__,
			'cb_admin_menu'
		) );

	}

	/**
	 * Get import user template file
	 */
	public static function cb_admin_menu() {
		include( self::$template );
	}

	/**
	 * Enqueue all scripts and styles
	 */
	public static function enqueue_scripts() {

		$plugin_base_url = plugins_url( basename( dirname( UO_FILE ) ) );

		$script_url = $plugin_base_url . '/src/assets/legacy/backend/js/import-user.js';
		$style_url = $plugin_base_url . '/src/assets/legacy/backend/css/import-user.css';

		wp_enqueue_script( self::$module_key, $script_url, array( 'jquery' ), self::$version );
		wp_enqueue_style( self::$module_key, $style_url, false, self::$version );

		// Main CSS file
		wp_enqueue_style( 'ult-admin', \uncanny_learndash_toolkit\Config::get_admin_css( 'style.css' ), array(), UNCANNY_TOOLKIT_VERSION );

		$translation_array = array(
			'max_upload_size' => wp_max_upload_size(),

			'err_upload_failed'   => esc_html__( 'Something went wrong!', 'uncanny-pro-toolkit' ),
			'err_required_file'   => esc_html__( 'Select the file!', 'uncanny-pro-toolkit' ),
			'err_max_upload'      => esc_html__( 'The file size is too big!', 'uncanny-pro-toolkit' ),
			'err_file_type'       => esc_html__( 'File type must be a csv!', 'uncanny-pro-toolkit' ),
			'err_required_fields' => esc_html__( 'Username and Email are required!', 'uncanny-pro-toolkit' ),

			'err_test_email_user_empty' => esc_html__( 'Test user ID is required!', 'uncanny-pro-toolkit' ),
		);

		wp_localize_script( self::$module_key, 'objString', $translation_array );

	}

	/**
	 * Ajax Process #1 : Validate CSV
	 *
	 * @since 1.0.0
	 */
	public static function ajax_file_upload() {

		if ( ! current_user_can( self::$capability ) ) {
			$data['error'] = 'You do not have permission to do this.';
			wp_send_json_error( $data );
		}

		// Get CSV from uploaded $_POST
		$csv_input = self::get_csv( $_POST['csv'] );

		// Store CSV for later use
		update_option( self::$option_key, $_POST['csv'] );

		// Remove all extra spaces
		$csv_input[0] = array_map( 'trim', $csv_input[0] );

		if ( 1 !== count( array_intersect( array( 'user_email' ), $csv_input[0] ) ) ) {

			$data['error'] = 'minimum_coulmns';
			wp_send_json_error( $data );
		} else {

			$data['validated_data'] = self::validate_data( $csv_input );
			wp_send_json_success( $data );
		}

		wp_die();
	}

	/**
	 * Validate CSV fields
	 *
	 * @param $csv_input
	 *
	 * @return mixed
	 */
	private static function validate_data( $csv_input ) {

		// The amount user are going to be updated or added
		$validate_data['total_rows'] = count( $csv_input ) - 1;

		$validate_data['emails'] = self::validate_email_addresses( $csv_input );

		// The learndash_courses column exists
		if ( in_array( 'learndash_courses', $csv_input[0] ) ) {
			$validate_data['courses'] = self::validate_learndash_courses( $csv_input );
		} else {
			// add an empty column
			$new_csv_input = [];
			foreach ( $csv_input as $key => $row ){
				if( $key === 0 ){
					$row[] = 'learndash_courses';
				} else {
					$row[] = '';
				}
				$new_csv_input[] = $row;
			}
			$validate_data['courses'] = self::validate_learndash_courses( $new_csv_input );
		}

		// The groups column exists
		if ( in_array( 'learndash_groups', $csv_input[0] ) ) {
			$validate_data['groups'] = self::validate_learndash_groups( $csv_input );
		} else {
			// add an empty column
			$new_csv_input = [];
			foreach ( $csv_input as $key => $row ){
				if( $key === 0 ){
					$row[] = 'learndash_groups';
				} else {
					$row[] = '';
				}
				$new_csv_input[] = $row;
			}
			$validate_data['groups'] = self::validate_learndash_groups( $new_csv_input );
		}

		return $validate_data;

	}

	/**
	 * Validate email addresses
	 *
	 * @param $csv_input
	 *
	 * @return array
	 */
	private static function validate_email_addresses( $csv_input ) {

		$validation = array(
			'new_emails'                => array(),
			'existing_emails'           => array(),
			'malformed_emails'          => array(),
			'import_existing_user_data' => get_option( 'uo_import_existing_user_data', 'update' )

		);

		// Get column number of user_email
		$user_email_column_key = array_search( 'user_email', $csv_input[0] );

		// Remove header from CSV and loop through all rows of data
		unset( $csv_input[0] );
		foreach ( $csv_input as $row_key => $row ) {

			// check if its a valid email
			$is_email = is_email( $row[ $user_email_column_key ] );
			if ( ! $is_email ) {

				$validation['malformed_emails'][ $row_key ] = $row[ $user_email_column_key ];
				continue;
			}

			// check if email exists, email_exists() return false or the match users ID
			$email_exists = email_exists( $row[ $user_email_column_key ] );
			if ( $email_exists ) {
				$validation['existing_emails'][ $row_key ] = array(
					'user_email' => $row[ $user_email_column_key ],
					'user_id'    => $email_exists,
					'edit_link'  => get_edit_user_link( $email_exists )
				);
				continue;
			}

			$validation['new_emails'][ $row_key ] = $row[ $user_email_column_key ];

		}

		return $validation;
	}

	/**
	 * Validate courses
	 *
	 * @param $csv_input
	 *
	 * @return array
	 */
	private static function validate_learndash_courses( $csv_input ) {

		$uo_import_existing_user_data = get_option( 'uo_import_existing_user_data', 'update' );
		$uo_import_enrol_in_courses   = get_option( 'uo_import_enrol_in_courses' );
		
		$validation = array( 'invalid_learndash_courses' => array() );

		// Get all course IDs
		global $wpdb;
		$post_type = 'sfwd-courses';

		$course_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s",
			$post_type
		) );

		// Get column numer of user_email
		$learndash_courses_column_key = array_search( 'learndash_courses', $csv_input[0] );

		// Remove header from CSV and loop through all rows of data
		unset( $csv_input[0] );
		foreach ( $csv_input as $row_key => $row ) {
			if ( ! empty( $row[ $learndash_courses_column_key ] ) ) {
				$csv_course_ids = array_map( 'intval', explode( ';', $row[ $learndash_courses_column_key ] ) );
			} else {
				$csv_course_ids = array_map( 'intval', explode( ',', $uo_import_enrol_in_courses ) );
				$row[ $learndash_courses_column_key ] = implode( ';', $csv_course_ids );
			}
			
			$invalid_ids    = array_diff( $csv_course_ids, $course_ids );

			if ( count( $invalid_ids ) ) {
				if ( 'update' === $uo_import_existing_user_data && '' === $row[ $learndash_courses_column_key ] ) {
					continue;
				}
				$validation['invalid_learndash_courses'][ $row_key ] = array(
					'invalid_ids'   => $invalid_ids,
					'available_ids' => $course_ids,
					'inputted_ids'  => $row[ $learndash_courses_column_key ]
				);
			}
		}

		return $validation;


	}

	/**
	 * Validate groups
	 *
	 * @param $csv_input
	 *
	 * @return array
	 */
	private static function validate_learndash_groups( $csv_input ) {

		$uo_import_existing_user_data = get_option( 'uo_import_existing_user_data', 'update' );
		$uo_import_add_to_group       = get_option( 'uo_import_add_to_group' );

		$validation = array(
			'invalid_learndash_groups' => array()

		);

		// Get all course IDs
		global $wpdb;
		$post_type = 'groups';

		$group_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE  post_type = %s",
			$post_type
		) );

		// Get column numer of user_email
		$learndash_groups_column_key = array_search( 'learndash_groups', $csv_input[0] );

		// Remove header from CSV and loop through all rows of data
		unset( $csv_input[0] );

		foreach ( $csv_input as $row_key => $row ) {
			if ( ! empty( $row[ $learndash_groups_column_key ] ) ) {
				$csv_group_ids = array_map( 'intval', explode( ';', $row[ $learndash_groups_column_key ] ) );
			} else {
				$csv_group_ids = array_map( 'intval', explode( ',', $uo_import_add_to_group ) );
				$row[ $learndash_groups_column_key ] = implode( ';', $csv_group_ids );
			}
			
			$invalid_ids   = array_diff( $csv_group_ids, $group_ids );

			if ( count( $invalid_ids ) ) {
				if ( 'update' === $uo_import_existing_user_data && '' === $row[ $learndash_groups_column_key ] ) {
					continue;
				}
				$validation['invalid_learndash_groups'][ $row_key ] = array(
					'invalid_ids'   => $invalid_ids,
					'available_ids' => $group_ids,
					'inputted_ids'  => $row[ $learndash_groups_column_key ]
				);
			}
		}

		return $validation;

	}

	/**
	 * AJAX save import options
	 */
	public static function ajax_option_checked() {

		if ( ! current_user_can( self::$capability ) ) {
			$data['error'] = 'You do not have permission to do this.';
			wp_send_json_error( $data );
		}

		$options = array();

		if ( isset( $_POST['uo_import_add_to_group'] ) ) {
			$options['uo_import_add_to_group'] = implode( ',', $_POST['uo_import_add_to_group'] );
		} else {
			$options['uo_import_add_to_group'] = '';
		}

		if ( isset( $_POST['uo_import_enrol_in_courses'] ) ) {
			$options['uo_import_enrol_in_courses'] = implode( ',', $_POST['uo_import_enrol_in_courses'] );
		} else {
			$options['uo_import_enrol_in_courses'] = '';
		}

		if ( isset( $_POST['uo_import_existing_user_data'] ) ) {
			$options['uo_import_existing_user_data'] = $_POST['uo_import_existing_user_data'];
		} else {
			$options['uo_import_existing_user_data'] = '';
		}

		if ( isset( $_POST['uo_import_set_roles'] ) ) {
			$options['uo_import_set_roles'] = $_POST['uo_import_set_roles'];
		} else {
			$options['uo_import_set_roles'] = '';
		}

		foreach ( $options as $meta_key => $meta_value ) {
			update_option( $meta_key, $meta_value );
		}

		//Testing $data['$_POST'] = $_POST;
		$data['message'] = esc_html__( 'Options are successfully saved.', 'uncanny-pro-toolkit' );
		//$data['$options'] = $options;

		wp_send_json_success( $data );

	}

	/**
	 * Ajax Process #2 : Option Checked & Create User
	 *
	 * @since 1.0.0
	 */
	public static function ajax_perform_import() {


		if ( ! current_user_can( self::$capability ) ) {
			$data['error'] = 'You do not have permission to do this.';
			wp_send_json_error( $data );
		}

		$csv_input = get_option( self::$option_key );

		// Get CSV from uploaded $_POST
		$csvArray   = self::get_csv( $csv_input );
		$csv_header = array_shift( $csvArray );
		$csv_header = array_map( 'trim', $csv_header );


		$status = get_option( 'user_import_status', 'starting' );

		// This is the first run ever OR the previous run has completed
		if ( 'starting' === $status || 'completed' === $status ) {

			// Reset the progress and start fresh
			update_option( 'user_import_total_rows', count( $csvArray ) );
			update_option( 'user_import_imported_rows', 0 );
			update_option( 'user_import_status', 'processing' );

		}

		$uo_import_users_send_new_user_email = ( get_option( 'uo_import_users_send_new_user_email', 'false' ) === 'true' ) ? true : false;

		$uo_import_users_new_user_email_subject = get_option( 'uo_import_users_new_user_email_subject', 'Your Account Has Been Created' );
		if ( '' === $uo_import_users_new_user_email_subject ) {
			$uo_import_users_new_user_email_subject = 'Your Account Has Been Created';
		}

		$uo_import_users_new_user_email_body = get_option( 'uo_import_users_new_user_email_body', 'Your new user account has been created at %Site URL%.' );
		if ( '' === $uo_import_users_new_user_email_body ) {
			$uo_import_users_new_user_email_body = 'Your new user account has been created at %Site URL%.';
		}

		$uo_import_users_send_updated_user_email = ( get_option( 'uo_import_users_send_updated_user_email', 'false' ) === 'true' ) ? true : false;

		$uo_import_users_updated_user_email_subject = get_option( 'uo_import_users_updated_user_email_subject', 'Your Account Has Been Updated' );
		if ( '' === $uo_import_users_updated_user_email_subject ) {
			$uo_import_users_updated_user_email_subject = 'Your Account Has Been Updated';
		}

		$uo_import_users_updated_user_email_body = get_option( 'uo_import_users_updated_user_email_body', 'Your new user account has been updated at %Site URL%.' );
		if ( '' === $uo_import_users_updated_user_email_body ) {
			$uo_import_users_updated_user_email_body = 'Your new user account has been updated at %Site URL%.';
		}

		$total_rows    = get_option( 'user_import_total_rows' );
		$imported_rows = get_option( 'user_import_imported_rows' );

		$status = get_option( 'user_import_status' );

		$data['total_rows'] = $total_rows;

		$key_location = self::get_key_location( $csv_header );

		$data['new_users']         = 0;
		$data['updated_users']     = 0;
		$data['emails_sent']       = 0;
		$data['rows_ignored']      = 0;
		$data['ignored_rows_data'] = array();

		$option_keys = array(
			'uo_import_add_to_group',
			'uo_import_enrol_in_courses',
			array( 'uo_import_existing_user_data', 'update' ),
			'uo_import_set_roles'
		);

		$options = array();

		foreach ( $option_keys as $meta_key ) {

			if ( is_array( $meta_key ) ) {
				$option = get_option( $meta_key[0], $meta_key[1] );
			} else {
				$option = get_option( $meta_key );
			}

			// all meta value have comma separated values from an array implode except uo_import_existing_user_data
			if ( is_array( $meta_key ) && $meta_key[0] === 'uo_import_existing_user_data' ) {
				$options[ $meta_key[0] ] = $option;
			} else {
				$options[ $meta_key ] = explode( ',', $option );
			}
		}

		$row_queue = $imported_rows + 9;
		for ( $i = $imported_rows; $i <= $row_queue; $i ++ ) {

			if ( $i >= $total_rows ) {
				break;
			}

			$current_row = $csvArray[ $i ];

			// check if email is proper
			if ( ! is_email( $current_row[ $key_location['user_email'] ] ) ) {
				$data['rows_ignored']            += 1;
				$data['ignored_rows_data'][ $i ] = 'Malformed Email';
				continue;
			}

			// check if login is too long
			if ( mb_strlen( $current_row[ $key_location['user_login'] ] ) > 60 ) {
				$data['rows_ignored']            += 1;
				$data['ignored_rows_data'][ $i ] = 'Username is too long';
				continue;
			}

			// check if login has illegal characters
			if ( isset( $current_row[ $key_location['user_login'] ] ) ) {
				$sanitized_user_name = sanitize_user( $current_row[ $key_location['user_login'] ] );
				if ( $sanitized_user_name !== $current_row[ $key_location['user_login'] ] ) {
					$data['rows_ignored']            += 1;
					$data['ignored_rows_data'][ $i ] = 'Username has illegal characters';
					continue;
				}
			} else {
				$sanitized_user_name = sanitize_user( $current_row[ $key_location['user_email'] ] );
			}


			$email_exists = email_exists( $current_row[ $key_location['user_email'] ] );

			if ( false === $email_exists ) {

				$password = ( $key_location['user_pass'] ) ? $current_row[ $key_location['user_pass'] ] : wp_generate_password( 12, false );

				// If the user_pass column is available but the cell is empty, generate a password
				if ( '' === $password ) {
					$password = wp_generate_password( 12, false );
				}

				$user_login = ( isset( $current_row[ $key_location['user_login'] ] ) ) ? $current_row[ $key_location['user_login'] ] : $sanitized_user_name;

				if ( username_exists( $user_login ) ) {
					$data['rows_ignored']            += 1;
					$data['ignored_rows_data'][ $i ] = 'This username name already exists';
					continue;
				}

				$userdata = array(
					'user_email' => $current_row[ $key_location['user_email'] ],
					'user_login' => $user_login,
					'user_pass'  => $password
				);

				$display_name             = ( isset( $key_location['display_name'] ) ) ? $current_row[ $key_location['display_name'] ] : '';
				$userdata['display_name'] = $display_name;

				// Remove new user notifications
				if ( ! function_exists( 'wp_new_user_notification' ) ) {
					function wp_new_user_notification() {
					}
				}

				$userdata = apply_filters( 'csv_wp_insert_user', $userdata, $current_row );

				$user_id     = wp_insert_user( $userdata );
				$import_type = 'new_user';

				if ( self::$log_error ) {
					$encode_user_data = json_encode( $userdata );
					$log              = "[ User id: {$user_id}] encode_user_data: {$encode_user_data} password: {$password}\n";
					error_log( $log, 3, dirname( UO_FILE ) . '/new_user.log' ); //TODO REMOVE
				}


			} else {

				// Check if updating is allowed
				if ( 'update' !== $options['uo_import_existing_user_data'] ) {
					$data['rows_ignored']            += 1;
					$data['ignored_rows_data'][ $i ] = 'User Exists, option to update users is off';
					continue;
				}

				// Emails exists, check if user updates are allow
				$password = ( $key_location['user_pass'] ) ? $current_row[ $key_location['user_pass'] ] : '';

				$user_object = get_user_by( 'email', $current_row[ $key_location['user_email'] ] );

				if ( ! $user_object ) {
					$data['rows_ignored']            += 1;
					$data['ignored_rows_data'][ $i ] = 'User with this email not found.';
					continue;
				}

				if ( $user_object->user_login !== $current_row[ $key_location['user_login'] ] ) {

					$email = $current_row[ $key_location['user_email'] ];
					$login = $current_row[ $key_location['user_login'] ];
					if ( self::$log_error ) {
						$log = "[ User by Email id: $user_object->ID email: $email User Login id: $user_object->user_login login: $login ]\n";
						error_log( $log, 3, dirname( UO_FILE ) . '/login_email_match.log' ); //TODO REMOVE
					}

					$data['rows_ignored']            += 1;
					$data['ignored_rows_data'][ $i ] = 'A user was found with a matching email address; however, the username in WordPress does not match the username in the spreadsheet.  No update was made to this user.';
					continue;
				}

				$userdata = array(
					'ID'        => (int) $user_object->ID,
					'user_pass' => $password
				);

				$display_name             = ( isset( $key_location['display_name'] ) ) ? $current_row[ $key_location['display_name'] ] : '';
				$userdata['display_name'] = $display_name;

				// Remove all updated user notifications
				add_filter( 'send_email_change_email', '__return_false' );
				add_filter( 'password_change_email', '__return_false' );

				$userdata = apply_filters( 'csv_wp_update_user', $userdata, $current_row );

				$user_id = wp_update_user( $userdata );

				if ( self::$log_error ) {
					$encode_user_data = json_encode( $userdata );
					$log              = "[ User id: {$user_id}] encode_user_data: {$encode_user_data} password: {$password}\n";
					error_log( $log, 3, dirname( UO_FILE ) . '/update_user.log' ); //TODO REMOVE
				}


				$import_type = 'updated_user';


			}


			//On success
			if ( ! is_wp_error( $user_id ) ) {

				if ( 'new_user' === $import_type ) {

					$data['new_users'] += 1;

				} elseif ( 'updated_user' === $import_type ) {

					$data['updated_users'] += 1;

				}

				global $wpdb;

				// Enroll in Courses
				if ( ! isset( $current_row[ $key_location['learndash_courses'] ] ) ) {
					$current_row[ $key_location['learndash_courses'] ] = '';
				}
				if ( isset( $current_row[ $key_location['learndash_courses'] ] ) ) {

					if ( '' === $current_row[ $key_location['learndash_courses'] ] ) {
						$csv_course_ids = array_map( 'intval', $options['uo_import_enrol_in_courses'] );
					} else {
						$csv_course_ids = array_map( 'intval', explode( ';', $current_row[ $key_location['learndash_courses'] ] ) );
					}

					// Get all course IDs
					$post_type = 'sfwd-courses';

					$course_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s",
						$post_type
					) );

					$course_ids = array_map( 'intval', $course_ids );

					if ( self::$log_error ) {
						$t1  = json_encode( $csv_course_ids );
						$t2  = json_encode( $course_ids );
						$log = "[ User id: {$user_id}] csv_course_ids: {$t1} course_ids: {$t2}\n";
						error_log( $log, 3, dirname( UO_FILE ) . '/enroll_course.log' ); //TODO REMOVE
					}

					foreach ( $csv_course_ids as $csv_course_id ) {
						if ( in_array( $csv_course_id, $course_ids ) ) {
							// add course access
							ld_update_course_access( $user_id, $csv_course_id );

							if ( self::$log_error ) {
								$log = "[ User id: {$user_id}] csv_course_id: {$csv_course_id}\n";
								error_log( $log, 3, dirname( UO_FILE ) . '/enroll_course.log' ); //TODO REMOVE
							}

						}
					}

					// Remove values that are needed anymore so we can loop the rest as meta
					unset( $current_row[ $key_location['learndash_courses'] ] );

				}

				// Enrol in groups
				if ( ! isset( $current_row[ $key_location['learndash_groups'] ] ) ) {
					$current_row[ $key_location['learndash_groups'] ] = '';
				}
				if ( isset( $current_row[ $key_location['learndash_groups'] ] ) ) {

					if ( '' === $current_row[ $key_location['learndash_groups'] ] ) {
						$csv_group_ids = array_map( 'intval', $options['uo_import_add_to_group'] );
					} else {
						$csv_group_ids = array_map( 'intval', explode( ';', $current_row[ $key_location['learndash_groups'] ] ) );
					}

					// Get all group IDs
					$post_type = 'groups';

					$group_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s",
						$post_type
					) );

					$group_ids = array_map( 'intval', $group_ids );

					if ( self::$log_error ) {
						$t1  = json_encode( $csv_group_ids );
						$t2  = json_encode( $group_ids );
						$log = "[ User id: {$user_id}] csv_group_ids: {$t1} group_ids: {$t2}\n";
						error_log( $log, 3, dirname( UO_FILE ) . '/enroll_groups.log' ); //TODO REMOVE
					}

					foreach ( $csv_group_ids as $csv_group_id ) {
						if ( in_array( $csv_group_id, $group_ids ) ) {
							// add group access
							ld_update_group_access( $user_id, $csv_group_id );
							if ( class_exists( '\uncanny_learndash_groups\SharedFunctions' ) ) {
								
								// check if group is converted
								$code_group_id   = get_post_meta( $csv_group_id, '_ulgm_code_group_id', true );
								
								if ( ! empty( $code_group_id ) ) {
									$code      = $wpdb->prepare(
										'SELECT code FROM ' . $wpdb->prefix . \uncanny_learndash_groups\SharedFunctions::$db_group_codes_tbl . ' WHERE student_id IS NULL AND user_email IS NULL AND group_id = %d ', $code_group_id
									);
									
									$code_available = $wpdb->get_var( $code );
									
									if ( ! empty( $code_available ) ) {
										$status = 'Not Started';
										if ( ! class_exists( '\uncanny_learndash_groups\Database' ) ) {
											include_once( \uncanny_learndash_groups\Utilities::get_include( 'database.php' ) );
										}
										\uncanny_learndash_groups\SharedFunctions::set_user_to_code( $user_id, $code_available, $status );
										update_user_meta( $user_id, 'uo_code_status', $code );
									}else{
										// add new code and update it with new user.
										$new_codes = \uncanny_learndash_groups\SharedFunctions::generate_random_codes( 1 );
										if ( ! class_exists( '\uncanny_learndash_groups\Database' ) ) {
											include_once( \uncanny_learndash_groups\Utilities::get_include( 'database.php' ) );
										}
										$attr = array(
											'qty'           => 1,
											'code_group_id' => $code_group_id,
										);
										\uncanny_learndash_groups\Database::add_additional_codes( $attr, $new_codes );
										$existing_seats  = get_post_meta( $csv_group_id, '_ulgm_total_seats', true );
										$new_number_of_seats = $existing_seats + 1;
										update_post_meta( $csv_group_id, '_ulgm_total_seats', $new_number_of_seats );
										
										$code      = $wpdb->prepare(
											'SELECT code FROM ' . $wpdb->prefix . \uncanny_learndash_groups\SharedFunctions::$db_group_codes_tbl . ' WHERE student_id IS NULL AND user_email IS NULL AND group_id = %d ', $code_group_id
										);
										$code_available = $wpdb->get_var( $code );
										$status = 'Not Started';
										\uncanny_learndash_groups\SharedFunctions::set_user_to_code( $user_id, $code_available, $status );
									}
								}
							}

							if ( self::$log_error ) {
								$log = "[ User id: {$user_id}] csv_group_id: {$csv_group_id}\n";
								error_log( $log, 3, dirname( UO_FILE ) . '/enroll_course.log' ); //TODO REMOVE
							}

						}
					}

					// Remove values that are needed anymore so we can loop the rest as meta
					unset( $current_row[ $key_location['learndash_groups'] ] );

				}

				// Assign Roles
				if ( isset( $current_row[ $key_location['wp_role'] ] ) ) {

					if ( '' === $current_row[ $key_location['wp_role'] ] ) {
						$csv_role = $options['uo_import_set_roles'][0];
					} else {
						$csv_role = $current_row[ $key_location['wp_role'] ];
					}

					wp_update_user( array( 'ID' => $user_id, 'role' => $csv_role ) );

					// Remove values that are needed anymore so we can loop the rest as meta
					unset( $current_row[ $key_location['wp_role'] ] );

				}

				$user_email = $current_row[ $key_location['user_email'] ];

				// Remove values that are not needed anymore so we can loop the remaining as meta
				unset( $current_row[ $key_location['user_email'] ] );
				if ( isset( $current_row[ $key_location['user_login'] ] ) ) {
					unset( $current_row[ $key_location['user_login'] ] );
				}
				if ( isset( $current_row[ $key_location['user_pass'] ] ) ) {
					unset( $current_row[ $key_location['user_pass'] ] );
				}


				// Anyother feild is considered a meta key

				foreach ( $current_row as $key => $value ) {
					$t1         = json_encode( $current_row );
					$t2         = json_encode( $key );
					$meta_value = json_encode( $value );
					$meta_key   = json_encode( $csv_header[ $key ] );
					if ( '' == $csv_header[ $key ] ) {
						continue;
					}

					$update = update_user_meta( (int) $user_id, $csv_header[ $key ], $value );

					if ( self::$log_error ) {
						$update = json_encode( $update );
						$log    = "[ User id: {$user_id}] current_row: {$t1} index: {$t2} key: {$meta_key} value: {$meta_value} update: {$update}  \n";
						error_log( $log, 3, dirname( UO_FILE ) . '/meta.log' ); //TODO REMOVE
					}


				}

				if ( 'new_user' === $import_type ) {

					if ( $uo_import_users_send_new_user_email ) {

						$email = self::send_email( $user_email, $uo_import_users_new_user_email_subject, $uo_import_users_new_user_email_body, $password );
						if ( $email ) {
							$data['emails_sent'] += 1;
						} else {
							$data['ignored_rows_data'][ $i ] = $email;
						}

					}
				} elseif ( 'updated_user' === $import_type ) {

					if ( $uo_import_users_send_updated_user_email ) {

						if ( '' === $password ) {
							$password = 'Password has not changed';
						}

						$email = self::send_email( $user_email, $uo_import_users_updated_user_email_subject, $uo_import_users_updated_user_email_body, $password );
						if ( $email ) {
							$data['emails_sent'] += 1;
						} else {
							$data['ignored_rows_data'][ $i ] = 'Email failed to send.';
						}

					}

				}


			} else {
				// define error message
				$data['rows_ignored']            += 1;
				$data['ignored_rows_data'][ $i ] = $user_id->get_error_message();
			}

		}

		$imported_rows = $i;

		update_option( 'user_import_imported_rows', $imported_rows );

		if ( $imported_rows >= $total_rows ) {
			// Reset the progress and set completed
			update_option( 'user_import_total_rows', count( $csvArray ) );
			update_option( 'user_import_imported_rows', 0 );
			update_option( 'user_import_status', 'completed' );
			update_option( 'user_import_results', array() );

			$status = 'completed';
		}

		$data['imported_rows'] = $imported_rows;
		$data['status']        = $status;

		//$data['testing_after'] = array($total_rows,$imported_rows,$status,$key_location);

		wp_send_json_success( $data );
	}

	private static function get_key_location( $columns ) {

		$key_location = array();

		foreach ( $columns as $key => $v ) {
			$key_location[ $v ] = $key;
		}

		return $key_location;
	}

	/**
	 * Process CSV from file
	 *
	 * @since 1.0.0
	 */
	private static function get_csv( $csv_input ) {

		$csv_input = str_replace( "\r\n", "\n", $csv_input );
		$csv_input = str_replace( "\r", "\n", $csv_input );

		$csv_input = str_getcsv( $csv_input, "\n" );
		foreach ( $csv_input as &$row ) {
			$row = str_getcsv( $row, ',' );
		}

		return $csv_input;
	}

	/**
	 * Replace variable in email text
	 *
	 * @since 1.0.0
	 */
	private static function convert_mail_text( $user_email, $text, $password = 'Password' ) {

		$WPUser = get_user_by( 'email', $user_email );

		$user_id      = $WPUser->ID;
		$user_email   = $WPUser->user_email;
		$user_name    = $WPUser->user_login;
		$first_name   = $WPUser->first_name;
		$last_name    = $WPUser->last_name;
		$display_name = $WPUser->data->display_name;


		$text = str_replace( '%Site URL%', get_home_url(), $text );
		$text = str_replace( '%Login URL%', wp_login_url(), $text );
		$text = str_replace( '%Email%', $user_email, $text );
		$text = str_replace( '%Username%', $user_name, $text );
		$text = str_replace( '%First Name%', $first_name, $text );
		$text = str_replace( '%Last Name%', $last_name, $text );
		$text = str_replace( '%Password%', $password, $text );
		$text = str_replace( '%Display Name%', $display_name, $text );

		// Courses
		$user_courses = array();
		foreach ( ld_get_mycourses( $user_id ) as $course_id ) {
			$Course         = get_post( $course_id );
			$user_courses[] = $Course->post_title;
		}
		$user_courses = implode( ', ', $user_courses );

		$text = str_replace( '%LD Courses%', $user_courses, $text );

		// Groups
		$user_groups = array();
		foreach ( learndash_get_users_group_ids( $user_id ) as $group_id ) {
			$Group         = get_post( $group_id );
			$user_groups[] = $Group->post_title;
		}
		$user_groups = implode( ', ', $user_groups );

		$text = str_replace( '%LD Groups%', $user_groups, $text );

		// Esc Chars
		$text = str_replace( '\"', '"', $text );
		$text = str_replace( "\'", "'", $text );

		return $text;
	}

	/**
	 * Send proccessed email
	 *
	 * @param        $user_email
	 * @param        $email_title
	 * @param        $email_body
	 * @param string $password
	 *
	 * @return bool
	 */
	private static function send_email( $user_email, $email_title, $email_body, $password = 'Password' ) {

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		if ( self::$log_error ) {
			$en_headers = json_encode( $headers );
			$log        = "[ C user_email: {$user_email}] email_title: {$email_title} email_body: {$email_body} headers: {$en_headers}  \n";
			error_log( $log, 3, dirname( UO_FILE ) . '/email.log' ); //TODO REMOVE
		}

		$email_title = self::convert_mail_text( $user_email, $email_title, $password );
		$email_body  = self::convert_mail_text( $user_email, $email_body, $password );


		if ( self::$log_error ) {
			$autop = wpautop( $email_body );
			$log   = "[ D user_email: {$user_email}] email_title: {$email_title} email_body: {$email_body}  wpautop( email_body ): {$autop} headers: {$en_headers}  \n";
			error_log( $log, 3, dirname( UO_FILE ) . '/email.log' ); //TODO REMOVE
		}

		return wp_mail( $user_email, $email_title, wpautop( $email_body ), $headers );
	}


	/**
	 * AJAX Send test email
	 */
	public static function ajax_test_email() {


		if ( ! current_user_can( self::$capability ) ) {
			$data['error'] = 'You do not have permission to do this.';
			wp_send_json_error( $data );
		}

		if ( self::send_email( $_POST['user_email_address'], $_POST['email_subject'], $_POST['email_body'] ) ) {
			$data['message'] = __( 'Email was successfully sent.', 'uncanny-pro-toolkit' );
			$data[]          = $_POST['user_email_address'];
			$data[]          = $_POST['email_subject'];
			$data[]          = $_POST['email_body'];
			wp_send_json_success( $data );
		} else {
			$data['message'] = __( 'Otherwise, check your WordPress and server settings.', 'uncanny-pro-toolkit' );
			$data[]          = $_POST['user_email_address'];
			$data[]          = $_POST['email_subject'];
			$data[]          = $_POST['email_body'];
			wp_send_json_error( $data );
		}
	}

	/**
	 * Save email settings
	 */
	public static function ajax_save_email() {

		if ( ! current_user_can( self::$capability ) ) {
			$data['error'] = 'You do not have permission to do this.';
			wp_send_json_error( $data );
		}

		$_POST['new_user_email_body'] = str_replace( '\"', '"', $_POST['new_user_email_body'] );
		$_POST['new_user_email_body'] = str_replace( "\'", "'", $_POST['new_user_email_body'] );

		$_POST['updated_user_email_body'] = str_replace( '\"', '"', $_POST['updated_user_email_body'] );
		$_POST['updated_user_email_body'] = str_replace( "\'", "'", $_POST['updated_user_email_body'] );

		update_option( 'uo_import_users_send_new_user_email', $_POST['send_new_user_email'] );
		update_option( 'uo_import_users_new_user_email_subject', $_POST['new_user_email_subject'] );
		update_option( 'uo_import_users_new_user_email_body', $_POST['new_user_email_body'] );

		update_option( 'uo_import_users_send_updated_user_email', $_POST['send_updated_user_email'] );
		update_option( 'uo_import_users_updated_user_email_subject', $_POST['updated_user_email_subject'] );
		update_option( 'uo_import_users_updated_user_email_body', $_POST['updated_user_email_body'] );

		//Testing
		$data['$_POST']  = $_POST;
		$data['message'] = esc_html__( 'Email template is successfully saved.', 'uncanny-pro-toolkit' );
		wp_send_json_success( $data );

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = self::$module_name;

		$kb_link = 'https://www.uncannyowl.com/knowledge-base/import-learndash-users/ ';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Create or update users and assign them to courses and LearnDash Groups from a CSV file.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-upload"></i><span class="uo_pro_text">PRO</span>';


		$category = 'wordpress';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => false,
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
		return true;
	}
}
