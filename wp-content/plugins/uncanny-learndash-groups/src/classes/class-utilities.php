<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * This class stores helper functions that can be used statically in all of WP after plugins loaded hook
 *
 * Use the Utilites::get_% function to retrieve the variable. The following is a list of calls
 *
 * @package    uncanny_learndash_groups
 * @subpackage uncanny_learndash_groups/config
 * @author     Uncanny Owl
 */
class Utilities {

	/**
	 * The name of the plugin
	 *
	 * @use get_plugin_name()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private static $plugin_name;

	/**
	 * The prefix of this plugin that is set in the config class
	 *
	 * @use get_version()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private static $prefix;

	/**
	 * The plugins version number
	 *
	 * @use get_version()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private static $version;

	/**
	 * The main plugin file path
	 *
	 * @use get_plugin_file()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private static $plugin_file;

	/**
	 * The references to autoloaded class instances
	 *
	 * @use get_autoloaded_class_instance()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private static $class_instances = array();

	/**
	 * The plugin specific debug mode
	 *
	 * @use get_debug_mode()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool
	 */
	private static $debug_mode;

	/**
	 * The plugin date and time format
	 *
	 * @use get_date_time_format()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool
	 */
	private static $date_time_format;

	/**
	 * The plugin date format
	 *
	 * @use get_date_format()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool
	 */
	private static $date_format;

	/**
	 * The plugin time format
	 *
	 * @use get_time_format()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool
	 */
	private static $time_format;

	/**
	 * The server time when the plugin was initialized
	 *
	 * @use get_time_format()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool
	 */
	private static $plugin_initialization;
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
	 * Set the name of the plugin
	 *
	 * @param string $plugin_name The name of the plugin
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function set_plugin_name( $plugin_name ) {
		if ( null === self::$prefix ) {
			self::$plugin_name = $plugin_name;
		}

		return self::$plugin_name;
	}

	/**
	 * Get the name of the plugin
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function get_plugin_name() {
		return self::$plugin_name;
	}

	/**
	 * Set the prefix for the plugin
	 *
	 * @param string $prefix Variable used to prefix filters and actions
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function set_prefix( $prefix ) {
		if ( null === self::$prefix ) {
			self::$prefix = $prefix;
		}

		return self::$prefix;
	}

	/**
	 * Get the prefix for the plugin
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function get_prefix() {
		return self::$prefix;
	}

	/**
	 * Set the version for the plugin
	 *
	 * @param string $version Variable used to prefix filters and actions
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function set_version( $version ) {
		if ( null === self::$version ) {
			self::$version = $version;
		}

		return self::$version;
	}

	/**
	 * Get the version for the plugin
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function get_version() {
		return self::$version;
	}


	/**
	 * Set the main plugin file path
	 *
	 * @param string $plugin_file The main plugin file path
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function set_plugin_file( $plugin_file ) {
		if ( null === self::$plugin_file ) {
			self::$plugin_file = $plugin_file;
		}

		return self::$plugin_file;
	}

	/**
	 * Get the version for the plugin
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function get_plugin_file() {
		return self::$plugin_file;
	}

	/**
	 * Set the main plugin file path
	 *
	 * @param string $class_name The name of the class instance
	 * @param object $class_instance The reference to the class instance
	 *
	 * @since    1.0.0
	 *
	 */
	public static function set_class_instance( $class_name, $class_instance ) {

		self::$class_instances[ $class_name ] = $class_instance;

	}

	/**
	 * Get the version for the plugin
	 *
	 * @param string $class_name The name of the class instance
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function get_class_instance( $class_name ) {
		return self::$class_instances[ $class_name ];
	}

	/**
	 * Set the default date and time format
	 *
	 * @param string $date Date format
	 * @param string $time Time format
	 * @param string $separator The separator between the date and time format
	 *
	 * @return bool
	 * @since    1.0.0
	 *
	 */
	public static function set_date_time_format( $date = 'F j, Y', $time = ' g:i a', $separator = ' ' ) {

		$date      = apply_filters( self::$prefix . '_date_time_format', $date );
		$time      = apply_filters( self::$prefix . '_date_time_format', $time );
		$separator = apply_filters( self::$prefix . '_date_time_format', $separator );

		if ( null === self::$date_time_format ) {
			self::$date_time_format = $date . $separator . $time;
		}

		if ( null === self::$date_format ) {
			self::$date_format = $date;
		}

		if ( null === self::$time_format ) {
			self::$time_format = $time;
		}

		return self::$date_time_format;
	}

