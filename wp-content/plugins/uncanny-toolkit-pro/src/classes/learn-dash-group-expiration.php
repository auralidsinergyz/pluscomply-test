<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class learnDashGroupExpiration
 * @package uncanny_pro_toolkit
 */
class learnDashGroupExpiration extends toolkit\Config implements toolkit\RequiredFunctions {
	private static $send_email;
	private static $send_email_before_days;

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
		add_action( 'init', array( __CLASS__, 'activate' ) );
		self::$send_email = self::get_settings_value( 'uncanny-group-expiry-send-email', __CLASS__ );
		self::$send_email_before_days = self::get_settings_value( 'uncanny-group-expiry-send-email-days', __CLASS__, 7 );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {

			/* ADD FILTERS ACTIONS FUNCTION */
			# DatePicker (JS)
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_date_picker' ) );

			# Metabox
			add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );
			add_action( 'save_post', array( __CLASS__, 'save_post' ), 100 );

			# Expire Group
			add_action( 'uo-expire-group', array( __CLASS__, 'expire_group' ) );

			# Notification E-mail
			add_action( 'uo-email-group', array( __CLASS__, 'email_group' ) );

			# List
			add_filter( 'manage_groups_posts_columns', array( __CLASS__, 'tweak_columns' ), 20 );
			add_filter( 'manage_edit-groups_sortable_columns', array( __CLASS__, 'tweak_sortable_columns' ), 20 );
			add_action( 'manage_groups_posts_custom_column', array( __CLASS__, 'tweak_columns_content' ), 10, 2 );
			add_action( 'pre_get_posts', array( __CLASS__, 'exp_date_orderby' ) );

			# Email Test
			add_action( 'wp_ajax_UOLDGE_Email_Test', array( __CLASS__, 'emailTest' ) );
			
