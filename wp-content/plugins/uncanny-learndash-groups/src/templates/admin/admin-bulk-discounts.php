<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">
	<div class="ulgm">

		<?php

		// Add admin header and tabs
		$tab_active = 'uncanny-learndash-groups-bulk-discount';
		include Utilities::get_template( 'admin/admin-header.php' );

		?>

		<div class="ulgm-admin-content">
			<form name="" method="post" action="<?php echo admin_url( 'admin.php?page=uncanny-learndash-groups-bulk-discount' ) ?>">
				<input type="hidden" value="<?php echo admin_url( 'admin.php?page=uncanny-learndash-groups-bulk-discount' ) ?>" name="redirect_to"/>
				<?php
				wp_nonce_field( Utilities::get_plugin_name(), '_ulgm_bulk_nonce' );
				$bulk_discount = get_option( SharedFunctions::$bulk_discount_options, array() );
				if ( ! isset( $bulk_discount['enabled'] ) ) {
					$bulk_discount['enabled'] = 'no';
				}
				if ( ! isset( $bulk_discount['discounts'] ) ) {
					$bulk_discount['discounts'] = array();
				}
				?>
				<table class="form-table group-management-form">
					<tr valign="top" class="options-header-container">
						<th scope="row" colspan="2">
							<h2><?php echo __( 'Bulk discounts', 'uncanny-learndash-groups' ); ?></h2>
						</th>
					</tr>
					<tr valign="top" class="option-setting-container">
						<th class="row"><h4><?php echo __( 'Enable bulk discount', 'uncanny-learndash-groups' ); ?></h4></th>
						<td>
							<input name="ulgm-enable-bulk-discount" id="ulgm-enable-bulk-discount" <?php if ( ! empty( $bulk_discount ) && 'yes' === $bulk_discount['enabled'] ) { ?>checked="checked"<?php } ?> type="checkbox"/>
						</td>
					</tr>
					<tr>
						<th class="row"><h4><?php echo __( 'Bulk discount(s)', 'uncanny-learndash-groups' ); ?></h4></th>
						<td>
							<div id="bulkdiscount_product_data" class="panel woocommerce_options_panel">

								<div class="bulk-qty-per-holder">
									<div class="header">
										<div class="lhs">
											<span><?php echo __( 'Minimum qty', 'uncanny-learndash-groups' ); ?></span>
										</div>
										<div class="rhs">
											<span><?php echo __( 'Discount', 'uncanny-learndash-groups' ); ?></span>
										</div>
									</div>
								</div>
								<?php
								for ( $i = 1; $i <= 10; $i ++ ) :
									//if ( ! empty( $bulk_discount ) && key_exists( $i, $bulk_discount['discounts'] ) ) {
									?>

									<div class="options_group<?php echo $i; ?>">
										<a id="add_discount_line<?php echo $i; ?>" class="button-secondary"
										   href="#block<?php echo $i; ?>"><?php echo __( 'Add discount line', 'uncanny-learndash-groups' ); ?></a>
										<a id="delete_discount_line<?php echo $i; ?>" class="button-secondary"
										   href="#block<?php echo $i; ?>"><?php echo __( 'Remove last discount line', 'uncanny-learndash-groups' ); ?></a>

										<div class="block<?php echo $i; ?> <?php echo ( $i % 2 === 0 ) ? 'even' : 'odd' ?>">
											<div class="lhs">
												<input type="number" min="1" step="1" id="_ulgm_bulk_discount_quantity_<?php echo $i; ?>" name="ulgm_bulk_discount_quantity_<?php echo $i; ?>" value="<?php if ( ! empty( $bulk_discount ) && key_exists( $i, $bulk_discount['discounts'] ) ) {
													echo $bulk_discount['discounts'][ $i ]['qty'];
												} ?>"/>
											</div>
											<div class="rhs">
												<input type="number" min="0" max="100" step="any" id="_ulgm_bulk_discount_value_<?php echo $i; ?>" name="_ulgm_bulk_discount_value_<?php echo $i; ?>" value="<?php if ( ! empty( $bulk_discount ) && key_exists( $i, $bulk_discount['discounts'] ) ) {
													echo $bulk_discount['discounts'][ $i ]['percent'];
												} ?>"/>
												<p class="per"><?php echo __( '%', 'uncanny-learndash-groups' ); ?></p>
											</div>
										</div>
									</div>

								<?php
									//}
								endfor;
								?>

								<div class="options_group11">
									<a id="delete_discount_line11" class="button-secondary"
									   href="#block11"><?php echo __( 'Remove last discount line', 'uncanny-learndash-groups' ); ?></a>
								</div>

								<br/>

							</div>
						</td>
					</tr>

					<tr>
						<th>

							<input type="hidden" id="action" name="action" value="save-bulk-discount"/>
							<?php submit_button( __( 'Save bulk discount', 'uncanny-learndash-groups' ) ); ?>
						</th>
					</tr>
				</table>
			</form>

			<script type="text/javascript">
				jQuery(document).ready(function () {
					var e = jQuery('#bulkdiscount_product_data');
					<?php
					$thepostid = 0;
					for($i = 1; $i <= 11; $i ++) :
					?>
					e.find('.block<?php echo $i; ?>').hide();
					e.find('.options_group<?php echo max( $i, 2 ); ?>').hide();
					e.find('#add_discount_line<?php echo max( $i, 2 ); ?>').hide();
					e.find('#add_discount_line<?php echo $i; ?>').click(function () {
						if ( <?php echo $i; ?> == 1 || (e.find('#_ulgm_bulk_discount_quantity_<?php echo max( $i - 1, 1 ); ?>').val() != '' && e.find('#_ulgm_bulk_discount_value_<?php echo max( $i - 1, 1 ); ?>').val() != '')
					)
						{
							e.find('.block<?php echo $i; ?>').fadeIn();
							e.find('.options_group<?php echo min( $i + 1, 6 ); ?>').fadeIn();
							e.find('#add_discount_line<?php echo min( $i + 1, 5 ); ?>').fadeIn();
							e.find('#add_discount_line<?php echo $i; ?>').fadeOut();
							e.find('#delete_discount_line<?php echo min( $i + 1, 6 ); ?>').fadeIn();
							e.find('#delete_discount_line<?php echo $i; ?>').fadeOut();
						}
					else
						{
							alert('<?php echo __( 'Please fill in the current line before adding new line.', 'uncanny-learndash-groups' ); ?>');
						}
					});
					e.find('#delete_discount_line<?php echo max( $i, 1 ); ?>').hide();
					e.find('#delete_discount_line<?php echo $i; ?>').click(function () {
						e.find('.block<?php echo max( $i - 1, 1 ); ?>').fadeOut();
						e.find('.options_group<?php echo min( $i, 11 ); ?>').fadeOut();
						e.find('#add_discount_line<?php echo min( $i, 5 ); ?>').fadeOut();
						e.find('#add_discount_line<?php echo max( $i - 1, 1 ); ?>').fadeIn();
						e.find('#delete_discount_line<?php echo min( $i, 11 ); ?>').fadeOut();
						e.find('#delete_discount_line<?php echo max( $i - 1, 2 ); ?>').fadeIn();
						e.find('#_ulgm_bulk_discount_quantity_<?php echo max( $i - 1, 1 ); ?>').val('');
						e.find('#_ulgm_bulk_discount_value_<?php echo max( $i - 1, 1 ); ?>').val('');
					});
					<?php
					endfor;
					for ($i = 1, $j = 2; $i <= 10; $i ++, $j ++) {
					$cnt = 1;
					if ( ! empty( $bulk_discount )) {
					?>
					e.find('.block<?php echo $i; ?>').show();
					e.find('.options_group<?php echo $i; ?>').show();
					e.find('#add_discount_line<?php echo $i; ?>').hide();
					e.find('#delete_discount_line<?php echo $i; ?>').hide();
					e.find('.options_group<?php echo min( $i + 1, 11 ); ?>').show();
					e.find('#add_discount_line<?php echo min( $i + 1, 11 ); ?>').show();
					e.find('#delete_discount_line<?php echo min( $i + 1, 11 ); ?>').show();
					<?php
					$cnt ++;
					}
					}
					if ($cnt >= 10) {
					?>e.find('#add_discount_line6').show();
					<?php
					}
					?>
				});
			</script>
		</div>

	</div>
</div>
