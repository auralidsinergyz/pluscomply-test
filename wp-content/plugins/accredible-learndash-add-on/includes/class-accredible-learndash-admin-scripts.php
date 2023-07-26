<?php
/**
 * Accredible LearnDash Add-on admin scripts class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;


if ( ! class_exists( 'Accredible_Learndash_Admin_Scripts' ) ) :
	/**
	 * Accredible LearnDash Add-on admin scripts class
	 */
	class Accredible_Learndash_Admin_Scripts {
		/**
		 * Enqueues styles and scripts for front-end.
		 */
		public static function load_resources() {
			wp_enqueue_style(
				'accredible-learndash-admin-theme',
				ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/css/accredible-admin-theme.css',
				array(),
				ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN
			);

			wp_enqueue_style(
				'accredible-learndash-admin-settings',
				ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/css/accredible-admin-settings.css',
				array(),
				ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN
			);

			if ( ! wp_script_is( 'jquery' ) ) {
				wp_enqueue_script( 'jquery' );
			}

			if ( ! wp_script_is( 'jquery-ui-autocomplete' ) ) {
				wp_enqueue_script( 'jquery-ui-autocomplete' );
			}

			if ( ! wp_script_is( 'jquery-ui-dialog' ) ) {
				wp_enqueue_script( 'jquery-ui-dialog' );
			}
		}

		/**
		 * Enqueues ajax scripts for pages.
		 */
		public static function load_page_ajax() {
			wp_enqueue_script(
				'accredible-learndash-common',
				ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/js/accredible-common.js',
				array( 'jquery' ),
				ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN,
				true
			);

			wp_localize_script(
				'accredible-learndash-common',
				'accredibledata',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['page'] ) && 'accredible_learndash_issuance_list' === $_GET['page'] ) {
				self::enqueue_groups_autocomplete();
			}
		}

		/**
		 * Enqueues groups autocomplete.
		 */
		public static function enqueue_groups_autocomplete() {
			wp_enqueue_script(
				'accredible-learndash-groups-autocomplete',
				ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/js/accredible-autocomplete.js',
				array( 'jquery' ),
				ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN,
				true
			);

			wp_localize_script(
				'accredible-learndash-groups-autocomplete',
				'ajaxdata',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}

		/**
		 * Enqueues ajax scripts for pages.
		 */
		public static function load_page_scripts() {
			wp_enqueue_script(
				'accredible-learndash-toast',
				ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/js/accredible-toast.js',
				array( 'jquery' ),
				ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN,
				true
			);

			wp_enqueue_script(
				'accredible-learndash-sidenav',
				ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/js/accredible-sidenav.js',
				array( 'jquery' ),
				ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN,
				true
			);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['page'] ) && 'accredible_learndash_issuance_list' === $_GET['page'] ) {
				wp_enqueue_script(
					'accredible-learndash-dialog',
					ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/js/accredible-dialog.js',
					array( 'jquery' ),
					ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN,
					true
				);
			}
		}

		/**
		 * Admin body class Filter.
		 *
		 * @param string $classes Optional. The admin body CSS classes. Default empty.
		 *
		 * @return string Admin body CSS classes.
		 */
		public static function add_admin_body_class( $classes = '' ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( is_admin() && isset( $_GET['page'] ) && stripos( sanitize_text_field( wp_unslash( $_GET['page'] ) ), 'accredible_learndash' ) !== false ) {
				$classes .= ' accredible-learndash-admin ';
			}

			return $classes;
		}

	}

endif;
