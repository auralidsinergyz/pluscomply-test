<?php
/**
 * Report Export data generation.
 *
 * @package Quiz Reporting Extension
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
if ( ! class_exists( 'Quiz_Reporting_Frontend' ) ) {
	/**
	 * Quiz_Reporting_Frontend Class.
	 *
	 * @class Quiz_Reporting_Frontend
	 */
	class Quiz_Reporting_Frontend {
		/**
		 * The single instance of the class.
		 *
		 * @var Quiz_Reporting_Frontend
		 * @since 2.1
		 */
		protected static $instance = null;

		/**
		 * Quiz_Reporting_Frontend Instance.
		 *
		 * Ensures only one instance of Quiz_Reporting_Frontend is loaded or can be loaded.
		 *
		 * @since 3.0.0
		 * @static
		 * @return Quiz_Reporting_Frontend - instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Quiz_Reporting_Frontend Constructor.
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
			add_action( 'wp_ajax_qre_live_search', array( $this, 'qre_live_search' ) );
			add_action( 'wp_ajax_qre_save_filters', array( $this, 'qre_save_filters' ) );
			add_action( 'wp_ajax_wrld_export_entries', array( $this, 'wrld_export_entries' ) );
			add_action( 'wp_ajax_wrld_export_progress_results', array( $this, 'wrld_export_progress_results' ) );
			add_action( 'wp_ajax_wrld_export_attempt_results', array( $this, 'wrld_export_attempt_filters' ) );
			add_action( 'wp_ajax_wrld_export_learner_results', array( $this, 'wrld_export_learner_results' ) );
			add_action( 'wp_ajax_get_quiz_reports_data', array( $this, 'get_quiz_reports_data' ) );
			add_action( 'init', array( $this, 'migration_for_incorrect_usermeta' ) );
			add_filter( 'wrld_get_all_statistic_ref_ids_by_quiz_args', array( $this, 'get_statistic_ref_ids_by_quiz_args' ), 10, 2 );
			add_filter( 'get_all_statistic_ref_ids_by_quiz', array( $this, 'get_all_statistic_ref_ids_by_quiz' ), 10, 1 );
			add_filter( 'wrld_get_all_statistic_ref_ids_by_user_args', array( $this, 'get_statistic_ref_ids_by_user_args' ), 10, 2 );
			add_filter( 'get_all_statistic_ref_ids_by_user', array( $this, 'get_all_statistic_ref_ids_by_user' ), 10, 1 );
			add_filter( 'wrld_get_crossreferenced_statistics_args', array( $this, 'get_crossreferenced_statistics_args' ), 10, 1 );
			add_filter( 'wrld_get_crossreferenced_statistics_count_args', array( $this, 'get_crossreferenced_statistics_args' ), 10, 1 );
			add_filter( 'wrld_get_all_statistic_ref_ids_args', array( $this, 'get_all_statistic_ref_ids_args' ), 10, 1 );
			add_filter( 'wrld_get_all_statistic_ref_ids_count_args', array( $this, 'get_all_statistic_ref_ids_args' ), 10, 1 );
			add_filter( 'get_all_statistic_ref_ids', array( $this, 'get_all_statistic_ref_ids' ), 10, 1 );
			// add_action( 'wp_ajax_qre_get_filters', array( $this, 'qre_get_filters' ) );
		}

		public function get_all_statistic_ref_ids( $statistics ) {
			$excluded_courses = get_option( 'exclude_courses', false );
			if ( ! empty( $excluded_courses ) ) {
				foreach ( $statistics as $key => $statistic ) {
					if ( in_array( $statistic['course_post_id'], $excluded_courses ) ) {
						unset( $statistics[ $key ] );
					}
				}
			}
			$excluded_users = get_option( 'exclude_users', false );
			if ( ! empty( $excluded_users ) ) {
				foreach ( $statistics as $key => $statistic ) {
					if ( in_array( $statistic['user_id'], $excluded_users ) ) {
						unset( $statistics[ $key ] );
					}
				}
			}
			$excluded_ur = get_option( 'exclude_ur', false );
			if ( ! empty( $excluded_ur ) ) {
				foreach ( $statistics as $key => $statistic ) {
					$user_meta  = get_userdata( $statistic['user_id'] );
					$user_roles = $user_meta->roles;
					if ( empty( array_intersect( $excluded_ur, $user_roles ) ) ) {
						continue;
					}
					unset( $statistics[ $key ] );
				}
			}
			return $statistics;
		}

		public function get_all_statistic_ref_ids_args( $args ) {
			$excluded_courses = get_option( 'exclude_courses', false );
			if ( ! empty( $excluded_courses ) ) {
				if ( empty( $args['quiz_ids'] ) ) {
					$args['not_include_course'] = $excluded_courses;
				} else {
					foreach ( $args['quiz_ids'] as $key => $quiz_id ) {
						$quiz_post_id = learndash_get_quiz_id_by_pro_quiz_id( $quiz_id );
						$course_id    = learndash_get_course_id( $quiz_post_id );
						if ( in_array( $course_id, $excluded_courses ) ) {
							unset( $args['quiz_ids'][ $key ] );
						}
					}
				}
			}
			$excluded_users            = get_option( 'exclude_users', false );
			$args['not_include_users'] = array();
			if ( ! empty( $excluded_users ) ) {
				if ( empty( $args['user_ids'] ) ) {
					$args['not_include_users'] = $excluded_users;
				} else {
					foreach ( $args['user_ids'] as $key => $user_id ) {
						if ( in_array( $user_id, $excluded_users ) ) {
							unset( $args['user_ids'][ $key ] );
						}
					}
				}
			}
			$excluded_ur = get_option( 'exclude_ur', false );
			if ( ! empty( $excluded_ur ) ) {
				if ( empty( $args['user_ids'] ) ) {
					$user_args                 = array(
						'number'   => -1,
						'fields'   => array(
							'ID',
						),
						'role__in' => $excluded_ur,
					);
					$users                     = get_users( $user_args );
					$user_ids                  = wp_list_pluck( $users, 'ID' );
					$args['not_include_users'] = array_merge( $args['not_include_users'], $user_ids );
				} else {
					foreach ( $args['user_ids'] as $key => $user_id ) {
						$user_meta  = get_userdata( $user_id );
						$user_roles = $user_meta->roles;
						if ( ! empty( array_intersect( $excluded_ur, $user_roles ) ) ) {
							unset( $args['user_ids'][ $key ] );
						}
					}
				}
			}
			return $args;
		}

		public function get_crossreferenced_statistics_args( $courses ) {
			$excluded_courses = get_option( 'exclude_courses', false );
			if ( ! empty( $excluded_courses ) ) {
				foreach ( $courses as $key => $course ) {
					if ( in_array( $course['post']->ID, $excluded_courses ) ) {
						unset( $courses[ $key ] );
					}
				}
			}
			$excluded_users = get_option( 'exclude_users', false );
			if ( ! empty( $excluded_users ) ) {
				foreach ( $courses as $key => $course ) {
					if ( ! empty( $course['user_ids'] ) ) {
						foreach ( $course['user_ids'] as $ukey => $user_id ) {
							if ( in_array( $user_id, $excluded_users ) ) {
								unset( $course['user_ids'][ $ukey ] );
							}
						}
					} else {
						$courses[ $key ]['exclude_user_ids'] = $excluded_users;
					}
				}
			}
			$excluded_ur = get_option( 'exclude_ur', false );
			if ( ! empty( $excluded_ur ) ) {
				foreach ( $courses as $key => $course ) {
					if ( ! empty( $course['user_ids'] ) ) {
						foreach ( $course['user_ids'] as $ukey => $user_id ) {
							$user_meta  = get_userdata( $user_id );
							$user_roles = $user_meta->roles;
							if ( ! empty( array_intersect( $excluded_ur, $user_roles ) ) ) {
								unset( $course['user_ids'][ $ukey ] );
							}
						}
					} else {
						$user_args = array(
							'number'   => -1,
							'fields'   => array(
								'ID',
							),
							'role__in' => $excluded_ur,
						);
						$users     = get_users( $user_args );
						$user_ids  = wp_list_pluck( $users, 'ID' );
						if ( ! isset( $course['exclude_user_ids'] ) ) {
							$course['exclude_user_ids'] = array();
						}
						$courses[ $key ]['exclude_user_ids'] = array_merge( $course['exclude_user_ids'], $user_ids );
					}
				}
			}
			return $courses;
		}

		public function get_all_statistic_ref_ids_by_user( $statistics ) {
			$excluded_courses = get_option( 'exclude_courses', false );
			if ( empty( $excluded_courses ) ) {
				return $statistics;
			}
			foreach ( $statistics as $key => $statistic ) {
				if ( in_array( $statistic['course_post_id'], $excluded_courses ) ) {
					unset( $statistics[ $key ] );
				}
			}
			return $statistics;
		}

		public function get_statistic_ref_ids_by_user_args( $args, $user_id ) {
			$excluded_users = get_option( 'exclude_users', false );
			$excluded_ur    = get_option( 'exclude_ur', false );
			if ( empty( $excluded_ur ) && empty( $excluded_users ) ) {
				return $args;
			}
			if ( ! empty( $excluded_ur ) ) {
				$user_meta  = get_userdata( $user_id );
				$user_roles = $user_meta->roles;
				if ( ! empty( array_intersect( $excluded_ur, $user_roles ) ) ) {
					return new \WP_Error( 'blocked', __( 'Related user role is excluded from reports', 'learndash-reports-pro' ) );
				}
			}
			if ( ! empty( $excluded_users ) ) {
				if ( in_array( $user_id, $excluded_users ) ) {
					return new \WP_Error( 'blocked', __( 'Related user is excluded from reports', 'learndash-reports-pro' ) );
				}
			}
			return $args;
		}

		public function get_all_statistic_ref_ids_by_quiz( $statistics ) {
			$excluded_users = get_option( 'exclude_users', false );
			$excluded_ur    = get_option( 'exclude_ur', false );
			if ( empty( $excluded_ur ) && empty( $excluded_users ) ) {
				return $statistics;
			}
			if ( ! empty( $excluded_ur ) ) {
				foreach ( $statistics as $key => $statistic ) {
					$user_meta  = get_userdata( $statistic['user_id'] );
					$user_roles = $user_meta->roles;
					if ( empty( array_intersect( $excluded_ur, $user_roles ) ) ) {
						continue;
					}
					unset( $statistics[ $key ] );
				}
			}
			if ( ! empty( $excluded_users ) ) {
				foreach ( $statistics as $key => $statistic ) {
					if ( ! in_array( $statistic['user_id'], $excluded_users ) ) {
						continue;
					}
					unset( $statistics[ $key ] );
				}
			}
			return $statistics;
		}

		public function get_statistic_ref_ids_by_quiz_args( $args, $quiz_id ) {
			$excluded_courses = get_option( 'exclude_courses', false );
			if ( empty( $excluded_courses ) ) {
				return $args;
			}

			$quiz_post_id = learndash_get_quiz_id_by_pro_quiz_id( $quiz_id );
			$course_id    = learndash_get_course_id( $quiz_post_id );
			if ( in_array( $course_id, $excluded_courses ) ) {
				return new \WP_Error( 'blocked', __( 'Related course is excluded from reports', 'learndash-reports-pro' ) );
			}
			return $args;
		}

		public function migration_for_incorrect_usermeta() {
			$saved_fields = get_user_meta( get_current_user_id(), 'qre_custom_reports_saved_query', true );
			if ( ! empty( $saved_fields ) && count( $saved_fields ) <= 4 ) {
				$defaults = array(
					'course_title'       => 'yes',
					'completion_status'  => 'yes',
					'completion_date'    => false,
					'course_category'    => false,
					'enrollment_date'    => false,
					'course_progress'    => false,
					'group_name'         => false,
					'user_name'          => 'yes',
					'user_email'         => false,
					'user_first_name'    => false,
					'user_last_name'     => false,
					'quiz_status'        => 'yes',
					'quiz_title'         => 'yes',
					'quiz_category'      => 'yes',
					'quiz_points_total'  => 'yes',
					'quiz_points_earned' => 'yes',
					'quiz_score_percent' => 'yes',
					'date_of_attempt'    => 'yes',
					'time_taken'         => 'yes',
					'question_text'      => 'yes',
					'question_options'   => 'yes',
					'correct_answers'    => 'yes',
					'user_answers'       => 'yes',
					'question_type'      => 'yes',
				);
				$fields   = wp_parse_args( $defaults, $saved_fields );
				update_user_meta( get_current_user_id(), 'qre_custom_reports_saved_query', $fields );
			}
		}

		/**
		 * This method is used to fetch default quiz reports paginated data.
		 */
		public function get_quiz_reports_data() {
			global $ldrp_quiz_table_data;
			$report_type = filter_input( INPUT_GET, 'report', FILTER_SANITIZE_STRING );
			$page_number = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT );
			$data        = array();
			if ( empty( $report_type ) ) {
				$content = $this->render_default_quiz_reports( $page_number );
				$data    = $ldrp_quiz_table_data;
			} else {
				$content = $this->render_custom_quiz_reports( $page_number );
			}
			wp_send_json_success(
				array(
					'html'    => $content,
					'entries' => $data,
				)
			);
		}

		public function render_default_quiz_reports( $page_number ) {
			ldrp_enqueue_shortcode_assets();
			ob_start();
			// ldrp_add_breadcrumbs();
			$screen_type = filter_input( INPUT_GET, 'screen', FILTER_SANITIZE_STRING );
			if ( empty( $screen_type ) || ! in_array( $screen_type, array( 'user', 'quiz' ), true ) ) {
				$screen_type = 'listing';
			}
			switch ( $screen_type ) {
				case 'listing':
					echo ldrp_show_report_listing_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;
				case 'quiz':
					echo ldrp_show_single_statistic_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;
				case 'user':
					echo ldrp_show_user_statistics_screen();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;
			}
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}

		public function render_custom_quiz_reports( $page_number = 1 ) {
			ldrp_enqueue_shortcode_assets();
			ob_start();
			echo ldrp_show_custom_reports_screen( $page_number );
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}

		/**
		 * This method is used to get statistic reports based on query params.
		 *
		 * @internal Currently Not in Use. Will need it later for Rest API endpoints.
		 */
		public function show_paginated_entries() {
			$query_type     = filter_input( INPUT_GET, 'search_result_type', FILTER_SANITIZE_STRING );
			$queried_obj_id = filter_input( INPUT_GET, 'search_result_id', FILTER_VALIDATE_INT );
			$queried_string = filter_input( INPUT_GET, 'qre-search-field', FILTER_SANITIZE_STRING );
			$date_filter    = filter_input( INPUT_GET, 'filter_type', FILTER_SANITIZE_STRING );
			$time_period    = filter_input( INPUT_GET, 'period', FILTER_SANITIZE_STRING );
			$from_date      = filter_input( INPUT_GET, 'from_date', FILTER_SANITIZE_STRING );
			$to_date        = filter_input( INPUT_GET, 'to_date', FILTER_SANITIZE_STRING );
			$limit          = filter_input( INPUT_GET, 'limit', FILTER_SANITIZE_STRING );
			$page           = filter_input( INPUT_GET, 'pageno', FILTER_SANITIZE_STRING );
			$security       = filter_input( INPUT_GET, 'security', FILTER_SANITIZE_STRING );

			if ( empty( $security ) || ! wp_verify_nonce( $security, 'refresh_page_entries' ) ) {
				$error = new WP_Error( 403, __( 'Illegal Request. Identifying security token is either missing or is incorrect.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}

			$statistics = Quiz_Export_Data::instance()->get_filtered_statistics( $query_type, $queried_obj_id, $queried_string, $date_filter, $time_period, $from_date, $to_date, $limit, $page );
			if ( is_wp_error( $statistics ) ) {
				wp_send_json_error( $statistics );
				die();
			}
			$statistic_data = array_map(
				function( $statistic ) {
					if ( ! array_key_exists( 'statistic_ref_id', $statistic ) ) {
						return;
					}
					$data                 = Quiz_Export_Db::instance()->get_statistic_summarized_data( $statistic['statistic_ref_id'] );
					$data['quiz_title']   = "<a href='" . add_query_arg(
						array(
							'report'    => 'quiz',
							'screen'    => 'quiz',
							'user'      => $statistic['user_id'],
							'quiz'      => $statistic['quiz_id'],
							'statistic' => $statistic['statistic_ref_id'],
						),
						get_permalink()
					) . "'>" . get_the_title( learndash_get_quiz_id_by_pro_quiz_id( $statistic['quiz_id'] ) ) . '</a>';
					$data['user_name']    = "<a href='" . add_query_arg(
						array(
							'report' => 'quiz',
							'screen' => 'user',
							'user'   => $statistic['user_id'],
						),
						get_permalink()
					) . "'>" . get_userdata( $statistic['user_id'] )->display_name . '</a>';
					$data['date_attempt'] = date_i18n( get_option( 'date_format', 'd-M-Y' ), $statistic['create_time'] );
					/* translators: %1$d: Points Earned, %2$d: Total Points */
					$data['score']      = sprintf( __( '%1$d of %2$d', 'learndash-reports-pro' ), $data['points'], $data['gpoints'] );
					$dt_current         = new \DateTime( '@0' );
					$dt_after_seconds   = new \DateTime( '@' . (int) $data['question_time'] );
					$data['time_taken'] = $dt_current->diff( $dt_after_seconds )->format( '%H:%I:%S' );
					$data['link']       = "<a href='#' data-ref_id='" . $statistic['statistic_ref_id'] . "' class=\"qre-export qre-download-csv\"><img src='" . LDRP_PLUGIN_URL . 'assets/public/images/csv.svg' . "'/></a>&nbsp;&nbsp;&nbsp;<a href='#' data-ref_id='" . $statistic['statistic_ref_id'] . "' class=\"qre-export qre-download-xlsx\"><img src='" . LDRP_PLUGIN_URL . 'assets/public/images/xls.svg' . "'/></a>";
					return $data;
				},
				$statistics
			);
			$statistic_data = remove_empty_array_items( $statistic_data );
			if ( empty( $statistic_data ) ) {
				$error = new WP_Error( 200, __( 'No Data to Display.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			wp_send_json_success( $statistic_data );
			die();
		}

		/**
		 * This method is used to execute the live search feature query and send realtime results.
		 */
		public function qre_live_search() {
			$query = filter_input( INPUT_POST, 'query', FILTER_SANITIZE_STRING );
			$nonce = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'get_search_suggestions' ) ) {
				$error = new WP_Error( 403, __( 'Security check failed. Please try again later.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			if ( empty( $query ) ) {
				$error = new WP_Error( 400, __( 'Empty Search Query.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			if ( strlen( $query ) < 3 ) {
				/* translators: %s: Search Query. */
				wp_send_json_error( array( 'message' => sprintf( __( 'There is no result for "%s".', 'learndash-reports-pro' ), $query ) ) );
				die();
			}
			$results        = array();
			$user_results   = array();
			$course_results = array();
			$quiz_results   = qre_search_quizzes( $query );
			$course_results = qre_search_courses( $query );
			$user_results   = qre_search_users( $query );
			$results        = array_merge( $user_results, $quiz_results );
			$results        = array_merge( $results, $course_results );
			if ( empty( $results ) ) {
				/* translators: %s: Search Query. */
				wp_send_json_error( array( 'message' => sprintf( __( 'There is no result for "%s".', 'learndash-reports-pro' ), $query ) ) );
				die();
			}
			wp_send_json_success( $results );
			die();
			// $user_results = qre_search_users( $query );
		}

		/**
		 * This method is used to save custom reports filter configuration.
		 */
		public function qre_save_filters() {
			$fields = filter_input( INPUT_POST, 'fields', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$nonce  = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'custom_reports_nonce' ) ) {
				$error = new WP_Error( 403, __( 'Security check failed. Please try again later.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			$saved_fields = get_user_meta( get_current_user_id(), 'qre_custom_reports_saved_query', true );
			if ( isset( $fields['select_event'] ) ) {
				if ( ! empty( $saved_fields ) ) {
					$fields = wp_parse_args( $fields, $saved_fields );
				} else {
					$defaults = array(
						'course_title'       => 'yes',
						'completion_status'  => 'yes',
						'completion_date'    => false,
						'course_category'    => false,
						'enrollment_date'    => false,
						'course_progress'    => false,
						'group_name'         => false,
						'user_name'          => 'yes',
						'user_email'         => false,
						'user_first_name'    => false,
						'user_last_name'     => false,
						'quiz_status'        => 'yes',
						'quiz_title'         => 'yes',
						'quiz_category'      => 'yes',
						'quiz_points_total'  => 'yes',
						'quiz_points_earned' => 'yes',
						'quiz_score_percent' => 'yes',
						'date_of_attempt'    => 'yes',
						'time_taken'         => 'yes',
						'question_text'      => 'yes',
						'question_options'   => 'yes',
						'correct_answers'    => 'yes',
						'user_answers'       => 'yes',
						'question_type'      => 'yes',
					);
					$fields   = wp_parse_args( $fields, $defaults );
				}
			}
			update_user_meta( get_current_user_id(), 'qre_custom_reports_saved_query', $fields );
			wp_send_json_success();
			die();
		}

		public function wrld_export_entries() {
			$fields = filter_input( INPUT_POST, 'fields', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$nonce  = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'custom_reports_nonce' ) ) {
				$error = new WP_Error( 403, __( 'Security check failed. Please try again later.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			$course_id  = $fields['course_filter'];
			$group_id   = $fields['group_filter'];
			$quiz_id    = $quiz_pro_id = $fields['quiz_filter'];
			$user_id    = array_key_exists( 'user_id', $fields ) ? $fields['user_id'] : 0;
			$start_date = $fields['start_date'];
			$end_date   = $fields['end_date'];
			$response   = array();
			// First Logic for attempt counts.
			$accessible_users   = '';
			$accessible_courses = '';
			if ( ! empty( $quiz_id ) && $quiz_id > 0 ) {
				$quiz_pro_id = get_post_meta( $quiz_id, 'quiz_pro_id', true );
			}
			if ( ! empty( $group_id ) && $group_id > 0 ) {
				$accessible_users   = \WRLD_Common_Functions::get_users_enrolled_in_groups( array( $group_id ) );
				$accessible_courses = \WRLD_Common_Functions::get_list_of_courses_in_groups( array( $group_id ) );
				$accessible_users   = implode( ',', $accessible_users );
				$accessible_courses = implode( ',', $accessible_courses );
			}
			$response['attempt_count'] = \WRLD_Quiz_Export_Db::instance()->get_export_attempts_data_count(
				array(
					'quiz_id'   => $quiz_pro_id,
					'course_id' => $course_id,
					'group_id'  => $group_id,
					'user_id'   => $user_id,
					'users'     => $accessible_users,
					'courses'   => $accessible_courses,
					'start'     => $start_date,
					'end'       => $end_date,
				)
			);
			// $response['quiz_count'] = \WRLD_Quiz_Export_Db::instance()->get_export_quizzes_count(
			// array(
			// 'quiz_id'   => $quiz_pro_id,
			// 'course_id' => $course_id,
			// 'group_id'  => $group_id,
			// 'users'     => $accessible_users,
			// 'courses'   => $accessible_courses,
			// 'start'     => $start_date,
			// 'end'       => $end_date
			// )
			// );
			update_user_meta( get_current_user_id(), 'total_export_entries', $response['attempt_count'] );
			wp_send_json_success( array( 'count' => $response ) );
			die();
		}

		public function wrld_export_progress_results() {
			$nonce = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'custom_reports_nonce' ) ) {
				$error = new WP_Error( 403, __( 'Security check failed. Please try again later.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			$total   = get_user_meta( get_current_user_id(), 'total_export_entries', true );
			$current = get_user_meta( get_current_user_id(), 'current_export_entries', true );
			if ( empty( $total ) ) {
				$total = 1;
			}

			if ( empty( $current ) ) {
				$current = 0;
			}
			$percentage = floatval( number_format( 100 * $current / $total, 2, '.', '' ) );
			wp_send_json_success( array( 'percentage' => $percentage ) );
			die();
		}

		public function wrld_export_attempt_filters() {
			$fields = filter_input( INPUT_POST, 'fields', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$nonce  = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'custom_reports_nonce' ) ) {
				$error = new WP_Error( 403, __( 'Security check failed. Please try again later.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			$type               = $fields['type'];
			$course_id          = $fields['course_filter'];
			$group_id           = $fields['group_filter'];
			$quiz_id            = $quiz_pro_id = $fields['quiz_filter'];
			$user_id            = array_key_exists( 'user_id', $fields ) ? $fields['user_id'] : 0;
			$start_date         = $fields['start_date'];
			$end_date           = $fields['end_date'];
			$accessible_users   = '';
			$accessible_courses = '';
			if ( ! empty( $quiz_id ) && $quiz_id > 0 ) {
				$quiz_pro_id = get_post_meta( $quiz_id, 'quiz_pro_id', true );
			}
			if ( ! empty( $group_id ) && $group_id > 0 ) {
				$accessible_users   = \WRLD_Common_Functions::get_users_enrolled_in_groups( array( $group_id ) );
				$accessible_courses = \WRLD_Common_Functions::get_list_of_courses_in_groups( array( $group_id ) );
				$accessible_users   = implode( ',', $accessible_users );
				$accessible_courses = implode( ',', $accessible_courses );
			}
			$results = \WRLD_Quiz_Export_Db::instance()->get_export_attempts_data(
				array(
					'quiz_id'   => $quiz_pro_id,
					'course_id' => $course_id,
					'group_id'  => $group_id,
					'user_id'   => $user_id,
					'users'     => $accessible_users,
					'courses'   => $accessible_courses,
					'start'     => $start_date,
					'end'       => $end_date,
				),
				10000,
				1
			);

			$data = array();
			foreach ( $results as $result ) {
				$temp            = array();
				$temp['summary'] = \WRLD_Quiz_Export_Db::instance()->get_statistic_summarized_data( $result['statistic_ref_id'] );
				$temp['result']  = $result;
				$data[]          = $temp;
			}
			$link = call_user_func_array( array( $this, 'wrld_export_attempt_' . $type ), array( 'data' => $data ) );
			if ( ! $link ) {
				wp_send_json_error();
				die();
			}
			wp_send_json_success( array( 'link' => $link ) );
			die();
		}

		public function wrld_export_learner_results() {
			$fields = filter_input( INPUT_POST, 'fields', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$nonce  = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'custom_reports_nonce' ) ) {
				$error = new WP_Error( 403, __( 'Security check failed. Please try again later.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			$type               = $fields['type'];
			$course_id          = $fields['course_filter'];
			$group_id           = $fields['group_filter'];
			$quiz_id            = $quiz_pro_id = $fields['quiz_filter'];
			$user_id            = array_key_exists( 'user_id', $fields ) ? $fields['user_id'] : 0;
			$start_date         = $fields['start_date'];
			$end_date           = $fields['end_date'];
			$accessible_users   = '';
			$accessible_courses = '';
			if ( ! empty( $quiz_id ) && $quiz_id > 0 ) {
				$quiz_pro_id = get_post_meta( $quiz_id, 'quiz_pro_id', true );
			}
			if ( ! empty( $group_id ) && $group_id > 0 ) {
				$accessible_users   = \WRLD_Common_Functions::get_users_enrolled_in_groups( array( $group_id ) );
				$accessible_courses = \WRLD_Common_Functions::get_list_of_courses_in_groups( array( $group_id ) );
				$accessible_users   = implode( ',', $accessible_users );
				$accessible_courses = implode( ',', $accessible_courses );
			}
			$results         = \WRLD_Quiz_Export_Db::instance()->get_export_attempts_data(
				array(
					'quiz_id'   => $quiz_pro_id,
					'user_id'   => $user_id,
					'course_id' => $course_id,
					'group_id'  => $group_id,
					'users'     => $accessible_users,
					'courses'   => $accessible_courses,
					'start'     => $start_date,
					'end'       => $end_date,
				),
				10000,
				1
			);
			$grouped_results = array();
			foreach ( $results as $element ) {
				$grouped_results[ $element['quiz_post_id'] ][ $element['course_post_id'] ][] = $element;
			}
			$data = array();
			foreach ( $grouped_results as $quiz_id => $result ) {
				foreach ( $result as $course_id => $statistics ) {
					foreach ( $statistics as $statistic ) {
						$temp                             = array();
						$temp['result']                   = $statistic;
						$temp['summary']                  = \WRLD_Quiz_Export_Db::instance()->get_statistic_summarized_data( $statistic['statistic_ref_id'] );
						$temp['detail']                   = \WRLD_Quiz_Export_Db::instance()->get_quiz_attempt_data( $statistic['statistic_ref_id'] );
						$data[ $quiz_id ][ $course_id ][] = $temp;
					}
				}
			}
			$link = call_user_func_array( array( $this, 'wrld_export_learner_' . $type ), array( 'data' => $data ) );
			if ( ! $link ) {
				wp_send_json_error();
				die();
			}
			wp_send_json_success( array( 'link' => $link ) );
			die();
		}

		public function wrld_export_attempt_csv( $data ) {
			$upload_dir = wp_upload_dir();
			$file       = null;
			$page       = 1;
			$filename   = sanitize_file_name( 'QuizAttemptsResult_CSV-' . current_time( 'mysql' ) );
	        // @codingStandardsIgnoreStart
			if ( ! file_exists( $upload_dir['basedir'] . '/QuizAttemptsResult_CSV' ) ) {
				mkdir( $upload_dir['basedir'] . '/QuizAttemptsResult_CSV' );
			}
			// Opens a file in write mode.
			$file      = fopen( $upload_dir['basedir'] . '/QuizAttemptsResult_CSV/' . $filename . '_' . $page . '.csv', 'w' );
	        // @codingStandardsIgnoreEnd
			// Checks if file opened on php output stream
			if ( $file ) {
				$headings = array(
					__( 'User ID', 'learndash-reports-pro' ),
					__( 'Username', 'learndash-reports-pro' ),
					__( 'Full Name', 'learndash-reports-pro' ),
					__( 'User Email', 'learndash-reports-pro' ),
					__( 'Quiz Title', 'learndash-reports-pro' ),
					__( 'Quiz Category', 'learndash-reports-pro' ),
					__( 'Date of Attempt', 'learndash-reports-pro' ),
					__( 'Time Taken(in sec)', 'learndash-reports-pro' ),
					__( 'Pass / Fail', 'learndash-reports-pro' ),
					__( 'Earned Points', 'learndash-reports-pro' ),
					__( 'Total Points Available', 'learndash-reports-pro' ),
					__( 'Score(%)', 'learndash-reports-pro' ),
					__( 'Course', 'learndash-reports-pro' ),
					__( 'Course Category', 'learndash-reports-pro' ),
					__( 'Group', 'learndash-reports-pro' ),
				);
				fputcsv( $file, $headings );
				$export_counter = 1;
				foreach ( $data as $entry ) {
					$td   = array();
					$user = get_user_by( 'id', $entry['summary']['user_id'] );
					$td[] = $entry['summary']['user_id'];
					$td[] = $user->user_login;
					$td[] = $user->display_name;
					$td[] = $user->user_email;

					$td[] = get_the_title( $entry['result']['quiz_post_id'] );

					$post_terms = wp_get_object_terms( $entry['result']['quiz_post_id'], 'ld_quiz_category', array( 'fields' => 'ids' ) );
					$separator  = ', ';
					$terms      = '';
					if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
						$term_ids = implode( ',', $post_terms );
						$terms    = wp_list_categories(
							array(
								'title_li' => '',
								'style'    => 'none',
								'echo'     => false,
								'taxonomy' => 'ld_quiz_category',
								'include'  => $term_ids,
							)
						);

						$terms = trim( strip_tags( rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator ) ) );
					}
					$td[] = $terms;

					$td[] = date_i18n( 'Y-m-j H:i:s', $entry['summary']['create_time'] );
					$td[] = $entry['summary']['question_time'];

					$quiz_post_settings = learndash_get_setting( $entry['result']['quiz_post_id'] );
					if ( ! is_array( $quiz_post_settings ) ) {
						$quiz_post_settings = array();
					}
					if ( ! isset( $quiz_post_settings['passingpercentage'] ) ) {
						$quiz_post_settings['passingpercentage'] = 0;
					}
					$passingpercentage = absint( $quiz_post_settings['passingpercentage'] );
					$pass              = 0;
					if ( $entry['summary']['points'] / $entry['summary']['gpoints'] * 100 >= $passingpercentage ) {
						$pass = 1;
					}
					$td[] = $pass ? __( 'Pass', 'learndash-reports-pro' ) : __( 'Fail', 'learndash-reports-pro' );

					$td[] = $entry['summary']['points'];
					$td[] = $entry['summary']['gpoints'];
					$td[] = number_format( (float) $entry['summary']['points'] / $entry['summary']['gpoints'] * 100, 2, '.', '' );
					$td[] = get_the_title( $entry['result']['course_post_id'] );

					$post_terms = wp_get_object_terms( $entry['result']['course_post_id'], 'ld_course_category', array( 'fields' => 'ids' ) );
					$separator  = ', ';
					$terms      = '';
					if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
						$term_ids = implode( ',', $post_terms );
						$terms    = wp_list_categories(
							array(
								'title_li' => '',
								'style'    => 'none',
								'echo'     => false,
								'taxonomy' => 'ld_course_category',
								'include'  => $term_ids,
							)
						);

						$terms = trim( strip_tags( rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator ) ) );
					}
					$td[] = $terms;

					$group_title = '';
					if ( isset( $_POST['fields']['group_filter'] ) ) {
						$group_id    = $_POST['fields']['group_filter'];
						$group_title = get_the_title( $_POST['fields']['group_filter'] );
					}
					$td[] = $group_title;
					fputcsv( $file, $td );
					update_user_meta( get_current_user_id(), 'current_export_entries', $export_counter );
					$export_counter++;

				}
				// Closes the Csv file.
				fclose( $file );
				return $upload_dir['baseurl'] . '/QuizAttemptsResult_CSV/' . $filename . '_' . $page . '.csv';
			} else {
				return false;
			}
			return true;
		}

		public function wrld_export_learner_csv( $data ) {
			$upload_dir = wp_upload_dir();
			$file       = null;
			$page       = 1;
			$filename   = sanitize_file_name( 'QuizAttemptsAnswers_CSV-' . current_time( 'mysql' ) );
	        // @codingStandardsIgnoreStart
			if ( ! file_exists( $upload_dir['basedir'] . '/QuizAttemptsAnswers_CSV' ) ) {
				mkdir( $upload_dir['basedir'] . '/QuizAttemptsAnswers_CSV' );
			}
			// Opens a file in write mode.
			$file      = fopen( $upload_dir['basedir'] . '/QuizAttemptsAnswers_CSV/' . $filename . '_' . $page . '.csv', 'w' );
	        // @codingStandardsIgnoreEnd
			// Checks if file opened on php output stream
			if ( $file ) {
				foreach ( $data as $quiz_id => $result ) {
					foreach ( $result as $course_id => $statistics_data ) {
						$group_title = '';
						if ( isset( $_POST['fields']['group_filter'] ) ) {
							$group_id    = $_POST['fields']['group_filter'];
							$group_title = get_the_title( $_POST['fields']['group_filter'] );
						}
						$post_terms   = wp_get_object_terms( $course_id, 'ld_course_category', array( 'fields' => 'ids' ) );
						$separator    = ', ';
						$course_terms = '';
						if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
							$term_ids     = implode( ',', $post_terms );
							$course_terms = wp_list_categories(
								array(
									'title_li' => '',
									'style'    => 'none',
									'echo'     => false,
									'taxonomy' => 'ld_course_category',
									'include'  => $term_ids,
								)
							);

							$course_terms = trim( strip_tags( rtrim( trim( str_replace( '<br />', $separator, $course_terms ) ), $separator ) ) );
						}
						$post_terms = wp_get_object_terms( $quiz_id, 'ld_quiz_category', array( 'fields' => 'ids' ) );
						$quiz_terms = '';
						if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
							$term_ids   = implode( ',', $post_terms );
							$quiz_terms = wp_list_categories(
								array(
									'title_li' => '',
									'style'    => 'none',
									'echo'     => false,
									'taxonomy' => 'ld_quiz_category',
									'include'  => $term_ids,
								)
							);

							$quiz_terms = trim( strip_tags( rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator ) ) );
						}
						$headings = array(
							__( 'Quiz Name: ', 'learndash-reports-pro' ) . get_the_title( $quiz_id ),
							__( 'Total Points: ', 'learndash-reports-pro' ) . reset( $statistics_data )['summary']['gpoints'],
							__( 'Course: ', 'learndash-reports-pro' ) . get_the_title( $course_id ),
							__( 'Group: ', 'learndash-reports-pro' ) . $group_title,
							__( 'Course Category: ', 'learndash-reports-pro' ) . $course_terms,
							__( 'Quiz Category: ', 'learndash-reports-pro' ) . $quiz_terms,
						);
						fputcsv( $file, $headings );
						$headings2 = array(
							__( 'User ID', 'learndash-reports-pro' ),
							__( 'Username', 'learndash-reports-pro' ),
							__( 'Full Name', 'learndash-reports-pro' ),
							__( 'Attempted on', 'learndash-reports-pro' ),
							__( 'Total Time Taken', 'learndash-reports-pro' ),
							__( 'Pass / Fail', 'learndash-reports-pro' ),
							__( 'Earned Points', 'learndash-reports-pro' ),
							__( 'Score(%)', 'learndash-reports-pro' ),
						);
						$qno       = 1;
						foreach ( $statistics_data[0]['detail'] as $question ) {
							$headings2[] = __( 'Question ', 'learndash-reports-pro' ) . $qno . ': ' . $question['question'];
							$headings2[] = __( 'Answered Correctly?', 'learndash-reports-pro' );
							$headings2[] = __( 'Points Scored', 'learndash-reports-pro' );
							$headings2[] = __( 'Time Taken(in sec)', 'learndash-reports-pro' );
							$qno++;
						}
						fputcsv( $file, $headings2 );
						$export_counter = 1;
						foreach ( $statistics_data as $entry ) {
							$td   = array();
							$user = get_user_by( 'id', $entry['summary']['user_id'] );
							$td[] = $entry['summary']['user_id'];
							$td[] = $user->user_login;
							$td[] = $user->display_name;
							$td[] = date_i18n( 'Y-m-j H:i:s', $entry['summary']['create_time'] );
							$td[] = $entry['summary']['question_time'];

							$quiz_post_settings = learndash_get_setting( $entry['result']['quiz_post_id'] );
							if ( ! is_array( $quiz_post_settings ) ) {
								$quiz_post_settings = array();
							}
							if ( ! isset( $quiz_post_settings['passingpercentage'] ) ) {
								$quiz_post_settings['passingpercentage'] = 0;
							}
							$passingpercentage = absint( $quiz_post_settings['passingpercentage'] );
							$pass              = 0;
							if ( $entry['summary']['points'] / $entry['summary']['gpoints'] * 100 >= $passingpercentage ) {
								$pass = 1;
							}
							$td[] = $pass ? __( 'Pass', 'learndash-reports-pro' ) : __( 'Fail', 'learndash-reports-pro' );

							$td[] = $entry['summary']['points'];
							$td[] = number_format( (float) $entry['summary']['points'] / $entry['summary']['gpoints'] * 100, 2, '.', '' );
							foreach ( $entry['detail'] as $question ) {
								$td[] = implode( ', ', $this->get_user_response( $question ) );

								$answered_correctly = '';
								if ( $question['qspoints'] >= $question['points'] ) {
									$answered_correctly = __( 'Yes', 'learndash-reports-pro' );
								} elseif ( $question['answer_type'] == 'essay' ) {
									$graded_id     = json_decode( $question['qsanswer_data'] )->graded_id;
									$graded_status = get_post_status( $graded_id );
									if ( 'not_graded' == $graded_status ) {
										$answered_correctly = __( 'Not Graded', 'learndash-reports-pro' );
									} elseif ( $question['qspoints'] > 0 ) {
										$answered_correctly = __( 'Partially', 'learndash-reports-pro' );
									} else {
										$answered_correctly = __( 'No', 'learndash-reports-pro' );
									}
								} elseif ( $question['qspoints'] > 0 ) {
									$answered_correctly = __( 'Partially', 'learndash-reports-pro' );
								} else {
									$answered_correctly = __( 'No', 'learndash-reports-pro' );
								}
								$td[] = $answered_correctly;
								$td[] = $question['qspoints'];
								$td[] = $question['question_time'];
							}
							fputcsv( $file, $td );
							update_user_meta( get_current_user_id(), 'current_export_entries', $export_counter );
							$export_counter++;
						}
					}
				}

				// Closes the Csv file.
				fclose( $file );
				return $upload_dir['baseurl'] . '/QuizAttemptsAnswers_CSV/' . $filename . '_' . $page . '.csv';
			} else {
				return false;
			}
			return true;
		}

		public function get_user_response( $question ) {
			$question_type      = $question['answer_type'];
			$qre_answer_data    = maybe_unserialize( $question['answer_data'] );
			$qre_user_response  = json_decode( $question['qsanswer_data'], 1 );
			$qre_question_id    = $question['question_id'];
			$cur_user_id        = $question['user_id'];
			$is_attach_question = false; // if want to attache answer to question. For cloze questions.
			switch ( $question_type ) {
				// if $question_type is single OR multiple.
				case 'single':
				case 'multiple':
					$res_object = new Qre_Single_Question_Data( $qre_answer_data, $qre_user_response );
					// $arr_correct_answers = $res_object->get_correct_answers();
					$arr_user_response = $res_object->get_user_answers();
					// $arr_answers         = $res_object->get_all_answers();
					break;

				case 'free_answer':
					$res_object = new Qre_Free_Question_Data( $qre_answer_data, $qre_user_response );
					// $arr_correct_answers = $res_object->get_correct_answers();
					$arr_user_response = $res_object->get_user_answers();
					// $arr_answers         = $res_object->get_all_answers();
					break;

				case 'sort_answer':
					$res_object = new Qre_Sort_Question_Data( $qre_answer_data, $qre_question_id, $qre_user_response, $cur_user_id );
					// $arr_correct_answers = $res_object->get_correct_answers();
					$arr_user_response = $res_object->get_user_answers();
					// $arr_answers         = $res_object->get_all_answers();
					break;

				case 'matrix_sort_answer':
					$res_object = new Qre_Matrix_Sort_Question_Data( $qre_answer_data, $qre_question_id, $qre_user_response, $cur_user_id );
					// $arr_correct_answers = $res_object->get_correct_answers();
					$arr_user_response = $res_object->get_user_answers();
					// $arr_answers         = $res_object->get_all_answers();
					break;

				case 'cloze_answer':
					$is_attach_question = true;
					$res_object         = new Qre_Cloze_Question_Data( $qre_answer_data, $question, $qre_user_response );
					// $arr_correct_answers = $res_object->get_correct_answers();
					$arr_user_response = $res_object->get_user_answers();
					// $arr_answers         = $res_object->get_all_answers();
					// $qre_answer          = $res_object->get_answer_obj();
					break;

				case 'assessment_answer':
					$res_object = new Qre_Assessment_Question_Data( $qre_answer_data, $qre_user_response );
					// $arr_correct_answers = $res_object->get_correct_answers();
					$arr_user_response = $res_object->get_user_answers();
					// $arr_answers         = $res_object->get_all_answers();
					// $qre_answer          = $res_object->get_answer_obj();
					break;

				case 'essay':
					$res_object        = new Qre_Essay_Question_Data( $qre_user_response );
					$arr_user_response = $res_object->get_user_answers();
					break;
			}
			return $arr_user_response;
		}

		public function wrld_export_learner_xlsx( $data ) {
			$upload_dir = wp_upload_dir();
			$file       = null;
			$page       = 1;
			$filename   = sanitize_file_name( 'QuizAttemptsAnswers_XLSX-' . current_time( 'mysql' ) );
			if ( ! file_exists( $upload_dir['basedir'] . '/QuizAttemptsAnswers_XLSX' ) ) {
				mkdir( $upload_dir['basedir'] . '/QuizAttemptsAnswers_XLSX' );
			}
			$spreadsheet = new Spreadsheet();

			$excel_data = array();
			$data       = $this->parse_xls_learner_data( $data );

			// Counter for $data array loop.
			$dnt = 0;

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle( 'A1:Z1' )->applyFromArray(
				array(
					'fill' => array(
						'fillType'   => Fill::FILL_SOLID,
						'startColor' => array( 'rgb' => 'c9daf8' ),
					),
				)
			);
			$export_counter    = 1;
			$spreadsheet_count = 0;
			$spreadsheets      = array();
			foreach ( $data as $row_key => $row_val ) {

				// Counter for $row_val array loop.
				$rnt = 0;

				foreach ( $row_val as $cell_key => $cell_val ) {
					if ( 0 === $rnt && count( $row_val ) === 6 && 0 === $spreadsheet_count ) {
						$name = trim( explode( ':', $cell_val['value'] )[1] );
						$name = str_replace( array( '\\', '/', '*', '?', ':', '[', ']' ), '', $name );
						$sheet->setTitle( $name );
						$spreadsheet_count++;
					} elseif ( 0 === $rnt && count( $row_val ) === 6 && 0 < $spreadsheet_count ) {
						$spreadsheets[] = $excel_data;
						$excel_data     = array();
						$name           = trim( explode( ':', $cell_val['value'] )[1] );
						$name           = str_replace( array( '\\', '/', '*', '?', ':', '[', ']' ), '', $name );
						$spreadsheet->createSheet();
						$spreadsheet->setActiveSheetIndex( $spreadsheet_count );
						$sheet = $spreadsheet->getActiveSheet();
						$sheet->setTitle( $name );
						$spreadsheet_count++;
						$dnt = 0;
					}
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

				// Here 15 because we have 14 columns in the file.
				for ( $snt = $rnt; $snt < count( $row_val ); $snt++ ) {
					$excel_data[ $dnt ][ $snt ] = '';
				}

				$dnt++;

				$data[ $row_key ] = null;
				update_user_meta( get_current_user_id(), 'current_export_entries', $export_counter );
				$export_counter++;
			}
			$spreadsheets[]    = $excel_data;
			$excel_data        = array();
			$spreadsheet_index = 0;
			foreach ( $spreadsheets as $excel_data ) {
				$spreadsheet->setActiveSheetIndex( $spreadsheet_index );
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
				$spreadsheet_index++;
			}

			// ob_end_clean(); // Dont know why this was added.
			// Object to wrie into the file and save in Php output stream.
			$writer = new Xlsx( $spreadsheet );

			// Opens a file in write mode.
			// @codingStandardsIgnoreEnd
			$file = fopen( $upload_dir['basedir'] . '/QuizAttemptsAnswers_XLSX/' . $filename . '_' . $page . '.xlsx', 'w' );
			$writer->save( $file );

			$spreadsheet->disconnectWorksheets();
			unset( $spreadsheet );
			return $upload_dir['baseurl'] . '/QuizAttemptsAnswers_XLSX/' . $filename . '_' . $page . '.xlsx';
		}

		public function wrld_export_attempt_xlsx( $data ) {
			$upload_dir = wp_upload_dir();
			$file       = null;
			$page       = 1;
			$filename   = sanitize_file_name( 'QuizAttemptsResult_XLSX-' . current_time( 'mysql' ) );
	        // @codingStandardsIgnoreStart
			if ( ! file_exists( $upload_dir['basedir'] . '/QuizAttemptsResult_XLSX' ) ) {
				mkdir( $upload_dir['basedir'] . '/QuizAttemptsResult_XLSX' );
			}
			$spreadsheet = new Spreadsheet();

			$excel_data = array();

			$data  = $this->parse_xls_data( $data );

			// Counter for $data array loop.
			$dnt = 0;

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1:N1')->applyFromArray(
		        array(
		            'fill' => array(
		                'fillType' => Fill::FILL_SOLID,
		                'startColor' => array('rgb' => 'c9daf8')
		            )
		        )
		    );
		    $export_counter = 1;
			foreach ( $data as $row_key => $row_val ) {

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

				// Here 15 because we have 14 columns in the file.
				for ( $snt = $rnt; $snt < 15; $snt++ ) {
					$excel_data[ $dnt ][ $snt ] = '';
				}

				$dnt++;

				$data[ $row_key ] = null;
				update_user_meta( get_current_user_id(), 'current_export_entries', $export_counter );
				$export_counter++;
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
			$spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'J' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'K' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'L' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'M' )->setAutoSize( true );
			$spreadsheet->getActiveSheet()->getColumnDimension( 'N' )->setAutoSize( true );

			$sheet = $spreadsheet->getActiveSheet();

			// ob_end_clean(); // Dont know why this was added.
			// Object to wrie into the file and save in Php output stream.
			$writer     = new Xlsx( $spreadsheet );
			
			// Opens a file in write mode.
	        // @codingStandardsIgnoreEnd
			$file = fopen( $upload_dir['basedir'] . '/QuizAttemptsResult_XLSX/' . $filename . '_' . $page . '.xlsx', 'w' );
			$writer->save( $file );

			$spreadsheet->disconnectWorksheets();
			unset( $spreadsheet );
			return $upload_dir['baseurl'] . '/QuizAttemptsResult_XLSX/' . $filename . '_' . $page . '.xlsx';
		}

		public function parse_xls_learner_data( $data ) {
			$table = array(
				0 => array(
					0 => array(
						'value' => '',
						'font'  => array(),
					),
				),
			);

			// For row number.
			$index = 0;
			foreach ( $data as $quiz_id => $result ) {
				foreach ( $result as $course_id => $statistics_data ) {
					if ( empty( get_the_title( $quiz_id ) ) ) {
						continue 2;
					}
					$group_title = '';
					if ( isset( $_POST['fields']['group_filter'] ) ) {
						$group_id    = $_POST['fields']['group_filter'];
						$group_title = get_the_title( $_POST['fields']['group_filter'] );
					}
					$post_terms   = wp_get_object_terms( $course_id, 'ld_course_category', array( 'fields' => 'ids' ) );
					$separator    = ', ';
					$course_terms = '';
					if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
						$term_ids     = implode( ',', $post_terms );
						$course_terms = wp_list_categories(
							array(
								'title_li' => '',
								'style'    => 'none',
								'echo'     => false,
								'taxonomy' => 'ld_course_category',
								'include'  => $term_ids,
							)
						);

						$course_terms = trim( strip_tags( rtrim( trim( str_replace( '<br />', $separator, $course_terms ) ), $separator ) ) );
					}
					$post_terms = wp_get_object_terms( $quiz_id, 'ld_quiz_category', array( 'fields' => 'ids' ) );
					$quiz_terms = '';
					if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
						$term_ids   = implode( ',', $post_terms );
						$quiz_terms = wp_list_categories(
							array(
								'title_li' => '',
								'style'    => 'none',
								'echo'     => false,
								'taxonomy' => 'ld_quiz_category',
								'include'  => $term_ids,
							)
						);

						$quiz_terms = trim( strip_tags( rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator ) ) );
					}

					$table[ $index ][0]['value'] = __( 'Quiz Name: ', 'learndash-reports-pro' ) . get_the_title( $quiz_id );
					$table[ $index ][1]['value'] = __( 'Total Points: ', 'learndash-reports-pro' ) . reset( $statistics_data )['summary']['gpoints'];
					$table[ $index ][2]['value'] = __( 'Course: ', 'learndash-reports-pro' ) . get_the_title( $course_id );
					$table[ $index ][3]['value'] = __( 'Group: ', 'learndash-reports-pro' ) . $group_title;
					$table[ $index ][4]['value'] = __( 'Course Category: ', 'learndash-reports-pro' ) . $course_terms;
					$table[ $index ][5]['value'] = __( 'Quiz Category: ', 'learndash-reports-pro' ) . $quiz_terms;
					$table[ $index ][0]['font']  = array( 'bold' => 1 );
					$table[ $index ][1]['font']  = array( 'bold' => 1 );
					$table[ $index ][2]['font']  = array( 'bold' => 1 );
					$table[ $index ][3]['font']  = array( 'bold' => 1 );
					$table[ $index ][4]['font']  = array( 'bold' => 1 );
					$table[ $index ][5]['font']  = array( 'bold' => 1 );
					// For next row.
					$index++;
					$qno                         = 1;
					$inner_index                 = 7;
					$table[ $index ][0]['value'] = __( 'User ID', 'learndash-reports-pro' );
					$table[ $index ][1]['value'] = __( 'Username', 'learndash-reports-pro' );
					$table[ $index ][2]['value'] = __( 'Full Name', 'learndash-reports-pro' );
					$table[ $index ][3]['value'] = __( 'Attempted on', 'learndash-reports-pro' );
					$table[ $index ][4]['value'] = __( 'Total Time Taken', 'learndash-reports-pro' );
					$table[ $index ][5]['value'] = __( 'Pass / Fail', 'learndash-reports-pro' );
					$table[ $index ][6]['value'] = __( 'Earned Points', 'learndash-reports-pro' );
					$table[ $index ][7]['value'] = __( 'Score(%)', 'learndash-reports-pro' );
					$table[ $index ][0]['font']  = array( 'bold' => 1 );
					$table[ $index ][1]['font']  = array( 'bold' => 1 );
					$table[ $index ][2]['font']  = array( 'bold' => 1 );
					$table[ $index ][3]['font']  = array( 'bold' => 1 );
					$table[ $index ][4]['font']  = array( 'bold' => 1 );
					$table[ $index ][5]['font']  = array( 'bold' => 1 );
					$table[ $index ][6]['font']  = array( 'bold' => 1 );
					foreach ( $statistics_data[0]['detail'] as $question ) {
						$table[ $index ][ $inner_index ]['value']     = __( 'Question ', 'learndash-reports-pro' ) . $qno . ': ' . $question['question'];
						$table[ $index ][ $inner_index + 1 ]['value'] = __( 'Answered Correctly?', 'learndash-reports-pro' );
						$table[ $index ][ $inner_index + 2 ]['value'] = __( 'Points Scored', 'learndash-reports-pro' );
						$table[ $index ][ $inner_index + 3 ]['value'] = __( 'Time Taken(in sec)', 'learndash-reports-pro' );
						// $table[ $index ][ $inner_index ]['font'] = array( 'bold' => 1 );
						// $table[ $index ][ $inner_index + 1 ]['font'] = array( 'bold' => 1 );
						// $table[ $index ][ $inner_index + 2 ]['font'] = array( 'bold' => 1 );
						// $table[ $index ][ $inner_index + 3 ]['font'] = array( 'bold' => 1 );
						$inner_index += 4;
						$qno++;
					}
					// For next row.
					$index++;
					foreach ( $statistics_data as $entry ) {
						$user                        = get_user_by( 'id', $entry['summary']['user_id'] );
						$table[ $index ][0]['value'] = $entry['summary']['user_id'];
						$table[ $index ][1]['value'] = $user->user_login;
						$table[ $index ][2]['value'] = $user->display_name;
						$table[ $index ][3]['value'] = date_i18n( 'Y-m-j H:i:s', $entry['summary']['create_time'] );
						$table[ $index ][4]['value'] = $entry['summary']['question_time'];

						$quiz_post_settings = learndash_get_setting( $entry['result']['quiz_post_id'] );
						if ( ! is_array( $quiz_post_settings ) ) {
							$quiz_post_settings = array();
						}
						if ( ! isset( $quiz_post_settings['passingpercentage'] ) ) {
							$quiz_post_settings['passingpercentage'] = 0;
						}
						$passingpercentage = absint( $quiz_post_settings['passingpercentage'] );
						$pass              = 0;
						if ( $entry['summary']['points'] / $entry['summary']['gpoints'] * 100 >= $passingpercentage ) {
							$pass = 1;
						}
						$table[ $index ][5]['value'] = $pass ? __( 'Pass', 'learndash-reports-pro' ) : __( 'Fail', 'learndash-reports-pro' );

						$table[ $index ][6]['value'] = $entry['summary']['points'];
						$table[ $index ][7]['value'] = number_format( (float) $entry['summary']['points'] / $entry['summary']['gpoints'] * 100, 2, '.', '' );
						$inner_index                 = 7;
						foreach ( $entry['detail'] as $question ) {
							$table[ $index ][ $inner_index ]['value'] = implode( ', ', $this->get_user_response( $question ) );

							$answered_correctly = '';
							if ( $question['qspoints'] >= $question['points'] ) {
								$answered_correctly = __( 'Yes', 'learndash-reports-pro' );
							} elseif ( $question['answer_type'] == 'essay' ) {
								$graded_status = '';
								if ( ! empty( json_decode( $question['qsanswer_data'] ) ) ) {
									$graded_id     = json_decode( $question['qsanswer_data'] )->graded_id;
									$graded_status = get_post_status( $graded_id );
								}
								if ( 'not_graded' == $graded_status ) {
									$answered_correctly = __( 'Not Graded', 'learndash-reports-pro' );
								} elseif ( $question['qspoints'] > 0 ) {
									$answered_correctly = __( 'Partially', 'learndash-reports-pro' );
								} else {
									$answered_correctly = __( 'No', 'learndash-reports-pro' );
								}
							} elseif ( $question['qspoints'] > 0 ) {
								$answered_correctly = __( 'Partially', 'learndash-reports-pro' );
							} else {
								$answered_correctly = __( 'No', 'learndash-reports-pro' );
							}
							$table[ $index ][ $inner_index + 1 ]['value'] = $answered_correctly;
							$table[ $index ][ $inner_index + 2 ]['value'] = $question['qspoints'];
							$table[ $index ][ $inner_index + 3 ]['value'] = $question['question_time'];
							$inner_index                                 += 4;
						}
						$index++;
					}
					$index++;
				}
			}
			return $table;
		}

		public function parse_xls_data( $data ) {
			$table = array(
				0 => array(
					0 => array(
						'value' => '',
						'font'  => array(),
					),
				),
			);

			// For row number.
			$index                        = 0;
			$table[ $index ][0]['value']  = __( 'User ID', 'learndash-reports-pro' );
			$table[ $index ][1]['value']  = __( 'Username', 'learndash-reports-pro' );
			$table[ $index ][2]['value']  = __( 'Full Name', 'learndash-reports-pro' );
			$table[ $index ][3]['value']  = __( 'User Email', 'learndash-reports-pro' );
			$table[ $index ][4]['value']  = __( 'Quiz Title', 'learndash-reports-pro' );
			$table[ $index ][5]['value']  = __( 'Quiz Category', 'learndash-reports-pro' );
			$table[ $index ][6]['value']  = __( 'Date of Attempt', 'learndash-reports-pro' );
			$table[ $index ][7]['value']  = __( 'Time Taken(in sec)', 'learndash-reports-pro' );
			$table[ $index ][8]['value']  = __( 'Pass / Fail', 'learndash-reports-pro' );
			$table[ $index ][9]['value']  = __( 'Earned Points', 'learndash-reports-pro' );
			$table[ $index ][10]['value'] = __( 'Total Points Available', 'learndash-reports-pro' );
			$table[ $index ][11]['value'] = __( 'Score(%)', 'learndash-reports-pro' );
			$table[ $index ][12]['value'] = __( 'Course', 'learndash-reports-pro' );
			$table[ $index ][13]['value'] = __( 'Course Category', 'learndash-reports-pro' );
			$table[ $index ][14]['value'] = __( 'Group', 'learndash-reports-pro' );
			$table[ $index ][0]['font']   = array( 'bold' => 1 );
			$table[ $index ][1]['font']   = array( 'bold' => 1 );
			$table[ $index ][2]['font']   = array( 'bold' => 1 );
			$table[ $index ][3]['font']   = array( 'bold' => 1 );
			$table[ $index ][4]['font']   = array( 'bold' => 1 );
			$table[ $index ][5]['font']   = array( 'bold' => 1 );
			$table[ $index ][6]['font']   = array( 'bold' => 1 );
			$table[ $index ][7]['font']   = array( 'bold' => 1 );
			$table[ $index ][8]['font']   = array( 'bold' => 1 );
			$table[ $index ][9]['font']   = array( 'bold' => 1 );
			$table[ $index ][10]['font']  = array( 'bold' => 1 );
			$table[ $index ][11]['font']  = array( 'bold' => 1 );
			$table[ $index ][12]['font']  = array( 'bold' => 1 );
			$table[ $index ][13]['font']  = array( 'bold' => 1 );
			// For next row.
			$index++;
			foreach ( $data as $entry ) {
				$td                          = array();
				$user                        = get_user_by( 'id', $entry['summary']['user_id'] );
				$table[ $index ][0]['value'] = $entry['summary']['user_id'];
				$table[ $index ][1]['value'] = $user->user_login;
				$table[ $index ][2]['value'] = $user->display_name;
				$table[ $index ][3]['value'] = $user->user_email;

				$table[ $index ][4]['value'] = get_the_title( $entry['result']['quiz_post_id'] );

				$post_terms = wp_get_object_terms( $entry['result']['quiz_post_id'], 'ld_quiz_category', array( 'fields' => 'ids' ) );
				$separator  = ', ';
				$terms      = '';
				if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
					$term_ids = implode( ',', $post_terms );
					$terms    = wp_list_categories(
						array(
							'title_li' => '',
							'style'    => 'none',
							'echo'     => false,
							'taxonomy' => 'ld_quiz_category',
							'include'  => $term_ids,
						)
					);

					$terms = trim( strip_tags( rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator ) ) );
				}
				$table[ $index ][5]['value'] = $terms;

				$table[ $index ][6]['value'] = date_i18n( 'Y-m-j H:i:s', $entry['summary']['create_time'] );
				$table[ $index ][7]['value'] = $entry['summary']['question_time'];

				$quiz_post_settings = learndash_get_setting( $entry['result']['quiz_post_id'] );
				if ( ! is_array( $quiz_post_settings ) ) {
					$quiz_post_settings = array();
				}
				if ( ! isset( $quiz_post_settings['passingpercentage'] ) ) {
					$quiz_post_settings['passingpercentage'] = 0;
				}
				$passingpercentage = absint( $quiz_post_settings['passingpercentage'] );
				$pass              = 0;
				if ( $entry['summary']['points'] / $entry['summary']['gpoints'] * 100 >= $passingpercentage ) {
					$pass = 1;
				}
				$table[ $index ][8]['value'] = $pass ? __( 'Pass', 'learndash-reports-pro' ) : __( 'Fail', 'learndash-reports-pro' );

				$table[ $index ][9]['value']  = $entry['summary']['points'];
				$table[ $index ][10]['value'] = $entry['summary']['gpoints'];
				$table[ $index ][11]['value'] = number_format( (float) $entry['summary']['points'] / $entry['summary']['gpoints'] * 100, 2, '.', '' );
				$table[ $index ][12]['value'] = get_the_title( $entry['result']['course_post_id'] );

				$post_terms = wp_get_object_terms( $entry['result']['course_post_id'], 'ld_course_category', array( 'fields' => 'ids' ) );
				$separator  = ', ';
				$terms      = '';
				if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
					$term_ids = implode( ',', $post_terms );
					$terms    = wp_list_categories(
						array(
							'title_li' => '',
							'style'    => 'none',
							'echo'     => false,
							'taxonomy' => 'ld_course_category',
							'include'  => $term_ids,
						)
					);

					$terms = trim( strip_tags( rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator ) ) );
				}
				$table[ $index ][13]['value'] = $terms;

				$group_title = '';
				if ( isset( $_POST['fields']['group_filter'] ) ) {
					$group_id    = $_POST['fields']['group_filter'];
					$group_title = get_the_title( $_POST['fields']['group_filter'] );
				}
				$table[ $index ][14]['value'] = $group_title;
				$index++;
			}
			return $table;
		}

		/**
		 * This method is used to fetch custom reports results.
		 */
		public function qre_get_filters() {
			$nonce = filter_input( INPUT_GET, 'security', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'fetch_custom_reports' ) ) {
				$error = new WP_Error( 403, __( 'Security check failed. Please try again later.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			wp_suspend_cache_addition( true );
			$defaults       = array(
				'category_filter'    => -1,
				'course_filter'      => -1,
				'enrollment_from'    => false,
				'enrollment_to'      => false,
				'completion_from'    => false,
				'completion_to'      => false,
				'course_title'       => 'yes',
				'completion_status'  => 'yes',
				'completion_date'    => false,
				'course_category'    => false,
				'last_activity'      => false,
				'enrollment_date'    => false,
				'course_progress'    => false,
				'group_filter'       => -1,
				'group_name'         => false,
				'user_name'          => 'yes',
				'user_email'         => false,
				'user_first_name'    => false,
				'user_last_name'     => false,
				'quiz_filter'        => -1,
				'quiz_status'        => 'yes',
				'quiz_title'         => 'yes',
				'quiz_category'      => 'yes',
				'quiz_points_total'  => 'yes',
				'quiz_points_earned' => 'yes',
				'quiz_score_percent' => 'yes',
				'date_of_attempt'    => 'yes',
				'time_taken'         => 'yes',
				'question_text'      => 'yes',
				'question_options'   => 'yes',
				'correct_answers'    => 'yes',
				'user_answers'       => 'yes',
				'question_type'      => 'yes',
			);
			$filter_options = get_user_meta( get_current_user_id(), 'qre_custom_reports_saved_query', true );
			$filter_options = wp_parse_args( $filter_options, $defaults );

			$data_fields        = array();
			$filter_fields      = array( 'category_filter', 'course_filter', 'enrollment_from', 'enrollment_to', 'completion_from', 'completion_to', 'group_filter', 'quiz_filter' );
			$filter_fields_data = array();
			foreach ( $filter_options as $option_key => $option_value ) {
				if ( 'yes' === $option_value ) {
					$data_fields[ $option_key ] = $option_value;
				}
				if ( in_array( $option_key, $filter_fields, true ) ) {
					$filter_fields_data[ $option_key ] = $option_value;
				}
			}

			if ( empty( $data_fields ) ) {
				$error = new WP_Error( 400, __( 'No data fields selected.', 'learndash-reports-pro' ) );
				wp_send_json_error( $error );
				die();
			}
			$user_managed_courses = qre_get_user_managed_group_courses();
			$current_user         = wp_get_current_user();

			if ( -1 !== (int) $filter_fields_data['quiz_filter'] ) {
				$is_quiz_accessible = qre_check_if_quiz_accessible( $filter_fields_data['quiz_filter'], $current_user, $user_managed_courses );
				if ( ! $is_quiz_accessible || is_wp_error( $is_quiz_accessible ) ) {
					$error = new WP_Error( 403, __( 'Requested resource not accessible.', 'learndash-reports-pro' ) );
					wp_send_json_error( $error );
					die();
				}
				$data = $this->get_data_by_quiz_id( $filter_fields_data, $data_fields );
				// Serial indexing for arrays to prevent getting converted to object type
				$data = array_values( $data );
				wp_send_json_success( $data );
				die();
			}
			if ( -1 !== (int) $filter_fields_data['course_filter'] ) {
				if ( ! sfwd_lms_has_access( $filter_fields_data['course_filter'] ) ) {
					$error = new WP_Error( 403, __( 'Requested resource not accessible.', 'learndash-reports-pro' ) );
					// Serial indexing for arrays to prevent getting converted to object type
					$data = array_values( $data );
					wp_send_json_error( $error );
					die();
				}
				$quizzes = learndash_course_get_steps_by_type( $filter_fields_data['course_filter'], 'sfwd-quiz' );
				$data    = array();
				if ( ! empty( $quizzes ) ) {
					foreach ( $quizzes as $quiz_id ) {
						$filter_fields_data['quiz_filter'] = $quiz_id;
						$data                              = array_merge( $data, $this->get_data_by_quiz_id( $filter_fields_data, $data_fields ) );
					}
					$filter_fields_data['quiz_filter'] = -1;
				}
				// Serial indexing for arrays to prevent getting converted to object type
				$data = array_values( $data );
				wp_send_json_success( $data );
				die();
			}
			if ( -1 !== (int) $filter_fields_data['group_filter'] ) {
				$user_group_ids = learndash_get_administrators_group_ids( $current_user->ID );
				$user_group_ids = array_map( 'absint', $user_group_ids );
				if ( ! in_array( (int) $filter_fields_data['group_filter'], $user_group_ids, true ) ) {
					$error = new WP_Error( 403, __( 'Requested resource not accessible.', 'learndash-reports-pro' ) );
					wp_send_json_error( $error );
					die();
				}
				$group_courses = learndash_group_enrolled_courses( $filter_fields_data['group_filter'] );
				$data          = array();
				foreach ( $group_courses as $course_id ) {
					$quizzes = learndash_course_get_steps_by_type( $course_id, 'sfwd-quiz' );
					if ( empty( $quizzes ) ) {
						continue;
					}
					foreach ( $quizzes as $quiz_id ) {
						$filter_fields_data['quiz_filter'] = $quiz_id;
						$data                              = array_merge( $data, $this->get_data_by_quiz_id( $filter_fields_data, $data_fields ) );
					}
					$filter_fields_data['quiz_filter'] = -1;
				}
				// Serial indexing for arrays to prevent getting converted to object type
				$data = array_values( $data );
				wp_send_json_success( $data );
				die();
			}
			$quizzes = get_posts(
				array(
					'post_type'   => 'sfwd-quiz',
					'numberposts' => -1,
					'fields'      => 'ids',
				)
			);
			$data    = array();
			foreach ( $quizzes as $quiz_id ) {
				if ( ! qre_check_if_quiz_accessible( $quiz_id, $current_user, $user_managed_courses ) ) {
					continue;
				}
				$filter_fields_data['quiz_filter'] = $quiz_id;
				$data                              = array_merge( $data, $this->get_data_by_quiz_id( $filter_fields_data, $data_fields ) );
			}
			$filter_fields_data['quiz_filter'] = -1;
			// Serial indexing for arrays to prevent getting converted to object type
			$data = array_values( $data );
			wp_send_json_success( $data );
			die();
		}

		/**
		 * This method is used to get Custom Reports Data by Quiz ID.
		 *
		 * @param  array $filter_fields Filtering Fields.
		 * @param  array $data_fields   Data Fields.
		 * @return array/null Custom Reports Data.
		 */
		public function get_data_by_quiz_id( $filter_fields, $data_fields ) {
			$quiz_id   = (int) $filter_fields['quiz_filter'];
			$course_id = learndash_get_course_id( $quiz_id );
			$data      = array();
			if ( -1 !== (int) $filter_fields['course_filter'] ) {
				if ( $course_id !== (int) $filter_fields['course_filter'] ) {
					return $data;
				}
			}
			if ( -1 !== (int) $filter_fields['group_filter'] ) {
				if ( ! learndash_group_has_course( $filter_fields['group_filter'], $course_id ) ) {
					return $data;
				}
			}
			// $groups      = learndash_get_course_groups( $course_id );
			$quiz_pro_id    = get_post_meta( $quiz_id, 'quiz_pro_id', true );
			$args           = array(
				'limit' => false,
			);
			$statistics     = Quiz_Export_Db::instance()->get_all_statistic_ref_ids_by_quiz( $quiz_pro_id, $args );
			$statistic_data = array_filter(
				array_map(
					function( $statistic ) use ( $data_fields, $course_id, $quiz_id, $filter_fields ) {
						if ( ! array_key_exists( 'statistic_ref_id', $statistic ) ) {
							return null;
						}
						$current_user         = wp_get_current_user();
						$user_managed_courses = qre_get_user_managed_group_courses();
						$user_id              = (int) $statistic['user_id'];
						$user_info            = get_userdata( $user_id );
						$is_user_accessible   = qre_check_if_user_accessible( $user_id, $current_user, $user_managed_courses );
						if ( ! $is_user_accessible || is_wp_error( $is_user_accessible ) ) {
							return null;
						}
						$user_enrolled_course_ids = learndash_user_get_enrolled_courses( $user_id, array(), true );
						// Check for Enrollment.
						if ( ! in_array( $course_id, $user_enrolled_course_ids ) ) {// phpcs:ignore
							return null;
						}
						$courses_access_from = ld_course_access_from( $course_id, $user_id );
						if ( empty( $courses_access_from ) ) {
							$courses_access_from = learndash_user_group_enrolled_to_course_from( $user_id, $course_id, false );
						}
						if ( ! empty( $filter_fields['enrollment_from'] ) ) {
							$enrollment_after = strtotime( $filter_fields['enrollment_from'] );
							if ( $courses_access_from < $enrollment_after ) {
								return null;
							}
						}
						if ( ! empty( $filter_fields['enrollment_to'] ) ) {
							$enrollment_to = strtotime( $filter_fields['enrollment_to'] );
							if ( $courses_access_from >= $enrollment_to ) {
								return null;
							}
						}
						$course_completion_date = learndash_user_get_course_completed_date( $user_id, $course_id );
						if ( ! empty( $filter_fields['completion_from'] ) ) {
							$completion_after = strtotime( $filter_fields['completion_from'] );
							if ( $course_completion_date < $completion_after ) {
								return null;
							}
						}
						if ( ! empty( $filter_fields['completion_to'] ) ) {
							$completion_to = strtotime( $filter_fields['completion_to'] );
							if ( $course_completion_date >= $completion_to ) {
								return null;
							}
						}
						$data = array();
						if ( array_key_exists( 'course_title', $data_fields ) ) {
							$data['course_title'] = get_the_title( $course_id );
						}
						if ( array_key_exists( 'completion_status', $data_fields ) ) {
							$data['completion_status'] = learndash_course_status( $course_id, $user_id );
						}
						if ( array_key_exists( 'completion_date', $data_fields ) ) {
							if ( 0 === (int) $course_completion_date ) {
								$course_completion_date = '-';
							} else {
								$course_completion_date = date_i18n( get_option( 'date_format' ), $course_completion_date );
							}
							$data['completion_date'] = $course_completion_date;
						}
						if ( array_key_exists( 'course_category', $data_fields ) ) {
							$category                = wp_get_post_terms( $course_id, 'ld_course_category', array( 'fields' => 'names' ) );
							$data['course_category'] = '-';
							if ( ! is_wp_error( $category ) ) {
								$data['course_category'] = implode( ', ', $category );
							}
						}
						if ( array_key_exists( 'enrollment_date', $data_fields ) ) {
							$data['enrollment_date'] = date_i18n( get_option( 'date_format', 'd-M-Y' ), $courses_access_from );
						}
						if ( array_key_exists( 'course_progress', $data_fields ) ) {
							$progress = learndash_course_progress(
								array(
									'course_id' => $course_id,
									'user_id'   => $user_id,
									'array'     => true,
								)
							);
							if ( '' === $progress ) {
								$progress = '-';
							} else {
								$progress = $progress['percentage'] . '%';
							}
							$data['course_progress'] = $progress;
						}
						if ( array_key_exists( 'group_name', $data_fields ) ) {
							$user_group_ids = learndash_get_users_group_ids( $user_id, $bypass_transient );

							$user_group_ids = array_map( 'absint', $user_group_ids );

							$course_group_ids = learndash_get_course_groups( $course_id );

							$course_group_ids = array_map( 'absint', $course_group_ids );

							$course_group_ids   = array_intersect( $course_group_ids, $user_group_ids );
							$data['group_name'] = get_the_title( current( $course_group_ids ) );
						}
						if ( array_key_exists( 'user_name', $data_fields ) ) {
							$data['user_name'] = $user_info->display_name;
						}
						if ( array_key_exists( 'user_email', $data_fields ) ) {
							$data['user_email'] = $user_info->user_email;
						}
						if ( array_key_exists( 'user_first_name', $data_fields ) ) {
							$data['user_first_name'] = $user_info->first_name;
						}
						if ( array_key_exists( 'user_last_name', $data_fields ) ) {
							$data['user_last_name'] = $user_info->last_name;
						}
						if ( array_key_exists( 'quiz_title', $data_fields ) ) {
							$data['quiz_title'] = get_the_title( $quiz_id );
						}
						$quiz_data = array();
						if ( array_key_exists( 'quiz_status', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							$quiz_post_settings = learndash_get_setting( $quiz_id );
							if ( ! is_array( $quiz_post_settings ) ) {
								$quiz_post_settings = array();
							}
							if ( ! isset( $quiz_post_settings['passingpercentage'] ) ) {
								$quiz_post_settings['passingpercentage'] = 0;
							}
							$percentage        = (float) number_format( ( $quiz_data[0]['tot_points_scored'] * 100 ) / $quiz_data[0]['total_points'] );
							$passingpercentage = (float) number_format( $quiz_post_settings['passingpercentage'], 2 );
							$pass              = ( $percentage >= $passingpercentage ) ? __( 'PASS', 'learndash-reports-pro' ) : __( 'FAIL', 'learndash-reports-pro' );

							$data['quiz_status'] = $pass;
						}
						if ( array_key_exists( 'quiz_category', $data_fields ) ) {
							$data['quiz_category'] = '-';
							if ( taxonomy_exists( 'ld_quiz_category' ) ) {
								$data['quiz_category'] = wp_get_post_terms( $quiz_id, 'ld_quiz_category', array( 'fields' => 'names' ) );
							}
						}
						if ( array_key_exists( 'quiz_points_total', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							$data['quiz_points_total'] = $quiz_data[0]['total_points'];
						}
						if ( array_key_exists( 'quiz_points_earned', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( (int) $statistic['statistic_ref_id'], $nonce );
							}
							$data['quiz_points_earned'] = $quiz_data[0]['tot_points_scored'];
						}
						if ( array_key_exists( 'quiz_score_percent', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							$data['quiz_score_percent'] = number_format( $data['quiz_points_earned'] / $data['quiz_points_total'] * 100, 2 );
						}
						if ( array_key_exists( 'date_of_attempt', $data_fields ) ) {
							$data['date_of_attempt'] = date_i18n( get_option( 'date_format', 'd-M-Y' ), $statistic['create_time'] );
						}
						if ( array_key_exists( 'time_taken', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							$data['time_taken'] = gmdate( 'H:i:s', $quiz_data[0]['tot_time_taken'] );
						}
						if ( array_key_exists( 'question_text', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							/**
							 * Commented Code.
							 * foreach ( $quiz_data[0]['question_meta'] as $qkey => $qval ) {
								$data['question_text'][ $qval['question_id'] ][] = str_replace( '&#39;', "'", html_entity_decode( $qval['question'] ) );
							}*/
							$data['question_meta'] = $quiz_data[0]['question_meta'];
						}
						if ( array_key_exists( 'question_options', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							/**
							 * Commented Code.
							 * foreach ( $quiz_data[0]['question_meta'] as $qkey => $qval ) {
								$data['question_options'][ $qval['question_id'] ][] = str_replace( '&#39;', "'", html_entity_decode( Export_File_Processing::instance()->append_qstn_str( $qval['answers'] ) ) );
							}*/
							$data['question_meta'] = $quiz_data[0]['question_meta'];
						}
						if ( array_key_exists( 'correct_answers', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							/**
							 * Commented Code.
							 * foreach ( $quiz_data[0]['question_meta'] as $qkey => $qval ) {
								$data['correct_answers'][ $qval['question_id'] ][] = str_replace( '&#39;', "'", html_entity_decode( Export_File_Processing::instance()->append_qstn_str( $qval['correct_answers'] ) ) );
							}*/
							$data['question_meta'] = $quiz_data[0]['question_meta'];
						}
						if ( array_key_exists( 'user_answers', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							/**
							 * Commented Code.
							 * [$question_str description]
								$question_str = '';
								foreach ( $qval['user_response'] as $answer ) {
									// user response.
									if ( '' !== $answer ) {
										$question_str .= str_replace( '&#39;', "'", html_entity_decode( $answer ) );
									}
								}
								$data['user_answers'][ $qval['question_id'] ][] = $question_str;
							}*/
							$data['question_meta'] = $quiz_data[0]['question_meta'];
						}
						if ( array_key_exists( 'question_type', $data_fields ) ) {
							if ( empty( $quiz_data ) ) {
								$nonce     = wp_create_nonce( 'quiz_export-' . get_current_user_id() );
								$quiz_data = Quiz_Export_Data::instance()->qre_export_data_generation( $statistic['statistic_ref_id'], $nonce )['arr_data'];
							}
							/**
							 * Commented Code.
							 * foreach ( $quiz_data[0]['question_meta'] as $qkey => $qval ) {
								$data['question_type'][ $qval['question_id'] ][] = $qval['question_type'];
								}
							 */
							$data['question_meta'] = $quiz_data[0]['question_meta'];
						}
						return $data;
					},
					$statistics
				)
			);
			return $statistic_data;
		}

		/**
		 * To get the statistic_ref_id we used creation_time of the quiz, because in the LearnDash version 2.0.6.3, auto generation of statistic_ref_id facility is not provided.
		 *
		 * @param integer $user_id       User ID.
		 * @param integer $pro_quiz_id   Pro Quiz ID.
		 * @param integer $statistics_id Statistics ID.
		 */
		public function display_attempted_questions( $user_id, $pro_quiz_id, $statistics_id ) {
			if ( ! empty( $statistics_id ) ) {
				$questions     = Quiz_Export_Data::instance()->get_statistics_data( $pro_quiz_id, $user_id, $statistics_id, false );
				$questions_key = array();
				if ( ! empty( $questions ) ) {
					$questions_key = array_keys( $questions );
				}
				if ( ! empty( $questions_key ) ) {
					$counter = 0;
					?>
					<div class="learndash">
						<div class="learndash-wrapper">
							<div class="wpProQuiz_content">
								<?php
								// echo $this->show_result_box( $que_count, $pro_quiz_id, $statistics_id );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.
								?>
								<div class="wpProQuiz_quiz">
									<span class="questions_heading"><?php echo esc_html( learndash_get_custom_label( 'questions' ) ); ?></span>
									<?php
									foreach ( $questions_key as $key ) {
										foreach ( $questions[ $key ]['questions'] as $value ) {
											$question_id   = $value['question_id'];
											$question_text = ! empty( $value['questionName'] ) ? $value['questionName'] : Quiz_Export_Data::instance()->get_question_text( $pro_quiz_id, $question_id );
											$cmsg          = Quiz_Export_Data::instance()->get_correct_message( $question_id, $pro_quiz_id );
											echo $this->show_user_answer( $question_text, $value['questionAnswerData'], $value['statistcAnswerData'], $value['answerType'], ++$counter, $value['correct'], $cmsg );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<?php
					$que_count = count( $questions[ $key ]['questions'] );
				}
			} else {
				?>
				<center>
					<?php echo esc_html__( 'Statistics not found', 'learndash-reports-pro' ); ?>
				</center>
				<?php
			}
		}

		/**
		 * This function is used to show the the Result section.
		 *
		 * @internal Not in Use currently.
		 * @param  integer $que_count     Question count.
		 * @param  integer $pqid          Pro quiz ID.
		 * @param  integer $statistics_id Statistics Reference ID.
		 */
		public function show_result_box( $que_count, $pqid, $statistics_id ) {
			// Primary Data.
			$qcount  = Quiz_Export_Db::instance()->get_total_questions_count( $statistics_id );
			$qscore  = Quiz_Export_Db::instance()->get_correct_questions_count( $statistics_id );
			$qpoints = Quiz_Export_Db::instance()->get_points_earned( $statistics_id );
			$qids    = Quiz_Export_Db::instance()->get_questions_asked( $statistics_id );
			$qtime   = Quiz_Export_Db::instance()->get_quiz_time_taken( $statistics_id );

			// Derived Data.
			$total_points = Quiz_Export_Db::instance()->get_quiz_total_points( $qids );
			$count        = $qcount[0]->count;
			$score        = $qscore[0]->score;
			$points       = $qpoints[0]->points;
			$per          = round( ( $points / $total_points ) * 100, 2 );
			$seconds      = $qtime[0]->quet;
			$time         = round( $seconds );

			$statistic_controller = new \WpProQuiz_Controller_Statistics();
			$avg_res              = $statistic_controller->getAverageResult( $pqid );
			$avg_per              = round( $avg_res, 2 );

			include LDRP_PLUGIN_DIR . 'includes/views/result-box.php';

			unset( $que_count );
		}

		/**
		 * This function is used to show the anser to the user in a HTML format
		 *
		 * @param  string  $question_text Question Text.
		 * @param  array   $q_answer_data Question Answer Data.
		 * @param  string  $s_answer_data Answer Data.
		 * @param  string  $answer_type   Answer Type.
		 * @param  integer $question_no   Question ID.
		 * @param  boolean $is_correct    Answer is correct or not.
		 * @param  string  $cmg            Correect Message.
		 */
		public function show_user_answer( $question_text, $q_answer_data, $s_answer_data, $answer_type, $question_no, $is_correct, $cmg = null ) {
			$matrix = array();
			if ( 'matrix_sort_answer' === $answer_type ) {
				foreach ( $q_answer_data as $ans_key => $ans_value ) {
					$matrix[ $ans_key ][] = $ans_key;
					foreach ( $q_answer_data as $ans_key2 => $ans_value2 ) {
						if ( $ans_key !== $ans_key2 ) {
							if ( $ans_value->getAnswer() === $ans_value2->getAnswer() ) {
								$matrix[ $ans_key ][] = $ans_key2;
							} elseif ( $ans_value->getSortString() === $ans_value2->getSortString() ) {
								$matrix[ $ans_key ][] = $ans_key2;
							}
						}
					}
				}
			}
			?>
			<div class="wpProQuiz_question">
				<div class="wpProQuiz_question_text">
					<span><?php echo esc_html( $question_no . '. ' ); ?><?php echo $question_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
				<?php
				if ( 'matrix_sort_answer' === $answer_type ) {
					$this->display_matrix_sort_answer( $q_answer_data );
				}
				$this->get_question_list( $question_text, $q_answer_data, $s_answer_data, $answer_type, $question_no, $is_correct, $matrix, $cmg );
				?>
			</div>
			<?php
			if ( 'essay' !== $answer_type ) {
				?>
				<div class="wpProQuiz_response">
					<?php
					if ( $is_correct ) {
						?>
						<div class="wpProQuiz_correct">
							<span>
								<?php esc_html_e( 'Correct', 'learndash-reports-pro' ); ?>
							</span>
							<p class="wpProQuiz_AnswerMessage">
								<?php echo do_shortcode( apply_filters( 'comment_text', $cmg, null, null ) ); ?>
							</p>
						</div>
						<?php
					} else {
						?>
						<div class="wpProQuiz_incorrect">
							<span>
								<?php esc_html_e( 'Incorrect', 'learndash-reports-pro' ); ?>
							</span>
							<p class="wpProQuiz_AnswerMessage">
								<?php echo do_shortcode( apply_filters( 'comment_text', $cmg, null, null ) ); ?>
							</p>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
		}

		/**
		 * Get Matrix sort Answer structure.
		 *
		 * @param  array $q_answer_data Question Answer Data.
		 * @return void
		 */
		public function display_matrix_sort_answer( $q_answer_data ) {
			?>
			<div class="wpProQuiz_matrixSortString">
				<h5 class="wpProQuiz_header">
					<?php esc_html_e( 'Sort elements', 'learndash-reports-pro' ); ?>
				</h5>
				<ul class="wpProQuiz_sortStringList">
					<?php
					foreach ( $q_answer_data as $key => $value ) {
						?>
						<li class="wpProQuiz_sortStringItem" data-pos="<?php echo esc_attr( $key ); ?>">
							<?php
								echo $value->isSortStringHtml() ? $value->getSortString() : esc_html( $value->getSortString() );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</li>
						<?php
					}
					?>
				</ul>
				<div style="clear: both;"></div>
			</div>
			<?php
		}

		/**
		 * This function is used to show questions list.
		 *
		 * @param  string  $question_text Question Text.
		 * @param  array   $q_answer_data Question Answer Data.
		 * @param  string  $s_answer_data Answer Data.
		 * @param  string  $answer_type   Answer Type.
		 * @param  integer $question_no   Question ID.
		 * @param  boolean $is_correct    Answer is correct or not.
		 * @param  array   $matrix Matrix type question.
		 * @param  string  $cmg            Correect Message.
		 */
		public function get_question_list( $question_text, $q_answer_data, $s_answer_data, $answer_type, $question_no, $is_correct, $matrix, $cmg = null ) {
			?>
			<ul class="wpProQuiz_questionList">
			<?php
			$max_count = count( $q_answer_data );
			for ( $cnt = 0; $cnt < $max_count; $cnt++ ) {
				$answer_text = $q_answer_data[ $cnt ]->isHtml() ? $q_answer_data[ $cnt ]->getAnswer() : esc_html( $q_answer_data[ $cnt ]->getAnswer() );
				$correct     = '';
				?>
				<?php
				if ( 'single' === $answer_type || 'multiple' === $answer_type ) {
					$this->display_single_multiple_questions( $q_answer_data, $s_answer_data, $answer_text, $answer_type, $cnt );
				} elseif ( 'free_answer' === $answer_type ) {
					$this->display_free_type_answer( $s_answer_data, $q_answer_data, $cnt );
				} elseif ( 'sort_answer' === $answer_type ) {
					$correct   = 'wpProQuiz_answerIncorrect';
					$sort_text = '';

					if ( isset( $s_answer_data[ $cnt ] ) && isset( $q_answer_data[ $s_answer_data[ $cnt ] ] ) ) {
						if ( $s_answer_data[ $cnt ] == $cnt ) {
							$correct = 'wpProQuiz_answerCorrect';
						}
						$ans_val   = $q_answer_data[ $s_answer_data[ $cnt ] ];
						$sort_text = $ans_val->isHtml() ? $ans_val->getAnswer() : esc_html( $ans_val->getAnswer() );
					}
					?>
					<li class="wpProQuiz_questionListItem <?php echo esc_attr( $correct ); ?>" style="margin-left:0px;padding: 3px;">
						<div class="wpProQuiz_sortable qreProQuiz_sortable">
							<?php echo $sort_text;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</li>
					<?php
				} elseif ( 'matrix_sort_answer' === $answer_type ) {
					$correct   = 'wpProQuiz_answerIncorrect';
					$sort_text = '';
					if ( isset( $s_answer_data[ $cnt ] ) && isset( $q_answer_data[ $s_answer_data[ $cnt ] ] ) ) {
						if ( in_array( $s_answer_data[ $cnt ], $matrix[ $cnt ] ) ) {
							$correct = 'wpProQuiz_answerCorrect';
						}
						$ans_val   = $q_answer_data[ $s_answer_data[ $cnt ] ];
						$sort_text = $ans_val->isSortStringHtml() ? $ans_val->getSortString() : esc_html( $ans_val->getSortString() );
					}
					?>
					<li class="wpProQuiz_questionListItem <?php echo esc_attr( $correct ); ?>">
						<table>
							<tbody>
								<tr class="wpProQuiz_mextrixTr">
									<td style = "width:20%">
										<div class="wpProQuiz_maxtrixSortText">
											<?php echo $answer_text;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</div>
									</td>
									<td style = "width:80%">
										<ul class="wpProQuiz_maxtrixSortCriterion">
											<li class="wpProQuiz_sortStringItem" data-pos="0" style="box-shadow: 0px 0px; cursor: auto;">
												<?php echo $sort_text;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											</li>
										</ul>
									</td>
								</tr>
							</tbody>
						</table>
					</li>
					<?php
				} elseif ( 'cloze_answer' === $answer_type ) {
					$cloze_data = $this->display_cloze_type_questions( $q_answer_data[ $cnt ]->getAnswer(), $s_answer_data );
					echo $cloze_data['replace']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} elseif ( 'assessment_answer' === $answer_type ) {
					$assessment_data = $this->display_assessment( $q_answer_data[ $cnt ]->getAnswer(), $s_answer_data );
					echo $assessment_data['replace']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} elseif ( 'essay' === $answer_type ) {
					$this->display_essay_answer( $s_answer_data );
				}
			}
			?>
			</ul>
			<?php
			unset( $question_text );
			unset( $question_no );
			unset( $is_correct );
			unset( $cmg );
			unset( $matrix );
		}

		/**
		 * This method is used to display single/multiple choice questions.
		 *
		 * @param  array   $q_answer_data Question Answer data.
		 * @param  string  $s_answer_data Answer data.
		 * @param  string  $answer_text   Answer Text.
		 * @param  string  $answer_type   Answer Type.
		 * @param  integer $cnt           Counter.
		 * @return void
		 */
		public function display_single_multiple_questions( $q_answer_data, $s_answer_data, $answer_text, $answer_type, $cnt ) {
			$correct = '';
			if ( $q_answer_data[ $cnt ]->isCorrect() ) {
				$correct = 'wpProQuiz_answerCorrect';
			} elseif ( isset( $s_answer_data[ $cnt ] ) && $s_answer_data[ $cnt ] ) {
				$correct = 'wpProQuiz_answerIncorrect';
			}
			?>
			<li class="wpProQuiz_questionListItem <?php echo esc_attr( $correct ); ?>" style="margin-left:0px;padding: 3px;">
				<label>
					<input disabled="disabled" type="<?php echo 'single' === $answer_type ? 'radio' : 'checkbox'; ?>" <?php echo $s_answer_data[ $cnt ] ? 'checked="checked"' : ''; ?>>
					<?php echo $answer_text;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</label>
			</li>
			<?php
		}

		/**
		 * This method is used to display free type questions.
		 *
		 * @param  string  $s_answer_data Answer data.
		 * @param  array   $q_answer_data Question Answer data.
		 * @param  integer $cnt           Counter.
		 * @return void
		 */
		public function display_free_type_answer( $s_answer_data, $q_answer_data, $cnt ) {
			$term_ans = str_replace( "\r\n", "\n", strtolower( $q_answer_data[ $cnt ]->getAnswer() ) );
			$term_ans = str_replace( "\r", "\n", $term_ans );
			$term_ans = explode( "\n", $term_ans );
			$term_ans = array_values( array_filter( array_map( 'trim', $term_ans ) ) );
			if ( isset( $s_answer_data[0] ) && in_array( strtolower( trim( $s_answer_data[0] ) ), $term_ans ) ) {
				$correct = 'wpProQuiz_answerCorrect';
			} else {
				$correct = 'wpProQuiz_answerIncorrect';
			}
			?>
			<li class="wpProQuiz_questionListItem <?php echo esc_attr( $correct ); ?>" style="margin-left:0px;padding: 3px;">
				<label>
					<input type="text" disabled="disabled" style="width: 300px; padding: 3px;margin-bottom: 5px;" value="<?php echo isset( $s_answer_data[0] ) ? esc_attr( $s_answer_data[0] ) : ''; ?>">
				</label>
				<br>
			</li>
			<?php
		}

		/**
		 * This method is used to display cloze type questions.
		 *
		 * @param  string $answer_text Answer Text.
		 * @param  array  $answer_data Answer Data.
		 * @return array  $data        Answer Data.
		 */
		public function display_cloze_type_questions( $answer_text, $answer_data ) {
			preg_match_all( '#\{(.*?)(?:\|(\d+))?(?:[\s]+)?\}#im', $answer_text, $matches, PREG_SET_ORDER );
			$data  = array();
			$index = 0;
			foreach ( $matches as $key => $value ) {
				$text            = $value[1];
				$points          = ! empty( $value[2] ) ? (int) $value[2] : 1;
				$row_text        = array();
				$multi_text_data = array();
				$len             = array();
				if ( preg_match_all( '#\[(.*?)\]#im', $text, $multi_text_matches ) ) {
					foreach ( $multi_text_matches[1] as $multi_text ) {
						$str_x             = mb_strtolower( trim( html_entity_decode( $multi_text, ENT_QUOTES ) ) );
						$len[]             = strlen( $str_x );
						$multi_text_data[] = $str_x;
						$row_text[]        = $multi_text;
					}
				} else {
					$str_x             = mb_strtolower( trim( html_entity_decode( $text, ENT_QUOTES ) ) );
					$len[]             = strlen( $str_x );
					$multi_text_data[] = $str_x;
					$row_text[]        = $text;
				}
				$correct           = $this->get_correct_class_name( $answer_data, $index, $row_text );
				$data['data'][]    = $this->get_cloze_answer_html( $correct, $answer_data, $index, $row_text );
				$data['correct'][] = $multi_text_data;
				$data['points'][]  = $points;
				$index++;
			}
			foreach ( $data['data'] as $key => $value ) {
				$answer_text = preg_replace( '/\{[^}]+\}/', $value, $answer_text, 1 );
			}
			$data['replace'] = $answer_text;
			return $data;
		}

		/**
		 * Get Correct/Incorrect class name.
		 *
		 * @param  array   $answer_data Answer Data.
		 * @param  integer $index       Index for numeric array.
		 * @param  array   $row_text    Row Text Array.
		 * @return string  $correct     Class Name.
		 */
		public function get_correct_class_name( $answer_data, $index, $row_text ) {
			$correct = 'wpProQuiz_answerIncorrect';
			if ( isset( $answer_data[ $index ] ) && in_array( $answer_data[ $index ], $row_text ) ) {
				$correct = 'wpProQuiz_answerCorrect';
			}
			return $correct;
		}

		/**
		 * Get Cloze Type Answer HTML.
		 *
		 * @param  string  $correct     Class Name.
		 * @param  array   $answer_data Answer Data.
		 * @param  integer $index       Numeric Index.
		 * @param  array   $row_text    Row Text Array.
		 * @return string  $ans_tag     Answer Tag HTML.
		 */
		public function get_cloze_answer_html( $correct, $answer_data, $index, $row_text ) {
			$ans_tag  = '<span class="wpProQuiz_cloze ' . $correct . '">' . esc_html( isset( $answer_data[ $index ] ) ? empty( $answer_data[ $index ] ) ? '---' : $answer_data[ $index ] : '---' ) . '</span>';
			$ans_tag .= '<span>(' . implode( ', ', $row_text ) . ')</span>';
			return $ans_tag;
		}

		/**
		 * This function is used to fetch the assessment type question data
		 *
		 * @param  string $answer_text Answer Text.
		 * @param  string $answer_data Answer Data.
		 * @return array  $data        Answer Data.
		 */
		public function display_assessment( $answer_text, $answer_data ) {
			preg_match_all( '#\{(.*?)\}#im', $answer_text, $matches );
			$data = array();
			for ( $count = 0, $count_1 = count( $matches[1] ); $count < $count_1; $count++ ) {
				$match = $matches[1][ $count ];
				preg_match_all( '#\[([^\|\]]+)(?:\|(\d+))?\]#im', $match, $qre_ms );
				$ans_html = '';
				$checked  = isset( $answer_data[ $count ] ) ? $answer_data[ $count ] - 1 : -1;
				for ( $count_j = 0, $counterj = count( $qre_ms[1] ); $count_j < $counterj; $count_j++ ) {
					$ans_val   = $qre_ms[1][ $count_j ];
					$ans_html .= '<label><input type="radio" disabled="disabled" ' . ( $checked === $count_j ? 'checked="checked"' : '' ) . '>' . $ans_val . '</label>';
				}
			}
			$data['replace'] = $ans_html;
			return $data;
		}

		/**
		 * This method is used to display essay type questions.
		 *
		 * @param  array $s_answer_data Answer Data.
		 * @return void
		 */
		public function display_essay_answer( $s_answer_data ) {
			if ( ( isset( $s_answer_data['graded_id'] ) ) && ( ! empty( $s_answer_data['graded_id'] ) ) ) {
				$essay_post_status     = get_post_status( $s_answer_data['graded_id'] );
				$essay_post_status_str = '';
				if ( 'graded' === $essay_post_status ) {
					$essay_post_status_str = __( 'Graded', 'learndash-reports-pro' );
				} else {
					$essay_post_status_str = __( 'Not Graded', 'learndash-reports-pro' );
				}
				?>
				<li class="wpProQuiz_questionListItem">
					<div class="wpProQuiz_sortable">
						<?php
						echo esc_html__( 'Status', 'learndash-reports-pro' ) . ' : ' . esc_html( $essay_post_status_str );
						if ( current_user_can( 'edit_post', $s_answer_data['graded_id'] ) ) {
							?>
							(<a target="_blank" href="<?php echo get_post_permalink( $s_answer_data['graded_id'] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'view', 'learndash-reports-pro' ); ?></a>)
							<?php
						}
						?>
					</div>
				</li>
				<?php
			}
		}
	}
}
