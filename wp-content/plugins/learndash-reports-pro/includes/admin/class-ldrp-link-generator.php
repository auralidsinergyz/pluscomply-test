<?php
/**
 * Report Export setup
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Qre_Link_Generator' ) ) {
	/**
	 * Qre_Link_Generator Class.
	 *
	 * @class Qre_Link_Generator
	 */
	class Qre_Link_Generator {
		/**
		 * The single instance of the class.
		 *
		 * @var Qre_Link_Generator
		 * @since 3.0.0
		 */
		protected static $instance = null;

		/**
		 * The instances of all the included classes.
		 *
		 * @var Qre_Link_Generator
		 * @since 3.0.0
		 */
		protected $instances = array();

		/**
		 * Qre_Link_Generator Instance.
		 *
		 * Ensures only one instance of Qre_Link_Generator is loaded or can be loaded.
		 *
		 * @since 3.0.0
		 * @static
		 * @return Qre_Link_Generator - instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Returns a list of all class instances used by this class.
		 *
		 * @return array Array of dependent class instances.
		 */
		public function instances() {
			return $this->instances;
		}

		/**
		 * QuizReportingExtension Constructor.
		 */
		public function __construct() {
			$this->set_instances();
			$this->init_hooks();
		}

		/**
		 * Include required core files used in admin side.
		 */
		public function includes() {
			/**
			 * Class QuizMode.
			 */
			include_once LDRP_PLUGIN_DIR . 'includes/class-export-file-processing.php';
			include_once LDRP_PLUGIN_DIR . 'includes/class-quiz-export-data.php';

		}

		/**
		 * This method is used to store instances of dependencies.
		 */
		private function set_instances() {
			$this->instances['form-handler'] = Export_File_Processing::instance();
		}

		/**
		 * This method is used to get instance of a particular class or all instances related to this class.
		 *
		 * @param string $instance_of Instance of which class.
		 *
		 * @return Array/Object
		 */
		public function get_instances( $instance_of = '' ) {
			switch ( $instance_of ) {
				case 'form-handler':
					return $this->instances['form-handler'];
					break;
			}
		}

		/**
		 * Hook into actions and filters.
		 *
		 * @since 3.0.0
		 */
		private function init_hooks() {
			add_action( 'admin_footer', array( $this, 'qre_localize_data' ) );
		}

		/**
		 * This method is used to localize data used by AJAX.
		 *
		 * @return void.
		 */
		public function qre_localize_data() {
			if ( isset( $_GET['page'] ) && isset( $_GET['module'] ) && 'ldAdvQuiz' === $_GET['page'] && 'statistics' === $_GET['module'] && isset( $_GET['id'] ) && '' !== $_GET['id'] ) {// phpcs:ignore WordPress.Security.NonceVerification
				// if page is 'module' and 'statistics' of quiz.
				$quiz_id = trim( $_GET['id'] ); // phpcs:ignore

				if ( is_numeric( $quiz_id ) ) {
					// to get all stat ids to export all responses - starts.
					$result  = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_by_quiz( $quiz_id );
					$all_ids = '';

					if ( is_array( $result ) ) {
						$result = array_filter( $result );
					}

					if ( ! empty( $result ) ) {
						foreach ( $result as $stat_id ) {
							$all_ids .= $stat_id['statistic_ref_id'] . ',';
						}
					}
				}

				$all_ids = substr( $all_ids, 0, -1 ); // removing last char
				// to get all stat ids to export all responses - ends.

				$quiz_export_nonce = wp_create_nonce( 'quiz_export-' . get_current_user_id() );

				$loader_link = LDRP_PLUGIN_URL . 'assets/admin/images/ajax_preloader.gif'; // loader image link after clicking export link.

				wp_enqueue_script( 'ldrp_export_js' );
				wp_enqueue_script( 'ldrp_page_blocker' );
				wp_enqueue_script( 'ldrp_common_js' );

				$qre_localized_data = array(
					'ajax_url'                    => admin_url( 'admin-ajax.php' ),
					'loader_link'                 => $loader_link,
					'all_ids'                     => $all_ids,
					'quiz_pro_id'                 => $quiz_id,
					'quiz_export_nonce'           => $quiz_export_nonce,
					'processing_text'             => sprintf( '<h4>%s</h4>', __( 'Processing...', 'quiz_reporting_learndash' ) ),
					'qre_msg_export_all_in_csv'   => __( 'Export All ', 'learndash-reports-pro' ),
					'qre_msg_export_all_in_excel' => __( 'Export All in Excel ', 'learndash-reports-pro' ),
					'qre_msg_export'              => __( 'Export', 'learndash-reports-pro' ),
					'qre_msg_export_response'     => __( 'Export Response ', 'learndash-reports-pro' ),
					'qre_msg_csv'                 => __( ' CSV ', 'learndash-reports-pro' ),
					'qre_msg_excel'               => __( ' Excel ', 'learndash-reports-pro' ),
					'export_btn_text'             => __( 'Export', 'learndash-reports-pro' ),
					'notice_text'                 => __( '<p> Dear User, </p><p> This is to inform you that WisdmLabs does not collect any user data. The data that is being exported is your sole responsibility and we urge you to update the privacy policy of your website. </p> <p> Regards, </p> <p> WisdmLabs </p>', 'quiz_reporting_learndash' ),
				);

				wp_localize_script( 'ldrp_export_js', 'qre_export_obj', $qre_localized_data );
				wp_enqueue_style( 'ldrp_admin_css' );
			}
		}
	}
}
