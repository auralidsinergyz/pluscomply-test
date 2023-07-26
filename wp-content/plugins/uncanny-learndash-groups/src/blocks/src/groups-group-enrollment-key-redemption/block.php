<?php

/**
 * Register Groups Essays Reports
 * render it with a callback function
 */

register_block_type(
	'uncanny-learndash-groups/uo-groups-enrollment-key-redemption',
	array(
		'attributes'      => array(
			'redirect' => array(
				'type'    => 'string',
				'default' => '',
			),
		),
		'render_callback' => 'render_user_redeem_code_block',
	)
);

function render_user_redeem_code_block( $attributes ) {
	// Start output
	ob_start();

	if ( class_exists( '\uncanny_learndash_groups\GroupManagementRegistration' ) ) {

		$gmr                        = new \uncanny_learndash_groups\GroupManagementRegistration();
		$gmr::$code_redemption_atts = shortcode_atts(
			array(
				'redirect' => '',
				'role'     => '',
			),
			$attributes,
			'ulgm_code_redemption'
		);

		$gmr->ulgm_show_error_messages();
		include_once \uncanny_learndash_groups\Utilities::get_include( 'forms/user-code-redemption.php' );

	}

	// Get output
	$output = ob_get_clean();

	// Return output
	return $output;
}
