<!-- Datatables -->
<div class="uo-row uo-groups-table">

	<table 
	       id="group-management-enrolled-leader-datatable" 
	       class="display responsive no-wrap uo-table-datatable"
 		   cellspacing="0"
 		   width="100%"

		   data-page_length='<?php echo esc_attr( $group_leaders_page_length ); ?>'
		   data-length_menu='<?php echo esc_attr( json_encode( $group_leaders_length_menu ), JSON_HEX_QUOT|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS ); ?>'
 	></table>
</div>