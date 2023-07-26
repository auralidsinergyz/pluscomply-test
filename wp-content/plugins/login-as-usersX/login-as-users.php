<?php
/*
Plugin Name: Login As Users
Description: Using this plugin, admin can access user's account in one click.
Author: Geek Code Lab
Version: 1.3
Author URI: https://geekcodelab.com/
Text Domain: gwslau_login_as_user
*/

if(!defined('ABSPATH')) exit;

if(!defined("GWSLAU_PLUGIN_DIR_PATH"))
	define("GWSLAU_PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));	
	
if(!defined("GWSLAU_PLUGIN_URL"))
	define("GWSLAU_PLUGIN_URL",plugins_url().'/'.basename(dirname(__FILE__)));	

define("GWSLAU_BUILD",'1.3');

register_activation_hook( __FILE__, 'gwslau_reg_activation_callback' );
function gwslau_reg_activation_callback() {
	$site_domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	setcookie("gwslau_new_user_id", "", time() - 3600, '/', $site_domain, false);
	setcookie("gwslau_old_user_id", "", time() - 3600, '/', $site_domain, false);
	
	if(isset($_SESSION['gwslau_new_user_id'])){
		unset($_SESSION['gwslau_new_user_id']);
	}
	if(isset($_SESSION['gwslau_old_user_id'])){
		unset($_SESSION['gwslau_old_user_id']);
	}
	
	$gwslau_loginas_status = 1;
	$gwslau_loginas_role = array("Administrator" => "Administrator");
	$gwslau_loginas_for = array("users_page" => "users_page", "users_profile_page" => "users_profile_page", "orders_page" => "orders_page", "order_edit_page" => "order_edit_page");
	$gwslau_loginas_redirect = '';
	$gwslau_loginas_name_show = 'user_login';
	$gwslau_loginas_sticky_position = 'left';
	$def_data = array();
	$setting = get_option('gwslau_loginas_options');
	if(!isset($setting['gwslau_loginas_status']))  $def_data['gwslau_loginas_status'] = $gwslau_loginas_status;	
	if(!isset($setting['gwslau_loginas_role']))  $def_data['gwslau_loginas_role'] = $gwslau_loginas_role;
	if(!isset($setting['gwslau_loginas_for']))  $def_data['gwslau_loginas_for'] = $gwslau_loginas_for;
	if(!isset($setting['gwslau_loginas_redirect']))  $def_data['gwslau_loginas_redirect'] = $gwslau_loginas_redirect;
	if(!isset($setting['gwslau_loginas_name_show']))  $def_data['gwslau_loginas_name_show'] = $gwslau_loginas_name_show;
	if(!isset($setting['gwslau_loginas_sticky_position']))  $def_data['gwslau_loginas_sticky_position'] = $gwslau_loginas_sticky_position;
	if(count($def_data) > 0)
	{
		update_option( 'gwslau_loginas_options', $def_data );
	}
}

require_once( GWSLAU_PLUGIN_DIR_PATH .'functions.php' );
require_once( GWSLAU_PLUGIN_DIR_PATH .'options.php' );
require_once( GWSLAU_PLUGIN_DIR_PATH .'settings.php' );

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'gwslau_plugin_action_links' );
function gwslau_plugin_action_links( $actions ) {
	$url = add_query_arg( 'page', 'login-as-user-settings', get_admin_url() . 'admin.php' );
   	$actions[] = '<a href="'. esc_url( $url ) .'">Settings</a>';
	$actions = array_reverse($actions);
   	return $actions;
}

add_action( 'wp_enqueue_scripts', 'gwslau_enqueue_scripts');
function gwslau_enqueue_scripts(){
	wp_enqueue_script( 'gwslau-custom-script', GWSLAU_PLUGIN_URL.'/assets/js/custom.js', array('jquery'), GWSLAU_BUILD );
	wp_localize_script( 'gwslau-custom-script', 'ajax_object',array('ajax_url' => admin_url( 'admin-ajax.php' ),'home_url'=>get_home_url()));
}

add_action( 'admin_footer', 'gwslau_enqueue_admin_scripts');
function gwslau_enqueue_admin_scripts(){
	wp_enqueue_style( 'gwslau-admin-style', GWSLAU_PLUGIN_URL.'/assets/css/admin-style.css', array(), GWSLAU_BUILD);
}

add_action( 'get_footer', 'gwslau_enqueue_style_footer');
function gwslau_enqueue_style_footer(){
	wp_enqueue_style( 'gwslau-main-style', GWSLAU_PLUGIN_URL.'/assets/css/main.css', array(), GWSLAU_BUILD);
}

add_action( 'wp_ajax_gwslau_login_as_user_action', 'gwslau_login_as_user_action' );
function gwslau_login_as_user_action(){
	$user_id = intval( $_POST['user_id'] );
	$admin_id = intval( $_POST['admin_id'] );
	$site_domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	setcookie('gwslau_new_user_id', $user_id, time()+31556926, '/', $site_domain, false);
	setcookie('gwslau_old_user_id', $admin_id, time()+31556926, '/', $site_domain, false);
	$_SESSION["gwslau_new_user_id"] = $user_id;
	$_SESSION["gwslau_old_user_id"] = $admin_id;
	die();
}

