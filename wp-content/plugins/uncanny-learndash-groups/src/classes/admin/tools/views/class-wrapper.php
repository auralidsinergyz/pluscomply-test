<?php
namespace uncanny_learndash_groups\Tools\Views;

use uncanny_learndash_groups\Tools;

class Wrapper {

	protected $sub_templates_path = '';

	public $system_report = '';

	public $logger = '';

	public function __construct() {

		$this->sub_templates_path = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'templates/';

		// Just prepare the CSS files to style our tools page.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_tools_style' ), 10 );

		add_action( 'admin_init', array( $this, 'proc_actions' ) );
	}

	public function proc_actions() {

		require_once Tools::get_path() . '/logs/class-actions.php';

		$logger = new Tools\Logs\Actions();

		if ( 'yes' === filter_input( INPUT_GET, 'delete_log' ) && wp_verify_nonce( filter_input( INPUT_GET, '_wpnonce' ), $logger::NONCE_KEY ) ) {

			$file = filter_input( INPUT_GET, 'delete' );

			$is_removed = $logger->remove_log( $file );

			wp_safe_redirect(
				add_query_arg(
					array(
						'page'       => 'uncanny-groups-tools-tools',
						'action'     => 'logs',
						'is_removed' => $is_removed ? 'yes' : 'no',
					),
					admin_url( 'admin.php' )
				)
			);

		}

	}

	public function enqueue_tools_style() {

		$page = filter_input( INPUT_GET, 'page', FILTER_DEFAULT );

		if ( 'uncanny-groups-tools-tools' === $page ) {
			wp_enqueue_style(
				'uncanny-groups-tools-style',
				Tools::get_uri_path() . 'assets/css/tools.css',
				array(), // No dependencies.
				Tools::TOOLS_VERSION, // Script version
				false // Show in <head>
			);
		}

	}

	public function get_header() {
		require_once $this->sub_templates_path . 'header.php';
	}

	public function get_tabs() {
		require_once $this->sub_templates_path . 'tabs.php';
	}

	public function get_tab_list() {
		return array(
			array(
				'id'    => 'status',
				'label' => esc_html__( 'Status', 'uncanny-groups-learndash' ),
			),
			array(
				'id'    => 'logs',
				'label' => esc_html__( 'Debug', 'uncanny-groups-learndash' ),
			),
			array(
				'id'    => 'db-tools',
				'label' => esc_html__( 'Database tools', 'uncanny-groups-learndash' ),
			),
		);
	}
	public function get_tab_class_attribute( $tab = '' ) {

		$current_tab = filter_input(
			INPUT_GET,
			'action',
			FILTER_DEFAULT,
			array(
				'options' => array(
					'default' => 'status',
				),
			)
		);

		$class_attributes = array( 'nav-tab' );

		if ( $current_tab === $tab ) {
			$class_attributes[] = 'nav-tab-active';
		}

		return implode( ' ', $class_attributes );

	}

	public function wrap() {
		require_once $this->sub_templates_path . 'main.php';
	}

	public function get_action_url( $action = '' ) {
		return add_query_arg(
			array(
				'page'   => 'uncanny-groups-tools-tools',
				'action' => $action,
			),
			admin_url( 'admin.php' )
		);
	}

	public function get_active_content() {

		$action = filter_input(
			INPUT_GET,
			'action',
			FILTER_UNSAFE_RAW,
			array(
				'options' => array(
					'default' => 'status',
				),
			)
		);

		$allowed_action = array( 'status', 'logs', 'db-tools' );

		if ( ! in_array( $action, $allowed_action, true ) ) {
			$action = 'status';
		}

		$this->get_dependencies( $action );

		$sub_template = $this->sub_templates_path . sanitize_title( $action ) . '.php';

		if ( file_exists( $sub_template ) ) {

			require_once $sub_template;

		}

	}

	protected function get_dependencies( $action = '' ) {

		$pages_report = array( 'status', 'db-tools' );
		$pages_logs   = array( 'logs' );

		if ( in_array( $action, $pages_report, true ) ) {

			require_once Tools::get_path() . '/system/class-report.php';
			require_once Tools::get_path() . '/system/class-db-report.php';

			$this->system_report = new Tools\System\Report();
			$this->db_report     = new Tools\System\DB_Report();

		}

		if ( in_array( $action, $pages_logs, true ) ) {

			require_once Tools::get_path() . '/logs/class-actions.php';

			$this->logger = new Tools\Logs\Actions();

		}

	}

	public function __invoke() {

		$this->wrap();

	}

}
