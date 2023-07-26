<?php

/**
 * Register Groups Essays Reports
 * render it with a callback function
 */

register_block_type( 'uncanny-learndash-groups/uo-groups-essays-report', [
	'attributes' => [
		'columns' => [
			'type'    => 'string',
			'default' => 'Title, First name, Last name, Username, Status, Points, Question, Content, Course, Lesson, Quiz, Comments, Date',
		],
		'csvExport' => [
			'type'    => 'string',
			'default' => 'hide',
		],
		'excelExport' => [
			'type'    => 'string',
			'default' => 'hide',
		],
	],
	'render_callback' => 'render_display_essays_report',
] );

function render_display_essays_report( $attributes ) {

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_learndash_groups\GroupEssays' ) ) {

		$class = \uncanny_learndash_groups\Utilities::get_class_instance( 'GroupEssays' );

		$columns = $attributes['columns'];
		$excel_export_button = $attributes['excelExport'];
		$csv_export_button = $attributes['csvExport'];

		// Check if the course ID is empty
		echo $class->display_essays( [
			'columns' => $columns,
			'excel_export_button' => $excel_export_button,
			'csv_export_button' => $csv_export_button,
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
