<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 *
 */
class AdminGroupEmailFields {

	/**
	 * class constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes_groups', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_email_body_changes' ), 999, 2 );
	}

	/**
	 * Adds the meta box.
	 */
	public function add_metabox() {
		global $current_screen;
		if ( isset( $current_screen->post_type ) && 'groups' !== $current_screen->post_type ) {
			// Not groups.. bail
			return;
		}

		if ( ! ulgm_filter_has_var( 'post' ) && ! ulgm_filter_has_var( 'action' ) ) {
			return;
		}

		add_meta_box(
			'uo-group-management-group-specific-emails',
			__( 'Uncanny Group Management - Group specific email', 'uncanny-learndash-groups' ),
			array( $this, 'render_invite_metabox' ),
			'groups',
			'advanced',
			'high'
		);
	}

	/**
	 * Renders the meta box.
	 */
	public function render_invite_metabox( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'custom_email_nonce', 'custom_nonce_for_email' );
		// Echo out the field
		ob_start();

		/** @var AdminPage $admin_page */
		$admin_page = Load_Groups::$class_instances['AdminPage'];
		$admin_page->localize_filter_globalize_text();
		$new_user_email_override = get_post_meta( $post->ID, 'ulgm_override_user_welcome_email', true );
		if ( empty( $new_user_email_override ) ) {
			$new_user_email_override = 'no';
		}
		$new_user_email_subject = get_post_meta( $post->ID, 'ulgm_override_user_welcome_email_subject', true );
		if ( empty( $new_user_email_subject ) ) {
			$new_user_email_subject = AdminPage::$ulgm_management_admin_page['ulgm_user_welcome_email_subject'];
		}
		$new_user_email_body = get_post_meta( $post->ID, 'ulgm_override_user_welcome_email_body', true );
		if ( empty( $new_user_email_body ) ) {
			$new_user_email_body = AdminPage::$ulgm_management_admin_page['ulgm_user_welcome_email_body'];
		}

		$existing_user_email_override = get_post_meta( $post->ID, 'ulgm_override_existing_user_welcome_email', true );
		if ( empty( $existing_user_email_override ) ) {
			$existing_user_email_override = 'no';
		}

		$existing_user_email_subject = get_post_meta( $post->ID, 'ulgm_override_existing_user_welcome_email_subject', true );
		if ( empty( $existing_user_email_subject ) ) {
			$existing_user_email_subject = AdminPage::$ulgm_management_admin_page['ulgm_existing_user_welcome_email_subject'];
		}
		$existing_user_email_body = get_post_meta( $post->ID, 'ulgm_override_existing_user_welcome_email_body', true );
		if ( empty( $existing_user_email_body ) ) {
			$existing_user_email_body = AdminPage::$ulgm_management_admin_page['ulgm_existing_user_welcome_email_body'];
		}

		?>
		<div class="wrap">
			<div class="ulgm">
				<div class="ulgm-admin-content">
					<div class="uo-ulgm-admin form-table group-management-form">
						<div class="uo-admin-section">
							<div class="uo-admin-block">
								<div class="uo-admin-accordion">
									<!-- New User email-->
									<div class="uo-admin-accordion-item">
										<div class="uo-admin-accordion-item__header">
											<?php echo AdminPage::$ulgm_management_admin_page['text']['user_welcome_email_template']; ?>
										</div>
										<div class="uo-admin-accordion-item__content">
											<div class="uo-admin-form">

												<div class="uo-admin-field">
													<label class="uo-checkbox">
														<input type="checkbox"
															   name="ulgm_override_new_user_welcome_email"
															   id="ulgm_override_new_user_welcome_email"
															   value="yes"
																<?php
																if ( 'yes' === $new_user_email_override ) {
																	echo 'checked="checked"';
																}
																?>
														/>
														<div class="uo-checkmark"></div>
														<span class="uo-label">
													<?php echo __( 'Override "Add and invite (new user)" Email.', 'uncanny-learndash-groups' ); ?>
												</span>
													</label>
												</div>

												<!-- Subject -->
												<div class="uo-admin-field">
													<div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['subject']; ?></div>

													<input class="uo-admin-input" type="text"
														   name="ulgm_override_user_welcome_email_subject"
														   id="ulgm_override_user_welcome_email_subject"
														   value="<?php echo $new_user_email_subject; ?>"/>
												</div>

												<div class="uo-admin-field">
													<div class="uo-admin-label">
														<?php echo AdminPage::$ulgm_management_admin_page['text']['body']; ?>
													</div>

													<div class="uo-admin-description">
														<?php _e( 'Insert these tokens into your email and they will be replaced with the corresponding information.  Click a token to copy it to your clipboard.', 'uncanny-learndash-groups' ); ?>
													</div>

													<div class="uo-admin-tags uo-admin-tags-has-top-description">
														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#SiteUrl</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#SiteUrl"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#SiteName</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#SiteName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#GroupName
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#GroupName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#LoginUrl</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#LoginUrl"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#Email</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#Email"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#EmailEncoded
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#EmailEncoded" readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#Username</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#Username"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#FirstName
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#FirstName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#LastName</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#LastName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#DisplayName
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#DisplayName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#Password</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#Password"
																   readonly>
														</div>
														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">
																#GroupLeaderInfo
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#GroupLeaderInfo" readonly>
														</div>
														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#Courses</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#Courses"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#ResetPassword
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#ResetPassword"
																   readonly>
														</div>

													</div>

