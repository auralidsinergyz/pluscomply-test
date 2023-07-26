<?php

namespace uncanny_learndash_codes;
$atts = $GLOBALS['atts'];
if ( isset( $_GET['registered'] ) ) { ?>
	<div class="registered">
		<h2><?php _e( 'Congratulations! You have successfully registered your account.', 'uncanny-learndash-codes' ) ?></h2>
	</div>
	<?php
} else {
	$default = array(
		'redirect'      => '',
		'code_optional' => 'no',
		'auto_login'    => 'yes',
		'role'          => 'subscriber',
	);
	if ( is_multisite() ) {
		$options = get_blog_option( get_current_blog_id(), 'uncanny-codes-custom-registration-atts', $default );
	} else {
		$options = get_option( 'uncanny-codes-custom-registration-atts', $default );
	}
	?>

	<form id="uncanny-learndash-codes-registration_form" class="uncanny-learndash-codes-registration uncanny-learndash-codes" action="" method="POST">
		<fieldset>
			<table class="table table-form form-table clr">
				<tr>
					<td class="label"><label
								for="uncanny-learndash-codes-user_first"><?php esc_html_e( 'First Name', 'uncanny-learndash-codes' ); ?></label>
					</td>
					<td class="input"><input name="uncanny-learndash-codes-user_first"
					                         id="uncanny-learndash-codes-user_first"
							<?php /* placeholder="<?php esc_html_e( 'First Name', 'uncanny-learndash-codes' ); ?>" */ ?>
							                 required="required"
							                 value="<?php if ( isset( $_POST['uncanny-learndash-codes-user_first'] ) ) {
								                 echo $_POST['uncanny-learndash-codes-user_first'];
							                 } ?>"
							                 type="text"/></td>
				</tr>
				<tr>
					<td class="label"><label
								for="uncanny-learndash-codes-user_last"><?php esc_html_e( 'Last Name', 'uncanny-learndash-codes' ); ?></label>
					</td>
					<td class="input"><input name="uncanny-learndash-codes-user_last"
					                         id="uncanny-learndash-codes-user_last"
					                         required="required"
							<?php /* placeholder="<?php esc_html_e( 'Last Name', 'uncanny-learndash-codes' ); ?>" */ ?>
							                 value="<?php if ( isset( $_POST['uncanny-learndash-codes-user_last'] ) ) {
								                 echo $_POST['uncanny-learndash-codes-user_last'];
							                 } ?>"
							                 type="text"/></td>
				</tr>
				<tr>
					<td class="label"><label
								for="uncanny-learndash-codes-user_email"><?php esc_html_e( 'Email / Username', 'uncanny-learndash-codes' ); ?></label>
					</td>
					<td class="input"><input name="uncanny-learndash-codes-user_email"
					                         id="uncanny-learndash-codes-user_email"
					                         required="required"
							<?php /* placeholder="<?php esc_html_e( 'Email / Username', 'uncanny-learndash-codes' ); ?>" */ ?>
							                 value="<?php if ( isset( $_POST['uncanny-learndash-codes-user_email'] ) ) {
								                 echo $_POST['uncanny-learndash-codes-user_email'];
							                 } ?>"
							                 class="required" type="email"/></td>
				</tr>
				<tr>
					<td class="label"><label
								for="password"><?php esc_html_e( 'Password', 'uncanny-learndash-codes' ); ?></label>
					</td>
					<td class="input"><input name="uncanny-learndash-codes-user_pass" id="password"
					                         required="required"
					                         minlength="6"
					                         class="required"
							<?php /* placeholder="<?php esc_html_e( 'Password', 'uncanny-learndash-codes' ); ?>" */ ?>
							                 type="password"/></td>
				</tr>
				<tr>
					<td class="label"><label
								for="password_again"><?php esc_html_e( 'Confirm Password', 'uncanny-learndash-codes' ); ?></label>
					</td>
					<td class="input"><input name="uncanny-learndash-codes-user_pass_confirm" id="password_again"
					                         required="required"
					                         minlength="6"
					                         class="required"
							<?php /* placeholder="<?php esc_html_e( 'Confirm Password', 'uncanny-learndash-codes' ); ?>" */ ?>
							                 type="password"/></td>
				</tr>
				<tr>
					<td class="label"><label
								for="code_registration"><?php esc_html_e( 'Registration Code', 'uncanny-learndash-codes' ); ?></label>
					</td>
					<td class="input"><input name="uncanny-learndash-codes-code_registration" id="code_registration"
					                         <?php if ( 'no' === $options['code_optional'] ){ ?>required="required"
					                         class="required"<?php } ?>
					                         value="<?php if ( isset( $_POST['uncanny-learndash-codes-code_registration'] ) ) {
						                         echo $_POST['uncanny-learndash-codes-code_registration'];
					                         } ?>"
							<?php /* placeholder="<?php esc_html_e( 'Registration Code', 'uncanny-learndash-codes' ); ?>" */ ?>
							                 type="text"/></td>
				</tr>
				<tr>
					<td class="label">
						<input type="hidden" name="_uo_nonce"
						       value="<?php echo wp_create_nonce( Config::get_project_name() ); ?>"/>
						<input type="hidden" name="redirect_to"
						       value="<?php echo $atts['redirect']; ?>"/>
						<input type="hidden" name="key"
						       value="<?php echo crypt( get_the_ID(), 'uncanny-learndash-codes' ); ?>"/></td>
					<td class="input">
						<input type="submit" class="btn btn-default" value="<?php esc_html_e( 'Register Your Account', 'uncanny-learndash-codes' ); ?>"/>
					</td>
				</tr>
			</table>
		</fieldset>
	</form>
<?php } ?>