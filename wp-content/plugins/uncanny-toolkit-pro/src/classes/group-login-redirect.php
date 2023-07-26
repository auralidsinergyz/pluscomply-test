<?php
/**
 * Class GroupLoginRedirect
 *
 * This class handle user login redirect for groups
 *
 *
 * @package     uncanny_learndash_toolkit
 * @subpackage  uncanny_pro_toolkit\GroupLoginRedirect
 * @since       3.0.0 Initial release
 */

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class GroupLoginRedirect
 * @package uncanny_pro_toolkit
 */
class GroupLoginRedirect extends toolkit\Config implements toolkit\RequiredFunctions {
	/**
	 * Class constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_backend_hooks' ) );
	}
	
	/*
	 * Initialize backend actions and filters
	 *
	 * @since 3.0.0
	 */
	public static function run_backend_hooks() {
		
		if ( true === self::dependants_exist() ) {
			/* ADD FILTERS ACTIONS FUNCTION */
			if ( is_admin() ) {
				add_action( 'load-post.php', array( __CLASS__, 'init_metabox' ) );
				add_action( 'load-post-new.php', array( __CLASS__, 'init_metabox' ) );
			}
			if ( ! is_admin() ) {
				add_shortcode( 'uo_group_login_redirect_link', array( __CLASS__, 'group_login_redirect_link' ) );
				add_shortcode( 'uo_group_login_redirect_url', array( __CLASS__, 'group_login_redirect_url' ) );
			}
			add_action( 'wp_login', array( __CLASS__, 'run_frontend_hooks' ), 10, 2 );
		}
	}
	
	/*
	 * Initialize frontend actions and filters
	 *
	 * @since 3.0.0
	 */
	public static function run_frontend_hooks( $user_login, $user ) {
		$login_priority = 99;
		$groups = learndash_get_users_group_ids($user->data->ID);
		$group_priorities = [];
		if( ! empty( $groups ) ){
			foreach( $groups as $group ){
				$redirect_priority = get_post_meta( $group, '_uo_group_redirect_priority', TRUE );
				$redirect_link     = get_post_meta( $group, '_uo_group_redirect_link', TRUE );
				if ( $redirect_link !== '' ){
					$login_priority = $redirect_priority ? $redirect_priority : 99;
					$group_priorities[] = $login_priority;
				}
			}
			
			asort($group_priorities);
			$highest_priority = end($group_priorities);
			add_filter( 'login_redirect', array( __CLASS__, 'login_redirect' ), $highest_priority, 3 );
		}
	}
	
