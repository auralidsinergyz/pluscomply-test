<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Quiz_Export_Data' ) ) {
	/**
	 * Quiz_Export_Data Class.
	 *
	 * @class Quiz_Export_Data
	 */
	class Quiz_Export_Data {
		/**
		 * The single instance of the class.
		 *
		 * @var Quiz_Export_Data
		 * @since 2.1
		 */
		protected static $instance = null;

		/**
		 * Quiz_Export_Data Instance.
		 *
		 * Ensures only one instance of Quiz_Export_Data is loaded or can be loaded.
		 *
		 * @since 3.0.0
		 * @static
		 * @return Quiz_Export_Data - instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Quiz_Export_Data Constructor.
		 */
		public function __construct() {
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {

			/**
			 * Class Qre_Question_Data
			 */
			include_once QRE_PLUGIN_DIR . 'includes/question-types/class-qre-question-data.php';

			/**
			 * Class Qre_Single_Question_Data
			 */
			include_once QRE_PLUGIN_DIR . 'includes/question-types/class-qre-single-question-data.php';

			/**
			 * Class Qre_Assessment_Question_Data
			 */
			include_once QRE_PLUGIN_DIR . 'includes/question-types/class-qre-assessment-question-data.php';

			/**
			 * Class Qre_Cloze_Question_Data
			 */
			include_once QRE_PLUGIN_DIR . 'includes/question-types/class-qre-cloze-question-data.php';

			/**
			 * Class Qre_Essay_Question_Data
			 */
			include_once QRE_PLUGIN_DIR . 'includes/question-types/class-qre-essay-question-data.php';

			/**
			 * Class Qre_Free_Question_Data
			 */
			include_once QRE_PLUGIN_DIR . 'includes/question-types/class-qre-free-question-data.php';

			/**
			 * Class Qre_Matrix_Sort_Question_Data
			 */
			include_once QRE_PLUGIN_DIR . 'includes/question-types/class-qre-matrix-sort-question-data.php';

			/**
			 * Class Qre_Sort_Question_Data
			 */
			include_once QRE_PLUGIN_DIR . 'includes/question-types/class-qre-sort-question-data.php';
		}

		/**
		 * Export Data generation.
		 *
		 * @param integer $ref_id            Statistic Ref ID.
		 * @param string  $quiz_export_nonce Quiz Export Nonce.
		 */
		public function qre_export_data_generation( $ref_id = '', $quiz_export_nonce = '' ) {
			$qre_quiz_id     = 0;
			$arr_data        = array();
			$arr_custom_data = array();
			if ( empty( $ref_id ) ) {
				$ref_id = check_isset( $_POST, 'ref_id' );// phpcs:ignore WordPress.Security.NonceVerification
			}
			if ( empty( $quiz_export_nonce ) ) {
				$quiz_export_nonce = check_isset( $_POST, 'quiz_export_nonce' );// phpcs:ignore WordPress.Security.NonceVerification
			}

			$ref_id = $this->qre_check_count( $ref_id );

			if ( ! empty( $ref_id ) && wp_verify_nonce( $quiz_export_nonce, 'quiz_export-' . get_current_user_id() ) ) {

				// If format of export is CSV.
				$format       = check_isset( $_POST, 'file_format' );
				$retrive_data = array();
				$result       = array();
				$ref_ids      = explode( ',', $ref_id );
				try {
					foreach ( $ref_ids as $ref_id ) {
						$retrive_data = Quiz_Export_Db::instance()->get_quiz_attempt_data( $ref_id );
						$result       = array_merge( $result, $retrive_data );
					}
				} catch ( Exception $exception ) {
					echo esc_html( $exception->getMessage() );
					return;
				}

				if ( ! empty( $result ) ) {
					// not checked "$custom_result" because if there is only custom fields in quiz, then quiz doesn't show any question on the page.
					$arr_data         = array(); // main array of final data.
					$custom_form_data = array(); // array of custom form fields.

					$arr_process_data = array(); // array to loop.

					foreach ( $result as $res_key => $res_value ) {
						// to generate process array.

						$qre_ref_id = check_isset( $res_value, 'statistic_ref_id' );
						// added in version 1.1.2.
						$arr_process_data[ $qre_ref_id ] = init_key_as_empty_array( $arr_process_data, $qre_ref_id );

						array_push( $arr_process_data[ $qre_ref_id ], $res_value );

						$qre_quiz_id = $this->get_quiz_id( $res_value, $qre_quiz_id );
					}

					$custom_form_data = $this->get_custom_form_data( $qre_quiz_id );
					$arr_custom_data  = array(); // final custom fields process data.

					if ( ! empty( $custom_form_data ) ) {
						$arr_custom_inserted = array(); // to check if custom answers are already inserted in $arr_process_data.
						foreach ( $result as $res_key => $res_value ) {
							$qre_ref_id = check_isset( $res_value, 'statistic_ref_id' );
							if ( ! in_array( $qre_ref_id, $arr_custom_inserted, true ) ) {
								$custom_answers = $this->custom_quiz_answers( $qre_ref_id, $qre_quiz_id, $res_value['user_id'] ); // to get answers of a ref id.

								if ( ! empty( $custom_answers ) ) {
									if ( ( ! isset( $arr_custom_data[ $qre_ref_id ] ) ) ) {
										$arr_custom_data[ $qre_ref_id ] = array();
									}

									$qre_custom_process             = $this->generate_custom_process_data_array( $qre_ref_id, $res_value, $qre_quiz_id, $custom_answers );
									$arr_custom_data[ $qre_ref_id ] = $qre_custom_process;
									array_push( $arr_custom_inserted, $qre_ref_id );
								}
							}
						}//foreach
					}
					// for custom field answers - ends.
					$arr_data = $this->get_quiz_data( $res_value, $result, $arr_data, $arr_process_data );
				}
				// filter of question types for the custom fields.

				if ( ! empty( $arr_custom_data ) ) {
					$arr_custom_data = $this->generate_custom_data_array( $arr_custom_data );
				}
			}
			return array(
				'arr_data'        => $arr_data,
				'arr_custom_data' => $arr_custom_data,
				'quiz_title'      => $this->get_login_name( $arr_data, 'quiz_title' ),
				'name'            => $this->get_login_name( $arr_data ),
			);
		}

		/**
		 * Check for pagination and get 30 entries.
		 *
		 * @param  string $ref_id All Question IDs.
		 * @return string $ref_id All Question IDs.
		 */
		public function qre_check_count( $ref_id ) {
			if ( isset( $_POST['page'] ) && ! empty( $ref_id ) ) {// phpcs:ignore WordPress.Security.NonceVerification
				$page = (int) $_POST['page'];// phpcs:ignore
				$quiz_id = trim( $_POST['quiz_id'] ); // phpcs:ignore
				$result  = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_by_quiz( $quiz_id, array( 'page' => ++$page ) );
				$ref_id  = '';

				if ( is_array( $result ) ) {
					$result = array_filter( $result );
				}

				if ( ! empty( $result ) ) {
					foreach ( $result as $stat_id ) {
						$ref_id .= $stat_id['statistic_ref_id'] . ',';
					}
				}
				$ref_id = substr( $ref_id, 0, -1 ); // removing last char
				// to get all stat ids to export all responses - ends.

				// $refs      = explode( ',', $ref_id );
				// $total     = count( $refs );
				// $count     = 0;
				// $ref_array = array();
				// $page = $_POST['page'];// phpcs:ignore
				// // @ini_set( 'memory_limit', '512M' );
				// wp_raise_memory_limit();
				// set_time_limit( 30 );
				// // @ini_set( 'max_execution_time', 30 );
				// // @ini_set( 'post_max_size', '512M' );
				// for ( $i = $page * 30; $count < 30 && $i < $total; $i++ ) {
				// if ( 29 !== $count ) {
				// array_push( $ref_array, $refs[ $i ] );
				// }
				// $count++;
				// }
				// $ref_id = implode( ',', $ref_array );
				echo esc_html( count( $result ) );
			}
			return $ref_id;
		}

		/**
		 * Get Quiz ID.
		 *
		 * @param array $res_value Res Value.
		 * @param int   $qre_quiz_id Quiz ID.
		 * @return int $qre_quiz_id
		 */
		public function get_quiz_id( $res_value, $qre_quiz_id ) {
			if ( 0 === $qre_quiz_id ) {
				// if quiz id not set.
				$qre_quiz_id = $res_value['quiz_id'];
			}

			return $qre_quiz_id;
		}

		/**
		 * To get custom form data
		 *
		 * @param  integer $qre_quiz_id  Quiz ID.
		 * @return array $custom_form_data Custom Form Data.
		 */
		public function get_custom_form_data( $qre_quiz_id ) {
			if ( ! empty( $qre_quiz_id ) ) {
				$custom_form_data = $this->qre_get_quiz_form_data( $qre_quiz_id ); // gets form data of a quiz.
			} else {
				$custom_form_data = array();
			}

			return $custom_form_data;
		}

		/**
		 * To get form questions data of custom field by quiz id
		 *
		 * @param number $quiz_id quiz id.
		 * @return array $custom_form_data custom form field questions
		 */
		public function qre_get_quiz_form_data( $quiz_id ) {
			$custom_form_data = array();
			if ( ! empty( $quiz_id ) ) {

				if ( ! empty( $quiz_id ) ) {
					$custom_form_data = Quiz_Export_Db::instance()->get_custom_field_form_questions( $quiz_id );
				}
			}

			return $custom_form_data;
		}


		/**
		 * Returns custom field's answers
		 *
		 * @param  integer $ref_id  statistics ref id.
		 * @param  integer $quiz_id quiz id.
		 * @param  integer $user_id user id of user.
		 * @return array   answers of user
		 */
		public function custom_quiz_answers( $ref_id, $quiz_id, $user_id ) {
			$custom_answers = array();
			if ( ! empty( $ref_id ) && ! empty( $quiz_id ) ) {
				// User ID check condition removed to get stat of anonymous users.
				$custom_answers = Quiz_Export_Db::instance()->get_custom_field_form_answers( $ref_id, $quiz_id, $user_id );
			}

			return $custom_answers;
		}

		/**
		 * This method is used for some data processing.
		 *
		 * @param integer $qre_ref_id Ref ID.
		 * @param array   $res_value Res Value.
		 * @param integer $qre_quiz_id Quiz ID.
		 * @param array   $custom_answers Custom Form Answers.
		 * @return array $arr_process_data[$qre_ref_id]
		 */
		public function generate_custom_process_data_array( $qre_ref_id, $res_value, $qre_quiz_id, $custom_answers ) {
			$arr_custom_process = array();
			$arr_custom_data    = array();

			$arr_custom_data[ $qre_ref_id ] = array();

			$custom_form_data = $this->get_custom_form_data( $qre_quiz_id );

			foreach ( $custom_form_data as $cust_question ) {
				$arr_custom_process['statistic_ref_id'] = $qre_ref_id;
				$arr_custom_process['quiz_id']          = $qre_quiz_id;
				$arr_custom_process['user_id']          = $res_value['user_id'];
				$arr_custom_process['question']         = $cust_question['fieldname'];
				$arr_custom_process['points']           = '';
				$arr_custom_process['answer_type']      = $cust_question['type'];
				$arr_custom_process['answer_data']      = $cust_question['data'];
				$arr_custom_process['col_sort']         = '';
				$arr_custom_process['qspoints']         = '';

				$form_id = $cust_question['form_id']; // id of custom question.

				$arr_custom_process['qsanswer_data'] = check_isset( $custom_answers, $form_id );
				$arr_custom_process['question_time'] = '';

				array_push( $arr_custom_data[ $qre_ref_id ], $arr_custom_process );
			}

			return $arr_custom_data[ $qre_ref_id ];
		}

		/**
		 * This method handles all result data of quiz
		 *
		 * @param array $res_value Result Value.
		 * @param array $result Result.
		 * @param array $arr_data Data.
		 * @param array $arr_process_data Processed Data.
		 * @since     1.0.0
		 */
		public function get_quiz_data( $res_value, $result, $arr_data, $arr_process_data ) {
			$main_cnt = 0; // index counter of main data array.

			foreach ( $arr_process_data as $res_key => $res_value ) {
				$res_key                                    = $res_key;
				$qre_user_id                                = check_isset( $res_value[0], 'user_id' );
				$qre_quiz_id                                = check_isset( $res_value[0], 'quiz_id' );
				$arr_data[ $main_cnt ]['user_id']           = $qre_user_id;
				$arr_data[ $main_cnt ]['user_login_name']   = '';
				$arr_data[ $main_cnt ]['total_points']      = 0;
				$arr_data[ $main_cnt ]['tot_points_scored'] = 0;
				$arr_data[ $main_cnt ]['tot_time_taken']    = 0;

				if ( ! empty( $qre_user_id ) ) {
					// to get user name.
					$qre_userdata = get_userdata( $qre_user_id );

					$arr_data[ $main_cnt ]['user_login_name'] = $this->set_username( $qre_userdata );
				}
				$arr_data['quiz_title'] = '';
				$arr_data['quiz_title'] = $this->get_quiz_title( $qre_quiz_id, $arr_data['quiz_title'] );

				$cnt = 0;

				if ( ! empty( $res_value ) ) {
					foreach ( $res_value as $key => $val ) {
						$result[ $key ]['answer_data'] = maybe_unserialize( $val['answer_data'] );

						$qre_answer_data = maybe_unserialize( $val['answer_data'] );

						$question_type = $val['answer_type'];

						$cur_user_id = $val['user_id'];

						$qre_user_response = json_decode( $val['qsanswer_data'], 1 );

						$qre_question_id = $val['question_id'];

						$arr_user_response   = array();
						$arr_answers         = array();
						$arr_correct_answers = array();

						$switch_true        = false; // if switch statement is true then make it TRUE.
						$is_attach_question = false; // if want to attache answer to question. For cloze questions.
						$qre_answer         = '';

						switch ( $question_type ) {
							// if $question_type is single OR multiple.
							case 'single':
							case 'multiple':
								$switch_true         = true;
								$res_object          = new Qre_Single_Question_Data( $qre_answer_data, $qre_user_response );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								break;

							case 'free_answer':
								$switch_true         = true;
								$res_object          = new Qre_Free_Question_Data( $qre_answer_data, $qre_user_response );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								break;

							case 'sort_answer':
								$switch_true         = true;
								$res_object          = new Qre_Sort_Question_Data( $qre_answer_data, $qre_question_id, $qre_user_response, $cur_user_id );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								break;

							case 'matrix_sort_answer':
								$switch_true         = true;
								$res_object          = new Qre_Matrix_Sort_Question_Data( $qre_answer_data, $qre_question_id, $qre_user_response, $cur_user_id );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								break;

							case 'cloze_answer':
								$switch_true         = true;
								$is_attach_question  = true;
								$res_object          = new Qre_Cloze_Question_Data( $qre_answer_data, $result[ $key ], $qre_user_response );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								$qre_answer          = $res_object->get_answer_obj();
								break;

							case 'assessment_answer':
								$switch_true         = true;
								$res_object          = new Qre_Assessment_Question_Data( $qre_answer_data, $qre_user_response );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								$qre_answer          = $res_object->get_answer_obj();
								break;

							case 'essay':
								$switch_true       = true;
								$res_object        = new Qre_Essay_Question_Data( $qre_user_response );
								$arr_user_response = $res_object->get_user_answers();
								break;
						}

						if ( $switch_true ) {
							$arr_data = $this->set_data_in_array( $is_attach_question, $qre_answer, $arr_data, $main_cnt, $val, $cnt, $arr_answers, $arr_correct_answers, $arr_user_response );

							$cnt++;
						}
					}
				}

				$main_cnt++;
			}

			return $arr_data;
		}

		/**
		 * Check whether user is exists and returns username
		 *
		 * @param object $qre_userdata User Data object.
		 * @since     2.0.0
		 */
		public function set_username( $qre_userdata ) {
			if ( false !== $qre_userdata ) {
				/**
				 *
				 * User's name displayed in the files.
				 *
				 * Returns user's name displayed in the exported CSV/XLSX files.
				 *
				 * @since 3.0.0
				 *
				 * @param string $user_login    Username.
				 * @param object $qre_userdata  WP_User object.
				 */
				$username = apply_filters( 'qre_username_displayed', $qre_userdata->user_login, $qre_userdata );
			} else {
				$username = __( 'Deleted User', 'learndash-reports-pro' );
			}

			return $username;
		}

		/**
		 * Returns a quiz title of quiz
		 *
		 * @since 2.0.0
		 *
		 * @param  int    $quiz_id Quiz ID.
		 * @param  string $quiz_title Quiz Title.
		 * @return string MD5 Checksum
		 */
		public function get_quiz_title( $quiz_id, $quiz_title ) {
			if ( ! empty( $quiz_id ) && '' === $quiz_title ) {
				// to get quiz title.
				$quiz_name  = Quiz_Export_Db::instance()->get_quiz_title( $quiz_id );
				$quiz_title = $quiz_name;

				return $quiz_title;
			}

			return '';
		}

		/**
		 * Final data to put in files.
		 *
		 * @param boolean $is_attach_question Is attach question.
		 * @param string  $qre_answer          Answer.
		 * @param array   $arr_data            Quiz data.
		 * @param int     $main_cnt            Main loop counter.
		 * @param array   $val                 Question.
		 * @param int     $cnt                 Question Count.
		 * @param array   $arr_answers         All Answers array.
		 * @param array   $arr_correct_answers Correct Answers array.
		 * @param array   $arr_user_response   User's answers array.
		 */
		public function set_data_in_array( $is_attach_question, $qre_answer, $arr_data, $main_cnt, $val, $cnt, $arr_answers, $arr_correct_answers, $arr_user_response ) {
			// . adding quiz title for each element in arr_data
			$arr_data[ $main_cnt ]['quiz_title'] = $arr_data['quiz_title'];

			$arr_data[ $main_cnt ]['total_points']      = $arr_data[ $main_cnt ]['total_points'] + $val['points'];
			$arr_data[ $main_cnt ]['tot_points_scored'] = $arr_data[ $main_cnt ]['tot_points_scored'] + $val['qspoints'];
			$arr_data[ $main_cnt ]['tot_time_taken']    = $arr_data[ $main_cnt ]['tot_time_taken'] + $val['question_time'];

			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['question'] = $val['question'];
			if ( $is_attach_question && '' !== $qre_answer ) {
				// to attache answer in question.
				$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['question'] .= '  ' . $qre_answer;
			}

			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['points'] = $val['points'];

			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['points_scored'] = $val['qspoints'];

			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['time_taken'] = $val['question_time'];

			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['answers']         = $arr_answers;
			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['correct_answers'] = $arr_correct_answers;
			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['user_response']   = $arr_user_response;

			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['question_type'] = $val['answer_type'];

			$arr_data[ $main_cnt ]['ref_id'] = $val['statistic_ref_id']; // added for the use of custom fields.

			$arr_data[ $main_cnt ]['question_meta'][ $cnt ]['question_id'] = $val['question_id'];

			return $arr_data;
		}

		/**
		 * Generate_custom_data_array
		 *
		 * @param  array $arr_custom_data Custom data related to quiz.
		 **/
		public function generate_custom_data_array( $arr_custom_data ) {
			foreach ( $arr_custom_data as $cust_key => $cust_val ) {
				foreach ( $cust_val as $qre_cust_key => $qre_cust_value ) {
					if ( ! is_array( $qre_cust_value ) ) {
						continue;
					}

					$cust_question_type = $qre_cust_value['answer_type'];

					switch ( $cust_question_type ) {
						case '0': // text.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type'] = 'text';
							break;

						case '1': // textarea.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type'] = 'textarea';
							break;

						case '2': // number.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type'] = 'number';
							break;

						case '3': // checkbox.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type']   = 'checkbox';
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['qsanswer_data'] = ( '' !== $qre_cust_value['qsanswer_data'] ) ? ( ( '1' == $qre_cust_value['qsanswer_data'] ) ? __( 'Yes', 'learndash-reports-pro' ) : __( 'No', 'learndash-reports-pro' ) ) : '';
							break;

						case '4': // email.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type'] = 'email';
							break;

						case '5': // yes/no.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type']   = 'yes/no';
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['qsanswer_data'] = ( '' !== $qre_cust_value['qsanswer_data'] ) ? ( ( '1' == $qre_cust_value['qsanswer_data'] ) ? __( 'Yes', 'learndash-reports-pro' ) : __( 'No', 'learndash-reports-pro' ) ) : '';
							break;

						case '6': // date.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type'] = 'date';
							break;

						case '7': // dropdown menu.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type'] = 'dropdown';

							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_data'] = json_decode( $qre_cust_value['answer_data'] );

							break;

						case '8': // radio buttons.
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_type'] = 'radio';
							$arr_custom_data[ $cust_key ][ $qre_cust_key ]['answer_data'] = json_decode( $qre_cust_value['answer_data'] );
							break;

						default:
							break;
					}
				}
			}

			return $arr_custom_data;
		}

		/**
		 * This method verifies request for export all or for specific user or quiz and returns login name or quiz title
		 *
		 * @param array  $arr_data Quiz Data.
		 * @param string $key Key.
		 * @return string $return_name Login name of user or quiz title depending on $key.
		 **/
		public function get_login_name( $arr_data, $key = 'user_login_name' ) {
			if ( ! empty( $arr_data ) ) {
				$all_values = array_column( $arr_data, $key );
				if ( count( array_unique( $all_values ) ) > 1 ) {
					// if all records are for same user.
					$return_name = 'all';
				} else {
					$return_name = $arr_data[0][ $key ];
				}

				return $return_name;
			}
		}

		// . shamali -
		/**
		 * Filter quizzes data by checking if their corresponding statistics data is deleted or not.
		 *
		 * @author Shamali
		 * @param  array $quizzes_data Quizzes data.
		 * @return array $quizzes_data Quizzes Filtered data.
		 */
		public function filter_quizzes_data( $quizzes_data ) {
			if ( empty( $quizzes_data ) ) {
				return $quizzes_data;
			}

			// . check for permanently deleted quizzes.
			$quiz_ids = implode( ',', array_unique( array_column( $quizzes_data, 'quiz' ) ) );

			$quiz_ids_present = Quiz_Export_Db::instance()->check_if_quiz_ids_actually_present( $quiz_ids );

			// . check for deleted/ reset quiz statistics.
			$statistics_ids = implode( ',', array_filter( array_column( $quizzes_data, 'statistic_ref_id' ) ) );

			$ids_present = Quiz_Export_Db::instance()->check_if_statistics_actually_present( $statistics_ids );

			if ( empty( $quiz_ids_present ) || empty( $ids_present ) ) {
				return array();
			}

			$quizzes_data = array_filter(
				$quizzes_data,
				function ( $data ) use ( $quiz_ids_present, $ids_present ) {
					if ( in_array( $data['quiz'], $quiz_ids_present ) && in_array( $data['statistic_ref_id'], $ids_present ) ) {
						return true;
					}
					return false;
				}
			);

			return $quizzes_data;
		}

		/**
		 * This method is used to remove quizzes that are not associated to the group courses
		 *
		 * @param  array   $quizzes         Quizzes array.
		 * @param  array   $qre_group_courses     Group Courses.
		 * @param  integer $user_id         User ID.
		 * @return array   $sorted_quizzes  Filtered Quizzes array.
		 */
		public function sort_quiz( $quizzes, $qre_group_courses, $user_id ) {
			$sorted_quizzes = array();
			if ( current_user_can( 'group_leader' ) && get_current_user_id() !== $user_id ) {
				foreach ( $quizzes as $quiz_value ) {
					$course_id = learndash_get_course_id( $quiz_value['quiz'] );
					if ( 0 === $course_id || false === $course_id ) {
						$course_id = $quiz_value['course'];
					}
					if ( ! empty( $course_id ) && in_array( $course_id, $qre_group_courses, true ) && ! empty( $qre_group_courses ) ) {
						$sorted_quizzes[] = $quiz_value;
					}
				}
			} else {
				return $quizzes;
			}
			return $sorted_quizzes;
		}

		/**
		 * This function is used to get the statistics of the quiz for particular user.
		 *
		 * @param  integer $quiz_id Quiz ID.
		 * @param  integer $user_id User ID.
		 * @param  integer $ref_id  Statistics reference ID.
		 * @param  integer $avg     Average of Quiz.
		 * @return array   $results Output of questions.
		 */
		public function get_statistics_data( $quiz_id, $user_id, $ref_id, $avg ) {
			$ref_id_user_id        = $avg ? $user_id : $ref_id;
			$statistic_user_mapper = new \WpProQuiz_Model_StatisticUserMapper();
			$statistic_users       = $statistic_user_mapper->fetchUserStatistic( $ref_id_user_id, $quiz_id, $avg );
			$results               = $this->get_statistics( $statistic_users, $user_id );
			return $results;
		}

		/**
		 * This method is used to get statistics details.
		 *
		 * @param  integer $statistic_users User Statistics.
		 * @param  integer $user_id         User ID.
		 * @return array   $results         Questions Data.
		 */
		public function get_statistics( $statistic_users, $user_id ) {
			$results = array();
			foreach ( $statistic_users as $statistic ) {
				if ( ! isset( $results[ $statistic->getCategoryId() ] ) ) {
					$results[ $statistic->getCategoryId() ] = array(
						'questions'    => array(),
						'categoryId'   => $statistic->getCategoryId(),
						'categoryName' => $statistic->getCategoryId() ? $statistic->getCategoryName() : __( 'No category', 'learndash-reports-pro' ),
					);
				}
				$result_str          = &$results[ $statistic->getCategoryId() ];
				$question_pro_mapper = new WpProQuiz_Model_QuestionMapper();
				$question_pro        = $question_pro_mapper->fetch( $statistic->getQuestionId() );

				$question_item = array(
					'question_id'        => $statistic->getQuestionId(),
					'correct'            => $statistic->getCorrectCount(),
					'incorrect'          => $statistic->getIncorrectCount(),
					'hintCount'          => $statistic->getIncorrectCount(),
					'time'               => $statistic->getQuestionTime(),
					'points'             => $statistic->getPoints(),
					'gPoints'            => $statistic->getGPoints(),
					'statistcAnswerData' => $statistic->getStatisticAnswerData(),
					'questionName'       => $statistic->getQuestionName(),
					'questionAnswerData' => $statistic->getQuestionAnswerData(),
					'answerType'         => $statistic->getAnswerType(),
					'questionModel'      => $question_pro,
				);
				// For the sort_answer items. This worked correctly with LD 2.0.6.8. But in 2.1.x there was a change where
				// the stored value for 'statistcAnswerData' was not simply keys to match 'questionAnswerData' but md5 value.
				// This causes a mis-match when viewing statistics data. To complicate things we will have a mix of LD 2.0.6.8
				// quiz values and 2.1.x quiz values.
				if ( ( 'sort_answer' === $question_item['answerType'] ) || ( 'matrix_sort_answer' === $question_item['answerType'] ) ) {
					if ( ( isset( $question_item['questionAnswerData'] ) ) &&
						( ! empty( $question_item['questionAnswerData'] ) ) &&
						( isset( $question_item['statistcAnswerData'] ) ) &&
						( ! empty( $question_item['statistcAnswerData'] ) )
					) {
						// So first we check the value of the first item from 'statistcAnswerData'. If the value
						// is a simple int then we can move on. If not, then we have some work to do.
						$statistic_answer_data_item = $question_item['statistcAnswerData'][0];
						if ( ( -1 === $statistic_answer_data_item ) ||
							( strcmp( $statistic_answer_data_item, intval( $statistic_answer_data_item ) ) !== 0 )
						) {
							$question_id = $statistic->getQuestionId();
							// Next we loop over the 'questionAnswerData' items.
							foreach ( $question_item['questionAnswerData'] as $q_k => $q_v ) {
								// Take the item key and encode it.
								$datapos = md5( $user_id . $question_id . intval( $q_k ) );
								$s_pos   = array_search( $datapos, $question_item['statistcAnswerData'], true );

								if ( false !== $s_pos ) {
									$question_item['statistcAnswerData'][ $s_pos ] = intval( $q_k );
								}
							}
						}
					}
				}
				$result_str['questions'][] = $question_item;
			}
			return $results;
		}

		/**
		 * This function is used to get the question text.
		 *
		 * @param  integer $pro_quiz_id  Pro Quiz ID.
		 * @param  integer $question_id  Question ID.
		 * @return string Question Text.
		 */
		public function get_question_text( $pro_quiz_id, $question_id ) {
			$question_mapper = new \WpProQuiz_Model_QuestionMapper();
			$questions       = $question_mapper->fetchAll( $pro_quiz_id );
			foreach ( $questions as $ques ) {
				if ( get_protected_value( $ques, '_id' ) == $question_id ) {
					return get_protected_value( $ques, '_question' );
				}
			}
		}

		/**
		 * Method to get statistics based on fitler parameters.
		 *
		 * @param  string  $query_type     Type of resource(user or quiz).
		 * @param  integer $queried_obj_id Resource ID.
		 * @param  string  $queried_string Search String.
		 * @param  string  $date_filter    Type of time filter.
		 * @param  string  $time_period    Relative time duration.
		 * @param  string  $from_date      From Date.
		 * @param  string  $to_date        To Date.
		 * @param  integer $limit          Stats per page.
		 * @param  integer $page           Page Number.
		 *
		 * @return array   $statistics     An array of statistic IDs and their attempt time.
		 */
		public function get_filtered_statistics( $query_type, $queried_obj_id, $queried_string, $date_filter, $time_period, $from_date, $to_date, $limit = 10, $page = 1 ) {
			// -----------------------------
			// VARIABLE DECLERATION
			// -----------------------------
			$from_timestamp = false;
			$to_timestamp   = false;
			if ( ! empty( $date_filter ) && 'on' === $date_filter ) {
				if ( ! empty( $from_date ) ) {
					$from_timestamp = strtotime( $from_date );
				}
				if ( ! empty( $to_date ) ) {
					$to_timestamp = strtotime( $to_date );
				}
			} else {
				if ( ! empty( $time_period ) ) {
					$from_timestamp = strtotime( "-1 {$time_period}" );
				}
			}
			if ( empty( $limit ) ) {
				$limit = 10;
			}
			if ( empty( $page ) ) {
				$page = 1;
			}
			// -------------------------------
			// FILTER 1
			// -------------------------------
			if ( ! empty( $queried_obj_id ) && ! empty( $query_type ) ) {
				$resource_accessible = qre_check_if_accessible( $query_type, $queried_obj_id );
				if ( is_wp_error( $resource_accessible ) ) {
					$error = new WP_Error( 403, __( 'You do not have sufficient privileges to view this information.', 'learndash-reports-pro' ) );
					return $error;
				}
				if ( ! $resource_accessible ) {
					$error = new WP_Error( 403, __( 'You do not have sufficient privileges to view this information.', 'learndash-reports-pro' ) );
					return $error;
				}
				switch ( $query_type ) {
					case 'post':
						if ( 'sfwd-quiz' === get_post_type( $queried_obj_id ) ) {
							// Show Statistics by Quiz.
							$quiz_pro_id      = get_post_meta( $queried_obj_id, 'quiz_pro_id', true );
							$statistics       = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_by_quiz(
								$quiz_pro_id,
								array(
									'from'  => $from_timestamp,
									'to'    => $to_timestamp,
									'limit' => $limit,
									'page'  => $page,
								)
							);
							$statistics_count = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_count(
								array(
									'quiz_ids' => array( $quiz_pro_id ),
									'from'     => $from_timestamp,
									'to'       => $to_timestamp,
								)
							);
						} else {
							$quizzes          = learndash_course_get_children_of_step( $queried_obj_id, $queried_obj_id, 'sfwd-quiz', 'ids', true );
							$quiz_pro_ids     = array_map(
								function( $quiz_id ) {
									return get_post_meta( $quiz_id, 'quiz_pro_id', true );
								},
								$quizzes
							);
							$statistics       = Quiz_Export_Db::instance()->get_all_statistic_ref_ids(
								array(
									'quiz_ids' => $quiz_pro_ids,
									'from'     => $from_timestamp,
									'to'       => $to_timestamp,
									'limit'    => $limit,
									'page'     => $page,
								)
							);
							$statistics_count = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_count(
								array(
									'quiz_ids' => $quiz_pro_ids,
									'from'     => $from_timestamp,
									'to'       => $to_timestamp,
								)
							);
						}
						break;
					case 'user':
						// Show Statistics by User.
						$statistics       = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_by_user(
							$queried_obj_id,
							array(
								'from'  => $from_timestamp,
								'to'    => $to_timestamp,
								'limit' => $limit,
								'page'  => $page,
							)
						);
						$statistics_count = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_count(
							array(
								'user_ids' => array( $queried_obj_id ),
								'from'     => $from_timestamp,
								'to'       => $to_timestamp,
							)
						);
						break;
					default:
						break;
				}
			} elseif ( ! empty( $queried_string ) ) {
				// --------------------------------
				// FILTER 2
				// Work with Query String here.
				// --------------------------------
				$quizzes = qre_search_quizzes( $queried_string );
				if ( current_user_can( 'group_leader' ) ) {
					$quiz_pro_ids = array_map(
						function( $quiz ) {
							$quiz_id = $quiz;
							if ( is_array( $quiz ) ) {
								$quiz_id = $quiz['ID'];
							}
							return get_post_meta( $quiz_id, 'quiz_pro_id', true );
						},
						$quizzes
					);
					$user_ids     = learndash_get_groups_administrators_users( get_current_user_id() );
					$user_ids[]   = get_current_user_id();
				} else {
					$quiz_pro_ids = array_map(
						function( $quiz ) {
							$quiz_id = $quiz['ID'];
							return get_post_meta( $quiz_id, 'quiz_pro_id', true );
						},
						$quizzes
					);
					$users        = qre_search_users( $queried_string );
					$user_ids     = array_map(
						function( $user ) {
							return $user['ID'];
						},
						$users
					);
				}
				if ( ! empty( $quiz_pro_ids ) || ! empty( $user_ids ) ) {
					if ( current_user_can( 'group_leader' ) ) {
						$statistics = Quiz_Export_Db::instance()->get_all_statistic_ref_ids(
							array(
								'quiz_ids' => $quiz_pro_ids,
								'user_ids' => $user_ids,
								'from'     => $from_timestamp,
								'to'       => $to_timestamp,
								'limit'    => $limit,
								'page'     => $page,
							// 'exclusive' => true,
							)
						);
						$statistics_count = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_count(
							array(
								'quiz_ids' => $quiz_pro_ids,
								'user_ids' => $user_ids,
								'from'     => $from_timestamp,
								'to'       => $to_timestamp,
							)
						);
						// if ( empty( $quizzes ) || empty( $user_ids ) ) {
							$statistics['total_count'] = 0;
							// return $statistics;
						// }
					} else {
						if ( current_user_can( 'subscriber' ) ) {
							$quizzes    = qre_search_quizzes( '' );
							$user_ids[] = get_current_user_id();
						}
						$statistics       = Quiz_Export_Db::instance()->get_all_statistic_ref_ids(
							array(
								'quiz_ids'  => $quiz_pro_ids,
								'user_ids'  => $user_ids,
								'from'      => $from_timestamp,
								'to'        => $to_timestamp,
								'limit'     => $limit,
								'page'      => $page,
								'exclusive' => true,
							)
						);
						$statistics_count = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_count(
							array(
								'quiz_ids' => $quiz_pro_ids,
								'user_ids' => $user_ids,
								'from'     => $from_timestamp,
								'to'       => $to_timestamp,
								// 'exclusive' => true,
							)
						);

					}
				} else {
					$statistics       = array();
					$statistics_count = 0;
				}
			} else {
				// ---------------------------------
				// FILTER 3
				// ---------------------------------
				$user_ids = array();
				$quizzes  = array();
				// ------------------------
				// SUBSCRIBER CHECK
				// ------------------------
				if ( current_user_can( 'subscriber' ) ) {
					$quizzes    = qre_search_quizzes( '' );
					$user_ids[] = get_current_user_id();
				}
				// ------------------------
				// INSTRUCTOR CHECK
				// ------------------------
				if ( function_exists( 'ir_get_instructor_complete_course_list' ) && wdm_is_instructor() ) {
					$instructor_course_ids = ir_get_instructor_complete_course_list( get_current_user_id() );
					$courses_enrolled      = learndash_user_get_enrolled_courses( get_current_user_id() );
					$courses               = array_unique( array_merge( $instructor_course_ids, $courses_enrolled ) );
					$user_ids              = array( get_current_user_id() ); // initialize with instructor id to show instructor statistics.
					if ( ! empty( $instructor_course_ids ) ) {
						foreach ( $instructor_course_ids as $course_id ) {
							$course_users = learndash_get_users_for_course( $course_id );
							if ( $course_users instanceof WP_User_Query ) {
								$course_users = $course_users->query_vars['include'];
							}
							$user_ids = array_merge( $user_ids, $course_users );
							$quizzes  = array_merge( $quizzes, learndash_course_get_steps_by_type( $course_id, 'sfwd-quiz' ) );
						}
						$user_ids = array_unique( $user_ids );
					}
					$quizzes = array_unique( $quizzes );
					if ( empty( $quizzes ) && empty( $user_ids ) ) {
						$statistics['total_count'] = 0;
						return $statistics;
					}
				}

				// --------------------------------
				// GROUP LEADER CHECK
				// --------------------------------
				if ( ! empty( qre_get_user_managed_group_courses() ) ) {
					$user_ids         = learndash_get_groups_administrators_users( get_current_user_id() );
					$user_ids[]       = get_current_user_id();
					$courses          = qre_get_user_managed_group_courses();
					$courses_enrolled = learndash_user_get_enrolled_courses( get_current_user_id() );
					$courses          = array_unique( array_merge( $courses, $courses_enrolled ) );
					foreach ( $courses as $course_id ) {
						$quizzes = array_merge( $quizzes, learndash_course_get_steps_by_type( $course_id, 'sfwd-quiz' ) );
					}
					$quizzes = array_unique( $quizzes );
					if ( empty( $quizzes ) && empty( $user_ids ) ) {
						$statistics['total_count'] = 0;
						return $statistics;
					}
				}

				// ------------------------------
				// DEFAULT PROCESS
				// -------------------------------
				$quiz_pro_ids = array_map(
					function( $quiz ) {
						$quiz_id = $quiz;
						if ( is_array( $quiz ) ) {
							$quiz_id = $quiz['ID'];
						}
						return get_post_meta( $quiz_id, 'quiz_pro_id', true );
					},
					$quizzes
				);
				$statistics   = Quiz_Export_Db::instance()->get_all_statistic_ref_ids(
					array(
						'quiz_ids' => $quiz_pro_ids,
						'user_ids' => $user_ids,
						'from'     => $from_timestamp,
						'to'       => $to_timestamp,
						'limit'    => $limit,
						'page'     => $page,
					)
				);
				// Count check
				if ( current_user_can( 'administrator' ) ) {
					// Default count check
					$statistics_count = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_count(
						array(
							'quiz_ids' => $quiz_pro_ids,
							'user_ids' => $user_ids,
							'from'     => $from_timestamp,
							'to'       => $to_timestamp,
						)
					);
				} elseif ( empty( $quizzes ) && empty( $user_ids ) && ! empty( qre_get_user_managed_group_courses() ) ) {
					// Group leader null count check
					$statistics_count = 0;
				} elseif ( empty( $quizzes ) && empty( $user_ids ) && function_exists( 'wdm_is_instructor' ) && wdm_is_instructor() ) {
					// Instructor null count check
					$statistics_count = 0;
				} elseif ( empty( $quizzes ) && empty( $user_ids ) ) {
					// Default null count check
					$statistics_count = 0;
				} else {
					// Default count check
					$statistics_count = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_count(
						array(
							'quiz_ids' => $quiz_pro_ids,
							'user_ids' => $user_ids,
							'from'     => $from_timestamp,
							'to'       => $to_timestamp,
						)
					);
				}
			}

			// -------------------------
			// FILAN RETURN
			// -------------------------
			$statistics['total_count'] = $statistics_count;
			return $statistics;
		}

		/**
		 * This method is used to calculate class average score for a quiz.
		 *
		 * @param  integer $quiz_id Quiz Pro ID.
		 *
		 * @return integer Class Average in percentage.
		 */
		public function get_quiz_class_average( $quiz_id ) {
			// $class_points = Quiz_Export_Db::instance()->get_users_total_points( $quiz_id );
			// if ( empty( $class_points ) ) {
			// return 0;
			// }

			// $points  = 0;
			// $gpoints = 0;
			// foreach ( $class_points as $userdata ) {
			// $points  += (int) $userdata['points'];
			// $gpoints += (int) $userdata['gpoints'];
			// }
			$statistic_controller = new \WpProQuiz_Controller_Statistics();
			$avg_result           = $statistic_controller->getAverageResult( $quiz_id );
			if ( 0 == $avg_result ) {// phpcs:ignore
				return (int) $avg_result;
			}
			return number_format( $avg_result, 2 );
		}

		public function get_group_users_average( $quiz_id ) {
			global $wpdb;
			$statistic_ref_table = \LDLMS_DB::get_table_name( 'quiz_statistic_ref', 'wpproquiz' );
			$statistic_table     = \LDLMS_DB::get_table_name( 'quiz_statistic', 'wpproquiz' );
			$question_table      = \LDLMS_DB::get_table_name( 'quiz_question', 'wpproquiz' );
			$groups_managed      = \WRLD_Common_Functions::get_managed_group_ids( get_current_user_id() );
			$results             = array();

			foreach ( $groups_managed as $group_id ) {
				$accessible_users = \WRLD_Common_Functions::get_users_enrolled_in_groups( array( $group_id ) );
				$group_courses    = \WRLD_Common_Functions::get_list_of_courses_in_groups( array( $group_id ) );
				if ( empty( $accessible_users ) ) {
					$accessible_users = array( -1 );
				}
				$accessible_users = implode( ',', $accessible_users );
				if ( empty( $group_courses ) ) {
					$group_courses = array( -1 );
				}
				$group_courses = implode( ',', $group_courses );
				$result        = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT
							SUM(s.points) AS points,
							SUM(q.points * (s.correct_count + s.incorrect_count)) AS g_points
						FROM
							{$statistic_ref_table} AS sf
							INNER JOIN {$statistic_table} AS s ON ( s.statistic_ref_id = sf.statistic_ref_id )
							INNER JOIN {$question_table} AS q ON ( q.id = s.question_id )
						WHERE
							sf.quiz_id = %d AND sf.user_id IN ({$accessible_users}) AND sf.course_post_id IN ({$group_courses})",
						$quiz_id
					),
					ARRAY_A
				);
				if ( isset( $result['g_points'] ) && $result['g_points'] ) {
					$avg_result           = round( 100 * $result['points'] / $result['g_points'], 2 );
					$results[ $group_id ] = number_format( $avg_result, 2 );
					continue;
				}
				$results[ $group_id ] = 0;
			}
			return $results;
		}

		/**
		 * This function is used to get the question details like answer options,description etc.
		 *
		 * @param  integer $que_id        Question ID.
		 * @param  integer $pro_quiz_id  Pro Quiz ID.
		 * @return string  $cmg          Correct message.
		 */
		public function get_correct_message( $que_id, $pro_quiz_id ) {
			$view            = new \WpProQuiz_View_FrontQuiz();
			$quiz_mapper     = new \WpProQuiz_Model_QuizMapper();
			$question_mapper = new \WpProQuiz_Model_QuestionMapper();
			$question        = $question_mapper->fetchAll( $pro_quiz_id );
			$category_mapper = new \WpProQuiz_Model_CategoryMapper();
			$form_mapper     = new \WpProQuiz_Model_FormMapper();
			$quiz            = $quiz_mapper->fetch( $pro_quiz_id );
			if ( $quiz->isShowMaxQuestion() && $quiz->getShowMaxQuestionValue() > 0 ) {
				$value = $quiz->getShowMaxQuestionValue();
				if ( $quiz->isShowMaxQuestionPercent() ) {
					$count = $question_mapper->count( $pro_quiz_id );
					$value = ceil( $count * $value / 100 );
				}
				$question = $question_mapper->fetchAll( $pro_quiz_id, true, $value );
			} else {
				$question = $question_mapper->fetchAll( $pro_quiz_id );
			}
			$view->quiz     = $quiz;
			$view->question = $question;
			$view->category = $category_mapper->fetchByQuiz( $quiz->getId() );
			$view->forms    = $form_mapper->fetch( $quiz->getId() );
			foreach ( $view->question as $que ) {
				$qid = get_protected_value( $que, '_id' );
				if ( $qid == $que_id ) {
					$cmsg = get_protected_value( $que, '_correctMsg' );
					return $cmsg;
				}
			}
		}
	}
}
