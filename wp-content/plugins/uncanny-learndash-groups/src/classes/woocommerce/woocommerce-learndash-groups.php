<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class LearndashGroupsWooCommerce
 *
 * @package uncanny_learndash_groups
 */
class WooCommerceLearndashGroups {

	/**
	 * @var bool
	 */
	public $debug = false;

	/**
	 * WooCommerceLearndashGroups constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_new_product_type' ), 11 );
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 20 );
	}

	/**
	 *
	 */
	public function plugins_loaded() {
		if ( Utilities::if_woocommerce_active() && 'yes' === get_option( 'add_groups_as_woo_products', 'no' ) ) {
			self::setup_constants();

			self::includes();

			add_action( 'admin_init', array( __CLASS__, 'requires_wc' ) );

			// Meta box
			add_filter( 'product_type_selector', array( __CLASS__, 'add_product_type' ), 10, 1 );
			add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'render_group_selector' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'add_scripts' ) );
			add_action( 'save_post', array( __CLASS__, 'store_related_groups' ), 10, 2 );

			// Product variation hooks
			add_action(
				'woocommerce_product_after_variable_attributes',
				array(
					__CLASS__,
					'render_variation_group_selector',
				),
				10,
				3
			);
			add_action( 'woocommerce_save_product_variation', array( __CLASS__, 'store_variation_related_groups' ), 10, 2 );

