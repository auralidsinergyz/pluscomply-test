import '../scss/public.scss';

import frontendExporter from './frontend-exporter';
import reportingDashboard from './reporting-dashboard';
// import loadQuizData from './quiz-data';

var exporter = new frontendExporter();
// var data_loader = new loadQuizData();
var dashboard_instance = new reportingDashboard();

exporter.downloadReport( '.qre-export' );
// data_loader.onQuizLoad( '.qre_quiz_data_container', '.quiz_load' );
// data_loader.onUserChange( '.UserID' );

/*New Dashboard Logic*/
// dashboard_instance.toggleDuration(
// 	'.quiz-report-filters',
// 	'.date-filter input[type=checkbox]'
// );
// dashboard_instance.initDatepicker(
// 	['.quiz-report-filters input[name=from_date]', '.quiz-report-filters input[name=to_date]'] );
dashboard_instance.livesearch(
	'.quiz-report-filters #qre-search-field',
	'.search_result_type',
	'.search_result_id'
);
dashboard_instance.showDatatable( '#qre_summarized_data' );
dashboard_instance.paginateReportsTable(
	'.pagination-form',
	'select.limit',
	'.pagination-section input.page',
	'.pagination-section button.previous-page',
	'.pagination-section button.next-page'
);
// dashboard_instance.customDatepicker(
// 	['.custom-report-filters input[name=enrollment_from]', '.custom-report-filters input[name=enrollment_to]'] );
// dashboard_instance.customDatepicker(
// 	['.custom-report-filters input[name=completion_from]', '.custom-report-filters input[name=completion_to]'] );
// dashboard_instance.saveCustomReports( '.custom-report-filters', '.save_config' );
// dashboard_instance.toggleFilterGroups( '.custom-report-filters', '.section-control' );
// dashboard_instance.previewCustomReport( '.custom-report-filters', '.preview-data', '.save_config' );
// dashboard_instance.enableGenerateButton( '.custom-report-filters', '.preview-data' );
// dashboard_instance.onScrollStickyElement();
dashboard_instance.toggleHiddenRow( '#custom-reports', '.accordion-target', '.accordion-trigger' );
dashboard_instance.showBulkExportModal( '.wrld-bulk-export', '.button-bulk-export' );