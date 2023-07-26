
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

$edd_action = 'activate';
$edd_action_text = __('Activate Your License',LD_MC_TEXT_DOMAIN);
if( $this->get_license_manager()->get_status_type() == 'success' ){
    $edd_action = 'deactivate';
    $edd_action_text = __('Deactivate Your License',LD_MC_TEXT_DOMAIN);
}

?>

<form method="post" action="options.php">
    <input type="hidden" name="license_edd_action" value="<?php echo $edd_action; ?>" />
<?php

settings_fields( 'ld_mc_license_settings_group' );
do_settings_sections( 'ld_mc_license_settings_page' );

submit_button($edd_action_text);
?>

</form>




<!--<div class="ld-mc-license-settings-wrapper">-->
<!--    <div class="ld-mc-license-key-wrapper">-->
<!--        <input type="text" class="ld-mc-license-key" id="ld_mc_license_key" value="--><?php //echo $license_key; ?><!--"/>-->
<!--    </div>-->
<!--    <div class="ld-mc-license-action-wrapper">-->
<!--        <button class="ld-mc-license-action button button-primary" id="ld_mc_license_action" data-edd_action="--><?php //echo $edd_action; ?><!--" >--><?php //echo $edd_action_text; ?><!--</button>-->
<!--        <span class="spinner"></span> -->
<!--    </div>-->
<!--    <div>--><?php //echo $edd_action_message; ?><!--</div>-->
<!--</div>-->
