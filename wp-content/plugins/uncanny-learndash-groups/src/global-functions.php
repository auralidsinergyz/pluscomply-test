<?php

/**
 * @param string $type
 * @param null $variable
 * @param string $flags
 *
 * @return mixed
 *
 * @since 4.0
 * @package uncanny_learndash_groups
 * @version 4.0
 */
function ulgm_filter_input( $variable = null, $type = INPUT_GET, $flags = FILTER_UNSAFE_RAW ) {
	/*
	 * View input types: https://www.php.net/manual/en/function.filter-input.php
	 * View flags at: https://www.php.net/manual/en/filter.filters.sanitize.php
	 */
	return filter_input( $type, $variable, $flags );
}


/**
 * @param string $type
 * @param null $variable
 * @param string $flags
 *
 * @return mixed
 *
 * @since 4.0
 * @package uncanny_learndash_groups
 * @version 4.0
 */
function ulgm_filter_has_var( $variable = null, $type = INPUT_GET ) {
	return filter_has_var( $type, $variable );
}

/**
 * @param string $type
 * @param null $variable
 * @param string $flags
 *
 * @return mixed
 *
 * @since 4.0
 * @package uncanny_learndash_groups
 * @version 4.0
 */
function ulgm_filter_input_array( $variable = null, $type = INPUT_GET, $flags = array() ) {
	if ( empty( $flags ) ) {
		$flags = array(
			'filter' => FILTER_VALIDATE_INT,
			'flags'  => FILTER_REQUIRE_ARRAY,
		);
	}
	/*
	 * View input types: https://www.php.net/manual/en/function.filter-input.php
	 * View flags at: https://www.php.net/manual/en/filter.filters.sanitize.php
	 */
	$args = array( $variable => $flags );
	$val  = filter_input_array( $type, $args );

	return isset( $val[ $variable ] ) ? $val[ $variable ] : array();
}


/**
 * @param null $group_title
 * @param int $seats
 * @param array $course_ids
 * @param null $first_name
 * @param null $last_name
 * @param null $email
 *
 * @return false|int|WP_Error
 */
function ulgm_create_custom_learndash_group( $group_title = null, $seats = 0, $course_ids = array(), $first_name = null, $last_name = null, $email = null ) {
	if ( null === $group_title ) {
		return false;
	}
	if ( 0 === $group_title ) {
		return false;
	}
	if ( empty( $course_ids ) ) {
		return false;
	}
	$args = array(
		'ulgm_group_name'              => $group_title,
		'ulgm_group_total_seats'       => $seats,
		'ulgm_group_courses'           => $course_ids,
		'ulgm_group_leader_first_name' => $first_name,
		'ulgm_group_leader_last_name'  => $last_name,
		'ulgm_group_leader_email'      => $email,
	);

	return \uncanny_learndash_groups\ProcessManualGroup::process( $args );
}

///**
// * @return bool
// */
//function ulgm_is_license_in_cart() {
//	include_woo_functions();
//
//	return Uncanny_Groups_Woo::ulgm_is_license_in_cart();
//}
//
///**
// * @return bool
// */
//function ulgm_is_subscription_license_in_cart() {
//	include_woo_functions();
//
//	return Uncanny_Groups_Woo::ulgm_is_subscription_license_in_cart();
//}
//
///**
// * @return false
// */
//function ulgm_is_license_for_existing_group_in_cart() {
//	include_woo_functions();
//
//	return Uncanny_Groups_Woo::ulgm_is_license_for_existing_group_in_cart();
//}
