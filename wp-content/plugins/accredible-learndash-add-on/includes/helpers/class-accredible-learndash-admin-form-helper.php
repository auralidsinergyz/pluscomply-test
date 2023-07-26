<?php
/**
 * Accredible LearnDash Add-on form table helper
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Form_Helper' ) ) :
	/**
	 * Accredible LearnDash Add-on admin form helper class
	 */
	class Accredible_Learndash_Admin_Form_Helper {
		/**
		 * Returns value attribute if value is present.
		 *
		 * @param array $obj contains value.
		 * @param array $key key to get value.
		 */
		public static function value_attr( $obj, $key ) {
			$obj = (array) $obj;
			if ( ! empty( $obj ) && isset( $obj[ $key ] ) ) {
				echo sprintf(
					'value="%s"',
					esc_attr( $obj[ $key ] )
				);
			}
		}

		/**
		 * Returns value if present else shows default value.
		 *
		 * @param mixed $value value.
		 * @param mixed $default default value.
		 */
		public static function html( $value, $default = 'N/A' ) {
			if ( empty( $value ) ) {
				$value = $default;
			}

			echo esc_html( $value );
		}
	}
endif;
