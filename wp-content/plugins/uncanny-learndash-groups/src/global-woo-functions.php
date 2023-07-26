<?php

use uncanny_learndash_groups\SharedFunctions;
use uncanny_learndash_groups\Utilities;

/**
 * Class Uncanny_Groups_Woo
 */
class Uncanny_Groups_Woo {

	/**
	 * The instance of the class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Uncanny_Groups_Woo
	 */
	private static $instance = null;

	/**
	 * @return Uncanny_Groups_Woo|null
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			// Lets boot up!
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @return bool
	 */
	public static function ulgm_is_license_in_cart() {
		if ( ! Utilities::if_woocommerce_active() ) {
			return false;
		}
		if ( ! isset( WC()->cart ) ) {
			return false;
		}
		$items = WC()->cart->get_cart_contents();
		if ( $items ) {
			foreach ( $items as $item ) {
				$product = $item['data'];

				if ( $product instanceof WC_Product && $product->is_type( 'license' ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public static function ulgm_is_subscription_license_in_cart() {
		if ( false === Utilities::if_woocommerce_active() || false === Utilities::if_woocommerce_subscription_active() ) {
			return false;
		}
		if ( ! isset( WC()->cart ) ) {
			return false;
		}
		$items = WC()->cart->get_cart_contents();
		if ( empty( $items ) ) {
			return false;
		}
		foreach ( $items as $item ) {
			$product = $item['data'];
			if ( class_exists( 'WC_Subscriptions_Product' ) && \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
				$courses = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
				if ( ! empty( $courses ) ) {
					return true;
				}
			}
		}


		return false;
	}

	/**
	 * @return false
	 */
	public static function ulgm_is_license_for_existing_group_in_cart() {
		if ( ! is_user_logged_in() ) {
			return false;
		}
		$user_id       = wp_get_current_user()->ID;
		$existing_data = SharedFunctions::get_transient_cache( '_ulgm_user_' . $user_id . '_order', $user_id );
		if ( ! $existing_data ) {
			return false;
		}

		return true;
	}
}
