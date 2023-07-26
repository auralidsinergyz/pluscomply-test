<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class WoocommerceModifyGroup
 *
 * @package uncanny_learndash_groups
 */
class WoocommerceModifyGroup {
	/**
	 * @var bool
	 */
	var $bulk_discount_calculated = false;
	/**
	 * @var
	 */
	var $discount_coeffs;
	/**
	 * @var bool
	 */
	var $is_being_modified = false;


	/**
	 * WoocommerceModifyGroup constructor.
	 */
	public function __construct() {
		// Only Run if woocommerce is available
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'admin_init', array( $this, 'remove_old_transients' ) );
	}

	/**
	 *
	 */
	public function plugins_loaded() {
		add_action( 'wp_loaded', array( $this, 'modify_group_seats_qty_v2' ), 500 );

		if ( is_user_logged_in() ) {
			//add_action( 'woocommerce_cart_calculate_fees', array( $this, 'calculate_cart_v2' ), 999, 1 );
			add_action( 'woocommerce_before_cart_contents', array( $this, 'add_qty_notice' ), 11, 1 );
			add_action( 'woocommerce_checkout_before_order_review', array( $this, 'add_qty_notice' ), 11, 1 );
		}

		add_action( 'woocommerce_checkout_order_processed', array( $this, 'order_processed' ), 99, 3 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'modify_existing_group' ), 599 );

		global $pagenow;
		if ( 'user-edit.php' === $pagenow || 'profile.php' === $pagenow ) {
			add_action( 'ld_added_group_access', array( $this, 'ld_added_group_access_edit_user_func' ), 99, 2 );
			add_action( 'admin_footer', array( $this, 'ld_edit_user_groups_warning' ) );
		}
		add_action( 'ld_removed_group_access', array( $this, 'ld_removed_group_access_edit_user_func' ), 99, 2 );
	}

	/**
	 * @param $order_id
	 * @param $posted_data
	 * @param $order
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 *
	 * @version 3.7
	 * Delete transients and store its data in the order itself for future usage
	 */
	public function order_processed( $order_id, $posted_data, $order ) {
		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		$user_id       = $order->get_user_id();
		$key           = "_ulgm_user_{$user_id}_order";
		$get_transient = SharedFunctions::get_transient_cache( $key, $user_id );
		if ( $get_transient ) {
			$get_transient['linked_order'] = $order_id;
			update_post_meta( $order_id, $key, maybe_unserialize( $get_transient ) );
			SharedFunctions::remove_transient_cache( 'no', $key );
		}

		$key           = "_ulgm_user_buy_courses_{$user_id}_order";
		$get_transient = SharedFunctions::get_transient_cache( $key, $user_id );

		if ( $get_transient ) {
			$get_transient['linked_order'] = $order_id;
			update_post_meta( $order_id, $key, maybe_unserialize( $get_transient ) );
			SharedFunctions::remove_transient_cache( 'no', $key );
			//rename new courses meta key in product to prevent purchased courses
			$product_id  = absint( $get_transient['order_details']['product_id'] );
			$new_courses = get_post_meta( $product_id, SharedFunctions::$license_meta_field . '_new', true );
			if ( $new_courses ) {
				delete_post_meta( $product_id, SharedFunctions::$license_meta_field . '_new' );
				update_post_meta( $product_id, SharedFunctions::$license_meta_field . '_paid', $new_courses );
				update_post_meta( $product_id, '_last_paid', time() );
				delete_post_meta( $product_id, '_last_modified' );
			}
		}
	}

	/**
	 * @param $order
	 *
	 * @return array|false[]
	 */
	public function check_if_license_product_in_order( $order ) {
		$items  = $order->get_items();
		$return = array( 'status' => false );
		if ( $items ) {
			/** @var \WC_Order_Item_Product $item */
			foreach ( $items as $item ) {
				$product = $item->get_product();
				//$product = wc_get_product( $pid );
				if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
					$return = array(
						'status'     => true,
						'product_id' => $product->get_id(),
					);
					break;
				}
			}
		}

		return $return;
	}

	/**
	 *
	 */
	public function modify_group_seats_qty_v2() {
		if ( ! ulgm_filter_has_var( 'modify-group' ) ) {
			return;
		}
		if ( 'true' !== (string) ulgm_filter_input( 'modify-group' ) ) {
			return;
		}
		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( 0 !== WC()->cart->get_cart_contents_count() ) {
			$cart_contents = WC()->cart->cart_contents;
			if ( $cart_contents ) {
				/** @var WoocommerceCourses $woocommerce_courses_class */
				$woocommerce_courses_class = Load_Groups::$class_instances['WoocommerceCourses'];
				$data                      = $woocommerce_courses_class->if_course_license_product_type_in_cart( $cart_contents );
				if ( ! $data['passed'] ) {
					wc_add_notice( sprintf( __( '%1$s licenses cannot be purchased in the same transaction as other products. %2$s was removed from your cart.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ), get_the_title( $data['product_id'] ) ), 'error' ); //phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
					$user_id = wp_get_current_user()->ID;
					SharedFunctions::remove_transient_cache( 'no', '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id );
					SharedFunctions::remove_transient_cache( 'no', '_ulgm_user_' . $user_id . '_order', $user_id );
					if ( is_array( $data['item_key'] ) ) {
						foreach ( $data['item_key'] as $k ) {
							\WC()->cart->remove_cart_item( $k );
						}
					}
				}
			}
		}

		$group_id      = (int) ulgm_filter_input( 'modify-group-id' );
		$existing_qty  = ulgm()->group_management->seat->total_seats( $group_id );
		$order_details = SharedFunctions::get_product_id_from_group_id( $group_id );
		$all_orders    = SharedFunctions::get_group_leader_all_orders( $group_id, $order_details['product_id'] );

		if ( ! is_array( $order_details ) ) {
			return;
		}

		$user_id = wp_get_current_user()->ID;

		$transient = SharedFunctions::get_transient_cache( '_ulgm_user_' . $user_id . '_order', $user_id );
		$this->move_and_remove_cache( $transient, '_ulgm_user_' . $user_id . '_order', $user_id );
		$transient = SharedFunctions::get_transient_cache( '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id );
		$this->move_and_remove_cache( $transient, '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id );

		$order_id   = $order_details['order_id'];
		$product_id = $order_details['product_id'];

		$fixed_price = (string) get_post_meta( $product_id, 'ulgm_license_fixed_price', true );
		if ( isset( $product_id ) && 'yes' === $fixed_price ) {
			$qty = get_post_meta( $product_id, 'ulgm_license_min_qty', true );
		} else {
			$qty = intval( ulgm_filter_input( 'new-qty' ) );
		}

		$save_data = array(
			'user_id'       => $user_id,
			'order_details' => $order_details,
			'group_id'      => $group_id,
			'order_id'      => $order_id,
			'all_orders'    => $all_orders,
			'existing_qty'  => $existing_qty,
			'new_qty'       => $qty,
		);
		if ( ulgm_filter_has_var( 'is_subscription_group' ) && 'yes' === ulgm_filter_input( 'is_subscription_group' ) ) {
			$save_data['is_subscription_group'] = 'yes';
		}

		if ( true === apply_filters( 'ulgm_force_calculate_license_price', false, $product_id ) ) {
			$product = wc_get_product( $product_id );
			if ( $product instanceof \WC_Product ) {
				$price = apply_filters( 'ulgm_force_new_license_price', SharedFunctions::get_custom_product_price( $product, true ), $product );
				$product->set_price( $price );
				$product->set_regular_price( $price );
				$product->save();
				WoocommerceBuyCourses::clear_caches( $product );
			}
		}

		SharedFunctions::remove_transient_cache( 'no', '_ulgm_user_buy_courses_USERID_order', $user_id );
		SharedFunctions::set_transient_cache( '_ulgm_user_USERID_order', $save_data, $user_id, $group_id );

		$this->reset_license_on_cart_empty( $product_id );

		wp_safe_redirect( wc_get_cart_url() . '?add-to-cart=' . $product_id . '&quantity=' . $qty . '&add-seats=true' );
		exit;
	}

	/**
	 * @param $transient
	 * @param $key
	 * @param $user_id
	 */
	public function move_and_remove_cache( $transient, $key, $user_id ) {
		if ( $transient ) {
			if ( isset( $transient['linked_order'] ) ) {
				$order_id = absint( $transient['linked_order'] );
				if ( is_numeric( $order_id ) ) {
					$order = wc_get_order( $order_id );
					if ( $order instanceof \WC_Order ) {
						update_post_meta( $order_id, $key, $transient );
					}
				}
			} elseif ( isset( $transient['group_id'] ) ) {
				$group_id       = absint( $transient['group_id'] );
				$trans_order_id = absint( $transient['order_id'] );
				$orders         = get_posts(
					array(
						'posts_per_page' => 999,
						'post_type'      => 'shop_order',
						'post_status'    => 'any',
						'orderby'        => 'date',
						'order'          => 'DESC',
						'meta_query'     => array(
							array(
								'key'     => SharedFunctions::$linked_group_id_meta,
								'value'   => $group_id,
								'compare' => '=',
							),
						),
					)
				);
				if ( $orders ) {
					foreach ( $orders as $order ) {
						$order_id = $order->ID;
						if ( absint( $order_id ) === absint( $trans_order_id ) ) {
							continue;
						}

						$order = wc_get_order( $order_id );

						if ( ! $order instanceof \WC_Order ) {
							continue;
						}

						$has_license = $this->check_if_license_product_in_order( $order );
						if ( false === $has_license['status'] ) {
							continue;
						}

						if ( absint( $has_license['product_id'] ) !== absint( $transient['product_id'] ) ) {
							continue;
						}

						if ( 'completed' === $order->get_status() ) {
							continue;
						}
						update_post_meta( $order_id, $key, $transient );
					}
				}
			}
		}
		SharedFunctions::remove_transient_cache( 'no', $key, $user_id );
	}

	/**
	 *
	 */
	public function add_qty_notice() {
		$user_id       = wp_get_current_user()->ID;
		$get_transient = SharedFunctions::get_transient_cache( '_ulgm_user_' . $user_id . '_order', $user_id );
		if ( $get_transient && ! is_checkout() ) {

			$seats           = strtolower( get_option( 'ulgm_per_seat_text_plural', __( 'Seats', 'uncanny-learndash-groups' ) ) );
			$message         = sprintf(
				esc_attr__( 'Add additional %s by changing quantity below.', 'uncanny-learndash-groups' ),
				$seats
			);
			$learn_more_link = apply_filters( 'ulgm_subscription_additional_seats_learn_more_link', get_option( 'woo_subscription_allow_additional_seats_learn_more_link', '' ), array() );
			if ( isset( $get_transient['is_subscription_group'] ) ) {
				$message = sprintf(
					esc_attr__( 'Add additional %1$s by increasing the quantity below. A new subscription will be created when you complete the order with additional %2$s.', 'uncanny-learndash-groups' ),
					$seats,
					$seats
				);
				if ( ! empty( $learn_more_link ) ) {
					$message = sprintf( '%s <a href="%s" target="_blank">%s</a>', $message, $learn_more_link, apply_filters( 'ulgm_subscription_additional_seats_learn_more', __( 'Learn more', 'uncanny-learndash-groups' ) ) );
				}
			}
			echo sprintf(
				'<p class="woocommerce-message">%s</p>',
				$message
			);
		}
	}

	/**
	 * @param $order_id
	 */
	public function modify_existing_group( $order_id ) {
		$order     = wc_get_order( $order_id );
		$user_id   = $order->get_user_id();
		$transient = maybe_unserialize( get_post_meta( $order_id, '_ulgm_user_' . $user_id . '_order', true ) );

		if ( ! $transient ) {
			//Fall back < 3.7 Groups
			$transient2 = SharedFunctions::get_transient_cache( '_ulgm_user_' . $user_id . '_order', $user_id );
			if ( ! $transient2 ) {
				return;
			} else {
				$transient = $transient2;
			}
		}
		if ( empty( $transient ) ) {
			return;
		}

		$line_items = $order->get_items( 'line_item' );
		$new_qty    = 0;

		if ( 'completed' !== $order->get_status() ) {
			return;
		}

		foreach ( $line_items as $line_item ) {
			$new_qty += absint( $line_item['qty'] );
		}
		$group_id     = $transient['group_id'];
		$existing_qty = $transient['existing_qty'];

		$old_order_id  = $transient['order_details']['order_id'];
		$code_group_id = $transient['order_details']['code_group_id'];
		$generate_qty  = $new_qty;
		$new_codes     = ulgm()->group_management->generate_random_codes( $generate_qty );
		$attr          = array(
			'qty'           => $generate_qty,
			'code_group_id' => $code_group_id,
		);
		ulgm()->group_management->add_additional_codes( $attr, $new_codes );
		$updated_qty = absint( $new_qty ) + absint( $existing_qty );
		update_post_meta( $order_id, SharedFunctions::$code_group_id_meta_key, $code_group_id );
		update_post_meta( $order_id, 'parent_order_id', $old_order_id );
		update_post_meta( $order_id, 'order_type', sprintf( _x( 'Additional %1$s %2$s purchased.', 'Group seats count and per seat label', 'uncanny-learndash-groups' ), $new_qty, strtolower( SharedFunctions::get_per_seat_text( $new_qty ) ) ) );
		update_post_meta( $group_id, '_ulgm_total_seats', $updated_qty );
		update_post_meta( $order_id, SharedFunctions::$linked_group_id_meta, $group_id );
		if ( isset( $transient['is_subscription_group'] ) ) {
			add_post_meta( $group_id, 'subscription_additional_seats_order', $order_id );
			update_post_meta( $group_id, 'subscription_additional_seats_order_' . $order_id, $order_id );
		}
		SharedFunctions::remove_transient_cache( 'no', '_ulgm_user_USERID_order', $user_id );
	}

	/**
	 * @param $product_id
	 */
	public function reset_license_on_cart_empty( $product_id ) {
		delete_post_meta( $product_id, SharedFunctions::$license_meta_field . '_new' );
	}

	/**
	 *
	 */
	public function ld_edit_groups_warning() {
		global $typenow;

		if ( ( empty( $typenow ) ) || ( $typenow != 'groups' ) ) {
			return;
		}

		// Value can be saved on settings page
		$per_seat_text        = strtolower( get_option( 'ulgm_per_seat_text', __( 'Seat', 'uncanny-learndash-groups' ) ) );
		$per_seat_text_plural = strtolower( get_option( 'ulgm_per_seat_text_plural', __( 'Seats', 'uncanny-learndash-groups' ) ) );

		printf(
			"<script>
            jQuery('div.learndash_group_users .learndash-binary-selector-button-add').click(function () {
                confirm('" . __( 'Adding users will automatically assign a %1$s for each new user. Additional %2$s will be added if number of users exceeds remaining %3$s.', 'uncanny-learndash-groups' ) . "');
            });

            jQuery('div.learndash_group_users .learndash-binary-selector-button-remove').click(function () {
                confirm('" . __( 'Removing users will automatically increase remaining %s.', 'uncanny-learndash-groups' ) . "');
            });
        </script>",
			$per_seat_text,
			$per_seat_text_plural,
			$per_seat_text_plural,
			$per_seat_text_plural
		);
	}


	/**
	 * @param $user_id
	 * @param $group_id
	 */
	public function ld_added_group_access_edit_user_func( $user_id, $group_id ) {

		$order_id       = SharedFunctions::get_order_id_from_group_id( $group_id );
		$remaining      = ulgm()->group_management->seat->remaining_seats( $group_id );
		$codes_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );

		//There are no remaining seats, add 1 seat and save it before using a code
		if ( $remaining < 1 ) {

			$random_code = ulgm()->group_management->generate_random_codes( 1 );
			$attr        = array(
				'qty'           => 1,
				'code_group_id' => $codes_group_id,
			);
			ulgm()->group_management->add_additional_codes( $attr, $random_code );
			$updated_qty = 1 + ulgm()->group_management->seat->total_seats( $group_id );
			update_post_meta( $group_id, '_ulgm_total_seats', $updated_qty );
			$code = array_pop( $random_code );

		} else {
			$code           = SharedFunctions::get_sign_up_code_from_group_id( $group_id, 1, $user_id, $order_id, false );
			$codes_group_id = SharedFunctions::get_codes_group_id_from_code( $code );
		}

		if ( ! $code ) {
			return;
		}

		SharedFunctions::set_sign_up_code_status( $code, $user_id, $order_id, SharedFunctions::$redeem_status, $codes_group_id );

		$user      = get_user_by( 'ID', $user_id );
		$user_data = array(
			'user_email' => $user->user_email,
			'user_id'    => $user_id,
		);

		do_action( 'ulgm_existing_group_user_added', $user_data, $group_id, $order_id );
	}

	/**
	 * @param $user_id
	 * @param $group_id
	 */
	public function ld_removed_group_access_edit_user_func( $user_id, $group_id ) {
		// Check permissions.
		if ( $user_id ) {
			$existing_code = ulgm()->group_management->get_user_code( $user_id, $group_id );
			// Remove Temp users
			if ( ! empty( $existing_code ) ) {
				ulgm()->group_management->remove_sign_up_code( $existing_code, $group_id, true );
			}
			//ld_update_group_access( $user_id, $group_id, true );
			do_action( 'uo-groups-role-cleanup', $user_id ); // remove the group membership role if no longer a member of any groups
		}
	}

	/**
	 *
	 */
	public function ld_edit_user_groups_warning() {
		// Value can be saved on settings page
		$per_seat_text        = strtolower( get_option( 'ulgm_per_seat_text', __( 'Seat', 'uncanny-learndash-groups' ) ) );
		$per_seat_text_plural = strtolower( get_option( 'ulgm_per_seat_text_plural', __( 'Seats', 'uncanny-learndash-groups' ) ) );

		printf(
			"<script>
            jQuery('div.learndash_user_groups .learndash-binary-selector-button-add').click(function () {
                confirm('" . __( 'Adding user to this group will automatically adjust %1$s count. Additional %2$s will be added if number of users exceeds remaining %3$s.', 'uncanny-learndash-groups' ) . "');
            });

            jQuery('div.learndash_user_groups .learndash-binary-selector-button-remove').click(function () {
                confirm('" . __( 'Removing user will automatically increase remaining %s of the group.', 'uncanny-learndash-groups' ) . "');
            });
        </script>",
			$per_seat_text,
			$per_seat_text,
			$per_seat_text_plural,
			$per_seat_text_plural
		);
	}

	/**
	 *
	 */
	public function remove_old_transients() {
		if ( 0 === get_option( 'ulgm_transients_removed', 0 ) ) {
			global $wpdb;
			$qry        = "SELECT option_id, option_name, option_value
					FROM $wpdb->options
					WHERE option_name LIKE '_transient__ulgm_user_%_order_%' OR
					option_name LIKE '_transient__ulgm_user_buy_courses_%_order'";
			$transients = $wpdb->get_results( $qry );
			if ( $transients ) {
				foreach ( $transients as $transient ) {
					$option_name = $transient->option_name;
					if ( strpos( $option_name, 'buy_courses' ) ) {
						//Buy courses
						$user_id    = (int) str_replace(
							array(
								'_transient__ulgm_user_buy_courses_',
								'_order',
							),
							'',
							$option_name
						);
						$order_id   = null;
						$last_order = wc_get_customer_last_order( $user_id );
						if ( $last_order ) {
							$order_id = $last_order->get_id();
							$items    = $last_order->get_items();
							$continue = false;
							if ( $items ) {
								/** @var \WC_Order_Item $item */
								foreach ( $items as $item ) {
									$product_id = $item['product_id'];
									if ( wc_get_product( $product_id )->is_type( 'license' ) ) {
										//Found product;
										$continue = true;
										break;
									}
								}
							}

							if ( $continue ) {
								if ( ! empty( $user_id ) && ! empty( $order_id ) ) {
									$key = "_ulgm_user_buy_courses_{$user_id}_order";
									update_post_meta( $order_id, $key, maybe_unserialize( $transient->option_value ) );
								}
								delete_transient( $key );
							}
						}
					} else {
						$string   = str_replace( array( '_transient__ulgm_user_', '_order' ), '', $option_name );
						$string   = explode( '_', $string );
						$user_id  = absint( $string[0] );
						$order_id = absint( $string[1] );
						if ( ! empty( $user_id ) && ! empty( $order_id ) ) {
							$key = "_ulgm_user_{$user_id}_order";
							update_post_meta( $order_id, $key, maybe_unserialize( $transient->option_value ) );
						}
						delete_transient( "{$key}_{$order_id}" );
					}
				}
				update_option( 'ulgm_transients_removed', time() );
			}
		}
	}
}
