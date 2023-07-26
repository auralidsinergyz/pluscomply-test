<?php

namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     WisdmLabs, India <support@wisdmlabs.com>
 */
class SsoPublic
{

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     *
     * @var string The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     *
     * @var string The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    public function initPublic()
    {
        /**
         * Load ascript and styes on the login page of the wp since public and admin scripts are not get loaded on the wp login page
         */
        add_action('login_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action("login_enqueue_scripts", array($this, "enqueueScripts"));
        /**
         * Enqueue public scripts.
         */
        add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action("wp_enqueue_scripts", array($this, "enqueueScripts"));

        /**
         * Add social login buttons on the wp login form.
         */
        add_action('login_form', array($this, 'wpLoginFormSocialLogin'));
        /**
         * Add social login buttons on the edwiser user account page (In login form).
         */
        add_action('eb_login_form', array($this, 'socialLogin'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueueStyles()
    {
        wp_enqueue_style($this->plugin_name . "-public-style", EBSSO_URL . "/public/assets/css/sso-public-css.css", array(), $this->version);
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueueScripts()
    {
        wp_enqueue_script($this->plugin_name . "-public-script", EBSSO_URL . "/public/assets/js/sso-public-js.js", array('jquery'), $this->version);
    }

    public function socialLogin()
    {
        echo do_shortcode("[eb_sso_social_login wploginform = '0']");
    }

    public function wpLoginFormSocialLogin()
    {
        echo do_shortcode("[eb_sso_social_login wploginform = '1']");
    }
}
