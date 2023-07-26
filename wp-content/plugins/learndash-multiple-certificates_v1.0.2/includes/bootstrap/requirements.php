<?php
namespace LDMC\Bootstrap;

if ( ! class_exists( '\LDMC\Bootstrap\Requirements' ) ) {
    class Requirements
    {
        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        private $ld_plugin_data;
        private $ldmc_plugin_data;

        public function __construct()
        {
            $this->ld_plugin_data = get_plugin_data( LD_MC_LD_MAIN_FILE_ABSOLUTE_PATH, false, false);
            $this->ldmc_plugin_data = get_plugin_data( LD_MC_MAIN_FILE_ABSOLUTE_PATH, false, false );
            add_action('ld_mc_deactivate', array($this, 'deactivate_ld_mc_plugin'), 10);
        }

        public function validate_requirements() {
            global $wp_version;
            $status = true;
            /* https://wordpress.org/support/article/requirements/ */
            if( file_exists( LD_MC_LD_MAIN_FILE_ABSOLUTE_PATH ) ) {
               if ( ( ! is_plugin_active( LD_MC_LD_MAIN_FILE_RELATIVE_PATH ) || ! class_exists( 'SFWD_LMS' ) ) ) {
                   add_action('admin_notices', array($this, 'ld_dependeny_validation_admin_notice_error'));
                    return false;
                }else{
                    if (version_compare($this->ld_plugin_data['Version'], '3.3.0.3', '<')) {
                        add_action('admin_notices', array($this, 'ld_dependeny_validation_admin_notice_error'));
                        return false;
                    }
                }
            }else{
                add_action('admin_notices', array($this, 'ld_dependeny_validation_admin_notice_error'));
                return false;
            }
            return $status;
        }

        public function ld_dependeny_validation_admin_notice_error()
        {
            do_action('ld_mc_deactivate');
            $class = 'notice notice-error';
            $message = "";
            if (file_exists( LD_MC_LD_MAIN_FILE_ABSOLUTE_PATH )) {
                if ( ( ! is_plugin_active( LD_MC_LD_MAIN_FILE_RELATIVE_PATH ) || ! class_exists( 'SFWD_LMS' ) ) ) {
                    $message = sprintf( 'Sorry ! The %1s is deactivated because the required %2s is not activated, Kindly activate the %2s and than try again. Thanks', $this->ldmc_plugin_data['Name'], $this->ld_plugin_data['Name'], $this->ld_plugin_data['Version'] );
                }else{
                    if (version_compare($this->ld_plugin_data['Version'], '3.3.0.3', '<')) {
                        $message = sprintf('Sorry ! The %1s is deactivated because your %2s current active version %3s is less than the required Version 3.3.0.3, Kindly install and activate the %2s latest and than try again. Thanks', $this->ldmc_plugin_data['Name'], $this->ld_plugin_data['Name'], $this->ld_plugin_data['Version'] );
                    }
                }
            }else{
                $message = sprintf('Sorry ! The %1s is deactivated because the required %2s is not found,  Kindly install and activate and than try again. Thanks', $this->ldmc_plugin_data['Name']);
            }
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html__($message, LD_MC_TEXT_DOMAIN) );
        }

        public function deactivate_ld_mc_plugin( ){
            if( is_plugin_active( LD_MC_BASE_DIR) || class_exists( '\LDMC\Bootstrap\App' ) ){
                deactivate_plugins( LD_MC_BASE_DIR, true );
            }
        }

    }
}