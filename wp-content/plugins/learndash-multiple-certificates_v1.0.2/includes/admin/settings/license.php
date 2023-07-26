<?php
namespace LDMC\Admin\Settings;

if ( ! class_exists( '\LDMC\Admin\Settings\License' ) ) {
    class License
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        public function __construct()
        {
            add_action('admin_init', array($this, 'ld_mc_initialize_license_settings'), 10 );
            /**
             * Trigger the License API Request after the License Key activation
             */
            add_action('pre_update_option_ld_mc_license_key', array($this, 'license_api_request'), 10, 3 );
        }


        public function license_api_request(  $value, $old_value, $option ){
            // $this->write_log('license_api_request');
            // $this->write_log('$_POST');
            // $this->write_log($_POST);
            if( $_POST['license_edd_action'] == 'activate' ){
                $this->get_license_manager()->set_license_key( $_POST['ld_mc_license_key'] );
                $attempt_activate = $this->get_license_manager()->activate();

                if ( is_wp_error( $attempt_activate ) ) {
                    // $this->write_log('License key not activated successfully');

//                    $license_response = $this->get_license_manager()->get_response();
//                    $this->write_log('$license_response');
//                    $this->write_log($license_response);
//
//                    add_settings_error(
//                        'edd-license-notices',
//                        esc_attr( $license_response['code'] ),
//                        $license_response['status']['message'],
//                        $license_response['status']['type']
//                    );

                } else {

                    // $this->write_log('License key activated successfully');

//                    $license_response = $this->get_license_manager()->get_response();
//                    $this->write_log('$license_response');
//                    $this->write_log($license_response);
//
//                    add_settings_error(
//                        'edd-license-notices',
//                        esc_attr( $license_response['code'] ),
//                        $license_response['status']['message'],
//                        $license_response['status']['type']
//                    );

                }
//                if( $License_Manager->get_license_handler()->activate_license($_POST['ld_mc_license_key']) ){
//                    $admin_notice_data = $License_Manager->get_license_admin_notice_data();
//                    $this->write_log('$admin_notice_data');
//                    $this->write_log($admin_notice_data);
//                }
            }elseif( $_POST['license_edd_action'] == 'deactivate' ){

                $this->get_license_manager()->set_license_key( $_POST['ld_mc_license_key'] );
                $attempt_deactivate = $this->get_license_manager()->deactivate();

                if ( is_wp_error( $attempt_deactivate ) ) {
                    // $this->write_log('License key not deactivated successfully');
                } else {
                    // $this->write_log('License key deactivated successfully');
                }

//                if( $License_Manager->get_license_handler()->deactivate_license($_POST['ld_mc_license_key']) ){
//                    $admin_notice_data = $License_Manager->get_license_admin_notice_data();
//                    $this->write_log('$admin_notice_data');
//                    $this->write_log($admin_notice_data);
//                }
            }
            return $value;
        }

        /* ------------------------------------------------------------------------ *
        * Setting Registration
        * ------------------------------------------------------------------------ */
        public function ld_mc_initialize_license_settings() {
            // $this->write_log('ld_mc_initialize_license_settings');


            // First, we register a section. This is necessary since all future options must belong to a
            add_settings_section(
                'ld_mc_license_settings_section',
                __('License Configuration',LD_MC_TEXT_DOMAIN),
                array( $this, 'ld_mc_license_settings_section_callback'),
                'ld_mc_license_settings_page'
            );
//            add_settings_field(
//                'ld_mc_license_status',
//                'License Status',
//                array( $this, 'ld_mc_license_status_callback'),
//                'ld_mc_license_settings_page',
//                'ld_mc_license_settings_section',
//                array(
//                    'label_for' => '',
//                    'class' => '',
//                    'help' => 'You can enable or disable the license.'
//                )
//            );
            add_settings_field(
                'ld_mc_license_key',
                __('License key',LD_MC_TEXT_DOMAIN),
                array($this, 'ld_mc_license_key_callback'),
                'ld_mc_license_settings_page',
                'ld_mc_license_settings_section',
                array(
                    'label_for' => '',
                    'class' => '',
                    'help' => 'Please enter a valid license key for LearnDash Multiple Certificates Addon to receive the latest updates.'
                )
            );

            // Finally, we register the fields with WordPress
            register_setting(
                'ld_mc_license_settings_group',
                'ld_mc_license_status',
                array(
                    'type' => 'string',
                    'description' => '',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );
            register_setting(
                'ld_mc_license_settings_group',
                'ld_mc_license_key',
                array(
                    'type' => 'string',
                    'description' => '',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );
        }


        /* ------------------------------------------------------------------------ *
        * Section Callbacks
        * ------------------------------------------------------------------------ */

        function ld_mc_license_settings_section_callback() {
            echo '<p>'.__('Please enter the license key for this product to get automatic updates. You were emailed the license key when you purchased this item',LD_MC_TEXT_DOMAIN).'</p>';
        }


        /* ------------------------------------------------------------------------ *
        * Field Callbacks
        * ------------------------------------------------------------------------ */



        function ld_mc_license_key_callback($args) {
            // $this->write_log('ld_mc_license_key_callback');
            // $this->write_log('$args');
            // $this->write_log($args);
            $value = ( ! empty(get_option('ld_mc_license_key')) ) ? get_option('ld_mc_license_key'): '';
            ?>
            <input type="text" id="ld_mc_license_key" name="ld_mc_license_key" value="<?php echo $value; ?>" />
<!--            <p>--><?php //echo $args['help'];  ?><!--</p>-->
            <?php

            if( $this->get_license_manager()->get_status_type() == 'error' || $this->get_license_manager()->get_status_type() == 'warning' ){
                $icon = '<span class="dashicons dashicons-dismiss"></span>';
                printf( '<div class=""><p> %1$s %2$s</p></div>', __($icon), __( $this->get_license_manager()->get_status_message() ) );
            }else{
                $icon = '<span class="dashicons dashicons-yes-alt"></span>';
                printf( '<div class=""><p> %1$s %2$s</p></div>', __($icon), __( $this->get_license_manager()->get_status_message() ) );
            }
        }


    }
}