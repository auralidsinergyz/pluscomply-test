<?php

namespace uncanny_learndash_groups;

/**
 * Class Woocommerce_Handlers
 * @package uncanny_learndash_groups
 */
class Woocommerce_Handlers {
	/**
	 * @var
	 */
	public static $instance;

	/**
	 * @return Woocommerce_Handlers
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param $group_id
	 * @param null $product_id
	 *
	 * @return array|false
	 */
	public function get_group_leader_all_orders( $group_id, $product_id = null ) {
		if ( ! is_user_logged_in() ) {
			return false;
		}
		global $wpdb;
		if ( is_numeric( $product_id ) ) {
			$qry = $wpdb->prepare( "SELECT wpp.order_item_id as OrderPrimaryID,wpp.order_id as OrderID,
           								wpm.meta_value as OrderProductId,wpsot.post_author as OrderPostAuthor,
           								wpu.ID as OrderUserID,wpu.user_email as OrderUserEmail
									    FROM {$wpdb->prefix}woocommerce_order_items as wpp
									    INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as wpm ON wpp.order_item_id=wpm.order_item_id
									    INNER JOIN {$wpdb->posts} wpsot ON wpp.order_id=wpsot.ID
									    INNER JOIN {$wpdb->postmeta} wppostmeta ON wpp.order_id=wppostmeta.post_id
									    INNER JOIN {$wpdb->users} wpu ON wppostmeta.meta_value=wpu.ID
									    WHERE wpm.meta_key='_product_id' AND wppostmeta.meta_key='_customer_user'
									    AND wpu.ID = %d AND wpm.meta_value = %d", wp_get_current_user()->ID, $product_id );

			$results = $wpdb->get_results( $qry );
			if ( $results ) {
				$order_ids = array();
				foreach ( $results as $r ) {
					$order_ids[] = $r->OrderID;
				}

				return $order_ids;
			}

			return false;
		}

		$qry = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key LIKE %s AND meta_value = %d", SharedFunctions::$linked_group_id_meta, $group_id );

		$results = $wpdb->get_col( $qry );
		if ( $results ) {
			return $results;
		}


		return false;
	}

	/**
	 * @param $product
	 * @param false $initial
	 *
	 * @return float|mixed|string|void
	 */
	public function get_custom_product_price( $product, $initial = false ) {

		if ( $product instanceof \WC_Product && $product->is_type( 'courses' ) ) {
			if ( 'yes' === (string) get_option( 'woocommerce_calc_taxes' ) ) {
				$price = 'yes' === get_option( 'woocommerce_prices_include_tax' ) ? wc_get_price_including_tax( $product ) : wc_get_price_excluding_tax( $product );
			} else {
				$price = $product->get_price();
			}
			WoocommerceLicense::$product_price[ $product->get_id() ] = $price;

			return apply_filters( 'ulgm_get_course_price', $price, $product );
		}

		if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
			return SharedFunctions::get_license_price( $product, $initial );
		}

		return $product->get_price();

	}

	/**
	 * @param \WC_Product $product
	 * @param bool $initial
	 * @param int $current_price
	 *
	 * @return float|string
	 */
	public function get_license_price( \WC_Product $product, $initial = false, $current_price = 0 ) {
		$price  = 0;
		$custom = 0;
		if ( ! $product instanceof \WC_Product ) {
			return $price;
		}

		if ( ! $product->is_type( 'license' ) ) {
			return $product->get_price();
		}

		if ( ! $initial ) {
			if ( empty( $current_price ) || 0 === $current_price ) {
				$custom  = 0;
				$initial = true;
			} else {
				$price  = $current_price;
				$custom = 1;
			}
		}

		if ( 1 !== absint( $custom ) || true === $initial ) {
			$linked_courses = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
			$new_courses    = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field . '_new', true );
			if ( ! empty( $new_courses ) ) {
				$linked_courses = $new_courses;
			}
			if ( $linked_courses ) {
				foreach ( $linked_courses as $course ) {
					if ( false !== get_post_status( $course ) ) {
						$course_product = wc_get_product( $course );
						if ( $course_product ) {
							$price_wt = SharedFunctions::get_custom_product_price( $course_product );
							$price    = floatval( $price ) + floatval( $price_wt );
						}
					}
				}
			}
		}


		return apply_filters( 'ulgm_get_license_price', $price, $product );
	}

	/**
	 * @param $product_id
	 * @param bool $return_count
	 *
	 * @return array|int|object
	 */
	public function get_orders_from_product_id( $product_id, $return_count = true ) {
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT wpp.order_id as OrderID
										FROM {$wpdb->prefix}woocommerce_order_items as wpp
										    INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as wpm ON wpp.order_item_id=wpm.order_item_id
										    INNER JOIN {$wpdb->postmeta} wppostmeta ON wpp.order_id=wppostmeta.post_id
										    WHERE wpm.meta_key='_product_id' AND wpm.meta_value = %d", $product_id ) );

		if ( $results ) {
			return $return_count ? count( $results ) : $results;
		}

		return $return_count ? 0 : array();

	}

	/**
	 * @param null $group_id
	 *
	 * @return array
	 */
	public function get_product_id_from_group_id( $group_id = null ) {
		if ( ! is_user_logged_in() ) {
			return array();
		}
		if ( ! is_numeric( $group_id ) ) {
			return array();
		}

		global $wpdb;
		$results       = array();
		$code_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );
		if ( empty( $code_group_id ) ) {
			return array();
		}
		$results['codes_group_id'] = $code_group_id;
		if ( is_numeric( $code_group_id ) ) {
			$qry           = $wpdb->prepare( "SELECT order_id FROM {$wpdb->prefix}" . ulgm()->db->tbl_group_details . " WHERE ID = %d AND order_id IS NOT NULL", $code_group_id );
			$orders_id_arr = $wpdb->get_row( $qry );
			if ( $orders_id_arr ) {
				$results['order_id'] = $orders_id_arr->order_id;
			}
		}
		/**/

		if ( array_key_exists( 'order_id', $results ) ) {
			$order_id = (int) $results['order_id'];
		} else {
			$order_id = 0;
		}
		$product_id = 0;
		if ( function_exists( 'wc_get_order' ) ) {
			$order = wc_get_order( $order_id );
			if ( $order ) {
				$line_items = $order->get_items( 'line_item' );

				if ( $line_items ) {
					foreach ( $line_items as $line_item ) {
						$_product = $line_item->get_product();
						if ( SharedFunctions::is_group_licensed_product( $_product ) ) {
							$product_id = $line_item['product_id'];
						}
					}
				}
			}

			return array(
				'order_id'      => $order_id,
				'code_group_id' => $code_group_id,
				'product_id'    => $product_id,
			);
		}

	}
}
