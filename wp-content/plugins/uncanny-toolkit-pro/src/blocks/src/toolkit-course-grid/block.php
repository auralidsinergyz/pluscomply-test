<?php

/**
 * Register uo time
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/course-grid', [
	'attributes'      => [
		'category'               => [
			'type'    => 'string',
			'default' => 'all',
		],
		'ldCategory'             => [
			'type'    => 'string',
			'default' => 'all',
		],
		'tag'                    => [
			'type'    => 'string',
			'default' => 'all',
		],
		'course_tag'             => [
			'type'    => 'string',
			'default' => 'all',
		],
		'enrolledOnly'           => [
			'type'    => 'string',
			'default' => 'no',
		],
		'notEnrolled'            => [
			'type'    => 'string',
			'default' => 'no',
		],
		'limit'                  => [
			'type'    => 'string',
			'default' => '4',
		],
		'cols'                   => [
			'type'    => 'string',
			'default' => '4',
		],
		'hideViewMore'           => [
			'type'    => 'string',
			'default' => 'no',
		],
		'hideCredits'            => [
			'type'    => 'string',
			'default' => 'no',
		],
		'hideDescription'        => [
			'type'    => 'string',
			'default' => 'no',
		],
		'hideProgress'           => [
			'type'    => 'string',
			'default' => 'no',
		],
		'more'                   => [
			'type'    => 'string',
			'default' => '',
		],
		'showImage'              => [
			'type'    => 'string',
			'default' => 'yes',
		],
		'price'                  => [
			'type'    => 'string',
			'default' => 'yes',
		],
		'currency'               => [
			'type'    => 'string',
			'default' => '$',
		],
		'linkToCourse'           => [
			'type'    => 'string',
			'default' => 'yes',
		],
		'orderby'                => [
			'type'    => 'string',
			'default' => 'title',
		],
		'order'                  => [
			'type'    => 'string',
			'default' => 'ASC',
		],
		'defaultSorting'         => [
			'type'    => 'string',
			'default' => 'course-progress,enrolled,not-enrolled,coming-soon,completed',
		],
		'ignoreDefaultSorting'   => [
			'type'    => 'string',
			'default' => 'no',
		],
		'borderHover'            => [
			'type'    => 'string',
			'default' => '',
		],
		'viewMoreColor'          => [
			'type'    => 'string',
			'default' => '',
		],
		'viewMoreHover'          => [
			'type'    => 'string',
			'default' => '',
		],
		'viewMoreTextColor'      => [
			'type'    => 'string',
			'default' => '',
		],
		'viewMoreText'           => [
			'type'    => 'string',
			'default' => 'View More <i class="fa fa fa-arrow-circle-right"></i>',
		],
		'viewLessText'           => [
			'type'    => 'string',
			'default' => 'View Less <i class="fa fa fa-arrow-circle-right"></i>',
		],
		'categoryselector'       => [
			'type'    => 'string',
			'default' => 'hide',
		],
		'courseCategoryselector' => [
			'type'    => 'string',
			'default' => 'hide',
		],
		'resumeCourseButton'     => [
			'type'    => 'string',
			'default' => 'hide',
		],
		'startCourseButton'      => [
			'type'    => 'string',
			'default' => 'hide',
		],
	],
	'render_callback' => 'render_uo_course_grid',
] );

function render_uo_course_grid( $attributes ) {

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\ShowAllCourses' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\ShowAllCourses::uo_courses( [
			'category'                => $attributes['category'],
			'ld_category'             => $attributes['ldCategory'],
			'tag'                     => $attributes['tag'],
			'course_tag'              => $attributes['course_tag'],
			'enrolled_only'           => $attributes['enrolledOnly'],
			'not_enrolled'            => $attributes['notEnrolled'],
			'limit'                   => $attributes['limit'],
			'cols'                    => $attributes['cols'],
			'hide_view_more'          => $attributes['hideViewMore'],
			'hide_credits'            => $attributes['hideCredits'],
			'hide_description'        => $attributes['hideDescription'],
			'hide_progress'           => $attributes['hideProgress'],
			'more'                    => $attributes['more'],
			'show_image'              => $attributes['showImage'],
			'price'                   => $attributes['price'],
			'currency'                => $attributes['currency'],
			'link_to_course'          => $attributes['linkToCourse'],
			'orderby'                 => $attributes['orderby'],
			'order'                   => $attributes['order'],
			'default_sorting'         => $attributes['defaultSorting'],
			'ignore_default_sorting'  => $attributes['ignoreDefaultSorting'],
			'border_hover'            => $attributes['borderHover'],
			'view_more_color'         => $attributes['viewMoreColor'],
			'view_more_hover'         => $attributes['viewMoreHover'],
			'view_more_text_color'    => $attributes['viewMoreTextColor'],
			'view_more_text'          => $attributes['viewMoreText'],
			'view_less_text'          => $attributes['viewLessText'],
			'categoryselector'        => $attributes['categoryselector'],
			'course_categoryselector' => $attributes['courseCategoryselector'],
			'start_course_button'     => $attributes['startCourseButton'],
			'resume_course_button'    => $attributes['resumeCourseButton'],
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
