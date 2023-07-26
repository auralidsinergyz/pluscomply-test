<?php
/**
 * This class provides the easy way to operate a product (a course or a group).
 *
 * @since 4.6.0
 *
 * @package LearnDash\Core
 */

namespace LearnDash\Core\Models;

use Exception;
use LDLMS_Post_Types;
use LearnDash\Core\Models\Traits\Has_Materials;
use LearnDash_Custom_Label;
use Learndash_Pricing_DTO;
use WP_User;

/**
 * Product model class.
 *
 * @since 4.6.0
 */
class Product extends Post {
	use Has_Materials;

	/**
	 * Returns allowed post types.
	 *
	 * @since 4.5.0
	 *
	 * @return string[]
	 */
	public static function get_allowed_post_types(): array {
		return array(
			LDLMS_Post_Types::get_post_type_slug( LDLMS_Post_Types::COURSE ),
			LDLMS_Post_Types::get_post_type_slug( LDLMS_Post_Types::GROUP ),
		);
	}

	/**
	 *
	 * Returns a product type (buy now, subscription, free, open, closed, etc).
	 *
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function get_pricing_type(): string {
		/**
		 * Filters product pricing type.
		 *
		 * @since 4.5.0
		 *
		 * @param string  $pricing_type Product pricing type.
		 * @param Product $product      Product model.
		 *
		 * @return string Product pricing type.
		 */
		return apply_filters(
			'learndash_model_product_pricing_type',
			$this->get_pricing_settings()['type'] ?? '',
			$this
		);
	}

	/**
	 * Returns if the product price type is open.
	 *
	 * @since 4.6.0
	 *
	 * @return bool
	 */
	public function is_price_type_open(): bool {
		return LEARNDASH_PRICE_TYPE_OPEN === $this->get_pricing_type();
	}

	/**
	 * Returns if the product price type is free.
	 *
	 * @since 4.6.0
	 *
	 * @return bool
	 */
	public function is_price_type_free(): bool {
		return LEARNDASH_PRICE_TYPE_FREE === $this->get_pricing_type();
	}

	/**
	 * Returns if the product price type is paynow.
	 *
	 * @since 4.6.0
	 *
	 * @return bool
	 */
	public function is_price_type_paynow(): bool {
		return LEARNDASH_PRICE_TYPE_PAYNOW === $this->get_pricing_type();
	}

	/**
	 * Returns if the product price type is subscribe.
	 *
	 * @since 4.6.0
	 *
	 * @return bool
	 */
	public function is_price_type_subscribe(): bool {
		return LEARNDASH_PRICE_TYPE_SUBSCRIBE === $this->get_pricing_type();
	}

	/**
	 * Returns if the product price type is closed.
	 *
	 * @since 4.6.0
	 *
	 * @return bool
	 */
	public function is_price_type_closed(): bool {
		return LEARNDASH_PRICE_TYPE_CLOSED === $this->get_pricing_type();
	}

	/**
	 * Returns true when the product has a trial.
	 *
	 * @since 4.6.0
	 *
	 * @return bool
	 */
	public function has_trial(): bool {
		$pricing = $this->get_pricing();

		return $pricing->trial_duration_value > 0 && ! empty( $pricing->trial_duration_length );
	}

	/**
	 * Returns the display price.
	 *
	 * @since 4.6.0
	 *
	 * @return string
	 */
	public function get_display_price(): string {
		$price = strval( $this->get_pricing_settings()['price'] ?? '' );

		/**
		 * Filters product display price.
		 *
		 * @since 4.6.0
		 *
		 * @param string  $display_price Product display price.
		 * @param string  $price         Product price.
		 * @param Product $product       Product model.
		 *
		 * @return string Product display price.
		 */
		return apply_filters(
			'learndash_model_product_display_price',
			$this->get_formatted_display_price( $price ),
			$price,
			$this
		);
	}

	/**
	 * Returns the display trial price.
	 *
	 * @since 4.6.0
	 *
	 * @return string
	 */
	public function get_display_trial_price(): string {
		$trial_price = strval( $this->get_pricing_settings()['trial_price'] ?? '' );

		/**
		 * Filters product display trial price.
		 *
		 * @since 4.6.0
		 *
		 * @param string  $display_trial_price Product display trial price.
		 * @param string  $trial_price         Product trial price.
		 * @param Product $product             Product model.
		 *
		 * @return string Product display trial price.
		 */
		return apply_filters(
			'learndash_model_product_display_trial_price',
			$this->get_formatted_display_price( $trial_price ),
			$trial_price,
			$this
		);
	}

	/**
	 * Returns the formatted display price (with the currency).
	 *
	 * @since 4.6.0
	 *
	 * @param string $price Price.
	 *
	 * @return string
	 */
	private function get_formatted_display_price( string $price ): string {
		$float_price = learndash_get_price_as_float( $price );

		return learndash_get_price_formatted( ! empty( $float_price ) ? $float_price : $price );
	}

	/**
	 * Returns a product type label. Usually "Course" or "Group".
	 *
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function get_type_label(): string {
		/**
		 * Filters product type label.
		 *
		 * @since 4.5.0
		 *
		 * @param string  $type_label Product type label. Course/Group.
		 * @param Product $product    Product model.
		 *
		 * @return string Product type label.
		 */
		return apply_filters(
			'learndash_model_product_type_label',
			LearnDash_Custom_Label::get_label(
				LDLMS_Post_Types::get_post_type_key( $this->post->post_type )
			),
			$this
		);
	}

	/**
	 * Returns a pricing DTO.
	 *
	 * @since 4.5.0
	 *
	 * @param WP_User|null $user If the special pricing for a user is needed.
	 *
	 * @return Learndash_Pricing_DTO
	 */
	public function get_pricing( WP_User $user = null ): Learndash_Pricing_DTO {
		$pricing_settings = $this->get_pricing_settings( $user );

		$pricing = array(
			'currency'              => learndash_get_currency_code(),
			'price'                 => isset( $pricing_settings['price'] )
				? learndash_get_price_as_float( strval( $pricing_settings['price'] ) )
				: 0,
			'recurring_times'       => $pricing_settings['repeats'] ?? 0,
			'duration_value'        => $pricing_settings['interval'] ?? 0,
			'duration_length'       => $pricing_settings['frequency_raw'] ?? '',
			'trial_price'           => isset( $pricing_settings['trial_price'] )
				? learndash_get_price_as_float( strval( $pricing_settings['trial_price'] ) )
				: 0,
			'trial_duration_value'  => $pricing_settings['trial_interval'] ?? 0,
			'trial_duration_length' => $pricing_settings['trial_frequency_raw'] ?? '',
		);

		try {
			$pricing_dto = new Learndash_Pricing_DTO( $pricing );
		} catch ( Exception $e ) {
			$pricing_dto = new Learndash_Pricing_DTO();
		}

		/**
		 * Filters product pricing.
		 *
		 * @since 4.5.0
		 *
		 * @param Learndash_Pricing_DTO $pricing_dto Product Pricing DTO.
		 * @param Product               $product     Product model.
		 *
		 * @return Learndash_Pricing_DTO Product pricing DTO.
		 */
		return apply_filters(
			'learndash_model_product_pricing',
			$pricing_dto,
			$this
		);
	}

	/**
	 * Returns true if a user has access to this product, false otherwise.
	 *
	 * @since 4.5.0
	 *
	 * @param WP_User $user WP_User object.
	 *
	 * @return bool
	 */
	public function user_has_access( WP_User $user ): bool {
		$has_access = false;

		if ( $user->ID > 0 ) {
			if ( learndash_is_course_post( $this->post ) ) {
				$has_access = sfwd_lms_has_access( $this->post->ID, $user->ID );
			} elseif ( learndash_is_group_post( $this->post ) ) {
				$has_access = learndash_is_user_in_group( $user->ID, $this->post->ID );
			}
		}

		/**
		 * Filters whether a user has access to a product.
		 *
		 * @since 4.5.0
		 *
		 * @param bool    $has_access True if a user has access, false otherwise.
		 * @param Product $product    Product model.
		 * @param WP_User $user       User.
		 *
		 * @return bool True if a user has access, false otherwise.
		 */
		return apply_filters( 'learndash_model_product_user_has_access', $has_access, $this, $user );
	}

	/**
	 * Adds access for a user.
	 *
	 * @since 4.5.0
	 *
	 * @param WP_User $user WP_User object.
	 *
	 * @return bool Returns true if it successfully enrolled a user, false otherwise.
	 */
	public function enroll( WP_User $user ): bool {
		$enrolled = false;

		if ( learndash_is_course_post( $this->post ) ) {
			$enrolled = ld_update_course_access( $user->ID, $this->post->ID );
		} elseif ( learndash_is_group_post( $this->post ) ) {
			$enrolled = ld_update_group_access( $user->ID, $this->post->ID );
		}

		/**
		 * Filters whether a user was enrolled to a product.
		 *
		 * @since 4.5.0
		 *
		 * @param bool    $enrolled True if a user was enrolled, false otherwise.
		 * @param Product $product  Product model.
		 * @param WP_User $user     User.
		 *
		 * @return bool True if a user was enrolled, false otherwise.
		 */
		return apply_filters( 'learndash_model_product_user_enrolled', $enrolled, $this, $user );
	}

	/**
	 * Removes access for a user.
	 *
	 * @since 4.5.0
	 *
	 * @param WP_User $user WP_User object.
	 *
	 * @return bool Returns true if it successfully unenrolled a user, false otherwise.
	 */
	public function unenroll( WP_User $user ): bool {
		$unenrolled = false;

		if ( learndash_is_course_post( $this->post ) ) {
			$unenrolled = ld_update_course_access( $user->ID, $this->post->ID, true );
		} elseif ( learndash_is_group_post( $this->post ) ) {
			$unenrolled = ld_update_group_access( $user->ID, $this->post->ID, true );
		}

		/**
		 * Filters whether a user was unenrolled from a product.
		 *
		 * @since 4.5.0
		 *
		 * @param bool    $unenrolled True if a user was unenrolled, false otherwise.
		 * @param Product $product    Product model.
		 * @param WP_User $user       User.
		 *
		 * @return bool True if a user was unenrolled, false otherwise.
		 */
		return apply_filters( 'learndash_model_product_user_unenrolled', $unenrolled, $this, $user );
	}

	/**
	 * Returns whether the product content should be visible.
	 *
	 * @since 4.6.0
	 *
	 * @param WP_User $user User.
	 *
	 * @return bool
	 */
	public function is_content_visible( WP_User $user ): bool {
		$is_content_visible = true;
		$setting_value      = '';

		if ( learndash_is_course_post( $this->post ) ) {
			$setting_value = $this->get_setting( 'course_disable_content_table' );
		} elseif ( learndash_is_group_post( $this->post ) ) {
			$setting_value = $this->get_setting( 'group_disable_content_table' );
		}

		// Only visible to enrolled users.
		if ( 'on' === $setting_value ) {
			$is_content_visible = $this->user_has_access( $user );
		}

		/**
		 * Filters whether a product content should be visible.
		 *
		 * @since 4.6.0
		 *
		 * @param bool    $is_content_visible True if the content should be visible, false otherwise.
		 * @param Product $product            Product model.
		 * @param WP_User $user               User.
		 *
		 * @return bool True if the content should be visible, false otherwise.
		 *
		 * @ignore
		 */
		return apply_filters( 'learndash_model_product_is_content_visible', $is_content_visible, $this, $user );
	}

	/**
	 * Returns formatted post pricing data.
	 *
	 * @since 4.5.0
	 *
	 * @param WP_User|null $user If the special pricing for a user is needed.
	 *
	 * @return array{
	 *     type?: string,
	 *     price?: float|string,
	 *     interval?: int,
	 *     frequency?: string,
	 *     frequency_raw?: string,
	 *     repeats?: int,
	 *     repeat_frequency?: string,
	 *     trial_price?: float,
	 *     trial_interval?: int,
	 *     trial_frequency?: string,
	 *     trial_frequency_raw?: string
	 * }
	 */
	private function get_pricing_settings( WP_User $user = null ): array {
		$pricing_settings = array();

		$user_id = $user ? $user->ID : 0;

		if ( learndash_is_course_post( $this->post ) ) {
			$pricing_settings = learndash_get_course_price( $this->post, $user_id );
		} elseif ( learndash_is_group_post( $this->post ) ) {
			$pricing_settings = learndash_get_group_price( $this->post, $user_id );
		}

		return $pricing_settings;
	}
}
