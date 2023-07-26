<?php

namespace uncanny_learndash_groups;

/**
 * Class WoocommerceMinMaxQuantity
 *
 * @package uncanny_learndash_groups
 */
class WoocommerceMinMaxQuantity {

	/**
	 * WoocommerceMinMaxQuantity constructor.
	 */
	public function __construct() {

		// The code for displaying WooCommerce Product Custom Fields
		add_action(
			'woocommerce_product_options_pricing',
			array(
				$this,
				'woocommerce_product_custom_fields',
			)
		);

		// Following code Saves  WooCommerce Product Custom Fields
		add_action(
			'woocommerce_process_product_meta',
			array(
				$this,
				'woocommerce_product_custom_fields_save',
			),
			10,
			2
		);

		add_filter(
			'woocommerce_quantity_input_args',
			array(
				$this,
				'woocommerce_qty_input_args',
			),
			10,
			2
		);

		add_filter(
			'woocommerce_add_to_cart_validation',
			array(
				$this,
				'woocommerce_qty_add_to_cart_validation',
			),
			1,
			5
		);

		add_action(
			'woocommerce_before_calculate_totals',
			array(
				$this,
				'woocommerce_fixed_price_refresh',
			),
			PHP_INT_MAX
		);
	}

	/**
	 * Get WooCommerce product custom fields.
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function woocommerce_product_custom_fields() {
		global $woocommerce, $post;

		$product = wc_get_product( $post->ID );

		if ( false === SharedFunctions::is_group_licensed_product( $product ) ) {
			return;
		}

		echo '<div class="options_group">';

		woocommerce_wp_checkbox(
			array(
				'id'          => 'ulgm_license_fixed_price',
				'value'       => get_post_meta( get_the_ID(), 'ulgm_license_fixed_price', true ),
				'label'       => __( 'Fixed price', 'uncanny-learndash-groups' ),
				'desc_tip'    => true,
				/* translators: seats  */
				'description' => sprintf( __( 'The license price set above will be fixed regardless of the quantity of %s purchased.', 'uncanny-learndash-groups' ), SharedFunctions::get_per_seat_text( 2 ) ),
			)
		);

		woocommerce_wp_text_input(
			array(
				'label'       => __( 'Minimum quantity', 'uncanny-learndash-groups' ),
				'placeholder' => __( 'Enter minimum quantity', 'uncanny-learndash-groups' ),
				'id'          => 'ulgm_license_min_qty',
				'desc_tip'    => true,
				'class'       => 'short wc_input_price',
				/* translators: seats  */
				'description' => sprintf( __( 'The minimum number of %s the user can purchase.', 'uncanny-learndash-groups' ), SharedFunctions::get_per_seat_text( 2 ) ),
			)
		);

		woocommerce_wp_text_input(
			array(
				'label'       => __( 'Maximum quantity', 'uncanny-learndash-groups' ),
				'placeholder' => __( 'Enter maximum quantity', 'uncanny-learndash-groups' ),
				'id'          => 'ulgm_license_max_qty',
				'desc_tip'    => true,
				'class'       => 'short wc_input_price',
				/* translators: seats  */
				'description' => sprintf( __( 'The maximum number of %s the user can purchase in a single transaction.', 'uncanny-learndash-groups' ), SharedFunctions::get_per_seat_text( 2 ) ),
			)
		);

		echo '</div>';
	}


	/**
	 * Save WooCommerce product custom fields.
	 *
	 * @param $post_id
	 *
	 * @return void
	 */
	public function woocommerce_product_custom_fields_save( $post_id ) {

		$product = wc_get_product( $post_id );

		if ( false === SharedFunctions::is_group_licensed_product( $product ) ) {
			return;
		}

		$new_min         = isset( $_POST['ulgm_license_min_qty'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['ulgm_license_min_qty'] ) ) : 1; //phpcs:ignore WordPress.Security.NonceVerification.Missing
		$new_max         = isset( $_POST['ulgm_license_max_qty'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['ulgm_license_max_qty'] ) ) : 99999; //phpcs:ignore WordPress.Security.NonceVerification.Missing
		$new_fixed_price = isset( $_POST['ulgm_license_fixed_price'] ) ? sanitize_text_field( wp_unslash( $_POST['ulgm_license_fixed_price'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( ! empty( $new_min ) ) {
			update_post_meta( $post_id, 'ulgm_license_min_qty', $new_min );
		}
		if ( ! empty( $new_max ) ) {
			update_post_meta( $post_id, 'ulgm_license_max_qty', $new_max );
		}
		if ( null !== $new_fixed_price ) {
			update_post_meta( $post_id, 'ulgm_license_fixed_price', $new_fixed_price );
		} else {
			delete_post_meta( $post_id, 'ulgm_license_fixed_price' );
		}
	}

	/**
	 * Setting minimum and maximum for quantity input args.
	 *
	 * @param $args
	 * @param \WC_Product $product
	 *
	 * @return mixed
	 */
	public function woocommerce_qty_input_args( $args, $product ) {

		$product_id  = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
		$product_min = $this->woocommerce_get_product_min_limit( $product_id );
		$product_max = $this->woocommerce_get_product_max_limit( $product_id );

		if ( is_numeric( $product_min ) ) {
			// min is empty
			$args['min_value'] = $product_min;
		}

		if ( is_numeric( $product_max ) ) {
			// max is empty
			$args['max_value'] = $product_max;
		}

		if ( $product->managing_stock() && ! $product->backorders_allowed() ) {
			$stock = $product->get_stock_quantity();

			$args['max_value'] = min( $stock, $args['max_value'] );
		}

		return $args;
	}

	/**
	 * Get WooCommerce product maximum qty field meta.
	 *
	 * @param $product_id
	 *
	 * @return false|int
	 */
	public function woocommerce_get_product_max_limit( $product_id ) {
		return get_post_meta( $product_id, 'ulgm_license_max_qty', true );
	}

	/**
	 * Get WooCommerce product minimum qty field meta.
	 *
	 * @param $product_id
	 *
	 * @return false|int
	 */
	public function woocommerce_get_product_min_limit( $product_id ) {
		return get_post_meta( $product_id, 'ulgm_license_min_qty', true );
	}

	/**
	 * Validating the quantity on add to cart action with the quantity of the same product available in the cart.
	 *
	 * @param $passed
	 * @param $product_id
	 * @param $quantity
	 * @param $variation_id
	 * @param $variations
	 *
	 * @return false|mixed
	 */
	public function woocommerce_qty_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {

		$product_min = $this->woocommerce_get_product_min_limit( $product_id );
		$product_max = $this->woocommerce_get_product_max_limit( $product_id );

		if ( ! is_numeric( $product_min ) && ! is_numeric( $product_max ) ) {
			return $passed;
		}

		$already_in_cart = $this->woocommerce_qty_get_cart_qty( $product_id );
		$product         = wc_get_product( $product_id );
		$product_title   = $product->get_title();

		if ( is_numeric( $product_max ) && is_numeric( $already_in_cart ) ) {

			if ( ( $already_in_cart + $quantity ) > $product_max ) {
				// oops. too much.
				$passed = false;
				$diff   = absint( $product_max - $already_in_cart );
				wc_add_notice(
					apply_filters(
						'ulgm_wc_max_qty_error_message',
						sprintf(
							__( "You can add a maximum of %1\$s %2\$s's to %3\$s. You had %4\$d in the cart, %4\$d more added.", 'uncanny-learndash-groups' ),
							$product_max,
							$product_title,
							'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'uncanny-learndash-groups' ) . '</a>',
							$already_in_cart
						),
						$product_max,
						$already_in_cart,
						$diff
					),
					'error'
				);
				WC()->cart->add_to_cart( $product_id, $diff );
			}
			if ( ( $already_in_cart + $quantity ) < $product_min ) {
				// oops. not enough.
				$passed = false;
				wc_add_notice(
					apply_filters(
						'ulgm_wc_min_qty_error_message',
						sprintf(
							__( "You need to add a minimum of %1\$s %2\$s's to %3\$s.", 'uncanny-learndash-groups' ),
							$product_min,
							$product_title,
							'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'uncanny-learndash-groups' ) . '</a>'
						),
						$product_min,
						$already_in_cart
					),
					'error'
				);
				WC()->cart->add_to_cart( $product_id, $product_min );
			}
		}

		return $passed;
	}

	/*
	* Get the total quantity of the product available in the cart.
	 *
	 * @param $product_id
	 *
	 * @return int
	 */
	public function woocommerce_qty_get_cart_qty( $product_id ) {
		global $woocommerce;
		$running_qty = 0;
		if ( empty( $woocommerce->cart->get_cart() ) ) {
			return $running_qty;
		}
		// search the cart for the product in and calculate quantity.
		foreach ( $woocommerce->cart->get_cart() as $values ) {
			if ( absint( $product_id ) === absint( $values['product_id'] ) ) {
				$running_qty += (int) $values['quantity'];
			}
		}

		return $running_qty;
	}

	/**
	 * WooCommerce Fixed price refresh
	 *
	 * @param $cart_object
	 *
	 * @return void
	 */
	public function woocommerce_fixed_price_refresh( $cart_object ) {
		foreach ( $cart_object->get_cart() as $item ) {
			$product = wc_get_product( $item['product_id'] );

			if ( 'license' !== $product->get_type() && 'subscription' !== $product->get_type() ) {
				return;
			}

			$fixed_price = (string) get_post_meta( $item['product_id'], 'ulgm_license_fixed_price', true );
			if ( isset( $item['product_id'] ) && 'yes' === $fixed_price ) {
				$item['data']->set_price( floatval( get_post_meta( $item['product_id'], '_price', true ) ) / floatval( $item['quantity'] ) );
			}
		}
	}

	/**
	 * Check if fixed price for license is set or not.
	 *
	 * @param $product_id
	 *
	 * @return bool
	 */
	public static function is_fixed_price_set_for_the_license( $product_id ) {
		//'ulgm_license_fixed_price'
		$product = wc_get_product( $product_id );
		if ( 'license' !== $product->get_type() && 'subscription' !== $product->get_type() ) {
			return false;
		}
		$fixed_price = (string) get_post_meta( $product_id, 'ulgm_license_fixed_price', true );
		if ( ! empty( $fixed_price ) && 'yes' === $fixed_price ) {
			return true;
		}

		return false;
	}
}
