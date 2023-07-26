<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

$name  = '';
$email = '';

if ( $license ) {
	$json = wp_remote_get( 'https://www.uncannyowl.com/wp-json/uncanny-rest-api/v1/license/' . $license . '?wpnonce=' . wp_create_nonce( time() ) );

	if ( ! is_wp_error( $json ) ) {
		if ( 200 === wp_remote_retrieve_response_code( $json ) ) {
			$data = json_decode( $json['body'], true );

			if ( $data ) {
				$name  = $data['name'];
				$email = $data['email'];
			}
		}
	}
}

ob_start();
include Utilities::get_template( 'admin/admin-siteinfo.php' );
$installation_information = ob_get_clean();

?>

<div class="wrap">
	<div class="ulgm">

		<?php

		// Add admin header and tabs
		$tab_active = 'uncanny-groups-kb';
		include Utilities::get_template( 'admin/admin-header.php' );

		?>

		<div class="ulgm-help">

			<?php if ( $license_is_active ): ?>

				<?php if ( isset( $_GET['sent'] ) ): ?>

					<div id="message" class="updated notice is-dismissible">
						<p>
							<?php _e( 'Your ticket has been created. Someone at Uncanny Owl will contact you regarding your issue.', 'uncanny-learndash-groups' ); ?>
						</p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">
								<?php _e( 'Dismiss this notice.', 'uncanny-learndash-groups' ); ?>
							</span>
						</button>
					</div>

				<?php endif; ?>

				<div class="uo-core">
					<div class="uo-send-ticket">
						<div class="uo-send-ticket__form">
							<div class="uo-send-ticket__title">
								<?php _e( 'Submit a ticket', 'uncanny-learndash-groups' ); ?>
							</div>

							<form name="uncanny-help" method="POST" action="<?php echo admin_url( 'admin.php' ) ?>">
								<?php wp_nonce_field( 'uncanny0w1', 'ulgm-send-ticket' ); ?>

								<textarea class="uo-send-ticket__hidden-field"
										  name="siteinfo"><?php echo $installation_information; ?></textarea>

								<input type="hidden" value="uncanny-groups-kb&send-ticket=true" name="page"/>

								<div class="uo-send-ticket-form__row">
									<label for="uo-fullname" class="uo-send-ticket-form__label">
										<?php _e( 'Full name', 'uncanny-learndash-groups' ); ?>
									</label>
									<input required name="fullname" id="uo-fullname" type="text"
										   class="uo-send-ticket-form__text"
										   value="<?php echo $name; ?>">
								</div>

								<div class="uo-send-ticket-form__row">
									<label for="uo-email" class="uo-send-ticket-form__label">
										<?php _e( 'Email', 'uncanny-learndash-groups' ); ?>
									</label>
									<input required name="email" id="uo-email" type="email"
										   class="uo-send-ticket-form__text"
										   value="<?php echo $email; ?>">
								</div>

								<div class="uo-send-ticket-form__row">
									<label for="uo-website" class="uo-send-ticket-form__label">
										<?php _e( 'Site URL', 'uncanny-learndash-groups' ); ?>
									</label>
									<input required name="website" id="uo-website" type="url"
										   class="uo-send-ticket-form__text"
										   readonly value="<?php echo get_bloginfo( 'url' ) ?>">
								</div>

								<div class="uo-send-ticket-form__row">
									<label for="license_key" class="uo-send-ticket-form__label">
										<?php _e( 'License Key', 'uncanny-learndash-groups' ); ?>
									</label>
									<input required name="license_key" id="license_key" type="text"
										   class="uo-send-ticket-form__text"
										   readonly value="<?php echo $license ?>">
								</div>

								<div class="uo-send-ticket-form__row">
									<label for="uo-subject" class="uo-send-ticket-form__label">
										<?php _e( 'Subject', 'uncanny-learndash-groups' ); ?>
									</label>
									<input required name="subject" id="uo-subject" type="text"
										   class="uo-send-ticket-form__text"
										   value="">
								</div>

								<div class="uo-send-ticket-form__row">
									<label for="uo-message" class="uo-send-ticket-form__label">
										<?php _e( 'Message', 'uncanny-learndash-groups' ); ?>
									</label>
									<textarea required name="message" id="uo-message"
											  class="uo-send-ticket-form__textarea"></textarea>
								</div>

								<div class="uo-send-ticket-form__row">
									<input type="checkbox" value="yes" name="site-data"
										   checked="checked"> <?php _e( 'Send site data', 'uncanny-learndash-groups' ); ?>
								</div>

								<div class="uo-send-ticket-form__row">
									<p>
										<?php echo printf( _x( "Emails must be enabled on your site to create a ticket using this form. If you don't receive a confirmation email shortly after submitting this form, please log the ticket through your %s page.", '%s is the "My Account" page', 'uncanny-learndash-groups' ), sprintf( '<a href="https://www.uncannyowl.com/my-account/submit-a-request/?utm_medium=uo_groups&utm_campaign=support_page" target="_blank" rel="noreferrer">%s</a>', __( 'My Account', 'uncanny-learndash-groups' ) ) ); ?>
									</p>
									<button type="submit" class="uo-send-ticket-form__submit">
										<?php _e( 'Create ticket', 'uncanny-learndash-groups' ); ?>
									</button>
								</div>
							</form>
						</div>
						<div class="uo-send-ticket__data">
							<div class="uo-send-ticket__title">
								<?php _e( 'Site data', 'uncanny-learndash-groups' ); ?>
							</div>

							<?php echo $installation_information; ?>
						</div>
					</div>
				</div>

			<?php endif; ?>

		</div>
	</div>
</div>
