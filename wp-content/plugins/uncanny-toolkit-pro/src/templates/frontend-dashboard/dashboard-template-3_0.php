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

<?php if ( $has_custom_colors ){ ?>

<style>

	<?php if ( ! empty( $user_colors->toggle->disabled_bg ) ){ ?>

	/**
	 * Change background color of a disabled toggle button
	 */

	.ultp-dashboard .ultp-dashboard-course__toggle-btn,
	.ultp-dashboard .ultp-dashboard-lesson__toggle-btn {
		background-color: <?php echo $user_colors->toggle->disabled_bg; ?>;
	}

	<?php } ?>

	<?php if ( ! empty( $user_colors->toggle->expanded_bg ) ){ ?>

	/**
	 * Change background color of a expanded toggle button
	 */

	.ultp-dashboard-course--expanding .ultp-dashboard-course__row .ultp-dashboard-course__toggle-btn,
	.ultp-dashboard-course--expanded .ultp-dashboard-course__row .ultp-dashboard-course__toggle-btn,
	.ultp-dashboard-lesson--expanding .ultp-dashboard-lesson__row .ultp-dashboard-lesson__toggle-btn,
	.ultp-dashboard-lesson--expanded .ultp-dashboard-lesson__row .ultp-dashboard-lesson__toggle-btn {
		background-color: <?php echo $user_colors->toggle->expanded_bg; ?>;
	}

	<?php } ?>

	<?php if ( ! empty( $user_colors->toggle->expanded_icon ) ){ ?>

	/**
	 * Change icon color of a expanded toggle button
	 */

	.ultp-dashboard-course--expanding .ultp-dashboard-course__row .ultp-dashboard-course__toggle-btn:before,
	.ultp-dashboard-course--expanded .ultp-dashboard-course__row .ultp-dashboard-course__toggle-btn:before,
	.ultp-dashboard-lesson--expanding .ultp-dashboard-lesson__row .ultp-dashboard-lesson__toggle-btn:before,
	.ultp-dashboard-lesson--expanded .ultp-dashboard-lesson__row .ultp-dashboard-lesson__toggle-btn:before {
		color: <?php echo $user_colors->toggle->expanded_icon; ?>;
	}

	<?php } ?>

	<?php if ( ! empty( $user_colors->progress ) ){ ?>

	/**
	 * Change color used to represent progress
	 */

	.ultp-dashboard .ultp-dashboard-course__row .ultp-dashboard-course__details .ultp-dashboard-course__right .ultp-dashboard-course__progress-bar {
		background-color: <?php echo $user_colors->progress; ?>;
	}

	.ultp-dashboard .ultp-dashboard-topic--completed .ultp-dashboard-topic__row .ultp-dashboard-topic__details .ultp-dashboard-topic__right .ultp-dashboard-topic__status .ultp-dashboard-topic__status-circle,
	.ultp-dashboard .ultp-dashboard-lesson--completed .ultp-dashboard-lesson__row .ultp-dashboard-lesson__details .ultp-dashboard-lesson__right .ultp-dashboard-lesson__status .ultp-dashboard-lesson__status-circle {
		background-color: <?php echo $user_colors->progress; ?>;
		border-color: <?php echo $user_colors->progress; ?>;
	}

	<?php } ?>

	<?php if ( ! empty( $user_colors->third_level_bg ) ){ ?>

	/**
	 * Change background color of third level rows
	 */

	.ultp-dashboard .ultp-dashboard-topic__row,
	.ultp-dashboard .ultp-dashboard-lesson__quizzes .ultp-dashboard-quiz__row {
		background-color: <?php echo $user_colors->third_level_bg; ?>;
	}

	<?php } ?>

	<?php if ( ! empty( $user_colors->quiz->passed_bg ) ){ ?>

	/**
	 * Change background of the quiz score when the user passed it
	 */

	.ultp-dashboard .ultp-dashboard-quiz__row .ultp-dashboard-quiz__details .ultp-dashboard-quiz__right .ultp-dashboard-quiz__score-label {
		background-color: <?php echo $user_colors->quiz->passed_bg; ?>;
		border-color: <?php echo $user_colors->quiz->passed_bg; ?>;
	}

	<?php } ?>

	<?php if ( ! empty( $user_colors->quiz->failed_bg ) ){ ?>

	/**
	 * Change background of the quiz score when the user failed it
	 */

	.ultp-dashboard .ultp-dashboard-quiz--failed .ultp-dashboard-quiz__row .ultp-dashboard-quiz__details .ultp-dashboard-quiz__right .ultp-dashboard-quiz__score-label {
		background-color: <?php echo $user_colors->quiz->failed_bg; ?>;
		border-color: <?php echo $user_colors->quiz->failed_bg; ?>;
	}

	<?php } ?>

</style>

<?php } ?>

