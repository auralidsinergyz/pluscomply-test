<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class WoocommerceLicenseSubscription
 *
 * @package uncanny_learndash_groups
 */
class WoocommerceLicenseSubscription {
	/**
	 * WoocommerceLicenseSubscription constructor.
	 */
	public function __construct() {

		// Only Run if woocommerce is available
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 21 );
	}

	/**
	 *
	 */
	public function plugins_loaded() {
		//Saving course option field - Simple Subscription
		add_action( 'woocommerce_process_product_meta_subscription', array( $this, 'save_courses_option_field' ) );
		//Not supported, but still added for variable subscription
		add_action(
			'woocommerce_process_product_meta_variable_subscription',
			array(
				$this,
				'save_courses_option_field',
			)
		);

		/************************************/
		/********DYNAMIC PRICING*************/
		/************************************/

		add_action( 'woocommerce_before_calculate_totals', array( $this, 'subscription_price_update' ), 200 );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'validating_renewal_process' ), 2000 );
		add_action( 'woocommerce_cart_subscription_string_details', array( $this, 'order_total_html' ), 2500 );
		add_filter(
			'woocommerce_subscriptions_product_price',
			array(
				$this,
				'update_subscription_price',
			),
			150,
			2
		);
		add_filter(
			'woocommerce_subscriptions_product_price_string_inclusions',
			array(
				$this,
				'wc_subscription_include_update',
			),
			15000,
			2
		);

		add_filter( 'woocommerce_cart_product_price', array( $this, 'subs_price_string' ), 100, 3 );
		add_filter(
			'woocommerce_subscriptions_product_price_string',
			array(
				$this,
				'subs_price_string_product_page',
			),
			101,
			3
		);
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'woo_custom_add_to_cart' ), 1600, 3 );

		//Add courses under cart item
		add_filter( 'woocommerce_cart_item_name', array( $this, 'add_course_name_in_product_title' ), 99, 3 );

		/************************************/
		/********AFTER CHECKOUT**************/
		/************************************/
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_order_meta' ) );

		/************************************/
		/********SUBSCRIPTION HOOKS**********/
		/************************************/
		add_action( 'woocommerce_subscription_status_expired', array( $this, 'change_group_to_draft' ), 99, 2 );
		add_action( 'woocommerce_subscription_status_on-hold', array( $this, 'change_group_to_draft' ), 99, 2 );
		add_action( 'woocommerce_subscription_status_cancelled', array( $this, 'change_group_to_draft' ), 99, 2 );
		add_action( 'woocommerce_subscription_status_pending-cancel', array( $this, 'change_group_to_draft' ), 99, 2 );
		add_action( 'woocommerce_subscription_status_active', array( $this, 'change_group_to_published' ), 99, 2 );

		add_action(
			'woocommerce_customer_changed_subscription_to_pending-cancel',
			array(
				$this,
				'handle_user_cancellation',
			),
			999,
			1
		);

		add_action(
			'woocommerce_customer_changed_subscription_to_expired',
			array(
				$this,
				'handle_user_cancellation',
			),
			999,
			1
		);
		add_action(
			'woocommerce_customer_changed_subscription_to_on-hold',
			array(
				$this,
				'handle_user_cancellation',
			),
			999,
			1
		);
		add_action(
			'woocommerce_customer_changed_subscription_to_cancelled',
			array(
				$this,
				'handle_user_cancellation',
			),
			999,
			1
		);
		//      add_action(
		//              'woocommerce_customer_changed_subscription_to_active',
		//              array(
		//                      $this,
		//                      'handle_user_reactivation',
		//              ),
		//              999,
		//              1
		//      );

		add_filter(
			'wcs_view_subscription_actions',
			array(
				$this,
				'my_account_subscription_actions',
			),
			10,
			2
		);
		add_action(
			'wcs_subscription_details_table_before_payment_method',
			array(
				$this,
				'my_account_subscription_lines',
			),
			99,
			1
		);

		add_action( 'template_redirect', array( $this, 'subscriptions_force_qty_update_notice' ), 999 );
	}

	/**
	 * @param $val
	 *
	 * @return void
	 */
	public function order_total_html( $val ) {
		$cart_contents = WC()->cart->cart_contents;
		if ( empty( $cart_contents ) ) {
			return;
		}
		if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
			return;
		}
		foreach ( $cart_contents as $cart ) {
			$id      = $cart['product_id'];
			$product = wc_get_product( $id );
			$qty     = WC()->cart->get_cart_item_quantities();

			if ( ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
				continue;
			}

			$price = \WC_Subscriptions_Product::get_meta_data( $product, 'subscription_price', 0, 'use_default_value' );
			if ( 0 === $price || empty( $price ) ) {
				$linked_courses = get_post_meta( $id, SharedFunctions::$license_meta_field, true );

				if ( $linked_courses ) {
					$total = 0;
					foreach ( $linked_courses as $course ) {
						$course_product = new \WC_Product( $course );
						if ( $course_product ) {
							$price_wt = SharedFunctions::get_custom_product_price( $course_product );
							$total    = floatval( $total ) + floatval( $price_wt );
						}
					}
					$qty                     = $qty[ $id ];
					$total                   = $total * $qty;
					$val['recurring_amount'] = wc_price( $total );
				}
			}
		}

		return $val;
	}

	/**
	 * @param $title
	 * @param $cart_item
	 * @param $cart_item_key
	 *
	 * @return string
	 */
	public function add_course_name_in_product_title( $title, $cart_item, $cart_item_key ) {
		if ( empty( $cart_item ) ) {
			return $title;
		}

		if ( true === apply_filters( 'ulgm_woocommerce_hide_course_title_in_subscription', false, $title, $cart_item, $cart_item_key ) ) {
			return $title;
		}

		$product = $cart_item['data'];
		if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
			return $title;
		}
		if ( ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
			return $title;
		}
		$course_names = array();
		$courses      = get_post_meta( $cart_item['product_id'], SharedFunctions::$license_meta_field, true );
		$courses_new  = get_post_meta( $cart_item['product_id'], SharedFunctions::$license_meta_field . '_new', true );
		if ( ! empty( $courses_new ) ) {
			foreach ( $courses_new as $new ) {
				$course_names[] = get_the_title( $new );
			}
		} elseif ( ! empty( $courses ) ) {
			foreach ( $courses as $c ) {
				$course_names[] = get_the_title( $c );
			}
		}

		if ( empty( $course_names ) ) {
			return $title;
		}

		$learn_dash_labels = new \LearnDash_Custom_Label();
		$course_label      = $learn_dash_labels::get_label( 'courses' );
		$courses           = '<p class="coures-assigned-heading">' . $course_label . ':</p><ul class="courses-assigned">';
		foreach ( $course_names as $n ) {
			$courses .= '<li>' . $n . '</li>';
		}
		$courses .= '</ul>';

		return $title . $courses;
	}

	/**
	 * Save the custom fields.
	 */
	public function save_courses_option_field( $post_id ) {
		if ( ulgm_filter_has_var( '_license', INPUT_POST ) ) {
			update_post_meta( $post_id, SharedFunctions::$license_meta_field, ulgm_filter_input_array( '_license', INPUT_POST ) );
		} else {
			delete_post_meta( $post_id, SharedFunctions::$license_meta_field );
		}
	}

	/**
	 * @param $cart_object
	 *
	 * @return mixed
	 */
	public function subscription_price_update( $cart_object ) {
		$products = $cart_object->cart_contents;
		if ( ! $products ) {
			return;
		}
		if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
			return;
		}
		foreach ( $products as $prod ) {
			$id      = $prod['product_id'];
			$product = wc_get_product( $id );
			if ( ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
				continue;
			}
			$price = \WC_Subscriptions_Product::get_meta_data( $product, 'subscription_price', 0, 'use_default_value' );
			if ( 0 === $price || empty( $price ) ) {
				$linked_courses = get_post_meta( $id, SharedFunctions::$license_meta_field, true );
				if ( $linked_courses ) {
					$total = 0;
					foreach ( $linked_courses as $course ) {
						$course_product = new \WC_Product( $course );
						if ( $course_product ) {
							$price_wt = SharedFunctions::get_custom_product_price( $course_product );
							$total    = floatval( $total ) + floatval( $price_wt );
						}
					}
					$prod['data']->set_price( $total );
				}
			}
		}
	}

	/**
	 * @param $subscription_string
	 * @param \WC_Product $product
	 *
	 * @return mixed|string|void
	 */
	public function subs_price_string( $subscription_string, \WC_Product $product ) {
		if ( empty( $subscription_string ) ) {
			return $subscription_string;
		}

		if ( ! class_exists( 'WC_Subscriptions_Product' ) || ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
			return $subscription_string;
		}

		$courses     = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
		$fixed_price = (string) get_post_meta( $product->get_id(), 'ulgm_license_fixed_price', true );
		if ( ! empty( $courses ) ) {
			$per_seat_text = get_option( 'ulgm_per_seat_text', __( 'Seat', 'uncanny-learndash-groups' ) );
			if ( 'yes' === $fixed_price ) {
				$per_seat_text = __( 'group', 'uncanny-learndash-groups' );
			}
			$subscription_string .= " / $per_seat_text";
			$subscription_string = apply_filters( 'ulgm_per_seat_text_subscription', $subscription_string, $product, $per_seat_text );
		}

		return $subscription_string;
	}

	/**
	 * @param $subscription_string
	 * @param \WC_Product $product
	 *
	 * @return mixed|string|void
	 */
	public function subs_price_string_product_page( $subscription_string, \WC_Product $product ) {
		if ( empty( $subscription_string ) || is_cart() || is_checkout() ) {
			return $subscription_string;
		}

		if ( ! class_exists( 'WC_Subscriptions_Product' ) || ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
			return $subscription_string;
		}

		$courses     = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
		$fixed_price = (string) get_post_meta( $product->get_id(), 'ulgm_license_fixed_price', true );
		if ( ! empty( $courses ) ) {
			$per_seat_text = get_option( 'ulgm_per_seat_text', __( 'Seat', 'uncanny-learndash-groups' ) );
			if ( 'yes' === $fixed_price ) {
				$per_seat_text = __( 'group', 'uncanny-learndash-groups' );
			}
			$subscription_string .= " / $per_seat_text";
			$subscription_string = apply_filters( 'ulgm_per_seat_text_subscription', $subscription_string, $product, $per_seat_text );
		}

		return $subscription_string;
	}

	/**
	 * @param $active_price
	 * @param \WC_Product_Subscription $product
	 *
	 * @return float|int
	 */
	public function update_subscription_price( $active_price, $product ) {
		if ( empty( $product ) ) {
			return $active_price;
		}
		if ( ! class_exists( 'WC_Subscriptions_Product' ) || ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
			return $active_price;
		}
		$id    = $product->get_id();
		$price = \WC_Subscriptions_Product::get_meta_data( $product, 'subscription_price', 0, 'use_default_value' );

		if ( 0 === $price || empty( $price ) ) {
			//$active_price   = 0;
			$linked_courses = get_post_meta( $id, SharedFunctions::$license_meta_field, true );
			if ( $linked_courses ) {
				foreach ( $linked_courses as $course ) {
					if ( false !== get_post_status( $course ) ) {
						$course_product = new \WC_Product( $course );
						if ( $course_product ) {
							$price_wt     = SharedFunctions::get_custom_product_price( $course_product );
							$active_price = floatval( $active_price ) + floatval( $price_wt );
						}
					}
				}
			}
		}

		return $active_price;
	}


	/**
	 * @param $include
	 * @param \WC_Product_Subscription $product
	 *
	 * @return float|int
	 */
	public function wc_subscription_include_update( $include, $product ) {
		if ( ! $product instanceof \WC_Product_Subscription ) {
			return $include;
		}
		$id    = $product->get_id();
		$price = \WC_Subscriptions_Product::get_meta_data( $product, 'subscription_price', 0, 'use_default_value' );

		if ( 0 === $price || empty( $price ) ) {
			$linked_courses = get_post_meta( $id, SharedFunctions::$license_meta_field, true );
			if ( $linked_courses ) {
				$active_price = 0;
				foreach ( $linked_courses as $course ) {
					if ( false !== get_post_status( $course ) ) {
						$course_product = new \WC_Product( $course );
						if ( $course_product ) {
							$price_wt     = SharedFunctions::get_custom_product_price( $course_product );
							$active_price = floatval( $active_price ) + floatval( $price_wt );
						}
					}
				}

				$include['price'] = wc_price( $active_price );
			}
		}

		return $include;
	}

	/**
	 * @return array
	 */
	public static function check_if_course_subscription_in_cart() {
		$items = \WC()->cart->get_cart();
		if ( empty( $items ) ) {
			return array( 'status' => false );
		}
		if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
			return array( 'status' => false );
		}
		foreach ( $items as $item ) {
			$product = $item['data'];
			if ( ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
				continue;
			}
			$courses = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
			if ( ! empty( $courses ) ) {
				return array(
					'status'     => true,
					'product_id' => $product->get_id(),
				);
			}
		}

		return array( 'status' => false );
	}

	/**
	 * @param $order_id
	 *
	 * @return array
	 */
	private function check_if_course_subscription_in_order( $order_id ) {
		$order = wc_get_order( $order_id );
		$items = $order->get_items();
		if ( empty( $items ) ) {
			return array( 'status' => false );
		}
		if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
			return array( 'status' => false );
		}
		/** @var \WC_Order_Item_Product $item */
		foreach ( $items as $item ) {
			$product = $item->get_product();
			if ( ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
				continue;
			}
			$courses = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
			if ( ! empty( $courses ) ) {
				return array(
					'status'     => true,
					'product_id' => $product->get_id(),
				);
			}
		}

		return array( 'status' => false );
	}


	/**
	 * @param \WC_Subscription $subscription
	 */
	public function process_subscription_completed( \WC_Subscription $subscription ) {
		$order_id = $subscription->get_last_order( 'ids', array( 'parent' ) );
		$order    = wc_get_order( $order_id );
		if ( ! $order instanceof \WC_Order ) {
			return;
		}
		$user_id = $order->get_user_id();
		$user    = $order->get_user();
		if ( ! $user instanceof \WP_User ) {
			return;
		}
		$product_id           = 0;
		$_quantity            = 0;
		$line_items           = $order->get_items( 'line_item' );
		$group_created        = get_post_meta( $order_id, '_group_already_created', true );
		$maybe_resubscription = wcs_order_contains_resubscribe( $order );
		$old_subscription     = wcs_get_subscriptions_for_resubscribe_order( $order );
		if ( ! empty( $old_subscription ) && $maybe_resubscription ) {
			if ( is_array( $old_subscription ) ) {
				foreach ( $old_subscription as $o_sub_id => $o_sub_data ) {
					$_old_subscription      = new \WC_Subscription( $o_sub_id );
					$old_order_id           = $_old_subscription->get_last_order( 'ids', 'parent' );
					$existing_group_id      = get_post_meta( $old_order_id, SharedFunctions::$linked_group_id_meta, true );
					$existing_code_group_id = get_post_meta( $old_order_id, SharedFunctions::$code_group_id_meta_key, true );
					update_post_meta( $order_id, SharedFunctions::$linked_group_id_meta, $existing_group_id );
					update_post_meta( $order_id, '_group_already_created', 'yes' );
					update_post_meta( $order_id, SharedFunctions::$code_group_id_meta_key, $existing_code_group_id );
					update_post_meta( $existing_group_id, '_group_resubscription_id', $subscription->get_id() );
					$this->force_change_group_to_publish( $existing_group_id, $_old_subscription );
				}
			}
		}

		if ( $maybe_resubscription ) {
			return;
		}

		if ( ! $order->has_status( 'completed' ) && ! $order->has_status( 'processing' ) ) {
			return;
		}

		if ( ! $line_items || ! empty( $group_created ) ) {
			return;
		}
		$continue = false;

		foreach ( $line_items as $item ) {
			$_product  = $item->get_product();
			$_quantity = $item->get_quantity();
			if ( ! $_product instanceof \WC_Product ) {
				continue;
			}
			if ( $this->check_if_courses_in_subscription_items( $_product->get_id() ) ) {
				$product_id = $_product->get_id();
				$continue   = true;
				break;
			}
		}

		if ( false === $continue ) {
			return;
		}
		if ( 'yes' === get_post_meta( $product_id, '_uo_custom_buy_product', true ) ) {
			wp_update_post(
				array(
					'ID'          => $product_id,
					'post_author' => apply_filters( 'ulgm_custom_license_post_author', $user->ID, get_current_user_id(), 'license-purchase' ),
				)
			);
		}
		if ( ! user_can( $user, 'group_leader' ) && ! user_can( $user, 'administrator' ) ) {
			$user->add_role( 'group_leader' );
		}
		$group_title   = get_post_meta( $order_id, SharedFunctions::$group_name_field, true );
		$ld_group_args = apply_filters(
			'ulgm_insert_group',
			array(
				'post_type'    => 'groups',
				'post_status'  => 'publish',
				'post_title'   => $group_title,
				'post_content' => '',
				'post_author'  => apply_filters( 'ulgm_custom_group_post_author', $user->ID, get_current_user_id(), 'license-purchase' ),
			)
		);

		$group_id = wp_insert_post( $ld_group_args );

		foreach ( $line_items as $line ) {
			$courses_linked = get_post_meta( $line['product_id'], SharedFunctions::$license_meta_field, true );
			if ( $courses_linked ) {
				foreach ( $courses_linked as $course_product ) {
					$course_id = get_post_meta( $course_product, '_ulgm_course', true );
					ld_update_course_group_access( (int) $course_id, (int) $group_id, false );
					$transient_key = 'learndash_course_groups_' . $course_id;
					delete_transient( $transient_key );
				}
			}
		}
		$qty = $_quantity;
		update_post_meta( $group_id, '_ulgm_total_seats', $qty );
		update_post_meta( $order_id, SharedFunctions::$linked_group_id_meta, $group_id );
		$attr          = array(
			'user_id'    => $user_id,
			'order_id'   => $order_id,
			'group_id'   => $group_id,
			'group_name' => $group_title,
			'qty'        => $qty,
		);
		$codes         = ulgm()->group_management->generate_random_codes( $qty );
		$code_group_id = ulgm()->group_management->add_codes( $attr, $codes );

		update_post_meta( $group_id, SharedFunctions::$code_group_id_meta_key, $code_group_id );
		update_post_meta( $group_id, '_uo_subscription_group', 'yes' );
		update_post_meta( $group_id, '_group_main_subscription_id', $subscription->get_id() );
		update_post_meta( $order_id, '_group_already_created', 'yes' );
		update_post_meta( $order_id, SharedFunctions::$code_group_id_meta_key, $code_group_id );
		update_post_meta( $subscription->get_id(), '_main_subscription_order', $group_id );

		ld_update_leader_group_access( $user->ID, $group_id );

		//Setting group leader as a member of the group & using 1 code / qty for it.
		if ( 'yes' !== get_option( 'do_not_add_group_leader_as_member', 'no' ) ) {

			Group_Management_Helpers::add_existing_user( array( 'user_email' => $user->user_email ), true, $group_id, $order_id, SharedFunctions::$redeem_status, false );
		}
		SharedFunctions::remove_transient_cache( 'all' );
		do_action( 'uo_new_group_purchased', $group_id, $user->ID );
	}

	/**
	 * @param \WC_Subscription $subscription
	 */
	public function change_group_to_draft( \WC_Subscription $subscription ) {
		if ( true !== apply_filters( 'ulgm_subscription_status_switch_group_to_draft', true, current_action(), $subscription, $this ) ) {
			return;
		}
		/**
		 * Let the user have access to group until the subscription runs out OR admin cancels it
		 */
		if ( 'woocommerce_subscription_status_pending-cancel' === current_action() ) {
			return;
		}
		$order_id = $subscription->get_last_order( 'ids', array( 'parent' ) );
		$group_id = get_post_meta( $order_id, SharedFunctions::$linked_group_id_meta, true );
		if ( empty( $group_id ) || ! is_numeric( $group_id ) ) {
			return;
		}

		if ( false === apply_filters( 'ulgm_subscription_license_drop_users_courses', true, $subscription ) ) {
			return;
		}

		/**
		 * Remove users access from Group
		 */
		$users = LearndashFunctionOverrides::learndash_get_groups_user_ids( $group_id );
		if ( $users ) {
			foreach ( $users as $user_id ) {
				ld_update_group_access( $user_id, $group_id, true );
				$transient_key = "learndash_user_groups_{$user_id}";
				delete_transient( $transient_key );
			}
		}

		/**
		 * Remove courses from Group
		 */
		$group_course_ids = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id );
		if ( $group_course_ids ) {
			foreach ( $group_course_ids as $course_id ) {
				ld_update_course_group_access( $course_id, $group_id, true );
				$transient_key = "learndash_course_groups_{$course_id}";
				delete_transient( $transient_key );
			}
		}

		update_post_meta( $group_id, 'uo_group_courses', $group_course_ids );
		update_post_meta( $group_id, 'uo_group_users', $users );

		$post              = get_post( $group_id );
		$post->post_status = 'draft';
		wp_update_post( $post );

		switch ( current_action() ) {
			case 'woocommerce_subscription_status_expired':
				$new_status = 'expired';
				break;
			case 'woocommerce_subscription_status_on-hold':
				$new_status = 'on-hold';
				break;
			case 'woocommerce_subscription_status_pending-cancel':
				$new_status = 'pending-cancel';
				break;
			case 'woocommerce_subscription_status_cancelled':
			default:
				$new_status = 'cancelled';
				break;
		}

		$this->change_subscription_statuses( $group_id, $subscription, $new_status );
	}

	/**
	 * @param \WC_Subscription $subscription
	 */
	public function change_group_to_published( \WC_Subscription $subscription ) {
		$order_id = $subscription->get_last_order( 'ids', array( 'parent' ) );
		$group_id = self::is_subscription_linked_to_a_group( $subscription );
		if ( false === $group_id ) {
			$this->process_subscription_completed( $subscription );

			return;
		}
		if ( ! empty( get_post_meta( $group_id, 'subscription_additional_seats_order_' . $order_id, true ) ) ) {
			add_post_meta( $group_id, 'subscription_additional_seats_subscription_ids', $subscription->get_id() );
			update_post_meta( $group_id, 'subscription_additional_seats_subscription_id_' . $subscription->get_id(), $subscription->get_id() );
			if ( empty( get_post_meta( $subscription->get_id(), '_subscription_resubscribe', true ) ) ) {
				update_post_meta( $subscription->get_id(), 'subscription_additional_seats_group_id', $group_id );
			} else {
				update_post_meta( $subscription->get_id(), '_main_subscription_order', $group_id );
			}
		}
		$post              = get_post( $group_id );
		$post->post_status = 'publish';
		wp_update_post( $post );

		$this->maybe_restore_courses_users( $group_id, $subscription );
	}

	/**
	 * @param null $group_id
	 */
	public function force_change_group_to_publish( $group_id = null, $subscription = null ) {
		if ( null === $group_id || ! is_numeric( $group_id ) ) {
			return;
		}
		$post              = get_post( $group_id );
		$post->post_status = 'publish';
		wp_update_post( $post );
		$this->maybe_restore_courses_users( $group_id, $subscription );
	}

	/**
	 * @param $group_id
	 * @param $subscription
	 */
	public function maybe_restore_courses_users( $group_id, $subscription ) {

		if ( false === apply_filters( 'ulgm_subscription_license_drop_users_courses', true, $subscription ) ) {
			return;
		}

		/**
		 * Assign courses back to group
		 */
		$group_course_ids = get_post_meta( $group_id, 'uo_group_courses', true );
		if ( $group_course_ids ) {
			foreach ( $group_course_ids as $course_id ) {
				ld_update_course_group_access( $course_id, $group_id, false );
				$transient_key = "learndash_course_groups_{$course_id}";
				delete_transient( $transient_key );
			}
		}
		delete_post_meta( $group_id, 'uo_group_courses' );

		/**
		 * Assign users back to group
		 */
		$user_ids = get_post_meta( $group_id, 'uo_group_users', true );
		if ( $user_ids ) {
			foreach ( $user_ids as $user_id ) {
				ld_update_group_access( $user_id, $group_id, false );
				$transient_key = "learndash_user_groups_{$user_id}";
				delete_transient( $transient_key );
			}
		}
		delete_post_meta( $group_id, 'uo_group_users' );
	}

	/**
	 * @param $order_id
	 */
	public function update_order_meta( $order_id ) {
		if ( ulgm_filter_has_var( 'ulgm_group_name', INPUT_POST ) && ! empty( trim( ulgm_filter_input( 'ulgm_group_name', INPUT_POST ) ) ) ) {
			$new_group_name = apply_filters( 'ulgm_group_name', sanitize_text_field( ulgm_filter_input( 'ulgm_group_name', INPUT_POST ) ), wc_get_order( $order_id ) );
			update_post_meta( $order_id, SharedFunctions::$group_name_field, $new_group_name );

			return;
		}
		$show_additional_fields = $this->check_if_course_subscription_in_order( $order_id );
		if ( $show_additional_fields['status'] ) {
			$order          = wc_get_order( $order_id );
			$new_group_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() . ' ' . $order->get_billing_company();
			$new_group_name = apply_filters( 'ulgm_group_name', $new_group_name, wc_get_order( $order_id ) );
			update_post_meta( $order_id, SharedFunctions::$group_name_field, sanitize_text_field( $new_group_name ) );
		}
	}

	/**
	 * @param $product_id
	 *
	 * @return bool
	 */
	public function check_if_courses_in_subscription_items( $product_id ) {
		$product = wc_get_product( $product_id );
		if ( ! class_exists( 'WC_Subscriptions_Product' ) || ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
			return false;
		}

		$courses = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
		if ( ! empty( $courses ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $cart_item_data
	 * @param $product_id
	 * @param $variation_id
	 *
	 * @return mixed
	 */
	public function woo_custom_add_to_cart( $cart_item_data, $product_id, $variation_id ) {

		$product = wc_get_product( $product_id );
		//global $woocommerce;
		if ( class_exists( 'WC_Subscriptions_Product' ) && \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
			if ( $this->check_if_courses_in_subscription_items( $product_id ) ) {
				$cart = WC()->cart->get_cart_contents();
				if ( $cart ) {
					foreach ( $cart as $cart_key => $cart_contents ) {
						$cart_product_id = $cart_contents['product_id'];
						$cart_product    = wc_get_product( $cart_product_id );
						$product_name    = get_the_title( $cart_product_id );
						if ( $product_id !== $cart_product_id && $cart_product instanceof \WC_Product_Subscription ) {
							if ( $this->check_if_courses_in_subscription_items( $cart_product_id ) ) {
								WC()->cart->remove_cart_item( $cart_key );
								wc_add_notice( sprintf( esc_html__( '%s removed from cart. Only one license is allowed.', 'uncanny-learndash-groups' ), $product_name ), 'error' );
							}
						}
					}
				}
			}
		}

		// Do nothing with the data and return
		return $cart_item_data;
	}

	/**
	 * @param $cart_product_id
	 */
	public function remove_related_courses( $cart_product_id ) {
		$cart    = WC()->cart->cart_contents;
		$courses = get_post_meta( $cart_product_id, SharedFunctions::$license_meta_field, true );
		if ( $cart && $courses ) {
			foreach ( $cart as $cart_key => $cart_contents ) {
				$cart_product_id = $cart_contents['product_id'];
				if ( in_array( $cart_product_id, $courses ) ) {
					WC()->cart->remove_cart_item( $cart_key );
				}
			}
		}
	}

	/**
	 * @param $actions
	 * @param \WC_Subscription $subscription
	 *
	 * @return mixed|void
	 */
	public function my_account_subscription_actions( $actions, \WC_Subscription $subscription ) {
		$group_id = self::is_subscription_linked_to_a_group( $subscription );
		if ( false === $group_id ) {
			return $actions;
		}

		unset( $actions['subscription_renewal_early'] );
		$_g_id = get_post_meta( $subscription->get_id(), 'subscription_additional_seats_group_id', true );

		if ( ! empty( $_g_id ) && $group_id === $_g_id ) {
			unset( $actions['resubscribe'] );
		}
		if ( isset( $actions['cancel'] ) ) {
			$actions['cancel']['name'] = __( 'Cancel all subscriptions for this group', 'uncanny-learndash-groups' );
			$actions['cancel']['url']  = $actions['cancel']['url'] . '&ld_group_id=' . $group_id;
		}

		return $actions;
	}

	/**
	 * @param $actions
	 * @param \WC_Subscription $subscription
	 *
	 * @return mixed|void
	 */
	public function my_account_subscription_lines( \WC_Subscription $subscription ) {
		$group_id = self::is_subscription_linked_to_a_group( $subscription );
		if ( false === $group_id ) {
			return;
		}
		$group_management_link = ulgm()->group_management->pages->get_group_management_page_id( true );
		$group_management_link = "$group_management_link?group-id=" . $group_id;
		?>
		<tr>
			<td><?php esc_html_e( 'Linked group', 'uncanny-learndash-groups' ); ?></td>
			<td>
				<span data-is_manual="<?php echo esc_attr( wc_bool_to_string( $subscription->is_manual() ) ); ?>">
					<a href="<?php echo esc_url_raw( $group_management_link ); ?>"><?php echo get_the_title( $group_id ); ?></a>
				</span>
			</td>
		</tr>
		<?php
		$group_id = get_post_meta( $subscription->get_id(), 'subscription_additional_seats_group_id', true );
		if ( empty( $group_id ) ) {
			return;
		}
		$main_subscription_id = get_post_meta( $group_id, '_group_main_subscription_id', true );
		$resubscription_ids   = get_post_meta( $group_id, '_group_resubscription_id' );
		if ( empty( $main_subscription_id ) && empty( $resubscription_ids ) ) {
			return;
		}
		if ( ! empty( $main_subscription_id ) ) {
			$subscription_id = $main_subscription_id;
		}

		if ( ! empty( $resubscription_ids ) && is_array( $resubscription_ids ) ) {
			$subscription_id = array_pop( $resubscription_ids );
		}

		if ( empty( $subscription_id ) ) {
			return;
		}
		$view_subscription_url = wc_get_endpoint_url( 'view-subscription', $subscription_id, wc_get_page_permalink( 'myaccount' ) );

		$view_subscription_url = apply_filters( 'wcs_get_view_subscription_url', $view_subscription_url, $subscription_id );
		?>
		<tr>
			<td><?php esc_html_e( 'Parent subscription', 'uncanny-learndash-groups' ); ?></td>
			<td>
				<span data-is_manual="<?php echo esc_attr( wc_bool_to_string( $subscription->is_manual() ) ); ?>">
					<a href="<?php echo esc_url_raw( $view_subscription_url ); ?>">#<?php echo esc_attr( $subscription_id ); ?></a>
				</span>
			</td>
		</tr>
		<?php
	}

	/**
	 * @param \WC_Subscription $subscription
	 *
	 * @return bool|mixed
	 */
	public static function is_subscription_linked_to_a_group( \WC_Subscription $subscription ) {
		$order_id = $subscription->get_last_order( 'ids', array( 'parent' ) );
		$group_id = get_post_meta( $order_id, SharedFunctions::$linked_group_id_meta, true );
		if ( empty( $group_id ) || ! is_numeric( $group_id ) ) {
			return false;
		}

		return $group_id;
	}

	/**
	 * @param \WC_Subscription $subscription
	 *
	 * @return void
	 */
	public function handle_user_cancellation( \WC_Subscription $subscription ) {
		if ( ! $subscription instanceof \WC_Subscription ) {
			return;
		}
		$group_id = self::is_subscription_linked_to_a_group( $subscription );
		if ( false === $group_id && ! isset( $_GET['ld_group_id'] ) ) {
			return;
		}
		if ( isset( $_GET['ld_group_id'] ) ) {
			$group_id = absint( $_GET['ld_group_id'] );
		}
		switch ( current_action() ) {
			case 'woocommerce_customer_changed_subscription_to_expired':
				$new_status = 'expired';
				break;
			case 'woocommerce_customer_changed_subscription_to_on-hold':
				$new_status = 'on-hold';
				break;
			case 'woocommerce_customer_changed_subscription_to_pending-cancel':
			case 'woocommerce_customer_changed_subscription_to_cancelled':
			default:
				$new_status = 'pending-cancel';
				break;
		}
		$this->change_subscription_statuses( $group_id, $subscription, $new_status );
	}

	/**
	 * @param $group_id
	 * @param $subscription
	 * @param $new_status
	 *
	 * @return void
	 */
	public function change_subscription_statuses( $group_id, $subscription, $new_status ) {
		$customer_id       = $subscription->get_customer_id();
		$all_subscriptions = wcs_get_subscriptions(
			array(
				'customer_id'            => $customer_id,
				'subscriptions_per_page' => 99,
			)
		);

		if ( empty( $all_subscriptions ) ) {
			return;
		}
		$g_title = get_the_title( $group_id );
		foreach ( $all_subscriptions as $all_subscription ) {
			if ( $all_subscription->get_id() === $subscription->get_id() ) {
				continue;
			}
			// Cancelling all additional seats orders if main subscription is cancelled
			$_g_id = get_post_meta( $all_subscription->get_id(), 'subscription_additional_seats_group_id', true );

			if ( absint( $_g_id ) === absint( $group_id ) ) {
				$all_subscription->update_status( $new_status, "All subscriptions linked to group #$group_id - $g_title ", true );
			}
			// Cancelling parent subscription if additional seats order is cancelled
			$_g_id = get_post_meta( $all_subscription->get_id(), '_main_subscription_order', true );

			if ( absint( $_g_id ) === absint( $group_id ) ) {
				$all_subscription->update_status( $new_status, "All subscriptions linked to group #$group_id - $g_title ", true );
			}
		}

	}

	/**
	 * @param \WC_Cart $cart
	 *
	 * @return void
	 */
	public function validating_renewal_process( \WC_Cart $cart ) {

		if ( ! class_exists( 'WC_Subscriptions_Product' ) || ! class_exists( '\WC_Subscriptions_Cart' ) ) {
			return;
		}
		if ( ! \WC_Subscriptions_Cart::cart_contains_subscription() ) {
			return;
		}

		foreach ( $cart->cart_contents as $cart_item_key => $cart_item ) {
			if ( ! array_key_exists( 'subscription_resubscribe', $cart_item ) ) {
				return;
			}

			$product = wc_get_product( $cart_item['product_id'] );
			if ( ! \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
				continue;
			}
			$subscription_id = absint( $cart_item['subscription_resubscribe']['subscription_id'] );
			$quantity        = $cart_item['quantity'];
			$subscription    = wcs_get_subscription( $subscription_id );
			$group_id        = self::is_subscription_linked_to_a_group( $subscription );
			if ( false === $group_id ) {
				continue;
			}
			$total_seats = ulgm()->group_management->seat->total_seats( $group_id );
			if ( $total_seats > $quantity ) {
				$cart->set_quantity( $cart_item_key, $total_seats ); // Change quantity
				set_transient(
					'uo_groups_wcs_forced_quantity_updated',
					sprintf(
						esc_html__( 'The subscription quantity updated from %1$d to %2$d in the cart to match the minimum seat count in the linked group: %3$s.', 'uncanny-learndash-groups' ),
						$quantity,
						$total_seats,
						get_the_title( $group_id )
					)
				);
			}
		}
	}

	/**
	 * @return bool
	 */
	public static function is_woo_subscriptions_add_seats_enabled() {
		if ( 'yes' === (string) get_option( 'woo_subscription_allow_additional_seats', 'no' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @return void
	 */
	public function subscriptions_force_qty_update_notice() {
		if ( is_checkout() && ! is_wc_endpoint_url() ) {
			$message = get_transient( 'uo_groups_wcs_forced_quantity_updated' );
			if ( ! empty( $message ) ) {
				wc_add_notice( $message, 'notice' );
				delete_transient( 'uo_groups_wcs_forced_quantity_updated' );
			}
		}
	}
}
