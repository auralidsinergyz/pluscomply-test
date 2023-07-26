<?php

namespace uncanny_learndash_codes;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class AdminMenu
 * @package uncanny_learndash_codes
 */
class AdminMenu extends Boot {

	/**
	 * class constructor
	 */
	public function __construct() {
		// Setup Theme Options Page Menu in Admin
		if ( is_admin() ) {
			add_action( 'admin_menu', array( __CLASS__, 'register_options_menu_page' ) );
			add_action( 'admin_init', array( __CLASS__, 'save_form_settings' ) );
		}

	}

	/**
	 * Create Plugin options menu
	 */
	public static function register_options_menu_page() {

		$page_title = esc_html__( 'Uncanny LearnDash Codes', 'uncanny-learndash-codes' );
		$menu_title = esc_html__( 'Uncanny Codes', 'uncanny-learndash-codes' );
		$capability = 'manage_options';
		$menu_slug  = 'uncanny-learndash-codes';
		$function   = array( __CLASS__, 'options_menu_page_output' );

		$icon_url   = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDU4MSA2NDAiIHZlcnNpb249IjEuMSIgdmlld0JveD0iMCAwIDU4MSA2NDAiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibTUyNi40IDM0LjFjMC42IDUgMSAxMC4xIDEuMyAxNS4xIDAuNSAxMC4zIDEuMiAyMC42IDAuOCAzMC45LTAuNSAxMS41LTEgMjMtMi4xIDM0LjQtMi42IDI2LjctNy44IDUzLjMtMTYuNSA3OC43LTcuMyAyMS4zLTE3LjEgNDEuOC0yOS45IDYwLjQtMTIgMTcuNS0yNi44IDMzLTQzLjggNDUuOS0xNy4yIDEzLTM2LjcgMjMtNTcuMSAyOS45LTI1LjEgOC41LTUxLjUgMTIuNy03Ny45IDEzLjggNzAuMyAyNS4zIDEwNi45IDEwMi44IDgxLjYgMTczLjEtMTguOSA1Mi42LTY4LjEgODguMS0xMjQgODkuNWgtNi4xYy0xMS4xLTAuMi0yMi4xLTEuOC0zMi45LTQuNy0yOS40LTcuOS01NS45LTI2LjMtNzMuNy01MC45LTI5LjItNDAuMi0zNC4xLTkzLjEtMTIuNi0xMzgtMjUgMjUuMS00NC41IDU1LjMtNTkuMSA4Ny40LTguOCAxOS43LTE2LjEgNDAuMS0yMC44IDYxLjEtMS4yLTE0LjMtMS4yLTI4LjYtMC42LTQyLjkgMS4zLTI2LjYgNS4xLTUzLjIgMTIuMi03OC45IDUuOC0yMS4yIDEzLjktNDEuOCAyNC43LTYwLjlzMjQuNC0zNi42IDQwLjYtNTEuM2MxNy4zLTE1LjcgMzcuMy0yOC4xIDU5LjEtMzYuOCAyNC41LTkuOSA1MC42LTE1LjIgNzYuOC0xNy4yIDEzLjMtMS4xIDI2LjctMC44IDQwLjEtMi4zIDI0LjUtMi40IDQ4LjgtOC40IDcxLjMtMTguMyAyMS05LjIgNDAuNC0yMS44IDU3LjUtMzcuMiAxNi41LTE0LjkgMzAuOC0zMi4xIDQyLjgtNTAuOCAxMy0yMC4yIDIzLjQtNDIuMSAzMS42LTY0LjcgNy42LTIxLjEgMTMuNC00Mi45IDE2LjctNjUuM3ptLTI3OS40IDMyOS41Yy0xOC42IDEuOC0zNi4yIDguOC01MC45IDIwLjQtMTcuMSAxMy40LTI5LjggMzIuMi0zNi4yIDUyLjktNy40IDIzLjktNi44IDQ5LjUgMS43IDczIDcuMSAxOS42IDE5LjkgMzcuMiAzNi44IDQ5LjYgMTQuMSAxMC41IDMwLjkgMTYuOSA0OC40IDE4LjZzMzUuMi0xLjYgNTEtOS40YzEzLjUtNi43IDI1LjQtMTYuMyAzNC44LTI4LjEgMTAuNi0xMy40IDE3LjktMjkgMjEuNS00NS43IDQuOC0yMi40IDIuOC00NS43LTUuOC02Ni45LTguMS0yMC0yMi4yLTM3LjYtNDAuMy00OS4zLTE4LTExLjctMzkuNS0xNy02MS0xNS4xeiIgZmlsbD0iIzgyODc4QyIvPjxwYXRoIGQ9Im0yNDIuNiA0MDIuNmM2LjItMS4zIDEyLjYtMS44IDE4LjktMS41LTExLjQgMTEuNC0xMi4yIDI5LjctMS44IDQyIDExLjIgMTMuMyAzMS4xIDE1LjEgNDQuNCAzLjkgNS4zLTQuNCA4LjktMTAuNCAxMC41LTE3LjEgMTIuNCAxNi44IDE2LjYgMzkuNCAxMSA1OS41LTUgMTguNS0xOCAzNC42LTM1IDQzLjUtMzQuNSAxOC4yLTc3LjMgNS4xLTk1LjUtMjkuNS0xLTItMi00LTIuOS02LjEtOC4xLTE5LjYtNi41LTQzIDQuMi02MS4zIDEwLTE3IDI2LjgtMjkuMiA0Ni4yLTMzLjR6IiBmaWxsPSIjODI4NzhDIi8+PC9zdmc+';

		$position = 82; // 81 - Above Settings Menu
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

		add_submenu_page( $menu_slug, 'Generate Codes', 'Generate Codes', $capability, 'uncanny-learndash-codes' );
		add_submenu_page( $menu_slug, 'View Codes', 'View Codes', $capability, 'uncanny-learndash-codes-view', array(
			__CLASS__,
			'options_menu_view_codes',
		) );
		add_submenu_page( $menu_slug, 'Settings', 'Settings', $capability, 'uncanny-learndash-codes-settings', array(
			__CLASS__,
			'options_menu_settings_page',
		) );
	}

