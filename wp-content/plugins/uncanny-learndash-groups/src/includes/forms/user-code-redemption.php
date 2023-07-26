<?php

namespace uncanny_learndash_groups;
if ( ! is_user_logged_in() ) {
	return '<h3>' . __( 'Sorry, please log in to redeem code.', 'uncanny-learndash-groups' ) . '</h3>';
}

if ( ulgm_filter_has_var( 'registered' ) ) {
	ob_start();
	?>
	<div class="registered">
		<h4><?php _e( 'Congratulations! You have successfully redeemed your key.', 'uncanny-learndash-groups' ); ?></h4>
	</div>
	<?php
	return ob_get_clean();
}
?>
	<form id="ulgm_registration_form" class="uncanny-learndash-groups" action="" method="POST">
		<fieldset>
			<?php do_action( 'ulgm_groups_key_redemption_form_start' ); ?>
			<table class="table table-form form-table clr">
				<?php do_action( 'ulgm_groups_key_redemption_form_before_enrollment_key' ); ?>
				<tr>
					<td class="label"><label
								for="code_registration"><?php esc_html_e( 'Enrollment key', 'uncanny-learndash-groups' ); ?></label>
					</td>
					<td class="input">
						<input name="ulgm_code_redeem"
							   id="ulgm_code_redeem"
							   required="required"
							   class="required"
							   value="<?php if ( ulgm_filter_has_var( 'ulgm_code_redeem', INPUT_POST ) ) {
								   echo ulgm_filter_input( 'ulgm_code_redeem', INPUT_POST );
							   } ?>"
							   placeholder="<?php esc_html_e( 'Enrollment key', 'uncanny-learndash-groups' ); ?>"
							   type="text"/></td>
				</tr>
				<?php do_action( 'ulgm_groups_key_redemption_form_after_enrollment_key' ); ?>
				<?php do_action( 'ulgm_groups_key_redemption_form_before_buttons' ); ?>
				<tr>
					<td class="label"></td>
					<td class="input">
						<input type="submit" class="btn btn-default"
							   value="<?php esc_html_e( 'Redeem code', 'uncanny-learndash-groups' ); ?>"/>
						<input type="hidden" name="_ulgm_code_nonce"
							   value="<?php echo wp_create_nonce( Utilities::get_prefix() ); ?>"/>
						<input type="hidden" name="_ulgm_code_redirect_to"
							   value="<?php echo GroupManagementRegistration::$code_redemption_atts['redirect'];
							   ?>"/>
						<input type="hidden" name="_ulgm_code_default_role"
							   value="<?php echo GroupManagementRegistration::$code_redemption_atts['role'];
							   ?>"/>
						<input type="hidden" name="key"
							   value="<?php echo crypt( get_the_ID(), 'uncanny-learndash-groups' ); ?>"/>
					</td>
				</tr>
				<?php do_action( 'ulgm_groups_key_redemption_form_after_enrollment_key' ); ?>
			</table>
			<?php do_action( 'ulgm_groups_key_redemption_form_end' ); ?>
		</fieldset>
	</form>
<?php
