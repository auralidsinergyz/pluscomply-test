/**
 * Uncanny Coupon Login
 *
 * @author Uncanny Owl
 * @version 1.0.0
 */

jQuery(document).ready(function ($){
    $("#coupon-length").keypress(function (e) {
        if (e.which === 10 || e.which === 13) {
            e.preventDefault();
        }
    });

    $('.uo-btn-delete').on('click', function (e) {
        if (!confirm(uoCodesStrings.Doyoureallywanttodeletethesecodes)) e.preventDefault();
    });

    $('input[name="coupon-for"]').change( function (){
        var coupon_for = $('input[name="coupon-for"]:checked', '#uncanny-form-create-coupon').val();
        if ('course' === coupon_for) {
            $('#tr-course-group').hide();
            $('#tr-course-courses').fadeIn();

        } else if ('group' === coupon_for) {
            $('#tr-course-group').fadeIn();
            $('#tr-course-courses').hide();
        }
    });

    $("#uncanny-form-create-coupon").submit(function (e) {
        var coupon_for = $('input[name="coupon-for"]:checked', '#uncanny-form-create-coupon').val();
        $("#uncanny-form-create-coupon .description").html("");

        if (!$("#coupon-max-usage").val().length) {
            e.preventDefault();
            $("#tr-coupon-max-usage .description").html(uoCodesStrings.PleaseInputMaximumUsageAmount);
        }
        if ('group' === coupon_for) {
            if ($("select[name='coupon-group[]'] option:selected").length === 0) {
                e.preventDefault();
                $("#tr-course-group .description").html(uoCodesStrings.PleaseSelectLearnDashGroups);
            }
        } else if ('course' === coupon_for) {
            if ($("select[name='coupon-courses[]'] option:selected").length === 0) {
                e.preventDefault();
                $("#tr-course-courses .description").html(uoCodesStrings.PleaseSelectLearnDashCourses);
            }
        }

        if (!$("#coupon-length").val().length) {
            e.preventDefault();
            $("#tr-coupon-length .description").html(uoCodesStrings.PleaseInputLetterLength);
        } else {
            var length = Math.floor($("#coupon-length").val()),
                prefix_length = $("#coupon-prefix").val().length,
                suffix_length = $("#coupon-suffix").val().length;

            if (length < prefix_length + suffix_length) {
                e.preventDefault();
                $("#tr-coupon-presuffix .description").html(uoCodesStrings.TheLengthofPrefixandSuffixisLongerthanLetterLength);
            } else if (length == prefix_length + suffix_length) {
                e.preventDefault();
                $("#tr-coupon-presuffix .description").html(uoCodesStrings.TheLengthofPrefixandSuffixissameasLetterLength);
            }
        }
    });

    $("#coupon-length, #coupon-prefix, #coupon-suffix, #coupon-dash").keyup(function (e) {
        render_code();
    });

    function render_code() {
        var chars = "123456789ABCDEFGHJKLMNPQRSTUVWXYZ",
            dashes = $("#coupon-dash").val(),
            coupon_length = $("#coupon-length").val(),
            prefix = $("#coupon-prefix").val(),
            prefix_length = prefix.length,
            suffix = $("#coupon-suffix").val(),
            suffix_length = suffix.length,
            random_string = '',
            ld_group = $("#coupon-group").val();

        coupon_length = coupon_length - prefix_length - suffix_length;

        for (var i = 0; i < coupon_length; i++) {
            var rnum = Math.floor(Math.random() * chars.length);
            random_string += chars.substring(rnum, rnum + 1);
        }

        var strings = prefix + random_string + suffix;

        if (dashes) {
            dashes = dashes.split("-");
            var strings_arr = strings.split(''),
                new_string = "",
                dash_inside_pointer = 0,
                dash_index = 0;

            $(strings_arr).each(function (i) {
                new_string = new_string + $(this)[0];
                dash_inside_pointer++;

                if (dashes[dash_index] <= dash_inside_pointer) {
                    dash_index++;
                    dash_inside_pointer = 0;
                    new_string = new_string + "-";
                }
            });

            if (new_string.substring(new_string.length - 1, new_string.length) == "-") {
                new_string = new_string.substring(0, new_string.length - 1);
            }
        } else {
            var new_string = strings;
        }
        $("#coupon-presuffix #coupon-render #coupon-render__fakecode").html(new_string);
    }

    if ($("#uncanny-form-create-coupon").length) {
        render_code();
    }

    function change_assign_form() {
        if ($("#uncanny_form_input_vendor #assign_where").val() == "courses") {
            $("#uncanny_form_input_vendor #assign_courses").show();
            $("#uncanny_form_input_vendor #assign_lessons").hide();

        } else {
            $("#uncanny_form_input_vendor #assign_courses").hide();
            $("#uncanny_form_input_vendor #assign_lessons").show();
        }
    }

    if ($("#uncanny_form_input_vendor #assign_where").length) {
        change_assign_form();

        $("#uncanny_form_input_vendor #assign_where").change(function () {
            change_assign_form();
        });
    }

    $("#page_coupon_stat .btn_delete, #page_coupon_stat .btn_delete").click(function (e) {
        if (!confirm(uoCodesStrings.Doyoureallywanttodeletethis)) e.preventDefault();
    });

    // Prefix Selector for View Coupon by Group
    $('#prefix_selector_for_coupon_group').change(function () {
        if ($(this).val()) {
            location.href = add_query_arg('prefix', $(this).val());
        } else {
            location.href = remove_query_arg('prefix');
        }
    });

    $('#badge_selector_for_coupon_group').change(function () {
        if ($(this).val()) {
            location.href = add_query_arg('badge_id', $(this).val());
        } else {
            location.href = remove_query_arg('badge_id');
        }
    });
});

function add_query_arg(key, value, url) {
    if (!url) url = location.href;

    key = escape(key);
    value = escape(value);

    if (!value) return remove_query_arg(key, url);

    var pair = key + "=" + value;
    var exp = new RegExp("(&|\\?)" + key + "=[^\&]*");
    url = url.replace(exp, "$1" + pair);

    if (url.indexOf(key + '=') > -1) {
    } else {
        if (url.indexOf('?') > -1) {
            url += '&' + pair;
        } else {
            url += '?' + pair;
        }
    }

    return url;
}

function remove_query_arg(key, url) {
    if (!url) url = location.href;

    key = escape(key);

    var exp = new RegExp("(&|\\?)" + key + "=[^\&]*");
    url = url.replace(exp, '');

    return url;
}

