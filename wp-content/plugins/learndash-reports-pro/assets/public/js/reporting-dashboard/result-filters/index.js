/* globals qre_export_obj: false, URLSearchParams: false */
import datepicker from 'js-datepicker';

/**
 * This class does all the processing related to filtering results.
 */
export default class resultFilters {
	constructor() {
		this.search_string = '';
		this.selected_result = {
			id: 0,
			type: '',
			text: ''
		};
	}
	/**
	 * This method is used to toggle between period and date range filters
	 * @param  {string} container Container for the input field.
	 * @param  {string} selector  Hidden checkbox for the Toggle button.
	 */
	toggle( container, selector ) {
		jQuery( container ).on('change', selector, function(){
			jQuery(this).parents('.date-filter').toggleClass('toggleon');
			jQuery(this).parents('.toggle').find('.option').toggleClass('active');
		});
	}
	/**
	 * This method is used to initialize both the date selectors and establish a daterange relation between them.
	 * @param  {string} selectors Selectors for both the date fields.
	 */
	datepicker_init( selectors ) {
		var args = {
			id: 1,
			formatter: (input, date, instance) => {
				instance = instance;
				const value = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
				input.value = value; // => 'F j, Y'
			}
		};
		var args_clone;
		for ( var i = 0; i <= selectors.length - 1; i++ ) {
			if ( jQuery( selectors[ i ] ).length === 0 ) {
				continue;
			}
			args_clone = Object.assign( {}, args );
 			if ( jQuery( selectors[ i ] ).val().length !== 0 ) {
				args_clone.dateSelected = new Date( jQuery( selectors[ i ] ).val() );
			}
			datepicker( selectors[ i ], args_clone );
		}
	}
	/**
	 * This method is used to perform the live search operation for the keyword based on quiz title or user.
	 * @param  {string} selector Selector for search field.
	 */
	search( selector ) {
		var instance = this;
		/* On typing */
		jQuery( selector ).on( 'keyup', this.delay( function ( event ) {
			event.stopPropagation();
			var query = jQuery(this).val();
			var search_field = jQuery(this);
			if ( query === instance.search_string ) {
				return;
			}
			instance.search_string = query;
			if( search_field.next().hasClass('search_results') || search_field.next().hasClass( 'reset_search' ) ){
				search_field.next().remove();
			}
			if ( instance.search_string.length < 3 ) {
				return;
			}
			jQuery(selector).addClass('searching');
			jQuery.ajax({
				type: 'POST',
				url: qre_export_obj.ajax_url,
				timeout: 10000,
				retry_count: 0,
				retry_limit: 1,
				data: {
					action: 'qre_live_search',
					security: qre_export_obj.search_results_nonce,
					query: instance.search_string,
				},
				success: function(result) {
					jQuery(selector).removeClass('searching');
					if (!result.success) {
						search_field.after('<span class="search_results">' + result.data.message + '</span>');
						return;
					}
					search_field.parent().append('<ul class="search_results"></ul>');
					for (var i = 0; i < result.data.length; i++) {
						search_field.parent().find('ul').append('<li class="' + result.data[i].icon + '" data-id="' + result.data[i].ID + '" data-type="' + result.data[i].type + '">' + result.data[i].title + '</li>');
					}
				},
				error: function(xhr, status, error_thrown) {
					if ( status === 'timeout' ) {
					    this.retry_count++;
					    if ( this.retry_count <= this.retry_limit ) {
					        console.log( 'Retrying' );
					        jQuery.ajax( this );
					        return;
						} else {
							console.error( 'request timed out' );
							jQuery(selector).removeClass('searching');
							search_field.after('<span class="search_results">' + qre_export_obj.timeout_message + '</span>');
						}
					} else {
						console.log(error_thrown);
						jQuery(selector).removeClass('searching');
						search_field.after('<span class="search_results">' + error_thrown + '</span>');
					}
				} 
			});
		}, 500 ) );
	}
	/**
	 * This method is used to delay a callback by sometime to prevent flooding of events.
	 * (like keyup or mouse scroll which are frequent events.)
	 * @param  {Function} fn Callback to delay.
	 * @param  {number}   ms Time in milliseconds.
	 * @return {Function}    Same function delayed by required time.
	 */
	delay( fn, ms ) {
		let timer = 0;
		return function( ...args ) {
			clearTimeout( timer );
			timer = setTimeout( fn.bind( this, ...args ), ms || 0 );
		};
	}

	/**
	 * This method is used to select a result for further processing.
	 *
	 * @param {string} search_input  Selector for search field.
	 * @param {string} type_selector Selector for hidden input which stores query type.
	 * @param {string} id_selector   Selector for hidden input which stores query object id.
	 */
	select_result( search_input, type_selector, id_selector ) {
		var instance = this;
		jQuery( document ).on( 'click', '.search-reports ul.search_results li', function() {
			var query                     = jQuery( this );
			if ( instance.selected_result.id === query.attr( 'data-id' ) ) {
				return;
			}
			instance.selected_result.id   = query.attr( 'data-id' );
			instance.selected_result.type = query.attr( 'data-type' );
			instance.selected_result.text = instance.search_string = query.text();
			jQuery( search_input ).val( instance.selected_result.text );
			jQuery( type_selector ).val( instance.selected_result.type );
			jQuery( id_selector ).val( instance.selected_result.id );
			if ( jQuery( search_input ).next().hasClass( 'search_results' ) ){
				jQuery( search_input ).next().remove();
			}
			jQuery( search_input ).after( '<span class="reset_search"></span>' );
		});
	}