			#shortcode
			add_shortcode( 'uo_group_expiration_date', [ __CLASS__, 'uo_group_expiration_date' ] );
		}
		
	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Group Expiration', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/learndash-group-expiration/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Set expiration dates for LearnDash groups so that course enrolment for the group is removed on the specified day. Reminder emails can be sent to users advising them of group expiration.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-calendar-times-o"></i><span class="uo_pro_text">PRO</span>';

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
	 * @return boolean || string Return either false or settings html modal
	 *
	 */
	public static function get_class_settings( $class_title ) {

		// Create options
		$options = array(
			array(
				'type'       => 'radio',
				'label'      => esc_html__( 'Send Expiry Email', 'uncanny-pro-toolkit' ),
				'radio_name' => 'uncanny-group-expiry-send-email',
				'radios'     => array(
					array( 'value' => 'yes', 'text' => 'Yes' ),
					array( 'value' => 'no', 'text' => 'No' ),
				),
			),
			array(
				'type'        => 'text',
				'label'       => 'Send email ____ days before expiration',
				'option_name' => 'uncanny-group-expiry-send-email-days',
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
	 *
	 */
	public static function activate() {
		add_role(
			'archived',
			__( 'Archived', 'uncanny-pro-toolkit' ),
			array(
				'read' => true,
			)
		);
	}

	/**
	 *
	 */
	public static function emailTest() {
		$admin_email     = get_option( 'admin_email' );
		$email_title     = get_post_meta( $_POST['post_id'], 'uo-expiration-email-title', true );
		$email_body      = get_post_meta( $_POST['post_id'], 'uo-expiration-email-body', true );
		$expiration_date = get_post_meta( $_POST['post_id'], 'uo-expiration-date', true );
		$group_name      = get_the_title( $_POST['post_id'] );

		$email_body = str_ireplace( array( '%LearnDash Group Name%', '%expiration date%' ), array(
			$group_name,
			$expiration_date,
		), $email_body );

		$user      = wp_get_current_user();
		$user_name = $user->display_name;

		$message = str_ireplace( '%display name%', $user_name, $email_body );
		$message = nl2br( stripcslashes( $message ) );
		$result  = wp_mail( $admin_email, 'Test - ' . $email_title, wpautop( $message ) );

		if ( $result ) {
			echo esc_html__( 'Test message successfully sent to ', 'uncanny-pro-toolkit' ) . $admin_email . '.';
		} else {
			echo esc_html__( 'Failed!!', 'uncanny-pro-toolkit' );
		}
		die;
	}

	/**
	 * @param $columns
	 *
	 * @return mixed
	 */
	public static function tweak_columns( $columns ) {
		$columns['exp_date'] = esc_html__( 'Expiration Date', 'uncanny-pro-toolkit' );

		return $columns;
	}

	public static function tweak_sortable_columns( $columns ) {
		$columns['exp_date'] = 'exp_date';

		return $columns;
	}

	/**
	 * @param $column
	 * @param $post_id
	 */
	public static function tweak_columns_content( $column, $post_id ) {
		if ( 'exp_date' === $column ) {
			self::PrintExpEmailInfo( $post_id );
		}
	}

	/**
	 * @param $query
	 */
	public static function exp_date_orderby( $query ) {
		if ( ! is_admin() ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'exp_date' === $orderby ) {
			$query->set( 'meta_key', 'uo-expiration-date' );
			$query->set( 'orderby', 'meta_value' );
		}
	}

	/**
	 * @param $post_id
	 */
	private static function PrintExpEmailInfo( $post_id ) {
		$system_time = current_time( 'timestamp' );
		$wp_time     = current_time( 'timestamp' );
		$offset      = $system_time - $wp_time;

		$expire_schedule = wp_next_scheduled( 'uo-expire-group', array( (int) $post_id ) );
		$email_schedule  = wp_next_scheduled( 'uo-expiration-date', array( (int) $post_id ) );

		$expire_schedule = ( $expire_schedule ) ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $expire_schedule - $offset ) : false;
		$email_schedule  = ( $email_schedule ) ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $email_schedule - $offset ) : false;

		$expired_date = get_post_meta( $post_id, 'uo-expiration-date-expired', true );
		$emailed_date = get_post_meta( $post_id, 'uo-email-group-sent', true );

		$message = array();

		if ( $expired_date ) {
			$string = sprintf( __( 'Access Expired on %s', 'uncanny-pro-toolkit' ), '<code>' . esc_attr( date_i18n( get_option( 'date_format' ), strtotime( $expired_date ) ) ) . '</code>' );
			printf( '<p>%s</p>', $string );
		} elseif ( $expire_schedule ) {
			//printf( '<p>Access Expires on <code>%s</code></p>', esc_attr( date( 'F j, Y', strtotime( $expire_schedule ) ) ) );
			$string = sprintf( __( 'Access Expires on %s', 'uncanny-pro-toolkit' ), '<code>' . $expire_schedule . '</code>' );
			printf( '<p>%s</p>', $string );
		}

		if ( $emailed_date ) {
			//printf( '<p>Email sent on <code>%s</code></p>', esc_attr( date( 'F j, Y', strtotime( $emailed_date ) ) ) );
			$string = sprintf( __( 'Email sent on %s', 'uncanny-pro-toolkit' ), '<code>' . esc_attr( date_i18n( get_option( 'date_format' ), strtotime( $emailed_date ) ) ) . '</code>' );
			printf( '<p>%s</p>', $string );
		} elseif ( $email_schedule ) {
			//printf( '<p>Email scheduled on <code>%s</code></p>', esc_attr( date( 'F j, Y', strtotime( $email_schedule ) ) ) );
			$string = sprintf( __( 'Email scheduled on %s', 'uncanny-pro-toolkit' ), '<code>' . $email_schedule . '</code>' );
			printf( '<p>%s</p>', $string );
		}
	}

	/**
	 *
	 */
	public static function enqueue_date_picker() {
		global $pagenow, $wp_styles;

		if ( ( 'post-new.php' === $pagenow && isset( $_GET['post_type'] ) && 'groups' === $_GET['post_type'] ) || ( 'post.php' === $pagenow && isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-ui-datepicker-ext', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css' );
		}
	}

	# Metabox

	/**
	 *
	 */
	public static function add_meta_box() {
		add_meta_box(
			'expiration_date',
			__( 'Expiration Date', 'uncanny-pro-toolkit' ),
			array( __CLASS__, 'expiration_date' ),
			'groups'
		);
		if ( 'yes' === self::$send_email ) {
			add_meta_box(
				'expiration_email',
				__( 'Expiration Email', 'uncanny-pro-toolkit' ),
				array( __CLASS__, 'expiration_email' ),
				'groups'
			);
		}
	}

	# Metabox - Date Setting

	/**
	 * @param $post
	 */
	public static function expiration_date( $post ) {

		$test_checked = get_post_meta( $post->ID, 'uo-is-test', true ) ? 'checked="checked"' : '';
		$exp_date     = get_post_meta( $post->ID, 'uo-expiration-date', true );
		if ( ! empty( $exp_date ) ) {
			$exp_date = date( 'm/d/Y', strtotime( $exp_date ) );
		} else {
			$exp_date = '';
		}
		printf( '<input type="text" id="exp-date" name="exp-date" value="%s" />', $exp_date );
		//printf( '&nbsp;&nbsp;&nbsp;<label for="is_test"><input type="checkbox" id="is_test" name="is_test" %s /> Make this Group as Test ( 1 minute )</label>', $test_checked );

		self::PrintExpEmailInfo( $post->ID );

		?>
		<script>
            jQuery(document).ready(function () {
                jQuery('#exp-date').datepicker({
                    dateFormat: 'mm/dd/yy'
                })
            })
		</script>
		<?php
	}

	/**
	 * @param $post
	 */
	public function expiration_email( $post ) {

		$email_title = sprintf( esc_html__( '%s Course Access Expiring', 'uncanny-pro-toolkit' ), get_bloginfo( 'name' ) );
		$email_body  = sprintf( esc_html__( "Hi %display name%,\n\nThis is a courtesy email to let you know that your access to %s as part of the %LearnDash Group Name% group is expiring on %expiration date%. Your access to %s courses will be removed on that date.", 'uncanny-pro-toolkit' ),
			get_bloginfo( 'name' ),
			get_bloginfo( 'name' ) );

		$title = get_post_meta( $post->ID, "uo-expiration-email-title", true );
		$title = $title ? $title : $email_title;

		$body = get_post_meta( $post->ID, "uo-expiration-email-body", true );
		$body = $body ? $body : $email_body;
		?>
		<p>This email will be sent to all group members <?php echo self::$send_email_before_days;?> days before the group expiry date.</p>
		<input type="text" name="uc_exp_email_title" value="<?php echo $title ?>" id="uc_exp_email_title"
			   style="width:100%; font-size:20px; margin-bottom:15px;"/>

		<textarea name="uc_exp_email_body" id="uc_exp_email_body"
				  style="width:100%; height:300px; font-size:14px; padding:10px;"><?php echo $body ?></textarea>

		<br/>
		<b>Available Variables:</b>
		<p>%Display Name%<br/>
			%LearnDash Group Name%<br/>
			%Expiration Date%</p>
		<br/>

		<!--<a href="#" id="email_test">Test Email</a> <span id="test_result"></span>-->
		<button id="email_test">Test Email</button> <span id="test_result"></span>

		<script>
            jQuery('#email_test').click(function (e) {
                e.preventDefault()

                var data = {
                    'action': 'UOLDGE_Email_Test',
                    'post_id': <?php echo $post->ID; ?>
                }

                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#test_result').html(response)
                })
            })
		</script>
		<?php
	}

	# Save Setting

	/**
	 * @param $post_id
	 */
	public static function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['post_type'] ) || 'groups' === ! $_POST['post_type'] ) {
			return;
		}

		# Test Mode
		$is_test = isset( $_POST['is_test'] );
		update_post_meta( $post_id, 'uo-is-test', $is_test );

		if ( 'yes' === self::$send_email ) {
			update_post_meta( $post_id, 'uo-expiration-email-title', $_POST['uc_exp_email_title'] );
			update_post_meta( $post_id, 'uo-expiration-email-body', $_POST['uc_exp_email_body'] );
		}

		if ( learndash_group_enrolled_courses( $post_id ) ) {
			$user_ids = learndash_get_groups_user_ids( $post_id );

			foreach ( $user_ids as $user_id ) {
				$user = get_user_by( 'id', $user_id );

				if ( 'archived' === $user->roles[0] ) {
					wp_update_user( array( 'ID' => $user_id, 'role' => 'subscriber' ) );
				}
			}
		}

		# Not changed => End
		if ( ! empty( $_POST['exp-date'] ) && $_POST['exp-date'] === get_post_meta( $post_id, 'uo-expiration-date', true ) && ! $is_test ) {
			return;
		}
		if ( empty( $_POST['exp-date'] ) ) {
			delete_post_meta( $post_id, 'uo-expiration-date' );
			wp_clear_scheduled_hook( 'uo-expire-group', array( $post_id ) );
			wp_clear_scheduled_hook( 'uo-email-group', array( $post_id ) );
		} else {
			# Save
			$exp_date = date( 'Y-m-d', strtotime( $_POST['exp-date'] ) );
			update_post_meta( $post_id, 'uo-expiration-date', $exp_date );

			# Reset Schedule
			wp_clear_scheduled_hook( 'uo-expire-group', array( $post_id ) );
			wp_clear_scheduled_hook( 'uo-email-group', array( $post_id ) );

			if ( empty( $_POST['exp-date'] ) ) {
				return;
			}

			# Milestones
			$system_time = current_time( 'timestamp' );
			$wp_time     = current_time( 'timestamp' );
			$offset      = $system_time - $wp_time;

			$timestamp = strtotime( $_POST['exp-date'] . 'T00:00:00' ) + $offset;
			//$email     = $timestamp - 60 * 60 * 24 * 30;
			//Send expiry email 7 days before!
			$email = $timestamp - 60 * 60 * 24 * self::$send_email_before_days;

			$now = current_time( 'timestamp' );

			if ( $is_test ) {
				wp_schedule_single_event( $now + 60, 'uo-expire-group', array( $post_id ) );
				delete_post_meta( $post_id, 'uo-expiration-date-expired' );

				if ( 'yes' === self::$send_email ) {
					wp_schedule_single_event( $now + 30, 'uo-email-group', array( $post_id ) );
					delete_post_meta( $post_id, 'uo-email-group-sent' );
				}

			} else {
				if ( $email && $now <= $email && 'yes' === self::$send_email ) {
					wp_schedule_single_event( $email, 'uo-email-group', array( $post_id ) );
					delete_post_meta( $post_id, 'uo-email-group-sent' );
				}

				if ( $timestamp && $now <= $timestamp ) {
					wp_schedule_single_event( $timestamp, 'uo-expire-group', array( $post_id ) );
					delete_post_meta( $post_id, 'uo-expiration-date-expired' );
				}
			}
		}
	}

	# Delete Courses (auto)

	/**
	 * @param $post_id
	 */
	public static function expire_group( $post_id ) {
		if ( function_exists( 'learndash_group_enrolled_courses' ) ) {
			# Make Them Archived.
			$group_enrolled_courses = learndash_group_enrolled_courses( $post_id );
			$user_ids               = learndash_get_groups_user_ids( $post_id );

			/*foreach ( $user_ids as $user_id ) {
				$user = get_user_by( 'id', $user_id );

				if ( $user->roles[0] == 'subscriber' ) {
					wp_update_user( array( 'ID' => $user_id, 'role' => 'archived' ) );
				}
			}*/

			foreach ( $group_enrolled_courses as $course_id ) {
				delete_post_meta( $course_id, 'learndash_group_enrolled_' . $post_id );
			}

			update_post_meta( $post_id, 'uo-expiration-date-expired', current_time( get_option( 'date_format' ) . ' H:i:s' ) );
		}

		wp_clear_scheduled_hook( 'uo-expire-group', array( $post_id ) );
		wp_clear_scheduled_hook( 'uo-email-group', array( $post_id ) );
	}

	# Email (auto)

	/**
	 * @param $post_id
	 */
	public static function email_group( $post_id ) {
		if ( function_exists( 'learndash_group_enrolled_courses' ) ) {
			$user_ids = array_merge( learndash_get_groups_user_ids( $post_id ), learndash_get_groups_administrator_ids( $post_id ) );
			$user_ids = array_unique( $user_ids );

			$email_title     = get_post_meta( $post_id, 'uo-expiration-email-title', true );
			$email_body      = get_post_meta( $post_id, 'uo-expiration-email-body', true );
			$expiration_date = get_post_meta( $post_id, 'uo-expiration-date', true );
			$group_name      = get_post( $post_id );
			$group_name      = $group_name->post_title;

			$email_body = str_ireplace( array( '%LearnDash Group Name%', '%expiration date%' ), array(
				$group_name,
				$expiration_date
			), $email_body );

			foreach ( $user_ids as $user_id ) {
				$user      = get_userdata( $user_id );
				$email     = $user->user_email;
				$user_name = $user->display_name;

				$message = str_ireplace( '%display name%', $user_name, $email_body );
				$message = nl2br( stripcslashes( $message ) );
				$sub     = $email_title;

				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

				$headers .= 'From: ' . get_bloginfo( 'name' ) . ' <' . get_bloginfo( 'admin_email' ) . '>';
				if ( 'yes' === self::$send_email ) {
					$result = wp_mail( $email, $sub, wpautop( $message ), $headers );
				}
			}
		}

		update_post_meta( $post_id, 'uo-email-group-sent', current_time( get_option( 'date_format' ) . ' H:i:s' ) );
		wp_clear_scheduled_hook( 'uo-email-group', array( $post_id ) );
	}
	
	
	/**
     * Shortcode callback function.
     *
	 * @param $atts
	 *
	 * @return string
	 */
	public static function uo_group_expiration_date( $atts ) {
		$atts = shortcode_atts( array(
			'group_id' => '',
		), $atts, 'uo_group_expiration_date' );
		
		if ( ! empty( $atts['group_id'] ) ) {
			$group_id = $atts['group_id'];
			$user_id  = get_current_user_id();
			
			if ( empty( $user_id ) ) {
				return '';
			}
			// get user's groups
			$user_groups_id = learndash_get_users_group_ids( $user_id );
			if ( ! in_array( $group_id, $user_groups_id ) ) {
				return '';
			}
			
			$expiration_date = get_post_meta( $group_id, 'uo-expiration-date', true );
			if ( ! empty( $expiration_date ) ) {
				$string = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $expiration_date ) );
				return $string;
			}
		} else {
			$user_id = get_current_user_id();
			
			if ( empty( $user_id ) ) {
				return '';
			}
		    // get user's groups
			$user_groups_id = learndash_get_users_group_ids( $user_id );
			
			if ( ! empty( $user_groups_id ) ) {
				$final_exp_date = '';
				foreach ( $user_groups_id as $group_id ) {
					$expiration_date = get_post_meta( $group_id, 'uo-expiration-date', true );
					if ( ! empty( $final_exp_date ) && ! empty( $expiration_date ) ) {
						return '';
					}
					
					if ( ! empty( $expiration_date ) ) {
						$final_exp_date = $expiration_date;
					}
				}
				
				if ( ! empty( $final_exp_date ) ) {
					$string = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $final_exp_date ) );
					return $string;
				}
				
			}
        }
		
        return '';
    }
}