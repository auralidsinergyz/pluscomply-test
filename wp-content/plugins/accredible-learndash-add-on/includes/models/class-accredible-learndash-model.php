<?php
/**
 * Accredible LearnDash Add-on model class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Model' ) ) :
	/**
	 * Accredible LearnDash Add-on model class
	 */
	abstract class Accredible_Learndash_Model {
		const DEFAULT_PAGE_SIZE = 50;
		const REQUIRED_FIELDS   = array();

		/**
		 * Return a list of DB records.
		 *
		 * @param string $where_sql SQL where clause.
		 * @param int    $limit Limit value.
		 * @param int    $offset Offset value.
		 * @param array  $options SQL options.
		 */
		public static function get_results( $where_sql = '', $limit = null, $offset = null, $options = array() ) {
			global $wpdb;
			$sql = 'SELECT * FROM ' . static::table_name();
			if ( ! empty( $where_sql ) ) {
				$sql .= " WHERE $where_sql";
			}
			if ( isset( $options['order_by'] ) ) {
				$sql .= ' ORDER BY ' . $options['order_by'];
			}
			if ( ! empty( $limit ) ) {
				$sql .= " LIMIT $limit";
			}
			if ( ! empty( $offset ) ) {
				$sql .= " OFFSET $offset";
			}
			// XXX `$where_sql` is a raw SQL so `$wpdb->prepare` cannot be used.
			return $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		/**
		 * Return a DB record.
		 *
		 * @param string $where_sql SQL where clause.
		 */
		public static function get_row( $where_sql = '' ) {
			global $wpdb;
			$sql = 'SELECT * FROM ' . static::table_name();
			if ( ! empty( $where_sql ) ) {
				$sql .= " WHERE $where_sql";
			}

			// XXX `$where_sql` is a raw SQL so `$wpdb->prepare` cannot be used.
			return $wpdb->get_row( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		/**
		 * Return the total count of the records.
		 *
		 * @param string $where_sql SQL where clause.
		 */
		public static function get_total_count( $where_sql = '' ) {
			global $wpdb;
			$sql = 'SELECT COUNT(*) FROM ' . static::table_name();
			if ( ! empty( $where_sql ) ) {
				$sql .= " WHERE $where_sql";
			}
			// XXX `$where_sql` is a raw SQL so `$wpdb->prepare` cannot be used.
			return $wpdb->get_var( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		/**
		 * Return paginated results.
		 *
		 * @param int    $page_num The current page number.
		 * @param int    $page_size Page size.
		 * @param string $where_sql SQL where clause.
		 * @param array  $options SQL options.
		 */
		public static function get_paginated_results( $page_num, $page_size, $where_sql = '', $options = array() ) {
			if ( empty( $page_size ) ) {
				$page_size = static::DEFAULT_PAGE_SIZE;
			}
			$current_page = empty( $page_num ) ? 1 : $page_num;
			$offset       = $page_size * ( $current_page - 1 );

			$results     = static::get_results( $where_sql, $page_size, $offset, $options );
			$total_count = static::get_total_count( $where_sql );
			$total_pages = ceil( $total_count / $page_size );
			$next_page   = $current_page < $total_pages ? $current_page + 1 : null;
			$prev_page   = $current_page > 1 ? $current_page - 1 : null;

			return array(
				'results' => $results,
				'meta'    => array(
					'current_page' => $current_page,
					'next_page'    => $next_page,
					'prev_page'    => $prev_page,
					'total_pages'  => $total_pages,
					'total_count'  => $total_count,
					'page_size'    => $page_size,
				),
			);
		}

		/**
		 * Insert a new record to the DB table.
		 *
		 * @param array $data Inserting data.
		 */
		public static function insert( $data ) {
			$data['created_at'] = time();
			static::validate( $data );
			global $wpdb;
			$wpdb->insert( static::table_name(), $data );
			$insert_id = $wpdb->insert_id;
			return $insert_id;
		}

		/**
		 * Update a record from DB table.
		 *
		 * @param int   $id ID of model to update.
		 * @param array $data Updating data.
		 */
		public static function update( $id, $data ) {
			static::validate( $data, $id );
			global $wpdb;
			return $wpdb->update( static::table_name(), $data, array( 'id' => $id ) );
		}

		/**
		 * Delete a record from the DB table.
		 *
		 * @param int $id ID.
		 */
		public static function delete( $id ) {
			global $wpdb;
			return $wpdb->delete( static::table_name(), array( 'id' => $id ), array( '%d' ) );
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
			foreach ( static::REQUIRED_FIELDS as $field ) {
				if ( null === $id ) {
					if ( ! isset( $data[ $field ] ) || '' === trim( $data[ $field ] ) ) {
						throw new Exception( esc_attr( $field ) . ' is a required field.' );
					}
				} else {
					if ( array_key_exists( $field, $data ) && ( null === $data[ $field ] || '' === trim( $data[ $field ] ) ) ) {
						throw new Exception( esc_attr( $field ) . ' is a required field.' );
					}
				}
			}
		}

		/**
		 * Define the DB table name in the sub class.
		 */
		abstract protected static function table_name();
	}
endif;
