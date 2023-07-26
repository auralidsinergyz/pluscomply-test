<?php
/**
 * Template is used to show quiz table on the frontend shortcode..
 *
 * @package Quiz Reporting Extension
 * @since 3.0.0
 */

global $wp;
$current_url  = get_permalink();
$query_string = filter_input( INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING );
// The below if condition is to check for a php bug. https://bugs.php.net/bug.php?id=49184.
if ( empty( $query_string ) && isset( $_SERVER['QUERY_STRING'] ) ) { // phpcs:ignore
	$query_string = sanitize_text_field( $_SERVER['QUERY_STRING'] ); // phpcs:ignore
}
if ( ! empty( $query_string ) ) {
	$current_url = add_query_arg( $query_string, '', home_url( $wp->request ) );
}
?>
<div class="results-section">				
	<?php /* translators: %s: Number of reports. */ ?>
	<div class="total_count"><span><?php echo sprintf( _n( '%s Report', '%s Reports', $total_count, 'learndash-reports-pro' ), '<strong>' . $total_count . '</strong>' );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></div>
	<?php if ( ! empty( $filter_nonce ) && wp_verify_nonce( $filter_nonce, 'filter_quiz_results' ) ) : ?>
		<div class="tags">
			<?php if ( ! empty( $queried_string ) ) : ?>
				<div class="tag search-tag">
					<span><?php echo esc_html( $queried_string ); ?></span>
					<a class="reset_filter" href="<?php echo esc_url( remove_query_arg( array( 'qre-search-field', 'search_result_id', 'search_result_type' ), $current_url ) ); ?>"></a>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $date_filter ) && 'on' === $date_filter ) : ?>
				<?php if ( ! empty( $from_date ) ) : ?>
					<div class="tag search-tag">
						<?php /* translators: %s: From Date */ ?>
						<span><?php echo esc_html( sprintf( __( 'From %s', 'learndash-reports-pro' ), date_i18n( get_option( 'date_format', 'd-M-Y' ), strtotime( $from_date ) ) ) ); ?></span>
						<a class="reset_filter" href="<?php echo esc_url( remove_query_arg( array( 'from_date' ), $current_url ) ); ?>"></a>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $to_date ) ) : ?>
					<div class="tag search-tag">
						<?php /* translators: %s: To Date */ ?>
						<span><?php echo esc_html( sprintf( __( 'To %s', 'learndash-reports-pro' ), date_i18n( get_option( 'date_format', 'd-M-Y' ), strtotime( $to_date ) ) ) ); ?></span>
						<a class="reset_filter" href="<?php echo esc_url( remove_query_arg( array( 'to_date' ), $current_url ) ); ?>"></a>
					</div>
				<?php endif; ?>
			<?php elseif ( empty( $date_filter ) && ! empty( $time_period ) ) : ?>
				<div class="tag search-tag">
					<?php /* translators: %s: From Date */ ?>
					<span><?php echo esc_html( sprintf( __( 'From %s', 'learndash-reports-pro' ), date_i18n( get_option( 'date_format', 'd-M-Y' ), strtotime( "-1 {$time_period}" ) ) ) ); ?></span>
					<a class="reset_filter" href="<?php echo esc_url( remove_query_arg( array( 'period', 'from_date', 'to_date', 'filter_type' ), $current_url ) ); ?>"></a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="entries-shown">
		<form method="get" class="pagination-form">
			<select class="limit" name="limit">
				<option value="5" <?php selected( $limit, 5 ); ?>><?php echo esc_html__( 'Show 5', 'learndash-reports-pro' ); ?></option>
				<option value="10" <?php selected( $limit, 10 ); ?>><?php echo esc_html__( 'Show 10', 'learndash-reports-pro' ); ?></option>
				<option value="20" <?php selected( $limit, 20 ); ?>><?php echo esc_html__( 'Show 20', 'learndash-reports-pro' ); ?></option>
			</select>
			<input type="hidden" class="hidden-page" name="pageno" value="<?php echo esc_attr( $page ); ?>">
		</form>
	</div>
	<table id="qre_summarized_data" class="qre_summarized_data row-border" style="width: 100%;">
		<thead>
			<tr>
				<th><?php echo esc_html__( 'No.', 'learndash-reports-pro' ); ?></th>
				<?php /* Translators: %s: Quiz Label */ ?>
				<th><?php echo esc_html( sprintf( _x( '%s Title', 'Quiz Label','learndash-reports-pro' ), learndash_get_custom_label( 'quiz' ) ) ); ?></th>
				<th><?php echo esc_html__( 'Student Name', 'learndash-reports-pro' ); ?></th>
				<th><?php echo esc_html__( 'Date of Attempt', 'learndash-reports-pro' ); ?></th>
				<th><?php echo esc_html__( 'Score', 'learndash-reports-pro' ); ?></th>
				<th><?php echo esc_html__( 'Time Taken', 'learndash-reports-pro' ); ?></th>	
				<th><?php echo esc_html__( 'Download', 'learndash-reports-pro' ); ?></th>
			</tr>
		</thead>
	</table>
	<?php if ( $pages > 1 ) : ?>
		<div class="pagination-section">
			<button class="previous-page" <?php disabled( $page, 1 ); ?>><?php echo esc_html__( 'Previous', 'learndash-reports-pro' ); ?></button>
			<?php /* translators: %1$s: HTML Input for pagination, %2$d: Total Number of pages. */ ?>
			<span><?php echo sprintf( __( 'Page %1$s of %2$d', 'learndash-reports-pro' ), '<input type="text" class="page" value="' . $page . '" data-max="' . $pages . '"/>', $pages );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<button class="next-page" <?php disabled( $page, $pages ); ?>><?php echo esc_html__( 'Next', 'learndash-reports-pro' ); ?></button>
		</div>
	<?php endif; ?>
</div>
<?php
