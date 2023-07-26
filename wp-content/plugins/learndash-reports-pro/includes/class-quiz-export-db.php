<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Quiz_Export_Db' ) ) {
	/**
	 * Quiz_Export_Db Class.
	 *
	 * @class Quiz_Export_Db
	 */
	class Quiz_Export_Db {
		/**
		 * The single instance of the class.
		 *
		 * @var Quiz_Export_Db
		 * @since 2.1
		 */
		protected static $instance = null;

		/**
		 * The single instance of the class.
		 *
		 * @var Quiz_Export_Db
		 * @since 2.1
		 */
		protected $wpdb = null;

		/**
		 * Quiz_Export_Db Instance.
		 *
		 * Ensures only one instance of Quiz_Export_Db is loaded or can be loaded.
		 *
		 * @since 3.0.0
		 * @static
		 * @return Quiz_Export_Db - instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Quiz_Export_Db Constructor.
		 */
		public function __construct() {
			$this->set_db_instance();
		}

		/**
		 * Setter method for setting database instance.
		 */
		public function set_db_instance() {
			global $wpdb;
			$this->wpdb = $wpdb;
		}

		/**
		 * Getter method for setting database instance.
		 *
		 * @return $wpdb WPDB Instance
		 */
		public function get_db_instance() {
			return $this->wpdb;
		}

		/**
		 * Gets all Statistics reference IDs for a given quiz.
		 *
		 * @param integer $quiz_id Quiz ID.
		 * @param array   $args    Timestamp Range.
		 *
		 * @return array $statistics Array of Statistic Reference IDs.
		 */
		public function get_all_statistic_ref_ids_by_quiz( $quiz_id, $args = array() ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$default     = array(
				'limit' => 10,
				'page'  => 1,
			);
			$args        = wp_parse_args( $args, $default );

			$args = apply_filters( 'wrld_get_all_statistic_ref_ids_by_quiz_args', $args, $quiz_id );

			if ( is_wp_error( $args ) ) {
				return array();
			}

			$offset    = ( (int) $args['page'] - 1 ) * (int) $args['limit'];
			$condition = 'quiz_id = %d AND is_old=0';
			if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $args['from'] );
			}
			if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $args['to'] );
			}
			$query  = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $quiz_id, $args['limit'], $offset );
			$result = $db_instance->get_results( $query, ARRAY_A );

			/**
			 *
			 * Statistics Reference IDs by Quiz.
			 *
			 * Returns all statistics reference IDs for a quiz.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param object $quiz_id   Quiz ID.
			 * @param array  $args      Timestamp Range.(keys are from and to).
			 */
			return apply_filters( 'get_all_statistic_ref_ids_by_quiz', $result, $quiz_id, $args );
		}

		/**
		 * Gets all Statistics reference IDs for a given user.
		 *
		 * @param integer $user_id User ID.
		 * @param array   $args    Timestamp Range.
		 *
		 * @return array $statistics Array of Statistic Reference IDs.
		 */
		public function get_all_statistic_ref_ids_by_user( $user_id, $args = array() ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$default     = array(
				'limit' => 10,
				'page'  => 1,
			);
			$args        = wp_parse_args( $args, $default );

			$args = apply_filters( 'wrld_get_all_statistic_ref_ids_by_user_args', $args, $user_id );

			if ( is_wp_error( $args ) ) {
				return array();
			}

			$offset    = ( (int) $args['page'] - 1 ) * (int) $args['limit'];
			$condition = 'user_id = %d AND is_old=0';
			if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $args['from'] );
			}
			if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $args['to'] );
			}
			$query  = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $user_id, $args['limit'], $offset );
			$result = $db_instance->get_results( $query, ARRAY_A );

			/**
			 *
			 * Statistics Reference IDs.
			 *
			 * Returns all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param object $user_id   User ID.
			 * @param array  $args      Timestamp Range.(keys are from and to).
			 */
			return apply_filters( 'get_all_statistic_ref_ids_by_user', $result, $user_id, $args );
		}

		public function get_crossreferenced_statistics( $courses, $start, $end, $limit, $page ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$offset      = ( (int) $page - 1 ) * (int) $limit;
			$condition   = 'is_old=0';
			$courses     = apply_filters( 'wrld_get_crossreferenced_statistics_args', $courses );

			if ( empty( $start ) || empty( $end ) ) {
				$start = apply_filters( 'wrld_filter_start_date_timestamp', strtotime( gmdate( 'j M Y' ) . '-30 days' ) );// phpcs:ignore.
				$end = apply_filters( 'wrld_filter_end_date_timestamp', current_time( 'timestamp' ) );// phpcs:ignore.
			}

			$condition .= sprintf( ' AND ( create_time >= %s AND create_time <= %s ) AND ( 0', $start, $end );

			foreach ( $courses as $key => $course ) {
				if ( empty( $course['quiz_pro_ids'] ) ) {
					continue;
				}
				if ( ! empty( $course['user_ids'] ) ) {
					$user_ids_string = implode( ',', $course['user_ids'] );
				} elseif ( isset( $course['exclude_user_ids'] ) && ! empty( $course['exclude_user_ids'] ) ) {
					$user_exclude_string = implode( ',', $course['exclude_user_ids'] );
				}
				$quiz_ids_string = implode( ',', $course['quiz_pro_ids'] );
				if ( isset( $user_ids_string ) && ! empty( $user_ids_string ) ) {
					$condition .= sprintf( ' OR ( user_id IN (%s) AND quiz_id IN (%s) AND course_post_id = %d )', $user_ids_string, $quiz_ids_string, $course['post']->ID );
				} elseif ( isset( $user_exclude_string ) && ! empty( $user_exclude_string ) ) {
					$condition .= sprintf( ' OR ( user_id NOT IN (%s) AND quiz_id IN (%s) AND course_post_id = %d )', $user_exclude_string, $quiz_ids_string, $course['post']->ID );
				} else {
					$condition .= sprintf( ' OR ( quiz_id IN (%s) AND course_post_id = %d )', $quiz_ids_string, $course['post']->ID );
				}
			}
			$condition .= ')';
			$query      = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $limit, $offset );
			$result     = $db_instance->get_results( $query, ARRAY_A );

			/**
			 *
			 * Statistics Reference IDs.
			 *
			 * Returns all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param array  $args      Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
			 */
			return apply_filters( 'get_crossreferenced_statistics', $result, $condition );
		}

		public function get_crossreferenced_statistics_count( $courses, $start, $end, $limit = 0, $page = 0 ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$offset      = ( (int) $page - 1 ) * (int) $limit;
			$condition   = 'is_old=0';

			$courses = apply_filters( 'wrld_get_crossreferenced_statistics_count_args', $courses );

			if ( empty( $start ) || empty( $end ) ) {
				$start = apply_filters( 'wrld_filter_start_date_timestamp', strtotime( gmdate( 'j M Y' ) . '-30 days' ) );// phpcs:ignore.
				$end = apply_filters( 'wrld_filter_end_date_timestamp', current_time( 'timestamp' ) );// phpcs:ignore.
			}

			$condition .= sprintf( ' AND ( create_time >= %s AND create_time <= %s ) AND ( 0', $start, $end );

			foreach ( $courses as $key => $course ) {
				if ( empty( $course['quiz_pro_ids'] ) ) {
					continue;
				}
				if ( ! empty( $course['user_ids'] ) ) {
					$user_ids_string = implode( ',', $course['user_ids'] );
				} elseif ( isset( $course['exclude_user_ids'] ) && ! empty( $course['exclude_user_ids'] ) ) {
					$user_exclude_string = implode( ',', $course['exclude_user_ids'] );
				}
				$quiz_ids_string = implode( ',', $course['quiz_pro_ids'] );
				if ( isset( $user_ids_string ) && ! empty( $user_ids_string ) ) {
					$condition .= sprintf( ' OR ( user_id IN (%s) AND quiz_id IN (%s) AND course_post_id = %d )', $user_ids_string, $quiz_ids_string, $course['post']->ID );
				} elseif ( isset( $user_exclude_string ) && ! empty( $user_exclude_string ) ) {
					$condition .= sprintf( ' OR ( user_id NOT IN (%s) AND quiz_id IN (%s) AND course_post_id = %d )', $user_exclude_string, $quiz_ids_string, $course['post']->ID );
				} else {
					$condition .= sprintf( ' OR ( quiz_id IN (%s) AND course_post_id = %d )', $quiz_ids_string, $course['post']->ID );
				}
			}
			$condition .= ')';
			$query      = $db_instance->prepare( "SELECT COUNT(*) FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC" );
			$result     = $db_instance->get_var( $query );

			/**
			 *
			 * Statistics Reference IDs.
			 *
			 * Returns all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param array  $args      Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
			 */
			return apply_filters( 'get_crossreferenced_statistics_count', $result, $condition );
		}

		/**
		 * Gets all Statistics reference IDs.
		 *
		 * @param array $args Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
		 *
		 * @return array $statistics Array of Statistic Reference IDs.
		 */
		public function get_all_statistic_ref_ids( $args = array(
			'user_ids'  => array(),
			'quiz_ids'  => array(),
			'limit'     => 10,
			'page'      => 1,
			'exclusive' => false,
		) ) {
			$default = array(
				'user_ids'  => array(),
				'quiz_ids'  => array(),
				'limit'     => 10,
				'page'      => 1,
				'exclusive' => false,
			);
			$args    = wp_parse_args( $args, $default );

			$args        = apply_filters( 'wrld_get_all_statistic_ref_ids_args', $args );
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$offset      = ( (int) $args['page'] - 1 ) * (int) $args['limit'];
			$condition   = 'is_old=0';
			if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $args['from'] );
			}
			if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $args['to'] );
			}
			if ( ! $args['exclusive'] ) {
				if ( isset( $args['user_ids'] ) && ! empty( $args['user_ids'] ) ) {
					$user_ids_string = implode( ',', $args['user_ids'] );
					$condition      .= sprintf( ' AND user_id IN (%s)', $user_ids_string );
				} elseif ( isset( $args['not_include_users'] ) && ! empty( $args['not_include_users'] ) ) {
					$user_ids_string = implode( ',', $args['not_include_users'] );
					$condition      .= sprintf( ' AND user_id NOT IN (%s)', $user_ids_string );
				}
				if ( isset( $args['quiz_ids'] ) && ! empty( $args['quiz_ids'] ) ) {
					$quiz_ids_string = implode( ',', $args['quiz_ids'] );
					$condition      .= sprintf( ' AND quiz_id IN (%s)', $quiz_ids_string );
				} elseif ( isset( $args['not_include_course'] ) && ! empty( $args['not_include_course'] ) ) {
					$quiz_ids_string = implode( ',', $args['not_include_course'] );
					$condition      .= sprintf( ' AND course_post_id NOT IN (%s)', $quiz_ids_string );
				}
			} else {
				$users   = '""';
				$quizzes = '""';
				if ( isset( $args['user_ids'] ) && ! empty( $args['user_ids'] ) ) {
					$user_ids_string = implode( ',', $args['user_ids'] );
					$users           = $user_ids_string;
				}
				if ( isset( $args['quiz_ids'] ) && ! empty( $args['quiz_ids'] ) ) {
					$quiz_ids_string = implode( ',', $args['quiz_ids'] );
					$quizzes         = $quiz_ids_string;
				}
				$condition .= sprintf( ' AND ( user_id IN (%1$s) OR quiz_id IN (%2$s) )', $users, $quizzes );
			}
			$query  = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $args['limit'], $offset );
			$result = $db_instance->get_results( $query, ARRAY_A );

			/**
			 *
			 * Statistics Reference IDs.
			 *
			 * Returns all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param array  $args      Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
			 */
			return apply_filters( 'get_all_statistic_ref_ids', $result, $args );
		}

		/**
		 * Gets all Statistics reference IDs.
		 *
		 * @param array $args Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
		 *
		 * @return array $statistics Array of Statistic Reference IDs.
		 */
		public function get_all_statistic_ref_ids_count( $args = array(
			'user_ids'  => array(),
			'quiz_ids'  => array(),
			'exclusive' => false,
		) ) {
			$default = array(
				'user_ids'  => array(),
				'quiz_ids'  => array(),
				'exclusive' => false,
			);
			$args    = wp_parse_args( $args, $default );

			$args = apply_filters( 'wrld_get_all_statistic_ref_ids_count_args', $args );

			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$condition   = 'is_old=0';
			if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $args['from'] );
			}
			if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $args['to'] );
			}
			if ( ! $args['exclusive'] ) {
				if ( isset( $args['user_ids'] ) && ! empty( $args['user_ids'] ) ) {
					$user_ids_string = implode( ',', $args['user_ids'] );
					$condition      .= sprintf( ' AND user_id IN (%s)', $user_ids_string );
				} elseif ( isset( $args['not_include_users'] ) && ! empty( $args['not_include_users'] ) ) {
					$user_ids_string = implode( ',', $args['not_include_users'] );
					$condition      .= sprintf( ' AND user_id NOT IN (%s)', $user_ids_string );
				}
				if ( isset( $args['quiz_ids'] ) && ! empty( $args['quiz_ids'] ) ) {
					$quiz_ids_string = implode( ',', $args['quiz_ids'] );
					$condition      .= sprintf( ' AND quiz_id IN (%s)', $quiz_ids_string );
				} elseif ( isset( $args['not_include_course'] ) && ! empty( $args['not_include_course'] ) ) {
					$quiz_ids_string = implode( ',', $args['not_include_course'] );
					$condition      .= sprintf( ' AND course_post_id NOT IN (%s)', $quiz_ids_string );
				}
			} else {
				$users   = '""';
				$quizzes = '""';
				if ( isset( $args['user_ids'] ) && ! empty( $args['user_ids'] ) ) {
					$user_ids_string = implode( ',', $args['user_ids'] );
					$users           = $user_ids_string;
				}
				if ( isset( $args['quiz_ids'] ) && ! empty( $args['quiz_ids'] ) ) {
					$quiz_ids_string = implode( ',', $args['quiz_ids'] );
					$quizzes         = $quiz_ids_string;
				}
				$condition .= sprintf( ' AND ( user_id IN (%1$s) OR quiz_id IN (%2$s) )', $users, $quizzes );
			}
			$query  = "SELECT COUNT(*) FROM {$table_name} WHERE {$condition}";
			$result = $db_instance->get_var( $query );

			/**
			 *
			 * Count of Statistics Reference IDs.
			 *
			 * Returns Count ofo all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs Count.
			 * @param array  $args      Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
			 */
			return apply_filters( 'get_all_statistic_ref_ids_count', $result, $args );
		}

		/**
		 * Gets all data related to the quiz attempt and quiz.
		 *
		 * @param  integer $ref_id Statistics Reference ID.
		 * @return array $result Array of all data associated with the quiz attempt.
		 * @throws Exception If a user tries an unauthorized SQL operation.
		 */
		public function get_quiz_attempt_data( $ref_id ) {
			$db_instance = $this->get_db_instance();

			$statistic_ref_table = $this->get_db_name( 'quiz_statistic_ref' );
			$question_table      = $this->get_db_name( 'quiz_question' );
			$statistic_table     = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT qsr.statistic_ref_id, qsr.quiz_id, qsr.user_id, qq.question, qq.points, qq.answer_type, qq.answer_data, qq.sort col_sort, qs.points qspoints, qs.answer_data qsanswer_data, qs.question_time, qs.question_id FROM {$statistic_ref_table} qsr,  {$question_table} qq, {$statistic_table} qs WHERE qsr.statistic_ref_id = qs.statistic_ref_id AND qq.id=qs.question_id AND qsr.statistic_ref_id IN (%d) ORDER BY qsr.statistic_ref_id DESC, col_sort ASC; ", $ref_id );

			if ( preg_match( '[update|delete|drop|alter]', strtolower( $query ) ) === true ) {
				throw new Exception( 'No cheating' );
			}

			/**
			 *
			 * Quiz Attempt Data.
			 *
			 * Returns Quiz Attempt data.
			 *
			 * @since 3.0.0
			 *
			 * @param array             Quiz Attempt data.
			 * @param integer $ref_id   Statistic Reference ID.
			 */
			$result = remove_empty_array_items( apply_filters( 'qre_quiz_attempt_data', $db_instance->get_results( $query, ARRAY_A ), $ref_id ) );
			$db_instance->flush();

			return $result;
		}

		/**
		 * Get Summarized statistic Data for Quiz.
		 *
		 * @param  integer $statistic_ref_id Statistic Ref ID.
		 * @return array  Results array.
		 */
		public function get_statistic_summarized_data( $statistic_ref_id ) {
			$db_instance = $this->get_db_instance();

			$statistic_ref_table = $this->get_db_name( 'quiz_statistic_ref' );
			$question_table      = $this->get_db_name( 'quiz_question' );
			$statistic_table     = $this->get_db_name( 'quiz_statistic' );

			$query   = $db_instance->prepare( "SELECT SUM(qq.points) AS gpoints, SUM(qs.points) AS points, SUM(qs.question_time) AS question_time, SUM(qs.correct_count) AS correct_count, SUM(qs.incorrect_count) AS incorrect_count, qsr.user_id, qsr.quiz_id, qsr.statistic_ref_id, qsr.create_time FROM {$statistic_ref_table} qsr INNER JOIN {$statistic_table} qs ON qsr.statistic_ref_id = qs.statistic_ref_id INNER JOIN {$question_table} qq ON qq.id=qs.question_id WHERE qsr.statistic_ref_id IN (%d) ORDER BY qsr.statistic_ref_id DESC", $statistic_ref_id );
			$results = $db_instance->get_results( $query, ARRAY_A );
			$results = current( $results );
			/**
			 * Get Summarized data about a statistic i.e., total points, correct/incorrect count etc.
			 *
			 * @param array   $results          Summarized data
			 * @param integer $statistic_ref_id Statistic Ref ID.
			 */
			return apply_filters( 'get_statistic_summarized_data', $results, $statistic_ref_id );
		}

		/**
		 * This method is used to fetch each user's total and earned points when attempting a particular quiz.
		 *
		 * @param  integer $quiz_id Quiz Pro ID.
		 * @return array   Class Points Earned.
		 */
		public function get_users_total_points( $quiz_id ) {
			$db_instance = $this->get_db_instance();

			$statistic_ref_table = $this->get_db_name( 'quiz_statistic_ref' );
			$question_table      = $this->get_db_name( 'quiz_question' );
			$statistic_table     = $this->get_db_name( 'quiz_statistic' );

			$query   = $db_instance->prepare( "SELECT qsr.user_id, SUM(qq.points) AS gpoints, SUM(qs.points) AS points FROM {$statistic_ref_table} qsr INNER JOIN {$statistic_table} qs ON qsr.statistic_ref_id = qs.statistic_ref_id INNER JOIN {$question_table} qq ON qq.id=qs.question_id WHERE qsr.quiz_id=%d GROUP BY qsr.user_id ORDER BY qsr.user_id DESC", $quiz_id );
			$results = $db_instance->get_results( $query, ARRAY_A );
			/**
			 * Get total points and earned points by user.
			 *
			 * @param array   $results          Summarized data
			 * @param integer $statistic_ref_id Statistic Ref ID.
			 * @param integer $quiz_id          Quiz Pro ID.
			 */
			return apply_filters( 'get_user_total_points', $results, $quiz_id );
		}

		/**
		 * To get form questions data of custom field by quiz id
		 *
		 * @param  number $quiz_id quiz id.
		 * @return array $custom_form_data custom form field questions
		 */
		public function get_custom_field_form_questions( $quiz_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_form' );

			$custom_form_query = $db_instance->prepare( "SELECT form_id, fieldname, type, data FROM {$table_name} WHERE quiz_id=%d ORDER BY sort ASC;", $quiz_id );

			$custom_form_data = $db_instance->get_results( $custom_form_query, ARRAY_A );
			$db_instance->flush();

			/**
			 *
			 * Custom Form Questions.
			 *
			 * Returns Custom form questions.
			 *
			 * @since 3.0.0
			 *
			 * @param array   $custom_form_data Custom Form Questions.
			 * @param integer $quiz_id          Quiz ID.
			 */
			return apply_filters( 'qre_custom_field_form_questions', $custom_form_data, $quiz_id );
		}

		/**
		 * To return custom field's answers.
		 *
		 * @param  integer $ref_id  statistics ref id.
		 * @param  integer $quiz_id quiz id.
		 * @param  integer $user_id user id of user.
		 * @return array   $custom_answers answers of user.
		 */
		public function get_custom_field_form_answers( $ref_id, $quiz_id, $user_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic_ref' );

			$custom_query   = $db_instance->prepare( "SELECT form_data FROM {$table_name} WHERE statistic_ref_id=%d AND quiz_id=%d AND user_id=%d;", $ref_id, $quiz_id, $user_id );
			$custom_answers = maybe_unserialize( $db_instance->get_var( $custom_query ) );
			$db_instance->flush();

			if ( '' !== $custom_answers ) {
				$custom_answers = json_decode( $custom_answers, 1 );
			}

			/**
			 *
			 * Custom Form Answers.
			 *
			 * Returns Custom form field answers.
			 *
			 * @since 3.0.0
			 *
			 * @param array   $custom_answers  Custom Form Answers.
			 * @param integer $ref_id          Statistics Reference ID.
			 * @param integer $quiz_id         Quiz ID.
			 * @param integer $user_id         User ID.
			 */
			return apply_filters( 'qre_custom_field_form_answers', $custom_answers, $ref_id, $quiz_id, $user_id );
		}

		/**
		 * Returns a quiz title of quiz
		 *
		 * @param  int $quiz_id Quiz ID.
		 * @return string MD5 Checksum
		 */
		public function get_quiz_title( $quiz_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_master' );

			$quiz_query = $db_instance->prepare( "SELECT `name` FROM {$table_name} WHERE id=%d", $quiz_id );

			$quiz_name = $db_instance->get_var( $quiz_query );

			/**
			 *
			 * Quiz Title.
			 *
			 * Returns Quiz Name from quiz_master table.
			 *
			 * @since 3.0.0
			 *
			 * @param string  $quiz_name  Quiz Title.
			 * @param integer $quiz_id    Quiz ID.
			 */
			return apply_filters( 'qre_quiz_title', $quiz_name, $quiz_id );
		}

		/**
		 * Check for permanently deleted quizzes.
		 *
		 * @param  string $quiz_ids Quiz IDs to check.
		 * @return array  $quiz_ids_present Quiz IDs present.
		 */
		public function check_if_quiz_ids_actually_present( $quiz_ids ) {
			$db_instance = $this->get_db_instance();

			$table_name = $db_instance->prefix . 'posts';

			$quiz_qry = $db_instance->prepare( "SELECT ID FROM {$table_name} where ID IN (%s)", $quiz_ids );

			$quiz_ids_present = $db_instance->get_col( $quiz_qry );

			return $quiz_ids_present;
		}

		/**
		 * Check for permanently deleted users.
		 *
		 * @param  string $user_ids User IDs to check.
		 * @return array  $user_ids_present Quiz IDs present.
		 */
		public function check_if_user_ids_actually_present( $user_ids ) {
			$db_instance = $this->get_db_instance();

			$table_name = $db_instance->prefix . 'users';

			$user_qry = $db_instance->prepare( "SELECT ID FROM {$table_name} where ID IN (%s)", $user_ids );

			$user_ids_present = $db_instance->get_col( $user_qry );

			return $user_ids_present;
		}

		/**
		 * Check for deleted/reset quiz statistics.
		 *
		 * @param  string $statistics_ids Statistic IDs to check.
		 * @return array  $ids_present    Statistic IDs present.
		 */
		public function check_if_statistics_actually_present( $statistics_ids ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic_ref' );

			$check_qry = $db_instance->prepare( "SELECT statistic_ref_id FROM {$table_name} where statistic_ref_id IN (%s)", $statistics_ids );

			$ids_present = $db_instance->get_col( $check_qry );

			return $ids_present;
		}

		/**
		 * Get Statistic Ref ID of a particular attempt.
		 *
		 * @param  integer $user_id     User ID.
		 * @param  integer $pro_quiz_id Pro Quiz ID.
		 * @param  integer $time        Timestamp of the time the quiz was attempted.
		 * @return array   $result      Query Results.
		 */
		public function get_statistic_ref_id( $user_id, $pro_quiz_id, $time ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic_ref' );

			$query = $db_instance->prepare( "SELECT statistic_ref_id FROM {$table_name} where user_id = %1$d AND quiz_id = %2$d AND create_time = %3$d ORDER BY quiz_id DESC", $user_id, $pro_quiz_id, $time );

			$result = $db_instance->get_results( $query );

			/**
			 *
			 * Statistic Reference ID.
			 *
			 * Returns Statistics Reference ID of a particular attempt.
			 *
			 * @since 3.0.0
			 *
			 * @param array   $result      Statistic Reference ID.
			 * @param integer $user_id     User ID.
			 * @param integer $pro_quiz_id Pro Quiz ID.
			 * @param integer $time        Timestamp of attempt.
			 */
			return apply_filters( 'qre_statistic_ref_id', $result, $user_id, $pro_quiz_id, $time );
		}

		/**
		 * This method returns the total question count for the quiz.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qcount        Question Count.
		 */
		public function get_total_questions_count( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT count(*) as count FROM {$table_name} WHERE statistic_ref_id = %d", $statistics_id );

			$qcount = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Total Questions Count.
			 *
			 * Returns the number of questions in the quiz.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $qcount         Question Count.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_total_questions_count', $qcount, $statistics_id );
		}

		/**
		 * This method returns the correct question count for the quiz.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qscore        Question Count.
		 */
		public function get_correct_questions_count( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT count(correct_count) as score FROM {$table_name} WHERE correct_count = 1 AND statistic_ref_id = %d", $statistics_id );

			$qscore = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Correct Questions Count.
			 *
			 * Returns the number of correct questions attempted.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $qscore         Correct Question Count.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_correct_questions_count', $qscore, $statistics_id );
		}

		/**
		 * This method returns the total points for the quiz attempt.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qpoints       Points Earned.
		 */
		public function get_points_earned( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT SUM(points) as points FROM {$table_name} WHERE statistic_ref_id = %d", $statistics_id );

			$qpoints = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Points Earned.
			 *
			 * Returns the points earned.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $qpoints        Points Earned.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_points_earned', $qpoints, $statistics_id );
		}

		/**
		 * This method returns all of the questions asked for the quiz attempt.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qids          Questions Asked.
		 */
		public function get_questions_asked( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT question_id FROM {$table_name} WHERE statistic_ref_id = %d", $statistics_id );

			$qids = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Questions Asked.
			 *
			 * Returns the list of questions asked for a particular quiz attempt.
			 *
			 * @since 3.0.0
			 *
			 * @param array    $qids           Question IDs.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_questions_attempted', $qids, $statistics_id );
		}

		/**
		 * This method returns the total time taken for the quiz attempt.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qtime         Time Taken.
		 */
		public function get_quiz_time_taken( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT SUM(question_time) as quet FROM {$table_name} WHERE statistic_ref_id = %d", $statistics_id );

			$qtime = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Quiz Time Taken.
			 *
			 * Returns the time taken by the user for a particular quiz attempt.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $qtime          Question Time taken.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_quiz_time_taken', $qtime, $statistics_id );
		}

		/**
		 * This method returns the total points that can be earned for the quiz attempt.
		 *
		 * @param  array $questions     Quiz Questions.
		 * @return integer $total_points  Total Points.
		 */
		public function get_quiz_total_points( $questions ) {
			$db_instance = $this->get_db_instance();

			$total_points = 0;
			$table_name   = $this->get_db_name( 'quiz_question' );

			foreach ( $questions as $question ) {
				$query        = $db_instance->prepare( "SELECT points FROM {$table_name} WHERE id = %d", $question->question_id );
				$qpoint       = $db_instance->get_results( $query, OBJECT );
				$total_points = $total_points + $qpoint[0]->points;
			}

			/**
			 *
			 * Quiz Points.
			 *
			 * Returns the total points for a particular quiz attempt.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $total_points   Total Points.
			 * @param integer  $questions      Statististics Reference ID.
			 */
			return apply_filters( 'qre_quiz_total_points', $total_points, $questions );
		}

		/**
		 * Used to get LearnDash table name.
		 *
		 * @param  string $table Table slug.
		 * @param string $context Table type.
		 *
		 * @return string $table Table name based on prefix setting in Data Upgrades(LD).
		 */
		private function get_db_name( $table, $context = 'wpproquiz' ) {
			/**
			 * All LD Table slugs.
			 * 'quiz_category', 'quiz_form', 'quiz_lock', 'quiz_master', 'quiz_prerequisite', 'quiz_question', 'quiz_statistic', 'quiz_statistic_ref', 'quiz_template', 'quiz_toplist'
			 */
			return LDLMS_DB::get_table_name( $table, $context );
		}

		/**
		 * Get published posts contained within specific IDS.
		 *
		 * @param string $post_type Post Type.
		 * @param array  $post_ids  Post IDs.
		 *
		 * @return array $post_ids  Ids of Posts.
		 */
		public function get_posts_within_ids( $post_type, $post_ids = array() ) {
			$db_instance = $this->get_db_instance();

			$table_name         = $db_instance->posts;
			$post_ids_condition = 1;

			if ( ! empty( $post_ids ) ) {
				$post_ids_condition = 'ID IN (' . implode( ',', $post_ids ) . ')';
			}

			$query = $db_instance->prepare( "SELECT ID from {$table_name} WHERE post_type=%s AND {$post_ids_condition} AND post_status IN ('publish', 'private', 'protected')", $post_type );
			$posts = $db_instance->get_results( $query, ARRAY_A );
			/**
			 *
			 * Result Posts.
			 *
			 * Get published posts contained within specific IDS.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $posts     Result Posts.
			 * @param string $post_type Post Type.
			 * @param array  $post_ids  Post IDs to filter from.
			 */
			return apply_filters( 'qre_get_posts_within_ids', $posts, $post_type, $post_ids );
		}
	}
}