	/**
	 * Create Theme Options page
	 */
	public static function options_menu_page_output() {
		global $uncanny_learndash_codes;
		include 'templates/admin-create-login-codes.php';
	}

	/**
	 *
	 */
	public static function options_menu_view_codes() {

		if ( empty( $_GET['group_id'] ) && empty( $_GET['mode'] ) ) { // Group View
			self::display_code_groups();

		} elseif ( ! empty( $_GET['group_id'] ) && empty( $_GET['mode'] ) ) { // Coupon View
			self::display_group_codes();
		}

	}

	/**
	 *
	 */
	public static function display_code_groups() {
		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		include_once 'classes/view-groups.php';
		$table = new ViewGroups();
		$table->prepare_items();

		include Config::get_template( 'admin-view-codes.php' );
	}

	/**
	 *
	 */
	public static function display_group_codes() {
		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		include_once 'classes/view-codes.php';
		$table = new ViewCodes( [ 'group_id' => '' ] );
		$table->prepare_items( $_GET['group_id'] );
		?>

		<div class="wrap uo-ulc-admin">
			<div class="ulc">
				<div id="page_coupon_stat">

					<?php 

					// Add admin header and tabs
					$tab_active = 'uncanny-learndash-codes-view';
					include Config::get_template( 'admin-header.php' );

					?>

					<div class="ulc__admin-content">

						<h2></h2> <!-- LearnDash notice will be shown here -->

						<h1 class="wp-heading-inline"><?php echo __('View Generated Codes', 'uncanny-learndash-codes'); ?></h1>
						<hr class="wp-header-end">

						<div class="uo-codes-heading">
							<form class="uo-codes-search" method="get" action="">
								<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
								<?php if ( ! empty( $_GET['group_id'] )){?>
			                        <input type="hidden" name="group_id" value="<?php echo $_GET['group_id'] ?>"/>
								<?php }?>
								<?php $table->search_box( __('Search Codes', 'uncanny-learndash-codes'), Config::get_project_name() ); ?>
							</form>
						</div>

						<div class="uo-codes-buttons">
							<?php $table->views(); ?>
						</div>

						<div class="uo-codes-list">
							<?php $table->display(); ?>
						</div>

					</div>
				</div>
			</div>
		</div>
		<?php

	}

