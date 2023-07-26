<?php
/**
 * Plugin Name:     WISDM Reports for LearnDash PRO
 * Plugin URI:      https://wisdmlabs.com/reports-for-learndash/
 * Description:     This is the pro version of the plugin Wisdm Reports for LearnDash.
 * Author:          WisdmLabs
 * Author URI:      https://wisdmlabs.com'
 * Text Domain:     learndash-reports-pro
 * Domain Path:     /languages
 * Version:         1.6.1
 *
 * @package         Learndash_Reporting_Pro
 */

define( 'LDRP_PLUGIN_VERSION', '1.6.1' );
define( 'LDRP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LDRP_PLUGIN_DIR', dirname( __FILE__ ) . '/' );
add_action( 'plugins_loaded', 'ldrp_load_license', 9 );

if ( ! defined( 'LDRP_RECOMENDED_FREE_PLUGIN_VERSION' ) ) {
	// The minimum version of free version
	define( 'LDRP_RECOMENDED_FREE_PLUGIN_VERSION', '1.6.1' );
}



if ( ! function_exists( 'ldrp_load_license' ) ) {
	/**
	 * This function is used to load the licensing logic to get plugin updates.
	 */
	function ldrp_load_license() {
		global $ldrp_plugin_data;
		$ldrp_plugin_data = include_once LDRP_PLUGIN_DIR . 'license.config.php';
		require_once LDRP_PLUGIN_DIR . 'licensing/class-wdm-license.php';
		new Licensing\WdmLicense( $ldrp_plugin_data );
	}
}

/**
 * This function is used to get file enqueue version used for enqueuing assets.
 *
 * @param  string $file File path.
 * @return string File change time/Plugin version.
 */
function ldrp_get_file_version( $file ) {
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( untrailingslashit( dirname( __FILE__ ) ) . $file ) ) {
		return filemtime( untrailingslashit( dirname( __FILE__ ) ) . $file );
	}
	return LDRP_PLUGIN_VERSION;
}

/**
 * This method is used to load common assets.
 *
 * @return void
 */
function ldrp_load_common_assets() {
	wp_register_script( 'page_blocker', LDRP_PLUGIN_URL . 'assets/dist/js/page_blocker.min.js', array( 'jquery' ), ldrp_get_file_version( '/assets/dist/js/page_blocker.min.js' ), false );
	if ( is_rtl() ) {
		wp_enqueue_style( 'qre-common-css', LDRP_PLUGIN_URL . 'assets/dist/css/common-rtl.css', array(), ldrp_get_file_version( '/assets/dist/css/common-rtl.css' ) );
	} else {
		wp_enqueue_style( 'qre-common-css', LDRP_PLUGIN_URL . 'assets/dist/css/common.css', array(), ldrp_get_file_version( '/assets/dist/css/common.css' ) );
	}
	wp_enqueue_script( 'qre-common-js', LDRP_PLUGIN_URL . 'assets/dist/js/common.js', array( 'jquery' ), ldrp_get_file_version( '/assets/dist/js/common.js' ), false );
}

/**
 * This method is used to register frontend scripts.
 *
 * @return void
 */
function ldrp_register_frontend_assets() {
	if ( is_rtl() ) {
		wp_register_style( 'qre_public_css', LDRP_PLUGIN_URL . 'assets/dist/css/public-rtl.css', array(), ldrp_get_file_version( '/assets/dist/css/public-rtl.css' ) );
	} else {
		wp_register_style( 'qre_public_css', LDRP_PLUGIN_URL . 'assets/dist/css/public.css', array(), ldrp_get_file_version( '/assets/dist/css/public.css' ) );
	}
	wp_register_script( 'qre_export_frontend', LDRP_PLUGIN_URL . 'assets/dist/js/public.js', array( 'jquery' ), ldrp_get_file_version( '/assets/dist/js/public.js' ), false );
}

/**
 * Autoload function to calculate the file name and include it based on WPCS.
 *
 * @param  string $class_name Class Name.
 * @return void.
 */
function ldrp_autoloader( $class_name ) {
	// Remove Namespaces from the classname.
	$class_with_namespace = explode( '\\', $class_name );
	$class                = end( $class_with_namespace );
	// Change from camelcaps to hyphen separated for fetching filenames.
	$pieces = explode( '_', $class );
	$class  = strtolower( implode( '-', $pieces ) );

	$paths = array(
		'includes/',
		'includes/question-types/',
	);

	foreach ( $paths as $path ) {
		if ( file_exists( LDRP_PLUGIN_DIR . $path . 'class-' . $class . '.php' ) ) {
			include_once LDRP_PLUGIN_DIR . $path . 'class-' . $class . '.php';
			break;
		}
	}
}

/**
 * This function is used for handling all the file include processes such as including individual files, composer autoload, class autoload and initialization, and main asset enqueues for backend export.
 */
function ldrp_include_files() {

	if ( ! defined( 'WRLD_REPORTS_FILE' ) ) {
		return '';
	}

	include_once LDRP_PLUGIN_DIR . 'includes/functions.php';
	/**
	 * Load Composer Packages.
	 */
	include_once LDRP_PLUGIN_DIR . 'vendor/autoload.php';
	include_once LDRP_PLUGIN_DIR . 'includes/admin/class-ldrp-link-generator.php';
	require 'includes/admin/class-bulkexportbuttonhandler.php';

	spl_autoload_register( 'ldrp_autoloader' );
	Export_File_Processing::instance();
	Quiz_Reporting_Frontend::instance();
	Qre_Link_Generator::instance();

	add_action(
		'admin_enqueue_scripts',
		function() {
			if ( is_admin() ) {
				wp_register_style( 'ldrp_admin_css', LDRP_PLUGIN_URL . 'assets/dist/css/admin.css', array(), LDRP_PLUGIN_VERSION );
				wp_register_script( 'ldrp_common_js', LDRP_PLUGIN_URL . 'assets/dist/js/common.js', array( 'jquery' ), LDRP_PLUGIN_VERSION, false );
				wp_register_script( 'ldrp_export_js', LDRP_PLUGIN_URL . 'assets/dist/js/admin.js', array( 'jquery' ), LDRP_PLUGIN_VERSION, false );
				wp_register_script( 'ldrp_page_blocker', LDRP_PLUGIN_URL . 'assets/dist/js/page_blocker.min.js', array( 'jquery' ), LDRP_PLUGIN_VERSION, false );
			}
		}
	);

}

/**
 * This function is used to localize custom reports configuration.
 */
function ldrp_localize_custom_settings() {
	$filter_options = maybe_unserialize( get_user_meta( get_current_user_id(), 'qre_custom_reports_saved_query', true ) );
	$defaults       = array(
		'course_filter'      => -1,
		'course_title'       => 'yes',
		'completion_status'  => 'yes',
		'completion_date'    => false,
		'course_category'    => false,
		'enrollment_date'    => false,
		'course_progress'    => false,
		'group_filter'       => -1,
		'group_name'         => false,
		'user_name'          => 'yes',
		'user_email'         => false,
		'user_first_name'    => false,
		'user_last_name'     => false,
		'quiz_filter'        => -1,
		'quiz_status'        => 'yes',
		'quiz_title'         => 'yes',
		'quiz_category'      => 'yes',
		'quiz_points_total'  => 'yes',
		'quiz_points_earned' => 'yes',
		'quiz_score_percent' => 'yes',
		'date_of_attempt'    => 'yes',
		'time_taken'         => 'yes',
		'question_text'      => 'yes',
		'question_options'   => 'yes',
		'correct_answers'    => 'yes',
		'user_answers'       => 'yes',
		'question_type'      => 'yes',
	);

	if ( empty( $filter_options ) ) {
		$filter_options = $defaults;
	} else {
		foreach ( $defaults as $keys => $default ) {
			if ( in_array( $keys, array( 'course_filter', 'quiz_filter', 'group_filter' ), true ) ) {
				if ( ! array_key_exists( $keys, $filter_options ) ) {
					$filter_options[ $keys ] = $default;
				}
				continue;
			}
			if ( ! array_key_exists( $keys, $filter_options ) ) {
				$filter_options[ $keys ] = false;
			}
		}
	}
	wp_localize_script(
		'wisdm-learndash-reports-front-end-script-report-filters',
		'report_preferences',
		array(
			'settings'              => $filter_options,
			'selected_course_title' => -1 != $filter_options['course_filter'] ? get_the_title( $filter_options['course_filter'] ) : __( 'All', 'learndash-reports-pro' ),
			'selected_group_title'  => -1 != $filter_options['group_filter'] ? get_the_title( $filter_options['group_filter'] ) : __( 'All', 'learndash-reports-pro' ),
			'selected_quiz_title'   => -1 != $filter_options['quiz_filter'] ? get_the_title( $filter_options['quiz_filter'] ) : __( 'All', 'learndash-reports-pro' ),
		)
	);
	wp_localize_script(
		'wisdm-learndash-reports-front-end-script-student-table',
		'report_preferences',
		array(
			'settings'              => $filter_options,
			'selected_course_title' => -1 != $filter_options['course_filter'] ? get_the_title( $filter_options['course_filter'] ) : __( 'All', 'learndash-reports-pro' ),
			'selected_group_title'  => -1 != $filter_options['group_filter'] ? get_the_title( $filter_options['group_filter'] ) : __( 'All', 'learndash-reports-pro' ),
			'selected_quiz_title'   => -1 != $filter_options['quiz_filter'] ? get_the_title( $filter_options['quiz_filter'] ) : __( 'All', 'learndash-reports-pro' ),
		)
	);
	wp_localize_script(
		'wisdm-learndash-reports-editor-script-student-table',
		'report_preferences',
		array(
			'settings'              => $filter_options,
			'selected_course_title' => -1 != $filter_options['course_filter'] ? get_the_title( $filter_options['course_filter'] ) : __( 'All', 'learndash-reports-pro' ),
			'selected_group_title'  => -1 != $filter_options['group_filter'] ? get_the_title( $filter_options['group_filter'] ) : __( 'All', 'learndash-reports-pro' ),
			'selected_quiz_title'   => -1 != $filter_options['quiz_filter'] ? get_the_title( $filter_options['quiz_filter'] ) : __( 'All', 'learndash-reports-pro' ),
		)
	);
}


