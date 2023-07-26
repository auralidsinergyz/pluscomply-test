<?php
namespace LDMC\Admin\Ajax;

if ( ! class_exists( '\LDMC\Admin\Ajax\License' ) ) {
    class License {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;

        public function __construct(){
            /**
             * Ajax JS  Request's Client Setup
             */
            add_action( 'admin_enqueue_scripts', array($this, 'ajax_js_client_setup'), 10, 1 );
            /**
             * Ajax PHP Post Request's Handler Setup
             */
            add_action('wp_ajax_update_license_settings', array($this, 'update_license_settings') );
        }

        public function ajax_js_client_setup(){
            wp_register_script( 'ld-mc-license-settings-js', LD_MC_ASSETS_URL . 'admin/js/ajax/license_settings.js', array('jquery'), time(), true );
            wp_localize_script( 'ld-mc-license-settings-js', 'license_settings', array(
                'ajax' => array(
                    'get' => array(
                        'url'   =>  esc_url_raw(admin_url( 'admin-ajax.php' )),
                        'action' => 'get_license_settings',
                        'nonce' => wp_create_nonce( 'ajax_get_license_settings_nonce' ),
                        'messages' => array(
                            'success' => __( 'Congratulation data updated Successfully!', LD_MC_TEXT_DOMAIN ),
                            'error' => __( 'Sorry! Can not update data', LD_MC_TEXT_DOMAIN ),
                        ),
                    ),
                    'update' => array(
                        'url'   =>  esc_url_raw(admin_url( 'admin-ajax.php' )),
                        'action' => 'update_license_settings',
                        'nonce' => wp_create_nonce( 'ajax_update_license_settings_nonce' ),
                        'messages' => array(
                            'success' => __( 'Congratulation you have been activated the plugin successfully!', LD_MC_TEXT_DOMAIN ),
                            'error' => __( 'Sorry! you can not activate by using provided License Key', LD_MC_TEXT_DOMAIN ),
                        ),
                    )
                ),
            ) );
            wp_enqueue_script('ld-mc-license-settings-js');
        }

        public function update_license_settings(){
            if (check_ajax_referer( 'ajax_update_license_settings_nonce', 'security' )){
                if( isset($_POST['license_key']) && ! empty($_POST['license_key']) ){
                    $license_key_updated = false;
                    $license_status_updated = false;
                    $License_Manager = new \LDMC\License\License_Manager();
                    $license_key_updated = update_option('ld_mc_license_key', $_POST['license_key'] );

                    if( $_POST['license_edd_action'] == 'activate' ){
                        if( $License_Manager->get_license_handler()->activate_license($_POST['license_key']) ){
                            $license_status_updated = true;
                        }
                    }elseif( $_POST['license_edd_action'] == 'deactivate' ){
                        if( $License_Manager->get_license_handler()->deactivate_license($_POST['license_key']) ){
                            $license_status_updated = true;
                        }
                    }

                    if( $license_key_updated == true && $license_status_updated == true ){
                        $license_settings = [
                            'license_key' => get_option('ld_mc_license_key'),
                            'license_status' => get_option('ld_mc_license_status'),
                        ];
                        return wp_send_json_success($license_settings);
                    }else{
                        return wp_send_json_error(array('error_code' => 500, 'error_message' => __( 'License update is failed', LD_MC_TEXT_DOMAIN ) ));
                    }
                }
            }else{
                return wp_send_json_error(array('error_code' => 498, 'error_message' => __( 'Security Token Verification is failed', LD_MC_TEXT_DOMAIN ) ));
            }
        }



    }

}