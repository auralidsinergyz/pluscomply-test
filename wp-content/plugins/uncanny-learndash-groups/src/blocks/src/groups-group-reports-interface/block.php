<?php

/**
 * Register Groups Reports Inerface
 * render it with a callback function
 */

register_block_type( 'uncanny-learndash-groups/uo-groups-course-report', [
	'attributes'      => [
		'transcriptPageId' => [
			'type'    => 'string',
			'default' => '0',
		],
		'courseOrder'      => [
			'type'    => 'string',
			'default' => 'title',
		],
	],
	'render_callback' => 'render_uo_group_course_report',
] );

function render_uo_group_course_report( $attributes ) {

	// Get course ID
	$transcript_page_id = $attributes['transcriptPageId'];
	$course_order       = $attributes['courseOrder'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_learndash_groups\GroupReportsInterface' ) ) {

		$class = \uncanny_learndash_groups\Utilities::get_class_instance( 'GroupReportsInterface' );
		// Check if the course ID is empty
		echo $class->uo_group_course_report( [
			'transcript-page-id' => $transcript_page_id,
			'course-order'       => $course_order,
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
