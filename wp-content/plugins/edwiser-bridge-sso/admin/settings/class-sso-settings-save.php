<?php

namespace ebsso;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SSOSettingsSave')) {

    /**
     * WooIntSettings.
     */
    class SSOSettingsSave
    {

        /**
         * Provides the functionality to save the SSO redirection data.
         *
         */
        public function saveRedirectionSettigns()
        {
            global $current_tab;
            $settings      = $this->removeData();
            // $savedSettings = array();
            if (isset($settings['ebsso_role_base_redirect']) && $settings['ebsso_role_base_redirect'] == 'on') {
                $settings['ebsso_role_base_redirect'] = 'on';
            } else {
                $settings['ebsso_role_base_redirect'] = 'no';
            }
            update_option('eb_' . $current_tab . '_redirection', $settings);
        }


        public function saveSociaLoginSettings($data)
        {
            global $current_tab;

            $oldSettings = get_option("eb_sso_settings_general");

            $settings         = new SSOSettingsSocialLogin();
            $options          = $settings->getSocialLoginSettings();
            $formData         = $this->getFormData($options, $data);
            $upd_opt_filtered = array_filter($formData);
            if (isset($oldSettings["eb_sso_secret_key"]) && !empty($oldSettings["eb_sso_secret_key"])) {
                $upd_opt_filtered["eb_sso_secret_key"] = $oldSettings["eb_sso_secret_key"];
            }
            update_option('eb_' . $current_tab . '_general', $upd_opt_filtered);
        }


        /**
         *  Provides the functionality to remove the extra data which comes with
         *  form submission
         */
        private function removeData()
        {
            $data = array("ebsso-role-top",
                "ebsso-role-bottom",
                "ebsso_selected_login_redirect_url_top",
                "ebsso_selected_login_redirect_url_bottom",
                "save",
                "subtab",
                "_wpnonce",
                "_wp_http_referer");
            foreach ($data as $key) {
                if (isset($_POST[$key])) {
                    unset($_POST[$key]);
                }
            }
            return $_POST;
        }

        /**
         * Provides the functionality to save the general settings.
         */
        public function saveGeneralSettigns($data)
        {
            global $current_tab;

            $oldSettings = get_option("eb_sso_settings_general");
            $settings         = new SSOSettingsGeneral();
            $options          = $settings->getGeneralSettings();
            $formData         = $this->getFormData($options, $data);
            $upd_opt_filtered = array_filter($formData);
            $oldSettings["eb_sso_secret_key"] = $upd_opt_filtered["eb_sso_secret_key"];

            update_option('eb_' . $current_tab . '_general', $oldSettings);
        }

        private function getFormData($options, $data)
        {
            $update_options = array();

            // Loop options and get values to save
            foreach ($options as $value) {
                if (!isset($value['id']) || !isset($value['type'])) {
                    continue;
                }

                // Get posted value
                if (strstr($value['id'], '[')) {
                    parse_str($value['id'], $option_name_array);

                    $option_name  = current(array_keys($option_name_array));
                    $setting_name = key($option_name_array[$option_name]);
                    $option_value = null;
                    if (isset($data[$option_name][$setting_name])) {
                        $option_value = wp_unslash($data[$option_name][$setting_name]);
                    }
                } else {
                    $option_name  = $value['id'];
                    $setting_name = '';
                    $option_value = null;
                    if (isset($_POST[$value['id']])) {
                        $option_value = wp_unslash($data[$value['id']]);
                    }
                }

                $option_value = $this->getFieldTypeValue($value, $option_value);

                if (!is_null($option_value)) {
                    // Check if option is an array
                    if ($option_name && $setting_name) {
                        // Get old option value
                        if (!isset($update_options[$option_name])) {
                            $update_options[$option_name] = get_option($option_name, array());
                        }

                        if (!is_array($update_options[$option_name])) {
                            $update_options[$option_name] = array();
                        }

                        $update_options[$option_name][$setting_name] = $option_value;

                        // Single value
                    } else {
                        $update_options[$option_name] = $option_value;
                    }
                }
            }
            return $update_options;
        }

        private function getFieldTypeValue($value, $option_value)
        {
            // Format value
            switch (sanitize_title($value['type'])) {
                case 'checkbox':
                    if (is_null($option_value)) {
                        $option_value = 'no';
                    } else {
                        $option_value = 'yes';
                    }
                    break;
                case 'textarea':
                    $option_value = wp_kses_post(trim($option_value));
                    break;
                case 'text':
                case 'email':
                case 'url':
                case 'number':
                case 'select':
                case 'color':
                case 'password':
                case 'single_select_page':
                case 'radio':
                    $option_value = wpClean($option_value);
                    break;
                case 'multiselect':
                    $option_value = array_filter(array_map('wpClean', (array) $option_value));
                    break;
                default:
                    do_action('eb_update_option_' . sanitize_title($value['type']), $value);
                    break;
            }
            return $option_value;
        }
    }
}
