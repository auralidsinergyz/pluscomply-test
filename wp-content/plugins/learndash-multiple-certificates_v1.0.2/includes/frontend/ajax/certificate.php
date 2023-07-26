<?php
namespace LDMC\FrontEnd\Ajax;

if ( ! class_exists( '\LDMC\FrontEnd\Ajax\Certificate' ) ) {
	class Certificate {

		/**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
		use \LDMC\Traits\Helpers;

		public function __construct() {
			/**
			 * Ajax JS  Request's Client Setup
			 */
			add_action( 'wp_enqueue_scripts', array( $this, 'ajax_js_client_setup' ), 10, 1 );
			/**
			 * Ajax PHP Post Request's Handler Setup
			 */

            add_action( 'wp_ajax_ld_mc_get_certificates', array( $this, 'ld_mc_get_certificates'), 10 );
		    add_action( 'wp_ajax_nopriv_ld_mc_get_certificates', array( $this, 'ld_mc_get_certificates'), 10 );



            // multiple certificate

            add_action( 'wp_ajax_ld_mc_get_multiple_certificates', array( $this, 'ld_mc_get_multiple_certificates'), 10 );
            add_action( 'wp_ajax_nopriv_ld_mc_get_multiple_certificates', array( $this, 'ld_mc_get_multiple_certificates'), 10 );


            // multiple certificate

            

		    add_action( 'wp_ajax_ld_mc_set_certificate_to_user_meta', array( $this, 'ld_mc_set_certificate_to_user_meta'), 10 );
		    add_action( 'wp_ajax_nopriv_ld_mc_set_certificate_to_user_meta', array( $this, 'ld_mc_set_certificate_to_user_meta'), 10 );

            add_action( 'ld_mc_create_certificate_pdf', array($this, 'ld_mc_create_certificate_pdf_for_group_course_quiz'), 10, 3 );
		   
 		    add_action('wp_ajax_ld_mc_send_certificate_email_for_group_course_quiz', array($this, 'ld_get_email_var'), 10);
            add_action('wp_ajax_nopriv_ld_mc_send_certificate_email_for_group_course_quiz', array($this, 'ld_get_email_var'), 10);
            
            add_action( 'learndash_certification_content_write_cell_after', array( $this, 'ld_download_pdf' ), 10, 2 );
//            add_filter( 'learndash_certificate_builder_pdf_output_mode', array( $this, 'ld_certificate_builder_download_pdf' ) );
//            add_filter( 'learndash_certificate_builder_pdf_name', array( $this, 'ld_certificate_builder_download_file_name' ) );

        }

		public function ajax_js_client_setup() {
           if(!is_404()){
			wp_register_script( 'ld_mc_frontend_ajax_script', LD_MC_ASSETS_URL . 'frontend/js/ajax/ld-mc-course-certificate.js', array( 'jquery', 'ld_mc_swa2_script' ), time(), true );
            global $post;
            wp_localize_script(
				'ld_mc_frontend_ajax_script',
				'ld_mc_frontend',
				array(
                    'settings' => array(
                        'post_id' => $post->ID,
                        'post_type' => learndash_get_post_type_key($post->post_type),
                    ),
                    'strings' => array(
                        'select_certificate_button_text' => __('Select Certificate',LD_MC_TEXT_DOMAIN),
                        'download_certificate_button_text' => __('Download Certificate',LD_MC_TEXT_DOMAIN),
                        'print_certificate_button_text' => esc_html__( 'PRINT YOUR CERTIFICATE', 'learndash' ),
                        'swal_title'          => esc_html__( 'Select a Certificate', LD_MC_TEXT_DOMAIN ),
                        'swal_placeholder'    => esc_html__( 'Select a Certificate', LD_MC_TEXT_DOMAIN ),
                        'wdm_selected_certificate' => '',
                        'swal_title_for_multiple_download'          => esc_html__( 'Download Certificates', LD_MC_TEXT_DOMAIN ),
                        'swal_placeholder_for_multiple_download'    => esc_html__( 'Download Certificates', LD_MC_TEXT_DOMAIN ),
                        'wdm_selected_certificate' => '',
                    ),
                    'images' => array(
                        'swal_icon'           => LD_MC_ASSETS_URL . 'frontend/images/certifcate_2.png'
                    ),
                    'ajax' => array(
                        'certificates' => array(
                            'get' => array(
                                'url'      => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
                                'action'   => 'ld_mc_get_certificates',
                                'nonce'    => wp_create_nonce( 'ajax_ld_mc_get_certificates_nonce' ),
                            ),
                            'get_multiple' => array(
                                'url'      => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
                                'action'   => 'ld_mc_get_multiple_certificates',
                                'nonce'    => wp_create_nonce( 'ajax_lld_mc_get_multiple_certificates_nonce' ),
                            ),
                        ),
                        'user_meta' => array(
                            'set_certificate' => array(
                                'url'      => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
                                'action'   => 'ld_mc_set_certificate_to_user_meta',
                                'nonce'    => wp_create_nonce( 'ajax_ld_mc_set_certificate_to_user_meta_nonce' ),
                            ),
                        ),
						
						'user_metas' => array(
                            'send_certificate' => array(
                                'url'      => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
                                'action'   => 'ld_mc_send_certificate_email_for_group_course_quiz',
                                'nonce'    => wp_create_nonce( 'ajax_ld_mc_get_certificates_nonce' ),
                            ),
                        ),
                    ),
				),
			);
			wp_enqueue_script( 'ld_mc_frontend_ajax_script');
		}
		}





        // multiple certificate


        public function ld_mc_get_multiple_certificates() {
            if ( check_ajax_referer( 'ajax_lld_mc_get_multiple_certificates_nonce', 'security' ) ) {
                $current_user_id = get_current_user_id();
                $post_id = intval($_POST['post_id']);
                $ldmc_certificates = $this->get_certificate_ids( $post_id );
                $ldmc_certificates = $this->ld_mc_multiple_certificates_for_select( $ldmc_certificates,$post_id,$current_user_id);
                return wp_send_json_success( $ldmc_certificates );
            } else {
                return wp_send_json_error(
                    array(
                        'status_code' => 498,
                        'message'     => __( 'Security Token Verification is failed', LD_MC_TEXT_DOMAIN ),
                    )
                );
            }
        }



        // multiple certificate


       public function ld_get_email_var() 
	   {
           		$user_id = get_current_user_id();
		        $post_id = intval($_POST['post_id']);
				$certificate_id = intval($_POST['certificate_id']);
				
			    //return wp_send_json_success( array( 'user_id' => $user_id, 'post_id' => $post_id, 'certificate_id' => $certificate_id ) );
			return	$this->ld_mc_create_certificate_pdf_for_group_course_quiz( $user_id, $post_id, $certificate_id );
        }



        public function ld_mc_get_certificates() {
            if ( check_ajax_referer( 'ajax_ld_mc_get_certificates_nonce', 'security' ) ) {
				$current_user_id = get_current_user_id();
		        $post_id = intval($_POST['post_id']);
		        $ldmc_certificates = $this->get_certificate_ids( $post_id );
		        $ldmc_certificates = $this->ld_mc_certificates_for_select( $ldmc_certificates );
                return wp_send_json_success( $ldmc_certificates );
			} else {
				return wp_send_json_error(
					array(
						'status_code' => 498,
						'message'     => __( 'Security Token Verification is failed', LD_MC_TEXT_DOMAIN ),
					)
				);
			}
        }

        public function ld_mc_create_certificate_pdf_for_group_course_quiz( $user_id, $post_id, $certificate_id ){
            $_GET['user_id'] = $user_id;
            if( get_post_type($post_id) == learndash_get_post_type_slug('group') ){
                $_GET['group_id'] = $post_id;
            }elseif( get_post_type($post_id) == learndash_get_post_type_slug('course') ){
                $_GET['course_id'] = $post_id;
            }elseif( get_post_type($post_id) == learndash_get_post_type_slug('quiz') ){
                $_GET['quiz'] = $post_id;
                $_REQUEST['user_id'] = $user_id;
                $_REQUEST['quiz'] = $post_id;
            } 
            $_REQUEST['ld_mc_save_certificate_pdf'] = 'yes';
            $this->pdf_output( $certificate_id, $post_id, $user_id );

            $this->ld_mc_send_certificate_email_for_group_course_quiz( $user_id, $post_id,$certificate_id );
        }

        public function ld_mc_send_certificate_email_for_group_course_quiz( $user_id, $post_id, $certificate_id ){
            // $this->write_log('$mysubmission');
			$email_submitted = false;
			//$user_id = get_current_user_id();
            if( get_post_type($post_id) == learndash_get_post_type_slug('group') ){
                $email_submitted = $this->send_group_certificate_email( $user_id, $post_id, $certificate_id );
            }elseif( get_post_type($post_id) == learndash_get_post_type_slug('course') ){
                $email_submitted = $this->send_course_certificate_email( $user_id, $post_id, $certificate_id );
            }elseif( get_post_type($post_id) == learndash_get_post_type_slug('quiz') ){
                $email_submitted = $this->send_quiz_certificate_email( $user_id, $post_id, $certificate_id );
            }
            // $this->write_log('$email_submitted');
            // $this->write_log($email_submitted);
            return $email_submitted;
        }

        public function ld_mc_set_certificate_to_user_meta(){
            if ( check_ajax_referer( 'ajax_ld_mc_set_certificate_to_user_meta_nonce', 'security' ) ) {
				$user_id = get_current_user_id();
                $certificate_id = $_POST['certificate_id'];
                $post_id = $_POST['post_id'];
                $user_meta_updated = update_user_meta($user_id,'ld_mc_get_selected_certificate_'.$post_id,$certificate_id);
                $certificate_link = 'javascript:;';

                if( $user_meta_updated ){
                    if( get_post_type($post_id) == learndash_get_post_type_slug('group') ){
                        do_action('ld_mc_create_certificate_pdf',  $user_id, $post_id, $certificate_id );
                    }elseif( get_post_type($post_id) == learndash_get_post_type_slug('course') ){
                        do_action('ld_mc_create_certificate_pdf',  $user_id, $post_id, $certificate_id );
                    }elseif( get_post_type($post_id) == learndash_get_post_type_slug('quiz') ){
                        do_action('ld_mc_create_certificate_pdf',  $user_id, $post_id, $certificate_id );
                        $certificate_link = $this->ld_mc_get_quiz_certificate_link( $user_id, $post_id, $certificate_id);
                    }
                }

                return wp_send_json_success( array( 'user_meta_updated' => $user_meta_updated, 'certificate_link' => $certificate_link ) );
			} else {
				return wp_send_json_error(
					array(
						'status_code' => 498,
						'message'     => __( 'Security Token Verification failed', LD_MC_TEXT_DOMAIN ),
					)
				);
			}
        }



        
        /**
         * Download PDF generated by LD.
         *
         * @param object $pdf TCPDF.
         * @param array $cert_args Certificate args.
         *
         * PDF output mode:
         *
         * I: send the file inline to the browser (default).
         * D: send to the browser and force a file download with the name given by name.
         * F: save to a local server file with the name given by name.
         * S: return the document as a string (name is ignored).
         * FI: equivalent to F + I option
         * FD: equivalent to F + D option
         * E: return the document as base64 mime multi-part email attachment (RFC 2045)
         *
         * @return void
         */
        public function ld_download_pdf( $pdf, $cert_args ) {
            // $this->write_log('ld_download_pdf');
            // $this->write_log('$cert_args');
            // $this->write_log($cert_args);

            // $this->write_log('$_REQUEST');
            // $this->write_log( $_REQUEST );

            // $this->write_log('$_GET');
            // $this->write_log( $_GET );

            if( isset($_REQUEST['ld_mc_save_certificate_pdf']) && 'yes' == $_REQUEST['ld_mc_save_certificate_pdf'] ){
//            if ( URL::get_pdf_action() == URL::PDF_ACTION_DOWNLOAD ) {
    $pdf_file_name = $this->get_pdf_download_file_name( $cert_args['user_id'],$cert_args['post_id'], $cert_args['cert_id'] );
    //                $pdf_file_name = ! empty ( $pdf_file_name ) ? $pdf_file_name : $cert_args['pdf_title'];
    //
    //                $pdf->Output( $pdf_file_name . '.pdf', 'D' );
    //                    $pdf->Output( LD_MC_PATH_CERTIFICATES.'/'.$pdf_file_name . '.pdf', 'F' );
                        $pdf->Output( LD_MC_PATH_CERTIFICATES.'/'.$pdf_file_name, 'F' );
                        // do_action('ld_mc_send_certificate_email', $cert_args['user_id'],$cert_args['post_id'], $cert_args['cert_id'] );
    //                exit;
                return;
    //            }
            }
        }

        /**
         * Download PDF generated by LD Certificate Builder.
         *
         * @param string $pdf_mode PDF mode.
         *
         * I: send the file inline to the browser (default).
         * D: send to the browser and force a file download with the name given by name.
         * F: save to a local server file with the name given by name.
         * S: return the document as a string (name is ignored).
         * FI: equivalent to F + I option
         * FD: equivalent to F + D option
         * E: return the document as base64 mime multi-part email attachment (RFC 2045)
         *
         *
         * @return string Changed PDF mode.
         */
        // public function ld_certificate_builder_download_pdf( $pdf_mode ) {
        //     if ( URL::get_pdf_action() == URL::PDF_ACTION_DOWNLOAD ) {
        //         $pdf_mode = 'D';
        //     }

        //     return $pdf_mode;
        // }

        /**
         * Change PDF file name generated by LD Certificate Builder.
         *
         * @param string $file_name PDF file name.
         *
         * @return string Changed PDF file name.
         */
        // public function ld_certificate_builder_download_file_name( $file_name ) {
        //     if ( URL::get_pdf_action() == URL::PDF_ACTION_DOWNLOAD ) {
        //         $pdf_file_name = $this->certificate->get_pdf_download_file_name();

        //         if ( ! empty ( $pdf_file_name ) ) {
        //             $file_name = $pdf_file_name;
        //         }
        //     }

        //     return $file_name;
        // }



	}

}