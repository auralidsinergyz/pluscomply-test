<?php

namespace uncanny_learndash_groups;

if ( ulgm_filter_has_var( 'registered' ) ) {
	ob_start(); ?>
	<div class="registered">
		<h2><?php _e( 'Congratulations! Your registration was successful.', 'uncanny-learndash-groups' ); ?></h2>
	</div>
	<?php
	return ob_get_clean();
}
?>
	<form id="ulgm_registration_form" class="uncanny-learndash-groups uo-groups-registration" action="" method="POST">
		<fieldset>
			<?php do_action( 'ulgm_groups_registration_form_start' ); ?>
			<table class="table table-form form-table clr">
				<?php do_action( 'ulgm_groups_registration_form_before_first_name' ); ?>
				<!-- First name  __ START -->
				<tr>
					<td class="label"><label
								for="ulgm_user_first"><?php esc_html_e( 'First name', 'uncanny-learndash-groups' ); ?></label>
					</td>
					<td class="input">
						<input name="ulgm_user_first"
							   id="ulgm_user_first"
							   placeholder="<?php esc_html_e( 'First name', 'uncanny-learndash-groups' ); ?>"
							   required="required"
							   value="<?php if ( ulgm_filter_has_var( 'ulgm_user_first', INPUT_POST ) ) {
								   echo sanitize_text_field( ulgm_filter_input( 'ulgm_user_first', INPUT_POST ) );
							   } ?>"
							   type="text"/></td>
				</tr>
				<!-- First name __ END -->
				<?php do_action( 'ulgm_groups_registration_form_after_first_name' ); ?>
				<?php do_action( 'ulgm_groups_registration_form_before_last_name' ); ?>
				<!-- Last name  __ START -->
				<tr>
					<td class="label"><label
								for="ulgm_user_last"><?php esc_html_e( 'Last name', 'uncanny-learndash-groups' ); ?></label>
					</td>
					<td class="input">
						<input name="ulgm_user_last"
							   id="ulgm_user_last"
							   required="required"
							   placeholder="<?php esc_html_e( 'Last name', 'uncanny-learndash-groups' ); ?>"
							   value="<?php if ( ulgm_filter_has_var( 'ulgm_user_last', INPUT_POST ) ) {
								   echo sanitize_text_field( ulgm_filter_input( 'ulgm_user_last', INPUT_POST ) );
							   } ?>"
							   type="text"/></td>
				</tr>
				<!-- Last name __ END -->
				<?php do_action( 'ulgm_groups_registration_form_after_last_name' ); ?>
				<?php do_action( 'ulgm_groups_registration_form_before_email' ); ?>
				<!-- Email / Username  __ START -->
				<tr>
					<td class="label"><label
								for="ulgm_user_email"><?php esc_html_e( 'Email / Username', 'uncanny-learndash-groups' ); ?></label>
					</td>
					<td class="input">
						<input name="ulgm_user_email"
							   id="ulgm_user_email"
							   required="required"
							   placeholder="<?php esc_html_e( 'Email / Username', 'uncanny-learndash-groups' ); ?>"
							   value="<?php if ( ulgm_filter_has_var( 'ulgm_user_email', INPUT_POST ) ) {
								   echo sanitize_email( ulgm_filter_input( 'ulgm_user_email', INPUT_POST ) );
							   } ?>"
							   class="required" type="email"/></td>
				</tr>
				<!-- Email / Username __ END -->
				<?php do_action( 'ulgm_groups_registration_form_after_email' ); ?>
				<?php do_action( 'ulgm_groups_registration_form_before_password' ); ?>
				<!-- Password  __ START -->
				<tr>
					<td class="label"><label
								for="password"><?php esc_html_e( 'Password', 'uncanny-learndash-groups' ); ?></label>
					</td>
					<td class="input">
						<input name="ulgm_user_pass" id="password"
							   required="required"
							   minlength="6"
							   class="required"
							   placeholder="<?php esc_html_e( 'Password', 'uncanny-learndash-groups' ); ?>"
							   type="password"/></td>
				</tr>
				<!-- Password __ END -->
				<?php do_action( 'ulgm_groups_registration_form_after_password' ); ?>
				<?php do_action( 'ulgm_groups_registration_form_before_password_confirm' ); ?>
				<!-- Confirm Password  __ START -->
				<tr>
					<td class="label"><label
								for="password_again"><?php esc_html_e( 'Confirm Password', 'uncanny-learndash-groups' ); ?></label>
					</td>
					<td class="input">
						<input name="ulgm_user_pass_confirm" id="password_again"
							   required="required"
							   minlength="6"
							   oninput="check(this)"
							   class="required"
							   placeholder="<?php esc_html_e( 'Confirm Password', 'uncanny-learndash-groups' ); ?>"
							   type="password"/></td>
				</tr>
				<!-- Confirm Password __ END -->
				<?php do_action( 'ulgm_groups_registration_form_after_password_confirm' ); ?>
				<?php do_action( 'ulgm_groups_registration_form_before_enrollment_key' ); ?>
				<!--  Enrollment key __ START -->
				<tr>
					<td class="label"><label
								for="code_registration"><?php esc_html_e( 'Enrollment key', 'uncanny-learndash-groups' ); ?></label>
					</td>
					<td class="input">
						<input name="ulgm_code_registration" id="code_registration"
							   <?php if ( 'no' === GroupManagementRegistration::$code_registration_atts['code_optional'] ){ ?>required="required"
							   class="required"<?php } ?>
							   value="<?php if ( ulgm_filter_has_var( 'ulgm_code_registration', INPUT_POST ) ) {
								   echo sanitize_text_field( ulgm_filter_input( 'ulgm_code_registration', INPUT_POST ) );
							   } ?>"
							   placeholder="<?php esc_html_e( 'Enrollment key', 'uncanny-learndash-groups' ); ?>"
							   type="text"/></td>
				</tr>
				<!-- Enrollment key __ END -->
				<?php do_action( 'ulgm_groups_registration_form_after_enrollment_key' ); ?>
				<!-- Terms __ START -->
				<?php
				if ( ! empty( SharedVariables::ulgm_term_condition() ) && 'yes' === GroupManagementRegistration::$code_registration_atts['enable_terms'] ) {
					?>
					<?php do_action( 'ulgm_groups_registration_form_before_terms' ); ?>
					<tr>
						<td class="label"><label
									for="terms_conditions"><?php esc_html_e( 'Terms & Conditions', 'uncanny-learndash-groups' ); ?></label>
						</td>
						<td class="input">
							<input name="ulgm_terms_conditions" id="terms_conditions"
								   required="required"
								   class="required"
								   value="<?php if ( ulgm_filter_has_var( 'ulgm_terms_conditions', INPUT_POST ) ) {
									   echo sanitize_email( ulgm_filter_input( 'ulgm_terms_conditions', INPUT_POST ) );
								   } ?>"
								   type="checkbox"/><?php echo SharedVariables::ulgm_term_condition(); ?>
						</td>
					</tr>
					<?php do_action( 'ulgm_groups_registration_form_after_terms' ); ?>
				<?php } ?>
				<!-- Terms __ END -->
				<?php do_action( 'ulgm_groups_registration_form_before_buttons' ); ?>
				<!-- Buttons __ START -->
				<tr>
					<td class="input">
						<input type="submit" class="btn btn-default"
							   value="<?php esc_html_e( 'Register your account', 'uncanny-learndash-groups' ); ?>"/>
						<input type="hidden" name="_ulgm_nonce"
							   value="<?php echo wp_create_nonce( Utilities::get_prefix() ); ?>"/>
						<input type="hidden" name="redirect_to"
							   value="<?php echo GroupManagementRegistration::$code_registration_atts['redirect'];
							   ?>"/>
						<input type="hidden" name="_ulgm_role_to_create"
							   value="<?php echo GroupManagementRegistration::$code_registration_atts['role'];
							   ?>"/>
						<input type="hidden" name="_ulgm_auto_login"
							   value="<?php echo GroupManagementRegistration::$code_registration_atts['auto_login'];
							   ?>"/>
						<input type="hidden" name="_ulgm_code_optional"
							   value="<?php echo GroupManagementRegistration::$code_registration_atts['code_optional'];
							   ?>"/>
						<input type="hidden" name="key"
							   value="<?php echo crypt( get_the_ID(), 'uncanny-learndash-groups' ); ?>"/>
					</td>
				</tr>
				<!-- Buttons __ END -->
				<?php do_action( 'ulgm_groups_registration_form_after_buttons' ); ?>
			</table>
			<?php do_action( 'ulgm_groups_registration_form_end' ); ?>
		</fieldset>
	</form>
	<script type='text/javascript'>
		function check(input) {
			if (input.value != document.getElementById('password').value) {
				input.setCustomValidity('<?php _e( 'Passwords do not match.', 'uncanny-learndash-groups' ) ?>')
			} else {
				// input is valid -- reset the error message
				input.setCustomValidity('')
			}
		}
	</script>
<?php
