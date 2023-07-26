const accredibleDialog = {};
jQuery( function(){
    const dialog = jQuery('<div></div>').dialog({
        appendTo: '#wpcontent',
        modal: true,
        draggable: false,
        autoOpen: false,
        minWidth: 400,
        classes: {
            'ui-dialog': 'accredible-dialog accredible-no-close'
        },
        buttons: [
            {
                text: "Cancel",
                class: 'accredible-button-flat-natural accredible-button-large',
                click: function() {
                    jQuery(this).dialog("close");
                }
            }
        ]
    });

    accredibleDialog.init = function() {
        jQuery('[data-accredible-dialog]').off('click');
        jQuery('[data-accredible-dialog]').on('click', function(event){
            accredibleToast.closeAll();
            const element = this;
            if (jQuery(element).data('accredibleDialog')) {
                event.preventDefault();
    
                // Set dialog options.
                const options = dialog.dialog('option');
                dialog.html('<p>Are you sure you want to delete this auto issuance?</p>');
                options.title = 'Delete auto issuance';
                options.buttons[1] = {
                    text: "Delete",
                    class: 'button accredible-button-primary accredible-button-large',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                };
                
                if (element.tagName === 'A') {
                    const actionParams = jQuery(element).data('accredibleActionParams');
                    const closeDialog = function() {
                        dialog.dialog('close');
                    }
                    options.buttons[1].click = function() {
                        const formData = {};
                        actionParams.split('&').reduce(function(acc, curr) {
                            const keyValue = curr.split('=');
                            acc[keyValue[0]] = keyValue[1];
                            return acc;
                        }, formData);
    
                        // call BE
                        accredibleAjax.doAutoIssuanceAction(
                            formData
                        ).always(function(res){
                            if ((typeof(res) === 'object')) {
                                const message = res.data && res.data.message ? res.data.message : res.data;
                                if (res.success) {
                                    closeDialog();
                                    accredibleToast.success(message, 5000);
                                    // Reload auto issuances.
                                    accredibleAjax.loadAutoIssuanceListInfo(formData.page_num).always(function(res){
                                        const issuerHTML = res.data;
                                        jQuery('.accredible-content').html(issuerHTML);
                                        // Re-initialise event handlers
                                        setupEditClickHandler();
                                        accredibleDialog.init();
                                    });
                                } else {
                                    accredibleToast.error(message, 5000);
                                }
                            }
                        });
                    }
                }
    
                dialog.dialog('option', options);
                dialog.dialog('open');
            }
        });
    }

    accredibleDialog.init();
});