	/**
	 * This method is used to select a result for further processing.
	 *
	 * @param {string} search_input Selector for search field.
	 * @param {string} type_selector Selector for hidden input which stores query type.
	 * @param {string} id_selector   Selector for hidden input which stores query object id.
	 */
	reset_search( search_input, type_selector, id_selector ) {
		var instance = this;
		jQuery( 'body' ).on( 'click', '.reset_search', function(){
			jQuery( search_input ).val( '' );
			jQuery( type_selector ).val( '' );
			jQuery( id_selector ).val( 0 );
			instance.search_string = '';
		    instance.selected_result = {
			    id: 0,
			    type: '',
			    text: ''
		    };
		    jQuery( this ).remove();
		} );
	}

	/**
	 * This method is used to change number of entries shown per page.
	 * @param  string pagination_form Pagination Form Selector.
	 * @param  string limit_selector  Limit Dropdown Selector.
	 */
	change_entry_count( pagination_form, limit_selector ) {
		var self = this;
		jQuery( limit_selector ).on( 'change', function(){
			if ( ! self.check_query_params( 'qre_dashboard_filter_nonce' ) && ! self.check_query_params( 'screen' ) ) {
				jQuery( pagination_form ).submit();
				return;
			}
			self.add_filters_to_pagination( pagination_form );
			jQuery( pagination_form ).submit();
		} );
	}

	/**
	 * This method is used to control pagination inputs.
	 * @param  string pagination_form      Pagination form Selector
	 * @param  string page_number_selector Page Number Input Selector
	 * @param  string previous_page_btn    Previous page button Selector
	 * @param  string next_page_button     Next page button Selector
	 */
	change_page_number( pagination_form, page_number_selector, previous_page_btn, next_page_button ) {
		var self = this;
		jQuery( page_number_selector ).on( 'change', function(){
			var page_number     = parseInt( jQuery( this ).val() );
			var max_page_number = parseInt( jQuery( this ).attr( 'data-max' ) ); 
			if ( isNaN( page_number ) ) {
				return false;
			}
			if ( page_number > max_page_number ) {
				return false;
			}
			jQuery( pagination_form ).find( 'input[name=pageno]' ).val( page_number );
			if ( ! self.check_query_params( 'qre_dashboard_filter_nonce' ) && ! self.check_query_params( 'screen' ) ) {
				jQuery( pagination_form ).submit();
				return;
			}
			self.add_filters_to_pagination( pagination_form );
			jQuery( pagination_form ).submit();
		});
		jQuery( next_page_button ).on( 'click', function() {
			var page_number     = parseInt( jQuery( page_number_selector ).val() );
			var max_page_number = parseInt( jQuery( page_number_selector ).attr( 'data-max' ) );
			if ( page_number === max_page_number ) {
				jQuery( this ).attr( 'disabled', 'disabled' );
				return false;
			}
			jQuery( pagination_form ).find( 'input[name=pageno]' ).val( page_number + 1 );
			if ( ! self.check_query_params( 'qre_dashboard_filter_nonce' ) && ! self.check_query_params( 'screen' ) ) {
				jQuery( pagination_form ).submit();
				return;
			}
			self.add_filters_to_pagination( pagination_form );
			jQuery( pagination_form ).submit();
		} );
		jQuery( previous_page_btn ).on( 'click', function() {
			var page_number     = parseInt( jQuery( page_number_selector ).val() );
			if ( page_number === 1 ) {
				jQuery( this ).attr( 'disabled', 'disabled' );
				return false;
			}
			jQuery( pagination_form ).find( 'input[name=pageno]' ).val( page_number - 1 );
			if ( ! self.check_query_params( 'qre_dashboard_filter_nonce' ) && ! self.check_query_params( 'screen' ) ) {
				jQuery( pagination_form ).submit();
				return;
			}
			self.add_filters_to_pagination( pagination_form );
			jQuery( pagination_form ).submit();
		} );
	}

	/**
	 * This method is used to check if a query param exists.
	 * @param  string field Query Param
	 */
	check_query_params( field ) {
		var url = window.location.href;
		if( url.indexOf( '?' + field + '=' ) !== -1 ) {
		    return true;
		} else if( url.indexOf( '&' + field + '=' ) !== -1 ) {
		    return true;
		}
		return false;
	}

	/**
	 * Add user selected filters to pagination.
	 * @param string pagination_form Form Selectors.
	 */
	add_filters_to_pagination( pagination_form ) {
		const url_params = new URLSearchParams( window.location.search );
		const filters    = url_params.keys();
		for( var param of filters ) {
			if ( -1 !== jQuery.inArray( param, [ 'limit', 'pageno' ] ) ) {
				continue;
			}
			let input = jQuery( '<input>' ).attr( 'type', 'hidden' ).attr( 'name', param ).val( jQuery( '[name=' + param + ']' ).val() );
			jQuery( pagination_form ).append( input );
		}
	}
}