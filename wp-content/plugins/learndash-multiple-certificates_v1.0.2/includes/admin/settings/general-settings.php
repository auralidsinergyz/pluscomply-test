<?php
namespace LDMC\Admin\Settings;

if ( ! class_exists( '\LDMC\Admin\Settings\GeneralSettings' ) ) {
    class GeneralSettings
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        public function __construct()
        {
            add_action('admin_init', array($this, 'ld_mc_initialize_general_settings'), 10 );
            
        }



        /* ------------------------------------------------------------------------ *
        * Setting Registration
        * ------------------------------------------------------------------------ */
        public function ld_mc_initialize_general_settings() {
            
        

            // First, we register a section. This is necessary since all future options must belong to a
            add_settings_section(
                'ld_mc_general_settings_section',
                __('General Settings',LD_MC_TEXT_DOMAIN),
                array( $this, 'ld_mc_general_settings_section_callback'),
                'ld_mc_general_settings_page'
            );
            
            add_settings_field(
                'ld_mc_general_multi_certificate_on_course',
                __('Let the user always choose the course certificate',LD_MC_TEXT_DOMAIN),
                array($this, 'ld_mc_general_multi_certificate_on_course_callback'),
                'ld_mc_general_settings_page',
                'ld_mc_general_settings_section',
                array(
                    'label_for' => '',
                    'class' => '',
                    'help' => __('Let the user always choose the course certificate.',LD_MC_TEXT_DOMAIN)
                )
            );


             add_settings_field(
                'ld_mc_general_multi_certificate_on_quizzes',
                __('Let the user always choose the quiz certificate',LD_MC_TEXT_DOMAIN),
                array($this, 'ld_mc_general_multi_certificate_on_quizzes_callback'),
                'ld_mc_general_settings_page',
                'ld_mc_general_settings_section',
                array(
                    'label_for' => '',
                    'class' => '',
                    'help' => __('Let the user always choose the quiz certificate.',LD_MC_TEXT_DOMAIN)
                )
            );

            // Finally, we register the fields with WordPress
            register_setting(
                'ld_mc_general_settings_group',
                'ld_mc_general_status',
                array(
                    'type' => 'string',
                    'description' => '',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );
            register_setting(
                'ld_mc_general_settings_group',
                'ld_mc_general_multi_certificate_on_course',
                array(
                    'type' => 'string',
                    'description' => '',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );


            register_setting(
                'ld_mc_general_settings_group',
                'ld_mc_general_multi_certificate_on_quizzes',
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

        function ld_mc_general_settings_section_callback() {
            echo '<p>'.__('Allowed or Disallowed Multiple Certificates',LD_MC_TEXT_DOMAIN).'</p>';
        }


        /* ------------------------------------------------------------------------ *
        * Field Callbacks
        * ------------------------------------------------------------------------ */




        
        function ld_mc_general_multi_certificate_on_course_callback($args) {
           
            $value = ( ! empty(get_option('ld_mc_general_multi_certificate_on_course')) ) ? get_option('ld_mc_general_multi_certificate_on_course'): '';

            // echo var_dump($value) ;
            ?>
            <input type="checkbox" id="ld_mc_general_multi_certificate_on_course" name="ld_mc_general_multi_certificate_on_course" value="1" <?php checked($value,'1'); ?> />
            <?php

            
        }




         function ld_mc_general_multi_certificate_on_quizzes_callback($args) {
           
            $value = ( ! empty(get_option('ld_mc_general_multi_certificate_on_quizzes')) ) ? get_option('ld_mc_general_multi_certificate_on_quizzes'): '';

            // echo var_dump($value) ;
            ?>
            <input type="checkbox" id="ld_mc_general_multi_certificate_on_quizzes" name="ld_mc_general_multi_certificate_on_quizzes" value="1" <?php checked($value,'1'); ?> />
            <?php

            
        }


    }
}