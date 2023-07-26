<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">
    <div class="ulgm">

		<?php

		// Add admin header and tabs
		$tab_active = 'uncanny-groups-email-settings';
		include Utilities::get_template( 'admin/admin-header.php' );

		?>

        <div class="ulgm-admin-content">
            <div class="uo-ulgm-admin form-table group-management-form">
                <input type="hidden" id="action" name="action" value="save-email-templates"/>

                <!-- Messages -->
				<?php if ( '' !== AdminPage::$ulgm_management_admin_page['text']['message'] ) {
					?>
                    <div class="updated ulgm-custom-message" style="margin-bottom: 20px">
                        <p><?php echo AdminPage::$ulgm_management_admin_page['text']['message']; ?></p>
                    </div>
					<?php
				}
				?>

                <!-- Email Settings -->
                <div class="uo-admin-section">
                    <div class="uo-admin-header">
                        <div class="uo-admin-title"><?php echo AdminPage::$ulgm_management_admin_page['text']['email_settings']; ?></div>
                    </div>
                    <div class="uo-admin-block">
                        <div class="uo-admin-form">

                            <!-- From Email -->
                            <div class="uo-admin-field">
                                <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['email_from']; ?></div>

                                <input class="uo-admin-input" type="text" name="ulgm_email_from" id="ulgm_email_from"
                                       value="<?php echo AdminPage::$ulgm_management_admin_page['email_from']; ?>"/>
                            </div>

                            <!-- From Name -->
                            <div class="uo-admin-field">
                                <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['name_from']; ?></div>

                                <input class="uo-admin-input" type="text" name="ulgm_name_from" id="ulgm_name_from"
                                       value="<?php echo AdminPage::$ulgm_management_admin_page['name_from']; ?>"/>
                            </div>

                            <!-- Reply to -->
                            <div class="uo-admin-field">
                                <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['reply_to']; ?></div>

                                <input class="uo-admin-input" type="text" name="ulgm_reply_to" id="ulgm_reply_to"
                                       value="<?php echo AdminPage::$ulgm_management_admin_page['reply_to']; ?>"/>
                            </div>


                            <!-- Submit -->
                            <div class="uo-admin-field uo-admin-extra-space">
                                <button id="btn-save_template" class="uo-admin-form-submit submit-group-management-form"
                                        data-end-point="save_email_templates"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email templates -->
                <div class="uo-admin-section">
                    <div class="uo-admin-header">
                        <div class="uo-admin-title"><?php _e( 'Email templates', 'uncanny-learndash-groups' ); ?></div>
                    </div>
                    <div class="uo-admin-block">

                        <div class="uo-admin-accordion">

                            <div class="uo-admin-accordion-item">
                                <div class="uo-admin-accordion-item__header">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['redemption_email_template']; ?>
                                </div>
                                <div class="uo-admin-accordion-item__content">
                                    <div class="uo-admin-form">

                                        <!-- Send Email Checkbox -->
                                        <div class="uo-admin-field">
                                            <label class="uo-checkbox">
                                                <input type="checkbox" name="ulgm_send_code_redemption_email"
                                                       id="ulgm_send_code_redemption_email"
                                                       value="yes"<?php if ( 'yes' === get_option( 'ulgm_send_code_redemption_email', 'yes' ) ) {
													echo 'checked="checked"';
												} ?> />
                                                <div class="uo-checkmark"></div>
                                                <span class="uo-label">
													<?php echo __( 'Send "Send enrollment key" Email.', 'uncanny-learndash-groups' ); ?>
												</span>
                                            </label>
                                        </div>

                                        <!-- Subject -->
                                        <div class="uo-admin-field">
                                            <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['subject']; ?></div>

                                            <input class="uo-admin-input" type="text"
                                                   name="ulgm_invitation_user_email_subject"
                                                   id="ulgm_invitation_user_email_subject"
                                                   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_invitation_user_email_subject']; ?>"/>
                                        </div>

                                        <div class="uo-admin-field">
                                            <div class="uo-admin-label">
												<?php echo AdminPage::$ulgm_management_admin_page['text']['body']; ?>
                                            </div>

                                            <div class="uo-admin-description">
												<?php _e( 'Insert these tokens into your email and they will be replaced with the corresponding information. Click a token to copy it to your clipboard.', 'uncanny-learndash-groups' ); ?>
                                            </div>

                                            <div class="uo-admin-tags uo-admin-tags-has-top-description">
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#FirstName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#FirstName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LastName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LastName"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#GroupName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LoginUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LoginUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Email</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Email"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#EmailEncoded</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#EmailEncoded" readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#RedemptionKey</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#RedemptionKey" readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupLeaderInfo</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#GroupLeaderInfo" readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Courses</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Courses"
                                                           readonly>
                                                </div>

                                            </div>

                                            <div class="uo-admin-wp-editor">
												<?php wp_editor( AdminPage::$ulgm_management_admin_page['ulgm_invitation_user_email_body'], 'ulgm_invitation_user_email_body' ); ?>
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="uo-admin-field uo-admin-extra-space">
                                            <button id="btn-save_template"
                                                    class="uo-admin-form-submit submit-group-management-form"
                                                    data-end-point="save_email_templates"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="uo-admin-accordion-item">
                                <div class="uo-admin-accordion-item__header">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['user_welcome_email_template']; ?>
                                </div>
                                <div class="uo-admin-accordion-item__content">
                                    <div class="uo-admin-form">

                                        <!-- Send Email Checkbox -->
                                        <div class="uo-admin-field">
                                            <label class="uo-checkbox">
                                                <input type="checkbox" name="ulgm_send_user_welcome_email"
                                                       id="ulgm_send_user_welcome_email"
                                                       value="yes"<?php if ( 'yes' === get_option( 'ulgm_send_user_welcome_email', 'yes' ) ) {
													echo 'checked="checked"';
												} ?> />
                                                <div class="uo-checkmark"></div>
                                                <span class="uo-label">
													<?php echo __( 'Send "Add and invite (new user)" Email.', 'uncanny-learndash-groups' ); ?>
												</span>
                                            </label>
                                        </div>

                                        <!-- Subject -->
                                        <div class="uo-admin-field">
                                            <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['subject']; ?></div>

                                            <input class="uo-admin-input" type="text"
                                                   name="ulgm_user_welcome_email_subject"
                                                   id="ulgm_user_welcome_email_subject"
                                                   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_user_welcome_email_subject']; ?>"/>
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
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#GroupName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LoginUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LoginUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Email</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Email"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#EmailEncoded</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#EmailEncoded" readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Username</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Username"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#FirstName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#FirstName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LastName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LastName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#DisplayName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#DisplayName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Password</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Password"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupLeaderInfo</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#GroupLeaderInfo" readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Courses</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Courses"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#ResetPassword</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#ResetPassword"
                                                           readonly>
                                                </div>

                                            </div>

                                            <div class="uo-admin-wp-editor">
												<?php wp_editor( AdminPage::$ulgm_management_admin_page['ulgm_user_welcome_email_body'], 'ulgm_user_welcome_email_body' ); ?>
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="uo-admin-field uo-admin-extra-space">
                                            <button id="btn-save_template"
                                                    class="uo-admin-form-submit submit-group-management-form"
                                                    data-end-point="save_email_templates"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="uo-admin-accordion-item">
                                <div class="uo-admin-accordion-item__header">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['existing_user_welcome_email_template']; ?>
                                </div>
                                <div class="uo-admin-accordion-item__content">
                                    <div class="uo-admin-form">

                                        <!-- Send Email Checkbox -->
                                        <div class="uo-admin-field">
                                            <label class="uo-checkbox">
                                                <input type="checkbox" name="ulgm_send_existing_user_welcome_email"
                                                       id="ulgm_send_existing_user_welcome_email"
                                                       value="yes"<?php if ( 'yes' === get_option( 'ulgm_send_existing_user_welcome_email', 'yes' ) ) {
													echo 'checked="checked"';
												} ?> />
                                                <div class="uo-checkmark"></div>
                                                <span class="uo-label">
													<?php echo __( 'Send "Add and invite (existing user)" Email.', 'uncanny-learndash-groups' ); ?>
												</span>
                                            </label>
                                        </div>

                                        <!-- Subject -->
                                        <div class="uo-admin-field">
                                            <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['subject']; ?></div>

                                            <input class="uo-admin-input" type="text"
                                                   name="ulgm_existing_user_welcome_email_subject"
                                                   id="ulgm_existing_user_welcome_email_subject"
                                                   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_existing_user_welcome_email_subject']; ?>"/>
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
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#GroupName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LoginUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LoginUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Email</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Email"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#EmailEncoded</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#EmailEncoded" readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Username</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Username"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#FirstName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#FirstName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LastName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LastName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#DisplayName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#DisplayName"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupLeaderInfo</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#GroupLeaderInfo" readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Courses</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Courses"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#ResetPassword</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#ResetPassword"
                                                           readonly>
                                                </div>
                                            </div>

                                            <div class="uo-admin-wp-editor">
												<?php wp_editor( AdminPage::$ulgm_management_admin_page['ulgm_existing_user_welcome_email_body'], 'ulgm_existing_user_welcome_email_body' ); ?>
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="uo-admin-field uo-admin-extra-space">
                                            <button id="btn-save_template"
                                                    class="uo-admin-form-submit submit-group-management-form"
                                                    data-end-point="save_email_templates"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="uo-admin-accordion-item">
                                <div class="uo-admin-accordion-item__header">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['group_leader_welcome_email_template']; ?>
                                </div>
                                <div class="uo-admin-accordion-item__content">
                                    <div class="uo-admin-form">

                                        <!-- Send Email Checkbox -->
                                        <div class="uo-admin-field">
                                            <label class="uo-checkbox">
                                                <input type="checkbox" name="ulgm_send_group_leader_welcome_email"
                                                       id="ulgm_send_group_leader_welcome_email"
                                                       value="yes"<?php if ( 'yes' === get_option( 'ulgm_send_group_leader_welcome_email', 'yes' ) ) {
													echo 'checked="checked"';
												} ?> />
                                                <div class="uo-checkmark"></div>
                                                <span class="uo-label">
													<?php echo __( 'Send "Add group leader/Create group" Email.', 'uncanny-learndash-groups' ); ?>
												</span>
                                            </label>
                                        </div>

                                        <!-- Subject -->
                                        <div class="uo-admin-field">
                                            <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['subject']; ?></div>

                                            <input class="uo-admin-input" type="text"
                                                   name="ulgm_group_leader_welcome_email_subject"
                                                   id="ulgm_group_leader_welcome_email_subject"
                                                   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_group_leader_welcome_email_subject']; ?>"/>
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
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#GroupName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LoginUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LoginUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Email</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Email"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#EmailEncoded</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#EmailEncoded" readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Username</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Username"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#FirstName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#FirstName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LastName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LastName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#DisplayName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#DisplayName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Password</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Password"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupLeaderInfo</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#GroupLeaderInfo" readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Courses</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Courses"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#ResetPassword</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#ResetPassword"
                                                           readonly>
                                                </div>
                                            </div>

                                            <div class="uo-admin-wp-editor">
												<?php wp_editor( AdminPage::$ulgm_management_admin_page['ulgm_group_leader_welcome_email_body'], 'ulgm_group_leader_welcome_email_body' ); ?>
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="uo-admin-field uo-admin-extra-space">
                                            <button id="btn-save_template"
                                                    class="uo-admin-form-submit submit-group-management-form"
                                                    data-end-point="save_email_templates"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="uo-admin-accordion-item">
                                <div class="uo-admin-accordion-item__header">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['existing_group_leader_welcome_email_template']; ?>
                                </div>
                                <div class="uo-admin-accordion-item__content">
                                    <div class="uo-admin-form">

                                        <!-- Send Email Checkbox -->
                                        <div class="uo-admin-field">
                                            <label class="uo-checkbox">
                                                <input type="checkbox"
                                                       name="ulgm_send_existing_group_leader_welcome_email"
                                                       id="ulgm_send_existing_group_leader_welcome_email"
                                                       value="yes"<?php if ( 'yes' === get_option( 'ulgm_send_existing_group_leader_welcome_email', 'yes' ) ) {
													echo 'checked="checked"';
												} ?> />
                                                <div class="uo-checkmark"></div>
                                                <span class="uo-label">
													<?php echo __( 'Send "Add group leader/Create group" Email.', 'uncanny-learndash-groups' ); ?>
												</span>
                                            </label>
                                        </div>

                                        <!-- Subject -->
                                        <div class="uo-admin-field">
                                            <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['subject']; ?></div>

                                            <input class="uo-admin-input" type="text"
                                                   name="ulgm_existing_group_leader_welcome_email_subject"
                                                   id="ulgm_existing_group_leader_welcome_email_subject"
                                                   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_existing_group_leader_welcome_email_subject']; ?>"/>
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
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#GroupName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LoginUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LoginUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Email</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Email"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#EmailEncoded</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#EmailEncoded" readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Username</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Username"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#FirstName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#FirstName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LastName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LastName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#DisplayName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#DisplayName"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupLeaderInfo</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#GroupLeaderInfo" readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Courses</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Courses"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#ResetPassword</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#ResetPassword"
                                                           readonly>
                                                </div>
                                            </div>

                                            <div class="uo-admin-wp-editor">
												<?php wp_editor( AdminPage::$ulgm_management_admin_page['ulgm_existing_group_leader_welcome_email_body'], 'ulgm_existing_group_leader_welcome_email_body' ); ?>
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="uo-admin-field uo-admin-extra-space">
                                            <button id="btn-save_template"
                                                    class="uo-admin-form-submit submit-group-management-form"
                                                    data-end-point="save_email_templates"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="uo-admin-accordion-item">
                                <div class="uo-admin-accordion-item__header">
									<?php echo AdminPage::$ulgm_management_admin_page['text']['new_group_purchase_email_template']; ?>
                                </div>
                                <div class="uo-admin-accordion-item__content">
                                    <div class="uo-admin-form">

                                        <!-- Send Email Checkbox -->
                                        <div class="uo-admin-field">
                                            <label class="uo-checkbox">
                                                <input type="checkbox" name="ulgm_send_new_group_purchase_email"
                                                       id="ulgm_send_new_group_purchase_email"
                                                       value="yes"<?php if ( 'yes' === get_option( 'ulgm_send_new_group_purchase_email', 'yes' ) ) {
													echo 'checked="checked"';
												} ?> />
                                                <div class="uo-checkmark"></div>
                                                <span class="uo-label">
													<?php echo __( 'Send "New group purchase" Email.', 'uncanny-learndash-groups' ); ?>
												</span>
                                            </label>
                                        </div>

                                        <!-- Subject -->
                                        <div class="uo-admin-field">
                                            <div class="uo-admin-label"><?php echo AdminPage::$ulgm_management_admin_page['text']['subject']; ?></div>

                                            <input class="uo-admin-input" type="text"
                                                   name="ulgm_new_group_purchase_email_subject"
                                                   id="ulgm_new_group_purchase_email_subject"
                                                   value="<?php echo AdminPage::$ulgm_management_admin_page['ulgm_new_group_purchase_email_subject']; ?>"/>
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
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#SiteName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#SiteName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#GroupName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LoginUrl</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LoginUrl"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Email</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Email"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#EmailEncoded</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#EmailEncoded" readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Username</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Username"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#FirstName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#FirstName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#LastName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#LastName"
                                                           readonly>
                                                </div>

                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#DisplayName</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#DisplayName"
                                                           readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#GroupLeaderInfo</div>
                                                    <input class="uo-admin-copy-to-clipboard-input"
                                                           value="#GroupLeaderInfo" readonly>
                                                </div>
                                                <div class="uo-admin-copy-to-clipboard">
                                                    <span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
                                                    <div class="uo-admin-copy-to-clipboard-fake">#Courses</div>
                                                    <input class="uo-admin-copy-to-clipboard-input" value="#Courses"
                                                           readonly>
                                                </div>
												<div class="uo-admin-copy-to-clipboard">
													<span class="uo-admin-ctc-tooltip"><?php _e( 'Copy to clipboard', 'uncanny-learndash-groups' ) ?></span>
													<div class="uo-admin-copy-to-clipboard-fake">#ResetPassword</div>
													<input class="uo-admin-copy-to-clipboard-input" value="#ResetPassword"
														   readonly>
												</div>
                                            </div>

                                            <div class="uo-admin-wp-editor">
												<?php wp_editor( AdminPage::$ulgm_management_admin_page['ulgm_new_group_purchase_email_body'], 'ulgm_new_group_purchase_email_body' ); ?>
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="uo-admin-field uo-admin-extra-space">
                                            <button id="btn-save_template"
                                                    class="uo-admin-form-submit submit-group-management-form"
                                                    data-end-point="save_email_templates"><?php echo AdminPage::$ulgm_management_admin_page['text']['save_changes']; ?></button>
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
