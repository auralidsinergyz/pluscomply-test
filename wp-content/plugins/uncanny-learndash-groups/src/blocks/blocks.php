<?php

namespace uncanny_learndash_groups;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class learndashBreadcrumbs
 *
 * @package uncanny_learndash_groups
 */
class Blocks {

	/**
	 * Blocks constructor.
	 *
	 */
	public function __construct() {

		// Check if Gutenberg exists
		if ( function_exists( 'register_block_type' ) ) {

			// Register Blocks
			add_action(
				'init',
				function () {
					require_once dirname( __FILE__ ) . '/src/groups-group-management-interface/block.php';
					require_once dirname( __FILE__ ) . '/src/groups-group-reports-interface/block.php';
					require_once dirname( __FILE__ ) . '/src/groups-group-quiz-report/block.php';
					require_once dirname( __FILE__ ) . '/src/groups-woocommerce-buy-courses/block.php';
					require_once dirname( __FILE__ ) . '/src/groups-group-essays-report/block.php';
					require_once dirname( __FILE__ ) . '/src/groups-group-assignments-report/block.php';
					require_once dirname( __FILE__ ) . '/src/groups-group-progress-report/block.php';
					require_once dirname( __FILE__ ) . '/src/groups-group-enrollment-key-redemption/block.php';
					require_once dirname( __FILE__ ) . '/src/groups-group-enrollment-key-registration/block.php';
				}
			);

			// Enqueue Gutenberg block assets for both frontend + backend
			add_action(
				'enqueue_block_assets',
				function () {
					wp_enqueue_style(
						'ulgm-gutenberg-blocks',
						plugins_url( 'blocks/dist/index.css', dirname( __FILE__ ) ),
						array(),
						Utilities::get_version()
					);
				}
			);

			// Enqueue Gutenberg block assets for backend editor
			add_action(
				'enqueue_block_editor_assets',
				function () {
					wp_enqueue_script(
						'ulgm-gutenberg-editor',
						plugins_url( 'blocks/dist/index.js', dirname( __FILE__ ) ),
						array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
						Utilities::get_version(),
						true
					);

					wp_enqueue_style(
						'ulgm-gutenberg-editor',
						plugins_url( 'blocks/dist/index.css', dirname( __FILE__ ) ),
						array( 'wp-edit-blocks' ),
						Utilities::get_version()
					);
				}
			);

			if ( version_compare( get_bloginfo( 'version' ), '5.8', '<' ) ) {
				// Legacy filter
				// Create custom block category
				add_filter(
					'block_categories',
					array(
						$this,
						'block_categories',
					),
					10,
					2
				);
			} else {
				// Create custom block category
				add_filter(
					'block_categories_all',
					array(
						$this,
						'block_categories',
					),
					10,
					2
				);
			}
		}
	}

	/**
	 * @param $categories
	 * @param $post
	 *
	 * @return array
	 */
	public function block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'uncanny-learndash-groups',
					'title' => __( 'Uncanny Groups for LearnDash', 'uncanny-learndash-groups' ),
				),
			)
		);
	}

}
