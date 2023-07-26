<?php

namespace uncanny_pro_toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}


use TCPDF;
use uncanny_learndash_toolkit as toolkit;

class GeneratePDFEmail extends toolkit\Config implements toolkit\RequiredFunctions {
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
			add_filter( 'learndash_quiz_completed_email', array( __CLASS__, 'add_cert_pdf' ), 999 );
			add_filter( 'learndash_quiz_email', array( __CLASS__, 'add_cert_pdf' ), 999 );
		}

	}


	/**
	 *
	 * @static
	 * @return mixed
	 */
	public static function get_details() {
		$class_title = esc_html__( 'Email Quiz Certificates', 'uncanny-pro-toolkit' );

		$kb_link = 'https://www.uncannyowl.com/knowledge-base/send-certificates-by-email/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Sends a copy of certificates earned from quiz completion and saves certificates on the server.', 'uncanny-pro-toolkit' );

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
	 * HTML for modal to create settings
	 *
	 * @param String
	 *
	 * @return array || string Return either false or settings html modal
	 *
	 */
	public static function get_class_settings( $class_title ) {

		// Create options
		$options = array(

			array(
				'type'       => 'html',
				'inner_html' => sprintf( '<h4 style="margin:0">To use this module, quiz emails to the learner must be enabled. <a target="_blank" href="%s">CLICK HERE</a> to turn them on and to change the email subject and body that are used in student emails (values can be changed under <i>E-Mail Settings > User e-mail settings</i>).</h4>', admin_url( 'admin.php?page=ldAdvQuiz&module=globalSettings' ) ),
			),
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Do not store certificates on server', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-pdf-certificate-dont-store',
			),
			array(
				'type'       => 'html',
				'inner_html' => 'By default, certificates are going to be stored at: &lt;site root&gt;/wp-content/uploads/user-certificates/',
			),
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Send Certificate to Site Admin?', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-pdf-certificate-admin',
			),
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Send Certificate to Group Leader(s)?', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-pdf-certificate-group-leader',
			),
			array(
				'type'        => 'text',
				'placeholder' => 'jon@doe.com, doe@jon.com',
				'label'       => esc_html__( 'CC Certificate To (comma separated)', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-pdf-certificate-cc-emails',
			),
			array(
				'type'        => 'text',
				'placeholder' => '%User% has earned a certificate',
				'label'       => esc_html__( 'Admin/Group Leader Email Subject', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-pdf-certificate-subject-line',
			),
			array(
				'type'        => 'textarea',
				'placeholder' => "%User% in Group %Group Name% has earned a certificate for completing %Quiz Name%.\r\n\r\nScore: \$result",
				'label'       => esc_html__( 'Admin/Group Leader Email Body', 'uncanny-pro-toolkit' ),
				'option_name' => 'uncanny-pdf-certificate-email-body',
			),
			array(
				'type'       => 'html',
				'inner_html' => '<strong>Available variables for email subject & body</strong><br /><ul><li><strong>%First Name%</strong> &mdash; Prints User First Name</li><li><strong>%Last Name%</strong> &mdash; Prints User Last Name</li><li><strong>%User%</strong> &mdash; Prints User Screen Name</li><li><strong>%Group Name%</strong> &mdash; Prints Group Name <!--<em>Only Available for Group Leader</em>--></li><li><strong>%Quiz Name%</strong> &mdash; Prints Quiz Name</li><li><strong>%User Email%</strong> &mdash; Prints User Email</li><li><strong>$result</strong> &mdash; Prints Quiz result</li></ul>',
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
	 *
	 *
	 * @static
	 * @return mixed
	 */
	static function dependants_exist() {
		/* Checks for LearnDash */
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		return true;
	}

	/**
	 * @param $email_params
	 *
	 * @return string
	 */
	public function add_cert_pdf( $email_params ) {
		//global $current_user;

		$current_user      = wp_get_current_user();
		$cc                = '';
		$file              = '';
		$http_link_to_file = '';
		$setup_parameters  = '';

		//self::trace_logs( $_POST, 'Post', 'pdf' );
		//        the certificate_link function uses the gobal post which is not set for this ajax call
		//        so we have to set it from the $_post values
		if ( isset( $_POST['quiz'] ) ) {
			//global $post;
			$post    = get_post( absint( $_POST['quiz'] ) );
			$quiz_id = $post->ID;

			/* Setting up variables for PDF by passing Quiz ID ($post->ID) and current logged in user ID */
			$setup_parameters = self::setup_parameters( $post->ID, $current_user->ID, $_POST );

			/* IF Print Certificate is allowed ( logic grabbed from Quiz Pro Print Certificate Part ) */
			if ( 1 === $setup_parameters['print-certificate'] ) {

				$email_params['msg']      .= "\n\n";
				$email_params['msg']      .= __( 'Your certificate is attached with this email.', 'uncanny-pro-toolkit' );
				self::$current_time_stamp = current_time( 'timestamp' );
				do_action( 'uo_quiz_completion_time', self::$current_time_stamp, $post->ID );

				$certificate_post = $setup_parameters['certificate-post'];
				$save_path        = apply_filters( 'uo_quiz_certificate_save_path', WP_CONTENT_DIR . '/uploads/user-certificates/' );
				$completion_time  = self::$current_time_stamp;
				$quiz_title       = get_the_title( $post->ID );
				/* Creating a fileName that is going to be stored on the server. Certificate-QUIZID-USERID-NONCE_String */
				$file_name = sanitize_title( $current_user->ID . '-' . $quiz_title . '-' . wp_create_nonce( $completion_time ) );
				$file_name = apply_filters( 'uo_quiz_completion_certificate_filename', $file_name, $current_user->ID, $quiz_id, $certificate_post, self::$current_time_stamp );

				if ( ! file_exists( $save_path ) ) {
					mkdir( $save_path, 0755 );
				}

				$generate_pdf_args = apply_filters( 'uo_quiz_completion_generate_pdf_args', [
					'certificate_post' => $certificate_post,
					'save_path'        => $save_path,
					//'plugin_dir'       => $plugin_dir,
					'file_name'        => $file_name,
					'quiz_id'          => $quiz_id,
					'completion_time'  => $completion_time,
					'parameters'       => $setup_parameters,
				] );

				$uo_generate_quiz_certs = apply_filters( 'uo_generate_quiz_certificate', true, $generate_pdf_args, $quiz_id, $current_user->ID );
				if ( $uo_generate_quiz_certs ) {
					$file = self::generate_pdf( $generate_pdf_args );
				}

				$certificate_id = $certificate_post;
				$path_on_server = $file;
				$quiz_post      = $post;
				$quiz_results   = $_POST['results']['comp'];
				$current_time   = self::$current_time_stamp;
				//self::trace_logs( $file, 'File', 'pdf' );
				do_action( 'uo_quiz_certificate', $path_on_server, $quiz_post, $quiz_results, $certificate_id, $current_user, $current_time );

				//$http_link_to_file = WP_CONTENT_URL . '/uploads/user-certificates/' . $file_name . '.pdf';
				$http_link_to_file = apply_filters( 'uo_quiz_certificate_http_url', WP_CONTENT_URL . '/uploads/user-certificates/' . $file_name . '.pdf' );

				do_action( 'uo_quiz_certificate_url', $http_link_to_file, $quiz_post, $quiz_results, $certificate_id, $current_user, $current_time );
				//self::trace_logs( $http_link_to_file, 'HTTP Link', 'pdf' );

				$course_id = $setup_parameters['course-id'];
				$meta_name = '_sfwd-quizzes-pdf-quiz-' . $course_id;

				//Retrieve any existing certificates from USER
				$certs         = array();
				$current_certs = get_user_meta( $current_user->ID, $meta_name, true );

				if ( ! empty( $current_certs ) ) {
					$current_certs[][ self::$current_time_stamp ] = $http_link_to_file;
					update_user_meta( $current_user->ID, $meta_name, $current_certs );
				} else {
					$certs[][ self::$current_time_stamp ] = $http_link_to_file;
					add_user_meta( $current_user->ID, $meta_name, $certs );
				}
			} else {
				return $email_params;
			}
		}
		if ( empty( $file ) ) {
			return $email_params;
		} else {
			/* Sending Final Email with Attachment & PDF Link! */
			if ( key_exists( 'headers', $email_params ) && ! empty( $email_params['headers'] ) ) {
				$headers = $email_params['headers'];
			} else {
				$headers   = array();
				$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . get_bloginfo( 'admin_email' ) . '>';
			}
			//self::trace_logs( $headers, 'Headers', 'pdf' );
			global $wpdb;
			$user           = wp_get_current_user();
			$is_admin       = self::get_settings_value( 'uncanny-pdf-certificate-admin', __CLASS__ );
			$is_group_admin = self::get_settings_value( 'uncanny-pdf-certificate-group-leader', __CLASS__ );
			$group_msg      = self::get_settings_value( 'uncanny-pdf-certificate-email-body', __CLASS__ );
			$group_sub      = self::get_settings_value( 'uncanny-pdf-certificate-subject-line', __CLASS__ );
			$cc             = self::get_settings_value( 'uncanny-pdf-certificate-cc-emails', __CLASS__ );

			if ( true === strpos( $cc, ',' ) ) {
				$cc = explode( ',', $cc );
			}

			if ( empty( $group_msg ) ) {
				$group_msg = "%User% in Group %Group Name% has earned a certificate for completing %Quiz Name%.\r\n\r\nScore: \$result";
			}

			if ( empty( $group_sub ) ) {
				$group_sub = '%User% has earned a certificate';
			}

			$user_groups = learndash_get_users_group_ids( $user->ID, true );
			$ugroups     = array();
			if ( $user_groups ) {
				foreach ( $user_groups as $gr ) {
					$ugroups[] = get_the_title( $gr );
				}
				$ugroups = join( ', ', $ugroups );
			} else {
				$ugroups[] = '';
			}
			$group_msg = str_ireplace( '%First Name%', $user->first_name, $group_msg );
			$group_msg = str_ireplace( '%Last Name%', $user->last_name, $group_msg );
			$group_msg = str_ireplace( '%User%', $user->display_name, $group_msg );
			$group_msg = str_ireplace( '%User Email%', $user->user_email, $group_msg );
			$group_msg = str_ireplace( '%Quiz Name%', $setup_parameters['quiz-name'], $group_msg );
			$group_msg = str_ireplace( '$result', $setup_parameters['result'] . '%', $group_msg );
			$group_msg = str_ireplace( '%Group Name%', $ugroups, $group_msg );
			$group_msg = do_shortcode( stripslashes( $group_msg ) );

			$group_sub = str_ireplace( '%First Name%', $user->first_name, $group_sub );
			$group_sub = str_ireplace( '%Last Name%', $user->last_name, $group_sub );
			$group_sub = str_ireplace( '%User%', $user->display_name, $group_sub );
			$group_sub = str_ireplace( '%User Email%', $user->user_email, $group_sub );
			$group_sub = str_ireplace( '%Group Name%', $ugroups, $group_sub );
			$group_sub = str_ireplace( '%Quiz Name%', $setup_parameters['quiz-name'], $group_sub );
			$group_sub = str_ireplace( '$result', $setup_parameters['result'] . '%', $group_sub );

			//Sending Email To User!
			$change_content_type = apply_filters( 'uo_apply_wp_mail_content_type', true );
			if ( $change_content_type ) {
				add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_html_mail_content_type' ) );
			}
			$email_params['msg'] = do_shortcode( stripslashes( $email_params['msg'] ) );
			wp_mail( $email_params['email'], $email_params['subject'], wpautop( $email_params['msg'] ), $headers, $file );

			if ( 'on' === $is_admin ) {
				$group_msg           = str_ireplace( '%Group Name%', $ugroups, $group_msg );
				$change_content_type = apply_filters( 'uo_apply_wp_mail_content_type', true );
				if ( $change_content_type ) {
					add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_html_mail_content_type' ) );
				}
				wp_mail( get_bloginfo( 'admin_email' ), $group_sub, wpautop( $group_msg ), $headers, $file );
			}

			if ( 'on' === $is_group_admin ) {
				$get_leaders       = array();
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
						$group_msg = str_ireplace( '%Group Name%', get_the_title( $key ), $group_msg );
						$group_sub = str_ireplace( '%Group Name%', get_the_title( $key ), $group_sub );
						//$email     = join( ', ', $value );
						$change_content_type = apply_filters( 'uo_apply_wp_mail_content_type', true );
						if ( $change_content_type ) {
							add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_html_mail_content_type' ) );
						}
						wp_mail( $value, $group_sub, wpautop( $group_msg ), $headers, $file );

					}
				}
			}

			if ( ! empty( $cc ) ) {
				$group_msg           = str_ireplace( '%Group Name%', $ugroups, $group_msg );
				$change_content_type = apply_filters( 'uo_apply_wp_mail_content_type', true );
				if ( $change_content_type ) {
					add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_html_mail_content_type' ) );
				}
				wp_mail( $cc, $group_sub, wpautop( $group_msg ), $headers, $file );
			}

			if ( 'on' === self::get_settings_value( 'uncanny-pdf-certificate-dont-store', __CLASS__ ) ) {
				if ( file_exists( $file ) ) {
					unlink( $file );
				}
			}
		}

		return '';
	}


	/**
	 * @param $id
	 * @param $user_id
	 * @param $get_request_results
	 *
	 * @return array
	 */
	public static function setup_parameters( $id, $user_id, $get_request_results ) {
		$setup_parameters = array();
		//$post = get_post( $ID );

		$meta = get_post_meta( $id, '_sfwd-quiz' );

		$setup_parameters['userID']            = $user_id;
		$setup_parameters['quiz-id']           = $id;
		$setup_parameters['quiz-name']         = get_the_title( $id );
		$setup_parameters['course-id']         = key_exists( 'course_id', $get_request_results ) ? $get_request_results['course_id'] : 0;
		$setup_parameters['print-certificate'] = 0;
		$setup_parameters['timespent']         = $get_request_results['timespent'];
		$setup_parameters['points']            = $get_request_results['results']['comp']['points'];
		$setup_parameters['correctQuestions']  = $get_request_results['results']['comp']['correctQuestions'];

		if ( is_array( $meta ) && ! empty( $meta ) ) {
			$meta = $meta[0];
			if ( is_array( $meta ) && ( ! empty( $meta['sfwd-quiz_certificate'] ) ) ) {
				//Setting Certificate Post ID
				$setup_parameters['certificate-post'] = $meta['sfwd-quiz_certificate'];
				//Setting Course Post ID
				if ( 0 === absint( $setup_parameters['course-id'] ) ) {
					$setup_parameters['course-id'] = $meta['sfwd-quiz_course'];
				}
			}
		}

		if ( empty( $setup_parameters['certificate-post'] ) ) {
			return $setup_parameters;
		}

		$result                = $get_request_results['results']['comp']['result'];
		$certificate_threshold = ( learndash_get_setting( $id, 'threshold' ) * 100 );

		$setup_parameters['result']                = $result;
		$setup_parameters['certificate_threshold'] = $certificate_threshold;

		if ( ( isset( $result ) && $result >= $certificate_threshold ) ) {
			// All Set. User & Quiz good to go to print pdf certificate.
			$setup_parameters['print-certificate'] = 1;
		}

		return apply_filters( 'uo_quiz_completion_setup_parameters', $setup_parameters, $id, $user_id, $setup_parameters['certificate-post'] );
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
		$user            = wp_get_current_user();
		$parameters      = $args['parameters'];
		$completion_time = $args['completion_time'];
		$quiz_id         = $args['quiz_id'];
		$post_id         = intval( $certificate_id );
		$post_data       = get_post( $post_id );
		$monospaced_font = '';
		$config_lang     = 'eng';
		$ratio           = 1.25;
		$title           = strip_tags( $post_data->post_title );
		//$content             = $post_data->post_content;
		$target_post_id      = $post_id;
		$get_by_http_request = 0;
		$shortcode           = 'parse';
		$l                   = '';
		global $post;
		$post = $post_data;
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

		$tag       = array();
		$tags      = '';
		$tags_data = wp_get_post_tags( $post_data->ID );

		if ( $tags_data ) {
			foreach ( $tags_data as $val ) {
				$tag[] = $val->name;
			}
			$tags = implode( ' ', $tag );
		}

		if ( 1 === $get_by_http_request ) {
			$permalink_url = get_permalink( $post_id );
			$response_data = wp_remote_get( $permalink_url );
			$content       = preg_replace( '|^.*?<!-- post2pdf-converter-begin -->(.*?)<!-- post2pdf-converter-end -->.*?$|is', '$1', $response_data['body'] );
		} else {
			$content = $post_data->post_content;

			// For qTranslate
			/*if ( function_exists( 'qtrans_use' ) && ! empty( $this->q_config['language'] ) ) {
				$content = qtrans_use( $this->q_config['language'], $content, true );
			}*/
		}

		if ( ! empty( $_GET['lang'] ) ) {
			$config_lang = substr( esc_html( $_GET['lang'] ), 0, 3 );
		}

		if ( ! empty( $_GET['file'] ) ) {
			$filename_type = $_GET['file'];
		}

		if ( 'title' === $filename_type && 0 === $target_post_id ) {
			$filename = $post_data->post_title;

			// For qTranslate
			/*if ( function_exists( 'qtrans_use' ) && ! empty( $this->q_config['language'] ) ) {
				$filename = qtrans_use( $this->q_config['language'], $filename, false );
			}*/
		} else {
			$filename = $post_id;
		}

		$filename = substr( $filename, 0, 255 );

		$chached_filename = '';

		if ( 0 !== $target_post_id ) {
			$filename = WP_CONTENT_DIR . '/tcpdf-pdf/' . $filename;
		}

		// For qTranslate
		/*if ( function_exists( 'qtrans_use' ) && ! empty( $this->q_config['language'] ) ) {
			$filename = $filename . '_' . $this->q_config['language'];
		}*/

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


		preg_match_all( '/\[quizinfo(.+?)\]/', $content, $matches );
		//self::trace_logs( $matches, 'Matches', 'pdf' );

		if ( $matches ) {
			foreach ( $matches[0] as $quizinfo ) {
				if ( strpos( $quizinfo, 'timestamp' ) ) {
					$qinfo = str_replace( 'show="timestamp"', '', $quizinfo );
					preg_match( '/\"(.*)\"/', $qinfo, $date_format );
					//self::trace_logs( $date_format, 'Date Format', 'pdf' );
					if ( $date_format ) {
						$date = date_i18n( $date_format[1], $completion_time );
					} else {
						$date = date_i18n( 'F d, Y', $completion_time );
					}
					$content = str_ireplace( $quizinfo, $date, $content );
				}
				if ( strpos( $quizinfo, 'timespent' ) ) {
					$content = str_ireplace( $quizinfo, learndash_seconds_to_time( $parameters['timespent'] ), $content );
				}
				if ( strpos( $quizinfo, 'percentage' ) ) {
					$content = str_ireplace( $quizinfo, $parameters['result'], $content );
				}
				if ( strpos( $quizinfo, 'points' ) ) {
					$content = str_ireplace( $quizinfo, $parameters['points'], $content );
				}
				if ( strpos( $quizinfo, 'total_points' ) ) {
					$content = str_ireplace( $quizinfo, $parameters['correctQuestions'], $content );
				}
				if ( strpos( $quizinfo, 'pass' ) ) {
					$content = str_ireplace( $quizinfo, 'Yes', $content );
				}
				if ( strpos( $quizinfo, 'count' ) ) {
					$content = str_ireplace( $quizinfo, $parameters['points'], $content );
				}
				if ( strpos( $quizinfo, 'score' ) ) {
					$content = str_ireplace( $quizinfo, $parameters['points'], $content );
				}
			}
		}

		$content = preg_replace( '/(\[usermeta)/', '[usermeta user_id="' . $user->ID . '" ', $content );

		$content = apply_filters( 'uo_generate_quiz_certificate_content', $content, $user->ID, $quiz_id, $parameters['course-id'] );

		// Delete shortcode for POST2PDF Converter
		$content = preg_replace( '|\[pdf[^\]]*?\].*?\[/pdf\]|i', '', $content );

		// For WP Code Highlight
		if ( function_exists( 'wp_code_highlight_filter' ) ) {
			$content = wp_code_highlight_filter( $content );
			$content = preg_replace( '/<pre[^>]*?>(.*?)<\/pre>/is', '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		}

		// Parse shortcode before applied WP default filters
		if ( 'parse' === $shortcode && 1 !== $get_by_http_request ) {

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

			// For CodeColorer(GeSHi)
			/*if ( class_exists( 'CodeColorerLoader' ) ) {
				$content = preg_replace_callback( "/\[cc[^\]]*?lang=['\"][^\]]*?\](.*?)\[\/cc\]/is", array(
					$this,
					post2pdf_conv_sourcecode_wrap_pre_and_esc
				), $content );
			}*/
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

			// For CodeColorer(GeSHi)
			/*if ( class_exists( 'CodeColorerLoader' ) ) {
				$content = preg_replace_callback( "/\[cc[^\]]*?lang=['\"][^\]]*?\](.*?)\[\/cc\]/is", array(
					$this,
					post2pdf_conv_sourcecode_esc
				), $content );
			}*/
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
			$pdf->setHeaderFont( Array( $font, '', PDF_FONT_SIZE_MAIN ) );
		}

		if ( 1 === $footer_enable ) {
			$pdf->setFooterFont( Array( $font, '', PDF_FONT_SIZE_DATA ) );
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

		// Parse shortcode after applied WP default filters
		if ( 'parse' === $shortcode ) {

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

		// For WP QuickLaTeX
		/*if ( function_exists( 'quicklatex_parser' ) ) {
			$content = preg_replace_callback( '/(<p class="ql-(center|left|right)-displayed-equation" style="line-height: )([0-9]+?)(px;)(">)/i', array(
				$this,
				post2pdf_conv_qlatex_displayed_equation
			), $content );
			$content = str_replace( '<p class="ql-center-picture">', '<p class="ql-center-picture" style="text-align: center;"><span class="ql-right-eqno"> &nbsp; <\/span><span class="ql-left-eqno"> &nbsp; <\/span>', $content );
		}*/

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
		if ( '' !== $img_file ) {

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
			$pdf->Image( $img_file, $x = '0', $y = '0', $w = $pageW, $h = $pageH, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = array() );

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
//		self::trace_logs( $full_path, 'Full Path' );
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

	/**
	 * @return string
	 */
	public static function set_html_mail_content_type() {
		return 'text/html';
	}
}