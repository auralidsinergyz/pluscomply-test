<?php

namespace uncanny_learndash_groups;

/**
 * [uo_groups_course_report]'s template
 */

?>

<div class="uo-groups uo-reports">
	<section id="group-report" class="box">
		<?php

		// User is a group leader, get users groups
		$is_hierarchy_setting_enabled = false;
		$dropdown_args                = array();
		if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
			$is_hierarchy_setting_enabled = true;
			include_once Utilities::get_include( 'class-walker-group-dropdown.php' );
			$dropdown_args = array(
				'selected'     => 0,
				'sort_column'  => 'post_title',
				'hierarchical' => true,
			);
			$walker        = new \Walker_GroupDropdown();
		}
		if ( $is_hierarchy_setting_enabled ) {
			$user_groups = learndash_get_administrators_group_ids( get_current_user_id() );
		} else {
			$user_groups = LearndashFunctionOverrides::learndash_get_administrators_group_ids( get_current_user_id() );
		}

		if ( empty( $user_groups ) ) {
			?>

			<div class="uo-row">
				<div class="uo-groups-message uo-groups-message-info">
					<?php _e( 'You are not a leader of any groups.', 'uncanny-learndash-groups' ); ?>
				</div>
			</div>

			<?php
			return;
		}
		?>

		<?php
		if ( ! $is_hierarchy_setting_enabled ) {
			global $wpdb;
			$result = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM $wpdb->posts WHERE post_status LIKE %s AND post_type LIKE %s AND ID IN (" . join( ',', $user_groups ) . ') ORDER BY post_title ASC LIMIT 9999', 'publish', 'groups' ) );
		} else {
			$posts_in = array_map( 'intval', $user_groups );
			$args     = array(
				'post_type'      => 'groups',
				'post__in'       => $posts_in,
				'posts_per_page' => 9999,
				'orderby'        => 'title',
				'order'          => 'ASC',
			);

			$result = new \WP_Query( $args );
		}

		$drop_down        = '<option value="0">' . __( 'Select Group', 'uncanny-learndash-groups' ) . '</option>';
		$drop_down_course = '<option value="0">' . sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ) . '</option>';
		if ( 1 === count( $user_groups ) ) {
			$r                 = isset( $result->posts ) ? array_shift( $result->posts ) : array_shift( $result );
			$single_drop_class = 'h3-select';
			$drop_down         .= '<option value="' . $r->ID . '">' . $r->post_title . '</option>';

		} else {
			$single_drop_class = 'pre-select';

			if ( $result ) {
				if ( $is_hierarchy_setting_enabled && $walker instanceof \Walker_GroupDropdown ) {
					$drop_down .= $walker->walk( $result->posts, 0, $dropdown_args );
					/* Restore original Post Data */
					wp_reset_postdata();
				} else {
					foreach ( $result as $r ) {
						$drop_down .= '<option value="' . $r->ID . '">' . $r->post_title . '</option>';
					}
				}
			} else {
				// no posts found
				$drop_down = '<option>' . __( 'No Groups', 'uncanny-learndash-groups' ) . '</option>';
			}
		}
		?>

		<style>

			.uo-groups .uo-select select.h3-select {
				background: none !important;
				border: none;
				-webkit-box-shadow: none;
				box-shadow: none;
				font-size: 18px;
				font-weight: bold;
				padding-left: 0;
				padding-top: 0;
				/*for firefox*/
				-moz-appearance: none;
				/*for chrome*/
				-webkit-appearance: none;
			}

		</style>

		<?php
		if ( ! empty( SharedFunctions::get_group_management_page_id() ) && ! empty( SharedFunctions::get_group_report_page_id() ) ) :
			?>
			<div class="uo-row uo-groups-section uo-groups-report-go-back">
				<div class="uo-groups-actions">
					<div class="group-management-buttons">
						<button class="ulgm-link uo-btn uo-left uo-btn-arrow-left"
								onclick="location.href='<?php echo GroupReportsInterface::$ulgm_reporting_shortcode['text']['group_management_link']; ?>'"
								type="button">
							<?php echo GroupReportsInterface::$ulgm_reporting_shortcode['text']['group_management']; ?>
						</button>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="uo-row uo-groups-section uo-groups-selection">

			<div class="uo-groups-select-filters">
				<div class="uo-row uo-groups-select-group" 
				<?php
				if ( 0 !== absint( GroupReportsInterface::$user_id ) ) {
					echo 'style="display:none"';
				}
				?>
				>
					<?php

					if ( 0 !== absint( GroupReportsInterface::$user_id ) ) {
						?>
						<div id="auto-run" class="group-management-form" data-end-point="get_user_course_data">

							<input type="hidden" id="action" name="action" value="get-user-data-courses">
							<input type="hidden" id="group-id" name="group-id" value="all">
							<input type="hidden" id="course-id" name="course-id" value="all">
							<input type="hidden" id="course-group-id" name="course-group-id" value="all">
							<input type="hidden" id="user-id" name="user-id"
								   value="<?php echo absint( GroupReportsInterface::$user_id ); ?>">

							<div class="uo-modal-spinner"></div>
							<div class="group-management-rest-message"></div>
						</div>
						<script>
							jQuery(document).ready(function () {

								let associatedForm = jQuery('#auto-run');
								let restData = {
									'action': "get-user-data-courses-single-user",
									'user_id': <?php echo GroupReportsInterface::$user_id; ?>,
									'group-id': 'all',
									'course-id': 'all',
									'course-group-id': 'all',
								};

								window.ulgmGroupManagement.restForms.makeAjaxCall('get_user_course_data', restData, associatedForm);
							})

						</script>
						<?php

					} else {

						?>
						<div class="group-management-form">

							<input type="hidden" id="action" name="action" value="get-courses">
							<input type="hidden" id="course-order" name="course-order"
								   value="<?php echo GroupReportsInterface::$course_order; ?>">

							<div class="uo-select">
								<label><?php _e( 'Group', 'uncanny-learndash-groups' ); ?></label>
								<select class="change-group-management-form <?php echo $single_drop_class; ?>"
										id="group-id"
										name="group-id" data-end-point="get_group_courses">
									<?php echo $drop_down; ?>
								</select>
							</div>

							<div class="uo-modal-spinner"></div>
							<div class="group-management-rest-message"></div>
						</div>
						<?php
					}

					?>
				</div>


				<div class="uo-row uo-groups-select-list" 
				<?php
				if ( 0 !== GroupReportsInterface::$user_id ) {
					echo 'style="display:none"';
				}
				?>
				>
					<div class="group-management-form">

						<input type="hidden" id="action" name="action" value="get-user-data-courses">
						<input type="hidden" id="course-group-id" name="course-group-id" value="">

						<div class="uo-select">
							<label><?php _e( \LearnDash_Custom_Label::get_label( 'course' ), 'uncanny-learndash-groups' ); ?></label>
							<select class="change-group-management-form " id="uo-group-report-course"
									name="course-id" data-end-point="get_user_course_data">
								<?php echo $drop_down_course; ?>
							</select>
						</div>

						<div class="group-management-rest-message" style="display: none;"></div>
					</div>
				</div>
			</div>
		</div>

		<!-- Datatables -->
		<div class="uo-row uo-groups-table">

			<table id="group-course-report-datatable"
					<?php echo ! empty( GroupReportsInterface::$transcript_page_url ) ? 'data-transcript_page_url="' . GroupReportsInterface::$transcript_page_url . '"' : ''; ?>
					<?php echo 'data-table_columns="' . implode( ',', GroupReportsInterface::$table_columns ) . '"'; ?>
					<?php
					if ( isset( GroupReportsInterface::$user_id ) && ! empty( GroupReportsInterface::$user_id ) ) {
						// Retrieve the user's data (here we suppose, of course, the user exists)
						$new_user = new \WP_User( GroupReportsInterface::$user_id );
						// Get the user's first and last name
						$first_name = $new_user->first_name;
						$last_name  = $new_user->last_name;

						$name = '';
						if ( ! empty( $first_name ) ) {
							$name .= $first_name . ' ';
						}

						if ( ! empty( $last_name ) ) {
							$name .= ' ' . $last_name;
						}

						if ( empty( $name ) ) {
							$name = GroupReportsInterface::$user_id;
						}

						// this name is used to create a csv file name for a specific user ex. /group-management-report/?user_id=15
						echo 'data-user_name="' . $name . '"';
					}

					?>
				   class="display responsive no-wrap uo-table-datatable"
				   cellspacing="0"
				   width="100%"
			></table>
		</div>

	</section>
</div>
