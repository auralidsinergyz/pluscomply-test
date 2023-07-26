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

if (!class_exists('SSOSettingsGeneral')) {

    /**
     * WooIntSettings.
     */
    class SSOSettingsGeneral
    {

        public function getGeneralSettings()
        {
            global $current_tab;
            $option   = get_option('eb_' . $current_tab . '_general');
            $settings = apply_filters(
                'sso_social_login_settings_fields',
                array(
                    array(
                        'title' => __('General Settings', "single_sign_on_text_domain"),
                        'type'  => 'title',
                        'id'    => 'sso_options',
                    ),
                    array(
                        'title'             => __('Secret Key', "single_sign_on_text_domain"),
                        'desc'              => __('Enter your secret key here.', "single_sign_on_text_domain"),
                        'id'                => 'eb_sso_secret_key',
                        'default'           => $this->getOptionValue($option, 'eb_sso_secret_key'),
                        'type'              => 'text',
                        'desc_tip'          => true,
                    ),
                    array(
                        'title'    => __('', "single_sign_on_text_domain"),
                        'desc'     => __('', "single_sign_on_text_domain"),
                        'id'       => 'eb_sso_verify_key',
                        'default'  => __('Verify token with moodle', "single_sign_on_text_domain"),
                        'type'     => 'button',
                        'desc_tip' => false,
                        'class'    => 'button secondary',
                    ),
                    array(
                        'type' => 'sectionend',
                        'id'   => 'sso_sl_settings',
                    ),
                    /*array(
                        'title' => __('Google plus login settings', "single_sign_on_text_domain"),
                        'type'  => 'title',
                        'id'    => 'sso_gp_settings',
                        'class' => 'sso-social-login-settings'
                    ),
                    array(
                        'title'           => __('Enable Google plus login on user-account page ', "single_sign_on_text_domain"),
                        'desc'            => __('Check this to enable google plus login on Edwiser-bridge user-account page.', "single_sign_on_text_domain"),
                        'id'              => 'eb_sso_gp_enable',
                        'default'         => $this->getOptionValue($option, 'eb_sso_gp_enable', "no"),
                        'type'            => 'checkbox',
                        'autoload' => false,
                    ),
                    array(
                        'title'           => __('Enable Google plus login on WP login page', "single_sign_on_text_domain"),
                        'desc'            => __('Check this to enable google plus login on Wordpress login page.', "single_sign_on_text_domain"),
                        'id'              => 'eb_wp_sso_gp_enable',
                        'default'         => $this->getOptionValue($option, 'eb_wp_sso_gp_enable', "no"),
                        'type'            => 'checkbox',
                        'autoload' => false,
                    ),
                    array(
                        'title'    => __('Client Id', "single_sign_on_text_domain"),
                        'desc'     => __('Enter your google plus client id here.', "single_sign_on_text_domain"),
                        'id'       => 'eb_sso_gp_client_id',
                        'default'  => $this->getOptionValue($option, 'eb_sso_gp_client_id'),
                        'type'     => 'text',
                        'desc_tip' => true,
                    ),
                    array(
                        'title'             => __('Client Secret', "single_sign_on_text_domain"),
                        'desc'              => __('Enter your google plus app secret key here.', "single_sign_on_text_domain"),
                        'id'                => 'eb_sso_gp_secret_key',
                        'default'           => $this->getOptionValue($option, 'eb_sso_gp_secret_key'),
                        'type'              => 'text',
                        'desc_tip'          => true,
                    ),
                    array(
                        'title'             => __('Application Name', "single_sign_on_text_domain"),
                        'desc'              => __('Enter your google plus app name here.', "single_sign_on_text_domain"),
                        'id'                => 'eb_sso_gp_app_name',
                        'default'           => $this->getOptionValue($option, 'eb_sso_gp_app_name'),
                        'type'              => 'text',
                        'desc_tip'          => true,
                    ),
                    array(
                        'type' => 'sectionend',
                        'id'   => 'sso_gp_settings',
                    ),
                    array(
                        'title' => __('Facebook login settings', "single_sign_on_text_domain"),
                        'type'  => 'title',
                        'id'    => 'sso_gp_settings',
                        'class' => 'sso-social-login-settings'
                    ),
                    array(
                        'title'    => __('Enable Facebook login on user-account page', "single_sign_on_text_domain"),
                        'desc'     => __('Check this to enable facebook loginon Edwiser-bridge user-account page.', "single_sign_on_text_domain"),
                        'id'       => 'eb_sso_fb_enable',
                        'default'  => $this->getOptionValue($option, 'eb_sso_fb_enable', "no"),
                        'type'            => 'checkbox',
                        'autoload' => false,
                    ),
                    array(
                        'title'    => __('Enable Facebook login on WP login page', "single_sign_on_text_domain"),
                        'desc'     => __('Check this to enable facebook login on wordpress login page.', "single_sign_on_text_domain"),
                        'id'       => 'eb_wp_sso_fb_enable',
                        'default'  => $this->getOptionValue($option, 'eb_wp_sso_fb_enable', "no"),
                        'type'            => 'checkbox',
                        'autoload' => false,
                    ),
                    array(
                        'title'    => __('App Id', "single_sign_on_text_domain"),
                        'desc'     => __('Enter your facebook App id here.', "single_sign_on_text_domain"),
                        'id'       => 'eb_sso_fb_app_id',
                        'default'  => $this->getOptionValue($option, 'eb_sso_fb_app_id'),
                        'type'     => 'text',
                        'desc_tip' => true,
                    ),
                    array(
                        'title'    => __('App Secret', "single_sign_on_text_domain"),
                        'desc'     => __('Enter your facebook app Secret key here.', "single_sign_on_text_domain"),
                        'id'       => 'eb_sso_fb_app_secret_key',
                        'default'  => $this->getOptionValue($option, 'eb_sso_fb_app_secret_key'),
                        'type'     => 'text',
                        'desc_tip' => true,
                    ),
                    array(
                        'type' => 'sectionend',
                        'id'   => 'sso_fb_settings',
                    ),*/
                )
            );
            return $settings;
        }

        private function getOptionValue($data, $key, $default = "")
        {
            if (isset($data[$key])) {
                return $data[$key];
            } else {
                return $default;
            }
        }
    }
}
