import './index.scss';
import ApexCharts from 'apexcharts';
import ChartSummarySection from '../commons/chart-summary/index.js';
import WisdmFilters from '../commons/filters/index.js';
import WisdmLoader from '../commons/loader/index.js';
import DummyReports from '../commons/dummy-reports/index.js';
import React, { Component } from "react";
import Chart from "react-apexcharts";
import moment from 'moment';
import { __ } from '@wordpress/i18n';


class CourseCompletionRate extends Component {
  constructor(props) {
    super(props);
      let error=null;
      if(null==this.getUserType()) {
        error = {message:__('Sorry you are not allowed to access this block, please check if you have proper access permissions','learndash-reports-by-wisdmlabs')}
      }
      this.state = {
        isLoaded: false,
        error: error,
        reportTypeInUse: wisdm_learndash_reports_front_end_script_course_completion_rate.report_type,
        chart_title: wisdm_reports_get_ld_custom_lebel_if_avaiable('Course')+ ' ' + __('Completion Rate', 'learndash-reports-by-wisdmlabs'),
        graph_type:'bar',
        lock_icon:'',
        series:[],
        options:[],
        graph_summary:[],
        start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
        end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),  
        request_data:null,        
        help_text:__('This report displays the average completion rate of courses.','learndash-reports-by-wisdmlabs'),
        course_report_type: null,
        show_supporting_text: false,
      };

      if (false==wisdm_learndash_reports_front_end_script_course_completion_rate.is_pro_version_active) {
        this.upgdare_to_pro = 'wisdm-ld-reports-upgrade-to-pro-front';
        this.lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports top-corner"></span>
      } 

