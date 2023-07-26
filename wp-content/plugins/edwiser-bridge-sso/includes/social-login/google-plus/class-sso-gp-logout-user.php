<?php

namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoGooglePlusLogout
{
    public function init()
    {
        if (\is_user_logged_in()) {
            add_action('wp_logout', array($this, 'gpLogoutUser'));
        }
    }

    public function gpLogoutUser()
    {
        unset($_SESSION['token']);
        unset($_SESSION['userData']);
        SsoGooglePlusInit::getGoogleClient()->revokeToken();
        session_destroy();
    }
}
