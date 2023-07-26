<?php
/**
 * Plugin Name: LearnDash Multi Certificates
 * Plugin URI: https://wooninjas.com/downloads/learndash-multi-certificates
 * Description: This add-on allows admin to provide multiple optional certificates to the student that they should select specific one for downloading.
 * Version: 1.0.2
 * Requires at least: 5.1
 * Requires PHP: 7.2
 * Author: WooNinjas
 * Author URI: https://wooninjas.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: LD-MC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PSR-4 Composer Autoloader
 */
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

if( ! function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

$plugin_data        = get_plugin_data( __FILE__ );
$wp_upload_dir      = wp_get_upload_dir();


/* Define constants. */

// ! defined( 'LD_MC_NAME' )                      && define( 'LD_MC_NAME', $plugin_data['Name'] );
// ! defined( 'LD_MC_VERSION' )                   && define( 'LD_MC_VERSION', $plugin_data['Version'] );
// ! defined( 'LD_MC_BASE' )                      && define( 'LD_MC_BASE', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
// ! defined( 'LD_MC_TEXT_DOMAIN' )               && define( 'LD_MC_TEXT_DOMAIN', 'LD-MC' );
// // ! defined( 'LD_MC_ASSETS_SUFFIX' )             && define( 'LD_MC_ASSETS_SUFFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ? '' : '.min' ) );
// ! defined( 'LD_MC_FILE' )                      && define( 'LD_MC_FILE', __FILE__ );
// ! defined( 'LD_MC_URL' )                       && define( 'LD_MC_URL', plugins_url( '', LD_MC_FILE ) );
// ! defined( 'LD_MC_URL_ASSETS' )                && define( 'LD_MC_URL_ASSETS', LD_MC_URL . '/assets' );
// ! defined( 'LD_MC_URL_ASSETS_CSS' )            && define( 'LD_MC_URL_ASSETS_CSS', LD_MC_URL_ASSETS . '/css' );
// ! defined( 'LD_MC_URL_ASSETS_JS' )             && define( 'LD_MC_URL_ASSETS_JS', LD_MC_URL_ASSETS . '/js' );
// ! defined( 'LD_MC_URL_ASSETS_IMAGES' )         && define( 'LD_MC_URL_ASSETS_IMAGES', LD_MC_URL_ASSETS . '/images' );
// ! defined( 'LD_MC_PATH' )                      && define( 'LD_MC_PATH', dirname( LD_MC_FILE ) );
// ! defined( 'LD_MC_PATH_ASSETS' )               && define( 'LD_MC_PATH_ASSETS', LD_MC_PATH . '/assets' );
// ! defined( 'LD_MC_PATH_ASSETS_IMAGES' )        && define( 'LD_MC_PATH_ASSETS_IMAGES', LD_MC_PATH_ASSETS . '/images' );
// ! defined( 'LD_MC_PATH_INCLUDES' )             && define( 'LD_MC_PATH_INCLUDES', LD_MC_PATH . '/includes' );
// ! defined( 'LD_MC_PATH_TEMPLATES' )            && define( 'LD_MC_PATH_TEMPLATES', LD_MC_PATH . '/templates' );
// ! defined( 'LD_MC_WP_UPLOAD_DIR' )             && define( 'LD_MC_WP_UPLOAD_DIR', $wp_upload_dir['basedir'] );
// ! defined( 'LD_MC_PATH_CERTIFICATES' )         && define( 'LD_MC_PATH_CERTIFICATES', LD_MC_WP_UPLOAD_DIR . '/learndash-multiple-certificates' );



/**
 * Plugin Directories Paths
 */
$plugins = get_plugins();
$learndash_dir_path = '';
$learndash_plugin_basename = '';
$ldcvss_plugin_basename = '';
foreach( $plugins as $plugin_main_file_path =>  $plugin ){
    if( $plugin['Name'] == 'LearnDash LMS' ){
        $learndash_plugin_basename = $plugin_main_file_path;
		$plugin_dir_path = $plugin_main_file_path;
		$plugin_dir_path_arr = preg_split("/\//", $plugin_dir_path);
		$learndash_dir_path = $plugin_dir_path_arr[0];
    }
    if( $plugin['Name'] == $plugin_data['Name'] ){
        $ldcvss_plugin_basename =  $plugin_main_file_path;
    }
}
/*
Start - Plugin Global Constants */
! defined( 'LD_MC_NAME' )                           && define( 'LD_MC_NAME', $plugin_data['Name'] );
! defined( 'LD_MC_VERSION' )                        && define( 'LD_MC_VERSION', $plugin_data['Version'] );
! defined( 'LD_MC_AUTHOR_NAME' )                    && define( 'LD_MC_AUTHOR_NAME', $plugin_data['AuthorName'] );
! defined( 'LD_MC_AUTHOR_SITE' )                    && define( 'LD_MC_AUTHOR_SITE', 'https:wooninjas.com' ); // $plugin_data['AuthorURI']  // edd.localhost.com
! defined( 'LD_MC_TEXT_DOMAIN' )                    && define( 'LD_MC_TEXT_DOMAIN', 'LD-MC' );
! defined( 'LD_MC_LD_NAME' )                        && define( 'LD_MC_LD_NAME', 'LearnDash LMS' );
! defined( 'LD_MC_LD_DIR_PATH' )                    && define( 'LD_MC_LD_DIR_PATH', $learndash_dir_path );
! defined( 'LD_MC_LD_MAIN_FILE_RELATIVE_PATH' )     && define( 'LD_MC_LD_MAIN_FILE_RELATIVE_PATH', $learndash_plugin_basename );
! defined( 'LD_MC_LD_MAIN_FILE_ABSOLUTE_PATH' )     && define( 'LD_MC_LD_MAIN_FILE_ABSOLUTE_PATH', WP_PLUGIN_DIR.'/'.LD_MC_LD_MAIN_FILE_RELATIVE_PATH );
! defined( 'LD_MC_MAIN_FILE_RELATIVE_PATH' )        && define( 'LD_MC_MAIN_FILE_RELATIVE_PATH', $ldcvss_plugin_basename);
! defined( 'LD_MC_MAIN_FILE_ABSOLUTE_PATH' )        && define( 'LD_MC_MAIN_FILE_ABSOLUTE_PATH', WP_PLUGIN_DIR.'/'.LD_MC_MAIN_FILE_RELATIVE_PATH );
! defined( 'LD_MC_BASE_DIR' )                       && define( 'LD_MC_BASE_DIR', plugin_basename( LD_MC_MAIN_FILE_ABSOLUTE_PATH ) );
! defined( 'LD_MC_DIR_PATH' )                       && define( 'LD_MC_DIR_PATH', plugin_dir_path( LD_MC_MAIN_FILE_ABSOLUTE_PATH ) );
! defined( 'LD_MC_ASSETS_DIR_PATH' )                && define( 'LD_MC_ASSETS_DIR_PATH', trailingslashit( LD_MC_DIR_PATH . 'assets' ) );
! defined( 'LD_MC_WP_UPLOAD_DIR' )                  && define( 'LD_MC_WP_UPLOAD_DIR', $wp_upload_dir['basedir'] );
! defined( 'LD_MC_PATH_CERTIFICATES' )              && define( 'LD_MC_PATH_CERTIFICATES', LD_MC_WP_UPLOAD_DIR . '/ld-mc-certificates' );


! defined( 'LD_MC_DIR_URL' )            && define( 'LD_MC_DIR_URL', trailingslashit( plugin_dir_url( LD_MC_MAIN_FILE_ABSOLUTE_PATH ) ) );
! defined( 'LD_MC_ASSETS_URL' )         && define( 'LD_MC_ASSETS_URL', trailingslashit( LD_MC_DIR_URL . 'assets' ) );
! defined( 'LD_MC_WP_UPLOAD_URL' )      && define( 'LD_MC_WP_UPLOAD_URL', $wp_upload_dir['baseurl'] );
! defined( 'LD_MC_URL_CERTIFICATES' )   && define( 'LD_MC_URL_CERTIFICATES', LD_MC_WP_UPLOAD_URL . '/ld-mc-certificates' );

/**
 * Plugin URLS*
*/
//define( 'LD_MC_BASE_URL', get_bloginfo( 'url' ) );
//define( 'LD_MC_DIR_URL', trailingslashit( plugin_dir_url( LD_MC_MAIN_FILE_ABSOLUTE_PATH ) ) );
//define( 'LD_MC_ASSETS_URL', trailingslashit( LD_MC_DIR_URL . 'assets' ) );
/* End - Plugin Global Constants */


/**
 * App Bootstraping
 */
function learndash_multiple_certificates() {
	if( \LDMC\Bootstrap\Requirements::get_instance()->validate_requirements() ) {
        $LDCVSS = \LDMC\Bootstrap\App::get_instance();
        do_action('ld_mc_loaded');
    }
}
add_action( 'plugins_loaded', 'learndash_multiple_certificates' );


function ldmc_bp_learndash_get_users_certificates( $user_id = '' ) {
        if ( empty( $user_id ) ) {
            return false;
        }

        /**
         * Course Certificate
         **/
        $user_courses = ld_get_mycourses( $user_id, array() );
        $certificates = array();

        $course_class_obj = new \LDMC\FrontEnd\Themes\LD30\Course();
        foreach ( $user_courses as $course_id ) {

            $certificateLink = learndash_get_course_certificate_link( $course_id, $user_id );
            $filename        = "Certificate.pdf";
            $course_title    = get_the_title( $course_id );
            $certificate_id  = learndash_get_setting( $course_id, 'certificate' );
            $image           = '';

            


            if (is_array($certificate_id)) {
                if ($course_class_obj->allowed_multiple_certificates($course_id)) {

                    foreach ($certificate_id as $key => $certi_id) {
                        $cert_query_args['cert-nonce'] = wp_create_nonce($course_id . $user_id . $user_id);
                        $cert_query_args['course_id'] = $course_id;
                        $certificateLink = add_query_arg($cert_query_args, get_permalink(intval($certi_id)));


                        if ( ! empty( $certi_id ) ) {
                            $certificate_data = get_post( $certi_id );
                            $filename         = sanitize_file_name( $course_title ) . "-" . sanitize_file_name( $certificate_data->post_title ) . ".pdf";
                            $image            = wp_get_attachment_url( get_post_thumbnail_id( $certi_id ) );
                        }

                        $date = get_user_meta( $user_id, 'course_completed_' . $course_id, true );

                        if ( ! empty( $certificateLink ) && !empty($date) ) {
                            $certificate           = new \stdClass();
                            $certificate->ID       = $course_id;
                            $certificate->link     = $certificateLink;
                            $certificate->title    = get_the_title( $course_id );
                            $certificate->filename = $filename;
                            $certificate->date     = date_i18n( "Y-m-d h:i:s", $date );
                            $certificate->time     = $date;
                            $certificate->type     = 'course';
                            $certificates[]        = $certificate;
                        }
                    }
                    
                }else{

                    $certificate_id = $course_class_obj->get_user_selected_certificate_id($course_id, $user_id);

                    if ( ! empty( $certificate_id ) ) {
                        $certificate_data = get_post( $certificate_id );
                        $filename         = sanitize_file_name( $course_title ) . "-" . sanitize_file_name( $certificate_data->post_title ) . ".pdf";
                        $image            = wp_get_attachment_url( get_post_thumbnail_id( $certificate_id ) );
                    }

                    $c_cert_query_args['cert-nonce'] = wp_create_nonce($course_id . $user_id . $user_id);
                    $c_cert_query_args['course_id'] = $course_id;
                    $certificateLink = add_query_arg($c_cert_query_args, get_permalink(intval($certificate_id)));

                    $date = get_user_meta( $user_id, 'course_completed_' . $course_id, true );

                    // var_dump();
                    // var_dump();

                    if ( ! empty( $certificateLink ) && !empty($date) ) {
                        $certificate           = new \stdClass();
                        $certificate->ID       = $course_id;
                        $certificate->link     = $certificateLink;
                        $certificate->title    = get_the_title( $course_id );
                        $certificate->filename = $filename;
                        $certificate->date     = date_i18n( "Y-m-d h:i:s", $date );
                        $certificate->time     = $date;
                        $certificate->type     = 'course';
                        $certificates[]        = $certificate;
                    }


                }
            }else{

                if ( ! empty( $certificate_id ) ) {
                    $certificate_data = get_post( $certificate_id );
                    $filename         = sanitize_file_name( $course_title ) . "-" . sanitize_file_name( $certificate_data->post_title ) . ".pdf";
                    $image            = wp_get_attachment_url( get_post_thumbnail_id( $certificate_id ) );
                }

                $date = get_user_meta( $user_id, 'course_completed_' . $course_id, true );

                if ( ! empty( $certificateLink ) ) {
                    $certificate           = new \stdClass();
                    $certificate->ID       = $course_id;
                    $certificate->link     = $certificateLink;
                    $certificate->title    = get_the_title( $course_id );
                    $certificate->filename = $filename;
                    $certificate->date     = date_i18n( "Y-m-d h:i:s", $date );
                    $certificate->time     = $date;
                    $certificate->type     = 'course';
                    $certificates[]        = $certificate;
                }

            }

            
        }

        /**
         * Quiz Certificate
         **/
        $quizzes  = get_user_meta( $user_id, '_sfwd-quizzes', true );
        $quiz_ids = empty( $quizzes ) ? array() : wp_list_pluck( $quizzes, 'quiz' );
        if ( ! empty( $quiz_ids ) ) {
            $quiz_total_query_args = array(
                'post_type' => 'sfwd-quiz',
                'fields'    => 'ids',
                'orderby'   => 'title', //$atts['quiz_orderby'],
                'order'     => 'ASC', //$atts['quiz_order'],
                'nopaging'  => true,
                'post__in'  => $quiz_ids
            );
            $quiz_query            = new \WP_Query( $quiz_total_query_args );
            $quizzes_tmp           = array();
            foreach ( $quiz_query->posts as $post_idx => $quiz_id ) {
                foreach ( $quizzes as $quiz_idx => $quiz_attempt ) {
                    if ( $quiz_attempt['quiz'] == $quiz_id ) {
                        $quiz_key                 = $quiz_attempt['time'] . '-' . $quiz_attempt['quiz'];
                        $quizzes_tmp[ $quiz_key ] = $quiz_attempt;
                        unset( $quizzes[ $quiz_idx ] );
                    }
                }
            }
            $quizzes = $quizzes_tmp;
            krsort( $quizzes );
            if ( ! empty( $quizzes ) ) {
                foreach ( $quizzes as $quizdata ) {
                    if ( ! in_array( $quizdata['quiz'], wp_list_pluck( $certificates, 'ID' ) ) ) {
                        $quiz_settings         = learndash_get_setting( $quizdata['quiz'] );
                        $certificate_post_id   = $quiz_settings['certificate'];
                        if (is_array($certificate_post_id)) {
                           
                            if ( $course_class_obj->allowed_multiple_certificates($quizdata['quiz']) ) {
                                 
                                foreach ( $certificate_post_id as $key => $certificate_id) {

                                    $certificate_post_data = get_post( $certificate_id );
                                    $certificate_data      = learndash_certificate_details( $quizdata['quiz'], $user_id );

                                    $cert_query_args['cert-nonce'] = wp_create_nonce($quizdata['quiz'] . $user_id . $user_id);
                                    $cert_query_args['quiz'] = $quizdata['quiz'];
                                    $certificate_data['certificateLink'] = add_query_arg($cert_query_args, get_permalink(intval($certificate_id)));
                                   

                                    if ( ! empty( $certificate_data['certificateLink'] ) && !( $certificate_data['certificateLink'] == 'javascript:;' || $certificate_data['certificateLink'] == 'javascript:void(0)' ) && $certificate_data['certificate_threshold'] <= $quizdata['percentage'] / 100 ) {
                                        $filename              = sanitize_file_name( get_the_title( $quizdata['quiz'] ) ) . "-" . sanitize_file_name( get_the_title( $certificate_id ) ) . ".pdf";
                                        $certificate           = new \stdClass();
                                        $certificate->ID       = $quizdata['quiz'];
                                        $certificate->link     = $certificate_data['certificateLink'];
                                        $certificate->title    = get_the_title( $quizdata['quiz'] ).' - '.get_the_title( $certificate_id );
                                        $certificate->filename = $filename;
                                        $certificate->date     = date_i18n( "Y-m-d h:i:s", $quizdata['time'] );
                                        $certificate->time     = $quizdata['time'];
                                        $certificate->type     = 'quiz';
                                        $certificates[]        = $certificate;
                                    }
                                   
                                }

                            }else{
                               $certificate_post_id = $course_class_obj->get_user_selected_certificate_id($quizdata['quiz'], $user_id);
                               $certificate_post_data = get_post( $certificate_post_id );


                               
                                $certificate_data      = learndash_certificate_details( $quizdata['quiz'], $user_id );

                                if ( ! empty( $certificate_post_id )  && $certificate_data['certificate_threshold'] <= $quizdata['percentage'] / 100 ) {
                                    $filename              = sanitize_file_name( get_the_title( $quizdata['quiz'] ) ) . "-" . sanitize_file_name( get_the_title( $certificate_post_id ) ) . ".pdf";

                                    $cert_query_args['cert-nonce'] = wp_create_nonce($quizdata['quiz'] .$user_id . $user_id);
                                    $cert_query_args['quiz'] = $quizdata['quiz'];
                                    $url = add_query_arg($cert_query_args, get_permalink(intval($certificate_post_id)));
                    
                                    $certificate           = new \stdClass();
                                    $certificate->ID       = $quizdata['quiz'];
                                    $certificate->link     = $url;
                                    $certificate->title    = get_the_title( $quizdata['quiz'] );
                                    $certificate->filename = $filename;
                                    $certificate->date     = date_i18n( "Y-m-d h:i:s", $quizdata['time'] );
                                    $certificate->time     = $quizdata['time'];
                                    $certificate->type     = 'quiz';
                                    $certificates[]        = $certificate;
                                }

                            }

                        }else{

                            $certificate_post_data = get_post( $certificate_post_id );
                            $certificate_data      = learndash_certificate_details( $quizdata['quiz'], $user_id );

                            if ( ! empty( $certificate_data['certificateLink'] ) && !( $certificate_data['certificateLink'] == 'javascript:;' || $certificate_data['certificateLink'] == 'javascript:void(0)' ) && $certificate_data['certificate_threshold'] <= $quizdata['percentage'] / 100 ) {
                                $filename              = sanitize_file_name( get_the_title( $quizdata['quiz'] ) ) . "-" . sanitize_file_name( get_the_title( $certificate_post_id ) ) . ".pdf";
                                $certificate           = new \stdClass();
                                $certificate->ID       = $quizdata['quiz'];
                                $certificate->link     = $certificate_data['certificateLink'];
                                $certificate->title    = get_the_title( $quizdata['quiz'] );
                                $certificate->filename = $filename;
                                $certificate->date     = date_i18n( "Y-m-d h:i:s", $quizdata['time'] );
                                $certificate->time     = $quizdata['time'];
                                $certificate->type     = 'quiz';
                                $certificates[]        = $certificate;
                            }


                        }
                        
                        
                    }

                }
            }
        }

        usort( $certificates, function ( $a, $b ) {
            return strcmp( $b->time, $a->time );
        } );

        return $certificates;
    }