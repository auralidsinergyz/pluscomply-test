<?php

/**
 * Register Course Expiry
 * render it with a callback function
 */

register_block_type( 'uncanny-toolkit-pro/course-expiry', [
	'attributes'      => [
		'preText'  => [
			'type'    => 'string',
			'default' => __( 'Course Access Expires in', 'uncanny-pro-toolkit' )
		],
		'courseId' => [
			'type'    => 'string',
			'default' => ''
		]
	],
	'render_callback' => 'render_course_expiry'
] );

function render_course_expiry( $attributes ) {

	// Get course ID
	$pre_text  = $attributes['preText'];
	$course_id = $attributes['courseId'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\CourseAccessExpiry' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\CourseAccessExpiry::expiration_in( [
			'pre-text'  => $pre_text,
			'course-id' => $course_id
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}