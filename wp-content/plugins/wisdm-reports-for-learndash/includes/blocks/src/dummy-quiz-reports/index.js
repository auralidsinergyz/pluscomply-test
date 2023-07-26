import React, { Component, CSSProperties } from "react";
import { __ } from '@wordpress/i18n';

class DummyFilters extends Component{
  constructor(props) {
    super(props); 
  }

  render(){
    let body = '';
    body = <div className="quiz-report-filters-wrapper wrld-dummy-filters">
            <div className="wrld-pro-note">
              <div className="wrld-pro-note-content">
                <span><b>{__('Note: ', 'learndash-reports-by-wisdmlabs')}</b>{__('Below is the dummy representation of the Quiz Reports available in WISDM Reports PRO.', 'learndash-reports-by-wisdmlabs')}</span>
              </div>
            </div>
            <div className="select-view">
              <span>{__('Select View', 'learndash-reports-by-wisdmlabs')}</span>
            </div>
            <div className="quiz-report-types">
              <input
                id="dfr"
                type="radio"
                name="quiz-report-types"
                defaultValue="default-quiz-reports"
                defaultChecked=""
              />
              <label htmlFor="dfr" className="">
              {__('Default Quiz Report View', 'learndash-reports-by-wisdmlabs')}
              </label>
              <input
                id="cqr"
                type="radio"
                name="quiz-report-types"
                defaultValue="custom-quiz-reports"
                checked
              />
              <label htmlFor="cqr" className="checked">
                {" "}
                {__('Customized Quiz Report View', 'learndash-reports-by-wisdmlabs')}
              </label>
            </div>
            <div>
              <div className="quiz-eporting-filter-section custom-filters">
                <div className="quiz-reporting-custom-filters">
                  <div className="selector">
                    <div className="selector-label">{__('Courses', 'learndash-reports-by-wisdmlabs')}</div>
                    <div className="select-control">
                      <div className=" css-b62m3t-container">
                        <span
                          id="react-select-8-live-region"
                          className="css-1f43avz-a11yText-A11yText"
                        />
                        <span
                          aria-live="polite"
                          aria-atomic="false"
                          aria-relevant="additions text"
                          className="css-1f43avz-a11yText-A11yText"
                        />
                        <div className=" css-1s2u09g-control">
                          <div className=" css-319lph-ValueContainer">
                            <div className=" css-qc6sy-singleValue">{__('All', 'learndash-reports-by-wisdmlabs')}</div>
                            <div className=" css-14dclt2-Input" data-value="">
                              <input
                                className=""
                                autoCapitalize="none"
                                autoComplete="off"
                                autoCorrect="off"
                                id="react-select-8-input"
                                spellCheck="false"
                                tabIndex={0}
                                type="text"
                                aria-autocomplete="list"
                                aria-expanded="false"
                                aria-haspopup="true"
                                aria-controls="react-select-8-listbox"
                                aria-owns="react-select-8-listbox"
                                role="combobox"
                                defaultValue=""
                                style={{
                                  color: "inherit",
                                  background: "0px center",
                                  opacity: 1,
                                  width: "100%",
                                  gridArea: "1 / 2 / auto / auto",
                                  font: "inherit",
                                  minWidth: 2,
                                  border: 0,
                                  margin: 0,
                                  outline: 0,
                                  padding: 0
                                }}
                              />
                            </div>
                          </div>
                          <div className=" css-1hb7zxy-IndicatorsContainer">
                            <div
                              className=" css-tlfecz-indicatorContainer"
                              aria-hidden="true"
                            >
                              <svg
                                height={20}
                                width={20}
                                viewBox="0 0 20 20"
                                aria-hidden="true"
                                focusable="false"
                                className="css-tj5bde-Svg"
                              >
                                <path d="M14.348 14.849c-0.469 0.469-1.229 0.469-1.697 0l-2.651-3.030-2.651 3.029c-0.469 0.469-1.229 0.469-1.697 0-0.469-0.469-0.469-1.229 0-1.697l2.758-3.15-2.759-3.152c-0.469-0.469-0.469-1.228 0-1.697s1.228-0.469 1.697 0l2.652 3.031 2.651-3.031c0.469-0.469 1.228-0.469 1.697 0s0.469 1.229 0 1.697l-2.758 3.152 2.758 3.15c0.469 0.469 0.469 1.229 0 1.698z" />
                              </svg>
                            </div>
                            <span className=" css-1okebmr-indicatorSeparator" />
                            <div
                              className=" css-tlfecz-indicatorContainer"
                              aria-hidden="true"
                            >
                              <svg
                                height={20}
                                width={20}
                                viewBox="0 0 20 20"
                                aria-hidden="true"
                                focusable="false"
                                className="css-tj5bde-Svg"
                              >
                                <path d="M4.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747 3.908-3.747c0.533-0.481 1.141-0.446 1.574 0 0.436 0.445 0.408 1.197 0 1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502 0.335-0.787 0.335s-0.57-0.112-0.789-0.335c0 0-4.287-4.084-4.695-4.502s-0.436-1.17 0-1.615z" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div className="selector">
                    <div className="selector-label">{__('Groups', 'learndash-reports-by-wisdmlabs')}</div>
                    <div className="select-control">
                      <div className=" css-b62m3t-container">
                        <span
                          id="react-select-9-live-region"
                          className="css-1f43avz-a11yText-A11yText"
                        />
                        <span
                          aria-live="polite"
                          aria-atomic="false"
                          aria-relevant="additions text"
                          className="css-1f43avz-a11yText-A11yText"
                        />
                        <div className=" css-1s2u09g-control">
                          <div className=" css-319lph-ValueContainer">
                            <div className=" css-qc6sy-singleValue">{__('All', 'learndash-reports-by-wisdmlabs')}</div>
                            <div className=" css-14dclt2-Input" data-value="">
                              <input
                                className=""
                                autoCapitalize="none"
                                autoComplete="off"
                                autoCorrect="off"
                                id="react-select-9-input"
                                spellCheck="false"
                                tabIndex={0}
                                type="text"
                                aria-autocomplete="list"
                                aria-expanded="false"
                                aria-haspopup="true"
                                aria-controls="react-select-9-listbox"
                                aria-owns="react-select-9-listbox"
                                role="combobox"
                                defaultValue=""
                                style={{
                                  color: "inherit",
                                  background: "0px center",
                                  opacity: 1,
                                  width: "100%",
                                  gridArea: "1 / 2 / auto / auto",
                                  font: "inherit",
                                  minWidth: 2,
                                  border: 0,
                                  margin: 0,
                                  outline: 0,
                                  padding: 0
                                }}
                              />
                            </div>
                          </div>
                          <div className=" css-1hb7zxy-IndicatorsContainer">
                            <div
                              className=" css-tlfecz-indicatorContainer"
                              aria-hidden="true"
                            >
                              <svg
                                height={20}
                                width={20}
                                viewBox="0 0 20 20"
                                aria-hidden="true"
                                focusable="false"
                                className="css-tj5bde-Svg"
                              >
                                <path d="M14.348 14.849c-0.469 0.469-1.229 0.469-1.697 0l-2.651-3.030-2.651 3.029c-0.469 0.469-1.229 0.469-1.697 0-0.469-0.469-0.469-1.229 0-1.697l2.758-3.15-2.759-3.152c-0.469-0.469-0.469-1.228 0-1.697s1.228-0.469 1.697 0l2.652 3.031 2.651-3.031c0.469-0.469 1.228-0.469 1.697 0s0.469 1.229 0 1.697l-2.758 3.152 2.758 3.15c0.469 0.469 0.469 1.229 0 1.698z" />
                              </svg>
                            </div>
                            <span className=" css-1okebmr-indicatorSeparator" />
                            <div
                              className=" css-tlfecz-indicatorContainer"
                              aria-hidden="true"
                            >
                              <svg
                                height={20}
                                width={20}
                                viewBox="0 0 20 20"
                                aria-hidden="true"
                                focusable="false"
                                className="css-tj5bde-Svg"
                              >
                                <path d="M4.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747 3.908-3.747c0.533-0.481 1.141-0.446 1.574 0 0.436 0.445 0.408 1.197 0 1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502 0.335-0.787 0.335s-0.57-0.112-0.789-0.335c0 0-4.287-4.084-4.695-4.502s-0.436-1.17 0-1.615z" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div className="selector">
                    <div className="selector-label">{__('Quizzes', 'learndash-reports-by-wisdmlabs')}</div>
                    <div className="select-control">
                      <div className=" css-b62m3t-container">
                        <span
                          id="react-select-10-live-region"
                          className="css-1f43avz-a11yText-A11yText"
                        />
                        <span
                          aria-live="polite"
                          aria-atomic="false"
                          aria-relevant="additions text"
                          className="css-1f43avz-a11yText-A11yText"
                        />
                        <div className=" css-1s2u09g-control">
                          <div className=" css-319lph-ValueContainer">
                            <div className=" css-qc6sy-singleValue">{__('All', 'learndash-reports-by-wisdmlabs')}</div>
                            <div className=" css-14dclt2-Input" data-value="">
                              <input
                                className=""
                                autoCapitalize="none"
                                autoComplete="off"
                                autoCorrect="off"
                                id="react-select-10-input"
                                spellCheck="false"
                                tabIndex={0}
                                type="text"
                                aria-autocomplete="list"
                                aria-expanded="false"
                                aria-haspopup="true"
                                aria-controls="react-select-10-listbox"
                                aria-owns="react-select-10-listbox"
                                role="combobox"
                                defaultValue=""
                                style={{
                                  color: "inherit",
                                  background: "0px center",
                                  opacity: 1,
                                  width: "100%",
                                  gridArea: "1 / 2 / auto / auto",
                                  font: "inherit",
                                  minWidth: 2,
                                  border: 0,
                                  margin: 0,
                                  outline: 0,
                                  padding: 0
                                }}
                              />
                            </div>
                          </div>
                          <div className=" css-1hb7zxy-IndicatorsContainer">
                            <div
                              className=" css-tlfecz-indicatorContainer"
                              aria-hidden="true"
                            >
                              <svg
                                height={20}
                                width={20}
                                viewBox="0 0 20 20"
                                aria-hidden="true"
                                focusable="false"
                                className="css-tj5bde-Svg"
                              >
                                <path d="M14.348 14.849c-0.469 0.469-1.229 0.469-1.697 0l-2.651-3.030-2.651 3.029c-0.469 0.469-1.229 0.469-1.697 0-0.469-0.469-0.469-1.229 0-1.697l2.758-3.15-2.759-3.152c-0.469-0.469-0.469-1.228 0-1.697s1.228-0.469 1.697 0l2.652 3.031 2.651-3.031c0.469-0.469 1.228-0.469 1.697 0s0.469 1.229 0 1.697l-2.758 3.152 2.758 3.15c0.469 0.469 0.469 1.229 0 1.698z" />
                              </svg>
                            </div>
                            <span className=" css-1okebmr-indicatorSeparator" />
                            <div
                              className=" css-tlfecz-indicatorContainer"
                              aria-hidden="true"
                            >
                              <svg
                                height={20}
                                width={20}
                                viewBox="0 0 20 20"
                                aria-hidden="true"
                                focusable="false"
                                className="css-tj5bde-Svg"
                              >
                                <path d="M4.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747 3.908-3.747c0.533-0.481 1.141-0.446 1.574 0 0.436 0.445 0.408 1.197 0 1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502 0.335-0.787 0.335s-0.57-0.112-0.789-0.335c0 0-4.287-4.084-4.695-4.502s-0.436-1.17 0-1.615z" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="filter-buttons">
                  <div className="filter-button-container">
                    <button className="button-customize-preview">{__('CUSTOMIZE REPORT', 'learndash-reports-by-wisdmlabs')}</button>
                    <button className="button-quiz-preview">{__('APPLY FILTERS', 'learndash-reports-by-wisdmlabs')}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>;
    return body;
  }
}

export default DummyFilters;