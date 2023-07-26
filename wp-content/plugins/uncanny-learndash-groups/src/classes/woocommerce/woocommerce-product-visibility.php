<?php


namespace uncanny_learndash_groups;


/**
 * Class Woo_Product_Visibility
 * @package uncanny_learndash_groups
 */
class Woo_Product_Visibility {
	/**
	 * Woo_Product_Visibility constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'woocommerce_modify_license_product_type' ) );
	}
	/**
	 *
	 */
	public function woocommerce_modify_license_product_type() {
		if ( 'no' === get_option( 'ulgm_license_product_updated', 'no' ) ) {
			$users = new \WP_User_Query( array(
				'role__in' => array(
					'shop_manager',
					'administrator',
				),
			) );

			$results = $users->get_results();

			$user_ids = array();
			foreach ( $results as $result ) {
				$user_ids[] = (int) $result->ID;
			}
			$user_ids = ! empty( $user_ids ) ? $user_ids : PHP_INT_MAX;

			$posts = get_posts( array(
				'post_type'      => 'product',
				'posts_per_page' => 999,
				'author__not_in' => $user_ids,
				'tax_query'      =>
					array(
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => 'license',
							'operator' => 'IN',
						),
					),
			) );

			if ( $posts ) {
				foreach ( $posts as $post ) {
					wp_set_object_terms( $post->ID, array(
						'exclude-from-catalog',
						'exclude-from-search',
					), 'product_visibility' );
				}
			}
			update_option( 'ulgm_license_product_updated', 'yes' );
		}

	}
}
