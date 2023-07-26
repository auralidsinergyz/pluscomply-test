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
        // Set some variables
        var time = '10000';
        var container = jQuery('.response-box');
        // Generate the HTML
        var html = '<div class="alert alert-' + type + '">' + text + '</div>';
        // Append the label to the container
        container.append(html);
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

    function isUrlValid(url) {
        return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    }

    //Add new redirection rule from header select box
    $('.ebsso-add-new-redirect-rule').click(function () {
        var parent = $(this).parent();
        var url = parent.find(".ebsso-role-redi-new-setting-url").val();
        var role_name = parent.find(".ebsso-role-redi-new-setting-role").val();
        var role_text = parent.find(".ebsso-role-redi-new-setting-role option:selected").text();
        //if Selected Item is Default Value
        if (role_text == 'Select Role') {
            parent.find(".ebsso-error").text(ebssoAdSet.select_role);
            return;
        }
        //check if empty
        if (url.length == 0) {
            parent.find(".ebsso-error").text(ebssoAdSet.empty_url);
            return;
        }else if (false == isUrlValid(url)) {
            parent.find(".ebsso-error").text(ebssoAdSet.invalid_url);
            return;
        }else{
            parent.find(".ebsso-error").text("");
        }
        //Add row to table
        var filedId = "ebsso_login_redirect_url_" + role_name;
        var row = "<tr id='ebsso_login_redirect_row_" + role_name + "'>" +
                "<td class='ebsso-setting-filed-lbl'>" + role_text + "</td>" +
                "<td><input type='url' id='" + filedId + "' name='" + filedId + "' value='" + url + "'></td>" +
                "<td><input type='button' class='ebsso-edit-manage-redirect-rule' name='" + filedId + "-btn' id='" + filedId + "-btn' data-name='" + role_name + "' data-text='" + role_text + "'' value='Delete'></td>" +
                "</tr>";
        $("#ebsso-tbl-role-redirect-rule").append(row);
        parent.find(".ebsso-role-redi-new-setting-url").val("");
        //remove row from table
        jQuery("option[value='" + role_name + "']").remove();

    });

    $(document).on("click", ".ebsso-edit-manage-redirect-rule", function () {
        var select_text = $(this).data("name");
        var value = $(this).data("text");
        

        $('#ebsso-role-top').append($('<option>', {
            value: value,
            text: select_text
        }));

        //Add to footer select Box
        $('#ebsso-role-bottom').append($('<option>', {
            value: value,
            text: select_text
        }));

        //Remove the row from the table
        $(this).closest('tr').remove();
    });

    // Remove the notification on click
    $('.alert').live('click', function () {
        ohSnapX(jQuery(this));
    });

    //General Settings Google Plus Checkbox events
    // if ($('#eb_sso_gp_enable').is(':checked') == false) {
    if ($("#eb_sso_gp_enable :selected").val() == "no") {
        var parentTable = $('#eb_sso_gp_enable').closest("table");

        // $("#eb_sso_gp_enable").closest("tr").nextAll().;
        parentTable.next("table").css("display", "none");
        // $(".sso-gp-settings").css("display", "none");
    }

    $('#eb_sso_gp_enable').change(function () {
        var parentTable = $('#eb_sso_gp_enable').closest("table");

        // this will contain a reference to the checkbox
        if ($(this).val() != "no") {
            // the checkbox is now checked
            parentTable.next("table").css("display", "block");
            // $(".sso-gp-settings").css("display", "block");
        } else {
            // the checkbox is now no longer checked
            parentTable.next("table").css("display", "none");
            // $(".sso-gp-settings").css("display", "none");
        }
    });

    //General Settings Facebook Login Checkbox events
    // if ($('#eb_sso_fb_enable').is(':checked') == false) {
    if ($("#eb_sso_fb_enable :selected").val() == "no") {
        var parentTable = $('#eb_sso_fb_enable').closest("table");

        // $("#eb_sso_fb_enable").closest("tr").nextAll().addClass("ebsso-hide");
        parentTable.next("table").css("display", "none");
        // $(".sso-fb-settings").css("display", "none");
    }

    $('#eb_sso_fb_enable').change(function () {
        // this will contain a reference to the checkbox
        var parentTable = $('#eb_sso_fb_enable').closest("table");
        if ($(this).val() != "no") {

            // the checkbox is now checked
            // $("#eb_sso_fb_enable").closest("tr").nextAll().removeClass("ebsso-hide");
            parentTable.next("table").css("display", "block");
            // $(".sso-fb-settings").css("display", "block");
        } else {

            // the checkbox is now no longer checked
            // $("#eb_sso_fb_enable").closest("tr").nextAll().addClass("ebsso-hide");
            parentTable.next("table").css("display", "none");
            // $(".sso-fb-settings").css("display", "none");
        }
    });


    //Redirection Settings Role based Redirection Checkbox events
    if ($('#ebsso_role_base_redirect').is(':checked') == false) {
        $("#ebsso-role-redirect-setting-block").addClass("ebsso-hide");
    }

    $("#ebsso_role_base_redirect").change(function () {
        // this will contain a reference to the checkbox   
        if (this.checked) {
            $("#ebsso-role-redirect-setting-block").removeClass("ebsso-hide");
        } else {
            $("#ebsso-role-redirect-setting-block").addClass("ebsso-hide");
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
                if (response.success == 1) {
                    ohSnap(response.data, 'success', 1);
                } else {
                    ohSnap(response.data, 'error', 0);
                }
            }
        });
    });

})(jQuery);
