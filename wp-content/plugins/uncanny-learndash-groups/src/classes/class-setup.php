<?php

namespace uncanny_learndash_groups;

/**
 * Class Setup
 * @package uncanny_learndash_groups
 */
class Setup {
	/**
	 * The instance of the class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Load_Groups
	 */
	private static $instance = null;

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
	 * Setup constructor.
	 */
	public function __construct() {

		register_activation_hook( UNCANNY_GROUPS_PLUGIN_FILE, array( $this, 'activation' ) );

		register_deactivation_hook( UNCANNY_GROUPS_PLUGIN_FILE, array( $this, 'deactivation' ) );

		add_action( 'admin_init', array( $this, 'move_and_remove_older_cached_orders' ) );
		add_action( 'admin_init', array( $this, 'move_and_remove_older_redemption_keys' ) );
	}

	/**
	 *
	 */
	public function move_and_remove_older_cached_orders() {
		if ( ! is_multisite() ) {
			return;
		}

		if ( 'yes' === get_option( 'ulgm_old_cached_orders_removed', 'no' ) ) {
			return;
		}

		global $wpdb;
		$caches = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->sitemeta WHERE meta_key LIKE '_ulgm_user_%_order'" );
		if ( empty( $caches ) ) {
			update_option( 'ulgm_old_cached_orders_removed', 'yes', false );

			return;
		}

		foreach ( $caches as $cache ) {
			$meta_key   = $cache->meta_key;
			$meta_value = maybe_unserialize( $cache->meta_value );
			if ( isset( $meta_value['order_id'] ) ) {
				$order_id = $meta_value['order_id'];
				if ( empty( get_post_meta( $order_id, $meta_key, true ) ) ) {
					update_post_meta( $order_id, $meta_key, $meta_value );
				}
			}
			SharedFunctions::remove_transient_cache( 'no', $meta_key );
		}

		update_option( 'ulgm_old_cached_orders_removed', 'yes', false );
	}

	/**
	 *
	 */
	public function move_and_remove_older_redemption_keys() {

		if ( 'yes' === get_option( 'ulgm_old_keys_data_removed', 'no' ) ) {
			return;
		}

		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'ulgm-user-redemption-atts_%'" );
		delete_option( 'ulgm-custom-registration-atts' );

		update_option( 'ulgm_old_keys_data_removed', 'yes', false );
	}


	/**
	 * The code that runs during plugin activation.
	 * @since    1.0.0
	 */
	public function activation() {

		do_action( 'ulgm_activation_before' );

		// Add Groups DB tables
		$this->initialize_db();

		// Add Group Management pages
		$this->generate_groups_pages();

		do_action( 'ulgm_activation_after' );

	}

	/**
	 *
	 */
	public function initialize_db() {

		include_once ULGM_ABSPATH . 'src/includes/database.php';
		$db_version = get_option( 'ulgm_database_version', null );
		if ( null !== $db_version && (string) UNCANNY_GROUPS_DB_VERSION === (string) $db_version ) {
			// bail. No db upgrade needed!
			return;
		}

		Database::create_tables();

	}

