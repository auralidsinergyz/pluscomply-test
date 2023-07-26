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

if (!class_exists('SSOSettingsSocialLogin')) {

    /**
     * WooIntSettings.
     */
    class SSOSettingsSocialLogin
    {

        public function getSocialLoginSettings()
        {
            global $current_tab;
            $option   = get_option('eb_' . $current_tab . '_general');




            $settings = apply_filters(
                'sso_social_login_settings_fields',
                array(
                    array(
                        'title' => __('Social Login Settings', "single_sign_on_text_domain"),
                        'type'  => 'title',
                        'id'    => 'sso_social_login_settings',
                        'class' => 'sso-social-login-settings'
                    ),
                    /*array(
                        'title'           => __('Enable Google plus login ', "single_sign_on_text_domain"),
                        'desc'            => __('Check this to enable google plus login on Edwiser-bridge user-account page.', "single_sign_on_text_domain"),
                        'id'              => 'eb_sso_gp_enable',
                        'default'         => $this->getOptionValue($option, 'eb_sso_gp_enable', "no"),
                        'type'            => 'checkbox',
                        'autoload' => false,
                    ),*/
                    /*array(
                        'title'    => __('Enable Facebook login', "single_sign_on_text_domain"),
                        'desc'     => __('Check this to enable facebook loginon Edwiser-bridge user-account page.', "single_sign_on_text_domain"),
                        'id'       => 'eb_sso_fb_enable',
                        'default'  => $this->getOptionValue($option, 'eb_sso_fb_enable', "no"),
                        'type'            => 'checkbox',
                        'autoload' => false,
                    ),*/
                    array(
                        'title'    => __('Google plus login ', 'single_sign_on_text_domain'),
                        'desc'     => '<br/>' .
                            __(
                                'Select page on which you want to enable Google plus login',
                                'single_sign_on_text_domain'
                            ),
                        'id'       => 'eb_sso_gp_enable',
                        'type'     => 'select',
                        'default'  => $this->getOptionValue($option, 'eb_sso_gp_enable'),
                        'css'      => 'min-width:300px;',
                        'options'     => array(
                            'no'  => __('Disable', "single_sign_on_text_domain"),
                            'user_account' => __("Edwiser user-account page", "single_sign_on_text_domain"),
                            'wp_login_page' => __("WP login page", "single_sign_on_text_domain"),
                            'both' => __("Edwiser user-account and WP login page", "single_sign_on_text_domain"),
                        ),
                        'desc_tip' => __(
                            'This enables Google plus login on the selected page.',
                            'single_sign_on_text_domain'
                        ),
                    ),
                    array(
                        'type' => 'sectionend',
                        'id'   => 'sso_social_login_settings',
                    ),
                    array(
                        'title' => __('', "single_sign_on_text_domain"),
                        'type'  => 'title',
                        'id'    => 'sso_gp_settings',
                        'class' => 'sso-gp-settings'
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
                        'title' => __('', "single_sign_on_text_domain"),
                        'type'  => 'title',
                        'id'    => 'sso-fb-settings',
                        'class' => 'sso-fb-settings'
                    ),
                    array(
                        'title'    => __('Facebook login ', 'single_sign_on_text_domain'),
                        'desc'     => '<br/>' .
                            __(
                                'Select page on which you want to enable Facebook login',
                                'single_sign_on_text_domain'
                            ),
                        'id'       => 'eb_sso_fb_enable',
                        'type'     => 'select',
                        'default'  => $this->getOptionValue($option, 'eb_sso_fb_enable'),
                        'css'      => 'min-width:300px;',
                        'options'     => array(
                            'no'  => __('Disable', "single_sign_on_text_domain"),
                            'user_account' => __("Edwiser user-account page", "single_sign_on_text_domain"),
                            'wp_login_page' => __("WP login page", "single_sign_on_text_domain"),
                            'both' => __("Edwiser user-account & WP login page", "single_sign_on_text_domain"),
                        ),
                        'desc_tip' => __(
                            'This enables Facebook login on the selected page.',
                            'single_sign_on_text_domain'
                        ),
                    ),
                    array(
                        'type' => 'sectionend',
                        'id'   => '',
                    ),
                    array(
                        'title' => __('', "single_sign_on_text_domain"),
                        'type'  => 'title',
                        'id'    => 'sso_fb_settings',
                        'class' => 'sso-fb-settings'
                    ),
                    array(
                        'title'    => __('App Id', "single_sign_on_text_domain"),
                        'desc'     => __('Enter your facebook app id here.', "single_sign_on_text_domain"),
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
                    ),
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
