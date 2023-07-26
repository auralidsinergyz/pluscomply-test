<?php
/**
 * Accredible LearnDash Add-on auto issuance list helper
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-table-helper.php';

if ( ! class_exists( 'Accredible_Learndash_Auto_Issuance_List_Helper' ) ) :
	/**
	 * Accredible LearnDash Add-on auto issuance list helper class
	 */
	class Accredible_Learndash_Auto_Issuance_List_Helper {
		const TABLE_COLUMNS = array(
			'post_id',
			array(
				'key'   => 'kind',
				'alias' => 'post_type',
			),
			'accredible_group_id',
			'kind',
			'created_at',
		);
		const ROW_ACTIONS   = array(
			array(
				'action' => 'edit_auto_issuance',
				'label'  => 'Edit',
			),
			array(
				'action' => 'delete_auto_issuance',
				'label'  => 'Delete',
			),
		);

		/**
		 * Displays auto issuance list information html.
		 *
		 * @param mixed $page_results auto issuance list and page meta.
		 * @param int   $current_page current page.
		 * @param int   $page_size number of results shown in page.
		 */
		public static function display_auto_issuance_list_info( $page_results, $current_page, $page_size ) {
			$table_helper = new Accredible_Learndash_Admin_Table_Helper(
				self::TABLE_COLUMNS,
				$current_page,
				$page_size,
				self::ROW_ACTIONS
			);
			?>
			<div class="accredible-table-wrapper">
				<table class="accredible-table">
					<thead>
						<tr class="accredible-header-row">
							<th></th>
							<th>Name</th>
							<th>Type</th>
							<th>Accredible Group</th>
							<th>Issuance Trigger</th>
							<th>Date Created</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						echo $table_helper->build_table_rows( $page_results['results'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</tbody>
				</table>
				<?php
				if ( ! empty( $page_results ) && ! empty( $page_results['meta'] ) ) :
					Accredible_Learndash_Admin_Table_Helper::build_pagination_tile( 'accredible_learndash_issuance_list', $page_results['meta'], 'auto issuances' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				endif;
				?>
			</div>
			<?php
		}
	}
endif;
