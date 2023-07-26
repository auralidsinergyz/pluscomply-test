<h3><?php esc_html_e( 'You are not a member of this Group', 'uncanny-pro-toolkit' ); ?></h3>
<form action="" method="post" class="uncanny_pro_toolkit_join_group">
	<input type="hidden" name="uncanny_pro_toolkit_join_group_id"
	       value="<?php echo get_the_ID(); ?>">
	<input type="submit" value="<?php esc_html_e( 'Join this Group', 'uncanny-pro-toolkit' ); ?>">
	<?php
	wp_nonce_field( 'uncanny_pro_toolkit_join_group', 'uncanny_pro_toolkit_join_group_nonce' )
	?>
</form>