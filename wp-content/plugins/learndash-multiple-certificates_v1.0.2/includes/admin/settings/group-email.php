<?php
namespace LDMC\Admin\Settings;

if ( ! class_exists( '\LDMC\Admin\Settings\GroupEmail' ) ) {
    class GroupEmail
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        public function __construct()
        {
            add_action('admin_init', array($this, 'ld_mc_initialize_group_email_settings'), 10 );
        }


        /* ------------------------------------------------------------------------ *
        * Setting Registration
        * ------------------------------------------------------------------------ */
        public function ld_mc_initialize_group_email_settings() {
            // $this->write_log('ld_mc_initialize_group_email_settings');

            // First, we register a section. This is necessary since all future options must belong to a
            add_settings_section(
                'ld_mc_group_email_settings_section',
                sprintf(
                        esc_html_x('The %1$s Certificate Email Notification Settings', 'placeholder: group', LD_MC_TEXT_DOMAIN),
                    learndash_get_custom_label( 'group' )
                ),
                array( $this, 'ld_mc_group_email_settings_section_callback'),
                'ld_mc_group_email_settings_page'
            );
            add_settings_field(
                'ld_mc_group_email_status',
                __('Email Notification Status',LD_MC_TEXT_DOMAIN),
                array( $this, 'ld_mc_group_email_status_callback'),
                'ld_mc_group_email_settings_page',
                'ld_mc_group_email_settings_section',
                array(
                    'label_for' => '',
                    'class' => '',
                    'help' => __('You can enable or disable this email notification submission.',LD_MC_TEXT_DOMAIN)
                )
            );
            add_settings_field(
                'ld_mc_group_email_subject',
                __('Email Notification Subject',LD_MC_TEXT_DOMAIN),
                array($this, 'ld_mc_group_email_subject_callback'),
                'ld_mc_group_email_settings_page',
                'ld_mc_group_email_settings_section',
                array(
                    'label_for' => '',
                    'class' => '',
                    'help' => __('The text inside this field will be used as subject of this email notification.',LD_MC_TEXT_DOMAIN)
                )
            );
            // Next, we'll introduce the fields for toggling the visibility of content elements.
            add_settings_field(
                'ld_mc_group_email_body',
                __('Email Notification Body',LD_MC_TEXT_DOMAIN),
                array( $this, 'ld_mc_group_email_body_callback'),
                'ld_mc_group_email_settings_page',
                'ld_mc_group_email_settings_section',
            );

            // Finally, we register the fields with WordPress
            register_setting(
                'ld_mc_group_email_settings_group',
                'ld_mc_group_email_status',
                array(
                    'type' => 'string',
                    'description' => '',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );
            register_setting(
                'ld_mc_group_email_settings_group',
                'ld_mc_group_email_subject',
                array(
                    'type' => 'string',
                    'description' => '',
                    'sanitize_callback' => '',
                    'show_in_rest' => '',
                    'default' => '',
                ),
            );
            register_setting(
                'ld_mc_group_email_settings_group',
                'ld_mc_group_email_body',
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

        function ld_mc_group_email_settings_section_callback() {
            echo '<p>'.__('The student will receive an email notification after selecting the certificate from the frontend popup',LD_MC_TEXT_DOMAIN).'</p>';
        }


        /* ------------------------------------------------------------------------ *
        * Field Callbacks
        * ------------------------------------------------------------------------ */

        function ld_mc_group_email_status_callback($args)
        {
            $value = ( ! empty(get_option('ld_mc_group_email_status')) ) ? get_option('ld_mc_group_email_status'): 'disabled';
            ?>
            <select name="ld_mc_group_email_status">
                <option value="enabled" <?php selected($value, "enabled"); ?>><?php echo __('Enabled',LD_MC_TEXT_DOMAIN); ?></option>
                <option value="disabled" <?php selected($value, "disabled"); ?>><?php echo __('Disabled',LD_MC_TEXT_DOMAIN); ?></option>
            </select>
            <p><?php echo $args['help']; ?></p>
            <?php
        }

        function ld_mc_group_email_subject_callback($args) {
            // $this->write_log('ld_mc_group_email_subject_callback');
            // $this->write_log('$args');
            // $this->write_log($args);

            // $this->write_log('group - subject');
            // $this->write_log($this->get_group_email_notification_subject());
        ?>
            <input type="text" id="ld_mc_group_email_subject" name="ld_mc_group_email_subject" value="<?php echo $this->get_group_email_notification_subject(); ?>" />
            <p><?php echo $args['help'];  ?></p>
        <?php
        }

        function ld_mc_group_email_body_callback($args) {
            $editor_id = 'ld_mc_group_email_options';
            $args = array(
//                'media_buttons' => false, // This setting removes the media button.
                'textarea_name' => 'ld_mc_group_email_body', // Set custom name.
                'textarea_rows' => get_option('default_post_edit_rows', 10), //Determine the number of rows.
//                'quicktags' => false, // Remove view as HTML button.
            );
            wp_editor( $this->get_group_email_notification_body(), $editor_id, $args );
        }


    }
}