<?php

/*
 * Plugin Name:       Edwiser Bridge Single Sign On
 * Plugin URI:        https://edwiser.org/bridge/
 * Description:       The plugin is an extension for Edwiser Bridge that allows users to login once and have access to both WordPress and Moodle.
 * Version:           1.3.2
 * Author:            WisdmLabs
 * Author URI:        https://edwiser.org
 * Text Domain:       single_sign_on_text_domain
 * Domain Path:       /languages/
 * License:           GPL-2.0+
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('EBSSO_DIR_NAME', dirname(plugin_basename(__FILE__)));
define('EBSSO_DIR_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('EBSSO_URL', plugins_url('', __FILE__));
define('EBSSO_TD', 'single_sign_on_text_domain');
define('EBSSO_MOODLE_PLUGIN_LINK', 'https://edwiser.org/wp-content/uploads/edd/2017/06/wdmwpmoodle.zip');

$ssoPluginData = array(
    'plugin_short_name' => 'Edwiser Bridge Single Sign On',
    'plugin_slug' => 'single_sign_on',
    'plugin_version' => '1.3.2',
    'plugin_name' => 'Edwiser Bridge Single Sign On',
    'store_url' => 'https://edwiser.org',
    'author_name' => 'WisdmLabs',
    'pluginTextDomain' => 'single_sign_on_text_domain',
);

function activateEdwiserBridge($netWide)
{
    require_once plugin_dir_path(__FILE__).'includes/class-sso-activator.php';
    ebsso\SsoActivator::activate($netWide);
}

register_activation_hook(__FILE__, 'activateEdwiserBridge');

function pluginUpdateHook()
{
    $oldVersion = get_option('eb_sso_version');
    if (!$oldVersion || version_compare('1.3.2', $oldVersion) == 1) {
        require_once plugin_dir_path(__FILE__).'includes/class-sso-activator.php';
        ebsso\SsoActivator::activate(false);
        //Save plugins current version in DB.
        update_option('eb_sso_version', '1.3.2');
    }
}

add_action('admin_init', 'pluginUpdateHook');

function deactivateEdwiserBridge()
{
    require_once plugin_dir_path(__FILE__).'includes/class-sso-deactivator.php';
    ebsso\SsoDeActivator::deactivate();
}

register_deactivation_hook(__FILE__, 'deactivateEdwiserBridge');

require_once 'includes/class-wdm-wusp-add-data-in-db.php';
$licens=new ebsso\SsoAddDataInDB($ssoPluginData);
$licens->initLicens();
/*
 * This code checks if new version is available
 */
if (!class_exists('ebsso\SsoPluginUpdater')) {
    include 'includes/class-wdm-wusp-plugin-updater.php';
}

$l_key = trim(get_option('edd_'.$ssoPluginData['plugin_slug'].'_license_key'));

// setup the updater
$pluginUpdate=new \ebsso\SsoPluginUpdater(
    $ssoPluginData['store_url'],
    __FILE__,
    array(
        'version' => $ssoPluginData['plugin_version'],
        'license' => $l_key,
        'item_name' => $ssoPluginData['plugin_name'],
        'author' => $ssoPluginData['author_name'],
        )
);
$pluginUpdate->initHook();

/*
 * Show row meta on the plugin screen, custom docs link added.
 */
function ebssoPluginRowMeta($links, $file)
{
    if ($file == plugin_basename(__FILE__)) {
        $row_meta = array(
            'docs' => '<a href="https://edwiser.org/bridge/extensions/single-sign-on/#Documentation" target="_blank"
                        title="'.esc_attr(__('Edwiser Bridge Single Sign On Documentation', 'single_sign_on_text_domain')).'">'.
                        __('Documentation', 'single_sign_on_text_domain').
                        '</a>',
        );

        return array_merge($links, $row_meta);
    }

    return (array) $links;
}
add_filter('plugin_row_meta', 'ebssoPluginRowMeta', 10, 2);

include_once 'includes/class-single-sign-on.php';
$GLOBALS['ebsso'] = new \ebsso\SingleSignOn($ssoPluginData['plugin_name'], $ssoPluginData['plugin_version']);


/*add_filter('login_form_middle', 'test', 10, 2);

function test($content, $args)
{

    error_log("args :: ".print_r($args, 1));
    error_log("content :: ".print_r($content, 1));

}
*/