	/**
	 * Get the date and time format for the plugin
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function get_date_time_format() {
		return self::$date_time_format;
	}

	/**
	 * Get the date format for the plugin
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function get_date_format() {
		return self::$date_time_format;
	}

	/**
	 * Get the time format for the plugin
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public static function get_time_format() {
		return self::$date_time_format;
	}

	/**
	 * Set the main plugin file path
	 *
	 * @param bool $debug_mode The main plugin file path
	 *
	 * @return bool
	 * @since    1.0.0
	 *
	 */
	public static function set_debug_mode( $debug_mode ) {

		if ( null === self::$debug_mode ) {

			self::$debug_mode = $debug_mode;
		}

		return self::$debug_mode;
	}

	/**
	 * Set the version for the plugin
	 *
	 * @return bool
	 * @since    1.0.0
	 *
	 */
	public static function get_debug_mode() {
		return self::$debug_mode;
	}

	/**
	 * Set the server time when the plugin was initialized
	 *
	 * @param int $time Timestamp
	 *
	 * @return int
	 * @since    1.0.0
	 *
	 */
	public static function set_plugin_initialization( $time ) {

		if ( null === self::$plugin_initialization ) {
			self::$plugin_initialization = $time;
		}

		return self::$plugin_initialization;
	}

	/**
	 * Get the server time when the plugin was initialized
	 *
	 * @return int Timestamp
	 * @since    1.0.0
	 *
	 */
	public static function get_plugin_initialization() {
		return self::$plugin_initialization;
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_asset( $source = 'frontend', $file_name = '' ) {
		return plugins_url( 'src/assets/' . $source . '/dist/' . $file_name, UNCANNY_GROUPS_PLUGIN_FILE );
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_vendor( $file_name ) {
		return plugins_url( 'src/assets/vendor/' . $file_name, UNCANNY_GROUPS_PLUGIN_FILE );
	}

	/**
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_sample( $file_name ) {
		return plugins_url( 'src/assets/sample/' . $file_name, UNCANNY_GROUPS_PLUGIN_FILE );
	}

	/**
	 * Returns the full url for the passed CSS file
	 *
	 * @param string $file_name
	 *
	 * @return string $asset_url
	 * @since    1.0.0
	 *
	 */
	public static function get_css( $file_name ) {
		return plugins_url( 'src/assets/css/' . $file_name, UNCANNY_GROUPS_PLUGIN_FILE );
	}

	/**
	 * Returns the full url for the passed JS file
	 *
	 * @param string $file_name
	 *
	 * @return string $asset_url
	 * @since    1.0.0
	 *
	 */
	public static function get_js( $file_name ) {
		return plugins_url( 'src/assets/js/' . $file_name, UNCANNY_GROUPS_PLUGIN_FILE );
	}

	/**
	 * Returns the full url for the passed media file
	 *
	 * @param string $file_name
	 *
	 * @return string $asset_url
	 * @since    1.0.0
	 *
	 */
	public static function get_media( $file_name ) {
		return plugins_url( 'src/assets/media/' . $file_name, UNCANNY_GROUPS_PLUGIN_FILE );
	}

	/**
	 * Returns the full server path for the passed template file
	 *
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_template( $file_name ) {

		$template_path = apply_filters( 'uncanny_groups_template_path', 'uncanny-groups' . DIRECTORY_SEPARATOR );
		$asset_path    = self::locate_template( $template_path . $file_name );

		if ( empty( $asset_path ) ) {
			$templates_directory = ULGM_ABSPATH . 'src/templates' . DIRECTORY_SEPARATOR;

			/**
			 * Filters the director path to the template file
			 *
			 * This can be used for template overrides by modifying the path to go to a directory in the theme or another plugin.
			 *
			 * @param string $templates_directory Path to the plugins template folder
			 * @param string $file_name The file name of the template file
			 *
			 * @since 1.0.0
			 *
			 */
			$templates_directory = apply_filters( 'ulgm_template_path', $templates_directory, $file_name );

			$asset_path = $templates_directory . $file_name;
		}

		return $asset_path;
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH and wp-includes/theme-compat
	 * so that themes which inherit from a parent theme can just overload one file.
	 *
	 * @param string|array $template_names Template file(s) to search for, in order.
	 *
	 * @return string The template filename if one is located.
	 * @since 3.1
	 *
	 */
	public static function locate_template( $template_names ) {
		$located = '';
		foreach ( (array) $template_names as $template_name ) {
			if ( ! $template_name ) {
				continue;
			}
			if ( file_exists( get_stylesheet_directory() . DIRECTORY_SEPARATOR . $template_name ) ) {
				$located = get_stylesheet_directory() . DIRECTORY_SEPARATOR . $template_name;
				break;
			} elseif ( file_exists( get_template_directory() . DIRECTORY_SEPARATOR . $template_name ) ) {
				$located = get_template_directory() . DIRECTORY_SEPARATOR . $template_name;
				break;
			} elseif ( file_exists( ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'theme-compat' . DIRECTORY_SEPARATOR . $template_name ) ) {
				$located = ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'theme-compat' . DIRECTORY_SEPARATOR . $template_name;
				break;
			}
		}

		return $located;
	}

	/**
	 * Returns the full server path for the passed include file
	 *
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_include( $file_name ) {

		$includes_directory = ULGM_ABSPATH . 'src/includes' . DIRECTORY_SEPARATOR;

		/**
		 * Filters the director path to the include file
		 *
		 * This can be used for template overrides by modifying the path to go to a directory in the theme or another plugin.
		 *
		 * @param string $templates_directory Path to the plugins template folder
		 * @param string $file_name The file name of the template file
		 *
		 * @since 1.0.0
		 *
		 */
		$includes_directory = apply_filters( 'ulgm_includes_path_to', $includes_directory, $file_name );

		$asset_path = $includes_directory . $file_name;

		return $asset_path;
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 */
	public static function if_woocommerce_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return true;
		}
		if ( is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
			return true;
		}
		if ( class_exists( 'WooCommerce' ) ) {
			return true;
		}
		if ( function_exists( 'WC' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if WooCommerce Subscription is active
	 *
	 * @return bool
	 * @since 2.0
	 *
	 */
	public static function if_woocommerce_subscription_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			return true;
		}
		if ( is_plugin_active_for_network( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			return true;
		}
		if ( class_exists( 'WC_Subscriptions' ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Check if WooCommerce Subscription is active
	 *
	 * @return bool
	 * @since 2.0
	 *
	 */
	public static function if_gravity_forms_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
			return true;
		}
		if ( is_plugin_active_for_network( 'gravityforms/gravityforms.php' ) ) {
			return true;
		}

		return false;

	}

	/**
	 * @return bool
	 */
	public static function if_formidable_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'formidable/formidable.php' ) ) {
			return true;
		}
		if ( is_plugin_active_for_network( 'formidable/formidable.php' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public static function if_wpforms_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'wpforms/wpforms.php' ) ) {
			return true;
		}
		if ( is_plugin_active( 'wpforms-lite/wpforms.php' ) ) {
			return true;
		}
		if ( is_plugin_active_for_network( 'wpforms/wpforms.php' ) ) {
			return true;
		}
		if ( is_plugin_active_for_network( 'wpforms-lite/wpforms.php' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if Theme My Login is active
	 *
	 * @return bool
	 * @since 2.6
	 *
	 */
	public static function if_tml_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'theme-my-login/theme-my-login.php' ) ) {
			return true;
		}
		if ( is_plugin_active_for_network( 'theme-my-login/theme-my-login.php' ) ) {
			return true;
		}

