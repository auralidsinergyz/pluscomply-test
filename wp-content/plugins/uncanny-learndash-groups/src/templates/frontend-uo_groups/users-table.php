<!-- Datatables -->
<div class="uo-row uo-groups-table">

	<table id="group-management-enrolled-users-datatable"
		   class="display responsive no-wrap uo-table-datatable"
		   cellspacing="0"
		   width="100%"
		   data-group-id="<?php echo \uncanny_learndash_groups\GroupManagementInterface::$ulgm_current_managed_group_id ?>"
		   data-can-remove-users="<?php echo $remove_user_button ? '1' : '0'; ?>"
		   data-can-remove-users-anytime="<?php echo get_option( 'allow_to_remove_users_anytime', 'no' ) == 'no' ? '0' : '1'; ?>"
		   data-key_column="<?php echo json_encode($key_column); ?>"


		   data-page_length='<?php echo esc_attr( $enrolled_users_page_length ); ?>'
		   data-length_menu='<?php echo esc_attr( json_encode( $enrolled_users_length_menu ), JSON_HEX_QUOT|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS ); ?>'
 	></table>
 </div>