/**
 * This class is used to handle the functionality of exporting in the website frontend.
 */
/* globals qre_export_obj: false */
export default class frontendExporter {
	constructor() {
	}
	/**
	 * This method is used to handle the export feature in the website frontend.
	 * @param  {string} selector The selector of the download link.
	 */
	downloadReport( selector ) {
		var instance = this;
		jQuery( 'body' ).on( 'click', selector, function( evnt ) {
			evnt.preventDefault();
			var $self = jQuery( this );
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
					if ( typeof jQuery( '.entry-content' ).block !== 'undefined' ) {
						jQuery( '.entry-content' ).block( {
							message: '<h4>Processing...</h4>',
							css: { border: '3px solid #a00' }
						} );
					}
					window.prepareZipFile.prototype.prepareZip(
						format,
						ref_id,
						0,
						qre_export_obj.ajax_url,
						qre_export_obj.quiz_export_nonce
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
		});
	}
}
