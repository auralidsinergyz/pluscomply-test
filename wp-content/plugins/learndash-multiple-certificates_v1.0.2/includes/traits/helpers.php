<?php

namespace LDMC\Traits;

trait Helpers {

	public function dd( array $array ) {
		echo '<pre>';
		print_r( $array );
		echo '</pre>';
	}

	public function write_log( $log ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}

    public function get_license_manager(){
        return \LDMC\License\LicenseManager::get_instance();
    }

    public function get_plugin_updater(){
        return new \LDMC\License\PluginUpdater();
    }


	public function get_certificate_ids( $group_id_or_course_id_or_quiz_id ) {
        // $this->write_log('get_certificate_ids');
        // $this->write_log('$group_id_or_course_id_or_quiz_id');
        // $this->write_log($group_id_or_course_id_or_quiz_id);
//        $ldmc_multiple_certificate = learndash_get_setting( $group_id_or_course_id_or_quiz_id, 'ldmc_multiple_certificate' );
        $ldmc_multiple_certificate = learndash_get_setting( $group_id_or_course_id_or_quiz_id, 'certificate' );
        // $this->write_log('$ldmc_multiple_certificate');
        // $this->write_log($ldmc_multiple_certificate);
		$certificate_ids = array();
		if ( ! empty($ldmc_multiple_certificate) && is_array($ldmc_multiple_certificate) && count($ldmc_multiple_certificate) > 0 ) {
            $certificate_ids = $ldmc_multiple_certificate;
        }else{
            $certificate_ids[] = $ldmc_multiple_certificate;
        }
        // $this->write_log('$certificate_ids');
        // $this->write_log($certificate_ids);
		return $certificate_ids;
	}

		/**
	 * Returns a product ID if we want user to purchase the product.
	 *
	 * @static
	 * @param  integer $course_id    Course ID.
	 * @param  integer $user_id      User ID.
	 * @return integer Product ID.
	 */
	public function get_user_selected_certificate_id( $group_id_or_course_id_quiz_id = 0, $user_id = 0 ) {
		$selected_certificate_id = 0;
        $certificates_ids = $this->get_certificate_ids( $group_id_or_course_id_quiz_id );
        if ( 0 != $group_id_or_course_id_quiz_id || 0 !=  $user_id ) {
            if( is_array($certificates_ids) ){
                if( count($certificates_ids) == 1 ){
                    $selected_certificate_id = $certificates_ids[0];
                }elseif( count($certificates_ids) > 1){
                    $user_selected_certificate_id = get_user_meta($user_id,'ld_mc_get_selected_certificate_'.$group_id_or_course_id_quiz_id,true);
                    $selected_certificate_id = ( ! empty($user_selected_certificate_id) && 0 != $user_selected_certificate_id ) ? $user_selected_certificate_id : 0;
                }
            }else{
                $selected_certificate_id = $certificates_ids;
            }
		}
		return $selected_certificate_id;
	}


	/**
	 * A function to check product purchase status.
	 *
	 * @static
	 * @param  integer $product_id Certificate product ID.
	 * @param  integer $user_id    User ID.
	 * @return boolean             True if purchased.
	 */
	// public function is_certificate_selected( $group_id_or_course_id_quiz_id = 0, $user_id = 0 ) {
    //     $this->write_log('is_certificate_selected');
    //     $certificate_id = 0;
	// 	if ( 0 != $group_id_or_course_id_quiz_id || 0 !=  $user_id ) {
    //         $certificate_id = get_user_meta($user_id,'ld_mc_get_selected_certificate_'.$group_id_or_course_id_quiz_id,true);
    //         $certificate_id = ( ! empty($certificate_id) )? $certificate_id: 0;
	// 	}
    //     $this->write_log('$certificate_id');
    //     $this->write_log($certificate_id);
	// 	return $certificate_id;
	// }


    /**
     * Process array for select options.
     *
     * @param  mixed $wdm_schools
     * @return void
     */
    public function ld_mc_certificates_for_select( $ldmc_certificates ) {
        // $this->write_log('ld_mc_certificates_for_select');
        $ldmc_temp = array();
        foreach ( $ldmc_certificates as $certificate_id ) {
            $ldmc_temp[ $certificate_id ] = get_the_title( $certificate_id );
        }
        // $this->write_log('$ldmc_temp');
        // $this->write_log($ldmc_temp);
        return $ldmc_temp;
    }




