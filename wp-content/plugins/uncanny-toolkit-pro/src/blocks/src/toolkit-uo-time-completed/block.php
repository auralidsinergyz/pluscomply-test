<?php

/**
 * Register uo time
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/uo-time-completed', [
	'attributes'      => [
		'userId'  => [
			'type'    => 'string',
			'default' => ''
		],
		'courseId' => [
			'type'    => 'string',
			'default' => ''
		]
	],
	'render_callback' => 'render_uo_time_completed'
] );

function render_uo_time_completed( $attributes ) {

	// Get course ID
	$user_id  = $attributes['userId'];
	$course_id = $attributes['courseId'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\CourseTimer' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\CourseTimer::shortcode_uo_time_course_completed( [
			'user-id'  => $user_id,
			'course-id' => $course_id
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}