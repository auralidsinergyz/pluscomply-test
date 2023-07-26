<?php
namespace LDMC\Admin\Settings;

if ( ! class_exists( '\LDMC\Admin\Settings\General' ) ) {
    class General
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;


        public function __construct()
        {
            add_action( 'admin_init', array($this, 'sandbox_theme_intialize_social_options'), 10 );

           
        }


        /**
         * Initializes the theme's social options by registering the Sections,
         * Fields, and Settings.
         *
         * This function is registered with the 'admin_init' hook.
         */
        function sandbox_theme_intialize_social_options() {

            // If the social options don't exist, create them.
            if( false == get_option( 'sandbox_theme_social_options' ) ) {
                add_option( 'sandbox_theme_social_options' );
            } // end if


            add_settings_section(
                'social_settings_section',          // ID used to identify this section and with which to register options
                'Social Options',                   // Title to be displayed on the administration page
                array($this, 'sandbox_social_options_callback'),  // Callback used to render the description of the section
                'ld-mc-admin-dashboard'      // Page on which to add this section of options
            );

            add_settings_field(
                'twitter',
                'Twitter',
                array($this, 'sandbox_twitter_callback'),
                'ld-mc-admin-dashboard',
                'social_settings_section'
            );

            add_settings_field(
                'facebook',
                'Facebook',
                array($this,'sandbox_facebook_callback'),
                'ld-mc-admin-dashboard',
                'social_settings_section'
            );

            add_settings_field(
                'googleplus',
                'Google+',
                array($this,'sandbox_googleplus_callback'),
                'ld-mc-admin-dashboard',
                'social_settings_section'
            );

            register_setting(
                'sandbox_theme_social_options',
                'sandbox_theme_social_options',
                'sandbox_theme_sanitize_social_options'
            );

        } // end sandbox_theme_intialize_social_options


        function sandbox_social_options_callback() {
            echo '<p>Provide the URL to the social networks you\'d like to display.</p>';
        } // end sandbox_general_options_callback


        function sandbox_twitter_callback() {

            // First, we read the social options collection
            $options = get_option( 'sandbox_theme_social_options' );

            // Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
            $url = '';
            if( isset( $options['twitter'] ) ) {
                $url = $options['twitter'];
            } // end if

            // Render the output
            echo '<input type="text" id="twitter" name="sandbox_theme_social_options[twitter]" value="' . $options['twitter'] . '" />';

        } // end sandbox_twitter_callback



        function sandbox_theme_sanitize_social_options( $input ) {

            // Define the array for the updated options
            $output = array();

            // Loop through each of the options sanitizing the data
            foreach( $input as $key => $val ) {

                if( isset ( $input[$key] ) ) {
                    $output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
                } // end if

            } // end foreach

            // Return the new collection
            return apply_filters( 'sandbox_theme_sanitize_social_options', $output, $input );

        } // end sandbox_theme_sanitize_social_options


        function sandbox_facebook_callback() {

            $options = get_option( 'sandbox_theme_social_options' );

            $url = '';
            if( isset( $options['facebook'] ) ) {
                $url = $options['facebook'];
            } // end if

            // Render the output
            echo '<input type="text" id="facebook" name="sandbox_theme_social_options[facebook]" value="' . $options['facebook'] . '" />';

        } // end sandbox_facebook_callback

        function sandbox_googleplus_callback() {

            $options = get_option( 'sandbox_theme_social_options' );

            $url = '';
            if( isset( $options['googleplus'] ) ) {
                $url = $options['googleplus'];
            } // end if

            // Render the output
            echo '<input type="text" id="googleplus" name="sandbox_theme_social_options[googleplus]" value="' . $options['googleplus'] . '" />';

        } // end sandbox_googleplus_callback


    }
}