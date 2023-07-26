<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoGooglePlusUserManager
{
    private $pluginName;
    private $version;
    private $googleClient;
    private $gOauth2Service;
    private $ebLogger;

    public function __construct($pluginName, $version)
    {
        $this->pluginName     = $pluginName;
        $this->version        = $version;
        $this->ebLogger       = \app\wisdmlabs\edwiserBridge\edwiserBridgeInstance();
        $this->googleClient   = SsoGooglePlusInit::getGoogleClient();
        $this->gOauth2Service = SsoGooglePlusInit::getGoogleOauth2Service();
    }

    public function googleLogin()
    {
        $userData = null;
        try {
            if (isset($_GET['code'])) {
                $this->googleClient->authenticate($_GET['code']);
                $_SESSION['token'] = $this->googleClient->getAccessToken();
            } else {
                return;
            }

            if (isset($_SESSION['token'])) {
                try {
                    $this->googleClient->setAccessToken($_SESSION['token']);
                } catch (Exception $e) {
                    $this->ebLogger->logger()->add("SSO Log: Google plus login access token exception" . serialize($e->getMessage()));
                }
            }
            if ($this->googleClient->getAccessToken()) {
                try {
                    //Get user profile data from google
                    $gpUserProfile = $this->gOauth2Service->userinfo->get();
                    $gpUserData    = array(
                        'oauth_provider' => 'google',
                        'oauth_uid'      => getArrayDataByIndex($gpUserProfile, 'id'),
                        'first_name'     => getArrayDataByIndex($gpUserProfile, 'given_name'),
                        'last_name'      => getArrayDataByIndex($gpUserProfile, 'family_name'),
                        'email'          => getArrayDataByIndex($gpUserProfile, 'email'),
                        'gender'         => getArrayDataByIndex($gpUserProfile, 'gender'),
                        'locale'         => getArrayDataByIndex($gpUserProfile, 'locale'),
                        'picture'        => getArrayDataByIndex($gpUserProfile, 'picture'),
                        'link'           => getArrayDataByIndex($gpUserProfile, 'link')
                    );
                } catch (Exception $e) {
                    $this->ebLogger->logger()->add("SSO Log: Google plus login failed to fetch google plus profile data." . serialize($e->getMessage()));
                    auth_redirect();
                    exit();
                }
                $userManager = new SsoSocialLoginUserManager($this->pluginName, $this->version);
                $redirect    = $this->getState();
                $userData    = $userManager->checkUserDetails($gpUserData, $redirect);
            }
        } catch (Exception $e) {
            $this->ebLogger->logger()->add("SSO Log: Google plus login failed with exception: " . serialize($e->getMessage()));
        }
    }

    private function getState()
    {
        if (isset($_GET['state'])) {
            $state = base64_decode($_GET['state']);
            $state = json_decode($state);
            return $state;
        } else {
            return get_site_url();
        }
    }
}
