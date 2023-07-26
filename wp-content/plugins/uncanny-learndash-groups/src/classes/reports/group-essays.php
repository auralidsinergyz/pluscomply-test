<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class GroupEssays
 *
 * @package uncanny_learndash_groups
 */
class GroupEssays {

	/**
	 * Group essay shortcode.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public static $ulgm_essays_shortcode = array();
	/**
	 * Group drop down
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public static $group_drop_downs = false;
	/**
	 * Rest API root path
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $root_path = 'ulgm_essays_report/v1';

	/**
	 * DataTable options for the table in this report
	 */
	private static $table_options = array();

	/**
	 * Class constructor
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'run_frontend_hooks' ) );
		//register api class
		add_action( 'rest_api_init', array( $this, 'uo_api' ) );
		// register ajax call
		add_action( 'init', array( $this, '_essay_bulk_actions_approve' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	/**
	 *
	 */
	public function run_frontend_hooks() {
		add_shortcode( 'uo_groups_essays', array( $this, 'display_essays' ) );
	}

	/*
	 * Display the shortcode
	 * @param array $attributes
	 *
	 * @return string $html header and table
	 */
	/**
	 * @param $request
	 *
	 * @return false|string|void
	 */
	public function display_essays( $request ) {
		$request = shortcode_atts(
			array(
				'columns'             => 'Title, First name, Last name, Username, Status, Points, Question, Content, Course, Lesson, Quiz, Comments, Date',
				'status'              => 'ungraded',
				'excel_export_button' => 'hide',
				'csv_export_button'   => 'hide',

				'orderby_column'      => esc_attr__( 'Date', 'uncanny-learndash-groups' ), // The ID of the column used to sort
				'order_column'        => 'desc', // Designates the ascending or descending order of the ‘orderby‘ parameter
			),
			$request
		);
		$html    = $this->generate_essays_html( $request );
		$this->essays_scripts( $request );

		return $html;
	}

	/*
	 * Generate Essays HTML Output
	 *
	 * @return string
	 */
	/**
	 * @param array $request
	 *
	 * @return false|string|void
	 */
	public function generate_essays_html( $request = array() ) {

		self::$ulgm_essays_shortcode['text']['group_management_link'] = SharedFunctions::get_group_management_page_id( true );

		self::$ulgm_essays_shortcode['text']['group_management'] = __( 'Back to Group Management', 'uncanny-learndash-groups' );

		return $this->create_essay_table( $request );
	}

