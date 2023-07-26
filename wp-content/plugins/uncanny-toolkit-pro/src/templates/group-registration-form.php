<form id="uncanny_group_signup_registration_form" class="uncanny_group_signup_form" action="" method="POST">
	<fieldset>
		<table class="table table-form form-table clr">
			<tr>
				<td class="label"><label
						for="uncanny_group_signup_user_first"><?php esc_html_e( 'First Name', 'uncanny-pro-toolkit' ); ?></label>
				</td>
				<td class="input"><input name="uncanny_group_signup_user_first"
				                         id="uncanny_group_signup_user_first"
				                         placeholder="<?php esc_html_e( 'First Name', 'uncanny-pro-toolkit' ); ?>"
				                         value="<?php if ( isset( $_POST['uncanny_group_signup_user_first'] ) ) {
					                         echo $_POST['uncanny_group_signup_user_first'];
				                         } ?>"
				                         type="text"/></td>
			</tr>
			<tr>
				<td class="label"><label
						for="uncanny_group_signup_user_last"><?php esc_html_e( 'Last Name', 'uncanny-pro-toolkit' ); ?></label>
				</td>
				<td class="input"><input name="uncanny_group_signup_user_last"
				                         id="uncanny_group_signup_user_last"
				                         placeholder="<?php esc_html_e( 'Last Name', 'uncanny-pro-toolkit' ); ?>"
				                         value="<?php if ( isset( $_POST['uncanny_group_signup_user_last'] ) ) {
					                         echo $_POST['uncanny_group_signup_user_last'];
				                         } ?>"
				                         type="text"/></td>
			</tr>
			<tr>
				<td class="label"><label
						for="uncanny_group_signup_user_Login"><?php esc_html_e( 'Username', 'uncanny-pro-toolkit' ); ?></label>
				</td>
				<td class="input"><input name="uncanny_group_signup_user_login"
				                         id="uncanny_group_signup_user_login"
				                         placeholder="<?php esc_html_e( 'Username', 'uncanny-pro-toolkit' ); ?>"
				                         value="<?php if ( isset( $_POST['uncanny_group_signup_user_login'] ) ) {
					                         echo $_POST['uncanny_group_signup_user_login'];
				                         } ?>"
				                         class="required" type="text"/></td>
			</tr>
			<tr>
				<td class="label"><label
						for="uncanny_group_signup_user_email"><?php esc_html_e( 'Email', 'uncanny-pro-toolkit' ); ?></label>
				</td>
				<td class="input"><input name="uncanny_group_signup_user_email"
				                         id="uncanny_group_signup_user_email"
				                         placeholder="<?php esc_html_e( 'Email', 'uncanny-pro-toolkit' ); ?>"
				                         value="<?php if ( isset( $_POST['uncanny_group_signup_user_email'] ) ) {
					                         echo $_POST['uncanny_group_signup_user_email'];
				                         } ?>"
				                         class="required" type="email"/></td>
			</tr>
			<tr>
				<td class="label">
					<label for="password"><?php esc_html_e( 'Password', 'uncanny-pro-toolkit' ); ?></label>
				</td>
				<td class="input"><input name="uncanny_group_signup_user_pass" id="password"
				                         class="required"
				                         placeholder="<?php esc_html_e( 'Password', 'uncanny-pro-toolkit' ); ?>"
				                         type="password"/></td>
			</tr>
			<tr>
				<td class="label"><label
						for="password_again"><?php esc_html_e( 'Confirm Password', 'uncanny-pro-toolkit' ); ?></label>
				</td>
				<td class="input"><input name="uncanny_group_signup_user_pass_confirm" id="password_again"
				                         class="required"
				                         placeholder="<?php esc_html_e( 'Confirm Password', 'uncanny-pro-toolkit' ); ?>"
				                         type="password"/></td>
			</tr>
			<tr>
				<td class="label">
					<input type="hidden" name="uncanny_group_signup_register_nonce"
					       value="<?php echo wp_create_nonce( 'uncanny_group_signup-register-nonce' ); ?>"/>
					<input type="hidden" name="gid"
					       value="<?php echo get_the_ID(); ?>"/>
					<input type="hidden" name="group_id"
					       value="<?php echo get_the_ID(); ?>"/>
					<input type="hidden" name="key"
					       value="<?php echo crypt( get_the_ID(), 'uncanny-group' ); ?>"/></td>
				<td class="input"><input type="submit" class="btn btn-default"
				                         value="<?php esc_html_e( 'Register Your Account', 'uncanny-pro-toolkit' ); ?>"/>
				</td>
			</tr>
		</table>
	</fieldset>
</form>