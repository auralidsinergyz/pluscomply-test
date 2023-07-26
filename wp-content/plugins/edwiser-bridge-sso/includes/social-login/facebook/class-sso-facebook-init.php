<?php

namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoFacebookInit
{

    private $pluginName;
    private $version;
    protected static $fClient        = null;
    protected static $fClientHelper  = null;
    protected static $fOauth2Service = null;

    public function __construct($pluginName, $version)
    {
        $this->pluginName = $pluginName;
        $this->version    = $version;
    }

    public function loadDepend()
    {
        $keys   = array(
            'eb_sso_fb_app_id',
            'eb_sso_fb_app_secret_key',
            'eb_sso_fb_enable'
        );
        $option = $this->getSettingData($keys);
        if ($option == false || !$this->checkIsSocialLoginEnabled($option, 'eb_sso_fb_enable')) {
            return;
        }
        $this->loadFacebookPlusConfig($option);
        include_once 'class-sso-fb-user-manager.php';
        include_once 'class-sso-fb-logout-user.php';
        $fbLogout=new SsoFacebookLogout();
        $fbLogout->init();
        if (!is_user_logged_in() && isset($_GET['action']) && $_GET['action'] == 'facebook_login') {
            $fbUserMang = new SsoFacebookUserManager($this->pluginName, $this->version);
            $fbUserMang->facebookLogin();
        }
        return true;
    }

    private function loadFacebookPlusConfig($option)
    {
        if ($option == false) {
            return;
        }
        $appId     = $option['eb_sso_fb_app_id'];
        $appSecret = $option['eb_sso_fb_app_secret_key'];

        /*
         * Configuration and setup Google API
         */
        include_once 'src/autoload.php';
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        self::$fClient        = new \Facebook\Facebook(array(
            'app_id'                  => $appId,
            'app_secret'              => $appSecret,
            'default_graph_version'   => 'v2.10',
            'persistent_data_handler' => 'session'
        ));
        self::$fClientHelper  = self::$fClient->getRedirectLoginHelper();
        self::$fOauth2Service = self::$fClient->getOAuth2Client();
    }

    private function getSettingData($keys = array())
    {
        $option = get_option("eb_sso_settings_general");
        if ($option !== false) {
            foreach ($keys as $key) {
                if (!$this->checkIsSet($option, $key)) {
                    $option = false;
                }
            }
        }
        return $option;
    }

    private function checkIsSet($data, $key)
    {
        $value = false;
        if (isset($data[$key])) {
            $value = trim($data[$key]);
        }
        if (empty($value)) {
            $value = false;
        }
        return $value;
    }

    private function checkIsSocialLoginEnabled($data, $key)
    {
        if (isset($data[$key]) && $data[$key] == "no") {
            return false;
        }
        return true;
    }

    public static function getFaceboookClient()
    {
        return self::$fClient;
    }

    public static function getFaceboookClientHelper()
    {
        return self::$fClientHelper;
    }

    public static function getFacebookOauth2Service()
    {

        return self::$fOauth2Service;
    }

    public function addFacebookLoginButton()
    {
        if (self::$fClientHelper == null) {
            return;
        }
        $permissions = ['email']; // Optional permissions
        $state= getSocialRedirectToURL($_GET, "");
        // $url=get_site_url() . "/?action=facebook_login&state_data=".$state;






        $_SESSION["state-data"] = $state;


        $url=get_site_url() . "/?action=facebook_login";



        $loginUrl    = self::$fClientHelper->getLoginUrl($url, $permissions);


        $this->getFaceboookClient();
        ob_start();
        ?>
        <a href="<?php echo filter_var($loginUrl, FILTER_SANITIZE_URL); ?>">
            <img  class="eb-sso-social-login-icon" src="<?php echo esc_url(EBSSO_URL . "/assets/images/facebook.png"); ?>"/>
        </a>
        <?php
        $login       = ob_get_clean();
        return $login;
        /*$fb = new \Facebook\Facebook([
          'app_id' => '200654573977452',
          'app_secret' => '3a950e1ce3cba9fd983ac8dd85d4185b',
          'default_graph_version' => 'v2.10',
          ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        // $loginUrl = $helper->getLoginUrl('https://example.com/fb-callback.php', $permissions);
        $loginUrl = $helper->getLoginUrl(get_site_url(), $permissions);

error_log(" login url 2222 :: ".print_r($loginUrl, 1));

        // echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

        ob_start();
        ?>
        <a href="<?php echo filter_var($loginUrl, FILTER_SANITIZE_URL); ?>">
            <img  class="eb-sso-social-login-icon" src="<?php echo esc_url(EBSSO_URL . "/assets/images/facebook.png"); ?>"/>
        </a>
        <?php
        $login       = ob_get_clean();
        return $login;*/
    }
}
