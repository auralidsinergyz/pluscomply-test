<?php
namespace LDMC\FrontEnd\Themes\LD30;

if ( ! class_exists( '\LDMC\FrontEnd\Themes\LD30\Course' ) ) {
	class Course {

		/**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
		use \LDMC\Traits\Helpers;

        protected $is_certificate_alert = false;

        public function __construct( ) {
            /**
             * Add Custom CSS Class to the Course Certificate Download Button
             */
            add_filter( 'ld-alert-class', array( $this, 'add_custom_class_to_course_certificate_button'), 10, 3 );
            /**
             * Update Group Certificate Button Attribute
             */
            add_filter('ld_mc_course_certificate_button_atts', array($this, 'update_course_certificate_button_atts'), 10, 3);
            /**
             * Update Group Certificate Button HTML
             */
            // add_filter('learndash_certificate_html', array($this, 'update_course_certificate_button_html'), 10, 3);


            add_action('learndash-course-certificate-link-before',array($this, 'learndash_course_certificate_link'),10,2 );

        }


        public function learndash_course_certificate_link($course_id, $cert_user_id ){

            $learndash_post_settings = (array) learndash_get_setting( $course_id, null );

            $course_status = learndash_course_status( $course_id, $cert_user_id,true );

            $certificate_id = $this->get_user_selected_certificate_id($course_id, $cert_user_id);

            if ($certificate_id) {
                $label = __('Download Certificate',LD_MC_TEXT_DOMAIN);
                $cert_query_args['cert-nonce'] = wp_create_nonce($course_id . $cert_user_id . $cert_user_id);
                $cert_query_args['course_id'] = $course_id;
                $url = add_query_arg($cert_query_args, get_permalink(intval($certificate_id)));
            }else{
                $label = __('Select Certificate',LD_MC_TEXT_DOMAIN);
                $url= '#';
            }

            // multiple certificate
            if ($this->allowed_multiple_certificates($course_id)) {
                $label = __('Download Certificates',LD_MC_TEXT_DOMAIN);
                $url= '#';
            }

                     // multiple certificate

            
            if (  $course_status == 'completed' ) {


                    learndash_get_template_part(
                        'modules/alert.php',
                        array(
                            'type'    => 'success ld-alert-certificate',
                            'icon'    => 'certificate',
                            'message' => __( 'You\'ve earned a certificate!', 'learndash' ),
                            'button'  => array(
                                'url'    =>  $url,
                                'icon'   => 'download',
                                'label'  => __( $label , 'learndash' ),
                                'target' => '_new',
                            ),
                        ),
                        true
                    );


            }
            
        }




        public function add_custom_class_to_course_certificate_button( $class, $type, $icon ) {
            global $post;
            if( $post->post_type == learndash_get_post_type_slug('course') ){
                // $this->write_log('add_custom_class_to_course_certificate_button');
                $user_id = get_current_user_id();
                if ( strpos( $class, 'ld-alert-certificate' ) !== false ) {


                     // multiple certificate

                    if ($this->allowed_multiple_certificates($post->ID)) {
                         return $class = $class.' ldmc_download_multiple_certificates';
                    }

                     // multiple certificate


                    $user_selected_certificate_id = $this->get_user_selected_certificate_id($post->ID, $user_id);
                    // $this->write_log('$user_selected_certificate_id');
                    // $this->write_log($user_selected_certificate_id);
                    if ( 0 == $user_selected_certificate_id ) {
                        $class = $class.' ldmc_select_certificate';
                    }

                   
                }
            }
            return $class;
        }

        public function update_course_certificate_button_atts( $atts, $cert_button_html, $content ){
            if( isset($atts['course_id']) && ! empty($atts['course_id']) && 0 != $atts['course_id'] ) {
                // $this->write_log('update_course_certificate_button_atts');

                // var_dump($atts);
                $course_status = learndash_course_status($atts['course_id'], $atts['user_id'], true);
				$curse_certificates = learndash_get_setting( $atts['course_id'], 'certificate' );
                if ($course_status == 'completed' && ! empty($curse_certificates) ) {
                    // $this->write_log('update_course_certificate_button_atts - case 01');
                    $user_selected_certificate_id = $this->get_user_selected_certificate_id($atts['course_id'], $atts['user_id']);
                    if( 0 != $user_selected_certificate_id ){
                        $atts['label'] =  __('Download Certificate',LD_MC_TEXT_DOMAIN);
                        $cert_query_args['cert-nonce'] = wp_create_nonce($atts['course_id'] . $atts['user_id'] . $atts['user_id']);
                        $cert_query_args['course_id'] = $atts['course_id'];
                        $url = add_query_arg($cert_query_args, get_permalink(intval($user_selected_certificate_id)));
                        $atts['cert_url'] = $url;
                    }else{
                        $atts['label'] =  __('Select Certificate',LD_MC_TEXT_DOMAIN);
                        $atts['cert_url'] = 'javascript:;';
                    }

                    // multiple certificate

// 
                    // var_dump($this->allowed_multiple_certificates($atts['course_id']));
                    if ($this->allowed_multiple_certificates($atts['course_id'])) {
                        $atts['label'] =  __('Download Certificates',LD_MC_TEXT_DOMAIN);
                        $atts['cert_url'] = 'javascript:;';
                    }

                     // multiple certificate






                    // $atts['label'] = ($user_selected_certificate_id) ? 'Download Certificate' : 'Select Certificate';
//                    $cert_query_args['cert-nonce'] = wp_create_nonce($atts['course_id'] . $atts['user_id'] . $atts['user_id']);
//                    $cert_query_args['course_id'] = $atts['course_id'];
//                    $url = add_query_arg($cert_query_args, get_permalink(intval($user_selected_certificate_id)));
//                    $atts['cert_url'] = $url;
                } else {
                    // $this->write_log('update_course_certificate_button_atts - case 02');
                    $atts = [];
                }
                // $this->write_log('$atts');
                // $this->write_log($atts);
            }
            return $atts;
        }

        public function update_course_certificate_button_html( $cert_button_html, $atts, $content ){
            if( isset($atts['course_id']) && ! empty($atts['course_id']) && 0 != $atts['course_id'] ) {

                // $this->write_log('update_course_certificate_button_html');
                // $this->write_log('$cert_button_html');
                // $this->write_log($cert_button_html);
                // $this->write_log('$atts - before');
                // $this->write_log($atts);
                // $this->write_log('$content');
                // $this->write_log($content);


                $atts = apply_filters('ld_mc_course_certificate_button_atts', $atts, $cert_button_html, $content );


                // $this->write_log('$atts - after');
                // $this->write_log($atts);

                // var_dump('expression');

				if( ! isset($atts['cert_url']) ){
					return $cert_button_html;
				}

                if( ! empty($atts['cert_url']) && '#' != $atts['cert_url'] && 'javascript:;' != $atts['cert_url'] && ! empty($cert_button_html) ){
                    // $this->write_log('update_course_certificate_button_html - case 03');
                    return $cert_button_html;
                }else{
                    // $this->write_log('update_course_certificate_button_html - case 04');
                }

                if ( isset($atts['display_as']) && 'banner' === $atts['display_as'] ) {
                    $cert_button_html = learndash_get_template_part(
                        'modules/alert.php',
                        array(
                            'type'    => 'success ld-alert-certificate',
                            'icon'    => 'certificate',
                            'message' => __( 'You\'ve earned a certificate!', 'learndash' ),
                            'button'  => array(
                                'url'    => $atts['cert_url'],
                                'icon'   => 'download',
                                'label'  => __( $atts['label'] , 'learndash' ),
                                //'target' => '_blank', //'_new',
                            ),
                        ),
                        false
                    );
                } else {
                    $cert_button_html = '<a href="' . esc_url( $atts['cert_url'] ) . '"' .
                        ( ! empty( $atts['class'] ) ? ' class="' . esc_attr( $atts['class'] ) : '' ) . '"' .
                        ( ! empty( $atts['id'] ) ? ' id="' . esc_attr( $atts['id'] ) . '"' : '' ) .
                        '>';

                    if ( ! empty( $atts['label'] ) ) {
                        $cert_button_html .= do_shortcode( $atts['label'] );
                    }

                    $cert_button_html .= '</a>';
                }
            }
            return $cert_button_html;
        }



    }
}