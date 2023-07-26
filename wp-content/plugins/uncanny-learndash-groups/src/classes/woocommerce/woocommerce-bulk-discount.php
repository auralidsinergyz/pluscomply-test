<?php

namespace uncanny_learndash_groups;

/**
 * Class WoocommerceBulkDiscount
 *
 * @package uncanny_learndash_groups
 */
class WoocommerceBulkDiscount {
	/**
	 * WoocommerceBulkDiscount constructor.
	 */
	public $bulk_discount_calculated = false;
	/**
	 * @var array
	 */
	public $discount_coeffs = array();

	/**
	 * WoocommerceBulkDiscount constructor.
	 */
	public function __construct() {

		// Only Run if woocommerce is available
		add_action( 'wp_loaded', array( $this, 'save_bulk_discount' ), 111 );
		add_action( 'admin_menu', array( $this, 'create_bulk_discount_menu' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'calculate_cart_discounts_v2' ), 1333, 1 );
		add_filter(
			'woocommerce_subscriptions_is_recurring_fee',
			array(
				$this,
				'woocommerce_subscriptions_is_recurring_fee_func',
			),
			99,
			3
		);

		add_filter(
			'woocommerce_paypal_payments_purchase_unit_from_wc_order',
			array(
				$this,
				'modify_paypal_checkout_negative_value',
			),
			99,
			2
		);

		add_filter( 'ppcp_request_args', array( $this, 'ppcp_request_args_func' ), 99, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'uo_group_bulk_discount_courses' ), 99 );
		add_shortcode( 'ulgm_bulk_discount_table', array( $this, 'ulgm_bulk_discount_table_func' ) );
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function ulgm_bulk_discount_table_func( $atts ) {
		$atts = shortcode_atts( array(), $atts, 'ulgm_bulk_discount_table' );
		if ( ! $this->is_bulk_discount_enabled() ) {
			return '';
		}
		ob_start();
		?>
		<style>
			#uo-groups-buy--group-data .uo-groups--bulk-discount {
				width: auto;
				margin: 0;
			}
		</style>
		<div id="uo-groups-buy-courses">
			<div id="uo-groups-buy--group-data" class="uo-groups-section">
				<div id="uo-groups-buy-courses-data" class="uo-row">
					<div class="uo-groups-section-content">
						<?php include Utilities::get_template( 'frontend-bulk-discount-table.php' ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 *
	 */
	public function uo_group_bulk_discount_courses() {
		global $post;

		if ( Utilities::has_shortcode( $post, 'ulgm_bulk_discount_table' ) && class_exists( '\uncanny_learndash_groups\WoocommerceBuyCourses' ) ) {
			WoocommerceBuyCourses::enqueue_frontend_assets();
		}
	}

	/**
	 * @param $args
	 * @param $url
	 *
	 * @return mixed
	 */
	public function ppcp_request_args_func( $args, $url ) {
		if ( ! $this->is_bulk_discount_enabled() ) {
			return $args;
		}
		if ( empty( $args ) ) {
			return $args;
		}
		if ( ! isset( $args['body'] ) ) {
			return $args;
		}
		if ( empty( $args['body'] ) ) {
			return $args;
		}

		$body = json_decode( $args['body'] );
		if ( ! isset( $body->purchase_units ) ) {
			return $args;
		}

		$discounted_price = 0;
		foreach ( $body->purchase_units as $k => $purchase_unit ) {
			if ( empty( $purchase_unit->items ) ) {
				continue;
			}
			foreach ( $purchase_unit->items as $kk => $item ) {
				if ( stripos( $item->name, 'bulk discount' ) ) {
					$discounted_price = $body->purchase_units[ $k ]->items[ $kk ]->unit_amount->value;
					unset( $body->purchase_units[ $k ]->items[ $kk ] );
					break;
				}
			}

			foreach ( $purchase_unit->items as $kk => $item ) {
				$unit_price = $body->purchase_units[ $k ]->items[ $kk ]->unit_amount->value;
				if ( $unit_price < 1 ) {
					continue;
				}
				$qty          = $body->purchase_units[ $k ]->items[ $kk ]->quantity;
				$actual_price = $unit_price + ( $discounted_price / $qty );

				$body->purchase_units[ $k ]->items[ $kk ]->unit_amount->value = number_format( $actual_price, 2 );
			}
		}

		$args['body'] = json_encode( $body );

		return $args;
	}

	/**
	 * @param $purchase_unit
	 * @param $order
	 *
	 * @return mixed|\WooCommerce\PayPalCommerce\ApiClient\Entity\PurchaseUnit
	 */
	public function modify_paypal_checkout_negative_value( $purchase_unit, $order ) {
		if ( ! $this->is_bulk_discount_enabled() ) {
			return $purchase_unit;
		}

		$new_units = array();
		if ( $purchase_unit->items() ) {
			/**
			 * @var \WooCommerce\PayPalCommerce\ApiClient\Entity\Item $item
			 */
			foreach ( $purchase_unit->items() as $item ) {
				if ( stripos( $item->name(), 'bulk discount' ) ) {
					continue;
				}
				$new_units[] = $item;

			}
			if ( ! empty( $new_units ) ) {
				return new \WooCommerce\PayPalCommerce\ApiClient\Entity\PurchaseUnit(
					$purchase_unit->amount(),
					$new_units,
					$purchase_unit->shipping(),
					$purchase_unit->reference_id(),
					$purchase_unit->description(),
					$purchase_unit->payee(),
					$purchase_unit->custom_id(),
					$purchase_unit->invoice_id(),
					$purchase_unit->soft_descriptor()
				);
			}
		}

		return $purchase_unit;
	}

	/**
	 * Apply discount to recurring subscription
	 *
	 * @param $current
	 * @param $fee
	 * @param $cart
	 *
	 * @return bool|mixed
	 */
	public function woocommerce_subscriptions_is_recurring_fee_func( $current, $fee, $cart ) {
		if ( ! \WC_Subscriptions_Cart::cart_contains_subscription() ) {
			return $current;
		}
		if ( isset( $fee->name ) && strpos( $fee->name, 'bulk discount' ) && $this->maybe_apply_recurring_discount( $cart ) ) {
			return apply_filters( 'ulgm_recurring_bulk_discount', true, $current, $fee, $cart );
		}

		return $current;
	}

	/**
	 * @param \WC_Cart $cart
	 *
	 * @return bool
	 */
	public function maybe_apply_recurring_discount( \WC_Cart $cart ) {
		if ( ! $this->is_bulk_discount_enabled() ) {
			return false;
		}
		$this->gather_discount_coeffs();
		if ( ! isset( $cart->cart_contents ) || 0 === count( $cart->cart_contents ) ) {
			return false;
		}

		foreach ( $cart->cart_contents as $values ) {
			$_product = $values['data'];
			if ( ! array_key_exists( absint( $_product->get_id() ), $this->discount_coeffs ) ) {
				return false;
			}

			$coeff = $this->discount_coeffs[ absint( $_product->get_id() ) ]['coeff'];

			if ( \WC_Subscriptions_Product::is_subscription( $_product->get_id() ) ) {
				if ( 1 == $coeff ) {
					return false;
				}

				return true;
			}
		}

		return false;
	}

	/**
	 *
	 */
	public function create_bulk_discount_menu() {
		$capability   = 'manage_options';
		$menu_slug    = 'uncanny-groups-create-group';
		$submenu_slug = 'uncanny-learndash-groups-bulk-discount';
		add_submenu_page(
			$menu_slug,
			__( 'Bulk discount', 'uncanny-learndash-groups' ),
			__( 'Bulk discount', 'uncanny-learndash-groups' ),
			$capability,
			$submenu_slug,
			array(
				$this,
				'bulk_discount_func',
			)
		);

	}

	/**
	 *
	 */
	public function bulk_discount_func() {
		include Utilities::get_template( 'admin/admin-bulk-discounts.php' );

	}

	/**
	 * @param $hook
	 */
	public function admin_scripts( $hook ) {
		if ( strpos( $hook, 'uncanny-learndash-groups-bulk-discount' ) || SharedFunctions::load_backend_bundles() ) {
			wp_enqueue_script( 'ulgm-backend', Utilities::get_asset( 'backend', 'bundle.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
			wp_enqueue_style( 'ulgm-backend', Utilities::get_asset( 'backend', 'bundle.min.css' ), array(), Utilities::get_version() );
		}
	}

	/**
	 *
	 */
	public function save_bulk_discount() {
		if ( ulgm_filter_has_var( '_ulgm_bulk_nonce', INPUT_POST ) && wp_verify_nonce( ulgm_filter_input( '_ulgm_bulk_nonce', INPUT_POST ), Utilities::get_plugin_name() ) ) {
			if ( ulgm_filter_has_var( 'action', INPUT_POST ) && 'save-bulk-discount' === ulgm_filter_input( 'action', INPUT_POST ) ) {

				$bulk_discount = array();
				if ( ulgm_filter_has_var( 'ulgm-enable-bulk-discount', INPUT_POST ) && 'on' === ulgm_filter_input( 'ulgm-enable-bulk-discount', INPUT_POST ) ) {
					$bulk_discount['enabled'] = 'yes';
				} else {
					$bulk_discount['enabled'] = 'no';
				}

				for ( $i = 1; $i <= 10; $i ++ ) {
					if ( isset( $_POST[ 'ulgm_bulk_discount_quantity_' . $i ] ) && ! empty( $_POST[ 'ulgm_bulk_discount_quantity_' . $i ] ) ) {
						$bulk_discount['discounts'][ $i ]['qty'] = $_POST[ 'ulgm_bulk_discount_quantity_' . $i ];
					}
					if ( isset( $_POST[ '_ulgm_bulk_discount_value_' . $i ] ) && ! empty( $_POST[ '_ulgm_bulk_discount_value_' . $i ] ) ) {
						$bulk_discount['discounts'][ $i ]['percent'] = $_POST[ '_ulgm_bulk_discount_value_' . $i ];
					}
				}
				update_option( SharedFunctions::$bulk_discount_options, $bulk_discount );
			}
			wp_safe_redirect( ulgm_filter_input( '_wp_http_referer', INPUT_POST ) );
			exit;
		}
	}

	/**
	 * Gather discount information to the array $this->discount_coefs
	 */
	protected function gather_discount_coeffs() {
		//
		$quantities = array();
		$quantity   = WC()->cart->get_cart_item_quantities();

		////
		$user_id       = wp_get_current_user()->ID;
		$get_transient = SharedFunctions::get_transient_cache( '_ulgm_user_USERID_order', $user_id );
		if ( is_user_logged_in() && ! empty( $get_transient ) ) {
			$product = wc_get_product( absint( $get_transient['order_details']['product_id'] ) );
			if ( SharedFunctions::is_group_licensed_product( $product ) ) {
				$quantities[ $product->get_id() ][] = absint( $get_transient['existing_qty'] );
			}
		}

		if ( $quantity ) {
			foreach ( $quantity as $product_id => $qty ) {
				$product = wc_get_product( $product_id );
				if ( SharedFunctions::is_group_licensed_product( $product ) ) {
					$quantities[ $product_id ][] = $qty;
				}
			}
		}

		foreach ( $quantities as $product_id => $qty ) {
			if ( 0 !== absint( $product_id ) && ! array_key_exists( $product_id, $this->discount_coeffs ) ) {
				$total_qty                                          = absint( array_sum( $quantities[ $product_id ] ) );
				$this->discount_coeffs[ $product_id ]['coeff']      = $this->get_discounted_coeff( $total_qty );
				$this->discount_coeffs[ $product_id ]['orig_price'] = SharedFunctions::get_custom_product_price( wc_get_product( $product_id ) );
				$this->discount_coeffs[ $product_id ]['quantity']   = $total_qty;
			}
		}
	}


	/**
	 * For given product, and quantity return the price modifying factor (percentage discount) or value to deduct (flat & fixed discounts).
	 *
	 * @param $product_id
	 * @param $quantity
	 *
	 * @return float
	 */
	protected function get_discounted_coeff( $quantity ) {
		if ( ! $this->is_bulk_discount_enabled() ) {
			return 1.0;
		}

		$q = array( 0.0 );
		$d = array( 0.0 );

		/* Find the appropriate discount coefficient by looping through up to the three discount settings */
		$bulk_discounts      = get_option( SharedFunctions::$bulk_discount_options, array() );
		$available_discounts = array();
		if ( isset( $bulk_discounts['discounts'] ) && is_array( $bulk_discounts['discounts'] ) ) {
			$available_discounts = $bulk_discounts['discounts'];
		}
		for ( $i = 1; $i <= 10; $i ++ ) {
			if ( key_exists( $i, $available_discounts ) ) {
				array_push( $q, $available_discounts[ $i ]['qty'] );
				array_push( $d, $available_discounts[ $i ]['percent'] ? $available_discounts[ $i ]['percent'] : 0.0 );

				if ( $quantity >= $q[ $i ] && $q[ $i ] > $q[0] ) {
					$q[0] = $q[ $i ];
					$d[0] = $d[ $i ];
				}
			}
		}

		return min( 1.0, max( 0, ( 100.0 - round( $d[0], 2 ) ) / 100.0 ) );
	}

	/**
	 *
	 * @param \WC_Cart $cart
	 */
	public function calculate_cart_discounts_v2( \WC_Cart $cart ) {
		if ( ! $this->is_bulk_discount_enabled() ) {
			return;
		}
		$this->gather_discount_coeffs();
		if ( ! isset( $cart->cart_contents ) || 0 === count( $cart->cart_contents ) ) {
			return;
		}
		foreach ( $cart->cart_contents as $values ) {
			$_product = $values['data'];
			if (
					WoocommerceMinMaxQuantity::is_fixed_price_set_for_the_license( $_product->get_id() ) &&
					false === apply_filters( 'ulgm_apply_bulk_discount_on_fixed_price', false, $_product->get_id() )
			) {
				continue;
			}
			$_price       = $_product->get_price();
			$subtract_fee = 0;
			if ( ! array_key_exists( absint( $_product->get_id() ), $this->discount_coeffs ) ) {
				continue;
			}
			$coeff = $this->discount_coeffs[ absint( $_product->get_id() ) ]['coeff'];
			WC()->cart->fees_api()->remove_all_fees();

			$apply_coeff = 0;

			if ( SharedFunctions::is_group_licensed_product( $_product ) ) {
				$_quantity   = $values['quantity'];
				$_price      = floatval( $_price ) * absint( $_quantity );
				$apply_coeff = 1;
			}

			if ( 1 == $coeff ) {
				return;
			}
			if ( 1 === $apply_coeff ) {
				$fee_name           = sprintf( __( '%s%% bulk discount', 'uncanny-learndash-groups' ), ( 1 - $coeff ) * 100 );
				$discount           = ( ( $_price + $subtract_fee ) * ( 1 - $coeff ) ) * - 1;
				$fee_to_add         = new \stdClass();
				$fee_to_add->amount = $discount;
				$fee_to_add->total  = $discount;
				$fee_to_add->name   = $fee_name;
				WC()->cart->fees_api()->add_fee( $fee_to_add );
				// Remove recurring totals and add only relevant bulk discount
				if ( ! empty( $cart->recurring_cart_key ) ) {
					$cart->fees_api()->remove_all_fees();
					$cart->fees_api()->add_fee( $fee_to_add );
				}
			}
		}
		$this->bulk_discount_calculated = true;
	}

	/**
	 * @return bool
	 */
	public function is_bulk_discount_enabled() {
		$bulk_discounts = get_option( SharedFunctions::$bulk_discount_options, array() );
		if ( empty( $bulk_discounts ) ) {
			return false;
		}
		if ( 'yes' !== $bulk_discounts['enabled'] ) {
			return false;
		}

		return true;
	}
}
