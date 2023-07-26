<?php
namespace uncanny_learndash_groups\Tools\Menu;

use \uncanny_learndash_groups\Tools\Views\Wrapper as ViewWrapper;

class Register {

	const MENU_SLUG   = 'uncanny-groups-tools';
	const CAPABILITY  = 'manage_options';
	const PARENT_SLUG = 'uncanny-groups-create-group';

	public $wrap = '';

	public function __construct( ViewWrapper $wrap ) {

		$this->wrap = $wrap;

		add_action( 'admin_menu', array( $this, 'create_admin_area' ), 150 );

	}

	/**
	 * Create Plugin admin menus, pages, and sub-pages
	 *
	 * @return void.
	 */
	public function create_admin_area() {

		// Add 'System Status' under 'Uncanny Groups' menu.
		add_submenu_page(
			self::PARENT_SLUG,
			__( 'Tools', 'uncanny-learndash-groups' ),
			__( 'System status', 'uncanny-learndash-groups' ),
			self::CAPABILITY,
			self::MENU_SLUG . '-tools',
			$this->wrap
		);

		// Add the 'Logs' page but leave the parent to null.
		add_submenu_page(
			null,
			__( 'Logs', 'uncanny-learndash-groups' ),
			__( 'Logs', 'uncanny-learndash-groups' ),
			self::CAPABILITY,
			self::MENU_SLUG . 'logs',
			$this->wrap
		);

		// Add the 'Database repair' page but leave the parent to null.
		add_submenu_page(
			null,
			__( 'Database repair', 'uncanny-learndash-groups' ),
			__( 'Database repair', 'uncanny-learndash-groups' ),
			self::CAPABILITY,
			self::MENU_SLUG . '-database',
			$this->wrap
		);
	}

}
