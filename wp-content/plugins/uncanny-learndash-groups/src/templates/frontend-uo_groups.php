<?php

namespace uncanny_learndash_groups;

/**
 * [uo_groups]'s template
 */

?>

<div id="group-management" class="uo-groups">

	<?php

	if ( '' !== GroupManagementInterface::$ulgm_management_shortcode['message'] ) {
		echo '<div style="display:inline-flex;" id="group-management-message" class="uo-groups-message">' . GroupManagementInterface::$ulgm_management_shortcode['message'] . '</div>';
	} elseif ( ulgm_filter_has_var( 'success-invited' ) ) {
		echo '<div id="group-management-message" class="uo-groups-message" style="display: block;">' . esc_html( wp_kses( wp_unslash( ulgm_filter_input( 'success-invited' ) ), array() ) ) . '</div>';
	} else {
		echo '<div id="group-management-message" class="uo-groups-message"></div>';
	}

	if ( ulgm_filter_has_var( 'bulk-errors' ) ) {
		echo '<div id="group-management-message-error" style="display: block; background: #ffd2d2;color: #d8000c;" class="uo-groups-message error">' . wp_kses( ulgm_filter_input( 'bulk-errors' ), true ) . '</div>';
	}

	include( Utilities::get_template( 'frontend-uo_groups/page-heading.php' ) );

	include( Utilities::get_template( 'frontend-uo_groups/add-users.php' ) );

	include( Utilities::get_template( 'frontend-uo_groups/users-table-actions.php' ) );

	include( Utilities::get_template( 'frontend-uo_groups/users-table.php' ) );

	if ( $group_leader_section ) {

		include( Utilities::get_template( 'frontend-uo_groups/groups-table-actions.php' ) );

		include( Utilities::get_template( 'frontend-uo_groups/groups-table.php' ) );

	}

	?>

</div>
