<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Qre_Cloze_Question_Data' ) ) {
	/**
	 * Qre_Cloze_Question_Data Class.
	 *
	 * @class Qre_Cloze_Question_Data
	 */
	class Qre_Cloze_Question_Data extends Qre_Question_Data {

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
		 * @param array $result Result Array.
		 * @param array $user_response Array of user response for a single question.
		 */
		public function __construct( $answer_data, $result, $user_response ) {
			$this->user_response = $user_response;
			$this->process_data( $answer_data, $result );
		}

		/**
		 * Processes recieved raw response and answer data and assign to parent vars.
		 *
		 * @param array $answer_data Answer data.
		 * @param array $result Result Array.
		 * @return void Nothing.
		 */
		protected function process_data( $answer_data, $result ) {
			$user_response = $this->user_response;

			$arr_user_response   = array();
			$arr_answers         = array();
			$arr_correct_answers = array();
			$qre_answer          = '';

			$ans_obj = $answer_data[0];

			$arr_correct_answers = maybe_unserialize( $result['qsanswer_data'] );
			$answer_data         = get_protected_value( $ans_obj, '_answer' );

			$arr_wdm_answer = $ans_obj;

			// examples -
			// I {[play][love][hate]} soccer
			// I {play} soccer, with a {ball|3}.

			$ans_obj    = check_isset_var( $answer_data );
			$qre_answer = $ans_obj;

			$arr_wdm_answer = explode( '{', $qre_answer );

			$arr_options = array();

			if ( ! empty( $arr_wdm_answer ) ) {
				$arr_options = $this->get_ans_string( $arr_wdm_answer );
			}

			$arr_answers = $arr_options;

			$arr_correct_answers = $arr_options;

			foreach ( $arr_answers as $ckey => $cval ) {
				$qre_user_ckey_res = check_isset( $user_response, $ckey );
				array_push( $arr_user_response, $qre_user_ckey_res );
				$cval = $cval;
			}

			$this->set_all_answers( $arr_answers );
			$this->set_correct_answers( $arr_correct_answers );
			$this->set_user_answers( $arr_user_response );
			$this->set_answer_obj( $qre_answer );
		}

		/**
		 * Get Answers Array.
		 *
		 * @param  Array $arr_wdm_answer Answer Array.
		 * @return Array $arr_options     Answer Array.
		 */
		public function get_ans_string( $arr_wdm_answer ) {
			$arr_options = array();
			foreach ( $arr_wdm_answer as $cloze_key => $cloze_val ) {
				if ( 0 === $cloze_key ) {
					// first value never be ib.
					continue;
				}

				$arr_wdm_answer2 = explode( '}', $cloze_val );

				$qre_answer_str = check_isset_var( $arr_wdm_answer2[0] );

				if ( '' !== $qre_answer_str ) {
					$arr_wdm_answer_str = explode( '|', $qre_answer_str );

					if ( isset( $arr_wdm_answer_str[1] ) ) {
						$qre_answer_str = $arr_wdm_answer_str[0];
					}
				}

				$qre_answer_str = str_replace( array( '][', ']', '[' ), array( ',', '', '' ), $qre_answer_str );
				array_push( $arr_options, $qre_answer_str );
			}
			return $arr_options;
		}
	}
}
