<?php

namespace uncanny_learndash_codes;


use Uncanny_Automator\Utilities;

/**
 * Class Database
 * @package uncanny_learndash_codes
 */
class Database extends Config {

	/**
	 * Database constructor.
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * @return false|int
	 */
	private static function is_table_exists() {
		global $wpdb;
		$qry = "SHOW TABLES LIKE '{$wpdb->prefix}" . Config::$tbl_codes . "';";

		return $wpdb->query( $qry );
	}

	/**
	 *
	 */
	public static function reset() {
		global $wpdb;
		$wpdb->query( "DROP TABLE {$wpdb->prefix}" . Config::$tbl_codes );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}" . Config::$tbl_groups );
	}

	/**
	 *
	 */
	public static function reset_data() {
		global $wpdb;
		$wpdb->query( "TRUNCATE {$wpdb->prefix}" . Config::$tbl_codes );
		$wpdb->query( "TRUNCATE {$wpdb->prefix}" . Config::$tbl_groups );
	}

	/**
	 * @return bool
	 */
	public static function create_tables() {
		if ( self::is_table_exists() ) {
			return false;
		}

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


		$tbl_codes  = Config::$tbl_codes;
		$tbl_groups = Config::$tbl_groups;


		$sql = "CREATE TABLE {$wpdb->prefix}{$tbl_codes} (
					ID bigint(20) NOT NULL AUTO_INCREMENT,
					code_group bigint(20),
					code varchar(30) NOT NULL,
					used_date datetime,
					user_id LONGTEXT,
					PRIMARY KEY (ID)
				);";
		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}{$tbl_groups} (
					ID bigint(20) NOT NULL AUTO_INCREMENT,
					code_for varchar(15),
					paid_unpaid varchar(20) NOT NULL DEFAULT 'default',
					prefix varchar(20),
					suffix varchar(20),
					issue_date datetime NOT NULL,
					expire_date datetime NOT NULL,
					linked_to LONGTEXT,
					issue_count int(20),
					issue_max_count int(20),
					used_code int(20) NOT NULL DEFAULT '0',
					dash varchar(30),
					PRIMARY KEY (ID)
				);";
		dbDelta( $sql );
	}

	/**
	 * @return bool
	 */
	public static function upgrade_table() {
		if ( ! self::is_table_exists() ) {
			return false;
		}
		if ( 'no' === get_option( 'uncanny_codes_paid_unpaid_db_upgraded', 'no' ) ) {
			global $wpdb;
			$tbl_groups = Config::$tbl_groups;
			$sql        = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$wpdb->prefix}{$tbl_groups}' AND column_name = 'paid_unpaid'";
			$row        = $wpdb->get_results( $sql );
			if ( empty( $row ) ) {
				$sql = "ALTER TABLE {$wpdb->prefix}{$tbl_groups} ADD paid_unpaid VARCHAR(20) NOT NULL DEFAULT 'default' AFTER code_for";
				$wpdb->query( $sql );
				if ( is_multisite() ) {
					update_site_option( 'uncanny_codes_paid_unpaid_db_upgraded', 'yes' );
				} else {
					update_option( 'uncanny_codes_paid_unpaid_db_upgraded', 'yes' );
				}
			}
		}
	}

	// Login Coupon Methods

	/**
	 * @param $group
	 * @param $coupons
	 *
	 * @return int
	 */
	public static function add_coupons( $group, $coupons ) {
		if ( ! self::is_table_exists() ) {
			self::create_tables();
		}

		global $wpdb;

		$now         = current_time( 'mysql' );
		$expiry_date = '0000-00-00 00:00:00';

		if ( 'course' === $group['coupon-for'] ) {
			$linked_to = $group['coupon-courses'];
		} elseif ( 'group' === $group['coupon-for'] ) {
			$linked_to = $group['coupon-group'];
		}
		if ( ! empty( $group['expiry-date'] ) ) {
			if ( ! empty( $group['expiry-time'] ) ) {
				$expiry_date = date( 'Y-m-d H:i:s', strtotime( $group['expiry-date'] . ' ' . $group['expiry-time'] ) );
			} else {
				$expiry_date = date( 'Y-m-d 23:59:59', strtotime( $group['expiry-date'] ) );
			}
		}
		$wpdb->insert( $wpdb->prefix . Config::$tbl_groups,
			array(
				'code_for'        => $group['coupon-for'],
				'paid_unpaid'     => $group['coupon-paid-unpaid'],
				'prefix'          => $group['coupon-prefix'],
				'suffix'          => $group['coupon-suffix'],
				'issue_date'      => $now,
				'linked_to'       => serialize( $linked_to ),
				'issue_count'     => intval( $group['coupon-amount'] ),
				'issue_max_count' => intval( $group['coupon-max-usage'] ),
				'dash'            => $group['coupon-dash'],
				'expire_date'     => $expiry_date,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
			)
		);

		$group_id = $wpdb->insert_id;

		for ( $i = 0; $i < $group['coupon-amount']; $i ++ ) {
			$wpdb->insert( $wpdb->prefix . Config::$tbl_codes, array(
				'code_group' => intval( $group_id ),
				'code'       => $coupons[ $i ],
			) );
		}

		return $group_id;
	}

	/**
	 * @param int $paged
	 * @param string $orderby
	 * @param string $order
	 *
	 * @param string $search
	 *
	 * @return array|null|object
	 */
	public static function get_groups( $paged = 1, $orderby = '', $order = 'DESC', $search = '' ) {
		global $wpdb;
		$limit = ( $paged - 1 ) * 100;

		switch ( $orderby ) {
			case 'issue_date' :
				$orderby = 'issue_date';
				break;
		}
		$order = ( $orderby ) ? ' ORDER BY ' . $orderby . ' ' . $order : '';

		if ( ! empty( $search ) ) {
			$sql = $wpdb->prepare( "SELECT g.* FROM {$wpdb->prefix}" . Config::$tbl_groups . " g LEFT JOIN {$wpdb->prefix}" . Config::$tbl_codes . " c ON g.ID = c.code_group WHERE ( g.prefix LIKE '%%%s%%' OR g.suffix LIKE '%%%s%%' OR c.code LIKE '%%%s%%' ) GROUP BY g.ID {$order} LIMIT {$limit}, 100", $search, $search, $search );
		} else {
			$sql = "SELECT * FROM {$wpdb->prefix}" . Config::$tbl_groups . " {$order} LIMIT {$limit}, 100";
		}

		return $wpdb->get_results( $sql );
	}

	/**
	 * @param string $group
	 * @param int $paged
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return array|null|object
	 */
	public static function get_coupons( $group = 'all', $paged = 1, $orderby = '', $order = 'DESC', $search = '' ) {
		global $wpdb;
		$limit = $where = '';

		if ( 'all' !== $group ) {
			$where = 'WHERE c.code_group = ' . $group;
		}
		if ( ! empty ( $search ) ) {
			$where .= ( $where == '' ? 'WHERE' : ' AND ' ) . " ( c.code LIKE '%{$search}%' )";
		}
		switch ( $orderby ) {
			case 'used_date' :
				$orderby = 'used_date';
				break;
		}
		$order = ( $orderby ) ? ' ORDER BY ' . $orderby . ' ' . $order : '';

		$limit = ( $paged - 1 ) * 100;
		$limit = "LIMIT {$limit}, 100";

		return $wpdb->get_results( "SELECT c.*, g.expire_date FROM {$wpdb->prefix}" . Config::$tbl_codes . " c LEFT JOIN {$wpdb->prefix}" . Config::$tbl_groups . " g ON g.ID=c.code_group {$where} {$order} {$limit}" );
	}

	/**
	 * @return null|string
	 */
	public static function get_num_groups( $search = '' ) {
		global $wpdb;
		$sql = '';
		if ( ! empty( $search ) ) {
			$sql = $wpdb->prepare( "SELECT COUNT(DISTINCT g.ID) FROM {$wpdb->prefix}" . Config::$tbl_groups . " g LEFT JOIN {$wpdb->prefix}" . Config::$tbl_codes . " c ON g.ID = c.code_group WHERE ( g.prefix LIKE '%%%s%%' OR g.suffix LIKE '%%%s%%' OR c.code LIKE '%%%s%%' ) ", $search, $search, $search );
		} else {
			$sql = "SELECT COUNT(DISTINCT g.ID) FROM {$wpdb->prefix}" . Config::$tbl_groups . ' g ';
		}

		return $wpdb->get_var( $sql );
	}

	/**
	 * @param string $group
	 *
	 * @return null|string
	 */
	public static function get_num_coupons( $group = "all", $search = "" ) {
		global $wpdb;

		$where = '';
		if ( $group !== 'all' ) {
			$where = 'WHERE c.code_group = ' . $group;
		}
		if ( ! empty ( $search ) ) {
			$where .= ( $where == '' ? 'WHERE' : ' AND ' ) . " ( c.code LIKE '%{$search}%' )";
		}

		return $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}" . Config::$tbl_codes . " c " . $where );
	}

	/**
	 * @param $group_id
	 */
	public static function delete_coupon( $group_id ) {
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . Config::$tbl_groups, array( 'ID' => $group_id ) );
		$wpdb->delete( $wpdb->prefix . Config::$tbl_codes, array( 'code_group' => $group_id ) );
	}

	/**
	 * @param $coupon
	 *
	 * @return array
	 */
	public static function is_coupon_available( $coupon ) {
		global $wpdb;
		$user    = wp_get_current_user();
		$user_id = $user->ID;
		//Modifying logic here to be able to use 1 coupon maximum times!
		$results = $wpdb->get_row( $wpdb->prepare( "SELECT c.ID as coupon_id, c.code, g.issue_max_count AS max_count, g.used_code AS is_used, c.user_id, g.expire_date as expiry_date
										FROM {$wpdb->prefix}" . Config::$tbl_groups . " g
										LEFT JOIN {$wpdb->prefix}" . Config::$tbl_codes . " c
										ON g.ID = c.code_group
										WHERE c.code LIKE %s", $coupon ) );
		if ( $results ) {
			$coupon_id = $results->coupon_id;
			$users     = maybe_unserialize( $results->user_id );
			$max       = $results->max_count;
			if ( $results->expiry_date !== '0000-00-00 00:00:00' ) {
				if ( strtotime( date( 'Y-m-d H:i:s' ), time() ) > strtotime( $results->expiry_date ) ) {
					return [
						'result' => 'failed',
						'error'  => 'expired',
					];
				}
			}

			if ( empty( $users ) ) {
				//Coupon used for first time
				return $coupon_id;
			} elseif ( is_array( $users ) && count( $users ) < $max ) {
				//Coupon used before BUT is not redeemed Maximum times
				if ( array_key_exists( $user_id, $users ) ) {
					return array(
						'result' => 'failed',
						'error'  => 'existing',
					);
				} else {
					return $coupon_id;
				}
			} elseif ( is_array( $users ) && count( $users ) === intval( $max ) ) {
				//Coupon redeemed Maximum Times
				return array(
					'result' => 'failed',
					'error'  => 'max',
				);
			}
		} else {

			return array(
				'result' => 'failed',
				'error'  => 'invalid',
			);

		}
	}

	/**
	 * @param $coupon
	 *
	 * @return bool
	 */
	public static function is_coupon_paid( $coupon ) {
		global $wpdb;

		$prepare = $wpdb->prepare( "
		SELECT c.ID FROM {$wpdb->prefix}" . Config::$tbl_codes . " c 
		LEFT JOIN {$wpdb->prefix}" . Config::$tbl_groups . " cg
		ON c.code_group = cg.ID
		WHERE c.code = %s
		AND cg.paid_unpaid = %s",
			$coupon,
			'paid'
		);
		if ( ! empty( $wpdb->get_var( $prepare ) ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $coupon
	 *
	 * @return bool
	 */
	public static function is_default_code( $coupon ) {
		global $wpdb;

		$prepare = $wpdb->prepare( "
		SELECT c.ID FROM {$wpdb->prefix}" . Config::$tbl_codes . " c 
		LEFT JOIN {$wpdb->prefix}" . Config::$tbl_groups . " cg
		ON c.code_group = cg.ID
		WHERE c.code = %s
		AND cg.paid_unpaid = %s",
			$coupon,
			'default'
		);
		if ( ! empty( $wpdb->get_var( $prepare ) ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $group_id
	 *
	 * @return int
	 */
	public static function get_group_redeemed_count( $group_id ) {
		global $wpdb;
		$count   = 0;
		$prepare = $wpdb->prepare( "SELECT user_id FROM {$wpdb->prefix}" . Config::$tbl_codes . " WHERE code_group = %d AND user_id IS NOT NULL", $group_id );

		$result = $wpdb->get_results( $prepare );
		if ( $result ) {
			foreach ( $result as $r ) {
				$users = maybe_unserialize( $r->user_id );
				$count = $count + count( $users );
			}
		}

		return $count;
	}

	/**
	 * @param $user_id
	 * @param $coupon_id
	 *
	 * @return array|bool
	 */
	public static function set_user_to_coupon( $user_id, $coupon_id ) {
		global $wpdb;
		$get_coupon_details = $wpdb->get_row( $wpdb->prepare(
			"SELECT g.ID as group_id, g.prefix, g.code_for, g.linked_to, c.ID as coupon_id, c.code, g.issue_max_count AS max_count, g.used_code AS is_used, c.user_id 
										FROM {$wpdb->prefix}" . Config::$tbl_groups . " g
										LEFT JOIN {$wpdb->prefix}" . Config::$tbl_codes . " c
										ON g.ID = c.code_group
										WHERE c.ID = %d", $coupon_id
		) );
		if ( $get_coupon_details ) {
			$users = maybe_unserialize( $get_coupon_details->user_id );
			if ( is_array( $users ) ) {
				$users[ $user_id ] = array(
					'redeemed' => time(),
					'user'     => $user_id,
				);
			} else {
				$users[ $user_id ] = array(
					'redeemed' => time(),
					'user'     => $user_id,
				);
			}
		} else {
			return false;
		}
		$max = $get_coupon_details->max_count;
		$wpdb->update(
			$wpdb->prefix . Config::$tbl_codes,
			array(
				'user_id'   => serialize( $users ),
				'used_date' => current_time( 'mysql' ),
			),
			array(
				'ID' => $coupon_id,
			),
			array(
				'%s',
				'%s',
			)
		);

		if ( count( $users ) === intval( $max ) ) {
			$group_id = $get_coupon_details->group_id;
			$wpdb->query( "UPDATE {$wpdb->prefix}" . Config::$tbl_groups . " SET used_code = used_code + 1 WHERE ID = {$group_id}" );
		}
		update_user_meta( $user_id, Config::$uncanny_codes_user_prefix_meta, $get_coupon_details->prefix );

		$coupon_for = $get_coupon_details->code_for;
		$linked_to  = maybe_unserialize( $get_coupon_details->linked_to );
		$data       = array(
			'for'  => $coupon_for,
			'data' => $linked_to,
		);

		return $data;
	}


	// Generate CSV

	/**
	 * @param string $group
	 *
	 * @return array
	 */
	public static function get_coupons_csv( $group = 'all' ) {
		global $wpdb;
		$where = '';
		$array = [];

		if ( $group !== 'all' ) {
			$where = 'WHERE c.code_group = ' . $group;
		}

		$results = $wpdb->get_results( "SELECT c.code AS Code, g.code_for AS `Code For`, g.prefix AS Prefix, g.suffix AS Suffix, g.linked_to AS `Linked To`, c.user_id AS Users, g.expire_date
										FROM {$wpdb->prefix}" . Config::$tbl_groups . " g
										LEFT JOIN {$wpdb->prefix}" . Config::$tbl_codes . " c
										ON g.ID = c.code_group
										$where" );
		if ( $results ) {
			$array = array();
			foreach ( $results as $result ) {
				$val       = (array) $result;
				$linked_to = maybe_unserialize( $val['Linked To'] );
				$dd        = array();
				foreach ( $linked_to as $d ) {
					$dd[] = get_the_title( $d );
				}
				$dd = join( '|', $dd );
				if ( ! empty( $val['Users'] ) ) {
					$users = maybe_unserialize( $val['Users'] );
					if ( ! function_exists( 'get_user_by' ) ) {
						include( ABSPATH . 'wp-includes/pluggable.php' );
					}
					foreach ( $users as $u ) {
						$user = get_user_by( 'ID', $u['user'] );
						if ( $user ) {
							$user_email = $user->user_email;
						} else {
							$user_email = '';
						}
						$r = '';
						if ( $val['expire_date'] !== '0000-00-00 00:00:00' ) {
							$_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $val['expire_date'] );
							$r     = $_date->format( 'F j, Y g:i a' );
						} else {
							$r = 'Unlimited';
						}
						
						$array[] = (object) array(
							'Code'          => $val['Code'],
							'Code For'      => $val['Code For'],
							'Prefix'        => $val['Prefix'],
							'Suffix'        => $val['Suffix'],
							'Linked To'     => $dd,
							'Redeemed Date' => ! empty( $u['redeemed'] ) ? date( 'Y-m-d', $u['redeemed'] ) : '',
							'Redeemed User'=> $user_email,
							'Expiry Date'   => $r,
						);
					}
				} else {
					$r = '';
					if ( $val['expire_date'] !== '0000-00-00 00:00:00' ) {
						$_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $val['expire_date'] );
						$r     = $_date->format( 'F j, Y g:i a' );
					} else {
						$r = 'Unlimited';
					}
					$array[] = (object) array(
						'Code'          => $val['Code'],
						'Code For'      => $val['Code For'],
						'Prefix'        => $val['Prefix'],
						'Suffix'        => $val['Suffix'],
						'Linked To'     => $dd,
						'Redeemed Date' => '',
						'Redeemed User'=> '',
						'Expiry Date'   => $r,
					);
				}
			}
		}

		return $array;
	}
	
	/**
	 * @param string $code
	 *
	 * @return null|string
	 */
	public static function get_coupon_codes( $code_length ) {
		global $wpdb;
		$codes   = [];
		$prepare = "SELECT code FROM {$wpdb->prefix}" . Config::$tbl_codes . " ";
		
		$results = $wpdb->get_results( $prepare );
		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$code = str_replace( '-', '', $result->code );
				if ( strlen( $code ) == $code_length ) {
					$codes[] = $code;
				}
			}
		}
		
		return $codes;
	}
}