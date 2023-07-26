<?php
namespace uncanny_learndash_groups;
$redirect_link = get_permalink( SharedFunctions::$group_management_page_id );
if ( ulgm_filter_has_var( 'group-id' ) ) {
	$redirect_link .= '?group-id=' . ulgm_filter_input( 'group-id' );
}
?>
<div id="uo-groups-upload-summary" class="uo-groups-upload-results">
	<div style="margin-bottom: 15px;" class="uo-modal-spinner"></div>
	<div class="uo-groups-upload-results-reload">
		<button onClick="javascript: window.location.href = '<?php echo $redirect_link; ?>'"
				class="uo-btn uo-left ulgm-modal-link"><?php _e( 'Done', 'uncanny-learndash-groups' ); ?></button>
	</div>
	<form>
		<div class="uo-groups">
			<section id="group-management-add-users">
				<div class="uo-row uo-groups-section uo-groups-group-leaders">
					<!-- Header -->
					<div class="uo-row uo-header">
						<h2 class="group-table-heading uo-looks-like-h3"><?php _e( 'Upload Summary', 'uncanny-learndash-groups' ); ?></h2>
						<div class="uo-row uo-header-subtitle"><?php _e( 'Upload is processing...', 'uncanny-learndash-groups' ); ?></div>
					</div>

					<div class="uo-row uo-groups-table uo-groups-results-table">
						<div class="uo-table">
							<div class="uo-group-management-table uo-pseudo-table leaders">
								<!-- Header -->
								<div class="uo-row uo-table-row uo-table-header">
									<header class="pseudo-table-header">
										<div class="uo-row">
											<div class="header-column header-leaders-total uo-table-cell uo-table-cell-2_5">
												<span class="uog_header header"><?php echo __( 'Total Rows:', 'uncanny-learndash-groups' ) ?></span>
												<span id="csv-result-total" class="uog_header header-value">0</span>
											</div>

											<div class="header-column header-leaders-added uo-table-cell uo-table-cell-2_5">
												<span class="uog_header header"><?php echo __( 'Users added:', 'uncanny-learndash-groups' ) ?></span>
												<span id="csv-result-added" class="uog_header header-value">0</span>
											</div>


											<div class="header-column header-leaders-in-group uo-table-cell uo-table-cell-2_5">
												<span class="uog_header header"><?php echo __( 'Already a member:', 'uncanny-learndash-groups' ) ?></span>
												<span id="csv-result-member" class="uog_header header-value">0</span>
											</div>

											<div class="header-column header-leaders-no-added uo-table-cell uo-table-cell-2_5">
												<span class="uog_header header"><?php echo __( 'Not Processed:', 'uncanny-learndash-groups' ) ?></span>
												<span id="csv-result-unprocessed"
													  class="uog_header header-value">0</span>
											</div>

										</div>
									</header>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<section id="group-management-add-users">
				<div class="uo-row uo-groups-section uo-groups-group-leaders">
					<div class="uo-row uo-groups-table">
						<div class="uo-table">
							<div class="uo-group-management-table uo-pseudo-table leaders">
								<!-- Header -->
								<div class="uo-row uo-table-row uo-table-header">
									<header class="pseudo-table-header">
										<div class="uo-row">
											<div class="header-column header-leaders-first-name uo-table-cell uo-table-cell-3">
												<span class="uog_header header"><?php echo __( 'Email', 'uncanny-learndash-groups' ) ?></span>
											</div>


											<div class="header-column header-leaders-last-name uo-table-cell uo-table-cell-7">
												<span class="uog_header header"><?php echo __( 'Status', 'uncanny-learndash-groups' ) ?></span>
											</div>

										</div>
									</header>
								</div>
							</div>
							<!-- Content & No results -->
							<div class="uo-row uo-table-content">
								<div id="uo-groups-upload-result-rows" class="pseudo-table-body">
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

		</div>

		<p><br/>
			<button onClick="javascript: window.location.href = '<?php echo $redirect_link; ?>'"
					class="uo-btn uo-left ulgm-modal-link"><?php _e( 'Done', 'uncanny-learndash-groups' ); ?></button>
		</p>
	</form>
</div>
