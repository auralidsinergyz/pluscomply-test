<?php

namespace ebsso;

if (!class_exists('SsoAddDataInDB')) {

    class SsoAddDataInDB
    {
        /*
         *
         * @var string Short Name for plugin.
         */

        private $pluginShortName = '';

        /**
         *
         * @var string Slug to be used in url and functions name
         */
        private $pluginSlug = '';

        /**
         *
         * @var string stores the current plugin version
         */
        private $pluginVersion = '';

        /**
         *
         * @var string Handles the plugin name
         */
        private $pluginName = '';

        /**
         *
         * @var string  Stores the URL of store. Retrieves updates from
         *              this store
         */
        private $storeUrl = '';

        /**
         *
         * @var string  Name of the Author
         */
        private $authorName = '';
        private $pluginTextDomain = '';

        public function __construct($plugin_data)
        {
            $this->authorName = $plugin_data['author_name'];
            $this->pluginName = $plugin_data['plugin_name'];
            $this->pluginShortName = $plugin_data['plugin_short_name'];
            $this->pluginSlug = $plugin_data['plugin_slug'];
            $this->pluginVersion = $plugin_data['plugin_version'];
            $this->storeUrl = $plugin_data['store_url'];
            $this->pluginTextDomain = $plugin_data['pluginTextDomain'];
        }

        public function initLicens()
        {
            add_filter('eb_setting_messages', array($this, 'licenseMessages'), 15, 1);
            add_filter('eb_licensing_information', array($this, 'licenseInformation'), 15, 1);
            add_action('init', array($this, 'addData'), 5);
        }

        public function licenseInformation($licensing_info)
        {
            $renew_link = get_option('wdm_' . $this->pluginSlug . '_product_site');

            //Get License Status
            $status = get_option('edd_' . $this->pluginSlug . '_license_status');
            include_once plugin_dir_path(__FILE__) . 'class-wdm-wusp-get-data.php';

            $active_site = SsoGetData::getSiteList($this->pluginSlug);

            $display = '';
            if (!empty($active_site) || '' != $active_site) {
                $display = '<ul>' . $active_site . '</ul>';
            }

            $license_key = trim(get_option('edd_' . $this->pluginSlug . '_license_key'));

            // LICENSE KEY
            if (('valid' == $status || 'expired' == $status) && (empty($display) || '' == $display)) {
                $license_key_html = '<input id="edd_' . $this->pluginSlug . '_license_key" name="edd_' . $this->pluginSlug . '_license_key" type="text" class="regular-text" value="' . esc_attr($license_key) . '" readonly/>';
            } else {
                $license_key_html = '<input id="edd_' . $this->pluginSlug . '_license_key" name="edd_' . $this->pluginSlug . '_license_key" type="text" class="regular-text" value="' . esc_attr($license_key) . '" />';
            }

            //LICENSE STATUS
            $license_status = $this->displayLicenseStatus($status, $display);

            //Activate License Action Buttons
            ob_start();
            wp_nonce_field('edd_' . $this->pluginSlug . '_nonce', 'edd_' . $this->pluginSlug . '_nonce');
            $nonce = ob_get_contents();
            ob_end_clean();
            if (false !== $status && 'valid' == $status) {
                $buttons = '<input type="submit" class="button-primary" name="edd_' . $this->pluginSlug . '_license_deactivate" value="' . __('Deactivate License', 'single_sign_on_text_domain') . '" />';
            } elseif ('expired' == $status && (!empty($display) || '' != $display)) {
                $buttons = '<input type = "submit" class = "button-primary" name = "edd_' . $this->pluginSlug . '_license_activate" value = "' . __('Activate License', 'single_sign_on_text_domain') . '"/>';
                $buttons .= ' <input type = "button" class = "button-primary" name = "edd_' . $this->pluginSlug . '_license_renew" value = "' . __('Renew License', 'single_sign_on_text_domain') . '" onclick = "window.open( \'' . $renew_link . '\')"/>';
            } elseif ('expired' == $status) {
                $buttons = '<input type="submit" class="button-primary" name="edd_' . $this->pluginSlug . '_license_deactivate" value="' . __('Deactivate License', 'single_sign_on_text_domain') . '" />';
                $buttons .= ' <input type="button" class="button-primary" name="edd_' . $this->pluginSlug . '_license_renew" value="' . __('Renew License', 'single_sign_on_text_domain') . '" onclick="window.open( \'' . $renew_link . '\' )"/>';
            } else {
                $buttons = '<input type="submit" class="button-primary" name="edd_' . $this->pluginSlug . '_license_activate" value="' . __('Activate License', 'single_sign_on_text_domain') . '"/>';
            }

            $info = array(
                'plugin_name' => $this->pluginName,
                'plugin_slug' => $this->pluginSlug,
                'license_key' => $license_key_html,
                'license_status' => $license_status,
                'activate_license' => $nonce . $buttons,
            );

            $licensing_info[] = $info;

            return $licensing_info;
        }

        private function getLicensesGlobalStatus($status)
        {
            if (isset($GLOBALS['wdm_server_null_response']) && $GLOBALS['wdm_server_null_response'] == true) {
                $status = 'server_did_not_respond';
            } elseif (isset($GLOBALS['wdm_license_activation_failed']) && $GLOBALS['wdm_license_activation_failed'] == true) {
                $status = 'license_activation_failed';
            } elseif (isset($_POST['edd_' . $this->pluginSlug . '_license_key']) && empty($_POST['edd_' . $this->pluginSlug . '_license_key'])) {
                $status = 'no_license_key_entered';
            }
            return $status;
        }

        public function licenseMessages($eb_lice_messages)
        {

            //Get License Status
            $status = get_option('edd_' . $this->pluginSlug . '_license_status');

            include_once plugin_dir_path(__FILE__) . 'class-wdm-wusp-get-data.php';
            $status=  $this->getLicensesGlobalStatus($status);

            $active_site = SsoGetData::getSiteList($this->pluginSlug);

            $display = '';
            $display = $this->checkIfSiteActive($active_site);
            if (isset($_POST['edd_' . $this->pluginSlug . '_license_key']) && !isset($_POST['eb_server_nr'])) {
                //Handle Submission of inputs on license page
                if (isset($_POST['edd_' . $this->pluginSlug . '_license_key']) && empty($_POST['edd_' . $this->pluginSlug . '_license_key'])) {
                    //If empty, show error message
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('Please enter license key for %s.', "single_sign_on_text_domain"), $this->pluginName),
                        'error'
                    );
                } elseif ($status == 'server_did_not_respond') {
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('No response from server. Please try again later.', "single_sign_on_text_domain"), $this->pluginName),
                        'error'
                    );
                } elseif ($status == 'item_name_mismatch') {
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('License key is not valid. Please check your license key and try again', "single_sign_on_text_domain"), $this->pluginName),
                        'error'
                    );
                } elseif (false !== $status && 'valid' == $status) { //Valid license key
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('License key for %s is activated.', 'ebbp-textdomain'), $this->pluginName),
                        'updated'
                    );
                } elseif (false !== $status && 'expired' == $status &&
                        (!empty($display) || '' != $display)) { //Expired license key
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('License key for %s have been Expired. Please, Renew it. <br/>Your License Key is already activated at : ' . $display, 'ebbp-textdomain'), $this->pluginName),
                        'error'
                    );
                } elseif (false !== $status && 'expired' == $status) { //Expired license key
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('License key for %s have been Expired. Please, Renew it.', 'ebbp-textdomain'), $this->pluginName),
                        'error'
                    );
                } elseif (false !== $status &&
                        'disabled' == $status) { //Disabled license key
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('License key for %s is Disabled.', 'ebbp-textdomain'), $this->pluginName),
                        'error'
                    );
                } elseif ($status == 'no_activations_left') { //Invalid license key   and site
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('License Key for %s is already activated at : %s', 'ebbp-textdomain'), $this->pluginName, $display),
                        'error'
                    );
                } else {
                    $this->invalidStatusMessages($status, $display);
                }
            }
            ob_start();
            settings_errors('eb_' . $this->pluginSlug . '_errors');
            $ss_setting_messages = ob_get_contents();
            ob_end_clean();

            return $eb_lice_messages . $ss_setting_messages;
        }

        public function addData()
        {
            if (isset($_POST['edd_' . $this->pluginSlug . '_license_activate'])) {
                if (!check_admin_referer('edd_' . $this->pluginSlug . '_nonce', 'edd_' . $this->pluginSlug . '_nonce')) {
                    return;
                }
                $this->activateLicense();
            } elseif (isset($_POST['edd_' . $this->pluginSlug . '_license_deactivate'])) {
                if (!check_admin_referer('edd_' . $this->pluginSlug . '_nonce', 'edd_' . $this->pluginSlug . '_nonce')) {
                    return;
                }
                $this->deactivateLicense();
            }
        }

        /**
         * Deactivates License.
         */
        public function deactivateLicense()
        {
            $licenseKey = trim(get_option('edd_' . $this->pluginSlug . '_license_key'));

            if ($licenseKey) {
                $apiParams = array(
                    'edd_action' => 'deactivate_license',
                    'license' => $licenseKey,
                    'item_name' => urlencode($this->pluginName),
                    'current_version' => $this->pluginVersion,
                );

                $response = wp_remote_get(add_query_arg($apiParams, $this->storeUrl), array(
                    'timeout' => 15, 'sslverify' => false, 'blocking' => true,));
                if (is_wp_error($response)) {
                    return false;
                }

                $licenseData = json_decode(wp_remote_retrieve_body($response));

                $validResponseCode = array('200', '301');

                $currentResponseCode = wp_remote_retrieve_response_code($response);

                $isDataAvailable = $this->checkIfNoData($licenseData, $currentResponseCode, $validResponseCode);

                if ($isDataAvailable == false) {
                    return;
                }

                if ($licenseData->license == 'deactivated' || $licenseData->license == 'failed') {
                    update_option('edd_' . $this->pluginSlug . '_license_status', 'deactivated');
                }
                delete_transient('wdm_' . $this->pluginSlug . '_license_trans');

                set_transient('wdm_' . $this->pluginSlug . '_license_trans', $licenseData->license, 0);
            }
        }

        /**
         * Updates license status in the database and returns status value.
         *
         * @param object $licenseData License data returned from server
         * @param  string $pluginSlug  Slug of the plugin. Format of the key in options table is 'edd_<$pluginSlug>_license_status'
         *
         * @return string              Returns status of the license
         */
        public static function updateStatus($licenseData, $pluginSlug)
        {
            $status = '';
            if (isset($licenseData->success)) {
                // Check if request was successful
                if ($licenseData->success === false) {
                    if (!isset($licenseData->error) || empty($licenseData->error)) {
                        $licenseData->error = 'invalid';
                    }
                }
                // Is there any licensing related error?
                $status = self::checkLicensingError($licenseData);

                if (!empty($status)) {
                    update_option('edd_' . $pluginSlug . '_license_status', $status);

                    return $status;
                }
                $status = 'invalid';
                //Check license status retrieved from EDD
                $status = self::checkLicenseStatus($licenseData, $pluginSlug);
            }

            $status = (empty($status)) ? 'invalid' : $status;
            update_option('edd_' . $pluginSlug . '_license_status', $status);
            return $status;
        }

        /**
         * Checks if there is any error in response.
         *
         * @param object $licenseData License Data obtained from server
         *
         * @return string empty if no error or else error
         */
        public static function checkLicensingError($licenseData)
        {
            $status = '';
            if (isset($licenseData->error) && !empty($licenseData->error)) {
                switch ($licenseData->error) {
                    case 'revoked':
                        $status = 'disabled';
                        break;
                    case 'expired':
                        $status = 'expired';
                        break;
                    case 'item_name_mismatch':
                        $status = 'item_name_mismatch';
                        break;
                    case 'no_activations_left':
                        $status = 'no_activations_left';
                        break;
                }
            }
            return $status;
        }

        public static function checkLicenseStatus($licenseData, $pluginSlug)
        {
            $status = 'invalid';
            if (isset($licenseData->license) && !empty($licenseData->license)) {
                switch ($licenseData->license) {
                    case 'invalid':
                        $status = 'invalid';
                        if (isset($licenseData->activations_left) && $licenseData->activations_left == '0') {
                            include_once plugin_dir_path(__FILE__) . 'class-wdm-wusp-get-data.php';
                            $activeSite = SsoGetData::getSiteList($pluginSlug);
                            if (!empty($activeSite) || $activeSite != '') {
                                $status = 'no_activations_left';
                            }
                            /**
                             * Removed the condition since it was activating the licens key for the other plugins valid licens key
                             * SInce EDD is returning invalid so it dosen't make any sance to activate the key here.
                             */
//                            else {
//                                $status = 'valid';
//                            }
                        }
                        break;
                    case 'failed':
                        $status = 'failed';
                        $GLOBALS['wdm_license_activation_failed'] = true;
                        break;
                    default:
                        $status = $licenseData->license;
                }
            }
            return $status;
        }

        /**
         * Checks if any response received from server or not after making an API call. If no response obtained, then sets next api request after 24 hours.
         *
         * @param object $licenseData         License Data obtained from server
         * @param  string   $currentResponseCode    Response code of the API request
         * @param  array    $validResponseCode      Array of acceptable response codes
         *
         * @return bool returns false if no data obtained. Else returns true.
         */
        public function checkIfNoData($licenseData, $currentResponseCode, $validResponseCode)
        {
            if ($licenseData == null || !in_array($currentResponseCode, $validResponseCode)) {
                $GLOBALS['wdm_server_null_response'] = true;
                set_transient('wdm_' . $this->pluginSlug . '_license_trans', 'server_did_not_respond', 60 * 60 * 24);

                return false;
            }

            return true;
        }

        /**
         * Activates License.
         */
        public function activateLicense()
        {
            $licenseKey = trim($_POST['edd_' . $this->pluginSlug . '_license_key']);
            if ($licenseKey) {
                update_option('edd_' . $this->pluginSlug . '_license_key', $licenseKey);
                $apiParams = array(
                    'edd_action' => 'activate_license',
                    'license' => $licenseKey,
                    'item_name' => urlencode($this->pluginName),
                    'current_version' => $this->pluginVersion,
                );

                $response = wp_remote_get(add_query_arg($apiParams, $this->storeUrl), array(
                    'timeout' => 15, 'sslverify' => false, 'blocking' => true,));
                if (is_wp_error($response)) {
                    return false;
                }

                $licenseData = json_decode(wp_remote_retrieve_body($response));

                $validResponseCode = array('200', '301');

                $currentResponseCode = wp_remote_retrieve_response_code($response);

                $isDataAvailable = $this->checkIfNoData($licenseData, $currentResponseCode, $validResponseCode);
                if ($isDataAvailable == false) {
                    return;
                }

                $expirationTime = $this->getExpirationTime($licenseData);
                $currentTime = time();

                if (isset($licenseData->expires) && ($licenseData->expires !== false) && ($licenseData->expires != 'lifetime') && $expirationTime <= $currentTime && $expirationTime != 0 && !isset($licenseData->error)) {
                    $licenseData->error = 'expired';
                }

                if (isset($licenseData->renew_link) && (!empty($licenseData->renew_link) || $licenseData->renew_link != '')) {
                    update_option('wdm_' . $this->pluginSlug . '_product_site', $licenseData->renew_link);
                }

                $this->updateNumberOfSitesUsingLicense($licenseData);

                $licenseStatus = self::updateStatus($licenseData, $this->pluginSlug);

                $this->setTransientOnActivation($licenseStatus);
            }
        }

        public function getExpirationTime($licenseData)
        {
            $expirationTime = 0;
            if (isset($licenseData->expires)) {
                $expirationTime = strtotime($licenseData->expires);
            }

            return $expirationTime;
        }

        public function updateNumberOfSitesUsingLicense($licenseData)
        {
            if (isset($licenseData->sites) && (!empty($licenseData->sites) || $licenseData->sites != '')) {
                update_option('wdm_' . $this->pluginSlug . '_license_key_sites', $licenseData->sites);
                update_option('wdm_' . $this->pluginSlug . '_license_max_site', $licenseData->license_limit);
            } else {
                update_option('wdm_' . $this->pluginSlug . '_license_key_sites', '');
                update_option('wdm_' . $this->pluginSlug . '_license_max_site', '');
            }
        }

        public function setTransientOnActivation($licenseStatus)
        {
            $transVar = get_transient('wdm_' . $this->pluginSlug . '_license_trans');
            if (isset($transVar)) {
                delete_transient('wdm_' . $this->pluginSlug . '_license_trans');
                if (!empty($licenseStatus)) {
                    if ($licenseStatus == 'valid') {
                        $time = 60 * 60 * 24 * 7;
                    } else {
                        $time = 60 * 60 * 24;
                    }
                    set_transient('wdm_' . $this->pluginSlug . '_license_trans', $licenseStatus, $time);
                }
            }
        }
        /*         * ****************************************************** */

        public function checkIfSiteActive($active_site)
        {
            if (!empty($active_site) || '' != $active_site) {
                $display = '<ul>' . $active_site . '</ul>';
            } else {
                $display = '';
            }
            return $display;
        }

        public function invalidStatusMessages($status, $display)
        {
            if ('invalid' == $status && (!empty($display) || '' != $display)) { //Invalid license key   and site
                add_settings_error(
                    'eb_' . $this->pluginSlug . '_errors',
                    esc_attr('settings_updated'),
                    sprintf(__('License Key for %s is already activated at : ' . $display, "single_sign_on_text_domain"), $this->pluginName),
                    'error'
                );
            } elseif ('invalid' == $status) { //Invalid license key
                add_settings_error(
                    'eb_' . $this->pluginSlug . '_errors',
                    esc_attr('settings_updated'),
                    sprintf(__('Please enter valid license key for %s.', "single_sign_on_text_domain"), $this->pluginName),
                    'error'
                );
            } elseif ('site_inactive' == $status) { //Invalid license key   and site inactive
                if ((!empty($display) || '' != $display)) {
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        sprintf(__('License Key for %s is already activated at : ' . $display, "single_sign_on_text_domain"), $this->pluginName),
                        'error'
                    );
                } else {
                    add_settings_error(
                        'eb_' . $this->pluginSlug . '_errors',
                        esc_attr('settings_updated'),
                        __('Site inactive(Press Activate license to activate plugin)', "single_sign_on_text_domain"),
                        'error'
                    );
                }
            } elseif ('deactivated' == $status) { //Site is inactive
                add_settings_error(
                    'eb_' . $this->pluginSlug . '_errors',
                    esc_attr('settings_updated'),
                    sprintf(__('License Key for %s is deactivated', "single_sign_on_text_domain"), $this->pluginName),
                    'updated'
                );
            }
        }

        public function displayLicenseStatus($status, $display)
        {
            if (false !== $status && 'valid' == $status) {
                $license_status = '<span style="color:green;">' . __('Active', "single_sign_on_text_domain") . '</span>';
            } elseif (get_option('edd_' . $this->pluginSlug . '_license_status') == 'site_inactive') {
                $license_status = '<span style="color:red;">' . __('Not Active', "single_sign_on_text_domain") . '</span>';
            } elseif (get_option('edd_' . $this->pluginSlug . '_license_status') == 'expired') {
                $license_status = '<span style="color:red;">' . __('Expired', "single_sign_on_text_domain") . '</span>';
            } elseif (get_option('edd_' . $this->pluginSlug . '_license_status') == 'invalid') {
                $license_status = '<span style="color:red;">' . __('Invalid Key', "single_sign_on_text_domain") . '</span>';
            } else {
                $license_status = '<span style="color:red;">' . __('Not Active ', "single_sign_on_text_domain") . '</span>';
            }
            unset($display);
            return $license_status;
        }
    }
}