add_action( 'wp_enqueue_scripts', 'ldrp_load_common_assets' );
add_action( 'wp_enqueue_scripts', 'ldrp_register_frontend_assets' );
add_action( 'wp_enqueue_scripts', 'ldrp_localize_custom_settings' );
add_action( 'admin_enqueue_scripts', 'ldrp_localize_custom_settings' );
add_action( 'plugins_loaded', 'wisdm_reports_free_dependency_check', 1 );
add_action( 'plugins_loaded', 'ldrp_include_files', 2 );
add_action( 'wisdm_ld_reports_pro_version', '__return_true' );
add_action( 'init', 'ldrp_load_textdomain' );


/**
 * This function is used to deactivate the pro plugin if free plugin is not present.
 */
function wisdm_reports_free_dependency_check() {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( ! defined( 'WRLD_REPORTS_FILE' ) ) {
		$installed_plugins       = get_plugins();
		$installed_and_activated = false;
		$free_plugin_uris        = array( 'learndash-reports-by-wisdmlabs', 'learndash-reports-pro' );
		foreach ( $installed_plugins as $key => $plugin_data ) {
			if ( in_array( $plugin_data['TextDomain'], $free_plugin_uris, true ) ) {
				activate_plugin( $key );
				$installed_and_activated = true;
				break;
			}
		}

		if ( $installed_and_activated ) {
			add_action( 'admin_notices', 'wisdm_reports_free_activated_info', 999 );
		} else {
			add_action( 'admin_notices', 'wisdm_reports_free_activation_notices', 99 );
			add_action(
				'admin_enqueue_scripts',
				function() {
					wp_enqueue_script( 'wrld_plugin_installer', LDRP_PLUGIN_URL . 'assets/admin/js/installer.js', array( 'jquery' ), LDRP_PLUGIN_VERSION, false );
				}
			);
		}
	}
}

function wisdm_reports_free_activation_notices() {
	?>
		<div class='error'>
			<div style='display:inline-flex; justify-content:center; align-items:center;' class='wrld-plugin-dependency-installation'>
				<span id='wrld-update-status-update-sym' class='spinner is-active' style="margin-right: 10px;"></span>
				<p>
				 <?php echo wp_kses_post( __( 'Installing <strong>WISDM Reports for LearnDash Free</strong> plugin. In order to make <strong>WISDM Reports For LearnDash Pro </strong> plugin work,  WISDM Reports for LearnDash Free plugin is required', 'learndash-reports-pro' ) ); ?>
				</p>
			</div>
			<div id='wrld-installation-failure-message' style='display:none;'>
				<p>
				<?php echo wp_kses_post( __( 'Automatic installation of <strong>WISDM Reports for LearnDash Free</strong> plugin failed. In order to make <strong>WISDM Reports For LearnDash Pro </strong> plugin work, <strong> WISDM Reports for LearnDash Free </strong> plugin is required, please install the plugin from ', 'learndash-reports-pro' ) ); ?>
				 <a target="_blank" href="https://wordpress.org/plugins/wisdm-reports-for-learndash/"><?php _e( 'WordPress Plugin Directory.', 'learndash-reports-pro' ); ?></a>
				</p>
			</div>
		</div>
	<?php
}


function wisdm_reports_free_activated_info() {
	echo "<div class='notice notice-error is-dismissible'>
            <p><strong>Alert:  </strong>In order to make <strong>WISDM Reports For LearnDash Pro </strong> plugin work, the plugin <strong>WISDM Reports for LearnDash Free</strong> first needs to be active, we have activated the plugin since it was already installed.</p>
        </div>";
}

/**
 * Load plugin textdomain.
 */
function ldrp_load_textdomain() {
	load_plugin_textdomain( 'learndash-reports-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * This method is used to enqueue shortcode specific assets.
 *
 * @return void.
 */
function ldrp_enqueue_shortcode_assets() {
	wp_enqueue_script( 'qre_export_frontend' );
	wp_localize_script(
		'qre_export_frontend',
		'qre_export_obj',
		array(
			'search_results_nonce'    => wp_create_nonce( 'get_search_suggestions' ),
			'filtered_results_nonce'  => wp_create_nonce( 'filter_statistics_data' ),
			'quiz_export_nonce'       => wp_create_nonce( 'quiz_export-' . get_current_user_id() ),
			'custom_reports_nonce'    => wp_create_nonce( 'custom_reports_nonce' ),
			'fetch_custom_reports'    => wp_create_nonce( 'fetch_custom_reports' ),
			'ajax_url'                => admin_url( 'admin-ajax.php' ),
			'timeout_message'         => __( 'Request timed out. Please try again later.', 'learndash-reports-pro' ),
			'preview_report_btn_text' => __( 'APPLY FILTER & PREVIEW REPORT', 'learndash-reports-pro' ),
			'download_csv_text'       => __( 'DOWNLOAD CSV', 'learndash-reports-pro' ),
			'download_xls_text'       => __( 'DOWNLOAD XLSX', 'learndash-reports-pro' ),
			'export_btn_text'         => __( 'Export', 'learndash-reports-pro' ),
			'first_custom_url'        => get_permalink(),
		)
	);

	wp_enqueue_script( 'page_blocker' );
	wp_enqueue_style( 'qre_public_css' );
}

/**
 * This method is used to get breadcrumb elements for QRE shortcode navigation.
 *
 * @return array Array of Breadcrumb elements.
 */
function ldrp_get_breadcrumbs() {
	$breadcrumbs = array();
	$report_type = filter_input( INPUT_GET, 'report', FILTER_SANITIZE_STRING );
	if ( empty( $report_type ) || ! in_array( $report_type, array( 'quiz', 'custom' ), true ) ) {
		$report_type = 'quiz';
	}
	$screen_type = filter_input( INPUT_GET, 'screen', FILTER_SANITIZE_STRING );
	if ( empty( $screen_type ) || ! in_array( $screen_type, array( 'user', 'quiz' ), true ) ) {
		$screen_type = 'listing';
	}
	if ( 'custom' === $report_type ) {
		$breadcrumbs[] = array(
			'url'  => '',
			'text' => __( 'Custom Report Dashboard', 'learndash-reports-pro' ),
		);
		/**
		 * This filter is used to modify/change the breadcrumbs shown on Quiz Reporting Dashboard.
		 *
		 * @var array List of breadcrumb items.
		 */
		return apply_filters( 'qre_dashboard_breadcrumbs', $breadcrumbs );
	}
	switch ( $screen_type ) {
		case 'listing':
			$breadcrumbs[] = array(
				'url'  => '',
				/* translators: %s: Quiz Label */
				'text' => sprintf( __( '%s Reporting Dashboard', 'learndash-reports-pro' ), learndash_get_custom_label( 'quiz' ) ),
			);
			break;
		case 'user':
			$user_id = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT );
			if ( empty( $user_id ) ) {
				$user_id = get_current_user_id();
			}
			$display_name  = get_userdata( $user_id )->display_name;
			$breadcrumbs[] = array(
				'url'  => add_query_arg( 'report', 'quiz', get_permalink() ),
				'text' => __( 'Home', 'learndash-reports-pro' ),
			);
			$breadcrumbs[] = array(
				'url'  => '',
				'text' => $display_name,
			);
			break;
		case 'quiz':
			$user_id = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT );
			if ( empty( $user_id ) ) {
				$user_id = get_current_user_id();
			}
			$display_name = get_userdata( $user_id )->display_name;
			$quiz_pro_id  = filter_input( INPUT_GET, 'quiz', FILTER_VALIDATE_INT );
			$quiz_id      = learndash_get_quiz_id_by_pro_quiz_id( $quiz_pro_id );
			$referer      = urlencode( get_permalink() );
			$query_string = filter_input( INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING );
			if ( ! empty( $query_string ) ) {
				$referer = urlencode(
					remove_query_arg(
						'referer',
						add_query_arg( $query_string, '', get_permalink() )
					)
				);
			}
			$breadcrumbs[] = array(
				'url'  => add_query_arg( 'report', 'quiz', get_permalink() ),
				'text' => __( 'Home', 'learndash-reports-pro' ),
			);
			$breadcrumbs[] = array(
				'url'  => add_query_arg(
					array(
						'report'  => 'quiz',
						'screen'  => 'user',
						'user'    => $user_id,
						'referer' => $referer,
					),
					get_permalink()
				),
				'text' => $display_name,
			);
			$breadcrumbs[] = array(
				'url'  => '',
				'text' => get_the_title( $quiz_id ),
			);
			break;
		default:
			$breadcrumbs[] = array(
				'url'  => add_query_arg( 'report', 'quiz', get_permalink() ),
				'text' => __( 'Home', 'learndash-reports-pro' ),
			);
			break;
	}
	/**
	 * This filter is used to modify/change the breadcrumbs shown on Quiz Reporting Dashboard.
	 *
	 * @var array List of breadcrumb items.
	 */
	return apply_filters( 'qre_dashboard_breadcrumbs', $breadcrumbs );
}

