<?php

namespace uncanny_learndash_codes;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>


<div class="wrap">
    <div class="ulc">

    	<?php

		// Add admin header and tabs
		$tab_active = 'uncanny-codes-license-activation';
		include Config::get_template( 'admin-header.php' );

		?>

		<div class="ulc__admin-content ulc-license <?php echo implode( ' ', $license_css_classes ); ?>">
			<div class="ulc-license-status">
				<div class="ulc-license-status__icon">

					<?php if ( $license_is_active ) { ?>

						<svg class="ulc-license-status-icon__svg" xmlns="http://www.w3.org/2000/svg"
							 xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
							<path class="ulc-license-status-icon__svg-path ulc-license-status-icon__svg-check"
								  d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
						</svg>

					<?php } else { ?>

						<svg class="ulc-license-status-icon__svg" xmlns="http://www.w3.org/2000/svg"
							 xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 352 512">
							<path class="ulc-license-status-icon__svg-path ulc-license-status-icon__svg-times"
								  d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
						</svg>

					<?php } ?>

				</div>
			</div>
			<div class="ulc-license-content">

				<form class="ulc-license-content-form" method="POST">

					<?php wp_nonce_field( 'uo_codes_nonce', 'uo_codes_nonce' ); ?>

					<div class="ulc-license-content-top">
						<div class="ulc-license-content-info">

							<?php ?>

							<div class="ulc-license-content-title">

								<?php

								if ( $license_is_active ) {
									_e( 'Your License is Active!', 'uncanny-learndash-codes' );
								} else {
									_e( 'Your License is Not Active!', 'uncanny-learndash-codes' );
								}

								?>
							</div>

							<div class="ulc-license-content-description">

								<?php

								switch ( $status ) {
									case 'valid':
										break;

									case 'empty':
										_e( 'Please enter a valid license code and click "Activate now".', 'uncanny-learndash-codes' );
										break;

									case 'expired':
										printf(
											_x(
												'Your license has expired. Please %s to get instant access to updates and support.',
												'Your license has expired. Please renew your license to get instant access to updates and support.',
												'uncanny-ceu'
											),
											sprintf(
												'<a href="%s" target="_blank">%s</a>',
												'https://www.uncannyowl.com/checkout/?edd_license_key='.$license.'&download_id=1377',
												_x(
													'renew your license',
													'Your license has expired. Please renew your license to get instant access to updates and support.',
													'uncanny-learndash-codes' )
											)
										);
										break;

									case 'disabled':
										printf(
											_x( 'Your license is disabled. Please %s to get instant access to updates and support.',
												'Your license has disabled. Please renew your license to get instant access to updates and support.',
												'uncanny-learndash-codes' ),
											sprintf(
												'<a href="%s" target="_blank">%s</a>',
												'https://www.uncannyowl.com/checkout/?edd_license_key='.$license.'&download_id=1377',
												_x( 'renew your license',
													'Your license has expired. Please renew your license to get instant access to updates and support.',
													'uncanny-learndash-codes' )
											)
										);
										break;

									case 'invalid':
										_e( 'The license code you entered is invalid.', 'uncanny-learndash-codes' );
										break;
									case 'inactive':
										_e( 'The license code you entered is deactivated.', 'uncanny-learndash-codes' );
										break;
								}

								?>

							</div>

							<div class="ulc-license-content-form">

								<?php if ( $license_is_active ) { ?>

									<input id="ulc-license-field" name="uo_codes_license_key"
										   type="password" value="<?php esc_attr_e( $license ); ?>"
										   placeholder="<?php _e( 'Enter your license key', 'uncanny-learndash-codes' ); ?>"
										   required>

								<?php } else { ?>

									<input id="ulc-license-field" name="uo_codes_license_key" type="text"
										   value="<?php esc_attr_e( $license ); ?>"
										   placeholder="<?php _e( 'Enter your license key', 'uncanny-learndash-codes' ); ?>"
										   required>

								<?php } ?>

							</div>

							<div class="ulc-license-content-mobile-buttons">

								<?php if ( $license_is_active ) { ?>

									<button type="submit" name="uo_codes_license_deactivate"
											class="ulc-license-btn ulc-license-btn--error">
										<?php _e( 'Deactivate License', 'uncanny-learndash-codes' ); ?>
									</button>

								<?php } else { ?>

									<button type="submit" name="uo_codes_license_activate"
											class="ulc-license-btn ulc-license-btn--primary">
										<?php _e( 'Activate now', 'uncanny-learndash-codes' ); ?>
									</button>

									<a href="<?php echo $buy_new_license; ?>" target="_blank"
									   class="ulc-license-btn ulc-license-btn--secondary">
										<?php _e( 'Buy license', 'uncanny-learndash-codes' ); ?>
									</a>

								<?php } ?>

							</div>

						</div>
						<div class="ulc-license-content-faq">
							<div class="ulc-license-content-title">
								<?php _e( 'Need help?', 'uncanny-learndash-codes' ); ?>
							</div>

							<div class="ulc-license-content-faq-list">
								<ul class="ulc-license-content-faq-list-ul">
									<li class="ulc-license-content-faq-item">
										<a href="<?php echo $where_to_get_my_license; ?>" target="_blank">
											<?php _e( 'Where to get my license key', 'uncanny-learndash-codes' ); ?>
										</a>
									</li>
									<li class="ulc-license-content-faq-item">
										<a href="<?php echo $buy_new_license; ?>" target="_blank">
											<?php _e( 'Buy a new license', 'uncanny-learndash-codes' ); ?>
										</a>
									</li>
									<li class="ulc-license-content-faq-item">
										<a href="<?php echo $knowledge_base; ?>" target="_blank">
											<?php _e( 'Knowledge Base', 'uncanny-learndash-codes' ); ?>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="ulc-license-content-footer">

						<?php if ( $license_is_active ) { ?>

							<button type="submit" name="uo_codes_license_deactivate"
									class="ulc-license-btn ulc-license-btn--error">
								<?php _e( 'Deactivate License', 'uncanny-learndash-codes' ); ?>
							</button>

						<?php } else { ?>

							<button type="submit" name="uo_codes_license_activate"
									class="ulc-license-btn ulc-license-btn--primary">
								<?php _e( 'Activate now', 'uncanny-learndash-codes' ); ?>
							</button>

							<a href="<?php echo $buy_new_license; ?>" target="_blank"
							   class="ulc-license-btn ulc-license-btn--secondary">
								<?php _e( 'Buy license', 'uncanny-learndash-codes' ); ?>
							</a>

						<?php } ?>

					</div>

				</form>

			</div>
		</div>
	</div>
</div>