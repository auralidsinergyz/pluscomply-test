<?php
?>
<div class="tml tml-register" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php //$template->the_action_template_message( 'register' ); ?>
	<?php if ( $template->get_errors() ) : ?>
        <div class="validation_error"><?php _e( 'There was a problem with your submission. Errors have been highlighted below.', 'uncanny-learndash-groups' ) ?></div>
	<?php endif; ?>
	<?php
	global $error;

	$theme_my_login = Theme_My_Login::get_object();
	$wp_error       = $theme_my_login->errors;
	if ( empty( $wp_error ) ) {
		$wp_error = new WP_Error();
	}

	$output = '';
	if ( $this->is_active() ) {
		if ( $wp_error->get_error_code() ) {
			$errors   = '';
			$messages = '';
			foreach ( $wp_error->get_error_codes() as $code ) {
				$severity = $wp_error->get_error_data( $code );
				foreach ( $wp_error->get_error_messages( $code ) as $error ) {
					if ( 'message' === $severity ) {
						$messages .= '    ' . $error . "<br />\n";
					} else {
						$errors .= '    ' . $error . "<br />\n";
					}
				}
			}
			if ( ! empty( $errors ) ) {
				$output .= '<p class="error">' . apply_filters( 'login_errors', $errors ) . "</p>\n";
			}
			if ( ! empty( $messages ) ) {
				$output .= '<p class="message">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
			}
		}
	}
	echo $output;
	$add_field      = get_option( \uncanny_learndash_groups\ThemeMyLoginSupport::$uncanny_groups_tml_codes_add_field );
	$required_field = get_option( \uncanny_learndash_groups\ThemeMyLoginSupport::$uncanny_groups_tml_codes_required_field );
	?>
    <form name="registerform" id="registerform<?php $template->the_instance(); ?>"
          action="<?php $template->the_action_url( 'register' ); ?>" method="post" class="dark-form tml-register-form">
		<?php if ( 1 === intval( $add_field ) ) { ?>
            <p class="tml-user-first_name">
                <label
                        for="first_name<?php $template->the_instance(); ?>"><?php echo esc_html__( 'First name', 'uncanny-learndash-groups' ); ?>
                    *</label>
                <input type="text" name="first_name" id="first_name<?php $template->the_instance(); ?>"
                       class="input"
                       placeholder="<?php echo esc_html__( 'First name', 'uncanny-learndash-groups' ); ?>"
                       required="required"
                       value="<?php $template->the_posted_value( 'first_name' ); ?>" size="20"/>
            </p>
            <p class="tml-user-last_name">
                <label
                        for="last_name<?php $template->the_instance(); ?>"><?php echo esc_html__( 'Last name', 'uncanny-learndash-groups' ); ?>
                    *</label>
                <input type="text" name="last_name" id="last_name<?php $template->the_instance(); ?>"
                       class="input"
                       placeholder="<?php echo esc_html__( 'Last name', 'uncanny-learndash-groups' ); ?>"
                       required="required"
                       value="<?php $template->the_posted_value( 'last_name' ); ?>" size="20"/>
            </p>
		<?php } ?>
		<?php if ( 'email' != $theme_my_login->get_option( 'login_type' ) ) : ?>
            <p class="tml-user-login-wrap">
                <label
                        for="user_login<?php $template->the_instance(); ?>"><?php echo esc_html__( 'Username', 'uncanny-learndash-groups' ); ?>
                    *</label>
                <input type="text"
                       placeholder="<?php echo esc_html__( 'Username', 'uncanny-learndash-groups' ); ?>"
                       name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input"
                       value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20"/>
            </p>
		<?php endif; ?>

        <p class="tml-user-email-wrap">
            <label
                    for="user_email<?php $template->the_instance(); ?>"><?php echo esc_html__( 'E-mail', 'uncanny-learndash-groups' ); ?>
                *</label>
            <input type="text" name="user_email" id="user_email<?php $template->the_instance(); ?>"
                   placeholder="<?php echo esc_html__( 'E-mail', 'uncanny-learndash-groups' ); ?>"
                   class="input"
                   value="<?php $template->the_posted_value( 'user_email' ); ?>" size="20"/>
        </p>
		<?php if ( 1 === intval( $add_field ) ) { ?>
            <p class="tml-code-registration-wrap">
                <label
                        for="code_registration<?php $template->the_instance(); ?>"><?php echo esc_html__( 'Registration Code', 'uncanny-learndash-groups' ); ?><?php if ( 1 === intval( get_option( \uncanny_learndash_groups\ThemeMyLoginSupport::$uncanny_groups_tml_codes_required_field ) ) ) { ?>*<?php } ?></label>
                <input type="text" name="code_registration" id="code_registration<?php $template->the_instance(); ?>"
                       class="input"
				       <?php if ( 1 === intval( $required_field ) ) { ?>required="required"<?php } ?>
                       placeholder="<?php echo esc_html__( 'Registration Code', 'uncanny-learndash-groups' ); ?>"
                       value="<?php $template->the_posted_value( 'code_registration' ); ?>" size="20"/>
            </p>
		<?php } ?>
        <p class="tml-registration-confirmation"
           id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'Registration confirmation will be e-mailed to you.', 'uncanny-learndash-groups' ) ); ?></p>

		<?php do_action( 'register_form' ); ?>
        <p class="tml-submit-wrap">
            <input type="submit" name="wp-submit" class="btn btn-default"
                   id="wp-submit<?php $template->the_instance(); ?>"
                   value="<?php esc_attr_e( 'Register', 'uncanny-learndash-groups' ); ?>"/>
            <input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>"/>
            <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>"/>
            <input type="hidden" name="action" value="register"/>
        </p>
    </form>
	<?php $template->the_action_links( array( 'register' => false ) ); ?>
</div>
