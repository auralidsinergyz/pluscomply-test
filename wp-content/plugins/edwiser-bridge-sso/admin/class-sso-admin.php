<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://edwiser.org
 * @since      1.0.0
 *
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SSOAdmin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     *
     * @var string The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     *
     * @var string The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    public function adminInit()
    {
        add_filter("eb_get_settings_pages", array($this, "pluginSettings"));
        add_action('eb_settings_tabs', array($this, 'loadScripts'));
    }

    public function loadScripts()
    {
        $this->enqueueStyles();
        $this->enqueueScripts();
    }

    public function pluginSettings($settings)
    {
        $settings[] = include EBSSO_DIR_PATH . '/admin/class-sso-settings.php';
        return $settings;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    private function enqueueStyles()
    {
        wp_enqueue_style($this->plugin_name . '_css', EBSSO_URL . "/admin/assets/admin.css", array(), $this->version);
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    private function enqueueScripts()
    {
        wp_enqueue_script($this->plugin_name . '_js', EBSSO_URL . '/admin/assets/admin.js', array('jquery'), $this->version);
        $nonce = wp_create_nonce('ebsso-verify-key');
        $data  = array(
            'ajaxurl'     => admin_url('admin-ajax.php'),
            'nonce'       => $nonce,
            'plugin_url'  => EB_PLUGIN_URL,
            'invalid_url' => __("Entered URL is invalid, Please check URL again.", 'single_sign_on_text_domain'),
            'empty_url'   => __("Please enter URL.", 'single_sign_on_text_domain'),
            'select_role' => __("Please select user role first.", 'single_sign_on_text_domain'),
        );
        wp_localize_script($this->plugin_name . '_js', 'ebssoAdSet', $data);
    }
}
