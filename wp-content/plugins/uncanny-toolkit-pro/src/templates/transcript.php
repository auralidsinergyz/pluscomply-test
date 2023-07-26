<div class="uo-ultp-transcript-container">
	<style>

		<?php

		// Get RGB
		list( $r, $g, $b ) = sscanf( $accent_color, '#%02x%02x%02x' );

		?>

		.uo-ultp-transcript-table--zebra .uo-ultp-transcript-table__row:not(.uo-ultp-transcript-table__row--head):not(.uo-ultp-transcript-table__row--footer):nth-child(even) {
			background: rgba( <?php echo $r; ?>, <?php echo $g; ?>, <?php echo $b; ?>, .15);
		}

		.uo-ultp-transcript__print-btn {
			color: <?php echo $accent_color; ?>;
			border: 1px solid<?php echo $accent_color; ?>;
		}

		.uo-ultp-transcript__print-btn:hover {
			color: #fff;
			background: <?php echo $accent_color; ?>;
		}

		.uo-ultp-transcript__print-btn:active,
		.uo-ultp-transcript__print-btn:focus {
			box-shadow: 0 0 0 0.2rem rgba( <?php echo $r; ?>, <?php echo $g; ?>, <?php echo $b; ?>, .5);
		}

	</style>

	<div class="uo-ultp-transcript">
		<div class="uo-ultp-transcript__print">
			<div class="uo-ultp-transcript__print-btn" id="uo-ultp-transcript__print-trigger">
				<?php _e( 'Print', 'uncanny-pro-toolkit' ); ?>
			</div>
		</div>
		<div class="uo-ultp-transcript__document" id="uo-ultp-transcript__document">
			<div class="uo-ultp-transcript-document__content">
				<div class="uo-ultp-transcript-document__header">
					<div class="uo-ultp-transcript-document__logo">
						<img src="<?php echo $transcript->logo->header; ?>" alt="">
					</div>
					<div class="uo-ultp-transcript-document__heading">
						<div class="uo-ultp-transcript-document__title">
							<?php echo $transcript->heading; ?>
						</div>
						<div class="uo-ultp-transcript-document__subtitle">
							<?php echo $transcript->creation_date; ?>
						</div>
					</div>
				</div>

				<div class="uo-ultp-transcript-document__summary uo-ultp-transcript-table">
					<div class="uo-ultp-transcript-table__row">
						<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--title uo-ultp-transcript-table__cell--nobreak">
							<?php _e( 'Student Name', 'uncanny-pro-toolkit' ); ?>
						</div>
						<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--content uo-ultp-transcript-table__cell--big-as-possible">
							<?php echo $transcript->summary->learner_name; ?>
						</div>
					</div>

					<?php if ( ! empty( $transcript->summary->centre_name ) ) { ?>

						<div class="uo-ultp-transcript-table__row">
							<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--title uo-ultp-transcript-table__cell--nobreak">
								<?php _e( 'Organization', 'uncanny-pro-toolkit' ); ?>
							</div>
							<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--content uo-ultp-transcript-table__cell--big-as-possible">
								<?php echo $transcript->summary->centre_name; ?>
							</div>
						</div>

					<?php } ?>

					<div class="uo-ultp-transcript-table__row">
						<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--title uo-ultp-transcript-table__cell--nobreak">
							<?php _e( 'Status', 'uncanny-pro-toolkit' ); ?>
						</div>
						<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--content uo-ultp-transcript-table__cell--big-as-possible">
							<?php echo $transcript->summary->status; ?>
						</div>
					</div>
				</div>

				<div class="uo-ultp-transcript-document__certificates uo-ultp-transcript-table uo-ultp-transcript-table--zebra">

					<!-- Header -->
					<div class="uo-ultp-transcript-table__row uo-ultp-transcript-table__row--head">

						<?php foreach ( $transcript->table->heading as $key => $heading ) { ?>

							<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--nobreak uo-ultp-transcript-table__cell--<?php echo $key; ?>">
								<?php echo $heading; ?>
							</div>

						<?php } ?>
					</div>

					<!-- Normal row -->
					<?php if ( isset( $transcript->table->rows ) && is_object( $transcript->table->heading ) ){ ?>

					<?php foreach ( $transcript->table->rows as $course_id => $row ){ ?>

					<div data-json="<?php echo json_encode( $row ); ?>"
						 class="uo-ultp-transcript-table__row uo-ultp-transcript-table__row--normal-row">

						<?php foreach ( $row

						as $key => $column ){
						if ( in_array( $key, array( 'course_date', 'course_order' ) ) ) {
							continue;
						}
						?>

						<div class="uo-ultp-transcript-table__cell" uo-ultp-transcript-table__cell--<?php echo $key; ?>"
						data-column="<?php echo $transcript->table->heading->$key; ?>">
						<?php echo $column; ?>
					</div>

				<?php } ?>

				</div>

				<?php } ?>

				<?php } ?>

				<!-- Footer -->

				<div class="uo-ultp-transcript-table__row uo-ultp-transcript-table__row--footer">
					<?php

					$left_empty_rows = count( (array) $transcript->table->heading ) - 2;

					$show_avg_quiz_column  = self::get_settings_value( 'uncanny-disable-transcript-avgquizscore-col', $class ) !== 'on';
					$show_final_quiz_score = self::get_settings_value( 'uncanny-disable-transcript-finalquizscore-col', $class ) !== 'on';

					foreach ( $transcript->table->heading as $key => $heading ) {

						if ( $show_avg_quiz_column && 'avg_score' === $key ) {

							$avg_quiz_score = ( '%' === $transcript->avg_quiz_score ) ? __( '0%', 'uncanny-pro-toolkit' ) : $transcript->avg_quiz_score;

							?>
							<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--nobreak uo-ultp-transcript-table__cell--border"
								 data-column="<?php _e( 'Total Avg. Score', 'uncanny-pro-toolkit' ); ?>">
								<?php echo $avg_quiz_score; ?>
							</div>
							<?php

						} elseif ( $show_final_quiz_score && 'final_score' === $key ) {

							$final_quiz_score = ( '%' === $transcript->final_quiz_score ) ? __( '0%', 'uncanny-pro-toolkit' ) : $transcript->final_quiz_score;

							?>
							<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--nobreak uo-ultp-transcript-table__cell--border"
								 data-column="<?php _e( 'Total Final Score', 'uncanny-pro-toolkit' ); ?>">
								<?php echo $final_quiz_score; ?>
							</div>
							<?php

						} else {

							$custom_cumulative_column = apply_filters( 'uo_pro_transcript_cumulative_column', [], $key, $transcript );

							if (
								! empty( $custom_cumulative_column )
								&& isset( $custom_cumulative_column[ $key ] )
								&& isset( $custom_cumulative_column[ $key ]['title'] )
								&& isset( $custom_cumulative_column[ $key ]['value'] )
							) {
								?>
								<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--nobreak uo-ultp-transcript-table__cell--border"
									 data-column="<?php echo $custom_cumulative_column[ $key ]['title']; ?>">
									<?php echo $custom_cumulative_column[ $key ]['value']; ?>
								</div>
								<?php

							} else {
								?>
								<div class="uo-ultp-transcript-table__cell uo-ultp-transcript-table__cell--empty"></div>
								<?php
							}


						}
					}

					?>
				</div>

			</div>

			<div class="uo-ultp-transcript-document__footer">
				<div class="uo-ultp-transcript-document__footer-logo">
					<img src="<?php echo $transcript->logo->footer; ?>" alt="">
				</div>
				<div class="uo-ultp-transcript-document__dismiss">
					<?php echo $transcript->footnote; ?>
				</div>
			</div>

		</div>
	</div>
</div>
</div>