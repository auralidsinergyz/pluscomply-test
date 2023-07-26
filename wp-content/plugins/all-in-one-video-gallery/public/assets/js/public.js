(function( $ ) {
	'use strict';

	/**
	 * Initialize Autocomplete.
	 *
	 * @since 2.5.6
	 */
	 function autocomplete( uid, source, callback ) {
		var inp = document.getElementById( 'aiovg-autocomplete-input-' + uid );
		var currentFocus;		

		// Execute a function presses a key on the keyboard
		inp.addEventListener( 'keydown', function( e ) {
			var x = document.getElementById( 'aiovg-autocomplete-items-' + uid );

			if ( x ) {
				x = x.getElementsByTagName( 'div' );
			}

			if ( e.keyCode == 40 ) {
			  	// If the arrow DOWN key is pressed,
			  	// increase the currentFocus variable
			  	currentFocus++;
			  	// and and make the current item more visible
			  	addActive( x );
			} else if ( e.keyCode == 38 ) {
			  	// If the arrow UP key is pressed,
			  	// decrease the currentFocus variable
			  	currentFocus--;
			  	// and and make the current item more visible
				addActive( x );
			} else if ( e.keyCode == 13 ) {
			  	// If the ENTER key is pressed, prevent the form from being submitted,
			  	e.preventDefault();
			  	if ( currentFocus > -1 ) {
					// and simulate a click on the 'active' item
					if ( x ) {
						x[ currentFocus ].click();
					}
			  	}
			}
		});

		function addActive( x ) {
		  	// A function to classify an item as 'active'
		  	if ( ! x ) {
				return false;
		  	}

		  	// Start by removing the 'aiovg-autocomplete-active' class on all items
		  	removeActive( x );

		  	if ( currentFocus >= x.length ) {
				currentFocus = 0;
		  	}

		  	if ( currentFocus < 0 ) {
				currentFocus = ( x.length - 1 );
		  	}

		  	// Add class 'aiovg-autocomplete-active'
		  	x[ currentFocus ].classList.add( 'aiovg-autocomplete-active' );
		}

		function removeActive( x ) {
		  	// A function to remove the 'aiovg-autocomplete-active' class from all autocomplete items
		  	for ( var i = 0; i < x.length; i++ ) {
				x[ i ].classList.remove( 'aiovg-autocomplete-active' );
		  	}
		}		

		function showLists( elem ) {
			var a, 
				b, 
				i, 
				t = document.getElementById( 'aiovg-autocomplete-tags-' + uid ),
				val = elem.value;

			// Close any already open lists of autocompleted values
			closeAllLists();

			currentFocus = -1;

			// Create a DIV element that will contain the items (values)
			a = document.createElement( 'DIV' );
			a.setAttribute( 'id', 'aiovg-autocomplete-items-' + uid );
			a.setAttribute( 'class', 'aiovg-autocomplete-items' );

			// Append the DIV element as a child of the autocomplete container
			elem.parentNode.appendChild( a );

			// For each item in the array...
			for ( i = 0; i < source.length; i++ ) {
				var value = source[ i ].value;
				var label = source[ i ].label;

			  	// Check if the item matches the text field value
				var isValid = false;

				if ( ! val ) {
					isValid = true;
				} else if ( label.toUpperCase().indexOf( val.toUpperCase() ) !== -1 ) {
					isValid = true;
				}				

				if ( ! b && ! isValid && i == source.length - 1 ) {
					value = 0;
					label = aiovg_public.i18n.no_tags_found;

					isValid = true;
				}

			  	if ( isValid ) {
					// Create a DIV element for each matching element
					b = document.createElement( 'DIV' );
					b.setAttribute( 'data-value', value );
					b.setAttribute( 'data-label', label );
					b.innerHTML += label;

					var isSelected = t.getElementsByClassName( 'aiovg-tag-item-' + value );
					if ( isSelected.length > 0 ) {
						b.setAttribute( 'class', 'aiovg-autocomplete-selected' );
					}
					
					// Execute a function when someone clicks on the item value (DIV element)
					b.addEventListener( 'click', function( e ) {
						inp.value = '';

						// Insert the value for the autocomplete text field						
						callback( this.getAttribute( 'data-value' ), this.getAttribute( 'data-label' ) );

						// Close the list of autocompleted values,
						// or any other open lists of autocompleted values
						closeAllLists();
					});

					a.appendChild( b );
			  	}
			}
		}

		function closeAllLists( elem ) {
			// Close all autocomplete lists in the document,
			// except the one passed as an argument
			var x = document.getElementsByClassName( 'aiovg-autocomplete-items' );

			var id = 0;
			if ( elem && elem.id ) {
				id = elem.id.replace( 'aiovg-autocomplete-input-', '' );
			}

			for ( var i = 0; i < x.length; i++ ) {
				if ( x[ i ].getAttribute( 'id' ) != ( 'aiovg-autocomplete-items-' + id ) ) {
					x[ i ].parentNode.removeChild( x[ i ] );
				}
		  	}
		}

		// Execute a function when someone focus in the text field
		inp.addEventListener( 'focus', function( e ) { 
			if ( e.target.value == '' ) {
				showLists( e.target );
			}
		});

		// Execute a function when someone writes in the text field
		inp.addEventListener( 'input', function( e ) {
			showLists( e.target );
		});

		// Execute a function when someone clicks in the document
		if ( ! aiovg_public.hasOwnProperty( 'autocomplete' ) ) {
			aiovg_public.autocomplete = true;
			
			document.addEventListener( 'click', function( e ) {
				closeAllLists( e.target );
			});
		}		
	}

	/**
	 * Called when the page has loaded.
	 *
	 * @since 2.4.3
	 */
	$(function() {

		// Common: Tags multiple select
		$( '.aiovg-autocomplete' ).each(function() {
			var uid    = $( this ).data( 'uid' );
			var source = [];			

			$( 'option', '#aiovg-autocomplete-select-' + uid ).each(function() {
				source.push({
					value: $( this ).val(),
					label: $( this ).text()
				});
			});

			if ( source.length == 0 ) {
				source.push({
					value: 0,
					label: aiovg_public.i18n.no_tags_found
				});
			}

			var callback = function( value, label ) {
				value = parseInt( value );

				if ( value != 0 ) {				
					var $tags  = $( '#aiovg-autocomplete-tags-' + uid );	
					var length = $tags.find( '.aiovg-tag-item-' + value ).length;

					if ( length == 0 ) {
						var html = '<span class="aiovg-tag-item aiovg-tag-item-' + value + '">';
						html += '<span class="aiovg-tag-item-name">' + label + '</span>';
						html += '<span class="aiovg-tag-item-close">&times;</span>';
						html += '<input type="hidden" name="ta[]" value="' + value + '" />';
						html += '</span>';
						
						$tags.append( html );
					}
				}
			}

			autocomplete( uid, source, callback );
		});

		$( document ).on( 'click', '.aiovg-tag-item-close', function() {
			$( this ).parent().remove();
		});	

		// Categories Dropdown
		$( 'select', '.aiovg-categories-template-dropdown' ).on( 'change', function() {
			if ( parseInt( this.options[ this.selectedIndex ].value ) === 0 ) {
				window.location.href = $( this ).closest( '.aiovg-categories-template-dropdown' ).data( 'uri' );
			} else {
				window.location.href = this.options[ this.selectedIndex ].getAttribute( 'data-uri' );
			}
		});

		// Pagination
		$( document ).on( 'click', '.aiovg-pagination-ajax a.page-numbers', function( e ) {
			e.preventDefault();

			var $this = $( this );	
			var $pagination = $this.closest( '.aiovg-pagination-ajax' );			
			var current = parseInt( $pagination.data( 'current' ) );			
			
			var params = $pagination.data( 'params' );
			params.action = 'aiovg_load_more_' + params.source;
			params.security = aiovg_public.ajax_nonce;
			
			var paged = parseInt( $this.html() );
			params.paged = paged++;
			if ( $this.hasClass( 'prev' ) ) {
				params.paged = current - 1;
			}
			if ( $this.hasClass( 'next' ) ) {
				params.paged = current + 1;
			}

			var $gallery = $( '#aiovg-' + params.uid );	

			$pagination.addClass( 'aiovg-spinner' );

			$.post( aiovg_public.ajax_url, params, function( response ) {
				if ( response.success ) {
					$gallery.html( $( response.data.html ).html() ).trigger( 'AIOVG.onGalleryUpdated' );

					$( 'html, body' ).animate({
						scrollTop: $gallery.offset().top - aiovg_public.scroll_to_top_offset
					}, 500);
				} else {
					$pagination.removeClass( 'aiovg-spinner' );
				}
			});
		});

		// Load More
		$( document ).on( 'click', '.aiovg-more-ajax button', function( e ) {
			e.preventDefault();

			var $this = $( this );
			var $pagination = $this.closest( '.aiovg-more-ajax' );			
			var numpages = parseInt( $this.data( 'numpages' ) );			
			
			var params = $pagination.data( 'params' );
			params.action = 'aiovg_load_more_' + params.source;
			params.security = aiovg_public.ajax_nonce;	
			
			var paged = parseInt( $this.data( 'paged' ) );
			params.paged = ++paged;
			
			$pagination.addClass( 'aiovg-spinner' );

			$.post( aiovg_public.ajax_url, params, function( response ) {
				$pagination.removeClass( 'aiovg-spinner' );

				if ( paged < numpages ) {
					$this.data( 'paged', params.paged );	
				} else {
					$this.hide();
				}			
				
				if ( response.success ) {					
					$( '.aiovg-grid', '#aiovg-' + params.uid ).append( $( '.aiovg-grid', response.data.html ).html() );					
				}
			});
		});
		
	});

})( jQuery );