		return false;

	}


	/**
	 * !!! ALPHA FUNCTION - NEEDS TESTING/BENCHMARKING
	 *
	 * Get User data with meta keys' value
	 *
	 * In some cases we need to loop a lot of users' data. If we need 1000 user with there user meta values we would
	 * normal run WP User Query, then loop the user and run get_user_meta() on each iteration which will return the
	 * specified user meta and also collect/store ALL the user meta. In case above, WP will run 1 query for the user loop
	 * and 1000 user meta queries; 1001 queries will run. WP will also store all the data collected in memory, if each
	 * user has 100 metas stores then 1000 x 100 metas is 100 000 values.
	 *
	 * With this function if we run the same scenrio as above, 2 quieries will run and only the amount of data points
	 * that are specifically needed. 1000 users
	 *
	 * Todo Maybe add optional transient
	 * Todo Benchmarking needs
	 *
	 * Only Returns this first meta_key value. Does not support multiple meta_values per single key.
	 *
	 * @param array $exact_meta_keys
	 * @param array $fuzzy_meta_keys
	 * @param array $include_user_ids
	 *
	 * @return array
	 */
	public static function get_users_with_meta( $exact_meta_keys = array(), $fuzzy_meta_keys = array(), $include_user_ids = array() ) {

		global $wpdb;

		// Collect all possible meta_key values
		$keys = $wpdb->get_col( "SELECT distinct meta_key FROM $wpdb->usermeta" );

		//then prepare the meta keys query as fields which we'll join to the user table fields
		$meta_columns = '';
		foreach ( $keys as $key ) {

			// Collect exact matches
			if ( ! empty( $exact_meta_keys ) ) {
				if ( in_array( $key, $exact_meta_keys ) ) {
					$meta_columns .= " MAX(CASE WHEN um1.meta_key = '$key' THEN um1.meta_value ELSE NULL END) AS '$key', \n";
					continue;
				}
			}

			// Collect fuzzy matches ... ex. "example" would match "example_947"
			// ToDo allow for SQL "LIKE" syntax ... ex "example%947"
			// ToDo allow for regex
			if ( ! empty( $fuzzy_meta_keys ) ) {
				foreach ( $fuzzy_meta_keys as $fuzzy_key ) {
					if ( false !== strpos( $key, $fuzzy_key ) ) {
						$meta_columns .= " MAX(CASE WHEN um1.meta_key = '$key' THEN um1.meta_value ELSE NULL END) AS '$key', \n";
					}
				}
			}
		}

		$sql_include_user_ids = '';
		if ( ! empty( $include_user_ids ) ) {
			$sql_include_user_ids = ' AND u.ID IN (' . implode( ',', $include_user_ids ) . ') ';
		}

		//then write the main query with all of the regular fields and use a simple left join on user users.ID and usermeta.user_id
		$query = '
SELECT
    u.ID,
    u.user_login,
    u.user_pass,
    u.user_nicename,
    u.user_email,
    u.user_url,
    u.user_registered,
    u.user_activation_key,
    u.user_status,
    u.display_name,
    ' . rtrim( $meta_columns, ", \n" ) . "
FROM
    $wpdb->users u
LEFT JOIN
    $wpdb->usermeta um1 ON (um1.user_id = u.ID)
	WHERE 1=1 {$sql_include_user_ids}
GROUP BY
    u.ID";

		$users = $wpdb->get_results( $query, ARRAY_A );

		return array(
			'query'   => $query,
			'results' => $users,
		);

	}

	/**
	 * Returns the heading for the Setting pages
	 *
	 * @param string $section_title
	 *
	 * @return string
	 * @since 2.5
	 *
	 */

	public static function get_settings_header( $section_title ) {
		ob_start();

		?>

		<div class="uo-plugins-header">
			<div class="uo-plugins-header__title">
				<?php echo $section_title; ?>
			</div>
			<div class="uo-plugins-header__author">
				<span>by</span>
				<a href="https://uncannyowl.com" target="_blank" class="uo-plugins-header__logo">
					<img src="<?php echo self::get_media( 'uncanny-plugins-header.png' ); ?>"
						 srcset="<?php echo self::get_media( 'uncanny-plugins-header@2x.png' ); ?> 2x"
						 alt="Uncanny Owl">
				</a>
			</div>
		</div>

		<?php

		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Create and store logs @ wp-content/{plugin_folder_name}/uo-{$file_name}.log
	 *
	 * @param string $trace_message The message logged
	 * @param string $trace_heading The heading of the current trace
	 * @param bool $force_log Create log even if debug mode is off
	 * @param string $file_name The file name of the log file
	 *
	 * @return bool $error_log Was the log successfully created
	 * @since    1.0.0
	 *
	 */
	public static function log( $trace_message = '', $trace_heading = '', $force_log = false, $file_name = 'logs' ) {

		// Only return log if debug mode is on OR if log is forced
		if ( ! $force_log ) {

			if ( ! self::get_debug_mode() ) {
				return false;
			}
		}

		$timestamp = date( 'Y-m-d H:i:s A' );

		$current_page_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$trace_start = "\n===========================<<<< $timestamp >>>>===========================\n";

		$trace_heading = "* Heading: $trace_heading \n";

		$trace_heading .= "* Current Page: $current_page_link \n";

		$trace_heading .= '* Plugin Initialized: ' . date( self::get_date_time_format(), self::get_plugin_initialization() ) . "\n";

		$trace_end = "\n===========================<<<< TRACE END >>>>===========================\n\n";

		$trace_message = print_r( $trace_message, true );

		//$file = dirname( self::get_plugin_file() ) . '/uo-' . $file_name . '.log';
		$file = WP_CONTENT_DIR . '/uo-' . $file_name . '.log';

		$error_log = error_log( $trace_start . $trace_heading . $trace_message . $trace_end, 3, $file );

		return $error_log;

	}

	/**
	 * Outputs CSS with selected colors by the user
	 *
	 * @return string
	 * @since    2.0.0
	 *
	 */

	public static function user_colors() {
		$main_color = get_option( 'ulgm_main_color', '#ff9655' );
		$font_color = get_option( 'ulgm_font_color', '#fff' );

		$main_color = empty( $main_color ) ? '#ff9655' : $main_color;

		// Get RGB
		list( $r, $g, $b ) = sscanf( $main_color, '#%02x%02x%02x' );

		$custom_css = '
		.uo-groups .uo-select select option:hover,
		.uo-groups .uo-select select option:checked,
		.uo-groups .uo-select-modal .uo-select-modal-option:hover,
		.uo-groups .uo-select-modal .uo-select-modal-option.uo-select-option--selected,
		.uo-groups .uo-table .uo-table-header,
		.uo-groups .uo-groups-table th,
		.uo-groups .uo-groups-table .essay_approve_single,
		.uo-groups .uo-groups-table .assignment_approve_single,
		.uo-groups .uo-radio input ~ .uo-radio-checkmark:after,
		.uo-groups-list-of-btns .uo-groups-list .uo-btn:hover,
		.uo-groups.uo-quiz-report #uo-quiz-report-table thead tr,
		.uo-groups .uo-groups-table thead tr,

		.uo-groups-datatable-footer .paginate_button.current,

		.uo-ulgm-front .uo-admin-block .uo-admin-form-submit:hover {
			background-color: ' . $main_color . ';
			color: ' . $font_color . ';
		}

		#uo-groups-buy-courses .uo-radio input:checked ~ .uo-checkmark:after {
			background-color: ' . $main_color . ';
		}

		.uo-ulgm-front .uo-admin-block .uo-admin-form-submit:hover {
			background: ' . $main_color . ';
		}

		#uo-groups-buy--courses .uo-groups-table .uo-groups-table-row.uo-groups-table-row--selected {
			background: rgba(' . $r . ', ' . $g . ', ' . $b . ', .1);
		}

		.uo-groups a:not(.button),
		.uo-groups .uo-btn:hover,
		.uo-groups .uo-btn.uo-btn--selected,
		.uo-groups .uo-select:hover button,
		.uo-groups .uo-select:hover select,
		.uo-groups .uo-checkbox:hover input ~ .uo-checkbox-checkmark,
		.uo-groups .uo-checkbox input:checked ~ .uo-checkbox-checkmark,
		.uo-groups .uo-radio:hover input ~ .uo-radio-checkmark,
		.uo-groups .uo-radio input:checked ~ .uo-radio-checkmark,
		.uo-groups.uo-quiz-report .buttons-csv:hover,
		.uo-groups.uo-quiz-report a.paginate_button:hover,
		.uo-groups.uo-quiz-report a.paginate_button.current,

		.uo-groups-registration button:hover,
		.uo-groups-registration button:focus,
		.uo-groups-registration input[type="button"]:hover,
		.uo-groups-registration input[type="button"]:focus,
		.uo-groups-registration input[type="reset"]:hover,
		.uo-groups-registration input[type="reset"]:focus,
		.uo-groups-registration input[type="submit"]:hover,
		.uo-groups-registration input[type="submit"]:focus,

		#uo-groups-buy-courses .uo-groups-table .uo-groups-table-cell.uo-groups-table-price ins,

		#uo-groups-buy-courses .uo-checkbox:hover input ~ .uo-checkmark,
		#uo-groups-buy-courses .uo-checkbox input:checked ~ .uo-checkmark,
		#uo-groups-buy-courses .uo-radio:hover input ~ .uo-checkmark,
		#uo-groups-buy-courses .uo-radio input:checked ~ .uo-checkmark,
		#uo-groups-buy-courses .uo-checkbox input ~ .uo-checkmark:after,
		#uo-groups-buy--add-to-cart .uo-btn:hover,

		.uo-ulgm-front .uo-admin-block .uo-admin-form-submit,
		.uo-groups .uo-groups-table .user_edit_link {
			color: ' . $main_color . ';
		}

		.uo-groups .uo-btn:hover,
		.uo-groups .uo-btn.uo-btn--selected,
		.uo-groups .uo-input:focus,
		.uo-groups .uo-select:hover button,
		.uo-groups .uo-select:hover select,
		.uo-groups .uo-checkbox:hover input ~ .uo-checkbox-checkmark,
		.uo-groups .uo-checkbox input:checked ~ .uo-checkbox-checkmark,
		.uo-groups .uo-radio:hover input ~ .uo-radio-checkmark,
		.uo-groups .uo-radio input:checked ~ .uo-radio-checkmark,
		.uo-groups.uo-quiz-report .buttons-csv:hover,
		.uo-groups.uo-quiz-report a.paginate_button:hover,
		.uo-groups.uo-quiz-report a.paginate_button.current,

		.uo-groups-registration button:hover,
		.uo-groups-registration button:focus,
		.uo-groups-registration input[type="button"]:hover,
		.uo-groups-registration input[type="button"]:focus,
		.uo-groups-registration input[type="reset"]:hover,
		.uo-groups-registration input[type="reset"]:focus,
		.uo-groups-registration input[type="submit"]:hover,
		.uo-groups-registration input[type="submit"]:focus,
		.uo-groups-registration input[type="date"]:hover,
		.uo-groups-registration input[type="time"]:hover,
		.uo-groups-registration input[type="datetime-local"]:hover,
		.uo-groups-registration input[type="week"]:hover,
		.uo-groups-registration input[type="month"]:hover,
		.uo-groups-registration input[type="text"]:hover,
		.uo-groups-registration input[type="email"]:hover,
		.uo-groups-registration input[type="url"]:hover,
		.uo-groups-registration input[type="password"]:hover,
		.uo-groups-registration input[type="search"]:hover,
		.uo-groups-registration input[type="tel"]:hover,
		.uo-groups-registration input[type="number"]:hover,
		.uo-groups-registration textarea:hover,
		.uo-groups-registration input[type="date"]:focus,
		.uo-groups-registration input[type="time"]:focus,
		.uo-groups-registration input[type="datetime-local"]:focus,
		.uo-groups-registration input[type="week"]:focus,
		.uo-groups-registration input[type="month"]:focus,
		.uo-groups-registration input[type="text"]:focus,
		.uo-groups-registration input[type="email"]:focus,
		.uo-groups-registration input[type="url"]:focus,
		.uo-groups-registration input[type="password"]:focus,
		.uo-groups-registration input[type="search"]:focus,
		.uo-groups-registration input[type="tel"]:focus,
		.uo-groups-registration input[type="number"]:focus,
		.uo-groups-registration textarea:focus,

		#uo-groups-buy-courses .uo-checkbox:hover input ~ .uo-checkmark,
		#uo-groups-buy-courses .uo-checkbox input:checked ~ .uo-checkmark,
		#uo-groups-buy-courses .uo-radio:hover input ~ .uo-checkmark,
		#uo-groups-buy-courses .uo-radio input:checked ~ .uo-checkmark,
		#uo-groups-buy-courses .uo-input:hover,
		#uo-groups-buy-courses .uo-input:focus,
		#uo-groups-buy--add-to-cart .uo-btn:hover,

		.uo-ulgm-front .uo-admin-block .uo-admin-form-submit,

		.uo-ulgm-front .uo-admin-block .uo-admin-form .uo-admin-field .uo-admin-tags .uo-admin-copy-to-clipboard .uo-admin-copy-to-clipboard-input:active,

		.uo-ulgm-front .uo-admin-block .uo-admin-form .uo-admin-field .uo-admin-input:hover,
		.uo-ulgm-front .uo-admin-block .uo-admin-form .uo-admin-field .uo-admin-select:hover,
		.uo-ulgm-front .uo-admin-block .uo-admin-form .uo-admin-field .uo-admin-input:focus,
		.uo-ulgm-front .uo-admin-block .uo-admin-form .uo-admin-field .uo-admin-select:focus,
		.ulg-select2 .select2-selection:hover,
		.ulg-select2.select2-container--default.select2-container--focus .select2-selection--multiple,

		.uo-groups-datatable-footer .paginate_button.current {
			border-color: ' . $main_color . ';
		}
		';

		return $custom_css;
	}

	/**
	 * Decides if it has to show or not a section
	 *
	 * @return boolean
	 * @since    2.0.0
	 *
	 */

	public static function show_section( $var ) {
		return 'hide' === $var ? false : true;
	}

	/**
	 * @param $post
	 * @param $shortcode
	 *
	 * @return bool
	 */
	public static function has_shortcode( $post, $shortcode ) {
		if ( $post instanceof \WP_Post ) {
			return has_shortcode( $post->post_content, $shortcode );
		}

		return false;
	}

	/**
	 * @param $post
	 * @param $block_to_match
	 *
	 * @return bool
	 */
	public static function has_block( $post, $block_to_match ) {
		if ( function_exists( 'has_blocks' ) && function_exists( 'parse_blocks' ) ) {
			// Check if the post content has blocks
			if ( $post instanceof \WP_Post && has_blocks( $post->post_content ) ) {
				// Get all the blocks
				$blocks = parse_blocks( $post->post_content );
				if ( $blocks ) {
					// Iterate all the blocks
					foreach ( $blocks as $block ) {
						// Check if one of the blocks is the Tin Canny
						// Gutenberg block
						if ( (string) $block_to_match === (string) $block['blockName'] ) {
							// Change value of variable
							return true;
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public static function i18n_datatable_strings() {
		return array(
			'processing'        => _x( 'Processing...', 'Table', 'uncanny-learndash-groups' ),
			'sSearch'           => _x( 'Search', 'Table', 'uncanny-learndash-groups' ),
			'searchPlaceholder' => _x( 'Search', 'Table', 'uncanny-learndash-groups' ),
			/* translators: %s is a number */
				'lengthMenu'    => sprintf( _x( 'Show %s entries', 'Table', 'uncanny-learndash-groups' ), '_MENU_' ),
			/* translators: Both %1$s and %2$s are numbers */
				'info'          => sprintf( _x( 'Showing page %1$s of %2$s', 'Table', 'uncanny-learndash-groups' ), '_PAGE_', '_PAGES_' ),
			'infoEmpty'         => _x( 'Showing 0 to 0 of 0 entries', 'Table', 'uncanny-learndash-groups' ),
			/* translators: %s is a number */
				'infoFiltered'  => sprintf( _x( '(filtered from %s total entries)', 'Table', 'uncanny-learndash-groups' ), '_MAX_' ),
			'loadingRecords'    => _x( 'Loading', 'Table', 'uncanny-learndash-groups' ),
			'zeroRecords'       => _x( 'No matching records found', 'Table', 'uncanny-learndash-groups' ),
			'emptyTable'        => _x( 'No data available in table', 'Table', 'uncanny-learndash-groups' ),
			'paginate'          => array(
				/* translators: Table pagination */
					'first'    => _x( 'First', 'Table', 'uncanny-learndash-groups' ),
				/* translators: Table pagination */
					'previous' => _x( 'Previous', 'Table', 'uncanny-learndash-groups' ),
				/* translators: Table pagination */
					'next'     => _x( 'Next', 'Table', 'uncanny-learndash-groups' ),
				/* translators: Table pagination */
					'last'     => _x( 'Last', 'Table', 'uncanny-learndash-groups' ),
			),
			'sortAscending'     => _x( ': activate to sort column ascending', 'Table', 'uncanny-learndash-groups' ),
			'sortDescending'    => _x( ': activate to sort column descending', 'Table', 'uncanny-learndash-groups' ),
			'buttons'           => array(
				/* translators: Table button */
					'csvExport' => _x( 'CSV export', 'Table', 'uncanny-learndash-groups' ),
				/* translators: Table button */
					'pdfExport' => _x( 'PDF export', 'Table', 'uncanny-learndash-groups' ),
			),
		);
	}

	/**
	 * This method takes a comma separated string of numbers
	 * and returns a valid value for the DataTable lengthMenu attribute.
	 * https://datatables.net/reference/option/lengthMenu
	 *
	 * @param String $attribute Comma separated numbers.
	 *                           For example, "10, 25, 50, 100, -1:All"
	 *
	 * @return String            Valid lengthMenu value
	 */
	public static function attr_datatables_length_menu( $attribute = '' ) {
		// Set the default value
		$datatable_length_menu_numbers      = array( 10, 25, 50, 100 );
		$datatable_length_menu_numbers_name = array( 10, 25, 50, 100 );

		// Remove extra spaces
		$attribute = trim( $attribute );

		// Get the custom length menu attribute
		$custom_length_menu = ! empty( $attribute ) ? preg_replace( "/[\n\r]/", ',', $attribute ) : $attribute;
		$custom_length_menu = ! empty( $custom_length_menu ) ? explode( ',', $custom_length_menu ) : array();

		// Check if there are valid values
		if ( ! empty( $custom_length_menu ) ) {
			// Override the default values
			$custom_datatable_length_menu_numbers      = array();
			$custom_datatable_length_menu_numbers_name = array();

			// Iterate the custom attribute
			foreach ( $custom_length_menu as $page_length ) {
				// Get the label of the page length. For example,
				// the label of the page length "-1" is "All".
				$page_length_parts = explode( ':', $page_length );

				// Check if there are two parts. If there are, then
				// the user defined a custom label for this page length too
				if ( count( $page_length_parts ) >= 2 ) {
					// Remove extra spaces from the page length number
					// and from the page number label
					$page_length_number = trim( $page_length_parts[0] );
					$page_length_text   = trim( $page_length_parts[1] );
				} else {
					// Remove extra spaces from the page length number
					// and from the page number label
					$page_length_number = trim( $page_length_parts[0] );
					// Then we have to define the text, but as the user didn't
					// define a custom text, we will just use the number
					$page_length_text = $page_length_number;
				}

				// Check if the page number is really a number
				// Otherwise, don't add it
				if ( is_numeric( $page_length_number ) ) {
					// Add the custom page length to the list of custom page lengths
					$custom_datatable_length_menu_numbers[]      = $page_length_number;
					$custom_datatable_length_menu_numbers_name[] = $page_length_text;
				}
			}

			// Check if we have at least one length menu
			// If so, override the original variables
			if ( count( $custom_datatable_length_menu_numbers ) > 0 ) {
				// Override the default values
				$datatable_length_menu_numbers      = $custom_datatable_length_menu_numbers;
				$datatable_length_menu_numbers_name = $custom_datatable_length_menu_numbers_name;
			}
		}

		// Create a variable with both page length numbers and text
		$datatable_length_menu = array(
			$datatable_length_menu_numbers,
			$datatable_length_menu_numbers_name,
		);

		// Return it
		return apply_filters(
			'ulgm-frontend-datatables-length-menu',
			$datatable_length_menu
		);
	}

	/**
	 * @param int $page_length
	 *
	 * @return mixed|void
	 */
	public static function attr_datatables_page_length( $page_length = 50 ) {
		// Check if it's a valid value
		// If it's not, fix it
		if ( empty( $page_length ) || ! is_numeric( $page_length ) ) {
			$page_length = 50;
		}

		// Return it
		return apply_filters(
			'ulgm-frontend-datatables-page-length',
			$page_length
		);
	}
}
