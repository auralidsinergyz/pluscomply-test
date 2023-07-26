<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Qre_Single_Question_Data' ) ) {
	/**
	 * Qre_Single_Question_Data Class.
	 *
	 * @class Qre_Single_Question_Data
	 */
	class Qre_Single_Question_Data extends Qre_Question_Data {
		/**
		 * Constuctor.
		 *
		 * @param array $answer_data Answer data.
		 * @param array $user_response Array of user response for a single question.
		 */
		public function __construct( $answer_data, $user_response ) {
			$this->user_response = $user_response;
			$this->process_data( $answer_data );
		}

		/**
		 * Processes recieved raw response and answer data and assign to parent vars.
		 *
		 * @param array $answer_data Answer data.
		 *
		 * @return void Nothing.
		 */
		protected function process_data( $answer_data ) {
			$user_response = $this->user_response;

			$ans_cnt = 0;

			$arr_user_response   = array();
			$arr_answers         = array();
			$arr_correct_answers = array();
			foreach ( $answer_data as $ans_obj ) {
				$ans_obj_answer = get_protected_value( $ans_obj, '_answer' );

				$arr_answers[ $ans_cnt ] = $ans_obj_answer;
				$ans_obj_correct         = get_protected_value( $ans_obj, '_correct' );

				if ( 1 == $ans_obj_correct ) {
					// if correct answer, makes entry in $arr_correct_answers array.
					array_push( $arr_correct_answers, $ans_obj_answer );
				}

				if ( isset( $user_response[ $ans_cnt ] ) && 1 == $user_response[ $ans_cnt ] ) {
					// if user has selected answer, '0' if not.
					array_push( $arr_user_response, $ans_obj_answer );
				}

				$ans_cnt++;
			}
			$this->set_all_answers( $arr_answers );
			$this->set_correct_answers( $arr_correct_answers );
			$this->set_user_answers( $arr_user_response );
		}
	}
}
