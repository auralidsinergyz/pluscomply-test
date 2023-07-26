(function($, w) {
    'use strict';

    $('#ld_mc_license_action').on('click',function(e) {
        e.preventDefault();
        $(".spinner").addClass("is-active");

        var license_activate_or_deactivate_action = $(this).data('edd_action');
        console.log(license_activate_or_deactivate_action);
        var data = {
            action: license_settings.ajax.update.action,
            security: license_settings.ajax.update.nonce,
            license_edd_action: license_activate_or_deactivate_action,
            settings_option: 'license_key',
            license_key: $('#ld_mc_license_key').val(),
        };
        $.ajax({
            method: 'POST',
            url: license_settings.ajax.update.url,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', license_settings.ajax.update.nonce);
            },
            data: data
        }).then(function(r) {
            console.log("R type = " + typeof(r));
            console.log("R = " + JSON.stringify(r));
            $(".spinner").removeClass("is-active"); // remove the class after the data has been posted
            location.reload();
        }).fail(function(r) {
            $(".spinner").removeClass("is-active"); // remove the class after the data has been posted
            location.reload();
        });
    });
    /* End - Post Stripe Account INtegration */

})(jQuery, window);