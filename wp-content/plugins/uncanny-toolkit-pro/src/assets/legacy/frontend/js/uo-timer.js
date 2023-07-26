jQuery.noConflict();
(function ($) {
    $(function () {

        if ('true' === uoTimer.enableDebugMode) {
            console.log(uoTimer);
        }

        var pageTimer = false;

        var entryContent = $('.entry-content');

        var previousTimer = false;

        $('body').append('<div id="btn-dialogBox"></div>');

        var startTimer = function () {

            if (pageTimer) {
                clearInterval(pageTimer);
            }

            pageTimer = setInterval(uoHeartBeat, uoTimer.uoHeartBeatInterval * 1000);

            entryContent.show();
        };

        var stopTimer = function () {
            clearInterval(pageTimer);
            entryContent.hide();
        };

        // ... Request
        var uoHeartBeat = function () {

            if ('true' === uoTimer.enableDebugMode) {
                console.log('sending...');
            }

            $.ajax({
                url: buildApiRoute(),
                beforeSend: function (xhr) {
                    if ('true' === uoTimer.enableDebugMode) {
                        console.log(xhr);
                    }
                    xhr.setRequestHeader('X-WP-Nonce', uoTimer.nonce);
                },
                success: apiTimerSuccess,
                error: apiErrorError
            })
        };

        // ... Build Route
        var buildApiRoute = function () {

            if ('true' === uoTimer.enablePerformanceTimer) {
                return uoTimer.performanceApiUrl + '?course_id=' + uoTimer.courseID + '&post_id=' + uoTimer.postID;
            }

            return uoTimer.apiUrl + uoTimer.courseID + '/' + uoTimer.postID + '/';
        };

        var apiTimerSuccess = function (response) {

            if ('true' === uoTimer.enableDebugMode) {
                console.log(response);
            }

            if (!response.success) {
                response = $.parseJSON(response);
            }

            if (!response.success) {
                if ('true' === uoTimer.enableDebugMode) {
                    console.log('no course ID error');
                }
                // /window.location.href = uoTimer.redirect;
            }

            if (typeof response.time !== 'undefined') {

                if (previousTimer && (Number(response.time) - Number(previousTimer)) != uoTimer.uoHeartBeatInterval) {
                    uoTimer.uoHeartBeatInterval = response.time - previousTimer;
                    startTimer();

                }

                previousTimer = response.time;

            }

        };

        var apiErrorError = function (statusCode, errorThrown) {
            if ('true' === uoTimer.enableDebugMode) {
                console.log(statusCode);
            }
            if (statusCode.status == 0) {
                //location.reload(true);
            }
        };

        var addTimerEvents = function () {

            $(document).on("idle.idleTimer", function (event, elem, obj) {
                // function you want to fire when the user goes idle
                stopTimer();
                check_if_active();
                if ('true' === uoTimer.enableDebugMode) {
                    console.log('timer has stop');
                }

            });

            $(document).on("active.idleTimer", function (event, elem, obj, triggerevent) {
                // function you want to fire when the user becomes active again
                startTimer();
                if ('true' === uoTimer.enableDebugMode) {
                    console.log('has started');
                }

            });

        };

        var check_if_active = function () {

            $(document).idleTimer("pause");
            var timeOutMessage = uoTimer.timedOutMessage;
            timeOutMessage = timeOutMessage.replace(new RegExp("\\\\", "g"), "");

            var cancelValue = uoTimer.inactiveButtonText;
            cancelValue = cancelValue.replace(new RegExp("\\\\", "g"), "");

            var confirmValue = uoTimer.activeButtonText;
            confirmValue = confirmValue.replace(new RegExp("\\\\", "g"), "");

            $('#btn-dialogBox').dialogBox({
                hasClose: true,
                hasBtn: true,
                zIndex: 999999999,
                confirmValue: confirmValue,
                confirm: function () {
                    $(document).idleTimer("resume");
                },
                cancelValue: cancelValue,
                cancel: function () {
                    window.location.href = uoTimer.redirect;
                },
                title: '',
                content: timeOutMessage
            });
        };

        // Initialize Timer
        startTimer();
        $(document).idleTimer(Number(uoTimer.idleTimeOut) * 1000);
        addTimerEvents();

    });

})(jQuery);