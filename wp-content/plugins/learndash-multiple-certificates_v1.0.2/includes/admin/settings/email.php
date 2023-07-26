<?php
namespace LDMC\Admin\Settings;

if ( ! class_exists( '\LDMC\Admin\Settings\Email' ) ) {
    class Email
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        public $options = [];

        public function __construct()
        {
            $this->options = get_option('ld_mc_email_options');
            add_action('admin_init', array($this, 'ld_mc_initialize_email_options'), 10 );
//            add_action( 'save_post', array($this, 'save_wp_editor_fields'), 10 );
        }

        function save_wp_editor_fields(){
//            global $post;
            // $this->write_log('$_POST');
            // $this->write_log($_POST);
//            update_post_meta($post->ID, 'ld_mc_email_options', $_POST['ld_mc_email_options']);

            update_option('ld_mc_email_options', $_POST['ld_mc_email_options']);
        }

        /* ------------------------------------------------------------------------ *
        * Setting Registration
        * ------------------------------------------------------------------------ */
        public function ld_mc_initialize_email_options() {
            // $this->write_log('ld_mc_initialize_email_options');

            if( isset($_POST['submit']) ) {
                $this->save_wp_editor_fields();
            }

            // If the theme options don't exist, create them.
            if( false == get_option( 'ld_mc_email_options' ) ) {
                add_option( 'ld_mc_email_options' );
            } // end if

            $options = get_option( 'ld_mc_email_options' );
             // $this->write_log('$options');
             // $this->write_log($options);

            // First, we register a section. This is necessary since all future options must belong to a
            add_settings_section(
                'ld_mc_email_general_settings_section',         // ID used to identify this section and with which to register options
                __('The Group Certificate Email Notification Settings',LD_MC_TEXT_DOMAIN),                  // Title to be displayed on the administration page
                array( $this, 'sandbox_general_options_callback'), // Callback used to render the description of the section
                'ld_mc_email_settings_page'                           // Page on which to add this section of options
            );

            add_settings_field(
                'ld_mc_email_status',
                __('Email Status',LD_MC_TEXT_DOMAIN),
                array( $this, 'ld_mc_email_status_callback'),
                'ld_mc_email_settings_page',
                'ld_mc_email_general_settings_section',
                array(
//                    'label_for' => 'Activate this setting to display the header.',
                    'class' => ''
                )
            );

            add_settings_field(
                'ld_mc_email_subject',
                __('Email Subject',LD_MC_TEXT_DOMAIN),
                array($this, 'ld_mc_email_subject_callback'),
                'ld_mc_email_settings_page',
                'ld_mc_email_general_settings_section',
                array(
//                    'label_for' => 'Activate this setting to display the header.',
                    'class' => ''
                )
            );

            // Next, we'll introduce the fields for toggling the visibility of content elements.
            add_settings_field(
                'ld_mc_email_content',                      // ID used to identify the field throughout the theme
                __('Email Content',LD_MC_TEXT_DOMAIN),                           // The label to the left of the option interface element
                array( $this, 'ld_mc_email_content_callback'),   // The name of the function responsible for rendering the option interface
                'ld_mc_email_settings_page',                          // The page on which this option will be displayed
                'ld_mc_email_general_settings_section',         // The name of the section to which this field belongs
                array(                              // The array of arguments to pass to the callback. In this case, just a description.
//                    'label_for' => 'Activate this setting to display the header.',
                    'class' => ''
                )
            );





            // Finally, we register the fields with WordPress
            register_setting(
                'ld_mc_email_general_settings_group',
                'ld_mc_email_status',
                array(
                    'type' => 'string',
                    'description' => 'This is testing description',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );
            register_setting(
                'ld_mc_email_general_settings_group',
                'ld_mc_email_content',
                array(
                    'type' => 'string',
                    'description' => 'This is testing description',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );

            register_setting(
                'ld_mc_email_general_settings_group',
                'ld_mc_email_subject',
                array(
                    'type' => 'string',
                    'description' => 'This is testing description',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );



        }


        /* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

        function sandbox_general_options_callback() {
            echo '<p>'.__('The following settings will be applied on the group certificate email notification.',LD_MC_TEXT_DOMAIN).'</p>';
        } // end sandbox_general_options_callback


        /* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */

        function ld_mc_email_subject_callback($args) {

            $options = get_option('ld_mc_email_options');

            $html = '<input type="text" id="ld_mc_email_subject" name="ld_mc_email_options[ld_mc_email_subject]" value=" ' . (( isset($options['ld_mc_email_subject']) ) ? $options['ld_mc_email_subject']: '') . '" />';
            $html .= '<label for="ld_mc_email_subject"> '  . (( isset($args[0]) ) ? $args[0]: '') . '</label>';
            $html .= '<p>'.__('The following settings will be applied on the group certificate email notification.',LD_MC_TEXT_DOMAIN).'</p>';

            echo $html;

        }

        function ld_mc_email_content_callback($args) {

            // First, we read the options collection
            $options = get_option('ld_mc_email_options');
            $content   = '';
            if( ! empty($options) && is_array($options) && count($options) > 0 ){
                $content   = $options['ld_mc_email_content'];
            }
            $editor_id = 'ld_mc_email_options';
            $args = array(
//                'media_buttons' => false, // This setting removes the media button.
                'textarea_name' => 'ld_mc_email_options[ld_mc_email_content]', // Set custom name.
                'textarea_rows' => get_option('default_post_edit_rows', 10), //Determine the number of rows.
//                'quicktags' => false, // Remove view as HTML button.
            );
            wp_editor( $content, $editor_id, $args );
        }




        function ld_mc_email_status_callback()
        {
            $value = ( isset($this->options['ld_mc_email_status'])) ? $this->options['ld_mc_email_status']: 'enabled';
            ?>
            <select name="ld_mc_email_options[ld_mc_email_status]">
                <option value="enabled" <?php selected($value, "enabled"); ?>>Enabled</option>
                <option value="disabled" <?php selected($value, "disabled"); ?>>Disabled</option>
            </select>
            <?php
        }


        function ld_mc_email_status_callback_old($args) {

            $options = get_option('ld_mc_email_options');

            $html = '<input type="checkbox" id="ld_mc_email_status" name="ld_mc_email_options[ld_mc_email_status]" value="1" ' . checked(1, (( isset($options['ld_mc_email_status']) ) ? $options['ld_mc_email_status']: ''), false) . '/>';
            $html .= '<label for="ld_mc_email_status"> '  . (( isset($args[0]) ) ? $args[0]: '') . '</label>';
            $html .= '<p>'.__('The following settings will be applied on the group certificate email notification.',LD_MC_TEXT_DOMAIN).'</p>';

            echo $html;

        }


    }
}