	/**
	 *
	 */
	public static function options_menu_settings_page() {
		?>

		<div class="wrap uo-ulc-admin">
			<div class="ulc">
				<?php 

				// Add admin header and tabs
				$tab_active = 'uncanny-learndash-codes-settings';
				include Config::get_template( 'admin-header.php' );

				?>

				<div class="ulc__admin-content">

					<h2></h2> <!-- LearnDash notice will be shown here -->

				    <!-- Notifications -->

					<?php if ( isset($_REQUEST['saved']) &&$_REQUEST['saved'] ) { ?>

						<div class="updated notice"><?php _e( 'Settings Saved!', 'uncanny-learndash-codes' ); ?></div>

					<?php } elseif ( isset( $_REQUEST['force_downloaded'] ) && $_REQUEST['force_downloaded'] ) { ?>

						<div class="notice error">
							<?php _e( 'Failed to create Theme My Login form! Download', 'uncanny-learndash-codes' ); ?>

							<a target="_blank" href="<?php echo add_query_arg( array( 'mode' => 'download_file' ), remove_query_arg( array(
								'redirect_nonce',
								'mode',
								'saved',
								'force_downloaded',
							) ) ) ?>">
								register-form.php
							</a>

							<?php _e( 'and upload to your theme\'s directory ( /wp-content/themes/YOUR-THEME/theme-my-login/ ) via (S)FTP.', 'uncanny-learndash-codes' ); ?>
						</div>

					<?php } ?>

					<div class="notice notice-error" id="registration_form_error" style="display: none"><h4></h4></div>

					<form method="post" action="" id="uncanny-learndash-codes-form">
						<input type="hidden" name="_wp_http_referer"
						       value="<?php echo admin_url( 'admin.php?page=uncanny-learndash-codes-settings&saved=true' ) ?>"/>
						<input type="hidden" name="_wpnonce"
						       value="<?php echo wp_create_nonce( Config::get_project_name() ); ?>"/>

						<?php
						if ( is_multisite() ) {
							$existing                 = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms, 0 );
							$code_field_mandatory     = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_mandatory, 0 );
							$code_field_label         = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_label, null );
							$code_field_error_message = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_error, null );
							$code_field_placeholder   = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_placeholder, null );
							$group_settings           = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_multiple_groups, 0 );
							$custom_messages          = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_custom_messages, null );
						} else {
							$existing                 = get_option( Config::$uncanny_codes_settings_gravity_forms, 0 );
							$code_field_mandatory     = get_option( Config::$uncanny_codes_settings_gravity_forms_mandatory, 0 );
							$code_field_label         = get_option( Config::$uncanny_codes_settings_gravity_forms_label, null );
							$code_field_error_message = get_option( Config::$uncanny_codes_settings_gravity_forms_error, null );
							$code_field_placeholder   = get_option( Config::$uncanny_codes_settings_gravity_forms_placeholder, null );
							$group_settings           = get_option( Config::$uncanny_codes_settings_multiple_groups, 0 );
							$custom_messages          = get_option( Config::$uncanny_codes_settings_custom_messages, null );
						}

						if ( class_exists( 'GFFormsModel' ) && class_exists( 'GF_User_Registration' ) ) {
							$forms = \GFFormsModel::get_forms();

							foreach ( $forms as $form ) {
								$results = \GF_User_Registration::get_config( $form->id );
								if ( $results ) {
									//foreach ( $results as $result ) {
									printf( '<div id="form-id-%d" style="display:none" data-register="1"></div>', $form->id );
									//}
								} else {
									printf( '<div id="form-id-%d" style="display:none" data-register="0"></div>', $form->id );
								}
							}

							?>

							<!-- Gravity Forms -->
							<div class="uo-admin-section">
								<div class="uo-admin-header">
									<div class="uo-admin-title"><?php esc_html_e( 'Gravity Forms Registration Settings', 'uncanny-learndash-codes' ); ?></div>
								</div>
								<div class="uo-admin-block">
									<div class="uo-admin-form">

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'Select form', 'uncanny-learndash-codes' ); ?></div>

											<select class="uo-admin-select" name="registration_form" id="registration_form">
												<option value="0"><?php _e( 'Select form', 'uncanny-learndash-codes' ); ?></option>
												<?php foreach ( $forms as $form ) { ?>
													<option
														<?php if ( $form->id === $existing ) {
															echo 'selected="selected"';
														} ?>
															value="<?php echo esc_attr( $form->id ) ?>"><?php echo esc_html( $form->title ) ?></option>
												<?php } ?>
											</select>
										</div>

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'Mandatory Code Field', 'uncanny-learndash-codes' ); ?></div>

											<label class="uo-checkbox">
												<input type="checkbox" value="1" name="registration-field-mandatory" id="registration-field-mandatory"<?php if ( 1 === intval( $code_field_mandatory ) ) {
													echo 'checked="checked"';
												} ?>>
												<div class="uo-checkmark"></div>
												<span class="uo-label">
													<?php _e( 'Make Code field mandatory on User Registration form.', 'uncanny-learndash-codes' ); ?>
												</span>
											</label>
										</div>

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'User registration field label', 'uncanny-learndash-codes' ); ?></div>

											<input class="uo-admin-input" type="text" value="<?php if ( null !== $code_field_label ) {
												echo esc_html( $code_field_label );
											} ?>" name="code_field_label" id="code_field_label" class="widefat" placeholder="<?php _e( 'Enter Registration Code', 'uncanny-learndash-codes' ); ?>"/>
										</div>

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'User registration field error message', 'uncanny-learndash-codes' ); ?></div>

											<input class="uo-admin-input" type="text" value="<?php if ( null !== $code_field_error_message ) {
												echo esc_html( $code_field_error_message );
											} ?>" name="code_field_error_message" id="code_field_error_message" class="widefat" placeholder="<?php _e( 'This Field is Mandatory', 'uncanny-learndash-codes' ); ?>"/>
										</div>

										<div class="uo-admin-field">
											<div class="uo-admin-label"><?php _e( 'User registration field placeholder', 'uncanny-learndash-codes' ); ?></div>

											<input class="uo-admin-input" type="text" value="<?php if ( null !== $code_field_placeholder ) {
												echo esc_html( $code_field_placeholder );
											} ?>" name="code_field_placeholder" id="code_field_placeholder" class="widefat" placeholder="<?php _e( 'Enter Code', 'uncanny-learndash-codes' ); ?>"/>
										</div>

										<div class="uo-admin-field">
											<input type="submit" name="submit" id="submit" class="uo-admin-form-submit" value="<?php _e( 'Save Changes', 'uncanny-learndash-codes' ); ?>">
										</div>

									</div>
								</div>
							</div>

						<?php } ?>

						<!-- LearnDash Group Settings -->
						<div class="uo-admin-section">
							<div class="uo-admin-header">
								<div class="uo-admin-title"><?php _e( 'LearnDash Group Settings', 'uncanny-learndash-codes' ); ?></div>
							</div>
							<div class="uo-admin-block">
								<div class="uo-admin-form">
									<div class="uo-admin-field">
										<label class="uo-checkbox">
											<input type="checkbox" value="1" name="allow-multiple-group-registration" id="allow-multiple-group-registration"<?php if ( 1 === intval( $group_settings ) ) {
												echo 'checked="checked"';
											} ?>/>
											<div class="uo-checkmark"></div>
											<span class="uo-label">
												<?php _e( 'Allow Users to register in multiple LearnDash Groups', 'uncanny-learndash-codes' ); ?>
											</span>
										</label>

										<?php /* <div class="uo-admin-description">More info</div> */ ?>
									</div>

									<div class="uo-admin-field">
										<input type="submit" name="submit" id="submit" class="uo-admin-form-submit" value="<?php _e( 'Save Changes', 'uncanny-learndash-codes' ); ?>">
									</div>
								</div>
							</div>
						</div>

						<!-- Custom Messages -->
						<div class="uo-admin-section">
							<div class="uo-admin-header">
								<div class="uo-admin-title"><?php _e( 'Custom Messages', 'uncanny-learndash-codes' ); ?></div>
							</div>
							<div class="uo-admin-block">
								<div class="uo-admin-form">

									<div class="uo-admin-field">
										<div class="uo-admin-label"><?php _e( 'Invalid code', 'uncanny-learndash-codes' ); ?></div>

										<input class="uo-admin-input" type="text" value="<?php if ( null !== $custom_messages ) {
											echo esc_html( $custom_messages['invalid-code'] );
										} ?>" name="invalid-code" id="invalid-code" placeholder="<?php _e( 'Sorry, the code you entered is not valid.', 'uncanny-learndash-codes' ); ?>"/>
									</div>
		                            <div class="uo-admin-field">
										<div class="uo-admin-label"><?php _e( 'Expired code', 'uncanny-learndash-codes' ); ?></div>

										<input class="uo-admin-input" type="text" value="<?php if ( null !== $custom_messages && isset( $custom_messages['expired-code'] ) ) {
											echo esc_html( $custom_messages['expired-code'] );
										} ?>" name="expired-code" id="expired-code" placeholder="<?php _e( 'Sorry, the code you entered has expired.', 'uncanny-learndash-codes' ); ?>"/>
									</div>

									<div class="uo-admin-field">
										<div class="uo-admin-label"><?php _e( 'Code already redeemed', 'uncanny-learndash-codes' ); ?></div>

										<input class="uo-admin-input" type="text" value="<?php if ( null !== $custom_messages ) {
											echo esc_html( $custom_messages['already-redeemed'] );
										} ?>" name="already-redeemed" id="already-redeemed" class="widefat" placeholder="<?php _e( 'Sorry, the code you entered is not valid.', 'uncanny-learndash-codes' ); ?>"/>
									</div>

									<div class="uo-admin-field">
										<div class="uo-admin-label"><?php _e( 'Code redeemed maximum times', 'uncanny-learndash-codes' ); ?></div>

										<input class="uo-admin-input" type="text" value="<?php if ( null !== $custom_messages ) {
											echo esc_html( $custom_messages['redeemed-maximum'] );
										} ?>" name="redeemed-maximum" id="redeemed-maximum" class="widefat" placeholder="<?php _e( 'Sorry, the code you entered has already been redeemed maximum times.', 'uncanny-learndash-codes' ); ?>"/>
									</div>

									<div class="uo-admin-field">
										<div class="uo-admin-label"><?php _e( 'Successfully redeemed', 'uncanny-learndash-codes' ); ?></div>

										<input class="uo-admin-input" type="text" value="<?php if ( null !== $custom_messages ) {
											echo esc_html( $custom_messages['successfully-redeemed'] );
										} ?>" name="successfully-redeemed" id="successfully-redeemed" class="widefat" placeholder="<?php _e( 'Congratulations, the code you entered has successfully been redeemed.', 'uncanny-learndash-codes' ); ?>"/>
									</div>

									<div class="uo-admin-field">
										<input type="submit" name="submit" id="submit" class="uo-admin-form-submit" value="<?php _e( 'Save Changes', 'uncanny-learndash-codes' ); ?>">
									</div>

								</div>
							</div>
						</div>
					</form>

					<script>
		              jQuery('#uncanny-learndash-codes-form').submit(function (e) {
		                if (jQuery('#registration_form').val() === '0') {
		                  /*e.preventDefault();
						   jQuery('#registration_form_error h4').html('Please Select Registration Form');
						   jQuery('#registration_form_error').show();
						   return false;*/
		                } else {
		                  if (is_registration_form(jQuery('#registration_form').val())) {
		                    jQuery('#registration_form_error').hide()
		                    return true
		                  } else {
		                    jQuery('#registration_form_error h4').html('<?php echo __('Please Select a Valid Registration Form that has User Registration Feed enabled.', 'uncanny-learndash-codes'); ?>')
		                    jQuery('#registration_form_error').show()
		                    return false
		                  }
		                }
		                //return true;
		              })
		              jQuery('#registration_form').change(function (e) {
		                if (jQuery(this).val() === '0') {
		                  /*e.preventDefault();
						   jQuery('#registration_form_error h4').html('Please Select Registration Form');
						   jQuery('#registration_form_error').show();
						   return false;*/
		                } else {
		                  if (is_registration_form(jQuery(this).val())) {
		                    jQuery('#registration_form_error').hide()
		                    return true
		                  } else {
		                    jQuery('#registration_form_error h4').html('<?php echo __('Please Select a Valid Registration Form that has User Registration Feed enabled.', 'uncanny-learndash-codes'); ?>')
		                    jQuery('#registration_form_error').show()
		                    return false
		                  }
		                }
		                return true
		              })

		              function is_registration_form (val) {
		                if ('0' === jQuery('#form-id-' + val).attr('data-register')) {
		                  return false
		                } else {
		                  return true
		                }
		              }
					</script>

				                                <!-- Theme my Login -->
					<?php if ( class_exists( 'Theme_My_Login' ) ) { ?>
						<?php
						if ( is_multisite() ) {
							$tml_registration_field = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_tml_template_override, 0 );
							$tml_required_field     = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_tml_codes_required_field, 0 );
						} else {
							$tml_registration_field = get_option( Config::$uncanny_codes_tml_template_override, 0 );
							$tml_required_field     = get_option( Config::$uncanny_codes_tml_codes_required_field, 0 );
						}
						?>

						<div class="uo-admin-section">
							<div class="uo-admin-header">
								<div class="uo-admin-title"><?php _e( 'Theme My Login Registration Settings', 'uncanny-learndash-codes' ); ?></div>
							</div>
							<div class="uo-admin-block">
								<form method="post" action="" id="uncanny-learndash-codes-form">
									<input type="hidden" name="_wp_http_referer" value="<?php echo admin_url( 'admin.php?page=uncanny-learndash-codes-settings&saved=true' ) ?>"/>
									<input type="hidden" name="_tml_wpnonce" value="<?php echo wp_create_nonce( Config::get_project_name() ); ?>"/>

									<div class="uo-admin-form">
										<div class="uo-admin-field">
											<label class="uo-checkbox">
												<input type="checkbox" value="1" name="tml-replace-registration-form" id="tml-replace-registration-form"<?php if ( 1 === intval( $tml_registration_field ) ) {
													echo 'checked="checked"';
												} ?>>
												<div class="uo-checkmark"></div>
												<span class="uo-label">
													<?php _e( 'Use Custom Theme My Login registration form that includes Registration Code field', 'uncanny-learndash-codes' ); ?>
												</span>
											</label>
										</div>

										<div class="uo-admin-field">
											<label class="uo-checkbox">
												<input type="checkbox" value="1" name="tml-code-required-field" id="tml-code-required-field"<?php if ( 1 === intval( $tml_required_field ) ) {
													echo 'checked="checked"';
												} ?>>
												<div class="uo-checkmark"></div>
												<span class="uo-label">
													<?php _e( 'Make <strong>Registration Code</strong> field required', 'uncanny-learndash-codes' ); ?>
												</span>
											</label>
										</div>

										<div class="uo-admin-field">
											<input type="submit" name="submit" id="submit" class="uo-admin-form-submit" value="<?php _e( 'Save Theme My Login Changes', 'uncanny-learndash-codes' ); ?>">
										</div>

										<?php

										/*
										<!--<a href="<?php /*echo add_query_arg( array( 'mode' => 'tml' ), remove_query_arg( array(
											'redirect_nonce',
											'mode',
											'saved',
											'force_downloaded',
										) ) ) ?>"
										   onclick="return confirm('Are you sure you want to set up custom Theme My Login Registration Form?');">Click
											here</a> to set up custom Theme My Login Registration form with Code Registration Field.

										<div class="notice-error"><h4>This action will replace your existing Theme My Login registration form.</h4>
										</div>-->
										*/

										?>
									</div>
								</form>
							</div>
						</div>
					<?php } ?>

					<div class="uo-admin-section">
						<div class="uo-admin-header">
							<div class="uo-admin-title"><?php _e( 'Reset or Delete Database', 'uncanny-learndash-codes' ); ?></div>
						</div>
						<div class="uo-admin-block">
							<div class="uo-admin-form">
								<div class="uo-admin-field">
									<div class="uo-admin-label"><?php _e( 'Reset data in database', 'uncanny-learndash-codes' ); ?></div>

									<a href="<?php echo add_query_arg( array( 'mode' => 'reset' ), remove_query_arg( array( 'redirect_nonce' ) ) ) ?>" onclick="return confirm('<?php _e( 'Are you sure you want to delete all data?', 'uncanny-learndash-codes' ); ?>');" class="uo-admin-form-submit uo-admin-form-submit-danger"><?php _e( 'Reset database', 'uncanny-learndash-codes' ); ?></a>

									<div class="uo-admin-description"><?php _e( 'This action will <span class="uo-danger">delete</span> all data including tracking of codes and users. Please download CSV before attempting this.', 'uncanny-learndash-codes' ); ?></div>
								</div>

								<div class="uo-admin-field">
									<div class="uo-admin-label"><?php _e( 'Delete database tables', 'uncanny-learndash-codes' ); ?></div>

									<a href="<?php echo add_query_arg( array( 'mode' => 'destroy' ), remove_query_arg( array( 'redirect_nonce' ) ) ) ?>" onclick="return confirm('Are you sure you want to delete database tables?');" class="uo-admin-form-submit uo-admin-form-submit-danger"><?php _e( 'Delete database', 'uncanny-learndash-codes' ); ?></a>

									<div class="uo-admin-description"><?php _e( 'This action will <span class="uo-danger">delete</span> all data including tracking of codes and users. Please download CSV before attempting this. This will deactivate Uncanny LearnDash Codes.', 'uncanny-learndash-codes' ); ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 *
	 */
	public static function save_form_settings() {
		if ( ( ! empty( $_POST ) && isset( $_POST['_wpnonce'] ) ) && wp_verify_nonce( $_POST['_wpnonce'], Config::get_project_name() ) ) {
			if ( isset( $_POST['registration_form'] ) && is_numeric( intval( $_POST['registration_form'] ) ) ) {
				update_option( Config::$uncanny_codes_settings_gravity_forms, intval( $_POST['registration_form'] ) );
			}

			if ( isset( $_POST['registration-field-mandatory'] ) && is_numeric( intval( $_POST['registration-field-mandatory'] ) ) ) {
				update_option( Config::$uncanny_codes_settings_gravity_forms_mandatory, intval( $_POST['registration-field-mandatory'] ) );
			} else {
				delete_option( Config::$uncanny_codes_settings_gravity_forms_mandatory );
			}

			if ( isset( $_POST['allow-multiple-group-registration'] ) && is_numeric( intval( $_POST['allow-multiple-group-registration'] ) ) ) {
				update_option( Config::$uncanny_codes_settings_multiple_groups, intval( $_POST['allow-multiple-group-registration'] ) );
			} else {
				delete_option( Config::$uncanny_codes_settings_multiple_groups );
			}

			if ( isset( $_POST['code_field_label'] ) && ! empty( $_POST['code_field_label'] ) ) {
				update_option( Config::$uncanny_codes_settings_gravity_forms_label, esc_html( $_POST['code_field_label'] ) );
			} else {
				delete_option( Config::$uncanny_codes_settings_gravity_forms_label );
			}

			if ( isset( $_POST['code_field_error_message'] ) && ! empty( $_POST['code_field_error_message'] ) ) {
				update_option( Config::$uncanny_codes_settings_gravity_forms_error, esc_html( $_POST['code_field_error_message'] ) );
			} else {
				delete_option( Config::$uncanny_codes_settings_gravity_forms_error );
			}

			if ( isset( $_POST['code_field_placeholder'] ) && ! empty( $_POST['code_field_placeholder'] ) ) {
				update_option( Config::$uncanny_codes_settings_gravity_forms_placeholder, esc_html( $_POST['code_field_placeholder'] ) );
			} else {
				delete_option( Config::$uncanny_codes_settings_gravity_forms_placeholder );
			}

			if ( isset( $_POST['invalid-code'] ) && ! empty( $_POST['invalid-code'] ) ) {
				$invalid_code = esc_html( $_POST['invalid-code'] );
			} else {
				$invalid_code = '';
			}
			
			if ( isset( $_POST['expired-code'] ) && ! empty( $_POST['expired-code'] ) ) {
				$expired_code = esc_html( $_POST['expired-code'] );
			} else {
				$expired_code = '';
			}

			if ( isset( $_POST['already-redeemed'] ) && ! empty( $_POST['already-redeemed'] ) ) {
				$already_redeemed = esc_html( $_POST['already-redeemed'] );
			} else {
				$already_redeemed = '';
			}

			if ( isset( $_POST['redeemed-maximum'] ) && ! empty( $_POST['redeemed-maximum'] ) ) {
				$redeemed_maximum = esc_html( $_POST['redeemed-maximum'] );
			} else {
				$redeemed_maximum = '';
			}

			if ( isset( $_POST['successfully-redeemed'] ) && ! empty( $_POST['successfully-redeemed'] ) ) {
				$successfully_redeemed = esc_html( $_POST['successfully-redeemed'] );
			} else {
				$successfully_redeemed = '';
			}


			$settings = array(
				'invalid-code'          => $invalid_code,
				'expired-code'          => $expired_code,
				'already-redeemed'      => $already_redeemed,
				'redeemed-maximum'      => $redeemed_maximum,
				'successfully-redeemed' => $successfully_redeemed,
			);
			update_option( Config::$uncanny_codes_settings_custom_messages, $settings );

			wp_safe_redirect( $_POST['_wp_http_referer'] . '&saved=true&redirect_nonce=' . wp_create_nonce( time() ) );
			exit;
		}

		if ( ( ! empty( $_POST ) && isset( $_POST['_tml_wpnonce'] ) ) && wp_verify_nonce( $_POST['_tml_wpnonce'], Config::get_project_name() ) ) {
			if ( isset( $_POST['tml-replace-registration-form'] ) && is_numeric( intval( $_POST['tml-replace-registration-form'] ) ) ) {
				update_option( Config::$uncanny_codes_tml_template_override, intval( $_POST['tml-replace-registration-form'] ) );
			} else {
				delete_option( Config::$uncanny_codes_tml_template_override );
			}
			if ( isset( $_POST['tml-code-required-field'] ) && is_numeric( intval( $_POST['tml-code-required-field'] ) ) ) {
				update_option( Config::$uncanny_codes_tml_codes_required_field, intval( $_POST['tml-code-required-field'] ) );
			} else {
				delete_option( Config::$uncanny_codes_tml_codes_required_field );
			}

			wp_safe_redirect( $_POST['_wp_http_referer'] . '&saved=true&redirect_nonce=' . wp_create_nonce( time() ) );
			exit;
		}
	}

	/**
	 * @param $message
	 */
	public static function show_message( $message ) {
		?>
		<div class="updated notice">
			<?php esc_html_e( $message, 'uncanny-learndash-codes' ); ?>
		</div>
		<?php
	}
}