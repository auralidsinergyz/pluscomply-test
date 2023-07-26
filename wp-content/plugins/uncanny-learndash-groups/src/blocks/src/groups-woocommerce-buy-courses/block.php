<?php

/**
 * Register Buy Courses Form
 * render it with a callback function
 */

register_block_type(
	'uncanny-learndash-groups/uo-groups-buy-courses',
	array(
		'attributes'      => array(
			'productCat' => array(
				'type'    => 'string',
				'default' => '',
			),
			'productTag' => array(
				'type'    => 'string',
				'default' => '',
			),
			'minQty'     => array(
				'type'    => 'string',
				'default' => '',
			),
			'maxQty'     => array(
				'type'    => 'string',
				'default' => '',
			),
		),
		'render_callback' => 'render_uo_buy_courses_func',
	)
);

function render_uo_buy_courses_func( $attributes ) {

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_learndash_groups\WoocommerceBuyCourses' ) ) {

		$shortcode_atts = array(
			'product_cat' => $attributes['productCat'],
			'product_tag' => $attributes['productTag'],
			'min_qty'     => $attributes['minQty'],
			'max_qty'     => $attributes['maxQty'],
		);

		$class = \uncanny_learndash_groups\Utilities::get_class_instance( 'WoocommerceBuyCourses' );
		// Check if the course ID is empty
		echo $class->uo_buy_courses_func( $shortcode_atts );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
