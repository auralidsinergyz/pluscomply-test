<?php

namespace uncanny_learndash_groups;

/**
 * [uo_groups_buy_courses]'s template
 */
?>

<form id="uo-groups-buy-courses" class="buy-courses-form" method="POST"
	  data-bulk-discount='<?php echo wp_json_encode( get_option( SharedFunctions::$bulk_discount_options, array() ) ); ?>'>

	<?php if ( ( ulgm_filter_has_var( '_wpnonce' ) && wp_verify_nonce( ulgm_filter_input( '_wpnonce' ), Utilities::get_plugin_name() ) ) && ulgm_filter_has_var( 'group-id' ) && is_numeric( ulgm_filter_input( 'group-id' ) ) ) { ?>
		<input type="hidden" value="yes" name="modify_license"/>
		<input type="hidden" value="<?php echo intval( ulgm_filter_input( 'group-id' ) ); ?>" name="group_id"/>
	<?php } ?>

	<?php if ( ( ulgm_filter_has_var( '_wpnonce' ) && wp_verify_nonce( ulgm_filter_input( '_wpnonce' ), Utilities::get_plugin_name() ) ) && ulgm_filter_has_var( 'group-id' ) && is_numeric( ulgm_filter_input( 'modify-license' ) ) ) { ?>
		<input type="hidden" value="<?php echo intval( ulgm_filter_input( 'modify-license' ) ); ?>" name="product_id"/>
	<?php } ?>

	<?php wp_nonce_field( Utilities::get_plugin_name(), '_custom_buy_courses' ); ?>

	<?php
	$logic              = true;
	$group_name         = '';
	$existing_courses   = array();
	$is_course_addition = false;

	if ( ( ulgm_filter_has_var( '_wpnonce' ) && wp_verify_nonce( ulgm_filter_input( '_wpnonce' ), Utilities::get_plugin_name() ) ) && ulgm_filter_has_var( 'modify-license' ) && is_user_logged_in() ) {

		$user_id   = wp_get_current_user()->ID;
		$group_ids = learndash_get_administrators_group_ids( $user_id );

		if ( ! learndash_is_group_leader_user( $user_id ) && ! in_array( intval( ulgm_filter_input( 'group-id' ) ), $group_ids ) ) {

			$error = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
			$logic = false;

		} else {

			$group_post = get_post( intval( ulgm_filter_input( 'group-id' ) ) );

			if ( ! empty( $group_post ) && 'groups' === $group_post->post_type ) {

				$group_name   = $group_post->post_title;
				$group_admins = learndash_get_groups_administrators( ulgm_filter_input( 'group-id' ), true );

				if ( ! empty( $group_admins ) ) {

					$group_users = array();

					foreach ( $group_admins as $group_admin ) {
						$group_users[ $group_admin->ID ] = $group_admin->ID;
					}
				}

				if ( ! in_array( $user_id, $group_users ) ) {

					$error = __( 'You do not have permission to manage this group.', 'uncanny-learndash-groups' );
					$logic = false;

				}
			} else {

				$error = __( 'Invalid group.', 'uncanny-learndash-groups' );
				$logic = false;

			}
		}

		$product_post = get_post( intval( ulgm_filter_input( 'modify-license' ) ) );

		if ( empty( $product_post ) || 'product' !== $product_post->post_type ) {

			$error = __( 'Invalid license.', 'uncanny-learndash-groups' );
			$logic = false;

		} else {

			$existing_courses   = get_post_meta( $product_post->ID, SharedFunctions::$license_meta_field, true );
			$is_course_addition = true;

		}
	}

	$total   = 0;
	$min_qty = 1;
	$max_qty = 99999999;
	if ( $logic ) :

		// set up default tax query fragment.
		$tax_query_fragment = array(
			array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => 'courses',
			),
		);

		// check for and add product cat attributes.
		if ( isset( $atts['product_cat'] ) && ! empty( $atts['product_cat'] ) ) {

			$tax_query_fragment[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => array_unique( array_filter( explode( ',', $atts['product_cat'] ), 'intval' ) ),
			);
		}

		// check for and add product tag attributes.
		if ( isset( $atts['product_tag'] ) && ! empty( $atts['product_tag'] ) ) {
			$tax_query_fragment[] = array(
				'taxonomy' => 'product_tag',
				'field'    => 'term_id',
				'terms'    => array_unique( array_filter( explode( ',', $atts['product_tag'] ), 'intval' ) ),
			);
		}

		// check for minimum quantities
		if ( isset( $atts['min_qty'] ) && ! empty( $atts['min_qty'] ) ) {
			$min_qty = absint( $atts['min_qty'] );
		}

		// check for maximum quantities
		if ( isset( $atts['max_qty'] ) && ! empty( $atts['max_qty'] ) ) {
			$max_qty = absint( $atts['max_qty'] );
		}

		// if any of the cats or tags are set, add the relation parameter.
		if ( count( $tax_query_fragment ) > 1 ) {
			$tax_query_fragment = array_merge( array( 'relation' => 'AND' ), $tax_query_fragment );
		}

		$args = apply_filters(
			'ulgm_buy_courses_qry_args',
			array(
				'post_type'      => 'product',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => 8888,
				'tax_query'      => $tax_query_fragment,
			)
		);

		$products = get_posts( $args );

		if ( $products ) {

			?>

			<!-- Group data -->
			<div id="uo-groups-buy--group-data" class="uo-groups-section">
				<div id="uo-groups-buy-courses-data" class="uo-row">
					<div class="uo-groups-section-header">
						<h3><?php _e( 'Group', 'uncanny-learndash-groups' ); ?></h3>
					</div>

					<?php

					$bulk_discounts           = get_option( SharedFunctions::$bulk_discount_options, array() );
					$number_of_bulk_discounts = key_exists( 'discounts', $bulk_discounts ) ? count( $bulk_discounts['discounts'] ) : 0;

					if ( key_exists( 'enabled', $bulk_discounts ) && 'yes' === $bulk_discounts['enabled'] && $number_of_bulk_discounts > 0 ) {
						$has_bulk_discounts = $bulk_discounts['enabled'];
					} else {
						$has_bulk_discounts = false;
					}

					$uo_section_class = ! $has_bulk_discounts ? 'uo-groups--bulk-discount-disabled' : '';

					$learn_dash_labels = new \LearnDash_Custom_Label();
					$course_label      = $learn_dash_labels::get_label( 'courses' );

					$uo_groups_buy_totals_classes = array();

					$uo_groups_has_group_name = ! empty( $group_name );

					$uo_groups_seats_quantity_default = $min_qty;
					if ( $is_course_addition ) {
						$uo_groups_seats_quantity_default = get_post_meta( absint( ulgm_filter_input( 'group-id' ) ), '_ulgm_total_seats', true );
					}

					if ( $has_bulk_discounts ) {
						$uo_groups_buy_totals_classes[] = 'uo-groups-buy__totals--has-bulk-discounts';
					}

					if ( $is_course_addition ) {
						$uo_groups_buy_totals_classes[] = 'uo-groups-buy__totals--has-course-additions';
					}

					?>

					<div class="uo-groups-section-content <?php echo $uo_section_class; ?>">

						<div class="uo-groups--group-data">
							<!-- Group name -->
							<div class="uo-groups--group-name">
								<div class="uo-groups-form-row">
									<div class="uo-groups-form-row--title">
										<?php _e( 'Group name', 'uncanny-learndash-groups' ); ?>
									</div>
									<div class="uo-groups-form-row--element">
										<input 
											class="uo-input" 
											type="text" 
											value="<?php echo $uo_groups_has_group_name ? $group_name : ''; ?>"
											name="_custom_group_name"

											<?php echo $uo_groups_has_group_name ? 'disabled="disabled"' : ''; ?>
											required="required"
										>
									</div>
									<div class="uo-groups-form-row--comment">
										<?php printf( __( 'The name of the group for which you are purchasing the %s.', 'uncanny-learndash-groups' ), strtolower( $course_label ) ); ?>
									</div>
								</div>
							</div>

							<!-- Seats -->
							<div class="uo-groups--group-seats">
								<div class="uo-groups-form-row">
									<div class="uo-groups-form-row--title">
										<?php

										$seats_label = ucfirst( get_option( 'ulgm_per_seat_text_plural', __( 'Seats', 'uncanny-learndash-groups' ) ) );
										$seats_label = ! empty( $seats_label ) ? $seats_label : __( 'Seats', 'uncanny-learndash-groups' );
										echo $seats_label;

										?>
									</div>
									<div class="uo-groups-form-row--element">
										<input type="hidden" name="ulgm_license_min_qty" value="<?php echo $min_qty; ?>">
										<input type="hidden" name="ulgm_license_max_qty" value="<?php echo $max_qty; ?>">
										<input 
											class="uo-input" 
											type="number" 
											value="<?php echo $uo_groups_seats_quantity_default; ?>"
											name="_custom_qty"
											id="uo-groups-buy-course-field-quantity"
											min="<?php echo $min_qty; ?>"
											max="<?php echo $max_qty; ?>"
											required="required"
											<?php echo $is_course_addition ? 'readonly="readonly" disabled="disabled"' : ''; ?>
										>
									</div>
									<div class="uo-groups-form-row--comment">
										<?php printf( __( 'The number of users who require access.', 'uncanny-learndash-groups' ), strtolower( $course_label ) ); ?>
									</div>
								</div>
							</div>
						</div>

						<?php
						if ( $has_bulk_discounts ) {
							include Utilities::get_template( 'frontend-bulk-discount-table.php' );
						}
						?>

					</div>
				</div>
			</div>

			<!-- Select courses -->
			<div id="uo-groups-buy--courses" class="uo-groups-section">
				<div id="uo-groups-buy-courses-select" class="uo-row">
					<div class="uo-groups-section-header">
						<h3>
							<?php
							echo esc_html( $course_label );
							?>
						</h3>
					</div>
					<div class="uo-groups-section-content">
						<div class="uo-groups--courses">
							<div class="uo-groups-box">
								<div class="uo-groups-table">
									<div class="uo-groups-table-header">
										<?php _e( 'Price', 'uncanny-learndash-groups' ); ?>
									</div>
									<div class="uo-groups-table-content">

										<?php

										foreach ( $products as $product ) {
											$product_id = $product->ID;
											$_product   = new \WC_Product( $product_id );
											//$price = wc_get_price_excluding_tax( $_product );
											$price_wt = SharedFunctions::get_custom_product_price( $_product );
											$price    = ! empty( $price_wt ) ? $price_wt : 0;

											?>

											<div class="uo-groups-table-row">

												<div class="uo-groups-table-cell uo-groups-table-name checkbox-required">
													<?php

													if ( in_array( $_product->get_id(), $existing_courses ) ) {
														$price = 0;
														$total += $price;

														?>

														<label class="uo-checkbox">
															<input type="checkbox" class="bb-custom-check"
																   data-price="<?php echo $price; ?>"
																   name="_custom_already_selected_courses[]"
																   disabled="disabled" checked="checked"
																   value="<?php echo $product_id; ?>"/>
															<div class="uo-checkmark"></div>
															<span class="uo-label">
																			<?php echo esc_html( $product->post_title ); ?>
																		</span>
														</label>

														<?php
													} else {
														?>

														<label class="uo-checkbox">
															<input type="checkbox" class="bb-custom-check"
																   data-price="<?php echo $price; ?>"
																   name="_custom_selected_courses[]"
																   value="<?php echo $product_id; ?>"/>
															<div class="uo-checkmark"></div>
															<span class="uo-label">
																			<?php echo esc_html( $product->post_title ); ?>
																		</span>
														</label>

													<?php } ?>

												</div>

												<div class="uo-groups-table-cell uo-groups-table-price">
													<?php
													echo wc_price( $price );
													?>
												</div>

											</div>

											<?php
										}

										?>
									</div>

									<?php if ( $is_course_addition ) { ?>
										<input type="hidden" value="<?php echo $total; ?>"
											   id="uo-groups-buy-course-field-prevorder"/>
									<?php } ?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Total -->
			<div id="uo-groups-buy--totals"
				 class="uo-groups-section <?php echo implode( ' ', $uo_groups_buy_totals_classes ); ?>">
				<div id="uo-groups-buy-courses-total" class="uo-row">
					<div class="uo-groups-section-content">
						<div class="uo-groups--totals">
							<div class="uo-groups-box">
								<div class="uo-groups-table">
									<div class="uo-groups-table-content">
										<!-- Subtotal -->
										<div class="uo-groups-table-row uo-groups-table-row--subtotal">
											<div class="uo-groups-table-cell uo-groups-table-name">
												<h5><?php _e( 'Cart total', 'uncanny-learndash-groups' ); ?></h5></div>
										</div>
										<?php if ( $has_bulk_discounts || $is_course_addition ) { ?>

											<!-- Subtotal -->
											<div class="uo-groups-table-row uo-groups-table-row--subtotal">
												<div class="uo-groups-table-cell uo-groups-table-name"><?php _e( 'Subtotal', 'uncanny-learndash-groups' ); ?></div>
												<div class="uo-groups-table-cell uo-groups-table-price">
													<div id="uo-groups-buy-course-totals-subtotal">
														<?php echo wc_price( 0 ); ?>
													</div>
												</div>
											</div>

										<?php } ?>

										<?php if ( $has_bulk_discounts ) { ?>

											<!-- Bulk discount -->
											<div class="uo-groups-table-row uo-groups-table-row--bulk-discount">
												<div class="uo-groups-table-cell uo-groups-table-name">
													<div id="uo-groups-buy-course-totals-discount-percentage"></div>
													<?php _e( 'Bulk discount', 'uncanny-learndash-groups' ); ?>
												</div>
												<div class="uo-groups-table-cell uo-groups-table-price">
													<div id="uo-groups-buy-course-totals-discount">
														<?php echo wc_price( 0 ); ?>
													</div>
												</div>
											</div>

										<?php } ?>

										<!-- Total -->
										<div class="uo-groups-table-row uo-groups-table-row--total">
											<div class="uo-groups-table-cell uo-groups-table-name"><?php _e( 'Total', 'uncanny-learndash-groups' ); ?></div>
											<div class="uo-groups-table-cell uo-groups-table-price">
												<div id="uo-groups-buy-course-totals-total">
													<?php echo wc_price( 0 ); ?>
												</div>
											</div>
										</div>

									</div>
								</div>

								<?php if ( get_option( 'woocommerce_calc_taxes', 'no' ) === 'yes' ) { ?>
									<div class="uo-groups-table--note">
										<?php _e( 'Taxes will be calculated at checkout.', 'uncanny-learndash-groups' ); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Maybe change this class? -->
			<div id="uo-groups-buy-course-error-notice" class="uo_groups_error"></div>

		<?php } ?>

		<!-- Add to cart -->
		<div id="uo-groups-buy--add-to-cart" class="uo-groups-section">
			<div class="uo-row">

				<?php if ( $products ) { ?>

					<button type="submit" id="submit"
							class="uo-btn"><?php _e( 'Add to cart', 'uncanny-learndash-groups' ); ?></button>

				<?php } else { ?>

					<?php echo sprintf( __( 'Sorry, there are currently no %s available for purchase.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?>

				<?php } ?>

			</div>
		</div>

	<?php else : ?>

		<h3><?php echo $error; ?></h3>

	<?php endif; ?>
</form>
