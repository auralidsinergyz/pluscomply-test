<?php
/**
 * Plugin Name: Mediamatic
 * Plugin URI:  http://mediamatic.frenify.com/1/
 * Description: Get organized with thousands of images. Organize media into folders.
 * Version:     1.7
 * Author:      plugincraft
 * Author URI:  http://mediamatic.frenify.com/1/
 * Text Domain: mediamatic
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages/
 */

/*

This plugin uses Open Source components. You can find the source code 
of their open source projects along with license information below. 
We acknowledge and are grateful to these developers for their contributions to open source.

--------------------------------------------------------------------

Project: FileBird – WordPress Media Library Folders (version:2.0)
Author: Ninja Team
Url: https://wordpress.org/plugins/filebird/
Lisence: GPL (General Public License)

--------------------------------------------------------------------

Project: Folders – Organize Pages, Posts and Media Library Folders with Drag and Drop (version:2.1.1)
Author: Premio
Url: https://wordpress.org/plugins/folders/
Lisence: GPL (General Public License)

*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MEDIAMATIC__FILE__', __FILE__ );
define( 'MEDIAMATIC_FOLDER', 'mediamatic_wpfolder' );
define( 'MEDIAMATIC_VERSION', '1.7' );
define( 'MEDIAMATIC_PATH', plugin_dir_path( MEDIAMATIC__FILE__ ) );
define( 'MEDIAMATIC_URL', plugins_url( '/', MEDIAMATIC__FILE__ ) );
define( 'MEDIAMATIC_ASSETS_URL', MEDIAMATIC_URL . 'assets/' );
define( 'MEDIAMATIC_TEXT_DOMAIN', 'mediamatic' );
define( 'MEDIAMATIC_PLUGIN_BASE', plugin_basename( MEDIAMATIC__FILE__ ) );
define( 'MEDIAMATIC_PLUGIN_NAME', 'Mediamatic' );



function mediamatic_plugins_loaded(){

	// main files
	include_once ( MEDIAMATIC_PATH . 'inc/plugin.php' );
	include_once ( MEDIAMATIC_PATH . 'inc/functions.php' );
	
	mediamatic_cores();
	
	load_plugin_textdomain(MEDIAMATIC_TEXT_DOMAIN, false, plugin_basename(__DIR__) . '/languages/');
}


add_action('plugins_loaded', 'mediamatic_plugins_loaded');





