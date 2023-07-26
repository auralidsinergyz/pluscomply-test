<?php
namespace ebsso;

/*
 * generateMoodleUrl() used for generating moodle login url.
 *
 * @since 1.0.0
 */

if (!function_exists('\ebsso\generateMoodleUrl')) {

    function generateMoodleUrl($query = array())
    {
        // encode array as querystring
        $details            = http_build_query($query);
        //echo $details;exit;
        // encryption = 3des using shared_secret
        $connection_options = get_option('eb_connection');
        $eb_moodle_url      = isset($connection_options['eb_url']) ? $connection_options['eb_url'] : '';
        $sso_data           = get_option('eb_sso_settings_general');
        $sso_secret_key     = isset($sso_data['eb_sso_secret_key']) ? $sso_data['eb_sso_secret_key'] : '';
        if ('' == $eb_moodle_url && '' == $sso_secret_key) {
            return __('Something went wrong', 'single_sign_on_text_domain');
        }
        $final_url = $eb_moodle_url . ED_MOODLE_PLUGIN_URL . encryptString($details, $sso_secret_key);

        return $final_url;
    }
}

/*
 * Used for genrating moodle logout url.
 *
 * @since    1.0.0
 */
if (!function_exists('\ebsso\generateMoodleLogoutUrl')) {

    function generateMoodleLogoutUrl($query = array())
    {
        // encode array as querystring
        $details            = http_build_query($query);
        // encryption = 3des using shared_secret
        $connection_options = get_option('eb_connection');
        $eb_moodle_url      = isset($connection_options['eb_url']) ? $connection_options['eb_url'] : '';
        $sso_data           = get_option('eb_sso_settings_general');
        $sso_secret_key     = isset($sso_data['eb_sso_secret_key']) ? $sso_data['eb_sso_secret_key'] : '';
        if ('' == $eb_moodle_url && '' == $sso_secret_key) {
            return __('Something went wrong', 'single_sign_on_text_domain');
        }
        $final_url = $eb_moodle_url . ED_MOODLE_LOGOUT_URL . encryptString($details, $sso_secret_key);

        return $final_url;
    }
}

/*
 * encrypt moodle url with value as data and key as encyption key.
 *
 * @since 1.0.0
 */
if (!function_exists('\ebsso\encryptString')) {

    function encryptString($value, $key)
    {
        $key = $key;
        if (!$value) {
            return '';
        }

        $token = $value;
        $enc_method = 'AES-128-CTR';
        $enc_key = openssl_digest("edwiser-bridge", 'SHA256', true);
        $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($enc_method));
        $crypted_token = openssl_encrypt($token, $enc_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
        $newToken = $crypted_token;

        $newToken = $newToken;



/*        unset($token, $enc_method, $enc_key, $enc_iv);
        error_log("envrypted token :: ".print_r($crypted_token, 1));*/
/*
        if (preg_match("/^(.*)::(.*)$/", $newToken, $regs)) {
            list(, $crypted_token, $enc_iv) = $regs;
            $enc_method = 'AES-128-CTR';
            $enc_key = openssl_digest(gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR'], 'SHA256', true));
            $decrypted_token = openssl_decrypt($crypted_token, $enc_method, $enc_key, 0, hex2bin($enc_iv));


            error_log("decrypted_token :: ".print_r($decrypted_token, 1));
            // unset($crypted_token, $enc_method, $enc_key, $enc_iv, $regs);
        }*/




/*        $text      = $value;
        $iv_size   = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $i_v       = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key . $key), $text, MCRYPT_MODE_ECB, $i_v);

        // encode data so that $_GET won't urldecode it and mess up some characters
        $data = base64_encode($crypttext);

        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

        return trim($data);
*/



/*    $crypttext = base64_decode($data);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $init_vector = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key.$key), $crypttext, MCRYPT_MODE_ECB, $init_vector);*/


        $data = base64_encode($crypted_token);

        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

        return trim($data);
    }
}

/*
 * Decrypt query argument.
 *
 * @since 1.2
 */
if (!function_exists('\ebsso\getDecryptedQueryArgs')) {

    function getDecryptedQueryArgs($base64, $key)
    {
        $key = $key;
        $data = str_replace(array('-', '_'), array('+', '/'), $base64);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        $crypttext      = base64_decode($data);


        // $crypttext = $base64;


        if (preg_match("/^(.*)::(.*)$/", $crypttext, $regs)) {
            list(, $crypted_token, $enc_iv) = $regs;
            $enc_method = 'AES-128-CTR';
            $enc_key = openssl_digest("edwiser-bridge", 'SHA256', true);
            $decrypted_data = openssl_decrypt($crypted_token, $enc_method, $enc_key, 0, hex2bin($enc_iv));
        }


/*
        $iv_size        = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $init_vector    = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted_data = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key . $key), $crypttext, MCRYPT_MODE_ECB, $init_vector);
*/
        $decrypted_args = trim($decrypted_data);

        return $decrypted_args;
    }
}