	/**
	 * Redirect user after successful login.
	 *
	 * @param string $redirect_to URL to redirect to.
	 *
	 * @return string
	 */
	public static function login_redirect( $redirect_to, $request, $user ) {
		
		$login_redirect = false;
		$redirect_links = [];
		//is there a user to check?
		//global $user;
		$groups = learndash_get_users_group_ids($user->data->ID);
		if( ! empty( $groups ) ){
			foreach ( $groups as $group ) {
				$redirect_priority = get_post_meta( $group, '_uo_group_redirect_priority', TRUE );
				$redirect_priority = ! empty( $redirect_priority ) ? $redirect_priority : 99;
				$redirect_link     = get_post_meta( $group, '_uo_group_redirect_link', TRUE );
				if ( $redirect_link !== '' ) {
					if ( ! isset( $redirect_links[ $redirect_priority ] ) ) {
						$redirect_links[ $redirect_priority ] = $redirect_link;
					}
				}
			}
		}else{
			return $redirect_to;
        }
		
		
		if ( ! empty( $redirect_links ) ) {
			krsort( $redirect_links );
			foreach ( $redirect_links as $key => $link ) {
				$login_redirect = $link;
				break;
			}
		}
  
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			//check for admins
			if ( in_array( 'administrator', $user->roles ) ) {
				// redirect them to the default place
				return $redirect_to;
			}
			
			if ( ! $login_redirect || '' === $login_redirect ) {
				// if redirect is not set than send them home
				return home_url();
			} else {
				return $login_redirect;
			}
		} else {
			return $redirect_to;
		}
	}
	
	/**
	 * Description of class in Admin View
	 *
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */
	public static function get_details() {
		
		$class_title = esc_html__( 'Group Login Redirect', 'uncanny-pro-toolkit' );
		
		$kb_link = 'https://www.uncannyowl.com/knowledge-base/learndash-group-login-redirect/';
		
		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Adds a group-specific login redirect setting to LearnDash groups. Automatically redirect group members to a specific page on login.', 'uncanny-pro-toolkit' );
		
		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-book"></i><span class="uo_pro_text">PRO</span>';
		
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
		
		return false;
	}
	
	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * @since           3.0.0
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
	 * Meta box initialization.
	 */
	public static function init_metabox() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_metabox' ) );
		add_action( 'save_post', array( __CLASS__, 'save_redirect_settings' ), 20, 2 );
	}
	
	/**
	 * Adds the meta box.
	 */
	public static function add_metabox() {
		add_meta_box(
			'uo-toolkit-group-redirect',
			__( 'Group Login Redirect', 'uncanny-pro-toolkit' ),
			array( __CLASS__, 'render_metabox' ),
			'groups',
			'advanced',
			'high'
		);
		
	}
	
	/**
	 * Renders the meta box.
	 */
	public static function render_metabox( $post ) {
		
		// Add nonce for security and authentication.
		wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );
		
		$group_id = $post->ID;
		
		// Get the location data if its already been entered
		$redirect_link     = get_post_meta( $group_id, '_uo_group_redirect_link', TRUE );
		$redirect_priority = get_post_meta( $group_id, '_uo_group_redirect_priority', TRUE );
		
		// Echo out the field
		ob_start();
		?>
		
		<div class="sfwd_input " id="sfwd-group_login_redirect">
			<span class="sfwd_option_label" style="text-align:right;vertical-align:top;">
				<a class="sfwd_help_text_link" style="cursor:pointer;" title="<?php _e( 'Click for Help!', 'uncanny-pro-toolkit' ) ?>" onclick="toggleVisibility('uo-redirect-link');">
					<img src="<?php echo plugins_url(); ?>/sfwd-lms/assets/images/question.png">
					<label class="sfwd_label textinput"><?php _e( 'Redirect members on login', 'uncanny-pro-toolkit' ) ?></label>
				</a></span><span class="sfwd_option_input">
				<div class="sfwd_option_div">
					<input name="_uo_group_redirect_link" type="text" size="57" value="<?php echo $redirect_link; ?>">
				</div>
				<div class="sfwd_help_text_div" style="display:none" id="uo-redirect-link">
					<label class="sfwd_help_text"><?php _e( 'Specify the URL (relative or absolute) that members will be redirected to upon log in.', 'uncanny-pro-toolkit' ) ?></label>
				</div>
			</span>
			<p style="clear:left"></p>
		</div>
		
		<div class="sfwd_input " id="sfwd-group_login_redirect">
			<span class="sfwd_option_label" style="text-align:right;vertical-align:top;">
				<a class="sfwd_help_text_link" style="cursor:pointer;" title="<?php _e( 'Click for Help!', 'uncanny-pro-toolkit' ) ?>" onclick="toggleVisibility('uo-redirect-priority');">
					<img src="<?php echo plugins_url(); ?>/sfwd-lms/assets/images/question.png">
					<label class="sfwd_label textinput"><?php _e( 'Redirect priority', 'uncanny-pro-toolkit' ) ?></label>
				</a></span><span class="sfwd_option_input">
				<div class="sfwd_option_div">
					<input name="_uo_group_redirect_priority" type="text" size="57" value="<?php echo $redirect_priority; ?>">
				</div>
				<div class="sfwd_help_text_div" style="display:none" id="uo-redirect-priority">
					<label class="sfwd_help_text"><?php _e( 'Specify a priority for this redirect. (optional) Default is 99.', 'uncanny-pro-toolkit' ) ?></label>
				</div>
			</span>
			<p style="clear:left"></p>
		</div>
		
		<?php
		
		echo ob_get_clean();
	}
	
	/**
	 * Handles saving the meta box.
	 *
	 * @param int $post_id Post ID.
	 * @param WP_Post $post Post object.
	 *
	 * @return null
	 */
	public function save_redirect_settings( $post_id, $post ) {
		
		if ( isset( $_POST['custom_nonce'] ) && isset( $_POST['_uo_group_redirect_link'] ) && isset( $_POST['_uo_group_redirect_priority'] ) ) {
			// Add nonce for security and authentication.
			$nonce_name   = isset( $_POST['custom_nonce'] ) ? $_POST['custom_nonce'] : '';
			$nonce_action = 'custom_nonce_action';
			
			// Check if nonce is set.
			if ( ! isset( $nonce_name ) ) {
				return;
			}
			
			// Check if nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
			}
			
			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			
			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}
			
			// Check if not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}
			
			update_post_meta( $post_id, '_uo_group_redirect_link', $_POST['_uo_group_redirect_link'] );
			update_post_meta( $post_id, '_uo_group_redirect_priority', $_POST['_uo_group_redirect_priority'] );
			
		}
		
		return;
		
	}
	
	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function group_login_redirect_link( $atts ) {
		
		$atts = shortcode_atts( [
			'text' => '',
		], $atts );
		
		//is there a user to check?
		$user_id = wp_get_current_user()->ID;
		if ( empty( $user_id ) ) {
			return;
		}
		$groups         = learndash_get_users_group_ids( $user_id );
		$redirect_links = [];
		//If the user is in multiple groups, use redirect priority to determine which URL is output
		//If the user is in multiple groups that have the same redirect priority, just display whichever one is first in the array.
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$redirect_priority = get_post_meta( $group, '_uo_group_redirect_priority', TRUE );
				$redirect_priority = ! empty( $redirect_priority ) ? $redirect_priority : 99;
				$redirect_link     = get_post_meta( $group, '_uo_group_redirect_link', TRUE );
				if ( $redirect_link !== '' ) {
					if ( ! isset( $redirect_links[ $redirect_priority ] ) ) {
						$redirect_links[ $redirect_priority ] = $redirect_link;
					}
				}
			}
		}
		if ( ! empty( $redirect_links ) ) {
			krsort( $redirect_links );
			foreach ( $redirect_links as $key => $link ) {
				$atts['text'] = $atts['text'] !== '' ? $atts['text'] : $link;
				
				return '<a href="' . $link . '" class="uo-group-redirect">' . $atts['text'] . '</a>';
			}
		}
		
		//If the user is in no groups with a redirect URL set, the shortcode should output nothing.
		return '';
	}
	
	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function group_login_redirect_url( $atts ) {
		
		//is there a user to check?
		$user_id = wp_get_current_user()->ID;
		if ( empty( $user_id ) ) {
			return;
		}
		$groups         = learndash_get_users_group_ids( $user_id );
		$redirect_links = [];
		//If the user is in multiple groups, use redirect priority to determine which URL is output
        //If the user is in multiple groups that have the same redirect priority, just display whichever one is first in the array.
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$redirect_priority = get_post_meta( $group, '_uo_group_redirect_priority', TRUE );
				$redirect_priority = ! empty( $redirect_priority ) ? $redirect_priority : 99;
				$redirect_link     = get_post_meta( $group, '_uo_group_redirect_link', TRUE );
				if ( $redirect_link !== '' ) {
					if ( ! isset( $redirect_links[ $redirect_priority ] ) ) {
						$redirect_links[ $redirect_priority ] = $redirect_link;
					}
				}
			}
		}
		if ( ! empty( $redirect_links ) ) {
			krsort( $redirect_links );
			foreach ( $redirect_links as $key => $link ) {
				return $link;
			}
		}
		
		//If the user is in no groups with a redirect URL set, the shortcode should output nothing.
		return '';
	}
}