			// Order hook
			add_action( 'woocommerce_order_status_processing', array( __CLASS__, 'add_group_access' ), 10, 1 );
			add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'add_group_access' ), 10, 1 );
			add_action( 'woocommerce_payment_complete', array( __CLASS__, 'add_group_access' ), 10, 1 );
			add_action( 'woocommerce_order_status_refunded', array( __CLASS__, 'remove_group_access' ), 10, 1 );

			// New hooks for WC subscription
			add_action(
				'woocommerce_subscription_status_cancelled',
				array(
					__CLASS__,
					'remove_subscription_group_access',
				)
			);
			add_action( 'woocommerce_subscription_status_on-hold', array( __CLASS__, 'remove_subscription_group_access' ) );
			add_action( 'woocommerce_subscription_status_expired', array( __CLASS__, 'remove_subscription_group_access' ) );
			add_action( 'woocommerce_subscription_status_active', array( __CLASS__, 'add_subscription_group_access' ) );

			add_action(
				'woocommerce_subscription_renewal_payment_complete',
				array(
					__CLASS__,
					'remove_group_access_on_billing_cycle_completion',
				),
				10,
				2
			);

			// Force user to log in or create account if there is LD group in WC cart
			add_action( 'woocommerce_checkout_init', array( __CLASS__, 'force_login' ), 10, 1 );

			// Remove group increment record if a group unenrolled manually
			add_action( 'learndash_update_group_access', array( $this, 'remove_access_increment_count' ), 10, 4 );

			add_action( 'woocommerce_group_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );

			// Update group access with 0 id for existing users.
			add_action( 'init', array( $this, 'update_users_group_access' ) );
		}
	}

	/**
	 *
	 */
	public function woocommerce_simple_add_to_cart() {
		wc_get_template( 'single-product/add-to-cart/simple.php' );
	}

	/**
	 *
	 */
	public function load_new_product_type() {
		if ( class_exists( '\WC_Product' ) ) {
			include_once Utilities::get_include( 'woocommerce/wc_product_group.php' );
			$product_type = new \WC_Product_Group( 'group' );
		}
	}

	/**
	 *
	 */
	public static function setup_constants() {
		if ( ! defined( 'LEARNDASH_GROUPS_WOOCOMMERCE_VERSION' ) ) {
			define( 'LEARNDASH_GROUPS_WOOCOMMERCE_VERSION', '3.0.0' );
		}

		// Plugin file
		if ( ! defined( 'LEARNDASH_GROUPS_WOOCOMMERCE_FILE' ) ) {
			define( 'LEARNDASH_GROUPS_WOOCOMMERCE_FILE', __FILE__ );
		}

		// Plugin folder path
		if ( ! defined( 'LEARNDASH_GROUPS_WOOCOMMERCE_PLUGIN_PATH' ) ) {
			define( 'LEARNDASH_GROUPS_WOOCOMMERCE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		}

		// Plugin folder URL
		if ( ! defined( 'LEARNDASH_GROUPS_WOOCOMMERCE_PLUGIN_URL' ) ) {
			define( 'LEARNDASH_GROUPS_WOOCOMMERCE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
	}

	/**
	 *
	 */
	public static function includes() {
		include Utilities::get_include( 'class-cron.php' );
		include Utilities::get_include( 'class-tools.php' );
	}

	/**
	 *
	 */
	public static function requires_wc() {
		if ( ! class_exists( 'WooCommerce' ) || version_compare( WC_VERSION, '3.0', '<' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );

			add_action( 'admin_notices', array( __CLASS__, 'upgrade_wc_notice' ) );

			unset( $_GET['activate'] );
		}
	}

	/**
	 *
	 */
	public static function upgrade_wc_notice() {
		?>

		<div class="notice notice-error is-dismissible">
			<p><?php _e( 'LearnDash WooCommerce addon requires WooCommerce 3.0 or above. Please activate and upgrade your WooCommerce. Reactivate this addon again after you activate or upgrade WooCommerce.', 'uncanny-learndash-groups' ); ?></p>
		</div>

		<?php
	}

	/**
	 * @param $types
	 *
	 * @return mixed
	 */
	public static function add_product_type( $types ) {
		$types['group'] = __( 'Group', 'uncanny-learndash-groups' );

		return $types;
	}

	/**
	 *
	 */
	public static function add_scripts() {
		if ( SharedFunctions::load_backend_bundles() ) {
			wp_enqueue_script( 'ulgm-backend', Utilities::get_asset( 'backend', 'bundle.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
		}
	}

	/**
	 *
	 */
	public static function render_group_selector() {
		global $post;

		$groups_options = array( 0 => __( 'No Related Groups', 'uncanny-learndash-groups' ) );

		$groups = self::list_groups();
		if ( ( is_array( $groups ) ) && ( ! empty( $groups ) ) ) {
			$groups_options = $groups_options + $groups;
		}

		echo '<div class="options_group show_if_group show_if_simple">';

		wp_nonce_field( 'save_post', 'uo_wc_nonce' );

		$values = get_post_meta( $post->ID, '_related_group', true );
		if ( ! $values ) {
			$values = array( 0 );
		}

		self::woocommerce_wp_select_multiple(
			array(
				'id'          => '_related_group[]',
				'class'       => 'select short ld_related_groups',
				'label'       => __( 'Related Groups', 'uncanny-learndash-groups' ),
				'options'     => $groups_options,
				'desc_tip'    => true,
				'description' => __( 'You can select multiple groups to sell together holding the SHIFT key when clicking.', 'uncanny-learndash-groups' ),
				'value'       => $values,
			)
		);

		echo '</div>';
	}

	/**
	 * @return array
	 */
	public static function list_groups() {
		global $post;
		$postid = $post->ID;
		query_posts(
			array(
				'post_type'      => 'groups',
				'posts_per_page' => - 1,
			)
		);
		$groups = array();
		while ( have_posts() ) {
			the_post();
			$groups[ get_the_ID() ] = get_the_title();
		}
		wp_reset_query();
		$post = get_post( $postid );

		return $groups;
	}

	/**
	 * Output a select input box.
	 *
	 * @param array $field
	 */
	public static function woocommerce_wp_select_multiple( $field ) {
		global $thepostid, $post;

		$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
		$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
		$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['value']         = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );
		$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
		$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

			foreach ( $field['custom_attributes'] as $attribute => $value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">
			<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

		if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
			echo wc_help_tip( $field['description'] );
		}

		echo '<select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" ' . implode( ' ', $custom_attributes ) . ' multiple="multiple">';

		foreach ( $field['options'] as $key => $value ) {
			$selected = in_array( $key, $field['value'] ) ? 'selected="selected"' : '';
			echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
		}

		echo '</select> ';

		if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
			echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
		}

		echo '</p>';
	}

	/**
	 * @param $id
	 * @param $post
	 */
	public static function store_related_groups( $id, $post ) {

		if ( ! ulgm_filter_has_var( 'uo_wc_nonce', INPUT_POST ) || ! wp_verify_nonce( ulgm_filter_input( 'uo_wc_nonce', INPUT_POST ), 'save_post' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( ! $post->post_type === 'product' ) {
			return;
		}

		if ( ulgm_filter_has_var( '_related_group', INPUT_POST ) && ! empty( ulgm_filter_input_array( '_related_group', INPUT_POST ) ) ) {
			$_related_groups = array();
			if ( is_array( ulgm_filter_input_array( '_related_group', INPUT_POST ) ) && ! empty( ulgm_filter_input_array( '_related_group', INPUT_POST ) ) ) {
				foreach ( ulgm_filter_input_array( '_related_group', INPUT_POST ) as $group_id ) {
					if ( $group_id > 0 ) {
						$_related_groups[] = $group_id;
					}
				}
			}
			if ( ! empty( $_related_groups ) ) {
				update_post_meta( $id, '_related_group', $_related_groups );
			} else {
				delete_post_meta( $id, '_related_group' );
			}
		} else {
			update_post_meta( $id, '_related_group', array() );
		}
	}

	/**
	 * @param $loop
	 * @param $data
	 * @param $variation
	 */
	public static function render_variation_group_selector( $loop, $data, $variation ) {
		$groups_options = array( 0 => __( 'No Related Groups', 'uncanny-learndash-groups' ) );

		$groups = self::list_groups();
		if ( ( is_array( $groups ) ) && ( ! empty( $groups ) ) ) {
			$groups_options = $groups_options + $groups;
		}

		echo '<div class="form-row form-row-full">';

		wp_nonce_field( 'save_post', 'uo_wc_nonce' );

		$values = get_post_meta( $variation->ID, '_related_group', true );
		if ( ! $values ) {
			$values = array( 0 );
		}

		self::woocommerce_wp_select_multiple(
			array(
				'id'          => '_related_group[' . $loop . '][]',
				'class'       => 'select short ld_related_groups_variation',
				'label'       => __( 'Related Groups', 'uncanny-learndash-groups' ),
				'options'     => $groups_options,
				'desc_tip'    => true,
				'description' => __( 'You can select multiple groups to sell together holding the SHIFT key when clicking.', 'uncanny-learndash-groups' ),
				'value'       => $values,
			)
		);

		echo '</div>';
	}

	/**
	 * @param $variation_id
	 * @param $loop
	 */
	public static function store_variation_related_groups( $variation_id, $loop ) {
		if ( ! ulgm_filter_has_var( 'uo_wc_nonce', INPUT_POST ) || ! wp_verify_nonce( ulgm_filter_input( 'uo_wc_nonce', INPUT_POST ), 'save_post' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( ulgm_filter_has_var( '_related_group', INPUT_POST ) && ! empty( ulgm_filter_input_array( '_related_group', INPUT_POST ) ) ) {
			$groups = array();
			foreach ( ulgm_filter_input_array( '_related_group', INPUT_POST ) as $key => $value ) {
				if ( ! empty( $value ) ) {
					foreach ( $value as $group_id ) {
						if ( $group_id > 0 ) {
							$groups[ $key ][] = sanitize_text_field( $group_id );
						}
					}
				}
			}

			if ( ! empty( $groups[ $loop ] ) ) {
				update_post_meta( $variation_id, '_related_group', $groups[ $loop ] );
			} else {
				delete_post_meta( $variation_id, '_related_group' );
			}
		} else {
			update_post_meta( $variation_id, '_related_group', array() );
		}
	}

	/**
	 * Remove group when order is refunded
	 *
	 * @param int $order_id Order ID
	 * @param int $refund_id Refund ID
	 */
	public static function remove_group_access( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( $order !== false ) {
			$products = $order->get_items();

			foreach ( $products as $product ) {
				$groups_id = get_post_meta( $product['product_id'], '_related_group', true );
				if ( $groups_id && is_array( $groups_id ) ) {
					foreach ( $groups_id as $cid ) {
						self::update_remove_group_access( $cid, $order->get_customer_id(), $order_id );
						//delete user groups transient
						$transient_key = 'learndash_user_groups_' . $order->get_customer_id();
						delete_transient( $transient_key );

						//delete user courses transient
						$transient_key = 'learndash_user_courses_' . $order->get_customer_id();
						delete_transient( $transient_key );

						//delete group users transient
						$transient_key = 'learndash_group_users_' . $cid;
						delete_transient( $transient_key );
					}
				}
			}
		}
	}

	/**
	 * Add group access
	 *
	 * @param int $group_id ID of a group
	 * @param int $user_id ID of a user
	 * @param int $order_id ID of an order
	 */
	private static function update_remove_group_access( $group_id, $user_id, $order_id ) {
		self::decrement_group_access_counter( $group_id, $user_id, $order_id );
		$groups = self::get_groups_access_counter( $user_id );

		if ( ! isset( $groups[ $group_id ] ) || empty( $groups[ $group_id ] ) ) {
			ld_update_group_access( $user_id, $group_id, $remove = true );
		}
	}

	/**
	 * Delete enrolled group record from a user
	 *
	 * @param int $group_id ID of a group
	 * @param int $user_id ID of a user
	 * @param int $order_id ID of an order
	 */
	private static function decrement_group_access_counter( $group_id, $user_id, $order_id ) {
		$groups = self::get_groups_access_counter( $user_id );

		if ( isset( $groups[ $group_id ] ) ) {
			$keys = array_keys( $groups[ $group_id ], $order_id );
			if ( is_array( $keys ) ) {
				foreach ( $keys as $key ) {
					unset( $groups[ $group_id ][ $key ] );
				}
			}
		}

		update_user_meta( $user_id, '_learndash_woocommerce_enrolled_groups_access_counter', $groups );
	}

	/**
	 * Get user enrolled group access counter
	 *
	 * @param int $user_id ID of a user
	 *
	 * @return array        Group access counter array
	 */
	private static function get_groups_access_counter( $user_id ) {
		$groups = get_user_meta( $user_id, '_learndash_woocommerce_enrolled_groups_access_counter', true );

		if ( ! empty( $groups ) ) {
			$groups = maybe_unserialize( $groups );
		} else {
			$groups = array();
		}

		return $groups;
	}

	/**
	 * @param $order_id
	 */
	public static function add_group_access( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( $order !== false ) {
			$products = $order->get_items();

			foreach ( $products as $product ) {
				if ( isset( $product['variation_id'] ) && ! empty( $product['variation_id'] ) ) {
					$groups_id = get_post_meta( $product['variation_id'], '_related_group', true );
				} else {
					$groups_id = get_post_meta( $product['product_id'], '_related_group', true );
				}

				if ( $groups_id && is_array( $groups_id ) ) {
					foreach ( $groups_id as $cid ) {
						if ( $cid > 0 ) {
							self::update_add_group_access( $cid, $order->get_customer_id(), $order_id );
							//delete user groups transient
							$transient_key = 'learndash_user_groups_' . $order->get_customer_id();
							delete_transient( $transient_key );

							//delete user courses transient
							$transient_key = 'learndash_user_courses_' . $order->get_customer_id();
							delete_transient( $transient_key );

							//delete group users transient
							$transient_key = 'learndash_group_users_' . $cid;
							delete_transient( $transient_key );
						}
					}
				}
			}
		}
	}

	/**
	 * Add group access
	 *
	 * @param int $group_id ID of a group
	 * @param int $user_id ID of a user
	 */
	private static function update_add_group_access( $group_id, $user_id, $order_id ) {
		self::increment_group_access_counter( $group_id, $user_id, $order_id );

		// check if user already enrolled
		if ( ! self::is_user_enrolled_to_group( $user_id, $group_id ) ) {
			ld_update_group_access( $user_id, $group_id );
		}
	}

	/**
	 * Add enrolled group record to a user
	 *
	 * @param int $group_id ID of a group
	 * @param int $user_id ID of a user
	 * @param int $order_id ID of an order
	 */
	private static function increment_group_access_counter( $group_id, $user_id, $order_id ) {
		$groups = self::get_groups_access_counter( $user_id );

		if ( ! is_array( $groups[ $group_id ] ) ) {
			$groups[ $group_id ] = array();
		}

		if ( ! isset( $groups[ $group_id ] ) || ( isset( $groups[ $group_id ] ) && array_search( $order_id, $groups[ $group_id ] ) === false ) ) {
			// Add order ID to group access counter
			$groups[ $group_id ][] = $order_id;
		}

		update_user_meta( $user_id, '_learndash_woocommerce_enrolled_groups_access_counter', $groups );
	}

	/**
	 * Check if a user is already enrolled to a group
	 *
	 * @param integer $user_id User ID
	 * @param integer $group_id Group ID
	 *
	 * @return boolean            True if enrolled|false otherwise
	 */
	private static function is_user_enrolled_to_group( $user_id = 0, $group_id = 0 ) {
		$enrolled_groups = learndash_get_users_group_ids( $user_id );

		if ( is_array( $enrolled_groups ) && in_array( $group_id, $enrolled_groups ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $order
	 */
	public static function add_subscription_group_access( $order ) {
		if ( ! apply_filters( 'ld_woocommerce_add_subscription_group_access', true, $order, current_filter() ) ) {
			return;
		}

		$products = $order->get_items();
		// $start_date = $order->get_date( 'start_date' );
		$customer_id = $order->get_customer_id();

		foreach ( $products as $product ) {
			if ( isset( $product['variation_id'] ) && ! empty( $product['variation_id'] ) ) {
				$groups_id = get_post_meta( $product['variation_id'], '_related_group', true );
			} else {
				$groups_id = get_post_meta( $product['product_id'], '_related_group', true );
			}

			// Update access to the groups
			if ( $groups_id && is_array( $groups_id ) ) {
				foreach ( $groups_id as $group_id ) {

					if ( $group_id > 0 ) {
						if ( empty( $customer_id ) || empty( $group_id ) ) {

							return;
						}

						self::update_add_group_access( $group_id, $customer_id, $order->get_id() );
						//delete user groups transient
						$transient_key = 'learndash_user_groups_' . $customer_id;
						delete_transient( $transient_key );

						//delete user courses transient
						$transient_key = 'learndash_user_courses_' . $customer_id;
						delete_transient( $transient_key );

						//delete group users transient
						$transient_key = 'learndash_group_users_' . $group_id;
						delete_transient( $transient_key );
					}
				}
			}
		}
	}

	/**
	 * Remove group access when user completes billing cycle
	 *
	 * @param object $subscription Subscription object
	 * @param array $last_order Last order details
	 */
	public static function remove_group_access_on_billing_cycle_completion( $subscription, $last_order ) {
		if ( self::is_group_access_removed_on_subscription_billing_cycle_completion() ) {

			$next_payment_date = $subscription->calculate_date( 'next_payment' );

			// Check if there's no next payment date
			// See calculate_date() in class-wc-subscriptions.php
			if ( 0 == $next_payment_date ) {
				self::remove_subscription_group_access( $subscription );
			}
		}
	}

	/**
	 * Get setting if group access should be removed when user completeng subscription payment billing cycle
	 *
	 * @return boolean
	 */
	public static function is_group_access_removed_on_subscription_billing_cycle_completion() {
		return apply_filters( 'learndash_woocommerce_remove_group_access_on_subscription_billing_cycle_completion', false );
	}

	/**
	 * @param $order
	 */
	public static function remove_subscription_group_access( $order ) {
		if ( ! apply_filters( 'ld_woocommerce_remove_subscription_group_access', true, $order, current_filter() ) ) {
			return;
		}

		// Get products related to this order
		$products = $order->get_items();

		foreach ( $products as $product ) {
			$groups_id = get_post_meta( $product['product_id'], '_related_group', true );
			// Update access to the groups
			if ( isset( $groups_id ) && is_array( $groups_id ) ) {
				foreach ( $groups_id as $group_id ) {
					if ( is_array( $group_id ) && ! empty( $group_id ) ) {
						foreach ( $group_id as $g___id ) {
							self::update_remove_group_access( $g___id, $order->get_customer_id(), $order->get_id() );
						}
					} elseif ( is_numeric( $group_id ) ) {
						self::update_remove_group_access( $group_id, $order->get_customer_id(), $order->get_id() );
					}

					foreach ( $order->get_related_orders() as $o_id ) {
						self::update_remove_group_access( $group_id, $order->get_customer_id(), $o_id );
						//delete user groups transient
						$transient_key = 'learndash_user_groups_' . $order->get_customer_id();
						delete_transient( $transient_key );

						//delete user courses transient
						$transient_key = 'learndash_user_courses_' . $order->get_customer_id();
						delete_transient( $transient_key );

						//delete group users transient
						$transient_key = 'learndash_group_users_' . $group_id;
						delete_transient( $transient_key );
					}
				}
			}
		}
	}

	/**
	 * Force user to login when there is a LD group in cart
	 *
	 * @param object $checkout Checkout object
	 */
	public static function force_login( $checkout ) {
		$cart = WC()->cart;
		if ( ! isset( $cart ) ) {
			return;
		}
		$cart_items = WC()->cart->get_cart_contents();
		if ( empty( $cart_items ) ) {
			return;
		}
		if ( ! is_array( $cart_items ) ) {
			return;
		}

		foreach ( $cart_items as $key => $item ) {

			$product      = wc_get_product( $item['data']->get_id() );
			$product_type = (string) $product->get_type();

			if ( ! in_array( $product_type, array( 'group', 'courses', 'license' ) ) ) {
				continue;
			}

			$groups = get_post_meta( $item['data']->get_id(), '_related_group', true );
			$groups = maybe_unserialize( $groups );

			if ( isset( $groups ) && is_array( $groups ) ) {
				foreach ( $groups as $group ) {
					if ( $group != 0 ) {
						self::add_front_scripts();
						break 2;
					}
				}
			}
		}
	}

	/**
	 *
	 */
	public static function add_front_scripts() {
		wp_enqueue_script( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
	}

	/**
	 * Get all LearnDash groups
	 *
	 * @return object LearnDash group
	 */
	private static function get_learndash_groups() {
		global $wpdb;
		$query = "SELECT posts.* FROM $wpdb->posts posts WHERE posts.post_type = 'groups' AND posts.post_status = 'publish' ORDER BY posts.post_title";

		return $wpdb->get_results( $query, OBJECT );
	}

	/**
	 * Remove group access count if a group unenrolled
	 *
	 * @param int $user_id
	 * @param int $group_id
	 * @param array $access_list
	 * @param bool $remove
	 */
	public function remove_access_increment_count( $user_id, $group_id, $access_list, $remove ) {
		if ( $remove !== true ) {
			return;
		}

		delete_user_meta( $user_id, '_learndash_woocommerce_enrolled_groups_access_counter' );
	}

	/**
	 * Update users access for group id = 0
	 */
	public function update_users_group_access() {
		$is_updated = get_option( '_ulgm_update_users_group_access', '' );
		if ( empty( $is_updated ) ) {
			global $wpdb;
			$query = "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'learndash_group_users_0' ";

			$wpdb->get_results( $query, OBJECT );
			update_option( '_ulgm_update_users_group_access', 'updated' );
		}
	}
}
