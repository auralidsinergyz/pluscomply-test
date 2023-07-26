<?php
namespace LDMC\Admin\Settings;


if ( ! class_exists( '\LDMC\Admin\Settings\Quiz' ) ) {
    class Quiz {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        private $settings_metabox_key;

        public function __construct() {
            // Used within the Settings API to uniquely identify this section.
            $this->settings_metabox_key = 'learndash-quiz-progress-settings';

            add_filter( 'learndash_settings_fields', array($this, 'multiselect_group_certificate'), 6, 2 );

        }



        function multiselect_group_certificate( $setting_option_fields, $settings_metabox_key )
        {
            // $this->write_log('multiselect_group_certificate');
            if ( $this->settings_metabox_key == $settings_metabox_key
                && isset($setting_option_fields['certificate'])
            ) {
                // $this->write_log('multiselect_course_certificate - case 01');

                // $this->write_log('multiselect_group_certificate - case 02');

                $setting_option_fields["certificate"]["type"] = "multiselect";
                $setting_option_fields["certificate"]["multiple"] = "true";
                $setting_option_fields["certificate"]["value_type"] = "intval";
                $setting_option_fields["certificate"]["placeholder"] = __("Select Certificates",LD_MC_TEXT_DOMAIN);
                $setting_option_fields["certificate"]["help_text"] = sprintf(
                // translators: placeholder: course.
                    esc_html_x( 'The %s Multiple Certificates feature is enabled, so now you can select multiple certificates from the drop-down and studnets will be able to select their desired certificate from the frontend popup.', 'placeholder: quiz', LD_MC_TEXT_DOMAIN ),
                    learndash_get_custom_label_lower( 'quiz' )
                );

	            $setting_option_values = learndash_get_setting( get_the_ID() );
	            $selected_certificates = ( isset($setting_option_values["certificate"]) ) ? $setting_option_values["certificate"] : [];
	            if( is_array($selected_certificates) && count($selected_certificates) > 0 ) {
		            foreach ($selected_certificates as $certificate_id) {
			            $cert_post = get_post($certificate_id);
			            $setting_option_fields["certificate"]["options"][$cert_post->ID] = $cert_post->post_title;
		            }
	            }

                $select_course_prerequisite_query_data_json = '';
                if ( ( defined( 'LEARNDASH_SELECT2_LIB_AJAX_FETCH' ) ) && ( true === apply_filters( 'learndash_select2_lib_ajax_fetch', LEARNDASH_SELECT2_LIB_AJAX_FETCH ) ) ) {
                    $select_course_prerequisite_query_data_json = $this->build_settings_select2_lib_ajax_fetch_json(
                        array(
                            'query_args'       => array(
                                'post_type'    => learndash_get_post_type_slug( 'certificate' ),
                                'post__not_in' => $selected_certificates,
                            ),
                            'settings_element' => array(
                                'settings_parent_class' => 'LearnDash_Settings_Metabox',
                                'settings_class'        => 'LearnDash_Settings_Metabox_Course_Display_Content',
                                'settings_field'        => 'certificate',
                            ),
                        )
                    );
                }
	            $setting_option_fields["certificate"]['attrs']['data-select2-query-data'] = $select_course_prerequisite_query_data_json;

                // $this->write_log('$setting_option_fields');
                // $this->write_log($setting_option_fields);


            }

            return $setting_option_fields;
        }


    }
}