<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class GroupAssignments
 *
 * @package uncanny_learndash_groups
 */
class GroupAssignments {

	/**
	 * Group assignment shortcode.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string
	 */
	public static $ulgm_assignments_shortcode = array();
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
	private $root_path = 'ulgm_assignments_report/v1';

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
		// Commented following line as its not useful anymore
		// add_action( 'init', [ $this, '_assignment_bulk_actions_approve' ] );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	/**
	 *
	 */
	public function run_frontend_hooks() {
		add_shortcode( 'uo_groups_assignments', array( $this, 'display_assignments' ) );
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
	public function display_assignments( $request ) {

		$request = shortcode_atts(
			array(
				'columns'             => 'Title, First name, Last name, Username, Status, Points, Assigned Course, Assigned Lesson, Comments, Date',
				'status'              => 'not-approved',
				'excel_export_button' => 'hide',
				'csv_export_button'   => 'hide',

				'orderby_column'      => 'Date', // The ID of the column used to sort
				'order_column'        => 'desc', // Designates the ascending or descending order of the ‘orderby‘ parameter
			),
			$request
		);
		$html    = $this->generate_assignments_html( $request );
		$this->assignments_scripts( $request );

		return $html;
	}

	/*
	 * Generate Assignments HTML Output
	 *
	 * @return string
	 */
	/**
	 * @return false|string|void
	 */
	public function generate_assignments_html( $request = array() ) {

		self::$ulgm_assignments_shortcode['text']['group_management_link'] = SharedFunctions::get_group_management_page_id( true );

		self::$ulgm_assignments_shortcode['text']['group_management'] = __( 'Back to Group Management', 'uncanny-learndash-groups' );

		return $this->create_assignment_table( $request );
	}

	/*
	 * Generate Assignments HTML Table
	 *
	 * @return string
	 */
	/**
	 * @return false|string|void
	 */
	public function create_assignment_table( $request = array() ) {
		$user_id = get_current_user_id();

		// Check if we have to add the export buttons
		if ( ! isset( $request['excel_export_button'] ) ) {
			$request['excel_export_button'] = 'hide';
		}

		if ( ! isset( $request['csv_export_button'] ) ) {
			$request['csv_export_button'] = 'hide';
		}

		self::$table_options = array(
			'orderBy' => $request['orderby_column'], // The ID of the column used to sort
			'order'   => $request['order_column'], // Designates the ascending or descending order of the ‘orderby‘ parameter
		);

		$status = 'not-approved';
		if ( isset( $request['status'] ) ) {
			if ( 'all' === $request['status'] ) {
				$status = 'all';
			} elseif ( 'approved' === $request['status'] ) {
				$status = 'approved';
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
		ob_start();
		?>

		<script>

		var ulgmAssignmentReportShortcode = {
			table: {
				orderBy: '<?php esc_attr_e( 'Date', 'uncanny-learndash-groups' ); ?>',
				order: 'desc'
			}
		}

		try {
			ulgmAssignmentReportShortcode.table = <?php echo json_encode( self::$table_options, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS ); ?>;
		} catch ( e ) {
			console.warn( e );
		}

		</script>

		<div class="uo-groups uo-reports">
			<?php if ( ! empty( SharedFunctions::get_group_management_page_id() ) && ! empty( SharedFunctions::get_group_assignment_report_page_id() ) ) : ?>
				<div class="uo-row uo-groups-section uo-groups-report-go-back">
					<div class="uo-groups-actions">
						<div class="group-management-buttons">
							<button class="ulgm-link uo-btn uo-left uo-btn-arrow-left"
									onclick="location.href='<?php echo self::$ulgm_assignments_shortcode['text']['group_management_link']; ?>'"
									type="button">
								<?php echo self::$ulgm_assignments_shortcode['text']['group_management']; ?>
							</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="uo-groups uo-quiz-report uo-groups-assignments" id="uo-groups-assignments-management">
				<form id="uo-groups-assignments-management-form" method="post">

					<!-- <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce(); ?>"> -->
					<input type="hidden" name="_wp_http_referer" value="<?php echo $current_page; ?>">
					<input type="hidden" name="action" id="bulk-action-selector-top" value="">
					<input type="hidden" name="post[]" id="bulk-post-selector-top" value="">
					<input type="hidden" name="_uogm_assignment_action" value="sfwd-assignment">
					<div class="uo-row uo-groups-section uo-groups-report-go-back">
						<div class="uo-groups-actions">
							<div class="group-management-buttons">
								<!-- <button class="ulgm-link uo-btn uo-left uo-btn-arrow-left"
								onclick="location.href='<?php echo self::$ulgm_assignments_shortcode['text']['group_management_link']; ?>'"
								type="button">
							<?php echo self::$ulgm_assignments_shortcode['text']['group_management']; ?>
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
											<label><?php _e( \LearnDash_Custom_Label::get_label( 'course' ), 'uncanny-learndash-groups' ); ?></label>
											<select class="change-group-management-form <?php echo isset( $group_drop_downs['courses_class'] ) ? $group_drop_downs['courses_class'] : ''; ?>"
													style="display:none;" id="uo-group-report-courses"
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
												 style="display: none;"><?php echo sprintf( _x( 'No %s found.', 'No Lessons found', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lessons' ) ); ?></div>
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
											<option value="approved" 
											<?php
											if ( 'approved' === $status ) {
												?>
												 selected <?php } ?>><?php _e( 'Approved', 'uncanny-learndash-groups' ); ?></option>
											<option value="not-approved" 
											<?php
											if ( 'not-approved' === $status ) {
												?>
												 selected <?php } ?>><?php _e( 'Not approved', 'uncanny-learndash-groups' ); ?></option>
										</select>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class="uo-row uo-groups-table">
						<table id="uo-group-assignments-table-hidden"
							   class="display responsive no-wrap uo-table-datatable"
							   cellspacing="0" width="100%" style="display: none;"></table>

						<table 
							id="uo-group-assignments-table"
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
			</style>

			<script>
				jQuery(document).ready(function () {
					jQuery('select.h3-select').prop('disabled', 'disabled');

					let groupSelect = jQuery('#uo-group-report-group');
					let groupSelectOptions = jQuery('#uo-group-report-group option');

					if (2 === groupSelectOptions.length) {
						groupSelect.addClass('h3-select');
						let groupId = jQuery(groupSelect.find('option')[1]).val();
						window.ulgmGroupManagement.Assignments.groupId = groupId;
						groupSelect.val(groupId).trigger('change');

						groupSelect.prop('disabled', 'disabled');

						let courseGrouplessons = groupAssignments.courseGrouplessons[groupId];
						let coursesSelect = jQuery('#uo-group-report-courses');
						let coursesSelectOptions = jQuery('#uo-group-report-courses option');
						coursesSelect.removeAttr('disabled');

						if (2 === coursesSelectOptions.length) {

							coursesSelect.show();
							coursesSelect.addClass('h3-select');
							// below line commented BY_AC
							let courseId = jQuery(coursesSelect.find('option')[1]).val();
							//console.log(courseId);
							window.ulgmGroupManagement.Assignments.courseId = courseId;
							coursesSelect.val(courseId).trigger('change');
							coursesSelect.prop('disabled', 'disabled');

							jQuery('#uo-group-report-lessons').show();
							jQuery('#uo-group-report-lessons option').hide();
							jQuery('#uo-group-report-lessons option').removeAttr('selected');
							// below line commented BY_AC
							jQuery('#uo-group-report-lessons option[value=0]').show();

							let groupslessons = array();
							if (typeof groupAssignments.relationships !== 'undefined' && typeof groupAssignments.relationships[window.ulgmGroupManagement.Assignments.groupId] !== 'undefined' && typeof groupAssignments.relationships[window.ulgmGroupManagement.Assignments.groupId][courseId] !== 'undefined') {
								groupslessons = groupAssignments.relationships[window.ulgmGroupManagement.Assignments.groupId][courseId];
							}

							if (typeof groupslessons !== 'undefined' && groupslessons.length > 0) {
								jQuery.each(groupslessons, function (key, quizId) {
									jQuery('#uo-group-report-lessons option[value=' + quizId + ']').addClass('showme');
									jQuery('#uo-group-report-lessons option[value=' + quizId + ']').css('display', 'block');
									jQuery('#uo-group-report-lessons option[value=' + quizId + ']').show();
								});
								// need a delay and let jquery finish actions in each loop
								setTimeout(function () {
									jQuery('#uo-group-report-lessons').trigger('change');
								}, 200);
							} else {
								jQuery('#uo-group-report-lessons option').hide();
								jQuery('#uo-group-report-lessons option[value=' + 0 + ']').show();
								//jQuery('#uo-group-report-nolessons').show();
							}
						} else {
							coursesSelect.show();
							jQuery('#uo-group-report-courses option').hide();
							jQuery('#uo-group-report-courses option').removeAttr('selected');
							jQuery('#uo-group-report-courses option[0]').attr("selected", "selected");
							jQuery('#uo-group-report-courses option[0]').show();
							jQuery('#uo-group-report-courses option[value=' + 0 + ']').show();
							jQuery.each(courseGrouplessons, function (key, courseId) {
								jQuery('#uo-group-report-courses option[value=' + courseId + ']').show();
							});
							setTimeout(function () {
								jQuery('#uo-group-report-courses').trigger('change');
							}, 400);
						}
						window.ulgmGroupManagement.Assignments.groupId = groupId;
					} else {
						// need a delay and let jquery finish actions in each loop
						setTimeout(function () {
							let groupId = jQuery(groupSelect.find('option')[0]).val();
							window.ulgmGroupManagement.Assignments.groupId = groupId;
							groupSelect.val(groupId).trigger('change');

						}, 200);
					}
				})
			</script>
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

		$drop_down['groups'] = '<option value="0">' . __( 'Select Group', 'uncanny-learndash-groups' ) . '</option>';
		//$drop_down['groups'] = '';
		$drop_down['lessons_objects'] = array();

		// Collect all the quiz IDs so we can query then altogether
		$course_ids = array();
		$quiz_ids   = array();

		if ( $group_post_objects ) {
			foreach ( $group_post_objects as $group_post_object ) {
				$group_post_id       = $group_post_object->ID;
				$drop_down['groups'] .= '<option value="' . $group_post_id . '">' . $group_post_object->post_title . '</option>';

				$group_lessons = $this->group_lessons( $group_post_id );

				$drop_down['lessons_objects'][ $group_post_id ] = $group_lessons['group_lesson_ids'];

				$drop_down['course_lessons_objects'][ $group_post_id ] = $group_lessons['group_course_lessons'];

				$course_ids = array_merge( $course_ids, $group_lessons['group_course_lessons'] );
				$quiz_ids   = array_merge( $quiz_ids, $group_lessons['group_lesson_ids'] );

				$drop_down['relationships'][ $group_post_id ] = $group_lessons['relationships'];
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
			$drop_down['groups'] = '<option value="0">' . __( 'No Groups', 'uncanny-learndash-groups' ) . '</option>';
		}

		// Get Courses
		$course_ids = array_unique( $course_ids );

		$courses = $this->get_objects( $course_ids, 'sfwd-courses', 'title', 'ASC' );

		if ( ! empty( $courses ) ) {
			// below line commented BY_AC
			$drop_down['courses'] = '<option value="0">' . sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ) . '</option>';
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

			$drop_down['lessons'] = '';
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
			'posts_per_page' => 9999,
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
	public function assignments_scripts( $request = array() ) {
		global $post;

		if ( Utilities::has_shortcode( $post, 'uo_groups_assignments' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-assignments-report' ) ) {
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
				'assignedCourse' => in_array( __( 'Assigned course', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'assignedlesson' => in_array( __( 'Assigned lesson', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'comments'       => in_array( __( 'Comments', 'uncanny-learndash-groups' ), $columns ) ? true : false,
				'date'           => in_array( __( 'Date', 'uncanny-learndash-groups' ), $columns ) ? true : false,
			);

			foreach ( $columns_visibilities as $column_key => $column_bool ) {
				if ( false === $column_bool ) {
					if ( in_array( $column_key, $columns ) ) {
						$columns_visibilities[ $column_key ] = true;
					}
				}
			}

			// Front End Questionnaire JS
			wp_enqueue_script( 'ulgm-frontend', Utilities::get_asset( 'frontend', 'bundle.min.js' ), array( 'jquery' ), Utilities::get_version(), true );

			$group_drop_downs = $this->get_groups_drop_downs();

			// Attach API data to ulgm-frontend
			$api_setup = array(
				'root'                 => esc_url_raw( rest_url() . $this->root_path . '/' ),
				'nonce'                => \wp_create_nonce( 'wp_rest' ),
				'ajax_nonce'           => \wp_create_nonce( 'edit_assignments' ),
				'ajaxurl'              => admin_url( 'admin-ajax.php' ),
				'currentUser'          => get_current_user_id(),
				'localized'            => $this->get_frontend_localized_strings(),
				'grouplessons'         => isset( $group_drop_downs['lessons_objects'] ) ? $group_drop_downs['lessons_objects'] : array(),
				'courseGrouplessons'   => isset( $group_drop_downs['course_lessons_objects'] ) ? $group_drop_downs['course_lessons_objects'] : array(),
				'relationships'        => isset( $group_drop_downs['relationships'] ) ? $group_drop_downs['relationships'] : array(),
				'columns_visibilities' => $columns_visibilities,

				'i18n'                 => array(
					'CSV'            => __( 'CSV', 'uncanny-learndash-groups' ),
					'exportCSV'      => __( 'CSV export', 'uncanny-learndash-groups' ),
					'excel'          => __( 'Excel', 'uncanny-learndash-groups' ),
					'exportExcel'    => __( 'Excel export', 'uncanny-learndash-groups' ),
					'reportBaseName' => __( 'Assignment report', 'uncanny-learndash-groups' ),
					'notApproved'    => __( 'Not Approved', 'uncanny-learndash-groups' ),
				),
			);

			wp_localize_script( 'ulgm-frontend', 'groupAssignments', $api_setup );

			wp_enqueue_script( 'ulgm-frontend' );

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

		$localized_strings['first_name'] = __( 'First name', 'uncanny-learndash-groups' );

		$localized_strings['last_name'] = __( 'Last name', 'uncanny-learndash-groups' );

		$localized_strings['author'] = __( 'Username', 'uncanny-learndash-groups' );

		$localized_strings['status'] = __( 'Status', 'uncanny-learndash-groups' );

		$localized_strings['points'] = __( 'Points', 'uncanny-learndash-groups' );

		$localized_strings['assignedCourse'] = sprintf( __( 'Assigned %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );

		$localized_strings['assignedlesson'] = sprintf( __( 'Assigned %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lesson' ) );

		$localized_strings['customizeColumns'] = __( 'Customize columns', 'uncanny-learndash-groups' );

		$localized_strings['hideCustomizeColumns'] = __( 'Hide customize columns', 'uncanny-learndash-groups' );

		$localized_strings['comments'] = __( 'Comments', 'uncanny-learndash-groups' );

		$localized_strings['date'] = __( 'Date', 'uncanny-learndash-groups' );

		$localized_strings['csvExport'] = __( 'CSV export', 'uncanny-learndash-groups' );

		$localized_strings['selectCourse'] = sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) );

		$localized_strings['approveAssignments'] = __( 'Approve assignments', 'uncanny-learndash-groups' );

		$localized_strings['deleteAssignments'] = __( 'Delete assignments', 'uncanny-learndash-groups' );

		$localized_strings['noCourse'] = sprintf( __( 'No %s available', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) );

		$localized_strings['selectUser'] = __( 'Select user', 'uncanny-learndash-groups' );

		$localized_strings['noUsers'] = __( 'No users available', 'uncanny-learndash-groups' );

		$localized_strings['all'] = __( 'All', 'uncanny-learndash-groups' );

		$localized_strings['approve_assignments'] = __( 'Approve assignments', 'uncanny-learndash-groups' );

		$localized_strings['selectLesson'] = sprintf( __( 'Select %s', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lesson' ) );

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
			'/get_assignments_data/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'get_assignments_data' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);
		register_rest_route(
			$this->root_path,
			'/edit_assignments_data/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'edit_assignments' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);
		register_rest_route(
			$this->root_path,
			'/save_assignments_data/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_assignments' ),
				'permission_callback' => function () {
					return RestApiEndPoints::permission_callback_check();
				},
			)
		);
	}

	/**
	 * @return array
	 */
	public function get_assignments_data() {

		$data = $_POST;

		// validate inputs
		$lesson_ID = absint( $data['lessonId'] );
		$course_ID = absint( $data['courseId'] );
		$group_ID  = absint( $data['groupId'] );
		$status    = $data['status'];

		$assignments_table = $this->assignments_table( $lesson_ID, $course_ID, $group_ID, $status );

		$assignments_table = apply_filters( 'ulgm_rest_api_get_assignments_data', $assignments_table, $_POST );

		return $assignments_table;
	}

	/**
	 * Return html for the assignment table
	 *
	 * @param $lesson_ID
	 * @param $course_ID
	 * @param $group_ID
	 *
	 * @return array
	 *
	 */
	public function assignments_table( $lesson_ID = 0, $course_ID = 0, $group_ID = 0, $_status = 'not-approved' ) {

		global $learndash_shortcode_used;
		$assignments              = array();
		$learndash_shortcode_used = true;
		$user_id                  = get_current_user_id();
		$q_vars                   = array(
			'post_type'      => 'sfwd-assignment',
			'posts_per_page' => - 1,
		);

		if ( learndash_is_group_leader_user( $user_id ) || learndash_is_admin_user( $user_id ) ) {
			$group_ids  = learndash_get_administrators_group_ids( $user_id );
			$course_ids = array();
			$lesson_ids = array();
			$user_ids   = array();

			if ( ! empty( $group_ids ) && is_array( $group_ids ) ) {
				if ( absint( $group_ID ) != 0 ) {
					foreach ( $group_ids as $group_id ) {
						if ( $group_ID === absint( $group_id ) ) {
							$group_course_ids = learndash_group_enrolled_courses( $group_id, true );
							if ( ! empty( $group_course_ids ) && is_array( $group_course_ids ) ) {
								$course_ids = array_merge( $course_ids, $group_course_ids );
							}
							$lessons    = $this->group_lessons( $group_id );
							$lesson_ids = array_merge( $lesson_ids, $lessons['group_lesson_ids'] );

							$group_users = learndash_get_groups_user_ids( $group_id, true );
							if ( ! empty( $group_users ) && is_array( $group_users ) ) {
								foreach ( $group_users as $group_user_id ) {
									$user_ids[ $group_user_id ] = $group_user_id;
								}
							}
						}
					}
				} else {
					foreach ( $group_ids as $group_id ) {
						$group_course_ids = learndash_group_enrolled_courses( $group_id, true );
						if ( ! empty( $group_course_ids ) && is_array( $group_course_ids ) ) {
							$course_ids = array_merge( $course_ids, $group_course_ids );
						}
						$lessons    = $this->group_lessons( $group_id );
						$lesson_ids = array_merge( $lesson_ids, $lessons['group_lesson_ids'] );

						$group_users = learndash_get_groups_user_ids( $group_id, true );
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

				if ( $course_ID !== 0 && in_array( $course_ID, $course_ids ) ) {
					$course_ids = array( $course_ID );
				}

				if ( ! empty( $lesson_ids ) && count( $lesson_ids ) && $lesson_ID !== 0 && in_array( $lesson_ID, $lesson_ids ) ) {
					$q_vars['meta_query'][] = "'relation' => 'AND'";
					$lesson_ids             = array( $lesson_ID );
					$q_vars['meta_query'][] = array(
						'key'     => 'lesson_id',
						'value'   => $lesson_ids,
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

		$assignment_posts = get_posts( $q_vars );

		if ( ! empty( $assignment_posts ) ) {
			foreach ( $assignment_posts as $a_post ) {
				$assignment_id = $a_post->ID;
				$status        = '';

				$assignment_lesson_id = intval( get_post_meta( $assignment_id, 'lesson_id', true ) );
				$assignment_course_id = intval( get_post_meta( $assignment_id, 'course_id', true ) );
				if ( ! empty( $assignment_lesson_id ) ) {
					$approval_status_flag = learndash_is_assignment_approved_by_meta( $assignment_id );
					if ( $approval_status_flag == 1 ) {
						$approval_status_label = esc_html__( 'Approved', 'uncanny-learndash-groups' );
					} else {
						$approval_status_flag  = 0;
						$approval_status_label = esc_html__( 'Not Approved', 'uncanny-learndash-groups' );
					}

					$approval_status_url = admin_url( 'edit.php?post_type=sfwd-assignment&approval_status=' . $approval_status_flag );

					$status = $approval_status_label;
					if ( $approval_status_flag != 1 ) {
						$status .= '<button id="assignment_approve_' . $assignment_id . '" class="small assignment_approve_single">' . esc_html__( 'Approve', 'uncanny-learndash-groups' ) . '</button>';
					}
				}

				if ( 'not-approved' === $_status ) {
					if ( 1 === absint( $approval_status_flag ) ) {
						continue;
					}
				}

				if ( 'approved' === $_status ) {
					if ( 0 === absint( $approval_status_flag ) ) {
						continue;
					}
				}

				if ( learndash_assignment_is_points_enabled( $assignment_id ) ) {
					$max_points = 0;

					$assignment_settings_id = intval( get_post_meta( $assignment_id, 'lesson_id', true ) );
					if ( ! empty( $assignment_settings_id ) ) {
						$max_points = learndash_get_setting( $assignment_settings_id, 'lesson_assignment_points_amount' );
					}

					$current_points = get_post_meta( $assignment_id, 'points', true );
					if ( ( $current_points == 'Pending' ) || ( $current_points == '' ) ) {
						$approval_status_flag = learndash_is_assignment_approved_by_meta( $assignment_id );
						if ( $approval_status_flag != 1 ) {
							$current_points = '<input id="assignment_points_' . $assignment_id . '" class="small-text" type="number" value="0" max="' . $max_points . '" min="0" step="1" name="assignment_points[' . $assignment_id . ']" />';
						} else {
							$current_points = '0';
						}
					}
					$points = sprintf( esc_html_x( '%1$s / %2$s', 'placeholders: current points / maximum point for assignment', 'uncanny-learndash-groups' ), $current_points, $max_points );

				} else {
					$points = esc_html__( 'Not Enabled', 'uncanny-learndash-groups' );
				}
				$lesson        = get_post( $assignment_lesson_id );
				$course        = get_post( $assignment_course_id );
				$row_action    = '<span class="trash"><a href="#" id="a_assignment_trash_' . $assignment_id . '" class="delete_assignment_single">' . __( 'Trash', 'uncanny-learndash-groups' ) . '</a> </span>';
				$download_link = get_post_meta( $assignment_id, 'file_link', true );
				if ( ! empty( $download_link ) ) {
					$row_action .= " | <a href='" . $download_link . "' target='_blank'>" . esc_html__( 'Download', 'uncanny-learndash-groups' ) . '</a>';
				}

				$assignments[] = array(
					'id'                    => $a_post->ID,
					'title'                 => '<a title="' . $a_post->post_title . '" data-assignment-id="' . $a_post->ID . '" class="edit_assignment_single">' . __( 'View', 'uncanny-learndash-groups' ) . '</a><div class="row-actions">' . $row_action . '</div>',
					'first_name'            => get_the_author_meta( 'first_name', $a_post->post_author ),
					'last_name'             => get_the_author_meta( 'last_name', $a_post->post_author ),
					'author'                => '<a href="mailto:' . get_the_author_meta( 'email', $a_post->post_author ) . '" class="edit_assignment">' . get_the_author_meta( 'login', $a_post->post_author ) . '</a>',
					'status'                => $status,
					'points'                => $points,
					'assignedCourse'        => '<a href="' . get_permalink( $course ) . '">' . $course->post_title . '</a>',
					'assignedlesson'        => '<a href="' . get_permalink( $lesson ) . '">' . $lesson->post_title . '</a>',
					'comments'              => '<a target="_blank" href="' . get_permalink( $assignment_id ) . '#comments">' . get_comments_number( $assignment_id ) . '</a>',
					'date'                  => get_the_date( '', $a_post ),
					'$approval_status_flag' => $approval_status_flag,
					'$_status'              => $_status,
				);
			}
		}

		return $assignments;
	}

	/**
	 *
	 */
	public function _assignment_bulk_actions_approve() {
		if ( isset( $_REQUEST['_uogm_assignment_action'] ) && $_REQUEST['_uogm_assignment_action'] === 'sfwd-assignment' ) {
			if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'approve_assignment' ) {
				$_REQUEST['post_type'] = 'sfwd-assignment';
				$this->assignment_bulk_actions_approve();
				unset( $_REQUEST['post_type'] );
			} elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'trash_assignment' ) {
				$this->trash_assignments();
			}
		}
	}

	/**
	 * This function replaces learndash_assignment_bulk_actions_approve() which
	 * was dropped from LearnDash 3.2.3+
	 *
	 * @since 3.7.5
	 * @author Saad
	 */
	protected function assignment_bulk_actions_approve() {
		if ( ( ! isset( $_REQUEST['post'] ) ) || ( empty( $_REQUEST['post'] ) ) || ( ! is_array( $_REQUEST['post'] ) ) ) {
			return;
		}

		if ( ( ! isset( $_REQUEST['post_type'] ) ) || ( learndash_get_post_type_slug( 'assignment' ) !== $_REQUEST['post_type'] ) ) {
			return;
		}

		$action = '';
		if ( isset( $_REQUEST['action'] ) && - 1 != $_REQUEST['action'] ) {
			$action = esc_attr( $_REQUEST['action'] );

		} elseif ( isset( $_REQUEST['action2'] ) && - 1 != $_REQUEST['action2'] ) {
			$action = esc_attr( $_REQUEST['action2'] );

		} elseif ( ( isset( $_REQUEST['ld_action'] ) ) && ( 'approve_assignment' === $_REQUEST['ld_action'] ) ) {
			$action = 'approve_assignment';
		}

		if ( 'approve_assignment' === $action ) {
			if ( ( isset( $_REQUEST['post'] ) ) && ( ! empty( $_REQUEST['post'] ) ) ) {

				if ( ! is_array( $_REQUEST['post'] ) ) {
					$assignments = array( $_REQUEST['post'] );
				} else {
					$assignments = $_REQUEST['post'];
				}

				$assignment_points = false;

				if ( isset( $_REQUEST['assignment-points'] ) && ! empty( $_REQUEST['assignment-points'] ) ) {
					$assigned_points = $_REQUEST['assignment-points'];
				}

				foreach ( $assignments as $assignment_id ) {

					$assignment_post = get_post( $assignment_id );
					if ( ( ! empty( $assignment_post ) ) && ( is_a( $assignment_post, 'WP_Post' ) ) && ( learndash_get_post_type_slug( 'assignment' ) === $assignment_post->post_type ) ) {

						$user_id   = absint( $assignment_post->post_author );
						$lesson_id = get_post_meta( $assignment_post->ID, 'lesson_id', true );

						if ( learndash_assignment_is_points_enabled( $assignment_id ) === true ) {

							$assignment_settings_id = intval( get_post_meta( $assignment_id, 'lesson_id', true ) );
							if ( ! empty( $assignment_settings_id ) ) {
								$max_points = learndash_get_setting( $assignment_settings_id, 'lesson_assignment_points_amount' );
							}

							// Double check the assiged points is NOT larger than max points.
							$assignment_points = $assigned_points ? $assigned_points : $max_points;

							if ( $assignment_points > $max_points ) {
								$assignment_points = $max_points;
							}

							update_post_meta( $assignment_id, 'points', $assignment_points );
						}

						learndash_approve_assignment( $user_id, $lesson_id, $assignment_id );
					}
				}
			}
		}
		if ( 'trash_assignment' === $action ) {
			$this->trash_assignments();
		}
	}

	/**
	 *
	 */
	public function trash_assignments() {
		$assignment_ids = ulgm_filter_input_array( 'post', INPUT_POST );
		if ( ! empty( $assignment_ids ) ) {
			foreach ( $assignment_ids as $assignment_id ) {
				$assignment = get_post( $assignment_id );
				if ( ! empty( $assignment ) && $assignment->post_type === 'sfwd-assignment' ) {
					$return = wp_trash_post( $assignment_id );
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function edit_assignments() {
		//check_ajax_referer( 'edit_assignments', 'security' );

		$sfwd_lms      = new \SFWD_LMS();
		$assignment_id = absint( ulgm_filter_input( 'assignment_id', INPUT_POST ) );
		$assignment    = null;
		if ( ! empty( $assignment_id ) ) {
			$assignment = get_post( $assignment_id );
		}
		if ( ! empty( $assignment ) ) {
			$assignment_course_id = intval( get_post_meta( $assignment->ID, 'course_id', true ) );
			$assignment_lesson_id = intval( get_post_meta( $assignment->ID, 'lesson_id', true ) );
			ob_start();

			?>
			<div class="group-management-modal group-assignment-modal modal">
				<form id="edit_assignment_form" method="post">
					<input name="assignment_id" type="hidden" value="<?php echo $assignment->ID; ?>"/>
					<input name="post[]" type="hidden" value="<?php echo $assignment->ID; ?>"/>
					<input name="action" type="hidden" value="approve_assignment"/>
					<div class="uo-groups">
						<div class="group-management-form">
							<div class="uo-groups-message-ok" id="group-management-message"></div>

							<?php wp_nonce_field( 'ld-assignment-nonce-' . $assignment->ID, 'ld-assignment-nonce' ); ?>

							<div class="uo-row">
								<div class="uo-row__title">
									<?php echo __( 'Assignment Title', 'uncanny-learndash-groups' ); ?>
								</div>

								<?php echo esc_attr( $assignment->post_title ); ?>
							</div>

							<?php

							$file_link = get_post_meta( $assignment->ID, 'file_link', true );

							if ( ! empty( $file_link ) ) {
								?>

								<div class="uo-row" id="sfwd-assignment_download">
									<div class="uo-row__title">
										<?php _e( 'Actions', 'uncanny-learndash-groups' ); ?>
									</div>

									<?php
									// link handling
									$file_link = get_post_meta( $assignment->ID, 'file_link', true );
									echo "<a href='" . $file_link . "' target='_blank' class='button'>" . esc_html__( 'Download', 'uncanny-learndash-groups' ) . '</a>';
									?>
								</div>

								<?php
							}

							?>

							<div class="uo-row">
								<div class="uo-row__title">
									<?php _e( 'Author', 'uncanny-learndash-groups' ); ?>
								</div>

								<?php

								$author = get_userdata( $assignment->post_author );
								echo $author->first_name . ' ' . $author->last_name . ' (' . $author->user_login . ')';

								?>
							</div>

							<div class="uo-row">
								<div class="uo-row__title">
									<?php echo sprintf( esc_html_x( 'Associated %s', 'Associated Course Label', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ); ?>
								</div>

								<?php

								if ( empty( $assignment_course_id ) ) {
									?>
									<select name="sfwd-assignment_course">
										<option value=""><?php echo sprintf( esc_html_x( '-- Select a %s --', 'Select a Course Label', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'course' ) ); ?></option>
										<?php
										$cb_courses = array();
										if ( ! empty( $assignment_lesson_id ) ) {
											$cb_courses = learndash_get_courses_for_step( $assignment_lesson_id, true );
											if ( ! empty( $cb_courses ) ) {
												$cb_courses = array_keys( $cb_courses );
											}
										}

										$query_courses_args = array(
											'post_type'   => 'sfwd-courses',
											'post_status' => 'any',
											'posts_per_page' => - 1,
											'post__in'    => $cb_courses,
											'orderby'     => 'title',
											'order'       => 'ASC',
										);

										$query_courses = new WP_Query( $query_courses_args );

										if ( ! empty( $query_courses->posts ) ) {
											foreach ( $query_courses->posts as $p ) {
												?>
												<option
												value="<?php echo $p->ID; ?>"><?php echo $p->post_title; ?></option>
																  <?php
											}
										}
										?>
									</select>
									<?php
								} else {
									echo get_the_title( $assignment_course_id );
								}

								?>
							</div>

							<div class="uo-row">
								<div class="uo-row__title">
									<?php echo sprintf( esc_html_x( 'Associated %s', 'Associated Lesson Label', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lesson' ) ); ?>
								</div>

								<?php

								if ( empty( $assignment_lesson_id ) ) {
									?>
									<select name="sfwd-assignment_lesson">
										<option value=""><?php echo sprintf( esc_html_x( '-- Select a %s --', 'Select a Lesson Label', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'lesson' ) ); ?></option>
										<?php
										if ( ! empty( $assignment_course_id ) ) {
											$course_lessons = $sfwd_lms->select_a_lesson_or_topic( $assignment_course_id, true );
											if ( ! empty( $course_lessons ) ) {
												foreach ( $course_lessons as $l_id => $l_label ) {
													?>
													<option
													value="<?php echo $l_id; ?>"><?php echo $l_label; ?></option>
																	  <?php
												}
											}
										}
										?>
									</select>
									<?php
								} else {
									echo get_the_title( $assignment_lesson_id );
								}

								?>
							</div>

							<div class="uo-row">
								<div class="uo-row__title">
									<?php _e( 'Status', 'uncanny-learndash-groups' ); ?>
								</div>

								<?php

								$approval_status_flag = learndash_is_assignment_approved_by_meta( $assignment->ID );
								if ( $approval_status_flag == 1 ) {
									$approval_status_label = esc_html__( 'Approved', 'uncanny-learndash-groups' );
									echo $approval_status_label;
								} else {
									$approval_status_label = esc_html__( 'Not Approved', 'uncanny-learndash-groups' );
									echo $approval_status_label;
								}

								?>
							</div>

							<div class="uo-row">
								<div class="uo-row__title">
									<?php _e( 'Points', 'uncanny-learndash-groups' ); ?>
								</div>

								<?php

								if ( ( ! empty( $assignment_course_id ) ) && ( ! empty( $assignment_lesson_id ) ) ) {
									//$points_enabled = learndash_get_setting( $assignment_lesson_id, 'lesson_assignment_points_enabled' );

									//if ( $points_enabled == 'on' ) {
									if ( ( learndash_get_setting( $assignment_lesson_id, 'lesson_assignment_points_enabled' ) === 'on' ) && ( intval( learndash_get_setting( $assignment_lesson_id, 'lesson_assignment_points_amount' ) ) > 0 ) ) {
										$max_points     = intval( learndash_get_setting( $assignment_lesson_id, 'lesson_assignment_points_amount' ) );
										$current_points = intval( get_post_meta( $assignment->ID, 'points', true ) );

										echo "<label for='assignment-points'>" . sprintf( esc_html__( 'Awarded Points (Out of %d):', 'uncanny-learndash-groups' ), $max_points ) . '</label><br />';
										echo "<input name='assignment-points' type='number' min=0 max='{$max_points}' value='{$current_points}'>";
									}
								}

								?>
							</div>

							<div class="uo-row-footer">
								<?php

								if ( ( ! empty( $assignment_course_id ) ) && ( ! empty( $assignment_lesson_id ) ) ) {
									if ( ( learndash_get_setting( $assignment_lesson_id, 'lesson_assignment_points_enabled' ) === 'on' ) && ( intval( learndash_get_setting( $assignment_lesson_id, 'lesson_assignment_points_amount' ) ) > 0 ) ) {
										$update_text = learndash_is_assignment_approved_by_meta( $assignment->ID ) ? esc_html__( 'Update', 'uncanny-learndash-groups' ) : esc_html__( 'Update & Approve', 'uncanny-learndash-groups' );
										echo "<button name='save' type='button' class='uo-btn' id='uo-assignment-update-button' value='{$update_text}'>{$update_text}</button>";
									} else {
										echo esc_html__( 'Points not enabled', 'uncanny-learndash-groups' );
										if ( $approval_status_flag != 1 ) {
											$approve_text = esc_html__( 'Approve', 'uncanny-learndash-groups' );
											echo '<input name="assignment-status" type="hidden" value="' . $approve_text . '">
		                                    <input name="assignment_status" type="button" class="uo-btn" id="uo-assignment-update-button" value="' . $approve_text . '">';

										}
									}
								}

								?>
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
	public function save_assignments() {
		$_REQUEST['post_type'] = 'sfwd-assignment';
		$this->assignment_bulk_actions_approve();
		unset( $_REQUEST['post_type'] );

		return array(
			'success' => true,
			'message' => __( 'Your changes have been saved.', 'uncanny-learndash-groups' ),
		);
	}
}
