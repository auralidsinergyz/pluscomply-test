<?php
namespace LDMC\Admin\Settings;


if ( ! class_exists( '\LDMC\Admin\Settings\Course' ) ) {
	class Course {

		/**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
		use \LDMC\Traits\Helpers;

		private $settings_metabox_key;
		private $globel_object_id;

		public function __construct() {
			// Used within the Settings API to uniquely identify this section.
			$this->settings_metabox_key = 'learndash-course-display-content-settings';
            add_filter( 'learndash_settings_fields', array($this, 'multiselect_course_certificate'), 6, 2 );
        }


        function multiselect_course_certificate( $setting_option_fields, $settings_metabox_key )
        {
            // $this->write_log('multiselect_course_certificate');
            if ( $this->settings_metabox_key == $settings_metabox_key
                && isset($setting_option_fields['certificate'])
            ) {
                // $this->write_log('multiselect_course_certificate - case 01');

                    // $this->write_log('multiselect_course_certificate - case 02');

                    $setting_option_fields["certificate"]["type"] = "multiselect";
                    $setting_option_fields["certificate"]["multiple"] = "true";
                    $setting_option_fields["certificate"]["value_type"] = "intval";
                    $setting_option_fields["certificate"]["placeholder"] = __("Select Certificates",LD_MC_TEXT_DOMAIN);
                    $setting_option_fields["certificate"]["help_text"] = sprintf(
                    // translators: placeholder: course.
                        esc_html_x( 'The %s Multiple Certificates feature is enabled, so now you can select multiple certificates from the drop-down and studnets will be able to select their desired certificate from the frontend popup.', 'placeholder: course', LD_MC_TEXT_DOMAIN ),
                        learndash_get_custom_label_lower( 'course' )
                    );

	                $setting_option_values = learndash_get_setting( get_the_ID() );


	                $selected_certificates = ( isset($setting_option_values["certificate"]) ) ? $setting_option_values["certificate"] : [];

                  // Fixed issue by dev waqardrigh

                    if( !is_array($selected_certificates) ){

                        $selected_certificates = array($selected_certificates);
                    }

                // Fixed issue by dev waqardrigh

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




                    // added new field


                     if ( ! isset( $setting_option_fields['allow_multiple_certificates'] ) ) {


                        $ld_mc_allow_multiple = get_post_meta( get_the_ID(), 'ld_mc_allow_multiple', true );
                        if ( empty( $ld_mc_allow_multiple ) ) {
                                    $ld_mc_allow_multiple = '';
                        }
                        
                        $setting_option_fields["allow_multiple_certificates"]['name']  = 'ld_mc_allow_multiple';
                        $setting_option_fields["allow_multiple_certificates"]['label'] = sprintf(
                                    // translators: placeholder: Course.
                                    esc_html_x( 'Allow Multiple Certificates for %s', 'placeholder: Course', LD_MC_TEXT_DOMAIN ),
                                    learndash_get_custom_label( 'course' )
                                );

                        $setting_option_fields["allow_multiple_certificates"]['type']  = "checkbox-switch"; 
                        $setting_option_fields["allow_multiple_certificates"]['default']  = '' ;
                        $setting_option_fields["allow_multiple_certificates"]['value']  = $ld_mc_allow_multiple ;
                        $setting_option_fields["allow_multiple_certificates"]['help_text']  =   sprintf(
                                    // translators: placeholder: course.
                                    esc_html_x( 'User can download multiple certificates per %s.', 'placeholder: course.', LD_MC_TEXT_DOMAIN ),
                                    learndash_get_custom_label_lower( 'course' )
                                );
                        $setting_option_fields["allow_multiple_certificates"]['option']  = array(
                                    'on' => sprintf(
                                        // translators: placeholder: Course.
                                        esc_html_x( 'User can download multiple certificates per %s', 'placeholder: Course', LD_MC_TEXT_DOMAIN ),
                                        learndash_get_custom_label( 'course' )
                                    ),
                                    ''   => '',
                                );
                    }




            }


           


            return $setting_option_fields;
        }

    }
}