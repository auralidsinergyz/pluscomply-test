<?php
/**
 * Database Tools template.
 *
 * @since 4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$report   = $this->system_report->get();
$database = $report['database'];

$request_db_repair = filter_input( 1, 'repair_db_tables' );
$nonce             = filter_input( 1, '_wpnonce' );

if ( 'yes' === $request_db_repair ) :

	$this->db_report->verify_base_tables( true );

	delete_option( 'uncanny_groups_schema_missing_tables' );

	$url = add_query_arg(
		array(
			'action'            => 'db-tools',
			'database_repaired' => 'yes',
			'page'              => 'uncanny-groups-tools-tools',
		),
		admin_url( 'admin.php' )
	);
	// Simply redirect.
	?>
	<script>
		window.location.href="<?php echo $url; ?>";
	</script>
	<?php
endif;

if ( filter_has_var( INPUT_GET, 'database_repaired' ) && 'yes' === filter_input( INPUT_GET, 'database_repaired' ) ) :
	?>
	<div class="notice notice-success is-dismissible">
		<p>
			<strong>
				<?php echo esc_html__( 'Database repaired successfully', 'uncanny-learndash-groups' ); ?>
			</strong>
		</p>
	</div>
<?php endif; ?>

<table id="repair-db-table" class="ulgm_status_table widefat" cellspacing="0">

	<thead>
		<tr>
			<th colspan="3" data-export-label="Database">
				<h2>
					<?php esc_html_e( 'Database', 'uncanny-learndash-groups' ); ?>
					<?php $this->system_report->output_tables_info(); ?>
				</h2>
			</th>
		</tr>
	</thead>

	<tbody>
		<?php if ( ! empty( $database['database_size'] ) && ! empty( $database['database_tables'] ) ) : ?>
			<?php foreach ( $database['database_tables']['uncanny_groups'] as $table => $table_data ) { ?>
				<tr>
					<td>
						<span class="dashicons dashicons-editor-table"></span>
						<?php echo esc_html( $table ); ?>
					</td>
					<td class="help">&nbsp;</td>
					<td>
						<?php if ( ! $table_data ) : ?>
							<?php $msg = strpos( $table, '_view' ) ? __( 'View does not exist', 'uncanny-learndash-groups' ) : __( 'Table does not exist', 'uncanny-learndash-groups' ); ?>
							<mark class="error">
								<span class="dashicons dashicons-database-remove"></span>
								<?php echo esc_html( $msg ); ?>
							</mark>
						<?php else : ?>
							<mark class="yes">
								<span class="dashicons dashicons-database-view"></span>
								<?php
									printf(
										/* Translators: %1$f: Table size, %2$f: Index size, %3$s Engine. */
										esc_html__( 'Data: %1$.2fMB + Index: %2$.2fMB + Engine %3$s', 'uncanny-learndash-groups' ),
										esc_html( $table_data['data'] ),
										esc_html( $table_data['index'] ),
										esc_html( $table_data['engine'] )
									);
								?>
							</mark>	
						<?php endif; ?>
					</td>
				</tr>
			<?php } ?>
		<?php endif; ?>
	</tbody>
</table>

<?php $missing_tables = $this->db_report->verify_base_tables(); ?>

<?php if ( 0 === count( $missing_tables ) ) : ?>

	<h3 style="color:green">
		<span class="dashicons dashicons-yes"></span>
		<?php esc_html_e( 'Everything Ok!', 'uncanny-learndash-groups' ); ?>
	</h3>

<?php else : ?>

	<?php
		$repair_db_uri = add_query_arg(
			array(
				'post_type'        => filter_input( INPUT_GET, 'post_type' ),
				'page'             => filter_input( INPUT_GET, 'page' ),
				'action'           => 'db-tools',
				'repair_db_tables' => 'yes',
				'_wpnonce'         => wp_create_nonce( 'Aut0mAt0r' ),
			),
			admin_url( 'admin.php' )
		);
	?>

	<p>
		<a title="<?php esc_attr_e( 'Repair Uncanny Groups tables', 'uncanny-learndash-groups' ); ?>" 
			href="<?php echo esc_url( $repair_db_uri ); ?>" class="button button-primary">
			<?php echo esc_html__( 'Repair Uncanny Groups tables', 'uncanny-learndash-groups' ); ?>
		</a>
	</p>

<?php endif; ?>
