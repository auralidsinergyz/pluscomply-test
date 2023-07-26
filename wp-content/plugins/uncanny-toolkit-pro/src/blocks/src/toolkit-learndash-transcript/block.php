<?php

/**
 * Register uo time
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/learn-dash-transcript', [
	'attributes'      => [
		'logoUrl'    => [
			'type'    => 'string',
			'default' => ''
		],
		'dateFormat' => [
			'type'    => 'string',
			'default' => 'F j, Y'
		]
	],
	'render_callback' => 'render_learn_dash_transcript'
] );

function render_learn_dash_transcript( $attributes ) {

	// Get course ID
	$logo_url    = $attributes['logoUrl'];
	$date_format = $attributes['dateFormat'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\LearnDashTranscript' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\LearnDashTranscript::display_course_transcript( [
			'logo-url'     => $logo_url,
			'date-format' => $date_format,
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
