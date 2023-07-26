<?php

use uncanny_learndash_groups\SharedFunctions;

$bulk_discounts           = get_option( SharedFunctions::$bulk_discount_options, array() );
$number_of_bulk_discounts = key_exists( 'discounts', $bulk_discounts ) ? count( $bulk_discounts['discounts'] ) : 0;

if ( key_exists( 'enabled', $bulk_discounts ) && 'yes' === $bulk_discounts['enabled'] && $number_of_bulk_discounts > 0 ) {
	$has_bulk_discounts = $bulk_discounts['enabled'];
} else {
	$has_bulk_discounts = false;
}
if ( $has_bulk_discounts ) {
	?>
	<div class="uo-groups--bulk-discount">
		<div class="uo-groups-box">
			<div class="uo-groups--bulk-discount-title">
				<?php

				if ( $number_of_bulk_discounts == 1 ) {
					_e( 'Bulk discount', 'uncanny-learndash-groups' );
				} else {
					_e( 'Bulk discounts', 'uncanny-learndash-groups' );
				}

				?>
			</div>

			<div class="uo-groups-table">

				<div class="uo-groups-table-content">

					<?php for ( $i = 1; $i < 11; $i ++ ) { ?>
						<?php if ( key_exists( 'discounts', $bulk_discounts ) && key_exists( $i, $bulk_discounts['discounts'] ) ) { ?>
							<?php

							$next = '+';

							if ( $i < 11 ) {
								if ( key_exists( $i + 1, $bulk_discounts['discounts'] ) ) {
									$next = $bulk_discounts['discounts'][ $i + 1 ]['qty'] - 1;
								}
							}

							?>

							<div class="uo-groups-table-row">
								<div class="uo-groups-table-cell">

									<?php

									$text = ! is_numeric( $next ) ? '+' : '';
									$text .= $bulk_discounts['discounts'][ $i ]['qty'];
									$text = is_numeric( $next ) ? $text . ' ' . __( 'to', 'uncanny-learndash-groups' ) . ' ' . $next : $text;
									$text .= ' ' . strtolower( __( get_option( 'ulgm_per_seat_text_plural', 'Seats' ), 'uncanny-learndash-groups' ) );

									echo $text;

									?>

									<input type="hidden" id="bulk-discount-<?php echo $i ?>"
										   name="bulk-discount-<?php echo $i ?>"
										   value="<?php echo $bulk_discounts['discounts'][ $i ]['percent'] ?>"
										   data-qty="<?php echo $bulk_discounts['discounts'][ $i ]['qty']; ?>"/>

								</div>
								<div class="uo-groups-table-cell">
									- <?php echo $bulk_discounts['discounts'][ $i ]['percent'] ?>
									%
								</div>
							</div>

						<?php } ?>
					<?php } ?>

				</div>
			</div>
		</div>
	</div>
<?php } ?>
