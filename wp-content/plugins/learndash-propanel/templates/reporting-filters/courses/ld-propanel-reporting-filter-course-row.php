<?php
/**
 * Rows of Users for a selected Course
 */
?>
<?php
if ( current_user_can( 'edit_user', $activity->user_id ) ) {
	$user_link = get_edit_user_link( $activity->user_id ) ."#ld_course_info";
} else {
	$user_link = "#";
}

if ( current_user_can( 'edit_courses', $activity->post_id ) ) {
	$post_link = get_edit_post_link( $activity->post_id ) ."#ld_course_info";
} else {
	$post_link = "#";
}

switch ( $header_key ) {
	case 'checkbox':
		?><input class="ld-propanel-report-checkbox" type="checkbox" data-user-id="<?php echo $activity->user_id; ?>"><?php
		break;

	case 'user':
		?><strong title="User ID: <?php echo $activity->user_id; ?>" class="display-name"><?php echo esc_html( $activity->user_display_name ); ?></strong>
		<p class="user-login"><a href="<?php echo $user_link; ?>" title="<?php echo esc_attr( $activity->user_display_name ); ?>"><?php echo esc_html( $activity->user_display_name ); ?></a></p>
		<p class="user-email"><a href="mailto:<?php echo esc_attr( $activity->user_email ); ?>" title="<?php printf(
			// translators: placeholder: user email.
			esc_attr_x( 'Compose a new mail to %s', 'placeholder: user email', 'ld_propanel' ),
			$activity->user_email
		); ?>"><?php echo esc_html( $activity->user_email ); ?></a></p><?php
		break;

	case 'user_id':
		echo $activity->user_id;
		break;

	case 'progress':
		?>
		<div class="progress-bar" title="<?php echo sprintf( 
			// translators: Number of completed course steps.
			esc_html_x( '%1$d of %2$d steps completed', 'Number of completed course steps', 'ld_propanel' ), absint( LearnDash_ProPanel_Activity::get_activity_steps_completed( $activity ) ), absint( LearnDash_ProPanel_Activity::get_activity_steps_total( $activity ) ) ); ?>">
			<?php
				$progress_label_style = '';
				if ( is_null( $activity->activity_status ) ) {
					$progress_percent = 0;
					$progress_label = __('Not Started', 'ld_propanel' );
					$progress_label_style = 'font-size: 16px;';
				} else if ( $activity->activity_status == false ) {
					$steps_completed = absint( LearnDash_ProPanel_Activity::get_activity_steps_completed( $activity ) );
					$steps_total = absint( LearnDash_ProPanel_Activity::get_activity_steps_total( $activity ) );
					if ( ( ! empty( $steps_total ) ) && ( ! empty( $steps_completed) ) ) {
						$progress_percent = round( 100 * ( $steps_completed / $steps_total ) );
					} else {
						$progress_percent = 0;
					}
					$progress_label = $progress_percent .'%';
					$progress_label_style = '';
				} else if ( $activity->activity_status == true ) {
					$progress_percent = 100;
					$progress_label = $progress_percent .'%';
				}
			?>
			<span class="actual-progress" style="width: <?php echo $progress_percent; ?>%;"></span>
		</div>
		<strong class="progress-amount" style="<?php echo $progress_label_style ?>"><?php echo $progress_label; ?></strong>
		<?php
		break;

	case 'last_update':
		echo learndash_adjust_date_time_display( intval($activity->activity_completed) );
		break;

	default:
		break;
}

