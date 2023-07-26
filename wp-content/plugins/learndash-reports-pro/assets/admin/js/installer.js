jQuery(function() {
    let button = jQuery('.wrld-install-free');
    jQuery( document ).on('wp-plugin-install-success', function (event, response) {
        event.preventDefault();
        jQuery('#wrld-update-status-update-sym').removeClass('spinner is-active');
        jQuery('#wrld-update-status-update-sym').addClass('dashicons dashicons-yes');
        location.reload();

    });

	jQuery( document ).on('wp-plugin-install-error', function (event, response) {
        event.preventDefault();
        jQuery('#wrld-update-status-update-sym').removeClass('spinner is-active');
        jQuery('#wrld-update-status-update-sym').addClass('dashicons dashicons-warning');
        let failure_p = jQuery('#wrld-installation-failure-message > p').html();
        jQuery('.wrld-plugin-dependency-installation > p').empty();
        jQuery('.wrld-plugin-dependency-installation > p').html(failure_p);
    });

	jQuery( document ).on('wp-plugin-installing', function (event, args) {
        event.preventDefault();
        jQuery('.wrld-install-free').attr('disabled', true);
        jQuery('.wrld-install-free').text('Installing Plugin...');
    });
    
    wp.updates.installPlugin( {
        slug:'wisdm-reports-for-learndash'
    });
    
});

