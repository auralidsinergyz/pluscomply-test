<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Qre_Question_Data' ) ) {
	/**
	 * Qre_Question_Data Class.
	 *
	 * @class Qre_Question_Data
	 */
	class Qre_Question_Data {
		/**
		 * The single instance of the class.
		 *
		 * @var Qre_Question_Data
		 * @since 2.1
		 */
		protected static $instance = null;

		/**
		 * Data of a single question response.
		 *
		 * @var array
		 */
		protected $user_response = null;


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
		);


		/*
		|--------------------------------------------------------------------------
		| Setters.
		|--------------------------------------------------------------------------
		*/

		/**
		 * All answers/options for the question.
		 *
		 * @param array $all_answers All answers/options for the question.
		 *
		 * @return array $all_answers All answers/options for the question.
		 */
		protected function set_all_answers( array $all_answers ) {
			return $this->set_prop( 'all_answers', $all_answers );
		}

		/**
		 * Correct answers/options for the question.
		 *
		 * @param array $correct_answers All answers/options for the question.
		 *
		 * @return array $correct_answers All answers/options for the question.
		 */
		protected function set_correct_answers( array $correct_answers ) {
			return $this->set_prop( 'correct_answers', $correct_answers );
		}

		/**
		 * User answers/options for the question.
		 *
		 * @param array $user_answers All answers/options for the question.
		 *
		 * @return array $user_answers All answers/options for the question.
		 */
		protected function set_user_answers( array $user_answers ) {
			return $this->set_prop( 'user_answers', $user_answers );
		}


		/*
		|--------------------------------------------------------------------------
		| Getters.
		|--------------------------------------------------------------------------
		*/

		/**
		 * All answers/options for the question.
		 *
		 * @return array $all_answers All answers/options for the question.
		 */
		public function get_all_answers() {
			return $this->get_prop( 'all_answers' );
		}

		/**
		 * Correct answers/options for the question.
		 *
		 * @return array $correct_answers All answers/options for the question.
		 */
		public function get_correct_answers() {
			return $this->get_prop( 'correct_answers' );
		}

		/**
		 * User answers/options for the question.
		 *
		 * @return array $user_answers All answers/options for the question.
		 */
		public function get_user_answers() {
			return $this->get_prop( 'user_answers' );
		}


		/*
		|--------------------------------------------------------------------------
		| Property setter and getter.
		|--------------------------------------------------------------------------
		*/

		/**
		 * Sets a prop for a setter method.
		 *
		 * This stores changes in a special array so we can track what needs saving the the DB later.
		 *
		 * @since 3.0.0
		 * @param string $prop Name of prop to set.
		 * @param mixed  $value Value of the prop.
		 */
		protected function set_prop( $prop, $value ) {
			if ( array_key_exists( $prop, $this->data ) ) {
				$this->data[ $prop ] = $value;
			}
		}

		/**
		 * Gets a prop for a getter method.
		 *
		 * Gets the value from either current pending changes, or the data itself.
		 * Context controls what happens to the value before it's returned.
		 *
		 * @since  3.0.0
		 * @param  string $prop Name of prop to get.
		 * @return mixed
		 */
		protected function get_prop( $prop ) {
			$value = null;

			if ( array_key_exists( $prop, $this->data ) ) {
				$value = $this->data[ $prop ];
			}

			return $value;
		}

		/**
		 * Returns a MD5 checksum on a concatenated string comprised of user id, question id, and pos
		 *
		 * @since 1.0.0
		 *
		 * @param  integer $user_id User ID.
		 * @param  integer $question_id Question ID.
		 * @param  integer $pos Position.
		 * @return string MD5 Checksum
		 */
		public function datapos( $user_id, $question_id, $pos ) {
			$pos = intval( $pos );

			return md5( $user_id . $question_id . $pos );
		}

		/**
		 * Qre_Question_Data Instance.
		 *
		 * Ensures only one instance of Qre_Question_Data is loaded or can be loaded.
		 *
		 * @since 3.0.0
		 * @static
		 * @return Qre_Question_Data - instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Qre_Question_Data Constructor.
		 */
		public function __construct() {
		}
	}
}
