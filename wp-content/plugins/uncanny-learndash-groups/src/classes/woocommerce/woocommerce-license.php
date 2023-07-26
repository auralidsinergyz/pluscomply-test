<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class WoocommerceLicense
 *
 * @package uncanny_learndash_groups
 */
class WoocommerceLicense {

	/**
	 * @var bool
	 */
	public $deduct_prices = false;
	/**
	 * @var array
	 */
	public static $product_price = array();

	/**
	 * WoocommerceLicense constructor.
	 */
	public function __construct() {
		add_filter(
			'product_type_selector',
			array(
				$this,
				'add_license_product',
			),
			12
		);
		add_filter(
			'product_type_options',
			array(
				$this,
				'add_virtual_and_downloadable_checks',
			)
		);

		add_action( 'admin_head', array( $this, 'woo_admin_head' ) );
		add_action(
			'delete_user',
			array(
				$this,
				'reset_seat_for_deleted_user',
			),
			999
		);
		add_action(
			'plugins_loaded',
			array(
				$this,
				'load_new_product_type',
			),
			1
		);
		add_action(
			'plugins_loaded',
			array(
				$this,
				'woocommerce_order_again_button',
			)
		);
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 200 );
		add_action(
			'woocommerce_thankyou',
			array(
				$this,
				'woocommerce_redirect_to_group',
			),
			1
		);
		add_action(
			'admin_enqueue_scripts',
			array(
				$this,
				'license_custom_js',
			),
			999
		);
		add_action(
			'uo_new_group_purchased',
			array(
				$this,
				'uo_new_group_purchased_func',
			),
			10,
			2
		);

