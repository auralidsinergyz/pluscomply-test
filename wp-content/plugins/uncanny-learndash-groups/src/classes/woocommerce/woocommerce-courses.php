<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class WoocommerceCourses
 * @package uncanny_learndash_groups
 */
class WoocommerceCourses {

	/**
	 * WoocommerceCourses constructor.
	 */
	public function __construct() {

		// Only Run if woocommerce is available
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'load_new_product_type' ), 11 );
	}

	/**
	 *
	 */
	public function plugins_loaded() {
		add_filter( 'product_type_selector', array( $this, 'add_courses_product' ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'courses_custom_js' ), 999 );
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'custom_product_tabs' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'courses_options_product_tab_content' ) );
		add_action( 'woocommerce_process_product_meta_courses', array( $this, 'save_courses_option_field' ) );
		add_action(
			'woocommerce_process_product_meta_variable_courses',
			array(
				$this,
				'save_courses_option_field',
			)
		);
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'hide_attributes_data_panel' ) );
		add_filter( 'woocommerce_is_sold_individually', array( $this, 'wc_remove_all_quantity_fields' ), 10, 2 );

		/************************************/
		/*
		 * Removed this in version 1.3 to avoid auto completing all type of orders.
		 * A user had issues when using cheque payment.
		 * add_filter( 'woocommerce_thankyou', array(
			$this,
			'virtual_order_payment_complete_order_status',
		), 999, 2 );*/
		add_action( 'woocommerce_order_status_completed', array( $this, 'send_receipt' ), 10, 1 );
		add_action( 'woocommerce_order_status_processing', array( $this, 'send_receipt' ), 10, 1 );
		add_action( 'woocommerce_order_status_refunded', array( $this, 'remove_course' ), 10, 1 );

		// Give and remove course access based on WooCommerce subscriptions
		add_action( 'cancelled_subscription', array( $this, 'delete_course_access_old' ), 10, 2 );
		add_action( 'subscription_put_on-hold', array( $this, 'delete_course_access_old' ), 10, 2 );
		add_action( 'subscription_expired', array( $this, 'delete_course_access_old' ), 10, 2 );
		add_action( 'activated_subscription', array( $this, 'give_course_access_old' ), 10, 2 );

		// New hooks for WC subscription
		add_action( 'woocommerce_subscription_status_cancelled', array( $this, 'delete_course_access' ) );
		add_action( 'woocommerce_subscription_status_on-hold', array( $this, 'delete_course_access' ) );
		add_action( 'woocommerce_subscription_status_expired', array( $this, 'delete_course_access' ) );
		// add_action( 'woocommerce_subscription_status_active', array( $this, 'give_course_access' ) );
		add_action(
			'woocommerce_subscription_status_updated',
			array(
				$this,
				'subscription_on_hold_to_active',
			),
			99,
			3
		);

		// Force user to log in or create account if there is LD course
		add_action( 'woocommerce_checkout_init', array( $this, 'force_login' ), 10, 1 );

		//add_filter( 'woocommerce_add_cart_item_data', array( $this, 'woo_custom_add_to_cart' ), 90, 2 );

		add_action( 'woocommerce_courses_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
	}

	/**
	 *
	 */
	public function woocommerce_simple_add_to_cart() {
		wc_get_template( 'single-product/add-to-cart/simple.php' );
	}

	/**
	 * @param $cart_item_data
	 * @param $product_id
	 *
	 * @return mixed
	 */
	public function woo_custom_add_to_cart( $cart_item_data, $product_id ) {
		if ( ! empty( get_post_meta( $product_id, SharedFunctions::$course_meta_field, true ) ) || ! empty( get_post_meta( $product_id, SharedFunctions::$license_meta_field, true ) ) ) {

			return $cart_item_data;
		}

		if ( 0 !== WC()->cart->get_cart_contents_count() ) {
			$cart_contents = WC()->cart->cart_contents;
			if ( $cart_contents ) {
				$data = $this->if_course_license_product_type_in_cart( $cart_contents );
				if ( ! $data['passed'] ) {
					wc_add_notice( sprintf( __( '%1$s licenses cannot be purchased in the same transaction as other products. %2$s was removed from your cart.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ), get_the_title( $data['product_id'] ) ), 'error' );
					$user_id = wp_get_current_user()->ID;
					delete_transient( '_ulgm_user_buy_courses_' . $user_id . '_order' );
					delete_transient( '_ulgm_user_' . $user_id . '_order' );
					if ( is_array( $data['item_key'] ) ) {
						foreach ( $data['item_key'] as $k ) {
							\WC()->cart->remove_cart_item( $k );
						}
					}
				}
			}
		}

		// Do nothing with the data and return
		return $cart_item_data;
	}


	/**
	 * @param $cart_items
	 *
	 * @return array
	 */
	public function if_course_license_product_type_in_cart( $cart_items ) {
		$passed        = true;
		$product_id    = 0;
		$cart_item_key = array();
		foreach ( $cart_items as $item_key => $cart_item ) {
			$product_id = $cart_item['product_id'];
			if ( ! empty( get_post_meta( $product_id, SharedFunctions::$course_meta_field, true ) ) || ! empty( get_post_meta( $product_id, SharedFunctions::$license_meta_field, true ) ) ) {
				$passed = false;
				break;
			}
		}
		foreach ( $cart_items as $item_key => $cart_item ) {
			$_product = $cart_item ['data'];
			if ( $_product instanceof \WC_Product && ( $_product->is_type( 'license' ) || $_product->is_type( 'courses' ) ) ) {
				$cart_item_key[] = $item_key;
			}
		}
		$array = array(
			'passed'     => $passed,
			'product_id' => $product_id,
			'item_key'   => $cart_item_key,
		);

		return $array;
	}

	/**
	 * @param $order_id
	 */
	/*public function virtual_order_payment_complete_order_status( $order_id ) {
		if ( ! $order_id ) {
			return;
		}

		$order = wc_get_order( $order_id );

		$order->update_status( 'completed' );
	}*/

	/**
	 *
	 */
	public function add_front_scripts() {
		wp_enqueue_script( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
	}

	/**
	 *
	 */
	public function load_new_product_type() {
		if ( class_exists( '\WC_Product' ) ) {
			include_once Utilities::get_include( 'woocommerce/wc_product_courses.php' );
			new \WC_Product_Courses( 'courses' );
		}
	}


	/**
	 * @param $types
	 *
	 * @return mixed
	 */
	public function add_courses_product( $types ) {

		// Key should be exactly the same as in the class product_type parameter
		$types['courses'] = sprintf( __( 'LearnDash group %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );

		return $types;

	}

	/**
	 * Show pricing fields for courses product.
	 */
	function courses_custom_js() {
		if ( SharedFunctions::load_backend_bundles() ) {
			wp_enqueue_script( 'ulgm-backend', Utilities::get_asset( 'backend', 'bundle.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
		}
	}

	/**
	 * Add a custom product tab.
	 */
	function custom_product_tabs( $tabs ) {
		$tabs['courses'] = array(
			'label'  => sprintf( _x( '%s', 'Courses', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ),
			'target' => 'courses_options',
			'class'  => array( 'show_if_courses', 'show_if_variable_courses' ),
		);

		return $tabs;
	}


	/**
	 * Contents of the courses options product tab.
	 */
	function courses_options_product_tab_content() {
		global $post, $woocommerce;
		$courses = $this->list_courses();
		?>
		<div id='courses_options' class='panel woocommerce_options_panel'>		<div class='options_group show_if_courses'>
		<?php
			woocommerce_wp_select(
				array(
					'id'          => SharedFunctions::$course_meta_field,
					'name'        => '_courses',
					'label'       => sprintf( _x( '%s', 'Course', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ),
					'description' => sprintf( __( 'Select LearnDash Group %s.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ),
					'options'     => $courses,
				)
			);
		?>
			</div>

		</div>
		<?php
	}

	/**
	 * Save the custom fields.
	 */
	function save_courses_option_field( $post_id ) {
		if ( ulgm_filter_has_var( '_courses', INPUT_POST ) && ulgm_filter_input( '_courses', INPUT_POST ) > 0 ) {
			update_post_meta( $post_id, SharedFunctions::$course_meta_field, ulgm_filter_input( '_courses', INPUT_POST ) );
		} else {
			delete_post_meta( $post_id, SharedFunctions::$course_meta_field );
		}
	}

	/**
	 * @return array
	 */
	function list_courses() {
		//global $post;
		//$postid     = $post->ID;
		$posts      = get_posts(
			array(
				'post_type'      => 'sfwd-courses',
				'posts_per_page' => 9999,
			)
		);
		$courses    = array();
		$courses[0] = sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );
		foreach ( $posts as $course_post ) {
			$courses[ $course_post->ID ] = get_the_title( $course_post->ID );
		}

		return $courses;
	}

	/**
	 * Hide Attributes data panel.
	 */
	function hide_attributes_data_panel( $tabs ) {
		// Other default values for 'attribute' are; general, inventory, shipping, linked_product, variations, advanced
		$tabs['attribute']['class'][]      = 'hide_if_courses hide_if_variable_courses';
		$tabs['linked_product']['class'][] = 'hide_if_courses hide_if_variable_courses';
		$tabs['variations']['class'][]     = 'hide_if_courses hide_if_variable_courses';
		$tabs['shipping']['class'][]       = 'hide_if_courses hide_if_variable_courses';

		return $tabs;

	}

	/**
	 * Remove Access to the course linked to the subscription key
	 *
	 * @param int $user_id User ID
	 * @param string $subscription_key Subscription key
	 *
	 * @link   https://thomaslecoz.com/learndash-with-woocommerce-subscriptions/
	 */
	public function delete_course_access_old( $user_id, $subscription_key ) {

		// Get the course ID related to the subscription
		$subscription = \WC_Subscriptions_Manager::get_subscription( $subscription_key );
		$courses_id   = get_post_meta( $subscription['product_id'], SharedFunctions::$course_meta_field, true );

		// Update access to the courses
		if ( $courses_id ) {
			//foreach ( $courses_id as $course_id ) {
			$this->remove_course_access( $courses_id, $user_id );
			//}
		}
	}

	/**
	 * @param $order_id
	 */
	public function remove_course( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order instanceof \WC_Order ) {
			return;
		}
		$products = $order->get_items();
		foreach ( $products as $product ) {
			$courses_id = get_post_meta( $product['product_id'], SharedFunctions::$course_meta_field, true );
			if ( $courses_id ) {
				//foreach ( $courses_id as $cid ) {
				$this->remove_course_access( $courses_id, $order->customer_user );
				//}
			}
		}
	}

	/**
	 * @param $order_id
	 */
	public function send_receipt( $order_id ) {

		$order = wc_get_order( $order_id );
		if ( ! $order instanceof \WC_Order ) {
			return;
		}
		if ( ! $order->has_status( 'completed' ) ) {
			return;
		}

		$products = $order->get_items();
		foreach ( $products as $product ) {
			if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
				continue;
			}
			$courses_id = get_post_meta( $product['product_id'], SharedFunctions::$course_meta_field, true );

			if ( ! $courses_id ) {
				continue;
			}

			$cid = $courses_id;
			$this->add_course_access( $cid, $order->get_customer_id() );

			// if WooCommerce subscription plugin enabled
			if ( class_exists( 'WC_Subscriptions' ) ) {
				// If it's a subscription...
				if ( \WC_Subscriptions_Order::order_contains_subscription( $order ) || \WC_Subscriptions_Renewal_Order::is_renewal( $order ) ) {
					$sub_key = \WC_Subscriptions_Manager::get_subscription_key( $order_id, $product['product_id'] );
					if ( $sub_key ) {
						$subscription_r = \WC_Subscriptions_Manager::get_subscription( $sub_key );
						$start_date     = $subscription_r['start_date'];
						update_user_meta( $order->get_customer_id(), 'course_' . $cid . '_access_from', strtotime( $start_date ) );
					}
				}
			}
		}
	}

	/**
	 * Give Access to the course linked to the subscription key
	 *
	 * @param int $user_id User ID
	 * @param string $subscription_key Subscription key
	 *
	 * @link   https://thomaslecoz.com/learndash-with-woocommerce-subscriptions/
	 */
	public function give_course_access_old( $user_id, $subscription_key ) {

		// Get the course ID related to the subscription
		$subscription = \WC_Subscriptions_Manager::get_subscription( $subscription_key );
		$courses_id   = get_post_meta( $subscription['product_id'], SharedFunctions::$course_meta_field, true );
		$start_date   = $subscription['start_date'];
		// Update access to the courses
		if ( $courses_id ) {
			//foreach ( $courses_id as $course_id ) {
			$course_id = $courses_id;
			if ( empty( $user_id ) || empty( $course_id ) ) {
				return;
			}
			$this->add_course_access( $course_id, $user_id );
			// Replace start date to keep the drip feeding working
			update_user_meta( $user_id, 'course_' . $course_id . '_access_from', strtotime( $start_date ) );
			//}
		}
	}

	/**
	 * @param $order
	 */
	public function delete_course_access( $order ) {
		// Get products related to this order
		$products = $order->get_items();

		foreach ( $products as $product ) {
			$courses_id = get_post_meta( $product['product_id'], SharedFunctions::$course_meta_field, true );

			// Update access to the courses
			if ( $courses_id ) {
				$course_id = $courses_id;
				//foreach ( $courses_id as $course_id ) {
				$this->remove_course_access( $course_id, $order->customer_user );
				//}
			}
		}
	}

	/**
	 * @param $order
	 */
	public function give_course_access( $order ) {
		$products   = $order->get_items();
		$start_date = $order->start_date;

		foreach ( $products as $product ) {
			$courses_id = get_post_meta( $product['product_id'], SharedFunctions::$course_meta_field, true );
			// Update access to the courses
			if ( $courses_id ) {
				//foreach ( $courses_id as $course_id ) {
				$course_id = $courses_id;
				if ( empty( $order->customer_user ) || empty( $course_id ) ) {
					return;
				}
				$this->add_course_access( $course_id, $order->customer_user );
				update_user_meta( $order->customer_user, 'course_' . $course_id . '_access_from', strtotime( $start_date ) );
				//}
			}
		}
	}

	/**
	 * @param $order
	 * @param $new_status
	 * @param $old_status
	 */
	public function subscription_on_hold_to_active( $order, $new_status, $old_status ) {
		if ( 'on-hold' !== $old_status || 'active' !== $new_status ) {
			return;
		}

		$this->give_course_access( $order );
	}

	/**
	 * Force user to login when there is a LD course in cart
	 *
	 * @param object $checkout Checkout object
	 */
	public function force_login( $checkout ) {
		$cart = WC()->cart;
		if ( $cart ) {
			$cart_items = $cart->cart_contents;

			if ( $cart_items ) {
				foreach ( $cart_items as $key => $item ) {
					$product      = wc_get_product( $item['data']->get_id() );
					$product_type = (string) $product->get_type();

					if ( ! in_array( $product_type, array( 'group', 'courses', 'license' ) ) ) {
						continue;
					}

					$courses = get_post_meta( $item['data']->get_id(), SharedFunctions::$course_meta_field, true );

					if ( isset( $courses ) || ! empty( $courses ) ) {
						$course = $courses;
						if ( 0 !== $course ) {
							$this->add_front_scripts();
							break;
						}
					}
				}
			}
		}
	}

	/**
	 * Add course access
	 *
	 * @param int $course_id ID of a course
	 * @param int $user_id ID of a user
	 */
	private function add_course_access( $course_id, $user_id ) {
		ld_update_course_access( $user_id, $course_id );
	}

	/**
	 * Add course access
	 *
	 * @param int $course_id ID of a course
	 * @param int $user_id ID of a user
	 */
	private function remove_course_access( $course_id, $user_id ) {
		ld_update_course_access( $user_id, $course_id, $remove = true );
	}

	/**
	 * @desc Remove in all product type
	 */
	public static function wc_remove_all_quantity_fields( $return, $product ) {
		if ( $product instanceof \WC_Product && $product->is_type( 'courses' ) ) {
			return true;
		}

		return $return;
	}
}
