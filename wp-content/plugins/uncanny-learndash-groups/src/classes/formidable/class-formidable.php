<?php

namespace uncanny_learndash_groups;

use FrmEntry;
use FrmField;


/**
 * Class Formidable
 * @package uncanny_learndash_groups
 */
class Formidable {

	/**
	 * Formidable constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 999 );
	}

	/**
	 * Add hookable actions of Formidable
	 */
	public function plugins_loaded() {
		require_once Utilities::get_include( 'formidable/class-formidable-code-field.php' );

		add_filter( 'frm_available_fields', array( $this, 'add_uncanny_code_field' ), 99 );
		add_filter( 'frm_get_field_type_class', array( $this, 'add_uncanny_code_field_type' ), 99, 2 );
		add_filter( 'frm_validate_field_entry', array( $this, 'validate_uncanny_code' ), 99, 3 );

		add_action( 'frm_after_create_entry', array( $this, 'redeem_uncanny_code' ), 30, 2 );

		add_action( 'admin_init', array( $this, 'migrate_code' ), 99 );
	}

	/**
	 *
	 */
	public function migrate_code() {
		if ( 'no' === get_option( 'ulgm_formidable_migrated', 'no' ) && class_exists( '\FrmEntryFormat' ) ) {
			global $wpdb;
			$wpdb->query( "UPDATE `{$wpdb->prefix}frm_fields` SET `type`= 'ulgm_code' WHERE `type` LIKE 'uncanny_code'" );
			update_option( 'ulgm_formidable_migrated', 'yes', false );
		}
	}

	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function add_uncanny_code_field( $fields ) {
		$fields['ulgm_code'] = array(
			'name' => esc_html__( 'Uncanny Key', 'uncanny-learndash-groups' ),
			// the key for the field and the label,.
			'icon' => 'frm_icon_font frm_price_tags_icon',
		);

		return $fields;
	}

	/**
	 * @param $class
	 * @param $type
	 *
	 * @return mixed|string
	 */
	public function add_uncanny_code_field_type( $class, $type ) {
		if ( 'ulgm_code' === $type ) {
			$class = '\uncanny_learndash_groups\FrmFieldUlgmCode';
		}

		return $class;
	}

	/**
	 * @param $errors
	 * @param $posted_field
	 * @param $field_value
	 *
	 * @return mixed
	 */
	public function validate_uncanny_code( $errors, $posted_field, $field_value ) {

		if ( 'ulgm_code' !== (string) $posted_field->type ) {
			return $errors;
		}

		if ( empty( $field_value ) ) {
			$errors[ 'field' . $posted_field->id ] = esc_html__( 'Please enter valid code.', 'uncanny-learndash-groups' );

			return $errors;
		}
		$code_details = SharedFunctions::is_key_available( $field_value );
		$results      = SharedFunctions::validate_key_results( $code_details );
		if ( false === $results['is_valid'] ) {
			$message                               = $results['message'];
			$errors[ 'field' . $posted_field->id ] = $message;
		}

		return $errors;
	}

	/**
	 * @param $entry_id
	 * @param $form_id
	 */
	public function redeem_uncanny_code( $entry_id, $form_id ) {
		$entry = FrmEntry::getOne( $entry_id, true );

		if ( ! isset( $entry->metas ) ) {
			return;
		}

		$user_id = wp_get_current_user()->ID;

		if ( absint( $entry->user_id ) !== absint( $user_id ) && 0 !== absint( $entry->user_id ) ) {
			$user_id = $entry->user_id;
		}
		if ( ! is_numeric( $user_id ) ) {
			return;
		}
		foreach ( $entry->metas as $k => $v ) {
			$value      = $v;
			$field_info = FrmField::getOne( $k );

			if ( ! $field_info ) {
				continue;
			}
			if ( 'ulgm_code' !== (string) $field_info->type ) {
				continue;
			}
			$code_details = SharedFunctions::is_key_available( $value );
			if ( 'success' === $code_details['result'] ) {
				update_user_meta( $user_id, '_ulgm_code_used', $value );
				$result = SharedFunctions::set_user_to_code( $user_id, $value, SharedFunctions::$not_started_status );
				if ( $result ) {
					SharedFunctions::set_user_to_group( $user_id, $code_details['ld_group_id'] );
				}
			}
		}
	}

}