/*
 * Function to return query argument from given string.
 *
 * @since 1.2
 */
if (!function_exists('\ebsso\getKeyValue')) {

    function getKeyValue($string, $key)
    {
        $key = $key;
        $list = explode('&', str_replace('&amp;', '&', $string));
        foreach ($list as $pair) {
            $item = explode('=', $pair);
            if (strtolower($key) == strtolower($item[0])) {
                return urldecode($item[1]);
            }
        }

        return;
    }
}

/*
 * Function to trigger logout.
 *
 * @since 1.2
 */
if (!function_exists('\ebsso\triggerLogout')) {

    function triggerLogout($mdl_uid)
    {
        if (is_user_logged_in()) {
            $wp_mdl_uid = get_user_meta(get_current_user_id(), 'moodle_user_id', true);
            if ($wp_mdl_uid && $wp_mdl_uid == $mdl_uid) {
                wp_logout();
            }
        }
    }
}

/*
 * Function to trigger login.
 *
 * @since 1.2
 */
if (!function_exists('\ebsso\triggerLogin')) {

    function triggerLogin($mdl_uid, $mdl_email)
    {
        if (is_user_logged_in()) {
            return;
            //wp_logout();
        }

        $user = get_user_by('email', $mdl_email);

        if (is_object($user)) {
            $wp_mdl_uid = get_user_meta($user->ID, 'moodle_user_id', true);
            if ($wp_mdl_uid && $wp_mdl_uid == $mdl_uid) {
                setLoginData($user);
            }
        }
    }
}
if (!function_exists("\ebsso\setLoginData")) {

    function setLoginData($user)
    {
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
    }
}

if (!function_exists('\ebsso\createEbUser')) {

    function createEbUser($userData)
    {
        $ebLoader   = \app\wisdmlabs\edwiserBridge\edwiserBridgeInstance();
        $ebUserMang = \app\wisdmlabs\edwiserBridge\EBUserManager::instance($ebLoader->getPluginName(), $ebLoader->getVersion());
        $ebUserMang->createWordpressUser($userData['email'], $userData['first_name'], $userData['last_name']);
    }
}

if (!function_exists('\ebsso\getArrayDataByIndex')) {

    function getArrayDataByIndex($data, $key, $value = '')
    {
        if (isset($data[$key])) {
            $value = $data[$key];
        }
        return $value;
    }
}

if (!function_exists('\ebsso\getSocialRedirect')) {

    function getSocialRedirectToURL($get, $redirect_url = '')
    {
        $post_content = null;
        if (isset($_SERVER['HTTP_REFERER']) && filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL)) {
            $postid       = url_to_postid($_SERVER['HTTP_REFERER']);
            $post_content = $postid ? get_post($postid)->post_content : null;
        }
        $redirect_url = getRedirectUrl($get, $post_content, $redirect_url);


        foreach ($get as $key => $value) {
            if ($key == 'is_enroll') {
                $key = 'auto_enroll';
            }
            $redirect_url = add_query_arg($key, $value, $redirect_url);
        }
        $redirect_url = apply_filters('eb_sso_set_social_login_redirect_url', $redirect_url);
        $state        = json_encode($redirect_url);
        $state        = base64_encode($state);
        return $state;
    }
}

function getRedirectUrl($get, $post_content, $redirect_url = '')
{
    if (isset($post_content) && has_shortcode($post_content, 'bridge_woo_single_cart_checkout')) {
        $redirect_url = $_SERVER['HTTP_REFERER'];
    } elseif (isset($post_content) && has_shortcode($post_content, 'woocommerce_checkout')) {
        $redirect_url = $_SERVER['HTTP_REFERER'];
    } elseif (isset($post_content) && has_shortcode($post_content, 'woocommerce_my_account')) {
        $redirect_url = $_SERVER['HTTP_REFERER'];
    } elseif (isset($get['redirect_to']) && filter_var($get['redirect_to'], FILTER_VALIDATE_URL)) {
        $redirect_url = $get['redirect_to'];
    } elseif (isset($get['redirect']) && filter_var($get['redirect'], FILTER_VALIDATE_URL)) {
        $redirect_url = $get['redirect'];
        unset($get['redirect']);
    }
    return $redirect_url;
}