													<div class="uo-admin-wp-editor">
														<?php wp_editor( $new_user_email_body, 'ulgm_override_user_welcome_email_body' ); ?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- Existing User email-->
									<div class="uo-admin-accordion-item">
										<div class="uo-admin-accordion-item__header">
											<?php echo AdminPage::$ulgm_management_admin_page['text']['existing_user_welcome_email_template']; ?>
										</div>
										<div class="uo-admin-accordion-item__content">
											<div class="uo-admin-form">

												<!-- Send Email Checkbox -->
												<div class="uo-admin-field">
													<label class="uo-checkbox">
														<input type="checkbox"
															   name="ulgm_override_existing_user_welcome_email"
															   id="ulgm_override_existing_user_welcome_email"
															   value="yes"
																<?php
																if ( 'yes' === $existing_user_email_override ) {
																	echo 'checked="checked"';
																}
																?>
														/>
														<div class="uo-checkmark"></div>
														<span class="uo-label">
													<?php echo __( 'Override "Add and invite (existing user)" Email.', 'uncanny-learndash-groups' ); ?>
												</span>
													</label>
												</div>

												<!-- Subject -->
												<div class="uo-admin-field">
													<div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['subject']; ?></div>

													<input class="uo-admin-input" type="text"
														   name="ulgm_override_existing_user_welcome_email_subject"
														   id="ulgm_override_existing_user_welcome_email_subject"
														   value="<?php echo $existing_user_email_subject; ?>"/>
												</div>

												<div class="uo-admin-field">
													<div class="uo-admin-label">
														<?php echo AdminPage::$ulgm_management_admin_page['text']['body']; ?>
													</div>

													<div class="uo-admin-description">
														<?php _e( 'Insert these tokens into your email and they will be replaced with the corresponding information.  Click a token to copy it to your clipboard.', 'uncanny-learndash-groups' ); ?>
													</div>

													<div class="uo-admin-tags uo-admin-tags-has-top-description">
														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#SiteUrl</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#SiteUrl"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#SiteName</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#SiteName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#GroupName
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#GroupName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#LoginUrl</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#LoginUrl"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#Email</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#Email"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#EmailEncoded
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#EmailEncoded" readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#Username</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#Username"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#FirstName
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#FirstName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#LastName</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#LastName"
																   readonly>
														</div>

														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#DisplayName
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#DisplayName"
																   readonly>
														</div>
														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">
																#GroupLeaderInfo
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#GroupLeaderInfo" readonly>
														</div>
														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">#Courses</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#Courses"
																   readonly>
														</div>
														<div class="uo-admin-copy-to-clipboard">
															<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ); ?></span>
															<div class="uo-admin-copy-to-clipboard-fake">
																#ResetPassword
															</div>
															<input class="uo-admin-copy-to-clipboard-input"
																   value="#ResetPassword"
																   readonly>
														</div>
													</div>

													<div class="uo-admin-wp-editor">
														<?php wp_editor( $existing_user_email_body, 'ulgm_override_existing_user_welcome_email_body' ); ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		echo ob_get_clean();
	}

	/**
	 * @param $post_id
	 * @param $post
	 *
	 * @return void
	 */
	public function save_email_body_changes( $post_id, $post ) {

		if ( 'groups' !== $post->post_type ) {
			return;
		}

		// Add nonce for security and authentication.
		$nonce_name   = ulgm_filter_has_var( 'custom_nonce_for_email', INPUT_POST ) ? ulgm_filter_input( 'custom_nonce_for_email', INPUT_POST ) : '';
		$nonce_action = 'custom_email_nonce';

		// Check if nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}
		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$new_user_email_override = isset( $_POST['ulgm_override_new_user_welcome_email'] ) ? sanitize_text_field( wp_unslash( $_POST['ulgm_override_new_user_welcome_email'] ) ) : 'no';
		$new_user_email_subject  = isset( $_POST['ulgm_override_user_welcome_email_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['ulgm_override_user_welcome_email_subject'] ) ) : '';
		$new_user_email_body     = isset( $_POST['ulgm_override_user_welcome_email_body'] ) ? wp_unslash( $_POST['ulgm_override_user_welcome_email_body'] ) : ''; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		update_post_meta( $post_id, 'ulgm_override_user_welcome_email', $new_user_email_override );
		update_post_meta( $post_id, 'ulgm_override_user_welcome_email_subject', $new_user_email_subject );
		update_post_meta( $post_id, 'ulgm_override_user_welcome_email_body', $new_user_email_body );

		$existing_user_email_override = isset( $_POST['ulgm_override_existing_user_welcome_email'] ) ? sanitize_text_field( wp_unslash( $_POST['ulgm_override_existing_user_welcome_email'] ) ) : 'no';
		$existing_user_email_subject  = isset( $_POST['ulgm_override_existing_user_welcome_email_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['ulgm_override_existing_user_welcome_email_subject'] ) ) : '';
		$existing_user_email_body     = isset( $_POST['ulgm_override_existing_user_welcome_email_body'] ) ? wp_unslash( $_POST['ulgm_override_existing_user_welcome_email_body'] ) : ''; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		update_post_meta( $post_id, 'ulgm_override_existing_user_welcome_email', $existing_user_email_override );
		update_post_meta( $post_id, 'ulgm_override_existing_user_welcome_email_subject', $existing_user_email_subject );
		update_post_meta( $post_id, 'ulgm_override_existing_user_welcome_email_body', $existing_user_email_body );
	}
}
