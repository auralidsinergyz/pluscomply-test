<?php
/**
 * Template is used to show Quiz Attempt summary.
 *
 * @package Quiz Reporting Extension
 * @since 3.0.0
 */

?>
<div class="quiz-summary-section">
	<div class="left-section">
		<div class="attempt-information">
			<div class="meta">
				<div class="meta-thumb">
					<img src="<?php echo esc_url( LDRP_PLUGIN_URL . 'assets/public/images/Result.svg' ); ?>" />
				</div>
				<div class="meta-info">
					<span class="label">
						<?php
						echo esc_html( sprintf( __( 'Result', 'learndash-reports-pro' ) ) );
						?>
					</span>
					<span><strong style="color: #1AB900;"><?php echo esc_html( $pass ); ?></strong></span>
				</div>
			</div>
			<div class="meta">
				<div class="meta-thumb">
					<img src="<?php echo esc_url( LDRP_PLUGIN_URL . 'assets/public/images/test.svg' ); ?>" />
				</div>
				<div class="meta-info">
					<span class="label">
						<?php
						/* translators: %s: Quiz Label */
						echo esc_html( sprintf( __( '%s Score', 'learndash-reports-pro' ), learndash_get_custom_label( 'quiz' ) ) );
						?>
					</span>
					<?php /* translators: 1: Points Earned Bold, 2: Total Points */ ?>
					<span><?php echo sprintf( __( '%1$s of %2$d', '1: Points Earned Bold, 2: Total Points', 'learndash-reports-pro' ), '<strong>' . $attempt_data['points'] . '</strong>', $attempt_data['gpoints'] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
			</div>
			<div class="meta">
				<div class="meta-thumb">
					<img src="<?php echo esc_url( LDRP_PLUGIN_URL . 'assets/public/images/correct.svg' ); ?>" />
				</div>
				<div class="meta-info">
					<span class="label">
						<?php echo esc_html__( 'Answered Correctly', 'learndash-reports-pro' ); ?>
					</span>
					<?php /* translators: %1$s: Correct Answers Count Bold, %2$d: Total Number of Questions */ ?>
					<span><?php echo sprintf( __( '%1$s of %2$d', 'learndash-reports-pro' ), '<strong>' . $attempt_data['correct_count'] . '</strong>', (int) $attempt_data['incorrect_count'] + (int) $attempt_data['correct_count'] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
			</div>
			<div class="meta">
				<div class="meta-thumb">
					<img src="<?php echo esc_url( LDRP_PLUGIN_URL . 'assets/public/images/clock3.svg' ); ?>" />
				</div>
				<div class="meta-info">
					<span class="label">
						<?php echo esc_html__( 'Time Taken', 'learndash-reports-pro' ); ?>
					</span>
					<span><strong><?php echo esc_html( $time_taken ); ?></strong></span>
				</div>
			</div>
			<div class="meta">
				<div class="meta-thumb">
					<img src="<?php echo esc_url( LDRP_PLUGIN_URL . 'assets/public/images/calendar1.svg' ); ?>" />
				</div>
				<div class="meta-info">
					<span class="label">
						<?php echo esc_html__( 'Date of Attempt', 'learndash-reports-pro' ); ?>
					</span>
					<span><strong><?php echo esc_html( date_i18n( get_option( 'date_format', 'd-M-Y' ), $attempt_data['create_time'] ) ); ?></strong></span>
				</div>
			</div>
		</div>
	</div>
	<div class="right-section">
		<div class="fragment">
			<div class="fragment-info">
				<span>
					<?php /* translators: %s: User's Display Name */ ?>
					<?php echo esc_html( sprintf( __( '%s has reached', 'learndash-reports-pro' ), $display_name ) ); ?>
				</span>
				<?php /* translators: %1$s: Points Earned Bold, %2$d: Total Points, %3$s: Percentage in Bold. */ ?>
				<span class="lighter"><?php echo sprintf( __( '%1$s of %2$d points %3$s', 'learndash-reports-pro' ), '<strong>' . $attempt_data['points'] . '</strong>', $attempt_data['gpoints'], '<strong style="color: #1AB900;">(' . $percentage . '%)</strong>' );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>
		</div>
		<div class="fragment">
			<div class="fragment-info percentage">
				<svg viewBox="-2 -2 40 40" class="circular-chart green">
					<path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
					<path class="circle" stroke-dasharray="<?php echo esc_attr( $percentage ); ?>, 100"	d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
					<text x="18" y="20.35" class="percentage"><?php echo esc_html( ceil( $percentage ) . '%' ); ?></text>
				</svg>
				<span>
					<?php /* translators: %s: User's Display Name */ ?>
					<?php echo esc_html( sprintf( __( '%s\'s score', 'learndash-reports-pro' ), $display_name ) ); ?>
				</span>
			</div>
		</div>
		<div class="fragment">
			<?php
			$user = wp_get_current_user();
			if ( ! in_array( 'group_leader', (array) $user->roles ) ) {// phpcs:ignore
				?>
			<div class="fragment-info percentage">
				<svg viewBox="-2 -2 40 40" class="circular-chart orange">
					<path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
					<path class="circle" stroke-dasharray="<?php echo esc_attr( $class_average ); ?>, 100"	d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
					<text x="18" y="20.35" class="percentage"><?php echo esc_html( ceil( $class_average ) . '%' ); ?></text>
				</svg>
				<span>
					<?php echo esc_html_e( 'Average Score', 'learndash-reports-pro' ); ?>
				</span>
			</div>
				<?php
			} else {
				foreach ( $class_average as $group_id => $avg ) {
					?>
					<div class="fragment-info percentage" style="justify-content: flex-start; text-align: left;">
						<svg viewBox="-2 -2 40 40" class="circular-chart orange">
							<path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
							<path class="circle" stroke-dasharray="<?php echo esc_attr( $avg ); ?>, 100"	d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
							<text x="18" y="20.35" class="percentage"><?php echo esc_html( ceil( $avg ) . '%' ); ?></text>
						</svg>
						<span class="group-average" title="<?php echo get_the_title( $group_id ); ?>">
							<?php echo esc_html__( 'Average Score - ', 'learndash-reports-pro' ) . get_the_title( $group_id ); ?>
						</span>
					</div>
					<?php
				}
			}
			?>
			<style type="text/css">
				.fragment-info span.group-average {
					width: 250px;
					white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;
				}
			</style>
		</div>
	</div>
</div>
