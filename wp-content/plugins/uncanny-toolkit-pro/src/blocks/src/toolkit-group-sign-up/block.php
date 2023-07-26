<?php

/**
 * Register Group Status
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/group-status', [
	'attributes'      => [],
	'render_callback' => 'render_uo_group_status'
] );

function render_uo_group_status() {

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\LearnDashGroupSignUp' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\LearnDashGroupSignUp::group_status();
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}

/**
 * Register Group Org Details
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/group-org-details', [
	'attributes'      => [
		'groupId' => [
			'type'    => 'string',
			'default' => ''
		]
	],
	'render_callback' => 'render_uo_group_org_details'
] );

function render_uo_group_org_details( $attributes ) {
	// Get course ID
	$group_id = $attributes['groupId'];

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\LearnDashGroupSignUp' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\LearnDashGroupSignUp::group_org_details( [
			'group_id' => $group_id
		] );
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}


/**
 * Register Group Org Details
 * render it with a callback function
 */
register_block_type( 'uncanny-toolkit-pro/group-login', [
	'attributes'      => [],
	'render_callback' => 'render_uo_group_login'
] );

function render_uo_group_login() {

	// Start output
	ob_start();

	// Check if the class exists
	if ( class_exists( '\uncanny_pro_toolkit\LearnDashGroupSignUp' ) ) {
		// Check if the course ID is empty
		echo \uncanny_pro_toolkit\LearnDashGroupSignUp::groups_login_form();
	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