    public function learndash_certificate_display() {
        // $this->write_log('learndash_certificate_display');
        if ( is_singular( learndash_get_post_type_slug( 'certificate' ) ) ) {
            if ( ( isset( $_GET['cert-nonce'] ) ) && ( ! empty( $_GET['cert-nonce'] ) ) ) {
                $certificate_post = get_post( get_the_ID() );

                // The viewing user ID.
                $view_user_id = get_current_user_id();

                /**
                 * Then determined for whom the certificate if for. A
                 * Group Leader or admin user can view other users.
                 */
                if ( ( ( learndash_is_admin_user() ) || ( learndash_is_group_leader_user() ) ) && ( ( isset( $_GET['user'] ) ) && ( ! empty( $_GET['user'] ) ) ) ) {
                    $cert_user_id = absint( $_GET['user'] );
                } else {
                    $cert_user_id = get_current_user_id();
                }

                if ( ( isset( $_GET['group_id'] ) ) && ( ! empty( $_GET['group_id'] ) ) ) {
                    $group_id = absint( $_GET['group_id'] );
                    if ( wp_verify_nonce( esc_attr( $_GET['cert-nonce'] ), $group_id . $cert_user_id . $view_user_id ) ) {
                        $group_post = get_post( $group_id );
                        if ( ( $group_post ) && ( is_a( $group_post, 'WP_Post' ) ) && ( learndash_get_post_type_slug( 'group' ) === $group_post->post_type ) ) {
                            $group_certificate_post_id = learndash_get_setting( $group_post->ID, 'certificate' );
                            if ( absint( $group_certificate_post_id ) === absint( $certificate_post->ID ) OR ( is_array($group_certificate_post_id) && count($group_certificate_post_id) > 0 ) ) {
                                $group_status = learndash_get_user_group_status( $group_id, $cert_user_id, true );
                                if ( 'completed' === $group_status ) {
                                    if ( ( ( learndash_is_admin_user() ) || ( learndash_is_group_leader_user() ) ) && ( intval( $cert_user_id ) !== intval( $view_user_id ) ) ) {
                                        wp_set_current_user( $cert_user_id );
                                    }

                                    if ( has_action( 'learndash_tcpdf_init' ) ) {
                                        /**
                                         * Fires on tcpdf initialization.
                                         *
                                         * @since 3.2.0
                                         *
                                         * @param int $cert_id      Certificate Post ID.
                                         * @param int $cert_user_id User ID.
                                         * @param int post-id       Related Course, Quiz post ID.
                                         */
                                        do_action(
                                            'learndash_tcpdf_init',
                                            array(
                                                'cert_id' => $certificate_post->ID,
                                                'user_id' => $cert_user_id,
                                                'post_id' => $group_id,
                                            )
                                        );
                                    } else {
                                        require_once LEARNDASH_LMS_PLUGIN_DIR . 'includes/ld-convert-post-pdf.php';
                                        learndash_certificate_post_shortcode(
                                            array(
                                                'cert_id' => $certificate_post->ID,
                                                'user_id' => $cert_user_id,
                                                'post_id' => $group_id,
                                            )
                                        );
                                    }
                                }
                            }
                        }
                    }
                } elseif ( ( isset( $_GET['course_id'] ) ) && ( ! empty( $_GET['course_id'] ) ) ) {
                    // $this->write_log('learndash_certificate_display - course certificate');
                    // $this->write_log('$_GET');
                    // $this->write_log( $_GET );

                    $course_id = absint( $_GET['course_id'] );

//                    $learndash_post_settings = (array) learndash_get_setting( $course_id, null );

                    if ( wp_verify_nonce( esc_attr( $_GET['cert-nonce'] ), $course_id . $cert_user_id . $view_user_id ) ) {
                        // $this->write_log('learndash_certificate_display - course certificate - case 01');

                        $course_post = get_post( $course_id );
                        if ( ( $course_post ) && ( is_a( $course_post, 'WP_Post' ) ) && ( learndash_get_post_type_slug( 'course' ) === $course_post->post_type ) ) {
                            // $this->write_log('learndash_certificate_display - course certificate - case 02');
                            $course_certificate_post_id = learndash_get_setting( $course_post->ID, 'certificate' );

//                            $multiple_certificates = $learndash_post_settings['ldmc_multiple_certificate'];
//                            $multiple_certificates = $learndash_post_settings['certificate'];

                            if ( ( absint( $course_certificate_post_id ) === absint( $certificate_post->ID ) )  OR ( is_array($course_certificate_post_id) && count($course_certificate_post_id) > 0 ) ) {
                                // $this->write_log('learndash_certificate_display - course certificate - case 03');
                                $course_status = learndash_course_status( $course_id, $cert_user_id, true );
                                if ( 'completed' === $course_status ) {
                                    // $this->write_log('learndash_certificate_display - course certificate - case 04');
                                    if ( ( ( learndash_is_admin_user() ) || ( learndash_is_group_leader_user() ) ) && ( intval( $cert_user_id ) !== intval( $view_user_id ) ) ) {
                                        // $this->write_log('learndash_certificate_display - course certificate - case 05');
                                        wp_set_current_user( $cert_user_id );
                                    }

                                    /** This filter is documented in includes/class-ld-cpt-instance.php */
                                    if ( has_action( 'learndash_tcpdf_init' ) ) {
                                        do_action(
                                            'learndash_tcpdf_init',
                                            array(
                                                'cert_id' => $certificate_post->ID,
                                                'user_id' => $cert_user_id,
                                                'post_id' => $course_id,
                                            )
                                        );
                                    } else {
                                        require_once LEARNDASH_LMS_PLUGIN_DIR . 'includes/ld-convert-post-pdf.php';
                                        learndash_certificate_post_shortcode(
                                            array(
                                                'cert_id' => $certificate_post->ID,
                                                'user_id' => $cert_user_id,
                                                'post_id' => $course_id,
                                            )
                                        );
                                    }
                                    die();
                                }
                            }
                        }
                    }
                } elseif ( ( isset( $_GET['quiz'] ) ) && ( ! empty( $_GET['quiz'] ) ) ) {
                    // $this->write_log('learndash_certificate_display - quiz certificate');
                    // $this->write_log('$_GET');
                    // $this->write_log( $_GET );

                    $quiz_id = intval( $_GET['quiz'] );
                    if ( wp_verify_nonce( $_GET['cert-nonce'], $quiz_id . $cert_user_id . $view_user_id ) ) {
                        // $this->write_log('learndash_certificate_display - quiz certificate - case 01');
                        $quiz_post = get_post( $quiz_id );
                        if ( ( $quiz_post ) && ( is_a( $quiz_post, 'WP_Post' ) ) && ( learndash_get_post_type_slug( 'quiz' ) === $quiz_post->post_type ) ) {
                            // $this->write_log('learndash_certificate_display - quiz certificate - case 02');
                            $quiz_certificate_post_id = learndash_get_setting( $quiz_post->ID, 'certificate' );
//                            $multiple_certificates = $learndash_post_settings['certificate'];
                            if ( absint( $quiz_certificate_post_id ) === absint( $certificate_post->ID ) OR ( is_array($quiz_certificate_post_id) && count($quiz_certificate_post_id) > 0 ) ) {
                                // $this->write_log('learndash_certificate_display - quiz certificate - case 03');
                                $time               = isset( $_GET['time'] ) ? intval( $_GET['time'] ) : -1;
                                $quizinfo           = get_user_meta( $cert_user_id, '_sfwd-quizzes', true );
                                $selected_quizinfo  = null;
                                $selected_quizinfo2 = null;

                                if ( ! empty( $quizinfo ) ) {
                                    foreach ( $quizinfo as $quiz_i ) {

                                        if ( ( ( isset( $quiz_i['time'] ) ) && intval( $quiz_i['time'] ) == intval( $time ) ) && ( intval( $quiz_i['quiz'] ) === intval( $quiz_id ) ) ) {
                                            $selected_quizinfo = $quiz_i;
                                            break;
                                        }

                                        if ( intval( $quiz_i['quiz'] ) === intval( $quiz_id ) ) {
                                            $selected_quizinfo2 = $quiz_i;
                                        }
                                    }
                                }

                                $selected_quizinfo = empty( $selected_quizinfo ) ? $selected_quizinfo2 : $selected_quizinfo;
                                if ( ! empty( $selected_quizinfo ) ) {
                                    // $this->write_log('learndash_certificate_display - quiz certificate - case 04');
                                    $certificate_threshold = learndash_get_setting( $selected_quizinfo['quiz'], 'threshold' );

                                    if ( ( isset( $selected_quizinfo['percentage'] ) && $selected_quizinfo['percentage'] >= $certificate_threshold * 100 ) || ( isset( $selected_quizinfo['count'] ) && $selected_quizinfo['score'] / $selected_quizinfo['count'] >= $certificate_threshold ) ) {
                                        // $this->write_log('learndash_certificate_display - quiz certificate - case 05');
                                        if ( ( ( learndash_is_admin_user() ) || ( learndash_is_group_leader_user() ) ) && ( $cert_user_id !== $view_user_id ) ) {
                                            // $this->write_log('learndash_certificate_display - quiz certificate - case 06');
                                            wp_set_current_user( $cert_user_id );
                                        }

                                        if ( has_action( 'learndash_tcpdf_init' ) ) {
                                            /** This filter is documented in includes/class-ld-cpt-instance.php */
                                            do_action(
                                                'learndash_tcpdf_init',
                                                array(
                                                    'cert_id' => $certificate_post->ID,
                                                    'user_id' => $cert_user_id,
                                                    'post_id' => $selected_quizinfo['quiz'],
                                                )
                                            );
                                        } else {
                                            /**
                                             * Include library to generate PDF
                                             */
                                            require_once LEARNDASH_LMS_PLUGIN_DIR . 'includes/ld-convert-post-pdf.php';
                                            learndash_certificate_post_shortcode(
                                                array(
                                                    'cert_id' => $certificate_post->ID,
                                                    'user_id' => $cert_user_id,
                                                    'post_id' => $selected_quizinfo['quiz'],
                                                )
                                            );
                                        }
                                        die();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            /**
             * Action to allow custom handling of when a user cannot view a certificate.
             *
             * @since 3.2.3
             */
            do_action( 'learndash_certificate_disallowed' );

            // If here we display the error and exit;
            esc_html_e( 'Access to certificate page is disallowed.', 'learndash' );
            die();

        }
    }


    function build_settings_select2_lib_ajax_fetch_json( $field_settings = array() ) {
        $settings_element_json   = wp_json_encode( $field_settings['settings_element'], JSON_FORCE_OBJECT );
        $field_settings['nonce'] = wp_create_nonce( $settings_element_json );
        return htmlspecialchars( wp_json_encode( $field_settings, JSON_FORCE_OBJECT ) );
    }

    public function ld_mc_get_quiz_certificate_link( $user_id, $quiz_id, $certificate_id ){
        $certificate_link = get_permalink( $certificate_id );
        if ( ! empty( $certificate_link ) ) {
            $cert_query_args = array(
                'user' => $user_id,
                'quiz' => $quiz_id,
                'cert-nonce' => wp_create_nonce( $quiz_id . $user_id . $user_id )
            );
            $certificate_link = add_query_arg( $cert_query_args, $certificate_link );
        }
        return $certificate_link;
    }


    /**
     * Output PDF
     *
     * @param int $cert_id Certificate ID.
     * @param int $source_id Source ID.
     * @param int $user_id User ID.
     *
     * @return void
     */
    public function pdf_output( $cert_id, $source_id, $user_id ) {
        header('Content-Disposition: attachment; filename="myfile.pdf"');
        header("Content-Type: application/pdf");

        ob_start();

        if ( has_action( 'learndash_tcpdf_init' ) ) {
            do_action(
                'learndash_tcpdf_init',
                array(
                    'cert_id' => $cert_id,
                    'user_id' => $user_id,
                    'post_id' => $source_id
                )
            );
        } else {
            require_once $this->ld_pdf_converter_path();

            learndash_certificate_post_shortcode(
                array(
                    'cert_id' => $cert_id,
                    'user_id' => $user_id,
                    'post_id' => $source_id
                )
            );
        }

        $pdf_creation_response = ob_get_clean();
        // $this->write_log('$pdf_creation_response');
        // $this->write_log($pdf_creation_response);

        do_action('ld_mc_send_certificate_email', $user_id, $source_id, $cert_id );

    }

    /**
     * Get certificate PDF download file name.
     *
     * @return string Certificate PDF download file name.
     */
    public function get_pdf_download_file_name( $user_id, $post_id, $certificate_id) {
        return sanitize_file_name( sprintf( '%s_%s_%s.pdf', get_post($certificate_id)->post_title, get_post($post_id)->post_title, get_userdata($user_id)->display_name ) );
    }

    /**
     * Get path to LD PDF converter.
     *
     * @return void
     */
    public function ld_pdf_converter_path() {
        return LEARNDASH_LMS_PLUGIN_DIR . '/includes/ld-convert-post-pdf.php';
    }

    public  function wp_filesystem() {
        global $wp_filesystem;

        require_once ABSPATH . 'wp-admin/includes/file.php';

        if ( ! $wp_filesystem ) {
            WP_Filesystem();
        }

        return $wp_filesystem;
    }

    public function get_group_email_notification_subject(){
        // $this->write_log('get_group_email_notification_subject');
        $default_value = 'Congratulations! Completion Certificate';
        $group_email_notification_subject = ( ! empty(get_option('ld_mc_group_email_subject')) ) ? get_option('ld_mc_group_email_subject'): $default_value;
        // $this->write_log('$group_email_notification_subject');
        // $this->write_log($group_email_notification_subject);
        return $group_email_notification_subject;
    }

    public function get_group_email_notification_body(){
        $default_value = 'Hi [usermeta field="first_last_name"],'. "<br/><br/>";
        $default_value .= 'Congratulations!,'. "<br/><br/>";
        $default_value .=' You have been awarded with the certificate on the completion of your  '.learndash_get_custom_label_lower( 'group' ).'.'. "<br/><br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'group' ).' Details:</strong>'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'group' ).' Title:</strong> [groupinfo show="group_title" group_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'group' ).' URL:</strong> [groupinfo show="group_url" group_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'group' ).' Enrollment Date:</strong> [groupinfo show="enrolled_on" group_id="0" user_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'group' ).' Completion Date:</strong> [groupinfo show="completed_on" group_id="0" user_id="0"]'. "<br/><br/>";
        $default_value .='Regards'. "<br/>";
        $group_email_notification_body = ( ! empty(get_option('ld_mc_group_email_body')) ) ? get_option('ld_mc_group_email_body'): $default_value;
        return nl2br($group_email_notification_body);
    }

    public function send_group_certificate_email( $user_id, $post_id, $certificate_id){
        // $this->write_log('send_group_certificate_email');
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $status = get_option( 'ld_mc_group_email_status' );

        if( empty($status) || 'disabled' == $status ){
            return;
        }

        $subject = $this->get_group_email_notification_subject();
//        if ( ! empty($subject) ) {
//            $subject = str_replace('[affiliate_email]', $affiliate->user_email, $subject);
//        }

        $body = $this->get_group_email_notification_body();
        $body = do_shortcode( html_entity_decode( stripslashes( $body ) ) );
//        if( ! empty($body) ) {
//            $body = str_replace('[affiliate_email]', $affiliate->user_email, $body);
//        }

        $to = get_userdata($user_id)->user_email;

        $pdf_download_file_name = $this->get_pdf_download_file_name( $user_id, $post_id, $certificate_id);
        $attachments = array(LD_MC_PATH_CERTIFICATES . '/'.$pdf_download_file_name);
        // $this->write_log('$to');
        // $this->write_log($to);
        // $this->write_log('$attachments');
        // $this->write_log($attachments);

        $result = wp_mail($to, $subject, $body, $headers, $attachments);
        // $this->write_log('$result');
        // $this->write_log($result);
        return $result;
    }

    public function get_course_email_notification_subject(){
        $default_value = 'Congratulations! Course Completion Certificate';
        $course_email_notification_subject = ( ! empty(get_option('ld_mc_course_email_subject')) ) ? get_option('ld_mc_course_email_subject'): $default_value;
        return $course_email_notification_subject;
    }

    public function get_course_email_notification_body(){
		// $this->write_log('get_course_email_notification_body');
        $default_value = 'Hi [usermeta field="first_last_name"],'. "<br/><br/>";
        $default_value .= 'Congratulations!,'. "<br/><br/>";
        $default_value .=' You have been awarded with the certificate on the completion of your '.learndash_get_custom_label_lower( 'course' ).'.'. "<br/><br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'course' ).' Details:</strong>'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'course' ).' Title:</strong> [courseinfo show="course_title" course_id="0" user_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'course' ).' URL:</strong> [courseinfo show="course_url" course_id="0" user_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'course' ).' Enrollment Date:</strong> [courseinfo show="enrolled_on" course_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'course' ).' Completion Date:</strong> [courseinfo show="completed_on" course_id="0"]'. "<br/><br/>";
        $default_value .='Regards'. "<br/>";
        $course_email_notification_body = ( ! empty(get_option('ld_mc_course_email_body')) ) ? get_option('ld_mc_course_email_body'): $default_value;
		// $this->write_log('$course_email_notification_body');
		// $this->write_log($course_email_notification_body);
	 //    $this->write_log('get_option - ld_mc_course_email_body');
	 //    $this->write_log(get_option('ld_mc_course_email_body'));
        return nl2br($course_email_notification_body);
    }

    public function send_course_certificate_email( $user_id, $post_id, $certificate_id){
        // $this->write_log('send_course_certificate_email');
        // $this->write_log('$_GET');
        // $this->write_log( $_GET );

        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $status = get_option('ld_mc_course_email_status');
        if( empty($status) || 'disabled' == $status ){
            return;
        }

        $subject = $this->get_course_email_notification_subject();
//        if ( ! empty($subject) ) {
//            $subject = str_replace('[affiliate_email]', $affiliate->user_email, $subject);
//        }

        $body = $this->get_course_email_notification_body();
//        $body = do_shortcode( html_entity_decode( stripslashes( $body ) ) );
        $body = do_shortcode( html_entity_decode( stripslashes( $body ) ) );
//        $body = htmlentities( $body );

        // $this->write_log('$body');
        // $this->write_log($body);

//        if( ! empty($body) ) {
//            $body = str_replace('[affiliate_email]', $affiliate->user_email, $body);
//        }


        $to = get_userdata($user_id)->user_email;

        $pdf_download_file_name = $this->get_pdf_download_file_name( $user_id, $post_id, $certificate_id);
        $attachments = array(LD_MC_PATH_CERTIFICATES . '/'.$pdf_download_file_name);

        // $this->write_log('$to');
        // $this->write_log($to);
        
        // $this->write_log('$attachments');
        // $this->write_log($attachments);

        $result = wp_mail($to, $subject, $body, $headers, $attachments);
        // $this->write_log('$result');
        // $this->write_log($result);
        return $result;
    }

    public function get_quiz_email_notification_subject(){
        $default_value = 'Congratulations! Quiz Completion Certificate';
        $quiz_email_notification_subject = ( ! empty(get_option('ld_mc_quiz_email_subject')) ) ? get_option('ld_mc_quiz_email_subject'): $default_value;
        return $quiz_email_notification_subject;
    }

    public function get_quiz_email_notification_body(){
        $default_value = 'Hi [usermeta field="first_last_name"],'. "<br/><br/>";
        $default_value .= 'Congratulations!,'. "<br/><br/>";
        $default_value .=' You have been awarded with the certificate on the completion of your '.learndash_get_custom_label_lower( 'quiz' ).'.'. "<br/><br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'quiz' ).' Details:</strong>'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'course' ).' Title:</strong> [quizinfo show="course_title" quiz="0" user_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'quiz' ).' Title:</strong> [quizinfo show="quiz_title" quiz="0" user_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'quiz' ).' Total Points:</strong> [quizinfo show="total_points" quiz="0" user_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'quiz' ).' Earned Points:</strong> [quizinfo show="points" quiz="0" user_id="0"]'. "<br/>";
        $default_value .=' <strong>'.learndash_get_custom_label( 'quiz' ).' Completion Time:</strong> [quizinfo show="timestamp" quiz="0" user_id="0"]'. "<br/><br/>";
        $default_value .='Regards'. "<br/>";
        $quiz_email_notification_body = ( ! empty(get_option('ld_mc_quiz_email_body')) ) ? get_option('ld_mc_quiz_email_body'): $default_value;
        return nl2br($quiz_email_notification_body);
    }

    public function send_quiz_certificate_email( $user_id, $post_id, $certificate_id){
        // $this->write_log('send_quiz_certificate_email');
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $status = get_option( 'ld_mc_quiz_email_status' );

        if( empty($status) || 'disabled' == $status ){
            return;
        }

        $subject = $this->get_quiz_email_notification_subject();
//        if ( ! empty($subject) ) {
//            $subject = str_replace('[affiliate_email]', $affiliate->user_email, $subject);
//        }

        $body = $this->get_quiz_email_notification_body();
        $body = do_shortcode( html_entity_decode( stripslashes( $body ) ) );
//        if( ! empty($body) ) {
//            $body = str_replace('[affiliate_email]', $affiliate->user_email, $body);
//        }

        $to = get_userdata($user_id)->user_email;

        $pdf_download_file_name = $this->get_pdf_download_file_name( $user_id, $post_id, $certificate_id);
        // $this->write_log('$pdf_download_file_name');
        // $this->write_log($pdf_download_file_name);
        // $this->write_log($to);
        // $this->write_log($subject);


        $attachments = array(LD_MC_PATH_CERTIFICATES . '/'.$pdf_download_file_name);
        // $this->write_log('$attachments');
        // $this->write_log($attachments);

        $result = wp_mail($to, $subject, $body, $headers, $attachments);
        // $this->write_log('$result');
        // $this->write_log($result);
        return $result;
    }


    public function get_user_course_certificate_id($user_id = 0, $course_id = 0)
    {
        $user_course_certificate_id = 0;
        if (learndash_course_completed($user_id, $course_id)) {
            $course_certificates = learndash_get_setting($course_id, 'certificate');
            if (!is_array($course_certificates) && intval($course_certificates) != 0) {
                $user_course_certificate_id = $course_certificates;
            } elseif (is_array($course_certificates)) {
                $user_selected_certificate_id = $this->get_user_selected_certificate_id($course_id, $user_id);
                if (0 != $user_selected_certificate_id) {
                    $user_course_certificate_id = $user_selected_certificate_id;
                }
            }
        }
        return $user_course_certificate_id;
    }

	/**
	 * End - New Functions
	 */


        // multiple certificate

    public function allowed_multiple_certificates( $post_id ){

            $allowed_multiple = false;

            if ( get_post_type($post_id)  == learndash_get_post_type_slug('quiz')) {
                $allowed_multi_certificate_on_quizzes = ( ! empty(get_option('ld_mc_general_multi_certificate_on_quizzes')) ) ? get_option('ld_mc_general_multi_certificate_on_quizzes'): '';
                if ($allowed_multi_certificate_on_quizzes) {
                     $allowed_multiple = true;
                }
            }else if(get_post_type($post_id)  == learndash_get_post_type_slug('course')){

                $allowed_multi_certificate_on_course = ( ! empty(get_option('ld_mc_general_multi_certificate_on_course')) ) ? get_option('ld_mc_general_multi_certificate_on_course'): '';
                if ($allowed_multi_certificate_on_course) {
                     $allowed_multiple = true;
                }

            }
            
           
           
            return $allowed_multiple;

    }





    public function ld_mc_multiple_certificates_for_select( $ldmc_certificates,$quiz_id,$user_id) {
        // $this->write_log('ld_mc_certificates_for_select');
        $ldmc_temp = array();

        $post_type = get_post_type($quiz_id);
        foreach ( $ldmc_certificates as $certificate_id ) {

            $cert_query_args['cert-nonce'] = wp_create_nonce($quiz_id . $user_id. $user_id);
            if($post_type == learndash_get_post_type_slug('quiz')){
                $cert_query_args['quiz'] = $quiz_id;
            }elseif($post_type == learndash_get_post_type_slug('course')){
                $cert_query_args['course_id'] = $quiz_id;
            }
            
            $url = add_query_arg($cert_query_args, get_permalink(intval($certificate_id)));
            
            $ldmc_temp[ $certificate_id ] = array('text'=> get_the_title( $certificate_id ) ,'url'=> $url );
        }
        // $this->write_log('$ldmc_temp');
        // $this->write_log($ldmc_temp);
        return $ldmc_temp;
    }




        // multiple certificate

		
}