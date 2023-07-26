
<?php
//$license_key     = get_option('ld_mc_license_key');
//$license_statuse = get_option('ld_mc_license_status');
//$edd_action = 'activate';
//$edd_action_text = 'Activate Your License';
//$edd_action_message = '<p class="ld-mc-license-message"><span class="dashicons dashicons-dismiss"></span> your license is not activated click on the activate button.</p>';
//if( ! empty($license_key) && ! empty($license_statuse) ){
//    if( $license_statuse == 'valid' ){
//        $edd_action = 'deactivate';
//        $edd_action_text = 'Deactivate Your License';
//        $edd_action_message = '<p class="ld-mc-license-message"><span class="dashicons dashicons-yes-alt"></span> your license is activated.</p>';
//    }
//}

?>

<form method="post" action="options.php">
    
<?php

settings_fields( 'ld_mc_general_settings_group' );
do_settings_sections( 'ld_mc_general_settings_page' );

submit_button(__('Save',LD_MC_TEXT_DOMAIN));
?>

</form>
