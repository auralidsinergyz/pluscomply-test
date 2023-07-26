<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoManageMooLogin
{

    private $plugin_name;
    private $version;

    public function __construct($pluginName, $version)
    {
        $this->plugin_name = $pluginName;
        $this->version     = $version;
    }

    /**
     * Logging out user from moodle site.
     *
     * @since 1.0.0
     */
    public function mdlLoggedOut()
    {
        if (isset($_SESSION['eb_wp_user_id']) && '' != $_SESSION['eb_wp_user_id']) {
            $user_id = $_SESSION['eb_wp_user_id'];
            unset($_SESSION['eb_wp_user_id']);
        } else {
            return;
        }
        $moodle_user_id = get_user_meta($user_id, 'moodle_user_id', true);
        if ('' == $moodle_user_id) {
            return '';
        }
        $query = array(
            'moodle_user_id'  => $moodle_user_id,
            'logout_redirect' => apply_filters('eb_sso_logout_url', site_url()),
        );

        // encode array as querystring
        $final_url = generateMoodleLogoutUrl($query);
        if (filter_var($final_url, FILTER_VALIDATE_URL)) {
            wp_redirect($final_url);
            exit;
        }
    }

    /**
     * Logged in user on moodle site.
     *
     * @since 1.0.0
     */
    public function mdlLoggedIn($user_login, $user, $redirect = "")
    {
        //unnecessary variable
        unset($user_login);
        $moodle_user_id = get_user_meta($user->ID, 'moodle_user_id', true);
        if (empty($moodle_user_id)) {
            return;
        }
        $redirection = new SsoRedirection($this->plugin_name, $this->version);
        $query       = array(
            'moodle_user_id' => $moodle_user_id,
            'login_redirect' => $redirection->getLoginRedirectUrl($user, $redirect),
        );

        if (!empty($redirect)) {
            if (strpos($redirect, 'is_enroll') != false  || strpos($redirect, 'auto_enroll') != false) {
                $final_url = $redirect;
            } else {
                $final_url = generateMoodleUrl($query);
            }
        } else {
//             encode array as querystring
            $final_url = generateMoodleUrl($query);
        }


        if (filter_var($final_url, FILTER_VALIDATE_URL)) {
            wp_redirect($final_url);
            exit;
        }
    }
}
