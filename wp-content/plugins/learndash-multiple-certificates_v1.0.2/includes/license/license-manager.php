<?php

namespace LDMC\License;

if ( ! class_exists( '\LDMC\License\LicenseManager' ) ) {

    class LicenseManager
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        /**
         * @var string API url.
         */
        const API_URL = 'https://wooninjas.com/';

        /**
         * @var int Request timeout.
         */
        const REQUEST_TIMEOUT = 30;

        /**
         * @var string API param for activation a license key.
         */
        const PARAM_LICENSE_ACTIVATE = 'activate_license';

        /**
         * @var string API param for deactivation a license key.
         */
        const PARAM_LICENSE_DEACTIVATE = 'deactivate_license';

        /**
         * @var string API param for checking a license key.
         */
        const PARAM_LICENSE_CHECK = 'check_license';

        /**
         * @var string Plugin name.
         */
        public $plugin_name = LD_MC_NAME;

        /**
         * @var string License key.
         */
        public $license_key = '';

        /**
         * @var string API response.
         */
        public $response;

        /**
         * Constructor.
         *
         * @return void
         */
        public function __construct()
        {
            $this->reset_response();
        }

        /**
         * Set plugin name.
         *
         * @param string $plugin_name Plugin name.
         *
         * @return object Instance of this class.
         */
        public function set_plugin_name($plugin_name)
        {
            $this->plugin_name = $plugin_name;

            return $this;
        }

        /**
         * Get plugin name.
         *
         * @return string Plugin name.
         */
        public function get_plugin_name()
        {
            return $this->plugin_name;
        }

        /**
         * Set license key.
         *
         * @param string $license_key License key.
         *
         * @return object Instance of this class.
         */
        public function set_license_key($license_key)
        {
            $this->license_key = trim($license_key);

            return $this;
        }

        /**
         * Get license key.
         *
         * @return string License key.
         */
        public function get_license_key()
        {
            return $this->license_key;
        }

        /**
         * Activate license key.
         *
         * @return boolean|WP_Error Result of activating.
         */
        public function activate()
        {
            $api_params = wp_parse_args($this->get_default_api_params(), array('edd_action' => self::PARAM_LICENSE_ACTIVATE));
            $license_response = $this->api_request($api_params, self::PARAM_LICENSE_ACTIVATE, true);

            if (!$license_response['success']) {
                return new \WP_Error($license_response['code'], $license_response['status']);
            }

            return true;
        }

        /**
         * Deactivate license key.
         *
         * @return boolean|WP_Error Result of deactivating.
         */
        public function deactivate()
        {
            $api_params = wp_parse_args($this->get_default_api_params(), array('edd_action' => self::PARAM_LICENSE_DEACTIVATE));
            $license_response = $this->api_request($api_params, self::PARAM_LICENSE_DEACTIVATE, true);

            if (!$license_response['success']) {
                return new \WP_Error($license_response['code'], $license_response['status']);
            }

            $transient_name = $this->get_transient_name();

            if ($transient_name) {
                delete_transient($transient_name);
            }

            return true;
        }

        /**
         * Check license key.
         *
         * @return boolean|WP_Error Result of checking.
         */
        public function check($force = false)
        {
            $api_params = wp_parse_args($this->get_default_api_params(), array('edd_action' => self::PARAM_LICENSE_CHECK));
            $license_response = $this->api_request($api_params, self::PARAM_LICENSE_CHECK, $force);

            if (!$license_response['success']) {
                return new \WP_Error($license_response['code'], $license_response['status']);
            }

            return true;
        }

        /**
         * Get default API params.
         *
         * @return array Default API params.
         */
        public function get_default_api_params()
        {
            return array(
                'license' => $this->get_license_key(),
                'item_name' => urlencode($this->get_plugin_name()),
                'url' => urlencode(home_url()),
                'time' => current_time('timestamp')
            );
        }

        /**
         * Execute an API request.
         *
         * @param array $api_params API params.
         *
         * @return array|WP_Error API response.
         */
        public function api_request($api_params, $action, $force = false)
        {
            $this->write_log('api_request');
            $this->reset_response();

            if (empty($api_params['license'])) {
                $this->set_response(false, 'missing', $this->set_status('missing'));

                return $this->get_response();
            } else {
                $transient_name = $this->get_transient_name();
                $transient = $transient_name ? get_transient($transient_name) : false;

                if (!$transient || $force) {
                    $response = wp_remote_post(self::API_URL, array(
                        'sslverify' => false,
                        'timeout' => self::REQUEST_TIMEOUT,
                        'body' => $api_params
                    ));

                    if (is_wp_error($response)) {
                        $this->set_response(false, $response->get_error_code(), $response->get_error_message());
                    } elseif (wp_remote_retrieve_response_code($response) !== 200) {
                        $this->set_response(false, 'unknown', $this->set_status('unknown'));
                    } else {
                        $license_data = json_decode(wp_remote_retrieve_body($response), true);
                        $this->write_log('$license_data');
                        $this->write_log($license_data);
                        $code = !empty($license_data['error']) ? $license_data['error'] : $license_data['license'];
                        $success = false;

                        switch ($action) {
                            case self::PARAM_LICENSE_ACTIVATE:
                            case self::PARAM_LICENSE_CHECK:
                                $success = $license_data['license'] == 'valid';
                                break;
                            case self::PARAM_LICENSE_DEACTIVATE:
                                $success = in_array($license_data['license'], array('deactivated', 'failed'));
                                break;
                        }

                        $this->set_response($success, $code, $this->set_status($code), $license_data);
                    }

                    if ($transient_name) {
                        $this->delete_plugins_updates();
                        set_transient($transient_name, $this->get_response(), 24 * HOUR_IN_SECONDS);
                    }

                    return $this->get_response();
                }

                $this->write_log('$transient');
                $this->write_log($transient);
                $this->set_response($transient['success'], $transient['code'], $transient['status'], $transient['data']);
                return $transient;
            }
        }

        public function delete_plugins_updates(){
            $this->write_log('delete_plugins_updates');

//            $meta_key = $this->get_plugin_updater()->cache_key;
//            $this->write_log('$meta_key');
//            $this->write_log($meta_key);
//
//            delete_option($meta_key);

            global $wpdb;
            $sql = "DELETE FROM ".$wpdb->prefix."options WHERE option_name LIKE '%update_plugins%'";
            $this->write_log('$sql');
            $this->write_log($sql);

            $result = $wpdb->query($sql);
            $this->write_log('$result');
            $this->write_log($result);

//            return $wpdb->delete(
//                $wpdb->prefix . 'options',      // table name with dynamic prefix
//                ['option_name' => $id],                       // which id need to delete
//                ['%d'],                             // make sure the id format
//            );
            return $result;
        }

        public function get_transient_name()
        {
            $plugin_name = $this->get_plugin_name();

            return $plugin_name ? 'wooninjas_license_' . sanitize_title($plugin_name) : '';
        }

        /**
         * Reset API response.
         *
         * @return void
         */
        public function reset_response()
        {
            $this->response = array(
                'success' => false,
                'status' => '',
                'code' => '',
                'data' => array()
            );
        }

        /**
         * Set API response.
         *
         * @param bool $success Success of a response.
         * @param string $code Code of a response.
         * @param string $status Status of a response.
         * @param array $data Data of a response.
         *
         * @return void
         */
        public function set_response($success, $code, $status, $data = array())
        {
            $this->response = array(
                'success' => $success,
                'code' => $code,
                'status' => $status,
                'data' => $data
            );
        }

        /**
         * Get API response.
         *
         * @return void
         */
        public function get_response()
        {
            return $this->response;
        }

        /**
         * Get status message.
         *
         * @param string $code Status code.
         *
         * @return string Status messsage.
         */
        public function set_status($code)
        {
            switch ($code) {
                case 'inactive':
                    $status = [
                        'type' => 'success',
                        'message' => sprintf(
                            esc_html_x('The %1s License key is inactive.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                            '<b>'.LD_MC_NAME.'</b>'
                        )
                    ];
                case 'deactivated':
                    $status = [
                        'type' => 'warning',
                        'message' => sprintf(
                            esc_html_x('The %1s License key has been deactivated.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>'
                        )
                    ];
                    break;
                case 'valid':
                    $status = [
                        'type' => 'success',
                        'message' => sprintf(
                            esc_html_x('The %1s License key has been activated.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>'
                        )
                    ];
                    break;
                case 'invalid':
                    $status = [
                        'type' => 'error',
                        'message' => sprintf(
                            esc_html_x('The %1s License key is invalid.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>'
                        )
                    ];
                    break;
                case 'expired':
                    $status = [
                        'type' => 'error',
                        'message' => sprintf(
                        esc_html_x('The %1s License key has expired.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>'
                        )
                    ];
                    break;
                case 'disabled':
                    $status = [
                        'type' => 'warning',
                        'message' => sprintf(
                            esc_html_x('The %1s License key has been disabled.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>'
                        )
                    ];
                    break;
                case 'revoked':
                    $status = [
                        'type' => 'warning',
                        'message' => sprintf(
                            esc_html_x('The %1s License key has been revoked.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>'
                        )
                    ];
                    break;
                case 'missing':
                    $status = [
                        'type' => 'warning',
                        'message' => sprintf(
                            esc_html_x('The %1s License key is missing.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>'
                        )
                    ];
                    break;
                case 'site_inactive':
                    $parse_url = parse_url(home_url());
                    $status = [
                        'type' => 'warning',
                        'message' => sprintf(
                            esc_html_x('The %1s License is not active for %2s', 'placeholder: add-on name, site URL', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>',
                            $parse_url['host']
                        )
                    ];
                    break;
                case 'item_name_mismatch':
                    $status = [
                        'type' => 'error',
                        'message' => sprintf(
                            esc_html_x('The %1s License key is not related to this product.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>',
                        )
                    ];
                    break;
                case 'no_activations_left':
                    $status = [
                        'type' => 'warning',
                        'message' => sprintf(
                            esc_html_x('The %1s License key has reached its activation limit.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>',
                        )
                    ];
                    break;
                case 'unknown':
                default:
                $status = [
                    'type' => 'error',
                    'message' => sprintf(
                        esc_html_x('An error occurred, please try again.', 'placeholder: add-on name', LD_MC_TEXT_DOMAIN),
                        '<b>'.$this->get_plugin_name().'</b>',
                    )
                ];
                    break;
            }

            return $status;
        }

        /**
         * Get success of a response.
         *
         * @return bool Success of a response.
         */
        public function get_success()
        {
            return $this->response['success'];
        }

        /**
         * Get code of a response.
         *
         * @return bool Code of a response.
         */
        public function get_code()
        {
            return $this->response['code'];
        }

        /**
         * Get status of a response.
         *
         * @return bool Status of a response.
         */
        public function get_status()
        {
            return $this->response['status'];
        }

        public function get_status_type(){
			if( isset($this->response['status']['type']) ){
				return $this->response['status']['type'];
			}
        }

        public function get_status_message(){
	        if( isset($this->response['status']['message']) ) {
		        return $this->response['status']['message'];
	        }
        }

        /**
         * Get data of a response.
         *
         * @return bool Data of a response.
         */
        public function get_data()
        {
            return $this->response['data'];
        }

    }
}