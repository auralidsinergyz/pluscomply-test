<?php
if(!defined('ABSPATH')) exit;

function gwslau_user_conditional($options = array()){

    if(empty($options)){
        return true;
    }
    if(!isset($options['gwslau_loginas_status']) || $options['gwslau_loginas_status'] == 0){
        return true;
    }

    if(is_user_logged_in()){
        $user = wp_get_current_user();
        if(isset($options['gwslau_loginas_role']) && !empty($options['gwslau_loginas_role'])){
            $in_role = false;
            foreach($options['gwslau_loginas_role'] as $name){
                $name = str_replace(' ','_',$name);
                if(in_array(strtolower($name), $user->roles)){
                    $in_role = true;
                }
            }
            if(!$in_role){
                return true;
            }
        }

    }
    return false;
}

function gwslau_get_display_name($user_id, $name_type) {
    if (!$user = get_userdata($user_id))
        return false;
    if($name_type == 'user_login'){
        return $user->user_login;
    }
    elseif($name_type == 'firstname'){
        return $user->first_name;
    }
    elseif($name_type == 'full_name'){
        return $user->first_name.' '.$user->last_name;
    }
    elseif($name_type == 'nickname'){
        return $user->nickname;
    }
    else{
        return $user->user_login;
    }
}