add_action( 'init', 'gwslau_login_selected_user',1 );
function gwslau_login_selected_user() {
	if(isset($_COOKIE['gwslau_new_user_id']) && $_COOKIE['gwslau_new_user_id'] !='' && isset($_COOKIE['gwslau_old_user_id']) && $_COOKIE['gwslau_old_user_id'] != ''){
		wp_set_current_user(absint($_COOKIE['gwslau_new_user_id']));
		show_admin_bar(false);
	}else if(isset($_SESSION['gwslau_new_user_id']) && $_SESSION['gwslau_new_user_id'] !='' && isset($_SESSION['gwslau_old_user_id']) && $_SESSION['gwslau_old_user_id'] != ''){
		wp_set_current_user(absint($_SESSION['gwslau_new_user_id']));
		show_admin_bar(false);
	}
}


add_action( 'wp_footer', 'gwslau_add_footer_logout_module' );
function gwslau_add_footer_logout_module(){		
	$new_user_set = '';				
	$old_user_set = '';    			
	if(isset($_COOKIE['gwslau_new_user_id']) && $_COOKIE['gwslau_new_user_id'] !='' && isset($_COOKIE['gwslau_old_user_id']) && $_COOKIE['gwslau_old_user_id'] != ''){
		$new_user_set = absint($_COOKIE['gwslau_new_user_id']);				
		$old_user_set = absint($_COOKIE['gwslau_old_user_id']);
		
	}else if(isset($_SESSION['gwslau_new_user_id']) && $_SESSION['gwslau_new_user_id'] !='' && isset($_SESSION['gwslau_old_user_id']) && $_SESSION['gwslau_old_user_id'] != ''){
		$new_user_set = absint($_SESSION['gwslau_new_user_id']);				
		$old_user_set = absint($_SESSION['gwslau_old_user_id']);				
	}	
	
	if($new_user_set !='' && $old_user_set != ''){
		$user_info = get_userdata($new_user_set);
		$options = get_option( 'gwslau_loginas_options' );
		$loginas_sticky_class = 'gwslau-sidepane-left';
		if($options['gwslau_loginas_sticky_position'] == 'left'){
			$loginas_sticky_class = 'gwslau-sidepane-left';
		}
		else if($options['gwslau_loginas_sticky_position'] == 'right'){
			$loginas_sticky_class = 'gwslau-sidepane-right';
		}
		else if($options['gwslau_loginas_sticky_position'] == 'top'){
			$loginas_sticky_class = 'gwslau-sidepane-top';
		}
		else if($options['gwslau_loginas_sticky_position'] == 'bottom'){
			$loginas_sticky_class = 'gwslau-sidepane-bottom';
		}
		$user_name_type = $options['gwslau_loginas_name_show'];
		$user_name_data = gwslau_get_display_name($new_user_set, $user_name_type);
		?>
		<div class="gwslau_logout_box gwslau_sidepanel <?php esc_attr_e($loginas_sticky_class);?>" id="gwslau_sidepanel">
			<div class="gwslau-logout-box-inner">
				<a href="javascript:void(0)" class="gwslau-closebtn">×</a>
				<div class="gwslau-logged-user-container">
					<div class="gwslau-logged-user-name"><?php _e('You are logged in as ', 'w');?> 
						<span><?php esc_html_e($user_name_data); ?></span>
					</div>
					<button id="gwslau_logout_btn_logout_box" class="gwslau_btn_logout_box"><?php _e('Back To Your Account','gwslau_login_as_user')?></button>
				</div>
			</div> 
			<div class="gwslau-logout-user-icon">
				<img src="<?php echo esc_url(GWSLAU_PLUGIN_URL.'/assets/images/user-icon.svg');?>" alt="User Icon">
			</div>				
		</div>
		<?php				
	}		
}

add_action('wp_ajax_gwslau_login_return_admin', 'gwslau_login_return_admin');
add_action('wp_ajax_nopriv_gwslau_login_return_admin', 'gwslau_login_return_admin');
function gwslau_login_return_admin(){
	$site_domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	setcookie("gwslau_new_user_id", "", time() - 3600, '/', $site_domain, false);
	setcookie("gwslau_old_user_id", "", time() - 3600, '/', $site_domain, false);
	
	if(isset($_SESSION['gwslau_new_user_id'])){
		unset($_SESSION['gwslau_new_user_id']);
	}
	if(isset($_SESSION['gwslau_old_user_id'])){
		unset($_SESSION['gwslau_old_user_id']);
	}
	wp_die();
}

add_action( 'admin_footer', 'gwslau_login_action_javascript' );
function gwslau_login_action_javascript(){
	$options = get_option( 'gwslau_loginas_options' );
	$gwslau_admin_as_back_to="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$redirect_url = home_url()."/".$options['gwslau_loginas_redirect'];
	?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		$( ".gwslau-login-as-btn" ).on( "click", function(event) {
			localStorage.setItem('gwslau_admin_as_back_to', '<?php echo esc_js($gwslau_admin_as_back_to);?>');
			event.preventDefault();
			var user_id = $(this).data("user-id");
			var admin_id = $(this).data("admin-id");
			$.ajax({
				url: ajaxurl,
				type: 'post',
				data: {
					'action': 'gwslau_login_as_user_action',
					'user_id': user_id,
					'admin_id': admin_id,
				},
				success: function(response) {
					window.location.replace("<?php echo esc_js($redirect_url);?>");
				},
			});
		});
	});
	</script>
	<?php
}