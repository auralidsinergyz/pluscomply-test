<?php
/**
 * Report Export Form/AJAX request Submission
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
if ( ! class_exists( 'Export_File_Processing' ) ) {
	/**
	 * Export_File_Processing Class.
	 *
	 * @class Export_File_Processing
	 */
	class Export_File_Processing {
		/**
		 * The single instance of the class.
		 *
		 * @var Export_File_Processing
		 * @since 2.1
		 */
		protected static $instance = null;

		/**
		 * Export_File_Processing Instance.
		 *
		 * Ensures only one instance of Export_File_Processing is loaded or can be loaded.
		 *
		 * @since 3.0.0
		 * @static
		 * @return Export_File_Processing - instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * QuizReportingExtension Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 *
		 * @since 3.0.0
		 */
		private function init_hooks() {
			add_action( 'init', array( $this, 'create_quiz_reporting_zip' ), 5 );
			add_action( 'init', array( $this, 'qre_export_data_request' ) );
			add_action( 'wp_ajax_qre_export_statistics', array( $this, 'qre_export_data_request' ) );
			add_action( 'wp_ajax_export_course_statistics', array( $this, 'export_course_data_request' ) );
		}

		/**
		 * This method is used to create a zip file for bulk export.
		 *
		 * @return void.
		 */
		public function create_quiz_reporting_zip() {
			if ( isset( $_POST['export_zip'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification
				$upload_dir = wp_upload_dir();
				// @codingStandardsIgnoreStart
				$format = isset( $_POST['file_format'] ) ? $_POST['file_format'] : '';
	            // @codingStandardsIgnoreEnd
				$root_path = \realpath( $upload_dir['basedir'] . '/QuizReporting_' . $format );

				// Initialize archive object.
				$zip        = new \ZipArchive();
				$zip_opened = $zip->open( $upload_dir['basedir'] . '/QuizReporting_' . $format . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE );
				if ( true === $zip_opened ) {
					// Create recursive directory iterator.
					/**
					 * File names in the uploads directory.
					 *
					 * @var SplFileInfo[] $files
					 */
					$files = new \RecursiveIteratorIterator(
						new \RecursiveDirectoryIterator( $root_path ),
						\RecursiveIteratorIterator::LEAVES_ONLY
					);

					foreach ( $files as $name => $file ) {
						// Skip directories (they would be added automatically).
						if ( ! $file->isDir() ) {
							// Get real and relative path for current file.
							$file_path     = $file->getRealPath();
							$relative_path = substr( $file_path, strlen( $root_path ) + 1 );

							// Add current file to archive.
							$zip->addFile( $file_path, $relative_path );
						}
					}

					// . phpcs fix
					unset( $name );

					// Zip archive will be created only after closing object.
					$zip->close();

					global $wp_filesystem;
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
					header( 'Content-disposition: attachment; filename=QuizReporting_' . $format . '.zip' );
					header( 'Content-type: application/zip' );
					echo $wp_filesystem->get_contents( $upload_dir['basedir'] . '/QuizReporting_' . $format . '.zip' );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					// User readfile instead of wp_filesystem if bulk export doesn't work.
					unlink( $upload_dir['basedir'] . '/QuizReporting_' . $format . '.zip' );
					$dir      = $upload_dir['basedir'] . '/QuizReporting_' . $format;
					$dir      = $upload_dir['basedir'] . '/QuizReporting_' . $format;
					$iterator = new \RecursiveDirectoryIterator( $dir, \RecursiveDirectoryIterator::SKIP_DOTS );
					$files    = new \RecursiveIteratorIterator(
						$iterator,
						\RecursiveIteratorIterator::CHILD_FIRST
					);
					foreach ( $files as $file ) {
						if ( $file->isDir() ) {
							rmdir( $file->getRealPath() );
						} else {
							unlink( $file->getRealPath() );
						}
					}
					rmdir( $dir );
					die();
				} else {
					die( esc_html( 'Could not create a ZIP file. Error: ' . $zip_opened ) );
				}
			}
		}

		/**
		 * QRE Export Processing.
		 * creates csv file
		 **/
		public function qre_export_data_request() {
			if ( isset( $_POST['file_format'] ) && ! isset( $_POST['export_zip'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification
				// ini_set('memory_limit', '-1');
				// ini_set('max_execution_time', '-1');
				// Don't cache the result.
				wp_suspend_cache_addition( true );

				// Get all data of statistics.

				$data = Quiz_Export_Data::instance()->qre_export_data_generation();
				if ( empty( $data ) ) {
					die( esc_html__( 'Something went wrong OR data not found!!!', 'learndash-reports-pro' ) );
				}

				// Stores all the required values from the form.

				// Data to be inserted in Csv or Excel.

				// Quiz Data.
				$arr_data = check_isset( $data, 'arr_data' );

				// Custom Data.
				$arr_custom_data = check_isset( $data, 'arr_custom_data' );

				// User name.
				$qre_uname = check_isset( $data, 'name' );

				// Quiz Title.
				$qre_quiz = check_isset( $data, 'quiz_title' );

				// Format of Export.
				$file_format = check_isset( $_REQUEST, 'file_format' );// phpcs:ignore WordPress.Security.NonceVerification

				if ( 'csv' === $file_format ) {
					if ( ! empty( $arr_data ) ) {
						$table = $this->csv_table( $arr_data, $arr_custom_data );
					} // $arr_data
					$table = htmlentities( $table );
				} else { // Data for xls format.
					if ( ! empty( $arr_data ) ) {
						$table = $this->xls_table( $arr_data, $arr_custom_data );
						// This array contains Data to be send to wdmExportCSV.php
						// It contains data for each row, each cell
						// Also contains style for each cell.
					}

					$table = wp_json_encode( $table );

					if ( $table == 'null' ) {
						$table = '';
					}
				} //else part ends.

				// Checks for the data to be exported.
				if ( '' !== $table && '' !== $file_format ) {
					// If username and Quiz title is not empty then set file name using Username and Quiz title.
					if ( ! empty( $qre_uname ) && ! empty( $qre_quiz ) ) {
						$file_name = $qre_quiz . '-' . $qre_uname;
						$file_name = str_replace( ' ', '_', $file_name );
						$file_name = preg_replace( '/[^A-Za-z0-9\-]/', '', $file_name );
					} else { // Else set it to sample.
						$file_name = 'sample';
					}

					header( 'Content-type: application/ms-excel' );
					header( 'Content-Disposition: attachment; filename=' . $file_name . '.' . $file_format );

					// Checks if the format is Csv.
					if ( 'csv' === $file_format ) {
						$this->create_csv_file( $table, $file_name );
					} else { // Create Excel sheet if format is xls.
						$this->create_xls_file( $table, $file_name );
					}
				} elseif ( isset( $_POST['page'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification
					die();
				} else {
					esc_html_e( 'Something went wrong OR data not found!!!', 'learndash-reports-pro' );
				}

				exit;
			}
		}

		/**
		 * Export Course report data as Excel sheet from Learndash Reporting Solution
		 */
		public function export_course_data_request() {
			if ( ! wp_verify_nonce( $_REQUEST['report_nonce'], 'wisdm_ld_reports_page' ) ) {
				return false;
			}
			$postData     = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
			$tableHeaders = $postData['tableHeaders'];
			$tableData    = json_decode( html_entity_decode( stripslashes( $postData['tableData'] ) ) );

			if ( empty( $tableData ) ) {
				wp_send_json(
					array(
						'status' => 'fail',
						'data'   => __(
							'Unable to process this request',
							'learndash-reports-by-wisdmlabs'
						),
					),
					200
				);
			}
			// convert data to required format,
			$excel_data = array();
			$row_index  = 1;

			// Header Formatting
			foreach ( $tableHeaders as $headerCell ) {
				$excel_data[0][] = $headerCell['Header'];
			}

			foreach ( $tableData as $row ) {
				foreach ( $row as $cell ) {
					$excel_data[ $row_index ][] = $cell;
				}
				$row_index++;
			}

			unset( $tableData );
			// include libraries & generate excel file
			$spreadsheet = new Spreadsheet();
			$activeSheet = $spreadsheet->getActiveSheet();
			$activeSheet->fromArray( $excel_data, null, 'A1', true );
			$columnsToAutoResize = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H' );
			array_map(
				function( $column ) use ( $activeSheet ) {
						   $activeSheet->getColumnDimension( $column )->setAutoSize( true );},
				$columnsToAutoResize
			);

			$upload_dir = wp_upload_dir();
			if ( ! file_exists( $upload_dir['basedir'] . '/ld_reports/table_export.xlsx' ) ) {
				mkdir( $upload_dir['basedir'] . '/ld_reports/' );
			}

			$file       = $upload_dir['basedir'] . '/ld_reports/table_export.xlsx';
			$xlsxWriter = new Xlsx( $spreadsheet );
			$xlsxWriter->save( $file );
			$spreadsheet->disconnectWorksheets();
			unset( $xlsxWriter );
			unset( $spreadsheet );

			$url = $upload_dir['baseurl'] . '/ld_reports/table_export.xlsx';
			wp_send_json(
				array(
					'status' => 'success',
					'data'   => $url,
				),
				200
			);
			// file
			die();
		}

		/**
		 * This method manages information to put in csv file.
		 *
		 * @param array $arr_data Quiz Data.
		 * @param array $arr_custom_data Custom Form Data.
		 * @since     1.0.0
		 */
		public function csv_table( $arr_data, $arr_custom_data ) {
			$table                    = '';
			$table                   .= '<table id="qre_export_table">';
			$wisdmlabs_question_types = array(
				'single'             => esc_html__( 'Single choice', 'learndash' ),
				'multiple'           => esc_html__( 'Multiple choice', 'learndash' ),
				'free_answer'        => esc_html__( 'Free choice', 'learndash' ),
				'sort_answer'        => esc_html__( 'Sorting choice', 'learndash' ),
				'matrix_sort_answer' => esc_html__( 'Matrix Sorting choice', 'learndash' ),
				'cloze_answer'       => esc_html__( 'Fill in the blank', 'learndash' ),
				'assessment_answer'  => esc_html__( 'Assessment', 'learndash' ),
				'essay'              => esc_html__( 'Essay / Open Answer', 'learndash' ),
			);

			foreach ( $arr_data as $key => $val ) {
				if ( isset( $val['question_meta'] ) && ! is_array( $val['question_meta'] ) ) {
					continue;
				}
				$key = $key;
				if ( isset( $val['question_meta'] ) ) {
					$table .= '<tr>';
					/* translators: %s : Quiz Title. */
					$table .= '<td>' . sprintf( __( 'QUIZ TITLE: %s', 'learndash-reports-pro' ), str_replace( '&#39;', "'", html_entity_decode( $val['quiz_title'] ) ) ) . '</td>';
					/* translators: %s : User Name. */
					$table .= '<td>' . sprintf( __( 'USER LOGIN: %s', 'learndash-reports-pro' ), $val['user_login_name'] ) . '</td>';
					/* translators: %s : User ID. */
					$table .= '<td>' . sprintf( __( 'USER ID: %s', 'learndash-reports-pro' ), $val['user_id'] ) . '</td>
	                </tr><tr>
	                    <td>' . __( 'QUESTION', 'learndash-reports-pro' ) . '</td>
	                    <td>' . __( 'OPTIONS', 'learndash-reports-pro' ) . '</td>
	                    <td>' . __( 'CORRECT ANSWERS', 'learndash-reports-pro' ) . '</td>
	                    <td>' . __( 'USER RESPONSE', 'learndash-reports-pro' ) . '</td>
	                    <td>' . __( 'POINTS', 'learndash-reports-pro' ) . '</td>
	                    <td>' . __( 'POINTS SCORED', 'learndash-reports-pro' ) . '</td>
	                    <td>' . __( 'TIME TAKEN', 'learndash-reports-pro' ) . '</td>
	                    <td>' . __( 'QUESTION TYPE', 'learndash-reports-pro' ) . '</td>
	                </tr>';

					$question_str = '';
					foreach ( $val['question_meta'] as $qkey => $qval ) {
						$qkey          = $qkey;
						$question_str .= '<tr>
	                        <td>' . str_replace( '&#39;', "'", html_entity_decode( $qval['question'] ) ) . '</td>';
						$question_str .= '<td>';

						$question_str .= $this->get_question_str( $qval['answers'] );

						$question_str .= '</td>';

						$question_str .= '<td>';

						$question_str .= $this->get_question_str( $qval['correct_answers'] );

						$question_str .= '</td>';

						$question_str .= '<td>';
						$cnt           = 1;
						foreach ( $qval['user_response'] as $answer ) {
							// user response.
							if ( '' !== $answer ) {
								$question_str .= $cnt . ') ' . str_replace( '&#39;', "'", html_entity_decode( $answer ) ) . '<br />';
							}
							$cnt++;
						}
						$question_str .= '</td>';

						$question_str .= '<td>' . $qval['points'] . '</td>
	                        <td>' . $qval['points_scored'] . '</td>
	                        <td>' . gmdate( 'H:i:s', $qval['time_taken'] ) . '</td>
	                        <td>' . $wisdmlabs_question_types[ $qval['question_type'] ] . '</td>
	                    </tr>';
					}

					$table .= $question_str;

					// for custom fields - starts.
					if ( isset( $arr_custom_data[ $val['ref_id'] ] ) ) {
						$options_types        = array( 'dropdown', 'radio' );
						$question_str_custom  = '';
						$question_str_custom .= '<tr><td>' . __( 'CUSTOM FIELDS', 'learndash-reports-pro' ) . '</td></tr>';
						foreach ( $arr_custom_data[ $val['ref_id'] ] as $cust_val ) {
							$question_str_custom .= '<tr>
	                            <td>' . str_replace( '&#39;', "'", html_entity_decode( $cust_val['question'] ) ) . '</td>';

							$question_str_custom .= '<td>';

							if ( in_array( $cust_val['answer_type'], $options_types, true ) ) {
								if ( is_array( $cust_val['answer_data'] ) ) {
									$cnt = 1;
									foreach ( $cust_val['answer_data'] as $answer ) {
										// options.
										$question_str_custom .= $cnt . ') ' . str_replace( '&#39;', "'", html_entity_decode( $answer ) ) . '<br />';
										$cnt++;
									}
								} else {
									$question_str_custom .= str_replace( '&#39;', "'", html_entity_decode( $cust_val['qsanswer_data'] ) );
								}
							}

							$question_str_custom .= '</td>';

							$question_str_custom .= '<td> </td>
	                                <td>' . str_replace( '&#39;', "'", html_entity_decode( $cust_val['qsanswer_data'] ) ) . '</td>
	                                <td> </td>
	                                <td> </td>
	                                <td> </td>
	                                <td>' . $cust_val['answer_type'] . '</td>
	                            </tr>';
						}
						$table .= $question_str_custom;
					}

					// for custom fields - ends.

					$table .= '<tr>
	                        <td> ' . __( 'TOTAL', 'learndash-reports-pro' ) . ' </td>
	                        <td> </td>
	                        <td> </td>
	                        <td> </td>
	                        <td>' . $val['total_points'] . '</td>
	                        <td>' . $val['tot_points_scored'] . ' ( ' . round( ( ( $val['tot_points_scored'] / $val['total_points'] ) * 100 ), 2 ) . '% )</td>
	                        <td>' . gmdate( 'H:i:s', $val['tot_time_taken'] ) . '</td>
	                        <td> </td>
	                    </tr>';
					$table .= '<tr>
	                        <td> </td>
	                    </tr>
	                    <tr>
	                        <td> </td>
	                    </tr>';
				}
			}

			$table .= '</table>';

			return $table;
		}

		/**
		 * [get_question_str Loops through array and generates string]
		 *
		 * @param  array $data [Array of a data].
		 * @return [string] [String of data]
		 */
		public function get_question_str( $data = array() ) {
			$question_str = '';
			$cnt          = 1;
			foreach ( $data as $value ) {
				// correct answers.
				$question_str .= $cnt . ') ' . str_replace( '&#39;', "'", html_entity_decode( $value ) ) . '<br />';
				$cnt++;
			}

			return $question_str;
		}

		/**
		 * This method manages information to put in xls file
		 *
		 * @param array $arr_data Quiz Data.
		 * @param array $arr_custom_data Custom Form Data.
		 * @since     1.0.0
		 */
		public function xls_table( $arr_data, $arr_custom_data ) {
			$table = array(
				0 => array(
					0 => array(
						'value' => '',
						'font'  => array(),
					),
				),
			);

			// For row number.
			$index                    = 0;
			$wisdmlabs_question_types = array(
				'single'             => esc_html__( 'Single choice', 'learndash' ),
				'multiple'           => esc_html__( 'Multiple choice', 'learndash' ),
				'free_answer'        => esc_html__( 'Free choice', 'learndash' ),
				'sort_answer'        => esc_html__( 'Sorting choice', 'learndash' ),
				'matrix_sort_answer' => esc_html__( 'Matrix Sorting choice', 'learndash' ),
				'cloze_answer'       => esc_html__( 'Fill in the blank', 'learndash' ),
				'assessment_answer'  => esc_html__( 'Assessment', 'learndash' ),
				'essay'              => esc_html__( 'Essay / Open Answer', 'learndash' ),
			);

			foreach ( $arr_data as $val ) {
				if ( isset( $val['question_meta'] ) && ! is_array( $val['question_meta'] ) ) {
					continue;
				}
				if ( isset( $val['question_meta'] ) ) {
					/* translators: %s : Quiz Title. */
					$table[ $index ][0]['value'] = sprintf( __( 'QUIZ TITLE: %s', 'learndash-reports-pro' ), str_replace( '&#39;', "'", html_entity_decode( $val['quiz_title'] ) ) );
					/* translators: %s : User Name. */
					$table[ $index ][1]['value'] = sprintf( __( 'USER LOGIN: %s', 'learndash-reports-pro' ), $val['user_login_name'] );
					/* translators: %s : User ID. */
					$table[ $index ][2]['value'] = sprintf( __( 'USER ID: %s', 'learndash-reports-pro' ), $val['user_id'] );

					$table[ $index ][0]['font'] = array(
						'bold'   => 1,
						'italic' => 1,
					);
					$table[ $index ][1]['font'] = array(
						'bold'   => 1,
						'italic' => 1,
					);
					$table[ $index ][2]['font'] = array(
						'bold'   => 1,
						'italic' => 1,
					);

					$index++;

					$table[ $index ][0]['value'] = __( 'QUESTION', 'learndash-reports-pro' );
					$table[ $index ][1]['value'] = __( 'OPTIONS', 'learndash-reports-pro' );
					$table[ $index ][2]['value'] = __( 'CORRECT ANSWERS', 'learndash-reports-pro' );
					$table[ $index ][3]['value'] = __( 'USER RESPONSE', 'learndash-reports-pro' );
					$table[ $index ][4]['value'] = __( 'POINTS', 'learndash-reports-pro' );
					$table[ $index ][5]['value'] = __( 'POINTS SCORED', 'learndash-reports-pro' );
					$table[ $index ][6]['value'] = __( 'TIME TAKEN', 'learndash-reports-pro' );
					$table[ $index ][7]['value'] = __( 'QUESTION TYPE', 'learndash-reports-pro' );

					$table[ $index ][0]['font'] = array( 'bold' => 1 );
					$table[ $index ][1]['font'] = array( 'bold' => 1 );
					$table[ $index ][2]['font'] = array( 'bold' => 1 );
					$table[ $index ][3]['font'] = array( 'bold' => 1 );
					$table[ $index ][4]['font'] = array( 'bold' => 1 );
					$table[ $index ][5]['font'] = array( 'bold' => 1 );
					$table[ $index ][6]['font'] = array( 'bold' => 1 );
					$table[ $index ][7]['font'] = array( 'bold' => 1 );

					// For next row.
					$index++;

					foreach ( $val['question_meta'] as $qkey => $qval ) {
						$qkey                        = $qkey;
						$table[ $index ][0]['value'] = strip_tags( str_replace( '&#39;', "'", html_entity_decode( $qval['question'] ) ) ); // Question Column.

						// To number the options.

						// To append array values.
						$question_str  = '';
						$question_str .= str_replace( '&#39;', "'", html_entity_decode( $this->append_qstn_str( $qval['answers'] ) ) );

						$table[ $index ][1]['value'] = $question_str;
						$question_str                = '';
						$question_str               .= str_replace( '&#39;', "'", html_entity_decode( $this->append_qstn_str( $qval['correct_answers'] ) ) );

						$table[ $index ][2]['value'] = $question_str;
						$question_str                = '';

						$question_str .= str_replace( '&#39;', "'", html_entity_decode( $this->append_qstn_str( $qval['user_response'] ) ) );

						$table[ $index ][3]['value'] = $question_str;
						$question_str                = '';

						$table[ $index ][4]['value'] = $qval['points'];

						$table[ $index ][5]['value'] = $qval['points_scored'];

						$table[ $index ][6]['value'] = gmdate( 'H:i:s', $qval['time_taken'] );

						$table[ $index ][7]['value'] = $wisdmlabs_question_types[ $qval['question_type'] ];

						// Sets font color for different situations.
						if ( $qval['points'] === $qval['points_scored'] ) {
							$table[ $index ][3]['font'] = array( 'color' => array( 'rgb' => '#008000' ) );
						} elseif ( $qval['points_scored'] <= 0 ) {
							$table[ $index ][3]['font'] = array( 'color' => array( 'rgb' => '#FF0000' ) );
						} else {
							$table[ $index ][3]['font'] = array( 'color' => array( 'rgb' => '#0000FF' ) );
						}
						// Next row.
						$index++;
					}

					// for custom fields - starts.
					if ( isset( $arr_custom_data[ $val['ref_id'] ] ) ) {
						// To append array values.

						$table[ $index ][0]['value'] = __( 'CUSTOM FIELDS', 'learndash-reports-pro' );
						$table[ $index ][0]['font']  = array( 'bold' => 1 );

						$options_types = array( 'dropdown', 'radio' );

						foreach ( $arr_custom_data[ $val['ref_id'] ] as $cust_val ) {

							$question_str_custom = '';
							$index++;
							$table[ $index ][0]['value'] = str_replace( '&#39;', "'", html_entity_decode( $cust_val['question'] ) );

							if ( in_array( $cust_val['answer_type'], $options_types, true ) ) {
								$cnt = 1;
								if ( is_array( $cust_val['answer_data'] ) ) {
									foreach ( $cust_val['answer_data'] as $answer ) {
										// options.
										$question_str_custom .= $cnt . ') ' . str_replace( '&#39;', "'", html_entity_decode( $answer ) ) . "\n";
										$cnt++;
									}
								} else {
									$question_str_custom = str_replace( '&#39;', "'", html_entity_decode( $cust_val['qsanswer_data'] ) );
								}
							}

							$table[ $index ][1]['value'] = $question_str_custom;

							$table[ $index ][2]['value'] = '';

							$table[ $index ][3]['value'] = str_replace( '&#39;', "'", html_entity_decode( $cust_val['qsanswer_data'] ) );

							$table[ $index ][4]['value'] = '';

							$table[ $index ][5]['value'] = '';

							$table[ $index ][6]['value'] = '';

							$table[ $index ][7]['value'] = $cust_val['answer_type'];
						}
						// Next row.
						$index++;
					} // for custom fields - ends
					// For total.

					$table[ $index ][0]['value'] = __( 'TOTAL', 'learndash-reports-pro' );
					$table[ $index ][0]['font']  = array( 'bold' => 1 );

					$table[ $index ][1]['value'] = '';

					$table[ $index ][2]['value'] = '';

					$table[ $index ][3]['value'] = '';

					$table[ $index ][4]['value'] = $val['total_points'];
					$table[ $index ][4]['font']  = array( 'bold' => 1 );

					$table[ $index ][5]['value'] = $val['tot_points_scored'] . ' ( ' . round( ( ( $val['tot_points_scored'] / $val['total_points'] ) * 100 ), 2 ) . '% )';
					$table[ $index ][5]['font']  = array( 'bold' => 1 );

					$table[ $index ][6]['value'] = gmdate( 'H:i:s', $val['tot_time_taken'] );
					$table[ $index ][6]['font']  = array( 'bold' => 1 );

					$table[ $index ][7]['value'] = '';
					$index++;

					// For a blank row
					// $m as a loop variable.
					for ( $m = 0; $m < 8; $m++ ) {
						$table[ $index ][ $m ]['value'] = '';
					}
					$index++;
				}
			}

			return $table;
		}

		/**
		 * Create Question String shown in file.
		 *
		 * @param array $qval Questions array.
		 * @return string $question_str Question String displayed.
		 */
		public function append_qstn_str( $qval ) {
			$cnt          = 1;
			$question_str = '';
			foreach ( $qval as $answer ) {
				if ( '' !== $answer ) {
					// user response.
					$question_str .= $cnt . ') ' . $answer . "\n";
				}
				$cnt++;
			}

			return $question_str;
		}

		/**
		 * This method creates csv file.
		 *
		 * @param string $table data to put in file.
		 * @param string $filename File Name.
		 **/
		public function create_csv_file( $table, $filename ) {
			// Include library which we used to convert Html to Csv data.
			include LDRP_PLUGIN_DIR . 'packages/simple_html_dom.php';
			// Library's function to get Html from the data.
			$html = str_get_html( htmlspecialchars_decode( $table ) );

			// Opens a file in write mode.
			$upload_dir = wp_upload_dir();
			$file       = null;
	        // @codingStandardsIgnoreStart
			if ( isset( $_POST['page'] ) ) {
				if ( ! file_exists( $upload_dir['basedir'] . '/QuizReporting_' . $_POST['file_format'] ) ) {
					mkdir( $upload_dir['basedir'] . '/QuizReporting_' . $_POST['file_format'] );
				}
				$file      = fopen( $upload_dir['basedir'] . '/QuizReporting_' . $_POST['file_format'] . '/' . $filename . '_' . $_POST['page'] . '.csv', 'w' );
			} else {
				$file      = fopen( 'php://output', 'w' );
			}
	        // @codingStandardsIgnoreEnd
			// Checks if file opened on php output stream
			if ( $file ) {
				// For each row of the table in Data recieved.
				foreach ( $html->find( 'tr' ) as $element ) {
					// For Headings.
					$th = array();
					foreach ( $element->find( 'th' ) as $row ) {
						$th[] = $row->plaintext;
					}
					// Inserts Heading into the csv file.
					if ( ! empty( $th ) ) {
						fputcsv( $file, $th );
					}
					// For cell's value.
					$td = array();
					// For each cell.
					foreach ( $element->find( 'td' ) as $row ) {
						$td[] = $row->plaintext;
					}
					// Inserts Each cell's value and points to next row.
					if ( ! empty( $td ) ) {
						fputcsv( $file, $td );
					}
				}
				// Closes the Csv file.
				fclose( $file );
			} else {
				esc_html_e( 'File Permission Issue!!!', 'learndash-reports-pro' );
			}
		}

		/**
		 * This method creates .xlsx file of report.
		 *
		 * @param string $table data to put in file.
		 * @param string $filename File Name.
		 * @since     1.0.0
		 */
		public function create_xls_file( $table, $filename ) {

			$spreadsheet = new Spreadsheet();

			$excel_data = array();

			$data  = json_decode( $table, true );
			$table = null;

			// Counter for $data array loop.
			$dnt = 0;

			foreach ( $data as $row_key => $row_val ) {
				$sheet = $spreadsheet->getActiveSheet();

				// Counter for $row_val array loop.
				$rnt = 0;

				foreach ( $row_val as $cell_key => $cell_val ) {
					$excel_data[ $dnt ][ $rnt ] = $cell_val['value'];

					// Setting height if multi lines present.
					if ( strpos( $cell_val['value'], "\n" ) !== false ) {
						$count_lines        = count( explode( "\n", $cell_val['value'] ) ) + 1;
						$current_row_height = $sheet->getRowDimension( $dnt + 1 )->getRowHeight();
						if ( ( 20 * $count_lines ) > $current_row_height ) {
							$sheet->getRowDimension( $dnt + 1 )->setRowHeight( 15 * $count_lines );
						}
						$current_row_height = null;
						$count_lines        = null;
					}

					// Adding formatting if required.
					if ( isset( $cell_val['font'] ) ) {
						$sheet->getStyle( chr( 65 + $rnt ) . '' . ( $dnt + 1 ) )->applyFromArray( array( 'font' => $cell_val['font'] ) );
					}

					$data[ $row_key ][ $cell_key ] = null;

					$rnt++;
				}

				// Here 8 because we have 7 columns in the file.
				for ( $snt = $rnt; $snt < 8; $snt++ ) {
					$excel_data[ $dnt ][ $snt ] = '';
				}

				$dnt++;

				$data[ $row_key ] = null;
			}

			// Adding whole data to object.
			$spreadsheet->getActiveSheet()->fromArray( $excel_data, null, 'A1' );

			// Setting auth widht to all the columns.
			$spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'B' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'D' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'F' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'G' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'H' )->setAutoSize( true );

			$sheet = $spreadsheet->getActiveSheet();

			// ob_end_clean(); // Dont know why this was added.
			// Object to wrie into the file and save in Php output stream.
			$upload_dir = wp_upload_dir();
			$writer     = new Xlsx( $spreadsheet );
			$file       = '';
	        // @codingStandardsIgnoreStart
			if ( isset( $_POST['page'] ) ) {
				if ( ! file_exists( $upload_dir['basedir'] . '/QuizReporting_' . $_POST['file_format'] ) ) {
					mkdir( $upload_dir['basedir'] . '/QuizReporting_' . $_POST['file_format'] );
				}
				$file      = $upload_dir['basedir'] . '/QuizReporting_' . $_POST['file_format'] . '/' . $filename . '_' . $_POST['page'] . '.xlsx';
	        // @codingStandardsIgnoreEnd
			} else {
				$file = 'php://output';
			}
			$writer->save( $file );

			$spreadsheet->disconnectWorksheets();
			unset( $spreadsheet );
		}
	}
}
