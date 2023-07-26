(function($) {
    "use strict";
    $.fn.loader = function loader(status) {
        //console.log("abc");
        if (status == 'show') {
            // console.log("case 01");
            var position = $(this).position();
            var height = $(this).height();
            var width = $(this).width();
            // console.log("left: " + position.left + ", top: " + position.top + "bottom: " + position.bottom);
            // if ($(this).attr('id') == 'wpcontent') {
            //     // console.log("case 01 - 01");
            //     var margin_left = $(this).css('margin-left'); // remove ps
            //     margin_left = parseInt(margin_left.slice(0, -2));
            //     // console.log("margin_left = " + margin_left);
            //     var padding_left = $(this).css('padding-left'); // remove px
            //     padding_left = parseInt(padding_left.slice(0, -2));
            //     // console.log("padding_left = " + padding_left);
            //     var final_margin_left = margin_left + padding_left
            //     // console.log("final_margin_left = " + final_margin_left);
            //     $(this).append('<div class="loader overlay" style="display: block; margin-left:' + final_margin_left + 'px; top:' + position.top + 'px; width:' + width + 'px; height:' + height + 'px; "><div class="loader-load"></div><div class="loader-overlay"></div></div>');
            // } else {
            //     // console.log("case 01 - 02");
            //     var margin_left = $(this).css('margin-left'); // remove ps
            //     margin_left = parseInt(margin_left.slice(0, -2));
            //     // console.log("margin_left = " + margin_left);
            //     // var padding_left = $('#wpcontent').css('padding-left'); // remove px
            //     var padding_left = $(this).css('padding-left');
            //     padding_left = parseInt(padding_left.slice(0, -2));
            //     // console.log("padding_left = " + padding_left);
            //     var final_margin_left = margin_left + padding_left
            //     $(this).append('<div class="loader overlay" style="display: block;  margin-left:' + final_margin_left + 'px; top:' + position.top + 'px; width:' + width + 'px; height:' + height + 'px; "><div class="loader-load"></div><div class="loader-overlay"></div></div>');
            // }
            $(this).append('<div class="loader overlay" style="display: block;  margin-left:' + position.left + 'px; top:' + position.top + 'px; width:' + width + 'px; height:' + height + 'px; "><div class="loader-load"></div><div class="loader-overlay"></div></div>');
        } else if (status == 'hide') {
            // console.log("case 02");
            $(this).find('.loader').remove();
        }
    };
}(jQuery));