/**
 * This method is used to show breadcrumb on QRE shortcode pages.
 */
function ldrp_add_breadcrumbs() {
	$breadcrumbs = ldrp_get_breadcrumbs();
	if ( empty( $breadcrumbs ) ) {
		return;
	}
	echo '<div class=\'qre-breadcrumbs\'>';
	foreach ( $breadcrumbs as $breadcrumb ) {
		if ( empty( $breadcrumb['url'] ) ) {
			echo sprintf( '<span><strong>%s</strong></span>', esc_html( $breadcrumb['text'] ) );
			continue;
		}
		echo sprintf( '<a href="%1$s">%2$s</a>', esc_url( $breadcrumb['url'] ), esc_html( $breadcrumb['text'] ) );
	}
	echo '</div>';
}

/**
 * This method is used to fetch, process and display tabular information as per the input provided.
 *
 * @param  string  $query_type     Type of resource(user or quiz).
 * @param  integer $queried_obj_id Resource ID.
 * @param  string  $queried_string Search String.
 * @param  string  $date_filter    Type of time filter.
 * @param  string  $time_period    Relative time duration.
 * @param  string  $from_date      From Date.
 * @param  string  $to_date        To Date.
 * @param  integer $limit          Stats per page.
 * @param  integer $page           Page Number.
 * @param  string  $filter_nonce   Filter Nonce.
 * @return string Content HTML for Tabular Data.
 */