      this.durationUpdated        = this.durationUpdated.bind(this);
      this.applyFilters           = this.applyFilters.bind(this);
      this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
      this.showDummyImages        = this.showDummyImages.bind(this);
    }
  
    isValidGraphData() {
      if (undefined==this.state.options || 0==this.state.options.length) {
        return false;
      } 
      if (undefined==this.state.series || undefined==this.state.series[0]) {
        return false;
      }
      if (undefined==this.state.series[0].data || 0==this.state.series[0].data.length) {
        return false;
      }
      return true;
    }

    /**
     * Based on the current user roles aray this function desides wether a user is a group
     * leader or an Administrator and returns the same.
     */
    getUserType() {
      let userRoles = wisdm_learndash_reports_front_end_script_average_quiz_attempts.user_roles;
      if ('object'==typeof(userRoles)) {
        userRoles = Object.keys(userRoles).map((key) => userRoles[key]);
      }
      if (undefined==userRoles || userRoles.length==0) {
        return null;
      }
      if (userRoles.includes('administrator')) {
          return 'administrator';
      } else if (userRoles.includes('group_leader')) {
          return 'group_leader';
      }
      return null;
    }

    componentDidMount() {    
      this.updateChart('/rp/v1/course-completion-rate?start_date=' + this.state.start_date + '&&end_date=' + this.state.end_date);
      document.addEventListener('duration_updated', this.durationUpdated);
      document.addEventListener('wisdm-ld-reports-filters-applied', this.applyFilters);
      document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
      document.addEventListener('wisdm-ldrp-course-report-type-changed', this.showDummyImages);
    }

    componentDidUpdate() {
      jQuery('.CourseCompletionRate .mixed-chart').prepend(jQuery('.CourseCompletionRate .apexcharts-toolbar'));
      jQuery( ".wisdm-learndash-reports-course-completion-rate .chart-title .dashicons, .wisdm-learndash-reports-course-completion-rate .chart-summary-revenue-figure .dashicons" ).hover(

        function() {
          var $div = jQuery('<div/>').addClass('wdm-tooltip').css({
              position: 'absolute',
              zIndex: 999,
              display: 'none'
          }).appendTo(jQuery(this));
          $div.text(jQuery(this).attr('data-title'));
          var $font = jQuery(this).parents('.graph-card-container').css('font-family');
          $div.css('font-family', $font);
          $div.show();
        }, function() {
          jQuery( this ).find( ".wdm-tooltip" ).remove();
        }
      );
    }

    handleReportTypeChange(event) {
        this.setState({reportTypeInUse:event.detail.active_reports_tab});
        if ( 'quiz-reports' == event.detail.active_reports_tab ) {
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-completion-rate', false);
        } else {
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-completion-rate', true);
        }
    }

    showDummyImages(event){
      this.setState({course_report_type:event.detail.report_type})
    }


    applyFilters(event) {
      let start_date = event.detail.start_date;
      let end_date   = event.detail.end_date;
      let category   = event.detail.selected_categories;
      let group      = event.detail.selected_groups;
      let course     = event.detail.selected_courses;
      let lesson     = event.detail.selected_lessons;
      let topic      = event.detail.selected_topics;
      let learner    = event.detail.selected_learners;
   
      let request_url = '/rp/v1/course-completion-rate/?start_date=' + start_date + '&end_date=' + end_date + '&category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner;
   
    if ( undefined != course ) {
      this.setState({show_supporting_text: true});
    } else {
      this.setState({show_supporting_text: false});
    }

      //Time spent on a course chart should not display for lesson/topic
    if (undefined==topic && undefined==lesson) {
      this.updateChart(request_url);
      this.setState({reportTypeInUse:'default-ld-reports'});
      wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-course-completion-rate' , true );
    } else {
        //hide this block.
        this.setState({reportTypeInUse:'default-ld-reports-lesson-topic'});
        wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-course-completion-rate' , false );
    }
  }

    durationUpdated(event) {
      this.setState({isLoaded: false, start_date:event.detail.startDate, end_date:event.detail.endDate});
      let requestUrl = '/rp/v1/course-completion-rate/';
      if ('duration_updated'==event.type) {
        requestUrl = '/rp/v1/course-completion-rate/?start_date=' + event.detail.startDate + '&&end_date=' + event.detail.endDate;
      } 
      if (window.globalfilters != undefined) {
        let category   = window.globalfilters.detail.selected_categories;
        let group      = window.globalfilters.detail.selected_groups;
        let course     = window.globalfilters.detail.selected_courses;
        let lesson     = window.globalfilters.detail.selected_lessons;
        let topic      = window.globalfilters.detail.selected_topics;
        let learner    = window.globalfilters.detail.selected_learners;
        requestUrl = requestUrl  + '&category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner;
        
      } 

      
      this.updateChart(requestUrl);
    }

    updateChart(requestUrl) {
      this.setState({isLoaded:false, error:null, request_data:null});
      let self = this;
      let checkIfEmpty = function() {
        setTimeout(function () {
          if (window.callStack.length > 4) {
            checkIfEmpty();
          }
          else {
          window.callStack.push(requestUrl);
           wp.apiFetch({
             path: requestUrl //Replace with the correct API
          }).then(response => {
             if(response.requestData) {
               self.setState({request_data:response.requestData})
             }
             self.plotChartTypeBy(response);
             }).catch((error) => {
              window.callStack.pop();

               if(error.data && error.data.requestData) {
                 self.setState({request_data:error.data.requestData})
               }
               self.setState({
                 error:error,
                 graph_summary:[],
                 series:[],
                 isLoaded: true,
               });
             });
          }
        }, 500);
      };
      checkIfEmpty();
      }

      plotChartTypeBy(response) {
        if (undefined!=response.courseWiseCompletion) {
          const completion = Object.values( response.courseWiseCompletion ).map( obj => obj.completion );
          const courses    = Object.values( response.courseWiseCompletion ).map( obj => obj.title );
          this.plotBarChart(courses, completion, wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses'), __('Percentage of Learners - Completed', 'learndash-reports-by-wisdmlabs'));
          let avgTimeSpent = response.averageCourseCompletion;
          let lock_icon = '';
          if (true!=wisdm_learndash_reports_front_end_script_course_completion_rate.is_pro_version_active) {
            avgTimeSpent = '??';
            lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports top-corner"></span>
          }
          this.setState(
            {
            isLoaded: true,
            lock_icon:lock_icon,
            graph_summary: {
              left: [{
                    title : __('AVG', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + ' ' + __('COMPLETION RATE', 'learndash-reports-by-wisdmlabs'),
                    value: '??'!=avgTimeSpent?Number(parseFloat(avgTimeSpent).toFixed(2)) + '%':avgTimeSpent,
                  },],
  
              right:[
                {
                  title : __('Total Learners', 'learndash-reports-by-wisdmlabs') + ': ',
                  value : parseInt( response.completedCount ) + parseInt( response.notstartedCount ) + parseInt( response.inprogressCount ),
                },
                {
                  title : wisdm_reports_get_ld_custom_lebel_if_avaiable( 'Courses' ) + ': ',
                  value : Object.keys( response.courseWiseCompletion ).length,
                },
                {
                  title : __('Learners - Completed All ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses') + ': ',
                  value : response.completedCount,
                }, 
                {
                  title : __('Learners - Not Started Any ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses') + ': ',
                  value : response.notstartedCount,
                },
                {
                  title: __('Learners - In Progress', 'learndash-reports-by-wisdmlabs') + ': ',
                  value : response.inprogressCount,
                }],
              inner_help_text: __('Avg Course Completion Rate = Rate of completion per course/No. of Courses', 'learndash-reports-by-wisdmlabs'),
            },
            help_text:__('This report displays the average completion rate of courses.','learndash-reports-by-wisdmlabs'),
          }); 
        } else if(undefined!=response.progress_data) {
          let user_names = Object.values( response.progress_data ).map( obj => obj.user_name );
          let progress  = Object.values( response.progress_data ).map( obj => obj.progress );
          let x_name    = 0!=response.requestData.learner.length?wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses'):__('Learners', 'learndash-reports-by-wisdmlabs');
          this.plotBarChart(user_names, progress, x_name, __('Rate of Completion', 'learndash-reports-by-wisdmlabs'));          
          let avgTimeSpent = response.averageCourseCompletion;
          let lock_icon = '';
          if (true!=wisdm_learndash_reports_front_end_script_course_completion_rate.is_pro_version_active) {
            avgTimeSpent = '??';
          }
          this.setState(
            {
            isLoaded: true,
            lock_icon:lock_icon,
            graph_summary: {
              left: [{
                    title : __('COMPLETION RATE', 'learndash-reports-by-wisdmlabs'),
                    value: '??'!=avgTimeSpent?Number(parseFloat(avgTimeSpent).toFixed(2)) + '%':avgTimeSpent,
                  },],
  
              right:[{
                  title : __('Total', 'learndash-reports-by-wisdmlabs') + ' ' + x_name + ': ',
                  value : parseInt( response.completedCount ) + parseInt( response.notstartedCount ) + parseInt( response.inprogressCount ),
                },
                {
                  title : __('Learners - Completed All ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses') + ': ',
                  value : response.completedCount,
                }, 
                {
                  title : __('Learners - Not Started Any ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses') + ': ',
                  value : response.notstartedCount,
                },
                {
                  title: __('Learners - In Progress', 'learndash-reports-by-wisdmlabs') + ': ',
                  value : response.inprogressCount,
                }],
            },
            help_text: __('This report displays which learners have completed the course.', 'learndash-reports-by-wisdmlabs'),
          }); 
        } else if(undefined!=response.progress_data_new) {
          let user_names = Object.values( response.progress_data_new ).map( obj => obj.user_name );
          let progress  = Object.values( response.progress_data_new ).map( obj => obj.progress );
          let x_name    = 0!=response.requestData.learner.length?wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses'):__('Learners', 'learndash-reports-by-wisdmlabs');
          this.plotBarChart(user_names, progress, x_name, __('Rate of Completion', 'learndash-reports-by-wisdmlabs'));          
          let avgTimeSpent = response.averageCourseCompletion;
          let lock_icon = '';
          if (true!=wisdm_learndash_reports_front_end_script_course_completion_rate.is_pro_version_active) {
            avgTimeSpent = '??';
          }
          this.setState(
            {
            isLoaded: true,
            lock_icon:lock_icon,
            graph_summary: {
              left: [{
                    title : __('COMPLETION RATE', 'learndash-reports-by-wisdmlabs'),
                    value: '??'!=avgTimeSpent?Number(parseFloat(avgTimeSpent).toFixed(2)) + '%':avgTimeSpent,
                  },],
  
              right:[{
                  title : __('Total', 'learndash-reports-by-wisdmlabs') + ' ' + x_name + ': ',
                  value : parseInt( response.completedCount ) + parseInt( response.notstartedCount ) + parseInt( response.inprogressCount ),
                },
                {
                  title : __('Learners - Completed All ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses') + ': ',
                  value : response.completedCount,
                }, 
                {
                  title : __('Learners - Not Started Any ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses') + ': ',
                  value : response.notstartedCount,
                },
                {
                  title: __('Learners - In Progress', 'learndash-reports-by-wisdmlabs') + ': ',
                  value : response.inprogressCount,
                }],
            },
            help_text: __('This report displays which learners have completed the course.', 'learndash-reports-by-wisdmlabs'),
          }); 
        }
      }

      plotBarChart(dataX, dataY, nameX, nameY) {
        let chart_options = {
          chart: {
            type: 'bar',
            width: dataX.length*75 < 645 ? '100%' : dataX.length*75,
            height: 400,
            zoom:{
              enabled:false,
            },
            toolbar:{
              show:true,
              export: {
                csv: {
                  filename: __( 'Completion Status.csv', 'learndash-reports-by-wisdmlabs' ),
                  columnDelimiter: ',',
                  headerCategory: nameX,
                  headerValue: nameY,
                },
                svg: {
                  filename: undefined,
                },
                png: {
                  filename: undefined,
                }
              },
            },
            events: {
              mounted: function(chartContext, config) {
                  window.callStack.pop();
                }
            }
          },
          fill: {
            colors: [function({ value, seriesIndex, w }) {
              return '#444444'
            }],
          },
          plotOptions: {
            bar: {
              borderRadius: 5,
              dataLabels: {
                position: 'top', // top, center, bottom
              },
            }
          },
          dataLabels: {
            enabled: true,
            formatter: function (val) {
              return val + "%";
            },
            offsetY: -25,
            style: {
              fontSize: '12px',
              colors: ["#304758"]
            }
          },

          itemMargin: {
              horizontal: 75,
              vertical: 100
          },
          
          xaxis: {
            title: {
              text: nameX,
            },
            categories: dataX,
            labels:{
                  hideOverlappingLabels: false,
                  trim: true,
              },
            itemMargin: {
                horizontal: 75,
                vertical: 100
            },
            tickPlacement: 'on',
            min: 1,
            // max:dataX.length>7?7:dataX.length,
            axisBorder: {
              show: false
            },
            axisTicks: {
              show: false
            },
            
            
          },
          yaxis: {
            max: Math.max.apply(Math, dataY)>0?Math.max.apply(Math, dataY) + Math.max.apply(Math, dataY)/10:10,
            title:{
              text:nameY,
            },
            axisBorder: {
              show: true,
            },
            axisTicks: {
              show: false,
            },
            labels: {
              show: false,
              formatter: function (val) {
                return val + "%";
              },
              min: 0,
              max: 100,
            }
          
          },
        }
    
        this.setState({graph_type:'bar', series:[{name:nameY, data:dataY}], options:chart_options});
        window.callStack.pop();
      }

  render() {
    let body = <div></div>;
    if(this.state.course_report_type == 'learner-specific-course-reports' && !wisdm_ld_reports_common_script_data.is_pro_version_active){
        body =  <DummyReports image_path='ccr.png'></DummyReports>;
        return (body);
    }
    let data_validation = '';
    if (!this.isValidGraphData()) {
      data_validation = 'invalid-or-empty-data';
    }
    if(''!=this.state.reportTypeInUse && 'default-ld-reports'!=this.state.reportTypeInUse) {
      body = '';
    } else if (!this.state.isLoaded) {
      // yet loading
      body =  <WisdmLoader text={this.state.show_supporting_text}/>;
  } else {
    let graph = '';
    if (!this.state.error) {
      graph = <div className="CourseCompletionRate">
                <div className="row">
                  <div className="mixed-chart">
                    <div className="ldr-toolbar">
                    </div>
                    <Chart
                      options={this.state.options}
                      series={this.state.series}
                      type={this.state.graph_type}
                      width={this.state.options.chart.width}
                      height={this.state.options.chart.height}
                    />
                  </div>
                </div>
              </div>;
    }
    body = 
    <div class={"wisdm-learndash-reports-chart-block " + data_validation}>
    <div class="wisdm-learndash-reports-course-completion-rate graph-card-container">
    <WisdmFilters request_data={this.state.request_data}/>
    <div class="chart-header course-completion-rate-chart-header">
        <div class="chart-title">
          <span>{this.state.chart_title}</span>
          <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={this.state.help_text}></span>
        </div>
        <ChartSummarySection pro_upgrade_option={this.upgdare_to_pro} wrapper_class='chart-summary-course-completion-rate' graph_summary={this.state.graph_summary} error={this.state.error}/>
      </div>
      <div>
        {graph}
      </div>
    </div>
  </div>;
  }
    return (body);
  }
}

export default CourseCompletionRate;

document.addEventListener("DOMContentLoaded", function(event) {
  
  let elem = document.getElementsByClassName('wisdm-learndash-reports-course-completion-rate front');
    if (elem.length>0) {
      ReactDOM.render(React.createElement(CourseCompletionRate), elem[0]); 
    }
    
});

