<!-- Header -->
<div class="uo-row uo-header">
	<h2 class="group-table-heading uo-looks-like-h3"><?php _e( 'Group leaders', 'uncanny-learndash-groups' ); ?></h2>
	<div class="uo-row uo-header-subtitle">
				<span class="uo-subtitle-of-h3">
					<?php echo count( self::$ulgm_group_leaders_data_dt ) . ' ' . __( 'leaders', 'uncanny-learndash-groups' ); ?>
				</span>
	</div>
</div>

<!-- Actions -->
<?php if ( $populate_management_features ) { ?>
	<div class="leader-table-actions">
		<div class="uo-row uo-groups-actions">
			<div class="group-management-buttons">
				<!-- Add Group Leaders -->

				<?php if ( $add_group_leader_button ) { ?>

					<button class="uo-btn uo-left ulgm-modal-link" data-modal-id="#group-management-add-group-leader"
							rel="modal:open"><?php echo self::$ulgm_management_shortcode['text']['add_group_leader']; ?></button>

					<div id="group-management-add-group-leader" class="group-management-modal" style="display:none;">
						<div class="uo-groups">
							<div class="group-management-form">
								<div class="group-management-rest-message"></div>

								<?php do_action( 'ulgm_before_add_group_leader_form_fields', self::$ulgm_current_managed_group_id ); ?>
							
								<div class="uo-row">
									<label for="first-name">
										<div class="uo-row__title">
											<?php echo __( 'First name*', 'uncanny-learndash-groups' ); ?>
										</div>
									</label>
									<input class="uo-input" type="text" name="first_name" id="first-name" value="">
								</div>

								<div class="uo-row">
									<label for="last-name">
										<div class="uo-row__title">
											<?php echo __( 'Last name*', 'uncanny-learndash-groups' ); ?>
										</div>
									</label>
									<input class="uo-input" type="text" name="last_name" id="last-name" value="">
								</div>

								<div class="uo-row">
									<label for="email">
										<div class="uo-row__title">
											<?php echo __( 'Email*', 'uncanny-learndash-groups' ); ?>
										</div>
									</label>
									<input class="uo-input" type="text" name="email" id="email" value="">
								</div>

								<div class="uo-row">
									<i>
									<?php
									echo __(
										'If the email address matches an existing user in the system, first and last name will not be
                                            changed but the existing user will be added as a Group Leader.',
										'uncanny-learndash-groups'
									);
									?>
										</i>
								</div>

								<input type="hidden" name="group-id" id="group-id"
									   value="<?php echo self::$ulgm_current_managed_group_id; ?>">
								<input type="hidden" name="action" id="add-leader" value="add-leader">

								<?php do_action( 'ulgm_after_add_group_leader_form_fields', self::$ulgm_current_managed_group_id ); ?>

								<div class="uo-row-footer">
									<div style="margin-bottom: 15px" class="uo-modal-spinner"></div>

									<button class="uo-btn submit-group-management-form"
											data-end-point="add_group_leader"><?php echo __( 'Add group leader', 'uncanny-learndash-groups' ); ?>
									</button>
								</div>

							</div>
						</div>

					</div>

				<?php } ?>

				<div class="group-leader-management-buttons uo-hidden">
					<!-- Remove Group Leaders -->
					<button class="uo-btn uo-left ulgm-modal-link" data-modal-id="#group-management-remove-group-leader"
							rel="modal:open"><?php echo self::$ulgm_management_shortcode['text']['remove_group_leader']; ?></button>

					<div id="group-management-remove-group-leader" class="group-management-modal" style="display:none;">
						<div class="uo-groups">
							<div class="group-management-form">
								<div class="group-management-rest-message"></div>

								<input type="hidden" id="removing-group-leaders" name="removing-group-leaders" value="">

								<div class="uo-row">
									<?php echo sprintf( __( 'Are you sure you want to remove %s group leader(s)?', 'uncanny-learndash-groups' ), '<span class="amount-group-leaders"></span>' ); ?>
								</div>

								<input type="hidden" name="action" id="remove-group-leaders"
									   value="remove-group-leaders">
								<input type="hidden" name="group-id" id="group-id"
									   value="<?php echo self::$ulgm_current_managed_group_id; ?>">

								<div class="uo-row-footer">
									<div style="margin-bottom: 15px" class="uo-modal-spinner"></div>

									<button class="uo-btn submit-group-management-form"
											data-end-point="remove_group_leaders"><?php echo self::$ulgm_management_shortcode['text']['remove_group_leader']; ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
