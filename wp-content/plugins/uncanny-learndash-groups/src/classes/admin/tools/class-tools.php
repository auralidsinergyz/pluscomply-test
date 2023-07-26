<?php
/**
 * This file contains our Tools class.
 *
 * Tools class serves as a bootstrap file for our tools pages.
 *
 * @package uncanny_learndash_groups
 */

namespace uncanny_learndash_groups;

/**
 * Class Tools
 *
 * @package uncanny_learndash_groups
 */
class Tools {

	const TOOLS_VERSION = '1.0.2';

	public function __construct() {

		// Prepare the admin menus.
		$this->prepare_admin_menus();

	}

	public function prepare_admin_menus() {

		require_once self::get_path() . '/menu/class-register.php';

		require_once self::get_path() . '/views/class-wrapper.php';

		// Wrapper is our views wrapping class.
		$view_wrapper = new Tools\Views\Wrapper();

		// Attach the view into menu.
		$menu = new Tools\Menu\Register( $view_wrapper );

	}

	public static function get_path() {

		return trailingslashit( plugin_dir_path( __FILE__ ) );

	}

	public static function get_uri_path() {

		return trailingslashit( plugin_dir_url( __FILE__ ) );

	}

}

