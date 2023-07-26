<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SingleSignOn
{

    private $version;
    private $plugin_name;

    public function __construct($plugin_name, $version)
    {
        $this->version     = $version;
        $this->plugin_name = $plugin_name;
        define('ED_MOODLE_PLUGIN_URL', '/auth/wdmwpmoodle/login.php?wdm_data=');
        define('ED_MOODLE_LOGOUT_URL', '/auth/wdmwpmoodle/login.php?wdm_logout=');
        add_action('admin_init', array($this, 'checkDependency'));
        add_action('init', array($this, 'runPluginUpdateHook'));
        $this->init();
    }

    /**
     * This function will run on plugin update and activation only
     * on first installation of the version
     */
    public function runPluginUpdateHook()
    {
        /*
         * Get old plugin version from the database.
         * This will return old version number if the old version is installed on site
         * otherwise it will return false.
         */
        $oldVersion = get_option('ebsso_version');
        /*
         * Check is old versoin of the plugin is installed on site.
         */
        if ($oldVersion == false || (version_compare($this->version, '1.3.2') == -1 && version_compare($oldVersion, '1.3.0') == -1 )) {
            /*
             * Below part of the code will only require if the plugin is updated from 1.2 to 1.2.1
             */

            /*
             * Start version update 1.3.0
             */
            $oldSsoSettings = $this->getOldSettings();

            /*
             * Check is old settings are avaialable for the plugin.
             */
            if ($oldSsoSettings == false) {
                return;
            }
            $rediSettings   = array();
            $genralSettings = array();
            /*
             * This will check is the login redirect url is set if yes then add into the array
             */
            if (isset($oldSsoSettings['ebsso_login_redirect_url'])) {
                $rediSettings['ebsso_login_redirect_url'] = $oldSsoSettings['ebsso_login_redirect_url'];
                $rediSettings['ebsso_role_base_redirect'] = "no";
            }
            /*
             * This will check is the secreate token is set is set if yes then add into the array
             */
            if (isset($oldSsoSettings['eb_sso_secret_key'])) {
                $genralSettings['eb_sso_secret_key'] = $oldSsoSettings['eb_sso_secret_key'];
            }
            /*
             * Save the previous versoin settings data into the new settings section
             *
             */

            update_option('eb_sso_settings_general', $genralSettings);
            update_option('eb_sso_settings_redirection', $rediSettings);
            /*
             * End version update 1.3.0
             */

            /*
             * Save the new plugin versoin into the database.
             */
            update_option('ebsso_version', $this->version);
        }
    }

    /**
     * Provides the functionality to get the plugins old functionality.
     * @since 1.3.0
     *
     * @return array returns the array of the plugins old settings
     */
    private function getOldSettings()
    {
        $settings = get_option('eb_sso_settings');
        update_option("eb_sso_settings_old", $settings);
        delete_option("eb_sso_settings", $settings);
        if ($settings == false) {
            $settings = get_option('eb_general');
        }
        return $settings;
    }

    public function init()
    {
        include_once EBSSO_DIR_PATH . '/includes/class-sso-init.php';
        /**
         * Load Initial settings.
         */
        
        include_once EBSSO_DIR_PATH . '/includes/class-wdm-wusp-get-data.php';
        include_once EBSSO_DIR_PATH . '/includes/class-sso-redirection.php';
        global $ssoPluginData;
        $getDataFromDb = \ebsso\SsoGetData::getDataFromDb($ssoPluginData);
        if ($getDataFromDb !== 'available') {
            return false;
        }
        $init=new SsoInit($this->plugin_name, $this->version);
        $init->run();

        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('wp_ajax_ebsso_verify_key', array($this, 'verifyKey'));
        add_shortcode('wdm_generate_link', array($this, 'generateLinkMessage'));
        add_action('template_redirect', array($this, 'generateLink'));
        add_filter('eb_course_access_button', array($this, 'courseAccessButton'), 10, 2);
        add_action('wp_logout', array($this, 'loggedOut'), 11);
        add_action('wp_login', array($this, 'loggedIn'), 10, 2);
        add_action('template_redirect', array($this, 'mdlTriggeredAction'));
        $this->checkMoodleToken();
    }

    /**
     * Verifys that the plugin contains the moodle sso version 1.2.1 or higher
     */
    private function checkMoodleToken()
    {
        if (!is_admin() || get_option("wdm_moodle_version_notice")) {
            return;
        }

        $connOptions = get_option('eb_connection');
        $mdlUrl      = $this->getMdlConnectionUrl($connOptions);
        $mdlToken    = $this->getMdlAccessToken($connOptions);
        $response    = $this->prepareTokenVerifyRequest($mdlUrl, $mdlToken, "wdm_sso_verify_token", "");
        if (is_wp_error($response)) {
            add_action('admin_notices', array($this, 'moodlePluginInCompWarning'));
        } elseif (wp_remote_retrieve_response_code($response) == 200) {
            $body = json_decode(wp_remote_retrieve_body($response));
            /**
             * Check moodle plugin installed and webservice function is added into the external services.
             */
            if (isset($body->exception) && $body->exception == "webservice_access_exception") {
                add_action('admin_notices', array($this, 'moodlePluginInCompWarning'));
            } else {
                update_option("wdm_moodle_version_notice", true);
            }
        }
    }

    private function getMdlConnectionUrl($connOptions)
    {
        $mdlUrl = false;
        if (isset($connOptions['eb_url'])) {
            $mdlUrl = $connOptions['eb_url'];
        }
        return $mdlUrl;
    }

    private function getMdlAccessToken($connOptions)
    {
        $mdlToken = false;
        if (isset($connOptions['eb_access_token'])) {
            $mdlToken = $connOptions['eb_access_token'];
        }
        return $mdlToken;
    }

    private function prepareTokenVerifyRequest($mdlUrl, $mdlToken, $webFunction, $token)
    {
        $reqUrl       = $mdlUrl . '/webservice/rest/server.php?wstoken=';
        $reqUrl       .= $mdlToken . '&wsfunction=' . $webFunction . '&moodlewsrestformat=json';
        $request_args = array(
            "body" => array('token' => $token),
            'timeout' => 500,
        );
        return wp_remote_post($reqUrl, $request_args);
    }

    public function moodlePluginInCompWarning()
    {
        $isDismissed = get_option("wdm_moodle_version_notice");
        if (isset($_GET['wdm_mdl_v_check'])) {
            update_option("wdm_moodle_version_notice", true);
            $isDismissed = true;
        }

        if (!$isDismissed) {
            $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false ? 'http' : 'https';
            $host     = $_SERVER['HTTP_HOST'];
            $script   = $_SERVER['SCRIPT_NAME'];
            $params   = $_SERVER['QUERY_STRING'];
            $url      = $protocol . '://' . $host . $script . '?' . $params;
            ?>
            <div class="notice notice-warning">
                <p>
                    <?php _e("Moodle ", "single_sign_on_text_domain"); ?>
                    <a href="<?php echo EBSSO_MOODLE_PLUGIN_LINK; ?>" target="_blank">Single Sign On</a>
                    <?php _e(" plugin is not compatible, please update your moodle plugin to version 1.2.1 or higher ", "single_sign_on_text_domain"); ?>
                    <a href="<?php echo add_query_arg(array("wdm_mdl_v_check" => true), $url); ?>"><?php _e("Dismiss this notice", "single_sign_on_text_domain") ?></a>
                </p>
            </div>
            <?php
        }
    }

    /**
     * Check dependent plugins.
     *
     * @since 1.0.0
     */
    public function checkDependency()
    {
        $extensions = array(
            'edwiser_bridge' => array('edwiser-bridge/edwiser-bridge.php', '1.1'),
        );

        $edwiser_old = true;

        /* deactive legacy extensions */
        foreach ($extensions as $extension) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $extension[0]);
            if (isset($plugin_data['Version'])) {
                if (version_compare($plugin_data['Version'], $extension[1]) >= 0) {
                    $edwiser_old = false;
                }
            }
        }



        /**
         *
         * @since 1.3.2
         */
        // $mcrypt = function_exists('mcrypt_encrypt') ? true : false;
        $curlV=function_exists('curl_version') ? true : false;

        /* If EB is not active or old version is installed */
        if (!is_plugin_active('edwiser-bridge/edwiser-bridge.php') || $edwiser_old || !$curlV) {
            deactivate_plugins(EBSSO_DIR_NAME . '/sso.php');
            unset($_GET['activate']);
            add_action('admin_notices', array($this, 'activationError'));
        }
    }

    /**
     * Show activation errors - admin notices.
     *
     * @since 1.0.0
     */
    public function activationError()
    {
        $class = 'notice notice-error is-dismissible';

        $eb_lnk = '<a href="' . esc_url('https://wordpress.org/plugins/edwiser-bridge/') . '" target="_blank">' . __('Edwiser Bridge', "single_sign_on_text_domain") . '</a>';
        $mc_lnk = '<a href="' . esc_url('http://php.net/manual/en/book.mcrypt.php') . '" target="_blank">' . __('Mcrypt', "single_sign_on_text_domain") . '</a>';
        $curl_lnk = '<a href="' . esc_url('http://php.net/manual/en/curl.installation.php') . '" target="_blank">' . __('cURL', "single_sign_on_text_domain") . '</a>';

        $eb_data = get_plugin_data(WP_PLUGIN_DIR . '/edwiser-bridge/edwiser-bridge.php');

        $eb_err = false;
        if (!is_plugin_active('edwiser-bridge/edwiser-bridge.php') || (isset($eb_data['Version']) && version_compare($eb_data['Version'], '1.1') < 0 )) {
            $eb_err = true;
        }

        $msg = __('Following things are required to activate Single Single Sign On plugin:', "single_sign_on_text_domain");

        $msg .= '<ol>';
        if ($eb_err) {
            $msg .= '<li>' . sprintf(__('Plugin %s version 1.1 or higher', "single_sign_on_text_domain"), $eb_lnk) . '</li>';
        }

        /*if (!function_exists('mcrypt_encrypt')) {
            $msg .= '<li>' . sprintf(__('%s PHP Extension', "single_sign_on_text_domain"), $mc_lnk) . '</li>';
        }*/

        /**
         * Using new encryption method openssl.
         * @since 1.3.2
         */
        if (!function_exists('openssl_encrypt')) {
            $msg .= '<li>' . sprintf(__('%s PHP Extension', "single_sign_on_text_domain"), $mc_lnk) . '</li>';
        }


        if (!function_exists('curl_version')) {
            $msg .= '<li>' . sprintf(__('%s PHP Extension', "single_sign_on_text_domain"), $curl_lnk) . '</li>';
        }
        $msg .= '</ol>';

        printf('<div class="%1$s"><p>%2$s</p></div>', $class, $msg);
    }

    /**
     * Alert script
     *
     * @since 1.0.0
     */
    public function enqueueScripts()
    {
        // Showing alert message to only admin if secret key is not matched.
        if (is_user_logged_in() && current_user_can('manage_options')) {
            if (isset($_GET['wdm_moodle_error']) && 'wdm_moodle_error' === $_GET['wdm_moodle_error']) {
                wp_enqueue_script(
                    'eb_sso_blockUI_js',
                    EBSSO_URL . '/assets/jquery.blockUI.js',
                    array('jquery')
                );
                wp_enqueue_script(
                    'eb_sso_moodle_js',
                    EBSSO_URL . '/assets/sso.js',
                    array('jquery', 'eb_sso_blockUI_js')
                );
                $data = array(
                    'error_message' => __('Please set the same secret key on WordPress as well as on Moodle', "single_sign_on_text_domain")
                );
                wp_localize_script('eb_sso_moodle_js', 'eb_sso_data', $data);
            }
        }
    }

    /**
     * Setting scripts
     *
     * @since 1.2
     */
    public function adminScripts()
    {
        wp_enqueue_style('ebsso_admin_setings_css', EBSSO_URL . '/assets/admin-settings.css');

        wp_enqueue_script(
            'ebsso_admin_setings_js',
            EBSSO_URL . '/assets/admin-settings.js',
            array('jquery')
        );

        $nonce = wp_create_nonce('ebsso-verify-key');

        $data = array(
            'ajaxurl'    => admin_url('admin-ajax.php'),
            'nonce'      => $nonce,
            'plugin_url' => EB_PLUGIN_URL
        );
        wp_localize_script('ebsso_admin_setings_js', 'ebssoAdSet', $data);
    }

    /**
     * This will provide the functionality to validate the secreat key token vith moodle.
     */
    public function verifyKey()
    {
        /**
         * Get Moodle Connection options
         */
        $connOptions = get_option('eb_connection');
        $mdlUrl      = $this->getMdlConnectionUrl($connOptions);
        $mdlToken    = $this->getMdlAccessToken($connOptions);
        if (!$mdlUrl) {
            wp_send_json_error(__('Please check your Edwiser Bridge Connection Settings first!', "single_sign_on_text_domain"));
        }
        if (!$mdlToken) {
            wp_send_json_error(__('Please check your Edwiser Bridge Connection Settings first!', "single_sign_on_text_domain"));
        }
        $response = $this->prepareTokenVerifyRequest($mdlUrl, $mdlToken, "wdm_sso_verify_token", $_POST['wp_key']);
        $msg      = "";
        if (is_wp_error($response)) {
            $msg = $response->get_error_message();
        } elseif (wp_remote_retrieve_response_code($response) == 200) {
            $body = json_decode(wp_remote_retrieve_body($response));
            /**
             * Check moodle plugin installed and webservice function is added into the external services.
             */
            if (isset($body->exception) && $body->exception == "webservice_access_exception") {
                $mdlPluginDownLink = "<a href='" . EBSSO_MOODLE_PLUGIN_LINK . "'>Edwiser Bridge SSO</a>";
                wp_send_json_error(__(sprintf("Please check you have %s plugin version 1.2.1 or greater install on moodle and %s web service added in external services", $mdlPluginDownLink, 'wdm_sso_verify_token'), "single_sign_on_text_domain"));
            }
            if ($body->success == true) {
                wp_send_json_success($body->msg);
            } else {
                $msg = $body->msg;
            }
        } else {
            $msg = __('Please check Moodle URL !', "single_sign_on_text_domain");
        }
        wp_send_json_error($msg);
    }

    /**
     * Used for generating moodle url.
     *
     * @since    1.0
     */
    public function generateLinkMessage()
    {
        ob_start();
        if (is_user_logged_in()) {
            _e('You don\'t have moodle account.', "single_sign_on_text_domain");
        } else {
            _e('Please login to view this page.', "single_sign_on_text_domain");
        }
        return ob_get_clean();
    }

    public function generateLink()
    {
        global $post;

        if (isset($post) && has_shortcode($post->post_content, 'wdm_generate_link')) {
            if (is_user_logged_in()) {
                $moodle_user_id = get_user_meta(get_current_user_id(), 'moodle_user_id', true);

                if ($moodle_user_id) {
                    $query = array(
                        'moodle_user_id' => $moodle_user_id
                    );

                    $final_url = generateMoodleUrl($query);
                    if (filter_var($final_url, FILTER_VALIDATE_URL)) {
                        wp_redirect($final_url);
                        exit;
                    }
                }
            }
        }
    }

    /**
     * changed the url of course access button.
     *
     * @since 1.0.0
     */
    public function courseAccessButton($access_button, $access_params)
    {
        if (!is_user_logged_in()) {
            return $access_button;
        }
        $post_id          = $access_params['post']->ID;
        $moodle_course_id = get_post_meta($post_id, 'moodle_course_id', true);
        if ('' == $moodle_course_id) {
            return $access_button;
        }
        $moodle_user_id = get_user_meta(get_current_user_id(), 'moodle_user_id', true);
        if ('' == $moodle_user_id) {
            return $access_button;
        }

        $query = array(
            'moodle_user_id'   => $moodle_user_id, //moodle user id
            'moodle_course_id' => $moodle_course_id,
        );

        // encode array as querystring
        $final_url = generateMoodleUrl($query);
        if (filter_var($final_url, FILTER_VALIDATE_URL)) {
            $access_params['access_course_url'] = $final_url;
            $html                               = '<div class="eb_join_button"><a class="wdm-btn" href="'
                    . $final_url . '" id="wdm-btn">' . __('Access Course', "single_sign_on_text_domain") . '</a></div>';

            return $html;
        } else {
            return $access_button;
        }

        return $access_button;
    }

    /**
     * Logging out user from moodle site.
     *
     * @since 1.0.0
     */
    public function loggedOut()
    {
        $mdlLogout = new SsoManageMooLogin($this->plugin_name, $this->version);
        $mdlLogout->mdlLoggedOut();
    }

    /**
     * Logged in user on moodle site.
     *
     * @since 1.0.0
     */
    public function loggedIn($user_login, $user)
    {
        $mdlLogout = new SsoManageMooLogin($this->plugin_name, $this->version);
        $mdlLogout->mdlLoggedIn($user_login, $user);
    }

    /**
     * Triggers actions - login/logout from moodle site.
     *
     * @return $redirect_url
     * @since 1.2
     */
    public function mdlTriggeredAction()
    {
        if (isset($_GET['wdmaction']) && isset($_GET['wdmargs'])) {
            $setting = get_option('eb_sso_settings_general');
            $wp_key  = isset($setting['eb_sso_secret_key']) ? (string) $setting['eb_sso_secret_key'] : '';

            $decrypted_args = getDecryptedQueryArgs($_GET['wdmargs'], $wp_key);
            $mdl_key        = (string) getKeyValue($decrypted_args, 'mdl_key');
            $mdl_uid        = getKeyValue($decrypted_args, 'mdl_uid');
            $mdl_email      = getKeyValue($decrypted_args, 'mdl_email');

            $redirect_to = getKeyValue($decrypted_args, 'redirect_to');
            if (!filter_var($redirect_to, FILTER_VALIDATE_URL)) {
                if (filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL)) {
                    $redirect_to = $_SERVER['HTTP_REFERER'];
                } else {
                    $redirect_to = site_url();
                }
            }

            if (!empty($wp_key) && $mdl_key === $wp_key) {
                if ($_GET['wdmaction'] === 'logout') {
                    add_action(
                        'wp_logout',
                        create_function(
                            '',
                            'wp_redirect("' . $redirect_to . '");exit();'
                        )
                    );
                    triggerLogout($mdl_uid);
                } elseif ($_GET['wdmaction'] === 'login') {
                    triggerLogin($mdl_uid, $mdl_email);
                }
            }
            wp_redirect($redirect_to);
            exit();
        }
    }
}
