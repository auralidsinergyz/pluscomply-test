<?php
/**
 * Report Export Form/AJAX request Submission
 *
 * @package Learndash_Reporting_Pro
 */

namespace bulk_export_modal {

	/**
	 * Class to handle ___
	 */
	class BulkExportButtonHandler {

		/**
		 * Instance of this class
		 *
		 * @since 1.0.0
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialization.
		 */
		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_popup_scripts' ) );
			add_action( 'admin_footer', array( $this, 'enqueue_thickbox' ) );
		}

		/**
		 * Returns an instance of this class.
		 *
		 * @since 1.0.0
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Enque thickbox library from WordPress.
		 */
		public function enqueue_thickbox() {
			add_thickbox();
		}


		/**
		 * Displaying popup HTML.
		 */
		public function show_popup_html() {
			$content = '';
			ob_start();
			include LDRP_PLUGIN_DIR . 'includes/templates/bulk-export-popup-html.php';
			return ob_get_clean() . $content;
		}

		/**
		 * Loading JS and CSS.
		 */
		public function enqueue_popup_scripts() {
			// Enqueuing 'my_micromodal.js' file.
			$js_url  = LDRP_PLUGIN_URL . 'assets/admin/js/bul-export-admin-modal.js';
			$js_path = LDRP_PLUGIN_DIR . 'assets/admin/js/bul-export-admin-modal.js';

			wp_enqueue_script( 'bulk_export_js', $js_url, array( 'jquery' ), filemtime( $js_path ), true );
			wp_localize_script(
				'bulk_export_js',
				'bulk_export_js_object',
				array(
					'ajax_url'               => admin_url( 'admin-ajax.php' ),
					'action'                 => 'my_popup_ajax_action',
					'nonce'                  => wp_create_nonce( 'my_popup_ajax_action-nonce' ),
					'search_results_nonce'   => wp_create_nonce( 'get_search_suggestions' ),
					'filtered_results_nonce' => wp_create_nonce( 'filter_statistics_data' ),
					'quiz_export_nonce'      => wp_create_nonce( 'quiz_export-' . get_current_user_id() ),
					'custom_reports_nonce'   => wp_create_nonce( 'custom_reports_nonce' ),
					'fetch_custom_reports'   => wp_create_nonce( 'fetch_custom_reports' ),
					'export_button_text'     => __( 'Export', 'learndash-reports-pro' ),
					'export_heading_text'    => __( 'Bulk Export', 'learndash-reports-pro' ),
					'export_message'         => __( 'Export all the below quiz attempt in a single  csv/xls file.', 'learndash-reports-pro' ),
					'modal_body'             => $this->show_popup_html(),
				)
			);

			// Enqueuing 'micromodal.css' file.
			$css_url  = LDRP_PLUGIN_URL . 'assets/admin/css/bulk-export-admin-modal.css';
			$css_path = LDRP_PLUGIN_DIR . 'assets/admin/css/bulk-export-admin-modal.css';

			wp_enqueue_style( 'micromodal_css', $css_url, array(), filemtime( $css_path ) );

		}

	}
	BulkExportButtonHandler::get_instance();
}

