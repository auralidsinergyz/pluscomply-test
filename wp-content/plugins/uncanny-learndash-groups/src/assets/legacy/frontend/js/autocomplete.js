(function ($) {
	$(function () {
		var url = ULGM_USER_AUTOCOMPLETE_AJAX.url + '?action=uo_groups_search_user&group-id=' + current_group_id.id
		var a_url = redirect_url.url;

		// Get the search field
		const $searchField = document.getElementById( 'ulg-manage-progress-user-search-field' );

		// Get field wrapper
		const $searchFieldWrapper = document.querySelector( '.ulg-manage-progress-user-search-field-wrapper' );

		// Disable "Return"/"Enter" key on the field
		$searchField.addEventListener( 'keypress', function( event ) {
			// Check if the user is pressing Enter
			if ( event.key === 'Enter' ) {
				// Get the results 
				const $resultsWrapper = document.querySelector( '.ulg-manage-progress-search-results' );

				// Number of results
				const numberOfResults = $resultsWrapper.querySelectorAll( '.ui-menu-item' );

				// If it doesn't have exactly one result, prevent the default behavior
				if ( numberOfResults.length !== 1 ) {
					event.preventDefault();
					return;
				}

				// Select the first (and only) result
				const $firstResult = $resultsWrapper.querySelector( '.ui-menu-item:first-child .ui-menu-item-wrapper' );
				$firstResult.click();

				event.preventDefault();
				return;
			}
		} );

		$( $searchField ).autocomplete({
			source: url,
			delay: 500,
			minLength: 2,
			classes: {
				'ui-autocomplete': 'ulg-manage-progress-search-results'
			},
			focus: function (event, ui) {
				$('#uncanny-ajax-search').val(ui.item.label)
				return false
			},
			select: function (event, ui) {
				$( $searchField ).val(ui.item.label).attr('disabled', true);
					location.href = a_url + '&user-id=' + ui.item.user_id;

				return false
			},

			/**
			 * Triggered before a search is performed, after minLength and delay are met. If canceled, then no request will be started and no items suggested.
			 */
			search: function() {
				// Add loading animation
				$searchFieldWrapper.classList.add( 'ulg-manage-progress-user-search-field-wrapper--loading' );
			},
			
			/**
			 * Triggered after a search completes, before the menu is shown. Useful for local manipulation of suggestion data, where a custom source option callback is not required. This event is always triggered when a search completes, even if the menu will not be shown because there are no results or the Autocomplete is disabled.
			 */
			response: function() {
				// Remove loading animation
				$searchFieldWrapper.classList.remove( 'ulg-manage-progress-user-search-field-wrapper--loading' );
			}
		})
	})
})(jQuery);
