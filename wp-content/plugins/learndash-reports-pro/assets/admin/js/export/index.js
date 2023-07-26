/**
 * Makes ajax request and pull data in JSON format, and creates <form> to submit data to generate .CSV/.XSLX file.
 * @var object aelInterval Contains the setInterval instance used to add export links to each row.
 * @var string html Contains each row's html structure.
 * @var boolean filter_flag Becomes true whenever a filtering operation takes place.
 */
/* globals qre_export_obj: false */
export default class Exporter {
	/**
	 * This method is used to define/initialize the class properties.
	 */
	constructor() {
		this.aelInterval 	= null;
		this.html 			= '';
		this.filter_flag	= false;
	}
	/**
	 * This method is called whenever a filtering operation is executed.
	 * @param  {string} selector Selector for the filter.
	 */
	enableFilterflag( selector ) {
		var instance = this;
		jQuery( selector ).click( function() {
			instance.filter_flag = true;
		} );
	}
	/**
	 * This method is used to execute export operation.
	 * @param  {string} selector Selector for the element clicked.
	 */
	startExportAll( selector ) {
		var instance = this;
		jQuery( 'body' ).on( 'click', selector, function( evnt ) {
			evnt.preventDefault();
			var $self = jQuery( this );
			if ( 'undefined' === typeof qre_export_obj ) {
				console.log( 'Localized Data Not Found' );
				return false;
			}
			if ( instance.filter_flag ) {
				var user_ref_id = '';
				jQuery( 'table.wp-list-table .user_statistic' ).each( function() {
					if( jQuery( this ).attr( 'data-ref_id' ) !== undefined ) {
						user_ref_id = ( user_ref_id !== '' ) ? 
							user_ref_id + ',' + jQuery( this ).attr( 'data-ref_id' ) : 
							jQuery( this ).attr( 'data-ref_id' );
					}
				} );
				if ( user_ref_id !== '' ) {
					jQuery( '.qre-export-all' ).attr( 'data-ref_id', user_ref_id );
				} else {
					return;
				}
			}
		
			var ref_id = $self.attr( 'data-ref_id' );
			var format = '';
			if ( $self.hasClass( 'qre-download-csv' ) ) {
				format = 'csv';
			}
			if ( $self.hasClass( 'qre-download-xlsx' ) ) {
				format = 'xlsx';
			}
			jQuery( '#qre_exp_form_' + qre_export_obj.quiz_export_nonce ).remove(); // removes previously generated form
			jQuery( '#qre_error' ).remove(); // removes previously generated errors

			if ( ref_id !== '' && ref_id !== undefined ) {
				var refs = ref_id.split( ',' );
				if ( refs.length > 1 ) {
					if ( typeof jQuery('#wpProQuiz_tabHistory').block !== 'undefined' ){
						jQuery( '#wpProQuiz_tabHistory' ).block( {
							message: qre_export_obj.processing_text,
							css: { border: '3px solid #a00' }
						} );
					}
					window.prepareZipFile.prototype.prepareZip(
						format,
						ref_id,
						0,
						qre_export_obj.ajax_url,
						qre_export_obj.quiz_export_nonce,
						qre_export_obj.quiz_pro_id
					);
				} else {
					$self.find( 'form' ).remove();
					var html_str =
					'<form id="qre_exp_form_' + qre_export_obj.quiz_export_nonce + '" method="post" action="" target="_blank" style="display:none;">' +
						'<input name="file_format" type="hidden" value="' + format + '">' +
						'<input name="ref_id" type="hidden" value="' + ref_id + '">' +
						'<input name="quiz_export_nonce" type="hidden" value="' + qre_export_obj.quiz_export_nonce + '">' +
					'</form>';
					$self.append( html_str );
					$self.find( 'form' ).submit();
					jQuery( '#qre_loader' ).remove();
				}
			}
		} );
	}
	/**
	 * This method is used to add statistics ids for all entries in the bulk export buttons.
	 */
	addAllStatisticIds() {
		if ( 'undefined' === typeof qre_export_obj ) {
			console.log( 'Localized Data Not Found' );
			return false;
		}
		// to add all stat IDs to export in export all button
		if ( qre_export_obj.all_ids !== 'undefined' && qre_export_obj.all_ids !== '' ) {
			// jQuery( '.wpProQuiz_update' ).before(
			// 	'<a id="qre_export_all" href="#" '+
			// 		'data-ref_id="' + qre_export_obj.all_ids + '"' +
			// 		' class="qre-export qre-export-all button-secondary qre-download-csv" style="margin-right:3px;">' +
			// 		qre_export_obj.qre_msg_export_all_in_csv +
			// 	'</a>' +
			// 	'<a id="qre_export_all" href="#" '+
			// 		'data-ref_id="' + qre_export_obj.all_ids + '"'+
			// 		' class="qre-export qre-export-all button-secondary qre-download-xlsx" style="margin-right:3px;">' +
			// 		qre_export_obj.qre_msg_export_all_in_excel +
			// 	'</a>'
			// );

			jQuery( '.wpProQuiz_update' ).before(
				'<a href="#TB_inline?&width=720&height=630&inlineId=my_bulk_export_popup" class="button-secondary thickbox" style="margin-right: 5px;" id="wdm_export_btn">'+qre_export_obj.export_btn_text+'</a>'
			);
			
			var $qre_notice = jQuery( 'a.wpProQuiz_update' ).parent();
			jQuery( $qre_notice ).each( function( index ) {
				jQuery( $qre_notice[ index ] ).after(
					'<div class="qre_gdpr_notice">' +
						qre_export_obj.notice_text +
					'</div>'
				);
			} );
		}
	}
	/**
	 * This method is used to add export link to each individual row.
	 */
	addExportLink() {
		// to check if content is updated then run our function
		var is_content_updated = jQuery( '#wpProQuiz_historyLoadContext tr' ).eq( 1 ).html();

		// This has been added because, only checking of HTML element was not sufficient. This will check for number of rows in the div
		// If rows are zero then don't clear interval.
		var rows_found = jQuery( '#wpProQuiz_historyLoadContext tr' ).length;
		//console.log("tr length = "+rows_found);
		if ( rows_found === 0 ) {
			return false;
		}

		//if ( jQuery( "#wpProQuiz_statistics_form_data" ).length > 0 ) { // if Learndash core ajax call is completed append links

		// if data not found, clear interval
		if ( typeof is_content_updated !== 'undefined' ) {
			if ( is_content_updated.length < 200 ) {
				clearInterval( this.aelInterval );
				jQuery( '.qre-export' ).remove();
			}
		}
        
		// if content is updated than add column
		if ( this.html !== is_content_updated ) {

			jQuery( '#qre_all_Exportexport' ).remove(); // removes <th> of a export column
			jQuery( '.qre-export-th' ).remove(); // removes all <td> of a export column

			jQuery( '#wpProQuiz_historyLoadContext .wp-list-table > thead > tr' ).append(
				'<th id="qre_all_Exportexport">' +
					qre_export_obj.qre_msg_export +
				'</th>'
			);

			var all_statistic_ref_ids = '';

			jQuery( '#wpProQuiz_statistics_form_data > tr' ).each( function() {

				var ref_id = jQuery( this ).find( '.user_statistic' ).attr( 'data-ref_id' );

				if ( ref_id !== '' && ref_id !== undefined ) {
					/* qre-download-xlsx  qre-download-csv*/
					jQuery( this ).append(
						'<th class="qre-export-th">' +
							qre_export_obj.qre_msg_export_response +
							'<a href="#" data-ref_id="' + ref_id + '" class="qre-export qre-download-csv">'+
								qre_export_obj.qre_msg_csv +
							'</a>'+
							'/' +
							'<a href="#" data-ref_id="' + ref_id + '" class="qre-export qre-download-xlsx">' +
								qre_export_obj.qre_msg_excel +
							'</a>'+
						'</th>'
					);
					all_statistic_ref_ids += ref_id + ',';
				}
			} );

			//console.log('all_statistic_ref_ids='+all_statistic_ref_ids);

			all_statistic_ref_ids = all_statistic_ref_ids.substring( 0, all_statistic_ref_ids.length - 1 );

			/*if (all_statistic_ref_ids !== '') {
				// jQuery( ".wpProQuiz_update" ).before(  '<a id="qre_export_all" href="#" data-ref_id="' + all_statistic_ref_ids + '" class="qre-export button-secondary" style="margin-right:3px;">Export</a>'  );
				//jQuery( '#qre_all_export' ).html( '<a href="#" data-ref_id="' + all_statistic_ref_ids + '" class="qre-export">Export</a>' );
	//console.log(jQuery(".qre-export.button-secondary#qre_export_all").length);
				//jQuery(".qre-export.button-secondary#qre_export_all").attr('data-ref_id', all_statistic_ref_ids);
			}*/
			clearInterval( this.aelInterval );
		}
	}
	/**
	 * This method is used for adding export links at set intervals. 
	 */
	triggerExportLinks() {
		this.aelInterval = setInterval( this.addExportLink.bind( this ), 500 );
	}
	/**
	 * This method is used for adding export links at set intervals after some navigation events occur,
	 * @param  {string} selector Selector
	 * @param  {String} event    Event
	 */
	triggerExportLinksOnEvents( selector, event = 'click' ) {
		var instance = this;
		jQuery( document ).on( event, selector, function() {
			instance.html = jQuery( '#wpProQuiz_historyLoadContext tr' ).eq( 1 ).html();
			instance.aelInterval = setInterval( instance.addExportLink.bind( instance ), 500 );
		} );
	}
	/**
	 * This method is used to handle operations on History tab open.
	 */
	onOpenHistoryTab() {
		var instance = this;
		jQuery( document ).on( 'click', '.wpProQuiz_tab_wrapper a', function() {
			if ( jQuery( this ).attr( 'data-tab' ) === '#wpProQuiz_tabHistory' ) {
				instance.html = jQuery( '#wpProQuiz_historyLoadContext tr' ).eq( 1 ).html();
				instance.aelInterval = setInterval( instance.addExportLink.bind( instance ), 500 );
			}
		} );
	}
}