function ldrp_datatable_process_display( $query_type, $queried_obj_id, $queried_string, $date_filter, $time_period, $from_date, $to_date, $limit, $page, $filter_nonce = '' ) {
	global $ldrp_quiz_table_data;
	ob_start();
	$statistics = Quiz_Export_Data::instance()->get_filtered_statistics( $query_type, $queried_obj_id, $queried_string, $date_filter, $time_period, $from_date, $to_date, $limit, $page );

	$query_params = array(
		'search_result_type' => $query_type,
		'search_result_id'   => $queried_obj_id,
		'qre_search_field'   => $queried_string,
		'filter_type'        => $date_filter,
		'period'             => $time_period,
		'from_date'          => $from_date,
		'to_date'            => $to_date,
		'limit'              => empty( $limit ) ? 10 : $limit,
	);

	if ( is_wp_error( $statistics ) ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php echo esc_html( $statistics->get_error_message() ); ?>
			</div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	$total_count = (int) $statistics['total_count'];
	if ( empty( $page ) ) {
		$page = 1;
	}
	if ( empty( $limit ) ) {
		$limit = 10;
	}
	$pages = ceil( $total_count / $limit );
	if ( 0 === $pages ) {
		$pages = 1;
	}
	if ( $page > $pages ) {
		$page       = 1;
		$statistics = Quiz_Export_Data::instance()->get_filtered_statistics( $query_type, $queried_obj_id, $queried_string, $date_filter, $time_period, $from_date, $to_date, $limit, $page );
		if ( is_wp_error( $statistics ) ) {
			?>
			<div class="qre_nodata_container">
				<div>
					<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
					<?php echo esc_html( $statistics->get_error_message() ); ?>
				</div>
			</div>
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
	}
	$statistic_data = array_map(
		function( $statistic ) {
			if ( ! is_array( $statistic ) || ! array_key_exists( 'statistic_ref_id', $statistic ) ) {
				return '';
			}
			$current_user         = wp_get_current_user();
			$user_managed_courses = qre_get_user_managed_group_courses();

			$is_user_accessible = qre_check_if_user_accessible( $statistic['user_id'], $current_user, $user_managed_courses );
			if ( ! $is_user_accessible || is_wp_error( $is_user_accessible ) ) {
				return '';
			}
			$is_quiz_accessible = qre_check_if_quiz_accessible( learndash_get_quiz_id_by_pro_quiz_id( $statistic['quiz_id'] ), $current_user, $user_managed_courses );
			if ( ! $is_quiz_accessible || is_wp_error( $is_quiz_accessible ) ) {
				return '';
			}
			global $wp;

			$referer      = urlencode( get_permalink() );
			$query_string = filter_input( INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING );
			if ( ! empty( $query_string ) ) {
				$referer = urlencode( remove_query_arg( 'referer', add_query_arg( $query_string, '', home_url( $wp->request ) ) ) );
			}

			$data               = Quiz_Export_Db::instance()->get_statistic_summarized_data( $statistic['statistic_ref_id'] );
			$quiz_title         = get_the_title( learndash_get_quiz_id_by_pro_quiz_id( $statistic['quiz_id'] ) );
			$extradot           = strlen( substr( strip_tags( $quiz_title ), 0, 100 ) ) == 100 ? ' ...' : '';
			$data['quiz_title'] = "<a target='_blank' href='" . add_query_arg(
				array(
					'report'         => 'quiz',
					'screen'         => 'quiz',
					'user'           => $statistic['user_id'],
					'quiz'           => $statistic['quiz_id'],
					'statistic'      => $statistic['statistic_ref_id'],
					'referer'        => $referer,
					'ld_report_type' => 'quiz-reports',
				),
				''
			) . "'>" . substr( strip_tags( $quiz_title ), 0, 100 ) . $extradot . '</a>';

			$data['user_name']    = "<a target='_blank' href='" . add_query_arg(
				array(
					'report'         => 'quiz',
					'screen'         => 'user',
					'user'           => $statistic['user_id'],
					'referer'        => $referer,
					'ld_report_type' => 'quiz-reports',
				),
				''
			) . "'>" . get_userdata( $statistic['user_id'] )->display_name . '</a>';
			$data['date_attempt'] = date_i18n( get_option( 'date_format', 'd-M-Y' ), $statistic['create_time'] );
			/* translators: %1$d: Points Earned, %2$d: Total Points */
			$data['score'] = sprintf( __( '%1$d of %2$d', 'learndash-reports-pro' ), $data['points'], $data['gpoints'] );

			$dt_current         = new \DateTime( '@0' );
			$dt_after_seconds   = new \DateTime( '@' . (int) $data['question_time'] );
			$data['time_taken'] = $dt_current->diff( $dt_after_seconds )->format( '%H:%I:%S' );

			$data['link'] = "<a href='#' data-ref_id='" . $statistic['statistic_ref_id'] . "' class=\"qre-export qre-download-csv\"><img src='" . LDRP_PLUGIN_URL . 'assets/public/images/csv.svg' . "'/></a><a href='#' data-ref_id='" . $statistic['statistic_ref_id'] . "' class=\"qre-export qre-download-xlsx\"><img src='" . LDRP_PLUGIN_URL . 'assets/public/images/xls.svg' . "'/></a>";

			return $data;
		},
		$statistics
	);
	$statistic_data = remove_empty_array_items( $statistic_data );
	$data           = array();
	foreach ( $statistic_data as $statistic ) {
		$data[] = $statistic;
	}
	wp_localize_script(
		'qre_export_frontend',
		'quiz_statistics_data',
		array(
			'total'        => $total_count,
			'data'         => $data,
			'entries'      => count( $data ),
			'limit'        => $limit,
			'page'         => $page,
			'query_params' => $query_params,
			'no_data'      => __( 'No Data to Display.', 'learndash-reports-pro' ),
		)
	);
	$ldrp_quiz_table_data = array(
		'total'        => $total_count,
		'data'         => $data,
		'entries'      => count( $data ),
		'limit'        => $limit,
		'page'         => $page,
		'query_params' => $query_params,
		'no_data'      => __( 'No Data to Display.', 'learndash-reports-pro' ),
	);
	include LDRP_PLUGIN_DIR . 'includes/views/results-section.php';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * Show Report Listing Screen.
 *
 * @return string Content HTML for Listing Screen.
 */
function ldrp_show_report_listing_screen() {
	ob_start();
	echo '<div class="wrld-loader"></div><div class="qre-reports-content">';
	/* translators: %s: Quiz Label */
	echo '<h2>' . esc_html( sprintf( __( 'All Attempts Report', 'learndash-reports-pro' ) ) ) . '</h2>';

	if ( ! is_user_logged_in() ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php esc_html_e( 'You need to be logged in to access this page.', 'learndash-reports-pro' ); ?>
			</div>
		</div>
		<?php
		echo '</div>';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	?>
	<button class="wrld-bulk-export"><span class="dashicons dashicons-download"></span><?php esc_html_e( 'Bulk Export', 'learndash-reports-pro' ); ?></button>
	<?php
	$query_type     = filter_input( INPUT_GET, 'search_result_type', FILTER_SANITIZE_STRING );
	$queried_obj_id = filter_input( INPUT_GET, 'search_result_id', FILTER_VALIDATE_INT );
	$queried_string = filter_input( INPUT_GET, 'qre_search_field', FILTER_SANITIZE_STRING );
	$date_filter    = filter_input( INPUT_GET, 'filter_type', FILTER_SANITIZE_STRING );
	$time_period    = filter_input( INPUT_GET, 'period', FILTER_SANITIZE_STRING );
	$from_date      = filter_input( INPUT_GET, 'from_date', FILTER_SANITIZE_STRING );
	$to_date        = filter_input( INPUT_GET, 'to_date', FILTER_SANITIZE_STRING );
	$filter_nonce   = filter_input( INPUT_GET, 'qre_dashboard_filter_nonce', FILTER_SANITIZE_STRING );
	$limit          = filter_input( INPUT_GET, 'limit', FILTER_VALIDATE_INT );
	$page           = filter_input( INPUT_GET, 'pageno', FILTER_VALIDATE_INT );

	echo ldrp_datatable_process_display( $query_type, $queried_obj_id, $queried_string, $date_filter, $time_period, $from_date, $to_date, $limit, $page, $filter_nonce );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * Show Single Statistics Screen.
 *
 * @return string Content HTML for User Screen.
 */
function ldrp_show_single_statistic_screen() {
	ob_start();
	echo '<div class="wrld-loader"></div><div class="qre-reports-content">';
	if ( ! is_user_logged_in() ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php esc_html_e( 'You need to be logged in to access this page.', 'learndash-reports-pro' ); ?>
			</div>
		</div>
		<?php
		echo '</div';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	$user_id          = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT );
	$quiz_pro_id      = filter_input( INPUT_GET, 'quiz', FILTER_VALIDATE_INT );
	$statistic_ref_id = filter_input( INPUT_GET, 'statistic', FILTER_VALIDATE_INT );
	$user_id          = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT );
	$referer          = filter_input( INPUT_GET, 'referer', FILTER_VALIDATE_URL );
	$query_type       = 'post';
	$quiz_id          = learndash_get_quiz_id_by_pro_quiz_id( $quiz_pro_id );
	if ( empty( $referer ) ) {
		$referer = get_permalink();
	}
	?>
	<a class="button back-button" href="<?php echo esc_url( $referer ); ?>"><?php echo esc_html__( 'BACK', 'learndash-reports-pro' ); ?></a>
	<?php
	include LDRP_PLUGIN_DIR . 'includes/views/user-introduction.php';

	$resource_accessible = qre_check_if_accessible( $query_type, $quiz_id );
	if ( is_wp_error( $resource_accessible ) ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php echo esc_html( $resource_accessible->get_error_message() ); ?>
			</div>
		</div>
		<?php
		echo '</div';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	if ( ! $resource_accessible ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php esc_html_e( 'You do not have sufficient privileges to view this information.', 'learndash-reports-pro' ); ?>
			</div>
		</div>
		<?php
		echo '</div';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	$quiz_title       = get_the_title( $quiz_id );
	$attempt_data     = Quiz_Export_Db::instance()->get_statistic_summarized_data( $statistic_ref_id );
	$dt_current       = new \DateTime( '@0' );
	$dt_after_seconds = new \DateTime( '@' . (int) $attempt_data['question_time'] );
	$time_taken       = $dt_current->diff( $dt_after_seconds )->format( '%H:%I:%S' );
	$percentage       = 0;
	if ( (int) $attempt_data['gpoints'] > 0 ) {
		$percentage = (int) $attempt_data['points'] / (int) $attempt_data['gpoints'] * 100;
	}

	$quiz_post_settings = learndash_get_setting( $quiz_id );
	if ( ! is_array( $quiz_post_settings ) ) {
		$quiz_post_settings = array();
	}
	if ( ! isset( $quiz_post_settings['passingpercentage'] ) ) {
		$quiz_post_settings['passingpercentage'] = 0;
	}

	$passingpercentage = (float) number_format( $quiz_post_settings['passingpercentage'], 2 );
	$percentage        = (float) number_format( $percentage, 2 );
	$pass              = ( $percentage >= $passingpercentage ) ? __( 'PASS', 'learndash-reports-pro' ) : __( 'FAIL', 'learndash-reports-pro' );
	$current_user      = wp_get_current_user();
	if ( in_array( 'group_leader', (array) $current_user->roles ) ) {// phpcs:ignore
		$class_average = Quiz_Export_Data::instance()->get_group_users_average( $quiz_pro_id );
	} else {
		$class_average = Quiz_Export_Data::instance()->get_quiz_class_average( $quiz_pro_id );
	}
	?>
	<div class="quiz-title-container">
		<div class="quiz-title-label">
			<span class="label">
				<?php
				/* translators: %s: Quiz Label */
				echo esc_html( sprintf( __( '%s Title', 'learndash-reports-pro' ), learndash_get_custom_label( 'quiz' ) ) );
				?>
			</span>
		</div>
		<div class="quiz-title">
			<span><?php echo esc_html( $quiz_title ); ?></span>
		</div>
	</div>
	<div class="download-report">
		<span><?php echo esc_html__( 'Download Report', 'learndash-reports-pro' ); ?></span>
		<a href="#" data-ref_id="<?php echo esc_attr( $statistic_ref_id ); ?>" class="qre-export qre-download-csv">
			<img src="<?php echo esc_url( LDRP_PLUGIN_URL . 'assets/public/images/csv.svg' ); ?>" />
		</a>
		<a href="#" data-ref_id="<?php echo esc_attr( $statistic_ref_id ); ?>" class="qre-export qre-download-xlsx">
			<img src="<?php echo esc_url( LDRP_PLUGIN_URL . 'assets/public/images/xls.svg' ); ?>"/>
		</a>
	</div>
	<?php
	include LDRP_PLUGIN_DIR . 'includes/views/attempt-summary.php';
	Quiz_Reporting_Frontend::instance()->display_attempted_questions( $user_id, $quiz_pro_id, $statistic_ref_id );
	echo '</div';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * Show User Statistics Screen.
 *
 * @return string Content HTML for User Screen.
 */
function ldrp_show_user_statistics_screen() {
	ob_start();
	echo '<div class="wrld-loader"></div><div class="qre-reports-content">';
	if ( ! is_user_logged_in() ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php esc_html_e( 'You need to be logged in to access this page.', 'learndash-reports-pro' ); ?>
			</div>
		</div>
		<?php
		echo '</div';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	$user_id        = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT );
	$limit          = filter_input( INPUT_GET, 'limit', FILTER_VALIDATE_INT );
	$page           = filter_input( INPUT_GET, 'pageno', FILTER_VALIDATE_INT );
	$referer        = filter_input( INPUT_GET, 'referer', FILTER_VALIDATE_URL );
	$query_type     = 'user';
	$queried_string = '';
	$time_period    = false;
	$from_date      = false;
	$to_date        = false;
	$date_filter    = false;
	if ( empty( $referer ) ) {
		$referer = get_permalink();
	}
	?>
	<a class="button back-button" href="<?php echo esc_url( $referer ); ?>"><?php echo esc_html__( 'BACK', 'learndash-reports-pro' ); ?></a>
	<input type="hidden" name="user" value="<?php echo esc_attr( $user_id ); ?>" />
	<input type="hidden" name="screen" value="user" />
	<input type="hidden" name="report" value="quiz" />
	<input type="hidden" name="referer" value="<?php echo esc_url( $referer ); ?>">
	<?php
	include LDRP_PLUGIN_DIR . 'includes/views/user-introduction.php';

	$resource_accessible = qre_check_if_accessible( $query_type, $user_id );
	if ( is_wp_error( $resource_accessible ) ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php echo esc_html( $resource_accessible->get_error_message() ); ?>
			</div>
		</div>
		<?php
		echo '</div';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	if ( ! $resource_accessible ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php esc_html_e( 'You do not have sufficient privileges to view this information.', 'learndash-reports-pro' ); ?>
			</div>
		</div>
		<?php
		echo '</div';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	echo ldrp_datatable_process_display( $query_type, $user_id, $queried_string, $date_filter, $time_period, $from_date, $to_date, $limit, $page );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * Show Custom Reports Export Screen.
 *
 * @param  integer $page_number Page Number for Pagination.
 *
 * @return string Content HTML for Custom Reports Screen.
 */
function ldrp_show_custom_reports_screen( $page_number ) {
	ob_start();
	if ( ! is_user_logged_in() ) {
		?>
		<div class="qre_nodata_container">
			<div>
				<strong><?php esc_html_e( 'Access Denied.', 'learndash-reports-pro' ); ?></strong>
				<?php esc_html_e( 'You need to be logged in to access this page.', 'learndash-reports-pro' ); ?>
			</div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	global $learndash_question_types;
	if ( empty( $learndash_question_types ) ) {
		$learndash_question_types = array(
			'single'             => esc_html__( 'Single choice', 'learndash' ),
			'multiple'           => esc_html__( 'Multiple choice', 'learndash' ),
			'free_answer'        => esc_html__( '"Free" choice', 'learndash' ),
			'sort_answer'        => esc_html__( '"Sorting" choice', 'learndash' ),
			'matrix_sort_answer' => esc_html__( '"Matrix Sorting" choice', 'learndash' ),
			'cloze_answer'       => esc_html__( 'Fill in the blank', 'learndash' ),
			'assessment_answer'  => esc_html__( 'Assessment', 'learndash' ),
			'essay'              => esc_html__( 'Essay / Open Answer', 'learndash' ),
		);
	}

	$categories     = maybe_unserialize( get_option( 'learndash_settings_courses_taxonomies', false ) );
	$filter_options = maybe_unserialize( get_user_meta( get_current_user_id(), 'qre_custom_reports_saved_query', true ) );
	$defaults       = array(
		'course_filter'      => -1,
		'enrollment_from'    => false,
		'enrollment_to'      => false,
		'completion_from'    => false,
		'completion_to'      => false,
		'course_title'       => 'yes',
		'completion_status'  => 'yes',
		'completion_date'    => false,
		'course_category'    => false,
		'enrollment_date'    => false,
		'course_progress'    => false,
		'group_filter'       => -1,
		'group_name'         => false,
		'user_name'          => 'yes',
		'user_email'         => false,
		'user_first_name'    => false,
		'user_last_name'     => false,
		'quiz_filter'        => -1,
		'quiz_status'        => 'yes',
		'quiz_title'         => 'yes',
		'quiz_category'      => 'yes',
		'quiz_points_total'  => 'yes',
		'quiz_points_earned' => 'yes',
		'quiz_score_percent' => 'yes',
		'date_of_attempt'    => 'yes',
		'time_taken'         => 'yes',
		'question_text'      => 'yes',
		'question_options'   => 'yes',
		'correct_answers'    => 'yes',
		'user_answers'       => 'yes',
		'question_type'      => 'yes',
		'start_date'         => strtotime( '-1 month' ),
		'end_date'           => current_time( 'timestamp' ),
	);

	if ( empty( $filter_options ) ) {
		$filter_options = $defaults;
	} else {
		foreach ( $defaults as $keys => $default ) {
			if ( in_array( $keys, array( 'course_filter', 'quiz_filter', 'group_filter' ), true ) ) {
				if ( ! array_key_exists( $keys, $filter_options ) ) {
					$filter_options[ $keys ] = $default;
					continue;
				}
			}
			if ( ! array_key_exists( $keys, $filter_options ) ) {
				$filter_options[ $keys ] = false;
			}
		}
	}
	$courses_label             = learndash_get_custom_label( 'courses' );
	$course_label              = learndash_get_custom_label( 'course' );
	$groups_label              = learndash_get_custom_label( 'groups' );
	$group_label               = learndash_get_custom_label( 'group' );
	$quizzes_label             = learndash_get_custom_label( 'quizzes' );
	$quiz_label                = learndash_get_custom_label( 'quiz' );
	$course_ids                = array();
	$excluded_courses          = get_option( 'exclude_courses', false );
	$group_ids                 = array();
	$user                      = wp_get_current_user();
	$page_size                 = 10;
	$selected_users_for_groups = null;

	// For Group Admins, only show data of users of the groups where they are the leaders.
	if ( ! in_array( 'administrator', (array) $user->roles, true ) && in_array( 'group_leader', (array) $user->roles, true ) ) {
		$associated_groups         = learndash_get_administrators_group_ids( $user->ID );
		$selected_users_for_groups = array();
		foreach ( $associated_groups as $group ) {
			$group_users = learndash_get_groups_user_ids( $group );
			if ( ! empty( $group_users ) ) {
				$selected_users_for_groups = array_merge( $selected_users_for_groups, $group_users );
			}
		}
		$selected_users_for_groups = empty( $selected_users_for_groups ) ? null : $selected_users_for_groups;
	}

	if ( -1 == $filter_options['course_filter'] && -1 == $filter_options['group_filter'] ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			if ( in_array( 'group_leader', (array) $user->roles, true ) ) {
				$course_ids = qre_get_user_managed_group_courses();
			}
			if ( function_exists( 'ir_get_instructor_complete_course_list' ) && in_array( 'wdm_instructor', (array) $user->roles, true ) ) {
				$course_ids = array_merge( $course_ids, ir_get_instructor_complete_course_list( $user->ID ) );
			}
			$course_ids = array_merge( $course_ids, learndash_user_get_enrolled_courses( $user->ID, array(), true ) );
		} else {
			$course_ids = get_posts(
				array(
					'post_type'      => 'sfwd-courses',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'fields'         => 'ids',
				)
			);
			$course_ids = array_values( $course_ids );
		}
		if ( ! empty( $excluded_courses ) ) {
			$course_ids = array_diff( $course_ids, $excluded_courses );
		}
		$course_ids = Quiz_Export_Db::instance()->get_posts_within_ids( 'sfwd-courses', $course_ids );
	} elseif ( -1 == $filter_options['course_filter'] && -1 != $filter_options['group_filter'] ) {
		$gadmins = learndash_get_groups_administrator_ids( $filter_options['group_filter'] );
		if ( in_array( $user->ID, $gadmins ) || in_array( 'administrator', (array) $user->roles, true ) ) {
			$course_ids                = learndash_group_enrolled_courses( $filter_options['group_filter'] );
			$selected_users_for_groups = learndash_get_groups_user_ids( $filter_options['group_filter'] );
			if ( ! empty( $excluded_courses ) ) {
				$course_ids = array_diff( $course_ids, $excluded_courses );
			}
		}
	} else {
		if ( -1 != $filter_options['group_filter'] ) {
			$selected_users_for_groups = learndash_get_groups_user_ids( $filter_options['group_filter'] );
		}

		if ( -1 != $filter_options['course_filter'] && $filter_options['course_filter'] > 0 ) {
			$course_ids = array( $filter_options['course_filter'] );
			if ( ! empty( $excluded_courses ) ) {
				$course_ids = array_diff( $course_ids, $excluded_courses );
			}
		}
	}

	if ( -1 == $filter_options['quiz_filter'] ) {
		$courses = array_map(
			function( $course_id ) {
				if ( is_array( $course_id ) ) {
					$course_id = current( $course_id );
				}
				$quiz_ids = learndash_course_get_steps_by_type( $course_id, 'sfwd-quiz' );
				return array(
					'post' => get_post( $course_id ),
					'quiz' => $quiz_ids,
				);
			},
			$course_ids
		);
	} else {
		$courses = array_map(
			function( $course_id ) use ( $filter_options ) {
				if ( is_array( $course_id ) ) {
					$course_id = current( $course_id );
				}
				$quiz_ids = learndash_course_get_steps_by_type( $course_id, 'sfwd-quiz' );
				if ( ! in_array( $filter_options['quiz_filter'], $quiz_ids ) ) {
					return '';
				}
				return array(
					'post' => get_post( $course_id ),
					'quiz' => array( $filter_options['quiz_filter'] ),
				);
			},
			$course_ids
		);
		$courses = remove_empty_array_items( $courses );
		if ( empty( $courses ) ) {
			// Map the Quiz to the dummy course named 'Quizes (not linked to any course)'.
			$post                 = new \stdClass();
			$post->ID             = -99;
			$post->post_author    = 1;
			$post->post_date      = current_time( 'mysql' );
			$post->post_date_gmt  = current_time( 'mysql', 1 );
			$post->post_title     = __( 'Quizzes (not linked to any course)', 'learndash-reports-pro' );
			$post->post_status    = 'publish';
			$post->comment_status = 'closed';
			$post->ping_status    = 'closed';
			$post->post_name      = 'quizes' . wp_rand( 1, 99999 ); // append random number to avoid clash.
			$post->post_type      = 'sfwd-courses';
			$post->filter         = 'raw'; // important!
			$courses[]            = array(
				'post' => $post,
				'quiz' => array( $filter_options['quiz_filter'] ),
			);
		}
	}

	?>
	<div class="wrld-loader"></div>
	<div class="custom-reports-content">
		<?php
		echo '<h2>' . esc_html( __( 'All Attempts Report', 'learndash-reports-pro' ) ) . '</h2>';

		?>
		<button class="wrld-bulk-export"><span class="dashicons dashicons-download"></span><?php esc_html_e( 'Bulk Export', 'learndash-reports-pro' ); ?></button>
		<div class="custom-reports-container">
			<div class="table-container">
				<table id="custom-reports">
					<tbody>
						<tr class="row_headings">
							<th></th>
							<?php if ( 'yes' == $filter_options['user_name'] ) : ?>
								<th class="table-user"><?php echo esc_html__( 'Username', 'learndash-reports-pro' ); ?></th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['quiz_title'] ) : ?>
								<th class="table-quiz"><?php echo esc_html( $quiz_label ); ?></th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['course_title'] ) : ?>
								<th class="table-course"><?php echo esc_html( $course_label ); ?></th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['course_category'] ) : ?>
								<th class="table-category">
									<?php
									/* translators: %s: Course Label */
									echo esc_html( sprintf( _x( '%s Category', 'Course Label', 'learndash-reports-pro' ), $course_label ) );
									?>
								</th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['group_name'] ) : ?>
								<th class="table-category"><?php echo esc_html( $group_label ); ?></th>
							<?php endif; ?>

							<?php if ( -1 != $filter_options['group_filter'] && 'yes' == $filter_options['group_name'] ) : ?>
								<th><?php echo esc_html( $group_label ); ?></th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['user_email'] ) : ?>
								<th class="table-email"><?php echo esc_html__( 'User Email', 'learndash-reports-pro' ); ?></th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['quiz_category'] ) : ?>
								<th>
									<?php
									/* translators: %s : Quiz Label */
									echo esc_html( sprintf( __( '%s Category', 'learndash-reports-pro' ), $quiz_label ) );
									?>
								</th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['quiz_points_earned'] ) : ?>
								<th><?php echo esc_html__( 'Earned Points', 'learndash-reports-pro' ); ?></th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['quiz_score_percent'] ) : ?>
								<th><?php echo esc_html__( 'Score (in %)', 'learndash-reports-pro' ); ?></th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['date_of_attempt'] ) : ?>
								<th><?php echo esc_html__( 'Date of Attempt', 'learndash-reports-pro' ); ?></th>
							<?php endif; ?>
							<?php if ( 'yes' == $filter_options['time_taken'] ) : ?>
								<th><?php echo esc_html__( 'Time Taken', 'learndash-reports-pro' ); ?></th>
							<?php endif; ?>
						</tr>
						<?php
						if ( empty( $courses ) ) {
							?>
							<tr>
								<th colspan="20" class="no-data-msg"><?php esc_html_e( 'No data available', 'learndash-reports-pro' ); ?></th>
							</tr>
							</tbody></table></div></div>
							<?php
							$content = ob_get_contents();
							ob_end_clean();
							return $content;
						}
						$count            = 0;
						$quiz_pro_ids_all = array();
						foreach ( $courses as $key => $course ) {
							$quiz_pro_ids                    = array_map(
								function( $quiz ) {
									return get_post_meta( $quiz, 'quiz_pro_id', true );
								},
								$course['quiz']
							);
							$courses[ $key ]['quiz_pro_ids'] = $quiz_pro_ids;
							$quiz_pro_ids_all                = array_merge( $quiz_pro_ids_all, $quiz_pro_ids );
							if ( check_if_user_is_course_admin( $course['post']->ID ) ) {
								$courses[ $key ]['user_ids'] = $selected_users_for_groups;
							} else {
								$courses[ $key ]['user_ids'] = array( get_current_user_id() );
							}
						}
						$statistics = Quiz_Export_Db::instance()->get_crossreferenced_statistics( $courses, $filter_options['start_date'], $filter_options['end_date'], $page_size, $page_number );
						$count      = Quiz_Export_Db::instance()->get_crossreferenced_statistics_count( $courses, $filter_options['start_date'], $filter_options['end_date'] );
						if ( empty( $statistics ) ) {
							?>
							<tr>
								<th colspan="20" class="no-data-msg"><?php esc_html_e( 'No data available', 'learndash-reports-pro' ); ?></th>
							</tr>
							</tbody></table></div></div>
							<?php
							$content = ob_get_contents();
							ob_end_clean();
							return $content;
						}
						foreach ( $statistics as $statistic ) {
							$statistic      = qre_get_statistic_data( $statistic );
							$questions      = Quiz_Export_Data::instance()->get_statistics_data( $statistic['quiz_id'], $statistic['user_id'], $statistic['statistic_ref_id'], false );
							$current_course = get_post( $statistic['course_post_id'] );
							$groups_str     = '-';
							$group_ids      = learndash_get_course_groups( $current_course->ID );
							$user           = wp_get_current_user();

							if ( ! current_user_can( 'manage_options' ) ) {
								if ( in_array( 'group_leader', (array) $user->roles, true ) ) {
									$associated_groups = learndash_get_administrators_group_ids( get_current_user_id() );
									foreach ( $group_ids as $key => $group ) {
										if ( ! in_array( $group, $associated_groups ) ) {
											unset( $group_ids[ $key ] );
										}
									}
								}
							}
							if ( ! empty( $group_ids ) ) {
								$groups     = array_map(
									function( $group_id ) {
										return str_replace( '&#8211;', '-', get_the_title( $group_id ) );
									},
									$group_ids
								);
								$groups_str = implode( ', ', $groups );
							}
							?>
							<tr class="row_data">
								<td><span class="accordion-trigger"></span></td>
								<td><?php echo $statistic['user_name'];// phpcs:ignore ?>
									<?php if ( 'yes' === $filter_options['quiz_status'] ) : ?>
										<br><span class="passing-status <?php echo esc_attr( strtolower( $statistic['pass_status'] ) ); ?>"><?php echo esc_html( $statistic['pass_status'] ); ?></span>
									<?php endif; ?>
								</td>
								<?php if ( 'yes' == $filter_options['quiz_title'] ) : ?>
									<td><?php echo $statistic['quiz_title'];// phpcs:ignore ?></td>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['course_title'] ) : ?>
									<td><?php echo $current_course->post_title;// phpcs:ignore ?></td>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['course_category'] ) : ?>
									<?php
									$category        = wp_get_post_terms( $current_course->ID, 'ld_course_category', array( 'fields' => 'names' ) );
									$course_category = '';
									if ( ! is_wp_error( $category ) ) {
										$course_category = implode( ', ', $category );
									}
									echo sprintf( '<td>%s</td>', esc_html( $course_category ) );
									?>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['group_name'] ) : ?>
									<td><?php echo esc_html( $groups_str ); ?></td>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['user_email'] ) : ?>
									<td><?php echo esc_html( get_userdata( $statistic['user_id'] )->user_email ); ?></td>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['quiz_category'] ) : ?>
									<td><?php echo esc_html( $statistic['quiz_category'] ); ?></td>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['quiz_points_earned'] ) : ?>
									<td><?php echo esc_html( $statistic['points'] ); ?></td>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['quiz_score_percent'] ) : ?>
									<td><?php echo esc_html( floatval( number_format( $statistic['percentage'], 2, '.', '' ) ) . '%' ); ?></td>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['date_of_attempt'] ) : ?>
									<td><?php echo esc_html( date_i18n( 'd M, Y', $statistic['create_time'] ) ); ?></td>
								<?php endif; ?>
								<?php if ( 'yes' == $filter_options['time_taken'] ) : ?>
									<td><?php echo esc_html( date_i18n( 'H:i:s', $statistic['quiz_time'] ) ); ?></td>
								<?php endif; ?>
							</tr>
							<tr class="accordion-target collapse">
								<td></td>
								<td colspan="6">
									<div class="flex-wrapper">
										<div>
											<strong>
												<?php
												/* translators: %d: Question Count   */
												echo esc_html( sprintf( __( 'List of %d Questions', 'learndash-reports-pro' ), count( ...array_column( $questions, 'questions' ) ) ) );
												?>
											</strong>
										</div>
										<div>
											<strong><?php echo esc_html__( 'Answer Status: ', 'learndash-reports-pro' ); ?></strong>
											<span class="list correct" style="color: #1AB900; margin-right: 20px; margin-left: 7px;"><?php echo esc_html__( 'Correct', 'learndash-reports-pro' ); ?></span>
											<span class="list incorrect" style="color: #FF0000;"><?php echo esc_html__( 'Incorrect', 'learndash-reports-pro' ); ?></span>
										</div>
									</div>
									<div class="answer-list">
										<?php foreach ( array_merge( ...array_column( $questions, 'questions' ) ) as $key => $question ) : ?>
											<span class="<?php echo ( 1 == $question['correct'] ) ? 'correct' : 'incorrect'; ?>" data-question="<?php echo esc_attr( $key + 1 ); ?>" onclick="ShowRelatedInformation( event );"><?php echo esc_html( $key + 1 ); ?></span>
										<?php endforeach; ?>
									</div>
									<div class="qre-question-container">
										<h2><?php echo esc_html__( 'Question Response Report', 'learndash-reports-pro' ); ?></h2>
										<?php foreach ( array_merge( ...array_column( $questions, 'questions' ) ) as $key => $question ) : ?>
											<div class="question-details" data-question="<?php echo esc_attr( $key + 1 ); ?>">
												<div class="outer-1">
													<div class="inner-1">
														<span>
															<strong><?php echo esc_html__( 'User Name:', 'learndash-reports-pro' ); ?></strong>
															<br />
															<?php echo $statistic['user_name'];// phpcs:ignore ?>
														</span>
														<span>
															<strong><?php echo esc_html__( 'Quiz Title:', 'learndash-reports-pro' ); ?></strong>
															<br />
															<?php echo $statistic['quiz_title'];// phpcs:ignore ?>
														</span>
														<?php if ( 'yes' == $filter_options['user_first_name'] ) : ?>
															<span>
																<strong><?php echo esc_html__( 'First Name:', 'learndash-reports-pro' ); ?></strong>
																<br />
																<?php echo esc_html( get_userdata( $statistic['user_id'] )->first_name ); ?>
															</span>
														<?php endif; ?>
														<?php if ( 'yes' == $filter_options['user_last_name'] ) : ?>
															<span>
																<strong><?php echo esc_html__( 'Last Name:', 'learndash-reports-pro' ); ?></strong>
																<br />
																<?php echo esc_html( get_userdata( $statistic['user_id'] )->last_name ); ?>
															</span>
														<?php endif; ?>
														<?php if ( 'yes' == $filter_options['quiz_score_percent'] ) : ?>
															<span>
																<strong><?php echo esc_html__( 'Score(in %):', 'learndash-reports-pro' ); ?></strong>
																<br />
																<?php echo floatval( number_format( $statistic['percentage'], 2, '.', '' ) ) . '%'; ?>
															</span>
														<?php endif; ?>
													</div>
													<div class="close-icon" onclick="closeRelatedModal()">&times;</div>
												</div>
												<div class="outer-2">
													<?php if ( 'yes' == $filter_options['question_type'] ) : ?>
														<span>
															<strong><?php echo esc_html__( 'Question Type:', 'learndash-reports-pro' ); ?></strong>
															<br />
															<?php echo esc_html( $learndash_question_types[ $question['answerType'] ] ); ?>
														</span>
													<?php endif; ?>
													<span>
														<strong><?php echo esc_html__( 'Question Category:', 'learndash-reports-pro' ); ?></strong>
														<br />
														<?php
															$category_mapper = new WpProQuiz_Model_CategoryMapper();
															$category_model  = $category_mapper->fetchById( $question['questionModel']->getCategoryId() );
															echo esc_html( ! empty( $category_model->getCategoryName() ) ? $category_model->getCategoryName() : __( 'No category', 'quiz_reporting_learndash' ) );
														?>
														</span>
												</div>
												<div class="outer-3">
													<div class="learndash">
														<div class="learndash-wrapper">
															<div class="wpProQuiz_content">
																<div class="wpProQuiz_quiz">
																	<!-- <span class="questions_heading"> -->
																	<?php
																		/*
																		 translators: %d: Question Number */
																		// echo esc_html( sprintf( __( 'Question %d', 'learndash-reports-pro' ), $key + 1 ) );
																	?>
																	<!-- </span> -->
																	<?php
																	$cmsg = Quiz_Export_Data::instance()->get_correct_message( learndash_get_question_post_by_pro_id( $question['question_id'] ), $question['question_id'] );
																	echo Quiz_Reporting_Frontend::instance()->show_user_answer( $question['questionName'], $question['questionAnswerData'], $question['statistcAnswerData'], $question['answerType'], $key + 1, $question['correct'], $cmsg );// phpcs:ignore
																	// $this->display_question_options( $question, $key+1 );
																	?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
										<div class="inner-pagination"></div>
									</div>
									<script>
										function ShowRelatedInformation( evnt ) {
											var $this          = jQuery( evnt.target );
											var question_index = $this.attr( 'data-question' );
											setTimeout( function() {
												if ( ! $this.parent().hasClass( '.inner-pagination' ) ) {
													$this.parents( '.accordion-target' ).find( '.qre-question-container .inner-pagination' ).html( $this.parents('.answer-list').html() );
												}
												jQuery( '.qre-backdrop' ).show();
												jQuery( 'body' ).addClass('wrld-open');
												$this.parents( '.accordion-target' ).find( '.qre-question-container' ).show();
												$this.parents( '.accordion-target' ).find( '.question-details' ).each( function( ind, el ){
													if ( jQuery( el ).attr( 'data-question' ) == question_index ) {
														jQuery( el ).show();
													} else {
														jQuery( el ).hide();
													}
												});
											}, 500 );
										}
										function closeRelatedModal() {
											setTimeout(function(){
												jQuery( '.accordion-target .qre-question-container' ).hide();
												jQuery( '.qre-backdrop' ).hide();
												jQuery( 'body' ).removeClass('wrld-open');
											}, 500);
										}
									</script>
								</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="pagination-section">
				<?php
				$current_page = max( 1, $page_number );
				$total_pages  = ceil( $count / $page_size );
				$big          = 999999999; // need an unlikely integer.
				?>
				<?php if ( 1 != $total_pages ) : ?>
					<a href="
					<?php
					echo esc_url(
						add_query_arg(
							array(
								'report'         => 'custom',
								'paged'          => $page_number - 1,
								'ld_report_type' => 'quiz-reports',
							),
							get_permalink()
						)
					);
					?>
								" class="previous-page button <?php echo ( 1 == $page_number ? 'disabled' : '' ); ?>"><?php echo esc_html__( 'Previous', 'learndash-reports-pro' ); ?></a>
				<?php endif; ?>
				<?php
				echo paginate_links(// phpcs:ignore
					array(
						'base'           => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'         => '?paged=%#%',
						'current'        => $current_page,
						'total'          => $total_pages,
						'ld_report_type' => 'quiz-reports',
					)
				);
				?>
				<?php if ( 1 != $total_pages ) : ?>
					<a href="
					<?php
					echo esc_url(
						add_query_arg(
							array(
								'report'         => 'custom',
								'paged'          => $page_number + 1,
								'ld_report_type' => 'quiz-reports',
							),
							get_permalink()
						)
					);
					?>
								" class="next-page button <?php echo ( $page_number == $total_pages ? 'disabled' : '' ); ?>"><?php echo esc_html__( 'Next', 'learndash-reports-pro' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="qre-backdrop"></div>
	<?php

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * This function is used to render the quiz reports content.
 *
 * @return string The HTML content for Quiz Reports.
 */
