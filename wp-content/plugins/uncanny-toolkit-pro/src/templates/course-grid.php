<?php
namespace uncanny_pro_toolkit;
/*
 * Available variables
 * ===================
 *
 * $grid_classes -- Core grid layout classes
 * $completed -- If Course is completed
 * $permalink -- Link to Course
 * $atts -- Array of shortcode attributes
 * $price -- LearnDash Course Price
 * $course -- Course Post Object
 * $currency -- $ default // Set from LearnDash
 * $short_description -- Short Description of course
 * $hide_progress -- Hide progress & percentage
 * $status_icon -- Completed, In Progress etc
 * $show_start_button -- Show Start Course Button
 * $percentage -- Course progress in percentage
 *
 */
?>
<div class="<?php echo implode( ' ', $grid_classes ) ?>">
	<div class="uo-border<?php if ( $completed ) {
		echo ' completed';
	} ?>">
		<a href="<?php echo $permalink; ?>">
			<?php do_action( 'uo-course-grid-before-course-info-holder', $course ); ?>
			<?php if ( 'yes' === $atts['price'] && 'yes' === $atts['show_image'] ) { ?>
				<div id="ribbon"
				     class="price  <?php echo ! empty( $course_options['sfwd-courses_course_price'] ) ? "price_" . $currency : esc_html__( 'Free', 'uncanny-pro-toolkit' ) ?>">
					<?php echo esc_html( $price ); ?>
				</div>
			<?php } ?>
			<?php if ( 'yes' === $atts['show_image'] ) { ?>
				<div class="featured-image">
					<?php if ( has_post_thumbnail( $course->ID ) ) { ?>
						<img src="<?php echo \uncanny_pro_toolkit\ShowAllCourses::resize_grid_image( $course->ID, 'uo_course_image_size' ); ?>"
						     class="uo-grid-featured-image" alt="<?php echo $course->post_title . ' ' . 'course image'; ?>"/>
					<?php } else { ?>
						<img
								src="<?php echo plugins_url( '/assets/legacy/frontend/img/no_image.jpg', dirname( __FILE__ ) ) ?>"
								class="uo-grid-featured-image" alt="<?php echo $course->post_title . ' ' . 'course image'; ?>"/>
					<?php } ?>
				</div>
				<?php
			}
			?>
			<div class="course-info-holder<?php if ( $completed ) {
				echo ' completed';
			} ?>">
				<span class="course-title"><?php echo $course->post_title; ?></span>
				<?php
				/**
				 * Check plugin activity is not on the page plugins.
				 */
				include_once( ABSPATH . 'wp-admin'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'plugin.php' );
				if ( \is_plugin_active( 'uncanny-continuing-education-credits/uncanny-continuing-education-credits.php' ) ) {
					$points = get_post_meta( $course->ID, 'ceu_value', true );
					if ( ( 'no' === $atts['hide_credits'] ) && ! empty( $points ) && $points > 0 ) {
						?>
						<p class="cue-points">
							<?php
							echo $points;
							echo ' ';
							if ( 1 === absint( $points ) ) {
								echo get_option( 'credit_designation_label', __( 'CEU', 'uncanny-ceu' ) );
							} else {
								echo get_option( 'credit_designation_label_plural', __( 'CEUs', 'uncanny-ceu' ) );
							}
							?>
						</p>
						<?php
					}
				}
				?>
				<?php if ( ( 'no' === $atts['hide_description'] ) && $short_description ) {
					?>
					<p><?php echo $short_description ?></p>
					<?php
				} ?>
			</div>
			<div class="course-info-holder<?php if ( $completed ) {
				echo ' completed';
			} ?> bottom">
				<?php if ( sprintf( esc_html__( 'View %s Outline', 'uncanny-pro-toolkit' ), \LearnDash_Custom_Label::get_label( 'course' ) ) !== $status_icon && 'Coming Soon' !== $status_icon ) { ?>
					<?php if ( 'no' === $hide_progress ) { ?>
						<h3 class="percentage"><?php echo $percentage ?>%</h3>
						<dd class="uo-course-progress" title="">
							<div class="course_progress" style="width: <?php echo $percentage ?>%;">
							</div>
						</dd>
						<div class="list-tag-container <?php echo sanitize_title( $status_icon ) ?>"><?php echo $status_icon; ?></div>
					<?php } ?>
				<?php } elseif ( sprintf( esc_html__( 'View %s Outline', 'uncanny-pro-toolkit' ), \LearnDash_Custom_Label::get_label( 'course' ) ) !== $status_icon ) { ?>
					<?php if ( 'no' === $hide_progress ) { ?>
						<dd class="uo-course-progress" title="" style="visibility: hidden">
							<div class="course_progress" style="width: 100%;">
							</div>
						</dd>
					<?php } ?>
					<h4><?php esc_html_e( 'Coming Soon', 'uncanny-pro-toolkit' ); ?></h4>
					<div class="list-tag-container <?php echo sanitize_title( 'Coming Soon' ) ?>" style="visibility: hidden">
						&nbsp;
					</div>
				<?php } elseif ( sprintf( esc_html__( 'View %s Outline', 'uncanny-pro-toolkit' ), \LearnDash_Custom_Label::get_label( 'course' ) ) === $status_icon ) { ?>
					<?php if ( 'no' === $hide_progress ) { ?>
						<dd class="uo-course-progress" title="" style="visibility: hidden">
							<div class="course_progress" style="width: 100%;">
							</div>
						</dd>
					<?php } ?>
					<h4 class="view-course-outline">
						<?php echo sprintf( esc_html__( 'View %s Outline', 'uncanny-pro-toolkit' ), \LearnDash_Custom_Label::get_label( 'course' ) ); ?>
					</h4>
					<div class="list-tag-container <?php echo sanitize_title( 'View Course Outline' ) ?>" style="visibility: hidden">
						&nbsp;
					</div>
				<?php } ?>
			</div>

			<?php do_action( 'uo-course-grid-after-course-info-holder', $course ); ?>
		</a>
		<?php //echo $percentage;
		if ( ( 'show' === $show_start_button && $percentage === 0 ) || ( 'show' === $show_resume_button && $percentage > 0 && $percentage < 100 ) ) { ?>
			<div class="uo-toolkit-grid__course-action">
				<?php do_action( 'uo-course-grid-before-action-buttons', $course ); ?>
				<?php if ( 'show' === $show_start_button && $percentage === 0 ) {
					$start_button_html = sprintf( '<a href="%s"><input type="submit" value="%s" class="" /></a>', $permalink, esc_html( 'Start Course', 'uncanny-pro-toolkit' ) );
					echo apply_filters( 'uo-course-grid-start-button', $start_button_html, $course, $permalink );
				} ?>

				<?php if ( 'show' === $show_resume_button && $percentage > 0 && $percentage < 100 ) {
					$uo_active_classes = get_option( 'uncanny_toolkit_active_classes', 0 );
					if ( 0 !== $uo_active_classes ) {
						if ( key_exists( 'uncanny_learndash_toolkit\LearnDashResume', $uo_active_classes ) ) {
							$resume_button_html = do_shortcode( '[uo_course_resume course_id="' . $course->ID . '"]' );
							echo apply_filters( 'uo-course-grid-resume-button', $resume_button_html, $course );
						}
					}
				} ?>
				<?php do_action( 'uo-course-grid-after-action-buttons', $course ); ?>
			</div>
		<?php } ?>

	</div>
</div>