<?php
/**
 * Template is used to show quiz table on the frontend shortcode..
 *
 * @package Quiz Reporting Extension
 * @since 3.0.0
 *
 * =====================================================================================
 * Variables available.
 *
 * $score        : Quiz Score.
 * $count        : Question Count.
 * $time         : Time taken.
 * $points       : Points Earned.
 * $total_points : Total Points.
 * $per          : Percentage.
 * $avg_per      : Average Percentage.(???)
 * =====================================================================================
 */

?>
<div class="wpProQuiz_results">
	<h4 class="wpProQuiz_header">
		<?php esc_html_e( 'Results', 'learndash-reports-pro' ); ?>
	</h4>
	<p style="padding:1%;">
		<?php
		/* translators: 1: Score 2: Question Count. */
		printf( __( '%1$s of %2$s questions answered correctly', 'learndash-reports-pro' ), '<span class="wpProQuiz_correct_answer">' . $score . '</span>', '<span>' . $count . '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</p>
	<p class="wpProQuiz_quiz_time">
		<?php
		_e( 'Your time: <span></span>', 'learndash-reports-pro' ); // WPCS: XSS ok.
		/* translators: 1: Hours 2: Minutes 3: Seconds. */
		echo esc_html( sprintf( '%02d:%02d:%02d', ( $time / 3600 ), ( $time / 60 % 60 ), ( $time % 60 ) ) );
		?>
	</p>
	<p class="wpProQuiz_points">
		<?php
		/* translators: 1: Points 2: Total Points 3: Percentage. */
		printf( __( 'You have reached %1$s of %2$s points, (%3$s)', 'learndash-reports-pro' ), '<span>' . $points . '</span>', '<span>' . $total_points . '</span>', '<span>' . $per . '%</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</p>
	<div class="wpProQuiz_resultTable">
		<table>
			<tbody>
				<tr>
					<td class="wpProQuiz_resultName">
						<?php esc_html_e( 'Average score', 'learndash-reports-pro' ); ?>
					</td>
					<td class="wpProQuiz_resultValue wpProQuiz_resultValue_AvgScore">
						<div style="background-color: #6CA54C;width:<?php echo esc_attr( $avg_per ) . '%'; ?>;">
							&nbsp;
						</div>
						<span>
							<?php echo esc_html( $avg_per ) . '%'; ?>
						</span>
					</td>
				</tr>
				<tr>
					<td class="wpProQuiz_resultName">
						<?php esc_html_e( 'Your score', 'learndash-reports-pro' ); ?>
					</td>
					<td class="wpProQuiz_resultValue wpProQuiz_resultValue_YourScore">
						<div style="width:<?php	echo esc_attr( $per ) . '%'; ?>;">
							&nbsp;
						</div>
						<span>
							<?php echo esc_html( $per ) . '%'; ?>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php
