import './index.scss';
import React, { Component } from "react";
import { __ } from '@wordpress/i18n';

/**
 * @argument wrapper_class - The string passed as an attribute 'wrapper_class' will be used as an additional class for the wrapper having class 'chart-summary'
 * @argument graph_summary - The Data arguement graph_summary will be required in the following format.
 * graph_summary: {
            left: [{
                  title : __('AVG TIME SPENT ON THE QUIZZES', 'learndash-reports-by-wisdmlabs'),
                  value: wisdmLDRConvertTime(response.average_time_spent),
                },],
                
            right:[{
                title : __('Total Time Spent: ', 'learndash-reports-by-wisdmlabs'),
                value: wisdmLDRConvertTime(response.total_time),
              }, 
              {
                title: __('Total Learners: ', 'learndash-reports-by-wisdmlabs'),
                value:response.total_learners,
              }],
          },
 * @argument show_pro_version_upgrade_option bool
 */
class ChartSummarySection extends React.Component {
    constructor(props) {
      super(props);
      let upgrade_link = '';
      if ( wisdm_learndash_reports_front_end_script_course_completion_rate.is_admin_user ) {
        upgrade_link = <a class="overlay pro-upgrade" href={wisdm_learndash_reports_front_end_script_course_completion_rate.upgrade_link} target="__blank">
                              <div class="description">
                                <span class="upgrade-text">{__('Available in PRO version', 'learndash-reports-by-wisdmlabs')}</span>
                                <button class="upgrade-button">{__('UPGRADE TO PRO', 'learndash-reports-by-wisdmlabs')}</button>
                              </div>
                            </a>;
        
      }
      if (undefined==props.graph_summary.left) {
        props.graph_summary.left = [];
      } 
      if(undefined==props.graph_summary.right) {
        props.graph_summary.right = [];
      }

      // if (undefined==props.error) {
      //   props.error = false;
      // }
      

      if (undefined!=props.pro_upgrade_option && ''!=props.pro_upgrade_option && ! props.error) {
        props.graph_summary.left[0].value='??';
      }

      this.error                            = props.error;
      this.summary                          = props.graph_summary;
      this.summary_class                    = props.wrapper_class;
      this.show_pro_version_upgrade_option  = (undefined==props.pro_upgrade_option || ''==props.pro_upgrade_option)?'':'wisdm-ld-reports-upgrade-to-pro-front';
      this.lock_icon  =  undefined==props.pro_upgrade_option?'':<span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports top-corner"></span>;
      this.upgrade_anchor = undefined==props.pro_upgrade_option?'':upgrade_link;
      this.inner_tooltip  = undefined!=props.graph_summary.inner_help_text ? <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={props.graph_summary.inner_help_text}></span> : '';
    }
  
  
    render() {
      let summary = '';
        if (this.error) {
          summary = <div className={ this.summary_class + " chart-summary error"}>
                      <div class="error-message">
                          <span>
                            {this.error?this.error.message:__('Invalid data or no data found', 'learndash-reports-by-wisdmlabs')}
                          </span>
                      </div>
                  </div>
        } else {
          summary = <div class={ this.summary_class + " chart-summary "  + this.show_pro_version_upgrade_option}>
                        <div class={"revenue-figure-wrapper "}>
                          {this.lock_icon} 
                          <div class="chart-summary-revenue-figure">
                            <div class="revenue-figure">
                              <span class="summary-amount">{undefined!=this.summary.left[0]?this.summary.left[0].value:''}</span>
                            </div>
                            <div class="chart-summary-label"><span>{undefined!=this.summary.left[0]?this.summary.left[0].title:''}</span>{this.inner_tooltip}</div>
                          </div>
                          {this.upgrade_anchor}
                        </div>
                        <div class="revenue-particulars-wrapper">
                          <div class="chart-summary-revenue-particulars">
                            {
                              this.summary.right.map((summary_data, i)=>{
                                return (
                                        <div class="summery-right-entry">
                                          <span class="summary-label">{summary_data.title}</span>
                                          <span class="summary-amount">{summary_data.value}</span>
                                        </div> );})
                            }
                          </div>
                        </div>
                      </div>;
            }
      return summary;
    }
  }

  export default ChartSummarySection;
