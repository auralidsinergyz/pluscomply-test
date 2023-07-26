<?php

namespace uncanny_learndash_groups;

/**
 * Class AdminCreateGroup
 *
 * @package uncanny_learndash_groups
 */
class AdminCreateGroup {
	/**
	 * AdminCreateGroup constructor.
	 */
	public function __construct() {
		// Enqueue page specific styles
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'create_new_group_scripts' ), 30 );
		add_action( 'wp_ajax_search_user', array( $this, 'search_user_by_email' ), 33 );
		add_action( 'admin_menu', array( $this, 'create_manual_group_admin_page' ), 11 );
		add_shortcode( 'uo_groups_create_group', array( $this, 'options_output' ) );
	}

	/**
	 *
	 */
	public function create_manual_group_admin_page() {

		$capability = 'manage_options';
		$menu_slug  = 'uncanny-groups-create-group';

		// Create a link the main page when the menu expands
		add_submenu_page(
			$menu_slug,
			__( 'Create group', 'uncanny-learndash-groups' ),
			__( 'Create group', 'uncanny-learndash-groups' ),
			$capability,
			$menu_slug,
			array(
				$this,
				'options_output',
			)
		);
	}

	/**
	 * @param $hook
	 */
	public function admin_scripts( $hook ) {
		if ( strpos( $hook, 'uncanny-groups-create-group' ) ) {
			$this->add_scripts( $hook );
		}
	}


	/**
	 *
	 */
	public function add_scripts( $hook = '' ) {
		// Load Styles for Licensing page located in general plugin styles

		wp_enqueue_style( 'ulgm-backend', Utilities::get_asset( 'backend', 'bundle.min.css' ), array(), Utilities::get_version() );

		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_register_style( 'jquery-ui-styles', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui-styles' );

		wp_enqueue_script( 'ulgm-select2', Utilities::get_vendor( 'select2/js/select2.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
		wp_enqueue_style( 'ulgm-select2', Utilities::get_vendor( 'select2/css/select2.min.css' ), array(), Utilities::get_version() );

		//wp_enqueue_script( 'jquery-auto-complete', Utilities::get_js( 'jquery-auto-complete.min.js' ), '', Utilities::get_version(), true );
		wp_register_script(
			'ulgm-backend',
			Utilities::get_asset( 'backend', 'bundle.min.js' ),
			array(
				'jquery',
				'ulgm-select2',
			),
			Utilities::get_version(),
			true
		);
		wp_localize_script( 'ulgm-backend', 'ULGM_USER_AUTOCOMPLETE_AJAX', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'ulgm-backend' );

		wp_enqueue_media();
	}

	/**
	 *
	 */
	public function create_new_group_scripts() {
		global $post;
		if ( Utilities::has_shortcode( $post, 'uo_groups_create_group' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-create-group' ) ) {
			self::enqueue_frontend_assets();
		}
	}

	/**
	 * @since 3.7.5
	 * @author Agus B.
	 * @internal Saad S.
	 */
	public static function enqueue_frontend_assets() {
		global $post;

		if ( ! empty( $post ) ) {
			wp_enqueue_script(
				'ulgm-frontend',
				Utilities::get_asset( 'frontend', 'bundle.min.js' ),
				array(
					'jquery',
					'ulgm-select2',
				),
				Utilities::get_version(),
				true
			);

			// Load Styles for Licensing page located in general plugin styles
			wp_register_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array(), Utilities::get_version() );
			$user_colors = Utilities::user_colors();
			wp_add_inline_style( 'ulgm-frontend', $user_colors );
			wp_enqueue_style( 'ulgm-frontend', $user_colors );

			wp_enqueue_media();

			wp_enqueue_script( 'ulgm-select2', Utilities::get_vendor( 'select2/js/select2.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
			wp_enqueue_style( 'ulgm-select2', Utilities::get_vendor( 'select2/css/select2.min.css' ), array(), Utilities::get_version() );
		}
	}


	/**
	 *
	 */
	public function search_user_by_email() {

		if ( ulgm_filter_has_var( 'term' ) && ! empty( ulgm_filter_input( 'term' ) ) ) {
			$term = strtolower( ulgm_filter_input( 'term' ) );
		} elseif ( ulgm_filter_has_var( 'name' ) && ! empty( ulgm_filter_input( 'name' ) ) ) {
			$term = strtolower( ulgm_filter_input( 'name' ) );
		} else {
			echo wp_json_encode( array() );
			die();
		}
		$suggestions = array();

		$loop = get_users( array( 'search' => "{$term}*" ) );
		foreach ( $loop as $user ) {
			$suggestions[] = $user->user_email;
		}

		$response = wp_json_encode( $suggestions );
		echo $response;
		die();

	}

	/**
	 * Create Theme Options page
	 *
	 * @since 1.0.0
	 */
	public function options_output( $atts = array() ) {
		global $post;

		$atts = shortcode_atts(
			array(
				'category'        => '',
				'course_category' => '',
			),
			$atts,
			'uo_groups_create_group'
		);

		if ( ulgm_filter_has_var( 'is-group-created' ) ) {
			return '<h3>' . __( 'Group created successfully.', 'uncanny-learndash-groups' ) . '</h3>';
		}
		if ( is_user_logged_in() ) {
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
			if ( current_user_can( 'manage_options' ) || array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {
				if ( Utilities::has_shortcode( $post, 'uo_groups_create_group' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-create-group' ) ) {
					ob_start();

					include Utilities::get_template( 'admin/admin-custom-groups.php' );

					return ob_get_clean();
				} else {
					include Utilities::get_template( 'admin/admin-custom-groups.php' );
				}
			} else {
				echo __( 'Oops! You are not allowed to view this page.', 'uncanny-learndash-groups' );
			}
		} else {
			echo __( 'Oops! You are not logged in to view this page.', 'uncanny-learndash-groups' );
		}

	}
}
