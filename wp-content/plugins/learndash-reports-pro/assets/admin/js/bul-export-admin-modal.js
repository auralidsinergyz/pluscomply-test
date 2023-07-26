jQuery(document).ready(function(){    
    jQuery("#wpProQuiz_tabHistory").append(bulk_export_js_object.modal_body);
    jQuery("#wpProQuiz_tabHistory .inside ul").append( '<li><br/><h2 class="bulk_export_label">'+bulk_export_js_object.export_heading_text+'</h2><label>'+bulk_export_js_object.export_message+'</label><br/><br/><a id="blk_export_btn" href="#TB_inline?&width=720&height=630&inlineId=my_bulk_export_popup" class="button-secondary thickbox" id="wdm_export_btn">'+bulk_export_js_object.export_button_text+'</a></li>' );       

  var userId = 0;
  jQuery('.wrp_user_dropdown').on('click',function(){
    jQuery('#apply-filter-button-admin').attr("disabled",false);
  });

  jQuery('#blk_export_btn').on('click', function(event) {
    initializeCal();
  });
  
  jQuery('#apply-filter-button-admin').on('click', function(event) {

      //copying values in modal
      jQuery('#datepickerFrom').val(jQuery('#from_date').val());
      jQuery('#datepickerTo').val(jQuery('#to_date').val());

      let searchParams = new URLSearchParams(window.location.search)
      const quizId = searchParams.get('post_id');
   
      var fromDate = getTimeStamp(jQuery('#from_date'));
      var toDate = getTimeStamp(jQuery('#to_date'));
    
      userId = jQuery('#selec_uxample').val();
      //setting selected user in modal
      const selectUserName = jQuery('.select2-container--learndash .select2-selection--single:eq(1)').text().slice(1);
      var newOption = new Option(selectUserName, userId, true, true);
      jQuery('#wpProQuiz_historyUser').append(newOption).trigger('change');
      jQuery(this).attr("disabled",true);
      
      let fields = {};
      fields.course_filter = -1;
      fields.group_filter = -1;
      fields.quiz_filter = quizId;
      fields.user_id = userId;
      fields.start_date = fromDate;
      fields.end_date = toDate;
      jQuery('.export-attempt-results .bulk-export-download ,.export-attempt-results .bulk-export-progress').addClass('wrld-hidden');
      jQuery('.export-attempt-learner-answers .bulk-export-download ,.export-attempt-learner-answers .bulk-export-progress').addClass('wrld-hidden');
      jQuery('.export-attempt-results .bulk-export-download').html('');
      jQuery('.export-attempt-learner-answers .bulk-export-download').html('');
      
      jQuery('.export-attempt-results .export-attempt-csv').attr('disabled',false);
      jQuery('.export-attempt-results .export-attempt-xlsx').attr('disabled',false);
      jQuery('.export-attempt-learner-answers .export-learner-csv').attr('disabled',false);
      jQuery('.export-attempt-learner-answers .export-learner-xlsx').attr('disabled',false);
      jQuery('.export-attempt-results .bulk-export-progress progress').val(0);
      fetchDataCount(fields);
      jQuery('#filter').trigger('click');
  });

  jQuery(".export-attempt-results .export-attempt-csv").on('click',function(){
      jQuery(this).attr('disabled',true);
      jQuery('.export-attempt-results .bulk-export-download ,.export-attempt-results .bulk-export-progress').removeClass('wrld-hidden');
      
      getDownloadableLink("csv");
      showProgressAttemptResult("csv");
  });

  jQuery(".export-attempt-results .export-attempt-xlsx").on('click',function(){
    jQuery(this).attr('disabled',true);
    jQuery('.export-attempt-results .bulk-export-download ,.export-attempt-results .bulk-export-progress').removeClass('wrld-hidden');
    showProgressAttemptResult("xlsx");
    getDownloadableLink("xlsx");
  });

//get count function
  function fetchDataCount( data ) {
      jQuery.ajax(
        {
          type: 'POST',
          url: qre_export_obj.ajax_url,
          timeout: 100000,
          retry_count: 0,
          retry_limit: 1,
          data: {
            action: 'wrld_export_entries',
            security: bulk_export_js_object.custom_reports_nonce,
            fields: data,
          },
          success: function( response ) {
            // getCustomReports(1);
            jQuery('.bulk-export-heading div span').text(response.data.count.attempt_count);
            // jQuery('.export-attempt-learner-answers .report-export-buttons div span').text(result.data.count.quiz_count);
            // jQuery('.bulk-export-progress label').text('Downloading ' + result.data.count.quiz_count + ' quiz attempts');
          }
        }
      );
    }

 // get downloadable link 
  function getDownloadableLink(type){
      var fromDate = getTimeStamp(jQuery('#from_date'));
      var toDate = getTimeStamp(jQuery('#to_date'));
      let searchParams = new URLSearchParams(window.location.search)
      const quizId = searchParams.get('post_id');
      let fields = {};
      fields.course_filter = -1;
      fields.group_filter = -1;
      fields.quiz_filter = quizId;
      fields.type = type;
      fields.user_id = userId;
      fields.start_date = fromDate;
      fields.end_date = toDate;
      jQuery('.filter-section').css({'opacity': 0.5, 'pointer-events': 'none'});
      jQuery('.bulk-export-download').css({'opacity': 0.5, 'pointer-events': 'none'});
      // jQuery('.wrld-loader').show();
      jQuery.ajax(
        {
          type: 'POST',
          url: qre_export_obj.ajax_url,
          timeout: 100000,
          retry_count: 0,
          retry_limit: 1,
          data: {
            action: 'wrld_export_attempt_results',
            security: bulk_export_js_object.custom_reports_nonce,
            fields: fields,
          },
          success: function( response ) {
            jQuery('.filter-section').css({'opacity': 1, 'pointer-events': 'auto'});
            jQuery('.bulk-export-download').css({'opacity': 1, 'pointer-events': 'auto'});
            createDownloadButton(type , response , "export-attempt-results");
            // getCustomReports(1);
          },
          error: function( xhr, status, error_thrown ) {
            if ( status === 'timeout' ) {
                this.retry_count++;
                if ( this.retry_count <= this.retry_limit ) {
                    console.log( 'Retrying' );
                    jQuery.ajax( this );
                    return;
              } else {
                console.error( 'request timed out' );
              }
            } else {
              console.log( error_thrown );
            }
          } 
        }
        );
      
        // document.getElementById("wisdm-learndash-reports-quiz-report-view").scrollIntoView();
    };

// Create Download button
    function createDownloadButton(type , response , exportType){
          if(exportType == "export-attempt-results"){
              jQuery('.export-attempt-results .bulk-export-progress').addClass('wrld-hidden');
              jQuery('.export-attempt-results .bulk-export-progress progress').val(0);
              jQuery('.export-attempt-results .bulk-export-progress span').text('');
              jQuery('.export-attempt-results .bulk-export-download').append('<a class="bulk-export-download-button" href="' + response.data.link + '" download><span class="dashicons dashicons-download"></span>Download ' + type.toUpperCase() + '</a>');
              jQuery('.export-attempt-results .bulk-export-download').removeClass('wrld-hidden');
          }else{
            jQuery('.export-attempt-learner-answers .bulk-export-progress').addClass('wrld-hidden');
            jQuery('.export-attempt-learner-answers .bulk-export-progress progress').val(0);
            jQuery('.export-attempt-learner-answers .bulk-export-progress span').text('');
            jQuery('.export-attempt-learner-answers .bulk-export-download').append('<a class="bulk-export-download-button" href="' + response.data.link + '" download><span class="dashicons dashicons-download"></span>Download ' + type.toUpperCase() + '</a>');
            jQuery('.export-attempt-learner-answers .bulk-export-download').removeClass('wrld-hidden');
          }
    }


/*****Progress indicator */
    var interval;
    function showProgressAttemptResult(type,exportFor){
      if(exportFor == "answers"){
        jQuery('.export-attempt-learner-answers .bulk-export-progress label').text(type.toUpperCase() + ' export in progress');
        jQuery('.export-attempt-learner-answers .bulk-export-progress').removeClass('wrld-hidden');
      }else{
      jQuery('.export-attempt-results .bulk-export-progress label').text(type.toUpperCase() + ' export in progress');
      jQuery('.export-attempt-results .bulk-export-progress').removeClass('wrld-hidden');
      }
      // jQuery('.export-attempt-results .bulk-export-download').addClass('wrld-hidden');

      var max = 100;
      var current = 0;
      var entries = jQuery('.export-attempt-results .report-export-buttons div span').text();
      interval = setInterval( function() {
        jQuery.ajax(
          {
            type: 'POST',
            url: qre_export_obj.ajax_url,
            timeout: 100000,
            retry_count: 0,
            retry_limit: 1,
            data: {
              action: 'wrld_export_progress_results',
              security: bulk_export_js_object.custom_reports_nonce,
            },
            success: function( response ) {
              current = response.data.percentage;
              if ( current <= max ) {
                  if(exportFor == "answers"){
                    jQuery('.export-attempt-learner-answers .bulk-export-progress progress').val(current);
                    jQuery('.export-attempt-learner-answers .bulk-export-progress span').text(current + '% Complete');
                  }else{
                    jQuery('.export-attempt-results .bulk-export-progress progress').val(current);
                  jQuery('.export-attempt-results .bulk-export-progress span').text(current + '% Complete');
                  }
                  
              }
              if ( current >= max ) {
                clearInterval(interval);
                return;
              }
            },
            error: function( xhr, status, error_thrown ) {
              if ( status === 'timeout' ) {
                  this.retry_count++;
                  if ( this.retry_count <= this.retry_limit ) {
                      console.log( 'Retrying' );
                      jQuery.ajax( this );
                      return;
                } else {
                  console.error( 'request timed out' );
                }
              } else {
                console.log( error_thrown );
              }
            } 
          }
          );
      }, 1000 );
  };


//Learner quiz attempts answer

    jQuery(".export-attempt-learner-answers .export-learner-csv").on('click',function(){
      jQuery(this).attr('disabled',true);
      jQuery('.export-attempt-learner-answers .bulk-export-download ,.export-attempt-learner-answers .bulk-export-progress').removeClass('wrld-hidden');
      
      gerExportLearnerResult("csv");
      showProgressAttemptResult("csv","answers");
  });

  jQuery(".export-attempt-learner-answers .export-learner-xlsx").on('click',function(){
    jQuery(this).attr('disabled',true);
    jQuery('.export-attempt-learner-answers .bulk-export-download ,.export-attempt-learner-answers .bulk-export-progress').removeClass('wrld-hidden');
    
    gerExportLearnerResult("xlsx");
    showProgressAttemptResult("xlsx","answers");
  });



  function gerExportLearnerResult(type){
    let searchParams = new URLSearchParams(window.location.search)
    const quizId = searchParams.get('post_id');
    var fromDate = getTimeStamp(jQuery('#from_date'));
      var toDate = getTimeStamp(jQuery('#to_date'));

    let fields = {};
    fields.course_filter = -1;
    fields.group_filter = -1;
    fields.quiz_filter = quizId;
    fields.type = type;
    fields.user_id = userId;
    fields.start_date = fromDate;
    fields.end_date = toDate;
    // jQuery('.wrld-loader').show();
    jQuery('.filter-section').css({'opacity': 0.5, 'pointer-events': 'none'});
    jQuery('.bulk-export-download').css({'opacity': 0.5, 'pointer-events': 'none'});
    jQuery.ajax(
      {
        type: 'POST',
        url: qre_export_obj.ajax_url,
        timeout: 100000,
        retry_count: 0,
        retry_limit: 1,
        data: {
          action: 'wrld_export_learner_results',
          security: bulk_export_js_object.custom_reports_nonce,
          fields: fields,
        },
        success: function( response ) {
          jQuery('.filter-section').css({'opacity': 1, 'pointer-events': 'auto'});
          jQuery('.bulk-export-download').css({'opacity': 1, 'pointer-events': 'auto'});
          jQuery('.export-attempt-learner-answers .bulk-export-progress progress').val(100);
          createDownloadButton(type , response , "wrld-bulk-export-learner-results-success");
          clearInterval(interval);
          // getCustomReports(1);
        },
        error: function( xhr, status, error_thrown ) {
          if ( status === 'timeout' ) {
              this.retry_count++;
              if ( this.retry_count <= this.retry_limit ) {
                  console.log( 'Retrying' );
                  jQuery.ajax( this );
                  return;
            } else {
              console.error( 'request timed out' );
            }
          } else {
            console.log( error_thrown );
          }
        } 
      }
      );
    
      // document.getElementById("wisdm-learndash-reports-quiz-report-view").scrollIntoView();
  };



  //tootip 
  jQuery(".report-label span").hover(function () {
    var jQuerydiv = jQuery('<div/>').addClass('wdm-tooltip').css({
      position: 'absolute',
      zIndex: 999,
      display: 'none'
    }).appendTo(jQuery(this));
    jQuerydiv.text(jQuery(this).attr('data-title'));
    var jQueryfont = jQuery(this).parents('.bulk-export-modal').css('font-family');
    jQuerydiv.css('font-family', jQueryfont);
    jQuerydiv.show();
  }, function () {
    jQuery(this).find(".wdm-tooltip").remove();
  });

  //getTimestamp
  function getTimeStamp(p) {
    var date = p.datepicker('getDate');

    return date === null ? 0 : date.getTime() / 1000;
  }

  //LD filter button event listener
  jQuery('#filter').on('click',function(){

    //copying values in modal
    jQuery('#from_date').val(jQuery('#datepickerFrom').val());
    jQuery('#to_date').val(jQuery('#datepickerTo').val());
      let searchParams = new URLSearchParams(window.location.search)
      const quizId = searchParams.get('post_id');
   
      const fromDate =getTimeStamp(jQuery('#datepickerFrom'));
      const toDate = getTimeStamp(jQuery('#datepickerTo'));
      userId = jQuery('#wpProQuiz_historyUser').val();
      //setting selected user in modal
      const selectUserName = jQuery('.select2-container--learndash .select2-selection--single:eq(0)').text().slice(1);
      var newOption = new Option(selectUserName, userId, true, true);
      jQuery('#selec_uxample').append(newOption).trigger('change');
      
      let fields = {};
      fields.course_filter = -1;
      fields.group_filter = -1;
      fields.quiz_filter = quizId;
      fields.user_id = userId;
      fields.start_date = fromDate;
      fields.end_date = toDate;

      jQuery('.export-attempt-results .bulk-export-download ,.export-attempt-results .bulk-export-progress').addClass('wrld-hidden');
      jQuery('.export-attempt-learner-answers .bulk-export-download ,.export-attempt-learner-answers .bulk-export-progress').addClass('wrld-hidden');
      jQuery('.export-attempt-results .bulk-export-download').html('');
      jQuery('.export-attempt-learner-answers .bulk-export-download').html('');
      jQuery('.export-attempt-results .export-attempt-csv').attr('disabled',false);
      jQuery('.export-attempt-results .export-attempt-xlsx').attr('disabled',false);
      jQuery('.export-attempt-learner-answers .export-learner-csv').attr('disabled',false);
      jQuery('.export-attempt-learner-answers .export-learner-xlsx').attr('disabled',false);
      jQuery('.export-attempt-results .bulk-export-progress progress').val(0);
      
      fetchDataCount(fields);
  });


  function fetchDefaultData(){
    let searchParams = new URLSearchParams(window.location.search);
      const quizId = searchParams.get('post_id');
      const fromDate = '';
      const toDate = '';
      userId = 0;
      let fields = {};
      fields.course_filter = -1;
      fields.group_filter = -1;
      fields.quiz_filter = quizId;
      fields.user_id = userId;
      fields.start_date = fromDate;
      fields.end_date = toDate;
      fetchDataCount(fields);
  }
  fetchDefaultData();

function initializeCal() {
  jQuery('.quiz-reporting-custom-filters .selector #from_date').datepicker({
    closeText: wpProQuizLocalize.closeText,
    currentText: wpProQuizLocalize.currentText,
    monthNames: wpProQuizLocalize.monthNames,
    monthNamesShort: wpProQuizLocalize.monthNamesShort,
    dayNames: wpProQuizLocalize.dayNames,
    dayNamesShort: wpProQuizLocalize.dayNamesShort,
    dayNamesMin: wpProQuizLocalize.dayNamesMin,
    dateFormat: wpProQuizLocalize.dateFormat,
    firstDay: wpProQuizLocalize.firstDay,

  changeMonth: true,
  onClose: function(selectedDate) {
    jQuery('#to_date').datepicker('option', 'minDate', selectedDate);
  }
});

jQuery('#to_date').datepicker({
    closeText: wpProQuizLocalize.closeText,
    currentText: wpProQuizLocalize.currentText,
    monthNames: wpProQuizLocalize.monthNames,
    monthNamesShort: wpProQuizLocalize.monthNamesShort,
    dayNames: wpProQuizLocalize.dayNames,
    dayNamesShort: wpProQuizLocalize.dayNamesShort,
    dayNamesMin: wpProQuizLocalize.dayNamesMin,
    dateFormat: wpProQuizLocalize.dateFormat,
    firstDay: wpProQuizLocalize.firstDay,

  changeMonth: true,
  onClose: function(selectedDate) {
    jQuery('.quiz-reporting-custom-filters .selector #from_date').datepicker('option', 'maxDate', selectedDate);
  }
});
}

//Select2 for user synchronization

var userMethods = {
  usersSelect2Ajax: function( eld ) {
    var nonce = jQuery('#wpProQuiz_historyUser').data('nonce');
    var el =jQuery('#wpProQuiz_historyUser');
    if ((typeof nonce === 'undefined') || (nonce === '')) {
      console.log("returned");
      return null;
    }
    
    // Trigger change when the selector is cleared.
    jQuery(el).on('select2:unselect', function (e) {
      jQuery(el).trigger('change');
    });

    return {
      url: learndash_admin_settings_data.ajaxurl,
      dataType: 'json',
      method: 'post',
      delay: 1500,
      cache: true,
      data: function (params) {
        return {
          'action': 'learndash_quiz_statistics_users_select2',
          'nonce': nonce || '',
          'search': params.term || '',
          'page': params.page || 1,
          'quiz_pro_id': jQuery('input#quizId').val(),
          'quiz_post_id': jQuery('input#quiz').val(),
        };
      },
      processResults: function (response, params) {
        params.page = params.page || 1;
        return {
          results: response.items,
          pagination: {
            more: (params.page < response.total_pages)
          }
        };
      },
    }
  }
};


  var itemsuser = '#selec_uxample';
  var select2_argsusr = learndash_get_base_select2_args();
  select2_argsusr.width = 'auto';

  var placeholder = jQuery(itemsuser).attr('placeholder');
  if ((typeof placeholder === 'undefined') || (placeholder === '')) {
    placeholder = jQuery("option[value='']", itemsuser).text();
  }
  if ((typeof placeholder === 'undefined') || (placeholder === '')) {
    placeholder = 'Select an option';
  }
  select2_argsusr.placeholder = placeholder;

  select2_argsusr.ajax = userMethods.usersSelect2Ajax(itemsuser);
  jQuery(itemsuser).select2(select2_argsusr);		

  jQuery('.wrp_user_dropdown .select2 .selection').on('click',function(){
    jQuery('.select2-container:eq(2)').css('z-index',99999999999);
  });




  jQuery(itemsuser).on('change', function (e) {
    var selectedValues = jQuery(this).val();  //Get the selected Values
    jQuery('#wpProQuiz_historyUser').val(selectedValues);        //Update S2 with selected values.
  });
});