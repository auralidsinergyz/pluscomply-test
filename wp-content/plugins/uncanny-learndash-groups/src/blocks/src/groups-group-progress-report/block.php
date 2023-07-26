<?php

/**
 * Register Groups Reports Inerface
 * render it with a callback function
 */

register_block_type( 'uncanny-learndash-groups/uo-groups-progress-report', [
	'attributes'      => [
		'orderby' => [
			'type'    => 'string',
			'default' => 'ID',
		],
		'order'   => [
			'type'    => 'string',
			'default' => 'asc',
		],
		'expandByDefault'   => [
			'type'    => 'string',
			'default' => 'no',
		],
	],
	'render_callback' => 'render_display_progress_report',
] );

function render_display_progress_report( $attributes ) {

	// Get course ID
	$orderby = $attributes['orderby'];
	$order   = $attributes['order'];
	$expand_by_default   = $attributes['expandByDefault'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_learndash_groups\LearnDashProgressReport' ) ) {

		$class = \uncanny_learndash_groups\Utilities::get_class_instance( 'LearnDashProgressReport' );
		// Check if the course ID is empty
		echo $class->uo_course_dashboard( [
			'orderby' => $orderby,
			'order'   => $order,
			'expand_by_default'   => $expand_by_default
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
