<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoGooglePlusInit
{

    private $pluginName;
    private $version;
    protected static $gClient        = null;
    protected static $gOauth2Service = null;

    public function __construct($pluginName, $version)
    {
        $this->pluginName = $pluginName;
        $this->version    = $version;
    }

    public function loadDepend()
    {
        $keys   = array(
            'eb_sso_gp_client_id',
            'eb_sso_gp_secret_key',
            'eb_sso_gp_app_name',
            'eb_sso_gp_enable'
        );
        $option = $this->getSettingData($keys);
        if ($option == false || !$this->checkIsSocialLoginEnabled($option, 'eb_sso_gp_enable')) {
            return;
        }
        $this->loadGooglePlusConfig($option);
        include_once 'class-sso-gp-logout-user.php';
        include_once 'class-sso-gp-user-manager.php';
        $gpLogout=new SsoGooglePlusLogout();
        $gpLogout->init();

        if (!is_user_logged_in() && !(isset($_GET['action']) && $_GET['action'] == 'facebook_login')) {
            $userManager = new SsoGooglePlusUserManager($this->pluginName, $this->version);
            $userManager->googleLogin();
        }
        return true;
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

    private function checkIsSocialLoginEnabled($data, $key)
    {
        if (isset($data[$key]) && $data[$key] == "no") {
            return false;
        }
        return true;
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

    private function loadGooglePlusConfig($option)
    {
        if ($option == false) {
            return;
        }
        $clientId      = $option['eb_sso_gp_client_id']; //Google client ID
        $clientSecret  = $option['eb_sso_gp_secret_key']; //Google client secret
        $clientAppName = $option['eb_sso_gp_app_name']; // Google client app name

        /*
         * Configuration and setup Google API
         */
        include_once 'src/Google_Client.php';
        include_once 'src/contrib/Google_Oauth2Service.php';
        self::$gClient        = new \Google_Client();
        self::$gClient->setApplicationName($clientAppName);
        self::$gClient->setClientId($clientId);
        self::$gClient->setClientSecret($clientSecret);
        self::$gClient->setRedirectUri(get_site_url());
        self::$gClient->setState(getSocialRedirectToURL($_GET, ""));
        self::$gOauth2Service = new \Google_Oauth2Service($this->getGoogleClient());
    }

    public static function getGoogleClient()
    {
        return self::$gClient;
    }

    public static function getGoogleOauth2Service()
    {
        return self::$gOauth2Service;
    }

    public function addGoogleLoginButton()
    {
        if (self::$gClient == null) {
            return;
        }
        $authUrl = self::$gClient->createAuthUrl();
        ob_start();
        ?>
        <a href="<?php echo filter_var($authUrl, FILTER_SANITIZE_URL); ?>">
            <img  class="eb-sso-social-login-icon" src="<?php echo esc_url(EBSSO_URL . "/assets/images/ic_google_plus.png"); ?>"/>
        </a>
        <?php
        $login   = ob_get_clean();
        return $login;
    }
}
