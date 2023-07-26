<?php

namespace uncanny_learndash_groups;

/**
 * Class to Swap product if quantity is 1
 *
 * @since 4.1.2
 */
class WoocommerceGroupLicenseSwapProducts {
	/**
	 * Construct
	 */
	public function __construct() {

		add_action(
			'woocommerce_product_options_general_product_data',
			array(
				$this,
				'backend_display_swap_product_field',
			),
			99
		);

		add_action(
			'woocommerce_process_product_meta',
			array(
				$this,
				'backend_process_product_meta',
			),
			99,
			2
		);

		add_filter(
			'woocommerce_add_to_cart_product_id',
			array(
				$this,
				'add_to_cart_product_id',
			),
			99
		);

		add_action(
			'woocommerce_after_cart_item_quantity_update',
			array(
				$this,
				'after_cart_item_quantity_update',
			),
			99,
			4
		);
	}

	/**
	 * If applicable, remove the product and add a new one  when the quantity is updated to 1
	 *
	 * @return void
	 */
	public function after_cart_item_quantity_update( $cart_item_key, $quantity, $old_quantity, $cart ) {

		if ( ! isset( $cart->cart_contents[ $cart_item_key ] ) ) {
			return;
		}

		$swappable_product_id = $this->is_swappable( $cart->cart_contents[ $cart_item_key ]['product_id'] );

		if ( 1 === $quantity && $swappable_product_id ) {
			// remove cart from the product.
			$cart->remove_cart_item( $cart_item_key );

			// swap license course product to configured product.
			$cart->add_to_cart( $swappable_product_id );
		}
	}

	/**
	 * Checks if a product is swappable
	 *
	 * @return int
	 */
	private function is_swappable( $product_id ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return 0;
		}

		if ( ! in_array( $product->get_type(), array( 'license', 'subscription' ), true ) ) {
			return 0;
		}

		$swap_license_product_id = absint( get_post_meta( $product_id, 'ulgm_swap_license_product', true ) );

		if ( $swap_license_product_id > 0 ) {
			$swap_adding_to_cart = wc_get_product( $swap_license_product_id );

			if ( ! $swap_adding_to_cart ) {
				return 0;
			}
		}

		return apply_filters( 'ulgm_is_swappable_product', $swap_license_product_id );
	}

	/**
	 * Product ID of the item added in to the cart
	 *
	 * @return void|int
	 */
	public function add_to_cart_product_id( $product_id ) {

		$adding_to_cart = wc_get_product( $product_id );
		if ( ! $adding_to_cart ) {
			return;
		}

		$quantity = ulgm_filter_has_var( 'quantity', INPUT_POST ) ? absint( wp_unslash( ulgm_filter_input( 'quantity', INPUT_POST ) ) ) : 0;

		if ( in_array( $adding_to_cart->get_type(), array( 'license', 'subscription' ), true ) && 1 === $quantity ) {
			$swap_license_product_id = get_post_meta( $product_id, 'ulgm_swap_license_product', true );

			if ( $swap_license_product_id > 0 ) {
				$swap_adding_to_cart = wc_get_product( $swap_license_product_id );

				if ( ! $swap_adding_to_cart ) {
					return;
				}

				$product_id = $swap_license_product_id;
			}
		}

		return $product_id;
	}

	/**
	 * Display swap dropdown on edit product screen
	 *
	 * @return void
	 */
	public function backend_display_swap_product_field() {
		global $woocommerce, $post;

		$types = wc_get_product_types();
		// exclude variable products
		if ( isset( $types['variable'] ) ) {
			unset( $types['variable'] );
		}

		$args = array(
			'type'   => apply_filters( 'ulgm_swap_product_types', array_keys( $types ), $types ),
			'status' => array( 'publish' ),
			'limit'  => '-1',
		);

		$current_value = 0;

		// exclude current product
		if ( is_object( $post ) && $post->ID ) {
			$args['exclude'] = array( $post->ID );
			$current_value   = get_post_meta( $post->ID, 'ulgm_swap_license_product', true );
		}

		$raw_products  = wc_get_products( $args );
		$products      = array();
		$products['0'] = __( 'Select a product', 'uncanny-learndash-groups' );

		if ( ! empty( $raw_products ) ) {
			foreach ( $raw_products as $raw_product ) {
				$products[ $raw_product->get_id() ] = $raw_product->get_name() . ' (#' . $raw_product->get_id() . ')';
			}
		}

		echo '<div id="ulgm_swap_license_product_wrapper" style="display: none;">';
		woocommerce_wp_select(
			array(
				'id'          => 'ulgm_swap_license_product',
				'class'       => 'select long select2',
				'label'       => __( 'Swap course product ', 'uncanny-learndash-groups' ),
				'selected'    => true,
				'value'       => $current_value,
				'options'     => apply_filters( 'ulgm_swap_products', $products, $post ),
				'desc_tip'    => true,
				'style'       => 'width: 50%"',
				'description' => __( 'If a quantity of 1 for this product is added to the cart, swap it for the selected product instead', 'uncanny-learndash-groups' ),
			)
		);
		echo '</div>';
	}

	/**
	 * Saves value to post meta
	 *
	 * @return void
	 */
	public function backend_process_product_meta( $id, $post ) {
		if ( ulgm_filter_has_var( 'ulgm_swap_license_product', INPUT_POST ) ) {
			update_post_meta( $id, 'ulgm_swap_license_product', absint( wp_unslash( ulgm_filter_input( 'ulgm_swap_license_product', INPUT_POST ) ) ) );
		}
	}
}
