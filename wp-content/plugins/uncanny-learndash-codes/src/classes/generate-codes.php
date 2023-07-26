<?php

namespace uncanny_learndash_codes;

/**
 * Class GenerateCodes
 *
 * @package uncanny_learndash_codes
 */
class GenerateCodes extends Config {
	
	/**
	 * @var array|null|object
	 */
	public static $courses;
	/**
	 * @var array|null|object
	 */
	public static $coupons;
	/**
	 * @var array|null|object
	 */
	public static $groups;
	
	/**
	 * @var array|null|object
	 */
	public static $num_coupons_added;
	/**
	 * @var array|null|object
	 */
	public static $num_coupons_requested;
	private static $chars = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
	protected $table;
	
	/**
	 * GenerateCodes constructor.
	 */
	function __construct() {
		parent::__construct();
		self::$courses = LearnDash::get_courses();
		self::$groups  = LearnDash::get_groups();
		
		add_action( 'admin_init', [ __CLASS__, 'process_submit' ], 9 );
	}
	
	/**
	 *
	 */
	public static function process_submit() {
		if ( ( ! empty( $_POST ) && isset( $_POST['_custom_wpnonce'] ) ) && wp_verify_nonce( $_POST['_custom_wpnonce'], Config::get_project_name() ) ) {
			$coupons       = [];
			$prefix        = sanitize_text_field( $_POST['coupon-prefix'] );
			$suffix        = sanitize_text_field( $_POST['coupon-suffix'] );
			$dashes        = explode( '-', sanitize_text_field( $_POST['coupon-dash'] ) );
			$code_length   = sanitize_text_field( $_POST['coupon-length'] ) - strlen( $prefix ) - strlen( $suffix );
			$codes         = Database::get_coupon_codes( sanitize_text_field( $_POST['coupon-length'] ) );
			$coupon_amount = $_POST['coupon-amount'];
			$threshold     = 5;
			if ( $coupon_amount > 10000 ) {
			    if( $threshold > strlen( $coupon_amount ) ){
				    $threshold = absint( $threshold - strlen( $coupon_amount ) );
                }else{
				    $threshold = 0;
                }
				
			}
			$looping = 0;
			self::$num_coupons_requested = $coupon_amount;
			
			for ( $i = 0; $i < $coupon_amount; $i ++ ) {
				$coupon     = $prefix . self::generate_random_string( $code_length ) . $suffix;
				$coupon_new = [];
				$pointer    = 0;
				// check for unique code.
				$is_unqiue  = FALSE;
				$force_exit = 0;
				while ( FALSE === $is_unqiue ) {
					$looping++;
					if ( ! in_array( $coupon, $codes ) ) {
						$is_unqiue = TRUE;
					} else {
						$coupon    = $prefix . self::generate_random_string( $code_length ) . $suffix;
						$is_unqiue = FALSE;
						$force_exit ++;
					}
					if ( $force_exit == $threshold ) {
						$is_unqiue = TRUE;
						$coupon    = '';
					}
				}
				//var_dump($looping);
				if ( $force_exit < $threshold && ! empty( $coupon ) ) {
					$codes[] = $coupon;
					
					foreach ( $dashes as $dash ) {
						$dash = (int) $dash;
						if ( $dash ) {
							if ( strlen( $coupon ) < $pointer + $dash ) {
								$dash = strlen( $coupon ) - $pointer;
							}
							if ( $pointer < strlen( $coupon ) ) {
								$coupon_new[] = substr( $coupon, $pointer, $dash );
								$pointer      = $pointer + $dash;
							}
						}
					}
					
					if ( $pointer < strlen( $coupon ) ) {
						$coupon_new[] = substr( $coupon, $pointer );
					}
					
					$coupons[] = implode( '-', $coupon_new );
				}
				
			}
			self::$num_coupons_added = count( $coupons );
			$_POST['coupon-amount']  = self::$num_coupons_added;
			if ( ! empty( $coupons ) ) {
				$group_id = Database::add_coupons( $_POST, $coupons );
			}
			add_action( 'admin_notices', [ __CLASS__, 'success_notice' ] );
		}
	}
	
	/**
	 * @param $length
	 *
	 * @return string
	 */
	private static function generate_random_string( $length ) {
		$string = "";
		for ( $i = 0; $i < $length; $i ++ ) {
			$string .= self::$chars[ mt_rand( 0, strlen( self::$chars ) - 1 ) ];
		}
		
		return $string;
	}
	
	/**
	 *
	 */
	public static function success_notice() {
		$message = sprintf( __( '<h4>%s Codes Created! <a href="%s">Manage Codes</a></h4>', 'uncanny-learndash-codes' ), self::$num_coupons_added, esc_attr( add_query_arg( 'page', 'uncanny-learndash-codes-view' ) ) );
		if ( self::$num_coupons_added < self::$num_coupons_requested ) {
			$message = sprintf( __( '<h4>Only %s unique codes were generated.  Try increasing the number of characters to generate more codes. <a href="%s">Manage Codes</a></h4>', 'uncanny-learndash-codes' ), self::$num_coupons_added, esc_attr( add_query_arg( 'page', 'uncanny-learndash-codes-view' ) ) );
		}
		?>
        <div class="updated notice">
            <p><?php echo $message; ?></p>
        </div>
		<?php
	}
}