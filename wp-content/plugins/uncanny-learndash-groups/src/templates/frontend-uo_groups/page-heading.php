<?php

namespace uncanny_learndash_groups;

?>

<section class="group-management-header">
	<div class="uo-row uo-groups-selector">
		<?php


		$dropdown_args = array(
			'selected'     => (int) GroupManagementInterface::$ulgm_current_managed_group_id,
			'sort_column'  => 'post_title',
			'hierarchical' => true,
		);

		require_once Utilities::get_include( 'class-walker-group-dropdown.php' );

		$walker = new \Walker_GroupDropdown();

		if ( $group_name_selector ) {
			// Only show drop down if user manages 2 or more groups
			if ( 2 <= count( GroupManagementInterface::$ulgm_managed_group_objects ) ) {
				global $post;
				?>
				<form id="group-management-header__selector" method="GET"
					  action="<?php echo get_permalink( $post->ID ); ?>">
					<span
						class="uo-looks-like-h3 ulg-manage-progress__title"><?php _e( 'Group', 'uncanny-learndash-groups' ); ?>:</span>

					<div class="uo-select uo-inline-block">
						<select name="group-id" id="group-id"
								class="users-table">
							<?php
							echo $walker->walk( GroupManagementInterface::$ulgm_managed_group_objects, 0, $dropdown_args );
							?>
						</select>
					</div>
					<?php
					if ( ulgm_filter_has_var( 'lang' ) ) {
						?>
						<input type="hidden" name="lang"
							   value="<?php echo esc_attr( ulgm_filter_input( 'lang' ) ); ?>"/>
						<?php
					}
					?>
				</form>
				<?php

			} else {

				?>

				<span
					class="uo-looks-like-h3 ulg-manage-progress__title"><?php _e( 'Group', 'uncanny-learndash-groups' ); ?>:</span>

				<h2 class="uo-looks-like-h3 uo-inline"
					id="group-name"><?php echo GroupManagementInterface::$ulgm_management_shortcode['text']['group_title']; ?></h2>

				<?php

			}
		}
		?>
		<input type="hidden" name="ulgm_current_managed_group_id"
			   id="ulgm_current_managed_group_id"
			   value="<?php echo GroupManagementInterface::$ulgm_current_managed_group_id; ?>"/>

		<?php
		// For group hierarchy support
		$is_hierarchy_setting_enabled = false;
		if ( ! ulgm_filter_has_var( 'show-children' ) ) {
			$show_child_url = add_query_arg( array( 'show-children' => 1 ) );
		} else {
			$show_child_url = remove_query_arg( 'show-children' );
		}


		if (
			function_exists( 'learndash_is_groups_hierarchical_enabled' )
			&& learndash_is_groups_hierarchical_enabled()
			&& 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' )
		) {
			$is_hierarchy_setting_enabled = SharedFunctions::has_children_in_group( GroupManagementInterface::$ulgm_current_managed_group_id );
			// check if current group has child
		}
		if ( $is_hierarchy_setting_enabled ) {
			?>
			<div id="group-management-header__children">
				<label><input type="checkbox" value="1"
							  name="show-children"
						<?php
						if ( ulgm_filter_has_var( 'show-children' ) ) {
							?>
							checked="checked" <?php } ?>
							  id="show_children"> <?php _e( 'Show student data for child groups', 'uncanny-learndash-groups' ); ?>
				</label>
			</div>

			<script>
				jQuery(document).ready(function () {
					let showChildren = jQuery('#show_children')
					showChildren.on('change', function () {
						window.location = '<?php echo $show_child_url; ?>';
					});
				})
			</script>
		<?php } ?>
		<?php
		if ( SharedFunctions::is_a_parent_group( GroupManagementInterface::$ulgm_current_managed_group_id ) &&
			 SharedFunctions::is_pool_seats_enabled() &&
			 false === SharedFunctions::is_pool_seats_enabled_for_all_groups()
		) {
			$pool_seats_in_children = absint( get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, 'ulgm_pool_seats_active', true ) );
			$toggle                 = 1;
			if ( 1 === $pool_seats_in_children ) {
				$toggle = 0;
			}
			?>
			<div id="group-management-header__children">
				<label><input type="checkbox" value="1"
							  name="pool-seats-in-children"
						<?php
						if ( 1 === $pool_seats_in_children ) {
							?>
							checked="checked" <?php } ?>
							  id="show_children_pl"> <?php _e( 'Enable pooled seats for this group hierarchy', 'uncanny-learndash-groups' ); ?>
				</label>
			</div>

			<!-- maybe modify this to redirect the same page with a setting in URL and save it.-->
			<script>
				<?php
				$group_management = ulgm()->group_management->pages->get_group_management_page_id( true );
				$url              = sprintf( '%s?group-id=%d&toggle_pool_value=%d', $group_management, GroupManagementInterface::$ulgm_current_managed_group_id, $toggle )
				?>
				jQuery(document).ready(function () {
					let showChildren = jQuery('#show_children_pl')
					showChildren.on('change', function () {
						window.location = '<?php echo esc_url_raw( $url ); ?>'
					});
				})
			</script>
		<?php } ?>


	</div>

	<?php do_action( 'ulgm_group_management_header', GroupManagementInterface::$ulgm_current_managed_group_id ); ?>
</section>