function ldrp_quiz_reports_handler() {
	if ( isset( $_GET['_locale'] ) || is_admin() ) {// phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	ldrp_enqueue_shortcode_assets();
	ob_start();
	ldrp_add_breadcrumbs();
	$report_type = filter_input( INPUT_GET, 'report', FILTER_SANITIZE_STRING );
	if ( empty( $report_type ) || ! in_array( $report_type, array( 'quiz', 'custom' ), true ) ) {
		$report_type = 'quiz';
	}
	$screen_type = filter_input( INPUT_GET, 'screen', FILTER_SANITIZE_STRING );
	if ( empty( $screen_type ) || ! in_array( $screen_type, array( 'user', 'quiz' ), true ) ) {
		$screen_type = 'listing';
	}
	if ( 'quiz' === $report_type ) {
		switch ( $screen_type ) {
			case 'listing':
				echo ldrp_show_report_listing_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'quiz':
				echo ldrp_show_single_statistic_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'user':
				echo ldrp_show_user_statistics_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
		}
	} elseif ( 'custom' === $report_type ) {
		echo ldrp_show_custom_reports_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

if ( ! function_exists( 'ldrp_pro_onboarding_modal' ) ) {
	function ldrp_pro_onboarding_modal() {
		$screen = get_current_screen();
		if ( ! empty( $screen ) && 'plugins' !== $screen->base && 'update' !== $screen->base ) {
			return; // not on admin plugins page
		}

		$visited_settings_page = get_option( 'wrld_settings_page_visited', false );
		if ( 'pro' == $visited_settings_page ) {
			return; // user knows about setting page.
		}

		if ( version_compare( WRLD_PLUGIN_VERSION, LDRP_RECOMENDED_FREE_PLUGIN_VERSION, '<' ) ) {
			return; // latest or recomendnded pro version is installed.
		}

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			wp_enqueue_style( 'wrld_modal_style', plugins_url( 'assets/admin/css/wrld-modal.css', __FILE__ ), array(), LDRP_PLUGIN_VERSION );
			wp_enqueue_script( 'wrld_modal_script', plugins_url( 'assets/admin/js/wrld-modal.js', __FILE__ ), array( 'jquery' ), LDRP_PLUGIN_VERSION );
			wp_localize_script( 'wrld_modal_script', 'wrld_modal_script_object', array( 'wp_ajax_url' => admin_url( 'admin-ajax.php' ) ) );

			$modal_head              = __( 'Welcome to WISDM Reports PRO for LearnDash!', 'learndash-reports-pro' );
			$modal_description       = __( 'The Free version of the WISDM Reports for LearnDash will automatically get installed and activated on the site from WordPress.org as part of the installation process if not installed.', 'learndash-reports-pro' );
			$modal_action_text       = __( 'Go ahead!', 'learndash-reports-pro' );
			$info_url                = 'admin.php?page=wrld-dashboard-page';
			$action_close            = '';
			$plugin_first_activation = get_option( 'wrld_free_plugin_first_activated', false );

			if ( $plugin_first_activation && ! is_array( $plugin_first_activation ) ) {
				// pluginu updated to v1.2.0
				$modal_description = __( 'The plugin has been updated successfully.', 'learndash-reports-pro' );
				$modal_action_text = __( 'Go Ahead!', 'learndash-reports-pro' );
			}

			$wp_nonce = wp_create_nonce( 'reports-firrst-install-modal' );
			include_once LDRP_PLUGIN_DIR . '/includes/templates/admin-modal.php';
		}
	}
}

if ( ! function_exists( 'ldrp_pro_update_free_modal' ) ) {
	function ldrp_pro_update_free_modal() {
		$screen = get_current_screen();
		if ( ! empty( $screen ) && 'plugins' !== $screen->base && 'update' !== $screen->base ) {
			return; // not on admin plugins page
		}

		if ( version_compare( WRLD_PLUGIN_VERSION, LDRP_RECOMENDED_FREE_PLUGIN_VERSION, '>=' ) ) {
			return; // latest or recomendnded pro version is installed.
		}

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			wp_enqueue_style( 'wrld_modal_style', plugins_url( 'assets/admin/css/wrld-modal.css', __FILE__ ), array(), LDRP_PLUGIN_VERSION );
			wp_enqueue_script( 'wrld_modal_script', plugins_url( 'assets/admin/js/wrld-modal.js', __FILE__ ), array( 'jquery' ), LDRP_PLUGIN_VERSION );
			wp_localize_script( 'wrld_modal_script', 'wrld_modal_script_object', array( 'wp_ajax_url' => admin_url( 'admin-ajax.php' ) ) );

			$modal_head        = __( 'Welcome to WISDM Reports PRO for LearnDash!', 'learndash-reports-pro' );
			$modal_description = __( 'To complete the update process, you will first need to update the WISDM Reports FREE for LearnDash to its latest version from the “All Plugins” Page of your WordPress Dashboard.', 'learndash-reports-pro' );
			$modal_action_text = __( 'Got It!', 'learndash-reports-pro' );
			$info_url          = '#';
			$action_close      = 'update-free';

			$wp_nonce = wp_create_nonce( 'reports-firrst-install-modal' );
			include_once LDRP_PLUGIN_DIR . '/includes/templates/admin-modal.php';
		}

	}
}

if ( ! function_exists( 'ldrp_pro_update_student_dashboard_modal' ) ) {
	function ldrp_pro_update_student_dashboard_modal() {
		$screen = get_current_screen();
		if ( ! empty( $screen ) && 'plugins' !== $screen->base && 'update' !== $screen->base ) {
			return; // not on admin plugins page
		}

		if ( version_compare( WRLD_PLUGIN_VERSION, LDRP_RECOMENDED_FREE_PLUGIN_VERSION, '<' ) ) {
			return; // latest or recomendnded pro version is installed.
		}

		$is_onboarding           = get_option( 'wrld_onboarded_student_dashboard_introduction', false );
		$plugin_first_activation = get_option( 'wrld_free_plugin_first_activated', false );

		if ( ! $plugin_first_activation ) {
			return;
		}

		/*
		$reporting_page = get_option( 'ldrp_student_page', false );

		if ( $reporting_page ) {
			update_option( 'wrld_onboarded_student_dashboard_introduction', 'done' );
			return;
		}*/

		if ( $is_onboarding ) {
			return;
		}
		update_option( 'wrld_onboarded_student_dashboard_introduction', 'done' );

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			wp_enqueue_style( 'wrld_modal_style', plugins_url( 'assets/admin/css/wrld-modal.css', __FILE__ ), array(), LDRP_PLUGIN_VERSION );
			wp_enqueue_script( 'wrld_modal_script', plugins_url( 'assets/admin/js/wrld-modal.js', __FILE__ ), array( 'jquery' ), LDRP_PLUGIN_VERSION );
			wp_localize_script( 'wrld_modal_script', 'wrld_modal_script_object', array( 'wp_ajax_url' => admin_url( 'admin-ajax.php' ) ) );

			$modal_head        = __( 'Introducing the Student Quiz Reports page!', 'learndash-reports-pro' );
			$modal_description = __( 'An easy to use page that allows learners to analyze past quiz results and improve their performance accordingly.', 'learndash-reports-pro' );
			$modal_action_text = __( 'Let\'s get started!', 'learndash-reports-pro' );
			$info_url          = 'admin.php?page=wrld-dashboard-page';
			$action_close      = '';

			$wp_nonce = wp_create_nonce( 'reports-firrst-install-modal' );
			include_once LDRP_PLUGIN_DIR . '/includes/templates/admin-modal.php';
		}
	}
}