	/*
	 * Generate Essays HTML Table
	 *
	 * @return string
	 */
	/**
	 * @param array $request
	 *
	 * @return false|string|void
	 */
	public function create_essay_table( $request = array() ) {
		$user_id = get_current_user_id();

		// Check if we have to add the export buttons
		if ( ! isset( $request['excel_export_button'] ) ) {
			$request['excel_export_button'] = 'hide';
		}

		if ( ! isset( $request['csv_export_button'] ) ) {
			$request['csv_export_button'] = 'hide';
		}

		$status = 'ungraded';
		if ( isset( $request['status'] ) ) {
			if ( 'all' == $request['status'] ) {
				$status = 'all';
			} elseif ( 'graded' == $request['status'] ) {
				$status = 'graded';
			}
		}
		// Is the user logged in
		if ( ! $user_id ) {
			return __( 'Please log in to view the report.', 'uncanny-learndash-groups' );
		}

		$allowed_roles = apply_filters(
			'ulgm_gm_allowed_roles',
			array(
				'administrator',
				'group_leader',
				'ulgm_group_management',
			)
		);
		// Is the user a group leader
		if ( array_intersect( wp_get_current_user()->roles, $allowed_roles ) ) {

			// Load Selection options for group and quiz list
			$group_drop_downs = $this->get_groups_drop_downs( $user_id );

		} else {
			return __( 'You must be a admin or group leader to access this page.', 'uncanny-learndash-groups' );
		}
		global $wp;
		$current_page = home_url( $wp->request );

		self::$table_options = array(
			'orderBy' => $request['orderby_column'], // The ID of the column used to sort
			'order'   => $request['order_column'], // Designates the ascending or descending order of the ‘orderby‘ parameter
		);

		ob_start();
		?>

		<script>

		var ulgmEssayReportShortcode = {
			table: {
				orderBy: '<?php esc_attr__( 'Date', 'uncanny-learndash-groups' ); ?>',
				order: 'desc'
			}
		}

		try {
			ulgmEssayReportShortcode.table = <?php echo json_encode( self::$table_options, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS ); ?>;
		} catch ( e ) {
			console.warn( e );
		}

		</script>

		<div class="uo-groups uo-reports">
			<?php
			if ( ! empty( SharedFunctions::get_group_management_page_id() ) && ! empty( SharedFunctions::get_group_essay_report_page_id() ) ) :
				?>
				<div class="uo-row uo-groups-section uo-groups-report-go-back">
					<div class="uo-groups-actions">
						<div class="group-management-buttons">
							<button class="ulgm-link uo-btn uo-left uo-btn-arrow-left"
									onclick="location.href='<?php echo self::$ulgm_essays_shortcode['text']['group_management_link']; ?>'"
									type="button">
								<?php echo self::$ulgm_essays_shortcode['text']['group_management']; ?>
							</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="uo-groups uo-quiz-report uo-groups-essays" id="uo-groups-essays-management">
				<form id="uo-groups-essays-management-form" method="post">

					<!-- <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce(); ?>"> -->
					<input type="hidden" name="_wp_http_referer" value="<?php echo $current_page; ?>">
					<input type="hidden" name="post_status" id="bulk-action-selector-top" value="">
					<input type="hidden" name="essay_id" id="bulk-post-selector-top" value="">
					<input type="hidden" name="_uogm_essay_action" value="sfwd-essay">
					<div class="uo-row uo-groups-section uo-groups-report-go-back">
						<div class="uo-groups-actions">
							<div class="group-management-buttons">
								<!-- <button class="ulgm-link uo-btn uo-left uo-btn-arrow-left"
								onclick="location.href='<?php echo self::$ulgm_essays_shortcode['text']['group_management_link']; ?>'"
								type="button">
							<?php echo self::$ulgm_essays_shortcode['text']['group_management']; ?>
						</button> -->
							</div>
						</div>
					</div>
					<div class="uo-row uo-groups-section uo-groups-selection">
						<div class="group-management-form">
							<div class="uo-groups-select-filters">
								<?php if ( isset( $group_drop_downs['groups'] ) ) { ?>
									<div class="uo-row uo-groups-select-filter">
										<div class="uo-select">
											<label><?php _e( 'Group', 'uncanny-learndash-groups' ); ?></label>
											<select class="change-group-management-form"
													id="uo-group-report-group"><?php echo $group_drop_downs['groups']; ?></select>
										</div>
									</div>
								<?php } ?>
								<?php if ( isset( $group_drop_downs['courses'] ) ) { ?>
									<div class="uo-row uo-groups-select-filter">
										<div class="uo-select">
											<label><?php echo sprintf( _x( '%s', 'Courses', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?></label>
											<select class="change-group-management-form <?php echo isset( $group_drop_downs['courses_class'] ) ? $group_drop_downs['courses_class'] : ''; ?>"
													id="uo-group-report-courses"
													disabled="disabled"><?php echo $group_drop_downs['courses']; ?></select>
											<div id="uo-group-report-nocourses" class="group-management-rest-message"
												 style="display: none;"><?php echo sprintf( __( 'No %s found.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?></div>
										</div>
									</div>
								<?php } ?>
								<?php if ( isset( $group_drop_downs['lessons'] ) ) { ?>
									<div class="uo-row uo-groups-select-filter">
										<div class="uo-select">
											<label><?php printf( _x( '%1$s / %2$s', 'LearnDash lesson and topic labels', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lesson' ), \LearnDash_Custom_Label::get_label( 'topic' ) ); ?></label>
											<select class="change-group-management-form <?php echo isset( $group_drop_downs['lessons_class'] ) ? $group_drop_downs['lessons_class'] : ''; ?>"
													style="" id="uo-group-report-lessons"
													disabled="disabled"><?php echo $group_drop_downs['lessons']; ?></select>
											<div id="uo-group-report-nolessons" class="group-management-rest-message"
												 style="display: none;"><?php echo sprintf( _x( 'No %s found.', 'No lessons found.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lessons' ) ); ?></div>
										</div>
									</div>
								<?php } ?>
								<?php if ( isset( $group_drop_downs['quizzes'] ) ) { ?>
									<div class="uo-row uo-groups-select-filter">
										<div class="uo-select">
											<label><?php _e( \LearnDash_Custom_Label::get_label( 'quizzes' ), 'uncanny-learndash-groups' ); ?></label>
											<select class="change-group-management-form <?php echo isset( $group_drop_downs['quizzes_class'] ) ? $group_drop_downs['quizzes_class'] : ''; ?>"
													style="" id="uo-group-report-quizzes"
													disabled="disabled"><?php echo $group_drop_downs['quizzes']; ?></select>
											<div id="uo-group-report-noquizzes" class="group-management-rest-message"
												 style="display: none;"><?php echo sprintf( _x( 'No %s found.', 'No quizzes found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quizzes' ) ); ?></div>
										</div>
									</div>
								<?php } ?>
								<div class="uo-row uo-groups-select-filter">
									<div class="uo-select">
										<label><?php _e( 'Status', 'uncanny-learndash-groups' ); ?></label>
										<select class="change-group-management-form" style=""
												id="uo-group-report-status">
											<option value="all" 
											<?php
											if ( 'all' === $status ) {
												?>
												 selected <?php } ?>><?php _e( 'All', 'uncanny-learndash-groups' ); ?></option>
											<option value="graded" 
											<?php
											if ( 'graded' === $status ) {
												?>
												 selected <?php } ?>><?php _e( 'Graded', 'uncanny-learndash-groups' ); ?></option>
											<option value="ungraded" 
											<?php
											if ( 'ungraded' === $status ) {
												?>
												 selected <?php } ?>><?php _e( 'Ungraded', 'uncanny-learndash-groups' ); ?></option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="uo-row uo-groups-table">
						<div class="group-essays-buttons uo-hidden">
							<button class="uo-btn uo-left"
									id="group-approve-essays-button"><?php echo __( 'Approve Essays', 'uncanny-learndash-groups' ); ?></button>
							<button class="uo-btn uo-left"
									id="group-trash-essays-button"><?php echo __( 'Delete Essays', 'uncanny-learndash-groups' ); ?></button>
						</div>

						<table id="uo-group-essays-table-hidden" class="display responsive no-wrap uo-table-datatable"
							   cellspacing="0" width="100%" style="display: none;"></table>

						<table 
							id="uo-group-essays-table"
							data-csv="<?php echo $request['csv_export_button']; ?>"
							data-excel="<?php echo $request['excel_export_button']; ?>"

							class="display responsive no-wrap uo-table-datatable"
							cellspacing="0"
							width="100%"
						></table>
					</div>
				</form>
				<div class="uo-row">
					<div style="" class="uo-modal-spinner"></div>
				</div>
			</div>

			<style>

				.uo-groups .uo-select select.h3-select {
					background: none !important;
					border: none;
					-webkit-box-shadow: none;
					box-shadow: none;
					font-size: 18px;
					font-weight: bold;
					padding-left: 0;
					padding-top: 0;
					/*for firefox*/
					-moz-appearance: none;
					/*for chrome*/
					-webkit-appearance: none;
					cursor: auto !important;
				}

				.uo-groups .uo-select select.h3-select:hover {
					color: #000 !important;
					border-color: transparent !important;
				}

				.uo-groups .uo-select:hover select.h3-select {
					color: #000 !important;
					border-color: transparent !important;
				}

				/*for IE10*/
				.uo-groups .uo-select select.h3-select::-ms-expand {
					display: none;
				}

				.showme {
					display: block !important;
				}

				#uo-group-essays-table td, #edit_essay_form .uo-row__content {
					white-space: break-spaces;
				}
			</style>
		</div>
		<?php

		return ob_get_clean();
	}


	/*
	 * Get all Groups the group leader is an administrator of
	 * @since
	 *
	 * @param int $group_leader_id
	 *
	 * @return string html
	 */
	/**
	 * @param int $user_id
	 *
	 * @return bool|string|void
	 */
	public function get_groups_drop_downs( $user_id = 0 ) {

		if ( false !== self::$group_drop_downs ) {
			return self::$group_drop_downs;
		}

		$user_id = get_current_user_id();

		if ( ! user_can( $user_id, 'group_leader' ) && ! user_can( $user_id, 'manage_options' ) ) {
			return false;
		}

		// User is a group leader, get users groups
		$is_hierarchy_setting_enabled = false;
		if ( function_exists( 'learndash_is_groups_hierarchical_enabled' ) && learndash_is_groups_hierarchical_enabled() && 'yes' === get_option( 'ld_hierarchy_settings_child_groups', 'no' ) ) {
			$is_hierarchy_setting_enabled = true;
			if ( ! class_exists( 'Walker_GroupDropdown' ) ) {
				include_once Utilities::get_include( 'class-walker-group-dropdown.php' );
			}
			$dropdown_args = array(
				'selected'     => 0,
				'sort_column'  => 'post_title',
				'hierarchical' => true,
			);
			$walker        = new \Walker_GroupDropdown();
			// User is a group leader, get users groups ... We already verified that the user is already a group leader
			$user_groups = learndash_get_administrators_group_ids( $user_id );

		} else {
			// User is a group leader, get users groups ... We already verified that the user is already a group leader
			$user_groups = learndash_get_administrators_group_ids( $user_id, true );
		}

		if ( empty( $user_groups ) ) {
			return __( 'You are not a leader of any groups.', 'uncanny-learndash-groups' );
		}

		// LD returns a array of IDs as strings, refactor to Int
		$posts_in = array_map( 'intval', $user_groups );

		$args = array(
			'post_type'      => 'groups',
			'post__in'       => $posts_in,
			'posts_per_page' => 9999,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		$args = apply_filters( 'lesson_group_dropdown', $args, $user_id, $posts_in );

		$group_post_objects = get_posts( $args );

		$drop_down['groups'] = '<option value="0">' . __( 'Select group', 'uncanny-learndash-groups' ) . '</option><option value="" class="ulgm-essays-no-results" style="display: none">' . sprintf( __( 'No %s found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'groups' ) ) . '</option>';
		//$drop_down['groups'] = '';
		$drop_down['lessons_objects'] = array();

		// Collect all the quiz IDs so we can query then altogether
		$course_ids = array();
		$lesson_ids = array();
		$quiz_ids   = array();

		if ( $group_post_objects ) {
			foreach ( $group_post_objects as $group_post_object ) {
				//$group_post_objects->the_post();
				$group_post_id       = $group_post_object->ID;
				$drop_down['groups'] .= '<option value="' . $group_post_id . '">' . $group_post_object->post_title . '</option>';

				$group_lessons                                  = $this->group_lessons( $group_post_id );
				$drop_down['lessons_objects'][ $group_post_id ] = $group_lessons['group_lesson_ids'];

				$drop_down['course_lessons_objects'][ $group_post_id ] = $group_lessons['group_course_lessons'];

				$course_ids = array_merge( $course_ids, $group_lessons['group_course_lessons'] );
				$lesson_ids = array_merge( $lesson_ids, $group_lessons['group_lesson_ids'] );

				$drop_down['relationships'][ $group_post_id ] = $group_lessons['relationships'];

				$group_quizzes                                  = $this->group_quizzes( $group_post_id );
				$drop_down['quizzes_objects'][ $group_post_id ] = $group_quizzes['group_quiz_ids'];

				$drop_down['course_quizzes_objects'][ $group_post_id ] = $group_quizzes['group_course_quizzes'];

				$course_ids = array_merge( $course_ids, $group_quizzes['group_course_quizzes'] );
				$quiz_ids   = array_merge( $quiz_ids, $group_quizzes['group_quiz_ids'] );

				$drop_down['quiz_relationships'][ $group_post_id ] = $group_quizzes['relationships'];
			}
			/* Restore original Post Data */
			//wp_reset_postdata();
			// Re-arrange groups in hierarchy view
			if ( $is_hierarchy_setting_enabled ) {
				$drop_down['groups'] = '<option value="0">' . __( 'Select Group', 'uncanny-learndash-groups' ) . '</option>';
				if ( $walker instanceof \Walker_GroupDropdown ) {
					$drop_down['groups'] .= $walker->walk( $group_post_objects, 0, $dropdown_args );
				}
			}
		} else {
			// no posts found
			$drop_down['groups'] = '<option value="0">' . __( 'No groups', 'uncanny-learndash-groups' ) . '</option>';
		}

		// Get Courses
		$course_ids = array_unique( $course_ids );

		$courses = $this->get_objects( $course_ids, 'sfwd-courses', 'title', 'ASC' );

		if ( ! empty( $courses ) ) {
			// below line commented BY_AC
			$drop_down['courses_class'] = '';
			$drop_down['courses']       = '<option value="0">' . sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ) . '</option><option value="" class="ulgm-essays-no-results" style="display: none">' . sprintf( __( 'No %s found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ) . '</option>';
			//$drop_down['courses'] = '';
			foreach ( $courses as $course ) {
				$drop_down['courses'] .= '<option value="' . $course->ID . '"  style="display:none">' . $course->post_title . '</option>';

			}
		} else {
			$drop_down['courses_class'] = 'h3-select';
			$drop_down['courses']       = '<option value="0">' . sprintf( __( 'No %s in group', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ) . '</option>';
		}

		// Get lessons
		$relations = array();

		if ( ! empty( $drop_down['relationships'] ) ) {
			// below line commented BY_AC
			$drop_down['lessons'] = '<option value="" class="ulgm-essays-no-results" style="display: none">' . sprintf( __( 'No %s found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lessons' ) ) . '</option>';
			$unique_lesson        = array();
			foreach ( $drop_down['relationships'] as $group_id => $groups_courses ) {
				if ( ! empty( $groups_courses ) ) {
					foreach ( $groups_courses as $course_id => $course_lesson ) {
						if ( ! empty( $course_lesson ) ) {
							foreach ( $course_lesson as $lesson_id => $lesson ) {
								$relations[ $group_id ][ $course_id ][] = $lesson_id;
								if ( ! in_array( $lesson_id, $unique_lesson ) ) {
									$unique_lesson[]      = $lesson_id;
									$drop_down['lessons'] .= '<option value="' . $lesson_id . '" style="display:none">' . $lesson . '</option>';
								}
							}
						}
					}
				}
				//$drop_down['lessons'] .= '<option value="' . $quiz->ID . '" style="display:none">' . $quiz->post_title . '</option>';;
			}

			if ( empty( $drop_down['lessons'] ) ) {
				$drop_down['lessons_class'] = 'h3-select';
				$drop_down['lessons']       = '<option value="0">' . sprintf( __( 'No %s in group', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lessons' ) ) . '</option>';
			}
		} else {
			$drop_down['lessons_class'] = 'h3-select';
			$drop_down['lessons']       = '<option value="0">' . sprintf( __( 'No %s in group', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lessons' ) ) . '</option>';
		}
		$drop_down['relationships'] = $relations;
		// Get lessons
		$relations = array();
		if ( ! empty( $drop_down['quiz_relationships'] ) ) {
			// below line commented BY_AC

			$drop_down['quizzes'] = '<option value="" class="ulgm-essays-no-results" style="display: none">' . sprintf( __( 'No %s found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quizzes' ) ) . '</option>';
			$unique_quiz          = array();
			foreach ( $drop_down['quiz_relationships'] as $group_id => $groups_courses ) {
				if ( ! empty( $groups_courses ) ) {
					foreach ( $groups_courses as $course_id => $course_quiz ) {
						if ( ! empty( $course_quiz ) ) {
							foreach ( $course_quiz as $quiz_id => $quiz ) {
								$relations[ $group_id ][ $course_id ][] = $quiz_id;
								if ( ! in_array( $quiz_id, $unique_quiz ) ) {
									$unique_quiz[]        = $quiz_id;
									$drop_down['quizzes'] .= '<option value="' . $quiz_id . '" style="">' . $quiz . '</option>';
								}
							}
						}
					}
				}
				//$drop_down['lessons'] .= '<option value="' . $quiz->ID . '" style="display:none">' . $quiz->post_title . '</option>';;
			}
			if ( empty( $drop_down['lessons'] ) ) {
				$drop_down['quizzes_class'] = 'h3-select';
				$drop_down['quizzes']       = '<option value="0">' . sprintf( __( 'No %s in group', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quizzes' ) ) . '</option>';
			}
		} else {
			$drop_down['quizzes_class'] = 'h3-select';
			$drop_down['quizzes']       = '<option value="0">' . sprintf( __( 'No %s in group', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quizzes' ) ) . '</option>';
		}
		$drop_down['quiz_relationships'] = $relations;
		// Cache results so we don't re-query
		self::$group_drop_downs = $drop_down;

		return $drop_down;
	}

	/**
	 * Get groups course lessons
	 *
	 * @param int $group_id
	 *
	 * @return mixed
	 */
	public function group_lessons( $group_id = 0 ) {
		$group_lesson_ids = array();
		$group_course_ids = array();
		$include_topics   = true;

		$relationships = array();
		if ( ! empty( $group_id ) ) {
			$group_course_ids = LearndashFunctionOverrides::learndash_group_enrolled_courses( intval( $group_id ) );

			if ( ! empty( $group_course_ids ) ) {
				foreach ( $group_course_ids as $course_id ) {

					if ( ! isset( $relationships[ $group_id ][ $course_id ] ) ) {
						$relationships[ $course_id ][0] = sprintf( _x( 'Select %1$s / %2$s', 'LearnDash lesson and topic labels', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lesson' ), \LearnDash_Custom_Label::get_label( 'topic' ) );
					}

					$lesson_ids = learndash_course_get_children_of_step( $course_id, $course_id, 'sfwd-lessons' );

					if ( ! empty( $lesson_ids ) ) {
						foreach ( $lesson_ids as $lesson_id ) {
							$group_lesson_ids[] = $lesson_id;

							if ( ! isset( $relationships[ $course_id ][ $lesson_id ] ) ) {
								$relationships[ $course_id ][ $lesson_id ] = get_the_title( $lesson_id );
							}

							if ( $include_topics ) {
								$topic_ids = learndash_course_get_children_of_step( $course_id, $lesson_id, 'sfwd-topic' );
								if ( ! empty( $topic_ids ) ) {
									foreach ( $topic_ids as $topic_id ) {
										$group_lesson_ids[] = $topic_id;
										if ( ! isset( $relationships[ $course_id ][ $topic_id ] ) ) {
											$relationships[ $course_id ][ $topic_id ] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . get_the_title( $topic_id );
										}
									}
								}
							}
						}

						$group_lesson_ids = array_unique( $group_lesson_ids );
					} else {
						$relationships[ $course_id ] = array();
					}
				}
			}
		}

		$data = array(
			'group_lesson_ids'     => $group_lesson_ids,
			'group_course_lessons' => $group_course_ids,
			'relationships'        => $relationships,
		);

		return $data;
	}

	/**
	 * Get groups course quizzes
	 *
	 * @param int $group_id
	 *
	 * @return mixed
	 */
	public function group_quizzes( $group_id = 0 ) {
		$group_quiz_ids   = array();
		$group_course_ids = array();
		$include_topics   = true;
		$relation         = array();

		$relationships = array();
		if ( ! empty( $group_id ) ) {

			$group_course_ids = LearndashFunctionOverrides::learndash_group_enrolled_courses( intval( $group_id ) );

			if ( ! empty( $group_course_ids ) ) {
				foreach ( $group_course_ids as $course_id ) {

					if ( ! isset( $relation[ $course_id ] ) ) {
						$relation[ $course_id ]         = array();
						$relationships[ $course_id ][0] = sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quiz' ) );
					}

					$lesson_ids = learndash_course_get_children_of_step( $course_id, $course_id, 'sfwd-lessons' );
					$quiz_ids   = learndash_course_get_children_of_step( $course_id, $course_id, 'sfwd-quiz' );
					if ( ! empty( $quiz_ids ) ) {
						$group_quiz_ids = array_merge( $group_quiz_ids, $quiz_ids );
						//$group_quiz_ids = array_unique( $group_quiz_ids );
					}
					if ( ! empty( $lesson_ids ) ) {
						foreach ( $lesson_ids as $lesson_id ) {
							$quiz_ids = learndash_course_get_children_of_step( $course_id, $lesson_id, 'sfwd-quiz' );
							if ( ! empty( $quiz_ids ) ) {
								$group_quiz_ids = array_merge( $group_quiz_ids, $quiz_ids );
								//$group_quiz_ids = array_unique( $group_quiz_ids );
							}
							if ( $include_topics ) {
								$topic_ids = learndash_course_get_children_of_step( $course_id, $lesson_id, 'sfwd-topic' );
								if ( ! empty( $topic_ids ) ) {
									foreach ( $topic_ids as $topic_id ) {
										$quiz_ids = learndash_course_get_children_of_step( $course_id, $topic_id, 'sfwd-quiz' );
										if ( ! empty( $quiz_ids ) ) {
											$group_quiz_ids = array_merge( $group_quiz_ids, $quiz_ids );
										}
									}
								}
							}
						}
					}

					$group_quiz_ids         = array_unique( $group_quiz_ids );
					$relation[ $course_id ] = $group_quiz_ids;
					$group_quiz_ids         = array();

					if ( ! empty( $relation[ $course_id ] ) ) {
						foreach ( $relation[ $course_id ] as $quiz_id ) {
							if ( ! isset( $relationships[ $course_id ][ $quiz_id ] ) ) {
								$relationships[ $course_id ][ $quiz_id ] = get_the_title( $quiz_id );
							}
						}
					}
				}
			}
		}

		$data = array(
			'group_quiz_ids'       => $group_quiz_ids,
			'group_course_quizzes' => $group_course_ids,
			'relationships'        => $relationships,
		);

		return $data;
	}

	/**
	 * Get all lessons/courses post objects
	 *
	 * @param array $ids
	 * @param string $post_type
	 * @param string $order_by
	 * @param string $order
	 *
	 * @return array $_lessons
	 */
	public function get_objects( $ids, $post_type, $order_by = 'title', $order = 'ASC' ) {

		if ( empty( $order_by ) ) {
			$order_by = 'title';
		}

		if ( empty( $order ) ) {
			$order = 'ASC';
		}

		if ( empty( $ids ) ) {
			return array();
		}

		$args = array(
			'post_type'      => $post_type,
			'post__in'       => $ids,
			'posts_per_page' => - 1,
			'orderby'        => $order_by,
			'order'          => $order,
		);

		$lessons = get_posts( $args );

		// Set the Key as the post ID so we don't have to run a nested loop
		$_lessons = array();
		foreach ( $lessons as $quiz ) {
			$_lessons[ $quiz->ID ] = $quiz;
		}

		return $_lessons;
	}


	/*
	 * Register rest api endpoints
	 *
	 */

	/**
	 * @param array $request
	 */
	public function essays_scripts( $request = array() ) {
		global $post;

		if ( Utilities::has_shortcode( $post, 'uo_groups_essays' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-essays-report' ) ) {
			// check columns settings from shortcode...
			if ( isset( $request['columns'] ) ) {
				$columns = explode( ',', $request['columns'] );
				$columns = array_filter( array_map( 'trim', $columns ) );
			}

			$columns_visibilities = array(
				'title'          => in_array( __( 'Title', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'first_name'     => in_array( __( 'First name', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'last_name'      => in_array( __( 'Last name', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'author'         => in_array( __( 'Username', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'status'         => in_array( __( 'Status', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'points'         => in_array( __( 'Points', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'question_text'  => in_array( __( 'Question', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'content'        => in_array( __( 'Content', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'assignedCourse' => in_array( __( 'Course', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'assignedlesson' => in_array( __( 'Lesson', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'assignedquiz'   => in_array( __( 'Quiz', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'comments'       => in_array( __( 'Comments', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'date'           => in_array( __( 'Date', 'uncanny-learndash-groups' ), $columns ) ? true : false,
			);

			// DataTables
			wp_enqueue_script(
				'ulgm-datatables',
				Utilities::get_vendor( 'datatables/datatables.min.js' ),
				array( 'jquery' ),
				Utilities::get_version(),
				true
			);

			wp_enqueue_style(
				'ulgm-datatables',
				Utilities::get_vendor( 'datatables/datatables.min.css' ),
				array(),
				Utilities::get_version()
			);

			// Front End Questionnaire JS
			wp_register_script( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.js' ), array( 'jquery', 'ulgm-datatables' ), Utilities::get_version(), true );

			$group_drop_downs = $this->get_groups_drop_downs();

			// Attach API data to ulgm-frontend
			$api_setup = array(
				'root'                 => esc_url_raw( rest_url() . $this->root_path . '/' ),
				'nonce'                => \wp_create_nonce( 'wp_rest' ),
				'ajax_nonce'           => \wp_create_nonce( 'edit_essays' ),
				'ajaxurl'              => admin_url( 'admin-ajax.php' ),
				'currentUser'          => get_current_user_id(),
				'localized'            => $this->get_frontend_localized_strings(),
				'grouplessons'         => isset( $group_drop_downs['lessons_objects'] ) ? $group_drop_downs['lessons_objects'] : array(),
				'courseGrouplessons'   => isset( $group_drop_downs['course_lessons_objects'] ) ? $group_drop_downs['course_lessons_objects'] : array(),
				'courseGroupquizzes'   => isset( $group_drop_downs['course_quizzes_objects'] ) ? $group_drop_downs['course_quizzes_objects'] : array(),
				'relationships'        => isset( $group_drop_downs['relationships'] ) ? $group_drop_downs['relationships'] : array(),
				'quiz_relationships'   => isset( $group_drop_downs['quiz_relationships'] ) ? $group_drop_downs['quiz_relationships'] : array(),
				'columns_visibilities' => $columns_visibilities,

				'i18n'                 => array(
					'CSV'            => __( 'CSV', 'uncanny-learndash-groups' ),
					'exportCSV'      => __( 'CSV export', 'uncanny-learndash-groups' ),
					'excel'          => __( 'Excel', 'uncanny-learndash-groups' ),
					'exportExcel'    => __( 'Excel export', 'uncanny-learndash-groups' ),
					'reportBaseName' => __( 'Essay report', 'uncanny-learndash-groups' ),
					'ungraded'       => __( 'Ungraded', 'uncanny-learndash-groups' ),
				),
			);

			wp_localize_script( 'ulgm-frontend', 'groupEssays', $api_setup );

			wp_enqueue_script( 'ulgm-frontend' );

			wp_enqueue_editor();

			// Load styles
			wp_register_style( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.css' ), array( 'ulgm-datatables' ), Utilities::get_version() );
			$user_colors = Utilities::user_colors();
			wp_add_inline_style( 'ulgm-frontend', $user_colors );
			wp_enqueue_style( 'ulgm-frontend', $user_colors );

			// Load Modal
			wp_enqueue_script( 'ulgm-modal', Utilities::get_vendor( 'jquery-modal/js/jquery.modal.js' ), array( 'jquery' ), Utilities::get_version(), true ); // @see https://raw.githubusercontent.com/kylefox/jquery-modal/master/jquery.modal.js
			wp_enqueue_style( 'ulgm-modal', Utilities::get_vendor( 'jquery-modal/css/jquery.modal.css' ), array(), Utilities::get_version() ); // @see https://raw.githubusercontent.com/kylefox/jquery-modal/master/jquery.modal.css

			// Load Select2
			wp_enqueue_script( 'ulgm-select2', Utilities::get_vendor( 'select2/js/select2.min.js' ), array( 'jquery' ), Utilities::get_version(), true );
			wp_enqueue_style( 'ulgm-select2', Utilities::get_vendor( 'select2/css/select2.min.css' ), array(), Utilities::get_version() );
		}
	}

	/**
	 * @return mixed|void
	 */
	private function get_frontend_localized_strings() {

		$localized_strings = array();

		$localized_strings['title'] = __( 'Title', 'uncanny-learndash-groups' );

		$localized_strings['idColumn'] = __( 'ID', 'uncanny-learndash-groups' );

		$localized_strings['first_name'] = __( 'First name', 'uncanny-learndash-groups' );

		$localized_strings['last_name'] = __( 'Last name', 'uncanny-learndash-groups' );

		$localized_strings['author'] = __( 'Username', 'uncanny-learndash-groups' );

		$localized_strings['status'] = __( 'Status', 'uncanny-learndash-groups' );

		$localized_strings['customizeColumns'] = __( 'Customize columns', 'uncanny-learndash-groups' );

		$localized_strings['hideCustomizeColumns'] = __( 'Hide customize columns', 'uncanny-learndash-groups' );

		$localized_strings['points'] = __( 'Points', 'uncanny-learndash-groups' );

		$localized_strings['assignedCourse'] = \LearnDash_Custom_Label::get_label( 'course' );

		$localized_strings['assignedlesson'] = \LearnDash_Custom_Label::get_label( 'lesson' );

		$localized_strings['assignedquiz'] = \LearnDash_Custom_Label::get_label( 'quiz' );

		$localized_strings['comments'] = __( 'Comments', 'uncanny-learndash-groups' );

		$localized_strings['question_text'] = \LearnDash_Custom_Label::get_label( 'question' );

		$localized_strings['content'] = __( 'Content', 'uncanny-learndash-groups' );

		$localized_strings['date'] = __( 'Date', 'uncanny-learndash-groups' );

		$localized_strings['csvExport'] = __( 'CSV export', 'uncanny-learndash-groups' );

		$localized_strings['selectCourse'] = sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );

		$localized_strings['noCourse'] = sprintf( __( 'No %s available', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );

		$localized_strings['selectUser'] = __( 'Select user', 'uncanny-learndash-groups' );

		$localized_strings['noUsers'] = __( 'No users available', 'uncanny-learndash-groups' );

		$localized_strings['all'] = __( 'All', 'uncanny-learndash-groups' );

		$localized_strings['selectLesson'] = sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lesson' ) );

		$localized_strings['noGroupsFound']  = sprintf( __( 'No %s found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'groups' ) );
		$localized_strings['noCoursesFound'] = sprintf( __( 'No %s found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );
		$localized_strings['noLessonsFound'] = sprintf( __( 'No %s found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lessons' ) );
		$localized_strings['noQuizzesFound'] = sprintf( __( 'No %s found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quizzes' ) );

		/* DataTable */
		$localized_strings                      = array_merge( $localized_strings, Utilities::i18n_datatable_strings() );
		$localized_strings['searchPlaceholder'] = __( 'Search by name, username, status or points', 'uncanny-learndash-groups' );

		$localized_strings = apply_filters( 'quiz-report-table-strings', $localized_strings );

		return $localized_strings;
	}

	/**
	 *
	 */
	public function uo_api() {
		register_rest_route(
			$this->root_path,
			'/get_essays_data/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'get_essays_data' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);
		register_rest_route(
			$this->root_path,
			'/edit_essays_data/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'edit_essays' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);
		register_rest_route(
			$this->root_path,
			'/save_essays_data/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_essays' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);
	}

	/**
	 * @return array
	 */
	public function get_essays_data() {

		$data = $_POST;

		// validate inputs
		$lesson_ID    = absint( $data['lessonId'] );
		$course_ID    = absint( $data['courseId'] );
		$group_ID     = absint( $data['groupId'] );
		$quiz_ID      = absint( $data['quizId'] );
		$status       = $data['status'];
		$essays_table = $this->essays_table( $lesson_ID, $course_ID, $group_ID, $quiz_ID, $status );

		$essays_table = apply_filters( 'ulgm_rest_api_get_essays_data', $essays_table, $_POST );

		return $essays_table;
	}

	/**
	 * Return html for the essay table
	 *
	 * @param $lesson_ID
	 * @param $course_ID
	 * @param $group_ID
	 *
	 * @return array
	 *
	 */
	public function essays_table( $lesson_ID = 0, $course_ID = 0, $group_ID = 0, $quiz_ID = 0, $status = 'ungraded' ) {

		global $learndash_shortcode_used;
		$essays                   = array();
		$learndash_shortcode_used = true;
		$user_id                  = get_current_user_id();

		$q_vars = array(
			'post_type'      => 'sfwd-essays',
			'posts_per_page' => - 1,
			'post_status'    => array(),
		);

		if ( $status === 'all' ) {
			$q_vars['post_status'] = array( 'graded', 'not_graded' );
		} elseif ( $status === 'ungraded' ) {
			$q_vars['post_status'] = array( 'not_graded' );
		} elseif ( $status === 'graded' ) {
			$q_vars['post_status'] = array( 'graded' );
		}

		if ( learndash_is_group_leader_user( $user_id ) || learndash_is_admin_user( $user_id ) ) {
			$group_ids  = learndash_get_administrators_group_ids( $user_id );
			$course_ids = array();
			$lesson_ids = array();
			$user_ids   = array();

			if ( ! empty( $group_ids ) && is_array( $group_ids ) ) {
				if ( absint( $group_ID ) !== 0 && absint( $group_ID ) !== '' ) {
					foreach ( $group_ids as $group_id ) {
						if ( $group_ID === absint( $group_id ) ) {
							$group_course_ids = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id );
							if ( ! empty( $group_course_ids ) && is_array( $group_course_ids ) ) {
								$course_ids = array_merge( $course_ids, $group_course_ids );
							}
							$lessons    = $this->group_lessons( $group_id );
							$lesson_ids = array_merge( $lesson_ids, $lessons['group_lesson_ids'] );

							$group_users = LearndashFunctionOverrides::learndash_get_groups_user_ids( $group_id );
							if ( ! empty( $group_users ) && is_array( $group_users ) ) {
								foreach ( $group_users as $group_user_id ) {
									$user_ids[ $group_user_id ] = $group_user_id;
								}
							}
						}
					}
				} else {
					foreach ( $group_ids as $group_id ) {
						$group_course_ids = LearndashFunctionOverrides::learndash_group_enrolled_courses( $group_id );
						if ( ! empty( $group_course_ids ) && is_array( $group_course_ids ) ) {
							$course_ids = array_merge( $course_ids, $group_course_ids );
						}
						$lessons    = $this->group_lessons( $group_id );
						$lesson_ids = array_merge( $lesson_ids, $lessons['group_lesson_ids'] );

						$group_users = LearndashFunctionOverrides::learndash_get_groups_user_ids( $group_id );
						if ( ! empty( $group_users ) && is_array( $group_users ) ) {
							foreach ( $group_users as $group_user_id ) {
								$user_ids[ $group_user_id ] = $group_user_id;
							}
						}
					}
				}
			}

			if ( ! empty( $course_ids ) && count( $course_ids ) ) {
				if ( ! isset( $q_vars['meta_query'] ) ) {
					$q_vars['meta_query'] = array();
				}

				if ( $course_ID !== '' && $course_ID !== 0 && in_array( $course_ID, $course_ids ) ) {
					$course_ids = array( $course_ID );
				}

				if ( ! empty( $lesson_ids ) && count( $lesson_ids ) && $lesson_ID !== '' && $lesson_ID !== 0 && in_array( $lesson_ID, $lesson_ids ) ) {
					$q_vars['meta_query'][] = "'relation' => 'AND'";
					$lesson_ids             = array( $lesson_ID );
					$q_vars['meta_query'][] = array(
						'key'     => 'lesson_id',
						'value'   => $lesson_ids,
						'compare' => 'IN',
					);
				}
				if ( ! empty( $quiz_ID ) && $quiz_ID !== 0 ) {
					$q_vars['meta_query'][] = "'relation' => 'AND'";
					$quiz_IDs               = array( $quiz_ID );
					$q_vars['meta_query'][] = array(
						'key'     => 'quiz_post_id',
						'value'   => $quiz_IDs,
						'compare' => 'IN',
					);
				}

				$q_vars['meta_query'][] = array(
					'key'     => 'course_id',
					'value'   => $course_ids,
					'compare' => 'IN',
				);

			}

			if ( ! empty( $user_ids ) && count( $user_ids ) ) {
				$q_vars['author__in'] = $user_ids;
			} else {
				$q_vars['author__in'] = - 2;
			}
		}

		$essay_posts = get_posts( $q_vars );

		if ( ! empty( $essay_posts ) ) {
			foreach ( $essay_posts as $essay ) {
				$essay_id           = $essay->ID;
				$status             = '';
				$points             = '';
				$post_status_object = get_post_status_object( $essay->post_status );
				if ( ( ! empty( $post_status_object ) ) && ( is_object( $post_status_object ) ) && ( property_exists( $post_status_object, 'label' ) ) ) {
					$status = $post_status_object->label;
				}

				if ( $essay->post_status == 'not_graded' ) {
					$status = '<button id="essay_approve_' . $essay_id . '" class="small essay_approve_single">' . esc_html__( 'approve', 'uncanny-learndash-groups' ) . '</button>';
				}
				$course_id          = get_post_meta( $essay_id, 'course_id', true );
				$lesson_id          = get_post_meta( $essay_id, 'lesson_id', true );
				$quiz_id            = get_post_meta( $essay_id, 'quiz_id', true );
				$essay_quiz_post_id = get_post_meta( $essay_id, 'quiz_post_id', true );
				if ( empty( $essay_quiz_post_id ) ) {

					$essay_quiz_query_args = array(
						'post_type'    => 'sfwd-quiz',
						'post_status'  => 'publish',
						'meta_key'     => 'quiz_pro_id_' . intval( $quiz_id ),
						'meta_value'   => intval( $quiz_id ),
						'meta_compare' => '=',
						'fields'       => 'ids',
						'orderby'      => 'title',
						'order'        => 'ASC',
					);

					$essay_quiz_query   = new \WP_Query( $essay_quiz_query_args );
					$essay_quiz_post_id = $essay_quiz_query->posts[0];

				}

				$question_id   = get_post_meta( $essay_id, 'question_id', true );
				$question_text = '';
				if ( ! empty( $quiz_id ) ) {
					$questionMapper = new \WpProQuiz_Model_QuestionMapper();
					$question       = $questionMapper->fetchById( intval( $question_id ), null );
					if ( $question instanceof \WpProQuiz_Model_Question ) {

						$submitted_essay_data = learndash_get_submitted_essay_data( $quiz_id, $question_id, $essay );
						$max_points           = $question->getPoints();
						$question_text        = $question->getQuestion();
						$current_points       = 0;
						if ( isset( $submitted_essay_data['points_awarded'] ) ) {
							$current_points = intval( $submitted_essay_data['points_awarded'] );
						}

						if ( $essay->post_status == 'not_graded' ) {
							$current_points = '<input id="essay_points_' . $essay_id . '" class="small-text" type="number" value="' . $current_points . '" max="' . $max_points . '" min="0" step="1" name="essay_points[' . $essay_id . ']" />';
							$points         = sprintf( _x( '%1$s / %2$d', 'placeholders: input points / maximum point for essay', 'uncanny-learndash-groups' ), $current_points, $max_points );
						} else {
							$points = sprintf( esc_html_x( '%1$d / %2$d', 'placeholders: current awarded points / maximum point for essay', 'uncanny-learndash-groups' ), $current_points, $max_points );
						}
					} else {
						$points = '-';
					}
				}
				$lesson = get_post( $lesson_id );
				$course = get_post( $course_id );
				$quiz   = get_post( $quiz_id );

				//$row_action                         = '<span class="edit"><a href="#" class="edit_essay_single" data-essay-id="' . $essay_id . '">Edit</a> | </span><span class="trash"><a href="#" id="a_essay_trash_' . $essay_id . '" class="delete_essay_single">Trash</a> | </span><span class="view"><a href="' . get_permalink( $essay_id ) . '" rel="bookmark" target="_blank">View</a></span>';
				$row_action    = '<span class="trash"><a href="#" id="a_essay_trash_' . $essay_id . '" class="delete_essay_single">Trash</a></span>';
				$upload        = get_post_meta( $essay_id, 'upload', true );
				$essay_content = $essay->post_content;

				if ( ! empty( $upload ) ) {
					$row_action    .= ' | <a href="' . esc_url( $upload ) . '" target="_blank">' . esc_html__( 'Download', 'uncanny-learndash-groups' ) . '</a>';
					$essay_content .= '<br/><a target="_blank" href="' . $upload . '">' . __( 'User Upload', 'uncanny-learndash-groups' ) . ' </a>';
				}

				$essays[] = array(
					'id'             => $essay_id,
					'title'          => '<a data-essay-id="' . $essay_id . '" class="edit_essay edit_essay_single">' . $essay->post_title . '</a><div class="row-actions">' . $row_action . '</div>',
					'first_name'     => get_the_author_meta( 'first_name', $essay->post_author ),
					'last_name'      => get_the_author_meta( 'last_name', $essay->post_author ),
					'author'         => '<a href="mailto:' . get_the_author_meta( 'email', $essay->post_author ) . '" class="edit_essay">' . get_the_author_meta( 'login', $essay->post_author ) . '</a>',
					'status'         => $status,
					'points'         => $points,
					'question_text'  => $question_text,
					'content'        => $essay_content,
					'assignedCourse' => ! empty( $course ) ? '<a href="' . get_permalink( $course ) . '">' . $course->post_title . '</a>' : '',
					'assignedlesson' => ! empty( $lesson ) ? '<a href="' . get_permalink( $lesson ) . '">' . $lesson->post_title . '</a>' : '',
					'assignedquiz'   => '<a href="' . get_permalink( $essay_quiz_post_id ) . '">' . get_the_title( $essay_quiz_post_id ) . '</a>',
					'comments'       => '<a target="_blank" href="' . get_permalink( $essay_id ) . '#comments">' . get_comments_number( $essay_id ) . '</a>',
					'date'           => '<span class="ulg-hidden-data" style="display: none">' . get_the_date( 'U', $essay ) . '</span>' . get_the_date( '', $essay ),
				);
			}
		}

		return $essays;
	}

	/**
	 * Added to substitute LD's learndash_essay_bulk_actions_approve() function.
	 *
	 * @since 3.7.5
	 */
	protected function essay_bulk_actions_approve() {
		if ( ! isset( $_REQUEST['post'] ) || empty( $_REQUEST['post'] ) ) {
			return;
		}

		if ( ! isset( $_REQUEST['post_type'] ) || empty( $_REQUEST['post_type'] ) || 'sfwd-essays' !== (string) $_REQUEST['post_type'] ) {
			return;
		}

		$action = '';
		if ( isset( $_REQUEST['action'] ) && - 1 != $_REQUEST['action'] ) {
			$action = esc_attr( $_REQUEST['action'] );
		} elseif ( isset( $_REQUEST['action2'] ) && - 1 != $_REQUEST['action2'] ) {
			$action = esc_attr( $_REQUEST['action2'] );
		} elseif ( ( isset( $_REQUEST['ld_action'] ) ) && ( $_REQUEST['ld_action'] == 'approve_essay' ) ) {
			$action = 'approve_essay';
		}

		if ( 'approve_essay' !== (string) $action ) {
			return;
		}

		if ( ! is_array( $_REQUEST['post'] ) ) {
			$essays = array( $_REQUEST['post'] );
		} else {
			$essays = $_REQUEST['post'];
		}

		foreach ( $essays as $essay_id ) {

			if ( ( ! isset( $_REQUEST['essay_points'][ $essay_id ] ) ) || ( $_REQUEST['essay_points'][ $essay_id ] == '' ) ) {
				continue;
			}

			// get the new assigned points.
			$submitted_essay['points_awarded'] = intval( $_REQUEST['essay_points'][ $essay_id ] );

			$essay_post = get_post( $essay_id );
			if ( ( ! empty( $essay_post ) ) && ( $essay_post instanceof \WP_Post ) && ( 'sfwd-essays' === (string) $essay_post->post_type ) ) {

				if ( 'graded' !== (string) $essay_post->post_status ) {
					$quiz_score_difference = 1;
				}

				// First we update the essat post with the new post_status
				$essay_post->post_status = 'graded';
				wp_update_post( $essay_post );

				$user_id     = $essay_post->post_author;
				$quiz_id     = get_post_meta( $essay_post->ID, 'quiz_id', true );
				$question_id = get_post_meta( $essay_post->ID, 'question_id', true );

				// Stole the following section ot code from learndash_save_essay_status_metabox_data();
				$submitted_essay_data = learndash_get_submitted_essay_data( $quiz_id, $question_id, $essay_post );

				if ( isset( $submitted_essay_data['points_awarded'] ) ) {
					$original_points_awarded = intval( $submitted_essay_data['points_awarded'] );
				} else {
					$original_points_awarded = 0;
				}

				$submitted_essay_data['status'] = 'graded';

				// get the new assigned points.
				$submitted_essay_data['points_awarded'] = intval( $_REQUEST['essay_points'][ $essay_id ] );

				/** This filter is documented in includes/quiz/ld-quiz-essays.php */
				$submitted_essay_data = apply_filters( 'learndash_essay_status_data', $submitted_essay_data );
				learndash_update_submitted_essay_data( $quiz_id, $question_id, $essay_post, $submitted_essay_data );

				if ( ! is_null( $original_points_awarded ) && ! is_null( $submitted_essay_data['points_awarded'] ) ) {
					if ( $submitted_essay_data['points_awarded'] > $original_points_awarded ) {
						$points_awarded_difference = intval( $submitted_essay_data['points_awarded'] ) - intval( $original_points_awarded );
					} else {
						$points_awarded_difference = ( intval( $original_points_awarded ) - intval( $submitted_essay_data['points_awarded'] ) ) * - 1;
					}

					$updated_scoring_data = array(
						'updated_question_score'    => $submitted_essay_data['points_awarded'],
						'points_awarded_difference' => $points_awarded_difference,
						'score_difference'          => $quiz_score_difference,
					);

					/** This filter is documented in includes/quiz/ld-quiz-essays.php */
					$updated_scoring_data = apply_filters( 'learndash_updated_essay_scoring', $updated_scoring_data );
					learndash_update_quiz_data( $quiz_id, $question_id, $updated_scoring_data, $essay_post );

					/** This action is documented in includes/quiz/ld-quiz-essays.php */
					do_action( 'learndash_essay_all_quiz_data_updated', $quiz_id, $question_id, $updated_scoring_data, $essay_post );
				}
			}
		}
	}

	/**
	 *
	 */
	public function _essay_bulk_actions_approve() {
		if ( isset( $_REQUEST['_uogm_essay_action'] ) && $_REQUEST['_uogm_essay_action'] === 'sfwd-essay' ) {
			if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'approve_essay' ) {
				$_REQUEST['post_type'] = 'sfwd-essays';
				$this->essay_bulk_actions_approve();
				unset( $_REQUEST['post_type'] );
			} elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'trash_essay' ) {
				$this->trash_essays();
			}
		}
	}

	/**
	 *
	 */
	public function trash_essays() {
		$essay_ids = ulgm_filter_input( 'post', INPUT_POST );
		if ( ! empty( $essay_ids ) ) {
			foreach ( $essay_ids as $essay_id ) {
				$essay = get_post( $essay_id );
				if ( ! empty( $essay ) && $essay->post_type === 'sfwd-essays' ) {
					learndash_before_delete_essay( $essay_id );
					$return = wp_trash_post( $essay_id );
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function edit_essays() {
		//check_ajax_referer( 'edit_essays', 'security' );

		$sfwd_lms = new \SFWD_LMS();
		$essay_id = absint( ulgm_filter_input( 'essay_id', INPUT_POST ) );
		$essay    = null;
		if ( ! empty( $essay_id ) ) {
			$essay = get_post( $essay_id );
		}
		if ( ! empty( $essay ) ) {
			$post_type        = $essay->post_type;
			$post_type_object = get_post_type_object( $post_type );
			$can_publish      = current_user_can( $post_type_object->cap->publish_posts );
			$quiz_id          = get_post_meta( $essay->ID, 'quiz_id', true );
			$question_id      = get_post_meta( $essay->ID, 'question_id', true );

			if ( ! empty( $quiz_id ) ) {
				$questionMapper = new \WpProQuiz_Model_QuestionMapper();
				$question       = $questionMapper->fetchById( intval( $question_id ), null );

			}

			if ( $question && is_a( $question, 'WpProQuiz_Model_Question' ) ) {
				$submitted_essay_data = learndash_get_submitted_essay_data( $quiz_id, $question->getId(), $essay );
			}

			$essay_content = esc_attr( $essay->post_content );
			$upload        = get_post_meta( $essay_id, 'upload', true );
			if ( ! empty( $upload ) ) {
				$essay_content .= '<br/><a target="_blank" href="' . $upload . '">' . __( 'User Upload', 'uncanny-learndash-groups' ) . ' </a>';
			}
			ob_start();

			?>
			<div class="group-management-modal group-essay-modal modal">
				<form id="edit_essay_form" method="post">
					<input name="essay_id" type="hidden" value="<?php echo $essay->ID; ?>"/>
					<div class="uo-groups">
						<div class="group-management-form">
							<div class="uo-groups-message-ok" id="group-management-message"></div>

							<?php wp_nonce_field( 'ld-essay-nonce-' . $essay->ID, 'ld-essay-nonce' ); ?>

							<div class="uo-row">
								<div class="uo-row__title" id="title-prompt-text" for="title">
									<?php esc_html_e( 'Essay Title', 'uncanny-learndash-groups' ); ?>
								</div>
								<?php echo $essay->post_title; ?>
							</div>
							<div class="uo-row">
								<?php if ( $question && is_a( $question, 'WpProQuiz_Model_Question' ) ) : ?>
									<div class="uo-row__title">
										<?php esc_html_e( 'Essay Question', 'uncanny-learndash-groups' ); ?>
									</div>
									<?php echo $question->getQuestion(); ?>
								<?php endif; ?>
							</div>
							<div class="uo-row">
								<div class="uo-row__title"><?php echo __( 'Content' ); ?></div>
								<div class="uo-row__content"><?php echo $essay_content; ?></div>
							</div>


							<div class="uo-row">
								<div class="uo-row__title"><?php _e( 'Author' ); ?></div>
								<?php
								$author = get_userdata( $essay->post_author );
								echo $author->first_name . ' ' . $author->last_name . ' (' . $author->user_login . ')';
								?>
							</div>

							<div class="uo-row">
								<div class="uo-row__title">
									<?php esc_html_e( 'Approval Status', 'uncanny-learndash-groups' ); ?>
								</div>

								<?php if ( 'not_graded' == $essay->post_status || 'graded' == $essay->post_status || $can_publish ) : ?>
									<div id="post-status-select">
										<select name='post_status' id='post_status'>
											<option<?php selected( $essay->post_status, 'not_graded' ); ?>
													value='not_graded'><?php esc_html_e( 'Not Graded', 'uncanny-learndash-groups' ); ?></option>
											<option<?php selected( $essay->post_status, 'graded' ); ?>
													value='graded'><?php esc_html_e( 'Graded', 'uncanny-learndash-groups' ); ?></option>
										</select>
									</div>

								<?php endif; ?>

							</div>

							<?php if ( $question && is_a( $question, 'WpProQuiz_Model_Question' ) ) : ?>
								<div class="uo-row uo-row-modal-points">
									<div class="uo-row-modal-points__col">
										<div class="uo-row__title">
											<?php esc_html_e( 'Points available', 'uncanny-learndash-groups' ); ?>
										</div>
										<?php echo $question->getPoints(); ?>
									</div>
									<div class="uo-row-modal-points__col">
										<div class="uo-row__title">
											<?php esc_html_e( 'Points awarded', 'uncanny-learndash-groups' ); ?>
										</div>

										<input name="points_awarded" type="number" min="0"
											   max="<?php echo $question->getPoints(); ?>"
											   value="<?php echo $submitted_essay_data['points_awarded']; ?>">
									</div>

									<input name="original_points_awarded" type="hidden"
										   value="<?php echo $submitted_essay_data['points_awarded']; ?>">
									<input name="quiz_id" type="hidden" value="<?php echo $quiz_id; ?>">
									<input name="ques_id" type="hidden" value="<?php echo $question->getId(); ?>">
								</div>
							<?php else : ?>
								<div class="uo-row">
									<p><?php esc_html_e( 'We could not find the essay question for this response', 'uncanny-learndash-groups' ); ?></p>
								</div>
							<?php endif; ?>

							<?php
							$essay_quiz_post_id = get_post_meta( $essay->ID, 'quiz_post_id', true );
							if ( empty( $essay_quiz_post_id ) ) {

								$essay_quiz_query_args = array(
									'post_type'    => 'sfwd-quiz',
									'post_status'  => 'publish',
									'meta_key'     => 'quiz_pro_id_' . intval( $quiz_id ),
									'meta_value'   => intval( $quiz_id ),
									'meta_compare' => '=',
									'fields'       => 'ids',
									'orderby'      => 'title',
									'order'        => 'ASC',
								);

								$essay_quiz_query = new \WP_Query( $essay_quiz_query_args );
								if ( count( $essay_quiz_query->posts ) > 1 ) {
									?>
									<div class="uo-row">
										<div class="uo-row__title">
											<?php echo sprintf( esc_html_x( 'Essay %s', 'Essay Quiz', 'uncanny-learndash-groups' ), LearnDash_Custom_Label::get_label( 'quiz' ) ); ?>
										</div>

										<select name="essay_quiz_post_id">
											<option value=""><?php echo sprintf( esc_html_x( 'No %s', 'No Quiz', 'uncanny-learndash-groups' ), LearnDash_Custom_Label::get_label( 'quiz' ) ); ?></option>
											<?php
											foreach ( $essay_quiz_query->posts as $quiz_post_id ) {
												?>
												<option
												value="<?php echo $quiz_post_id; ?>"><?php echo get_the_title( $quiz_post_id ); ?></option>
																  <?php
											}
											?>
										</select>
									</div>
									<?php

								} else {
									$essay_quiz_post_id = $essay_quiz_query->posts[0];
								}
							}

							if ( ! empty( $essay_quiz_post_id ) ) {
								?>

								<div class="uo-row">
									<div class="uo-row__title">
										<?php echo sprintf( esc_html_x( 'Essay %s', 'Essay Quiz', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'quiz' ) ); ?>
									</div>

									<?php echo get_the_title( $essay_quiz_post_id ); ?>
								</div>

								<?php

								$essay_quiz_course_id = get_post_meta( $essay_quiz_post_id, 'course_id', true );
								if ( ! empty( $essay_quiz_course_id ) ) {
									?>

									<div class="uo-row">
										<div class="uo-row__title">
											<?php echo sprintf( esc_html_x( 'Essay %s', 'Essay Course', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ); ?>
										</div>

										<?php echo get_the_title( $essay_quiz_course_id ); ?>
									</div>

									<?php

									$essay_quiz_lesson_id = get_post_meta( $essay_quiz_post_id, 'lesson_id', true );
									if ( ! empty( $essay_quiz_lesson_id ) ) {
										?>

										<div class="uo-row">
											<div class="uo-row__title">
												<?php echo sprintf( esc_html_x( 'Essay %s', 'Essay Lesson', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lesson' ) ); ?>
											</div>
											<?php echo get_the_title( $essay_quiz_lesson_id ); ?>
										</div>

										<?php
									}
								}
							}
							?>

							<?php
							/* translators: Publish box date format, see http://php.net/date */
							$datef = esc_html__( 'M j, Y @ H:i' );
							if ( 0 != $essay->ID ) :
								$date = date_i18n( $datef, strtotime( $essay->post_date ) );
							endif;

							if ( $can_publish ) : // Contributors don't get to choose the date of publish
								?>
								<div class="uo-row">
									<div class="uo-row__title">
										<?php esc_html_e( 'Submitted on', 'uncanny-learndash-groups' ); ?>
									</div>

									<span id="timestamp">
										<?php echo $date; ?>
									</span>
								</div>
							<?php endif; ?>

							<div class="uo-row-footer">
								<button id="uo-essay-edit-button" class="uo-btn" type="button">
									<?php esc_html_e( 'Save', 'uncanny-learndash-groups' ); ?>
								</button>
							</div>
						</div>
					</div>
				</form>

			</div>

			<?php
			$html = ob_get_clean();
		}

		return array( 'html' => $html );
	}

	/**
	 * @return array
	 */
	public function save_essays() {
		$post_data  = $_POST;
		$update_arr = array();

		if ( isset( $_REQUEST['post_status'] ) && $_REQUEST['post_status'] === 'trash_essay' ) {
			$_POST['post'] = array( $post_data['essay_id'] );
			$this->trash_essays();

			return array(
				'success' => true,
				'message' => __( 'Essay has been removed.', 'uncanny-learndash-groups' ),
			);
		}

		$update_arr['post_status'] = $post_data['post_status'];
		$update_arr['ID']          = $post_data['essay_id'];
		if ( ! isset( $post_data['essay_points'] ) ) {
			wp_update_post( $update_arr );
			$essay_id    = intval( ulgm_filter_input( 'essay_id', INPUT_POST ) );
			$essay       = get_post( $essay_id );
			$quiz_id     = intval( ulgm_filter_input( 'quiz_id', INPUT_POST ) );
			$question_id = intval( ulgm_filter_input( 'ques_id', INPUT_POST ) );

			$submitted_essay = learndash_get_submitted_essay_data( $quiz_id, $question_id, $essay );

			if ( ( ulgm_filter_has_var( 'essay_quiz_post_id', INPUT_POST ) ) && ( ! empty( ulgm_filter_input( 'essay_quiz_post_id', INPUT_POST ) ) ) ) {
				$essay_quiz_post_id = intval( ulgm_filter_input( 'essay_quiz_post_id', INPUT_POST ) );
				update_post_meta( $essay_id, 'quiz_post_id', $essay_quiz_post_id );
			}

			$quiz_score_difference = 0;
			if ( ulgm_filter_has_var( 'post_status', INPUT_POST ) ) {
				if ( ( ulgm_filter_input( 'post_status', INPUT_POST ) != $submitted_essay['status'] ) ) {
					if ( ulgm_filter_input( 'post_status', INPUT_POST ) == 'graded' ) {
						$quiz_score_difference = 1;
					} elseif ( ulgm_filter_input( 'post_status', INPUT_POST ) == 'not_graded' ) {
						$quiz_score_difference = - 1;
					}
				}
			}

			$submitted_essay['status']         = esc_html( ulgm_filter_input( 'post_status', INPUT_POST ) );
			$submitted_essay['points_awarded'] = intval( ulgm_filter_input( 'points_awarded', INPUT_POST ) );

			/**
			 * Filter essay status data
			 */
			$submitted_essay = apply_filters( 'learndash_essay_status_data', $submitted_essay );
			learndash_update_submitted_essay_data( $quiz_id, $question_id, $essay, $submitted_essay );

			$original_points_awarded = ulgm_filter_has_var( 'original_points_awarded', INPUT_POST ) ? intval( ulgm_filter_input( 'original_points_awarded', INPUT_POST ) ) : null;
			$points_awarded          = ulgm_filter_has_var( 'points_awarded', INPUT_POST ) ? intval( ulgm_filter_input( 'points_awarded', INPUT_POST ) ) : null;

			if ( ! is_null( $original_points_awarded ) && ! is_null( $points_awarded ) ) {
				if ( $points_awarded > $original_points_awarded ) {
					$points_awarded_difference = intval( $points_awarded ) - intval( $original_points_awarded );
				} else {
					$points_awarded_difference = ( intval( $original_points_awarded ) - intval( $points_awarded ) ) * - 1;
				}

				$updated_scoring = array(
					'updated_question_score'    => $points_awarded,
					'points_awarded_difference' => $points_awarded_difference,
					'score_difference'          => $quiz_score_difference,
				);

				/**
				 * Filter updated scoring data
				 */
				$updated_scoring = apply_filters( 'learndash_updated_essay_scoring', $updated_scoring );
				learndash_update_quiz_data( $quiz_id, $question_id, $updated_scoring, $essay );

				/**
				 * Perform action after all the quiz data is updated
				 */
				do_action( 'learndash_essay_all_quiz_data_updated', $quiz_id, $question_id, $updated_scoring, $essay );
			}
		}
		if ( isset( $post_data['essay_points'] ) ) {

			$_REQUEST['post_type'] = 'sfwd-essays';
			$_REQUEST['post']      = array( $post_data['essay_id'] );
			$_REQUEST['ld_action'] = 'approve_essay';
			$this->essay_bulk_actions_approve();
			unset( $_REQUEST['post_type'] );
		}

		return array(
			'success' => true,
			'message' => __( 'Your changes have been saved.', 'uncanny-learndash-groups' ),
		);
	}
}
