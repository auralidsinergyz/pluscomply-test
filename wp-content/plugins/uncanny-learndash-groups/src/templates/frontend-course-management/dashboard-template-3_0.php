<?php
/*
 * The template for displaying user courses on page by using [uo_dashboard] shortcode.
 *
 * This template can be overridden by adding absolute path of your template file by
 * using apply_filters( 'uo-dashboard-template', 'your_function_name' ) in functions.php
 * or copy this template to yourtheme/uo-plugin-pro/dashboard-template.php.
 *
 * Available Variables:
 * $courses 		          array of courses
 * $expanded_on_load          boolean. That defines if all the containers must be expanded on page load
 * $has_wp_category_dropdown: boolean. True if you have to render the WordPress category dropdown
 * $has_ld_category_dropdown: boolean. True if you have to render the LearnDash category dropdown
 * $wp_categories:            array of categories
 * $ld_categories:            array of categories
 * $has_custom_colors         boolean. True if the user has at least one custom color
 * $user_colors:              object
 *
 * @author  UncannyOwl
 * @package uo-plugin-pro/src/templates
 * @version 3.2
 *
 */

?>

<?php if ( $has_custom_colors ) { ?>

	<style>

		<?php if ( ! empty( $user_colors->toggle->disabled_bg ) ){ ?>

		/**
		 * Change background color of a disabled toggle button
		 */

		.ulg-manage-progress .ulg-manage-progress-course__toggle-btn,
		.ulg-manage-progress .ulg-manage-progress-lesson__toggle-btn {
			background-color: <?php echo $user_colors->toggle->disabled_bg; ?>;
		}

		<?php } ?>

		<?php if ( ! empty( $user_colors->toggle->expanded_bg ) ){ ?>

		/**
		 * Change background color of a expanded toggle button
		 */

		.ulg-manage-progress-course--expanding .ulg-manage-progress-course__row .ulg-manage-progress-course__toggle-btn,
		.ulg-manage-progress-course--expanded .ulg-manage-progress-course__row .ulg-manage-progress-course__toggle-btn,
		.ulg-manage-progress-lesson--expanding .ulg-manage-progress-lesson__row .ulg-manage-progress-lesson__toggle-btn,
		.ulg-manage-progress-lesson--expanded .ulg-manage-progress-lesson__row .ulg-manage-progress-lesson__toggle-btn {
			background-color: <?php echo $user_colors->toggle->expanded_bg; ?>;
		}

		<?php } ?>

		<?php if ( ! empty( $user_colors->toggle->expanded_icon ) ){ ?>

		/**
		 * Change icon color of a expanded toggle button
		 */

		.ulg-manage-progress-course--expanding .ulg-manage-progress-course__row .ulg-manage-progress-course__toggle-btn:before,
		.ulg-manage-progress-course--expanded .ulg-manage-progress-course__row .ulg-manage-progress-course__toggle-btn:before,
		.ulg-manage-progress-lesson--expanding .ulg-manage-progress-lesson__row .ulg-manage-progress-lesson__toggle-btn:before,
		.ulg-manage-progress-lesson--expanded .ulg-manage-progress-lesson__row .ulg-manage-progress-lesson__toggle-btn:before {
			color: <?php echo $user_colors->toggle->expanded_icon; ?>;
		}

		<?php } ?>

		<?php if ( ! empty( $user_colors->progress ) ){ ?>

		/**
		 * Change color used to represent progress
		 */

		.ulg-manage-progress .ulg-manage-progress-course__row .ulg-manage-progress-course__details .ulg-manage-progress-course__right .ulg-manage-progress-course__progress-bar {
			background-color: <?php echo $user_colors->progress; ?>;
		}

		.ulg-manage-progress .ulg-manage-progress-topic--completed .ulg-manage-progress-topic__row .ulg-manage-progress-topic__details .ulg-manage-progress-topic__right .ulg-manage-progress-topic__status .ulg-manage-progress-topic__status-circle,
		.ulg-manage-progress .ulg-manage-progress-lesson--completed .ulg-manage-progress-lesson__row .ulg-manage-progress-lesson__details .ulg-manage-progress-lesson__right .ulg-manage-progress-lesson__status .ulg-manage-progress-lesson__status-circle {
			background-color: <?php echo $user_colors->progress; ?>;
			border-color: <?php echo $user_colors->progress; ?>;
		}

		<?php } ?>

		<?php if ( ! empty( $user_colors->third_level_bg ) ){ ?>

		/**
		 * Change background color of third level rows
		 */

		.ulg-manage-progress .ulg-manage-progress-topic__row,
		.ulg-manage-progress .ulg-manage-progress-lesson__quizzes .ulg-manage-progress-quiz__row {
			background-color: <?php echo $user_colors->third_level_bg; ?>;
		}

		<?php } ?>

		<?php if ( ! empty( $user_colors->quiz->passed_bg ) ){ ?>

		/**
		 * Change background of the quiz score when the user passed it
		 */

		.ulg-manage-progress .ulg-manage-progress-quiz__row .ulg-manage-progress-quiz__details .ulg-manage-progress-quiz__right .ulg-manage-progress-quiz__score-label {
			background-color: <?php echo $user_colors->quiz->passed_bg; ?>;
			border-color: <?php echo $user_colors->quiz->passed_bg; ?>;
		}

		<?php } ?>

		<?php if ( ! empty( $user_colors->quiz->failed_bg ) ){ ?>

		/**
		 * Change background of the quiz score when the user failed it
		 */

		.ulg-manage-progress .ulg-manage-progress-quiz--failed .ulg-manage-progress-quiz__row .ulg-manage-progress-quiz__details .ulg-manage-progress-quiz__right .ulg-manage-progress-quiz__score-label {
			background-color: <?php echo $user_colors->quiz->failed_bg; ?>;
			border-color: <?php echo $user_colors->quiz->failed_bg; ?>;
		}

		<?php } ?>
	</style>

<?php } ?>
<?php
if ( false === $can_manage_progress ) {
	?>
	<style>
		.ulg-manage-progress-course--can-not-manage-progress .ulg-manage-progress-course__progress-action-checkbox,
		.ulg-manage-progress-course--can-not-manage-progress .ulg-manage-progress-lesson__progress-action-checkbox,
		.ulg-manage-progress-course--can-not-manage-progress .ulg-manage-progress-topic__progress-action-checkbox,
		.ulg-manage-progress-course--can-not-manage-progress .ulg-manage-progress-quiz__progress-action-checkbox {
			cursor: not-allowed !important;
		}
	</style>
<?php } ?>


