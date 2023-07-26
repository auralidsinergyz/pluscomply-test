<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
// New user template variables
$uo_import_users_send_new_user_email     = ( get_option( 'uo_import_users_send_new_user_email' ) === 'true' ) ? 'checked' : '';
$uo_import_users_new_user_email_subject  = get_option( 'uo_import_users_new_user_email_subject', '' );
$uo_import_users_new_user_email_body     = get_option( 'uo_import_users_new_user_email_body', '' );

// Updated user template variables
$uo_import_users_send_updated_user_email = ( get_option( 'uo_import_users_send_updated_user_email' ) === 'true' ) ? 'checked' : '';
$uo_import_users_updated_user_email_subject     = get_option( 'uo_import_users_updated_user_email_subject', '' );
$uo_import_users_updated_user_email_body = get_option( 'uo_import_users_updated_user_email_body', '' );

?>

<form method="post" action="options.php">

	<table class="form-table">

		<tr valign="top" class="options-header-container">
			<th scope="row" colspan="2">
				<h2><?php _e( 'New User Email Template', 'uncanny-pro-toolkit' ); ?></h2>
			</th>
		</tr>

		<tr valign="top" class="option-setting-container">
			<th scope="row"><h4><?php _e( 'Email to New Users', 'uncanny-pro-toolkit' ); ?></h4></th>
			<td>
				<label>
					<input type="checkbox" name="uo_import_email_send_new_users"
					       id="uo_import_email_send_new_users" <?php echo $uo_import_users_send_new_user_email ?> />
					Send Email
				</label>
			</td>
		</tr>

		<tr valign="top" class="option-setting-container">
			<th scope="row"><h4><?php _e( 'Subject', 'uncanny-pro-toolkit' ); ?></h4></th>
			<td>
				<input width="300px" type="text" name="uo_import_email_new_users_subject" id="uo_import_email_new_users_subject"
				       value="<?php echo $uo_import_users_new_user_email_subject; ?>"/>
			</td>
		</tr>

		<tr valign="top">

			<th scope="row">
				<h4><?php _e( 'Body', 'uncanny-pro-toolkit' ); ?></h4>
				<h5><?php _e( 'Variables', 'uncanny-pro-toolkit' ); ?></h5>
				<ul class="import-user-list">
					<li>%Site URL%</li>
					<li>%Login URL%</li>
					<li>%Email%</li>
					<li>%Username%</li>
					<li>%First Name%</li>
					<li>%Last Name%</li>
					<li>%Display Name%</li>
					<li>%Password%</li>
					<li>%LD Courses%</li>
					<li>%LD Groups%</li>
				</ul>
			</th>

			<td><?php wp_editor( $uo_import_users_new_user_email_body, 'uo_import_users_new_user_email_body' ); ?></td>
		</tr>

		<tr valign="top" class="option-setting-container">
			<th scope="row"><h4><?php _e( 'Test Email Address:', 'uncanny-pro-toolkit' ); ?></h4></th>
			<td>
				<label>
					<input type="text" name="uo_import_email_new_users_test_email" id="uo_import_email_new_users_test_email"/>
					<input type="submit" id="btn-test_new_user_template" class="button button-secondary" value="Send Test Email"/>
				</label>
			</td>
		</tr>

		<tr class="options-spacer"></tr>

		<tr valign="top" class="options-header-container">
			<th colspan="2">
				<h2><?php _e( 'Updated User Email Template', 'uncanny-pro-toolkit' ); ?></h2>
			</th>
		</tr>

		<tr valign="top" class="option-setting-container">
			<th scope="row"><h4><?php _e( 'Email to Updated Users', 'uncanny-pro-toolkit' ); ?></h4></th>
			<td>
				<label>
					<input type="checkbox" name="uo_import_email_send_updated_users"
					       id="uo_import_email_send_updated_users" <?php echo $uo_import_users_send_updated_user_email; ?> />
					Send Email
				</label>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><h4><?php _e( 'Subject', 'uncanny-pro-toolkit' ); ?></h4></th>
			<td>
				<input type="text" name="uo_import_email_updated_users_subject"
				       id="uo_import_email_updated_users_subject"
				       value="<?php echo $uo_import_users_updated_user_email_subject; ?>"/>
			</td>
		</tr>

		<tr valign="top">

			<th scope="row">
				<h4><?php _e( 'Body', 'uncanny-pro-toolkit' ); ?></h4>
				<h5><?php _e( 'Variables', 'uncanny-pro-toolkit' ); ?></h5>
				<ul class="import-user-list">
					<li>%Site URL%</li>
					<li>%Login URL%</li>
					<li>%Email%</li>
					<li>%Username%</li>
					<li>%First Name%</li>
					<li>%Last Name%</li>
					<li>%Display Name%</li>
					<li>%Password%</li>
					<li>%LD Courses%</li>
					<li>%LD Groups%</li>
				</ul>
			</th>
			<td><?php wp_editor( $uo_import_users_updated_user_email_body, 'uo_import_users_updated_user_email_body' ); ?></td>
		</tr>


		<tr valign="top" class="option-setting-container">
			<th scope="row"><h4><?php _e( 'Test Email Address:', 'uncanny-pro-toolkit' ); ?></h4></th>
			<td>
				<label>
					<input type="text" name="uo_import_email_updated_users_test_email"  id="uo_import_email_updated_users_test_email"/>
					<input type="submit" id="btn-test_updated_user_template" class="button button-secondary" value="Send Test Email"/>
				</label>
			</td>
		</tr>

		<tr class="options-spacer"></tr>

	</table>

	<input type="submit" id="btn-save_template" class="button button-primary" value="Save Changes"/>

</form>