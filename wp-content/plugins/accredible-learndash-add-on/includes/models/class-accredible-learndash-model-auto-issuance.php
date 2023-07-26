<?php
/**
 * Accredible LearnDash Add-on auto issuance model class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-model.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-database.php';

if ( ! class_exists( 'Accredible_Learndash_Model_Auto_Issuance' ) ) :
	/**
	 * Accredible LearnDash Add-on auto issuance model class
	 */
	class Accredible_Learndash_Model_Auto_Issuance extends Accredible_Learndash_Model {
		const REQUIRED_FIELDS = array( 'kind', 'post_id', 'accredible_group_id' );
		const KINDS           = array( 'course_completed', 'lesson_completed' );

		/**
		 * Define the DB table name.
		 */
		protected static function table_name() {
			global $wpdb;
			return $wpdb->prefix . Accredible_Learndash_Admin_Database::AUTO_ISSUANCES_TABLE_NAME;
		}

		/**
		 * Validate inserting or updating data.
		 *
		 * @throws Exception Exception containing the validation error message.
		 *
		 * @param array $data Inserting or updating data.
		 * @param int   $id ID of the record.
		 */
		public static function validate( $data, $id = null ) {
			parent::validate( $data, $id );

			$post_id = array_key_exists( 'post_id', $data ) ? $data['post_id'] : null;
			$kind    = array_key_exists( 'kind', $data ) ? $data['kind'] : null;

			if ( ! self::is_kind_valid( $kind ) ) {
				throw new Exception( esc_attr( $kind ) . ' is an invalid kind.' );
			}

			if ( self::is_duplicate( $post_id, $kind, $id ) ) {
				$title = get_the_title( $post_id );
				if ( empty( $title ) ) {
					$title = 'Post ID ' . $post_id;
				}
				throw new Exception( esc_attr( $title ) . ' already has the same kind of auto issuance.' );
			}
		}

		/**
		 * Check if the kind value is valid.
		 *
		 * @param string|null $kind Kind of the record.
		 */
		private static function is_kind_valid( $kind ) {
			return is_null( $kind ) || in_array( $kind, self::KINDS, true );
		}

		/**
		 * Check if the pair of the post_id & the kind is a duplicate.
		 *
		 * @param int|null    $post_id Post ID of the record.
		 * @param string|null $kind Kind of the record.
		 * @param int|null    $id ID of the record.
		 */
		private static function is_duplicate( $post_id, $kind, $id ) {
			$where_array = array();

			// When updating the existing record.
			if ( ! empty( $id ) ) {
				$auto_issuance = static::get_results( "id = $id" )[0];
				if ( empty( $post_id ) ) {
					$post_id = $auto_issuance->post_id;
				}
				if ( empty( $kind ) ) {
					$kind = $auto_issuance->kind;
				}
				array_push( $where_array, "id != $id" );
			}
			array_push( $where_array, "post_id = $post_id" );
			array_push( $where_array, "kind = '$kind'" );

			$duplicate_count = static::get_total_count( join( ' AND ', $where_array ) );
			return $duplicate_count > 0;
		}
	}
endif;
