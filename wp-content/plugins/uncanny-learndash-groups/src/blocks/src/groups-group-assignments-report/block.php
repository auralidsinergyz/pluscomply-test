<?php

/**
 * Register Groups Essays Reports
 * render it with a callback function
 */

register_block_type( 'uncanny-learndash-groups/uo-groups-assignments-report', [
	'attributes' => [
		'columns' => [
			'type'    => 'string',
			'default' => 'Title, First name, Last name, Username, Status, Points, Assigned Course, Assigned Lesson, Comments, Date',
		],
		'status' => [
			'type'    => 'string',
			'default' => 'not-approved',
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
	'render_callback' => 'render_display_assignments_report',
] );

function render_display_assignments_report( $attributes ) {
	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_learndash_groups\GroupAssignments' ) ) {

		$columns = $attributes['columns'];
		$status  = $attributes['status'];
		$excel_export_button = $attributes['excelExport'];
		$csv_export_button = $attributes['csvExport'];

		$class = \uncanny_learndash_groups\Utilities::get_class_instance( 'GroupAssignments' );

		// Check if the course ID is empty
		echo $class->display_assignments( [
			'columns' => $columns,
			'status' => $status,
			'excel_export_button' => $excel_export_button,
			'csv_export_button' => $csv_export_button,
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
