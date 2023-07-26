<?php

namespace uncanny_learndash_groups;

/**
 * Class Group_Management_Pages
 * @package uncanny_learndash_groups
 */
class Group_Management_Pages {
	/**
	 * @var
	 */
	public static $instance;

	/**
	 * @return Group_Management_Pages
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the page id of the report page
	 *
	 * @param bool $url Return url of page
	 *
	 * @return int/string Page ID or the URL
	 */
	public function get_group_report_page_id( $url = false ) {

		SharedFunctions::$group_report_page_id = get_option( 'ulgm_group_report_page', '' );

		// Return URL
		if ( $url && ! empty( SharedFunctions::$group_report_page_id ) ) {
			return get_permalink( (int) SharedFunctions::$group_report_page_id );
		}

		// Return ID
		return SharedFunctions::$group_report_page_id;
	}

	/**
	 * Get the page id of the quiz report page
	 *
	 * @param bool $url Return url of page
	 *
	 * @return int/string Page ID or the URL
	 */
	public function get_group_quiz_report_page_id( $url = false ) {

		SharedFunctions::$group_quiz_report_page_id = get_option( 'ulgm_group_quiz_report_page', '' );

		// Return URL
		if ( $url && ! empty( SharedFunctions::$group_quiz_report_page_id ) ) {
			return get_permalink( (int) SharedFunctions::$group_quiz_report_page_id );
		}

		// Return ID
		return SharedFunctions::$group_quiz_report_page_id;
	}

	/**
	 * Get the page id of the quiz report page
	 *
	 * @param bool $url Return url of page
	 *
	 * @return int/string Page ID or the URL
	 */
	public function get_group_manage_progress_report_page_id( $url = false ) {

		SharedFunctions::$group_manage_progress_report_page_id = get_option( 'ulgm_group_manage_progress_page', '' );

		// Return URL
		if ( $url && ! empty( SharedFunctions::$group_manage_progress_report_page_id ) ) {
			return get_permalink( (int) SharedFunctions::$group_manage_progress_report_page_id );
		}

		// Return ID
		return SharedFunctions::$group_manage_progress_report_page_id;
	}

	/**
	 * Get the page id of the Assignment report page
	 *
	 * @param bool $url Return url of page
	 *
	 * @return int/string Page ID or the URL
	 */
	public function get_group_assignment_report_page_id( $url = false ) {

		SharedFunctions::$group_assignment_report_page_id = get_option( 'ulgm_group_assignment_report_page', '' );

		// Return URL
		if ( $url && ! empty( SharedFunctions::$group_assignment_report_page_id ) ) {
			return get_permalink( (int) SharedFunctions::$group_assignment_report_page_id );
		}

		// Return ID
		return SharedFunctions::$group_assignment_report_page_id;
	}

	/**
	 * Get the page id of the Essay report page
	 *
	 * @param bool $url Return url of page
	 *
	 * @return int/string Page ID or the URL
	 */
	public function get_group_essay_report_page_id( $url = false ) {

		SharedFunctions::$group_essay_report_page_id = get_option( 'ulgm_group_essay_report_page', '' );

		// Return URL
		if ( $url && ! empty( SharedFunctions::$group_essay_report_page_id ) ) {
			return get_permalink( (int) SharedFunctions::$group_essay_report_page_id );
		}

		// Return ID
		return SharedFunctions::$group_essay_report_page_id;
	}

	/**
	 * Get the page id of the buy courses page
	 *
	 * @param bool $url Return url of page
	 *
	 * @return int/string Page ID or the URL
	 */
	public function get_buy_courses_page_id( $url = false ) {

		SharedFunctions::$buy_courses_page_id = get_option( 'ulgm_group_buy_courses_page', '' );

		// Return URL
		if ( $url && ! empty( SharedFunctions::$buy_courses_page_id ) ) {
			return get_permalink( (int) SharedFunctions::$buy_courses_page_id );
		}

		// Return ID
		return SharedFunctions::$buy_courses_page_id;
	}

	/**
	 * Get the page id of the group management page
	 *
	 * @param bool $url Return url of page
	 *
	 * @return int/string Page ID or the URL
	 */
	public function get_group_management_page_id( $url = false ) {
		SharedFunctions::$group_management_page_id = get_option( 'ulgm_group_management_page', '' );

		// Return URL
		if ( $url && ! empty( SharedFunctions::$group_management_page_id ) ) {
			return apply_filters( 'ulgm_group_management_page_link', get_permalink( (int) SharedFunctions::$group_management_page_id ), (int) SharedFunctions::$group_management_page_id );
		}

		// Return ID
		return SharedFunctions::$group_management_page_id;
	}

	/**
	 * Get the add to cart link for extra seats in a group
	 *
	 * @param int $group_id
	 *
	 * @return string $link Link to add seats to cart
	 * @internal param int $amount_seats
	 *
	 */
	public function add_buy_courses_link( $group_id ) {
		$link = $this->get_buy_courses_page_id( true );
		if ( is_null( $group_id ) || ! is_numeric( $group_id ) ) {
			return $link;
		}
		$product_id = SharedFunctions::get_product_id_from_group_id( $group_id );
		if ( empty( $product_id ) ) {
			return $link;
		}
		$link .= '?modify-license=' . $product_id['product_id'] . "&group-id={$group_id}&_wpnonce=" . wp_create_nonce( Utilities::get_plugin_name() );

		return $link;

	}
}
