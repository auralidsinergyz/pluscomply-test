<?php

/**
 * Register Group Logo
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/group-logo', [
	'attributes'      => [
		'size' => [
			'type'    => 'string',
			'default' => 'full'
		]
	],
	'render_callback' => 'render_uo_group_logo'
] );

function render_uo_group_logo( $attributes ) {
	// Get course ID
	$size = $attributes['size'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\GroupLogoList' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\GroupLogoList::uo_group_logo( [
			'size' => $size
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}

/**
 * Register Group List
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/group-list', [
	'attributes'      => [
		'separator' => [
			'type'    => 'string',
			'default' => ', '
		]
	],
	'render_callback' => 'render_uo_group_list'
] );

function render_uo_group_list( $attributes ) {
	// Get course ID
	$separator = $attributes['separator'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\GroupLogoList' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\GroupLogoList::uo_group_list( [
			'separator' => $separator
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
