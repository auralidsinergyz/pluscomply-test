<?php

namespace uncanny_pro_toolkit;

use TCPDF;
use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class CourseCompletionCertificate
 * @package uncanny_pro_toolkit
 */
class CourseCompletionCertificate extends toolkit\Config implements toolkit\RequiredFunctions {

	public static $current_time_stamp;

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {

			/* ADD FILTERS ACTIONS FUNCTION */
			add_action( 'learndash_course_completed', array(
				__CLASS__,
				'schedule_generate_course_certificate'
			), 20, 1 );


			add_action( 'uo_scheduled_learndash_course_completed', array(
				__CLASS__,
				'generate_course_certificate'
			), 20, 2 );
			//add_action( 'learndash_course_completed', array( __CLASS__, 'generate_course_certificate' ), 20 );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = __( 'Email Course Certificates', 'uncanny-pro-toolkit' );

		//set to null or remove to disable the link to KB
		$kb_link = 'https://www.uncannyowl.com/knowledge-base/send-course-certificates-email/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Sends a copy of certificate earned from course completion and saves certificates on the server.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-file-pdf-o"></i><span class="uo_pro_text">PRO</span>';

		$category = 'learndash';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => self::get_class_settings( $class_title ),
			'icon'             => $class_icon,
		);

	}

	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * @return boolean || string Return either true or name of function or plugin
	 *
	 */
	public static function dependants_exist() {

		/* Checks for LearnDash */
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		// Return true if no dependency or dependency is available
		return true;
	}


	/**
	 * HTML for modal to create settings
	 *
	 * @static
	 *
	 * @param $class_title
	 *
	 * @return array
	 */
	public static function get_class_settings( $class_title ) {

		// Create options
		$options = array(

			/*array(
				'type'       => 'html',
				'inner_html' => sprintf( '<h4 style="margin:0">To use this module, quiz emails to the learner must be enabled. <a target="_blank" href="%s">CLICK HERE</a> to turn them on and to change the email subject and body that are used in student emails (values can be changed under <i>E-Mail Settings > User e-mail settings</i>).</h4>', admin_url( 'admin.php?page=ldAdvQuiz&module=globalSettings' ) ),
			),*/
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Use Cron to send certificate', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-use-cron',
			),
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Do not store certificates on server', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-dont-store',
			),
			array(
				'type'       => 'html',
				'inner_html' => 'By default, certificates are stored at: &lt;site root&gt;/wp-content/uploads/course-certificates/',
			),
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Send Certificate to Site Admin?', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-admin',
			),
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Send Certificate to Group Leader(s)?', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-group-leader',
			),
			array(
				'type'        => 'text',
				'placeholder' => 'jon@doe.com, doe@jon.com',
				'label'       => esc_html__( 'CC Certificate To (comma separated)', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-cc-emails',
			),
			array(
				'type'        => 'text',
				'placeholder' => 'You Earned a Certificate',
				'label'       => esc_html__( 'User Email Subject', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-user-subject-line',
			),
			array(
				'type'        => 'text',
				'placeholder' => '%User% has earned a course certificate',
				'label'       => esc_html__( 'Admin/Group Leader Email Subject', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-subject-line',
			),
			array(
				'type'        => 'textarea',
				'placeholder' => '%User% in %Group Name% has earned a course certificate for completing %Course Name%.',
				'label'       => esc_html__( 'Email Body', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-email-body',
			),
			array(
				'type'        => 'textarea',
				'placeholder' => '%User% in %Group Name% has earned a course certificate for completing %Course Name%.',
				'label'       => esc_html__( 'Email Body &mdash; Admin/Group Leader', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-course-certificate-non-user-email-body',
			),
			array(
				'type'       => 'html',
				'inner_html' => '<strong>Available variables for email subject & body</strong><br /><ul><li><strong>%User%</strong> &mdash; Prints User\'s Display Name</li><li><strong>%User First Name%</strong> &mdash; Prints User\'s First Name</li><li><strong>%User Last Name%</strong> &mdash; Prints User\'s Last Name</li><li><strong>%Group Name%</strong> &mdash; Prints Group Name <!--<em>Only Available for Group Leader</em>--></li><li><strong>%User Email%</strong> &mdash; Prints User Email</li><li><strong>%Course Name%</strong> &mdash; Prints Course Title</li></ul>',
			),
		);

		// Build html
		$html = self::settings_output(
			array(
				'class'   => __CLASS__,
				'title'   => $class_title,
				'options' => $options,
			) );

		return $html;
	}

	/**
	 * @param $atts
	 */
	public static function schedule_generate_course_certificate( $atts ) {
		$pass_args = [
			$atts['user']->ID,
			$atts['course']->ID,
		];
		//Use Cron?
		$use_cron = self::get_settings_value( 'uncanny-course-certificate-use-cron', __CLASS__ );
		if ( ! empty( $use_cron ) && 'on' === $use_cron ) {
			$random_number = rand( 25, 90 );
			$next_run      = strtotime( '+' . $random_number . ' second' );
			wp_schedule_single_event( $next_run, 'uo_scheduled_learndash_course_completed', $pass_args );
		} else {
			self::generate_course_certificate( $atts['user']->ID, $atts['course']->ID );
		}
	}

	/**
	 * @param $user_id
	 * @param $course_id
	 */
	public static function generate_course_certificate( $user_id, $course_id ) {
		$user         = new \WP_User( $user_id );
		$email_params = array( 'email' => '', 'msg' => '', 'subject' => '', );
		$file         = '';

		//External override if required. Usage is in CEU historical records & AT for events ending date.
		$current_time             = get_user_meta( $user_id, 'course_completed_' . $course_id, true );
		self::$current_time_stamp = apply_filters( 'uo_course_completion_time', $current_time, $course_id, $user_id );
		//Fallback
		if ( empty( self::$current_time_stamp ) ) {
			self::$current_time_stamp = ! empty( $current_time ) ? $current_time : current_time( 'timestamp' );
		}

		do_action( 'uo_course_completion_time', self::$current_time_stamp, $course_id, $user_id );
		if ( learndash_course_completed( $user_id, $course_id ) ) {
			$setup_parameters = self::setup_parameters( $course_id, $user_id );

			if ( 1 === $setup_parameters['print-certificate'] ) {

				$certificate_post = $setup_parameters['certificate-post'];
				/* Save Path on Server under Upload & allow overwrite */
				$save_path = apply_filters( 'uo_course_certificate_save_path', WP_CONTENT_DIR . '/uploads/course-certificates/' );
				//$plugin_dir       = WP_PLUGIN_DIR; /* Plugin directory - to be used to include tcpdf files in generate_pdf() function  */
				//$course_title     = $setup_parameters['course-name'];
				$course_cert_meta = '_uo-course-cert-' . $course_id;

				/* Creating a fileName that is going to be stored on the server. Certificate-QUIZID-USERID-NONCE_String */
				$file_name = sanitize_title( $user->user_email . '-' . $course_id . '-' . $certificate_post . '-' . date( 'Ymd', self::$current_time_stamp ) . '-' . wp_create_nonce( self::$current_time_stamp ) );

				//Allow overwrite of custom filename
				$file_name = apply_filters( 'uo_course_completion_certificate_filename', $file_name, $user, $course_id, $certificate_post, self::$current_time_stamp );
				if ( ! file_exists( $save_path ) ) {
					mkdir( $save_path, 0755 );
				}

				//Allow PDF args to be modified
				$generate_pdf_args = apply_filters( 'uo_course_completion_generate_pdf_args', [
					'certificate_post' => $certificate_post,
					'save_path'        => $save_path,
					'user'             => $user,
					'file_name'        => $file_name,
					'parameters'       => $setup_parameters,
				], $course_id, $user_id );

				//External override if certificate is not needed!
				$uo_generate_course_certs = apply_filters( 'uo_generate_course_certificate', true, $generate_pdf_args, $course_id, $user_id );
				if ( $uo_generate_course_certs ) {
					$file = self::generate_pdf( $generate_pdf_args );
					//Allow custom Link to an upload folder
					$http_link         = apply_filters( 'uo_course_certificate_http_url', WP_CONTENT_URL . '/uploads/course-certificates/' );
					$http_link_to_file = $http_link . $file_name . '.pdf';
					do_action( 'uo_course_certificate_pdf_url', $http_link_to_file, $course_id, self::$current_time_stamp, $user_id );
					$current_certs = get_user_meta( $user_id, $course_cert_meta, true );

					if ( ! empty( $current_certs ) ) {
						$current_certs[][ self::$current_time_stamp ] = $http_link_to_file;
						update_user_meta( $user_id, $course_cert_meta, $current_certs );
					} else {
						$certs[][ self::$current_time_stamp ] = $http_link_to_file;
						add_user_meta( $user_id, $course_cert_meta, $certs );
					}
				}
			}
			if ( ! empty( $file ) ) {
				//Sending Final Email with Attachment & PDF Link!
				if ( key_exists( 'headers', $email_params ) && ! empty( $email_params['headers'] ) ) {
					$headers = $email_params['headers'];
				} else {
					$headers   = [];
					$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . get_bloginfo( 'admin_email' ) . '>';
				}


				//self::trace_logs( $headers, 'Headers', 'pdf' );
				//global $wpdb;
				//$user           = wp_get_current_user();
				$email_params['email'] = $user->user_email;

				$is_admin            = self::get_settings_value( 'uncanny-course-certificate-admin', __CLASS__ );
				$is_group_admin      = self::get_settings_value( 'uncanny-course-certificate-group-leader', __CLASS__ );
				$email_message       = self::get_settings_value( 'uncanny-course-certificate-email-body', __CLASS__ );
				$non_user_email_body = self::get_settings_value( 'uncanny-course-certificate-non-user-email-body', __CLASS__ );
				$email_subject       = self::get_settings_value( 'uncanny-course-certificate-subject-line', __CLASS__ );
				$email_subject_user  = self::get_settings_value( 'uncanny-course-certificate-user-subject-line', __CLASS__ );
				$cc                  = self::get_settings_value( 'uncanny-course-certificate-cc-emails', __CLASS__ );


				if ( true === strpos( $cc, ',' ) ) {
					$cc = explode( ',', $cc );
				}

				if ( empty( $email_message ) ) {
					$email_message = '%User% has earned a course certificate for completing %Course Name%.';
				}

				if ( empty( $non_user_email_body ) ) {
					$non_user_email_body = '%User% has earned a course certificate for completing %Course Name%.';
				}

				if ( empty( $email_subject ) ) {
					$email_subject = '%User% has earned a course certificate';
				}

				if ( empty( $email_subject_user ) ) {
					$email_subject_user = 'You earned a certificate';
				}


				$user_groups = learndash_get_users_group_ids( $user->ID, true );
				$ugroups     = [];
				if ( $user_groups ) {
					foreach ( $user_groups as $gr ) {
						$ugroups[] = get_the_title( $gr );
					}
				} else {
					$ugroups[] = '';
				}
				$ugroups = join( ', ', $ugroups );

				$email_message = str_ireplace( '%User%', $user->display_name, $email_message );
				$email_message = str_ireplace( '%User First Name%', $user->first_name, $email_message );
				$email_message = str_ireplace( '%User Last Name%', $user->last_name, $email_message );
				$email_message = str_ireplace( '%User Email%', $user->user_email, $email_message );
				$email_message = str_ireplace( '%Course Name%', $setup_parameters['course-name'], $email_message );
				$email_message = str_ireplace( '%Group Name%', $ugroups, $email_message );

				$non_user_email_body = str_ireplace( '%User%', $user->display_name, $non_user_email_body );
				$non_user_email_body = str_ireplace( '%User First Name%', $user->first_name, $non_user_email_body );
				$non_user_email_body = str_ireplace( '%User Last Name%', $user->last_name, $non_user_email_body );
				$non_user_email_body = str_ireplace( '%User Email%', $user->user_email, $non_user_email_body );
				$non_user_email_body = str_ireplace( '%Course Name%', $setup_parameters['course-name'], $non_user_email_body );
				$non_user_email_body = do_shortcode( stripslashes( $non_user_email_body ) );

				$email_subject_user = str_ireplace( '%User%', 'You', $email_subject_user );
				$email_subject_user = str_ireplace( '%User First Name%', $user->first_name, $email_subject_user );
				$email_subject_user = str_ireplace( '%User Last Name%', $user->last_name, $email_subject_user );
				$email_subject_user = str_ireplace( '%User Email%', $user->user_email, $email_subject_user );
				$email_subject_user = str_ireplace( '%Group Name%', $ugroups, $email_subject_user );
				$email_subject_user = str_ireplace( '%Course Name%', $setup_parameters['course-name'], $email_subject_user );
				$email_subject_user = do_shortcode( stripslashes( $email_subject_user ) );

				$email_subject = str_ireplace( '%User%', $user->display_name, $email_subject );
				$email_subject = str_ireplace( '%User First Name%', $user->first_name, $email_subject );
				$email_subject = str_ireplace( '%User Last Name%', $user->last_name, $email_subject );
				$email_subject = str_ireplace( '%User Email%', $user->user_email, $email_subject );
				$email_subject = str_ireplace( '%Group Name%', $ugroups, $email_subject );
				$email_subject = str_ireplace( '%Course Name%', $setup_parameters['course-name'], $email_subject );
				$email_subject = do_shortcode( stripslashes( $email_subject ) );

				$email_message           = do_shortcode( stripslashes( $email_message ) );
				$email_message           = wpautop( $email_message );
				$email_params['msg']     .= $email_message;
				$email_params['subject'] = stripslashes( $email_subject_user );
				//self::trace_logs( $email_params, '$email_params', 'pdf' );

				//Sending Email To User!
				$change_content_type = apply_filters( 'uo_apply_wp_mail_content_type', true );
				if ( $change_content_type ) {
					add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_html_mail_content_type' ) );
				}
				wp_mail( $email_params['email'], $email_params['subject'], $email_params['msg'], $headers, $file );

				if ( 'on' === $is_admin ) {
					$non_user_email_body = str_ireplace( '%Group Name%', $ugroups, $non_user_email_body );
					$change_content_type = apply_filters( 'uo_apply_wp_mail_content_type', true );
					if ( $change_content_type ) {
						add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_html_mail_content_type' ) );
					}
					$non_user_email_body = wpautop( $non_user_email_body );
					wp_mail( get_bloginfo( 'admin_email' ), $email_subject, $non_user_email_body, $headers, $file );
				}

				if ( 'on' === $is_group_admin ) {
					$get_leaders       = [];
					$get_course_groups = learndash_get_course_groups( $setup_parameters['course-id'], true );
					$user_groups       = learndash_get_users_group_ids( $user->ID, true );
					//self::trace_logs( $get_course_groups, 'Course Groups', 'pdf' );
					//self::trace_logs( $user_groups, 'User Groups', 'pdf' );
					if ( ! empty( $get_course_groups ) && ! empty( $user_groups ) ) {
						$results = array_intersect( $get_course_groups, $user_groups );
						//self::trace_logs( $results, 'Results', 'pdf' );
						if ( $results ) {
							foreach ( $results as $group ) {
								$has_group_leader = learndash_get_groups_administrators( $group, true );
								//self::trace_logs( $has_group_leader, 'Has Group Leaders', 'pdf' );
								if ( ! empty( $has_group_leader ) ) {
									foreach ( $has_group_leader as $leader ) {
										if ( learndash_is_group_leader_of_user( $leader->ID, $user->ID ) ) {
											$ll                      = get_user_by( 'ID', $leader->ID );
											$get_leaders[ $group ][] = $ll->user_email;
										}
									}
								}
							}
						}
					}
					//self::trace_logs( $get_leaders, 'Get Leaders', 'pdf' );
					if ( ! empty( $get_leaders ) ) {
						foreach ( $get_leaders as $key => $value ) {
							$email_subject       = str_ireplace( '%Group Name%', get_the_title( $key ), $email_subject );
							$non_user_email_body = str_ireplace( '%Group Name%', get_the_title( $key ), $non_user_email_body );
							$change_content_type = apply_filters( 'uo_apply_wp_mail_content_type', true );
							if ( $change_content_type ) {
								add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_html_mail_content_type' ) );
							}
							$non_user_email_body = wpautop( $non_user_email_body );
							wp_mail( $value, $email_subject, $non_user_email_body, $headers, $file );
						}
					}
				}

				if ( ! empty( $cc ) ) {
					$email_message       = str_ireplace( '%Group Name%', $ugroups, $email_message );
					$change_content_type = apply_filters( 'uo_apply_wp_mail_content_type', true );
					if ( $change_content_type ) {
						add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_html_mail_content_type' ) );
					}
					$email_message = wpautop( $email_message );
					wp_mail( $cc, $email_subject, $email_message, $headers, $file );
				}

				if ( 'on' === self::get_settings_value( 'uncanny-course-certificate-dont-store', __CLASS__ ) ) {
					if ( file_exists( $file ) ) {
						unlink( $file );
					}
				}
			}
		}
	}

	/**
	 * @param $course_id
	 * @param $user_id
	 *
	 * @return array
	 */
	public static function setup_parameters( $course_id, $user_id ) {
		$setup_parameters = [];
		$meta             = get_post_meta( $course_id, '_sfwd-courses', true );

		$setup_parameters['userID']            = $user_id;
		$setup_parameters['course-id']         = $course_id;
		$setup_parameters['course-name']       = get_the_title( $course_id );
		$setup_parameters['print-certificate'] = 0;

		if ( is_array( $meta ) && ! empty( $meta ) && key_exists( 'sfwd-courses_certificate', $meta ) && ! empty( $meta['sfwd-courses_certificate'] ) ) {
			//Setting Certificate Post ID
			$setup_parameters['certificate-post'] = $meta['sfwd-courses_certificate'];
		}

		if ( empty( $setup_parameters['certificate-post'] ) ) {
			return $setup_parameters;
		}

		$setup_parameters['print-certificate'] = 1;

		return apply_filters( 'uo_course_completion_setup_parameters', $setup_parameters, $course_id, $user_id, $setup_parameters['certificate-post'] );
	}

	/**
	 * @param $args
	 *
	 * @return string
	 */
	public static function generate_pdf( $args ) {
		$certificate_id  = $args['certificate_post'];
		$save_path       = $args['save_path'];
		$file_name       = $args['file_name'];
		$user            = $args['user'];
		$parameters      = $args['parameters'];
		$post_id         = intval( $certificate_id );
		$post_data       = get_post( $post_id );
		$monospaced_font = '';
		$l               = '';
		$config_lang     = 'eng';
		$ratio           = 1.25;
		$title           = strip_tags( $post_data->post_title );
		//$content             = $post_data->post_content;
		$target_post_id      = $post_id;
		$get_by_http_request = 0;
		$shortcode           = 'parse';
		$get_post            = get_post( $post_id );
		global $post;
		$post = $get_post;
		setup_postdata( $post );
		ob_start();

		$title = strip_tags( $title );

		$permalink   = get_permalink( $post_data->ID );
		$author_data = get_userdata( $post_data->post_author );

		if ( $author_data->display_name ) {
			$author = $author_data->display_name;
		} else {
			$author = $author_data->user_nicename;
		}

		/*$tag       = [];
		$tags      = '';
		$tags_data = wp_get_post_tags( $post_data->ID );

		if ( $tags_data ) {
			foreach ( $tags_data as $val ) {
				$tag[] = $val->name;
			}
			$tags = implode( ' ', $tag );
		}*/

		if ( 1 === $get_by_http_request ) {
			$permalink_url = get_permalink( $post_id );
			$response_data = wp_remote_get( $permalink_url );
			$content       = preg_replace( '|^.*?<!-- post2pdf-converter-begin -->(.*?)<!-- post2pdf-converter-end -->.*?$|is', '$1', $response_data['body'] );
		} else {
			$content = $post_data->post_content;
		}

		if ( ! empty( $_GET['lang'] ) ) {
			$config_lang = substr( esc_html( $_GET['lang'] ), 0, 3 );
		}

		if ( ! empty( $_GET['file'] ) ) {
			$filename_type = $_GET['file'];
		}

		if ( 'title' === $filename_type && 0 === $target_post_id ) {
			$filename = $post_data->post_title;

		} else {
			$filename = $post_id;
		}

		$filename = substr( $filename, 0, 255 );

		$chached_filename = '';

		if ( 0 !== $target_post_id ) {
			$filename = WP_CONTENT_DIR . '/tcpdf-pdf/' . $filename;
		}


		if ( ! empty( $_GET['font'] ) ) {
			$font = esc_html( $_GET['font'] );
		}

		if ( ! empty( $_GET['monospaced'] ) ) {
			$monospaced_font = esc_html( $_GET['monospaced'] );
		}

		if ( ! empty( $_GET['fontsize'] ) ) {
			$font_size = intval( $_GET['fontsize'] );
		}

		if ( ! empty( $_GET['subsetting'] ) && ( $_GET['subsetting'] == 1 || $_GET['subsetting'] == 0 ) ) {
			$subsetting_enable = $_GET['subsetting'];
		}

		if ( 1 === $subsetting_enable ) {
			$subsetting = 'true';
		} else {
			$subsetting = 'false';
		}

		if ( ! empty( $_GET['ratio'] ) ) {
			$ratio = floatval( $_GET['ratio'] );
		}

		if ( ! empty( $_GET['header'] ) ) {
			$header_enable = $_GET['header'];
		}

		if ( ! empty( $_GET['logo'] ) ) {
			$logo_enable = $_GET['logo'];
		}

		if ( ! empty( $_GET['logo_file'] ) ) {
			$logo_file = esc_html( $_GET['logo_file'] );
		}

		if ( ! empty( $_GET['logo_width'] ) ) {
			$logo_width = intval( $_GET['logo_width'] );
		}

		if ( ! empty( $_GET['wrap_title'] ) ) {
			$wrap_title = $_GET['wrap_title'];
		}

		if ( ! empty( $_GET['footer'] ) ) {
			$footer_enable = $_GET['footer'];
		}

		if ( ! empty( $_GET['filters'] ) ) {
			$filters = $_GET['filters'];
		}

		if ( ! empty( $_GET['shortcode'] ) ) {
			$shortcode = esc_html( $_GET['shortcode'] );
		}

		if ( 0 !== $target_post_id ) {
			$destination = 'F';
		} else {
			$destination = 'I';
		}

		$content = preg_replace( '/(\[courseinfo)/', '[courseinfo user_id="' . $user->ID . '" course_id="' . $parameters['course-id'] . '" ', $content );
		$content = preg_replace( '/(\[usermeta)/', '[usermeta user_id="' . $user->ID . '" ', $content );
		$content = apply_filters( 'uo_generate_course_certificate_content', $content, $user->ID, $parameters['course-id'] );

		// Delete shortcode for POST2PDF Converter
		$content = preg_replace( '|\[pdf[^\]]*?\].*?\[/pdf\]|i', '', $content );

		// For WP Code Highlight
		if ( function_exists( 'wp_code_highlight_filter' ) ) {
			$content = wp_code_highlight_filter( $content );
			$content = preg_replace( '/<pre[^>]*?>(.*?)<\/pre>/is', '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		}

		// Parse shortcode before applied WP default filters
		if ( 'parse' === $shortcode ) {

			// For WP SyntaxHighlighter
			if ( function_exists( 'wp_sh_add_extra_bracket' ) ) {
				$content = wp_sh_add_extra_bracket( $content );
			}

			if ( function_exists( 'wp_sh_do_shortcode' ) ) {
				$content = wp_sh_do_shortcode( $content );
			}

			// For SyntaxHighlighter Evolved
			if ( class_exists( 'SyntaxHighlighter' ) ) {
				global $SyntaxHighlighter;
				if ( method_exists( 'SyntaxHighlighter', 'parse_shortcodes' ) && method_exists( 'SyntaxHighlighter', 'shortcode_hack' ) ) {
					$content = $SyntaxHighlighter->parse_shortcodes( $content );
				}
			}

			// For SyntaxHighlighterPro
			if ( class_exists( 'GoogleSyntaxHighlighterPro' ) ) {
				global $googleSyntaxHighlighter;
				if ( method_exists( 'GoogleSyntaxHighlighterPro', 'bbcode' ) ) {
					$content = $googleSyntaxHighlighter->bbcode( $content );
				}
			}

		} elseif ( 1 !== $get_by_http_request ) {

			// For WP SyntaxHighlighter
			if ( function_exists( 'wp_sh_strip_shortcodes' ) ) {
				$content = wp_sh_strip_shortcodes( $content );
			}

			// For SyntaxHighlighterPro
			if ( class_exists( 'GoogleSyntaxHighlighterPro' ) ) {
				global $googleSyntaxHighlighter;
				if ( method_exists( 'GoogleSyntaxHighlighterPro', 'bbcode_strip' ) ) {
					$content = $googleSyntaxHighlighter->bbcode_strip( $content );
				}
			}
		}

		// Apply WordPress default filters to title and content
		if ( 1 === $filters && 1 !== $get_by_http_request ) {

			if ( has_filter( 'the_title', 'wptexturize' ) ) {
				$title = wptexturize( $title );
			}

			if ( has_filter( 'the_title', 'convert_chars' ) ) {
				$title = convert_chars( $title );
			}

			if ( has_filter( 'the_title', 'trim' ) ) {
				$title = trim( $title );
			}

			if ( has_filter( 'the_title', 'capital_P_dangit' ) ) {
				$title = capital_P_dangit( $title );
			}

			if ( has_filter( 'the_content', 'wptexturize' ) ) {
				$content = wptexturize( $content );
			}

			if ( has_filter( 'the_content', 'convert_smilies' ) ) {
				$content = convert_smilies( $content );
			}

			if ( has_filter( 'the_content', 'convert_chars' ) ) {
				$content = convert_chars( $content );
			}

			if ( has_filter( 'the_content', 'wpautop' ) ) {
				$content = wpautop( $content );
			}

			if ( has_filter( 'the_content', 'shortcode_unautop' ) ) {
				$content = shortcode_unautop( $content );
			}

			if ( has_filter( 'the_content', 'prepend_attachment' ) ) {
				$content = prepend_attachment( $content );
			}

			if ( has_filter( 'the_content', 'capital_P_dangit' ) ) {
				$content = capital_P_dangit( $content );
			}
		}
		//$content = do_shortcode( $content );

		if ( defined( 'LEARNDASH_LMS_LIBRARY_DIR' ) ) {
			require_once LEARNDASH_LMS_LIBRARY_DIR . '/tcpdf/config/lang/' . $config_lang . '.php';
			require_once LEARNDASH_LMS_LIBRARY_DIR . '/tcpdf/tcpdf.php';
		} else {
			$dir = self::get_learndash_plugin_directory();
			if ( $dir ) {
				// Include TCPDF
				require_once $dir . 'includes/vendor/tcpdf/config/lang/' . $config_lang . '.php';
				require_once $dir . 'includes/vendor/tcpdf/tcpdf.php';
			} else {
				return false;
			}
		}

		$certificate_details = get_post_meta( $certificate_id, 'learndash_certificate_options', true );

		if ( $certificate_details ) {
			$page_size        = $certificate_details['pdf_page_format'];
			$page_orientation = $certificate_details['pdf_page_orientation'];
		} else {
			$page_size        = 'LETTER';
			$page_orientation = 'L';
		}

		// Create a new object
		$pdf = new TCPDF( $page_orientation, PDF_UNIT, $page_size, true, 'UTF-8', false, false );

		// Set document information
		$pdf->SetCreator( PDF_CREATOR );
		$pdf->SetAuthor( get_bloginfo( 'name' ) );
		$pdf->SetTitle( $title . '_' . $post_id . '_' . get_bloginfo( 'name' ) );
		// Set default monospaced font
		$pdf->SetDefaultMonospacedFont( $monospaced_font );

		// Set header data
		if ( mb_strlen( $title, 'UTF-8' ) < 42 ) {
			$header_title = $title;
		} else {
			$header_title = mb_substr( $title, 0, 42, 'UTF-8' ) . '...';
		}

		if ( 1 === $header_enable ) {
			if ( 1 === $logo_enable && $logo_file ) {
				$pdf->SetHeaderData( $logo_file, $logo_width, $header_title, 'by ' . $author . ' - ' . $permalink );
			} else {
				$pdf->SetHeaderData( '', 0, $header_title, 'by ' . $author . ' - ' . $permalink );
			}
		}

		// Set header and footer fonts
		if ( 1 === $header_enable ) {
			$pdf->setHeaderFont( array( $font, '', PDF_FONT_SIZE_MAIN ) );
		}

		if ( 1 === $footer_enable ) {
			$pdf->setFooterFont( array( $font, '', PDF_FONT_SIZE_DATA ) );
		}

		// Remove header/footer
		if ( 0 === $header_enable ) {
			$pdf->setPrintHeader( false );
		}

		if ( 0 === $header_enable ) {
			$pdf->setPrintFooter( false );
		}
		// Set margins
		$pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );

		if ( 1 === $header_enable ) {
			$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
		}

		if ( 1 === $footer_enable ) {
			$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
		}

		// Set auto page breaks
		$pdf->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );

		// Set image scale factor
		$pdf->setImageScale( $ratio );

		// Set some language-dependent strings
		$pdf->setLanguageArray( $l );

		// Set fontsubsetting mode
		$pdf->setFontSubsetting( $subsetting );

		// Set font
		$pdf->SetFont( $font, '', $font_size, true );

		// Add a page
		$pdf->AddPage();

		// Create post content to print
		if ( 1 === $wrap_title ) {
			if ( ! mb_strlen( $title, 'UTF-8' ) < 33 ) {
				$title = mb_substr( $title, 0, 33, 'UTF-8' ) . '<br />' . mb_substr( $title, 33, 222, 'UTF-8' );
			}
		}

		//self::trace_logs( $post, '$post', 'certs' );

		// Parse shortcode after applied WP default filters
		if ( 'parse' === $shortcode && 1 !== $get_by_http_request ) {

			// For WP QuickLaTeX
			if ( function_exists( 'quicklatex_parser' ) ) {
				$content = quicklatex_parser( $content );
			}

			// For WP shortcode API
			$content = do_shortcode( $content );
		} elseif ( 1 !== $get_by_http_request ) {

			// For WP shortcode API
			$content = strip_shortcodes( $content );
		}

		// Convert relative image path to absolute image path
		$content = preg_replace( "/<img([^>]*?)src=['\"]((?!(http:\/\/|https:\/\/|\/))[^'\"]+?)['\"]([^>]*?)>/i", '<img$1src="' . site_url() . '/$2"$4>', $content );

		// Set image align to center
		$content = preg_replace_callback( "/(<img[^>]*?class=['\"][^'\"]*?aligncenter[^'\"]*?['\"][^>]*?>)/i", [
			__CLASS__,
			'post2pdf_conv_image_align_center'
		], $content );

		// Add width and height into image tag
		$content = preg_replace_callback( "/(<img[^>]*?src=['\"]((http:\/\/|https:\/\/|\/)[^'\"]*?(jpg|jpeg|gif|png))['\"])([^>]*?>)/i", [
			__CLASS__,
			'post2pdf_conv_img_size'
		], $content );

		// For common SyntaxHighlighter
		$content = preg_replace( "/<pre[^>]*?class=['\"][^'\"]*?brush:[^'\"]*?['\"][^>]*?>(.*?)<\/pre>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		$content = preg_replace( "/<script[^>]*?type=['\"]syntaxhighlighter['\"][^>]*?>(.*?)<\/script>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		$content = preg_replace( "/<pre[^>]*?name=['\"]code['\"][^>]*?>(.*?)<\/pre>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		$content = preg_replace( "/<textarea[^>]*?name=['\"]code['\"][^>]*?>(.*?)<\/textarea>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		$content = preg_replace( '/\n/', '<br/>', $content ); //"\n" should be treated as a next line

		// For WP-SynHighlight(GeSHi)
		if ( function_exists( 'wp_synhighlight_settings' ) ) {
			$content = preg_replace( "/<pre[^>]*?class=['\"][^>]*?>(.*?)<\/pre>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
			$content = preg_replace( '|<div[^>]*?class="wp-synhighlighter-outer"><div[^>]*?class="wp-synhighlighter-expanded"><table[^>]*?><tr><td[^>]*?><a[^>]*?></a><a[^>]*?class="wp-synhighlighter-title"[^>]*?>[^<]*?</a></td><td[^>]*?><a[^>]*?><img[^>]*?/></a>[^<]*?<a[^>]*?><img[^>]*?/></a>[^<]*?<a[^>]*?><img[^>]*?/></a>[^<]*?</td></tr></table></div>|is', '', $content );
		}

		// For other sourcecode
		$content = preg_replace( '/<pre[^>]*?><code[^>]*?>(.*?)<\/code><\/pre>/is', '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );

		// For blockquote
		$content = preg_replace( '/<blockquote[^>]*?>(.*?)<\/blockquote>/is', '<blockquote style="color: #406040;">$1</blockquote>', $content );

		// Combine title with content
		$formatted_title = '<h1 style="text-align:center;">' . $title . '</h1>';

		//$formatted_post = $formatted_title . '<br/><br/>' . $content;    (Title will not appear on PDF)
		$formatted_post = '<br/><br/>' . $content;
		$formatted_post = preg_replace( '/(<[^>]*?font-family[^:]*?:)([^;]*?;[^>]*?>)/is', '$1' . $font . ',$2', $formatted_post );

		// get featured image
		$postid   = get_the_id(); //Get current post id
		$img_file = self::learndash_get_thumb_path( $certificate_id ); //The same function from theme's[twentytwelve here] function.php

		//Only print image if it exists
		if ( ! empty( $img_file ) ) {

			//Print BG image
			$pdf->setPrintHeader( false );

			// get the current page break margin
			$bMargin = $pdf->getBreakMargin();

			// get current auto-page-break mode
			$auto_page_break = $pdf->getAutoPageBreak();

			// disable auto-page-break
			$pdf->SetAutoPageBreak( false, 0 );

			// Get width and height of page for dynamic adjustments
			$pageH = $pdf->getPageHeight();
			$pageW = $pdf->getPageWidth();

			//Print the Background
			$pdf->Image( $img_file, $x = '0', $y = '0', $w = $pageW, $h = $pageH, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = [] );

			// restore auto-page-break status
			$pdf->SetAutoPageBreak( $auto_page_break, $bMargin );

			// set the starting point for the page content
			$pdf->setPageMark();
		}

		// Print post
		$pdf->writeHTMLCell( $w = 0, $h = 0, $x = '', $y = '', $formatted_post, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true );

		// Set background
		$pdf->SetFillColor( 255, 255, 127 );
		$pdf->setCellPaddings( 0, 0, 0, 0 );
		// Print signature

		ob_clean();
		// Output pdf document
		$full_path = $save_path . $file_name . '.pdf';

		$pdf->Output( $full_path, 'F' ); /* F means saving on server. */
		wp_reset_postdata();

		return $full_path;

	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	public static function post2pdf_conv_image_align_center( $matches ) {
		$tag_begin = '<p class="post2pdf_conv_image_align_center">';
		$tag_end   = '</p>';

		return $tag_begin . $matches[1] . $tag_end;
	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	public static function post2pdf_conv_img_size( $matches ) {
		$size = null;

		if ( strpos( $matches[2], site_url() ) === false ) {
			return $matches[1] . $matches[5];
		}

		$image_path = ABSPATH . str_replace( site_url() . '/', '', $matches[2] );

		if ( file_exists( $image_path ) ) {
			$size = getimagesize( $image_path );
		} else {
			return $matches[1] . $matches[5];
		}

		return $matches[1] . ' ' . $size[3] . $matches[5];
	}

	/**
	 * @param $this ->post_id
	 *
	 * @return string
	 */
	public static function learndash_get_thumb_path( $post_id ) {
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		if ( $thumbnail_id ) {
			$img_path      = get_post_meta( $thumbnail_id, '_wp_attached_file', true );
			$upload_url    = wp_upload_dir();
			$img_full_path = $upload_url['basedir'] . '/' . $img_path;

			return $img_full_path;
		}
	}

	/**
	 * @return string
	 */
	public static function get_learndash_plugin_directory() {
		$all_plugins = get_plugins();
		$dir         = '';
		if ( $all_plugins ) {
			foreach ( $all_plugins as $key => $plugin ) {
				if ( 'LearnDash LMS' === $plugin['Name'] ) {
					$dir = plugin_dir_path( $key );

					return WP_PLUGIN_DIR . '/' . $dir;
					break;
				}
			}
		}

		return $dir;
	}

	public static function set_html_mail_content_type() {
		return 'text/html';
	}
}
