<?php

namespace uncanny_learndash_codes;

/**
 * Class ViewGroups
 * @package uncanny_learndash_codes
 */
class ViewGroups extends \WP_List_Table {
	public $site_id;

	/**
	 * ViewGroups constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		parent::__construct();
	}

	/**
	 *
	 */
	public function prepare_items( $searched = '' ) {
		$paged                 = $this->get_pagenum();
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$orderby  = ( isset( $_GET['orderby'] ) ) ? $_GET['orderby'] : '';
		$order    = ( isset( $_GET['order'] ) ) ? $_GET['order'] : '';
		$paged    = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;
		$searched = isset( $_GET['s'] ) ? $_GET['s'] : '';

		$this->items = Database::get_groups( $paged, $orderby, $order, $searched );
		$this->set_pagination_args( array(
			'total_items' => Database::get_num_groups( $searched ),
			'per_page'    => 100,
		) );
	}

	/**
	 *
	 */
	public function no_items() {
		_e( 'No group found.', 'uncanny-learndash-codes' );
	}

	/**
	 * @return array
	 */
	public function get_columns() {
		$c = array(
			'issue_date'   => __( 'Created', 'uncanny-learndash-codes' ),
			'code_for'     => __( 'Code Type', 'uncanny-learndash-codes' ),
			'linked_to'    => __( 'Linked To', 'uncanny-learndash-codes' ),
			'prefix'       => __( 'Prefix', 'uncanny-learndash-codes' ),
			'suffix'       => __( 'Suffix', 'uncanny-learndash-codes' ),
			'paid_unpaid'  => __( 'Paid / Unpaid', 'uncanny-learndash-codes' ),
			'max_per_code' => __( 'Max Per Code', 'uncanny-learndash-codes' ),
			'count'        => __( 'Codes Generated', 'uncanny-learndash-codes' ),
			'expire_date'  => __( 'Expiry Date', 'uncanny-learndash-codes' ),
			'used_count'   => __( 'Redeemed', 'uncanny-learndash-codes' ),
			'action'       => __( 'Actions', 'uncanny-learndash-codes' ),
		);

		return $c;
	}

	/**
	 * @return mixed
	 */
	protected function get_views() {
		$view['view_all'] = sprintf( '<a href="%s" class="button">View all Codes</a>', add_query_arg( array( 'group_id' => 'all' ), remove_query_arg( array(
			'orderby',
			'order',
		) ) ) );

		return $view;
	}

	/**
	 * @return array
	 */
	protected function get_sortable_columns() {
		$c = array(
			'issue_date' => array( 'issue_date', true ),
			'code_for'   => array( 'code_for', true ),
		);

		return $c;
	}

	/**
	 *
	 */
	public function display_rows() {
		foreach ( $this->items as $group ) {
			echo "\n\t";
			echo $this->single_row( $group );
		}
	}

	/**
	 * @param object $group
	 *
	 * @return string
	 */
	public function single_row( $group ) {
		$r = '<tr>';
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();


		foreach ( $columns as $column_name => $column_display_name ) {
			$r .= '<td>';
			switch ( $column_name ) {
				case 'code_for' :
					$r .= ucwords( $group->code_for );
					break;

				case 'paid_unpaid' :
					$r .= ucwords( $group->paid_unpaid );
					break;

				case 'prefix' :
					$r .= $group->prefix;
					break;

				case 'suffix' :
					$r .= $group->suffix;
					break;

				case 'issue_date' :
					// $r .= substr( $group->issue_date, 0, 11 );

					$_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $group->issue_date );
					$r .= $_date->format( 'F j, Y g:i a' );

					break;
				case 'expire_date' :
					if ( $group->expire_date !== '0000-00-00 00:00:00' ) {
						$_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $group->expire_date );
						$r     .= $_date->format( 'F j, Y g:i a' );
					} else {
						$r .= 'Unlimited';
					}
					break;

				case 'linked_to' :
					if ( $group->linked_to ) {
						$data = maybe_unserialize( $group->linked_to );
						if ( $data ) {
							foreach ( $data as $d ) {
								$r .= sprintf( '<a href="%s">%s</a>', get_permalink( $d ), get_the_title( $d ) ) . '<br />';
							}
						}
					}
					break;
				case 'used_count' :
					$used  = Database::get_group_redeemed_count( $group->ID );
					$issue = $group->issue_count;
					$max   = $group->issue_max_count;
					$r     .= $used . ' / ' . ( $issue * $max );
					break;

				case 'max_per_code' :
					$r .= $group->issue_max_count;
					break;

				case 'count' :
					$r .= $group->issue_count;
					break;

				case 'action' :
					$actions             = array();
					$actions['view']     = '<a class="button uo-btn-actions" href="' . add_query_arg( array( 'group_id' => $group->ID ), remove_query_arg( array(
							'orderby',
							'order',
						) ) ) . '" uo-tooltip="' . __( 'View', 'uncanny-learndash-codes' ) . '" uo-flow="up"><span class="dashicons dashicons-visibility"></span></a>';
					$actions['download'] = '<a class="button uo-btn-actions" href="' . add_query_arg( array(
							'group_id' => $group->ID,
							'mode'     => 'download',
						), remove_query_arg( array( 'orderby', 'order', ) ) ) . '" uo-tooltip="' . __( 'Download', 'uncanny-learndash-codes' ) . '" uo-flow="up"><span class="dashicons dashicons-download"></span></a>';
					$actions['delete']   = '<a class="button uo-btn-actions uo-btn-delete" href="' . add_query_arg( array(
							'group_id' => $group->ID,
							'mode'     => 'delete',
						) ) . '" uo-tooltip="' . __( 'Delete', 'uncanny-learndash-codes' ) . '" uo-flow="up"><span class="dashicons dashicons-trash"></span></a>';

					$r .= implode( ' ', $actions );
					break;
			}
			$r .= '</td>';
		}


		$r .= '</tr>';

		return $r;
	}
}