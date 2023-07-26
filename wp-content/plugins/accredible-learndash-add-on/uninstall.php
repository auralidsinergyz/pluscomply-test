<?php
/**
 * Functions for uninstall Accredible_Learndash_Add_On
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash-admin-database.php';
Accredible_Learndash_Admin_Database::drop_all();

require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash-admin-setting.php';
Accredible_Learndash_Admin_Setting::delete_options();