if ( ! function_exists( 'ldrp_add_recomendation_notice' ) ) {
	function ldrp_add_recomendation_notice() {
		if ( current_user_can( 'manage_options' ) && defined( 'WRLD_PLUGIN_VERSION' ) && version_compare( WRLD_PLUGIN_VERSION, LDRP_RECOMENDED_FREE_PLUGIN_VERSION, '<' ) ) {
			wp_enqueue_script( 'wrld_update_script', plugins_url( 'assets/admin/js/wrld-update.js', __FILE__ ), array( 'jquery', 'updates' ), LDRP_PLUGIN_VERSION );
			$plugin_basename  = plugin_basename( WRLD_REPORTS_FILE );
			$plugin_dir       = plugin_dir_path( WRLD_REPORTS_FILE );
			$from_wisdm       = false;
			$license_inactive = '';
			$screen           = get_current_screen();
			$on_plugins_page  = 'plugins' === $screen->base ? 'yes' : 'no';

			$cta_message_part_1 = '';
			$cta_message_action = '';
			$cta_message_part_2 = '';
			if ( file_exists( $plugin_dir . 'license.config.php' ) ) {
				// free plugin from wisdmlabs.com active.
				$from_wisdm  = true;
				$license_key = get_option( 'edd_learndash-reports-by-wisdmlabs_license_key', false );
				$validity    = get_option( 'edd_learndash-reports-by-wisdmlabs_license_status', false );
				if ( $license_key && $validity && 'valid' !== trim( $validity ) ) {
					$license_inactive   = 'license_inactive';
					$cta_message_part_1 = __( 'Your License Key for the plugin seems to be inactive. Kindly "Activate" the License Key from ', 'learndash-reports-pro' );
					$cta_message_action = __( 'here', 'learndash-reports-by-wisdmlabs' );
					$cta_message_part_2 = __( ' and then proceed to update the WISDM Reports FREE plugin.', 'learndash-reports-pro' );
				}
			}
			?>
			<div class='notice-error notice is-dismissible'>
				<div style='display:inline-flex; justify-content:center; align-items:center;' class='wrld-plugin-dependency-installation'>
					<p>
						<strong>
						<?php echo wp_kses_post( __( 'WisdmLabs recommends you to update the WISDM Reports FREE for LearnDash to its latest version now.', 'learndash-reports-pro' ) ); ?>
						</strong>
						<?php
						if ( $from_wisdm && ! empty( $license_inactive ) ) {
							$license_page = admin_url( 'admin.php?page=wisdmlabs-licenses' );
							?>
								<span><?php echo esc_html( $cta_message_part_1 ); ?></span>
								<a href=<?php echo esc_attr( $license_page ); ?>><?php echo esc_html( $cta_message_action ); ?></a>
								<span><?php echo esc_html( $cta_message_part_2 ); ?></span>
								<?php
						} else {
							?>
							<a id="wrld-plugin-update-notice" data-on_plugins_page="<?php echo esc_attr( $on_plugins_page ); ?>" data-pluginspage="<?php echo admin_url( 'plugins.php' ); ?>" data-basename="<?php echo esc_attr( $plugin_basename ); ?>" href="#"><?php esc_html_e( 'Update Now', 'learndash-reports-pro' ); ?></a>
							<span id="wrld-plugin-update-notice-spinner" data-updating="<?php esc_attr_e( 'Updating...', 'learndash-reports-pro' ); ?>" data-failed_message="<?php esc_attr_e( 'Plugin update failed, we request you to update the plugin manually. if you still face any issues feel free to reach out to us at helpdesk@wisdmlabs.com', 'learndash-reports-pro' ); ?>" style="margin-top:0px;" class="spinner"></span>
							<span id="wrld-plugin-update-notice-status"></span>
							<?php
						}
						?>
					</p>
				</div>
			</div>
			<?php
		}
	}
}