		add_action(
			'pre_get_posts',
			array(
				$this,
				'hide_license_product_from_searches',
			)
		);
		// WP No index
		add_action(
			'wp_robots',
			array(
				$this,
				'manually_add_noindex_to_licenses',
			),
			PHP_INT_MAX
		);
	}

	/**
	 *
	 */
	public function plugins_loaded() {
		add_filter(
			'post_row_actions',
			array(
				$this,
				'remove_row_actions',
			),
			1010,
			2
		);

		add_filter(
			'woocommerce_cart_item_name',
			array(
				$this,
				'woocommerce_cart_item_name_func',
			),
			99,
			3
		);
		add_filter(
			'woocommerce_get_price_html',
			array(
				$this,
				'change_product_price_display',
			),
			70,
			2
		);
		add_filter(
			'woocommerce_cart_item_price',
			array(
				$this,
				'change_product_cart_item_price',
			),
			99,
			3
		);
		add_filter(
			'woocommerce_product_get_price',
			array(
				$this,
				'return_custom_price',
			),
			9,
			2
		);
		add_filter(
			'woocommerce_product_data_tabs',
			array(
				$this,
				'custom_product_tabs',
			)
		);
		add_filter(
			'woocommerce_product_data_tabs',
			array(
				$this,
				'hide_attributes_data_panel',
			)
		);
		add_filter(
			'woocommerce_add_cart_item_data',
			array(
				$this,
				'woo_custom_add_to_cart',
			),
			1500,
			3
		);
		add_filter(
			'woocommerce_cart_item_thumbnail',
			array(
				$this,
				'woocommerce_cart_item_thumbnail_func',
			),
			99,
			3
		);
		add_filter(
			'woocommerce_cart_item_permalink',
			array(
				$this,
				'woocommerce_cart_item_permalink_func',
			),
			99,
			3
		);
		add_filter(
			'woocommerce_order_item_meta_end',
			array(
				$this,
				'woocommerce_order_item_meta_end_func',
			),
			10,
			3
		);
		add_filter(
			'woocommerce_order_item_permalink',
			array(
				$this,
				'woocommerce_order_item_permalink',
			),
			99,
			3
		);
		//add_filter( 'woocommerce_cart_product_subtotal', [ $this, 'woocommerce_cart_product_subtotal' ], 99, 4 );
		/*Message Filter*/
		add_filter(
			'wc_add_to_cart_message_html',
			array(
				$this,
				'custom_add_to_cart_message_html',
			),
			10,
			3
		);

		/************************************/
		add_action(
			'woocommerce_checkout_process',
			array(
				$this,
				'process_group_name',
			),
			30
		);
		add_action(
			'woocommerce_license_add_to_cart',
			array(
				$this,
				'woocommerce_simple_add_to_cart',
			),
			30
		);
		add_action(
			'woocommerce_product_data_panels',
			array(
				$this,
				'license_options_product_tab_content',
			)
		);
		add_action(
			'woocommerce_order_status_completed',
			array(
				$this,
				'process_license_odr_complete',
			),
			99
		);
		//add_action( 'woocommerce_before_calculate_totals', array( $this, 'woo_license_price_update' ), 20 );
		add_action(
			'woocommerce_checkout_update_order_meta',
			array(
				$this,
				'grp_update_order_meta',
			)
		);
		add_action(
			'woocommerce_process_product_meta_license',
			array(
				$this,
				'save_license_option_field',
			)
		);
		add_action(
			'woocommerce_process_product_meta_variable_license',
			array(
				$this,
				'save_license_option_field',
			)
		);
		add_action(
			'woocommerce_admin_order_data_after_billing_address',
			array(
				$this,
				'grp_admin_ordr_billing_address',
			)
		);

		add_action(
			'woocommerce_after_order_notes',
			array(
				$this,
				'create_group_related_fields',
			),
			1001
		);

		/* New Functionality to add courses with licenses*/
		if ( 'yes' === get_option( 'add_courses_as_part_of_license', 'no' ) ) {
			add_action(
				'woocommerce_add_to_cart',
				array(
					$this,
					'woocommerce_add_to_cart_func',
				),
				999
			);
			add_action(
				'woocommerce_before_calculate_totals',
				array(
					$this,
					'deduct_courses_in_cart',
				),
				29,
				1
			);
		}
	}

	/**
	 * @param $query
	 */
	public function hide_license_product_from_searches( $query ) {
		if ( $query->is_search() && $query->is_main_query() ) {
			$tax_query = $query->get( 'tax_query', array() );

			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'exclude-from-catalog',
				'operator' => 'NOT IN',
			);

			$query->set( 'tax_query', $tax_query );

		}

	}

	/**
	 *
	 */
	public function woocommerce_simple_add_to_cart() {
		wc_get_template( 'single-product/add-to-cart/simple.php' );
	}

	/**
	 * @param $message
	 * @param $products
	 * @param $show_qty
	 *
	 * @return string
	 */
	public function custom_add_to_cart_message_html( $message, $products, $show_qty ) {
		$override = get_option( 'ulgm_add_to_cart_message', '' );
		if ( ! empty( $override ) && ulgm_filter_has_var( 'add-seats' ) ) {
			$titles = array();
			$count  = 0;

			if ( ! is_array( $products ) ) {
				$products = array( $products => 1 );
				$show_qty = false;
			}

			if ( ! $show_qty ) {
				$products = array_fill_keys( array_keys( $products ), 1 );
			}
			$found_license = false;
			foreach ( $products as $product_id => $qty ) {
				/* translators: %s: product name */
				$product = wc_get_product( $product_id );
				if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
					$found_license = true;
					break;
				}
			}

			if ( ! $found_license ) {
				return $message;
			}

			foreach ( $products as $product_id => $qty ) {
				/* translators: %s: product name */
				$titles[] = ( $qty > 1 ? absint( $qty ) . ' &times; ' : '' ) . apply_filters( 'woocommerce_add_to_cart_item_name_in_quotes', sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'woocommerce' ), strip_tags( get_the_title( $product_id ) ) ), $product_id );
				$count    += $qty;
			}

			$titles = array_filter( $titles );

			// The custom message is just below
			$added_text = sprintf( str_replace( '{{product}}', '%s', $override ), wc_format_list_of_items( $titles ) );

			// Output success messages
			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				$return_to = apply_filters( 'woocommerce_continue_shopping_redirect', wc_get_raw_referer() ? wp_validate_redirect( wc_get_raw_referer(), false ) : wc_get_page_permalink( 'shop' ) );
				$message   = sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', esc_url( $return_to ), esc_html__( 'Continue shopping', 'woocommerce' ), esc_html( $added_text ) );
			} else {
				$message = sprintf( '%s', esc_html( $added_text ) );
			}
		}

		return $message;
	}

	/**
	 *
	 */
	public function woo_admin_head() {
		if ( ulgm_filter_has_var( 'post' ) && ! empty( ulgm_filter_input( 'post' ) ) ) {
			if ( has_term( 'license', 'product_type', absint( ulgm_filter_input( 'post' ) ) ) ) {
				?>
				<style>
					#delete-action {
						display: none;
					}
				</style>
				<?php
			}
		}
	}

	/**
	 * @param $actions
	 *
	 * @return mixed
	 */
	public function remove_row_actions( $actions, $post ) {
		if ( 'product' === get_post_type() ) {
			if ( has_term( 'license', 'product_type', $post ) ) {
				unset( $actions['trash'] );
			}
		}

		return $actions;
	}

	/**
	 * @param $permalink
	 * @param $item
	 * @param $order
	 *
	 * @return string
	 */
	public function woocommerce_order_item_permalink( $permalink, $item, $order ) {

		$product = wc_get_product( $item['product_id'] );
		if ( $product instanceof \WC_Product && strpos( $product->get_slug(), 'license' ) ) {
			return '';
		} else {
			return $permalink;
		}
	}

	/**
	 *
	 */
	public function woocommerce_order_again_button() {
		remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );
	}

	/**
	 * @param $item_id
	 * @param $item
	 * @param \WC_Order $order
	 */
	public function woocommerce_order_item_meta_end_func( $item_id, $item, \WC_Order $order ) {
		$courses       = '';
		$added_courses = get_post_meta( $item['product_id'], SharedFunctions::$license_meta_field, true );
		$courses_new   = get_post_meta( $item['product_id'], SharedFunctions::$license_meta_field . '_new', true );
		if ( ! empty( $courses_new ) ) {
			foreach ( $courses_new as $new ) {
				$course_names[] = get_the_title( $new );
			}
		} elseif ( ! empty( $added_courses ) ) {
			foreach ( $added_courses as $c ) {
				$course_names[] = get_the_title( $c );
			}
		}
		if ( ! empty( $course_names ) ) {
			$learn_dash_labels = new \LearnDash_Custom_Label();
			$course_label      = $learn_dash_labels::get_label( 'courses' );
			$courses           = sprintf( '<p class="coures-assigned-heading"><strong>%s:</strong> %s</p>', $course_label, join( ', ', $course_names ) );

		}
		echo $courses;
	}

	/**
	 *
	 */
	public function modify_woo_cart_css() {
		if ( is_cart() || is_checkout() ) {
			wp_register_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array(), Utilities::get_version() );
			$user_colors = Utilities::user_colors();
			wp_add_inline_style( 'ulgm-frontend', $user_colors );
			wp_enqueue_style( 'ulgm-frontend', $user_colors );
		}
	}

	/**
	 * @param $permalink
	 * @param $cart_item
	 * @param $cart_item_key
	 *
	 * @return bool
	 */
	public function woocommerce_cart_item_name_func( $title, $cart_item, $cart_item_key ) {
		if ( true === apply_filters( 'ulgm_woocommerce_hide_course_title_in_product', false, $title, $cart_item, $cart_item_key ) ) {
			return $title;
		}
		if ( ! empty( $cart_item ) ) {
			$product = $cart_item['data'];
			if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
				$course_names = array();
				$courses      = get_post_meta( $cart_item['product_id'], SharedFunctions::$license_meta_field, true );
				$courses_new  = get_post_meta( $cart_item['product_id'], SharedFunctions::$license_meta_field . '_new', true );
				if ( ! empty( $courses_new ) ) {
					foreach ( $courses_new as $new ) {
						$course_names[] = get_the_title( $new );
					}
				} else {
					if ( ! empty( $courses ) ) {
						foreach ( $courses as $c ) {
							$course_names[] = get_the_title( $c );
						}
					}
				}
				if ( ! empty( $course_names ) ) {
					$learn_dash_labels = new \LearnDash_Custom_Label();
					$course_label      = $learn_dash_labels::get_label( 'courses' );
					$courses           = sprintf( '<p class="coures-assigned-heading"><strong>%s:</strong> %s</p>', $course_label, join( ', ', $course_names ) );
				}

				return $title . $courses;
			}
		}

		return $title;
	}

	/**
	 * @param $permalink
	 * @param $cart_item
	 * @param $cart_item_key
	 *
	 * @return bool
	 */
	public function woocommerce_cart_item_permalink_func( $permalink, $cart_item, $cart_item_key ) {
		if ( ! empty( $cart_item ) ) {
			$product = $cart_item['data'];
			if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
				return false;
			}
		}

		return $permalink;
	}

	/**
	 * @param $product_get_image
	 * @param $cart_item
	 * @param $cart_item_key
	 *
	 * @return bool
	 */
	public function woocommerce_cart_item_thumbnail_func( $product_get_image, $cart_item, $cart_item_key ) {
		if ( ! empty( $cart_item ) ) {
			$product = $cart_item['data'];
			if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
				return apply_filters( 'ulgm_license_product_thumbnail_in_cart', false, $product_get_image, $product );
			}
		}

		return $product_get_image;
	}

	/**
	 *
	 */
	public function add_hidden_field_css() {
		if ( is_cart() ) {
			?>
			<style>
				.woocommerce table.shop_table th {
					padding: 5px 7.5px;
				}

				th.product-remove {
					width: 7%;
					min-width: 10%;
				}

				th.product-name {
					width: 46%;
					min-width: 10%;
				}

				th.product-price {
					width: 18%;
					min-width: 10%;

				}

				th.product-quantity {
					width: 15%;
					min-width: 10%;
				}

				th.product-subtotal {
					width: 15%;
					min-width: 10%;
				}

				th.product-thumbnail,
				td.product-thumbnail {
					display: none !important;
				}
			</style>
			<?php
		}
		if ( is_checkout() ) {
			?>
			<style>
				.form-uo-hidden {
					display: none !important;
				}
			</style>
			<?php
		}
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
		if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
			$this->check_if_existing_license( $product_id );
		}

		// Do nothing with the data and return
		return $cart_item_data;
	}

	/**
	 * @param $product_id
	 */
	public function check_if_existing_license( $product_id ) {
		$user    = wp_get_current_user();
		$product = wc_get_product( $product_id );
		$cart    = WC()->cart->cart_contents;
		if ( $product ) {
			if ( $cart && $product instanceof \WC_Product && $product->is_type( 'license' ) ) {

				foreach ( $cart as $cart_key => $cart_contents ) {
					$cart_product_id = $cart_contents['product_id'];
					$cart_product    = wc_get_product( $cart_product_id );
					$product_name    = get_the_title( $cart_product_id );
					if ( $product_id !== $cart_product_id && $cart_product instanceof \WC_Product && $cart_product->is_type( 'license' ) ) {
						$this->remove_related_courses( $cart_product_id );
						WC()->cart->remove_cart_item( $cart_key );
						SharedFunctions::remove_transient_cache( 'yes', '', $user->ID );
						wc_add_notice( sprintf( esc_html__( '%s removed from cart. Only one license is allowed.', 'uncanny-learndash-groups' ), $product_name ), 'error' );
					}
				}
			}
		}
	}

	/**
	 * @param $product_id
	 *
	 * @return bool
	 */
	public function check_if_courses_in_subscription_items( $product_id ) {
		$product = wc_get_product( $product_id );
		if ( class_exists( 'WC_Subscriptions_Product' ) && \WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
			$courses = get_post_meta( $product->get_id(), SharedFunctions::$license_meta_field, true );
			if ( ! empty( $courses ) ) {
				return true;
			}
		}

		return false;
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
			include_once Utilities::get_include( 'woocommerce/wc_product_license.php' );
			new \WC_Product_License( 'license' );
		}
	}


	/**
	 * @param $types
	 *
	 * @return mixed
	 */
	public function add_license_product( $types ) {

		// Key should be exactly the same as in the class product_type parameter
		$types['license'] = __( 'LearnDash group license' );

		return $types;

	}

	/**
	 * Show pricing fields for license product.
	 */
	public function license_custom_js() {
		if ( SharedFunctions::load_backend_bundles() ) {
			wp_enqueue_script( 'ulgm-backend', Utilities::get_asset( 'backend', 'bundle.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
		}
	}

	/**
	 * @param $tabs
	 *
	 * @return mixed
	 */
	public function custom_product_tabs( $tabs ) {
		$tabs['license'] = array(
			'label'    => sprintf( __( 'License %s(s)', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ),
			'target'   => 'license_options',
			'priority' => 100,
			'class'    => array(
				'show_if_license',
				'show_if_variable_license',
				'show_if_subscription',
			),
		);

		//$tabs = array_merge( $tabs, $new_tab );

		return $tabs;
	}


	/**
	 *
	 */
	public function license_options_product_tab_content() {
		global $post;
		$license = $this->list_course_products();
		?>
		<div id='license_options' class='panel woocommerce_options_panel'>
			<style>
				.woocommerce_options_panel ._ulgm_license_field .description {
					display: block;
					width: 50%;
					clear: both
				}
			</style>
			<div class='options_group show_if_license show_if_subscription'>
				<?php
				$kb = sprintf( '<a href="%s" target="_blank">%s</a>', 'https://www.uncannyowl.com/knowledge-base/creating-pre-configured-learndash-group-licenses/?utm_medium=uo_groups&utm_campaign=edit_product_page#Creating_a_pre-configured_LearnDash_Group_License_Product_Subscription_payments', __( 'this knowledge base article', 'uncanny-learndash-groups' ) );
				$this->woocommerce_wp_select_multiple(
					array(
						'id'          => SharedFunctions::$license_meta_field,
						'name'        => '_license[]',
						'label'       => sprintf( __( 'Linked %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ),
						'description' => sprintf( __( 'If the box above is empty, first create at least one LearnDash Group %1$s product. See %2$s for details.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ), $kb ),
						'options'     => $license,
					)
				);
				?>
			</div>

		</div>
		<?php
	}


	/**
	 * @param $post_id
	 */
	public function save_license_option_field( $post_id ) {
		if ( ulgm_filter_has_var( '_license', INPUT_POST ) ) {
			update_post_meta( $post_id, SharedFunctions::$license_meta_field, ulgm_filter_input_array( '_license', INPUT_POST ) );
		} else {
			delete_post_meta( $post_id, SharedFunctions::$license_meta_field );
		}
	}

	/**
	 * @return array
	 */
	public function list_course_products() {
		$query_args = array(
			'post_type'      => 'product',
			'posts_per_page' => 999,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'courses',
				),
			),
		);
		$posts      = get_posts( $query_args );
		$license    = array();
		if ( $posts ) {
			foreach ( $posts as $post ) {
				$license[ $post->ID ] = get_the_title( $post->ID );
			}
		}

		return $license;
	}


	/**
	 * @param $tabs
	 *
	 * @return mixed
	 */
	public function hide_attributes_data_panel( $tabs ) {
		// Other default values for 'attribute' are; general, inventory, shipping, linked_product, variations, advanced
		//$tabs['general']['class'][]        = 'show_if_license show_if_variable_license';
		$tabs['attribute']['class'][]      = 'hide_if_license hide_if_variable_license';
		$tabs['linked_product']['class'][] = 'hide_if_license hide_if_variable_license';
		$tabs['variations']['class'][]     = 'hide_if_license hide_if_variable_license';
		$tabs['shipping']['class'][]       = 'hide_if_license hide_if_variable_license';

		return $tabs;

	}

	/**
	 * @param $field
	 */
	public function woocommerce_wp_select_multiple( $field ) {
		global $thepostid, $post, $woocommerce;
		$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
		$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
		$field['value']         = isset( $field['value'] ) ? $field['value'] : ( get_post_meta( $thepostid, $field['id'], true ) ? get_post_meta( $thepostid, $field['id'], true ) : array() );

		echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" class="' . esc_attr( $field['class'] ) . '" multiple="multiple">';

		foreach ( $field['options'] as $key => $value ) {

			echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ) . '>' . esc_html( $value ) . '</option>';

		}

		echo '</select> ';

		if ( ! empty( $field['description'] ) ) {

			if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
				echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
			} else {
				echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}
		}
		echo '</p>';
	}


	/**
	 *
	 * @param $order_id
	 */
	public function process_license_odr_complete( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		if ( ! $order->has_status( 'completed' ) ) {
			return;
		}

		$linked_group_id = (int) get_post_meta( $order_id, '_ulgm_linked_group_id', true );
		if ( 0 < $linked_group_id ) {
			return; // Bailout -- Group was already created for this order.
		}

		$user = $order->get_user();
		if ( ! $user instanceof \WP_User ) {
			return;
		}
		$user_id = $user->ID;

		//New Method
		$transient  = maybe_unserialize( get_post_meta( $order_id, '_ulgm_user_' . $user_id . '_order', true ) );
		$transient2 = maybe_unserialize( get_post_meta( $order_id, '_ulgm_user_buy_courses_' . $user_id . '_order', true ) );
		if ( $transient || $transient2 ) {
			return;
		}

		//Fall back < 3.7 Groups
		$transient  = SharedFunctions::get_transient_cache( '_ulgm_user_' . $user_id . '_order', $user_id, true );
		$transient2 = SharedFunctions::get_transient_cache( '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id, true );
		if ( $transient || $transient2 ) {
			return;
		}

		$product_id = 0;
		$_quantity  = 0;
		$line_items = $order->get_items( 'line_item' );
		if ( ! $line_items ) {
			return;
		}

		$continue = false;
		foreach ( $line_items as $item ) {
			if ( $this->check_if_license_product_in_items( $item['product_id'] ) ) {
				$_quantity  = $item->get_quantity();
				$product_id = $item['product_id'];
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

		$group_title = get_post_meta( $order_id, SharedFunctions::$group_name_field, true );

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

		do_action( 'ulgm_before_license_group_is_inserted', $ld_group_args, $this );
		$group_id = wp_insert_post( $ld_group_args );

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			 $type = 'groups';

			// Get the translation id (trid)
			$trid = 0;

			// Set the desired language
			$language_code = apply_filters( 'wpml_current_language', 'en' );

			// Update the post language info
			$language_args = array(
				'element_id'           => $group_id,
				'element_type'         => 'post_' . $type,
				'trid'                 => $trid,
				'language_code'        => $language_code,
				'source_language_code' => null,
			);

			do_action( 'wpml_set_element_language_details', $language_args );
		}

		$price_type = apply_filters( 'ulgm_new_group_license_order_price_type', 'closed', $group_id );
		update_post_meta( $group_id, '_ld_price_type', $price_type );
		update_post_meta(
			$group_id,
			'_groups',
			array(
				'groups_group_price_type' => $price_type,
			)
		);

		foreach ( $line_items as $line ) {
			$courses_linked = apply_filters( 'ulgm_license_group_courses_linked_in_order', get_post_meta( $line['product_id'], SharedFunctions::$license_meta_field, true ), $group_id, $this );
			if ( $courses_linked ) {
				foreach ( $courses_linked as $course_product ) {
					$course_id = apply_filters( 'ulgm_license_group_linked_course_id', get_post_meta( $course_product, '_ulgm_course', true ), $group_id, $this );
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
		$code_group_id = ulgm()->group_management->add_code_group( $attr );
		$code_group_id = ulgm()->group_management->add_codes( $attr, $codes, $code_group_id );
		update_post_meta( $order_id, SharedFunctions::$code_group_id_meta_key, $code_group_id );
		update_post_meta( $group_id, SharedFunctions::$code_group_id_meta_key, $code_group_id );
		if ( 'yes' === get_post_meta( $product_id, '_uo_custom_buy_product', true ) ) {
			update_post_meta( $group_id, '_uo_custom_group', 'yes' );
		}
		// This is user is the creator or the group and cannot be remove on the frontend group management only
		//update_post_meta( $group_id, 'locked_admin_group_leader', $user->ID );
		ld_update_leader_group_access( $user->ID, $group_id );

		//Setting group leader as a member of the group & using 1 code / qty for it.
		if ( 'yes' !== get_option( 'do_not_add_group_leader_as_member', 'no' ) ) {
			Group_Management_Helpers::add_existing_user( array( 'user_email' => $user->user_email ), true, $group_id, $order_id, SharedFunctions::$redeem_status, false, false, false, true );
		}
		SharedFunctions::remove_transient_cache( 'all' );
		do_action( 'uo_new_group_purchased', $group_id, $user->ID );
		do_action( 'ulgm_after_license_group_is_inserted', $group_id, $ld_group_args, $this );
	}

	/**
	 * @param $order_id
	 */
	public function woocommerce_redirect_to_group( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id    = $order->get_user_id();
		$transient  = maybe_unserialize( get_post_meta( $order_id, '_ulgm_user_' . $user_id . '_order', true ) );
		$transient2 = maybe_unserialize( get_post_meta( $order_id, '_ulgm_user_buy_courses_' . $user_id . '_order', true ) );

		if ( $transient || $transient2 ) {
			return;
		}

		if ( ! $order->has_status( 'completed' ) ) {
			return;
		}

		$line_items = $order->get_items( 'line_item' );
		if ( ! $line_items ) {
			return;
		}

		$continue = false; //Added to remove force redirect unless user overrides by following filter
		$continue = apply_filters( 'uo_redirect_after_checkout', $continue, $order_id );
		if ( true === $continue && ! is_admin() ) {
			$url = SharedFunctions::get_group_management_page_id( true );
			wp_safe_redirect( $url );
			exit;
		}
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
			foreach ( $products as $prod ) {
				$id      = $prod['product_id'];
				$product = wc_get_product( $id );
				if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
					$linked_courses = get_post_meta( $id, SharedFunctions::$license_meta_field, true );
					if ( $linked_courses ) {
						foreach ( $linked_courses as $course ) {
							$course_product = wc_get_product( $course );
							if ( $course_product ) {
								$price = SharedFunctions::get_custom_product_price( $course_product );
								$total = floatval( $total ) + floatval( $price );
							}
						}
					}
					$prod['data']->price = $total;
				}
			}
		}

		return $cart_object;
	}


	/**
	 * @param $price
	 * @param \WC_Product $product
	 *
	 * @return int
	 */
	public function return_custom_price( $price, \WC_Product $product ) {

		if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
			if ( empty( $price ) || 0 === $price ) {
				$get_price = SharedFunctions::get_custom_product_price( $product, true );
				$product->set_price( $get_price );
				$product->set_regular_price( $get_price );
				$product->save();
				WoocommerceBuyCourses::clear_caches( $product );
				$price = $get_price;
			}
		}

		return $price;
	}

	/**
	 * @param $price
	 * @param $product
	 *
	 * @return string
	 */
	public function change_product_price_display( $price, $product ) {
		if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
			$price = $this->add_per_seat_text( $price, $product );
		}

		return $price;
	}

	/**
	 * @param $price
	 * @param $cart_item
	 * @param $cart_item_key
	 *
	 * @return string
	 */
	public function change_product_cart_item_price( $price, $cart_item, $cart_item_key ) {
		if ( ! empty( $cart_item ) ) {
			if ( is_array( $cart_item ) ) {
				$cart_item = $cart_item['data'];
			}

			if ( $cart_item instanceof \WC_Product && $cart_item->is_type( 'license' ) ) {
				$raw_price = wc_price( get_post_meta( $cart_item->get_id(), '_price', true ) );
				$price     = $this->add_per_seat_text( $raw_price, $cart_item );
			}
		}

		return $price;
	}

	/**
	 * @param $price
	 * @param \WC_Product $product
	 *
	 * @return mixed|void
	 */
	public function add_per_seat_text( $price, \WC_Product $product ) {

		// Value can be saved on settings page
		$per_seat_text  = get_option( 'ulgm_per_seat_text', 'Seat' );
		$per_group_text = esc_html__( 'Group', 'uncanny-learndash-groups' );
		//$price         = wc_price( SharedFunctions::get_custom_product_price( $product ) );

		if ( WoocommerceMinMaxQuantity::is_fixed_price_set_for_the_license( $product->get_id() ) ) {
			$price = preg_replace( '/\<\/bdi\>\<\/span\>/', "</bdi> &#47; $per_group_text</span>", $price );
		} else {
			$price = preg_replace( '/\<\/bdi\>\<\/span\>/', "</bdi> &#47; $per_seat_text</span>", $price );
		}

		return apply_filters( 'ulgm_per_seat_text', $price, $product, $per_seat_text );
	}

	/**
	 * @param $checkout
	 */
	public function create_group_related_fields( $checkout ) {
		$show_field = $this->do_show_group_name_field();
		if ( false === $show_field ) {
			return;
		}
		$has_license_product = $this->check_if_license_product_in_cart();
		if ( class_exists( '\uncanny_learndash_groups\WoocommerceLicenseSubscription' ) ) {
			$has_license_subscription = WoocommerceLicenseSubscription::check_if_course_subscription_in_cart();
		} else {
			$has_license_subscription = array( 'status' => false );
		}

		if ( key_exists( 'product_id', $has_license_product ) ) {
			$product_id = $has_license_product['product_id'];
		} elseif ( key_exists( 'product_id', $has_license_subscription ) ) {
			$product_id = $has_license_subscription['product_id'];
		}
		$group_name = get_post_meta( $product_id, '_group_name', true );
		$custom_buy = get_post_meta( $product_id, '_uo_custom_buy_product', true );
		if ( empty( $group_name ) ) {
			$group_name = '';
		}
		if ( 'yes' === $custom_buy ) {
			$classes = array( 'ulgm-woo-group-settings form-row-wide form-uo-hidden' );
			$class   = 'form-uo-hidden';
		} else {
			$classes = array( 'ulgm-woo-group-settings form-row-wide' );
			$class   = '';
		}

		echo '<div id="ulgm-checkout-heading" class="' . $class . '"><h3>' . esc_html__( 'Group settings', 'uncanny-learndash-groups' ) . '</h3>';
		$required        = apply_filters( 'ulgm_group_name_required', true );
		$group_name_args = apply_filters(
			'ulgm_group_name_args',
			array(
				'type'         => 'text',
				'class'        => $classes,
				'label'        => apply_filters( 'ulgm_group_name_text', esc_html__( 'Group Name', 'uncanny-learndash-groups' ) ),
				'placeholder'  => apply_filters( 'ulgm_group_name_placeholder', '' ),
				'maxlength'    => 80,
				'default'      => $group_name,
				'required'     => $required,
				'autocomplete' => false,
				'description'  => false === $required ? __( 'If left blank, the group name will be [First name] [Last name] - [Company name].', 'uncanny-learndash-groups' ) : __( 'Enter your group name.', 'uncanny-learndash-groups' ),
			)
		);

		woocommerce_form_field( 'ulgm_group_name', $group_name_args, $checkout->get_value( 'ulgm_group_name' ) );

		echo '</div>';
	}

	/**
	 * @throws \Exception
	 */
	public function woocommerce_add_to_cart_func() {
		$user_id    = wp_get_current_user()->ID;
		$transient  = SharedFunctions::get_transient_cache( '_ulgm_user_' . $user_id . '_order', $user_id );
		$transient2 = SharedFunctions::get_transient_cache( '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id );

		if ( ! $transient && ! $transient2 ) {
			if ( sizeof( \WC()->cart->get_cart() ) > 0 ) {
				foreach ( \WC()->cart->get_cart() as $cart_item_key => $values ) {
					$_product            = $values['data'];
					$this->deduct_prices = false;
					if ( $_product instanceof \WC_Product && $_product->is_type( 'license' ) ) {
						$found      = true;
						$product_id = $_product->get_id();
						if ( $found && $product_id > 0 ) {
							$courses = get_post_meta( $product_id, SharedFunctions::$license_meta_field, true );
							if ( ! empty( $courses ) ) {
								foreach ( $courses as $course ) {
									if ( ! $this->woo_in_cart( $course ) ) {
										\WC()->cart->add_to_cart( $course, 1 );
									}
								}
							}
							$this->deduct_prices = true;
						}
					}
				}
			}
		}

		if ( $transient2 ) {
			if ( sizeof( \WC()->cart->get_cart() ) > 0 ) {
				foreach ( \WC()->cart->get_cart() as $cart_item_key => $values ) {
					$_product            = $values['data'];
					$this->deduct_prices = false;
					if ( $_product instanceof \WC_Product && $_product->is_type( 'license' ) ) {
						$found      = true;
						$product_id = $_product->get_id();
						if ( $found && $product_id > 0 ) {
							$courses = get_post_meta( $product_id, SharedFunctions::$license_meta_field . '_new', true );
							//$courses = get_post_meta( $product_id, SharedFunctions::$license_meta_field, true );
							if ( ! empty( $courses ) ) {
								foreach ( $courses as $course ) {
									if ( ! $this->woo_in_cart( $course ) ) {
										\WC()->cart->add_to_cart( $course, 1 );
									}
								}
							}
							$this->deduct_prices = true;
						} else {
							$this->deduct_prices = false;
						}
					}
				}
			}
		}
	}

	/**
	 * @param \WC_Cart $cart
	 */
	public function deduct_courses_in_cart( \WC_Cart $cart ) {
		$user_id    = wp_get_current_user()->ID;
		$transient  = SharedFunctions::get_transient_cache( '_ulgm_user_' . $user_id . '_order', $user_id );
		$transient2 = SharedFunctions::get_transient_cache( '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id );
		if ( ! $transient && ! $transient2 ) {
			if ( count( $cart->cart_contents ) > 0 ) {
				foreach ( $cart->cart_contents as $cart_item_key => $values ) {
					$_product = $values['data'];
					if ( $_product instanceof \WC_Product && $_product->is_type( 'license' ) ) {
						$product_id = $_product->get_id();
						$courses    = get_post_meta( $product_id, SharedFunctions::$license_meta_field, true );
						foreach ( $cart->cart_contents as $c_item_key => $_values ) {
							$_product = $_values['data'];
							if ( $_product instanceof \WC_Product && $_product->is_type( 'courses' ) && in_array( $_product->get_id(), $courses ) ) {
								$_product->set_price( 0 );
							}
						}
					}
				}
			}
		}
		if ( $transient2 ) {
			if ( count( $cart->cart_contents ) > 0 ) {
				foreach ( $cart->cart_contents as $cart_item_key => $values ) {
					$_product = $values['data'];
					if ( $_product instanceof \WC_Product && $_product->is_type( 'license' ) ) {
						$product_id = $_product->get_id();
						$courses    = get_post_meta( $product_id, SharedFunctions::$license_meta_field, true );
						foreach ( $cart->cart_contents as $c_item_key => $_values ) {
							$_product = $_values['data'];
							if ( $_product instanceof \WC_Product && $_product->is_type( 'courses' ) && in_array( $_product->get_id(), $courses ) ) {
								$_product->set_price( 0 );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param $product_id
	 *
	 * @return bool
	 */
	public function woo_in_cart( $product_id ) {
		//global $woocommerce;

		foreach ( \WC()->cart->get_cart() as $key => $val ) {
			$_product = $val['data'];

			if ( absint( $product_id ) === absint( $_product->get_id() ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 *
	 */
	public function process_group_name() {
		$show_field = $this->do_show_group_name_field();

		if ( false === $show_field ) {
			return;
		}

		if ( false === apply_filters( 'ulgm_group_name_required', true ) ) {
			return;
		}

		if ( true === apply_filters( 'ulgm_group_name_required', true ) && ! isset( $_POST['ulgm_group_name'] ) ) {
			wc_add_notice( esc_html__( 'Please enter group name.', 'uncanny-learndash-groups' ), 'error' );
		}

		if ( true === apply_filters( 'ulgm_group_name_required', true ) && empty( $_POST['ulgm_group_name'] ) ) {
			wc_add_notice( esc_html__( 'Please enter group name.', 'uncanny-learndash-groups' ), 'error' );
		}
	}

	/**
	 * @return bool
	 */
	public function do_show_group_name_field() {

		$has_license_product = $this->check_if_license_product_in_cart();
		if ( class_exists( '\uncanny_learndash_groups\WoocommerceLicenseSubscription' ) ) {
			$has_license_subscription = WoocommerceLicenseSubscription::check_if_course_subscription_in_cart();
		} else {
			$has_license_subscription = array( 'status' => false );
		}

		// No license / subscription license found. Bail
		if ( ! isset( $has_license_subscription['status'] ) && ! isset( $has_license_product['status'] ) ) {
			return false;
		}

		// No license / subscription license found. Bail
		if ( false === $has_license_product['status'] && false === $has_license_subscription['status'] ) {
			return false;
		}

		/**
		 * If user is not logged in, show field
		 */
		if ( ! is_user_logged_in() ) {
			return true;
		}

		if ( true === $has_license_subscription['status'] && function_exists( 'wcs_cart_contains_resubscribe' ) ) {
			$resub_exists = wcs_cart_contains_resubscribe( WC()->cart );
			// Re-subscription found. Bail
			if ( ! empty( $resub_exists ) ) {
				return false;
			}
		}

		$show_field = true;
		$user_id    = wp_get_current_user()->ID;
		$transient  = SharedFunctions::get_transient_cache( '_ulgm_user_' . $user_id . '_order', $user_id );
		$transient2 = SharedFunctions::get_transient_cache( '_ulgm_user_buy_courses_' . $user_id . '_order', $user_id );
		if ( $transient || $transient2 ) {
			// User is adding new seats / courses. No need to show field
			$show_field = false;
		}

		return $show_field;
	}

	/**
	 * @param $order_id
	 */
	public function grp_update_order_meta( $order_id ) {
		if ( ulgm_filter_has_var( 'ulgm_group_name', INPUT_POST ) && ! empty( trim( ulgm_filter_input( 'ulgm_group_name', INPUT_POST ) ) ) ) {
			$new_group_name = apply_filters( 'ulgm_group_name', sanitize_text_field( ulgm_filter_input( 'ulgm_group_name', INPUT_POST ) ), wc_get_order( $order_id ) );
			update_post_meta( $order_id, SharedFunctions::$group_name_field, $new_group_name );
		} else {
			$show_additional_fields = $this->check_if_license_product_in_order( $order_id );
			if ( $show_additional_fields['status'] ) {
				$order          = wc_get_order( $order_id );
				$new_group_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() . ' ' . $order->get_billing_company();
				$new_group_name = apply_filters( 'ulgm_group_name', $new_group_name, $order );
				update_post_meta( $order_id, SharedFunctions::$group_name_field, sanitize_text_field( $new_group_name ) );
			}
		}
	}

	/**
	 * @param \WC_Order $order
	 */
	public function grp_admin_ordr_billing_address( $order ) {
		$order_id        = $order->get_id();
		$parent_order_id = get_post_meta( $order_id, 'parent_order_id', true );
		if ( ! empty( $parent_order_id ) && is_numeric( $parent_order_id ) ) {
			$order_id = $parent_order_id;
		} else {
			// may be order id exists in another key
			$user = $order->get_customer_id();
			$k    = "_ulgm_user_buy_courses_{$user}_order";
			$meta = get_post_meta( $order_id, $k, true );
			if ( $meta && isset( $meta['order_details']['order_id'] ) && is_numeric( $meta['order_details']['order_id'] ) ) {
				$order_id = absint( $meta['order_details']['order_id'] );
			}
		}
		$ld_group_id = SharedFunctions::get_ld_group_id_from_order_id( $order_id );
		if ( ! empty( $ld_group_id ) && is_numeric( $ld_group_id ) ) {
			$group_name = get_the_title( $ld_group_id );
			$edit_link  = get_edit_post_link( $ld_group_id );
			$group_name = "<a href='{$edit_link}' title='" . esc_html__( 'Linked LearnDash group', 'uncanny-learndash-groups' ) . "'>{$group_name}</a>";
		} else {
			$group_name = get_post_meta( $order_id, SharedFunctions::$group_name_field, true );
		}
		printf( '<p><strong>%s:</strong><br />%s</p>', esc_html__( 'LearnDash group name', 'uncanny-learndash-groups' ), $group_name );
	}


	/**
	 * @return array
	 */
	public function check_if_license_product_in_cart() {
		$items  = \WC()->cart->get_cart();
		$return = array( 'status' => false );
		if ( $items ) {
			foreach ( $items as $item ) {
				if ( isset( $item['data'] ) && ! empty( $item['data'] ) ) {
					$product = $item['data'];
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
		}

		return $return;
	}

	/**
	 * @param $order_id
	 *
	 * @return array
	 */
	public function check_if_license_product_in_order( $order_id ) {
		$order  = wc_get_order( $order_id );
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
	 * @param $product_id
	 *
	 * @return bool
	 */
	public function check_if_license_product_in_items( $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $user_id
	 */
	public function reset_seat_for_deleted_user( $user_id ) {
		$learndash_groups = learndash_get_users_group_ids( $user_id, true );
		if ( $learndash_groups ) {
			foreach ( $learndash_groups as $group_id ) {
				Group_Management_Helpers::remove_user_from_group( $user_id, $group_id );
			}
		}
	}

	/**
	 * @param $group_id
	 * @param $group_leader_id
	 */
	public function uo_new_group_purchased_func( $group_id, $group_leader_id ) {
		// Send New group purchase email, for extra validation we are sending in the user id and getting user data from WP because there may be filters
		$send_email = true;
		if ( 'yes' !== get_option( 'ulgm_send_new_group_purchase_email', 'yes' ) ) {
			$send_email = false;
		}

		if ( $send_email ) {
			// Add group leader/Create group email subject
			$ulgm_send_new_group_purchase_email_subject = SharedVariables::ulgm_new_group_purchase_email_subject();

			// Add group leader/Create group email subject
			$ulgm_send_new_group_purchase_email_body = SharedVariables::ulgm_new_group_purchase_email_body();

			$group_title = get_the_title( $group_id );
			// Send Welcome email, for extra validation we are sending in the user id and getting user data from WP because there may be filters
			Group_Management_Helpers::send_new_group_purchase_email( $group_leader_id, $ulgm_send_new_group_purchase_email_subject, $ulgm_send_new_group_purchase_email_body, $group_title, $group_id );
		}
	}

	/**
	 * @param $product_subtotal
	 * @param $product
	 * @param $quantity
	 * @param $cart
	 *
	 * @return string
	 */
	public function woocommerce_cart_product_subtotal( $product_subtotal, $product, $quantity, $cart ) {
		if ( $product instanceof \WC_Product && $product->is_type( 'license' ) ) {
			$price            = SharedFunctions::get_license_price( $product );
			$total            = $price * $quantity;
			$product_subtotal = wc_price( $total );
		}

		return $product_subtotal;
	}

	/**
	 * @param $options
	 *
	 * @return mixed
	 */
	public function add_virtual_and_downloadable_checks( $options ) {

		if ( isset( $options['virtual'] ) ) {
			$options['virtual']['wrapper_class'] = $options['virtual']['wrapper_class'] . ' show_if_license show_if_courses';
		}

		if ( isset( $options['downloadable'] ) ) {
			$options['downloadable']['wrapper_class'] = $options['downloadable']['wrapper_class'] . ' show_if_license show_if_courses';
		}

		return $options;
	}

	/**
	 * @param $robots
	 *
	 * @return mixed
	 */
	public function manually_add_noindex_to_licenses( $robots ) {
		global $post;
		if ( ! $post instanceof \WP_Post ) {
			return $robots;
		}
		if ( 'product' !== $post->post_type ) {
			return $robots;
		}
		$product = wc_get_product( $post->ID );
		if ( ! $product instanceof \WC_Product ) {
			return $robots;
		}
		if ( ! $product->is_type( 'license' ) ) {
			return $robots;
		}
		if ( empty( get_post_meta( $post->ID, '_uo_custom_buy_product', true ) ) ) {
			return $robots;
		}
		$robots['noindex']  = true;
		$robots['nofollow'] = true;

		// Check if another plugin is adding the `index` and `follow` directives
		if ( isset( $robots['index'] ) ) {
			$robots['index'] = false;
		}

		if ( isset( $robots['follow'] ) ) {
			$robots['follow'] = false;
		}

		return $robots;
	}

}
