import './index.scss';
import React, { Component } from "react";
import WisdmLoader from '../commons/loader/index.js';
import moment from 'moment';
import { __ } from '@wordpress/i18n';

class TotalLearners extends Component {
    constructor(props) {
      super(props);
  
      this.state = {
        isLoaded: false,
        error: null,
        start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
      };
      
      this.durationUpdated = this.durationUpdated.bind(this);
      this.updateBlock     = this.updateBlock.bind(this);
    }

    durationUpdated(event) {
      this.setState({start_date:event.detail.startDate, end_date:event.detail.endDate});
      this.updateBlock();
    }
  
    componentDidMount() {  
      document.addEventListener('duration_updated', this.durationUpdated);  
      this.updateBlock();
    }

    updateBlock() {
      wp.apiFetch({
        path: '/rp/v1/total-learners?start_date=' + this.state.start_date + '&end_date=' + this.state.end_date, 
     }).then(response => {
      let percentChange        = response.percentChange;
      let chnageDirectionClass = 'udup';
      let percentValueClass    = 'change-value';
      let hideChange           = '';
      let udtxt = '';
      let udsrc = '';
      if ( 0 < percentChange ) {
        chnageDirectionClass = 'udup';
        percentValueClass    = 'change-value-positive';
        udtxt = __('Up', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
      } else if( 0 > percentChange ) {
        chnageDirectionClass = 'uddown'
        percentValueClass    = 'change-value-negative';
        udtxt = __('Down', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/down.png';
      } else if( 0 == percentChange ){
        hideChange = 'wrld-hidden';
        udtxt = __('Up', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
      }
      this.setState(
              {
              isLoaded: true,
              graphData: {
                totalLearners: response.totalLearners,
                percentChange:percentChange + '%',
                chnageDirectionClass:chnageDirectionClass,
                percentValueClass:percentValueClass,
                hideChange: hideChange,
                udtxt: udtxt,
                udsrc: udsrc
              },
              startDate: moment.unix(response.requestData.start_date).format("MMM, DD YYYY"),
              endDate: moment.unix(response.requestData.end_date).format("MMM, DD YYYY"),
            });  
        }).catch((error) => {
          this.setState({
            error:error,
              graph_summary:[],
            isLoaded: true,
            series:[],

          });
        });
    }
  
    render() {
      let body = <div></div>;
      if (!this.state.isLoaded) {
        // yet loading
        body =  <WisdmLoader />;
    } else if (this.state.error) {
        // error
        body = <div class="wisdm-learndash-reports-chart-block error">
        <div>{this.state.error.message}</div>
        </div>;
    } else {
      body =  
      <div class="wisdm-learndash-reports-chart-block"> 
            <div class="total-learners-container top-card-container">
                <div className="wrld-date-filter">
                  <span class="dashicons dashicons-calendar-alt"></span>
                  <div className="wdm-tooltip">
                    {__('Date filter applied:', 'learndash-reports-by-wisdmlabs')}<br />{this.state.startDate} - {this.state.endDate}
                  </div>
                </div>
                <div class="total-learners-icon">
                  <img src={wisdm_learndash_reports_front_end_script_total_learners.plugin_asset_url + '/images/icon_learners_counter.png'}>
                  </img>
                </div>
                <div class="total-learners-details">
                    <div class="total-learners-text top-label-text">
                        <span>{__('Total Learners','learndash-reports-by-wisdmlabs')}</span>
                    </div>
                    <div class="total-learners-figure">
                        <span>{this.state.graphData.totalLearners}</span>
                    </div>
                    <div class={`total-learners-percent-change ${this.state.graphData.hideChange}`}>
                      <span class={this.state.graphData.chnageDirectionClass}>
                        <img src={this.state.graphData.udsrc}></img>
                      </span>
                      <span class={this.state.graphData.percentValueClass}>{this.state.graphData.percentChange}</span>
                      <span class="ud-txt">{this.state.graphData.udtxt}</span>
                    </div>
                </div>
            </div>
          </div>;
    }
      return (body);
    }
  }

export default TotalLearners;

document.addEventListener("DOMContentLoaded", function(event) {
    let elem = document.getElementsByClassName('wisdm-learndash-reports-total-learners front');
    if (elem.length>0) {
        ReactDOM.render(React.createElement(TotalLearners), elem[0]); 
    }
});

