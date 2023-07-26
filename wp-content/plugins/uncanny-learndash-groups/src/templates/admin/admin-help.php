<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get data about the license
$license = get_option( 'ulgm_license_key' );
// This will be either "valid", "invalid", "expired", "disabled"
$status  = get_option( 'ulgm_license_status' );

// Check license status
$license_is_active = $status == 'valid' ? true : false;

// Check if the user wants to send a ticket
$is_send_ticket_page = isset( $_GET[ 'send-ticket' ] );

if ( $is_send_ticket_page ){
	// Show Send a ticket
	include Utilities::get_template( 'admin/admin-send-ticket.php' );
}
else {
	// Show KB articles
	include Utilities::get_template( 'admin/admin-kb-articles.php' );
}

?>
