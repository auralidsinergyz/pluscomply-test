<?php

/**
 * Register Groups Reports Inerface
 * render it with a callback function
 */

register_block_type( 'uncanny-learndash-groups/uo-groups-quiz-report', [
	'attributes'      => [
		'courseOrderby' => [
			'type'    => 'string',
			'default' => 'title',
		],
		'courseOrder'   => [
			'type'    => 'string',
			'default' => 'ASC',
		],
		'quizOrderby'   => [
			'type'    => 'string',
			'default' => 'title',
		],
		'quizOrder'     => [
			'type'    => 'string',
			'default' => 'ASC',
		],
	],
	'render_callback' => 'render_display_quiz_report',
] );

function render_display_quiz_report( $attributes ) {

	// Get course ID
	$course_orderby = $attributes['courseOrderby'];
	$course_order   = $attributes['courseOrder'];
	$quiz_orderby   = $attributes['quizOrderby'];
	$quiz_order     = $attributes['quizOrder'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_learndash_groups\GroupQuizReport' ) ) {

		$class = \uncanny_learndash_groups\Utilities::get_class_instance( 'GroupQuizReport' );
		// Check if the course ID is empty
		echo $class->display_quiz_report( [
			'course-orderby' => $course_orderby,
			'course-order'   => $course_order,
			'quiz-orderby'   => $quiz_orderby,
			'quiz-order'     => $quiz_order,
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
