<?php
/****
 * Template for statstics export modal
 *
 * @package Learndash_Reporting_Pro
 */

?>
<div id="my_bulk_export_popup" style="display:none;">
	<div class="bulk-export-modal">
		<div style="width: 100%;">
			<div class="filter-section">
				<div class="quiz-reporting-custom-filters">
					<div class="selector wrp_user_dropdown">
						<div class="selector-label"><?php esc_html_e( 'Users', 'learndash-reports-pro' ); ?></div>
						<select id="selec_uxample">

						</select>

					</div>
					<div class="selector">
						<div class="selector-label"><?php esc_html_e( 'From date', 'learndash-reports-pro' ); ?></div>
						<input type="text" name="from_date" id="from_date" autocomplete="off">
					</div>
					<div class="selector">
						<div class="selector-label"><?php esc_html_e( 'To date', 'learndash-reports-pro' ); ?></div>
						<input type="text" name="to_date" id="to_date" autocomplete="off">
					</div>


				</div>
				<div class="filter-button-container apply_filters"><button id="apply-filter-button-admin"
						class="apply-bulk-filters"><?php esc_html_e( 'APPLY FILTERS', 'learndash-reports-pro' ); ?></button></div>
			</div>
			<div class="bulk-export-heading">
				<h3><?php esc_html_e( 'Export', 'learndash-reports-pro' ); ?></h3>
				<div>
				<?php esc_html_e( 'Total quiz attempts', 'learndash-reports-pro' ); ?> - <span> ??? </span>
					<div> <?php esc_html_e( 'selected', 'learndash-reports-pro' ); ?></div>
				</div>
			</div>
			<div class="export-attempt-results">
				<div class="report-label"><label><?php esc_html_e( 'Export all quiz attempts result', 'learndash-reports-pro' ); ?></label><span
						class="dashicons dashicons-info-outline tooltip-icon"
						data-title="This report exports the summarized information of all quiz attempts"></span></div>
				<div class="report-export-buttons">
					<button class="export-attempt-csv"><?php esc_html_e( 'CSV', 'learndash-reports-pro' ); ?></button>
					<button class="export-attempt-xlsx"><?php esc_html_e( 'XLSX', 'learndash-reports-pro' ); ?></button>
				</div>
				<div class="export-link-wrapper">
					<div class="bulk-export-download wrld-hidden"></div>
					<div class="bulk-export-progress wrld-hidden"><label><?php esc_html_e( 'Downloading progress', 'learndash-reports-pro' ); ?>:</label><progress
							value="0" max="100"></progress><span></span></div>
				</div>
			</div>
			<div class="export-attempt-learner-answers">
				<div class="report-label"><label><?php esc_html_e( 'Export quiz attempts learner answers', 'learndash-reports-pro' ); ?></label><span
						class="dashicons dashicons-info-outline tooltip-icon"
						data-title="This report exports the actual answers provided by learners for all the quiz attempts"></span>
				</div>
				<div class="report-export-buttons">
					<button class="export-learner-csv"><?php esc_html_e( 'CSV', 'learndash-reports-pro' ); ?></button>
					<button class="export-learner-xlsx"><?php esc_html_e( 'XLSX', 'learndash-reports-pro' ); ?></button>
				</div>
				<div class="export-link-wrapper">
					<div class="bulk-export-download wrld-hidden"></div>
					<div class="bulk-export-progress wrld-hidden"><label><?php esc_html_e( 'Downloading progress', 'learndash-reports-pro' ); ?>:</label><progress
							value="0" max="100"></progress><span></span></div>
				</div>
			</div>
			<div class="export-note"><span><?php esc_html_e( 'Note: We recommend to download atmost 10000 number of quiz attempts to avoid server timeout.', 'learndash-reports-pro' ); ?></span>
			</div>
		</div>
	</div>
</div>