add_shortcode( 'ldrp_quiz_reports', 'ldrp_quiz_reports_handler' );
add_action( 'admin_notices', 'ldrp_pro_onboarding_modal', 99 );
add_action( 'admin_notices', 'ldrp_pro_update_free_modal', 99 );
add_action( 'admin_notices', 'ldrp_pro_update_student_dashboard_modal', 99 );
add_action( 'admin_notices', 'ldrp_add_recomendation_notice', 2 );

function show_statistic_detail_screen( $content ) {
	ldrp_enqueue_shortcode_assets();
	$dashboard_type = filter_input( INPUT_GET, 'dashboard', FILTER_SANITIZE_STRING );
	if ( empty( $dashboard_type ) || 'student' != $dashboard_type ) {
		return $content;
	}
	$report_type = filter_input( INPUT_GET, 'report', FILTER_SANITIZE_STRING );
	if ( empty( $report_type ) || ! in_array( $report_type, array( 'quiz', 'custom' ), true ) ) {
		$report_type = 'quiz';
	}
	$screen_type = filter_input( INPUT_GET, 'screen', FILTER_SANITIZE_STRING );
	if ( empty( $screen_type ) || ! in_array( $screen_type, array( 'user', 'quiz' ), true ) ) {
		$screen_type = 'listing';
	}
	if ( 'quiz' === $report_type ) {
		switch ( $screen_type ) {
			case 'listing':
				echo ldrp_show_report_listing_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'quiz':
				echo ldrp_show_single_statistic_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'user':
				echo ldrp_show_user_statistics_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
		}
	} elseif ( 'custom' === $report_type ) {
		echo ldrp_show_custom_reports_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_filter( 'the_content', 'show_statistic_detail_screen', 99999, 1 );
