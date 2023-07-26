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

function wisdmLDRConvertTime(seconds) {                     
  var hours = Math.floor(seconds / 3600);
  var minutes = Math.floor(seconds % 3600 / 60);
  var seconds = Math.floor(seconds % 3600 % 60);
  if (hours   < 10) {hours   = "0" + hours;}
  if (minutes < 10) {minutes = "0" + minutes;}
  if (seconds < 10) {seconds = "0" + seconds;}
  if ( !!hours ) {                                         
    if ( !!minutes ) {                                     
      return `${hours}:${minutes}:${seconds}`           
    } else {                                               
      return `${hours}:00:${seconds}`                       
    }                                                      
  }                                                        
  if ( !!minutes ) {                                       
    return `00:${minutes}:${seconds}`                       
  }                                                        
  return `00:00:${seconds}`                                     
}

class TimeSpentOnACourseDonutChart extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoaded: false,
      error: null,
      series:[],
      options:[],
      reportTypeInUse: wisdm_learndash_reports_front_end_script_time_spent_on_a_course.report_type,
      chart_title:__('Time Spent On a', 'learndash-reports-by-wisdmlabs') + ' ' +   wisdm_reports_get_ld_custom_lebel_if_avaiable('Course'),
      graph_type:'donut',
      graph_summary:[],
      start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
      help_text:__("This report displays the average time that learners spend per course.",'learndash-reports-by-wisdmlabs'),
      request_data:null,
      course_report_type: null,
      show_supporting_text: false,
    };

    this.durationUpdated        = this.durationUpdated.bind(this);
    this.applyFilters           = this.applyFilters.bind(this);
    this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
    this.showDummyImages        = this.showDummyImages.bind(this);
  }

  isValidGraphData() {
    if (undefined==this.state.options || 0==this.state.options.length) {
      return false;
    } 
    if (undefined==this.state.series || 0==this.state.series.length) {
      return false;
    }
    
    return true;
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
              path: requestUrl, //Replace with the correct API 
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
                  isLoaded: true,
                  series:[],
                });
              });
            }
          }, 500);
      };
      checkIfEmpty();
  }

  plotChartTypeBy(response) {
    this.setState({series:[], options:[]});
    if (undefined!=response.courseWiseTime) {
        const time_percent = Object.values( response.courseWiseTime ).map( obj => parseFloat( obj.time ) );
        const courses = Object.values( response.courseWiseTime ).map( obj => obj.course );

        this.plotPieChart(time_percent, courses);
        this.setState(
          {
          isLoaded: true,
          chart_title : __('Time Spent On a Course', 'learndash-reports-by-wisdmlabs'),
          graph_summary: {
            left: [{
                  title : __('AVG TIME SPENT', 'learndash-reports-by-wisdmlabs'),
                  value: wisdmLDRConvertTime(response.averageCourseTime),
                },],

            right:[{
                title : __('Total Time Spent: ', 'learndash-reports-by-wisdmlabs'),
                value: wisdmLDRConvertTime(response.courseTotalTime),
              }, 
              {
                title: wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses') + ': ',
                value:response.courseCount,
              }],
            inner_help_text: __( 'Avg Time Spent = Total Time Spent on Courses/Total Courses', 'learndash-reports-by-wisdmlabs' ),
          },
          help_text:__("This report displays the average time that learners spend per course.",'learndash-reports-by-wisdmlabs'),

        }); 
    } else if(undefined!=response.time_spent) {
        const time_spent = Object.values( response.time_spent ).map( obj => parseFloat( obj.time ) );
        const users = Object.values( response.time_spent ).map( obj => obj.username );
        this.plotBarChart(users, time_spent, __('Student/Learner', 'learndash-reports-by-wisdmlabs'), __('Time Spent', 'learndash-reports-by-wisdmlabs'))
        const chart_for = response.requestData.topic ? __("Topic", 'learndash-reports-by-wisdmlabs') : response.requestData.lesson ? __("Lesson", 'learndash-reports-by-wisdmlabs') : __("Course", 'learndash-reports-by-wisdmlabs');
        const  chart_title =__('Time Spent On a', 'learndash-reports-by-wisdmlabs') + ' '+ chart_for;
        this.setState(
          {
          isLoaded: true,
          chart_title : chart_title,
          graph_summary: {
            left: [{
                  title : __('AVG TIME SPENT PER LEARNER', 'learndash-reports-by-wisdmlabs'),
                  value: wisdmLDRConvertTime(response.average_time_spent),
                },],
                
            right:[{
                title : __('Total Time Spent: ', 'learndash-reports-by-wisdmlabs'),
                value: wisdmLDRConvertTime(response.total_time),
              }, 
              {
                title: __('Learners: ', 'learndash-reports-by-wisdmlabs'),
                value:response.total_learners,
              }],
            inner_help_text: __( 'Avg Time Spent = Total Time Spent/No. of Learners', 'learndash-reports-by-wisdmlabs' ),
          },
          help_text: __("This report displays the average time that learners spend on this course.", 'learndash-reports-by-wisdmlabs'),
        });
    }
  }

  plotBarChart(dataX, dataY, nameX, nameY) {
    let chart_options = {
      chart: {
        type:'bar',
        width: dataX.length*75 < 645 ? '100%' : dataX.length*75,
        height: 400,
        zoom:{
          enabled:false,
        },
        toolbar:{
          show:true,
          export: {
            csv: {
              filename: __( 'Time Spent.csv', 'learndash-reports-by-wisdmlabs' ),
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
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return wisdmLDRConvertTime( val );
        },
        offsetY: -25,
        style: {
          fontSize: '12px',
          colors: ["#008AD8"]
        }
      },
      plotOptions: {
        bar: {
          borderRadius: 5,
          dataLabels: {
            enabled: true,
            position:'top',
          },
        }
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
        tickPlacement: 'on',
        min:1,
      },
      yaxis: {
        max: Math.max.apply(Math, dataY)>0?Math.max.apply(Math, dataY) + Math.max.apply(Math, dataY)/10:10,
        axisBorder: {
          show: true
        },
        title: {
          text: nameY,
        },
        labels: {
          formatter: function(value) {
            return  wisdmLDRConvertTime(value);
          } 
        }
      },
    }

    this.setState({graph_type:'bar', series:[{name:nameY, data:dataY}], options:chart_options});

  }


  plotPieChart(data, labels, tooltipTextLineOne = __('Time Spent', 'learndash-reports-by-wisdmlabs')) {
    let chart_options = {
      colors:['#5f5f5f'],
      theme: {
        monochrome: {
          enabled: true,
          color:'#008AD8',
          shadeTo: 'dark',
          shadeIntensity: 0.65,
        }
      },
      chart: {
        width: '100%',
        type: 'donut',
        dropShadow: {
          enabled: true,
          color: '#111',
          top: -1,
          left: 3,
          blur: 3,
          opacity: 0.2
        },
        events: {
          mounted: function(chartContext, config) {
              window.callStack.pop();
            }
        }
      },
      stroke: {
        width: 0,
      },
      
      plotOptions: {
        pie: {
          donut: {
            dataLabels: {
              enabled: true,
            },
            total: {
              show: false,
              showAlways: false,
              label: 'Total',
              fontSize: '22px',
              fontFamily: 'Helvetica, Arial, sans-serif',
              fontWeight: 600,
              color: '#373d3f',
              formatter: function (w) {
                return w.globals.seriesTotals.reduce((a, b) => {
                  return a + b
                }, 0)
              }
            }
          }
        }
      },
      labels: labels,
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
        }
      }],
      legend: {
        formatter:function(seriesName, opts) {
          return [seriesName, " - ", wisdmLDRConvertTime(opts.w.globals.series[opts.seriesIndex]), ""]
      }
      },
      tooltip: {
        custom: function({series, seriesIndex, dataPointIndex, w}) {
          return '<div class="wisdm-donut-chart-tooltip"> <div class="tooltip-title"><span>' + w.globals.labels[seriesIndex] + '</span></div><div class="tooltip-body"> <span>' + tooltipTextLineOne + ': ' + wisdmLDRConvertTime(series[seriesIndex]) + '</span></div></div>';
        },

        y: {
          formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
            return  wisdmLDRConvertTime(value);
          }
        },
      },
      };
    this.setState({graph_type:'donut',series:data, options:chart_options});
        window.callStack.pop();
  }

  componentDidMount() {    
    this.updateChart('/rp/v1/time-spent-on-a-course/?start_date=' + this.state.start_date+'&&end_date='+this.state.end_date);
    document.addEventListener('duration_updated', this.durationUpdated);
    document.addEventListener('wisdm-ld-reports-filters-applied', this.applyFilters);
    document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
    document.addEventListener('wisdm-ldrp-course-report-type-changed', this.showDummyImages);
  }

  componentDidUpdate() {
    jQuery('.time-spent-on-a-course .mixed-chart').prepend(jQuery('.time-spent-on-a-course .apexcharts-toolbar'));
    jQuery( ".wisdm-learndash-reports-time-spent-on-a-course .chart-title .dashicons ,.wisdm-learndash-reports-time-spent-on-a-course .chart-summary-revenue-figure .dashicons" ).hover(

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
    jQuery('.wisdm-learndash-reports-time-spent-on-a-course .chart-title .tooltip').hover(function(){
      var $font = jQuery(this).parents('.graph-card-container').css('font-family');
      jQuery(this).css('font-family', $font);
    });


   
  }

  handleReportTypeChange(event) {
    this.setState({reportTypeInUse:event.detail.active_reports_tab});
    if ( 'quiz-reports' == event.detail.active_reports_tab ) {
      wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-time-spent-on-a-course', false);
    } else {
      wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-time-spent-on-a-course', true);
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
 
    let request_url = '/rp/v1/time-spent-on-a-course/?start_date=' + start_date + '&end_date=' + end_date + '&category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner;
    if ( undefined != course ) {
      this.setState({show_supporting_text: true});
    } else {
      this.setState({show_supporting_text: false});
    }
    //Time spent on a course chart should not display for lesson/topic
    if (undefined==topic && undefined==lesson) {
        this.updateChart(request_url);
        this.setState({reportTypeInUse:'default-ld-reports'});
        wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-time-spent-on-a-course' , true );
     } else{
      //hide this block.
        this.setState({reportTypeInUse:'default-ld-reports-lesson-topic'});
        wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-time-spent-on-a-course' , false );
    }
   }

  durationUpdated(event) {
    this.setState({isLoaded: false, start_date:event.detail.startDate, end_date:event.detail.endDate});
    let requestUrl = '/rp/v1/time-spent-on-a-course/';
    if ('duration_updated'==event.type) {
      requestUrl = '/rp/v1/time-spent-on-a-course/?start_date=' + event.detail.startDate + '&&end_date=' + event.detail.endDate;
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

  render() {
    let body = <div></div>;
    if(this.state.course_report_type == 'learner-specific-course-reports' && !wisdm_ld_reports_common_script_data.is_pro_version_active){
        body =  <DummyReports image_path='tsoc.png'></DummyReports>;
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
      body =  <WisdmLoader text={this.state.show_supporting_text}/>
  } else {
    let graph = '';
    if (!this.state.error) {
      graph = <div class="time-spent-on-a-course">
                <Chart options={this.state.options} series={this.state.series} width={this.state.options.chart.width} height={this.state.options.chart.height} type={this.state.graph_type} />
              </div>;
    }


    //tooltip message configuration 
   let tooltip_text = "";
   let icon_enabled = false;
   const time_tracking_enabled = wisdm_learndash_reports_front_end_script_course_list.is_idle_tracking_enabled;
   const block_description = this.state.request_data.topic ? __("This graph shows the Time Spent by Learners on this Topic", 'learndash-reports-by-wisdmlabs') : this.state.request_data.lesson ? __("This graph shows the Time Spent by Learners on this Lesson", 'learndash-reports-by-wisdmlabs') : __("This report displays the average time that learners spend per course", 'learndash-reports-by-wisdmlabs');
  if(wisdm_learndash_reports_front_end_script_course_list.is_admin_user){
    //If current user is admin
      if(wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active){
        //need time tracking module setting
        if(time_tracking_enabled == 'on'){
          //Checking weather time tracking is enabled or not
          tooltip_text = <p>{__('Idle Time Configured , Activated on ', 'learndash-reports-by-wisdmlabs' ) + wisdm_learndash_reports_front_end_script_course_list.idle_tracking_active_from +'. '} <a href={wisdm_learndash_reports_front_end_script_course_list.time_tacking_setting_url} >{__('View Idle Time Configuration Log', 'learndash-reports-by-wisdmlabs' )}</a> </p>;
            icon_enabled = true;
        }else{
          tooltip_text = 
                        <div class="tooltip_container">
                          <p>{__('\"Idle Time\" not configured. Configure the Settings from here ', 'learndash-reports-by-wisdmlabs' )}</p>
                          <a href={wisdm_learndash_reports_front_end_script_course_list.time_tacking_setting_url} class="tooltip_button">{__('Time Tracking Setting', 'learndash-reports-by-wisdmlabs' )}</a>
                        </div>
                        ;
        }
      }else{
        tooltip_text = 
                        <div class="tooltip_container">
                          <p>{__('\"Idle Time\" not configured. This is available in the PRO version of the plugin', 'learndash-reports-by-wisdmlabs' )}</p>
                          <a href={wisdm_learndash_reports_front_end_script_report_filters.upgrade_link} target="_blank" class="tooltip_button">{__('Upgrade To PRO', 'learndash-reports-by-wisdmlabs' )}</a>
                        </div>;
      }

  }else{
    //For non-admin users group leader , instructor
      if(wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active){
        //need time tracking module setting
        if(time_tracking_enabled == 'on'){
          tooltip_text =  __('Idle Time Configured , Activated at', 'learndash-reports-by-wisdmlabs' ) + wisdm_learndash_reports_front_end_script_course_list.idle_tracking_active_from;
          icon_enabled = true;
            }else{
              tooltip_text = __('Idle Time Not Configured', 'learndash-reports-by-wisdmlabs');;
            }
      }else{
        console.log("Pro version is not active");
      }
  }


    body =  
    <div class={"wisdm-learndash-reports-chart-block " + data_validation}> 
      <div class="wisdm-learndash-reports-time-spent-on-a-course graph-card-container">
        <WisdmFilters request_data={this.state.request_data}/>
        <div class="chart-header time-spent-on-a-course-chart-header">
            <div class="chart-title">
              <span>{this.state.chart_title}</span>
              <div class="tooltip">
                {icon_enabled && <img src={wisdm_learndash_reports_front_end_script_total_courses.plugin_asset_url + '/images/time_tracking_active.png'}>
                  </img>}

                {!icon_enabled && <img src={wisdm_learndash_reports_front_end_script_total_courses.plugin_asset_url + '/images/time-tracking-disabled.png'}>
                  </img>}
              {/* on remove tooltiptext class button inside tootip will not be reachable */}
              <span class="tooltiptext wdm-tooltip">{block_description}<br/><br/>{tooltip_text}</span>
              </div>
            </div>
            <ChartSummarySection wrapper_class='chart-summary-time-spent-on-a-course' graph_summary={this.state.graph_summary} error={this.state.error}/>
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

export default TimeSpentOnACourseDonutChart;

document.addEventListener("DOMContentLoaded", function(event) {
    // Your code to run since DOM is loaded and ready

    let elem = document.getElementsByClassName('wisdm-learndash-reports-time-spent-on-a-course');
    if (elem.length>0) {
      ReactDOM.render(React.createElement(TimeSpentOnACourseDonutChart), elem[0]); 
    }
});

