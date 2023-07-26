<?php
namespace LDMC\FrontEnd\Themes\LD30;

if ( ! class_exists( '\LDMC\FrontEnd\Themes\LD30\Quiz' ) ) {
    class Quiz
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        public $quiz_id = 0;
        public $quiz_certificate_id = 0;
        public $quiz_certificate_post_id = 0;

        private $verification_page;

        public function __construct()
        {
            //            add_filter('ld_mc_download_certificate_button_atts', array($this, 'update_quiz_certificate_button_atts'), 10, 3);

            /**
             * Quiz Certificates BUtton
             */
            
            //add_filter( 'learndash_quiz_completed_result_settings', array( $this, 'certificate_quiz_completion_json' ), 11, 2 );
            add_filter('learndash_certificate_details_link', array($this, 'update_quiz_certificate_url'), 10, 4 );
            add_filter( 'ld_certificate_link_label', array( $this, 'update_quiz_certificate_button_label' ), 11, 3 );


            // $this->verification_page = \LD_CVSS\Classes\Verification_Page::get_instance();

            // add_filter( 'learndash_quiz_completed_result_settings', array( $this, 'certificate_quiz_completion_json' ), 11, 2 );

        }




        // public function certificate_quiz_completion_json( $quiz_result_settings, $quiz_data ) {
        //     $user_id    = get_current_user_id();
        //     $quiz_id    = $quiz_data['quiz'];
        //     // $cert_id    = ( int ) learndash_get_setting( $quiz_id, 'certificate' );

        //     $cert_id = $this->get_user_selected_certificate_id($quiz_id, $user_id);


        //     if (class_exists('\LD_CVSS\Classes\Verification_Page')) {

        //         // print_r($cert_id);

        //         if ( $cert_id ) {
        //             $certificate = new \LD_CVSS\Classes\Certificate( $cert_id, $quiz_id, $user_id );


        //             if ( $certificate->is_valid() ) {
        //                 if ( $this->verification_page->page_exists() ) {
        //                     $certificate_details    = learndash_certificate_details( $quiz_id, $user_id );
        //                     $certificate_url        = ! empty( $certificate_details['certificateLink'] ) ? $certificate_details['certificateLink'] : '';
        //                     if ( $certificate_url ) {
        //                         $quiz_result_settings['ld_cvss_social_buttons'] = \LD_CVSS\Classes\Social_Buttons::get( $certificate );
        //                         $quiz_result_settings[ 'ld_cvss_quiz_certificate_url_key' ] = $certificate_url;
        //                     }
        //                 }
        //             }
        //         }
                
        //     }

            

        //     return $quiz_result_settings;
        // }

        public function testing_debugging_script(){
            // $this->write_log('testing_debugging_script');
            $user_quizzes       = (array) get_user_meta( 2, '_sfwd-quizzes', true );
            // $this->write_log('$user_quizzes');
            // $this->write_log($user_quizzes);
        }

   


        public function update_quiz_certificate_button_label( $label, $user_id, $quiz_id ){
            $certificate_id = $this->get_user_selected_certificate_id($quiz_id, $user_id);
            $label = ($certificate_id) ? esc_html__( 'IMPRIME TU CERTIFICADO', 'learndash' ) : __('Select Certificate',LD_MC_TEXT_DOMAIN);

            // multiple certificate

            if ($this->allowed_multiple_certificates($quiz_id)) {
                $label = esc_html__( 'IMPRIME TUS CERTIFICADOS');
            }

             // multiple certificate

            return $label;
        }

        public function update_quiz_certificate_url( $certificate_link, $certificate_post, $quiz_id, $user_id ){
            // $this->write_log('update_quiz_certificate_url');

            // $this->write_log('$certificate_link');
            // $this->write_log($certificate_link);

            // $this->write_log('$certificate_post');
            // $this->write_log($certificate_post);

            // $this->write_log('$quiz_id');
            // $this->write_log($quiz_id);

            // $this->write_log('$user_id');
            // $this->write_log($user_id);


            $user_selected_certificate_id = $this->get_user_selected_certificate_id($quiz_id, $user_id);
            if( ! $user_selected_certificate_id ){
                // $this->write_log('$certificate_id - case 01');
                $certificate_link = 'javascript:;';
            }else{
                // $this->write_log('$certificate_id - case 02');

                if( ! empty($certificate_link) ){
                    $certificate_link_parsed =  wp_parse_url($certificate_link);
                    // $this->write_log('$certificate_link_parsed');
                    // $this->write_log($certificate_link_parsed);

                    wp_parse_str( $certificate_link_parsed['query'], $certificate_link_args );
                    // $this->write_log('$certificate_link_args');
                    // $this->write_log($certificate_link_args);

                    if( intval($certificate_post) == 1 ){
                        $certificate_link = get_permalink( $user_selected_certificate_id );
                        if ( ! empty( $certificate_link ) ) {
                            $cert_query_args = array(
                                'quiz' => $quiz_id,
                            );
                            // We add the user query string key/value if the viewing user is an admin. This
                            // allows the admin to view other user's certificated.
//                        if ( ( $cert_user_id != $view_user_id ) && ( ( learndash_is_admin_user() ) || ( learndash_is_group_leader_user() ) ) ) {
                            $cert_query_args['user'] = $user_id;
//                        }
                            $cert_query_args['cert-nonce'] = wp_create_nonce( $quiz_id . $user_id . $user_id );

                            if( isset($certificate_link_args['time']) && ! empty($certificate_link_args['time']) ){
                                $cert_query_args['time'] = $certificate_link_args['time'];
                            }
                            $certificate_link = add_query_arg( $cert_query_args, $certificate_link );
                        }
                    }

                }


//                $certificate_link = '#';
//                $quiz_attempts_count = learndash_get_user_quiz_attempts_count( $user_id, $quiz_id );
//                if( $quiz_attempts_count > 0 ){
//                    $quiz_attempts = learndash_get_user_quiz_attempts( $user_id, $quiz_id );
//                }
            }



            // multiple certificate

            if ($this->allowed_multiple_certificates($quiz_id)) {
                 $certificate_link = 'javascript:void(0)';
            }

             // multiple certificate



            return $certificate_link;
        }


        public function update_quiz_certificate_button_atts( $atts, $cert_button_html, $content ){
            if( isset($atts['quiz_id']) && ! empty($atts['quiz_id']) && 0 != $atts['quiz_id'] ) {
                // $this->write_log('update_quiz_certificate_button_atts');
                $user_quizzes       = (array) get_user_meta( 2, '_sfwd-quizzes', true );
                $quiz_user_meta_key = array_search($atts['quiz_id'], array_column($user_quizzes, 'quiz'));
                $quiz_user_data = $user_quizzes[$quiz_user_meta_key];
                $quiz_pass = $quiz_user_data['pass'];
                if ( $quiz_pass ) {
                    // $this->write_log('update_quiz_certificate_button_atts - case 01');
                    $certificate_id = $this->get_user_selected_certificate_id($atts['quiz_id'], $atts['user_id']);
                    $atts['label'] = ($certificate_id) ? __('Download Certificate',LD_MC_TEXT_DOMAIN) : __('Select Certificate',LD_MC_TEXT_DOMAIN);
//                    $cert_user_id = ! empty( $atts['user_id'] ) ? intval( $atts['user_id'] ) : get_current_user_id();
//                    if ( ( learndash_is_admin_user() ) || ( learndash_is_group_leader_user() ) ) {
//                        $view_user_id = get_current_user_id();
//                    } else {
//                        $view_user_id = $cert_user_id;
//                    }
                    $cert_query_args['cert-nonce'] = wp_create_nonce($atts['quiz_id'] . $atts['user_id'] . $atts['user_id']);
                    $cert_query_args['quiz'] = $atts['quiz_id'];
                    $url = add_query_arg($cert_query_args, get_permalink(intval($certificate_id)));
                    $atts['cert_url'] = $url;
                } else {
                    // $this->write_log('update_quiz_certificate_button_atts - case 02');
                    $atts = [];
                }
                // $this->write_log('$atts - updated - update_quiz_certificate_button_atts');
                // $this->write_log($atts);
            }
            return $atts;
        }


    }
}