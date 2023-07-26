<?php

/**
 * Register Groups Essays Reports
 * render it with a callback function
 */

register_block_type(
	'uncanny-learndash-groups/uo-groups-enrollment-key-registration',
	array(
		'attributes'      => array(
			'redirect'      => array(
				'type'    => 'string',
				'default' => '',
			),
			'code_optional' => array(
				'type'    => 'string',
				'default' => 'no',
			),
			'auto_login'    => array(
				'type'    => 'string',
				'default' => 'yes',
			),
			'role'          => array(
				'type'    => 'string',
				'default' => 'subscriber',
			),
		),
		'render_callback' => 'render_user_register_code_block',
	)
);

function render_user_register_code_block( $attributes ) {
	// Start output
	ob_start();

	if ( class_exists( '\uncanny_learndash_groups\GroupManagementRegistration' ) ) {
		$gmr                          = new \uncanny_learndash_groups\GroupManagementRegistration();
		$gmr::$code_registration_atts = shortcode_atts(
			array(
				'redirect'      => '',
				'code_optional' => 'no',
				'auto_login'    => 'yes',
				'role'          => get_option( 'default_role', 'subscriber' ),
				'enable_terms'  => 'yes',
			),
			$attributes,
			'ulgm_user_registration'
		);

		$gmr->ulgm_show_error_messages();
		include_once \uncanny_learndash_groups\Utilities::get_include( 'forms/user-registration-form.php' );
	}
	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
