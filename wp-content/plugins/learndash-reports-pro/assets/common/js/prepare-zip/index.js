/**
 * This class is used to create zip files for export.
 */
export default class prepareZipFile {
	constructor() {
	}
	/**
	 * This method is used to handle zip file creation either bulk export or for single exports with more than 30 entries.
	 * @param  {string}  format File Format.
	 * @param  {integer} ref_id Statistics Ref ID.
	 * @param  {integer} page   Page Number.
	 * @param  {strgng}  url    AJAX URL.
	 * @param  {string}  nonce  zip creation process nonce.
	 */
	prepareZip( format, ref_id, page, url, nonce, quiz_id = 0 ) {
		var instance = this;
		jQuery.ajax( {
			type: 'POST',
			url: url,
			timeout: 10000,
			retry_count: 0,
			retry_limit: 1,
			data: {
					action: 'qre_export_statistics',
					file_format: format,
					ref_id: ref_id,
					page: page,
					quiz_export_nonce: nonce,
					quiz_id: quiz_id
				},
			success: function( response ) {
				if ( isNaN( parseInt( response ) ) ) {
					console.log( 'Invalid response' );
					return;
				}
				if ( response.trim() !== '0' ) {
					page++;
					instance.prepareZip( format, ref_id, page, url, nonce, quiz_id );
				} else {
					jQuery( '.qre-download-' + format ).find( 'form' ).remove();
					var html_str =
						'<form id="qre_exp_form_' + nonce + '" method="post" action="" target="_blank" style="display:none;">' +
							'<input name="file_format" type="hidden" value="' + format + '">' +
							'<input name="ref_id" type="hidden" value="' + ref_id + '">' +
							'<input name="quiz_id" type="hidden" value="' + quiz_id + '">' +
							'<input name="quiz_export_nonce" type="hidden" value="' + nonce + '">' +
							'<input name="export_zip" type="hidden" value="true">' +
						'</form>';
					jQuery( '.qre-download-' + format ).append( html_str );

					if ( typeof jQuery( '#wpProQuiz_tabHistory' ).unblock !== 'undefined' ) {
						jQuery('#wpProQuiz_tabHistory').unblock();
					}
					// console.log(html_str);
					jQuery( '#qre_exp_form_' + nonce ).submit();
				}
			},
			error: function( xhr_instance, status, error ) {
				if ( status === 'timeout' ) {
				    this.retry_count++;
				    if ( this.retry_count <= this.retry_limit ) {
				        console.log( 'Retrying' );
				        jQuery.ajax( this );
				        return;
					} else {
						console.error( 'request timed out' );
					}
				} else {
					console.error( error );
				}
			}
		} );
	}
}
