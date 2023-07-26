<?php

/**
 * Class WC_Product_Group
 */
class WC_Product_Group extends \WC_Product {

	/**
	 * Initialize group product.
	 *
	 * @param mixed $product
	 */
	public function __construct( $product ) {
		$this->product_type = 'group';
		parent::__construct( $product );

	}

	/**
	 * Get internal type. Should return string and *should be overridden* by child classes.
	 *
	 * The product_type property is deprecated but is used here for BW compat with child classes which may be defining product_type and not have a get_type method.
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_type() {
		return 'group';
	}

	/**
	 * Get the add to cart button text
	 *
	 * @return string
	 */
	public function add_to_cart_text() {
		$text = $this->is_purchasable() ? __( 'Add to cart', 'woocommerce' ) : __( 'Read More', 'uncanny-learndash-groups' );

		return apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );
	}

	/**
	 * Set the add to cart button URL used on the /shop/ page
	 *
	 * @return string
	 * @since 1.3.1
	 */
	public function add_to_cart_url() {
		// Code copied from WP Simple Product function of same name
		$url = $this->is_purchasable() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->get_id() ) ) : get_permalink( $this->get_id() );

		return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
	}

	/**
	 * Get virtual.
	 *
	 * @param string $context
	 *
	 * @return bool
	 * @since 3.0.0
	 *
	 */
	public function get_virtual( $context = 'view' ) {
		return true;
	}

	/**
	 * Return if should be sold individually.
	 *
	 * @param string $context
	 *
	 * @return boolean
	 * @since 3.0.0
	 */
	public function get_sold_individually( $context = 'view' ) {
		return true;
	}


	/**
	 * Returns false if the product cannot be bought.
	 *
	 * @return bool
	 */
	public function is_purchasable() {
		return true;
	}

	/**
	 * Returns false if the product is taxable.
	 *
	 * @return bool
	 */
	public function is_taxable() {
		return 'taxable';
	}
}
