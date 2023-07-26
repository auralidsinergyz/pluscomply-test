<?php

namespace uncanny_learndash_codes;

use uncanny_learndash_codes\Database;
use uncanny_learndash_codes\LearnDash;

/**
 * Class Woocommerce
 * @package uncanny_learndash_codes
 */
class Woocommerce extends Config {
	/**
	 * @var
	 */
	private static $coupon_id;
	private static $label;
	private static $placeholder;
	private static $error;

	/**
	 * Woocommerce constructor.
	 */
	function __construct() {
		parent::__construct();

		add_action( 'plugins_loaded', [ __CLASS__, 'plugins_loaded' ], 11 );

		if ( is_multisite() ) {
			self::$label       = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_label, __( 'Enter Registration Code', 'uncanny-learndash-codes' ) );
			self::$error       = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_error, __( 'This field is mandatory', 'uncanny-learndash-codes' ) );
			self::$placeholder = get_blog_option( get_current_blog_id(), Config::$uncanny_codes_settings_gravity_forms_placeholder, __( 'Enter Code', 'uncanny-learndash-codes' ) );
		} else {
			self::$label       = get_option( Config::$uncanny_codes_settings_gravity_forms_label, __( 'Enter Registration Code', 'uncanny-learndash-codes' ) );
			self::$error       = get_option( Config::$uncanny_codes_settings_gravity_forms_error, __( 'This field is mandatory', 'uncanny-learndash-codes' ) );
			self::$placeholder = get_option( Config::$uncanny_codes_settings_gravity_forms_placeholder, __( 'Enter Code', 'uncanny-learndash-codes' ) );
		}
	}

	public static function plugins_loaded() {

		if ( class_exists( 'Woocommerce' ) ) {
			// Display Fields
			add_action( 'woocommerce_product_options_general_product_data', array(
				__CLASS__,
				'woo_add_custom_general_fields',
			), 99 );

			// Save Fields
			add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'woo_add_custom_general_fields_save' ) );

			//Add Group Code field after Order Notes
			add_action( 'woocommerce_after_order_notes', array( __CLASS__, 'woo_group_code_field' ) );

			//Process custom field, i.e., verify if the data is correct
			add_action( 'woocommerce_checkout_process', array( __CLASS__, 'woo_group_code_field_process' ), 9999 );

			//Add javascript so that we don't have to reload page
			add_action( 'wp_footer', array( __CLASS__, 'woo_check_group_code' ) );

			//Calculate Fee .. check if paid / unpaid coupon applied
			add_action( 'woocommerce_cart_calculate_fees', array( __CLASS__, 'woo_calculate_fee_again' ) );
			//Save Order Field
			add_action( 'woocommerce_checkout_update_order_meta', array(
				__CLASS__,
				'woo_checkout_field_update_order_meta',
			) );

			//Lets apply coupon and assign user to appropriate group!
			add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'so_payment_complete' ) );
		}

	}

	/**
	 * Add Group Code field on Product Edit Screen
	 */

	public static function woo_add_custom_general_fields() {

		// Checkbox
		woocommerce_wp_checkbox(
			array(
				'id'            => '_enable_group_code',
				'label'         => __( 'Require LearnDash Code', 'uncanny-learndash-codes' ),
				'description'   => __( 'Check to enable registration code field for this product at checkout', 'uncanny-learndash-codes' ),
				'desc_tip'      => 'true',
				'wrapper_class' => '',
			)
		);
	}

	/**
	 * Save Custom Field on Product Eidt Page
	 *
	 * @param $post_id
	 */
	public static function woo_add_custom_general_fields_save( $post_id ) {
		$woocommerce_checkbox = isset( $_POST['_enable_group_code'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_enable_group_code', $woocommerce_checkbox );

	}

	/**
	 * Add Mandatory Group Code field on checkout page
	 * if one of the product in cart has Group Code checkbox
	 * enabled
	 *
	 * @param $checkout
	 */
	public static function woo_group_code_field( $checkout ) {

		$cart        = wc()->cart->get_cart();
		$show_fields = false;
		/**/

		if ( $cart ) {
			foreach ( $cart as $item ) {
				$product_id = $item['product_id'];
				if ( 'yes' === get_post_meta( $product_id, '_enable_group_code', true ) ) {
					$show_fields = true;
					break;
				}
			}
		}
		if ( $show_fields ) {
			echo '<div id="woo_group_code_field"><h2>' . esc_html__( self::$label, 'uncanny-learndash-codes' ) . '</h2>';

			woocommerce_form_field( 'woo_group_code_field', array(
				'type'        => 'text',
				'class'       => array( 'woo-group-code-field-class form-row-wide' ),
				'label'       => esc_html__( self::$label, 'uncanny-learndash-codes' ),
				'placeholder' => esc_html__( self::$placeholder, 'uncanny-learndash-codes' ),
				'required'    => true,
				'clear'       => true,
			), $checkout->get_value( 'woo_group_code_field' ) );

			echo '</div>';

		}
	}

	/**
	 *
	 */
	public static function woo_group_code_field_process() {
		$cart        = wc()->cart->get_cart();
		$show_fields = false;
		/**/

		if ( $cart ) {
			foreach ( $cart as $item ) {
				$product_id = $item['product_id'];
				if ( 'yes' === get_post_meta( $product_id, '_enable_group_code', true ) ) {
					$show_fields = true;
					break;
				}
			}
		}
		if ( $show_fields ) {
			if ( ! $_POST['woo_group_code_field'] ) {

				$notice = sprintf( __( '<strong>%s</strong> is a required field.', 'uncanny-learndash-codes' ),
					self::$label
				);

				if ( ! wc_has_notice( $notice ) ) {
					wc_add_notice( $notice, 'error' );
				}
			} elseif ( ! empty( $_POST['woo_group_code_field'] ) ) {
				$coupon_id = Database::is_coupon_available( trim( $_POST['woo_group_code_field'] ) );
				if ( is_array( $coupon_id ) ) {
					if ( 'failed' === $coupon_id['result'] ) {
						if ( 'max' === $coupon_id['error'] ) {
							if ( ! wc_has_notice( Config::$redeemed_maximum ) ) {
								wc_add_notice( Config::$redeemed_maximum, 'error' );
							}
						} elseif ( 'invalid' === $coupon_id['error'] ) {
							if ( ! wc_has_notice( Config::$invalid_code ) ) {
								wc_add_notice( Config::$invalid_code, 'error' );
							}
						} elseif ( 'expired' === $coupon_id['error'] ) {
							if ( ! wc_has_notice( Config::$expired_code ) ) {
								wc_add_notice( Config::$expired_code, 'error' );
							}
						}
					}
				} elseif ( is_numeric( $coupon_id ) ) {
					self::$coupon_id = intval( $coupon_id );
				} else {
					self::$coupon_id = null;
					if ( ! wc_has_notice( Config::$invalid_code ) ) {
						wc_add_notice( Config::$invalid_code, 'error' );
					}
				}
			}
		}
	}

	/**
	 *
	 */
	public static function woo_check_group_code() {
		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('#woo_group_code_field').change(function () {
                        jQuery('body').trigger('update_checkout')
                    })
                })
            </script>
			<?php
		}
	}

	/**
	 * @param $cart
	 */
	public static function woo_calculate_fee_again( \WC_Cart $cart ) {
		if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
			return;
		}

		if ( isset( $_POST['post_data'] ) ) {
			parse_str( $_POST['post_data'], $post_data );
		} else {
			$post_data = $_POST; // fallback for final checkout (non-ajax)
		}

		//$cart            = wc()->cart->get_cart();
		$show_fields     = false;
		$price_to_deduct = 0;

		if ( count( $cart->cart_contents ) > 0 ) {
			foreach ( $cart->cart_contents as $cart_item_key => $values ) {
				$_product   = $values['data'];
				$product_id = $_product->get_ID();
				if ( 'yes' === get_post_meta( $product_id, '_enable_group_code', true ) ) {
					$show_fields     = true;
					$price_to_deduct = $_product->get_price();
					break;
				}
			}
		}
		unset( $cart_item_key );
		if ( $show_fields ) {
			foreach ( $cart->cart_contents as $cart_item_key => $values ) {
				foreach ( $cart->cart_contents as $cart_item_key => $values ) {
					$_product   = $values['data'];
					$product_id = $_product->get_ID();
					if ( 'yes' === get_post_meta( $product_id, '_enable_group_code', true ) ) {
						$price_to_deduct = $price_to_deduct + $_product->get_price();
					}
				}
			}

			if ( ! empty( $post_data['woo_group_code_field'] ) ) {
				$coupon_id = Database::is_coupon_available( $post_data['woo_group_code_field'] );
				if ( is_array( $coupon_id ) ) {
					if ( 'failed' === $coupon_id['result'] ) {
						if ( 'max' === $coupon_id['error'] ) {
							if ( ! wc_has_notice( Config::$redeemed_maximum ) ) {
								wc_add_notice( Config::$redeemed_maximum, 'error' );
							}
						} elseif ( 'invalid' === $coupon_id['error'] ) {
							if ( ! wc_has_notice( Config::$invalid_code ) ) {
								wc_add_notice( Config::$invalid_code, 'error' );
							}
						} elseif ( 'expired' === $coupon_id['error'] ) {
							if ( ! wc_has_notice( Config::$expired_code ) ) {
								wc_add_notice( Config::$expired_code, 'error' );
							}
						}
					}
				} elseif ( is_numeric( $coupon_id ) ) {
					self::$coupon_id = intval( $coupon_id );
					$is_paid         = Database::is_coupon_paid( $post_data['woo_group_code_field'] );
					if ( $is_paid ) {
						wc()->cart->add_fee( __('Paid Code Applied: ','uncanny-learndash-codes') . $post_data['woo_group_code_field'], ( $price_to_deduct * - 1 ) );
					}
				} else {
					self::$coupon_id = null;
					if ( ! wc_has_notice( Config::$invalid_code ) ) {
						wc_add_notice( Config::$invalid_code, 'error' );
					}
				}
			}
		}
	}

	/**
	 * @param $order_id
	 */
	public static function woo_checkout_field_update_order_meta( $order_id ) {
		if ( ! empty( $_POST['woo_group_code_field'] ) ) {
			update_post_meta( $order_id, 'uncanny-learndash-codes-used-code', $_POST['woo_group_code_field'] );
		}
	}

	/**
	 * @param $order_id
	 */
	public static function so_payment_complete( $order_id ) {
		$order       = new \WC_Order( $order_id );
		$user_id     = (int) $order->user_id;
		$coupon_code = get_post_meta( $order_id, 'uncanny-learndash-codes-used-code', true );
		if ( intval( $user_id ) && ! empty( $coupon_code ) ) {
			$coupon_id = Database::is_coupon_available( $coupon_code );
			if ( is_numeric( $coupon_id ) ) {
				self::$coupon_id = intval( $coupon_id );
			}

			if ( intval( self::$coupon_id ) ) {
				update_user_meta( $user_id, Config::$uncanny_codes_tracking, __('Woocommerce','uncanny-learndash-codes') );

				$result = Database::set_user_to_coupon( $user_id, self::$coupon_id );
				LearnDash::set_user_to_course_or_group( $user_id, $result );
			}
		}
	}
}