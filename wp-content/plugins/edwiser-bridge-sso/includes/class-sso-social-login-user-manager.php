<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoSocialLoginUserManager
{

    private $plugin_name;
    private $version;
    private $userTbl;
    private $ebLogger;

    public function __construct($pluginName, $version)
    {
        $this->plugin_name = $pluginName;
        $this->version     = $version;
        $this->ebLogger    = \app\wisdmlabs\edwiserBridge\edwiserBridgeInstance();
    }

    public function checkUserDetails($socialProfData = array(), $redirect = "")
    {
        global $wpdb;
        $userData  = false;
        $checkKeys = array('oauth_uid', 'first_name', 'last_name', 'email');
        foreach ($checkKeys as $key) {
            if (empty($socialProfData[$key])) {
                auth_redirect();
                exit();
            }
        }
        if (!empty($socialProfData)) {
            $this->ebLogger->logger()->add("SSO", "SSO Log: Checking user is already exist or not for the emial address: " . $socialProfData['email'] . "using provider: " . $socialProfData['oauth_provider']);
            $this->userTbl = $wpdb->prefix . 'gp_oauth_users';
            $stmtCheckUser = "SELECT * FROM " . $this->userTbl . " WHERE oauth_provider = '" . $socialProfData['oauth_provider'] . "' AND oauth_uid = '" . $socialProfData['oauth_uid'] . "'";
            $prevResult    = $wpdb->get_row($stmtCheckUser, ARRAY_A);
            if ($prevResult == null) {
                $this->addUser($socialProfData);
            } else {
                $where = array(
                    'oauth_provider' => $prevResult['oauth_provider'],
                    'oauth_uid'      => $prevResult['oauth_uid']
                );
                $this->updateUserData($socialProfData, $where);
            }
            $userData = $wpdb->get_row($stmtCheckUser, ARRAY_A);
        }
        $this->redirectUser($socialProfData['email'], $userData, $redirect);
    }

    private function redirectUser($socialLoginEmial, $userData, $redirect)
    {
        if ($userData) {
            /**
             * Get wordpress user id by email address
             */
            $wpUser   = get_user_by("email", $socialLoginEmial);
            /**
             * Login user to WordPress site
             */
            $this->ebLogger->logger()->add("SSO", "SSO Log: User registrerd with email address: " . $socialLoginEmial);
            setLoginData($wpUser);
            $mdlLogIn = new SsoManageMooLogin($this->plugin_name, $this->version);
            $mdlLogIn->mdlLoggedIn("", $wpUser, $redirect);
        } else {
            $this->ebLogger->logger()->add("SSO", "SSO Log: User registration failed");
            auth_redirect();
            exit();
        }
    }

    private function addUser($userData)
    {
        global $wpdb;
        $wpdb->insert($this->userTbl, $userData);
        $this->ebLogger->logger()->add("SSO", "SSO Log: Creating new wordpress user with email: " . $gpUserData['email'] . "using provider: " . $gpUserData['oauth_provider']);
        /**
         * Create wordPress user on sucessfull registration this will call the edwiser bridge create user to create new user.
         */
        createEbUser($userData);
        /**
         * Get wordpress user id by email address
         */
        $wpUser = get_user_by("email", $userData['email']);
        /**
         * Update google plus user data. Add wp user id to identify the user.
         */
        $this->updateUserData(array('wp_user_id' => $wpUser->ID), array("email" => $userData['email']));
    }

    private function updateUserData($userData, $where)
    {
        global $wpdb;
        $wpUser = get_user_by("email", $userData['email']);
        if (!$wpUser) {
            createEbUser($userData);
        }
        $this->ebLogger->logger()->add("SSO", "SSO Log: updating user with email: " . $userData['email']);
        $wpdb->update($this->userTbl, $userData, $where);
    }
}