	/**
	 *
	 */
	public function generate_groups_pages( $force = false ) {
		if ( ! $force && 'yes' === get_option( 'ulgm_group_management_pages_generated', 'no' ) ) {
			return;
		}
		$user_id = get_current_user_id();

		$create_group_page = array(
			'post_type'    => 'page',
			'post_title'   => _x( 'Group Management', 'group page post_title', 'uncanny-learndash-groups' ),
			'post_content' => '[uo_groups]',
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_name'    => 'group-management',
		);
		$page_id_exists    = $this->the_slug_exists( 'group-management' );
		if ( false === $page_id_exists ) {
			$management_page_id = wp_insert_post( $create_group_page );
		} else {
			$management_page_id = $page_id_exists;
		}
		update_option( 'ulgm_group_management_page', $management_page_id );

		$course_label  = class_exists( '\LearnDash_Custom_Label' ) ? \LearnDash_Custom_Label::get_label( 'course' ) : __( 'Course', 'uncanny-learndash-groups' );
		$courses_label = class_exists( '\LearnDash_Custom_Label' ) ? \LearnDash_Custom_Label::get_label( 'courses' ) : __( 'Courses', 'uncanny-learndash-groups' );
		$quiz_label    = class_exists( '\LearnDash_Custom_Label' ) ? \LearnDash_Custom_Label::get_label( 'quiz' ) : __( 'Quiz', 'uncanny-learndash-groups' );
		//
		$create_report_page = array(
			'post_type'    => 'page',
			'post_title'   => sprintf( _x( 'Group %s Report', 'group course report post_title', 'uncanny-learndash-groups' ), $course_label ),
			'post_content' => '[uo_groups_course_report]',
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_name'    => 'group-management-report',
		);

		$page_id_exists = $this->the_slug_exists( 'group-management-report' );
		if ( false === $page_id_exists ) {
			$report_page_id = wp_insert_post( $create_report_page );
		} else {
			$report_page_id = $page_id_exists;
		}
		update_option( 'ulgm_group_report_page', $report_page_id );

		//
		$create_quiz_report_page = array(
			'post_type'    => 'page',
			'post_title'   => sprintf( _x( 'Group %s Report', 'group quiz report post_title', 'uncanny-learndash-groups' ), $quiz_label ),
			'post_content' => '[uo_groups_quiz_report]',
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_name'    => 'group-quiz-report',
		);

		$page_id_exists = $this->the_slug_exists( 'group-quiz-report' );
		if ( false === $page_id_exists ) {
			$quiz_report_page_id = wp_insert_post( $create_quiz_report_page );
		} else {
			$quiz_report_page_id = $page_id_exists;
		}
		update_option( 'ulgm_group_quiz_report_page', $quiz_report_page_id );

		//
		$create_assignment_report_page = [
			'post_type'    => 'page',
			'post_title'   => _x( 'Group Assignment Report', 'group assigment report post_title', 'uncanny-learndash-groups' ),
			'post_content' => '[uo_groups_assignments]',
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_name'    => 'assignment-management-page',
		];

		$page_id_exists = $this->the_slug_exists( 'assignment-management-page' );
		if ( false === $page_id_exists ) {
			$assignment_report_page_id = wp_insert_post( $create_assignment_report_page );
		} else {
			$assignment_report_page_id = $page_id_exists;
		}
		update_option( 'ulgm_group_assignment_report_page', $assignment_report_page_id );

		//
		$create_essay_report_page = array(
			'post_type'    => 'page',
			'post_title'   => _x( 'Group Essay Report', 'group essay report post_title', 'uncanny-learndash-groups' ),
			'post_content' => '[uo_groups_essays]',
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_name'    => 'essay-management-page',
		);

		$page_id_exists = $this->the_slug_exists( 'essay-management-page' );
		if ( false === $page_id_exists ) {
			$essay_report_page_id = wp_insert_post( $create_essay_report_page );
		} else {
			$essay_report_page_id = $page_id_exists;
		}
		update_option( 'ulgm_group_essay_report_page', $essay_report_page_id );

		//
		$create_progress_report_page = array(
			'post_type'    => 'page',
			'post_title'   => _x( 'Group Progress Report', 'group progress report post_title', 'uncanny-learndash-groups' ),
			'post_content' => '[uo_groups_manage_progress]',
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_name'    => 'group-progress-report',
		);

		$page_id_exists = $this->the_slug_exists( 'group-progress-report' );
		if ( false === $page_id_exists ) {
			$progress_report_page_id = wp_insert_post( $create_progress_report_page );
		} else {
			$progress_report_page_id = $page_id_exists;
		}
		update_option( 'ulgm_group_manage_progress_page', $progress_report_page_id );

		$create_a_la_carte_license_page = array(
			'post_type'    => 'page',
			'post_title'   => sprintf( _x( 'Group Management Buy %s', 'group buy course report post_title', 'uncanny-learndash-groups' ), $courses_label ),
			'post_content' => '[uo_groups_buy_courses]',
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_name'    => 'group-management-buy-courses',
		);

		$page_id_exists = $this->the_slug_exists( 'group-management-buy-courses' );
		if ( false === $page_id_exists ) {
			$buy_courses_id = wp_insert_post( $create_a_la_carte_license_page );
		} else {
			$buy_courses_id = $page_id_exists;
		}
		update_option( 'ulgm_group_buy_courses_page', $buy_courses_id );
		update_option( 'ulgm_group_management_pages_generated', 'yes' );
	}

	/**
	 * @param $post_name
	 *
	 * @return bool|int
	 */
	public function the_slug_exists( $post_name ) {
		global $wpdb;
		$qry = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name LIKE %s", $post_name ) );

		if ( $qry ) {
			return (int) $qry;
		} else {
			return false;
		}
	}

	/**
	 * The code that runs during plugin deactivation.
	 * @since    1.0.0
	 */
	public function deactivation() {

		do_action( 'ulgm_deactivation_before' );

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
		 *
		 */
		$set_role_capabilities = apply_filters( 'ulgm_add_role_capabilities', $set_role_capabilities );

		include_once ULGM_ABSPATH . 'src/includes/capabilities.php';
		$capabilities = new Capabilities( $set_role_capabilities );
		$capabilities->remove_capabilities();

		do_action( 'ulgm_deactivation_after' );
	}
}
