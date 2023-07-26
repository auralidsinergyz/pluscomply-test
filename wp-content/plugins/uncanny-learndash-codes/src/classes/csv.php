<?php
namespace uncanny_learndash_codes;


/**
 * Class CSV
 * @package uncanny_learndash_codes
 */
class CSV extends Config {
	/**
	 * @var
	 */
	private static $filename;
	private static $data;
	private static $output;
	private static $table_header;

	public function __construct( $args = array() ) {
		extract( shortcode_atts(
			array(
				'filename' => 'data',
				'data'     => array(),
			), $args, 'uc_csv'
		), EXTR_SKIP );

		self::$filename = $filename;
		self::$data     = $data;

		if ( ! empty( self::$data ) ) {
			if ( ! headers_sent() ) {
				self::header();
			}

			self::$output = fopen( 'php://output', 'w' );

			self::print_table_header();
			self::print_csv();

			fclose( self::$output );
			die;
		}
	}

	/**
	 *
	 */
	private static function header() {
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( sprintf( 'Content-Disposition: attachment; filename=%s.csv', self::$filename ) );
	}

	/**
	 *
	 */
	private static function print_table_header() {
		$data = array_shift( self::$data );

		$header = array();
		foreach ( $data as $key => $value ) {
			$header[] = $key;
		}
		fputcsv( self::$output, $header );
		array_unshift( self::$data, $data );
	}

	/**
	 *
	 */
	private static function print_csv() {
		foreach ( self::$data as $value ) {
			fputcsv( self::$output, (array) $value );
		}
	}
}