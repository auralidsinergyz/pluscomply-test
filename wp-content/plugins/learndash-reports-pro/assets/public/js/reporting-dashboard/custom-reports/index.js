/**
 * This class iz used to load quiz data and other UI events related to the shortcode.
 */
/* globals qre_export_obj: false */
import datepicker from 'js-datepicker';

/**
 * This class does all the processing related to filtering results.
 */
export default class customReports {
	constructor() {
	}
	save_config( container, button ) {
		jQuery( button ).on( 'click', function(){
			let form_data = {};
			
			jQuery( container ).find( 'input[type=checkbox]' ).each( function( ind, el ){
				if ( jQuery( el ).is( ':checked' ) ) {
					form_data[ jQuery( el ).attr( 'name' ) ] = jQuery( el ).val();
					// form_data.append( jQuery( el ).attr( 'name' ), jQuery( el ).val() );
				}
			});
			jQuery( container ).find( 'select, input[type=text]' ).each( function( ind, el ){
				form_data[ jQuery( el ).attr( 'name' ) ] = jQuery( el ).val();
			});

			jQuery( button ).attr( 'disabled', 'disabled' );
			jQuery.ajax(
			{
				type: 'POST',
				url: qre_export_obj.ajax_url,
				timeout: 10000,
				retry_count: 0,
				retry_limit: 1,
				data: {
					action: 'qre_save_filters',
					security: qre_export_obj.custom_reports_nonce,
					fields: form_data,
				},
				success: function( result ) {
					jQuery( button ).removeAttr( 'disabled' );
					jQuery( document ).trigger( 'custom_reports_config_set' );
					result = result;
				},
				error: function( xhr, status, error_thrown ) {
					if ( status === 'timeout' ) {
					    this.retry_count++;
					    if ( this.retry_count <= this.retry_limit ) {
					        console.log( 'Retrying' );
					        jQuery.ajax( this );
					        return;
						} else {
							console.error( 'request timed out' );
							jQuery( button ).removeAttr( 'disabled' );
							jQuery( document ).trigger( 'custom_reports_config_set' );
						}
					} else {
						console.log( error_thrown );
						jQuery( button ).removeAttr( 'disabled' );
						jQuery( document ).trigger( 'custom_reports_config_set' );
					}
				} 
			}
			);
		});
	}
	toggle_filter_group( container, button ) {
		jQuery( container ).on( 'click', button, function(){
			var $this = jQuery( this );
			var $all = jQuery( button );
			var open = false;
			if ( $this.hasClass( 'expanded' ) ) {
				open = true;
			}
			$all.removeClass( 'expanded' );
			$all.parents( '.section' ).find( '.section-body' ).removeClass( 'expanded' );
			if ( ! open ) {
				$this.toggleClass( 'expanded' );
				$this.parents( '.section' ).find( '.section-body' ).toggleClass( 'expanded' );
			}
		} );
	}
	fetch_data( button, save_button ) {
		jQuery( button ).on( 'click', function() {
			jQuery('.custom-reports-content').addClass('loading');
			// jQuery( button ).css( 'visibility', 'hidden' );
			jQuery( save_button ).trigger( 'click' );
			setTimeout( function() {
				jQuery( button ).text( qre_export_obj.preview_report_btn_text );
			}, 1000	);
		});
		jQuery( document ).on( 'custom_reports_config_set', function() {
			jQuery.ajax(
			{
				type: 'GET',
				url: window.location.href,
				timeout: 60000,
				retry_count: 0,
				retry_limit: 1,
				data: {
					action: 'qre_get_filters',
					security: qre_export_obj.fetch_custom_reports,
				},
				success: function( result ) {
					// if ( ! result.success ) {
					// 	jQuery('.custom-reports-content').removeClass('loading');
					// 	jQuery( button ).css( 'visibility', 'visible' );
					// 	return;
					// }
					//jQuery('.custom-reports-container').html( jQuery( result ).find( '.custom-reports-container' ).html() );
					// jQuery(document).trigger('custom_report_created', [compiled_data] );
					// jQuery('.custom-reports-container').html( jQuery( result ).find( '.custom-reports-container' ).html() );
					jQuery('.custom-reports-content').removeClass('loading');
					window.location.replace( qre_export_obj.first_custom_url );
				},
				error: function( xhr, status, error_thrown ) {
					if ( status === 'timeout' ) {
					    this.retry_count++;
					    if ( this.retry_count <= this.retry_limit ) {
					        console.log( 'Retrying' );
					        jQuery.ajax( this );
					        return;
						} else {
							console.error( 'request timed out' );
							// jQuery( button ).removeAttr( 'disabled' );
							jQuery('.custom-reports-content').removeClass('loading');

						}
					} else {
						console.log( error_thrown );
						// jQuery( button ).removeAttr( 'disabled' );
						jQuery('.custom-reports-content').removeClass('loading');
					}
				} 
			}
			);
		} );
	}
	/**
	 * This method is used to initialize both the date selectors and establish a daterange relation between them.
	 * @param  {string} selectors Selectors for both the date fields.
	 */
	datepicker_init( selectors ) {
		var did = Math.floor(Math.random() * Math.floor(100));
		var args = {
			id: did,
			formatter: (input, date, instance) => {
				instance = instance;
				const value = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
				input.value = value; // => 'F j, Y'
			}
		};
		var args_clone;
		for ( var counter_i = 0; counter_i <= selectors.length - 1; counter_i++ ) {
			if ( jQuery( selectors[ counter_i ] ).length === 0 ) {
				continue;
			}
			args_clone = Object.assign( {}, args );
 			if ( jQuery( selectors[ counter_i ] ).val().length !== 0 ) {
				args_clone.dateSelected = new Date( jQuery( selectors[ counter_i ] ).val() );
			}
			datepicker( selectors[ counter_i ], args_clone );
		}
	}
}
