<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class WoocommerceBuyCourses
 *
 * @package uncanny_learndash_groups
 */
class WoocommerceBuyCourses {

	/**
	 * WoocommerceBuyCourses constructor.
	 */
	public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 10 );
			add_shortcode( 'uo_groups_buy_courses', array( $this, 'uo_buy_courses_func' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'uo_group_buy_courses' ), 99 );
			add_action( 'wp', array( $this, 'create_custom_product' ), 100 );
	}

	/**
	 *
	 */
	public function plugins_loaded() {
		add_filter( 'woocommerce_product_get_price', array( $this, 'return_custom_price' ), 111, 2 );

		add_action( 'woocommerce_before_calculate_totals', array( $this, 'woo_license_price_update' ), 111 );
		add_action( 'woocommerce_cart_item_removed', array( $this, 'remove_additional_fields' ), 20, 2 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'modify_existing_license' ), 1001 );
		add_action( 'delete_me_if_i_am_not_purchased', array( $this, 'delete_me_if_i_am_not_purchased' ) );
		add_action(
			'reset_license_courses_back_to_original',
			array(
				$this,
				'reset_license_courses_back_to_original_price',
			)
		);
		add_action( 'woocommerce_before_cart_contents', array( $this, 'add_additional_qty_notice' ), 11, 1 );
		add_action( 'woocommerce_checkout_before_order_review', array( $this, 'add_additional_qty_notice' ), 11, 1 );

	}

	/**
	 *
	 */
	public function uo_group_buy_courses() {
		global $post;

		if ( Utilities::has_shortcode( $post, 'uo_groups_buy_courses' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-buy-courses' ) ) {
			self::enqueue_frontend_assets();
		}
	}

	/**
	 * @since    3.7.5
	 * @author   Agus B.
	 * @internal Saad S.
	 */
	public static function enqueue_frontend_assets() {
		global $post;

		if ( ! empty( $post ) ) {
			wp_register_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array(), Utilities::get_version() );
			$user_colors = Utilities::user_colors();
			wp_add_inline_style( 'ulgm-frontend', $user_colors );
			wp_enqueue_style( 'ulgm-frontend', $user_colors );

			wp_register_script( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.js' ), array( 'jquery' ), Utilities::get_version() );

			$api_setup = array(
				'i18n'        => array(
					'selectAtLeastOne' => sprintf( __( 'Please select at least one %s to continue.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ),
				),
				'formatPrice' => array(
					'template'          => wc_price( 0 ),
					'numberOfDecimals'  => wc_get_price_decimals(),
					'thousandSeparator' => wc_get_price_thousand_separator(),
					'decimalSeparator'  => wc_get_price_decimal_separator(),
				),
			);

			wp_localize_script( 'ulgm-frontend', 'ULG_BuyCourses', $api_setup );

			wp_enqueue_script( 'ulgm-frontend' );
		}
	}

	/**
	 * @param $attr
	 *
	 * @return string
	 */
	public function uo_buy_courses_func( $atts ) {

		$defaults = array(
			'product_cat' => null,
			'product_tag' => null,
			'min_qty'     => null,
			'max_qty'     => null,
		);
		$atts     = shortcode_atts( $defaults, $atts );

		ob_start();

		include Utilities::get_template( 'frontend-uo_groups_buy_courses.php' );

		return ob_get_clean();
	}

	/**
	 * @param $product_id
	 */
	public function delete_me_if_i_am_not_purchased( $product_id ) {
		$has_order_count = SharedFunctions::get_orders_from_product_id( absint( $product_id ) );
		if ( 0 === absint( $has_order_count ) ) {
			//No order found related to this product-id
			wp_delete_post( $product_id, true );
		}
	}

	/**
	 * @param $product_id
	 */
	public function reset_license_courses_back_to_original_price( $product_id ) {
		$last_modified = get_post_meta( $product_id, '_last_modified', true );
		if ( ! empty( $last_modified ) ) {
			$diff = ceil( ( ( time() - $last_modified ) / 60 ) );
			if ( $diff >= 15 ) {
				delete_post_meta( $product_id, SharedFunctions::$license_meta_field . '_new' );
			}
		}
	}

	/**
	 *
	 */
	public function add_additional_qty_notice() {
		$user_id       = wp_get_current_user()->ID;
		$get_transient = SharedFunctions::get_transient_cache( '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id, true );
		if ( ! empty( $get_transient ) ) {
			//$seats = strtolower( get_option( 'ulgm_per_seat_text_plural', __( 'Seats', 'uncanny-learndash-groups' ) ) );
			echo '<p class="woocommerce-message">' . sprintf( __( 'Additional cost of license after adding a new %s.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ) . '</p>';
		}
	}

	/**
	 *
	 */
	public function create_custom_product() {
		if ( ulgm_filter_has_var( '_custom_buy_courses', INPUT_POST ) && wp_verify_nonce( ulgm_filter_input( '_custom_buy_courses', INPUT_POST ), Utilities::get_plugin_name() ) ) {
			if ( ulgm_filter_has_var( 'modify_license', INPUT_POST ) && 'yes' === ulgm_filter_input( 'modify_license', INPUT_POST ) ) {
				$group_id        = absint( ulgm_filter_input( 'group_id', INPUT_POST ) );
				$product_id      = absint( ulgm_filter_input( 'product_id', INPUT_POST ) );
				$new_courses     = ulgm_filter_input_array( '_custom_selected_courses', INPUT_POST );
				$existing_course = get_post_meta( $product_id, SharedFunctions::$license_meta_field, true );
				$courses         = array_merge( $new_courses, $existing_course );
				update_post_meta( $product_id, SharedFunctions::$license_meta_field . '_new', $courses );
				update_post_meta( $product_id, '_ulgm_last_courses', $existing_course );
				update_post_meta( $product_id, '_last_modified', time() );
				$order_details = SharedFunctions::get_product_id_from_group_id( $group_id );
				$all_orders    = SharedFunctions::get_group_leader_all_orders( $group_id, $product_id );
				$existing_qty  = ulgm()->group_management->seat->total_seats( $group_id );
				if ( empty( $existing_qty ) ) {
					$new_qty = 1;
				} else {
					$new_qty = $existing_qty;
				}
				if ( is_user_logged_in() && is_array( $order_details ) ) {
					$user_id    = wp_get_current_user()->ID;
					$order_id   = absint( $order_details['order_id'] );
					$product_id = absint( $order_details['product_id'] );
					$save_data  = array(
						'user_id'       => $user_id,
						'order_details' => $order_details,
						'group_id'      => $group_id,
						'order_id'      => $order_id,
						'all_orders'    => $all_orders,
						'existing_qty'  => $existing_qty,
						'new_qty'       => $new_qty,
					);
					SharedFunctions::remove_transient_cache( 'no', '_ulgm_user_USERID_order', $user_id );
					SharedFunctions::set_transient_cache( '_ulgm_user_buy_courses_USERID_order', $save_data, $user_id );
					if ( function_exists( 'as_schedule_single_action' ) ) {
						as_schedule_single_action( current_time( 'U' ) + 879, 'reset_license_courses_back_to_original', array( $product_id ) );
					} else {
						wp_schedule_single_event( current_time( 'U' ) + 879, 'reset_license_courses_back_to_original', array( $product_id ) );
					}
				}
				//\WC()->cart->empty_cart();
				wp_safe_redirect( SharedFunctions::get_checkout_return_url( $product_id, $new_qty ) );
				exit;

			} else {
				$group_name    = wp_strip_all_tags( ulgm_filter_input( '_custom_group_name', INPUT_POST ) );
				$group_courses = ulgm_filter_input_array( '_custom_selected_courses', INPUT_POST );
				$qty           = (int) ulgm_filter_input( '_custom_qty', INPUT_POST );

				$new_min = isset( $_POST['ulgm_license_min_qty'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['ulgm_license_min_qty'] ) ) : 1; //phpcs:ignore WordPress.Security.NonceVerification.Missing
				$new_max = isset( $_POST['ulgm_license_max_qty'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['ulgm_license_max_qty'] ) ) : 99999; //phpcs:ignore WordPress.Security.NonceVerification.Missing

				if ( is_user_logged_in() ) {
					$user_id = wp_get_current_user()->ID;
				} else {
					$admins = get_users(
						array(
							'role'    => array( 'administrator' ),
							'number'  => 1,
							'orderby' => 'ID',
							'order'   => 'ASC',
							'fields'  => 'ID',
						)
					);
					if ( $admins ) {
						$user_id = array_shift( $admins );
					}
					if ( empty( $user_id ) || ! is_numeric( $user_id ) ) {
						$user_id = 1;
					}
				}
				$new_product_array = apply_filters(
					'ulgm_new_license_product_args',
					array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'post_author'    => $user_id,
						'post_title'     => sprintf( __( '%s - License', 'uncanny-learndash-groups' ), $group_name ),
						'post_content'   => '',
						'post_excerpt'   => '',
						'post_parent'    => 0,
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
					),
					$_POST
				);

				$cat_id = get_option( 'ulgm_group_license_product_cat', '' );
				if ( ! empty( $cat_id ) ) {
					$new_product_array['category_ids'] = absint( $cat_id );
				}

				//Create License product
				$product = self::create_license_product( $new_product_array );
				//Verify if the product created, if not, redirect back to buy courses page
				if ( ! $product || ! $product instanceof \WC_Product_License ) {
					$message = esc_html__( 'Something went wrong. Your license failed to create, please try again.', 'uncanny-learndash-groups' );
					wp_safe_redirect( SharedFunctions::get_buy_courses_page_id( true ) . "?message=$message&group_title=$group_name&courses=" . serialize( $group_courses ) );
					exit;
				}

				//New product ID
				$new_product_id = $product->get_id();

				update_post_meta( $new_product_id, SharedFunctions::$license_meta_field, $group_courses );
				update_post_meta( $new_product_id, '_uo_custom_buy_product', 'yes' );
				update_post_meta( $new_product_id, '_group_name', $group_name );

				if ( ! empty( $new_min ) ) {
					update_post_meta( $new_product_id, 'ulgm_license_min_qty', $new_min );
				}
				if ( ! empty( $new_max ) ) {
					update_post_meta( $new_product_id, 'ulgm_license_max_qty', $new_max );
				}

				if ( function_exists( 'as_schedule_single_action' ) ) {
					as_schedule_single_action( current_time( 'U' ) + DAY_IN_SECONDS, 'delete_me_if_i_am_not_purchased', array( $new_product_id ) );
				} else {
					wp_schedule_single_event( current_time( 'U' ) + DAY_IN_SECONDS, 'delete_me_if_i_am_not_purchased', array( $new_product_id ) );
				}

				delete_transient( '_ulgm_user_' . $user_id . '_order' );
				delete_transient( '_ulgm_user_buy_courses_' . $user_id . '_order' );
				$added = wc()->cart->add_to_cart( $new_product_id, $qty );
				if ( $added ) {
					wp_safe_redirect( wc_get_checkout_url() );
					exit;
				}

				//Something went wrong, redirect back to buy courses page
				$message = esc_html__( 'Something went wrong. Your license failed to create, please try again.', 'uncanny-learndash-groups' );
				wp_safe_redirect( SharedFunctions::get_buy_courses_page_id( true ) . "?message=$message&group_title=$group_name&courses=" . serialize( $group_courses ) );
				exit;
			}
		}
	}

	/**
	 * Create a license type product
	 *
	 * @param $args
	 *
	 * @return false|\WC_Product_License
	 * @throws \WC_Data_Exception
	 */
	public static function create_license_product( $args ) {

		// Get an empty instance of the product object (defining it's type)
		$product = new \WC_Product_License();
		if ( ! $product ) {
			return false;
		}

		// Product name (Title) and slug
		$product->set_name( $args['post_title'] ); // Name (title).
		// Status ('publish', 'pending', 'draft' or 'trash')
		$product->set_status( isset( $args['post_status'] ) ? $args['post_status'] : 'publish' );
		// Visibility ('hidden', 'visible', 'search' or 'catalog')
		$product->set_catalog_visibility( 'hidden' );
		// Featured (boolean)
		$product->set_featured( isset( $args['featured'] ) ? $args['featured'] : false );
		// Virtual (boolean)
		$product->set_virtual( apply_filters( 'ulgm_license_is_virtual', true, $product ) );
		// Downloadable (boolean)
		$product->set_downloadable( apply_filters( 'ulgm_license_is_downloadable', false, $product ) );
		// Sold Individually
		$product->set_sold_individually( apply_filters( 'ulgm_license_is_sold_individually', false, $product ) );
		// Reviews
		$product->set_reviews_allowed( apply_filters( 'ulgm_license_is_reviews_allowed', false, $product ) );

		// Taxes
		if ( get_option( 'woocommerce_calc_taxes' ) === 'yes' ) {
			$product->set_tax_status( isset( $args['tax_status'] ) ? $args['tax_status'] : 'taxable' );
			$product->set_tax_class( isset( $args['tax_class'] ) ? $args['tax_class'] : '' );
		}

		// Attributes et default attributes
		if ( isset( $args['attributes'] ) ) {
			$product->set_attributes( self::wc_prepare_product_attributes( apply_filters( 'ulgm_license_attributes', $args['attributes'], $product ) ) );
		}
		if ( isset( $args['default_attributes'] ) ) {
			$product->set_default_attributes( apply_filters( 'ulgm_license_default_attributes', $args['default_attributes'], $product ) );
		} // Needs a special formatting

		// Product categories and Tags
		if ( isset( $args['category_ids'] ) ) {
			$product->set_category_ids( apply_filters( 'ulgm_license_category_ids', array( $args['category_ids'] ), $product ) );
		}

		if ( isset( $args['tag_ids'] ) ) {
			$product->set_tag_ids( apply_filters( 'ulgm_license_tag_ids', array( $args['tag_ids'] ), $product ) );
		}

		## --- SAVE PRODUCT --- ##
		$product->save();

		//Set price
		$get_price = SharedFunctions::get_custom_product_price( $product, true );
		$product->set_price( $get_price );
		$product->set_regular_price( $get_price );

		return $product;
	}

	/**
	 * Utility function that prepare product attributes before saving
	 *
	 * @param $attributes
	 *
	 * @return array
	 */
	public static function wc_prepare_product_attributes( $attributes ) {

		$data     = array();
		$position = 0;

		foreach ( $attributes as $taxonomy => $values ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			// Get an instance of the WC_Product_Attribute Object
			$attribute = new \WC_Product_Attribute();

			$term_ids = array();

			// Loop through the term names
			foreach ( $values['term_names'] as $term_name ) {
				if ( term_exists( $term_name, $taxonomy ) ) { // Get and set the term ID in the array from the term name
					$term_ids[] = get_term_by( 'name', $term_name, $taxonomy )->term_id;
				} else {
					continue;
				}
			}

			$taxonomy_id = wc_attribute_taxonomy_id_by_name( $taxonomy ); // Get taxonomy ID

			$attribute->set_id( $taxonomy_id );
			$attribute->set_name( $taxonomy );
			$attribute->set_options( $term_ids );
			$attribute->set_position( $position );
			$attribute->set_visible( $values['is_visible'] );
			$attribute->set_variation( $values['for_variation'] );

			$data[ $taxonomy ] = $attribute; // Set in an array

			$position ++; // Increase position
		}

		return $data;
	}

	/**
	 * @param $product
	 */
	public static function clear_caches( $product ) {
		wc_delete_product_transients( $product->get_id() );
		if ( $product->get_parent_id( 'edit' ) ) {
			wc_delete_product_transients( $product->get_parent_id( 'edit' ) );
			\WC_Cache_Helper::invalidate_cache_group( 'product_' . $product->get_parent_id( 'edit' ) );
		}
		\WC_Cache_Helper::invalidate_attribute_count( array_keys( $product->get_attributes() ) );
		\WC_Cache_Helper::invalidate_cache_group( 'product_' . $product->get_id() );

	}

	/**
	 * @param $cart_object
	 *
	 * @return mixed
	 */
	public function woo_license_price_update( $cart_object ) {
		$total    = 0;
		$products = $cart_object->cart_contents;

		if ( $products ) {
			foreach ( $products as $cart_item_key => $prod ) {
				$id                = $prod['product_id'];
				$product           = wc_get_product( $id );
				$is_being_modified = get_post_meta( $id, '_last_modified', true );
				if ( ! empty( $is_being_modified ) ) :
					$diff = ceil( ( ( time() - $is_being_modified ) / 60 ) / 60 );

					if ( $product instanceof \WC_Product && $product->is_type( 'license' ) && $diff < 3 ) {
						$additional_courses = $this->get_additional_courses( $id );
						if ( $additional_courses ) {
							foreach ( $additional_courses as $course ) {
								$course_product = wc_get_product( $course );
								if ( $course_product ) {
									//$price = $course_product->get_price();
									$price = SharedFunctions::get_custom_product_price( $course_product );
									$total = floatval( $total ) + floatval( $price );
								}
							}
						}

						$prod['data']->price = $total;
					}
				endif;
			}
		}

		return $cart_object;
	}

	/**
	 * @param $product_id
	 *
	 * @return int
	 */
	public function get_previous_cost_of_license( $product_id ) {
		$linked_courses = get_post_meta( $product_id, SharedFunctions::$license_meta_field, true );
		$total          = 0;
		if ( $linked_courses ) {
			foreach ( $linked_courses as $course ) {
				$course_product = wc_get_product( $course );
				if ( $course_product ) {
					//$price = $course_product->get_price();
					$price = SharedFunctions::get_custom_product_price( $course_product );
					$total = floatval( $total ) + floatval( $price );
				}
			}
		}

		return $total;
	}

	/**
	 * @param             $price
	 * @param \WC_Product $product
	 *
	 * @return int
	 */
	public function return_custom_price( $price, \WC_Product $product ) {
		$id                = $product->get_id();
		$is_being_modified = get_post_meta( $id, '_last_modified', true );
		if ( ! empty( $is_being_modified ) ) :
			$diff = ceil( ( ( time() - $is_being_modified ) / 60 ) / 60 );
			if ( $product instanceof \WC_Product && $product->is_type( 'license' ) && $diff < 3 ) {
				$additional_courses = $this->get_additional_courses( $id );
				if ( $additional_courses ) {
					$price = 0;
					foreach ( $additional_courses as $course ) {
						$course_product = wc_get_product( $course );
						if ( $course_product ) {
							$price_wt = SharedFunctions::get_custom_product_price( $course_product );
							$price    = floatval( $price ) + floatval( $price_wt );
						}
					}

					return $price;
				}
			}
		endif;

		return $price;
	}

	/**
	 * @param \WC_Cart $cart
	 */
	public function calculate_cart_discounts_modify_group( \WC_Cart $cart ) {
		$user_id       = wp_get_current_user()->ID;
		$existing_data = SharedFunctions::get_transient_cache( '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id );
		if ( $existing_data && count( $cart->cart_contents ) > 0 ) {

			foreach ( $cart->cart_contents as $cart_item_key => $values ) {
				$_product     = $values['data'];
				$license_cost = $this->get_previous_cost_of_license( $_product->get_ID() );
				if ( ! empty( $license_cost ) ) {
					$discount = $license_cost * $existing_data['existing_qty'];
					if ( $discount > 0 ) {
						$seats = strtolower( get_option( 'ulgm_per_seat_text_plural', __( 'Seats', 'uncanny-learndash-groups' ) ) );
						\WC()->cart->add_fee( __( sprintf( 'Credit for existing %s', $seats ), 'uncanny-learndash-groups' ), - $discount );
					}
				}
			}
		}
	}

	/**
	 * @param $cart_item_key
	 * @param $cart
	 */
	public function remove_additional_fields( $cart_item_key, $cart ) {
		if ( ! empty( $cart ) ) {
			$cart_contents = $cart->removed_cart_contents;
			foreach ( $cart_contents as $key => $item ) {
				if ( $cart_item_key === $key ) {
					$product_id = $item['product_id'];
					if ( 'yes' === get_post_meta( $product_id, '_uo_custom_buy_product', true ) ) {
						delete_post_meta( $product_id, '_last_modified' );
						delete_post_meta( $product_id, SharedFunctions::$license_meta_field . '_new' );
					}
				}
			}
		}
		SharedFunctions::remove_transient_cache( 'yes' );
	}

	/**
	 * @param $product_id
	 *
	 * @return array
	 */
	public function get_additional_courses( $product_id ) {
		$existing_courses = get_post_meta( $product_id, SharedFunctions::$license_meta_field, true );
		$linked_courses   = get_post_meta( $product_id, SharedFunctions::$license_meta_field . '_new', true );
		if ( is_array( $existing_courses ) && is_array( $linked_courses ) ) {
			return array_diff( $linked_courses, $existing_courses );
		}

		return array();
	}

	/**
	 *
	 */
	public function add_inline_js() {
		?>
		<script>
			jQuery('.product-quantity .quantity input').attr('disabled', 'disabled')
		</script>
		<?php
	}

	/**
	 * @param $order_id
	 */
	public function modify_existing_license( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id = $order->get_user_id();

		$transient = maybe_unserialize( get_post_meta( $order_id, '_ulgm_user_buy_courses_' . $user_id . '_order', true ) );

		if ( ! $transient ) {
			//Fall back < 3.7 Groups
			$transient2 = SharedFunctions::get_transient_cache( '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id );
			if ( ! $transient2 ) {
				return;
			} else {
				$transient = $transient2;
			}
		}

		if ( ! $order->has_status( 'completed' ) ) {
			return;
		}

		$line_items = $order->get_items();

		if ( ! $line_items ) {
			return;
		}

		$group_id      = absint( $transient['group_id'] );
		$product_id    = absint( $transient['order_details']['product_id'] );
		$old_order_id  = absint( $transient['order_details']['order_id'] );
		$code_group_id = absint( $transient['order_details']['code_group_id'] );

		if ( 'yes' === (string) get_post_meta( $product_id, '_uo_custom_buy_product', true ) ) {
			$old_courses = get_post_meta( $product_id, SharedFunctions::$license_meta_field, true );
			update_post_meta( $product_id, '_ulgm_last_courses', $old_courses );
			$new_courses = get_post_meta( $product_id, SharedFunctions::$license_meta_field . '_paid', true );
			update_post_meta( $product_id, SharedFunctions::$license_meta_field, $new_courses );
			update_post_meta( $order_id, SharedFunctions::$code_group_id_meta_key, $code_group_id );
			update_post_meta( $order_id, 'parent_order_id', $old_order_id );
			$new_course_titles = array();
			if ( $new_courses ) {
				$existing_c = learndash_group_enrolled_courses( $group_id, true );
				foreach ( $new_courses as $n_c ) {
					$c_id = get_post_meta( $n_c, '_ulgm_course', true );
					if ( ! in_array( $c_id, $existing_c ) ) {
						$new_course_titles[] = get_the_title( $c_id );
					}
				}
			}
			update_post_meta( $order_id, 'order_type', sprintf( __( '%1$s %2$s(s) added to the group.', 'uncanny-learndash-groups' ), join( ', ', $new_course_titles ), strtolower( \LearnDash_Custom_Label::get_label( 'course' ) ) ) );
			delete_post_meta( $product_id, SharedFunctions::$license_meta_field . '_paid' );
			SharedFunctions::remove_transient_cache( 'no', '_ulgm_user_buy_courses_USERID_order', $user_id );

			if ( $new_courses ) {
				foreach ( $new_courses as $course_product ) {
					$course_id = get_post_meta( $course_product, '_ulgm_course', true );
					ld_update_course_group_access( (int) $course_id, (int) $group_id, false );
					$transient_key = 'learndash_course_groups_' . $course_id;
					delete_transient( $transient_key );
				}
			}
			$product = wc_get_product( $product_id );
			if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
				$price = SharedFunctions::get_custom_product_price( $product, true );
				$product->set_regular_price( $price );
				$product->set_price( $price );
				$product->save();
			}
		}
	}
}
