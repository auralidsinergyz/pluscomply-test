<?php
/**
 * Displays a course
 *
 * Available Variables:
 * $course_id        : (int) ID of the course
 * $course        : (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id        : Current User ID
 * $logged_in        : User is logged in
 * $current_user    : (object) Currently logged in user object
 *
 * $course_status    : Course Status
 * $has_access    : User has access to course or is enrolled.
 * $materials        : Course Materials
 * $has_course_content        : Course has course content
 * $lessons        : Lessons Array
 * $quizzes        : Quizzes Array
 * $lesson_progression_enabled    : (true/false)
 * $has_topics        : (true/false)
 * $lesson_topics    : (array) lessons topics
 *
 * @since 2.1.0
 *
 * @package LearnDash\Course
 */
/**
 * Display course status
 */
?>
<?php if ( $logged_in ) : ?>
	<span id="learndash_course_status">
		<b><?php printf( _x( '%s Status:', 'Course Status Label', 'learndash' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?></b> <?php echo $course_status; ?>
		<br/>
	</span>
	<br/>

	<?php
	/**
	 * Filter to add custom content after the Course Status section of the Course template output.
		 *
	 * @since 2.3
	 * See https://bitbucket.org/snippets/learndash/7oe9K for example use of this filter.
	 */
	echo apply_filters( 'ld_after_course_status_template_container', '', learndash_course_status_idx( $course_status ), $course_id, $user_id );
	?>

	<?php if ( ! empty( $course_certficate_link ) ) : ?>
		<div id="learndash_course_certificate" class="learndash_course_certificate">
			<a href='<?php echo esc_attr( $course_certficate_link ); ?>' class="btn-blue" target="_blank"><?php echo apply_filters( 'ld_certificate_link_label', __( 'PRINT YOUR CERTIFICATE', 'learndash' ), $user_id, $post->ID ); ?></a>
		</div>
		<br/>
	<?php endif; ?>
<?php endif; ?>

<div class="learndash_content"><?php echo $content; ?></div>

<?php if ( ! $has_access ) : ?>
	<?php echo learndash_payment_buttons( $post ); ?>
<?php endif; ?>
<?php if ( isset( $materials ) && ! empty( $materials ) ) : ?>
	<div id="learndash_course_materials" class="learndash_course_materials">
		<h4><?php printf( _x( '%s Materials', 'Course Materials Label', 'learndash' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?></h4>
		<p><?php echo $materials; ?></p>
	</div>
<?php endif; ?>
<?php
if ( ! has_shortcode( $post->post_content, 'uo_lessons_topics_grid' ) ) {

	$cols = \uncanny_learndash_toolkit\Config::get_settings_value( 'uncanny-lesson-grid-default-cols', 'uncanny_pro_toolkit\LessonTopicGrid' );
	//\uncanny_learndash_toolkit\Boot::trace_logs( $cols, '$cols', 'cols' );
	if ( ! empty( $cols ) ) {
		$total = $cols;
	} else {
		$total = 2;
	}
//echo do_shortcode( '[uo_lessons_topics_grid course_id="' . $course_id . '" cols="' . $total . '" is_lesson="yes"]' );
	echo do_shortcode( '[uo_lessons_topics_grid course_id="' . $course_id . '" cols="' . $total . '"]' );
}
?>
<?php if ( ! empty( $quizzes ) && is_user_logged_in() ) : ?>
	<div id="learndash_quizzes" class="learndash_quizzes">
		<div id="quiz_heading">
			<span><?php echo LearnDash_Custom_Label::get_label( 'quizzes' ) ?></span><span class="right"><?php _e( 'Status', 'learndash' ); ?></span>
		</div>
		<div id="quiz_list" class=“quiz_list”>

			<?php foreach ( $quizzes as $quiz ) : ?>
				<div id='post-<?php echo esc_attr( $quiz['post']->ID ); ?>' class='<?php echo esc_attr( $quiz['sample'] ); ?>'>
					<div class="list-count"><?php echo $quiz['sno']; ?></div>
					<h4>
						<a class='<?php echo esc_attr( $quiz['status'] ); ?>' href='<?php echo esc_attr( $quiz['permalink'] ); ?>'><?php echo $quiz['post']->post_title; ?></a>
					</h4>
				</div>
			<?php endforeach; ?>

		</div>
	</div>
<?php endif; ?>
