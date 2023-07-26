jQuery(document).ready(
        function () {
//        alert(eb_sso_data.error_message);
            jQuery.blockUI({
                message: jQuery('<p>' + eb_sso_data.error_message + '</p>'),
                fadeIn: 700,
                fadeOut: 700,
                timeout: 2000,
                showOverlay: false,
                centerY: false,
                css: {
                    margin: "unset",
                    width: '350px',
                    bottom: '10px',
                    top: '-110',
                    left: '',
                    right: '0px',
                    border: 'non',
                    padding: '5px',
                    backgroundColor: '#000000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .7,
                    color: '#ffffff',
                    'font-size': "18px"
                }
            });
        }
);