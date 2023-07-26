<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoFacebookLogout
{

    private $fClientHelper;

    public function init()
    {
        $this->fClientHelper = SsoFacebookInit::getFaceboookClientHelper();
        if (\is_user_logged_in()) {
            add_action('wp_logout', array($this, 'gpLogoutUser'));
        }
    }

    public function gpLogoutUser()
    {
        unset($_SESSION['facebook_access_token']);
        unset($_SESSION['userData']);
        $this->fClientHelper->getReRequestUrl(get_site_url(), array("email"));
        session_destroy();
    }
}
