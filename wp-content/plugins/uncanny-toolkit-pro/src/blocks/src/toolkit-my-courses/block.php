<?php

/**
 * Register uo time
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/learn-dash-my-courses', [
	'attributes'      => [
		'orderby'                 => [
			'type'    => 'string',
			'default' => 'ID',
		],
		'order'                   => [
			'type'    => 'string',
			'default' => 'asc',
		],
		'show'                    => [
			'type'    => 'string',
			'default' => 'enrolled',
		],
		'ldCategory'              => [
			'type'    => 'string',
			'default' => '',
		],
		'category'                => [
			'type'    => 'string',
			'default' => '',
		],
		'categoryselector'        => [
			'type'    => 'boolean',
			'default' => false,
		],
		'course_categoryselector' => [
			'type'    => 'boolean',
			'default' => false,
		],
		'expand_by_default' => [
			'type'    => 'string',
			'default' => 'no',
		],
	],
	'render_callback' => 'render_uo_course_dashboard',
] );

function render_uo_course_dashboard( $attributes ) {
	if ( empty( $attributes['category'] ) ){
		$attributes['category'] = 'all';
	}

	if ( empty( $attributes['ldCategory'] ) ){
		$attributes['ldCategory'] = 'all';
	}

	// Check if the user is using the toggle to define this parameter
	if ( $attributes['categoryselector'] === false ){
		$categoryselector = 'hide';
	}

	if ( $attributes['categoryselector'] === true ){
		$categoryselector = 'show';
	}

	// Check if the user is using the toggle to define this parameter
	if ( $attributes['course_categoryselector'] === false ){
		$course_categoryselector = 'hide';
	}

	if ( $attributes['course_categoryselector'] === true ){
		$course_categoryselector = 'show';
	}

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\learnDashMyCourses' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\learnDashMyCourses::uo_course_dashboard( [
			'orderby'                 => $attributes['orderby'],
			'order'                   => $attributes['order'],
			'show'                    => $attributes['show'],
			'ld_category'             => $attributes['ldCategory'],
			'category'                => $attributes['category'],
			'categoryselector'        => $categoryselector,
			'course_categoryselector' => $course_categoryselector,
			'expand_by_default'       => $attributes['expand_by_default'],
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
