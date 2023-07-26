<?php

/**
 * Register uo time
 * render it with a callback function
 */
register_block_type( 'uncanny-learndash-codes/uo-code-registration', [
	'attributes'      => [
		'redirect'      => [
			'type'    => 'string',
			'default' => '',
		],
		'code_optional' => [
			'type'    => 'string',
			'default' => 'no',
		],
		'auto_login'    => [
			'type'    => 'string',
			'default' => 'yes',
		],
		'role'          => [
			'type'    => 'string',
			'default' => 'subscriber',
		],
	],
	'render_callback' => 'render_user_code_registration',
] );

function render_user_code_registration( $attributes ) {
	
	// Start output
	ob_start();
	
	// Check if the class exists
	if ( class_exists( '\uncanny_learndash_codes\Shortcodes' ) ) {
		// Check if the course ID is empty
		echo \uncanny_learndash_codes\Shortcodes::user_code_registration( [
			'redirect'      => $attributes['redirect'],
			'code_optional' => $attributes['code_optional'],
			'auto_login'    => $attributes['auto_login'],
			'role'          => $attributes['role'],
		] );
	}
	
	// Get output
	$output = ob_get_clean();
	
	// Return output
	return $output;
}
