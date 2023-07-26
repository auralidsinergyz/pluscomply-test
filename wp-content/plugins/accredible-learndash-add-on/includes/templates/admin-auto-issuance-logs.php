<?php
/**
 * Accredible LearnDash Add-on admin issuance list page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-table-helper.php';
require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance-log.php';


$accredible_learndash_page          = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$accredible_learndash_current_page  = isset( $_GET['page_num'] ) ? sanitize_key( wp_unslash( $_GET['page_num'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$accredible_learndash_page_size     = 20;
$accredible_learndash_table_columns = array(
	'recipient_name',
	'recipient_email',
	'accredible_group_name',
	'created_at',
	array(
		'key'   => 'error_message',
		'alias' => 'status',
	),
	'credential_url',
);

$accredible_learndash_table_helper = new Accredible_Learndash_Admin_Table_Helper(
	$accredible_learndash_table_columns,
	$accredible_learndash_current_page,
	$accredible_learndash_page_size
);

$accredible_learndash_pagination = Accredible_Learndash_Model_Auto_Issuance_Log::get_paginated_results(
	$accredible_learndash_current_page,
	$accredible_learndash_page_size,
	'',
	array( 'order_by' => 'id DESC' )
);
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title"><?php esc_html_e( 'Issuance Logs' ); ?></h1>
	</div>
	<div class="accredible-content">
		<div class="accredible-table-wrapper">
			<table class="accredible-table">
				<thead>
					<tr class="accredible-header-row">
						<th></th>
						<th>Recipient Name</th>
						<th>Recipient Email</th>
						<th>Accredible Group</th>
						<th>Date Issued</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					echo $accredible_learndash_table_helper->build_table_rows( $accredible_learndash_pagination['results'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</tbody>
			</table>
			<?php
			if ( ! empty( $accredible_learndash_pagination ) && ! empty( $accredible_learndash_pagination['meta'] ) ) :
				Accredible_Learndash_Admin_Table_Helper::build_pagination_tile( $accredible_learndash_page, $accredible_learndash_pagination['meta'], 'issuance logs' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endif;
			?>
		</div>
	</div>
</div>