<div class="ultp-dashboard">
	<div class="ultp-dashboard-toolbar">
		<div class="ultp-dashboard-filters">

			<form class="ultp-dashboard-filters-form" id="ultp-dashboard-filters-form" method="GET">

				<?php if ( $has_wp_category_dropdown ) { ?>

					<div class="ultp-dashboard-filter">
						<div class="ultp-dashboard-filter__label">
							<?php _e( 'Category', 'uncanny-pro-toolkit' ); ?>
						</div>
						<div class="ultp-dashboard-filter__field">
							<select name="catid">
								<option value="">
									<?php _e( 'All categories', 'uncanny-pro-toolkit' ) ?>
								</option>

								<?php foreach ( $wp_categories as $category ) {
									$posts = get_posts( 'post_type=sfwd-courses&posts_per_page=999&category=' . $category->id );
									$count = count( $posts );
									?>
									<option value="<?php echo $category->id; ?>" <?php echo $category->is_selected ? 'selected' : ''; ?>>
										<?php printf( _x( '%1$s (%2$s)', '%1$s is the category name, %2$s is the number of courses that category has', 'uncanny-pro-toolkit' ), $category->title, $count ); ?>
									</option>
								<?php } ?>
							</select>
						</div>
					</div>

				<?php } ?>

				<?php if ( $has_ld_category_dropdown ) { ?>

					<div class="ultp-dashboard-filter">
						<div class="ultp-dashboard-filter__label">
							<?php _e( 'Category', 'uncanny-pro-toolkit' ); ?>
						</div>
						<div class="ultp-dashboard-filter__field">
							<select name="course_catid">
								<option value="">
									<?php _e( 'All categories', 'uncanny-pro-toolkit' ) ?>
								</option>

								<?php foreach ( $ld_categories as $category ) { ?>
									<option value="<?php echo $category->id; ?>" <?php echo $category->is_selected ? 'selected' : ''; ?>>
										<?php printf( _x( '%1$s (%2$s)', '%1$s is the category name, %2$s is the number of courses that category has', 'uncanny-pro-toolkit' ), $category->title, $category->number_of_courses ); ?>
									</option>
								<?php } ?>
							</select>
						</div>
					</div>

				<?php } ?>

			</form>

		</div>

		<div class="ultp-dashboard-actions">
			<div class="ultp-dashboard-btn ultp-dashboard-btn--expand-all">
				<?php _e( 'Expand all', 'uncanny-pro-toolkit' ); ?>
			</div>
			<div class="ultp-dashboard-btn ultp-dashboard-btn--collapse-all">
				<?php _e( 'Collapse all', 'uncanny-pro-toolkit' ); ?>
			</div>
		</div>
	</div>

	<div class="ultp-dashboard-box">
		<div class="ultp-dashboard-courses">

			<?php foreach ( $courses as $course ) {

				// Set array with the extra classes we're going to
				// add to the course div
				$course_css_classes = [];

				// Check if we have to expand it on page load
				if ( $expanded_on_load && ( $course->has_lessons || $course->has_quizzes ) ) {
					$course_css_classes[] = 'ultp-dashboard-course--expanded';
				} else {
					$course_css_classes[] = 'ultp-dashboard-course--collapsed';
				}

				// Set a class to define the progress status
				// This will be completed || in-progress || not-started
				$course_css_classes[] = sprintf( 'ultp-dashboard-course--%s', $course->status );

				// Set class depending if it has or not lessons
				if ( $course->has_lessons ) {
					$course_css_classes[] = 'ultp-dashboard-course--has-lessons';
				} else {
					$course_css_classes[] = 'ultp-dashboard-course--does-not-have-lessons';
				}

				// Set class depending if it has or not quizzes
				if ( $course->has_quizzes ) {
					$course_css_classes[] = 'ultp-dashboard-course--has-quizzes';
				} else {
					$course_css_classes[] = 'ultp-dashboard-course--does-not-have-quizzes';
				}

				// Set a class depending if it has or not a certificate
				if ( $course->has_certificate ) {
					$course_css_classes[] = 'ultp-dashboard-course--has-certificate';
				}

				?>

				<div class="ultp-dashboard-course <?php echo implode( ' ', $course_css_classes ); ?>"
					 data-course-id="<?php echo $course->id; ?>"
					 data-status="<?php echo $course->status; ?>"
					 data-has-lessons="<?php echo $course->has_lessons ? 1 : 0; ?>"
					 data-has-quizzes="<?php echo $course->has_quizzes ? 1 : 0; ?>"
					 data-has-certificate="<?php echo $course->has_certificate ? 1 : 0; ?>">

					<div class="ultp-dashboard-course__row">
						<div class="ultp-dashboard-course__toggle-btn"></div>
						<div class="ultp-dashboard-course__details">
							<div class="ultp-dashboard-course__left">
								<div class="ultp-dashboard-course__name">
									<a href="<?php echo $course->url; ?>">
										<?php echo $course->title; ?>
									</a>
								</div>
							</div>
							<div class="ultp-dashboard-course__right">
								<?php

								$action = (object) [
									'text'   => '',
									'url'    => '',
									'target' => '_self',
								];

								// Define what button we will show
								// Check if the status is "not started"
								if ( $course->status == 'not-started' ) {
									// Then show "Start" button
									$action->text = __( 'Start', 'uncanny-pro-toolkit' );
									$action->url  = $course->url;
								} elseif ( $course->status == 'in-progress' ) {
									if ( $course->has_resume_url ) {
										// "Resume" button
										$action->text = __( 'Resume', 'uncanny-pro-toolkit' );
										$action->url  = $course->resume_url;
									}
								} elseif ( $course->status == 'completed' ) {
									if ( $course->has_certificate ) {
										// "Certificate" button
										$action->text   = __( 'Certificate', 'uncanny-pro-toolkit' );
										$action->url    = $course->certificate_url;
										$action->target = '_blank';
									}
								}

								?>

								<?php if ( ! empty( $action->text ) ) { ?>

									<div class="ultp-dashboard-course__action">
										<a href="<?php echo $action->url; ?>" target="<?php echo $action->target; ?>"
										   class="ultp-dashboard-btn">
											<?php echo $action->text; ?>
										</a>
									</div>

								<?php } ?>

								<div class="ultp-dashboard-course__progress">
									<div class="ultp-dashboard-course__progress-sizer">
										<?php printf( __( '%s complete', 'uncanny-pro-toolkit' ), '100%' ); ?>
									</div>
									<div class="ultp-dashboard-course__progress-percentage">
										<?php printf( __( '%s complete', 'uncanny-pro-toolkit' ), sprintf( '%s%%', $course->progress ) ); ?>
									</div>
									<div class="ultp-dashboard-course__progress-holder">
										<div class="ultp-dashboard-course__progress-bar"
											 style="width: <?php echo $course->progress; ?>%"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ultp-dashboard-course__content">

						<?php if ( $course->has_lessons ) { ?>
							<div class="ultp-dashboard-course__lessons">
								<div class="ultp-dashboard-lessons">

									<?php foreach ( $course->lessons as $lesson ) { ?>

										<?php

										// Set array with the extra classes we're going to
										// add to the lesson div
										$lesson_css_classes = [];

										// Check if we have to expand it on page load
										if ( $expanded_on_load && ( $lesson->has_topics || $lesson->has_quizzes ) ) {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--expanded';
										} else {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--collapsed';
										}

										// Set a class depending if it's completed or not
										if ( $lesson->is_completed ) {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--completed';
										} else {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--not-completed';
										}

										// Set a class depending if it has topics or not
										if ( $lesson->has_topics ) {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--has-topics';
										} else {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--does-not-have-topics';
										}

										// Set a class depending if it has quizzes or not
										if ( $lesson->has_quizzes ) {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--has-quizzes';
										} else {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--does-not-have-quizzes';
										}

										// Set a topic depending if it's available or not
										if ( $lesson->is_available ) {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--available';
										} else {
											$lesson_css_classes[] = 'ultp-dashboard-lesson--not-available';
										}

										?>

										<div class="ultp-dashboard-lesson <?php echo implode( ' ', $lesson_css_classes ); ?>"
											 data-lesson-id="<?php echo $lesson->id; ?>"
											 data-course-id="<?php echo $course->id; ?>"
											 data-is-completed="<?php echo $lesson->is_completed ? 1 : 0; ?>"
											 data-is-available="<?php echo $lesson->is_available ? 1 : 0; ?>"
											 data-available-on="<?php echo $lesson->available_on; ?>"
											 data-has-topics="<?php echo $lesson->has_topics ? 1 : 0; ?>"
											 data-has-quizzes="<?php echo $lesson->has_quizzes ? 1 : 0; ?>">

											<div class="ultp-dashboard-lesson__row">
												<div class="ultp-dashboard-lesson__toggle-btn"></div>
												<div class="ultp-dashboard-lesson__details">
													<div class="ultp-dashboard-lesson__left">
														<div class="ultp-dashboard-lesson__name">
															<a href="<?php echo $lesson->url; ?>">
																<?php echo $lesson->title; ?>
															</a>

															<?php if ( ! $lesson->is_available ) { ?>

																<div class="ultp-dashboard-lesson__available-on">
																	<div class="ultp-dashboard-lesson__available-on-text">
																		<?php _e( 'Available on', 'uncanny-pro-toolkit' ) ?>
																	</div>
																	<div class="ultp-dashboard-lesson__available-on-date">
																		<?php echo learndash_adjust_date_time_display( $lesson->available_on ); ?>
																	</div>
																</div>
															<?php } ?>
														</div>
													</div>
													<div class="ultp-dashboard-lesson__right">

														<?php if ( ! $lesson->is_available ) { ?>

															<div class="ultp-dashboard-lesson__available-on">
																<div class="ultp-dashboard-lesson__available-on-text">
																	<?php _e( 'Available on', 'uncanny-pro-toolkit' ) ?>
																</div>
																<div class="ultp-dashboard-lesson__available-on-date">
																	<?php echo learndash_adjust_date_time_display( $lesson->available_on ); ?>
																</div>
															</div>
														<?php } ?>

														<div class="ultp-dashboard-lesson__status">
															<div class="ultp-dashboard-lesson__status-circle"></div>
														</div>
													</div>
												</div>
											</div>
											<div class="ultp-dashboard-lesson__content">

												<?php if ( $lesson->has_topics ) { ?>

													<div class="ultp-dashboard-lesson__topics">
														<div class="ultp-dashboard-topics">

															<?php foreach ( $lesson->topics as $topic ) { ?>

																<?php

																// Set array with the extra classes we're going to
																// add to the topic div
																$topic_css_classes = [];

																// Set a class depending if it's completed or not
																if ( $topic->is_completed ) {
																	$topic_css_classes[] = 'ultp-dashboard-topic--completed';
																} else {
																	$topic_css_classes[] = 'ultp-dashboard-topic--not-completed';
																}

																?>

																<div class="ultp-dashboard-topic <?php echo implode( ' ', $topic_css_classes ); ?>"
																	 data-topic-id="<?php echo $topic->id; ?>"
																	 data-lesson-id="<?php echo $lesson->id; ?>"
																	 data-course-id="<?php echo $course->id; ?>"
																	 data-is-completed="<?php echo $topic->is_completed ? 1 : 0; ?>">
																	<div class="ultp-dashboard-topic__row">
																		<div class="ultp-dashboard-topic__details">
																			<div class="ultp-dashboard-topic__left">
																				<div class="ultp-dashboard-topic__name">
																					<a href="<?php echo $topic->url; ?>">
																						<?php echo $topic->title; ?>
																					</a>
																				</div>
																			</div>
																			<div class="ultp-dashboard-topic__right">
																				<div class="ultp-dashboard-topic__status">
																					<div class="ultp-dashboard-topic__status-circle"></div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>

															<?php } ?>
														</div>
													</div>

												<?php } ?>

												<?php if ( $lesson->has_quizzes ) { ?>

													<div class="ultp-dashboard-lesson__quizzes">

														<?php

														// Set array with the extra classes we're going to
														// add to the quizzes container
														$quizzes_css_classes = [];

														// Check if we have to expand it on page load
														if ( $expanded_on_load ) {
															$quizzes_css_classes[] = 'ultp-dashboard-quizzes--expanded';
														} else {
															$quizzes_css_classes[] = 'ultp-dashboard-quizzes--collapsed';
														}

														?>

														<div class="ultp-dashboard-quizzes <?php echo implode( ' ', $quizzes_css_classes ); ?>">
															<div class="ultp-dashboard-quizzes__header">
																<div class="ultp-dashboard-quizzes__header-toggle-btn"></div>
																<div class="ultp-dashboard-quizzes__header-title">
																	<?php _e( 'Quizzes Results', 'uncanny-pro-toolkit' ); ?>
																</div>
															</div>
															<div class="ultp-dashboard-quizzes__list">

																<?php

																foreach ( $lesson->quizzes as $quiz ) {

																	// Set array with the extra classes we're going to
																	// add to the quiz div
																	$quiz_css_classes = [];

																	// Set a class depending if it's completed or not
																	if ( $quiz->is_completed ) {
																		$quiz_css_classes[] = 'ultp-dashboard-quiz--completed';
																	} else {
																		$quiz_css_classes[] = 'ultp-dashboard-quiz--not-completed';
																	}

																	// Set a class depending if the user passed or not
																	if ( $quiz->passed ) {
																		$quiz_css_classes[] = 'ultp-dashboard-quiz--passed';
																	} else {
																		$quiz_css_classes[] = 'ultp-dashboard-quiz--failed';
																	}

																	?>

																	<div class="ultp-dashboard-quiz <?php echo implode( ' ', $quiz_css_classes ); ?>"
																		 data-quiz-id="<?php echo $quiz->id; ?>"
																		 data-lesson-id="<?php echo $lesson->id; ?>"
																		 data-course-id="<?php echo $course->id; ?>"
																		 data-is-completed="<?php echo $quiz->is_completed ? 1 : 0; ?>"
																		 data-passed="<?php echo $quiz->passed ? 1 : 0; ?>">

																		<div class="ultp-dashboard-quiz__row">
																			<div class="ultp-dashboard-quiz__details">
																				<div class="ultp-dashboard-quiz__left">
																					<div class="ultp-dashboard-quiz__name">
																						<a href="<?php echo $quiz->url; ?>">
																							<?php echo $quiz->title; ?>
																						</a>
																					</div>
																					<div class="ultp-dashboard-quiz__date">
																						<?php echo learndash_adjust_date_time_display( $quiz->taken_on ); ?>
																					</div>
																				</div>
																				<div class="ultp-dashboard-quiz__right">
																					<div class="ultp-dashboard-quiz__action">

																						<?php if ( $quiz->has_certificate ) { ?>

																							<a href="<?php echo $quiz->certificate_url; ?>"
																							   target="_blank"
																							   class="ultp-dashboard-btn ultp-dashboard-btn--certificate">
																								<?php _e( 'Certificate', 'uncanny-pro-toolkit' ); ?>
																							</a>

																						<?php } ?>

																						<?php if ( $quiz->has_statistics ) { ?>

																							<a class="ultp-dashboard-btn ultp-dashboard-btn--statistics user_statistic"
																							   data-statistic_nonce="<?php echo $quiz->statistics_nonce; ?>"
																							   data-user_id="<?php echo $user_id ?>"
																							   data-quiz_id="<?php echo $quiz->pro_quizid; ?>"
																							   data-ref_id="<?php echo intval( $quiz->statistic_ref_id ); ?>"
																							   href="#">
																								<?php _e( 'Statistics', 'uncanny-pro-toolkit' ); ?>
																							</a>

																						<?php } ?>

																					</div>
																					<div class="ultp-dashboard-quiz__score">
																						<div class="ultp-dashboard-quiz__score-label">
																							<?php

																							if ( $quiz->passed ) {
																								printf( __( '%s Passed', 'uncanny-pro-toolkit' ), sprintf( '%s%%', $quiz->score ) );
																							} else {
																								printf( __( '%s Failed', 'uncanny-pro-toolkit' ), sprintf( '%s%%', $quiz->score ) );
																							}

																							?>
																						</div>
																					</div>
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

							<div class="ultp-dashboard-course__quizzes">

								<?php

								// Set array with the extra classes we're going to
								// add to the quizzes container
								$quizzes_css_classes = [];

								// Check if we have to expand it on page load
								if ( $expanded_on_load ) {
									$quizzes_css_classes[] = 'ultp-dashboard-quizzes--expanded';
								} else {
									$quizzes_css_classes[] = 'ultp-dashboard-quizzes--collapsed';
								}

								?>

								<div class="ultp-dashboard-quizzes <?php echo implode( ' ', $quizzes_css_classes ); ?>">
									<div class="ultp-dashboard-quizzes__header">
										<div class="ultp-dashboard-quizzes__header-toggle-btn"></div>
										<div class="ultp-dashboard-quizzes__header-title">
											<?php _e( 'Quizzes Results', 'uncanny-pro-toolkit' ); ?>
										</div>
									</div>
									<div class="ultp-dashboard-quizzes__list">

										<?php

										foreach ( $course->quizzes as $quiz ) {

											// Set array with the extra classes we're going to
											// add to the quiz div
											$quiz_css_classes = [];

											// Set a class depending if it's completed or not
											if ( $quiz->is_completed ) {
												$quiz_css_classes[] = 'ultp-dashboard-quiz--completed';
											} else {
												$quiz_css_classes[] = 'ultp-dashboard-quiz--not-completed';
											}

											// Set a class depending if the user passed or not
											if ( $quiz->passed ) {
												$quiz_css_classes[] = 'ultp-dashboard-quiz--passed';
											} else {
												$quiz_css_classes[] = 'ultp-dashboard-quiz--failed';
											}

											?>

											<div class="ultp-dashboard-quiz <?php echo implode( ' ', $quiz_css_classes ); ?>"
												 data-quiz-id="<?php echo $quiz->id; ?>"
												 data-course-id="<?php echo $course->id; ?>"
												 data-is-completed="<?php echo $quiz->is_completed ? 1 : 0; ?>"
												 data-passed="<?php echo $quiz->passed ? 1 : 0; ?>">

												<div class="ultp-dashboard-quiz__row">
													<div class="ultp-dashboard-quiz__details">
														<div class="ultp-dashboard-quiz__left">
															<div class="ultp-dashboard-quiz__name">
																<a href="<?php echo $quiz->url; ?>">
																	<?php echo $quiz->title; ?>
																</a>
															</div>
															<div class="ultp-dashboard-quiz__date">
																<?php echo learndash_adjust_date_time_display( $quiz->taken_on ); ?>
															</div>
														</div>
														<div class="ultp-dashboard-quiz__right">
															<div class="ultp-dashboard-quiz__action">

																<?php if ( $quiz->has_certificate ) { ?>

																	<a href="<?php echo $quiz->certificate_url; ?>"
																	   target="_blank"
																	   class="ultp-dashboard-btn ultp-dashboard-btn--certificate">
																		<?php _e( 'Certificate', 'uncanny-pro-toolkit' ); ?>
																	</a>

																<?php } ?>

																<?php if ( $quiz->has_statistics ) { ?>

																	<a class="ultp-dashboard-btn ultp-dashboard-btn--statistics user_statistic"
																	   data-statistic_nonce="<?php echo $quiz->statistics_nonce; ?>"
																	   data-user_id="<?php echo $user_id ?>"
																	   data-quiz_id="<?php echo $quiz->pro_quizid; ?>"
																	   data-ref_id="<?php echo intval( $quiz->statistic_ref_id ); ?>"
																	   href="#">
																		<?php _e( 'Statistics', 'uncanny-pro-toolkit' ); ?>
																	</a>

																<?php } ?>

															</div>
															<div class="ultp-dashboard-quiz__score">
																<div class="ultp-dashboard-quiz__score-label">
																	<?php

																	if ( $quiz->passed ) {
																		printf( __( '%s Passed', 'uncanny-pro-toolkit' ), sprintf( '%s%%', $quiz->score ) );
																	} else {
																		printf( __( '%s Failed', 'uncanny-pro-toolkit' ), sprintf( '%s%%', $quiz->score ) );
																	}

																	?>
																</div>
															</div>
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