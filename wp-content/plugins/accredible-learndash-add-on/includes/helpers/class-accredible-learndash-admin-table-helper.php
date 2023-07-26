<?php
/**
 * Accredible LearnDash Add-on admin table helper
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/rest-api/v1/class-accredible-learndash-api-v1-client.php';

if ( ! class_exists( 'Accredible_Learndash_Admin_Table_Helper' ) ) :
	/**
	 * Accredible LearnDash Add-on admin table helper class
	 */
	class Accredible_Learndash_Admin_Table_Helper {
		const POST_ID        = 'post_id';
		const POST_TYPE      = 'post_type';
		const GROUP_ID       = 'accredible_group_id';
		const KIND           = 'kind';
		const DATE_CREATED   = 'created_at';
		const STATUS         = 'status';
		const CREDENTIAL_URL = 'credential_url';

		const DEFAULT_PAGE_SIZE = 50;

		/**
		 * Caches group names for faster lookup.
		 *
		 * @var array|null $group_name_cache.
		 */
		private static $group_name_cache = null;

		/**
		 * Current results page.
		 *
		 * @var int $page.
		 */
		private static $current_page;

		/**
		 * Results page size.
		 *
		 * @var int $page_size.
		 */
		private static $page_size;

		/**
		 * Row actions.
		 *
		 * @var array $row_actions.
		 */
		private static $row_actions;

		/**
		 * Table columns.
		 *
		 * @var array $table_columns. Defined as string/mixed array - array('id', array('key'=>'error_msg', 'alias'=>'status'))
		 */
		private static $table_columns;

		/**
		 * Public constructor for class.
		 *
		 * @param array $table_columns Table columns.
		 * @param int   $current_page Current page.
		 * @param int   $page_size Page size (optional).
		 * @param array $row_actions Row actions (optional). Defined as array( 'action' => 'edit', 'label' => 'Edit' ).
		 */
		public function __construct( $table_columns, $current_page, $page_size = self::DEFAULT_PAGE_SIZE, $row_actions = array() ) {
			self::$table_columns = $table_columns;
			self::$current_page  = empty( $current_page ) ? 1 : $current_page;
			self::$page_size     = $page_size;
			self::$row_actions   = $row_actions;
		}

		/**
		 * Build table rows.
		 *
		 * @param object $table_data table data.
		 *
		 * @return string
		 */
		public function build_table_rows( $table_data ) {
			$row_cells = '';
			$index     = 0;
			foreach ( $table_data as $row_data ) {
				$row_cells .= '<tr class="accredible-row">';
				$row_cells .= self::table_cell( self::eval_row_num( $index + 1 ) );
				$row_cells .= self::get_table_cells( $row_data );
				if ( ! empty( self::$row_actions ) && isset( $row_data->id ) ) {
					$row_cells .= self::table_cell( self::eval_actions( $row_data->id ), 'accredible-cell-actions' );
				}
				$row_cells .= '</tr>';

				$index ++;
			}

			return $row_cells;
		}

		/**
		 *
		 * Returns formatted table cells.
		 *
		 * @param object $row_data table row data.
		 *
		 * @return string
		 */
		private static function get_table_cells( $row_data ) {
			$table_cells = '';
			foreach ( self::$table_columns as $key ) {
				$data_key = $key;
				if ( is_array( $key ) ) {
					$data_key = $key['key'];
					$key      = $key['alias'];
				}
				$value = $row_data->$data_key;
				switch ( $key ) {
					case self::POST_ID:
						$name  = get_the_title( $value );
						$value = ! empty( $name ) ? $name : self::eval_error( 'Not found' );
						break;
					case self::POST_TYPE:
						$value = self::eval_kind_to_type( $value );
						break;
					case self::GROUP_ID:
						$value = self::eval_group_id( $value );
						break;
					case self::KIND:
						$value = self::eval_kind( $value );
						break;
					case self::DATE_CREATED:
						$value = self::eval_date_time( $value );
						break;
					case self::STATUS:
						$value = self::eval_status( $value );
						break;
					case self::CREDENTIAL_URL:
						$value = self::eval_view_url( $value, 'View Credential' );
						break;
					default:
						$value;
				}

				$table_cells .= '<td>' . $value . '</td>';
			}

			return $table_cells;
		}

		/**
		 * Build a table cell tag .
		 *
		 * @param mixed  $cell_value value in cell.
		 * @param string $classes style classes applied to <td> tag.
		 *
		 * @return string
		 */
		private static function table_cell( $cell_value, $classes = '' ) {
			$start_cell_tag = empty( $classes ) ? '<td>' : '<td class="' . $classes . '">';
			return $start_cell_tag . $cell_value . '</td>';
		}

		/**
		 * Evaluates Accredible group ID to string.
		 *
		 * @param int $group_id Accredible Group ID.
		 *
		 * @return string
		 */
		private static function eval_group_id( $group_id ) {
			if ( null === self::$group_name_cache ) {
				self::store_group_name_cache();
			}

			$key = $group_id;
			if ( array_key_exists( $key, self::$group_name_cache ) ) {
				$value = self::$group_name_cache[ $key ];
			} else {
				$value = 'Not found';
			}

			return $value;
		}

		/**
		 * Store group_name_cache.
		 */
		private static function store_group_name_cache() {
			self::$group_name_cache = array();
			$page                   = 1;
			$client                 = new Accredible_Learndash_Api_V1_Client();
			while ( ! empty( $page ) ) {
				$response = $client->search_groups( null, $page, 50 );
				if ( isset( $response['errors'] ) ) {
					wp_die( 'Accredible API Search Groups Error ' . esc_attr( $response['errors'] ) );
				}

				foreach ( $response['groups'] as $group ) {
					self::$group_name_cache[ $group['id'] ] = $group['name'];
				}
				$page = $response['meta']['next_page'];
			}
		}

		/**
		 * Evaluates kind enum to string.
		 *
		 * @param string $kind enum value.
		 *
		 * @return string
		 */
		private static function eval_kind( $kind ) {
			switch ( $kind ) {
				case 'course_completed':
					$kind = 'Course Completed';
					break;
				case 'lesson_completed':
					$kind = 'Lesson Completed';
					break;
				default:
					$kind;
			}

			return $kind;
		}

		/**
		 * Evaluates kind enum to type string.
		 *
		 * @param string $kind enum value.
		 *
		 * @return string
		 */
		private static function eval_kind_to_type( $kind ) {
			$type = '';
			switch ( $kind ) {
				case 'course_completed':
					$type = 'Course';
					break;
				case 'lesson_completed':
					$type = 'Lesson';
					break;
				default:
					$type;
			}

			return $type;
		}

		/**
		 * Evaluates date values to string.
		 *
		 * @param int $timestamp Timestamp value.
		 *
		 * @return string
		 */
		private static function eval_date_time( $timestamp ) {
			$date_format = 'd M Y';
			$time_format = 'G:i A';

			return sprintf(
				'<span> %1s </span> <span class="accredible-cell-time"> %2s </span>',
				wp_date( $date_format, $timestamp ),
				wp_date( $time_format, $timestamp )
			);
		}

		/**
		 * Returns cell error.
		 *
		 * @param string $error_message error.
		 * @param string $tooltip_message tooltip message.
		 *
		 * @return string
		 */
		private static function eval_error( $error_message, $tooltip_message = '' ) {
			$error  = '';
			$error .= '<span class="cell-value-error">';
			$error .= $error_message;
			if ( ! empty( $tooltip_message ) ) {
				$error .= sprintf(
					'<img src="%1s" title="%2s">',
					ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/warning.svg',
					$tooltip_message
				);
			}
			$error .= '</span>';

			return $error;
		}

		/**
		 * Returns cell status.
		 *
		 * @param string $value enum value.
		 *
		 * @return string
		 */
		private static function eval_status( $value ) {
			$status = '<span class="cell-value-success">Success</span>';

			if ( ! empty( $value ) ) {
				$status = self::eval_error( 'Error', $value );
			}

			return $status;
		}

		/**
		 * Returns row number.
		 *
		 * @param int $index item index.
		 *
		 * @return int
		 */
		private static function eval_row_num( $index ) {
			return ( self::$current_page - 1 ) * self::$page_size + $index;
		}

		/**
		 * Returns a formatted view url.
		 *
		 * @param string $url url.
		 * @param string $label url name.
		 *
		 * @return string
		 */
		private static function eval_view_url( $url, $label ) {
			$href     = 'javascript:void(0);';
			$target   = '';
			$disabled = 'disabled="disabled"';

			if ( ! is_null( $url ) && ! empty( $url ) ) {
				$href     = $url;
				$target   = 'target="_blank"';
				$disabled = '';
			}

			return sprintf(
				'<a href="%1s" %2s class="button accredible-button-outline-natural accredible-button-small" %3s>%4s</a>',
				$href,
				$disabled,
				$target,
				$label
			);
		}

		/**
		 * Returns row actions.
		 *
		 * @param int $id id.
		 *
		 * @return string
		 */
		private static function eval_actions( $id ) {
			$actions = '';
			if ( ! empty( self::$row_actions ) ) {
				foreach ( self::$row_actions as $value ) {
					if ( ( ! empty( $value['label'] ) ) && ( ! empty( $value['action'] ) ) ) {
						$page               = '';
						$has_confirm_dialog = false;
						$need_nonce_url     = false;
						switch ( $value['action'] ) {
							case 'edit_auto_issuance':
								$page = 'accredible_learndash_auto_issuance';
								break;
							case 'delete_auto_issuance':
								$page               = 'accredible_learndash_admin_action';
								$has_confirm_dialog = true;
								$need_nonce_url     = true;
								break;
							default:
								$page = 'accredible_learndash_issuance_list';
						}

						$url = admin_url( 'admin.php?page=' . $page . '&call_action=' . $value['action'] . '&page_num=' . self::$current_page . '&id=' . $id );

						if ( $need_nonce_url ) {
							$url = wp_nonce_url(
								$url,
								$value['action'] . $id,
								'_mynonce'
							);
						}

						$action_params = str_replace( admin_url( 'admin.php?' ), '', $url );

						$actions .= sprintf(
							'<a href="javascript:void(0);" %1s %2s class="button accredible-button-outline-natural accredible-button-small">' . $value['label'] . '</a>',
							$has_confirm_dialog ? 'data-accredible-dialog="true"' : 'data-accredible-sidenav="true"',
							'data-accredible-action-params=' . $action_params
						);
					}
				}
			}
			return $actions;
		}

		/**
		 * Build pagination tile.
		 *
		 * @param string $page page.
		 * @param mixed  $page_meta pagination meta.
		 * @param string $page_name page name used in the tile.
		 *
		 * @return void
		 */
		public static function build_pagination_tile( $page, $page_meta, $page_name ) {
			$viewing_from_to = array(
				'start' => self::eval_row_num( 1 ),
				'end'   => intval( $page_meta['current_page'] ) === intval( $page_meta['total_pages'] ) ? $page_meta['total_count'] : $page_meta['current_page'] * $page_meta['page_size'],
			);
			?>
			<div class="accredible-pagination-tile">
				<div>
					<?php
					echo esc_html(
						sprintf(
							'Viewing %1s - %2s of %3s %4s',
							$viewing_from_to['start'],
							$viewing_from_to['end'],
							$page_meta['total_count'],
							$page_name
						)
					);
					?>
				</div>

				<div class="accredible-pagination-actions">
					<div>
						<?php
						echo esc_html(
							sprintf(
								'Page %1s of %2s',
								$page_meta['current_page'],
								$page_meta['total_pages']
							)
						);
						?>
					</div>

					<a	<?php disabled( null, $page_meta['prev_page'] ); ?>
						href="<?php echo esc_attr( self::get_pagination_href( $page, $page_meta['prev_page'] ) ); ?>"
						class="button accredible-button-outline-natural accredible-button-small"
						aria-label="Go to next page">
						<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/chevron-left.svg' ); ?>">
					</a>
					<a	<?php disabled( null, $page_meta['next_page'] ); ?>
						href="<?php echo esc_attr( self::get_pagination_href( $page, $page_meta['next_page'] ) ); ?>"
						class="button accredible-button-outline-natural accredible-button-small" 
						aria-label="Go to previous page">
						<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/chevron-right.svg' ); ?>">
					</a>
				</div>
			</div>
			<?php
		}

		/**
		 * Resolves href attribute for pagination.
		 *
		 * @param string $page page.
		 *
		 * @param string $page_num page num used passed to href.
		 *
		 * @return string
		 */
		private static function get_pagination_href( $page, $page_num ) {
			$href = 'javascript:void(0);';
			if ( ! is_null( $page_num ) ) {
				$href = 'admin.php?page=' . $page . '&page_num=' . $page_num;
			}

			return $href;
		}
	}
endif;
