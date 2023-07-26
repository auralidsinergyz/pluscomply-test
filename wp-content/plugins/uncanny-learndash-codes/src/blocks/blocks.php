<?php

namespace uncanny_learndash_codes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Blocks
 * @package uncanny_learndash_codes
 */
class Blocks {

	/*
	 * Plugin prefix
	 * @var string
	 */
	public $prefix = '';

	/*
	 * Plugin version
	 * @var string
	 */
	public $version = '';

	/*
	 * Active Classes
	 * @var string
	 */
	public $active_classes = '';

	/**
	 * Blocks constructor.
	 *
	 * @param string $prefix
	 * @param string $version
	 * @param array  $active_classes
	 */
	public function __construct( $prefix = '', $version = '', $active_classes = [] ) {

		$this->prefix         = $prefix;
		$this->version        = $version;
		$this->active_classes = $active_classes;

		$add_block_scripts = false;
		// Check if Gutenberg exists
		if ( function_exists( 'register_block_type' ) ) {

			if (
				class_exists( '\uncanny_learndash_codes\Shortcodes' )
			) {
				$add_block_scripts = true;
			}

			// Register Blocks
			add_action( 'init', function () {

				if ( class_exists( '\uncanny_learndash_codes\Shortcodes' ) ) {
					require_once( dirname( __FILE__ ) . '/src/uo_code_registration/block.php' );
					require_once( dirname( __FILE__ ) . '/src/uo_user_redeem_code/block.php' );
				}
			} );

			if ( $add_block_scripts ) {

				// Enqueue Gutenberg block assets for both frontend + backend
				add_action( 'enqueue_block_assets', function () {
					wp_enqueue_style(
						$this->prefix . '-gutenberg-blocks',
						plugins_url( 'blocks/dist/blocks.style.build.css', dirname( __FILE__ ) ),
						[],
						UNCANNY_LEARNDASH_CODES_VERSION
					);
				} );

				// Enqueue Gutenberg block assets for backend editor
				add_action( 'enqueue_block_editor_assets', function () {
					wp_enqueue_script(
						$this->prefix . '-gutenberg-editor',
						plugins_url( 'blocks/dist/blocks.build.js', dirname( __FILE__ ) ),
						[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ],
						UNCANNY_LEARNDASH_CODES_VERSION,
						true
					);

					wp_enqueue_style(
						$this->prefix . '-gutenberg-editor',
						plugins_url( 'blocks/dist/blocks.editor.build.css', dirname( __FILE__ ) ),
						[ 'wp-edit-blocks' ],
						UNCANNY_LEARNDASH_CODES_VERSION
					);
				} );
				
				// Create custom block category
				add_filter( 'block_categories', function ( $categories, $post ) {
					return array_merge(
						$categories,
						array(
							array(
								'slug'  => 'uncanny-learndash-codes',
								'title' => __( 'Uncanny LearnDash Codes', 'uncanny-learndash-codes' ),
							),
						)
					);
				}, 10, 2 );
			}
		}
	}
}
