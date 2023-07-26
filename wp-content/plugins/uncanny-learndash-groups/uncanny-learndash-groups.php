<?php
/**
 * Plugin Name:         Uncanny Groups for LearnDash
 * Description:         Allow LearnDash Group Leaders to manage group membership and licenses for LearnDash courses from the front end
 * Author:              Uncanny Owl
 * Author URI:          https://www.uncannyowl.com
 * Plugin URI:          https://www.uncannyowl.com/uncanny-learndash-groups/
 * Text Domain:         uncanny-learndash-groups
 * Domain Path:         /languages
 * License:             GPLv3
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 * Version:             5.1
 * Requires at least:   5.3
 * Requires PHP:        7.2
 */

use uncanny_learndash_groups\Setup;
use uncanny_learndash_groups\DB_Handler;
use uncanny_learndash_groups\Load_Groups;
use uncanny_learndash_groups\Uncanny_Groups_Helpers;
use uncanny_learndash_groups\Group_Management_DB_Handler;

const UNCANNY_GROUPS_VERSION = '5.1';
/**
 *
 */
const UNCANNY_GROUPS_DB_VERSION = '4.4';
/**
 *
 */
const UNCANNY_GROUPS_PLUGIN = __DIR__;
/**
 *
 */
const UNCANNY_GROUPS_PLUGIN_FILE = __FILE__;

require_once __DIR__ . '/src/globals.php';


/**
 * @return Uncanny_Groups_Helpers
 */
function ulgm() {
	include_once __DIR__ . '/src/classes/class-uncanny-groups-helpers.php';

	return Uncanny_Groups_Helpers::get_instance();
}

require_once __DIR__ . '/src/classes/handlers/class-db-handler.php';
require_once __DIR__ . '/src/classes/handlers/class-group-management-db-handler.php';
/**
 * param DB_Handler
 */
ulgm()->db               = DB_Handler::get_instance();
ulgm()->group_management = Group_Management_DB_Handler::get_instance();

/**
 * Legacy code.
 * @deprecated v4.0.
 */
require_once ULGM_ABSPATH . 'src' . DIRECTORY_SEPARATOR . 'classes/class-initialize-plugin.php';
// Generate DB Tables and Group Management pages
require_once ULGM_ABSPATH . 'src' . DIRECTORY_SEPARATOR . 'classes/class-setup.php';
Setup::get_instance();

// Add global functions
require_once ULGM_ABSPATH . 'src' . DIRECTORY_SEPARATOR . 'global-functions.php';

if ( defined( 'LEARNDASH_VERSION' ) ) {

	include_once ULGM_ABSPATH . 'src' . DIRECTORY_SEPARATOR . 'class-load-groups.php';
	Load_Groups::get_instance();

	// Upgrade Groups DB tables
	ulgm()->db->upgrade_db();

} else {

	/**
	 *
	 */
	function uo_groups_learndash_not_activated() {
		?>
		<div class="notice notice-error">
			<h4>
				<?php echo esc_attr__( 'Warning: Uncanny Groups for LearnDash requires LearnDash. Please install LearnDash before using the plugin.', 'uncanny-learndash-groups' ); ?>
			</h4>
		</div>
		<?php
	}

	add_action( 'admin_notices', 'uo_groups_learndash_not_activated' );
}

/**
 * Load notifications.
 */
require_once __DIR__ . '/src/notifications/notifications.php';

if ( class_exists( '\Uncanny_Owl\Notifications' ) ) {

	$notifications = new \Uncanny_Owl\Notifications();

	// On activate, persists/update `uncanny_owl_over_time_uncanny-groups`.
	register_activation_hook(
		__FILE__,
		function() {
			update_option( 'uncanny_owl_over_time_uncanny-groups', array( 'installed_date' => time() ), false );
		}
	);

	// Initiate the Notifications handler, but only load once.
	if ( false === \Uncanny_Owl\Notifications::$loaded ) {

		$notifications::$loaded = true;

		add_action( 'admin_init', array( $notifications, 'init' ) );

	}
}
