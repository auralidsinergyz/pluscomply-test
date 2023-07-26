<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoRedirection
{

    private $version;
    private $plugin_name;

    public function __construct($plugin_name, $version)
    {
        $this->version     = $version;
        $this->plugin_name = $plugin_name;
    }

    /**
     * Get login redirect url.
     *
     * @return $redirect_url
     * @since 1.2
     */
    public function getLoginRedirectUrl($user, $defaultRedirectUrl = "")
    {
        $post_content = null;
        $redirectUrl  = $this->getUserRedirectUrl($user);

        if (empty($redirectUrl)) {
            if (!empty($defaultRedirectUrl)) {
                $redirectUrl = $defaultRedirectUrl;
            } else {
                $redirectUrl = get_site_url();
            }
        }

        $get          = array();
        if (isset($_SERVER['HTTP_REFERER']) && filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL)) {
            $postid       = url_to_postid($_SERVER['HTTP_REFERER']);
            $post_content = $postid ? get_post($postid)->post_content : null;
            parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $get);
        }
        $redirect_url = getRedirectUrl($get, $post_content, $redirectUrl);
        if (isset($get['redirect_to']) && filter_var($get['redirect_to'], FILTER_VALIDATE_URL) && isset($get['is_enroll'])) {
            $redirect_url = $get['redirect_to'];
            $redirect_url = add_query_arg("auto_enroll", "true", $redirect_url);
        }
        return apply_filters('eb_sso_login_url', $redirect_url);
    }

    private function getUserRedirectUrl($user)
    {
        $redirectUrls = get_option('eb_sso_settings_redirection');
        if ($redirectUrls['ebsso_role_base_redirect'] == 'no') {
            return $this->getRedirectUrl($redirectUrls, "ebsso_login_redirect_url");
        } else {
            return $this->getRedirectUrl($redirectUrls, 'ebsso_login_redirect_url_' . $user->roles[0]);
        }
    }

    private function getRedirectUrl($data, $role)
    {
        /*if (isset($data[$role]) && !empty($data[$role])) {
            return $data[$role];
        } else {
            return get_site_url();
        }*/


        if (isset($data[$role]) && !empty($data[$role])) {
            return $data[$role];
        } elseif (isset($data['ebsso_login_redirect_url']) && !empty($data['ebsso_login_redirect_url'])) {
            return $data['ebsso_login_redirect_url'];
        }

        return 0;
    }
}
