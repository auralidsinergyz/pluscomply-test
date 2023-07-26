<?php

/**
 * Register Reset Button
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/reset-button', [
	'attributes'      => [
		'courseId'      => [
			'type'    => 'string',
			'default' => ''
		],
		'resetTincanny' => [
			'type'    => 'string',
			'default' => 'no'
		]
	],
	'render_callback' => 'render_uo_reset_button'
] );

function render_uo_reset_button( $attributes ) {
	// Get course ID
	$course_id     = $attributes['courseId'];
	$resetTincanny = $attributes['resetTincanny'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\LearnDashReset' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\LearnDashReset::learndash_reset( [
			'course_id'      => $course_id,
			'reset_tincanny' => $resetTincanny,
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}