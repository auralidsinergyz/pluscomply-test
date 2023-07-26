<?php

namespace uncanny_learndash_groups;

/**
 * @since 4.0.4
 */
class WoocommercePaymentComplete {

	/**
	 * Constructor
	 */
	public function __construct() {

		/**
		 * Autocomplete Uncanny Groups orders
		 * @since 4.0.4
		 * @author Saad S.
		 */
		if ( 'yes' === get_option( 'ulgm_complete_group_license_orders', 'no' ) ) {
			add_filter(
				'woocommerce_payment_complete_order_status',
				array(
					$this,
					'autocomplete_uo_groups_order',
				),
				999,
				2
			);
		}
	}

	/**
	 * @param $status
	 * @param $order_id
	 *
	 * @return string
	 */
	public function autocomplete_uo_groups_order( $status, $order_id ) {
		if ( null === $order_id ) {
			return $status;
		}

		// Order is not in processing status, bail
		if ( 'processing' !== (string) $status ) {
			return $status;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order instanceof \WC_Order ) {
			return $status;
		}

		$line_items = $order->get_items();
		// No products found, bail
		if ( ! $line_items ) {
			return $status;
		}

		$course_type       = $this->has_course_type_product( $order );
		$license_type      = $this->has_license_type_product( $order );
		$group_type        = $this->has_groups_type_product( $order );
		$subscription_type = $this->has_subscription_license_type_product( $order );

		/**
		 * Bail early if none of Groups type product found in order
		 */
		if ( false === $course_type && false === $license_type && false === $group_type && false === $subscription_type ) {
			return $status;
		}

		/**
		 * Bail early if using non-payment methods of groups type product
		 */
		if ( $this->has_groups_type_product( $order ) ) {
			$manual_payment_methods = apply_filters(
				'learndash_woocommerce_manual_payment_methods',
				array(
					'bacs',
					'cheque',
					'cod',
				)
			);

			// If using manual payment, bail
			$payment_method = $order->get_payment_method();
			if ( in_array( $payment_method, $manual_payment_methods ) ) {
				return $status;
			}
		}

		$other_products = array();
		foreach ( $line_items as $item ) {
			$product    = $item->get_product();
			$product_id = $product->get_id();
			// check if product is virtual or downloadable regardless of the product type
			if ( ! $product->get_virtual() && ! $product->get_downloadable() ) {
				// only add to $other_products if it's not a groups type product
				if ( true === $this->is_courses_type_product( $product ) || true === $this->is_license_type_product( $product ) || true === $this->is_groups_type_product( $product ) || true === $this->is_subscription_type_product( $product ) ) {
					continue;
				}
				// The product is not virtual and not groups type
				$other_products[ $product_id ] = $product->get_type();
			}
		}

		// Other product type found which is not virtual type in order, bail
		if ( ! empty( $other_products ) ) {
			return $status;
		}

		// Seems good to mark order complete.
		return 'completed';
	}

	/**
	 * @param \WC_Order $order
	 *
	 * @return bool
	 */
	public function has_course_type_product( \WC_Order $order ) {
		$items = $order->get_items();
		if ( empty( $items ) ) {
			return false;
		}
		foreach ( $items as $item ) {
			$product = $item->get_product();
			if ( $product instanceof \WC_Product && $product->is_type( 'courses' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param \WC_Order $order
	 *
	 * @return bool
	 */
	public function has_license_type_product( \WC_Order $order ) {
		$items = $order->get_items();
		if ( empty( $items ) ) {
			return false;
		}
		foreach ( $items as $item ) {
			$product = $item->get_product();
			if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param \WC_Order $order
	 *
	 * @return bool
	 */
	public function has_groups_type_product( \WC_Order $order ) {
		$items = $order->get_items();
		if ( empty( $items ) ) {
			return false;
		}
		$found = array();
		foreach ( $items as $item ) {
			$product = $item->get_product();
			if ( false === $this->is_groups_type_product( $product ) ) {
				continue;
			}
			$product_id = $product->get_id();
			$found[]    = $product_id;
		}

		if ( empty( $found ) ) {
			return false;
		}

		if ( count( $found ) > 0 ) {
			return true;
		}

		return false;
	}

	/**
	 * @param \WC_Order $order
	 *
	 * @return bool
	 */
	public function has_subscription_license_type_product( \WC_Order $order ) {
		if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
			return false;
		}

		$items = $order->get_items();
		if ( empty( $items ) ) {
			return false;
		}
		/** @var \WC_Order_Item_Product $item */
		foreach ( $items as $item ) {
			$product = $item->get_product();

			if ( false === $this->is_subscription_type_product( $product ) ) {
				continue;
			}

			return $this->is_subscription_type_product( $product );
		}

		return false;
	}

	/**
	 * @param \WC_Product $product
	 *
	 * @return bool
	 */
	public function is_courses_type_product( \WC_Product $product ) {
		if ( $product instanceof \WC_Product && $product->is_type( 'courses' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param \WC_Product $product
	 *
	 * @return bool
	 */
	public function is_license_type_product( \WC_Product $product ) {
		if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param \WC_Product $product
	 *
	 * @return bool
	 */
	public function is_groups_type_product( \WC_Product $product ) {
		$product_id = $product->get_id();
		$groups     = get_post_meta( $product_id, '_related_group', true );
		if ( empty( $groups ) || ! is_array( $groups ) ) {
			return false;
		}
		//removing any 0 group ids
		unset( $groups[ array_search( 0, $groups ) ] );

		if ( 1 === count( $groups ) && in_array( 0, $groups ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @param \WC_Product $product
	 *
	 * @return bool
	 */
	public function is_subscription_type_product( \WC_Product $product ) {
		if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
			return false;
		}

		if ( ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
			return false;
		}

		$courses = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
		if ( ! empty( $courses ) ) {
			return true;
		}

		return false;
	}
}
