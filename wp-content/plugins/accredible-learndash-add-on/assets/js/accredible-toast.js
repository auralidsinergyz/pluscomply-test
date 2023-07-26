const accredibleToast = {
    closeAll: function() {},
    timeout: null,
};

jQuery(function(){
    function _escapeHTML(html) {
        var escape = document.createElement('textarea');
        escape.textContent = html;
        return escape.innerHTML;
    }

    function _open(type, message, duration) {
        accredibleToast.closeAll();
        const toastHTML = `
        <div class="accredible-toast-message">
            <div><div class="alert-icon toast-${type}"></div></div>
            <p>${_escapeHTML(message)}</p>
        </div>`;

        const dialogRef = jQuery(toastHTML).dialog({
            draggable: false,
            minWidth: 400,
            minHeight: 48,
            autoOpen: false,
            classes: {
                'ui-dialog': `accredible-toast accredible-toast-${type}`
            },
            position: { my: 'bottom', at: 'center bottom', of: '.accredible-learndash-admin' },
            buttons: [
                {
                    class: 'accredible-toast-close',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }
            ],
            close: function(event, ui) {
                _clearToastTimeout();
                jQuery(this).dialog('destroy');
            }
        });

        if (duration && !isNaN(Number(duration))) {
            dialogRef.dialog('option', 'open', function(event, ui) {
                const toastRef = jQuery(this); 
                accredibleToast.timeout = setTimeout(function(){
                    toastRef.dialog('destroy');
                }, duration);
            });
        }

        dialogRef.dialog('open');
    };

    function _clearToastTimeout() {
        if (accredibleToast.timeout) {
            clearTimeout(accredibleToast.timeout);
            accredibleToast.timeout = null;
        }
    }

    accredibleToast.info = function(message, duration) {
        _open('info', message, duration);
    }

    accredibleToast.success = function(message, duration) {
        _open('success', message, duration);
    }

    accredibleToast.error = function(message, duration) {
        _open('error', message, duration);
    }

    accredibleToast.closeAll = function() {
        _clearToastTimeout();
        jQuery('.accredible-toast-message').dialog('destroy');
    }
});
