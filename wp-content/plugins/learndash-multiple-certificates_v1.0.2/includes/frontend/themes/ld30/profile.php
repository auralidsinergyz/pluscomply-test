<?php
namespace LDMC\FrontEnd\Themes\LD30;

if ( ! class_exists( '\LDMC\FrontEnd\Themes\LD30\Profile' ) ) {
    class Profile
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        public $course_id = 0;
        public $certificate_user_id = 0;

        public function __construct()
        {
            /**
             * Update LD Profile Certificates Counter
             */
            add_filter('learndash-get-user-stats', array($this, 'update_ld_profile_certificates_counter'), 10, 2);
            /**
             * The LearnDash profile Course Certificate Icon and Link implementation
             */
            add_filter('learndash_profile_stats', array($this, 'ld_profile_course_certificate_setup'), 10, 2 );

            add_filter('learndash_profile_quiz_columns', array($this, 'show_quizzes_attempt_certificate_icons'), 10, 3 );


            
        }

        public function get_user_enrolled_courses_certificates( $user_id = 0 ){
            $certificates = [];
            $certificate_index = 0;
            /**
             * Course Certificates
             */
            $courses_ids = learndash_user_get_enrolled_courses($user_id);
            // $this->write_log('$courses_ids');
            // $this->write_log($courses_ids);
            // $this->write_log('get_certificate - courses certificates');
            if (is_array($courses_ids) && count($courses_ids) > 0) {
                // $this->write_log('get_certificate - courses certificates - case 01');
                foreach ($courses_ids as $course_id) {
                    /**
                     * Course Certificates
                     */
                    $ser_course_certificate_id = $this->get_user_course_certificate_id($user_id, $course_id);
                    if ( ! is_array($ser_course_certificate_id) && intval($ser_course_certificate_id) != 0) {
                        $certificates[$certificate_index] = $ser_course_certificate_id;
                        $certificate_index++;
                    }
                }
            }
            return $certificates;
        }

        public function update_ld_profile_certificates_counter( $stats, $user_id )
        {
            $user_certificates = $this->get_user_enrolled_courses_certificates( $user_id );
            $user_certificates_count = count($user_certificates);
            $stats['certificates'] = $user_certificates_count;
            return $stats;
        }

        public function ld_profile_course_certificate_setup( $learndash_profile_stats, $user_id ){
            add_filter('learndash-course-row-class', array($this, 'update_properties'), 10, 3 );
            add_filter('learndash_status_bubble', array($this, 'ld_profile_course_certificate'), 10, 2 );
            return $learndash_profile_stats;
        }

        public function update_properties( $course_row_class, $course, $user_id ){
            // $this->write_log('update_learndash_course_certificate_link');
            $course_status = learndash_course_status($course->ID, $user_id, true);
            if ($course_status == 'completed') {
                // $this->write_log('update_learndash_course_certificate_link - cae 01');
                $this->course_id = $course->ID;
                $this->certificate_user_id = $user_id;
//                $user_selected_certificate_id = $this->get_user_selected_certificate_id($course_id, $cert_user_id);
//                if( 0 != $user_selected_certificate_id ){
//                    $this->write_log('update_learndash_course_certificate_link - cae 02');
//                    $cert_query_args['cert-nonce'] = wp_create_nonce($course_id . $cert_user_id . $cert_user_id);
//                    $cert_query_args['course_id'] = $course_id;
//                    $url = add_query_arg($cert_query_args, get_permalink(intval($user_selected_certificate_id)));
//                }else{
//                    $this->write_log('update_learndash_course_certificate_link - cae 03');
//                    $url = 'javascript:;';
//                }
            }else{
                // $this->write_log('update_learndash_course_certificate_link - cae 04');
            }
            return $course_row_class;
        }

        public function ld_profile_course_certificate( $bubble, $status ){
            // $this->write_log('ld_profile_course_certificate');

            if (class_exists('\BuddyBossTheme\BaseTheme') || function_exists('genesis_constants') )
                return $bubble;
            
            if( ( $status == 'complete' ) || ( $status == 'completed' ) || ( $status == 'Completed' ) ){
                // $this->write_log('ld_profile_course_certificate - case 01');
                // 
                //var_dump($status);
                $user_selected_certificate_id = $this->get_user_selected_certificate_id($this->course_id, $this->certificate_user_id);
                $us = $this->ld_mc_multiple_certificates_for_select($this->get_certificate_ids( $this->course_id ),$this->course_id,$this->certificate_user_id);
                // $this->write_log('$user_selected_certificate_id');
                // $this->write_log($user_selected_certificate_id);
                //var_dump((! empty($user_selected_certificate_id)));
                //var_dump($user_selected_certificate_id);
                if( ! empty($user_selected_certificate_id) && 0 != $user_selected_certificate_id ) {
                    // $this->write_log('ld_profile_course_certificate - case 02');
                    $cert_query_args['cert-nonce'] = wp_create_nonce($this->course_id . $this->certificate_user_id . $this->certificate_user_id);
                    $cert_query_args['course_id'] = $this->course_id;
                    $url = add_query_arg($cert_query_args, get_permalink(intval($user_selected_certificate_id)));
                                    
                     $certificate_link_html ="";
                    if(!empty(get_option('ld_mc_general_multi_certificate_on_course')))
                    {
                        foreach($us as $u) 
                        {
                    
                         $certificate_link_html .= '<a class="ld-certificate-link" target="_blank" href="' . $u['url'] . '" ><span class="ld-icon ld-icon-certificate"></span></span></a>'; 
                    
                        }
                    }
                    
                    else 
                    {
                        $certificate_link_html = '<a class="ld-certificate-link" target="_blank" href="' . esc_url($url) . '" ><span class="ld-icon ld-icon-certificate"></span></span></a>';
                    }
                    
                    
                    
                    
                    
                    
                    $bubble = $certificate_link_html . $bubble;
                }else{
                    if(!empty(get_option('ld_mc_general_multi_certificate_on_course')))
                    {
                        foreach($us as $u) 
                        {
                    
                         $certificate_link_html .= '<a class="ld-certificate-link" target="_blank" href="' . $u['url'] . '" ><span class="ld-icon ld-icon-certificate"></span></span></a>'; 
                    
                        }
                    }
                     $bubble = $certificate_link_html . $bubble;
                }
            }else{
                // $this->write_log('ld_profile_course_certificate - case 04');
            }
            return $bubble;
        }




        public function show_quizzes_attempt_certificate_icons($data,$quiz_attempt,$quiz_list_columns){
                
        
            $course_class_obj = new \LDMC\FrontEnd\Themes\LD30\Course();
            $quiz_settings         = learndash_get_setting( $quiz_attempt['post']->ID );
            $certificate_post_id   = $quiz_settings['certificate'];

            $certificates = array();
            $user_id = get_current_user_id();
            if (is_array($certificate_post_id)) {
                               
                if ( $course_class_obj->allowed_multiple_certificates($quiz_attempt['post']->ID) ) {
                    foreach ( $certificate_post_id as $key => $certificate_id) {
                        $certificate_post_data = get_post( $certificate_id );
                        $certificate_data      = learndash_certificate_details( $quiz_attempt['post']->ID, $user_id );
                        $cert_query_args['cert-nonce'] = wp_create_nonce($quiz_attempt['post']->ID . $user_id . $user_id);
                        $cert_query_args['quiz'] = $quiz_attempt['post']->ID;
                        $certificate_data['certificateLink'] = add_query_arg($cert_query_args, get_permalink(intval($certificate_id)));

                        // Verify and Share addon Compatibility
                        if (class_exists('\LD_CVSS\Classes\Certificate')) {

                            $certificate =  new \LD_CVSS\Classes\Certificate( $certificate_id, $quiz_attempt['post']->ID, $user_id );
                            $certificate_data['certificateLink']  = $certificate->get_public_url();
                        }
                        // Verify and Share addon Compatibility


                        if ( ! empty( $certificate_data['certificateLink'] ) && !( $certificate_data['certificateLink'] == 'javascript:;' || $certificate_data['certificateLink'] == 'javascript:void(0)' ) && $certificate_data['certificate_threshold'] <= $quiz_attempt['percentage'] / 100 ) {
                             
                                
                                $certificates[]        = $certificate_data['certificateLink'];
                        }
                    }
                }else{

                        $certificate_post_id = $course_class_obj->get_user_selected_certificate_id($quiz_attempt['post']->ID, $user_id);
                        $certificate_post_data = get_post( $certificate_post_id );
                        $certificate_data      = learndash_certificate_details( $quiz_attempt['post']->ID, $user_id );

                        // var_dump($certificate_post_id);

                        if ( ! empty( $certificate_post_id )  && $certificate_data['certificate_threshold'] <= $quiz_attempt['percentage'] / 100 ) {
                            

                            $cert_query_args['cert-nonce'] = wp_create_nonce($quiz_attempt['post']->ID .$user_id . $user_id);
                            $cert_query_args['quiz'] = $quiz_attempt['post']->ID;
                            $url = add_query_arg($cert_query_args, get_permalink(intval($certificate_post_id)));

                            // Verify and Share addon Compatibility
                            if (class_exists('\LD_CVSS\Classes\Certificate')) {

                                $certificate =  new \LD_CVSS\Classes\Certificate( $certificate_post_id, $quiz_attempt['post']->ID, $user_id );
                                $certificate_data['certificateLink']  = $certificate->get_public_url();
                            }
                            // Verify and Share addon Compatibility
                        
                            $certificates[]        = $certificate_data['certificateLink'];

                        }
                }
                }else{
                    $certificate_post_data = get_post( $certificate_post_id );
                    $certificate_data      = learndash_certificate_details( $quiz_attempt['post']->ID, $user_id );
                    if ( ! empty( $certificate_data['certificateLink'] ) && !( $certificate_data['certificateLink'] == 'javascript:;' || $certificate_data['certificateLink'] == 'javascript:void(0)' ) && $certificate_data['certificate_threshold'] <= $quiz_attempt['percentage'] / 100 ) {

                        // Verify and Share addon Compatibility
                        if (class_exists('\LD_CVSS\Classes\Certificate')) {

                            $certificate =  new \LD_CVSS\Classes\Certificate( $certificate_post_id, $quiz_attempt['post']->ID, $user_id );
                            $certificate_data['certificateLink']  = $certificate->get_public_url();
                        }
                        // Verify and Share addon Compatibility
                        
                        $certificates[]        = $certificate_data['certificateLink'];
                    }
            }



            $certificate_links = '';
            if ($certificates) {
                foreach ($certificates as $key => $certificate_link) {
                    $certificate_links .= '<a class="ld-certificate-link" href="' . $certificate_link . '" target="_new" aria-label="' . __( 'Certificate', 'learndash' ) . '"><span class="ld-icon ld-icon-certificate"></span></a>';
                }
            }

            $data['certificate']['content'] = $certificate_links;

            return $data;
        }

    }
}