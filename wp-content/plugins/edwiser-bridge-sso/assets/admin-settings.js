// jQuery(document).ready(function(){
//     jQuery("#eb_sso_secret_key:parent").css({"padding-top": "0 !important;", "margin-top": "0 !important;"});
// });

(function ($) {
    'use strict';


    /**
     * == OhSnap!.js ==
     * A simple notification jQuery/Zepto library designed to be used in mobile apps
     *
     * author: Justin Domingue
     * date: september 5, 2013
     * version: 0.1.2
     * copyright - nice copyright over here
     */
    function ohSnap(text, type, status)
    {
        // text : message to show (HTML tag allowed)
        // Available colors : red, green, blue, orange, yellow --- add your own!

        // Set some variables
        var time = '10000';
        var container = jQuery('.response-box');

        // Generate the HTML
        var html = '<div class="alert alert-' + type + '">' + text + '</div>';

        // Append the label to the container
        container.append(html);

        // after 'time' seconds, the animation fades out
        // setTimeout(function () {
        //   ohSnapX(container.children('.alert'));
        // }, time);
    }

    function ohSnapX(element)
    {
        // Called without argument, the function removes all alerts
        // element must be a jQuery object

        if (typeof element !== "undefined") {
            element.fadeOut();
        } else {
            jQuery('.alert').fadeOut();
        }
    }

    // Remove the notification on click
    $('.alert').live('click', function () {
        ohSnapX(jQuery(this));
    });
    
    if ($('#ebsso_role_base_redirect').is(':checked') == false) {
        $("#ebsso_user_role_redirect_rules").addClass("ebsso-hide");
            $(".ebsso-role-redirect-settings").addClass("ebsso-hide");
    }
    $("#ebsso_role_base_redirect").change(function () {
        // this will contain a reference to the checkbox   
        if (this.checked) {
            $("#ebsso_user_role_redirect_rules").removeClass("ebsso-hide");
            $(".ebsso-role-redirect-settings").removeClass("ebsso-hide");
        } else {
            $("#ebsso_user_role_redirect_rules").addClass("ebsso-hide");
            $(".ebsso-role-redirect-settings").addClass("ebsso-hide");
        }
    });


    /**
     * creates ajax request to initiate test connection request
     * display a response to user on process completion
     */
    $('#eb_sso_verify_key').click(function () {
        //get selected options
        //
        $('.response-box').empty(); // empty the response
        var url = $('#eb_url').val();
        var token = $('#eb_access_token').val();

        var $this = $(this);

        //display loading animation
        $('.load-response').show();

        $.ajax({
            method: "post",
            url: ebssoAdSet.ajaxurl,
            dataType: "json",
            data: {
                'action': 'ebsso_verify_key',
                'url': url,
                'nonce': ebssoAdSet.nonce,
                'wp_key': jQuery('#eb_sso_secret_key').val()
            },
            success: function (response) {
                $('.load-response').hide();
                // console.log(response);
                if (response.success == 1) {
                    ohSnap(response.data, 'success', 1);
                } else {
                    ohSnap(response.data, 'error', 0);
                }
            }
        });
    });

})(jQuery);
