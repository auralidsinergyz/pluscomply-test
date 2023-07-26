/**
 * This class iz used to load quiz data and other UI events related to the shortcode.
 */
/*globals quiz_statistics_data:false, qre_export_obj: false*/
window.$ = jQuery;
// var dt2 = require('datatables.net-buttons');
var dt = require('datatables.net-responsive-dt');
var JSZip = require( 'jszip' );
window.$.fn.DataTable = dt;
window.$.fn.DataTable.Buttons.jszip(JSZip);

jQuery.fn.outerHTML = function() {
  return jQuery('<div />').append(this.eq(0).clone()).html();
};

import resultFilters from './result-filters';
import customReports from './custom-reports';
import 'datatables.net-buttons';
import 'datatables.net-buttons/js/buttons.html5';


export default class reportingDashboard {
	constructor() {
		this.result_filters = new resultFilters();
		this.custom_reports = new customReports();
		this.quiz_reports_table = null;
		this.custom_reports_table = null;
	}
	toggleDuration( container, selector ) {
		this.result_filters.toggle( container, selector );
	}
	initDatepicker( selectors ) {
		this.result_filters.datepicker_init( selectors );
	}
	livesearch( selector, type_selector, id_selector ) {
		this.result_filters.search( selector );
		this.result_filters.select_result( selector, type_selector, id_selector );
		this.result_filters.reset_search( selector, type_selector, id_selector );
	}
	showDatatable( selector ) {
		var instance = this;
		jQuery( document ).ready(function(){
			if ( typeof quiz_statistics_data === 'undefined' ) {
				return;
			}
			var $quiz_title = null;
			var $quiz_title_text = '';
			var $user_name = null;
			var $user_name_text = '';
			for ( var i = 0; i < quiz_statistics_data.data.length; i++ ) {
				if ( jQuery( window ).width() < 1500 ) {
					$quiz_title      = jQuery( quiz_statistics_data.data[ i ].quiz_title );
					$quiz_title_text = $quiz_title.text().substring( 0 , 30 );
					if ( $quiz_title.text().length > 30 ) {
						$quiz_title_text += '...';		
					}
					$quiz_title.text( $quiz_title_text );
					quiz_statistics_data.data[ i ].quiz_title = $quiz_title.outerHTML();
					$user_name      = jQuery( quiz_statistics_data.data[ i ].user_name );
					$user_name_text = $user_name.text().substring( 0 , 30 );
					if ( $user_name.text().length > 30 ) {
						$user_name_text += '...';		
					}
					$user_name.text( $user_name_text );
					quiz_statistics_data.data[ i ].user_name = $user_name.outerHTML();
				}
				quiz_statistics_data.data[ i ].index = i + 1;
			}
			instance.quiz_reports_table = jQuery( selector ).DataTable(
			{
				paging: false,
                ordering: false,
                searching: false,
                info: false,
                responsive: false,
				data: quiz_statistics_data.data,
				columns: [
		            { data: 'index', visible: true },
		            { data: 'quiz_title', visible: true },
		            { data: 'user_name', visible: true },
		            { data: 'date_attempt', visible: true },
		            { data: 'score', visible: true },
		            { data: 'time_taken', visible: true },
		            { data: 'link', visible: true },
		        ],
		        columnDefs: [
		            {
		                targets: [ 0, 3, 4, 5, 6 ],
		          	    className: 'dt-center'
		            },
		            {
		            	targets: [ 1, 2 ],
		            	className: 'dt-left'
		            }
	            ],
	            language: {
                  emptyTable: quiz_statistics_data.no_data
                }
			});
		});
	}
	paginateReportsTable( pagination_form, limit_selector, page_number_selector, previous_page_btn, next_page_button ) {
		var instance = this;
		jQuery( document ).ready(function(){
			window.change_entry_count( pagination_form, limit_selector );
			window.change_page_number( pagination_form, page_number_selector, previous_page_btn, next_page_button );
		});
	}
	customDatepicker( selectors ) {
		this.custom_reports.datepicker_init( selectors );
	}
	saveCustomReports( container, button ) {
		var instance = this;
		jQuery(document).ready(function(){
			instance.custom_reports.save_config( container, button );
		});
	}
	toggleFilterGroups( container, button ) {
		var instance = this;
		jQuery(document).ready(function(){
			instance.custom_reports.toggle_filter_group( container, button );
		});	
	}
	previewCustomReport( container, preview_button, save_button ) {
		var instance = this;
		jQuery(document).ready(function(){
			instance.custom_reports.fetch_data( preview_button, save_button );
			jQuery(document).on( 'custom_report_created', function(evnt, dat) {
				/*if ( jQuery.fn.DataTable.isDataTable( '#custom-reports' ) ) {
					instance.custom_reports_table.fnDestroy();
					column_slugs = [];
					jQuery( container ).find( 'input[type="checkbox"]' ).each( function( ind, ele ) {
						if ( jQuery( ele ).is( ':checked' ) ) {
							if ( jQuery( '#custom-reports' ).find( 'th[data-column=' + jQuery( ele ).attr( 'name' ) + ']' ).length === 0 ) {
								jQuery( 'table thead tr' ).append(
									'<th data-column="' + jQuery( ele ).attr( 'name' ) + '">' +
										jQuery( 'label[for=' + jQuery( ele ).attr( 'name' ) + ']' ).text() +
									'</th>'
								);
							}
						} else {
							if ( jQuery( '#custom-reports' ).find( 'th[data-column=' + jQuery( ele ).attr( 'name' ) + ']' ).length > 0 ) {
								var $table_column = jQuery( 'table th[data-column="' + jQuery( ele ).attr( 'name' ) + '"]' );
								var cell_index = $table_column.index();
								$table_column.closest('table').find('tr').each(function() {
							        if ( typeof this.cells[ cell_index ] !== 'undefined' ) {
								        this.removeChild( this.cells[ cell_index ] );
							        }
							    });
							}
						}
					} );
				}*/
				jQuery('#custom-reports').show();
				/*var headings = jQuery('#custom-reports th');
				var column_slugs = [];
				headings.each(function(index, ele){
					column_slugs.push({ data: jQuery(ele).attr('data-column'), className: 'px200' });
				});
				instance.custom_reports_table = jQuery( '#custom-reports' ).DataTable(
				{
	                responsive: true,
					dom: '<"html5buttons"B>lTfgtip',
			        buttons: [
				        { extend: 'csv', text: qre_export_obj.download_csv_text},
				        { extend: 'excel', text: qre_export_obj.download_xls_text },
			        ],
	                'bDestroy': true,
					data: dat,
					columns: column_slugs,
					destroy: true,
				});
				jQuery('.dt-buttons').appendTo( '.custom-report-buttons' );*/
			} );
		});	
	}
	enableGenerateButton( container, preview_button ) {
		var instance = this;
		jQuery( container ).on( 'change', 'select', function() {
			jQuery( preview_button ).css( 'visibility', 'visible' );
		} );
		jQuery( container ).on( 'change click', 'input[type=checkbox], input[type=text]', function() {
			jQuery( preview_button ).css( 'visibility', 'visible' );
		} );
		/*jQuery( container ).on( 'change', 'input[type=checkbox]', function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				if ( ! jQuery.fn.DataTable.isDataTable( '#custom-reports' ) ) {
					jQuery( 'table thead tr' ).append(
						'<th data-column="' + jQuery( this ).attr( 'name' ) + '">' +
							jQuery( 'label[for=' + jQuery( this ).attr( 'name' ) + ']' ).text() +
						'</th>'
					);
				}
			} else {
				if ( ! jQuery.fn.DataTable.isDataTable( '#custom-reports' ) ) {
					var $table_column = jQuery( 'table th[data-column="' + jQuery(this).attr( 'name' ) + '"]' );
					var cell_index = $table_column.index();
					$table_column.closest('table').find('tr').each(function() {
				        if ( typeof this.cells[ cell_index ] !== 'undefined' ) {
					        this.removeChild( this.cells[ cell_index ] );
				        }
				    });
				}
			}
		} );*/
	}
	onScrollStickyElement() {
		jQuery( window ).on( 'load resize', function() {
			var sticky_section = jQuery( '.sticky-custom-report-buttons' );
			if ( sticky_section.length === 0 ) {
				return;
			}
			var sticky = jQuery( '.custom-reports-content' ).offset().top;
			jQuery( window ).on( 'scroll', function(){
				if ( window.pageYOffset > sticky ) {
			        jQuery('.dt-buttons').appendTo( '.sticky-custom-report-buttons' );
			        sticky_section.addClass( 'sticky' );
				} else {
			        jQuery('.dt-buttons').appendTo( '.custom-report-buttons' );
			        sticky_section.removeClass( 'sticky' );
			    }
			});
		});
	}
	toggleHiddenRow( container, target, selector ) {
		jQuery( document ).on( 'click', selector, function( evnt ) {
			var $self   = jQuery( this );
			var $parent = $self.parents( '.row_data' );
			var $target = $parent.next();
			$target.toggleClass( 'collapse' );
			$self.toggleClass( 'active' );
			$parent.toggleClass( 'expanded' );
		});
	}

	showBulkExportModal( source, target ) {
		jQuery( document ).on( 'click', source, function() {
			jQuery( target ).trigger( 'click' );
		} );
	}
}
