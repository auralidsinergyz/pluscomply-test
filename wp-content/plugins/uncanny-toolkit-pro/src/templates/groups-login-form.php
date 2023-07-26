<aside id="uo-widgets" class="sidebar-login-form clr">
	<div class="clr widget">
		<div class="widget-title"><?php esc_html_e( 'Log In', 'uncanny-pro-toolkit' ) ?></div>
		<?php
		if ( defined( 'PWP_NAME' ) ){
		?>
		<form name="uncanny-pro-toolkit-login-form" id="uncanny-pro-toolkit-login-form"
		      action="<?php echo wp_login_url(); ?>?wpe-login=<?php echo PWP_NAME; ?>" method="post">
			<?php } else { ?>
			<form name="uncanny-pro-toolkit-login-form" id="uncanny-pro-toolkit-login-form"
			      action="<?php echo wp_login_url(); ?>" method="post">
				<?php } ?>
				<p class="login-username">
					<label for="user_login"><?php esc_html_e( 'Username', 'uncanny-pro-toolkit' ) ?></label><br/>
					<input type="text" name="log" id="user_login" placeholder="<?php esc_html_e( 'Username', 'uncanny-pro-toolkit' ) ?>" class="input" value=""
					       size="20"
					       autocomplete="off">
				</p>
				<p class="login-password">
					<label for="user_pass"><?php esc_html_e( 'Password', 'uncanny-pro-toolkit' ) ?></label><br/>
					<input type="password" name="pwd" id="user_pass" placeholder="<?php esc_html_e( 'Password', 'uncanny-pro-toolkit' ) ?>" class="input" value=""
					       size="20"
					       autocomplete="off">
				</p>
				
				<p class="login-remember">
					<label>
						<input name="rememberme" type="checkbox" id="rememberme"
						       value="forever"><?php esc_html_e( 'Remember Me', 'uncanny-pro-toolkit' ) ?>
					</label>
				</p>
				<p class="login-submit">
					<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="<?php esc_html_e( 'Log In', 'uncanny-pro-toolkit' ) ?>">
				</p>
				<input type="hidden" name="group_id" value="<?php echo get_the_ID() ?>">
			</form>
	</div>
</aside>