<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Qre_Assessment_Question_Data' ) ) {
	/**
	 * Qre_Assessment_Question_Data Class.
	 *
	 * @class Qre_Assessment_Question_Data
	 */
	class Qre_Assessment_Question_Data extends Qre_Question_Data {

		/**
		 * Data array.
		 *
		 * @since 3.0.0
		 * @var array
		 */
		protected $data = array(
			'all_answers'     => array(), // All answers/options for the question.
			'correct_answers' => array(), // Correct answers for this questions.
			'user_answers'    => array(), // User's answers for this questions.
			'qre_answer'      => '',      // Answer String.
		);

		/*
		|--------------------------------------------------------------------------
		| Setters.
		|--------------------------------------------------------------------------
		*/

		/**
		 * Set answer string for the question.
		 *
		 * @param string $qre_answer Answer string(json) for the question.
		 *
		 * @return array $qre_answer Answer string(json) for the question.
		 */
		protected function set_answer_obj( $qre_answer ) {
			return $this->set_prop( 'qre_answer', $qre_answer );
		}

		/*
		|--------------------------------------------------------------------------
		| Getters.
		|--------------------------------------------------------------------------
		*/

		/**
		 * All answers/options for the question.
		 *
		 * @return array $qre_answer Answer string(json) for the question.
		 */
		public function get_answer_obj() {
			return $this->get_prop( 'qre_answer' );
		}

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
		 * @return void Nothing.
		 */
		protected function process_data( $answer_data ) {
			$user_response = $this->user_response;

			$arr_user_response   = array();
			$arr_answers         = array();
			$arr_correct_answers = array();
			$qre_answer          = '';

			$ans_obj = check_isset_var( $answer_data[0] );

			$qre_answer = get_protected_value( $ans_obj, '_answer' );

			$arr_wdm_answer = explode( '{', $qre_answer );

			if ( ! empty( $arr_wdm_answer ) ) {
				$arr_wdm_answer2 = explode( '}', isset( $arr_wdm_answer[1] ) ? $arr_wdm_answer[1] : array() );
				$qre_answer_str  = check_isset_var( $arr_wdm_answer2[0] );

				$qre_answer_str = str_replace( array( '] [', ']', '[' ), array( ',', '', '' ), $qre_answer_str );

				$arr_answers[0]         = $qre_answer_str;
				$arr_correct_answers[0] = $qre_answer_str;
				$qre_actual_ans         = explode( ',', $qre_answer_str );
			}
			$qre_z_user_response = $this->get_user_response( $user_response, $qre_actual_ans );

			array_push( $arr_user_response, $qre_z_user_response );

			$this->set_all_answers( $arr_answers );
			$this->set_correct_answers( $arr_correct_answers );
			$this->set_user_answers( $arr_user_response );
			$this->set_answer_obj( $qre_answer );
		}

		/**
		 * Get_user_response.
		 * checks user response for question
		 *
		 * @param array $qre_user_response WisdmLabs User Response ;).
		 * @param array $qre_actual_ans Actual Answer.
		 * @return array $qre_z_user_response WisdmLabs ZZZ User Response.
		 *
		 * @since     1.0.0
		 */
		public function get_user_response( $qre_user_response, $qre_actual_ans ) {
			if ( ! empty( $qre_user_response ) ) {
				$qre_z_user_response = check_isset_var( $qre_user_response[0] );
			} else {
				$qre_z_user_response = '';
			}
			if ( isset( $qre_actual_ans[ $qre_z_user_response ] ) && isset( $qre_actual_ans ) && 0 !== $qre_z_user_response ) {
				$qre_z_user_response = $qre_actual_ans[ intval( $qre_z_user_response ) - 1 ];
			}

			return $qre_z_user_response;
		}
	}
}
