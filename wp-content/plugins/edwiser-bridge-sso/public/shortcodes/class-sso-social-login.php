<?php

namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoSocoalLogin
{
    private $plugin_name;
    private $version;

    public function __construct($pluginName, $version)
    {
        $this->plugin_name = $pluginName;
        $this->version     = $version;
              // add_action('login_form', array($this, 'output'), 100);
    }

    /**
     * function responsible for the social icons on the wordpress login page and the user-account page.
     * @return string
     */
    public function output($attr)
    {
        $gpLogin = new SsoGooglePlusInit($this->plugin_name, $this->version);
        $fbLogin = new SsoFacebookInit($this->plugin_name, $this->version);
        $ssoSettings = get_option("eb_sso_settings_general");

        if (isset($attr['wploginform']) && !$attr['wploginform']) {
            ob_start();
            ?>
            <ul class="eb-sso-cont-login-btns">
                <li><?php
                if ($gpLogin->loadDepend() && isset($ssoSettings['eb_sso_gp_enable']) && ($ssoSettings['eb_sso_gp_enable'] == 'both' || $ssoSettings['eb_sso_gp_enable'] == 'user_account')) {
                    echo $gpLogin->addGoogleLoginButton();
                }
                ?></li>
                <li><?php


                if ($fbLogin->loadDepend() && isset($ssoSettings['eb_sso_fb_enable']) && ($ssoSettings['eb_sso_fb_enable'] == 'both' || $ssoSettings['eb_sso_fb_enable'] == 'user_account')) {
                    echo $fbLogin->addFacebookLoginButton();
                }
                ?></li>
                <?php do_action("eb-sso-add-more-social-login-options-user-accnt-page"); ?>
            </ul>
            <?php
            ob_flush();
        } else {
            ob_start();
            ?>
            <ul class="eb-sso-cont-login-btns">
                <li><?php
                if ($gpLogin->loadDepend() && isset($ssoSettings['eb_sso_gp_enable']) && ($ssoSettings['eb_sso_gp_enable'] == 'both' || $ssoSettings['eb_sso_gp_enable'] == 'wp_login_page')) {
                    echo $gpLogin->addGoogleLoginButton();
                }
                ?></li>
                <li><?php
                if ($fbLogin->loadDepend() && isset($ssoSettings['eb_sso_fb_enable']) && ($ssoSettings['eb_sso_fb_enable'] == 'both' || $ssoSettings['eb_sso_fb_enable'] == 'wp_login_page')) {
                    echo $fbLogin->addFacebookLoginButton();
                }
                ?></li>
                <?php do_action("eb-sso-add-more-social-login-options-wp-login-page"); ?>
            </ul>
            <?php
            ob_flush();
        }
    }
}
