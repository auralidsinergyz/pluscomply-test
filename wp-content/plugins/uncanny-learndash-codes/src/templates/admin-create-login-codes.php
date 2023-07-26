<?php

namespace uncanny_learndash_codes;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="uo-ulc-admin wrap">
	<div class="ulc">

		<?php 

		// Add admin header and tabs
		$tab_active = 'uncanny-learndash-codes';
		include Config::get_template( 'admin-header.php' );

		?>

		<div class="ulc__admin-content">
			<div id="page_create_coupon">
				<div class="uo-admin-section">
					<div class="uo-admin-header">
						<div class="uo-admin-title"><?php echo __('Generate Codes', 'uncanny-learndash-codes'); ?></div>
						<div class="uo-admin-description">
							<?php printf( __( 'Generate your codes from this page to allow users to register directly into LearnDash Groups and courses. For more information on how to use the plugin, please refer to the <a href="%s" target="_blank" >Knowledge Base articles</a>.', 'uncanny-learndash-codes' ), esc_url( 'https://www.uncannyowl.com/article-categories/uncanny-learndash-code' ) ); ?>
						</div>
					</div>

					<div class="uo-clear"></div>

					<div class="uo-admin-block">
						<form id="uncanny-form-create-coupon" method="post" action="">
							<div class="uo-admin-form">

								<div class="uo-admin-field" id="tr-coupon-for">
									<div class="uo-admin-label"><?php echo __('Generate Code for', 'uncanny-learndash-codes'); ?></div>

									<label class="uo-radio">
										<input name="coupon-for" checked="checked" type="radio" value="course"/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
											<?php echo __('LearnDash Course(s)', 'uncanny-learndash-codes'); ?>
										</span>
									</label>

									<label class="uo-radio">
										<input name="coupon-for" type="radio" value="group"/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
											<?php echo __('LearnDash Group(s)', 'uncanny-learndash-codes'); ?>
										</span>
									</label>
								</div>

								<div class="uo-admin-field" id="tr-coupon-paid-unpaid">
									<div class="uo-admin-label"><?php echo __('Code Type', 'uncanny-learndash-codes'); ?></div>

									<label class="uo-radio">
										<input name="coupon-paid-unpaid" checked="checked" type="radio" id="coupon-paid-unpaid" value="default"/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
											<?php echo __('Default', 'uncanny-learndash-codes'); ?>
										</span>
									</label>

									<label class="uo-radio">
										<input name="coupon-paid-unpaid" type="radio" id="coupon-paid-unpaid" value="paid"/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
											<?php echo __('Paid', 'uncanny-learndash-codes'); ?>
										</span>
									</label>

									<label class="uo-radio">
										<input name="coupon-paid-unpaid" type="radio" id="coupon-paid-unpaid" value="unpaid"/>
										<div class="uo-checkmark"></div>
										<span class="uo-label">
											<?php echo __('Unpaid', 'uncanny-learndash-codes'); ?>
										</span>
									</label>
								</div>

								<div class="uo-admin-field">
									<div class="uo-admin-label"><?php echo __('Number of Unique Codes', 'uncanny-learndash-codes'); ?></div>

									<input class="uo-admin-input" name="coupon-amount" type="number" required id="coupon-amount" value="1">
								</div>

								<div class="uo-admin-field">
									<div class="uo-admin-label"><?php echo __('Number of Uses Per Code', 'uncanny-learndash-codes'); ?></div>

									<input class="uo-admin-input" name="coupon-max-usage" type="number" required id="coupon-max-usage" value="1">
								</div>

								<div class="uo-admin-field">
									<div class="uo-admin-label"><?php echo __('Number of Characters', 'uncanny-learndash-codes'); ?></div>

									<input class="uo-admin-input" name="coupon-length" type="number" required id="coupon-length" value="20">
								</div>

								<div class="uo-admin-field">
									<div class="uo-admin-label"><?php echo __('Dash Separation', 'uncanny-learndash-codes'); ?></div>

									<input class="uo-admin-input" name="coupon-dash" type="text" required id="coupon-dash" value="4-4-4-4-4">
								</div>

								<div class="uo-admin-field uo-hidden" id="tr-course-group">
									<div class="uo-admin-label"><?php echo __('Groups', 'uncanny-learndash-codes'); ?></div>

									<select class="uo-admin-select" name="coupon-group[]" id="coupon-group" multiple="multiple">
										<?php foreach ( \uncanny_learndash_codes\GenerateCodes::$groups as $group ){ ?>
											<option value="<?php echo esc_attr( $group->ID ) ?>"><?php echo esc_attr( $group->post_title ) ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="uo-admin-field" id="tr-course-courses">
									<div class="uo-admin-label"><?php echo __('Courses', 'uncanny-learndash-codes'); ?></div>

									<select  class="uo-admin-select" name="coupon-courses[]" id="coupon-courses" multiple="multiple">
										<?php foreach ( \uncanny_learndash_codes\GenerateCodes::$courses as $course ){ ?>
											<option value="<?php echo esc_attr( $course->ID ) ?>"><?php echo esc_attr( $course->post_title ) ?></option>
										<?php } ?>
									</select>
								</div>

			                    <div class="uo-admin-field">
			                        <div class="uo-admin-label"><?php echo __('Expiry Date', 'uncanny-learndash-codes'); ?></div>

			                        <input class="uo-admin-input" name="expiry-date" type="date" id="expiry-date" value="">
			                    </div>
			                    <div class="uo-admin-field">
			                        <div class="uo-admin-label"><?php echo __('Expiry Time', 'uncanny-learndash-codes'); ?></div>

			                        <input class="uo-admin-input" name="expiry-time" type="time" id="expiry-time" value="">
			                    </div>
								<div class="uo-admin-field" id="tr-coupon-prefix">
									<div class="uo-admin-label"><?php echo __('Prefix', 'uncanny-learndash-codes'); ?></div>

									<input class="uo-admin-input" name="coupon-prefix" type="text" id="coupon-prefix" value=""/>
								</div>

								<div class="uo-admin-field" id="tr-coupon-suffix">
									<div class="uo-admin-label"><?php echo __('Suffix', 'uncanny-learndash-codes'); ?></div>

									<input class="uo-admin-input" name="coupon-suffix" type="text" id="coupon-suffix" value=""/>
								</div>

								<div class="uo-admin-field" id="coupon-presuffix">
									<div id="coupon-render">
										<span class="coupon-render__title">
											<?php echo __('Sample code', 'uncanny-learndash-codes'); ?>
										</span>
										<span id="coupon-render__fakecode"></span>
									</div>
								</div>

								<input type="hidden" name="_custom_wpnonce" value="<?php echo wp_create_nonce( \uncanny_learndash_codes\Config::get_project_name() ) ?>">

								<div class="uo-admin-field">
									<input type="submit" name="submit" id="submit" class="uo-admin-form-submit" value="<?php _e('Generate Codes', 'uncanny-learndash-codes'); ?>">
								</div>

							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>