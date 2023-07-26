<?php

namespace uncanny_learndash_groups;

use WPForms_Field;
use function wpforms_get_min_suffix;

/**
 * Add new "Uncanny Codes" field type for WPForms
 *
 * Class WPForms_Code_Field
 * @package uncanny_learndash_groups
 */
class WPForms_Code_Field extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information.
		$this->name  = esc_html__( 'Uncanny Key', 'uncanny-learndash-groups' );
		$this->type  = 'ulgm_code';
		$this->icon  = 'fa-ticket';
		$this->order = 600;

		// Define additional field properties.
		add_filter( 'wpforms_field_properties_uncanny_code', array( $this, 'field_properties' ), 5, 3 );
		add_action( 'wpforms_frontend_js', array( $this, 'frontend_js' ) );
	}

	/**
	 * Define additional field properties.
	 *
	 * @param array $properties Field properties.
	 * @param array $field Field settings.
	 * @param array $form_data Form data and settings.
	 *
	 * @return array
	 * @since 1.4.5
	 *
	 */
	public function field_properties( $properties, $field, $form_data ) {

		return $properties;
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @param array $field Field settings.
	 *
	 * @since 1.0.0
	 *
	 */
	public function field_options( $field ) {
		/*
		 * Basic field options.
		 */

		// Options open markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'open',
			)
		);

		// Label.
		$this->field_option( 'label', $field );

		// Description.
		$this->field_option( 'description', $field );

		// Required toggle.
		$this->field_option( 'required', $field );

		// Options close markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'close',
			)
		);

		/*
		 * Advanced field options.
		 */

		// Options open markup.
		$this->field_option(
			'advanced-options',
			$field,
			array(
				'markup' => 'open',
			)
		);

		// Size.
		$this->field_option( 'size', $field );

		// Placeholder.
		$this->field_option( 'placeholder', $field );

		// Hide label.
		$this->field_option( 'label_hide', $field );

		// Limit length.
		$args = array(
			'slug'    => 'limit_enabled',
			'content' => $this->field_element(
				'checkbox',
				$field,
				array(
					'slug'    => 'limit_enabled',
					'value'   => isset( $field['limit_enabled'] ),
					'desc'    => esc_html__( 'Limit Length', 'wpforms-lite' ),
					'tooltip' => esc_html__( 'Check this option to limit text length by characters or words count.', 'wpforms-lite' ),
				),
				false
			),
		);
		$this->field_element( 'row', $field, $args );

		$count = $this->field_element(
			'text',
			$field,
			array(
				'type'  => 'number',
				'slug'  => 'limit_count',
				'attrs' => array(
					'min'     => 1,
					'step'    => 1,
					'pattern' => '[0-9]',
				),
				'value' => ! empty( $field['limit_count'] ) ? absint( $field['limit_count'] ) : 1,
			),
			false
		);

		$mode = $this->field_element(
			'select',
			$field,
			array(
				'slug'    => 'limit_mode',
				'value'   => ! empty( $field['limit_mode'] ) ? esc_attr( $field['limit_mode'] ) : 'characters',
				'options' => array(
					'characters' => esc_html__( 'Characters', 'wpforms-lite' ),
					//'words'      => esc_html__( 'Words', 'wpforms-lite' ),
				),
			),
			false
		);
		$args = array(
			'slug'    => 'limit_controls',
			'class'   => ! isset( $field['limit_enabled'] ) ? 'wpforms-hide' : '',
			'content' => $count . $mode,
		);
		$this->field_element( 'row', $field, $args );

		// Custom CSS classes.
		$this->field_option( 'css', $field );

		// Options close markup.
		$this->field_option(
			'advanced-options',
			$field,
			array(
				'markup' => 'close',
			)
		);
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @param array $field Field settings.
	 *
	 * @since 1.0.0
	 *
	 */
	public function field_preview( $field ) {

		// Define data.
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';

		// Label.
		$this->field_preview_option( 'label', $field );

		// Primary input.
		echo '<input type="text" placeholder="' . esc_attr( $placeholder ) . '" class="primary-input" disabled>';

		// Description.
		$this->field_preview_option( 'description', $field );
	}

	/**
	 * Field display on the form front-end.
	 *
	 * @param array $field Field settings.
	 * @param array $deprecated Deprecated.
	 * @param array $form_data Form data and settings.
	 *
	 * @since 1.0.0
	 *
	 */
	public function field_display( $field, $deprecated, $form_data ) {

		// Define data.
		$primary = $field['properties']['inputs']['primary'];

		if ( isset( $field['limit_enabled'] ) ) {
			$limit_count = isset( $field['limit_count'] ) ? absint( $field['limit_count'] ) : 0;
			$limit_mode  = isset( $field['limit_mode'] ) ? sanitize_key( $field['limit_mode'] ) : 'characters';

			$primary['data']['form-id']  = $form_data['id'];
			$primary['data']['field-id'] = $field['id'];

			if ( 'characters' === $limit_mode ) {
				$primary['class'][]            = 'wpforms-limit-characters-enabled';
				$primary['attr']['maxlength']  = $limit_count;
				$primary['data']['text-limit'] = $limit_count;
			}
		}

		// Primary field.
		printf(
			'<input type="text" %s %s>',
			wpforms_html_attributes( $primary['id'], $primary['class'], $primary['data'], $primary['attr'] ),
			$primary['required'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Enqueue frontend limit option js.
	 *
	 * @param array $forms Forms on the current page.
	 *
	 * @since 1.5.6
	 *
	 */
	public function frontend_js( $forms ) {

		// Get fields.
		$fields = array_map(
			function ( $form ) {
				return empty( $form['fields'] ) ? array() : $form['fields'];
			},
			(array) $forms
		);

		// Make fields flat.
		$fields = array_reduce(
			$fields,
			function ( $accumulator, $current ) {
				return array_merge( $accumulator, $current );
			},
			array()
		);

		// Leave only fields with limit.
		$fields = array_filter(
			$fields,
			function ( $field ) {
				return $field['type'] === $this->type && isset( $field['limit_enabled'] ) && ! empty( $field['limit_count'] );
			}
		);

		if ( count( $fields ) ) {
			$min = wpforms_get_min_suffix();
			wp_enqueue_script( 'wpforms-text-limit', WPFORMS_PLUGIN_URL . "assets/js/text-limit{$min}.js", array(), WPFORMS_VERSION, true );
		}
	}

	/**
	 * Format and sanitize field.
	 *
	 * @param int $field_id Field ID.
	 * @param mixed $field_submit Field value that was submitted.
	 * @param array $form_data Form data and settings.
	 *
	 * @since 1.5.6
	 *
	 */
	public function format( $field_id, $field_submit, $form_data ) {

		$field = $form_data['fields'][ $field_id ];
		$name  = ! empty( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';

		// Sanitize.
		$value = sanitize_text_field( $field_submit );

		wpforms()->process->fields[ $field_id ] = array(
			'name'  => $name,
			'value' => $value,
			'id'    => absint( $field_id ),
			'type'  => 'text',
		);
	}
}

new WPForms_Code_Field();
