jQuery.noConflict();

jQuery(document).ready(function ($) {

    // ! Step 1 : CSV Upload
    $('#file-upload').submit(function (e) {
        e.preventDefault();

        $('#uo_import_user_message').hide();

        try {
            var reader = new FileReader();
            //do something
        }
        catch (e) {
            $('#uo_import_user_message').show().text("Error: seems File API is not supported. Update your browser.");
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        //get file object
        var file = $('#csv-file')[0].files[0];
        var csvText = false;

        if (file) {
            // check is csv extension
            if ('csv' !== file.name.split('.').pop()) {
                $('#uo_import_user_message').show().text("The file must be a CSV");
                $("html, body").animate({scrollTop: 0}, "slow");
                return false;
            }
            reader.readAsText(file);
            reader.onload = function (e) {

                csvText = e.target.result;

                if (!csvText) {
                    $('#uo_import_user_message').show().text("CSV File Error");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    return false;
                }

                // split file text on line breaks
                var csvArray = csvText.split(/\r?\n/g);

                if (1001 <= csvArray.length) {
                    $('#uo_import_user_message').show().text("CSV file has too many rows. Please decrease rows to 1000.");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    return false;
                }

                // Preliminary checks

                // Pull out the header and store it
                var csvHead = csvArray.shift();
                csvHead = csvHead.split(",");

                // Remove last line of csv that is always blank
                csvArray.pop();

                // Require emails
                var emailIndex = verifyRequiredHeaders(csvHead, 'user_email');
                if ('error' === emailIndex) {
                    return false;
                }

                // Require user login
                var loginIndex = verifyRequiredHeaders(csvHead, 'user_login');
                if ('error' === loginIndex) {
                    return false;
                }

                var newArray = csvArray.map(function mapper(v) {
                    if (typeof v == "string") {
                        return v.split(",");
                    } else {
                        return v.map(mapper);
                    }
                });


                // Check for blank lines
                var emptyRows = verifyEmptyRows(newArray, emailIndex, loginIndex);
                console.log(emptyRows);
                if( emptyRows){
                    $('#uo_import_user_message').show().text('Each row in the CSV must have values including a username and email.');
                    $("html, body").animate({scrollTop: 0}, "slow");
                    return false;
                }

                var badEmailCells = verifyColumnValuesUnique(newArray, emailIndex );
                console.log(badEmailCells);
                if( badEmailCells){
                    $('#uo_import_user_message').show().text('CSV requires all user_email cells to be unique');
                    $("html, body").animate({scrollTop: 0}, "slow");
                    return false;
                }

                var badUserCells = verifyColumnValuesUnique(newArray, loginIndex );
                console.log(badUserCells);
                if( badUserCells){
                    $('#uo_import_user_message').show().text('CSV requires all user_login cells to be unique');
                    $("html, body").animate({scrollTop: 0}, "slow");
                    return false;
                }

                verifyCsv(csvText);

            };
        } else {
            $('#uo_import_user_message').show().text("Please choose a file to upload.");
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }


    });

    function verifyEmptyRows(array, emailIndex ,loginIndex ) {

        for (var i = 0; i < array.length; ++i) {
            if(array[i][emailIndex] === '' || array[i][loginIndex] === '' ){
                return true;
            }
        }

        return false;

    }

    function verifyRequiredHeaders(headerArray, type) {

        var columnsAmount = 0;
        var columnIndex = false;
        for (var i = 0; i < headerArray.length; i++) {
            if (headerArray[i] === type) {
                columnsAmount++;
                columnIndex = i;
            }
        }

        if (0 === columnsAmount) {
            $('#uo_import_user_message').show().text('CSV requires ' + type + ' header.');
            $("html, body").animate({scrollTop: 0}, "slow");
            return 'error';

        }

        if (1 !== columnsAmount) {
            $('#uo_import_user_message').show().text('CSV requires only 1 ' + type + ' header.');
            $("html, body").animate({scrollTop: 0}, "slow");
            return 'error';
        }

        return columnIndex;

    }

    function verifyColumnValuesUnique( array, index ){

        var column = [];

        for (var i = 0; i < array.length; ++i) {
            column.push(array[i][index]);
        }

        return hasDuplicates(column);


    }

    function hasDuplicates(array) {
        var valuesSoFar = Object.create(null);
        for (var i = 0; i < array.length; ++i) {
            var value = array[i];
            if (value in valuesSoFar) {
                return true;
            }
            valuesSoFar[value] = true;
        }
        return false;
    }

    function verifyCsv(csvText) {
        // Ajax Request
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                'action': 'Uncanny Toolkit Pro - Import Users : File Upload',
                'csv': csvText
            },

            success: function (response) {
                console.log(response);
                //var json = JSON.parse(response);

                if ('error' in response.data) {
                    setAdminMsg(objString.err_required_fields, 'error');
                    return;
                }

                console.log(response.data.validated_data);

                var data = response.data.validated_data;

                $('#total-rows').text(data.total_rows);

                $('#new-emails').text(Object.keys(data.emails.new_emails).length);

                $('#existing-emails').text(Object.keys(data.emails.existing_emails).length);

                $('#malformed-emails').text(Object.keys(data.emails.malformed_emails).length);

                $('#invalid-courses').text(Object.keys(data.courses.invalid_learndash_courses).length);

                $('#invalid-groups').text(Object.keys(data.groups.invalid_learndash_groups).length);

                var existingEmails = '';
                if( 'update' !== data.emails.import_existing_user_data ){
                    $.each(data.emails.existing_emails, function (row, dataObject) {
                        var csvRow = Number(row) + 1;
                        existingEmails += '<tr><td>' + csvRow + '</td><td>' + dataObject.user_email + '</td><td><a href="' + dataObject.edit_link + '">View Profile</a></td></tr>';
                    });
                }


                if ('' === existingEmails) {
                    existingEmails = '<tr><td  colspan="3" align="center">Everything looks good!</td></tr>'
                }

                $('#existing-user-email-table tbody').html(existingEmails);

                var invalidCourseRows = '';
                $.each(data.courses.invalid_learndash_courses, function (row, dataObject) {
                    var invalidCourses = '';

                    // Data did not validate to an interger (either cell was empty or had invalid characters)
                    if (0 === dataObject.invalid_ids[0]) {
                        if ('' === dataObject.inputted_ids) {
                            invalidCourses = 'Empty Cell';
                        } else {
                            invalidCourses = dataObject.inputted_ids;
                        }

                    } else {
                        console.log(dataObject);
                        console.log(dataObject.invalid_ids);
                        $.each(dataObject.invalid_ids, function (row, invalidId) {
                            invalidCourses += invalidId + ' ';
                        });
                    }
                    var csvRow = Number(row) + 1;
                    invalidCourseRows += '<tr><td>' + csvRow + '</td><td>' + invalidCourses + '</td></tr>';
                });

                if ('' === invalidCourseRows) {
                    invalidCourseRows = '<tr><td  colspan="2" align="center">Everything looks good!</td></tr>'
                }

                $('#invalid-courses-table tbody').html(invalidCourseRows);

                var invalidGroupRows = '';
                $.each(data.groups.invalid_learndash_groups, function (row, dataObject) {
                    var invalidGroups = '';

                    // Data did not validate to an interger (either cell was empty or had invalid characters)
                    if (0 === dataObject.invalid_ids[0]) {
                        if ('' === dataObject.inputted_ids) {
                            invalidGroups = 'Empty Cell';
                        } else {
                            invalidGroups = dataObject.inputted_ids;
                        }

                    } else {
                        console.log(dataObject);
                        console.log(dataObject.invalid_ids);
                        $.each(dataObject.invalid_ids, function (row, invalidId) {
                            invalidGroups += invalidId + ' ';
                        });
                    }

                    var csvRow = Number(row) + 1;
                    invalidGroupRows += '<tr><td>' + csvRow + '</td><td>' + invalidGroups + '</td></tr>';
                });

                if ('' === invalidGroupRows) {
                    invalidGroupRows = '<tr><td  colspan="2" align="center">Everything looks good!</td></tr>'
                }

                $('#invalid-groups-table tbody').html(invalidGroupRows);


                $('#import-users-validation').show();
                $('#import-users-upload').hide();
                $("html, body").animate({scrollTop: 0}, "slow");
            },
            error: errorHandler = function () {
                setAdminMsg(objString.err_upload_failed, 'error');
            },
        });


    }

    // ! Step 2 : validation check
    //#perform-import-users-text, #perform-import-users-review ,#perform-import-users-ready
    $('#abort-import-users').on('click', function (e) {
        // reload page
        location.reload();

    });

    $('#perform-import-users').on('click', function (e) {

        $('#perform-import-users').hide();
        $('#abort-import-users').hide();

        $('#perform-import-users-text').show();
        $('#perform-import-users-review').show();
        $('#perform-import-users-ready').show();

    });

    $('#perform-import-users-review').on('click', function (e) {

        $('#perform-import-users-text').hide();
        $('#perform-import-users-review').hide();
        $('#perform-import-users-ready').hide();

        $('#perform-import-users').show();
        $('#abort-import-users').show();


    });

    var performImportResults = [];

    $('#perform-import-users-ready').on('click', function (e) {

        $('#import-users-validation').hide();
        $('#import-users-progress').show();

        // Setup Results
        performImportResults = {};
        performImportResults.newUsers = 0;
        performImportResults.updatedUsers = 0;
        performImportResults.emailsSent = 0;
        performImportResults.rowsIgnored = 0;
        performImportResults.ignoredRowsData = [];
        $('#import-users-ignored-table tbody').empty();

        // Run import
        performImport();

    });

    function performImport() {

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                'action': 'Uncanny Toolkit Pro - Import Users : Perform Import'
            },

            success: function (response) {

                console.log(response);

                if ('error' in response.data) {
                    setAdminMsg(objString.err_required_fields, 'error');
                    return;
                }
                var progressOverlay = $('.import-progress-bar-overlay');

                performImportResults.newUsers += response.data.new_users;
                performImportResults.updatedUsers += response.data.updated_users;
                performImportResults.emailsSent += response.data.emails_sent;
                performImportResults.rowsIgnored += response.data.rows_ignored;
                performImportResults.ignoredRowsData.push(response.data.ignored_rows_data);

                console.log(performImportResults);

                $('#import-users-results-new-users').text(performImportResults.newUsers);
                $('#import-users-results-updated-users').text(performImportResults.updatedUsers);
                $('#import-users-results-emails-sent').text(performImportResults.emailsSent);
                $('#import-users-results-rows-ignored').text(performImportResults.rowsIgnored);

                $.each(response.data.ignored_rows_data, function (row, issue) {
                    var csvRow = parseInt(row) + 2;
                    $('#import-users-ignored-table tbody').append('<tr><td>' + csvRow + '</td><td>' + issue + '</td></tr>');
                });

                if ('completed' === response.data.status) {
                    // DONE Show results
                    console.log('DONE');
                    progressOverlay.css("width", "100%");
                    progressOverlay.css("background", "green");


                } else {
                    var percentComplete = Math.ceil(response.data.imported_rows / response.data.total_rows * 100);
                    percentComplete += '%';
                    progressOverlay.css("width", percentComplete);
                    performImport();
                }

            }
        });

    }


    $('#uo_import_save_options').submit(function (e) {
        e.preventDefault();


        var formData = $(this).serializeControls();
        formData.action = 'Uncanny Toolkit Pro - Import Users : Options Form';
        console.log(formData);

        // Ajax Request
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,

            success: function (response) {
                console.log(response);
                //console.log(response.data.message);
                $('#uo_import_user_message').show().text(response.data.message);
                $("html, body").animate({scrollTop: 0}, "slow");
            },
            error: errorHandler = function () {

            }
        });
    });

    // ! Test New User Email
    $('#btn-test_new_user_template').click(function (e) {
        e.preventDefault();

        console.log('new test');

        var data = {
            action: 'Uncanny Toolkit Pro - Import Users : Test Email',
            user_email_address: $('#uo_import_email_new_users_test_email').val(),
            email_subject: $('#uo_import_email_new_users_subject').val(),
            email_body: getWpEditorContent('uo_import_users_new_user_email_body')
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            success: function (response) {
                console.log(response);
                $('#uo_import_user_message').show().text('Please check that the email is correct and the subject is filled. ' + response.data.message);
                $("html, body").animate({scrollTop: 0}, "slow");
            }
        });

    });

    // ! Test Updated User Email
    $('#btn-test_updated_user_template').click(function (e) {
        e.preventDefault();
        console.log('updated test');

        var data = {
            action: 'Uncanny Toolkit Pro - Import Users : Test Email',
            user_email_address: $('#uo_import_email_updated_users_test_email').val(),
            email_subject: $('#uo_import_email_updated_users_subject').val(),
            email_body: getWpEditorContent('uo_import_users_updated_user_email_body')
        };

        console.log(data);
        $('#test_email_result').hide();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            success: function (response) {
                //console.log(response);
                $('#uo_import_user_message').show().text('Please check that the email is correct and the subject is filled. ' + response.data.message);
                $("html, body").animate({scrollTop: 0}, "slow");
            }
        });

    });

    // ! Save Email Control
    $('#btn-save_template').click(function (e) {
        e.preventDefault();
        $('#test_email_result').hide();

        var formData = {
            action: 'Uncanny Toolkit Pro - Import Users : Save Email',

            send_new_user_email: $('#uo_import_email_send_new_users').is(':checked'),
            new_user_email_subject: $('#uo_import_email_new_users_subject').val(),
            new_user_email_body: getWpEditorContent('uo_import_users_new_user_email_body'),

            send_updated_user_email: $('#uo_import_email_send_updated_users').is(':checked'),
            updated_user_email_subject: $('#uo_import_email_updated_users_subject').val(),
            updated_user_email_body: getWpEditorContent('uo_import_users_updated_user_email_body')
        };

        console.log(formData);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                $('#uo_import_user_message').show().text(response.data.message);
                $("html, body").animate({scrollTop: 0}, "slow");
            }
        });
    });

    function getWpEditorContent(editorId) {
        var content;
        var editor = tinyMCE.get(editorId);
        if (editor) {
            // Ok, the active tab is Visual
            content = editor.getContent();
        } else {
            // The active tab is HTML, so just query the textarea
            content = $('#' + editorId).val();
        }

        return content;
    }

    // ! Test Import
    $('#btn-test_import').click(function (e) {
        e.preventDefault();

        var formData = new FormData($('#options-form')[0]);
        formData.append('action', 'Uncanny Toolkit Pro - Import Users : Test Import');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: completeHandler = function (response) {
                if ('error' in response) {
                    setAdminMsg(objString.err_required_fields, 'error');
                    return;
                }

                $('#test-import-result').html(response.html);
            },
        });
    });

    // ! Control Admin Message
    function setAdminMsg(msg, cls) {
        if (!cls)
            cls = 'updated';

        if (!msg) {
            deleteAdminMsg();
        } else {
            $('.message-holder').attr('class', 'message-holder');
            $('.message-holder').addClass(cls);
            $('.message-holder p').html(msg);
            $('.message-holder').show();
        }

        window.scrollTo(0, 0);
    }

    function deleteAdminMsg() {
        $('.message-holder').attr('class', 'message-holder');
        $('.message-holder').hide();
    }

    $.fn.serializeControls = function () {
        var data = {};

        function buildInputObject(arr, val) {
            if (arr.length < 1)
                return val;
            var objkey = arr[0];
            if (objkey.slice(-1) == "]") {
                objkey = objkey.slice(0, -1);
            }
            var result = {};
            if (arr.length == 1) {
                result[objkey] = val;
            } else {
                arr.shift();
                var nestedVal = buildInputObject(arr, val);
                result[objkey] = nestedVal;
            }
            return result;
        }

        $.each(this.serializeArray(), function () {
            var val = this.value;
            var c = this.name.split("[");
            var a = buildInputObject(c, val);
            $.extend(true, data, a);
        });

        return data;
    }
});
