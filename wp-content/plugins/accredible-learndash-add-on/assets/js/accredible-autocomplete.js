accredibleAutoComplete = {};

jQuery( function(){
    accredibleAutoComplete.init = function() {
        if ( jQuery('#accredible_learndash_group_autocomplete').length ) {
            jQuery('#accredible_learndash_group_autocomplete').autocomplete({
                classes: { "ui-autocomplete": "accredible-autocomplete" },
                delay: 500,
                minLength: 2,
                source: function(request, response){
                    var post_data = {
                        'action': 'accredible_learndash_ajax_search_groups',
                        'search_term': request.term
                    };
                    jQuery.post(ajaxdata.ajaxurl, post_data, function (res) {
                        var results = [ { label: 'No results found', value: 'no_results' } ];
                        if (res.success && res.data.length) {
                            results = res.data;
                        }
                        response(results);
                    }, "json");
                },
                select: function(event, ui) {
                    var group_name = "";
                    var group_id = ""

                    if (ui.item.value !== 'no_results') {
                        group_name = ui.item.label;
                        group_id = ui.item.value;
                        jQuery('#accredible-form-field-group-error-msg').addClass('accredible-form-field-hidden');
                    }

                    jQuery("#accredible_learndash_group_autocomplete").val(group_name);
                    jQuery("#accredible_learndash_group").val(group_id);

                    event.preventDefault();
                }
            });
        }
    }

    accredibleAutoComplete.init();
});
