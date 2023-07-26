<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Qre_Essay_Question_Data' ) ) {
	/**
	 * Qre_Essay_Question_Data Class.
	 *
	 * @class Qre_Essay_Question_Data
	 */
	class Qre_Essay_Question_Data extends Qre_Question_Data {
		/**
		 * Constuctor.
		 *
		 * @param array $user_response Array of user response for a single question.
		 */
		public function __construct( $user_response ) {
			$this->user_response = $user_response;
			$this->process_data();
		}

		/**
		 * Processes recieved raw response and answer data and assign to parent vars.
		 *
		 * @return void Nothing.
		 */
		protected function process_data() {
			$user_response     = $this->user_response;
			$arr_user_response = array();

			if ( ! empty( $user_response ) ) {
				$qre_link = get_post_meta( $user_response['graded_id'], 'upload', true );
				if ( $qre_link ) {
					array_push( $arr_user_response, $qre_link );
				} else {
					$content_post = get_post( $user_response['graded_id'] );
					$content      = $content_post->post_content;
					array_push( $arr_user_response, $content );
				}
			} else {
				$content = '';
				array_push( $arr_user_response, $content );
			}

			$this->set_user_answers( $arr_user_response );
		}
	}
}
