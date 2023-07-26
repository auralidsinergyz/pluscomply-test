<?php
namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     WisdmLabs, India <support@wisdmlabs.com>
 */
class SsoInit
{

    protected $plugin_name;
    protected $version;

    public function __construct($pluginName, $version)
    {
        $this->plugin_name = $pluginName;
        $this->version     = $version;
    }

    public function run()
    {
        $this->loadCommonDependancy();
        if (is_admin()) {
            $this->loadAdminDependancy();
        } else {
            $this->loadPublicDependancy();
        }
    }

    private function loadCommonDependancy()
    {
        include_once EBSSO_DIR_PATH . '/includes/ebsso-functions.php';
        include_once EBSSO_DIR_PATH . '/includes/class-sso-manage-moodle-login.php';
        include_once EBSSO_DIR_PATH . '/includes/class-sso-social-login-user-manager.php';
        include_once EBSSO_DIR_PATH . '/includes/social-login/facebook/class-sso-facebook-init.php';
        include_once EBSSO_DIR_PATH . '/includes/social-login/google-plus/class-sso-google-plus-init.php';
        include_once EBSSO_DIR_PATH . '/public/shortcodes/class-sso-social-login.php';

        add_action('eb_after_shortcode_doc', array($this, 'addShortcodeDesc'));
        add_action('plugins_loaded', array($this, 'loadTxtDomain'));
        add_action('init', array($this, 'startSession'));
        add_action('init', array($this, 'loadSocialLoginDependancy'));
        add_action('clear_auth_cookie', array($this, 'clearAuthCookie'));
        add_action('admin_init', array($this, 'migratePreviousVersionData'));
        $this->addPluginShortcodes();
    }


    public function migratePreviousVersionData()
    {
        $ssoSettings = get_option("eb_sso_settings_general");
        if (isset($ssoSettings['eb_sso_fb_enable']) && $ssoSettings['eb_sso_fb_enable'] == "yes") {
            $ssoSettings['eb_sso_fb_enable'] = "both";
        }
        if (isset($ssoSettings['eb_sso_gp_enable']) && $ssoSettings['eb_sso_gp_enable'] == "yes") {
            $ssoSettings['eb_sso_gp_enable'] = "both";
        }
        update_option("eb_sso_settings_general", $ssoSettings);
    }



    /**
     * this is no more needed
     * @since 1.3.1
     */
    public function loadSocialLoginDependancy()
    {
        $ssoSettings = get_option("eb_sso_settings_general");
        if (isset($ssoSettings['eb_sso_fb_enable']) && $ssoSettings['eb_sso_fb_enable'] != "no") {
            $fbSDK = new SsoGooglePlusInit($this->plugin_name, $this->version);
            $fbSDK->loadDepend();
        }
        if (isset($ssoSettings['eb_sso_gp_enable']) && $ssoSettings['eb_sso_gp_enable'] != "no") {
            $gpSDK = new SsoFacebookInit($this->plugin_name, $this->version);
            $gpSDK->loadDepend();
        }
    }

    private function loadPublicDependancy()
    {
        include_once EBSSO_DIR_PATH . '/public/class-sso-public.php';
        $publicSide = new SsoPublic($this->plugin_name, $this->version);
        $publicSide->initPublic();
    }

    private function loadAdminDependancy()
    {
        include_once EBSSO_DIR_PATH . '/admin/class-sso-admin.php';
        $adminSide = new SSOAdmin($this->plugin_name, $this->version);
        $adminSide->adminInit();
    }

    private function addPluginShortcodes()
    {
        $socialLogin = new SsoSocoalLogin($this->plugin_name, $this->version);
        /**
         * Create shortcode to display social login widget.
         */
        add_shortcode("eb_sso_social_login", array($socialLogin, "output"));
    }

    /**
     * Load plugin's textdomain.
     *
     * @since 1.2
     */
    public function loadTxtDomain()
    {
        load_plugin_textdomain("single_sign_on_text_domain", false, EBSSO_DIR_NAME . '/languages');
    }

    /**
     * Register session.
     *
     * @since 1.2
     */
    public function startSession()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public function clearAuthCookie()
    {
        $userinfo                  = wp_get_current_user();
        $user_id                   = $userinfo->ID;
        $_SESSION['eb_wp_user_id'] = $user_id;
    }



    public function addShortcodeDesc()
    {
        $html = "<div class='eb-shortcode-doc-wpra'>
                    <h3>Single Sign On Shortcode Options </h3>
                    <div class='eb-shortcode-doc'>
                        <h4>[eb_sso_social_login]</h4>
                        <div class='eb-shortcode-doc-desc'>
                            <p>
                                This shortcode shows Facebook and Google plus icons for login.
                            </p>
                        </div>
                    </div>
                    <div class='eb-shortcode-doc'>
                        <h4>[wdm_generate_link]</h4>
                        <div class='eb-shortcode-doc-desc'>
                            <p>
                            This shortcode redirects user to the Moodle site
                            </p>
                        </div>
                    </div>
                </div>";
        echo $html;
    }
}
