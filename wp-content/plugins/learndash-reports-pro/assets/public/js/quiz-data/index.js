/**
 * This class iz used to load quiz data and other UI events related to the shortcode.
 */
/* globals qre_export_obj: false */
export default class loadQuizData {
	constructor() {
	}
	/**
	 * This method handles the activities that occur on clicking a quiz in the shortcode.
	 * @param  {string} container Selector for the quiz container
	 * @param  {string} selector  Quiz Title selector.
	 */
	onQuizLoad( container, selector ) {
		var instance = this;
		jQuery( container ).on( 'click', selector, function() {
			jQuery( '.qre_prev_div' ).slideUp( 'slow' );
			jQuery( '#qre_loader' ).show();
			var $ref_ids 		= jQuery( this ).siblings();
			var $qre_new_div = jQuery( this ).closest( '.qre_quiz_data' );
			var attr_val	= jQuery( $qre_new_div ).attr( 'data-counter' );
			// To send the request only once for single attempt
			if (
				jQuery( '#qre_quiz_details_' + attr_val ).html() !== '' &&
				jQuery( '#qre_quiz_details_' + attr_val ).css( 'display' ) === 'none'
			) {
				jQuery( '#qre_loader' ).hide();
				jQuery( '#qre_quiz_details_' + attr_val ).slideDown( 'slow' );
				return false;
			} else if (
				jQuery( '#qre_quiz_details_' + attr_val ).html() !== '' &&
				jQuery( '#qre_quiz_details_' + attr_val ).css( 'display' ) !== 'none'
			) {
				jQuery( '#qre_loader' ).hide();
				return false;
			}
			//To check that quiz deails div have no data.
			if (
				jQuery( '#qre_quiz_details_' + attr_val ).children().length === 0 ||
				jQuery( '#qre_quiz_details_' + attr_val ).css( 'display' ) === 'none'
			) {
				var data_0 = {
					action: 'display_attempted_questions',
					'quiz_id': $ref_ids[ 1 ].value,
					'time_id': $ref_ids[ 0 ].value,
					'user_id': jQuery( '.UserID' ).val(),
					'stat_ref_id': $ref_ids[ 2 ].value
				};
				jQuery.ajax( {
					type: 'POST',
					url: qre_export_obj.ajax_url,
					data: data_0,
					timeout: 10000,
					retry_count: 0,
					retry_limit: 1,
					success: (msg) => {
						jQuery( '#qre_quiz_details_' + attr_val ).html( msg );	
						jQuery( '#qre_loader' ).hide();
						jQuery( '#qre_quiz_details_' + attr_val ).slideDown( 'slow' );
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
				/*jQuery.post( qre_export_obj.ajax_url, data_0, function( msg ) {
					jQuery( '#qre_quiz_details_' + attr_val ).html( msg );
				} ).done( function() {
					jQuery( '#qre_loader' ).hide();
					jQuery( '#qre_quiz_details_' + attr_val ).slideDown( 'slow' );
				} );*/
			}
			instance.onScroll();
			instance.onArrowClick( attr_val );
		} );
	}
	/**
	 * This method is used to handle the scroll operation which basically shows the scroll-to-top button.
	 */
	onScroll() {
		jQuery( window ).scroll( function() {
			if ( jQuery( this ).scrollTop() >= 50 ) {        // If page is scrolled more than 50px
				if ( jQuery( '.qre_prev_div' ).is( ':visible' ) ) {
					jQuery( '#qre_arrow_sec' ).show();
					jQuery( '#qre_arrow-up' ).fadeIn( 200 );    // Fade in the arrow
				}
			} else {
				jQuery( '#qre_arrow-up' ).fadeOut( 200 );   // Else fade out the arrow
			}
		} );
	}
	/**
	 * This method scrolls the user to the top on arrow button click.
	 * @param  {string} attr_val Unique Identifier.
	 */
	onArrowClick( attr_val ) {
		jQuery( '#qre_arrow_sec' ).click( function() {      // When arrow is clicked
			jQuery( '#qre_quiz_details_' + attr_val ).slideUp( 'slow' );
			jQuery( 'body, html' ).animate( {
				scrollTop: 0                       // Scroll to top of body
			}, 500 );
		   jQuery( '#qre_arrow_sec' ).hide();
		} );
	}
	/**
	 * This method is used to handle the operations that occur on selecting a user in the dropdown.
	 * @param  {string} selector User Dropdown Selector.
	 */
	onUserChange( selector ) {
		jQuery( selector ).change( function() {
			jQuery.ajax( {
				type: 'POST',
				url: qre_export_obj.ajax_url,
				timeout: 10000,
				retry_count: 0,
				retry_limit: 1,
				data: {
					action: 'qre_user_data_display',
					'qre_user_id': jQuery( this ).val()
				},
				success: function( response ) {
					jQuery( '.qre_quiz_data_container' ).html( response );
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
		} );
	}
}
