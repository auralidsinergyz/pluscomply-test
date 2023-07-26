<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Qre_Sort_Question_Data' ) ) {
	/**
	 * Qre_Sort_Question_Data Class.
	 *
	 * @class Qre_Sort_Question_Data
	 */
	class Qre_Sort_Question_Data extends Qre_Question_Data {
		/**
		 * Constuctor.
		 *
		 * @param array   $answer_data Answer data.
		 * @param integer $question_id Question ID.
		 * @param array   $user_response Array of user response for a single question.
		 * @param integer $current_user_id Current User ID.
		 */
		public function __construct( $answer_data, $question_id, $user_response, $current_user_id ) {
			$this->user_response = $user_response;
			$this->process_data( $answer_data, $question_id, $current_user_id );
		}

		/**
		 * Processes recieved raw response and answer data and assign to parent vars.
		 *
		 * @param array   $answer_data Answer data.
		 * @param integer $question_id Question ID.
		 * @param integer $current_user_id Current User ID.
		 *
		 * @return void Nothing.
		 */
		protected function process_data( $answer_data, $question_id, $current_user_id ) {
			$user_response = $this->user_response;
			$arr_answers   = array();

			$ans_cnt             = 0;
			$arr_correct_answers = array();

			foreach ( $answer_data as $ans_obj ) {
				$ans_obj_answer          = get_protected_value( $ans_obj, '_answer' );
				$arr_answers[ $ans_cnt ] = $ans_obj_answer;

				array_push( $arr_correct_answers, $ans_obj_answer );

				$ans_cnt++;
			}

			$arr_user_response = array();

			foreach ( $arr_answers as $ans_key => $ans_val ) {
				$ans_val = $ans_val;
				$md5     = $this->datapos( $current_user_id, $question_id, $ans_key );
				if ( empty( $user_response ) ) {
					$res_key = false;
				} else {
					$res_key = array_search( $md5, $user_response, true );
				}

				if ( false !== $res_key && isset( $arr_answers[ $res_key ] ) ) {
					$arr_user_response[ $ans_key ] = $arr_answers[ $res_key ];
				}
			}
			$this->set_all_answers( $arr_answers );
			$this->set_correct_answers( $arr_correct_answers );
			$this->set_user_answers( $arr_user_response );
		}
	}
}
