<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoFacebookUserManager
{

    private $pluginName;
    private $version;
    private $fClient;
    private $fClientHelper;
    private $fOauth2Service;
    private $ebLogger;

    public function __construct($pluginName, $version)
    {
        $this->pluginName     = $pluginName;
        $this->version        = $version;
        $this->ebLogger       = \app\wisdmlabs\edwiserBridge\edwiserBridgeInstance();
        $this->fClient        = SsoFacebookInit::getFaceboookClient();
        $this->fClientHelper  = SsoFacebookInit::getFaceboookClientHelper();
        $this->fOauth2Service = SsoFacebookInit::getFacebookOauth2Service();
    }

    public function facebookLogin()
    {
        $helper   = $this->fClient->getRedirectLoginHelper();
        $userData = null;
        // Try to get access token
        try {
            // Already login
            if (isset($_SESSION['facebook_access_token'])) {
                $accessToken = $_SESSION['facebook_access_token'];
            } else {
                $accessToken = $helper->getAccessToken();
            }

            if (isset($accessToken)) {
                if (isset($_SESSION['facebook_access_token'])) {
                    $this->fClient->setDefaultAccessToken($_SESSION['facebook_access_token']);
                } else {
                    // Put short-lived access token in session
                    $_SESSION['facebook_access_token'] = (string) $accessToken;

                    // OAuth 2.0 client handler helps to manage access tokens
                    $this->fOauth2Service = $this->fClient->getOAuth2Client();

                    // Exchanges a short-lived access token for a long-lived one
                    $longLivedAccessToken              = $this->fOauth2Service->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                    $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

                    // Set default access token to be used in script
                    $this->fClient->setDefaultAccessToken($_SESSION['facebook_access_token']);
                }
                // Redirect the user back to the same page if url has "code" parameter in query string
                if (isset($_GET['code'])) {
                    // Getting user facebook profile info
                    try {
                        $profileRequest = $this->fClient->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
                        $fbUserProfile  = $profileRequest->getGraphNode()->asArray();
                        $picture=getArrayDataByIndex($fbUserProfile, 'picture');
                        $fbUserData  = array(
                            'oauth_provider' => 'facebook',
                            'oauth_uid'      => getArrayDataByIndex($fbUserProfile, 'id'),
                            'first_name'     => getArrayDataByIndex($fbUserProfile, 'first_name'),
                            'last_name'      => getArrayDataByIndex($fbUserProfile, 'last_name'),
                            'email'          => getArrayDataByIndex($fbUserProfile, 'email'),
                            'gender'         => getArrayDataByIndex($fbUserProfile, 'gender'),
                            'locale'         => getArrayDataByIndex($fbUserProfile, 'locale'),
                            'picture'        => getArrayDataByIndex($picture, 'url'),
                            'link'           => getArrayDataByIndex($fbUserProfile, 'link')
                        );
                        $userManager = new SsoSocialLoginUserManager($this->pluginName, $this->version);
                        $redirect    = $this->getState();
                        $userData    = $userManager->checkUserDetails($fbUserData, $redirect);
                    } catch (FacebookResponseException $e) {
                        $this->ebLogger->logger()->add("SSO Log: Facebook login Failed to fetch user profile data." . serialize($e->getMessage()));
                        session_destroy();
                        auth_redirect();
                        exit;
                    } catch (FacebookSDKException $e) {
                        $this->ebLogger->logger()->add("SSO Log: Facebook login Failed ." . serialize($e->getMessage()));
                        auth_redirect();
                        exit;
                    }
                }
            }
        } catch (FacebookResponseException $e) {
            $this->ebLogger->logger()->add("SSO Log: Facebook login Failed got facebook responce exception." . serialize($e->getMessage()));
            session_destroy();
            // Redirect user back to app login page
            auth_redirect();
            exit;
        } catch (FacebookSDKException $e) {
            $this->ebLogger->logger()->add("SSO Log: Facebook login Failed got facebook SDK exception." . serialize($e->getMessage()));
            auth_redirect();
            exit;
        }
    }

    private function getState()
    {
        /*if (isset($_GET['state_data'])) {
            $state = base64_decode($_GET['state_data']);
            $state = json_decode($state);
            return $state;
        } else {
            return get_site_url();
        }*/


        if (isset($_SESSION['state-data'])) {
            $state = base64_decode($_SESSION['state-data']);
            $state = json_decode($state);
            return $state;
        } else {
            return get_site_url();
        }
    }
}
