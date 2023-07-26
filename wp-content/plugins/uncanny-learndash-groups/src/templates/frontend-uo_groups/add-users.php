<?php
namespace uncanny_learndash_groups;

?>
<div class="uo-groups-bulk">
	<div class="uo-groups-bulk-close">
		<?php _e( 'Close', 'uncanny-learndash-groups' ); ?>
	</div>
	<form name="groups-multi-add-users" id="groups-multi-add-users" action="?group-id=<?php echo self::$ulgm_current_managed_group_id; ?>&action=bulk-add-users" method="post">
		<input type="hidden" name="group-id" id="group-id" value="<?php echo self::$ulgm_current_managed_group_id; ?>">
		<div class="uo-groups">
			<section id="group-management-add-users">
				<div class="uo-row uo-groups-section uo-groups-group-leaders">
					<!-- Header -->
					<div class="uo-row uo-header">
						<h2 class="group-table-heading uo-looks-like-h3"><?php _e( 'Bulk add & Invite users', 'uncanny-learndash-groups' ); ?></h2>
						<div class="uo-row uo-header-subtitle">
						</div>
					</div>

					<div class="uo-row uo-groups-table">
						<div class="uo-table">
							<div class="uo-group-management-table uo-pseudo-table leaders">
								<!-- Header -->
								<div class="uo-row uo-table-row uo-table-header">
									<header class="pseudo-table-header">
										<div class="uo-row">
											<div class="header-column header-leaders-select-all uo-table-cell uo-table-cell-0_5">

											</div>

											<?php do_action( 'ulgm_before_add_bulk_users_columns', self::$ulgm_current_managed_group_id ); ?>

											<div class="header-column header-leaders-first-name uo-table-cell uo-table-cell-2">
												<span class="uog_header header"><?php echo __( 'First name', 'uncanny-learndash-groups' ); ?></span>
											</div>


											<div class="header-column header-leaders-last-name uo-table-cell uo-table-cell-2">
												<span class="uog_header header"><?php echo __( 'Last name', 'uncanny-learndash-groups' ); ?></span>
											</div>


											<div class="header-column header-leaders-email uo-table-cell uo-table-cell-3_5">
												<span class="uog_header header"><?php echo __( 'Email', 'uncanny-learndash-groups' ); ?></span>
											</div>

											<div class="header-column header-leaders-password uo-table-cell uo-table-cell-2">
												<span class="uog_header header"><?php echo __( 'Password', 'uncanny-learndash-groups' ); ?></span>

												<span class="uo-table-header-note" ulg-tooltip-frontend="<?php _e( 'Set an optional password for new users. If no password is entered, a random password will be generated. If the user already exists, the user\'s password will not be changed and this value will be ignored.', 'uncanny-learndash-groups' ); ?>" ulg-flow-frontend="down">
													<span class="ulg-icon ulg-icon--info"></span>
												</span>
											</div>

											<?php do_action( 'ulgm_after_add_bulk_users_columns', self::$ulgm_current_managed_group_id ); ?>
										</div>
									</header>
								</div>
							</div>
							<!-- Content & No results -->
							<?php
							$remaining = ulgm()->group_management->seat->remaining_seats( self::$ulgm_current_managed_group_id );
							if ( $remaining > 0 ) {
								?>
								<div class="uo-row uo-table-content">
									<div class="pseudo-table-body">
										<?php $j = 1; ?>
										<?php while ( $j <= $remaining ) { ?>
											<div class="uo-row uo-table-content uo-tbl-item body-row">
												<div class="content-leaders-select uo-table-cell uo-table-cell-0_5">
													<?php echo $j; ?>.
												</div>

												<?php do_action( 'ulgm_before_add_bulk_users_form_fields', self::$ulgm_current_managed_group_id ); ?>

												<div class="content-leaders-first-name uo-table-cell uo-table-cell-2">
													<input class="uo-input" type="text" name="first_name[]" value="" placeholder="<?php echo __( 'First name', 'uncanny-learndash-groups' ); ?>">
												</div>
												<div class="content-leaders-last-name uo-table-cell uo-table-cell-2">
													<input class="uo-input" type="text" name="last_name[]" value="" placeholder="<?php echo __( 'Last name', 'uncanny-learndash-groups' ); ?>">
												</div>
												<div class="content-leaders-email uo-table-cell uo-table-cell-3_5">
													<input class="uo-input" type="email" name="email[]" value="" placeholder="<?php echo __( 'Email', 'uncanny-learndash-groups' ); ?>">
												</div>
												<div class="content-leaders-password uo-table-cell uo-table-cell-2">
													<input class="uo-input" type="text" name="uo_password[]" value="" placeholder="<?php echo __( 'Password', 'uncanny-learndash-groups' ); ?>">
												</div>

												<?php do_action( 'ulgm_after_add_bulk_users_form_fields', self::$ulgm_current_managed_group_id ); ?>
											</div>
											<?php
											$j ++;
											if ( $j > 25 ) {
												break;
											}
										}
										?>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php
		if ( $remaining > 0 ) {
			?>
			<p><br/>
				<button class="uo-btn submit-group-management-form" data-end-point="add_user"><?php echo __( 'Add & Invite users', 'uncanny-learndash-groups' ); ?></button>
			</p>
		<?php } ?>
	</form>
</div>
