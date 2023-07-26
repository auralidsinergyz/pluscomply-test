<?php
if(!defined('ABSPATH')) exit;

add_filter( 'manage_users_columns', 'gwslau_user_table_new_column');
function gwslau_user_table_new_column( $column ) {
	$options = get_option( 'gwslau_loginas_options');
	if(!gwslau_user_conditional($options)){
		if(isset($options['gwslau_loginas_for']) && !empty($options['gwslau_loginas_for'])){
            if(in_array("users_page", $options['gwslau_loginas_for'])){
				$column['login-as'] = __('Login As','gwslau_login_as_user');
			}
        }
	}
	return $column;
}

add_filter( 'manage_users_custom_column', 'gwslau_user_table_new_value', 10, 3 );
function gwslau_user_table_new_value( $val, $column_name, $user_id ) {
	switch ($column_name) {
		case 'login-as' :
			$options = get_option( 'gwslau_loginas_options' ,array());
			if(!gwslau_user_conditional($options)){
				$user_info = get_userdata($user_id);
				if($user_id == get_current_user_id()){
					return __('This user','gwslau_login_as_user');
				}
				$user_meta=get_userdata($user_id);
				$user_roles=$user_meta->roles;
				if(in_array('administrator', $user_roles)){
					return __('Administrator user','gwslau_login_as_user');
				}
				$user_name_type = $options['gwslau_loginas_name_show'];
				$user_name_data = gwslau_get_display_name($user_id, $user_name_type);
				$links = sprintf('<a href="#" class="page-title-action gwslau-login-as-btn" data-user-id="%d" data-admin-id="%d">%s</a>', absint($user_id),absint(get_current_user_id()), __( 'Login as <span>'.$user_name_data.'</span>', 'gwslau_login_as_user' ));
				return __($links,'gwslau_login_as_user');
			}
		break;
	}
	return $val;
}

add_filter( 'manage_edit-shop_order_columns', 'gwslau_order_table_new_column', 20);
function gwslau_order_table_new_column( $columns ) {
	$options = get_option( 'gwslau_loginas_options' );
	$reordered_columns = array();
	foreach( $columns as $key => $column){
		$reordered_columns[$key] = $column;
		if( $key ==  'order_status' ){
			if(!gwslau_user_conditional($options)){
				if(!gwslau_user_conditional($options)){
					if(isset($options['gwslau_loginas_for']) && !empty($options['gwslau_loginas_for'])){
						if(in_array("orders_page", $options['gwslau_loginas_for'])){
							$reordered_columns['Login-as'] = __( 'Login As','gwslau_login_as_user');
						}
					}
				}
			}
		}
	}
	return $reordered_columns;
}

add_action( 'manage_shop_order_posts_custom_column' , 'gwslau_orders_column_content', 20, 2 );
function gwslau_orders_column_content( $column, $post_id ){
	$options = get_option( 'gwslau_loginas_options' );
	if(gwslau_user_conditional($options)){
		return;
	}
	switch ( $column )
	{
		case 'Login-as' :
			$order = wc_get_order($post_id);
			$user_id = $order->get_user_id();
			if($user_id != 0){
				if($user_id == get_current_user_id()){
					return _e('This user','gwslau_login_as_user');
				}
				$user_info = get_userdata($user_id);
				$user_roles=$user_info->roles;
				$user_roles = array_filter(array_map('trim', $user_roles));
				if(in_array('administrator', $user_roles)){
					return  _e('Administrator user','gwslau_login_as_user');
				}
				$user_name_type = $options['gwslau_loginas_name_show'];
				$user_name_data = gwslau_get_display_name($user_id, $user_name_type);
				if(!empty($user_info)){
					$links = sprintf('<a href="#" class="page-title-action gwslau-login-as-btn" data-user-id="%d" data-admin-id="%d">%s</a>', absint($user_id),absint(get_current_user_id()), __( 'Login as <span>'.$user_name_data.'</span>', 'gwslau_login_as_user' ));
					return _e($links,'gwslau_login_as_user');
				}
			}
			else{
				_e('Visitor','gwslau_login_as_user');
			}
			break;
	}
}

add_action('personal_options', 'gwslau_add_personal_options');
function gwslau_add_personal_options( WP_User $user ) 
{
	$options = get_option( 'gwslau_loginas_options' );
	if(!gwslau_user_conditional($options)){
		if(isset($options['gwslau_loginas_for']) && !empty($options['gwslau_loginas_for'])){
			if(in_array("users_profile_page", $options['gwslau_loginas_for'])){
				if (get_current_user_id() != $user->ID && !empty($user->user_login))
				{
					$user_name_type = $options['gwslau_loginas_name_show'];
					$user_name_data = gwslau_get_display_name($user->ID, $user_name_type);
					$links = sprintf('<a href="#" class="page-title-action gwslau-login-as-btn" data-user-id="%d" data-admin-id="%d">%s</a>', absint($user->ID),absint(get_current_user_id()), __( 'Login as <span>'.$user_name_data.'</span>', 'gwslau_login_as_user' ));
					return _e($links,'gwslau_login_as_user');
				}
			}
		}
	}
}

add_action('add_meta_boxes', 'gwslau_add_login_as_user_metabox');
function gwslau_add_login_as_user_metabox()
{
	$options = get_option( 'gwslau_loginas_options' );
	if(!gwslau_user_conditional($options)){
		if(isset($options['gwslau_loginas_for']) && !empty($options['gwslau_loginas_for'])){
			if(in_array("order_edit_page", $options['gwslau_loginas_for'])){
				add_meta_box( 'login_as_user_metabox', __( 'Login as User' ), 'gwslau_login_as_user_metabox', 'shop_order', 'side', 'low');
			}
		}
	}
}

function gwslau_login_as_user_metabox($post){
	$order = wc_get_order($post->ID);
	$user_id = $order->get_user_id();
	$options = get_option( 'gwslau_loginas_options' );
	if($user_id != 0){
		if($user_id == get_current_user_id()){
			return _e('This user','gwslau_login_as_user');
		}
		$user_info = get_userdata($user_id);
		$user_roles=$user_info->roles;
		if(in_array('administrator', $user_roles)){
			return _e('Administrator user','gwslau_login_as_user');
		}
		if(!empty($user_info)){
			$user_name_type = $options['gwslau_loginas_name_show'];
			$user_name_data = gwslau_get_display_name($user_id, $user_name_type);
			$links = sprintf('<a href="#" class="page-title-action gwslau-login-as-btn" data-user-id="%d" data-admin-id="%d">%s</a>', absint($user_id),absint(get_current_user_id()), __( 'Login as <span>'.$user_name_data.'</span>', 'gwslau_login_as_user' ));
			return _e($links,'gwslau_login_as_user');
		}
	}
	else{
		return _e('Visitor','gwslau_login_as_user');
		
	}
}
?>