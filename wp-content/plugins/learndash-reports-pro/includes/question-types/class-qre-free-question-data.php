<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Qre_Free_Question_Data' ) ) {
	/**
	 * Qre_Free_Question_Data Class.
	 *
	 * @class Qre_Free_Question_Data
	 */
	class Qre_Free_Question_Data extends Qre_Question_Data {
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

			$arr_answers         = array();
			$arr_correct_answers = array();
			$ans_obj             = check_isset_var( $answer_data[0] );
			$ans_obj_answer      = get_protected_value( $ans_obj, '_answer' );

			$ans_obj_answer      = str_replace( array( "\r", "\n" ), ',', $ans_obj_answer );
			$arr_correct_answers = explode( ',', $ans_obj_answer );
			$arr_correct_answers = array_filter( $arr_correct_answers );
			$arr_correct_answers = array_values( $arr_correct_answers );

			// Because "free answer" type questions have correct answers as same as options.
			$arr_answers = $arr_correct_answers;

			if ( '' !== $ans_obj ) {
				$arr_user_response = $user_response;
			}
			$this->set_all_answers( $arr_answers );
			$this->set_correct_answers( $arr_correct_answers );
			$this->set_user_answers( $arr_user_response );
		}
	}
}
