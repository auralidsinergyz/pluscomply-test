<div class="<?php echo implode( ' ', $grid_classes ) ?>">
	<div class="uo-border<?php if ( 'completed' === $completed ) {
		echo ' completed';
	} ?>">
		<a href="<?php echo $permalink; ?>">
			<?php if ( 'yes' === $atts['show_image'] ) { ?>
				<div class="featured-image">
					<?php if ( has_post_thumbnail( $lesson_topic_id->ID ) ) { ?>
						<img src="<?php echo self::resize_grid_image( $lesson_topic_id->ID, 'uo_lesson_image_size' ); ?>"
						     class="uo-grid-featured-image"/>
					<?php } else { ?>
						<img
							src="<?php echo $default_no_image_path; ?>"
							class="uo-grid-featured-image"/>
					<?php } ?>
				</div>
				<?php
			}
			?>
			<div class="course-info-holder<?php if ( 'completed' === $completed ) {
				echo ' completed';
			} ?>">
				<span class="course-title"><?php echo $lesson_topic_id->post_title; ?></span>
				<?php
				//$settings = get_post_meta( $lesson_topic_id->ID, '_sfwd-lessons', true );
				$lesson_available_from  = ld_lesson_access_from( $lesson_topic_id, wp_get_current_user()->ID );
				$uncanny_active_classes = get_option( 'uncanny_toolkit_active_classes', '' );
				
				if ( ! empty( $uncanny_active_classes ) ) {
					if ( key_exists( 'uncanny_pro_toolkit\UncannyDripLessonsByGroup', $uncanny_active_classes ) ) {
						$uo_lesson_id = learndash_get_lesson_id( $lesson_topic_id->ID );
						if ( empty( $uo_lesson_id ) ) {
							$uo_lesson_id = $lesson_topic_id->ID;
						}
						$lesson_access_from = uncanny_pro_toolkit\UncannyDripLessonsByGroup::get_lesson_access_from( $uo_lesson_id, wp_get_current_user()->ID );
						if ( ! empty( $lesson_access_from ) ) {
							$lesson_available_from = $lesson_access_from;
						}
					}
				}
				if ( ! empty( $lesson_available_from ) ) {
					if ( ! is_numeric( $lesson_available_from ) ) {
						$timestamp = strtotime( $lesson_available_from );
					} else {
						$timestamp = $lesson_available_from;
					}
					if ( ! empty( $timestamp ) && $timestamp > current_time( 'timestamp' ) ) {
						?>
						<p class="lesson_available"><?php echo sprintf( __( 'Available on: %s', 'uncanny-pro-toolkit' ), date( 'M d, Y', $timestamp ) ) ?> </p>
						<?php
					}
				} ?>
			
			</div>
			<div class="course-info-holder<?php if ( 'completed' === $completed ) {
				echo ' completed';
			} ?> bottom">
				<?php echo $status_icon; ?>
			</div>
		</a>
	</div>
</div>