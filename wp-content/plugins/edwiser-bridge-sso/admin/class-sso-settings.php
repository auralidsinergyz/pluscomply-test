<?php

namespace ebsso;

/*
 * EDW General Settings
 *
 * @link       https://edwiser.org
 * @since      1.0.0
 *
 * @package    Edwiser Bridge
 * @subpackage Edwiser Bridge/admin
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SSOSettings')) {

    /**
     * WooIntSettings.
     */
    class SSOSettings extends \app\wisdmlabs\edwiserBridge\EBSettingsPage
    {

        private $generalSettings  = null;
        private $redirectSettings = null;
        private $socialLoginSettings = null;

        /**
         * Constructor.
         */
        public function __construct()
        {
            include_once 'settings/class-sso-settings-general.php';
            include_once 'settings/class-sso-settings-redirection.php';
            include_once 'settings/class-sso-settings-save.php';
            include_once 'settings/class-sso-settings-social-login.php';
            $this->generalSettings  = new SSOSettingsGeneral();
            $this->redirectSettings = new SSOSettingsRedirection();
            $this->socialLoginSettings = new SSOSettingsSocialLogin();
            $this->_id              = 'sso_settings';
            $this->label            = __('Single Sign On', "single_sign_on_text_domain");
            add_filter('eb_settings_tabs_array', array($this, 'addSettingsPage'), 20);
            add_action('eb_settings_' . $this->_id, array($this, 'output'));
            add_action('eb_settings_save_' . $this->_id, array($this, 'save'));
            add_action('eb_sections_' . $this->_id, array($this, 'outputSections'));
        }

        public function getSections()
        {
            $sections = array(
                ''            => __('General', 'single_sign_on_text_domain'),
                'redirection' => __('Redirection', 'single_sign_on_text_domain'),
                'social_login' => __("Social login", 'single_sign_on_text_domain'),
            );
            return apply_filters('eb_getSections_' . $this->_id, $sections);
        }

        public function output()
        {
            global $current_section;
            $settings = $this->getSettings($current_section);
            \app\wisdmlabs\edwiserBridge\EbAdminSettings::outputFields($settings);
        }

        public function getSettings($current_section = '')
        {
            if ($current_section == 'redirection') {
                $settings = array();
                $this->redirectSettings->getUserRedirectionSettings();
            } elseif (isset($_GET['section']) && $_GET['section'] == 'social_login') {
                $settings = $this->socialLoginSettings->getSocialLoginSettings($_POST);
            } else {
                $settings = $this->generalSettings->getGeneralSettings();
            }
            return apply_filters('eb_get_settings_' . $this->_id, $settings);
        }

        /**
         * Save settings.
         *
         * @since  1.0.0
         */
        public function save()
        {
            // global $current_tab;
            if (empty($_POST)) {
                return false;
            }
            $saveSettings = new SSOSettingsSave();
            if (isset($_GET['section']) && $_GET['section'] == 'redirection') {
                $saveSettings->saveRedirectionSettigns($_POST);
            } elseif (isset($_GET['section']) && $_GET['section'] == 'social_login') {
                // $settings = $this->socialLoginSettings->getSocialLoginSettings($_POST);
                $saveSettings->saveSociaLoginSettings($_POST);
            } else {
                $saveSettings->saveGeneralSettigns($_POST);
            }
        }
    }
}

return new SSOSettings();
