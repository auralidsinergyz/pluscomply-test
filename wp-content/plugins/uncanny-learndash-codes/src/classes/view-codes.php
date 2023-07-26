<?php

namespace uncanny_learndash_codes;

/**
 * Class ViewCodes
 * @package uncanny_learndash_codes
 */
class ViewCodes extends \WP_List_Table {
	private $group_id;

	/**
	 * ViewCodes constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		parent::__construct();
		$this->group_id = isset( $args['group_id'] ) ? $args['group_id'] : 'all';
	}

	/**
	 * @param $group_id
	 */
	public function prepare_items() {
		//$this->group_id = $group_id;
		if ( empty( $this->group_id ) && ! empty( $_GET['group_id'] ) ) {
			$this->group_id = $_GET['group_id'];
		}
		$paged                 = $this->get_pagenum();
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$orderby = ( isset( $_GET['orderby'] ) ) ? $_GET['orderby'] : '';
		$order   = ( isset( $_GET['order'] ) ) ? $_GET['order'] : '';

		$paged       = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;
		$searched    = isset( $_GET['s'] ) ? $_GET['s'] : '';
		$this->items = Database::get_coupons( $this->group_id, $paged, $orderby, $order, $searched );

		$this->set_pagination_args( array(
			'total_items' => Database::get_num_coupons( $this->group_id, $searched ),
			'per_page'    => 100,
		) );
	}

	/**
	 *
	 */
	public function no_items() {
		_e( 'No coupon found.', 'uncanny-learndash-codes' );
	}

	/**
	 * @return array
	 */
	public function get_columns() {
		$c = array(
			'coupon'        => __( 'Code', 'uncanny-learndash-codes' ),
			'used_date'     => __( 'Redeemed Date', 'uncanny-learndash-codes' ),
			'expire_date'   => __( 'Expiry Date', 'uncanny-learndash-codes' ),
			'user_nicename' => __( 'Redeemed User', 'uncanny-learndash-codes' ),
			//'source'        => __( 'Source' ),
		);

		return $c;
	}

	/**
	 * @return array
	 */
	protected function get_sortable_columns() {
		$c = array(
			//'used_date' => array( 'used_date', true ),
			//'user_nicename' => array( 'user_nicename', true ),
		);

		return $c;
	}

	/**
	 * @return mixed
	 */
	protected function get_views() {
		$view['download'] = sprintf( '<a href="%s" class="button">%s</a>',
			add_query_arg(
				array( 'group_id' => $this->group_id, 'mode' => 'download', ),
				remove_query_arg( array( 'orderby', 'order' ) )
			),
			__( 'Download', 'uncanny-learndash-codes' )
		);

		$view['back'] = sprintf( '<a href="%s" class="button">%s</a>',
			remove_query_arg(
				array(
					'group_id',
					'orderby',
					'order',
				)
			),
			__( 'Back to Code Group View', 'uncanny-learndash-codes' )
		);

		return $view;
	}

	/**
	 *
	 */
	public function display_rows() {
		foreach ( $this->items as $coupon ) {
			echo "\n\t" . $this->single_row( $coupon );
		}
	}

	/**
	 * @param object $coupon
	 *
	 * @return string
	 */
	public function single_row( $coupon ) {
		$r = '<tr>';
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			$r .= '<td>';
			switch ( $column_name ) {
				case 'coupon' :
					$r .= $coupon->code;
					break;

				case 'source' :
					$users = maybe_unserialize( $coupon->user_id );
					if ( $users ) {
						foreach ( $users as $u ) {
							$r .= sprintf( '%s', get_user_meta( $u['user'], Config::$uncanny_codes_tracking, true ) ) . '<br />';
						}
					}
					break;

				case 'used_date' :
					$users = maybe_unserialize( $coupon->user_id );
					if ( $users ) {
						foreach ( $users as $u ) {
							$r .= date( 'Y-m-d', $u['redeemed'] ) . '<br />';
						}
					}
					break;
				case 'expire_date' :
					if ( $coupon->expire_date !== '0000-00-00 00:00:00' ) {
						$_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $coupon->expire_date );
						$r     .= $_date->format( 'F j, Y g:i a' );
					} else {
						$r .= 'Unlimited';
					}
					break;

				case 'user_nicename' :
					$users   = maybe_unserialize( $coupon->user_id );
					$display = '';
					if ( $users ) {
						foreach ( $users as $u ) {
							$first_name = get_user_meta( $u['user'], 'first_name', true );
							$last_name  = get_user_meta( $u['user'], 'last_name', true );
							if ( empty( $first_name ) && empty( $last_name ) ) {
								$us = get_user_by( 'id', $u['user'] );
								if ( ! empty( $us ) ) {
									$display = $us->user_email;
								}
							} else {
								$display = "{$first_name} {$last_name}";
							}

							$r .= sprintf( '<a href="%s">%s</a>', admin_url( 'user-edit.php?user_id=' . $u['user'] ), $display ) . '<br />';
						}
					}
					break;
			}
			$r .= '</td>';
		}
		$r .= '</tr>';

		return $r;
	}

	/**
	 * @return string
	 */
	protected function get_default_primary_column_name() {
		return 'created';
	}
}