<?php

namespace uncanny_learndash_groups;

/**
 *
 */
class YoastOverrides {

	/**
	 *
	 */
	public function __construct() {
		// Yoast no index
		add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', array( $this, 'exclude_license_products' ) );
	}

	/**
	 * @return int[]|\WP_Post[]
	 */
	public function exclude_license_products() {
		return $this->gather_product_ids();
	}

	/**
	 * @return int[]|\WP_Post[]
	 */
	public function gather_product_ids() {
		return get_posts(
			array(
				'post_type'      => 'product',
				'posts_per_page' => 9999,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => '_uo_custom_buy_product',
						'value'   => 'yes',
						'compare' => 'LIKE',
					),
				),
				'tax_query'      =>
					array(
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => 'license',
							'operator' => 'IN',
						),
					),
			)
		);
	}
}
