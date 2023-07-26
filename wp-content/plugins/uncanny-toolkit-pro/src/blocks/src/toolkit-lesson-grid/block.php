<?php

/**
 * Register uo time
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/lesson-topic-grid', [
	'attributes'      => [
		'courseId'               => [
			'type'    => 'string',
			'default' => '',
		],
		'lessonId'             => [
			'type'    => 'string',
			'default' => '',
		],
		'category'               => [
			'type'    => 'string',
			'default' => 'all',
		],
		'tag'             => [
			'type'    => 'string',
			'default' => 'all',
		],
		'cols'                   => [
			'type'    => 'string',
			'default' => '4',
		],
		'showImage'              => [
			'type'    => 'string',
			'default' => 'yes',
		],
		'borderHover'            => [
			'type'    => 'string',
			'default' => '',
		]
	],
	'render_callback' => 'render_uo_lesson_topic_grid'
] );

function render_uo_lesson_topic_grid( $attributes ) {

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\LessonTopicGrid' ) ) {

		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\LessonTopicGrid::uo_lessons_topics_grid( [
			'course_id'    => $attributes['courseId'],
			'lesson_id'    => $attributes['lessonId'],
			'category'     => $attributes['category'],
			'tag'          => $attributes['tag'],
			'cols'         => $attributes['cols'],
			'show_image'   => $attributes['showImage'],
			'border_hover' => $attributes['borderHover'],
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
