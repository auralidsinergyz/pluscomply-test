<?php

namespace uncanny_pro_toolkit;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class learndashBreadcrumbs
 * @package uncanny_custom_toolkit
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
				isset( $active_classes['uncanny_pro_toolkit\CourseAccessExpiry'] ) ||
				isset( $active_classes['uncanny_pro_toolkit\CourseTimer'] ) ||
				isset( $active_classes['uncanny_pro_toolkit\ShowAllCourses'] ) ||
				isset( $active_classes['uncanny_pro_toolkit\LessonTopicGrid'] ) ||
				isset( $active_classes['uncanny_pro_toolkit\learnDashMyCourses'] ) ||
				isset( $active_classes['uncanny_pro_toolkit\LearnDashReset'] ) ||
				isset( $active_classes['uncanny_pro_toolkit\LearnDashTranscript'] ) ||
				isset( $active_classes['uncanny_pro_toolkit\GroupLogoList'] ) ||
				isset( $active_classes['uncanny_pro_toolkit\LearnDashGroupSignUp'] )
			) {
				$add_block_scripts = true;
			}

			// Register Blocks
			add_action( 'init', function () {

				if ( isset( $this->active_classes['uncanny_pro_toolkit\CourseAccessExpiry'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-course-expiry/block.php' );
				}

				if ( isset( $this->active_classes['uncanny_pro_toolkit\CourseTimer'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-uo-time/block.php' );
					require_once( dirname( __FILE__ ) . '/src/toolkit-uo-time-completed/block.php' );
				}

				if ( isset( $this->active_classes['uncanny_pro_toolkit\ShowAllCourses'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-course-grid/block.php' );
				}

				if ( isset( $this->active_classes['uncanny_pro_toolkit\LessonTopicGrid'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-lesson-grid/block.php' );
				}

				if ( isset( $this->active_classes['uncanny_pro_toolkit\learnDashMyCourses'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-my-courses/block.php' );
				}

				if ( isset( $this->active_classes['uncanny_pro_toolkit\LearnDashReset'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-reset-button/block.php' );
				}

				if ( isset( $this->active_classes['uncanny_pro_toolkit\LearnDashTranscript'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-learndash-transcript/block.php' );
				}

				if ( isset( $this->active_classes['uncanny_pro_toolkit\GroupLogoList'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-group-logo-list/block.php' );
				}

				if ( isset( $this->active_classes['uncanny_pro_toolkit\LearnDashGroupSignUp'] ) ) {
					require_once( dirname( __FILE__ ) . '/src/toolkit-group-sign-up/block.php' );
				}
			} );

			if ( $add_block_scripts ) {

				// Enqueue Gutenberg block assets for both frontend + backend
				add_action( 'enqueue_block_assets', function () {
					wp_enqueue_style(
						$this->prefix . '-gutenberg-blocks',
						plugins_url( 'blocks/dist/blocks.style.build.css', dirname( __FILE__ ) ),
						[],
						UNCANNY_TOOLKIT_VERSION
					);
				} );

				// Enqueue Gutenberg block assets for backend editor
				add_action( 'enqueue_block_editor_assets', function () {
					wp_enqueue_script(
						$this->prefix . '-gutenberg-editor',
						plugins_url( 'blocks/dist/blocks.build.js', dirname( __FILE__ ) ),
						[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ],
						UNCANNY_TOOLKIT_VERSION,
						true
					);

					wp_enqueue_style(
						$this->prefix . '-gutenberg-editor',
						plugins_url( 'blocks/dist/blocks.editor.build.css', dirname( __FILE__ ) ),
						[ 'wp-edit-blocks' ],
						UNCANNY_TOOLKIT_VERSION
					);
				} );
			}
		}
	}
}