<div class="ulg-manage-progress ulg-manage-progress--manage-progress">
	<div class="ulg-manage-progress-toolbar">
		<div class="ulg-manage-progress-filters"></div>

		<?php /*
		<div class="ulg-manage-progress-actions">
			<div class="ulg-manage-progress-btn ulg-manage-progress-btn--expand-all">
				<?php _e( 'Expand all', 'uncanny-learndash-groups' ); ?>
			</div>
			<div class="ulg-manage-progress-btn ulg-manage-progress-btn--collapse-all">
				<?php _e( 'Collapse all', 'uncanny-learndash-groups' ); ?>
			</div>
		</div> */ ?>
	</div>

	<div class="ulg-manage-progress-box">
		<div class="ulg-manage-progress-courses">

			<?php foreach ( $courses as $course ) {

				// Set array with the extra classes we're going to
				// add to the course div
				$course_css_classes = array();

				// Check if we have to expand it on page load
				if ( $expanded_on_load && ( $course->has_lessons || $course->has_quizzes ) ) {
					$course_css_classes[] = 'ulg-manage-progress-course--expanded';
				} else {
					$course_css_classes[] = 'ulg-manage-progress-course--collapsed';
				}

				// Set a class to define the progress status
				// This will be completed || in-progress || not-started
				$course_css_classes[] = sprintf( 'ulg-manage-progress-course--%s', $course->status );

				// Set class depending if it has or not lessons
				if ( $course->has_lessons ) {
					$course_css_classes[] = 'ulg-manage-progress-course--has-lessons';
				} else {
					$course_css_classes[] = 'ulg-manage-progress-course--does-not-have-lessons';
				}

				// Set class depending if it has or not quizzes
				if ( $course->has_quizzes ) {
					$course_css_classes[] = 'ulg-manage-progress-course--has-quizzes';
				} else {
					$course_css_classes[] = 'ulg-manage-progress-course--does-not-have-quizzes';
				}

				// Set a class depending if the user can manage or not the progress
				if ( ! $can_manage_progress ) {
					$course_css_classes[] = 'ulg-manage-progress-course--can-not-manage-progress';
				}

				?>

				<div class="ulg-manage-progress-course <?php echo implode( ' ', $course_css_classes ); ?>"
					 data-course-id="<?php echo $course->id; ?>"
					 data-status="<?php echo $course->status; ?>"
					 data-has-lessons="<?php echo $course->has_lessons ? 1 : 0; ?>"
					 data-has-quizzes="<?php echo $course->has_quizzes ? 1 : 0; ?>"
					 data-has-certificate="<?php echo $course->has_certificate ? 1 : 0; ?>">

					<div class="ulg-manage-progress-course__row">
						<div class="ulg-manage-progress-course__toggle-btn"></div>
						<div class="ulg-manage-progress-course__details">
							<div class="ulg-manage-progress-course__left">
								<div class="ulg-manage-progress-course__progress-actions">
									<div class="ulg-manage-progress-course__progress-action-checkbox">
										<span class="ulg-manage-progress-course__progress-action-confirm-text">
											<?php _e( 'Confirm', 'uncanny-learndash-groups' ); ?>
										</span>
									</div>
									<div class="ulg-manage-progress-course__progress-action-cancel">
										<?php _e( 'Cancel', 'uncanny-learndash-groups' ); ?>
									</div>
								</div>

								<div class="ulg-manage-progress-course__name">
									<?php echo ! empty( $course->title ) ? $course->title : __( '(no title)', 'uncanny-learndash-groups' ); ?>
								</div>
							</div>
							<div class="ulg-manage-progress-course__right">
								<?php

								$action = (object) [
										'text'   => '',
										'url'    => '',
										'target' => '_self',
								];

								// Define what button we will show
								// Check if the course is completed
								if ( $course->status == 'completed' ) {
									if ( $course->has_certificate ) {
										// "Certificate" button
										$action->text   = __( 'Certificate', 'uncanny-learndash-groups' );
										$action->url    = $course->certificate_url;
										$action->target = '_blank';
									}
								}

								?>

								<?php if ( ! empty( $action->text ) ) { ?>

									<div class="ulg-manage-progress-course__action">
										<a href="<?php echo $action->url; ?>" target="<?php echo $action->target; ?>"
										   class="ulg-manage-progress-btn">
											<?php echo $action->text; ?>
										</a>
									</div>

								<?php } ?>

								<div class="ulg-manage-progress-course__progress">
									
									<?php $course_progress = $course->progress; ?>
									<?php if ( 'completed' === $course->status && false === $course->has_lessons && false === $course->has_quizzes ): ?>
										<?php // Make it 100% if there are no lessons and quizzes and the status is 'completed'. ?>
										<?php $course_progress = 100; ?>
									<?php endif; ?>

									<div class="ulg-manage-progress-course__progress-sizer">
										<?php printf( __( '%s complete', 'uncanny-learndash-groups' ), '100%' ); ?>
									</div>
									<div class="ulg-manage-progress-course__progress-percentage">
										<?php printf( __( '%s complete', 'uncanny-learndash-groups' ), sprintf( '<span>%s</span>%%', $course_progress ) ); ?>
									</div>
									<div class="ulg-manage-progress-course__progress-holder">
										<div class="ulg-manage-progress-course__progress-bar"
											 style="width: <?php echo $course_progress; ?>%"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ulg-manage-progress-course__content">

						<?php if ( $course->has_lessons ) { ?>
							<div class="ulg-manage-progress-course__lessons">
								<div class="ulg-manage-progress-lessons">

									<?php foreach ( $course->lessons as $lesson ) { ?>

										<?php

										// Set array with the extra classes we're going to
										// add to the lesson div
										$lesson_css_classes = array();

										// Check if we have to expand it on page load
										if ( $expanded_on_load && ( $lesson->has_topics || $lesson->has_quizzes ) ) {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--expanded';
										} else {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--collapsed';
										}

										// Set a class depending if it's completed or not
										if ( $lesson->is_completed ) {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--completed';
										} else {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--not-completed';
										}

										// Set a class depending if it has topics or not
										if ( $lesson->has_topics ) {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--has-topics';
										} else {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--does-not-have-topics';
										}

										// Set a class depending if it has quizzes or not
										if ( $lesson->has_quizzes ) {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--has-quizzes';
										} else {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--does-not-have-quizzes';
										}

										// Set a topic depending if it's available or not
										if ( $lesson->is_available ) {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--available';
										} else {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--not-available';
										}

										// Set a class depending if the user can manage or not the progress
										if ( ! $can_manage_progress ) {
											$lesson_css_classes[] = 'ulg-manage-progress-lesson--can-not-manage-progress';
										}

										?>

										<div class="ulg-manage-progress-lesson <?php echo implode( ' ', $lesson_css_classes ); ?>"
											 data-lesson-id="<?php echo $lesson->id; ?>"
											 data-course-id="<?php echo $course->id; ?>"
											 data-is-completed="<?php echo $lesson->is_completed ? 1 : 0; ?>"
											 data-is-available="<?php echo $lesson->is_available ? 1 : 0; ?>"
											 data-available-on="<?php echo $lesson->available_on; ?>"
											 data-has-topics="<?php echo $lesson->has_topics ? 1 : 0; ?>"
											 data-has-quizzes="<?php echo $lesson->has_quizzes ? 1 : 0; ?>">

											<div class="ulg-manage-progress-lesson__row">
												<div class="ulg-manage-progress-lesson__toggle-btn"></div>
												<div class="ulg-manage-progress-lesson__details">
													<div class="ulg-manage-progress-lesson__left">

														<div class="ulg-manage-progress-lesson__progress-actions">
															<div class="ulg-manage-progress-lesson__progress-action-checkbox">
																<span class="ulg-manage-progress-lesson__progress-action-confirm-text">
																	<?php _e( 'Confirm', 'uncanny-learndash-groups' ); ?>
																</span>
															</div>
															<div class="ulg-manage-progress-lesson__progress-action-cancel">
																<?php _e( 'Cancel', 'uncanny-learndash-groups' ); ?>
															</div>
														</div>

														<div class="ulg-manage-progress-lesson__name">
															<?php echo ! empty( $lesson->title ) ? $lesson->title : __( '(no title)', 'uncanny-learndash-groups' ); ?>

															<?php if ( 0 === 1 && ! $lesson->is_available ) { ?>

																<div class="ulg-manage-progress-lesson__available-on">
																	<div class="ulg-manage-progress-lesson__available-on-text">
																		<?php _e( 'Available on', 'uncanny-learndash-groups' ) ?>
																	</div>
																	<div class="ulg-manage-progress-lesson__available-on-date">
																		<?php echo learndash_adjust_date_time_display( $lesson->available_on ); ?>
																	</div>
																</div>
															<?php } ?>
														</div>
													</div>
													<div class="ulg-manage-progress-lesson__right"></div>
												</div>
											</div>
											<div class="ulg-manage-progress-lesson__content">

												<?php if ( $lesson->has_topics ) { ?>

													<div class="ulg-manage-progress-lesson__topics">
														<div class="ulg-manage-progress-topics">

															<?php foreach ( $lesson->topics as $topic ) { ?>

																<?php

																// Set array with the extra classes we're going to
																// add to the topic div
																$topic_css_classes = array();

																// Set a class depending if it's completed or not
																if ( $topic->is_completed ) {
																	$topic_css_classes[] = 'ulg-manage-progress-topic--completed';
																} else {
																	$topic_css_classes[] = 'ulg-manage-progress-topic--not-completed';
																}

																// Check if we have to expand it on page load
																if ( $expanded_on_load && $topic->has_quizzes ) {
																	$topic_css_classes[] = 'ulg-manage-progress-topic--expanded';
																} else {
																	$topic_css_classes[] = 'ulg-manage-progress-topic--collapsed';
																}

																// Set a class depending if the user can manage or not the progress
																if ( ! $can_manage_progress ) {
																	$topic_css_classes[] = 'ulg-manage-progress-topic--can-not-manage-progress';
																}

																// Add class if it has quizzes
																if ( $topic->has_quizzes ) {
																	$topic_css_classes[] = 'ulg-manage-progress-topic--has-quizzes';
																}

																?>

																<div class="ulg-manage-progress-topic <?php echo implode( ' ', $topic_css_classes ); ?>"
																	 data-topic-id="<?php echo $topic->id; ?>"
																	 data-lesson-id="<?php echo $lesson->id; ?>"
																	 data-course-id="<?php echo $course->id; ?>"
																	 data-is-completed="<?php echo $topic->is_completed ? 1 : 0; ?>"
																	 data-has-quizzes="<?php echo $topic->has_quizzes ? 1 : 0; ?>">
																	<div class="ulg-manage-progress-topic__row">
																		<div class="ulg-manage-progress-topic__toggle-btn"></div>
																		<div class="ulg-manage-progress-topic__details">
																			<div class="ulg-manage-progress-topic__left">
																				<div class="ulg-manage-progress-topic__progress-actions">
																					<div class="ulg-manage-progress-topic__progress-action-checkbox">
																						<span class="ulg-manage-progress-topic__progress-action-confirm-text">
																							<?php _e( 'Confirm', 'uncanny-learndash-groups' ); ?>
																						</span>
																					</div>
																					<div class="ulg-manage-progress-topic__progress-action-cancel">
																						<?php _e( 'Cancel', 'uncanny-learndash-groups' ); ?>
																					</div>
																				</div>

																				<div class="ulg-manage-progress-topic__name">
																					<?php echo ! empty( $topic->title ) ? $topic->title : __( '(no title)', 'uncanny-learndash-groups' ); ?>
																				</div>
																			</div>
																			<div class="ulg-manage-progress-topic__right">
																				<!-- <div class="ulg-manage-progress-topic__status">
																					<div class="ulg-manage-progress-topic__status-circle"></div>
																				</div> -->
																			</div>
																		</div>
																	</div>
																	<div class="ulg-manage-progress-topic__content">
																		<?php if ( $topic->has_quizzes ) { ?>

																			<div class="ulg-manage-progress-topic__quizzes">

																				<?php

																				// Set array with the extra classes we're going to
																				// add to the quizzes container
																				$quizzes_css_classes = array();

																				// Check if we have to expand it on page load
																				if ( $expanded_on_load ) {
																					$quizzes_css_classes[] = 'ulg-manage-progress-quizzes--expanded';
																				} else {
																					$quizzes_css_classes[] = 'ulg-manage-progress-quizzes--collapsed';
																				}

																				?>

																				<div class="ulg-manage-progress-quizzes <?php echo implode( ' ', $quizzes_css_classes ); ?>">
																					<?php /* <div class="ulg-manage-progress-quizzes__header">
																						<div class="ulg-manage-progress-quizzes__header-toggle-btn"></div>
																						<div class="ulg-manage-progress-quizzes__header-title">
																							<?php _e( 'Quizzes', 'uncanny-learndash-groups' ); ?>
																						</div>
																					</div> */ ?>
																					<div class="ulg-manage-progress-quizzes__list">

																						<?php

																						foreach ( $topic->quizzes as $quiz ) {

																							// Set array with the extra classes we're going to
																							// add to the quiz div
																							$quiz_css_classes = array();

																							// Set a class depending if it's completed or not
																							if ( $quiz->is_completed ) {
																								$quiz_css_classes[] = 'ulg-manage-progress-quiz--completed';
																								$quiz_css_classes[] = 'ulg-manage-progress-quiz--passed';
																							} else {
																								$quiz_css_classes[] = 'ulg-manage-progress-quiz--not-completed';
																								$quiz_css_classes[] = 'ulg-manage-progress-quiz--failed';
																							}

																							// Set a class depending if the user can manage or not the progress
																							if ( ! $can_manage_progress ) {
																								$quiz_css_classes[] = 'ulg-manage-progress-quiz--can-not-manage-progress';
																							}

																							?>

																							<div class="ulg-manage-progress-quiz <?php echo implode( ' ', $quiz_css_classes ); ?>"
																								 data-quiz-id="<?php echo $quiz->id; ?>"
																								 data-topic-id="<?php echo $topic->id; ?>"
																								 data-lesson-id="<?php echo $lesson->id; ?>"
																								 data-course-id="<?php echo $course->id; ?>"
																								 data-is-completed="<?php echo $quiz->is_completed ? 1 : 0; ?>"
																								 data-passed="<?php echo $quiz->passed ? 1 : 0; ?>">

																								<div class="ulg-manage-progress-quiz__row">
																									<div class="ulg-manage-progress-quiz__details">
																										<div class="ulg-manage-progress-quiz__left">
																											<div class="ulg-manage-progress-quiz__progress-actions">
																												<div class="ulg-manage-progress-quiz__progress-action-checkbox">
																													<span class="ulg-manage-progress-quiz__progress-action-confirm-text">
																														<?php _e( 'Confirm', 'uncanny-learndash-groups' ); ?>
																													</span>
																												</div>
																												<div class="ulg-manage-progress-quiz__progress-action-cancel">
																													<?php _e( 'Cancel', 'uncanny-learndash-groups' ); ?>
																												</div>
																											</div>

																											<div class="ulg-manage-progress-quiz__name">
																												<?php echo ! empty( $quiz->title ) ? $quiz->title : __( '(no title)', 'uncanny-learndash-groups' ); ?>
																											</div>
																											<div class="ulg-manage-progress-quiz__date">
																												<?php echo learndash_adjust_date_time_display( $quiz->taken_on ); ?>
																											</div>
																										</div>
																										<div class="ulg-manage-progress-quiz__right">
																											<div class="ulg-manage-progress-quiz__action">

																												<?php if ( $quiz->has_certificate ) { ?>

																													<a href="<?php echo $quiz->certificate_url; ?>"
																													   target="_blank"
																													   class="ulg-manage-progress-btn ulg-manage-progress-btn--certificate">
																														<?php _e( 'Certificate', 'uncanny-learndash-groups' ); ?>
																													</a>

																												<?php } ?>

																												<?php if ( $quiz->has_statistics ) { ?>

																													<a class="ulg-manage-progress-btn ulg-manage-progress-btn--statistics user_statistic"
																													   data-statistic_nonce="<?php echo $quiz->statistics_nonce; ?>"
																													   data-user_id="<?php echo $user_id ?>"
																													   data-quiz_id="<?php echo $quiz->pro_quizid; ?>"
																													   data-ref_id="<?php echo intval( $quiz->statistic_ref_id ); ?>"
																													   data-nonce="' . wp_create_nonce( 'wpProQuiz_nonce' ) . '"
																													   href="#">
																														<?php _e( 'Statistics', 'uncanny-learndash-groups' ); ?>
																													</a>

																												<?php } ?>

																											</div>
																											<div class="ulg-manage-progress-quiz__score"></div>
																										</div>
																									</div>
																								</div>

																							</div>

																							<?php
																						} ?>
																					</div>
																				</div>
																			</div>

																		<?php } ?>
																	</div>
																</div>

															<?php } ?>
														</div>
													</div>

												<?php } ?>

												<?php if ( $lesson->has_quizzes ) { ?>

													<div class="ulg-manage-progress-lesson__quizzes">

														<?php

														// Set array with the extra classes we're going to
														// add to the quizzes container
														$quizzes_css_classes = array();

														// Check if we have to expand it on page load
														if ( $expanded_on_load ) {
															$quizzes_css_classes[] = 'ulg-manage-progress-quizzes--expanded';
														} else {
															$quizzes_css_classes[] = 'ulg-manage-progress-quizzes--collapsed';
														}

														?>

														<div class="ulg-manage-progress-quizzes <?php echo implode( ' ', $quizzes_css_classes ); ?>">
															<?php /* <div class="ulg-manage-progress-quizzes__header">
																<div class="ulg-manage-progress-quizzes__header-toggle-btn"></div>
																<div class="ulg-manage-progress-quizzes__header-title">
																	<?php _e( 'Quizzes', 'uncanny-learndash-groups' ); ?>
																</div>
															</div> */ ?>
															<div class="ulg-manage-progress-quizzes__list">

																<?php

																foreach ( $lesson->quizzes as $quiz ) {

																	// Set array with the extra classes we're going to
																	// add to the quiz div
																	$quiz_css_classes = array();

																	// Set a class depending if it's completed or not
																	if ( $quiz->is_completed ) {
																		$quiz_css_classes[] = 'ulg-manage-progress-quiz--completed';
																		$quiz_css_classes[] = 'ulg-manage-progress-quiz--passed';
																	} else {
																		$quiz_css_classes[] = 'ulg-manage-progress-quiz--not-completed';
																		$quiz_css_classes[] = 'ulg-manage-progress-quiz--failed';
																	}

																	// Set a class depending if the user can manage or not the progress
																	if ( ! $can_manage_progress ) {
																		$quiz_css_classes[] = 'ulg-manage-progress-quiz--can-not-manage-progress';
																	}

																	?>

																	<div class="ulg-manage-progress-quiz <?php echo implode( ' ', $quiz_css_classes ); ?>"
																		 data-quiz-id="<?php echo $quiz->id; ?>"
																		 data-lesson-id="<?php echo $lesson->id; ?>"
																		 data-course-id="<?php echo $course->id; ?>"
																		 data-is-completed="<?php echo $quiz->is_completed ? 1 : 0; ?>"
																		 data-passed="<?php echo $quiz->passed ? 1 : 0; ?>">

																		<div class="ulg-manage-progress-quiz__row">
																			<div class="ulg-manage-progress-quiz__details">
																				<div class="ulg-manage-progress-quiz__left">
																					<div class="ulg-manage-progress-quiz__progress-actions">
																						<div class="ulg-manage-progress-quiz__progress-action-checkbox">
																							<span class="ulg-manage-progress-quiz__progress-action-confirm-text">
																								<?php _e( 'Confirm', 'uncanny-learndash-groups' ); ?>
																							</span>
																						</div>
																						<div class="ulg-manage-progress-quiz__progress-action-cancel">
																							<?php _e( 'Cancel', 'uncanny-learndash-groups' ); ?>
																						</div>
																					</div>

																					<div class="ulg-manage-progress-quiz__name">
																						<?php echo ! empty( $quiz->title ) ? $quiz->title : __( '(no title)', 'uncanny-learndash-groups' ); ?>
																					</div>
																					<div class="ulg-manage-progress-quiz__date">
																						<?php echo learndash_adjust_date_time_display( $quiz->taken_on ); ?>
																					</div>
																				</div>
																				<div class="ulg-manage-progress-quiz__right">
																					<div class="ulg-manage-progress-quiz__action">

																						<?php if ( $quiz->has_certificate ) { ?>

																							<a href="<?php echo $quiz->certificate_url; ?>"
																							   target="_blank"
																							   class="ulg-manage-progress-btn ulg-manage-progress-btn--certificate">
																								<?php _e( 'Certificate', 'uncanny-learndash-groups' ); ?>
																							</a>

																						<?php } ?>

																						<?php if ( $quiz->has_statistics ) { ?>

																							<a class="ulg-manage-progress-btn ulg-manage-progress-btn--statistics user_statistic"
																							   data-statistic_nonce="<?php echo $quiz->statistics_nonce; ?>"
																							   data-user_id="<?php echo $user_id ?>"
																							   data-quiz_id="<?php echo $quiz->pro_quizid; ?>"
																							   data-ref_id="<?php echo intval( $quiz->statistic_ref_id ); ?>"
																							   data-nonce="' . wp_create_nonce( 'wpProQuiz_nonce' ) . '"
																							   href="#">
																								<?php _e( 'Statistics', 'uncanny-learndash-groups' ); ?>
																							</a>

																						<?php } ?>

																					</div>
																					<div class="ulg-manage-progress-quiz__score"></div>
																				</div>
																			</div>
																		</div>

																	</div>

																	<?php
																} ?>
															</div>
														</div>
													</div>

												<?php } ?>
											</div>
										</div>

									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php

						if ( $course->has_quizzes ) { ?>

							<div class="ulg-manage-progress-course__quizzes">

								<?php

								// Set array with the extra classes we're going to
								// add to the quizzes container
								$quizzes_css_classes = array();

								// Check if we have to expand it on page load
								if ( $expanded_on_load ) {
									$quizzes_css_classes[] = 'ulg-manage-progress-quizzes--expanded';
								} else {
									$quizzes_css_classes[] = 'ulg-manage-progress-quizzes--collapsed';
								}

								?>

								<div class="ulg-manage-progress-quizzes <?php echo implode( ' ', $quizzes_css_classes ); ?>">
									<?php /* <div class="ulg-manage-progress-quizzes__header">
										<div class="ulg-manage-progress-quizzes__header-toggle-btn"></div>
										<div class="ulg-manage-progress-quizzes__header-title">
											<?php _e( 'Quizzes', 'uncanny-learndash-groups' ); ?>
										</div>
									</div> */ ?>
									<div class="ulg-manage-progress-quizzes__list">

										<?php

										foreach ( $course->quizzes as $quiz ) {

											// Set array with the extra classes we're going to
											// add to the quiz div
											$quiz_css_classes = array();

											// Set a class depending if it's completed or not
											if ( $quiz->is_completed ) {
												$quiz_css_classes[] = 'ulg-manage-progress-quiz--completed';
												$quiz_css_classes[] = 'ulg-manage-progress-quiz--passed';
											} else {
												$quiz_css_classes[] = 'ulg-manage-progress-quiz--not-completed';
												$quiz_css_classes[] = 'ulg-manage-progress-quiz--failed';
											}

											// Set a class depending if the user can manage or not the progress
											if ( ! $can_manage_progress ) {
												$quiz_css_classes[] = 'ulg-manage-progress-quiz--can-not-manage-progress';
											}

											?>

											<div class="ulg-manage-progress-quiz <?php echo implode( ' ', $quiz_css_classes ); ?>"
												 data-quiz-id="<?php echo $quiz->id; ?>"
												 data-course-id="<?php echo $course->id; ?>"
												 data-is-completed="<?php echo $quiz->is_completed ? 1 : 0; ?>"
												 data-passed="<?php echo $quiz->passed ? 1 : 0; ?>">

												<div class="ulg-manage-progress-quiz__row">
													<div class="ulg-manage-progress-quiz__details">
														<div class="ulg-manage-progress-quiz__left">
															<div class="ulg-manage-progress-quiz__progress-actions">
																<div class="ulg-manage-progress-quiz__progress-action-checkbox">
																	<span class="ulg-manage-progress-quiz__progress-action-confirm-text">
																		<?php _e( 'Confirm', 'uncanny-learndash-groups' ); ?>
																	</span>
																</div>
																<div class="ulg-manage-progress-quiz__progress-action-cancel">
																	<?php _e( 'Cancel', 'uncanny-learndash-groups' ); ?>
																</div>
															</div>

															<div class="ulg-manage-progress-quiz__name">
																<?php echo ! empty( $quiz->title ) ? $quiz->title : __( '(no title)', 'uncanny-learndash-groups' ); ?>
															</div>
															<div class="ulg-manage-progress-quiz__date">
																<?php echo learndash_adjust_date_time_display( $quiz->taken_on ); ?>
															</div>
														</div>
														<div class="ulg-manage-progress-quiz__right">
															<div class="ulg-manage-progress-quiz__action">

																<?php if ( $quiz->has_certificate ) { ?>

																	<a href="<?php echo $quiz->certificate_url; ?>"
																	   target="_blank"
																	   class="ulg-manage-progress-btn ulg-manage-progress-btn--certificate">
																		<?php _e( 'Certificate', 'uncanny-learndash-groups' ); ?>
																	</a>

																<?php } ?>

																<?php if ( $quiz->has_statistics ) { ?>

																	<a class="ulg-manage-progress-btn ulg-manage-progress-btn--statistics user_statistic"
																	   data-statistic_nonce="<?php echo $quiz->statistics_nonce; ?>"
																	   data-user_id="<?php echo $user_id ?>"
																	   data-quiz_id="<?php echo $quiz->pro_quizid; ?>"
																	   data-ref_id="<?php echo intval( $quiz->statistic_ref_id ); ?>"
																	   data-nonce="' . wp_create_nonce( 'wpProQuiz_nonce' ) . '"
																	   href="#">
																		<?php _e( 'Statistics', 'uncanny-learndash-groups' ); ?>
																	</a>

																<?php } ?>

															</div>
															<div class="ulg-manage-progress-quiz__score"></div>
														</div>
													</div>
												</div>

											</div>

										<?php } ?>
									</div>
								</div>
							</div>

						<?php } ?>
					</div>
				</div>

			<?php } ?>

		</div>
	</div>
</div>
